<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Division extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->load->model('division_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_DOCENTE_CURSADA, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE));
		$this->roles_escritorio = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_DOCENTE_CURSADA));
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_ESCUELA_ALUM, ROL_ESCUELA_CAR, ROL_GRUPO_ESCUELA_CONSULTA, ROL_ASISTENCIA_DIVISION))) {
			$this->edicion = FALSE;
		}
		$this->roles_administrar = array(ROL_ADMIN, ROL_USI, ROL_DIR_ESCUELA);
		$this->nav_route = 'menu/division';
	}

	public function escritorio($id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_escritorio) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("division/escritorio/$id/$ciclo_lectivo", 'refresh');
		}

		$division = $this->division_model->get_one($id);
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
		if (in_array($this->rol->codigo, array(ROL_ASISTENCIA_DIVISION, ROL_DOCENTE))) {
			if (!$this->usuarios_model->verificar_permiso('division', $this->rol, $division)) {
				show_error('No tiene permisos para acceder a la asistencia de la división', 500, 'Acción no autorizada');
			}
		}
		if ($this->rol->codigo !== ROL_ASISTENCIA_DIVISION) {
			//mostrar notas y cosas de la división que no debería ver rol asistencia división
		}

		$this->load->model('alumno_model');
		$division->escuela = "Esc. $escuela->nombre_largo";
		$this->load->model('division_inasistencia_model');
		$this->load->model('calendario_model');
		$this->load->model('carrera_model');
		$carrera = $this->carrera_model->get_one($division->carrera_id);
		$alumnos = $this->alumno_model->get_alumnos_division($division->id);
		$alumnos_div = array();
		foreach ($alumnos as $alumno) {
			if (!isset($alumnos_div[$alumno->ciclo_lectivo])) {
				$alumnos_div[$alumno->ciclo_lectivo] = array('N' => 0, 'M' => 0, 'F' => 0);
			}
			$alumnos_div[$alumno->ciclo_lectivo][empty($alumno->sexo) ? 'N' : substr($alumno->sexo, 0, 1)] ++;
		}
		$data['estadisticas_total'] = $this->division_inasistencia_model->get_estadisticas_inasistencias($division->id, $ciclo_lectivo);
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['carrera'] = $carrera;
		$data['inasistencias'] = $this->division_inasistencia_model->get_registros($division->id, $ciclo_lectivo);
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['alumnos'] = $alumnos;
		$data['alumnos_div'] = $alumnos_div;
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
		$data['grafica'] = $this->division_inasistencia_model->grafica($periodos, $division->id, $ciclo_lectivo);
		$data['periodos'] = $periodos;
		$data['estadisticas'] = $this->load->view('division_inasistencia/escritorio_division_modulo_inasistencia', $data, TRUE);
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$data['css'][] = 'plugins/c3/c3.min.css';
		$data['js'][] = 'plugins/d3/d3.min.js';
		$data['js'][] = 'plugins/c3/c3.min.js';
		$this->load_template('escritorio/escritorio_division', $data);
	}

	public function listar($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$tableData = array(
			'columns' => array(
				array('label' => 'Curso', 'data' => 'curso', 'width' => 5),
				array('label' => 'División', 'data' => 'division', 'width' => 10),
				array('label' => 'Turno', 'data' => 'turno', 'width' => 10),
				array('label' => 'Carrera', 'data' => 'carrera', 'width' => 20),
				array('label' => 'Modalidad', 'data' => 'modalidad', 'width' => 15),
				array('label' => 'Fecha Alta', 'data' => 'fecha_alta', 'render' => 'date', 'width' => 10),
				array('label' => 'Fecha Baja', 'data' => 'fecha_baja', 'render' => 'date', 'width' => 10),
				array('label' => 'Horario', 'data' => 'horario', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all')
			),
			'table_id' => 'division_table',
			'source_url' => "division/listar_data/$escuela_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(0, 'asc'), array(1, 'asc')),
			'reuse_var' => TRUE,
			'initComplete' => "complete_division_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);

		$data['escuela'] = $escuela;

		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Divisiones';
		$this->load_template('division/division_listar', $data);
	}

	public function listar_cerrados($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$tableData = array(
			'columns' => array(
				array('label' => 'Curso', 'data' => 'curso', 'width' => 5),
				array('label' => 'División', 'data' => 'division', 'width' => 10),
				array('label' => 'Turno', 'data' => 'turno', 'width' => 10),
				array('label' => 'Carrera', 'data' => 'carrera', 'width' => 20),
				array('label' => 'Modalidad', 'data' => 'modalidad', 'width' => 15),
				array('label' => 'Fecha Alta', 'data' => 'fecha_alta', 'render' => 'date', 'width' => 10),
				array('label' => 'Fecha Baja', 'data' => 'fecha_baja', 'render' => 'date', 'width' => 10),
				array('label' => 'Horario', 'data' => 'horario', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all')
			),
			'table_id' => 'division_table',
			'source_url' => "division/listar_cerrados_data/$escuela_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(0, 'asc'), array(1, 'asc')),
			'reuse_var' => TRUE,
			'initComplete' => "complete_division_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);

		$data['escuela'] = $escuela;

		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Divisiones cerradas';
		$this->load_template('division/division_listar_cerrados', $data);
	}

	public function listar_data($escuela_id, $rol_codigo, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select('division.id, division.escuela_id, division.turno_id, division.curso_id, division.division, division.carrera_id, division.fecha_alta, division.fecha_baja, carrera.descripcion as carrera, curso.descripcion as curso, modalidad.descripcion as modalidad, escuela.nombre as escuela, turno.descripcion as turno, COUNT(horario.id) as horarios')
			->unset_column('id')
			->from('division')
			->join('horario', 'horario.division_id = division.id', 'left')
			->join('carrera', 'carrera.id = division.carrera_id', 'left')
			->join('curso', 'curso.id = division.curso_id', 'left')
			->join('escuela', 'escuela.id = division.escuela_id', 'left')
			->join('turno', 'turno.id = division.turno_id', 'left')
			->join('modalidad', 'modalidad.id = division.modalidad_id', 'left')
			->where('division.escuela_id', $escuela_id)
			->where('division.fecha_baja IS NULL')
			->group_by('division.id')
			->add_column('horario', '$1', 'dt_column_division_horario(horarios)');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="division/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item" href="division/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '<li><a class="dropdown-item" href="division/eliminar/$1" title="Eliminar"><i class="fa fa-remove"></i> Eliminar</a></li>'
				. '<li><a class="dropdown-item" href="division/ver_horario/$1" title="Horarios"><i class="fa fa-clock-o"></i> Horarios</a></li>'
				. '<li><a class="dropdown-item" href="division/alumnos/$1" title="Alumnos"><i class="fa fa-users"></i> Alumnos</a></li>'
				. '<li><a class="dropdown-item" href="division/cargos/$1" title="Cargos"><i class="fa fa-users"></i> Cargos</a></li>'
				. '</ul></div>', 'id');
		} elseif ($this->rol->codigo == ROL_ESCUELA_ALUM) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="division/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item" href="division/ver_horario/$1" title="Horarios"><i class="fa fa-clock-o"></i> Horarios</a></li>'
				. '<li><a class="dropdown-item" href="division/alumnos/$1" title="Alumnos"><i class="fa fa-users"></i> Alumnos</a></li>'
				. '</ul></div>', 'id');
		} else {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="division/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item" href="division/ver_horario/$1" title="Horarios"><i class="fa fa-clock-o"></i> Horarios</a></li>'
				. '<li><a class="dropdown-item" href="division/alumnos/$1" title="Alumnos"><i class="fa fa-users"></i> Alumnos</a></li>'
				. '<li><a class="dropdown-item" href="division/cargos/$1" title="Cargos"><i class="fa fa-users"></i> Cargos</a></li>'
				. '</ul></div>', 'id');
		}

		echo $this->datatables->generate();
	}

	public function listar_cerrados_data($escuela_id, $rol_codigo, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select('division.id, division.escuela_id, division.turno_id, division.curso_id, division.division, division.carrera_id, division.fecha_alta, division.fecha_baja, carrera.descripcion as carrera, curso.descripcion as curso, modalidad.descripcion as modalidad, escuela.nombre as escuela, turno.descripcion as turno, COUNT(horario.id) as horarios')
			->unset_column('id')
			->from('division')
			->join('horario', 'horario.division_id = division.id', 'left')
			->join('carrera', 'carrera.id = division.carrera_id', 'left')
			->join('curso', 'curso.id = division.curso_id', 'left')
			->join('escuela', 'escuela.id = division.escuela_id', 'left')
			->join('turno', 'turno.id = division.turno_id', 'left')
			->join('modalidad', 'modalidad.id = division.modalidad_id', 'left')
			->where('division.escuela_id', $escuela_id)
			->where('division.fecha_baja IS NOT NULL')
			->group_by('division.id')
			->add_column('horario', '$1', 'dt_column_division_horario(horarios)');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="division/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '</div>', 'id');
		} elseif ($this->rol->codigo == ROL_ESCUELA_ALUM) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="division/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '</div>', 'id');
		} else {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="division/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '</div>', 'id');
		}

		echo $this->datatables->generate();
	}

	public function agregar($escuela_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("division/listar/$escuela_id");
		}

		$this->load->model('carrera_model');
		$this->load->model('curso_model');
		$this->load->model('turno_model');
		$this->load->model('modalidad_model');
		$this->load->model('calendario_model');

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->array_carrera_control = $array_carrera = $this->get_array('carrera', 'descripcion', 'id', array(
			'join' => array(
				array('escuela_carrera', 'escuela_carrera.carrera_id=carrera.id AND escuela_carrera.escuela_id=' . $escuela->id),
			),
			'sort_by' => 'carrera.descripcion'
			), array('' => '-- Seleccionar carrera --'));
		if ($escuela->secundaria_mixta === 'N') {
			$this->array_curso_control = $array_curso = $this->get_array('curso', 'curso', 'id', array(
				'select' => array('curso.id', 'CONCAT(curso.descripcion, \' \', nivel.descripcion) AS curso'),
				'join' => array(
					array('nivel', 'nivel.id=curso.nivel_id', 'left'),
				),
				'nivel_id' => $escuela->nivel_id,
				'sort_by' => 'curso.descripcion'
				), array('' => '-- Seleccionar curso --'));
		} else {
			$this->array_curso_control = $array_curso = $this->get_array('curso', 'curso', 'id', array(
				'select' => array('curso.id', 'CONCAT(curso.descripcion, \' \', nivel.descripcion) AS curso'),
				'join' => array(
					array('nivel', 'nivel.id=curso.nivel_id', 'left'),
				),
				'where' => array('nivel_id in (3,4)'),
				'sort_by' => 'curso.descripcion'
				), array('' => '-- Seleccionar curso --'));
		}
		$this->array_turno_control = $array_turno = $this->get_array('turno', 'descripcion', 'id', array(), array('' => '-- Seleccionar turno --'));
		$this->array_modalidad_control = $array_modalidad = $this->get_array('modalidad', 'descripcion', 'id', array(), array('' => '-- Seleccionar modalidad --'));
		$this->array_calendario_control = $array_calendario = $this->get_array('calendario', 'descripcion', 'id', array(), array('' => '-- Seleccionar calendario --'));

		$this->set_model_validation_rules($this->division_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($escuela_id !== $this->input->post('escuela_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->load->helper('string');
				$trans_ok = TRUE;
				$trans_ok &= $this->division_model->create(array(
					'escuela_id' => $this->input->post('escuela_id'),
					'turno_id' => $this->input->post('turno'),
					'curso_id' => $this->input->post('curso'),
					'division' => $this->input->post('division'),
					'clave' => random_string('alnum', 8),
					'carrera_id' => $this->input->post('carrera'),
					'modalidad_id' => $this->input->post('modalidad'),
					'calendario_id' => $this->input->post('calendario'),
					'fecha_alta' => $this->get_date_sql('fecha_alta'),
					'fecha_baja' => $this->get_date_sql('fecha_baja'),
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->division_model->get_msg());
					redirect("division/listar/$escuela_id", 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->division_model->get_error() ? $this->division_model->get_error() : $this->session->flashdata('error')));

		$this->division_model->fields['carrera']['array'] = $array_carrera;
		$this->division_model->fields['curso']['array'] = $array_curso;
		$this->division_model->fields['escuela']['value'] = "Esc. $escuela->nombre_largo";
		$this->division_model->fields['turno']['array'] = $array_turno;
		$this->division_model->fields['modalidad']['array'] = $array_modalidad;
		$this->division_model->fields['calendario']['array'] = $array_calendario;

		$data['fields'] = $this->build_fields($this->division_model->fields);
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = TITLE . ' - Agregar división';
		$this->load_template('division/division_abm', $data);
	}

	public function editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("division/ver/$id");
		}

		$division = $this->division_model->get(array('id' => $id,
			'join' => array(
				array('turno', 'turno.id=division.turno_id', 'left', array('turno.descripcion as turno')),
				array('curso', 'curso.id=division.curso_id', 'left', array('curso.descripcion as curso')),
				array('carrera', 'carrera.id=division.carrera_id', 'left', array('carrera.descripcion as carrera')),
				array('calendario', 'calendario.id=division.calendario_id', 'left', array('calendario.descripcion as calendario')),
		)));
		if (empty($division)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		if (!empty($division->fecha_baja)) {
			$this->session->set_flashdata('error', 'No se puede editar una división cerrada');
			redirect("division/ver/$division->id", 'refresh');
		}

		$division->escuela = "Esc. $escuela->nombre_largo";

		$this->load->model('carrera_model');
		$this->load->model('curso_model');
		$this->load->model('turno_model');
		$this->load->model('modalidad_model');
		$this->array_carrera_control = $array_carrera = $this->get_array('carrera', 'descripcion', 'id', array(
			'join' => array(
				array('escuela_carrera', 'escuela_carrera.carrera_id=carrera.id', 'left')
			),
			'where' => array('escuela_carrera.escuela_id=' . $escuela->id . ' OR carrera.id=\'' . $division->carrera_id . '\''),
			'sort_by' => 'carrera.descripcion'
			), array('' => '-- Seleccionar carrera --'));
		if ($escuela->secundaria_mixta === 'N') {
			$this->array_curso_control = $array_curso = $this->get_array('curso', 'curso', 'id', array(
				'select' => array('curso.id', 'CONCAT(curso.descripcion, \' \', nivel.descripcion) AS curso'),
				'join' => array(
					array('nivel', 'nivel.id=curso.nivel_id', 'left'),
				),
				'nivel_id' => $escuela->nivel_id,
				'sort_by' => 'curso.descripcion'
			));
		} else {
			$this->array_curso_control = $array_curso = $this->get_array('curso', 'curso', 'id', array(
				'select' => array('curso.id', 'CONCAT(curso.descripcion, \' \', nivel.descripcion) AS curso'),
				'join' => array(
					array('nivel', 'nivel.id=curso.nivel_id', 'left'),
				),
				'where' => array('nivel_id in (3,4)'),
				'sort_by' => 'curso.descripcion'
			));
		}
		$this->array_turno_control = $array_turno = $this->get_array('turno', 'descripcion', 'id', array(), array('' => '-- Seleccionar turno --'));
		$this->array_modalidad_control = $array_modalidad = $this->get_array('modalidad', 'descripcion', 'id', array(), array('' => '-- Seleccionar modalidad --'));
		$this->load->model('division_inasistencia_model');
		$division_inasistencia = $this->division_inasistencia_model->get(array(
			'division_id' => $division->id,
		));
		if (!empty($division_inasistencia)) {
			unset($this->division_model->fields['calendario']['input_type']);
			unset($this->division_model->fields['calendario']['required']);
			$this->division_model->fields['calendario']['value'] = $division->calendario;
			$this->division_model->fields['calendario']['readonly'] = TRUE;
		} else {
			$this->load->model('calendario_model');
			$this->array_calendario_control = $array_calendario = $this->get_array('calendario', 'descripcion', 'id', array(), array('' => '-- Seleccionar calendario --'));
			$this->division_model->fields['calendario']['array'] = $array_calendario;
		}

		if (in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_PRIVADA))) {
			$this->division_model->fields['curso']['array'] = $array_curso;
		} else {
			unset($this->division_model->fields['curso']['array']);
			unset($this->division_model->fields['curso']['input_type']);
			unset($this->division_model->fields['curso']['required']);
		}

		$this->set_model_validation_rules($this->division_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->division_model->update(array(
					'id' => $this->input->post('id'),
					'turno_id' => $this->input->post('turno'),
					'curso_id' => $this->input->post('curso'),
					'division' => $this->input->post('division'),
					'carrera_id' => $this->input->post('carrera'),
					'modalidad_id' => $this->input->post('modalidad'),
					'calendario_id' => empty($division_inasistencia) ? $this->input->post('calendario') : $division->calendario_id,
					'fecha_alta' => $this->get_date_sql('fecha_alta', 'd/m/Y'),
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->division_model->get_msg());
					redirect("division/ver/$division->id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("division/ver/$division->id", 'refresh');
			}
		}

		$data['error'] = (validation_errors() ? validation_errors() : ($this->division_model->get_error() ? $this->division_model->get_error() : $this->session->flashdata('error')));

		$this->division_model->fields['carrera']['array'] = $array_carrera;
		if (in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_PRIVADA))) {
			$this->division_model->fields['curso']['array'] = $array_curso;
		} else {
			$this->division_model->fields['curso']['array'] = $division->curso;
			$this->division_model->fields['curso']['disabled'] = TRUE;
		}
		$this->division_model->fields['turno']['array'] = $array_turno;
		$this->division_model->fields['modalidad']['array'] = $array_modalidad;

		$data['fields'] = $this->build_fields($this->division_model->fields, $division);
		$data['error'] = $this->session->flashdata('error');
		$data['division'] = $division;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar división';
		$this->load_template('division/division_abm', $data);
	}

	public function eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("division/ver/$id");
		}

		$division = $this->division_model->get_one($id);
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

		$division->escuela = "Esc. $escuela->nombre_largo";
		$this->load->model('cargo_horario_model');
		$horarios_cargo = $this->cargo_horario_model->get(array(
			'select' => array('cargo_horario.id'),
			'join' => array(
				array('horario', "cargo_horario.horario_id = horario.id AND division_id=$division->id"),
			),
		));

		$this->load->model('horario_model');
		$horarios = $this->horario_model->get(array(
			'select' => array('horario.id'),
			'division_id' => $division->id
		));

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			$this->db->trans_begin();
			$trans_ok = TRUE;
			if (!empty($horarios_cargo)) {
				foreach ($horarios_cargo as $horario_cargo) {
					$trans_ok &= $this->cargo_horario_model->delete(array('id' => $horario_cargo->id), FALSE);
				}
			}
			if (!empty($horarios)) {
				foreach ($horarios as $horario) {
					$trans_ok &= $this->horario_model->delete(array('id' => $horario->id), FALSE);
				}
			}
			$trans_ok &= $this->division_model->delete(array('id' => $division->id), FALSE);
			$this->load->model('usuario_rol_model');
			$usuarios_rol = $this->usuario_rol_model->get_usuarios(array(ROL_DOCENTE, ROL_ASISTENCIA_DIVISION), $division->id);
			foreach ($usuarios_rol as $usuario_rol) {
				$trans_ok &= $this->usuario_rol_model->delete(array(
					'id' => $usuario_rol->id
					), FALSE);
			}
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->division_model->get_msg());
				redirect("division/listar/$escuela->id", 'refresh');
			} else {
				$this->db->trans_rollback();
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->division_model->get_error() ? $this->division_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->division_model->fields, $division, TRUE);

		$data['horario'] = empty($horarios) ? FALSE : TRUE;
		$data['division'] = $division;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = TITLE . ' - Eliminar división';
		$this->load_template('division/division_abm', $data);
	}

	public function ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$division = $this->division_model->get_one($id);
		if (empty($division)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_model');
		$this->load->model('cargo_model');

		$alumnos = $this->alumno_model->get_alumnos_division($id);
		$cargos = $this->cargo_model->get_cargos_division($id);

		if (!empty($alumnos)) {
			$cantidad_alumnos = count($alumnos);
			$data['cantidad_alumnos'] = $cantidad_alumnos;
		}

		$division->escuela = "Esc. $escuela->nombre_largo";

		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');

		$data['fields'] = $this->build_fields($this->division_model->fields, $division, TRUE);
		$data['cargos'] = $cargos;
		$data['alumnos'] = $alumnos;
		$data['division'] = $division;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver división';
		$this->load_template('division/division_abm', $data);
	}

	public function cargos($division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ESCUELA_CAR) {
			$this->edicion = TRUE;
		}
		if ($this->rol->codigo === ROL_ESCUELA_ALUM) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$division = $this->division_model->get_one($division_id);
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

		$division->escuela = "Esc. $escuela->nombre_largo";

		$tableData = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 2, 'class' => '', 'sortable' => 'false'),
				array('label' => 'Condición', 'data' => 'condicion_cargo', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Materia', 'data' => 'materia', 'width' => 24, 'class' => 'text-sm'),
				array('label' => 'Régimen', 'data' => 'regimen', 'width' => 25, 'class' => 'text-sm'),
				array('label' => 'Hs.', 'data' => 'carga_horaria', 'class' => 'dt-body-right', 'width' => 4),
				array('label' => 'Observaciones', 'data' => 'observaciones', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Servicios', 'data' => 'servicios', 'width' => 6, 'class' => 'dt-body-right', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => 'Persona Actual', 'data' => 'persona', 'width' => 24, 'class' => 'text-sm'),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(2, 'asc'), array(3, 'asc'), array(4, 'asc')),
			'table_id' => 'cargo_table',
			'source_url' => "division/cargos_data/$division_id",
			'details_format' => 'cargo_table_detalles',
			'reuse_var' => TRUE,
			'initComplete' => "complete_cargo_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - División';
		$this->load_template('division/division_cargos', $data);
	}

	public function cargos_data($division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ESCUELA_ALUM) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ESCUELA_CAR) {
			$this->edicion = TRUE;
		}
		$division = $this->division_model->get(array('id' => $division_id));
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

		$this->datatables
			->select('cargo.id, cargo.division_id, condicion_cargo.descripcion as condicion_cargo, curso.descripcion as curso, division.division as division, '
				. 'CONCAT(regimen.codigo, \' \', regimen.descripcion) as regimen, materia.descripcion as materia, '
				. 'cargo.observaciones, cargo.carga_horaria, COUNT(DISTINCT servicio.id) as servicios, '
				. 'CONCAT(COALESCE(persona.cuil, \'\'), \'<br>\', COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona')
			->unset_column('id')
			->from('cargo')
			->join('area', 'area.id = cargo.area_id', 'left')
			->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', 'left')
			->join('division', 'division.id = cargo.division_id', 'left')
			->join('curso', 'curso.id = division.curso_id', 'left')
			->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
			->join('carrera', 'espacio_curricular.carrera_id = carrera.id', 'left')
			->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
			->join('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', '')
			->join('servicio', 'servicio.cargo_id = cargo.id AND servicio.fecha_baja IS NULL', 'left')
			->join('servicio sp', "sp.id = (SELECT id FROM servicio s WHERE cargo_id = cargo.id AND s.fecha_baja IS NULL ORDER BY fecha_alta DESC LIMIT 1)", 'left', false)
			->join('persona', 'persona.id = sp.persona_id', 'left')
			->join('turno', 'turno.id = cargo.turno_id', 'left')
			->where('cargo.division_id', $division_id)
			->where('cargo.fecha_hasta IS NULL')
			->group_by('cargo.id, condicion_cargo.descripcion, curso.descripcion, division.division, materia.descripcion, carrera.descripcion, cargo.carga_horaria, regimen.codigo, regimen.descripcion')
			->add_column('', '', 'id');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="cargo/ver/$1/$2" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item" href="cargo/editar/$1/$2" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '<li><a class="dropdown-item" href="cargo/eliminar/$1/$2" title="Eliminar"><i class="fa fa-remove"></i> Eliminar</a></li>'
				. '<li><a class="dropdown-item btn-primary" href="servicio/agregar/$1"><i class="fa fa-plus" id="btn-agregar"></i> Agregar servicio</a></li>'
				. '</ul></div>', 'id, division_id');
		} else {
			$this->datatables->add_column('edit', '<a class="btn btn-xs btn-default" href="cargo/ver/$1/$2" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id, division_id');
		}

		echo $this->datatables->generate();
	}

	public function reporte_cargos($division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ESCUELA_ALUM) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$division = $this->division_model->get_one($division_id);
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
		$division->escuela = "Esc. $escuela->nombre_largo";

		$cargos_servicios = $this->db->query("
			select s.persona_id,s.id,s.cargo_id,e.numero, e.anexo,turno.descripcion as turno,d.id as division_id,
			p.cuil,CONCAT(p.apellido, ', ', p.nombre) nombre,cc.descripcion as condicion,COALESCE(f.descripcion, sf.detalle) as funcion_detalle,ec.cuatrimestre,
			t.liquidacion_s, r.codigo, r.descripcion regimen, c.carga_horaria, sr.descripcion revista, s.fecha_alta,
			sf.destino as funcion_destino, sf.norma as funcion_norma, sf.tarea as funcion_tarea,
			s.fecha_baja, ca.descripcion carrera, CONCAT(cu.descripcion, ' ', d.division) division, m.descripcion materia
			from servicio s
			join situacion_revista sr on s.situacion_revista_id=sr.id
			join cargo c on s.cargo_id=c.id
			join regimen r on c.regimen_id=r.id
			join escuela e on c.escuela_id=e.id
			join persona p on s.persona_id=p.id
			left join servicio_funcion sf on sf.servicio_id=s.id AND sf.fecha_hasta IS NULL
			left join funcion f on sf.funcion_id=f.id
			left join turno on turno.id = c.turno_id
			left join condicion_cargo cc on cc.id = c.condicion_cargo_id
			left join tbcabh t on s.id=t.servicio_id and t.vigente=201712
			left join division d on c.division_id=d.id
			left join carrera ca on d.carrera_id=ca.id
			left join curso cu on d.curso_id=cu.id
			left join espacio_curricular ec on c.espacio_curricular_id=ec.id
			left join materia m on ec.materia_id=m.id
			WHERE d.id = ? and c.fecha_hasta IS NULL and (COALESCE(s.fecha_baja, '2017-12-01')>='2017-12-01' or t.id IS NOT NULL)", array($division_id))->result();
		$cargos = array();
		foreach ($cargos_servicios as $cargo_servicio) {
			if (empty($cargo_servicio->division) && empty($cargo_servicio->carrera)) {
				$cargos["Cargos sin carrera"][$cargo_servicio->cargo_id][] = $cargo_servicio;
			} else {
				$cargos["$cargo_servicio->division - $cargo_servicio->carrera"][$cargo_servicio->cargo_id][] = $cargo_servicio;
			}
		}
		$data['escuela'] = $escuela;
		$data['cargos'] = $cargos;
		$data['division'] = $division;
		$data['cargos_servicios'] = $cargos_servicios;
		$content = $this->load->view('division/division_reporte_cargos', $data, TRUE);
		echo $content;
	}

	public function alumnos($division_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ESCUELA_ALUM) {
			$this->edicion = TRUE;
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("division/alumnos/$division_id/$ciclo_lectivo", 'refresh');
		}
		$division = $this->division_model->get_one($division_id);
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

		$division->escuela = "Esc. $escuela->nombre_largo";

		$tableData = array(
			'columns' => array(
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Alumno', 'data' => 'persona', 'width' => 25, 'class' => 'text-sm'),
				array('label' => 'Sexo', 'data' => 'sexo', 'width' => 5, 'class' => 'dt-body-center'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Div', 'data' => 'division', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Condición', 'data' => 'condicion', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'date', 'width' => 10),
				array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'date', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(6, 'asc'), array(1, 'asc'), array(2, 'asc'), array(3, 'asc')),
			'table_id' => 'alumno_table',
			'source_url' => "division/alumnos_data/$division_id/$ciclo_lectivo",
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_table",
			'footer' => TRUE,
			'disablePagination' => TRUE,
			'dom' => 'rt'
		);
		$alumnos_transicion = $this->division_model->get_alumnos_transicion($division->id);

		$data['abrir_modal'] = $this->session->flashdata('abrir_modal');
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['alumnos_transicion'] = $alumnos_transicion;
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - División';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('division/division_alumnos', $data);
	}

	public function clave_portal($escuela_id = NULL) {
		if (!PORTAL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ESCUELA_ALUM) {
			$this->edicion = TRUE;
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$divisiones = $this->division_model->get_by_divisiones($escuela_id);

		$data['escuela'] = $escuela;
		$data['abrir_modal'] = $this->session->flashdata('abrir_modal');
		$data['escuela'] = $escuela;
		$data['divisiones'] = $divisiones;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - División - Clave portal';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('division/division_clave_portal', $data);
	}

	public function imprimir_clave_curso_division($escuela_id = NULL) {
		if (!PORTAL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}

		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$divisiones = $this->division_model->get_by_divisiones($escuela_id);

		$data['divisiones'] = $divisiones;
		$data['escuela'] = $escuela;

		$content = $this->load->view('division/division_clave_impresion_parcial', $data, TRUE);

		$this->load->helper('mpdf');
		$fecha_actual = date('d/m/Y');
		$watermark = '';

		exportMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Clave de cursos y divisiones para el Portal Alumnos', '', '|{PAGENO} de {nb}|', '', $watermark, 'I', FALSE, FALSE);
	}

	public function excel_clave($escuela_id = NULL) {
		if (!PORTAL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}

		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$campos = array(
			'A' => array('Curso', 25),
			'B' => array('División', 25),
			'C' => array('Turno', 20),
			'D' => array('Clave portal', 35)
		);

		$divisiones = $this->db->select('curso.descripcion as curso, division.division, turno.descripcion as turno, division.clave')
				->from('division')
				->join('horario', 'horario.division_id = division.id', 'left')
				->join('carrera', 'carrera.id = division.carrera_id', 'left')
				->join('curso', 'curso.id = division.curso_id', 'left')
				->join('escuela', 'escuela.id = division.escuela_id', 'left')
				->join('turno', 'turno.id = division.turno_id', 'left')
				->join('modalidad', 'modalidad.id = division.modalidad_id', 'left')
				->where('division.escuela_id', $escuela_id)
				->where('division.fecha_baja IS NULL')
				->order_by('curso.descripcion, division.division')
				->group_by('division.id')
				->get()->result_array();

		if (!empty($divisiones)) {
			if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
				show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
			}
			$this->load->library('PHPExcel');
			$this->phpexcel->getProperties()->setTitle($escuela->nombre_largo)->setDescription("");
			$this->phpexcel->setActiveSheetIndex(0);

			$sheet = $this->phpexcel->getActiveSheet();
			$escuela_nombre = str_replace("/", " Anexo ", "$escuela->nombre_corto");
			$sheet->setTitle(substr($escuela_nombre, 0, 30));
			$encabezado = array();
			$ultima_columna = 'A';
			foreach ($campos as $columna => $campo) {
				$encabezado[] = $campo[0];
				$sheet->getColumnDimension($columna)->setWidth($campo[1]);
				$ultima_columna = $columna;
			}

			$sheet->setCellValue('A1', 'LISTADO DE CLAVES PARA CURSOS Y DIVISIONES');
			$sheet->setCellValue('A2', "");
			$sheet->setCellValue('A3', "Escuela N°: $escuela->numero, Nombre: $escuela->nombre");
			$sheet->setCellValue('A4', '');
			$sheet->fromArray(array($encabezado), NULL, 'A5');
			$sheet->fromArray($divisiones, NULL, 'A6');

			$sheet->getStyle('A5:' . $ultima_columna . '5')->getFont()->setBold(true);
			$sheet->mergeCells('A1:D1');
			$sheet->mergeCells('A2:D2');
			$sheet->mergeCells('A3:D3');
			$sheet->mergeCells('A4:D4');
			$sheet->getStyle('A1:A4')->getFont()->setBold(true);
			$sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A2:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			$ultima_fila = $sheet->getHighestRow();
			$sheet->getStyle("A5:D$ultima_fila")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sheet->getStyle("A5:D$ultima_fila")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle("G5:D$ultima_fila")->getAlignment()->setWrapText(true);
			$sheet->getStyle('A4')->getFont()->setBold(true);

			header("Content-Type: application/vnd.ms-excel");
			$nombreArchivo = "Clave de cursos y divisiones, Escuela - $escuela_nombre";
			header("Content-Disposition: attachment; filename=\"$nombreArchivo.xls\"");
			header("Cache-Control: max-age=0");

			$writer = PHPExcel_IOFactory::createWriter($this->phpexcel, "Excel5");
			$writer->save('php://output');
			exit;
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect("division/clave_portal/$escuela_id", 'refresh');
		}
	}

	public function alumnos_data($division_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ESCUELA_ALUM) {
			$this->edicion = TRUE;
		}
		$division = $this->division_model->get(array('id' => $division_id));
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

		$this->datatables
			->select("alumno_division.id,COALESCE(CONCAT(CASE WHEN sexo.id=1 THEN 'M' WHEN sexo.id=2 THEN 'F' ELSE '' END), ' ') as sexo, alumno.observaciones, alumno_division.fecha_desde, alumno_division.fecha_hasta, CONCAT(documento_tipo.descripcion_corta,' ', persona.documento) as documento, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, CASE curso.grado_multiple WHEN 'Si' THEN CONCAT(division.division, ' (', COALESCE(c_gm.descripcion, ''), ')') ELSE division.division END as division, curso.descripcion as curso, division.escuela_id, alumno_division.condicion as condicion")
			->unset_column('id')
			->from('alumno')
			->join('persona', 'persona.id = alumno.persona_id')
			->join('sexo', 'sexo.id = persona.sexo_id', 'left')
			->join('alumno_division', 'alumno_division.alumno_id = alumno.id')
			->join('division', 'division.id = alumno_division.division_id')
			->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id')
			->join('curso', 'division.curso_id = curso.id')
			->join('curso c_gm', 'alumno_division.curso_id = c_gm.id', 'left')
			->where('division.id', $division_id)
			->where('ciclo_lectivo', $ciclo_lectivo);
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="alumno/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item btn-warning" href="alumno/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '</ul></div>', 'id, division_id');
		} else {
			$this->datatables->add_column('edit', '<a class="btn btn-xs btn-default" href="alumno/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id, division_id');
		}

		echo $this->datatables->generate();
	}

	public function establecer_horarios($escuela_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("division/listar/$escuela_id");
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('curso_model');
		$this->load->model('turno_model');
		$this->load->model('dia_model');
		$data['dias'] = $this->dia_model->get(array('sort_by' => 'dia.id'));
		$this->array_cursos_control = $array_cursos = $this->get_array('curso', 'descripcion', 'id', array(
			'join' => array(
				array('division', "curso.id = division.curso_id AND division.escuela_id=$escuela->id", '')
			),
			'group_by' => 'curso.id',
			'sort_by' => 'curso.descripcion'
			), array('' => 'Seleccionar'));
		$this->array_turno_control = $array_turno = $this->get_array('turno', 'descripcion', 'id', array(
			'join' => array(
				array('division', "turno.id = division.turno_id AND division.escuela_id=$escuela->id", '')
			),
			'group_by' => 'turno.id',
			'sort_by' => 'turno.descripcion'
			), array('' => 'Seleccionar'));

		$model = new stdClass();
		$model->fields = array(
			'escuela' => array('label' => 'Escuela', 'readonly' => TRUE),
			'turno' => array('label' => 'Turno', 'input_type' => 'combo', 'required' => TRUE),
			'cursos' => array('label' => 'Cursos', 'input_type' => 'combo', 'type' => 'multiple', 'required' => TRUE),
			'divisiones' => array('label' => 'Divisiones', 'input_type' => 'combo', 'type' => 'multiple', 'required' => TRUE, 'readonly' => TRUE, 'array' => array('' => 'Seleccione Turno y Cursos para definir divisiones')),
		);
		$this->array_divisiones_control = $this->get_array('division', 'division', 'id', array('escuela_id' => $escuela_id));
		$errors = FALSE;
		$this->set_model_validation_rules($model);
		$this->load->model('horario_model');
		if (isset($_POST) && !empty($_POST)) {
			if ($escuela_id !== $this->input->post('escuela_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$horas = $this->input->post('hora');

			if (!empty($horas)) {
				foreach ($horas as $hora_catedra => $hora) {
					foreach ($hora['dia'] as $dia_id => $dia) {
						$this->form_validation->set_rules("hora[$hora_catedra][dia][$dia_id][desde]", 'Hora desde', 'trim|required|exact_length[5]|validate_time');
						$this->form_validation->set_rules("hora[$hora_catedra][dia][$dia_id][hasta]", 'Hora hasta', 'trim|required|exact_length[5]|validate_time');
					}
				}
			}
			if (!empty($horas) && $this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$divisiones = $this->input->post('divisiones');
				foreach ($divisiones as $division) {
					$horarios_division = $this->horario_model->get(array(
						'division_id' => $division,
					));
					if (!empty($horarios_division)) {
						foreach ($horarios_division as $horario_division) {
							$trans_ok &= $this->horario_model->delete(array('id' => $horario_division->id), FALSE);
						}
					}
					if ($trans_ok) {
						foreach ($horas as $hora_catedra => $hora) {
							foreach ($hora['dia'] as $dia_id => $dia) {
								$trans_ok &= $this->horario_model->create(array(
									'division_id' => $division,
									'hora_catedra' => $hora_catedra,
									'obligaciones' => 1,
									'dia_id' => $dia_id,
									'hora_desde' => $dia['desde'],
									'hora_hasta' => $dia['hasta']
									), FALSE);
							}
						}
					}
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->horario_model->get_msg());
					redirect("division/listar/$escuela->id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar guardar.';
					if ($this->horario_model->get_error())
						$errors .= '<br>' . $this->horario_model->get_error();
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($errors ? $errors : $this->session->flashdata('error')));

		$model->fields['cursos']['array'] = $array_cursos;
		$model->fields['escuela']['value'] = "Esc. $escuela->nombre_largo";
		$model->fields['turno']['array'] = $array_turno;

		$data['fields'] = $this->build_fields($model->fields);
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Establecer Horarios';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = TITLE . ' - Agregar división';
		$this->load_template('division/division_establecer_horarios', $data);
	}

	public function ver_horario($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$division = $this->division_model->get_one($id);
		if (empty($division)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		if ($escuela->nivel_id == 7) {
			redirect("division/ver_horario_cuatrimestre/$id");
		}

		$division->escuela = "Esc. $escuela->nombre_largo";

		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');

		$data['fields'] = $this->build_fields($this->division_model->fields, $division, TRUE);

		$this->load->model('dia_model');
		$dias = $this->dia_model->get(array(
			'join' => array(
				array('horario', 'dia.id=horario.dia_id'),
			),
			'where' => array(
				array('column' => 'horario.division_id', 'value' => $division->id),
			),
			'group_by' => 'dia.id',
			'sort_by' => 'dia.id'
		));
		$data['dias'] = $dias;

		$this->load->model('horario_model');
		$turnos_query = $this->horario_model->get(array(
			'select' => array('horario.hora_catedra', 'horario.dia_id', 'horario.hora_desde', 'horario.hora_hasta', 'horario.obligaciones', 'cargo.id', 'cargo.carga_horaria', 'regimen.regimen_tipo_id', 'COALESCE(materia.descripcion, regimen.descripcion) as materia', 'materia.pareja_pedagogica', 'CONCAT(persona.apellido, \' \', persona.nombre) as persona'),
			'join' => array(
				array('cargo_horario', 'cargo_horario.horario_id = horario.id', 'left', array('cargo_horario.cargo_id')),
				array('cargo', 'cargo.id = cargo_horario.cargo_id', 'left'),
				array('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', 'left'),
				array('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left'),
				array('materia', 'espacio_curricular.materia_id = materia.id', 'left', array('materia.descripcion as materia')),
				array('servicio', "servicio.id = (SELECT id FROM servicio s WHERE s.cargo_id = cargo.id AND ((NOW() BETWEEN s.fecha_alta AND s.fecha_baja) OR NOW() >= s.fecha_alta AND s.fecha_baja IS NULL ) ORDER BY s.fecha_alta DESC LIMIT 1)", 'left'),
				array('persona', 'persona.id = servicio.persona_id', 'left')
			),
			'division_id' => $division->id,
			'sort_by' => 'dia_id, hora_catedra'
		));

		if (empty($dias) || empty($turnos_query)) {
			if ($this->edicion) {
				$this->session->set_flashdata('error', "Debe cargar el horario de la división.");
				redirect("division/agregar_horario/$id", 'refresh');
			} else {
				$this->session->set_flashdata('error', 'La división no tiene horarios asignados y no tiene permisos para agregarlos');
				redirect("division/ver/$id");
			}
		}
		$turno = $turnos_query[0];
		$turno->horarios = array();
		$turno->min_hora_catedra = 1;
		$turno->max_hora_catedra = 1;
		foreach ($turnos_query as $turno_row) {
			if (isset($turno_row->hora_catedra)) {
				$turno->min_hora_catedra = min(array($turno_row->hora_catedra, $turno->min_hora_catedra == 1 ? 99 : $turno->min_hora_catedra));
				$turno->max_hora_catedra = max(array($turno_row->hora_catedra, $turno->max_hora_catedra));
				if (!isset($turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id])) {
					$turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id] = $turno_row;
					$turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id]->cargos = array((empty($turno_row->materia) ? '' : "$turno_row->materia<br>") . "<b>$turno_row->persona</b>");
				} else {
					$turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id]->cargos[] = (empty($turno_row->materia) ? '' : "$turno_row->materia<br>") . "<b>$turno_row->persona</b>";
				}
			}
		}
		$data['turno'] = $turno;
		$data['division'] = $division;
		$data['escuela'] = $escuela;
		$data['title'] = TITLE . ' - Ver división';
		$this->load_template('division/division_ver_horario', $data);
	}

	public function ver_horario_cuatrimestre($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$division = $this->division_model->get_one($id);
		if (empty($division)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$division->escuela = "Esc. $escuela->nombre_largo";

		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');

		$data['fields'] = $this->build_fields($this->division_model->fields, $division, TRUE);

		$this->load->model('dia_model');
		$dias = $this->dia_model->get(array(
			'join' => array(
				array('horario', 'dia.id=horario.dia_id'),
			),
			'where' => array(
				array('column' => 'horario.division_id', 'value' => $division->id),
			),
			'group_by' => 'dia.id',
			'sort_by' => 'dia.id'
		));
		$data['dias'] = $dias;

		$turnos = array();
		for ($i = 1; $i <= 2; $i++) {
			$this->load->model('horario_model');
			$turnos_query = $this->horario_model->get(array(
				'select' => array('horario.hora_catedra', 'horario.dia_id', 'horario.hora_desde', 'horario.hora_hasta', 'horario.obligaciones', 'cargo.id', 'cargo.carga_horaria', 'regimen.regimen_tipo_id', 'COALESCE(materia.descripcion, regimen.descripcion) as materia', 'materia.pareja_pedagogica', 'CONCAT(persona.apellido, \' \', persona.nombre) as persona'),
				'join' => array(
					array('cargo_horario', "cargo_horario.horario_id = horario.id AND cargo_horario.cuatrimestre = $i", 'left', array('cargo_horario.cargo_id')),
					array('cargo', 'cargo.id = cargo_horario.cargo_id', 'left'),
					array('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', 'left'),
					array('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left'),
					array('materia', 'espacio_curricular.materia_id = materia.id', 'left', array('materia.descripcion as materia')),
					array('servicio', "servicio.id = (SELECT id FROM servicio s WHERE s.cargo_id = cargo.id AND ((NOW() BETWEEN s.fecha_alta AND s.fecha_baja) OR NOW() >= s.fecha_alta AND s.fecha_baja IS NULL ) ORDER BY s.fecha_alta DESC LIMIT 1)", 'left'),
					array('persona', 'persona.id = servicio.persona_id', 'left')
				),
				'division_id' => $division->id,
				'sort_by' => 'dia_id, hora_catedra'
			));

			if (empty($dias) || empty($turnos_query)) {
				if ($this->edicion) {
					$this->session->set_flashdata('error', "Debe cargar el horario de la división.");
					redirect("division/agregar_horario/$id", 'refresh');
				} else {
					$this->session->set_flashdata('error', 'La división no tiene horarios asignados y no tiene permisos para agregarlos');
					redirect("division/ver/$id");
				}
			}
			$turno = $turnos_query[0];
			$turno->horarios = array();
			$turno->min_hora_catedra = 1;
			$turno->max_hora_catedra = 1;
			foreach ($turnos_query as $turno_row) {
				if (isset($turno_row->hora_catedra)) {
					$turno->min_hora_catedra = min(array($turno_row->hora_catedra, $turno->min_hora_catedra == 1 ? 99 : $turno->min_hora_catedra));
					$turno->max_hora_catedra = max(array($turno_row->hora_catedra, $turno->max_hora_catedra));
					if (!isset($turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id])) {
						$turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id] = $turno_row;
						$turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id]->cargos = array((empty($turno_row->materia) ? '' : "$turno_row->materia<br>") . "<b>$turno_row->persona</b>");
					} else {
						$turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id]->cargos[] = (empty($turno_row->materia) ? '' : "$turno_row->materia<br>") . "<b>$turno_row->persona</b>";
					}
				}
			}
			$turno->cuatrimestre = $i;
			$turnos[] = $turno;
		}
		$data['turnos'] = $turnos;
		$data['division'] = $division;
		$data['escuela'] = $escuela;
		$data['title'] = TITLE . ' - Ver división';
		$this->load_template('division/division_ver_horario_cuatrimestre', $data);
	}

	public function agregar_horario($id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("division/ver/$id");
		}

		$division = $this->division_model->get_one($id);
		if (empty($division)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$division->escuela = "Esc. $escuela->nombre_largo";

		$this->load->model('dia_model');
		$data['dias'] = $this->dia_model->get(array('sort_by' => 'dia.id'));
		$model = new stdClass();
		$model->fields = array(
			'turno' => array('label' => 'Turno', 'readonly' => TRUE),
			'curso' => array('label' => 'Curso', 'readonly' => TRUE),
			'division' => array('label' => 'División', 'readonly' => TRUE)
		);
		$this->load->model('horario_model');
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('division_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$horas = $this->input->post('hora');

			if (!empty($horas)) {
				foreach ($horas as $hora_catedra => $hora) {
					foreach ($hora['dia'] as $dia_id => $dia) {
						$this->form_validation->set_rules("hora[$hora_catedra][dia][$dia_id][desde]", 'Hora desde', 'trim|required|exact_length[5]|validate_time');
						$this->form_validation->set_rules("hora[$hora_catedra][dia][$dia_id][hasta]", 'Hora hasta', 'trim|required|exact_length[5]|validate_time');
					}
				}
			}
			if (!empty($horas) && $this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$horarios_division = $this->horario_model->get(array(
					'division_id' => $division->id,
				));
				if (!empty($horarios_division)) {
					foreach ($horarios_division as $horario_division) {
						$trans_ok &= $this->horario_model->delete(array('id' => $horario_division->id), FALSE);
					}
				}
				if ($trans_ok) {
					foreach ($horas as $hora_catedra => $hora) {
						foreach ($hora['dia'] as $dia_id => $dia) {
							$trans_ok &= $this->horario_model->create(array(
								'division_id' => $division->id,
								'hora_catedra' => $hora_catedra,
								'obligaciones' => 1,
								'dia_id' => $dia_id,
								'hora_desde' => $dia['desde'],
								'hora_hasta' => $dia['hasta']
								), FALSE);
						}
					}
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->horario_model->get_msg());
					redirect("division/ver_horario/$division->id", 'refresh');
				} else {
					$this->db->trans_rollback();
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->horario_model->get_error() ? $this->horario_model->get_error() : $this->session->flashdata('error')));

		$model->fields['turno']['value'] = $division->turno;
		$model->fields['curso']['value'] = $division->curso;
		$model->fields['division']['value'] = $division->division;

		$data['fields'] = $this->build_fields($model->fields);
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['txt_btn'] = 'Agregar horario';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = TITLE . ' - Agregar horario';
		$this->load_template('division/division_agregar_horario', $data);
	}

	public function editar_horario($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("division/ver_horario/$id");
		}

		$division = $this->division_model->get_one($id);
		if (empty($division)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$division->escuela = "Esc. $escuela->nombre_largo";

		$this->load->model('horario_model');
		$horarios_query = $this->horario_model->get(array(
			'division_id' => $division->id,
			'sort_by' => 'dia_id, hora_catedra'
		));

		$horarios = array();
		$min_hora_catedra = 1;
		$max_hora_catedra = 1;
		if (!empty($horarios_query)) {
			foreach ($horarios_query as $horario_row) {
				if (isset($horario_row->hora_catedra)) {
					$min_hora_catedra = min(array($horario_row->hora_catedra, $min_hora_catedra == 1 ? 99 : $min_hora_catedra));
					$max_hora_catedra = max(array($horario_row->hora_catedra, $max_hora_catedra));
					$horarios[$horario_row->hora_catedra][$horario_row->dia_id] = $horario_row;
				}
			}
		}
		$data['horarios'] = $horarios;
		$data['min_hora_catedra'] = $min_hora_catedra;
		$data['max_hora_catedra'] = $max_hora_catedra;
		$this->load->model('dia_model');
		$data['dias'] = $this->dia_model->get(array('sort_by' => 'id'));
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Turnos de escuelas';
		$this->load_template('division/division_editar_horario', $data);
	}

	public function asignar_horario($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("division/ver_horario/$id");
		}

		$division = $this->division_model->get_one($id);
		if (empty($division)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$division->escuela = "Esc. $escuela->nombre_largo";

		$this->load->model('horario_model');
		if (isset($_POST) && !empty($_POST)) {
			$cargos = $this->input->post('cargo');

			$this->load->model('cargo_horario_model');
			$this->load->model('horario_model');
			$this->load->model('cargo_model');

			$this->db->trans_begin();
			$trans_ok = TRUE;
			if (!empty($cargos)) {
				foreach ($cargos as $horario_id => $cargo_horario) {
					if (!empty($cargo_horario)) {
						$horario_query = $this->cargo_horario_model->get(array('horario_id' => $horario_id));
						$horarios = array();
						if (!empty($horario_query)) {
							foreach ($horario_query as $cargo_horario_row) {
								$horarios[$cargo_horario_row->cargo_id] = $cargo_horario_row;
							}
						}
						foreach ($cargo_horario as $cargo_id) {
							$horario = $this->horario_model->get(array('id' => $horario_id));
							$cargo = $this->cargo_model->get(array('id' => $cargo_id));

							if (!empty($horario) && !empty($cargo)) {
								if (!isset($horarios[$cargo->id])) {
									$trans_ok &= $this->cargo_horario_model->create(array(
										'cargo_id' => $cargo->id,
										'horario_id' => $horario->id
										), FALSE);
								} else {
									$trans_ok &= $this->cargo_horario_model->update(array(
										'id' => $horarios[$cargo->id]->id,
										'horario_id' => $horario->id
										), FALSE);
									unset($horarios[$cargo->id]);
								}
							}
						}
						if (!empty($horarios)) {
							foreach ($horarios as $horario_row) {
								$trans_ok &= $this->cargo_horario_model->delete(array('id' => $horario_row->id), FALSE);
							}
						}
					}
				}
			}

			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->cargo_horario_model->get_msg());
				redirect("division/ver_horario/$id", 'refresh');
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al intentar guardar.';
				if ($this->cargo_horario_model->get_error())
					$errors .= '<br>' . $this->cargo_horario_model->get_error();
			}
		}

		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');

		$data['fields'] = $this->build_fields($this->division_model->fields, $division, TRUE);

		$this->load->model('dia_model');
		$dias = $this->dia_model->get(array(
			'join' => array(
				array('horario', 'dia.id=horario.dia_id'),
			),
			'where' => array(
				array('column' => 'horario.division_id', 'value' => $division->id),
			),
			'group_by' => 'dia.id',
			'sort_by' => 'dia.id'
		));
		$data['dias'] = $dias;

		$turnos_query = $this->horario_model->get(array(
			'join' => array(
				array('cargo_horario', 'cargo_horario.horario_id=horario.id', 'left', array('cargo_horario.cargo_id'))
			),
			'division_id' => $division->id,
			'sort_by' => 'dia_id, hora_catedra'
		));

		if (empty($dias) || empty($turnos_query)) {
			$this->session->set_flashdata('error', "Debe cargar el horario de la división.");
			redirect("division/ver/$id", 'refresh');
		}
		$turno = $turnos_query[0];
		$turno->horarios = array();
		$turno->min_hora_catedra = 1;
		$turno->max_hora_catedra = 1;
		foreach ($turnos_query as $turno_row) {
			if (isset($turno_row->hora_catedra)) {
				$turno->min_hora_catedra = min(array($turno_row->hora_catedra, $turno->min_hora_catedra == 1 ? 99 : $turno->min_hora_catedra));
				$turno->max_hora_catedra = max(array($turno_row->hora_catedra, $turno->max_hora_catedra));
				if (!isset($turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id])) {
					$turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id] = $turno_row;
					$turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id]->cargos = array($turno_row->cargo_id);
				} else {
					$turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id]->cargos[] = $turno_row->cargo_id;
				}
			}
		}
		$data['turno'] = $turno;
		$data['division'] = $division;
		$this->load->model('cargo_model');
		$cargos = $this->cargo_model->get(array(
			'select' => array('cargo.id', 'cargo.carga_horaria', 'regimen.regimen_tipo_id',
				"(SELECT SUM(h.obligaciones) FROM horario h join cargo_horario ch on ch.horario_id=h.id WHERE h.division_id!=$division->id AND ch.cargo_id=cargo.id ) hs",
				"(SELECT GROUP_CONCAT(CONCAT(c.descripcion, ' ', d.division, ': ',LEFT(h.hora_desde,5),' a ', LEFT(h.hora_hasta,5), ' (',h.obligaciones,')')) FROM horario h join cargo_horario ch on ch.horario_id=h.id JOIN division d ON h.division_id=d.id JOIN curso c ON d.curso_id=c.id WHERE h.division_id!=$division->id AND ch.cargo_id=cargo.id ) detalle",
				'COALESCE(materia.descripcion, regimen.descripcion) as materia', 'materia.pareja_pedagogica', 'CONCAT(persona.apellido, \' \', persona.nombre) as persona'),
			'join' => array(
				array('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', ''),
				array('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left'),
				array('materia', 'materia.id = espacio_curricular.materia_id', 'left'),
				array('servicio', "servicio.id = (SELECT id FROM servicio s WHERE s.cargo_id = cargo.id AND ((NOW() BETWEEN s.fecha_alta AND s.fecha_baja) OR NOW() >= s.fecha_alta AND s.fecha_baja IS NULL ) ORDER BY s.fecha_alta DESC LIMIT 1)", 'left'),
				array('persona', 'persona.id = servicio.persona_id', 'left')
			),
			'where' => array("(division_id = $division->id OR (division_id IS NULL AND espacio_curricular_id IS NOT NULL)) AND cargo.fecha_hasta IS NULL"),
			'escuela_id' => $escuela->id
		));
		if (empty($cargos)) {
			$this->session->set_flashdata('error', "Debe tener cargos con espacios curriculares asignados en la división para poder cargar el horario.");
			redirect("division/ver/$id", 'refresh');
		}
		$data['cargos'] = $cargos;
		$options_cargo = array('' => 'Seleccionar');
		$options_cargo_pp = array('' => 'Seleccionar');
		foreach ($cargos as $cargo) {
			if ($cargo->pareja_pedagogica === 'Si') {
				$options_cargo[$cargo->id] = "PP - $cargo->materia" . (empty($cargo->persona) ? '' : " - $cargo->persona");
				$options_cargo_pp[$cargo->id] = "PP - $cargo->materia" . (empty($cargo->persona) ? '' : " - $cargo->persona");
			} else {
				$options_cargo[$cargo->id] = $cargo->materia . (empty($cargo->persona) ? '' : " - $cargo->persona");
			}
		}
		$data['options_cargo'] = $options_cargo;
		$data['options_cargo_pp'] = $options_cargo_pp;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Guardar';
		$data['title'] = TITLE . ' - Asignar horarios a División';
		$this->load_template('division/division_asignar_horario', $data);
	}

	public function asignar_horario_cuatrimestre($id = NULL, $cuatrimestre = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id) || $cuatrimestre == NULL || !ctype_digit($cuatrimestre)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("division/ver_horario/$id");
		}
		$division = $this->division_model->get_one($id);
		if (empty($division)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$division->escuela = "Esc. $escuela->nombre_largo";
		$this->load->model('horario_model');

		if (isset($_POST) && !empty($_POST)) {
			if ($cuatrimestre != $this->input->post('cuatrimestre')) {
				show_error('Esta solicitud no paso el control de seguridad');
			}
			$cargos = $this->input->post('cargo');
			$this->load->model('cargo_horario_model');
			$this->load->model('horario_model');
			$this->load->model('cargo_model');
			$this->db->trans_begin();
			$trans_ok = TRUE;
			if (!empty($cargos)) {
				foreach ($cargos as $horario_id => $cargo_horario) {
					if (!empty($cargo_horario)) {
						$horario_query = $this->cargo_horario_model->get(array(
							'horario_id' => $horario_id,
							'cuatrimestre' => $cuatrimestre
						));
						$horarios = array();
						if (!empty($horario_query)) {
							foreach ($horario_query as $cargo_horario_row) {
								$horarios[$cargo_horario_row->cargo_id] = $cargo_horario_row;
							}
						}
						foreach ($cargo_horario as $cargo_id) {
							$horario = $this->horario_model->get(array('id' => $horario_id));
							$cargo = $this->cargo_model->get(array('id' => $cargo_id));

							if (!empty($horario) && !empty($cargo)) {
								if (!isset($horarios[$cargo->id])) {
									$trans_ok &= $this->cargo_horario_model->create(array(
										'cargo_id' => $cargo->id,
										'horario_id' => $horario->id,
										'cuatrimestre' => $this->input->post('cuatrimestre')
										), FALSE);
								} else {
									$trans_ok &= $this->cargo_horario_model->update(array(
										'id' => $horarios[$cargo->id]->id,
										'horario_id' => $horario->id,
										), FALSE);
									unset($horarios[$cargo->id]);
								}
							}
						}
						if (!empty($horarios)) {
							foreach ($horarios as $horario_row) {
								$trans_ok &= $this->cargo_horario_model->delete(array('id' => $horario_row->id), FALSE);
							}
						}
					}
				}
			}
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->cargo_horario_model->get_msg());
				redirect("division/ver_horario_cuatrimestre/$id", 'refresh');
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al intentar guardar.';
				if ($this->cargo_horario_model->get_error())
					$errors .= '<br>' . $this->cargo_horario_model->get_error();
			}
		}
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['fields'] = $this->build_fields($this->division_model->fields, $division, TRUE);
		$this->load->model('dia_model');
		$dias = $this->dia_model->get(array(
			'join' => array(
				array('horario', 'dia.id=horario.dia_id'),
			),
			'where' => array(
				array('column' => 'horario.division_id', 'value' => $division->id),
			),
			'group_by' => 'dia.id',
			'sort_by' => 'dia.id'
		));
		$data['dias'] = $dias;
		$turnos_query = $this->horario_model->get(array(
			'join' => array(
				array('cargo_horario', "cargo_horario.horario_id=horario.id AND cargo_horario.cuatrimestre = $cuatrimestre", 'left', array('cargo_horario.cargo_id'))
			),
			'division_id' => $division->id,
			'sort_by' => 'dia_id, hora_catedra'
		));
		if (empty($dias) || empty($turnos_query)) {
			$this->session->set_flashdata('error', "Debe cargar el horario de la división.");
			redirect("division/ver/$id", 'refresh');
		}
		$turno = $turnos_query[0];
		$turno->horarios = array();
		$turno->min_hora_catedra = 1;
		$turno->max_hora_catedra = 1;
		foreach ($turnos_query as $turno_row) {
			if (isset($turno_row->hora_catedra)) {
				$turno->min_hora_catedra = min(array($turno_row->hora_catedra, $turno->min_hora_catedra == 1 ? 99 : $turno->min_hora_catedra));
				$turno->max_hora_catedra = max(array($turno_row->hora_catedra, $turno->max_hora_catedra));
				if (!isset($turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id])) {
					$turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id] = $turno_row;
					$turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id]->cargos = array($turno_row->cargo_id);
				} else {
					$turno->horarios[$turno_row->hora_catedra][$turno_row->dia_id]->cargos[] = $turno_row->cargo_id;
				}
			}
		}
		$data['turno'] = $turno;
		$data['division'] = $division;
		$this->load->model('cargo_model');
		$cargos = $this->cargo_model->get(array(
			'select' => array('cargo.id', 'cargo.carga_horaria', 'regimen.regimen_tipo_id',
				"(SELECT SUM(h.obligaciones) FROM horario h join cargo_horario ch on ch.horario_id=h.id WHERE h.division_id!=$division->id AND ch.cargo_id=cargo.id ) hs",
				"(SELECT GROUP_CONCAT(CONCAT(c.descripcion, ' ', d.division, ': ',LEFT(h.hora_desde,5),' a ', LEFT(h.hora_hasta,5), ' (',h.obligaciones,')')) FROM horario h join cargo_horario ch on ch.horario_id=h.id JOIN division d ON h.division_id=d.id JOIN curso c ON d.curso_id=c.id WHERE h.division_id!=$division->id AND ch.cargo_id=cargo.id ) detalle",
				'COALESCE(materia.descripcion, regimen.descripcion) as materia', 'materia.pareja_pedagogica', 'CONCAT(persona.apellido, \' \', persona.nombre) as persona'),
			'join' => array(
				array('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', ''),
				array('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left'),
				array('materia', 'materia.id = espacio_curricular.materia_id', 'left'),
				array('servicio', "servicio.id = (SELECT id FROM servicio s WHERE s.cargo_id = cargo.id AND ((NOW() BETWEEN s.fecha_alta AND s.fecha_baja) OR NOW() >= s.fecha_alta AND s.fecha_baja IS NULL ) ORDER BY s.fecha_alta DESC LIMIT 1)", 'left'),
				array('persona', 'persona.id = servicio.persona_id', 'left')
			),
			'where' => array("(division_id = $division->id OR (division_id IS NULL AND espacio_curricular_id IS NOT NULL)) AND cargo.fecha_hasta IS NULL AND cargo.cuatrimestre IN(0, $cuatrimestre)"),
			'escuela_id' => $escuela->id
		));
		if (empty($cargos)) {
			$this->session->set_flashdata('error', "Debe tener cargos con espacios curriculares asignados en la división para poder cargar el horario.");
			redirect("division/ver/$id", 'refresh');
		}
		$data['cargos'] = $cargos;
		$options_cargo = array('' => 'Seleccionar');
		$options_cargo_pp = array('' => 'Seleccionar');
		foreach ($cargos as $cargo) {
			if ($cargo->pareja_pedagogica === 'Si') {
				$options_cargo[$cargo->id] = "PP - $cargo->materia" . (empty($cargo->persona) ? '' : " - $cargo->persona");
				$options_cargo_pp[$cargo->id] = "PP - $cargo->materia" . (empty($cargo->persona) ? '' : " - $cargo->persona");
			} else {
				$options_cargo[$cargo->id] = $cargo->materia . (empty($cargo->persona) ? '' : " - $cargo->persona");
			}
		}
		$data['cuatrimestre'] = $cuatrimestre;
		$data['options_cargo'] = $options_cargo;
		$data['options_cargo_pp'] = $options_cargo_pp;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Guardar';
		$data['title'] = TITLE . ' - Asignar horarios a División';
		$this->load_template('division/division_asignar_horario_cuatrimestre', $data);
	}

	public function mover_alumnos($division_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ESCUELA_ALUM) {
			$this->edicion = TRUE;
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("division/mover_alumnos/$division_id/$ciclo_lectivo", 'refresh');
		}

		$this->load->model('division_model');
		$division = $this->division_model->get_one($division_id);
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

		$tableData = array(
			'columns' => array(
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Alumno', 'data' => 'persona', 'width' => 25, 'class' => 'text-sm'),
				array('label' => 'Sexo', 'data' => 'sexo', 'width' => 5, 'class' => 'dt-body-center'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Div', 'data' => 'division', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Condición', 'data' => 'condicion', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'date', 'width' => 10),
				array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'date', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(4, 'asc'), array(1, 'asc'), array(2, 'asc'), array(5, 'asc')),
			'table_id' => 'alumno_table',
			'source_url' => "division/alumnos_mover_data/$division_id/$ciclo_lectivo",
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_table",
			'footer' => TRUE,
			'disablePagination' => TRUE,
			'dom' => 'rt'
		);

		$model = new stdClass();
		$model->fields = array(
			'alumno_division[]' => array('label' => 'Alumnos', 'required' => TRUE),
			'division' => array('label' => 'División', 'input_type' => 'combo', 'required' => TRUE),
			'fecha_hasta' => array('label' => 'Fecha (d/m/a)', 'type' => 'date', 'required' => TRUE),
			'causa_salida' => array('label' => 'Causa de salida', 'input_type' => 'combo', 'required' => TRUE)
		);

		$this->load->model('alumno_division_model');
		$this->load->model('causa_salida_model');
		$this->load->model('division_model');
		$this->load->model('alumno_movimiento_model');
		$this->load->model('alumno_movimiento_detalle_model');

		$this->array_causa_salida_control = $array_causa_salida = $this->get_array('causa_salida', 'descripcion', 'id', null, array('' => '-- Seleccionar motivo de salida --'));

		$this->array_division_control = $array_division = $this->get_array('division', 'division', 'id', array(
			'select' => array('division.id', 'CONCAT(curso.descripcion, \' \', division.division) as division'),
			'join' => array(
				array('curso', 'curso.id=division.curso_id'),
				array('escuela', 'escuela.id=division.escuela_id'),
			),
			'where' => array('division.fecha_baja IS NULL', array('column' => ((!empty($escuela->escuela_id)) ? 'escuela.id' : 'COALESCE(escuela.escuela_id,escuela.id)'), 'value' => $escuela->id)),
			'sort_by' => 'curso.descripcion, division.division'
			), array('' => '-- Seleccionar división destino --'));

		$this->form_validation->set_rules('ciclo_lectivo_nuevo', 'Ciclo lectivo nuevo', 'integer|required');
		$this->set_model_validation_rules($model);
		if ($this->form_validation->run() === TRUE) {
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$alumno_division_ids = $this->input->post('alumno_division');
			$count = count($alumno_division_ids);
			$nueva_division = $this->division_model->get_one($this->input->post('division'));
			$trans_ok &= $this->alumno_movimiento_model->create(array(
				'fecha' => date("Y-m-d H:m:s"),
				'escuela_id' => $escuela->id,
				'tipo' => 'Mover',
				'movimiento' => "Movimiento desde división $division->curso $division->division (C.L. $ciclo_lectivo) a división $nueva_division->curso $nueva_division->division (C.L. " . $this->input->post('ciclo_lectivo_nuevo') . ")",
				'causa_salida_id' => 9,
				'division_origen_id' => $division_id,
				'ciclo_lectivo_origen' => $ciclo_lectivo,
				'causa_entrada_id' => 5,
				'division_destino_id' => $this->input->post('division'),
				'ciclo_lectivo_destino' => $this->input->post('ciclo_lectivo_nuevo')
				), FALSE);
			$alumno_movimiento_id = $this->alumno_movimiento_model->get_row_id();
			for ($i = 0; $i < $count; $i++) {
				if ($trans_ok) {
					$trans_ok &= $this->alumno_division_model->update(array(
						'id' => $alumno_division_ids[$i],
						'fecha_hasta' => $this->get_date_sql('fecha_hasta'),
						'estado_id' => 3,
						'causa_salida_id' => 9
						), FALSE);
					$trans_ok &= $this->alumno_movimiento_detalle_model->create(array(
						'alumno_movimiento_id' => $alumno_movimiento_id,
						'alumno_division_id' => $alumno_division_ids[$i],
						'accion' => 'Egreso'
						), FALSE);
					$alumno_division = $this->alumno_division_model->get_one($alumno_division_ids[$i]);
					if ($trans_ok) {
						$trans_ok &= $this->alumno_division_model->create(array(
							'alumno_id' => $alumno_division->alumno_id,
							'legajo' => $alumno_division->legajo,
							'division_id' => $this->input->post('division'),
							'fecha_desde' => $this->get_date_sql('fecha_hasta'),
							'ciclo_lectivo' => $this->input->post('ciclo_lectivo_nuevo'),
							'estado_id' => 1,
							'causa_entrada_id' => 5
							), FALSE);
						$nuevo_alumno_division_id = $this->alumno_division_model->get_row_id();
						$trans_ok &= $this->alumno_movimiento_detalle_model->create(array(
							'alumno_movimiento_id' => $alumno_movimiento_id,
							'alumno_division_id' => $nuevo_alumno_division_id,
							'accion' => 'Ingreso'
							), FALSE);
					}
				}
			}

			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Movimiento realizado satisfactoriamente');
				redirect("division/mover_alumnos/$division_id/$ciclo_lectivo", 'refresh');
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al intentar actualizar.';
				if ($this->alumno_division_model->get_error())
					$errors .= '<br>' . $this->alumno_division_model->get_error();
			}
		}

		$data['error'] = (validation_errors() ? validation_errors() : ($this->alumno_division_model->get_error() ? $this->alumno_division_model->get_error() : $this->session->flashdata('error')));

		$model->fields['causa_salida']['array'] = $array_causa_salida;
		$model->fields['division']['array'] = $array_division;

		$alumnos_transicion = $this->division_model->get_alumnos_transicion($division->id);

		$data['alumnos_transicion'] = $alumnos_transicion;
		$data['fields'] = $this->build_fields($model->fields);
		$data['anno'] = date("Y");
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - División mover';
//$data['txt_btn'] = 'Mover alumnos';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('division/division_alumnos_mover', $data);
	}

	public function alumnos_mover_data($division_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ESCUELA_ALUM) {
			$this->edicion = TRUE;
		}
		$division = $this->division_model->get(array('id' => $division_id));
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

		$this->datatables
			->select("alumno_division.id,COALESCE(CONCAT(CASE WHEN sexo.id=1 THEN 'M' WHEN sexo.id=2 THEN 'F' ELSE '' END), ' ') as sexo, alumno.observaciones, alumno_division.fecha_desde, alumno_division.fecha_hasta, CONCAT(documento_tipo.descripcion_corta,' ', persona.documento) as documento, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, CASE curso.grado_multiple WHEN 'Si' THEN CONCAT(division.division, ' (', COALESCE(c_gm.descripcion, ''), ')') ELSE division.division END as division, curso.descripcion as curso, division.escuela_id, alumno_division.condicion as condicion")
			->unset_column('id')
			->from('alumno')
			->join('persona', 'persona.id = alumno.persona_id')
			->join('sexo', 'sexo.id = persona.sexo_id', 'left')
			->join('alumno_division', 'alumno_division.alumno_id = alumno.id')
			->join('division', 'division.id = alumno_division.division_id')
			->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id')
			->join('curso', 'division.curso_id = curso.id')
			->join('curso c_gm', 'alumno_division.curso_id = c_gm.id', 'left')
			->where('alumno_division.fecha_hasta IS NULL')
			->where('division.id', $division_id)
			->where('ciclo_lectivo', $ciclo_lectivo);
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<input type="checkbox" name="alumno_division[]" value="$1">', 'id');
		} else {
			$this->datatables->add_column('edit', '', '');
		}
		echo $this->datatables->generate();
	}

	public function sacar_alumnos($division_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ESCUELA_ALUM) {
			$this->edicion = TRUE;
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("division/sacar_alumnos/$division_id/$ciclo_lectivo", 'refresh');
		}

		$this->load->model('division_model');
		$division = $this->division_model->get_one($division_id);
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

		$tableData = array(
			'columns' => array(
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Alumno', 'data' => 'persona', 'width' => 25, 'class' => 'text-sm'),
				array('label' => 'Sexo', 'data' => 'sexo', 'width' => 5, 'class' => 'dt-body-center'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Div', 'data' => 'division', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Condición', 'data' => 'condicion', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'date', 'width' => 10),
				array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'date', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(4, 'asc'), array(1, 'asc'), array(2, 'asc'), array(5, 'asc')),
			'table_id' => 'alumno_table',
			'source_url' => "division/alumnos_mover_data/$division_id/$ciclo_lectivo",
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_table",
			'footer' => TRUE,
			'disablePagination' => TRUE,
			'dom' => 'rt'
		);

		$model = new stdClass();
		$model->fields = array(
			'alumno_division[]' => array('label' => 'Alumnos', 'required' => TRUE),
			'division' => array('label' => 'División', 'input_type' => 'combo', 'required' => TRUE),
			'fecha_hasta' => array('label' => 'Fecha de salida', 'type' => 'date', 'required' => TRUE),
			'causa_salida' => array('label' => 'Causa de salida', 'input_type' => 'combo', 'required' => TRUE),
			'ciclo_lectivo' => array('label' => 'Ciclo Lectivo', 'type' => 'integer', 'maxlength' => '11', 'required' => TRUE)
		);

		$this->load->model('alumno_division_model');
		$this->load->model('causa_salida_model');
		$this->load->model('division_model');
		$this->load->model('alumno_movimiento_model');
		$this->load->model('alumno_movimiento_detalle_model');

		$this->array_causa_salida_control = $array_causa_salida = $this->get_array('causa_salida', 'descripcion', 'id', array(
			'id !=' => 1,
			'salida_escuela' => "Si"), array('' => '-- Seleccionar motivo de salida --'));
		$this->array_division_control = $array_division = $this->get_array('division', 'division', 'id', array(
			'select' => array('division.id', 'CONCAT(curso.descripcion, \' \', division.division) as division'),
			'join' => array(array('curso', 'curso.id=division.curso_id')),
			'escuela_id' => $division->escuela_id,
			'sort_by' => 'curso.descripcion, division.division'
			), array('' => '-- Seleccionar división --'));

		$this->set_model_validation_rules($model);
		if ($this->form_validation->run() === TRUE) {
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$alumno_division_ids = $this->input->post('alumno_division');
			$count = count($alumno_division_ids);
			$trans_ok &= $this->alumno_movimiento_model->create(array(
				'fecha' => date("Y-m-d H:m:s"),
				'escuela_id' => $escuela->id,
				'tipo' => 'Retirar',
				'movimiento' => "Retirando desde división $division->curso $division->division (C.L. $ciclo_lectivo)",
				'causa_salida_id' => $this->input->post('causa_salida'),
				'division_origen_id' => $division_id,
				'ciclo_lectivo_origen' => $ciclo_lectivo
				), FALSE);
			$alumno_movimiento_id = $this->alumno_movimiento_model->get_row_id();
			for ($i = 0; $i < $count; $i++) {
				if ($trans_ok) {
					$trans_ok &= $this->alumno_division_model->update(array(
						'id' => $alumno_division_ids[$i],
						'fecha_hasta' => $this->get_date_sql('fecha_hasta'),
						'ciclo_lectivo' => $this->input->post('ciclo_lectivo'),
						'estado_id' => 3,
						'causa_salida_id' => $this->input->post('causa_salida')
						), FALSE);
					$trans_ok &= $this->alumno_movimiento_detalle_model->create(array(
						'alumno_movimiento_id' => $alumno_movimiento_id,
						'alumno_division_id' => $alumno_division_ids[$i],
						'accion' => 'Egreso'
						), FALSE);
				}
			}
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Movimiento realizado satisfactoriamente');
				redirect("division/sacar_alumnos/$division_id/$ciclo_lectivo", 'refresh');
			} else {
				$this->db->trans_rollback();
			}
		}

		$data['error'] = (validation_errors() ? validation_errors() : ($this->alumno_division_model->get_error() ? $this->alumno_division_model->get_error() : $this->session->flashdata('error')));

		asort($array_causa_salida);
		$model->fields['causa_salida']['array'] = $array_causa_salida;
		$model->fields['division']['array'] = $array_division;

		$alumnos_transicion = $this->division_model->get_alumnos_transicion($division->id);


		$data['fields'] = $this->build_fields($model->fields);

		$data['alumnos_transicion'] = $alumnos_transicion;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - División sacar';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('division/division_alumnos_sacar', $data);
	}

	public function pase_alumnos($division_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ESCUELA_ALUM) {
			$this->edicion = TRUE;
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("division/sacar_alumnos/$division_id/$ciclo_lectivo", 'refresh');
		}

		$this->load->model('alumno_division_model');
		$this->load->model('causa_salida_model');
		$this->load->model('division_model');
		$this->load->model('alumno_pase_model');
		$this->load->model('alumno_movimiento_model');
		$this->load->model('alumno_movimiento_detalle_model');

		$division = $this->division_model->get_one($division_id);
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

		$tableData = array(
			'columns' => array(
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Alumno', 'data' => 'persona', 'width' => 25, 'class' => 'text-sm'),
				array('label' => 'Sexo', 'data' => 'sexo', 'width' => 5, 'class' => 'dt-body-center'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Div', 'data' => 'division', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Condición', 'data' => 'condicion', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'date', 'width' => 10),
				array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'date', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(4, 'asc'), array(1, 'asc'), array(2, 'asc'), array(5, 'asc')),
			'table_id' => 'alumno_table',
			'source_url' => "division/alumnos_mover_data/$division_id/$ciclo_lectivo",
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_table",
			'footer' => TRUE,
			'disablePagination' => TRUE,
			'dom' => 'rt'
		);

		$model = new stdClass();
		$model->fields = array(
			'alumno_division[]' => array('label' => 'Alumnos', 'required' => TRUE),
			'fecha_pase' => array('label' => 'Fecha de Pase (d/m/a)', 'type' => 'date', 'required' => TRUE),
			'escuela_destino' => array('label' => 'Escuela de destino', 'input_type' => 'combo', 'required' => TRUE),
			'ciclo_lectivo' => array('label' => 'Ciclo Lectivo', 'type' => 'integer', 'maxlength' => '11', 'required' => TRUE)
		);
		$this->array_escuela_destino_control = $array_escuela_destino = $this->get_array('escuela', 'nombre_largo', 'id', array(
			'join' => array(array('nivel', "escuela.nivel_id=nivel.id AND (nivel.formal='Si' OR nivel.id = 6)")),
			'id !=' => $escuela->id,
			'sort_by' => 'escuela.numero, escuela.anexo'
			), array('' => '-- Seleccionar escuela destino --'));
		$model->fields['escuela_destino']['array'] = $array_escuela_destino;

		$this->set_model_validation_rules($model);
		if ($this->form_validation->run() === TRUE) {
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$alumno_division_ids = $this->input->post('alumno_division');
			$count = count($alumno_division_ids);
			$escuela_destino = $this->escuela_model->get_one($this->input->post('escuela_destino'));
			$trans_ok &= $this->alumno_movimiento_model->create(array(
				'fecha' => date("Y-m-d H:m:s"),
				'escuela_id' => $escuela->id,
				'tipo' => 'Pase',
				'movimiento' => "Pase desde división $division->curso $division->division (C.L. $ciclo_lectivo) a la escuela $escuela_destino->nombre_largo",
				'causa_salida_id' => 1,
				'division_origen_id' => $division_id,
				'ciclo_lectivo_origen' => $ciclo_lectivo
				), FALSE);
			$alumno_movimiento_id = $this->alumno_movimiento_model->get_row_id();
			for ($i = 0; $i < $count; $i++) {
				if ($trans_ok) {
					$alumno_division = $this->alumno_division_model->get_one($alumno_division_ids[$i]);
					$trans_ok &= $this->alumno_pase_model->create(array(
						'alumno_id' => $alumno_division->alumno_id,
						'escuela_origen_id' => $escuela->id,
						'escuela_destino_id' => $this->input->post('escuela_destino'),
						'fecha_pase' => $this->get_date_sql('fecha_pase'),
						'estado' => 'Pendiente'
						), FALSE);
					$trans_ok &= $this->alumno_division_model->update(array(
						'id' => $alumno_division->id,
						'fecha_hasta' => $this->get_date_sql('fecha_pase'),
						'estado_id' => 3,
						'causa_salida_id' => 1
						), FALSE);
					$trans_ok &= $this->alumno_movimiento_detalle_model->create(array(
						'alumno_movimiento_id' => $alumno_movimiento_id,
						'alumno_division_id' => $alumno_division->id,
						'accion' => 'Egreso'
						), FALSE);
				}
			}
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Movimiento realizado satisfactoriamente');
				redirect("division/pase_alumnos/$division_id/$ciclo_lectivo", 'refresh');
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al realizar la acción.';
				if ($this->alumno_division_model->get_error())
					$errors .= '<br>' . $this->alumno_division_model->get_error();
				if ($this->alumno_pase_model->get_error())
					$errors .= '<br>' . $this->alumno_pase_model->get_error();
			}
		}

		$alumnos_transicion = $this->division_model->get_alumnos_transicion($division->id);

		$data['alumnos_transicion'] = $alumnos_transicion;
		$data['error'] = (validation_errors() ? validation_errors() : (!empty($errors) ? $errors : $this->session->flashdata('error')));
		$data['fields'] = $this->build_fields($model->fields);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - División sacar';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('division/division_alumnos_pase', $data);
	}

	public function cambiar_cl_alumnos($division_id = NULL, $ciclo_lectivo = NULL) {
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
		redirect("division/alumnos/$division_id/$ciclo_lectivo", 'refresh');
	}

	public function mover($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("division/listar/$escuela_id");
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->array_anexo_control = $array_anexo = $this->get_array('escuela', 'nombre_largo', 'id', array('numero' => $escuela->numero, 'id !=' => $escuela->id, 'sort_by' => 'anexo'), array('' => '-- Seleccione anexo --'));
		unset($this->array_anexo_control['']);
		$model = new stdClass();
		$model->fields = array(
			'anexo' => array('label' => 'Anexo', 'input_type' => 'combo', 'array' => $array_anexo, 'required' => TRUE)
		);
		$this->set_model_validation_rules($model);
		$this->form_validation->set_rules('division[]', 'Divisiones', 'integer|required');
		if ($this->form_validation->run() === TRUE) {
			$divisiones_id = $this->input->post('division');
			if (empty($divisiones_id)) {
				$this->session->set_flashdata('error', 'Debe seleccionar las divisiones a mover');
				redirect("division/mover/$escuela->id", 'refresh');
			}

			$divisiones = $this->division_model->get(array(
				'escuela_id' => $escuela->id,
				'where' => array('id IN (' . implode(',', $divisiones_id) . ')')
			));
			if (!empty($divisiones) && count($divisiones) === count($divisiones_id)) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$this->load->model('cargo_model');
				$cargos = $this->cargo_model->get(array('select' => 'id', 'where' => array('division_id IN (' . implode(',', $divisiones_id) . ')')));
				if (!empty($cargos)) {
					foreach ($cargos as $cargo) {
						$trans_ok &= $this->cargo_model->update(array('id' => $cargo->id, 'escuela_id' => $this->input->post('anexo')), FALSE);
					}
				}
				foreach ($divisiones as $division) {
					$trans_ok &= $this->division_model->update(array('id' => $division->id, 'escuela_id' => $this->input->post('anexo')), FALSE);
				}

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', 'Divisiones movidas exitosamente');
					redirect("division/mover/$escuela->id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar mover divisiones.';
					if ($this->cargo_model->get_error())
						$errors .= '<br>' . $this->cargo_model->get_error();
					if ($this->division_model->get_error())
						$errors .= '<br>' . $this->division_model->get_error();
					$this->session->set_flashdata('error', $errors);
					redirect("division/mover/$escuela->id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', 'Ocurrió un error al intentar mover divisiones');
				redirect("division/mover/$escuela->id", 'refresh');
			}
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Curso', 'data' => 'curso', 'width' => 5),
				array('label' => 'División', 'data' => 'division', 'width' => 15),
				array('label' => 'Turno', 'data' => 'turno', 'width' => 10),
				array('label' => 'Carrera', 'data' => 'carrera', 'width' => 25),
				array('label' => 'Modalidad', 'data' => 'modalidad', 'width' => 15),
				array('label' => 'Fecha Alta', 'data' => 'fecha_alta', 'render' => 'date', 'width' => 10),
				array('label' => 'Fecha Baja', 'data' => 'fecha_baja', 'render' => 'date', 'width' => 10),
				array('label' => '', 'data' => 'select', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(0, 'asc'), array(1, 'asc')),
			'table_id' => 'division_table',
			'source_url' => "division/mover_data/$escuela_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'reuse_var' => TRUE,
			'initComplete' => "complete_division_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);

		$data['fields'] = $this->build_fields($model->fields);
		$data['escuela'] = $escuela;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Mover divisiones';
		$this->load_template('division/division_mover', $data);
	}

	public function mover_data($escuela_id, $rol_codigo, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('division.id, division.escuela_id, division.turno_id, division.curso_id, division.division, division.carrera_id, division.fecha_alta, division.fecha_baja, carrera.descripcion as carrera, curso.descripcion as curso, modalidad.descripcion as modalidad, escuela.nombre as escuela, turno.descripcion as turno, COUNT(horario.id) as horarios')
			->unset_column('id')
			->from('division')
			->join('horario', 'horario.division_id = division.id', 'left')
			->join('carrera', 'carrera.id = division.carrera_id', 'left')
			->join('curso', 'curso.id = division.curso_id', 'left')
			->join('escuela', 'escuela.id = division.escuela_id', 'left')
			->join('turno', 'turno.id = division.turno_id', 'left')
			->join('modalidad', 'modalidad.id = division.modalidad_id', 'left')
			->where('division.escuela_id', $escuela_id)
			->group_by('division.id')
			->add_column('select', '<input type="checkbox" name="division[]" value="$1">', 'id');

		echo $this->datatables->generate();
	}

	public function imprimir_curso_division($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}

		$division = $this->division_model->get_one($id);
		if (empty($division)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_model');
		$this->load->model('cargo_model');

		$alumnos = $this->alumno_model->get_alumnos_division($id);
		$cargos = $this->cargo_model->get_cargos_division($id);

		$division->escuela = "Esc. $escuela->nombre_largo";

		$data['error'] = $this->session->flashdata('error');

		$data['fields'] = $this->build_fields($this->division_model->fields, $division, TRUE);

		$data['cargos'] = $cargos;
		$data['alumnos'] = $alumnos;
		$data['division'] = $division;
		$data['escuela'] = $escuela;

		$fecha_actual = date('d/m/Y');
		$curydiv = "$division->curso $division->division";

		$content = $this->load->view('division/division_alumnos_impresion_parcial', $data, TRUE);

		$this->load->helper('mpdf');

		$watermark = '';

		exportMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Planilla de Alumnos', 'Planilla de alumnos - Esc. Nº ' . $escuela->numero . ' "' . $escuela->nombre . '" - Curso y Division: ' . $curydiv . ' - Fecha de impresion: ' . $fecha_actual, '|{PAGENO} de {nb}|', '', $watermark, 'I', FALSE, FALSE);
	}

	public function excel($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}

		$division = $this->division_model->get_one($id);
		if (empty($division)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$campos = array(
			'A' => array('Curso - Division', 25),
			'B' => array('C. lectivo', 10),
			'C' => array('Documento', 10),
			'D' => array('Persona', 35),
			'E' => array('F. Nacimiento', 15),
			'F' => array('Sexo', 15),
			'G' => array('Desde', 15),
			'H' => array('Edad', 15)
		);

		$alumnos = $this->db->select('CONCAT(c.descripcion, " ", d.division) as division2, ad.ciclo_lectivo, p.documento, CONCAT(COALESCE(p.apellido, \'\'), \', \', COALESCE(p.nombre, \'\')) as persona, p.fecha_nacimiento, s.descripcion as sexo, ad.fecha_desde')
				->from('alumno al')
				->join('persona p', 'p.id = al.persona_id', 'left')
				->join('documento_tipo dt', 'dt.id = p.documento_tipo_id', 'left')
				->join('sexo s', 's.id = p.sexo_id', 'left')
				->join('alumno_division ad', 'ad.alumno_id = al.id', 'left')
				->join('division d', 'd.id = ad.division_id', 'left')
				->join('curso c', 'd.curso_id = c.id', 'left')
				->where('ad.fecha_hasta IS NULL')
				->where('ad.division_id', $id)
				->group_by('al.id')
				->get()->result_array();

		$i = 0;
		foreach ($alumnos as $campo) {
			$fecha_nacimiento = $campo['fecha_nacimiento'];
			$datetime1 = new DateTime($fecha_nacimiento);
			$datetime2 = new DateTime();
			$interval = $datetime1->diff($datetime2);
			$edad = $interval->y;
			$campo['edad'] = $edad;
			$alumnos[$i]['edad'] = $edad;
			$i++;
		}

		$escuela_nombre = str_replace("/", " Anexo ", "$escuela->nombre_corto");
		if (!empty($alumnos)) {
			$this->exportar_excel(array('title' => "Alumnos $escuela_nombre"), $campos, $alumnos);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect("division/ver/$id", 'refresh');
		}
	}

	public function persona_buscar_listar_modal($division_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}

		$this->load->model('division_model');
		$division = $this->division_model->get_one($division_id);
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

		$data['fields'] = $this->build_fields($model_busqueda->fields);
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Buscar alumno a agregar';
		$this->load->view('division/division_persona_buscar_listar_modal', $data);
	}

	public function divisiones_con_carrera_por_escuela($supervision_id = NULL) {
		$this->load->model('supervision_model');
		$supervision = $this->supervision_model->get_one($supervision_id);
		$data['supervision'] = $supervision;
		$this->load->model('nivel_model');
		$nivel = $this->nivel_model->get(array('id' => $supervision->nivel_id));
		$this->load->model('escuela_model');
		$nivel->escuelas = $this->escuela_model->get_by_nivel($nivel->id, 1, $supervision->id);
		$nivel->indices = $this->nivel_model->get_indices_by_escuela($nivel->id, 1, $supervision->id);
		$data['niveles'] = array($nivel);
		$this->load_template('division/division_con_carrera_por_escuela', $data);
	}

	public function modal_cerrar_division($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$division = $this->division_model->get_one($id);
		if (empty($division)) {
			$this->modal_error('No se encontró el registro de la división', 'Registro no encontrado');
			return;
		}

		$this->load->model('alumno_division_model');
		$this->load->model('cargo_model');

		$alumnos_activos = $this->alumno_division_model->get(array('division_id' => $division->id, 'where' => array('fecha_hasta IS NULL')));
		if (!empty($alumnos_activos)) {
			$this->modal_error('No se puede cerrar una división con alumnos activos', 'Acción no permitida');
			return;
		}
		$cargos_abiertos = $this->cargo_model->get(array('division_id' => $division->id, 'where' => array('fecha_hasta IS NULL')));
		if (!empty($cargos_abiertos)) {
			$this->modal_error('No se puede cerrar una división con cargos activos', 'Acción no permitida');
			return;
		}
		$model = new stdClass();
		$model->fields = array(
			'fecha_baja' => array('label' => 'Fecha Baja', 'type' => 'date', 'required' => TRUE)
		);
		$this->set_model_validation_rules($model);
		if (isset($_POST) && !empty($_POST)) {

			$this->load->model('horario_model');
			$horarios = $this->horario_model->get(array(
				'select' => array('COUNT(1) cantidad'),
				'join' => array(
					array('cargo_horario', 'cargo_horario.horario_id = horario.id'),
					array('cargo', 'cargo.id = cargo_horario.cargo_id'),
				),
				'division_id' => $division->id,
			));
			if ($horarios[0]->cantidad > 0) {
				$this->session->set_flashdata('error', 'No se puede cerrar una división que posea cargos con horarios asignados. Si desea cerrar la división primero desasigne las materias en el horario.');
				redirect("division/asignar_horario/$id");
			}

			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
				return;
			}
			if (!empty($division->fecha_alta) && $this->get_date_sql('fecha_baja') < $division->fecha_alta) {
				$this->session->set_flashdata('error', 'La fecha de baja debe ser posterior a la fecha de alta');
				redirect("division/ver/$id", 'refresh');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->division_model->update(array(
					'id' => $id,
					'fecha_baja' => $this->get_date_sql('fecha_baja'),
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', 'División cerrada correctamente');
				} else {
					$this->session->set_flashdata('error', 'Error al cerrar la división');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect("division/ver/$id", 'refresh');
		}

		$data['division'] = $division;
		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($model->fields, $division);
		$data['txt_btn'] = 'Cerrar';
		$data['title'] = 'Cerrar división';
		$this->load->view('division/division_modal_cerrar', $data);
	}

	public function transicion_cl_alumnos($division_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ESCUELA_ALUM) {
			$this->edicion = TRUE;
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("division/mover_alumnos/$division_id/$ciclo_lectivo", 'refresh');
		}

		$this->load->model('division_model');
		$division = $this->division_model->get_one($division_id);
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

		$tableData = array(
			'columns' => array(
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Persona', 'data' => 'persona', 'width' => 30, 'class' => 'text-sm'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Div', 'data' => 'division', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'C.L.', 'data' => 'ciclo_lectivo', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'date', 'width' => 10),
				array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'date', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(3, 'asc'), array(1, 'asc'), array(2, 'asc'), array(5, 'asc')),
			'table_id' => 'alumno_table',
			'source_url' => "division/alumnos_transicion_cl_data/$division_id/$ciclo_lectivo",
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_table",
			'footer' => TRUE,
			'disablePagination' => TRUE,
			'dom' => 'rt'
		);

		$model = new stdClass();
		$model->fields = array(
			'alumno_division[]' => array('label' => 'Alumnos', 'required' => TRUE),
			'division' => array('label' => 'División', 'input_type' => 'combo', 'required' => TRUE),
			'fecha_desde' => array('label' => 'Fecha inicio C.L.', 'type' => 'date', 'value' => '05/03/2018'),
			'fecha_hasta' => array('label' => 'Fecha fin C.L.', 'type' => 'date', 'value' => '07/12/2017'),
			'causa_salida' => array('label' => 'Movimiento', 'input_type' => 'combo', 'required' => TRUE)
		);

		$this->load->model('alumno_division_model');
		$this->load->model('causa_salida_model');
		$this->load->model('division_model');
		$this->load->model('alumno_movimiento_model');
		$this->load->model('alumno_movimiento_detalle_model');

		$array_causa_salida = $this->get_array('causa_salida', 'descripcion', 'id', array('where' => array("id IN (31,32,33,34,35,37,40)")), array('' => '-- Seleccionar motivo--'));
		switch ($escuela->nivel_id) {
			case '2':
				unset($array_causa_salida['34']);
				unset($array_causa_salida['35']);
				unset($array_causa_salida['40']);
				break;
			case '3':
			case '4':
				unset($array_causa_salida['37']);
				unset($array_causa_salida['40']);
				break;
			case '5':
				unset($array_causa_salida['35']);
				unset($array_causa_salida['37']);
				break;
			case '8':
				unset($array_causa_salida['33']);
				unset($array_causa_salida['34']);
				unset($array_causa_salida['35']);
				unset($array_causa_salida['37']);
				break;
			default:
				break;
		}
		$this->array_causa_salida_control = $array_causa_salida;

		$this->array_division_control = $array_division = $this->get_array('division', 'division', 'id', array(
			'select' => array('division.id', 'CONCAT(curso.descripcion, \' \', division.division, \' \',CASE WHEN escuela.anexo= 0 THEN \'\' ELSE CONCAT(\'(Anexo \', escuela.anexo,\')\') END ) as division'),
			'join' => array(
				array('curso', 'curso.id = division.curso_id'),
				array('escuela', 'escuela.id = division.escuela_id'),
				array('escuela ea', 'escuela.escuela_id = ea.id OR escuela.id = ea.id')
			),
			'where' => array('division.fecha_baja IS NULL',
				array('column' => 'ea.id', 'value' => (empty($escuela->escuela_id) ? $escuela->id : $escuela->escuela_id))
			),
			'sort_by' => 'curso.descripcion, division.division'
			), array('' => '-- Seleccionar división de destino --')
		);
		$this->form_validation->set_rules('ciclo_lectivo_nuevo', 'Ciclo lectivo nuevo', 'integer|required');
		$this->set_model_validation_rules($model);
		if ($this->form_validation->run() === TRUE) {
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$alumno_division_ids = $this->input->post('alumno_division');
			$count = count($alumno_division_ids);
			$nueva_division = $this->division_model->get_one($this->input->post('division'));
			if ($this->input->post('causa_salida') === '31') {
				$trans_ok &= $this->alumno_movimiento_model->create(array(
					'fecha' => date("Y-m-d H:m:s"),
					'escuela_id' => $escuela->id,
					'tipo' => 'Transición',
					'movimiento' => "Transición, egreso desde división $division->curso $division->division (C.L. $ciclo_lectivo)",
					'causa_salida_id' => $this->input->post('causa_salida'),
					'division_origen_id' => $division_id,
					'ciclo_lectivo_origen' => $ciclo_lectivo
					), FALSE);
				$alumno_movimiento_id = $this->alumno_movimiento_model->get_row_id();
			} else {
				if ($this->input->post('causa_salida') === '34') {
					$trans_ok &= $this->alumno_movimiento_model->create(array(
						'fecha' => date("Y-m-d H:m:s"),
						'escuela_id' => $escuela->id,
						'tipo' => 'Transición',
						'movimiento' => "Transición, Egreso no efectivo desde división $division->curso $division->division (C.L. $ciclo_lectivo)",
						'causa_salida_id' => $this->input->post('causa_salida'),
						'division_origen_id' => $division_id,
						'ciclo_lectivo_origen' => $ciclo_lectivo,
						'causa_entrada_id' => 10,
						'division_destino_id' => $this->input->post('division'),
						'ciclo_lectivo_destino' => $this->input->post('ciclo_lectivo_nuevo')
						), FALSE);
					$alumno_movimiento_id = $this->alumno_movimiento_model->get_row_id();
				} else {
					$trans_ok &= $this->alumno_movimiento_model->create(array(
						'fecha' => date("Y-m-d H:m:s"),
						'escuela_id' => $escuela->id,
						'tipo' => 'Transición',
						'movimiento' => "Transición, " . ($this->input->post('causa_salida') === '32' ? 'Promoción' : (($this->input->post('causa_salida') === '37' ) ? 'Compensación primaria' : (($this->input->post('causa_salida') === '33' ) ? 'Repitencia' : (($this->input->post('causa_salida') === '40' ) ? 'Continua' : 'Promoción condicional' )))) . " desde división $division->curso $division->division (C.L. $ciclo_lectivo) a división $nueva_division->curso $nueva_division->division (C.L. " . $this->input->post('ciclo_lectivo_nuevo') . ")",
						'causa_salida_id' => $this->input->post('causa_salida'),
						'division_origen_id' => $division_id,
						'ciclo_lectivo_origen' => $ciclo_lectivo,
						'causa_entrada_id' => ($this->input->post('causa_salida') === '32' ? '7' : (($this->input->post('causa_salida') === '37' ) ? '11' : (($this->input->post('causa_salida') === '33' ) ? '8' : (($this->input->post('causa_salida') === '40' ) ? '13' : '9' )))),
						'division_destino_id' => $this->input->post('division'),
						'ciclo_lectivo_destino' => $this->input->post('ciclo_lectivo_nuevo')
						), FALSE);
					$alumno_movimiento_id = $this->alumno_movimiento_model->get_row_id();
				}
			}
			for ($i = 0; $i < $count; $i++) {
				if ($trans_ok) {
					if ($this->input->post('causa_salida') === '31') {
						$trans_ok &= $this->alumno_division_model->update(array(
							'id' => $alumno_division_ids[$i],
							'fecha_hasta' => $this->get_date_sql('fecha_hasta'),
							'estado_id' => 3,
							'causa_salida_id' => $this->input->post('causa_salida')
							), FALSE);
						$trans_ok &= $this->alumno_movimiento_detalle_model->create(array(
							'alumno_movimiento_id' => $alumno_movimiento_id,
							'alumno_division_id' => $alumno_division_ids[$i],
							'accion' => 'Egreso'
							), FALSE);
					} else {
						if ($this->input->post('causa_salida') === '34') {
							$trans_ok &= $this->alumno_division_model->update(array(
								'id' => $alumno_division_ids[$i],
								'fecha_hasta' => $this->get_date_sql('fecha_hasta'),
								'estado_id' => 3,
								'causa_salida_id' => $this->input->post('causa_salida')
								), FALSE);
							$trans_ok &= $this->alumno_movimiento_detalle_model->create(array(
								'alumno_movimiento_id' => $alumno_movimiento_id,
								'alumno_division_id' => $alumno_division_ids[$i],
								'accion' => 'Egreso'
								), FALSE);
							$alumno_division = $this->alumno_division_model->get_one($alumno_division_ids[$i]);
							if ($trans_ok) {
								$trans_ok &= $this->alumno_division_model->create(array(
									'alumno_id' => $alumno_division->alumno_id,
									'legajo' => $alumno_division->legajo,
									'division_id' => $this->input->post('division'),
									'fecha_desde' => $this->get_date_sql('fecha_desde'),
									'ciclo_lectivo' => $this->input->post('ciclo_lectivo_nuevo'),
									'condicion' => 'Egreso no efectivo',
									'estado_id' => 1,
									'causa_entrada_id' => 10
									), FALSE);
								$nuevo_alumno_division_id = $this->alumno_division_model->get_row_id();
								$trans_ok &= $this->alumno_movimiento_detalle_model->create(array(
									'alumno_movimiento_id' => $alumno_movimiento_id,
									'alumno_division_id' => $nuevo_alumno_division_id,
									'accion' => 'Ingreso'
									), FALSE);
							}
						} else {
							$trans_ok &= $this->alumno_division_model->update(array(
								'id' => $alumno_division_ids[$i],
								'fecha_hasta' => $this->get_date_sql('fecha_hasta'),
								'estado_id' => 3,
								'causa_salida_id' => $this->input->post('causa_salida')
								), FALSE);
							$trans_ok &= $this->alumno_movimiento_detalle_model->create(array(
								'alumno_movimiento_id' => $alumno_movimiento_id,
								'alumno_division_id' => $alumno_division_ids[$i],
								'accion' => 'Egreso'
								), FALSE);
							$alumno_division = $this->alumno_division_model->get_one($alumno_division_ids[$i]);
							if ($trans_ok) {
								$trans_ok &= $this->alumno_division_model->create(array(
									'alumno_id' => $alumno_division->alumno_id,
									'legajo' => $alumno_division->legajo,
									'division_id' => $this->input->post('division'),
									'fecha_desde' => $this->get_date_sql('fecha_desde'),
									'ciclo_lectivo' => $this->input->post('ciclo_lectivo_nuevo'),
									'estado_id' => 1,
									'causa_entrada_id' => ($this->input->post('causa_salida') === '32' ? '7' : (($this->input->post('causa_salida') === '37' ) ? '11' : ( ($this->input->post('causa_salida') === '33' ) ? '8' : (($this->input->post('causa_salida') === '40' ) ? '13' : '9'))))
									), FALSE);
								$nuevo_alumno_division_id = $this->alumno_division_model->get_row_id();
								$trans_ok &= $this->alumno_movimiento_detalle_model->create(array(
									'alumno_movimiento_id' => $alumno_movimiento_id,
									'alumno_division_id' => $nuevo_alumno_division_id,
									'accion' => 'Ingreso'
									), FALSE);
							}
						}
					}
				}
			}

			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Movimiento realizado satisfactoriamente');
				redirect("division/transicion_cl_alumnos/$division_id/$ciclo_lectivo", 'refresh');
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al intentar actualizar.';
				if ($this->alumno_division_model->get_error())
					$errors .= '<br>' . $this->alumno_division_model->get_error();
			}
		}

		$data['error'] = (validation_errors() ? validation_errors() : ($this->alumno_division_model->get_error() ? $this->alumno_division_model->get_error() : $this->session->flashdata('error')));

		$model->fields['causa_salida']['array'] = $array_causa_salida;
		$model->fields['division']['array'] = $array_division;

		$alumnos_transicion = $this->division_model->get_alumnos_transicion($division->id);

		$data['alumnos_transicion'] = $alumnos_transicion;
		$data['fields'] = $this->build_fields($model->fields);
		$data['anno'] = date("Y");
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Transición Ciclo Lectivo';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('division/division_alumnos_transicion_cl', $data);
	}

	public function alumnos_transicion_cl_data($division_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ESCUELA_ALUM) {
			$this->edicion = TRUE;
		}
		$division = $this->division_model->get(array('id' => $division_id));
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

		$this->datatables
			->select("alumno_division.id, alumno.persona_id, alumno.observaciones, alumno_division.fecha_desde, alumno_division.fecha_hasta, CONCAT(documento_tipo.descripcion_corta, ' ', persona.documento) as documento, alumno_division.ciclo_lectivo, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, CASE curso.grado_multiple WHEN 'Si' THEN CONCAT(division.division, ' (', COALESCE(c_gm.descripcion, ''), ')') ELSE division.division END as division, curso.descripcion as curso, division.escuela_id")
			->unset_column('id')
			->from('alumno')
			->join('persona', 'persona.id = alumno.persona_id')
			->join('alumno_division', 'alumno_division.alumno_id = alumno.id')
			->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id')
			->join('division', 'division.id = alumno_division.division_id')
			->join('curso', 'division.curso_id = curso.id')
			->join('curso c_gm', 'alumno_division.curso_id = c_gm.id', 'left')
			->where('alumno_division.fecha_hasta IS NULL')
			->where('division.id', $division_id)
			->where('ciclo_lectivo', $ciclo_lectivo)
			->where('alumno_division.condicion = "Regular"');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<input type="checkbox" name="alumno_division[]" value="$1">', 'id');
		} else {
			$this->datatables->add_column('edit', '', '');
		}

		echo $this->datatables->generate();
	}

	public function movimientos_alumnos($division_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ESCUELA_ALUM) {
			$this->edicion = TRUE;
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("division/mover_alumnos/$division_id/$ciclo_lectivo", 'refresh');
		}
		$this->load->model('division_model');
		$division = $this->division_model->get_one($division_id);
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

		$tableData = array(
			'columns' => array(
				array('label' => 'Fecha', 'data' => 'fecha', 'render' => 'datetime', 'width' => 6, 'class' => 'dt-body-center'),
				array('label' => 'Tipo', 'data' => 'tipo', 'width' => 8, 'class' => 'dt-body-center'),
//				array('label' => 'Movimiento', 'data' => 'movimiento', 'width' => 14, 'class' => 'text-sm'),
				array('label' => 'Causa salida', 'data' => 'causa_salida_descripcion', 'width' => 13, 'class' => 'dt-body-center'),
				array('label' => 'División origen', 'data' => 'division_origen', 'width' => 20, 'class' => 'dt-body-center'),
				array('label' => 'C.L. origen', 'data' => 'ciclo_lectivo_origen', 'width' => 5, 'class' => 'dt-body-center'),
				array('label' => 'Causa entrada', 'data' => 'causa_entrada_descripcion', 'width' => 13, 'class' => 'dt-body-center'),
				array('label' => 'División destino', 'data' => 'division_destino', 'width' => 20, 'class' => 'dt-body-center'),
				array('label' => 'C.L. destino', 'data' => 'ciclo_lectivo_destino', 'width' => 5, 'class' => 'dt-body-center'),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(0, 'asc')),
			'table_id' => 'alumno_table',
			'source_url' => "division/movimientos_alumnos_data/$escuela->id/$division->id",
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_table",
			'footer' => TRUE,
			'disablePagination' => TRUE,
			'dom' => 'rt'
		);

		$this->load->model('alumno_division_model');
		$this->load->model('causa_salida_model');
		$this->load->model('division_model');

		$alumnos_transicion = $this->division_model->get_alumnos_transicion($division->id);

		$data['error'] = (validation_errors() ? validation_errors() : ($this->alumno_division_model->get_error() ? $this->alumno_division_model->get_error() : $this->session->flashdata('error')));

		$data['alumnos_transicion'] = $alumnos_transicion;
		$data['anno'] = date("Y");
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('errors');
		$data['title'] = TITLE . ' - Movimientos';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('division/division_alumnos_movimientos', $data);
	}

	public function movimientos_alumnos_data($escuela_id, $division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_ESCUELA_ALUM) {
			$this->edicion = TRUE;
		}
		$division = $this->division_model->get(array('id' => $division_id));
		if (empty($division)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->datatables
			->select("alumno_movimiento.id, alumno_movimiento.fecha, alumno_movimiento.tipo, alumno_movimiento.movimiento, causa_salida.descripcion as causa_salida_descripcion, alumno_movimiento.ciclo_lectivo_origen, causa_entrada.descripcion as causa_entrada_descripcion, CONCAT(COALESCE(co.descripcion, ''), ' ', COALESCE(do.division, '')) as division_origen, CONCAT(COALESCE(cd.descripcion, ''), ' ', COALESCE(dd.division, '')) as division_destino, alumno_movimiento.ciclo_lectivo_destino")
			->unset_column('id')
			->from('alumno_movimiento')
			->join('causa_salida', 'causa_salida.id = alumno_movimiento.causa_salida_id', 'left')
			->join('causa_entrada', 'causa_entrada.id = alumno_movimiento.causa_entrada_id', 'left')
			->join('division do', 'do.id = alumno_movimiento.division_origen_id', 'left')
			->join('curso co', 'co.id = do.curso_id', 'left')
			->join('division dd', 'dd.id = alumno_movimiento.division_destino_id', 'left')
			->join('curso cd', 'cd.id = dd.curso_id', 'left')
			->join('alumno_movimiento_detalle', 'alumno_movimiento_detalle.alumno_movimiento_id = alumno_movimiento.id', 'left')
//			->where('alumno_movimiento.division_origen_id ', $division_id)
			->where("alumno_movimiento.division_origen_id = $division_id OR alumno_movimiento.division_destino_id = $division_id")
			->where('alumno_movimiento.escuela_id', $escuela_id)
			->group_by('alumno_movimiento.id');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="division/modal_movimientos_alumnos/$1/$2" title="Revertir"><i class="fa fa-refresh"></i> Revertir</a>'
				. '</div>', "$division_id,id");
		} else {
			$this->datatables->add_column('edit', '', '');
		}

		echo $this->datatables->generate();
	}

	public function modal_movimientos_alumnos($division_id = NULL, $alumno_movimiento_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id) || $alumno_movimiento_id == NULL || !ctype_digit($alumno_movimiento_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('alumno_movimiento_detalle_model');
		$this->load->model('alumno_division_model');
		$this->load->model('alumno_movimiento_model');
		$movimiento = $this->alumno_movimiento_model->get_one($alumno_movimiento_id);
		if (empty($movimiento)) {
			$this->modal_error('No se encontró el registro de alumno movimiento', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($movimiento->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$alumnos = $this->alumno_movimiento_detalle_model->get_alumnos($movimiento->id);
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_movimiento_id !== $this->input->post('alumnos_movimientos_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$trans_ok = TRUE;
			if (!empty($alumnos)) {
				foreach ($alumnos as $alumno) {
//					$alumno_movimiento_anterior_id = $this->alumno_movimiento_detalle_model->alumno_movimiento_anterior($alumno->alumno_division_id, $alumno->id);
					if (!empty($alumno->movimiento_posterior)) {
						$this->session->set_flashdata('errors', 'Ocurrió un error al intentar actualizar, debe eliminar el registro de movimientos anteriores');
						redirect("division/movimientos_alumnos/$division_id/2017", 'refresh');
					}
				}
				foreach ($alumnos as $alumno) {
					$alumno_id = $alumno->id;
					$alumno_division_id = $alumno->alumno_division_id;
					if ($alumno->accion == 'Ingreso') {
						$trans_ok &= $this->alumno_movimiento_detalle_model->delete(array(
							'id' => $alumno_id
						));
						$trans_ok &= $this->alumno_division_model->delete(array(
							'id' => $alumno_division_id
						));
					}
					if ($alumno->accion == 'Egreso') {
						$trans_ok &= $this->alumno_division_model->update(array(
							'id' => $alumno_division_id,
							'condicion' => 'Regular',
							'fecha_hasta' => '',
							'estado_id' => 1,
							'causa_salida_id' => ''
						));
						$trans_ok &= $this->alumno_movimiento_detalle_model->delete(array(
							'id' => $alumno_id
						));
					}
				}
			}
			if (!empty($trans_ok)) {
				$trans_ok &= $this->alumno_movimiento_model->delete(array(
					'id' => $alumno_movimiento_id
				));
			}

			if ($trans_ok) {
				$this->session->set_flashdata('message', 'Movimiento revertido satisfactoriamente');
				redirect("division/movimientos_alumnos/$division_id/2017", 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Ocurrió un error al intentar revertir el movimiento');
				redirect("division/movimientos_alumnos/$division_id/2017", 'refresh');
			}
		}

		$this->load->model('alumno_movimiento_detalle_model');
		$tabla_alumnos = array();
		foreach ($alumnos as $alumno) {
			if ($alumno->accion === 'Egreso') {
				$tabla_alumnos[$alumno->alumno_id][$alumno->accion] = $alumno->movimiento_posterior;
				$tabla_alumnos[$alumno->alumno_id]['nombre'] = $alumno->persona;
			}
			if ($alumno->accion === 'Ingreso') {
				$tabla_alumnos[$alumno->alumno_id][$alumno->accion] = $alumno->movimiento_posterior;
				$tabla_alumnos[$alumno->alumno_id]['nombre'] = $alumno->persona;
			}
		}
		$data['alumno_movimiento'] = $movimiento;
		$data['escuela'] = $escuela;
		$data['alumnos_movimientos_detalle'] = $alumnos;
		$data['tabla_alumnos'] = $tabla_alumnos;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['txt_btn'] = 'Revertir';
		$data['title'] = 'Revertir los movimientos de los alumnos';
		$this->load->view('division/division_modal_movimientos_alumnos', $data);
	}

	public function cambiar_ciclo_lectivo($division_id, $ciclo_lectivo) {
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
		redirect("division/escritorio/$division_id/$ciclo_lectivo", 'refresh');
	}

	public function asignar_rol_docente_division($usuario_id = NULL, $division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_administrar) || $usuario_id == NULL || !ctype_digit($usuario_id) || $division_id == NULL || !ctype_digit($division_id)) {
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
		$rol_id = 5;
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

	public function modal_eliminar_rol_docente_division($usuario_rol_id = NULL) {
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
		$data['title'] = "Eliminar rol de docente de la division";
		$data['txt_btn'] = "Quitar rol";
		$this->load->view('division/division_modal_eliminar_usuario_rol_docente', $data);
	}
}
/* End of file Division.php */
	/* Location: ./application/controllers/Division.php */	