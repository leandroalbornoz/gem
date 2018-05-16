<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Departamento_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'departamento';
		$this->msg_name = 'Departamento';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'provincia_id', 'regional_id');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '45', 'required' => TRUE),
			'provincia' => array('label' => 'Provincia', 'input_type' => 'combo', 'id_name' => 'provincia_id', 'required' => TRUE),
			'regional' => array('label' => 'Regional', 'input_type' => 'combo', 'id_name' => 'regional_id', 'required' => TRUE)
		);
		$this->requeridos = array('descripcion', 'provincia_id', 'regional_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('provincia', 'provincia.id = departamento.provincia_id', 'left', array('provincia.descripcion as provincia')),
			array('regional', 'regional.id = departamento.regional_id', 'left', array('regional.descripcion as regional'))
		);
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('departamento_id', $delete_id)->count_all_results('localidad') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a localidad.');
			return FALSE;
		}
		if ($this->db->where('depto_nacimiento_id', $delete_id)->count_all_results('persona') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a persona.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Departamento_model.php */
/* Location: ./application/models/Departamento_model.php */