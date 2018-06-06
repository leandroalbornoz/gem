<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modalidad_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'modalidad';
		$this->msg_name = 'Modalidad';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion');
		$this->requeridos = array('id', 'descripcion');
	}
	/*
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */

	protected function _can_delete($delete_id) {
		return TRUE;
	}
}
/* End of file Antiguedad_tipo_model.php */
/* Location: ./application/modules/bono/models/Modalidad_model.php */