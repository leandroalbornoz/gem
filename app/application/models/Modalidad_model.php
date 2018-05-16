<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modalidad_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'modalidad';
		$this->msg_name = 'Modalidad';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripcion', 'maxlength' => '45', 'required' => TRUE)
		);
		$this->requeridos = array('descripcion');
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
/* End of file Modalidad_model.php */
/* Location: ./application/models/Modalidad_model.php */