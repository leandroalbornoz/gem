<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ASISTENCIA_DIVISION));
		//@TODO ver permisos
	}

	public function get_listar_postulantes() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('persona_model');
		$this->load->model('preinscripciones/preinscripcion_model');
		$this->load->model('preinscripciones/preinscripcion_alumno_model');

		$escuela = $this->input->get('escuela_id');

		if (!empty($escuela)) {
			$preinscripcion = $this->preinscripcion_model->get_by_escuela($escuela, 2018);
			if (!empty($preinscripcion)) {
				$personas_listar = $this->preinscripcion_alumno_model->get_alumnos_postulantes($preinscripcion->id);
			}
		}
		if (!empty($personas_listar)) {
			echo json_encode(array('status' => 'success', 'personas_listar' => $personas_listar));
		} else {
			echo json_encode(array('status' => 'error'));
		}
	}

	public function get_listar_preinscriptos() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('persona_model');
		$this->load->model('preinscripciones/preinscripcion_alumno_model');

		$documento = $this->input->get('documento');
		$ciclo_lectivo = $this->input->get('ciclo_lectivo');
		if (!empty($documento)) {
			$persona = $this->persona_model->get_by_documento($documento);
			$personas_listar = $this->preinscripcion_alumno_model->get_consulta_preinscripcion($persona->id, $ciclo_lectivo);
			if (empty($personas_listar)) {
				$personas_listar = $this->persona_model->get(array(
					'documento' => $documento,
					'join' => array(
						array('documento_tipo', 'documento_tipo.id=persona.documento_tipo_id', 'left', array('documento_tipo.descripcion_corta')),
					)
				));
			}
		}
		if (!empty($personas_listar)) {
			echo json_encode(array('status' => 'success', 'personas_listar' => $personas_listar));
		} else {
			echo json_encode(array('status' => 'error'));
		}
	}
}
/* End of file Ajax.php */
	/* Location: ./application/modules/preinscripciones/controllers/Ajax.php */
