<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Caracteristica_tipo extends MY_Controller {

	function __construct() {
		parent::__construct();
		if (!isset($this->entidad)) {
			show_404();
		}
		$this->load->model('caracteristica_tipo_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'admin/caracteristica_' . $this->entidad;
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 90),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'caracteristica_tipo_table',
			'source_url' => 'caracteristica_' . $this->entidad . '/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['entidad'] = $this->entidad;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Tipos de características ' . $this->entidad;
		$this->load_template('caracteristica_tipo/caracteristica_tipo_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('caracteristica_tipo.id, caracteristica_tipo.descripcion, caracteristica_tipo.entidad')
			->unset_column('id')
			->from('caracteristica_tipo')
			->where('entidad', $this->entidad)
			->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="caracteristica_' . $this->entidad . '/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item" href="caracteristica_' . $this->entidad . '/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '<li><a class="dropdown-item" href="caracteristica_' . $this->entidad . '/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Eliminar"><i class="fa fa-remove"></i> Eliminar</a></li>'
				. '<li><a class="dropdown-item" href="caracteristica/listar/' . $this->entidad . '/$1"><i class="fa fa-list-alt" id="btn-listar-caracteristicas"></i> Características</a></li>'
				. '</ul></div>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->set_model_validation_rules($this->caracteristica_tipo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->caracteristica_tipo_model->create(array(
					'descripcion' => $this->input->post('descripcion'),
					'entidad' => $this->entidad
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->caracteristica_tipo_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->caracteristica_tipo_model->get_error());
				}
				redirect('caracteristica_' . $this->entidad . '/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('caracteristica_' . $this->entidad . '/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->caracteristica_tipo_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar tipo de característica';
		$this->load->view('caracteristica_tipo/caracteristica_tipo_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$tipo_caracteristica = $this->caracteristica_tipo_model->get(array('id' => $id));
		if (empty($tipo_caracteristica)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->set_model_validation_rules($this->caracteristica_tipo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->caracteristica_tipo_model->update(array(
					'id' => $this->input->post('id'),
					'descripcion' => $this->input->post('descripcion')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->caracteristica_tipo_model->get_msg());
					redirect('caracteristica_' . $this->entidad . '/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('caracteristica_' . $this->entidad . '/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->caracteristica_tipo_model->fields, $tipo_caracteristica);

		$data['tipo_caracteristica'] = $tipo_caracteristica;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar tipo de característica';
		$this->load->view('caracteristica_tipo/caracteristica_tipo_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$tipo_caracteristica = $this->caracteristica_tipo_model->get(array('id' => $id));
		if (empty($tipo_caracteristica)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->caracteristica_tipo_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->caracteristica_tipo_model->get_msg());
				redirect('caracteristica_' . $this->entidad . '/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->caracteristica_tipo_model->fields, $tipo_caracteristica, TRUE);

		$data['tipo_caracteristica'] = $tipo_caracteristica;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar tipo de característica';
		$this->load->view('caracteristica_tipo/caracteristica_tipo_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$tipo_caracteristica = $this->caracteristica_tipo_model->get(array('id' => $id));
		if (empty($tipo_caracteristica)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->caracteristica_tipo_model->fields, $tipo_caracteristica, TRUE);

		$data['tipo_caracteristica'] = $tipo_caracteristica;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver tipo de característica';
		$this->load->view('caracteristica_tipo/caracteristica_tipo_modal_abm', $data);
	}
}
/* End of file Caracteristica_tipo.php */
/* Location: ./application/controllers/Caracteristica_tipo.php */