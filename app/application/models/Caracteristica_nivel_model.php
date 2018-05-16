<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Caracteristica_nivel_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'caracteristica_nivel';
		$this->msg_name = 'Característica de nivel';
		$this->id_name = 'id';
		$this->columnas = array('id', 'nivel_id', 'caracteristica_id');
		$this->fields = array(
			'nivel' => array('label' => 'Nivel', 'input_type' => 'combo', 'id_name' => 'nivel_id', 'required' => TRUE),
			'caracteristica' => array('label' => 'Característica', 'input_type' => 'combo', 'id_name' => 'caracteristica_id', 'required' => TRUE)
		);
		$this->requeridos = array('nivel_id', 'caracteristica_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('nivel', 'nivel.id = caracteristica_nivel.nivel_id', 'left', array('nivel.descripcion as nivel'))
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
/* End of file Caracteristica_tipo_model.php */
/* Location: ./application/models/Caracteristica_tipo_model.php */