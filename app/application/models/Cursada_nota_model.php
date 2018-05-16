<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cursada_nota_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'cursada_nota';
		$this->msg_name = 'Nota de cursada';
		$this->id_name = 'id';
		$this->columnas = array('id', 'evaluacion_id', 'alumno_cursada_id', 'nota', 'asistencia');
		$this->fields = array(
			'evaluacion' => array('label' => 'Evaluación', 'required' => TRUE),
			'cursada' => array('label' => 'Cursada', 'required' => TRUE),
			'documento' => array('label' => 'Documento'),
			'persona' => array('label' => 'Persona'),
			'nota' => array('label' => 'Nota', 'type' => 'number', 'max' => 10, 'min' => 0),
			'asistencia' => array('label' => 'Asistencia', 'input_type' => 'combo', 'id_name' => 'asistencia_id', 'required' => TRUE, 'array' => array('Presente' => 'Presente', 'Ausente' => 'Ausente'))
		);
		$this->requeridos = array('evaluacion_id', 'alumno_cursada_id', 'nota', 'asistencia');
		//$this->unicos = array();
		$this->default_join = array(
			array('alumno_cursada', 'alumno_cursada.id = cursada_nota.alumno_cursada_id', 'left', array('alumno_cursada.id as alumno_cursada')),
			array('evaluacion', 'evaluacion.id = cursada_nota.evaluacion_id', 'left', array('evaluacion.tema as evaluacion', 'evaluacion.id as evaluacion_id')),
			array('evaluacion_tipo', 'evaluacion_tipo.id = evaluacion.evaluacion_tipo_id', 'left', array('evaluacion_tipo.descripcion as evaluacion_tipo')),
			array('cursada', 'cursada.id = evaluacion.cursada_id', 'left', array('cursada.id as cursada')),
			array('alumno_division', 'alumno_cursada.alumno_division_id = alumno_division.id', 'left', array('')),
			array('division', 'division.id = alumno_division.division_id', 'left', array('')),
			array('curso', 'division.curso_id = curso.id', 'left', array('')),
			array('alumno', 'alumno_division.alumno_id = alumno.id', 'left', array('')),
			array('persona', 'persona.id = alumno.persona_id', 'left', array("CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona", "CONCAT(documento_tipo.descripcion_corta,' ',persona.documento) as documento")),
			array('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id', 'left', array('')),
			array('sexo', 'sexo.id = persona.sexo_id', 'left', array('')),
		);
	}

	public function get_alumnos_evaluacion($evaluacion) {
		return $this->db->select("alumno_cursada.id, alumno_division_id as alumno_division, persona.documento, persona.documento_tipo_id, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, persona.fecha_nacimiento, sexo.descripcion as sexo, documento_tipo.descripcion_corta as documento_tipo, alumno_division.ciclo_lectivo, alumno_division.condicion, alumno_division.fecha_desde, alumno_division.fecha_hasta, cursada_nota.nota as nota_evaluacion, COALESCE(evaluacion_tipo.descripcion,'') as evaluacion_tipo, cursada_nota.asistencia as asistencia, cursada_nota.id as cursada_nota_id")
				->from('alumno_cursada')
				->join('evaluacion', "evaluacion.id=$evaluacion->id")
				->join('cursada', 'cursada.id = alumno_cursada.cursada_id')
				->join('cursada_nota', 'cursada_nota.alumno_cursada_id = alumno_cursada.id AND cursada_nota.evaluacion_id = evaluacion.id', 'left')
				->join('evaluacion_tipo', 'evaluacion_tipo.id = evaluacion.evaluacion_tipo_id')
				->join('alumno_division', 'alumno_cursada.alumno_division_id = alumno_division.id')
				->join('division', 'division.id = alumno_division.division_id')
				->join('curso', 'division.curso_id = curso.id')
				->join('alumno', 'alumno_division.alumno_id = alumno.id')
				->join('persona', 'persona.id = alumno.persona_id')
				->join('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id')
				->join('sexo', 'sexo.id = persona.sexo_id', 'left')
				->where('alumno_division.ciclo_lectivo', $evaluacion->ciclo_lectivo)
				->where('alumno_cursada.cursada_id', $evaluacion->cursada_id)
				->where("evaluacion.fecha BETWEEN alumno_division.fecha_desde AND COALESCE(alumno_division.fecha_hasta, evaluacion.fecha)")
				->get()->result();
	}

	public function verificar_cursada_nota($evaluacion_id) {
		return $this->db->select('id')
				->from('cursada_nota')
				->where('cursada_nota.evaluacion_id', $evaluacion_id)
				->get()->result();
	}

	public function get_alumnos_cursada($cursada_id, $ciclo_lectivo) {
		return $this->db->select("alumno_cursada.id, alumno_division_id as alumno_division, persona.documento, persona.documento_tipo_id, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, persona.fecha_nacimiento, sexo.descripcion as sexo, documento_tipo.descripcion_corta as documento_tipo, alumno_division.ciclo_lectivo, alumno_division.condicion, alumno_division.fecha_desde, alumno_division.fecha_hasta, alumno_division.ciclo_lectivo")
				->from('alumno_cursada')
				->join('cursada', 'cursada.id = alumno_cursada.cursada_id')
				->join('alumno_division', 'alumno_cursada.alumno_division_id = alumno_division.id', 'left')
				->join('division', 'division.id = alumno_division.division_id', 'left')
				->join('curso', 'division.curso_id = curso.id', 'left')
				->join('alumno', 'alumno_division.alumno_id = alumno.id', 'left')
				->join('persona', 'persona.id = alumno.persona_id', 'left')
				->join('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id', 'left')
				->join('sexo', 'sexo.id = persona.sexo_id', 'left')
				->where('alumno_division.ciclo_lectivo', $ciclo_lectivo)
				->where('alumno_cursada.cursada_id', $cursada_id)
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
/* End of file Cursada_nota_model.php */
/* Location: ./application/models/Cursada_nota_model.php */