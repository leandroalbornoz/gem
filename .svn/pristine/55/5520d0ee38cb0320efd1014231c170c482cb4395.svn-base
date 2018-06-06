<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Departamento extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('departamento_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'par/departamento';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 30),
				array('label' => 'Provincia', 'data' => 'provincia', 'width' => 30),
				array('label' => 'Regional', 'data' => 'regional', 'width' => 30),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'departamento_table',
			'source_url' => 'departamento/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Departamentos';
		$this->load_template('departamento/departamento_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('departamento.id, departamento.descripcion, departamento.provincia_id, departamento.regional_id, provincia.descripcion as provincia, regional.descripcion as regional')
			->unset_column('id')
			->from('departamento')
			->join('provincia', 'provincia.id = departamento.provincia_id', 'left')
			->join('regional', 'regional.id = departamento.regional_id', 'left')
			->add_column('edit', '<a href="departamento/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="departamento/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="departamento/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('provincia_model');
		$this->load->model('regional_model');
		$this->array_provincia_control = $array_provincia = $this->get_array('provincia', 'descripcion', 'id', null, array('' => '-- Seleccionar provincia --'));
		$this->array_regional_control = $array_regional = $this->get_array('regional', 'descripcion', 'id', null, array('' => '-- Seleccionar regional --'));
		$this->set_model_validation_rules($this->departamento_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->departamento_model->create(array(
					'descripcion' => $this->input->post('descripcion'),
					'provincia_id' => $this->input->post('provincia'),
					'regional_id' => $this->input->post('regional')
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->departamento_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->departamento_model->get_error());
				}
				redirect('departamento/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('departamento/listar', 'refresh');
			}
		}

		$this->departamento_model->fields['provincia']['array'] = $array_provincia;
		$this->departamento_model->fields['regional']['array'] = $array_regional;
		$data['fields'] = $this->build_fields($this->departamento_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar departamento';
		$this->load->view('departamento/departamento_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$departamento = $this->departamento_model->get(array('id' => $id));
		if (empty($departamento)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->load->model('provincia_model');
		$this->load->model('regional_model');
		$this->array_provincia_control = $array_provincia = $this->get_array('provincia', 'descripcion');
		$this->array_regional_control = $array_regional = $this->get_array('regional', 'descripcion');
		$this->set_model_validation_rules($this->departamento_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->departamento_model->update(array(
					'id' => $this->input->post('id'),
					'descripcion' => $this->input->post('descripcion'),
					'provincia_id' => $this->input->post('provincia'),
					'regional_id' => $this->input->post('regional')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->departamento_model->get_msg());
					redirect('departamento/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('departamento/listar', 'refresh');
			}
		}

		$this->departamento_model->fields['provincia']['array'] = $array_provincia;
		$this->departamento_model->fields['regional']['array'] = $array_regional;
		$data['fields'] = $this->build_fields($this->departamento_model->fields, $departamento);

		$data['departamento'] = $departamento;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar departamento';
		$this->load->view('departamento/departamento_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$departamento = $this->departamento_model->get_one($id);
		if (empty($departamento)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->departamento_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->departamento_model->get_msg());
				redirect('departamento/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->departamento_model->fields, $departamento, TRUE);

		$data['departamento'] = $departamento;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar departamento';
		$this->load->view('departamento/departamento_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$departamento = $this->departamento_model->get_one($id);
		if (empty($departamento)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->departamento_model->fields, $departamento, TRUE);

		$data['departamento'] = $departamento;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver departamento';
		$this->load->view('departamento/departamento_modal_abm', $data);
	}
}
/* End of file Departamento.php */
/* Location: ./application/controllers/Departamento.php */