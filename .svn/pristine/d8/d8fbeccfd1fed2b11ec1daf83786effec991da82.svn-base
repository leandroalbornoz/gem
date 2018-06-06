<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Asisnov extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('servicio_model');
		$this->load->model('escuela_model');
		$this->load->model('planilla_asisnov_model');
		$this->load->model('tem/tem_proyecto_escuela_model');
		$this->load->model('tem/tem_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ESCUELA_ALUM, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE_CURSADA));
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_ESCUELA_ALUM))) {
			$this->edicion = FALSE;
		}
//		$this->nav_route = 'menu/asisnov';
	}

	public function index($escuela_id = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || ($mes != NULL && !ctype_digit($mes))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($mes) || empty(DateTime::createFromFormat('Ym', $mes))) {
			$mes = $this->planilla_asisnov_model->get_mes_actual();
			redirect("tem/asisnov/index/$escuela_id/$mes", 'refresh');
		}
		$escuela = $this->escuela_model->get(array('id' => $escuela_id));
		if (empty($escuela)) {
			show_error('No se encontró la escuela', 500, 'Acción no autorizada');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$planillas = $this->planilla_asisnov_model->get(array(
			'escuela_id' => $escuela_id,
			'ames' => $mes,
			'planilla_tipo_id' => 3,
			'sort_by' => 'rectificativa desc'
		));

		$planillas_abiertas = array();
		if (!empty($planillas)) {
			foreach ($planillas as $planilla) {
				if (empty($planilla->fecha_cierre)) {
					$planillas_abiertas[] = $planilla;
				}
			}
		}
		$data['planillas'] = $planillas;
		$data['planillas_abiertas'] = $planillas_abiertas;
		$data['escuela'] = $escuela;
		$data['mes_id'] = $mes;
		$data['mes'] = $this->nombres_meses[substr($mes, 4, 2)] . '\'' . substr($mes, 2, 2);
		$fecha = DateTime::createFromFormat('Ymd', $mes . '01');
		$data['fecha'] = $fecha->format('d/m/Y');
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Asistencia y Novedades';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';

		$this->load_template('tem/asisnov/asisnov_index', $data);
	}

	public function imprimir($escuela_id = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL ||
			$mes == NULL || !ctype_digit($escuela_id) || !ctype_digit($mes)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$planilla = $this->planilla_asisnov_model->get_planilla($escuela_id, $mes, 3);
		if (empty($planilla->fecha_cierre)) {
			$content = $this->generar_impresion($planilla);
			$watermark = 'Planilla de revisión';
		} else {
			$this->load->model('planilla_impresion_model');
			$content = $this->planilla_impresion_model->get_impresion($planilla->id);
			$watermark = '';
		}
		$month = substr($planilla->ames, 4, 2);
		$year = substr($planilla->ames, 0, 4);
		$this->load->helper('mpdf');
		exportMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Terminalidad_' . $escuela->numero . '_' . $year . '_' . $month, 'Planilla de Asistencia y Novedades - Esc. "' . $escuela->nombre . '" Nº ' . $escuela->numero . ' - Juri: ' . $escuela->jurisdiccion_codigo . ' - Repa: ' . $escuela->reparticion_codigo . ' - Zona: ' . $escuela->zona_valor . ' - MES:' . (int) substr($mes, -2) . ' AÑO:' . substr($mes, 0, 4) . ($planilla->rectificativa ? " RECTIFICATIVA:$planilla->rectificativa" : ''), '|{PAGENO} de {nb}|', 'LEGAL-L', $watermark, 'I', FALSE, FALSE);
	}

	public function imprimir_parcial($id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$planilla = $this->planilla_asisnov_model->get_one($id);
		if (empty($planilla)) {
			show_error('No se encontró el registro de planilla', 500, 'Acción no autorizada');
		}

		$area = $this->area_model->get_one($planilla->area_id);
		if (empty($area)) {
			show_error('No se encontró el registro de área', 500, 'Acción no autorizada');
		}
		if ($this->rol->codigo === ROL_AREA && strpos($area->codigo, substr($this->rol->entidad, 0, strpos($this->rol->entidad, '-'))) === FALSE) {
			show_error('No tiene permisos para acceder al área', 500, 'Acción no autorizada');
		}

		$this->load->model('servicio_novedad_model');
		$novedades_creadas = $this->servicio_novedad_model->get(array(
			'join' => array(
				array('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id', 'left', array('novedad_tipo.descripcion as novedad_tipo', 'novedad_tipo.articulo', 'novedad_tipo.inciso', 'novedad_tipo.id as novedad_tipo_id', 'novedad_tipo.novedad as novedad_tipo_novedad')),
				array('servicio', 'servicio.id = servicio_novedad.servicio_id', '', array('servicio.liquidacion', 'servicio.motivo_baja', 'servicio.fecha_baja')),
				array('servicio_funcion', 'servicio_funcion.servicio_id = servicio.id AND servicio_funcion.fecha_hasta IS NULL', 'left', array('servicio_funcion.destino as funcion_destino', 'servicio_funcion.norma as funcion_norma', 'servicio_funcion.tarea as funcion_tarea', 'servicio_funcion.carga_horaria as funcion_cargahoraria', 'servicio_funcion.fecha_desde as funcion_desde')),
				array('funcion', 'funcion.id = servicio_funcion.funcion_id', 'left', array('COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion_detalle')),
				array('persona', 'persona.id = servicio.persona_id', 'left', array('persona.cuil', 'persona.apellido', 'persona.nombre', 'persona.fecha_nacimiento')),
				array('cargo', 'cargo.id = servicio.cargo_id', '', array('cargo.escuela_id', 'cargo.carga_horaria')),
				array('division', 'division.id = cargo.division_id', 'left', array('division.id as division_id')),
				array('celador_concepto', 'celador_concepto.id = servicio.celador_concepto_id', 'left', array('celador_concepto.descripcion as celador_concepto')),
				array('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', '', array('regimen.codigo as regimen_codigo', 'regimen.descripcion as regimen', 'regimen_tipo_id', 'regimen.puntos')),
				array('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id', 'left', array('situacion_revista.descripcion as situacion_revista')),
				array('area', 'area.id = cargo.area_id', 'left', array('area.descripcion as area')),
				array('turno', 'turno.id = cargo.turno_id', 'left', array('turno.descripcion as turno')),
				array('turno turno2', 'turno2.id = division.turno_id', 'left', array('turno2.descripcion as turno2')),
				array('escuela', 'escuela.id = cargo.escuela_id', 'left', array('escuela.nombre as escuela', 'escuela.numero as escuela_numero')),
			),
			'planilla_alta_id' => $planilla->id,
		));
		$novedades_eliminadas = $this->servicio_novedad_model->get(array(
			'join' => array(
				array('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id', 'left', array('novedad_tipo.descripcion as novedad_tipo', 'novedad_tipo.articulo', 'novedad_tipo.inciso', 'novedad_tipo.id as novedad_tipo_id', 'novedad_tipo.novedad as novedad_tipo_novedad')),
				array('servicio', 'servicio.id = servicio_novedad.servicio_id', '', array('servicio.liquidacion', 'servicio.motivo_baja', 'servicio.fecha_baja')),
				array('servicio_funcion', 'servicio_funcion.servicio_id = servicio.id AND servicio_funcion.fecha_hasta IS NULL', 'left', array('servicio_funcion.destino as funcion_destino', 'servicio_funcion.norma as funcion_norma', 'servicio_funcion.tarea as funcion_tarea', 'servicio_funcion.carga_horaria as funcion_cargahoraria', 'servicio_funcion.fecha_desde as funcion_desde')),
				array('funcion', 'funcion.id = servicio_funcion.funcion_id', 'left', array('COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion_detalle')),
				array('persona', 'persona.id = servicio.persona_id', 'left', array('persona.cuil', 'persona.apellido', 'persona.nombre', 'persona.fecha_nacimiento')),
				array('cargo', 'cargo.id = servicio.cargo_id', '', array('cargo.escuela_id', 'cargo.carga_horaria')),
				array('division', 'division.id = cargo.division_id', 'left', array('division.id as division_id')),
				array('celador_concepto', 'celador_concepto.id = servicio.celador_concepto_id', 'left', array('celador_concepto.descripcion as celador_concepto')),
				array('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', '', array('regimen.codigo as regimen_codigo', 'regimen.descripcion as regimen', 'regimen_tipo_id', 'regimen.puntos')),
				array('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id', 'left', array('situacion_revista.descripcion as situacion_revista')),
				array('area', 'area.id = cargo.area_id', 'left', array('area.descripcion as area')),
				array('turno', 'turno.id = cargo.turno_id', 'left', array('turno.descripcion as turno')),
				array('turno turno2', 'turno2.id = division.turno_id', 'left', array('turno2.descripcion as turno2')),
				array('escuela', 'escuela.id = cargo.escuela_id', 'left', array('escuela.nombre as escuela', 'escuela.numero as escuela_numero')),
			),
			'planilla_baja_id' => $planilla->id,
		));

		$autoridad = (object) array(
				'apellido' => $this->session->userdata('usuario')->apellido,
				'nombre' => $this->session->userdata('usuario')->nombre,
				'cuil' => $this->session->userdata('usuario')->cuil,
				'email' => $this->session->userdata('usuario')->usuario,
		);

		if (isset($novedades_creadas) && !empty($novedades_creadas)) {
			$filasAltas_nuevas = array();
			$filasBajas_nuevas = array();
			$filasNovedades_nuevas = array();
			foreach ($novedades_creadas as $novedad) {
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
		$data['area'] = $area;
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
		//echo $content;exit;
		exportMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Planilla de Asistencia', 'Planilla de Asistencia y Novedades - Área "' . $area->descripcion . '" Código ' . $area->codigo . ' - Juri: ' . $area->jurisdiccion_codigo . ' - Repa: ' . $area->reparticion_codigo . ' - MES:' . substr($planilla->ames, -2) . ' AÑO:' . substr($planilla->ames, 0, 4) . ($planilla->rectificativa ? " RECTIFICATIVA:$planilla->rectificativa" : ''), '|{PAGENO} de {nb}|', 'LEGAL-L', $watermark, 'I', FALSE, FALSE);
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

		$servicios = $this->servicio_model->get(array(
			'join' => array(
				array('persona', 'persona.id = servicio.persona_id', 'left', array('persona.cuil', 'persona.apellido', 'persona.nombre', 'persona.fecha_nacimiento')),
				array('cargo', 'cargo.id = servicio.cargo_id', '', array('cargo.escuela_id', 'cargo.carga_horaria', 'cargo.regimen_id')),
				array('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=2', '', array('regimen.codigo as regimen_codigo', 'regimen.descripcion as regimen', 'regimen.regimen_tipo_id', 'regimen.puntos')),
				array('turno', 'turno.id = cargo.turno_id', 'left', array('turno.descripcion as turno')),
				array('escuela', 'escuela.id = cargo.escuela_id', 'left', array('CONCAT(escuela.numero, \' \', escuela.nombre) as escuela', 'escuela.unidad_organizativa')),
			),
			'where' => array(
				"cargo.escuela_id = $planilla->escuela_id",
				"regimen.planilla_modalidad_id = 2",
				"'$planilla->ames' >= COALESCE(DATE_FORMAT(servicio.fecha_alta,'%Y%m'),'000000')",
				"'$planilla->ames' <= COALESCE(DATE_FORMAT(servicio.fecha_baja,'%Y%m'),'999999')"
			),
			'sort_by' => 'persona.documento'
		));
		$this->load->model('servicio_novedad_model');
		$filas = array();
		$filasAltas = array();
		$this->load->model('tem_proyecto_model');
		$proyecto = $this->tem_proyecto_model->get_proyecto_mes($planilla->ames);
		if ((new DateTime($proyecto->fecha_hasta))->format('Ym') === $planilla->ames) {
			$baja_proyecto = $proyecto->fecha_hasta;
		} else {
			$baja_proyecto = FALSE;
		}
		if (!empty($servicios)) {
			foreach ($servicios as $serv_id => $servicio) {
				$servicios[$serv_id]->inasistencias = array();
				$servicios[$serv_id]->nroliq = substr($servicio->liquidacion, -2);
				if ($baja_proyecto) {
					if (empty($servicios[$serv_id]->fecha_baja)) {
						$servicios[$serv_id]->fecha_baja = $baja_proyecto;
						$servicios[$serv_id]->motivo_baja = 'Fin proyecto';
						$servicios[$serv_id]->inasistencias = array(
							(object) array(
								'novedad_tipo_id' => 248,
								'novedad_tipo_novedad' => 'B',
								'dias_desde' => $baja_proyecto,
								'dias_hasta' => (new DateTime($planilla->ames . '01 +1 month -1 day'))->format('Y-m-d'),
								'articulo' => "02-4",
								'oblig_nocumplio' => $servicio->carga_horaria * (4 - $proyecto->semanas)
							)
						);
					}
					$servicios[$serv_id]->baja_continua = 0;
				} else {
					$servicios[$serv_id]->baja_continua = empty($servicio->fecha_baja);
				}
				$servicios[$serv_id]->baja_dada = (!empty($servicio->fecha_baja) && $planilla->ames === (new DateTime($servicio->fecha_baja . ' +1 month'))->format('Ym')) ? 1 : 0;
				$servicios[$serv_id]->oblig = $servicio->regimen_tipo_id === '1' ? $servicio->puntos : $servicio->carga_horaria;
				$servicios[$serv_id]->oblig_cumplir = ($servicio->regimen_tipo_id === '1') ? '30' : $servicio->carga_horaria * 4;
				$novedades = $this->servicio_novedad_model->get(array(
					'servicio_id' => $servicio->id,
					'ames' => $planilla->ames,
					'join' => array(
						array('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id', 'left', array('novedad_tipo.articulo', 'novedad_tipo.inciso', 'novedad_tipo.id as novedad_tipo_id', 'novedad_tipo.novedad as novedad_tipo_novedad'))),
					'where' => array('planilla_baja_id is null', 'novedad_tipo_id!=1')
				));
				if (!empty($novedades)) {
					foreach ($novedades as $novedad) {
						$servicios[$serv_id]->inasistencias[] = (object) array(
								'novedad_tipo_id' => $novedad->novedad_tipo_id,
								'novedad_tipo_novedad' => $novedad->novedad_tipo_novedad,
								'dias_desde' => $novedad->fecha_desde,
								'dias_hasta' => $novedad->fecha_hasta,
								'articulo' => "$novedad->articulo-$novedad->inciso",
								'oblig_nocumplio' => ($servicio->regimen_tipo_id == '1') ? $novedad->dias : $novedad->obligaciones
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
		$this->load->model('persona_model');
		$data['final'] = !empty($planilla->fecha_cierre);
		$data['filas'] = $filas;
		$data['filasAltas'] = $filasAltas;
		return $this->load->view('tem/asisnov/asisnov_impresion', $data, TRUE);
	}

	public function cambiar_mes($escuela_id, $mes = '') {
		if (!ctype_digit($mes)) {
			redirect("tem/asisnov/index/$escuela_id/" . date('Ym'), 'refresh');
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
			redirect("tem/asisnov/index/$escuela_id/" . $mes, 'refresh');
		}
		$data['escuela_id'] = $escuela_id;
		$data['mes'] = $mes;
		$this->load->view('tem/asisnov/asisnov_cambiar_mes', $data);
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
		$escuela = $this->escuela_model->get(array('id' => $planilla->escuela_id));
		if (empty($escuela)) {
			$this->modal_error('No se encontró la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($planilla->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			$fecha_cierre = date('Y-m-d H:i:s');
			$planilla->fecha_cierre = $fecha_cierre;
			$content = $this->generar_impresion($planilla);

			if ($content !== FALSE) {
				$trans_ok = TRUE;
				$this->db->trans_begin();
				$trans_ok &= $this->planilla_asisnov_model->update(array(
					'id' => $this->input->post('id'),
					'fecha_cierre' => date('Y-m-d H:i:s')
					), FALSE);

				if ($trans_ok) {
					$this->load->model('planilla_impresion_model');
					$trans_ok &= $this->planilla_impresion_model->create(array(
						'planilla_id' => $planilla->id,
						'impresion' => $content
						), FALSE);
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
			redirect("tem/asisnov/index/$planilla->escuela_id/$planilla->ames", 'refresh');
		}
		$data['planilla'] = $planilla;
		$data['txt_btn'] = 'Cerrar';
		$data['title'] = 'Cerrar planilla de asistencia y novedades';
		$this->load->view('tem/asisnov/asisnov_modal_cerrar', $data);
	}

	public function modal_rectificativa($escuela_id = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL ||
			$mes == NULL || !ctype_digit($escuela_id) || !ctype_digit($mes)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$escuela = $this->escuela_model->get(array('id' => $escuela_id));
		if (empty($escuela)) {
			$this->modal_error('No se encontró la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$planilla = $this->planilla_asisnov_model->get_planilla($escuela_id, $mes, 3);

		if (isset($_POST) && !empty($_POST)) {
			$error_msg = FALSE;
			$trans_ok = TRUE;

			if (isset($planilla->id)) {
				if ($planilla->id !== $this->input->post('id')) {
					show_error('Esta solicitud no pasó el control de seguridad.');
				}
				if (!empty($planilla->fecha_cierre)) {
					$trans_ok &= $this->planilla_asisnov_model->create(array(
						'escuela_id' => $planilla->escuela_id,
						'planilla_tipo_id' => 3,
						'ames' => $planilla->ames,
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
					'planilla_tipo_id' => 3,
					'ames' => $planilla->ames,
					'fecha_creacion' => date('Y-m-d H:i:s'),
					'rectificativa' => '0'
				));
			}
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->planilla_asisnov_model->get_msg());
				redirect("tem/asisnov/index/$planilla->escuela_id/$planilla->ames", 'refresh');
			} else {
				$this->session->set_flashdata('error', $error_msg ? $error_msg : $this->planilla_asisnov_model->get_error());
				redirect("tem/asisnov/index/$planilla->escuela_id/$planilla->ames", 'refresh');
			}
		}
		$data['planilla'] = $planilla;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = (isset($planilla->id)) ? 'Agregar Rectificativa' : 'Agregar Planilla';
		$this->load->view('tem/asisnov/asisnov_modal_rectificativa', $data);
	}

	public function listar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$planilla = $this->planilla_asisnov_model->get(array('id' => $id));
		if (empty($planilla)) {
			show_error('No se encontró el registro a listar', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get(array('id' => $planilla->escuela_id));
		if (empty($escuela)) {
			show_error('No se encontró la escuela', 500, 'Acción no autorizada');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$novedades = $this->tem_model->get_novedades_tem($planilla->id);

		$planilla->titulo = 'Planilla ' . ($planilla->rectificativa === '0' ? ' Original' : ' Rectificativa ' . $planilla->rectificativa);
		$data['novedades'] = $novedades;
		$data['planilla'] = $planilla;
		$data['escuela'] = $escuela;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Asistencia y Novedades';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('tem/asisnov/asisnov_listar', $data);
	}
}
/* End of file Asisnov.php */
/* Location: ./application/controllers/Asisnov.php */
