<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Referente_vigencia_fondos_pendientes_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'referente_vigencia_fondos_pendientes';
		$this->msg_name = 'Referente vigencia fondos pendientes';
		$this->id_name = 'id';
		$this->columnas = array('id', 'referente_vigencia_id', 'fecha_transferencia', 'concepto', 'importe');
		$this->fields = array(
			'fecha_transferencia' => array('label' => 'Fecha de transferecia', 'type' => 'date', 'required' => TRUE),
			'concepto' => array('label' => 'Concepto', 'type' => 'text', 'required' => TRUE),
			'importe' => array('label' => 'Importe ($)', 'type' => 'number', 'required' => TRUE)
		);
		$this->requeridos = array('referente_vigencia_id');
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
/* End of file Referente_vigencia_fondos_pendientes_model.php */
/* Location: ./application/modules/tribunal/models/Referente_vigencia_fondos_pendientes_model.php */