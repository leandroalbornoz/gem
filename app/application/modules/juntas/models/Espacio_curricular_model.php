<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Espacio_curricular_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'espacio_curricular';
		$this->msg_name = 'Espacio curricular';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'materia_id');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'readonly' => TRUE)
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
/* Location: ./application/modules/bono/models/Persona_idoneidad_model.php */