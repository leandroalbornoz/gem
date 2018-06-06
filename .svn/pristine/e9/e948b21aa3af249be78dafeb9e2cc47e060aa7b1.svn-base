<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inasistencia_actividad_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'inasistencia_actividad';
		$this->msg_name = 'Actividad';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'realiza_actividad');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripcion', 'maxlength' => '50', 'required' => TRUE),
			'realiza_actividad' => array('label' => 'Realiza Actividad', 'required' => TRUE)
		);
		$this->requeridos = array('descripcion', 'realiza_actividad');
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
		if ($this->db->where('inasistencia_actividad_id', $delete_id)->count_all_results('division_inasistencia_dia') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a division de inasistencia de dia.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Inasistencia_actividad_model.php */
/* Location: ./application/models/Inasistencia_actividad_model.php */