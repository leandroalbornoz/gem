<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Localidad_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'localidad';
		$this->msg_name = 'Localidad';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'departamento_id');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '45', 'required' => TRUE),
			'departamento' => array('label' => 'Departamento', 'input_type' => 'combo', 'id_name' => 'departamento_id', 'required' => TRUE)
		);
		$this->requeridos = array('descripcion', 'departamento_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('departamento', 'departamento.id = localidad.departamento_id', 'left', array('departamento.descripcion as departamento'))
		);
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('localidad_id', $delete_id)->count_all_results('persona') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a persona.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Localidad_model.php */
/* Location: ./application/models/Localidad_model.php */