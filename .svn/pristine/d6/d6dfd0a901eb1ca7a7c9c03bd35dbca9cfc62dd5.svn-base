<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Funcion extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('funcion_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI);
		$this->nav_route = 'par/funcion';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 38),
				array('label' => 'S.Tarea', 'data' => 'usa_tarea', 'width' => 10),
				array('label' => 'S.Carga', 'data' => 'usa_carga_horaria', 'width' => 10),
				array('label' => 'S.Destino', 'data' => 'usa_destino', 'width' => 10),
				array('label' => 'S.Norma', 'data' => 'usa_norma', 'width' => 10),
				array('label' => 'Horario', 'data' => 'horario_propio', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'funcion_table',
			'source_url' => 'funcion/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Funciones';
		$this->load_template('funcion/funcion_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('funcion.id, funcion.descripcion, funcion.usa_tarea, funcion.usa_carga_horaria, funcion.usa_destino, funcion.usa_norma, funcion.horario_propio')
			->unset_column('id')
			->from('funcion')
			->where('funcion.planilla_modalidad_id', 1)
			->add_column('edit', '<a href="funcion/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="funcion/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="funcion/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->array_usa_tarea_control = $this->funcion_model->fields['usa_tarea']['array'];
		$this->array_usa_carga_horaria_control = $this->funcion_model->fields['usa_carga_horaria']['array'];
		$this->array_usa_destino_control = $this->funcion_model->fields['usa_destino']['array'];
		$this->array_usa_norma_control = $this->funcion_model->fields['usa_norma']['array'];
		$this->array_horario_propio_control = $this->funcion_model->fields['horario_propio']['array'];
		$this->set_model_validation_rules($this->funcion_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->funcion_model->create(array(
					'descripcion' => $this->input->post('descripcion'),
					'usa_tarea' => $this->input->post('usa_tarea'),
					'usa_carga_horaria' => $this->input->post('usa_carga_horaria'),
					'usa_destino' => $this->input->post('usa_destino'),
					'usa_norma' => $this->input->post('usa_norma'),
					'horario_propio' => $this->input->post('horario_propio'),
					'planilla_modalidad_id' => 1
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->funcion_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->funcion_model->get_error());
				}
				redirect('funcion/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('funcion/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->funcion_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar función';
		$this->load->view('funcion/funcion_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$funcion = $this->funcion_model->get(array('id' => $id));
		if (empty($funcion)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->array_usa_tarea_control = $this->funcion_model->fields['usa_tarea']['array'];
		$this->array_usa_carga_horaria_control = $this->funcion_model->fields['usa_carga_horaria']['array'];
		$this->array_usa_destino_control = $this->funcion_model->fields['usa_destino']['array'];
		$this->array_usa_norma_control = $this->funcion_model->fields['usa_norma']['array'];
		$this->array_horario_propio_control = $this->funcion_model->fields['horario_propio']['array'];
		$this->set_model_validation_rules($this->funcion_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->funcion_model->update(array(
					'id' => $this->input->post('id'),
					'descripcion' => $this->input->post('descripcion'),
					'usa_tarea' => $this->input->post('usa_tarea'),
					'usa_carga_horaria' => $this->input->post('usa_carga_horaria'),
					'usa_destino' => $this->input->post('usa_destino'),
					'usa_norma' => $this->input->post('usa_norma'),
					'horario_propio' => $this->input->post('horario_propio')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->funcion_model->get_msg());
					redirect('funcion/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('funcion/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->funcion_model->fields, $funcion);

		$data['funcion'] = $funcion;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar función';
		$this->load->view('funcion/funcion_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$funcion = $this->funcion_model->get(array('id' => $id));
		if (empty($funcion)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->funcion_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->funcion_model->get_msg());
				redirect('funcion/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->funcion_model->fields, $funcion, TRUE);

		$data['funcion'] = $funcion;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar función';
		$this->load->view('funcion/funcion_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$funcion = $this->funcion_model->get(array('id' => $id));
		if (empty($funcion)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->funcion_model->fields, $funcion, TRUE);

		$data['funcion'] = $funcion;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver función';
		$this->load->view('funcion/funcion_modal_abm', $data);
	}
}
/* End of file Funcion.php */
/* Location: ./application/controllers/Funcion.php */