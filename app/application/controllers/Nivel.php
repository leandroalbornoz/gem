<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Nivel extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('nivel_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'par/nivel';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 44),
				array('label' => 'Linea', 'data' => 'linea', 'width' => 44),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'nivel_table',
			'source_url' => 'nivel/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Niveles';
		$this->load_template('nivel/nivel_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('nivel.id, nivel.descripcion, nivel.linea_id, linea.nombre as linea')
			->unset_column('id')
			->from('nivel')
			->join('linea', 'linea.id = nivel.linea_id', 'left')
			->add_column('edit', '<a href="nivel/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="nivel/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="nivel/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('linea_model');
		$this->array_linea_control = $array_linea = $this->get_array('linea', 'nombre', 'id', null, array('' => '-- Seleccionar linea --'));

		$this->set_model_validation_rules($this->nivel_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->nivel_model->create(array(
					'descripcion' => $this->input->post('descripcion'),
					'linea_id' => $this->input->post('linea')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->nivel_model->get_msg());
				} else {
					$errors = 'Ocurrió un error al intentar agregar.';
					if ($this->nivel_model->get_error())
						$errors .= '<br>' . $this->nivel_model->get_error();
					$this->session->set_flashdata('error', $errors);
				}
				redirect('nivel/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('nivel/listar', 'refresh');
			}
		}
		$this->nivel_model->fields['linea']['array'] = $array_linea;
		$data['fields'] = $this->build_fields($this->nivel_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar nivel';
		$this->load->view('nivel/nivel_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$nivel = $this->nivel_model->get(array('id' => $id));
		if (empty($nivel)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}

		$this->load->model('linea_model');
		$this->array_linea_control = $array_linea = $this->get_array('linea', 'nombre');

		$this->set_model_validation_rules($this->nivel_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->nivel_model->update(array(
					'id' => $this->input->post('id'),
					'descripcion' => $this->input->post('descripcion'),
					'linea_id' => $this->input->post('linea')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->nivel_model->get_msg());
				} else {
					$errors = 'Ocurrió un error al intentar editar.';
					if ($this->nivel_model->get_error())
						$errors .= '<br>' . $this->nivel_model->get_error();
					$this->session->set_flashdata('error', $errors);
				}
				redirect('nivel/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('nivel/listar', 'refresh');
			}
		}
		$this->nivel_model->fields['linea']['array'] = $array_linea;
		$data['fields'] = $this->build_fields($this->nivel_model->fields, $nivel);
		$data['nivel'] = $nivel;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar nivel';
		$this->load->view('nivel/nivel_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$nivel = $this->nivel_model->get_one($id);
		if (empty($nivel)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->nivel_model->delete(array('id' => $this->input->post('id')));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->nivel_model->get_msg());
			} else {
				$errors = 'Ocurrió un error al intentar eliminar.';
				if ($this->nivel_model->get_error())
					$errors .= '<br>' . $this->nivel_model->get_error();
				$this->session->set_flashdata('error', $errors);
			}
			redirect('nivel/listar', 'refresh');
		}
		$data['fields'] = $this->build_fields($this->nivel_model->fields, $nivel, TRUE);
		$data['nivel'] = $nivel;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar nivel';
		$this->load->view('nivel/nivel_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$nivel = $this->nivel_model->get_one($id);
		if (empty($nivel)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->nivel_model->fields, $nivel, TRUE);
		$data['nivel'] = $nivel;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver nivel';
		$this->load->view('nivel/nivel_modal_abm', $data);
	}
}
/* End of file Nivel.php */
/* Location: ./application/controllers/Nivel.php */