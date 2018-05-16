 set foreign_key_checks=0;
truncate division;
truncate cargo;
truncate persona;
truncate servicio;
truncate servicio_funcion;
set foreign_key_checks=1;

-- DIVISIONES
INSERT INTO division (escuela_id, turno_id, curso_id, division, carrera_id, fecha_alta)
SELECT e.id AS escuela_id,
       d.turno_id,
       al.curso_id,
       d.division,
       NULL AS carrera_id,
       '2016-01-01' AS fecha_alta
FROM cedula_migracion.tmp_division d
JOIN escuela e ON d.escid=e.numero
LEFT JOIN cedula_migracion.tmp_alugrados al ON al.grado_id = d.curso_id
AND al.nivel_id=e.nivel_id;

 -- actualizar carreras de divisiones en escuelas con 1 sola carrera (debe estar previamente cargada)

UPDATE division d
JOIN escuela e ON d.escuela_id=e.id
JOIN escuela_carrera ec ON ec.escuela_id=e.id
SET d.carrera_id=ec.carrera_id
WHERE d.escuela_id IN
    (SELECT escuela_id
     FROM escuela_carrera
     GROUP BY escuela_id HAVING count(1)=1);


 -- CARGOS
INSERT INTO cargo (condicion_cargo_id, division_id, espacio_curricular_id, carga_horaria, regimen_id, escuela_id, pof_id)
SELECT condicion_cargo,
       d.id AS division_id,
       NULL,
       carga_horaria,
       reg.id AS reg_salarial_id,
       e.id AS escuela_id,
       c.id AS pof_id
FROM cedula_migracion.tmp_cargo c
JOIN escuela e ON c.escid=e.numero
LEFT JOIN regimen reg ON c.regimen_salarial = reg.codigo
LEFT JOIN cedula_migracion.tmp_alugrados al ON al.grado_id = c.curso_id
AND al.nivel_id = e.nivel_id
LEFT JOIN curso cu ON cu.id = al.curso_id
LEFT JOIN division d ON cu.id=d.curso_id
AND d.escuela_id = e.id
AND REPLACE(REPLACE(c.division_id, '°', ''), 'º', '')=REPLACE(REPLACE(d.division, '°', ''), 'º', '') ;

 -- actualizar espacios curriculares de cargos según carrera_id de division de cada cargo (debe estar previamente cargada) y tmp_materia

UPDATE cargo c
JOIN division d ON c.division_id=d.id
JOIN cedula_migracion.tmp_cargo tc ON c.pof_id=tc.id
LEFT JOIN cedula_migracion.tmp_materia tm ON tc.materia_nombre=tm.nombre
LEFT JOIN espacio_curricular ec ON d.carrera_id=ec.carrera_id
AND tm.materia_id=ec.materia_id
SET c.espacio_curricular_id=ec.id
WHERE c.espacio_curricular_id IS NOT NULL;


 -- PERSONAS

INSERT INTO persona (cuil, documento_tipo_id, documento, apellido, nombre, fecha_nacimiento, estado_civil_id)
SELECT max(cuil) AS cuil,
       documento_tipo_id,
       documento,
       max(apellido),
       max(nombre),
       fecha_nacimiento,
       1 AS estado_civil_id
FROM cedula_migracion.tmp_persona
GROUP BY documento;

 -- SERVICIO

 -- para arreglar nulos
-- update cedula_migracion.tmp_servicio set fila_id = NULL where fila_id <= 0;
-- update cedula_migracion.tmp_servicio set baja_fecha = NULL where baja_fecha = '1753-01-01';

INSERT INTO servicio (persona_id, cargo_id, fecha_alta, fecha_baja, liquidacion, 
reemplazo_id, situacion_revista_id, pof_id, motivo_baja, funcion_detalle)
SELECT DISTINCT p.id AS persona_id,
                c.id AS cargo_id,
                '1753-01-01' AS fecha_desde,
                baja_fecha,
                substring(s.liquidacion, 7,12) AS liquidacion,
                NULL AS reemplazo_id,
                CASE
                    WHEN s.revista='REEMPLAZOS' THEN 2
                    ELSE 1
                END AS situacion_revista_id,
                s.id,
                baja_motivo,
                funcion_detalle
FROM cedula_migracion.tmp_servicio s
INNER JOIN persona p ON s.documento = p.documento
LEFT JOIN cargo c ON COALESCE(fila_id,s.id)=c.pof_id ;

-- SERVICIO_FUNCION

INSERT INTO servicio_funcion (servicio_id, detalle, destino, norma, tarea, carga_horaria)
SELECT s.id,
       sf.funcion_detalle,
       sf.funcion_destino,
       sf.funcion_norma,
       sf.funcion_tarea,
       sf.funcion_cargahoraria
FROM cedula_migracion.tmp_servicio_funcion sf
INNER JOIN servicio s ON sf.id = s.pof_id;


 -- CELADOR

-- update de tabla servicios donde se cargan los conceptos de celadores

UPDATE servicio s
INNER JOIN
  (SELECT p.id,
          CASE
              WHEN tc.celador_concepto='Bueno' THEN 1
              WHEN tc.celador_concepto='Exelente' THEN 2
              WHEN tc.celador_concepto='Muy Bueno' THEN 3
              ELSE 4
          END AS celador_concepto_id
   FROM cedula_migracion.tmp_celador tc
   INNER JOIN persona p ON tc.documento = p.documento) b ON b.id = s.persona_id
SET s.celador_concepto_id = b.celador_concepto_id ;

 -- update de tabla persona donde se carga el nivel de estudios de los celadores

UPDATE persona p
INNER JOIN
  (SELECT p.id,
          CASE
              WHEN tc.celador_estudios='PRIMARIO' THEN 1
              WHEN tc.celador_estudios='SECUNDARIO' THEN 2
              WHEN tc.celador_estudios='TERCIARIO' THEN 3
              ELSE NULL
          END AS celador_estudios
   FROM cedula_migracion.tmp_celador tc
   INNER JOIN persona p ON tc.documento = p.documento) b ON p.id = b.id
SET p.nivel_estudio_id = b.celador_estudios;