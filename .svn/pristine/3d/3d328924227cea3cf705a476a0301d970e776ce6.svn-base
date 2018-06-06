<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dia extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('dia_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'par/dia';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Nombre', 'data' => 'nombre', 'width' => 88),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'dia_table',
			'source_url' => 'dia/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Dias';
		$this->load_template('dia/dia_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('dia.id, dia.nombre')
			->unset_column('id')
			->from('dia')
			->add_column('edit', '<a href="dia/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="dia/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="dia/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->set_model_validation_rules($this->dia_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->dia_model->create(array(
					'nombre' => $this->input->post('nombre')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->dia_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->dia_model->get_error());
				}
				redirect('dia/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('dia/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->dia_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar día';
		$this->load->view('dia/dia_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$dia = $this->dia_model->get(array('id' => $id));
		if (empty($dia)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->set_model_validation_rules($this->dia_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->dia_model->update(array(
					'id' => $this->input->post('id'),
					'nombre' => $this->input->post('nombre')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->dia_model->get_msg());
					redirect('dia/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('dia/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->dia_model->fields, $dia);

		$data['dia'] = $dia;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar día';
		$this->load->view('dia/dia_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$dia = $this->dia_model->get(array('id' => $id));
		if (empty($dia)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->dia_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->dia_model->get_msg());
				redirect('dia/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->dia_model->fields, $dia, TRUE);

		$data['dia'] = $dia;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar día';
		$this->load->view('dia/dia_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$dia = $this->dia_model->get(array('id' => $id));
		if (empty($dia)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->dia_model->fields, $dia, TRUE);

		$data['dia'] = $dia;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver día';
		$this->load->view('dia/dia_modal_abm', $data);
	}
}
/* End of file Dia.php */
/* Location: ./application/controllers/Dia.php */