<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Regimen_lista_regimen_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'regimen_lista_regimen';
		$this->msg_name = 'Régimen de lista';
		$this->id_name = 'id';
		$this->columnas = array('id', 'regimen_lista_id', 'regimen_id');
		$this->requeridos = array('regimen_lista_id', 'regimen_id');
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
/* End of file Regimen_lista_regimen_model.php */
/* Location: ./application/models/Regimen_lista_regimen_model.php */