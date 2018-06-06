<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Calendario extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('calendario_model');
		$this->load->model('calendario_periodo_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'menu/calendario';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 60),
				array('label' => 'Nombre de Período', 'data' => 'nombre_periodo', 'width' => 30),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'calendario_table',
			'footer' => TRUE,
			'reuse_var' => TRUE,
			'initComplete' => "complete_calendario_table",
			'source_url' => 'calendario/listar_data',
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Calendarios';
		$this->load_template('calendario/calendario_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('calendario.id, calendario.descripcion, calendario.nombre_periodo')
			->unset_column('id')
			->from('calendario');
			if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="calendario/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item" href="calendario/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '<li><a class="dropdown-item" href="calendario/eliminar/$1" title="Eliminar"><i class="fa fa-remove"></i> Eliminar</a></li>'
				. '</ul></div>', 'id');
		} else {
			$this->datatables->add_column('edit', '<a class="btn btn-xs btn-default" href="calendario/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id');
		}

		echo $this->datatables->generate();
	}

	public function agregar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->set_model_validation_rules($this->calendario_model);
		if ($this->form_validation->run() === TRUE) {
			$trans_ok = TRUE;
			$trans_ok&= $this->calendario_model->create(array(
				'descripcion' => $this->input->post('descripcion'),
				'nombre_periodo' => $this->input->post('nombre_periodo')));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->calendario_model->get_msg());
				redirect('calendario/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->calendario_model->get_error() ? $this->calendario_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->calendario_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = TITLE . ' - Agregar calendario';
		$this->load_template('calendario/calendario_abm', $data);
	}

	public function editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$calendario = $this->calendario_model->get(array('id' => $id));
		if (empty($calendario)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$periodo = $this->calendario_periodo_model->get_periodos($calendario->id);

		$this->set_model_validation_rules($this->calendario_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok&= $this->calendario_model->update(array(
					'id' => $this->input->post('id'),
					'descripcion' => $this->input->post('descripcion'),
					'nombre_periodo' => $this->input->post('nombre_periodo')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->calendario_model->get_msg());
					redirect("calendario/editar/$calendario->id", 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->calendario_model->get_error() ? $this->calendario_model->get_error() : $this->session->flashdata('error')));
		$data['message'] = $this->session->flashdata('message');
		$data['fields'] = $this->build_fields($this->calendario_model->fields, $calendario);
		$data['calendario'] = $calendario;
		$data['periodos'] = $periodo;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar calendario';
		$this->load_template('calendario/calendario_abm', $data);
	}

	public function eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$calendario = $this->calendario_model->get(array('id' => $id));
		if (empty($calendario)) {
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}
		$periodo = $this->calendario_periodo_model->get_periodos($calendario->id);
		
		
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$periodo = $this->calendario_periodo_model->get_periodos($calendario->id);
			if (!empty($periodo)) {
				foreach ($periodo as $periodo) {
					$trans_ok &= $this->calendario_periodo_model->delete(array('id' => $periodo->id), FALSE);
				}
			}
			$trans_ok &= $this->calendario_model->delete(array('id' => $this->input->post('id')),FALSE);
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->calendario_model->get_msg());
				redirect('calendario/listar', 'refresh');
			} else {
				$this->db->trans_rollback();
			}
		}
		
		$data['error'] = (validation_errors() ? validation_errors() : ($this->calendario_model->get_error() ? $this->calendario_model->get_error() : $this->session->flashdata('error')));
		$data['fields'] = $this->build_fields($this->calendario_model->fields, $calendario, TRUE);
		$data['calendario'] = $calendario;
		$data['periodos'] = $periodo;
		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = TITLE . ' - Eliminar calendario';
		$this->load_template('calendario/calendario_abm', $data);
	}

	public function ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$calendario = $this->calendario_model->get(array('id' => $id));
		if (empty($calendario)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$periodo = $this->calendario_periodo_model->get_periodos($calendario->id);

		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->calendario_model->fields, $calendario, TRUE);
		$data['periodos'] = $periodo;
		$data['calendario'] = $calendario;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver calendario';
		$this->load_template('calendario/calendario_abm', $data);
	}

	public function modal_agregar_periodo($calendario_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $calendario_id == NULL || !ctype_digit($calendario_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$calendario = $this->calendario_model->get(array('id' => $calendario_id));
		if (empty($calendario)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		unset($this->calendario_periodo_model->fields['calendario']);

		$this->set_model_validation_rules($this->calendario_periodo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok&= $this->calendario_periodo_model->create(array(
					'calendario_id' => $calendario_id,
					'ciclo_lectivo' =>$this->input->post('ciclo_lectivo'),
					'periodo' => $this->input->post('periodo'),
					'inicio' => $this->get_date_sql('inicio'),
					'fin' => $this->get_date_sql('fin')
					));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->calendario_periodo_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->calendario_periodo_model->get_error());
				}
				redirect("calendario/editar/$calendario_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("calendario/editar/$calendario_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->calendario_periodo_model->fields);
		$data['calendario']=$calendario;
		$data['title'] = 'Agregar nuevo período';
		$data['txt_btn'] = 'Agregar';
		$this->load->view('calendario/calendario_periodo_abm', $data);
	}

	public function modal_editar_periodo($periodo_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $periodo_id == NULL || !ctype_digit($periodo_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$periodo = $this->calendario_periodo_model->get_one($periodo_id);
		if (empty($periodo)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$calendario = $this->calendario_model->get_one($periodo->calendario_id);
		if (empty($calendario)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		unset($this->calendario_periodo_model->fields['calendario']);
		$this->set_model_validation_rules($this->calendario_periodo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($periodo_id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->calendario_periodo_model->update(array(
					'id' => $periodo_id,
					'calendario_id' => $calendario->id,
					'ciclo_lectivo' =>$this->input->post('ciclo_lectivo'),
					'periodo' => $this->input->post('periodo'),
					'inicio' => $this->get_date_sql('inicio'),
					'fin' => $this->get_date_sql('fin')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->calendario_periodo_model->get_msg());
					redirect("calendario/editar/$calendario->id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("calendario/editar/$calendario->id", 'refresh');
			}
		}
		$data['fields'] = $this->build_fields($this->calendario_periodo_model->fields, $periodo);
		$data['calendario']=$calendario;
		$data['periodo']=$periodo;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar Período del Calendario';
		$this->load->view('calendario/calendario_periodo_abm', $data);
	}

	public function eliminar_periodo($periodo_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $periodo_id == NULL || !ctype_digit($periodo_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		
		$periodo = $this->calendario_periodo_model->get_one($periodo_id);
		$calendario = $this->calendario_model->get_one($periodo->calendario_id);
		if (empty($calendario)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		if (empty($periodo)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		
		if (isset($_POST) && !empty($_POST)) {
			if ($periodo->id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->calendario_periodo_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->calendario_periodo_model->get_msg());
				redirect("calendario/editar/$calendario->id", 'refresh');
			}
		}
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar Periodo de Calendario';
		$data['calendario']=$calendario;
		$data['periodo']=$periodo;
		$data['fields'] = $this->build_fields($this->calendario_periodo_model->fields, $periodo, TRUE);
		$this->load->view('calendario/calendario_periodo_abm', $data);
	}
}
/* End of file Calendario.php */
/* Location: ./application/controllers/Calendario.php */