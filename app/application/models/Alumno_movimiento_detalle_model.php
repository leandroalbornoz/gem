<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alumno_movimiento_detalle_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'alumno_movimiento_detalle';
		$this->msg_name = 'Alumno movimiento detalle';
		$this->id_name = 'id';
		$this->columnas = array('id', 'alumno_movimiento_id', 'alumno_division_id', 'accion');
		$this->fields = array(
			'accion' => array('label' => 'Accion', 'input_type' => 'combo', 'array' => array('Ingreso' => 'Ingreso', 'Egreso' => 'Egreso'), 'id_name' => 'accion')
		);

		$this->requeridos = array('alumno_movimiento_id');
//		$this->default_join = array(
//		);
	}

	public function get_alumnos($alumno_movimiento_id) {
		return $this->db->select("amd.id, ad.id as alumno_division_id, amd.accion, CONCAT(c.descripcion, ' ', d.division) as division, CONCAT(COALESCE(p.apellido, ''), ', ', COALESCE(p.nombre, '')) as persona, amd_post.id movimiento_posterior, a.id as alumno_id")
				->from('alumno_movimiento_detalle amd')
				->join('alumno_movimiento am', 'am.id = amd.alumno_movimiento_id', 'left')
				->join('alumno_movimiento_detalle amd_post', 'amd_post.alumno_division_id=amd.alumno_division_id and amd.id<amd_post.id', 'left')
				->join('alumno_division ad', 'ad.id = amd.alumno_division_id', 'left')
				->join('division d', 'd.id = ad.division_id', 'left')
				->join('curso c', 'c.id = d.curso_id', 'left')
				->join('alumno a', 'a.id = ad.alumno_id', 'left')
				->join('persona p', 'p.id = a.persona_id', 'left')
				->where('am.id', $alumno_movimiento_id)
				->group_by('ad.id')
				->get()->result();
	}

	public function alumno_movimiento_anterior($alumno_division_id, $id) {
		return $this->db->select('amd.id')
				->from('alumno_movimiento_detalle amd')
				->where('amd.alumno_division_id', $alumno_division_id)
				->where('amd.id >', $id)
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
/* End of file Alumno_movimiento_model.php */
/* Location: ./application/models/Alumno_movimiento_model.php */