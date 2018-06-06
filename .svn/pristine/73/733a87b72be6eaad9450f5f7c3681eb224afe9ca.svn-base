<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rol extends MY_Controller {

	public $sin_rol = TRUE;

	function __construct() {
		parent::__construct();
	}

	public function seleccionar() {
		$roles = $this->usuarios_model->get_roles($this->usuario);
		$array_rol = array();
		foreach ($roles as $rol) {
			$array_rol[$rol->id] = "$rol->nombre $rol->entidad";
		}
		$this->array_rol_control = $array_rol;
		$model = new stdClass();
		$model->fields = array(
			'rol' => array('label' => 'Rol', 'input_type' => 'combo', 'array' => $array_rol)
		);
		$redirect_url = $this->input->post_get('redirect_url');
		$this->set_model_validation_rules($model);
		if ($this->form_validation->run() === TRUE) {
			$rol_id = $this->input->post('rol');
			$rol = $this->usuarios_model->set_rol_activo($this->usuario, $rol_id, $this->rol);
			if (!empty($rol)) {
				$this->session->set_userdata('rol', $rol);
				$this->session->set_flashdata('message', 'Rol seleccionado correctamente');
			} else {
				$this->session->set_flashdata('error', 'Error al seleccionar rol');
			}
//			if (empty($redirect_url)) {
			redirect('escritorio', 'refresh');
//			} else {
//				redirect(urldecode($redirect_url), 'refresh');
//			}
		}
//		if (empty($roles)) {
//			$this->session->set_flashdata('error', 'No se encuentra asociada ninguna Escuela para trabajar.');
//			redirect('escritorio', 'refresh');
//		} else if (count($roles) == 1) {
//			$escuela = $this->escuelas_model->get_escuela($escuelas_permitidas[0]);
//			$this->session->set_userdata('nroesc', $escuela['id']);
//			$this->session->set_userdata('nombreesc', $escuela['nombre']);
//			if (empty($redirect_url)) {
//				redirect('escritorio', 'refresh');
//			} else {
//				redirect(urldecode($redirect_url), 'refresh');
//			}
//		}
		$data['redirect_url'] = $redirect_url;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($model->fields);
		$data['title'] = TITLE . ' - Seleccionar rol';
		$this->load_template('usuarios/rol/rol_seleccionar', $data);
	}

	public function modal_seleccionar() {
		$roles = $this->usuarios_model->get_roles($this->usuario);
		$array_rol = array();
		foreach ($roles as $rol) {
			$array_rol[$rol->id] = "$rol->nombre $rol->entidad";
		}
		$this->array_rol_control = $array_rol;
		$model = new stdClass();
		$model->fields = array(
			'rol' => array('label' => 'Rol', 'input_type' => 'combo', 'array' => $array_rol)
		);
		$redirect_url = $this->input->post_get('redirect_url');
		$this->set_model_validation_rules($model);
		if ($this->form_validation->run() === TRUE) {
			$rol_id = $this->input->post('rol');
			$rol = $this->usuarios_model->set_rol_activo($this->usuario, $rol_id, $this->rol);
			if (!empty($rol)) {
				$this->session->set_userdata('rol', $rol);
				$this->session->set_flashdata('message', 'Rol seleccionado correctamente');
			} else {
				$this->session->set_flashdata('error', 'Error al seleccionar rol');
			}
			if (empty($redirect_url)) {
				redirect('escritorio', 'refresh');
			} else {
				redirect(urldecode($redirect_url), 'refresh');
			}
		}
//		if (empty($roles)) {
//			$this->session->set_flashdata('error', 'No se encuentra asociada ninguna Escuela para trabajar.');
//			redirect('escritorio', 'refresh');
//		} else if (count($roles) == 1) {
//			$escuela = $this->escuelas_model->get_escuela($escuelas_permitidas[0]);
//			$this->session->set_userdata('nroesc', $escuela['id']);
//			$this->session->set_userdata('nombreesc', $escuela['nombre']);
//			if (empty($redirect_url)) {
//				redirect('escritorio', 'refresh');
//			} else {
//				redirect(urldecode($redirect_url), 'refresh');
//			}
//		}
		$data['redirect_url'] = $redirect_url;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($model->fields);
		$data['title'] = 'Seleccionar rol';
		$this->load->view('usuarios/rol/rol_modal_seleccionar', $data);
	}
}