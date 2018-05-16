<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cargo_escuela extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('cargo_escuela_model');
		$this->load->model('cargo_model');
		$this->load->model('escuela_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ESCUELA_ALUM,ROL_ASISTENCIA_DIVISION,ROL_DOCENTE_CURSADA));
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_GRUPO_ESCUELA_CONSULTA))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'menu/cargo_escuela';
	}

	public function modal_agregar($cargo_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || !$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$cargo = $this->cargo_model->get(array('id' => $cargo_id));
		if (empty($cargo)) {
			$this->modal_error('No se encontró el registro a agregar', 'Registro no encontrado');
			return;
		}

		$escuela = $this->escuela_model->get_one($cargo->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$this->load->model('cargo_escuela_model');
		$this->array_escuela_control = $array_escuela = $this->get_array('escuela', 'nombre_mostrar', 'id', array('select' => array('id', "CONCAT(numero, CASE WHEN anexo=0 THEN ' ' ELSE CONCAT('/',anexo,' ') END, nombre) as nombre_mostrar"), 'sort_by' => 'numero, anexo, nombre'), array('' => '-- Seleccionar --'));

		$this->set_model_validation_rules($this->cargo_escuela_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->cargo_escuela_model->create(array(
					'cargo_id' => $cargo->id,
					'escuela_id' => $this->input->post('escuela'),
					'cantidad_horas' => $this->input->post('cantidad_horas')
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->cargo_escuela_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->cargo_escuela_model->get_error());
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect("cargo/compartir/$cargo->id", 'refresh');
		}
		$this->cargo_escuela_model->fields['escuela']['array'] = $array_escuela;
		$data['fields'] = $this->build_fields($this->cargo_escuela_model->fields);
		$data['txt_btn'] = 'Compartir';
		$data['title'] = 'Compartir cargo con escuela';
		$this->load->view('cargo_escuela/cargo_escuela_modal_abm', $data);
	}

	public function modal_eliminar($id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || !$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$cargo_escuela = $this->cargo_escuela_model->get_one($id);
		if (empty($cargo_escuela)) {
			$this->modal_error('No se encontró el registro a agregar', 'Registro no encontrado');
			return;
		}

		$cargo = $this->cargo_model->get(array('id' => $cargo_escuela->cargo_id));
		if (empty($cargo)) {
			$this->modal_error('No se encontró el registro a agregar', 'Registro no encontrado');
			return;
		}

		$escuela = $this->escuela_model->get_one($cargo->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($cargo_escuela->id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->cargo_escuela_model->delete(array('id' => $this->input->post('id')));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->cargo_escuela_model->get_msg());
			} else {
				$this->session->set_flashdata('error', $this->cargo_escuela_model->get_error());
			}
			redirect("cargo/compartir/$cargo_escuela->cargo_id", 'refresh');
		}

		$data['fields'] = $this->build_fields($this->cargo_escuela_model->fields, $cargo_escuela, TRUE);
		$data['cargo_escuela'] = $cargo_escuela;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Dejar de compartir cargo con escuela';
		$this->load->view('cargo_escuela/cargo_escuela_modal_abm', $data);
	}
}
/* End of file Cargo_escuela.php */
/* Location: ./application/controllers/Cargo_escuela.php */