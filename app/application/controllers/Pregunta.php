<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pregunta extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('pregunta_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI);
		$this->nav_route = 'ayuda/pregunta';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Orden', 'data' => 'orden', 'width' => 8),
				array('label' => 'Pregunta', 'data' => 'pregunta', 'width' => 40),
				array('label' => 'Respuesta', 'data' => 'respuesta', 'width' => 40),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'pregunta_table',
			'source_url' => 'pregunta/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Preguntas';
		$this->load_template('pregunta/pregunta_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('pregunta.id, pregunta.orden, pregunta.pregunta, pregunta.respuesta')
			->unset_column('id')
			->from('pregunta')
			->add_column('edit', '<a href="pregunta/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="pregunta/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="pregunta/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->set_model_validation_rules($this->pregunta_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->pregunta_model->create(array(
					'pregunta' => $this->input->post('pregunta'),
					'respuesta' => $this->input->post('respuesta'),
					'orden' => $this->input->post('orden')
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->pregunta_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->pregunta_model->get_error());
				}
				redirect('pregunta/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('pregunta/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->pregunta_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar pregunta';
		$this->load->view('pregunta/pregunta_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$pregunta = $this->pregunta_model->get(array('id' => $id));
		if (empty($pregunta)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->set_model_validation_rules($this->pregunta_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->pregunta_model->update(array(
					'id' => $this->input->post('id'),
					'pregunta' => $this->input->post('pregunta'),
					'respuesta' => $this->input->post('respuesta'),
					'orden' => $this->input->post('orden')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->pregunta_model->get_msg());
					redirect('pregunta/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('pregunta/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->pregunta_model->fields, $pregunta);

		$data['pregunta'] = $pregunta;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar pregunta';
		$this->load->view('pregunta/pregunta_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$pregunta = $this->pregunta_model->get(array('id' => $id));
		if (empty($pregunta)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->pregunta_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->pregunta_model->get_msg());
				redirect('pregunta/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->pregunta_model->fields, $pregunta, TRUE);

		$data['pregunta'] = $pregunta;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar pregunta';
		$this->load->view('pregunta/pregunta_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$pregunta = $this->pregunta_model->get(array('id' => $id));
		if (empty($pregunta)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->pregunta_model->fields, $pregunta, TRUE);

		$data['pregunta'] = $pregunta;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver pregunta';
		$this->load->view('pregunta/pregunta_modal_abm', $data);
	}
}
/* End of file Pregunta.php */
/* Location: ./application/controllers/Pregunta.php */