<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jurisdiccion_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'jurisdiccion';
		$this->msg_name = 'Jurisdicción';
		$this->id_name = 'id';
		$this->columnas = array('id', 'codigo', 'descripcion');
		$this->fields = array(
			'codigo' => array('label' => 'Codigo', 'maxlength' => '2'),
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '100')
		);
		$this->requeridos = array();
		//$this->unicos = array();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('jurisdiccion_id', $delete_id)->count_all_results('reparticion') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a reparticion.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Jurisdiccion_model.php */
/* Location: ./application/models/Jurisdiccion_model.php */