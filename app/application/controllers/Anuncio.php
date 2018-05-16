<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Anuncio extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('anuncio_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'menu/anuncio';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'N° Anuncio', 'data' => 'id', 'class' => 'dt-body-right', 'width' => 10),
				array('label' => 'Fecha', 'data' => 'fecha', 'render' => 'date', 'width' => 15),
				array('label' => 'Título', 'data' => 'titulo', 'width' => 63),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'anuncio_table',
			'source_url' => 'anuncio/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Anuncios';
		$this->load_template('anuncio/anuncio_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('anuncio.id, anuncio.fecha, anuncio.titulo')
			->unset_column('id')
			->from('anuncio')
			->add_column('edit', '<a href="anuncio/ver/$1" class="btn btn-xs btn-default" title="Ver"><i class="fa fa-search"></i></a> <a href="anuncio/editar/$1" class="btn btn-xs btn-warning" title="Editar"><i class="fa fa-pencil"></i></a> <a href="anuncio/eliminar/$1" class="btn btn-xs btn-danger" title="Eliminar"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->set_model_validation_rules($this->anuncio_model);
		if ($this->form_validation->run() === TRUE) {
			$trans_ok = TRUE;
			$trans_ok &= $this->anuncio_model->create(array(
				'fecha' => $this->get_date_sql('fecha'),
				'titulo' => $this->input->post('titulo'),
				'texto' => $this->input->post('texto')));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->anuncio_model->get_msg());
				redirect('anuncio/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->anuncio_model->get_error() ? $this->anuncio_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->anuncio_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = TITLE . ' - Agregar anuncio';
		$this->load_template('anuncio/anuncio_abm', $data);
	}

	public function editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$anuncio = $this->anuncio_model->get(array('id' => $id));
		if (empty($anuncio)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$this->set_model_validation_rules($this->anuncio_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->anuncio_model->update(array(
					'id' => $this->input->post('id'),
					'fecha' => $this->get_date_sql('fecha'),
					'titulo' => $this->input->post('titulo'),
					'texto' => $this->input->post('texto')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->anuncio_model->get_msg());
					redirect('anuncio/listar', 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->anuncio_model->get_error() ? $this->anuncio_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->anuncio_model->fields, $anuncio);

		$data['anuncio'] = $anuncio;

		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar anuncio';
		$this->load_template('anuncio/anuncio_abm', $data);
	}

	public function eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$anuncio = $this->anuncio_model->get(array('id' => $id));
		if (empty($anuncio)) {
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->anuncio_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->anuncio_model->get_msg());
				redirect('anuncio/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->anuncio_model->get_error() ? $this->anuncio_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->anuncio_model->fields, $anuncio, TRUE);

		$data['anuncio'] = $anuncio;

		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = TITLE . ' - Eliminar anuncio';
		$this->load_template('anuncio/anuncio_abm', $data);
	}

	public function ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$anuncio = $this->anuncio_model->get(array('id' => $id));
		if (empty($anuncio)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$data['error'] = $this->session->flashdata('error');

		$data['fields'] = $this->build_fields($this->anuncio_model->fields, $anuncio, TRUE);

		$data['anuncio'] = $anuncio;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver anuncio';
		$this->load_template('anuncio/anuncio_abm', $data);
	}
}
/* End of file Anuncio.php */
/* Location: ./application/controllers/Anuncio.php */