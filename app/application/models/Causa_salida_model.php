<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Causa_salida_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'causa_salida';
		$this->msg_name = 'Causa de salida';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'salida_escuela');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '45', 'required' => TRUE),
			'salida_escuela' => array('label' => 'Salida de escuela', 'input_type' => 'combo', 'id_name' => 'salida_escuela', 'required' => TRUE, 'array' => array('No' => 'No', 'Si' => 'Si')),
		);
		$this->requeridos = array('descripcion', 'salida_escuela');
		//$this->unicos = array();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('causa_salida_id', $delete_id)->count_all_results('alumno_division') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a alumno de division.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Causa_salida_model.php */
/* Location: ./application/models/Causa_salida_model.php */