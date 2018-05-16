<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Persona_antiguedad_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'persona_antiguedad';
		$this->msg_name = 'Antigüedad de persona';
		$this->id_name = 'id';
		$this->columnas = array('id', 'persona_id', 'escuela_id', 'cargo', 'situacion_revista', 'fecha_desde', 'fecha_hasta');
		$this->requeridos = array('persona_id', 'escuela_id', 'fecha_desde', 'fecha_hasta');
		//$this->unicos = array();
		$this->fields = array(
			'escuela' => array('label' => 'Escuela', 'readonly' => TRUE),
			'cargo' => array('label' => 'Cargo', 'input_type' => 'combo', 'id_name' => 'cargo', 'array' => array('' => '','Docente' => 'Docente', 'Administrativo' => 'Administrativo', 'Celador/Personal de servicio' => 'Celador/Personal de servicio')),
			'situacion_revista' => array('label' => 'Situación revista', 'input_type' => 'combo', 'id_name' => 'situacion_revista', 'array' => array('' => '', 'Titular' => 'Titular', 'Reemplazo' => 'Reemplazo', 'Contratado (Privada)' => 'Contratado (Privada)')),
			'fecha_desde' => array('label' => 'Desde', 'type' => 'date', 'required' => TRUE),
			'fecha_hasta' => array('label' => 'Hasta', 'type' => 'date', 'required' => TRUE),
		);
		$this->default_join = array(
			array('escuela', 'escuela.id=persona_antiguedad.escuela_id', 'left', array("CONCAT(numero, CASE WHEN anexo=0 THEN ' ' ELSE CONCAT('/',anexo,' ') END, nombre) as escuela"))
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
/* End of file Persona_antiguedad_model.php */
/* Location: ./application/models/Persona_antiguedad_model.php */