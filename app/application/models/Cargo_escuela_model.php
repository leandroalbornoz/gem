<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cargo_escuela_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'cargo_escuela';
		$this->msg_name = 'Cargo compartido';
		$this->id_name = 'id';
		$this->columnas = array('id', 'cargo_id', 'escuela_id', 'cantidad_horas');
		$this->requeridos = array('cargo_id', 'escuela_id', 'cantidad_horas');
		//$this->unicos = array();
		$this->fields = array(
			'escuela' => array('label' => 'Escuela', 'input_type' => 'combo', 'required' => TRUE),
			'cantidad_horas' => array('label' => 'Cantidad Hs.', 'type' => 'integer', 'required' => TRUE),
		);
		$this->default_join = array(
			array('escuela', 'escuela.id=cargo_escuela.escuela_id', 'left', array("CONCAT(numero, CASE WHEN anexo=0 THEN ' ' ELSE CONCAT('/',anexo,' ') END, nombre) as escuela"))
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
/* End of file Cargo_escuela_model.php */
/* Location: ./application/models/Cargo_escuela_model.php */