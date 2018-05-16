<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Titulo extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('titulos/titulo_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_LINEA, ROL_SUPERVISION, ROL_DOCENTE, ROL_TITULO);
		$this->nav_route = 'titulo/titulo';
		$this->load->model('provincia_model');
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(//@todo arreglar anchos de columnas
				array('label' => 'Nombre', 'data' => 'nombre', 'width' => 24),
				array('label' => 'País Origen', 'data' => 'pais_origen', 'width' => 14),
				array('label' => 'Provincia', 'data' => 'provincia', 'width' => 14),
				array('label' => 'Establecimiento', 'data' => 'titulo_establecimiento', 'width' => 22),
				array('label' => 'Carrera', 'data' => 'titulo_tipo', 'width' => 16),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'titulo_table',
			'source_url' => 'titulos/titulo/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Gestión Escuelas Mendoza - Titulos';
		$this->load_template('titulos/titulo/titulo_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('titulo.id, titulo.nombre, nacionalidad.descripcion as pais_origen,provincia.descripcion as provincia, titulo.titulo_establecimiento_id, titulo.titulo_tipo_id, titulo_establecimiento.descripcion as titulo_establecimiento, titulo_tipo.descripcion as titulo_tipo')
			->unset_column('id')
			->from('titulo')
			->join('titulo_establecimiento', 'titulo_establecimiento.id = titulo.titulo_establecimiento_id', 'left')
			->join('titulo_tipo', 'titulo_tipo.id = titulo.titulo_tipo_id', 'left')
			->join('nacionalidad', 'nacionalidad.id = titulo.pais_origen_id', 'left')
			->join('provincia', 'provincia.id = titulo.provincia_id', 'left')
			->add_column('edit', '<a href="titulos/titulo/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="titulos/titulo/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="titulos/titulo/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('titulos/titulo_establecimiento_model');
		$this->load->model('titulos/titulo_tipo_model');
		$this->array_titulo_establecimiento_control = $array_titulo_establecimiento = $this->get_array('titulo_establecimiento', 'descripcion', 'id', null, array('' => '-- Seleccionar establecimiento --'));
		$this->array_titulo_tipo_control = $array_titulo_tipo = $this->get_array('titulo_tipo', 'descripcion', 'id', null, array('' => '-- Seleccionar tipo de titulo --'));
		$this->load->model('nacionalidad_model');
		$this->array_pais_origen_control = $array_pais_origen = $this->get_array('nacionalidad', 'descripcion', 'id', array(), array('' => '-- Seleccionar País Origen --'));
		$this->array_provincia_control = $array_provincia = $this->get_array('provincia', 'descripcion', 'id', array(), array('' => '-- Seleccionar Provincia --'));
		$this->titulo_model->fields['titulo_establecimiento_val'] = array('label' => 'Otro Establecimiento', 'maxlength' => '50');
		$this->titulo_model->fields['titulo_tipo_val'] = array('label' => 'Otra Carrera', 'maxlength' => '50');
		$this->set_model_validation_rules($this->titulo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				if ($this->input->post('titulo_tipo_val')) {
					$trans_ok&= $this->titulo_tipo_model->create(array(
						'descripcion' => $this->input->post('titulo_tipo_val')), FALSE);
					$titulo_tipo_id = $this->titulo_tipo_model->get_row_id();
				} else {
					$titulo_tipo_id = $this->input->post('titulo_tipo');
				}
				if ($this->input->post('titulo_establecimiento_val')) {
					$trans_ok&= $this->titulo_establecimiento_model->create(array(
						'descripcion' => $this->input->post('titulo_establecimiento_val')), FALSE);
					$titulo_establecimiento_id = $this->titulo_establecimiento_model->get_row_id();
				} else {
					$titulo_establecimiento_id = $this->input->post('titulo_establecimiento');
				}
				$trans_ok&= $this->titulo_model->create(array(
					'nombre' => $this->input->post('nombre'),
					'pais_origen_id' => $this->input->post('pais_origen'),
					'provincia_id' => $this->input->post('provincia'),
					'titulo_establecimiento_id' => $titulo_establecimiento_id,
					'titulo_tipo_id' => $titulo_tipo_id), FALSE);
				$titulo_id = $this->titulo_model->get_row_id();

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->titulo_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar el título.';
					if ($this->titulo_model->get_error())
						$errors .= '<br>' . $this->titulo_model->get_error();
					if ($this->titulo_persona_model->get_error())
						$errors .= '<br>' . $this->titulo_persona_model->get_error();
					if ($this->titulo_establecimiento_model->get_error())
						$errors .= '<br>' . $this->titulo_establecimiento_model->get_error();
					if ($this->titulo_tipo_model->get_error())
						$errors .= '<br>' . $this->titulo_tipo_model->get_error();
					$this->session->set_flashdata('error', $errors);
				}
				redirect('titulos/titulo/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('titulos/titulo/listar', 'refresh');
			}
		}
		$this->titulo_model->fields['titulo_establecimiento']['array'] = $array_titulo_establecimiento;
		$this->titulo_model->fields['titulo_tipo']['array'] = $array_titulo_tipo;
		$this->titulo_model->fields['pais_origen']['array'] = $array_pais_origen;
		$this->titulo_model->fields['pais_origen']['value'] = 1;
		$this->titulo_model->fields['provincia']['array'] = $array_provincia;
		$this->titulo_model->fields['provincia']['value'] = 14;
		$data['fields'] = $this->build_fields($this->titulo_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar titulo';
		$this->load->view('titulos/titulo/titulo_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$titulo = $this->titulo_model->get(array('id' => $id));
		if (empty($titulo)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->load->model('titulos/titulo_establecimiento_model');
		$this->load->model('titulos/titulo_tipo_model');
		$this->array_titulo_establecimiento_control = $array_titulo_establecimiento = $this->get_array('titulo_establecimiento', 'descripcion', 'id', array('id !=' => '1', 'sort_by' => 'descripcion'));
		$this->array_titulo_tipo_control = $array_titulo_tipo = $this->get_array('titulo_tipo', 'descripcion', 'id', array('id !=' => '1', 'sort_by' => 'descripcion'));
		$this->load->model('nacionalidad_model');
		$this->array_pais_origen_control = $array_pais_origen = $this->get_array('nacionalidad', 'descripcion');
		$this->array_provincia_control = $array_provincia = $this->get_array('provincia', 'descripcion');
		$this->set_model_validation_rules($this->titulo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$trans_ok = TRUE;
			$trans_ok&= $this->titulo_model->update(array(
				'id' => $this->input->post('id'),
				'nombre' => $this->input->post('nombre'),
				'pais_origen_id' => $this->input->post('pais_origen'),
				'provincia_id' => $this->input->post('provincia'),
				'titulo_establecimiento_id' => $this->input->post('titulo_establecimiento'),
				'titulo_tipo_id' => $this->input->post('titulo_tipo')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->titulo_model->get_msg());
				redirect('titulos/titulo/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->titulo_model->get_error());
				redirect('titulos/titulo/listar', 'refresh');
			}
		}

		$this->titulo_model->fields['titulo_establecimiento']['array'] = $array_titulo_establecimiento;
		$this->titulo_model->fields['titulo_tipo']['array'] = $array_titulo_tipo;
		$this->titulo_model->fields['pais_origen']['array'] = $array_pais_origen;
		$this->titulo_model->fields['provincia']['array'] = $array_provincia;
		$data['fields'] = $this->build_fields($this->titulo_model->fields, $titulo);
		$data['titulo'] = $titulo;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar titulo';
		$this->load->view('titulos/titulo/titulo_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$titulo = $this->titulo_model->get_one($id);
		if (empty($titulo)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$trans_ok = TRUE;
			$trans_ok&= $this->titulo_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->titulo_model->get_msg());
				redirect('titulos/titulo/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->titulo_model->get_error());
				redirect('titulos/titulo/listar', 'refresh');
			}
		}
		$data['fields'] = $this->build_fields($this->titulo_model->fields, $titulo, TRUE);
		$data['titulo'] = $titulo;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar titulo';
		$this->load->view('titulos/titulo/titulo_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$titulo = $this->titulo_model->get_one($id);
		if (empty($titulo)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}
		$this->load->model('titulos/titulo_establecimiento_model');
		$this->load->model('titulos/titulo_tipo_model');
		$array_titulo_establecimiento = $this->get_array('titulo_establecimiento', 'descripcion');
		$array_titulo_tipo = $this->get_array('titulo_tipo', 'descripcion');
		$this->load->model('nacionalidad_model');
		$this->array_pais_origen_control = $array_pais_origen = $this->get_array('nacionalidad', 'descripcion');
		$this->array_provincia_control = $array_provincia = $this->get_array('provincia', 'descripcion');
		$this->titulo_model->fields['titulo_establecimiento']['array'] = $array_titulo_establecimiento;
		$this->titulo_model->fields['titulo_tipo']['array'] = $array_titulo_tipo;
		$this->titulo_model->fields['pais_origen']['array'] = $array_pais_origen;
		$data['fields'] = $this->build_fields($this->titulo_model->fields, $titulo, TRUE);
		$data['titulo'] = $titulo;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver titulo';
		$this->load->view('titulos/titulo/titulo_modal_abm', $data);
	}

	public function modal_buscar($persona_id = NULL) {
		$this->load->model('persona_model');
		$persona = $this->persona_model->get_one($persona_id);
		if (empty($persona)) {
			show_error('No se encontró una persona', 500, 'Registro no encontrado');
		}
		$model_busqueda = new stdClass();
		$model_busqueda->fields = array(
			'd_nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'minlength' => '3'),
			'd_tipo' => array('label' => 'Carrera', 'maxlength' => '100', 'minlength' => '3'),
			'd_establecimiento' => array('label' => 'Establecimiento', 'maxlength' => '100', 'minlength' => '3'),
			'persona_seleccionada' => array('label' => '', 'type' => 'integer', 'required' => TRUE),
		);
		$this->set_model_validation_rules($model_busqueda);
		if (isset($_POST) && !empty($_POST)) {
			$titulo_id = $this->input->post('titulo_id');
			if ($titulo_id !== '') {
				$this->session->set_flashdata('titulo_id', $titulo_id);
			}
			redirect("titulos/titulo_persona/ver/$persona->id", 'refresh');
		}
		$data['fields'] = $this->build_fields($model_busqueda->fields);
		$data['persona'] = $persona;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Buscar título a agregar';
		$this->load->view('titulos/titulo/titulo_modal_buscar', $data);
	}

	public function modal_agregar_titulo_nuevo($persona_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $persona_id == NULL || !ctype_digit($persona_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('persona_model');
		$this->load->model('titulos/titulo_persona_model');
		$persona = $this->persona_model->get_one($persona_id);
		if (empty($persona)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		$this->load->model('titulos/titulo_establecimiento_model');
		$this->load->model('titulos/titulo_tipo_model');
		$this->array_titulo_establecimiento_control = $array_titulo_establecimiento = $this->get_array('titulo_establecimiento', 'descripcion', 'id', null, array('' => '-- Seleccionar establecimiento --'));
		unset($this->array_titulo_establecimiento_control['']);
		$this->array_titulo_tipo_control = $array_titulo_tipo = $this->get_array('titulo_tipo', 'descripcion', 'id', null, array('' => '-- Seleccionar carrera --'));
		unset($this->array_titulo_tipo_control['']);
		$this->load->model('nacionalidad_model');
		$this->array_pais_origen_control = $array_pais_origen = $this->get_array('nacionalidad', 'descripcion', 'id', array(), array('' => '-- Seleccionar País Origen --'));
		unset($this->array_pais_origen_control['']);
		$this->array_provincia_control = $array_provincia = $this->get_array('provincia', 'descripcion', 'id', array(), array('' => '-- Seleccionar Provincia --'));
		unset($this->array_provincia_control['']);
		$this->array_titulo_control = $array_titulo = $this->get_array('titulo', 'titulo', 'id', array(
			'select' => array('titulo.id', "CONCAT(tt.descripcion, '-', te.descripcion, '-', titulo.nombre) titulo"),
			'join' => array(
				array('titulo_establecimiento te', 'te.id=titulo.titulo_establecimiento_id'),
				array('titulo_tipo tt', 'tt.id=titulo.titulo_tipo_id')
			),
			'sort_by' => 'tt.descripcion'
			), array('' => '-- Seleccionar Título --'));
		//	unset($this->array_titulo_control['']);
		$this->titulo_model->fields['titulo_establecimiento_val'] = array('label' => 'Otro Establecimiento', 'maxlength' => '50');
		$this->titulo_model->fields['titulo_tipo_val'] = array('label' => 'Otra Carrera', 'maxlength' => '50');

		$this->set_model_validation_rules($this->titulo_persona_model);
		$this->set_model_validation_rules($this->titulo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				if ($this->input->post('titulo_tipo_val')) {
					$trans_ok&= $this->titulo_tipo_model->create(array(
						'descripcion' => $this->input->post('titulo_tipo_val')), FALSE);
					$titulo_tipo_id = $this->titulo_tipo_model->get_row_id();
				} else {
					$titulo_tipo_id = $this->input->post('titulo_tipo');
				}
				if ($this->input->post('titulo_establecimiento_val')) {
					$trans_ok&= $this->titulo_establecimiento_model->create(array(
						'descripcion' => $this->input->post('titulo_establecimiento_val')), FALSE);
					$titulo_establecimiento_id = $this->titulo_establecimiento_model->get_row_id();
				} else {
					$titulo_establecimiento_id = $this->input->post('titulo_establecimiento');
				}
				$trans_ok&= $this->titulo_model->create(array(
					'nombre' => $this->input->post('nombre'),
					'pais_origen_id' => $this->input->post('pais_origen'),
					'provincia_id' => $this->input->post('provincia'),
					'titulo_establecimiento_id' => $titulo_establecimiento_id,
					'titulo_tipo_id' => $titulo_tipo_id), FALSE);
				$titulo_id = $this->titulo_model->get_row_id();

				$trans_ok &= $this->titulo_persona_model->create(array(
					'persona_id' => $persona->id,
					'titulo_id' => $titulo_id,
					'fecha_inscripcion' => $this->get_date_sql('fecha_inscripcion'),
					'fecha_egreso' => $this->get_date_sql('fecha_egreso'),
					'serie' => $this->input->post('serie'),
					'numero' => $this->input->post('numero'),
					'numero_titulo' => $this->input->post('numero_titulo'),
					'observaciones' => $this->input->post('observaciones'),), FALSE);
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->titulo_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar el título.';
					if ($this->titulo_model->get_error())
						$errors .= '<br>' . $this->titulo_model->get_error();
					if ($this->titulo_persona_model->get_error())
						$errors .= '<br>' . $this->titulo_persona_model->get_error();
					if ($this->titulo_establecimiento_model->get_error())
						$errors .= '<br>' . $this->titulo_establecimiento_model->get_error();
					if ($this->titulo_tipo_model->get_error())
						$errors .= '<br>' . $this->titulo_tipo_model->get_error();
					$this->session->set_flashdata('error', $errors);
				}
				redirect("titulos/titulo_persona/ver/$persona->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("titulos/titulo_persona/ver/$persona->id", 'refresh');
			}
		}
		$this->titulo_persona_model->fields['titulo']['array'] = $array_titulo;
		$this->titulo_model->fields['titulo_establecimiento']['array'] = $array_titulo_establecimiento;
		$this->titulo_model->fields['titulo_tipo']['array'] = $array_titulo_tipo;
		$this->titulo_model->fields['pais_origen']['array'] = $array_pais_origen;
		$this->titulo_model->fields['pais_origen']['value'] = 1;
		$this->titulo_model->fields['provincia']['array'] = $array_provincia;
		$this->titulo_model->fields['provincia']['value'] = 14;
		$data['fields_tp'] = $this->build_fields($this->titulo_persona_model->fields);
		$data['fields'] = $this->build_fields($this->titulo_model->fields);
		$data['persona'] = $persona;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar nuevo título';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load->view('titulos/titulo/titulo_modal_agregar_nuevo', $data);
	}

	public function modal_agregar_titulo_existente($persona_id = NULL, $titulo_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $persona_id == NULL || !ctype_digit($persona_id) || $titulo_id == NULL || !ctype_digit($titulo_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('persona_model');
		$this->load->model('titulo_persona_model');
		$this->load->model('titulo_model');
		$persona = $this->persona_model->get_one($persona_id);
		if (empty($persona)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		$titulo = $this->titulo_model->get_one($titulo_id);
		if (empty($titulo)) {
			show_error('No se encontró el registro del titulo', 500, 'Registro no encontrado');
		}
		$this->load->model('titulos/titulo_establecimiento_model');
		$this->load->model('titulos/titulo_tipo_model');
		$this->array_titulo_establecimiento_control = $array_titulo_establecimiento = $this->get_array('titulo_establecimiento', 'descripcion', 'id', null, array('' => '-- Seleccionar establecimiento --'));
		unset($this->array_titulo_establecimiento_control['']);
		$this->array_titulo_tipo_control = $array_titulo_tipo = $this->get_array('titulo_tipo', 'descripcion', 'id', null, array('' => '-- Seleccionar carrera --'));
		unset($this->array_titulo_tipo_control['']);
		$this->array_titulo_control = $array_titulo = $this->get_array('titulo', 'titulo', 'id', array(
			'select' => array('titulo.id', "CONCAT(tt.descripcion, '-', te.descripcion, '-', titulo.nombre) titulo"),
			'join' => array(
				array('titulo_establecimiento te', 'te.id=titulo.titulo_establecimiento_id'),
				array('titulo_tipo tt', 'tt.id=titulo.titulo_tipo_id')
			),
			'sort_by' => 'tt.descripcion'
			), array('' => '-- Seleccionar Título --'));
		//unset($this->array_titulo_control['']);
		$this->set_model_validation_rules($this->titulo_persona_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->titulo_persona_model->create(array(
					'persona_id' => $persona->id,
					'titulo_id' => $titulo_id,
					'fecha_inscripcion' => $this->get_date_sql('fecha_inscripcion'),
					'fecha_egreso' => $this->get_date_sql('fecha_egreso'),
					'serie' => $this->input->post('serie'),
					'numero' => $this->input->post('numero'),
					'numero_titulo' => $this->input->post('numero_titulo'),
					'observaciones' => $this->input->post('observaciones')), FALSE);
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->titulo_persona_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar el titulo a la persona.';
					if ($this->titulo_persona_model->get_error())
						$errors .= '<br>' . $this->titulo_persona_model->get_error();
					$this->session->set_flashdata('error', $errors);
				}
				redirect("titulos/titulo_persona/ver/$persona->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("titulos/titulo_persona/ver/$persona->id", 'refresh');
			}
		}
		$this->titulo_persona_model->fields['titulo']['array'] = $array_titulo;
		$this->titulo_model->fields['titulo_establecimiento']['array'] = $array_titulo_establecimiento;
		$this->titulo_model->fields['titulo_tipo']['array'] = $array_titulo_tipo;
		$data['fields_tp'] = $this->build_fields($this->titulo_persona_model->fields);
		$data['fields'] = $this->build_fields($this->titulo_model->fields, $titulo, TRUE);
		$data['titulo'] = $titulo;
		$data['persona'] = $persona;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar nuevo título';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load->view('titulos/titulo/titulo_modal_agregar_nuevo', $data);
	}
}
/* End of file Titulo.php */
	/* Location: ./application/modules/titulos/controllers/Titulo.php */	