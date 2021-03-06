<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escritorio extends MY_Controller {

	function __construct() {
		$this->login_url = 'portal';
		parent::__construct();
		$this->load->model('persona_model');
		$this->load->model('familia_model');
		$this->load->model('alumno_model');
		$this->load->model('division_model');
		$this->load->model('alumno_division_model');
		$this->load->model('calendario_model');
		$this->load->model('division_inasistencia_model');
		$this->load->model('escuela_model');
		$this->load->model('usuario_model');
		$this->roles_permitidos = array(ROL_PORTAL);
		$this->nav_route = 'portal/escritorio';
	}

	public function index() {
		if (!PORTAL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$cuil = $this->session->userdata('usuario')->cuil;
		if (empty($cuil)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}

		$persona_id = $this->persona_model->get(array('cuil' => $cuil));
		if (empty($persona_id)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		$persona = $this->persona_model->get_one($persona_id[0]->id);
		if (empty($persona)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		$hijos = $this->familia_model->get_hijos($persona->id);
		$hijo_persona = array();
		$hijo_alumno = array();
		$alumno_tipo_inasistencia_diaria = array();
		$alumno_inasistencia = array();
		$inasistencias = array();
		$periodos = array();
		$trayectoria = array();

		$alumno = $this->alumno_model->get(array('persona_id' => $persona->id));
		if (!empty($alumno)) {
			$trayectoria_alumno = $this->alumno_division_model->get_trayectoria_alumno($alumno[0]->id);
		}
		$alumno_tipo_inasistencia_diaria_alumno = array();
		$alumno_inasistencia_alumno = array();
		$inasistencias_alumno = array();
		$periodos_alumno = array();

		if (!empty($hijos)) {
			foreach ($hijos as $hijo) {
				$hijo_persona[$hijo->persona_id] = $this->persona_model->get_one($hijo->persona_id);
				$hijo_alumno[$hijo->persona_id] = $this->alumno_model->get_one($hijo->alumno_id);
				if (!empty($hijo->alumno_id)) {
					$trayectoria[$hijo->persona_id] = $this->alumno_division_model->get_trayectoria_alumno($hijo->alumno_id);
					if (!empty($trayectoria[$hijo->persona_id])) {
						foreach ($trayectoria[$hijo->persona_id] as $ad) {
							$alumno_tipo_inasistencia_diaria[$hijo->persona_id][$ad->id] = $this->alumno_division_model->get_alumno_tipo_inasistencia($ad->id);
							$alumno_inasistencia[$hijo->persona_id][$ad->id] = $this->alumno_division_model->get_alumno_inasistencia($ad->id);
							$inasistencias[$hijo->persona_id][$ad->id] = $this->division_inasistencia_model->get_registros($ad->division_id, $ad->ciclo_lectivo);
							$periodos[$hijo->persona_id][$ad->id] = $this->calendario_model->get_periodos($ad->calendario_id, $ad->ciclo_lectivo);
						}
					}
				}
			}
		}

		if (!empty($alumno)) {
			foreach ($trayectoria_alumno as $ad) {
				$alumno_tipo_inasistencia_diaria_alumno[$ad->id] = $this->alumno_division_model->get_alumno_tipo_inasistencia($ad->id);
				$alumno_inasistencia_alumno[$ad->id] = $this->alumno_division_model->get_alumno_inasistencia($ad->id);
				$inasistencias_alumno[$ad->id] = $this->division_inasistencia_model->get_registros($ad->division_id, $ad->ciclo_lectivo);
				$periodos_alumno[$ad->id] = $this->calendario_model->get_periodos($ad->calendario_id, $ad->ciclo_lectivo);
			}
		}
		$data['hijo_persona'] = $hijo_persona;
		$data['alumno_tipo_inasistencia_diaria'] = $alumno_tipo_inasistencia_diaria;
		$data['alumno_inasistencia'] = $alumno_inasistencia;
		$data['inasistencias'] = $inasistencias;
		$data['periodos'] = $periodos;
		$data['trayectoria'] = $trayectoria;
		$data['hijos'] = $hijos;
		$data['persona'] = $persona;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		if (!empty($alumno)) {
			$data['alumno_tipo_inasistencia_diaria_alumno'] = $alumno_tipo_inasistencia_diaria_alumno;
			$data['alumno_inasistencia_alumno'] = $alumno_inasistencia_alumno;
			$data['inasistencias_alumno'] = $inasistencias_alumno;
			$data['periodos_alumno'] = $periodos_alumno;
			$data['trayectoria_alumno'] = $trayectoria_alumno;
		}

		$this->load_template_portal('portal/escritorio/escritorio', $data);
	}

	public function alumno($alumno_division_id = NULL) {
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!PORTAL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$cuil = $this->session->userdata('usuario')->cuil;
		if (empty($cuil)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		$persona_id = $this->persona_model->get(array('cuil' => $cuil));
		if (empty($persona_id)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		$persona = $this->persona_model->get_one($persona_id[0]->id);
		if (empty($persona)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}

		$alumno = $this->alumno_division_model->get(array('id' => $alumno_division_id,
			'join' => array(
				array('alumno', 'alumno.id = alumno_division.alumno_id', '', array('alumno.persona_id as alumno')),
				array('division', 'division.id = alumno_division.division_id', '', array('division.division as division', 'division.escuela_id', 'division.calendario_id')),
				array('escuela', 'division.escuela_id = escuela.id', '', array('escuela.numero', 'escuela.anexo', 'escuela.nombre')),
				array('curso', 'curso.id = division.curso_id', '', array('curso.descripcion as curso', 'curso.nivel_id as nivel_id', 'curso.grado_multiple')),
				array('curso c_gm', 'alumno_division.curso_id=c_gm.id', 'left', array('c_gm.descripcion as curso_grado_multiple')),
				array('causa_entrada', 'causa_entrada.id = alumno_division.causa_entrada_id', 'left', array('causa_entrada.descripcion as causa_entrada')),
				array('causa_salida', 'causa_salida.id = alumno_division.causa_salida_id', 'left', array('causa_salida.descripcion as causa_salida')),
				array('estado_alumno', 'estado_alumno.id = alumno_division.estado_id', 'left', array('estado_alumno.descripcion as estado'))
			)
		));
		if (empty($alumno)) {
			show_error('No se encontró el registro del alumno', 500, 'Registro no encontrado');
		}
		$alumno->nombre_escuela = $alumno->numero . ($alumno->anexo === '0' ? '' : "/$alumno->anexo") . ' ' . $alumno->nombre;
		$persona_a = $this->persona_model->get_one($alumno->alumno);
		if ($alumno->alumno !== $persona->id) {
			$hijos = $this->familia_model->get_hijos($persona->id);
			$es_hijo = FALSE;
			if (!empty($hijos)) {
				foreach ($hijos as $hijo) {
					if ($alumno->alumno === $hijo->persona_id) {
						$es_hijo = TRUE;
					}
				}
			}
			if (!$es_hijo) {
				show_error('No tiene permisos para acceder al alumno', 500, 'Acción no autorizada');
			}
		}
		$alumno_tipo_inasistencia_diaria = $this->alumno_division_model->get_alumno_tipo_inasistencia($alumno->id);
		$alumno_inasistencia = $this->alumno_division_model->get_alumno_inasistencia($alumno->id);
		$inasistencias = $this->division_inasistencia_model->get_registros($alumno->division_id, $alumno->ciclo_lectivo);
		$periodos = $this->calendario_model->get_periodos($alumno->calendario_id, $alumno->ciclo_lectivo);

		$this->load->model('alumno_cursada_model');
		$notas = $this->alumno_cursada_model->get_notas_alumno($alumno->id);
		
		$data['txt_btn'] = 'Volver';
		$data['notas'] = $notas;
		$data['periodos'] = $periodos;
		$data['persona'] = $persona_a;
		$data['alumno'] = $alumno;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$data['alumno_tipo_inasistencia_diaria_alumno'] = $alumno_tipo_inasistencia_diaria;
		$data['alumno_inasistencia_alumno'] = $alumno_inasistencia;
		$data['inasistencias_alumno'] = $inasistencias;
		$data['periodos_alumno'] = $periodos;
		$this->load_template_portal('portal/escritorio/escritorio_alumno', $data);
	}

	public function modal_cambiar_password() {
		if (!PORTAL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$cuil = $this->session->userdata('usuario')->cuil;
		if (empty($cuil)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		$persona_id = $this->persona_model->get(array('cuil' => $cuil));
		if (empty($persona_id)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		$persona = $this->persona_model->get_one($persona_id[0]->id);
		if (empty($persona)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($this->input->post('password') != NULL && $this->input->post('nuevo_password') != NULL && $this->input->post('repetir_nuevo_password') != NULL && ($this->input->post('nuevo_password') === $this->input->post('repetir_nuevo_password'))) {
				$usuario = $this->usuario_model->get_one($this->session->userdata('usuario')->usuario_id);
				$this->load->model('portal/access_control_model');
				if (!$this->access_control_model->login($usuario->usuario, $this->input->post('password'))) {
					$this->session->set_flashdata('error', 'Ocurrió un error al intentar cambiar la contraseña (contraseña actual incorrecta)');
					redirect("portal/escritorio", 'refresh');
				}

				$this->load->helper('string');
				$password_cryp = $this->generatePasswordHash($this->input->post('nuevo_password'));

				$db_acontrol = $this->load->database('acontrol', TRUE);
				$db_acontrol->trans_begin();
				$db_acontrol->set('password', $password_cryp);
				$db_acontrol->where('id', $this->session->userdata('usuario')->usuario_id);
				$db_acontrol->update('usuario');

				$this->db->trans_begin();
				$this->db->set('password', $password_cryp);
				$this->db->where('id', $this->session->userdata('usuario')->usuario_id);
				$this->db->update('usuario');

				if ($db_acontrol->trans_status() && $this->db->trans_status()) {
					$db_acontrol->trans_commit();
					$this->db->trans_commit();
					$this->session->set_flashdata('message', 'La contraseña se modificó correctamente.');
					redirect("portal/escritorio", 'refresh');
				} else {
					$db_acontrol->trans_rollback();
					$this->db->trans_rollback();
					$this->session->set_flashdata('message', 'Ocurrió un error al procesar la solicitud.');
					redirect("portal/escritorio", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', 'Ocurrió un error al procesar la solicitud.');
				redirect("portal/escritorio", 'refresh');
			}
		}

		$data['password'] = array('name' => 'password',
			'id' => 'password',
			'type' => 'password',
			'class' => 'has-feedback form-control',
			'minlength' => '8',
			'placeholder' => 'Ingrese contraseña actual',
		);
		$data['nuevo_password'] = array('name' => 'nuevo_password',
			'id' => 'nuevo_password',
			'type' => 'password',
			'class' => 'has-feedback form-control',
			'minlength' => '8',
			'placeholder' => 'Ingrese una nueva contraseña',
		);
		$data['repetir_nuevo_password'] = array('name' => 'repetir_nuevo_password',
			'id' => 'repetir_nuevo_password',
			'type' => 'password',
			'class' => 'has-feedback form-control',
			'minlength' => '8',
			'placeholder' => 'Repita la contraseña ingresada',
		);

		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['txt_btn'] = 'Guardar';
		$data['title'] = 'Cambiar contraseña';
		$this->load->view('portal/cambiar_password_modal', $data);
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
}