<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alumno_movimiento_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'alumno_movimiento';
		$this->msg_name = 'Alumno movimiento';
		$this->id_name = 'id';
		$this->columnas = array('id', 'fecha', 'escuela_id', 'tipo', 'movimiento', 'causa_salida_id', 'division_origen_id', 'ciclo_lectivo_origen', 'causa_entrada_id', 'division_destino_id', 'ciclo_lectivo_destino');
		$this->fields = array(
			'fecha' =>  array('label' => 'Fecha', 'type' => 'date'),
			'movimiento' =>  array('label' => 'Movimiento', 'type' => 'text'),
			'ciclo_lectivo_origen' => array('label' => 'Ciclo Lectivo', 'type' => 'integer', 'maxlength' => '11'),
			'ciclo_lectivo_destino' =>  array('label' => 'Ciclo Lectivo', 'type' => 'integer', 'maxlength' => '11'),
			
		);

		$this->requeridos = array('escuela_id');
//		$this->default_join = array(
//		);
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
/* End of file Alumno_movimiento_model.php */
/* Location: ./application/models/Alumno_movimiento_model.php */