<?php

class Declaracion_model extends MY_Model {

	function get_persona($cuil) {
		return $this->db->select("p.id, p.cuil, CONCAT(p.apellido, ', ', p.nombre) AS persona, COALESCE(DATE_FORMAT(p.fecha_nacimiento, '%d/%m/%Y'), '') AS fecha_nacimiento, CONCAT(COALESCE(CONCAT(p.calle, ' '), ''), COALESCE(CONCAT(p.calle_numero, ' '), ''), COALESCE(CONCAT('Dpto:' ,p.departamento, ' '), ''), COALESCE(CONCAT('P:', p.piso, ' ') , ''), COALESCE(CONCAT('B°:', p.barrio, ' '), ''), COALESCE(CONCAT('M:', p.manzana, ' '), ''), COALESCE(CONCAT('C:', p.casa, ' '), '')) AS domicilio, l.descripcion AS localidad, d.descripcion AS departamento,  CONCAT_WS('/', p.telefono_fijo, p.telefono_movil) AS telefono, p.email")
				->from('persona p')
				->join('localidad l', 'l.id = p.localidad_id', 'left')
				->join('departamento d', 'd.id = l.departamento_id', 'left')
				->where('p.cuil', $cuil)
				->get()->row();
	}

	function get_servicios($persona_cuil) {
		$s_gem = $this->db->query("SELECT s.id,
COALESCE(CONCAT(CASE WHEN e.anexo=0 THEN e.numero ELSE CONCAT(e.numero, '/', e.anexo) END, ' - ', e.nombre), CONCAT(a.codigo, ' ', a.descripcion)) institucion,
r.codigo regimen_codigo, CONCAT_WS('<br>', r.descripcion, CONCAT_WS(' - ', CONCAT(cu.descripcion, ' ', d.division), m.descripcion)) regimen, r.puntos regimen_puntos, c.carga_horaria,
COALESCE(f.descripcion, sf.detalle) funcion, sf.fecha_desde funcion_desde, sr.descripcion revista, cc.descripcion condicion,
CONCAT(nt.articulo, '-', nt.inciso) novedad, CASE WHEN t.id IS NULL THEN '' WHEN t.SINSUELDO=0 THEN 'Si' ELSE 'No' END haberes,
GROUP_CONCAT(DISTINCT CASE WHEN h1.dia_id=1 THEN CONCAT(LEFT(h1.hora_desde, 5), '-', LEFT(h1.hora_hasta, 5)) ELSE NULL END ORDER BY h1.hora_desde SEPARATOR '<br>') h1_1,
GROUP_CONCAT(DISTINCT CASE WHEN h1.dia_id=2 THEN CONCAT(LEFT(h1.hora_desde, 5), '-', LEFT(h1.hora_hasta, 5)) ELSE NULL END ORDER BY h1.hora_desde SEPARATOR '<br>') h1_2,
GROUP_CONCAT(DISTINCT CASE WHEN h1.dia_id=3 THEN CONCAT(LEFT(h1.hora_desde, 5), '-', LEFT(h1.hora_hasta, 5)) ELSE NULL END ORDER BY h1.hora_desde SEPARATOR '<br>') h1_3,
GROUP_CONCAT(DISTINCT CASE WHEN h1.dia_id=4 THEN CONCAT(LEFT(h1.hora_desde, 5), '-', LEFT(h1.hora_hasta, 5)) ELSE NULL END ORDER BY h1.hora_desde SEPARATOR '<br>') h1_4,
GROUP_CONCAT(DISTINCT CASE WHEN h1.dia_id=5 THEN CONCAT(LEFT(h1.hora_desde, 5), '-', LEFT(h1.hora_hasta, 5)) ELSE NULL END ORDER BY h1.hora_desde SEPARATOR '<br>') h1_5,
GROUP_CONCAT(DISTINCT CASE WHEN h1.dia_id=6 THEN CONCAT(LEFT(h1.hora_desde, 5), '-', LEFT(h1.hora_hasta, 5)) ELSE NULL END ORDER BY h1.hora_desde SEPARATOR '<br>') h1_6,
GROUP_CONCAT(DISTINCT CASE WHEN h1.dia_id=7 THEN CONCAT(LEFT(h1.hora_desde, 5), '-', LEFT(h1.hora_hasta, 5)) ELSE NULL END ORDER BY h1.hora_desde SEPARATOR '<br>') h1_7,
GROUP_CONCAT(DISTINCT CASE WHEN h2.dia_id=1 THEN CONCAT(LEFT(h2.hora_desde, 5), '-', LEFT(h2.hora_hasta, 5)) ELSE NULL END ORDER BY h2.hora_desde SEPARATOR '<br>') h2_1,
GROUP_CONCAT(DISTINCT CASE WHEN h2.dia_id=2 THEN CONCAT(LEFT(h2.hora_desde, 5), '-', LEFT(h2.hora_hasta, 5)) ELSE NULL END ORDER BY h2.hora_desde SEPARATOR '<br>') h2_2,
GROUP_CONCAT(DISTINCT CASE WHEN h2.dia_id=3 THEN CONCAT(LEFT(h2.hora_desde, 5), '-', LEFT(h2.hora_hasta, 5)) ELSE NULL END ORDER BY h2.hora_desde SEPARATOR '<br>') h2_3,
GROUP_CONCAT(DISTINCT CASE WHEN h2.dia_id=4 THEN CONCAT(LEFT(h2.hora_desde, 5), '-', LEFT(h2.hora_hasta, 5)) ELSE NULL END ORDER BY h2.hora_desde SEPARATOR '<br>') h2_4,
GROUP_CONCAT(DISTINCT CASE WHEN h2.dia_id=5 THEN CONCAT(LEFT(h2.hora_desde, 5), '-', LEFT(h2.hora_hasta, 5)) ELSE NULL END ORDER BY h2.hora_desde SEPARATOR '<br>') h2_5,
GROUP_CONCAT(DISTINCT CASE WHEN h2.dia_id=6 THEN CONCAT(LEFT(h2.hora_desde, 5), '-', LEFT(h2.hora_hasta, 5)) ELSE NULL END ORDER BY h2.hora_desde SEPARATOR '<br>') h2_6,
GROUP_CONCAT(DISTINCT CASE WHEN h2.dia_id=7 THEN CONCAT(LEFT(h2.hora_desde, 5), '-', LEFT(h2.hora_hasta, 5)) ELSE NULL END ORDER BY h2.hora_desde SEPARATOR '<br>') h2_7,
MAX(sfh.id) as sfh_id,
GROUP_CONCAT(DISTINCT CASE WHEN sfh.dia_id=1 THEN CONCAT('(', LEFT(sfh.hora_desde, 5), '-', LEFT(sfh.hora_hasta, 5), ')') ELSE NULL END ORDER BY sfh.hora_desde SEPARATOR '<br>') sfh_1,
GROUP_CONCAT(DISTINCT CASE WHEN sfh.dia_id=2 THEN CONCAT('(', LEFT(sfh.hora_desde, 5), '-', LEFT(sfh.hora_hasta, 5), ')') ELSE NULL END ORDER BY sfh.hora_desde SEPARATOR '<br>') sfh_2,
GROUP_CONCAT(DISTINCT CASE WHEN sfh.dia_id=3 THEN CONCAT('(', LEFT(sfh.hora_desde, 5), '-', LEFT(sfh.hora_hasta, 5), ')') ELSE NULL END ORDER BY sfh.hora_desde SEPARATOR '<br>') sfh_3,
GROUP_CONCAT(DISTINCT CASE WHEN sfh.dia_id=4 THEN CONCAT('(', LEFT(sfh.hora_desde, 5), '-', LEFT(sfh.hora_hasta, 5), ')') ELSE NULL END ORDER BY sfh.hora_desde SEPARATOR '<br>') sfh_4,
GROUP_CONCAT(DISTINCT CASE WHEN sfh.dia_id=5 THEN CONCAT('(', LEFT(sfh.hora_desde, 5), '-', LEFT(sfh.hora_hasta, 5), ')') ELSE NULL END ORDER BY sfh.hora_desde SEPARATOR '<br>') sfh_5,
GROUP_CONCAT(DISTINCT CASE WHEN sfh.dia_id=6 THEN CONCAT('(', LEFT(sfh.hora_desde, 5), '-', LEFT(sfh.hora_hasta, 5), ')') ELSE NULL END ORDER BY sfh.hora_desde SEPARATOR '<br>') sfh_6,
GROUP_CONCAT(DISTINCT CASE WHEN sfh.dia_id=7 THEN CONCAT('(', LEFT(sfh.hora_desde, 5), '-', LEFT(sfh.hora_hasta, 5), ')') ELSE NULL END ORDER BY sfh.hora_desde SEPARATOR '<br>') sfh_7,
COALESCE(CONCAT(CASE WHEN de.anexo=0 THEN de.numero ELSE CONCAT(de.numero, '/', de.anexo) END, ' - ', de.nombre), CONCAT(da.codigo, ' ', da.descripcion)) destino, s.fecha_baja
FROM persona p
JOIN servicio s ON p.id=s.persona_id
JOIN situacion_revista sr ON s.situacion_revista_id=sr.id
JOIN cargo c ON s.cargo_id=c.id
JOIN condicion_cargo cc ON c.condicion_cargo_id=cc.id
JOIN regimen r ON c.regimen_id=r.id
LEFT JOIN division d ON c.division_id=d.id
LEFT JOIN curso cu ON d.curso_id=cu.id
LEFT JOIN espacio_curricular ec ON c.espacio_curricular_id=ec.id
LEFT JOIN materia m ON ec.materia_id=m.id
LEFT JOIN escuela e ON c.escuela_id=e.id
LEFT JOIN area a ON c.area_id=a.id
LEFT JOIN cargo_horario ch ON c.id=ch.cargo_id
LEFT JOIN horario h1 ON ch.horario_id=h1.id
LEFT JOIN horario h2 ON c.id=h2.cargo_id
LEFT JOIN servicio_funcion sf ON s.id=sf.servicio_id AND sf.fecha_hasta IS NULL
LEFT JOIN servicio_funcion_horario sfh ON sf.id=sfh.servicio_funcion_id
LEFT JOIN funcion f ON sf.funcion_id=f.id
LEFT JOIN escuela de ON sf.escuela_id=de.id
LEFT JOIN area da ON sf.area_id=da.id
LEFT JOIN servicio_novedad sn ON s.id=sn.servicio_id AND NOW() BETWEEN sn.fecha_desde AND sn.fecha_hasta AND sn.novedad_tipo_id IN (SELECT id FROM novedad_tipo WHERE novedad='N') AND DATE_FORMAT(NOW(), '%Y%m')=sn.ames
LEFT JOIN novedad_tipo nt ON sn.novedad_tipo_id=nt.id
LEFT JOIN tbcabh t ON t.servicio_id=s.id AND t.vigente=?
WHERE p.cuil=? AND (s.fecha_baja IS NULL OR t.id IS NOT NULL)
GROUP BY s.id", array(AMES_LIQUIDACION, $persona_cuil))->result();
		$s_tbcab = $this->db->query("SELECT s.id,
CASE WHEN t.guiescid IS NULL THEN CONCAT_WS(' - ', CONCAT(t.juri, '/', t.repa), repa.descripcion) ELSE CONCAT_WS(' - ', t.guiescid, e.nombre) END institucion,
r.codigo regimen_codigo, r.descripcion regimen, r.puntos regimen_puntos, CASE WHEN r.regimen_tipo_id=2 THEN t.diasoblig ELSE '' END carga_horaria,
'' funcion, '' funcion_desde, t.REVISTA revista, '' condicion,
'' novedad, CASE WHEN t.SINSUELDO=0 THEN 'Si' ELSE 'No' END haberes,
'' h1_1, '' h1_2, '' h1_3, '' h1_4, '' h1_5, '' h1_6, '' h1_7,
'' h2_1, '' h2_2, '' h2_3, '' h2_4, '' h2_5, '' h2_6, '' h2_7,
'' as sfh_id, '' sfh_1, '' sfh_2, '' sfh_3, '' sfh_4, '' sfh_5, '' sfh_6, '' sfh_7,
'' destino, '' fecha_baja
FROM tbcabh t
LEFT JOIN servicio s ON t.servicio_id=s.id
JOIN regimen r ON t.regimen=r.codigo
LEFT JOIN reparticion repa ON t.juri=repa.jurisdiccion_id AND t.repa=repa.codigo
LEFT JOIN escuela e ON t.guiescid=e.numero and e.anexo=0
WHERE t.vigente=?
AND t.persona=?
AND s.id IS NULL", array(AMES_LIQUIDACION, substr(str_replace('-', '', $persona_cuil), 0, 10)))->result();
		return array_merge($s_gem, $s_tbcab);
	}

	function get_dpersona_gem($cuil) {
		return $this->db->select("persona.cuil, CONCAT(persona.apellido, ', ', persona.nombre) as persona, COALESCE(DATE_FORMAT(persona.fecha_nacimiento, '%d/%m/%Y'), '') as fecha_nacimiento, CONCAT(COALESCE(CONCAT(persona.calle,' '),''), COALESCE(CONCAT(persona.calle_numero,' '),''), COALESCE(CONCAT('Dpto:',persona.departamento,' '),''), COALESCE(CONCAT('P:',persona.piso,' '),''), COALESCE(CONCAT('B°:',persona.barrio,' '),''), COALESCE(CONCAT('M:',persona.manzana,' '),''), COALESCE(CONCAT('C:',persona.casa,' '),'')) as domicilio, departamento.descripcion as departamento,  CONCAT(COALESCE(CONCAT(persona.telefono_fijo, ' - '), ''),COALESCE(CONCAT(persona.telefono_movil, ' '), '')) AS telefono")
				->from('persona')
				->join('localidad', 'localidad.id = persona.localidad_id', 'left')
				->join('departamento', 'departamento.id = localidad.departamento_id', 'left')
				->where('persona.cuil', $cuil)
				->get()->result();
	}

	function get_datospersona($cuil) {
		$db_dge55 = $this->load->database('dge55', TRUE);
		$db_dge55->select(array("PERSONA.PerCuil AS cuil",
			"RTRIM(PERSONA.PerApe) + ',  ' + PERSONA.PerNom AS Apenom",
			"PERSONA.PerDomClle + '  ' + PERSONA.PerDomNro + ', Piso ' + PERSONA.PerDomPiso + ',  Depto ' + PERSONA.PerDomDpto AS domicilio",
			"CONVERT(varchar(10),perfecnac,103) as fch_nac",
			"PERSONA.PerDomTel + ' - ' + PERSONA.PerDomTel2 AS telefono",
			"depto.depdes as depto"));
		$db_dge55->from('dge50.sirrhh.dbo.persona');
		$db_dge55->join('dge50.sirrhh.dbo.depto', 'persona.perdomdepi=depto.depid', 'inner');
		$db_dge55->where('percuil', $cuil);
		$result = $db_dge55->get();
		$datos = $result->row_array();
		if (empty($datos)) {
			$datos['cuil'] = $cuil;
		}
		return $datos;
	}
	/* NO SE USA MAS, PERO NUNCA SE SABE
	  function get_datosliquidacion($cuil) {
	  $db_dge55 = $this->load->database('dge55', TRUE);

	  $datos_query = $db_dge55->select(array("CASE WHEN (liqantca.LqdEscid <> '0000' AND liqantca.LqdEscid <> '    ') THEN liqantca.LqdEscid + '  ' + ESCUELA.EscNom ELSE 'ADMINISTRACION CENTRAL' END AS esc",
	  "liqantca.LqdLeg AS leg",
	  "CASE WHEN regsaltipc = 'H' THEN lqdhoras ELSE '' END AS horas",
	  "CASE WHEN regsaltipc = 'C' THEN lqdclase ELSE '    ' END AS ptos",
	  "CASE WHEN lqdsinsuel = 'S' THEN 'NO' ELSE 'SI' END AS haber",
	  "CASE WHEN liqantca.LiqTipCod = '01' THEN 'TITULAR' ELSE 'SUPLENTE' END AS revista",
	  "CASE WHEN liqantca.LqdConSal = 'S' THEN 'SI' ELSE 'NO' END AS salario",
	  "'Regimen ' + liqantca.LqdItem + ', Ptos ' + liqantca.LqdClase + ', ' + REGSALCA.RegSalDes AS cargo",
	  "liqantca.LqdLeg AS LqdLeg",
	  "liqantde.LqdDetCodi AS codigo",
	  "liqantde.LqdDetSubC AS subcod"))
	  ->from('liqantca_dj liqantca')
	  ->join('dge50.sirrhh.dbo.REGSALCA', 'liqantca.LqdItem = REGSALCA.RegSalCod', 'LEFT')
	  ->join('dge50.sirrhh.dbo.ESCUELA', 'liqantca.LqdEscid = ESCUELA.EscId AND liqantca.LqdEscanex = ESCUELA.EscAnexo', 'LEFT OUTER JOIN')
	  ->join('liqantde_dj liqantde', "liqantca.lqdleg=liqantde.lqdleg AND liqantde.LqdPerCuil=liqantca.LqdPerCuil AND liqantde.LiqTipCod='01' AND liqantde.LqdDetCodi in ('0440', '0950') AND liqantde.LqdDetRep='1'", 'LEFT')
	  ->where('liqantca.lqdpercuil', $cuil)
	  //			->where("(liqantca.lqdmes='01' AND liqantca.liqtipcod='03' OR liqantca.lqdmes='02' AND liqantca.liqtipcod<>'03')")//Arreglo temporal por fechas de tbcab
	  ->get();
	  if (!$datos_query)
	  return FALSE;
	  $datos = $datos_query->result_array();
	  return $datos;
	  }
	 */

	function get_datosantig($cuil) {
		$db_dge55 = $this->load->database('dge55', TRUE);

		$result = $db_dge55->select(array(
				"CASE WHEN anttip = 'D' THEN 'Docente' ELSE 'No Docente' END AS tipo_ant",
				"AntAnio as anos", "AntMes as mes", "AntDia as dia",
				"CONVERT(varchar(10),AntCalFec,103) AS fechacal"))
			->from('dge50.sirrhh.dbo.ANTTOTAL')
			->where('percuil', $cuil)
			->get();
		$datos = $result->result_array();
		return $datos;
	}

	function get_datosnovedad($cuil) {
		$db_dge55 = $this->load->database('dge55', TRUE);
		$db_dge55->select("CONVERT(varchar(10),novedad.novfchdesd,103) as fecha, novedad.artid as art,novedad.artincid as inc, artlic.artdescor as motivo,
			servicio.escid as escu , servicio.srvnroliq as legajo ");
		$db_dge55->from('dge50.sirrhh.dbo.novedad')
			->join('dge50.sirrhh.dbo.servicio', 'novedad.PerCuil=servicio.PerCuil and novedad.SrvPofCod=servicio.SrvPofCod and novedad.SrvAltFch=servicio.SrvAltFch')
			->join('dge50.sirrhh.dbo.artlic', 'novedad.ArtId=artlic.ArtId and novedad.ArtIncId=artlic.ArtIncId');
		$db_dge55->where('novedad.PerCuil', $cuil);
		$db_dge55->where("(novedad.ArtId ='48' or (novedad.ArtId ='02' and novedad.ArtIncId ='2')  or (novedad.ArtId ='02' and novedad.ArtIncId ='3'))");
		$db_dge55->order_by('novedad.NovFchDesd', 'asc');
		$result = $db_dge55->get();
		$datos = $result->result_array();
		return $datos;
	}

	function get_datos40444754($cuil) {
		$db_dge55 = $this->load->database('dge55', TRUE);
		$fecha = date("Y/m/d");
		$db_dge55->select("convert(varchar, novedad.novfchdesd, 103) as inicio, convert(varchar, novedad.novfchhast, 103) as fin, 
			novedad.artid as art,novedad.artincid as inc, artlic.artdescor as motivo,
			servicio.escid as escu , servicio.srvnroliq as legajo");
		$db_dge55->from('dge50.sirrhh.dbo.novedad')
			->join('dge50.sirrhh.dbo.servicio', 'novedad.PerCuil=servicio.percuil and novedad.srvpofcod=servicio.srvpofcod and  novedad.srvaltfch=servicio.srvaltfch')
			->join('dge50.sirrhh.dbo.artlic', 'novedad.artid=artlic.artid and novedad.artincid=artlic.artincid');
		$db_dge55->where('novedad.percuil', $cuil);
		$db_dge55->where("(novedad.artid ='40' or novedad.artid ='44' or novedad.artid ='47' or novedad.artid ='54' or 
			(novedad.artid ='50' and novedad.artincid ='7'))");
		$db_dge55->where('novedad.novfchdesd <=', str_replace('/', '', $fecha));
		$db_dge55->where('novedad.novfchhast >=', str_replace('/', '', $fecha));
		$db_dge55->order_by('novedad.novfchdesd', 'asc');
		$result = $db_dge55->get();
		$datos = $result->result_array();
		return $datos;
	}

	function get_datoscbiofuncion($cuil) {
		$DB1 = $this->load->database('saludLaboral', TRUE);
		$result = $DB1->query("SELECT Percuil, MAX(FECHA_INIC_CF) AS fch FROM view_CambiosFuncion WHERE Percuil = ? GROUP BY Percuil", array($cuil));
		if (!$result)
			return FALSE;
		$dato = $result->row_array();

		if (!empty($dato)) {
			$fecha = $dato['fch'];
			$result = $DB1->query("SELECT FECHA_INIC_CF as fecha, ULT_RES as resolucion, patologia, observaciones FROM view_CambiosFuncion WHERE view_CambiosFuncion.Percuil = ? AND view_CambiosFuncion.FECHA_INIC_CF = ?", array($cuil, str_replace('/', '', $fecha)));
			if (!$result)
				return FALSE;
			$datos = $result->row_array();
			return $datos;
		}
	}

	function get_datosaptitud($cuil) {
		/* Recupera la última fecha de la tabla aptitud para el campo recibido */
		$db_dge55 = $this->load->database('dge55', TRUE);
		$result = $db_dge55->query("SELECT Cuil, MAX(CONVERT(varchar,recibido,103)) AS max1 FROM dge50.sirrhh.dbo.aptitud WHERE Cuil = ? GROUP BY Cuil", array($cuil));
		$dato = $result->row_array();
		if (!empty($dato)) {
			$max1 = $dato['max1'];
		}

		/* Recupera la última fecha de la tabla aptitud para el campo recibido */
		$result = $db_dge55->query("SELECT Cuil, MAX(CONVERT(varchar,recibido1,103)) AS max2 FROM dge50.sirrhh.dbo.aptitud WHERE Cuil = ? AND recibido1 IS NOT NULL GROUP BY Cuil", array($cuil));
		$dato = $result->row_array();
		if (!empty($dato)) {
			$max2 = $dato['max2'];
		}

		if (!empty($dato)) {
			if (($max1) >= ($max2)) {
				$fecha = DateTime::createFromFormat('d/m/Y', $max1);
				$fecha = date_format($fecha, 'd/m/Y');

				$result = $db_dge55->query("SELECT cargo, preoc, period, periodo_aut, recibido, CASE WHEN aptitud like '%apto%' THEN aptitud ELSE 'NO APTO' END AS aptitud FROM dge50.sirrhh.dbo.aptitud WHERE Cuil = ? AND recibido = ?", array($cuil, $fecha));
				$datos = $result->row_array();
				return $datos;
			} else {
				$fecha = DateTime::createFromFormat('d/m/Y', $max2);
				$fecha = date_format($fecha, 'd/m/Y');

				$result = $db_dge55->query("SELECT cargo, recibido1 as recibido, CASE WHEN aptitudotorgada like '%apto%' THEN aptitudotorgada ELSE 'NO APTO' END AS aptitud FROM dge50.sirrhh.dbo.aptitud WHERE Cuil = ? AND recibido1 = ?", array($cuil, $fecha));
				$datos = $result->row_array();
				return $datos;
			}
		}
	}

	function get_horario_by_liquidacion($liquidacion) {
		$query = $this->db->select('dia.id as dia_id, TIME_FORMAT(COALESCE(h.hora_desde, sfh.hora_desde), "%H:%i") as hora_desde, TIME_FORMAT(COALESCE(h.hora_hasta, sfh.hora_hasta), "%H:%i") as hora_hasta')
			->from('servicio s')
			->join('persona p', 'p.id = s.persona_id')
			->join('cargo c', 'c.id = s.cargo_id', '')
			->join('regimen r', 'r.id = c.regimen_id AND r.planilla_modalidad_id=1', '')
			->join('servicio_funcion sf', 's.id = sf.servicio_id AND sf.fecha_hasta IS NULL', 'left')
			->join('funcion f', 'f.id = sf.funcion_id', 'left')
			->join('servicio_funcion_horario sfh', 'sf.id = sfh.servicio_funcion_id', 'left')
			->join('cargo_horario ch', 'ch.cargo_id = s.cargo_id AND sfh.id IS NULL', 'left')
			->join('horario h', '(h.cargo_id = s.cargo_id OR h.id = ch.horario_id) AND sfh.id IS NULL', 'left')
			->join('dia', 'dia.id = h.dia_id OR dia.id = sfh.dia_id', 'left')
			->where('s.liquidacion', $liquidacion)
			->order_by('(CASE WHEN s.fecha_baja IS NULL THEN 0 ELSE 1 END), s.fecha_baja DESC, s.fecha_alta DESC, dia.id, s.id ASC, h.hora_desde ASC');
		return $query->get()->result();
	}

	function get_liquidacion($persona_cuil) {
		$query = $this->db->select('s.id, s.liquidacion, c.carga_horaria, r.codigo as regimen_codigo, t.RegSalDes as regimen, '
				. 'COALESCE(CONCAT(es.numero, CASE WHEN es.anexo=0 THEN "" ELSE CONCAT("/", anexo) END, " - ", es.nombre), ar.descripcion) as escuela_area, '
				. 'sr.descripcion as situacion_revista, ar.descripcion as area, '
				. 'ar.codigo as area_codigo, '
				. "COALESCE(f.descripcion, sf.detalle) as f_detalle, c.escuela_id, c.area_id, , t.diasoblig,  CASE WHEN t.SINSUELDO = 0 THEN 'SI' ELSE 'NO' END as haber")
			->from('servicio s')
			->join('persona p', 'p.id = s.persona_id', 'left')
			->join('cargo c', 'c.id = s.cargo_id')
			->join('condicion_cargo cc', 'cc.id = c.condicion_cargo_id')
			->join('escuela es', 'es.id = c.escuela_id', 'left')
			->join('area ar', 'ar.id = c.area_id', 'left')
			->join('situacion_revista sr', 'sr.id = s.situacion_revista_id')
			->join('regimen r', 'r.id = c.regimen_id AND r.planilla_modalidad_id=1')
			->join('division d', 'd.id = c.division_id', 'left')
			->join('espacio_curricular ec', 'ec.id = c.espacio_curricular_id', 'left')
			->join('materia m', 'm.id = ec.materia_id', 'left')
			->join('curso cu', 'cu.id = d.curso_id', 'left')
			->join('servicio_funcion sf', 'sf.servicio_id = s.id AND sf.fecha_hasta IS NULL', 'left')
			->join('funcion f', 'sf.funcion_id = f.id', 'left')
			->join('tbcabh t', 't.servicio_id = s.id AND t.vigente=' . AMES_LIQUIDACION, 'left')
			->where('p.cuil', $persona_cuil)
			->order_by('(CASE WHEN s.fecha_baja IS NULL THEN 0 ELSE 1 END), s.fecha_baja DESC, s.fecha_alta DESC, s.liquidacion DESC');
		return $query->get()->result_array();
	}
}
?>