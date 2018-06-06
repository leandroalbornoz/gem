<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Regimen extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('regimen_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'admin/regimen';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Codigo', 'data' => 'codigo', 'width' => 20),
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 58),
				array('label' => 'Tipo', 'data' => 'regimen_tipo', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'regimen_table',
			'source_url' => 'regimen/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Regímenes';
		$this->load_template('regimen/regimen_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('regimen.id, regimen.codigo, regimen.descripcion, regimen.regimen_tipo_id, regimen_tipo.descripcion as regimen_tipo')
			->unset_column('id')
			->from('regimen')
			->join('regimen_tipo', 'regimen_tipo.id = regimen.regimen_tipo_id', 'left')
			->where('regimen.planilla_modalidad_id', 1)
			->add_column('edit', '<a href="regimen/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="regimen/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="regimen/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('regimen_tipo_model');
		$this->array_regimen_tipo_control = $array_regimen_tipo = $this->get_array('regimen_tipo', 'descripcion', 'id', null, array('' => '-- Seleccionar tipo de régimen --'));

		$this->set_model_validation_rules($this->regimen_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->regimen_model->create(array(
					'codigo' => $this->input->post('codigo'),
					'descripcion' => $this->input->post('descripcion'),
					'planilla_modalidad_id' => 1,
					'regimen_tipo_id' => $this->input->post('regimen_tipo')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->regimen_model->get_msg());
				} else {
					$errors = 'Ocurrió un error al intentar agregar.';
					if ($this->regimen_model->get_error())
						$errors .= '<br>' . $this->regimen_model->get_error();
					$this->session->set_flashdata('error', $errors);
				}
				redirect('regimen/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('regimen/listar', 'refresh');
			}
		}

		$this->regimen_model->fields['regimen_tipo']['array'] = $array_regimen_tipo;
		$data['fields'] = $this->build_fields($this->regimen_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar régimen';
		$this->load->view('regimen/regimen_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$regimen = $this->regimen_model->get(array('id' => $id));
		if (empty($regimen)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->load->model('regimen_tipo_model');
		$this->array_regimen_tipo_control = $array_regimen_tipo = $this->get_array('regimen_tipo', 'descripcion');

		$this->set_model_validation_rules($this->regimen_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->regimen_model->update(array(
					'id' => $this->input->post('id'),
					'codigo' => $this->input->post('codigo'),
					'descripcion' => $this->input->post('descripcion'),
					'regimen_tipo_id' => $this->input->post('regimen_tipo')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->regimen_model->get_msg());
				} else {
					$errors = 'Ocurrió un error al intentar editar.';
					if ($this->regimen_model->get_error())
						$errors .= '<br>' . $this->regimen_model->get_error();
					$this->session->set_flashdata('error', $errors);
				}
				redirect('regimen/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('regimen/listar', 'refresh');
			}
		}
		$this->regimen_model->fields['regimen_tipo']['array'] = $array_regimen_tipo;

		$data['fields'] = $this->build_fields($this->regimen_model->fields, $regimen);
		$data['regimen'] = $regimen;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar régimen';
		$this->load->view('regimen/regimen_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$regimen = $this->regimen_model->get_one($id);
		if (empty($regimen)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->regimen_model->delete(array('id' => $this->input->post('id')));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->regimen_model->get_msg());
			} else {
				$errors = 'Ocurrió un error al intentar eliminar.';
				if ($this->regimen_model->get_error())
					$errors .= '<br>' . $this->regimen_model->get_error();
				$this->session->set_flashdata('error', $errors);
			}
			redirect('regimen/listar', 'refresh');
		}
		$data['fields'] = $this->build_fields($this->regimen_model->fields, $regimen, TRUE);
		$data['regimen'] = $regimen;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar régimen';
		$this->load->view('regimen/regimen_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$regimen = $this->regimen_model->get_one($id);
		if (empty($regimen)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->regimen_model->fields, $regimen, TRUE);
		$data['regimen'] = $regimen;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver régimen';
		$this->load->view('regimen/regimen_modal_abm', $data);
	}
}
/* End of file Regimen.php */
/* Location: ./application/controllers/Regimen.php */