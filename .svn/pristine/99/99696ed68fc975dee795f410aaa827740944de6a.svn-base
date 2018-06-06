<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reparticion_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'reparticion';
		$this->msg_name = 'Repartición';
		$this->id_name = 'id';
		$this->columnas = array('id', 'jurisdiccion_id', 'codigo', 'descripcion', 'fecha_desde', 'fecha_hasta');
		$this->fields = array(
			'jurisdiccion' => array('label' => 'Jurisdicción', 'input_type' => 'combo', 'id_name' => 'jurisdiccion_id', 'required' => TRUE),
			'codigo' => array('label' => 'Codigo', 'maxlength' => '4', 'required' => TRUE),
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '100', 'required' => TRUE),
			'fecha_desde' => array('label' => 'Fecha Desde', 'type' => 'date'),
			'fecha_hasta' => array('label' => 'Fecha Hasta', 'type' => 'date')
		);
		$this->requeridos = array('jurisdiccion_id', 'codigo', 'descripcion');
		//$this->unicos = array();
		$this->default_join = array(
			array('jurisdiccion', 'jurisdiccion.id = reparticion.jurisdiccion_id', 'left', array('jurisdiccion.codigo as jurisdiccion'))
		);
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('reparticion_id', $delete_id)->count_all_results('area') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a área.');
			return FALSE;
		}
		if ($this->db->where('reparticion_id', $delete_id)->count_all_results('escuela') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a escuela.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Reparticion_model.php */
/* Location: ./application/models/Reparticion_model.php */