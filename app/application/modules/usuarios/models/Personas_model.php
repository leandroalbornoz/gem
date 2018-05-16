<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Personas_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'personas';
		$this->msg_name = 'Persona';
		$this->id_name = 'id';
		$this->columnas = array('id', 'cuil', 'nombre', 'sexo', 'fecha_nacimiento', 'estado');
		$this->fields = array(
			array('name' => 'cuil', 'label' => 'CUIL', 'maxlength' => '13'),
			array('name' => 'sexo', 'label' => 'Sexo', 'input_type' => 'combo'),
			array('name' => 'nombre', 'label' => 'Nombre', 'maxlength' => '100'),
			array('name' => 'fecha_nacimiento', 'label' => 'Fecha Nacimiento', 'type' => 'date'),
			array('name' => 'estado', 'label' => 'Estado', 'input_type' => 'combo')
		);
		$this->requeridos = array('nombre', 'sexo', 'estado');
		//$this->unicos = array();
	}
}
/* End of file Personas_model.php */
/* Location: ./application/models/Personas_model.php */