<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Turno_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'turno';
		$this->msg_name = 'Turno';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '25', 'required' => TRUE)
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
		if ($this->db->where('turno_id', $delete_id)->count_all_results('division') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a division.');
			return FALSE;
		}
		if ($this->db->where('turno_id', $delete_id)->count_all_results('cargo') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a cargo.');
			return FALSE;
		}
		if ($this->db->where('turno_id', $delete_id)->count_all_results('cargo_historial') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a historial de cargo.');
			return FALSE;
		}
		if ($this->db->where('turno_id', $delete_id)->count_all_results('horario') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a horario.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Turno_model.php */
/* Location: ./application/models/Turno_model.php */