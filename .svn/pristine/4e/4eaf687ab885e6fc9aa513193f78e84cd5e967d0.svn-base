<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Referente extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('persona_model');
		$this->load->model('escuela_model');
		$this->load->model('servicio_model');
		$this->load->model('regimen_model');
		$this->load->model('referente_model');
		$this->load->model('referente_vigencia_model');
		$this->load->model('tribunal_cuenta_model');
		$this->load->model('referente_vigencia_fondos_pendientes_model');
		$this->load->model('referente_vigencia_saldo_model');
		$this->load->model('referente_vigencia_ultimo_cheque_model');
		$this->roles_admin = array(ROL_ADMIN, ROL_USI);
		$this->roles_permitidos = array(ROL_ADMIN, ROL_ESCUELA_ALUM, ROL_ESCUELA_CAR, ROL_MODULO, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA);
		$this->modulos_permitidos = array(ROL_MODULO_REFERENTES_TRIBUNAL_CUENTAS);
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_CAR, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA))) {
			$this->edicion = FALSE;
		}
	}

	public function modal_buscar($escuela_id = NULL, $cuenta_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $cuenta_id == NULL || !ctype_digit($cuenta_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		$cuenta = $this->tribunal_cuenta_model->get_one($cuenta_id);
		if (empty($cuenta)) {
			$this->modal_error('No se encontró el registro de la cuenta bacaria', 'Registro no encontrado');
			return;
		}
//		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
//			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
//			return;
//		}
		$model_busqueda = new stdClass();
		$model_busqueda->fields = array(
			'd_cuil' => array('label' => 'Cuil', 'maxlength' => '14', 'onkeypress' => 'validar(event);'),
			'fecha_desde' => array('label' => 'Fecha desde (Vigencia del Referente)', 'type' => 'date', 'required' => TRUE),
			'persona_seleccionada' => array('label' => '', 'type' => 'integer', 'required' => TRUE),
		);
		$this->set_model_validation_rules($model_busqueda);

		$data['fields'] = $this->build_fields($model_busqueda->fields);
		$data['escuela'] = $escuela;
		$data['cuenta'] = $cuenta;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Buscar referente a agregar';
		$this->load->view('referente/referente_modal_buscar', $data);
	}

	public function agregar_referente($escuela_id = NULL, $cuenta_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $cuenta_id == NULL || !ctype_digit($cuenta_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$servicio_id = $this->input->post('servicio_id');
		if (empty($servicio_id)) {
			show_error('No se encontró el registro del servicio', 500, 'Registro no encontrado');
		}
		$fecha_desde = $this->get_date_sql('fecha_desde');
		if (empty($fecha_desde)) {
			show_error('No se encontró el registro de fecha desde', 500, 'Registro no encontrado');
		}
		$servicio = $this->servicio_model->get_one($servicio_id);
		if (empty($servicio)) {
			show_error('No se encontró el registro del servicio', 500, 'Registro no encontrado');
		}
		$persona = $this->persona_model->get_one($servicio->persona_id);
		if (empty($persona)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		$cuenta = $this->tribunal_cuenta_model->get_one($cuenta_id);
		if (empty($cuenta)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
//		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
//			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
//		}

		if (isset($_POST) && !empty($_POST)) {
			$referente_existente = $this->referente_model->get_one($this->input->post('referente_id'));
			$this->db->trans_begin();
			$trans_ok = TRUE;
			if ($this->input->post('referente_id') === 'null' || $referente_existente->servicio_id != $servicio_id) {
				$trans_ok &= $this->referente_model->create(array(
					'tribunal_cuenta_id' => $cuenta->id,
					'servicio_id' => $servicio_id,
					'domicilio_legal' => "Casa de Gobierno 3er Piso Ala Este - Ciudad",
					'estado' => 'Activo'
					), FALSE);

				$referente_id = $this->referente_model->get_row_id();

				$trans_ok &= $this->referente_vigencia_model->create(array(
					'referente_id' => $referente_id,
					'fecha_desde' => $fecha_desde
					), FALSE);
			} else {
				$trans_ok &= $this->referente_vigencia_model->create(array(
					'referente_id' => $this->input->post('referente_id'),
					'fecha_desde' => $fecha_desde
					), FALSE);
			}
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->referente_model->get_msg());
				redirect("tribunal/escritorio/escuela/$escuela_id", 'refresh');
			} else {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', $this->referente_model->get_error());
				redirect("tribunal/escritorio/escuela/$escuela_id", 'refresh');
			}
		}
	}

	public function ver($referente_vigencia_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $referente_vigencia_id == NULL || !ctype_digit($referente_vigencia_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$referente_vigencia = $this->referente_vigencia_model->get_one($referente_vigencia_id);
		if (empty($referente_vigencia)) {
			show_error('No se encontró el registro de vigencia del referente', 500, 'Registro no encontrado');
		}
		$referente = $this->referente_model->get_one($referente_vigencia->referente_id);
		if (empty($referente)) {
			show_error('No se encontró el registro de referente', 500, 'Registro no encontrado');
		}
		$servicio = $this->servicio_model->get_one($referente->servicio_id);
		if (empty($servicio)) {
			show_error('No se encontró el registro de servicio', 500, 'Registro no encontrado');
		}
		$regimen = $this->regimen_model->get_one($servicio->regimen_id);
		if (empty($regimen)) {
			show_error('No se encontró el registro de servicio', 500, 'Registro no encontrado');
		}
		$persona = $this->persona_model->get_one($servicio->persona_id);
		if (empty($servicio)) {
			show_error('No se encontró el registro de persona', 500, 'Registro no encontrado');
		}
		$tribunal_cuenta = $this->tribunal_cuenta_model->get_one($referente->tribunal_cuenta_id);
		if (empty($tribunal_cuenta)) {
			show_error('No se encontró el registro de cuenta bancaria', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($tribunal_cuenta->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
//		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
//			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
//		}

		$this->load->model('referente_vigencia_model');
		$this->load->model('referente_vigencia_fondos_pendientes_model');
		$this->load->model('referente_vigencia_saldo_model');
		$this->load->model('referente_vigencia_ultimo_cheque_model');

		$fondos_pendientes = $this->referente_vigencia_fondos_pendientes_model->get(array('referente_vigencia_id' => $referente_vigencia->id));
		$saldo = $this->referente_vigencia_saldo_model->get(array('referente_vigencia_id' => $referente_vigencia->id));
		$ultimo_cheque = $this->referente_vigencia_ultimo_cheque_model->get(array('referente_vigencia_id' => $referente_vigencia->id));

		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona, TRUE);
		$data['fields_vs'] = $this->build_fields($this->referente_vigencia_saldo_model->fields, $saldo[0], TRUE);
		$data['fields_vc'] = $this->build_fields($this->referente_vigencia_ultimo_cheque_model->fields, $ultimo_cheque[0], TRUE);
		$data['servicio'] = $servicio;
		$data['regimen'] = $regimen;
		$data['fondos_pendientes'] = $fondos_pendientes;
		$data['persona'] = $persona;
		$data['escuela'] = $escuela;
		$data['referente'] = $referente;
		$data['referente_vigencia'] = $referente_vigencia;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['txt_btn'] = 'ver';
		$data['title'] = TITLE . ' - Ver referente';
		$this->load_template('tribunal/referente/referente_ver', $data);
	}

	public function modal_ultimo_cheque($referente_vigencia_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $referente_vigencia_id == NULL || !ctype_digit($referente_vigencia_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$referente_vigencia = $this->referente_vigencia_model->get_one($referente_vigencia_id);
		if (empty($referente_vigencia)) {
			$this->modal_error('No se encontró el registro de vigencia del referente', 'Registro no encontrado');
			return;
		}
		$referente = $this->referente_model->get_one($referente_vigencia->referente_id);
		if (empty($referente)) {
			$this->modal_error('No se encontró el registro de referente', 'Registro no encontrado');
			return;
		}
		$servicio = $this->servicio_model->get_one($referente->servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el registro del servicio', 'Registro no encontrado');
			return;
		}
		$persona = $this->persona_model->get_one($servicio->persona_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el registro de persona', 'Registro no encontrado');
			return;
		}
		$tribunal_cuenta = $this->tribunal_cuenta_model->get_one($referente->tribunal_cuenta_id);
		if (empty($tribunal_cuenta)) {
			$this->modal_error('No se encontró el registro de cuenta bancaria', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($tribunal_cuenta->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
//		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
//			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
//			return;
//		}
		$this->load->model('referente_vigencia_ultimo_cheque_model');

		$this->load->model('referente_vigencia_model');
		$this->load->model('referente_vigencia_fondos_pendientes_model');
		$this->load->model('referente_vigencia_saldo_model');
		$this->load->model('referente_vigencia_ultimo_cheque_model');

		$ultimo_cheque = $this->referente_vigencia_ultimo_cheque_model->get(array('referente_vigencia_id' => $referente_vigencia->id));

		$this->set_model_validation_rules($this->referente_vigencia_ultimo_cheque_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($referente_vigencia->id !== $this->input->post('referente_vigencia_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				if ($ultimo_cheque[0] !== NULL) {
					$trans_ok &= $this->referente_vigencia_ultimo_cheque_model->update(array(
						'id' => $ultimo_cheque[0]->id,
						'referente_vigencia_id' => $this->input->post('referente_vigencia_id'),
						'numero' => $this->input->post('numero'),
						'importe' => $this->input->post('importe'),
						'fecha' => $this->get_date_sql('fecha')
						), FALSE);
				} else {
					$trans_ok &= $this->referente_vigencia_ultimo_cheque_model->create(array(
						'referente_vigencia_id' => $this->input->post('referente_vigencia_id'),
						'numero' => $this->input->post('numero'),
						'importe' => $this->input->post('importe'),
						'fecha' => $this->get_date_sql('fecha')
						), FALSE);
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->referente_vigencia_ultimo_cheque_model->get_msg());
					redirect("tribunal/referente/ver/$referente_vigencia_id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->referente_vigencia_ultimo_cheque_model->get_error())
						$errors .= '<br>' . $this->referente_vigencia_ultimo_cheque_model->get_error();
					$this->session->set_flashdata('error', $errors);
					redirect("tribunal/referente/ver/$referente_vigencia_id", 'refresh');
				}
			}else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("tribunal/referente/ver/$referente_vigencia_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona, TRUE);
		$data['fields_vc'] = $this->build_fields($this->referente_vigencia_ultimo_cheque_model->fields, $ultimo_cheque[0]);
		$data['servicio'] = $servicio;
		$data['persona'] = $persona;
		$data['escuela'] = $escuela;
		$data['referente'] = $referente;
		$data['referente_vigencia'] = $referente_vigencia;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['txt_btn'] = 'Guardar';
		$data['title'] = 'Último cheque';
		$this->load->view('tribunal/referente/referente_modal_cheque', $data);
	}

	public function modal_saldo_cuenta($referente_vigencia_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $referente_vigencia_id == NULL || !ctype_digit($referente_vigencia_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$referente_vigencia = $this->referente_vigencia_model->get_one($referente_vigencia_id);
		if (empty($referente_vigencia)) {
			$this->modal_error('No se encontró el registro de vigencia del referente', 'Registro no encontrado');
			return;
		}
		$referente = $this->referente_model->get_one($referente_vigencia->referente_id);
		if (empty($referente)) {
			$this->modal_error('No se encontró el registro de referente', 'Registro no encontrado');
			return;
		}
		$servicio = $this->servicio_model->get_one($referente->servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el registro de servicio', 'Registro no encontrado');
			return;
		}
		$persona = $this->persona_model->get_one($servicio->persona_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el registro de persona', 'Registro no encontrado');
			return;
		}
		$tribunal_cuenta = $this->tribunal_cuenta_model->get_one($referente->tribunal_cuenta_id);
		if (empty($tribunal_cuenta)) {
			$this->modal_error('No se encontró el registro de cuenta bancaria', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($tribunal_cuenta->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
//		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
//			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
//			return;
//		}
		$this->load->model('referente_vigencia_model');
		$this->load->model('referente_vigencia_saldo_model');

		$saldo_cuenta = $this->referente_vigencia_saldo_model->get(array('referente_vigencia_id' => $referente_vigencia->id));

		$this->set_model_validation_rules($this->referente_vigencia_saldo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($referente_vigencia_id !== $this->input->post('referente_vigencia_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				if ($saldo_cuenta[0] !== NULL) {
					$trans_ok &= $this->referente_vigencia_saldo_model->update(array(
						'id' => $saldo_cuenta[0]->id,
						'referente_vigencia_id' => $this->input->post('referente_vigencia_id'),
						'saldo' => $this->input->post('saldo'),
						'fecha' => $this->get_date_sql('fecha')
						), FALSE);
				} else {
					$trans_ok &= $this->referente_vigencia_saldo_model->create(array(
						'referente_vigencia_id' => $this->input->post('referente_vigencia_id'),
						'saldo' => $this->input->post('saldo'),
						'fecha' => $this->get_date_sql('fecha')
						), FALSE);
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->referente_vigencia_saldo_model->get_msg());
					redirect("tribunal/referente/ver/$referente_vigencia_id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->referente_vigencia_saldo_model->get_error())
						$errors .= '<br>' . $this->referente_vigencia_saldo_model->get_error();
					$this->session->set_flashdata('error', $errors);
					redirect("tribunal/referente/ver/$referente_vigencia_id", 'refresh');
				}
			}else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("tribunal/referente/ver/$referente_vigencia_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona, TRUE);
		$data['fields_vs'] = $this->build_fields($this->referente_vigencia_saldo_model->fields, $saldo_cuenta[0]);
		$data['servicio'] = $servicio;
		$data['persona'] = $persona;
		$data['escuela'] = $escuela;
		$data['referente'] = $referente;
		$data['referente_vigencia'] = $referente_vigencia;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['txt_btn'] = 'Guardar';
		$data['title'] = 'Saldo de cuenta bancaria';
		$this->load->view('tribunal/referente/referente_modal_cuenta', $data);
	}

	public function modal_fondos_pendientes($referente_vigencia_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $referente_vigencia_id == NULL || !ctype_digit($referente_vigencia_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$referente_vigencia = $this->referente_vigencia_model->get_one($referente_vigencia_id);
		if (empty($referente_vigencia)) {
			$this->modal_error('No se encontró el registro de vigencia del referente', 'Registro no encontrado');
			return;
		}
		$referente = $this->referente_model->get_one($referente_vigencia->referente_id);
		if (empty($referente)) {
			$this->modal_error('No se encontró el registro de referente', 'Registro no encontrado');
			return;
		}
		$servicio = $this->servicio_model->get_one($referente->servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el registro de servicio', 'Registro no encontrado');
			return;
		}
		$persona = $this->persona_model->get_one($servicio->persona_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el registro de persona', 'Registro no encontrado');
			return;
		}
		$tribunal_cuenta = $this->tribunal_cuenta_model->get_one($referente->tribunal_cuenta_id);
		if (empty($tribunal_cuenta)) {
			$this->modal_error('No se encontró el registro de cuenta bancaria', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($tribunal_cuenta->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
//		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
//			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
//			return;
//		}
		$this->load->model('referente_vigencia_model');

		$fondos_pendientes = $this->referente_vigencia_fondos_pendientes_model->get(array('referente_vigencia_id' => $referente_vigencia->id));

		$this->set_model_validation_rules($this->referente_vigencia_fondos_pendientes_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($referente_vigencia_id !== $this->input->post('referente_vigencia_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->referente_vigencia_fondos_pendientes_model->create(array(
					'referente_vigencia_id' => $this->input->post('referente_vigencia_id'),
					'fecha_transferencia' => $this->get_date_sql('fecha_transferencia'),
					'concepto' => $this->input->post('concepto'),
					'importe' => $this->input->post('importe')
					), FALSE);

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->referente_vigencia_fondos_pendientes_model->get_msg());
					redirect("tribunal/referente/ver/$referente_vigencia_id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->referente_vigencia_fondos_pendientes_model->get_error())
						$errors .= '<br>' . $this->referente_vigencia_fondos_pendientes_model->get_error();
					$this->session->set_flashdata('error', $errors);
					redirect("tribunal/referente/ver/$referente_vigencia_id", 'refresh');
				}
			}else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("tribunal/referente/ver/$referente_vigencia_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona, TRUE);
		$data['fields_vf'] = $this->build_fields($this->referente_vigencia_fondos_pendientes_model->fields);
		$data['servicio'] = $servicio;
		$data['persona'] = $persona;
		$data['escuela'] = $escuela;
		$data['referente'] = $referente;
		$data['referente_vigencia'] = $referente_vigencia;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['txt_btn'] = 'Guardar';
		$data['title'] = 'Fondos pendientes';
		$this->load->view('tribunal/referente/referente_modal_fondos', $data);
	}

	public function modal_fondos_pendientes_editar($fondo_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $fondo_id == NULL || !ctype_digit($fondo_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$fondo_pendiente = $this->referente_vigencia_fondos_pendientes_model->get_one($fondo_id);
		if (empty($fondo_pendiente)) {
			$this->modal_error('No se encontró el registro de fondos', 'Registro no encontrado');
			return;
		}
		$referente_vigencia = $this->referente_vigencia_model->get_one($fondo_pendiente->referente_vigencia_id);
		if (empty($referente_vigencia)) {
			$this->modal_error('No se encontró el registro de vigencia del referente', 'Registro no encontrado');
			return;
		}
		$referente = $this->referente_model->get_one($referente_vigencia->referente_id);
		if (empty($referente)) {
			$this->modal_error('No se encontró el registro de referente', 'Registro no encontrado');
			return;
		}
		$tribunal_cuenta = $this->tribunal_cuenta_model->get_one($referente->tribunal_cuenta_id);
		if (empty($tribunal_cuenta)) {
			$this->modal_error('No se encontró el registro de cuenta bancaria', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($tribunal_cuenta->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
//		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
//			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
//			return;
//		}
		$this->load->model('referente_vigencia_model');
		$this->load->model('referente_vigencia_fondos_pendientes_model');

		$this->set_model_validation_rules($this->referente_vigencia_fondos_pendientes_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($referente_vigencia->id !== $this->input->post('referente_vigencia_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->referente_vigencia_fondos_pendientes_model->update(array(
					'id' => $fondo_pendiente->id,
					'fecha_transferencia' => $this->get_date_sql('fecha_transferencia'),
					'concepto' => $this->input->post('concepto'),
					'importe' => $this->input->post('importe')
					), FALSE);

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->referente_vigencia_fondos_pendientes_model->get_msg());
					redirect("tribunal/referente/ver/$referente_vigencia->id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->referente_vigencia_fondos_pendientes_model->get_error())
						$errors .= '<br>' . $this->referente_vigencia_fondos_pendientes_model->get_error();
					$this->session->set_flashdata('error', $errors);
					redirect("tribunal/referente/ver/$referente_vigencia->id", 'refresh');
				}
			}else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("tribunal/referente/ver/$referente_vigencia->id", 'refresh');
			}
		}

		$data['fields_vf'] = $this->build_fields($this->referente_vigencia_fondos_pendientes_model->fields, $fondo_pendiente);
		$data['escuela'] = $escuela;
		$data['referente'] = $referente;
		$data['referente_vigencia'] = $referente_vigencia;
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Fondos pendientes editar';
		$this->load->view('tribunal/referente/referente_modal_fondos', $data);
	}

	public function modal_fondos_pendientes_eliminar($fondo_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $fondo_id == NULL || !ctype_digit($fondo_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$fondo_pendiente = $this->referente_vigencia_fondos_pendientes_model->get_one($fondo_id);
		if (empty($fondo_pendiente)) {
			$this->modal_error('No se encontró el registro de fondos', 'Registro no encontrado');
			return;
		}
		$referente_vigencia = $this->referente_vigencia_model->get_one($fondo_pendiente->referente_vigencia_id);
		if (empty($referente_vigencia)) {
			$this->modal_error('No se encontró el registro de vigencia del referente', 'Registro no encontrado');
			return;
		}
		$referente = $this->referente_model->get_one($referente_vigencia->referente_id);
		if (empty($referente)) {
			$this->modal_error('No se encontró el registro de referente', 'Registro no encontrado');
			return;
		}
		$tribunal_cuenta = $this->tribunal_cuenta_model->get_one($referente->tribunal_cuenta_id);
		if (empty($tribunal_cuenta)) {
			$this->modal_error('No se encontró el registro de cuenta bancaria', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($tribunal_cuenta->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
//		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
//			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
//			return;
//		}
		$this->load->model('referente_vigencia_model');
		$this->load->model('referente_vigencia_fondos_pendientes_model');

//		$this->set_model_validation_rules($this->referente_vigencia_fondos_pendientes_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($referente_vigencia->id !== $this->input->post('referente_vigencia_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->referente_vigencia_fondos_pendientes_model->delete(array(
				'id' => $fondo_pendiente->id
			));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->referente_vigencia_fondos_pendientes_model->get_msg());
				redirect("tribunal/referente/ver/$referente_vigencia->id", 'refresh');
			} else {
				$errors = 'Ocurrió un error al intentar actualizar.';
				if ($this->referente_vigencia_fondos_pendientes_model->get_error())
					$errors .= '<br>' . $this->referente_vigencia_fondos_pendientes_model->get_error();
				$this->session->set_flashdata('error', $errors);
				redirect("tribunal/referente/ver/$referente_vigencia->id", 'refresh');
			}
		}

		$data['fields_vf'] = $this->build_fields($this->referente_vigencia_fondos_pendientes_model->fields, $fondo_pendiente, TRUE);
		$data['escuela'] = $escuela;
		$data['fondo_pendiente'] = $fondo_pendiente;
		$data['referente'] = $referente;
		$data['referente_vigencia'] = $referente_vigencia;
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Fondos pendientes eliminar';
		$this->load->view('tribunal/referente/referente_modal_fondos', $data);
	}

	public function cerrar_periodo($referente_vigencia_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $referente_vigencia_id == NULL || !ctype_digit($referente_vigencia_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$referente_vigencia = $this->referente_vigencia_model->get_one($referente_vigencia_id);
		if (empty($referente_vigencia)) {
			$this->modal_error('No se encontró el registro de vigencia del referente', 'Registro no encontrado');
			return;
		}
		$referente = $this->referente_model->get_one($referente_vigencia->referente_id);
		if (empty($referente)) {
			$this->modal_error('No se encontró el registro de referente', 'Registro no encontrado');
			return;
		}
		$servicio = $this->servicio_model->get_one($referente->servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el registro del servicio', 'Registro no encontrado');
			return;
		}
		$persona = $this->persona_model->get_one($servicio->persona_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el registro de persona', 'Registro no encontrado');
			return;
		}
		$tribunal_cuenta = $this->tribunal_cuenta_model->get_one($referente->tribunal_cuenta_id);
		if (empty($tribunal_cuenta)) {
			$this->modal_error('No se encontró el registro de cuenta bancaria', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($tribunal_cuenta->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
//		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
//			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
//			return;
//		}

		$saldo = $this->referente_vigencia_saldo_model->get(array('referente_vigencia_id' => $referente_vigencia->id));
		$ultimo_cheque = $this->referente_vigencia_ultimo_cheque_model->get(array('referente_vigencia_id' => $referente_vigencia->id));
		$fondos_pendientes = $this->referente_vigencia_fondos_pendientes_model->get(array('referente_vigencia_id' => $referente_vigencia->id));

		$this->form_validation->set_rules("fecha_hasta", 'fecha_hasta', 'date');
		if (isset($_POST) && !empty($_POST)) {
			if ($referente_vigencia_id !== $this->input->post('referente_vigencia_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				if (!empty($saldo) && !empty($ultimo_cheque)) {
					$trans_ok &= $this->referente_vigencia_model->update(array(
						'id' => $referente_vigencia->id,
						'fecha_hasta' => $this->get_date_sql('fecha_hasta')
						), FALSE);
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if (empty($saldo)) {
						$errors .= '<br>Por favor, completar los campos correspontientes a Saldo de cuenta bancaria.';
					}
					if (empty($ultimo_cheque)) {
						$errors .= '<br>Por favor, completar los campos correspontientes a Último cheque.';
					}
					$this->session->set_flashdata('error', $errors);
					redirect("tribunal/referente/ver/$referente_vigencia_id", 'refresh');
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->referente_vigencia_model->get_msg());
					redirect("tribunal/referente/ver/$referente_vigencia_id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->referente_vigencia_model->get_error())
						$errors .= '<br>' . $this->referente_vigencia_model->get_error();
					$this->session->set_flashdata('error', $errors);
					redirect("tribunal/referente/ver/$referente_vigencia_id", 'refresh');
				}
			}else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("tribunal/referente/ver/$referente_vigencia_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona, TRUE);
		$data['fields_vs'] = $this->build_fields($this->referente_vigencia_saldo_model->fields, $saldo[0], TRUE);
		$data['fields_vc'] = $this->build_fields($this->referente_vigencia_ultimo_cheque_model->fields, $ultimo_cheque[0], TRUE);
		$data['servicio'] = $servicio;
		$data['fondos_pendientes'] = $fondos_pendientes;
		$data['persona'] = $persona;
		$data['saldo'] = $saldo;
		$data['ultimo_cheque'] = $ultimo_cheque;
		$data['escuela'] = $escuela;
		$data['referente'] = $referente;
		$data['referente_vigencia'] = $referente_vigencia;
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['txt_btn'] = 'Cerrar';
		$data['title'] = 'Cerrar periodo del referente ';
		$this->load->view('tribunal/referente/referente_modal_cerrar_periodo', $data);
	}

	public function modal_eliminar_referente($referente_vigencia_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $referente_vigencia_id == NULL || !ctype_digit($referente_vigencia_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$referente_vigencia = $this->referente_vigencia_model->get_one($referente_vigencia_id);
		if (empty($referente_vigencia)) {
			$this->modal_error('No se encontró el registro de vigencia del referente', 'Registro no encontrado');
			return;
		}
		$referente = $this->referente_model->get_one($referente_vigencia->referente_id);
		if (empty($referente)) {
			$this->modal_error('No se encontró el registro de referente', 'Registro no encontrado');
			return;
		}
		$servicio = $this->servicio_model->get_one($referente->servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el registro de servicio', 'Registro no encontrado');
			return;
		}
		$regimen = $this->regimen_model->get_one($servicio->regimen_id);
		if (empty($regimen)) {
			$this->modal_error('No se encontró el registro de regimen', 'Registro no encontrado');
			return;
		}
		$persona = $this->persona_model->get_one($servicio->persona_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el registro de persona', 'Registro no encontrado');
			return;
		}
		$tribunal_cuenta = $this->tribunal_cuenta_model->get_one($referente->tribunal_cuenta_id);
		if (empty($tribunal_cuenta)) {
			$this->modal_error('No se encontró el registro de cuenta bancaria', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($tribunal_cuenta->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
//		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
//			$this->modal_error('No tiene permisos para acceder a la escuela', 'Registro no encontrado');
//			return;
//		}

		$saldo = $this->referente_vigencia_saldo_model->get(array('referente_vigencia_id' => $referente_vigencia->id));
		$ultimo_cheque = $this->referente_vigencia_ultimo_cheque_model->get(array('referente_vigencia_id' => $referente_vigencia->id));
		$fondos_pendientes = $this->referente_vigencia_fondos_pendientes_model->get(array('referente_vigencia_id' => $referente_vigencia->id));

		if (isset($_POST) && !empty($_POST)) {
			if ($referente_vigencia_id !== $this->input->post('referente_vigencia_id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$trans_ok &= $this->referente_vigencia_model->delete(array(
				'id' => $referente_vigencia_id
				), FALSE);
			$referente_vigente_existente = $this->referente_vigencia_model->get(array('referente_id' => $referente->id));
			if (empty($referente_vigente_existente)) {
				$trans_ok &= $this->referente_model->delete(array(
					'id' => $referente->id
					), FALSE);
			}
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->referente_model->get_msg());
				redirect("tribunal/escritorio/escuela/$escuela->id", 'refresh');
			} else {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', $this->referente_model->get_error());
				redirect("tribunal/escritorio/escuela/$escuela->id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona, TRUE);
		$data['fields_vs'] = $this->build_fields($this->referente_vigencia_saldo_model->fields, $saldo[0], TRUE);
		$data['fields_vc'] = $this->build_fields($this->referente_vigencia_ultimo_cheque_model->fields, $ultimo_cheque[0], TRUE);
		$data['servicio'] = $servicio;
		$data['persona'] = $persona;
		$data['escuela'] = $escuela;
		$data['referente'] = $referente;
		$data['fondos_pendientes'] = $fondos_pendientes;
		$data['ultimo_cheque'] = $ultimo_cheque;
		$data['saldo'] = $saldo;
		$data['referente_vigencia'] = $referente_vigencia;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar referente';
		$this->load->view('tribunal/referente/referente_modal_eliminar', $data);
	}
}
/* End of file Referente.php */
/* Location: ./application/modules/tribunal/controllers/Referente.php */
