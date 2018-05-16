<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reparticion extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('reparticion_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'admin/reparticion';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Jurisdicción', 'data' => 'jurisdiccion', 'width' => 15),
				array('label' => 'Reparticion', 'data' => 'codigo', 'width' => 15),
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 40),
				array('label' => 'Fecha Desde', 'data' => 'fecha_desde', 'render' => 'date', 'width' => 9),
				array('label' => 'Fecha Hasta', 'data' => 'fecha_hasta', 'render' => 'date', 'width' => 9),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'reparticion_table',
			'source_url' => 'reparticion/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Reparticiones';
		$this->load_template('reparticion/reparticion_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('reparticion.id, reparticion.jurisdiccion_id, reparticion.codigo, reparticion.descripcion, reparticion.fecha_desde, reparticion.fecha_hasta, jurisdiccion.codigo as jurisdiccion')
			->unset_column('id')
			->from('reparticion')
			->join('jurisdiccion', 'jurisdiccion.id = reparticion.jurisdiccion_id', 'left')
			->add_column('edit', '<a href="reparticion/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="reparticion/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="reparticion/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('jurisdiccion_model');
		$this->array_jurisdiccion_control = $array_jurisdiccion = $this->get_array('jurisdiccion', 'codigo', 'id', null, array('' => '-- Seleccionar jurisdicción --'));
		$this->set_model_validation_rules($this->reparticion_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->reparticion_model->create(array(
					'jurisdiccion_id' => $this->input->post('jurisdiccion'),
					'codigo' => $this->input->post('codigo'),
					'descripcion' => $this->input->post('descripcion'),
					'fecha_desde' => $this->get_date_sql('fecha_desde'),
					'fecha_hasta' => $this->get_date_sql('fecha_hasta')
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->reparticion_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->reparticion_model->get_error());
				}
				redirect('reparticion/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('reparticion/listar', 'refresh');
			}
		}

		$this->reparticion_model->fields['jurisdiccion']['array'] = $array_jurisdiccion;
		$data['fields'] = $this->build_fields($this->reparticion_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar repartición';
		$this->load->view('reparticion/reparticion_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$reparticion = $this->reparticion_model->get(array('id' => $id));
		if (empty($reparticion)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->load->model('jurisdiccion_model');
		$this->array_jurisdiccion_control = $array_jurisdiccion = $this->get_array('jurisdiccion', 'codigo');
		$this->set_model_validation_rules($this->reparticion_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->reparticion_model->update(array(
					'id' => $this->input->post('id'),
					'jurisdiccion_id' => $this->input->post('jurisdiccion'),
					'codigo' => $this->input->post('codigo'),
					'descripcion' => $this->input->post('descripcion'),
					'fecha_desde' => $this->get_date_sql('fecha_desde'),
					'fecha_hasta' => $this->get_date_sql('fecha_hasta')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->reparticion_model->get_msg());
					redirect('reparticion/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('reparticion/listar', 'refresh');
			}
		}

		$this->reparticion_model->fields['jurisdiccion']['array'] = $array_jurisdiccion;
		$data['fields'] = $this->build_fields($this->reparticion_model->fields, $reparticion);

		$data['reparticion'] = $reparticion;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar repartición';
		$this->load->view('reparticion/reparticion_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$reparticion = $this->reparticion_model->get_one($id);
		if (empty($reparticion)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->reparticion_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->reparticion_model->get_msg());
				redirect('reparticion/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->reparticion_model->fields, $reparticion, TRUE);

		$data['reparticion'] = $reparticion;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar repartición';
		$this->load->view('reparticion/reparticion_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$reparticion = $this->reparticion_model->get_one($id);
		if (empty($reparticion)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->reparticion_model->fields, $reparticion, TRUE);

		$data['reparticion'] = $reparticion;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver repartición';
		$this->load->view('reparticion/reparticion_modal_abm', $data);
	}
}
/* End of file Reparticion.php */
/* Location: ./application/controllers/Reparticion.php */