fe<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fechas_limite extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->roles_permitidos = array(ROL_JUNTAS, ROL_MODULO, ROL_ADMIN);
		$this->nav_route = 'titulo/titulo_persona';
		$this->modulos_permitidos = array(ROL_MODULO_ADMINISTRADOR_DE_JUNTAS);
	}

	public function ver() {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($this->usuario == '37' || $this->usuario == '3460') {
			$tableData = array(
				'columns' => array(
					array('label' => 'Fecha desde', 'data' => 'fecha_desde', 'render' => 'datetime', 'width' => 10),
					array('label' => 'Fecha hasta', 'data' => 'fecha_hasta', 'render' => 'datetime', 'width' => 25),
					array('label' => 'Detalle', 'data' => 'detalle', 'width' => 25),
					array('label' => '', 'data' => 'edit', 'width' => 5, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
				),
				'table_id' => 'fechas_table',
				'source_url' => 'juntas/fechas_limite/listar_data_fechas',
				'reuse_var' => TRUE,
				'initComplete' => "complete_fechas_table",
				'footer' => TRUE,
				'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
			);
			$data['html_table'] = buildHTML($tableData);
			$data['js_table'] = buildJS($tableData);
			$data['error'] = $this->session->flashdata('error');
			$data['message'] = $this->session->flashdata('message');
			$data['title'] = 'Fechas Límite';
			$this->load_template('juntas/fechas_limite/fechas_limite_ver', $data);
		} else {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
	}

	public function listar_data_fechas() {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables->set_database('bono_secundario');
		$this->datatables
			->select("fl.id, fl.fecha_desde, fl.fecha_hasta, fl.detalle")
			->unset_column('id')
			->from('bono_secundario.fechas_limites fl');
		if ($this->rol->entidad_tipo_id == '8' && $this->rol->entidad_id == '3') {
			$this->datatables->add_column('edit', '<a class="btn btn-xs btn-default" href="juntas/fechas_limite/modal_editar_fechas_limites/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-pencil"></i> Editar</a>', 'id');
			echo $this->datatables->generate();
		}
	}

	public function modal_editar_fechas_limites($fecha_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $fecha_id == NULL || !ctype_digit($fecha_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if ($this->usuario == '37' || $this->usuario == '3460') {
			$DB1 = $this->load->database('bono_secundario', TRUE);
			$this->load->model('juntas/fechas_limites_model');
			$this->fechas_limites_model->set_database($DB1);
			if (empty($fecha_id)) {
				redirect('juntas/escritorio/fechas_limite', 'refresh');
			} else {
				$fecha = $this->fechas_limites_model->get_one($fecha_id);
			}

			if (empty($fecha)) {
				$this->modal_error('No se encontró el registro a ver', 'Acción no autorizada');
				return;
			}

			if (isset($_POST) && !empty($_POST)) {
				if ($fecha_id !== $this->input->post('id')) {
					$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				}

				$trans_ok = TRUE;
//				$trans_ok &= $this->fechas_limites_model->update(array(
//					'id' => $this->input->post('id'),
//					'fecha_desde' => $this->get_date_sql('fecha_desde'),
//					'fecha_hasta' => $this->get_date_sql('fecha_hasta'),
//					'detalle' => $this->input->post('detalle')
//				));


				if ($DB1->trans_status() && $trans_ok) {
					$DB1->trans_commit();
					$this->session->set_flashdata('message', $this->fechas_limites_model->get_msg());
					redirect("juntas/fechas_limite/ver/", 'refresh');
				} else {
					$DB1->trans_rollback();
					$this->session->set_flashdata('error', $this->fechas_limites_model->get_error());
					redirect("juntas/fechas_limite/ver/", 'refresh');
				}
			}

			$data['fields'] = $this->build_fields($this->fechas_limites_model->fields, $fecha);
			$data['fecha'] = $fecha;
			$data['txt_btn'] = 'Editar';
			$data['title'] = 'Editar fecha';
			$this->load->view('juntas/fechas_limite/fechas_limite_modal', $data);
		} else {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
	}
}