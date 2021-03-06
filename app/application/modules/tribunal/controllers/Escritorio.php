<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escritorio extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('persona_model');
		$this->load->model('nivel_model');
		$this->load->model('escuela_model');
		$this->load->model('referente_model');
		$this->load->model('tribunal_cuenta_model');
		$this->roles_admin = array(ROL_ADMIN, ROL_USI);
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_ESCUELA_ALUM, ROL_ESCUELA_CAR, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA);
		$this->modulos_permitidos = array(ROL_MODULO_REFERENTES_TRIBUNAL_CUENTAS);
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_CAR, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA))) {
			$this->edicion = FALSE;
		}
	}

	public function listar($nivel_id = '0') {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Número', 'data' => 'numero', 'width' => 5),
				array('label' => 'Anexo', 'data' => 'anexo', 'class' => 'dt-body-right', 'width' => 5),
				array('label' => 'CUE', 'data' => 'cue', 'class' => 'dt-body-right', 'width' => 5),
				array('label' => 'Nombre', 'data' => 'nombre', 'width' => 25),
				array('label' => 'Nivel', 'data' => 'nivel', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'Juri/Repa', 'data' => 'jurirepa', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Supervisión', 'data' => 'supervision', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Delegación', 'data' => 'delegacion', 'width' => 8, 'class' => 'text-sm'),
				array('label' => 'Zona', 'data' => 'zona', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Teléfono', 'data' => 'telefono', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Email', 'data' => 'email', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'edit', 'width' => 5, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'escuela_table',
			'source_url' => "tribunal/escritorio/listar_data/$nivel_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'reuse_var' => TRUE,
			'initComplete' => "complete_escuela_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);

		$tableData_referentes = array(
			'columns' => array(
				array('label' => 'Num. Esc.<br>Num. de Cuenta', 'data' => 'cuenta_escuela', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Documento<br>CUIL', 'data' => 'documento_cuil', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Persona', 'data' => 'persona', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'email', 'data' => 'email', 'width' => 15, 'class' => 'text-sm'),
				array('label' => 'Telefono', 'data' => 'telefono_fijo', 'width' => 8, 'class' => 'text-sm'),
				array('label' => 'Domicilio real', 'data' => 'domicilio_real', 'width' => 18, 'class' => 'text-sm'),
				array('label' => 'Cargo', 'data' => 'cargo', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Periodo', 'data' => 'fecha_desde', 'width' => 7, ''),
				array('label' => 'Estado', 'data' => 'estado', 'width' => 6),
				array('label' => '', 'data' => 'edit', 'width' => 6, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'referentes_table',
			'source_url' => "tribunal/escritorio/referentes_data/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'reuse_var' => TRUE,
			'initComplete' => "complete_referentes_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['html_table_referentes'] = buildHTML($tableData_referentes);
		$data['js_table_referentes'] = buildJS($tableData_referentes);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		if (empty($nivel_id)) {
			$data['title'] = TITLE . ' - Escuelas';
		} else {
			$this->load->model('nivel_model');
			$nivel = $this->nivel_model->get(array('id' => $nivel_id));
			$data['nivel'] = $nivel;
			$data['title'] = TITLE . ' - Escuelas Nivel ' . $nivel->descripcion;
		}
		$this->load_template('tribunal/escritorio/escritorio', $data);
	}

	public function listar_data($nivel_id, $rol_codigo, $entidad_id = '') {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('escuela.id, escuela.numero, escuela.anexo, escuela.cue, escuela.subcue, escuela.nombre, escuela.calle, escuela.calle_numero, escuela.departamento, escuela.piso, escuela.barrio, escuela.manzana, escuela.casa, escuela.localidad_id, escuela.nivel_id, escuela.reparticion_id, escuela.supervision_id, escuela.regional_id, escuela.delegacion_id, escuela.dependencia_id, escuela.zona_id, escuela.fecha_creacion, escuela.anio_resolucion, escuela.numero_resolucion, escuela.telefono, escuela.email, escuela.fecha_cierre, escuela.anio_resolucion_cierre, escuela.numero_resolucion_cierre, dependencia.descripcion as dependencia, nivel.descripcion as nivel, delegacion.descripcion as delegacion, CONCAT(jurisdiccion.codigo,\'/\',reparticion.codigo) as jurirepa, supervision.nombre as supervision, zona.descripcion as zona')
			->unset_column('id')
			->from('escuela')
			->join('dependencia', 'dependencia.id = escuela.dependencia_id', 'left')
			->join('nivel', 'nivel.id = escuela.nivel_id', 'left')
			->join('delegacion', 'delegacion.id = escuela.delegacion_id', 'left')
			->join('reparticion', 'reparticion.id = escuela.reparticion_id', 'left')
			->join('jurisdiccion', 'jurisdiccion.id = reparticion.jurisdiccion_id', 'left')
			->join('supervision', 'supervision.id = escuela.supervision_id', 'left')
			->join('zona', 'zona.id = escuela.zona_id', 'left');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="tribunal/escritorio/escuela/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '</ul></div>', 'id');
		} else {
			$this->datatables->add_column('edit', '<a class="btn btn-xs btn-default" href="tribunal/escritorio/escuela/$1"><i class="fa fa-search"></i> Ver</a>', 'id');
		}
		if (!empty($nivel_id)) {
			$this->datatables->where('nivel.id', $nivel_id);
		}
		switch ($this->rol->codigo) {
			case ROL_GRUPO_ESCUELA:
			case ROL_GRUPO_ESCUELA_CONSULTA:
				$this->datatables
					->join('escuela_grupo_escuela', 'escuela_grupo_escuela.escuela_id = escuela.id', 'left')
					->join('escuela_grupo', 'escuela_grupo_escuela.escuela_grupo_id = escuela_grupo.id', 'left')
					->where('escuela_grupo.id', $this->rol->entidad_id);
				break;
			case ROL_LINEA:
			case ROL_CONSULTA_LINEA:
				$this->datatables->where('nivel.linea_id', $this->rol->entidad_id);
				break;
			case ROL_DIR_ESCUELA:
			case ROL_SDIR_ESCUELA:
			case ROL_SEC_ESCUELA:
				$this->datatables->where('escuela.id', $this->rol->entidad_id);
				break;
			case ROL_SUPERVISION:
				$this->datatables->where('supervision.id', $this->rol->entidad_id);
				break;
			case ROL_PRIVADA:
				$this->datatables->where('dependencia_id', 2);
				break;
			case ROL_SEOS:
				$this->datatables->where('dependencia_id', 3);
				break;
			case ROL_REGIONAL:
				$this->datatables->where('regional.id', $this->rol->entidad_id);
				break;
			case ROL_ADMIN:
			case ROL_MODULO:
			case ROL_USI:
			case ROL_JEFE_LIQUIDACION:
			case ROL_LIQUIDACION:
			case ROL_CONSULTA:
				break;
			default:
				echo '';
				return;
		}

		echo $this->datatables->generate();
	}

	public function referentes_data($rol_codigo, $entidad_id = '') {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select("tribunal_cuenta.id, CONCAT(escuela.numero,'<br>', tribunal_cuenta.numero_cuenta) as cuenta_escuela, referente.id, referente_vigencia.id, referente.estado, escuela.id as escuela_id,escuela.numero, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, CONCAT(documento_tipo.descripcion_corta,' ', persona.documento) as documento, persona.cuil, persona.email, persona.telefono_fijo, persona.calle as domicilio_legal, CONCAT(COALESCE(CONCAT(persona.calle,' '),''), COALESCE(CONCAT(persona.calle_numero,' '),''), COALESCE(CONCAT('Dpto:',persona.departamento,' '),''), COALESCE(CONCAT('P:',persona.piso,' '),''), COALESCE(CONCAT('B°:',persona.barrio,' '),''), COALESCE(CONCAT('M:',persona.manzana,' '),''), COALESCE(CONCAT('C:',persona.casa,' '),'')) as domicilio_real, regimen.descripcion as cargo, (CASE WHEN referente_vigencia.fecha_hasta IS NULL THEN 1 ELSE 0 END) as estado, CONCAT(DATE_FORMAT(referente_vigencia.fecha_desde,'%d/%m/%y'),'<br>', COALESCE(DATE_FORMAT(referente_vigencia.fecha_hasta,'%d/%m/%y'),'')) as fecha_desde, CONCAT(CONCAT(documento_tipo.descripcion_corta,' ', persona.documento),'<br>', persona.cuil) as documento_cuil")
			->unset_column('id')
			->from('tribunal_cuenta')
			->join('referente', 'referente.tribunal_cuenta_id = tribunal_cuenta.id', 'left')
			->join('referente_vigencia', 'referente_vigencia.referente_id = referente.id', 'left')
			->join('servicio', 'servicio.id = referente.servicio_id', 'left')
			->join('cargo', 'cargo.id = servicio.cargo_id', 'left')
			->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', 'left')
			->join('regimen', 'regimen.id = cargo.regimen_id', 'left')
			->join('persona', 'persona.id = servicio.persona_id', 'left')
			->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id')
			->join('escuela', 'escuela.id = tribunal_cuenta.escuela_id', 'left')
			->add_column('estado', '$1', 'dt_column_referente_vigencia(estado)')
			->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="alumno/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item btn-warning" href="alumno/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '</ul></div>', ' referente_vigencia.id');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="tribunal/referente/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item btn-warning" href="tribunal/referente/cerrar_Periodo/$1" title=" Cerrar Período" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-calendar"></i> Cerrar Período</a></li>'
				. '<li><a class="dropdown-item btn-danger" href="tribunal/referente/modal_eliminar_referente/$1" title="Eliminar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i> Eliminar</a></li>'
				. '</ul></div>', 'id, escuela_id');
		} else {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="tribunal/referente/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item btn-warning" href="tribunal/referente/cerrar_Periodo/$1" title=" Cerrar Período" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-calendar"></i> Cerrar Período</a></li>'
				. '</ul></div>', 'id, escuela_id');
		}
		echo $this->datatables->generate();
	}

	public function escuela($escuela_id = '0') {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$cuentas_bancarias = $this->tribunal_cuenta_model->get(array('escuela_id' => $escuela_id));
		if (empty($cuentas_bancarias)) {
			show_error('No se encontró el registro de cuenta bancaria', 500, 'Registro no encontrado');
		}
		foreach ($cuentas_bancarias as $cuenta) {
			$tableData[$cuenta->id] = array(
				'columns' => array(
					array('label' => 'Documento/CUIL', 'data' => 'documento_cuil', 'width' => 10, 'class' => 'text-sm'),
					array('label' => 'Persona', 'data' => 'persona', 'width' => 20, 'class' => 'text-sm'),
					array('label' => 'email', 'data' => 'email', 'width' => 15, 'class' => 'text-sm'),
					array('label' => 'Telefono', 'data' => 'telefono_fijo', 'width' => 8, 'class' => 'text-sm'),
					array('label' => 'Domicilio real', 'data' => 'domicilio_real', 'width' => 18, 'class' => 'text-sm'),
					array('label' => 'Cargo', 'data' => 'cargo', 'width' => 10, 'class' => 'text-sm'),
					array('label' => 'Periodo', 'data' => 'fecha_desde', 'width' => 7, ''),
					array('label' => 'Estado', 'data' => 'estado', 'width' => 6),
					array('label' => '', 'data' => 'edit', 'width' => 6, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
				),
				'table_id' => 'referente' . "$cuenta->id" . '_table',
				'source_url' => "tribunal/escritorio/escuela_data/$escuela_id/$cuenta->id/{$this->rol->codigo}/{$this->rol->entidad_id}",
				'reuse_var' => TRUE,
				'initComplete' => "complete_referente$cuenta->id" . "_table",
				'order' => array(array(0, 'asc'), array(7, 'desc')),
				'footer' => TRUE,
				'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
			);
			$html_table[$cuenta->id] = buildHTML($tableData[$cuenta->id]);
			$js_table[$cuenta->id] = buildJS($tableData[$cuenta->id]);
		}
		$data['html_table'] = $html_table;
		$data['js_table'] = $js_table;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['cuentas_bancarias'] = $cuentas_bancarias;
		if (empty($escuela_id)) {
			$data['title'] = TITLE . ' - Tribunal';
		} else {
			$this->load->model('escuela_model');
			$escuela = $this->escuela_model->get(array('id' => $escuela_id));
			$data['escuela'] = $escuela;
			$data['title'] = TITLE . ' - Escuelas tribunal';
		}
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('escritorio/escritorio_escuela', $data);
	}

	public function escuela_data($escuela_id, $cuenta_id, $rol_codigo, $entidad_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select("tribunal_cuenta.id, referente.id, referente_vigencia.id, referente.estado, escuela.id as escuela_id,escuela.numero, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona,CONCAT(documento_tipo.descripcion_corta,' ', persona.documento) as documento , persona.cuil, persona.email, persona.telefono_fijo, persona.calle as domicilio_legal, CONCAT(COALESCE(CONCAT(persona.calle,' '),''), COALESCE(CONCAT(persona.calle_numero,' '),''), COALESCE(CONCAT('Dpto:',persona.departamento,' '),''), COALESCE(CONCAT('P:',persona.piso,' '),''), COALESCE(CONCAT('B°:',persona.barrio,' '),''), COALESCE(CONCAT('M:',persona.manzana,' '),''), COALESCE(CONCAT('C:',persona.casa,' '),'')) as domicilio_real, regimen.descripcion as cargo, (CASE WHEN referente_vigencia.fecha_hasta IS NULL THEN 1 ELSE 0 END) as estado, CONCAT(DATE_FORMAT(referente_vigencia.fecha_desde,'%d/%m/%y'),'<br>', COALESCE(DATE_FORMAT(referente_vigencia.fecha_hasta,'%d/%m/%y'),'')) as fecha_desde, CONCAT(CONCAT(documento_tipo.descripcion_corta,' ', persona.documento),'<br>', persona.cuil) as documento_cuil")
			->unset_column('id')
			->from('tribunal_cuenta')
			->join('referente', 'referente.tribunal_cuenta_id = tribunal_cuenta.id', 'left')
			->join('referente_vigencia', 'referente_vigencia.referente_id = referente.id', 'left')
			->join('servicio', 'servicio.id = referente.servicio_id', 'left')
			->join('cargo', 'cargo.id = servicio.cargo_id', 'left')
			->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', 'left')
			->join('regimen', 'regimen.id = cargo.regimen_id', 'left')
			->join('persona', 'persona.id = servicio.persona_id', 'left')
			->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id')
			->join('escuela', 'escuela.id = tribunal_cuenta.escuela_id', 'left')
			->where('tribunal_cuenta.escuela_id', $escuela_id)
			->where('tribunal_cuenta.id', $cuenta_id)
			->add_column('estado', '$1', 'dt_column_referente_vigencia(estado)')
			->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="alumno/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item btn-warning" href="alumno/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '</ul></div>', ' referente_vigencia.id');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="tribunal/referente/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item btn-warning" href="tribunal/referente/cerrar_Periodo/$1" title=" Cerrar Período" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-calendar"></i> Cerrar Período</a></li>'
				. '<li><a class="dropdown-item btn-danger" href="tribunal/referente/modal_eliminar_referente/$1" title="Eliminar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i> Eliminar</a></li>'
				. '</ul></div>', 'id, escuela_id');
		} else {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="tribunal/referente/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item btn-warning" href="tribunal/referente/cerrar_Periodo/$1" title=" Cerrar Período" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-calendar"></i> Cerrar Período</a></li>'
				. '</ul></div>', 'id, escuela_id');
		}
		echo $this->datatables->generate();
	}

	public function modal_reporte_dinamico($escuela_id = NULL, $cuenta_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $cuenta_id == NULL || !ctype_digit($cuenta_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		$tribunal_cuenta = $this->tribunal_cuenta_model->get_one($cuenta_id);
		if (empty($tribunal_cuenta)) {
			$this->modal_error('No se encontró el registro de cuenta bancaria', 'Registro no encontrado');
			return;
		}
//		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
//			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
//			return;
//		}

		$this->set_model_validation_rules($this->tribunal_cuenta_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($tribunal_cuenta->id !== $this->input->post('tribunal_cuenta_id') && $escuela->id !== $this->input->post('escuela_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$fecha_desde = $this->get_date_sql('fecha_desde');
			$fecha_hasta = $this->get_date_sql('fecha_hasta');
			$referentes = $this->referente_model->get_lista_referentes_reporte($escuela_id, $cuenta_id, $fecha_desde, $fecha_hasta);
			$data['fecha_desde'] = $this->input->post('fecha_desde');
			$data['fecha_hasta'] = $this->input->post('fecha_hasta');
			$data['referentes'] = $referentes;
			$data['escuela'] = $escuela;
			$data['tribunal_cuenta'] = $tribunal_cuenta;
			$content = $this->load->view('tribunal/escritorio/escuela_imprimir_reporte', $data, TRUE);

			$this->load->helper('mpdf');
			exportMPDF0($content, '.: Reporte de Referentes - Tribunal de Cuentas - DGE :.', '', '', 'LEGAL-L', 'I', FALSE, FALSE);
		}

		$data['escuela'] = $escuela;
		$data['tribunal_cuenta'] = $tribunal_cuenta;
		$data['txt_btn'] = 'Generar';
		$data['title'] = 'Referentes reporte dinamico';
		$this->load->view('escritorio/reporte_modal_dinamico', $data);
	}

	public function imprimir_reporte($escuela_id = NULL, $cuenta_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $cuenta_id == NULL || !ctype_digit($cuenta_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		$tribunal_cuenta = $this->tribunal_cuenta_model->get_one($cuenta_id);
		if (empty($tribunal_cuenta)) {
			show_error('No se encontró el registro de cuenta', 500, 'Registro no encontrado');
		}
//		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
//			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
//		}

		$referentes = $this->referente_model->get_lista_referentes($escuela_id, $cuenta_id);
		$data['referentes'] = $referentes;
		$data['escuela'] = $escuela;
		$data['tribunal_cuenta'] = $tribunal_cuenta;
		$content = $this->load->view('tribunal/escritorio/escuela_imprimir_reporte', $data, TRUE);

		$this->load->helper('mpdf');
		exportMPDF0($content, '.: Reporte de Referentes - Tribunal de Cuentas - DGE :.', '', '', 'LEGAL-L', 'I', FALSE, FALSE);
	}

	public function modal_editar_cuenta($escuela_id = NULL, $cuenta_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $cuenta_id == NULL || !ctype_digit($cuenta_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		$tribunal_cuenta = $this->tribunal_cuenta_model->get_one($cuenta_id);
		if (empty($tribunal_cuenta)) {
			$this->modal_error('No se encontró el registro de cuenta bancaria', 'Registro no encontrado');
			return;
		}
//		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
//			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
//			return;
//		}

		$this->set_model_validation_rules($this->tribunal_cuenta_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($tribunal_cuenta->id !== $this->input->post('tribunal_cuenta_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->tribunal_cuenta_model->update(array(
					'id' => $tribunal_cuenta->id,
					'descripcion_cuenta' => $this->input->post('descripcion_cuenta'),
					'numero_cuenta' => $this->input->post('numero_cuenta')
					), FALSE);

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->tribunal_cuenta_model->get_msg());
					redirect("tribunal/escritorio/escuela/$escuela->id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->tribunal_cuenta_model->get_error())
						$errors .= '<br>' . $this->tribunal_cuenta_model->get_error();
					$this->session->set_flashdata('error', $errors);
					redirect("tribunal/escritorio/escuela/$escuela->id", 'refresh');
				}
			}else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("tribunal/escritorio/escuela/$escuela->id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->tribunal_cuenta_model->fields, $tribunal_cuenta);
		$data['escuela'] = $escuela;
		$data['tribunal_cuenta'] = $tribunal_cuenta;
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar número de cuenta';
		$this->load->view('tribunal/escritorio/escuela_modal_editar_cuenta', $data);
	}

	public function excel_referentes() {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$campos = array(
			'A' => array('Num. Esc.', 15),
			'B' => array('Num. de Cuenta', 25),
			'C' => array('Documento', 15),
			'D' => array('CUIL', 15),
			'E' => array('Persona', 25),
			'F' => array('email', 35),
			'G' => array('Telefono', 15),
			'H' => array('Domicilio real', 35),
			'I' => array('Cargo', 20),
			'J' => array('Periodo', 20),
			'K' => array('Estado', 15)
		);

		$referentes = $this->db->select("escuela.numero as escuela_numero, tribunal_cuenta.numero_cuenta as cuenta_escuela, CONCAT(documento_tipo.descripcion_corta,' ', persona.documento) as documento, persona.cuil as cuil, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, persona.email, persona.telefono_fijo, CONCAT(COALESCE(CONCAT(persona.calle,' '),''), COALESCE(CONCAT(persona.calle_numero,' '),''), COALESCE(CONCAT('Dpto:',persona.departamento,' '),''), COALESCE(CONCAT('P:',persona.piso,' '),''), COALESCE(CONCAT('B°:',persona.barrio,' '),''), COALESCE(CONCAT('M:',persona.manzana,' '),''), COALESCE(CONCAT('C:',persona.casa,' '),'')) as domicilio_real, regimen.descripcion as cargo, CONCAT(DATE_FORMAT(referente_vigencia.fecha_desde,'%d/%m/%y'),' - ', COALESCE(DATE_FORMAT(referente_vigencia.fecha_hasta,'%d/%m/%y'),'')) as fecha_desde, (CASE WHEN referente_vigencia.fecha_hasta IS NULL THEN 'Vigente' ELSE 'No vigente' END) as estado")
				->from('tribunal_cuenta')
				->join('referente', 'referente.tribunal_cuenta_id = tribunal_cuenta.id', 'left')
				->join('referente_vigencia', 'referente_vigencia.referente_id = referente.id', 'left')
				->join('servicio', 'servicio.id = referente.servicio_id', 'left')
				->join('cargo', 'cargo.id = servicio.cargo_id', 'left')
				->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', 'left')
				->join('regimen', 'regimen.id = cargo.regimen_id', 'left')
				->join('persona', 'persona.id = servicio.persona_id', 'left')
				->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id')
				->join('escuela', 'escuela.id = tribunal_cuenta.escuela_id', 'left')
				->get()->result_array();

		if (!empty($referentes)) {
			$this->exportar_excel(array('title' => "Tribunal de cuentas - Referentes"), $campos, $referentes);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect("division/ver/$id", 'refresh');
		}
	}
}