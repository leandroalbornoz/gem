<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluar_operativo_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'evaluar_operativo';
		$this->msg_name = 'Evaluacion';
		$this->id_name = 'id';
		$this->columnas = array('id', 'fecha_carga', 'alumno_id', 'alumno_division_id', 'puntuacion_1', 'puntuacion_2', 'puntuacion_3', 'puntuacion_4', 'puntuacion_5', 'puntuacion_6a', 'puntuacion_6b', 'puntuacion_7', 'puntuacion_8', 'puntuacion_9', 'puntuacion_10a', 'puntuacion_10b', 'puntuacion_11a', 'puntuacion_11b', 'puntuacion_11c', 'estado');
		$this->fields = array(
			'puntuacion_1' => array('label' => '1- Reconoce clase textual. (1)', 'input_type' => 'combo', 'class' => 'punto', 'required' => TRUE, 'array' => array('0' => 0, '1' => 1), 'id_name' => 'puntuacion_1', 'tabindex' => '1'),
			'puntuacion_2' => array('label' => '2- Reconoce información explícita. (1)', 'input_type' => 'combo', 'class' => 'punto', 'required' => TRUE, 'array' => array('0' => 0, '1' => 1), 'id_name' => 'puntuacion_2', 'tabindex' => '2'),
			'puntuacion_3' => array('label' => '3- Reconoce información explícita. (1)', 'input_type' => 'combo', 'class' => 'punto', 'required' => TRUE, 'array' => array('0' => 0, '1' => 1), 'id_name' => 'puntuacion_3', 'tabindex' => '3'),
			'puntuacion_4' => array('label' => '4- Reconoce información explícita. (1)', 'input_type' => 'combo', 'class' => 'punto', 'required' => TRUE, 'array' => array('0' => 0, '1' => 1), 'id_name' => 'puntuacion_4', 'tabindex' => '4'),
			'puntuacion_5' => array('label' => '5- Reconoce información explícita. (1)', 'input_type' => 'combo', 'class' => 'punto', 'required' => TRUE, 'array' => array('0' => 0, '1' => 1), 'id_name' => 'puntuacion_5', 'tabindex' => '5'),
			'puntuacion_6a' => array('label' => '6a- Reconoce tipo de grafías. (1)', 'input_type' => 'combo', 'class' => 'punto', 'required' => TRUE, 'array' => array('0' => 0, '1' => 1), 'id_name' => 'puntuacion_6a', 'tabindex' => '6'),
			'puntuacion_6b' => array('label' => '6b- Conexión fonema grafema. (1)', 'input_type' => 'combo', 'class' => 'punto', 'required' => TRUE, 'array' => array('0' => 0, '1' => 1), 'id_name' => 'puntuacion_6b', 'tabindex' => '7'),
			'puntuacion_7' => array('label' => '7- Identifica la palabra más larga. (1)', 'input_type' => 'combo', 'class' => 'punto', 'required' => TRUE, 'array' => array('0' => 0, '1' => 1), 'id_name' => 'puntuacion_7', 'tabindex' => '8'),
			'puntuacion_8' => array('label' => '8- Traducción dibujo - palabra. (1)', 'input_type' => 'combo', 'class' => 'punto', 'required' => TRUE, 'array' => array('0' => 0, '1' => 1), 'id_name' => 'puntuacion_8', 'tabindex' => '9'),
			'puntuacion_9' => array('label' => '9- Traducción letra - número. (2)', 'input_type' => 'combo', 'class' => 'punto', 'required' => TRUE, 'array' => array('0' => 0, '1' => 1, '2' => 2), 'id_name' => 'puntuacion_9', 'tabindex' => '10'),
			'puntuacion_10a' => array('label' => '10a- Identifica datos e incógnitas. (3)', 'input_type' => 'combo', 'class' => 'punto', 'required' => TRUE, 'array' => array('0' => 0, '1' => 1, '2' => 2, '3' => 3), 'id_name' => 'puntuacion_10a', 'tabindex' => '11'),
			'puntuacion_10b' => array('label' => '10b- Compara procedimientos usados para resolver problemas y determina procedimientos más económicos para la obtención de un resultado concreto. (4)', 'input_type' => 'combo', 'class' => 'punto', 'required' => TRUE, 'array' => array('0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4), 'id_name' => 'puntuacion_10b', 'tabindex' => '12'),
			'puntuacion_11a' => array('label' => '11a -Traducción idea - palabra. (2)', 'input_type' => 'combo', 'class' => 'punto', 'required' => TRUE, 'array' => array('0' => 0, '1' => 1, '2' => 2), 'id_name' => 'puntuacion_11a', 'tabindex' => '13'),
			'puntuacion_11b' => array('label' => '11b- Dominio de unidades escritas (palabras, frase, oración, párrafo). (3)', 'input_type' => 'combo', 'class' => 'punto', 'required' => TRUE, 'array' => array('0' => 0, '1' => 1, '2' => 2, '3' => 3), 'id_name' => 'puntuacion_11b', 'tabindex' => '14'),
			'puntuacion_11c' => array('label' => '11c- Distribución gráfico espacial. (2)', 'input_type' => 'combo', 'class' => 'punto', 'required' => TRUE, 'array' => array('0' => 0, '1' => 1, '2' => 2), 'id_name' => 'puntuacion_11c', 'tabindex' => '15')
		);
		$this->requeridos = array('puntuacion_1', 'puntuacion_2', 'puntuacion_3', 'puntuacion_4', 'puntuacion_5', 'puntuacion_6a', 'puntuacion_6b', 'puntuacion_7', 'puntuacion_8', 'puntuacion_9', 'puntuacion_10a', 'puntuacion_10b', 'puntuacion_11a', 'puntuacion_11b', 'puntuacion_11c');
		//$this->unicos = array();
		$this->default_join = array(
			array('alumno', 'alumno.id = evaluar_operativo.alumno_id', 'left', array('alumno.persona_id as alumno')),);
	}

	public function get_vista($escuela_id, $data) {
		$return['escuela'] = $data['escuela'];
		$return['divisiones'] = $this->get_divisiones($escuela_id, 2017);
		$return['administrar'] = $data['administrar'];
		return $return;
	}

	public function get_escuelas() {
		return $this->db->select('escuela.id, escuela.numero, escuela.anexo, escuela.cue, escuela.subcue, escuela.nombre, escuela.calle, escuela.calle_numero, escuela.departamento, escuela.piso, escuela.barrio, escuela.manzana, escuela.casa, escuela.localidad_id, escuela.nivel_id, escuela.reparticion_id, escuela.supervision_id, escuela.regional_id, escuela.dependencia_id, escuela.zona_id, escuela.fecha_creacion, escuela.anio_resolucion, escuela.numero_resolucion, escuela.telefono, escuela.email, escuela.fecha_cierre, escuela.anio_resolucion_cierre, escuela.numero_resolucion_cierre, dependencia.descripcion as dependencia, nivel.descripcion as nivel, regional.descripcion as regional, CONCAT(jurisdiccion.codigo,\'/\',reparticion.codigo) as jurirepa, supervision.nombre as supervision, zona.descripcion as zona')
				->from('escuela')
				->join('dependencia', 'dependencia.id = escuela.dependencia_id', 'left')
				->join('nivel', 'nivel.id = escuela.nivel_id', 'left')
				->join('regional', 'regional.id = escuela.regional_id', 'left')
				->join('reparticion', 'reparticion.id = escuela.reparticion_id', 'left')
				->join('jurisdiccion', 'jurisdiccion.id = reparticion.jurisdiccion_id', 'left')
				->join('supervision', 'supervision.id = escuela.supervision_id', 'left')
				->join('zona', 'zona.id = escuela.zona_id', 'left')
				->where('nivel.id', 2)
				->get()->result();
	}

	public function get_divisiones($escuela_id, $ciclo_lectivo = 2017) {
		return $this->db->select('division.id, division.escuela_id, division.turno_id, division.curso_id, division.division, division.fecha_alta, division.fecha_baja, curso.descripcion as curso, modalidad.descripcion as modalidad, escuela.nombre as escuela, turno.descripcion as turno, carrera.descripcion as carrera, SUM(CASE WHEN eo.estado=\'Presente\' THEN 1 ELSE 0 END) as total_cargados, COUNT(DISTINCT alumno_division.alumno_id) as total_alumnos, SUM(CASE WHEN eo.estado = \'Ausente\' THEN 1 ELSE 0 END) as total_ausentes')
				->from('division')
				->join('carrera', 'carrera.id = division.carrera_id', 'left')
				->join('curso', 'curso.id = division.curso_id')
				->join('escuela', 'escuela.id = division.escuela_id')
				->join('turno', 'turno.id = division.turno_id', 'left')
				->join('modalidad', 'modalidad.id = division.modalidad_id', 'left')
				->join('alumno_division', "alumno_division.division_id = division.id AND COALESCE(alumno_division.fecha_hasta,'2017-12-01')>='2017-12-01'")
				->join('alumno', 'alumno_division.alumno_id = alumno.id')
				->join('evaluar_operativo eo', 'alumno.id = eo.alumno_id', 'left')
				->where('division.escuela_id', $escuela_id)
				->where('alumno_division.ciclo_lectivo', $ciclo_lectivo)
				->where("(curso.id = 8 OR (curso.id = 71 AND division.division LIKE '%2%'))")
				->order_by('curso.id ASC ,division ASC')
				->group_by('division.id')
				->get()->result();
	}

	public function get_porcentaje_carga($escuela_id, $ciclo_lectivo) {
		return $this->db
				->select("e.id, e.numero, e.anexo, e.nombre,(count(DISTINCT eo.id))/count(DISTINCT ad.id) as porcentaje_cargado")
				->from('alumno_division ad')
				->join('evaluar_operativo eo', 'ad.id = eo.alumno_division_id', 'left')
				->join('division d', 'ad.division_id = d.id')
				->join('curso c', "c.id = d.curso_id AND (c.id = 8 OR (c.id = 71 AND d.division LIKE '%2%'))")
				->join('escuela e', 'e.id = d.escuela_id')
				->join('nivel n', 'e.nivel_id = n.id AND e.nivel_id=2')
				->join('regional reg', 'reg.id = e.regional_id', 'left')
				->join('reparticion rep', 'rep.id = e.reparticion_id', 'left')
				->join('jurisdiccion jur', 'jur.id = rep.jurisdiccion_id', 'left')
				->join('supervision s', 's.id = e.supervision_id', 'left')
				->where('e.id', $escuela_id)
				->where('ad.ciclo_lectivo', $ciclo_lectivo)
				->where("COALESCE(ad.fecha_hasta,'2017-12-01')>='2017-12-01'")
				->group_by('e.id, e.anexo')
				->get()->row();
	}

	public function get_alumnos_division($division_id, $ciclo_lectivo) {
		return $this->db->select("alumno_division.id, alumno.id as alumno_id,division.id as division_id, alumno_division.ciclo_lectivo, alumno_division.fecha_desde, CONCAT(documento_tipo.descripcion_corta,' ', persona.documento) as documento, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, CASE curso.grado_multiple WHEN 'Si' THEN c_gm.descripcion ELSE division.division END as division, curso.descripcion as curso, division.escuela_id, COALESCE(CONCAT(CASE WHEN sexo.id=1 THEN 'M' WHEN sexo.id=2 THEN 'F' ELSE '' END), ' ') as sexo, COALESCE(eo.id,'') as evaluacion_operativo_id,(eo.puntuacion_1 + eo.puntuacion_2 + eo.puntuacion_3 + eo.puntuacion_4 + eo.puntuacion_5 + eo.puntuacion_6a + eo.puntuacion_6b + eo.puntuacion_7 + eo.puntuacion_8 + eo.puntuacion_9 + eo.puntuacion_11a + eo.puntuacion_11b + eo.puntuacion_11c) as puntuacion_1,(eo.puntuacion_10a + eo.puntuacion_10b) as puntuacion_2, COALESCE(eo.estado,'') as estado")
				->from('alumno')
				->join('persona', 'persona.id = alumno.persona_id', 'left')
				->join('sexo', 'sexo.id = persona.sexo_id', 'left')
				->join('alumno_division', 'alumno_division.alumno_id = alumno.id', 'left')
				->join('division', 'division.id = alumno_division.division_id')
				->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id', 'left')
				->join('curso', 'division.curso_id = curso.id')
				->join('curso c_gm', 'alumno_division.curso_id = c_gm.id', 'left')
				->join('evaluar_operativo eo', 'alumno.id = eo.alumno_id', 'left')
				->where('division.id', $division_id)
				->where("COALESCE(alumno_division.fecha_hasta,'2017-12-01')>='2017-12-01'")
				->where('ciclo_lectivo', $ciclo_lectivo)
				->order_by('sexo DESC, persona.fecha_nacimiento,persona.nombre ASC')
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
/* End of file Evaluar_operativo_model.php */
/* Location: ./application/models/Evaluar_operativo_model.php */