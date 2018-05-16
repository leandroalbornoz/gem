<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inasistencia_justificacion_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'inasistencia_justificacion';
		$this->msg_name = 'Justificación';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '50', 'required' => TRUE)
		);
		$this->requeridos = array('descripcion');
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
		if ($this->db->where('inasistencia_justificacion_id', $delete_id)->count_all_results('alumno_inasistencia') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a alumno de inasistencia.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Inasistencia_justificacion_model.php */
/* Location: ./application/models/Inasistencia_justificacion_model.php */