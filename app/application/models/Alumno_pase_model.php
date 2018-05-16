<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alumno_pase_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'alumno_pase';
		$this->msg_name = 'Pase de Alumno';
		$this->id_name = 'id';
		$this->columnas = array('id', 'alumno_id', 'escuela_origen_id', 'escuela_destino_id', 'estado', 'motivo_rechazo', 'fecha_pase');
		$this->requeridos = array('alumno_id', 'escuela_origen_id', 'escuela_destino_id', 'estado', 'fecha_pase');
		$this->default_join = array();
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
/* End of file Alumno_division_model.php */
/* Location: ./application/models/Alumno_division_model.php */