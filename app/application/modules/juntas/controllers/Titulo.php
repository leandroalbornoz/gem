<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Titulo extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('juntas/titulo_model');
		$this->roles_permitidos = array(ROL_JUNTAS, ROL_MODULO);
		$this->nav_route = 'juntas/titulo';
		$this->modulos_permitidos = array(ROL_MODULO_ADMINISTRADOR_DE_JUNTAS);
		if (in_array($this->rol->codigo, array(ROL_JUNTAS))) {
			$this->edicion = FALSE;
		}
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Nombre', 'data' => 'NomTitLon', 'width' => 70),
				array('label' => 'Tipo', 'data' => 'titulo_tipo', 'width' => 10),
				array('label' => 'Clasificación', 'data' => 'titulo_clasificacion', 'width' => 15),
				array('label' => '', 'data' => 'edit', 'width' => 5, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'titulo_table',
			'source_url' => 'juntas/titulo/listar_data',
			'reuse_var' => TRUE,
			'initComplete' => "complete_titulo_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Bono Secundario - Títulos';
		$this->load_template('juntas/titulo/titulo_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables->set_database('bono_secundario');
		$this->datatables
			->select('t.id, t.NomTitLon, tt.descripcion as titulo_tipo, tc.descripcion as titulo_clasificacion')
			->unset_column('id')
			->from('titulo t')
			->join('titulo_tipo tt', 'tt.id = t.titulo_tipo_id', 'left')
			->join('titulo_tipo_clasificacion tc', 'tc.id = t.titulo_tipo_clasificacion_id', 'left');
		$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
			. '<a class="btn btn-xs btn-default" href="juntas/titulo/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>'
			. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
			. '<ul class="dropdown-menu dropdown-menu-right">'
			. '<li><a class="dropdown-item" href="juntas/titulo/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-pencil"></i> Editar</a></li>'
			. '<li><a class="dropdown-item" href="juntas/titulo/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i> Eliminar</a></li>'
			. '</ul></div>', 'id');


		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$model_titulo = new stdClass();
		$model_titulo->fields = array(
			'titulo_tipo' => array('label' => 'Tipo de Título', 'input_type' => 'combo', 'id_name' => 'titulo_tipo', 'required' => TRUE),
			'NomTitLon' => array('label' => 'Título', 'input_type' => 'combo', 'id_name' => 'NomTitLon', 'required' => TRUE, 'class' => 'no-selectize'),
			'titulo_tipo_clasificacion' => array('label' => 'Tipo de Clasificación', 'input_type' => 'combo', 'id_name' => 'titulo_tipo_clasificacion', 'required' => TRUE),
		);

		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('juntas/titulo_tipo_clasificacion_model');
		$this->titulo_tipo_clasificacion_model->set_database($DB1);
		$this->array_titulo_tipo_clasificacion_control = $array_titulo_tipo_clasificacion = $this->get_array('titulo_tipo_clasificacion', 'descripcion', 'id', null, array('' => '-- Seleccionar clasificación --'));
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('juntas/titulo_tipo_model');
		$this->titulo_tipo_model->set_database($DB1);
		$this->array_titulo_tipo_control = $array_titulo_tipo = $this->get_array('titulo_tipo', 'descripcion', 'id', null, array('' => '-- Seleccionar tipo de título --'));
		unset($model_titulo->fields['NomTitLon']['input_type']);
		$this->set_model_validation_rules($model_titulo);
		$model_titulo->fields['NomTitLon']['input_type'] = 'combo';
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->titulo_model->set_database($DB1);

				$trans_ok = TRUE;
				$trans_ok &= $this->titulo_model->create(array(
					'NomTitLon' => $this->input->post('NomTitLon'),
					'titulo_tipo_id' => $this->input->post('titulo_tipo'),
					'titulo_tipo_clasificacion_id' => $this->input->post('titulo_tipo_clasificacion')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->titulo_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->titulo_model->get_error());
				}
				redirect('juntas/titulo/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('juntas/titulo/listar', 'refresh');
			}
		}

		$this->array_NomTitLon_control = $array_NomTitLon = array('' => '');
		$model_titulo->fields['NomTitLon']['array'] = $array_NomTitLon;
		$model_titulo->fields['titulo_tipo']['array'] = $array_titulo_tipo;
		$model_titulo->fields['titulo_tipo_clasificacion']['array'] = $array_titulo_tipo_clasificacion;
		$data['fields'] = $this->build_fields($model_titulo->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar título';
		$this->load->view('juntas/titulo/titulo_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$model_titulo = new stdClass();
		$model_titulo->fields = array(
			'titulo_tipo' => array('label' => 'Tipo de Título', 'disabled' => '1'),
			'NomTitLon' => array('label' => 'Denominación ', 'required' => TRUE),
			'titulo_tipo_clasificacion' => array('label' => 'Tipo de Clasificación', 'input_type' => 'combo', 'id_name' => 'titulo_tipo_clasificacion', 'required' => TRUE, 'disabled' => '1'),
		);

		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('juntas/titulo_tipo_clasificacion_model');
		$this->titulo_tipo_clasificacion_model->set_database($DB1);
		$this->titulo_model->set_database($DB1);
		$titulo = $this->titulo_model->get(array(
			'id' => $id,
			'join' => array(
				array('table' => 'titulo_tipo_clasificacion', 'where' => 'titulo_tipo_clasificacion.id=titulo.titulo_tipo_clasificacion_id', 'type' => 'left', 'columnas' => array('titulo_tipo_clasificacion.descripcion as titulo_tipo_clasificacion')),
				array('table' => 'titulo_tipo', 'where' => 'titulo_tipo.id=titulo.titulo_tipo_id', 'type' => 'left', 'columnas' => array('titulo_tipo.descripcion as titulo_tipo'))
		)));
		if (empty($titulo)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}

		$this->array_titulo_tipo_clasificacion_control = $array_titulo_tipo_clasificacion = $this->get_array('titulo_tipo_clasificacion', 'descripcion');

		$this->set_model_validation_rules($model_titulo);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->titulo_model->update(array(
				'id' => $this->input->post('id'),
				'NomTitLon' => $this->input->post('NomTitLon'),
				'titulo_tipo_clasificacion_id' => $this->input->post('titulo_tipo_clasificacion')
			));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->titulo_model->get_msg());
				redirect('juntas/titulo/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->titulo_model->get_error());
				redirect('juntas/titulo/listar', 'refresh');
			}
		}

		$model_titulo->fields['titulo_tipo_clasificacion']['array'] = $array_titulo_tipo_clasificacion;
		$data['fields'] = $this->build_fields($model_titulo->fields, $titulo);
		$data['titulo'] = $titulo;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar título';
		$this->load->view('juntas/titulo/titulo_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$model_titulo = new stdClass();
		$model_titulo->fields = array(
			'titulo_tipo' => array('label' => 'Tipo de Título', 'input_type' => 'combo', 'id_name' => 'titulo_tipo', 'required' => TRUE),
			'NomTitLon' => array('label' => 'Título', 'required' => TRUE),
			'titulo_tipo_clasificacion' => array('label' => 'Tipo de Clasificación', 'input_type' => 'combo', 'id_name' => 'titulo_tipo_clasificacion', 'required' => TRUE),
		);

		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->titulo_model->set_database($DB1);
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('juntas/titulo_model');
		$titulo = $this->titulo_model->get(array(
			'id' => $id,
			'join' => array(
				array('table' => 'titulo_tipo_clasificacion', 'where' => 'titulo_tipo_clasificacion.id=titulo.titulo_tipo_clasificacion_id', 'type' => 'left', 'columnas' => array('titulo_tipo_clasificacion.descripcion as titulo_tipo_clasificacion')),
				array('table' => 'titulo_tipo', 'where' => 'titulo_tipo.id=titulo.titulo_tipo_id', 'type' => 'left', 'columnas' => array('titulo_tipo.descripcion as titulo_tipo'))
		)));

		if (empty($titulo)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->titulo_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->titulo_model->get_msg());
				redirect('juntas/titulo/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->titulo_model->get_error());
				redirect('juntas/titulo/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($model_titulo->fields, $titulo, TRUE);
		$data['titulo'] = $titulo;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar título';
		$this->load->view('juntas/titulo/titulo_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$model_titulo = new stdClass();
		$model_titulo->fields = array(
			'titulo_tipo' => array('label' => 'Tipo de Título', 'input_type' => 'combo', 'id_name' => 'titulo_tipo', 'required' => TRUE),
			'NomTitLon' => array('label' => 'Denominación ', 'required' => TRUE),
			'titulo_tipo_clasificacion' => array('label' => 'Tipo de Clasificación', 'input_type' => 'combo', 'id_name' => 'titulo_tipo_clasificacion', 'required' => TRUE),
		);

		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('juntas/titulo_tipo_clasificacion_model');
		$this->titulo_tipo_clasificacion_model->set_database($DB1);
		$this->array_titulo_tipo_clasificacion_control = $array_titulo_tipo_clasificacion = $this->get_array('titulo_tipo_clasificacion', 'descripcion');
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('juntas/titulo_tipo_model');
		$this->titulo_tipo_model->set_database($DB1);
		$this->array_titulo_tipo_control = $array_titulo_tipo = $this->get_array('titulo_tipo', 'descripcion', 'id', null, array('' => '-- Seleccionar tipo de título --'));
		$this->titulo_model->set_database($DB1);
		$titulo = $this->titulo_model->get(array(
			'id' => $id,
			'join' => array(
				array('table' => 'titulo_tipo_clasificacion', 'where' => 'titulo_tipo_clasificacion.id=titulo.titulo_tipo_clasificacion_id', 'type' => 'left', 'columnas' => array('titulo_tipo_clasificacion.descripcion as titulo_tipo_clasificacion')),
				array('table' => 'titulo_tipo', 'where' => 'titulo_tipo.id=titulo.titulo_tipo_id', 'type' => 'left', 'columnas' => array('titulo_tipo.descripcion as titulo_tipo'))
		)));

		if (empty($titulo)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}
		$model_titulo->fields['titulo_tipo_clasificacion']['array'] = $array_titulo_tipo_clasificacion;
		$model_titulo->fields['titulo_tipo']['array'] = $array_titulo_tipo;

		$data['fields'] = $this->build_fields($model_titulo->fields, $titulo, TRUE);
		$data['titulo'] = $titulo;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver título';
		$this->load->view('juntas/titulo/titulo_modal_abm', $data);
	}

	public function get_titulo_tipo_clasificacion() {
		if (in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->form_validation->set_rules('titulo_tipo', 'Tipo de titulo', 'required');
			if ($this->form_validation->run() === TRUE) {
				$titulo_tipo = $this->input->post('titulo_tipo');
				$DB1 = $this->load->database('bono_secundario', TRUE);
				$this->load->model('juntas/titulo_tipo_clasificacion_model');
				$this->titulo_tipo_clasificacion_model->set_database($DB1);
				$tipo_clasificacion = $this->titulo_tipo_clasificacion_model->get(array('titulo_tipo_id' => $titulo_tipo));
				if (!empty($tipo_clasificacion)) {
					echo json_encode($tipo_clasificacion);
					return;
				}
			}
			echo json_encode('error');
		} else {
			show_404();
		}
	}
}
/* End of file Titulo.php */
	/* Location: ./application/modules/titulos/controllers/Titulo.php */	