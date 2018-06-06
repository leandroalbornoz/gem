<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->load->model('servicio_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ESCUELA_ALUM, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE_CURSADA));
		$this->roles_admin = array(ROL_ADMIN, ROL_USI);
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_ESCUELA_ALUM, ROL_GRUPO_ESCUELA_CONSULTA))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'menu/servicio';
	}

	public function listar($escuela_id = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || ($mes != NULL && !ctype_digit($mes))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (empty($mes) || empty(DateTime::createFromFormat('Ym', $mes))) {
			$this->load->model('planilla_asisnov_model');
			$mes = $this->planilla_asisnov_model->get_mes_actual();
			redirect("servicio/listar/$escuela_id/$mes", 'refresh');
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
				array('label' => '', 'data' => '', 'width' => 2, 'class' => '', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => 'Persona', 'data' => 'persona', 'width' => 20, 'class' => 'text-sm'),
				array('label' => 'Alta', 'data' => 'fecha_alta', 'render' => 'short_date', 'width' => 7),
				array('label' => 'Liquidación', 'data' => 'liquidacion', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'S.R.', 'data' => 'situacion_revista', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 3, 'class' => 'dt-body-center'),
				array('label' => 'Div', 'data' => 'division', 'width' => 9, 'class' => 'dt-body-center'),
				array('label' => 'Régimen/Materia', 'data' => 'regimen_materia', 'width' => 25, 'class' => 'text-sm'),
				array('label' => 'Hs.', 'data' => 'carga_horaria', 'width' => 4, 'class' => 'dt-body-right'),
				array('label' => 'Baja', 'data' => 'fecha_baja', 'render' => 'short_date', 'width' => 6),
				array('label' => 'Observaciones', 'data' => 'observaciones', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all'),
				array('label' => 'F.Detalle', 'data' => 'funcion_detalle', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'F.Destino', 'data' => 'funcion_destino', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'F.Norma', 'data' => 'funcion_norma', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'F.Tarea', 'data' => 'funcion_tarea', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'F.Hs.', 'data' => 'funcion_carga_horaria', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'F.Desde', 'data' => 'funcion_desde', 'width' => 10, 'responsive_class' => 'none'),
			),
			'table_id' => 'servicio_table',
			'source_url' => "servicio/listar_data/$escuela_id/$mes/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(1, 'asc'), array(5, 'asc'), array(6, 'asc'), array(7, 'asc')),
			'details_format' => 'servicio_table_detalles',
			'reuse_var' => TRUE,
			'initComplete' => "complete_servicio_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);

		$tableData_o = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 2, 'class' => '', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => 'Persona', 'data' => 'persona', 'width' => 23, 'class' => 'text-sm'),
				array('label' => 'Alta', 'data' => 'fecha_alta', 'render' => 'short_date', 'width' => 7),
				array('label' => 'Liquidación', 'data' => 'liquidacion', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'S.R.', 'data' => 'situacion_revista', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'Origen', 'data' => 'origen', 'width' => 14, 'class' => 'text-sm'),
				array('label' => 'Régimen', 'data' => 'regimen', 'width' => 20, 'class' => 'text-sm'),
				array('label' => 'Hs.', 'data' => 'carga_horaria', 'width' => 4, 'class' => 'dt-body-right'),
				array('label' => 'Baja', 'data' => 'fecha_baja', 'render' => 'short_date', 'width' => 6),
				array('label' => 'Observaciones', 'data' => 'observaciones', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all')
			),
			'table_id' => 'servicio_f_table',
			'source_url' => "servicio/listar_data_funcion/$escuela_id/$mes/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(1, 'asc'), array(5, 'asc'), array(6, 'asc'), array(7, 'asc')),
			'details_format' => 'servicio_f_table_detalles',
			'reuse_var' => TRUE,
			'initComplete' => "complete_servicio_f_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['escuela'] = $escuela;
		$data['mes_id'] = $mes;
		$data['mes'] = $this->nombres_meses[substr($mes, 4, 2)] . '\'' . substr($mes, 2, 2);
		$fecha = DateTime::createFromFormat('Ymd', $mes . '01');
		$data['fecha'] = $fecha->format('d/m/Y');
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['html_table_o'] = buildHTML($tableData_o);
		$data['js_table_o'] = buildJS($tableData_o);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Servicios';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';

		$this->load_template('servicio/servicio_listar', $data);
	}

	public function listar_data($escuela_id = NULL, $mes = NULL, $rol_codigo = NULL, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $mes == NULL || !ctype_digit($mes)) {
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
			->select(
				'servicio.id, servicio.persona_id, servicio.cargo_id, servicio.fecha_alta,'
				. 'servicio.fecha_baja, servicio.liquidacion, servicio.reemplazado_id, '
				. 'servicio.situacion_revista_id, cargo.carga_horaria, '
				. 'CONCAT(COALESCE(persona.cuil, \'\'), \'<br>\', COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona, situacion_revista.descripcion as situacion_revista, '
				. 'division.division, curso.descripcion as curso, CONCAT(regimen.descripcion, \'<br>\', COALESCE(materia.descripcion, \'\')) as regimen_materia, regimen.descripcion as regimen, materia.descripcion as materia, escuela.id as escuela_id, servicio.observaciones, '
				. 'COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion_detalle, servicio_funcion.destino as funcion_destino, servicio_funcion.norma as funcion_norma, '
				. 'servicio_funcion.tarea as funcion_tarea, servicio_funcion.carga_horaria as funcion_carga_horaria, servicio_funcion.fecha_desde as funcion_desde, alta.id as alta_id, baja.id as baja_id')
			->unset_column('id')
			->from('servicio')
			->join('persona', 'persona.id = servicio.persona_id')
			->join('cargo', 'cargo.id = servicio.cargo_id')
			->join('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id')
			->join('planilla_tipo', 'planilla_tipo.id = situacion_revista.planilla_tipo_id AND planilla_tipo.planilla_modalidad_id = 1')
			->join('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1')
			->join('escuela', 'cargo.escuela_id = escuela.id')
			->join('servicio_funcion', 'servicio_funcion.servicio_id=servicio.id AND servicio_funcion.fecha_hasta IS NULL', 'left')
			->join('funcion', 'funcion.id = servicio_funcion.funcion_id', 'left')
			->join('servicio_novedad alta', 'alta.servicio_id=servicio.id AND alta.novedad_tipo_id=1 AND alta.ames=' . $mes, 'left')
			->join('servicio_novedad baja', 'baja.servicio_id=servicio.id AND baja.novedad_tipo_id IN(SELECT id FROM novedad_tipo WHERE novedad=\'B\') AND baja.planilla_baja_id IS NULL AND baja.ames=' . $mes, 'left')
			->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
			->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
			->join('division', 'cargo.division_id = division.id', 'left')
			->join('curso', 'division.curso_id = curso.id', 'left')
			->where("'$mes' BETWEEN COALESCE(DATE_FORMAT(servicio.fecha_alta,'%Y%m'),'000000') AND COALESCE(DATE_FORMAT(servicio.fecha_baja,'%Y%m'),'999999')")
			->where('escuela.id', $escuela_id)
			->group_by('servicio.id')
			->add_column('', '', 'id');
		if ($this->edicion) {
			$this->datatables->add_column('menu', '$1', 'dt_column_servicio_menu(\'' . $mes . '\', id, escuela_id, fecha_baja, alta_id, baja_id)');
		} else {
			$this->datatables->add_column('menu', '<a class="btn btn-xs btn-default" href="servicio/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id');
		}

		echo $this->datatables->generate();
	}

	public function listar_data_funcion($escuela_id = NULL, $mes = NULL, $rol_codigo = NULL, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $mes == NULL || !ctype_digit($mes)) {
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
			->select(
				'servicio_funcion.id, servicio.persona_id, servicio.cargo_id, servicio.fecha_alta,'
				. 'servicio.fecha_baja, servicio.liquidacion, servicio.reemplazado_id, '
				. 'servicio.situacion_revista_id, cargo.carga_horaria, '
				. 'CONCAT(COALESCE(persona.cuil, \'\'), \'<br>\', COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona, situacion_revista.descripcion as situacion_revista,'
				. 'COALESCE(CONCAT(area.codigo, \' \', area.descripcion), CONCAT(escuela.numero, \' \', escuela.nombre, CASE WHEN anexo = 0 THEN \'\' ELSE CONCAT(\'/\', anexo) END)) as origen, regimen.descripcion as regimen, servicio_funcion.escuela_id, servicio.observaciones')
			->unset_column('id')
			->from('servicio_funcion')
			->join('servicio', 'servicio.id = servicio_funcion.servicio_id')
			->join('cargo', 'cargo.id = servicio.cargo_id', '')
			->join('escuela', 'escuela.id = cargo.escuela_id', 'left')
			->join('area', 'area.id = cargo.area_id', 'left')
			->join('persona', 'persona.id = servicio.persona_id', 'left')
			->join('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id', 'left')
			->join('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1', '')
			->where("'$mes' BETWEEN COALESCE(DATE_FORMAT(servicio.fecha_alta,'%Y%m'),'000000') AND COALESCE(DATE_FORMAT(servicio.fecha_baja,'%Y%m'),'999999')")
			->where('servicio_funcion.escuela_id', $escuela_id)
			->where('servicio_funcion.fecha_hasta IS NULL')
			->add_column('', '', 'id');
		if ($this->edicion) {
			$this->datatables->add_column('menu', '$1', 'dt_column_servicio_funcion_menu(\'' . $mes . '\', id, escuela_id, fecha_baja)');
		} else {
			$this->datatables->add_column('menu', '<a class="btn btn-xs btn-default" href="servicio_funcion/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id');
		}

		echo $this->datatables->generate();
	}

	public function cambiar_mes($escuela_id, $mes) {
		$model = new stdClass();
		$model->fields = array(
			'mes' => array('label' => 'Mes', 'type' => 'date', 'required' => TRUE)
		);
		$this->set_model_validation_rules($model);
		if ($this->form_validation->run() === TRUE) {
			$mes = (new DateTime($this->get_date_sql('mes')))->format('Ym');
			$this->session->set_flashdata('message', 'Mes cambiado correctamente');
		} else {
			$this->session->set_flashdata('error', 'Error al cambiar mes');
		}
		redirect("servicio/listar/$escuela_id/$mes", 'refresh');
	}

	public function agregar($cargo_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $cargo_id == NULL || !ctype_digit($cargo_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('cargo_model');
		$cargo = $this->cargo_model->get_one($cargo_id);
		if (empty($cargo)) {
			show_error('No se encontró el cargo del servicio a agregar', 500, 'Registro no encontrado');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("servicio/listar/$cargo->escuela_id");
		}

		$escuela = $this->escuela_model->get_one($cargo->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('departamento_model');
		$this->load->model('documento_tipo_model');
		$this->load->model('estado_civil_model');
		$this->load->model('grupo_sanguineo_model');
		$this->load->model('localidad_model');
		$this->load->model('nacionalidad_model');
		$this->load->model('nivel_estudio_model');
		$this->load->model('obra_social_model');
		$this->load->model('ocupacion_model');
		$this->load->model('persona_model');
		$this->load->model('prestadora_model');
		$this->load->model('sexo_model');
		$this->array_depto_nacimiento_control = $array_depto_nacimiento = $this->get_array('departamento', 'descripcion', 'id', null, array('' => '-- Seleccionar departamento --'));
		$this->array_documento_tipo_control = $array_documento_tipo = $this->get_array('documento_tipo', 'descripcion_corta', 'id', null, array('' => '-- Seleccionar tipo de documento --'));
		$this->array_estado_civil_control = $array_estado_civil = $this->get_array('estado_civil', 'descripcion', 'id', null, array('' => '-- Seleccionar estado civil --'));
		$this->array_grupo_sanguineo_control = $array_grupo_sanguineo = $this->get_array('grupo_sanguineo', 'descripcion', 'id', null, array('' => '-- Seleccionar grupo sanguíneo --'));
		$this->array_localidad_control = $array_localidad = $this->get_array('localidad', 'localidad', 'id', array(
			'select' => array('localidad.id', "CONCAT(departamento.descripcion, ' - ', localidad.descripcion) as localidad"),
			'join' => array(array('departamento', 'departamento.id = localidad.departamento_id')),
			'sort_by' => 'departamento.descripcion, localidad.descripcion'
			), array('' => '-- Seleccionar localidad --'));
		$this->array_nacionalidad_control = $array_nacionalidad = $this->get_array('nacionalidad', 'descripcion', 'id', null, array('' => '-- Seleccionar nacionalidad --'));
		$this->array_nivel_estudio_control = $array_nivel_estudio = $this->get_array('nivel_estudio', 'descripcion', 'id', null, array('' => '-- Seleccionar nivel de estudios --'));
		$this->array_obra_social_control = $array_obra_social = $this->get_array('obra_social', 'descripcion', 'id', null, array('' => '-- Seleccionar obra social --'));
		$this->array_ocupacion_control = $array_ocupacion = $this->get_array('ocupacion', 'descripcion', 'id', null, array('' => '-- Seleccionar ocupación --'));
		$this->array_prestadora_control = $array_prestadora = $this->get_array('prestadora', 'descripcion', 'id', null, array('' => '-- Seleccionar prestadora --'));
		$this->array_sexo_control = $array_sexo = $this->get_array('sexo', 'descripcion', 'id', null, array('' => '-- Seleccionar sexo --'));

		$this->persona_model->fields['depto_nacimiento']['array'] = $array_depto_nacimiento;
		$this->persona_model->fields['documento_tipo']['array'] = $array_documento_tipo;
		$this->persona_model->fields['documento_tipo']['value'] = 1;
		$this->persona_model->fields['estado_civil']['array'] = $array_estado_civil;
		$this->persona_model->fields['grupo_sanguineo']['array'] = $array_grupo_sanguineo;
		$this->persona_model->fields['localidad']['array'] = $array_localidad;
		$this->persona_model->fields['nacionalidad']['array'] = $array_nacionalidad;
		$this->persona_model->fields['nivel_estudio']['array'] = $array_nivel_estudio;
		$this->persona_model->fields['obra_social']['array'] = $array_obra_social;
		$this->persona_model->fields['ocupacion']['array'] = $array_ocupacion;
		$this->persona_model->fields['prestadora']['array'] = $array_prestadora;
		$this->persona_model->fields['sexo']['array'] = $array_sexo;

		foreach ($this->persona_model->fields as $field_id => $field) {
			if ($field_id !== 'documento' && $field_id !== 'documento_tipo') {
				$this->persona_model->fields[$field_id]['disabled'] = 'TRUE';
			}
		}

		if (substr($cargo->regimen, 1, 6) === '560201') {
			$this->load->model('celador_concepto_model');
			$this->array_celador_concepto_control = $array_celador_concepto = $this->get_array('celador_concepto', 'descripcion', 'id', null, array('' => '-- Seleccionar concepto de celador --'));
			$this->servicio_model->fields['celador_concepto']['array'] = $array_celador_concepto;
		} else {
			unset($this->servicio_model->fields['celador_concepto']);
		}
		$this->load->model('situacion_revista_model');
		$this->load->model('servicio_funcion_model');
		$this->array_situacion_revista_control = $array_situacion_revista = $this->get_array('situacion_revista', 'descripcion', 'id', array('where' => array('id IN (1,2,3)')), array('' => '-- Seleccione situación de revista --'));
		unset($this->servicio_funcion_model->fields['servicio']);
		unset($this->servicio_model->fields['cuil']);
		unset($this->servicio_model->fields['apellido']);
		unset($this->servicio_model->fields['nombre']);
		$this->servicio_model->fields['dias'] = array('label' => 'Días a cumplir en mes', 'type' => 'number', 'max' => '30', 'min' => '1');
		$this->servicio_model->fields['obligaciones'] = array('label' => 'Oblig. a cumplir en mes', 'type' => 'number', 'step' => '0.5', 'max' => $cargo->carga_horaria ? $cargo->carga_horaria * 4 : '0', 'min' => $cargo->carga_horaria ? '1' : '0');
		$this->load->model('funcion_model');
		$this->array_funcion_control = $array_funcion = $this->get_array('funcion', 'descripcion', 'id', null, array('' => '-- Seleccione función --'));
		$this->array_tarea_control = $this->servicio_funcion_model->fields['tarea']['array'];
		$this->array_tipo_destino_control = $this->servicio_funcion_model->fields['tipo_destino']['array'];

		$this->load->model('planilla_asisnov_model');
		$mes_actual = $this->planilla_asisnov_model->get_mes_actual();
		$meses_habilitados = $this->planilla_asisnov_model->get_meses();
		if (!in_array($mes_actual, $meses_habilitados) && $escuela->dependencia_id === '1') {
			show_error('No se encuentra habilitado el mes para realizar altas', 500, 'Acción no permitida');
		}

		$this->set_model_validation_rules($this->servicio_model);
		unset($this->servicio_funcion_model->fields['fecha_desde']);
		$this->set_model_validation_rules($this->servicio_funcion_model);
		$errors = FALSE;
		if (isset($_POST) && !empty($_POST)) {
			if (empty($this->input->post('tipo_destino'))) {
				$this->array_destino_control = array('' => '');
			} else {
				if ($this->input->post('tipo_destino') === 'escuela') {
					$this->array_destino_control = $this->get_array('escuela', 'escuela', 'id', array('select' => array('id', "CONCAT(numero, ' - ', nombre) as escuela")));
				} elseif ($this->input->post('tipo_destino') === 'area') {
					$this->load->model('areas/area_model');
					$this->array_destino_control = $this->get_array('area', 'area', 'id', array('select' => array('id', "CONCAT(codigo, ' - ', descripcion) as area")));
				} else {
					$this->array_destino_control = array('' => '');
				}
			}
			$persona_id = $this->input->post('persona_id');
			if (empty($persona_id)) {
				$this->set_model_validation_rules($this->persona_model);
				if ($escuela->dependencia_id == '1') {
					$fecha_alta_post = $this->get_date_sql('fecha_alta');
					$fecha_alta = new DateTime($fecha_alta_post);
					if (!in_array($fecha_alta->format('Ym'), $meses_habilitados)) {
						$this->session->set_flashdata('error', 'No puede dar alta fuera del periodo actual');
						redirect("servicio/listar/$cargo->escuela_id/$mes_actual", 'refresh');
					}
				}
			} else {
				$this->form_validation->set_rules('documento', 'Documento', 'required|integer');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				if (empty($persona_id)) {
					$trans_ok &= $this->persona_model->create(array(
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
						'prestadora_id' => $this->input->post('prestadora'),
						'fecha_nacimiento' => $this->get_date_sql('fecha_nacimiento'),
						'fecha_defuncion' => $this->get_date_sql('fecha_defuncion'),
						'obra_social_id' => $this->input->post('obra_social'),
						'grupo_sanguineo_id' => $this->input->post('grupo_sanguineo'),
						'depto_nacimiento_id' => $this->input->post('depto_nacimiento'),
						'lugar_traslado_emergencia' => $this->input->post('lugar_traslado_emergencia'),
						'nacionalidad_id' => $this->input->post('nacionalidad')
						), FALSE);

					$persona_id = $this->persona_model->get_row_id();
				} else {
					$persona = $this->persona_model->get(array('id' => $persona_id));
					if (empty($persona)) {
						$persona_id = FALSE;
					} else {
						$servicio_en_cargo = $this->servicio_model->get(array('persona_id' => $persona->id, 'cargo_id' => $cargo->id, 'where' => array('fecha_baja IS NULL')));
						if (!empty($servicio_en_cargo)) {
							$persona_id = FALSE;
							$errors = 'La persona ya tiene un servicio activo en el cargo';
						} elseif (empty($persona->cuil)) {
							$trans_ok &= $this->persona_model->update(array(
								'id' => $persona->id,
								'cuil' => $this->input->post('cuil'),
								'apellido' => $this->input->post('apellido'),
								'nombre' => $this->input->post('nombre'),
								), FALSE);
						}
					}
				}
				if ($trans_ok && $persona_id) {
					$trans_ok &= $this->servicio_model->create(array(
						'persona_id' => $persona_id,
						'cargo_id' => $cargo->id,
						'fecha_alta' => $this->get_date_sql('fecha_alta'),
						'fecha_baja' => $this->get_date_sql('fecha_baja'),
						'situacion_revista_id' => $this->input->post('situacion_revista'),
						'celador_concepto_id' => $this->input->post('celador_concepto'),
						'observaciones' => $this->input->post('observaciones')
						), FALSE);

					$servicio_id = $this->servicio_model->get_row_id();
					$trans_ok &= $this->servicio_funcion_model->create(array(
						'servicio_id' => $servicio_id,
						'celador_concepto' => $this->input->post('celador_concepto'),
						'funcion_id' => $this->input->post('funcion'),
						'destino' => $this->input->post('destino'),
						'norma' => $this->input->post('norma'),
						'tarea' => $this->input->post('tarea'),
						'carga_horaria' => $this->input->post('carga_horaria'),
						'fecha_desde' => $this->get_date_sql('fecha_alta')
						), FALSE);
					$this->load->model('situacion_revista_model');
					$situacion_revista = $this->situacion_revista_model->get(array('id' => $this->input->post('situacion_revista')));
					$this->load->model('planilla_asisnov_model');
					$planilla_id = $this->planilla_asisnov_model->get_planilla_abierta($cargo->escuela_id, $this->get_date_sql('fecha_alta', 'd/m/Y', 'Ym'), $situacion_revista->planilla_tipo_id);
					$trans_ok &= $planilla_id > 0;
					if ($trans_ok) {
						$this->load->model('servicio_novedad_model');
						$trans_ok &= $this->servicio_novedad_model->create(array(
							'servicio_id' => $servicio_id,
							'ames' => $this->get_date_sql('fecha_alta', 'd/m/Y', 'Ym'),
							'novedad_tipo_id' => 1,
							'fecha_desde' => $this->get_date_sql('fecha_alta'),
							'fecha_hasta' => $this->input->post('fecha_baja') ? $this->get_date_sql('fecha_baja') : $this->get_date_sql('fecha_alta'),
							'dias' => $this->input->post('dias'),
							'obligaciones' => $cargo->regimen_tipo_id === '1' ? '0' : $this->input->post('obligaciones'),
							'estado' => 'Cargado',
							'planilla_alta_id' => $planilla_id
							), FALSE);
					}
				}

				if ($this->db->trans_status() && $trans_ok && $persona_id) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->servicio_model->get_msg());
					redirect("cargo/listar/$cargo->escuela_id/", 'refresh');
				} else {
					$this->db->trans_rollback();
					if (empty($errors)) {
						$errors = 'Ocurrió un error al intentar agregar.';
						if ($this->persona_model->get_error()) {
							$errors .= '<br>' . $this->persona_model->get_error();
						}
						if ($this->servicio_model->get_error()) {
							$errors .= '<br>' . $this->servicio_model->get_error();
						}
						if ($this->servicio_funcion_model->get_error()) {
							$errors .= '<br>' . $this->servicio_funcion_model->get_error();
						}
					}
				}
			}
		}

		$data['error'] = validation_errors() ? validation_errors() : ($errors ? $errors : $this->session->flashdata('error'));

		$this->servicio_model->fields['escuela']['value'] = "$cargo->escuela_numero $cargo->escuela";
		$this->servicio_model->fields['regimen']['value'] = $cargo->regimen;
		$this->servicio_model->fields['carga_horaria']['value'] = $cargo->carga_horaria;
		$this->servicio_model->fields['division']['value'] = $cargo->division;
		$this->servicio_model->fields['espacio_curricular']['value'] = $cargo->espacio_curricular;
		$this->servicio_model->fields['situacion_revista']['array'] = $array_situacion_revista;
		$this->servicio_model->fields['fecha_baja']['readonly'] = TRUE;

		$this->servicio_funcion_model->fields['funcion']['array'] = $array_funcion;
		$data['fecha_inicio_actual'] = (new DateTime($mes_actual . '01'))->format('d/m/Y');
		$data['fecha_fin_actual'] = (new DateTime($mes_actual . '01 +1 month -1 day'))->format('d/m/Y');
		$data['fields'] = $this->build_fields($this->persona_model->fields);
		$data['fields_servicio'] = $this->build_fields($this->servicio_model->fields);
		$data['fields_servicio']['fecha_baja']['form'] = '			<div class="input-group">
				' . $data['fields_servicio']['fecha_baja']['form'] . '
				<span class="input-group-addon">
					<input type="checkbox" id="check_fecha_baja">
				</span>
			</div>
';
		$data['fields_funcion'] = $this->build_fields($this->servicio_funcion_model->fields);
		$data['fn_mostrar_campos'] = $this->funcion_model->get_fn_mostrar_campos();
		$data['escuela'] = $escuela;
		$data['cargo'] = $cargo;
		$this->load->model('horario_model');
		$data['horarios'] = $this->horario_model->get_horarios($cargo->id);
		$data['mes'] = $this->nombres_meses[substr($mes_actual, 4, 2)] . '\'' . substr($mes_actual, 2, 2);
		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active disabled btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = TITLE . ' - Agregar servicio';
		$this->load_template('servicio/servicio_agregar', $data);
	}

	public function editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("servicio/ver/$id");
		}

		$this->load->model('celador_concepto_model');
		$this->load->model('servicio_funcion_model');

		$servicio = $this->servicio_model->get_one($id);
		if (empty($servicio)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($servicio->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$servicio_funciones = $this->servicio_funcion_model->get(array(
			'servicio_id' => $servicio->id,
			'join' => array(
				array('funcion', 'servicio_funcion.funcion_id=funcion.id', 'left', array('COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion', 'funcion.horario_propio')),
			)
		));

		if (substr($servicio->regimen, 1, 6) === '560201') {
			$this->load->model('celador_concepto_model');
			$this->array_celador_concepto_control = $array_celador_concepto = $this->get_array('celador_concepto', 'descripcion', 'id', null, array('' => '-- Seleccionar concepto de celador --'));
			$this->servicio_model->fields['celador_concepto']['array'] = $array_celador_concepto;
		} else {
			unset($this->servicio_model->fields['celador_concepto']);
		}
		if (($escuela->dependencia_id !== '1' && in_array($this->rol->codigo, $this->roles_admin)) ||
			$escuela->dependencia_id === '2' && $this->rol->codigo === ROL_PRIVADA) {
			$this->load->model('situacion_revista_model');
			$this->array_situacion_revista_control = $array_situacion_revista = $this->get_array('situacion_revista', 'descripcion', 'id', array('planilla_tipo_id !=' => '3'));
			$this->servicio_model->fields['situacion_revista']['array'] = $array_situacion_revista;
		} else {
			unset($this->servicio_model->fields['situacion_revista']['input_type']);
			$this->servicio_model->fields['situacion_revista']['readonly'] = TRUE;
		}
		if (!empty($servicio->fecha_alta) && (!in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_PRIVADA)))) {
			$this->servicio_model->fields['fecha_alta']['readonly'] = TRUE;
		}
		$this->servicio_model->fields['fecha_baja']['readonly'] = TRUE;
		$this->servicio_model->fields['motivo_baja']['readonly'] = TRUE;

		$this->set_model_validation_rules($this->servicio_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->servicio_model->update(array(
					'id' => $this->input->post('id'),
					'situacion_revista_id' => isset($this->servicio_model->fields['situacion_revista']['readonly']) ? $servicio->situacion_revista_id : $this->input->post('situacion_revista'),
					'fecha_alta' => isset($this->servicio_model->fields['fecha_alta']['readonly']) ? $servicio->fecha_alta : $this->get_date_sql('fecha_alta'),
					'celador_concepto_id' => $this->input->post('celador_concepto'),
					'observaciones' => $this->input->post('observaciones')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->servicio_model->get_msg());
					redirect("servicio/editar/$id", 'refresh');
				}
			}
		}
		$this->load->model('servicio_novedad_model');
		$this->load->model('cargo_model');
		$this->load->model('incidencia_model');
		$servicio_novedades = $this->servicio_novedad_model->get_servicio_novedades($servicio->id, $servicio->persona_id, $escuela->id);
		$otros_servicios = $this->servicio_model->get_otros_servicios_cargo($servicio->cargo_id, $servicio->id);
		$otros_cargos = $this->cargo_model->get_otros_servicios_persona($servicio->id);
		$incidencias = $this->incidencia_model->get_incidencias_servicio($servicio->id);
		$data['error'] = (validation_errors() ? validation_errors() : ($this->servicio_model->get_error() ? $this->servicio_model->get_error() : $this->session->flashdata('error')));
		$data['fields'] = $this->build_fields($this->servicio_model->fields, $servicio);
		$data['incidencias'] = $incidencias;
		$data['servicio_novedades'] = $servicio_novedades;
		$data['otros_servicios'] = $otros_servicios;
		$data['otros_cargos'] = $otros_cargos;
		$data['servicio_funciones'] = $servicio_funciones;
		$data['message'] = $this->session->flashdata('message');
		$data['servicio'] = $servicio;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar servicio';
		$this->load_template('servicio/servicio_abm', $data);
	}

	public function eliminar($id = NULL, $novedad_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("servicio/ver/$id");
		}

		if ($novedad_id === NULL && !in_array($this->rol->codigo, $this->roles_admin)) {
			$this->session->set_flashdata('error', 'No está autorizado para eliminar el servicio');
			redirect("servicio/ver/$id");
		}
		$servicio = $this->servicio_model->get_one($id);
		if (empty($servicio)) {
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}
		if (!empty($servicio->liquidacion)) {
			$this->session->set_flashdata('error', 'No puede eliminar servicios con liquidación asociada.');
			redirect("servicio/ver/$id");
		}
		$this->load->model('servicio_novedad_model');
		$alta = $this->servicio_novedad_model->get(array('servicio_id' => $servicio->id, 'novedad_tipo_id' => '1'));
		if (!empty($alta) && $alta[0]->estado === 'Procesado') {
			$this->session->set_flashdata('error', 'No puede eliminar el servicio. El alta ya ha sido procesada.');
			redirect("servicio/ver/$id");
		}

		$escuela = $this->escuela_model->get_one($servicio->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		if ($novedad_id !== NULL) {
			$novedad = $this->servicio_novedad_model->get_one($novedad_id);
			if ($novedad->novedad_tipo_id === '1') {
				$this->load->model('planilla_asisnov_model');
				$planilla = $this->planilla_asisnov_model->get_planilla($escuela->id, $novedad->ames, $servicio->planilla_tipo_id);
				if ($planilla->id !== $novedad->planilla_alta_id || !empty($planilla->fecha_cierre)) {
					$this->session->set_flashdata('error', 'La planilla del alta del servicio ya ha sido cerrada');
					redirect("servicio/ver/$id");
				}
			} else {
				if ($planilla->id !== $novedad->planilla_alta_id) {
					$this->session->set_flashdata('error', 'No está autorizado para eliminar el servicio');
					redirect("servicio/ver/$id");
				}
			}
		}

		if (substr($servicio->regimen, 1, 6) !== '560201') {
			unset($this->servicio_model->fields['celador_concepto']);
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$this->load->model('servicio_funcion_model');
			$funciones = $this->servicio_funcion_model->get(array('servicio_id' => $id));
			if (!empty($funciones)) {
				foreach ($funciones as $funcion) {
					$trans_ok &= $this->servicio_funcion_model->delete(array('id' => $funcion->id), FALSE);
				}
			}
			$this->load->model('servicio_historial_model');
			$historiales = $this->servicio_historial_model->get(array('servicio_id' => $id));
			if (!empty($historiales)) {
				foreach ($historiales as $historial) {
					$trans_ok &= $this->servicio_historial_model->delete(array('id' => $historial->id), FALSE);
				}
			}
			$this->load->model('servicio_novedad_model');
			$novedades = $this->servicio_novedad_model->get(array('servicio_id' => $id, 'sort_by' => 'id desc'));
			if (!empty($novedades)) {
				foreach ($novedades as $novedad) {
					$trans_ok &= $this->servicio_novedad_model->delete(array('id' => $novedad->id), FALSE);
				}
			}
			$trans_ok &= $this->servicio_model->delete(array('id' => $this->input->post('id')), FALSE);
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->servicio_model->get_msg());
				redirect("servicio/listar/$servicio->escuela_id/", 'refresh');
			} else {
				$this->db->trans_rollback();
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->servicio_model->get_error() ? $this->servicio_model->get_error() : $this->session->flashdata('error')));
		$data['fields'] = $this->build_fields($this->servicio_model->fields, $servicio, TRUE);

		$data['servicio'] = $servicio;
		$data['cargo'] = $servicio;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = TITLE . ' - Eliminar servicio';
		$this->load_template('servicio/servicio_abm', $data);
	}

	public function ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$servicio = $this->servicio_model->get_one($id);
		if (empty($servicio)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		if (empty($servicio->escuela_id)) {
			redirect("areas/personal/ver/$servicio->id");
		}

		$escuela = $this->escuela_model->get_one($servicio->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		if (substr($servicio->regimen, 1, 6) !== '560201') {
			unset($this->servicio_model->fields['celador_concepto']);
		}
		$this->load->model('servicio_funcion_model');
		$servicio_funciones = $this->servicio_funcion_model->get(array(
			'servicio_id' => $servicio->id,
			'join' => array(
				array('funcion', 'servicio_funcion.funcion_id=funcion.id', 'left', array('COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion')),
			)
		));
		$this->load->model('servicio_novedad_model');
		$this->load->model('cargo_model');
		$this->load->model('incidencia_model');
		$servicio_novedades = $this->servicio_novedad_model->get_servicio_novedades($servicio->id, $servicio->persona_id, $escuela->id);
		$otros_servicios = $this->servicio_model->get_otros_servicios_cargo($servicio->cargo_id, $servicio->id);
		$otros_cargos = $this->cargo_model->get_otros_servicios_persona($servicio->id);
		$incidencias = $this->incidencia_model->get_incidencias_servicio($servicio->id);
		$data['incidencias'] = $incidencias;
		$data['otros_cargos'] = $otros_cargos;
		$data['otros_servicios'] = $otros_servicios;
		$data['servicio_novedades'] = $servicio_novedades;
		$data['fields'] = $this->build_fields($this->servicio_model->fields, $servicio, TRUE);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['servicio_funciones'] = $servicio_funciones;
		$data['servicio'] = $servicio;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver servicio';
		$this->load_template('servicio/servicio_abm', $data);
	}

	public function separar_cargo($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->servicio_model->fields['liquidacion_regimen'] = array('label' => 'Régimen', 'readonly' => TRUE);
		$this->servicio_model->fields['liquidacion_carga_horaria'] = array('label' => 'Hs.', 'readonly' => TRUE);
		;
		$options_servicio = array('id' => $id, 'join' => $this->servicio_model->default_join);
		$options_servicio['join'][] = array('regimen regimen_l', 'servicio.liquidacion_regimen_id = regimen_l.id', 'left', array('CONCAT(regimen_l.codigo, \' \',regimen_l.descripcion) as liquidacion_regimen', 'regimen_l.regimen_tipo_id liquidacion_regimen_tipo_id'));
		$options_servicio['join'][] = array('situacion_revista situacion_revista_l', 'situacion_revista_l.id = servicio.liquidacion_situacion_revista_id', 'left', array('situacion_revista_l.descripcion as liquidacion_situacion_revista'));
		$servicio = $this->servicio_model->get($options_servicio);
		if (empty($servicio)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		if (empty($servicio->liquidacion_ames)) {
			$servicio->liquidacion_carga_horaria = $servicio->carga_horaria;
			$servicio->liquidacion_regimen_id = $servicio->regimen_id;
		}

		$escuela = $this->escuela_model->get_one($servicio->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		if (substr($servicio->regimen, 1, 6) !== '560201') {
			unset($this->servicio_model->fields['celador_concepto']);
		}

		$this->load->model('cargo_model');
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$cargo = $this->cargo_model->get(array('id' => $servicio->cargo_id));
			$trans_ok &= $this->cargo_model->create(array(
				'condicion_cargo_id' => $cargo->condicion_cargo_id,
				'division_id' => $cargo->division_id,
				'espacio_curricular_id' => $cargo->espacio_curricular_id,
				'carga_horaria' => empty($servicio->liquidacion_ames) ? $cargo->carga_horaria : $servicio->liquidacion_carga_horaria,
				'regimen_id' => empty($servicio->liquidacion_ames) ? $cargo->regimen_id : $servicio->liquidacion_regimen_id,
				'escuela_id' => $cargo->escuela_id,
				'observaciones' => $cargo->observaciones
				), FALSE);
			$cargo_id = $this->cargo_model->get_row_id();
			$trans_ok &= $this->servicio_model->update(array(
				'id' => $this->input->post('id'),
				'cargo_id' => $cargo_id
				), FALSE);
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->cargo_model->get_msg());
				redirect("cargo/editar/$cargo_id", 'refresh');
			} else {
				$this->db->trans_rollback();
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->servicio_model->get_error() ? $this->servicio_model->get_error() : ($this->cargo_model->get_error() ? $this->cargo_model->get_error() : $this->session->flashdata('error'))));
		$data['fields'] = $this->build_fields($this->servicio_model->fields, $servicio, TRUE);
		$data['servicio'] = $servicio;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = NULL;
		$data['title'] = TITLE . ' - Separar servicio de cargo';
		$this->load_template('servicio/servicio_separar_cargo', $data);
	}

	public function agregar_reemplazo($mes = NULL, $servicio_id = NULL, $escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $mes == NULL || !ctype_digit($mes) || $servicio_id == NULL || !ctype_digit($servicio_id) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("servicio/listar/$escuela_id/$mes");
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$servicio = $this->servicio_model->get(array('id' => $servicio_id));
		if (empty($servicio)) {
			show_error('No se encontró el servicio a reemplazar', 500, 'Registro no encontrado');
		}
		$this->load->model('cargo_model');
		$cargo = $this->cargo_model->get_one($servicio->cargo_id);
		if (empty($cargo)) {
			show_error('No se encontró el cargo del servicio a reemplazar', 500, 'Registro no encontrado');
		}
		$this->load->model('servicio_novedad_model');
		$novedades = $this->servicio_novedad_model->get(array('servicio_id' => $servicio_id, 'ames' => $mes,
			'join' => array(
				array('novedad_tipo', 'servicio_novedad.novedad_tipo_id = novedad_tipo.id', 'left', array('novedad_tipo.descripcion_corta', 'novedad_tipo.articulo', 'novedad_tipo.inciso'))
		)));
		if (empty($novedades)) {
			$this->session->set_flashdata('error', 'No hay novedades sobre las que asignar el reemplazo');
			redirect("servicio/listar/$cargo->escuela_id/$mes", 'refresh');
		}

		$this->load->model('departamento_model');
		$this->load->model('documento_tipo_model');
		$this->load->model('estado_civil_model');
		$this->load->model('grupo_sanguineo_model');
		$this->load->model('localidad_model');
		$this->load->model('nacionalidad_model');
		$this->load->model('nivel_estudio_model');
		$this->load->model('obra_social_model');
		$this->load->model('ocupacion_model');
		$this->load->model('persona_model');
		$this->load->model('prestadora_model');
		$this->load->model('sexo_model');
		$this->array_depto_nacimiento_control = $array_depto_nacimiento = $this->get_array('departamento', 'descripcion', 'id', null, array('' => '-- Seleccionar departamento --'));
		$this->array_documento_tipo_control = $array_documento_tipo = $this->get_array('documento_tipo', 'descripcion_corta', 'id', null, array('' => '-- Seleccionar tipo de documento --'));
		$this->array_estado_civil_control = $array_estado_civil = $this->get_array('estado_civil', 'descripcion', 'id', null, array('' => '-- Seleccionar estado civil --'));
		$this->array_grupo_sanguineo_control = $array_grupo_sanguineo = $this->get_array('grupo_sanguineo', 'descripcion', 'id', null, array('' => '-- Seleccionar grupo sanguíneo --'));
		$this->array_localidad_control = $array_localidad = $this->get_array('localidad', 'localidad', 'id', array(
			'select' => array('localidad.id', "CONCAT(departamento.descripcion, ' - ', localidad.descripcion) as localidad"),
			'join' => array(array('departamento', 'departamento.id = localidad.departamento_id')),
			'sort_by' => 'departamento.descripcion, localidad.descripcion'
			), array('' => '-- Seleccionar localidad --'));
		$this->array_nacionalidad_control = $array_nacionalidad = $this->get_array('nacionalidad', 'descripcion', 'id', null, array('' => '-- Seleccionar nacionalidad --'));
		$this->array_nivel_estudio_control = $array_nivel_estudio = $this->get_array('nivel_estudio', 'descripcion', 'id', null, array('' => '-- Seleccionar nivel de estudios --'));
		$this->array_obra_social_control = $array_obra_social = $this->get_array('obra_social', 'descripcion', 'id', null, array('' => '-- Seleccionar obra social --'));
		$this->array_ocupacion_control = $array_ocupacion = $this->get_array('ocupacion', 'descripcion', 'id', null, array('' => '-- Seleccionar ocupación --'));
		$this->array_prestadora_control = $array_prestadora = $this->get_array('prestadora', 'descripcion', 'id', null, array('' => '-- Seleccionar prestadora --'));
		$this->array_sexo_control = $array_sexo = $this->get_array('sexo', 'descripcion', 'id', null, array('' => '-- Seleccionar sexo --'));

		$this->persona_model->fields['depto_nacimiento']['array'] = $array_depto_nacimiento;
		$this->persona_model->fields['documento_tipo']['array'] = $array_documento_tipo;
		$this->persona_model->fields['documento_tipo']['value'] = 1;
		$this->persona_model->fields['estado_civil']['array'] = $array_estado_civil;
		$this->persona_model->fields['grupo_sanguineo']['array'] = $array_grupo_sanguineo;
		$this->persona_model->fields['localidad']['array'] = $array_localidad;
		$this->persona_model->fields['nacionalidad']['array'] = $array_nacionalidad;
		$this->persona_model->fields['nivel_estudio']['array'] = $array_nivel_estudio;
		$this->persona_model->fields['obra_social']['array'] = $array_obra_social;
		$this->persona_model->fields['ocupacion']['array'] = $array_ocupacion;
		$this->persona_model->fields['prestadora']['array'] = $array_prestadora;
		$this->persona_model->fields['sexo']['array'] = $array_sexo;

		foreach ($this->persona_model->fields as $field_id => $field) {
			if ($field_id !== 'documento' && $field_id !== 'documento_tipo') {
				$this->persona_model->fields[$field_id]['disabled'] = 'TRUE';
			}
		}

		if (substr($cargo->regimen, 1, 6) === '560201') {
			$this->load->model('celador_concepto_model');
			$this->array_celador_concepto_control = $array_celador_concepto = $this->get_array('celador_concepto', 'descripcion', 'id', null, array('' => '-- Seleccionar concepto de celador --'));
			$this->servicio_model->fields['celador_concepto']['array'] = $array_celador_concepto;
		} else {
			unset($this->servicio_model->fields['celador_concepto']);
		}
		$this->load->model('situacion_revista_model');
		$this->load->model('servicio_funcion_model');
		$this->load->model('funcion_model');
		unset($this->servicio_funcion_model->fields['servicio']);
		unset($this->servicio_model->fields['situacion_revista']['input_type']);
		$this->servicio_model->fields['situacion_revista']['readonly'] = TRUE;
		$this->servicio_model->fields['situacion_revista']['value'] = 'Reemplazo';
		unset($this->servicio_model->fields['cuil']);
		unset($this->servicio_model->fields['apellido']);
		unset($this->servicio_model->fields['nombre']);
		$this->servicio_model->fields['dias'] = array('label' => 'Días a cumplir en mes', 'type' => 'number', 'max' => '30', 'min' => '1');
		$this->servicio_model->fields['obligaciones'] = array('label' => 'Oblig. a cumplir en mes', 'type' => 'number', 'step' => '0.5', 'max' => $cargo->carga_horaria ? $cargo->carga_horaria * 4 : '0', 'min' => $cargo->carga_horaria ? '1' : '0');
		$this->load->model('funcion_model');
		$this->array_funcion_control = $array_funcion = $this->get_array('funcion', 'descripcion', 'id', null, array('' => '-- Seleccione función --'));
		$this->array_tarea_control = $this->servicio_funcion_model->fields['tarea']['array'];
		$this->array_tipo_destino_control = $this->servicio_funcion_model->fields['tipo_destino']['array'];

		$this->load->model('planilla_asisnov_model');
		$meses_habilitados = $this->planilla_asisnov_model->get_meses();
		if (!in_array($mes, $meses_habilitados) && $escuela->dependencia_id === '1') {
			show_error('No se encuentra habilitado el mes para realizar altas', 500, 'Acción no permitida');
		}

		$this->set_model_validation_rules($this->servicio_model);
		unset($this->servicio_funcion_model->fields['fecha_desde']);
		$this->set_model_validation_rules($this->servicio_funcion_model);
		$this->form_validation->set_rules('novedad', 'Novedad', 'required|integer');
		$errors = FALSE;
		if (isset($_POST) && !empty($_POST)) {
			$novedad_id = $this->input->post('novedad');
			if (empty($novedad_id)) {
				$this->session->set_flashdata('error', 'Debe seleccionar la novedad sobre la que se debe dar de alta al reemplazo.');
				redirect("servicio/agregar_reemplazo/$mes/$servicio_id/$escuela_id", 'refresh');
			}
			$this->load->model('servicio_novedad_model');
			$novedad = $this->servicio_novedad_model->get(array('id' => $novedad_id));
			if ($escuela->dependencia_id === '1') {
				if ($novedad->fecha_desde > $this->get_date_sql('fecha_alta')) {
					$this->session->set_flashdata('error', 'La fecha de alta debe ser mayor igual a la fecha de inicio de novedad.');
					redirect("servicio/agregar_reemplazo/$mes/$servicio_id/$escuela_id", 'refresh');
				}
			}
			if (empty($this->input->post('tipo_destino'))) {
				$this->array_destino_control = array('' => '');
			} else {
				if ($this->input->post('tipo_destino') === 'escuela') {
					$this->array_destino_control = $this->get_array('escuela', 'escuela', 'id', array('select' => array('id', "CONCAT(numero, ' - ', nombre) as escuela")));
				} elseif ($this->input->post('tipo_destino') === 'area') {
					$this->load->model('areas/area_model');
					$this->array_destino_control = $this->get_array('area', 'area', 'id', array('select' => array('id', "CONCAT(codigo, ' - ', descripcion) as area")));
				} else {
					$this->array_destino_control = array('' => '');
				}
			}
			$persona_id = $this->input->post('persona_id');
			if (empty($persona_id)) {
				$this->set_model_validation_rules($this->persona_model);
				if ($escuela->dependencia_id == '1') {
					$fecha_alta_post = $this->get_date_sql('fecha_alta');
					$fecha_alta = new DateTime($fecha_alta_post);
					if (!in_array($fecha_alta->format('Ym'), $meses_habilitados)) {
						$this->session->set_flashdata('error', 'No puede dar alta fuera del periodo actual');
						redirect("servicio/agregar_reemplazo/$mes/$servicio_id/$escuela_id", 'refresh');
					}
					if ($this->input->post('fecha_baja')) {
						$fecha_baja_post = $this->get_date_sql('fecha_baja');
						$fecha_baja = new DateTime($fecha_baja_post);
						if ($fecha_baja->format('Ym') != $fecha_alta->format('Ym')) {
							$this->session->set_flashdata('error', 'No puede dar baja fuera del periodo actual');
							redirect("servicio/agregar_reemplazo/$mes/$servicio_id/$escuela_id", 'refresh');
						}
					}
				}
				if ($this->input->post('fecha_baja')) {
					if ($this->get_date_sql('fecha_baja') < $this->get_date_sql('fecha_alta')) {
						$this->session->set_flashdata('error', 'Le fecha de baja debe ser superior a la fecha de alta.');
						redirect("servicio/agregar_reemplazo/$mes/$servicio_id/$escuela_id", 'refresh');
					}
				}
			} else {
				$this->form_validation->set_rules('documento', 'Documento', 'required|integer');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				if (empty($persona_id)) {
					$trans_ok &= $this->persona_model->create(array(
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
						'prestadora_id' => $this->input->post('prestadora'),
						'fecha_nacimiento' => $this->get_date_sql('fecha_nacimiento'),
						'fecha_defuncion' => $this->get_date_sql('fecha_defuncion'),
						'obra_social_id' => $this->input->post('obra_social'),
						'grupo_sanguineo_id' => $this->input->post('grupo_sanguineo'),
						'depto_nacimiento_id' => $this->input->post('depto_nacimiento'),
						'lugar_traslado_emergencia' => $this->input->post('lugar_traslado_emergencia'),
						'nacionalidad_id' => $this->input->post('nacionalidad')
						), FALSE);

					$persona_id = $this->persona_model->get_row_id();
				} else {
					$persona = $this->persona_model->get(array('id' => $persona_id));
					if (empty($persona)) {
						$persona_id = FALSE;
					} elseif (empty($persona->cuil)) {
						$trans_ok &= $this->persona_model->update(array(
							'id' => $persona->id,
							'cuil' => $this->input->post('cuil'),
							'apellido' => $this->input->post('apellido'),
							'nombre' => $this->input->post('nombre'),
							), FALSE);
					}
				}

				if ($trans_ok && $persona_id && !empty($novedad)) {
					$trans_ok &= $this->servicio_model->create(array(
						'persona_id' => $persona_id,
						'cargo_id' => $cargo->id,
						'fecha_alta' => $this->get_date_sql('fecha_alta'),
						'fecha_baja' => $this->get_date_sql('fecha_baja'),
						'reemplazado_id' => $servicio->id,
						'situacion_revista_id' => 2,
						'articulo_reemplazo_id' => $novedad->novedad_tipo_id,
						'celador_concepto_id' => $this->input->post('celador_concepto'),
						'observaciones' => $this->input->post('observaciones')
						), FALSE);

					$servicio_id = $this->servicio_model->get_row_id();
					if ($trans_ok) {
						$trans_ok &= $this->servicio_funcion_model->create(array(
							'servicio_id' => $servicio_id,
							'celador_concepto' => $this->input->post('celador_concepto'),
							'funcion_id' => $this->input->post('funcion'),
							'destino' => $this->input->post('destino'),
							'norma' => $this->input->post('norma'),
							'tarea' => $this->input->post('tarea'),
							'carga_horaria' => $this->input->post('carga_horaria'),
							'fecha_desde' => $this->get_date_sql('fecha_alta')
							), FALSE);
						$this->load->model('planilla_asisnov_model');
						$planilla_id = $this->planilla_asisnov_model->get_planilla_abierta($cargo->escuela_id, $this->get_date_sql('fecha_alta', 'd/m/Y', 'Ym'), 2);
						$trans_ok &= $planilla_id > 0;
						if ($trans_ok) {
							$trans_ok &= $this->servicio_novedad_model->create(array(
								'servicio_id' => $servicio_id,
								'ames' => $this->get_date_sql('fecha_alta', 'd/m/Y', 'Ym'),
								'novedad_tipo_id' => 1,
								'fecha_desde' => $this->get_date_sql('fecha_alta'),
								'fecha_hasta' => $this->input->post('fecha_baja') ? $this->get_date_sql('fecha_baja') : $this->get_date_sql('fecha_alta'),
								'dias' => $this->input->post('dias'),
								'obligaciones' => $cargo->regimen_tipo_id === '1' ? '0' : $this->input->post('obligaciones'),
								'estado' => 'Cargado',
								'planilla_alta_id' => $planilla_id
								), FALSE);
						}
					}
				}

				if ($this->db->trans_status() && $trans_ok && $persona_id && !empty($novedad)) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->servicio_model->get_msg());
					redirect("servicio/listar/$cargo->escuela_id/$mes", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar.';
					if ($this->persona_model->get_error()) {
						$errors .= '<br>' . $this->persona_model->get_error();
					}
					if ($this->servicio_model->get_error()) {
						$errors .= '<br>' . $this->servicio_model->get_error();
					}
					if ($this->servicio_funcion_model->get_error()) {
						$errors .= '<br>' . $this->servicio_funcion_model->get_error();
					}
				}
			}
		}
		$data['error'] = validation_errors() ? validation_errors() : ($errors ? $errors : $this->session->flashdata('error'));

		$this->servicio_model->fields['escuela']['value'] = "$cargo->escuela_numero $cargo->escuela";
		$this->servicio_model->fields['regimen']['value'] = $cargo->regimen;
		$this->servicio_model->fields['carga_horaria']['value'] = $cargo->carga_horaria;
		$this->servicio_model->fields['division']['value'] = $cargo->division;
		$this->servicio_model->fields['espacio_curricular']['value'] = $cargo->espacio_curricular;
		$this->servicio_model->fields['fecha_baja']['readonly'] = TRUE;

		$this->servicio_funcion_model->fields['funcion']['array'] = $array_funcion;

		$data['fields'] = $this->build_fields($this->persona_model->fields);
		$data['fields_servicio'] = $this->build_fields($this->servicio_model->fields);
		$data['fields_servicio']['fecha_baja']['form'] = '			<div class="input-group">
				' . $data['fields_servicio']['fecha_baja']['form'] . '
				<span class="input-group-addon">
					<input type="checkbox" id="check_fecha_baja">
				</span>
			</div>
';
		$data['fields_funcion'] = $this->build_fields($this->servicio_funcion_model->fields);
		$data['fn_mostrar_campos'] = $this->funcion_model->get_fn_mostrar_campos();
		$data['fecha_inicio_actual'] = (new DateTime($mes . '01'))->format('d/m/Y');
		$data['fecha_fin_actual'] = (new DateTime($mes . '01 +1 month -1 day'))->format('d/m/Y');
		$data['novedades'] = $novedades;
		$data['escuela'] = $escuela;
		$data['mes_id'] = $mes;
		$data['mes'] = $this->nombres_meses[substr($mes, 4, 2)] . '\'' . substr($mes, 2, 2);
		$data['cargo'] = $cargo;
		$this->load->model('horario_model');
		$data['horarios'] = $this->horario_model->get_horarios($cargo->id);
		$data['servicio'] = $cargo;
		$data['txt_btn'] = 'Seleccionar persona';
		$data['class'] = array('agregar' => 'active disabled btn-app-zetta-active');
		$data['title'] = TITLE . ' - Seleccionar persona';
		$this->load_template('servicio/servicio_agregar_reemplazo', $data);
	}

	public function modal_asignar_reemplazado($servicio_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $servicio_id == NULL || !ctype_digit($servicio_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("servicio/ver/$servicio_id");
		}

		$servicio = $this->servicio_model->get_one($servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$escuela = $this->escuela_model->get_one($servicio->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$servicios = $this->servicio_model->get(array(
			'join' => array(
				array('persona', 'servicio.persona_id=persona.id', 'left', array('persona.nombre', 'persona.apellido')),
				array('situacion_revista', 'servicio.situacion_revista_id=situacion_revista.id', 'left', array('situacion_revista.descripcion as situacion_revista'))
			),
			'id !=' => $servicio->id,
			'cargo_id' => $servicio->cargo_id,
		));

		$array_servicio = array();
		if (!empty($servicios)) {
			foreach ($servicios as $servicio_db) {
				$array_servicio[$servicio_db->id] = $servicio_db->id;
			}
		}
		$this->load->model('novedad_tipo_model');
		$this->array_servicio_reemplazado_control = $array_servicio;
		$this->array_servicio_reemplazado_control[''] = 'Sin servicio';

		$this->array_articulo_control = $array_articulo = $this->novedad_tipo_model->get_tipos_novedades('R');
		$model = new stdClass();
		$model->fields = array(
			'articulo' => array('label' => 'Artículo', 'input_type' => 'combo', 'array' => $array_articulo),
			'servicio_reemplazado' => array('label' => 'Servicio reemplazado', 'input_type' => 'combo', 'array' => $array_servicio),
		);
		if (!empty($servicios)) {
			$model->fields['servicio_reemplazado']['required'] = TRUE;
		}

		$this->set_model_validation_rules($model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->servicio_model->update(array(
					'id' => $servicio->id,
					'reemplazado_id' => empty($servicios) ? 'NULL' : ($this->input->post('servicio_reemplazado') ? $this->input->post('servicio_reemplazado') : 'NULL'),
					'articulo_reemplazo_id' => $this->input->post('articulo'),
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->servicio_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->servicio_model->get_error());
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect("servicio/editar/$servicio_id", 'refresh');
		}

		$data['fields'] = $this->build_fields($model->fields);
		$data['servicio'] = $servicio;
		$data['escuela'] = $escuela;
		$data['servicios'] = $servicios;
		$data['txt_btn'] = 'Asignar';
		$data['title'] = 'Asignar servicio reemplazado';
		$this->load->view('servicio/servicio_modal_asignar_reemplazado', $data);
	}

	public function excel($escuela_id = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || ($mes != NULL && !ctype_digit($mes))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($mes) || empty(DateTime::createFromFormat('Ym', $mes))) {
			$this->load->model('planilla_asisnov_model');
			$mes = $this->planilla_asisnov_model->get_mes_actual();
			redirect("servicio/excel/$escuela_id/$mes", 'refresh');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$campos = array(
			'A' => array('Escuela', 10),
			'B' => array('Anexo', 10),
			'C' => array('Persona', 50),
			'D' => array('Alta', 11),
			'E' => array('Liquidación', 15),
			'F' => array('Sit. Revista', 15),
			'G' => array('Régimen', 40),
			'H' => array('Hs Cátedra', 15),
			'I' => array('Materia', 40),
			'J' => array('División', 20),
			'K' => array('Baja', 11),
			'L' => array('Función (Fn)', 30),
			'M' => array('Fn Destino', 11),
			'N' => array('Fn Norma', 11),
			'O' => array('Fn Tarea', 11),
			'P' => array('Fn Carga Horaria', 11),
			'Q' => array('Fn Desde', 11),
			'R' => array('Observaciones', 50)
		);
		$servicios = $this->db->select('e.numero, e.anexo, CONCAT(COALESCE(p.cuil, p.documento), \' \', p.apellido, \' \', p.nombre) as persona, s.fecha_alta, s.liquidacion, sr.descripcion as situacion_revista, CONCAT(r.codigo, \' \', r.descripcion) as regimen, c.carga_horaria, m.descripcion as materia, CONCAT(cu.descripcion, \' \', d.division) division, s.fecha_baja, sf.detalle as funcion_detalle, sf.destino as funcion_destino, sf.norma as funcion_norma, sf.tarea as funcion_tarea, sf.carga_horaria as funcion_carga_horaria, sf.fecha_desde as funcion_desde, s.observaciones')
				->from('servicio s')
				->join('situacion_revista sr', 'sr.id = s.situacion_revista_id')
				->join('cargo c', 'c.id = s.cargo_id')
				->join('escuela e', 'e.id = c.escuela_id')
				->join('servicio_funcion sf', 'sf.servicio_id = s.id AND sf.fecha_hasta IS NULL', 'left')
				->join('division d', 'd.id = c.division_id', 'left')
				->join('curso cu', 'cu.id = d.curso_id', 'left')
				->join('espacio_curricular ec', 'ec.id = c.espacio_curricular_id', 'left')
				->join('materia m', 'ec.materia_id = m.id', 'left')
				->join('regimen r', 'r.id = c.regimen_id AND r.planilla_modalidad_id=1', '')
				->join('persona p', 'p.id = s.persona_id', 'left')
				->where('e.id', $escuela->id)
				->where("'$mes' BETWEEN COALESCE(DATE_FORMAT(s.fecha_alta,'%Y%m'),'000000') AND COALESCE(DATE_FORMAT(s.fecha_baja,'%Y%m'),'999999')")
				->group_by('s.id')
				->get()->result_array();

		if (!empty($servicios)) {
			$this->exportar_excel(array('title' => "Servicios $escuela->numero $mes"), $campos, $servicios);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect("servicio/listar/$escuela->id/$mes", 'refresh');
		}
	}

	public function modal_baja($mes = NULL, $servicio_id = NULL, $escuela_id = NULL, $fecha_baja_s = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$mes == NULL || $servicio_id == NULL || $escuela_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($servicio_id) || !ctype_digit($escuela_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$this->load->model('novedad_tipo_model');
		$this->load->model('servicio_novedad_model');
		$array_novedad_tipo = $this->novedad_tipo_model->get_tipos_novedades('B');
		$this->array_novedad_tipo_control = $array_novedad_tipo;
		unset($this->servicio_novedad_model->fields['estado']);
		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($servicio_id);
		if (empty($servicio) || $escuela_id !== $servicio->escuela_id) {
			$this->modal_error('No se encontró el servicio', 'Registro no encontrado');
			return;
		}

		$this->set_model_validation_rules($this->servicio_novedad_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$this->load->model('planilla_asisnov_model');
				$planilla_id = $this->planilla_asisnov_model->get_planilla_abierta($escuela_id, $mes, $servicio->planilla_tipo_id);
				$trans_ok &= $planilla_id > 0;

				if ($trans_ok) {
					$trans_ok &= $this->servicio_novedad_model->create(array(
						'servicio_id' => $servicio->id,
						'ames' => $mes,
						'novedad_tipo_id' => $this->input->post('novedad_tipo'),
						'fecha_desde' => $this->get_date_sql('fecha_desde'),
						'fecha_hasta' => $this->get_date_sql('fecha_hasta'),
						'dias' => $this->input->post('dias'),
						'obligaciones' => $servicio->regimen_tipo_id === '1' ? '0' : $this->input->post('obligaciones'),
						'estado' => 'Cargado',
						'planilla_alta_id' => $planilla_id
						), FALSE);
					$trans_ok &= $this->servicio_model->update(array(
						'id' => $servicio_id,
						'fecha_baja' => $this->get_date_sql('fecha_desde'),
						'motivo_baja' => $array_novedad_tipo[$this->input->post('novedad_tipo')]
						), FALSE);
				}

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->servicio_novedad_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->servicio_novedad_model->get_error());
				}
				redirect("servicio/listar/$escuela_id/$mes", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("servicio/listar/$escuela_id/$mes", 'refresh');
			}
		}
		$novedades = $this->servicio_novedad_model->get(array(
			'join' => $this->servicio_novedad_model->default_join,
			'servicio_id' => $servicio->id,
			'ames' => $mes,
			'where' => array('planilla_baja_id IS NULL'),
		));

		$fechas_inhabilitadas = array();
		if (!empty($novedades)) {
			foreach ($novedades as $novedad) {
				$desde = new DateTime($novedad->fecha_desde);
				$hasta = new DateTime($novedad->fecha_hasta);
				for ($fecha = $desde; $fecha <= $hasta; $fecha->modify('+1 day')) {
					$fechas_inhabilitadas[] = $fecha->format('d/m/Y');
				}
			}
		}

		$this->servicio_novedad_model->fields['novedad_tipo']['array'] = $array_novedad_tipo;
		if (!empty($fecha_baja_s)) {
			$this->servicio_novedad_model->fields['novedad_tipo']['value'] = 202;
			$this->servicio_novedad_model->fields['fecha_desde']['value'] = (new DateTime($fecha_baja_s))->format('d/m/Y');
		}

		$data['servicio'] = $servicio;
		if ($servicio->regimen_tipo_id !== '1') {
			$this->load->model('horario_model');
			$data['horarios'] = $this->horario_model->get_horarios($servicio->cargo_id);
		}
		$data['fecha_desde'] = (new DateTime($fecha_baja_s))->format('d/m/Y');
		$data['fecha_hasta'] = (new DateTime($mes . '01 +1 month -1 day'))->format('d/m/Y');
		$data['reemplazo_corto'] = TRUE;
		$data['novedades'] = $novedades;
		$data['fechas_inhabilitadas'] = $fechas_inhabilitadas;
		$this->servicio_novedad_model->fields['novedad_tipo']['label'] = 'Motivo Baja';
		$data['fields'] = $this->build_fields($this->servicio_novedad_model->fields);

		$data['title'] = "Dar de baja a <b>$servicio->apellido, $servicio->nombre</b> ($servicio->cuil <b>" . substr($servicio->liquidacion, -2) . "</b>)";
		$this->load->view('servicio_novedad/servicio_novedad_modal_baja', $data);
	}
}
/* End of file Servicio.php */
/* Location: ./application/controllers/Servicio.php */
