<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Delegacion_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'delegacion';
		$this->msg_name = 'Delegacion';
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
		if ($this->db->where('delegacion_id', $delete_id)->count_all_results('escuela') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a escuela.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Delegacion_model.php */
/* Location: ./application/models/Delegacion_model.php */