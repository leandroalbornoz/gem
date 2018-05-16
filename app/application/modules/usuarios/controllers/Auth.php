<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->load->helper(array('url', 'language'));
	}

	function login() {
		$this->session->unset_userdata('acceso');
//		$session_id = $this->input->cookie('loginDTIDGE');
		$error_msg = FALSE;
//		if (empty($session_id)) {
//			$error_msg = "Acceso denegado. Debe ingresar a través de Intranet de la DGE.";
//		} else {
//			$db_acontrol = $this->load->database('acontrol', TRUE);
//			$session = $db_acontrol->where('id', $session_id)->get('access_control.YiiSession')->row();
//			if (empty($session) || $session->data === '__flash|a:0:{}') {
//				$error_msg = "Acceso denegado. Debe ingresar a través de Intranet de la DGE.";
//			} elseif ($session->expire < (time())) {
//				$error_msg = "Su sesión ha caducado. Debe ingresar a través de Intranet de la DGE.";
//			}
//		}
		if ($error_msg) {
			$data['message'] = $error_msg;
		} else {
//			$user_id = $session->user_id;
			$user_id=3045;
			$usuario = $this->usuarios_model->get_usuario($user_id);
			if (!empty($usuario)) {
//				$grupos = $this->usuarios_model->get_grupos($user_id);
//				if (empty($grupos)) {
//					$data['message'] = "Acceso Denegado. No tiene permiso para acceder a la aplicación.";
//				} else {
					$this->session->set_userdata('usuario', $usuario);
					if (empty($_GET['redirect_url'])) {
						redirect('escritorio', 'refresh');
					} else {
						redirect(urldecode($_GET['redirect_url']), 'refresh');
					}
//				}
			} else {
				$data['message'] = "Acceso Denegado. Debe ingresar a través de Intranet de la DGE.";
			}
		}
		$this->_render_page('usuarios/auth/login', $data);
	}

	function logout() {
		$this->session->unset_userdata('usuario');
		$this->session->unset_userdata('rol');
		setcookie('loginDTIDGE', '', time() - 3600, '.mendoza.edu.ar');
		redirect(LOGIN_URL, 'refresh');
	}

	function _render_page($view, $data = null, $returnhtml = false) {//I think this makes more sense
		$view_html = $this->load->view($view, $data, $returnhtml);

		if ($returnhtml)
			return $view_html; //This will return html on 3rd argument being true
	}
}
/* End of file Auth.php */
/* Location: ./application/modules/usuarios/controllers/Auth.php */