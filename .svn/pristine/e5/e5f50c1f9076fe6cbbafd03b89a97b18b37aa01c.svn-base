<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Altas extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('planilla_tipo_model');
		$this->load->model('planilla_asisnov_model');
		$this->load->model('liquidaciones/altas_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_CONSULTA, ROL_JEFE_LIQUIDACION, ROL_LIQUIDACION);
		$this->roles_escuelas = array(ROL_ADMIN, ROL_USI, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_ESCUELA_CAR, ROL_JEFE_LIQUIDACION, ROL_LIQUIDACION, ROL_LINEA, ROL_SUPERVISION, ROL_REGIONAL, ROL_GRUPO_ESCUELA, ROL_CONSULTA, ROL_CONSULTA_LINEA);
		if (in_array($this->rol->codigo, array(ROL_CONSULTA)) || !in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'liquidaciones/altas';
	}

	public function aud_escuelas($planilla_tipo_id = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $planilla_tipo_id == NULL || !ctype_digit($planilla_tipo_id) || ($mes != NULL && !ctype_digit($mes))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$planilla_tipo = $this->planilla_tipo_model->get_one($planilla_tipo_id);
		if (empty($planilla_tipo)) {
			show_error('No se encontró el registro del tipo de planilla', 500, 'Registro no encontrado');
		}
		if (empty($mes) || empty(DateTime::createFromFormat('Ym', $mes))) {
			$mes = $this->planilla_asisnov_model->get_mes_auditoria();
			redirect("liquidaciones/altas/aud_escuelas/$planilla_tipo_id/$mes", 'refresh');
		}
		$tableData = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 2, 'class' => '', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => 'N°', 'data' => 'numero', 'width' => 8, 'class' => 'dt-body-right'),
				array('label' => 'Anexo', 'data' => 'anexo', 'width' => 8, 'class' => 'dt-body-right'),
				array('label' => 'Escuela', 'data' => 'nombre', 'width' => 20),
				array('label' => 'Departamento', 'data' => 'departamento', 'width' => 15),
				array('label' => 'Nivel', 'data' => 'nivel', 'width' => 15),
				array('label' => 'Altas Cargadas', 'data' => 'altas_cargadas', 'width' => 10, 'class' => 'text-sm dt-body-center'),
				array('label' => 'Altas Auditadas', 'data' => 'altas_auditadas', 'width' => 10, 'class' => 'text-sm dt-body-center'),
				array('label' => '', 'data' => 'menu', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => 'Teléfono', 'data' => 'telefono', 'width' => 10, 'responsive_class' => 'none')
			),
			'table_id' => 'altas_table',
			'source_url' => "liquidaciones/altas/aud_escuelas_data/$planilla_tipo_id/$mes/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(1, 'asc'), array(2, 'asc')),
			'details_format' => 'altas_table_detalles',
			'reuse_var' => TRUE,
			'initComplete' => "complete_altas_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['planilla_tipo'] = $planilla_tipo;
		$data['mes'] = $mes;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Auditoria de Altas - Escuelas';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';

		$this->load_template('altas/altas_aud_escuelas', $data);
	}

	public function aud_escuelas_data($planilla_tipo_id = NULL, $mes = NULL, $rol_codigo = NULL, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $planilla_tipo_id == NULL || !ctype_digit($planilla_tipo_id) || $mes == NULL || !ctype_digit($mes)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}

		$this->datatables
			->select("e.id, e.numero, e.anexo, e.nombre, e.telefono, COALESCE(d.descripcion, 'N/D') as departamento, n.descripcion as nivel, COUNT(DISTINCT sn_alta.id) as altas_cargadas, COUNT(DISTINCT CASE WHEN sn_alta.estado='Cargado' THEN NULL ELSE sn_alta.id END) as altas_auditadas")
			->unset_column('id')
			->from('servicio_novedad sn_alta')
			->join('servicio s', 'sn_alta.servicio_id = s.id')
			->join('situacion_revista sr', 's.situacion_revista_id = sr.id')
			->join('cargo c', 's.cargo_id = c.id')
			->join('regimen r', 'r.id = c.regimen_id AND r.planilla_modalidad_id=1')
			->join('escuela e', 'c.escuela_id = e.id AND e.dependencia_id = 1')
			->join('nivel n', 'e.nivel_id = n.id')
			->join('localidad l', 'e.localidad_id = l.id', 'left')
			->join('departamento d', 'l.departamento_id = d.id', 'left')
			->where('sn_alta.novedad_tipo_id', 1)
			->where('sr.planilla_tipo_id', $planilla_tipo_id)
			->where('sn_alta.ames', $mes)
			->where('sn_alta.planilla_baja_id IS NULL')
			->group_by('e.id')
			->add_column('', '', 'id')
			->add_column('menu', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="liquidaciones/altas/aud_escuela/$1/' . $planilla_tipo_id . '/' . $mes . '" title="Auditar"><i class="fa fa-search"></i> Auditar</a>', 'id');

		echo $this->datatables->generate();
	}

	public function departamento($planilla_tipo_id = NULL, $mes = NULL, $departamento_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $planilla_tipo_id == NULL || !ctype_digit($planilla_tipo_id) || $departamento_id === NULL || !ctype_digit($departamento_id) || ($mes != NULL && !ctype_digit($mes))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$planilla_tipo = $this->planilla_tipo_model->get_one($planilla_tipo_id);
		if (empty($planilla_tipo)) {
			show_error('No se encontró el registro del tipo de planilla', 500, 'Registro no encontrado');
		}
		if (empty($mes) || empty(DateTime::createFromFormat('Ym', $mes))) {
			$mes = $this->planilla_asisnov_model->get_mes_auditoria();
			redirect("liquidaciones/altas/departamento/$planilla_tipo_id/$mes/$departamento_id", 'refresh');
		}
		if ($departamento_id !== '0') {
			$this->load->model('departamento_model');
			$data['departamento'] = $this->departamento_model->get_one($departamento_id);
		}
		$tableData = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 2, 'class' => '', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => 'N°', 'data' => 'numero', 'width' => 8, 'class' => 'dt-body-right'),
				array('label' => 'Anexo', 'data' => 'anexo', 'width' => 8, 'class' => 'dt-body-right'),
				array('label' => 'Escuela', 'data' => 'nombre', 'width' => 25),
				array('label' => 'Nivel', 'data' => 'nivel', 'width' => 15),
				array('label' => 'Cargadas', 'data' => 'altas_cargadas', 'width' => 10, 'class' => 'text-sm dt-body-center'),
				array('label' => 'Pendientes', 'data' => 'altas_pendientes', 'width' => 10, 'class' => 'text-sm dt-body-center'),
				array('label' => 'Rechazadas', 'data' => 'altas_rechazadas', 'width' => 10, 'class' => 'text-sm dt-body-center'),
				array('label' => '', 'data' => 'menu', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => 'Teléfono', 'data' => 'telefono', 'width' => 10, 'responsive_class' => 'none')
			),
			'table_id' => 'altas_table',
			'source_url' => "liquidaciones/altas/departamento_data/$planilla_tipo_id/$departamento_id/0/$mes/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(1, 'asc'), array(2, 'asc')),
			'details_format' => 'altas_table_detalles',
			'reuse_var' => TRUE,
			'initComplete' => "complete_altas_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$tableData_2 = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 2, 'class' => '', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => 'N°', 'data' => 'numero', 'width' => 8, 'class' => 'dt-body-right'),
				array('label' => 'Anexo', 'data' => 'anexo', 'width' => 8, 'class' => 'dt-body-right'),
				array('label' => 'Escuela', 'data' => 'nombre', 'width' => 25),
				array('label' => 'Nivel', 'data' => 'nivel', 'width' => 15),
				array('label' => 'Cargadas', 'data' => 'altas_cargadas', 'width' => 10, 'class' => 'text-sm dt-body-center'),
				array('label' => 'Pendientes', 'data' => 'altas_pendientes', 'width' => 10, 'class' => 'text-sm dt-body-center'),
				array('label' => 'Rechazadas', 'data' => 'altas_rechazadas', 'width' => 10, 'class' => 'text-sm dt-body-center'),
				array('label' => '', 'data' => 'menu', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => 'Teléfono', 'data' => 'telefono', 'width' => 10, 'responsive_class' => 'none')
			),
			'table_id' => 'altas_auditadas_table',
			'source_url' => "liquidaciones/altas/departamento_data/$planilla_tipo_id/$departamento_id/1/$mes/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(1, 'asc'), array(2, 'asc')),
			'details_format' => 'altas_auditadas_table_detalles',
			'reuse_var' => TRUE,
			'initComplete' => "complete_altas_auditadas_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['planilla_tipo'] = $planilla_tipo;
		$data['mes'] = $mes;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['html_table_2'] = buildHTML($tableData_2);
		$data['js_table_2'] = buildJS($tableData_2);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Auditoria de Altas - Departamento';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';

		$this->load_template('altas/altas_departamento', $data);
	}

	public function departamento_data($planilla_tipo_id = NULL, $departamento = NULL, $auditadas = NULL, $mes = NULL, $rol_codigo = NULL, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $planilla_tipo_id == NULL || !ctype_digit($planilla_tipo_id) || $departamento == NULL || !ctype_digit($departamento) || $auditadas == NULL || !ctype_digit($auditadas) || $mes == NULL || !ctype_digit($mes)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}

		$this->datatables
			->select("e.id, e.numero, e.anexo, e.nombre, e.telefono, COALESCE(d.descripcion, 'N/D') as departamento, n.descripcion as nivel, COUNT(DISTINCT sn_alta.id) as altas_cargadas, COUNT(DISTINCT CASE WHEN sn_alta.estado='Cargado' THEN sn_alta.id ELSE NULL END) as altas_pendientes, COUNT(DISTINCT CASE WHEN sn_alta.estado='Rechazado' THEN sn_alta.id ELSE NULL END) as altas_rechazadas")
			->unset_column('id')
			->from('servicio_novedad sn_alta')
			->join('servicio s', 'sn_alta.servicio_id = s.id')
			->join('situacion_revista sr', 's.situacion_revista_id = sr.id')
			->join('cargo c', 's.cargo_id = c.id')
			->join('regimen r', 'r.id = c.regimen_id AND r.planilla_modalidad_id=1')
			->join('escuela e', 'c.escuela_id = e.id AND e.dependencia_id = 1')
			->join('nivel n', 'e.nivel_id = n.id')
			->join('localidad l', 'e.localidad_id = l.id', 'left')
			->join('departamento d', 'l.departamento_id = d.id', 'left')
			->where('sn_alta.novedad_tipo_id', 1)
			->where('sr.planilla_tipo_id', $planilla_tipo_id)
			->where('sn_alta.ames', $mes)
			->where('sn_alta.planilla_baja_id IS NULL')
			->group_by('e.id')
			->add_column('', '', 'id');
		if ($departamento === '0') {
			$this->datatables->where('d.id IS NULL');
		} else {
			$this->datatables->where('d.id', $departamento);
		}
		if ($auditadas) {
			$this->datatables->having("COUNT(DISTINCT CASE WHEN sn_alta.estado='Cargado' THEN sn_alta.id ELSE NULL END) = 0")
				->add_column('menu', '<div class="btn-group" role="group">'
					. '<a class="btn btn-xs btn-default" href="liquidaciones/altas/aud_escuela/$1/' . $planilla_tipo_id . '/' . $mes . '" title="Auditar"><i class="fa fa-search"></i> Ver</a>', 'id');
		} else {
			$this->datatables->having("COUNT(DISTINCT CASE WHEN sn_alta.estado='Cargado' THEN sn_alta.id ELSE NULL END) > 0")
				->add_column('menu', '<div class="btn-group" role="group">'
					. '<a class="btn btn-xs btn-default" href="liquidaciones/altas/aud_escuela/$1/' . $planilla_tipo_id . '/' . $mes . '" title="Auditar"><i class="fa fa-search"></i> Auditar</a>', 'id');
		}

		echo $this->datatables->generate();
	}

	public function modal_ver($escuela_id = NULL, $mes = NULL, $alta_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_escuelas) ||
			$mes == NULL || $alta_id == NULL || $escuela_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($alta_id) || !ctype_digit($escuela_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		if (!in_array($this->rol->codigo, $this->roles_permitidos) && $mes <= '201710') {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$this->load->model('servicio_novedad_model');
		$this->servicio_novedad_model->fields['dias']['label'] = 'Días a cumplir';
		$this->servicio_novedad_model->fields['obligaciones']['label'] = 'Obl. a cumplir';
		unset($this->servicio_novedad_model->fields['estado']);
		unset($this->servicio_novedad_model->fields['novedad_tipo']);
		unset($this->servicio_novedad_model->fields['fecha_desde']['readonly']);
		unset($this->servicio_novedad_model->fields['fecha_hasta']['required']);
		unset($this->servicio_novedad_model->fields['fecha_hasta']['readonly']);
		$this->servicio_novedad_model->fields['dias']['type'] = 'number';
		$alta = $this->servicio_novedad_model->get_one($alta_id);
		if (empty($alta)) {
			$this->modal_error('No se encontró el registro del alta', 'Registro no encontrado');
			return;
		}
		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($alta->servicio_id);
		if (empty($servicio) || $escuela->id !== $servicio->escuela_id) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$novedades = $this->servicio_novedad_model->get(array(
			'join' => $this->servicio_novedad_model->default_join,
			'servicio_id' => $servicio->id,
			'ames' => $mes,
			'id !=' => $alta->id,
			'where' => array('planilla_baja_id IS NULL'),
		));

		$data['servicio'] = $servicio;
		if ($servicio->regimen_tipo_id !== '1') {
			$this->load->model('horario_model');
			$data['horarios'] = $this->horario_model->get_horarios($servicio->cargo_id);
		}

		$data['novedades'] = $novedades;
		$data['mes'] = $mes;
		$data['alta'] = $alta;
		$alta->dias = number_format($alta->dias, 0);
		if (empty($servicio->fecha_baja)) {
			$this->servicio_novedad_model->fields['fecha_hasta']['readonly'] = TRUE;
			$alta->fecha_hasta = '';
		}
		$data['fields'] = $this->build_fields($this->servicio_novedad_model->fields, $alta, TRUE);

		$data['title'] = "Ver alta de <b>$servicio->apellido, $servicio->nombre</b> ($servicio->cuil)";
		$this->load->view('liquidaciones/altas/altas_modal_ver', $data);
	}

	public function modal_editar($escuela_id = NULL, $mes = NULL, $alta_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$mes == NULL || $alta_id == NULL || $escuela_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($alta_id) || !ctype_digit($escuela_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if ($mes !== $this->planilla_asisnov_model->get_mes_auditoria_habilitada()) {
			$this->edicion = FALSE;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$this->load->model('servicio_novedad_model');
		$this->servicio_novedad_model->fields['dias']['label'] = 'Días a cumplir';
		$this->servicio_novedad_model->fields['obligaciones']['label'] = 'Obl. a cumplir';
		unset($this->servicio_novedad_model->fields['estado']);
		unset($this->servicio_novedad_model->fields['novedad_tipo']);
		unset($this->servicio_novedad_model->fields['fecha_desde']['readonly']);
		unset($this->servicio_novedad_model->fields['fecha_hasta']['required']);
		unset($this->servicio_novedad_model->fields['fecha_hasta']['readonly']);
		$this->servicio_novedad_model->fields['dias']['type'] = 'number';
		$alta = $this->servicio_novedad_model->get_one($alta_id);
		if (empty($alta)) {
			$this->modal_error('No se encontró el registro del alta', 'Registro no encontrado');
			return;
		}
		if ($alta->estado === 'Procesado') {
			$this->modal_error('No puede editar un registro procesado', 'Acción no autorizada');
			return;
		}
		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($alta->servicio_id);
		if (empty($servicio) || $escuela->id !== $servicio->escuela_id) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->set_model_validation_rules($this->servicio_novedad_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;

				if ($trans_ok) {
					$trans_ok &= $this->servicio_novedad_model->update(array(
						'id' => $alta->id,
						'fecha_desde' => $this->get_date_sql('fecha_desde'),
						'fecha_hasta' => $this->input->post('fecha_hasta') ? $this->get_date_sql('fecha_hasta') : $this->get_date_sql('fecha_desde'),
						'dias' => $this->input->post('dias'),
						'obligaciones' => $servicio->regimen_tipo_id === '1' ? '0' : $this->input->post('obligaciones'),
						), FALSE);
					if (empty($servicio->fecha_baja) || (new DateTime($servicio->fecha_baja))->format('Ym') === $mes) {
						$trans_ok &= $this->servicio_model->update(array(
							'id' => $servicio->id,
							'fecha_alta' => $this->get_date_sql('fecha_desde'),
							'fecha_baja' => $this->get_date_sql('fecha_hasta'),
							'motivo_baja' => $this->input->post('fecha_hasta') ? '02-4 BAJA DE SERVICIO' : ''
							), FALSE);
					} else {
						$trans_ok &= $this->servicio_model->update(array(
							'id' => $servicio->id,
							'fecha_alta' => $this->get_date_sql('fecha_desde'),
							), FALSE);
					}
				}

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->servicio_novedad_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->servicio_novedad_model->get_error());
				}
				redirect("liquidaciones/altas/aud_escuela/$escuela_id/$servicio->planilla_tipo_id/$mes", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("liquidaciones/altas/aud_escuela/$escuela_id/$servicio->planilla_tipo_id/$mes", 'refresh');
			}
		}
		$novedades = $this->servicio_novedad_model->get(array(
			'join' => $this->servicio_novedad_model->default_join,
			'servicio_id' => $servicio->id,
			'ames' => $mes,
			'id !=' => $alta->id,
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

		$data['servicio'] = $servicio;
		if ($servicio->regimen_tipo_id !== '1') {
			$this->load->model('horario_model');
			$data['horarios'] = $this->horario_model->get_horarios($servicio->cargo_id);
		}

		$data['fecha_desde'] = (new DateTime($mes . '01'))->format('d/m/Y');

		$data['fecha_hasta'] = (new DateTime($mes . '01 +1 month -1 day'))->format('d/m/Y');
		$data['novedades'] = $novedades;
		$data['mes'] = $mes;
		$data['alta'] = $alta;
		$data['fechas_inhabilitadas'] = $fechas_inhabilitadas;
		$alta->dias = number_format($alta->dias, 0);
		if (empty($servicio->fecha_baja)) {
			$this->servicio_novedad_model->fields['fecha_hasta']['readonly'] = TRUE;
			$alta->fecha_hasta = '';
		} elseif ((new DateTime($servicio->fecha_baja))->format('Ym') > $mes) {
			$this->servicio_novedad_model->fields['fecha_hasta']['readonly'] = TRUE;
			$alta->fecha_hasta = '';
		}
		$data['fields'] = $this->build_fields($this->servicio_novedad_model->fields, $alta);
		if (empty($servicio->fecha_baja) || (new DateTime($servicio->fecha_baja))->format('Ym') === $mes) {
			$data['fields']['fecha_hasta']['form'] = '			<div class="input-group">
				' . $data['fields']['fecha_hasta']['form'] . '
				<span class="input-group-addon">
					<input type="checkbox" id="check_fecha_hasta"' . (empty($servicio->fecha_baja) ? '' : ' checked') . '>
				</span>
			</div>
';
		}

		$data['title'] = "Editar alta de <b>$servicio->apellido, $servicio->nombre</b> ($servicio->cuil)";
		$this->load->view('liquidaciones/altas/altas_modal_editar', $data);
	}

	public function modal_rechazar($escuela_id = NULL, $mes = NULL, $alta_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $mes == NULL || !ctype_digit($mes) || $alta_id == NULL || !ctype_digit($alta_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if ($mes !== $this->planilla_asisnov_model->get_mes_auditoria_habilitada()) {
			$this->edicion = FALSE;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);

		if (empty($escuela) || !$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}
		$this->load->model('servicio_novedad_model');
		$alta = $this->servicio_novedad_model->get_one($alta_id);
		if (empty($alta)) {
			$this->modal_error('No se encontró el registro del alta', 'Registro no encontrado');
			return;
		}
		if ($alta->estado === 'Procesado') {
			$this->modal_error('No puede editar un registro procesado', 'Acción no autorizada');
			return;
		}
		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($alta->servicio_id);
		if (empty($servicio) || $escuela->id !== $servicio->escuela_id) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$rechazar_model = new stdClass();
		$rechazar_model->fields = array(
			'motivo_rechazo' => array('label' => 'Motivo Rechazo', 'maxlength' => '100', 'required' => TRUE)
		);
		$alta->persona = "$servicio->cuil $servicio->apellido, $servicio->nombre";
		$alta->regimen = $servicio->regimen;
		$this->set_model_validation_rules($rechazar_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($escuela_id !== $this->input->post('escuela_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($alta_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->servicio_novedad_model->update(array(
					'id' => $alta->id,
					'motivo_rechazo' => $this->input->post('motivo_rechazo'),
					'estado' => 'Rechazado'
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->servicio_novedad_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->servicio_novedad_model->get_error());
				}
				redirect("liquidaciones/altas/aud_escuela/$escuela->id/$servicio->planilla_tipo_id/$mes", 'refresh');
			}
		}

		$data['escuela'] = $escuela;
		$data['mes'] = $mes;
		$data['alta'] = $alta;
		$data['servicio'] = $servicio;
		$data['fields'] = $this->build_fields($rechazar_model->fields, $alta);

		$data['title'] = 'Rechazar Alta';
		$this->load->view('altas/altas_modal_rechazar', $data);
	}

	public function modal_ver_auditoria($escuela_id = NULL, $mes = NULL, $alta_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_escuelas) ||
			$mes == NULL || $alta_id == NULL || $escuela_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($alta_id) || !ctype_digit($escuela_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		if (!in_array($this->rol->codigo, $this->roles_permitidos) && $mes <= '201710') {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$this->load->model('servicio_novedad_model');
		$alta = $this->servicio_novedad_model->get_one($alta_id);
		if (empty($alta)) {
			$this->modal_error('No se encontró el alta', 'Registro no encontrado');
			return;
		}

		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($alta->servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el servicio', 'Registro no encontrado');
			return;
		}
		$data['servicio'] = $servicio;

		$data['alta'] = $alta;
		$data['historicos'] = $this->servicio_novedad_model->get_auditoria($alta->id);
		$data['title'] = "Ver auditoría de alta de <b>$servicio->apellido, $servicio->nombre</b> ($servicio->cuil <b>" . substr($servicio->liquidacion, -2) . "</b>)";
		$this->load->view('liquidaciones/altas/altas_modal_ver_auditoria', $data);
	}

	public function aud_escuela($escuela_id = NULL, $planilla_tipo_id = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_escuelas) || $escuela_id == NULL || !ctype_digit($escuela_id) || $planilla_tipo_id == NULL || !ctype_digit($planilla_tipo_id) || $mes == NULL || !ctype_digit($mes)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_permitidos) && $mes <= '201710') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$planilla_tipo = $this->planilla_tipo_model->get_one($planilla_tipo_id);
		if (empty($planilla_tipo)) {
			show_error('No se encontró el registro del tipo de planilla', 500, 'Registro no encontrado');
		}
		if ($mes !== $this->planilla_asisnov_model->get_mes_auditoria_habilitada()) {
			$this->edicion = FALSE;
		}

		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);

		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->form_validation->set_rules('aprobar', 'Aprobar', 'integer');
		$this->form_validation->set_rules('revertir', 'Revertir', 'integer');
		if (isset($_POST) && !empty($_POST)) {
			if ($escuela_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$this->load->model('servicio_novedad_model');
				if ($this->input->post('aprobar')) {
					$alta = $this->servicio_novedad_model->get_one($this->input->post('aprobar'));
					if ($alta->estado === 'Procesado') {
						$this->session->set_flashdata('error', 'No puede editar un registro procesado');
						redirect("liquidaciones/altas/aud_escuela/$escuela->id/$planilla_tipo_id/$mes", 'refresh');
					}
					$trans_ok &= $this->servicio_novedad_model->update(array(
						'id' => $this->input->post('aprobar'),
						'estado' => 'Auditado',
						'motivo_rechazo' => 'NULL'
					));
				} elseif ($this->input->post('revertir')) {
					$alta = $this->servicio_novedad_model->get_one($this->input->post('revertir'));
					if ($alta->estado === 'Procesado') {
						$this->session->set_flashdata('error', 'No puede editar un registro procesado');
						redirect("liquidaciones/altas/aud_escuela/$escuela->id/$planilla_tipo_id/$mes", 'refresh');
					}
					$trans_ok &= $this->servicio_novedad_model->update(array(
						'id' => $this->input->post('revertir'),
						'estado' => 'Cargado',
						'motivo_rechazo' => 'NULL'
					));
				}
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->servicio_novedad_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->servicio_novedad_model->get_error());
				}
				redirect("liquidaciones/altas/aud_escuela/$escuela->id/$planilla_tipo_id/$mes", 'refresh');
			}
		}

		$data['escuela'] = $escuela;
		$data['planilla_tipo'] = $planilla_tipo;
		$data['mes'] = $mes;
		$altas = $this->altas_model->get_altas_escuela($planilla_tipo->id, $mes, $escuela->id);
		foreach ($altas as $alta) {
			$data['altas_estado'][$alta->estado][] = $alta;
		}
		$this->load->model('escuela_autoridad_model');
		$autoridades = $this->escuela_autoridad_model->get(array(
			'escuela_id' => $escuela->id,
			'join' => array(
				array('autoridad_tipo', 'autoridad_tipo.id = escuela_autoridad.autoridad_tipo_id', 'left', array('autoridad_tipo.descripcion as autoridad')),
				array('persona', 'persona.id = escuela_autoridad.persona_id', 'left', array('CONCAT(persona.apellido, \', \', persona.nombre) as persona', 'persona.cuil', 'persona.telefono_fijo', 'persona.telefono_movil', 'persona.email'))),
		));

		$data['autoridades'] = $autoridades;
		$data['rol_escuela'] = !in_array($this->rol->codigo, $this->roles_permitidos);
		if (AMES_LIQUIDACION === $mes) {
			$this->load->model('tbcabh_model');
			$data['casos_revisar'] = $this->tbcabh_model->get_casos_aud_altas($escuela, AMES_LIQUIDACION, $planilla_tipo_id);
		}
		$data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Auditoria de Altas - Escuelas';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';

		$this->load_template('altas/altas_aud_escuela', $data);
	}

	public function modal_pasar_auditoria($escuela_id = NULL, $mes = NULL, $servicio_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) ||
			$mes == NULL || $servicio_id == NULL || $escuela_id == NULL ||
			!ctype_digit($mes) || !ctype_digit($servicio_id) || !ctype_digit($escuela_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if ($mes !== $this->planilla_asisnov_model->get_mes_auditoria_habilitada()) {
			$this->edicion = FALSE;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if ($mes !== AMES_LIQUIDACION) {
			$this->modal_error('Aún no se ha procesado la liquidación del mes a auditar', 'Acción no autorizada');
			return;
		}

		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$this->load->model('servicio_novedad_model');
		unset($this->servicio_novedad_model->fields['estado']);
		unset($this->servicio_novedad_model->fields['novedad_tipo']);
		$this->servicio_novedad_model->fields['dias']['label'] = 'Días a cumplir';
		$this->servicio_novedad_model->fields['obligaciones']['label'] = 'Obl. a cumplir';
		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($servicio_id);
		if (empty($servicio) || $escuela->id !== $servicio->escuela_id) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('tbcabh_model');
		$caso = $this->tbcabh_model->es_caso_aud_altas($escuela, AMES_LIQUIDACION, $servicio);
		if (empty($caso)) {
			$this->modal_error('El servicio no figura en casos a revisar', 'Acción no autorizada');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($servicio->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;

			$trans_ok &= $this->servicio_novedad_model->create(array(
				'servicio_id' => $servicio->id,
				'ames' => $mes,
				'novedad_tipo_id' => '1',
				'fecha_desde' => (new DateTime($mes . '01'))->format('Y-m-d'),
				'fecha_hasta' => (new DateTime($mes . '01'))->format('Y-m-d'),
				'estado' => 'Cargado',
				'dias' => 30,
				'obligaciones' => $servicio->regimen_tipo_id === '1' ? '0' : $servicio->carga_horaria * 4,
				), FALSE);
			if (empty($servicio->fecha_alta)) {
				$observaciones = $servicio->observaciones;
			} else {
				$observaciones = (empty($servicio->observaciones) ? '' : "$servicio->observaciones ") . 'F. Alta: ' . (new DateTime($servicio->fecha_alta))->format('d/m/Y');
			}
			$trans_ok &= $this->servicio_model->update(array(
				'id' => $servicio->id,
				'fecha_alta' => (new DateTime($mes . '01'))->format('Y-m-d'),
				'fecha_baja' => '',
				'motivo_baja' => '',
				'observaciones' => $observaciones
				), FALSE);

			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->servicio_novedad_model->get_msg());
			} else {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', $this->servicio_novedad_model->get_error());
			}
			redirect("liquidaciones/altas/aud_escuela/$escuela_id/$servicio->planilla_tipo_id/$mes", 'refresh');
		}

		$data['servicio'] = $servicio;

		$data['fecha_desde'] = (new DateTime($mes . '01'))->format('d/m/Y');

		$data['fecha_hasta'] = (new DateTime($mes . '01 +1 month -1 day'))->format('d/m/Y');
		$data['mes'] = $mes;
		$data['alta'] = $servicio;
		$servicio->dias = 30;
		$servicio->obligaciones = $servicio->carga_horaria * 4;
		$servicio->fecha_desde = (new DateTime($mes . '01'))->format('Y-m-d');
		$servicio->fecha_hasta = '';
		$data['fields'] = $this->build_fields($this->servicio_novedad_model->fields, $servicio, TRUE);
		$data['title'] = "Pasar a auditoría alta de <b>$servicio->apellido, $servicio->nombre</b> ($servicio->cuil)";
		$this->load->view('liquidaciones/altas/altas_modal_pasar_auditoria', $data);
	}

	public function cambiar_mes($planilla_tipo_id, $mes, $escuela_id = '') {
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
		if (empty($escuela_id)) {
			redirect("liquidaciones/altas/aud_escuelas/$planilla_tipo_id/$mes", 'refresh');
		} else {
			redirect("liquidaciones/altas/aud_escuela/$escuela_id/$planilla_tipo_id/$mes", 'refresh');
		}
	}

	public function ajax_get_altas() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela_id = $this->input->get('escuela_id');
		$planilla_tipo_id = $this->input->get('planilla_tipo_id');
		$mes = $this->input->get('mes');
		if (empty($escuela_id) || empty($planilla_tipo_id) || empty($mes)) {
			show_error('No se han recibido los parámetros necesarios', 500, 'Acción no autorizada');
		}

		$altas = $this->altas_model->get_altas_escuela($planilla_tipo_id, $mes, $escuela_id);

		if (!empty($altas)) {
			echo json_encode($altas);
			return;
		} else {
			echo json_encode(array());
		}
	}

	public function exportar_auditoria_excel($planilla_tipo_id, $mes) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $planilla_tipo_id == NULL || !ctype_digit($planilla_tipo_id) || ($mes != NULL && !ctype_digit($mes))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$planilla_tipo = $this->planilla_tipo_model->get_one($planilla_tipo_id);
		if (empty($planilla_tipo)) {
			show_error('No se encontró el registro del tipo de planilla', 500, 'Registro no encontrado');
		}
		$altas = $this->altas_model->get_altas($planilla_tipo->id, $mes);

		$campos = array(
			'A' => array('Mes', 10),
			'B' => array('Novedad', 10),
			'C' => array('Departamento', 15),
			'D' => array('Nº Escuela', 10),
			'E' => array('Anexo', 10),
			'F' => array('Escuela', 30),
			'G' => array('Juri', 5),
			'H' => array('UO', 5),
			'I' => array('Repa', 5),
			'J' => array('Cuil', 15),
			'K' => array('Persona', 30),
			'L' => array('S.R', 15),
			'M' => array('Liquidación', 15),
			'N' => array('Condición', 10),
			'O' => array('Nº Régimen', 12),
			'P' => array('Régimen', 30),
			'Q' => array('Puntos', 7),
			'R' => array('Carga horaria', 12),
			'S' => array('Fecha alta', 12),
			'T' => array('Fecha baja', 12),
			'U' => array('Motivo baja', 30),
			'V' => array('Días', 5),
			'W' => array('Obligaciones', 12),
			'X' => array('Estado', 10),
			'Y' => array('Motivo Rechazo', 30),
			'Z' => array('Usuario', 30),
			'AA' => array('Fecha Auditoria', 20),
			'AB' => array('Cuil Reemplazante', 18),
			'AC' => array('Persona Reemplazante', 30),
			'AD' => array('Liquidación Reemplazante', 30),
			'AE' => array('Artículo Reemplazante', 30),
			'AF' => array('Nº Artículo Reemplazante', 10),
		);

		$this->exportar_excel(array('title' => "Altas servicios"), $campos, $altas);
	}
}
/* End of file Altas.php */
/* Location: ./application/controllers/Altas.php */