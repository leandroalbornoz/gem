<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Provincia_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'provincia';
		$this->msg_name = 'Provincia';
		$this->id_name = 'id';
		$this->columnas = array('id', 'codigo', 'descripcion');
		$this->fields = array(
			'codigo' => array('label' => 'Código', 'maxlength' => '2', 'required' => TRUE),
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '45', 'required' => TRUE)
		);
		$this->requeridos = array('codigo', 'descripcion');
		//$this->unicos = array();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('provincia_id', $delete_id)->count_all_results('departamento') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a departamento.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Provincia_model.php */
/* Location: ./application/models/Provincia_model.php */