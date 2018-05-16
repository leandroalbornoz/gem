<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Espacio_curricular_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'espacio_curricular';
		$this->msg_name = 'Espacio curricular';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'carrera_id', 'carga_horaria', 'materia_id', 'curso_id', 'fecha_desde', 'fecha_hasta', 'grupo_id', 'resolucion_alta', 'codigo_junta', 'cuatrimestre');
		$this->fields = array(
			'carrera' => array('label' => 'Carrera', 'readonly' => TRUE),
			'carga_horaria' => array('label' => 'Carga Horaria', 'type' => 'integer', 'maxlength' => '2'),
			'materia' => array('label' => 'Materia', 'input_type' => 'combo', 'id_name' => 'materia_id', 'required' => TRUE),
			'curso' => array('label' => 'Curso', 'readonly' => TRUE),
			'resolucion_alta' => array('label' => 'Resolución', 'required' => TRUE),
			'codigo_junta' => array('label' => 'Código Junta', 'type' => 'integer'),
			'fecha_desde' => array('label' => 'Desde', 'type' => 'date'),
			'fecha_hasta' => array('label' => 'Hasta', 'type' => 'date'),
			'cuatrimestre' => array('label' => 'Cuatrimestre', 'input_type' => 'combo', 'id_name' => 'cuatrimestre', 'array' => array('0' => 'Anual', '1' => '1°', '2' => '2°'))
		);
		$this->requeridos = array();
		//$this->unicos = array();
		$this->default_join = array(
			array('carrera', 'carrera.id = espacio_curricular.carrera_id', 'left', array('carrera.descripcion as carrera')),
			array('curso', 'curso.id = espacio_curricular.curso_id', 'left', array('curso.descripcion as curso', 'curso.nivel_id')),
			array('materia', 'materia.id = espacio_curricular.materia_id', 'left', array('materia.descripcion as materia'))
		);
	}

	public function get_extracurriculares($division_id) {
		$espacios_extracurriculares = $this->db->select('espacio_curricular.id, materia.descripcion as materia')
				->from('espacio_curricular')
				->join('materia', 'materia.id=espacio_curricular.materia_id')
				->join('division', 'espacio_curricular.curso_id=division.curso_id')
				->where('espacio_curricular.carrera_id IS NULL')->where('division.id', $division_id)->get()->result();
		$array_espacio_extracurricular = array();
		if (!empty($espacios_extracurriculares)) {
			foreach ($espacios_extracurriculares as $espacio_extracurricular) {
				$array_espacio_extracurricular[$espacio_extracurricular->id] = $espacio_extracurricular->materia;
			}
		}
		return $array_espacio_extracurricular;
	}
	
	public function get_cargos($espacio_curricular_id) {
		return $this->db->select("c.id, e.numero, e.anexo, e.nombre escuela, cu.descripcion as curso, d.division as division")
				->from('cargo c')
				->join('escuela e', 'e.id = c.escuela_id')
				->join('division d', 'd.id = c.division_id', 'left')
				->join('curso cu', 'cu.id = d.curso_id', 'left')
				->where('c.espacio_curricular_id', $espacio_curricular_id)
				->order_by('e.numero, e.anexo, cu.descripcion, d.division, c.id')
				->get()->result();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('espacio_curricular_id', $delete_id)->count_all_results('cargo') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a cargo.');
			return FALSE;
		}
		if ($this->db->where('espacio_curricular_id', $delete_id)->count_all_results('cargo_historial') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a historial de cargo.');
			return FALSE;
		}
		if ($this->db->where('espacio_curricular_id', $delete_id)->count_all_results('cursada') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a cursada.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Espacio_curricular_model.php */
/* Location: ./application/models/Espacio_curricular_model.php */