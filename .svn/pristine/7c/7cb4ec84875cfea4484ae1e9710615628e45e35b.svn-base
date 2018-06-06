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
					'periodo' => $this->input->post('periodo')
					), FALSE);
				if ($cursada->alumnos === 'Todos') {
					$this->load->model('alumno_cursada_model');
					$alumnos = $this->alumno_cursada_model->get_alumnos_cursada($cursada, $ciclo_lectivo, '');
					foreach ($alumnos as $alumno) {
						if ($alumno->id === NULL) {
							$trans_ok &= $this->alumno_cursada_model->create(array(
								'alumno_division_id' => $alumno->alumno_division_id,
								'cursada_id' => $cursada->id), FALSE);
						}
					}
				}
				if ($trans_ok && $this->db->trans_status()) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->evaluacion_model->get_msg());
					redirect("cursada/escritorio/$cursada->id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar.<br>';
					if ($this->alumno_cursada_model->get_error()) {
						$errors .= $this->alumno_cursada_model->get_error() . '<br>';
					}
					if ($this->evaluacion_model->get_error()) {
						$errors .= $this->evaluacion_model->get_error() . '<br>';
					}
					$this->session->flashdata('error', $errors);
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
					'periodo' => $this->input->post('periodo'),
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
		$this->form_validation->set_rules("asistencia[]", 'Asistencia', 'callback_control_combo[asistencia]');
		$this->form_validation->set_rules('nota[]', 'Nota', 'numeric');
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
				foreach ($notas as $key => $nota) {
					if (!empty($nota) || $asistencias[$key] === 'Ausente') {
						if (!empty($cursada_nota_ids[$key])) {
							$trans_ok &= $this->cursada_nota_model->update(array(
								'id' => $cursada_nota_ids[$key],
								'evaluacion_id' => $evaluacion->id,
								'alumno_cursada_id' => $key,
								'nota' => $notas[$key],
								'asistencia' => $asistencias[$key]
								), FALSE);
						} else {
							$trans_ok &= $this->cursada_nota_model->create(array(
								'evaluacion_id' => $evaluacion->id,
								'alumno_cursada_id' => $key,
								'nota' => $notas[$key],
								'asistencia' => $asistencias[$key]
								), FALSE);
						}
					} elseif (empty($nota) && $asistencias[$key] === 'Presente' && $cursada_nota_ids[$key] !== '') {
						$trans_ok &= $this->cursada_nota_model->delete(array(
							'id' => $cursada_nota_ids[$key]
						));
					}
				}
				if ($trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->cursada_nota_model->get_msg());
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
}
/* End of file Evaluacion.php */
/* Location: ./application/controllers/Evaluacion.php */