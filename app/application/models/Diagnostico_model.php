<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Diagnostico_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'diagnostico';
		$this->msg_name = 'Diagnóstico';
		$this->id_name = 'id';
		$this->columnas = array('id', 'detalle');
		$this->fields = array(
			'detalle' => array('label' => 'Detalle', 'maxlength' => '100', 'required' => TRUE)
		);
		$this->requeridos = array('detalle');
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
		if ($this->db->where('diagnostico_id', $delete_id)->count_all_results('alumno_derivacion') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a alumno de derivacion.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Diagnostico_model.php */
/* Location: ./application/models/Diagnostico_model.php */