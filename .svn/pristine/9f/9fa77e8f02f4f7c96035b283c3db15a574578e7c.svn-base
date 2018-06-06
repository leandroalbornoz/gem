-- ESCUELAS

SELECT DISTINCT escid AS numero_escuela,
                cue,
                subcue,
                escnom AS nombre_escuela,
                guirepcod AS reparticion_id,
                rglid AS regional_id,
                zonid AS zona_id,
                escaniores AS anio_resolucion,
                escfeccre AS fecha_resolucion,
                escnumres AS numero_resolucion,
                esctel AS telefono,
                escemail AS email,
                escfeccie AS fecha_cierre,
                escnumreci AS numero_resolucion_cierre,
                g.GuiJurId AS jurisdiccion_id
FROM escuela e
LEFT JOIN GUIAREP g ON e.escid = g.guiescid
AND g.GuiFchHas = '17530101'
WHERE escid IN ('4016',
                '1047',
                '3216',
                '4124',
                '9002',
                '0123',
				'4128');

 -- DIVISIONES
SELECT escid ,
       MIN(turno_id) AS turno_id,
       curso_id,
       MAX(division_id) AS division
FROM fila f
INNER JOIN planilla p ON f.planilla_id = p.id
WHERE escid IN('1004', '4046', '4016', '1047', '3216', '4124', '9002', '0123', 'T019')
  AND p.mes=12
  AND curso_id IS NOT NULL
  AND curso_id != ''
  AND curso_id != 'NO CORRESPONDE'
  AND division_id != 'NO CORRESPONDE'
  AND turno_id IS NOT NULL
GROUP BY escid,
         curso_id,
         REPLACE(REPLACE(division_id, '°', ''), 'º', '');
		 
-- CARGOS

SELECT f.id,
       1 AS condicion_cargo,
       NULL AS espacio_curricular_id,
       materia_id,
       materia_nombre,
       turno_id,
       curso_id,
       division_id,
       CASE
           WHEN left(regsaldesc,4)='HORA' THEN oblig
           ELSE 0
       END AS carga_horaria,
       regimen_salarial,
       escid
FROM fila f
JOIN planilla p ON f.planilla_id=p.id
WHERE p.escid IN('1004',
                 '4046',
                 '4016',
                 '1047',
                 '3216',
                 '4124',
                 '9002',
                 '0123', 
		 'T019')
  AND p.mes=12
  AND (fila_id IS NULL or fila_id <= 0);


 -- PERSONA
 
 SELECT max(persona_cuil) AS persona_cuil,
       1 AS documento_tipo_id,
       documento,
       max(LEFT(persona_apenom,CHARINDEX(' ',persona_apenom)- 1)) AS apellido,
       max(SUBSTRING(persona_apenom, CHARINDEX(' ', persona_apenom) + 1, LEN(persona_apenom))) AS nombre,
       max(persona_fecha_nacimiento) AS fecha_nacimiento
FROM fila f
INNER JOIN planilla p ON f.planilla_id = p.id
WHERE p.escid IN('1004',
                 '4046',
                 '4016',
                 '1047',
                 '3216',
                 '4124',
                 '9002',
                 '0123', 
		 'T019')
  AND p.mes=12
GROUP BY documento;

-- SERVICIO

SELECT f.id,
       CASE
           WHEN f.fila_id IS NULL THEN -1
           ELSE f.fila_id
       END AS fila_id,
       documento,
       liquidacion,
       revista,
       CASE
           WHEN f.baja_fecha IS NULL THEN '01-01-1753'
           ELSE f.baja_fecha
       END AS baja_fecha,
       baja_motivo,
       funcion_detalle
FROM fila f
INNER JOIN planilla p ON f.planilla_id = p.id
WHERE p.escid IN('1004',
                 '4046',
                 '4016',
                 '1047',
                 '3216',
                 '4124',
                 '9002',
                 '0123',
	 	 'T019')
  AND p.mes=12;

  
 -- SERVICIO_FUNCION
 
 SELECT f.id,
       funcion_detalle,
       funcion_destino,
       funcion_norma,
       funcion_tarea,
       funcion_cargahoraria
FROM fila f
LEFT JOIN planilla p ON f.planilla_id=p.id
WHERE p.escid IN('1004',
                 '4046',
                 '4106',
                 '1047',
                 '3216',
                 '4124',
                 '9002',
                 '0123',
		 'T019')
  AND p.mes=12
  AND funcion_detalle != ''
  AND funcion_detalle IS NOT NULL;
  
  -- CELADORES
  SELECT DISTINCT f.id,
                persona_cuil,
                1 AS documento_tipo_id,
                documento,
                LEFT(persona_apenom,CHARINDEX(' ',persona_apenom)- 1) AS apellido,
                SUBSTRING(persona_apenom, CHARINDEX(' ', persona_apenom) + 1, LEN(persona_apenom)) AS nombre,
                persona_fecha_nacimiento AS fecha_nacimiento,
                celador_concepto,
                celador_estudios
FROM fila f
INNER JOIN planilla p ON f.planilla_id = p.id
WHERE p.escid IN('1004',
                 '4046',
                 '4106',
                 '1047',
                 '3216',
                 '4124',
                 '9002',
                 '0123',
		 'T019')
  AND p.mes=12
  AND celador_concepto IS NOT NULL
  AND celador_concepto != '';
  
  -- SERVICIOS DGE50
  SELECT *
FROM SERVICIO
WHERE escid IN('1004',
               '4046',
               '4106',
               '1047',
               '3216',
               '4124',
               '9002',
               '0123',
		'T019')
ORDER BY isnull(srvunicos,0),
         isnull(mat1cod,0),
         isnull(areacod,0),
         isnull(srvhorario,0),
         isnull(srvdestino,0);
		 
-- PERSONA DGE50
SELECT *
  FROM persona where PerCuil in (SELECT PerCuil
FROM SERVICIO
WHERE escid IN('1004',
               '4046',
               '4106',
               '1047',
               '3216',
               '4124',
               '9002',
               '0123',
			   'T019'))
		ORDER BY 
		 isnull([PerTipoTit],0),
         isnull([PerDifmmyy],0),
         isnull([PerExpte],0),
         isnull([PerCntRecl],0),
         isnull([PerRDfch],0),
		 isnull([Perjubfch],0),
		 isnull([PerJubnum],0),
		 isnull([PerRCexp],0),
		 isnull([PerRCres],0),
		 isnull([PErRCnotif],0),
		 isnull([PerRCemite],0),
		 isnull([PerFchCobr],0),
		 isnull([PerResJub],0),
		 isnull([PerFchApr],0),
		 isnull([PerNroBen],0),
		 isnull([PerExpAnse],0);






  