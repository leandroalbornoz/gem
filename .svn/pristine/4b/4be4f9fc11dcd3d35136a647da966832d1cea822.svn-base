<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tem_proyecto_escuela_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'tem_proyecto_escuela';
		$this->msg_name = 'Escuela TEM';
		$this->id_name = 'id';
		$this->columnas = array('id', 'tem_proyecto_id', 'escuela_id', 'horas_catedra');
		$this->fields = array(
			'tem_proyecto' => array('label' => 'Proyecto TEM', 'input_type' => 'combo', 'id_name' => 'tem_proyecto_id', 'required' => TRUE),
			'escuela' => array('label' => 'Escuela', 'input_type' => 'combo', 'id_name' => 'escuela_id', 'required' => TRUE),
			'horas_catedra' => array('label' => 'Horas de Catedra', 'type' => 'integer', 'maxlength' => '10', 'required' => TRUE)
		);
		$this->requeridos = array('tem_proyecto_id', 'escuela_id', 'horas_catedra');
		//$this->unicos = array();
		$this->default_join = array(
			array('escuela', 'escuela.id = tem_proyecto_escuela.escuela_id', 'left', array('escuela.nombre as escuela')), 
			array('tem_proyecto', 'tem_proyecto.id = tem_proyecto_escuela.tem_proyecto_id', 'left', array('tem_proyecto.descripcion as tem_proyecto')), );
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
/* End of file Tem_proyecto_escuela_model.php */
/* Location: ./application/modules/tem/models/Tem_proyecto_escuela_model.php */