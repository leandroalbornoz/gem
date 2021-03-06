<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE_CURSADA));
		$this->roles_admin = array(ROL_ADMIN);
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ESCUELA_ALUM));
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_ESCUELA_ALUM))) {
			$this->edicion = FALSE;
		}
	}

	public function get_escuelas($search = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!empty($search)) {
			$this->load->model('tem_model');
			echo json_encode(array('status' => 'success', 'escuelas' => $this->tem_model->buscar_escuelas($search)));
		} else {
			echo json_encode(array('status' => 'error', 'error' => 'No se introdujo un término de búsqueda'));
		}
	}
}
/* End of file Ajax.php */
	/* Location: ./application/modules/tem/controllers/Ajax.php */