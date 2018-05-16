<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fechas_limites_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'fechas_limites';
		$this->msg_name = 'Fecha límite';
		$this->id_name = 'id';
		$this->columnas = array('id', 'fecha_desde', 'fecha_hasta', 'detalle');
		$this->fields = array(
			'fecha_desde' => array('label' => 'Fecha Desde', 'type' => 'datetime', 'required' => TRUE),
			'fecha_hasta' => array('label' => 'Fecha Hasta', 'type' => 'datetime', 'required' => TRUE),
			'detalle' => array('label' => 'Detalle', 'maxlength' => '100', 'required' => TRUE),
		);
	}
	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
}
/* End of file Fechas_limites_model.php */
/* Location: ./application/models/Fechas_limites_model.php */