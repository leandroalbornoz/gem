<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alumno_division extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->load->model('division_model');
		$this->load->model('alumno_division_model');
		$this->load->model('alumno_model');
		$this->load->model('persona_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE_CURSADA));
		$this->roles_admin = array(ROL_ADMIN, ROL_USI);
		if (in_array($this->rol->codigo, array(ROL_PRIVADA, ROL_SEOS, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_GRUPO_ESCUELA_CONSULTA, ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_JEFE_LIQUIDACION, ROL_LIQUIDACION, ROL_REGIONAL))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'alumnos/alumno_division';
	}

	public function agregar_existente($division_id = NULL, $ciclo_lectivo = NULL, $persona_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo) || $persona_id == NULL || !ctype_digit($persona_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$division = $this->division_model->get_one($division_id);
		if (empty($division)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$persona = $this->persona_model->get_one($persona_id);
		if (empty($persona)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}

		$this->load->model('alumno_model');
		$this->load->model('alumno_division_model');
		$alumno = $this->alumno_model->get_by_persona($persona_id);
		$trayectoria = array();
		if (!empty($alumno)) {
			if ($escuela->formal === 'Si') {
				$a_d_anterior = $this->alumno_model->get_ultima_division($alumno->id);
				if (!empty($a_d_anterior)) {
					$data['division_anterior'] = $a_d_anterior->id;
				}
			}
			$trayectoria = $this->alumno_division_model->get_trayectoria_alumno($alumno->id);
			if (!empty($trayectoria)) {
				foreach ($trayectoria as $div_trayectoria) {
					if ($div_trayectoria->legajo === NULL) {
						$div_trayectoria->legajo = '';
					}
					$div_trayectoria->fecha_desde = (new DateTime($div_trayectoria->fecha_desde))->format('d/m/y');
					if ($div_trayectoria->fecha_hasta === NULL) {
						$div_trayectoria->fecha_hasta = '';
					} else {
						$div_trayectoria->fecha_hasta = (new DateTime($div_trayectoria->fecha_hasta))->format('d/m/y');
					}
					if ($div_trayectoria->causa_salida === NULL) {
						$div_trayectoria->causa_salida = '';
					}
				}
			}
		}

		$this->load->model('causa_entrada_model');
		unset($this->alumno_division_model->fields['division']);
		unset($this->alumno_division_model->fields['fecha_hasta']);
		unset($this->alumno_division_model->fields['causa_salida']);
		unset($this->alumno_division_model->fields['estado']);
		$persona->division = "$division->curso $division->division";

		$this->array_causa_entrada_control = $array_causa_entrada = $this->get_array('causa_entrada', 'descripcion', 'id', null, array('' => '-- Seleccionar motivo de entrada --'));
		$this->array_condicion_control = $this->alumno_division_model->fields['condicion']['array'];
		$this->set_model_validation_rules($this->alumno_division_model);
		$errors = FALSE;
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$division_anterior_id = $this->input->post('division_anterior');
				$alumno_id = $this->input->post('alumno_id');
				if (!empty($a_d_anterior) && $a_d_anterior->id !== $division_anterior_id) {
					show_error('Esta solicitud no pasó el control de seguridad.');
				}
				if (!empty($alumno) && $alumno->id !== $alumno_id) {
					show_error('Esta solicitud no pasó el control de seguridad.');
				}
				if (!empty($a_d_anterior)) {
					$trans_ok &= $this->alumno_division_model->update(array(
						'id' => $division_anterior_id,
						'estado_id' => 2
						), FALSE);
				}
				if ($trans_ok) {
					if (empty($alumno_id)) {
						$trans_ok &= $this->alumno_model->create(array('persona_id' => $persona_id), FALSE);
						$alumno_id = $this->alumno_model->get_row_id();
					}
					$trans_ok &= $this->alumno_division_model->create(array(
						'alumno_id' => $alumno_id,
						'legajo' => $this->input->post('legajo'),
						'division_id' => $division->id,
						'fecha_desde' => $this->get_date_sql('fecha_desde'),
						'ciclo_lectivo' => $this->input->post('ciclo_lectivo'),
						'estado_id' => 1,
						'condicion' => $this->input->post('condicion'),
						'causa_entrada_id' => $this->input->post('causa_entrada')
						), FALSE);
					$alumno_division_id = $this->alumno_division_model->get_row_id();
				}

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->alumno_division_model->get_msg());
					if ($this->input->post('editar') === '1') {
						redirect("alumno/editar/$alumno_division_id", 'refresh');
					} else {
						$this->session->set_flashdata('abrir_modal', TRUE);
						redirect("division/alumnos/$division_id/" . $this->input->post('ciclo_lectivo'), 'refresh');
					}
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->alumno_division_model->get_error())
						$errors .= '<br>' . $this->alumno_division_model->get_error();
					if ($this->alumno_model->get_error())
						$errors .= '<br>' . $this->alumno_model->get_error();
				}
			}
		}

		$data['error'] = (validation_errors() ? validation_errors() : $errors);

		$this->alumno_division_model->fields['causa_entrada']['array'] = $array_causa_entrada;

		$data['fields_alumno'] = $this->build_fields($this->alumno_division_model->fields_alumno, $persona);
		$data['fields'] = $this->build_fields($this->alumno_division_model->fields);
		$data['escuela'] = $escuela;
		$data['persona'] = $persona;
		$data['alumno'] = $alumno;
		$data['trayectoria'] = $trayectoria;
		$data['division'] = $division;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Agregar alumno a división';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('alumno_division/alumno_division_agregar_existente', $data);
	}

	public function agregar_nuevo($division_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$division = $this->division_model->get_one($division_id);
		if (empty($division)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('causa_entrada_model');
		$this->load->model('documento_tipo_model');
		unset($this->alumno_division_model->fields['division']);
		unset($this->alumno_division_model->fields['fecha_hasta']);
		unset($this->alumno_division_model->fields['causa_salida']);
		unset($this->alumno_division_model->fields['estado']);
		$model_alumno = new stdClass();
		$model_alumno->fields = array(
			'documento_tipo' => array('label' => 'Tipo de documento', 'input_type' => 'combo', 'id_name' => 'documento_tipo_id', 'required' => TRUE, 'array' => $this->get_array('documento_tipo', 'descripcion_corta', 'id', null, array('1' => ''))),
			'documento' => array('label' => 'Documento', 'type' => 'integer', 'maxlength' => '9', 'required' => TRUE),
			'apellido' => array('label' => 'Apellido', 'maxlength' => '100', 'required' => TRUE),
			'nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'required' => TRUE),
			'fecha_nacimiento' => array('label' => 'Fecha Nacimiento', 'type' => 'date'),
			'division' => array('label' => 'División', 'readonly' => TRUE, 'value' => "$division->curso $division->division")
		);
		$this->array_causa_entrada_control = $array_causa_entrada = $this->get_array('causa_entrada', 'descripcion', 'id', null, array('' => '-- Seleccionar motivo de entrada --'));
		$this->array_documento_tipo_control = $model_alumno->fields['documento_tipo']['array'];
		$this->array_condicion_control = $this->alumno_division_model->fields['condicion']['array'];
		$this->set_model_validation_rules($this->alumno_division_model);
		$this->set_model_validation_rules($model_alumno);
		if ($this->form_validation->run() === TRUE) {
			$this->db->trans_begin();
			$trans_ok = TRUE;
			if ($trans_ok) {
				$trans_ok &= $this->persona_model->create(array(
					'documento_tipo_id' => $this->input->post('documento_tipo'),
					'documento' => $this->input->post('documento'),
					'nombre' => $this->input->post('nombre'),
					'apellido' => $this->input->post('apellido'),
					'fecha_nacimiento' => $this->get_date_sql('fecha_nacimiento')
					), FALSE);
				$persona_id = $this->persona_model->get_row_id();
				$trans_ok &= $this->alumno_model->create(array('persona_id' => $persona_id), FALSE);
				$alumno_id = $this->alumno_model->get_row_id();
				$trans_ok &= $this->alumno_division_model->create(array(
					'alumno_id' => $alumno_id,
					'legajo' => $this->input->post('legajo'),
					'division_id' => $division->id,
					'fecha_desde' => $this->get_date_sql('fecha_desde'),
					'ciclo_lectivo' => $this->input->post('ciclo_lectivo'),
					'estado_id' => 1,
					'condicion' => $this->input->post('condicion'),
					'causa_entrada_id' => $this->input->post('causa_entrada')
					), FALSE);
				$alumno_division_id = $this->alumno_division_model->get_row_id();
			}

			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->alumno_division_model->get_msg());
				if ($this->input->post('editar') === '1') {
					redirect("alumno/editar/$alumno_division_id", 'refresh');
				} else {
					$this->session->set_flashdata('abrir_modal', TRUE);
					redirect("division/alumnos/$division_id/" . $this->input->post('ciclo_lectivo'), 'refresh');
				}
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al intentar actualizar.';
				if ($this->alumno_division_model->get_error())
					$errors .= '<br>' . $this->alumno_division_model->get_error();
			}
		}

		$data['error'] = (validation_errors() ? validation_errors() : ($this->alumno_division_model->get_error() ? $this->alumno_division_model->get_error() : $this->session->flashdata('error')));
		$data['error'] = (validation_errors() ? validation_errors() : ($this->alumno_model->get_error() ? $this->alumno_model->get_error() : $this->session->flashdata('error')));

		$this->alumno_division_model->fields['causa_entrada']['array'] = $array_causa_entrada;

		$data['fields_alumno'] = $this->build_fields($model_alumno->fields);
		$data['fields'] = $this->build_fields($this->alumno_division_model->fields);
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Agregar alumno a división';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('alumno_division/alumno_division_agregar_nuevo', $data);
	}

	public function modal_eliminar_alumno_division($alumno_division_id = NULL, $redirect = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			$this->modal_error('No se encontró el registro del alumno', 'Registro no encontrado');
			return;
		}
		$division = $this->division_model->get_one($alumno_division->division_id);
		if (empty($division)) {
			$this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$this->load->model('alumno_inasistencia_model');
		$alumno_inasistencias = $this->alumno_inasistencia_model->get(array('alumno_division_id' => $alumno_division_id));
		if (!empty($alumno_inasistencias)) {
			$borrar = FALSE;
			foreach ($alumno_inasistencias as $alumno_inasistencia):
				if ($alumno_inasistencia->justificada !== 'NC'):
					$borrar = TRUE;
				endif;
			endforeach;
		}

		$alumno_division->escuela = $escuela->nombre_largo;

		$model = new stdClass();
		$model->fields = array(
			'ciclo_lectivo' => array('label' => 'Ciclo lectivo'),
			'escuela' => array('label' => 'Escuela'),
			'division' => array('label' => 'División'),
			'legajo' => array('label' => 'Legajo'),
			'fecha_desde' => array('label' => 'Desde', 'type' => 'date'),
			'causa_entrada' => array('label' => 'Causa de entrada'),
			'fecha_hasta' => array('label' => 'Hasta', 'type' => 'date'),
			'causa_salida' => array('label' => 'Causa de salida'),
			'condicion' => array('label' => 'Condición', 'input_type' => 'combo', 'array' => $this->alumno_division_model->fields['condicion']['array'], 'id_name' => 'condicion'),
			'estado' => array('label' => 'Estado')
		);
		$this->array_condicion_control = $model->fields['condicion']['array'];

		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_division_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
				return;
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;
			if (!empty($alumno_inasistencias)):
				if ($borrar === FALSE):
					foreach ($alumno_inasistencias as $alumno_inasistencia):
						$trans_ok &= $this->alumno_inasistencia_model->delete(array('id' => $alumno_inasistencia->id));
					endforeach;
				endif;
			endif;
			$trans_ok &= $this->alumno_division_model->delete(array('id' => $this->input->post('id')));
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Registro de Trayectoria del alumno eliminada correctamente.');
				redirect("division/ver/$alumno_division->division_id", 'refresh');
			} else {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', $this->alumno_division_model->get_error());
				redirect("alumno/ver/$alumno_division_id", 'refresh');
			}
		}

		$model->fields['division']['value'] = "$alumno_division->curso $alumno_division->division";
		$model->fields['ciclo_lectivo']['value'] = $alumno_division->ciclo_lectivo;

		$data['fields'] = $this->build_fields($model->fields, $alumno_division, TRUE);
		$data['alumno_division'] = $alumno_division;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar trayectoria de alumno';
		$this->load->view('alumno_division/alumno_division_modal_abm', $data);
	}

	public function modal_editar_alumno_condicion($alumno_division_id = NUL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			$this->modal_error('No se encontró el registro del alumno', 'Registro no encontrado');
			return;
		}
		$division = $this->division_model->get_one($alumno_division->division_id);
		if (empty($division)) {
			$this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}
		$alumno_division->escuela = $escuela->nombre_largo;
		unset($this->alumno_division_model->fields['division']);
		unset($this->alumno_division_model->fields['legajo']);
		unset($this->alumno_division_model->fields['fecha_desde']);
		unset($this->alumno_division_model->fields['fecha_hasta']);
		unset($this->alumno_division_model->fields['causa_entrada']);
		unset($this->alumno_division_model->fields['causa_salida']);
		unset($this->alumno_division_model->fields['estado']);
		unset($this->alumno_division_model->fields['ciclo_lectivo']);

		$this->array_condicion_control = $this->alumno_division_model->fields['condicion']['array'];

		$this->set_model_validation_rules($this->alumno_division_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_division_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->alumno_division_model->update(array(
					'id' => $this->input->post('id'),
					'condicion' => $this->input->post('condicion')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', 'Registro de Trayectoria del alumno editado correctamente.');
					redirect("alumno/ver/$alumno_division_id", 'refresh');
				} else {
					$this->session->set_flashdata('error', 'No se ha podido editar el registro de Trayectoria del alumno.');
					redirect("alumno/ver/$alumno_division_id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("alumno/ver/$alumno_division_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->alumno_division_model->fields, $alumno_division);
		$data['alumno_division'] = $alumno_division;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar condición de alumno';
		$this->load->view('alumno_division/alumno_division_modal_condicion', $data);
	}

	public function modal_editar_alumno_division($alumno_division_id = NULL, $redirect = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			$this->modal_error('No se encontró el registro del alumno', 'Registro no encontrado');
			return;
		}
		$division = $this->division_model->get_one($alumno_division->division_id);
		if (empty($division)) {
			$this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}
		$alumno_division->escuela = $escuela->nombre_largo;

		$model = new stdClass();
		$model->fields = array(
			'ciclo_lectivo' => array('label' => 'Ciclo lectivo'),
			'escuela' => array('label' => 'Escuela', 'readonly' => TRUE),
			'division' => array('label' => 'División', 'input_type' => 'combo', 'id_name' => 'division_id'),
			'legajo' => array('label' => 'Legajo'),
			'fecha_desde' => array('label' => 'Desde', 'type' => 'date'),
			'causa_entrada' => array('label' => 'Causa de entrada', 'input_type' => 'combo', 'required'),
			'fecha_hasta' => array('label' => 'Hasta', 'type' => 'date'),
			'causa_salida' => array('label' => 'Causa de salida', 'input_type' => 'combo', 'id_name' => 'causa_salida_id'),
			'estado' => array('label' => 'Estado', 'input_type' => 'combo', 'id_name' => 'estado_id'),
			'condicion' => array('label' => 'Condición', 'input_type' => 'combo', 'array' => $this->alumno_division_model->fields['condicion']['array'], 'id_name' => 'condicion')
		);

		$this->load->model('estado_alumno_model');
		$this->load->model('causa_entrada_model');
		$this->load->model('causa_salida_model');
		$this->array_condicion_control = $model->fields['condicion']['array'];
		$this->array_estado_control = $array_estado = $this->get_array('estado_alumno', 'descripcion', 'id', null, array('' => '-- Seleccionar estado --'));
		$this->array_division_control = $array_division = $this->get_array('division', 'division', 'id', array(
			'select' => array('division.id', 'CONCAT(curso.descripcion, \' \', division.division) as division'),
			'join' => array(
				array('curso', 'curso.id=division.curso_id')
			),
			'escuela_id' => $escuela->id,
			'where' => array('fecha_baja IS NULL'),
			'sort_by' => 'curso.descripcion, division.division'
			), array('' => '-- Sin división --'));

		$this->array_causa_entrada_control = $array_causa_entrada = $this->get_array('causa_entrada', 'descripcion', 'id', null, array('' => '-- Seleccionar motivo de entrada --'));
		$this->array_causa_salida_control = $array_causa_salida = $this->get_array('causa_salida', 'descripcion', 'id', null, array('' => '-- Seleccionar motivo de salida --'));

		$this->set_model_validation_rules($model);
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_division_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->alumno_division_model->update(array(
					'id' => $this->input->post('id'),
					'division_id' => $this->input->post('division'),
					'legajo' => $this->input->post('legajo'),
					'fecha_desde' => $this->get_date_sql('fecha_desde'),
					'causa_entrada_id' => $this->input->post('causa_entrada'),
					'fecha_hasta' => $this->get_date_sql('fecha_hasta'),
					'causa_salida_id' => $this->input->post('causa_salida'),
					'estado_id' => $this->input->post('estado'),
					'condicion' => $this->input->post('condicion'),
					'ciclo_lectivo' => $this->input->post('ciclo_lectivo'),
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', 'Registro de Trayectoria del alumno editado correctamente.');
					redirect("alumno/ver/$alumno_division_id", 'refresh');
				} else {
					$this->session->set_flashdata('error', 'No se ha podido editar el registro de Trayectoria del alumno.');
					redirect("alumno/ver/$alumno_division_id", 'refresh');
				}
			}
			$this->session->set_flashdata('error', validation_errors());
			redirect("alumno/ver/$alumno_division_id", 'refresh');
		}

		$model->fields['causa_entrada']['array'] = $array_causa_entrada;
		$model->fields['causa_salida']['array'] = $array_causa_salida;
		$model->fields['estado']['array'] = $array_estado;
		$model->fields['division']['array'] = $array_division;

		$this->load->model('alumno_inasistencia_model');
		$inasistencias_cargadas = $this->alumno_inasistencia_model->get_inasistencias_cargadas($alumno_division->id);
		$data['inasistencias_cargadas'] = $inasistencias_cargadas;
		$data['fields'] = $this->build_fields($model->fields, $alumno_division);
		$data['alumno_division'] = $alumno_division;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar trayectoria de alumno';
		$this->load->view('alumno_division/alumno_division_modal_abm', $data);
	}

	public function modal_eliminar_baja($alumno_division_id = NULL, $redirect = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			$this->modal_error('No se encontró el registro del alumno', 'Registro no encontrado');
			return;
		}
		$division = $this->division_model->get_one($alumno_division->division_id);
		if (empty($division)) {
			$this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$alumno_division->escuela = $escuela->nombre_largo;

		$model = new stdClass();
		$model->fields = array(
			'fecha_hasta' => array('label' => 'Hasta', 'type' => 'date'),
			'causa_salida' => array('label' => 'Causa de salida'),
			'estado' => array('label' => 'Estado')
		);

		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_division_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->alumno_division_model->update(array(
				'id' => $this->input->post('id'),
				'fecha_hasta' => '',
				'causa_salida_id' => '',
				'estado_id' => '1'
			));
			if ($this->db->trans_status() && $trans_ok) {
				$this->session->set_flashdata('message', 'Registro de Trayectoria del alumno editado correctamente.');
				redirect("alumno/ver/$alumno_division_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', 'No se ha podido editar el registro de Trayectoria del alumno.');
				redirect("alumno/ver/$alumno_division_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($model->fields, $alumno_division, TRUE);
		$data['alumno_division'] = $alumno_division;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar trayectoria de alumno';
		$this->load->view('alumno_division/alumno_division_modal_escuela', $data);
	}

	public function modal_grado_multiple($alumno_division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			$this->modal_error('No se encontró el registro del alumno', 'Registro no encontrado');
			return;
		}
		$division = $this->division_model->get_one($alumno_division->division_id);
		if (empty($division)) {
			$this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$model = new stdClass();
		$model->fields = array(
			'curso' => array('label' => 'Curso', 'input_type' => 'combo', 'id_name' => 'curso_id', 'required' => TRUE)
		);

		$this->load->model('division_model');
		$this->load->model('curso_model');
		$this->array_curso_control = $array_curso = $this->get_array('curso', 'descripcion', 'id', array(
			'nivel_id' => $alumno_division->nivel_id,
			'grado_multiple' => 'No'
			), array('' => '-- Seleccionar curso --'));

		$this->set_model_validation_rules($model);
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_division_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
				return;
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->alumno_division_model->update(array(
					'id' => $this->input->post('id'),
					'curso_id' => $this->input->post('curso')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->alumno_division_model->get_msg());
					redirect("alumno/ver/$alumno_division->id", 'refresh');
				} else {
					$this->session->set_flashdata('error', validation_errors());
					redirect("alumno/ver/$alumno_division->id", 'refresh');
				}
			}
		}

		$model->fields['curso']['array'] = $array_curso;

		$data['fields'] = $this->build_fields($model->fields);
		$data['alumno_division'] = $alumno_division;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar curso de Alumno de Grado Múltiple';
		$this->load->view('alumno_division/alumno_division_modal_curso_grado_multiple', $data);
	}

	public function modal_inasistencia_alumno($alumno_division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			$this->modal_error('No se encontró el registro del alumno', 'Registro no encontrado');
			return;
		}

		$division = $this->division_model->get_one($alumno_division->division_id);
		if (empty($division)) {
			$this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
			return;
		}

		$this->load->model('division_inasistencia_model');
		$division_inasistencia = $this->division_inasistencia_model->get(array('division_id' => $division->id));
		if (empty($division_inasistencia)) {
			$this->modal_error('No se encontró ningún registro de inasistencia del alumno', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$this->load->model('calendario_model');
		$periodos = $this->calendario_model->get_periodos($division->calendario_id, $alumno_division->ciclo_lectivo);
		if (empty($periodos)) {
			$this->modal_error('No se encontró ningún registro de inasistencia del alumno', 'Registro no encontrado');
			return;
		}

		$alumno_division->escuela = $escuela->nombre_largo;
		$alumno_tipo_inasistencia_diaria = $this->alumno_division_model->get_alumno_tipo_inasistencia($alumno_division->id);
		$alumno_inasistencia = $this->alumno_division_model->get_alumno_inasistencia($alumno_division->id);
		$inasistencias = $this->division_inasistencia_model->get_registros($division->id, $alumno_division->ciclo_lectivo);

		$data['inasistencias'] = $inasistencias;
		$data['alumno_inasistencia'] = $alumno_inasistencia;
		$data['alumno_tipo_inasistencia_diaria'] = $alumno_tipo_inasistencia_diaria;
		$data['periodos'] = $periodos;
		$data['alumno'] = $this->alumno_model->get_one($alumno_division->alumno_id);
		$data['ciclo_lectivo'] = $alumno_division->ciclo_lectivo;
		$data['division'] = $division;
		$data['alumno_division'] = $alumno_division;
		$data['txt_btn'] = '';
		$data['title'] = 'Asistencia del alumno';
		$this->load->view('alumno_division/alumno_division_modal_inasistencias', $data);
	}

	public function modal_editar_sexo_alumno($alumno_division_id = NULL, $division_inasistencia_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id) || $division_inasistencia_id == NULL || !ctype_digit($division_inasistencia_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_division_model');
		$this->load->model('division_model');

		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		$division = $this->division_model->get_one($alumno_division->division_id);

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_model');
		$alumno = $this->alumno_model->get(array('id' => $alumno_division->alumno_id, 'join' => array(
				array('persona', 'alumno.persona_id=persona.id', 'left', array('cuil', 'documento_tipo_id', 'documento', 'apellido', 'nombre', 'calle', 'calle_numero', 'departamento', 'piso', 'barrio', 'manzana', 'casa', 'localidad_id', 'sexo_id', 'estado_civil_id', 'nivel_estudio_id', 'ocupacion_id', 'telefono_fijo', 'telefono_movil', 'prestadora_id', 'fecha_nacimiento', 'fecha_defuncion', 'obra_social_id', 'contacto_id', 'grupo_sanguineo_id', 'depto_nacimiento_id', 'lugar_traslado_emergencia', 'nacionalidad_id', 'email'))
		)));
		if (empty($alumno)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}

		$this->load->model('sexo_model');

		$this->array_sexo_control = $array_sexo = $this->get_array('sexo', 'descripcion', 'id', array('sort_by' => 'id'), array('' => '-- Seleccionar sexo --'));

		unset($this->alumno_model->fields['documento_tipo']);
		unset($this->alumno_model->fields['documento']);
		unset($this->alumno_model->fields['cuil']);
		unset($this->alumno_model->fields['apellido']);
		unset($this->alumno_model->fields['nombre']);
		unset($this->alumno_model->fields['fecha_nacimiento']);
		unset($this->alumno_model->fields['calle']);
		unset($this->alumno_model->fields['calle_numero']);
		unset($this->alumno_model->fields['departamento']);
		unset($this->alumno_model->fields['piso']);
		unset($this->alumno_model->fields['barrio']);
		unset($this->alumno_model->fields['manzana']);
		unset($this->alumno_model->fields['casa']);
		unset($this->alumno_model->fields['localidad']);
		unset($this->alumno_model->fields['telefono_fijo']);
		unset($this->alumno_model->fields['telefono_movil']);
		unset($this->alumno_model->fields['observaciones']);
		unset($this->alumno_model->fields['prestadora']);
		unset($this->alumno_model->fields['email']);
		unset($this->alumno_model->fields['lugar_traslado_emergencia']);
		unset($this->alumno_model->fields['nacionalidad']);
		unset($this->alumno_model->fields['grupo_sanguineo']);
		unset($this->alumno_model->fields['obra_social']);
		unset($this->alumno_model->fields['nivel_estudio']);
		unset($this->alumno_model->fields['estado_civil']);
		unset($this->alumno_model->fields['ocupacion']);
		unset($this->alumno_model->fields['email_contacto']);
		unset($this->alumno_model->fields['codigo_postal']);

		$this->set_model_validation_rules($this->alumno_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->persona_model->update(array(
					'id' => $alumno->persona_id,
					'sexo_id' => $this->input->post('sexo')
					), FALSE);
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->persona_model->get_msg());
					redirect("division_inasistencia/ver/$division_inasistencia_id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->persona_model->get_error())
						$errors .= '<br>' . $this->persona_model->get_error();
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : (!empty($errors) ? $errors : $this->session->flashdata('error')));

		$this->alumno_model->fields['sexo']['array'] = $array_sexo;

		$data['fields'] = $this->build_fields($this->alumno_model->fields, $alumno);
		$data['message'] = $this->session->flashdata('message');
		$data['ciclo_lectivo'] = $alumno_division->ciclo_lectivo;
		$data['division'] = "$division->curso $alumno_division->division";
		$data['escuela'] = $escuela;
		$data['alumno'] = $alumno;
		$data['alumno_division_id'] = $alumno_division_id;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = 'Editar alumno';
		$this->load->view('alumno_division/alumno_division_modal_sexo', $data);
	}

	public function modal_certificado_alumno_regular($alumno_division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			$this->modal_error('No se encontró el registro de alumno división', 'Registro no encontrado');
			return;
		}
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		if (empty($alumno)) {
			$this->modal_error('No se encontró el registro de alumno', 'Registro no encontrado');
			return;
		}
		$persona = $this->persona_model->get_one($alumno->persona_id);
		if (empty($persona)) {
			$this->modal_error('No se encontró el registro de persona', 'Registro no encontrado');
			return;
		}
		$division = $this->division_model->get_one($alumno_division->division_id);
		if (empty($division)) {
			$this->modal_error('No se encontró el registro de división', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}

		$model = new stdClass();
		$model->fields = array(
			'presentado' => array('label' => 'Para ser presentado en:', 'type' => 'text', 'required' => TRUE)
		);

		$this->set_model_validation_rules($model);
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_division->id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$data['persona'] = $persona;
			$data['alumno'] = $alumno;
			$data['alumno_division'] = $alumno_division;
			$data['presentado'] = $this->input->post('presentado');
			$data['division'] = $division;
			$data['dias'] = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado');
			$data['escuela'] = $escuela;
			$content = $this->load->view('alumno_division/alumno_division_certificado_regular', $data, TRUE);

			$this->load->helper('mpdf');
			exportarMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', '.: Reporte de alumno regular - DGE :.', '', '', 'A4', '', 'I', FALSE, FALSE);
		}

		$data['fields'] = $this->build_fields($model->fields);
		$data['persona'] = $persona;
		$data['alumno'] = $alumno;
		$data['alumno_division'] = $alumno_division;
		$data['division'] = $division;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Generar certificado';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = 'Cerfiticado de alumno regular';
		$this->load->view('alumno_division/alumno_division_modal_certificado_regular', $data);
	}
}
/* End of file Alumno_division.php */
/* Location: ./application/controllers/Alumno_division.php */