<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Obra_social extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('obra_social_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'par/obra_social';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Código', 'data' => 'codigo', 'width' => 20),
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 40),
				array('label' => 'Descripción Corta', 'data' => 'descripcion', 'width' => 28),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'obra_social_table',
			'source_url' => 'obra_social/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Obras sociales';
		$this->load_template('obra_social/obra_social_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('obra_social.id, obra_social.codigo, obra_social.descripcion')
			->unset_column('id')
			->from('obra_social')
			->add_column('edit', '<a href="obra_social/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="obra_social/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="obra_social/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->set_model_validation_rules($this->obra_social_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->obra_social_model->create(array(
					'codigo' => $this->input->post('codigo'),
					'descripcion' => $this->input->post('descripcion'),
					'descripcion_corta' => $this->input->post('descripcion_corta')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->obra_social_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->obra_social_model->get_error());
				}
				redirect('obra_social/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('obra_social/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->obra_social_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar obra social';
		$this->load->view('obra_social/obra_social_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$obra_social = $this->obra_social_model->get(array('id' => $id));
		if (empty($obra_social)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->set_model_validation_rules($this->obra_social_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->obra_social_model->update(array(
					'id' => $this->input->post('id'),
					'codigo' => $this->input->post('codigo'),
					'descripcion' => $this->input->post('descripcion'),
					'descripcion_corta' => $this->input->post('descripcion_corta')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->obra_social_model->get_msg());
					redirect('obra_social/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('obra_social/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->obra_social_model->fields, $obra_social);

		$data['obra_social'] = $obra_social;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar obra social';
		$this->load->view('obra_social/obra_social_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$obra_social = $this->obra_social_model->get(array('id' => $id));
		if (empty($obra_social)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->obra_social_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->obra_social_model->get_msg());
				redirect('obra_social/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->obra_social_model->fields, $obra_social, TRUE);

		$data['obra_social'] = $obra_social;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar obra social';
		$this->load->view('obra_social/obra_social_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$obra_social = $this->obra_social_model->get(array('id' => $id));
		if (empty($obra_social)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->obra_social_model->fields, $obra_social, TRUE);

		$data['obra_social'] = $obra_social;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver obra social';
		$this->load->view('obra_social/obra_social_modal_abm', $data);
	}
}
/* End of file Obra_social.php */
/* Location: ./application/controllers/Obra_social.php */