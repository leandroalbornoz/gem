<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Referente_vigencia_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'referente_vigencia';
		$this->msg_name = 'Referente vigencia';
		$this->id_name = 'id';
		$this->columnas = array('id', 'referente_id', 'fecha_desde', 'fecha_hasta', 'fecha_hasta_confirmacion');
		$this->fields = array(
			'fecha_desde' => array('label' => 'Vencimiento*', 'type' => 'date', 'required' => TRUE),
			'fecha_hasta' => array('label' => 'Número Serie*', 'type' => 'number', 'min' => '1', 'required' => TRUE)
		);
		$this->requeridos = array('referente_id');
		//$this->unicos = array();
		$this->default_join = array(
		);
	}
	
	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		return TRUE;
	}
}
/* End of file Referente_vigencia_model.php */
/* Location: ./application/modules/tribunal/models/Referente_vigencia_model.php */