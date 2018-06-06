<?php

class Imagen_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'feria_foto';
		$this->msg_name = 'Foto';
		$this->id_name = 'id';
		$this->columnas = array('id', 'path', 'pie', 'posicion', 'feria_id', 'fecha_alta', 'fecha_baja');
		$this->fields = array(
			'path' => array('label' => 'URL', 'type' => 'file', 'required' => TRUE),
			'pie' => array('label' => 'Texto', 'maxlength' => '255', 'required' => TRUE)
		);
		$this->requeridos = array('path', 'pie', 'feria_id');
	}
}