<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Referente_vigencia_ultimo_cheque_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'referente_vigencia_ultimo_cheque';
		$this->msg_name = 'Referente vigencia ultimo cheque';
		$this->id_name = 'id';
		$this->columnas = array('id', 'referente_vigencia_id', 'numero', 'importe', 'fecha');
		$this->fields = array(
			'numero' => array('label' => 'Número del cheque', 'type' => 'number', 'required' => TRUE),
			'importe' => array('label' => 'Importe del cheque', 'type' => 'number', 'required' => TRUE),
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
/* End of file Referente_vigencia_ultimo_cheque_model.php */
/* Location: ./application/modules/tribunal/models/Referente_vigencia_ultimo_cheque_model.php */