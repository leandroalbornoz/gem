<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Titulo_establecimiento_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'titulo_establecimiento';
		$this->msg_name = 'Establecimiento';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '100', 'required' => TRUE)
		);
		$this->requeridos = array('descripcion');
		$this->unicos = array('descripcion');
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
		if ($this->db->where('titulo_establecimiento_id', $delete_id)->count_all_results('titulo') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a titulo.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Titulo_establecimiento_model.php */
/* Location: ./application/modules/titulos/models/Titulo_establecimiento_model.php */