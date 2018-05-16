<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Incidencia_detalle extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('incidencia_detalle_model');
		$this->load->model('incidencia_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'par/incidencia_detalle';
	}

	public function modal_ver($incidencia_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $incidencia_id == NULL || !ctype_digit($incidencia_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$incidencia = $this->incidencia_model->get_one($incidencia_id);
		if (empty($incidencia)) {
			return $this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
		}
		$incidencia_detalle = $this->incidencia_detalle_model->get(array(
			'join' => array(
				array('usuario', 'usuario.id = incidencia_detalle.audi_user', 'left'),
				array('usuario_persona', 'usuario.id = usuario_persona.usuario_id', 'left'),
				array('persona', 'usuario_persona.cuil = persona.cuil', 'left', array("CONCAT(persona.apellido, ', ', persona.nombre) as usuario")),
			),
			'incidencia_id' => $incidencia_id
		));
		if (empty($incidencia_detalle)) {
			return $this->modal_error('No hay detalles cargados en esta incidencia', 'Registro no encontrado');
		}
		$incidencia_detalle_fecha = array();
		foreach ($incidencia_detalle as $detalle) {
			$incidencia_detalle_fecha[date_format(new DateTime($detalle->fecha), 'Y-m-d')][] = $detalle;
		}
		$data['incidencia_detalle_fecha'] = $incidencia_detalle_fecha;
		$data['incidencia_detalle'] = $incidencia_detalle;
		$data['incidencia'] = $incidencia;
		$data['txt_btn'] = NULL;
		$data['title'] = TITLE . ' - Ver detalles de incidencia';
		$this->load->view('incidencia_detalle/incidencia_detalle_modal_ver', $data);
	}

	public function modal_agregar($incidencia_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $incidencia_id == NULL || !ctype_digit($incidencia_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$incidencia = $this->incidencia_model->get_one($incidencia_id);
		if (empty($incidencia)) {
			return $this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
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

		$model = new stdClass();
		$model->fields = array(
			'estado' => array('label' => 'Estado', 'input_type' => 'combo', 'required' => TRUE, 'id_name' => 'estado', 'array' => array('Pendiente' => 'Pendiente', 'Cerrado' => 'Cerrado')),
			'detalle' => array('label' => 'Detalle', 'form_type' => 'textarea', 'rows' => '5', 'required' => TRUE)
		);
		$this->array_estado_control = $this->incidencia_model->fields['estado']['array'];
		$this->set_model_validation_rules($model);
		if (isset($_POST) && !empty($_POST)) {
			if ($incidencia_id !== $this->input->post('incidencia_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->incidencia_detalle_model->create(array(
					'incidencia_id' => $incidencia_id,
					'detalle' => $this->input->post('detalle'),
					'fecha' => date('Y-m-d H:i:s')
					), FALSE);
				$trans_ok &= $this->incidencia_model->update(array(
					'id' => $incidencia->id,
					'estado' => $this->input->post('estado'),
					'fecha_cierre' => date('Y-m-d H:i:s')
					), FALSE);
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->incidencia_detalle_model->get_msg());
					redirect("servicio/editar/$incidencia->servicio_id#tab_incidencias", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al realizar la acción.';
					if ($this->incidencia_model->get_error())
						$errors .= '<br>' . $this->incidencia_model->get_error();
					if ($this->incidencia_detalle_model->get_error())
						$errors .= '<br>' . $this->incidencia_detalle_model->get_error();
					redirect("servicio/editar/$incidencia->servicio_id#tab_incidencias", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("servicio/editar/$incidencia->servicio_id#tab_incidencias", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($model->fields);
		$data['incidencia_detalle_fecha'] = $incidencia_detalle_fecha;
		$data['incidencia_detalle'] = $incidencia_detalle;
		$data['incidencia'] = $incidencia;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['txt_btn'] = 'Agregar detalle';
		$data['title'] = TITLE . ' - Agregar detalle a una incidencia';
		$this->load->view('incidencia_detalle/incidencia_detalle_modal_agregar', $data);
	}
}
/* End of file Incidencia_detalle.php */
/* Location: ./application/controllers/Incidencia_detalle.php */