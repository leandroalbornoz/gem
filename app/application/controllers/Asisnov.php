<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Asisnov extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('planilla_asisnov_model');
		$this->load->model('servicio_model');
		$this->load->model('escuela_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ESCUELA_ALUM, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE_CURSADA));
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_GRUPO_ESCUELA_CONSULTA, ROL_REGIONAL, ROL_ESCUELA_ALUM))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'menu/asisnov';
	}

	public function index($escuela_id = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || ($mes != NULL && !ctype_digit($mes))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($mes) || empty(DateTime::createFromFormat('Ym', $mes))) {
			$mes = $this->planilla_asisnov_model->get_mes_actual();
			redirect("asisnov/index/$escuela_id/$mes", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Acción no autorizada');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$planillas_query = $this->planilla_asisnov_model->get(array(
			'escuela_id' => $escuela->id, 'ames' => $mes,
			'sort_by' => 'planilla_tipo_id, rectificativa desc'
		));
		$planillas = array();
		$planillas_abiertas = array();
		if (!empty($planillas_query)) {
			foreach ($planillas_query as $planilla) {
				$planillas[$planilla->planilla_tipo_id][] = $planilla;
				if (empty($planilla->fecha_cierre)) {
					$planillas_abiertas[$planilla->planilla_tipo_id][] = $planilla;
				}
			}
		}

		$this->load->model('tbcabh_model');

		$data['labels_casos'] = array('liquidacion_sin_servicio' => 'Liquidacion sin servicio', 'servicio_activo_sin_liquidacion' => 'Servicios activos sin liquidación', 'servicio_baja_con_liquidacion' => 'Servicios baja con liquidación');
		$data['explicacion_casos'] = array('liquidacion_sin_servicio' => '<ul><li>En caso de que corresponda la liquidación, por favor crear el servicio, con el régimen y la cantidad de horas que corresponda, luego por sistema se asociará el servicio a la liquidación</li><li>En caso de no corresponder la liquidación, informar la baja mediante nota a la Delegación / Liquidaciones</li></ul>', 'servicio_activo_sin_liquidacion' => '<ul><li>En caso de que el servicio ya no esté activo, deberá informar la baja al primer día del mes para subsanar la información de GEM</li><li>En caso de que el servicio corresponda a un traslado transitorio, cambio de funciones, etc. Deberá declarar la situación correspondiente en la función del servicio y la novedad. En este caso seguirá apareciendo en planilla porque es planta de la escuela que no tiene liquidación en la escuela.</li><li>En caso de que efectivamente se trate de un servicio que no está cobrando por error administrativo entonces deberá presentar la documentación pertinente en la Delegación / Liquidaciones para que se le cree una liquidación</li></ul>', 'servicio_baja_con_liquidacion' => '<ul><li>En caso de corresponder la baja, ratificar la baja mediante nota a la Delegación / Liquidaciones con la fecha correspondiente.</li><li>En caso de no corresponder la baja, informar un mensaje mediante GEM a Administrador para que se anule la misma.</li></ul>');
		if ($escuela->dependencia_id === '1') {
			$data['casos_revisar_1'] = $this->tbcabh_model->get_casos($escuela, AMES_LIQUIDACION, '1');
			$data['casos_revisar_2'] = $this->tbcabh_model->get_casos($escuela, AMES_LIQUIDACION, '2');
			for ($i = 1; $i <= 2; $i++) {
				${"cant_casos_revisar_$i"} = 0;
				foreach ($data["casos_revisar_$i"] as $casos) {
					${"cant_casos_revisar_$i"} += count($casos);
				}
				$data["cant_casos_revisar_$i"] = ${"cant_casos_revisar_$i"};
			}
		} else {
			$data["cant_casos_revisar_1"] = 0;
			$data["casos_revisar_1"] = array();
			$data["cant_casos_revisar_2"] = 0;
			$data["casos_revisar_2"] = array();
		}

		$data['planillas'] = $planillas;
		$data['planillas_abiertas'] = $planillas_abiertas;
		$data['escuela'] = $escuela;
		$data['mes_id'] = $mes;
		$data['mes'] = $this->nombres_meses[substr($mes, 4, 2)] . '\'' . substr($mes, 2, 2);
		$fecha = DateTime::createFromFormat('Ymd', $mes . '01');
		$this->load->model('alertas_model');
		$data['alertas'] = array(); //$this->alertas_model->get_alertas_escuela_planilla($escuela->id);
		$data['fecha'] = $fecha->format('d/m/Y');
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Asistencia y Novedades';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';

		$this->load_template('asisnov/asisnov_index', $data);
	}

	public function imprimir($planilla_tipo = NULL, $escuela_id = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $planilla_tipo == NULL || $escuela_id == NULL ||
			$mes == NULL || !ctype_digit($planilla_tipo) || !ctype_digit($escuela_id) || !ctype_digit($mes)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$planilla = $this->planilla_asisnov_model->get_planilla($escuela->id, $mes, $planilla_tipo);
		if (empty($planilla->fecha_cierre)) {
			$content = $this->generar_impresion($planilla);
			$watermark = 'Planilla de revisión';
		} else {
			$this->load->model('planilla_impresion_model');
			$content = $this->planilla_impresion_model->get_impresion($planilla->id);
			$watermark = '';
		}
		$content = trim_html($content);
		$content = explode('</table> </div> </div> </div>', $content);
		if (is_array($content)) {
			for ($i = 0; $i < count($content) - 1; $i++) {
				$content[$i] .= '</table> </div> </div> </div>';
			}
		}
		ini_set('memory_limit', '256M');
		set_time_limit(0);

		$this->load->helper('mpdf');
		exportarMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Planilla de Asistencia', 'Planilla de Asistencia y Novedades - Esc. "' . $escuela->nombre . '" Nº ' . $escuela->numero . ' - Juri: ' . $escuela->jurisdiccion_codigo . ' - Repa: ' . $escuela->reparticion_codigo . ' - Zona: ' . $escuela->zona_valor . ' - MES:' . (int) substr($mes, -2) . ' AÑO:' . substr($mes, 0, 4) . ($planilla->rectificativa ? " RECTIFICATIVA:$planilla->rectificativa" : ''), '|{PAGENO} de {nb}|', 'LEGAL-L', $watermark, 'I', FALSE, FALSE);
//		return $pdf->render();
	}

	public function imprimir_parcial($id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$planilla = $this->planilla_asisnov_model->get_one($id);
		if (empty($planilla)) {
			show_error('No se encontró el registro de planilla', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($planilla->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('servicio_novedad_model');
		$novedades_creadas = $this->servicio_novedad_model->get(array(
			'join' => array(
				array('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id', 'left', array('novedad_tipo.descripcion as novedad_tipo', 'novedad_tipo.articulo', 'novedad_tipo.inciso', 'novedad_tipo.novedad as novedad_tipo_novedad', 'novedad_tipo.id as novedad_tipo_id')),
				array('servicio', 'servicio.id = servicio_novedad.servicio_id', '', array('servicio.fecha_alta', 'servicio.liquidacion', 'servicio.motivo_baja', 'servicio.fecha_baja')),
				array('servicio_funcion', 'servicio_funcion.servicio_id = servicio.id AND servicio_funcion.fecha_hasta IS NULL', 'left', array('servicio_funcion.destino as funcion_destino', 'servicio_funcion.norma as funcion_norma', 'servicio_funcion.tarea as funcion_tarea', 'servicio_funcion.carga_horaria as funcion_cargahoraria', 'servicio_funcion.fecha_desde as funcion_desde')),
				array('novedad_tipo nr', 'nr.id = servicio.articulo_reemplazo_id', 'left', array('CONCAT(nr.articulo, \'-\', nr.inciso) as reemplaza_articulo, nr.descripcion_corta as reemplaza_articulo_desc')),
				array('servicio r', 'r.id = servicio.reemplazado_id', 'left', array('r.liquidacion as reemplaza_liquidacion')),
				array('persona pr', 'pr.id = r.persona_id', 'left', array('pr.cuil as reemplaza_cuil', 'CONCAT(pr.apellido, \' \', pr.nombre) as reemplaza')),
				array('funcion', 'funcion.id = servicio_funcion.funcion_id', 'left', array('COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion_detalle')),
				array('persona', 'persona.id = servicio.persona_id', 'left', array('persona.cuil', 'persona.apellido', 'persona.nombre', 'persona.fecha_nacimiento')),
				array('cargo', 'cargo.id = servicio.cargo_id', '', array('cargo.escuela_id', 'cargo.carga_horaria')),
				array('celador_concepto', 'celador_concepto.id = servicio.celador_concepto_id', 'left', array('celador_concepto.descripcion as celador_concepto')),
				array('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', '', array('condicion_cargo.descripcion as condicion_cargo')),
				array('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', '', array('regimen.codigo as regimen_codigo', 'regimen.descripcion as regimen', 'regimen_tipo_id', 'regimen.puntos')),
				array('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id', 'left', array('situacion_revista.descripcion as situacion_revista')),
				array('area', 'area.id = cargo.area_id', 'left', array('area.descripcion as area')),
				array('division', 'cargo.division_id = division.id', 'left', array('CONCAT(curso.descripcion, \' \', division.division) as division')),
				array('turno', 'turno.id = cargo.turno_id', 'left', array('turno.descripcion as turno')),
				array('turno turno2', 'turno2.id = division.turno_id', 'left', array('turno2.descripcion as turno2')),
				array('curso', 'curso.id = division.curso_id', 'left'),
				array('nivel', 'nivel.id = curso.nivel_id', 'left'),
				array('escuela', 'escuela.id = cargo.escuela_id', 'left', array('escuela.nombre as escuela', 'escuela.numero as escuela_numero')),
				array('espacio_curricular', 'cargo.espacio_curricular_id = espacio_curricular.id', 'left', array('materia.descripcion as espacio_curricular')),
				array('materia', 'espacio_curricular.materia_id = materia.id', 'left', array()),
				array('carrera', 'espacio_curricular.carrera_id = carrera.id', 'left', array('carrera.descripcion as carrera'))
			),
			'planilla_alta_id' => $planilla->id,
		));

		$novedades_eliminadas = $this->servicio_novedad_model->get(array(
			'join' => array(
				array('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id', 'left', array('novedad_tipo.descripcion as novedad_tipo', 'novedad_tipo.articulo', 'novedad_tipo.inciso', 'novedad_tipo.novedad as novedad_tipo_novedad', 'novedad_tipo.id as novedad_tipo_id')),
				array('servicio', 'servicio.id = servicio_novedad.servicio_id', '', array('servicio.liquidacion', 'servicio.motivo_baja', 'servicio.fecha_baja')),
				array('servicio_funcion', 'servicio_funcion.servicio_id = servicio.id AND servicio_funcion.fecha_hasta IS NULL', 'left', array('servicio_funcion.destino as funcion_destino', 'servicio_funcion.norma as funcion_norma', 'servicio_funcion.tarea as funcion_tarea', 'servicio_funcion.carga_horaria as funcion_cargahoraria', 'servicio_funcion.fecha_desde as funcion_desde')),
				array('funcion', 'funcion.id = servicio_funcion.funcion_id', 'left', array('COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion_detalle')),
				array('persona', 'persona.id = servicio.persona_id', 'left', array('persona.cuil', 'persona.apellido', 'persona.nombre', 'persona.fecha_nacimiento')),
				array('cargo', 'cargo.id = servicio.cargo_id', '', array('cargo.escuela_id', 'cargo.carga_horaria')),
				array('celador_concepto', 'celador_concepto.id = servicio.celador_concepto_id', 'left', array('celador_concepto.descripcion as celador_concepto')),
				array('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', '', array('condicion_cargo.descripcion as condicion_cargo')),
				array('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', '', array('regimen.codigo as regimen_codigo', 'regimen.descripcion as regimen', 'regimen_tipo_id', 'regimen.puntos')),
				array('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id', 'left', array('situacion_revista.descripcion as situacion_revista')),
				array('area', 'area.id = cargo.area_id', 'left', array('area.descripcion as area')),
				array('division', 'cargo.division_id = division.id', 'left', array('CONCAT(curso.descripcion, \' \', division.division) as division')),
				array('turno', 'turno.id = cargo.turno_id', 'left', array('turno.descripcion as turno')),
				array('turno turno2', 'turno2.id = division.turno_id', 'left', array('turno2.descripcion as turno2')),
				array('curso', 'curso.id = division.curso_id', 'left'),
				array('nivel', 'nivel.id = curso.nivel_id', 'left'),
				array('escuela', 'escuela.id = cargo.escuela_id', 'left', array('escuela.nombre as escuela', 'escuela.numero as escuela_numero')),
				array('espacio_curricular', 'cargo.espacio_curricular_id = espacio_curricular.id', 'left', array('materia.descripcion as espacio_curricular')),
				array('materia', 'espacio_curricular.materia_id = materia.id', 'left', array()),
				array('carrera', 'espacio_curricular.carrera_id = carrera.id', 'left', array('carrera.descripcion as carrera'))
			),
			'planilla_baja_id' => $planilla->id,
		));

		$autoridad = $this->escuela_model->get_autoridad($escuela->id);
		if (empty($autoridad)) {
			$this->session->set_flashdata('error', 'Debe cargar una autoridad a la escuela para cerrar la planilla');
			redirect("escuela/autoridades/$escuela->id", 'refresh');
		}

		if (isset($novedades_creadas) && !empty($novedades_creadas)) {
			$filasAltas_nuevas = array();
			$filasBajas_nuevas = array();
			$filasNovedades_nuevas = array();
			foreach ($novedades_creadas as $novedad) {
				$novedad->reemplaza_nroliq = empty($novedad->reemplaza_liquidacion) ? '' : substr($novedad->reemplaza_liquidacion, -2);
				if ($novedad->novedad_tipo_id == '1') {
					$filasAltas_nuevas[] = $novedad;
				} else if ($novedad->novedad_tipo_novedad == 'B') {
					$filasBajas_nuevas[] = $novedad;
				} else if ($novedad->novedad_tipo_novedad == 'N') {
					$filasNovedades_nuevas[] = $novedad;
				}
			}
			$data['filas']['ALTAS NUEVAS'] = $filasAltas_nuevas;
			$data['filas']['BAJAS NUEVAS'] = $filasBajas_nuevas;
			$data['filas']['NOVEDADES NUEVAS'] = $filasNovedades_nuevas;
		}

		if (isset($novedades_eliminadas) && !empty($novedades_eliminadas)) {
			$filasBajas_eliminadas = array();
			$filasNovedades_eliminadas = array();
			foreach ($novedades_eliminadas as $novedad) {
				$novedad->reemplaza_nroliq = empty($novedad->reemplaza_liquidacion) ? '' : substr($novedad->reemplaza_liquidacion, -2);
				if ($novedad->novedad_tipo_novedad == 'B') {
					$filasBajas_eliminadas[] = $novedad;
				} else if ($novedad->novedad_tipo_novedad == 'N') {
					$filasNovedades_eliminadas[] = $novedad;
				}
			}
			$data['filas']['BAJAS ELIMINADAS'] = $filasBajas_eliminadas;
			$data['filas']['NOVEDADES ELIMINADAS'] = $filasNovedades_eliminadas;
		}
		$data['autoridad'] = $autoridad;
		$data['escuela'] = $escuela;
		$data['planilla'] = $planilla;
		$data['novedades_creadas'] = $novedades_creadas;
		$data['novedades_eliminadas'] = $novedades_eliminadas;

		$content = $this->load->view('asisnov/asisnov_impresion_parcial', $data, TRUE);

		$this->load->helper('mpdf');
		if (empty($planilla->fecha_cierre)) {
			$watermark = 'Planilla de revisión';
		} else {
			$watermark = '';
		}
		exportarMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Planilla de Asistencia', 'Planilla de Asistencia y Novedades - Esc. "' . $escuela->nombre . '" Nº ' . $escuela->numero . ' - Juri: ' . $escuela->jurisdiccion_codigo . ' - Repa: ' . $escuela->reparticion_codigo . ' - Zona: ' . $escuela->zona_valor . ' - MES:' . (int) substr($planilla->ames, -2) . ' AÑO:' . substr($planilla->ames, 0, 4) . ($planilla->rectificativa ? " RECTIFICATIVA:$planilla->rectificativa" : ''), '|{PAGENO} de {nb}|', 'LEGAL-L', $watermark, 'I', FALSE, FALSE);
	}

	public function imprimir_casos($planilla_tipo = NULL, $escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $planilla_tipo == NULL || $escuela_id == NULL || !ctype_digit($planilla_tipo) || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$content = $this->generar_impresion_casos($escuela, $planilla_tipo);
		if (empty($content)) {
			$this->session->set_flashdata('message', 'No tiene casos a revisar');
			redirect("asisnov/index/$escuela_id");
		}

		$this->load->helper('mpdf');
		exportarMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Planilla de Asistencia', 'Casos a Revisar - Esc. "' . $escuela->nombre . '" Nº ' . $escuela->numero . ' - Juri: ' . $escuela->jurisdiccion_codigo . ' - Repa: ' . $escuela->reparticion_codigo . ' - Zona: ' . $escuela->zona_valor . ' - MES:' . (int) substr(AMES_LIQUIDACION, -2) . ' AÑO:' . substr(AMES_LIQUIDACION, 0, 4), '|{PAGENO} de {nb}|', 'LEGAL-L', '', 'I', FALSE, FALSE);
//		return $pdf->render();
	}

	private function generar_impresion($planilla) {
		$escuela = $this->escuela_model->get_one($planilla->escuela_id);

		if (!empty($planilla->fecha_cierre)) {
			$autoridad = $this->escuela_model->get_autoridad($escuela->id);
			if (empty($autoridad)) {
				$this->session->set_flashdata('error', 'Debe cargar una autoridad a la escuela para cerrar la planilla');
				redirect("escuela/autoridades/$escuela->id", 'refresh');
			}
			$data['directivo'] = $autoridad;
		}
		$data['planilla'] = $planilla;
		$data['escuela'] = $escuela;

		$servicios_escuela = $this->servicio_model->get(array(
			'join' => array(
				array('tbcabh', 'servicio.id=tbcabh.servicio_id AND tbcabh.vigente=' . AMES_LIQUIDACION, 'left', array('tbcabh.lsignos')),
				array('servicio_novedad alta', "alta.servicio_id = servicio.id AND alta.novedad_tipo_id=1 AND alta.ames='$planilla->ames'", 'left', array('alta.id alta_id', 'alta.dias', 'alta.obligaciones')),
				array('servicio_funcion', 'servicio_funcion.servicio_id = servicio.id AND servicio_funcion.fecha_hasta IS NULL', 'left', array('servicio_funcion.destino as funcion_destino', 'servicio_funcion.norma as funcion_norma', 'servicio_funcion.tarea as funcion_tarea', 'servicio_funcion.carga_horaria as funcion_cargahoraria', 'servicio_funcion.fecha_desde as funcion_desde')),
				array('novedad_tipo', 'novedad_tipo.id = servicio.articulo_reemplazo_id', 'left', array('CONCAT(novedad_tipo.articulo, \'-\', novedad_tipo.inciso) as reemplaza_articulo, novedad_tipo.descripcion_corta as reemplaza_articulo_desc, novedad_tipo.id as novedad_tipo_id, novedad_tipo.novedad as novedad_tipo_novedad')),
				array('servicio r', 'r.id = servicio.reemplazado_id', 'left', array('r.liquidacion as reemplaza_liquidacion')),
				array('persona pr', 'pr.id = r.persona_id', 'left', array('pr.cuil as reemplaza_cuil', 'CONCAT(pr.apellido, \' \', pr.nombre) as reemplaza')),
				array('funcion', 'funcion.id = servicio_funcion.funcion_id', 'left', array('COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion_detalle')),
				array('persona', 'persona.id = servicio.persona_id', '', array('persona.cuil', 'persona.apellido', 'persona.nombre', 'persona.fecha_nacimiento')),
				array('cargo', 'cargo.id = servicio.cargo_id', '', array('cargo.escuela_id', 'cargo.area_id', 'cargo.carga_horaria', 'cargo.regimen_id')),
				array('celador_concepto', 'celador_concepto.id = servicio.celador_concepto_id', 'left', array('celador_concepto.descripcion as celador_concepto')),
				array('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', '', array('condicion_cargo.descripcion as condicion_cargo')),
				array('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1', '', array('regimen.codigo as regimen_codigo', 'regimen.descripcion as regimen', 'regimen.regimen_tipo_id', 'regimen.puntos')),
				array('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id', '', array('situacion_revista.descripcion as situacion_revista', 'situacion_revista.planilla_tipo_id')),
				array('area', 'area.id = cargo.area_id', 'left', array('area.descripcion as area', 'area.codigo as area_codigo')),
				array('division', 'cargo.division_id = division.id', 'left', array('CONCAT(curso.descripcion, \' \', division.division) as division')),
				array('turno', 'turno.id = cargo.turno_id', 'left', array('turno.descripcion as turno')),
				array('turno turno2', 'turno2.id = division.turno_id', 'left', array('turno2.descripcion as turno2')),
				array('curso', 'curso.id = division.curso_id', 'left'),
				array('escuela', 'escuela.id = cargo.escuela_id', '', array('CONCAT(escuela.numero, \' \', escuela.nombre) as escuela', 'escuela.unidad_organizativa')),
				array('espacio_curricular', 'cargo.espacio_curricular_id = espacio_curricular.id', 'left'),
				array('materia', 'espacio_curricular.materia_id = materia.id', 'left', array('materia.descripcion as materia'))
			),
			'where' => array(
				"escuela.id=$escuela->id",
				"'$planilla->ames' >= COALESCE(DATE_FORMAT(servicio.fecha_alta,'%Y%m'),'000000')",
				"'$planilla->ames' <= COALESCE(DATE_FORMAT(DATE_ADD(servicio.fecha_baja, INTERVAL 1 MONTH),'%Y%m'),'999999')",
				array('column' => 'situacion_revista.planilla_tipo_id', 'value' => $planilla->planilla_tipo_id),
			),
			'sort_by' => 'persona.documento, servicio.liquidacion'
			)
		);

		$servicios_funcion = $this->servicio_model->get(array(
			'join' => array(
				array('servicio_funcion', 'servicio_funcion.servicio_id = servicio.id AND servicio_funcion.fecha_hasta IS NULL', 'left', array('servicio_funcion.id as servicio_funcion_id', 'servicio_funcion.destino as funcion_destino', 'servicio_funcion.norma as funcion_norma', 'servicio_funcion.tarea as funcion_tarea', 'servicio_funcion.carga_horaria as funcion_cargahoraria', 'servicio_funcion.fecha_desde as funcion_desde')),
				array('novedad_tipo', 'novedad_tipo.id = servicio.articulo_reemplazo_id', 'left', array('CONCAT(novedad_tipo.articulo, \'-\', novedad_tipo.inciso) as reemplaza_articulo, novedad_tipo.descripcion_corta as reemplaza_articulo_desc, novedad_tipo.id as novedad_tipo_id, novedad_tipo.novedad as novedad_tipo_novedad')),
				array('servicio r', 'r.id = servicio.reemplazado_id', 'left', array('r.liquidacion as reemplaza_liquidacion')),
				array('persona pr', 'pr.id = r.persona_id', 'left', array('pr.cuil as reemplaza_cuil', 'CONCAT(pr.apellido, \' \', pr.nombre) as reemplaza')),
				array('funcion', 'funcion.id = servicio_funcion.funcion_id', 'left', array('COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion_detalle')),
				array('persona', 'persona.id = servicio.persona_id', 'left', array('persona.cuil', 'persona.apellido', 'persona.nombre', 'persona.fecha_nacimiento')),
				array('cargo', 'cargo.id = servicio.cargo_id', '', array('cargo.escuela_id', 'cargo.area_id', 'cargo.carga_horaria', 'cargo.regimen_id')),
				array('celador_concepto', 'celador_concepto.id = servicio.celador_concepto_id', 'left', array('celador_concepto.descripcion as celador_concepto')),
				array('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', '', array('condicion_cargo.descripcion as condicion_cargo')),
				array('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1', '', array('regimen.codigo as regimen_codigo', 'regimen.descripcion as regimen', 'regimen.regimen_tipo_id', 'regimen.puntos')),
				array('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id', 'left', array('situacion_revista.descripcion as situacion_revista', 'situacion_revista.planilla_tipo_id')),
				array('area', 'area.id = cargo.area_id', 'left', array('area.descripcion as area', 'area.codigo as area_codigo')),
				array('division', 'cargo.division_id = division.id', 'left', array('CONCAT(curso.descripcion, \' \', division.division) as division')),
				array('turno', 'turno.id = cargo.turno_id', 'left', array('turno.descripcion as turno')),
				array('turno turno2', 'turno2.id = division.turno_id', 'left', array('turno2.descripcion as turno2')),
				array('curso', 'curso.id = division.curso_id', 'left'),
				array('nivel', 'nivel.id = curso.nivel_id', 'left'),
				array('escuela', 'escuela.id = cargo.escuela_id', 'left', array('CONCAT(escuela.numero, \' \', escuela.nombre) as escuela', 'escuela.unidad_organizativa')),
				array('espacio_curricular', 'cargo.espacio_curricular_id = espacio_curricular.id', 'left'),
				array('materia', 'espacio_curricular.materia_id = materia.id', 'left', array('materia.descripcion as materia'))
			),
			'where' => array(
				"servicio_funcion.escuela_id=$escuela->id",
				"'$planilla->ames' >= COALESCE(DATE_FORMAT(servicio.fecha_alta,'%Y%m'),'000000')",
				"'$planilla->ames' <= COALESCE(DATE_FORMAT(DATE_ADD(servicio.fecha_baja, INTERVAL 1 MONTH),'%Y%m'),'999999')",
				array('column' => 'situacion_revista.planilla_tipo_id', 'value' => $planilla->planilla_tipo_id),
			),
			'sort_by' => 'persona.documento, servicio.liquidacion'
			)
		);
		$this->load->model('servicio_novedad_model');

		$filas = array();
		$filasAltas = array();
		if (!empty($servicios_escuela)) {
			foreach ($servicios_escuela as $serv_id => $servicio) {
				$servicios_escuela[$serv_id]->nroliq = substr($servicio->liquidacion, -2);
				$servicios_escuela[$serv_id]->reemplaza_nroliq = empty($servicio->reemplaza_liquidacion) ? '' : substr($servicio->reemplaza_liquidacion, -2);
				$servicios_escuela[$serv_id]->baja_dada = (!empty($servicio->fecha_baja) && $planilla->ames === (new DateTime(substr($servicio->fecha_baja, 0, 8) . '01 +1 month'))->format('Ym')) ? 1 : 0;
				$servicios_escuela[$serv_id]->baja_continua = empty($servicio->fecha_baja);
				$servicios_escuela[$serv_id]->tbcab_id = (empty($servicio->fecha_alta) || $planilla->ames !== (new DateTime(substr($servicio->fecha_alta, 0, 8) . '01 +1 month'))->format('Ym')) ? 1 : 0;
				$servicios_escuela[$serv_id]->oblig = $servicio->regimen_tipo_id === '1' ? $servicio->puntos : $servicio->carga_horaria;
				$servicios_escuela[$serv_id]->oblig_cumplir = $servicio->regimen_tipo_id === '1' ? ($servicio->alta_id ? $servicio->dias : '30') : ($servicio->alta_id ? $servicio->obligaciones : $servicio->carga_horaria * 4);
				$novedades = $this->servicio_novedad_model->get(array(
					'servicio_id' => $servicio->id,
					'ames' => $planilla->ames,
					'join' => array(
						array('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id', 'left', array('novedad_tipo.articulo', 'novedad_tipo.inciso', 'novedad_tipo.id as novedad_tipo_id', 'novedad_tipo.novedad as novedad_tipo_novedad'))),
					'where' => array('planilla_baja_id IS NULL', 'novedad_tipo_id!=1', 'servicio_funcion_id IS NULL')
				));
				$servicios_escuela[$serv_id]->inasistencias = array();
				if (!empty($novedades)) {
					foreach ($novedades as $novedad) {
						$servicios_escuela[$serv_id]->inasistencias[] = (object) array(
								'novedad_tipo_id' => $novedad->novedad_tipo_id,
								'novedad_tipo_novedad' => $novedad->novedad_tipo_novedad,
								'dias_desde' => $novedad->fecha_desde,
								'dias_hasta' => $novedad->fecha_hasta,
								'articulo' => "$novedad->articulo-$novedad->inciso",
								'oblig_nocumplio' => ($servicio->regimen_tipo_id === '1') ? $novedad->dias : $novedad->obligaciones
						);
					}
				}

				if (!empty($servicio->fecha_alta) && $planilla->ames === (new DateTime($servicio->fecha_alta))->format('Ym')) {
					$filasAltas[] = $servicio;
				} else {
					$filas[] = $servicio;
				}
			}
		}

		$filasFuncion = array();
		if (!empty($servicios_funcion)) {
			foreach ($servicios_funcion as $serv_id => $servicio) {
				$servicios_funcion[$serv_id]->nroliq = substr($servicio->liquidacion, -2);
				$servicios_funcion[$serv_id]->reemplaza_nroliq = empty($servicio->reemplaza_liquidacion) ? '' : substr($servicio->reemplaza_liquidacion, -2);
				$servicios_funcion[$serv_id]->baja_dada = (!empty($servicio->fecha_baja) && $planilla->ames === (new DateTime(substr($servicio->fecha_baja, 0, 8) . '01 +1 month'))->format('Ym')) ? 1 : 0;
				$servicios_funcion[$serv_id]->baja_continua = empty($servicio->fecha_baja);
				$servicios_funcion[$serv_id]->tbcab_id = (empty($servicio->fecha_alta) || $planilla->ames !== (new DateTime(substr($servicio->fecha_alta, 0, 8) . '01 +1 month'))->format('Ym')) ? 1 : 0;
				$servicios_funcion[$serv_id]->oblig = $servicio->regimen_tipo_id === '1' ? $servicio->puntos : $servicio->carga_horaria;
				$servicios_funcion[$serv_id]->oblig_cumplir = $servicio->regimen_tipo_id === '1' ? '30' : $servicio->carga_horaria * 4;
				$novedades = $this->servicio_novedad_model->get(array(
					'servicio_id' => $servicio->id,
					'servicio_funcion_id' => $servicio->servicio_funcion_id,
					'ames' => $planilla->ames,
					'join' => array(
						array('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id', 'left', array('novedad_tipo.articulo', 'novedad_tipo.inciso', 'novedad_tipo.id as novedad_tipo_id', 'novedad_tipo.novedad as novedad_tipo_novedad'))),
					'where' => array('planilla_baja_id is null', 'novedad_tipo_id!=1')
				));
				$servicios_funcion[$serv_id]->inasistencias = array();
				if (!empty($novedades)) {
					foreach ($novedades as $novedad) {
						$servicios_funcion[$serv_id]->inasistencias[] = (object) array(
								'novedad_tipo_id' => $novedad->novedad_tipo_id,
								'novedad_tipo_novedad' => $novedad->novedad_tipo_novedad,
								'dias_desde' => $novedad->fecha_desde,
								'dias_hasta' => $novedad->fecha_hasta,
								'articulo' => "$novedad->articulo-$novedad->inciso",
								'oblig_nocumplio' => ($servicio->regimen_tipo_id === '1') ? $novedad->dias : $novedad->obligaciones
						);
					}
				}

				if ($servicio->escuela_id !== $planilla->escuela_id) {
					$filasFuncion[] = $servicio;
				}
			}
		}

		if ($planilla->planilla_tipo_id === '1') {
			$resumen_cargos_db = $this->planilla_asisnov_model->get_resumen_cargos($planilla);
			if (!empty($resumen_cargos_db)) {
				$cursos = array();
				$regimenes = array();
				$resumen_cargos = array();
				foreach ($resumen_cargos_db as $resumen_cargo) {
					$cursos[$resumen_cargo->curso_id] = $resumen_cargo->curso;
					$regimenes[$resumen_cargo->regimen_id] = "$resumen_cargo->codigo $resumen_cargo->regimen";
					$resumen_cargos[$resumen_cargo->curso_id][$resumen_cargo->regimen_id] = $resumen_cargo;
				}
				$data['cursos'] = $cursos;
				$data['regimenes'] = $regimenes;
				$data['resumen_cargos'] = $resumen_cargos;
			}
		}

		$data['final'] = !empty($planilla->fecha_cierre);
		$data['filas'] = $filas;
		$data['filasAltas'] = $filasAltas;
		$data['filasFuncion'] = $filasFuncion;
		$data['planilla_tipo'] = $planilla->planilla_tipo_id;
		if ($escuela->dependencia_id === '1') {
			$this->load->model('tbcabh_model');
			$data['casos'] = $this->tbcabh_model->get_casos($escuela, AMES_LIQUIDACION, $planilla->planilla_tipo_id);
			$data['planilla_tipo_id'] = $planilla->planilla_tipo_id;
			$data['view_casos'] = $this->load->view('asisnov/asisnov_impresion_casos_revisar', $data, TRUE);
//			echo $data['view_casos'];
//			exit();
		}

		return trim_html($this->load->view('asisnov/asisnov_impresion', $data, TRUE));
	}

	private function generar_impresion_casos($escuela, $planilla_tipo_id) {

		$autoridad = $this->escuela_model->get_autoridad($escuela->id);
		if (empty($autoridad)) {
			$this->session->set_flashdata('error', 'Debe cargar una autoridad a la escuela para cerrar la planilla');
			redirect("escuela/autoridades/$escuela->id", 'refresh');
		}
		$data['directivo'] = $autoridad;
		$data['escuela'] = $escuela;

		$this->load->model('tbcabh_model');
		$data['casos'] = $this->tbcabh_model->get_casos($escuela, AMES_LIQUIDACION, $planilla_tipo_id);
		$data['planilla_tipo_id'] = $planilla_tipo_id;
		return trim_html($this->load->view('asisnov/asisnov_impresion_casos_revisar', $data, TRUE));
	}

	public function cambiar_mes($escuela_id, $mes = '') {
		if (!ctype_digit($mes)) {
			redirect("asisnov/index/$escuela_id/" . date('Ym'), 'refresh');
		}
		$model = new stdClass();
		$model->fields = array(
			'mes' => array('label' => 'Mes', 'type' => 'date', 'required' => TRUE)
		);
		$this->set_model_validation_rules($model);
		if (empty($mes)) {
			$mes = $this->planilla_asisnov_model->get_mes_actual();
		}

		if ($this->form_validation->run() === TRUE) {
			$mes = $this->get_date_sql('mes', 'd/m/Y', 'Ym');
			$this->session->set_flashdata('message', 'Mes cambiado correctamente');
			redirect("asisnov/index/$escuela_id/" . $mes, 'refresh');
		}
		$data['escuela_id'] = $escuela_id;
		$data['mes'] = $mes;
		$this->load->view('asisnov/asisnov_cambiar_mes', $data);
	}

	public function modal_cerrar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$planilla = $this->planilla_asisnov_model->get(array('id' => $id));
		if (empty($planilla)) {
			$this->modal_error('No se encontró el registro a cerrar', 'Registro no encontrado');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$escuela = $this->escuela_model->get_one($planilla->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($planilla->id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$fecha_cierre = date('Y-m-d H:i:s');
			$planilla->fecha_cierre = $fecha_cierre;
			$content = $this->generar_impresion($planilla);
			if ($content !== FALSE) {
				$trans_ok = TRUE;
				$this->db->trans_begin();
				$trans_ok &= $this->planilla_asisnov_model->update(array(
					'id' => $this->input->post('id'),
					'fecha_cierre' => $fecha_cierre
					), FALSE);

				if ($trans_ok) {
					$this->load->model('planilla_impresion_model');
					$trans_ok &= $this->planilla_impresion_model->create(array(
						'planilla_id' => $planilla->id,
						'impresion' => $content
						), FALSE);
				}

				if ($trans_ok) {
					$this->load->model('servicio_novedad_model');
					$novedades_pendientes = $this->servicio_novedad_model->get(array(
						'planilla_alta_id' => $planilla->id,
						'ames' => $planilla->ames,
						'novedad_tipo_id <>' => '1',
						'where' => array("ames < DATE_FORMAT(fecha_hasta, '%Y%m')", 'planilla_baja_id IS NULL'),
					));

					if (!empty($novedades_pendientes) && $trans_ok) {
						$mes_siguiente = (new DateTime($planilla->ames . '01 +1 month'))->format('Ym');
						$planilla_siguiente_id = $this->planilla_asisnov_model->get_planilla_abierta($planilla->escuela_id, $mes_siguiente, $planilla->planilla_tipo_id);
						$trans_ok &= $planilla_siguiente_id > 0;

						if ($trans_ok) {
							foreach ($novedades_pendientes as $novedad) {
								$fecha_desde = (new DateTime($mes_siguiente . '01'));
								$fecha_hasta = (new DateTime($novedad->fecha_hasta));
								$fecha_ultimo_dia = (new DateTime($mes_siguiente . '01'));
								$dias = $novedad->fecha_hasta < $fecha_ultimo_dia->format('Y-m-t') ?
									$fecha_desde->diff($fecha_hasta)->days + 1 :
									$fecha_desde->diff($fecha_ultimo_dia)->days + 1;

								$trans_ok &= $this->servicio_novedad_model->create(array(
									'servicio_id' => $novedad->servicio_id,
									'ames' => $mes_siguiente,
									'novedad_tipo_id' => $novedad->novedad_tipo_id,
									'fecha_desde' => $novedad->fecha_desde,
									'fecha_hasta' => $novedad->fecha_hasta,
									'dias' => $dias,
									'obligaciones' => 'NULL',
									'estado' => 'Pendiente',
									'planilla_alta_id' => $planilla_siguiente_id,
									'origen_id' => $novedad->id
									), FALSE);
							}
						}
					}
				}
			}
			if ($content !== FALSE && $this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->planilla_asisnov_model->get_msg());
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al intentar cerrar.';
				if ($this->planilla_asisnov_model->get_error())
					$errors .= '<br>' . $this->planilla_asisnov_model->get_error();
				if ($this->planilla_impresion_model->get_error())
					$errors .= '<br>' . $this->planilla_impresion_model->get_error();
				$this->session->set_flashdata('error', $errors);
			}
			redirect("asisnov/index/$planilla->escuela_id/$planilla->ames", 'refresh');
		}
		$data['planilla'] = $planilla;
		$data['txt_btn'] = 'Cerrar';
		$data['title'] = 'Cerrar planilla de asistencia y novedades';
		$this->load->view('asisnov/asisnov_modal_cerrar', $data);
	}

	public function modal_rectificativa($planilla_tipo = NULL, $escuela_id = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $planilla_tipo == NULL || $escuela_id == NULL ||
			$mes == NULL || !ctype_digit($planilla_tipo) || !ctype_digit($escuela_id) || !ctype_digit($mes)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}
		$planilla = $this->planilla_asisnov_model->get_planilla($escuela_id, $mes, $planilla_tipo);

		if (isset($_POST) && !empty($_POST)) {
			$error_msg = FALSE;
			$trans_ok = TRUE;

			if (isset($planilla->id)) {
				if ($planilla->id !== $this->input->post('id')) {
					$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
					return;
				}
				if (!empty($planilla->fecha_cierre)) {
					$trans_ok &= $this->planilla_asisnov_model->create(array(
						'escuela_id' => $planilla->escuela_id,
						'ames' => $planilla->ames,
						'planilla_tipo_id' => $planilla->planilla_tipo_id,
						'fecha_creacion' => date('Y-m-d H:i:s'),
						'rectificativa' => $planilla->rectificativa + 1
					));
				} else {
					$trans_ok = FALSE;
					$this->planilla_asisnov_model->get_error();
					$error_msg = 'Ocurrió un error al intentar agregar.';
				}
			} else {
				$trans_ok &= $this->planilla_asisnov_model->create(array(
					'escuela_id' => $planilla->escuela_id,
					'ames' => $planilla->ames,
					'planilla_tipo_id' => $planilla->planilla_tipo_id,
					'fecha_creacion' => date('Y-m-d H:i:s'),
					'rectificativa' => '0'
				));
			}
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->planilla_asisnov_model->get_msg());
				redirect("asisnov/index/$planilla->escuela_id/$planilla->ames", 'refresh');
			} else {
				$this->session->set_flashdata('error', $error_msg ? $error_msg : $this->planilla_asisnov_model->get_error());
				redirect("asisnov/index/$planilla->escuela_id/$planilla->ames", 'refresh');
			}
		}
		$data['planilla'] = $planilla;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = (isset($planilla->id)) ? 'Agregar Rectificativa' : 'Agregar Planilla';
		$this->load->view('asisnov/asisnov_modal_rectificativa', $data);
	}

	public function listar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$planilla = $this->planilla_asisnov_model->get(array('id' => $id));
		$escuela = $this->escuela_model->get_one($planilla->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$novedades = $this->planilla_asisnov_model->get_novedades($planilla->id);
		if (empty($planilla)) {
			show_error('No se encontró el registro a listar', 500, 'Registro no encontrado');
		}

		$planilla->titulo = 'Planilla ' . ($planilla->planilla_tipo_id === '1' ? 'Titulares' : 'Reemplazos') . ($planilla->rectificativa === '0' ? ' Original' : ' Rectificativa ' . $planilla->rectificativa);
		$data['novedades'] = $novedades;
		$data['planilla'] = $planilla;
		$data['escuela'] = $escuela;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Asistencia y Novedades';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('asisnov/asisnov_listar', $data);
	}

	public function modal_casos($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$planilla = $this->planilla_asisnov_model->get(array('id' => $id));
		if (empty($planilla)) {
			$this->modal_error('No se encontró el registro a cerrar', 'Registro no encontrado');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$escuela = $this->escuela_model->get_one($planilla->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($planilla->id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$fecha_cierre = date('Y-m-d H:i:s');
			$planilla->fecha_cierre = $fecha_cierre;
			$content = $this->generar_impresion($planilla);
			if ($content !== FALSE) {
				$trans_ok = TRUE;
				$this->db->trans_begin();
				$trans_ok &= $this->planilla_asisnov_model->update(array(
					'id' => $this->input->post('id'),
					'fecha_cierre' => $fecha_cierre
					), FALSE);

				if ($trans_ok) {
					$this->load->model('planilla_impresion_model');
					$trans_ok &= $this->planilla_impresion_model->create(array(
						'planilla_id' => $planilla->id,
						'impresion' => $content
						), FALSE);
				}

				if ($trans_ok) {
					$this->load->model('servicio_novedad_model');
					$novedades_pendientes = $this->servicio_novedad_model->get(array(
						'planilla_alta_id' => $planilla->id,
						'ames' => $planilla->ames,
						'novedad_tipo_id <>' => '1',
						'where' => array("ames < DATE_FORMAT(fecha_hasta, '%Y%m')", 'planilla_baja_id IS NULL'),
					));

					if (!empty($novedades_pendientes) && $trans_ok) {
						$mes_siguiente = (new DateTime($planilla->ames . '01 +1 month'))->format('Ym');
						$planilla_siguiente_id = $this->planilla_asisnov_model->get_planilla_abierta($planilla->escuela_id, $mes_siguiente, $planilla->planilla_tipo_id);
						$trans_ok &= $planilla_siguiente_id > 0;

						if ($trans_ok) {
							foreach ($novedades_pendientes as $novedad) {
								$fecha_desde = (new DateTime($mes_siguiente . '01'));
								$fecha_hasta = (new DateTime($novedad->fecha_hasta));
								$fecha_ultimo_dia = (new DateTime($mes_siguiente . '01'));
								$dias = $novedad->fecha_hasta < $fecha_ultimo_dia->format('Y-m-t') ?
									$fecha_desde->diff($fecha_hasta)->days + 1 :
									$fecha_desde->diff($fecha_ultimo_dia)->days + 1;

								$trans_ok &= $this->servicio_novedad_model->create(array(
									'servicio_id' => $novedad->servicio_id,
									'ames' => $mes_siguiente,
									'novedad_tipo_id' => $novedad->novedad_tipo_id,
									'fecha_desde' => $novedad->fecha_desde,
									'fecha_hasta' => $novedad->fecha_hasta,
									'dias' => $dias,
									'obligaciones' => 'NULL',
									'estado' => 'Pendiente',
									'planilla_alta_id' => $planilla_siguiente_id,
									'origen_id' => $novedad->id
									), FALSE);
							}
						}
					}
				}
			}
			if ($content !== FALSE && $this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->planilla_asisnov_model->get_msg());
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al intentar cerrar.';
				if ($this->planilla_asisnov_model->get_error())
					$errors .= '<br>' . $this->planilla_asisnov_model->get_error();
				if ($this->planilla_impresion_model->get_error())
					$errors .= '<br>' . $this->planilla_impresion_model->get_error();
				$this->session->set_flashdata('error', $errors);
			}
			redirect("asisnov/index/$planilla->escuela_id/$planilla->ames", 'refresh');
		}
		$data['planilla'] = $planilla;
		$data['txt_btn'] = 'Cerrar';
		$data['title'] = 'Cerrar planilla de asistencia y novedades';
		$this->load->view('asisnov/asisnov_modal_cerrar', $data);
	}
}
/* End of file Asisnov.php */
/* Location: ./application/controllers/Asisnov.php */
