<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Espacio_curricular extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('carrera_model');
		$this->load->model('curso_model');
		$this->load->model('espacio_curricular_model');
		$this->load->model('materia_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA);
	}

	public function modal_agregar($id, $curso_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || $curso_id == NULL || !ctype_digit($id) || !ctype_digit($curso_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$carrera = $this->carrera_model->get(array('id' => $id));
		$curso = $this->curso_model->get(array('id' => $curso_id));

		if (empty($carrera) || empty($curso)) {
			$this->modal_error('No se encontró el registro de carrera/curso', 'Registro no encontrado');
			return;
		}
		if ($this->rol->codigo === ROL_LINEA || $this->rol->codigo === ROL_GRUPO_ESCUELA) {
			$this->load->model('nivel_model');
			$nivel = $this->nivel_model->get_one($carrera->nivel_id);
			if ($nivel->linea_id !== $this->rol->entidad_id) {
				$this->modal_error('No tiene permisos para eliminar carreras de otra Linea', 'Acción no autorizada');
			}
		}

		$this->array_materia_control = $array_materia = $this->get_array('materia', 'descripcion', 'id', array(
			'sort_by' => 'descripcion'
			), array('-1' => '-- Seleccionar materia --'));
		$this->array_cuatrimestre_control = $this->espacio_curricular_model->fields['cuatrimestre']['array'];

		$this->set_model_validation_rules($this->espacio_curricular_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->espacio_curricular_model->create(array(
					'carrera_id' => $carrera->id,
					'carga_horaria' => $this->input->post('carga_horaria'),
					'materia_id' => $this->input->post('materia'),
					'curso_id' => $curso->id,
					'cuatrimestre' => $this->input->post('cuatrimestre'),
					'codigo_junta' => $this->input->post('codigo_junta'),
					'resolucion_alta' => $this->input->post('resolucion_alta')
					), FALSE);
				$espacio_curricular_id = $this->espacio_curricular_model->get_row_id();
				$materias_grupo = $this->materia_model->get(array('grupo_id' => $this->input->post('materia')));
				if (!empty($materias_grupo)) {
					foreach ($materias_grupo as $materia) {
						$trans_ok &= $this->espacio_curricular_model->create(array(
							'carrera_id' => $carrera->id,
							'grupo_id' => $espacio_curricular_id,
							'materia_id' => $materia->id,
							'curso_id' => $curso->id,
							'cuatrimestre' => $this->input->post('cuatrimestre'),
							'codigo_junta' => $this->input->post('codigo_junta'),
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
				redirect("carrera/editar/$carrera->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("carrera/editar/$carrera->id", 'refresh');
			}
		}
		$this->espacio_curricular_model->fields['carrera']['value'] = $carrera->descripcion;
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
					'codigo_junta' => $this->input->post('codigo_junta'),
					'resolucion_alta' => $this->input->post('resolucion_alta')
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->espacio_curricular_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->espacio_curricular_model->get_error());
				}
				redirect("carrera/editar/$espacio_curricular->carrera_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("carrera/editar/$espacio_curricular->carrera_id", 'refresh');
			}
		}
		$data['cargos'] = $this->espacio_curricular_model->get_cargos($espacio_curricular->id);
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
		$espacio_curricular->cuatrimestre = $this->espacio_curricular_model->fields['cuatrimestre']['array'][$espacio_curricular->cuatrimestre];


		if (empty($espacio_curricular)) {
			$this->modal_error('No se encontró el registro a agregar', 'Registro no encontrado');
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
			redirect("carrera/editar/$espacio_curricular->carrera_id", 'refresh');

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->espacio_curricular_model->get_msg());
			} else {
				$this->session->set_flashdata('error', $this->espacio_curricular_model->get_error());
			}
			redirect("carrera/editar/$espacio_curricular->carrera_id", 'refresh');
		}
		$data['cargos'] = $this->espacio_curricular_model->get_cargos($espacio_curricular->id);
		$data['espacio_curricular'] = $espacio_curricular;
		$data['fields'] = $this->build_fields($this->espacio_curricular_model->fields, $espacio_curricular, TRUE);
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar espacio curricular';
		$this->load->view('espacio_curricular/espacio_curricular_modal_abm', $data);
	}

	public function cargos($espacio_curricular_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $espacio_curricular_id == NULL || !ctype_digit($espacio_curricular_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$espacio_curricular = $this->espacio_curricular_model->get_one($espacio_curricular_id);
		if (empty($espacio_curricular)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Escuela', 'data' => 'escuela', 'width' => 20, 'class' => 'text-sm'),
				array('label' => 'Condición', 'data' => 'condicion_cargo', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Curso', 'data' => 'curso', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'División', 'data' => 'division', 'class' => 'dt-body-right', 'width' => 10),
				array('label' => 'Tur', 'data' => 'turno', 'width' => 10),
				array('label' => 'Régimen/Materia', 'data' => 'regimen_materia', 'width' => 20, 'class' => 'text-sm'),
				array('label' => 'Hs.', 'data' => 'carga_horaria', 'class' => 'dt-body-right', 'width' => 5),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(0, 'asc')),
			'table_id' => 'cargo_table',
			'source_url' => "espacio_curricular/cargos_data/$espacio_curricular->id",
			'reuse_var' => TRUE,
			'initComplete' => "complete_cargo_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['espacio_curricular'] = $espacio_curricular;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Cargos';
		$this->load_template('espacio_curricular/espacio_curricular_ver_cargos', $data);
	}

	public function cargos_data($espacio_curricular_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $espacio_curricular_id == NULL || !ctype_digit($espacio_curricular_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->helper('datatables_functions_helper');
		$this->datatables->select("cargo.id ,cargo.escuela_id, CONCAT(e.numero, CASE WHEN e.anexo=0 THEN ' - ' ELSE CONCAT('/',e.anexo,' - ') END, e.nombre) as escuela, cu.descripcion as curso, d.division as division, turno.descripcion as turno, CONCAT(regimen.codigo,'', regimen.descripcion,'', COALESCE(materia.descripcion, '')) as regimen_materia, condicion_cargo.descripcion as condicion_cargo, cargo.carga_horaria ")
			->unset_column('id')
			->from('cargo')
			->join('turno', 'turno.id = cargo.turno_id', 'left')
			->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', 'left')
			->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
			->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
			->join('regimen', 'regimen.id = cargo.regimen_id', 'left')
			->join('escuela e', 'e.id = cargo.escuela_id', 'left')
			->join('division d', 'd.id = cargo.division_id', 'left')
			->join('curso cu', 'cu.id = d.curso_id', 'left')
			->where('cargo.espacio_curricular_id', $espacio_curricular_id)
			->add_column('edit', '<a class="btn btn-xs btn-default" target="_blank" href="cargo/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id');

		echo $this->datatables->generate();
	}
}
/* End of file Espacio_curricular.php */
/* Location: ./application/controllers/Espacio_curricular.php */