<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Area_cargo_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'cargo';
		$this->msg_name = 'Cargo';
		$this->id_name = 'id';
		$this->columnas = array('id', 'condicion_cargo_id', 'carga_horaria', 'regimen_id', 'area_id', 'fecha_desde', 'fecha_hasta');
		$this->fields = array(
			'condicion_cargo' => array('label' => 'Condición de cargo', 'input_type' => 'combo', 'id_name' => 'condicion_cargo_id', 'required' => TRUE),
			'carga_horaria' => array('label' => 'Carga Horaria', 'type' => 'integer', 'maxlength' => '11'),
			'regimen' => array('label' => 'Régimen', 'input_type' => 'combo', 'id_name' => 'regimen_id', 'required' => TRUE),
			'fecha_desde' => array('label' => 'Fecha Desde', 'type' => 'date'),
			'fecha_hasta' => array('label' => 'Fecha Hasta', 'type' => 'date')
		);
		$this->requeridos = array('condicion_cargo_id', 'regimen_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('area', 'area.id = cargo.area_id', 'left', array('area.descripcion as area')),
			array('condicion_cargo', 'cargo.condicion_cargo_id = condicion_cargo.id', 'left', array('condicion_cargo.descripcion as condicion_cargo')),
			array('regimen', 'cargo.regimen_id = regimen.id', 'left', array('CONCAT(regimen.codigo, \' \',regimen.descripcion) as regimen'))
		);
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('cargo_id', $delete_id)->count_all_results('horario') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que tenga horarios asociados.');
			return FALSE;
		}
		if ($this->db->where('cargo_id', $delete_id)->count_all_results('servicio') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a servicio.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Area_cargo_model.php */
/* Location: ./application/models/Area_cargo_model.php */