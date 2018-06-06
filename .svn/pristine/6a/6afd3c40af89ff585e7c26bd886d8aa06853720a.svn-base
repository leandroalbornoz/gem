<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tem_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_vista($escuela_id, $data) {
		$return['escuela'] = $data['escuela'];
		$return['administrar'] = $data['administrar'];
		$proyectos_db = $this->db->select('p.id, p.descripcion, p.fecha_desde, p.fecha_hasta, pe.horas_catedra, ms.mes, ms.semanas')->from('tem_proyecto_escuela pe')->join('tem_proyecto p', 'p.id=pe.tem_proyecto_id')->join('tem_mes_semana ms', 'ms.tem_proyecto_id=p.id')->where('pe.escuela_id', $escuela_id)->get()->result();
		$proyectos = array();
		foreach ($proyectos_db as $proyecto_db) {
			if (!isset($proyectos[$proyecto_db->id])) {
				$proyectos[$proyecto_db->id] = new stdClass();
				$proyectos[$proyecto_db->id]->id = $proyecto_db->id;
				$proyectos[$proyecto_db->id]->descripcion = $proyecto_db->descripcion;
				$proyectos[$proyecto_db->id]->horas_catedra = $proyecto_db->horas_catedra;
				$proyectos[$proyecto_db->id]->horas_disponibles = $proyecto_db->horas_catedra;
				$proyectos[$proyecto_db->id]->horas_asignadas = 0;
				$proyectos[$proyecto_db->id]->fecha_desde = $proyecto_db->fecha_desde;
				$proyectos[$proyecto_db->id]->fecha_hasta = $proyecto_db->fecha_hasta;
			}
			$proyectos[$proyecto_db->id]->meses[] = $proyecto_db;
		}
		$horas_db = $this->db->query("
				SELECT p.id, SUM(c.carga_horaria) horas_asignadas
				FROM tem_proyecto_escuela pe 
				JOIN tem_proyecto p ON p.id=pe.tem_proyecto_id
				JOIN cargo c ON c.escuela_id=pe.escuela_id
				JOIN servicio s ON s.cargo_id = c.id  AND s.fecha_alta BETWEEN p.fecha_desde AND p.fecha_hasta AND (s.fecha_baja IS NULL OR s.fecha_baja=p.fecha_hasta)
				JOIN condicion_cargo cc ON cc.id = c.condicion_cargo_id AND cc.planilla_modalidad_id = 2
				WHERE c.escuela_id = ?
				GROUP BY p.id
				", array($escuela_id)
			)->result();
		foreach ($horas_db as $horas) {
			$proyectos[$horas->id]->horas_asignadas = $horas->horas_asignadas;
			$proyectos[$horas->id]->horas_disponibles = $proyectos[$horas->id]->horas_catedra - $proyectos[$horas->id]->horas_asignadas;
		}
		$personal_tem_db = $this->db->query("
				SELECT pr.id proyecto_id, s.id, s.persona_id, s.cargo_id, s.fecha_alta, c.escuela_id, p.cuil, s.fecha_baja, 
				s.situacion_revista_id, c.carga_horaria, p.apellido, p.nombre, sr.descripcion as situacion_revista,
				CONCAT(r.codigo, '-', r.descripcion) as regimen
				FROM tem_proyecto_escuela pe 
				JOIN tem_proyecto pr ON pr.id=pe.tem_proyecto_id
				JOIN cargo c ON c.escuela_id=pe.escuela_id
				JOIN servicio s ON s.cargo_id = c.id  AND s.fecha_alta BETWEEN pr.fecha_desde AND pr.fecha_hasta AND (s.fecha_baja IS NULL OR s.fecha_baja=pr.fecha_hasta)
				JOIN condicion_cargo cc ON cc.id = c.condicion_cargo_id AND cc.planilla_modalidad_id = 2
				JOIN persona p ON p.id = s.persona_id
				JOIN situacion_revista sr ON sr.id = s.situacion_revista_id
				JOIN regimen r ON c.regimen_id = r.id
				WHERE c.escuela_id = ?
				", array($escuela_id)
			)->result();
		foreach ($personal_tem_db as $personal_tem) {
			$proyectos[$personal_tem->proyecto_id]->personal[] = $personal_tem;
		}
		$return['proyectos'] = $proyectos;
		return $return;
	}

	public function get_cargos_tem($escuela_id) {
		return $this->db->select('cargo.id, cargo.carga_horaria, cargo.fecha_desde, servicio.fecha_alta, servicio.fecha_baja')
				->from('cargo')
				->join('servicio', 'servicio.cargo_id = cargo.id')
				->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id')
				->where('cargo.escuela_id', $escuela_id)
				->where('condicion_cargo.planilla_modalidad_id = 2')
				->order_by('servicio.fecha_alta')
				->get()
				->result();
	}

	public function get_novedades_tem($planilla_id) {
		return $this->db
				->select('servicio_novedad.id, servicio_novedad.planilla_alta_id, servicio_novedad.planilla_baja_id, servicio_novedad.fecha_desde, servicio_novedad.fecha_hasta, servicio_novedad.dias, servicio_novedad.obligaciones, CONCAT(novedad_tipo.articulo, \'-\', novedad_tipo.inciso) as articulo, novedad_tipo.descripcion_corta as novedad_tipo, servicio.liquidacion, cargo.carga_horaria, CONCAT(COALESCE(persona.cuil,persona.documento), \'<br>\', persona.apellido, \', \', persona.nombre) as persona, division.division, curso.descripcion as curso, CONCAT(regimen.descripcion, \'<br>\', COALESCE(materia.descripcion, \'\')) as regimen_materia')
				->from('servicio_novedad')
				->join('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id', 'left')
				->join('servicio', 'servicio.id = servicio_novedad.servicio_id', '')
				->join('cargo', 'cargo.id = servicio.cargo_id', '')
				->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
				->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
				->join('persona', 'persona.id = servicio.persona_id', 'left')
				->join('division', 'cargo.division_id = division.id', 'left')
				->join('curso', 'division.curso_id = curso.id', 'left')
				->join('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=2', '')
				->where('planilla_alta_id', $planilla_id)
				->or_where('planilla_baja_id', $planilla_id)
				->get()
				->result();
	}

	public function get_escuela_tem($escuela_id) {
		return $this->db->select('pe.escuela_id')
				->from('tem_proyecto_escuela pe')
				->where('pe.escuela_id', $escuela_id)
				->get()
				->row();
	}

	public function buscar_escuelas($search = '') {
		return $this->db->select("CONCAT(e.numero, CASE WHEN e.anexo=0 THEN ' - ' ELSE CONCAT('/',e.anexo,' - ') END, e.nombre) as escuela")->from('escuela e')->like('e.numero', $search)->or_like('e.nombre', $search)->get()->result();
	}
}
/* End of file Tem_model.php */
/* Location: ./application/modules/tem/models/Tem_model.php */