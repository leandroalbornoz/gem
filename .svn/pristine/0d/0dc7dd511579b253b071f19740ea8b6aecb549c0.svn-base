<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio_tolerancia_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'servicio_tolerancia';
		$this->msg_name = 'Tolerancia de servicio';
		$this->id_name = 'id';
		$this->columnas = array('id', 'servicio_id', 'dia_id', 'tolerancia', 'validez_desde', 'validez_hasta');
		$this->fields = array(
			'servicio' => array('label' => 'Servicio', 'input_type' => 'combo', 'id_name' => 'servicio_id', 'required' => TRUE),
			'dia' => array('label' => 'Día', 'input_type' => 'combo', 'id_name' => 'dia_id', 'required' => TRUE),
			'tolerancia' => array('label' => 'Tolerancia', 'required' => TRUE),
			'validez_desde' => array('label' => 'Validez de Desde', 'type' => 'date', 'required' => TRUE),
			'validez_hasta' => array('label' => 'Validez de Hasta', 'type' => 'date', 'required' => TRUE)
		);
		$this->requeridos = array('servicio_id', 'dia_id', 'tolerancia', 'validez_desde', 'validez_hasta');
		//$this->unicos = array();
		$this->default_join = array(
			array('dia', 'dia.id = servicio_tolerancia.dia_id', 'left', array('dia.nombre as dia')),
			array('servicio', 'servicio.id = servicio_tolerancia.servicio_id', 'left', array('servicio.persona_id as servicio'))
		);
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		return TRUE;
	}
}
/* End of file Servicio_tolerancia_model.php */
/* Location: ./application/models/Servicio_tolerancia_model.php */