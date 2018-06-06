<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Caracteristica_tipo_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'caracteristica_tipo';
		$this->msg_name = 'Tipo de característica';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'entidad');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '100', 'required' => TRUE),
		);
		$this->requeridos = array('descripcion', 'entidad');
		//$this->unicos = array();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('caracteristica_tipo_id', $delete_id)->count_all_results('caracteristica') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a caracteristica.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Caracteristica_tipo_model.php */
/* Location: ./application/models/Caracteristica_tipo_model.php */