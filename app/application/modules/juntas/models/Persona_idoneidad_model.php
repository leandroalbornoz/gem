<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Persona_idoneidad_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'persona_idoneidad';
		$this->msg_name = 'Idoneidad de Persona';
		$this->id_name = 'id';
		$this->columnas = array('id', 'persona_cargo_antecedente_id', 'espacio_id', 'certifica_cct');
//		$this->fields = array(
//			'matricula_tipo_id' => array('label' => 'Matrícula Tipo', 'input_type' => 'combo', 'id_name' => 'matricula_tipo_id', 'required' => TRUE),
//			'matricula_numero' => array('label' => 'N° de Matrícula', 'readonly' => TRUE),
//			'matricula_vence' => array('label' => 'Vencimiento de Matrícula', 'type' => 'date'),
//		);
		$this->requeridos = array();
	}
	/*
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */

	protected function _can_delete($delete_id) {
		return TRUE;
	}
}
/* End of file Posgrado_tipo_model.php */
/* Location: ./application/modules/bono/models/Persona_idoneidad_model.php */