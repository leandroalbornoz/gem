<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Comedor_presupuesto_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'comedor_presupuesto';
		$this->msg_name = 'Comedor presupuesto';
		$this->id_name = 'id';
		$this->columnas = array('id', 'escuela_id', 'mes', 'monto_presupuestado', 'monto_entregado', 'dias_albergado', 'alumnos_media_racion', 'alumnos_racion_completa');
		$this->fields = array(
			'monto_presupuestado' => array('label' => 'Monto presupuestado', 'type' => 'number', 'step' => '.01'),
			'monto_entregado' => array('label' => 'Monto entregado', 'type' => 'number', 'step' => '.01'),
			'dias_albergado' => array('label' => 'Días albergado', 'type' => 'number'),
			'alumnos_media_racion' => array('label' => 'Alumnos con media racion', 'type' => 'number'),
			'alumnos_racion_completa' => array('label' => 'Alumnos con racion completa', 'type' => 'number')
		);
		$this->requeridos = array('escuela_id');
		//$this->unicos = array();
		$this->default_join = array(
		);
	}

	public function buscar($escuela_id = NULL, $mes = NULL) {
		return $this->db->select('id')
				->from('comedor_presupuesto')
				->where('escuela_id', $escuela_id)
				->where('mes', $mes)
				->get()->row();
	}

	public function get_comedor_divisiones($escuela_id, $mes) {
		$ciclo_lectivo = substr($mes, 0, 4);
		return $this->db->select("d.id, cu.descripcion curso, d.division, t.descripcion turno,cp.id as comedor_presuspuesto_id,cp.mes as mes, COUNT(DISTINCT ad.alumno_id) alumnos, 
COUNT(DISTINCT CASE WHEN ca.comedor_racion_id=1 THEN  ad.alumno_id ELSE NULL END) alumnos_r1, 
COUNT(DISTINCT CASE WHEN ca.comedor_racion_id=2 THEN  ad.alumno_id ELSE NULL END) alumnos_r2,
COUNT(DISTINCT CASE WHEN ca.comedor_racion_id IS NULL THEN  ad.alumno_id ELSE NULL END) alumnos_sr")
				->from('division d')
				->join('curso cu', 'cu.id = d.curso_id')
				->join('turno t', 't.id = d.turno_id', 'left')
				->join('alumno_division ad', 'ad.division_id = d.id', 'left')
				->join('comedor_presupuesto cp', "cp.escuela_id = d.escuela_id and cp.mes = $mes", 'left')
				->join('comedor_alumno ca', 'ad.id = ca.alumno_division_id AND cp.id = ca.comedor_presupuesto_id', 'left')
				->where('d.escuela_id', $escuela_id)
				->where('d.fecha_baja IS NULL')
				->where("(ad.fecha_hasta IS NULL OR DATE_FORMAT(ad.fecha_hasta,'%Y%m') >= $mes)")
				->where("ad.ciclo_lectivo", $ciclo_lectivo)
				->group_by('d.id, cp.mes')
				->order_by('cu.descripcion, d.division')
				->get()->result();
	}
	
	public function monto_raciones ($ames= NULL, $escuela_id = NULL){
		
		$ciclo_lectivo = substr($ames, 0, 4);
		return $this->db->query("SELECT d.escuela_id ,e.numero,e.anexo,cp.periodo,e.nombre,
CONCAT(RIGHT(m.ames,2), '/', LEFT(m.ames,4)) as mes,
COALESCE(ai.alumnos, 0)as  alumnos,
COALESCE(SUM(COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) - COALESCE(ai.dias_falta, 0))/
SUM(COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0)),0) as asistencia_media,
COALESCE(vr.alumnos_r1, 0), COALESCE(vr.alumnos_r2, 0),COALESCE(vr.alumnos_sr, 0)
FROM division d
JOIN calendario ca ON ca.id = d.calendario_id
JOIN calendario_periodo cp ON cp.calendario_id = ca.id
JOIN planilla_asisnov_plazo m ON m.ames BETWEEN DATE_FORMAT(cp.inicio,'%Y%m') AND DATE_FORMAT(cp.fin,'%Y%m')
JOIN comedor_presupuesto cpre on cpre.escuela_id = d.escuela_id and cpre.mes = m.ames
JOIN escuela e ON e.id = d.escuela_id
LEFT JOIN(
		SELECT e.id, e.numero,e.nombre,e.anexo,cp.id as comedor_presuspuesto_id, cp.mes as mes,
		COUNT(DISTINCT CASE WHEN ca.comedor_racion_id=1 THEN  ad.alumno_id ELSE NULL END) alumnos_r1,
		COUNT(DISTINCT CASE WHEN ca.comedor_racion_id=2 THEN  ad.alumno_id ELSE NULL END) alumnos_r2,
		COUNT(DISTINCT CASE WHEN ca.comedor_racion_id IS NULL THEN  ad.alumno_id ELSE NULL END) alumnos_sr
		FROM division d
		JOIN curso cu ON cu.id = d.curso_id
        LEFT JOIN escuela e ON e.id = d.escuela_id
		LEFT JOIN alumno_division ad ON ad.division_id = d.id
		LEFT JOIN comedor_presupuesto cp ON cp.escuela_id = d.escuela_id
		LEFT JOIN comedor_alumno ca ON ad.id = ca.alumno_division_id AND cp.id = ca.comedor_presupuesto_id
		WHERE d.fecha_baja IS NULL
		AND (ad.fecha_hasta IS NULL OR DATE_FORMAT(ad.fecha_hasta,'%Y%m') >= ?)
		AND ad.ciclo_lectivo = ?
        AND cp.mes = ?
		GROUP BY e.id, cp.mes,ad.ciclo_lectivo
) vr ON vr.id = e.id AND vr.mes=m.ames AND vr.comedor_presuspuesto_id = cpre.id
LEFT JOIN (
	SELECT e.id, cp.periodo, m.ames mes, ad.ciclo_lectivo, COUNT(DISTINCT ad.id) alumnos, 
    SUM(CASE WHEN justificada='NC' THEN falta ELSE 0 END) dias_nc, 
	SUM(CASE WHEN justificada='NC' THEN 0 ELSE falta END) dias_falta
	FROM alumno_division ad
	JOIN division d ON ad.division_id = d.id AND ad.ciclo_lectivo = ?
    JOIN escuela e ON e.id = d.escuela_id
	JOIN calendario ca ON ca.id = d.calendario_id
	JOIN calendario_periodo cp ON cp.calendario_id = ca.id
	JOIN planilla_asisnov_plazo m ON m.ames BETWEEN DATE_FORMAT(ad.fecha_desde,'%Y%m') AND COALESCE(DATE_FORMAT(ad.fecha_hasta,'%Y%m'),m.ames)
    AND cp.inicio<=COALESCE(ad.fecha_hasta,cp.inicio) AND cp.fin>=ad.fecha_desde AND m.ames=?
    JOIN comedor_alumno cal ON cal.alumno_division_id = ad.id
    JOIN comedor_presupuesto ON comedor_presupuesto.id = cal.comedor_presupuesto_id
	LEFT JOIN division_inasistencia di ON ad.division_id=di.division_id AND ad.ciclo_lectivo=di.ciclo_lectivo AND di.mes = m.ames 
    AND di.periodo=cp.periodo
	LEFT JOIN division_inasistencia_dia did ON di.id=did.division_inasistencia_id
    LEFT JOIN alumno_inasistencia ai ON did.id=ai.division_inasistencia_dia_id AND cal.alumno_division_id = ai.alumno_division_id
    GROUP BY e.id, m.ames, cp.periodo, ad.ciclo_lectivo
) ai ON ai.id=e.id AND cp.ciclo_lectivo=ai.ciclo_lectivo AND m.ames=ai.mes AND cp.periodo=ai.periodo
LEFT JOIN division_inasistencia di ON d.id=di.division_id AND di.ciclo_lectivo = ? AND di.mes=m.ames AND di.periodo=cp.periodo
WHERE m.ames = ? AND d.escuela_id = ?
group by d.escuela_id, m.ames, cp.periodo;", array($ames, $ciclo_lectivo, $ames, $ciclo_lectivo, $ames, $ciclo_lectivo, $ames,$escuela_id))->result_array();

	}
	
}