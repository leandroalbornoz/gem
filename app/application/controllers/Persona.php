<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Persona extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('persona_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_JEFE_LIQUIDACION, ROL_LIQUIDACION, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_PRIVADA, ROL_SEOS);
		$this->roles_admin = array(ROL_ADMIN);
		$this->roles_altas = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ESCUELA_ALUM,ROL_ASISTENCIA_DIVISION));
		if (in_array($this->rol->codigo, array(ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_GRUPO_ESCUELA_CONSULTA))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'admin/persona';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Cuil', 'data' => 'cuil', 'width' => 10),
				array('label' => 'Tipo', 'data' => 'documento_tipo', 'width' => 8),
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10),
				array('label' => 'Nombre', 'data' => 'persona', 'width' => 20),
				array('label' => 'Dirección', 'data' => 'direccion', 'width' => 20),
				array('label' => 'Localidad', 'data' => 'localidad', 'width' => 10),
				array('label' => 'Teléfonos', 'data' => 'telefonos', 'width' => 10),
				array('label' => '', 'data' => 'menu', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'persona_table',
			'source_url' => 'persona/listar_data',
			'reuse_var' => TRUE,
			'initComplete' => "complete_persona_table",
			'pagingType' => 'full',
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Personas';
		$this->load_template('persona/persona_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->fast_mode()
			->select("persona.id, persona.cuil, documento_tipo.descripcion_corta as documento_tipo, persona.documento, CONCAT(persona.apellido, ', ', persona.nombre) as persona, CONCAT(COALESCE(CONCAT(persona.calle, ' '), ''), COALESCE(CONCAT(persona.calle_numero, ' '), ''), COALESCE(CONCAT('Dpto:', persona.departamento, ' '), ''), COALESCE(CONCAT('P:', persona.piso, ' '), ''), COALESCE(CONCAT('B°:', persona.barrio, ' '), ''), COALESCE(CONCAT('M:', persona.manzana, ' '), ''), COALESCE(CONCAT('C:', persona.casa, ' '), '')) as direccion, localidad.descripcion as localidad, CONCAT(persona.telefono_fijo, ' ', persona.telefono_movil) as telefonos")
			->exact_search('persona.cuil')
			->exact_search('persona.documento')
			->unset_column('id')
			->from('persona')
			->join('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id', 'left')
			->join('localidad', 'localidad.id = persona.localidad_id', 'left');
		if ($this->edicion) {
			$this->datatables->add_column('menu', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="persona/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item" href="persona/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '</ul></div>', 'id');
		} else {
			$this->datatables->add_column('menu', '<a class="btn btn-xs btn-default" href="persona/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id');
		}

		echo $this->datatables->generate();
	}

	public function agregar($cuil = '') {
		if (!in_array($this->rol->codigo, $this->roles_altas)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("persona/listar");
		}

		if (!empty($cuil) && strlen($cuil) === 13) {
			$persona_documento = $this->persona_model->get(array('documento_tipo_id' => '1', 'documento' => substr($cuil, 3, 8)));
			if (!empty($persona_documento)) {
				redirect("persona/editar/" . $persona_documento[0]->id . '/' . $cuil);
			}
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
		$this->load->model('prestadora_model');
		$this->load->model('sexo_model');
		$this->array_depto_nacimiento_control = $array_depto_nacimiento = $this->get_array('departamento', 'descripcion', 'id', null, array('' => '-- Seleccionar departamento --'));
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
		$this->array_obra_social_control = $array_obra_social = $this->get_array('obra_social', 'descripcion', 'id', null, array('' => '-- Seleccionar obra social --'));
		$this->array_ocupacion_control = $array_ocupacion = $this->get_array('ocupacion', 'descripcion', 'id', null, array('' => '-- Seleccionar ocupación --'));
		$this->array_prestadora_control = $array_prestadora = $this->get_array('prestadora', 'descripcion', 'id', null, array('' => '-- Seleccionar prestadora --'));
		$this->array_sexo_control = $array_sexo = $this->get_array('sexo', 'descripcion', 'id', null, array('' => '-- Seleccionar sexo --'));
		if (!empty($cuil)) {
			$this->persona_model->fields['cuil']['value'] = $cuil;
		}
		$this->set_model_validation_rules($this->persona_model);
		if ($this->form_validation->run() === TRUE) {
			$trans_ok = TRUE;
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
				'email' => $this->input->post('email'),
				'nacionalidad_id' => $this->input->post('nacionalidad')));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->persona_model->get_msg());
				if (!empty($cuil)) {
					redirect('usuario/permisos', 'refresh');
				} else {
					redirect('persona/listar', 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->persona_model->get_error() ? $this->persona_model->get_error() : $this->session->flashdata('error')));

		$this->persona_model->fields['depto_nacimiento']['array'] = $array_depto_nacimiento;
		$this->persona_model->fields['documento_tipo']['array'] = $array_documento_tipo;
		$this->persona_model->fields['estado_civil']['array'] = $array_estado_civil;
		$this->persona_model->fields['grupo_sanguineo']['array'] = $array_grupo_sanguineo;
		$this->persona_model->fields['localidad']['array'] = $array_localidad;
		$this->persona_model->fields['nacionalidad']['array'] = $array_nacionalidad;
		$this->persona_model->fields['nivel_estudio']['array'] = $array_nivel_estudio;
		$this->persona_model->fields['obra_social']['array'] = $array_obra_social;
		$this->persona_model->fields['ocupacion']['array'] = $array_ocupacion;
		$this->persona_model->fields['prestadora']['array'] = $array_prestadora;
		$this->persona_model->fields['sexo']['array'] = $array_sexo;

		$data['fields'] = $this->build_fields($this->persona_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = TITLE . ' - Agregar persona';
		$this->load_template('persona/persona_abm', $data);
	}

	public function editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("persona/ver/$id");
		}

		$persona = $this->persona_model->get(array('id' => $id, 'join' => array(
				array('documento_tipo', 'persona.documento_tipo_id=documento_tipo.id', 'left', array('documento_tipo.descripcion_corta as documento_tipo'))
		)));
		if (empty($persona)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
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
		$this->load->model('prestadora_model');
		$this->load->model('sexo_model');
		$this->load->model('familia_model');
		$this->load->model('alumno_model');
		$this->load->model('servicio_model');
		$this->load->model('tbcabh_model');
		$this->array_depto_nacimiento_control = $array_depto_nacimiento = $this->get_array('departamento', 'descripcion', 'id', null, array('' => '-- Seleccionar departamento --'));
		if ($persona->documento_tipo_id === '8') {
			$this->array_documento_tipo_control = $array_documento_tipo = $this->get_array('documento_tipo', 'descripcion_corta', 'id', array(''), array('' => '-- Seleccionar --'));
		} else {
			$this->array_documento_tipo_control = $array_documento_tipo = $this->get_array('documento_tipo', 'descripcion_corta', 'id', array('id !=' => 8), array('' => '-- Seleccionar --'));
		}
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

		$this->set_model_validation_rules($this->persona_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->persona_model->update(array(
					'id' => $this->input->post('id'),
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
					'email' => $this->input->post('email'),
					'nacionalidad_id' => $this->input->post('nacionalidad')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->persona_model->get_msg());
					redirect("persona/ver/$persona->id", 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->persona_model->get_error() ? $this->persona_model->get_error() : $this->session->flashdata('error')));

		$this->persona_model->fields['depto_nacimiento']['array'] = $array_depto_nacimiento;
		$this->persona_model->fields['documento_tipo']['array'] = $array_documento_tipo;
		$this->persona_model->fields['estado_civil']['array'] = $array_estado_civil;
		$this->persona_model->fields['grupo_sanguineo']['array'] = $array_grupo_sanguineo;
		$this->persona_model->fields['localidad']['array'] = $array_localidad;
		$this->persona_model->fields['nacionalidad']['array'] = $array_nacionalidad;
		$this->persona_model->fields['nivel_estudio']['array'] = $array_nivel_estudio;
		$this->persona_model->fields['obra_social']['array'] = $array_obra_social;
		$this->persona_model->fields['ocupacion']['array'] = $array_ocupacion;
		$this->persona_model->fields['prestadora']['array'] = $array_prestadora;
		$this->persona_model->fields['sexo']['array'] = $array_sexo;

		$data['servicios'] = $this->servicio_model->get_servicios_persona($persona->id);
		$data['liquidaciones'] = $this->tbcabh_model->get_by_persona($persona->cuil);
		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona);
		$data['parientes'] = $this->familia_model->get_familiares($persona->id);
		$data['alumno'] = $this->alumno_model->get_trayectoria_alumno($persona->id);
		$data['txt_btn'] = 'Editar';
		$data['persona'] = $persona;
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar persona';
		$this->load_template('persona/persona_ver', $data);
	}

	public function eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("persona/ver/$id");
		}

		$persona = $this->persona_model->get_one($id);
		if (empty($persona)) {
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->persona_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->persona_model->get_msg());
				redirect('persona/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->persona_model->get_error() ? $this->persona_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona, TRUE);

		$data['persona'] = $persona;

		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = TITLE . ' - Eliminar persona';
		$this->load_template('persona/persona_abm', $data);
	}

	public function ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$persona = $this->persona_model->get_one($id);
		if (empty($persona)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$this->load->model('familia_model');
		$this->load->model('alumno_model');
		$this->load->model('servicio_model');
		$this->load->model('tbcabh_model');

		$parientes = $this->familia_model->get_familiares($persona->id);
		$hijos = $this->familia_model->get_hijos($persona->id);
		$data['hijos'] = $hijos;
		$data['parientes'] = $parientes;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona, TRUE);
		$data['servicios'] = $this->servicio_model->get_servicios_persona($persona->id);
		$data['liquidaciones'] = $this->tbcabh_model->get_by_persona($persona->cuil);
		$data['alumno'] = $this->alumno_model->get_trayectoria_alumno($persona->id);
		if (ENVIRONMENT !== 'production') {
			$horarios_servicios_db = $this->servicio_model->get_horario_by_persona($persona->id);
			$horarios_servicios = array();
			foreach ($horarios_servicios_db as $hs) {
				if (!isset($horarios_servicios[$hs->servicio_id])) {
					$horarios_servicios[$hs->servicio_id] = $hs;
					$horarios_servicios[$hs->servicio_id]->horarios = array();
				}
				if (!isset($horarios_servicios[$hs->servicio_id]->dias[$hs->dia_id])) {
					$horarios_servicios[$hs->servicio_id]->dias[$hs->dia_id] = $hs;
					$horarios_servicios[$hs->servicio_id]->dias[$hs->dia_id]->horarios = array();
				}
				$horarios_servicios[$hs->servicio_id]->dias[$hs->dia_id]->horarios[] = $hs;
			}
			$data['horarios_servicios'] = $horarios_servicios;
		}
		$data['persona'] = $persona;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['txt_btn'] = 'ver';
		$data['title'] = TITLE . ' - Ver persona';
		$this->load_template('persona/persona_ver', $data);
	}

	public function liquidacion($id = NULL, $AMES_LIQUIDACION = AMES_LIQUIDACION) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_admin)) {
			$this->edicion = FALSE;
		}
		$persona = $this->persona_model->get_one($id);
		if (empty($persona)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$data['error'] = $this->session->flashdata('error');

		$this->load->model('servicio_model');
		$data['servicios'] = $this->servicio_model->get_servicios_persona($persona->id);
		$data['servicios_by_regimen'] = $this->servicio_model->get_servicios_persona_by_regimen($persona->id);
		$this->load->model('tbcabh_model');
		$data['liquidaciones'] = $this->tbcabh_model->get_by_persona($persona->cuil);
		$data['persona'] = $persona;
		$data['AMES_LIQUIDACION'] = $AMES_LIQUIDACION;
		$data['title'] = TITLE . ' - Ver liquidación de persona';
		$this->load_template('persona/persona_liquidacion', $data);
	}

	public function unificar_servicios($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_admin) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$persona = $this->persona_model->get_one($id);
		if (empty($persona)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$asigna_model = (object) array(
				'fields' => array(
					'servicio_1_id' => array('type' => 'integer'),
					'servicio_2_id' => array('type' => 'integer')
				)
		);
		$this->set_model_validation_rules($asigna_model);
		$this->load->model('servicio_model');
		$this->load->model('cargo_model');
		$this->load->model('servicio_novedad_model');
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$errors = '';
				$this->db->trans_begin();
				$servicio_1_id = $this->input->post('servicio_1_id');
				$servicio_2_id = $this->input->post('servicio_2_id');
				$servicio_1 = $this->servicio_model->get(array('id' => $servicio_1_id,
					'join' => array(
						array('cargo', 'cargo.id=servicio.cargo_id', '', array('cargo.carga_horaria', 'cargo.escuela_id')),
						array('regimen', 'regimen.id=cargo.regimen_id AND regimen.planilla_modalidad_id=1', '', array('regimen.puntos')),
				)));
				$servicio_2 = $this->servicio_model->get(array('id' => $servicio_2_id,
					'join' => array(
						array('cargo', 'cargo.id=servicio.cargo_id', '', array('cargo.carga_horaria', 'cargo.escuela_id')),
						array('regimen', 'regimen.id=cargo.regimen_id AND regimen.planilla_modalidad_id=1', '', array('regimen.puntos')),
				)));
				$trans_ok = TRUE;
				if ($servicio_1->escuela_id === $servicio_2->escuela_id && $servicio_1->puntos === $servicio_2->puntos && $servicio_1->carga_horaria === $servicio_2->carga_horaria) {
					$servicios_cargo = $this->servicio_model->get(array('cargo_id' => $servicio_2->cargo_id));
					$trans_ok &= $this->servicio_model->update(array(
						'id' => $servicio_1_id,
						'fecha_alta' => empty($servicio_1->fecha_alta) ? $servicio_2->fecha_alta : $servicio_1->fecha_alta,
						'liquidacion' => $servicio_2->liquidacion,
						'liquidacion_ames' => $servicio_2->liquidacion_ames,
						'liquidacion_reparticion_id' => $servicio_2->liquidacion_reparticion_id,
						'liquidacion_regimen_id' => $servicio_2->liquidacion_regimen_id,
						'liquidacion_carga_horaria' => $servicio_2->liquidacion_carga_horaria,
						'liquidacion_situacion_revista_id' => $servicio_2->liquidacion_situacion_revista_id
						), FALSE);
					$this->load->model('servicio_funcion_model');
					$funciones = $this->servicio_funcion_model->get(array('servicio_id' => $servicio_2_id));
					if (!empty($funciones)) {
						foreach ($funciones as $funcion) {
							$trans_ok &= $this->servicio_funcion_model->delete(array('id' => $funcion->id), FALSE);
						}
					}
					$novedades = $this->servicio_novedad_model->get(array('servicio_id' => $servicio_2_id));
					if (!empty($novedades)) {
						foreach ($novedades as $novedad) {
							$trans_ok &= $this->servicio_novedad_model->delete(array('id' => $novedad->id), FALSE);
						}
					}
					$this->load->model('tbcabh_model');
					$trans_ok &= $this->tbcabh_model->unificar_servicio($servicio_2_id, $servicio_1_id);
					$trans_ok &= $this->servicio_model->delete(array('id' => $servicio_2_id), FALSE);
					if (count($servicios_cargo) === 1) {
						$trans_ok &= $this->cargo_model->delete(array('id' => $servicio_2->cargo_id), FALSE);
					}
				} else {
					$errors .= 'No coincide los puntos del régimen, la escuela o la cantidad de horas';
					$trans_ok = FALSE;
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', 'Servicio unificado correctamente');
				} else {
					$this->db->trans_rollback();
					$errors .= $this->servicio_model->get_error();
					$errors .= $this->cargo_model->get_error();
					$errors .= $this->servicio_novedad_model->get_error();
					$this->session->set_flashdata('error', $errors);
				}
				redirect("persona/unificar_servicios/$id", 'refresh');
			}
		}
		$data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

		$this->load->model('servicio_model');
		$data['servicios'] = $this->servicio_model->get_servicios_persona($persona->id);
		$this->load->model('tbcabh_model');
		$data['liquidaciones'] = $this->tbcabh_model->get_by_persona($persona->cuil);
		$data['persona'] = $persona;
		$data['title'] = TITLE . ' - Unificar servicios de persona';
		$this->load_template('persona/persona_unificar_servicios', $data);
	}
}
/* End of file Persona.php */
/* Location: ./application/controllers/Persona.php */