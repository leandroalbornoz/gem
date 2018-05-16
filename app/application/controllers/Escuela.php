<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escuela extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE_CURSADA));
		$this->roles_agregar = array(ROL_ADMIN, ROL_USI);
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_GRUPO_ESCUELA_CONSULTA, ROL_REGIONAL, ROL_ESCUELA_ALUM, ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'admin/escuela';
	}

	public function listar($nivel_id = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Número', 'data' => 'numero', 'width' => 5),
				array('label' => 'Anexo', 'data' => 'anexo', 'class' => 'dt-body-right', 'width' => 5),
				array('label' => 'CUE', 'data' => 'cue', 'class' => 'dt-body-right', 'width' => 5),
				array('label' => 'Nombre', 'data' => 'nombre', 'width' => 20),
				array('label' => 'Nivel', 'data' => 'nivel', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'Juri/Repa', 'data' => 'jurirepa', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Supervisión', 'data' => 'supervision', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Delegación', 'data' => 'delegacion', 'width' => 8, 'class' => 'text-sm'),
				array('label' => 'Zona', 'data' => 'zona', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Teléfono', 'data' => 'telefono', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Email', 'data' => 'email', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'escuela_table',
			'source_url' => "escuela/listar_data/$nivel_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'reuse_var' => TRUE,
			'initComplete' => "complete_escuela_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
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
		$this->load_template('escuela/escuela_listar', $data);
	}

	public function listar_data($nivel_id, $rol_codigo, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
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
			->join('regional', 'regional.id = escuela.regional_id', 'left')
			->join('jurisdiccion', 'jurisdiccion.id = reparticion.jurisdiccion_id', 'left')
			->join('supervision', 'supervision.id = escuela.supervision_id', 'left')
			->join('zona', 'zona.id = escuela.zona_id', 'left');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="escuela/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item" href="escuela/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '</ul></div>', 'id');
		} else {
			$this->datatables->add_column('edit', '<a class="btn btn-xs btn-default" href="escuela/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id');
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

	public function modal_seleccionar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$options['join'] = array(
			array('nivel', 'nivel.id = escuela.nivel_id', 'left')
		);
		switch ($this->rol->codigo) {
			case ROL_LINEA:
			case ROL_GRUPO_ESCUELA:
			case ROL_CONSULTA_LINEA:
				$options['where'][] = array('column' => 'nivel.linea_id', 'value' => $this->rol->entidad_id);
				break;
			case ROL_DIR_ESCUELA:
			case ROL_SDIR_ESCUELA:
			case ROL_SEC_ESCUELA:
				$this->modal_error('No tiene permisos para la acción solicitada', 'Acceso denegado');
				return;
				break;
			case ROL_SUPERVISION:
				$options['supervision_id'] = $this->rol->entidad_id;
				break;
			case ROL_PRIVADA:
				$options['dependencia_id'] = 2;
				break;
			case ROL_SEOS:
				$options['dependencia_id'] = 3;
				break;
			case ROL_REGIONAL:
				$options['regional_id'] = $this->rol->entidad_id;
				break;
			default:
		}

		$options['sort_by'] = 'escuela.numero, escuela.anexo';
		$this->array_escuela_control = $array_escuela = $this->get_array('escuela', 'nombre_largo', 'id', $options, array('' => 'Seleccione escuela'));
		unset($this->array_escuela_control['']);
		$buscador_model = new stdClass();
		$buscador_model->fields = array(
			'escuela' => array('label' => 'Escuela', 'input_type' => 'combo', 'required' => TRUE)
		);

		$this->set_model_validation_rules($buscador_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				redirect("escuela/escritorio/" . $this->input->post('escuela'), 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("escritorio", 'refresh');
			}
		}

		$buscador_model->fields['escuela']['array'] = $array_escuela;
		$data['fields'] = $this->build_fields($buscador_model->fields);
		$data['txt_btn'] = 'Seleccionar';
		$data['title'] = 'Seleccionar escuela';
		$this->load->view('escuela/escuela_modal_seleccionar', $data);
	}

	public function agregar() {
		if (!in_array($this->rol->codigo, $this->roles_agregar)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('dependencia_model');
		$this->load->model('linea_model');
		$this->load->model('localidad_model');
		$this->load->model('nivel_model');
		$this->load->model('regimen_lista_model');
		$this->load->model('regional_model');
		$this->load->model('delegacion_model');
		$this->load->model('reparticion_model');
		$this->load->model('supervision_model');
		$this->load->model('zona_model');
		$this->array_dependencia_control = $array_dependencia = $this->get_array('dependencia', 'descripcion', 'id', null, array('' => '-- Seleccionar gestión --'));
		$this->array_linea_control = $array_linea = $this->get_array('linea', 'nombre', 'id', null, array('' => '-- Seleccionar linea --'));
		$this->array_localidad_control = $array_localidad = $this->get_array('localidad', 'localidad', 'id', array(
			'join' => array(
				array('departamento', 'departamento.id=localidad.departamento_id', 'left', array('CONCAT(departamento.descripcion, \' - \', localidad.descripcion) as localidad'))
			),
			'sort_by' => 'departamento.descripcion, localidad.descripcion'
			), array('' => '-- Seleccionar localidad --'));
		$this->array_nivel_control = $array_nivel = $this->get_array('nivel', 'descripcion', 'id', null, array('' => '-- Seleccionar nivel --'));
		$this->array_regimen_lista_control = $array_regimen_lista = $this->get_array('regimen_lista', 'descripcion', 'id', null, array('' => '-- Seleccionar lista de regímenes --'));
		$this->array_regional_control = $array_regional = $this->get_array('regional', 'descripcion', 'id', null, array('' => '-- Seleccionar regional --'));
		$this->array_delegacion_control = $array_delegacion = $this->get_array('delegacion', 'descripcion', 'id', null, array('' => '-- Seleccionar delegación --'));
		$this->array_reparticion_control = $array_reparticion = $this->get_array('reparticion', 'reparticion', 'id', array(
			'select' => array('reparticion.id', 'CONCAT(jurisdiccion.codigo, \' \', reparticion.codigo, \' \', reparticion.descripcion) as reparticion'),
			'join' => array(
				array('jurisdiccion', 'jurisdiccion.id = reparticion.jurisdiccion_id', 'left'),
			),
			'sort_by' => 'jurisdiccion.codigo, reparticion.codigo'
			), array('' => '-- Seleccionar repartición --'));
		$this->array_supervision_control = $array_supervision = $this->get_array('supervision', 'nombre', 'id', null, array('' => '-- Seleccionar supervisión --'));
		$this->array_zona_control = $array_zona = $this->get_array('zona', 'descripcion', 'id', array('sort_by' => 'valor'), array('' => '-- Seleccionar zona --'));
		$this->set_model_validation_rules($this->escuela_model);
		if ($this->form_validation->run() === TRUE) {
			$trans_ok = TRUE;
			$trans_ok &= $this->escuela_model->create(array(
				'numero' => $this->input->post('numero'),
				'anexo' => '0',
				'cue' => $this->input->post('cue'),
				'subcue' => $this->input->post('subcue'),
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
				'nivel_id' => $this->input->post('nivel'),
				'regimen_lista_id' => $this->input->post('regimen_lista'),
				'reparticion_id' => $this->input->post('reparticion'),
				'supervision_id' => $this->input->post('supervision'),
				'regional_id' => $this->input->post('regional'),
				'delegacion_id' => $this->input->post('delegacion'),
				'dependencia_id' => $this->input->post('dependencia'),
				'zona_id' => $this->input->post('zona'),
				'fecha_creacion' => $this->get_date_sql('fecha_creacion'),
				'anio_resolucion' => $this->input->post('anio_resolucion'),
				'numero_resolucion' => $this->input->post('numero_resolucion'),
				'telefono' => $this->input->post('telefono'),
				'email' => $this->input->post('email'),
				'email2' => $this->input->post('email2'),
				'fecha_cierre' => $this->get_date_sql('fecha_cierre'),
				'anio_resolucion_cierre' => $this->input->post('anio_resolucion_cierre'),
				'numero_resolucion_cierre' => $this->input->post('numero_resolucion_cierre')
			));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->escuela_model->get_msg());
				redirect('escuela/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->escuela_model->get_error() ? $this->escuela_model->get_error() : $this->session->flashdata('error')));

		$this->escuela_model->fields['dependencia']['array'] = $array_dependencia;
		$this->escuela_model->fields['linea']['array'] = $array_linea;
		$this->escuela_model->fields['localidad']['array'] = $array_localidad;
		$this->escuela_model->fields['nivel']['array'] = $array_nivel;
		$this->escuela_model->fields['regimen_lista']['array'] = $array_regimen_lista;
		$this->escuela_model->fields['regional']['array'] = $array_regional;
		$this->escuela_model->fields['delegacion']['array'] = $array_delegacion;
		$this->escuela_model->fields['reparticion']['array'] = $array_reparticion;
		$this->escuela_model->fields['supervision']['array'] = $array_supervision;
		$this->escuela_model->fields['zona']['array'] = $array_zona;
		$data['fields'] = $this->build_fields($this->escuela_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'escritorio' => 'disabled', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = TITLE . ' - Agregar escuela';
		$this->load_template('escuela/escuela_abm', $data);
	}

	public function editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("escuela/ver/$id");
		}

		$escuela = $this->escuela_model->get(array('id' => $id, 'join' => array(
				array('supervision', 'supervision.id=escuela.supervision_id', 'left', array('supervision.nombre as supervision', 'supervision.id as supervision_id')),
				array('nivel', 'nivel.id=escuela.nivel_id', 'left', array('nivel.linea_id', 'nivel.descripcion as nivel')),
				array('regional', 'regional.id=escuela.regional_id', 'left', array('regional.id as regional_id', 'regional.descripcion as regional')),
				array('delegacion', 'delegacion.id=escuela.delegacion_id', 'left', array('delegacion.id as delegacion_id', 'delegacion.descripcion as delegacion')),
				array('dependencia', 'dependencia.id=escuela.dependencia_id', 'left', array('dependencia.descripcion as dependencia', 'dependencia.id as dependencia_id')),
				array('reparticion', 'reparticion.id = escuela.reparticion_id', 'left'),
				array('linea', 'linea.id = nivel.linea_id', 'left', array('linea.nombre as linea')),
				array('regimen_lista', 'escuela.regimen_lista_id = regimen_lista.id', 'left', array('regimen_lista.descripcion as regimen_lista')),
				array('jurisdiccion', 'jurisdiccion.id = reparticion.jurisdiccion_id', 'left', array('CONCAT(jurisdiccion.codigo, \' \', reparticion.codigo, \' \', reparticion.descripcion) as reparticion')),
		)));

		if (empty($escuela)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->load->model('dependencia_model');
		$this->load->model('linea_model');
		$this->load->model('localidad_model');
		$this->load->model('nivel_model');
		$this->load->model('regimen_lista_model');
		$this->load->model('regional_model');
		$this->load->model('delegacion_model');
		$this->load->model('supervision_model');
		$this->load->model('zona_model');
		$this->array_localidad_control = $array_localidad = $this->get_array('localidad', 'localidad', 'id', array(
			'join' => array(
				array('departamento', 'departamento.id=localidad.departamento_id', 'left', array('CONCAT(departamento.descripcion, \' - \', localidad.descripcion) as localidad'))
			),
			'sort_by' => 'departamento.descripcion, localidad.descripcion'
			), array('' => '-- Seleccionar localidad --'));
		$this->array_zona_control = $array_zona = $this->get_array('zona', 'descripcion', 'id', array('sort_by' => 'valor'), array('' => '-- Seleccionar zona --'));
		unset($this->escuela_model->fields['reparticion']['input_type']);
		unset($this->escuela_model->fields['reparticion']['id_name']);
		$this->escuela_model->fields['reparticion']['readonly'] = TRUE;

		if (in_array($this->rol->codigo, array(ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA))) {
			unset($this->escuela_model->fields['linea']['input_type']);
			unset($this->escuela_model->fields['linea']['id_name']);
			$this->escuela_model->fields['linea']['readonly'] = TRUE;
			$this->escuela_model->fields['linea']['value'] = $escuela->linea;
			unset($this->escuela_model->fields['nivel']['input_type']);
			unset($this->escuela_model->fields['nivel']['id_name']);
			$this->escuela_model->fields['nivel']['readonly'] = TRUE;
			$this->escuela_model->fields['nivel']['value'] = $escuela->nivel;
			unset($this->escuela_model->fields['dependencia']['input_type']);
			unset($this->escuela_model->fields['dependencia']['id_name']);
			$this->escuela_model->fields['dependencia']['readonly'] = TRUE;
			$this->escuela_model->fields['dependencia']['value'] = $escuela->dependencia;
			$this->array_supervision_control = $array_supervision = $this->get_array('supervision', 'nombre', 'id', array(
				'nivel_id' => $escuela->nivel_id,
				'dependencia_id' => $escuela->dependencia_id,
				'sort_by' => 'orden'
				), array('' => '-- Seleccionar supervisión --'));
		} else {
			$this->array_linea_control = $array_linea = $this->get_array('linea', 'nombre', 'id', null, array('' => '-- Seleccionar linea --'));
			$this->escuela_model->fields['linea']['array'] = $array_linea;
			$this->array_nivel_control = $array_nivel = $this->get_array('nivel', 'descripcion', 'id', null, array('' => '-- Seleccionar nivel --'));
			$this->escuela_model->fields['nivel']['array'] = $array_nivel;
			$this->array_dependencia_control = $array_dependencia = $this->get_array('dependencia', 'descripcion', 'id', null, array('' => '-- Seleccionar gestión --'));
			$this->escuela_model->fields['dependencia']['array'] = $array_dependencia;
			$this->array_supervision_control = $array_supervision = $this->get_array('supervision', 'nombre', 'id', null, array('' => '-- Seleccionar supervisión --'));
		}

		if (in_array($this->rol->codigo, array(ROL_DIR_ESCUELA))) {
			unset($this->escuela_model->fields['regional']['input_type']);
			unset($this->escuela_model->fields['regional']['id_name']);
			$this->escuela_model->fields['regional']['value'] = $escuela->regional;
			$this->escuela_model->fields['regional']['readonly'] = TRUE;
			unset($this->escuela_model->fields['delegacion']['input_type']);
			unset($this->escuela_model->fields['delegacion']['id_name']);
			$this->escuela_model->fields['delegacion']['value'] = $escuela->delegacion;
			$this->escuela_model->fields['delegacion']['readonly'] = TRUE;
		} else {
			$this->array_regional_control = $array_regional = $this->get_array('regional', 'descripcion', 'id', null, array('' => '-- Seleccionar regional --'));
			$this->escuela_model->fields['regional']['array'] = $array_regional;
			$this->array_delegacion_control = $array_delegacion = $this->get_array('delegacion', 'descripcion', 'id', null, array('' => '-- Seleccionar delegacion --'));
			$this->escuela_model->fields['delegacion']['array'] = $array_delegacion;
		}

		if (in_array($this->rol->codigo, array(ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_ADMIN, ROL_USI, ROL_PRIVADA, ROL_LIQUIDACION, ROL_JEFE_LIQUIDACION))) {
			$this->array_supervision_control = $array_supervision = $this->get_array('supervision', 'nombre', 'id', null, array('' => '-- Seleccionar supervisión --'));
			$this->escuela_model->fields['supervision']['array'] = $array_supervision;
		} else {
			unset($this->escuela_model->fields['supervision']['input_type']);
			unset($this->escuela_model->fields['supervision']['id_name']);
			$this->escuela_model->fields['supervision']['readonly'] = TRUE;
			$this->escuela_model->fields['supervision']['value'] = $escuela->supervision;
		}

		if ($this->rol->codigo !== ROL_ADMIN && $this->rol->codigo !== ROL_USI) {
			$this->escuela_model->fields['numero']['readonly'] = TRUE;
			unset($this->escuela_model->fields['regimen_lista']['input_type']);
			unset($this->escuela_model->fields['regimen_lista']['id_name']);
			$this->escuela_model->fields['regimen_lista']['readonly'] = TRUE;
			$this->escuela_model->fields['regimen_lista']['value'] = $escuela->regimen_lista;
			if (!empty($escuela->cue)) {
				$this->escuela_model->fields['cue']['readonly'] = TRUE;
			}
		} else {
			$this->array_regimen_lista_control = $array_regimen_lista = $this->get_array('regimen_lista', 'descripcion', 'id', null, array('' => '-- Seleccionar lista de regímenes --'));
			$this->escuela_model->fields['regimen_lista']['array'] = $array_regimen_lista;
		}
		$this->set_model_validation_rules($this->escuela_model);
		$errors = '';
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->escuela_model->update(array(
					'id' => $this->input->post('id'),
					'numero' => isset($this->escuela_model->fields['numero']['readonly']) ? $escuela->numero : $this->input->post('numero'),
					'anexo' => $this->input->post('anexo'),
					'cue' => isset($this->escuela_model->fields['cue']['readonly']) ? $escuela->cue : $this->input->post('cue'),
					'subcue' => $this->input->post('subcue'),
					'nombre' => $this->input->post('nombre'),
					'calle' => $this->input->post('calle'),
					'calle_numero' => $this->input->post('calle_numero'),
					'barrio' => $this->input->post('barrio'),
					'localidad_id' => $this->input->post('localidad'),
					'codigo_postal' => $this->input->post('codigo_postal'),
					'nivel_id' => isset($this->escuela_model->fields['nivel']['readonly']) ? $escuela->nivel_id : $this->input->post('nivel'),
					'regimen_lista_id' => isset($this->escuela_model->fields['regimen_lista']['readonly']) ? $escuela->regimen_lista_id : $this->input->post('regimen_lista'),
//					'reparticion_id' => $this->input->post('reparticion'),
					'supervision_id' => isset($this->escuela_model->fields['supervision']['readonly']) ? $escuela->supervision_id : $this->input->post('supervision'),
					'regional_id' => isset($this->escuela_model->fields['regional']['readonly']) ? $escuela->regional_id : $this->input->post('regional'),
					'delegacion_id' => isset($this->escuela_model->fields['delegacion']['readonly']) ? $escuela->delegacion_id : $this->input->post('delegacion'),
					'dependencia_id' => isset($this->escuela_model->fields['dependencia']['readonly']) ? $escuela->dependencia_id : $this->input->post('dependencia'),
					'zona_id' => $this->input->post('zona'),
					'fecha_creacion' => $this->get_date_sql('fecha_creacion'),
					'anio_resolucion' => $this->input->post('anio_resolucion'),
					'numero_resolucion' => $this->input->post('numero_resolucion'),
					'telefono' => $this->input->post('telefono'),
					'email' => $this->input->post('email'),
					'email2' => $this->input->post('email2'),
					'fecha_cierre' => $this->get_date_sql('fecha_cierre'),
					'anio_resolucion_cierre' => $this->input->post('anio_resolucion_cierre'),
					'numero_resolucion_cierre' => $this->input->post('numero_resolucion_cierre')
					), FALSE);

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->escuela_model->get_msg());
					redirect('escuela/listar', 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->escuela_model->get_error())
						$errors .= '<br>' . $this->escuela_model->get_error();
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : (!empty($errors) ? $this->escuela_model->get_error() : $this->session->flashdata('error')));

		$this->escuela_model->fields['localidad']['array'] = $array_localidad;
		$this->escuela_model->fields['zona']['array'] = $array_zona;
		$data['fields'] = $this->build_fields($this->escuela_model->fields, $escuela);

		$data['escuela'] = $escuela;

		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'escritorio' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar escuela';
		$this->load_template('escuela/escuela_abm', $data);
	}

	public function caracteristicas($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("escuela/ver/$id");
		}
		$escuela = $this->escuela_model->get_one($id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->load->model('caracteristica_escuela_model');
		$fields_caracteristicas = $this->caracteristica_escuela_model->get_fields($escuela->nivel_id, $escuela->id, FALSE, TRUE);
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
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$this->load->model('caracteristica_model');
				$this->load->model('caracteristica_valor_model');
				foreach ($this->input->post('caracteristicas') as $id_caracteristica => $valor_caracteristica) {
					if (!isset($lista_caracteristicas[$id_caracteristica]->caracteristica_escuela_id)) {
						if ($lista_caracteristicas[$id_caracteristica]->lista_valores === 'Si') {
							if (!empty($valor_caracteristica)) {
								$valor = $this->caracteristica_valor_model->get_valor($valor_caracteristica);
								if (!empty($valor)) {
									$trans_ok &= $this->caracteristica_escuela_model->create(array(
										'escuela_id' => $id,
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
								$trans_ok &= $this->caracteristica_escuela_model->create(array(
									'escuela_id' => $id,
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
										$trans_ok &= $this->caracteristica_escuela_model->update(array(
											'id' => $lista_caracteristicas[$id_caracteristica]->caracteristica_escuela_id,
											'fecha_hasta' => date('Y-m-d')
											), FALSE);
										$trans_ok &= $this->caracteristica_escuela_model->create(array(
											'escuela_id' => $id,
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
									$trans_ok &= $this->caracteristica_escuela_model->update(array(
										'id' => $lista_caracteristicas[$id_caracteristica]->caracteristica_escuela_id,
										'fecha_hasta' => date('Y-m-d')
										), FALSE);
//									No insertar características vacías
//									$trans_ok &= $this->caracteristica_escuela_model->create(array(
//										'escuela_id' => $id,
//										'caracteristica_id' => $id_caracteristica,
//										'fecha_desde' => date('Y-m-d'),
//										'valor' => 'NULL',
//										'caracteristica_valor_id' => 'NULL'
//										), FALSE);
								}
							}
						} else {
							if ($lista_caracteristicas[$id_caracteristica]->valor != $valor_caracteristica) {
								$trans_ok &= $this->caracteristica_escuela_model->update(array(
									'id' => $lista_caracteristicas[$id_caracteristica]->caracteristica_escuela_id,
									'fecha_hasta' => date('Y-m-d')
									), FALSE);
								if (!empty($valor_caracteristica)) {//No insertar características vacías
									$trans_ok &= $this->caracteristica_escuela_model->create(array(
										'escuela_id' => $id,
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
					if (isset($caracteristica->caracteristica_escuela_id)) {
						$trans_ok &= $this->caracteristica_escuela_model->update(array(
							'id' => $caracteristica->caracteristica_escuela_id,
							'fecha_hasta' => date('Y-m-d')
							), FALSE);
//					No insertar características vacías
//					$trans_ok &= $this->caracteristica_escuela_model->create(array(
//						'escuela_id' => $id,
//						'caracteristica_id' => $id_caracteristica,
//						'fecha_desde' => date('Y-m-d'),
//						'valor' => 'NULL',
//						'caracteristica_valor_id' => 'NULL'
//						), FALSE);
					}
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->caracteristica_escuela_model->get_msg());
					redirect("escuela/ver/$escuela->id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->caracteristica_escuela_model->get_error())
						$errors .= '<br>' . $this->caracteristica_escuela_model->get_error();
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : (!empty($errors) ? $errors : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->escuela_model->fields, $escuela, TRUE);

		foreach ($fields_tipos as $tipo => $fields) {
			$data['fields_tipos'][$tipo] = $this->build_fields($fields);
		}

		$data['escuela'] = $escuela;

		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'escritorio' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar características de escuela';
		$this->load_template('escuela/escuela_caracteristicas', $data);
	}

	public function eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_agregar) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->escuela_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->escuela_model->get_msg());
				redirect('escuela/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->escuela_model->get_error() ? $this->escuela_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->escuela_model->fields, $escuela, TRUE);

		$this->load->model('caracteristica_escuela_model');
		$fields_tipos = $this->caracteristica_escuela_model->get_fields($escuela->nivel_id, $escuela->id, TRUE);
		foreach ($fields_tipos as $tipo => $fields) {
			$data['fields_tipos'][$tipo] = $this->build_fields($fields);
		}

		$data['escuela'] = $escuela;

		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'escritorio' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = TITLE . ' - Eliminar escuela';
		$this->load_template('escuela/escuela_abm', $data);
	}

	public function ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->escuela_model->fields, $escuela, TRUE);

		$this->load->model('caracteristica_escuela_model');
		$fields_tipos = $this->caracteristica_escuela_model->get_fields($escuela->nivel_id, $escuela->id, TRUE);
		foreach ($fields_tipos as $tipo => $fields) {
			$data['fields_tipos'][$tipo] = $this->build_fields($fields);
		}

		$this->load->model('escuela_autoridad_model');
		$autoridades = $this->escuela_autoridad_model->get(array(
			'escuela_id' => $id,
			'join' => array(
				array('autoridad_tipo', 'autoridad_tipo.id = escuela_autoridad.autoridad_tipo_id', 'left', array('autoridad_tipo.descripcion as autoridad')),
				array('persona', 'persona.id = escuela_autoridad.persona_id', 'left', array('CONCAT(persona.apellido, \', \', persona.nombre) as persona', 'persona.cuil', 'persona.telefono_fijo', 'persona.telefono_movil', 'persona.email'))),
		));

		$data['autoridades'] = $autoridades;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'escritorio' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver escuela';
		$this->load_template('escuela/escuela_abm', $data);
	}

	public function autoridades($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('escuela_autoridad_model');
		$autoridades = $this->escuela_autoridad_model->get(array(
			'escuela_id' => $id,
			'join' => array(
				array('autoridad_tipo', 'autoridad_tipo.id = escuela_autoridad.autoridad_tipo_id', 'left', array('autoridad_tipo.descripcion as autoridad')),
				array('persona', 'persona.id = escuela_autoridad.persona_id', 'left', array('CONCAT(persona.apellido, \', \', persona.nombre) as persona', 'persona.cuil', 'persona.telefono_fijo', 'persona.telefono_movil', 'persona.email'))),
		));

		$data['fields'] = $this->build_fields($this->escuela_model->fields, $escuela, TRUE);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['autoridades'] = $autoridades;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'escritorio' => '', 'ver' => '', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver autoridades';
		$this->load_template('escuela/escuela_autoridades', $data);
	}

	public function escritorio($id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('anuncio_model');
		$data['anuncios'] = $this->anuncio_model->get_anuncios();

		$escuela = $this->escuela_model->get_one($id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$data['administrar'] = TRUE;
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			if (in_array($this->rol->codigo, array(ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_CONSULTA_LINEA)) && !empty($escuela->escuela_id)) {
				$escuela_sede = $this->escuela_model->get_one($escuela->escuela_id);
				if ($this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela_sede)) {
					$data['administrar'] = FALSE;
				} else {
					show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
				}
			} else {
				show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
			}
		}
		$this->load->model('caracteristica_escuela_model');
		$fields_tipos = $this->caracteristica_escuela_model->get_fields($escuela->nivel_id, $escuela->id, TRUE);
		foreach ($fields_tipos as $tipo => $fields) {
			$data['fields_tipos'][$tipo] = $this->build_fields($fields);
		}
		$data['escuela'] = $escuela;
		/* Auditoría Liquidaciones */
		if ($escuela->dependencia_id === '1') {
			$this->load->model('liquidaciones/liquidaciones_model');
			$data['liquidaciones'] = $this->load->view('liquidaciones/escritorio_escuela_modulo_liquidaciones', $this->liquidaciones_model->get_vista($escuela->id, $data), TRUE);
		}
		/* Fines */
		if ($escuela->nivel_id === '9') {
			$this->load->model('tem/tem_model');
			$escuela_tem = $this->tem_model->get_escuela_tem($escuela->id);
			if (!empty($escuela_tem)) {
				$data['modulos']['tem'] = $this->load->view('tem/escritorio_escuela_modulo_tem', $this->tem_model->get_vista($escuela->id, $data), TRUE);
			}
		}
		/* Plan Conectividad Nacional */
		if ($escuela->dependencia_id === '1') {
			$this->load->model('conectividad/conectividad_model');
			$escuela_conectividad = $this->conectividad_model->get_escuela_conectividad($escuela->id);
			if (!empty($escuela_conectividad)) {
				$data['modulos']['conectividad'] = $this->load->view('conectividad/escritorio_escuela_modulo_conectividad', $this->conectividad_model->get_vista($escuela->id, $data), TRUE);
			}
		}
		/* Preinscripción 1° Grado 2018 */
		if ($escuela->nivel_id === '2' && $escuela->dependencia_id === '1'/* && ENVIRONMENT !== 'production' */) {
			$this->load->model('preinscripciones/preinscripcion_calendario_model');
			$data['preinscripcion_instancias'] = $this->preinscripcion_calendario_model->get_instancias(FALSE);
			$this->load->model('preinscripciones/preinscripcion_model');
			$data['modulos']['escuela_preinscripcion'] = $this->load->view('preinscripciones/escritorio_escuela_modulo_preinscripciones', $this->preinscripcion_model->get_vista($escuela->id, 2018, $data), TRUE);
		}
		/* Relevamiento Extintores */
		if ($escuela->dependencia_id === '1') {
			$this->load->model('extintores/extintores_model');
			$escuela_extintores = $this->extintores_model->get_escuela_extintores($escuela->id);
			if (!empty($escuela_extintores)) {
				$data['modulos_inactivos']['extintores'] = $this->load->view('extintores/escritorio_escuela_modulo_extintores', $this->extintores_model->get_vista($escuela->id, $data), TRUE);
			}
		}
		/* Operativo Aprender 2017 */
		$this->load->model('aprender/aprender_operativo_model');
		$escuela_operativo = $this->aprender_operativo_model->get_escuela_operativo($escuela->id);
		if (!empty($escuela_operativo)) {
			$this->load->model('aprender/aprender_operativo_aplicador_model');
			$data['aprender_aplicadores'] = $this->aprender_operativo_aplicador_model->get_aplicadores_escuela($escuela->id);
			$data['modulos_inactivos']['aprender_operativo'] = $this->load->view('aprender/escritorio_escuela_modulo_aprender', $this->aprender_operativo_model->get_vista($escuela->id, $data), TRUE);
		}
		/* Plan de completamiento de datos para SEOS */
		if ($escuela->dependencia_id === '3') {
			$this->load->model('completamiento/completamiento_model');
			$data['modulos']['completamiento'] = $this->load->view('completamiento/escritorio_escuela_modulo_completamiento', $this->completamiento_model->get_vista($escuela->id, $data), TRUE);
		}
		/* Desinfección Elecciones */
		$this->load->model('elecciones/elecciones_model');
		$escuela_elecciones = $this->elecciones_model->get_escuela_elecciones($escuela->id);
		if (!empty($escuela_elecciones)) {
			if ($escuela_elecciones->eleccion_id === '4') {
				$data['modulos']['elecciones'] = $this->load->view('elecciones/escritorio_escuela_modulo_elecciones', $this->elecciones_model->get_vista($escuela->id, $data, TRUE), TRUE);
			} else {
				$data['modulos_inactivos']['elecciones'] = $this->load->view('elecciones/escritorio_escuela_modulo_elecciones', $this->elecciones_model->get_vista($escuela->id, $data), TRUE);
			}
		}
		/* Evaluar operativo */
		if ($this->rol->codigo === ROL_DIR_ESCUELA && $escuela->dependencia_id === '2' && $escuela->nivel_id === '2') {
			$this->load->model('operativo_evaluar/evaluar_operativo_model');
			$data['modulos']['evaluar_operativo'] = $this->load->view('operativo_evaluar/escritorio_escuela_evaluar_operativo', $this->evaluar_operativo_model->get_vista($escuela->id, $data), TRUE);
		}
		if ($escuela->anexo === '0') {
			$data['anexos'] = $this->escuela_model->get_anexos($escuela->id);
		}
		/* Comedor */
		if (ENVIRONMENT !== 'production') {
			$this->load->model('comedor/comedor_model');
			$comedor_presupuesto = $this->comedor_model->get_escuela_comedor($escuela->id);
			if (!empty($comedor_presupuesto->escuela_id)) {
				$data['meses'] = explode(",", $comedor_presupuesto->meses);
				$data['comedor_presupuesto'] = $comedor_presupuesto;
				$data['comedor_mes_nombre'] = $this->nombres_meses[substr($comedor_presupuesto->mes, 4, 2)] . '\'' . substr($comedor_presupuesto->mes, 2, 2);
				$mes = new DateTime((!empty($comedor_presupuesto->mes) ? $comedor_presupuesto->mes : date('Ym')) . '01 ');
				$data['ames'] = $mes->format('Ym');
				$data['mes'] = $mes->format('d/m/Y');
				$data['modulos']['comedor'] = $this->load->view('comedor/escritorio_escuela_modulo_comedor', $this->comedor_model->get_vista($escuela->id, $data, TRUE), TRUE);
			}
		}

		$this->load->model('escuela_carrera_model');
		$data['carreras'] = $this->escuela_carrera_model->get_by_escuela($escuela->id);
		$this->load->model('cargo_model');
		$data['cargos'] = $this->cargo_model->get_by_escuela($escuela->id);
		$this->load->model('division_model');
		$divisiones = $this->division_model->get_by_escuela($escuela->id);
		$data['divisiones'] = $divisiones;
		$data['indices'] = $this->escuela_model->get_indices($escuela->id);
		$this->load->model('usuarios_model');
		$data['usuarios'] = $this->usuarios_model->usuarios_escuela($escuela->id);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$this->load_template('escritorio/escritorio_escuela', $data);
	}

	public function anuncios($usuario_id = NULL, $escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $usuario_id == NULL || !ctype_digit($usuario_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (!empty($escuela_id)) {
			$this->load->model('escuela_model');
			$escuela = $this->escuela_model->get_one($this->rol->entidad_id);
			if (empty($escuela)) {
				show_error('No se encontró la escuela', 500, 'Registro no encontrado');
			}
			if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
				show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
			}
			$data['escuela'] = $escuela;
		}

		$data['anuncios'] = $this->db->select('a.id, a.fecha, a.texto, a.titulo')
				->from('anuncio a')
				->join('anuncio_usuario au', "au.anuncio_id=a.id AND au.usuario_id=$this->usuario", 'left')
				->where('au.id IS NULL')
				->order_by('a.fecha', 'DESC')
				->get()->result();
		$data['title'] = TITLE . ' - Ver anuncios';
		$this->load_template('escuela/escuela_anuncios', $data);
	}

	public function alumnos_inasistencias($id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}

		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->escuela_model->fields, $escuela, TRUE);

		$this->load->model('caracteristica_escuela_model');
		$fields_tipos = $this->caracteristica_escuela_model->get_fields($escuela->nivel_id, $escuela->id, TRUE);
		foreach ($fields_tipos as $tipo => $fields) {
			$data['fields_tipos'][$tipo] = $this->build_fields($fields);
		}

		$this->load->model('escuela_autoridad_model');
		$this->load->model('calendario_model');
		$this->load->model('division_model');
		$this->load->model('division_inasistencia_model');

		$divisiones_escuela = $this->division_inasistencia_model->get_divisiones_escuela($escuela->id, $ciclo_lectivo);
		$division_mes_inasistencia = array();
		$divisiones = $this->division_inasistencia_model->get_divisiones_inasistencias($escuela->id, $ciclo_lectivo);
		foreach ($divisiones as $division) {
			$division_mes_inasistencia[$division->nombre_periodo][$division->id][$division->periodo][$division->mes] = $division;
			if (!isset($calendarios[$division->calendario_id])) {
				$calendarios[$division->calendario_id] = $this->calendario_model->get_periodos($division->calendario_id, $ciclo_lectivo);
			}
		}
		$data['division_mes_inasistencia'] = $division_mes_inasistencia;
		$data['calendarios'] = $calendarios;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['divisiones_escuela'] = $divisiones_escuela;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'escritorio' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Asistencia de alumnos';
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('escuela/escuela_alumnos_inasistencias', $data);
	}

	public function cambiar_cl_alumnos($escuela_id = NULL, $ciclo_lectivo = NULL) {
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
		redirect("escuela/alumnos_inasistencias/$escuela_id/$ciclo_lectivo", 'refresh');
	}

	public function imprimir_asistencia_mensual($id = NULL, $ciclo_lectivo = NULL, $mes = NULL, $periodo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo) || $mes == NULL || !ctype_digit($mes) || $periodo == NULL || !ctype_digit($periodo)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('escuela_autoridad_model');
		$this->load->model('calendario_model');
		$this->load->model('division_model');
		$this->load->model('division_inasistencia_model');
		$this->load->model('alumno_inasistencia_model');
		$this->load->model('alumno_inasistencia_model');

		$calendarios = array();
		$alumnos = array();
		$divisiones = $this->division_inasistencia_model->get_divisiones_inasistencias_mensual($escuela->id, $ciclo_lectivo, $mes);
		foreach ($divisiones as $division) {
			$calendarios[$division->calendario_id] = $this->calendario_model->get_periodos($division->calendario_id, $ciclo_lectivo);
			$alumnos[$division->id] = $this->alumno_inasistencia_model->get_alumnos_division($division->id, $ciclo_lectivo . $mes, 1);
		}

		$data['alumnos'] = $alumnos;
		$data['periodo'] = $periodo;
		$data['escuela'] = $escuela;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['mes'] = $mes;
		$data['divisiones_escuela'] = $divisiones;
		$data['calendarios'] = $calendarios;

		$content = $this->load->view('escuela/escuela_alumnos_inasistencias_pdf', $data, TRUE);
		$this->load->helper('mpdf');
		$watermark = '';
		exportarMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Resumen general de asistencia de alumnos', 'Resumen general de asistencias del mes de ' . substr($this->nombres_meses[$mes], 0) . ': Alumnos de la escuela ' . $escuela->nombre_largo, '|{PAGENO} de {nb}|' .' ('. date('d/m/Y H:i:s').' Hs.)', 'LEGAL-L', $watermark, 'I', FALSE, FALSE);
	}
}
/* End of file Escuela.php */
/* Location: ./application/controllers/Escuela.php */