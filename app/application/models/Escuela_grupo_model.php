<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escuela_grupo_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'escuela_grupo';
		$this->msg_name = 'Grupo de escuela';
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
		if ($this->db->where('escuela_grupo_id', $delete_id)->count_all_results('escuela_grupo_escuela') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a escuela de grupo de escuela.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Escuela_grupo_model.php */
/* Location: ./application/models/Escuela_grupo_model.php */