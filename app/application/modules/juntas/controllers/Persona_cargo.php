<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Persona_cargo extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->roles_permitidos = array(ROL_JUNTAS, ROL_MODULO, ROL_ADMIN);
		$this->nav_route = 'titulo/titulo_persona';
		$this->modulos_permitidos = array(ROL_MODULO_ADMINISTRADOR_DE_JUNTAS);
	}

	public function modal_eliminar($id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$model_persona_cargo = new stdClass();
		$model_persona_cargo->fields = array(
			'documento_bono' => array('label' => 'Documento'),
			'persona' => array('label' => 'Persona'),
			'cargo' => array('label' => 'cargo')
		);

		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('juntas/persona_cargo_model');
		$this->persona_cargo_model->set_database($DB1);


		$persona_cargo = $this->persona_cargo_model->get(array(
			'id' => $id,
			'join' => array(
				array('table' => 'persona', 'where' => 'persona.documento=persona_cargo.documento_bono', 'type' => 'left', 'columnas' => array('persona.documento', 'CONCAT(persona.apellido, ", ", persona.nombre) as persona', 'persona.id as persona_id'))
		)));
		if (empty($persona_cargo)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$this->load->model('juntas/cargo_entidad_model');
		$this->cargo_entidad_model->set_database($DB1);
		$cargos_entidad = $this->cargo_entidad_model->verificar_cargo($persona_cargo->cargo);
		if (empty($cargos_entidad)) {
			$this->modal_error('El cargo que intenta eliminar no está asociado a su rol', 'Acción no autorizada');
			return;
		}


		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->persona_cargo_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->persona_cargo_model->get_msg());
				redirect("juntas/escritorio/ver/$persona_cargo->persona_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->persona_cargo_model->get_error());
				redirect("juntas/escritorio/ver/$persona_cargo->persona_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($model_persona_cargo->fields, $persona_cargo, TRUE);
		$data['persona_cargo'] = $persona_cargo;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar cargo';
		$this->load->view('juntas/persona_cargo/persona_cargo_modal_abm', $data);
	}

	public function modal_agregar($persona_id) {
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

		$this->load->model('juntas/persona_cargo_model');
		$this->persona_cargo_model->set_database($DB1);

		$persona_cargos = $this->persona_cargo_model->get_cargos($persona->documento);
		$persona_cargos_array = explode(", ", $persona_cargos->cargos);
		$model_persona = new stdClass();
		$modelo = array(
			'documento_bono' => array('label' => 'Documento', 'readonly' => 'TRUE'),
			'persona' => array('label' => 'Persona', 'readonly' => 'TRUE'),
			'cargos' => array('label' => 'Cargos actuales', 'readonly' => 'TRUE'),
			'cargo' => array('label' => 'Cargo', 'input_type' => 'combo', 'class' => 'selectize', 'required' => TRUE));

		$model_persona->fields = $modelo;

		$this->load->model('juntas/cargo_entidad_model');
		$this->cargo_entidad_model->set_database($DB1);
		$this->array_cargo_control = $array_cargos = $this->cargo_entidad_model->get_cargo($persona->documento);

		if (empty($array_cargos)) {
			unset($model_persona->fields['cargo']['input_type']);
			$model_persona->fields['cargo']['readonly'] = TRUE;
			$model_persona->fields['cargo']['value'] = "No hay cargos a seleccionar";
			$data['txt_btn'] = '';
		} else {
			$model_persona->fields['cargo']['array'] = $array_cargos;
			$data['txt_btn'] = 'Agregar';
		}

		$model_persona->fields['cargos']['value'] = "$persona_cargos->cargos";
		$model_persona->fields['documento_bono']['value'] = $persona->documento;
		$model_persona->fields['persona']['value'] = " $persona->apellido, $persona->nombre";

		$this->load->model('juntas/persona_cargo_model');
		$this->persona_cargo_model->set_database($DB1);
		$this->set_model_validation_rules($model_persona);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				if (in_array($this->input->post('cargo'), $persona_cargos_array)) {
					$this->session->set_flashdata('error', "El cargo ya se encuentra asignado");
					redirect("juntas/escritorio/ver/$persona->id", 'refresh');
				}
				$trans_ok = TRUE;
				$trans_ok &= $this->persona_cargo_model->create(array(
					'documento_bono' => $persona->documento,
					'cargo' => $this->input->post('cargo'),
					'estado' => '1'
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->persona_cargo_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->persona_cargo_model->get_error());
				}
				redirect("juntas/escritorio/ver/$persona->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("juntas/escritorio/ver/$persona->id", 'refresh');
			}
		}

		$data['agregar'] = TRUE;
		$data['fields'] = $this->build_fields($model_persona->fields);
		$data['persona'] = $persona;
		$data['title'] = 'Asignar Cargo';
		$this->load->view('juntas/persona_cargo/persona_cargo_modal_abm', $data);
	}

	public function agregar($persona_id) {
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

		$this->load->model('juntas/persona_cargo_model');
		$this->persona_cargo_model->set_database($DB1);

		$persona_cargos = $this->persona_cargo_model->get_cargos($persona->documento);
		$persona_cargos_array = explode(", ", $persona_cargos->cargos);
		$model_persona = new stdClass();
		$modelo = array(
			'cargos' => array('label' => 'Cargos actuales', 'readonly' => 'TRUE'),
			'cargo' => array('label' => 'Cargo a agregar', 'input_type' => 'combo', 'class' => 'selectize', 'required' => TRUE));

		$model_persona->fields = $modelo;

		$this->load->model('juntas/cargo_entidad_model');
		$this->cargo_entidad_model->set_database($DB1);
		$this->array_cargo_control = $array_cargos = $this->cargo_entidad_model->get_cargo($persona->documento);

		if (empty($array_cargos)) {
			unset($model_persona->fields['cargo']['input_type']);
			$model_persona->fields['cargo']['readonly'] = TRUE;
			$model_persona->fields['cargo']['value'] = "No hay cargos a seleccionar";
			$data['txt_btn'] = '';
		} else {
			$model_persona->fields['cargo']['array'] = $array_cargos;
			$data['txt_btn'] = 'Agregar';
		}

		$model_persona->fields['cargos']['value'] = "$persona_cargos->cargos";

		$this->load->model('juntas/persona_cargo_model');
		$this->persona_cargo_model->set_database($DB1);
		$this->load->model('juntas/persona_matricula_model');
		$this->persona_matricula_model->set_database($DB1);
		$this->load->model('juntas/matricula_tipo_model');
		$this->matricula_tipo_model->set_database($DB1);
		$this->load->model('juntas/espacio_curricular_model');
		$this->espacio_curricular_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_antecedente_model');
		$this->persona_antecedente_model->set_database($DB1);
		$this->load->model('juntas/persona_cargo_antecedente_model');
		$this->persona_cargo_antecedente_model->set_database($DB1);
		$this->load->model('juntas/persona_idoneidad_model');
		$this->persona_idoneidad_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_antecedente_model');
		$persona_antecedente = $this->persona_antecedente_model->get(array('persona_id' => $persona->id,
			'join' => array(
				array('table' => 'modalidad', 'where' => 'modalidad.id=persona_antecedente.modalidad_id', 'type' => 'left', 'columnas' => array('descripcion as modalidad'))
		)));

		if (isset($_POST) && !empty($_POST)) {
			if (in_array($this->input->post('cargo'), $persona_cargos_array)) {
				$this->session->set_flashdata('error', "El cargo ya se encuentra asignado");
				redirect("juntas/escritorio/ver/$persona->id", 'refresh');
			}

			$this->db->trans_begin();
			$trans_ok = TRUE;
			$trans_ok &= $this->persona_cargo_model->create(array(
				'documento_bono' => $persona->documento,
				'cargo' => $this->input->post('cargo'),
				'estado' => '1'
			));
			$persona_cargo_id = $this->persona_cargo_model->get_row_id();
			$trans_ok &= $this->persona_cargo_antecedente_model->create(array(
				'persona_antecedente_id' => $this->input->post('opcion'),
				'persona_cargo_id' => $persona_cargo_id
			));
			$persona_cargo_antecedente_id = $this->persona_cargo_antecedente_model->get_row_id();

			if ($this->input->post('cargo') === 'Preceptor') {
				$espacio_id = '2477';
			} else if ($this->input->post('cargo') === 'Bibliotecario') {
				$espacio_id = '2249';
			} else if ($this->input->post('cargo') === 'Secretario') {
				$espacio_id = '1201';
			} else {
				$espacio_id = '0';
			}

			$trans_ok &= $this->persona_idoneidad_model->create(array(
				'persona_cargo_antecedente_id' => $persona_cargo_antecedente_id,
				'espacio_id' => $espacio_id,
				'certifica_cct' => '0',
			));

			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Cargo asignado correctamente.');
			} else {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', 'Error al asignar cargo.');
			}
			redirect("juntas/escritorio/ver/$persona->id", 'refresh');
		}

		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['agregar'] = TRUE;
		$data['fields'] = $this->build_fields($model_persona->fields);
		$data['persona_antecedente'] = $persona_antecedente;
		$data['persona'] = $persona;
		$data['title'] = 'Asignar Cargo';
		$this->load_template('juntas/persona_cargo/persona_cargo_abm', $data);
	}

	public function agregar_cct($persona_id) {
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

		$this->load->model('juntas/persona_cargo_model');
		$this->persona_cargo_model->set_database($DB1);

		$persona_cargos = $this->persona_cargo_model->get_cargos($persona->documento);
		$persona_cargos_array = explode(", ", $persona_cargos->cargos);
		$model_persona = new stdClass();
		$modelo = array(
			'cargos' => array('label' => 'Cargos actuales', 'readonly' => 'TRUE'),
			'cargo' => array('label' => 'Cargo a agregar', 'input_type' => 'combo', 'class' => 'selectize', 'required' => TRUE));

		$model_persona->fields = $modelo;

		$this->load->model('juntas/cargo_entidad_model');
		$this->cargo_entidad_model->set_database($DB1);
		$this->array_cargo_control = $array_cargos = $this->cargo_entidad_model->get_cargo($persona->documento);

		if (empty($array_cargos)) {
			unset($model_persona->fields['cargo']['input_type']);
			$model_persona->fields['cargo']['readonly'] = TRUE;
			$model_persona->fields['cargo']['value'] = "No hay cargos a seleccionar";
			$data['txt_btn'] = '';
		} else {
			$model_persona->fields['cargo']['array'] = $array_cargos;
			$data['txt_btn'] = 'Agregar';
		}

		$model_persona->fields['cargos']['value'] = "$persona_cargos->cargos";

		$this->load->model('juntas/persona_cargo_model');
		$this->persona_cargo_model->set_database($DB1);
		$this->load->model('juntas/persona_matricula_model');
		$this->persona_matricula_model->set_database($DB1);
		$this->load->model('juntas/matricula_tipo_model');
		$this->matricula_tipo_model->set_database($DB1);
		$this->load->model('juntas/espacio_curricular_model');
		$this->espacio_curricular_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_antecedente_model');
		$this->persona_antecedente_model->set_database($DB1);
		$this->load->model('juntas/persona_cargo_antecedente_model');
		$this->persona_cargo_antecedente_model->set_database($DB1);
		$this->load->model('juntas/persona_idoneidad_model');
		$this->persona_idoneidad_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_antecedente_model');
		$persona_antecedente = $this->persona_antecedente_model->get(array('persona_id' => $persona->id,
			'join' => array(
				array('table' => 'modalidad', 'where' => 'modalidad.id=persona_antecedente.modalidad_id', 'type' => 'left', 'columnas' => array('descripcion as modalidad'))
		)));

		if (isset($_POST) && !empty($_POST)) {
			if (in_array($this->input->post('cargo'), $persona_cargos_array)) {
				$this->session->set_flashdata('error', "El cargo ya se encuentra asignado");
				redirect("juntas/escritorio/ver/$persona->id", 'refresh');
			}

			$this->db->trans_begin();
			$trans_ok = TRUE;
			$trans_ok &= $this->persona_cargo_model->create(array(
				'documento_bono' => $persona->documento,
				'cargo' => $this->input->post('cargo'),
				'estado' => '1'
			));
			$persona_cargo_id = $this->persona_cargo_model->get_row_id();
			$trans_ok &= $this->persona_cargo_antecedente_model->create(array(
				'persona_antecedente_id' => (!empty($this->input->post('opcion'))) ? $this->input->post('opcion') : 'NULL',
				'persona_cargo_id' => $persona_cargo_id
			));
			$persona_cargo_antecedente_id = $this->persona_cargo_antecedente_model->get_row_id();

			if ($this->input->post('cargo') === 'Preceptor') {
				$espacio_id = '2477';
			} else if ($this->input->post('cargo') === 'Bibliotecario') {
				$espacio_id = '2249';
			} else if ($this->input->post('cargo') === 'Secretario') {
				$espacio_id = '1201';
			} else {
				$espacio_id = '0';
			}

			$trans_ok &= $this->persona_idoneidad_model->create(array(
				'persona_cargo_antecedente_id' => $persona_cargo_antecedente_id,
				'espacio_id' => $espacio_id,
				'certifica_cct' => '0',
			));

			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Cargo asignado correctamente.');
			} else {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', 'Error al asignar cargo.');
			}
			redirect("juntas/escritorio/ver/$persona->id", 'refresh');
		}

		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['agregar'] = TRUE;
		$data['fields'] = $this->build_fields($model_persona->fields);
		$data['persona_antecedente'] = $persona_antecedente;
		$data['persona'] = $persona;
		$data['title'] = 'Asignar Cargo';
		$this->load_template('juntas/persona_cargo/persona_cargo_cct', $data);
	}

	public function modal_activar($id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$model_persona_cargo = new stdClass();
		$model_persona_cargo->fields = array(
			'documento_bono' => array('label' => 'Documento', 'readonly' => TRUE),
			'persona' => array('label' => 'Persona', 'readonly' => TRUE),
			'cargo' => array('label' => 'cargo', 'readonly' => TRUE),
			'estado' => array('label' => 'Estado', 'input_type' => 'combo', 'id_name' => 'estado', 'required' => TRUE, 'array' => array('1' => 'Activo', '0' => 'No activo')),
		);

		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('juntas/persona_cargo_model');
		$this->persona_cargo_model->set_database($DB1);


		$persona_cargo = $this->persona_cargo_model->get(array(
			'id' => $id,
			'join' => array(
				array('table' => 'persona', 'where' => 'persona.documento=persona_cargo.documento_bono', 'type' => 'left', 'columnas' => array('persona.documento', 'CONCAT(persona.apellido, ", ", persona.nombre) as persona', 'persona.id as persona_id'))
		)));
		if (empty($persona_cargo)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$this->load->model('juntas/cargo_entidad_model');
		$this->cargo_entidad_model->set_database($DB1);
		$cargos_entidad = $this->cargo_entidad_model->verificar_cargo($persona_cargo->cargo);
		if (empty($cargos_entidad)) {
			$this->modal_error('El cargo que intenta editar no está asociado a su rol', 'Acción no autorizada');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($persona_cargo->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->persona_cargo_model->update(array(
				'id' => $this->input->post('id'),
				'estado' => $this->input->post('estado')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->persona_cargo_model->get_msg());
				redirect("juntas/escritorio/ver/$persona_cargo->persona_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->persona_cargo_model->get_error());
				redirect("juntas/escritorio/ver/$persona_cargo->persona_id", 'refresh');
			}
		}
		$this->array_estado_control = $model_persona_cargo->fields['estado']['array'];
		$data['fields'] = $this->build_fields($model_persona_cargo->fields, $persona_cargo);
		$data['persona_cargo'] = $persona_cargo;
		$data['txt_btn'] = 'Activar';
		$data['title'] = 'Activar cargo';
		$this->load->view('juntas/persona_cargo/persona_cargo_modal_abm', $data);
	}

	public function modal_asignar_espacios($id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$model_persona_cargo = new stdClass();
		$model_persona_cargo->fields = array(
			'documento_bono' => array('label' => 'Documento', 'readonly' => TRUE),
			'persona' => array('label' => 'Persona', 'readonly' => TRUE),
			'cargo' => array('label' => 'cargo', 'readonly' => TRUE),
			'estado' => array('label' => 'Estado', 'input_type' => 'combo', 'id_name' => 'estado', 'required' => TRUE, 'array' => array('1' => 'Activo', '0' => 'No activo')),
			'espacio' => array('label' => 'Espacios curriculares', 'input_type' => 'combo', 'id_name' => 'espacio', 'required' => TRUE, 'class' => 'no-selectize', 'type' => 'multiple'),
		);


		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('juntas/matricula_tipo_model');
		$this->matricula_tipo_model->set_database($DB1);

		$this->array_matricula_tipo_control = $array_matricula_tipo = $this->get_array('matricula_tipo', 'descripcion', 'id', null, array('' => '-- Seleccionar tipo de matrícula --'));
		$this->array_espacio_control = $array_espacio = array('' => '');

		$this->load->model('juntas/persona_cargo_model');
		$this->persona_cargo_model->set_database($DB1);

		$this->load->model('juntas/persona_idoneidad_model');
		$this->persona_idoneidad_model->set_database($DB1);

		$this->load->model('juntas/persona_matricula_model');
		$this->persona_matricula_model->set_database($DB1);

		$this->load->model('juntas/espacio_curricular_model');
		$this->espacio_curricular_model->set_database($DB1);

		$persona_cargo = $this->persona_cargo_model->get(array(
			'id' => $id,
			'join' => array(
				array('table' => 'persona', 'where' => 'persona.documento=persona_cargo.documento_bono', 'type' => 'left', 'columnas' => array('persona.documento', 'CONCAT(persona.apellido, ", ", persona.nombre) as persona', 'persona.id as persona_id')),
				array('table' => 'persona_cargo_antecedente', 'where' => 'persona_cargo_antecedente.persona_cargo_id=persona_cargo.id', 'type' => 'left', 'columnas' => array('persona_cargo_antecedente.id as persona_cargo_antecedente_id'))
		)));

		$persona_cargo->espacio = '';
		if (empty($persona_cargo)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$this->load->model('juntas/cargo_entidad_model');
		$this->cargo_entidad_model->set_database($DB1);
		$cargos_entidad = $this->cargo_entidad_model->verificar_cargo($persona_cargo->cargo);
		if (empty($cargos_entidad)) {
			$this->modal_error('El cargo que intenta editar no está asociado a su rol', 'Acción no autorizada');
			return;
		}
		unset($model_persona_cargo->fields['espacio']['input_type']);
		$this->set_model_validation_rules($model_persona_cargo);
		$model_persona_cargo->fields['espacio']['input_type'] = 'combo';
		if (isset($_POST) && !empty($_POST)) {
			if ($persona_cargo->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$espacios_ids = $this->input->post('espacio');
			$count = count($espacios_ids);
			for ($i = 0; $i < $count; $i++) {
				if ($trans_ok) {
					$verificar_espacio = $this->espacio_curricular_model->verificar_espacio($espacios_ids[$i], $persona_cargo->persona_cargo_antecedente_id);
					if (!$verificar_espacio) {
						$trans_ok &= $this->persona_idoneidad_model->create(array(
							'persona_cargo_antecedente_id' => $persona_cargo->persona_cargo_antecedente_id,
							'espacio_id' => $espacios_ids[$i],
							'certifica_cct' => '0',
						));
						$persona_idoneidad_id = $this->persona_idoneidad_model->get_row_id();

						if ($espacios_ids[$i] == '101') { //gasista
							$trans_ok &= $this->persona_matricula_model->create(array(
								'persona_idoneidad_id' => (!empty($persona_cargo->persona_idoneidad_id) ? $persona_cargo->persona_idoneidad_id : $persona_idoneidad_id),
								'matricula_tipo_id' => $this->input->post('matricula_tipo'),
								'matricula_nro' => $this->input->post('matricula_numero'),
								'matricula_vence' => $this->get_date_sql('matricula_vence'),
							));
						}
					} else {
						$this->session->set_flashdata('error', 'Algunos espacios ya fueron asignados anteriormente.');
						redirect("juntas/escritorio/ver/$persona_cargo->persona_id", 'refresh');
					}
				}
			}

			$trans_ok &= $this->persona_cargo_model->update(array(
				'id' => $this->input->post('id'),
				'estado' => $this->input->post('estado')));
			if ($this->db->trans_status() && $trans_ok) {
				$this->session->set_flashdata('message', 'Espacios asignados con éxito');
				redirect("juntas/escritorio/ver/$persona_cargo->persona_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->persona_idoneidad_model->get_error());
				redirect("juntas/escritorio/ver/$persona_cargo->persona_id", 'refresh');
			}
		}

		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('juntas/requiere_matricula_model');
		$this->requiere_matricula_model->set_database($DB1);
		$data['requiere_matricula'] = $this->get_array('requiere_matricula', 'espacio_id', 'id', null);
		$this->persona_matricula_model->fields['matricula_tipo']['array'] = $array_matricula_tipo;
		$model_persona_cargo->fields['espacio']['array'] = $array_espacio;
		$data['fields_matricula'] = $this->build_fields($this->persona_matricula_model->fields);
		$data['fields'] = $this->build_fields($model_persona_cargo->fields, $persona_cargo);
		$data['persona_cargo'] = $persona_cargo;
		$data['js'][] = 'plugins/querybuilder/js/query-builder.standalone.min.js';
		$data['js'][] = 'plugins/querybuilder/i18n/query-builder.es.js';
		$data['js'][] = 'plugins/bootstrap-select-1.12.2/js/bootstrap-select.min.js';
		$data['js'][] = 'plugins/bootstrap-select-1.12.2/js/i18n/defaults-es_ES.min.js';
		$data['css'][] = 'plugins/querybuilder/css/query-builder.default.min.css';
		$data['css'][] = 'plugins/bootstrap-select-1.12.2/css/bootstrap-select.min.css';
		$data['error'] = $this->session->flashdata('error');
		$data['txt_btn'] = 'Asignar';
		$data['title'] = 'Asignar espacios';
		$this->load->view('juntas/persona_cargo/asignar_espacios', $data);
	}

	public function ajax_buscar_espacio_curricular() {
		$this->load->model('juntas/espacio_curricular_model');
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->espacio_curricular_model->set_database($DB1);
		$numero_area = $this->input->post('numero_area');
		$espacio = $this->input->post('espacio');
		echo json_encode($this->espacio_curricular_model->get_espacios_cct($numero_area, $espacio));
	}

	public function ajax_recuperar_espacio_cargo() {
		$this->load->model('juntas/espacio_curricular_model');
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->espacio_curricular_model->set_database($DB1);
		$numero_area = $this->input->post('numero_area');
		$espacio = $this->input->post('espacio');
		echo json_encode($this->espacio_curricular_model->get_espacios_cct($numero_area, $espacio));
	}

	public function modal_eliminar_espacio($persona_idoneidad_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $persona_idoneidad_id == NULL || !ctype_digit($persona_idoneidad_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$model_persona_cargo = new stdClass();
		$model_persona_cargo->fields = array(
			'documento_bono' => array('label' => 'Documento', 'readonly' => 'TRUE'),
			'persona' => array('label' => 'Persona', 'readonly' => 'TRUE'),
			'cargo' => array('label' => 'Cargo', 'readonly' => 'TRUE'),
			'espacio' => array('label' => 'Espacio', 'readonly' => 'TRUE'),
		);

		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('juntas/persona_idoneidad_model');
		$this->persona_idoneidad_model->set_database($DB1);
		$this->load->model('juntas/persona_matricula_model');
		$this->persona_matricula_model->set_database($DB1);


		$persona_idoneidad = $this->persona_idoneidad_model->get(array(
			'id' => $persona_idoneidad_id,
			'join' => array(
				array('table' => 'persona_cargo_antecedente', 'where' => 'persona_cargo_antecedente.id=persona_idoneidad.persona_cargo_antecedente_id', 'type' => 'left', 'columnas' => array('')),
				array('table' => 'persona_cargo', 'where' => 'persona_cargo.id=persona_cargo_antecedente.persona_cargo_id', 'type' => 'left', 'columnas' => array('persona_cargo.cargo')),
				array('table' => 'persona', 'where' => 'persona.documento=persona_cargo.documento_bono', 'type' => 'left', 'columnas' => array('persona.documento', 'CONCAT(persona.apellido, ", ", persona.nombre) as persona', 'persona.id as persona_id')),
				array('table' => 'espacio_curricular', 'where' => 'persona_idoneidad.espacio_id=espacio_curricular.id', 'type' => 'left', 'columnas' => array('espacio_curricular.descripcion as espacio')),
		)));
		if (empty($persona_idoneidad)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$persona_matricula = $this->persona_matricula_model->get(array(
			'persona_idoneidad_id' => $persona_idoneidad_id,
			'join' => array(
				array('table' => 'matricula_tipo', 'where' => 'persona_matricula.matricula_tipo_id=matricula_tipo.id', 'type' => 'left', 'columnas' => array('matricula_tipo.descripcion as matricula_tipo')),
		)));
		if (isset($persona_matricula) && !empty($persona_matricula)) {
			$persona_matricula[0]->matricula_numero = $persona_matricula[0]->matricula_nro;
			$data['fields_pmatricula'] = $this->build_fields($this->persona_matricula_model->fields, $persona_matricula[0], TRUE);
		}
		$this->load->model('juntas/cargo_entidad_model');
		$this->cargo_entidad_model->set_database($DB1);
		$cargos_entidad = $this->cargo_entidad_model->verificar_cargo($persona_idoneidad->cargo);
		if (empty($cargos_entidad)) {
			$this->modal_error('El cargo que intenta eliminar no está asociado a su rol', 'Acción no autorizada');
			return;
		}


		if (isset($_POST) && !empty($_POST)) {
			if ($persona_idoneidad_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			$trans_ok = TRUE;

			if (!empty($persona_matricula)) {
				$trans_ok &= $this->persona_matricula_model->delete(array('id' => $persona_matricula[0]->id));
			}

			$trans_ok &= $this->persona_idoneidad_model->delete(array('id' => $this->input->post('id')));

			if ($trans_ok) {
				$this->session->set_flashdata('message', 'Espación eliminado correctamente');
				redirect("juntas/escritorio/ver/$persona_idoneidad->persona_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->persona_idoneidad_model->get_error());
				redirect("juntas/escritorio/ver/$persona_idoneidad->persona_id", 'refresh');
			}
		}

		$model_persona_cargo->fields['documento_bono']['value'] = "$persona_idoneidad->documento";
		$model_persona_cargo->fields['persona']['value'] = "$persona_idoneidad->persona";
		$model_persona_cargo->fields['cargo']['value'] = "$persona_idoneidad->cargo";
		$model_persona_cargo->fields['espacio']['value'] = "$persona_idoneidad->espacio";
		$data['fields'] = $this->build_fields($model_persona_cargo->fields);
		$data['persona_idoneidad'] = $persona_idoneidad;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar espacio';
		$this->load->view('juntas/persona_cargo/eliminar_espacio_modal', $data);
	}
}
/* End of file Titulo_persona.php */
	/* Location: ./application/modules/titulos/controllers/Titulo_persona.php */	