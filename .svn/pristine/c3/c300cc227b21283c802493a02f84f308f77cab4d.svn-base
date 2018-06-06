<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Novedad_tipo extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('novedad_tipo_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'admin/novedad_tipo';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Articulo', 'data' => 'articulo', 'width' => 10),
				array('label' => 'Descripción', 'data' => 'descripcion_corta', 'width' => 24),
				array('label' => 'Detalle', 'data' => 'descripcion', 'width' => 24),
				array('label' => 'Concomitante', 'data' => 'concomitante', 'width' => 10),
				array('label' => 'Novedad', 'data' => 'novedad', 'width' => 10),
				array('label' => 'Reemplazo', 'data' => 'reemplazo', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'novedad_tipo_table',
			'source_url' => 'novedad_tipo/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Tipos de novedades';
		$this->load_template('novedad_tipo/novedad_tipo_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select("novedad_tipo.id, CONCAT(novedad_tipo.articulo, '-', novedad_tipo.inciso) as articulo, novedad_tipo.descripcion, novedad_tipo.descripcion_corta, novedad_tipo.concomitante, novedad_tipo.novedad, novedad_tipo.reemplazo")
			->unset_column('id')
			->from('novedad_tipo')
			->add_column('edit', '<a href="novedad_tipo/ver/$1" class="btn btn-xs btn-default" title="Ver"><i class="fa fa-search"></i></a> <a href="novedad_tipo/editar/$1" class="btn btn-xs btn-warning" title="Editar"><i class="fa fa-pencil"></i></a> <a href="novedad_tipo/eliminar/$1" class="btn btn-xs btn-danger" title="Eliminar"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->array_concomitante_control = $this->novedad_tipo_model->fields['concomitante']['array'];
		$this->array_novedad_control = $this->novedad_tipo_model->fields['novedad']['array'];
		$this->array_reemplazo_control = $this->novedad_tipo_model->fields['reemplazo']['array'];
		$this->set_model_validation_rules($this->novedad_tipo_model);
		if ($this->form_validation->run() === TRUE) {
			$trans_ok = TRUE;
			$trans_ok &= $this->novedad_tipo_model->create(array(
				'articulo' => $this->input->post('articulo'),
				'inciso' => $this->input->post('inciso'),
				'descripcion' => $this->input->post('descripcion'),
				'descripcion_corta' => $this->input->post('descripcion_corta'),
				'concomitante' => $this->input->post('concomitante'),
				'novedad' => $this->input->post('novedad'),
				'reemplazo' => $this->input->post('reemplazo')
			));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->novedad_tipo_model->get_msg());
				redirect('novedad_tipo/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->novedad_tipo_model->get_error() ? $this->novedad_tipo_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->novedad_tipo_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = TITLE . ' - Agregar tipo de novedad';
		$this->load_template('novedad_tipo/novedad_tipo_abm', $data);
	}

	public function editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tipo_novedad = $this->novedad_tipo_model->get(array('id' => $id));
		if (empty($tipo_novedad)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$this->array_concomitante_control = $this->novedad_tipo_model->fields['concomitante']['array'];
		$this->array_novedad_control = $this->novedad_tipo_model->fields['novedad']['array'];
		$this->array_reemplazo_control = $this->novedad_tipo_model->fields['reemplazo']['array'];
		$this->set_model_validation_rules($this->novedad_tipo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->novedad_tipo_model->update(array(
					'id' => $this->input->post('id'),
					'articulo' => $this->input->post('articulo'),
					'inciso' => $this->input->post('inciso'),
					'descripcion' => $this->input->post('descripcion'),
					'descripcion_corta' => $this->input->post('descripcion_corta'),
					'concomitante' => $this->input->post('concomitante'),
					'novedad' => $this->input->post('novedad'),
					'reemplazo' => $this->input->post('reemplazo')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->novedad_tipo_model->get_msg());
					redirect('novedad_tipo/listar', 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->novedad_tipo_model->get_error() ? $this->novedad_tipo_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->novedad_tipo_model->fields, $tipo_novedad);

		$data['tipo_novedad'] = $tipo_novedad;

		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar tipo de novedad';
		$this->load_template('novedad_tipo/novedad_tipo_abm', $data);
	}

	public function eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tipo_novedad = $this->novedad_tipo_model->get(array('id' => $id));
		if (empty($tipo_novedad)) {
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->novedad_tipo_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->novedad_tipo_model->get_msg());
				redirect('novedad_tipo/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->novedad_tipo_model->get_error() ? $this->novedad_tipo_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->novedad_tipo_model->fields, $tipo_novedad, TRUE);

		$data['tipo_novedad'] = $tipo_novedad;

		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = TITLE . ' - Eliminar tipo de novedad';
		$this->load_template('novedad_tipo/novedad_tipo_abm', $data);
	}

	public function ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tipo_novedad = $this->novedad_tipo_model->get(array('id' => $id));
		if (empty($tipo_novedad)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$data['error'] = $this->session->flashdata('error');

		$data['fields'] = $this->build_fields($this->novedad_tipo_model->fields, $tipo_novedad, TRUE);

		$data['tipo_novedad'] = $tipo_novedad;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver tipo de novedad';
		$this->load_template('novedad_tipo/novedad_tipo_abm', $data);
	}
}
/* End of file Novedad_tipo.php */
/* Location: ./application/controllers/Novedad_tipo.php */