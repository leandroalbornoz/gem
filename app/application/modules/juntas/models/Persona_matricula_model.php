<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Persona_matricula_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'persona_matricula';
		$this->msg_name = 'Matrícula de persona';
		$this->id_name = 'id';
		$this->columnas = array('id', 'persona_idoneidad_id', 'matricula_tipo_id', 'matricula_nro', 'matricula_vence');
		$this->fields = array(
			'matricula_tipo_id' => array('label' => 'Matrícula Tipo', 'input_type' => 'combo', 'id_name' => 'matricula_tipo_id', 'required' => TRUE),
			'matricula_numero' => array('label' => 'N° de Matrícula', 'readonly' => TRUE),
			'matricula_vence' => array('label' => 'Vencimiento de Matrícula', 'type' => 'date'),
		);
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
/* Location: ./application/modules/bono/models/Persona_matricula_model.php */