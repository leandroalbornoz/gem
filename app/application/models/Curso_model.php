<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Curso_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'curso';
		$this->msg_name = 'Curso';
		$this->id_name = 'id';
		$this->columnas = array('id', 'nivel_id', 'descripcion', 'grado_multiple');
		$this->fields = array(
			'nivel' => array('label' => 'Nivel', 'input_type' => 'combo', 'id_name' => 'nivel_id', 'required' => TRUE),
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '45', 'required' => TRUE),
			'grado_multiple' => array('label' => 'Grado Múltiple', 'input_type' => 'combo', 'id_name' => 'grado_multiple', 'array' => array('' => '-- Seleccionar --', 'Si' => 'Si', 'No' => 'No'), 'required' => TRUE)
		);
		$this->requeridos = array('nivel_id', 'descripcion');
		//$this->unicos = array();
		$this->default_join = array(
			array('nivel', 'nivel.id = curso.nivel_id', 'left', array('nivel.descripcion as nivel'))
		);
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('curso_id', $delete_id)->count_all_results('division') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a division.');
			return FALSE;
		}
		if ($this->db->where('curso_id', $delete_id)->count_all_results('espacio_curricular') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a espacio de curricular.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Curso_model.php */
/* Location: ./application/models/Curso_model.php */