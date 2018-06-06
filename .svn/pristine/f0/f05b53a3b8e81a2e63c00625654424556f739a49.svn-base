<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Extintor_escuela_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'extintor_escuela';
		$this->msg_name = 'Extintores Escuela';
		$this->id_name = 'id';
		$this->columnas = array('id', 'extintor_relevamiento_id', 'escuela_id', 'extintores_faltantes', 'observaciones');
		$this->fields = array(
			'extintores_faltantes' => array('label' => 'Extintores Faltantes', 'type' => 'number', 'min' => '0', 'max' => '10', 'required' => TRUE),
			'observaciones' => array('label' => 'Observaciones')
		);
		$this->requeridos = array('extintor_relevamiento_id', 'escuela_id', 'extintores_faltantes');
		//$this->unicos = array();
		$this->default_join = array(
			array('escuela', 'escuela.id = extintor_escuela.escuela_id', 'left', array('escuela.nombre as escuela')),
			array('extintor_relevamiento', 'extintor_relevamiento.id = extintor_escuela.extintor_relevamiento_id', 'left', array('extintor_relevamiento.descripcion as extintor_relevamiento')),);
	}

	public function get_relevamiento_escuela($eleccion_id, $escuela_id) {
		return $this->db
				->select("e.id, ee.id as ee_id, e.descripcion extintor_relevamiento, e.fecha_desde, e.fecha_hasta, ee.escuela_id, ee.extintores_faltantes, ee.observaciones, ex.cantidad as extintores_cargados")
				->from('extintor_relevamiento e')
				->join('extintor_escuela ee', 'ee.extintor_relevamiento_id = e.id')
				->join('(SELECT extintor_relevamiento_id, COUNT(1) cantidad FROM extintor ex GROUP BY extintor_relevamiento_id) ex', 'ex.extintor_relevamiento_id = e.id', 'left')
				->where('ee.extintor_relevamiento_id', $eleccion_id)
				->where('ee.escuela_id', $escuela_id)
				->get()->row();
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
/* End of file Extintor_escuela_model.php */
/* Location: ./application/modules/extintores/models/Extintor_escuela_model.php */