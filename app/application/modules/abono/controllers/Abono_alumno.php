<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Abono_alumno extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('abono/abono_alumno_model');
		$this->load->model('abono/abono_tipo_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION, ROL_DOCENTE);
		$this->nav_route = 'par/abono_alumno';
	}

	public function listar($escuela_id = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		if (empty($mes) || empty(DateTime::createFromFormat('Ym', $mes))) {
			$mes = date('Ym');
			redirect("abono/abono_alumno/listar/$escuela_id/$mes", 'refresh');
		}
		if (!empty($this->abono_alumno_model->get_abonos_ames($escuela_id, (new DateTime($mes . '01 -1 month'))->format('Ym'))) && empty($this->abono_alumno_model->get_abonos_ames($escuela_id, $mes))) {
			$escuela_mes = true;
		} else {
			$escuela_mes = false;
		}
		$this->load->model('abono/abono_escuela_monto_model');
		$monto_escuela_mes = $this->abono_escuela_monto_model->get_escuela_mes($escuela_id, $mes);
		if (empty($monto_escuela_mes)) {
			show_error('La escuela no tiene asignado un monto para abonos', 500, 'Registro no encontrado');
		}
		$monto_sum_escuela_mes = $this->abono_alumno_model->get_suma_monto_mes($escuela_id, $mes);
		$cupos_sum_escuela_mes = $this->abono_alumno_model->get_suma_cupos_mes($escuela_id, $mes);
		$cantidad_alumnos_espera = $this->abono_alumno_model->get_cantidad_alumnos_espera($escuela_id, $mes);
		$tableData = array(
			'columns' => array(
				array('label' => 'N° Abono', 'data' => 'numero_abono', 'class' => 'dt-body-left', 'width' => 10),
				array('label' => 'Alumno', 'data' => 'alumno', 'width' => 15),
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10),
				array('label' => 'División', 'data' => 'division', 'width' => 18),
				array('label' => 'Tipo Abono', 'data' => 'abono_tipo', 'width' => 10),
				array('label' => 'Monto', 'data' => 'monto', 'width' => 6, 'render' => 'money', 'class' => 'dt-body-right'),
				array('label' => 'Motivo Alta', 'data' => 'motivo_alta', 'width' => 14),
				array('label' => 'Estado Alumno', 'data' => 'estado', 'width' => 8),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'abono_alumno_table',
			'source_url' => 'abono/abono_alumno/listar_data/' . $escuela_id . '/' . 0 . '/' . $mes
		);
		$monto_total_escuela = $monto_escuela_mes->monto - $monto_sum_escuela_mes->monto_escuela_ames;
		$cupos_total_escuela = $monto_escuela_mes->cupo_alumnos - $cupos_sum_escuela_mes->cupos_escuela_ames;

		if ($monto_total_escuela == 0 && $cantidad_alumnos_espera->cantidad >= 5) {
			$this->session->set_flashdata('warning', "No pueden cargarse mas de 5 alumnos en espera");
		}

		$data['cupos_total_escuela'] = $cupos_total_escuela;
		$data['monto_total_escuela'] = round($monto_total_escuela, 2);
		$data['cantidad_alumnos_espera'] = $cantidad_alumnos_espera->cantidad;
		$data['division_id'] = 0;
		$data['monto_escuela_mes'] = $monto_escuela_mes;
		$data['escuela_mes'] = $escuela_mes;
		$data['mes_id'] = $mes;
		$data['mes'] = $this->nombres_meses[substr($mes, 4, 2)] . '\'' . substr($mes, 2, 2);
		$fecha = DateTime::createFromFormat('Ymd', $mes . '01');
		$data['fecha'] = $fecha->format('d/m/Y');
		$data['alumno_id'] = $this->session->flashdata('alumno_id');
		$data['escuela'] = $escuela;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['warning'] = $this->session->flashdata('warning');
		$data['title'] = 'Gestión Escuelas Mendoza - Abono Escuela';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('abono/abono_alumno/abono_alumno_listar', $data);
	}

	public function listar_data($escuela_id = NULL, $division_id = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select("abono_alumno.id, abono_alumno.numero_abono, CONCAT(persona.apellido, ', ', persona.nombre) alumno, CONCAT(documento_tipo.descripcion_corta, ' ', persona.documento) documento, abono_tipo.descripcion abono_tipo, abono_alumno.monto, abono_motivo_alta.descripcion as motivo_alta, CONCAT(curso.descripcion, ' ', division.division) division, alumno.id as alumno_id,abono_alumno_estado.descripcion as estado")
			->unset_column('id')
			->from('abono_alumno')
			->join('alumno', 'alumno.id = abono_alumno.alumno_id')
			->join('persona', 'persona.id = alumno.persona_id')
			->join('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id')
			->join('alumno_division', 'alumno_division.alumno_id = alumno.id AND alumno_division.fecha_hasta IS NULL', 'left')
			->join('division', 'division.id = alumno_division.division_id', 'left')
			->join('curso', 'curso.id = division.curso_id', 'left')
			->join('escuela', 'escuela.id = abono_alumno.escuela_id')
			->join('abono_tipo', 'abono_tipo.id = abono_alumno.abono_tipo_id', 'left')
			->join('abono_motivo_alta', 'abono_motivo_alta.id = abono_alumno.abono_motivo_alta_id', 'left')
			->join('abono_alumno_estado', 'abono_alumno_estado.id = abono_alumno.abono_alumno_estado_id')
			->where('escuela.id', $escuela_id)
			->where('abono_alumno.ames', $mes)
			->group_by('alumno.id, abono_alumno.id')
			->add_column('edit', '<a href="abono/abono_alumno/modal_ver/$1/' . $escuela_id . '/' . $mes . '" class="btn btn-xs btn-default" title="Ver" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i></a> <a href="abono/abono_alumno/modal_editar/$1/' . $escuela_id . '/' . $division_id . '/' . $mes . '" class="btn btn-xs btn-warning" title="Editar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-pencil"></i></a> <a href="abono/abono_alumno/modal_eliminar/$1/' . $escuela_id . '/' . $division_id . '/' . $mes . '" class="btn btn-xs btn-danger" title="Eliminar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i></a>', 'id');
		if ($division_id > 0) {
			$this->datatables->where('division.id', $division_id);
		}
		echo $this->datatables->generate();
	}

	public function listar_division($escuela_id = NULL, $division_id = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		if (empty($mes) || empty(DateTime::createFromFormat('Ym', $mes))) {
			$mes = date('Ym');
			redirect("abono/abono_alumno/listar_division/$escuela_id/$division_id/$mes", 'refresh');
		}
		if (!empty($this->abono_alumno_model->get_abonos_ames($escuela_id, (new DateTime($mes . '01 -1 month'))->format('Ym'))) && empty($this->abono_alumno_model->get_abonos_ames($escuela_id, $mes))) {
			$escuela_mes = true;
		} else {
			$escuela_mes = false;
		}
		$this->load->model('abono/abono_escuela_monto_model');
		$monto_escuela_mes = $this->abono_escuela_monto_model->get_escuela_mes($escuela_id, $mes);
		if (empty($monto_escuela_mes)) {
			show_error('La escuela no tiene asignado un monto para abonos', 500, 'Registro no encontrado');
		}
		$monto_sum_escuela_mes = $this->abono_alumno_model->get_suma_monto_mes($escuela_id, $mes);
		$cupos_sum_escuela_mes = $this->abono_alumno_model->get_suma_cupos_mes($escuela_id, $mes);
		$cantidad_alumnos_espera = $this->abono_alumno_model->get_cantidad_alumnos_espera($escuela_id, $mes);
		$tableData = array(
			'columns' => array(
				array('label' => 'N° Abono', 'data' => 'numero_abono', 'class' => 'dt-body-left', 'width' => 10),
				array('label' => 'Alumno', 'data' => 'alumno', 'width' => 15),
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10),
				array('label' => 'División', 'data' => 'division', 'width' => 20),
				array('label' => 'Tipo Abono', 'data' => 'abono_tipo', 'width' => 10),
				array('label' => 'Monto', 'data' => 'monto', 'width' => 8, 'render' => 'money', 'class' => 'dt-body-right'),
				array('label' => 'Motivo Alta', 'data' => 'motivo_alta', 'width' => 15),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'abono_alumno_table',
			'source_url' => 'abono/abono_alumno/listar_data/' . $escuela_id . '/' . $division_id . '/' . $mes
		);

		$monto_total_escuela = $monto_escuela_mes->monto - $monto_sum_escuela_mes->monto_escuela_ames;
		$cupos_total_escuela = $monto_escuela_mes->cupo_alumnos - $cupos_sum_escuela_mes->cupos_escuela_ames;

		if ($monto_total_escuela == 0 && $cantidad_alumnos_espera->cantidad >= 5) {
			$this->session->set_flashdata('warning', "No pueden cargarse mas de 5 alumnos en espera");
		}

		$data['cupos_total_escuela'] = $cupos_total_escuela;
		$data['monto_total_escuela'] = $monto_total_escuela;
		$data['cantidad_alumnos_espera'] = $cantidad_alumnos_espera->cantidad;
		$data['monto_escuela_mes'] = $monto_escuela_mes;
		$data['division_id'] = $division_id;
		$data['escuela_mes'] = $escuela_mes;
		$data['mes_id'] = $mes;
		$data['mes'] = $this->nombres_meses[substr($mes, 4, 2)] . '\'' . substr($mes, 2, 2);
		$fecha = DateTime::createFromFormat('Ymd', $mes . '01');
		$data['fecha'] = $fecha->format('d/m/Y');
		$data['alumno_id'] = $this->session->flashdata('alumno_id');
		$data['escuela'] = $escuela;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Gestión Escuelas Mendoza - Abono Escuela';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('abono/abono_alumno/abono_alumno_listar', $data);
	}

	public function modal_eliminar($id = NULL, $escuela_id = NULL, $division_id = NULL, $ames = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$abono_alumno = $this->abono_alumno_model->get_one($id);
		if (empty($abono_alumno)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró la escuela', 500, 'Registro no encontrado');
		}
		$this->load->model('abono/abono_escuela_monto_model');
		$monto_escuela_mes = $this->abono_escuela_monto_model->get_escuela_mes($escuela_id, $ames);
		if (empty($monto_escuela_mes)) {
			show_error('La escuela no tiene asignado un monto para abonos', 500, 'Registro no encontrado');
		}
		$monto_sum_escuela_mes = $this->abono_alumno_model->get_suma_monto_mes($escuela_id, $ames);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$url = $this->input->post('url_redireccion');
			$division_id = $this->input->post('division_id');
			$trans_ok = TRUE;
			$trans_ok&= $this->abono_alumno_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				if ($url == FALSE) {
					$this->session->set_flashdata('message', $this->abono_alumno_model->get_msg());
					if ($division_id > 0) {
						redirect("abono/abono_alumno/listar_division/$escuela_id/$division_id/$ames", 'refresh');
					} else {
						redirect("abono/abono_alumno/listar/$escuela_id/$ames", 'refresh');
					}
				} else {
					redirect($url, 'refresh');
				}
			}
		}
		if (!empty($_GET['redirect_url'])) {
			$data['url_redireccion'] = urldecode($_GET['redirect_url']);
		} else {
			$data['url_redireccion'] = FALSE;
		}
		$monto_total_escuela = $monto_escuela_mes->monto - $monto_sum_escuela_mes->monto_escuela_ames;
		$data['ames'] = $ames;
		$data['monto_abono_alumno'] = $abono_alumno->monto;
		$data['monto_total_escuela'] = $monto_total_escuela;
		$data['monto_escuela_mes'] = $monto_escuela_mes;
		$data['fields'] = $this->build_fields($this->abono_alumno_model->fields, $abono_alumno, TRUE);
		$data['abono_alumno'] = $abono_alumno;
		$data['division_id'] = $division_id;
		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = 'Gestión Escuelas Mendoza - Eliminar abono escuelas';
		$this->load->view('abono/abono_alumno/abono_alumno_modal_abm', $data);
	}

	public function modal_buscar($escuela_id = NULL, $ames = NULL, $division_id = NULL) {
		$this->load->model('division_model');
		$division = $this->division_model->get_one($division_id);
		if (!empty($division->fecha_baja)) {
			$this->modal_error('No se puede editar una división cerrada', 'Error');
			return;
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró la escuela', 500, 'Registro no encontrado');
		}
		$model_busqueda = new stdClass();
		$model_busqueda->fields = array(
			'd_documento' => array('label' => 'Documento', 'maxlength' => '100', 'minlength' => '3'),
			'd_apellido' => array('label' => 'Apellido', 'maxlength' => '100', 'minlength' => '3'),
			'd_nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'minlength' => '3'),
		);
		$this->set_model_validation_rules($model_busqueda);
		if (isset($_POST) && !empty($_POST)) {
			$alumno_id = $this->input->post('alumno_id');
			$division_id = $this->input->post('division_id');
			if ($alumno_id !== '') {
				$this->session->set_flashdata('alumno_id', $alumno_id);
				$this->session->set_flashdata('escuela_id', $escuela->id);
				$mes = $this->input->post('ames');
			}
			if ($division_id > 0) {
				redirect("abono/abono_alumno/listar_division/$escuela->id/$division_id/$mes", 'refresh');
			} else {
				redirect("abono/abono_alumno/listar/$escuela->id/$mes", 'refresh');
			}
		}
		$data['division_id'] = $division_id;
		$data['fields'] = $this->build_fields($model_busqueda->fields);
		$data['escuela'] = $escuela;
		$data['ames'] = $ames;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Buscar Alumno a agregar';
		$this->load->view('abono/abono_alumno/abono_alumno_modal_buscar', $data);
	}

	public function modal_agregar_abono_alumno($alumno_id = NULL, $escuela_id = NULL, $division_id = NULL, $ames = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_id == NULL || !ctype_digit($alumno_id) || $escuela_id == NULL || !ctype_digit($escuela_id) || $ames == NULL || !ctype_digit($ames)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró la escuela', 500, 'Registro no encontrado');
		}
		$this->load->model('alumno_model');
		$alumno = $this->alumno_model->get_one($alumno_id);
		if (empty($alumno)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		$this->load->model('abono/abono_escuela_monto_model');
		$monto_escuela_mes = $this->abono_escuela_monto_model->get_escuela_mes($escuela_id, $ames);
		if (empty($monto_escuela_mes)) {
			show_error('La escuela no tiene asignado un monto para abonos', 500, 'Registro no encontrado');
		}
		$monto_sum_escuela_mes = $this->abono_alumno_model->get_suma_monto_mes($escuela_id, $ames);
		$cupos_sum_escuela_mes = $this->abono_alumno_model->get_suma_cupos_mes($escuela_id, $ames);
		$cantidad_alumnos_espera = $this->abono_alumno_model->get_cantidad_alumnos_espera($escuela_id, $ames);
		$this->load->model('abono/abono_tipo_model');
		$this->array_abono_tipo_control = $array_abono_tipo = $this->get_array('abono_tipo', 'descripcion', 'id', null, array('' => '-- Seleccionar Tipo Abono --'));
		unset($array_abono_tipo[4]); // se elimina el tipo particular para que no se vea en el combo.
		$this->load->model('abono/abono_motivo_alta_model');
		$this->array_motivo_alta_control = $array_motivo_alta = $this->get_array('abono_motivo_alta', 'descripcion', 'id', null, array('' => '-- Seleccionar Motivo de Alta --'));

		$this->set_model_validation_rules($this->abono_alumno_model);
		if (isset($_POST) && !empty($_POST)) {
			$url = $this->input->post('url_redireccion');
			$mes = $this->input->post('ames');
			$division_id = $this->input->post('division_id');
			$cantidad_alumnos_espera = $this->input->post('cantidad_alumnos_espera');
			if ($this->input->post('abono_alumno_estado_id') == 1) {
				$monto_final = $this->input->post('monto_total_escuela') - $this->input->post('monto');
				$cupo_final = $this->input->post('cupos_total_escuela') - 1;
			} else {
				$monto_final = 0;
				$cupo_final = 0;
			}
			if ($this->form_validation->run() === TRUE) {
				$abono_validado = $this->abono_alumno_model->valida_abono($mes, $this->input->post('abono_tipo'), $this->input->post('numero_abono'));
				if (empty($abono_validado) && $monto_final >= 0 && $cupo_final >= 0 || $cantidad_alumnos_espera < 5) {
					$trans_ok = TRUE;
					$trans_ok &= $this->abono_alumno_model->create(array(
						'numero_abono' => $this->input->post('numero_abono'),
						'monto' => $this->input->post('monto'),
						'alumno_id' => $this->input->post('alumno'),
						'abono_tipo_id' => $this->input->post('abono_tipo'),
						'abono_motivo_alta_id' => $this->input->post('motivo_alta'),
						'abono_alumno_estado_id' => $this->input->post('abono_alumno_estado_id'),
						'fecha_alta' => date('Y-m-d H:i:s'),
						'ames' => $mes,
						'escuela_id' => $this->input->post('escuela'),));
				} else {
					$trans_ok = FALSE;
				}
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->abono_alumno_model->get_msg());
				} else {
					if ($monto_final < 0) {
						$this->session->set_flashdata('error', 'No puede superar el monto restante.');
					} elseif ($cupo_final < 0) {
						$this->session->set_flashdata('error', 'No puede superar el cupo de alumnos.');
					} elseif ($monto_final == 0 && $cantidad_alumnos_espera >= 5) {
						$this->session->set_flashdata('error', 'No puede cargar mas de 5 alumnos en espera.');
					} elseif (!empty($abono_validado)) {
						$this->session->set_flashdata('error', 'Ese abono ya esta cargado en este mes.');
					} else {
						$this->session->set_flashdata('error', 'Hubo un error en la carga.');
					}
				}
				if ($url == FALSE) {
					if ($division_id > 0) {
						redirect("abono/abono_alumno/listar_division/$escuela->id/$division_id/$mes", 'refresh');
					} else {
						redirect("abono/abono_alumno/listar/$escuela->id/$mes", 'refresh');
					}
				} else {
					redirect($url, 'refresh');
				}
			} else {
				if ($url == FALSE) {
					$this->session->set_flashdata('error', validation_errors());
					if ($division_id > 0) {
						redirect("abono/abono_alumno/listar_division/$escuela->id/$division_id/$mes", 'refresh');
					} else {
						redirect("abono/abono_alumno/listar/$escuela->id/$mes", 'refresh');
					}
				} else {
					redirect($url, 'refresh');
				}
			}
		}
		if (!empty($_GET['redirect_url'])) {
			$data['url_redireccion'] = urldecode($_GET['redirect_url']);
		} else {
			$data['url_redireccion'] = FALSE;
		}
		$cupos_total_escuela = $monto_escuela_mes->cupo_alumnos - $cupos_sum_escuela_mes->cupos_escuela_ames;
		$monto_total_escuela = $monto_escuela_mes->monto - $monto_sum_escuela_mes->monto_escuela_ames;
		$this->abono_alumno_model->fields['abono_tipo']['array'] = $array_abono_tipo;
		$this->abono_alumno_model->fields['motivo_alta']['array'] = $array_motivo_alta;
		$data['fields'] = $this->build_fields($this->abono_alumno_model->fields);
		$data['cupos_total_escuela'] = $cupos_total_escuela;
		$data['monto_escuela_mes'] = $monto_escuela_mes;
		$data['ames'] = $ames;
		$data['monto_total_escuela'] = $monto_total_escuela;
		$data['cantidad_alumnos_espera'] = $cantidad_alumnos_espera->cantidad;
		$data['division_id'] = $division_id;
		$data['escuela'] = $escuela;
		$data['alumno'] = $alumno;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar abono a: ';
		$this->load->view('abono/abono_alumno/abono_alumno_modal_agregar', $data);
	}

	public function modal_editar($id = NULL, $escuela_id = NULL, $division_id = NULL, $ames = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró la escuela', 500, 'Registro no encontrado');
		}
		$abono_alumno = $this->abono_alumno_model->get_one($id);
		if (empty($abono_alumno)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->load->model('abono/abono_escuela_monto_model');
		$monto_escuela_mes = $this->abono_escuela_monto_model->get_escuela_mes($escuela_id, $ames);
		if (empty($monto_escuela_mes)) {
			show_error('La escuela no tiene asignado un monto para abonos', 500, 'Registro no encontrado');
		}
		$monto_sum_escuela_mes = $this->abono_alumno_model->get_suma_monto_mes($escuela_id, $ames);
		$this->load->model('abono/abono_tipo_model');
		$this->array_alumno_control = $array_abono_tipo = $this->get_array('abono_tipo', 'descripcion');
		unset($array_abono_tipo[4]); // se elimina el tipo particular para que no se vea en el combo.
		$this->load->model('abono/abono_motivo_alta_model');
		$this->array_motivo_alta_control = $array_motivo_alta = $this->get_array('abono_motivo_alta', 'descripcion');
		$this->set_model_validation_rules($this->abono_alumno_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$trans_ok = TRUE;
			$mes = $this->input->post('mes');
			$division_id = $this->input->post('division_id');
			$url = $this->input->post('url_redireccion');
			$monto_final = ($this->input->post('monto_total_escuela') + $this->input->post('monto_abono_alumno')) - $this->input->post('monto');
			if ($monto_final >= 0) {
				$trans_ok&= $this->abono_alumno_model->update(array(
					'id' => $this->input->post('id'),
					'numero_abono' => $this->input->post('numero_abono'),
					'abono_tipo_id' => $this->input->post('abono_tipo'),
					'abono_motivo_alta_id' => $this->input->post('motivo_alta'),
					'monto' => $this->input->post('monto')
				));
			} else {
				$negativo = TRUE;
				$trans_ok = FALSE;
			}
			if ($trans_ok) {
				if ($url == FALSE) {
					$this->session->set_flashdata('message', $this->abono_alumno_model->get_msg());
					if ($division_id > 0) {
						redirect("abono/abono_alumno/listar_division/$escuela_id/$division_id/$mes", 'refresh');
					} else {
						redirect("abono/abono_alumno/listar/$escuela_id/$mes", 'refresh');
					}
				} else {
					redirect($url, 'refresh');
				}
			} else {
				if ($url == FALSE) {
					if ($negativo == TRUE) {
						$this->session->set_flashdata('error', "No puede tener un monto negativo");
					} else {
						$this->session->set_flashdata('error', $this->abono_alumno_model->get_msg());
					}
					if ($division_id > 0) {
						redirect("abono/abono_alumno/listar_division/$escuela_id/$division_id/$mes", 'refresh');
					} else {
						redirect("abono/abono_alumno/listar/$escuela_id/$mes", 'refresh');
					}
				} else {
					redirect($url, 'refresh');
				}
			}
		}
		if (!empty($_GET['redirect_url'])) {
			$data['url_redireccion'] = urldecode($_GET['redirect_url']);
		} else {
			$data['url_redireccion'] = FALSE;
		}
		$monto_total_escuela = $monto_escuela_mes->monto - $monto_sum_escuela_mes->monto_escuela_ames;
		$data['monto_total_escuela'] = $monto_total_escuela;
		$data['monto_escuela_mes'] = $monto_escuela_mes;
		$data['ames'] = $ames;
		$this->abono_alumno_model->fields['motivo_alta']['array'] = $array_motivo_alta;
		$this->abono_alumno_model->fields['abono_tipo']['array'] = $array_abono_tipo;
		$data['division_id'] = $division_id;
		$data['fields'] = $this->build_fields($this->abono_alumno_model->fields, $abono_alumno);
		$data['abono_alumno'] = $abono_alumno;
		$data['monto_abono_alumno'] = $abono_alumno->monto;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar Abono Alumno';
		$this->load->view('abono/abono_alumno/abono_alumno_modal_abm', $data);
	}

	public function modal_ver($id = NULL, $escuela_id = NULL, $ames = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$abono_alumno = $this->abono_alumno_model->get_one($id);
		if (empty($abono_alumno)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}
		$this->load->model('abono/abono_escuela_monto_model');
		$monto_escuela_mes = $this->abono_escuela_monto_model->get_escuela_mes($escuela_id, $ames);
		if (empty($monto_escuela_mes)) {
			show_error('La escuela no tiene asignado un monto para abonos', 500, 'Registro no encontrado');
		}
		$monto_sum_escuela_mes = $this->abono_alumno_model->get_suma_monto_mes($escuela_id, $ames);
		if (!empty($_GET['redirect_url'])) {
			$data['url_redireccion'] = urldecode($_GET['redirect_url']);
		} else {
			$data['url_redireccion'] = FALSE;
		}
		$monto_total_escuela = $monto_escuela_mes->monto - $monto_sum_escuela_mes->monto_escuela_ames;
		$data['ames'] = $ames;
		$data['monto_abono_alumno'] = $abono_alumno->monto;
		$data['monto_total_escuela'] = $monto_total_escuela;
		$data['monto_escuela_mes'] = $monto_escuela_mes;
		$data['fields'] = $this->build_fields($this->abono_alumno_model->fields, $abono_alumno, TRUE);
		$data['abono_alumno'] = $abono_alumno;
		$data['division_id'] = 0;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver Abono';
		$this->load->view('abono/abono_alumno/abono_alumno_modal_abm', $data);
	}

	public function cambiar_mes($escuela_id, $mes, $division_id) {
		$model = new stdClass();
		$model->fields = array(
			'mes' => array('label' => 'Mes', 'type' => 'date', 'required' => TRUE)
		);
		$this->set_model_validation_rules($model);
		if ($this->form_validation->run() === TRUE) {
			$mes_nuevo = (new DateTime($this->get_date_sql('mes')))->format('Ym');
			$this->load->model('abono/abono_alumno_model');
			$escuela_ames_monto = $this->abono_alumno_model->get_abonos_escuela_ames($escuela_id, $mes_nuevo);
			if (!empty($escuela_ames_monto)) {
				$this->session->set_flashdata('message', 'Mes cambiado correctamente');
			} else {
				$this->session->set_flashdata('error', 'No tiene un monto cargado para el mes elegido');
				redirect("abono/abono_alumno/listar/$escuela_id/$mes", 'refresh');
			}
		} else {
			$this->session->set_flashdata('error', 'Error al cambiar mes');
		}
		if ($division_id > 0) {
			redirect("abono/abono_alumno/listar_division/$escuela_id/$division_id/$mes_nuevo", 'refresh');
		} else {
			//$escuela_ames = $this->abono_alumno_model->get_abonos_escuela($escuela_id);
			redirect("abono/abono_alumno/listar/$escuela_id/$mes_nuevo", 'refresh');
		}
	}

	public function listar_anteriores($escuela_id = NULL, $mes = NULL, $division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		if (empty($mes) || empty(DateTime::createFromFormat('Ym', $mes))) {
			$mes = date('Ym');
			if ($division_id > 0) {
				redirect("abono/abono_alumno/listar_division/$escuela_id/$mes/$division_id", 'refresh');
			} else {
				redirect("abono/abono_alumno/listar/$escuela_id/$mes/$division_id", 'refresh');
			}
		}
		$mes_anterior = (new DateTime($mes . '01 -1 month'))->format('Ym');
		$tableData = array(
			'columns' => array(//@todo arreglar anchos de columnas
				array('label' => 'N° Abono', 'data' => 'numero_abono', 'class' => 'dt-body-left', 'width' => 10),
				array('label' => 'Alumno', 'data' => 'alumno', 'width' => 15),
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10),
				array('label' => 'División', 'data' => 'division', 'width' => 20),
				array('label' => 'Tipo Abono', 'data' => 'abono_tipo', 'width' => 10),
				array('label' => 'Monto', 'data' => 'monto', 'width' => 8, 'render' => 'money', 'class' => 'dt-body-right'),
				array('label' => 'Motivo Alta', 'data' => 'motivo_alta', 'width' => 15),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'abono_alumno_table',
			'source_url' => 'abono/abono_alumno/listar_anteriores_data/' . $escuela_id . '/' . $mes_anterior,
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_table",
			'footer' => TRUE,
			'disablePagination' => TRUE,
			'dom' => 'rt'
		);

		$model = new stdClass();
		$model->fields = array(
			'abono_alumno[]' => array('label' => 'Abono Alumno', 'required' => TRUE),
		);
		$this->form_validation->set_rules('ames', 'Mes nuevo', 'integer|required');
		$this->set_model_validation_rules($model);
		if ($this->form_validation->run() === TRUE) {
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$abono_alumno_ids = $this->input->post('abono_alumno');
			$count = count($abono_alumno_ids);
			for ($i = 0; $i < $count; $i++) {
				$abono_alumno_nuevo = $this->abono_alumno_model->get_one($abono_alumno_ids[$i]);
				$valida_abono = $this->abono_alumno_model->valida_abono($this->input->post('ames'), $abono_alumno_nuevo->abono_tipo_id, $abono_alumno_nuevo->numero_abono);
				if ($trans_ok && empty($valida_abono)) {
					$trans_ok &= $this->abono_alumno_model->create(array(
						'alumno_id' => $abono_alumno_nuevo->alumno_id,
						'abono_tipo_id' => $abono_alumno_nuevo->abono_tipo_id,
						'abono_motivo_alta_id' => $abono_alumno_nuevo->abono_motivo_alta_id,
						'numero_abono' => $abono_alumno_nuevo->numero_abono,
						'monto' => $abono_alumno_nuevo->monto,
						'fecha_alta' => date('Y-m-d H:i:s'),
						'ames' => $this->input->post('ames'),
						'escuela_id' => $this->input->post('escuela_id'),
						'abono_alumno_estado_id' => $this->input->post('abono_alumno_estado_id'),), FALSE);
				}
			}
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Movimiento realizado satisfactoriamente');
				redirect("abono/abono_alumno/listar/$escuela_id/$mes", 'refresh');
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al intentar actualizar.';
				if ($this->abono_alumno_model->get_error())
					$errors .= '<br>' . $this->abono_alumno_model->get_error();
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->abono_alumno_model->get_error() ? $this->abono_alumno_model->get_error() : $this->session->flashdata('error')));
		$data['mes_id'] = $mes;
		$data['mes_anterior'] = $mes_anterior;
		$data['mes'] = $this->nombres_meses[substr($mes_anterior, 4, 2)] . '\'' . substr($mes_anterior, 2, 2);
		$fecha = DateTime::createFromFormat('Ymd', $mes_anterior . '01');
		$data['fecha'] = $fecha->format('d/m/Y');
		$data['alumno_id'] = $this->session->flashdata('alumno_id');
		$data['escuela'] = $escuela;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Gestión Escuelas Mendoza - Abono Escuela';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('abono/abono_alumno/abono_alumno_listar_anteriores', $data);
	}

	public function listar_anteriores_data($escuela_id = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select("abono_alumno.id, abono_alumno.numero_abono,alumno.id as alumno_id,abono_tipo.id as abono_tipo_id,abono_motivo_alta.id as abono_motivo_alta_id, CONCAT(persona.apellido, ', ', persona.nombre) alumno, CONCAT(documento_tipo.descripcion_corta, ' ', persona.documento) documento, abono_tipo.descripcion abono_tipo, abono_alumno.monto, abono_motivo_alta.descripcion as motivo_alta, CONCAT(curso.descripcion, ' ', division.division) division, alumno.id as alumno_id, abono_alumno_estado.id as abono_alumno_estado_id")
			->unset_column('id')
			->from('abono_alumno')
			->join('alumno', 'alumno.id = abono_alumno.alumno_id', 'left')
			->join('persona', 'persona.id = alumno.persona_id', 'left')
			->join('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id', 'left')
			->join('alumno_division', 'alumno_division.alumno_id = alumno.id AND alumno_division.fecha_hasta IS NULL', 'left')
			->join('division', 'division.id = alumno_division.division_id', 'left')
			->join('curso', 'curso.id = division.curso_id', 'left')
			->join('escuela', 'escuela.id = division.escuela_id', 'left')
			->join('abono_tipo', 'abono_tipo.id = abono_alumno.abono_tipo_id', 'left')
			->join('abono_motivo_alta', 'abono_motivo_alta.id = abono_alumno.abono_motivo_alta_id', 'left')
			->join('abono_alumno_estado', 'abono_alumno_estado.id = abono_alumno.abono_alumno_estado_id', 'left')
			->where('escuela.id', $escuela_id)
			->where('abono_alumno.ames', $mes)
			->group_by('alumno.id, abono_alumno.id')
			->add_column('edit', '<a href="abono/abono_alumno/modal_ver/$1/' . $escuela_id . '/' . $mes . '" class="btn btn-xs btn-default" title="Ver" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i></a> <div class="checkbox"><input type="checkbox" name="abono_alumno[]" value="$1"> </div>', 'id');
		echo $this->datatables->generate();
	}
}
/* End of file Abono_alumno.php */
/* Location: ./application/modules/abono/controllers/Abono_alumno.php */