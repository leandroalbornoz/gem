<?php

class Texto_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'feria_texto';
		$this->msg_name = 'Texto';
		$this->id_name = 'id';
		$this->columnas = array('id', 'encabezado', 'texto', 'feria_id', 'posicion', 'fecha_alta', 'fecha_baja');
		$this->fields = array(
			'encabezado' => array('label' => 'Título', 'maxlength' => '255', 'required' => TRUE),
			'texto' => array('label' => 'Texto', 'form_type' => 'textarea', 'maxlength' => '255', 'required' => TRUE)
		);
		$this->requeridos = array('encabezado', 'texto', 'feria_id');
	}
}