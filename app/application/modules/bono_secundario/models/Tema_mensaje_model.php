<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tema_mensaje_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'tema_mensaje';
		$this->msg_name = 'Tema';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '45', 'required' => TRUE)
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
		if ($this->db->where('id', $delete_id)->count_all_results('persona') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a persona.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Sexo_model.php */
/* Location: ./application/models/tema_mensaje_model.php */