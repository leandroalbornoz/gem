<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_login extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->load->model('usuarios/access_control_model');
		$this->load->helper(array('url', 'language'));
	}

	function login() {
		$this->session->unset_userdata('usuario');
		$this->session->unset_userdata('rol');
		$this->session->unset_userdata('acceso');
		$this->data['title'] = 'Ingresar';

		$this->form_validation->set_rules('identity', 'Usuario', 'required');
		$this->form_validation->set_rules('password', 'Contraseña', 'required');

		if ($this->form_validation->run() == true) {
			$usuario_login = $this->access_control_model->login($this->input->post('identity'), $this->input->post('password'));
			if ($usuario_login) {
				$user_id = $usuario_login->id;
				$usuario = $this->usuarios_model->get_usuario($user_id);
				$grupos = $this->usuarios_model->get_grupos($user_id);
				if (empty($grupos)) {
					$data['message'] = "Acceso Denegado. No tiene permiso para acceder a la aplicación.";
				} else {
					$this->session->set_userdata('usuario', $usuario);
					if (empty($_GET['redirect_url'])) {
						redirect('escritorio', 'refresh');
					} else {
						redirect(urldecode($_GET['redirect_url']), 'refresh');
					}
				}
				$this->session->set_flashdata('message', 'Bienvenido');
				redirect('/', 'refresh');
			} else {
				$this->session->set_flashdata('message', 'Error al iniciar sesión, por favor intente nuevamente');
				redirect('usuarios/auth_login/login', 'refresh');
			}
		} else {
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'class' => 'has-feedback form-control',
				'placeholder' => 'Usuario',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'class' => 'has-feedback form-control',
				'placeholder' => 'Contraseña',
			);

			$this->_render_page('auth_login/login', $this->data);
		}
	}

	function logout() {
		$this->session->unset_userdata('usuario');
		$this->session->unset_userdata('rol');
		setcookie('loginDTIDGE', '', time() - 3600, '.mendoza.gov.ar');
		redirect(LOGIN_URL, 'refresh');
	}

	function _render_page($view, $data = null, $returnhtml = false) {//I think this makes more sense
		$view_html = $this->load->view($view, $data, $returnhtml);

		if ($returnhtml)
			return $view_html; //This will return html on 3rd argument being true
	}
}
/* End of file Auth_login.php */
/* Location: ./application/modules/usuarios/controllers/Auth_login.php */