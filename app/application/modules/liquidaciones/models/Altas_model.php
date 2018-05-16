<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Altas_model extends CI_Model {

	public function get_by_departamento($mes) {
		$altas = $this->db->query("SELECT sr.planilla_tipo_id, COALESCE(d.id,0) id, COALESCE(d.descripcion, 'N/D') departamento, COUNT(DISTINCT e.id) escuelas_altas, COUNT(DISTINCT sn_alta.id) altas_cargadas, COUNT(DISTINCT CASE WHEN sn_alta.estado='Cargado' THEN NULL ELSE sn_alta.id END) altas_auditadas
FROM servicio_novedad sn_alta
JOIN servicio s ON sn_alta.servicio_id = s.id
JOIN situacion_revista sr ON s.situacion_revista_id = sr.id
JOIN cargo c ON s.cargo_id = c.id
JOIN regimen r ON c.regimen_id = r.id AND r.planilla_modalidad_id = 1
JOIN escuela e ON c.escuela_id = e.id AND e.dependencia_id = 1
LEFT JOIN localidad l ON e.localidad_id = l.id
LEFT JOIN departamento d ON l.departamento_id = d.id
WHERE sn_alta.novedad_tipo_id = 1 AND sn_alta.ames = ? AND sn_alta.planilla_baja_id IS NULL
GROUP BY sr.planilla_tipo_id, d.id
ORDER BY d.descripcion", array($mes))->result();
		$departamentos = array();
		foreach ($altas as $alta) {
			$departamentos[$alta->planilla_tipo_id][] = $alta;
		}
		return $departamentos;
	}

	public function get_altas_escuela($planilla_tipo_id, $mes, $escuela) {
		return $this->db->query("SELECT sn.id, s.persona_id, p.documento, p.cuil, CONCAT(p.apellido, ', ', p.nombre) persona, p.fecha_nacimiento, s.liquidacion, s.observaciones, c.observaciones observaciones_c, sr.descripcion situacion_revista, s.fecha_alta, s.fecha_baja, r.codigo regimen, r.descripcion regimen_desc, r.puntos, c.carga_horaria, LPAD(c.carga_horaria,4,'0') clase, r.regimen_tipo_id, sn.dias, sn.obligaciones, sn.motivo_rechazo, sn.estado, t.descripcion turno, CONCAT(cu.descripcion, ' ', d.division) division, m.descripcion materia, COALESCE(f.descripcion, sf.detalle) funcion_detalle, sf.destino funcion_destino, sf.norma funcion_norma, sf.tarea funcion_tarea, sf.carga_horaria funcion_cargahoraria, sf.fecha_desde as funcion_desde, CONCAT(rn.articulo, '-', rn.inciso) reemplaza_articulo, rn.descripcion_corta reemplaza_articulo_desc, rn.id novedad_tipo_id, rn.novedad novedad_tipo_novedad, rs.liquidacion reemplaza_liquidacion, rp.cuil reemplaza_cuil, CONCAT(rp.apellido, ', ', rp.nombre) reemplaza_persona
FROM servicio_novedad sn
JOIN servicio s ON sn.servicio_id=s.id
LEFT JOIN novedad_tipo rn ON s.articulo_reemplazo_id = rn.id
LEFT JOIN servicio rs ON s.reemplazado_id = rs.id
LEFT JOIN persona rp ON rs.persona_id = rp.id
LEFT JOIN servicio_funcion sf ON s.id = sf.servicio_id AND sf.fecha_hasta IS NULL
LEFT JOIN funcion f ON sf.funcion_id = f.id
JOIN situacion_revista sr ON s.situacion_revista_id = sr.id
JOIN cargo c ON s.cargo_id = c.id
LEFT JOIN turno t ON c.turno_id = t.id
LEFT JOIN division d ON c.division_id = d.id
LEFT JOIN curso cu ON d.curso_id = cu.id
LEFT JOIN espacio_curricular ec ON c.espacio_curricular_id = ec.id
LEFT JOIN materia m ON ec.materia_id = m.id
JOIN persona p ON s.persona_id = p.id
JOIN regimen r ON c.regimen_id = r.id AND r.planilla_modalidad_id = 1
JOIN escuela e ON c.escuela_id = e.id AND e.dependencia_id = 1
WHERE e.id=? AND sr.planilla_tipo_id=? AND sn.ames=? AND sn.novedad_tipo_id = 1 AND sn.planilla_baja_id IS NULL
GROUP BY s.id
ORDER BY p.documento", array($escuela, $planilla_tipo_id, $mes))->result();
	}

	public function get_altas($planilla_tipo_id, $mes) {
		return $this->db->query("SELECT ames, novedad, departamento, numero, anexo, escuela, juri, uo, repa, cuil, nombre, situacion_revista, liquidacion_u_tbcab, condicion, regimen, descripcion, puntos, carga_horaria, fecha_alta, fecha_baja, motivo_baja, dias, obligaciones, estado, motivo_rechazo, usuario, audi_fecha, reemplaza_cuil, reemplaza_nombre, reemplaza_liquidacion_u_tbcab, reemplaza_articulo, articulo
			FROM gem_barrido
			WHERE ames = ?
			AND planilla_tipo_id = ?
			AND novedad = 'A'
			ORDER BY departamento, numero, nombre ASC
		", array($mes, $planilla_tipo_id))->result_array();
	}
}