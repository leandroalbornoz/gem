<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cargo_cursada_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'cargo_cursada';
		$this->msg_name = ' Cargo de Cursada';
		$this->id_name = 'id';
		$this->columnas = array('id', 'cargo_id', 'cursada_id', 'carga_horaria');
		$this->fields = array(
			'persona' => array('label' => 'Persona', 'readonly' => TRUE),
			'espacio_curricular' => array('label' => 'Espacio curricular', 'readonly' => TRUE),
			'division' => array('label' => 'Curso/División', 'readonly' => TRUE),
			'grupo' => array('label' => 'Grupo', 'readonly' => TRUE),
			'carga_horaria' => array('label' => 'Carga horaria', 'type' => 'number'),
		);
		$this->requeridos = array('cargo_id', 'cursada_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('cursada', 'cursada.id = cargo_cursada.cursada_id', 'left', array('cursada.grupo')),
			array('espacio_curricular', 'espacio_curricular.id = cursada.espacio_curricular_id', 'left', array('espacio_curricular.id as espacio_curricular_id')),
			array('materia', 'materia.id = espacio_curricular.materia_id', 'left', array('materia.descripcion as espacio_curricular')),
			array('division', 'division.id = cursada.division_id', 'left', array('division.id as division_id, CONCAT(curso.descripcion,\' \',division.division) as division')),
			array('curso', 'curso.id = division.curso_id', 'left', array('curso.descripcion as curso')),
			array('cargo', 'cargo.id = cargo_cursada.cargo_id', 'left', array('')),
			array('servicio sp', 'sp.id = (SELECT id FROM servicio s WHERE cargo_id = cargo.id AND s.fecha_baja IS NULL ORDER BY fecha_alta DESC LIMIT 1)', 'left', array('')),
			array('persona', 'persona.id = sp.persona_id', 'left', array('CONCAT(COALESCE(persona.cuil, \'\'), \' \', COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona')),
		);
	}

	public function get_cursada_carga_horaria($cursada_id) {
		return $this->db->select('cargo_cursada.id, cursada.carga_horaria,SUM(cargo_cursada.carga_horaria) as carga_horaria_cubierta')
				->from('cursada')
				->join('cargo_cursada', 'cargo_cursada.cursada_id= cursada.id', 'left')
				->where('cargo_cursada.cursada_id', $cursada_id)
				->get()->row();
	}

	public function get_cargos_cursada($cursada_id) {
		return $this->db->select('cargo_cursada.id, cargo.id as cargo_id, condicion_cargo.descripcion as condicion_cargo, curso.descripcion as curso, division.division as division, turno.descripcion as turno, cargo.aportes, '
					. 'CONCAT(regimen.codigo, \' \', regimen.descripcion, \'<br>\', COALESCE(materia.descripcion, \'\')) as regimen_materia, '
					. 'cargo.observaciones, cargo_cursada.carga_horaria, '
					. 'CONCAT(COALESCE(persona.cuil, \'\'), \'<br>\', COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona, CONCAT(COALESCE(persona.cuil, \'\'), \' \', COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as cargo_persona')
				->from('cargo_cursada')
				->join('cargo', 'cargo_cursada.cargo_id= cargo.id AND cargo.fecha_hasta IS NULL')
				->join('turno', 'turno.id = cargo.turno_id', 'left')
				->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', 'left')
				->join('division', 'division.id = cargo.division_id', 'left')
				->join('curso', 'curso.id = division.curso_id', 'left')
				->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
				->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
				->join('regimen', 'regimen.id = cargo.regimen_id', '')
				->join('servicio sp', "sp.id = (SELECT id FROM servicio s WHERE cargo_id = cargo.id AND s.fecha_baja IS NULL ORDER BY fecha_alta DESC LIMIT 1)", 'left', false)
				->join('persona', 'persona.id = sp.persona_id', 'left')
				->where('cargo_cursada.cursada_id', $cursada_id)
				->get()->result();
	}

	public function get_cursadas_by_cargo($cargo_id) {
		return $this->db->select("cargo_cursada.id, cargo_cursada.carga_horaria, cursada.division_id,CONCAT(curso.descripcion,' ',division.division) as division, materia.descripcion as espacio_curricular, cursada.grupo")
				->from('cargo_cursada')
				->join('cargo', 'cargo_cursada.cargo_id = cargo.id')
				->join('cursada', 'cargo_cursada.cursada_id = cursada.id')
				->join('espacio_curricular', 'cursada.espacio_curricular_id = espacio_curricular.id')
				->join('materia', 'espacio_curricular.materia_id = materia.id')
				->join('division', 'cursada.division_id = division.id')
				->join('curso', 'curso.id = division.curso_id')
				->where('cargo_cursada.cargo_id', $cargo_id)
				->get()->result();
	}

	public function buscar_cargos_escuela($escuela_id, $cursada_id) {
		return $this->db->select('cargo.id, condicion_cargo.descripcion as condicion_cargo, curso.descripcion as curso, division.division as division, turno.descripcion as turno, cargo.aportes, '
					. 'CONCAT(regimen.codigo, \' \', regimen.descripcion, \' \', COALESCE(materia.descripcion, \'\')) as regimen_materia, '
					. 'cargo.observaciones, cargo.carga_horaria, COUNT(DISTINCT servicio.id) as servicios, '
					. 'CONCAT(COALESCE(persona.cuil, \'\'), \' \', COALESCE(persona.apellido,\'\'), \', \', COALESCE(persona.nombre, \'\')) as persona')
				->from('cargo')
				->join('area', 'area.id = cargo.area_id', 'left')
				->join('turno', 'turno.id = cargo.turno_id', 'left')
				->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', 'left')
				->join('division', 'division.id = cargo.division_id', 'left')
				->join('curso', 'curso.id = division.curso_id', 'left')
				->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
				->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
				->join('regimen', 'regimen.id = cargo.regimen_id', 'left')
				->join('servicio', "servicio.cargo_id = cargo.id AND servicio.fecha_baja IS NULL", 'left')
				->join('servicio sp', "sp.id = (SELECT id FROM servicio s WHERE cargo_id = cargo.id AND s.fecha_baja IS NULL ORDER BY fecha_alta DESC LIMIT 1)", 'left', false)
				->join('persona', 'persona.id = sp.persona_id', 'left')
				->join('cargo_cursada', "cargo_cursada.cargo_id = cargo.id AND cargo_cursada.cursada_id=$cursada_id", 'left')
				->where('cargo.escuela_id', $escuela_id)
				->where('cargo.fecha_hasta IS NULL')
				->where('cargo_cursada.id IS NULL')
				->group_by('cargo.id, condicion_cargo.descripcion, curso.descripcion, division.division, materia.descripcion, cargo.carga_horaria, regimen.codigo, regimen.descripcion')
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
/* End of file Cargo_cursada_model.php */
/* Location: ./application/models/Cargo_cursada_model.php */