<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio_historial_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'servicio_historial';
		$this->msg_name = 'Historial de servicio';
		$this->id_name = 'id';
		$this->columnas = array('id', 'cargo_id', 'servicio_id', 'fecha_hasta', 'motivo');
		$this->fields = array(
			'fecha_hasta' => array('label' => 'Fecha Reubicación', 'type' => 'date', 'required' => TRUE),
			'motivo' => array('label' => 'Motivo', 'maxlength' => '100', 'value' => 'Reubicación de servicios','required' => TRUE)
		);
		$this->requeridos = array('cargo_id', 'servicio_id', 'fecha_hasta');
		//$this->unicos = array();
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
/* End of file Servicio_historial_model.php */
/* Location: ./application/models/Servicio_historial_model.php */