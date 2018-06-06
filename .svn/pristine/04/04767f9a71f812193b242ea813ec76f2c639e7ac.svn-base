<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Linea_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'linea';
		$this->msg_name = 'Linea';
		$this->id_name = 'id';
		$this->columnas = array('id', 'nombre');
		$this->fields = array(
			'nombre' => array('label' => 'Nombre', 'maxlength' => '255', 'required' => TRUE)
		);
		$this->requeridos = array('nombre');
		//$this->unicos = array();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('linea_id', $delete_id)->count_all_results('nivel') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a nivel.');
			return FALSE;
		}
		if ($this->db->where('linea_id', $delete_id)->count_all_results('supervision') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a supervision.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Linea_model.php */
/* Location: ./application/models/Linea_model.php */