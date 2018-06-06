<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Carrera_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'carrera';
		$this->msg_name = 'Carrera';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'fecha_desde', 'fecha_hasta', 'nivel_id');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '100', 'required' => TRUE),
			'fecha_desde' => array('label' => 'Fecha Desde', 'type' => 'date'),
			'fecha_hasta' => array('label' => 'Fecha Hasta', 'type' => 'date'),
			'nivel' => array('label' => 'Nivel', 'input_type' => 'combo', 'id_name' => 'nivel_id')
		);
		$this->requeridos = array('descripcion');
		//$this->unicos = array();
		$this->default_join = array(
			array('nivel', 'nivel.id = carrera.nivel_id', 'left', array('nivel.descripcion as nivel'))
		);
	}

	public function get_by_nivel($nivel_id, $dependencia = 0, $supervision = 0) {
		$options['join'] = array(
			array('escuela_carrera', 'carrera.id=escuela_carrera.carrera_id', 'left', array('COUNT(DISTINCT escuela_carrera.escuela_id) as escuelas')),
			array('escuela', 'escuela_carrera.escuela_id=escuela.id')
		);
		$options['where'] = array(array('column' => 'escuela.nivel_id', 'value' => $nivel_id));
		if ($dependencia) {
			$options['where'][] = array('column'=>'escuela.dependencia_id', 'value' => $dependencia);
		}
		if ($supervision) {
			$options['where'][] = array('column'=>'escuela.supervision_id', 'value' => $supervision);
		}
		$options['group_by'] = 'carrera.id';
		$options['sort_by'] = 'carrera.descripcion';
		return $this->get($options);
	}
	
	public function get_by_escuela_grupo($escuela_grupo_id) {
		return $this->db->select(' c.id, c.descripcion, COUNT(DISTINCT ec.escuela_id) as escuelas')
			->from('escuela_grupo eg')
			->join('escuela_grupo_escuela ege','ege.escuela_grupo_id = eg.id','left')
			->join('escuela e','e.id = ege.escuela_id','left')
			->join('escuela_carrera ec','ec.escuela_id = e.id')
			->join('carrera c','c.id = ec.carrera_id','left')
			->where('eg.id', $escuela_grupo_id)
			->group_by('c.id')
			->get()->result();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('carrera_id', $delete_id)->count_all_results('division') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a division.');
			return FALSE;
		}
		if ($this->db->where('carrera_id', $delete_id)->count_all_results('escuela_carrera') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a escuela de carrera.');
			return FALSE;
		}
		if ($this->db->where('carrera_id', $delete_id)->count_all_results('espacio_curricular') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a espacio de curricular.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Carrera_model.php */
/* Location: ./application/models/Carrera_model.php */