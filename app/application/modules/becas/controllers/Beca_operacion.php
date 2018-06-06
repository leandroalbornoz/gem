<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Beca_operacion extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('becas/beca_operacion_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'beca/beca_operacion';
	}

	public function listar()
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(//@todo arreglar anchos de columnas
				array('label' => 'Id', 'data' => 'id', 'class' => 'dt-body-right', 'width' => 10),
				array('label' => 'Etapa Beca', 'data' => 'beca_etapa', 'width' => 10),
				array('label' => 'Descripcion', 'data' => 'descripcion', 'width' => 10),
				array('label' => 'Beca de Estado', 'data' => 'beca_estado_o', 'width' => 10),
				array('label' => 'Beca de Estado', 'data' => 'beca_estado_d', 'width' => 10),
				array('label' => 'Cambia de Escuela', 'data' => 'cambia_escuela', 'width' => 10),
				array('label' => 'Cambia de Validador', 'data' => 'cambia_validador', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'beca_operacion_table',
			'source_url' => 'becas/beca_operacion/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Becas - Operaciones Becas';
		$this->load_template('becas/beca_operacion/beca_operacion_listar', $data);
	}

	public function listar_data()
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('beca_operacion.id, beca_operacion.beca_etapa_id, beca_operacion.descripcion, beca_operacion.beca_estado_o_id, beca_operacion.beca_estado_d_id, beca_operacion.cambia_escuela, beca_operacion.cambia_validador, beca_estado.descripcion as beca_estado_d, beca_estado.descripcion as beca_estado_o, beca_etapa.beca_id as beca_etapa')
			->unset_column('id')
			->from('beca_operacion')
			->join('beca_estado', 'beca_estado.id = beca_operacion.beca_estado_d_id', 'left')
			->join('beca_estado', 'beca_estado.id = beca_operacion.beca_estado_o_id', 'left')
			->join('beca_etapa', 'beca_etapa.id = beca_operacion.beca_etapa_id', 'left')
			->add_column('edit', '<a href="becas/beca_operacion/ver/$1" class="btn btn-xs btn-default" title="Ver"><i class="fa fa-search"></i></a> <a href="becas/beca_operacion/editar/$1" class="btn btn-xs btn-warning" title="Editar"><i class="fa fa-pencil"></i></a> <a href="becas/beca_operacion/eliminar/$1" class="btn btn-xs btn-danger" title="Eliminar"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function agregar()
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos))
		{
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;

		}
		
		$this->load->model('becas/beca_estado_model');
		$this->load->model('becas/beca_estado_model');
		$this->load->model('becas/beca_etapa_model');
		$this->array_beca_estado_d_control = $array_beca_estado_d = $this->get_array('beca_estado', 'descripcion', 'id', null, array('' => '-- Seleccionar estado beca --'));
		$this->array_beca_estado_o_control = $array_beca_estado_o = $this->get_array('beca_estado', 'descripcion', 'id', null, array('' => '-- Seleccionar estado beca --'));
		$this->array_beca_etapa_control = $array_beca_etapa = $this->get_array('beca_etapa', 'beca_id', 'id', null, array('' => '-- Seleccionar etapa beca --'));
		$this->set_model_validation_rules($this->beca_operacion_model);
		if ($this->form_validation->run() === TRUE)
		{
			$trans_ok = TRUE;
			$trans_ok&= $this->beca_operacion_model->create(array(
				'beca_etapa_id' => $this->input->post('beca_etapa'),
				'descripcion' => $this->input->post('descripcion'),
				'beca_estado_o_id' => $this->input->post('beca_estado_o'),
				'beca_estado_d_id' => $this->input->post('beca_estado_d'),
				'cambia_escuela' => $this->input->post('cambia_escuela'),
				'cambia_validador' => $this->input->post('cambia_validador')));

			if ($trans_ok)
			{
				$this->session->set_flashdata('message', $this->beca_operacion_model->get_msg());
				redirect('becas/beca_operacion/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->beca_operacion_model->get_error() ? $this->beca_operacion_model->get_error() : $this->session->flashdata('error')));

		$this->beca_operacion_model->fields['beca_estado_d']['array'] = $array_beca_estado_d;
		$this->beca_operacion_model->fields['beca_estado_o']['array'] = $array_beca_estado_o;
		$this->beca_operacion_model->fields['beca_etapa']['array'] = $array_beca_etapa;
		$data['fields'] = $this->build_fields($this->beca_operacion_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = 'Becas - Agregar operaci�n beca';
		$this->load_template('becas/beca_operacion/beca_operacion_abm', $data);
	}

	public function editar($id = NULL)
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$beca_operacion = $this->beca_operacion_model->get(array('id' => $id));
		if (empty($beca_operacion))
		{
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$this->load->model('becas/beca_estado_model');
		$this->load->model('becas/beca_estado_model');
		$this->load->model('becas/beca_etapa_model');
		$this->array_beca_estado_d_control = $array_beca_estado_d = $this->get_array('beca_estado', 'descripcion');
		$this->array_beca_estado_o_control = $array_beca_estado_o = $this->get_array('beca_estado', 'descripcion');
		$this->array_beca_etapa_control = $array_beca_etapa = $this->get_array('beca_etapa', 'beca_id');
		$this->set_model_validation_rules($this->beca_operacion_model);
		if (isset($_POST) && !empty($_POST))
		{
			if ($id !== $this->input->post('id'))
			{
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$trans_ok = TRUE;
				$trans_ok&= $this->beca_operacion_model->update(array(
					'id' => $this->input->post('id'),
					'beca_etapa_id' => $this->input->post('beca_etapa'),
					'descripcion' => $this->input->post('descripcion'),
					'beca_estado_o_id' => $this->input->post('beca_estado_o'),
					'beca_estado_d_id' => $this->input->post('beca_estado_d'),
					'cambia_escuela' => $this->input->post('cambia_escuela'),
					'cambia_validador' => $this->input->post('cambia_validador')));
				if ($trans_ok)
				{
					$this->session->set_flashdata('message', $this->beca_operacion_model->get_msg());
					redirect('becas/beca_operacion/listar', 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->beca_operacion_model->get_error() ? $this->beca_operacion_model->get_error() : $this->session->flashdata('error')));

		$this->beca_operacion_model->fields['beca_estado_d']['array'] = $array_beca_estado_d;
		$this->beca_operacion_model->fields['beca_estado_o']['array'] = $array_beca_estado_o;
		$this->beca_operacion_model->fields['beca_etapa']['array'] = $array_beca_etapa;
		$data['fields'] = $this->build_fields($this->beca_operacion_model->fields, $beca_operacion);

		$data['beca_operacion'] = $beca_operacion;

		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = 'Becas - Editar operaci�n beca';
		$this->load_template('becas/beca_operacion/beca_operacion_abm', $data);
	}

	public function eliminar($id = NULL)
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$beca_operacion = $this->beca_operacion_model->get(array('id' => $id));
		if (empty($beca_operacion))
		{
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}

		$this->load->model('becas/beca_estado_model');
		$this->load->model('becas/beca_estado_model');
		$this->load->model('becas/beca_etapa_model');
		$array_beca_estado_d = $this->get_array('beca_estado', 'descripcion');
		$array_beca_estado_o = $this->get_array('beca_estado', 'descripcion');
		$array_beca_etapa = $this->get_array('beca_etapa', 'beca_id');
		if (isset($_POST) && !empty($_POST))
		{
			if ($id !== $this->input->post('id'))
			{
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			$trans_ok = TRUE;
			$trans_ok&= $this->beca_operacion_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok)
			{
				$this->session->set_flashdata('message', $this->beca_operacion_model->get_msg());
				redirect('becas/beca_operacion/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->beca_operacion_model->get_error() ? $this->beca_operacion_model->get_error() : $this->session->flashdata('error')));

		$this->beca_operacion_model->fields['beca_estado_d']['array'] = $array_beca_estado_d;
		$this->beca_operacion_model->fields['beca_estado_o']['array'] = $array_beca_estado_o;
		$this->beca_operacion_model->fields['beca_etapa']['array'] = $array_beca_etapa;
		$data['fields'] = $this->build_fields($this->beca_operacion_model->fields, $beca_operacion, TRUE);

		$data['beca_operacion'] = $beca_operacion;

		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = 'Becas - Eliminar operaci�n beca';
		$this->load_template('becas/beca_operacion/beca_operacion_abm', $data);
	}

	public function ver($id = NULL)
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$beca_operacion = $this->beca_operacion_model->get(array('id' => $id));
		if (empty($beca_operacion))
		{
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$this->load->model('becas/beca_estado_model');
		$this->load->model('becas/beca_estado_model');
		$this->load->model('becas/beca_etapa_model');
		$array_beca_estado_d = $this->get_array('beca_estado', 'descripcion');
		$array_beca_estado_o = $this->get_array('beca_estado', 'descripcion');
		$array_beca_etapa = $this->get_array('beca_etapa', 'beca_id');
		$data['error'] = $this->session->flashdata('error');

		$this->beca_operacion_model->fields['beca_estado_d']['array'] = $array_beca_estado_d;
		$this->beca_operacion_model->fields['beca_estado_o']['array'] = $array_beca_estado_o;
		$this->beca_operacion_model->fields['beca_etapa']['array'] = $array_beca_etapa;
		$data['fields'] = $this->build_fields($this->beca_operacion_model->fields, $beca_operacion, TRUE);

		$data['beca_operacion'] = $beca_operacion;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = 'Becas - Ver operaci�n beca';
		$this->load_template('becas/beca_operacion/beca_operacion_abm', $data);
	}
}
/* End of file Beca_operacion.php */
/* Location: ./application/modules/becas/controllers/Beca_operacion.php */