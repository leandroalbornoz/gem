<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Division_inasistencia extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->load->model('division_model');
		$this->load->model('division_inasistencia_model');
		$this->load->model('division_inasistencia_dia_model');
		$this->load->model('calendario_model');
		$this->load->model('alumno_inasistencia_model');
		$this->load->model('alumno_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_DOCENTE_CURSADA));
		$this->roles_admin = array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_SUPERVISION, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_ESCUELA_ALUM, ROL_DOCENTE, ROL_ASISTENCIA_DIVISION);
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_ESCUELA_CAR, ROL_GRUPO_ESCUELA_CONSULTA))) {
			$this->edicion = FALSE;
		}
		$this->admin_rol_asistencia = array(ROL_ADMIN, ROL_USI, ROL_DIR_ESCUELA);
		$this->nav_route = 'menu/division';
	}

	public function listar($division_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("division_inasistencia/listar/$division_id/$ciclo_lectivo", 'refresh');
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

		if (in_array($this->rol->codigo, array(ROL_ASISTENCIA_DIVISION))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_model');

		$division->escuela = "Esc. $escuela->nombre_largo";
		$alumnos = $this->alumno_model->get_alumnos_division($division->id);
		$data['estadisticas_total'] = $this->division_inasistencia_model->get_estadisticas_inasistencias($division_id, $ciclo_lectivo);
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['inasistencias'] = $this->division_inasistencia_model->get_registros($division->id, $ciclo_lectivo);
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['alumnos'] = $alumnos;
		$data['usuarios'] = $this->usuarios_model->get_usuarios(ROL_ASISTENCIA_DIVISION, $division->id);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - División';
		$periodos = $this->calendario_model->get_periodos($division->calendario_id, $ciclo_lectivo);
		if (empty($periodos)) {
			if ($this->rol->codigo === ROL_ESCUELA_ALUM) {
				$this->session->set_flashdata('error', "Para trabajar con asistencias la división debe tener un calendario seleccionado");
				redirect("division/ver/$division->id");
			} else {
				$this->session->set_flashdata('error', "Para trabajar con asistencias primero debe seleccionar un tipo de calendario");
				redirect("division/editar/$division->id");
			}
		}
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['grafica'] = $this->division_inasistencia_model->grafica($periodos, $division_id, $ciclo_lectivo);
		$data['periodos'] = $periodos;
		$data['estadisticas'] = $this->load->view('division_inasistencia/escritorio_division_modulo_inasistencia', $data, TRUE);
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$data['css'][] = 'plugins/c3/c3.min.css';
		$data['js'][] = 'plugins/d3/d3.min.js';
		$data['js'][] = 'plugins/c3/c3.min.js';
		$this->load_template('division_inasistencia/division_inasistencia_listar', $data);
	}

	public function agregar($division_id = NULL, $ciclo_lectivo = NULL, $periodo = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("division_inasistencia/agregar/$division_id/$ciclo_lectivo", 'refresh');
		}
		if (empty($periodo) || !ctype_digit($periodo)) {
			redirect("division_inasistencia/agregar/$division_id/$ciclo_lectivo/1", 'refresh');
		}
		if (empty($mes) || empty(DateTime::createFromFormat('Ym', $mes))) {
			$mes = date('Ym');
			redirect("division_inasistencia/agregar/$division_id/$ciclo_lectivo/$periodo/$mes", 'refresh');
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
		$division_inasistencia = $this->division_inasistencia_model->get_registro($division->id, $ciclo_lectivo, $periodo, $mes);
		if (empty($division_inasistencia)) {
			$this->division_inasistencia_model->create(array(
				'division_id' => $division_id,
				'periodo' => $periodo,
				'ciclo_lectivo' => $ciclo_lectivo,
				'mes' => $mes,
			));
			$division_inasistencia_id = $this->division_inasistencia_model->get_row_id();
		} else {
			$division_inasistencia_id = $division_inasistencia->id;
		}
		redirect("division_inasistencia/ver/$division_inasistencia_id", 'refresh');
		$division->escuela = "Esc. $escuela->nombre_largo";

		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['mes'] = $mes;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$this->load->model('alumno_model');
		$alumnos = $this->alumno_model->get_alumnos_division($division->id);
		$data['alumnos'] = $alumnos;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - División';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('division/division_alumnos_inasistencias', $data);
	}

	public function ver($id = NULL, $orden = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$division_inasistencia = $this->division_inasistencia_model->get_one($id);
		if (empty($division_inasistencia)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($division_inasistencia->division_id);
		if (empty($division)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		$alumnos_resumen_mensual = $this->alumno_inasistencia_model->get_alumnos_resumen($division_inasistencia->id, $orden);
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ASISTENCIA_DIVISION) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				show_error('No tiene permisos para acceder a la asistencia de la división', 500, 'Acción no autorizada');
			}
		}
		$division->escuela = "Esc. $escuela->nombre_largo";

		$this->load->model('alumno_model');

		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['periodos'] = $this->calendario_model->get_periodos($division->calendario_id, $division_inasistencia->ciclo_lectivo);
		$inasistencias_db = $this->alumno_inasistencia_model->get_alumnos_resumen_diario($division_inasistencia->id, $orden);
		$inasistencias_acumulada_db = $this->alumno_inasistencia_model->get_alumnos_acumulada($division->id, $division_inasistencia->mes, $division_inasistencia->periodo);
		$inasistencias_db_varones = array();
		$inasistencias_db_mujeres = array();
		foreach ($inasistencias_db as $inasistencia) {
			if ($inasistencia->sexo_id === '1') {
				$inasistencias_db_varones[] = $inasistencia;
			} else {
				$inasistencias_db_mujeres[] = $inasistencia;
			}
		}

		$inasistencias_resumen = array();
		$inasistencias_resumen_sexo = array();
		$inasistencias_acumulada_resumen = array();
		$inasistencias_mes = array();

		if (!empty($inasistencias_acumulada_db)) {
			foreach ($inasistencias_acumulada_db as $inasistencia_acumulada_db) {
				$inasistencias_acumulada_resumen[$inasistencia_acumulada_db->alumno_division_id][$inasistencia_acumulada_db->justificada] = $inasistencia_acumulada_db->falta;
			}
		}

		if (!empty($inasistencias_db)) {
			foreach ($inasistencias_db as $inasistencia_db) {
				if (!isset($inasistencias_resumen[$inasistencia_db->alumno_division_id][$inasistencia_db->justificada])) {
					$inasistencias_resumen[$inasistencia_db->alumno_division_id][$inasistencia_db->justificada] = 0;
				}
				$inasistencias_resumen[$inasistencia_db->alumno_division_id][$inasistencia_db->justificada] += $inasistencia_db->falta;
				if (!isset($inasistencias_resumen_sexo[$inasistencia_db->sexo_id][$inasistencia_db->justificada])) {
					$inasistencias_resumen_sexo[$inasistencia_db->sexo_id][$inasistencia_db->justificada] = 0;
				}
				$inasistencias_resumen_sexo[$inasistencia_db->sexo_id][$inasistencia_db->justificada] += $inasistencia_db->falta;
				if ($inasistencia_db->inasistencia_tipo_id === "2") {
					if ($inasistencia_db->justificada === "Si") {
						$inasistencia_db->falta = "<span class='label bg-green'>A</span>";
					} else {
						$inasistencia_db->falta = "<span class='label bg-red'>A</span>";
					}
				} elseif ($inasistencia_db->inasistencia_tipo_id === "3") {
					if ($inasistencia_db->justificada === "Si") {
						$inasistencia_db->falta = "<span class='label bg-green'>T</span>";
					} else {
						$inasistencia_db->falta = "<span class='label bg-red'>T</span>";
					}
				} elseif ($inasistencia_db->inasistencia_tipo_id === "4") {
					if ($inasistencia_db->justificada === "Si") {
						$inasistencia_db->falta = "<span class='label bg-green'>R</span>";
					} else {
						$inasistencia_db->falta = "<span class='label bg-red'>R</span>";
					}
				} elseif ($inasistencia_db->inasistencia_tipo_id === "5") {
					$inasistencia_db->falta = "<span class='fa fa-fw fa-minus'></span>";
				} elseif ($inasistencia_db->inasistencia_tipo_id === "6") {
					$inasistencia_db->falta = "<span class='fa fa-fw fa-minus'></span>";
				} elseif ($inasistencia_db->inasistencia_tipo_id === "7") {
					$inasistencia_db->falta = "<span class='label bg-blue'>A</span>";
				} elseif ($inasistencia_db->inasistencia_tipo_id === "8") {
					$inasistencia_db->falta = "<span class='label bg-red'>P</span>";
				} elseif ($inasistencia_db->inasistencia_tipo_id === "9") {
					$inasistencia_db->falta = "<span class='label' style='border:1px solid gray;'>P</span>";
				}
				$inasistencias_mes[$inasistencia_db->alumno_division_id][$inasistencia_db->fecha][$inasistencia_db->contraturno] = $inasistencia_db;
			}
		}

		$data['inasistencias_mes'] = $inasistencias_mes;
		$data['inasistencias_resumen'] = $inasistencias_resumen;
		$data['inasistencias_resumen_sexo'] = $inasistencias_resumen_sexo;
		$data['inasistencias_acumulada_resumen'] = $inasistencias_acumulada_resumen;
		$data['inasistencias'] = $this->division_inasistencia_model->get_registros($division->id, $division_inasistencia->ciclo_lectivo);
		$data['dias_mes_abierto'] = $this->division_inasistencia_dia_model->get(array('division_inasistencia_id' => $division_inasistencia->id));
		$data['mes'] = $division_inasistencia->mes;
		$data['ciclo_lectivo'] = $division_inasistencia->ciclo_lectivo;
		$data['division_inasistencia'] = $division_inasistencia;
		$data['dias'] = $this->division_inasistencia_dia_model->get_dias($division_inasistencia->id);
		$alumnos = $this->alumno_inasistencia_model->get_alumnos_division($division_inasistencia->id, $division_inasistencia->mes, $orden);
		$data['alumnos'] = $alumnos;
		$data['alumnos_resumen_mensual'] = $alumnos_resumen_mensual;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['orden'] = $orden;
		$data['title'] = TITLE . ' - División';
		if ($division->calendario_id <= 2) {
			$data['resumen'] = $this->load->view('division_inasistencia/division_inasistencia_resumen_2', $data, TRUE);
		} else {
			$data['resumen'] = $this->load->view('division_inasistencia/division_inasistencia_resumen_1', $data, TRUE);
		}
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('division_inasistencia/division_inasistencia_ver', $data);
	}

	public function administrar_rol_asistencia_division($division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->admin_rol_asistencia) || $division_id == NULL || !ctype_digit($division_id)) {
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
		$tableData = array(
			'columns' => array(
				array('label' => 'Usuario', 'data' => 'usuario', 'width' => 13),
				array('label' => 'CUIL', 'data' => 'cuil', 'width' => 10),
				array('label' => 'Persona', 'data' => 'persona', 'width' => 21),
				array('label' => 'Rol/es', 'data' => 'rol', 'width' => 14),
				array('label' => 'Entidad', 'data' => 'entidad', 'width' => 20),
				array('label' => 'Activo', 'data' => 'active', 'class' => 'dt-body-right', 'width' => 7),
				array('label' => 'Asignado', 'data' => 'rol_asignado', 'width' => 15)
			),
			'order' => array(),
			'table_id' => 'usuario_table',
			'source_url' => "division_inasistencia/listar_usuarios_data/$division_id",
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
		$data['ciclo_lectivo'] = date('Y');
		$data['title'] = "Administrar rol asistencia de la division";
		$this->load_template('division_inasistencia/division_inasistencia_administrar_rol_asistencia', $data);
	}

	public function listar_usuarios_data($division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->admin_rol_asistencia) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select("usuario.id, usuario.usuario, persona.cuil as cuil, CONCAT(persona.apellido, ', ', persona.nombre) as persona, GROUP_CONCAT(rol.nombre SEPARATOR '<br>') as rol, entidad.nombre as entidad, (CASE usuario.active WHEN 1 THEN 'Activo' ELSE 'No Activo' END) as active, (CASE WHEN rol_asistencia.entidad_id IS NOT NULL THEN 'Si' ELSE 'No' END) as rol_asignado,group_concat(distinct rol_asistencia.entidad_id) as division_asignada_id,rol_docente_division.id as rol_docente_division_id, rol_asistencia.id as rol_asistencia_id")
			->unset_column('id')
			->from('usuario')
			->join('usuario_persona', 'usuario_persona.usuario_id=usuario.id')
			->join('usuario_rol', 'usuario_rol.usuario_id=usuario.id', 'left')
			->join('usuario_rol rol_asistencia', 'rol_asistencia.usuario_id=usuario.id AND rol_asistencia.rol_id=26 AND rol_asistencia.entidad_id=' . $division_id, 'left')
			->join('usuario_rol rol_docente_division', 'rol_docente_division.usuario_id=usuario.id AND rol_docente_division.rol_id=5 AND rol_docente_division.entidad_id = ' . $division_id, 'left')
			->join('rol', 'usuario_rol.rol_id=rol.id', 'left')
			->join('entidad_tipo', 'rol.entidad_tipo_id=entidad_tipo.id', 'left')
			->join('entidad', 'entidad.tabla=entidad_tipo.tabla and entidad.id=usuario_rol.entidad_id', 'left')
			->join('persona', "persona.cuil = usuario_persona.cuil", 'left')
			->where('usuario.active', 1)
			->where_in('rol.codigo', array(ROL_ASISTENCIA_DIVISION, ROL_DOCENTE))
			->where('usuario_rol.entidad_id', $division_id)
			->group_by('usuario.id')
			->add_column('rol_asignado', '$1', 'dt_rol_asignado_division(rol_docente_division_id,rol_asistencia_id)');
		echo $this->datatables->generate();
	}

	public function modal_buscar_usuarios($division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->admin_rol_asistencia) || $division_id == NULL || !ctype_digit($division_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$division = $this->division_model->get_one($division_id);
		if (empty($division)) {
			return $this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		if (isset($_POST) && !empty($_POST)) {
			$usuario_id = $this->input->post('usuario');
			$rol_inasistencia = $this->division_inasistencia_model->verificar_usuario_rol_asistencia($usuario_id, $division_id);
			$rol_docente_division = $this->division_model->verificar_usuario_rol_docente($usuario_id, $division_id);
			$this->load->model('usuario_model');
			$usuario = $this->usuario_model->get(array('id' => $usuario_id));
			if (!empty($usuario)) {
				$roles = $this->usuarios_model->get_roles($usuario_id);
				if (empty($rol_inasistencia) && empty($rol_docente_division)) {
					echo json_encode(array('status' => 'success', 'roles' => $roles, 'rol_inasistencia' => '0', 'rol_docente_division' => '0'));
				} elseif (!empty($rol_inasistencia) && empty($rol_docente_division)) {
					echo json_encode(array('status' => 'success', 'roles' => $roles, 'rol_inasistencia' => '1', 'rol_docente_division' => '0'));
				} elseif (empty($rol_inasistencia) && !empty($rol_docente_division)) {
					echo json_encode(array('status' => 'success', 'roles' => $roles, 'rol_inasistencia' => '0', 'rol_docente_division' => '1'));
				} elseif (!empty($rol_inasistencia) && !empty($rol_docente_division)) {
					echo json_encode(array('status' => 'success', 'roles' => $roles, 'rol_inasistencia' => '1', 'rol_docente_division' => '1'));
				}
			} else {
				echo json_encode(array('status' => 'success', 'roles' => '-1', 'rol_inasistencia' => '0'));
			}
			return TRUE;
		}
		$data['division'] = $division;
		$data['title'] = "Asignar rol de asistencia de la division";
		$data['txt_btn'] = "Asignar";
		$this->load->view('division_inasistencia/division_inasistencia_modal_buscar_usuarios', $data);
	}

	public function asignar_rol_asistencia_division($usuario_id = NULL, $division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->admin_rol_asistencia) || $usuario_id == NULL || !ctype_digit($usuario_id) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('usuario_model');
		$usuario = $this->usuario_model->get_one($usuario_id);
		if (empty($usuario)) {
			show_error('No se encontró el registro del usuario', 500, 'Registro no encontrado');
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

		$this->load->model('usuario_rol_model');
		$this->db->trans_begin();
		$trans_ok = TRUE;
		$rol_id = 26;
		$entidad_id = $division_id;
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
		redirect("division_inasistencia/administrar_rol_asistencia_division/$division_id", 'refresh');
	}

	public function modal_eliminar_rol_asistencia($usuario_rol_id = NULL) {
		if (!in_array($this->rol->codigo, $this->admin_rol_asistencia) || $usuario_rol_id == NULL || !ctype_digit($usuario_rol_id)) {
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
		$division = $this->division_model->get_one($usuario_rol->entidad_id);
		if (empty($division)) {
			return $this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($usuario_rol_id !== $this->input->post('usuario_rol_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->usuario_rol_model->delete(array(
				'id' => $this->input->post('usuario_rol_id')
			));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->usuario_rol_model->get_msg());
			} else {
				$this->session->set_flashdata('error', $this->usuario_rol_model->get_error());
			}
			redirect("division_inasistencia/administrar_rol_asistencia_division/$division->id", 'refresh');
		}

		$data['division'] = $division;
		$data['usuario'] = $usuario;
		$data['escuela'] = $escuela;
		$data['usuario_rol'] = $usuario_rol;
		$data['fields_division'] = $this->build_fields($this->division_model->fields, $division, TRUE);
		$data['fields'] = $this->build_fields($this->usuario_model->fields, $usuario, TRUE);
		$data['title'] = "Eliminar rol de asistencia al usuario";
		$data['txt_btn'] = "Quitar rol";
		$this->load->view('division_inasistencia/division_inasistencia_modal_eliminar_usuario_rol', $data);
	}

	public function modal_agregar_dia($id = NULL, $fecha = NULL, $orden = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id) || $fecha == NULL || empty(DateTime::createFromFormat('Ymd', $fecha))) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$division_inasistencia = $this->division_inasistencia_model->get_one($id);
		if (empty($division_inasistencia) || $division_inasistencia->resumen_mensual === 'Si') {
			$this->modal_error('No se encontró el registro de la inasistencia de división', 'Registro no encontrado');
			return;
		}
		$division = $this->division_model->get_one($division_inasistencia->division_id);
		if (empty($division)) {
			$this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
			return;
		}

		$dia = $this->division_inasistencia_dia_model->get_by_fecha($id, (new DateTime($fecha))->format('Y-m-d'));
		if (!empty($dia)) {
			$this->modal_error('Ya se ha creado la inasistencia del día', 'Error general');
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
		$this->load->model('inasistencia_tipo_model');

		$division->escuela = "Esc. $escuela->nombre_largo";

		$this->load->model('inasistencia_actividad_model');
		$this->array_contraturno_control = $array_contraturno = array('No' => 'No', 'Si' => 'Si', 'Parcial' => 'Parcial');
		$this->array_inasistencia_actividad_control = $array_inasistencia_actividad = $this->get_array('inasistencia_actividad');
		$this->set_model_validation_rules($this->division_inasistencia_dia_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->division_inasistencia_dia_model->create(array(
					'division_inasistencia_id' => $division_inasistencia->id,
					'fecha' => (new DateTime($fecha))->format('Y-m-d'),
					'inasistencia_actividad_id' => $this->input->post('inasistencia_actividad'),
					'contraturno' => $this->input->post('contraturno')
					), FALSE);
				$dia_id = $this->division_inasistencia_dia_model->get_row_id();
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', 'Asistencia de alumnos actualizada');
					redirect("division_inasistencia/editar_dia/$dia_id/$orden", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al actualizar la asistencia.';
					if ($this->division_inasistencia_dia_model->get_error()) {
						$errors .= '<br>' . $this->division_inasistencia_dia_model->get_error();
					}
					$this->session->set_flashdata('error', $errors);
					redirect("division_inasistencia/ver/$id/$orden", 'refresh');
				}
			}
		}

		$this->division_inasistencia_dia_model->fields['contraturno']['array'] = $array_contraturno;
		$this->division_inasistencia_dia_model->fields['inasistencia_actividad']['array'] = $array_inasistencia_actividad;

		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['mes'] = $division_inasistencia->mes;
		$data['ciclo_lectivo'] = $division_inasistencia->ciclo_lectivo;
		$data['division_inasistencia'] = $division_inasistencia;
		$data['fields'] = $this->build_fields($this->division_inasistencia_dia_model->fields);
		$data['orden'] = $orden;
		$data['title'] = 'Crear asistencia - Día ' . (new DateTime($fecha))->format('d/m/Y');
		$this->load->view('division_inasistencia/division_inasistencia_modal_agregar_dia', $data);
	}

	public function editar_dia($id, $orden = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$dia = $this->division_inasistencia_dia_model->get_one($id);
		if (empty($dia)) {
			show_error('No se encontró el registro de la asistencia', 500, 'Registro no encontrado');
		}
		$division_inasistencia = $this->division_inasistencia_model->get_one($dia->division_inasistencia_id);
		if (empty($division_inasistencia) || $division_inasistencia->resumen_mensual === 'Si') {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($division_inasistencia->division_id);
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

		$this->load->model('alumno_inasistencia_model');
		$this->load->model('inasistencia_tipo_model');

		$division->escuela = "Esc. $escuela->nombre_largo";

		if (isset($_POST) && !empty($_POST)) {
			$this->array_justificada_control = array('Si' => 'Si', 'No' => 'No', 'NC' => 'NC', '' => '');
			$this->form_validation->set_rules('inasistencia[]', 'Inasistencia', 'numeric');
			$this->form_validation->set_rules('justificada[]', 'Justificada', 'callback_control_combo[justificada]');
			if ($this->form_validation->run() === TRUE) {
				$inasistencias = $this->input->post('inasistencia');
				$justificadas = $this->input->post('justificada');
				$alumno_inasistencia_ids = $this->input->post('alumno_inasistencia_ids');
				$inasistencias_tipos = $this->inasistencia_tipo_model->get_array();
				if ($division->calendario_id <= 2) {
					$inasistencias_tipos['3']->falta_defecto = '0';
					$inasistencias_tipos['3']->falta_contraturno = '0';
					$inasistencias_tipos['4']->falta_defecto = '0';
					$inasistencias_tipos['4']->falta_contraturno = '0';
					$inasistencias_tipos['8']->falta_defecto = '0';
					$inasistencias_tipos['8']->falta_contraturno = '0';
				}
				$this->db->trans_begin();
				$trans_ok = TRUE;
				switch ($dia->contraturno) {
					case 'No':
						$turnos = array('No');
						break;
					case 'Si':
						$turnos = array('No', 'Si');
						break;
					case 'Parcial':
						$turnos = array('No', 'Si');
						break;
				}
				foreach ($turnos as $turno) {
					if (!empty($inasistencias[$turno])) {
						foreach ($inasistencias[$turno] as $alumno_division_id => $inasistencia) {
							$alumno_inasistencia_id = $alumno_inasistencia_ids[$turno][$alumno_division_id];
							if ($inasistencia !== '0') {
								$justificada = $justificadas[$turno][$alumno_division_id];
								$falta = $inasistencias_tipos[$inasistencia];
							} else {
								$justificada = NULL;
								$falta = NULL;
							}
							if ($alumno_inasistencia_id !== '' && $inasistencia !== '0') {
								if ($dia->inasistencia_actividad_id === '1') {
									if ($dia->contraturno === 'Parcial' && $turno === 'No' && isset($inasistencias['Si'][$alumno_division_id]) && $inasistencias['Si'][$alumno_division_id] === '9') {
										$falta_inasistencia = $falta->falta_defecto;
									} else {
										$falta_inasistencia = $dia->contraturno === 'No' ? $falta->falta_defecto : $falta->falta_contraturno;
									}
								} else {
									$falta_inasistencia = '';
								}

								$trans_ok &= $this->alumno_inasistencia_model->update(array(
									'id' => $alumno_inasistencia_id,
									'division_inasistencia_dia_id' => $dia->id,
									'alumno_division_id' => $alumno_division_id,
									'inasistencia_tipo_id' => $inasistencia,
									'justificada' => $justificada,
									'contraturno' => $turno,
									'falta' => $falta_inasistencia,
									), FALSE);
							} elseif ($alumno_inasistencia_id !== '') {
								$trans_ok &= $this->alumno_inasistencia_model->delete(array('id' => $alumno_inasistencia_id), FALSE);
							} elseif ($inasistencia !== '0') {
								if ($dia->inasistencia_actividad_id === '1') {
									if ($dia->contraturno === 'Parcial' && $turno === 'No' && isset($inasistencias['Si'][$alumno_division_id]) && $inasistencias['Si'][$alumno_division_id] === '9') {
										$falta_inasistencia = $falta->falta_defecto;
									} else {
										$falta_inasistencia = $dia->contraturno === 'No' ? $falta->falta_defecto : $falta->falta_contraturno;
									}
								} else {
									$falta_inasistencia = '';
								}

								$trans_ok &= $this->alumno_inasistencia_model->create(array(
									'division_inasistencia_dia_id' => $dia->id,
									'alumno_division_id' => $alumno_division_id,
									'inasistencia_tipo_id' => $inasistencia,
									'justificada' => $justificada,
									'contraturno' => $turno,
									'falta' => $falta_inasistencia,
									), FALSE);
							}
						}
					}
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', 'Asistencia de alumnos actualizada');
					redirect("division_inasistencia/ver/$division_inasistencia->id/$orden", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al actualizar la asistencia.';
					if ($this->alumno_inasistencia_model->get_error()) {
						$errors .= '<br>' . $this->alumno_inasistencia_model->get_error();
					}
					if ($this->division_inasistencia_dia_model->get_error()) {
						$errors .= '<br>' . $this->division_inasistencia_dia_model->get_error();
					}
					$this->session->set_flashdata('error', $errors);
				}
			}
		}

		$data['dia'] = $dia;
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['fecha'] = $dia->fecha;
		$data['mes'] = $division_inasistencia->mes;
		$data['ciclo_lectivo'] = $division_inasistencia->ciclo_lectivo;
		$data['division_inasistencia'] = $division_inasistencia;
		$this->load->model('alumno_inasistencia_model');
		$data['alumnos']['No'] = $this->alumno_inasistencia_model->get_alumnos_dia($dia->id, 'No', $orden);
		if ($dia->contraturno === 'Si' || $dia->contraturno === 'Parcial') {
			$data['alumnos']['Si'] = $this->alumno_inasistencia_model->get_alumnos_dia($dia->id, 'Si', $orden);
		}
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['orden'] = $orden;
		$data['title'] = TITLE . ' - División';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('division_inasistencia/division_inasistencia_editar_dia', $data);
	}

	public function eliminar_dia($id, $orden = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$dia = $this->division_inasistencia_dia_model->get_one($id);
		if (empty($dia)) {
			show_error('No se encontró el registro de la asistencia', 500, 'Registro no encontrado');
		}
		$division_inasistencia = $this->division_inasistencia_model->get_one($dia->division_inasistencia_id);
		if (empty($division_inasistencia) || $division_inasistencia->resumen_mensual === 'Si') {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($division_inasistencia->division_id);
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

		if (isset($_POST) && !empty($_POST)) {
			if ($dia->id !== $this->input->post('division_inasitencia_dia_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$alumnos_inasistencias = $this->alumno_inasistencia_model->get(array('division_inasistencia_dia_id' => $dia->id));
			$this->db->trans_begin();
			$trans_ok = TRUE;
			if (!empty($alumnos_inasistencias)) {
				foreach ($alumnos_inasistencias as $inasistencia) {
					$trans_ok &= $this->alumno_inasistencia_model->delete(array('id' => $inasistencia->id), FALSE);
				}
			}
			$trans_ok &= $this->division_inasistencia_dia_model->delete(array('id' => $dia->id), FALSE);
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Asistencia de alumnos actualizada');
				redirect("division_inasistencia/ver/$division_inasistencia->id/$orden", 'refresh');
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al actualizar la asistencia.';
				if ($this->alumno_inasistencia_model->get_error())
					$errors .= '<br>' . $this->alumno_inasistencia_model->get_error();
				if ($this->division_inasistencia_dia_model->get_error())
					$errors .= '<br>' . $this->division_inasistencia_dia_model->get_error();
				$this->session->set_flashdata('error', $errors);
			}
		}
		redirect("division_inasistencia/editar_dia/$dia->id/$orden", 'refresh');
	}

	public function agregar_resumen_mensual($id = NULL, $orden = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$division_inasistencia = $this->division_inasistencia_model->get_one($id);
		if (empty($division_inasistencia)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$dia = $this->division_inasistencia_dia_model->get_by_fecha($id, NULL);
		if (!empty($dia)) {
			redirect("division_inasistencia/editar_resumen_mensual/$dia->id", 'refresh');
		}
		$division = $this->division_model->get_one($division_inasistencia->division_id);
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

		$this->db->trans_begin();
		$trans_ok = TRUE;
		$trans_ok &= $this->division_inasistencia_dia_model->create(array(
			'division_inasistencia_id' => $division_inasistencia->id,
			'inasistencia_actividad_id' => '1',
			'contraturno' => 'No'
			), FALSE);
		$dia_id = $this->division_inasistencia_dia_model->get_row_id();

		if ($this->db->trans_status() && $trans_ok) {
			$this->db->trans_commit();
			$this->session->set_flashdata('message', $this->division_inasistencia_dia_model->get_msg());
			redirect("division_inasistencia/editar_resumen_mensual/$dia_id/$orden", 'refresh');
		} else {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', $this->division_inasistencia_dia_model->get_error());
			redirect("division_inasistencia/ver/$id/$orden", 'refresh');
		}
	}

	public function editar_resumen_mensual($id = NULL, $orden = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$dia = $this->division_inasistencia_dia_model->get_one($id);
		if (empty($dia)) {
			show_error('No se encontró el registro de la asistencia', 500, 'Registro no encontrado');
		}
		$division_inasistencia = $this->division_inasistencia_model->get_one($dia->division_inasistencia_id);
		if (empty($division_inasistencia) || $division_inasistencia->resumen_mensual === 'No') {
			show_error('No se encontró el registro de la inasistencia de la división', 500, 'Registro no encontrado');
		}

		$division = $this->division_model->get_one($division_inasistencia->division_id);
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
		$alumnos = $this->alumno_inasistencia_model->get_alumnos_resumen($division_inasistencia->id, $orden);
		if (isset($_POST) && !empty($_POST)) {
			$this->form_validation->set_rules('justificadas[]', 'Justificadas', 'numeric');
			$this->form_validation->set_rules('injustificadas[]', 'Injustificadas', 'numeric');
			$this->form_validation->set_rules('dias_previos[]', 'Justificadas', 'integer');
			$this->form_validation->set_rules('dias_posteriores[]', 'Justificadas', 'integer');
			if ($this->form_validation->run() === TRUE) {
				$justificada = $this->input->post('justificadas');
				$injustificada = $this->input->post('injustificadas');
				$dias_previos = $this->input->post('dias_previos');
				$dias_posteriores = $this->input->post('dias_posteriores');
				if (!empty($justificada) || !empty($injustificada)) {
					$this->db->trans_begin();
					$trans_ok = TRUE;
					if(!empty($justificada)){
						foreach ($justificada as $alumno_division_id => $cantidad) {
							if (isset($alumnos[$alumno_division_id]->Si) && $cantidad > 0) {
								$trans_ok &= $this->alumno_inasistencia_model->update(array(
									'id' => $alumnos[$alumno_division_id]->Si->id,
									'division_inasistencia_dia_id' => $dia->id,
									'falta' => $cantidad,
									), FALSE);
							} elseif (isset($alumnos[$alumno_division_id]->Si)) {
								$trans_ok &= $this->alumno_inasistencia_model->delete(array('id' => $alumnos[$alumno_division_id]->Si->id), FALSE);
							} elseif ($cantidad > 0) {
								$trans_ok &= $this->alumno_inasistencia_model->create(array(
									'division_inasistencia_dia_id' => $dia->id,
									'alumno_division_id' => $alumno_division_id,
									'inasistencia_tipo_id' => 1,
									'justificada' => 'Si',
									'falta' => $cantidad,
									), FALSE);
							}
						}
					}
					if(!empty($injustificada)){
						foreach ($injustificada as $alumno_division_id => $cantidad) {
							if (isset($alumnos[$alumno_division_id]->No) && $cantidad > 0) {
								$trans_ok &= $this->alumno_inasistencia_model->update(array(
									'id' => $alumnos[$alumno_division_id]->No->id,
									'falta' => $cantidad,
									), FALSE);
							} elseif (isset($alumnos[$alumno_division_id]->No)) {
								$trans_ok &= $this->alumno_inasistencia_model->delete(array('id' => $alumnos[$alumno_division_id]->No->id), FALSE);
							} elseif ($cantidad > 0) {
								$trans_ok &= $this->alumno_inasistencia_model->create(array(
									'division_inasistencia_dia_id' => $dia->id,
									'alumno_division_id' => $alumno_division_id,
									'inasistencia_tipo_id' => 1,
									'justificada' => 'No',
									'falta' => $cantidad,
									), FALSE);
							}
						}
					}
					if(!empty($dias_previos)){
						foreach ($dias_previos as $alumno_division_id => $cantidad) {
							if (isset($alumnos[$alumno_division_id]->Prev) && $cantidad > 0) {
								$trans_ok &= $this->alumno_inasistencia_model->update(array(
									'id' => $alumnos[$alumno_division_id]->Prev->id,
									'falta' => $cantidad,
									), FALSE);
							} elseif (isset($alumnos[$alumno_division_id]->Prev)) {
								$trans_ok &= $this->alumno_inasistencia_model->delete(array('id' => $alumnos[$alumno_division_id]->Prev->id), FALSE);
							} elseif ($cantidad > 0) {
								$trans_ok &= $this->alumno_inasistencia_model->create(array(
									'division_inasistencia_dia_id' => $dia->id,
									'alumno_division_id' => $alumno_division_id,
									'inasistencia_tipo_id' => 5,
									'justificada' => 'NC',
									'falta' => $cantidad,
									), FALSE);
							}
						}
					}
					if(!empty($dias_posteriores)){
						foreach ($dias_posteriores as $alumno_division_id => $cantidad) {
							if (isset($alumnos[$alumno_division_id]->Post) && $cantidad > 0) {
								$trans_ok &= $this->alumno_inasistencia_model->update(array(
									'id' => $alumnos[$alumno_division_id]->Post->id,
									'falta' => $cantidad,
									), FALSE);
							} elseif (isset($alumnos[$alumno_division_id]->Post)) {
								$trans_ok &= $this->alumno_inasistencia_model->delete(array('id' => $alumnos[$alumno_division_id]->Post->id), FALSE);
							} elseif ($cantidad > 0) {
								$trans_ok &= $this->alumno_inasistencia_model->create(array(
									'division_inasistencia_dia_id' => $dia->id,
									'alumno_division_id' => $alumno_division_id,
									'inasistencia_tipo_id' => 6,
									'justificada' => 'NC',
									'falta' => $cantidad,
									), FALSE);
							}
						}
					}
					if ($this->db->trans_status() && $trans_ok) {
						$this->db->trans_commit();
						$this->session->set_flashdata('message', 'Asistencia mensual de alumno actualizada');
						redirect("division_inasistencia/ver/$division_inasistencia->id/$orden", 'refresh');
					} else {
						$this->db->trans_rollback();
					}
				}
			}
		}
		$division->escuela = "Esc. $escuela->nombre_largo";
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['dia_id'] = $dia->id;
		$data['mes'] = $division_inasistencia->mes;
		$data['ciclo_lectivo'] = $division_inasistencia->ciclo_lectivo;
		$data['division_inasistencia'] = $division_inasistencia;
		$this->load->model('alumno_inasistencia_model');
		$data['alumnos'] = $alumnos;
		$data['error'] = validation_errors() ? validation_errors() : ($this->alumno_inasistencia_model->get_error() ? $this->alumno_inasistencia_model->get_error() : $this->session->flashdata('error'));
		$data['message'] = $this->session->flashdata('message');
		$data['orden'] = $orden;
		$data['title'] = TITLE . ' - División';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('division_inasistencia/division_inasistencia_editar_resumen', $data);
	}

	public function eliminar_resumen_mensual($id = NULL, $orden = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$dia = $this->division_inasistencia_dia_model->get_one($id);
		if (empty($dia)) {
			show_error('No se encontró el registro de la asistencia', 500, 'Registro no encontrado');
		}
		$division_inasistencia = $this->division_inasistencia_model->get_one($dia->division_inasistencia_id);
		if (empty($division_inasistencia) || $division_inasistencia->resumen_mensual === 'No') {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($division_inasistencia->division_id);
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

		if (isset($_POST) && !empty($_POST)) {
			if ($dia->id !== $this->input->post('division_inasitencia_dia_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$alumnos_inasistencias = $this->alumno_inasistencia_model->get(array('division_inasistencia_dia_id' => $dia->id));
			$this->db->trans_begin();
			$trans_ok = TRUE;
			if (!empty($alumnos_inasistencias)) {
				foreach ($alumnos_inasistencias as $inasistencia) {
					$trans_ok &= $this->alumno_inasistencia_model->delete(array('id' => $inasistencia->id), FALSE);
				}
			}
			$trans_ok &= $this->division_inasistencia_dia_model->delete(array('id' => $dia->id), FALSE);
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Asistencia de alumnos actualizada');
				redirect("division_inasistencia/ver/$division_inasistencia->id/$orden", 'refresh');
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al actualizar la asistencia.';
				if ($this->alumno_inasistencia_model->get_error())
					$errors .= '<br>' . $this->alumno_inasistencia_model->get_error();
				if ($this->division_inasistencia_dia_model->get_error())
					$errors .= '<br>' . $this->division_inasistencia_dia_model->get_error();
				$this->session->set_flashdata('error', $errors);
			}
		}
		redirect("division_inasistencia/editar_resumen_mensual/$dia->id/$orden", 'refresh');
	}

	public function modal_abrir_mes($division_id = NULL, $ciclo_lectivo = NULL, $periodo = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$division_id == NULL || !ctype_digit($division_id) ||
			$ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo) ||
			$periodo == NULL || !ctype_digit($periodo) ||
			$mes == NULL || !ctype_digit($mes)
		) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if ($this->edicion === FALSE) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$division = $this->division_model->get_one($division_id);
		if (empty($division)) {
			$this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
			return;
		}
		if (empty($division->calendario_id)) {
			$this->modal_error('La división no tiene un calendario cargado', 'Error al crear asistencia');
			return;
		}
		if (!empty($division->fecha_baja)) {
			$this->modal_error('No se puede editar una división cerrada', 'Error');
			return;
		}
		$this->load->model('calendario_periodo_model');
		$periodo_db = $this->calendario_periodo_model->get(array('calendario_id' => $division->calendario_id, 'periodo' => $periodo, 'ciclo_lectivo' => $ciclo_lectivo));
		if (empty($periodo_db)) {
			$this->modal_error('No se encontró el periodo a cargar', 'Registro no encontrado');
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
		$division_inasistencia = $this->division_inasistencia_model->get_registro($division->id, $ciclo_lectivo, $periodo, $mes);
		if (!empty($division_inasistencia)) {
			$this->modal_error('La inasistencia mensual de la división ya se encuentra creada, recargue la página', 'Error al crear inasistencia mensual');
			return;
		}
		$this->division_inasistencia_model->fields['division']['value'] = "$division->curso $division->division";
		$this->division_inasistencia_model->fields['ciclo_lectivo']['value'] = $ciclo_lectivo;
		$this->division_inasistencia_model->fields['periodo']['value'] = "{$periodo}° $division->nombre_periodo - $ciclo_lectivo";
		$this->division_inasistencia_model->fields['mes']['value'] = $this->nombres_meses[substr($mes, 4, 2)];
		$this->array_resumen_mensual_control = $this->division_inasistencia_model->fields['resumen_mensual']['array'];
		$this->set_model_validation_rules($this->division_inasistencia_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->division_inasistencia_model->create(array(
					'division_id' => $division_id,
					'ciclo_lectivo' => $ciclo_lectivo,
					'periodo' => $periodo,
					'mes' => $mes,
					'dias' => $this->input->post('dias'),
					'resumen_mensual' => $this->input->post('resumen_mensual'),
				));
				$division_inasistencia_id = $this->division_inasistencia_model->get_row_id();
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->division_inasistencia_model->get_msg());
					redirect("division_inasistencia/ver/$division_inasistencia_id", 'refresh');
				} else {
					$this->session->set_flashdata('error', $this->division_inasistencia_model->get_error());
					redirect("division_inasistencia/listar/$division_id/$ciclo_lectivo", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("division_inasistencia/listar/$division_id/$ciclo_lectivo", 'refresh');
			}
		}

		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['mes'] = $mes;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['fields'] = $this->build_fields($this->division_inasistencia_model->fields);

		$dias_semana = array(0, 1, 1, 1, 1, 1, 0);
		$fecha_ini_m = new DateTime($mes . '01');
		$fecha_ini_p = new DateTime($periodo_db[0]->inicio);
		$fecha_fin_m = new DateTime($mes . '01 +1 month');
		$fecha_fin_p = new DateTime($periodo_db[0]->fin . ' +1 day');
		$fecha_ini = max(array($fecha_ini_m, $fecha_ini_p));
		$fecha_fin = min(array($fecha_fin_m, $fecha_fin_p));
		$dia = DateInterval::createFromDateString('1 day');
		$fechas = new DatePeriod($fecha_ini, $dia, $fecha_fin);
		$dias = 0;
		$dias_max = 0;
		foreach ($fechas as $fecha) {
			$dias += $dias_semana[$fecha->format('w')];
			$dias_max++;
		}

		$data['dias_cursado'] = $dias;
		$data['dias_max_cursado'] = $dias_max;
		$data['fecha_ini'] = $fecha_ini;
		$data['fecha_fin'] = (new DateTime($fecha_fin->format('Y-m-d') . ' -1 day'));

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Apertura de mes para registro de Asistencias';
		$this->load->view('division_inasistencia/division_inasistencia_modal_abrir_mes', $data);
	}

	public function modal_cerrar_mes($id = NULL, $orden = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if ($this->edicion === FALSE) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$division_inasistencia = $this->division_inasistencia_model->get_one($id);
		if (empty($division_inasistencia)) {
			$this->modal_error('No se encontró el registro de la inasistencia de la división', 'Registro no encontrado');
			return;
		}
		$division = $this->division_model->get_one($division_inasistencia->division_id);
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
		$this->division_inasistencia_model->fields['resumen_mensual'] = array('label' => 'Tipo de Carga', 'readonly' => TRUE);
		$division_inasistencia->periodo = "{$division_inasistencia->periodo}° $division_inasistencia->nombre_periodo - $division_inasistencia->ciclo_lectivo";
		$division_inasistencia->division = "$division_inasistencia->curso $division_inasistencia->division";
		if ($division_inasistencia->resumen_mensual === 'No') {
			$dias = $this->division_inasistencia_dia_model->get(array('division_inasistencia_id' => $division_inasistencia->id, 'inasistencia_actividad_id' => '1'));
			$division_inasistencia->dias = empty($dias) ? 0 : count($dias);
			$fechas = array();
			if (!empty($dias)) {
				foreach ($dias as $dia) {
					$fechas[] = $dia->fecha;
				}
			}
			$data['fechas'] = $fechas;
		}
		$division_inasistencia->resumen_mensual = $division_inasistencia->resumen_mensual === 'Si' ? 'Resumen Mensual' : 'Detallado por Día';

		$this->set_model_validation_rules($this->division_inasistencia_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($division_inasistencia->dias === NULL || $division_inasistencia->dias === 0) {
				$this->session->set_flashdata('error', 'No puede cerrar el registro de asistencia del mes si no posee ningún día cargado.');
				redirect("division_inasistencia/ver/$division_inasistencia->id/$orden", 'refresh');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->division_inasistencia_model->update(array(
					'id' => $division_inasistencia->id,
					'dias' => $division_inasistencia->dias,
					'fecha_cierre' => date('Y-m-d H:i:s')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', 'Registro de Inasistencia Mensual Cerrado Correctamente');
					redirect("division_inasistencia/ver/$division_inasistencia->id/$orden", 'refresh');
				} else {
					$this->session->set_flashdata('error', $this->division_inasistencia_model->get_error());
					redirect("division_inasistencia/listar/$division_inasistencia->division_id/$division_inasistencia->ciclo_lectivo", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("division_inasistencia/listar/$division_inasistencia->division_id/$division_inasistencia->ciclo_lectivo", 'refresh');
			}
		}

		$data['escuela'] = $escuela;

		$fecha_ini_m = new DateTime($division_inasistencia->mes . '01');
		$fecha_ini_p = new DateTime($division_inasistencia->fecha_inicio);
		$fecha_fin_m = new DateTime($division_inasistencia->mes . '01 +1 month');
		$fecha_fin_p = new DateTime($division_inasistencia->fecha_fin . ' +1 day');
		$fecha_ini = max(array($fecha_ini_m, $fecha_ini_p));
		$fecha_fin = min(array($fecha_fin_m, $fecha_fin_p));
		$data['division'] = $division;
		$data['mes'] = $division_inasistencia->mes;
		$data['fecha_ini'] = $fecha_ini;
		$data['fecha_fin'] = (new DateTime($fecha_fin->format('Y-m-d') . ' -1 day'));
		$data['ciclo_lectivo'] = $division_inasistencia->ciclo_lectivo;
		$this->division_inasistencia_model->fields['dias']['readonly'] = TRUE;
		$data['fields'] = $this->build_fields($this->division_inasistencia_model->fields, $division_inasistencia);
		$data['dias'] = $division_inasistencia->dias;
		$data['txt_btn'] = 'Cerrar';
		$data['title'] = 'Cerrar mes para registro de Asistencias';
		$this->load->view('division_inasistencia/division_inasistencia_modal_cerrar_mes', $data);
	}

	public function modal_abrir_mes_cerrado($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_admin) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$division_inasistencia = $this->division_inasistencia_model->get_one($id);
		if (empty($division_inasistencia)) {
			$this->modal_error('No se encontró el registro de la inasistencia de la división', 'Registro no encontrado');
			return;
		}
		$division = $this->division_model->get_one($division_inasistencia->division_id);
		if (empty($division)) {
			$this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
			return;
		}
		if (!empty($division->fecha_baja)) {
			$this->modal_error('No se puede editar una división cerrada', 'Error');
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
		$this->division_inasistencia_model->fields['resumen_mensual'] = array('label' => 'Tipo de Carga', 'readonly' => TRUE);
		$division_inasistencia->periodo = "{$division_inasistencia->periodo}° $division_inasistencia->nombre_periodo - $division_inasistencia->ciclo_lectivo";
		$division_inasistencia->division = "$division_inasistencia->curso $division_inasistencia->division";
		if ($division_inasistencia->resumen_mensual === 'No') {
			$dias = $this->division_inasistencia_dia_model->get(array('division_inasistencia_id' => $division_inasistencia->id, 'inasistencia_actividad_id' => '1'));
			$division_inasistencia->dias = empty($dias) ? 0 : count($dias);
			$fechas = array();
			if ($dias != '0') {
				foreach ($dias as $dia) {
					$fechas[] = $dia->fecha;
				}
			}
			$data['fechas'] = $fechas;
		}
		$division_inasistencia->resumen_mensual = $division_inasistencia->resumen_mensual === 'Si' ? 'Resumen Mensual' : 'Detallado por Día';
		$this->set_model_validation_rules($this->division_inasistencia_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->division_inasistencia_model->update(array(
					'id' => $division_inasistencia->id,
					'fecha_cierre' => ''
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', 'Registro de Inasistencia Mensual Abierto Correctamente');
					redirect("division_inasistencia/ver/$division_inasistencia->id", 'refresh');
				} else {
					$this->session->set_flashdata('error', $this->division_inasistencia_model->get_error());
					redirect("division_inasistencia/listar/$division_inasistencia->division_id/$division_inasistencia->ciclo_lectivo", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("division_inasistencia/listar/$division_inasistencia->division_id/$division_inasistencia->ciclo_lectivo", 'refresh');
			}
		}

		$data['escuela'] = $escuela;

		$fecha_ini_m = new DateTime($division_inasistencia->mes . '01');
		$fecha_ini_p = new DateTime($division_inasistencia->fecha_inicio);
		$fecha_fin_m = new DateTime($division_inasistencia->mes . '01 +1 month');
		$fecha_fin_p = new DateTime($division_inasistencia->fecha_fin . ' +1 day');
		$fecha_ini = max(array($fecha_ini_m, $fecha_ini_p));
		$fecha_fin = min(array($fecha_fin_m, $fecha_fin_p));
		$data['division'] = $division;
		$data['mes'] = $division_inasistencia->mes;
		$data['fecha_ini'] = $fecha_ini;
		$data['fecha_fin'] = (new DateTime($fecha_fin->format('Y-m-d') . ' -1 day'));
		$data['ciclo_lectivo'] = $division_inasistencia->ciclo_lectivo;
		$this->division_inasistencia_model->fields['dias']['readonly'] = TRUE;
		$data['fields'] = $this->build_fields($this->division_inasistencia_model->fields, $division_inasistencia);
		$data['dias'] = $division_inasistencia->dias;
		$data['txt_btn'] = 'Abrir';
		$data['title'] = 'Abrir mes cerrado para registro de Asistencias';
		$this->load->view('division_inasistencia/division_inasistencia_modal_abrir_mes_cerrado', $data);
	}

	public function modal_editar_dias($id = NULL, $orden = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$division_inasistencia = $this->division_inasistencia_model->get_one($id);
		if (empty($division_inasistencia)) {
			$this->modal_error('No se encontró el registro de la inasistencia de la división', 'Registro no encontrado');
			return;
		}
		$division = $this->division_model->get_one($division_inasistencia->division_id);
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
		$this->division_inasistencia_model->fields['resumen_mensual'] = array('label' => 'Tipo de Carga', 'readonly' => TRUE);
		$division_inasistencia->periodo = "{$division_inasistencia->periodo}° $division_inasistencia->nombre_periodo - $division_inasistencia->ciclo_lectivo";
		$division_inasistencia->division = "$division_inasistencia->curso $division_inasistencia->division";
		if ($division_inasistencia->resumen_mensual === 'No') {
			$dias = $this->division_inasistencia_dia_model->get(array('division_inasistencia_id' => $division_inasistencia->id, 'inasistencia_actividad_id' => '1'));
			$division_inasistencia->dias = empty($dias) ? 0 : count($dias);
		}
		$division_inasistencia->resumen_mensual = $division_inasistencia->resumen_mensual === 'Si' ? 'Resumen Mensual' : 'Detallado por Día';

		$this->set_model_validation_rules($this->division_inasistencia_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE && $this->input->post('dias')) {
				$trans_ok = TRUE;
				$trans_ok &= $this->division_inasistencia_model->update(array(
					'id' => $division_inasistencia->id,
					'dias' => $this->input->post('dias')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', 'Dias de cursados modificados correctamente');
					redirect("division_inasistencia/ver/$division_inasistencia->id/$orden", 'refresh');
				} else {
					$this->session->set_flashdata('error', $this->division_inasistencia_model->get_error());
					redirect("division_inasistencia/ver/$division_inasistencia->id/$orden", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("division_inasistencia/ver/$division_inasistencia->id/$orden", 'refresh');
			}
		}

		$data['escuela'] = $escuela;

		$fecha_ini_m = new DateTime($division_inasistencia->mes . '01');
		$fecha_ini_p = new DateTime($division_inasistencia->fecha_inicio);
		$fecha_fin_m = new DateTime($division_inasistencia->mes . '01 +1 month');
		$fecha_fin_p = new DateTime($division_inasistencia->fecha_fin . ' +1 day');
		$fecha_ini = max(array($fecha_ini_m, $fecha_ini_p));
		$fecha_fin = min(array($fecha_fin_m, $fecha_fin_p));
		$dia = DateInterval::createFromDateString('1 day');
		$fechas = new DatePeriod($fecha_ini, $dia, $fecha_fin);
		$dias = 0;
		foreach ($fechas as $fecha) {
			$dias ++;
		}
		$data['dias_max_cursado'] = $dias;
		$data['division'] = $division;
		$data['mes'] = $division_inasistencia->mes;
		$data['fecha_ini'] = $fecha_ini;
		$data['fecha_fin'] = (new DateTime($fecha_fin->format('Y-m-d') . ' -1 day'));
		$data['ciclo_lectivo'] = $division_inasistencia->ciclo_lectivo;
		$data['fields'] = $this->build_fields($this->division_inasistencia_model->fields, $division_inasistencia);
		$data['dias'] = $division_inasistencia->dias;
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => '');
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar días hábiles del mes para registro de Asistencias';
		$this->load->view('division_inasistencia/division_inasistencia_modal_editar_dias', $data);
	}

	public function modal_eliminar_mes($id = NULL, $orden = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if ($this->edicion === FALSE) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$division_inasistencia = $this->division_inasistencia_model->get_one($id);
		if (empty($division_inasistencia)) {
			$this->modal_error('No se encontró el registro de la inasistencia de la división', 'Registro no encontrado');
			return;
		}
		if ($division_inasistencia->resumen_mensual === 'No') {
			$dias_creado = $this->division_inasistencia_dia_model->get(array('division_inasistencia_id' => $division_inasistencia->id));
			if (!empty($dias_creado)) {
				$this->modal_error('Para poder eliminar el registro de asistencia del mes, debe eliminar todos los días cargados.', 'Eliminar mes de registro de Asistencias');
				return;
			}
		}

		if ($division_inasistencia->resumen_mensual === 'Si') {
			$dias_creado_mensual = $this->division_inasistencia_dia_model->get(array('division_inasistencia_id' => $division_inasistencia->id));
			if (!empty($dias_creado_mensual)) {
				$this->modal_error('Para poder eliminar el registro de asistencia del mes, debe eliminar todas las inasistencias cargadas.', 'Eliminar mes de registro de Asistencias');
				return;
			}
		}
		$division = $this->division_model->get_one($division_inasistencia->division_id);
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
		$this->division_inasistencia_model->fields['resumen_mensual'] = array('label' => 'Tipo de Carga', 'readonly' => TRUE);
		$division_inasistencia->periodo = "{$division_inasistencia->periodo}° $division_inasistencia->nombre_periodo - $division_inasistencia->ciclo_lectivo";
		$division_inasistencia->division = "$division_inasistencia->curso $division_inasistencia->division";
		if ($division_inasistencia->resumen_mensual === 'No') {
			$dias = $this->division_inasistencia_dia_model->get(array('division_inasistencia_id' => $division_inasistencia->id, 'inasistencia_actividad_id' => '1'));
			$division_inasistencia->dias = empty($dias) ? 0 : count($dias);
			$fechas = array();
			if (!empty($dias)) {
				foreach ($dias as $dia) {
					$fechas[] = $dia->fecha;
				}
			}
			$data['fechas'] = $fechas;
		}


		$division_inasistencia->resumen_mensual = $division_inasistencia->resumen_mensual === 'Si' ? 'Resumen Mensual' : 'Detallado por Día';

		$this->set_model_validation_rules($this->division_inasistencia_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->division_inasistencia_model->delete(array(
					'id' => $division_inasistencia->id
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', 'Registro de Inasistencia Mensual de División Eliminado Correctamente');
					redirect("division_inasistencia/listar/$division->id/$division_inasistencia->ciclo_lectivo", 'refresh');
				} else {
					$this->session->set_flashdata('error', $this->division_inasistencia_model->get_error());
					redirect("division_inasistencia/listar/$division_inasistencia->division_id/$division_inasistencia->ciclo_lectivo", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("division_inasistencia/listar/$division_inasistencia->division_id/$division_inasistencia->ciclo_lectivo", 'refresh');
			}
		}

		$data['escuela'] = $escuela;

		$fecha_ini_m = new DateTime($division_inasistencia->mes . '01');
		$fecha_ini_p = new DateTime($division_inasistencia->fecha_inicio);
		$fecha_fin_m = new DateTime($division_inasistencia->mes . '01 +1 month');
		$fecha_fin_p = new DateTime($division_inasistencia->fecha_fin . ' +1 day');
		$fecha_ini = max(array($fecha_ini_m, $fecha_ini_p));
		$fecha_fin = min(array($fecha_fin_m, $fecha_fin_p));
		$data['division'] = $division;
		$data['mes'] = $division_inasistencia->mes;
		$data['fecha_ini'] = $fecha_ini;
		$data['fecha_fin'] = (new DateTime($fecha_fin->format('Y-m-d') . ' -1 day'));
		$data['ciclo_lectivo'] = $division_inasistencia->ciclo_lectivo;
		$this->division_inasistencia_model->fields['dias']['readonly'] = TRUE;
		$data['fields'] = $this->build_fields($this->division_inasistencia_model->fields, $division_inasistencia);
		$data['dias'] = $division_inasistencia->dias;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar mes de registro de Asistencias';
		$this->load->view('division_inasistencia/division_inasistencia_modal_eliminar_mes', $data);
	}

	public function resumen_mensual_imprimir_pdf($id = NULL, $orden = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$division_inasistencia = $this->division_inasistencia_model->get_one($id);
		if (empty($division_inasistencia)) {
			show_error('No se encontró el registro de la inasistencia de la división', 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($division_inasistencia->division_id);
		if (empty($division)) {
			show_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		$alumnos_resumen_mensual = $this->alumno_inasistencia_model->get_alumnos_resumen($division_inasistencia->id, $orden);

		$division->escuela = "Esc. $escuela->nombre_largo";

		$inasistencias_db = $this->alumno_inasistencia_model->get_alumnos_resumen_diario($division_inasistencia->id, $orden);
		$inasistencias_acumulada_db = $this->alumno_inasistencia_model->get_alumnos_acumulada($division->id, $division_inasistencia->mes, $division_inasistencia->periodo);
		$inasistencias_db_varones = array();
		$inasistencias_db_mujeres = array();
		foreach ($inasistencias_db as $inasistencia) {
			if ($inasistencia->sexo_id === '1') {
				$inasistencias_db_varones[] = $inasistencia;
			} else {
				$inasistencias_db_mujeres[] = $inasistencia;
			}
		}

		$inasistencias_resumen = array();
		$inasistencias_resumen_sexo = array();
		$inasistencias_acumulada_resumen = array();
		$inasistencias_mes = array();

		if (!empty($inasistencias_acumulada_db)) {
			foreach ($inasistencias_acumulada_db as $inasistencia_acumulada_db) {
				$inasistencias_acumulada_resumen[$inasistencia_acumulada_db->alumno_division_id][$inasistencia_acumulada_db->justificada] = $inasistencia_acumulada_db->falta;
			}
		}

		if (!empty($inasistencias_db)) {
			foreach ($inasistencias_db as $inasistencia_db) {
				if (!isset($inasistencias_resumen[$inasistencia_db->alumno_division_id][$inasistencia_db->justificada])) {
					$inasistencias_resumen[$inasistencia_db->alumno_division_id][$inasistencia_db->justificada] = 0;
				}
				$inasistencias_resumen[$inasistencia_db->alumno_division_id][$inasistencia_db->justificada] += $inasistencia_db->falta;
				if (!isset($inasistencias_resumen_sexo[$inasistencia_db->sexo_id][$inasistencia_db->justificada])) {
					$inasistencias_resumen_sexo[$inasistencia_db->sexo_id][$inasistencia_db->justificada] = 0;
				}
				$inasistencias_resumen_sexo[$inasistencia_db->sexo_id][$inasistencia_db->justificada] += $inasistencia_db->falta;
				if ($inasistencia_db->inasistencia_tipo_id === "2") {
					if ($inasistencia_db->justificada === "Si") {
						$inasistencia_db->falta = "<span class='label bg-green'>A</span>";
					} else {
						$inasistencia_db->falta = "<span class='label bg-red'>A</span>";
					}
				} elseif ($inasistencia_db->inasistencia_tipo_id === "3") {
					if ($inasistencia_db->justificada === "Si") {
						$inasistencia_db->falta = "<span class='label bg-green'>T</span>";
					} else {
						$inasistencia_db->falta = "<span class='label bg-red'>T</span>";
					}
				} elseif ($inasistencia_db->inasistencia_tipo_id === "4") {
					if ($inasistencia_db->justificada === "Si") {
						$inasistencia_db->falta = "<span class='label bg-green'>R</span>";
					} else {
						$inasistencia_db->falta = "<span class='label bg-red'>R</span>";
					}
				} elseif ($inasistencia_db->inasistencia_tipo_id === "5") {
					$inasistencia_db->falta = "<span class='fa fa-fw fa-minus'></span>";
				} elseif ($inasistencia_db->inasistencia_tipo_id === "6") {
					$inasistencia_db->falta = "<span class='fa fa-fw fa-minus'></span>";
				} elseif ($inasistencia_db->inasistencia_tipo_id === "7") {
					$inasistencia_db->falta = "<span class='label bg-blue'>A</span>";
				} elseif ($inasistencia_db->inasistencia_tipo_id === "8") {
					$inasistencia_db->falta = "<span class='label bg-red'>P</span>";
				} elseif ($inasistencia_db->inasistencia_tipo_id === "9") {
					$inasistencia_db->falta = "<span class='label' style='border:1px solid gray;'>P</span>";
				}
				$inasistencias_mes[$inasistencia_db->alumno_division_id][$inasistencia_db->fecha][$inasistencia_db->contraturno] = $inasistencia_db;
			}
		}

		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['periodos'] = $this->calendario_model->get_periodos($division->calendario_id, $division_inasistencia->ciclo_lectivo);
		$data['inasistencias_mes'] = $inasistencias_mes;
		$data['inasistencias_resumen'] = $inasistencias_resumen;
		$data['inasistencias_resumen_sexo'] = $inasistencias_resumen_sexo;
		$data['inasistencias_acumulada_resumen'] = $inasistencias_acumulada_resumen;
		$data['inasistencias'] = $this->division_inasistencia_model->get_registros($division->id, $division_inasistencia->ciclo_lectivo);
		$data['dias_mes_abierto'] = $this->division_inasistencia_dia_model->get(array('division_inasistencia_id' => $division_inasistencia->id));
		$data['mes'] = $division_inasistencia->mes;
		$data['ciclo_lectivo'] = $division_inasistencia->ciclo_lectivo;
		$data['division_inasistencia'] = $division_inasistencia;
		$data['dias'] = $this->division_inasistencia_dia_model->get_dias($division_inasistencia->id);
		$alumnos = $this->alumno_inasistencia_model->get_alumnos_division($division_inasistencia->id, $division_inasistencia->mes, $orden);
		$data['alumnos'] = $alumnos;
		$data['alumnos_resumen_mensual'] = $alumnos_resumen_mensual;
		$fecha_actual = date('d/m/Y');

		$content = $this->load->view('division_inasistencia/resumen_mensual_pdf', $data, TRUE);
		$this->load->helper('mpdf');
		$watermark = '';
		exportarMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Planilla de Preinscripción', 'Resumen de Inasistencias : Alumnos de ' . $division->curso . $division->division, '|{PAGENO} de {nb}|', 'LEGAL-L', $watermark, 'I', FALSE, FALSE);
	}

	public function resumen_diario_imprimir_pdf($id = NULL, $orden = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$division_inasistencia = $this->division_inasistencia_model->get_one($id);
		if (empty($division_inasistencia)) {
			show_error('No se encontró el registro de la inasistencia de la división', 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($division_inasistencia->division_id);
		if (empty($division)) {
			show_error('No se encontró el registro de la división', 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		$alumnos_resumen_mensual = $this->alumno_inasistencia_model->get_alumnos_resumen($division_inasistencia->id, $orden);

		$division->escuela = "Esc. $escuela->nombre_largo";

		$inasistencias_db = $this->alumno_inasistencia_model->get_alumnos_resumen_diario($division_inasistencia->id, $orden);
		$inasistencias_acumulada_db = $this->alumno_inasistencia_model->get_alumnos_acumulada($division->id, $division_inasistencia->mes, $division_inasistencia->periodo);
		$inasistencias_db_varones = array();
		$inasistencias_db_mujeres = array();
		$inasistencias_resumen = array();
		$inasistencias_resumen_sexo = array();
		$inasistencias_acumulada_resumen = array();
		$inasistencias_mes = array();
		foreach ($inasistencias_db as $inasistencia) {
			if ($inasistencia->sexo_id === '1') {
				$inasistencias_db_varones[] = $inasistencia;
			} else {
				$inasistencias_db_mujeres[] = $inasistencia;
			}
		}

		if (!empty($inasistencias_acumulada_db)) {
			foreach ($inasistencias_acumulada_db as $inasistencia_acumulada_db) {
				$inasistencias_acumulada_resumen[$inasistencia_acumulada_db->alumno_division_id][$inasistencia_acumulada_db->justificada] = $inasistencia_acumulada_db->falta;
			}
		}

		if (!empty($inasistencias_db)) {
			foreach ($inasistencias_db as $inasistencia_db) {
				if (!isset($inasistencias_resumen[$inasistencia_db->alumno_division_id][$inasistencia_db->justificada])) {
					$inasistencias_resumen[$inasistencia_db->alumno_division_id][$inasistencia_db->justificada] = 0;
				}
				$inasistencias_resumen[$inasistencia_db->alumno_division_id][$inasistencia_db->justificada] += $inasistencia_db->falta;
				if (!isset($inasistencias_resumen_sexo[$inasistencia_db->sexo_id][$inasistencia_db->justificada])) {
					$inasistencias_resumen_sexo[$inasistencia_db->sexo_id][$inasistencia_db->justificada] = 0;
				}
				$inasistencias_resumen_sexo[$inasistencia_db->sexo_id][$inasistencia_db->justificada] += $inasistencia_db->falta;
				if ($inasistencia_db->inasistencia_tipo_id === "2") {
					if ($inasistencia_db->justificada === "Si") {
						$inasistencia_db->falta = "A<sub>j</sub>";
					} else {
						$inasistencia_db->falta = "A<sub>i</sub>";
					}
				} elseif ($inasistencia_db->inasistencia_tipo_id === "3") {
					if ($inasistencia_db->justificada === "Si") {
						$inasistencia_db->falta = "T<sub>j</sub>";
					} else {
						$inasistencia_db->falta = "T<sub>i</sub>";
					}
				} elseif ($inasistencia_db->inasistencia_tipo_id === "4") {
					if ($inasistencia_db->justificada === "Si") {
						$inasistencia_db->falta = "R<sub>j</sub>";
					} else {
						$inasistencia_db->falta = "R<sub>i</sub>";
					}
				} elseif ($inasistencia_db->inasistencia_tipo_id === "5") {
					$inasistencia_db->falta = "<strong>-</strong>";
				} elseif ($inasistencia_db->inasistencia_tipo_id === "6") {
					$inasistencia_db->falta = "<strong>-</strong>";
				} elseif ($inasistencia_db->inasistencia_tipo_id === "7") {
					$inasistencia_db->falta = "A<sub>nc</sub>";
				} elseif ($inasistencia_db->inasistencia_tipo_id === "8") {
					$inasistencia_db->falta = "P<sub>a</sub>";
				} elseif ($inasistencia_db->inasistencia_tipo_id === "9") {
					$inasistencia_db->falta = "P";
				}
				$inasistencias_mes[$inasistencia_db->alumno_division_id][$inasistencia_db->fecha][$inasistencia_db->contraturno] = $inasistencia_db;
			}
		}

		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['periodos'] = $this->calendario_model->get_periodos($division->calendario_id, $division_inasistencia->ciclo_lectivo);
		$data['inasistencias_mes'] = $inasistencias_mes;
		$data['inasistencias_resumen'] = $inasistencias_resumen;
		$data['inasistencias_resumen_sexo'] = $inasistencias_resumen_sexo;
		$data['inasistencias_acumulada_resumen'] = $inasistencias_acumulada_resumen;
		$data['inasistencias'] = $this->division_inasistencia_model->get_registros($division->id, $division_inasistencia->ciclo_lectivo);
		$data['dias_mes_abierto'] = $this->division_inasistencia_dia_model->get(array('division_inasistencia_id' => $division_inasistencia->id));
		$data['mes'] = $division_inasistencia->mes;
		$data['ciclo_lectivo'] = $division_inasistencia->ciclo_lectivo;
		$data['division_inasistencia'] = $division_inasistencia;
		$data['dias'] = $this->division_inasistencia_dia_model->get_dias($division_inasistencia->id);
		$alumnos = $this->alumno_inasistencia_model->get_alumnos_division($division_inasistencia->id, $division_inasistencia->mes, $orden);
		$data['alumnos'] = $alumnos;
		$data['alumnos_resumen_mensual'] = $alumnos_resumen_mensual;
		$fecha_actual = date('d/m/Y');

		$content = $this->load->view('division_inasistencia/resumen_diario_pdf', $data, TRUE);
		$this->load->helper('mpdf');
		$watermark = '';
		exportMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Planilla de Preinscripción', 'Resumen de Inasistencias : Alumnos de ' . $division->curso . $division->division, '|{PAGENO} de {nb}|', 'LEGAL-L', $watermark, 'I', FALSE, FALSE);
	}
}
/* End of file Division_inasistencia.php */
/* Location: ./application/controllers/Division_inasistencia.php */