<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Personal_novedad extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('areas/area_model');
		$this->load->model('servicio_novedad_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_AREA, ROL_CONSULTA, ROL_JEFE_LIQUIDACION, ROL_LIQUIDACION);
		if (in_array($this->rol->codigo, array(ROL_CONSULTA))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'menu/servicio_novedad';
	}

	public function listar($area_id = NULL, $mes = NULL, $pendientes = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $area_id == NULL || !ctype_digit($area_id) || ($mes != NULL && !ctype_digit($mes))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($mes) || empty(DateTime::createFromFormat('Ym', $mes))) {
			$this->load->model('planilla_asisnov_model');
			$mes = $this->planilla_asisnov_model->get_mes_actual();
			redirect("areas/personal_novedad/listar/$area_id/$mes", 'refresh');
		}
		$area = $this->area_model->get_one($area_id);
		if (empty($area)) {
			show_error('No se encontró el registro del área', 500, 'Registro no encontrado');
		}
		if ($this->rol->codigo === ROL_AREA && strpos($area->codigo, substr($this->rol->entidad, 0, strpos($this->rol->entidad, '-'))) === FALSE) {
			show_error('No tiene permisos para acceder al área', 500, 'Acción no autorizada');
		}
		if ($pendientes === '0') {
			$tableData = array(
				'columns' => array(
					array('label' => 'Persona', 'data' => 'persona', 'width' => 19, 'class' => 'text-sm'),
					array('label' => 'Liquidación', 'data' => 'liquidacion', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'S.R.', 'data' => 'situacion_revista', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'Régimen/Área', 'data' => 'regimen_area', 'width' => 27, 'class' => 'text-sm'),
					array('label' => 'Hs.', 'data' => 'carga_horaria', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Art.', 'data' => 'articulo', 'width' => 3),
					array('label' => 'Novedad', 'data' => 'novedad_tipo', 'width' => 10, 'class' => 'text-sm'),
					array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => 'Dias', 'data' => 'dias', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Oblig', 'data' => 'obligaciones', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all')
				),
				'table_id' => 'personal_novedad_table',
				'source_url' => "areas/personal_novedad/listar_data/$area_id/$mes/$pendientes/{$this->rol->codigo}/{$this->rol->entidad_id}",
				'order' => array(array(1, 'asc'), array(5, 'asc'), array(6, 'asc'), array(7, 'asc')),
				'reuse_var' => TRUE,
				'initComplete' => "complete_personal_novedad_table",
				'footer' => TRUE,
				'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
			);

			$tableData_o = array(
				'columns' => array(
					array('label' => 'Persona', 'data' => 'persona', 'width' => 19, 'class' => 'text-sm'),
					array('label' => 'Liquidación', 'data' => 'liquidacion', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'S.R.', 'data' => 'situacion_revista', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'Régimen/Área', 'data' => 'regimen_area', 'width' => 27, 'class' => 'text-sm'),
					array('label' => 'Hs.', 'data' => 'carga_horaria', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Art.', 'data' => 'articulo', 'width' => 3),
					array('label' => 'Novedad', 'data' => 'novedad_tipo', 'width' => 10, 'class' => 'text-sm'),
					array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => 'Dias', 'data' => 'dias', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Oblig', 'data' => 'obligaciones', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all')
				),
				'table_id' => 'personal_novedad_f_table',
				'source_url' => "areas/personal_novedad/listar_data_funcion/$area_id/$mes/$pendientes/{$this->rol->codigo}/{$this->rol->entidad_id}",
				'order' => array(array(1, 'asc'), array(5, 'asc'), array(6, 'asc'), array(7, 'asc')),
				'reuse_var' => TRUE,
				'initComplete' => "complete_personal_novedad_f_table",
				'footer' => TRUE,
				'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
			);
		} else {
			$tableData = array(
				'columns' => array(
					array('label' => 'Persona', 'data' => 'persona', 'width' => 22, 'class' => 'text-sm'),
					array('label' => 'Liquidación', 'data' => 'liquidacion', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'S.R.', 'data' => 'situacion_revista', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'Régimen/Área', 'data' => 'regimen_area', 'width' => 30, 'class' => 'text-sm'),
					array('label' => 'Hs.', 'data' => 'carga_horaria', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Art.', 'data' => 'articulo', 'width' => 3),
					array('label' => 'Novedad', 'data' => 'novedad_tipo', 'width' => 10, 'class' => 'text-sm'),
					array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all')
				),
				'table_id' => 'personal_novedad_table',
				'source_url' => "areas/personal_novedad/listar_data/$area_id/$mes/$pendientes/{$this->rol->codigo}/{$this->rol->entidad_id}",
				'order' => array(array(1, 'asc'), array(5, 'asc'), array(6, 'asc'), array(7, 'asc')),
				'reuse_var' => TRUE,
				'initComplete' => "complete_personal_novedad_table",
				'footer' => TRUE,
				'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
			);
			$tableData_o = array(
				'columns' => array(
					array('label' => 'Persona', 'data' => 'persona', 'width' => 22, 'class' => 'text-sm'),
					array('label' => 'Liquidación', 'data' => 'liquidacion', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'S.R.', 'data' => 'situacion_revista', 'width' => 7, 'class' => 'text-sm'),
					array('label' => 'Régimen/Área', 'data' => 'regimen_area', 'width' => 31, 'class' => 'text-sm'),
					array('label' => 'Hs.', 'data' => 'carga_horaria', 'width' => 3, 'class' => 'dt-body-center'),
					array('label' => 'Art.', 'data' => 'articulo', 'width' => 3),
					array('label' => 'Novedad', 'data' => 'novedad_tipo', 'width' => 10, 'class' => 'text-sm'),
					array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
					array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all')
				),
				'table_id' => 'personal_novedad_f_table',
				'source_url' => "areas/personal_novedad/listar_data_funcion/$area_id/$mes/$pendientes/{$this->rol->codigo}/{$this->rol->entidad_id}",
				'order' => array(array(0, 'asc'), array(3, 'asc'), array(4, 'asc')),
				'reuse_var' => TRUE,
				'initComplete' => "complete_personal_novedad_f_table",
				'footer' => TRUE,
				'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
			);
		}

		$data['area'] = $area;
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
		$data['title'] = TITLE . ' - Novedades de personal';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		if ($pendientes === '1') {
			$this->load_template('areas/personal_novedad/personal_novedad_pendientes', $data);
		} else {
			$this->load_template('areas/personal_novedad/personal_novedad_listar', $data);
		}
	}

	public function listar_data($area_id = NULL, $mes = NULL, $pendientes = '0', $rol_codigo = NULL, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $area_id == NULL || !ctype_digit($area_id) || $mes == NULL || !ctype_digit($mes)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$area = $this->area_model->get_one($area_id);
		if (empty($area)) {
			show_error('No se encontró el registro del área', 500, 'Registro no encontrado');
		}
		if ($this->rol->codigo === ROL_AREA && strpos($area->codigo, substr($this->rol->entidad, 0, strpos($this->rol->entidad, '-'))) === FALSE) {
			show_error('No tiene permisos para acceder al área', 500, 'Acción no autorizada');
		}
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select('servicio_novedad.id, servicio_novedad.fecha_desde, servicio_novedad.fecha_hasta, servicio_novedad.dias, servicio_novedad.obligaciones, CONCAT(novedad_tipo.articulo, \'-\', novedad_tipo.inciso) as articulo, novedad_tipo.descripcion_corta as novedad_tipo, novedad_tipo.novedad, servicio.liquidacion, cargo.carga_horaria, CONCAT(persona.cuil, \'<br>\', persona.apellido, \', \', persona.nombre) as persona, situacion_revista.descripcion as situacion_revista, CONCAT(regimen.codigo, \'-\', regimen.descripcion, \'<br>\', area.codigo, \'-\', area.descripcion) as regimen_area, area.id as area_id, servicio_novedad.origen_id, planilla_asisnov.fecha_cierre')
			->unset_column('id')
			->from('servicio_novedad')
			->join('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id', 'left')
			->join('servicio', 'servicio.id = servicio_novedad.servicio_id', '')
			->join('cargo', 'cargo.id = servicio.cargo_id', '')
			->join('persona', 'persona.id = servicio.persona_id', 'left')
			->join('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id', 'left')
			->join('area', 'cargo.area_id = area.id', 'left')
			->join('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1', '')
			->join('planilla_asisnov', 'servicio_novedad.planilla_alta_id = planilla_asisnov.id', 'left')
			->where('servicio_novedad.ames', $mes)
			->where('servicio_novedad.planilla_baja_id IS NULL')
			->where("novedad_tipo.novedad = 'N'")
			->where('area.id', $area_id);

		if ($pendientes === '1') {
			$this->datatables->where('servicio_novedad.estado', 'Pendiente');
			if ($this->edicion) {
				$this->datatables->add_column('menu', '$1', 'dt_column_personal_novedad_menu(\'' . $mes . '\', id, area_id, novedad, 0, 1, fecha_cierre, origen_id)');
			} else {
				$this->datatables->add_column('menu', '<a class="btn btn-xs btn-default" href="areas/personal_novedad/modal_ver/' . $mes . '/$1/$2/0" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>', 'id, area_id');
			}
		} else {
			$this->datatables->where('servicio_novedad.estado !=', 'Pendiente');
			if ($this->edicion) {
				$this->datatables->add_column('menu', '$1', 'dt_column_personal_novedad_menu(\'' . $mes . '\', id, area_id, novedad, 0, 0, fecha_cierre, origen_id)');
			} else {
				$this->datatables->add_column('menu', '<a class="btn btn-xs btn-default" href="areas/personal_novedad/modal_ver/' . $mes . '/$1/$2/0" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>', 'id, area_id');
			}
		}
		echo $this->datatables->generate();
	}

	public function listar_data_funcion($area_id = NULL, $mes = NULL, $pendientes = '0', $rol_codigo = NULL, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $area_id == NULL || !ctype_digit($area_id) || $mes == NULL || !ctype_digit($mes)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$area = $this->area_model->get_one($area_id);
		if (empty($area)) {
			show_error('No se encontró el registro del área', 500, 'Registro no encontrado');
		}
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select('servicio_novedad.id, servicio_novedad.fecha_desde, servicio_novedad.fecha_hasta, servicio_novedad.dias, servicio_novedad.obligaciones, '
				. 'CONCAT(novedad_tipo.articulo, \'-\', novedad_tipo.inciso) as articulo, novedad_tipo.descripcion_corta as novedad_tipo, novedad_tipo.novedad, '
				. 'servicio.liquidacion, cargo.carga_horaria, CONCAT(persona.cuil, \'<br>\', persona.apellido, \', \', persona.nombre) as persona, '
				. 'situacion_revista.descripcion as situacion_revista, CONCAT(regimen.codigo, \'-\', regimen.descripcion, \'<br>\', area_f.codigo, \'-\', area_f.descripcion) as regimen_area, '
				. 'servicio_funcion.area_id, COALESCE(CONCAT(area.codigo, \' \', area.descripcion), CONCAT(escuela.numero, \' \', escuela.nombre, CASE WHEN anexo = 0 THEN \'\' ELSE CONCAT(\'/\', anexo) END)) as origen, servicio_novedad.origen_id, planilla_asisnov.fecha_cierre')
			->unset_column('id')
			->from('servicio_novedad')
			->join('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id')
			->join('servicio', 'servicio.id = servicio_novedad.servicio_id')
			->join('servicio_funcion', 'servicio.id = servicio_funcion.servicio_id AND servicio_funcion.fecha_hasta IS NULL')
			->join('area area_f', 'servicio_funcion.area_id = area_f.id', 'left')
			->join('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id')
			->join('persona', 'persona.id = servicio.persona_id')
			->join('cargo', 'cargo.id = servicio.cargo_id')
			->join('area', 'cargo.area_id = area.id', 'left')
			->join('escuela', 'escuela.id = cargo.escuela_id', 'left')
			->join('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1')
			->join('planilla_asisnov', 'servicio_novedad.planilla_alta_id = planilla_asisnov.id', 'left')
			->where('servicio_novedad.ames', $mes)
			->where('servicio_novedad.planilla_baja_id IS NULL')
			->where("novedad_tipo.novedad = 'N'")
			->where('area_f.id', $area_id)
			->add_column('', '', 'id');

		if ($pendientes === '1') {
			$this->datatables->where('servicio_novedad.estado', 'Pendiente');
			if ($this->edicion) {
				$this->datatables->add_column('menu', '$1', 'dt_column_personal_novedad_menu(\'' . $mes . '\', id, area_id, novedad, 1, 1)');
			} else {
				$this->datatables->add_column('menu', '<a class="btn btn-xs btn-default" href="areas/personal_novedad/modal_ver/' . $mes . '/$1/$2/0" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>', 'id, area_id');
			}
		} else {
			$this->datatables->where('servicio_novedad.estado !=', 'Pendiente');
			if ($this->edicion) {
				$this->datatables->add_column('menu', '$1', 'dt_column_personal_novedad_menu(\'' . $mes . '\', id, area_id, novedad, 1, 0)');
			} else {
				$this->datatables->add_column('menu', '<a class="btn btn-xs btn-default" href="areas/personal_novedad/modal_ver/' . $mes . '/$1/$2/0" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>', 'id, area_id');
			}
		}
		echo $this->datatables->generate();
	}

	public function cambiar_mes($area_id, $mes, $pendientes = '0') {
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
			redirect("areas/personal_novedad/listar/$area_id/$mes/$pendientes", 'refresh');
		}
		redirect("areas/personal_novedad/listar/$area_id/$mes", 'refresh');
	}

	public function modal_agregar($mes = NULL, $servicio_id = NULL, $area_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$mes == NULL || $servicio_id == NULL || $area_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($servicio_id) || !ctype_digit($area_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$area = $this->area_model->get_one($area_id);
		if (empty($area)) {
			$this->modal_error('No se encontró el área', 'Registro no encontrado');
			return;
		}
		if ($this->rol->codigo === ROL_AREA && strpos($area->codigo, substr($this->rol->entidad, 0, strpos($this->rol->entidad, '-'))) === FALSE) {
			$this->modal_error('No tiene permisos para acceder al área', 'Acción no autorizada');
			return;
		}
		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($servicio_id);
		if (empty($servicio) || $area_id !== $servicio->area_id) {
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
				$planilla_id = $this->planilla_asisnov_model->get_planilla_area_abierta($area_id, $mes);
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
				redirect("areas/personal/listar/$area_id/$mes", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("areas/personal/listar/$area_id/$mes", 'refresh');
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
		$data['fecha_alta'] = '';
		$data['fecha_hasta'] = '';
		$fecha_fin_mes = new DateTime($mes . '01 +1 month -1 day');
		if (!empty($servicio->fecha_alta)) {
			$f1 = $mes . '01';
			$f2 = (new DateTime($servicio->fecha_alta))->format('Ymd');
			if ($f2 > $f1) {
				$data['fecha_alta'] = (new DateTime($f2 . ' -1 day'))->format('d/m/Y');
			}
			$data['fecha_desde'] = (new DateTime(max(array($f1, $f2))))->format('d/m/Y');
		} else {
			$data['fecha_desde'] = (new DateTime($mes . '01'))->format('d/m/Y');
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
		$data['url_redireccion'] = FALSE;
		$data['title'] = "Agregar novedad a <b>$servicio->apellido, $servicio->nombre</b> ($servicio->cuil <b>" . substr($servicio->liquidacion, -2) . "</b>)";
		$data['operacion'] = 'Agregar';
		$this->load->view('servicio_novedad/servicio_novedad_modal_abm', $data);
	}

	public function modal_agregar_funcion($mes = NULL, $id = NULL, $area_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$mes == NULL || $id == NULL || $area_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($id) || !ctype_digit($area_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('servicio_funcion_model');
		$servicio_funcion = $this->servicio_funcion_model->get_one($id);
		if (empty($servicio_funcion) || $area_id !== $servicio_funcion->area_id) {
			$this->modal_error('No se encontró el registro a agregar', 'Registro no encontrado');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$area = $this->area_model->get_one($area_id);
		if (empty($area)) {
			$this->modal_error('No se encontró el registro del área', 'Registro no encontrado');
			return;
		}
		if ($this->rol->codigo === ROL_AREA && strpos($area->codigo, substr($this->rol->entidad, 0, strpos($this->rol->entidad, '-'))) === FALSE) {
			$this->modal_error('No tiene permisos para acceder al área', 'Acción no autorizada');
			return;
		}

		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($servicio_funcion->servicio_id);
		if (empty($servicio) || $area_id !== $servicio_funcion->area_id) {
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
				$planilla_id = $this->planilla_asisnov_model->get_planilla_area_abierta($area_id, $mes);
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
				redirect("areas/personal/listar/$area_id/$mes", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("areas/personal/listar/$area_id/$mes", 'refresh');
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
		$data['url_redireccion'] = FALSE;
		$this->load->view('servicio_novedad/servicio_novedad_modal_abm', $data);
	}

	public function modal_baja($mes = NULL, $servicio_id = NULL, $area_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$mes == NULL || $servicio_id == NULL || $area_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($servicio_id) || !ctype_digit($area_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$area = $this->area_model->get_one($area_id);
		if (empty($area)) {
			$this->modal_error('No se encontró el área', 'Registro no encontrado');
			return;
		}
		if ($this->rol->codigo === ROL_AREA && strpos($area->codigo, substr($this->rol->entidad, 0, strpos($this->rol->entidad, '-'))) === FALSE) {
			$this->modal_error('No tiene permisos para acceder al área', 'Acción no autorizada');
			return;
		}
		$this->load->model('novedad_tipo_model');
		$array_novedad_tipo = $this->novedad_tipo_model->get_tipos_novedades('B');
		$this->array_novedad_tipo_control = $array_novedad_tipo;
		unset($this->servicio_novedad_model->fields['estado']);
		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($servicio_id);
		if (empty($servicio) || $area_id !== $servicio->area_id) {
			$this->modal_error('No se encontró el servicio', 'Registro no encontrado');
			return;
		}

		$this->set_model_validation_rules($this->servicio_novedad_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$this->load->model('planilla_asisnov_model');
				$planilla_id = $this->planilla_asisnov_model->get_planilla_area_abierta($area_id, $mes);
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
				redirect("areas/personal/listar/$area_id/$mes", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("areas/personal/listar/$area_id/$mes", 'refresh');
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

	public function modal_editar($mes = NULL, $novedad_id = NULL, $area_id = NULL, $es_funcion = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$mes == NULL || $novedad_id == NULL || $area_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($novedad_id) || !ctype_digit($area_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$area = $this->area_model->get_one($area_id);
		if (empty($area)) {
			$this->modal_error('No se encontró el registro a área', 'Registro no encontrado');
			return;
		}
		if ($this->rol->codigo === ROL_AREA && strpos($area->codigo, substr($this->rol->entidad, 0, strpos($this->rol->entidad, '-'))) === FALSE) {
			$this->modal_error('No tiene permisos para acceder al área', 'Acción no autorizada');
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
			if ($servicio->area_id !== $area_id) {
				$this->modal_error('El servicio no pertenece al área', 'Acción no autorizada');
				return;
			}
		} else {
			$this->load->model('servicio_funcion_model');
			$servicio_funcion = $this->servicio_funcion_model->get(array(
				'area_id' => $area_id,
				'servicio_id' => $servicio->id,
				'where' => array('fecha_hasta IS NULL')
			));
			if (empty($servicio_funcion)) {
				$this->modal_error('El servicio no tiene función en el área', 'Acción no autorizada');
				return;
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
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
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
					redirect("areas/personal_novedad/listar/$area_id/$mes", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("areas/personal_novedad/listar/$area_id/$mes", 'refresh');
			}
		}
		$novedades = $this->servicio_novedad_model->get(array(
			'join' => $this->servicio_novedad_model->default_join,
			'servicio_id' => $servicio->id,
			'ames' => $mes,
			'id !=' => $novedad->id,
			'where' => array('planilla_baja_id IS NULL'),
		));
		$fechas_inhabilitadas = array();
		if (!empty($novedades)) {
			foreach ($novedades as $novedad_o) {
				if ($novedad->concomitante === 'N') {
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
		$data['fecha_alta'] = '';
		if (!empty($servicio->fecha_alta)) {
			$f1 = $mes . '01';
			$f2 = (new DateTime($servicio->fecha_alta))->format('Ymd');
			if ($f2 > $f1) {
				$data['fecha_alta'] = (new DateTime($f2 . ' -1 day'))->format('d/m/Y');
			}
			$data['fecha_desde'] = (new DateTime(max(array($f1, $f2))))->format('d/m/Y');
		} else {
			$data['fecha_desde'] = (new DateTime($mes . '01'))->format('d/m/Y');
		}

		$data['fecha_hasta'] = (new DateTime($mes . '01 +1 month -1 day'))->format('d/m/Y');
		$data['ames'] = $mes;
		$data['novedades'] = $novedades;
		$data['fechas_inhabilitadas'] = $fechas_inhabilitadas;
		$data['novedades_concomitantes'] = $tipos_novedades[1];
		$data['fields'] = $this->build_fields($this->servicio_novedad_model->fields, $novedad);
		$data['servicio_novedad'] = $novedad;
		$data['servicio'] = $servicio;
		$data['url_redireccion'] = FALSE;
		$data['txt_btn'] = 'Editar';
		$data['title'] = "Editar novedad de <b>$servicio->apellido, $servicio->nombre</b> ($servicio->cuil <b>" . substr($servicio->liquidacion, -2) . "</b>)";
		$data['operacion'] = 'Editar';
		$this->load->view('servicio_novedad/servicio_novedad_modal_abm', $data);
	}

	public function modal_editar_pendiente($mes = NULL, $novedad_id = NULL, $area_id = NULL, $es_funcion = '0', $es_pendiente = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$mes == NULL || $novedad_id == NULL || $area_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($novedad_id) || !ctype_digit($area_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$area = $this->area_model->get_one($area_id);
		if (empty($area)) {
			$this->modal_error('No se encontró el registro área', 'Registro no encontrado');
			return;
		}
		if ($this->rol->codigo === ROL_AREA && strpos($area->codigo, substr($this->rol->entidad, 0, strpos($this->rol->entidad, '-'))) === FALSE) {
			$this->modal_error('No tiene permisos para acceder al área', 'Acción no autorizada');
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
			if ($servicio->area_id !== $area_id) {
				$this->modal_error('El servicio no pertenece al área.', 'Acción no autorizada');
				return;
			}
		} else {
			$this->load->model('servicio_funcion_model');
			$servicio_funcion = $this->servicio_funcion_model->get(array(
				'area_id' => $area_id,
				'servicio_id' => $servicio->id,
				'where' => array('fecha_hasta IS NULL')
			));
			if (empty($servicio_funcion)) {
				$this->modal_error('El servicio no tiene función en el área.', 'Acción no autorizada');
				return;
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
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
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
					redirect("areas/personal_novedad/listar/$area_id/$mes/" . ($es_pendiente ? '' : '1'), 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("areas/personal_novedad/listar/$area_id/$mes/" . ($es_pendiente ? '' : '1'), 'refresh');
			}
		}
		$novedades = $this->servicio_novedad_model->get(array(
			'join' => $this->servicio_novedad_model->default_join,
			'servicio_id' => $servicio->id,
			'ames' => $mes,
			'id !=' => $novedad->id,
			'where' => array('planilla_baja_id IS NULL'),
		));
		$fechas_inhabilitadas = array();
		if (!empty($novedades)) {
			foreach ($novedades as $novedad_o) {
				if ($novedad->concomitante === 'N') {
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
		$data['fecha_alta'] = '';
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
		$data['url_redireccion'] = FALSE;
		$data['txt_btn'] = 'Editar';
		if ($es_pendiente === '1') {
			$data['title'] = "Editar novedad pendiente de <b>$servicio->apellido, $servicio->nombre</b> ($servicio->cuil <b>" . substr($servicio->liquidacion, -2) . "</b>)";
			$data['txt_btn'] = 'Editar';
			$data['operacion'] = 'Editar p';
		} else {
			$data['title'] = "Confirmar novedad pendiente de <b>$servicio->apellido, $servicio->nombre</b> ($servicio->cuil <b>" . substr($servicio->liquidacion, -2) . "</b>)";
			$data['txt_btn'] = 'Confirmar';
			$data['operacion'] = 'Confirmar p';
		}
		$this->load->view('servicio_novedad/servicio_novedad_modal_abm', $data);
	}

	public function modal_eliminar($mes = NULL, $novedad_id = NULL, $area_id = NULL, $es_funcion = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$mes == NULL || $novedad_id == NULL || $area_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($novedad_id) || !ctype_digit($area_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$area = $this->area_model->get_one($area_id);
		if (empty($area)) {
			$this->modal_error('No se encontró el registro a área', 'Registro no encontrado');
			return;
		}
		if ($this->rol->codigo === ROL_AREA && strpos($area->codigo, substr($this->rol->entidad, 0, strpos($this->rol->entidad, '-'))) === FALSE) {
			$this->modal_error('No tiene permisos para acceder al área', 'Acción no autorizada');
			return;
		}
		$novedad = $this->servicio_novedad_model->get_one($novedad_id);
		if (empty($novedad)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($novedad->servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el servicio', 'Registro no encontrado');
			return;
		}
		if ($es_funcion === '0') {
			if ($servicio->area_id !== $area_id) {
				$this->modal_error('El servicio no pertenece al área', 'Acción no autorizada');
				return;
			}
		} else {
			$this->load->model('servicio_funcion_model');
			$servicio_funcion = $this->servicio_funcion_model->get(array(
				'area_id' => $area_id,
				'servicio_id' => $servicio->id,
				'where' => array('fecha_hasta IS NULL')
			));
			if (empty($servicio_funcion)) {
				$this->modal_error('El servicio no tiene función en el área', 'Acción no autorizada');
				return;
			}
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($novedad_id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;

			$this->load->model('planilla_asisnov_model');
			$planilla_id = $this->planilla_asisnov_model->get_planilla_area_abierta($area_id, $mes);
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
			redirect("areas/personal_novedad/listar/$area_id/$mes", 'refresh');
		}
		$data['servicio'] = $servicio;
		if ($servicio->regimen_tipo_id !== '1') {
			$this->load->model('horario_model');
			$data['horarios'] = $this->horario_model->get_horarios($servicio->cargo_id);
		}
		$data['novedades'] = $this->servicio_novedad_model->get(array(
			'join' => $this->servicio_novedad_model->default_join,
			'servicio_id' => $novedad->servicio_id,
			'id !=' => $novedad->id,
			'ames' => $mes,
			'where' => array('planilla_baja_id IS NULL'),
		));
		$data['fields'] = $this->build_fields($this->servicio_novedad_model->fields, $novedad, TRUE);
		$data['servicio_novedad'] = $novedad;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar novedad de servicio';
		if ($novedad->novedad === 'A') {
			$this->load->model('planilla_asisnov_model');
			$planilla = $this->planilla_asisnov_model->get_planilla_area($area->id, $novedad->ames);
			$data['permitido'] = $planilla->id === $novedad->planilla_alta_id && empty($planilla->fecha_cierre);
			$this->load->view('areas/personal_novedad/personal_novedad_modal_eliminar_servicio', $data);
		} else {
			$this->load->view('servicio_novedad/servicio_novedad_modal_ver', $data);
		}
	}

	public function modal_ver($mes = NULL, $novedad_id = NULL, $area_id = NULL, $es_funcion = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$mes == NULL || $novedad_id == NULL || $area_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($novedad_id) || !ctype_digit($area_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$novedad = $this->servicio_novedad_model->get_one($novedad_id);
		if (empty($novedad)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}
		$area = $this->area_model->get_one($area_id);
		if (empty($area)) {
			$this->modal_error('No se encontró el área', 'Registro no encontrado');
			return;
		}
		if ($this->rol->codigo === ROL_AREA && strpos($area->codigo, substr($this->rol->entidad, 0, strpos($this->rol->entidad, '-'))) === FALSE) {
			$this->modal_error('No tiene permisos para acceder al área', 'Acción no autorizada');
			return;
		}
		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($novedad->servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el servicio', 'Registro no encontrado');
			return;
		}
		if ($es_funcion === '0') {
			if ($servicio->area_id !== $area_id) {
				$this->modal_error('El servicio no pertenece al área.', 'Acción no autorizada');
				return;
			}
		} else {
			$this->load->model('servicio_funcion_model');
			$servicio_funcion = $this->servicio_funcion_model->get(array(
				'area_id' => $area_id,
				'servicio_id' => $servicio->id,
				'where' => array('fecha_hasta IS NULL')
			));
			if (empty($servicio_funcion)) {
				$this->modal_error('El servicio no tiene función en el área.', 'Acción no autorizada');
				return;
			}
		}
		if ($servicio->regimen_tipo_id !== '1') {
			$this->load->model('horario_model');
			$data['horarios'] = $this->horario_model->get_horarios($servicio->cargo_id);
		}
		$data['novedades'] = $this->servicio_novedad_model->get(array(
			'join' => $this->servicio_novedad_model->default_join,
			'servicio_id' => $novedad->servicio_id,
			'id !=' => $novedad->id,
			'ames' => $mes,
			'where' => array('planilla_baja_id IS NULL'),
		));
		$data['fields'] = $this->build_fields($this->servicio_novedad_model->fields, $novedad, TRUE);
		$data['servicio'] = $servicio;
		$data['servicio_novedad'] = $novedad;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver novedad de servicio';
		$this->load->view('servicio_novedad/servicio_novedad_modal_ver', $data);
	}
}
/* End of file Personal_novedad.php */
/* Location: ./application/modules/areas/controllers/Personal_novedad.php */
