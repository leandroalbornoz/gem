<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Area_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'area';
		$this->msg_name = 'Área';
		$this->id_name = 'id';
		$this->columnas = array('id', 'codigo', 'descripcion', 'reparticion_id', 'area_padre_id');
		$this->fields = array(
			'area_padre' => array('label' => 'Área Padre', 'input_type' => 'combo', 'id_name' => 'area_padre_id'),
			'codigo' => array('label' => 'Código', 'maxlength' => '20', 'readonly' => TRUE),
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '45', 'required' => TRUE),
		);
		$this->requeridos = array('codigo', 'descripcion', 'reparticion_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('area area_padre', 'area_padre.id = area.area_padre_id', 'left', array('area_padre.descripcion as area_padre')),
			array('reparticion', 'reparticion.id = area.reparticion_id', 'left', array('CONCAT(jurisdiccion.codigo, \' \', reparticion.codigo, \' \', reparticion.descripcion) as reparticion', 'reparticion.codigo as reparticion_codigo')),
			array('jurisdiccion', 'jurisdiccion.id = reparticion.jurisdiccion_id', 'left', array('jurisdiccion.codigo as jurisdiccion_codigo')),
		);
	}

	public function get_codigo($area_padre_id) {
		if ($area_padre_id) {
			$area_hijo = $this->db->select('ap.id, ap.codigo, MAX(ah.codigo) as codigo_hijo')
					->from('area ap')
					->join('area ah', 'ap.id=ah.area_padre_id', 'left')
					->where('ap.id', $area_padre_id)
					->group_by('ap.id')
					->get()->row();
		} else {
			$area_hijo = $this->db->select('MAX(ah.codigo) as codigo_hijo')
					->from('area ah')
					->where('ah.area_padre_id IS NULL')
					->get()->row();
		}
		$ultimo_codigo = empty($area_hijo->codigo_hijo) ? $area_hijo->codigo . '00' : $area_hijo->codigo_hijo;
		$codigo = substr($ultimo_codigo, 0, strlen($ultimo_codigo) - 2) . str_pad(substr($ultimo_codigo, -2) + 1, 2, '0', STR_PAD_LEFT);
		return $codigo;
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('area_padre_id', $delete_id)->count_all_results('area') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a área.');
			return FALSE;
		}
		if ($this->db->where('area_id', $delete_id)->count_all_results('cargo') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a cargo.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Area_model.php */
/* Location: ./application/models/Area_model.php */