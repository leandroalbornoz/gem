<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Nivel_estudio_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'nivel_estudio';
		$this->msg_name = 'Nivel de estudios';
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
		if ($this->db->where('nivel_estudio_id', $delete_id)->count_all_results('persona') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a persona.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Nivel_estudio_model.php */
/* Location: ./application/models/Nivel_estudio_model.php */