<?php

class Feria_especialidad_model extends MY_Model {

		public function __construct() {
		parent::__construct();
		$this->table_name = 'feria_especialidad';
		$this->msg_name = 'Feria especialidad';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'type' => 'date')
		);
		$this->requeridos = array('escuela_id');
	}
	

}