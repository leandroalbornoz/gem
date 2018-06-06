<?php

class Feria_escuela_especialidad_model extends MY_Model {

		public function __construct() {
		parent::__construct();
		$this->table_name = 'feria_escuela_especialidad';
		$this->msg_name = 'Feria escuela especialidad';
		$this->id_name = 'id';
		$this->columnas = array('id', 'escuela_id','especialidad_id');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'type' => 'date')
		);
		$this->requeridos = array('escuela_id');
	}
	
	public function get_escuela_areas($escuela_id) {
		return $this->db->select('fe.id, fe.descripcion, fee.id as feria_escuela_especiliadad_id')
				->from('feria_escuela_especialidad fee')
				->join('feria_especialidad fe', 'fe.id = fee.especialidad_id')
				->where('fee.escuela_id', $escuela_id)
				->get()->result();
	}

	public function get_area_existente($escuela_id, $area_interes) {
		return $this->db->select('fe.id, fe.descripcion, fee.id as feria_escuela_especiliadad_id')
				->from('feria_escuela_especialidad fee')
				->join('feria_especialidad fe', 'fe.id = fee.especialidad_id')
				->where('fee.escuela_id', $escuela_id)
				->where('fee.especialidad_id', $area_interes)
				->get()->result();
	}
	
}