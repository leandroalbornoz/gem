<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cargo_horario extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('horario_model');
		$this->load->model('cargo_model');
		$this->load->model('escuela_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ESCUELA_ALUM, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE_CURSADA));
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_GRUPO_ESCUELA_CONSULTA))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'menu/cargo_horario';
	}

	public function modal_agregar($cargo_id, $dia_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || !$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('dia_model');

		$cargo = $this->cargo_model->get_one($cargo_id);
		
		$dia = $this->dia_model->get(array('id' => $dia_id));
		if (empty($cargo) || empty($dia)) {
			$this->modal_error('No se encontró el registro a agregar', 'Registro no encontrado');
			return;
		}

		$escuela = $this->escuela_model->get_one($cargo->escuela_id);
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		unset($this->horario_model->fields['division']);
		unset($this->horario_model->fields['hora_catedra']);
		if ($cargo->regimen_tipo_id != 2) {
			unset($this->horario_model->fields['obligaciones']);
		}
		unset($this->horario_model->fields['dia']['input_type']);
		unset($this->horario_model->fields['valido_desde']);
		unset($this->horario_model->fields['valido_hasta']);

		$this->horario_model->fields['dia']['readonly'] = TRUE;
		$this->horario_model->fields['dia']['value'] = $dia->nombre;

		$this->set_model_validation_rules($this->horario_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->horario_model->create(array(
					'cargo_id' => $cargo->id,
					'dia_id' => $dia->id,
					'obligaciones' => $this->input->post('obligaciones'),
					'hora_desde' => $this->input->post('hora_desde'),
					'hora_hasta' => $this->input->post('hora_hasta')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->horario_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->horario_model->get_error());
				}
				redirect("cargo/horarios/$cargo->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("cargo/horarios/$cargo->id", 'refresh');
			}
		}
		$data['regimen_tipo_id'] = $cargo->regimen_tipo_id;
		$data['fields'] = $this->build_fields($this->horario_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar horarios de cargo';
		$this->load->view('cargo_horario/cargo_horario_modal_abm', $data);
	}

	public function modal_editar($id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || !$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$cargo_horario = $this->horario_model->get_one($id);
		if (empty($cargo_horario)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}

		$cargo = $this->cargo_model->get_one($cargo_horario->cargo_id);
		$escuela = $this->escuela_model->get_one($cargo->escuela_id);
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}


		unset($this->horario_model->fields['division']);
		unset($this->horario_model->fields['hora_catedra']);
		if ($cargo->regimen_tipo_id != 2) {
			unset($this->horario_model->fields['obligaciones']);
		}
		unset($this->horario_model->fields['dia']['input_type']);
		unset($this->horario_model->fields['valido_desde']);
		unset($this->horario_model->fields['valido_hasta']);

		$this->horario_model->fields['dia']['readonly'] = TRUE;

		$this->set_model_validation_rules($this->horario_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($cargo_horario->id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->horario_model->update(array(
					'id' => $this->input->post('id'),
					'obligaciones' => $this->input->post('obligaciones'),
					'hora_desde' => $this->input->post('hora_desde'),
					'hora_hasta' => $this->input->post('hora_hasta')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->horario_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->horario_model->get_error());
				}
				redirect("cargo/horarios/$cargo_horario->cargo_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("cargo/horarios/$cargo_horario->cargo_id", 'refresh');
			}
		}

		$data['regimen_tipo_id'] = $cargo->regimen_tipo_id;
		$data['fields'] = $this->build_fields($this->horario_model->fields, $cargo_horario);
		$data['cargo_horario'] = $cargo_horario;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar horarios de cargo';
		$this->load->view('cargo_horario/cargo_horario_modal_abm', $data);
	}

	public function modal_eliminar($id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || !$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$cargo_horario = $this->horario_model->get_one($id);
		if (empty($cargo_horario)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}

		$cargo = $this->cargo_model->get_one($cargo_horario->cargo_id);
		$escuela = $this->escuela_model->get_one($cargo->escuela_id);
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		unset($this->horario_model->fields['division']);
		unset($this->horario_model->fields['hora_catedra']);
		if ($cargo->regimen_tipo_id != 2) {
			unset($this->horario_model->fields['obligaciones']);
		}
		unset($this->horario_model->fields['dia']['input_type']);
		unset($this->horario_model->fields['valido_desde']);
		unset($this->horario_model->fields['valido_hasta']);

		$this->horario_model->fields['dia']['readonly'] = TRUE;

		if (isset($_POST) && !empty($_POST)) {
			if ($cargo_horario->id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->horario_model->delete(array('id' => $this->input->post('id')));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->horario_model->get_msg());
			} else {
				$this->session->set_flashdata('error', $this->horario_model->get_error());
			}
			redirect("cargo/horarios/$cargo_horario->cargo_id", 'refresh');
		}
		$data['regimen_tipo_id'] = $cargo->regimen_tipo_id;
		$data['fields'] = $this->build_fields($this->horario_model->fields, $cargo_horario, TRUE);
		$data['cargo_horario'] = $cargo_horario;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar horarios de cargo';
		$this->load->view('cargo_horario/cargo_horario_modal_abm', $data);
	}

	public function modal_carga_masiva($cargo_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $cargo_id == NULL || !ctype_digit($cargo_id) || !$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('dia_model');
		$cargo = $this->cargo_model->get(array('id' => $cargo_id));
		if (empty($cargo)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}

		$escuela = $this->escuela_model->get_one($cargo->escuela_id);
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($cargo_id !== $this->input->post('cargo_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$this->form_validation->set_rules("hora_desde", 'Hora desde', 'trim|required|exact_length[5]|validate_time');
			$this->form_validation->set_rules("hora_hasta", 'Hora hasta', 'trim|required|exact_length[5]|validate_time');
			$this->form_validation->set_rules("dias[]", 'Días', 'required');

			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				foreach ($this->input->post('dias') as $dia) {
					$cargo_horario = $this->horario_model->get(array(
						'dia_id' => $dia,
						'cargo_id' => $cargo->id,
						'where' => array('division_id IS NULL')
					));

					if (!empty($cargo_horario)) {
						$trans_ok &= $this->horario_model->update(array(
							'id' => $cargo_horario[0]->id,
							'hora_desde' => $this->input->post('hora_desde'),
							'hora_hasta' => $this->input->post('hora_hasta')
							), FALSE);
					} else {
						$trans_ok &= $this->horario_model->create(array(
							'cargo_id' => $cargo->id,
							'dia_id' => $dia,
							'obligaciones' => 1,
							'hora_desde' => $this->input->post('hora_desde'),
							'hora_hasta' => $this->input->post('hora_hasta')
							), FALSE);
					}
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->horario_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->horario_model->get_error());
				}
				redirect("cargo/horarios/$cargo->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("cargo/horarios/$cargo->id", 'refresh');
			}
		}
		$data['cargo'] = $cargo;
		$data['dias'] = $this->dia_model->get();
		$data['title'] = "Carga masiva de horarios";
		$this->load->view('cargo_horario/cargo_horario_modal_carga_masiva', $data);
	}
}
/* End of file Cargo_horario.php */
/* Location: ./application/controllers/Cargo_horario.php */