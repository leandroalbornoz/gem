<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escuela_grupo extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_grupo_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'par/escuela_grupo';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$tableData = array(
			'columns' => array(
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 90),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'escuela_grupo_table',
			'source_url' => 'escuela_grupo/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Grupos de Escuelas';
		$this->load_template('escuela_grupo/escuela_grupo_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('escuela_grupo.id, escuela_grupo.descripcion')
			->unset_column('id')
			->from('escuela_grupo')
			->add_column('edit', '<a href="escuela_grupo/ver_grupo/$1" class="btn btn-xs btn-default" title="Ver"><i class="fa fa-search"></i></a> <a href="escuela_grupo/editar/$1" class="btn btn-xs btn-warning" title="Editar"><i class="fa fa-pencil"></i></a> <a href="escuela_grupo/eliminar/$1" class="btn btn-xs btn-danger" title="Eliminar"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->set_model_validation_rules($this->escuela_grupo_model);
		if ($this->form_validation->run() === TRUE) {
			$trans_ok = TRUE;
			$trans_ok &= $this->escuela_grupo_model->create(array(
				'descripcion' => $this->input->post('descripcion')));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->escuela_grupo_model->get_msg());
				redirect('escuela_grupo/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->escuela_grupo_model->get_error() ? $this->escuela_grupo_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->escuela_grupo_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = TITLE . ' - Agregar grupo de escuela';
		$this->load_template('escuela_grupo/escuela_grupo_abm', $data);
	}

	public function editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela_grupo = $this->escuela_grupo_model->get(array('id' => $id));
		if (empty($escuela_grupo)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$this->set_model_validation_rules($this->escuela_grupo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->escuela_grupo_model->update(array(
					'id' => $this->input->post('id'),
					'descripcion' => $this->input->post('descripcion')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->escuela_grupo_model->get_msg());
					redirect('escuela_grupo/listar', 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->escuela_grupo_model->get_error() ? $this->escuela_grupo_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->escuela_grupo_model->fields, $escuela_grupo);

		$data['escuela_grupo'] = $escuela_grupo;

		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar grupo de escuela';
		$this->load_template('escuela_grupo/escuela_grupo_abm', $data);
	}

	public function eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela_grupo = $this->escuela_grupo_model->get(array('id' => $id));
		if (empty($escuela_grupo)) {
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->escuela_grupo_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->escuela_grupo_model->get_msg());
				redirect('escuela_grupo/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->escuela_grupo_model->get_error() ? $this->escuela_grupo_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->escuela_grupo_model->fields, $escuela_grupo, TRUE);

		$data['escuela_grupo'] = $escuela_grupo;
		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = TITLE . ' - Eliminar grupo de escuela';
		$this->load_template('escuela_grupo/escuela_grupo_abm', $data);
	}

	public function ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela_grupo = $this->escuela_grupo_model->get(array('id' => $id));
		if (empty($escuela_grupo)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$data['error'] = $this->session->flashdata('error');

		$data['fields'] = $this->build_fields($this->escuela_grupo_model->fields, $escuela_grupo, TRUE);

		$data['escuela_grupo'] = $escuela_grupo;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver grupo de escuela';
		$this->load_template('escuela_grupo/escuela_grupo_abm', $data);
	}

	public function ver_grupo($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela_grupo = $this->escuela_grupo_model->get(array('id' => $id));
		if (empty($escuela_grupo)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$this->load->model('escuela_grupo_escuela_model');
		$escuela_grupo_escuela = $this->escuela_grupo_escuela_model->get_escuelas($escuela_grupo->id);

		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['fields'] = $this->build_fields($this->escuela_grupo_model->fields, $escuela_grupo, TRUE);
		$data['escuela_grupo_escuela'] = $escuela_grupo_escuela;
		$data['escuela_grupo'] = $escuela_grupo;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver grupo de escuela';
		$this->load_template('escuela_grupo_escuela/escuela_grupo_escuela_abm', $data);
	}

	public function editar_grupo($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela_grupo = $this->escuela_grupo_model->get(array('id' => $id));
		if (empty($escuela_grupo)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$this->load->model('escuela_grupo_escuela_model');
		$escuela_grupo_escuela = $this->escuela_grupo_escuela_model->get_escuelas($escuela_grupo->id);
		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->escuela_grupo_model->fields, $escuela_grupo, TRUE);
		$data['escuela_grupo'] = $escuela_grupo;
		$data['escuela_grupo_escuela'] = $escuela_grupo_escuela;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar grupo de escuela';
		$this->load_template('escuela_grupo_escuela/escuela_grupo_escuela_abm', $data);
	}

	public function modal_eliminar_escuela($escuela_grupo_escuela_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_grupo_escuela_id == NULL || !ctype_digit($escuela_grupo_escuela_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('escuela_grupo_escuela_model');
		$escuela_grupo_escuela = $this->escuela_grupo_escuela_model->get_one($escuela_grupo_escuela_id);
		if (empty($escuela_grupo_escuela)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		$escuela_grupo = $this->escuela_grupo_model->get_one($escuela_grupo_escuela->escuela_grupo_id);
		if (empty($escuela_grupo)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($escuela_grupo->id !== $this->input->post('escuela_grupo_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			if ($escuela_grupo_escuela_id !== $this->input->post('escuela_grupo_escuela_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->escuela_grupo_escuela_model->delete(
				array('id' => $escuela_grupo_escuela_id));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->escuela_grupo_escuela_model->get_msg());
			} else {
				$this->session->set_flashdata('error', $this->escuela_grupo_escuela_model->get_error());
			}
			redirect("escuela_grupo/ver_grupo/$escuela_grupo_escuela->escuela_grupo_id", 'refresh');
		}
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['escuela_grupo_escuela'] = $escuela_grupo_escuela;
		$data['escuela_grupo'] = $escuela_grupo;
		$data['title'] = 'Quitar Escuela';
		$data['txt_btn'] = 'Quitar';
		$this->load->view('escuela_grupo_escuela/escuela_grupo_escuela_modal_abm', $data);
	}

	public function modal_agregar_escuela($id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$escuela_grupo = $this->escuela_grupo_model->get(array('id' => $id));
		if (empty($escuela_grupo)) {
			$this->modal_error('No se encontró el registro a agregar', 'Registro no encontrado');
			return;
		}
		$this->load->model('escuela_grupo_escuela_model');
		$this->load->model('escuela_model');

		unset($this->escuela_grupo_escuela_model->fields['escuela_grupo']);
		$this->array_escuela_control = $array_escuela = $this->get_array('escuela', 'nombre_largo', 'id', array(
			'sort_by' => 'escuela.numero, escuela.anexo'
			), array('' => '-- Seleccionar escuela --'));

		$this->set_model_validation_rules($this->escuela_grupo_escuela_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($escuela_grupo->id !== $this->input->post('escuela_grupo_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$consulta = $this->escuela_grupo_escuela_model->consulta($escuela_grupo->id, $this->input->post('escuela'));
			if (!empty($consulta)) {
				$errors = 'Esta Escuela ya ha sido cargada a este grupo';
				$this->session->set_flashdata('error', $errors);
				redirect("escuela_grupo/ver_grupo/$escuela_grupo->id", 'refresh');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;

				$trans_ok &= $this->escuela_grupo_escuela_model->create(array(
					'escuela_grupo_id' => $escuela_grupo->id,
					'escuela_id' => $this->input->post('escuela')
					), FALSE);

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->escuela_grupo_escuela_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar.';
					if ($this->escuela_grupo_escuela_model->get_error())
						$errors .= '<br>' . $this->escuela_grupo_escuela_model->get_error();
					$this->session->set_flashdata('error', $errors);
				}
				redirect("escuela_grupo/ver_grupo/$escuela_grupo->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("escuela_grupo/ver_grupo/$escuela_grupo->id", 'refresh');
			}
		}
		$this->escuela_grupo_escuela_model->fields['escuela']['array'] = $array_escuela;
		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->escuela_grupo_escuela_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['message'] = $this->session->flashdata('message');
		$data['escuela_grupo'] = $escuela_grupo;
		$data['title'] = 'Agregar escuela';
		$this->load->view('escuela_grupo_escuela/escuela_grupo_escuela_modal_abm', $data);
	}
}
/* End of file Escuela_grupo.php */
/* Location: ./application/controllers/Escuela_grupo.php */