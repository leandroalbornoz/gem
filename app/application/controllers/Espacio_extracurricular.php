<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Espacio_extracurricular extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('nivel_model');
		$this->load->model('espacio_curricular_model');
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'admin/espacio_extracurricular';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Descripción', 'data' => 'descripcion', 'width' => 44),
				array('label' => 'Linea', 'data' => 'linea', 'width' => 44),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'espacio_extracurricular_table',
			'source_url' => 'espacio_extracurricular/listar_data',
			'disablePagination' => TRUE,
			'dom' => 'rt'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Espacios extracurriculares';
		$this->load_template('espacio_extracurricular/espacio_extracurricular_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('nivel.id, nivel.descripcion, nivel.linea_id, linea.nombre as linea')
			->unset_column('id')
			->from('nivel')
			->join('linea', 'linea.id = nivel.linea_id', 'left')
			->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="espacio_extracurricular/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item" href="espacio_extracurricular/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '</ul></div>', 'id');

		echo $this->datatables->generate();
	}

	public function editar($nivel_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $nivel_id == NULL || !ctype_digit($nivel_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$nivel = $this->nivel_model->get(array('id' => $nivel_id));
		if (empty($nivel)) {
			show_error('No se encontró el registro de nivel', 500, 'Registro no encontrado');
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->nivel_model->get_error() ? $this->nivel_model->get_error() : $this->session->flashdata('error')));
		$data['message'] = $this->session->flashdata('message');

		$data['nivel'] = $nivel;
		$this->load->model('curso_model');
		$cursos = $this->curso_model->get(array('nivel_id' => $nivel_id, 'sort_by' => 'id'));
		$espacios_curriculares = $this->espacio_curricular_model->get(array(
			'join' => array(
				array('materia', 'materia.id=espacio_curricular.materia_id', 'left', array('materia.descripcion as materia', 'materia.grupo_id')),
				array('curso', 'curso.id=espacio_curricular.curso_id AND espacio_curricular.carrera_id IS NULL'),
			),
			'where' => array("curso.nivel_id=$nivel->id"),
			'sort_by' => 'espacio_curricular.id'
		));

		$array_cursos = array();
		if (!empty($cursos)) {
			foreach ($cursos as $curso) {
				$array_cursos[$curso->id] = $curso;
			}
		}

		if (!empty($espacios_curriculares)) {
			foreach ($espacios_curriculares as $espacio_curricular) {
				$array_cursos[$espacio_curricular->curso_id]->materias[] = $espacio_curricular;
			}
		}

		$data['cursos'] = $array_cursos;

		$data['txt_btn'] = 'Editar';
		$data['class'] = array('ver' => '', 'editar' => 'active btn-app-zetta-active');
		$data['title'] = TITLE . ' - Editar espacios extracurriculares';
		$this->load_template('espacio_extracurricular/espacio_extracurricular_abm', $data);
	}

	public function ver($nivel_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $nivel_id == NULL || !ctype_digit($nivel_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$nivel = $this->nivel_model->get(array('id' => $nivel_id));
		if (empty($nivel)) {
			show_error('No se encontró el registro de nivel', 500, 'Registro no encontrado');
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->nivel_model->get_error() ? $this->nivel_model->get_error() : $this->session->flashdata('error')));
		$data['message'] = $this->session->flashdata('message');

		$data['error'] = $this->session->flashdata('error');

		$data['nivel'] = $nivel;
		$this->load->model('curso_model');
		$cursos = $this->curso_model->get(array(
			'join' => array(
				array('espacio_curricular', 'espacio_curricular.curso_id = curso.id AND espacio_curricular.carrera_id IS NULL', 'left'),
				array('materia', 'materia.id=espacio_curricular.materia_id', 'left', array('materia.descripcion as materia', 'materia.grupo_id')),
			),
			'nivel_id' => $nivel->id,
			'sort_by' => 'id'
		));

		$array_cursos = array();
		if (!empty($cursos)) {
			foreach ($cursos as $curso) {
				if (!isset($array_cursos[$curso->id])) {
					$array_cursos[$curso->id] = $curso;
					$array_cursos[$curso->id]->materias = array();
				}
				if (!empty($curso->materia)) {
					$array_cursos[$curso->id]->materias[] = $curso;
				}
			}
		}
		$data['cursos'] = $array_cursos;
		$data['txt_btn'] = NULL;
		$data['class'] = array('ver' => 'active btn-app-zetta-active', 'editar' => '');

		$data['title'] = TITLE . ' - Ver espacios extracurriculares';
		$this->load_template('espacio_extracurricular/espacio_extracurricular_abm', $data);
	}

	public function modal_agregar($id, $curso_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || $curso_id == NULL || !ctype_digit($id) || !ctype_digit($curso_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$nivel = $this->nivel_model->get(array('id' => $id));
		$this->load->model('curso_model');
		$curso = $this->curso_model->get(array('id' => $curso_id));

		if (empty($nivel) || empty($curso)) {
			$this->modal_error('No se encontró el registro de nivel/curso', 'Registro no encontrado');
			return;
		}

		$this->load->model('materia_model');
		$this->array_materia_control = $array_materia = $this->get_array('materia', 'descripcion', 'id', array(
			'sort_by' => 'descripcion'
			), array('' => '-- Seleccionar materia --'));
		$this->array_cuatrimestre_control = $this->espacio_curricular_model->fields['cuatrimestre']['array'];
		$this->set_model_validation_rules($this->espacio_curricular_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->espacio_curricular_model->create(array(
					'carga_horaria' => $this->input->post('carga_horaria'),
					'materia_id' => $this->input->post('materia'),
					'curso_id' => $curso->id,
					'cuatrimestre' => $this->input->post('cuatrimestre'),
					'resolucion_alta' => $this->input->post('resolucion_alta')
					), FALSE);
				$espacio_curricular_id = $this->espacio_curricular_model->get_row_id();
				$materias_grupo = $this->materia_model->get(array('grupo_id' => $this->input->post('materia')));
				if (!empty($materias_grupo)) {
					foreach ($materias_grupo as $materia) {
						$trans_ok &= $this->espacio_curricular_model->create(array(
							'carga_horaria' => $this->input->post('carga_horaria'),
							'grupo_id' => $espacio_curricular_id,
							'materia_id' => $materia->id,
							'curso_id' => $curso->id,
							'cuatrimestre' => $this->input->post('cuatrimestre'),
							'resolucion_alta' => $this->input->post('resolucion_alta')
							), FALSE);
					}
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->espacio_curricular_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->espacio_curricular_model->get_error());
				}
				redirect("espacio_extracurricular/editar/$nivel->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("espacio_extracurricular/editar/$nivel->id", 'refresh');
			}
		}
		$this->espacio_curricular_model->fields['carrera']['label'] = 'Nivel';
		$this->espacio_curricular_model->fields['carrera']['value'] = $nivel->descripcion;
		$this->espacio_curricular_model->fields['curso']['value'] = $curso->descripcion;
		$this->espacio_curricular_model->fields['materia']['array'] = $array_materia;

		$data['fields'] = $this->build_fields($this->espacio_curricular_model->fields);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar espacio curricular';
		$this->load->view('espacio_curricular/espacio_curricular_modal_abm', $data);
	}

	public function modal_editar($id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('espacio_curricular_model');
		$espacio_curricular = $this->espacio_curricular_model->get_one($id);

		if (empty($espacio_curricular)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}

		$nivel = $this->nivel_model->get(array('id' => $espacio_curricular->nivel_id));
		if (empty($nivel)) {
			$this->modal_error('No se encontró el registro de nivel', 'Registro no encontrado');
			return;
		}

		unset($this->espacio_curricular_model->fields['materia']['input_type']);
		$this->espacio_curricular_model->fields['materia']['readonly'] = TRUE;
		$this->array_cuatrimestre_control = $this->espacio_curricular_model->fields['cuatrimestre']['array'];

		$this->set_model_validation_rules($this->espacio_curricular_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->espacio_curricular_model->update(array(
					'id' => $this->input->post('id'),
					'carga_horaria' => $this->input->post('carga_horaria'),
					'cuatrimestre' => $this->input->post('cuatrimestre'),
					'resolucion_alta' => $this->input->post('resolucion_alta')
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->espacio_curricular_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->espacio_curricular_model->get_error());
				}
				redirect("espacio_extracurricular/editar/$espacio_curricular->nivel_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("espacio_extracurricular/editar/$espacio_curricular->nivel_id", 'refresh');
			}
		}

		$this->espacio_curricular_model->fields['carrera']['label'] = 'Nivel';
		$this->espacio_curricular_model->fields['carrera']['value'] = $nivel->descripcion;

		$data['espacio_curricular'] = $espacio_curricular;
		$data['fields'] = $this->build_fields($this->espacio_curricular_model->fields, $espacio_curricular);
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar espacio curricular';
		$this->load->view('espacio_curricular/espacio_curricular_modal_abm', $data);
	}

	public function modal_eliminar($id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('espacio_curricular_model');
		$espacio_curricular = $this->espacio_curricular_model->get_one($id);
		if (empty($espacio_curricular)) {
			$this->modal_error('No se encontró el registro a agregar', 'Registro no encontrado');
			return;
		}

		$nivel = $this->nivel_model->get(array('id' => $espacio_curricular->nivel_id));
		if (empty($nivel)) {
			$this->modal_error('No se encontró el registro de nivel', 'Registro no encontrado');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$espacios_curriculares_grupo = $this->espacio_curricular_model->get(array('grupo_id' => $espacio_curricular->id));
			if (!empty($espacios_curriculares_grupo)) {
				foreach ($espacios_curriculares_grupo as $espacio_curricular_grupo) {
					$trans_ok &= $this->espacio_curricular_model->delete(array('id' => $espacio_curricular_grupo->id), FALSE);
				}
			}
			$trans_ok &= $this->espacio_curricular_model->delete(array('id' => $espacio_curricular->id), FALSE);

			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->espacio_curricular_model->get_msg());
			} else {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', $this->espacio_curricular_model->get_error());
			}
			redirect("espacio_extracurricular/editar/$espacio_curricular->nivel_id", 'refresh');

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->espacio_curricular_model->get_msg());
			} else {
				$this->session->set_flashdata('error', $this->espacio_curricular_model->get_error());
			}
			redirect("espacio_extracurricular/editar/$espacio_curricular->nivel_id", 'refresh');
		}

		$this->espacio_curricular_model->fields['carrera']['label'] = 'Nivel';
		$this->espacio_curricular_model->fields['carrera']['value'] = $nivel->descripcion;

		$data['espacio_curricular'] = $espacio_curricular;
		$data['fields'] = $this->build_fields($this->espacio_curricular_model->fields, $espacio_curricular, TRUE);
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar espacio curricular';
		$this->load->view('espacio_curricular/espacio_curricular_modal_abm', $data);
	}
}
/* End of file Espacio_extracurricular.php */
/* Location: ./application/controllers/Espacio_extracurricular.php */