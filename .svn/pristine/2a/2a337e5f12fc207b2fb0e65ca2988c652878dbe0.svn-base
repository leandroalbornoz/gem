<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bonificaciones_model extends MY_Model {
public function __construct() {
		parent::__construct();
		$this->table_name = 'bonificaciones';
		$this->msg_name = 'Bonificaciones';
		$this->id_name = 'id';
		$this->columnas = array('id', 'bonificacion_escolaridad', 'bonificacion_matrimonio', 'bonificacion_cargo', 'bonificacion_discapacidad','servicio_id','persona_id' );
		$this->fields = array(
			'persona' => array('label' => 'Persona', 'disabled' => TRUE),
			'cuil' => array('label' => 'Cuil', 'disabled' => TRUE),
			'bonificacion_escolaridad' => array('label' => 'Bonificacion escolaridad', 'id_name' => 'bonificacion_escolaridad', 'input_type' => 'combo','required' => TRUE, 'array' => array('Si' => 'Si', 'No' => 'No')),
			'bonificacion_matrimonio' => array('label' => 'Bonificacion matrimonio', 'id_name' => 'bonificacion_matrimonio', 'input_type' => 'combo', 'required' => TRUE, 'array' => array('Si' => 'Si', 'No' => 'No')),
			'bonificacion_cargo' => array('label' => 'Bonificacion cargo', 'id_name' => 'bonificacion_cargo', 'input_type' => 'combo', 'required' => TRUE, 'array' => array('Si' => 'Si', 'No' => 'No')),
			'bonificacion_discapacidad' => array('label' => 'Bonificacion discapacidad', 'id_name' => 'bonificacion_discapacidad', 'input_type' => 'combo', 'required' => TRUE, 'array' => array('Si' => 'Si', 'No' => 'No')),
		);
		
		$this->requeridos = array('servicio_id','persona_id');
    $this->unicos = array();
		$this->default_join = array(
			array('persona', 'persona.id = bonificaciones.persona_id', 'left', array("CONCAT (persona.apellido, ', ' , persona.nombre )as persona ",'persona.cuil')),
			array('servicio', 'servicio.id = bonificaciones.servicio_id', 'left', array('servicio.persona_id as servicio')),);
	}
	
function get_bonificaciones($persona_id,$servicio_id) {
		return $this->db->select('id,bonificacion_escolaridad,bonificacion_matrimonio,bonificacion_cargo,bonificacion_discapacidad,servicio_id,persona_id')
				->from('bonificaciones')
				->where('bonificaciones.persona_id',$persona_id)
				->where('bonificaciones.servicio_id',$servicio_id)
				->get()->result();
		
	}
}