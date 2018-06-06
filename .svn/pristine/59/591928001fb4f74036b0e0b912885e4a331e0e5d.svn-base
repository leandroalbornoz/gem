<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escuela_autoridad_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'escuela_autoridad';
		$this->msg_name = 'Autoridad de escuela';
		$this->id_name = 'id';
		$this->columnas = array('id', 'autoridad_tipo_id', 'escuela_id', 'persona_id');
		$this->fields = array(
			'autoridad_tipo' => array('label' => 'Tipo de autoridad', 'input_type' => 'combo', 'id_name' => 'autoridad_tipo_id', 'required' => TRUE),
			'persona' => array('label' => 'Persona', 'id_name' => 'persona_id', 'required' => TRUE),
			'escuela' => array('label' => 'Escuela', 'required' => TRUE)
		);
		$this->requeridos = array('autoridad_tipo_id', 'escuela_id', 'persona_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('autoridad_tipo', 'autoridad_tipo.id = escuela_autoridad.autoridad_tipo_id', 'left', array('autoridad_tipo.descripcion as autoridad_tipo')), 
			array('escuela', 'escuela.id = escuela_autoridad.escuela_id', 'left', array('CONCAT(escuela.numero, \' - \', escuela.nombre) as escuela')), 
			array('persona', 'persona.id = escuela_autoridad.persona_id', 'left', array('CONCAT(persona.apellido, \', \', persona.nombre, \' (\', persona.cuil, \' )\') as persona')), 
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
/* End of file Escuela_autoridad_model.php */
/* Location: ./application/models/Escuela_autoridad_model.php */