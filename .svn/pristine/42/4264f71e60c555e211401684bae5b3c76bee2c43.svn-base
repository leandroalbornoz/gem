<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Suple_estado_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'suple_estado';
		$this->msg_name = 'Estado';
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
		if ($this->db->where('estado_id', $delete_id)->count_all_results('suple_persona') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a suple de persona.');
			return FALSE;
		}
		if ($this->db->where('estado_id', $delete_id)->count_all_results('suple_persona_auditoria') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a suple de persona de auditoria.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Suple_estado_model.php */
/* Location: ./application/modules/suplementarias/models/Suple_estado_model.php */