<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Prenatal_model extends MY_Model {
public function __construct() {
		parent::__construct();
		$this->table_name = 'prenatal';
		$this->msg_name = 'Prenatal';
		$this->id_name = 'id';
		$this->columnas = array('id','fecha_certificada','fecha_posiblenac','fecha_defuncion','bonificaciones_id' );
//		$this->fields = array(
//			'fecha_certificada' => array('label' => 'Fecha Certificada', 'type' => 'date', 'required' => TRUE),
//			'fecha_posiblenac' => array('label' => 'Fecha Posible Nacimiento', 'type' => 'date', 'required' => TRUE),
//			'fecha_defuncion' => array('label' => 'Fecha Defunción', 'type' => 'date', 'required' => TRUE),
//		);
		$this->fields = array(
			'fecha_certificada' => array('label' => 'Fecha Certificada', 'id_name' => 'fecha_certificada', 'type' => 'datetime', 'required' => TRUE),
			'fecha_posiblenac' => array('label' => 'Fecha Posible Nacimiento', 'id_name' => 'fecha_posiblenac',  'type' => 'datetime', 'required' => TRUE),
			'fecha_defuncion' => array('label' => 'Fecha Defunción
', 'id_name' => 'fecha_defuncion',  'type' => 'datetime', 'required' => TRUE),
		);
		
		$this->requeridos = array('bonificaciones_id');
		$this->unicos = array();
		$this->default_join = array();
	}
	
	function get_prenatal($bonificaciones_id) {
		return $this->db->select('id,fecha_certificada,fecha_posiblenac,fecha_defuncion,bonificaciones_id')
				->from('prenatal')
				->where('prenatal.bonificaciones_id',$bonificaciones_id)
				->get()->result();
		
	}
}