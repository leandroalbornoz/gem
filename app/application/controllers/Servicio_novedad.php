<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio_novedad extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->load->model('servicio_novedad_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ESCUELA_ALUM,ROL_ASISTENCIA_DIVISION,ROL_DOCENTE_CURSADA));
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_ESCUELA_ALUM, ROL_GRUPO_ESCUELA_CONSULTA))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'menu/servicio_novedad';
	}

	public function listar($escuela_id = NULL, $mes = NULL, $pendientes = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || ($mes != NULL && !ctype_digit($mes))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($mes) || empty(DateTime::createFromFormat('Ym', $mes))) {
			$this->load->model('planilla_asisnov_model');
			$mes = $this->planilla_asisnov_model->get_mes_actual();
			redirect("servicio_novedad/listar/$escuela_id/$mes", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		if ($pendientes === '0') {
			$tableData = array(
				'columns' => array(
					array('label' => 'Persona', 'data' => 'persona', 'width' => 19, 'class' => 'text-sm'),
					array('label' => 'Liquidación', 'data' => 'liquidacion', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'S.R.', 'data' => 'situacion_revista', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'Cur', 'data' => 'curso', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Div', 'data' => 'division', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Régimen/Materia', 'data' => 'regimen_materia', 'width' => 21, 'class' => 'text-sm'),
					array('label' => 'Hs.', 'data' => 'carga_horaria', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Art.', 'data' => 'articulo', 'width' => 3),
					array('label' => 'Novedad', 'data' => 'novedad_tipo', 'width' => 10, 'class' => 'text-sm'),
					array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => 'Dias', 'data' => 'dias', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Oblig', 'data' => 'obligaciones', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all')
				),
				'table_id' => 'servicio_novedad_table',
				'source_url' => "servicio_novedad/listar_data/$escuela_id/$mes/$pendientes/{$this->rol->codigo}/{$this->rol->entidad_id}",
				'order' => array(array(1, 'asc'), array(5, 'asc'), array(6, 'asc'), array(7, 'asc')),
				'reuse_var' => TRUE,
				'initComplete' => "complete_servicio_novedad_table",
				'footer' => TRUE,
				'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
			);

			$tableData_o = array(
				'columns' => array(
					array('label' => 'Persona', 'data' => 'persona', 'width' => 19, 'class' => 'text-sm'),
					array('label' => 'Liquidación', 'data' => 'liquidacion', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'S.R.', 'data' => 'situacion_revista', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'Régimen/Origen', 'data' => 'regimen_origen', 'width' => 27, 'class' => 'text-sm'),
					array('label' => 'Hs.', 'data' => 'carga_horaria', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Art.', 'data' => 'articulo', 'width' => 3),
					array('label' => 'Novedad', 'data' => 'novedad_tipo', 'width' => 10, 'class' => 'text-sm'),
					array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => 'Dias', 'data' => 'dias', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Oblig', 'data' => 'obligaciones', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all')
				),
				'table_id' => 'servicio_novedad_o_table',
				'source_url' => "servicio_novedad/listar_data_otros/$escuela_id/$mes/$pendientes/{$this->rol->codigo}/{$this->rol->entidad_id}",
				'order' => array(array(0, 'asc'), array(3, 'asc'), array(4, 'asc')),
				'reuse_var' => TRUE,
				'initComplete' => "complete_servicio_novedad_o_table",
				'footer' => TRUE,
				'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
			);
		} else {
			$tableData = array(
				'columns' => array(
					array('label' => 'Persona', 'data' => 'persona', 'width' => 22, 'class' => 'text-sm'),
					array('label' => 'Liquidación', 'data' => 'liquidacion', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'S.R.', 'data' => 'situacion_revista', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'Cur', 'data' => 'curso', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Div', 'data' => 'division', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Régimen/Materia', 'data' => 'regimen_materia', 'width' => 24, 'class' => 'text-sm'),
					array('label' => 'Hs.', 'data' => 'carga_horaria', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Art.', 'data' => 'articulo', 'width' => 3),
					array('label' => 'Novedad', 'data' => 'novedad_tipo', 'width' => 10, 'class' => 'text-sm'),
					array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all')
				),
				'table_id' => 'servicio_novedad_table',
				'source_url' => "servicio_novedad/listar_data/$escuela_id/$mes/$pendientes/{$this->rol->codigo}/{$this->rol->entidad_id}",
				'order' => array(array(1, 'asc'), array(5, 'asc'), array(6, 'asc'), array(7, 'asc')),
				'reuse_var' => TRUE,
				'initComplete' => "complete_servicio_novedad_table",
				'footer' => TRUE,
				'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
			);
			$tableData_o = array(
				'columns' => array(
					array('label' => 'Persona', 'data' => 'persona', 'width' => 22, 'class' => 'text-sm'),
					array('label' => 'Liquidación', 'data' => 'liquidacion', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'S.R.', 'data' => 'situacion_revista', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'Régimen/Origen', 'data' => 'regimen_origen', 'width' => 31, 'class' => 'text-sm'),
					array('label' => 'Hs.', 'data' => 'carga_horaria', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Art.', 'data' => 'articulo', 'width' => 3),
					array('label' => 'Novedad', 'data' => 'novedad_tipo', 'width' => 10, 'class' => 'text-sm'),
					array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all')
				),
				'table_id' => 'servicio_novedad_o_table',
				'source_url' => "servicio_novedad/listar_data_otros/$escuela_id/$mes/$pendientes/{$this->rol->codigo}/{$this->rol->entidad_id}",
				'order' => array(array(0, 'asc'), array(3, 'asc'), array(4, 'asc')),
				'reuse_var' => TRUE,
				'initComplete' => "complete_servicio_novedad_o_table",
				'footer' => TRUE,
				'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
			);
		}

		$data['escuela'] = $escuela;
		$data['mes_id'] = $mes;
		$data['mes'] = $this->nombres_meses[substr($mes, 4, 2)] . '\'' . substr($mes, 2, 2);
		$fecha = DateTime::createFromFormat('Ymd', $mes . '01');
		$data['fecha'] = $fecha->format('d/m/Y');
		$data['pendientes'] = $pendientes;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['html_table_o'] = buildHTML($tableData_o);
		$data['js_table_o'] = buildJS($tableData_o);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Novedades de servicios';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		if ($pendientes === '1') {
			$this->load_template('servicio_novedad/servicio_novedad_pendientes', $data);
		} else {
			$this->load_template('servicio_novedad/servicio_novedad_listar', $data);
		}
	}

	public function listar_data($escuela_id = NULL, $mes = NULL, $pendientes = '0', $rol_codigo = NULL, $entidad_id = '') {
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
			->select('servicio_novedad.id, servicio_novedad.fecha_desde, CASE novedad_tipo.id WHEN 1 THEN \'\' ELSE servicio_novedad.fecha_hasta END as fecha_hasta, CASE novedad_tipo.id WHEN 1 THEN \'\' ELSE servicio_novedad.dias END as dias,CASE novedad_tipo.id WHEN 1 THEN \'\' ELSE servicio_novedad.obligaciones END as obligaciones, '
				. 'CASE novedad_tipo.id WHEN 1 THEN \'Alta\' ELSE CONCAT(novedad_tipo.articulo, \'-\', novedad_tipo.inciso) END as articulo, novedad_tipo.descripcion_corta as novedad_tipo, novedad_tipo.novedad, '
				. 'servicio.liquidacion, cargo.carga_horaria, CONCAT(persona.cuil, \'<br>\', persona.apellido, \', \', persona.nombre) as persona, '
				. 'situacion_revista.descripcion as situacion_revista,division.division, curso.descripcion as curso, '
				. 'CONCAT(regimen.descripcion, \'<br>\', COALESCE(materia.descripcion, \'\')) as regimen_materia, escuela.id as escuela_id, servicio_novedad.origen_id, planilla_asisnov.fecha_cierre')
			->unset_column('id')
			->from('servicio_novedad')
			->join('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id')
			->join('servicio', 'servicio.id = servicio_novedad.servicio_id')
			->join('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id')
			->join('persona', 'persona.id = servicio.persona_id')
			->join('cargo', 'cargo.id = servicio.cargo_id')
			->join('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1')
			->join('escuela', 'cargo.escuela_id = escuela.id')
			->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
			->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
			->join('division', 'cargo.division_id = division.id', 'left')
			->join('curso', 'division.curso_id = curso.id', 'left')
			->join('planilla_asisnov', 'servicio_novedad.planilla_alta_id = planilla_asisnov.id', 'left')
			->where('servicio_novedad.servicio_funcion_id IS NULL')
			->where('servicio_novedad.ames', $mes)
			->where('servicio_novedad.planilla_baja_id IS NULL')
			->where("novedad_tipo.novedad = 'N'")
			->where('escuela.id', $escuela_id);


		if ($pendientes === '1') {
			$this->datatables->where('servicio_novedad.estado', 'Pendiente');
			if ($this->edicion) {
				$this->datatables->add_column('menu', '$1', 'dt_column_servicio_novedad_menu(\'' . $mes . '\', id, escuela_id, novedad, 0, 1, fecha_cierre, origen_id)');
			} else {
				$this->datatables->add_column('menu', '<a class="btn btn-xs btn-default" href="servicio_novedad/modal_ver/' . $mes . '/$1/$2/0" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>', 'id, escuela_id');
			}
		} else {
			$this->datatables->where('servicio_novedad.estado !=', 'Pendiente');
			if ($this->edicion) {
				$this->datatables->add_column('menu', '$1', 'dt_column_servicio_novedad_menu(\'' . $mes . '\', id, escuela_id, novedad, 0, 0, fecha_cierre, origen_id)');
			} else {
				$this->datatables->add_column('menu', '<a class="btn btn-xs btn-default" href="servicio_novedad/modal_ver/' . $mes . '/$1/$2/0" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>', 'id, escuela_id');
			}
		}
		echo $this->datatables->generate();
	}

	public function listar_data_otros($escuela_id = NULL, $mes = NULL, $pendientes = '0', $rol_codigo = NULL, $entidad_id = '') {
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
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select('servicio_novedad.id, servicio_novedad.fecha_desde, servicio_novedad.fecha_hasta, servicio_novedad.dias, servicio_novedad.obligaciones, '
				. 'CONCAT(novedad_tipo.articulo, \'-\', novedad_tipo.inciso) as articulo, novedad_tipo.descripcion_corta as novedad_tipo, novedad_tipo.novedad, '
				. 'servicio.liquidacion, cargo.carga_horaria, CONCAT(persona.cuil, \'<br>\', persona.apellido, \', \', persona.nombre) as persona, '
				. 'situacion_revista.descripcion as situacion_revista, servicio_funcion.escuela_id, '
				. 'CONCAT(regimen.descripcion, \'<br>\', COALESCE(CONCAT(area.codigo, \' \', area.descripcion), CONCAT(escuela.numero, \' \', escuela.nombre, CASE WHEN anexo = 0 THEN \'\' ELSE CONCAT(\'/\', anexo) END))) as regimen_origen, servicio_novedad.origen_id, planilla_asisnov.fecha_cierre')
			->unset_column('id')
			->from('servicio_novedad')
			->join('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id')
			->join('servicio', 'servicio.id = servicio_novedad.servicio_id')
			->join('servicio_funcion', 'servicio.id = servicio_funcion.servicio_id AND servicio_funcion.fecha_hasta IS NULL AND servicio_funcion.id = servicio_novedad.servicio_funcion_id')
			->join('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id')
			->join('persona', 'persona.id = servicio.persona_id')
			->join('cargo', 'cargo.id = servicio.cargo_id')
			->join('escuela', 'escuela.id = cargo.escuela_id', 'left')
			->join('area', 'cargo.area_id = area.id', 'left')
			->join('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1')
			->join('planilla_asisnov', 'servicio_novedad.planilla_alta_id = planilla_asisnov.id', 'left')
			->where('servicio_novedad.ames', $mes)
			->where('servicio_novedad.planilla_baja_id IS NULL')
			->where("novedad_tipo.novedad = 'N'")
			->where('servicio_funcion.escuela_id', $escuela_id)
			->add_column('', '', 'id');

		if ($pendientes === '1') {
			$this->datatables->where('servicio_novedad.estado', 'Pendiente');
			if ($this->edicion) {
				$this->datatables->add_column('menu', '$1', 'dt_column_servicio_novedad_menu(\'' . $mes . '\', id, escuela_id, novedad, 1, 1)');
			} else {
				$this->datatables->add_column('menu', '<a class="btn btn-xs btn-default" href="servicio_novedad/modal_ver/' . $mes . '/$1/$2/0" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>', 'id, escuela_id');
			}
		} else {
			$this->datatables->where('servicio_novedad.estado !=', 'Pendiente');
			if ($this->edicion) {
				$this->datatables->add_column('menu', '$1', 'dt_column_servicio_novedad_menu(\'' . $mes . '\', id, escuela_id, novedad, 1, 0)');
			} else {
				$this->datatables->add_column('menu', '<a class="btn btn-xs btn-default" href="servicio_novedad/modal_ver/' . $mes . '/$1/$2/0" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>', 'id, escuela_id');
			}
		}
		echo $this->datatables->generate();
	}

	public function cambiar_mes($escuela_id, $mes, $pendientes = '0') {
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
		if ($pendientes === '1') {
			redirect("servicio_novedad/listar/$escuela_id/$mes/$pendientes", 'refresh');
		}
		redirect("servicio_novedad/listar/$escuela_id/$mes", 'refresh');
	}

	public function modal_agregar($mes = NULL, $servicio_id = NULL, $escuela_id = NULL) {
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

		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($servicio_id);
		if (empty($servicio) || $escuela_id !== $servicio->escuela_id) {
			$this->modal_error('No se encontró el servicio', 'Registro no encontrado');
			return;
		}

		$this->load->model('novedad_tipo_model');
		$tipos_novedades = $this->novedad_tipo_model->get_tipos_novedades('N', TRUE);
		$array_novedad_tipo = $tipos_novedades[0];
		$this->array_novedad_tipo_control = $array_novedad_tipo;
		unset($this->servicio_novedad_model->fields['estado']);

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
					$servicio_novedad_id = $this->servicio_novedad_model->get_row_id();
				}

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->servicio_novedad_model->get_msg());
					if (!empty($servicio_novedad_id)) {
						$this->load->model('llamados/llamado_model');
						if ($this->llamado_model->realizar_novedad($servicio_novedad_id)) {
							redirect("llamados/llamado/agregar_novedad/$servicio_novedad_id", 'refresh');
						}
					}
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar.';
					if ($this->planilla_asisnov_model->get_error())
						$errors .= '<br>' . $this->planilla_asisnov_model->get_error();
					if ($this->servicio_novedad_model->get_error())
						$errors .= '<br>' . $this->servicio_novedad_model->get_error();
					$this->session->set_flashdata('error', $errors);
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
			'where' => array('planilla_baja_id IS NULL', 'servicio_funcion_id IS NULL'),
		));

		$fechas_inhabilitadas = array();
		if (!empty($novedades)) {
			foreach ($novedades as $novedad) {
				if ($novedad->concomitante === 'N') {
					$desde = new DateTime($novedad->fecha_desde);
					$hasta = new DateTime($novedad->fecha_hasta);
					for ($fecha = $desde; $fecha <= $hasta; $fecha->modify('+1 day')) {
						$fechas_inhabilitadas[] = $fecha->format('d/m/Y');
					}
				}
			}
		}

		$this->servicio_novedad_model->fields['novedad_tipo']['array'] = $array_novedad_tipo;
		$data['servicio'] = $servicio;
		if ($servicio->regimen_tipo_id !== '1') {
			$this->load->model('horario_model');
			$data['horarios'] = $this->horario_model->get_horarios($servicio->cargo_id);
		}
		$fecha_inicio_mes = new DateTime($mes . '01');
		$fecha_fin_mes = new DateTime($mes . '01 +1 month -1 day');
		$data['fecha_desde'] = $fecha_inicio_mes->format('d/m/Y');
		$data['fecha_hasta'] = '';
		if (!empty($servicio->fecha_alta)) {
			$fecha_alta = new DateTime($servicio->fecha_alta);
			if ($fecha_alta >= $fecha_inicio_mes) {
				$data['fecha_desde'] = $fecha_alta->format('d/m/Y');
			}
		}
		if (!empty($servicio->fecha_baja)) {
			$fecha_baja = new DateTime($servicio->fecha_baja);
			if ($fecha_baja <= $fecha_fin_mes) {
				$data['fecha_hasta'] = $fecha_baja->format('d/m/Y');
			}
		}

		$data['ames'] = $mes;
		$data['novedades'] = $novedades;
		$data['fechas_inhabilitadas'] = $fechas_inhabilitadas;
		$data['novedades_concomitantes'] = $tipos_novedades[1];
		$data['fields'] = $this->build_fields($this->servicio_novedad_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = "Agregar novedad a <b>$servicio->apellido, $servicio->nombre</b> ($servicio->cuil <b>" . substr($servicio->liquidacion, -2) . "</b>)";
		$data['operacion'] = 'Agregar';
		if (!empty($_GET['redirect_url'])) {
			$data['url_redireccion'] = urldecode($_GET['redirect_url']);
		} else {
			$data['url_redireccion'] = FALSE;
		}
		$this->load->view('servicio_novedad/servicio_novedad_modal_abm', $data);
	}

	public function modal_agregar_funcion($mes = NULL, $id = NULL, $escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$mes == NULL || $id == NULL || $escuela_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($id) || !ctype_digit($escuela_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('servicio_funcion_model');
		$servicio_funcion = $this->servicio_funcion_model->get_one($id);
		if (empty($servicio_funcion) || $escuela_id !== $servicio_funcion->escuela_id) {
			$this->modal_error('No se encontró el registro a agregar', 'Registro no encontrado');
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

		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($servicio_funcion->servicio_id);
		if (empty($servicio) || $escuela_id !== $servicio_funcion->escuela_id) {
			$this->modal_error('No se encontró el servicio', 'Registro no encontrado');
			return;
		}

		$this->load->model('novedad_tipo_model');
		$tipos_novedades = $this->novedad_tipo_model->get_tipos_novedades('N', TRUE);
		$array_novedad_tipo = $tipos_novedades[0];
		$this->array_novedad_tipo_control = $array_novedad_tipo;
		unset($this->servicio_novedad_model->fields['estado']);

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
						'servicio_funcion_id' => $servicio_funcion->id,
						'ames' => $mes,
						'novedad_tipo_id' => $this->input->post('novedad_tipo'),
						'fecha_desde' => $this->get_date_sql('fecha_desde'),
						'fecha_hasta' => $this->get_date_sql('fecha_hasta'),
						'dias' => $this->input->post('dias'),
						'obligaciones' => $servicio->regimen_tipo_id === '1' ? '0' : $this->input->post('obligaciones'),
						'estado' => 'Cargado',
						'planilla_alta_id' => $planilla_id
						), FALSE);
				}

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->servicio_novedad_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar.';
					if ($this->planilla_asisnov_model->get_error())
						$errors .= '<br>' . $this->planilla_asisnov_model->get_error();
					if ($this->servicio_novedad_model->get_error())
						$errors .= '<br>' . $this->servicio_novedad_model->get_error();
					$this->session->set_flashdata('error', $errors);
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
			'servicio_funcion_id' => $servicio_funcion->id,
			'ames' => $mes,
			'where' => array('planilla_baja_id IS NULL'),
		));

		$fechas_inhabilitadas = array();
		if (!empty($novedades)) {
			foreach ($novedades as $novedad) {
				if ($novedad->concomitante === 'N') {
					$desde = new DateTime($novedad->fecha_desde);
					$hasta = new DateTime($novedad->fecha_hasta);
					for ($fecha = $desde; $fecha <= $hasta; $fecha->modify('+1 day')) {
						$fechas_inhabilitadas[] = $fecha->format('d/m/Y');
					}
				}
			}
		}

		$this->servicio_novedad_model->fields['novedad_tipo']['array'] = $array_novedad_tipo;
		$data['servicio'] = $servicio;
		if ($servicio->regimen_tipo_id !== '1') {
			$this->load->model('horario_model');
			$data['horarios'] = $this->horario_model->get_horarios($servicio->cargo_id);
		}

		$fecha_inicio_mes = new DateTime($mes . '01');
		$fecha_fin_mes = new DateTime($mes . '01 +1 month -1 day');
		$data['fecha_desde'] = $fecha_inicio_mes->format('d/m/Y');
		$data['fecha_hasta'] = '';
		if (!empty($servicio->fecha_alta)) {
			$fecha_alta = new DateTime($servicio->fecha_alta);
			if ($fecha_alta >= $fecha_inicio_mes) {
				$data['fecha_desde'] = $fecha_alta->format('d/m/Y');
			}
		}
		if (!empty($servicio->fecha_baja)) {
			$fecha_baja = new DateTime($servicio->fecha_baja);
			if ($fecha_baja <= $fecha_fin_mes) {
				$data['fecha_hasta'] = $fecha_baja->format('d/m/Y');
			}
		}

		$data['ames'] = $mes;
		$data['novedades'] = $novedades;
		$data['fechas_inhabilitadas'] = $fechas_inhabilitadas;
		$data['novedades_concomitantes'] = $tipos_novedades[1];
		$data['fields'] = $this->build_fields($this->servicio_novedad_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = "Agregar novedad a <b>$servicio->apellido, $servicio->nombre</b> ($servicio->cuil <b>" . substr($servicio->liquidacion, -2) . "</b>)";
		$data['operacion'] = 'Agregar';
		if (!empty($_GET['redirect_url'])) {
			$data['url_redireccion'] = urldecode($_GET['redirect_url']);
		} else {
			$data['url_redireccion'] = FALSE;
		}
		$this->load->view('servicio_novedad/servicio_novedad_modal_abm', $data);
	}

	public function modal_baja($mes = NULL, $servicio_id = NULL, $escuela_id = NULL) {
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

		$this->load->model('planilla_asisnov_model');
		$meses_habilitados = $this->planilla_asisnov_model->get_meses();
		if (!in_array($mes, $meses_habilitados) && $escuela->dependencia_id === '1') {
			$this->modal_error('No se puede agregar bajas fuera del periodo actual', 'Acción no autorizada');
			return;
		}
		$this->load->model('novedad_tipo_model');
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
		$data['servicio'] = $servicio;
		if ($servicio->regimen_tipo_id !== '1') {
			$this->load->model('horario_model');
			$data['horarios'] = $this->horario_model->get_horarios($servicio->cargo_id);
		}

		if (!empty($servicio->fecha_alta)) {
			$f1 = $mes . '01';
			$f2 = (new DateTime($servicio->fecha_alta))->format('Ymd');
			$data['fecha_desde'] = (new DateTime(max(array($f1, $f2))))->format('d/m/Y');
		} else {
			$data['fecha_desde'] = (new DateTime($mes . '01'))->format('d/m/Y');
		}

		$data['fecha_hasta'] = (new DateTime($mes . '01 +1 month -1 day'))->format('d/m/Y');
		$data['novedades'] = $novedades;
		$data['fechas_inhabilitadas'] = $fechas_inhabilitadas;
		$this->servicio_novedad_model->fields['novedad_tipo']['label'] = 'Motivo Baja';
		$data['fields'] = $this->build_fields($this->servicio_novedad_model->fields);

		$data['title'] = "Dar de baja a <b>$servicio->apellido, $servicio->nombre</b> ($servicio->cuil <b>" . substr($servicio->liquidacion, -2) . "</b>)";
		$this->load->view('servicio_novedad/servicio_novedad_modal_baja', $data);
	}

	public function modal_editar($mes = NULL, $novedad_id = NULL, $escuela_id = NULL, $es_funcion = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$mes == NULL || $novedad_id == NULL || $escuela_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($novedad_id) || !ctype_digit($escuela_id)) {
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

		$novedad = $this->servicio_novedad_model->get_one($novedad_id);
		if (empty($novedad)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}

		if ($novedad->novedad === 'A') {
			$this->modal_error('No se puede editar una novedad de alta', 'Acción no autorizada');
			return;
		}
		if ($novedad->novedad === 'B') {
			$this->modal_error('No se puede editar una novedad de baja', 'Acción no autorizada');
			return;
		}

		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($novedad->servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el servicio', 'Registro no encontrado');
			return;
		}

		if ($es_funcion === '0') {
			if ($servicio->escuela_id !== $escuela_id) {
				$this->modal_error('El servicio no pertenece a la escuela.', 'Acción no autorizada');
				return;
			}
		} else {
			$this->load->model('servicio_funcion_model');
			if (!empty($novedad->servicio_funcion_id)) {
				$servicio_funcion = $this->servicio_funcion_model->get_one($novedad->servicio_funcion_id);
				if ($servicio_funcion->escuela_id !== $escuela_id) {
					$this->modal_error('El servicio no tiene función en la escuela.', 'Acción no autorizada');
					return;
				}
			} else {
				$servicio_funcion = $this->servicio_funcion_model->get(array(
					'escuela_id' => $escuela_id,
					'servicio_id' => $servicio->id,
					'where' => array('fecha_hasta IS NULL')
				));
				if (empty($servicio_funcion)) {
					$this->modal_error('El servicio no tiene función en la escuela.', 'Acción no autorizada');
					return;
				}
			}
		}

		$this->load->model('novedad_tipo_model');
		$tipos_novedades = $this->novedad_tipo_model->get_tipos_novedades('N', TRUE);
		$array_novedad_tipo = $tipos_novedades[0];
		$this->array_novedad_tipo_control = $array_novedad_tipo;
		unset($this->servicio_novedad_model->fields['estado']['input_type']);
		unset($this->servicio_novedad_model->fields['estado']['array']);
		$this->set_model_validation_rules($this->servicio_novedad_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($novedad_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$url = $this->input->post('url_redireccion');
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->servicio_novedad_model->update(array(
					'id' => $this->input->post('id'),
					'novedad_tipo_id' => $this->input->post('novedad_tipo'),
					'fecha_desde' => $this->get_date_sql('fecha_desde'),
					'fecha_hasta' => $this->get_date_sql('fecha_hasta'),
					'dias' => $this->input->post('dias'),
					'obligaciones' => $servicio->regimen_tipo_id === '1' ? '0' : $this->input->post('obligaciones'),
					'estado' => 'Cargado'
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->servicio_novedad_model->get_msg());
					if ($url == FALSE) {
						redirect("servicio_novedad/listar/$escuela_id/$mes", 'refresh');
					} else {
						redirect($url, 'refresh');
					}
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				if ($url == FALSE) {
					redirect("servicio_novedad/listar/$escuela_id/$mes", 'refresh');
				} else {
					redirect($url, 'refresh');
				}
			}
		}

		if ($es_funcion === '0' || empty($novedad->servicio_funcion_id)) {
			$novedades = $this->servicio_novedad_model->get(array(
				'join' => $this->servicio_novedad_model->default_join,
				'servicio_id' => $servicio->id,
				'ames' => $mes,
				'id !=' => $novedad->id,
				'where' => array('planilla_baja_id IS NULL', 'servicio_funcion_id IS NULL'),
			));
		} else {
			$novedades = $this->servicio_novedad_model->get(array(
				'join' => $this->servicio_novedad_model->default_join,
				'servicio_id' => $servicio->id,
				'servicio_funcion_id' => $novedad->servicio_funcion_id,
				'ames' => $mes,
				'id !=' => $novedad->id,
				'where' => array('planilla_baja_id IS NULL'),
			));
		}

		$fechas_inhabilitadas = array();
		if (!empty($novedades)) {
			foreach ($novedades as $novedad_o) {
				if ($novedad->concomitante === 'N' && $novedad_o->concomitante === 'N' && $novedad_o->novedad_tipo_id !== '1') {
					$desde = new DateTime($novedad_o->fecha_desde);
					$hasta = new DateTime($novedad_o->fecha_hasta);
					for ($fecha = $desde; $fecha <= $hasta; $fecha->modify('+1 day')) {
						$fechas_inhabilitadas[] = $fecha->format('d/m/Y');
					}
				}
			}
		}

		$this->servicio_novedad_model->fields['novedad_tipo']['array'] = $array_novedad_tipo;
		$data['servicio'] = $servicio;
		if ($servicio->regimen_tipo_id !== '1') {
			$this->load->model('horario_model');
			$data['horarios'] = $this->horario_model->get_horarios($servicio->cargo_id);
		}
		$fecha_inicio_mes = new DateTime($mes . '01');
		$fecha_fin_mes = new DateTime($mes . '01 +1 month -1 day');
		$data['fecha_desde'] = $fecha_inicio_mes->format('d/m/Y');
		$data['fecha_hasta'] = '';
		if (!empty($servicio->fecha_alta)) {
			$fecha_alta = new DateTime($servicio->fecha_alta);
			if ($fecha_alta >= $fecha_inicio_mes) {
				$data['fecha_desde'] = $fecha_alta->format('d/m/Y');
			}
		}
		if (!empty($servicio->fecha_baja)) {
			$fecha_baja = new DateTime($servicio->fecha_baja);
			if ($fecha_baja <= $fecha_fin_mes) {
				$data['fecha_hasta'] = $fecha_baja->format('d/m/Y');
			}
		}

		$data['ames'] = $mes;
		$data['novedades'] = $novedades;
		$data['fechas_inhabilitadas'] = $fechas_inhabilitadas;
		$data['novedades_concomitantes'] = $tipos_novedades[1];
		$data['fields'] = $this->build_fields($this->servicio_novedad_model->fields, $novedad);
		$data['servicio_novedad'] = $novedad;
		$data['servicio'] = $servicio;

		$data['txt_btn'] = 'Editar';
		$data['title'] = "Editar novedad de <b>$servicio->apellido, $servicio->nombre</b> ($servicio->cuil <b>" . substr($servicio->liquidacion, -2) . "</b>)";
		$data['operacion'] = 'Editar';
		if (!empty($_GET['redirect_url'])) {
			$data['url_redireccion'] = urldecode($_GET['redirect_url']);
		} else {
			$data['url_redireccion'] = FALSE;
		}
		$this->load->view('servicio_novedad/servicio_novedad_modal_abm', $data);
	}

	public function modal_editar_pendiente($mes = NULL, $novedad_id = NULL, $escuela_id = NULL, $es_funcion = '0', $tipo_redireccion = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$mes == NULL || $novedad_id == NULL || $escuela_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($novedad_id) || !ctype_digit($escuela_id)) {
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

		$novedad = $this->servicio_novedad_model->get_one($novedad_id);
		if (empty($novedad)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}

		if ($novedad->novedad === 'A') {
			$this->modal_error('No se puede editar una novedad de alta', 'Acción no autorizada');
			return;
		}

		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($novedad->servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el servicio', 'Registro no encontrado');
			return;
		}

		if ($es_funcion === '0') {
			if ($servicio->escuela_id !== $escuela_id) {
				$this->modal_error('El servicio no pertenece a la escuela.', 'Acción no autorizada');
				return;
			}
		} else {
			$this->load->model('servicio_funcion_model');
			if (!empty($novedad->servicio_funcion_id)) {
				$servicio_funcion = $this->servicio_funcion_model->get_one($novedad->servicio_funcion_id);
				if ($servicio_funcion->escuela_id !== $escuela_id) {
					$this->modal_error('El servicio no tiene función en la escuela.', 'Acción no autorizada');
					return;
				}
			} else {
				$servicio_funcion = $this->servicio_funcion_model->get(array(
					'escuela_id' => $escuela_id,
					'servicio_id' => $servicio->id,
					'where' => array('fecha_hasta IS NULL')
				));
				if (empty($servicio_funcion)) {
					$this->modal_error('El servicio no tiene función en la escuela.', 'Acción no autorizada');
					return;
				}
			}
		}

		$this->load->model('novedad_tipo_model');
		$tipos_novedades = $this->novedad_tipo_model->get_tipos_novedades('N', TRUE);
		$array_novedad_tipo = $tipos_novedades[0];
		$this->array_novedad_tipo_control = $array_novedad_tipo;
		unset($this->servicio_novedad_model->fields['estado']['input_type']);
		unset($this->servicio_novedad_model->fields['estado']['array']);
		$this->set_model_validation_rules($this->servicio_novedad_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($novedad_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->servicio_novedad_model->update(array(
					'id' => $this->input->post('id'),
					'novedad_tipo_id' => $this->input->post('novedad_tipo'),
					'fecha_desde' => $this->get_date_sql('fecha_desde'),
					'fecha_hasta' => $this->get_date_sql('fecha_hasta'),
					'dias' => $this->input->post('dias'),
					'obligaciones' => $servicio->regimen_tipo_id === '1' ? '0' : $this->input->post('obligaciones'),
					'estado' => 'Cargado'
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->servicio_novedad_model->get_msg());
					if ($tipo_redireccion === '1') {
						redirect("alertas/novedades_pendientes/$escuela_id", 'refresh');
					} else {
						redirect("servicio_novedad/listar/$escuela_id/$mes/1", 'refresh');
					}
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				if ($tipo_redireccion === '1') {
					redirect("alertas/novedades_pendientes/$escuela_id", 'refresh');
				} else {
					redirect("servicio_novedad/listar/$escuela_id/$mes/1", 'refresh');
				}
			}
		}

		if ($es_funcion === '0' || empty($novedad->servicio_funcion_id)) {
			$novedades = $this->servicio_novedad_model->get(array(
				'join' => $this->servicio_novedad_model->default_join,
				'servicio_id' => $servicio->id,
				'ames' => $mes,
				'id !=' => $novedad->id,
				'where' => array('planilla_baja_id IS NULL'),
			));
		} else {
			$novedades = $this->servicio_novedad_model->get(array(
				'join' => $this->servicio_novedad_model->default_join,
				'servicio_id' => $servicio->id,
				'servicio_funcion_id' => $novedad->servicio_funcion_id,
				'ames' => $mes,
				'id !=' => $novedad->id,
				'where' => array('planilla_baja_id IS NULL'),
			));
		}

		$fechas_inhabilitadas = array();
		if (!empty($novedades)) {
			foreach ($novedades as $novedad_o) {
				if ($novedad->concomitante === 'N' && $novedad_o->concomitante === 'N' && $novedad_o->novedad_tipo_id !== '1') {
					$desde = new DateTime($novedad_o->fecha_desde);
					$hasta = new DateTime($novedad_o->fecha_hasta);
					for ($fecha = $desde; $fecha <= $hasta; $fecha->modify('+1 day')) {
						$fechas_inhabilitadas[] = $fecha->format('d/m/Y');
					}
				}
			}
		}

		$this->servicio_novedad_model->fields['novedad_tipo']['array'] = $array_novedad_tipo;
		$data['servicio'] = $servicio;
		if ($servicio->regimen_tipo_id !== '1') {
			$this->load->model('horario_model');
			$data['horarios'] = $this->horario_model->get_horarios($servicio->cargo_id);
		}
		$data['fecha_desde'] = (new DateTime($novedad->fecha_desde))->format('d/m/Y');
		$fecha_fin_mes = new DateTime($mes . '01 +1 month -1 day');
		$data['fecha_hasta'] = '';
		if (!empty($servicio->fecha_baja)) {
			$fecha_baja = new DateTime($servicio->fecha_baja);
			if ($fecha_baja <= $fecha_fin_mes) {
				$data['fecha_hasta'] = $fecha_baja->format('d/m/Y');
			}
		}

		$data['ames'] = $mes;
		$data['novedades'] = $novedades;
		$data['fechas_inhabilitadas'] = $fechas_inhabilitadas;
		$data['novedades_concomitantes'] = $tipos_novedades[1];
		$data['fields'] = $this->build_fields($this->servicio_novedad_model->fields, $novedad);
		$data['servicio_novedad'] = $novedad;
		$data['servicio'] = $servicio;

		$data['txt_btn'] = 'Editar';
		$data['title'] = "Confirmar novedad pendiente de <b>$servicio->apellido, $servicio->nombre</b> ($servicio->cuil <b>" . substr($servicio->liquidacion, -2) . "</b>)";
		$data['txt_btn'] = 'Confirmar';
		$data['operacion'] = 'Confirmar p';
		if (!empty($_GET['redirect_url'])) {
			$data['url_redireccion'] = urldecode($_GET['redirect_url']);
		} else {
			$data['url_redireccion'] = FALSE;
		}
		$this->load->view('servicio_novedad/servicio_novedad_modal_abm', $data);
	}

	public function modal_eliminar($mes = NULL, $novedad_id = NULL, $escuela_id = NULL, $es_funcion = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$mes == NULL || $novedad_id == NULL || $escuela_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($novedad_id) || !ctype_digit($escuela_id)) {
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
		$novedad = $this->servicio_novedad_model->get_one($novedad_id);
		if (empty($novedad)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if ($novedad->estado === 'Procesada') {
			$this->modal_error('No puede editar un registro procesado', 'Acción no autorizada');
			return;
		}

		$this->load->model('servicio_model');
		$this->load->model('servicio_funcion_model');
		$servicio = $this->servicio_model->get_one($novedad->servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el servicio', 'Registro no encontrado');
			return;
		}

		if ($es_funcion === '0') {
			if ($servicio->escuela_id !== $escuela_id) {
				$this->modal_error('El servicio no pertenece a la escuela.', 'Acción no autorizada');
				return;
			}
		} else {
			if (!empty($novedad->servicio_funcion_id)) {
				$servicio_funcion = $this->servicio_funcion_model->get_one($novedad->servicio_funcion_id);
				if ($servicio_funcion->escuela_id !== $escuela_id) {
					$this->modal_error('El servicio no tiene función en la escuela.', 'Acción no autorizada');
					return;
				}
			} else {
				$servicio_funcion = $this->servicio_funcion_model->get(array(
					'escuela_id' => $escuela_id,
					'servicio_id' => $servicio->id,
					'where' => array('fecha_hasta IS NULL')
				));
				if (empty($servicio_funcion)) {
					$this->modal_error('El servicio no tiene función en la escuela.', 'Acción no autorizada');
					return;
				}
			}
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($novedad_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;

			$this->load->model('planilla_asisnov_model');
			$planilla_id = $this->planilla_asisnov_model->get_planilla_abierta($escuela_id, $mes, $servicio->planilla_tipo_id);
			$trans_ok &= $planilla_id > 0;

			if ($trans_ok) {
				if ($novedad->planilla_alta_id === $planilla_id) {
					$trans_ok &= $this->servicio_novedad_model->delete(array('id' => $this->input->post('id')), FALSE);
				} else {
					$trans_ok &= $this->servicio_novedad_model->update(array(
						'id' => $novedad->id,
						'planilla_baja_id' => $planilla_id
						), FALSE);
				}
				if ($novedad->novedad === 'B') {
					$trans_ok &= $this->servicio_model->update(array(
						'id' => $servicio->id,
						'fecha_baja' => 'NULL',
						'motivo_baja' => 'NULL'
						), FALSE);
				}
			}
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->servicio_novedad_model->get_msg());
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al intentar eliminar.';
				if ($this->planilla_asisnov_model->get_error())
					$errors .= '<br>' . $this->planilla_asisnov_model->get_error();
				if ($this->servicio_novedad_model->get_error())
					$errors .= '<br>' . $this->servicio_novedad_model->get_error();
			}
			if ($novedad->novedad === 'B') {
				redirect("servicio/listar/$escuela_id/$mes", 'refresh');
			} else {
				redirect("servicio_novedad/listar/$escuela_id/$mes", 'refresh');
			}
		}
		$data['servicio'] = $servicio;
		if ($servicio->regimen_tipo_id !== '1') {
			$this->load->model('horario_model');
			$data['horarios'] = $this->horario_model->get_horarios($servicio->cargo_id);
		}

		if ($es_funcion === '0' || empty($novedad->servicio_funcion_id)) {
			$novedades = $this->servicio_novedad_model->get(array(
				'join' => $this->servicio_novedad_model->default_join,
				'servicio_id' => $servicio->id,
				'ames' => $mes,
				'id !=' => $novedad->id,
				'where' => array('planilla_baja_id IS NULL'),
			));
		} else {
			$novedades = $this->servicio_novedad_model->get(array(
				'join' => $this->servicio_novedad_model->default_join,
				'servicio_id' => $servicio->id,
				'servicio_funcion_id' => $novedad->servicio_funcion_id,
				'ames' => $mes,
				'id !=' => $novedad->id,
				'where' => array('planilla_baja_id IS NULL'),
			));
		}

		$data['fields'] = $this->build_fields($this->servicio_novedad_model->fields, $novedad, TRUE);

		$data['servicio_novedad'] = $novedad;
		$data['novedades'] = $novedades;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar novedad de servicio';
		if ($novedad->novedad === 'A') {
			$this->load->model('planilla_asisnov_model');
			$planilla = $this->planilla_asisnov_model->get_planilla($escuela->id, $novedad->ames, $servicio->planilla_tipo_id);
			$data['permitido'] = $planilla->id === $novedad->planilla_alta_id && empty($planilla->fecha_cierre);
			$this->load->view('servicio_novedad/servicio_novedad_modal_eliminar_servicio', $data);
		} else {
			$this->load->view('servicio_novedad/servicio_novedad_modal_ver', $data);
		}
	}

	public function modal_ver($mes = NULL, $novedad_id = NULL, $escuela_id = NULL, $es_funcion = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$mes == NULL || $novedad_id == NULL || $escuela_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($novedad_id) || !ctype_digit($escuela_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$novedad = $this->servicio_novedad_model->get_one($novedad_id);
		if (empty($novedad)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
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

		$this->load->model('servicio_model');
		$this->load->model('servicio_funcion_model');
		$servicio = $this->servicio_model->get_one($novedad->servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el servicio', 'Registro no encontrado');
			return;
		}

		if ($es_funcion === '0') {
			if ($servicio->escuela_id !== $escuela_id) {
				$this->modal_error('El servicio no pertenece a la escuela.', 'Acción no autorizada');
				return;
			}
		} else {
			if (!empty($novedad->servicio_funcion_id)) {
				$servicio_funcion = $this->servicio_funcion_model->get_one($novedad->servicio_funcion_id);
				if ($servicio_funcion->escuela_id !== $escuela_id) {
					$this->modal_error('El servicio no tiene función en la escuela.', 'Acción no autorizada');
					return;
				}
			} else {
				$servicio_funcion = $this->servicio_funcion_model->get(array(
					'escuela_id' => $escuela_id,
					'servicio_id' => $servicio->id,
					'where' => array('fecha_hasta IS NULL')
				));
				if (empty($servicio_funcion)) {
					$this->modal_error('El servicio no tiene función en la escuela.', 'Acción no autorizada');
					return;
				}
			}
		}

		if ($servicio->regimen_tipo_id !== '1') {
			$this->load->model('horario_model');
			$data['horarios'] = $this->horario_model->get_horarios($servicio->cargo_id);
		}

		if ($es_funcion === '0' || empty($novedad->servicio_funcion_id)) {
			$novedades = $this->servicio_novedad_model->get(array(
				'join' => $this->servicio_novedad_model->default_join,
				'servicio_id' => $servicio->id,
				'ames' => $mes,
				'id !=' => $novedad->id,
				'where' => array('planilla_baja_id IS NULL'),
			));
		} else {
			$novedades = $this->servicio_novedad_model->get(array(
				'join' => $this->servicio_novedad_model->default_join,
				'servicio_id' => $servicio->id,
				'servicio_funcion_id' => $novedad->servicio_funcion_id,
				'ames' => $mes,
				'id !=' => $novedad->id,
				'where' => array('planilla_baja_id IS NULL'),
			));
		}

		$data['fields'] = $this->build_fields($this->servicio_novedad_model->fields, $novedad, TRUE);

		$data['servicio'] = $servicio;
		$data['novedades'] = $novedades;
		$data['servicio_novedad'] = $novedad;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver novedad de servicio';
		$this->load->view('servicio_novedad/servicio_novedad_modal_ver', $data);
	}

	public function excel($escuela_id = NULL, $mes = NULL, $pendientes = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || ($mes != NULL && !ctype_digit($mes))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($mes) || empty(DateTime::createFromFormat('Ym', $mes))) {
			$this->load->model('planilla_asisnov_model');
			$mes = $this->planilla_asisnov_model->get_mes_actual();
			redirect("servicio_novedad/excel/$escuela_id/$mes", 'refresh');
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
			'G' => array('Régimen', 30),
			'H' => array('Hs Cátedra', 15),
			'I' => array('Materia', 35),
			'J' => array('División', 20),
			'K' => array('Articulo', 15),
			'L' => array('Tipo de novedad', 30),
			'M' => array('Desde', 15),
			'N' => array('Hasta', 15),
			'O' => array('Baja', 11),
			'P' => array('Días', 11),
			'Q' => array('Obligaciones', 11),
			'R' => array('Estado', 15),
			'S' => array('Función (Fn)', 25),
			'T' => array('Fn Destino', 34),
			'U' => array('Fn Norma', 11),
			'V' => array('Fn Tarea', 11),
			'W' => array('Fn Carga Horaria', 11),
			'X' => array('Fn Desde', 11),
			'Y' => array('Observaciones', 50)
		);

		$novedades = $this->db->select('escuela.numero,escuela.anexo,CONCAT(COALESCE(persona.cuil, persona.documento), \' \', persona.apellido, \' \', persona.nombre) as persona,servicio.fecha_alta, servicio.liquidacion, situacion_revista.descripcion as situacion_revista, CONCAT(regimen.codigo, \' \', regimen.descripcion) as regimen, cargo.carga_horaria, materia.descripcion as materia, CONCAT(curso.descripcion, \' \', division.division)as division,CASE novedad_tipo.id WHEN 1 THEN \'Alta\' ELSE CONCAT(novedad_tipo.articulo, \'-\', novedad_tipo.inciso) END as articulo,novedad_tipo.descripcion_corta as novedad_tipo, servicio_novedad.fecha_desde, CASE novedad_tipo.id WHEN 1 THEN \'\' ELSE servicio_novedad.fecha_hasta END as fecha_hasta, servicio.fecha_baja,CASE novedad_tipo.id WHEN 1 THEN \'\' ELSE servicio_novedad.dias END as dias,CASE novedad_tipo.id WHEN 1 THEN \'\' ELSE servicio_novedad.obligaciones END as obligaciones,servicio_novedad.estado,servicio_funcion.detalle as funcion_detalle,servicio_funcion.destino as funcion_destino,servicio_funcion.norma as funcion_norma,servicio_funcion.tarea as funcion_tarea, cargo.carga_horaria,servicio_funcion.fecha_desde as funcion_desde,servicio.observaciones ')
				->from('servicio_novedad')
				->join('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id', 'left')
				->join('servicio', 'servicio.id = servicio_novedad.servicio_id', '')
				->join('cargo', 'cargo.id = servicio.cargo_id', '')
				->join('servicio_funcion', 'servicio_funcion.servicio_id = servicio.id AND servicio_funcion.fecha_hasta IS NULL', 'left')
				->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
				->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
				->join('persona', 'persona.id = servicio.persona_id', 'left')
				->join('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id', 'left')
				->join('escuela', 'cargo.escuela_id = escuela.id', 'left')
				->join('division', 'cargo.division_id = division.id', 'left')
				->join('curso', 'division.curso_id = curso.id', 'left')
				->join('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1', '')
				->where('servicio_novedad.ames', $mes)
				->where('servicio_novedad.planilla_baja_id IS NULL')
				->where('escuela.id', $escuela_id)
				->where('servicio_novedad.servicio_funcion_id IS NULL')
				->group_by('servicio_novedad.id')
				->get()->result_array();
		$novedades_f = $this->db->select('escuela.numero,escuela.anexo,CONCAT(COALESCE(persona.cuil, persona.documento), \' \', persona.apellido, \' \', persona.nombre) as persona,servicio.fecha_alta, servicio.liquidacion, situacion_revista.descripcion as situacion_revista, CONCAT(regimen.codigo, \' \', regimen.descripcion) as regimen, cargo.carga_horaria, materia.descripcion as materia, CONCAT(curso.descripcion, \' \', division.division)as division,CASE novedad_tipo.id WHEN 1 THEN \'Alta\' ELSE CONCAT(novedad_tipo.articulo, \'-\', novedad_tipo.inciso) END as articulo,novedad_tipo.descripcion_corta as novedad_tipo, servicio_novedad.fecha_desde, CASE novedad_tipo.id WHEN 1 THEN \'\' ELSE servicio_novedad.fecha_hasta END as fecha_hasta, servicio.fecha_baja,CASE novedad_tipo.id WHEN 1 THEN \'\' ELSE servicio_novedad.dias END as dias,CASE novedad_tipo.id WHEN 1 THEN \'\' ELSE servicio_novedad.obligaciones END as obligaciones,servicio_novedad.estado,servicio_funcion.detalle as funcion_detalle,servicio_funcion.destino as funcion_destino,servicio_funcion.norma as funcion_norma,servicio_funcion.tarea as funcion_tarea, cargo.carga_horaria,servicio_funcion.fecha_desde as funcion_desde,servicio.observaciones ')
				->from('servicio_novedad')
				->join('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id')
				->join('servicio_funcion', 'servicio_funcion.id = servicio_novedad.servicio_funcion_id')
				->join('servicio', 'servicio.id = servicio_novedad.servicio_id')
				->join('cargo', 'cargo.id = servicio.cargo_id')
				->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
				->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
				->join('persona', 'persona.id = servicio.persona_id', 'left')
				->join('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id', 'left')
				->join('escuela', 'cargo.escuela_id = escuela.id', 'left')
				->join('division', 'cargo.division_id = division.id', 'left')
				->join('curso', 'division.curso_id = curso.id', 'left')
				->join('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1', '')
				->where('servicio_novedad.ames', $mes)
				->where('servicio_novedad.planilla_baja_id IS NULL')
				->where('servicio_funcion.escuela_id', $escuela_id)
				->group_by('servicio_novedad.id')
				->get()->result_array();
		$novedades_t = array_merge($novedades, $novedades_f);

		if (!empty($novedades_t)) {
			$this->exportar_excel(array('title' => "Novedades $escuela->numero $mes"), $campos, $novedades_t);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect("servicio_novedad/listar/$escuela_id/$mes/$pendientes", 'refresh');
		}
	}
}
/* End of file Servicio_novedad.php */
/* Location: ./application/controllers/Servicio_novedad.php */