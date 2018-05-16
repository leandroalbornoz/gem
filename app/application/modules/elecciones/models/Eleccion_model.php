<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Eleccion_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'eleccion';
		$this->msg_name = 'Elección';
		$this->id_name = 'id';
		$this->columnas = array('id', 'fecha_desde', 'fecha_hasta', 'descripcion');
		$this->fields = array(
			'fecha_desde' => array('label' => 'Fecha desde', 'readonly' => TRUE),
			'fecha_hasta' => array('label' => 'Fecha Hasta', 'readonly' => TRUE),
			'descripcion' => array('label' => 'Descripcion', 'readonly' => TRUE),
		);
		$this->requeridos = array();
		//$this->unicos = array();
	}
	protected function _can_delete($delete_id) {
		return TRUE;
	}
}