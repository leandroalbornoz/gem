<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Documento_tipo_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'documento_tipo';
		$this->msg_name = 'Tipo de documento';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion_corta', 'descripcion');
		$this->fields = array(
			'descripcion_corta' => array('label' => 'Descripción Corta', 'maxlength' => '5', 'required' => TRUE),
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '45', 'required' => TRUE)
		);
		$this->requeridos = array('descripcion_corta', 'descripcion');
		//$this->unicos = array();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('documento_tipo_id', $delete_id)->count_all_results('persona') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a persona.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Documento_tipo_model.php */
/* Location: ./application/models/Documento_tipo_model.php */