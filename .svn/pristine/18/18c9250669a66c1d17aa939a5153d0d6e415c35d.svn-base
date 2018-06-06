<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Administrar extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->load->model('comedor_presupuesto_model');
		$this->load->model('comedor_racion_model');
		$this->load->model('comedor_valor_racion_model');
		$this->load->model('comedor_alumno_model');
		$this->load->model('comedor_plazo_model');
		$this->roles_admin = array(ROL_ADMIN, ROL_USI);
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_CONSULTA);
		$this->modulos_permitidos = array(ROL_MODULO_COMEDOR);
		if (in_array($this->rol->codigo, array(ROL_CONSULTA))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'admin/escuela';
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
	}

	public function listar_escuelas($mes = NULL, $nivel_id = '0') {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $mes == NULL || !ctype_digit($mes) || $nivel_id == NULL || !ctype_digit($nivel_id)) {
			if (empty($mes)) {
				$mes = date('Ym');
			} else {
				show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
			}
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Número', 'data' => 'numero', 'width' => 20),
				array('label' => 'Nombre', 'data' => 'nombre', 'width' => 25),
				array('label' => 'Direccion', 'data' => 'calle', 'width' => 25),
				array('label' => 'Teléfono', 'data' => 'telefono', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Email', 'data' => 'email', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Delegacion', 'data' => 'descripcion', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'escuela_table',
			'source_url' => "comedor/administrar/listar_data/$mes",
			'reuse_var' => TRUE,
			'initComplete' => "complete_escuela_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);

		$comedor_racion = $this->comedor_racion_model->get_raciones($mes);
		$fecha_c = $this->comedor_plazo_model->get(array('mes' => $mes));

		$fecha_cierre = (!empty($fecha_c)) ? $fecha_c[0]->fecha_cierre : "No cargada";

		$data ['fecha_cierre'] = $fecha_cierre;
		$data['comedor_racion'] = $comedor_racion;
		$data['mes'] = $mes;
		$data['mes_nombre'] = $this->nombres_meses[substr($mes, 4, 2)] . '\'' . substr($mes, 2, 2);
		$fecha = DateTime::createFromFormat('Ymd', $mes . '01');
		$data['fecha'] = $fecha->format('d/m/Y');
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['class'] = array('escritorio' => '', 'administrar' => 'active btn-app-zetta-active', 'ver' => '', 'editar' => '');
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		if (empty($nivel_id)) {
			$data['title'] = TITLE . ' - Escuelas';
		} else {
			$this->load->model('nivel_model');
			$nivel = $this->nivel_model->get(array('id' => $nivel_id));
			$data['nivel'] = $nivel;
			$data['title'] = TITLE . ' - Escuelas Nivel ' . $nivel->descripcion;
		}
		$this->load_template('comedor/administrar/listar_escuelas_comedor', $data);
	}

	public function listar_data($mes = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $mes == NULL || !ctype_digit($mes)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select("escuela.id, comedor_presupuesto.id as comedor_escuela_id, numero, nombre, calle, telefono, email, delegacion.descripcion")
			->unset_column('escuela.id')
			->from('escuela')
			->join('comedor_presupuesto', 'escuela.id = comedor_presupuesto.escuela_id', 'left')
			->join('delegacion', 'escuela.delegacion_id = delegacion.id', 'left')
			->where('comedor_presupuesto.mes', $mes);
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="comedor/administrar/escuela_ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item btn-danger" href="comedor/administrar/modal_eliminar_escuela/$1" "data-remote="false" data-toggle="modal" data-target="#remote_modal" ><i class="fa fa-remove"></i> Eliminar escuela</a></li>'
				. '</ul></div>', 'comedor_escuela_id');
		} else {
			$this->datatables->add_column('edit', '<a class="btn btn-xs btn-default" href="comedor/escuela/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id');
		}

		echo $this->datatables->generate();
	}

	public function cambiar_mes_lista($mes = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $mes == NULL || !ctype_digit($mes)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$model = new stdClass();
		$model->fields = array(
			'mes' => array('label' => 'Mes', 'type' => 'date', 'required' => TRUE)
		);
		$this->set_model_validation_rules($model);
		if ($this->form_validation->run() === TRUE) {
			$mes = (new DateTime($this->get_date_sql('mes')))->format('Ym');
			$this->session->set_flashdata('message', 'Mes cambiado correctamente');
		} else {
			$this->session->set_flashdata('error', 'Error al cambiar mes');
		}
		redirect("comedor/administrar/listar_escuelas/$mes", 'refresh');
	}

	public function modal_agregar_escuela($mes = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $mes == NULL || !ctype_digit($mes)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$comedor_racion = $this->comedor_racion_model->get_raciones($mes);
		if (empty($comedor_racion)) {
			$this->modal_error('Antes de agregar escuelas debe importar las del mes anterior', 'No puede agregar');
			return;
		}

		$model_busqueda = new stdClass();
		$model_busqueda->fields = array(
			'escuela_numero' => array('label' => 'Numero', 'maxlength' => '10'),
			'escuela_nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'minlength' => '3'),
		);

		$this->set_model_validation_rules($this->comedor_presupuesto_model);
		if (isset($_POST) && !empty($_POST)) {
			if (empty($this->input->post('escuela_id'))) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$escuela_id = $this->input->post('escuela_id');
				$trans_ok &= $this->comedor_presupuesto_model->create(array(
					'escuela_id' => $escuela_id,
					'mes' => $mes), FALSE);

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->comedor_presupuesto_model->get_msg());
					redirect("comedor/administrar/listar_escuelas/$mes", 'refresh');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->comedor_presupuesto_model->get_error());
					redirect("comedor/administrar/listar_escuelas/$mes", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("comedor/administrar/listar_escuelas/$mes", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($model_busqueda->fields);
		$data['mes'] = $mes;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['txt_btn'] = "Agregar";
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = 'Agregar Escuela';
		$this->load->view('comedor/administrar/modal_agregar_escuela', $data);
	}

	public function modal_importar_escuela($mes = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $mes == NULL || !ctype_digit($mes)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$mes_valor = new DateTime($mes . '01');
		$mes_anterior = (new DateTime($mes_valor->format('Ym') . '01 -1 month'))->format('Ym');

		$valor_racion = $this->comedor_valor_racion_model->get(array('mes' => $mes_anterior));

		$escuelas_importar = $this->comedor_presupuesto_model->get(array('mes' => $mes_anterior));

		$mes_cierre = $this->comedor_plazo_model->get(array('mes' => $mes));
		$crear = FALSE;
		if (empty($mes_cierre)) {
			$crear = TRUE;
		}

		$contar = 0;
		$cartel = "";
		if (!empty($escuelas_importar)) {
			foreach ($escuelas_importar as $escuela) {
				$escuela_existente = $this->comedor_presupuesto_model->get(array('escuela_id' => $escuela->escuela_id, 'mes' => $mes));
				if (empty($escuela_existente)) {
					$contar++;
				}
			}
		} else {
			$cartel = "No se encuentran escuelas en el mes anterior.";
		}

		$this->set_model_validation_rules($this->comedor_presupuesto_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
//			$crear = FALSE;
				foreach ($escuelas_importar as $escuela) {
					$escuela_existente = $this->comedor_presupuesto_model->get(array('escuela_id' => $escuela->escuela_id, 'mes' => $mes));
					if (empty($escuela_existente)) {
						$trans_ok &= $this->comedor_presupuesto_model->create(array(
							'escuela_id' => $escuela->escuela_id,
							'mes' => $mes), FALSE);
					}
				}
				if ($crear === TRUE) {
					$trans_ok &= $this->comedor_plazo_model->create(array(
						'mes' => $mes,
						'fecha_cierre' => $this->get_date_sql('fecha_cierre')
						), FALSE);

					foreach ($valor_racion as $valor) {
						$trans_ok &= $this->comedor_valor_racion_model->create(array(
							'comedor_racion_id' => $valor->comedor_racion_id,
							'monto' => $valor->monto,
							'mes' => $mes
							), FALSE);
					}
				}

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->comedor_presupuesto_model->get_msg());
					redirect("comedor/administrar/listar_escuelas/$mes", 'refresh');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->comedor_presupuesto_model->get_error());
					redirect("comedor/administrar/listar_escuelas/$mes", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("comedor/administrar/listar_escuelas/$mes", 'refresh');
			}
		}

		$data['crear'] = $crear;
		$data['contar'] = $contar;
		$data['cartel'] = $cartel;
		$data['mes'] = $mes;
		$data['fields'] = $this->build_fields($this->comedor_plazo_model->fields);
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['txt_btn'] = "Importar";
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = 'Importar Escuelas';
		$this->load->view('comedor/administrar/modal_importar_escuela', $data);
	}

	public function modal_eliminar_escuela($comedor_presupuesto_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $comedor_presupuesto_id == NULL || !ctype_digit($comedor_presupuesto_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}

		$comedor_presupuesto = $this->comedor_presupuesto_model->get_one($comedor_presupuesto_id);
		if (empty($comedor_presupuesto)) {
			$this->modal_error('No se encontró el registro del presupuesto', 'Registro no encontrado');
			return;
		}

		$escuela = $this->escuela_model->get_one($comedor_presupuesto->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}

		$comedor_alumnos = $this->comedor_alumno_model->get(array('comedor_presupuesto_id' => $comedor_presupuesto->id));
		if (!empty($comedor_alumnos)) {
			$this->modal_error('No se puede borrar el registro, datos de alumnos cargados', 'No se puede borrar');
			return;
		}

		$this->set_model_validation_rules($this->comedor_presupuesto_model);
		if (isset($_POST) && !empty($_POST)) {
			if (empty($this->input->post('comedor_presupuesto_id'))) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
				return;
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$id = $this->input->post('comedor_presupuesto_id');
				$comedor_alumnos = $this->comedor_alumno_model->get(array('comedor_presupuesto_id' => $id));
				if (!empty($comedor_alumnos)) {
					$this->session->set_flashdata('error', 'No se puede borrar el registro, datos de alumnos cargados.');
					redirect("comedor/administrar/listar_escuelas/$comedor_presupuesto->mes", 'refresh');
				}
				$trans_ok &= $this->comedor_presupuesto_model->delete(array(
					'id' => $id));

				if ($trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->comedor_presupuesto_model->get_msg());
					redirect("comedor/administrar/listar_escuelas/$comedor_presupuesto->mes", 'refresh');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->comedor_presupuesto_model->get_error());
					redirect("comedor/administrar/listar_escuelas/$comedor_presupuesto->mes", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("comedor/administrar/listar_escuelas/$comedor_presupuesto->mes", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->escuela_model->fields, $escuela, TRUE);
		$data['mes'] = $comedor_presupuesto->mes;
		$data['comedor_presupuesto'] = $comedor_presupuesto;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['txt_btn'] = "Eliminar";
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = 'Eliminar presupuesto';
		$this->load->view('comedor/administrar/modal_eliminar_escuela', $data);
	}

	public function escuela_ver($comedor_presupuesto_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $comedor_presupuesto_id == NULL || !ctype_digit($comedor_presupuesto_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$comedor_presupuesto = $this->comedor_presupuesto_model->get_one($comedor_presupuesto_id);
		if (empty($comedor_presupuesto)) {
			show_error('No se encontró el registro del presupuesto', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($comedor_presupuesto->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}


		$comedor_divisiones = $this->comedor_presupuesto_model->get_comedor_divisiones($escuela->id, $comedor_presupuesto->mes);
		$comedor_racion = $this->comedor_racion_model->get_raciones($comedor_presupuesto->mes);
		$mes = new DateTime((!empty($comedor_presupuesto->mes) ? $comedor_presupuesto->mes : date('Ym')) . '01 ');
		$total_alumnos_r1 = 0;
		$total_alumnos_r2 = 0;
		foreach ($comedor_divisiones as $division) {
			$total_alumnos_r1 += $division->alumnos_r1;
			$total_alumnos_r2 += $division->alumnos_r2;
		}
		$racion = ($total_alumnos_r1 * $comedor_racion['1']->monto) + ($total_alumnos_r2 * $comedor_racion['2']->monto);

		// monto consumido
		$asistencia = $this->comedor_presupuesto_model->monto_raciones($comedor_presupuesto->mes, $comedor_presupuesto->escuela_id);
		$dias_asistidos = round($asistencia[0]['asistencia_media'] * $comedor_presupuesto->dias_albergado);

		$data['monto_consumido'] = ($dias_asistidos * $racion);
		$data['monto_ideal'] = ($comedor_presupuesto->dias_albergado * $racion);
		$data['mes'] = $comedor_presupuesto->mes;
		$data['mes_nombre'] = $this->nombres_meses[substr(date_format($mes, 'Ym'), 4, 2)] . "' " . substr(date_format($mes, 'Ym'), 2, 2);
		$data['escuela'] = $escuela;
		$data['comedor_divisiones'] = $comedor_divisiones;
		$data['comedor_presupuesto'] = $comedor_presupuesto;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['escuela'] = $escuela;
		$data['txt_btn'] = NULL;
		$data['class'] = array('administrar' => 'active btn-app-zetta-active', 'cargar_presupuesto' => '');
		$data['title'] = TITLE . ' - Comedor';
		$this->load_template('comedor/administrar/escuela_ver', $data);
	}

	public function modal_presupuesto($comedor_presupuesto_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $comedor_presupuesto_id == NULL || !ctype_digit($comedor_presupuesto_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$comedor_presupuesto = $this->comedor_presupuesto_model->get_one($comedor_presupuesto_id);
		if (empty($comedor_presupuesto)) {
			show_error('No se encontró el registro del presupuesto', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($comedor_presupuesto->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}

		if (empty($comedor_presupuesto->alumnos_media_racion) && empty($comedor_presupuesto->alumnos_racion_completa)) {
			$this->modal_error('Datos de alumnos no cargados', 'No se puede asignar');
			return;
		}

		$comedor_divisiones = $this->comedor_presupuesto_model->get_comedor_divisiones($escuela->id, $comedor_presupuesto->mes);
		$comedor_racion = $this->comedor_racion_model->get_raciones($comedor_presupuesto->mes);
		$mes = new DateTime((!empty($comedor_presupuesto->mes) ? $comedor_presupuesto->mes : date('Ym')) . '01 ');
		$total_alumnos_r1 = 0;
		$total_alumnos_r2 = 0;
		foreach ($comedor_divisiones as $division) {
			$total_alumnos_r1 += $division->alumnos_r1;
			$total_alumnos_r2 += $division->alumnos_r2;
		}
		$racion = ($total_alumnos_r1 * $comedor_racion['1']->monto) + ($total_alumnos_r2 * $comedor_racion['2']->monto);
		$monto_ideal = ($comedor_presupuesto->dias_albergado * $racion);


		$this->set_model_validation_rules($this->comedor_presupuesto_model);
		if (isset($_POST) && !empty($_POST)) {
			if (empty($this->input->post('comedor_presupuesto_id')) || empty($this->input->post('monto_presupuestado')) || empty($this->input->post('monto_entregado'))) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			if ($monto_ideal != $this->input->post('monto_presupuestado')) {
				$this->session->set_flashdata('error', 'El monto no coincide con el calculado');
				redirect("comedor/administrar/escuela_ver/$comedor_presupuesto->id", 'refresh');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->comedor_presupuesto_model->update(array(
					'id' => $this->input->post('comedor_presupuesto_id'),
					'monto_entregado' => $this->input->post('monto_entregado'),
					'monto_presupuestado' => $this->input->post('monto_presupuestado')
					), FALSE);
				if ($trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->comedor_presupuesto_model->get_msg());
					redirect("comedor/administrar/escuela_ver/$comedor_presupuesto->id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->comedor_presupuesto_model->get_error());
					redirect("comedor/administrar/escuela_ver/$comedor_presupuesto->id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("comedor/administrar/escuela_ver/$comedor_presupuesto->id", 'refresh');
			}
		}


		$this->comedor_presupuesto_model->fields['monto_presupuestado']['value'] = ($comedor_presupuesto->dias_albergado * $racion);
		$this->comedor_presupuesto_model->fields['monto_presupuestado']['disabled'] = TRUE;

		$data['monto_ideal'] = $monto_ideal;
		$data['fields2'] = $this->build_fields($this->comedor_presupuesto_model->fields, $comedor_presupuesto);
		$data['fields'] = $this->build_fields($this->escuela_model->fields, $escuela, TRUE);
		$data['comedor_presupuesto'] = $comedor_presupuesto;
		$data ['escuela'] = $escuela;
		$data['title'] = "Asignar Presupuesto";
		$data['txt_btn'] = NULL;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$this->load->view('comedor/administrar/modal_presupuesto', $data);
	}

	public function modal_editar_racion($mes_actual = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $mes_actual == NULL || !ctype_digit($mes_actual)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$raciones = $this->comedor_racion_model->get_raciones($mes_actual);

		if (empty($raciones)) {
			$this->modal_error('Debe importar las escuelas.', 'Falta importar');
			return;
		}
		$this->form_validation->set_rules('monto[]', 'Monto', 'numeric');
		$this->set_model_validation_rules($this->comedor_racion_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($mes_actual !== $this->input->post('mes_actual')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$raciones_id = $this->input->post('racion_id');
				$montos = $this->input->post('monto');
				$this->load->model('comedor/comedor_valor_racion_model');
				foreach ($raciones_id as $post_id => $racion_id) {
					$trans_ok &= $this->comedor_valor_racion_model->update(array(
						'id' => $racion_id,
						'monto' => $montos[$post_id]
						), FALSE);
				}

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->comedor_valor_racion_model->get_msg());
					redirect("comedor/administrar/listar_escuelas/$mes_actual", 'refresh');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->comedor_valor_racion_model->get_error());
					redirect("comedor/administrar/listar_escuelas/$mes_actual", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("comedor/administrar/listar_escuelas/$mes_actual", 'refresh');
			}
		}

		$data['raciones'] = $raciones;
		$data['mes_actual'] = $mes_actual;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['txt_btn'] = "Editar";
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = 'Editar racion';
		$this->load->view('comedor/administrar/modal_editar_racion', $data);
	}

	public function reporte_excel_comedor($ames = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $ames == NULL || !ctype_digit($ames)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$campos = array(
			'A' => array('N°', 7),
			'B' => array('Anexo', 10),
			'C' => array('Periodo', 10),
			'D' => array('Escuela', 35),
			'E' => array('Mes', 10),
			'F' => array('Alumnos con ración', 18),
			'G' => array('Asitencia media', 18),
			'H' => array('Ración completa', 18),
			'I' => array('Media racion', 18),
			'J' => array('Sin ración', 18),
		);
		$ciclo_lectivo = substr($ames, 0, 4);
		$mes = substr($ames, 4);

		$query = $this->db->query("SELECT e.numero,e.anexo,cp.periodo,e.nombre,
CONCAT(RIGHT(m.ames,2), '/', LEFT(m.ames,4)) as mes,
COALESCE(ai.alumnos, 0)as  alumnos,
COALESCE(SUM(COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) - COALESCE(ai.dias_falta, 0))/
SUM(COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0)),0) as asistencia_media,
COALESCE(vr.alumnos_r1, 0), COALESCE(vr.alumnos_r2, 0),COALESCE(vr.alumnos_sr, 0)
FROM division d
JOIN calendario ca ON ca.id = d.calendario_id
JOIN calendario_periodo cp ON cp.calendario_id = ca.id
JOIN planilla_asisnov_plazo m ON m.ames BETWEEN DATE_FORMAT(cp.inicio,'%Y%m') AND DATE_FORMAT(cp.fin,'%Y%m')
JOIN comedor_presupuesto cpre on cpre.escuela_id = d.escuela_id and cpre.mes = m.ames
JOIN escuela e ON e.id = d.escuela_id
LEFT JOIN(
		SELECT e.id, e.numero,e.nombre,e.anexo,cp.id as comedor_presuspuesto_id, cp.mes as mes,
		COUNT(DISTINCT CASE WHEN ca.comedor_racion_id=1 THEN  ad.alumno_id ELSE NULL END) alumnos_r1,
		COUNT(DISTINCT CASE WHEN ca.comedor_racion_id=2 THEN  ad.alumno_id ELSE NULL END) alumnos_r2,
		COUNT(DISTINCT CASE WHEN ca.comedor_racion_id IS NULL THEN  ad.alumno_id ELSE NULL END) alumnos_sr
		FROM division d
		JOIN curso cu ON cu.id = d.curso_id
        LEFT JOIN escuela e ON e.id = d.escuela_id
		LEFT JOIN alumno_division ad ON ad.division_id = d.id
		LEFT JOIN comedor_presupuesto cp ON cp.escuela_id = d.escuela_id
		LEFT JOIN comedor_alumno ca ON ad.id = ca.alumno_division_id AND cp.id = ca.comedor_presupuesto_id
		WHERE d.fecha_baja IS NULL
		AND (ad.fecha_hasta IS NULL OR DATE_FORMAT(ad.fecha_hasta,'%Y%m') >= ?)
		AND ad.ciclo_lectivo = ?
        AND cp.mes = ?
		GROUP BY e.id, cp.mes,ad.ciclo_lectivo
) vr ON vr.id = e.id AND vr.mes=m.ames AND vr.comedor_presuspuesto_id = cpre.id
LEFT JOIN (
	SELECT e.id, cp.periodo, m.ames mes, ad.ciclo_lectivo, COUNT(DISTINCT ad.id) alumnos, 
    SUM(CASE WHEN justificada='NC' THEN falta ELSE 0 END) dias_nc, 
	SUM(CASE WHEN justificada='NC' THEN 0 ELSE falta END) dias_falta
	FROM alumno_division ad
	JOIN division d ON ad.division_id = d.id AND ad.ciclo_lectivo = ?
    JOIN escuela e ON e.id = d.escuela_id
	JOIN calendario ca ON ca.id = d.calendario_id
	JOIN calendario_periodo cp ON cp.calendario_id = ca.id
	JOIN planilla_asisnov_plazo m ON m.ames BETWEEN DATE_FORMAT(ad.fecha_desde,'%Y%m') AND COALESCE(DATE_FORMAT(ad.fecha_hasta,'%Y%m'),m.ames)
    AND cp.inicio<=COALESCE(ad.fecha_hasta,cp.inicio) AND cp.fin>=ad.fecha_desde AND m.ames=?
    JOIN comedor_alumno cal ON cal.alumno_division_id = ad.id
    JOIN comedor_presupuesto ON comedor_presupuesto.id = cal.comedor_presupuesto_id
	LEFT JOIN division_inasistencia di ON ad.division_id=di.division_id AND ad.ciclo_lectivo=di.ciclo_lectivo AND di.mes = m.ames 
    AND di.periodo=cp.periodo
	LEFT JOIN division_inasistencia_dia did ON di.id=did.division_inasistencia_id
    LEFT JOIN alumno_inasistencia ai ON did.id=ai.division_inasistencia_dia_id AND cal.alumno_division_id = ai.alumno_division_id
    GROUP BY e.id, m.ames, cp.periodo, ad.ciclo_lectivo
) ai ON ai.id=e.id AND cp.ciclo_lectivo=ai.ciclo_lectivo AND m.ames=ai.mes AND cp.periodo=ai.periodo
LEFT JOIN division_inasistencia di ON d.id=di.division_id AND di.ciclo_lectivo = ? AND di.mes=m.ames AND di.periodo=cp.periodo
WHERE m.ames = ?
group by d.escuela_id, m.ames, cp.periodo;", array($ames, $ciclo_lectivo, $ames, $ciclo_lectivo, $ames, $ciclo_lectivo, $ames))->result_array();

		if (!empty($query)) {
			$this->load->library('PHPExcel');
			$this->phpexcel->getProperties()->setTitle("Reporte " . $this->nombres_meses[$mes] . '/' . $ciclo_lectivo)->setDescription("");
			$this->phpexcel->setActiveSheetIndex(0);
			$this->phpexcel->getDefaultStyle()->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('argb' => 'FFFFFFFF')
					),
				)
			);

			$sheet = $this->phpexcel->getActiveSheet();
			$sheet->setTitle(substr("Reporte comedor " . $ames, 0, 30));
			$encabezado = array();
			$ultima_columna = 'A';
			foreach ($campos as $columna => $campo) {
				$encabezado[] = $campo[0];
				$sheet->getColumnDimension($columna)->setWidth($campo[1]);
				$ultima_columna = $columna;
			}
			$sheet->freezePane('A3');
			$sheet->mergeCells('A1:J1');
			$sheet->getStyle('A1')->getAlignment()->applyFromArray(
				array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);
			$sheet->setCellValue('A1', ("Reporte escuelas comedor " . $this->nombres_meses[$mes] . '-' . $ciclo_lectivo));
			$sheet->getStyle('A1:' . $ultima_columna . '1')->getFont()->setBold(true);
			$sheet->fromArray(array($encabezado), NULL, 'A2');
			$sheet->fromArray($query, NULL, 'A3');
			$sheet->getStyle('A2:' . $ultima_columna . '2')->getFont()->setBold(true)->getColor()->setRGB('ffffff');
			$ultima_fila = $sheet->getHighestRow();
			$sheet->getStyle('G2:G' . $ultima_fila)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			$sheet->getStyle("A2:J$ultima_fila")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sheet->getStyle('A2:' . $ultima_columna . '2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('3c8dbc');


			header("Content-Type: application/vnd.ms-excel");
			$nombreArchivo = "Reporte comedor " . $ames;
			header("Content-Disposition: attachment; filename=\"$nombreArchivo.xls\"");
			header("Cache-Control: max-age=0");

			$writer = PHPExcel_IOFactory::createWriter($this->phpexcel, "Excel5");
			$writer->save('php://output');
			exit;
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect("comedor/administrar/listar_escuelas/$ames", 'refresh');
		}
	}

	public function reporte_excel_escuela_comedor($comedor_presupuesto_id = NULL, $ames = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $ames == NULL || !ctype_digit($ames) || $comedor_presupuesto_id == NULL || !ctype_digit($comedor_presupuesto_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$comedor_presupuesto = $this->comedor_presupuesto_model->get_one($comedor_presupuesto_id);
		if (empty($comedor_presupuesto)) {
			show_error('No se encontró el registro del presupuesto', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($comedor_presupuesto->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}


		$ciclo_lectivo = substr($ames, 0, 4);
		$mes = substr($ames, 4);

		$campos = array(
			'A' => array('Curso', 7),
			'B' => array('Division', 10),
			'C' => array('Turno', 35),
			'D' => array('Mes', 10),
			'E' => array('Alumnos con ración', 18),
			'F' => array('Asitencia media', 18),
			'G' => array('Ración completa', 18),
			'H' => array('Media racion', 18),
			'I' => array('Sin ración', 18),
		);

		$query = $this->db->query("SELECT c.descripcion as curso, d.division,t.descripcion as turno,CONCAT(RIGHT(m.ames,2), '/', LEFT(m.ames,4)) as mes,COALESCE(ai.alumnos, 0) alumnos,
(COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0) - COALESCE(ai.dias_falta, 0))/(COALESCE(ai.alumnos, 0)*di.dias - COALESCE(ai.dias_nc, 0)) as asistencia_media, vr.alumnos_r1, vr.alumnos_r2,vr.alumnos_sr
FROM division d
JOIN calendario ca ON ca.id = d.calendario_id
JOIN calendario_periodo cp ON cp.calendario_id = ca.id
JOIN planilla_asisnov_plazo m ON m.ames BETWEEN DATE_FORMAT(cp.inicio,'%Y%m') AND DATE_FORMAT(cp.fin,'%Y%m')
JOIN comedor_presupuesto cpre on cpre.escuela_id = d.escuela_id and cpre.mes = m.ames
JOIN curso c ON c.id = d.curso_id
JOIN(
		SELECT d.id, cu.id as curso_id, d.division, t.descripcion turno, cp.id as comedor_presuspuesto_id, cp.mes as mes,
		COUNT(DISTINCT CASE WHEN ca.comedor_racion_id=1 THEN  ad.alumno_id ELSE NULL END) alumnos_r1,
		COUNT(DISTINCT CASE WHEN ca.comedor_racion_id=2 THEN  ad.alumno_id ELSE NULL END) alumnos_r2,
		COUNT(DISTINCT CASE WHEN ca.comedor_racion_id IS NULL THEN  ad.alumno_id ELSE NULL END) alumnos_sr
		FROM division d
		JOIN curso cu ON cu.id = d.curso_id
		LEFT JOIN turno t ON t.id = d.turno_id
		LEFT JOIN alumno_division ad ON ad.division_id = d.id
		LEFT JOIN comedor_presupuesto cp ON cp.escuela_id = d.escuela_id and cp.mes = ?
		LEFT JOIN comedor_alumno ca ON ad.id = ca.alumno_division_id AND cp.id = ca.comedor_presupuesto_id
		WHERE d.escuela_id = ?
		AND d.fecha_baja IS NULL
		AND (ad.fecha_hasta IS NULL OR DATE_FORMAT(ad.fecha_hasta,'%Y%m') >= ?)
		AND ad.ciclo_lectivo = ?
		GROUP BY d.id, cp.mes
) vr ON vr.id = d.id AND vr.curso_id = c.id AND vr.mes=m.ames AND vr.comedor_presuspuesto_id = cpre.id
LEFT JOIN (
	SELECT di.id, cp.periodo, m.ames mes, d.id division_id, ad.ciclo_lectivo, COUNT(DISTINCT ad.id) alumnos, 
		SUM(CASE WHEN justificada='NC' THEN COALESCE(falta, 0) ELSE 0 END) dias_nc, 
		SUM(CASE WHEN justificada='NC' THEN 0 ELSE COALESCE(falta, 0) END) dias_falta
	FROM alumno_division ad
	JOIN division d ON ad.division_id = d.id AND d.escuela_id = ? AND ad.ciclo_lectivo = ?
	JOIN calendario ca ON ca.id = d.calendario_id
	JOIN calendario_periodo cp ON cp.calendario_id = ca.id
	JOIN planilla_asisnov_plazo m ON m.ames BETWEEN DATE_FORMAT(ad.fecha_desde,'%Y%m') AND COALESCE(DATE_FORMAT(ad.fecha_hasta,'%Y%m'),m.ames) AND cp.inicio<=COALESCE(ad.fecha_hasta,cp.inicio) AND cp.fin>=ad.fecha_desde AND m.ames =?
    JOIN comedor_alumno cal ON cal.alumno_division_id = ad.id
    JOIN comedor_presupuesto ON comedor_presupuesto.id = cal.comedor_presupuesto_id
	LEFT JOIN division_inasistencia di ON ad.division_id=di.division_id AND ad.ciclo_lectivo=di.ciclo_lectivo AND di.mes = m.ames 
    AND di.periodo=cp.periodo
	LEFT JOIN division_inasistencia_dia did ON di.id=did.division_inasistencia_id
    LEFT JOIN alumno_inasistencia ai ON did.id=ai.division_inasistencia_dia_id AND cal.alumno_division_id = ai.alumno_division_id
    GROUP BY di.id, d.id, m.ames, cp.periodo, ad.ciclo_lectivo) ai ON ai.division_id=d.id AND cp.ciclo_lectivo=ai.ciclo_lectivo
    AND m.ames=ai.mes AND cp.periodo=ai.periodo
LEFT JOIN division_inasistencia di ON d.id=di.division_id AND di.ciclo_lectivo = ? AND di.mes=m.ames AND di.periodo=cp.periodo
LEFT JOIN turno t ON t.id = d.turno_id
WHERE d.escuela_id = ? AND m.ames = ?
group by d.id,c.id, m.ames, cp.periodo
ORDER BY ca.nombre_periodo, cp.periodo, m.ames, c.descripcion, d.division", array($ames, $escuela->id, $ames, $ciclo_lectivo, $escuela->id, $ciclo_lectivo, $ames, $ciclo_lectivo, $escuela->id, $ames))->result_array();
		if (!empty($query)) {
			$this->load->library('PHPExcel');
			$this->phpexcel->getProperties()->setTitle("Reporte " . $this->nombres_meses[$mes] . '/' . $ciclo_lectivo)->setDescription("");
			$this->phpexcel->setActiveSheetIndex(0);
			$this->phpexcel->getDefaultStyle()->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('argb' => 'FFFFFFFF')
					),
				)
			);
			$sheet = $this->phpexcel->getActiveSheet();
			$sheet->setTitle(substr('Reporte comedor', 0, 30));
			$encabezado = array();
			$ultima_columna = 'A';
			foreach ($campos as $columna => $campo) {
				$encabezado[] = $campo[0];
				$sheet->getColumnDimension($columna)->setWidth($campo[1]);
				$ultima_columna = $columna;
			}

			$sheet->freezePane('A3');
			$sheet->mergeCells('A1:I1');
			$sheet->getStyle('A1')->getAlignment()->applyFromArray(
				array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);
			$sheet->setCellValue('A1', ("Reporte:" . $escuela->nombre_largo . " " . $this->nombres_meses[$mes] . '-' . $ciclo_lectivo));
			$sheet->getStyle('A1:' . $ultima_columna . '1')->getFont()->setBold(true);
			$sheet->fromArray(array($encabezado), NULL, 'A2');
			$sheet->fromArray($query, NULL, 'A3');
			$sheet->getStyle('A2:' . $ultima_columna . '2')->getFont()->setBold(true)->getColor()->setRGB('ffffff');
			$ultima_fila = $sheet->getHighestRow();
			$sheet->getStyle('F2:F' . $ultima_fila)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			$sheet->getStyle("A2:I$ultima_fila")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sheet->getStyle('A2:' . $ultima_columna . '2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('3c8dbc');



			header("Content-Type: application/vnd.ms-excel");
			$nombreArchivo = "Reporte " . $escuela->nombre_largo;
			header("Content-Disposition: attachment; filename=\"$nombreArchivo.xls\"");
			header("Cache-Control: max-age=0");

			$writer = PHPExcel_IOFactory::createWriter($this->phpexcel, "Excel5");
			$writer->save('php://output');
			exit;
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect("comedor/administrar/listar_escuelas/$ames", 'refresh');
		}
	}

	public function modal_editar_fecha_cierre($ames = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $ames == NULL || !ctype_digit($ames)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$comedor_plazo = $this->comedor_plazo_model->get(array('mes' => $ames));

		if (empty($comedor_plazo)) {
			$this->modal_error('No hay fecha cargada.', 'Error');
			return;
		}

		$this->set_model_validation_rules($this->comedor_plazo_model);
		if (isset($_POST) && !empty($_POST)) {
			if (empty($this->input->post('comedor_plazo')) || empty($this->input->post('mes')) || empty($this->input->post('fecha_cierre'))) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}

			$fecha_cierre = (new DateTime($this->input->post('fecha_cierre')));
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->comedor_plazo_model->update(array(
					'id' => $this->input->post('comedor_plazo'),
					'mes' => $this->input->post('mes'),
					'fecha_cierre' => $fecha_cierre->format('Y-m-d')
					), FALSE);
				if ($trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->comedor_plazo_model->get_msg());
					redirect("comedor/administrar/listar_escuelas/$ames", 'refresh');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->comedor_plazo_model->get_error());
					redirect("comedor/administrar/listar_escuelas/$ames", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("comedor/administrar/listar_escuelas/$ames", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->comedor_plazo_model->fields, $comedor_plazo[0], TRUE);
		$data['fields2'] = $this->build_fields($this->comedor_plazo_model->fields);
		$data['comedor_plazo'] = $comedor_plazo[0];
		$data['ames'] = $ames;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['txt_btn'] = "Editar";
		$data['class'] = ['agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active'];
		$data['title'] = 'Editar fecha de cierre';
		$this->load->view('comedor/administrar/modal_editar_fecha_cierre', $data);
	}
}