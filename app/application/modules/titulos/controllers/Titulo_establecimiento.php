<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Titulo_establecimiento extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('titulos/titulo_establecimiento_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_LINEA, ROL_SUPERVISION, ROL_DOCENTE, ROL_TITULO);
		$this->nav_route = 'titulo/titulo_establecimiento';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(//@todo arreglar anchos de columnas
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 88),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'titulo_establecimiento_table',
			'source_url' => 'titulos/titulo_establecimiento/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Gestión Escuelas Mendoza - Establecimientos';
		$this->load_template('titulos/titulo_establecimiento/titulo_establecimiento_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('titulo_establecimiento.id, titulo_establecimiento.descripcion')
			->unset_column('id')
			->from('titulo_establecimiento')
			->add_column('edit', '<a href="titulos/titulo_establecimiento/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="titulos/titulo_establecimiento/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="titulos/titulo_establecimiento/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->set_model_validation_rules($this->titulo_establecimiento_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->titulo_establecimiento_model->create(array(
					'descripcion' => $this->input->post('descripcion')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->titulo_establecimiento_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->titulo_establecimiento_model->get_error());
				}
				redirect('titulos/titulo_establecimiento/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('titulos/titulo_establecimiento/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->titulo_establecimiento_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar establecimiento';
		$this->load->view('titulos/titulo_establecimiento/titulo_establecimiento_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$establecimiento = $this->titulo_establecimiento_model->get(array('id' => $id));
		if (empty($establecimiento)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->set_model_validation_rules($this->titulo_establecimiento_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->titulo_establecimiento_model->update(array(
					'id' => $this->input->post('id'),
					'descripcion' => $this->input->post('descripcion')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->titulo_establecimiento_model->get_msg());
					redirect('titulos/titulo_establecimiento/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('titulos/titulo_establecimiento/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->titulo_establecimiento_model->fields, $establecimiento);

		$data['establecimiento'] = $establecimiento;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar establecimiento';
		$this->load->view('titulos/titulo_establecimiento/titulo_establecimiento_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$establecimiento = $this->titulo_establecimiento_model->get(array('id' => $id));
		if (empty($establecimiento)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->titulo_establecimiento_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->titulo_establecimiento_model->get_msg());
				redirect('titulos/titulo_establecimiento/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->titulo_establecimiento_model->get_error());
				redirect('titulos/titulo_establecimiento/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->titulo_establecimiento_model->fields, $establecimiento, TRUE);
		$data['establecimiento'] = $establecimiento;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar establecimiento';
		$this->load->view('titulos/titulo_establecimiento/titulo_establecimiento_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$establecimiento = $this->titulo_establecimiento_model->get(array('id' => $id));
		if (empty($establecimiento)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->titulo_establecimiento_model->fields, $establecimiento, TRUE);

		$data['establecimiento'] = $establecimiento;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver establecimiento';
		$this->load->view('titulos/titulo_establecimiento/titulo_establecimiento_modal_abm', $data);
	}
}
/* End of file Titulo_establecimiento.php */
/* Location: ./application/modules/titulos/controllers/Titulo_establecimiento.php */