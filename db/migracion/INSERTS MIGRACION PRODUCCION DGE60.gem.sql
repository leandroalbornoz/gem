<<<<<<< .mine
-- Archivo con Inserts de migracion a sistema de produccion.

-- Escuelas

INSERT INTO gem.escuela (numero,anexo, cue, nombre, reparticion_id, regional_id, zona_id, anio_resolucion, 
fecha_creacion, numero_resolucion, telefono, email, fecha_cierre, numero_resolucion_cierre, nivel_id,codigo_postal,calle)
SELECT numero_escuela,
			 CASE
           WHEN e.anexo!=e.numero_escuela THEN e.anexo
					 WHEN e.anexo='0' THEN '1'
					 WHEN e.anexo='S' OR e.anexo='P' OR e.anexo='S319' THEN '99'
           ELSE '0'
       END as anexo,
       CASE
           WHEN cue='' THEN NULL
           ELSE cue
       END as cue,
       nombre_escuela,
       r.id as reparticion,
       CASE
           WHEN regional_id='' THEN NULL
					 WHEN regional_id='0' OR regional_id='6' 
					 OR regional_id='7' OR regional_id='8' OR regional_id='9' THEN NULL
           ELSE regional_id
       END as regional_id,
       CASE
           WHEN zona_id='' THEN NULL
					 WHEN zona_id='0' THEN NULL
           ELSE zona_id
       END as zona_id,
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
       niv.id_nivel_gem as nivel_id,
			e.codigo_postal,
			e.domicilio
FROM gem_mig.tmp_escuela e
LEFT JOIN gem.jurisdiccion j ON e.jurisdiccion_id=j.codigo
LEFT JOIN gem.reparticion r ON e.reparticion_id=r.codigo
AND j.id=r.jurisdiccion_id
JOIN gem_mig.nivelgem_nivelmig niv ON e.nivel_id=niv.id_nivel_mig
GROUP BY numero_escuela, anexo;

/*
-- INSERT DE ESCUELA CON NIVEL_ID SEGUN EL NUMERO
INSERT INTO gem.escuela (numero,anexo, cue, nombre, reparticion_id, regional_id, zona_id, anio_resolucion, 
fecha_creacion, numero_resolucion, telefono, email, fecha_cierre, numero_resolucion_cierre, nivel_id,codigo_postal,calle)
SELECT e.numero_escuela,
			 CASE
           WHEN e.anexo!=e.numero_escuela THEN e.anexo
					 WHEN e.anexo='0' THEN '1'
					 WHEN e.anexo='S' OR e.anexo='P' OR e.anexo='S319' THEN '99'
           ELSE '0'
       END as anexo,
       CASE
           WHEN e.cue='' THEN NULL
           ELSE e.cue
       END as cue,
       e.nombre_escuela,
       r.id as reparticion,
       CASE
           WHEN e.regional_id='' THEN NULL
					 WHEN e.regional_id='0' OR e.regional_id='6' 
					 OR e.regional_id='7' OR e.regional_id='8' OR e.regional_id='9' THEN NULL
           ELSE e.regional_id
       END as regional_id,
       CASE
           WHEN e.zona_id='' THEN NULL
					 WHEN e.zona_id='0' THEN NULL
           ELSE e.zona_id
       END as zona_id,
       e.anio_resolucion,
       e.fecha_resolucion,
       e.numero_resolucion,
       e.telefono,
       e.email,
	   CASE
           WHEN e.fecha_cierre = '17530101' THEN NULL
           ELSE e.fecha_cierre
       END AS fecha_cierre,
       e.numero_resolucion_cierre,
       CASE WHEN LEFT(e.numero_escuela,1)='0' THEN '8'
			  WHEN LEFT(e.numero_escuela,1)='1' THEN '2'
			  WHEN LEFT(e.numero_escuela,1)='2' THEN '6'
			  WHEN LEFT(e.numero_escuela,2)='30' THEN '5'
			  WHEN LEFT(e.numero_escuela,1)='3' THEN '9'
			  WHEN LEFT(e.numero_escuela,1)='4' THEN '3'
			  WHEN LEFT(e.numero_escuela,1)='5' THEN '10'
			  WHEN LEFT(e.numero_escuela,1)='6' THEN '12'
			  WHEN LEFT(e.numero_escuela,1)='7' THEN '6'
			  WHEN LEFT(e.numero_escuela,1)='8' THEN '2'
			  WHEN LEFT(e.numero_escuela,1)='9' THEN '2'
				WHEN LEFT(e.numero_escuela,1)='J' THEN '8'
				WHEN LEFT(e.numero_escuela,1)='C' THEN '8'
				WHEN LEFT(e.numero_escuela,1)='P' THEN '2'
				WHEN LEFT(e.numero_escuela,1)='S' THEN '3'
				WHEN LEFT(e.numero_escuela,1)='T' THEN '7'
				WHEN LEFT(e.numero_escuela,1)='I' THEN '7'
			 ELSE '2' END as nivel_id,
			e.codigo_postal,
			e.domicilio
FROM gem_mig.tmp_escuela e
LEFT JOIN gem.jurisdiccion j ON e.jurisdiccion_id=j.codigo
LEFT JOIN gem.reparticion r ON e.reparticion_id=r.codigo
AND j.id=r.jurisdiccion_id
LEFT JOIN gem_mig.nivelgem_nivelmig niv ON e.nivel_id=niv.id_nivel_mig
LEFT JOIN gem.escuela esc ON e.numero_escuela=esc.numero
where esc.nivel_id is null
GROUP BY numero_escuela, anexo;
*/


/*
-- UPDATE DE DOMICILIOS DE TABLERO_ESCUELA
update gem.escuela esc
left JOIN gem_mig.tmp_escuela_tablero esc2 on esc.numero = esc2.EscId 
left JOIN tmp_localidad_distrito tld ON esc2.EscDepId = tld.DepID AND esc2.EscDisId = tld.DisId
set 
esc.nombre = esc2.EscNom,
esc.codigo_postal = esc2.EscCodPos,
esc.localidad_id = tld.id,
esc.calle=esc2.escdom,
esc.telefono=esc2.EscTel,
esc.email = case when esc.email=null then esc2.Escemail else esc.email end
WHERE esc.numero = esc2.escid AND esc2.escid = esc2.EscAnexo
*/
/*
-- UPDATE DATOS TABLERO ESCUELA A GEM.ESCUELA
UPDATE escuela e join gem_mig.tmp_tablero_escuela_may ta ON e.numero=ta.EscId and (e.anexo_migracion=ta.EscAnexo or ta.EscAnexo=ta.EscId)
    set e.cue=ta.CUE,
      e.calle=ta.escdom,
      e.telefono=ta.EscTel,
      e.email=ta.Escemail
where e.audi_fecha is null AND ta.EscId not in ('0000') AND ta.CUE !='';

*/
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
 
-- AGREGAR CURSO_ID A TABLA DE MIGRACION DE DIVISIONES

UPDATE gem_mig.tmp_division td 
JOIN gem.escuela e ON e.numero=td.escid 
JOIN gem_mig.tmp_alugrados ta ON ta.nivel_id=e.nivel_id AND td.curso_id = ta.grado_id
SET td.curso_id2 = ta.curso_id;

-- DIVISIONES

INSERT INTO gem.division (escuela_id, turno_id, curso_id, division, fecha_alta)
SELECT e.id AS escuela_id,
       td.turno_id,
       td.curso_id2,
       td.division,
       '2017-01-01' AS fecha_alta
FROM gem_mig.tmp_division td
JOIN gem.escuela e ON td.escid=e.numero AND e.anexo='0'
LEFT JOIN gem_mig.tmp_alugrados ta ON ta.grado_id = td.curso_id
AND ta.nivel_id=e.nivel_id 
LEFT JOIN gem.division d ON
	REPLACE(REPLACE(REPLACE(REPLACE(d.division,'Â', ''), 'ª',''), '°', ''), 'º', '') = REPLACE(REPLACE(REPLACE(REPLACE(td.division,'Â', ''), 'ª',''), '°', ''), 'º', '') 
	AND d.curso_id = ta.curso_id 
	AND d.escuela_id = e.id
WHERE  d.id is null AND ta.curso_id is not null;

--UPDATE DE SUPERVISIONES

UPDATE gem.supervision s 
join gem_mig.tmp_supervision ts on ts.linea_id=s.linea_id AND ts.orden=s.orden
SET
s.responsable=ts.responsable,
s.nombre=ts.nombre,
s.email=ts.email,
s.blackberry=ts.blackberry,
s.telefono=ts.telefono,
s.regional_id=ts.regional_id
where s.linea_id=8 AND s.orden BETWEEN 1 AND 6  

-- INSERT DE SUPERVISIONES

insert into gem.supervision (responsable,linea_id,nombre,email,orden,blackberry,telefono,sede,regional_id)
select ts.responsable,ts.linea_id,ts.nombre,ts.email,ts.orden,ts.blackberry,ts.telefono,ts.sede,ts.regional_id 
from tmp_supervision ts
LEFT JOIN gem.supervision gs ON gs.linea_id=ts.linea_id AND gs.orden=ts.orden
where gs.id is null;

-- UPDATE SUPERVISIONES EN ESCUELAS

update escuela e set supervision_id=(select id from gem_mig.tmp_supervision where linea='8' AND orden='1') where e.numero='3007';


	
-- CARRERAS

insert into gem.carrera(id,descripcion,fecha_desde,fecha_hasta,nivel_id)
SELECT * FROM cedula.carrera where id not in (66);


-- MATERIAS

insert into gem.materia(id,descripcion,es_grupo,grupo_id,pareja_pedagogica)
select * from gem.materia


-- ESPACIO CURRICULAR

insert into gem.espacio_curricular(id,descripcion,carrera_id,carga_horaria,materia_id,curso_id,fecha_desde,fecha_hasta,grupo_id,resolucion_alta)
select * from cedula.espacio_curricular where carrera_id not in (66)

-- INSERT DE PERSONAS


INSERT INTO gem.persona (cuil, documento_tipo_id, documento, apellido, nombre, fecha_nacimiento, estado_civil_id)
SELECT max(cmp.persona_cuil) AS cuil,
       cmp.documento_tipo_id,
       cmp.documento,
       max(cmp.apellido),
       max(cmp.nombre),
       cmp.fecha_nacimiento,
       1 AS estado_civil_id
FROM gem.gem_tmp_persona_dge55 cmp
LEFT JOIN gem.persona cp ON cp.documento = cmp.documento
where cp.id is NULL
GROUP BY documento;


-- Actualizando CUIL de personas / Actualizando datos de dge50

UPDATE persona SET cuil= CONCAT(SUBSTRING(cuil,1,2), '-', SUBSTRING(cuil,3,8), '-', SUBSTRING(cuil,11,1)) WHERE LOCATE('-',cuil) = 0;

UPDATE gem.persona p
JOIN gem.gem_tmp_servicio_dge50 tpd ON p.cuil = tpd.PerCuil
SET p.cuil=tpd.PerCuil, 
	p.documento_tipo_id='1',
	p.documento=SUBSTRING(tpd.PerCuil,4,8),
	p.apellido=tpd.PerApe,
	p.nombre=tpd.PerNom,
	p.calle=tpd.PerDomClle,
	p.calle_numero=tpd.PerDomNro,
	p.piso=tpd.PerDomPiso,
	p.departamento=tpd.PerDomDpto,
	p.telefono_fijo=tpd.PerDomTel,
	p.telefono_movil=tpd.PerDomTel2,
	p.fecha_nacimiento=tpd.PerFecNac,
	p.sexo_id=CASE WHEN tpd.PerSex='' THEN NULL ELSE tpd.PerSex END,
	p.estado_civil_id=CASE WHEN PerEstCivC='' OR PerEstCivC=0 THEN NULL ELSE PerEstCivC END;

	
-- corroborar usuarios

SELECT * FROM cedula.usuario_persona WHERE cuil NOT IN(SELECT cuil FROM gem.persona)


-- INSERT CARGOS

INSERT INTO gem.cargo (condicion_cargo_id, division_id, espacio_curricular_id, carga_horaria, regimen_id, escuela_id, pof_id)
SELECT condicion_cargo,
       d.id AS division_id,
       NULL,
       carga_horaria,
       reg.id AS reg_salarial_id,
       e.id AS escuela_id,
       c.id AS pof_id
FROM gem.gem_tmp_cargo c
JOIN gem.escuela e ON c.escid=e.numero AND e.anexo = 0
LEFT JOIN gem.regimen reg ON c.regimen_salarial = reg.codigo
LEFT JOIN gem_mig.tmp_alugrados al ON al.grado_id = c.curso_id
AND al.nivel_id = e.nivel_id
LEFT JOIN gem.curso cu ON cu.id = al.curso_id
LEFT JOIN gem.division d ON cu.id=d.curso_id
AND d.escuela_id = e.id  
AND REPLACE(REPLACE(c.division_id, '°', ''), 'º', '')=REPLACE(REPLACE(d.division, '°', ''), 'º', '')
WHERE reg.id IS NOT NULL;

-- CARGO
-- Actualizar espacios curriculares según carreras

UPDATE gem.cargo c
JOIN gem.division d ON c.division_id=d.id
JOIN gem.gem_tmp_cargo tc ON c.pof_id=tc.id
LEFT JOIN cedula_migracion.tmp_materia tm ON tc.materia_nombre=tm.nombre
LEFT JOIN gem.espacio_curricular ec ON d.carrera_id=ec.carrera_id
AND tm.materia_id=ec.materia_id
SET c.espacio_curricular_id=ec.id
WHERE c.espacio_curricular_id IS NULL;

-- INSERT SERVICIO
INSERT INTO gem.servicio (persona_id, cargo_id, fecha_alta, fecha_baja, liquidacion, situacion_revista_id, pof_id, motivo_baja)
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
FROM gem.gem_tmp_servicio_dge55 s
INNER JOIN gem.persona p ON s.documento = p.documento
LEFT JOIN gem.cargo c ON CASE WHEN COALESCE(fila_id,-1)<=0 THEN s.id ELSE fila_id END=c.pof_id
LEFT JOIN gem.gem_tmp_servicio_dge55 s2 ON s.fila_id=s2.id
LEFT JOIN gem.cargo c2 ON CASE WHEN COALESCE(s2.fila_id,-1)<=0 THEN s2.id ELSE s2.fila_id END=c2.pof_id
LEFT JOIN gem.gem_tmp_servicio_dge55 s3 ON s2.fila_id=s3.id
LEFT JOIN gem.cargo c3 ON CASE WHEN COALESCE(s3.fila_id,-1)<=0 THEN s3.id ELSE s3.fila_id END=c3.pof_id

--Buscar Servicios Repetidos
select s.*, s2.*
from servicio s
join servicio s2 on s.id<s2.id and s.pof_id=s2.pof_id
limit 10000;

-- UPDATE SERVICIOS CON TMP_SERVICIOS_DGE50S
UPDATE gem.servicio s
JOIN gem_mig.tmp_servicio_dge50s s50 ON s.liquidacion=s50.srvnroliq
SET s.fecha_alta=s50.SrvAltFch;  

				 
UPDATE gem.servicio s
JOIN gem_mig.tmp_servicio_dge50s s50 ON s.liquidacion=s50.srvnroliq
SET s.fecha_baja=CASE
                     WHEN s50.SrvBajFch='1753-01-01' THEN NULL
                     ELSE s50.SrvBajFch
                 END;

-- UPDATE REEMPLAZOS
UPDATE gem.servicio s
  JOIN gem.gem_tmp_servicio_dge55 ts ON s.pof_id = ts.id AND ts.fila_id >0
JOIN gem.servicio s2 ON ts.fila_id = s2.pof_id
SET s.reemplazado_id = s2.id
where s.reemplazado_id is null;

-- CAMBIAR FECHA BASE A NULL
UPDATE gem.servicio set fecha_alta = null where fecha_alta ='1753-01-01';
UPDATE gem.servicio set fecha_baja = null where fecha_baja='1753-01-01';

 -- SERVICIO_FUNCION
 -- tmp_servicio_funcion
 -- INSERT

INSERT INTO gem.servicio_funcion (servicio_id, detalle, destino, norma, tarea, carga_horaria)
SELECT s.id,
       sf.funcion_detalle,
       sf.funcion_destino,
       sf.funcion_norma,
       sf.funcion_tarea,
       sf.funcion_cargahoraria
FROM gem.gem_tmp_servicio_funcion sf
INNER JOIN gem.servicio s ON sf.id = s.pof_id;

-- Buscar Servicio_funcion repetidos

select sf.*, sf2.*
from servicio_funcion sf
join servicio_funcion sf2 on sf.id<sf2.id AND sf.servicio_id=sf2.servicio_id limit 10000;


 -- CELADORES
 -- tmp_celador
 -- UPDATES
 -- update de tabla servicios donde se cargan los conceptos de celadores

UPDATE gem.servicio s
INNER JOIN
  (SELECT p.id,
          CASE
              WHEN tc.celador_concepto='Bueno' THEN 1
              WHEN tc.celador_concepto='Exelente' THEN 2
              WHEN tc.celador_concepto='Muy Bueno' THEN 3
              ELSE 4
          END AS celador_concepto_id
   FROM gem_mig.tmp_celador_dge55 tc
   INNER JOIN gem.persona p ON tc.documento = p.documento) b ON b.id = s.persona_id
SET s.celador_concepto_id = b.celador_concepto_id ;

 -- update de tabla persona donde se carga el nivel de estudios de los celadores

UPDATE gem.persona p
INNER JOIN
  (SELECT p.id,
          CASE
              WHEN tc.celador_estudios='PRIMARIO' THEN 1
              WHEN tc.celador_estudios='SECUNDARIO' THEN 2
              WHEN tc.celador_estudios='TERCIARIO' THEN 3
              ELSE NULL
          END AS celador_estudios
   FROM gem_mig.tmp_celador_dge55 tc
   INNER JOIN gem.persona p ON tc.documento = p.documento) b ON p.id = b.id
SET p.nivel_estudio_id = b.celador_estudios;


/* 
-- UPDATE ACTUALIZAR DOMICILIOS PERSONAS DE DGE55.DOMICILIO
update gem.persona p
join gem_mig.tmp_domicilio_persona dp on p.documento=dp.documento 
join gem_mig.tmp_localidad_distrito ld on dp.departamento_id = ld.depid and dp.distrito_id=ld.disid
set p.calle = dp.calle,
p.calle_numero = dp.numero,
p.piso = dp.piso,
p.departamento=dp.depto,
p.localidad_id = ld.id;
*/
||||||| .r384
-- Archivo con Inserts de migracion a sistema de produccion.

-- Escuelas

INSERT INTO gem.escuela (numero,anexo, cue, nombre, reparticion_id, regional_id, zona_id, anio_resolucion, 
fecha_creacion, numero_resolucion, telefono, email, fecha_cierre, numero_resolucion_cierre, nivel_id,codigo_postal,calle)
SELECT numero_escuela,
			 CASE
           WHEN e.anexo!=e.numero_escuela THEN e.anexo
					 WHEN e.anexo='0' THEN '1'
					 WHEN e.anexo='S' OR e.anexo='P' OR e.anexo='S319' THEN '99'
           ELSE '0'
       END as anexo,
       CASE
           WHEN cue='' THEN NULL
           ELSE cue
       END as cue,
       nombre_escuela,
       r.id as reparticion,
       CASE
           WHEN regional_id='' THEN NULL
					 WHEN regional_id='0' OR regional_id='6' 
					 OR regional_id='7' OR regional_id='8' OR regional_id='9' THEN NULL
           ELSE regional_id
       END as regional_id,
       CASE
           WHEN zona_id='' THEN NULL
					 WHEN zona_id='0' THEN NULL
           ELSE zona_id
       END as zona_id,
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
       niv.id_nivel_gem as nivel_id,
			e.codigo_postal,
			e.domicilio
FROM gem_mig.tmp_escuela e
LEFT JOIN gem.jurisdiccion j ON e.jurisdiccion_id=j.codigo
LEFT JOIN gem.reparticion r ON e.reparticion_id=r.codigo
AND j.id=r.jurisdiccion_id
JOIN gem_mig.nivelgem_nivelmig niv ON e.nivel_id=niv.id_nivel_mig
GROUP BY numero_escuela, anexo;

/*
-- INSERT DE ESCUELA CON NIVEL_ID SEGUN EL NUMERO
INSERT INTO gem.escuela (numero,anexo, cue, nombre, reparticion_id, regional_id, zona_id, anio_resolucion, 
fecha_creacion, numero_resolucion, telefono, email, fecha_cierre, numero_resolucion_cierre, nivel_id,codigo_postal,calle)
SELECT e.numero_escuela,
			 CASE
           WHEN e.anexo!=e.numero_escuela THEN e.anexo
					 WHEN e.anexo='0' THEN '1'
					 WHEN e.anexo='S' OR e.anexo='P' OR e.anexo='S319' THEN '99'
           ELSE '0'
       END as anexo,
       CASE
           WHEN e.cue='' THEN NULL
           ELSE e.cue
       END as cue,
       e.nombre_escuela,
       r.id as reparticion,
       CASE
           WHEN e.regional_id='' THEN NULL
					 WHEN e.regional_id='0' OR e.regional_id='6' 
					 OR e.regional_id='7' OR e.regional_id='8' OR e.regional_id='9' THEN NULL
           ELSE e.regional_id
       END as regional_id,
       CASE
           WHEN e.zona_id='' THEN NULL
					 WHEN e.zona_id='0' THEN NULL
           ELSE e.zona_id
       END as zona_id,
       e.anio_resolucion,
       e.fecha_resolucion,
       e.numero_resolucion,
       e.telefono,
       e.email,
	   CASE
           WHEN e.fecha_cierre = '17530101' THEN NULL
           ELSE e.fecha_cierre
       END AS fecha_cierre,
       e.numero_resolucion_cierre,
       CASE WHEN LEFT(e.numero_escuela,1)='0' THEN '8'
			  WHEN LEFT(e.numero_escuela,1)='1' THEN '2'
			  WHEN LEFT(e.numero_escuela,1)='2' THEN '6'
			  WHEN LEFT(e.numero_escuela,2)='30' THEN '5'
			  WHEN LEFT(e.numero_escuela,1)='3' THEN '9'
			  WHEN LEFT(e.numero_escuela,1)='4' THEN '3'
			  WHEN LEFT(e.numero_escuela,1)='5' THEN '10'
			  WHEN LEFT(e.numero_escuela,1)='6' THEN '12'
			  WHEN LEFT(e.numero_escuela,1)='7' THEN '6'
			  WHEN LEFT(e.numero_escuela,1)='8' THEN '2'
			  WHEN LEFT(e.numero_escuela,1)='9' THEN '2'
				WHEN LEFT(e.numero_escuela,1)='J' THEN '8'
				WHEN LEFT(e.numero_escuela,1)='C' THEN '8'
				WHEN LEFT(e.numero_escuela,1)='P' THEN '2'
				WHEN LEFT(e.numero_escuela,1)='S' THEN '3'
				WHEN LEFT(e.numero_escuela,1)='T' THEN '7'
				WHEN LEFT(e.numero_escuela,1)='I' THEN '7'
			 ELSE '2' END as nivel_id,
			e.codigo_postal,
			e.domicilio
FROM gem_mig.tmp_escuela e
LEFT JOIN gem.jurisdiccion j ON e.jurisdiccion_id=j.codigo
LEFT JOIN gem.reparticion r ON e.reparticion_id=r.codigo
AND j.id=r.jurisdiccion_id
LEFT JOIN gem_mig.nivelgem_nivelmig niv ON e.nivel_id=niv.id_nivel_mig
LEFT JOIN gem.escuela esc ON e.numero_escuela=esc.numero
where esc.nivel_id is null
GROUP BY numero_escuela, anexo;
*/

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
 
-- AGREGAR CURSO_ID A TABLA DE MIGRACION DE DIVISIONES

UPDATE gem_mig.tmp_division td 
JOIN gem.escuela e ON e.numero=td.escid 
JOIN gem_mig.tmp_alugrados ta ON ta.nivel_id=e.nivel_id AND td.curso_id = ta.grado_id
SET td.curso_id2 = ta.curso_id;

-- DIVISIONES

INSERT INTO gem.division (escuela_id, turno_id, curso_id, division, fecha_alta)
SELECT e.id AS escuela_id,
       td.turno_id,
       td.curso_id2,
       td.division,
       '2017-01-01' AS fecha_alta
FROM gem_mig.tmp_division td
JOIN gem.escuela e ON td.escid=e.numero AND e.anexo='0'
LEFT JOIN gem_mig.tmp_alugrados ta ON ta.grado_id = td.curso_id
AND ta.nivel_id=e.nivel_id 
LEFT JOIN gem.division d ON
	REPLACE(REPLACE(REPLACE(REPLACE(d.division,'Â', ''), 'ª',''), '°', ''), 'º', '') = REPLACE(REPLACE(REPLACE(REPLACE(td.division,'Â', ''), 'ª',''), '°', ''), 'º', '') 
	AND d.curso_id = ta.curso_id 
	AND d.escuela_id = e.id
WHERE  d.id is null AND ta.curso_id is not null;

--UPDATE DE SUPERVISIONES

UPDATE gem.supervision s 
join gem_mig.tmp_supervision ts on ts.linea_id=s.linea_id AND ts.orden=s.orden
SET
s.responsable=ts.responsable,
s.nombre=ts.nombre,
s.email=ts.email,
s.blackberry=ts.blackberry,
s.telefono=ts.telefono,
s.regional_id=ts.regional_id
where s.linea_id=8 AND s.orden BETWEEN 1 AND 6  

-- INSERT DE SUPERVISIONES

insert into gem.supervision (responsable,linea_id,nombre,email,orden,blackberry,telefono,sede,regional_id)
select ts.responsable,ts.linea_id,ts.nombre,ts.email,ts.orden,ts.blackberry,ts.telefono,ts.sede,ts.regional_id 
from tmp_supervision ts
LEFT JOIN gem.supervision gs ON gs.linea_id=ts.linea_id AND gs.orden=ts.orden
where gs.id is null;

-- UPDATE SUPERVISIONES EN ESCUELAS

update escuela e set supervision_id=(select id from gem_mig.tmp_supervision where linea='8' AND orden='1') where e.numero='3007';


	
-- CARRERAS

insert into gem.carrera(id,descripcion,fecha_desde,fecha_hasta,nivel_id)
SELECT * FROM cedula.carrera where id not in (66);


-- MATERIAS

insert into gem.materia(id,descripcion,es_grupo,grupo_id,pareja_pedagogica)
select * from gem.materia


-- ESPACIO CURRICULAR

insert into gem.espacio_curricular(id,descripcion,carrera_id,carga_horaria,materia_id,curso_id,fecha_desde,fecha_hasta,grupo_id,resolucion_alta)
select * from cedula.espacio_curricular where carrera_id not in (66)

=======
-- Archivo con Inserts de migracion a sistema de produccion.

-- Escuelas

INSERT INTO gem.escuela (numero,anexo, cue, nombre, reparticion_id, regional_id, zona_id, anio_resolucion, 
fecha_creacion, numero_resolucion, telefono, email, fecha_cierre, numero_resolucion_cierre, nivel_id,codigo_postal,calle)
SELECT numero_escuela,
			 CASE
           WHEN e.anexo!=e.numero_escuela THEN e.anexo
					 WHEN e.anexo='0' THEN '1'
					 WHEN e.anexo='S' OR e.anexo='P' OR e.anexo='S319' THEN '99'
           ELSE '0'
       END as anexo,
       CASE
           WHEN cue='' THEN NULL
           ELSE cue
       END as cue,
       nombre_escuela,
       r.id as reparticion,
       CASE
           WHEN regional_id='' THEN NULL
					 WHEN regional_id='0' OR regional_id='6' 
					 OR regional_id='7' OR regional_id='8' OR regional_id='9' THEN NULL
           ELSE regional_id
       END as regional_id,
       CASE
           WHEN zona_id='' THEN NULL
					 WHEN zona_id='0' THEN NULL
           ELSE zona_id
       END as zona_id,
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
       niv.id_nivel_gem as nivel_id,
			e.codigo_postal,
			e.domicilio
FROM gem_mig.tmp_escuela e
LEFT JOIN gem.jurisdiccion j ON e.jurisdiccion_id=j.codigo
LEFT JOIN gem.reparticion r ON e.reparticion_id=r.codigo
AND j.id=r.jurisdiccion_id
JOIN gem_mig.nivelgem_nivelmig niv ON e.nivel_id=niv.id_nivel_mig
GROUP BY numero_escuela, anexo;

/*
-- INSERT DE ESCUELA CON NIVEL_ID SEGUN EL NUMERO
INSERT INTO gem.escuela (numero,anexo, cue, nombre, reparticion_id, regional_id, zona_id, anio_resolucion, 
fecha_creacion, numero_resolucion, telefono, email, fecha_cierre, numero_resolucion_cierre, nivel_id,codigo_postal,calle)
SELECT e.numero_escuela,
			 CASE
           WHEN e.anexo!=e.numero_escuela THEN e.anexo
					 WHEN e.anexo='0' THEN '1'
					 WHEN e.anexo='S' OR e.anexo='P' OR e.anexo='S319' THEN '99'
           ELSE '0'
       END as anexo,
       CASE
           WHEN e.cue='' THEN NULL
           ELSE e.cue
       END as cue,
       e.nombre_escuela,
       r.id as reparticion,
       CASE
           WHEN e.regional_id='' THEN NULL
					 WHEN e.regional_id='0' OR e.regional_id='6' 
					 OR e.regional_id='7' OR e.regional_id='8' OR e.regional_id='9' THEN NULL
           ELSE e.regional_id
       END as regional_id,
       CASE
           WHEN e.zona_id='' THEN NULL
					 WHEN e.zona_id='0' THEN NULL
           ELSE e.zona_id
       END as zona_id,
       e.anio_resolucion,
       e.fecha_resolucion,
       e.numero_resolucion,
       e.telefono,
       e.email,
	   CASE
           WHEN e.fecha_cierre = '17530101' THEN NULL
           ELSE e.fecha_cierre
       END AS fecha_cierre,
       e.numero_resolucion_cierre,
       CASE WHEN LEFT(e.numero_escuela,1)='0' THEN '8'
			  WHEN LEFT(e.numero_escuela,1)='1' THEN '2'
			  WHEN LEFT(e.numero_escuela,1)='2' THEN '6'
			  WHEN LEFT(e.numero_escuela,2)='30' THEN '5'
			  WHEN LEFT(e.numero_escuela,1)='3' THEN '9'
			  WHEN LEFT(e.numero_escuela,1)='4' THEN '3'
			  WHEN LEFT(e.numero_escuela,1)='5' THEN '10'
			  WHEN LEFT(e.numero_escuela,1)='6' THEN '12'
			  WHEN LEFT(e.numero_escuela,1)='7' THEN '6'
			  WHEN LEFT(e.numero_escuela,1)='8' THEN '2'
			  WHEN LEFT(e.numero_escuela,1)='9' THEN '2'
				WHEN LEFT(e.numero_escuela,1)='J' THEN '8'
				WHEN LEFT(e.numero_escuela,1)='C' THEN '8'
				WHEN LEFT(e.numero_escuela,1)='P' THEN '2'
				WHEN LEFT(e.numero_escuela,1)='S' THEN '3'
				WHEN LEFT(e.numero_escuela,1)='T' THEN '7'
				WHEN LEFT(e.numero_escuela,1)='I' THEN '7'
			 ELSE '2' END as nivel_id,
			e.codigo_postal,
			e.domicilio
FROM gem_mig.tmp_escuela e
LEFT JOIN gem.jurisdiccion j ON e.jurisdiccion_id=j.codigo
LEFT JOIN gem.reparticion r ON e.reparticion_id=r.codigo
AND j.id=r.jurisdiccion_id
LEFT JOIN gem_mig.nivelgem_nivelmig niv ON e.nivel_id=niv.id_nivel_mig
LEFT JOIN gem.escuela esc ON e.numero_escuela=esc.numero
where esc.nivel_id is null
GROUP BY numero_escuela, anexo;
*/

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
 
-- AGREGAR CURSO_ID A TABLA DE MIGRACION DE DIVISIONES

UPDATE gem_mig.tmp_division td 
JOIN gem.escuela e ON e.numero=td.escid 
JOIN gem_mig.tmp_alugrados ta ON ta.nivel_id=e.nivel_id AND td.curso_id = ta.grado_id
SET td.curso_id2 = ta.curso_id;

-- DIVISIONES

INSERT INTO gem.division (escuela_id, turno_id, curso_id, division, fecha_alta)
SELECT e.id AS escuela_id,
       td.turno_id,
       td.curso_id2,
       td.division,
       '2017-01-01' AS fecha_alta
FROM gem_mig.tmp_division td
JOIN gem.escuela e ON td.escid=e.numero AND e.anexo='0'
LEFT JOIN gem_mig.tmp_alugrados ta ON ta.grado_id = td.curso_id
AND ta.nivel_id=e.nivel_id 
LEFT JOIN gem.division d ON
	REPLACE(REPLACE(REPLACE(REPLACE(d.division,'Â', ''), 'ª',''), '°', ''), 'º', '') = REPLACE(REPLACE(REPLACE(REPLACE(td.division,'Â', ''), 'ª',''), '°', ''), 'º', '') 
	AND d.curso_id = ta.curso_id 
	AND d.escuela_id = e.id
WHERE d.id is null AND ta.curso_id is not null
GROUP BY e.id, td.turno_id, td.curso_id2, td.division;

-- UPDATE DE SUPERVISIONES

UPDATE gem.supervision s 
join gem_mig.tmp_supervision ts on ts.linea_id=s.linea_id AND ts.orden=s.orden
SET
s.responsable=ts.responsable,
s.nombre=ts.nombre,
s.email=ts.email,
s.blackberry=ts.blackberry,
s.telefono=ts.telefono,
s.regional_id=ts.regional_id
where s.linea_id=8 AND s.orden BETWEEN 1 AND 6;

-- INSERT DE SUPERVISIONES

insert into gem.supervision (responsable,linea_id,nombre,email,orden,blackberry,telefono,sede,regional_id)
select ts.responsable,ts.linea_id,ts.nombre,ts.email,ts.orden,ts.blackberry,ts.telefono,ts.sede,ts.regional_id 
from tmp_supervision ts
LEFT JOIN gem.supervision gs ON gs.linea_id=ts.linea_id AND gs.orden=ts.orden
where gs.id is null;

-- UPDATE SUPERVISIONES EN ESCUELAS

update escuela e set supervision_id=(select id from gem_mig.tmp_supervision where linea='8' AND orden='1') where e.numero='3007';


-- CARRERAS

insert into gem.carrera(id,descripcion,fecha_desde,fecha_hasta,nivel_id)
SELECT * FROM cedula.carrera where id not in (66);


-- MATERIAS

insert into gem.materia(id,descripcion,es_grupo,grupo_id,pareja_pedagogica)
select * from gem.materia;


-- ESPACIO CURRICULAR

insert into gem.espacio_curricular(id,descripcion,carrera_id,carga_horaria,materia_id,curso_id,fecha_desde,fecha_hasta,grupo_id,resolucion_alta)
select * from cedula.espacio_curricular where carrera_id not in (66);

-- ESCUELA_CARRERA
-- Agregando carreras a escuelas que tienen niveles con 1 sola carrera
INSERT INTO escuela_carrera (escuela_id, carrera_id) SELECT id, 26 FROM escuela WHERE nivel_id=2;
INSERT INTO escuela_carrera (escuela_id, carrera_id) SELECT id, 27 FROM escuela WHERE nivel_id=5;
INSERT INTO escuela_carrera (escuela_id, carrera_id) SELECT id, 1 FROM escuela WHERE nivel_id=8;
INSERT INTO escuela_carrera (escuela_id, carrera_id) SELECT id, 67 FROM escuela WHERE nivel_id=12;
INSERT INTO escuela_carrera (escuela_id, carrera_id) SELECT id, 68 FROM escuela WHERE nivel_id=3;

-- DIVISION
-- Agregando divisiones de DGE04.pregase que no estaban incluidas en dge55.pof
INSERT INTO gem.division (escuela_id, turno_id, curso_id, division, fecha_alta)
SELECT e.id AS escuela_id,
       td.TurCod,
       ta.curso_id,
       td.DivNro,
       '2017-01-01' AS fecha_alta
FROM gem.gem_tmp_division_dge04 td
JOIN gem.escuela e ON td.EscId=e.numero AND e.anexo=0
LEFT JOIN gem_mig.tmp_alugrados ta ON ta.grado_id = td.DivAniCod AND ta.nivel_id=e.nivel_id 
LEFT JOIN gem.division d ON
	REPLACE(REPLACE(REPLACE(REPLACE(d.division,'Â', ''), 'ª',''), '°', ''), 'º', '') = REPLACE(REPLACE(REPLACE(REPLACE(td.DivNro,'Â', ''), 'ª',''), '°', ''), 'º', '') 
	AND d.curso_id = ta.curso_id 
	AND d.escuela_id = e.id
WHERE  d.id is null AND ta.curso_id is not null;

-- DIVISION
-- Actualizando carreras de divisiones que tienen escuelas con 1 sola carrera
UPDATE division SET carrera_id = 26 WHERE escuela_id IN (SELECT id FROM escuela WHERE nivel_id=2);
UPDATE division SET carrera_id = 27 WHERE escuela_id IN (SELECT id FROM escuela WHERE nivel_id=5);
UPDATE division SET carrera_id = 1 WHERE escuela_id IN (SELECT id FROM escuela WHERE nivel_id=8);
UPDATE division SET carrera_id = 67 WHERE escuela_id IN (SELECT id FROM escuela WHERE nivel_id=12);
UPDATE division SET carrera_id = 68 WHERE escuela_id IN (SELECT id FROM escuela WHERE nivel_id=3) and curso_id IN (1,2,3); -- Sec. Orientada - cursos 1°,2°,3°

-- INSERT DE PERSONAS


INSERT INTO gem.persona (cuil, documento_tipo_id, documento, apellido, nombre, fecha_nacimiento, estado_civil_id)
SELECT max(cmp.persona_cuil) AS cuil,
       cmp.documento_tipo_id,
       cmp.documento,
       max(cmp.apellido),
       max(cmp.nombre),
       cmp.fecha_nacimiento,
       1 AS estado_civil_id
FROM gem.gem_tmp_persona_dge55 cmp
LEFT JOIN gem.persona cp ON cp.documento = cmp.documento
where cp.id is NULL
GROUP BY documento;


-- Actualizando CUIL de personas / Actualizando datos de dge50

UPDATE persona SET cuil= CONCAT(SUBSTRING(cuil,1,2), '-', SUBSTRING(cuil,3,8), '-', SUBSTRING(cuil,11,1)) WHERE LOCATE('-',cuil) = 0;

UPDATE gem.persona p
JOIN gem.gem_tmp_servicio_dge50 tpd ON p.cuil = tpd.PerCuil
SET p.cuil=tpd.PerCuil, 
	p.documento_tipo_id='1',
	p.documento=SUBSTRING(tpd.PerCuil,4,8),
	p.apellido=tpd.PerApe,
	p.nombre=tpd.PerNom,
	p.calle=tpd.PerDomClle,
	p.calle_numero=tpd.PerDomNro,
	p.piso=tpd.PerDomPiso,
	p.departamento=tpd.PerDomDpto,
	p.telefono_fijo=tpd.PerDomTel,
	p.telefono_movil=tpd.PerDomTel2,
	p.fecha_nacimiento=tpd.PerFecNac,
	p.sexo_id=CASE WHEN tpd.PerSex='' THEN NULL ELSE tpd.PerSex END,
	p.estado_civil_id=CASE WHEN PerEstCivC='' OR PerEstCivC=0 THEN NULL ELSE PerEstCivC END;

	
-- corroborar usuarios

SELECT * FROM cedula.usuario_persona WHERE cuil NOT IN(SELECT cuil FROM gem.persona)


-- INSERT CARGOS

INSERT INTO gem.cargo (condicion_cargo_id, division_id, espacio_curricular_id, carga_horaria, regimen_id, escuela_id, pof_id)
SELECT condicion_cargo,
       d.id AS division_id,
       NULL,
       carga_horaria,
       reg.id AS reg_salarial_id,
       e.id AS escuela_id,
       c.id AS pof_id
FROM gem.gem_tmp_cargo c
JOIN gem.escuela e ON c.escid=e.numero AND e.anexo = 0
LEFT JOIN gem.regimen reg ON c.regimen_salarial = reg.codigo
LEFT JOIN gem_mig.tmp_alugrados al ON al.grado_id = c.curso_id
AND al.nivel_id = e.nivel_id
LEFT JOIN gem.curso cu ON cu.id = al.curso_id
LEFT JOIN gem.division d ON cu.id=d.curso_id
AND d.escuela_id = e.id  
AND REPLACE(REPLACE(c.division_id, '°', ''), 'º', '')=REPLACE(REPLACE(d.division, '°', ''), 'º', '')
WHERE reg.id IS NOT NULL;

-- CARGO
-- Actualizar espacios curriculares según carreras

UPDATE gem.cargo c
JOIN gem.division d ON c.division_id=d.id
JOIN gem.gem_tmp_cargo tc ON c.pof_id=tc.id
LEFT JOIN cedula_migracion.tmp_materia tm ON tc.materia_nombre=tm.nombre
LEFT JOIN gem.espacio_curricular ec ON d.carrera_id=ec.carrera_id
AND tm.materia_id=ec.materia_id
SET c.espacio_curricular_id=ec.id
WHERE c.espacio_curricular_id IS NULL;

-- INSERT SERVICIO
INSERT INTO gem.servicio (persona_id, cargo_id, fecha_alta, fecha_baja, liquidacion, situacion_revista_id, pof_id, motivo_baja)
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
FROM gem.gem_tmp_servicio_dge55 s
INNER JOIN gem.persona p ON s.documento = p.documento
LEFT JOIN gem.cargo c ON CASE WHEN COALESCE(fila_id,-1)<=0 THEN s.id ELSE fila_id END=c.pof_id
LEFT JOIN gem.gem_tmp_servicio_dge55 s2 ON s.fila_id=s2.id
LEFT JOIN gem.cargo c2 ON CASE WHEN COALESCE(s2.fila_id,-1)<=0 THEN s2.id ELSE s2.fila_id END=c2.pof_id
LEFT JOIN gem.gem_tmp_servicio_dge55 s3 ON s2.fila_id=s3.id
LEFT JOIN gem.cargo c3 ON CASE WHEN COALESCE(s3.fila_id,-1)<=0 THEN s3.id ELSE s3.fila_id END=c3.pof_id

--Buscar Servicios Repetidos
select s.*, s2.*
from servicio s
join servicio s2 on s.id<s2.id and s.pof_id=s2.pof_id
limit 10000;

-- UPDATE SERVICIOS CON TMP_SERVICIOS_DGE50S
UPDATE gem.servicio s
JOIN gem_mig.tmp_servicio_dge50s s50 ON s.liquidacion=s50.srvnroliq
SET s.fecha_alta=s50.SrvAltFch;  

				 
UPDATE gem.servicio s
JOIN gem_mig.tmp_servicio_dge50s s50 ON s.liquidacion=s50.srvnroliq
SET s.fecha_baja=CASE
                     WHEN s50.SrvBajFch='1753-01-01' THEN NULL
                     ELSE s50.SrvBajFch
                 END;

-- UPDATE REEMPLAZOS
UPDATE gem.servicio s
  JOIN gem.gem_tmp_servicio_dge55 ts ON s.pof_id = ts.id AND ts.fila_id >0
JOIN gem.servicio s2 ON ts.fila_id = s2.pof_id
SET s.reemplazado_id = s2.id
where s.reemplazado_id is null;

-- CAMBIAR FECHA BASE A NULL
UPDATE gem.servicio set fecha_alta = null where fecha_alta ='1753-01-01';
UPDATE gem.servicio set fecha_baja = null where fecha_baja='1753-01-01';

 -- SERVICIO_FUNCION
 -- tmp_servicio_funcion
 -- INSERT

INSERT INTO gem.servicio_funcion (servicio_id, detalle, destino, norma, tarea, carga_horaria)
SELECT s.id,
       sf.funcion_detalle,
       sf.funcion_destino,
       sf.funcion_norma,
       sf.funcion_tarea,
       sf.funcion_cargahoraria
FROM gem.gem_tmp_servicio_funcion sf
INNER JOIN gem.servicio s ON sf.id = s.pof_id;

-- Buscar Servicio_funcion repetidos

select sf.*, sf2.*
from servicio_funcion sf
join servicio_funcion sf2 on sf.id<sf2.id AND sf.servicio_id=sf2.servicio_id limit 10000;


 -- CELADORES
 -- tmp_celador
 -- UPDATES
 -- update de tabla servicios donde se cargan los conceptos de celadores

UPDATE gem.servicio s
INNER JOIN
  (SELECT p.id,
          CASE
              WHEN tc.celador_concepto='Bueno' THEN 1
              WHEN tc.celador_concepto='Exelente' THEN 2
              WHEN tc.celador_concepto='Muy Bueno' THEN 3
              ELSE 4
          END AS celador_concepto_id
   FROM gem_mig.tmp_celador_dge55 tc
   INNER JOIN gem.persona p ON tc.documento = p.documento) b ON b.id = s.persona_id
SET s.celador_concepto_id = b.celador_concepto_id ;

 -- update de tabla persona donde se carga el nivel de estudios de los celadores

UPDATE gem.persona p
INNER JOIN
  (SELECT p.id,
          CASE
              WHEN tc.celador_estudios='PRIMARIO' THEN 1
              WHEN tc.celador_estudios='SECUNDARIO' THEN 2
              WHEN tc.celador_estudios='TERCIARIO' THEN 3
              ELSE NULL
          END AS celador_estudios
   FROM gem_mig.tmp_celador_dge55 tc
   INNER JOIN gem.persona p ON tc.documento = p.documento) b ON p.id = b.id
SET p.nivel_estudio_id = b.celador_estudios;


/* 
-- UPDATE ACTUALIZAR DOMICILIOS PERSONAS DE DGE55.DOMICILIO
update gem.persona p
join gem_mig.tmp_domicilio_persona dp on p.documento=dp.documento 
join gem_mig.tmp_localidad_distrito ld on dp.departamento_id = ld.depid and dp.distrito_id=ld.disid
set p.calle = dp.calle,
p.calle_numero = dp.numero,
p.piso = dp.piso,
p.departamento=dp.depto,
p.localidad_id = ld.id;
*/


-- ALUMNOS 

-- insert de alumnos en tabla persona persona 

insert into gem.persona 
(documento_tipo_id, 
documento, 
nombre, 
apellido, 
calle, 
calle_numero,
sexo_id,
estado_civil_id, 
fecha_nacimiento)
select 
dt.id_doc_tipo,
AluDocNum,
AluNombre,
AluApellido,
AluCalle,
AluCalleNro,
case when alusexo=-1 then 1 else AluSexo end as alusexo,
1 as estado_civil,
AluFchNac 
from gem_mig.tmp_alumno ta
join gem_mig.tmp_doc_tipo dt on ta.aludoccod=dt.docid
left join gem.persona p on p.documento_tipo_id=dt.id_doc_tipo AND p.documento=ta.AluDocNum 
where p.id is null and aludocnum not in (99,-1) AND (alunombre <> '' or AluApellido <>'')GROUP BY AluDocNum, dt.id_doc_tipo ;


-- insert de alumnos en tabla alumno

INSERT INTO gem.alumno (persona_id, 
documento_padre,
ocupacion_padre,
nombre_padre,
documento_madre,
ocupacion_madre,
nombre_madre)
SELECT p.id,
AluDocPadre,
AluOcupaPadre,
AluNomPadre,
AluDocMadre,
AluOcupaMadre,
AluNomMadre 
FROM gem_mig.tmp_alumno ta
JOIN gem.persona p ON ta.AluDocNum = p.documento and p.documento_tipo_id =1
LEFT JOIN cedula.alumno a ON p.id = a.persona_id
where a.id is null GROUP BY p.id;


-- insert de padres a tabla persona

insert into gem.persona 
(documento_tipo_id, 
documento, 
nombre, 
apellido, 
calle, 
calle_numero,
ocupacion_id,
sexo_id,
estado_civil_id)
select 
1 as doc_tipo,
aludocpadre,
AluNomPadre,
' ',
AluCalle,
AluCalleNro,
top.id,
1 as sexo,
 1 as estado_civil
from gem_mig.tmp_alumno ta
LEFT JOIN gem_mig.tmp_ocupacion_padres top on top.id = ta.AluOcupaPadre
LEFT JOIN gem.persona p on ta.aludocpadre = p.documento AND p.documento_tipo_id=1
WHERE ta.aludocpadre > 99999 AND p.documento is null 
and AluDocPadre not in (111111,1111111,999999)
GROUP BY ta.aludocpadre;


-- insert de madres a tabla persona

insert into gem.persona 
(documento_tipo_id, 
documento, 
nombre, 
apellido, 
calle, 
calle_numero,
ocupacion_id,
sexo_id,
estado_civil_id)
select 1 as doc_tipo,
AluDocMadre,
AluNomMadre,
' ',
AluCalle,
AluCalleNro,
top.id,
2 as sexo,
1 as estado_civil
from gem_mig.tmp_alumno ta
LEFT JOIN gem_mig.tmp_ocupacion_padres top on top.id = ta.AluOcupaMadre 
LEFT JOIN gem.persona p on ta.AluDocMadre = p.documento AND p.documento_tipo_id=1
WHERE ta.AluDocMadre > 99999 AND p.documento is null 
AND AluDocMadre not in (111111,1111111,999999) 
GROUP BY AluDocMadre;

-- insert parentesco padres

insert into gem.familia (persona_id, pariente_id, parentesco_tipo_id)
select a.id, m.id,2
from gem.persona a
JOIN gem_mig.tmp_alumno ta on a.documento = ta.AluDocNum 
join gem.persona m on ta.AluDocPadre = m.documento and m.documento_tipo_id=1
where ta.AluDocMadre <>0 AND a.documento<>0 and m.documento <>0
group by a.id;


-- insert parentesco madres

insert into gem.familia (persona_id, pariente_id, parentesco_tipo_id)
select a.id, m.id,1
from gem.persona a
JOIN gem_mig.tmp_alumno ta on a.documento = ta.AluDocNum 
join gem.persona m on ta.AluDocMadre = m.documento and m.documento_tipo_id=1
where ta.AluDocMadre <>0 AND a.documento<>0 and m.documento <>0
group by a.id;


-- UPDATE DOMICILIOS DE PERSONAS DESDE DGE55.domicilio en tmp_domicilio_dge55


select d.* from gem.persona p
join gem_mig.tmp_domicilio_dge55 d  on SUBSTR(p.cuil from 1 FOR 11) = d.cuil
join gem_mig.tmp_localidad_distrito l 
on d.distrito_id = l.DisId and d.departamento_id = l.DepId
where d.fecha_carga > p.audi_fecha or audi_fecha is null;


update persona p 
join gem_mig.tmp_domicilio_dge55 d on SUBSTR(p.cuil from 1 FOR 11) = d.cuil
join gem_mig.tmp_localidad_distrito l 
on d.distrito_id = l.DisId and d.departamento_id = l.DepId
set
p.calle=d.calle,
p.calle_numero=d.numero,
p.piso=d.piso,
p.departamento=d.depto,
p.localidad_id=l.id,
p.telefono_fijo=d.telefono,
p.telefono_movil=d.celular,
p.prestadora_id=case when d.prestadora_id=0 then null else d.prestadora_id end,
p.email=d.email
where d.fecha_carga > p.audi_fecha or audi_fecha is null;



-- INSERT CARACTERISTICAS ESCUELAS 


-- ESCUELA FRONTERA
insert into caracteristica_escuela (escuela_id,caracteristica_id,valor,fecha_desde,caracteristica_valor_id)
select e.id as escuela_id,43 as caracteristica_id,
CASE 
WHEN tc.escfro='N' THEN 'No' 
WHEN tc.escfro='S' THEN 'Si'
when tc.escfro='1' THEN 'No'
when tc.escfro='2' THEN 'Si'
when tc.escfro='' THEN null
else null end as valor,
CURDATE() as fecha,
CASE 
WHEN tc.escfro='N' THEN '62' 
WHEN tc.escfro='S' THEN '61'
when tc.escfro='1' THEN '62'
when tc.escfro='2' THEN '61'
when tc.escfro='' THEN null
else null end as 'valor_id'
from gem_mig.tmp_caracteristica_escuela tc 
join escuela e on tc.escid=e.numero
where tc.escfro is not null AND tc.escfro <> '';


-- EDIFICIO COMPARTIDO
insert into caracteristica_escuela (escuela_id,caracteristica_id,valor,fecha_desde,caracteristica_valor_id)
select e.id as escuela_id,38 as caracteristica_id,
CASE 
WHEN tc.escegbcomp='N' THEN 'No' 
WHEN tc.escegbcomp='S' THEN 'Si'
when tc.escegbcomp='' THEN null
else null end as valor,
CURDATE() as fecha,
CASE 
WHEN tc.escegbcomp='N' THEN '500' 
WHEN tc.escegbcomp='S' THEN '499'
when tc.escegbcomp='' THEN null
else null end as 'valor_id'
from gem_mig.tmp_caracteristica_escuela tc 
join escuela e on tc.escid=e.numero
where tc.escfro is not null AND tc.escfro <> '';


-- RAMPA DISCAPACITADOS
insert into caracteristica_escuela (escuela_id,caracteristica_id,valor,fecha_desde,caracteristica_valor_id)
select e.id as escuela_id,63 as caracteristica_id,
CASE 
 when tc.rampa='0' THEN 'No'
 when tc.rampa='1' THEN 'Si'
 when tc.rampa='' THEN null
else null end as valor,
CURDATE() as fecha,
CASE 
 when tc.rampa='0' THEN '155'
 when tc.rampa='1' THEN '154'
 when tc.rampa='' THEN null
else null end as 'valor_id'
from gem_mig.tmp_caracteristica_escuela tc 
join escuela e on tc.escid=e.numero
where tc.rampa is not null AND tc.rampa <> '';


-- ALARMA
insert into caracteristica_escuela (escuela_id,caracteristica_id,valor,fecha_desde,caracteristica_valor_id)
select e.id as escuela_id,69 as caracteristica_id,
CASE 
 when tc.alarma='0' THEN 'No'
 when tc.alarma='1' THEN 'Si'
 when tc.alarma='' THEN null
else null end as valor,
CURDATE() as fecha,
CASE 
 when tc.alarma='0' THEN '252'
 when tc.alarma='1' THEN '251'
 when tc.alarma='' THEN null
else null end as 'valor_id'
from gem_mig.tmp_caracteristica_escuela tc 
join escuela e on tc.escid=e.numero
where tc.alarma is not null AND tc.alarma <> '';


-- CALDERA
insert into caracteristica_escuela (escuela_id,caracteristica_id,valor,fecha_desde,caracteristica_valor_id)
select e.id as escuela_id,66 as caracteristica_id,
CASE 
 when tc.caldera='0' THEN 'No'
 when tc.caldera='1' THEN 'Si'
else null end as valor,
CURDATE() as fecha,
CASE 
 when tc.caldera='0' THEN '160'
 when tc.caldera='1' THEN '161'
else null end as 'valor_id'
from gem_mig.tmp_caracteristica_escuela tc 
join escuela e on tc.escid=e.numero
where tc.caldera is not null;


-- CIERRE PERIMETRAL
insert into caracteristica_escuela (escuela_id,caracteristica_id,valor,fecha_desde,caracteristica_valor_id)
select e.id as escuela_id,65 as caracteristica_id,
CASE 
 when tc.cierre='0' THEN 'No'
 when tc.cierre='1' THEN 'Si'

else null end as valor,
CURDATE() as fecha,
CASE 
 when tc.cierre='0' THEN '159'
 when tc.cierre='1' THEN '158'

else null end as 'valor_id'
from gem_mig.tmp_caracteristica_escuela tc 
join escuela e on tc.escid=e.numero
where tc.cierre is not null;


-- CANTIDAD DE AULAS
insert into caracteristica_escuela (escuela_id,caracteristica_id,valor,fecha_desde,caracteristica_valor_id)
select e.id as escuela_id,64 as caracteristica_id,
tc.cantaulas valor,
CURDATE() as fecha,
156 as valor_id
from gem_mig.tmp_caracteristica_escuela tc 
join escuela e on tc.escid=e.numero
where tc.cierre is not null 

