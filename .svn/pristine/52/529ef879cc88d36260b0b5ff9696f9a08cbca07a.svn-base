<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Suple_persona_auditoria extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('suplementarias/suple_persona_auditoria_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_JEFE_LIQUIDACION, ROL_LIQUIDACION);
		$this->nav_route = 'suplementaria/suple_persona_auditoria';
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
				array('label' => 'Estado', 'data' => 'estado', 'width' => 10),
				array('label' => 'Fecha', 'data' => 'fecha', 'render' => 'datetime', 'width' => 10),
				array('label' => 'Usuario', 'data' => 'usuario_id', 'width' => 10),
				array('label' => 'Observaciones', 'data' => 'observaciones', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'suple_persona_auditoria_table',
			'source_url' => 'suplementarias/suple_persona_auditoria/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Gestión Escuelas Mendoza - Auditorías persona';
		$this->load_template('suplementarias/suple_persona_auditoria/suple_persona_auditoria_listar', $data);
	}

	public function listar_data()
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('suple_persona_auditoria.id, suple_persona_auditoria.suple_persona_id, suple_persona_auditoria.estado_id, suple_persona_auditoria.fecha, suple_persona_auditoria.usuario_id, suple_persona_auditoria.observaciones, suple_estado.id as estado, suple_persona.id as suple_persona')
			->unset_column('id')
			->from('suple_persona_auditoria')
			->join('suple_estado', 'suple_estado.id = suple_persona_auditoria.estado_id', 'left')
			->join('suple_persona', 'suple_persona.id = suple_persona_auditoria.suple_persona_id', 'left')
			->add_column('edit', '<a href="suplementarias/suple_persona_auditoria/ver/$1" class="btn btn-xs btn-default" title="Ver"><i class="fa fa-search"></i></a> <a href="suplementarias/suple_persona_auditoria/editar/$1" class="btn btn-xs btn-warning" title="Editar"><i class="fa fa-pencil"></i></a> <a href="suplementarias/suple_persona_auditoria/eliminar/$1" class="btn btn-xs btn-danger" title="Eliminar"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function agregar()
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		
		$this->load->model('suplementarias/suple_estado_model');
		$this->load->model('suplementarias/suple_persona_model');
		$this->array_estado_control = $array_estado = $this->get_array('suple_estado', 'id', 'id', null, array('' => '-- Seleccionar estado --'));
		$this->array_suple_persona_control = $array_suple_persona = $this->get_array('suple_persona', 'id', 'id', null, array('' => '-- Seleccionar persona --'));
		$this->set_model_validation_rules($this->suple_persona_auditoria_model);
		if ($this->form_validation->run() === TRUE)
		{
			$trans_ok = TRUE;
			$trans_ok&= $this->suple_persona_auditoria_model->create(array(
				'suple_persona_id' => $this->input->post('suple_persona'),
				'estado_id' => $this->input->post('estado'),
				'fecha' => $this->input->post('fecha'),
				'usuario_id' => $this->input->post('usuario'),
				'observaciones' => $this->input->post('observaciones')));

			if ($trans_ok)
			{
				$this->session->set_flashdata('message', $this->suple_persona_auditoria_model->get_msg());
				redirect('suplementarias/suple_persona_auditoria/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->suple_persona_auditoria_model->get_error() ? $this->suple_persona_auditoria_model->get_error() : $this->session->flashdata('error')));

		$this->suple_persona_auditoria_model->fields['estado']['array'] = $array_estado;
		$this->suple_persona_auditoria_model->fields['suple_persona']['array'] = $array_suple_persona;
		$data['fields'] = $this->build_fields($this->suple_persona_auditoria_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = 'Gestión Escuelas Mendoza - Agregar auditor�a persona';
		$this->load_template('suplementarias/suple_persona_auditoria/suple_persona_auditoria_abm', $data);
	}

	public function editar($id = NULL)
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$suple_persona_auditoria = $this->suple_persona_auditoria_model->get(array('id' => $id));
		if (empty($suple_persona_auditoria))
		{
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$this->load->model('suplementarias/suple_estado_model');
		$this->load->model('suplementarias/suple_persona_model');
		$this->array_estado_control = $array_estado = $this->get_array('suple_estado', 'id');
		$this->array_suple_persona_control = $array_suple_persona = $this->get_array('suple_persona', 'id');
		$this->set_model_validation_rules($this->suple_persona_auditoria_model);
		if (isset($_POST) && !empty($_POST))
		{
			if ($id !== $this->input->post('id'))
			{
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$trans_ok = TRUE;
				$trans_ok&= $this->suple_persona_auditoria_model->update(array(
					'id' => $this->input->post('id'),
					'suple_persona_id' => $this->input->post('suple_persona'),
					'estado_id' => $this->input->post('estado'),
					'fecha' => $this->input->post('fecha'),
					'usuario_id' => $this->input->post('usuario'),
					'observaciones' => $this->input->post('observaciones')));
				if ($trans_ok)
				{
					$this->session->set_flashdata('message', $this->suple_persona_auditoria_model->get_msg());
					redirect('suplementarias/suple_persona_auditoria/listar', 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->suple_persona_auditoria_model->get_error() ? $this->suple_persona_auditoria_model->get_error() : $this->session->flashdata('error')));

		$this->suple_persona_auditoria_model->fields['estado']['array'] = $array_estado;
		$this->suple_persona_auditoria_model->fields['suple_persona']['array'] = $array_suple_persona;
		$data['fields'] = $this->build_fields($this->suple_persona_auditoria_model->fields, $suple_persona_auditoria);

		$data['suple_persona_auditoria'] = $suple_persona_auditoria;

		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = 'Gestión Escuelas Mendoza - Editar auditor�a persona';
		$this->load_template('suplementarias/suple_persona_auditoria/suple_persona_auditoria_abm', $data);
	}

	public function eliminar($id = NULL)
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$suple_persona_auditoria = $this->suple_persona_auditoria_model->get(array('id' => $id));
		if (empty($suple_persona_auditoria))
		{
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}

		$this->load->model('suplementarias/suple_estado_model');
		$this->load->model('suplementarias/suple_persona_model');
		$array_estado = $this->get_array('suple_estado', 'id');
		$array_suple_persona = $this->get_array('suple_persona', 'id');
		if (isset($_POST) && !empty($_POST))
		{
			if ($id !== $this->input->post('id'))
			{
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			$trans_ok = TRUE;
			$trans_ok&= $this->suple_persona_auditoria_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok)
			{
				$this->session->set_flashdata('message', $this->suple_persona_auditoria_model->get_msg());
				redirect('suplementarias/suple_persona_auditoria/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->suple_persona_auditoria_model->get_error() ? $this->suple_persona_auditoria_model->get_error() : $this->session->flashdata('error')));

		$this->suple_persona_auditoria_model->fields['estado']['array'] = $array_estado;
		$this->suple_persona_auditoria_model->fields['suple_persona']['array'] = $array_suple_persona;
		$data['fields'] = $this->build_fields($this->suple_persona_auditoria_model->fields, $suple_persona_auditoria, TRUE);

		$data['suple_persona_auditoria'] = $suple_persona_auditoria;

		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = 'Gestión Escuelas Mendoza - Eliminar auditor�a persona';
		$this->load_template('suplementarias/suple_persona_auditoria/suple_persona_auditoria_abm', $data);
	}

	public function ver($id = NULL)
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$suple_persona_auditoria = $this->suple_persona_auditoria_model->get(array('id' => $id));
		if (empty($suple_persona_auditoria))
		{
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$this->load->model('suplementarias/suple_estado_model');
		$this->load->model('suplementarias/suple_persona_model');
		$array_estado = $this->get_array('suple_estado', 'id');
		$array_suple_persona = $this->get_array('suple_persona', 'id');
		$data['error'] = $this->session->flashdata('error');

		$this->suple_persona_auditoria_model->fields['estado']['array'] = $array_estado;
		$this->suple_persona_auditoria_model->fields['suple_persona']['array'] = $array_suple_persona;
		$data['fields'] = $this->build_fields($this->suple_persona_auditoria_model->fields, $suple_persona_auditoria, TRUE);

		$data['suple_persona_auditoria'] = $suple_persona_auditoria;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = 'Gestión Escuelas Mendoza - Ver auditor�a persona';
		$this->load_template('suplementarias/suple_persona_auditoria/suple_persona_auditoria_abm', $data);
	}
}
/* End of file Suple_persona_auditoria.php */
/* Location: ./application/modules/suplementarias/controllers/Suple_persona_auditoria.php */