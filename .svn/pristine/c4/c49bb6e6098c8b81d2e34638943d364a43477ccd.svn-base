<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Beca_estado extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('becas/beca_estado_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'beca/beca_estado';
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
				array('label' => 'Descripcion', 'data' => 'descripcion', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'beca_estado_table',
			'source_url' => 'becas/beca_estado/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Becas - Estados Becas';
		$this->load_template('becas/beca_estado/beca_estado_listar', $data);
	}

	public function listar_data()
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('beca_estado.id, beca_estado.descripcion')
			->unset_column('id')
			->from('beca_estado')
			->add_column('edit', '<a href="becas/beca_estado/ver/$1" class="btn btn-xs btn-default" title="Ver"><i class="fa fa-search"></i></a> <a href="becas/beca_estado/editar/$1" class="btn btn-xs btn-warning" title="Editar"><i class="fa fa-pencil"></i></a> <a href="becas/beca_estado/eliminar/$1" class="btn btn-xs btn-danger" title="Eliminar"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function agregar()
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos))
		{
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;

		}
		
		$this->set_model_validation_rules($this->beca_estado_model);
		if ($this->form_validation->run() === TRUE)
		{
			$trans_ok = TRUE;
			$trans_ok&= $this->beca_estado_model->create(array(
				'descripcion' => $this->input->post('descripcion')));

			if ($trans_ok)
			{
				$this->session->set_flashdata('message', $this->beca_estado_model->get_msg());
				redirect('becas/beca_estado/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->beca_estado_model->get_error() ? $this->beca_estado_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->beca_estado_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = 'Becas - Agregar estado beca';
		$this->load_template('becas/beca_estado/beca_estado_abm', $data);
	}

	public function editar($id = NULL)
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$beca_estado = $this->beca_estado_model->get(array('id' => $id));
		if (empty($beca_estado))
		{
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$this->set_model_validation_rules($this->beca_estado_model);
		if (isset($_POST) && !empty($_POST))
		{
			if ($id !== $this->input->post('id'))
			{
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$trans_ok = TRUE;
				$trans_ok&= $this->beca_estado_model->update(array(
					'id' => $this->input->post('id'),
					'descripcion' => $this->input->post('descripcion')));
				if ($trans_ok)
				{
					$this->session->set_flashdata('message', $this->beca_estado_model->get_msg());
					redirect('becas/beca_estado/listar', 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->beca_estado_model->get_error() ? $this->beca_estado_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->beca_estado_model->fields, $beca_estado);

		$data['beca_estado'] = $beca_estado;

		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = 'Becas - Editar estado beca';
		$this->load_template('becas/beca_estado/beca_estado_abm', $data);
	}

	public function eliminar($id = NULL)
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$beca_estado = $this->beca_estado_model->get(array('id' => $id));
		if (empty($beca_estado))
		{
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}
		if (isset($_POST) && !empty($_POST))
		{
			if ($id !== $this->input->post('id'))
			{
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			$trans_ok = TRUE;
			$trans_ok&= $this->beca_estado_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok)
			{
				$this->session->set_flashdata('message', $this->beca_estado_model->get_msg());
				redirect('becas/beca_estado/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->beca_estado_model->get_error() ? $this->beca_estado_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->beca_estado_model->fields, $beca_estado, TRUE);

		$data['beca_estado'] = $beca_estado;

		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = 'Becas - Eliminar estado beca';
		$this->load_template('becas/beca_estado/beca_estado_abm', $data);
	}

	public function ver($id = NULL)
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$beca_estado = $this->beca_estado_model->get(array('id' => $id));
		if (empty($beca_estado))
		{
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$data['error'] = $this->session->flashdata('error');

		$data['fields'] = $this->build_fields($this->beca_estado_model->fields, $beca_estado, TRUE);

		$data['beca_estado'] = $beca_estado;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = 'Becas - Ver estado beca';
		$this->load_template('becas/beca_estado/beca_estado_abm', $data);
	}
}
/* End of file Beca_estado.php */
/* Location: ./application/modules/becas/controllers/Beca_estado.php */