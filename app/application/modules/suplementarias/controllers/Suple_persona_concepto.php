<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Suple_persona_concepto extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('suplementarias/suple_persona_concepto_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_JEFE_LIQUIDACION, ROL_LIQUIDACION);
		$this->nav_route = 'suplementaria/suple_persona_concepto';
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
				array('label' => 'Persona', 'data' => 'suple_persona', 'width' => 10),
				array('label' => 'Concepto', 'data' => 'concepto', 'width' => 10),
				array('label' => 'Importe', 'data' => 'importe', 'width' => 10),
				array('label' => 'Audi de User', 'data' => 'audi_user', 'class' => 'dt-body-right', 'width' => 10),
				array('label' => 'Audi de Fecha', 'data' => 'audi_fecha', 'render' => 'datetime', 'width' => 10),
				array('label' => 'Audi de Accion', 'data' => 'audi_accion', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'suple_persona_concepto_table',
			'source_url' => 'suplementarias/suple_persona_concepto/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Gestión Escuelas Mendoza - Concepto persona';
		$this->load_template('suplementarias/suple_persona_concepto/suple_persona_concepto_listar', $data);
	}

	public function listar_data()
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('suple_persona_concepto.id, suple_persona_concepto.suple_persona_id, suple_persona_concepto.concepto_id, suple_persona_concepto.importe, suple_persona_concepto.audi_user, suple_persona_concepto.audi_fecha, suple_persona_concepto.audi_accion, suple_concepto.id as concepto, suple_persona.id as suple_persona')
			->unset_column('id')
			->from('suple_persona_concepto')
			->join('suple_concepto', 'suple_concepto.id = suple_persona_concepto.concepto_id', 'left')
			->join('suple_persona', 'suple_persona.id = suple_persona_concepto.suple_persona_id', 'left')
			->add_column('edit', '<a href="suplementarias/suple_persona_concepto/ver/$1" class="btn btn-xs btn-default" title="Ver"><i class="fa fa-search"></i></a> <a href="suplementarias/suple_persona_concepto/editar/$1" class="btn btn-xs btn-warning" title="Editar"><i class="fa fa-pencil"></i></a> <a href="suplementarias/suple_persona_concepto/eliminar/$1" class="btn btn-xs btn-danger" title="Eliminar"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function agregar()
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		
		$this->load->model('suplementarias/suple_concepto_model');
		$this->load->model('suplementarias/suple_persona_model');
		$this->array_concepto_control = $array_concepto = $this->get_array('suple_concepto', 'id', 'id', null, array('' => '-- Seleccionar concepto --'));
		$this->array_suple_persona_control = $array_suple_persona = $this->get_array('suple_persona', 'id', 'id', null, array('' => '-- Seleccionar persona --'));
		$this->set_model_validation_rules($this->suple_persona_concepto_model);
		if ($this->form_validation->run() === TRUE)
		{
			$trans_ok = TRUE;
			$trans_ok&= $this->suple_persona_concepto_model->create(array(
				'suple_persona_id' => $this->input->post('suple_persona'),
				'concepto_id' => $this->input->post('concepto'),
				'importe' => $this->input->post('importe'),
				'audi_user' => $this->input->post('audi_user'),
				'audi_fecha' => $this->input->post('audi_fecha'),
				'audi_accion' => $this->input->post('audi_accion')));

			if ($trans_ok)
			{
				$this->session->set_flashdata('message', $this->suple_persona_concepto_model->get_msg());
				redirect('suplementarias/suple_persona_concepto/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->suple_persona_concepto_model->get_error() ? $this->suple_persona_concepto_model->get_error() : $this->session->flashdata('error')));

		$this->suple_persona_concepto_model->fields['concepto']['array'] = $array_concepto;
		$this->suple_persona_concepto_model->fields['suple_persona']['array'] = $array_suple_persona;
		$data['fields'] = $this->build_fields($this->suple_persona_concepto_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = 'Gestión Escuelas Mendoza - Agregar concepto persona';
		$this->load_template('suplementarias/suple_persona_concepto/suple_persona_concepto_abm', $data);
	}

	public function editar($id = NULL)
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$suple_persona_concepto = $this->suple_persona_concepto_model->get(array('id' => $id));
		if (empty($suple_persona_concepto))
		{
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$this->load->model('suplementarias/suple_concepto_model');
		$this->load->model('suplementarias/suple_persona_model');
		$this->array_concepto_control = $array_concepto = $this->get_array('suple_concepto', 'id');
		$this->array_suple_persona_control = $array_suple_persona = $this->get_array('suple_persona', 'id');
		$this->set_model_validation_rules($this->suple_persona_concepto_model);
		if (isset($_POST) && !empty($_POST))
		{
			if ($id !== $this->input->post('id'))
			{
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$trans_ok = TRUE;
				$trans_ok&= $this->suple_persona_concepto_model->update(array(
					'id' => $this->input->post('id'),
					'suple_persona_id' => $this->input->post('suple_persona'),
					'concepto_id' => $this->input->post('concepto'),
					'importe' => $this->input->post('importe'),
					'audi_user' => $this->input->post('audi_user'),
					'audi_fecha' => $this->input->post('audi_fecha'),
					'audi_accion' => $this->input->post('audi_accion')));
				if ($trans_ok)
				{
					$this->session->set_flashdata('message', $this->suple_persona_concepto_model->get_msg());
					redirect('suplementarias/suple_persona_concepto/listar', 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->suple_persona_concepto_model->get_error() ? $this->suple_persona_concepto_model->get_error() : $this->session->flashdata('error')));

		$this->suple_persona_concepto_model->fields['concepto']['array'] = $array_concepto;
		$this->suple_persona_concepto_model->fields['suple_persona']['array'] = $array_suple_persona;
		$data['fields'] = $this->build_fields($this->suple_persona_concepto_model->fields, $suple_persona_concepto);

		$data['suple_persona_concepto'] = $suple_persona_concepto;

		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = 'Gestión Escuelas Mendoza - Editar concepto persona';
		$this->load_template('suplementarias/suple_persona_concepto/suple_persona_concepto_abm', $data);
	}

	public function eliminar($id = NULL)
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$suple_persona_concepto = $this->suple_persona_concepto_model->get(array('id' => $id));
		if (empty($suple_persona_concepto))
		{
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}

		$this->load->model('suplementarias/suple_concepto_model');
		$this->load->model('suplementarias/suple_persona_model');
		$array_concepto = $this->get_array('suple_concepto', 'id');
		$array_suple_persona = $this->get_array('suple_persona', 'id');
		if (isset($_POST) && !empty($_POST))
		{
			if ($id !== $this->input->post('id'))
			{
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			$trans_ok = TRUE;
			$trans_ok&= $this->suple_persona_concepto_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok)
			{
				$this->session->set_flashdata('message', $this->suple_persona_concepto_model->get_msg());
				redirect('suplementarias/suple_persona_concepto/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->suple_persona_concepto_model->get_error() ? $this->suple_persona_concepto_model->get_error() : $this->session->flashdata('error')));

		$this->suple_persona_concepto_model->fields['concepto']['array'] = $array_concepto;
		$this->suple_persona_concepto_model->fields['suple_persona']['array'] = $array_suple_persona;
		$data['fields'] = $this->build_fields($this->suple_persona_concepto_model->fields, $suple_persona_concepto, TRUE);

		$data['suple_persona_concepto'] = $suple_persona_concepto;

		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = 'Gestión Escuelas Mendoza - Eliminar concepto persona';
		$this->load_template('suplementarias/suple_persona_concepto/suple_persona_concepto_abm', $data);
	}

	public function ver($id = NULL)
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$suple_persona_concepto = $this->suple_persona_concepto_model->get(array('id' => $id));
		if (empty($suple_persona_concepto))
		{
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$this->load->model('suplementarias/suple_concepto_model');
		$this->load->model('suplementarias/suple_persona_model');
		$array_concepto = $this->get_array('suple_concepto', 'id');
		$array_suple_persona = $this->get_array('suple_persona', 'id');
		$data['error'] = $this->session->flashdata('error');

		$this->suple_persona_concepto_model->fields['concepto']['array'] = $array_concepto;
		$this->suple_persona_concepto_model->fields['suple_persona']['array'] = $array_suple_persona;
		$data['fields'] = $this->build_fields($this->suple_persona_concepto_model->fields, $suple_persona_concepto, TRUE);

		$data['suple_persona_concepto'] = $suple_persona_concepto;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = 'Gestión Escuelas Mendoza - Ver concepto persona';
		$this->load_template('suplementarias/suple_persona_concepto/suple_persona_concepto_abm', $data);
	}
}
/* End of file Suple_persona_concepto.php */
/* Location: ./application/modules/suplementarias/controllers/Suple_persona_concepto.php */