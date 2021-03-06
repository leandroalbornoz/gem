<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alumno extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->load->model('alumno_model');
		$this->load->model('persona_model');
		//$this->roles_permitidos = array(ROL_ADMIN, ROL_USI);
		$this->roles_admin = array(ROL_ADMIN, ROL_USI);
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE_CURSADA));
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_GRUPO_ESCUELA_CONSULTA, ROL_REGIONAL, ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'menu/alumno';
	}

	public function listar($escuela_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || ($ciclo_lectivo != NULL && !ctype_digit($ciclo_lectivo))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("alumno/listar/$escuela_id/$ciclo_lectivo", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->load->model('abono/abono_alumno_model');
		$escuela_mes = $this->abono_alumno_model->get_abonos_escuela($escuela->id);
		$escuela_mes->ames = date('Ym');
		if (empty($escuela_mes)) {
			$escuela_mes = 1;
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Alumno', 'data' => 'persona', 'width' => 25, 'class' => 'text-sm'),
				array('label' => 'Sexo', 'data' => 'sexo', 'width' => 5, 'class' => 'dt-body-center'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Div', 'data' => 'division', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Condición', 'data' => 'condicion', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'short_date', 'width' => 10),
				array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'short_date', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'alumno_table',
			'source_url' => "alumno/listar_data/$escuela_id/$ciclo_lectivo",
			'order' => array(array(5, 'asc'), array(2, 'asc'), array(3, 'asc'), array(1, 'asc')),
			'responsive' => FALSE,
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['escuela_mes'] = $escuela_mes;
		$data['escuela'] = $escuela;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Alumnos';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('alumno/alumno_listar', $data);
	}

	public function listar_data($escuela_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->datatables
			->select("alumno_division.id,COALESCE(CONCAT(CASE WHEN sexo.id=1 THEN 'M' WHEN sexo.id=2 THEN 'F' ELSE '' END), ' ') as sexo, alumno.observaciones, alumno_division.fecha_desde, alumno_division.fecha_hasta, CONCAT(documento_tipo.descripcion_corta,' ', persona.documento) as documento, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, CASE curso.grado_multiple WHEN 'Si' THEN CONCAT(division.division, ' (', COALESCE(c_gm.descripcion, ''), ')') ELSE division.division END as division, curso.descripcion as curso, division.escuela_id, alumno_division.condicion as condicion")
			->unset_column('id')
			->from('alumno')
			->join('persona', 'persona.id = alumno.persona_id', 'left')
			->join('sexo', 'sexo.id = persona.sexo_id', 'left')
			->join('alumno_division', 'alumno_division.alumno_id = alumno.id', 'left')
			->join('division', 'division.id = alumno_division.division_id', 'left')
			->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id', 'left')
			->join('curso', 'division.curso_id = curso.id', 'left')
			->join('curso c_gm', 'alumno_division.curso_id = c_gm.id', 'left')
			->where('division.escuela_id', $escuela_id)
			->where('ciclo_lectivo', $ciclo_lectivo)
			->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="alumno/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item btn-warning" href="alumno/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '</ul></div>', 'id,escuela_id');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="alumno/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item btn-warning" href="alumno/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '</ul></div>', 'id,escuela_id');
		} else {
			$this->datatables->add_column('edit', '<div class="dt-body-center" role="group">'
				. '<a class="btn btn-xs btn-default" href="alumno/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '</ul></div>', 'id,escuela_id');
		}

		echo $this->datatables->generate();
	}

	public function trayectorias($escuela_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || ($ciclo_lectivo != NULL && !ctype_digit($ciclo_lectivo))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("alumno/trayectorias/$escuela_id/$ciclo_lectivo", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$tableData = array(
			'columns' => array(
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Sexo', 'data' => 'sexo', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Alumno', 'data' => 'persona', 'width' => 35, 'class' => 'text-sm'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 5, 'class' => 'dt-body-center'),
				array('label' => 'Div', 'data' => 'division', 'width' => 15, 'class' => 'dt-body-center'),
				array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'short_date', 'width' => 10),
				array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'short_date', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'alumno_table',
			'source_url' => "alumno/trayectorias_data/$escuela_id/$ciclo_lectivo",
			'order' => array(array(3, 'asc'), array(4, 'asc'), array(1, 'desc'), array(2, 'asc')),
			'responsive' => FALSE,
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['escuela'] = $escuela;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Alumnos';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('alumno/alumno_trayectorias', $data);
	}

	public function trayectorias_data($escuela_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->datatables
			->select("alumno_division.id, alumno.persona_id, alumno.observaciones, alumno_division.fecha_desde, alumno_division.fecha_hasta, CONCAT(COALESCE(documento_tipo.descripcion_corta,''),' ',COALESCE(persona.documento,' ')) as documento, persona.documento_tipo_id, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, CASE curso.grado_multiple WHEN 'Si' THEN CONCAT(division.division, ' (', COALESCE(c_gm.descripcion, ''), ')') ELSE division.division END as division, curso.descripcion as curso, division.escuela_id, curso.grado_multiple, LEFT(sexo.descripcion,1) as sexo")
			->unset_column('id')
			->from('alumno')
			->join('persona', 'persona.id = alumno.persona_id')
			->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id')
			->join('sexo', 'persona.sexo_id = sexo.id', 'left')
			->join('alumno_division', 'alumno_division.alumno_id = alumno.id')
			->join('division', 'division.id = alumno_division.division_id')
			->join('curso', 'division.curso_id = curso.id')
			->join('curso c_gm', 'alumno_division.curso_id = c_gm.id', 'left')
			->where('division.escuela_id', $escuela_id)
			->where('ciclo_lectivo', $ciclo_lectivo);
		if (in_array($this->rol->codigo, $this->roles_admin)) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="alumno/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item btn-warning" href="alumno/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '<li><a class="dropdown-item btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_division/modal_inasistencia_alumno/$1" title="Ver inasistencias"><i class="fa fa-clock-o"></i> Ver inasistencias</a></li>'
				. '<li><a class="dropdown-item btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_division/modal_editar_alumno_division/$1/2" title="Editar trayectoria"><i class="fa fa-edit"></i> Editar trayectoria</a></li>'
				. '<li><a class="dropdown-item btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_division/modal_eliminar_alumno_division/$1/2" title="Eliminar trayectoria"><i class="fa fa-remove"></i> Eliminar trayectoria</a></li>'
				. '</ul></div>', 'id,escuela_id');
		} elseif ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="alumno/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item btn-warning" href="alumno/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '<li><a class="dropdown-item btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_division/modal_inasistencia_alumno/$1" title="Ver inasistencias"><i class="fa fa-clock-o"></i> Ver inasistencias</a></li>'
				. '</ul></div>', 'id,escuela_id');
		} else {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="alumno/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_division/modal_inasistencia_alumno/$1" title="Ver inasistencias"><i class="fa fa-clock-o"></i> Ver inasistencias</a></li>'
				. '</ul></div>', 'id,escuela_id');
		}

		echo $this->datatables->generate();
	}

	public function ver($alumno_division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_division_model');
		$this->load->model('division_model');
		$this->load->model('alumno_apoyo_especial_model');
		$this->load->model('alumno_derivacion_model');

		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			show_error('No se encontró el registro del alumno', 500, 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($alumno_division->division_id);
		if (empty($alumno_division->ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $alumno_division->ciclo_lectivo))) {
			$alumno_division->ciclo_lectivo = date('Y');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_model');
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		if (empty($alumno)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$this->load->model('alumno_division_model');
		$trayectoria = $this->alumno_division_model->get_trayectoria_alumno($alumno->id);
		if (ENVIRONMENT !== 'production') {
			$this->load->model('abono/abono_alumno_model');
			$abonos = $this->abono_alumno_model->getAbonosByAlumno($alumno->id, $escuela->id);
			$data['abonos'] = $abonos;
		}
		$apoyo_especial_ver = $this->alumno_apoyo_especial_model->get_alumno_apoyo($alumno->id);
		$derivacion_ver = $this->alumno_derivacion_model->get_alumno_derivacion($alumno->id);

		$this->load->model('familia_model');
		$parientes = $this->familia_model->get_familiares($alumno->persona_id);
		$this->load->model('caracteristica_alumno_model');
		$fields_tipos = $this->caracteristica_alumno_model->get_fields($alumno->id, TRUE);
		foreach ($fields_tipos as $tipo => $fields) {
			$data['fields_tipos'][$tipo] = $this->build_fields($fields);
		}
		ksort($data['fields_tipos']);

		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['apoyo_especial'] = $apoyo_especial_ver;
		$data['derivacion'] = $derivacion_ver;
		$data['ciclo_lectivo'] = $alumno_division->ciclo_lectivo;
		$data['division'] = "$division->curso $alumno_division->division";
		$data['division_id'] = $alumno_division->division_id;
		$data['fields'] = $this->build_fields($this->alumno_model->fields, $alumno, TRUE);
		$data['trayectoria'] = $trayectoria;
		$data['escuela'] = $escuela;
		$data['parientes'] = $parientes;
		$data['alumno'] = $alumno;
		$data['alumno_division_id'] = $alumno_division_id;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver alumno';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('alumno/alumno_abm', $data);
	}

	public function editar($alumno_division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_division_model');
		$this->load->model('division_model');
		$this->load->model('alumno_apoyo_especial_model');
		$this->load->model('alumno_derivacion_model');

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
				array('persona', 'alumno.persona_id=persona.id', 'left', array('cuil', 'documento_tipo_id', 'documento', 'apellido', 'nombre', 'calle', 'calle_numero', 'departamento', 'piso', 'barrio', 'manzana', 'casa', 'localidad_id', 'codigo_postal', 'sexo_id', 'estado_civil_id', 'nivel_estudio_id', 'ocupacion_id', 'telefono_fijo', 'telefono_movil', 'prestadora_id', 'fecha_nacimiento', 'fecha_defuncion', 'obra_social_id', 'contacto_id', 'grupo_sanguineo_id', 'depto_nacimiento_id', 'lugar_traslado_emergencia', 'nacionalidad_id', 'email'))
		)));
		if (empty($alumno)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}

		$this->load->model('documento_tipo_model');
		$this->load->model('estado_civil_model');
		$this->load->model('grupo_sanguineo_model');
		$this->load->model('localidad_model');
		$this->load->model('nacionalidad_model');
		$this->load->model('nivel_estudio_model');
		$this->load->model('obra_social_model');
		$this->load->model('ocupacion_model');
		$this->load->model('prestadora_model');
		$this->load->model('sexo_model');

		$this->array_documento_tipo_control = $array_documento_tipo = $this->get_array('documento_tipo', 'descripcion_corta', 'id', null, array('' => '-- Seleccionar --'));
		$this->array_estado_civil_control = $array_estado_civil = $this->get_array('estado_civil', 'descripcion', 'id', null, array('' => '-- Seleccionar estado civil --'));
		$this->array_grupo_sanguineo_control = $array_grupo_sanguineo = $this->get_array('grupo_sanguineo', 'descripcion', 'id', null, array('' => '-- Seleccionar grupo sanguíneo --'));
		$this->array_localidad_control = $array_localidad = $this->get_array('localidad', 'localidad', 'id', array(
			'select' => array('localidad.id', "CONCAT(departamento.descripcion, ' - ', localidad.descripcion) as localidad"),
			'join' => array(array('departamento', 'departamento.id = localidad.departamento_id')),
			'sort_by' => 'departamento.descripcion, localidad.descripcion'
			), array('' => '-- Seleccionar localidad --'));
		$this->array_nacionalidad_control = $array_nacionalidad = $this->get_array('nacionalidad', 'descripcion', 'id', null, array('' => '-- Seleccionar nacionalidad --'));
		$this->array_nivel_estudio_control = $array_nivel_estudio = $this->get_array('nivel_estudio', 'descripcion', 'id', null, array('' => '-- Seleccionar nivel de estudios --'));
		$this->array_obra_social_control = $array_obra_social = $this->get_array('obra_social', 'obra_social', 'id', array(
			'select' => array('obra_social.id', "CASE WHEN obra_social.descripcion_corta IS NULL OR obra_social.descripcion_corta='' THEN obra_social.descripcion ELSE obra_social.descripcion_corta END as obra_social")
			), array('' => '-- Seleccionar obra social --'));
		$this->array_ocupacion_control = $array_ocupacion = $this->get_array('ocupacion', 'descripcion', 'id', null, array('' => '-- Seleccionar ocupación --'));
		$this->array_prestadora_control = $array_prestadora = $this->get_array('prestadora', 'descripcion', 'id', null, array('' => '-- Seleccionar prestadora --'));
		$this->array_sexo_control = $array_sexo = $this->get_array('sexo', 'descripcion', 'id', null, array('' => '-- Seleccionar sexo --'));
		unset($this->persona_model->fields['depto_nacimiento']);

		$this->set_model_validation_rules($this->alumno_model);
		$this->set_model_validation_rules($this->persona_model);
		$errors = '';
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->persona_model->update(array(
					'id' => $alumno->persona_id,
					'cuil' => $this->input->post('cuil'),
					'documento_tipo_id' => $this->input->post('documento_tipo'),
					'documento' => $this->input->post('documento'),
					'apellido' => $this->input->post('apellido'),
					'nombre' => $this->input->post('nombre'),
					'calle' => $this->input->post('calle'),
					'calle_numero' => $this->input->post('calle_numero'),
					'departamento' => $this->input->post('departamento'),
					'piso' => $this->input->post('piso'),
					'barrio' => $this->input->post('barrio'),
					'manzana' => $this->input->post('manzana'),
					'casa' => $this->input->post('casa'),
					'localidad_id' => $this->input->post('localidad'),
					'codigo_postal' => $this->input->post('codigo_postal'),
					'sexo_id' => $this->input->post('sexo'),
					'estado_civil_id' => $this->input->post('estado_civil'),
					'nivel_estudio_id' => $this->input->post('nivel_estudio'),
					'ocupacion_id' => $this->input->post('ocupacion'),
					'telefono_fijo' => $this->input->post('telefono_fijo'),
					'telefono_movil' => $this->input->post('telefono_movil'),
					'email' => $this->input->post('email'),
					'prestadora_id' => $this->input->post('prestadora'),
					'fecha_nacimiento' => $this->get_date_sql('fecha_nacimiento'),
					'fecha_defuncion' => $this->get_date_sql('fecha_defuncion'),
					'obra_social_id' => $this->input->post('obra_social'),
					'grupo_sanguineo_id' => $this->input->post('grupo_sanguineo'),
					'lugar_traslado_emergencia' => $this->input->post('lugar_traslado_emergencia'),
					'nacionalidad_id' => $this->input->post('nacionalidad')
					), FALSE);

				$trans_ok &= $this->alumno_model->update(array(
					'id' => $this->input->post('id'),
					'email_contacto' => $this->input->post('email_contacto'),
					'observaciones' => $this->input->post('observaciones'),
					), FALSE);
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->alumno_model->get_msg());
					redirect("alumno/ver/$alumno_division_id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->alumno_model->get_error())
						$errors .= '<br>' . $this->alumno_model->get_error();
					if ($this->persona_model->get_error())
						$errors .= '<br>' . $this->persona_model->get_error();
				}
			}else {
				$errors = 'Ocurrió un error al intentar actualizar.';
				if ($this->alumno_model->get_error())
					$errors .= '<br>' . $this->alumno_model->get_error();
				if ($this->persona_model->get_error())
					$errors .= '<br>' . $this->persona_model->get_error();
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : (!empty($errors) ? $errors : $this->session->flashdata('error')));

		$this->alumno_model->fields['documento_tipo']['array'] = $array_documento_tipo;
		$this->alumno_model->fields['estado_civil']['array'] = $array_estado_civil;
		$this->alumno_model->fields['grupo_sanguineo']['array'] = $array_grupo_sanguineo;
		$this->alumno_model->fields['localidad']['array'] = $array_localidad;
		$this->alumno_model->fields['nacionalidad']['array'] = $array_nacionalidad;
		$this->alumno_model->fields['nivel_estudio']['array'] = $array_nivel_estudio;
		$this->alumno_model->fields['obra_social']['array'] = $array_obra_social;
		$this->alumno_model->fields['ocupacion']['array'] = $array_ocupacion;
		$this->alumno_model->fields['prestadora']['array'] = $array_prestadora;
		$this->alumno_model->fields['sexo']['array'] = $array_sexo;
		$apoyo_especial_editar = $this->alumno_apoyo_especial_model->get_alumno_apoyo($alumno->id);
		$derivacion_editar = $this->alumno_derivacion_model->get_alumno_derivacion($alumno->id);

		$data['derivacion'] = $derivacion_editar;
		$data['apoyo_especial'] = $apoyo_especial_editar;
		$data['fields'] = $this->build_fields($this->alumno_model->fields, $alumno);
		$data['message'] = $this->session->flashdata('message');

		$this->load->model('familia_model');
		$parientes = $this->familia_model->get_familiares($alumno->persona_id);

		$data['pariente_id'] = $this->session->flashdata('pariente_id');
		$data['ciclo_lectivo'] = $alumno_division->ciclo_lectivo;
		$data['division'] = "$division->curso $alumno_division->division";
		$data['division_id'] = $alumno_division->division_id;
		$data['escuela'] = $escuela;
		$data['parientes'] = $parientes;
		$data['alumno'] = $alumno;
		$data['alumno_division_id'] = $alumno_division_id;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar alumno';
		$this->load_template('alumno/alumno_abm', $data);
	}

	public function caracteristicas($alumno_division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_division_model');
		$this->load->model('division_model');

		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		$division = $this->division_model->get_one($alumno_division->division_id);

		if (empty($alumno_division->ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $alumno_division->ciclo_lectivo))) {
			$alumno_division->ciclo_lectivo = date('Y');
		}

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_model');
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		if (empty($alumno)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}

		$this->load->model('caracteristica_alumno_model');
		$fields_caracteristicas = $this->caracteristica_alumno_model->get_fields($alumno->id, FALSE, TRUE);
		$fields_tipos = $fields_caracteristicas[0];
		$lista_caracteristicas = $fields_caracteristicas[1];
		ksort($fields_tipos);
		foreach ($fields_tipos as $fields) {
			foreach ($fields as $field_id => $field) {
				if (isset($field['input_type'])) {
					$this->{"array_{$field_id}_control"} = $field['array'];
				}
			}
			$this->set_model_validation_rules((object) array('fields' => $fields));
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$this->load->model('caracteristica_model');
				$this->load->model('caracteristica_valor_model');
				foreach ($this->input->post('caracteristicas') as $id_caracteristica => $valor_caracteristica) {
					if (!isset($lista_caracteristicas[$id_caracteristica]->caracteristica_alumno_id)) {
						if ($lista_caracteristicas[$id_caracteristica]->lista_valores === 'Si') {
							if (!empty($valor_caracteristica)) {
								$valor = $this->caracteristica_valor_model->get_valor($valor_caracteristica);
								if (!empty($valor)) {
									$trans_ok &= $this->caracteristica_alumno_model->create(array(
										'alumno_id' => $alumno->id,
										'caracteristica_id' => $id_caracteristica,
										'valor' => $valor->valor,
										'fecha_desde' => date('Y-m-d'),
										'caracteristica_valor_id' => $valor->id
										), FALSE);
								} else {
									$this->db->trans_rollback();
									show_error('No se encontró el registro a actualizar', 500, 'Registro no encontrado');
								}
							}
						} else {
							if (!empty($valor_caracteristica)) {
								$trans_ok &= $this->caracteristica_alumno_model->create(array(
									'alumno_id' => $alumno->id,
									'caracteristica_id' => $id_caracteristica,
									'fecha_desde' => date('Y-m-d'),
									'valor' => $valor_caracteristica
									), FALSE);
							}
						}
					} else {
						if ($lista_caracteristicas[$id_caracteristica]->lista_valores === 'Si') {
							if (!empty($valor_caracteristica)) {
								$valor = $this->caracteristica_valor_model->get_valor($valor_caracteristica);
								if (!empty($valor)) {
									if ($lista_caracteristicas[$id_caracteristica]->valor !== $valor->valor) {
										$trans_ok &= $this->caracteristica_alumno_model->update(array(
											'id' => $lista_caracteristicas[$id_caracteristica]->caracteristica_alumno_id,
											'fecha_hasta' => date('Y-m-d')
											), FALSE);
										$trans_ok &= $this->caracteristica_alumno_model->create(array(
											'alumno_id' => $alumno->id,
											'caracteristica_id' => $id_caracteristica,
											'fecha_desde' => date('Y-m-d'),
											'valor' => $valor->valor,
											'caracteristica_valor_id' => $valor->id
											), FALSE);
									}
								} else {
									$this->db->trans_rollback();
									show_error('No se encontró el registro a actualizar', 500, 'Registro no encontrado');
								}
							} else {
								if (!empty($lista_caracteristicas[$id_caracteristica]->valor)) {
									$trans_ok &= $this->caracteristica_alumno_model->update(array(
										'id' => $lista_caracteristicas[$id_caracteristica]->caracteristica_alumno_id,
										'fecha_hasta' => date('Y-m-d')
										), FALSE);
								}
							}
						} else {
							if ($lista_caracteristicas[$id_caracteristica]->valor != $valor_caracteristica) {
								$trans_ok &= $this->caracteristica_alumno_model->update(array(
									'id' => $lista_caracteristicas[$id_caracteristica]->caracteristica_alumno_id,
									'fecha_hasta' => date('Y-m-d')
									), FALSE);
								if (!empty($valor_caracteristica)) {//No insertar características vacías
									$trans_ok &= $this->caracteristica_alumno_model->create(array(
										'alumno_id' => $alumno->id,
										'caracteristica_id' => $id_caracteristica,
										'fecha_desde' => date('Y-m-d'),
										'valor' => $valor_caracteristica
										), FALSE);
								}
							}
						}
					}
					unset($lista_caracteristicas[$id_caracteristica]);
				}
				foreach ($lista_caracteristicas as $caracteristica) {
					if (isset($caracteristica->caracteristica_alumno_id)) {
						$trans_ok &= $this->caracteristica_alumno_model->update(array(
							'id' => $caracteristica->caracteristica_alumno_id,
							'fecha_hasta' => date('Y-m-d')
							), FALSE);
					}
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->caracteristica_alumno_model->get_msg());
					redirect("alumno/ver/$alumno_division_id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->caracteristica_alumno_model->get_error())
						$errors .= '<br>' . $this->caracteristica_alumno_model->get_error();
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->caracteristica_alumno_model->get_error() ? $this->caracteristica_alumno_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->alumno_model->fields, $alumno, TRUE);

		foreach ($fields_tipos as $tipo => $fields) {
			$data['fields_tipos'][$tipo] = $this->build_fields($fields);
		}

		$data['ciclo_lectivo'] = $alumno_division->ciclo_lectivo;
		$data['division'] = "$division->curso $alumno_division->division";
		$data['division_id'] = $alumno_division->division_id;
		$data['escuela'] = $escuela;
		$data['alumno'] = $alumno;
		$data['alumno_division_id'] = $alumno_division_id;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar características de escuela';
		$this->load_template('alumno/alumno_caracteristicas', $data);
	}

	private function agregar($escuela_id = NULL, $division = NULL) {
		//no se esta usando!
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->array_persona_control = $array_persona = $this->get_array('persona', 'cuil', 'id', null, array('' => '-- Seleccionar persona --'));
		$this->set_model_validation_rules($this->alumno_model);
		if ($this->form_validation->run() === TRUE) {
			$trans_ok = TRUE;
			$trans_ok &= $this->alumno_model->create(array(
				'persona_id' => $this->input->post('persona'),
				'observaciones' => $this->input->post('observaciones')));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->alumno_model->get_msg());
				redirect('alumno/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->alumno_model->get_error() ? $this->alumno_model->get_error() : $this->session->flashdata('error')));

		$this->alumno_model->fields['persona']['array'] = $array_persona;
		$data['fields'] = $this->build_fields($this->alumno_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = TITLE . ' - Agregar alumno';
		$this->load_template('alumno/alumno_abm', $data);
	}

	private function eliminar($id = NULL) {
		//no se esta usando!
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$alumno = $this->alumno_model->get_one($id);
		if (empty($alumno)) {
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}

		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->alumno_model->fields, $alumno, TRUE);


		$this->load->model('familia_model');
		$parientes = $this->familia_model->get_familiares($alumno->persona_id);

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->alumno_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->alumno_model->get_msg());
				redirect('alumno/listar', 'refresh');
			}
		}

		$this->load->model('caracteristica_alumno_model');
		$fields_tipos = $this->caracteristica_alumno_model->get_fields($alumno->id, TRUE);
		foreach ($fields_tipos as $tipo => $fields) {
			$data['fields_tipos'][$tipo] = $this->build_fields($fields);
		}

		$data['error'] = (validation_errors() ? validation_errors() : ($this->alumno_model->get_error() ? $this->alumno_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->alumno_model->fields, $alumno, TRUE);

		$data['alumno'] = $alumno;

		$data['parientes'] = $parientes;
		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = TITLE . ' - Eliminar alumno';
		$this->load_template('alumno/alumno_abm', $data);
	}

	public function cambiar_ciclo_lectivo($escuela_id, $ciclo_lectivo) {
		$model = new stdClass();
		$model->fields = array(
			'ciclo_lectivo' => array('label' => 'Ciclo Lectivo', 'type' => 'date', 'required' => TRUE)
		);
		$this->set_model_validation_rules($model);
		if ($this->form_validation->run() === TRUE) {
			$ciclo_lectivo = (new DateTime($this->get_date_sql('ciclo_lectivo')))->format('Y');
			$this->session->set_flashdata('message', 'Ciclo lectivo cambiado correctamente');
		} else {
			$this->session->set_flashdata('error', 'Error al cambiar ciclo lectivo');
		}
		redirect("alumno/listar/$escuela_id/$ciclo_lectivo", 'refresh');
	}

	public function ficha_psicopedagogica($alumno_division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_division_model');
		$this->load->model('division_model');

		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			show_error('No se encontró el registro del alumno', 500, 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($alumno_division->division_id);
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

		$this->load->model('alumno_model');
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		if (empty($alumno)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}

		$this->load->model('ficha_p_model');
		$ficha_p = $this->ficha_p_model->get(array('alumno_id' => $alumno->id));
		if (empty($ficha_p)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$this->load->model('caracteristica_ficha_p_model');
		$fields_caracteristicas = $this->caracteristica_ficha_p_model->get_fields($ficha_p[0]->id, FALSE, TRUE);
		$fields_tipos = $fields_caracteristicas[0];
		$lista_caracteristicas = $fields_caracteristicas[1];
		foreach ($fields_tipos as $fields) {
			foreach ($fields as $field_id => $field) {
				if (isset($field['input_type'])) {
					$this->{"array_{$field_id}_control"} = $field['array'];
				}
			}
			$this->set_model_validation_rules((object) array('fields' => $fields));
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->ficha_p_model->update(array(
					'id' => $ficha_p[0]->id,
					'informe' => $this->input->post('informe'),
					'situacion_familiar' => $this->input->post('situacion_familiar'),
					'actividad_laboral' => $this->input->post('actividad_laboral')
					), FALSE);
				$this->load->model('caracteristica_model');
				$this->load->model('caracteristica_valor_model');
				foreach ($this->input->post('caracteristicas') as $id_caracteristica => $valor_caracteristica) {
					if (!isset($lista_caracteristicas[$id_caracteristica]->caracteristica_alumno_id)) {
						if ($lista_caracteristicas[$id_caracteristica]->lista_valores === 'Si') {
							if (!empty($valor_caracteristica)) {
								$valor = $this->caracteristica_valor_model->get_valor($valor_caracteristica);
								if (!empty($valor)) {
									$trans_ok &= $this->caracteristica_ficha_p_model->create(array(
										'ficha_p_id' => $ficha_p[0]->id,
										'caracteristica_id' => $id_caracteristica,
										'valor' => $valor->valor,
										'fecha_desde' => date('Y-m-d'),
										'caracteristica_valor_id' => $valor->id
										), FALSE);
								} else {
									$this->db->trans_rollback();
									show_error('No se encontró el registro a actualizar', 500, 'Registro no encontrado');
								}
							}
						} else {
							if (!empty($valor_caracteristica)) {
								$trans_ok &= $this->caracteristica_ficha_p_model->create(array(
									'ficha_p_id' => $ficha_p[0]->id,
									'caracteristica_id' => $id_caracteristica,
									'fecha_desde' => date('Y-m-d'),
									'valor' => $valor_caracteristica
									), FALSE);
							}
						}
					} else {
						if ($lista_caracteristicas[$id_caracteristica]->lista_valores === 'Si') {
							if (!empty($valor_caracteristica)) {
								$valor = $this->caracteristica_valor_model->get_valor($valor_caracteristica);
								if (!empty($valor)) {
									if ($lista_caracteristicas[$id_caracteristica]->valor !== $valor->valor) {
										$trans_ok &= $this->caracteristica_ficha_p_model->update(array(
											'id' => $lista_caracteristicas[$id_caracteristica]->caracteristica_alumno_id,
											'fecha_hasta' => date('Y-m-d')
											), FALSE);
										$trans_ok &= $this->caracteristica_ficha_p_model->create(array(
											'ficha_p_id' => $ficha_p[0]->id,
											'caracteristica_id' => $id_caracteristica,
											'fecha_desde' => date('Y-m-d'),
											'valor' => $valor->valor,
											'caracteristica_valor_id' => $valor->id
											), FALSE);
									}
								} else {
									$this->db->trans_rollback();
									show_error('No se encontró el registro a actualizar', 500, 'Registro no encontrado');
								}
							} else {
								if (!empty($lista_caracteristicas[$id_caracteristica]->valor)) {
									$trans_ok &= $this->caracteristica_ficha_p_model->update(array(
										'id' => $lista_caracteristicas[$id_caracteristica]->caracteristica_alumno_id,
										'fecha_hasta' => date('Y-m-d')
										), FALSE);
								}
							}
						} else {
							if ($lista_caracteristicas[$id_caracteristica]->valor != $valor_caracteristica) {
								$trans_ok &= $this->caracteristica_ficha_p_model->update(array(
									'id' => $lista_caracteristicas[$id_caracteristica]->caracteristica_alumno_id,
									'fecha_hasta' => date('Y-m-d')
									), FALSE);
								if (!empty($valor_caracteristica)) {//No insertar características vacías
									$trans_ok &= $this->caracteristica_ficha_p_model->create(array(
										'ficha_p_id' => $ficha_p[0]->id,
										'caracteristica_id' => $id_caracteristica,
										'fecha_desde' => date('Y-m-d'),
										'valor' => $valor_caracteristica
										), FALSE);
								}
							}
						}
					}
					unset($lista_caracteristicas[$id_caracteristica]);
				}
				foreach ($lista_caracteristicas as $caracteristica) {
					if (isset($caracteristica->caracteristica_alumno_id)) {
						$trans_ok &= $this->caracteristica_ficha_p_model->update(array(
							'id' => $caracteristica->caracteristica_alumno_id,
							'fecha_hasta' => date('Y-m-d')
							), FALSE);
					}
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->caracteristica_ficha_p_model->get_msg());
					redirect("alumno/ficha_psicopedagogica_ver/$alumno_division_id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->caracteristica_ficha_p_model->get_error())
						$errors .= '<br>' . $this->caracteristica_ficha_p_model->get_error();
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->caracteristica_ficha_p_model->get_error() ? $this->caracteristica_ficha_p_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->alumno_model->fields, $alumno, TRUE);

		foreach ($fields_tipos as $tipo => $fields) {
			$data['fields_tipos'][$tipo] = $this->build_fields($fields);
		}

		$data['fields_ficha'] = $this->build_fields($this->ficha_p_model->fields, $ficha_p[0]);
		$data['ciclo_lectivo'] = $alumno_division->ciclo_lectivo;
		$data['division'] = "$division->curso $alumno_division->division";
		$data['division_id'] = $alumno_division->division_id;
		$data['ficha_p_ex'] = 1;
		$data['escuela'] = $escuela;
		$data['alumno'] = $alumno;
		$data['alumno_division_id'] = $alumno_division_id;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar características de escuela';
		$this->load_template('alumno/alumno_ficha_psicopedagogica', $data);
	}

	public function ficha_psicopedagogica_ver($alumno_division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_division_model');
		$this->load->model('division_model');

		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			show_error('No se encontró el registro del alumno', 500, 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($alumno_division->division_id);

		if (empty($alumno_division->ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $alumno_division->ciclo_lectivo))) {
			$alumno_division->ciclo_lectivo = date('Y');
		}

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_model');
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		if (empty($alumno)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}

		$this->load->model('ficha_p_model');
		$ficha_p = $this->ficha_p_model->get(array('alumno_id' => $alumno->id));
		if (!empty($ficha_p)) {

			$this->load->model('caracteristica_ficha_p_model');
			$fields_caracteristicas = $this->caracteristica_ficha_p_model->get_fields($ficha_p[0]->id, TRUE);
			$fields_tipos = $fields_caracteristicas;
			$data['fields'] = $this->build_fields($this->alumno_model->fields, $alumno, TRUE);

			foreach ($fields_tipos as $tipo => $fields) {
				$data['fields_tipos'][$tipo] = $this->build_fields($fields);
			}
			$data['ficha_p_ex'] = 1;
		} else {
			$data['fields'] = $this->build_fields($this->alumno_model->fields, $alumno, TRUE);
			$data['ficha_p_ex'] = NULL;
		}

		$data['fields_ficha'] = $this->build_fields($this->ficha_p_model->fields, $ficha_p[0], TRUE);
		$data['ciclo_lectivo'] = $alumno_division->ciclo_lectivo;
		$data['division'] = "$division->curso $alumno_division->division";
		$data['division_id'] = $alumno_division->division_id;
		$data['escuela'] = $escuela;
		$data['alumno'] = $alumno;
		$data['alumno_division_id'] = $alumno_division_id;
		$data['txt_btn'] = '';
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar características de escuela';
		$this->load_template('alumno/alumno_ficha_psicopedagogica', $data);
	}

	public function ficha_psicopedagogica_crear($alumno_division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_division_model');
		$this->load->model('division_model');

		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		$division = $this->division_model->get_one($alumno_division->division_id);

		if (empty($alumno_division->ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $alumno_division->ciclo_lectivo))) {
			$alumno_division->ciclo_lectivo = date('Y');
		}

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_model');
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		if (empty($alumno)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}

		$this->load->model('ficha_p_model');
		$ficha_p = $this->ficha_p_model->get(array('alumno_id' => $alumno->id));

		$this->load->model('caracteristica_ficha_p_model');
		$fields_caracteristicas = $this->caracteristica_ficha_p_model->get_fields(NULL, FALSE, TRUE);
		$fields_tipos = $fields_caracteristicas[0];
		$lista_caracteristicas = $fields_caracteristicas[1];
		foreach ($fields_tipos as $fields) {
			foreach ($fields as $field_id => $field) {
				if (isset($field['input_type'])) {
					$this->{"array_{$field_id}_control"} = $field['array'];
				}
			}
			$this->set_model_validation_rules((object) array('fields' => $fields));
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->ficha_p_model->create(array(
					'alumno_id' => $alumno->id,
					'informe' => $this->input->post('informe'),
					'situacion_familiar' => $this->input->post('situacion_familiar'),
					'actividad_laboral' => $this->input->post('actividad_laboral')
					), FALSE);
				$ficha_p = $this->ficha_p_model->get_row_id();
				$this->load->model('caracteristica_model');
				$this->load->model('caracteristica_valor_model');
				foreach ($this->input->post('caracteristicas') as $id_caracteristica => $valor_caracteristica) {
					if (!isset($lista_caracteristicas[$id_caracteristica]->caracteristica_alumno_id)) {
						if ($lista_caracteristicas[$id_caracteristica]->lista_valores === 'Si') {
							if (!empty($valor_caracteristica)) {
								$valor = $this->caracteristica_valor_model->get_valor($valor_caracteristica);
								if (!empty($valor)) {
									$trans_ok &= $this->caracteristica_ficha_p_model->create(array(
										'ficha_p_id' => $ficha_p,
										'caracteristica_id' => $id_caracteristica,
										'valor' => $valor->valor,
										'fecha_desde' => date('Y-m-d'),
										'caracteristica_valor_id' => $valor->id
										), FALSE);
								} else {
									$this->db->trans_rollback();
									show_error('No se encontró el registro a actualizar', 500, 'Registro no encontrado');
								}
							}
						} else {
							if (!empty($valor_caracteristica)) {
								$trans_ok &= $this->caracteristica_ficha_p_model->create(array(
									'ficha_p_id' => $ficha_p,
									'caracteristica_id' => $id_caracteristica,
									'fecha_desde' => date('Y-m-d'),
									'valor' => $valor_caracteristica
									), FALSE);
							}
						}
					} else {
						if ($lista_caracteristicas[$id_caracteristica]->lista_valores === 'Si') {
							if (!empty($valor_caracteristica)) {
								$valor = $this->caracteristica_valor_model->get_valor($valor_caracteristica);
								if (!empty($valor)) {
									if ($lista_caracteristicas[$id_caracteristica]->valor !== $valor->valor) {
										$trans_ok &= $this->caracteristica_ficha_p_model->update(array(
											'id' => $lista_caracteristicas[$id_caracteristica]->caracteristica_alumno_id,
											'fecha_hasta' => date('Y-m-d')
											), FALSE);
										$trans_ok &= $this->caracteristica_ficha_p_model->create(array(
											'ficha_p_id' => $ficha_p,
											'caracteristica_id' => $id_caracteristica,
											'fecha_desde' => date('Y-m-d'),
											'valor' => $valor->valor,
											'caracteristica_valor_id' => $valor->id
											), FALSE);
									}
								} else {
									$this->db->trans_rollback();
									show_error('No se encontró el registro a actualizar', 500, 'Registro no encontrado');
								}
							} else {
								if (!empty($lista_caracteristicas[$id_caracteristica]->valor)) {
									$trans_ok &= $this->caracteristica_ficha_p_model->update(array(
										'id' => $lista_caracteristicas[$id_caracteristica]->caracteristica_alumno_id,
										'fecha_hasta' => date('Y-m-d')
										), FALSE);
								}
							}
						} else {
							if ($lista_caracteristicas[$id_caracteristica]->valor != $valor_caracteristica) {
								$trans_ok &= $this->caracteristica_ficha_p_model->update(array(
									'id' => $lista_caracteristicas[$id_caracteristica]->caracteristica_alumno_id,
									'fecha_hasta' => date('Y-m-d')
									), FALSE);
								if (!empty($valor_caracteristica)) {//No insertar características vacías
									$trans_ok &= $this->caracteristica_ficha_p_model->create(array(
										'ficha_p_id' => $ficha_p,
										'caracteristica_id' => $id_caracteristica,
										'fecha_desde' => date('Y-m-d'),
										'valor' => $valor_caracteristica
										), FALSE);
								}
							}
						}
					}
					unset($lista_caracteristicas[$id_caracteristica]);
				}
				foreach ($lista_caracteristicas as $caracteristica) {
					if (isset($caracteristica->caracteristica_alumno_id)) {
						$trans_ok &= $this->caracteristica_ficha_p_model->update(array(
							'id' => $caracteristica->caracteristica_alumno_id,
							'fecha_hasta' => date('Y-m-d')
							), FALSE);
					}
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->caracteristica_ficha_p_model->get_msg());
					redirect("alumno/ficha_psicopedagogica_ver/$alumno_division_id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->caracteristica_ficha_p_model->get_error())
						$errors .= '<br>' . $this->caracteristica_ficha_p_model->get_error();
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->caracteristica_ficha_p_model->get_error() ? $this->caracteristica_ficha_p_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->alumno_model->fields, $alumno, TRUE);

		foreach ($fields_tipos as $tipo => $fields) {
			$data['fields_tipos'][$tipo] = $this->build_fields($fields);
		}

		$data['fields_ficha'] = $this->build_fields($this->ficha_p_model->fields, $ficha_p[0]);
		$data['ciclo_lectivo'] = $alumno_division->ciclo_lectivo;
		$data['division'] = "$division->curso $alumno_division->division";
		$data['division_id'] = $alumno_division->division_id;
		$data['escuela'] = $escuela;
		$data['alumno'] = $alumno;
		$data['alumno_division_id'] = $alumno_division_id;
		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar características de escuela';
		$this->load_template('alumno/alumno_ficha_psicopedagogica', $data);
	}

	public function modal_alumno_familia_buscar($alumno_division_id = NULL, $persona_id = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}

		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division_id)) {
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}
		$this->load->model('division_model');
		$division = $this->division_model->get_one($alumno_division->division_id);
		if (empty($division)) {
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_model');

		$model_busqueda = new stdClass();
		$model_busqueda->fields = array(
			'd_documento' => array('label' => 'Documento', 'maxlength' => '10'),
			'd_apellido' => array('label' => 'Apellido', 'maxlength' => '100', 'minlength' => '3'),
			'd_nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'minlength' => '3'),
			'persona_seleccionada' => array('label' => '', 'type' => 'integer', 'required' => TRUE),
		);

		$this->set_model_validation_rules($model_busqueda);
		if (isset($_POST) && !empty($_POST)) {
			$pariente_id = $this->input->post('persona_id');
			if ($pariente_id !== '') {
				$this->session->set_flashdata('pariente_id', $pariente_id);
			}
			redirect("alumno/editar/$alumno_division_id#tab_familiares", 'refresh');
		}

		$data['fields'] = $this->build_fields($model_busqueda->fields);
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['alumno_division'] = $alumno_division;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Buscar familiar a agregar';
		$this->load->view('alumno/alumno_familia_buscar_modal', $data);
	}

	public function modal_agregar_familiar_existente($alumno_division_id = NULL, $pariente_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id) || $pariente_id == NULL || !ctype_digit($pariente_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$persona = $this->persona_model->get_one($pariente_id);
		if (empty($persona)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}

		$this->load->model('alumno_division_model');
		$this->load->model('division_model');

		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		$division = $this->division_model->get_one($alumno_division->division_id);

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$alumno = $this->alumno_model->get(array('id' => $alumno_division->alumno_id));
		if (empty($alumno)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}

		$this->load->model('parentesco_tipo_model');
		$this->load->model('familia_model');
		$hijos = $this->familia_model->get_hijos($pariente_id);

		$persona_model = new stdClass();
		$persona_model->fields = array(
			'documento_tipo' => array('label' => 'Tipo', 'readonly' => TRUE),
			'documento' => array('label' => 'Documento', 'readonly' => TRUE),
			'apellido' => array('label' => 'Apellido', 'readonly' => TRUE),
			'nombre' => array('label' => 'Nombre', 'readonly' => TRUE),
			'nivel_estudio' => array('label' => 'Nivel de estudios', 'readonly' => TRUE),
			'ocupacion' => array('label' => 'Ocupación', 'readonly' => TRUE),
			'prestadora' => array('label' => 'Prestadora', 'readonly' => TRUE),
			'telefono_movil' => array('label' => 'Celular', 'readonly' => TRUE),
			'email' => array('label' => 'Email', 'readonly' => TRUE)
		);


		$this->array_parentesco_tipo_control = $this->familia_model->fields['parentesco_tipo']['array'] = $this->get_array('parentesco_tipo', 'descripcion', 'id', null, array('' => '-- Seleccionar parentesco --'));
		$this->array_convive_control = $this->familia_model->fields['convive']['array'];

		$this->set_model_validation_rules($this->familia_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				if ($pariente_id !== $this->input->post('persona_id')) {
					show_error('Esta solicitud no pasó el control de seguridad.');
				}
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->familia_model->create(array(
					'persona_id' => $alumno->persona_id,
					'pariente_id' => $pariente_id,
					'parentesco_tipo_id' => $this->input->post('parentesco_tipo'),
					'convive' => $this->input->post('convive')
					), FALSE);

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->familia_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar el familiar.';
					if ($this->familia_model->get_error())
						$errors .= '<br>' . $this->familia_model->get_error();
					$this->session->set_flashdata('error', $errors);
				}
			}else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect("alumno/editar/$alumno_division_id#tab_familiares", 'refresh');
		}

		$data['fields_p'] = $this->build_fields($persona_model->fields, $persona, TRUE);
		$data['fields'] = $this->build_fields($this->familia_model->fields);
		$data['hijos'] = $hijos;
		$data['persona'] = $persona;
		$data['alumno'] = $alumno;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar nuevo familiar';
		$this->load->view('alumno/alumno_agregar_familiar_existente', $data);
	}

	public function modal_agregar_familiar_nuevo($alumno_division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('alumno_division_model');
		$this->load->model('division_model');

		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		$division = $this->division_model->get_one($alumno_division->division_id);

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$alumno = $this->alumno_model->get(array('id' => $alumno_division->alumno_id));
		if (empty($alumno)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}

		$this->load->model('documento_tipo_model');
		$this->load->model('ocupacion_model');
		$this->load->model('prestadora_model');
		$this->load->model('nivel_estudio_model');
		$this->load->model('familia_model');
		$this->load->model('parentesco_tipo_model');

		$model = new stdClass();
		$model->fields = array(
			'p_documento_tipo' => array('label' => 'Tipo de documento', 'input_type' => 'combo', 'id_name' => 'documento_tipo_id', 'required' => TRUE),
			'p_documento' => array('label' => 'Documento', 'type' => 'integer', 'maxlength' => '9', 'required' => TRUE),
			'p_apellido' => array('label' => 'Apellido', 'maxlength' => '100', 'required' => TRUE),
			'p_nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'required' => TRUE),
			'p_nivel_estudio' => array('label' => 'Nivel de estudios', 'input_type' => 'combo', 'id_name' => 'nivel_estudio_id'),
			'p_ocupacion' => array('label' => 'Ocupación', 'input_type' => 'combo', 'id_name' => 'ocupacion_id'),
			'p_prestadora' => array('label' => 'Prestadora', 'input_type' => 'combo', 'id_name' => 'prestadora_id'),
			'p_telefono_movil' => array('label' => 'Celular', 'maxlength' => '40'),
			'p_email' => array('label' => 'Email', 'maxlength' => '100')
		);

		$this->array_p_documento_tipo_control = $model->fields['p_documento_tipo']['array'] = $this->get_array('documento_tipo', 'descripcion_corta', 'id', null, array('1' => ''));
		$this->array_p_ocupacion_control = $model->fields['p_ocupacion']['array'] = $this->get_array('ocupacion', 'descripcion', 'id', null, array('' => '-- Seleccionar ocupación --'));
		$this->array_p_prestadora_control = $model->fields['p_prestadora']['array'] = $this->get_array('prestadora', 'descripcion', 'id', null, array('' => '-- Seleccionar prestadora --'));
		$this->array_p_nivel_estudio_control = $model->fields['p_nivel_estudio']['array'] = $this->get_array('nivel_estudio', 'descripcion', 'id', null, array('' => '-- Seleccionar nivel de estudios --'));
		$this->array_parentesco_tipo_control = $this->familia_model->fields['parentesco_tipo']['array'] = $this->get_array('parentesco_tipo', 'descripcion', 'id', null, array('' => '-- Seleccionar parentesco --'));
		$this->array_convive_control = $this->familia_model->fields['convive']['array'];

		$this->set_model_validation_rules($model);
		$this->set_model_validation_rules($this->familia_model);

		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->persona_model->create(array(
					'documento_tipo_id' => $this->input->post('p_documento_tipo'),
					'documento' => $this->input->post('p_documento'),
					'nombre' => $this->input->post('p_nombre'),
					'apellido' => $this->input->post('p_apellido'),
					'nivel_estudio_id' => $this->input->post('p_nivel_estudio'),
					'ocupacion_id' => $this->input->post('p_ocupacion'),
					'prestadora_id' => $this->input->post('p_prestadora'),
					'telefono_movil' => $this->input->post('p_telefono_movil'),
					'email' => $this->input->post('p_email')
					), FALSE);
				$pariente_id = $this->persona_model->get_row_id();

				$trans_ok &= $this->familia_model->create(array(
					'persona_id' => $alumno->persona_id,
					'pariente_id' => $pariente_id,
					'parentesco_tipo_id' => $this->input->post('parentesco_tipo'),
					'convive' => $this->input->post('convive')
					), FALSE);

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->familia_model->get_msg());
					redirect("alumno/editar/$alumno_division_id#tab_familiares", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar el familiar.';
					if ($this->persona_model->get_error())
						$errors .= '<br>' . $this->persona_model->get_error();
					if ($this->familia_model->get_error())
						$errors .= '<br>' . $this->familia_model->get_error();
					$this->session->set_flashdata('error', $errors);
				}
			}else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect("alumno/editar/$alumno_division_id#tab_familiares", 'refresh');
		}

		$data['fields_p'] = $this->build_fields($model->fields);
		$data['fields'] = $this->build_fields($this->familia_model->fields);

		$data['alumno'] = $alumno;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar nuevo familiar';
		$this->load->view('alumno/alumno_agregar_familiar_nuevo', $data);
	}

	public function modal_eliminar_familiar($alumno_division_id = NULL, $familia_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id) || $familia_id == NULL || !ctype_digit($familia_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('alumno_division_model');
		$this->load->model('division_model');

		$datos = $this->alumno_division_model->get_one($alumno_division_id);
		$datos2 = $this->division_model->get_one($datos->division_id);

		if (empty($datos->ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $datos->ciclo_lectivo))) {
			$datos->ciclo_lectivo = date('Y');
		}

		$escuela = $this->escuela_model->get_one($datos2->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$this->load->model('familia_model');
		$familia = $this->familia_model->get_one($familia_id);
		$hijos = $this->familia_model->get_hijos($familia->pariente_id);
		if (empty($familia)) {
			$this->modal_error('No se encontró el registro del familiar a eliminar', 'Registro no encontrado');
			return;
		}

		$familia->convive = $this->familia_model->fields['convive']['array'][$familia->convive];

		$persona = $this->persona_model->get_one($familia->pariente_id);
		if (empty($persona)) {
			$this->modal_error('No se encontró el registro de la persona', 'Registro no encontrado');
			return;
		}

		unset($this->persona_model->fields['cuil']);
		unset($this->persona_model->fields['calle']);
		unset($this->persona_model->fields['calle_numero']);
		unset($this->persona_model->fields['departamento']);
		unset($this->persona_model->fields['piso']);
		unset($this->persona_model->fields['barrio']);
		unset($this->persona_model->fields['manzana']);
		unset($this->persona_model->fields['casa']);
		unset($this->persona_model->fields['localidad']);
		unset($this->persona_model->fields['sexo']);
		unset($this->persona_model->fields['estado_civil']);
		unset($this->persona_model->fields['telefono_fijo']);
		unset($this->persona_model->fields['fecha_nacimiento']);
		unset($this->persona_model->fields['fecha_defuncion']);
		unset($this->persona_model->fields['obra_social']);
		unset($this->persona_model->fields['grupo_sanguineo']);
		unset($this->persona_model->fields['depto_nacimiento']);
		unset($this->persona_model->fields['lugar_traslado_emergencia']);
		unset($this->persona_model->fields['nacionalidad']);

		if (isset($_POST) && !empty($_POST)) {
			if ($familia->id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->familia_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->familia_model->get_msg());
				redirect("alumno/editar/$alumno_division_id#tab_familiares", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona, TRUE);
		$data['fields_familia'] = $this->build_fields($this->familia_model->fields, $familia, TRUE);
		$data['hijos'] = $hijos;
		$data['familia'] = $familia;
		$data['persona'] = $persona;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar familiar';
		$this->load->view('alumno/alumno_familia', $data);
	}

	public function modal_editar_familiar($alumno_division_id = NULL, $familia_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id) || $familia_id == NULL || !ctype_digit($familia_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('alumno_division_model');
		$this->load->model('division_model');
		$this->load->model('familia_model');
		$datos = $this->alumno_division_model->get_one($alumno_division_id);
		$datos2 = $this->division_model->get_one($datos->division_id);

		if (empty($datos->ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $datos->ciclo_lectivo))) {
			$datos->ciclo_lectivo = date('Y');
		}
		$escuela = $this->escuela_model->get_one($datos2->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}
		$familia = $this->familia_model->get_one($familia_id);
		if (empty($familia)) {
			$this->modal_error('No se encontró el registro del familiar', 'Registro no encontrado');
			return;
		}
		$hijos = $this->familia_model->get_hijos($familia->pariente_id);
		$persona = $this->persona_model->get_one($familia->pariente_id);
		if (empty($persona)) {
			$this->modal_error('No se encontró el registro de la persona', 'Registro no encontrado');
			return;
		}
		$this->load->model('ocupacion_model');
		$this->load->model('documento_tipo_model');
		$this->load->model('parentesco_tipo_model');
		$this->load->model('nivel_estudio_model');
		$this->load->model('prestadora_model');
		$this->load->model('sexo_model');
		$this->load->model('estado_civil_model');

		$this->array_documento_tipo_control = $array_documento_tipo = $this->get_array('documento_tipo', 'descripcion_corta', 'id', null, array('' => '-- Seleccionar --'));
		$this->array_nivel_estudio_control = $array_nivel_estudio = $this->get_array('nivel_estudio', 'descripcion', 'id', null, array('' => ''));
		$this->array_prestadora_control = $array_prestadora = $this->get_array('prestadora', 'descripcion', 'id', null, array('' => ''));
		$this->array_ocupacion_control = $array_ocupacion = $this->get_array('ocupacion', 'descripcion', 'id', null, array('' => ''));
		$this->array_convive_control = $this->familia_model->fields['convive']['array'];
		$this->array_parentesco_tipo_control = $array_parentesco_tipo = $this->get_array('parentesco_tipo', 'descripcion');
		$this->array_sexo_control = $array_sexo = $this->get_array('sexo', 'descripcion', 'id', array('sort_by' => 'id'), array('' => '-- Seleccionar sexo --'));
		$this->array_estado_civil_control = $array_estado_civil = $this->get_array('estado_civil', 'descripcion', 'id', null, array('' => '-- Seleccionar estado civil --'));

		unset($this->persona_model->fields['cuil']);
		unset($this->persona_model->fields['calle']);
		unset($this->persona_model->fields['calle_numero']);
		unset($this->persona_model->fields['departamento']);
		unset($this->persona_model->fields['piso']);
		unset($this->persona_model->fields['barrio']);
		unset($this->persona_model->fields['manzana']);
		unset($this->persona_model->fields['casa']);
		unset($this->persona_model->fields['localidad']);
		unset($this->persona_model->fields['telefono_fijo']);
		unset($this->persona_model->fields['fecha_nacimiento']);
		unset($this->persona_model->fields['fecha_defuncion']);
		unset($this->persona_model->fields['obra_social']);
		unset($this->persona_model->fields['grupo_sanguineo']);
		unset($this->persona_model->fields['depto_nacimiento']);
		unset($this->persona_model->fields['lugar_traslado_emergencia']);
		unset($this->persona_model->fields['nacionalidad']);

		$this->set_model_validation_rules($this->familia_model);
		$this->set_model_validation_rules($this->persona_model);

		if (isset($_POST) && !empty($_POST)) {
			if ($familia_id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->persona_model->update(array(
					'id' => $familia->pariente_id,
					'nombre' => $this->input->post('nombre'),
					'apellido' => $this->input->post('apellido'),
					'documento' => $this->input->post('documento'),
					'nivel_estudio_id' => $this->input->post('nivel_estudio'),
					'ocupacion_id' => $this->input->post('ocupacion'),
					'telefono_movil' => $this->input->post('telefono_movil'),
					'email' => $this->input->post('email'),
					'sexo_id' => $this->input->post('sexo'),
					'estado_civil_id' => $this->input->post('estado_civil'),
					'prestadora_id' => $this->input->post('prestadora')
					), FALSE);

				$trans_ok &= $this->familia_model->update(array(
					'id' => $familia_id,
					'parentesco_tipo_id' => $this->input->post('parentesco_tipo'),
					'convive' => $this->input->post('convive')
					), FALSE);

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->familia_model->get_msg());
					redirect("alumno/editar/$alumno_division_id#tab_familiares", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar el familiar.';
					if ($this->persona_model->get_error())
						$errors .= '<br>' . $this->persona_model->get_error();
					if ($this->familia_model->get_error())
						$errors .= '<br>' . $this->familia_model->get_error();
					$this->session->set_flashdata('error', $errors);
					redirect("alumno/editar/$alumno_division_id#tab_familiares", 'refresh');
				}
			}else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("alumno/editar/$alumno_division_id#tab_familiares", 'refresh');
			}
		}

		$this->persona_model->fields['documento_tipo']['array'] = $array_documento_tipo;
		$this->persona_model->fields['nivel_estudio']['array'] = $array_nivel_estudio;
		$this->persona_model->fields['prestadora']['array'] = $array_prestadora;
		$this->persona_model->fields['ocupacion']['array'] = $array_ocupacion;
		$this->familia_model->fields['parentesco_tipo']['array'] = $array_parentesco_tipo;
		$this->persona_model->fields['sexo']['array'] = $array_sexo;
		$this->persona_model->fields['estado_civil']['array'] = $array_estado_civil;
		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona);
		$data['fields_familia'] = $this->build_fields($this->familia_model->fields, $familia);
		$data['hijos'] = $hijos;
		$data['familia'] = $familia;
		$data['persona'] = $persona;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar valores del familiar';
		$this->load->view('alumno/alumno_familia', $data);
	}
}
/* End of file Alumno.php */
/* Location: ./application/controllers/Alumno.php */
