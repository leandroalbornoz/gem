<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Suple_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'suple';
		$this->msg_name = 'Suplementaria';
		$this->id_name = 'id';
		$this->columnas = array('id', 'motivo', 'fecha_desde', 'fecha_hasta', 'expediente');
		$this->fields = array(
			'motivo' => array('label' => 'Motivo', 'maxlength' => '150', 'required' => TRUE),
			'fecha_desde' => array('label' => 'Fecha desde', 'type' => 'date', 'required' => TRUE),
			'fecha_hasta' => array('label' => 'Fecha hasta', 'type' => 'date', 'required' => TRUE),
			'expediente' => array('label' => 'Expediente', 'maxlength' => '50')
		);
		$this->requeridos = array('motivo', 'fecha_desde', 'fecha_hasta');
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
		if ($this->db->where('suple_id', $delete_id)->count_all_results('suple_persona') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a suple de persona.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Suple_model.php */
/* Location: ./application/modules/suplementarias/models/Suple_model.php */