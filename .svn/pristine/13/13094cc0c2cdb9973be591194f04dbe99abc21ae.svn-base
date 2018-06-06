<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Incidencia extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('incidencia_model');
		$this->load->model('incidencia_detalle_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'par/incidencia';
	}

	public function modal_agregar($servicio_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $servicio_id == NULL || !ctype_digit($servicio_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}

		$this->load->model('servicio_model');
		$servicio = $this->servicio_model->get_one($servicio_id);
		if (empty($servicio)) {
			return $this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
		}
		unset($this->incidencia_model->fields['fecha']);
		$this->array_estado_control = $this->incidencia_model->fields['estado']['array'];
		$this->set_model_validation_rules($this->incidencia_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->incidencia_model->create(array(
					'servicio_id' => $servicio_id,
					'asunto' => $this->input->post('asunto'),
					'fecha' => date('Y-m-d H:i:s'),
					'estado' => $this->input->post('estado')
				));
				$incidencia_id= $this->incidencia_model->get_row_id();
				$trans_ok &= $this->incidencia_detalle_model->create(array(
					'incidencia_id' => $incidencia_id,
					'detalle' => $this->input->post('detalle'),
					'fecha' => date('Y-m-d H:i:s')
					));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->incidencia_model->get_msg());
					redirect("servicio/editar/$servicio_id#tab_incidencias", 'refresh');
				} else {
					$this->session->set_flashdata('error', $this->incidencia_model->get_error());
					redirect("servicio/editar/$servicio_id#tab_incidencias", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("servicio/editar/$servicio_id#tab_incidencias", 'refresh');
			}
		}
		$data['servicio'] = $servicio;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['fields'] = $this->build_fields($this->incidencia_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = TITLE . ' - Agregar incidencia';
		$this->load->view('incidencia/incidencia_modal_agregar', $data);
	}

	public function modal_eliminar($incidencia_id = NULL, $servicio_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $incidencia_id == NULL || !ctype_digit($incidencia_id) || $servicio_id == NULL || !ctype_digit($servicio_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$incidencia = $this->incidencia_model->get_one($incidencia_id);
		if (empty($incidencia)) {
			return $this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
		}
		$incidencia_detalle = $this->incidencia_detalle_model->get(array(
			'join' => array(
				array('usuario', 'usuario.id = incidencia_detalle.audi_user', 'left'),
				array('usuario_persona', 'usuario.id = usuario_persona.usuario_id', 'left'),
				array('persona', 'usuario_persona.cuil = persona.cuil', 'left', array("CONCAT(persona.apellido, ', ', persona.nombre) as usuario")),
			),
			'incidencia_id' => $incidencia_id
		));
		$incidencia_detalle_fecha = array();
		if (!empty($incidencia_detalle)) {
			foreach ($incidencia_detalle as $detalle) {
				$incidencia_detalle_fecha[date_format(new DateTime($detalle->fecha), 'Y-m-d')][] = $detalle;
			}
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($incidencia_id !== $this->input->post('incidencia_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;
			foreach ($incidencia_detalle_fecha as $key => $detalle_fecha) {
				foreach ($detalle_fecha as $detalle) {
					$trans_ok &= $this->incidencia_detalle_model->delete(array(
						'id' => $detalle->id
						), FALSE);
				}
			}
			$trans_ok &= $this->incidencia_model->delete(array(
				'id' => $this->input->post('incidencia_id')), FALSE);
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->incidencia_model->get_msg());
				redirect("servicio/editar/$servicio_id#tab_incidencias", 'refresh');
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al realizar la acción.';
				if ($this->incidencia_model->get_error())
					$errors .= '<br>' . $this->incidencia_model->get_error();
				if ($this->incidencia_detalle_model->get_error())
					$errors .= '<br>' . $this->incidencia_detalle_model->get_error();
				redirect("servicio/editar/$incidencia->servicio_id#tab_incidencias", 'refresh');
			}
		}
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['incidencia'] = $incidencia;
		$data['incidencia_detalle'] = $incidencia_detalle;
		$data['incidencia_detalle_fecha'] = $incidencia_detalle_fecha;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = TITLE . ' - Eliminar incidencia';
		$this->load->view('incidencia/incidencia_modal_eliminar', $data);
	}
}
/* End of file Incidencia.php */
/* Location: ./application/controllers/Incidencia.php */