<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Estado_civil extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('estado_civil_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'par/estado_civil';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 88),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'estado_civil_table',
			'source_url' => 'estado_civil/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Estados civiles';
		$this->load_template('estado_civil/estado_civil_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('estado_civil.id, estado_civil.descripcion')
			->unset_column('id')
			->from('estado_civil')
			->add_column('edit', '<a href="estado_civil/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="estado_civil/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="estado_civil/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->set_model_validation_rules($this->estado_civil_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->estado_civil_model->create(array(
					'descripcion' => $this->input->post('descripcion')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->estado_civil_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->estado_civil_model->get_error());
				}
				redirect('estado_civil/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('estado_civil/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->estado_civil_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar estado civil';
		$this->load->view('estado_civil/estado_civil_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$estado_civil = $this->estado_civil_model->get(array('id' => $id));
		if (empty($estado_civil)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->set_model_validation_rules($this->estado_civil_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->estado_civil_model->update(array(
					'id' => $this->input->post('id'),
					'descripcion' => $this->input->post('descripcion')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->estado_civil_model->get_msg());
					redirect('estado_civil/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('estado_civil/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->estado_civil_model->fields, $estado_civil);

		$data['estado_civil'] = $estado_civil;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar estado civil';
		$this->load->view('estado_civil/estado_civil_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$estado_civil = $this->estado_civil_model->get(array('id' => $id));
		if (empty($estado_civil)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->estado_civil_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->estado_civil_model->get_msg());
				redirect('estado_civil/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->estado_civil_model->fields, $estado_civil, TRUE);

		$data['estado_civil'] = $estado_civil;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar estado civil';
		$this->load->view('estado_civil/estado_civil_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$estado_civil = $this->estado_civil_model->get(array('id' => $id));
		if (empty($estado_civil)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->estado_civil_model->fields, $estado_civil, TRUE);

		$data['estado_civil'] = $estado_civil;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver estado civil';
		$this->load->view('estado_civil/estado_civil_modal_abm', $data);
	}
}
/* End of file Estado_civil.php */
/* Location: ./application/controllers/Estado_civil.php */