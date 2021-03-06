<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Persona_antecedente extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->roles_permitidos = array(ROL_JUNTAS, ROL_MODULO, ROL_ADMIN);
		$this->nav_route = 'titulo/titulo_persona';
		$this->modulos_permitidos = array(ROL_MODULO_ADMINISTRADOR_DE_JUNTAS);
	}

	function modal_agregar_antecedente($persona_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $persona_id == NULL || !ctype_digit($persona_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('bono_secundario/persona_bono_model');
		$this->persona_bono_model->set_database($DB1);
		if (empty($persona_id)) {
			redirect('juntas/escritorio/listar_personas', 'refresh');
		} else {
			$persona = $this->persona_bono_model->get_one($persona_id);
		}

		if (empty($persona)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
			redirect('juntas/escritorio/listar_personas', 'refresh');
		}

		$this->load->model('juntas/modalidad_model');
		$this->load->model('juntas/cursos_model');
		$this->load->model('bono_secundario/persona_antecedente_model');
		$this->modalidad_model->set_database($DB1);
		$this->cursos_model->set_database($DB1);
		$this->persona_antecedente_model->set_database($DB1);
		$this->array_modalidad_control = $array_modalidad = $this->get_array('modalidad', 'descripcion', 'id', null, array('' => '-- Seleccionar modalidad --'));

		$this->array_tipo_duracion_control = $this->persona_antecedente_model->fields['tipo_duracion']['array'];
		$this->array_tipo_duracion_control = $this->persona_antecedente_model->fields['tipo_duracion']['array'];
		$this->persona_antecedente_model->fields['persona']['value'] = "$persona->cuil $persona->apellido $persona->nombre";
		$this->set_model_validation_rules($this->persona_antecedente_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;

				$trans_ok &= $this->persona_antecedente_model->create(array(
					'persona_id' => $persona->id,
					'modalidad_id' => $this->input->post('modalidad'),
					'antecedente' => $this->input->post('antecedente'),
					'institucion' => $this->input->post('institucion'),
					'numero_resolucion' => $this->input->post('numero_resolucion'),
					'fecha_editado' => date("Y-m-d"),
					'fecha_emision' => $this->get_date_sql('fecha_emision'),
					'duracion' => $this->input->post('duracion'),
					'tipo_duracion' => $this->input->post('tipo_duracion'),
					'aprobado' => $this->input->post('aprobado'),
					'estado' => '1'
					), FALSE);

				$trans_ok &= $this->cursos_model->create(array(
					'antecedente' => $this->input->post('antecedente'),
					'institucion' => $this->input->post('institucion'),
					'numero_resolucion' => $this->input->post('numero_resolucion'),
					'duracion' => $this->input->post('duracion'),
					'tipo_duracion' => $this->input->post('tipo_duracion')
				));

				if ($trans_ok && $this->db->trans_status()) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->persona_antecedente_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->persona_antecedente_model->get_error());
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect("juntas/persona_cargo/agregar/$persona->id", 'refresh');
		}

		$this->persona_antecedente_model->fields['modalidad']['array'] = $array_modalidad;
		$data['fields'] = $this->build_fields($this->persona_antecedente_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar antecedente';
		$this->load->view('juntas/persona_antecedente/antecedentes_modal_abm', $data);
	}

	function modal_editar_antecedente($id) {
		if (!in_groups($this->grupos_permitidos, $this->grupos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada.', 'Acción no autorizada');
		}
		$this->load->model('bono/persona_model');
		$persona_cuil = $this->persona_model->get(array('cuil' => $this->session->userdata('usuario')->cuil));

		if (empty($persona_cuil)) {
			redirect('bono/persona/agregar', 'refresh');
		} else {
			$persona = $persona_cuil[0];
		}
		$this->load->model('bono/persona_antecedente_model');


		$this->load->model('bono/modalidad_model');
		$this->array_modalidad_control = $array_modalidad = $this->get_array('modalidad', 'descripcion', 'id', null, array('' => '-- Seleccionar modalidad --'));
		$this->array_tipo_duracion_control = $this->persona_antecedente_model->fields['tipo_duracion']['array'];
		$persona_antecedente = $this->persona_antecedente_model->get_one($id);
		if ($persona->id !== $persona_antecedente->persona_id) {
			$this->session->set_flashdata('error', 'No tiene permisos para editar este antecedente');
			redirect("bono/inscripcion/antecedentes", 'refresh');
		}

		if (isset($persona_antecedente->curso_avalado_id) && !empty($persona_antecedente->curso_avalado_id)) {
			unset($this->persona_antecedente_model->fields['antecedente']['input_type']);
			unset($this->persona_antecedente_model->fields['antecedente']['required']);
			$this->persona_antecedente_model->fields['antecedente']['readonly'] = TRUE;
			unset($this->persona_antecedente_model->fields['institucion']['input_type']);
			unset($this->persona_antecedente_model->fields['institucion']['required']);
			$this->persona_antecedente_model->fields['institucion']['readonly'] = TRUE;
			unset($this->persona_antecedente_model->fields['numero_resolucion']['input_type']);
			unset($this->persona_antecedente_model->fields['numero_resolucion']['required']);
			$this->persona_antecedente_model->fields['numero_resolucion']['readonly'] = TRUE;
		}
		$this->load->model('bono/inscripcion_model');
		$inscripcion = $this->inscripcion_model->get(array('persona_id' => $persona->id));

		if (!empty($inscripcion[0]->fecha_recepcion) && $persona_antecedente->estado == '1') {
			$this->modal_error('No puede editar este antecedente.', 'Acción no autorizada');
			return;
		}

		$this->set_model_validation_rules($this->persona_antecedente_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->input->post('id') !== $id) {
				$this->session->set_flashdata('error', 'Error al intentar editar antecedente');
				redirect("bono/inscripcion/antecedentes", 'refresh');
			}
			if (empty($this->input->post('fecha_emision'))) {
				$this->session->set_flashdata('error', 'La fecha de emisión no puede ser nula');
				redirect("bono/inscripcion/antecedentes", 'refresh');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				if (empty($this->input->post('aprobado'))) {
					$aprobado = 'No';
				} else {
					$aprobado = 'Si';
				}
				$trans_ok &= $this->persona_antecedente_model->update(array(
					'id' => $id,
					'antecedente' => $this->input->post('antecedente'),
					'institucion' => $this->input->post('institucion'),
					'numero_resolucion' => $this->input->post('numero_resolucion'),
					'modalidad_id' => $this->input->post('modalidad'),
					'fecha_emision' => $this->get_date_sql('fecha_emision'),
					'duracion' => $this->input->post('duracion'),
					'fecha_editado' => date("Y-m-d"),
					'tipo_duracion' => $this->input->post('tipo_duracion'),
					'aprobado' => $aprobado
				));

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->persona_antecedente_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->persona_antecedente_model->get_error());
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect("bono/inscripcion/antecedentes", 'refresh');
		}
		$this->persona_antecedente_model->fields['modalidad']['array'] = $array_modalidad;
		$data['fields'] = $this->build_fields($this->persona_antecedente_model->fields, $persona_antecedente);
		$data['persona_antecedente'] = $persona_antecedente;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar antecedente';
		$this->load->view('bono/inscripcion/antecedentes_modal_abm', $data);
	}

	function modal_agregar_antecedente_avalado($persona_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $persona_id == NULL || !ctype_digit($persona_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('bono_secundario/persona_bono_model');
		$this->persona_bono_model->set_database($DB1);
		if (empty($persona_id)) {
			redirect('juntas/escritorio/listar_personas', 'refresh');
		} else {
			$persona = $this->persona_bono_model->get_one($persona_id);
		}

		if (empty($persona)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
			redirect('juntas/escritorio/listar_personas', 'refresh');
		}

		$model_busqueda = new stdClass();
		$model_busqueda->fields = array(
			'identificador' => array('label' => 'Identificador', 'maxlength' => '10'),
			'antecedente' => array('label' => 'Antecedente', 'maxlength' => '100', 'minlength' => '3'),
			'numero_resolucion' => array('label' => 'N° Resolución(solo número) ', 'maxlength' => '100'),
			'fecha_emision' => array('label' => 'Ingrese la Fecha de emisión del antecedente a agregar', 'type' => 'date', 'required' => TRUE),
		);

		$this->set_model_validation_rules($model_busqueda);
		if (isset($_POST) && !empty($_POST)) {
			if (empty($this->input->post('fecha_emision'))) {
				$this->session->set_flashdata('error', 'La fecha de emisión no puede ser nula');
				redirect("bono/inscripcion/antecedentes", 'refresh');
			}
			$this->load->model('juntas/persona_antecedente_avalado_model');
			$this->persona_antecedente_avalado_model->set_database($DB1);
			$this->load->model('bono_secundario/persona_antecedente_model');
			$this->persona_antecedente_model->set_database($DB1);

			$antecedente = $this->persona_antecedente_avalado_model->get(array('id' => $this->input->post('antecedente_id')));
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->persona_antecedente_model->create(array(
					'persona_id' => $persona->id,
					'curso_avalado_id' => $this->input->post('antecedente_id'),
					'antecedente' => $antecedente->antecedente,
					'institucion' => $antecedente->institucion,
					'numero_resolucion' => $antecedente->numero_resolucion,
					'fecha_editado' => date("Y-m-d"),
					'fecha_emision' => $this->get_date_sql('fecha_emision'),
					'duracion' => $antecedente->duracion,
					'tipo_duracion' => $antecedente->tipo_duracion,
					'aprobado' => 'Si',
					'estado' => '1'
					), FALSE);
				if ($trans_ok && $this->db->trans_status()) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->persona_antecedente_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->persona_antecedente_model->get_error());
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect("juntas/persona_cargo/agregar/$persona_id", 'refresh');
		}

		$this->persona_antecedente_avalado_model->fields['persona']['value'] = "$persona->cuil $persona->apellido $persona->nombre";
		$data['fields'] = $this->build_fields($model_busqueda->fields);
		$data['error'] = $this->session->flashdata('error');
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar antecedentes avalados';
		$this->load->view('juntas/persona_antecedente/antecedentes_avalados_modal_abm', $data);
	}
}