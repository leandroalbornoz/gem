<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Elecciones_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_escuela_elecciones($escuela_id) {
		return $this->db->select('MAX(eleccion_id) eleccion_id, escuela_id')
				->from('eleccion_desinfeccion')
				->where('escuela_id', $escuela_id)
				->get()
				->row();
	}

	public function get_vista($escuela_id, $data, $activo=FALSE) {
		$return['escuela'] = $data['escuela'];
		$return['administrar'] = $data['administrar'];
		$elecciones_db = $this->db->select('e.id, e.descripcion, e.fecha_desde, e.fecha_hasta, ed.mesas, ed.celadores_permitidos, ed.fecha_cierre')->from('eleccion e')->join('eleccion_desinfeccion ed', 'e.id=ed.eleccion_id')->where('ed.escuela_id', $escuela_id)->order_by('e.id desc')->get()->result();
		$elecciones = array();
		foreach ($elecciones_db as $eleccion_db) {
			$eleccion_db->celadores_asignados = 0;
			$elecciones[$eleccion_db->id] = $eleccion_db;
		}
		$celadores_eleccion_db = $this->db->query("
				SELECT ed.eleccion_id, p.id, p.cuil, p.apellido, p.nombre
				FROM eleccion_desinfeccion ed
				JOIN eleccion_desinfeccion_persona edp ON ed.id=edp.eleccion_desinfeccion_id
				JOIN persona p ON p.id = edp.persona_id
				WHERE ed.escuela_id = ? AND edp.estado='Activo'
				", array($escuela_id)
			)->result();
		foreach ($celadores_eleccion_db as $celador_eleccion_db) {
			$elecciones[$celador_eleccion_db->eleccion_id]->celadores[] = $celador_eleccion_db;
			$elecciones[$celador_eleccion_db->eleccion_id]->celadores_asignados++;
		}
		$return['activo'] = $activo;
		$return['elecciones'] = $elecciones;
		return $return;
	}
}
/* End of file Elecciones_model.php */
/* Location: ./application/modules/elecciones/models/Elecciones_model.php */