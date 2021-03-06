<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cursada extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('cursada_model');
		$this->load->model('cargo_cursada_model');
		$this->load->model('division_model');
		$this->load->model('escuela_model');
		$this->load->model('cargo_cursada_model');
		$this->load->model('cursada_nota_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ESCUELA_ALUM, ROL_ASISTENCIA_DIVISION));
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_ESCUELA_ALUM, ROL_GRUPO_ESCUELA_CONSULTA))) {
			$this->edicion = FALSE;
		}
		$this->roles_administrar = array(ROL_ADMIN, ROL_USI, ROL_DIR_ESCUELA);
		$this->nav_route = 'menu/cursada';
	}

	public function escritorio($cursada_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $cursada_id == NULL || !ctype_digit($cursada_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}
		$cursada = $this->cursada_model->get_one($cursada_id);
		if (empty($cursada)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$this->load->model('calendario_model');
		$calendario = $this->calendario_model->get_one($division->calendario_id);
		if (empty($calendario)) {
			return $this->modal_error('No se encontró el registro de el calendario', 'Registro no encontrado');
		}
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
		$this->load->model('calendario_periodo_model');
		$periodos = $this->calendario_periodo_model->get_periodos_ciclo_lectivo($calendario->id, $ciclo_lectivo);

		$this->load->model('evaluacion_espacio_curricular_model');
		$data['array_evaluacion_espacio_curricular_id'] = $this->evaluacion_espacio_curricular_model->get_espacios_curriculares_id();
		$this->load->model('alumno_cursada_model');
		$alumnos = $this->alumno_cursada_model->get_alumnos_cursada($cursada, $ciclo_lectivo, $cursada->alumnos);
		$data['alumnos'] = $alumnos;
		$cargos_cursada = $this->cargo_cursada_model->get_cargos_cursada($cursada->id);
		$data['cargos_cursada'] = $cargos_cursada;
		$this->load->model('evaluacion_model');
		$evaluaciones = $this->evaluacion_model->get_evaluaciones_cursada($cursada->id);
		$data['evaluaciones'] = $evaluaciones;
		$data['escuela'] = $escuela;
		$data['periodos'] = $periodos;
		$data['cursada'] = $cursada;
		$data['usuarios'] = $this->usuarios_model->get_usuarios_cursada($cursada->id);
		$data['division'] = $division;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Cursadas';
		$this->load_template('cursada/escritorio_cursada', $data);
	}

	public function listar($division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (in_array($this->rol->codigo, array(ROL_DOCENTE_CURSADA, ROL_DOCENTE))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$division = $this->division_model->get_one($division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		$cursadas_division = $this->cursada_model->get_cursadas($division->id);
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$data['cursadas_division'] = $cursadas_division;
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Cursadas';
		$this->load_template('cursada/cursada_listar', $data);
	}

	public function agregar($division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (in_array($this->rol->codigo, array(ROL_DOCENTE_CURSADA, ROL_DOCENTE))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$division = $this->division_model->get_one($division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		$cursadas_division = $this->cursada_model->get_cursadas($division->id);
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('espacio_curricular_model');
		$array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
			'select' => array('espacio_curricular.id', 'materia.descripcion materia'),
			'join' => array(
				array('division', 'division.curso_id=espacio_curricular.curso_id AND division.carrera_id=espacio_curricular.carrera_id'),
				array('materia', "materia.id=espacio_curricular.materia_id AND materia.es_grupo='No'")
			),
			'where' => array("division.id=$division->id")
		));
		$array_cuatrimestre = $this->get_array('espacio_curricular', 'cuatrimestre', 'id', array(
			'select' => array('espacio_curricular.id', 'espacio_curricular.cuatrimestre'),
			'join' => array(
				array('division', 'division.curso_id=espacio_curricular.curso_id AND division.carrera_id=espacio_curricular.carrera_id'),
				array('materia', 'materia.id=espacio_curricular.materia_id')
			),
			'where' => array("division.id=$division->id")
		));
		$array_carga_horaria = $this->get_array('espacio_curricular', 'carga_horaria', 'id', array(
			'select' => array('espacio_curricular.id', 'espacio_curricular.carga_horaria'),
			'join' => array(
				array('division', 'division.curso_id=espacio_curricular.curso_id AND division.carrera_id=espacio_curricular.carrera_id'),
				array('materia', 'materia.id=espacio_curricular.materia_id')
			),
			'where' => array("division.id=$division->id")
		));
		$this->array_espacio_curricular_control = $array_espacio_curricular;
		if ($escuela->nivel_id === '7') {
			$this->array_cuatrimestre_control = array('0' => '0', '1' => '1', '2' => '2');
		}
		$this->form_validation->set_rules("cursada[]", 'EC', 'callback_control_combo[espacio_curricular]|required');
		if ($escuela->nivel_id === '7') {
			$this->form_validation->set_rules("cuatrimestre[]", 'Cuatrimestre', 'callback_control_combo[cuatrimestre]');
		}
		$this->form_validation->set_rules('carga_horaria[]', 'Carga horaria', 'integer');
		if (isset($_POST) && !empty($_POST)) {
			if (empty($this->input->post('cursada'))) {
				$this->session->set_flashdata('error', 'Debe seleccionar al menos una materia');
				redirect("cursada/agregar/$division_id", 'refresh');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$p_carga_horaria = $this->input->post('carga_horaria');
				$p_cursadas = $this->input->post('cursada');
				$p_grupos = $this->input->post('grupo');
				$p_cuatrimestre = $this->input->post('cuatrimestre');
				$p_alumnos = $this->input->post('alumnos');
				$trans_ok = TRUE;
				foreach ($p_cursadas as $p_id => $p_cursada) {
					$consulta = $this->cursada_model->consulta($p_grupos[$p_id], $p_cursadas[$p_id], $division->id);
					if (!empty($consulta)) {
						$errors = "Cursada ya cargada:";
						foreach ($consulta as $materia) {
							$errors .= '<br>' . $materia->materia;
						}
						$this->session->set_flashdata('error', $errors);
						redirect("cursada/agregar/$division->id", 'refresh');
					}
					if ($p_carga_horaria[$p_id] <= 0) {//El gusti dijo que va asi, no lo cambies
						$errors = "Carga horaria invalida verifique los datos enviados.";
						$this->session->set_flashdata('error', $errors);
						redirect("cursada/agregar/$division->id", 'refresh');
					}
				}
				foreach ($p_cursadas as $p_id => $p_cursada) {
					$trans_ok &= $this->cursada_model->create(array(
						'division_id' => $division->id,
						'espacio_curricular_id' => $p_cursadas[$p_id],
						'carga_horaria' => $p_carga_horaria[$p_id],
						'cuatrimestre' => $p_cuatrimestre[$p_id],
						'alumnos' => $p_alumnos[$p_id],
						'grupo' => $p_grupos[$p_id],
						'fecha_desde' => '2017-01-01')
					);
				}
				if ($trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->cursada_model->get_msg());
					redirect("cursada/listar/$division_id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->cursada_model->get_error());
					redirect("cursada/listar/$division_id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("cursada/listar/$division_id", 'refresh');
			}
		}

		$data['cursadas_division'] = $cursadas_division;
		$data['cursadas'] = $this->cursada_model->get_by_division($division->id);
		$data['espacios_curriculares'] = $array_espacio_curricular;
		$data['cuatrimestre'] = $array_cuatrimestre;
		$data['carga_horaria'] = $array_carga_horaria;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = TITLE . ' - Agregar cursada';
		$this->load_template('cursada/cursada_agregar', $data);
	}

	public function modal_agregar($division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		if ($this->edicion === FALSE) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$division = $this->division_model->get_one($division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			return $this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				return $this->modal_error('No tiene permisos para editar esta cursada', 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				return $this->modal_error('No tiene permisos para editar esta cursada', 'Acción no autorizada');
			}
		}
		$this->load->model('espacio_curricular_model');
		$array_espacios = $this->get_array('espacio_curricular', 'materia', 'id', array(
			'select' => array('espacio_curricular.id', 'materia.descripcion materia'),
			'join' => array(
				array('division', 'division.curso_id=espacio_curricular.curso_id AND COALESCE(espacio_curricular.carrera_id,division.carrera_id)=division.carrera_id'),
				array('materia', "materia.id=espacio_curricular.materia_id AND materia.es_grupo='No'"),
				array('cursada', 'cursada.division_id=division.id AND cursada.espacio_curricular_id=espacio_curricular.id', 'left')
			),
			'where' => array(array('column' => 'division.id', 'value' => $division->id), "cursada.id IS NULL")
		));
		$this->cursada_model->fields['espacio_curricular']['array'] = $array_espacios;
		if ($escuela->nivel_id === '7') {
			$array_cuatrimestre = $this->get_array('espacio_curricular', 'cuatrimestre', 'id', array(
				'select' => array('espacio_curricular.id', 'espacio_curricular.cuatrimestre'),
				'join' => array(
					array('division', 'division.curso_id=espacio_curricular.curso_id AND division.carrera_id=espacio_curricular.carrera_id'),
					array('materia', 'materia.id=espacio_curricular.materia_id')
				),
				'where' => array("division.id=$division->id")
			));
			$this->cursada_model->fields['cuatrimestre']['array'] = $array_cuatrimestre;
			$this->array_cuatrimestre_control = $this->cursada_model->fields['cuatrimestre']['array'];
		} else {
			unset($this->cursada_model->fields['cuatrimestre']);
		}
		$this->array_espacio_curricular_control = $array_espacios;
		$this->array_alumnos_control = $this->cursada_model->fields['alumnos']['array'];
		unset($this->cursada_model->fields['fecha_desde']);
		unset($this->cursada_model->fields['fecha_hasta']);
		$this->set_model_validation_rules($this->cursada_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($division->id != ($this->input->post('division_id'))) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no permitida');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$carga_horaria = $this->input->post('carga_horaria');
				$espacio_curricular = $this->input->post('espacio_curricular');
				$grupos = $this->input->post('grupo');
				$cuatrimestre = ($escuela->nivel_id === '7') ? $this->input->post('cuatrimestre') : null;
				$alumnos = $this->input->post('alumnos');
				$trans_ok = TRUE;
				$consulta = $this->cursada_model->consulta($grupos, $espacio_curricular, $division->id);
				if (!empty($consulta)) {
					$errors = "Cursada ya cargada:";
					foreach ($consulta as $materia) {
						$errors .= '<br>' . $materia->materia;
					}
					$this->session->set_flashdata('error', $errors);
					redirect("cursada/listar/$division->id", 'refresh');
				}
				if ($carga_horaria <= 0) {//El gusti dijo que va asi, no lo cambies
					$errors = "Carga horaria invalida verifique los datos enviados.";
					$this->session->set_flashdata('error', $errors);
					redirect("cursada/listar/$division->id", 'refresh');
				}
				$trans_ok &= $this->cursada_model->create(array(
					'division_id' => $division->id,
					'espacio_curricular_id' => $espacio_curricular,
					'carga_horaria' => $carga_horaria,
					'cuatrimestre' => $cuatrimestre,
					'alumnos' => $alumnos,
					'grupo' => $grupos,
					'fecha_desde' => date("Y") . '-01-01')
				);
				if ($trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->cursada_model->get_msg());
					redirect("cursada/listar/$division_id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->cursada_model->get_error());
					redirect("cursada/listar/$division_id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("cursada/listar/$division_id", 'refresh');
			}
		}

		$this->cursada_model->fields['division']['value'] = "$division->curso $division->division";
		$data['fields'] = $this->build_fields($this->cursada_model->fields);
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = TITLE . ' - Agregar cursada';
		$this->load->view('cursada/cursada_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		if ($this->edicion === FALSE) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$cursada = $this->cursada_model->get_one($id);
		if (empty($cursada)) {
			return $this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			return $this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				return $this->modal_error('No tiene permisos para editar esta cursada', 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				return $this->modal_error('No tiene permisos para editar esta cursada', 'Acción no autorizada');
			}
		}
		$this->cursada_model->fields['carga_horaria']['min'] = empty($cursada->carga_horaria_cargos) ? 1 : $cursada->carga_horaria_cargos;

		if ($escuela->nivel_id === '7') {
			$this->array_cuatrimestre_control = $this->cursada_model->fields['cuatrimestre']['array'];
			$this->form_validation->set_rules("cuatrimestre", 'Cuatrimestre', 'callback_control_combo[cuatrimestre]');
		} else {
			unset($this->cursada_model->fields['cuatrimestre']);
		}
		$this->cursada_model->fields['espacio_curricular']['array'] = $cursada->espacio_curricular;
		$this->cursada_model->fields['espacio_curricular']['disabled'] = TRUE;
		$this->array_alumnos_control = $this->cursada_model->fields['alumnos']['array'];
		if ($cursada->evaluaciones === '0') {
			$this->form_validation->set_rules("alumnos", 'Alumnos', 'callback_control_combo[alumnos]');
		}
		$this->form_validation->set_rules("grupo", 'Grupo', 'trim');
		$this->form_validation->set_rules('carga_horaria', 'Carga horaria', 'integer');
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$url = $this->input->post('url_redireccion');
			if ($this->form_validation->run() === TRUE) {
				$grupo = $this->input->post('grupo');
				$espacio_curricular_id = $this->input->post('espacio_curricular_id');
				$carga_horaria = $this->input->post('carga_horaria');
				if ($cursada->grupo != $grupo) {
					$consulta = $this->cursada_model->consulta($grupo, $espacio_curricular_id, $division->id);
					if (!empty($consulta)) {
						$errors = "Esta cursada ya fue cargada";
						$this->session->set_flashdata('error', $errors);
						redirect("cursada/listar/$division->id", 'refresh');
					}
				}
				if ($carga_horaria <= 0) {
					$errors = "Carga horaria invalida verifique los datos enviados.";
					$this->session->set_flashdata('error', $errors);
					redirect("cursada/listar/$division->id", 'refresh');
				}
				$trans_ok = TRUE;
				$trans_ok &= $this->cursada_model->update(array(
					'id' => $this->input->post('id'),
					'carga_horaria' => $carga_horaria,
					'cuatrimestre' => $this->input->post('cuatrimestre'),
					'alumnos' => ($cursada->evaluaciones > 0) ? $cursada->alumnos : $this->input->post('alumnos'),
					'grupo' => $grupo
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->cursada_model->get_msg());
					if ($url == FALSE) {
						redirect("cursada/listar/$division->id", 'refresh');
					} else {
						redirect($url, 'refresh');
					}
				} else {
					$this->session->set_flashdata('error', $this->cursada_model->get_error());
					if ($url == FALSE) {
						redirect("cursada/listar/$division->id", 'refresh');
					} else {
						redirect($url, 'refresh');
					}
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				if ($url == FALSE) {
					redirect("cursada/listar/$division->id", 'refresh');
				} else {
					redirect($url, 'refresh');
				}
			}
		}

		if (!empty($_GET['redirect_url'])) {
			$data['url_redireccion'] = urldecode($_GET['redirect_url']);
		} else {
			$data['url_redireccion'] = FALSE;
		}

		if ($cursada->evaluaciones > 0) {
			$this->cursada_model->fields['alumnos']['disabled'] = TRUE;
		}
		$data['fields'] = $this->build_fields($this->cursada_model->fields, $cursada);
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['cursada'] = $cursada;
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar cursada';
		$this->load->view('cursada/cursada_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		if ($this->edicion === FALSE) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$cursada = $this->cursada_model->get_one($id);
		if (empty($cursada)) {
			return $this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			return $this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				return $this->modal_error('No tiene permisos para eliminar esta cursada', 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				return $this->modal_error('No tiene permisos para eliminar esta cursada', 'Acción no autorizada');
			}
		}

		$cargos_cursada = $this->cargo_cursada_model->get(array(
			'select' => array('cargo_cursada.id'),
			'join' => array(
				array('cursada', "cargo_cursada.cursada_id = cursada.id AND cargo_cursada.cursada_id = $cursada->id"),
			),
		));

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;
			if (!empty($cargos_cursada)) {
				foreach ($cargos_cursada as $cargo_cursada) {
					$trans_ok &= $this->cargo_cursada_model->delete(array('id' => $cargo_cursada->id), FALSE);
				}
			}
			$this->load->model('usuario_rol_model');
			$usuarios_rol = $this->usuario_rol_model->get_usuarios(array(ROL_DOCENTE_CURSADA), $cursada->id);
			foreach ($usuarios_rol as $usuario_rol) {
				$trans_ok &= $this->usuario_rol_model->delete(array(
					'id' => $usuario_rol->id
					), FALSE);
			}
			$trans_ok &= $this->cursada_model->delete(array('id' => $this->input->post('id')), FALSE);
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->cursada_model->get_msg());
				redirect("cursada/listar/$division->id", 'refresh');
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al intentar eliminar.';
				if ($this->cursada_model->get_error()) {
					$errors .= '<br>' . $this->cursada_model->get_error();
				}
				if ($this->usuario_rol_model->get_error()) {
					$errors .= '<br>' . $this->usuario_rol_model->get_error();
				}
				if ($this->cargo_cursada_model->get_error()) {
					$errors .= '<br>' . $this->cargo_cursada_model->get_error();
				}
				$this->session->set_flashdata('error', $errors);
				redirect("cursada/listar/$division->id", 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->cursada_model->get_error() ? $this->cursada_model->get_error() : $this->session->flashdata('error')));

		$cursada->cuatrimestre = $this->cursada_model->fields['cuatrimestre']['array'][$cursada->cuatrimestre];
		if (!empty($_GET['redirect_url'])) {
			$data['url_redireccion'] = urldecode($_GET['redirect_url']);
		} else {
			$data['url_redireccion'] = FALSE;
		}
		$data['fields'] = $this->build_fields($this->cursada_model->fields, $cursada, TRUE);
		$data['cursada'] = $cursada;
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = TITLE . ' - Eliminar cursada';
		$this->load->view('cursada/cursada_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$cursada = $this->cursada_model->get_one($id);
		if (empty($cursada)) {
			return $this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			return $this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				return $this->modal_error('No tiene permisos para ver esta cursada', 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				return $this->modal_error('No tiene permisos para ver esta cursada', 'Acción no autorizada');
			}
		}

		$cursada->cuatrimestre = $this->cursada_model->fields['cuatrimestre']['array'][$cursada->cuatrimestre];
		if (!empty($_GET['redirect_url'])) {
			$data['url_redireccion'] = urldecode($_GET['redirect_url']);
		} else {
			$data['url_redireccion'] = FALSE;
		}
		$data['cargos_cursada'] = $this->cargo_cursada_model->get_cargos_cursada($cursada->id);
		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->cursada_model->fields, $cursada, TRUE);
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['cursada'] = $cursada;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver cursada';
		$this->load->view('cursada/cursada_modal_abm', $data);
	}

	public function modal_grupos($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		if ($this->edicion === FALSE) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$cursada = $this->cursada_model->get_one($id);
		if (empty($cursada)) {
			return $this->modal_error('No se encontró el registro a dividir', 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			return $this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				return $this->modal_error('No tiene permisos para editar esta cursada', 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				return $this->modal_error('No tiene permisos para editar esta cursada', 'Acción no autorizada');
			}
		}
		$this->cursada_model->fields['espacio_curricular']['array'] = $cursada->espacio_curricular;
		$this->cursada_model->fields['espacio_curricular']['disabled'] = TRUE;
		$this->form_validation->set_rules('grupo_1', 'Grupo 1', 'required|max_length[15]');
		$this->form_validation->set_rules('grupo_2', 'Grupo 2', 'required|max_length[15]');
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$grupo_1 = $this->input->post('grupo_1');
				$grupo_2 = $this->input->post('grupo_2');
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->cursada_model->update(array(
					'id' => $this->input->post('id'),
					'alumnos' => 'Grupo',
					'grupo' => $grupo_1
					), FALSE);
				$trans_ok &= $this->cursada_model->create(array(
					'division_id' => $cursada->division_id,
					'espacio_curricular_id' => $cursada->espacio_curricular_id,
					'carga_horaria' => $cursada->carga_horaria,
					'alumnos' => 'Grupo',
					'grupo' => $grupo_2,
					'cuatrimestre' => $cursada->cuatrimestre,
					'fecha_desde' => $cursada->fecha_desde,
					'fecha_hasta' => $cursada->fecha_hasta
					), FALSE);
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->cursada_model->get_msg());
					redirect("cursada/listar/$division->id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->cursada_model->get_error());
					redirect("cursada/listar/$division->id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("cursada/listar/$division->id", 'refresh');
			}
		}
		$cursada->grupo_1 = $cursada->grupo;
		$cursada->grupo_2 = '';
		$this->cursada_model->fields['grupo_1'] = $this->cursada_model->fields['grupo'];
		$this->cursada_model->fields['grupo_1']['label'] = 'Grupo 1';
		$this->cursada_model->fields['grupo_2'] = $this->cursada_model->fields['grupo'];
		$this->cursada_model->fields['grupo_2']['label'] = 'Grupo 2';
		unset($this->cursada_model->fields['grupo']);
		$data['fields'] = $this->build_fields($this->cursada_model->fields, $cursada);
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['cursada'] = $cursada;
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar cursada';
		$this->load->view('cursada/cursada_modal_grupos', $data);
	}

	public function modal_agregar_cargo($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		if ($this->edicion === FALSE) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$cursada = $this->cursada_model->get_one($id);
		if (empty($cursada)) {
			return $this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			return $this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				return $this->modal_error('No tiene permisos para editar esta cursada', 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				return $this->modal_error('No tiene permisos para ver esta cursada', 'Acción no autorizada');
			}
		}

		unset($this->cargo_cursada_model->fields['espacio_curricular']);
		unset($this->cargo_cursada_model->fields['persona']);
		$this->set_model_validation_rules($this->cargo_cursada_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($cursada->id !== $this->input->post('cursada_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$url = $this->input->post('url_redireccion');
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$cargo = $this->input->post('cargo_id');
				$trans_ok = TRUE;
				$trans_ok &= $this->cargo_cursada_model->create(array(
					'cargo_id' => $cargo,
					'carga_horaria' => $cursada->carga_horaria - ($cursada->carga_horaria_cargos),
					'cursada_id' => $cursada->id)
				);
				if ($trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->cargo_cursada_model->get_msg());
					if ($url == FALSE) {
						redirect("cursada/listar/$division->id", 'refresh');
					} else {
						redirect($url, 'refresh');
					}
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->cargo_cursada_model->get_error());
					if ($url == FALSE) {
						redirect("cursada/listar/$division->id", 'refresh');
					} else {
						redirect($url, 'refresh');
					}
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				if ($url == FALSE) {
					redirect("cursada/listar/$division->id", 'refresh');
				} else {
					redirect($url, 'refresh');
				}
			}
		}

		if (!empty($_GET['redirect_url'])) {
			$data['url_redireccion'] = urldecode($_GET['redirect_url']);
		} else {
			$data['url_redireccion'] = FALSE;
		}
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['cursada'] = $cursada;
		$data['cargos'] = $this->cargo_cursada_model->buscar_cargos_escuela($escuela->id, $cursada->id);
		$data['title'] = TITLE . ' - Agregar cargos a cursada';
		$this->load->view('cursada/cursada_modal_abm_cargos', $data);
	}

	public function modal_editar_cargo($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		if ($this->edicion === FALSE) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$cargo_cursada = $this->cargo_cursada_model->get_one($id);
		if (empty($cargo_cursada)) {
			return $this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
		}
		$cursada = $this->cursada_model->get_one($cargo_cursada->cursada_id);
		if (empty($cursada)) {
			return $this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			return $this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				return $this->modal_error('No tiene permisos para editar este cargo', 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				return $this->modal_error('No tiene permisos para ver esta cursada', 'Acción no autorizada');
			}
		}

		$this->cargo_cursada_model->fields['carga_horaria']['max'] = $cursada->carga_horaria - ($cursada->carga_horaria_cargos - $cargo_cursada->carga_horaria);
		$this->cargo_cursada_model->fields['carga_horaria']['min'] = 1;

		$this->set_model_validation_rules($this->cargo_cursada_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($cargo_cursada->id !== $this->input->post('cargo_cursada_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$url = $this->input->post('url_redireccion');
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$carga_horaria = $this->input->post('carga_horaria');
				$trans_ok = TRUE;
				$trans_ok &= $this->cargo_cursada_model->update(array(
					'id' => $cargo_cursada->id,
					'carga_horaria' => $carga_horaria
					), FALSE);
				if ($trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->cargo_cursada_model->get_msg());
					if ($url == FALSE) {
						redirect("cursada/listar/$division->id", 'refresh');
					} else {
						redirect($url, 'refresh');
					}
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->cargo_cursada_model->get_error());
					if ($url == FALSE) {
						redirect("cursada/listar/$division->id", 'refresh');
					} else {
						redirect($url, 'refresh');
					}
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				if ($url == FALSE) {
					redirect("cursada/listar/$division->id", 'refresh');
				} else {
					redirect($url, 'refresh');
				}
			}
		}

		if (!empty($_GET['redirect_url'])) {
			$data['url_redireccion'] = urldecode($_GET['redirect_url']);
		} else {
			$data['url_redireccion'] = FALSE;
		}
		$data['cargo_cursada'] = $cargo_cursada;
		$data['fields'] = $this->build_fields($this->cargo_cursada_model->fields, $cargo_cursada, FALSE);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['cursada'] = $cursada;
		$data['txt_btn'] = 'Editar';
		$data['title'] = TITLE . ' - Editar cargo de ' . $cursada->espacio_curricular;
		$this->load->view('cursada/cursada_modal_editar_cargo', $data);
	}

	public function modal_eliminar_cargo($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		if ($this->edicion === FALSE) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$cargo_cursada = $this->cargo_cursada_model->get_one($id);
		if (empty($cargo_cursada)) {
			return $this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
		}
		$cursada = $this->cursada_model->get_one($cargo_cursada->cursada_id);
		if (empty($cursada)) {
			return $this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			return $this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_DOCENTE_CURSADA) {
			if (!$this->usuarios_model->verificar_permiso('cursada', $this->rol, $cursada)) {
				return $this->modal_error('No tiene permisos para eliminar este cargo', 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo === ROL_DOCENTE) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				return $this->modal_error('No tiene permisos para ver esta cursada', 'Acción no autorizada');
			}
		}

		$this->set_model_validation_rules($this->cargo_cursada_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($cargo_cursada->id !== $this->input->post('cargo_cursada_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$url = $this->input->post('url_redireccion');
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$cargo_cursada_id = $this->input->post('cargo_cursada_id');
				$trans_ok = TRUE;
				$trans_ok &= $this->cargo_cursada_model->delete(array('id' => $cargo_cursada_id), FALSE);
				if ($trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->cargo_cursada_model->get_msg());
					if ($url == FALSE) {
						redirect("cursada/listar/$division->id", 'refresh');
					} else {
						redirect($url, 'refresh');
					}
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->cargo_cursada_model->get_error());
					if ($url == FALSE) {
						redirect("cursada/listar/$division->id", 'refresh');
					} else {
						redirect($url, 'refresh');
					}
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				if ($url == FALSE) {
					redirect("cursada/listar/$division->id", 'refresh');
				} else {
					redirect($url, 'refresh');
				}
			}
		}

		if (!empty($_GET['redirect_url'])) {
			$data['url_redireccion'] = urldecode($_GET['redirect_url']);
		} else {
			$data['url_redireccion'] = FALSE;
		}
		$data['cargo_cursada'] = $cargo_cursada;
		$data['fields'] = $this->build_fields($this->cargo_cursada_model->fields, $cargo_cursada, TRUE);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['cursada'] = $cursada;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = TITLE . ' - Eliminar cargo de' . $cursada->espacio_curricular;
		$this->load->view('cursada/cursada_modal_editar_cargo', $data);
	}

	public function administrar_rol_cursada($cursada_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_administrar) || $cursada_id == NULL || !ctype_digit($cursada_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$cursada = $this->cursada_model->get_one($cursada_id);
		if (empty($cursada)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($cursada->division_id);
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
		$tableData = array(
			'columns' => array(
				array('label' => 'Usuario', 'data' => 'usuario', 'width' => 13),
				array('label' => 'CUIL', 'data' => 'cuil', 'width' => 10),
				array('label' => 'Persona', 'data' => 'persona', 'width' => 21),
				array('label' => 'Rol', 'data' => 'rol', 'width' => 17, 'searchable' => 'false'),
				array('label' => 'Entidad', 'data' => 'entidad', 'width' => 20),
				array('label' => 'Activo', 'data' => 'active', 'class' => 'dt-body-right', 'width' => 7),
				array('label' => 'Asignado', 'data' => 'rol_asignado', 'width' => 10)
			),
			'order' => array(),
			'table_id' => 'usuario_table',
			'source_url' => "cursada/listar_usuarios_data/$cursada_id",
			'responsive' => FALSE,
			'reuse_var' => TRUE,
			'initComplete' => 'complete_usuario_table',
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['division'] = $division;
		$data['escuela'] = $escuela;
		$data['cursada'] = $cursada;
		$data['title'] = "Administrar rol Cursada";
		$this->load_template('cursada/cursada_administrar_rol', $data);
	}

	public function listar_usuarios_data($cursada_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_administrar) || $cursada_id == NULL || !ctype_digit($cursada_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select('usuario_rol.id as usuario_rol_id,usuario.id, usuario.usuario, persona.cuil as cuil, CONCAT(persona.apellido, \', \', persona.nombre) as persona, rol.nombre as rol, entidad.nombre as entidad, (CASE usuario.active WHEN 1 THEN "Activo" ELSE "No Activo" END) as active, (CASE WHEN rol_cursada.entidad_id IS NOT NULL THEN "Si" ELSE "No" END) as rol_asignado,group_concat(distinct rol_cursada.entidad_id) as division_asignada_id')
			->unset_column('id')
			->from('usuario')
			->join('usuario_persona', 'usuario_persona.usuario_id=usuario.id')
			->join('usuario_rol', 'usuario_rol.usuario_id=usuario.id', 'left')
			->join('usuario_rol rol_cursada', 'rol_cursada.usuario_id=usuario.id AND rol_cursada.rol_id=27', 'left')
			->join('rol', 'usuario_rol.rol_id=rol.id', 'left')
			->join('entidad_tipo', 'rol.entidad_tipo_id=entidad_tipo.id', 'left')
			->join('entidad', 'entidad.tabla=entidad_tipo.tabla and entidad.id=usuario_rol.entidad_id', 'left')
			->join('persona', "persona.cuil = usuario_persona.cuil", 'left')
			->where('usuario.active', 1)
			->where('rol.codigo', ROL_DOCENTE_CURSADA)
			->where('usuario_rol.entidad_id', $cursada_id)
			->group_by('usuario.id')
			->add_column('rol_asignado', '$1', 'dt_rol_cursada_asignado(usuario_rol_id)');
		echo $this->datatables->generate();
	}

	public function modal_buscar_usuarios($cursada_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_administrar) || $cursada_id == NULL || !ctype_digit($cursada_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$cursada = $this->cursada_model->get_one($cursada_id);
		if (empty($cursada)) {
			return $this->modal_error('No se encontró el registro de la cursada', 'Registro no encontrado');
		}

		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division)) {
			return $this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		if (isset($_POST) && !empty($_POST)) {
			$usuario_id = $this->input->post('usuario');
			$rol_cursada = $this->cursada_model->verificar_usuario_rol_cursada($usuario_id, $cursada->id);
			$this->load->model('usuario_model');
			$usuario = $this->usuario_model->get(array('id' => $usuario_id));
			if (!empty($usuario)) {
				$roles = $this->usuarios_model->get_roles($usuario_id);
				if (empty($rol_cursada)) {
					echo json_encode(array('status' => 'success', 'roles' => $roles, 'rol_cursada' => '0'));
				} else {
					echo json_encode(array('status' => 'success', 'roles' => $roles, 'rol_cursada' => '1'));
				}
			} else {
				echo json_encode(array('status' => 'success', 'roles' => '-1', 'rol_cursada' => '0'));
			}
			return TRUE;
		}
		$data['division'] = $division;
		$data['cursada'] = $cursada;
		$data['title'] = "Asignar rol de cursada";
		$data['txt_btn'] = "Asignar";
		$this->load->view('cursada/cursada_modal_buscar_usuarios', $data);
	}

	public function asignar_rol_cursada($usuario_id = NULL, $cursada_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_administrar) || $usuario_id == NULL || !ctype_digit($usuario_id) || $cursada_id == NULL || !ctype_digit($cursada_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('usuario_model');
		$usuario = $this->usuario_model->get_one($usuario_id);
		if (empty($usuario)) {
			show_error('No se encontró el registro del usuario', 500, 'Registro no encontrado');
		}
		$cursada = $this->cursada_model->get_one($cursada_id);
		if (empty($cursada)) {
			show_error('No se encontró el registro de la cursada', 500, 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->load->model('usuario_rol_model');
		$this->db->trans_begin();
		$trans_ok = TRUE;
		$rol_id = 27;
		$entidad_id = $cursada->id;
		$trans_ok &= $this->usuario_rol_model->create(array(
			'usuario_id' => $usuario_id,
			'rol_id' => $rol_id,
			'entidad_id' => $entidad_id,
			'activo' => 1)
			, FALSE);
		if ($this->db->trans_status() && $trans_ok) {
			$this->db->trans_commit();
			$this->session->set_flashdata('message', "Rol asignado correctamente");
		} else {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', $this->usuario_rol_model->get_error());
		}
		redirect("cursada/administrar_rol_cursada/$cursada->id", 'refresh');
	}

	public function modal_eliminar_rol_cursada($usuario_rol_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_administrar) || $usuario_rol_id == NULL || !ctype_digit($usuario_rol_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$this->load->model('usuario_rol_model');
		$usuario_rol = $this->usuario_rol_model->get_one($usuario_rol_id);
		if (empty($usuario_rol)) {
			return $this->modal_error('No se encontró el registro buscado', 'Registro no encontrado');
		}
		$this->load->model('usuario_model');
		$usuario = $this->usuario_model->get_one($usuario_rol->usuario_id);
		if (empty($usuario)) {
			return $this->modal_error('No se encontró el registro del usuario', 'Registro no encontrado');
		}
		$cursada = $this->cursada_model->get_one($usuario_rol->entidad_id);
		if (empty($cursada)) {
			return $this->modal_error('No se encontró el registro de la cursada', 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division)) {
			return $this->modal_error('No se encontró el registro de la division', 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($usuario_rol_id !== $this->input->post('usuario_rol_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->usuario_rol_model->delete(array('id' => $this->input->post('usuario_rol_id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->usuario_rol_model->get_msg());
			} else {
				$this->session->set_flashdata('error', $this->usuario_rol_model->get_error());
			}
			redirect("cursada/administrar_rol_cursada/$cursada->id", 'refresh');
		}

		$data['division'] = $division;
		$data['cursada'] = $cursada;
		$data['usuario'] = $usuario;
		$data['escuela'] = $escuela;
		$data['usuario_rol'] = $usuario_rol;
		$data['fields_division'] = $this->build_fields($this->division_model->fields, $division, TRUE);
		$data['fields_cursada'] = $this->build_fields($this->cursada_model->fields, $cursada, TRUE);
		$data['fields'] = $this->build_fields($this->usuario_model->fields, $usuario, TRUE);
		$data['title'] = "Eliminar rol de cursada al usuario";
		$data['txt_btn'] = "Quitar rol";
		$this->load->view('cursada/cursada_modal_eliminar_usuario_rol', $data);
	}

	public function evaluaciones($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$cursada = $this->cursada_model->get_one($id);
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
		$this->load->model('evaluacion_model');
		$evaluaciones_periodo = $this->evaluacion_model->get_evaluaciones_notas_cursada($cursada->id);
		if (empty($evaluaciones_periodo)) {
			$this->session->set_flashdata('error', 'No hay registros de notas cargados.');
			redirect('cursada/escritorio/' . $cursada->id, 'refresh');
		}
		$alumnos = array();
		foreach ($evaluaciones_periodo as $evaluaciones) {
			foreach ($evaluaciones as $evaluacion) {
				foreach ($evaluacion->notas as $alumno) {
					if (!isset($alumnos[$alumno->alumno_cursada_id])) {
						$alumnos[$alumno->alumno_cursada_id] = (object) array('id' => $alumno->alumno_cursada_id, 'documento_tipo' => $alumno->documento_tipo, 'documento' => $alumno->documento, 'persona' => $alumno->persona);
					}
				}
			}
		}
		usort($alumnos, array($this, 'usort_alumnos'));
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['cursada'] = $cursada;
		$data['alumnos'] = $alumnos;
		$data['evaluaciones_periodo'] = $evaluaciones_periodo;
		$data['title'] = 'Evaluaciones cursada';
		$this->load_template('cursada/cursada_evaluaciones', $data);
	}

	public function excel_resumen_evaluaciones($cursada_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $cursada_id == NULL || !ctype_digit($cursada_id)) {
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
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		$notas = $this->db->select("CONCAT(COALESCE(documento_tipo.descripcion_corta,''),' ',persona.documento),CONCAT_WS(', ', persona.apellido, persona.nombre) persona,CONCAT(calendario_periodo.periodo,'° ',calendario.nombre_periodo),evaluacion_tipo.descripcion as evaluacion_tipo, evaluacion.tema, DATE_FORMAT(evaluacion.fecha,'%d/%m/%Y'), cursada_nota.nota, cursada_nota.asistencia")
				->from('evaluacion')
				->join('evaluacion_tipo', 'evaluacion_tipo.id = evaluacion.evaluacion_tipo_id')
				->join('cursada', 'cursada.id = evaluacion.cursada_id')
				->join('cursada_nota', 'cursada_nota.evaluacion_id = evaluacion.id')
				->join('alumno_cursada', 'alumno_cursada.id = cursada_nota.alumno_cursada_id', 'left')
				->join('alumno_division', 'alumno_division.id = alumno_cursada.alumno_division_id', 'left')
				->join('alumno', 'alumno.id = alumno_division.alumno_id', 'left')
				->join('persona', 'persona.id = alumno.persona_id', 'left')
				->join('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id', 'left')
				->join('calendario_periodo', 'calendario_periodo.id= evaluacion.calendario_periodo_id', 'left')
				->join('calendario', 'calendario.id = calendario_periodo.calendario_id', 'left')
				->where('evaluacion.cursada_id', $cursada_id)
				->order_by('evaluacion.fecha asc, evaluacion.tema asc')
				->get()->result_array();

		if (empty($notas)) {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect('cursada/evaluaciones/' . $cursada->id, 'refresh');
		} else {
			$this->load->library('PHPExcel');
			$reader = PHPExcel_IOFactory::createReader('Excel2007');
			$workbook = $reader->load("templates/Reporte de evaluaciones.xlsx");
			$sheet = $workbook->getSheet(0);
			$sheet->setTitle("Reporte de evaluaciones");
			$sheet->fromArray($notas, NULL, 'A3');
			$nombreArchivo = "Evaluaciones {$escuela->nombre_corto} {$division->curso}-{$division->division}";
			header("Content-Disposition: attachment; filename=\"$nombreArchivo.xlsx\"");
			header("Cache-Control: max-age=0");

			$writer = PHPExcel_IOFactory::createWriter($workbook, "Excel2007");
			$writer->save('php://output');
			exit;
			$this->load->library('pdf');
			$pdf = $this->pdf->load('', 'A4', 0, '', 8, 8, 65, 15, 7, 7, 'P');
			$pdf->SetTitle();
		}
	}

	public function pdf_resumen_evaluaciones($cursada_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $cursada_id == NULL || !ctype_digit($cursada_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}
		$cursada = $this->cursada_model->get_one($cursada_id);
		if (empty($cursada)) {
			show_error('No se encontró el registro de la cursada', 500, 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($cursada->division_id);
		if (empty($division) || !empty($division->fecha_baja)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$notas = $this->db->select("evaluacion.id evaluacion_id, evaluacion_tipo.descripcion as evaluacion_tipo, evaluacion.tema, evaluacion.fecha, cursada_nota.alumno_cursada_id, cursada_nota.nota, cursada_nota.asistencia, documento_tipo.descripcion_corta documento_tipo, persona.documento, CONCAT_WS(', ', persona.apellido, persona.nombre) persona, CONCAT(calendario_periodo.periodo,'° ',calendario.nombre_periodo) as calendario_periodo, evaluacion.calendario_periodo_id as calendario_periodo_id")
				->from('evaluacion')
				->join('evaluacion_tipo', 'evaluacion_tipo.id = evaluacion.evaluacion_tipo_id')
				->join('cursada', 'cursada.id = evaluacion.cursada_id')
				->join('cursada_nota', 'cursada_nota.evaluacion_id = evaluacion.id')
				->join('alumno_cursada', 'alumno_cursada.id = cursada_nota.alumno_cursada_id', 'left')
				->join('alumno_division', 'alumno_division.id = alumno_cursada.alumno_division_id', 'left')
				->join('alumno', 'alumno.id = alumno_division.alumno_id', 'left')
				->join('persona', 'persona.id = alumno.persona_id', 'left')
				->join('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id', 'left')
				->join('calendario_periodo', 'calendario_periodo.id= evaluacion.calendario_periodo_id', 'left')
				->join('calendario', 'calendario.id = calendario_periodo.calendario_id', 'left')
				->where('evaluacion.cursada_id', $cursada_id)
				->order_by('evaluacion.fecha asc, evaluacion.tema asc')
				->get()->result();
		if (empty($notas)) {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect('cursada/evaluaciones/' . $cursada->id, 'refresh');
		}
		$evaluaciones_periodo = array();
		foreach ($notas as $nota) {
			if (!isset($evaluaciones_periodo[$nota->calendario_periodo][$nota->evaluacion_id])) {
				$evaluaciones_periodo[$nota->calendario_periodo][$nota->evaluacion_id] = (object) array('evaluacion_id' => $nota->evaluacion_id, 'evaluacion_tipo' => $nota->evaluacion_tipo, 'tema' => $nota->tema, 'fecha' => $nota->fecha, 'periodo' => $nota->calendario_periodo);
				$evaluaciones_periodo[$nota->calendario_periodo][$nota->evaluacion_id]->notas = array();
			}
			$evaluaciones_periodo[$nota->calendario_periodo][$nota->evaluacion_id]->notas[$nota->alumno_cursada_id] = $nota;
		}
		$alumnos = array();
		foreach ($evaluaciones_periodo as $evaluaciones) {
			foreach ($evaluaciones as $evaluacion) {
				foreach ($evaluacion->notas as $alumno) {
					if (!isset($alumnos[$alumno->alumno_cursada_id])) {
						$alumnos[$alumno->alumno_cursada_id] = (object) array('id' => $alumno->alumno_cursada_id, 'documento_tipo' => $alumno->documento_tipo, 'documento' => $alumno->documento, 'persona' => $alumno->persona);
					}
				}
			}
		}
		$data['notas'] = $notas;
		$data['escuela'] = $escuela;
		$data['cursada'] = $cursada;
		$data['alumnos'] = $alumnos;
		$data['evaluaciones_periodo'] = $evaluaciones_periodo;
		$fecha_actual = date('d/m/Y');
		$content = $this->load->view('cursada/cursada_evaluaciones_pdf', $data, TRUE);
		$this->load->helper('mpdf');
		$watermark = '';
		exportarMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Planilla de Notas', 'Esc. "' . trim($escuela->nombre) . '" Nº ' . $escuela->numero . ' " - Fecha actual: ' . $fecha_actual, '|{PAGENO} de {nb}|', 'LEGAL-L', $watermark, 'I', FALSE, FALSE);
	}

	private function usort_alumnos($a, $b) {
		return strcmp($a->persona, $b->persona);
	}
}
/* End of file Cursada.php */
/* Location: ./application/controllers/Cursada.php */