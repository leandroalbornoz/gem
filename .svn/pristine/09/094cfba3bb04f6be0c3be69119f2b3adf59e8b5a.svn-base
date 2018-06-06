<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Comedor_plazo_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'comedor_plazo';
		$this->msg_name = 'Comedor plazo';
		$this->id_name = 'id';
		$this->columnas = array('id', 'mes','fecha_cierre');
		$this->fields = array(
			'mes' => array('label' => 'Mes', 'type' => 'integer', 'required' => TRUE),
			'fecha_cierre' => array('label' => 'Fecha cierre', 'type' => 'date', 'required' => TRUE)
		);
		$this->requeridos = array('mes','fecha_cierre');
		//$this->unicos = array();
		$this->default_join = array(
		);
	}
}