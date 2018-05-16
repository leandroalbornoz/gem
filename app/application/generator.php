<?php

// <editor-fold defaultstate="collapsed" desc="Opciones G.E.M. (Activada)">
/**/
// php generator.php
$SITE_TITLE = 'G.E.M.';
$MODULES_ENABLED = TRUE;
$MODULE_TITLE = "Gestión Escuelas Mendoza";
$MODULE_PATH = "abono_escuela_monto";
$DB_SERVER = 'mysql';
$DB_HOST = '192.168.31.49'; // localhost
$DB_SCHEMA = "cedula";
$DB_USER = "";
$DB_PASSWORD = "";
$TBL_PREFIX = '';
$TABLES = [//table=>[col_desc='descripcion', n_name='', 1_name='', género='', navroute='par', var_1_name='default', modal=false]
	/*
	  SELECT CONCAT("'",c.TABLE_NAME,"' => ['", c.COLUMN_NAME,"', '", UPPER(LEFT(c.TABLE_NAME,1)),SUBSTR(c.TABLE_NAME,2),'s',"', '", UPPER(LEFT(c.TABLE_NAME,1)),SUBSTR(c.TABLE_NAME,2), "', '', 'menu', '", c.TABLE_NAME, "'],")
	  FROM information_schema.columns c
	  WHERE c.TABLE_SCHEMA='primavera' AND c.ORDINAL_POSITION=2;
	 */
	'abono_escuela_monto' => ['id', 'Abono Escuela Monto', 'Abono Escuela Montos', '', 'par', 'abono_escuela_monto', true],
	'abono_alumno' => ['id', 'Abono Escuela', 'Abono Escuelas', '', 'par', 'abono_escuela', false],
	'tipo_abono' => ['id', 'Tipo Abono', 'Tipo Abono', '', 'par', 'tipo_abono', false],
	'tem_proyecto_escuela' => ['proyecto_id', 'Escuelas TEM', 'Escuela TEM', '', 'par', 'proyecto_escuela', false],
	'tem_proyecto_escuela' => ['proyecto_id', 'Escuelas TEM', 'Escuela TEM', '', 'par', 'proyecto_escuela', false],
	'tem_proyecto' => ['descripcion', 'Proyectos TEM', 'Proyecto TEM', '', 'par', 'proyecto', false],
	'tem_alumno' => ['descripcion', 'Alumnos TEM', 'Alumno TEM', '', 'par', 'alumno', false],
	'tem_mes_semana' => ['proyecto_id', 'Meses/Semanas TEM', 'Mes/Semana TEM', '', 'par', 'mes_semana', false],
	'escuela_grupo' => ['descripcion', 'Grupos de Escuelas', 'Grupo de escuela', '', 'par', 'escuela_grupo', false],
	'escuela_grupo_escuela' => ['id', 'Escuelas de Grupos de Escuelas', 'Escuela de Grupo de escuela', '', 'par', 'escuela_grupo_escuela', false],
	'alumno_derivacion' => ['alumno_id', 'Derivaciones Hospitalarias/Domiciliarias', 'Derivación Hospitalaria/Domiciliaria', '', 'par', 'alumno_derivacion', true],
	'diagnostico' => ['descripcion', 'Diagnósticos', 'Diagnóstico', '', 'par', 'diagnostico', true],
	'anuncio' => ['titulo', 'Anuncios', 'Anuncio', '', 'menu', 'anuncio', false],
	'calendario' => ['descripcion', 'Calendarios', 'Calendario', '', 'menu', 'calendario', false],
	'calendario_periodo' => ['periodo', 'Periodos de calendario', 'Periodo de calendario', '', 'menu', 'calendario_periodo', false],
	'suple' => ['id', 'Suplementarias', 'Suplementaria', 'a', 'suplementaria', 'suple'],
	'suple_estado' => ['id', 'Estados', 'Estado', 'a', 'suplementaria', 'suplem_estado'],
	'suple_persona' => ['id', 'Personas', 'Persona', 'a', 'suplementaria', 'suple_persona'],
	'suple_persona_auditoria' => ['id', 'Auditorías persona', 'Auditoría persona', 'a', 'suplementaria', 'suple_persona_auditoria'],
	'suple_persona_concepto' => ['id', 'Concepto persona', 'Concepto persona', 'a', 'suplementaria', 'suple_persona_concepto'],
	'suple_concepto' => ['id', 'Conceptos', 'Concepto', '', 'suplementaria', 'suple_concepto'],
	'alumno' => ['persona_id', 'Alumnos', 'Alumno', '', 'menu', 'alumno', false],
	'alumno_division' => ['alumno_id', 'Divisiones de alumnos', 'División de alumno', '', 'menu', 'alumno_division', false],
	'alumno_inasistencia' => ['cursada_id', 'Inasistencias de alumnos', 'Inasistencia de alumno', 'a', 'menu', 'alumno_inasistencia', false],
	'autoridad_tipo' => ['descripcion', 'Tipos de autoridades', 'Tipo de autoridad', '', 'par', 'tipo_autoridad', true],
	'caracteristica_alumno' => ['alumno_id', 'Características de alumnos', 'Característica de alumno', 'a', 'menu', 'caracteristica_alumno', false],
	'caracteristica_escuela' => ['escuela_id', 'Características de escuelas', 'Característica de escuela', 'a', 'menu', 'caracteristica_escuela', false],
	'caracteristica_supervision' => ['supervision_id', 'Características de supervisiones', 'Característica de supervision', 'a', 'menu', 'caracteristica_supervision', false],
	'caracteristica_nivel' => ['caracteristica_id', 'Niveles de características', 'Nivel de característica', '', 'menu', 'caracteristica_nivel', false],
	'caracteristica_valor' => ['caracteristica_id', 'Valores de características', 'Valor de característica', '', 'menu', 'caracteristica_valor', false],
	'cargo' => ['condicion_cargo_id', 'Cargos', 'Cargo', '', 'menu', 'cargo', false],
	'celador_concepto' => ['descripcion', 'Conceptos de celadores', 'Concepto de celador', '', 'menu', 'celador_concepto', true],
	'condicion_cargo' => ['descripcion', 'Condiciones de cargos', 'Condición de cargo', 'a', 'menu', 'condicion_cargo', true],
	'cursada' => ['alumno_id', 'Cursadas', 'Cursada', 'a', 'menu', 'cursada', false],
	'cursada_nota' => ['cursada_id', 'Notas de cursadas', 'Nota de cursada', 'a', 'menu', 'cursada_nota', false],
	'division' => ['escuela_id', 'Divisiones', 'División', 'a', 'menu', 'division', false],
	'escuela' => ['nombre', 'Escuelas', 'Escuela', 'a', 'menu', 'escuela', false],
	'escuela_carrera' => ['escuela_id', 'Carreras de escuelas', 'Carrera de escuela', 'a', 'menu', 'escuela_carrera', false],
	'espacio_curricular' => ['descripcion', 'Espacios curriculares', 'Espacio curricular', '', 'menu', 'espacio_curricular', false],
	'evaluacion' => ['evaluacion_tipo_id', 'Evaluaciones', 'Evaluación', 'a', 'menu', 'evaluacion', false],
	'familia' => ['persona_id', 'Familias', 'Familia', 'a', 'menu', 'familia', false],
	'horario' => ['hora_catedra', 'Horarios', 'Horario', '', 'menu', 'horario', false],
	'mensaje' => ['asunto', 'Mensajes', 'Mensaje', '', 'menu', 'mensaje', true],
	'permiso' => ['nombre', 'Permisos', 'Permiso', '', 'menu', 'permiso', true],
	'persona' => ['cuil', 'Personas', 'Persona', 'a', 'menu', 'persona', false],
	'pregunta' => ['pregunta', 'Preguntas', 'Pregunta', '', 'ayuda', 'pregunta', true],
	'regimen' => ['codigo', 'Regímenes', 'Régimen', '', 'menu', 'regimen', true],
	'rol_permiso' => ['rol_id', 'Permisos de roles', 'Permiso de rol', '', 'menu', 'rol_permiso', false],
	'servicio' => ['persona_id', 'Servicios', 'Servicio', '', 'menu', 'servicio', false],
	'servicio_funcion' => ['detalle', 'Funciones de servicios', 'Función de servicio', 'a', 'menu', 'servicio_funcion', true],
	'servicio_novedad' => ['servicio_id', 'Novedades de servicios', 'Novedad de servicio', 'a', 'menu', 'servicio_novedad', true],
	'servicio_tolerancia' => ['servicio_id', 'Tolerancias de servicios', 'Tolerancia de servicio', 'a', 'menu', 'servicio_tolerancia', false],
	'sexo' => ['descripcion', 'Sexos', 'Sexo', '', 'menu', 'sexo', true],
	'usuario' => ['persona_id', 'Usuarios', 'Usuario', '', 'menu', 'usuario', false],
	'usuario_rol' => ['usuario_id', 'Roles de usuarios', 'Rol de usuario', '', 'menu', 'usuario_rol', false],
	'usuario_rol_parametro' => ['parametro_id', 'Parámetros de roles de usuarios', 'Parámetro de rol de usuario', '', 'menu', 'usuario_rol_parametro', false],
	'area' => ['descripcion', 'Áreas', 'Área', '', 'par', 'area', false],
	'caracteristica' => ['descripcion', 'Caracteristicas', 'Caracteristica', 'a', 'par', 'caracteristica', true],
	'caracteristica_tipo' => ['descripcion', 'Tipos de características', 'Tipo de característica', '', 'par', 'tipo_caracteristica', true],
	'carrera' => ['descripcion', 'Carreras', 'Carrera', 'a', 'par', 'carrera', false],
	'causa_entrada' => ['descripcion', 'Causas de entradas', 'Causa de entrada', 'a', 'par', 'causa_entrada', true],
	'causa_salida' => ['descripcion', 'Causas de salidas', 'Causa de salida', 'a', 'par', 'causa_salida', true],
	'curso' => ['descripcion', 'Cursos', 'Curso', '', 'par', 'curso', true],
	'departamento' => ['descripcion', 'Departamentos', 'Departamento', '', 'par', 'departamento', true],
	'dependencia' => ['descripcion', 'Dependencias', 'Dependencia', 'a', 'par', 'dependencia', true],
	'dia' => ['nombre', 'Dias', 'Día', '', 'par', 'dia', true],
	'documento_tipo' => ['descripcion_corta', 'Tipos de documentos', 'Tipo de documento', '', 'par', 'tipo_documento', true],
	'escuela_autoridad' => ['descripcion', 'Autoridades de escuelas', 'Autoridad de escuela', 'a', 'par', 'autoridad_escuela', false],
	'estado_alumno' => ['descripcion', 'Estados de alumnos', 'Estado de alumno', '', 'par', 'estado_alumno', true],
	'estado_civil' => ['descripcion', 'Estados civiles', 'Estado civil', '', 'par', 'estado_civil', true],
	'evaluacion_tipo' => ['descripcion', 'Tipos de evaluaciones', 'Tipo de evaluación', '', 'par', 'tipo_evaluacion', true],
	'funcion' => ['descripcion', 'Funciones', 'Función', '', 'par', 'funcion', true],
	'grupo_sanguineo' => ['descripcion', 'Grupos sanguíneos', 'Grupo sanguíneo', '', 'par', 'grupo_sanguineo', true],
	'inasistencia_tipo' => ['descripcion', 'Tipos de inasistencias', 'Tipo de inasistencia', '', 'par', 'tipo_inasistencia', true],
	'jornada' => ['descripcion', 'Jornadas', 'Jornada', 'a', 'par', 'jornada', true],
	'jurisdiccion' => ['codigo', 'Jurisdicciones', 'Jurisdicción', 'a', 'par', 'jurisdiccion', true],
	'linea' => ['nombre', 'Lineas', 'Linea', 'a', 'par', 'linea', true],
	'localidad' => ['descripcion', 'Localidades', 'Localidad', 'a', 'par', 'localidad', true],
	'materia' => ['descripcion', 'Materias', 'Materia', 'a', 'par', 'materia', true],
	'modalidad' => ['descripcion', 'Modalidades', 'Modalidad', 'a', 'par', 'modalidad', true],
	'nacionalidad' => ['descripcion', 'Nacionalidades', 'Nacionalidad', 'a', 'par', 'nacionalidad', true],
	'nivel' => ['descripcion', 'Niveles', 'Nivel', '', 'par', 'nivel', true],
	'nivel_estudio' => ['descripcion', 'Niveles de estudios', 'Nivel de estudios', '', 'par', 'nivel_estudio', true],
	'novedad_tipo' => ['descripcion', 'Tipos de novedades', 'Tipo de novedad', '', 'par', 'tipo_novedad', false],
	'obra_social' => ['descripcion', 'Obras sociales', 'Obra social', 'a', 'par', 'obra_social', true],
	'ocupacion' => ['descripcion', 'Ocupaciones', 'Ocupación', 'a', 'par', 'ocupacion', true],
	'parametro' => ['descripcion', 'Parámetros', 'Parámetro', '', 'par', 'parametro', true],
	'parentesco_tipo' => ['descripcion', 'Tipos de parentescos', 'Tipo de parentesco', '', 'par', 'tipo_parentesco', true],
	'prestadora' => ['descripcion', 'Prestadoras', 'Prestadora', 'a', 'par', 'prestadora', true],
	'provincia' => ['descripcion', 'Provincias', 'Provincia', 'a', 'par', 'provincia', true],
	'regimen_tipo' => ['descripcion', 'Tipos regímenes', 'Tipo de régimen', '', 'par', 'tipo_regimen', true],
	'regional' => ['descripcion', 'Regionales', 'Regional', 'a', 'par', 'regional', true],
	'reparticion' => ['codigo', 'Reparticiones', 'Repartición', 'a', 'par', 'reparticion', true],
	'rol' => ['nombre', 'Roles', 'Rol', '', 'par', 'rol', true],
	'sistema' => ['nombre', 'Sistemas', 'Sistema', '', 'par', 'sistema', true],
	'situacion_revista' => ['descripcion', 'Situaciones de revista', 'Situación de revista', 'a', 'par', 'situacion_revista', true],
	'supervision' => ['nombre', 'Supervisiones', 'Supervisión', 'a', 'par', 'supervision', true],
	'turno' => ['descripcion', 'Turnos', 'Turno', '', 'par', 'turno', true],
	'valor_tipo' => ['descripcion', 'Tipos de valores', 'Tipo de valor', '', 'par', 'tipo_valor', true],
	'zona' => ['descripcion', 'Zonas', 'Zona', 'a', 'par', 'zona', true],
];
/**/
// </editor-fold>

foreach ($TABLES as $table => $row) {
	if (!in_array($table, array('abono_escuela_monto')))
		continue;
// <editor-fold defaultstate="collapsed" desc="Proceso">
	set_time_limit(300);
	$nav_route = empty($row[4]) ? 'par' : $row[4];
	$modal = empty($row[6]) ? false : $row[6];
	$db_table = $TBL_PREFIX . $table;
	if (empty($row[5])) {
		if (substr($table, -2) === 'es')
			$table_1 = substr($table, 0, strlen($table) - 2);
		else
			$table_1 = substr($table, 0, strlen($table) - 1);
	} else
		$table_1 = $row[5];
	$table_show = strtolower($row[1]);
	$table_1_show = strtolower($row[2]);

	$controller_file_name = strtoupper(substr($table, 0, 1)) . substr($table, 1);
	$model_file_name = $controller_file_name . "_model";
	$multiple = strpos($table, '_');
	if (!empty($row[3])) {
		$art_1 = 'a';
		$art_2 = 'la';
	} else {
		$art_1 = 'o';
		$art_2 = 'el';
	}
	if (!empty($row[1])) {
		$object_name = $row[1];
		$msg_name = $row[2];
	} else {
		if ($multiple === FALSE) {
			$object_name = strtoupper(substr($table, 0, 1)) . substr($table, 1);
			if (substr($object_name, -2) === 'es') {
				$msg_name = substr($object_name, 0, strlen($object_name) - 2);
			} else {
				$msg_name = substr($object_name, 0, strlen($object_name) - 1);
			}
		} else {
			$names = explode('_', $table);
			$names[0] = strtoupper(substr($names[0], 0, 1)) . substr($names[0], 1);
			$names[1] = strtoupper(substr($names[1], 0, 1)) . substr($names[1], 1);
			$object_name = $names[0] . ' de ' . $names[1];
			if (substr($names[0], -2) === 'es') {
				$msg_name = substr($names[0], 0, strlen($names[0]) - 2);
			} else if (substr($names[0], -1) === 's') {
				$msg_name = substr($names[0], 0, strlen($names[0]) - 1);
			} else {
				$msg_name = $names[0];
			}
			if (substr($names[1], -2) === 'es') {
				$msg_name .= ' de ' . substr($names[1], 0, strlen($names[1]) - 2);
			} else if (substr($names[1], -1) === 's') {
				$msg_name .= ' de ' . substr($names[1], 0, strlen($names[1]) - 1);
			} else {
				$msg_name .= ' de ' . $names[1];
			}
		}
	}
	if ($DB_SERVER === 'mysql') {
		$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_SCHEMA, 3306);
		if ($mysqli->connect_errno)
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;

		$rs = $mysqli->query("SELECT C.COLUMN_NAME AS Field, C.COLUMN_TYPE AS Type, C.IS_NULLABLE AS 'Null', C.COLUMN_KEY AS 'Key', C.COLUMN_DEFAULT AS 'Default', C.EXTRA AS Extra, FK.REFERENCED_TABLE_NAME AS FK_Table, FK.REFERENCED_COLUMN_NAME AS FK_Column"
			. " FROM INFORMATION_SCHEMA.COLUMNS C"
			. " LEFT JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE FK ON C.TABLE_NAME=FK.TABLE_NAME AND C.COLUMN_NAME=FK.COLUMN_NAME AND C.TABLE_SCHEMA=FK.REFERENCED_TABLE_SCHEMA"
			. " WHERE C.TABLE_SCHEMA='$DB_SCHEMA' AND C.TABLE_NAME='$db_table' AND C.COLUMN_NAME NOT IN ('audi_fecha', 'audi_user', 'audi_accion')"
			. " ORDER BY C.ORDINAL_POSITION");
	}
	else {
		$serverName = $DB_HOST; //serverName\instanceName
		$connectionInfo = array("Database" => $DB_SCHEMA, "UID" => $DB_USER, "PWD" => $DB_PASSWORD);
		$link = mssql_connect($serverName, $DB_USER, $DB_PASSWORD);
		mssql_select_db('[' . $DB_SCHEMA . ']', $link);
		if (!$link)
			echo "Failed to connecto to SQLSRV";
		$rs = mssql_query("SELECT c.name 'Field'," .
			" (t.Name + '(' + cast(c.max_length as varchar(5)) + ')') 'Type'," .
			" c.is_nullable 'Null'," .
			" (CASE WHEN i.is_primary_key IS NOT NULL THEN 'PRI' ELSE '' END) 'Key'" .
			" FROM sys.columns c" .
			" INNER JOIN sys.types t ON c.system_type_id = t.system_type_id" .
			" LEFT OUTER JOIN sys.index_columns ic ON ic.object_id = c.object_id AND ic.column_id = c.column_id" .
			" LEFT OUTER JOIN sys.indexes i ON ic.object_id = i.object_id AND ic.index_id = i.index_id" .
			" WHERE c.object_id = OBJECT_ID('$db_table')", $link);
	}

	$model_columnas = "array(";
	$model_fields = "array(";
	$model_requeridos = "array(";
	$model_default_join = "";
	$controller_list_columns = "";
	$controller_datos_list_join = "";
	$controller_datos_list_columns = "";
	$controller_load_models = "";
	$controller_array_control_combos_agregar = "";
	$controller_array_control_combos_editar = "";
	$controller_array_control_combos_ver = "";
	$controller_array_to_field = "";
	$controller_query_create = "";
	$controller_query_update = "";
	$pk_name = "";
	$pk_name_nq = "";
	if ($DB_SERVER === 'mysql') {
		print_r("Tabla $table - " . $rs->num_rows . " columnas\n");
	} else {
		print_r("Tabla $table - " . mssql_num_rows($rs) . " columnas\n");
	}
	while ($DB_SERVER === 'mysql' ? $row = $rs->fetch_object() : $row = mssql_fetch_object($rs)) {
		//Model
		$field_name = $row->Field;
		$field_name_no_id = substr($field_name, -3) == '_id' ? substr($field_name, 0, -3) : $field_name;

		if (isset($TABLES[$field_name_no_id])) {
			$field_label = $TABLES[$field_name_no_id][2];
		} elseif (strpos($field_name_no_id, '_') === FALSE) {
			$field_label = strtoupper(substr($field_name_no_id, 0, 1)) . substr($field_name_no_id, 1);
		} else {
			$names = explode('_', $field_name_no_id);
			$names[0] = strtoupper(substr($names[0], 0, 1)) . substr($names[0], 1);
			$names[1] = strtoupper(substr($names[1], 0, 1)) . substr($names[1], 1);
			$field_label = $names[0] . ' de ' . $names[1];
		}

		if ($row->Key !== "PRI") {
			$model_fields .= "
			'$field_name_no_id' => array('label' => '$field_label'";
			if (substr($field_name, -3) === '_id')
				$model_fields .= ", 'input_type' => 'combo', 'id_name' => '$field_name'";
			else {
				if (strpos($row->Type, 'int') !== false) {
					$model_fields .= ", 'type' => 'integer'";
					if (preg_match('!\(([^\)]+)\)!', $row->Type, $match)) {
						$size = $match[1];
						$model_fields .= ", 'maxlength' => '$size'";
					}
				} elseif (strpos($row->Type, 'char(') !== false) {
					if (preg_match('!\(([^\)]+)\)!', $row->Type, $match)) {
						$size = $match[1];
						$model_fields .= ", 'maxlength' => '$size'";
					}
				} elseif (strpos($row->Type, 'datetime') !== false) {
					$model_fields .= ", 'type' => 'datetime'";
				} elseif (strpos($row->Type, 'date') !== false) {
					$model_fields .= ", 'type' => 'date'";
				}
			}

			if ($row->Null === "NO") {
				$model_requeridos .= "'$row->Field', ";
				$model_fields .= ", 'required' => TRUE";
			}
			$model_fields .= "),";
		}
		$model_columnas .= "'$row->Field', ";

		//Controller + Views

		$controller_datos_list_columns .= "$db_table.$field_name, ";

		$class = '';
		$formatter = '';
		if ($row->Key == "PRI") {
			$pk_name = "'$field_name'";
			$pk_name_nq = "$field_name";
			if (strpos($row->Type, 'int') !== false)
				$class = ", 'class' => 'dt-body-right'";
		}
		else {
			if ($modal) {
				$controller_query_create .= "
					'$field_name' => \$this->input->post('$field_name_no_id'),";
			} else {
				$controller_query_create .= "
				'$field_name' => \$this->input->post('$field_name_no_id'),";
			}
			$controller_query_update .= "
					'$field_name' => \$this->input->post('$field_name_no_id'),";
			if (strpos($row->Type, 'int') !== false && substr($field_name, -3) !== '_id') {
				$class = ", 'class' => 'dt-body-right'";
			} elseif (strpos($row->Type, 'datetime') !== false) {
				$formatter = ", 'render' => 'datetime'";
			} elseif (strpos($row->Type, 'date') !== false) {
				$formatter = ", 'render' => 'date'";
			}
		}
		if (empty($row->FK_Table)) {
			$controller_list_columns .= "
				array('label' => '$field_label', 'data' => '$field_name'$class$formatter, 'width' => 10),";
		} else {
			$controller_list_columns .= "
				array('label' => '$field_label', 'data' => '$field_name_no_id'$class$formatter, 'width' => 10),";
		}
	}
	$model_columnas = substr($model_columnas, 0, strlen($model_columnas) - 2);
	$model_columnas .= ")";
	$model_fields = substr($model_fields, 0, strlen($model_fields) - 1);
	$model_fields .= "
		)";
	if ($model_requeridos !== 'array(') {
		$model_requeridos = substr($model_requeridos, 0, strlen($model_requeridos) - 2);
	}
	$model_requeridos .= ")";
	$controller_datos_list_columns = substr($controller_datos_list_columns, 0, strlen($controller_datos_list_columns) - 2);
	$controller_query_create = substr($controller_query_create, 0, strlen($controller_query_create) - 1);
	$controller_query_update = substr($controller_query_update, 0, strlen($controller_query_update) - 1);
	$can_delete_fn = "";
	if ($DB_SERVER === 'mysql') {
		$rs->close();
		$rs_ref_fk = $mysqli->query("SELECT TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME" .
			" FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE" .
			" WHERE REFERENCED_TABLE_SCHEMA = '$DB_SCHEMA'" .
			" AND REFERENCED_TABLE_NAME='$db_table'" .
			" ORDER BY TABLE_NAME, COLUMN_NAME");
		while ($row = $rs_ref_fk->fetch_object()) {
			$t_name = str_replace('_', ' de ', str_replace($TBL_PREFIX, '', $row->TABLE_NAME));
			$can_delete_fn .= "
		if (\$this->db->where('$row->COLUMN_NAME', \$delete_id)->count_all_results('$row->TABLE_NAME') > 0) {
			\$this->_set_error('No se ha podido eliminar el registro de ' . \$this->msg_name . '. Verifique que no esté asociado a $t_name.');
			return FALSE;
		}";
		}
		$rs_ref_fk->close();
		$rs_fk = $mysqli->query("SELECT TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME" .
			" FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE" .
			" WHERE REFERENCED_TABLE_SCHEMA = '$DB_SCHEMA'" .
			" AND TABLE_NAME='$db_table'" .
			" ORDER BY REFERENCED_TABLE_NAME, COLUMN_NAME");
		while ($row = $rs_fk->fetch_object()) {
			$t_name = str_replace($TBL_PREFIX, '', $row->TABLE_NAME);
			$c_name = str_replace('_id', '', $row->COLUMN_NAME);
			$r_t_name = str_replace($TBL_PREFIX, '', $row->REFERENCED_TABLE_NAME);
			$c_o_name = strtolower($TABLES[str_replace($TBL_PREFIX, '', $row->REFERENCED_TABLE_NAME)][2]);
			$controller_datos_list_join .= "
			->join('$row->REFERENCED_TABLE_NAME', '$row->REFERENCED_TABLE_NAME.id = $row->TABLE_NAME.$row->COLUMN_NAME', 'left')";
			$model_default_join .= "
			array('$row->REFERENCED_TABLE_NAME', '$row->REFERENCED_TABLE_NAME.id = $row->TABLE_NAME.$row->COLUMN_NAME', 'left', array('$row->REFERENCED_TABLE_NAME." . (empty($TABLES[$row->REFERENCED_TABLE_NAME][0]) ? 'descripcion' : $TABLES[$row->REFERENCED_TABLE_NAME][0]) . " as $c_name')), ";
			$controller_datos_list_columns .= ", $row->REFERENCED_TABLE_NAME." . (empty($TABLES[$row->REFERENCED_TABLE_NAME][0]) ? 'descripcion' : $TABLES[$row->REFERENCED_TABLE_NAME][0]) . " as $c_name";
			$controller_load_models .= "
		\$this->load->model('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "{$r_t_name}_model');";
			$controller_array_control_combos_agregar .= "
		\$this->array_{$c_name}_control = \$array_{$c_name} = \$this->get_array('$r_t_name', '" . (empty($TABLES[$row->REFERENCED_TABLE_NAME][0]) ? 'descripcion' : $TABLES[$row->REFERENCED_TABLE_NAME][0]) . "', 'id', null, array('' => '-- Seleccionar $c_o_name --'));";
			$controller_array_control_combos_editar .= "
		\$this->array_{$c_name}_control = \$array_{$c_name} = \$this->get_array('$r_t_name'" . (empty($TABLES[$row->REFERENCED_TABLE_NAME][0]) ? '' : ", '" . $TABLES[$row->REFERENCED_TABLE_NAME][0] . "'") . ");";
			$controller_array_control_combos_ver .= "
		\$array_{$c_name} = \$this->get_array('$r_t_name'" . (empty($TABLES[$row->REFERENCED_TABLE_NAME][0]) ? '' : ", '" . $TABLES[$row->REFERENCED_TABLE_NAME][0] . "'") . ");";
			$controller_array_to_field .= "\$this->{$table}_model->fields['{$c_name}']['array'] = \$array_{$c_name};
		";
		}
		$rs_fk->close();
	} else {
		$rs = null;
		$rs_ref_fk = mssql_query("SELECT KCU1.TABLE_NAME AS TABLE_NAME," .
			" KCU1.COLUMN_NAME AS COLUMN_NAME," .
			" KCU2.TABLE_NAME AS REFERENCED_TABLE_NAME," .
			" KCU2.COLUMN_NAME AS REFERENCED_COLUMN_NAME" .
			" FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS AS RC" .
			" INNER JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS KCU1 ON KCU1.CONSTRAINT_CATALOG = RC.CONSTRAINT_CATALOG AND KCU1.CONSTRAINT_SCHEMA = RC.CONSTRAINT_SCHEMA AND KCU1.CONSTRAINT_NAME = RC.CONSTRAINT_NAME" .
			" INNER JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS KCU2 ON KCU2.CONSTRAINT_CATALOG = RC.UNIQUE_CONSTRAINT_CATALOG AND KCU2.CONSTRAINT_SCHEMA = RC.UNIQUE_CONSTRAINT_SCHEMA AND KCU2.CONSTRAINT_NAME = RC.UNIQUE_CONSTRAINT_NAME AND KCU2.ORDINAL_POSITION = KCU1.ORDINAL_POSITION" .
			" WHERE KCU2.TABLE_NAME='$db_table' AND KCU1.CONSTRAINT_CATALOG='$DB_SCHEMA'", $link);
		while ($row = mssql_fetch_object($rs_ref_fk)) {
			$t_name = str_replace('_', ' de ', str_replace($TBL_PREFIX, '', $row->TABLE_NAME));
			$can_delete_fn .= "
	if (\$this->db->where('$row->COLUMN_NAME', \$delete_id)->count_all_results('$row->TABLE_NAME') > 0)
	{
		\$this->_set_error('No se ha podido eliminar el registro de ' . \$this->msg_name . '. Verifique que no esté asociado a $t_name.');
		return FALSE;
		}";
		}
		$rs_ref_fk = null;
		$rs_fk = mssql_query("select t3.TABLE_NAME, t3.COLUMN_NAME, T4.TABLE_NAME AS REFERENCED_TABLE_NAME, t4.COLUMN_NAME AS REFERENCED_COLUMN_NAME
        from INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS r
        join INFORMATION_SCHEMA.TABLE_CONSTRAINTS t1 on t1.CONSTRAINT_NAME=r.CONSTRAINT_NAME
		JOIN information_schema.CONSTRAINT_COLUMN_USAGE t3 on t3.CONSTRAINT_NAME=r.CONSTRAINT_NAME
        join INFORMATION_SCHEMA.TABLE_CONSTRAINTS t2 on t2.CONSTRAINT_NAME=r.UNIQUE_CONSTRAINT_NAME
		JOIN information_schema.CONSTRAINT_COLUMN_USAGE t4 on t4.CONSTRAINT_NAME=r.UNIQUE_CONSTRAINT_NAME
        where t1.table_name = '$db_table' ORDER BY T4.TABLE_NAME, t3.COLUMN_NAME", $link);
		while ($row = mssql_fetch_object($rs_fk)) {
			$t_name = str_replace($TBL_PREFIX, '', $row->TABLE_NAME);
			$c_name = str_replace('_id', '', $row->COLUMN_NAME);
			$r_t_name = str_replace($TBL_PREFIX, '', $row->REFERENCED_TABLE_NAME);
			$model_default_join .= "
			array('$row->REFERENCED_TABLE_NAME', '$row->REFERENCED_TABLE_NAME.id = $row->TABLE_NAME.$row->COLUMN_NAME', 'left', array('$row->REFERENCED_TABLE_NAME." . (empty($TABLES[$row->REFERENCED_TABLE_NAME][0]) ? 'descripcion' : $TABLES[$row->REFERENCED_TABLE_NAME][0]) . " as $c_name')), ";
			$controller_datos_list_join .= "
			->join('$row->REFERENCED_TABLE_NAME', '$row->REFERENCED_TABLE_NAME.id = $row->TABLE_NAME.$row->COLUMN_NAME', 'left')";
			$controller_datos_list_columns .= ", $row->REFERENCED_TABLE_NAME." . (empty($TABLES[$row->REFERENCED_TABLE_NAME][0]) ? 'descripcion' : $TABLES[$row->REFERENCED_TABLE_NAME][0]) . " as $c_name";
			$controller_load_models .= "
		\$this->load->model('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "{$r_t_name}_model');";
			$controller_array_control_combos_agregar .= "
		\$this->array_{$c_name}_control = \$array_{$c_name} = \$this->get_array('$r_t_name', '" . (empty($TABLES[$row->REFERENCED_TABLE_NAME][0]) ? 'descripcion' : $TABLES[$row->REFERENCED_TABLE_NAME][0]) . "', 'id', null, array('' => '-- Seleccionar $c_name --'));";
			$controller_array_control_combos_editar .= "
		\$this->array_{$c_name}_control = \$array_{$c_name} = \$this->get_array('$r_t_name'" . (empty($TABLES[$row->REFERENCED_TABLE_NAME][0]) ? '' : ", '" . $TABLES[$row->REFERENCED_TABLE_NAME][0] . "'") . ");";
			$controller_array_control_combos_ver .= "
		\$array_{$c_name} = \$this->get_array('$r_t_name'" . (empty($TABLES[$row->REFERENCED_TABLE_NAME][0]) ? '' : ", '" . $TABLES[$row->REFERENCED_TABLE_NAME][0] . "'") . ");";
			$controller_array_to_field .= "\$this->{$table}_model->fields['{$c_name}']['array'] = \$array_{$c_name};
		";
		}
		$rs_fk = null;
		mssql_close($link);
	}
	if ($MODULES_ENABLED) {
		if (!is_dir("modules"))
			mkdir("modules");
		if (!is_dir("modules/$MODULE_PATH"))
			mkdir("modules/$MODULE_PATH");
		if (!is_dir("modules/$MODULE_PATH/controllers"))
			mkdir("modules/$MODULE_PATH/controllers");
		if (!is_dir("modules/$MODULE_PATH/models"))
			mkdir("modules/$MODULE_PATH/models");
		if (!is_dir("modules/$MODULE_PATH/views"))
			mkdir("modules/$MODULE_PATH/views");
	}
	else {
		if (!is_dir("controllers"))
			mkdir("controllers");
		if (!is_dir("models"))
			mkdir("models");
		if (!is_dir("views"))
			mkdir("views");
		if (!empty($MODULE_PATH)) {
			if (!is_dir("controllers/$MODULE_PATH"))
				mkdir("controllers/$MODULE_PATH");
			if (!is_dir("models/$MODULE_PATH"))
				mkdir("models/$MODULE_PATH");
			if (!is_dir("views/$MODULE_PATH"))
				mkdir("views/$MODULE_PATH");
		}
	}

//Model
	if ($MODULES_ENABLED) {
		$model_location = "modules/$MODULE_PATH/models";
		$controller_location = "modules/$MODULE_PATH/controllers";
		$views_location = "modules/$MODULE_PATH/views/$table";
	} else {
		$model_location = "models" . (empty($MODULE_PATH) ? '' : "/$MODULE_PATH");
		$controller_location = "controllers" . (empty($MODULE_PATH) ? '' : "/$MODULE_PATH");
		$views_location = "views" . (empty($MODULE_PATH) ? '' : "/$MODULE_PATH") . "/$table";
	}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Archivo Modelo">
	$model_content = "<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class $model_file_name extends MY_Model {

	public function __construct() {
		parent::__construct();
		\$this->table_name = '$db_table';
		\$this->msg_name = '$msg_name';
		\$this->id_name = $pk_name;
		\$this->columnas = $model_columnas;
		\$this->fields = $model_fields;
		\$this->requeridos = $model_requeridos;
		//\$this->unicos = array();
		\$this->default_join = array($model_default_join);
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int \$delete_id
	 * @return bool
	 */
	protected function _can_delete(\$delete_id) {{$can_delete_fn}
		return TRUE;
	}
}
/* End of file $model_file_name.php */
/* Location: ./application/$model_location/$model_file_name.php */";

	$fp = fopen("$model_location/$model_file_name.php", "w");
	fwrite($fp, $model_content);
	fclose($fp);

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Archivo Controlador">
	if ($modal) {
		$listar_data_add_column = "->add_column('edit', '<a href=\"" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/modal_ver/$1\" data-remote=\"false\" data-toggle=\"modal\" data-target=\"#remote_modal\" class=\"btn btn-xs btn-default\"><i class=\"fa fa-search\"></i></a> <a href=\"" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/modal_editar/$1\" data-remote=\"false\" data-toggle=\"modal\" data-target=\"#remote_modal\" class=\"btn btn-xs btn-warning\"><i class=\"fa fa-pencil\"></i></a> <a href=\"" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/modal_eliminar/$1\" data-remote=\"false\" data-toggle=\"modal\" data-target=\"#remote_modal\" class=\"btn btn-xs btn-danger\"><i class=\"fa fa-remove\"></i></a>', 'id');";
		$function_agregar = "public function modal_agregar()
	{
		if (!in_array(\$this->rol->codigo, \$this->roles_permitidos))
		{
			\$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
{$controller_load_models}{$controller_array_control_combos_agregar}
		\$this->set_model_validation_rules(\$this->{$table}_model);
		if (isset(\$_POST) && !empty(\$_POST))
		{
			if (\$this->form_validation->run() === TRUE)
			{
				\$trans_ok = TRUE;
				\$trans_ok&= \$this->{$table}_model->create(array($controller_query_create));

				if (\$trans_ok)
				{
					\$this->session->set_flashdata('message', \$this->{$table}_model->get_msg());
				}
				else
				{
					\$this->session->set_flashdata('error', \$this->{$table}_model->get_error());
				}
				redirect('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/listar', 'refresh');
			}
			else
			{
				\$this->session->set_flashdata('error', validation_errors());
				redirect('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/listar', 'refresh');
			}
		}

		$controller_array_to_field\$data['fields'] = \$this->build_fields(\$this->{$table}_model->fields);

		\$data['txt_btn'] = 'Agregar';
		\$data['title'] = 'Agregar $table_1_show';
		\$this->load->view('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/{$table}_modal_abm', \$data);
	}";
		$function_editar = "public function modal_editar(\$id = NULL)
	{
		if (!in_array(\$this->rol->codigo, \$this->roles_permitidos) || \$id == NULL || !ctype_digit(\$id))
		{
			\$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;

		}
		\$$table_1 = \$this->{$table}_model->get(array($pk_name => \$id));
		if (empty(\$$table_1))
		{
			\$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;

		}{$controller_load_models}{$controller_array_control_combos_editar}
		\$this->set_model_validation_rules(\$this->{$table}_model);
		if (isset(\$_POST) && !empty(\$_POST))
		{
			if (\$id !== \$this->input->post($pk_name))
			{
				\$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
			return;

			}

			if (\$this->form_validation->run() === TRUE)
			{
				\$trans_ok = TRUE;
				\$trans_ok&= \$this->{$table}_model->update(array(
					$pk_name => \$this->input->post($pk_name),$controller_query_update));
				if (\$trans_ok)
				{
					\$this->session->set_flashdata('message', \$this->{$table}_model->get_msg());
					redirect('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/listar', 'refresh');
				}
			}
			else
			{
				\$this->session->set_flashdata('error', validation_errors());
				redirect('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/listar', 'refresh');
			}
		}

		$controller_array_to_field\$data['fields'] = \$this->build_fields(\$this->{$table}_model->fields, \${$table_1});

		\$data['$table_1'] = \$$table_1;

		\$data['txt_btn'] = 'Editar';
		\$data['title'] = 'Editar $table_1_show';
		\$this->load->view('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/{$table}_modal_abm', \$data);
	}";
		$function_eliminar = "public function modal_eliminar(\$id = NULL)
	{
		if (!in_array(\$this->rol->codigo, \$this->roles_permitidos) || \$id == NULL || !ctype_digit(\$id))
		{
			\$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;

		}
		\$$table_1 = \$this->{$table}_model->get(array($pk_name => \$id));
		if (empty(\$$table_1))
		{
			\$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;

		}" . (empty($controller_load_models . $controller_array_control_combos_ver) ? "" : "
" . $controller_load_models . $controller_array_control_combos_ver) . "
		if (isset(\$_POST) && !empty(\$_POST))
		{
			if (\$id !== \$this->input->post($pk_name))
			{
				\$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
			return;

			}

			\$trans_ok = TRUE;
			\$trans_ok&= \$this->{$table}_model->delete(array($pk_name => \$this->input->post($pk_name)));
			if (\$trans_ok)
			{
				\$this->session->set_flashdata('message', \$this->{$table}_model->get_msg());
				redirect('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/listar', 'refresh');
			}
		}

		$controller_array_to_field\$data['fields'] = \$this->build_fields(\$this->{$table}_model->fields, \${$table_1}, TRUE);

		\$data['{$table_1}'] = \${$table_1};

		\$data['txt_btn'] = 'Eliminar';
		\$data['title'] = 'Eliminar $table_1_show';
		\$this->load->view('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/{$table}_modal_abm', \$data);
	}";
		$function_ver = "public function modal_ver(\$id = NULL)
	{
		if (!in_array(\$this->rol->codigo, \$this->roles_permitidos) || \$id == NULL || !ctype_digit(\$id))
		{
			\$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;

		}
		\$$table_1 = \$this->{$table}_model->get(array($pk_name => \$id));
		if (empty(\$$table_1))
		{
			\$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;

		}" . (empty($controller_load_models . $controller_array_control_combos_ver) ? "" : "
" . $controller_load_models . $controller_array_control_combos_ver) . "

		$controller_array_to_field\$data['fields'] = \$this->build_fields(\$this->{$table}_model->fields, \${$table_1}, TRUE);

		\$data['{$table_1}'] = \${$table_1};
		\$data['txt_btn'] = NULL;
		\$data['title'] = 'Ver $table_1_show';
		\$this->load->view('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/{$table}_modal_abm', \$data);
	}";
	} else {
		$listar_data_add_column = "->add_column('edit', '<a href=\"" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/ver/$1\" class=\"btn btn-xs btn-default\" title=\"Ver\"><i class=\"fa fa-search\"></i></a> <a href=\"" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/editar/$1\" class=\"btn btn-xs btn-warning\" title=\"Editar\"><i class=\"fa fa-pencil\"></i></a> <a href=\"" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/eliminar/$1\" class=\"btn btn-xs btn-danger\" title=\"Eliminar\"><i class=\"fa fa-remove\"></i></a>', 'id');";
		$function_agregar = "public function agregar()
	{
		if (!in_array(\$this->rol->codigo, \$this->roles_permitidos))
		{
			\$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;

		}
		{$controller_load_models}{$controller_array_control_combos_agregar}
		\$this->set_model_validation_rules(\$this->{$table}_model);
		if (\$this->form_validation->run() === TRUE)
		{
			\$trans_ok = TRUE;
			\$trans_ok&= \$this->{$table}_model->create(array($controller_query_create));

			if (\$trans_ok)
			{
				\$this->session->set_flashdata('message', \$this->{$table}_model->get_msg());
				redirect('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/listar', 'refresh');
			}
		}
		\$data['error'] = (validation_errors() ? validation_errors() : (\$this->{$table}_model->get_error() ? \$this->{$table}_model->get_error() : \$this->session->flashdata('error')));

		$controller_array_to_field\$data['fields'] = \$this->build_fields(\$this->{$table}_model->fields);

		\$data['txt_btn'] = 'Agregar';
		\$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		\$data['title'] = " . ($MODULES_ENABLED ? "'$MODULE_TITLE" : "TITLE . '") . " - Agregar $table_1_show';
		\$this->load_template('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/{$table}_abm', \$data);
	}";
		$function_editar = "public function editar(\$id = NULL)
	{
		if (!in_array(\$this->rol->codigo, \$this->roles_permitidos) || \$id == NULL || !ctype_digit(\$id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		\$$table_1 = \$this->{$table}_model->get(array($pk_name => \$id));
		if (empty(\$$table_1))
		{
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}{$controller_load_models}{$controller_array_control_combos_editar}
		\$this->set_model_validation_rules(\$this->{$table}_model);
		if (isset(\$_POST) && !empty(\$_POST))
		{
			if (\$id !== \$this->input->post($pk_name))
			{
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if (\$this->form_validation->run() === TRUE)
			{
				\$trans_ok = TRUE;
				\$trans_ok&= \$this->{$table}_model->update(array(
					$pk_name => \$this->input->post($pk_name),$controller_query_update));
				if (\$trans_ok)
				{
					\$this->session->set_flashdata('message', \$this->{$table}_model->get_msg());
					redirect('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/listar', 'refresh');
				}
			}
		}
		\$data['error'] = (validation_errors() ? validation_errors() : (\$this->{$table}_model->get_error() ? \$this->{$table}_model->get_error() : \$this->session->flashdata('error')));

		$controller_array_to_field\$data['fields'] = \$this->build_fields(\$this->{$table}_model->fields, \${$table_1});

		\$data['$table_1'] = \$$table_1;

		\$data['txt_btn'] = 'Editar';
		\$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		\$data['title'] = " . ($MODULES_ENABLED ? "'$MODULE_TITLE" : "TITLE . '") . " - Editar $table_1_show';
		\$this->load_template('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/{$table}_abm', \$data);
	}";
		$function_eliminar = "public function eliminar(\$id = NULL)
	{
		if (!in_array(\$this->rol->codigo, \$this->roles_permitidos) || \$id == NULL || !ctype_digit(\$id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		\$$table_1 = \$this->{$table}_model->get(array($pk_name => \$id));
		if (empty(\$$table_1))
		{
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}" . (empty($controller_load_models . $controller_array_control_combos_ver) ? "" : "
" . $controller_load_models . $controller_array_control_combos_ver) . "
		if (isset(\$_POST) && !empty(\$_POST))
		{
			if (\$id !== \$this->input->post($pk_name))
			{
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			\$trans_ok = TRUE;
			\$trans_ok&= \$this->{$table}_model->delete(array($pk_name => \$this->input->post($pk_name)));
			if (\$trans_ok)
			{
				\$this->session->set_flashdata('message', \$this->{$table}_model->get_msg());
				redirect('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/listar', 'refresh');
			}
		}
		\$data['error'] = (validation_errors() ? validation_errors() : (\$this->{$table}_model->get_error() ? \$this->{$table}_model->get_error() : \$this->session->flashdata('error')));

		$controller_array_to_field\$data['fields'] = \$this->build_fields(\$this->{$table}_model->fields, \${$table_1}, TRUE);

		\$data['{$table_1}'] = \${$table_1};

		\$data['txt_btn'] = 'Eliminar';
		\$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		\$data['title'] = " . ($MODULES_ENABLED ? "'$MODULE_TITLE" : "TITLE . '") . " - Eliminar $table_1_show';
		\$this->load_template('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/{$table}_abm', \$data);
	}";
		$function_ver = "public function ver(\$id = NULL)
	{
		if (!in_array(\$this->rol->codigo, \$this->roles_permitidos) || \$id == NULL || !ctype_digit(\$id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		\$$table_1 = \$this->{$table}_model->get(array($pk_name => \$id));
		if (empty(\$$table_1))
		{
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}" . (empty($controller_load_models . $controller_array_control_combos_ver) ? "" : "
" . $controller_load_models . $controller_array_control_combos_ver) . "
		\$data['error'] = \$this->session->flashdata('error');

		$controller_array_to_field\$data['fields'] = \$this->build_fields(\$this->{$table}_model->fields, \${$table_1}, TRUE);

		\$data['{$table_1}'] = \${$table_1};
		\$data['txt_btn'] = NULL;
		\$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		\$data['title'] = " . ($MODULES_ENABLED ? "'$MODULE_TITLE" : "TITLE . '") . " - Ver $table_1_show';
		\$this->load_template('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/{$table}_abm', \$data);
	}";
	}
	$controller_content = "<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class $controller_file_name extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		\$this->load->model('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "{$table}_model');
		\$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION, ROL_DOCENTE);
		\$this->nav_route = '{$nav_route}/{$table}';
	}

	public function listar()
	{
		if (!in_array(\$this->rol->codigo, \$this->roles_permitidos))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		\$tableData = array(
			'columns' => array(//@todo arreglar anchos de columnas$controller_list_columns
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => '{$table}_table',
			'source_url' => '" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/listar_data'
		);
		\$data['html_table'] = buildHTML(\$tableData);
		\$data['js_table'] = buildJS(\$tableData);
		\$data['error'] = \$this->session->flashdata('error');
		\$data['message'] = \$this->session->flashdata('message');
		\$data['title'] = " . ($MODULES_ENABLED ? "'$MODULE_TITLE" : "TITLE . '") . " - $object_name';
		\$this->load_template('" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/{$table}_listar', \$data);
	}

	public function listar_data()
	{
		if (!in_array(\$this->rol->codigo, \$this->roles_permitidos))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		\$this->datatables
			->select('$controller_datos_list_columns')
			->unset_column('id')
			->from('$db_table')$controller_datos_list_join
			$listar_data_add_column

		echo \$this->datatables->generate();
	}

	$function_agregar

	$function_editar

	$function_eliminar

	$function_ver
}
/* End of file $controller_file_name.php */
/* Location: ./application/$controller_location/$controller_file_name.php */";

	$fp = fopen("$controller_location/$controller_file_name.php", "w");
	fwrite($fp, $controller_content);
	fclose($fp);

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Archivos View">
	$content_listar = "<div class=\"content-wrapper\">
	<section class=\"content-header\">
		<h1>
			$object_name
		</h1>
		<ol class=\"breadcrumb\">
			<li><a href=\"\"><i class=\"fa fa-home\"></i> Inicio</a></li>
			<li><a href=\"" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "<?php echo \$controlador; ?>\">$object_name</a></li>
			<li class=\"active\"><?php echo ucfirst(\$metodo); ?></li>
		</ol>
	</section>
	<section class=\"content\">
		<?php if (!empty(\$error)) : ?>
			<div class=\"alert alert-danger alert-dismissable\">
				<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
				<h4><i class=\"icon fa fa-ban\"></i> Error!</h4>
				<?php echo \$error; ?>
			</div>
		<?php endif; ?>
		<?php if (!empty(\$message)) : ?>
			<div class=\"alert alert-success alert-dismissable\">
				<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
				<h4><i class=\"icon fa fa-check\"></i> OK!</h4>
				<?php echo \$message; ?>
			</div>
		<?php endif; ?>
		<div class=\"row\">
			<div class=\"col-xs-12\">
				<div class=\"box box-primary\">
					<div class=\"box-body\">
						<a class=\"btn bg-blue btn-app btn-app-zetta\" href=\"" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/" . ($modal ? "modal_agregar\" data-remote=\"false\" data-toggle=\"modal\" data-target=\"#remote_modal\"" : "agregar\"") . ">
							<i class=\"fa fa-plus\" id=\"btn-agregar\"></i> Agregar
						</a>"/* .
		  <div class=\"row\">
		  <div class=\"col-sm-12\">
		  <a class=\"btn btn-primary\" href=\"" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/" . ($modal ? "modal_agregar\" data-remote=\"false\" data-toggle=\"modal\" data-target=\"#remote_modal\"" : "agregar\"") . " title=\"Agregar $table_1_show\"><i class=\"fa fa-plus\"></i> Agregar $table_1_show</a>
		  </div>
		  </div>" */ . "
						<hr style=\"margin: 10px 0;\">
						<?php echo \$js_table; ?>
						<?php echo \$html_table; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>";
	if ($modal) {
		$content_listar .= "
<script>
	$(document).ready(function() {
		$(\"#remote_modal\").on(\"show.bs.modal\", function(e) {
			var link = $(e.relatedTarget);
			$(this).find(\".modal-content\").load(link.attr(\"href\"));
		});
		$('#remote_modal').on(\"hidden.bs.modal\", function(e) {
			$(this).find(\".modal-content\").empty();
		});
	});
</script>
<div class=\"modal fade\" id=\"remote_modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"Modal\" aria-hidden=\"true\">
  <div class=\"modal-dialog\">
    <div class=\"modal-content\">
    </div>
  </div>
</div>";
	}

	if ($modal) {
		$content_modal_abm = "<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class=\"modal-header\">
	<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
	<h4 class=\"modal-title\" id=\"myModalLabel\"><?php echo \$title; ?></h4>
</div>
<div class=\"modal-body\">
		<?php foreach (\$fields as \$field): ?>
			<div class=\"form-group\">
				<?php echo \$field['label']; ?>
				<?php echo \$field['form']; ?>
			</div>
		<?php endforeach; ?>
</div>
<div class=\"modal-footer\">
	<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\"><?php echo isset(\$txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty(\$txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => \$txt_btn), \$txt_btn) : ''; ?>
	<?php echo (\$txt_btn === 'Editar' || \$txt_btn === 'Eliminar') ? form_hidden($pk_name, \${$table_1}->$pk_name_nq) : ''; ?>
</div>
<?php echo form_close(); ?>";
	} else {
		$content_abm = "<div class=\"content-wrapper\">
	<section class=\"content-header\">
		<h1>
			$object_name
		</h1>
		<ol class=\"breadcrumb\">
			<li><a href=\"\"><i class=\"fa fa-home\"></i> Inicio</a></li>
			<li><a href=\"<?php echo \$controlador; ?>\">$object_name</a></li>
			<li class=\"active\"><?php echo ucfirst(\$metodo); ?></li>
		</ol>
	</section>
	<section class=\"content\">
		<?php if (!empty(\$error)) : ?>
			<div class=\"alert alert-danger alert-dismissable\">
				<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
				<h4><i class=\"icon fa fa-ban\"></i> Error!</h4>
				<?php echo \$error; ?>
			</div>
		<?php endif; ?>
		<?php if (!empty(\$message)) : ?>
			<div class=\"alert alert-success alert-dismissable\">
				<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
				<h4><i class=\"icon fa fa-check\"></i> OK!</h4>
				<?php echo \$message; ?>
			</div>
		<?php endif; ?>
		<div class=\"row\">
			<div class=\"col-xs-12\">
				<div class=\"box box-primary\">
					<?php \$data_submit = array('class' => 'btn btn-primary pull-right', 'title' => \$txt_btn); ?>
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class=\"box-body\">
						<a class=\"btn btn-app btn-app-zetta <?php echo \$class['agregar']; ?>\" href=\"" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/agregar\">
							<i class=\"fa fa-plus\" id=\"btn-agregar\"></i> Agregar
						</a>
						<a class=\"btn btn-app btn-app-zetta <?php echo \$class['ver']; ?>\" href=\"" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/ver/<?php echo (!empty(\${$table_1}->$pk_name_nq)) ? \${$table_1}->$pk_name_nq : ''; ?>\">
							<i class=\"fa fa-search\" id=\"btn-ver\"></i> Ver
						</a>
						<a class=\"btn btn-app btn-app-zetta <?php echo \$class['editar']; ?>\" href=\"" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/editar/<?php echo (!empty(\${$table_1}->$pk_name_nq)) ? \${$table_1}->$pk_name_nq : ''; ?>\">
							<i class=\"fa fa-edit\" id=\"btn-editar\"></i> Editar
						</a>
						<a class=\"btn btn-app btn-app-zetta <?php echo \$class['eliminar']; ?>\" href=\"" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/eliminar/<?php echo (!empty(\${$table_1}->$pk_name_nq)) ? \${$table_1}->$pk_name_nq : ''; ?>\">
							<i class=\"fa fa-ban\" id=\"btn-eliminar\"></i> Eliminar
						</a>
						<?php foreach (\$fields as \$field): ?>
							<div class=\"form-group\">
								<?php echo \$field['label']; ?>
								<?php echo \$field['form']; ?>
							</div>
						<?php endforeach; ?>
					</div>
					<div class=\"box-footer\">
						<a class=\"btn btn-default\" href=\"" . ($MODULES_ENABLED ? "$MODULE_PATH/" : '') . "$table/listar\" title=\"Cancelar\">Cancelar</a>
						<?php echo (!empty(\$txt_btn)) ? form_submit(\$data_submit, \$txt_btn) : ''; ?>
						<?php echo (\$txt_btn === 'Editar' || \$txt_btn === 'Eliminar') ? form_hidden($pk_name, \${$table_1}->$pk_name_nq) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>";
	}

	if (!is_dir("$views_location"))
		mkdir("$views_location");
	$operations = array('_listar', $modal ? '_modal_abm' : '_abm');
	foreach ($operations as $operation) {
		$fp = fopen("$views_location/{$table}$operation.php", "w");
		fwrite($fp, ${"content$operation"});
		fclose($fp);
	}
// </editor-fold>
}
/* Para formato específico de vistas abm * /
						<div class="row">
							<?php $html = ''; ?>
							<?php foreach ($fields as $name => $field): ?>
								<?php $html.='							<div class="form-group col-md-3">
								<?php echo $fields[\'' . $name . '\'][\'label\']; ?>
								<?php echo $fields[\'' . $name . '\'][\'form\']; ?>
							</div>
'; ?>
							<?php endforeach; ?>
							<?php lm($html); ?>
						</div>
/* */
