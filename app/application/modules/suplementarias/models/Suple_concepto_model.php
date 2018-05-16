<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Suple_concepto_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'suple_concepto';
		$this->msg_name = 'Concepto';
		$this->id_name = 'id';
		$this->columnas = array('id', 'codigo', 'descripcion', 'tipo', 'orden', 'inicial');
		$this->fields = array(
			'codigo' => array('label' => 'Codigo', 'maxlength' => '4'),
			'descripcion' => array('label' => 'Descripcion', 'maxlength' => '150'),
			'tipo' => array('label' => 'Tipo', 'maxlength' => '4'),
			'orden' => array('label' => 'Orden', 'maxlength' => '10'),
			'inicial' => array('label' => 'Inicial', 'maxlength' => '3')
		);
		$this->requeridos = array();
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
		if ($this->db->where('concepto_id', $delete_id)->count_all_results('suple_persona_concepto') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a suple de persona de concepto.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Suple_concepto_model.php */
/* Location: ./application/modules/suplementarias/models/Suple_concepto_model.php */