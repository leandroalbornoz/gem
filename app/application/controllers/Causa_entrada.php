<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Causa_entrada extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('causa_entrada_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'par/causa_entrada';
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
			'table_id' => 'causa_entrada_table',
			'source_url' => 'causa_entrada/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Causas de entradas';
		$this->load_template('causa_entrada/causa_entrada_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('causa_entrada.id, causa_entrada.descripcion')
			->unset_column('id')
			->from('causa_entrada')
			->add_column('edit', '<a href="causa_entrada/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="causa_entrada/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="causa_entrada/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->set_model_validation_rules($this->causa_entrada_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->causa_entrada_model->create(array(
					'descripcion' => $this->input->post('descripcion')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->causa_entrada_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->causa_entrada_model->get_error());
				}
				redirect('causa_entrada/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('causa_entrada/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->causa_entrada_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar causa de entrada';
		$this->load->view('causa_entrada/causa_entrada_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$causa_entrada = $this->causa_entrada_model->get(array('id' => $id));
		if (empty($causa_entrada)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->set_model_validation_rules($this->causa_entrada_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->causa_entrada_model->update(array(
					'id' => $this->input->post('id'),
					'descripcion' => $this->input->post('descripcion')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->causa_entrada_model->get_msg());
					redirect('causa_entrada/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('causa_entrada/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->causa_entrada_model->fields, $causa_entrada);

		$data['causa_entrada'] = $causa_entrada;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar causa de entrada';
		$this->load->view('causa_entrada/causa_entrada_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$causa_entrada = $this->causa_entrada_model->get(array('id' => $id));
		if (empty($causa_entrada)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->causa_entrada_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->causa_entrada_model->get_msg());
				redirect('causa_entrada/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->causa_entrada_model->fields, $causa_entrada, TRUE);

		$data['causa_entrada'] = $causa_entrada;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar causa de entrada';
		$this->load->view('causa_entrada/causa_entrada_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$causa_entrada = $this->causa_entrada_model->get(array('id' => $id));
		if (empty($causa_entrada)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->causa_entrada_model->fields, $causa_entrada, TRUE);

		$data['causa_entrada'] = $causa_entrada;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver causa de entrada';
		$this->load->view('causa_entrada/causa_entrada_modal_abm', $data);
	}
}
/* End of file Causa_entrada.php */
/* Location: ./application/controllers/Causa_entrada.php */