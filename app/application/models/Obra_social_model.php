<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Obra_social_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'obra_social';
		$this->msg_name = 'Obra social';
		$this->id_name = 'id';
		$this->columnas = array('id', 'codigo', 'descripcion', 'descripcion_corta');
		$this->fields = array(
			'codigo' => array('label' => 'Codigo', 'maxlength' => '10'),
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '255', 'required' => TRUE),
			'descripcion_corta' => array('label' => 'Descripción Corta', 'maxlength' => '20', 'required' => TRUE)
		);
		$this->requeridos = array('descripcion', 'descripcion_corta');
		//$this->unicos = array();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('obra_social_id', $delete_id)->count_all_results('persona') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a persona.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Obra_social_model.php */
/* Location: ./application/models/Obra_social_model.php */