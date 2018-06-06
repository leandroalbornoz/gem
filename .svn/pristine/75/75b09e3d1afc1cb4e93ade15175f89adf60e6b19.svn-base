<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Referente_vigencia_saldo_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'referente_vigencia_saldo';
		$this->msg_name = 'Referente vigencia saldo';
		$this->id_name = 'id';
		$this->columnas = array('id', 'referente_vigencia_id', 'saldo', 'fecha');
		$this->fields = array(
			'saldo' => array('label' => 'Saldo de la cuenta', 'type' => 'number', 'required' => TRUE),
			'fecha' => array('label' => 'Fecha', 'type' => 'date', 'required' => TRUE)
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
/* End of file Referente_vigencia_saldo.php */
/* Location: ./application/modules/tribunal/models/Referente_vigencia_saldo.php */

