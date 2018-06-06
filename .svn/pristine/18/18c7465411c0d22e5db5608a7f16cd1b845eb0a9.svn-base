<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Autoridad_tipo_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'autoridad_tipo';
		$this->msg_name = 'Tipo de autoridad';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripcion', 'maxlength' => '45', 'required' => TRUE)
		);
		$this->requeridos = array('descripcion');
		//$this->unicos = array();
		$this->default_join = array();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('autoridad_tipo_id', $delete_id)->count_all_results('escuela_autoridad') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a escuela de autoridad.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Autoridad_tipo_model.php */
/* Location: ./application/models/Autoridad_tipo_model.php */