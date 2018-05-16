<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Provincia extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('provincia_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'par/provincia';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Código', 'data' => 'codigo', 'width' => 20, 'class' => 'dt-body-right'),
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 68),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'provincia_table',
			'source_url' => 'provincia/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Provincias';
		$this->load_template('provincia/provincia_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('provincia.id, provincia.codigo, provincia.descripcion')
			->unset_column('id')
			->from('provincia')
			->add_column('edit', '<a href="provincia/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="provincia/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="provincia/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->set_model_validation_rules($this->provincia_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->provincia_model->create(array(
					'codigo' => $this->input->post('codigo'),
					'descripcion' => $this->input->post('descripcion')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->provincia_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->provincia_model->get_error());
				}
				redirect('provincia/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('provincia/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->provincia_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar provincia';
		$this->load->view('provincia/provincia_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$provincia = $this->provincia_model->get(array('id' => $id));
		if (empty($provincia)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->set_model_validation_rules($this->provincia_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->provincia_model->update(array(
					'id' => $this->input->post('id'),
					'codigo' => $this->input->post('codigo'),
					'descripcion' => $this->input->post('descripcion')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->provincia_model->get_msg());
					redirect('provincia/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('provincia/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->provincia_model->fields, $provincia);

		$data['provincia'] = $provincia;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar provincia';
		$this->load->view('provincia/provincia_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$provincia = $this->provincia_model->get(array('id' => $id));
		if (empty($provincia)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->provincia_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->provincia_model->get_msg());
				redirect('provincia/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->provincia_model->fields, $provincia, TRUE);

		$data['provincia'] = $provincia;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar provincia';
		$this->load->view('provincia/provincia_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$provincia = $this->provincia_model->get(array('id' => $id));
		if (empty($provincia)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->provincia_model->fields, $provincia, TRUE);

		$data['provincia'] = $provincia;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver provincia';
		$this->load->view('provincia/provincia_modal_abm', $data);
	}
}
/* End of file Provincia.php */
/* Location: ./application/controllers/Provincia.php */