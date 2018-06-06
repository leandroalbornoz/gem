<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Beca_etapa extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('becas/beca_etapa_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'beca/beca_etapa';
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
				array('label' => 'Beca', 'data' => 'beca', 'width' => 10),
				array('label' => 'Descripcion', 'data' => 'descripcion', 'width' => 10),
				array('label' => 'Inicio', 'data' => 'inicio', 'render' => 'date', 'width' => 10),
				array('label' => 'Fin', 'data' => 'fin', 'render' => 'date', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'beca_etapa_table',
			'source_url' => 'becas/beca_etapa/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Becas - Etapas Becas';
		$this->load_template('becas/beca_etapa/beca_etapa_listar', $data);
	}

	public function listar_data()
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('beca_etapa.id, beca_etapa.beca_id, beca_etapa.descripcion, beca_etapa.inicio, beca_etapa.fin, beca.descripcion as beca')
			->unset_column('id')
			->from('beca_etapa')
			->join('beca', 'beca.id = beca_etapa.beca_id', 'left')
			->add_column('edit', '<a href="becas/beca_etapa/ver/$1" class="btn btn-xs btn-default" title="Ver"><i class="fa fa-search"></i></a> <a href="becas/beca_etapa/editar/$1" class="btn btn-xs btn-warning" title="Editar"><i class="fa fa-pencil"></i></a> <a href="becas/beca_etapa/eliminar/$1" class="btn btn-xs btn-danger" title="Eliminar"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function agregar()
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos))
		{
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;

		}
		
		$this->load->model('becas/beca_model');
		$this->array_beca_control = $array_beca = $this->get_array('beca', 'descripcion', 'id', null, array('' => '-- Seleccionar beca --'));
		$this->set_model_validation_rules($this->beca_etapa_model);
		if ($this->form_validation->run() === TRUE)
		{
			$trans_ok = TRUE;
			$trans_ok&= $this->beca_etapa_model->create(array(
				'beca_id' => $this->input->post('beca'),
				'descripcion' => $this->input->post('descripcion'),
				'inicio' => $this->get_date_sql('inicio'),
				'fin' => $this->get_date_sql('fin')));

			if ($trans_ok)
			{
				$this->session->set_flashdata('message', $this->beca_etapa_model->get_msg());
				redirect('becas/beca_etapa/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->beca_etapa_model->get_error() ? $this->beca_etapa_model->get_error() : $this->session->flashdata('error')));

		$this->beca_etapa_model->fields['beca']['array'] = $array_beca;
		$data['fields'] = $this->build_fields($this->beca_etapa_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = 'Becas - Agregar etapa beca';
		$this->load_template('becas/beca_etapa/beca_etapa_abm', $data);
	}

	public function editar($id = NULL)
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$beca_etapa = $this->beca_etapa_model->get(array('id' => $id));
		if (empty($beca_etapa))
		{
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$this->load->model('becas/beca_model');
		$this->array_beca_control = $array_beca = $this->get_array('beca', 'descripcion');
		$this->set_model_validation_rules($this->beca_etapa_model);
		if (isset($_POST) && !empty($_POST))
		{
			if ($id !== $this->input->post('id'))
			{
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$trans_ok = TRUE;
				$trans_ok&= $this->beca_etapa_model->update(array(
					'id' => $this->input->post('id'),
					'beca_id' => $this->input->post('beca'),
					'descripcion' => $this->input->post('descripcion'),
					'inicio' => $this->get_date_sql('inicio'),
					'fin' => $this->get_date_sql('fin')));
				if ($trans_ok)
				{
					$this->session->set_flashdata('message', $this->beca_etapa_model->get_msg());
					redirect('becas/beca_etapa/listar', 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->beca_etapa_model->get_error() ? $this->beca_etapa_model->get_error() : $this->session->flashdata('error')));

		$this->beca_etapa_model->fields['beca']['array'] = $array_beca;
		$data['fields'] = $this->build_fields($this->beca_etapa_model->fields, $beca_etapa);

		$data['beca_etapa'] = $beca_etapa;

		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = 'Becas - Editar etapa beca';
		$this->load_template('becas/beca_etapa/beca_etapa_abm', $data);
	}

	public function eliminar($id = NULL)
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$beca_etapa = $this->beca_etapa_model->get(array('id' => $id));
		if (empty($beca_etapa))
		{
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}

		$this->load->model('becas/beca_model');
		$array_beca = $this->get_array('beca', 'descripcion');
		if (isset($_POST) && !empty($_POST))
		{
			if ($id !== $this->input->post('id'))
			{
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			$trans_ok = TRUE;
			$trans_ok&= $this->beca_etapa_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok)
			{
				$this->session->set_flashdata('message', $this->beca_etapa_model->get_msg());
				redirect('becas/beca_etapa/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->beca_etapa_model->get_error() ? $this->beca_etapa_model->get_error() : $this->session->flashdata('error')));

		$this->beca_etapa_model->fields['beca']['array'] = $array_beca;
		$data['fields'] = $this->build_fields($this->beca_etapa_model->fields, $beca_etapa, TRUE);

		$data['beca_etapa'] = $beca_etapa;

		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = 'Becas - Eliminar etapa beca';
		$this->load_template('becas/beca_etapa/beca_etapa_abm', $data);
	}

	public function ver($id = NULL)
	{
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id))
		{
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$beca_etapa = $this->beca_etapa_model->get(array('id' => $id));
		if (empty($beca_etapa))
		{
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$this->load->model('becas/beca_model');
		$array_beca = $this->get_array('beca', 'descripcion');
		$data['error'] = $this->session->flashdata('error');

		$this->beca_etapa_model->fields['beca']['array'] = $array_beca;
		$data['fields'] = $this->build_fields($this->beca_etapa_model->fields, $beca_etapa, TRUE);

		$data['beca_etapa'] = $beca_etapa;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = 'Becas - Ver etapa beca';
		$this->load_template('becas/beca_etapa/beca_etapa_abm', $data);
	}
}
/* End of file Beca_etapa.php */
/* Location: ./application/modules/becas/controllers/Beca_etapa.php */