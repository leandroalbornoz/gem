<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dia_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'dia';
		$this->msg_name = 'Día';
		$this->id_name = 'id';
		$this->columnas = array('id', 'nombre');
		$this->fields = array(
			'nombre' => array('label' => 'Nombre', 'maxlength' => '50')
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
		if ($this->db->where('dia_id', $delete_id)->count_all_results('horario') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a horarios.');
			return FALSE;
		}
		if ($this->db->where('dia_id', $delete_id)->count_all_results('horario') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a horarios.');
			return FALSE;
		}
		if ($this->db->where('dia_id', $delete_id)->count_all_results('servicio_tolerancia') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a servicio de tolerancia.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Dia_model.php */
/* Location: ./application/models/Dia_model.php */