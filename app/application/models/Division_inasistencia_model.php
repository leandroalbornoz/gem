<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Division_inasistencia_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'division_inasistencia';
		$this->msg_name = 'Inasistencia Mensual de División';
		$this->id_name = 'id';
		$this->columnas = array('id', 'division_id', 'ciclo_lectivo', 'periodo', 'mes', 'resumen_mensual', 'dias', 'fecha_cierre', 'fecha_notificacion');
		$this->fields = array(
			'division' => array('label' => 'División', 'readonly' => TRUE),
			'ciclo_lectivo' => array('label' => 'Ciclo Lectivo', 'readonly' => TRUE),
			'periodo' => array('label' => 'Periodo', 'readonly' => TRUE),
			'mes' => array('label' => 'Mes', 'readonly' => TRUE),
			'dias' => array('label' => 'Días de Cursado', 'type' => 'integer', 'maxlength' => '2'),
			'resumen_mensual' => array('label' => 'Tipo de Carga', 'input_type' => 'combo', 'array' => array('Si' => 'Resumen Mensual', 'No' => 'Detallado por Día'))
		);
		$this->requeridos = array('division_id', 'ciclo_lectivo', 'mes');
		//$this->unicos = array();
		$this->default_join = array(
			array('division', 'division.id = division_inasistencia.division_id', 'left', array('division.division as division', 'division.escuela_id')),
			array('curso', 'curso.id = division.curso_id', 'left', array('curso.descripcion as curso')),
			array('calendario', 'calendario.id = division.calendario_id', 'left', array('calendario.nombre_periodo')),
			array('calendario_periodo', 'calendario_periodo.calendario_id = calendario.id AND calendario_periodo.ciclo_lectivo =  division_inasistencia.ciclo_lectivo AND calendario_periodo.periodo = division_inasistencia.periodo ', 'left', array('calendario_periodo.inicio as fecha_inicio', 'calendario_periodo.fin as fecha_fin')),
		);
	}

	public function get_registro($division_id, $ciclo_lectivo, $periodo, $mes) {
		return $this->db->select('id')
				->from('division_inasistencia')
				->where('division_id', $division_id)
				->where('ciclo_lectivo', $ciclo_lectivo)
				->where('periodo', $periodo)
				->where('mes', $mes)
				->get()->row();
	}

	public function get_registros($division_id, $ciclo_lectivo) {
		$registros_bd = $this->db->select('id, periodo, mes, resumen_mensual, fecha_cierre, dias')
				->from('division_inasistencia')
				->where('division_id', $division_id)
				->where('ciclo_lectivo', $ciclo_lectivo)
				->get()->result();
		$registros = array();
		if (!empty($registros_bd)) {
			foreach ($registros_bd as $registro_bd) {
				$registros[$registro_bd->periodo][$registro_bd->mes] = $registro_bd;
			}
		}
		return $registros;
	}

	public function get_estadisticas_inasistencias($division_id, $ciclo_lectivo) {
		return $this->db->select('di.periodo, di.mes, di.dias, di.fecha_cierre, COALESCE(ai.alumnos,0) alumnos, COALESCE(ai.dias_nc,0) dias_nc, COALESCE(ai.dias_falta, 0) dias_falta,
	COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) asistencia_ideal,
    COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) - COALESCE(ai.dias_falta, 0) asistencia_real,
    (COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) - COALESCE(ai.dias_falta, 0))/(COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0))*100 asistencia_media')
				->from('division_inasistencia di')
				->join("(SELECT di.id, COUNT(DISTINCT ad.id) alumnos, SUM(CASE WHEN justificada='NC' THEN falta ELSE 0 END) dias_nc, SUM(CASE WHEN justificada='NC' THEN 0 ELSE falta END) dias_falta
	FROM alumno_division ad
	JOIN division_inasistencia di ON ad.division_id=di.division_id AND ad.ciclo_lectivo=di.ciclo_lectivo AND di.mes BETWEEN DATE_FORMAT(ad.fecha_desde, '%Y%m') AND COALESCE(DATE_FORMAT(ad.fecha_hasta,'%Y%m'),di.mes)
		LEFT JOIN division_inasistencia_dia did ON di.id=did.division_inasistencia_id
    LEFT JOIN alumno_inasistencia ai ON did.id=ai.division_inasistencia_dia_id AND ad.id=ai.alumno_division_id
    WHERE di.division_id=$division_id AND di.ciclo_lectivo=$ciclo_lectivo
    GROUP BY di.id) ai", 'di.id = ai.id')
				->where('di.fecha_cierre IS NOT NULL')
				->where('di.division_id', $division_id)
				->where('di.ciclo_lectivo', $ciclo_lectivo)
				->get()->result();
	}

	public function get_divisiones_inasistencias($escuela_id, $ciclo_lectivo) {
		return $this->db->select('d.id, ca.id as calendario_id, ca.descripcion as calendario, cp.inicio, cp.fin, ca.nombre_periodo, c.descripcion as curso, d.division, cp.periodo, m.ames mes, di.id as division_inasistencia_id, di.dias, di.fecha_cierre, di.fecha_notificacion, COALESCE(ai.dia_abierto, 0) dia_abierto, COALESCE(ai.alumnos,0) alumnos, COALESCE(ai.dias_nc,0) dias_nc, COALESCE(ai.dias_falta, 0) dias_falta,
	COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) asistencia_ideal,
    COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) - COALESCE(ai.dias_falta, 0) asistencia_real,
    (COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) - COALESCE(ai.dias_falta, 0))/(COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0))*100 asistencia_media')
				->from('division d')
				->join('calendario ca', 'ca.id = d.calendario_id')
				->join('calendario_periodo cp', 'cp.calendario_id = ca.id')
				->join('planilla_asisnov_plazo m', "m.ames BETWEEN DATE_FORMAT(cp.inicio,'%Y%m') AND DATE_FORMAT(cp.fin,'%Y%m') AND LEFT(ames,4)='$ciclo_lectivo'")
				->join('curso c', 'c.id = d.curso_id')
				->join("(SELECT di.id, cp.periodo, m.ames mes, d.id division_id, ad.ciclo_lectivo, COUNT(DISTINCT ad.id) alumnos, COUNT(DISTINCT did.id) dia_abierto, SUM(CASE WHEN justificada='NC' THEN falta ELSE 0 END) dias_nc, SUM(CASE WHEN justificada='NC' THEN 0 ELSE falta END) dias_falta
	FROM alumno_division ad
	JOIN division d ON ad.division_id = d.id AND d.escuela_id = $escuela_id AND ad.ciclo_lectivo = $ciclo_lectivo
	JOIN calendario ca ON ca.id = d.calendario_id
	JOIN calendario_periodo cp ON cp.calendario_id = ca.id
	JOIN planilla_asisnov_plazo m ON m.ames BETWEEN DATE_FORMAT(ad.fecha_desde,'%Y%m') AND COALESCE(DATE_FORMAT(ad.fecha_hasta,'%Y%m'),m.ames) AND cp.inicio<=COALESCE(ad.fecha_hasta,cp.inicio) AND cp.fin>=ad.fecha_desde
	LEFT JOIN division_inasistencia di ON ad.division_id=di.division_id AND ad.ciclo_lectivo=di.ciclo_lectivo AND di.mes = m.ames AND di.periodo=cp.periodo
	LEFT JOIN division_inasistencia_dia did ON di.id=did.division_inasistencia_id
    LEFT JOIN alumno_inasistencia ai ON did.id=ai.division_inasistencia_dia_id AND ad.id=ai.alumno_division_id
    GROUP BY di.id, d.id, m.ames, cp.periodo, ad.ciclo_lectivo) ai", 'ai.division_id=d.id AND cp.ciclo_lectivo=ai.ciclo_lectivo AND m.ames=ai.mes AND cp.periodo=ai.periodo', 'left')
				->join('division_inasistencia di', "d.id=di.division_id AND di.ciclo_lectivo = $ciclo_lectivo AND di.mes=m.ames AND di.periodo=cp.periodo", 'left')
				->where('d.escuela_id', $escuela_id)
				->where('d.fecha_baja IS NULL')
				->order_by('ca.nombre_periodo, cp.periodo, m.ames, c.descripcion, d.division')
				->get()->result();
	}

	public function get_divisiones_inasistencias_mensual($escuela_id, $ciclo_lectivo, $mes) {
		return $this->db->select('d.id, di.id,ca.id as calendario_id, ca.descripcion as calendario, cp.inicio, cp.fin, ca.nombre_periodo, c.descripcion as curso, d.division, cp.periodo, m.ames as mes, t.descripcion as turno, di.id as division_inasistencia_id, di.dias, di.fecha_cierre, di.fecha_notificacion, COALESCE(ai.dia_abierto, 0) dia_abierto, 
			SUM(ai.alumnos) alumnos, 
			SUM(CASE WHEN sexo_id=1 THEN ai.alumnos ELSE 0 END) alumnos_hombres,
			SUM(CASE WHEN sexo_id=2 THEN ai.alumnos ELSE 0 END) alumnos_mujeres,
			SUM(CASE WHEN sexo_id IS NULL THEN ai.alumnos ELSE 0 END) alumnos_sin_sexo,
			SUM(CASE WHEN sexo_id=1 THEN ai.alumnos_primer_dia ELSE 0 END) alumnos_hombres_primer_dia,
			SUM(CASE WHEN sexo_id=2 THEN ai.alumnos_primer_dia ELSE 0 END) alumnos_mujeres_primer_dia,
			SUM(CASE WHEN sexo_id IS NULL THEN ai.alumnos_primer_dia ELSE 0 END) alumnos_sin_sexo_primer_dia,
			SUM(CASE WHEN sexo_id=1 THEN ai.alumnos_entrados ELSE 0 END) alumnos_entrados_hombres,
			SUM(CASE WHEN sexo_id=2 THEN ai.alumnos_entrados ELSE 0 END) alumnos_entrados_mujeres,
			SUM(CASE WHEN sexo_id IS NULL THEN ai.alumnos_entrados ELSE 0 END) alumnos_entrados_sin_sexo,
			SUM(CASE WHEN sexo_id=1 THEN ai.alumnos_salidos ELSE 0 END) alumnos_salidos_hombres,
			SUM(CASE WHEN sexo_id=2 THEN ai.alumnos_salidos ELSE 0 END) alumnos_salidos_mujeres,
			SUM(CASE WHEN sexo_id IS NULL THEN ai.alumnos_salidos ELSE 0 END) alumnos_salidos_sin_sexo,
			SUM(CASE WHEN sexo_id=1 THEN ai.dias_nc ELSE 0 END) alumnos_nc_hombres,
			SUM(CASE WHEN sexo_id=2 THEN ai.dias_nc ELSE 0 END) alumnos_nc_mujeres,
			COALESCE(ai.dias_nc, 0) dias_nc, 
			COALESCE(ai.dias_falta, 0) dias_falta, 
			SUM(CASE WHEN sexo_id=1 THEN COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) ELSE 0 END) asistencia_ideal_hombres,
			SUM(CASE WHEN sexo_id=2 THEN COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) ELSE 0 END) asistencia_ideal_mujeres,  
			SUM(CASE WHEN sexo_id IS NULL THEN COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) ELSE 0 END) asistencia_ideal_sin_sexo,
			SUM(CASE WHEN sexo_id=1 THEN COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) - COALESCE(ai.dias_falta, 0) ELSE 0 END) asistencia_real_hombres,
			SUM(CASE WHEN sexo_id=2 THEN COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) - COALESCE(ai.dias_falta, 0) ELSE 0 END) asistencia_real_mujeres,  
			SUM(CASE WHEN sexo_id IS NULL THEN COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) - COALESCE(ai.dias_falta, 0) ELSE 0 END) asistencia_real_sin_sexo,
			SUM(CASE WHEN sexo_id=1 THEN (COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) - COALESCE(ai.dias_falta, 0))/(COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0))*100 ELSE 0 END) asistencia_media_hombres,
			SUM(CASE WHEN sexo_id=2 THEN (COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) - COALESCE(ai.dias_falta, 0))/(COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0))*100 ELSE 0 END) asistencia_media_mujeres,
			SUM(CASE WHEN sexo_id IS NULL THEN (COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) - COALESCE(ai.dias_falta, 0))/(COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0))*100 ELSE 0 END) asistencia_media_sin_sexo
')
				->from('division d')
				->join('turno t', 't.id = d.turno_id')
				->join('calendario ca', 'ca.id = d.calendario_id')
				->join('calendario_periodo cp', 'cp.calendario_id = ca.id')
				->join('planilla_asisnov_plazo m', "m.ames BETWEEN DATE_FORMAT(cp.inicio,'%Y%m') AND DATE_FORMAT(cp.fin,'%Y%m')")
				->join('curso c', 'c.id = d.curso_id')
				->join("(SELECT p.sexo_id, di.id, cp.periodo, m.ames mes, d.id division_id, ad.ciclo_lectivo,
					COUNT(DISTINCT ad.id) alumnos,
					COUNT(DISTINCT CASE WHEN GREATEST(cp.inicio, STR_TO_DATE(CONCAT(m.ames, '01'), '%Y%m%d')) >= ad.fecha_desde THEN ad.id ELSE NULL END) alumnos_primer_dia,
					COUNT(DISTINCT CASE WHEN GREATEST(cp.inicio, STR_TO_DATE(CONCAT(m.ames, '01'), '%Y%m%d')) < ad.fecha_desde AND DATE_FORMAT(ad.fecha_desde, '%Y%m')=m.ames THEN  ad.id ELSE NULL END) alumnos_entrados,
					COUNT(DISTINCT CASE WHEN GREATEST(cp.fin, STR_TO_DATE(CONCAT(m.ames, '01'), '%Y%m%d')) >= ad.fecha_hasta AND DATE_FORMAT(ad.fecha_hasta, '%Y%m')=m.ames THEN ad.id ELSE NULL END) alumnos_salidos,
					COUNT(DISTINCT did.id) dia_abierto, 
					SUM(CASE WHEN justificada='NC' THEN falta ELSE 0 END) dias_nc, 
					SUM(CASE WHEN justificada='NC' THEN 0 ELSE falta END) dias_falta
					FROM alumno_division ad
					JOIN alumno al on al.id = ad.alumno_id
					JOIN persona p on p.id = al.persona_id
					LEFT JOIN sexo s on s.id = p.sexo_id
					JOIN division d ON ad.division_id = d.id AND d.escuela_id = $escuela_id AND ad.ciclo_lectivo = $ciclo_lectivo
					JOIN calendario ca ON ca.id = d.calendario_id
					JOIN calendario_periodo cp ON cp.calendario_id = ca.id
					JOIN planilla_asisnov_plazo m ON m.ames BETWEEN DATE_FORMAT(ad.fecha_desde,'%Y%m') AND COALESCE(DATE_FORMAT(ad.fecha_hasta,'%Y%m'),m.ames) AND cp.inicio<=COALESCE(ad.fecha_hasta,cp.inicio) AND cp.fin>=ad.fecha_desde
					LEFT JOIN division_inasistencia di ON ad.division_id=di.division_id AND ad.ciclo_lectivo=di.ciclo_lectivo AND di.mes = m.ames AND di.periodo=cp.periodo
					LEFT JOIN division_inasistencia_dia did ON di.id=did.division_inasistencia_id
					LEFT JOIN alumno_inasistencia ai ON did.id=ai.division_inasistencia_dia_id AND ad.id=ai.alumno_division_id
					GROUP BY p.sexo_id, di.id, d.id, m.ames, cp.periodo, ad.ciclo_lectivo) as ai", 'ai.division_id=d.id AND cp.ciclo_lectivo=ai.ciclo_lectivo AND m.ames=ai.mes AND cp.periodo=ai.periodo', 'left')
				->join('division_inasistencia di', "d.id=di.division_id AND di.ciclo_lectivo = $ciclo_lectivo AND di.mes=m.ames AND di.periodo=cp.periodo", 'left')
				->where('d.escuela_id', $escuela_id)
				->where('m.ames', $ciclo_lectivo . $mes)
				->where('di.id is not null')
				->group_by('d.id, cp.periodo')
				->order_by('ca.nombre_periodo, cp.periodo, m.ames, c.descripcion, d.division')
				->get()->result();
	}

	public function get_divisiones_escuela($escuela_id, $ciclo_lectivo) {
		return $this->db->select('d.id, ca.nombre_periodo, CONCAT(COALESCE(c.descripcion, \'\'), \' \', COALESCE(d.division, \'\')) as division, c.id as calendario_id, count(ad.id) as cant_alumnos')
				->from('division d')
				->join('calendario ca', 'ca.id = d.calendario_id')
				->join('curso c', 'c.id = d.curso_id')
				->join('alumno_division ad', "d.id = ad.division_id AND ad.ciclo_lectivo = $ciclo_lectivo")
				->where('d.escuela_id', $escuela_id)
				->where('d.fecha_baja IS NULL')
				->group_by('d.id')
				->order_by('ca.nombre_periodo, c.descripcion, d.division')
				->get()->result();
	}
	
	public function verificar_usuario_rol_asistencia($usuario_id, $division_id) {
		return $this->db->select('usuario.id, usuario.usuario, persona.cuil as cuil, CONCAT(persona.apellido, \', \', persona.nombre) as persona, rol.nombre as rol, entidad.nombre as entidad, (CASE usuario.active WHEN 1 THEN "Activo" ELSE "No Activo" END) as active, (CASE WHEN rol_asistencia.entidad_id IS NOT NULL THEN "Si" ELSE "No" END) as rol_asignado,rol_asistencia.entidad_id as division_asignada_id')
				->from('usuario')
				->join('usuario_persona', 'usuario_persona.usuario_id=usuario.id')
				->join('usuario_rol', 'usuario_rol.usuario_id=usuario.id', 'left')
				->join('usuario_rol rol_asistencia', 'rol_asistencia.usuario_id=usuario.id AND rol_asistencia.rol_id=26', 'left')
				->join('rol', 'usuario_rol.rol_id=rol.id', 'left')
				->join('entidad_tipo', 'rol.entidad_tipo_id=entidad_tipo.id', 'left')
				->join('entidad', 'entidad.tabla=entidad_tipo.tabla and entidad.id=usuario_rol.entidad_id', 'left')
				->join('persona', "persona.cuil = usuario_persona.cuil", 'left')
				->where('usuario.active', 1)
				->where('rol_asistencia.entidad_id', $division_id)
				->where('usuario.id', $usuario_id)
				->group_by('usuario.id')
				->get()->result();
	}

	public function grafica($periodos, $division_id, $ciclo_lectivo) {
		$result_1 = $this->db->query("SELECT di.periodo, di.mes, di.dias, di.fecha_cierre, COALESCE(ai.alumnos,0) alumnos, COALESCE(ai.dias_nc,0) dias_nc, COALESCE(ai.dias_falta, 0) dias_falta,
COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) asistencia_ideal,
   COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) - COALESCE(ai.dias_falta, 0) asistencia_real,
   (COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) - COALESCE(ai.dias_falta, 0))/(COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0)) asistencia_media
FROM division_inasistencia di
LEFT JOIN (
SELECT di.id, COUNT(DISTINCT ad.id) alumnos, SUM(CASE WHEN justificada='NC' THEN falta ELSE 0 END) dias_nc, SUM(CASE WHEN justificada='NC' THEN 0 ELSE falta END) dias_falta
FROM alumno_division ad
JOIN division_inasistencia di ON ad.division_id=di.division_id AND ad.ciclo_lectivo=di.ciclo_lectivo AND di.mes BETWEEN DATE_FORMAT(ad.fecha_desde, '%Y%m') AND COALESCE(DATE_FORMAT(ad.fecha_hasta,'%Y%m'),di.mes)
   LEFT JOIN division_inasistencia_dia did ON di.id=did.division_inasistencia_id
   LEFT JOIN alumno_inasistencia ai ON did.id=ai.division_inasistencia_dia_id AND ad.id=ai.alumno_division_id
   WHERE di.division_id=$division_id AND di.ciclo_lectivo=$ciclo_lectivo
   GROUP BY di.id
   ) ai ON di.id = ai.id
WHERE di.division_id=$division_id and di.ciclo_lectivo=$ciclo_lectivo and fecha_cierre is not null
GROUP BY di.periodo, di.periodo, di.mes, di.dias, di.fecha_cierre;")->result();
		$chart_values = array('data1');
		$labels = array();
		foreach ($result_1 as $row) {
			$valores[$row->periodo][substr($row->mes, -2)] = $row;
		}

		foreach ($periodos as $periodo) {
			$fecha_ini = new DateTime(substr($periodo->inicio, 0, 8) . '01');
			$fecha_fin = new DateTime($periodo->fin);
			$dia = DateInterval::createFromDateString('1 month');
			$fechas = new DatePeriod($fecha_ini, $dia, $fecha_fin);
			foreach ($fechas as $fecha) {
				$mes = $fecha->format('m');
				if (isset($valores[$periodo->periodo][$mes])) {
					$row->x = "{$periodo->periodo}°	" . substr($this->nombres_meses[$mes], 0, 3);
					$chart_values[] = $valores[$periodo->periodo][$mes]->asistencia_media;
					$labels[] = "{$periodo->periodo}° " . substr($this->nombres_meses[$mes], 0, 3);
				} else {
					if (empty($row)) {
						$row = new StdClass;
					}
					$row->x = "{$periodo->periodo}° " . substr($this->nombres_meses[$mes], 0, 3);
					$chart_values[] = 0;
					$labels[] = "{$periodo->periodo}° " . substr($this->nombres_meses[$mes], 0, 3);
				}
			}
		}
		return array(json_encode($chart_values), json_encode($labels));
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('division_inasistencia_id', $delete_id)->count_all_results('division_inasistencia_dia') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no tenga cargas asociadas.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Division_inasistencia_model.php */
/* Location: ./application/models/Division_inasistencia_model.php */
