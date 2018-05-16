<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Condicion_cargo_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'condicion_cargo';
		$this->msg_name = 'Condición de cargo';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'planilla_modalidad_id');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '60', 'required' => TRUE)
		);
		$this->requeridos = array('descripcion');
		//$this->unicos = array();
	}

	public function get($options = array()) {
		if (!isset($options['planilla_modalidad_id']) && !isset($options['id'])) {
			$options['planilla_modalidad_id'] = 1;
		}
		return parent::get($options);
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('condicion_cargo_id', $delete_id)->count_all_results('cargo') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a cargo.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Condicion_cargo_model.php */
/* Location: ./application/models/Condicion_cargo_model.php */