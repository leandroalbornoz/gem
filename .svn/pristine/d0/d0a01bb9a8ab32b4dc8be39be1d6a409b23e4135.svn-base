<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Extintores_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_escuela_extintores($escuela_id) {
		return $this->db->select('escuela_id')
				->from('extintor_escuela')
				->where('escuela_id', $escuela_id)
				->get()
				->row();
	}

	public function get_vista($escuela_id, $data) {
		$return['escuela'] = $data['escuela'];
		$return['administrar'] = $data['administrar'];
		$relevamientos_db = $this->db->select('e.id, e.descripcion, e.fecha_desde, e.fecha_hasta, ee.extintores_faltantes, ee.observaciones')->from('extintor_relevamiento e')->join('extintor_escuela ee', 'e.id = ee.extintor_relevamiento_id')->where('ee.escuela_id', $escuela_id)->order_by('e.id desc')->get()->result();
		$relevamientos = array();
		foreach ($relevamientos_db as $relevamiento_db) {
			$relevamiento_db->extintores_cargados = 0;
			$relevamientos[$relevamiento_db->id] = $relevamiento_db;
		}
		$extintores_relevamiento_db = $this->db->query("
				SELECT e.*
				FROM extintor e
				WHERE e.escuela_id = ?
				", array($escuela_id)
			)->result();
		foreach ($extintores_relevamiento_db as $extintor_relevamiento_db) {
			$relevamientos[$extintor_relevamiento_db->extintor_relevamiento_id]->extintores[] = $extintor_relevamiento_db;
			$relevamientos[$extintor_relevamiento_db->extintor_relevamiento_id]->extintores_cargados++;
		}
		$return['relevamientos_extintores'] = $relevamientos;
		return $return;
	}
}
/* End of file Extintores_model.php */
/* Location: ./application/modules/extintores/models/Extintores_model.php */