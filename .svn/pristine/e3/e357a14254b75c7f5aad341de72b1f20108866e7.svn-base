<?php

class Feria_model extends MY_Model {

		public function __construct() {
		parent::__construct();
		$this->table_name = 'feria';
		$this->msg_name = 'Feria';
		$this->id_name = 'id';
		$this->columnas = array('id', 'escuela_id', 'fecha_alta', 'fecha_baja');
		$this->fields = array(
			'fecha_alta' => array('label' => 'Fecha alta', 'type' => 'date', 'required' => TRUE),
			'fecha_baja' => array('label' => 'Fecha baja', 'type' => 'date', 'required' => TRUE)
		);
		$this->requeridos = array('escuela_id');
	}

}