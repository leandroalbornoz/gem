<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Comedor_valor_racion_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'comedor_valor_racion';
		$this->msg_name = 'Comedor valor racion';
		$this->id_name = 'id';
		$this->columnas = array('id', 'comedor_racion_id','monto', 'mes');
		$this->fields = array(
			'mes' => array('label' => 'Mes', 'type' => 'date', 'required' => TRUE)
		);
		$this->requeridos = array('comedor_racion_id','monto');
		//$this->unicos = array();
		$this->default_join = array(
		);
	}
}