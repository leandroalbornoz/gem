<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escuela extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->load->model('acuerdo_zona_model');
		$this->load->model('acuerdo_zona_remito_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_DIR_ESCUELA, ROL_LINEA, ROL_SUPERVISION, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_JEFE_LIQUIDACION, ROL_LIQUIDACION, ROL_REGIONAL, ROL_USI, ROL_GRUPO_ESCUELA, ROL_GRUPO_ESCUELA_CONSULTA);
		$this->roles_admin = array(ROL_USI, ROL_ADMIN);
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_GRUPO_ESCUELA_CONSULTA, ROL_REGIONAL))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'admin/escuela';
	}

	public function recepcion($escuela_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->form_validation->set_rules('cuil', 'CUIL', 'required|callback_validaCuil');
		$this->form_validation->set_message('validaCuil', 'Cuil no válido');
		if ($this->form_validation->run() === TRUE) {
			$cuil = $this->input->post('cuil');
			$data['persona'] = $this->acuerdo_zona_model->buscar_persona($cuil);
		}

		$remitos_db = $this->acuerdo_zona_remito_model->get(array(
			'escuela_id' => $escuela->id,
			'join' => array(
				array('acuerdo_zona_recepcion', 'acuerdo_zona_remito.id=acuerdo_zona_recepcion.acuerdo_zona_remito_id', 'left', array('fecha', 'cuil', 'nombre', 'jubilado', 'estado'))
			),
			'sort_by' => 'numero DESC, fecha DESC'
		));
		$remito_activo = (object) array('id' => '0', 'numero' => 'Sin Remito Activo');
		$remitos = array();
		if (!empty($remitos_db)) {
			if (empty($remitos_db[0]->fecha_fin)) {
				$remito_activo = $remitos_db[0];
			}
			foreach ($remitos_db as $remito) {
				if (empty($remitos[$remito->id])) {
					$remito->recepcion = array();
					$remitos[$remito->id] = $remito;
				}
				if (empty($remito->persona)) {
					$remitos[$remito->id]->recepcion[] = $remito;
				}
			}
		}

		$data['error'] = $this->session->flashdata('error');

		$data['remito_activo'] = $remito_activo;
		$data['remitos'] = $remitos;
		$data['escuela'] = $escuela;
		$data['title'] = TITLE . ' - Recepción Acuerdos Zona';
		$this->load_template('acuerdo_zona/escuela/escuela_recepcion', $data);
	}

	public function recibir($escuela_id) {
		if (!accion_permitida($this->rol, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		if (TRUE) {
			$this->session->set_flashdata('error', 'Ya ha cerrado el periodo de recepción de Acuerdos');
			redirect("acuerdo_zona/escuela/recepcion/$escuela->id");
		}

		$this->array_estado_control = array('Conforme' => 'Conforme', 'Disconforme' => 'Disconforme');
		$this->array_jubilado_control = array('Si' => 'Si', 'No' => 'No');
		$this->form_validation->set_rules('cuil', 'CUIL', 'required|callback_validaCuil');
		$this->form_validation->set_rules('estado', 'Estado', 'required|callback_control_combo[estado]');
		$this->form_validation->set_message('validaCuil', 'Cuil no válido');
		if ($this->input->post('estado') === 'Conforme') {
			$this->form_validation->set_rules('jubilado', 'Jubilado', 'required|callback_control_combo[jubilado]');
		}
		if ($this->form_validation->run() === TRUE) {
			$persona = $this->acuerdo_zona_model->buscar_persona($this->input->post('cuil'));
			if (!empty($persona)) {
				$remito = $this->acuerdo_zona_remito_model->buscar_remito_activo($escuela_id);
				$this->load->model('acuerdo_zona_recepcion_model');
				$inconsistencia = $this->acuerdo_zona_recepcion_model->buscar_inconsistencia_cuil($this->input->post('cuil'), $this->input->post('estado'));
				if (empty($inconsistencia)) {
					$trans_ok = $this->acuerdo_zona_recepcion_model->create(array(
						'acuerdo_zona_remito_id' => $remito->id,
						'fecha' => date('Y-m-d H:i:s'),
						'cuil' => $this->input->post('cuil'),
						'persona' => $persona->persona,
						'nombre' => $persona->nombre,
						'jubilado' => $this->input->post('jubilado') ? $this->input->post('jubilado') : 'No',
						'estado' => $this->input->post('estado')
					));
					if ($trans_ok) {
						$this->session->set_flashdata('message', $this->acuerdo_zona_recepcion_model->get_msg());
					} else {
						$this->session->set_flashdata('error', $this->acuerdo_zona_recepcion_model->get_error());
					}
				} else {
					$this->session->set_flashdata('error', "Ya se ha recibido un acuerdo de la persona en Esc. $inconsistencia->escuela - Remito N° $inconsistencia->numero como $inconsistencia->estado.<br/>La conformidad debe ser absoluta.<br/>Ante cualquier consulta por favor comunicarse con Dirección de Administración.");
				}
			} else {
				$this->session->set_flashdata('error', 'Persona no encontrada');
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
		}
		redirect("acuerdo_zona/escuela/recepcion/$escuela->id");
	}

	public function modal_cerrar_remito($remito_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos) || $remito_id == NULL || !ctype_digit($remito_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$remito = $this->acuerdo_zona_remito_model->get_one($remito_id);
		if (empty($remito)) {
			$this->modal_error('No se encontró el registro a cerrar', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($remito->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}
		if (!empty($remito->fecha_fin)) {
			$this->modal_error('El remito ya está cerrado', 'Acción no permitida');
			return;
		}
		$this->load->model('acuerdo_zona_recepcion_model');
		$recepciones = $this->acuerdo_zona_recepcion_model->get(array('acuerdo_zona_remito_id' => $remito->id));
		if (empty($recepciones)) {
			$this->modal_error('No puede cerrar un remito sin recepciones', 'Acción no permitida');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($remito->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}

			$trans_ok = TRUE;
			$this->db->trans_begin();
			$trans_ok &= $this->acuerdo_zona_remito_model->update(array(
				'id' => $remito->id,
				'fecha_fin' => date('Y-m-d H:i')
				), FALSE);

			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Remito cerrado correctamente');
			} else {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', $this->acuerd_zona_remito_model->get_error());
			}
			redirect("acuerdo_zona/escuela/recepcion/$escuela->id");
		}
		$data['remito'] = $remito;
		$data['txt_btn'] = 'Cerrar';
		$data['title'] = 'Cerrar remito de acuerdos zona';
		$this->load->view('acuerdo_zona/escuela/escuela_modal_cerrar_remito', $data);
	}

	public function imprimir_remito($remito_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos) || $remito_id == NULL || !ctype_digit($remito_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$remito = $this->acuerdo_zona_remito_model->get_one($remito_id);
		if (empty($remito)) {
			show_error('No se encontró el registro a cerrar', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($remito->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		if (empty($remito->fecha_fin)) {
			show_error('El remito no está cerrado', 500, 'Acción no permitida');
		}
		$this->load->model('acuerdo_zona_recepcion_model');
		$recepciones = $this->acuerdo_zona_recepcion_model->get(array('acuerdo_zona_remito_id' => $remito->id));
		if (empty($recepciones)) {
			show_error('No puede cerrar un remito sin recepciones', 500, 'Acción no permitida');
		}
		$data['escuela'] = $escuela;
		$data['remito'] = $remito;
		$data['recepciones'] = $recepciones;
		$data['title'] = 'Cerrar remito de acuerdos zona';
		$this->load->helper('mpdf');
		$html = $this->load->view('acuerdo_zona/escuela/escuela_impresion_remito_pdf', $data, TRUE);
		exportarMPDF($html, 'plugins/kv-mpdf-bootstrap.min.css', "Esc. $escuela->nombre_corto - Remito N° $remito->numero", "|Esc. $escuela->nombre_corto - Remito N° $remito->numero|Fecha: " . date('d/m/Y'), '', 'A4', '', 'I', FALSE, FALSE);
	}

	public function validaCuil($cuit) {
		$digits = array();
		if (strlen($cuit) != 13)
			return false;
		for ($i = 0; $i < strlen($cuit); $i++) {
			if ($i == 2 or $i == 11) {
				if ($cuit[$i] != '-')
					return false;
			} else {
				if (!ctype_digit($cuit[$i]))
					return false;
				if ($i < 12) {
					$digits[] = $cuit[$i];
				}
			}
		}
		$acum = 0;
		foreach (array(5, 4, 3, 2, 7, 6, 5, 4, 3, 2) as $i => $multiplicador) {
			$acum += $digits[$i] * $multiplicador;
		}
		$cmp = 11 - ($acum % 11);
		if ($cmp == 11)
			$cmp = 0;
		if ($cmp == 10)
			$cmp = 9;
		return ($cuit[12] == $cmp);
	}
}
/* End of file Escuela.php */
/* Location: ./application/modules/acuerdo_zona/controllers/Escuela.php */