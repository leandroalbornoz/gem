<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Requiere_matricula_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'requiere_matricula';
		$this->msg_name = 'Requiere Matrícula';
		$this->id_name = 'id';
		$this->columnas = array('id', 'espacio_id');
		$this->requeridos = array();
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
/* End of file Posgrado_tipo_model.php */
/* Location: ./application/modules/bono/models/Requiere_matricula_model.php */