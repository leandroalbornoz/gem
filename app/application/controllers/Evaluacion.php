<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluacion extends MY_Controller {

	function __construct() {
		parent::__construct();

		$this->load->model('cursada_nota_model');
		$this->load->model('cursada_model');
		$this->load->model('alumno_cursada_model');
		$this->load->model('evaluacion_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_DOCENTE_CURSADA, ROL_DOCENTE);
		$this->nav_route = 'alumnos/evaluacion';
	}

	public function modal_agregar($cursada_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $cursada_id == NULL || !ctype_digit($cursada_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}

		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}
		$cursada = $this->cursada_model->get_one($cursada_id);
		if (empty($cursada)) {
			return $this->modal_error('No se encontró el registro de la cursada', 'Registro no encontrado');
		}
		$this->load->model('division_model');
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			return $this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		$this->load->model('calendario_model');
		$calendario = $this->calendario_model->get_one($division->calendario_id);
		if (empty($calendario)) {
			return $this->modal_error('No se encontró el registro de el calendario', 'Registro no encontrado');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				show_error('No tiene permisos para acceder a la cursada', 500, 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				show_error('No tiene permisos para acceder a la cursada', 500, 'Acción no autorizada');
			}
		}
		$this->load->model('calendario_periodo_model');
		$this->array_periodo_control = $array_calendario_periodo = $this->get_array('calendario_periodo', 'descripcion', 'id', array(
			'select' => array('calendario_periodo.id', "CONCAT(calendario_periodo.periodo,'° ',calendario.nombre_periodo) as descripcion", 'calendario_periodo.periodo as periodo'),
			'join' => array(
				array('calendario', 'calendario.id = calendario_periodo.calendario_id')
			),
			'calendario_id' => $calendario->id,
			'ciclo_lectivo' => $ciclo_lectivo
		));
		$this->evaluacion_model->fields['periodo']['array'] = $array_calendario_periodo;
		$this->evaluacion_model->fields['periodo']['value'] = $array_calendario_periodo[array_keys($array_calendario_periodo)[0]];
		$this->evaluacion_model->fields['ciclo_lectivo']['value'] = $ciclo_lectivo;
		$this->evaluacion_model->fields['ciclo_lectivo']['value'] = $ciclo_lectivo;
		$this->evaluacion_model->fields['tema']['value'] = 'Nota Cierre ' . $array_calendario_periodo[array_keys($array_calendario_periodo)[0]];
		$periodo = $this->calendario_periodo_model->get_one(array_keys($array_calendario_periodo)[0]);
		$this->evaluacion_model->fields['fecha']['value'] = (new DateTime($periodo->fin))->format('d/m/Y');

		$this->load->model('evaluacion_tipo_model');
		$this->array_evaluacion_tipo_control = $array_evaluacion_tipo = $this->get_array('evaluacion_tipo');
		$this->evaluacion_model->fields['evaluacion_tipo']['array'] = $array_evaluacion_tipo;

		$this->set_model_validation_rules($this->evaluacion_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->evaluacion_model->create(array(
					'evaluacion_tipo_id' => $this->input->post('evaluacion_tipo'),
					'fecha' => $this->get_date_sql('fecha'),
					'cursada_id' => $cursada->id,
					'tema' => $this->input->post('tema'),
					'ciclo_lectivo' => $this->input->post('ciclo_lectivo'),
					'calendario_periodo_id' => $this->input->post('periodo')
					), FALSE);
				$evaluacion_id = $this->evaluacion_model->get_row_id();
				if ($trans_ok && $this->db->trans_status()) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->evaluacion_model->get_msg());
					redirect("evaluacion/editar/$evaluacion_id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar.<br>';
					if ($this->evaluacion_model->get_error()) {
						$errors .= $this->evaluacion_model->get_error() . '<br>';
					}
					$this->session->set_flashdata('error', $errors);
					redirect("cursada/escritorio/$cursada->id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("cursada/escritorio/$cursada->id", 'refresh');
			}
		}

		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['cursada'] = $cursada;
		$data['fields'] = $this->build_fields($this->evaluacion_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar evaluación';
		$this->load->view('evaluacion/evaluacion_modal_abm', $data);
	}

	public function modal_agregar_concepto($cursada_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $cursada_id == NULL || !ctype_digit($cursada_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}
		$cursada = $this->cursada_model->get_one($cursada_id);
		if (empty($cursada)) {
			return $this->modal_error('No se encontró el registro de la cursada', 'Registro no encontrado');
		}
		$this->load->model('division_model');
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			return $this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		$this->load->model('calendario_model');
		$calendario = $this->calendario_model->get_one($division->calendario_id);
		if (empty($calendario)) {
			return $this->modal_error('No se encontró el registro de el calendario', 'Registro no encontrado');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				show_error('No tiene permisos para acceder a la cursada', 500, 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				show_error('No tiene permisos para acceder a la cursada', 500, 'Acción no autorizada');
			}
		}
		$this->load->model('calendario_periodo_model');
		$this->array_periodo_control = $array_calendario_periodo = $this->get_array('calendario_periodo', 'descripcion', 'id', array(
			'select' => array('calendario_periodo.id', "CONCAT(calendario_periodo.periodo,'° ',calendario.nombre_periodo) as descripcion", 'calendario_periodo.periodo as periodo'),
			'join' => array(
				array('calendario', 'calendario.id = calendario_periodo.calendario_id')
			),
			'calendario_id' => $calendario->id,
			'ciclo_lectivo' => $ciclo_lectivo
		));
		$this->load->model('evaluacion_espacio_curricular_model');
		$evaluacion_espacio_curricular_conceptos = $this->evaluacion_espacio_curricular_model->get_conceptos_evaluacion($cursada->espacio_curricular_id);
		$this->evaluacion_model->fields['periodo']['array'] = $array_calendario_periodo;
		$this->evaluacion_model->fields['periodo']['value'] = $array_calendario_periodo[array_keys($array_calendario_periodo)[0]];
		$this->evaluacion_model->fields['ciclo_lectivo']['value'] = $ciclo_lectivo;
		$this->evaluacion_model->fields['ciclo_lectivo']['value'] = $ciclo_lectivo;
		unset($this->evaluacion_model->fields['tema']);
		$periodo = $this->calendario_periodo_model->get_one(array_keys($array_calendario_periodo)[0]);
		$this->evaluacion_model->fields['fecha']['value'] = (new DateTime($periodo->fin))->format('d/m/Y');

		$this->load->model('evaluacion_tipo_model');
		$this->array_evaluacion_tipo_control = $array_evaluacion_tipo = $this->get_array('evaluacion_tipo');
		$this->evaluacion_model->fields['evaluacion_tipo']['array'] = $array_evaluacion_tipo;

		$this->set_model_validation_rules($this->evaluacion_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$evaluacion_espacio_ids = $this->evaluacion_espacio_curricular_model->get(array('select' => 'id,concepto', 'espacio_curricular_id' => $cursada->espacio_curricular_id));
				$this->db->trans_begin();
				$trans_ok = TRUE;
				foreach ($evaluacion_espacio_ids as $evaluacion_espacio_id) {
					$trans_ok &= $this->evaluacion_model->create(array(
						'evaluacion_tipo_id' => $this->input->post('evaluacion_tipo'),
						'fecha' => $this->get_date_sql('fecha'),
						'cursada_id' => $cursada->id,
						'tema' => $evaluacion_espacio_id->concepto,
						'ciclo_lectivo' => $this->input->post('ciclo_lectivo'),
						'calendario_periodo_id' => $this->input->post('periodo')
						), FALSE);
				}
				if ($trans_ok && $this->db->trans_status()) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->evaluacion_model->get_msg());
					redirect("cursada/escritorio/$cursada->id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar.<br>';
					if ($this->evaluacion_model->get_error()) {
						$errors .= $this->evaluacion_model->get_error() . '<br>';
					}
					$this->session->set_flashdata('error', $errors);
					redirect("cursada/escritorio/$cursada->id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("cursada/escritorio/$cursada->id", 'refresh');
			}
		}

		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['evaluacion_espacio_curricular_conceptos'] = $evaluacion_espacio_curricular_conceptos;
		$data['cursada'] = $cursada;
		$data['fields'] = $this->build_fields($this->evaluacion_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar evaluación por concepto';
		$this->load->view('evaluacion/evaluacion_concepto_modal_abm', $data);
	}

	public function modal_editar($evaluacion_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $evaluacion_id == NULL || !ctype_digit($evaluacion_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}
		$evaluacion = $this->evaluacion_model->get_one($evaluacion_id);
		if (empty($evaluacion)) {
			return $this->modal_error('No se encontró el registro de la cursada', 'Registro no encontrado');
		}
		$cursada = $this->cursada_model->get_one($evaluacion->cursada_id);
		if (empty($cursada)) {
			return $this->modal_error('No se encontró el registro de la cursada', 'Registro no encontrado');
		}
		$this->load->model('division_model');
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			return $this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		$this->load->model('calendario_model');
		$calendario = $this->calendario_model->get_one($division->calendario_id);
		if (empty($calendario)) {
			return $this->modal_error('No se encontró el registro de el calendario', 'Registro no encontrado');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				return $this->modal_error('No tiene permisos para acceder a la cursada', 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				show_error('No tiene permisos para acceder a la cursada', 500, 'Acción no autorizada');
			}
		}
		$cursada_nota = $this->cursada_nota_model->verificar_cursada_nota($evaluacion_id);
		if (!empty($cursada_nota)) {
			return $this->modal_error('No se puede editar la evaluación si posee notas cargadas', 'Accion no permitida');
		}

		$this->load->model('calendario_periodo_model');
		$this->array_periodo_control = $array_calendario_periodo = $this->get_array('calendario_periodo', 'descripcion', 'id', array(
			'select' => array('calendario_periodo.id', "CONCAT(calendario_periodo.periodo,'° ',calendario.nombre_periodo) as descripcion", 'calendario_periodo.periodo as periodo'),
			'join' => array(
				array('calendario', 'calendario.id = calendario_periodo.calendario_id')),
			'where' => array("calendario.id = {$calendario->id} AND calendario_periodo.ciclo_lectivo = $ciclo_lectivo")
			), array('' => '-- Seleccionar periodo --'));

		$this->evaluacion_model->fields['periodo']['array'] = $array_calendario_periodo;

		$this->load->model('evaluacion_tipo_model');
		$this->array_evaluacion_tipo_control = $array_evaluacion_tipo = $this->get_array('evaluacion_tipo', 'descripcion', 'id', null, array('' => '-- Seleccionar tipo de evaluación --'));

		$this->set_model_validation_rules($this->evaluacion_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($cursada->id != $this->input->post('cursada_id')) {
				show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->evaluacion_model->update(array(
					'id' => $evaluacion->id,
					'fecha' => $this->get_date_sql('fecha'),
					'cursada_id' => $cursada->id,
					'tema' => $this->input->post('tema'),
					'ciclo_lectivo' => $this->input->post('ciclo_lectivo'),
					'calendario_periodo_id' => $this->input->post('periodo'),
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->evaluacion_model->get_msg());
					redirect("cursada/escritorio/$cursada->id", 'refresh');
				} else {
					$this->session->set_flashdata('error', $this->evaluacion_model->get_error());
					redirect("cursada/escritorio/$cursada->id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("cursada/escritorio/$cursada->id", 'refresh');
			}
		}
		$this->evaluacion_model->fields['evaluacion_tipo']['array'] = $array_evaluacion_tipo;
		$this->evaluacion_model->fields['evaluacion_tipo']['disabled'] = TRUE;
		$data['fields'] = $this->build_fields($this->evaluacion_model->fields, $evaluacion);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['txt_btn'] = 'Editar';
		$data['evaluacion'] = $evaluacion;
		$data['cursada'] = $cursada;
		$data['title'] = 'Editar evaluación';
		$this->load->view('evaluacion/evaluacion_modal_abm', $data);
	}

	public function editar($evaluacion_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $evaluacion_id == NULL || !ctype_digit($evaluacion_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}
		$evaluacion = $this->evaluacion_model->get_one($evaluacion_id);
		if (empty($evaluacion)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$cursada = $this->cursada_model->get_one($evaluacion->cursada_id);
		if (empty($cursada)) {
			show_error('No se encontró el registro de la cursada', 500, 'Registro no encontrado');
		}
		$this->load->model('division_model');
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				show_error('No tiene permisos para acceder a la cursada', 500, 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				show_error('No tiene permisos para acceder a la cursada', 500, 'Acción no autorizada');
			}
		}
		$alumnos_notas = $this->cursada_nota_model->get_alumnos_evaluacion($evaluacion);
		$this->array_asistencia_control = $this->cursada_nota_model->fields['asistencia']['array'];
		if ($division->calificacion_id === '1') {
			$this->array_nota_concepto_control = $this->cursada_nota_model->fields['nota_concepto']['array'];
			$this->form_validation->set_rules("nota[]", 'Nota', 'callback_control_combo[nota_concepto]');
		} else {
			$this->form_validation->set_rules('nota[]', 'Nota', 'numeric');
		}
		$this->form_validation->set_rules("asistencia[]", 'Asistencia', 'callback_control_combo[asistencia]');
		$errors = FALSE;
		if (isset($_POST) && !empty($_POST)) {
			if ($evaluacion_id !== $this->input->post('evaluacion_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$asistencias = $this->input->post('asistencia');
				$notas = $this->input->post('nota');
				$cursada_nota_ids = $this->input->post('cursada_nota_ids');
				$trans_ok = TRUE;
				$this->load->model('alumno_cursada_model');
				foreach ($alumnos_notas as $alumno) {
					if (empty($alumno->id) || empty($alumno->cursada_nota_id)) {
						if (empty($alumno->id) && empty($alumno->cursada_nota_id) && !(empty($notas[$alumno->alumno_division_id]) && $asistencias[$alumno->alumno_division_id] === 'Presente' && empty($cursada_nota_ids[$alumno->alumno_division_id]))) {
							$trans_ok &= $this->alumno_cursada_model->create(array(
								'alumno_division_id' => $alumno->alumno_division_id,
								'cursada_id' => $cursada->id), FALSE);
							$alumno_cursada_id = $this->alumno_cursada_model->get_row_id();
							$trans_ok &= $this->cursada_nota_model->create(array(
								'evaluacion_id' => $evaluacion->id,
								'alumno_cursada_id' => $alumno_cursada_id,
								'nota' => $notas[$alumno->alumno_division_id],
								'asistencia' => $asistencias[$alumno->alumno_division_id]
								), FALSE);
						} elseif (!empty($alumno->id) && empty($alumno->cursada_nota_id) && !(empty($notas[$alumno->id]) && $asistencias[$alumno->id] === 'Presente' && empty($cursada_nota_ids[$alumno->id]))) {
							$trans_ok &= $this->cursada_nota_model->create(array(
								'evaluacion_id' => $evaluacion->id,
								'alumno_cursada_id' => $alumno->id,
								'nota' => (!isset($notas[$alumno->id])) ? NULL : $notas[$alumno->id],
								'asistencia' => $asistencias[$alumno->id]
								), FALSE);
						}
					} else {
						if (!empty($notas[$alumno->id]) || $asistencias[$alumno->id] === 'Ausente') {
							$alumno_cursada_id = $alumno->id;
							$trans_ok &= $this->cursada_nota_model->update(array(
								'id' => $cursada_nota_ids[$alumno->id],
								'evaluacion_id' => $evaluacion->id,
								'alumno_cursada_id' => $alumno_cursada_id,
								'nota' => (!isset($notas[$alumno->id])) ? NULL : $notas[$alumno->id],
								'asistencia' => $asistencias[$alumno->id]
								), FALSE);
						} elseif (empty($notas[$alumno->id]) && $asistencias[$alumno->id] === 'Presente' && $cursada_nota_ids[$alumno->id] !== '') {
							$trans_ok &= $this->cursada_nota_model->delete(array(
								'id' => $cursada_nota_ids[$alumno->id]
							));
						}
					}
				}
				if ($trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->cursada_nota_model->get_msg());
					redirect('evaluacion/ver/' . $evaluacion->id, 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->cursada_nota_model->get_error()) {
						$errors .= '<br>' . $this->cursada_nota_model->get_error();
					}
					if ($this->alumno_cursada_model->get_error()) {
						$errors .= '<br>' . $this->alumno_cursada_model->get_error();
					}
					if ($this->evaluacion_model->get_error()) {
						$errors .= '<br>' . $this->evaluacion_model->get_error();
					}
					$this->session->set_flashdata('error', $errors);
					redirect('evaluacion/ver/' . $evaluacion->id, 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('evaluacion/ver/' . $evaluacion->id, 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->evaluacion_model->fields, $evaluacion, TRUE);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['escuela'] = $escuela;
		$data['alumnos_notas'] = $alumnos_notas;
		$data['division'] = $division;
		$data['evaluacion'] = $evaluacion;
		$data['cursada'] = $cursada;
		$data['class'] = array('ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar evaluación';
		$this->load_template('evaluacion/evaluacion_abm', $data);
	}

	public function ver($evaluacion_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $evaluacion_id == NULL || !ctype_digit($evaluacion_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$evaluacion = $this->evaluacion_model->get_one($evaluacion_id);
		if (empty($evaluacion)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$cursada = $this->cursada_model->get_one($evaluacion->cursada_id);
		if (empty($cursada)) {
			show_error('No se encontró el registro de la cursada', 500, 'Registro no encontrado');
		}
		$this->load->model('division_model');
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				show_error('No tiene permisos para acceder a la cursada', 500, 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				show_error('No tiene permisos para acceder a la cursada', 500, 'Acción no autorizada');
			}
		}
		$alumnos_notas = $this->cursada_nota_model->get_alumnos_evaluacion($evaluacion);
		$data['fields'] = $this->build_fields($this->evaluacion_model->fields, $evaluacion, TRUE);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['escuela'] = $escuela;
		$data['alumnos_notas'] = $alumnos_notas;
		$data['division'] = $division;
		$data['evaluacion'] = $evaluacion;
		$data['cursada'] = $cursada;
		$data['class'] = array('ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['txt_btn'] = 'Ver';
		$data['title'] = 'Ver evaluación';
		$this->load_template('evaluacion/evaluacion_abm', $data);
	}

	public function eliminar($evaluacion_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $evaluacion_id == NULL || !ctype_digit($evaluacion_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$evaluacion = $this->evaluacion_model->get_one($evaluacion_id);
		if (empty($evaluacion)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$cursada = $this->cursada_model->get_one($evaluacion->cursada_id);
		if (empty($cursada)) {
			show_error('No se encontró el registro de la cursada', 500, 'Registro no encontrado');
		}
		$this->load->model('division_model');
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				show_error('No tiene permisos para acceder a la cursada', 500, 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				show_error('No tiene permisos para acceder a la cursada', 500, 'Acción no autorizada');
			}
		}
		$this->load->model('evaluacion_tipo_model');
		$this->array_evaluacion_tipo_control = $array_evaluacion_tipo = $this->get_array('evaluacion_tipo', 'descripcion');
		$alumnos_notas = $this->cursada_nota_model->get_alumnos_evaluacion($evaluacion);

		$this->array_asistencia_control = $this->cursada_nota_model->fields['asistencia']['array'];
		$this->form_validation->set_rules('nota[]', 'Nota', 'numeric');
		$errors = FALSE;
		if (isset($_POST) && !empty($_POST)) {
			if ($evaluacion_id !== $this->input->post('evaluacion_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$notas_evaluacion = $this->evaluacion_model->get_notas_evalaucion($evaluacion->id);
				$this->db->trans_begin();
				$trans_ok = TRUE;
				foreach ($notas_evaluacion as $nota_evaluacion) {
					$trans_ok &= $this->cursada_nota_model->delete(array(
						'id' => $nota_evaluacion->id
						), FALSE);
				}
				$trans_ok &= $this->evaluacion_model->delete(array(
					'id' => $evaluacion->id
					), FALSE);
				if ($trans_ok) {
					$this->db->trans_commit();
					$message = '';
					if ($this->evaluacion_model->get_msg()) {
						$message .= $this->evaluacion_model->get_msg();
					}
					if ($this->cursada_nota_model->get_msg()) {
						$message .= '<br>' . $this->cursada_nota_model->get_msg();
					}
					$this->session->set_flashdata('message', $message);
					redirect('cursada/escritorio/' . $cursada->id, 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->cursada_nota_model->get_error()) {
						$errors .= '<br>' . $this->cursada_nota_model->get_error();
					}
					if ($this->evaluacion_model->get_error()) {
						$errors .= '<br>' . $this->evaluacion_model->get_error();
					}
					$this->session->set_flashdata('error', $errors);
					redirect('cursada/escritorio/' . $cursada->id, 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('cursada/escritorio/' . $cursada->id, 'refresh');
			}
		}
		$data['fields'] = $this->build_fields($this->evaluacion_model->fields, $evaluacion, TRUE);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['escuela'] = $escuela;
		$data['alumnos_notas'] = $alumnos_notas;
		$data['division'] = $division;
		$data['evaluacion'] = $evaluacion;
		$data['cursada'] = $cursada;
		$data['class'] = array('ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar evaluación';
		$this->load_template('evaluacion/evaluacion_abm', $data);
	}

	public function modal_seleccion_periodo($cursada_id = NULL, $alumno_division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $cursada_id == NULL || !ctype_digit($cursada_id) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}
		$cursada = $this->cursada_model->get_one($cursada_id);
		if (empty($cursada)) {
			return $this->modal_error('No se encontró el registro de la cursada', 'Registro no encontrado');
		}
		$this->load->model('division_model');
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			return $this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		$this->load->model('calendario_model');
		$calendario = $this->calendario_model->get_one($division->calendario_id);
		if (empty($calendario)) {
			return $this->modal_error('No se encontró el registro de el calendario', 'Registro no encontrado');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			return $this->modal_error('No se encontró el registro del alumno en la division', 'Registro no encontrado');
		}
		$this->load->model("alumno_model");
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		if (empty($alumno)) {
			return $this->modal_error('No se encontró el registro del alumno', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				return $this->modal_error('No tiene permisos para acceder a la cursada', 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				return $this->modal_error('No tiene permisos para acceder a la cursada', 'Acción no autorizada');
			}
		}
		$model = new stdClass();
		$model->fields = array(
			'periodo' => array('label' => 'Periodo', 'input_type' => 'combo', 'id_name' => 'calendario_periodo_id', 'required' => TRUE),
			'ciclo_lectivo' => array('label' => 'Ciclo Lectivo', 'type' => 'number', 'required' => TRUE, 'readonly' => TRUE)
		);
		$this->load->model('calendario_periodo_model');
		$this->array_calendario_periodo_control = $array_calendario_periodo = $this->get_array('calendario_periodo', 'descripcion', 'id', array(
			'select' => array('calendario_periodo.id', "CONCAT(calendario_periodo.periodo,'° ',calendario.nombre_periodo) as descripcion", 'calendario_periodo.periodo as periodo'),
			'join' => array(
				array('calendario', 'calendario.id = calendario_periodo.calendario_id')),
			'where' => array("calendario.id = {$calendario->id} AND calendario_periodo.ciclo_lectivo = $ciclo_lectivo")
			), array('' => '-- Seleccionar periodo --'));
		$model->fields['periodo']['array'] = $array_calendario_periodo;
		$model->fields['ciclo_lectivo']['value'] = $ciclo_lectivo;
		if (isset($_POST) && !empty($_POST)) {
			if ($cursada->id !== $this->input->post('cursada_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$calendario_periodo_id = $this->input->post('periodo');
			$calendario_periodo = $this->calendario_periodo_model->get_one($calendario_periodo_id);
			if (empty($calendario_periodo)) {
				$this->session->set_flashdata('error', 'El periodo seleccionado es incorrecto');
				redirect("cursada/escritorio/{$cursada->id}", 'refresh');
			} else {
				$this->session->set_flashdata('message', 'Periodo seleccionado correctamente');
				redirect("evaluacion/cargar_notas_alumno/{$cursada->id}/{$alumno_division->id}/{$calendario_periodo_id}", 'refresh');
			}
		}
		$data['fields'] = $this->build_fields($model->fields);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['txt_btn'] = 'Cargar';
		$data['cursada'] = $cursada;
		$data['title'] = 'Cargar evaluaciones del periodo';
		$this->load->view('evaluacion/evaluacion_modal_seleccion_periodo', $data);
	}

	public function cargar_notas_alumno($cursada_id = NULL, $alumno_division_id = NULL, $calendario_periodo_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $cursada_id == NULL || !ctype_digit($cursada_id) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id) || $calendario_periodo_id == NULL || !ctype_digit($calendario_periodo_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$cursada = $this->cursada_model->get_one($cursada_id);
		if (empty($cursada)) {
			show_error('No se encontró el registro de la cursada', 500, 'Registro no encontrado');
		}
		$this->load->model('division_model');
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$this->load->model('calendario_model');
		$calendario = $this->calendario_model->get_one($division->calendario_id);
		if (empty($calendario)) {
			return $this->modal_error('No se encontró el registro de el calendario', 'Registro no encontrado');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			show_error('No se encontró el registro del alumno en la division', 500, 'Registro no encontrado');
		}
		$this->load->model("alumno_model");
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		if (empty($alumno)) {
			show_error('No se encontró el registro del alumno', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				show_error('No tiene permisos para acceder a la cursada', 500, 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				show_error('No tiene permisos para acceder a la cursada', 500, 'Acción no autorizada');
			}
		}
		$this->load->model('calendario_periodo_model');
		$calendario_periodo = $this->calendario_periodo_model->get_one($calendario_periodo_id);
		if (empty($calendario_periodo)) {
			show_error('No se encontró el registro del periodo', 500, 'Registro no encontrado');
		}
		$evaluaciones = $this->evaluacion_model->get_evaluaciones_alumno_periodo($cursada->id, $alumno_division, $calendario_periodo->id);
		$this->array_asistencia_control = $this->cursada_nota_model->fields['asistencia']['array'];
		$this->form_validation->set_rules("asistencia[]", 'Asistencia', 'callback_control_combo[asistencia]');
		$this->form_validation->set_rules('nota[]', 'Nota', 'numeric');
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_division->id !== $this->input->post('alumno_division_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$asistencias = $this->input->post('asistencia');
				$notas = $this->input->post('nota');
				$cursada_nota_ids = $this->input->post('cursada_nota_ids');
				$trans_ok = TRUE;
				$this->load->model('alumno_cursada_model');
				foreach ($evaluaciones as $evaluacion_alumno) {
					if (empty($evaluacion_alumno->alumno_cursada_id) || empty($evaluacion_alumno->cursada_nota_id)) {
						if (empty($evaluacion_alumno->alumno_cursada_id) && empty($evaluacion_alumno->cursada_nota_id) && !(empty($notas[$evaluacion_alumno->id]) && $asistencias[$evaluacion_alumno->id] === 'Presente' && empty($cursada_nota_ids[$evaluacion_alumno->id]))) {
							$trans_ok &= $this->alumno_cursada_model->create(array(
								'alumno_division_id' => $evaluacion_alumno->alumno_division_id,
								'cursada_id' => $cursada->id), FALSE);
							$evaluacion_alumno_cursada_id = $this->alumno_cursada_model->get_row_id();
							$trans_ok &= $this->cursada_nota_model->create(array(
								'evaluacion_id' => $evaluacion_alumno->id,
								'alumno_cursada_id' => $evaluacion_alumno_cursada_id,
								'nota' => $notas[$evaluacion_alumno->id],
								'asistencia' => $asistencias[$evaluacion_alumno->id]
								), FALSE);
						} elseif (!empty($evaluacion_alumno->alumno_cursada_id) && empty($evaluacion_alumno->cursada_nota_id) && !(empty($notas[$evaluacion_alumno->id]) && $asistencias[$evaluacion_alumno->id] === 'Presente' && empty($cursada_nota_ids[$evaluacion_alumno->id]))) {
							$trans_ok &= $this->cursada_nota_model->create(array(
								'evaluacion_id' => $evaluacion_alumno->id,
								'alumno_cursada_id' => $evaluacion_alumno->alumno_cursada_id,
								'nota' => $notas[$evaluacion_alumno->id],
								'asistencia' => $asistencias[$evaluacion_alumno->id]
								), FALSE);
						}
					} else {
						if (!empty($notas[$evaluacion_alumno->id]) || $asistencias[$evaluacion_alumno->id] === 'Ausente') {
							$trans_ok &= $this->cursada_nota_model->update(array(
								'id' => $cursada_nota_ids[$evaluacion_alumno->id],
								'evaluacion_id' => $evaluacion_alumno->id,
								'alumno_cursada_id' => $evaluacion_alumno->alumno_cursada_id,
								'nota' => $notas[$evaluacion_alumno->id],
								'asistencia' => $asistencias[$evaluacion_alumno->id]
								), FALSE);
						} elseif (empty($notas[$evaluacion_alumno->id]) && $asistencias[$evaluacion_alumno->id] === 'Presente' && $cursada_nota_ids[$evaluacion_alumno->id] !== '') {
							$trans_ok &= $this->cursada_nota_model->delete(array(
								'id' => $cursada_nota_ids[$evaluacion_alumno->id]
							));
						}
					}
				}
				if ($trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->cursada_nota_model->get_msg());
					redirect("evaluacion/cargar_notas_alumno/{$cursada->id}/{$alumno_division->id}/{$calendario_periodo->id}", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->cursada_nota_model->get_error()) {
						$errors .= '<br>' . $this->cursada_nota_model->get_error();
					}
					if ($this->alumno_cursada_model->get_error()) {
						$errors .= '<br>' . $this->alumno_cursada_model->get_error();
					}
					if ($this->evaluacion_model->get_error()) {
						$errors .= '<br>' . $this->evaluacion_model->get_error();
					}
					$this->session->set_flashdata('error', $errors);
					redirect("evaluacion/cargar_notas_alumno/{$cursada->id}/{$alumno_division->id}/{$calendario_periodo->id}", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("evaluacion/cargar_notas_alumno/{$cursada->id}/{$alumno_division->id}/{$calendario_periodo->id}", 'refresh');
			}
		}

		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['escuela'] = $escuela;
		$data['evaluaciones'] = $evaluaciones;
		$data['calendario_periodo'] = $calendario_periodo;
		$data['division'] = $division;
		$data['alumno_division'] = $alumno_division;
		$data['alumno'] = $alumno;
		$data['cursada'] = $cursada;
		$data['class'] = array('ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Cargar notas de las evaluaciones';
		$this->load_template('evaluacion/cargar_notas_alumno', $data);
	}
}
/* End of file Evaluacion.php */
/* Location: ./application/controllers/Evaluacion.php */