<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Localidad extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('localidad_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'par/localidad';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 44),
				array('label' => 'Departamento', 'data' => 'departamento', 'width' => 44),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'localidad_table',
			'source_url' => 'localidad/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Localidades';
		$this->load_template('localidad/localidad_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('localidad.id, localidad.descripcion, localidad.departamento_id, departamento.descripcion as departamento')
			->unset_column('id')
			->from('localidad')
			->join('departamento', 'departamento.id = localidad.departamento_id', 'left')
			->add_column('edit', '<a href="localidad/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="localidad/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="localidad/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('departamento_model');
		$this->array_departamento_control = $array_departamento = $this->get_array('departamento', 'descripcion', 'id', null, array('' => '-- Seleccionar departamento --'));
		$this->set_model_validation_rules($this->localidad_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->localidad_model->create(array(
					'descripcion' => $this->input->post('descripcion'),
					'departamento_id' => $this->input->post('departamento')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->localidad_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->localidad_model->get_error());
				}
				redirect('localidad/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('localidad/listar', 'refresh');
			}
		}

		$this->localidad_model->fields['departamento']['array'] = $array_departamento;
		$data['fields'] = $this->build_fields($this->localidad_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar localidad';
		$this->load->view('localidad/localidad_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$localidad = $this->localidad_model->get(array('id' => $id));
		if (empty($localidad)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->load->model('departamento_model');
		$this->array_departamento_control = $array_departamento = $this->get_array('departamento', 'descripcion');
		$this->set_model_validation_rules($this->localidad_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->localidad_model->update(array(
					'id' => $this->input->post('id'),
					'descripcion' => $this->input->post('descripcion'),
					'departamento_id' => $this->input->post('departamento')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->localidad_model->get_msg());
					redirect('localidad/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('localidad/listar', 'refresh');
			}
		}

		$this->localidad_model->fields['departamento']['array'] = $array_departamento;
		$data['fields'] = $this->build_fields($this->localidad_model->fields, $localidad);

		$data['localidad'] = $localidad;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar localidad';
		$this->load->view('localidad/localidad_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$localidad = $this->localidad_model->get_one($id);
		if (empty($localidad)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->localidad_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->localidad_model->get_msg());
				redirect('localidad/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->localidad_model->fields, $localidad, TRUE);

		$data['localidad'] = $localidad;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar localidad';
		$this->load->view('localidad/localidad_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$localidad = $this->localidad_model->get_one($id);
		if (empty($localidad)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->localidad_model->fields, $localidad, TRUE);

		$data['localidad'] = $localidad;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver localidad';
		$this->load->view('localidad/localidad_modal_abm', $data);
	}
}
/* End of file Localidad.php */
/* Location: ./application/controllers/Localidad.php */