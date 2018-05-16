<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tribunal_cuenta_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'tribunal_cuenta';
		$this->msg_name = 'Tribunal cuenta';
		$this->id_name = 'id';
		$this->columnas = array('id', 'escuela_id', 'descripcion_cuenta', 'numero_cuenta');
		$this->fields = array(
			'descripcion_cuenta' => array('label' => 'Nombre de cuenta', 'type' => 'text', 'required' => TRUE),
			'numero_cuenta' => array('label' => 'Número de cuenta', 'type' => 'text', 'required' => TRUE)
		);
		$this->requeridos = array('escuela_id');
		//$this->unicos = array();
		$this->default_join = array(
		);
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
/* End of file Tribunal_cuenta_model.php */
/* Location: ./application/modules/tribunal/models/Tribunal_cuenta_model.php */