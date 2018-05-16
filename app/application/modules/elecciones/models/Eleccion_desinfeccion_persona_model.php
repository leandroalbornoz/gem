<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Eleccion_desinfeccion_persona_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'eleccion_desinfeccion_persona';
		$this->msg_name = 'Desinfeccion de Escuela';
		$this->id_name = 'id';
		$this->columnas = array('id', 'eleccion_desinfeccion_id', 'persona_id', 'estado', 'fecha_carga', 'fecha_anulacion');
		$this->fields = array(
			'nombre' => array('label' => 'Nombre', 'readonly' => TRUE),
			'cuil' => array('label' => 'Cuil', 'readonly' => TRUE),
		);
		$this->requeridos = array();
		//$this->unicos = array();
	}

	public function get_celadores_escuela($eleccion_id, $escuela_id) {
		return $this->db
				->select("edp.id, ed.escuela_id, edp.persona_id, edp.estado, edp.fecha_carga, edp.fecha_anulacion, p.nombre, p.apellido, p.cuil")
				->from('eleccion_desinfeccion_persona edp')
				->join('persona p', 'p.id = edp.persona_id')
				->join('eleccion_desinfeccion ed', 'edp.eleccion_desinfeccion_id = ed.id ')
				->join('escuela e', 'e.id = ed.escuela_id')
				->where('ed.eleccion_id', $eleccion_id)
				->where('e.id', $escuela_id)
				->order_by('edp.estado, edp.fecha_anulacion, edp.fecha_carga')
//			 	->where('edp.estado', 'Activo')
				->get()->result();
	}

	public function get_celador($persona_id) {
		return $this->db
				->select("CONCAT(COALESCE(p.apellido,''),' , ', COALESCE(p.nombre,'')) as nombre, p.cuil")
				->from('persona p')
				->where('p.id', $persona_id)
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