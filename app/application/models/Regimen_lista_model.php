<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Regimen_lista_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'regimen_lista';
		$this->msg_name = 'Lista de regímenes permitidos';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '50', 'required' => TRUE),
			'regimenes' => array('label' => 'Regímenes', 'input_type' => 'combo', 'id_name' => 'nivel', 'type' => 'multiple')
		);
		$this->requeridos = array('descripcion');
		//$this->unicos = array();
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
/* End of file Regimen_lista_model.php */
/* Location: ./application/models/Regimen_lista_model.php */