<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Regimen_lista extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('regimen_lista_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'admin/regimen_lista';
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
			'table_id' => 'regimen_lista_table',
			'source_url' => 'regimen_lista/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Regímenes';
		$this->load_template('regimen_lista/regimen_lista_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('regimen_lista.id, regimen_lista.descripcion')
			->unset_column('id')
			->from('regimen_lista')
			->add_column('edit', '<a href="regimen_lista/ver/$1" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="regimen_lista/editar/$1" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="regimen_lista/eliminar/$1" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('regimen_model');
		$this->array_regimenes_control = $array_regimenes = $this->get_array('regimen', 'regimen', 'id', array(
			'select' => array('regimen.id', 'CONCAT(regimen.codigo, \' \',regimen.descripcion) as regimen'),
			'where' => array('dependencia_id IS NULL'),
			'sort_by' => 'regimen.codigo'
			), array('' => '-- Sin regímenes --'));

		$this->set_model_validation_rules($this->regimen_lista_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->load->model('regimen_lista_regimen_model');
				$this->db->trans_begin();
				$trans_ok = TRUE;

				$trans_ok &= $this->regimen_lista_model->create(array(
					'descripcion' => $this->input->post('descripcion')
					), FALSE);
				$regimen_lista_id = $this->regimen_lista_model->get_row_id();

				if ($trans_ok) {
					$regimenes = $this->input->post('regimenes');
					if (!empty($regimenes)) {
						foreach ($regimenes as $regimen_id) {
							$this->regimen_lista_regimen_model->create(array(
								'regimen_lista_id' => $regimen_lista_id,
								'regimen_id' => $regimen_id
								), FALSE);
						}
					}
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->regimen_lista_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar eliminar.';
					if ($this->regimen_lista_model->get_error())
						$errors .= '<br>' . $this->regimen_lista_model->get_error();
					if ($this->regimen_lista_regimen_model->get_error())
						$errors .= '<br>' . $this->regimen_lista_regimen_model->get_error();
					$this->session->set_flashdata('error', $errors);
				}
				redirect('regimen_lista/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('regimen_lista/listar', 'refresh');
			}
		}
		$this->regimen_lista_model->fields['regimenes']['array'] = $array_regimenes;
		$data['fields'] = $this->build_fields($this->regimen_lista_model->fields);

		$data['class'] = array('agregar' => 'active disabled btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar lista de regímenes';
		$this->load_template('regimen_lista/regimen_lista_abm', $data);
	}

	public function editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$regimen_lista = $this->regimen_lista_model->get(array('id' => $id));
		if (empty($regimen_lista)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		unset($this->regimen_lista_model->fields['regimenes']);
		$this->load->model('regimen_lista_regimen_model');
		$regimenes_lista = $this->regimen_lista_regimen_model->get(array(
			'regimen_lista_id' => $id,
			'join' => array(
				array('regimen', 'regimen_lista_regimen.regimen_id=regimen.id', 'left', array('CONCAT(regimen.codigo, \' - \', regimen.descripcion) as regimen')),
			)
		));

		$this->set_model_validation_rules($this->regimen_lista_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->regimen_lista_model->update(array(
					'id' => $this->input->post('id'),
					'descripcion' => $this->input->post('descripcion')
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->regimen_lista_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->regimen_lista_model->get_error());
				}
				redirect("regimen_lista/editar/$id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("regimen_lista/editar/$id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->regimen_lista_model->fields, $regimen_lista);
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['regimenes_lista'] = $regimenes_lista;
		$data['regimen_lista'] = $regimen_lista;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar lista de regímenes';
		$this->load_template('regimen_lista/regimen_lista_abm', $data);
	}

	public function eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$regimen_lista = $this->regimen_lista_model->get(array('id' => $id));
		if (empty($regimen_lista)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		unset($this->regimen_lista_model->fields['regimenes']);
		$this->load->model('regimen_lista_regimen_model');
		$regimenes_lista = $this->regimen_lista_regimen_model->get(array(
			'regimen_lista_id' => $id,
			'join' => array(
				array('regimen', 'regimen_lista_regimen.regimen_id=regimen.id', 'left', array('CONCAT(regimen.codigo, \' - \', regimen.descripcion) as regimen')),
			)
		));

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;
			if (!empty($regimenes_lista)) {
				foreach ($regimenes_lista as $regimen) {
					$trans_ok &= $this->regimen_lista_regimen_model->delete(array('id' => $regimen->id), FALSE);
				}
			}
			if ($trans_ok) {
				$trans_ok &= $this->regimen_lista_model->delete(array('id' => $this->input->post('id')), FALSE);
			}
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->regimen_lista_model->get_msg());
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al intentar eliminar.';
				if ($this->regimen_lista_model->get_error())
					$errors .= '<br>' . $this->regimen_lista_model->get_error();
				if ($this->regimen_lista_regimen_model->get_error())
					$errors .= '<br>' . $this->regimen_lista_regimen_model->get_error();
				$this->session->set_flashdata('error', $errors);
			}
			redirect('regimen_lista/listar', 'refresh');
		}

		$data['fields'] = $this->build_fields($this->regimen_lista_model->fields, $regimen_lista, TRUE);

		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['regimenes_lista'] = $regimenes_lista;
		$data['regimen_lista'] = $regimen_lista;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar lista de regímenes';
		$this->load_template('regimen_lista/regimen_lista_abm', $data);
	}

	public function ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$regimen_lista = $this->regimen_lista_model->get(array('id' => $id));
		if (empty($regimen_lista)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		unset($this->regimen_lista_model->fields['regimenes']);
		$this->load->model('regimen_lista_regimen_model');
		$regimenes_lista = $this->regimen_lista_regimen_model->get(array(
			'regimen_lista_id' => $id,
			'join' => array(
				array('regimen', 'regimen_lista_regimen.regimen_id=regimen.id', 'left', array('CONCAT(regimen.codigo, \' - \', regimen.descripcion) as regimen')),
			)
		));

		$data['fields'] = $this->build_fields($this->regimen_lista_model->fields, $regimen_lista, TRUE);

		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['regimenes_lista'] = $regimenes_lista;
		$data['regimen_lista'] = $regimen_lista;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver régimen';
		$this->load_template('regimen_lista/regimen_lista_abm', $data);
	}

	public function modal_quitar_regimen($regimen_lista_regimen_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $regimen_lista_regimen_id == NULL || !ctype_digit($regimen_lista_regimen_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('regimen_lista_regimen_model');
		$regimen_lista_regimen = $this->regimen_lista_regimen_model->get(array(
			'id' => $regimen_lista_regimen_id
		));
		if (empty($regimen_lista_regimen)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($regimen_lista_regimen_id !== $this->input->post('regimen_lista_regimen_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->regimen_lista_regimen_model->delete(array('id' => $this->input->post('regimen_lista_regimen_id')));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->regimen_lista_regimen_model->get_msg());
			} else {
				$this->session->set_flashdata('error', $this->regimen_lista_regimen_model->get_error());
			}
			redirect("regimen_lista/editar/$regimen_lista_regimen->regimen_lista_id", 'refresh');
		}
		$data['regimen_lista_regimen_id'] = $regimen_lista_regimen_id;
		$data['title'] = 'Quitar régimen';
		$data['txt_btn'] = 'Quitar';
		$this->load->view('regimen_lista/regimen_modal_abm', $data);
	}

	public function modal_agregar_regimen($regimen_lista_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $regimen_lista_id == NULL || !ctype_digit($regimen_lista_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$regimen_lista = $this->regimen_lista_model->get(array('id' => $regimen_lista_id));
		if (empty($regimen_lista)) {
			$this->modal_error('No se encontró el registro a agregar', 'Registro no encontrado');
			return;
		}

		$this->load->model('regimen_model');
		unset($this->regimen_lista_model->fields['descripcion']);
		$this->array_regimenes_control = $array_regimenes = $this->get_array('regimen', 'regimen', 'id', array(
			'select' => array('regimen.id', 'CONCAT(regimen.codigo, \' \',regimen.descripcion) as regimen'),
			'join' => array(array('type' => 'left', 'table' => 'regimen_lista_regimen', 'where' => 'regimen.id = regimen_lista_regimen.regimen_id AND regimen_lista_regimen.regimen_lista_id =' . $regimen_lista_id)),
			'where' => array('regimen_lista_regimen.id IS NULL AND dependencia_id IS NULL'),
			'sort_by' => 'regimen.codigo'
			), array('' => '-- Sin regímenes --'));

		$this->set_model_validation_rules($this->regimen_lista_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($regimen_lista_id !== $this->input->post('regimen_lista_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			if ($this->form_validation->run() === TRUE) {
				$this->load->model('regimen_lista_regimen_model');
				$this->db->trans_begin();
				$trans_ok = TRUE;

				foreach ($this->input->post('regimenes') as $regimen_id) {
					$trans_ok &= $this->regimen_lista_regimen_model->create(array(
						'regimen_lista_id' => $regimen_lista->id,
						'regimen_id' => $regimen_id
						), FALSE);
				}

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->regimen_lista_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar.';
					if ($this->regimen_lista_regimen_model->get_error())
						$errors .= '<br>' . $this->regimen_lista_regimen_model->get_error();
					$this->session->set_flashdata('error', $errors);
				}
				redirect("regimen_lista/editar/$regimen_lista_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("regimen_lista/editar/$regimen_lista_id", 'refresh');
			}
		}
		$this->regimen_lista_model->fields['regimenes']['array'] = $array_regimenes;
		$data['fields'] = $this->build_fields($this->regimen_lista_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['regimen_lista_id'] = $regimen_lista_id;
		$data['title'] = 'Agregar régimen';
		$this->load->view('regimen_lista/regimen_modal_abm', $data);
	}
}
/* End of file Regimen.php */
/* Location: ./application/controllers/Regimen.php */