<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Conectividad_nacion_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'conectividad_nacion';
		$this->msg_name = 'Relevamiento Conectividad';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'fecha_desde', 'fecha_hasta');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripcion', 'maxlength' => '50', 'required' => TRUE),
			'fecha_desde' => array('label' => 'Fecha de Desde', 'type' => 'datetime', 'required' => TRUE),
			'fecha_hasta' => array('label' => 'Fecha de Hasta', 'type' => 'datetime', 'required' => TRUE)
		);
		$this->requeridos = array('descripcion', 'fecha_desde', 'fecha_hasta');
		//$this->unicos = array();
		$this->default_join = array();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('conectividad_nacion_id', $delete_id)->count_all_results('conectividad_nacion_escuela') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a conectividad de nacion de escuela.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Conectividad_nacion_model.php */
/* Location: ./application/modules/conectividad/models/Conectividad_nacion_model.php */