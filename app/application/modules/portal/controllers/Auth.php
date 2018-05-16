<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->load->model('portal/access_control_model');
		$this->load->helper(array('url', 'language'));
	}

	public function login() {
		$this->session->unset_userdata('usuario');
		$this->session->unset_userdata('rol');
		$this->session->unset_userdata('acceso');
		$this->data['title'] = 'Ingresar';

		$this->form_validation->set_rules('usuario', 'Usuario', 'required');
		$this->form_validation->set_rules('password', 'Contraseña', 'required');

		if ($this->form_validation->run() == true) {
			$usuario_login = $this->access_control_model->login($this->input->post('usuario'), $this->input->post('password'));
			if ($usuario_login && isset($usuario_login->active) && $usuario_login->active != 0) {
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
				if (isset($usuario_login->active) && $usuario_login->active == 0) {
					$this->session->set_flashdata('error', 'Error al iniciar sesión, la cuenta no ha sido activada, por favor revisar su email');
					redirect('portal/auth/login', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Error al iniciar sesión, por favor intente nuevamente');
					redirect('portal/auth/login', 'refresh');
				}
			}
		} else {
			$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
			$this->data['message'] = $this->session->flashdata('message');
			$this->data['usuario'] = array('name' => 'usuario',
				'id' => 'usuario',
				'type' => 'text',
				'name' => 'usuario',
				'class' => 'has-feedback form-control',
				'placeholder' => 'Usuario',
				'value' => $this->form_validation->set_value('usuario'),
			);
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'name' => 'password',
				'class' => 'has-feedback form-control',
				'placeholder' => 'Contraseña',
			);

			$this->_render_page('auth/login', $this->data);
		}
	}

	private function prueba_pass($escuela_id = NULL) {
		$this->load->model('division_model');

		$divisiones = $this->division_model->get_by_divisiones($escuela_id);

		foreach ($divisiones as $division) {
			$this->load->helper('string');
			$clave_curso = random_string('alnum', 8);

			$this->db->trans_begin();
			$this->db->set('clave', $clave_curso);
			$this->db->where('id', $division->id);
			$this->db->update('division');
			if ($this->db->trans_status()) {
				$this->db->trans_commit();
			} else {
				$this->db->trans_rollback();
			}
		}
	}

	public function verificar_usuario_padre() {
		if (!PORTAL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->session->unset_userdata('usuario');
		$this->session->unset_userdata('rol');
		$this->session->unset_userdata('acceso');
		$this->data['title'] = TITLE . ' - Portal';

		$this->form_validation->set_rules('dni_padres', 'DNI padre', 'required');
		$this->form_validation->set_rules('dni_alumno', 'DNI alumno', 'required');
		$this->form_validation->set_rules('num_escuela', 'Numero escuela', 'required');

		if ($this->form_validation->run() == true) {
			$padre_verificado = $this->access_control_model->login_portal($this->input->post('dni_padres'), $this->input->post('dni_alumno'), $this->input->post('num_escuela'));
			if ($padre_verificado) {
				$usuario_existente = $this->access_control_model->usuario_existente($padre_verificado->padre_dni);
				if (!empty($usuario_existente)) {
					$usuario_creado = $this->access_control_model->rol_creado($usuario_existente->usuario_id);
					if (!empty($usuario_creado)) {
						$data['datos'] = $padre_verificado;
						$data['usuario'] = $usuario_existente;
						$data['usuario_creado'] = $usuario_creado;
						$data['aviso'] = "Datos verificados correctamente, usuario encontrado. Ya posee el rol asignado, por favor ingresar al sistema con el usuario y contraseña establecidos.";
						$data['btn_text'] = NULL;
						$this->load->view('portal/auth/crear_usuario_padre', $data);
						return;
					}
					$data['datos'] = $padre_verificado;
					$data['usuario'] = $usuario_existente;
					$data['aviso'] = "Datos verificados correctamente, usuario encontrado.";
					$data['btn_text'] = "Asignar rol y continuar";
					$this->load->view('portal/auth/crear_usuario_padre', $data);
				} else {
					return $this->usuario_padre($padre_verificado);
				}
			} else {
				$this->session->set_flashdata('error', "Verificación incorrecta, los datos ingresados no coinciden. Por favor comunicarse con la escuela para corroborar los datos.");
				redirect('portal/auth/verificar_usuario_padre', 'refresh');
			}
		} else {
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['error'] = $this->session->flashdata('error');

			$this->data['dni_padres'] = array('name' => 'dni_padres',
				'id' => 'dni_padres',
				'type' => 'text',
				'class' => 'has-feedback form-control',
				'placeholder' => 'DNI padre (sin puntos)',
				'value' => $this->form_validation->set_value('dni_padres'),
			);
			$this->data['dni_alumno'] = array('name' => 'dni_alumno',
				'id' => 'dni_alumno',
				'type' => 'text',
				'class' => 'has-feedback form-control',
				'placeholder' => 'DNI alumno (sin puntos)',
				'value' => $this->form_validation->set_value('dni_alumno'),
			);
			$this->data['num_escuela'] = array('name' => 'num_escuela',
				'id' => 'num_escuela',
				'type' => 'text',
				'class' => 'has-feedback form-control',
				'placeholder' => 'Numero escuela (sin guiones)',
				'value' => $this->form_validation->set_value('num_escuela'),
			);

			$this->_render_page('auth/login_padres', $this->data);
		}
	}

	private function prueba_mail() {
		$this->load->helper('string');
		$password = random_string('alnum', 8);
		$activation_code = random_string('alnum', 12);
		$data = array(
			'username' => 'nanoquiroga180@gmail.com',
			'password' => $password,
//				'activation_link' => "http://testing.mendoza.edu.ar/gem/portal/auth/activacion/$activation_code"
			'activation_link' => BASE_URL . "portal/auth/activacion/$activation_code"
		);
		$this->load->library('email');
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => '192.168.31.6',
//			'smtp_user' => 'notificaciones-gem@mendoza.edu.ar',
			'smtp_user' => 'dge-sistemas@mendoza.edu.ar',
//			'smtp_pass' => 'nr52pq40',
//				'smtp_user' => 'cchiappone@mendoza.gov.ar',
//				'smtp_pass' => '9fd9dbeab36a593.',
//				'smtp_host' => 'smtp.mendoza.gov.ar',
//				'smtp_port' => 25,
			'mailtype' => 'html',
			'charset' => 'utf-8'
		);

		$this->email->initialize($config);

		$this->email->from('dge-sistemas@mendoza.edu.ar', 'Sistemas - DGE');
//		$this->email->from('notificaciones-gem@mendoza.edu.ar', 'Sistemas - DGE');
		$this->email->to($this->input->post('mail_padres'));
//		$this->email->to('nanoquiroga180@gmail.com'); //ggingins@mendoza.gov.ar,gusgins@gmail.com,,vledonne@mendoza.gov.ar');
		$this->email->subject('Creación de cuenta.');
		$content = trim_html($this->load->view('portal/auth/mail', $data, TRUE));
		//$content = $this->load->view('portal/auth/mail', $data);
		$this->email->message($content);
		$this->email->set_alt_message(html_entity_decode(str_replace(array('|a', '|tr|', '</tr>'), array('<a', '', '\r\n'), strip_tags(str_replace(array('<i>', '</i>', '<a', '<tr>'), array('_', '_', '|a', '|tr|'), $content)))));
		$this->email->send();
		print_r($this->email->print_debugger());
		exit();
	}

	public function crear_usuario_padre($mail_padres, $cuil_padres) {
		if (!PORTAL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->session->unset_userdata('usuario');
		$this->session->unset_userdata('rol');
		$this->session->unset_userdata('acceso');
		$this->data['title'] = TITLE . ' - Portal';
		$verificar_mail = $this->access_control_model->mail_existente($mail_padres);
		$verificar_cuil = $this->access_control_model->cuil_existente($cuil_padres);

		if (empty($verificar_mail) && empty($verificar_cuil) && $this->verificaCuil($cuil_padres)) {
			$this->load->helper('string');

			$documento = substr($this->input->post('cuil_padres'), 3, 8);
			$password = random_string('alnum', 8);
			$activation_code = random_string('alnum', 12);
			$picture = file_get_contents(DIRECTORIO . 'img/generales/usuario.png');
			$password_cryp = $this->generatePasswordHash($password);

			$db_acontrol = $this->load->database('acontrol', TRUE);
			$db_acontrol->trans_begin();
			$db_acontrol->set('ip_address', $this->input->ip_address());
			$db_acontrol->set('usuario', $this->input->post('mail_padres'));
			$db_acontrol->set('password', $password_cryp);
			$db_acontrol->set('activation_code', $activation_code);
			$db_acontrol->set('authKey', "");
			$db_acontrol->set('accessToken', "");
			$db_acontrol->set('picture', $picture);
			$db_acontrol->set('active', 0);
			$db_acontrol->insert('usuario');
			$usuario_id = $db_acontrol->insert_id();

			$db_acontrol->set('usuario_id', $usuario_id);
			$db_acontrol->set('rol_id', 51);
			$db_acontrol->insert('usuario_rol');

			$db_acontrol->set('usuario_id', $usuario_id);
			$db_acontrol->set('cuil', $this->input->post('cuil_padres'));
			$db_acontrol->set('documento', $documento);
			$db_acontrol->insert('usuario_persona');
			$usuario_persona_id = $db_acontrol->insert_id();

			$this->db->trans_begin();
			$this->db->set('id', $usuario_id);
			$this->db->set('ip_address', $this->input->ip_address());
			$this->db->set('usuario', $this->input->post('mail_padres'));
			$this->db->set('password', $password_cryp);
			$this->db->set('activation_code', $activation_code);
			$this->db->set('authKey', "");
			$this->db->set('accessToken', "");
			$this->db->set('picture', $picture);
			$this->db->set('active', 0);
			$this->db->insert('usuario');

			$this->db->set('usuario_id', $usuario_id);
			$this->db->set('rol_id', 24);
			$this->db->set('activo', 1);
			$this->db->insert('usuario_rol');

			$this->db->set('id', $usuario_persona_id);
			$this->db->set('usuario_id', $usuario_id);
			$this->db->set('cuil', $this->input->post('cuil_padres'));
			$this->db->set('documento', $documento);
			$this->db->insert('usuario_persona');

			$data = array(
				'username' => $this->input->post('mail_padres'),
				'password' => $password,
//				'activation_link' => "http://testing.mendoza.edu.ar/gem/portal/auth/activacion/$activation_code"
				'activation_link' => BASE_URL . "portal/auth/activacion/$activation_code"
			);
			$this->load->library('email');
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => '192.168.31.6',
//				'smtp_user' => 'notificaciones-gem@mendoza.edu.ar',
				'smtp_user' => 'dge-sistemas@mendoza.edu.ar',
//				'smtp_pass' => 'nr52pq40',
//				'smtp_user' => 'cchiappone@mendoza.gov.ar',
//				'smtp_pass' => '9fd9dbeab36a593.',
//				'smtp_host' => 'smtp.mendoza.gov.ar',
//				'smtp_port' => 25,
				'mailtype' => 'html',
				'charset' => 'utf-8'
			);

			$this->email->initialize($config);

			$this->email->from('dge-sistemas@mendoza.edu.ar', 'Sistemas - DGE');
//			$this->email->from('notificaciones-gem@mendoza.edu.ar', 'Sistemas - DGE');
			$this->email->to($this->input->post('mail_padres'));
//			$this->email->to('nanoquiroga180@gmail.com'); //ggingins@mendoza.gov.ar,gusgins@gmail.com,,vledonne@mendoza.gov.ar');
			$this->email->subject('Creación de cuenta.');
			$content = trim_html($this->load->view('portal/auth/mail', $data, TRUE));
			//$content = $this->load->view('portal/auth/mail', $data);
			$this->email->message($content);
			$this->email->set_alt_message(html_entity_decode(str_replace(array('|a', '|tr|', '</tr>'), array('<a', '', '\r\n'), strip_tags(str_replace(array('<i>', '</i>', '<a', '<tr>'), array('_', '_', '|a', '|tr|'), $content)))));
			$this->email->send();
			print_r($this->email->print_debugger());
			//exit();
			//return $content;
			//return;

			if ($db_acontrol->trans_status() && $this->db->trans_status()) {
				$db_acontrol->trans_commit();
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'El usuario se creó correctamente. Acceda a su correo para activar la cuenta.');
				redirect("portal/auth/login", 'refresh');
			} else {
				$db_acontrol->trans_rollback();
				$this->db->trans_rollback();
				$this->session->set_flashdata('message', 'Ocurrió un error al crear su cuenta. Por favor intente nuevamente.');
				redirect("portal/auth/login", 'refresh');
			}
		} else {
			$error = "Error de creación de cuenta." . (!empty($verificar_mail) ? "<br>El Mail ingresado ya se encuentra registrado." : "") . (!empty($verificar_cuil) ? "<br>El Cuil ingresado ya se encuentra registrado." : "") . (($this->verificaCuil($cuil_padres) != TRUE) ? "<br>El Cuil ingresado no es válido." : "");

			$padre_verificado = $this->access_control_model->login_portal($this->input->post('dni_padres'), $this->input->post('dni_alumno'), $this->input->post('num_escuela'));
			if ($padre_verificado) {
				return $this->usuario_padre($padre_verificado, $error);
			} else {
				$this->session->set_flashdata('error', $error);
				redirect('portal/auth/verificar_usuario_padre', 'refresh');
			}
		}
	}

	private function usuario_padre($padre_verificado, $error = '') {
		if (!PORTAL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($padre_verificado)) {
			show_404();
		}
		$this->form_validation->set_rules('cuil_padres', 'Cuil', 'required');
		$this->form_validation->set_rules('mail_padres', 'Mail', 'required');

		if (!empty($padre_verificado->padre_cuil)) {
			$data['cuil_padres'] = array('name' => 'cuil_padres',
				'id' => 'cuil_padres',
				'type' => 'text',
				'value' => (!empty($padre_verificado->padre_cuil) ? "$padre_verificado->padre_cuil" : ""),
				'class' => 'has-feedback form-control',
				'placeholder' => 'Cuil',
				'tabindex' => '-1');
		} else {
			$data['cuil_padres'] = array('name' => 'cuil_padres',
				'id' => 'cuil_padres',
				'type' => 'text',
				'value' => (!empty($padre_verificado->padre_cuil) ? "$padre_verificado->padre_cuil" : ""),
				'class' => 'has-feedback form-control',
				'placeholder' => 'Cuil',
			);
		}
		$data['mail_padres'] = array('name' => 'mail_padres',
			'id' => 'mail_padres',
			'type' => 'text',
			'class' => 'has-feedback form-control',
			'placeholder' => 'Mail',
			'value' => (!empty($padre_verificado->padre_mail) ? "$padre_verificado->padre_mail" : ""),
		);
		$data['aviso'] = "Datos verificados correctamente, complete los datos solicitados para la creación de usuario.";
		$data['datos'] = $padre_verificado;
		$data['dni_padres'] = $this->input->post('dni_padres');
		$data['dni_alumno'] = $this->input->post('dni_alumno');
		$data['num_escuela'] = $this->input->post('num_escuela');
		if ($this->input->post('mail_padres') != NULL && $this->input->post('cuil_padres') != NULL && empty($error)) {
			$mail_padres = $this->input->post('mail_padres');
			$cuil_padres = $this->input->post('cuil_padres');
			return $this->crear_usuario_padre($mail_padres, $cuil_padres);
		}
		$data['error'] = $error;
		$data['btn_text'] = "Crear usuario";
		$this->load->view('portal/auth/crear_usuario_padre', $data);
	}

	public function verificar_usuario_alumno() {
		if (!PORTAL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->session->unset_userdata('usuario');
		$this->session->unset_userdata('rol');
		$this->session->unset_userdata('acceso');
		$this->data['title'] = TITLE . ' - Portal';

		$this->form_validation->set_rules('dni_alumno', 'DNI alumno', 'required');
		$this->form_validation->set_rules('num_escuela', 'Numero escuela', 'required');
		$this->form_validation->set_rules('clave_division', 'Clave division', 'required');

		if ($this->form_validation->run() == true) {
			$alumno_verificado = $this->access_control_model->login_portal_alumno($this->input->post('dni_alumno'), $this->input->post('clave_division'), $this->input->post('num_escuela'));
			if ($alumno_verificado) {
				$usuario_existente = $this->access_control_model->usuario_existente($alumno_verificado->documento);
				if (!empty($usuario_existente)) {
					$usuario_creado = $this->access_control_model->rol_creado($usuario_existente->usuario_id);
					if (!empty($usuario_creado)) {
						$data['datos'] = $alumno_verificado;
						$data['usuario'] = $usuario_existente;
						$data['usuario_creado'] = $usuario_creado;
						$data['aviso'] = "Datos verificados correctamente, usuario encontrado. Ya posee el rol asignado, por favor ingresar al sistema con el usuario y contraseña establecidos.";
						$data['btn_text'] = NULL;
						$this->load->view('portal/auth/crear_usuario_alumno', $data);
						return;
					}
					$data['datos'] = $alumno_verificado;
					$data['usuario'] = $usuario_existente;
					$data['aviso'] = "Datos verificados correctamente, usuario encontrado.";
					$data['btn_text'] = "Asignar rol y continuar";
					$this->load->view('portal/auth/crear_usuario_alumno', $data);
				} else {
					return $this->usuario_alumno($alumno_verificado);
				}
			} else {
				$this->session->set_flashdata('error', "Verificación incorrecta, los datos ingresados no coinciden. Por favor comunicarse con la escuela para corroborar los datos.");
				redirect('portal/auth/verificar_usuario_alumno', 'refresh');
			}
		} else {
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['error'] = $this->session->flashdata('error');
			$this->data['dni_alumno'] = array('name' => 'dni_alumno',
				'id' => 'dni_alumno',
				'type' => 'text',
				'class' => 'has-feedback form-control',
				'placeholder' => 'DNI alumno (sin puntos)',
				'value' => $this->form_validation->set_value('dni_alumno'),
			);
			$this->data['clave_division'] = array('name' => 'clave_division',
				'id' => 'clave_division',
				'type' => 'text',
				'class' => 'has-feedback form-control',
				'placeholder' => 'Clave Division (Informada por Escuela)',
				'value' => $this->form_validation->set_value('clave_division'),
			);
			$this->data['num_escuela'] = array('name' => 'num_escuela',
				'id' => 'num_escuela',
				'type' => 'text',
				'class' => 'has-feedback form-control',
				'placeholder' => 'Numero escuela (sin guiones)',
				'value' => $this->form_validation->set_value('num_escuela'),
			);

			$this->_render_page('auth/login_alumnos', $this->data);
		}
	}

	public function crear_usuario_alumno($mail_alumno, $cuil_alumno) {
		if (!PORTAL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->session->unset_userdata('usuario');
		$this->session->unset_userdata('rol');
		$this->session->unset_userdata('acceso');
		$this->data['title'] = TITLE . ' - Portal';
		$verificar_mail = $this->access_control_model->mail_existente($mail_alumno);
		$verificar_cuil = $this->access_control_model->cuil_existente($cuil_alumno);

		if (empty($verificar_mail) && empty($verificar_cuil) && $this->verificaCuil($cuil_alumno)) {
			$this->load->helper('string');

			$documento = substr($this->input->post('cuil_alumno'), 3, 8);
			$password = random_string('alnum', 8);
			$activation_code = random_string('alnum', 12);
			$picture = file_get_contents(DIRECTORIO . 'img/generales/usuario.png');
			$password_cryp = $this->generatePasswordHash($password);

			$db_acontrol = $this->load->database('acontrol', TRUE);
			$db_acontrol->trans_begin();
			$db_acontrol->set('ip_address', $this->input->ip_address());
			$db_acontrol->set('usuario', $this->input->post('mail_alumno'));
			$db_acontrol->set('password', $password_cryp);
			$db_acontrol->set('activation_code', $activation_code);
			$db_acontrol->set('authKey', "");
			$db_acontrol->set('accessToken', "");
			$db_acontrol->set('picture', $picture);
			$db_acontrol->set('active', 0);
			$db_acontrol->insert('usuario');
			$usuario_id = $db_acontrol->insert_id();

			$db_acontrol->set('usuario_id', $usuario_id);
			$db_acontrol->set('rol_id', 51);
			$db_acontrol->insert('usuario_rol');

			$db_acontrol->set('usuario_id', $usuario_id);
			$db_acontrol->set('cuil', $this->input->post('cuil_alumno'));
			$db_acontrol->set('documento', $documento);
			$db_acontrol->insert('usuario_persona');
			$usuario_persona_id = $db_acontrol->insert_id();

			$this->db->trans_begin();
			$this->db->set('id', $usuario_id);
			$this->db->set('ip_address', $this->input->ip_address());
			$this->db->set('usuario', $this->input->post('mail_alumno'));
			$this->db->set('password', $password_cryp);
			$this->db->set('activation_code', $activation_code);
			$this->db->set('authKey', "");
			$this->db->set('accessToken', "");
			$this->db->set('picture', $picture);
			$this->db->set('active', 0);
			$this->db->insert('usuario');

			$this->db->set('usuario_id', $usuario_id);
			$this->db->set('rol_id', 24);
			$this->db->set('activo', 1);
			$this->db->insert('usuario_rol');

			$this->db->set('id', $usuario_persona_id);
			$this->db->set('usuario_id', $usuario_id);
			$this->db->set('cuil', $this->input->post('cuil_alumno'));
			$this->db->set('documento', $documento);
			$this->db->insert('usuario_persona');

			$data = array(
				'username' => $this->input->post('mail_alumno'),
				'password' => $password,
//				'activation_link' => "http://dti.mendoza.edu.ar/gem/portal/auth/activacion/$activation_code"
				'activation_link' => BASE_URL . "portal/auth/activacion/$activation_code"
			);
			$this->load->library('email');
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => '192.168.31.6',
//				'smtp_user' => 'notificaciones-gem@mendoza.edu.ar',
				'smtp_user' => 'dge-sistemas@mendoza.edu.ar',
//				'smtp_pass' => 'nr52pq40',
//				'smtp_user' => 'cchiappone@mendoza.gov.ar',
//				'smtp_pass' => '9fd9dbeab36a593.',
//				'smtp_host' => 'smtp.mendoza.gov.ar',
//				'smtp_port' => 25,
				'mailtype' => 'html',
				'charset' => 'utf-8'
			);

			$this->email->initialize($config);

			$this->email->from('dge-sistemas@mendoza.edu.ar', 'Sistemas - DGE');
//			$this->email->from('notificaciones-gem@mendoza.edu.ar', 'Sistemas - DGE');
			$this->email->to($this->input->post('mail_alumno'));
//			$this->email->to('nanoquiroga180@gmail.com'); //ggingins@mendoza.gov.ar,gusgins@gmail.com,,vledonne@mendoza.gov.ar');
			$this->email->subject('Creación de cuenta.');
			$content = trim_html($this->load->view('portal/auth/mail', $data, TRUE));
			//$content = $this->load->view('portal/auth/mail', $data);
			$this->email->message($content);
			$this->email->set_alt_message(html_entity_decode(str_replace(array('|a', '|tr|', '</tr>'), array('<a', '', '\r\n'), strip_tags(str_replace(array('<i>', '</i>', '<a', '<tr>'), array('_', '_', '|a', '|tr|'), $content)))));
			$this->email->send();
			print_r($this->email->print_debugger());
			//exit();
			//return $content;
			//return;

			if ($db_acontrol->trans_status() && $this->db->trans_status()) {
				$db_acontrol->trans_commit();
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'El usuario se creó correctamente. Acceda a su correo para activar la cuenta.');
				redirect("portal/auth/login", 'refresh');
			} else {
				$db_acontrol->trans_rollback();
				$this->db->trans_rollback();
				$this->session->set_flashdata('message', 'Ocurrió un error al crear su cuenta. Por favor intente nuevamente.');
				redirect("portal/auth/login", 'refresh');
			}
		} else {
			$error = "Error de creación de cuenta." . (!empty($verificar_mail) ? "<br>El Mail ingresado ya se encuentra registrado." : "") . (!empty($verificar_cuil) ? "<br>El Cuil ingresado ya se encuentra registrado." : "") . (($this->verificaCuil($cuil_alumno) != TRUE) ? "<br>El Cuil ingresado no es válido." : "");

			$alumno_verificado = $this->access_control_model->login_portal_alumno($this->input->post('dni_alumno'), $this->input->post('clave_division'), $this->input->post('num_escuela'));
			if ($alumno_verificado) {
				return $this->usuario_alumno($alumno_verificado, $error);
			} else {
				$this->session->set_flashdata('error', $error);
				redirect('portal/auth/verificar_usuario_alumno', 'refresh');
			}
		}
	}

	private function usuario_alumno($alumno_verificado, $error = '') {
		if (!PORTAL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($alumno_verificado)) {
			show_404();
		}
		$this->form_validation->set_rules('cuil_alumno', 'Cuil', 'required');
		$this->form_validation->set_rules('mail_alumno', 'Mail', 'required');

		if (!empty($alumno_verificado->alumno_cuil)) {
			$data['cuil_alumno'] = array('name' => 'cuil_alumno',
				'id' => 'cuil_alumno',
				'type' => 'text',
				'value' => (!empty($alumno_verificado->alumno_cuil) ? "$alumno_verificado->alumno_cuil" : ""),
				'class' => 'has-feedback form-control',
				'placeholder' => 'Cuil',
				'tabindex' => '-1');
		} else {
			$data['cuil_alumno'] = array('name' => 'cuil_alumno',
				'id' => 'cuil_alumno',
				'type' => 'text',
				'value' => (!empty($alumno_verificado->alumno_cuil) ? "$alumno_verificado->alumno_cuil" : ""),
				'class' => 'has-feedback form-control',
				'placeholder' => 'Cuil',
			);
		}
		$data['mail_alumno'] = array('name' => 'mail_alumno',
			'id' => 'mail_alumno',
			'type' => 'text',
			'class' => 'has-feedback form-control',
			'placeholder' => 'Mail',
			'value' => (!empty($alumno_verificado->alumno_mail) ? "$alumno_verificado->alumno_mail" : ""),
		);
		$data['aviso'] = "Datos verificados correctamente, complete los datos solicitados para la creación de usuario.";
		$data['datos'] = $alumno_verificado;
		$data['dni_alumno'] = $this->input->post('dni_alumno');
		$data['clave_division'] = $this->input->post('clave_division');
		$data['num_escuela'] = $this->input->post('num_escuela');
		if ($this->input->post('mail_alumno') != NULL && $this->input->post('cuil_alumno') != NULL && empty($error)) {
			$mail_alumno = $this->input->post('mail_alumno');
			$cuil_alumno = $this->input->post('cuil_alumno');
			return $this->crear_usuario_alumno($mail_alumno, $cuil_alumno);
		}
		$data['error'] = $error;
		$data['btn_text'] = "Crear usuario";
		$this->load->view('portal/auth/crear_usuario_alumno', $data);
	}

	public function generateRandomString($length = 32) {
		if (!is_int($length)) {
			throw new InvalidParamException('First parameter ($length) must be an integer');
		}

		if ($length < 1) {
			throw new InvalidParamException('First parameter ($length) must be greater than 0');
		}

		$bytes = $this->generateRandomKey($length);
		return strtr(substr(base64_encode($bytes), 0, $length), '+/', '_-');
	}

	private $_useLibreSSL;

	public function generatePasswordHash($password, $cost = null) {
		if ($cost === null) {
			$cost = $this->passwordHashCost;
		}

		if (function_exists('password_hash')) {
			return password_hash($password, 1, ['cost' => $cost]);
		}

		$salt = $this->generateSalt($cost);
		$hash = crypt($password, $salt);
		if (!is_string($hash) || strlen($hash) !== 60) {
			throw new Exception('Unknown error occurred while generating hash.');
		}

		return $hash;
	}

	public $passwordHashCost = 13;

	protected function generateSalt($cost = 13) {
		$cost = (int) $cost;
		if ($cost < 4 || $cost > 31) {
			throw new InvalidParamException('Cost must be between 4 and 31.');
		}

		$rand = $this->generateRandomKey(20);
		$salt = sprintf("$2y$%02d$", $cost);
		$salt .= str_replace('+', '.', substr(base64_encode($rand), 0, 22));

		return $salt;
	}

	public function generateRandomKey($length = 32) {
		if (!is_int($length)) {
			throw new InvalidParamException('First parameter ($length) must be an integer');
		}

		if ($length < 1) {
			throw new InvalidParamException('First parameter ($length) must be greater than 0');
		}

		if (function_exists('random_bytes')) {
			return random_bytes($length);
		}

		if ($this->_useLibreSSL === null) {
			$this->_useLibreSSL = defined('OPENSSL_VERSION_TEXT') && preg_match('{^LibreSSL (\d\d?)\.(\d\d?)\.(\d\d?)$}', OPENSSL_VERSION_TEXT, $matches) && (10000 * $matches[1]) + (100 * $matches[2]) + $matches[3] >= 20105;
		}

		if ($this->_useLibreSSL || (
			DIRECTORY_SEPARATOR !== '/' && substr_compare(PHP_OS, 'win', 0, 3, true) === 0 && function_exists('openssl_random_pseudo_bytes')
			)
		) {
			$key = openssl_random_pseudo_bytes($length, $cryptoStrong);
			if ($cryptoStrong === false) {
				throw new Exception(
				'openssl_random_pseudo_bytes() set $crypto_strong false. Your PHP setup is insecure.'
				);
			}
			if ($key !== false && mb_strlen($key, '8bit') === $length) {
				return $key;
			}
		}

		if (function_exists('mcrypt_create_iv')) {
			$key = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
			if (StringHelper::byteLength($key) === $length) {
				return $key;
			}
		}

		if ($this->_randomFile === null && DIRECTORY_SEPARATOR === '/') {
			$device = PHP_OS === 'FreeBSD' ? '/dev/random' : '/dev/urandom';
			$lstat = @lstat($device);
			if ($lstat !== false && ($lstat['mode'] & 0170000) === 020000) {
				$this->_randomFile = fopen($device, 'rb') ?: null;

				if (is_resource($this->_randomFile)) {
					$bufferSize = 8;

					if (function_exists('stream_set_read_buffer')) {
						stream_set_read_buffer($this->_randomFile, $bufferSize);
					}
					if (function_exists('stream_set_chunk_size')) {
						stream_set_chunk_size($this->_randomFile, $bufferSize);
					}
				}
			}
		}

		if (is_resource($this->_randomFile)) {
			$buffer = '';
			$stillNeed = $length;
			while ($stillNeed > 0) {
				$someBytes = fread($this->_randomFile, $stillNeed);
				if ($someBytes === false) {
					break;
				}
				$buffer .= $someBytes;
				$stillNeed -= StringHelper::byteLength($someBytes);
				if ($stillNeed === 0) {
					return $buffer;
				}
			}
			fclose($this->_randomFile);
			$this->_randomFile = null;
		}

		throw new Exception('Unable to generate a random key');
	}

	public function activacion($activation_code = NULL) {
		if ($activation_code == NULL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$activacion_code = $this->access_control_model->verificacion($activation_code);
		if (!empty($activacion_code)) {
			$db_acontrol = $this->load->database('acontrol', TRUE);
			$db_acontrol->trans_begin();
			$db_acontrol->set('activation_code', null);
			$db_acontrol->set('active', 1);
			$db_acontrol->where('id', $activacion_code->usuario_id);
			$db_acontrol->update('usuario');
			$this->db->trans_begin();
			$this->db->set('activation_code', null);
			$this->db->set('active', 1);
			$this->db->where('id', $activacion_code->usuario_id);
			$this->db->update('usuario');
			if ($db_acontrol->trans_status() && $this->db->trans_status()) {
				$db_acontrol->trans_commit();
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'La cuenta se activó con éxito.');
				redirect("portal/auth/login", 'refresh');
			} else {
				$db_acontrol->trans_rollback();
				$this->db->trans_rollback();
				$this->session->set_flashdata('message', 'Ocurrió un error al activar la cuenta.');
				redirect("portal/auth/login", 'refresh');
			}
		}
	}

	public function agregar_rol($usuario_id = NULL) {
		if ($usuario_id == NULL || !ctype_digit($usuario_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('usuario_model');
		$usuario = $this->usuarios_model->get_usuario($usuario_id);
		if (empty($usuario)) {
			show_error('No se encontró el usuario a agregar en intranet.mendoza.edu.ar', 500, 'Acción no autorizada');
		}
		if (!empty($usuario)) {
			$trans_ok = TRUE;
			$trans_ok &= $this->usuario_model->migrar($usuario_id);
			$this->db->trans_begin();
			$this->db->set('usuario_id', $usuario->usuario_id);
			$this->db->set('rol_id', 24);
			$this->db->set('activo', 0);
			$trans_ok &= $this->db->insert('usuario_rol');
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Rol asignado correctamente, ingresar al sistema con el usuario y contraseña establecidos');
				redirect("portal/auth/login", 'refresh');
			} else {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', 'Ocurrió un error al agregar el rol');
				redirect("portal/auth/login", 'refresh');
			}
		}
	}

	public function logout() {
		$this->session->unset_userdata('usuario');
		$this->session->unset_userdata('rol');
		setcookie('loginDTIDGE', '', time() - 3600, '.mendoza.gov.ar');
		redirect(LOGIN_URL, 'refresh');
	}

	public function _render_page($view, $data = null, $returnhtml = false) {//I think this makes more sense
		$view_html = $this->load->view($view, $data, $returnhtml);

		if ($returnhtml)
			return $view_html;
	}

	private function verificaCuil($cuil) {
		if (strlen($cuil) == 13) {
			$cuil = str_replace('-', '', $cuil);

			$cadena = str_split($cuil);
			$result = $cadena[0] * 5;
			$result += $cadena[1] * 4;
			$result += $cadena[2] * 3;
			$result += $cadena[3] * 2;
			$result += $cadena[4] * 7;
			$result += $cadena[5] * 6;
			$result += $cadena[6] * 5;
			$result += $cadena[7] * 4;
			$result += $cadena[8] * 3;
			$result += $cadena[9] * 2;

			$div = intval($result / 11);
			$resto = $result - ($div * 11);

			if ($resto == 0) {
				if ($resto == $cadena[10]) {

					return TRUE;
				} else {
					return FALSE;
				}
			} elseif ($resto == 1) {
				if ($cadena[10] == 9 AND $cadena[0] == 2 AND $cadena[1] == 3) {
					return TRUE;
				} elseif ($cadena[10] == 4 AND $cadena[0] == 2 AND $cadena[1] == 3) {
					return TRUE;
				}
			} elseif ($cadena[10] == (11 - $resto)) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	public function recuperar_password() {
		$this->session->unset_userdata('usuario');
		$this->session->unset_userdata('rol');
		$this->session->unset_userdata('acceso');
		$this->data['title'] = TITLE . ' - Portal';

		if ($this->input->post('usuario') != NULL) {
			$usuario = $this->access_control_model->mail_existente($this->input->post('usuario'));
			if ($usuario) {
				$this->load->helper('string');

				$password = random_string('alnum', 20);

				$db_acontrol = $this->load->database('acontrol', TRUE);
				$db_acontrol->trans_begin();
				$db_acontrol->set('forgotten_password_code', $password);
				$db_acontrol->where('id', $usuario->usuario_id);
				$db_acontrol->update('usuario');

				$this->db->trans_begin();
				$this->db->set('forgotten_password_code', $password);
				$this->db->where('id', $usuario->usuario_id);
				$this->db->update('usuario');

				$data = array(
//					'activation_link' => "http://dti.mendoza.edu.ar/gem/portal/auth/accion_recuperar/$password"
					'activation_link' => BASE_URL . "portal/auth/accion_recuperar/$password"
				);
				$this->load->library('email');
				$config = Array(
					'protocol' => 'smtp',
					'smtp_host' => '192.168.31.6',
//					'smtp_user' => 'notificaciones-gem@mendoza.edu.ar',
					'smtp_user' => 'dge-sistemas@mendoza.edu.ar',
//					'smtp_pass' => 'nr52pq40',
//					'smtp_user' => 'cchiappone@mendoza.gov.ar',
//					'smtp_pass' => '9fd9dbeab36a593.',
//					'smtp_host' => 'smtp.mendoza.gov.ar',
//					'smtp_port' => 25,
					'mailtype' => 'html',
					'charset' => 'utf-8'
				);

				$this->email->initialize($config);

				$this->email->from('dge-sistemas@mendoza.edu.ar', 'Sistemas - DGE');
//			$this->email->from('notificaciones-gem@mendoza.edu.ar', 'Sistemas - DGE');
				$this->email->to($this->input->post('usuario'));
//				$this->email->to('nanoquiroga180@gmail.com'); //ggingins@mendoza.gov.ar,gusgins@gmail.com,,vledonne@mendoza.gov.ar');
				$this->email->subject('Recuperación de contraseña.');
				$content = trim_html($this->load->view('portal/auth/mail_recuperar_password', $data, TRUE));
				//$content = $this->load->view('portal/auth/mail', $data);
				$this->email->message($content);
				$this->email->set_alt_message(html_entity_decode(str_replace(array('|a', '|tr|', '</tr>'), array('<a', '', '\r\n'), strip_tags(str_replace(array('<i>', '</i>', '<a', '<tr>'), array('_', '_', '|a', '|tr|'), $content)))));
				$this->email->send();
				print_r($this->email->print_debugger());
				//exit();
				//return $content;
				//return;

				if ($db_acontrol->trans_status() && $this->db->trans_status()) {
					$db_acontrol->trans_commit();
					$this->db->trans_commit();
					$this->session->set_flashdata('message', 'En su correo encontrará instrucciones para proceder con la recuperación de contraseña.');
					redirect("portal/auth/login", 'refresh');
				} else {
					$db_acontrol->trans_rollback();
					$this->db->trans_rollback();
					$this->session->set_flashdata('message', 'Ocurrió un error al procesar la solicitud.');
					redirect("portal/auth/login", 'refresh');
				}
			}
		} else {
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['usuario'] = array('name' => 'usuario',
				'id' => 'usuario',
				'type' => 'text',
				'class' => 'has-feedback form-control',
				'placeholder' => 'Usuario',
				'value' => $this->form_validation->set_value('usuario'),
			);
			$this->_render_page('auth/recuperar_password', $this->data);
		}
	}

	public function accion_recuperar($activation_code = NULL) {
		if ($activation_code == NULL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$activation_code_true = $this->access_control_model->control_recuperar_password($activation_code);
		if ($activation_code_true == NULL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->form_validation->set_rules('password', 'Ingrese una nueva contraseña', 'required');
		$this->form_validation->set_rules('repetir_password', 'Repita la contraseña ingresada', 'required');

		if ($this->form_validation->run() == true) {
			if ($this->input->post('password') != NULL && $this->input->post('repetir_password') != NULL && $this->input->post('password') === $this->input->post('repetir_password')) {
				$this->load->helper('string');
				$this->db->trans_begin();
				$password_cryp = $this->generatePasswordHash($this->input->post('password'));

				$db_acontrol = $this->load->database('acontrol', TRUE);
				$db_acontrol->trans_begin();
				$db_acontrol->set('forgotten_password_code', null);
				$db_acontrol->set('password', $password_cryp);
				$db_acontrol->where('id', $activation_code_true->usuario_id);
				$db_acontrol->update('usuario');

				$this->db->trans_begin();
				$this->db->set('forgotten_password_code', null);
				$this->db->set('password', $password_cryp);
				$this->db->where('id', $activation_code_true->usuario_id);
				$this->db->update('usuario');

				if ($db_acontrol->trans_status() && $this->db->trans_status()) {
					$db_acontrol->trans_commit();
					$this->db->trans_commit();
					$this->session->set_flashdata('message', 'La contraseña se modificó correctamente.');
					redirect("portal/auth/login", 'refresh');
				} else {
					$db_acontrol->trans_rollback();
					$this->db->trans_rollback();
					$this->session->set_flashdata('message', 'Ocurrió un error al procesar la solicitud.');
					redirect("portal/auth/login", 'refresh');
				}
			} else {
				$error = "Error al recuperar contraseña. " . ((($this->input->post('password') !== $this->input->post('repetir_password'))) ? "<br>Las contraseñas ingresadas no coinciden." : "") . (empty($this->input->post('password')) ? "<br>Los campos no pueden ser nulos." : "");
				$this->data['error'] = $error;
			}
		}

		$this->data['password'] = array('name' => 'password',
			'id' => 'password',
			'type' => 'password',
			'class' => 'has-feedback form-control',
			'minlength' => '8',
			'placeholder' => 'Ingrese una nueva contraseña',
		);
		$this->data['repetir_password'] = array('name' => 'repetir_password',
			'id' => 'repetir_password',
			'type' => 'password',
			'class' => 'has-feedback form-control',
			'minlength' => '8',
			'placeholder' => 'Repita la contraseña ingresada',
		);
		$this->load->view('portal/auth/recuperacion', $this->data);
	}

	public function info() {
		$this->load->view('portal/auth/info');
	}

	public function info_padres() {
		$this->load->view('portal/auth/info_padres');
	}

	public function info_alumnos() {
		$this->load->view('portal/auth/info_alumnos');
	}
}
/* End of file Auth_login.php */
	/* Location: ./application/modules/usuarios/controllers/Auth_login.php */	
