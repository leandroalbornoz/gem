<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Beca_estado_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'beca_estado';
		$this->msg_name = 'Estado Beca';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'clase', 'icono');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '20', 'required' => TRUE)
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
		if ($this->db->where('beca_estado_d_id', $delete_id)->count_all_results('beca_operacion') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a operación de beca.');
			return FALSE;
		}
		if ($this->db->where('beca_estado_o_id', $delete_id)->count_all_results('beca_operacion') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a operación de beca.');
			return FALSE;
		}
		if ($this->db->where('beca_estado_id', $delete_id)->count_all_results('beca_persona') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a beca de persona.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Beca_estado_model.php */
/* Location: ./application/modules/becas/models/Beca_estado_model.php */