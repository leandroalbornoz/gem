<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Desinfeccion extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->load->model('elecciones/eleccion_desinfeccion_model');
		$this->load->model('elecciones/eleccion_desinfeccion_persona_model');
		$this->load->model('persona_model');
		$this->load->model('servicio_model');
		$this->load->model('servicio_funcion_model');
		$this->load->model('cargo_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE_CURSADA));
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_ESCUELA_ALUM))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'elecciones/desinfeccion';
	}

	public function ver($eleccion_id = NULL, $escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $eleccion_id == NULL || !ctype_digit($eleccion_id) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$desinfeccion = $this->eleccion_desinfeccion_model->get_eleccion_desinfeccion($eleccion_id, $escuela_id);
		if (empty($desinfeccion)) {
			$this->session->set_flashdata('error', 'La escuela no participa del operativo de desinfección por elecciones');
			redirect("escritorio", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$desinfeccion->escuela = $escuela->nombre_largo;
		$data['fecha'] = date('Y-m-d');
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->eleccion_desinfeccion_model->fields, $desinfeccion, TRUE);
		$data['desinfeccion'] = $desinfeccion;
		$data['escuela'] = $escuela;
		$data['celadores'] = $this->eleccion_desinfeccion_persona_model->get_celadores_escuela($eleccion_id, $escuela_id);
		$data['txt_btn'] = NULL;
		$data['class'] = array('ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver Desinfección';

		$this->load_template("elecciones/desinfeccion/desinfeccion_abm", $data);
	}

	public function supervision_ver($eleccion_id = NULL, $escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $eleccion_id == NULL || !ctype_digit($eleccion_id) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$desinfeccion = $this->eleccion_desinfeccion_model->get_eleccion_desinfeccion($eleccion_id, $escuela_id);
		if (empty($desinfeccion)) {
			$this->session->set_flashdata('error', 'La escuela no participa del operativo de desinfección por elecciones');
			redirect("escritorio", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$desinfeccion->escuela = $escuela->nombre_largo;
		$data['fecha'] = date('Y-m-d');
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->eleccion_desinfeccion_model->fields, $desinfeccion, TRUE);
		$data['desinfeccion'] = $desinfeccion;
		$data['escuela'] = $escuela;
		$data['celadores'] = $this->eleccion_desinfeccion_persona_model->get_celadores_escuela($eleccion_id, $escuela_id);
		$data['txt_btn'] = NULL;
		$data['class'] = array('ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver Desinfección';

		$this->load_template("elecciones/desinfeccion/desinfeccion_supervision_ver", $data);
	}

	public function modal_buscar_celador($desinfeccion_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $desinfeccion_id == NULL || !ctype_digit($desinfeccion_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$desinfeccion = $this->eleccion_desinfeccion_model->get_one($desinfeccion_id);
		if (empty($desinfeccion)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($desinfeccion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$model_busqueda = new stdClass();
		$model_busqueda->fields = array(
			'd_cuil' => array('label' => 'Cuil', 'maxlength' => '14', 'onkeypress' => 'validar(event);'),
			'persona_seleccionada' => array('label' => '', 'type' => 'integer', 'required' => TRUE),
		);
		$this->set_model_validation_rules($model_busqueda);

		$data['fields'] = $this->build_fields($model_busqueda->fields);
		$data['escuela'] = $escuela;
		$data['desinfeccion'] = $desinfeccion;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Buscar celador a agregar';
		$this->load->view('elecciones/desinfeccion/desinfeccion_modal_buscar_celador', $data);
	}

	public function agregar_celador($desinfeccion_id = NULL, $persona_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $desinfeccion_id == NULL || !ctype_digit($desinfeccion_id) || $persona_id == NULL || !ctype_digit($persona_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$desinfeccion = $this->eleccion_desinfeccion_model->get_one($desinfeccion_id);
		if (empty($desinfeccion)) {
			show_error('No se encontró el registro de la desinfección', 500, 'Registro no encontrado');
		}
		if ($desinfeccion->celadores_permitidos <= $desinfeccion->celadores_asignados) {
			$this->session->set_flashdata('error', 'Ya ha asignado la cantidad permitida de celadores en la escuela.');
			redirect("elecciones/desinfeccion/ver/$desinfeccion->eleccion_id/$desinfeccion->escuela_id", 'refresh');
		}
		if (!empty($desinfeccion->fecha_cierre)) {
			$this->session->set_flashdata('error', 'La planilla de carga ya ha sido cerrada.');
			redirect("elecciones/desinfeccion/ver/$desinfeccion->eleccion_id/$desinfeccion->escuela_id", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($desinfeccion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$persona = $this->persona_model->get_one($persona_id);
		if (empty($persona)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		$this->db->trans_begin();
		$trans_ok = TRUE;
		$trans_ok &= $this->eleccion_desinfeccion_persona_model->create(array(
			'eleccion_desinfeccion_id' => $desinfeccion->id,
			'persona_id' => $persona_id,
			'fecha_carga' => date('Y-m-d H:i:s'),
			'estado' => 'Activo',
			), FALSE);
		if ($this->db->trans_status() && $trans_ok) {
			$this->db->trans_commit();
			$this->session->set_flashdata('message', $this->eleccion_desinfeccion_persona_model->get_msg());
			redirect("elecciones/desinfeccion/ver/$desinfeccion->eleccion_id/$desinfeccion->escuela_id", 'refresh');
		} else {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', $this->eleccion_desinfeccion_persona_model->get_error());
			redirect("elecciones/desinfeccion/ver/$desinfeccion->eleccion_id/$desinfeccion->escuela_id", 'refresh');
		}
	}

	public function eliminar_celador($desinfeccion_id = NULL, $desinfeccion_persona_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $desinfeccion_id == NULL || !ctype_digit($desinfeccion_id) || $desinfeccion_persona_id == NULL || !ctype_digit($desinfeccion_persona_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$desinfeccion = $this->eleccion_desinfeccion_model->get_one($desinfeccion_id);
		if (empty($desinfeccion)) {
			show_error('No se encontró el registro de la desinfección', 500, 'Registro no encontrado');
		}
		if (!empty($desinfeccion->fecha_cierre)) {
			$this->session->set_flashdata('error', 'La planilla de carga ya ha sido cerrada.');
			redirect("elecciones/desinfeccion/ver/$desinfeccion->eleccion_id/$desinfeccion->escuela_id", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($desinfeccion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$desinfeccion_persona = $this->eleccion_desinfeccion_persona_model->get_one($desinfeccion_persona_id);
		if (empty($desinfeccion_persona) || $desinfeccion_persona->eleccion_desinfeccion_id !== $desinfeccion->id) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		$celador = $this->eleccion_desinfeccion_persona_model->get_celador($desinfeccion_persona->persona_id);
		if (isset($_POST) && !empty($_POST)) {
			if ($desinfeccion->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$trans_ok &= $this->eleccion_desinfeccion_persona_model->update(array(
				'id' => $desinfeccion_persona->id,
				'fecha_anulacion' => date('Y-m-d H:i:s'),
				'estado' => 'Anulado'
				), FALSE);
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Celador eliminado de carga correctamente');
				redirect("elecciones/desinfeccion/ver/$desinfeccion->eleccion_id/$desinfeccion->escuela_id", 'refresh');
			} else {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', $this->eleccion_desinfeccion_persona_model->get_error());
				redirect("elecciones/desinfeccion/ver/$desinfeccion->eleccion_id/$desinfeccion->escuela_id", 'refresh');
			}
		}
		$desinfeccion->escuela = $escuela->nombre_largo;
		$data['fields'] = $this->build_fields($this->eleccion_desinfeccion_persona_model->fields, $celador, TRUE);
		$data['desinfeccion'] = $desinfeccion;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar celador';
		$this->load->view('elecciones/desinfeccion/desinfeccion_modal_eliminar_celador', $data);
	}

	public function cerrar($desinfeccion_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $desinfeccion_id == NULL || !ctype_digit($desinfeccion_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$desinfeccion = $this->eleccion_desinfeccion_model->get_one($desinfeccion_id);
		if (empty($desinfeccion)) {
			show_error('No se encontró el registro de la desinfección', 500, 'Registro no encontrado');
		}
		if (!empty($desinfeccion->fecha_cierre)) {
			$this->session->set_flashdata('error', 'La planilla de celadores ya ha sido cerrada.');
			redirect("elecciones/desinfeccion/ver/$desinfeccion->eleccion_id/$desinfeccion->escuela_id", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($desinfeccion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->db->trans_begin();
		$trans_ok = TRUE;
		$trans_ok &= $this->eleccion_desinfeccion_model->update(array(
			'id' => $desinfeccion->id,
			'fecha_cierre' => date('Y-m-d H:i:s')
			), FALSE);
		if ($this->db->trans_status() && $trans_ok) {
			$this->db->trans_commit();
			$this->session->set_flashdata('message', 'Se cerró la carga correctamente');
			redirect("elecciones/desinfeccion/ver/$desinfeccion->eleccion_id/$desinfeccion->escuela_id", 'refresh');
		} else {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', $this->eleccion_desinfeccion_persona_model->get_error());
			redirect("elecciones/desinfeccion/ver/$desinfeccion->eleccion_id/$desinfeccion->escuela_id", 'refresh');
		}
	}

	public function imprimir_pdf($desinfeccion_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $desinfeccion_id == NULL || !ctype_digit($desinfeccion_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$desinfeccion = $this->eleccion_desinfeccion_model->get_one($desinfeccion_id);
		if (empty($desinfeccion)) {
			show_error('No se encontró el registro de la desinfección', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($desinfeccion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$celadores = $this->eleccion_desinfeccion_persona_model->get_celadores_escuela($desinfeccion->eleccion_id, $desinfeccion->escuela_id);

		$data['error'] = $this->session->flashdata('error');
		$data['celadores'] = $celadores;
		$data['escuela'] = $escuela;
		$data['desinfeccion'] = $desinfeccion;

		$fecha_actual = date('d/m/Y');
		$content = $this->load->view('elecciones/desinfeccion/elecciones_desinfeccion_imprimir_pdf', $data, TRUE);
		$this->load->helper('mpdf');
		$watermark = '';
		exportMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Planilla de Desinfección', 'Planilla de Desinfección - Esc. "' . trim($escuela->nombre) . '" Nº ' . $escuela->numero . ' " - Fecha actual: ' . $fecha_actual, '|{PAGENO} de {nb}|', '', $watermark, 'I', FALSE, FALSE);
	}

	private function supervision_imprimir_pdf($eleccion_id = NULL, $supervision_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $eleccion_id == NULL || !ctype_digit($eleccion_id) || $supervision_id == NULL || !ctype_digit($supervision_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuelas = $this->eleccion_desinfeccion_model->get_by_supervision($eleccion_id, $supervision_id);
		$data['error'] = $this->session->flashdata('error');
		$data['escuelas'] = $escuelas;
		$fecha_actual = date('d/m/Y');
		$content = $this->load->view('elecciones/desinfeccion/elecciones_desinfeccion_supervision_imprimir_pdf', $data, TRUE);
		$this->load->helper('mpdf');
		$watermark = '';
		exportMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Planilla de Desinfección', 'Planilla de Desinfección Supervisión - Fecha actual: ' . $fecha_actual, '|{PAGENO} de {nb}|', '', $watermark, 'I', FALSE, FALSE);
	}
}