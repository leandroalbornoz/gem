<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inasistencia_tipo extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('inasistencia_tipo_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'par/inasistencia_tipo';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 88),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'inasistencia_tipo_table',
			'source_url' => 'inasistencia_tipo/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Tipos de inasistencias';
		$this->load_template('inasistencia_tipo/inasistencia_tipo_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('inasistencia_tipo.id, inasistencia_tipo.descripcion')
			->unset_column('id')
			->from('inasistencia_tipo')
			->add_column('edit', '<a href="inasistencia_tipo/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="inasistencia_tipo/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="inasistencia_tipo/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->set_model_validation_rules($this->inasistencia_tipo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->inasistencia_tipo_model->create(array(
					'descripcion' => $this->input->post('descripcion')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->inasistencia_tipo_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->inasistencia_tipo_model->get_error());
				}
				redirect('inasistencia_tipo/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('inasistencia_tipo/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->inasistencia_tipo_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar tipo de inasistencia';
		$this->load->view('inasistencia_tipo/inasistencia_tipo_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$tipo_inasistencia = $this->inasistencia_tipo_model->get(array('id' => $id));
		if (empty($tipo_inasistencia)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->set_model_validation_rules($this->inasistencia_tipo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->inasistencia_tipo_model->update(array(
					'id' => $this->input->post('id'),
					'descripcion' => $this->input->post('descripcion')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->inasistencia_tipo_model->get_msg());
					redirect('inasistencia_tipo/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('inasistencia_tipo/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->inasistencia_tipo_model->fields, $tipo_inasistencia);

		$data['tipo_inasistencia'] = $tipo_inasistencia;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar tipo de inasistencia';
		$this->load->view('inasistencia_tipo/inasistencia_tipo_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
			return;
		}
		$tipo_inasistencia = $this->inasistencia_tipo_model->get(array('id' => $id));
		if (empty($tipo_inasistencia)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->inasistencia_tipo_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->inasistencia_tipo_model->get_msg());
				redirect('inasistencia_tipo/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->inasistencia_tipo_model->fields, $tipo_inasistencia, TRUE);

		$data['tipo_inasistencia'] = $tipo_inasistencia;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar tipo de inasistencia';
		$this->load->view('inasistencia_tipo/inasistencia_tipo_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$tipo_inasistencia = $this->inasistencia_tipo_model->get(array('id' => $id));
		if (empty($tipo_inasistencia)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->inasistencia_tipo_model->fields, $tipo_inasistencia, TRUE);

		$data['tipo_inasistencia'] = $tipo_inasistencia;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver tipo de inasistencia';
		$this->load->view('inasistencia_tipo/inasistencia_tipo_modal_abm', $data);
	}
}
/* End of file Inasistencia_tipo.php */
/* Location: ./application/controllers/Inasistencia_tipo.php */