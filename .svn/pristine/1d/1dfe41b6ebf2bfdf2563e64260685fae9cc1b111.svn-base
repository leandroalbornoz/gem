<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Causa_salida extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('causa_salida_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'par/causa_salida';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 68),
				array('label' => 'Salida escuela', 'data' => 'salida_escuela', 'width' => 20),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'causa_salida_table',
			'source_url' => 'causa_salida/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Causas de salidas';
		$this->load_template('causa_salida/causa_salida_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('causa_salida.id, causa_salida.descripcion, causa_salida.salida_escuela')
			->unset_column('id')
			->from('causa_salida')
			->add_column('edit', '<a href="causa_salida/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="causa_salida/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="causa_salida/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->array_salida_escuela_control = $this->causa_salida_model->fields['salida_escuela']['array'];
		$this->set_model_validation_rules($this->causa_salida_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->causa_salida_model->create(array(
					'descripcion' => $this->input->post('descripcion'),
					'salida_escuela' => $this->input->post('salida_escuela')
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->causa_salida_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->causa_salida_model->get_error());
				}
				redirect('causa_salida/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('causa_salida/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->causa_salida_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar causa de salida';
		$this->load->view('causa_salida/causa_salida_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$causa_salida = $this->causa_salida_model->get(array('id' => $id));
		if (empty($causa_salida)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}

		$this->array_salida_escuela_control = $this->causa_salida_model->fields['salida_escuela']['array'];
		$this->set_model_validation_rules($this->causa_salida_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->causa_salida_model->update(array(
					'id' => $this->input->post('id'),
					'descripcion' => $this->input->post('descripcion'),
					'salida_escuela' => $this->input->post('salida_escuela')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->causa_salida_model->get_msg());
					redirect('causa_salida/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('causa_salida/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->causa_salida_model->fields, $causa_salida);

		$data['causa_salida'] = $causa_salida;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar causa de salida';
		$this->load->view('causa_salida/causa_salida_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$causa_salida = $this->causa_salida_model->get(array('id' => $id));
		if (empty($causa_salida)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->causa_salida_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->causa_salida_model->get_msg());
				redirect('causa_salida/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->causa_salida_model->fields, $causa_salida, TRUE);

		$data['causa_salida'] = $causa_salida;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar causa de salida';
		$this->load->view('causa_salida/causa_salida_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$causa_salida = $this->causa_salida_model->get(array('id' => $id));
		if (empty($causa_salida)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->causa_salida_model->fields, $causa_salida, TRUE);

		$data['causa_salida'] = $causa_salida;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver causa de salida';
		$this->load->view('causa_salida/causa_salida_modal_abm', $data);
	}
}
/* End of file Causa_salida.php */
/* Location: ./application/controllers/Causa_salida.php */