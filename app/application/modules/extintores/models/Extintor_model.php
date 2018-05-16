<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Extintor_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'extintor';
		$this->msg_name = 'Extintor';
		$this->id_name = 'id';
		$this->columnas = array('id', 'extintor_relevamiento_id', 'escuela_id', 'primer_carga', 'vencimiento', 'numero_registro', 'empresa_instalacion', 'marca', 'kilos', 'tipo_extintor');
		$this->fields = array(
			'primer_carga' => array('label' => 'Primer Carga*', 'type' => 'date', 'required' => TRUE),
			'vencimiento' => array('label' => 'Vencimiento*', 'type' => 'date', 'required' => TRUE),
			'numero_registro' => array('label' => 'Número Serie*', 'type' => 'number', 'min' => '1', 'required' => TRUE),
			'empresa_instalacion' => array('label' => 'Empresa', 'maxlength' => '150'),
			'marca' => array('label' => 'Marca', 'maxlength' => '150'),
			'kilos' => array('label' => 'Kilos*', 'type' => 'numeric', 'maxlength' => '4', 'required' => TRUE),
			'tipo_extintor' => array('label' => 'Tipo de Extintor*', 'input_type' => 'combo', 'array' => array('Polvo' => 'Polvo', 'Agua' => 'Agua', 'HCFC' => 'HCFC', 'CO2' => 'CO2'), 'id_name' => 'tipo_extintor', 'required' => TRUE)
		);
		$this->requeridos = array('extintor_relevamiento_id', 'escuela_id', 'primer_carga', 'vencimiento', 'numero_registro', 'kilos', 'tipo_extintor');
		//$this->unicos = array();
		$this->default_join = array(
			array('escuela', 'escuela.id = extintor.escuela_id', 'left', array('escuela.nombre as escuela')),
			array('extintor_relevamiento', 'extintor_relevamiento.id = extintor.extintor_relevamiento_id', 'left', array('extintor_relevamiento.descripcion as extintor_relevamiento')),);
	}

	public function get_extintores_escuela($extintor_relevamiento_id, $escuela_id) {
		return $this->db
				->from('extintor ex')
				->where('ex.extintor_relevamiento_id', $extintor_relevamiento_id)
				->where('ex.escuela_id', $escuela_id)
				->order_by('ex.numero_registro')
				->get()->result();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		return TRUE;
	}
}
/* End of file Extintor_model.php */
/* Location: ./application/modules/extintores/models/Extintor_model.php */