<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Titulo_persona extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('titulos/titulo_persona_model');
		$this->load->model('persona_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_TITULO);
		$this->nav_route = 'titulo/titulo_persona';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'T.Doc.', 'data' => 'documento_tipo', 'width' => 5),
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10),
				array('label' => 'Nombre', 'data' => 'persona', 'width' => 20),
				array('label' => 'Sexo', 'data' => 'sexo', 'width' => 10),
				array('label' => 'Nacionalidad', 'data' => 'nacionalidad', 'width' => 15),
				array('label' => 'Teléfono', 'data' => 'telefono_fijo', 'width' => 8),
				array('label' => 'Celular', 'data' => 'telefono_movil', 'width' => 10),
				array('label' => 'Email', 'data' => 'email', 'width' => 20),
				array('label' => '', 'data' => 'menu', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(0, 'asc'), array(1, 'asc')),
			'table_id' => 'persona_table',
			'source_url' => 'titulos/titulo_persona/listar_data',
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
		$data['title'] = TITLE . 'Titulos - Personas';
		$this->load_template('titulos/persona/persona_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->fast_mode()
			->select("persona.id, documento_tipo.descripcion_corta as documento_tipo, persona.documento, CONCAT(persona.apellido, ', ', persona.nombre) as persona, sexo.descripcion as sexo, nacionalidad.descripcion as nacionalidad, persona.telefono_fijo, persona.telefono_movil, persona.email")
			->exact_search('documento_tipo.descripcion_corta')
			->exact_search('persona.documento')
			->unset_column('id')
			->from('persona')
			->join('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id')
			->join('sexo', 'sexo.id = persona.sexo_id', 'left')
			->join('nacionalidad', 'nacionalidad.id = persona.nacionalidad_id', 'left');
		if ($this->edicion) {
			$this->datatables->add_column('menu', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="titulos/titulo_persona/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item" href="titulos/titulo_persona/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '</ul></div>', 'id');
		} else {
			$this->datatables->add_column('menu', '<a class="btn btn-xs btn-default" href="titulos/titulo_persona/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id');
		}

		echo $this->datatables->generate();
	}

	public function agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("titulos/titulo_persona/listar");
		}
		$this->load->model('persona_model');
		$this->persona_model->fields = array(
			'documento_tipo' => array('label' => 'Tipo de documento', 'input_type' => 'combo', 'id_name' => 'documento_tipo_id', 'required' => TRUE),
			'documento' => array('label' => 'Documento', 'maxlength' => '100', 'required' => TRUE),
			'apellido' => array('label' => 'Apellido', 'maxlength' => '100', 'required' => TRUE),
			'nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'required' => TRUE),
			'sexo' => array('label' => 'Sexo', 'input_type' => 'combo', 'id_name' => 'sexo_id'),
			'telefono_fijo' => array('label' => 'Teléfono Fijo', 'maxlength' => '40'),
			'telefono_movil' => array('label' => 'Celular', 'maxlength' => '40'),
			'fecha_nacimiento' => array('label' => 'Fecha Nacimiento', 'type' => 'date'),
			'nacionalidad' => array('label' => 'Nacionalidad', 'input_type' => 'combo', 'id_name' => 'nacionalidad_id'),
			'email' => array('label' => 'Email', 'type' => 'email', 'maxlength' => '100'),
		);
		$this->load->model('nacionalidad_model');
		$this->load->model('sexo_model');
		$this->load->model('documento_tipo_model');
		$this->array_documento_tipo_control = $array_documento_tipo = $this->get_array('documento_tipo', 'descripcion_corta', 'id', null, array('' => '-- Seleccionar --'));
		$this->array_nacionalidad_control = $array_nacionalidad = $this->get_array('nacionalidad', 'descripcion', 'id', null, array('' => '-- Seleccionar nacionalidad --'));
		$this->array_sexo_control = $array_sexo = $this->get_array('sexo', 'descripcion', 'id', null, array('' => '-- Seleccionar sexo --'));
		$this->set_model_validation_rules($this->persona_model);
		if ($this->form_validation->run() === TRUE) {
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$trans_ok &= $this->persona_model->create(array(
				'documento_tipo_id' => $this->input->post('documento_tipo'),
				'documento' => $this->input->post('documento'),
				'apellido' => $this->input->post('apellido'),
				'nombre' => $this->input->post('nombre'),
				'sexo_id' => $this->input->post('sexo'),
				'telefono_fijo' => $this->input->post('telefono_fijo'),
				'telefono_movil' => $this->input->post('telefono_movil'),
				'fecha_nacimiento' => $this->get_date_sql('fecha_nacimiento'),
				'nacionalidad_id' => $this->input->post('nacionalidad'),
				'email' => $this->input->post('email')), FALSE);
			$persona_id = $this->persona_model->get_row_id();
			if ($trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->persona_model->get_msg());
				redirect("titulos/titulo_persona/ver/$persona_id", 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->persona_model->get_error() ? $this->persona_model->get_error() : $this->session->flashdata('error')));

		$this->persona_model->fields['documento_tipo']['array'] = $array_documento_tipo;
		$this->persona_model->fields['nacionalidad']['array'] = $array_nacionalidad;
		$this->persona_model->fields['sexo']['array'] = $array_sexo;
		$data['fields'] = $this->build_fields($this->persona_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled', 'titulo' => '');
		$data['title'] = TITLE . ' - Agregar persona';
		$this->load_template('titulos/persona/persona_agregar', $data);
	}

	public function editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("titulos/titulo_persona/ver/$id");
		}

		$persona = $this->persona_model->get(array('id' => $id, 'join' => array(
				array('documento_tipo', 'persona.documento_tipo_id=documento_tipo.id', 'left', array('documento_tipo.descripcion_corta as documento_tipo'))
		)));
		if (empty($persona)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}

		$this->persona_model->fields = array(
			'documento_tipo' => array('label' => 'Tipo de documento', 'readonly' => TRUE),
			'documento' => array('label' => 'Documento', 'readonly' => TRUE),
			'apellido' => array('label' => 'Apellido', 'maxlength' => '100', 'required' => TRUE),
			'nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'required' => TRUE),
			'sexo' => array('label' => 'Sexo', 'input_type' => 'combo', 'id_name' => 'sexo_id'),
			'telefono_fijo' => array('label' => 'Teléfono Fijo', 'maxlength' => '40'),
			'telefono_movil' => array('label' => 'Celular', 'maxlength' => '40'),
			'fecha_nacimiento' => array('label' => 'Fecha Nacimiento', 'type' => 'date'),
			'nacionalidad' => array('label' => 'Nacionalidad', 'input_type' => 'combo', 'id_name' => 'nacionalidad_id'),
			'email' => array('label' => 'Email', 'type' => 'email', 'maxlength' => '100'),
		);
		$this->load->model('nacionalidad_model');
		$this->load->model('sexo_model');
		$this->array_nacionalidad_control = $array_nacionalidad = $this->get_array('nacionalidad', 'descripcion', 'id', null, array('' => '-- Seleccionar nacionalidad --'));
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
					'apellido' => $this->input->post('apellido'),
					'nombre' => $this->input->post('nombre'),
					'sexo_id' => $this->input->post('sexo'),
					'telefono_fijo' => $this->input->post('telefono_fijo'),
					'telefono_movil' => $this->input->post('telefono_movil'),
					'fecha_nacimiento' => $this->get_date_sql('fecha_nacimiento'),
					'nacionalidad_id' => $this->input->post('nacionalidad'),
					'email' => $this->input->post('email')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->persona_model->get_msg());
					redirect("titulos/titulo_persona/ver/$persona->id", 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->persona_model->get_error() ? $this->persona_model->get_error() : $this->session->flashdata('error')));

		$this->persona_model->fields['nacionalidad']['array'] = $array_nacionalidad;
		$this->persona_model->fields['sexo']['array'] = $array_sexo;

		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona);
		$data['txt_btn'] = 'Editar';
		$data['persona'] = $persona;
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '', 'titulo' => '');
		$data['title'] = TITLE . ' - Editar persona';
		$this->load_template('titulo_persona/titulo_persona_ver', $data);
	}

	public function eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("titulo_persona/titulo_persona_ver/$id");
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
				redirect('titulos/titulo_persona/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->persona_model->get_error() ? $this->persona_model->get_error() : $this->session->flashdata('error')));
		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona, TRUE);
		$data['persona'] = $persona;
		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active', 'titulo' => '');
		$data['title'] = TITLE . ' - Eliminar persona';
		$this->load_template('titulos/titulo_persona/titulo_persona_modal_abm', $data);
	}

	public function ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$persona = $this->persona_model->get_one($id);
		if (empty($persona)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$data['titulo_id'] = $this->session->flashdata('titulo_id');
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona, TRUE);
		$this->load->model('titulo_model');
		$titulos = $this->titulo_persona_model->get(array(
			'persona_id' => $id,
			'join' => array(
				array('titulo', 'titulo.id = titulo_persona.titulo_id', 'left', array('titulo.nombre', 'nacionalidad.descripcion as pais_origen', 'provincia.descripcion as provincia')),
				array('persona', 'persona.id = titulo_persona.persona_id', 'left', array('CONCAT(persona.apellido, \', \', persona.nombre) as persona', 'persona.cuil')),
				array('titulo_tipo', 'titulo_tipo.id = titulo.titulo_tipo_id', 'left', array('titulo_tipo.descripcion as tipo')),
				array('titulo_establecimiento', 'titulo_establecimiento.id = titulo.titulo_establecimiento_id', 'left', array('titulo_establecimiento.descripcion as establecimiento')),
				array('nacionalidad', 'nacionalidad.id = titulo.pais_origen_id', 'left'),
				array('provincia', 'provincia.id = titulo.provincia_id', 'left')
			),
		));
		$data['titulos'] = $titulos;
		$data['persona'] = $persona;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '', 'titulo' => '');
		$data['txt_btn'] = 'ver';
		$data['title'] = TITLE . ' - Ver persona';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('titulos/titulo_persona/titulo_persona_ver', $data);
	}

	public function ver_titulo($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$persona = $this->persona_model->get_one($id);
		if (empty($persona)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$this->load->model('titulo_model');
		$titulos = $this->titulo_persona_model->get(array(
			'persona_id' => $id,
			'join' => array(
				array('titulo', 'titulo.id = titulo_persona.titulo_id', 'left', array('titulo.nombre', 'titulo.pais_origen')),
				array('persona', 'persona.id = titulo_persona.persona_id', 'left', array('CONCAT(persona.apellido, \', \', persona.nombre) as persona', 'persona.cuil')),
				array('titulo_tipo', 'titulo_tipo.id = titulo.titulo_tipo_id', 'left', array('titulo_tipo.descripcion as tipo')),
				array('titulo_establecimiento', 'titulo_establecimiento.id = titulo.titulo_establecimiento_id', 'left', array('titulo_establecimiento.descripcion as establecimiento'))
			),
		));
		$tableData = array(
			'columns' => array(//@todo arreglar anchos de columnas
				array('label' => 'Nombre', 'data' => 'nombre', 'width' => 24),
				array('label' => 'País origen', 'data' => 'pais_origen', 'class' => 'dt-body-right', 'width' => 16),
				array('label' => 'Establecimiento', 'data' => 'titulo_establecimiento', 'width' => 24),
				array('label' => 'Carrera', 'data' => 'titulo_tipo', 'width' => 24),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'titulo_table',
			'source_url' => 'titulos/titulo/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['titulos'] = $titulos;
		$data['persona'] = $persona;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['titulo_id'] = $this->session->flashdata('titulo_id');
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => '', 'titulo' => 'active btn-app-zetta-active', 'establecimiento' => '', 'tipo' => '');
		$data['title'] = TITLE . ' - Ver Títulos de Persona';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('titulo_persona/titulo_persona_listar', $data);
	}

	public function modal_agregar_titulo($id) {

		$persona = $this->persona_model->get_one($id);
		if (empty($persona)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$this->load->model('titulo_persona_model');
		$this->load->model('titulo_model');
		$this->titulo_persona_model->fields['persona']['value'] = "$persona->apellido, $persona->nombre";
		$this->array_titulo_control = $array_titulo = $this->get_array('titulo', 'titulo', 'id', array(
			'select' => array('titulo.id', "CONCAT(tt.descripcion, '-', te.descripcion, '-', titulo.nombre) titulo"),
			'join' => array(
				array('titulo_establecimiento te', 'te.id=titulo.titulo_establecimiento_id'),
				array('titulo_tipo tt', 'tt.id=titulo.titulo_tipo_id')
			),
			'sort_by' => 'tt.descripcion'
			), array('' => '-- Seleccionar Título --'));

		$this->set_model_validation_rules($this->titulo_persona_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->titulo_persona_model->create(array(
					'persona_id' => $persona->id,
					'titulo_id' => $this->input->post('titulo'),
					'fecha_inscripcion' => $this->get_date_sql('fecha_inscripcion'),
					'fecha_egreso' => $this->get_date_sql('fecha_egreso'),
					'serie' => $this->input->post('serie'),
					'numero' => $this->input->post('numero'),
					'observaciones' => $this->input->post('observaciones'),));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->titulo_persona_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->titulo_persona_model->get_error());
				}
				redirect("titulos/titulo_persona/ver/$persona->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("titulos/titulo_persona/ver/$persona->id", 'refresh');
			}
		}
		$this->titulo_persona_model->fields['titulo']['array'] = $array_titulo;
		$data['fields'] = $this->build_fields($this->titulo_persona_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar Título';
		$this->load->view('titulo/titulo_modal_agregar', $data);
	}

	public function modal_editar_titulo($titulo_persona_id = NULL) {

		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $titulo_persona_id == NULL || !ctype_digit($titulo_persona_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$titulo_persona = $this->titulo_persona_model->get_one($titulo_persona_id);
		if (empty($titulo_persona)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->load->model('titulo_model');
		$this->array_titulo_control = $array_titulo = $this->get_array('titulo', 'titulo', 'id', array(
			'select' => array('titulo.id', "CONCAT(titulo.nombre, '-', te.descripcion, '-',tt.descripcion ) titulo"),
			'join' => array(
				array('titulo_establecimiento te', 'te.id=titulo.titulo_establecimiento_id'),
				array('titulo_tipo tt', 'tt.id=titulo.titulo_tipo_id')
			),
			'sort_by' => 'tt.descripcion'
			), array('' => '-- Seleccionar Título --'));
		$this->set_model_validation_rules($this->titulo_persona_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				if ($titulo_persona_id !== $this->input->post('id')) {
					show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
				}
				$trans_ok = TRUE;
				$trans_ok &= $this->titulo_persona_model->update(array(
					'id' => $titulo_persona->id,
					'titulo_id' => $this->input->post('titulo'),
					'fecha_inscripcion' => $this->get_date_sql('fecha_inscripcion'),
					'fecha_egreso' => $this->get_date_sql('fecha_egreso'),
					'serie' => $this->input->post('serie'),
					'numero' => $this->input->post('numero'),
					'numero_titulo' => $this->input->post('numero_titulo'),
					'observaciones' => $this->input->post('observaciones'),));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->titulo_persona_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->titulo_persona_model->get_error());
				}
				redirect("titulos/titulo_persona/ver/$titulo_persona->persona_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("titulos/titulo_persona/ver/$titulo_persona->persona_id", 'refresh');
			}
		}
		$this->titulo_persona_model->fields['titulo']['array'] = $array_titulo;
		$data['fields'] = $this->build_fields($this->titulo_persona_model->fields, $titulo_persona);
		$data['titulo_persona'] = $titulo_persona;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar Título de persona';
		$this->load->view('titulo_persona/titulo_persona_modal_abm', $data);
	}

	public function modal_eliminar_titulo($titulo_persona_id = NULL) {
		$titulo_persona = $this->titulo_persona_model->get_one($titulo_persona_id);
		if (empty($titulo_persona)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($titulo_persona_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->titulo_persona_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->titulo_persona_model->get_msg());
			} else {
				$this->session->set_flashdata('error', $this->titulo_persona_model->get_error());
			}
			redirect("titulos/titulo_persona/ver/$titulo_persona->persona_id", 'refresh');
		}
		$data['fields'] = $this->build_fields($this->titulo_persona_model->fields, $titulo_persona, TRUE);
		$data['titulo_persona'] = $titulo_persona;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar título de persona';
		$this->load->view('titulo_persona/titulo_persona_modal_abm', $data);
	}

	public function cargar_establecimiento_modal($id = NULL) {
		$this->load->model('titulos/titulo_establecimiento_model');
		$persona = $this->persona_model->get_one($id);
		if (empty($persona)) {
			$this->modal_error('Error', 'Registro no encontrado');
			return;
		}
		$this->set_model_validation_rules($this->titulo_establecimiento_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok&= $this->titulo_establecimiento_model->create(array(
					'descripcion' => $this->input->post('descripcion')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->titulo_establecimiento_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->titulo_establecimiento_model->get_error());
				}
				redirect("titulos/titulo_persona/ver/$persona->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("titulos/titulo_persona/ver/$persona->id", 'refresh');
			}
		}
		$data['fields'] = $this->build_fields($this->titulo_establecimiento_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar establecimiento';
		$this->load->view('titulos/titulo_establecimiento/titulo_establecimiento_modal_abm', $data);
	}

	public function cargar_tipo_titulo_modal($id = NULL) {
		$this->load->model('titulos/titulo_tipo_model');
		$persona = $this->persona_model->get_one($id);
		if (empty($persona)) {
			$this->modal_error('Error', 'Registro no encontrado');
			return;
		}
		$this->set_model_validation_rules($this->titulo_tipo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok&= $this->titulo_tipo_model->create(array(
					'descripcion' => $this->input->post('descripcion')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->titulo_tipo_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->titulo_tipo_model->get_error());
				}
				redirect("titulos/titulo_persona/ver/$persona->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("titulos/titulo_persona/ver/$persona->id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->titulo_tipo_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar Carrera';
		$this->load->view('titulos/titulo_tipo/titulo_tipo_modal_abm', $data);
	}
}
/* End of file Titulo_persona.php */
/* Location: ./application/modules/titulos/controllers/Titulo_persona.php */