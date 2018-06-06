<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_tribunal extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL,ROL_ASISTENCIA_DIVISION)); //@TODO ver permisos
	}

	public function get_listar_referentes() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('persona_model');
		$this->load->model('tribunal/referente_model');
		$cuil = $this->input->get('cuil');
		$escuela_id = $this->input->get('escuela_id');
		$cuenta_id = $this->input->get('cuenta_id');
		if (!empty($cuil) && !EMPTY($escuela_id)) {
			$personas_listar = $this->referente_model->get_referentes($cuil, $escuela_id, $cuenta_id);
		}
		if (!empty($personas_listar)) {
			echo json_encode(array('status' => 'success', 'personas_listar' => $personas_listar));
		} else {
			echo json_encode(array('status' => 'error'));
		}
	}
}