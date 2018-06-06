<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Situacion_revista extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('situacion_revista_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'par/situacion_revista';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 68),
				array('label' => 'Tipo planilla', 'data' => 'planilla_tipo', 'width' => 20),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'situacion_revista_table',
			'source_url' => 'situacion_revista/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Situaciones de revista';
		$this->load_template('situacion_revista/situacion_revista_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('situacion_revista.id, situacion_revista.descripcion, planilla_tipo.descripcion as planilla_tipo')
			->unset_column('id')
			->from('situacion_revista')
			->join('planilla_tipo', 'planilla_tipo.id = situacion_revista.planilla_tipo_id', 'left')
			->add_column('edit', '<a href="situacion_revista/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="situacion_revista/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="situacion_revista/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('planilla_tipo_model');
		$this->array_planilla_tipo_control = $array_planilla_tipo = $this->get_array('planilla_tipo', 'descripcion', 'id', null, array('' => '-- Seleccionar tipo de planilla --'));

		$this->set_model_validation_rules($this->situacion_revista_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->situacion_revista_model->create(array(
					'descripcion' => $this->input->post('descripcion'),
					'planilla_tipo_id' => $this->input->post('planilla_tipo')
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->situacion_revista_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->situacion_revista_model->get_error());
				}
				redirect('situacion_revista/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('situacion_revista/listar', 'refresh');
			}
		}

		$this->situacion_revista_model->fields['planilla_tipo']['array'] = $array_planilla_tipo;
		$data['fields'] = $this->build_fields($this->situacion_revista_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar situación de revista';
		$this->load->view('situacion_revista/situacion_revista_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$situacion_revista = $this->situacion_revista_model->get(array('id' => $id));
		if (empty($situacion_revista)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->load->model('planilla_tipo_model');
		$this->array_planilla_tipo_control = $array_planilla_tipo = $this->get_array('planilla_tipo', 'descripcion', 'id', null, array('' => '-- Seleccionar tipo de planilla --'));

		$this->set_model_validation_rules($this->situacion_revista_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->situacion_revista_model->update(array(
					'id' => $this->input->post('id'),
					'descripcion' => $this->input->post('descripcion'),
					'planilla_tipo_id' => $this->input->post('planilla_tipo')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->situacion_revista_model->get_msg());
					redirect('situacion_revista/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('situacion_revista/listar', 'refresh');
			}
		}

		$this->situacion_revista_model->fields['planilla_tipo']['array'] = $array_planilla_tipo;

		$data['fields'] = $this->build_fields($this->situacion_revista_model->fields, $situacion_revista);

		$data['situacion_revista'] = $situacion_revista;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar situación de revista';
		$this->load->view('situacion_revista/situacion_revista_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$situacion_revista = $this->situacion_revista_model->get(array('id' => $id));
		if (empty($situacion_revista)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->situacion_revista_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->situacion_revista_model->get_msg());
				redirect('situacion_revista/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->situacion_revista_model->fields, $situacion_revista, TRUE);

		$data['situacion_revista'] = $situacion_revista;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar situación de revista';
		$this->load->view('situacion_revista/situacion_revista_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$situacion_revista = $this->situacion_revista_model->get_one($id);
		if (empty($situacion_revista)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->situacion_revista_model->fields, $situacion_revista, TRUE);

		$data['situacion_revista'] = $situacion_revista;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver situación de revista';
		$this->load->view('situacion_revista/situacion_revista_modal_abm', $data);
	}
}
/* End of file Situacion_revista.php */
/* Location: ./application/controllers/Situacion_revista.php */