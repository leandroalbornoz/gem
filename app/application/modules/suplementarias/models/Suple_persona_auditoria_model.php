<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Suple_persona_auditoria_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'suple_persona_auditoria';
		$this->msg_name = 'Auditoría persona';
		$this->id_name = 'id';
		$this->columnas = array('id', 'suple_persona_id', 'estado_id', 'fecha', 'usuario_id', 'observaciones');
		$this->fields = array(
			'suple_persona' => array('label' => 'Persona', 'input_type' => 'combo', 'id_name' => 'suple_persona_id', 'required' => TRUE),
			'estado' => array('label' => 'Estado', 'input_type' => 'combo', 'id_name' => 'estado_id', 'required' => TRUE),
			'fecha' => array('label' => 'Fecha', 'type' => 'datetime', 'required' => TRUE),
			'usuario' => array('label' => 'Usuario', 'input_type' => 'combo', 'id_name' => 'usuario_id', 'required' => TRUE),
			'observaciones' => array('label' => 'Observaciones', 'maxlength' => '150')
		);
		$this->requeridos = array('suple_persona_id', 'estado_id', 'fecha', 'usuario_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('suple_estado', 'suple_estado.id = suple_persona_auditoria.estado_id', 'left', array('suple_estado.id as estado')), 
			array('suple_persona', 'suple_persona.id = suple_persona_auditoria.suple_persona_id', 'left', array('suple_persona.id as suple_persona')), );
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
/* End of file Suple_persona_auditoria_model.php */
/* Location: ./application/modules/suplementarias/models/Suple_persona_auditoria_model.php */