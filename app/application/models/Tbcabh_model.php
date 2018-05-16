<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tbcabh_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'tbcabh';
		$this->msg_name = 'tb_cab';
		$this->id_name = 'id';
		$this->columnas = array('id', 'servicio_id', 'liquidacion_s', 'uodes', 'REVISTA', 'SINSUELDO', 'liquidacion', 'jurid', 'mes', 'periodo', 'documento', 'persona', 'nombre', 'vigente', 'traza', 'nrocodliq', 'ug', 'juriuo', 'juri', 'uo', 'repa', 'regimen', 'diashorapag', 'diasoblig', 'difdiasobli', 'codrevi', 'fechafin', 'fechaini', 'haberes', 'descuentos', 'patronales', 'neto', 'RegSalDes', 'antigcod', 'antigsubc', 'estadocod', 'celador', 'personac', 'singoce', 'dpto', 'guiescid', 'guiescane', 'escnivel', 'horas', 'docente', 'admin', 'salud', 'sueldoneg');
		$this->fields = array(
			'liquidacion_s' => array('label' => 'Liquidación', 'readonly' => TRUE),
			'REVISTA' => array('label' => 'Sit. Rev.', 'required' => TRUE),
			'ug' => array('label' => 'Juri/UO/Repa', 'readonly' => TRUE),
			'regimen' => array('label' => 'Régimen', 'readonly' => TRUE),
			'diasoblig' => array('label' => 'Clase', 'readonly' => TRUE),
			'fechaini' => array('label' => 'Inicio', 'readonly' => TRUE),
			'fechafin' => array('label' => 'Fin', 'readonly' => TRUE),
		);
		$this->requeridos = array();
		//$this->unicos = array();
	}

	public function get($id, $vigente) {
		return $this->db->select('id, servicio_id, liquidacion_s, uodes, REVISTA, SINSUELDO, liquidacion, jurid, mes, periodo, documento, persona, nombre, vigente, traza, nrocodliq, ug, juriuo, juri, uo, repa, regimen, diashorapag, diasoblig, difdiasobli, codrevi, fechafin, fechaini, haberes, descuentos, patronales, neto, RegSalDes, antigcod, antigsubc, estadocod, celador, personac, singoce, dpto, guiescid, guiescane, escnivel, horas, docente, admin, salud, sueldoneg')->from('tbcabh')->where('id', $id)->where('vigente', $vigente)->get()->row();
	}

	public function get_ultimo_mes() {
		return $this->db->select('max(vigente) as mes')->from('tbcabh')->get()->row()->mes;
	}

	public function get_by_persona($cuil, $vigente = NULL) {
		$query = $this->db->select('t.id, servicio_id, liquidacion_s, uodes, REVISTA, SINSUELDO, liquidacion, jurid, mes, periodo, documento, persona, nombre, vigente, traza, nrocodliq, ug, juriuo, juri, uo, repa, regimen, diashorapag, diasoblig, difdiasobli, codrevi, fechafin, fechaini, haberes, descuentos, patronales, neto, RegSalDes, antigcod, antigsubc, estadocod, celador, personac, singoce, dpto, guiescid, guiescane, escnivel, horas, docente, admin, salud, sueldoneg, r.descripcion as reparticion')
			->from('tbcabh t')
			->join('jurisdiccion j', 't.juri=j.codigo')
			->join('reparticion r', 'j.id=r.jurisdiccion_id AND t.repa=r.codigo')
			->where('persona', substr(str_replace('-', '', $cuil), 0, 10))
			->order_by('vigente desc, liquidacion_s');
		if (!empty($vigente)) {
			$query->where('vigente', $vigente);
		}
		return $query->get()->result();
	}

	public function get_by_servicio($servicio_id) {
		return $this->db->select('id, servicio_id, liquidacion_s, uodes, REVISTA, SINSUELDO, liquidacion, jurid, mes, periodo, documento, persona, nombre, vigente, traza, nrocodliq, ug, juriuo, juri, uo, repa, regimen, diashorapag, diasoblig, difdiasobli, codrevi, fechafin, fechaini, haberes, descuentos, patronales, neto, RegSalDes, antigcod, antigsubc, estadocod, celador, personac, singoce, dpto, guiescid, guiescane, escnivel, horas, docente, admin, salud, sueldoneg')->from('tbcabh')->where('servicio_id', $servicio_id)->order_by('vigente desc')->get()->result();
	}

	public function actualizar_servicio_id($tbcab_id, $vigente, $servicio_id) {
		$this->asignar_servicio($tbcab_id, $vigente, $servicio_id);
		$this->db->query('UPDATE servicio s
JOIN tbcabh t ON t.servicio_id=s.id AND t.id = ? AND t.vigente = ?
JOIN regimen r ON t.regimen=r.codigo
JOIN reparticion repa ON repa.codigo=t.repa AND repa.jurisdiccion_id=t.juri
JOIN situacion_revista sr ON sr.codigo=t.codrevi
SET s.liquidacion=t.liquidacion_s,
s.liquidacion_ames=t.vigente,
s.liquidacion_regimen_id=r.id,
s.liquidacion_carga_horaria=CASE WHEN r.regimen_tipo_id=2 THEN t.diasoblig ELSE 0 END,
s.liquidacion_reparticion_id=repa.id,
s.liquidacion_situacion_revista_id=sr.id
;', array($tbcab_id, $vigente));
		return TRUE;
	}

	public function agregar_servicio($tbcab_id, $vigente, $persona_id, $tipo_destino, $destino, $fecha_alta) {
		switch ($tipo_destino) {
			case 'escuela':
				$this->db->query('INSERT INTO cargo (escuela_id, regimen_id, carga_horaria, condicion_cargo_id) SELECT ?, r.id, CASE WHEN r.regimen_tipo_id=2 THEN t.diasoblig ELSE 0 END, 1 FROM tbcabh t JOIN regimen r ON t.regimen=r.codigo WHERE t.id=? AND t.vigente=?', array($destino, $tbcab_id, $vigente));
				break;
			case 'area':
				$this->db->query('INSERT INTO cargo (area_id, regimen_id, carga_horaria, condicion_cargo_id) SELECT ?, r.id, CASE WHEN r.regimen_tipo_id=2 THEN t.diasoblig ELSE 0 END, 1 FROM tbcabh t JOIN regimen r ON t.regimen=r.codigo WHERE t.id=? AND t.vigente=?', array($destino, $tbcab_id, $vigente));
				break;
		}
		$cargo_id = $this->db->insert_id();
		$this->db->query('INSERT INTO servicio (persona_id, cargo_id, fecha_alta, liquidacion, situacion_revista_id) SELECT ?, ?, ?, t.liquidacion_s, sr.id FROM tbcabh t JOIN situacion_revista sr ON t.codrevi=sr.codigo WHERE t.id=? AND t.vigente=?', array($persona_id, $cargo_id, $fecha_alta, $tbcab_id, $vigente));
		$servicio_id = $this->db->insert_id();
		$this->db->query('INSERT INTO servicio_funcion (servicio_id, fecha_desde) values (?,?)', array($servicio_id, $fecha_alta));
		return $servicio_id;
	}

	public function asignar_servicio($tbcab_id, $vigente, $servicio_id) {
		$this->db->where('id', $tbcab_id);
		$this->db->where('vigente', $vigente);
		$this->db->set('servicio_id', $servicio_id);
		$this->db->update('tbcabh');
		return TRUE;
	}

	public function desasignar_servicio($tbcab_id, $vigente) {
		$this->db->where('id', $tbcab_id);
		$this->db->where('vigente', $vigente);
		$this->db->set('servicio_id', null);
		$this->db->update('tbcabh');
		return TRUE;
	}

	public function tbcabh_no_valido($tbcab_id, $vigente) {
		$this->db->where('id', $tbcab_id);
		$this->db->where('vigente', $vigente);
		$this->db->set('servicio_id', '-1');
		$this->db->update('tbcabh');
		return TRUE;
	}

	public function limpiar_liquidacion_servicio($servicio_id) {
		$this->db->where('servicio_id', $servicio_id);
		$this->db->set('servicio_id', null);
		$this->db->update('tbcabh');
		$this->db->query('UPDATE servicio s
SET s.liquidacion=NULL,
s.liquidacion_ames=NULL,
s.liquidacion_regimen_id=NULL,
s.liquidacion_carga_horaria=NULL,
s.liquidacion_reparticion_id=NULL,
s.liquidacion_situacion_revista_id=NULL
WHERE s.id=?
;', array($servicio_id));
		return TRUE;
	}

	public function unificar_servicio($servicio_anterior_id, $servicio_nuevo_id) {
		$this->db->where('servicio_id', $servicio_anterior_id);
		$this->db->set('servicio_id', $servicio_nuevo_id);
		$this->db->update('tbcabh');
		return TRUE;
	}

	public function get_casos($escuela, $vigente, $planilla_tipo_id) {
		$casos = array();
		if ($escuela->anexo === '0') {
			$casos['liquidacion_sin_servicio'] = $this->db->select('p.id as persona_id, p.cuil, p.fecha_nacimiento, liquidacion_s, uodes, REVISTA, SINSUELDO, liquidacion, jurid, mes, periodo, tbcabh.documento, persona, tbcabh.nombre, vigente, traza, nrocodliq, ug, juriuo, juri, uo, repa, regimen, diashorapag, diasoblig, difdiasobli, codrevi, fechafin, fechaini, RegSalDes, dpto, guiescid')
					->from('tbcabh')
					->join('persona p', 'tbcabh.documento=p.documento and p.documento_tipo_id=1', 'left')
					->where('vigente', $vigente)
					->where('guiescid', $escuela->numero)
					->where($planilla_tipo_id === '1' ? "codrevi <> 'R'" : "codrevi='R'")
					->where('servicio_id IS NULL')->get()->result();
		}
		$casos['servicio_activo_sin_liquidacion'] = $this->db->query("
				SELECT servicio.id as servicio_id, servicio.fecha_alta, servicio.fecha_baja, servicio.motivo_baja, servicio.liquidacion, servicio.observaciones, servicio_funcion.destino as funcion_destino, servicio_funcion.norma as funcion_norma, servicio_funcion.tarea as funcion_tarea, servicio_funcion.carga_horaria as funcion_cargahoraria, servicio_funcion.fecha_desde as funcion_desde, CONCAT(novedad_tipo.articulo, '-', novedad_tipo.inciso) as reemplaza_articulo, novedad_tipo.descripcion_corta as reemplaza_articulo_desc, novedad_tipo.novedad as novedad_tipo_novedad, r.liquidacion as reemplaza_liquidacion, pr.cuil as reemplaza_cuil, CONCAT(pr.apellido, ' ', pr.nombre) as reemplaza, COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion_detalle, persona.cuil, persona.apellido, persona.nombre, persona.fecha_nacimiento, cargo.carga_horaria, celador_concepto.descripcion as celador_concepto, regimen.codigo as regimen_codigo, regimen.descripcion as regimen, regimen.regimen_tipo_id, regimen.puntos, situacion_revista.descripcion as situacion_revista, situacion_revista.planilla_tipo_id, CONCAT(curso.descripcion, ' ', division.division) as division, turno.descripcion as turno, turno2.descripcion as turno2, CONCAT(escuela.numero, ' ', escuela.nombre) as escuela, escuela.unidad_organizativa, materia.descripcion as materia
				FROM servicio
				JOIN persona ON persona.id = servicio.persona_id
				JOIN cargo ON cargo.id = servicio.cargo_id
				JOIN escuela ON escuela.id = cargo.escuela_id
				JOIN regimen ON cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1
				JOIN situacion_revista ON situacion_revista.id = servicio.situacion_revista_id
				LEFT JOIN servicio_funcion ON servicio_funcion.servicio_id = servicio.id AND servicio_funcion.fecha_hasta IS NULL
				LEFT JOIN funcion ON funcion.id = servicio_funcion.funcion_id
				LEFT JOIN novedad_tipo ON novedad_tipo.id = servicio.articulo_reemplazo_id
				LEFT JOIN servicio r ON r.id = servicio.reemplazado_id
				LEFT JOIN persona pr ON pr.id = r.persona_id
				LEFT JOIN celador_concepto ON celador_concepto.id = servicio.celador_concepto_id
				LEFT JOIN division ON cargo.division_id = division.id
				LEFT JOIN turno ON turno.id = cargo.turno_id
				LEFT JOIN turno turno2 ON turno2.id = division.turno_id
				LEFT JOIN curso ON curso.id = division.curso_id
				LEFT JOIN espacio_curricular ON cargo.espacio_curricular_id = espacio_curricular.id
				LEFT JOIN materia ON espacio_curricular.materia_id = materia.id
				LEFT JOIN tbcabh t ON t.servicio_id=servicio.id AND t.vigente=?
				WHERE escuela.id = ?
				AND ? > COALESCE(DATE_FORMAT(servicio.fecha_alta,'%Y%m'),'000000')
				AND servicio.fecha_baja IS NULL
				AND situacion_revista.planilla_tipo_id = ?
				AND t.id IS NULL
				ORDER BY persona.documento, servicio.liquidacion
				", array($vigente, $escuela->id, $vigente, $planilla_tipo_id))->result();
		$casos['servicio_baja_con_liquidacion'] = $this->db->query("
				SELECT servicio.id as servicio_id, servicio.fecha_alta, servicio.fecha_baja, servicio.motivo_baja, servicio.liquidacion, servicio.observaciones, servicio_funcion.destino as funcion_destino, servicio_funcion.norma as funcion_norma, servicio_funcion.tarea as funcion_tarea, servicio_funcion.carga_horaria as funcion_cargahoraria, servicio_funcion.fecha_desde as funcion_desde, CONCAT(novedad_tipo.articulo, '-', novedad_tipo.inciso) as reemplaza_articulo, novedad_tipo.descripcion_corta as reemplaza_articulo_desc, novedad_tipo.novedad as novedad_tipo_novedad, r.liquidacion as reemplaza_liquidacion, pr.cuil as reemplaza_cuil, CONCAT(pr.apellido, ' ', pr.nombre) as reemplaza, COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion_detalle, persona.cuil, persona.apellido, persona.nombre, persona.fecha_nacimiento, cargo.carga_horaria, celador_concepto.descripcion as celador_concepto, regimen.codigo as regimen_codigo, regimen.descripcion as regimen, regimen.regimen_tipo_id, regimen.puntos, situacion_revista.descripcion as situacion_revista, situacion_revista.planilla_tipo_id, CONCAT(curso.descripcion, ' ', division.division) as division, turno.descripcion as turno, turno2.descripcion as turno2, CONCAT(escuela.numero, ' ', escuela.nombre) as escuela, escuela.unidad_organizativa, materia.descripcion as materia, t.diashorapag
				FROM servicio
				JOIN persona ON persona.id = servicio.persona_id
				JOIN cargo ON cargo.id = servicio.cargo_id
				JOIN escuela ON escuela.id = cargo.escuela_id
				JOIN regimen ON cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1
				JOIN situacion_revista ON situacion_revista.id = servicio.situacion_revista_id
				LEFT JOIN servicio_funcion ON servicio_funcion.servicio_id = servicio.id AND servicio_funcion.fecha_hasta IS NULL
				LEFT JOIN funcion ON funcion.id = servicio_funcion.funcion_id
				LEFT JOIN novedad_tipo ON novedad_tipo.id = servicio.articulo_reemplazo_id
				LEFT JOIN servicio r ON r.id = servicio.reemplazado_id
				LEFT JOIN persona pr ON pr.id = r.persona_id
				LEFT JOIN celador_concepto ON celador_concepto.id = servicio.celador_concepto_id
				LEFT JOIN division ON cargo.division_id = division.id
				LEFT JOIN turno ON turno.id = cargo.turno_id
				LEFT JOIN turno turno2 ON turno2.id = division.turno_id
				LEFT JOIN curso ON curso.id = division.curso_id
				LEFT JOIN espacio_curricular ON cargo.espacio_curricular_id = espacio_curricular.id
				LEFT JOIN materia ON espacio_curricular.materia_id = materia.id
				JOIN tbcabh t ON t.servicio_id=servicio.id AND t.vigente=?
				WHERE escuela.id = ?
				AND ? > DATE_FORMAT(DATE_ADD(servicio.fecha_baja, INTERVAL 1 MONTH),'%Y%m')
				AND situacion_revista.planilla_tipo_id = ?
				ORDER BY persona.documento, servicio.liquidacion
				", array($vigente, $escuela->id, $vigente, $planilla_tipo_id))->result();
		return $casos;
	}

	public function get_casos_aud_altas($escuela, $vigente, $planilla_tipo_id) {
		$casos = $this->db->query("
				SELECT servicio.id as servicio_id, servicio.persona_id, servicio.fecha_alta, servicio.fecha_baja, servicio.motivo_baja, servicio.liquidacion, servicio.observaciones, servicio_funcion.destino as funcion_destino, servicio_funcion.norma as funcion_norma, servicio_funcion.tarea as funcion_tarea, servicio_funcion.carga_horaria as funcion_cargahoraria, servicio_funcion.fecha_desde as funcion_desde, CONCAT(novedad_tipo.articulo, '-', novedad_tipo.inciso) as reemplaza_articulo, novedad_tipo.descripcion_corta as reemplaza_articulo_desc, novedad_tipo.novedad as novedad_tipo_novedad, r.liquidacion as reemplaza_liquidacion, pr.cuil as reemplaza_cuil, CONCAT(pr.apellido, ' ', pr.nombre) as reemplaza, COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion_detalle, persona.cuil, CONCAT(persona.apellido, ', ', persona.nombre) as persona, persona.fecha_nacimiento, cargo.carga_horaria, celador_concepto.descripcion as celador_concepto, regimen.codigo as regimen_codigo, regimen.descripcion as regimen, regimen.regimen_tipo_id, regimen.puntos, situacion_revista.descripcion as situacion_revista, situacion_revista.planilla_tipo_id, CONCAT(curso.descripcion, ' ', division.division) as division, turno.descripcion as turno, turno2.descripcion as turno2, CONCAT(escuela.numero, ' ', escuela.nombre) as escuela, escuela.unidad_organizativa, materia.descripcion as materia
				FROM servicio
				JOIN persona ON persona.id = servicio.persona_id
				JOIN cargo ON cargo.id = servicio.cargo_id
				JOIN escuela ON escuela.id = cargo.escuela_id
				JOIN regimen ON cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1
				JOIN situacion_revista ON situacion_revista.id = servicio.situacion_revista_id
				LEFT JOIN servicio_funcion ON servicio_funcion.servicio_id = servicio.id AND servicio_funcion.fecha_hasta IS NULL
				LEFT JOIN funcion ON funcion.id = servicio_funcion.funcion_id
				LEFT JOIN novedad_tipo ON novedad_tipo.id = servicio.articulo_reemplazo_id
				LEFT JOIN servicio r ON r.id = servicio.reemplazado_id
				LEFT JOIN persona pr ON pr.id = r.persona_id
				LEFT JOIN celador_concepto ON celador_concepto.id = servicio.celador_concepto_id
				LEFT JOIN division ON cargo.division_id = division.id
				LEFT JOIN turno ON turno.id = cargo.turno_id
				LEFT JOIN turno turno2 ON turno2.id = division.turno_id
				LEFT JOIN curso ON curso.id = division.curso_id
				LEFT JOIN espacio_curricular ON cargo.espacio_curricular_id = espacio_curricular.id
				LEFT JOIN materia ON espacio_curricular.materia_id = materia.id
				LEFT JOIN tbcabh t ON t.servicio_id=servicio.id AND t.vigente=?
				WHERE escuela.id = ?
				AND ? > COALESCE(DATE_FORMAT(servicio.fecha_alta,'%Y%m'),'000000')
				AND servicio.fecha_baja IS NULL
				AND situacion_revista.planilla_tipo_id = ?
				AND t.id IS NULL
				ORDER BY persona.documento, servicio.liquidacion
				", array($vigente, $escuela->id, $vigente, $planilla_tipo_id))->result();
		return $casos;
	}

	public function es_caso_aud_altas($escuela, $vigente, $servicio) {
		$caso = $this->db->query("
				SELECT servicio.id as servicio_id, servicio.persona_id, servicio.fecha_alta, servicio.fecha_baja, servicio.motivo_baja, servicio.liquidacion, servicio.observaciones, servicio_funcion.destino as funcion_destino, servicio_funcion.norma as funcion_norma, servicio_funcion.tarea as funcion_tarea, servicio_funcion.carga_horaria as funcion_cargahoraria, servicio_funcion.fecha_desde as funcion_desde, CONCAT(novedad_tipo.articulo, '-', novedad_tipo.inciso) as reemplaza_articulo, novedad_tipo.descripcion_corta as reemplaza_articulo_desc, novedad_tipo.novedad as novedad_tipo_novedad, r.liquidacion as reemplaza_liquidacion, pr.cuil as reemplaza_cuil, CONCAT(pr.apellido, ' ', pr.nombre) as reemplaza, COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion_detalle, persona.cuil, CONCAT(persona.apellido, ', ', persona.nombre) as persona, persona.fecha_nacimiento, cargo.carga_horaria, celador_concepto.descripcion as celador_concepto, regimen.codigo as regimen_codigo, regimen.descripcion as regimen, regimen.regimen_tipo_id, regimen.puntos, situacion_revista.descripcion as situacion_revista, situacion_revista.planilla_tipo_id, CONCAT(curso.descripcion, ' ', division.division) as division, turno.descripcion as turno, turno2.descripcion as turno2, CONCAT(escuela.numero, ' ', escuela.nombre) as escuela, escuela.unidad_organizativa, materia.descripcion as materia
				FROM servicio
				JOIN persona ON persona.id = servicio.persona_id
				JOIN cargo ON cargo.id = servicio.cargo_id
				JOIN escuela ON escuela.id = cargo.escuela_id
				JOIN regimen ON cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1
				JOIN situacion_revista ON situacion_revista.id = servicio.situacion_revista_id
				LEFT JOIN servicio_funcion ON servicio_funcion.servicio_id = servicio.id AND servicio_funcion.fecha_hasta IS NULL
				LEFT JOIN funcion ON funcion.id = servicio_funcion.funcion_id
				LEFT JOIN novedad_tipo ON novedad_tipo.id = servicio.articulo_reemplazo_id
				LEFT JOIN servicio r ON r.id = servicio.reemplazado_id
				LEFT JOIN persona pr ON pr.id = r.persona_id
				LEFT JOIN celador_concepto ON celador_concepto.id = servicio.celador_concepto_id
				LEFT JOIN division ON cargo.division_id = division.id
				LEFT JOIN turno ON turno.id = cargo.turno_id
				LEFT JOIN turno turno2 ON turno2.id = division.turno_id
				LEFT JOIN curso ON curso.id = division.curso_id
				LEFT JOIN espacio_curricular ON cargo.espacio_curricular_id = espacio_curricular.id
				LEFT JOIN materia ON espacio_curricular.materia_id = materia.id
				LEFT JOIN tbcabh t ON t.servicio_id=servicio.id AND t.vigente=?
				WHERE escuela.id = ?
				AND ? > COALESCE(DATE_FORMAT(servicio.fecha_alta,'%Y%m'),'000000')
				AND servicio.fecha_baja IS NULL
				AND t.id IS NULL
				AND servicio.id = ?
				ORDER BY persona.documento, servicio.liquidacion
				", array($vigente, $escuela->id, $vigente, $servicio->id))->row();
		return $caso;
	}

	public function get_casos_aud_bajas($escuela, $vigente, $planilla_tipo_id) {
		$casos = $this->db->query("
				SELECT servicio.persona_id, servicio.id as servicio_id, servicio.fecha_alta, servicio.fecha_baja, servicio.motivo_baja, servicio.liquidacion, servicio.observaciones, servicio_funcion.destino as funcion_destino, servicio_funcion.norma as funcion_norma, servicio_funcion.tarea as funcion_tarea, servicio_funcion.carga_horaria as funcion_cargahoraria, servicio_funcion.fecha_desde as funcion_desde, CONCAT(novedad_tipo.articulo, '-', novedad_tipo.inciso) as reemplaza_articulo, novedad_tipo.descripcion_corta as reemplaza_articulo_desc, novedad_tipo.novedad as novedad_tipo_novedad, r.liquidacion as reemplaza_liquidacion, pr.cuil as reemplaza_cuil, CONCAT(pr.apellido, ' ', pr.nombre) as reemplaza, COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion_detalle, persona.cuil, CONCAT(persona.apellido, ', ', persona.nombre) as persona, persona.fecha_nacimiento, cargo.carga_horaria, celador_concepto.descripcion as celador_concepto, regimen.codigo as regimen_codigo, regimen.descripcion as regimen, regimen.regimen_tipo_id, regimen.puntos, situacion_revista.descripcion as situacion_revista, situacion_revista.planilla_tipo_id, CONCAT(curso.descripcion, ' ', division.division) as division, turno.descripcion as turno, turno2.descripcion as turno2, CONCAT(escuela.numero, ' ', escuela.nombre) as escuela, escuela.unidad_organizativa, materia.descripcion as materia, t.diashorapag
				FROM servicio
				JOIN persona ON persona.id = servicio.persona_id
				JOIN cargo ON cargo.id = servicio.cargo_id
				JOIN escuela ON escuela.id = cargo.escuela_id
				JOIN regimen ON cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1
				JOIN situacion_revista ON situacion_revista.id = servicio.situacion_revista_id
				LEFT JOIN servicio_funcion ON servicio_funcion.servicio_id = servicio.id AND servicio_funcion.fecha_hasta IS NULL
				LEFT JOIN funcion ON funcion.id = servicio_funcion.funcion_id
				LEFT JOIN novedad_tipo ON novedad_tipo.id = servicio.articulo_reemplazo_id
				LEFT JOIN servicio r ON r.id = servicio.reemplazado_id
				LEFT JOIN persona pr ON pr.id = r.persona_id
				LEFT JOIN celador_concepto ON celador_concepto.id = servicio.celador_concepto_id
				LEFT JOIN division ON cargo.division_id = division.id
				LEFT JOIN turno ON turno.id = cargo.turno_id
				LEFT JOIN turno turno2 ON turno2.id = division.turno_id
				LEFT JOIN curso ON curso.id = division.curso_id
				LEFT JOIN espacio_curricular ON cargo.espacio_curricular_id = espacio_curricular.id
				LEFT JOIN materia ON espacio_curricular.materia_id = materia.id
				JOIN tbcabh t ON t.servicio_id=servicio.id AND t.vigente=?
				WHERE escuela.id = ?
				AND ? > DATE_FORMAT(DATE_ADD(servicio.fecha_baja, INTERVAL 1 MONTH),'%Y%m')
				AND situacion_revista.planilla_tipo_id = ?
				ORDER BY persona.documento, servicio.liquidacion
				", array($vigente, $escuela->id, $vigente, $planilla_tipo_id))->result();
		return $casos;
	}

	public function es_caso_aud_bajas($escuela, $vigente, $servicio) {
		$caso = $this->db->query("
				SELECT servicio.id as servicio_id, servicio.fecha_alta, servicio.fecha_baja, servicio.motivo_baja, servicio.liquidacion, servicio.observaciones, servicio_funcion.destino as funcion_destino, servicio_funcion.norma as funcion_norma, servicio_funcion.tarea as funcion_tarea, servicio_funcion.carga_horaria as funcion_cargahoraria, servicio_funcion.fecha_desde as funcion_desde, CONCAT(novedad_tipo.articulo, '-', novedad_tipo.inciso) as reemplaza_articulo, novedad_tipo.descripcion_corta as reemplaza_articulo_desc, novedad_tipo.novedad as novedad_tipo_novedad, r.liquidacion as reemplaza_liquidacion, pr.cuil as reemplaza_cuil, CONCAT(pr.apellido, ' ', pr.nombre) as reemplaza, COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion_detalle, persona.cuil, persona.apellido, persona.nombre, persona.fecha_nacimiento, cargo.carga_horaria, celador_concepto.descripcion as celador_concepto, regimen.codigo as regimen_codigo, regimen.descripcion as regimen, regimen.regimen_tipo_id, regimen.puntos, situacion_revista.descripcion as situacion_revista, situacion_revista.planilla_tipo_id, CONCAT(curso.descripcion, ' ', division.division) as division, turno.descripcion as turno, turno2.descripcion as turno2, CONCAT(escuela.numero, ' ', escuela.nombre) as escuela, escuela.unidad_organizativa, materia.descripcion as materia, t.diashorapag
				FROM servicio
				JOIN persona ON persona.id = servicio.persona_id
				JOIN cargo ON cargo.id = servicio.cargo_id
				JOIN escuela ON escuela.id = cargo.escuela_id
				JOIN regimen ON cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1
				JOIN situacion_revista ON situacion_revista.id = servicio.situacion_revista_id
				LEFT JOIN servicio_funcion ON servicio_funcion.servicio_id = servicio.id AND servicio_funcion.fecha_hasta IS NULL
				LEFT JOIN funcion ON funcion.id = servicio_funcion.funcion_id
				LEFT JOIN novedad_tipo ON novedad_tipo.id = servicio.articulo_reemplazo_id
				LEFT JOIN servicio r ON r.id = servicio.reemplazado_id
				LEFT JOIN persona pr ON pr.id = r.persona_id
				LEFT JOIN celador_concepto ON celador_concepto.id = servicio.celador_concepto_id
				LEFT JOIN division ON cargo.division_id = division.id
				LEFT JOIN turno ON turno.id = cargo.turno_id
				LEFT JOIN turno turno2 ON turno2.id = division.turno_id
				LEFT JOIN curso ON curso.id = division.curso_id
				LEFT JOIN espacio_curricular ON cargo.espacio_curricular_id = espacio_curricular.id
				LEFT JOIN materia ON espacio_curricular.materia_id = materia.id
				JOIN tbcabh t ON t.servicio_id=servicio.id AND t.vigente=?
				WHERE escuela.id = ?
				AND ? > DATE_FORMAT(DATE_ADD(servicio.fecha_baja, INTERVAL 1 MONTH),'%Y%m')
				AND servicio.id = ?
				ORDER BY persona.documento, servicio.liquidacion
				", array($vigente, $escuela->id, $vigente, $servicio->id))->row();
		return $caso;
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		return FALSE;
	}
}
/* End of file Tbcabh_model.php */
/* Location: ./application/models/Tbcabh_model.php */