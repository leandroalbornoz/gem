<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Regimen_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'regimen';
		$this->msg_name = 'Régimen';
		$this->id_name = 'id';
		$this->columnas = array('id', 'codigo', 'descripcion', 'regimen_tipo_id', 'planilla_modalidad_id');
		$this->fields = array(
			'codigo' => array('label' => 'Codigo', 'maxlength' => '7', 'required' => TRUE),
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '60', 'required' => TRUE),
			'regimen_tipo' => array('label' => 'Tipo de régimen', 'input_type' => 'combo', 'id_name' => 'regimen_tipo_id', 'required' => TRUE),
		);
		$this->requeridos = array('codigo', 'descripcion', 'regimen_tipo_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('regimen_tipo', 'regimen_tipo.id = regimen.regimen_tipo_id', 'left', array('regimen_tipo.descripcion as regimen_tipo'))
		);
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
		if ($this->db->where('regimen_id', $delete_id)->count_all_results('cargo') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a cargo.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Regimen_model.php */
/* Location: ./application/models/Regimen_model.php */