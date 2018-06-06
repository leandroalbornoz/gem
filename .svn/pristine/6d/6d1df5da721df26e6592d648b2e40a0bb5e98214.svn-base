<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Matricula_tipo_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'matricula_tipo';
		$this->msg_name = 'Tipo de Matrículas';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'readonly' => TRUE),
		);
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
/* Location: ./application/modules/bono/models/Matricula_tipo_model.php */