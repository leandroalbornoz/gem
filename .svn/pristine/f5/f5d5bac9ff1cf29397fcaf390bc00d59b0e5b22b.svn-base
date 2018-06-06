<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Aprender_operativo_tipo_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'aprender_operativo_tipo';
		$this->msg_name = 'Tipo de operativo';
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
		if ($this->db->where('operativo_tipo_id', $delete_id)->count_all_results('aprender_operativo') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a aprender de operativo.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Aprender_operativo_tipo_model.php */
/* Location: ./application/models/Aprender_operativo_tipo_model.php */