<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Abono_escuela_monto extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('abono/abono_escuela_monto_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION, ROL_DOCENTE);
		$this->modulos_permitidos = array(ROL_MODULO_TRANSPORTE);
		$this->nav_route = 'par/abono';
	}

	public function listar($mes = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($mes)) {
			$mes = date('Ym');
		}
		$tableData = array(
			'columns' => array(//@todo arreglar anchos de columnas
				array('label' => 'Escuela', 'data' => 'escuela', 'width' => 30),
				array('label' => 'Período', 'data' => 'ames', 'class' => 'dt-body-right', 'width' => 10),
				array('label' => 'Estado de Escuela', 'data' => 'abono_escuela_estado', 'width' => 14),
				array('label' => 'Monto', 'data' => 'monto', 'width' => 10),
				array('label' => 'Cupo Alumnos', 'data' => 'cupo_alumnos', 'width' => 10),
				array('label' => 'Alumnos', 'data' => 'alumnos', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 16, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'abono_escuela_monto_table',
			'source_url' => 'abono/abono_escuela_monto/listar_data/' . $mes
		);

		$mes_anterior = (new DateTime($mes . '01 -1 month'))->format('Ym');
		$data['mes_id'] = $mes;
		$data['mes_anterior'] = $mes_anterior;
		$data['mes'] = $this->nombres_meses[substr($mes, 4, 2)] . '\'' . substr($mes, 2, 2);
		$fecha = DateTime::createFromFormat('Ymd', $mes . '01');
		$data['fecha'] = $fecha->format('d/m/Y');
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Gestión Escuelas Mendoza - Transporte Escuela Monto';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('abono/abono_escuela_monto/abono_escuela_monto_listar', $data);
	}

	public function listar_data($mes = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select("abono_escuela_monto.id,CONCAT(escuela.numero,' - ',escuela.nombre) as escuela, abono_escuela_monto.ames, abono_escuela_estado.descripcion as abono_escuela_estado, abono_escuela_monto.monto, abono_escuela_monto.cupo_alumnos, COUNT(abono_alumno.id) as alumnos")
			->unset_column('id')
			->from('abono_escuela_monto')
			->join('escuela', 'escuela.id = abono_escuela_monto.escuela_id', 'left')
			->join('abono_escuela_estado', 'abono_escuela_estado.id = abono_escuela_monto.abono_escuela_estado_id', 'left')
			->join('abono_alumno', 'abono_alumno.ames=abono_escuela_monto.ames AND abono_alumno.escuela_id=abono_escuela_monto.escuela_id', 'left')
			->where('abono_escuela_monto.ames', $mes)
			->group_by('abono_escuela_monto.id')
			->add_column('edit', '<a href="abono/abono_escuela_monto/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default" title="Ver"><i class="fa fa-search"></i></a> <a href="abono/abono_escuela_monto/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning" title="Editar"><i class="fa fa-pencil"></i></a> <a href="abono/abono_escuela_monto/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger" title="Eliminar"><i class="fa fa-remove"></i></a> <a href="abono/abono_escuela_monto/listar_abono_alumno/$1" data-remote="false" class="btn btn-xs btn-default" title="Alumnos"><i class="fa fa-users"></i></a>', 'id');
		echo $this->datatables->generate();
	}

	public function modal_agregar($ames = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('escuela_model');
		$this->array_escuela_control = $array_escuela = $this->get_array('escuela', 'nombre_largo', 'id', array(
			'join' => array(
				array('abono_escuela_monto aem', 'aem.escuela_id=escuela.id', 'left')
			),
			'sort_by' => 'numero, nombre'
			), array('' => '-- Seleccionar Escuela--'));

		$this->load->model('abono/abono_escuela_estado_model');
		$this->array_abono_escuela_estado_control = $array_abono_escuela_estado = $this->get_array('abono_escuela_estado', 'descripcion', 'id', null, array('' => '-- Seleccionar Estado de Escuela --'));

		$this->set_model_validation_rules($this->abono_escuela_monto_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				if ($this->input->post('abono_escuela_estado') == 2) {
					$monto = '0';
					$cupo_alumnos = $this->input->post('cupo_alumnos');
				} else {
					$monto = $this->input->post('monto');
					$cupo_alumnos = '0';
				}
				$trans_ok&= $this->abono_escuela_monto_model->create(array(
					'escuela_id' => $this->input->post('escuela'),
					'ames' => $this->input->post('ames'),
					'abono_escuela_estado_id' => $this->input->post('abono_escuela_estado'),
					'monto' => $monto,
					'cupo_alumnos' => $cupo_alumnos
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->abono_escuela_monto_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->abono_escuela_monto_model->get_error());
				}
				redirect('abono/abono_escuela_monto/listar/'.$ames, 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('abono/abono_escuela_monto/listar/'.$ames, 'refresh');
			}
		}
		$data['ames'] = $ames;
		$this->abono_escuela_monto_model->fields['abono_escuela_estado']['array'] = $array_abono_escuela_estado;
		$this->abono_escuela_monto_model->fields['escuela']['array'] = $array_escuela;
		$data['fields'] = $this->build_fields($this->abono_escuela_monto_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar monto de transporte escuela';
		$this->load->view('abono/abono_escuela_monto/abono_escuela_monto_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$abono_escuela_monto = $this->abono_escuela_monto_model->get(array('id' => $id));
		if (empty($abono_escuela_monto)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->load->model('escuela_model');
		$this->array_escuela_control = $array_escuela = $this->get_array('escuela', 'nombre_largo', 'id', array(
			'join' => array(
				array('abono_escuela_monto aem', 'aem.escuela_id=escuela.id')
			),
			'sort_by' => 'numero, nombre'
			), array('' => '-- Seleccionar Escuela--'));

		$this->load->model('abono/abono_escuela_estado_model');
		$this->array_abono_escuela_estado_control = $array_abono_escuela_estado = $this->get_array('abono_escuela_estado', 'descripcion', 'id', null, array('' => '-- Seleccionar Estado de Escuela --'));

		$this->set_model_validation_rules($this->abono_escuela_monto_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				if ($this->input->post('abono_escuela_estado') == 2) {
					$monto = '0';
					$cupo_alumnos = $this->input->post('cupo_alumnos');
				} else {
					$monto = $this->input->post('monto');
					$cupo_alumnos = '0';
				}
				$trans_ok&= $this->abono_escuela_monto_model->update(array(
					'id' => $this->input->post('id'),
					'escuela_id' => $this->input->post('escuela'),
					'ames' => $this->input->post('ames'),
					'abono_escuela_estado_id' => $this->input->post('abono_escuela_estado'),
					'monto' => $monto,
					'cupo_alumnos' => $cupo_alumnos));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->abono_escuela_monto_model->get_msg());
					redirect('abono/abono_escuela_monto/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('abono/abono_escuela_monto/listar', 'refresh');
			}
		}
		$this->abono_escuela_monto_model->fields['abono_escuela_estado']['array'] = $array_abono_escuela_estado;
		$this->abono_escuela_monto_model->fields['escuela']['array'] = $array_escuela;
		$data['fields'] = $this->build_fields($this->abono_escuela_monto_model->fields, $abono_escuela_monto);
		$data['abono_escuela_monto'] = $abono_escuela_monto;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar monto de transporte escuela';
		$this->load->view('abono/abono_escuela_monto/abono_escuela_monto_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$abono_escuela_monto = $this->abono_escuela_monto_model->get_one($id);
		if (empty($abono_escuela_monto)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get(array('id' => $abono_escuela_monto->escuela_id));
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$trans_ok = TRUE;
			$trans_ok&= $this->abono_escuela_monto_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->abono_escuela_monto_model->get_msg());
				redirect('abono/abono_escuela_monto/listar', 'refresh');
			}
		}
		$data['fields'] = $this->build_fields($this->abono_escuela_monto_model->fields, $abono_escuela_monto, TRUE);
		$data['abono_escuela_monto'] = $abono_escuela_monto;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar monto de transporte escuela';
		$this->load->view('abono/abono_escuela_monto/abono_escuela_monto_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$abono_escuela_monto = $this->abono_escuela_monto_model->get_one($id);
		if (empty($abono_escuela_monto)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}
		$this->load->model('escuela_model');
		$this->get_array('escuela', 'nombre_largo', 'id', array(
			'join' => array(
				array('abono_escuela_monto aem', 'aem.escuela_id=escuela.id')
			),
			'sort_by' => 'numero, nombre'
		));
		$this->load->model('abono/abono_escuela_estado_model');
		$data['fields'] = $this->build_fields($this->abono_escuela_monto_model->fields, $abono_escuela_monto, TRUE);
		$data['abono_escuela_monto'] = $abono_escuela_monto;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver monto de transporte escuela';
		$this->load->view('abono/abono_escuela_monto/abono_escuela_monto_abm', $data);
	}

	public function listar_abono_alumno($abono_escuela_monto_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $abono_escuela_monto_id == NULL || !ctype_digit($abono_escuela_monto_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$abono_escuela_monto = $this->abono_escuela_monto_model->get_one($abono_escuela_monto_id);
		if (empty($abono_escuela_monto)) {
			show_error('La escuela no tiene asignado un monto para abonos', 500, 'Registro no encontrado');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($abono_escuela_monto->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$this->load->model('abono/abono_alumno_model');
		if (!empty($this->abono_alumno_model->get_abonos_ames($escuela->id, (new DateTime($abono_escuela_monto->ames . '01 -1 month'))->format('Ym'))) && empty($this->abono_alumno_model->get_abonos_ames($escuela->id, $abono_escuela_monto->ames))) {
			$escuela_mes = true;
		} else {
			$escuela_mes = false;
		}
		$monto_sum_escuela_mes = $this->abono_alumno_model->get_suma_monto_mes($escuela->id, $abono_escuela_monto->ames);
		$cantidad_alumnos_espera = $this->abono_alumno_model->get_cantidad_alumnos_espera($escuela->id, $abono_escuela_monto->ames);
		$tableData = array(
			'columns' => array(
				array('label' => 'N° Abono', 'data' => 'numero_abono', 'class' => 'dt-body-left', 'width' => 8),
				array('label' => 'Alumno', 'data' => 'alumno', 'width' => 15),
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10),
				array('label' => 'División', 'data' => 'division', 'width' => 18),
				array('label' => 'Tipo Abono', 'data' => 'abono_tipo', 'width' => 10),
				array('label' => 'Monto', 'data' => 'monto', 'width' => 10, 'render' => 'money', 'class' => 'dt-body-right'),
				array('label' => 'Motivo Alta', 'data' => 'motivo_alta', 'width' => 20),
				array('label' => 'Estado Alumno', 'data' => 'estado', 'width' => 8),
				array('label' => '', 'data' => 'edit', 'width' => 4, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'abono_alumno_table',
			'source_url' => 'abono/abono_escuela_monto/listar_abono_alumno_data/' . $escuela->id . '/' . 0 . '/' . $abono_escuela_monto->ames
		);
		$data['cantidad_alumnos_espera'] = $cantidad_alumnos_espera->cantidad;
		$data['monto_total_escuela'] = $abono_escuela_monto->monto - $monto_sum_escuela_mes->monto_escuela_ames;
		$data['division_id'] = 0;
		$data['escuela_mes'] = $escuela_mes;
		$data['mes_id'] = $abono_escuela_monto->ames;
		$data['abono_escuela_monto'] = $abono_escuela_monto;
		$data['mes'] = $this->nombres_meses[substr($abono_escuela_monto->ames, 4, 2)] . '\'' . substr($abono_escuela_monto->ames, 2, 2);
		$fecha = DateTime::createFromFormat('Ymd', $abono_escuela_monto->ames . '01');
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
		$this->load_template('abono/abono_escuela_monto/abono_alumno_listar', $data);
	}

	public function listar_abono_alumno_data($escuela_id = NULL, $division_id = NULL, $mes = NULL) {
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
			->add_column('edit', '<a href="abono/abono_alumno/modal_ver/$1/' . $escuela_id . '/' . $mes . '" class="btn btn-xs btn-default" title="Ver" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i></a>', 'id');
		if ($division_id > 0) {
			$this->datatables->where('division.id', $division_id);
		}
		echo $this->datatables->generate();
	}

	public function cambiar_mes($mes) {
		$model = new stdClass();
		$model->fields = array(
			'mes' => array('label' => 'Mes', 'type' => 'date', 'required' => TRUE)
		);
		$this->set_model_validation_rules($model);
		if ($this->form_validation->run() === TRUE) {
			$mes_nuevo = (new DateTime($this->get_date_sql('mes')))->format('Ym');
			$this->session->set_flashdata('message', 'Mes cambiado correctamente');
		} else {
			$this->session->set_flashdata('error', 'Error al cambiar mes');
		}
		redirect("abono/abono_escuela_monto/listar/$mes_nuevo", 'refresh');
	}
}
/* End of file Abono_escuela_monto.php */
/* Location: ./application/modules/abono/controllers/Abono_escuela_monto.php */