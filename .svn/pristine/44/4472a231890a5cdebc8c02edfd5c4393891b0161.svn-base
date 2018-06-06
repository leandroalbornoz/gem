<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Estado_alumno_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'estado_alumno';
		$this->msg_name = 'Estado de alumno';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '45', 'required' => TRUE)
		);
		$this->requeridos = array('descripcion');
		//$this->unicos = array();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('estado_id', $delete_id)->count_all_results('alumno_division') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a alumno de division.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Estado_alumno_model.php */
/* Location: ./application/models/Estado_alumno_model.php */