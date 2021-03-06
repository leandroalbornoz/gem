<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alumno_cursada_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'alumno_cursada';
		$this->msg_name = 'Cursada de alumno';
		$this->id_name = 'id';
		$this->columnas = array('id', 'alumno_division_id', 'cursada_id');
		$this->fields = array(
			'persona' => array('label' => 'Persona'),
			'documento' => array('label' => 'Documento'),
		);
		$this->requeridos = array('alumno_division_id', 'cursada_id');
//$this->unicos = array();
		$this->default_join = array(
			array('cursada', 'cursada.id = alumno_cursada.cursada_id', 'left', array('cursada.id as cursada_id')),
			array('cursada_nota', 'cursada_nota.alumno_cursada_id = alumno_cursada.id', 'left', array('cursada_nota.id as cursada_nota_id')),
			array('evaluacion', 'cursada_nota.evaluacion_id = evaluacion.id', 'left', array('evaluacion.id as evaluacion_id')),
			array('alumno_division', 'alumno_division.id = alumno_cursada.alumno_division_id', 'left', array('alumno_division_id as alumno_division', 'alumno_division.fecha_desde', 'alumno_division.condicion', 'alumno_division.ciclo_lectivo')),
			array('division', 'alumno_division.division_id = division.id', 'left', array('')),
			array('curso', 'curso.id = division.curso_id', 'left', array('')),
			array('alumno', 'alumno_division.alumno_id = alumno.id', 'left', array('')),
			array('persona', 'alumno.persona_id = persona.id', 'left', array('CONCAT(documento_tipo.descripcion_corta , \' \',persona.documento ) as documento', 'CONCAT(COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona', 'persona.fecha_nacimiento')),
			array('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id', 'left', array('')),
			array('sexo', 'sexo.id = persona.sexo_id', 'left', array('sexo.descripcion as sexo')),
		);
	}

	public function get_alumnos_division($division_id, $cursada_id, $ciclo_lectivo) {
		return $this->db->select('ad.id, al.persona_id, al.observaciones, ad.fecha_desde, ad.fecha_hasta, '
					. 'p.documento, p.documento_tipo_id, CONCAT(COALESCE(p.apellido, \'\'), \', \', COALESCE(p.nombre, \'\')) as persona, '
					. 'p.fecha_nacimiento, s.descripcion sexo, dt.descripcion_corta as documento_tipo, ad.ciclo_lectivo, ad.condicion')
				->from('alumno al')
				->join('persona p', 'p.id = al.persona_id', 'left')
				->join('documento_tipo dt', 'dt.id = p.documento_tipo_id', 'left')
				->join('sexo s', 's.id = p.sexo_id', 'left')
				->join('alumno_division ad', 'ad.alumno_id = al.id', 'left')
				->join('alumno_cursada', 'alumno_cursada.alumno_division_id = ad.id AND alumno_cursada.cursada_id = ' . $cursada_id, 'left')
				->where('ad.division_id', $division_id)
				->where('ad.ciclo_lectivo', $ciclo_lectivo)
				->where('alumno_cursada.id IS NULL')
				->order_by('ad.ciclo_lectivo,p.apellido, p.nombre')
				->get()->result();
	}

	public function get_alumnos_cursada($cursada, $ciclo_lectivo, $alumnos) {
		if ($alumnos === 'Grupo') {
			return $this->db->select("alumno_cursada.id, alumno_division_id as alumno_division_id, persona.documento, persona.documento_tipo_id, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, persona.fecha_nacimiento, sexo.descripcion as sexo, documento_tipo.descripcion_corta as documento_tipo, alumno_division.ciclo_lectivo, alumno_division.condicion, alumno_division.fecha_desde, alumno_division.fecha_hasta, alumno_division.ciclo_lectivo")
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
					->where('alumno_cursada.cursada_id', $cursada->id)
					->get()->result();
		} else {
			return $this->db->select("alumno_cursada.id,cursada.id as cursada_id ,alumno_division.id as alumno_division_id, persona.documento, persona.documento_tipo_id, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, persona.fecha_nacimiento, sexo.descripcion as sexo,documento_tipo.descripcion_corta as documento_tipo, alumno_division.ciclo_lectivo, alumno_division.condicion, alumno_division.fecha_desde, alumno_division.fecha_hasta, alumno_division.ciclo_lectivo")
					->from('cursada')
					->join('division', 'division.id = cursada.division_id')
					->join('curso', 'division.curso_id = curso.id')
					->join('alumno_division', 'alumno_division.division_id = division.id AND alumno_division.ciclo_lectivo=' . $ciclo_lectivo)
					->join('alumno', 'alumno_division.alumno_id = alumno.id')
					->join('persona', 'persona.id = alumno.persona_id')
					->join('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id')
					->join('sexo', 'sexo.id = persona.sexo_id', 'left')
					->join('alumno_cursada', 'alumno_cursada.cursada_id = cursada.id AND alumno_division.id= alumno_cursada.alumno_division_id', 'left')
					->where('cursada.id', $cursada->id)
					->where("(cursada.alumnos='Todos' OR alumno_cursada.id IS NOT NULL)")
					->get()->result();
		}
	}
	public function get_notas_alumno($alumno_division_id){
		return $this->db->select("ad.id , cn.nota , m.descripcion AS materia, e.fecha, e.tema, et.descripcion, cur.calificacion_id")
			->from('alumno_division ad')
			->join('division d',' d.id = ad.division_id')
			->join('cursada c' ,' c.division_id = d.id')
			->join('evaluacion e' , 'e.cursada_id = c.id')
			->join('evaluacion_tipo et' , 'et.id = e.evaluacion_tipo_id')
			->join('alumno_cursada ac' , ' ac.alumno_division_id = ad.id and ac.cursada_id = c.id')
			->join('cursada_nota cn ' , ' cn.alumno_cursada_id = ac.id')
			->join('espacio_curricular ec ',' ec.id = c.espacio_curricular_id')
			->join('materia m ',' m.id = ec.materia_id')
			->join('curso cur ',' cur.id = ec.curso_id')
			->where('ad.id',$alumno_division_id)
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
/* End of file Alumno_cursada_model.php */
/* Location: ./application/models/Alumno_cursada_model.php */