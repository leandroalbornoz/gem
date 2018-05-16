<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('in_modulos')) {

	function in_modulos($rol, $modulos_permitidos = array()) {
		return $rol->codigo === ROL_MODULO && in_array($rol->entidad_id, $modulos_permitidos);
	}
}

if (!function_exists('accion_permitida')) {

	function accion_permitida($rol, $roles_permitidos = array(), $modulos_permitidos = array()) {
		return in_array($rol->codigo, $roles_permitidos) || in_modulos($rol, $modulos_permitidos);
	}
}

if (!function_exists('load_permisos_nav')) {

	function load_permisos_nav($rol, $route) {
		$routes = explode('/', $route);
		$li_class = array('admin' => '', 'alumnos' => '', 'esc' => '', 'menu' => '', 'par' => '', 'usuarios' => '', 'suplementaria' => '', 'busqueda' => '', 'reportes' => '', 'titulo' => '', 'juntas' => '' , 'transporte' => '');
		$li_class[$routes[0]] = 'active';
		$nav = '';
		if ($rol->codigo !== ROL_PORTAL) {
			$nav .= '<li><a href="ayuda/tutoriales"><i class="fa fa-question"></i> <span>Ayuda - Tutoriales</span></a></li>';
		}
		$nav .= '<li><a href="escritorio"><i class="fa fa-laptop"></i> <span>Escritorio</span></a></li>';
		$nav .= '<li><a href="usuarios/rol/modal_seleccionar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-refresh"></i> <span>Selección de Rol</span></a></li>';
		if (empty($rol)) {
			return $nav;
		}
		switch ($rol->codigo) {
			case ROL_ADMIN:
				$nav .= '<li><a href="usuario_rol/modal_simular" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-refresh"></i> <span>Simular Rol</span></a></li>';
				$nav .= '<li><a href="escuela/modal_seleccionar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> <span>Ver Escuela</span></a></li>';
				$nav .= '<li><a href="escuela/listar"><i class="fa fa-building"></i> <span>Escuelas</span></a></li>';
				$nav .= '<li><a href="carrera/listar/"><i class="fa fa-building"></i> <span>Carreras</span></a></li>';
				$nav .= '<li><a href="areas/area/listar"><i class="fa fa-building"></i> <span>Áreas</span></a></li>';
				$nav .= '<li class="treeview ' . $li_class['admin'] . '">';
				$nav .= '<a href="#"><i class="fa fa-cogs"></i><span>Administración</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="anuncio/listar"><i class="fa fa-user"></i> <span>Anuncios</span></a></li>';
				$nav .= '<li><a href="caracteristica_alumno/listar"><i class="fa fa-user"></i> <span>Características de alumnos</span></a></li>';
				$nav .= '<li><a href="caracteristica_escuela/listar"><i class="fa fa-user"></i> <span>Características de escuelas</span></a></li>';
				$nav .= '<li><a href="caracteristica_supervision/listar"><i class="fa fa-user"></i> <span>Características de supervisiones</span></a></li>';
				$nav .= '<li><a href="carrera/listar"><i class="fa fa-user"></i> <span>Carreras</span></a></li>';
				$nav .= '<li><a href="celador_concepto/listar"><i class="fa fa-user"></i> <span>Conceptos de celadores</span></a></li>';
				$nav .= '<li><a href="condicion_cargo/listar"><i class="fa fa-user"></i> <span>Condiciones de cargos</span></a></li>';
				$nav .= '<li><a href="espacio_extracurricular/listar"><i class="fa fa-user"></i> <span>Espacios extracurriculares</span></a></li>';
				$nav .= '<li><a href="materia/listar"><i class="fa fa-user"></i> <span>Materias</span></a></li>';
				$nav .= '<li><a href="modalidad/listar"><i class="fa fa-user"></i> <span>Modalidades</span></a></li>';
				$nav .= '<li><a href="novedad_tipo/listar"><i class="fa fa-user"></i> <span>Tipos de novedades</span></a></li>';
				$nav .= '<li><a href="persona/listar"><i class="fa fa-user"></i> <span>Personas</span></a></li>';
				$nav .= '<li><a href="pregunta/listar"><i class="fa fa-question-circle"></i> <span>Preguntas Frec.</span></a></li>';
				$nav .= '<li><a href="regimen/listar"><i class="fa fa-user"></i> <span>Regímenes</span></a></li>';
				$nav .= '<li><a href="regimen_lista/listar"><i class="fa fa-user"></i> <span>Lista de regímenes</span></a></li>';
				$nav .= '<li><a href="regional/listar"><i class="fa fa-user"></i> <span>Regionales</span></a></li>';
				$nav .= '<li><a href="reparticion/listar"><i class="fa fa-user"></i> <span>Reparticiones</span></a></li>';
				$nav .= '<li><a href="supervision/listar"><i class="fa fa-user"></i> <span>Supervisiones</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li class="treeview ' . $li_class['par'] . '">';
				$nav .= '<a href="#"><i class="fa fa-cogs"></i><span>Parámetros</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="sexo/listar"><i class="fa fa-user"></i> <span>Sexos</span></a></li>';
				$nav .= '<li><a href="causa_entrada/listar"><i class="fa fa-user"></i> <span>Causas de entradas</span></a></li>';
				$nav .= '<li><a href="causa_salida/listar"><i class="fa fa-user"></i> <span>Causas de salidas</span></a></li>';
				$nav .= '<li><a href="curso/listar"><i class="fa fa-user"></i> <span>Cursos</span></a></li>';
				$nav .= '<li><a href="departamento/listar"><i class="fa fa-user"></i> <span>Departamentos</span></a></li>';
				$nav .= '<li><a href="dependencia/listar"><i class="fa fa-user"></i> <span>Dependencias</span></a></li>';
				$nav .= '<li><a href="dia/listar"><i class="fa fa-user"></i> <span>Dias</span></a></li>';
				$nav .= '<li><a href="documento_tipo/listar"><i class="fa fa-user"></i> <span>Tipos de documentos</span></a></li>';
				$nav .= '<li><a href="estado_civil/listar"><i class="fa fa-user"></i> <span>Estados civiles</span></a></li>';
				$nav .= '<li><a href="evaluacion_tipo/listar"><i class="fa fa-user"></i> <span>Tipos de evaluaciones</span></a></li>';
				$nav .= '<li><a href="grupo_sanguineo/listar"><i class="fa fa-user"></i> <span>Grupos sanguíneos</span></a></li>';
				$nav .= '<li><a href="inasistencia_tipo/listar"><i class="fa fa-user"></i> <span>Tipos de inasistencias</span></a></li>';
				$nav .= '<li><a href="jurisdiccion/listar"><i class="fa fa-user"></i> <span>Jurisdicciones</span></a></li>';
				$nav .= '<li><a href="linea/listar"><i class="fa fa-user"></i> <span>Lineas</span></a></li>';
				$nav .= '<li><a href="localidad/listar"><i class="fa fa-user"></i> <span>Localidades</span></a></li>';
				$nav .= '<li><a href="nacionalidad/listar"><i class="fa fa-user"></i> <span>Nacionalidades</span></a></li>';
				$nav .= '<li><a href="nivel/listar"><i class="fa fa-user"></i> <span>Niveles</span></a></li>';
				$nav .= '<li><a href="nivel_estudio/listar"><i class="fa fa-user"></i> <span>Niveles de estudios</span></a></li>';
				$nav .= '<li><a href="obra_social/listar"><i class="fa fa-user"></i> <span>Obras sociales</span></a></li>';
				$nav .= '<li><a href="ocupacion/listar"><i class="fa fa-user"></i> <span>Ocupaciones</span></a></li>';
				$nav .= '<li><a href="parentesco_tipo/listar"><i class="fa fa-user"></i> <span>Tipos de parentescos</span></a></li>';
				$nav .= '<li><a href="prestadora/listar"><i class="fa fa-user"></i> <span>Prestadoras</span></a></li>';
				$nav .= '<li><a href="provincia/listar"><i class="fa fa-user"></i> <span>Provincias</span></a></li>';
				$nav .= '<li><a href="situacion_revista/listar"><i class="fa fa-user"></i> <span>Situaciones de revista</span></a></li>';
				$nav .= '<li><a href="planilla_tipo/listar"><i class="fa fa-user"></i> <span>Tipos de planilla</span></a></li>';
				$nav .= '<li><a href="turno/listar"><i class="fa fa-user"></i> <span>Turnos</span></a></li>';
				$nav .= '<li><a href="zona/listar"><i class="fa fa-user"></i> <span>Zonas</span></a></li>';
				$nav .= '<li><a href="autoridad_tipo/listar"><i class="fa fa-user"></i> <span>Tipo de autoridad</span></a></li>';
				$nav .= '<li><a href="calendario/listar"><i class="fa fa-user"></i> <span>Calendarios</span></a></li>';
				$nav .= '<li><a href="escuela_grupo/listar"><i class="fa fa-user"></i> <span>Grupos escuelas</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li class="treeview ' . $li_class['usuarios'] . '">';
				$nav .= '<a href="#"><i class="fa fa-user"></i><span>Usuarios</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="usuario/listar"><i class="fa fa-list"></i> <span>Listar usuarios</span></a></li>';
				$nav .= '<li><a href="usuario/permisos"><i class="fa fa-plus"></i> <span>Agregar usuario</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li class="treeview ' . $li_class['busqueda'] . '">';
				$nav .= '<a href="#"><i class="fa fa-search"></i><span>Búsqueda</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="busqueda/buscar_personal"><i class="fa fa-list"></i> <span>Buscar personal</span></a></li>';
				$nav .= '<li><a href="busqueda/buscar_alumno"><i class="fa fa-list"></i> <span>Buscar alumnos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li class="treeview ' . $li_class['suplementaria'] . '">';
				$nav .= '<a href="#"><i class="fa fa-files-o"></i><span>Suplementarias (Pruebas)</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="suplementarias/suple/listar"><i class="fa fa-user"></i> <span>Suplementarias</span></a></li>';
				$nav .= '<li><a href="suplementarias/suple_concepto/listar"><i class="fa fa-user"></i> <span>Conceptos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				if (ENVIRONMENT !== 'production') {
					$nav .= '<li><a href="tribunal/escritorio/listar/"><i class="fa fa-money"></i> <span> Referentes Tribunal Cuentas</span></a></li>';
				}
				$nav .= '<li class="treeview ' . $li_class['titulo'] . '">';
				$nav .= '<a href="#"><i class="fa fa-book"></i><span>Títulos</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="titulos/titulo_persona/listar"><i class="fa fa-user"></i> <span>Personas</span></a></li>';
				$nav .= '<li><a href="titulos/titulo/listar"><i class="fa fa-book"></i> <span>Títulos</span></a></li>';
				$nav .= '<li><a href="titulos/titulo_establecimiento/listar"><i class="fa fa-building"></i> <span>Establecimientos</span></a></li>';
				$nav .= '<li><a href="titulos/titulo_tipo/listar"><i class="fa fa-book"></i> <span>Carreras</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li class="treeview ' . $li_class['transporte'] . '">';
				$nav .= '<a href="#"><i class="fa fa-bus"></i><span>Transporte</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="abono/abono_escuela_monto/listar"><i class="fa fa-money"></i> <span>Montos Escuelas</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
			case ROL_CONSULTA:
				$nav .= '<li><a href="escuela/modal_seleccionar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> <span>Ver Escuela</span></a></li>';
				$nav .= '<li><a href="escuela/listar"><i class="fa fa-home"></i> <span>Escuelas</span></a></li>';
				$nav .= '<li><a href="areas/area/listar"><i class="fa fa-building"></i> <span>Áreas</span></a></li>';
				$nav .= '<li><a href="persona/listar"><i class="fa fa-user"></i> <span>Personas</span></a></li>';
				$nav .= '<li class="treeview ' . $li_class['busqueda'] . '">';
				$nav .= '<a href="#"><i class="fa fa-search"></i><span>Búsqueda</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="busqueda/buscar_personal"><i class="fa fa-list"></i> <span>Buscar personal</span></a></li>';
				$nav .= '<li><a href="busqueda/buscar_alumno"><i class="fa fa-list"></i> <span>Buscar alumnos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
			case ROL_DIR_ESCUELA:
			case ROL_SDIR_ESCUELA:
			case ROL_SEC_ESCUELA:
				$escuela = explode(' ', $rol->entidad)[0];
				if ((substr($escuela, 0, 1) >= '0' && substr($escuela, 0, 1) <= '9') || substr($escuela, 0, 1) === 'T') {
					$nav .= '<li><a class="text-green" href="acuerdo_zona/escuela/recibir/' . $rol->entidad_id . '"><i class="fa fa-folder-open-o"></i><span>Acuerdo Zona</span></a></li>';
				}
				$nav .= '<li class="treeview active">';
				$nav .= '<a href="#"><i class="fa fa-home"></i><span>Esc. ' . explode(' ', $rol->entidad)[0] . '</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="escuela/ver/' . $rol->entidad_id . '"><i class="fa fa-home"></i> <span>Escuela</span></a></li>';
				$nav .= '<li><a href="anexo/listar/' . $rol->entidad_id . '"><i class="fa fa-home"></i> <span>Anexos</span></a></li>';
				$nav .= '<li><a href="escuela_carrera/listar/' . $rol->entidad_id . '"><i class="fa fa-book"></i> <span>Carreras</span></a></li>';
				$nav .= '<li><a href="division/listar/' . $rol->entidad_id . '"><i class="fa fa-tasks"></i> <span>Cursos y Divisiones</span></a></li>';
				$nav .= '<li><a href="cargo/listar/' . $rol->entidad_id . '"><i class="fa fa-users"></i> <span>Cargos</span></a></li>';
				$nav .= '<li><a href="datos_personal/listar/' . $rol->entidad_id . '"><i class="fa fa-users"></i> <span>Datos personal</span></a></li>';
				$nav .= '<li><a href="servicio/listar/' . $rol->entidad_id . '"><i class="fa fa-bookmark"></i> <span>Servicios</span></a></li>';
				$nav .= '<li><a href="asisnov/index/' . $rol->entidad_id . '"><i class="fa fa-print"></i> <span>Asis Nov</span></a></li>';
				if (ENVIRONMENT !== 'production') {
					$nav .= '<li><a href="tribunal/escritorio/escuela/' . $rol->entidad_id . '"><i class="fa fa-money"></i> <span> Referentes Tribunal Cuentas</span></a></li>';
				}
				$nav .= '<li><a class="text-red" href="extintores/extintor/ver/1/' . $rol->entidad_id . '"><i class="fa fa-fire-extinguisher"></i><span>Rel. Extintores</span></a></li>';
				$nav .= '<li><a class="text-green" href="elecciones/desinfeccion/ver/2/' . $rol->entidad_id . '"><i class="fa fa-shower"></i><span>Desinfección Elecciones</span></a></li>';
				$nav .= '<li><a class="text-green" href="aprender/aprender_operativo/ver/' . $rol->entidad_id . '"><i class="fa fa-book"></i><span>Operativo Aprender 2017</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li class="treeview ' . $li_class['busqueda'] . '">';
				$nav .= '<a href="#"><i class="fa fa-search"></i><span>Búsqueda</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="busqueda/buscar_personal"><i class="fa fa-list"></i> <span>Buscar personal</span></a></li>';
				$nav .= '<li><a href="busqueda/buscar_alumno"><i class="fa fa-list"></i> <span>Buscar alumnos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li class="treeview ' . $li_class['usuarios'] . '">';
				$nav .= '<a href="#"><i class="fa fa-user"></i><span>Usuarios</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="usuario/listar"><i class="fa fa-list"></i> <span>Listar usuarios</span></a></li>';
				$nav .= '<li><a href="usuario/permisos"><i class="fa fa-plus"></i> <span>Agregar usuario</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
			case ROL_GRUPO_ESCUELA_CONSULTA:
				$nav .= '<li><a href="escuela/modal_seleccionar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> <span>Ver Escuela</span></a></li>';
				$nav .= '<li><a href="carrera/listar/"><i class="fa fa-building"></i> <span>Carreras</span></a></li>';
				$nav .= '<li><a href="escuela/listar"><i class="fa fa-building"></i> <span>Escuelas</span></a></li>';
				$nav .= '<li class="treeview ' . $li_class['usuarios'] . '">';
				$nav .= '<li class="treeview ' . $li_class['busqueda'] . '">';
				$nav .= '<a href="#"><i class="fa fa-search"></i><span>Búsqueda</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="busqueda/buscar_personal"><i class="fa fa-list"></i> <span>Buscar personal</span></a></li>';
				$nav .= '<li><a href="busqueda/buscar_alumno"><i class="fa fa-list"></i> <span>Buscar alumnos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
			case ROL_GRUPO_ESCUELA:
			case ROL_LINEA:
				$nav .= '<li><a href="escuela/modal_seleccionar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> <span>Ver Escuela</span></a></li>';
				$nav .= '<li><a href="carrera/listar/"><i class="fa fa-building"></i> <span>Carreras</span></a></li>';
				$nav .= '<li><a href="escuela/listar"><i class="fa fa-building"></i> <span>Escuelas</span></a></li>';
				$nav .= '<li class="treeview ' . $li_class['usuarios'] . '">';
				$nav .= '<a href="#"><i class="fa fa-user"></i><span>Usuarios</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="usuario/listar"><i class="fa fa-list"></i> <span>Listar usuarios</span></a></li>';
				$nav .= '<li><a href="usuario/permisos"><i class="fa fa-plus"></i> <span>Agregar usuario</span></a></li>';
				$nav .= '</ul>';
				$nav .= '<li class="treeview ' . $li_class['busqueda'] . '">';
				$nav .= '<a href="#"><i class="fa fa-search"></i><span>Búsqueda</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="busqueda/buscar_personal"><i class="fa fa-list"></i> <span>Buscar personal</span></a></li>';
				$nav .= '<li><a href="busqueda/buscar_alumno"><i class="fa fa-list"></i> <span>Buscar alumnos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
			case ROL_PRIVADA:
			case ROL_SEOS:
				$nav .= '<li><a href="escuela/modal_seleccionar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> <span>Ver Escuela</span></a></li>';
				$nav .= '<li><a href="escuela/listar"><i class="fa fa-building"></i> <span>Escuelas</span></a></li>';
				$nav .= '<li class="treeview ' . $li_class['usuarios'] . '">';
				$nav .= '<a href="#"><i class="fa fa-user"></i><span>Usuarios</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="usuario/listar"><i class="fa fa-list"></i> <span>Listar usuarios</span></a></li>';
				$nav .= '<li><a href="usuario/permisos"><i class="fa fa-plus"></i> <span>Agregar usuario</span></a></li>';
				$nav .= '</ul>';
				$nav .= '<li class="treeview ' . $li_class['busqueda'] . '">';
				$nav .= '<a href="#"><i class="fa fa-search"></i><span>Búsqueda</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="busqueda/buscar_personal"><i class="fa fa-list"></i> <span>Buscar personal</span></a></li>';
				$nav .= '<li><a href="busqueda/buscar_alumno"><i class="fa fa-list"></i> <span>Buscar alumnos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
			case ROL_CONSULTA_LINEA:
			case ROL_REGIONAL:
				$nav .= '<li><a href="escuela/modal_seleccionar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> <span>Ver Escuela</span></a></li>';
				$nav .= '<li><a href="escuela/listar"><i class="fa fa-building"></i> <span>Escuelas</span></a></li>';
				$nav .= '<li class="treeview ' . $li_class['busqueda'] . '">';
				$nav .= '<a href="#"><i class="fa fa-search"></i><span>Búsqueda</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="busqueda/buscar_personal"><i class="fa fa-list"></i> <span>Buscar personal</span></a></li>';
				$nav .= '<li><a href="busqueda/buscar_alumno"><i class="fa fa-list"></i> <span>Buscar alumnos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
			case ROL_SUPERVISION:
				$nav .= '<li><a href="escuela/modal_seleccionar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> <span>Ver Escuela</span></a></li>';
				$nav .= '<li><a href="escuela/listar"><i class="fa fa-building"></i> <span>Escuelas</span></a></li>';
				$nav .= '<li><a href="reportes/listar"><i class="fa fa-file-excel-o"></i> <span>Reportes</span></a></li>';
				$nav .= '<li><a href="consultas/reportes_estaticos/escritorio"><i class="fa fa-file-excel-o"></i><span>Reportes estáticos</span></a></li>';
				$nav .= '<li class="treeview ' . $li_class['busqueda'] . '">';
				$nav .= '<a href="#"><i class="fa fa-search"></i><span>Búsqueda</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="busqueda/buscar_personal"><i class="fa fa-list"></i> <span>Buscar personal</span></a></li>';
				$nav .= '<li><a href="busqueda/buscar_alumno"><i class="fa fa-list"></i> <span>Buscar alumnos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
			case ROL_AREA:
				$nav .= '<li class="treeview active">';
				$nav .= '<a href="#"><i class="fa fa-home"></i><span style="overflow-x:hidden;">Área</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="areas/area/ver/' . $rol->entidad_id . '"><i class="fa fa-home"></i> <span>Ver</span></a></li>';
				$nav .= '<li><a href="areas/personal/listar/' . $rol->entidad_id . '"><i class="fa fa-users"></i> <span>Personal</span></a></li>';
				$nav .= '<li><a href="areas/area/listar/' . $rol->entidad_id . '"><i class="fa fa-home"></i> <span>Sub Áreas</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li class="treeview ' . $li_class['busqueda'] . '">';
				$nav .= '<a href="#"><i class="fa fa-search"></i><span>Búsqueda</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="busqueda/buscar_personal"><i class="fa fa-list"></i> <span>Buscar personal</span></a></li>';
				$nav .= '<li><a href="busqueda/buscar_alumno"><i class="fa fa-list"></i> <span>Buscar alumnos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
			case ROL_JEFE_LIQUIDACION:
				$nav .= '<li class="treeview ' . $li_class['suplementaria'] . '">';
				$nav .= '<a href="#"><i class="fa fa-files-o"></i><span>Suplementarias (Pruebas)</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="suplementarias/suple/listar"><i class="fa fa-user"></i> <span>Suplementarias</span></a></li>';
				$nav .= '<li><a href="suplementarias/suple_concepto/listar"><i class="fa fa-user"></i> <span>Conceptos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li><a href="escuela/modal_seleccionar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> <span>Ver Escuela</span></a></li>';
				$nav .= '<li><a href="escuela/listar"><i class="fa fa-building"></i> <span>Escuelas</span></a></li>';
				$nav .= '<li><a href="areas/area/listar"><i class="fa fa-building"></i> <span>Áreas</span></a></li>';
				$nav .= '<li class="treeview ' . $li_class['usuarios'] . '">';
				$nav .= '<a href="#"><i class="fa fa-user"></i><span>Usuarios</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="usuario/listar"><i class="fa fa-list"></i> <span>Listar usuarios</span></a></li>';
				$nav .= '<li><a href="usuario/permisos"><i class="fa fa-plus"></i> <span>Agregar usuario</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li class="treeview ' . $li_class['busqueda'] . '">';
				$nav .= '<a href="#"><i class="fa fa-search"></i><span>Búsqueda</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="busqueda/buscar_personal"><i class="fa fa-list"></i> <span>Buscar personal</span></a></li>';
				$nav .= '<li><a href="busqueda/buscar_alumno"><i class="fa fa-list"></i> <span>Buscar alumnos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
			case ROL_LIQUIDACION:
				$nav .= '<li><a href="escuela/modal_seleccionar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> <span>Ver Escuela</span></a></li>';
				$nav .= '<li><a href="escuela/listar"><i class="fa fa-building"></i> <span>Escuelas</span></a></li>';
				$nav .= '<li><a href="areas/area/listar"><i class="fa fa-building"></i> <span>Áreas</span></a></li>';
				$nav .= '<li><a href="suplementarias/suple/listar"><i class="fa fa-files-o"></i> <span>Suplementarias (Pruebas)</span></a></li>';
				$nav .= '<li class="treeview ' . $li_class['usuarios'] . '">';
				$nav .= '<a href="#"><i class="fa fa-user"></i><span>Usuarios</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="usuario/listar"><i class="fa fa-list"></i> <span>Listar usuarios</span></a></li>';
				$nav .= '<li><a href="usuario/permisos"><i class="fa fa-plus"></i> <span>Agregar usuario</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li class="treeview ' . $li_class['busqueda'] . '">';
				$nav .= '<a href="#"><i class="fa fa-search"></i><span>Búsqueda</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="busqueda/buscar_personal"><i class="fa fa-list"></i> <span>Buscar personal</span></a></li>';
				$nav .= '<li><a href="busqueda/buscar_alumno"><i class="fa fa-list"></i> <span>Buscar alumnos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
			case ROL_ESCUELA_ALUM:
				$nav .= '<li class="treeview active">';
				$nav .= '<a href="#"><i class="fa fa-home"></i><span>Esc. ' . explode(' ', $rol->entidad)[0] . '</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="escuela/ver/' . $rol->entidad_id . '"><i class="fa fa-home"></i> <span>Escuela</span></a></li>';
				$nav .= '<li><a href="division/listar/' . $rol->entidad_id . '"><i class="fa fa-tasks"></i> <span>Cursos y Divisiones</span></a></li>';
				$nav .= '<li><a href="alumno/listar/' . $rol->entidad_id . '"><i class="fa fa-users"></i> <span>Alumnos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li class="treeview ' . $li_class['busqueda'] . '">';
				$nav .= '<a href="#"><i class="fa fa-search"></i><span>Búsqueda</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="busqueda/buscar_personal"><i class="fa fa-list"></i> <span>Buscar personal</span></a></li>';
				$nav .= '<li><a href="busqueda/buscar_alumno"><i class="fa fa-list"></i> <span>Buscar alumnos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
			case ROL_ESCUELA_CAR:
				$nav .= '<li class="treeview active">';
				$nav .= '<a href="#"><i class="fa fa-home"></i><span>Esc. ' . explode(' ', $rol->entidad)[0] . '</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="escuela/ver/' . $rol->entidad_id . '"><i class="fa fa-home"></i> <span>Escuela</span></a></li>';
				$nav .= '<li><a href="alumno/listar/' . $rol->entidad_id . '"><i class="fa fa-users"></i> <span>Alumnos</span></a></li>';
				$nav .= '<li><a href="cargo/listar/' . $rol->entidad_id . '"><i class="fa fa-users"></i> <span>Cargos</span></a></li>';
				$nav .= '<li><a href="datos_personal/listar/' . $rol->entidad_id . '"><i class="fa fa-users"></i> <span>Datos personal</span></a></li>';
				$nav .= '<li><a href="servicio/listar/' . $rol->entidad_id . '"><i class="fa fa-bookmark"></i> <span>Servicios</span></a></li>';
				$nav .= '<li><a href="asisnov/index/' . $rol->entidad_id . '"><i class="fa fa-print"></i> <span>Asis Nov</span></a></li>';
				$nav .= '<li><a class="text-green" href="elecciones/desinfeccion/ver/' . $rol->entidad_id . '"><i class="fa fa-shower"></i><span>Desinfección Elecciones</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li class="treeview ' . $li_class['busqueda'] . '">';
				$nav .= '<a href="#"><i class="fa fa-search"></i><span>Búsqueda</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="busqueda/buscar_personal"><i class="fa fa-list"></i> <span>Buscar personal</span></a></li>';
				$nav .= '<li><a href="busqueda/buscar_alumno"><i class="fa fa-list"></i> <span>Buscar alumnos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li><a class="text-green" href="elecciones/desinfeccion/ver/' . $rol->entidad_id . '"><i class="fa fa-shower"></i><span>Desinfección Elecciones</span></a></li>';
				break;
			case ROL_USI:
				$nav .= '<li><a href="usuario_rol/modal_simular" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-refresh"></i> <span>Simular Rol</span></a></li>';
				$nav .= '<li><a href="escuela/modal_seleccionar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> <span>Ver Escuela</span></a></li>';
				$nav .= '<li><a href="escuela/listar"><i class="fa fa-building"></i> <span>Escuelas</span></a></li>';
				$nav .= '<li><a href="carrera/listar/"><i class="fa fa-building"></i> <span>Carreras</span></a></li>';
				$nav .= '<li><a href="areas/area/listar"><i class="fa fa-building"></i> <span>Áreas</span></a></li>';
				$nav .= '<li><a href="pregunta/listar"><i class="fa fa-question-circle"></i> <span>Preguntas Frec.</span></a></li>';
				$nav .= '<li class="treeview ' . $li_class['usuarios'] . '">';
				$nav .= '<a href="#"><i class="fa fa-user"></i><span>Usuarios</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="usuario/listar"><i class="fa fa-list"></i> <span>Listar usuarios</span></a></li>';
				$nav .= '<li><a href="usuario/permisos"><i class="fa fa-plus"></i> <span>Agregar usuario</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li class="treeview ' . $li_class['busqueda'] . '">';
				$nav .= '<a href="#"><i class="fa fa-search"></i><span>Búsqueda</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="busqueda/buscar_personal"><i class="fa fa-list"></i> <span>Buscar personal</span></a></li>';
				$nav .= '<li><a href="busqueda/buscar_alumno"><i class="fa fa-list"></i> <span>Buscar alumnos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				$nav .= '<li class="treeview ' . $li_class['suplementaria'] . '">';
				$nav .= '<a href="#"><i class="fa fa-files-o"></i><span>Suplementarias (Pruebas)</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="suplementarias/suple/listar"><i class="fa fa-user"></i> <span>Suplementarias</span></a></li>';
				$nav .= '<li><a href="suplementarias/suple_concepto/listar"><i class="fa fa-user"></i> <span>Conceptos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
			case ROL_MODULO:
				switch ($rol->entidad_id) {
					case ROL_MODULO_TITULOS:
						break;
					case ROL_MODULO_LEER_ESCRIBIR:
						$nav .= '<li><a href="operativo_evaluar/evaluar_operativo/listar_escuelas"><i class="fa fa-laptop"></i> <span>Operativo Leer y Escribir</span></a></li>';
						break;
					case ROL_MODULO_OPERATIVO_APRENDER:
					case ROL_MODULO_CONSULTA_TRAYECTORIA:
					case ROL_MODULO_REFERENTES_TRIBUNAL_CUENTAS:
					case ROL_MODULO_COMEDOR:
						break;
					case ROL_MODULO_ADMINISTRADOR_DE_JUNTAS:
						$nav .= '<li class="treeview ' . $li_class['usuarios'] . '">';
						$nav .= '<a href="#"><i class="fa fa-user"></i><span>Usuarios</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
						$nav .= '<ul class="treeview-menu">';
						$nav .= '<li><a href="usuario/listar"><i class="fa fa-list"></i> <span>Listar usuarios</span></a></li>';
						$nav .= '<li><a href="usuario/permisos"><i class="fa fa-plus"></i> <span>Agregar usuario</span></a></li>';
						$nav .= '</ul>';
						$nav .= '</li>';
						$nav .= '<li class="treeview ' . $li_class['juntas'] . '">';
						$nav .= '<a href="#"><i class="fa fa-book"></i><span>Juntas</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
						$nav .= '<ul class="treeview-menu">';
						$nav .= '<li><a href="juntas/escritorio/listar_personas"><i class="fa fa-book"></i> <span>Lista de Personas</span></a></li>';
						$nav .= '<li><a href="juntas/titulo/listar"><i class="fa fa-book"></i> <span>Títulos</span></a></li>';
						$nav .= '<li><a href="juntas/fechas_limite/ver"><i class="fa fa-hourglass-half"></i> <span>Fechas Límite</span></a></li>';
						$nav .= '</ul>';
						$nav .= '</li>';
						break;
				}
				break;
			case ROL_TITULO:
				$nav .= '<li class="treeview ' . $li_class['titulo'] . '">';
				$nav .= '<a href="#"><i class="fa fa-book"></i><span>Títulos</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="titulos/titulo_persona/listar"><i class="fa fa-user"></i> <span>Personas</span></a></li>';
				$nav .= '<li><a href="titulos/titulo/listar"><i class="fa fa-book"></i> <span>Títulos</span></a></li>';
				$nav .= '<li><a href="titulos/titulo_establecimiento/listar"><i class="fa fa-building"></i> <span>Establecimientos</span></a></li>';
				$nav .= '<li><a href="titulos/titulo_tipo/listar"><i class="fa fa-book"></i> <span>Carreras</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
			case ROL_JUNTAS:
				$nav .= '<li class="treeview ' . $li_class['juntas'] . '">';
				$nav .= '<a href="#"><i class="fa fa-book"></i><span>Juntas</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="juntas/escritorio/listar_personas"><i class="fa fa-book"></i> <span>Lista de Personas</span></a></li>';
				$nav .= '<li><a href="juntas/alertas/listar_inscriptos"><i class="fa fa-book"></i> <span>Lista de Inscriptos</span></a></li>';
				$nav .= '<li><a href="juntas/alertas/listar_vacantes"><i class="fa fa-building"></i> <span>Lista de Escuelas</span></a></li>';
				$nav .= '<li><a href="juntas/titulo/listar"><i class="fa fa-book"></i> <span>Títulos</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
				case ROL_MODULO_TRANSPORTE:
				$nav .= '<li class="treeview ' . $li_class['transporte'] . '">';
				$nav .= '<a href="#"><i class="fa fa-bus"></i><span>Transporte</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
				$nav .= '<ul class="treeview-menu">';
				$nav .= '<li><a href="abono/abono_escuela_monto/listar"><i class="fa fa-user"></i> <span>Montos Escuelas</span></a></li>';
				$nav .= '</ul>';
				$nav .= '</li>';
				break;
			default:
		}
		if (in_array($rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_CONSULTA, ROL_LINEA, ROL_CONSULTA_LINEA, ROL_PRIVADA, ROL_SEOS, ROL_DIR_ESCUELA))) {
			$nav .= '<li class="treeview ' . $li_class['reportes'] . '">';
			$nav .= '<a href="#"><i class="fa fa-file-excel-o" aria-hidden="true"></i><span>Reportes</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
			$nav .= '<ul class="treeview-menu">';
			if (in_array($rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_CONSULTA, ROL_LINEA, ROL_CONSULTA_LINEA, ROL_PRIVADA, ROL_SEOS))) {
				$nav .= '<li><a href="consultas/reportes/listar_guardados"><i class="fa fa-file-excel-o"></i><span>Reportes dinámicos</span></a></li>';
			}
			if (in_array($rol->codigo, array(ROL_ADMIN, ROL_LINEA, ROL_SUPERVISION, ROL_USI,ROL_CONSULTA_LINEA))) {
				$nav .= '<li><a href="consultas/reportes_estaticos/escritorio"><i class="fa fa-file-excel-o"></i><span>Reportes estáticos</span></a></li>';
			}
			if (in_array($rol->codigo, array(ROL_ADMIN, ROL_CONSULTA, ROL_DIR_ESCUELA, ROL_USI))) {
				$nav .= '<li><a href="rrhh/listar"><i class="fa fa-file-excel-o"></i><span>ANEXO 1 - Celadores</span></a></li>';
			}
			$nav .= '</ul>';
			$nav .= '</li>';
		}
		return $nav;
	}
}

if (!function_exists('load_permisos_escritorio_usuario')) {

	function load_permisos_escritorio_usuario($rol) {
		$accesos = '';
		$accesos .= '<ul class="ds-btn">';
		switch ($rol->codigo) {
			case ROL_ADMIN:
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="escritorio" title="Escritorio"><i class="fa fa-laptop fa-2x pull-left"></i><span>Escritorio<br><small>Volver a escritorio</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="escuela/listar" title="Escuelas"><i class="fa fa-home fa-2x pull-left"></i><span>Escuelas<br><small>Listado de escuelas</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="areas/area/listar" title="Áreas"><i class="fa fa-user fa-2x pull-left"></i><span>Áreas<br><small>Listado de áreas</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="persona/listar" title="Personas"><i class="fa fa-user fa-2x pull-left"></i><span>Personas<br><small>Listado de personas</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="usuario/listar" title="Usuarios"><i class="fa fa-user fa-2x pull-left"></i><span>Usuarios<br><small>Listado de usuarios</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="carrera/listar" title="Carreras"><i class="fa fa-book fa-2x pull-left"></i><span>Carreras<br><small>Listado de carreras</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="regional/listar" title="Regionales"><i class="fa fa-home fa-2x pull-left"></i><span>Regionales<br><small>Listado de regionales</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="reparticion/listar" title="Reparticiones"><i class="fa fa-home fa-2x pull-left"></i><span>Reparticiones<br><small>Listado de reparticiones</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="supervision/listar" title="Supervisiones"><i class="fa fa-home fa-2x pull-left"></i><span>Supervisiones<br><small>Listado de supervisiones</small></span></a></li>';
				break;
			case ROL_USI:
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="escritorio" title="Escritorio"><i class="fa fa-laptop fa-2x pull-left"></i><span>Escritorio<br><small>Volver a escritorio</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="escuela/listar" title="Escuelas"><i class="fa fa-home fa-2x pull-left"></i><span>Escuelas<br><small>Listado de escuelas</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="areas/area/listar" title="Áreas"><i class="fa fa-user fa-2x pull-left"></i><span>Áreas<br><small>Listado de áreas</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="persona/listar" title="Personas"><i class="fa fa-user fa-2x pull-left"></i><span>Personas<br><small>Listado de personas</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="usuario/listar" title="Usuarios"><i class="fa fa-user fa-2x pull-left"></i><span>Usuarios<br><small>Listado de usuarios</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="carrera/listar" title="Carreras"><i class="fa fa-book fa-2x pull-left"></i><span>Carreras<br><small>Listado de carreras</small></span></a></li>';
				break;
			case ROL_JEFE_LIQUIDACION:
			case ROL_LIQUIDACION:
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="escuela/listar" title="Escuelas"><i class="fa fa-home fa-2x pull-left"></i><span>Escuelas<br><small>Listado de escuelas</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="areas/area/listar" title="Áreas"><i class="fa fa-building fa-2x pull-left"></i><span>Áreas<br><small>Listado de áreas</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="persona/listar" title="Personas"><i class="fa fa-user fa-2x pull-left"></i><span>Personas<br><small>Listado de personas</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="carrera/listar" title="Carreras"><i class="fa fa-book fa-2x pull-left"></i><span>Carreras<br><small>Listado de carreras</small></span></a></li>';
				break;
		}
		$accesos .= '</ul>';
		return $accesos;
	}
}

if (!function_exists('load_permisos_escritorio_administracion')) {

	function load_permisos_escritorio_administracion($rol) {
		$accesos = '';
		$accesos .= '<ul class="ds-btn">';
		switch ($rol->codigo) {
			case ROL_ADMIN:
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="caracteristica_escuela/listar" title="Características de escuelas"><i class="fa fa-user fa-2x pull-left"></i><span>Características de escuelas<br><small>Listado de características de escuelas</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="caracteristica_alumno/listar" title="Características de alumnos"><i class="fa fa-user fa-2x pull-left"></i><span>Características de alumnos<br><small>Listado de características de alumnos</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="celador_concepto/listar" title="Conceptos de celadores"><i class="fa fa-user fa-2x pull-left"></i><span>Conceptos de celadores<br><small>Listado de conceptos de celadores</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="condicion_cargo/listar" title="Condiciones de cargos"><i class="fa fa-user fa-2x pull-left"></i><span>Condiciones de cargos<br><small>Listado de condiciones de cargos</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="regimen/listar" title="Regímenes"><i class="fa fa-user fa-2x pull-left"></i><span>Regímenes<br><small>Listado de regímenes</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="sexo/listar" title="Sexos"><i class="fa fa-user fa-2x pull-left"></i><span>Sexos<br><small>Listado de sexos</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="curso/listar" title="Cursos"><i class="fa fa-user fa-2x pull-left"></i><span>Cursos<br><small>Listado de cursos</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="departamento/listar" title="Departamentos"><i class="fa fa-user fa-2x pull-left"></i><span>Departamentos<br><small>Listado de departamentos</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="dependencia/listar" title="Dependencias"><i class="fa fa-user fa-2x pull-left"></i><span>Dependencias<br><small>Listado de dependencias</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="dia/listar" title="Dias"><i class="fa fa-user fa-2x pull-left"></i><span>Dias<br><small>Listado de dias</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="documento_tipo/listar" title="Tipos de documentos"><i class="fa fa-user fa-2x pull-left"></i><span>Tipos de documentos<br><small>Listado de tipos de documentos</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="estado_civil/listar" title="Estados civiles"><i class="fa fa-user fa-2x pull-left"></i><span>Estados civiles<br><small>Listado de estados civiles</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="grupo_sanguineo/listar" title="Grupos sanguíneos"><i class="fa fa-user fa-2x pull-left"></i><span>Grupos sanguíneos<br><small>Listado de grupos sanguíneos</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="jurisdiccion/listar" title="Jurisdicciones"><i class="fa fa-user fa-2x pull-left"></i><span>Jurisdicciones<br><small>Listado de jurisdicciones</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="linea/listar" title="Lineas"><i class="fa fa-user fa-2x pull-left"></i><span>Lineas<br><small>Listado de lineas</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="localidad/listar" title="Localidades"><i class="fa fa-user fa-2x pull-left"></i><span>Localidades<br><small>Listado de localidades</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="materia/listar" title="Materias"><i class="fa fa-user fa-2x pull-left"></i><span>Materias<br><small>Listado de materias</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="nacionalidad/listar" title="Nacionalidades"><i class="fa fa-user fa-2x pull-left"></i><span>Nacionalidades<br><small>Listado de nacionalidades</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="nivel/listar" title="Niveles"><i class="fa fa-user fa-2x pull-left"></i><span>Niveles<br><small>Listado de niveles</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="nivel_estudio/listar" title="Niveles de estudios"><i class="fa fa-user fa-2x pull-left"></i><span>Niveles de estudios<br><small>Listado de niveles de estudios</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="novedad_tipo/listar" title="Tipos de novedades"><i class="fa fa-user fa-2x pull-left"></i><span>Tipos de novedades<br><small>Listado de tipos de novedades</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="obra_social/listar" title="Obras sociales"><i class="fa fa-user fa-2x pull-left"></i><span>Obras sociales<br><small>Listado de obras sociales</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="ocupacion/listar" title="Ocupaciones"><i class="fa fa-user fa-2x pull-left"></i><span>Ocupaciones<br><small>Listado de ocupaciones</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="prestadora/listar" title="Prestadoras"><i class="fa fa-user fa-2x pull-left"></i><span>Prestadoras<br><small>Listado de prestadoras</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="provincia/listar" title="Provincias"><i class="fa fa-user fa-2x pull-left"></i><span>Provincias<br><small>Listado de provincias</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="situacion_revista/listar" title="Situaciones de revista"><i class="fa fa-user fa-2x pull-left"></i><span>Situaciones de revista<br><small>Listado de situaciones de revista</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="turno/listar" title="Turnos"><i class="fa fa-user fa-2x pull-left"></i><span>Turnos<br><small>Listado de turnos</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="zona/listar" title="Zonas"><i class="fa fa-user fa-2x pull-left"></i><span>Zonas<br><small>Listado de zonas</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="causa_salida/listar" title="Causas de salidas"><i class="fa fa-user fa-2x pull-left"></i><span>Causas de salidas<br><small>Listado de causas de salidas</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="evaluacion_tipo/listar" title="Tipos de evaluaciones"><i class="fa fa-user fa-2x pull-left"></i><span>Tipos de evaluaciones<br><small>Listado de tipos de evaluaciones</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="inasistencia_tipo/listar" title="Tipos de inasistencias"><i class="fa fa-user fa-2x pull-left"></i><span>Tipos de inasistencias<br><small>Listado de tipos de inasistencias</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="parentesco_tipo/listar" title="Tipos de parentescos"><i class="fa fa-user fa-2x pull-left"></i><span>Tipos de parentescos<br><small>Listado de tipos de parentescos</small></span></a></li>';
				$accesos .= '<li><a class="btn btn-lg btn-primary" href="calendario/listar" title="Calendarios"><i class="fa fa-user fa-2x pull-left"></i><span>Calendarios<br><small>Calendarios</small></span></a></li>';
				break;
		}
		$accesos .= '</ul>';
		return $accesos;
	}
}

if (!function_exists('es_rol_bono')) {

	function es_rol_bono($rol) {
		if ($rol->codigo === ROL_MODULO && $rol->entidad_id === ROL_MODULO_ADMINISTRADOR_DE_JUNTAS) {
			return TRUE;
		} elseif ($rol->codigo === ROL_JUNTAS) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
/* End of file permisos_helper.php */
/* Location: ./application/helpers/permisos_helper.php */
