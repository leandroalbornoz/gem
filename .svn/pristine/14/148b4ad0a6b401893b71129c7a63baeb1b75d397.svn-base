<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tem_personal_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'tem_personal';
		$this->msg_name = 'Tutores TEM';
		$this->id_name = 'id';
		$this->columnas = array('id', 'persona', 'documento', 'cargos_jerarquicos', 'cargos_no_jerarquicos', 'horas_superior', 'horas_no_superior', 'horas_disponibles');
		$this->fields = array(
			
		);
		$this->requeridos = array('persona', 'documento', 'cargos_jerarquicos', 'cargos_no_jerarquicos', 'horas_superior', 'horas_no_superior', 'horas_disponibles');
		//$this->unicos = array();
		$this->default_join = array();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		return TRUE;
	}
}
/* End of file Tem_personal_model.php */
/* Location: ./application/modules/tem/models/Tem_personal_model.php */