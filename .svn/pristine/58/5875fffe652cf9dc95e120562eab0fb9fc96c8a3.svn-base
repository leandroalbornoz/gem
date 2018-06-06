<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Suple_persona_concepto_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'suple_persona_concepto';
		$this->msg_name = 'Concepto persona';
		$this->id_name = 'id';
		$this->columnas = array('id', 'suple_persona_id', 'concepto_id', 'importe');
		$this->requeridos = array('suple_persona_id', 'concepto_id', 'importe');
		//$this->unicos = array();
		$this->default_join = array(
			array('suple_concepto', 'suple_concepto.id = suple_persona_concepto.concepto_id', 'left', array('suple_concepto.id as concepto')), 
			array('suple_persona', 'suple_persona.id = suple_persona_concepto.suple_persona_id', 'left', array('suple_persona.id as suple_persona')), );
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
/* End of file Suple_persona_concepto_model.php */
/* Location: ./application/modules/suplementarias/models/Suple_persona_concepto_model.php */