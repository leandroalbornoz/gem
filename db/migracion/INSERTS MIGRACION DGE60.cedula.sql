 -- ESCUELA
 -- Se migran las escuelas a traves del paquete generado en SQLServer
 -- tmp_escuela
 -- una vez migrado eso, se realiza el siguiente insert
 
INSERT INTO cedula.escuela (numero, cue, nombre, reparticion_id, regional_id, zona_id, anio_resolucion, 
fecha_creacion, numero_resolucion, telefono, email, fecha_cierre, numero_resolucion_cierre, nivel_id)
SELECT numero_escuela,
       CASE
           WHEN cue='' THEN NULL
           ELSE cue
       END,
       nombre_escuela,
       r.id,
       CASE
           WHEN regional_id='' THEN NULL
					 WHEN regional_id='0' THEN NULL
					 WHEN regional_id>'0' THEN NULL
           ELSE regional_id
       END,
       CASE
           WHEN zona_id='' THEN NULL
           ELSE zona_id
       END,
       anio_resolucion,
       fecha_resolucion,
       numero_resolucion,
       telefono,
       email,
	   CASE
           WHEN fecha_cierre = '17530101' THEN NULL
           ELSE fecha_cierre
       END AS fecha_cierre,
       numero_resolucion_cierre,
       2 AS nivel_id
FROM cedula_migracion.tmp_escuela_3 e
LEFT JOIN cedula.jurisdiccion j ON e.jurisdiccion_id=j.codigo
LEFT JOIN cedula.reparticion r ON e.reparticion_id=r.codigo
AND j.id=r.jurisdiccion_id 
WHERE numero_escuela in ('4111','4115','T001') AND subcue ='';

-- INSERT DE ESCUELAS PRIVADAS

/*INSERT INTO escuela (numero, cue, nombre,regional_id, zona_id, anio_resolucion, 
fecha_creacion, numero_resolucion, telefono, email, fecha_cierre, numero_resolucion_cierre, nivel_id)
SELECT numero_escuela,
       CASE
           WHEN cue='' THEN NULL
           ELSE cue
       END,
       nombre_escuela,
       CASE
           WHEN regional_id='' THEN NULL
           ELSE regional_id
       END,
       CASE
           WHEN zona_id='' THEN NULL
           ELSE zona_id
       END,
       anio_resolucion,
       fecha_resolucion,
       numero_resolucion,
       telefono,
       email,
	   CASE
           WHEN fecha_cierre = '17530101' THEN NULL
           ELSE fecha_cierre
       END AS fecha_cierre,
       numero_resolucion_cierre,
       2 AS nivel_id
FROM cedula_migracion.tmp_escuela_2 e
LEFT JOIN jurisdiccion j ON e.jurisdiccion_id=j.codigo
LEFT JOIN reparticion r ON e.reparticion_id=r.codigo
AND j.id=r.jurisdiccion_id
WHERE numero_escuela in ('C257',
'P049',
'P124');*/

-- CAMBIAR FECHA BASE A NULL
UPDATE escuela set fecha_creacion=null where fecha_creacion='1753-01-01';
UPDATE escuela set fecha_creacion=null where fecha_cierre='1753-01-01';

-- UPDATE PARA ORDENAR REGIMENES, SE DEBEN EJECUTAR DESPUES DE EDITAR LOS NIVEL_ID QUE SE HACEN A MANO

-- NO EJECUTAR HASTA CARGAR NIVELES!!!!!!!
UPDATE escuela SET regimen_lista_id=2 where nivel_id=2 AND regimen_lista_id IS NULL;
UPDATE escuela SET regimen_lista_id=13 where nivel_id=3 AND regimen_lista_id IS NULL;
UPDATE escuela SET regimen_lista_id=13 where nivel_id=4 AND regimen_lista_id IS NULL;
UPDATE escuela SET regimen_lista_id=5 where nivel_id=5 AND regimen_lista_id IS NULL;
UPDATE escuela SET regimen_lista_id=7 where nivel_id=6 AND regimen_lista_id IS NULL;
UPDATE escuela SET regimen_lista_id=15 where nivel_id=7 AND regimen_lista_id IS NULL;
UPDATE escuela SET regimen_lista_id=1 where nivel_id=8 AND regimen_lista_id IS NULL;
UPDATE escuela SET regimen_lista_id=14 where nivel_id=9 AND regimen_lista_id IS NULL;
UPDATE escuela SET regimen_lista_id=6 where nivel_id=10 AND regimen_lista_id IS NULL;
UPDATE escuela SET regimen_lista_id=4 where nivel_id=11 AND regimen_lista_id IS NULL;
UPDATE escuela SET regimen_lista_id=10 where nivel_id=12 AND regimen_lista_id IS NULL;
UPDATE escuela SET regimen_lista_id=6 where nivel_id=13 AND regimen_lista_id IS NULL;

 -- DIVISIONES
 -- Se migran las divisiones a traves del paquete generado en SQLServer
 -- tmp_division
 -- una vez migrado eso, se realiza el siguiente insert
 
-- AGREGAR CURSO_ID A TABLA DE MIGRACION DE DIVISIONES
UPDATE cedula_migracion.tmp_division_privada tdp 
JOIN cedula.escuela e ON e.numero=tdp.escid 
JOIN cedula_migracion.tmp_alugrados ta ON ta.nivel_id=e.nivel_id AND tdp.divanicod = ta.grado_id
SET tdp.curso_id = ta.curso_id;


INSERT INTO cedula.division (escuela_id, turno_id, curso_id, division, carrera_id, fecha_alta)
SELECT e.id AS escuela_id,
       td.turno_id,
       ta.curso_id,
       td.division,
       NULL AS carrera_id,
       '2016-01-01' AS fecha_alta
FROM cedula_migracion.tmp_division td
JOIN cedula.escuela e ON td.escid=e.numero AND e.anexo=0
LEFT JOIN cedula_migracion.tmp_alugrados ta ON ta.curso_id = td.curso_id
AND ta.nivel_id=e.nivel_id 
LEFT JOIN cedula.division d ON
	REPLACE(REPLACE(REPLACE(REPLACE(d.division,'Â', ''), 'ª',''), '°', ''), 'º', '') = REPLACE(REPLACE(REPLACE(REPLACE(td.division,'Â', ''), 'ª',''), '°', ''), 'º', '') 
	AND d.curso_id = ta.curso_id 
	AND d.escuela_id = e.id
WHERE  d.id is null AND ta.curso_id is not null;



-- Buscar Divisiones repetidas

select d.*, d2.*
from division d
join division d2 on d.id<d2.id AND d.escuela_id=d2.escuela_id and d.turno_id=d2.turno_id and d.curso_id=d2.curso_id and d.division=d2.division;

 -- actualizar carreras de divisiones en escuelas con 1 sola carrera (debe estar previamente cargada)

UPDATE cedula.division d
JOIN cedula.escuela e ON d.escuela_id=e.id
JOIN cedula.escuela_carrera ec ON ec.escuela_id=e.id
SET d.carrera_id=ec.carrera_id
WHERE d.escuela_id IN
    (SELECT escuela_id
     FROM cedula.escuela_carrera
     GROUP BY escuela_id HAVING count(1)=1);

 -- CARGOS
 -- Se migran los cargos a traves del paquete generado en SQLServer
 -- tmp_cargo
 -- cuando se migra la tabla, el paso siguiente es insertar los datos en la tabla original.

INSERT INTO cedula.cargo (condicion_cargo_id, division_id, espacio_curricular_id, carga_horaria, regimen_id, escuela_id, pof_id)
SELECT condicion_cargo,
       d.id AS division_id,
       NULL,
       carga_horaria,
       reg.id AS reg_salarial_id,
       e.id AS escuela_id,
       c.id AS pof_id
FROM cedula_migracion.tmp_cargo c
JOIN cedula.escuela e ON c.escid=e.numero AND e.anexo = 0
LEFT JOIN cedula.regimen reg ON c.regimen_salarial = reg.codigo
LEFT JOIN cedula_migracion.tmp_alugrados al ON al.grado_id = c.curso_id
AND al.nivel_id = e.nivel_id
LEFT JOIN cedula.curso cu ON cu.id = al.curso_id
LEFT JOIN cedula.division d ON cu.id=d.curso_id
AND d.escuela_id = e.id  
AND REPLACE(REPLACE(c.division_id COLLATE latin1_spanish_ci, '°', ''), 'º', '')=REPLACE(REPLACE(d.division COLLATE latin1_spanish_ci, '°', ''), 'º', '') ;

-- Buscar Cargos repetidos

select c.*, c2.*
from cargo c
join cargo c2 on c.id<c2.id and c.pof_id=c2.pof_id
limit 10000;

 -- actualizar espacios curriculares de cargos según carrera_id de division de cada cargo (debe estar previamente cargada) y tmp_materia

UPDATE cedula.cargo c
JOIN cedula.division d ON c.division_id=d.id
JOIN cedula_migracion.tmp_cargo tc ON c.pof_id=tc.id
LEFT JOIN cedula_migracion.tmp_materia tm ON tc.materia_nombre=tm.nombre
LEFT JOIN cedula.espacio_curricular ec ON d.carrera_id=ec.carrera_id
AND tm.materia_id=ec.materia_id
SET c.espacio_curricular_id=ec.id
WHERE c.espacio_curricular_id IS NULL;

 -- PERSONA
 -- Se migran las personas a traves del paquete generado en SQLServer
 -- tmp_persona
 -- una vez migrada la tabla, lo siguiente es insertar los datos en la tabla original.

INSERT INTO cedula.persona (cuil, documento_tipo_id, documento, apellido, nombre, fecha_nacimiento, estado_civil_id)
SELECT max(cmp.cuil) AS cuil,
       cmp.documento_tipo_id,
       cmp.documento,
       max(cmp.apellido),
       max(cmp.nombre),
       cmp.fecha_nacimiento,
       1 AS estado_civil_id
FROM cedula_migracion.tmp_persona cmp
LEFT JOIN cedula.persona cp ON cp.documento = cmp.documento
where cp.id is NULL
GROUP BY documento;

 -- SERVICIO
 -- tmp_servicio
 
 -- para arreglar nulos
 -- update cedula_migracion.tmp_servicio set fila_id = NULL where fila_id <= 0;
 -- update cedula_migracion.tmp_servicio set baja_fecha = NULL where baja_fecha = '1753-01-01';

INSERT INTO cedula.servicio (persona_id, cargo_id, fecha_alta, fecha_baja, liquidacion, situacion_revista_id, pof_id, motivo_baja)
SELECT DISTINCT p.id AS persona_id,
                COALESCE(c.id,c2.id,c3.id) AS cargo_id,
                '1753-01-01' AS fecha_desde,
                s.baja_fecha,
                substring(s.liquidacion, 7,12) AS liquidacion,  
                CASE
                    WHEN s.revista='REEMPLAZOS' THEN 2
                    ELSE 1
                END AS situacion_revista_id,
                s.id,
                s.baja_motivo
FROM cedula_migracion.tmp_servicio s
INNER JOIN cedula.persona p ON s.documento = p.documento
LEFT JOIN cedula.cargo c ON CASE WHEN COALESCE(fila_id,-1)<=0 THEN s.id ELSE fila_id END=c.pof_id
LEFT JOIN cedula_migracion.tmp_servicio s2 ON s.fila_id=s2.id
LEFT JOIN cedula.cargo c2 ON CASE WHEN COALESCE(s2.fila_id,-1)<=0 THEN s2.id ELSE s2.fila_id END=c2.pof_id
LEFT JOIN cedula_migracion.tmp_servicio s3 ON s2.fila_id=s3.id
LEFT JOIN cedula.cargo c3 ON CASE WHEN COALESCE(s3.fila_id,-1)<=0 THEN s3.id ELSE s3.fila_id END=c3.pof_id;

--UPDATE REEMPLAZOS
UPDATE servicio s
  JOIN cedula_migracion.tmp_servicio ts ON s.pof_id = ts.id AND ts.fila_id >0
JOIN servicio s2 ON ts.fila_id = s2.pof_id
SET s.reemplazado_id = s2.id
where s.reemplazado_id is null;

--Buscar Servicios Repetidos

select s.*, s2.*
from servicio s
join servicio s2 on s.id<s2.id and s.pof_id=s2.pof_id
limit 10000;

-- CAMBIAR FECHA BASE A NULL

UPDATE servicio set fecha_alta = null where fecha_alta ='1753-01-01';
UPDATE servicio set fecha_baja = null where fecha_baja='1753-01-01';

 -- SERVICIO_FUNCION
 -- tmp_servicio_funcion
 -- INSERT

INSERT INTO cedula.servicio_funcion (servicio_id, detalle, destino, norma, tarea, carga_horaria)
SELECT s.id,
       sf.funcion_detalle,
       sf.funcion_destino,
       sf.funcion_norma,
       sf.funcion_tarea,
       sf.funcion_cargahoraria
FROM cedula_migracion.tmp_servicio_funcion sf
INNER JOIN cedula.servicio s ON sf.id = s.pof_id;

-- Buscar Servicio_funcion repetidos

select sf.*, sf2.*
from servicio_funcion sf
join servicio_funcion sf2 on sf.id<sf2.id AND sf.servicio_id=sf2.servicio_id limit 10000;

 -- CELADORES
 -- tmp_celador
 -- UPDATES
 -- update de tabla servicios donde se cargan los conceptos de celadores

UPDATE cedula.servicio s
INNER JOIN
  (SELECT p.id,
          CASE
              WHEN tc.celador_concepto='Bueno' THEN 1
              WHEN tc.celador_concepto='Exelente' THEN 2
              WHEN tc.celador_concepto='Muy Bueno' THEN 3
              ELSE 4
          END AS celador_concepto_id
   FROM cedula_migracion.tmp_celador tc
   INNER JOIN cedula.persona p ON tc.documento = p.documento) b ON b.id = s.persona_id
SET s.celador_concepto_id = b.celador_concepto_id ;

 -- update de tabla persona donde se carga el nivel de estudios de los celadores

UPDATE cedula.persona p
INNER JOIN
  (SELECT p.id,
          CASE
              WHEN tc.celador_estudios='PRIMARIO' THEN 1
              WHEN tc.celador_estudios='SECUNDARIO' THEN 2
              WHEN tc.celador_estudios='TERCIARIO' THEN 3
              ELSE NULL
          END AS celador_estudios
   FROM cedula_migracion.tmp_celador tc
   INNER JOIN cedula.persona p ON tc.documento = p.documento) b ON p.id = b.id
SET p.nivel_estudio_id = b.celador_estudios;

 -- SERVICIOS DGE50
 -- tmp_servicio_dge50
 -- actualiza fechas de alta y baja de servicios con dge50

UPDATE cedula.servicio s
JOIN cedula_migracion.tmp_servicio_dge50 s50 ON s.liquidacion=s50.srvnroliq
SET s.fecha_alta=s50.SrvAltFch
where s.fecha_alta is null;  

				 
UPDATE cedula.servicio s
JOIN cedula_migracion.tmp_servicio_dge50 s50 ON s.liquidacion=s50.srvnroliq
SET s.fecha_baja=CASE
                     WHEN s50.SrvBajFch='1753-01-01' THEN NULL
                     ELSE s50.SrvBajFch
                 END
where s.fecha_baja is null;

 -- PERSONA DGE50
 -- tmp_persona_dge50
 -- UPDATE de personas que ya existian cargadas desde dge55 con datos que no contenian de dge50

-- Se agrega documento desde el cuil de las personas para hacer join desde el mismo con la tabla persona.

UPDATE cedula_migracion.tmp_persona_dge50 p
JOIN cedula_migracion.tmp_persona_dge50 p50 ON p.PerCuil = p50.PerCuil
SET p.DocFromCuil=SUBSTRING(p50.PerCuil,4,8)
where p.DocFromCuil is null

UPDATE cedula.persona p
JOIN cedula_migracion.tmp_persona_dge50 tpd ON p.documento = tpd.DocFromCuil
SET p.cuil=tpd.PerCuil, 
	p.documento_tipo_id='1',
	p.documento=tpd.DocFromCuil,
	p.apellido=tpd.PerApe,
	p.nombre=tpd.PerNom,
	p.calle=tpd.PerDomClle,
	p.calle_numero=tpd.PerDomNro,
	p.piso=tpd.PerDomPiso,
	p.departamento=tpd.PerDomDpto,
	p.telefono_fijo=tpd.PerDomTel,
	p.telefono_movil=tpd.PerDomTel2,
	p.fecha_nacimiento=tpd.PerFecNac,
	p.sexo_id=tpd.PerSex,
	p.estado_civil_id=PerEstCivC
where p.cuil is null AND PerEstCivC is not null;


-- Insert de personas de DGE50 que no existian
/*
-- no se insertan estas personas ya que corresponden a servicios antiguos, y puede que no esten vigentes o generar conflictos de duplicidad de informacion.
INSERT INTO cedula.persona 
	(cuil, 
	documento_tipo_id,
	documento,apellido,
	nombre,
	calle,
	calle_numero,
	piso,
	departamento,
	telefono_fijo,
	telefono_movil,
	fecha_nacimiento,
	sexo_id,
	estado_civil_id)
SELECT 
	PerCuil,
	'1',
	SUBSTRING(PerCuil, 4, 8),
	PerApe,
	PerNom,
	PerDomClle,
	PerDomNro, 
	PerDomPiso, 
	PerDomDpto, 
	PerDomTel, 
	PerDomTel2,
	PerFecNac,
	PerSex, 
	CASE WHEN PerEstCivC is null THEN 0 WHEN PerEstCivC='' THEN 0 ELSE PerEstCivC END AS PerEstCivC
FROM 
	cedula_migracion.tmp_persona_dge50 
where 
	SUBSTRING(PerCuil, 4, 8) not in (select documento from cedula.persona)
	AND percuil not in ('27-24932699-8',
'27-25564172-2',
'27-29105444-2');

*/
-- agregando alumnos en tabla persona	
insert into cedula.persona 
(documento_tipo_id, 
documento, 
nombre, 
apellido, 
calle, 
calle_numero,
sexo_id,
estado_civil_id, 
fecha_nacimiento)
select 1 as tipo_doc,
AluDocNum,
AluNombre,
AluApellido,
AluCalle,
AluCalleNro,
AluSexo,
1 as estado_civil,
AluFchNac 
from cedula_migracion.tmp_alumno
where AluDocNum not in (select documento from cedula.persona)GROUP BY AluDocNum;

-- agregar padres en tabla persona
insert into cedula.persona 
(documento_tipo_id, 
documento, 
nombre, 
apellido, 
calle, 
calle_numero,
ocupacion_id,
sexo_id,
estado_civil_id)
select 1 as doc_tipo,aludocpadre,AluNomPadre,' ',AluCalle,AluCalleNro,top.id,1 as sexo, 1 as estado_civil
from cedula_migracion.tmp_alumno ta
LEFT JOIN cedula_migracion.tmp_ocupacion_padres top on top.id = ta.AluOcupaPadre
LEFT JOIN cedula.persona p on ta.aludocpadre = p.documento
WHERE ta.aludocpadre > 99999 AND p.documento is null
GROUP BY ta.aludocpadre;

-- agregar madres en tabla persona
insert into cedula.persona 
(documento_tipo_id, 
documento, 
nombre, 
apellido, 
calle, 
calle_numero,
ocupacion_id,
sexo_id,
estado_civil_id)
select 1 as doc_tipo,AluDocMadre,AluNomMadre,' ',AluCalle,AluCalleNro,top.id,2 as sexo, 1 as estado_civil
from cedula_migracion.tmp_alumno ta
LEFT JOIN cedula_migracion.tmp_ocupacion_padres top on top.id = ta.AluOcupaMadre
LEFT JOIN cedula.persona p on ta.AluDocMadre = p.documento
WHERE ta.AluDocMadre > 99999 AND p.documento is null
GROUP BY ta.AluDocMadre;

-- agregar parentescos en familia
--  MADRES
insert into cedula.familia (persona_id, pariente_id, parentesco_tipo_id)
select a.id, m.id,1
from cedula.persona a
JOIN cedula_migracion.tmp_alumno ta on a.documento = ta.AluDocNum
join cedula.persona m on ta.AluDocMadre = m.documento;

--PADRES
insert into cedula.familia (persona_id, pariente_id, parentesco_tipo_id)
select a.id, p.id,2
from cedula.persona a
JOIN cedula_migracion.tmp_alumno ta on a.documento = ta.AluDocNum
join cedula.persona p on ta.AluDocPadre = p.documento;

--agregar alumnos en tabla alumno
INSERT INTO cedula.alumno (persona_id)
SELECT DISTINCT cp.id 
FROM cedula_migracion.tmp_alumno ta
JOIN cedula.persona cp ON ta.AluDocNum = cp.documento
LEFT JOIN cedula.alumno ca ON cp.id = ca.persona_id
where ca.id is null;

-- Agregamos curso_id en la tabla tmp_alumno
update cedula_migracion.tmp_alumno  ta
JOIN cedula.escuela e ON ta.EscId = e.numero
JOIN cedula_migracion.tmp_alugrados tal ON ta.divanicod = tal.grado_id and e.nivel_id=tal.nivel_id
set ta.curso_id=tal.curso_id

-- Agregando Divisiones de alumnos faltantes
INSERT INTO cedula.division (escuela_id, turno_id, curso_id, division, carrera_id, fecha_alta, fecha_baja)
select /*e.numero, ta.DivAniCod, e.nivel_id, */
	e.id, MIN(ta.turcod), ta.curso_id , ta.divnro, NULL, '2016-01-01 00:00:00',NULL 
from cedula_migracion.tmp_alumno ta
JOIN cedula.escuela e ON ta.escid=e.numero AND ta.EscAnexo = e.numero
LEFT JOIN cedula.division d ON
	REPLACE(REPLACE(REPLACE(REPLACE(d.division,'Â', ''), 'ª',''), '°', ''), 'º', '') = REPLACE(REPLACE(REPLACE(REPLACE(ta.DivNro,'Â', ''), 'ª',''), '°', ''), 'º', '') 
	AND d.curso_id = ta.curso_id 
	AND d.escuela_id = e.id
WHERE DivAniCod is not null AND DivAniCod != '' AND DivAniCod != '0' AND  d.id is null 
AND (e.numero not in ('8448', 'P049' , 'P124') OR divanicod not like 'J%')
 AND ta.curso_id is not NULL /*comentar para ver lo que falta en alugrados*/
group by e.id, divanicod, divnro, curso_id
order by e.id;

-- Creamos tabla temporal para divisiones de alumnos
create table cedula_migracion.tmp_alumno_division as
select ta.AluId, tal.curso_id, d.id as division_id from cedula_migracion.tmp_alumno ta 
JOIN cedula.escuela e ON ta.EscId = e.numero
JOIN cedula_migracion.tmp_alugrados tal ON ta.divanicod = tal.grado_id and e.nivel_id=tal.nivel_id
JOIN cedula.division d ON 
REPLACE(REPLACE(REPLACE(REPLACE(d.division,'Â', ''), 'ª',''), '°', ''), 'º', '') = REPLACE(REPLACE(REPLACE(REPLACE(ta.DivNro,'Â', ''), 'ª',''), '°', ''), 'º', '') AND e.id=d.escuela_id
AND d.curso_id = ta.curso_id
GROUP BY aluid ORDER BY escid;

--agregar divisiones de alumnos en tabla alumno_division

INSERT INTO cedula.alumno_division (alumno_id, division_id, fecha_desde, fecha_hasta, causa_entrada_id, causa_salida_id, estado_id, ciclo_lectivo)
select al.id,d.id, ta.AluFchIng, NULL, tci.gem_id, NULL, 1, '2017'  
from cedula_migracion.tmp_alumno ta
JOIN cedula.persona p ON p.documento = ta.AluDocNum
JOIN cedula.alumno al ON al.persona_id = p.id
JOIN cedula.escuela e ON ta.escid = e.numero
JOIN cedula.division d ON 
REPLACE(REPLACE(REPLACE(REPLACE(d.division,'Â', ''), 'ª',''), '°', ''), 'º', '') =
 REPLACE(REPLACE(REPLACE(REPLACE(ta.DivNro,'Â', ''), 'ª',''), '°', ''), 'º', '') AND e.id=d.escuela_id
AND d.curso_id = ta.curso_id
JOIN cedula_migracion.tmp_causa_ingreso tci ON tci.ing_id = ta.AluCauIngC
order by al.id;

-- ATUALIZAR NULOS EN tmp_alumno_resto 
update cedula_migracion.tmp_alumno_resto tar 
set alusalcod = NULL where alusalcod = '-1'

update cedula_migracion.tmp_alumno_resto tar 
set aludestino = NULL where aludestino = '-1'

-- Actualizar los datos faltantes de alumnos en alumno_division
UPDATE cedula.alumno_division ad
-- select al.id, tcs.gem_id from cedula.alumno_division ad
JOIN cedula.alumno al ON al.id = ad.alumno_id
JOIN cedula.persona p ON p.id = al.persona_id
JOIN cedula_migracion.tmp_alumno ta ON ta.AluDocNum = p.documento
JOIN cedula_migracion.tmp_alumno_resto tar ON tar.aluid = ta.AluId
JOIN cedula_migracion.tmp_causa_salida tcs ON tcs.Col001=tar.alusalcod
SET ad.id = al.id, ad.causa_salida_id = tcs.gem_id;



--agregar cursadas de todos los alumnos segùn espacios_curriculares de cursos de carrera de divisiòn (a futuro, no ahora)

	