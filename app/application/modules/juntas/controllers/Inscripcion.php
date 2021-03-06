<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inscripcion extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->roles_permitidos = array(ROL_JUNTAS, ROL_MODULO, ROL_ADMIN);
		$this->nav_route = 'titulo/titulo_persona';
		$this->modulos_permitidos = array(ROL_MODULO_ADMINISTRADOR_DE_JUNTAS);
	}

	public function modal_abrir_inscripcion($persona_id) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $persona_id == NULL || !ctype_digit($persona_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if ($this->usuario == '37' || $this->usuario == '3460') {
			$DB1 = $this->load->database('bono_secundario', TRUE);
			$this->load->model('bono_secundario/persona_bono_model');
			$this->persona_bono_model->set_database($DB1);
			if (empty($persona_id)) {
				redirect('juntas/escritorio/listar_personas', 'refresh');
			} else {
				$persona = $this->persona_bono_model->get_one($persona_id);
			}

			if (empty($persona)) {
				$this->modal_error('No se encontró el registro a ver', 'Acción no autorizada');
				return;
			}

			$this->load->model('bono_secundario/inscripcion_model');
			$this->inscripcion_model->set_database($DB1);
			$inscripcion = $this->inscripcion_model->get(array(
				'persona_id' => $persona_id
			));
			if (isset($inscripcion) && !empty($inscripcion)) {
				$inscripcion = $inscripcion[0];
				$model_inscripcion = new stdClass();
				$modelo = array(
					'persona' => array('label' => 'Persona', 'readonly' => 'TRUE'),
					'fecha_cierre' => array('label' => 'Fecha cierre', 'readonly' => 'TRUE'));
				$model_inscripcion->fields = $modelo;
				$model_inscripcion->fields['fecha_cierre']['value'] = (new DateTime($inscripcion->fecha_cierre))->format('d/m/Y');
				if (!empty($persona->apellido) && !empty($persona->nombre)) {
					$model_inscripcion->fields['persona']['value'] = "$persona->cuil | $persona->apellido, $persona->nombre";
				} else {
					$model_inscripcion->fields['persona']['value'] = "$persona->cuil";
				}
			} else {
				$this->modal_error('Esta persona no cerró su inscripción todavía', 'Acción no autorizada');
				return;
			}

			if (isset($_POST) && !empty($_POST)) {
				if ($inscripcion->id !== $this->input->post('id')) {
					$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				}
				$DB1 = $this->load->database('bono_secundario', TRUE);
				$this->load->model('bono_secundario/escuela_model');
				$this->escuela_model->set_database($DB1);
				$DB1->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->inscripcion_model->delete(array('id' => $this->input->post('id')));

				$trans_ok &= $this->escuela_model->sumar_vacante($inscripcion->escuela_id);

				if ($DB1->trans_status() && $trans_ok) {
					$DB1->trans_commit();
					$this->session->set_flashdata('message', $this->inscripcion_model->get_msg());
					redirect("juntas/alertas/listar_inscriptos/", 'refresh');
				} else {
					$DB1->trans_rollback();
					$this->session->set_flashdata('error', $this->inscripcion_model->get_error());
					redirect("juntas/alertas/listar_inscriptos/", 'refresh');
				}
			}

			$data['agregar'] = TRUE;
			$data['fields'] = $this->build_fields($model_inscripcion->fields);
			$data['persona'] = $persona;
			$data['txt_btn'] = 'Abrir';
			$data['inscripcion'] = $inscripcion;
			$data['title'] = 'Abrir inscripción';
			$this->load->view('juntas/inscripcion/inscripcion_modal_abm', $data);
		} else {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
	}

	public function inscripcion_ver($inscripcion_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $inscripcion_id == NULL || !ctype_digit($inscripcion_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$DB1 = $this->load->database('bono_secundario', TRUE);

		$this->load->model('bono_secundario/inscripcion_model');
		$this->inscripcion_model->set_database($DB1);
		$inscripcion = $this->inscripcion_model->get_one($inscripcion_id);
		if (empty($inscripcion)) {
			show_error('No se encontró la inscripción a recibir', 500, 'Registro no encontrado');
		}
		$this->load->model('bono_secundario/escuela_model');
		$this->escuela_model->set_database($DB1);
		$escuela = $this->escuela_model->get_one($inscripcion->escuela_id);
		if (!empty($escuela) && ($escuela->id != $inscripcion->escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('bono_secundario/recepcion_model');
		$this->recepcion_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_bono_model');
		$this->persona_bono_model->set_database($DB1);
		$persona = $this->persona_bono_model->get_persona($inscripcion->persona_id);
		if (isset($persona) && !empty($persona)) {
			$this->load->model('persona_model');
			$persona_gem = $this->persona_model->get_persona_parcial('cuil', $persona->cuil);
			if (!empty($persona_gem)) {
				$persona_gem = $persona_gem;
				$data['persona_gem'] = $persona_gem;
				$data['fields_p_gem'] = $this->build_fields($this->persona_bono_model->fields, $persona_gem, TRUE);
				unset($persona->usuario_id);
				$array_persona = get_object_vars($persona);
				$array_persona_gem = get_object_vars($persona_gem);
				array_diff($array_persona, $array_persona_gem);
			}
		}

		$this->load->model('bono_secundario/persona_titulo_model');
		$this->persona_titulo_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_antiguedad_model');
		$this->persona_antiguedad_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_antecedente_model');
		$this->persona_antecedente_model->set_database($DB1);
		$this->load->model('juntas/persona_cargo_model');
		$this->persona_cargo_model->set_database($DB1);

		$this->form_validation->set_rules('observacion_recepcion', 'Observacion de Recepción', 'max_length[150]');

		$titulos_persona = $this->persona_titulo_model->get(array(
			'join' => $this->persona_titulo_model->default_join,
			'persona_id' => $persona->id,
			'titulo_tipo_id' => '1',
			'borrado' => '0'
		));
		$postitulos_persona = $this->persona_titulo_model->get(array(
			'join' => $this->persona_titulo_model->default_join,
			'persona_id' => $persona->id,
			'titulo_tipo_id' => '2',
			'borrado' => '0'
		));
		$posgrados_persona = $this->persona_titulo_model->get(array(
			'join' => $this->persona_titulo_model->default_join,
			'persona_id' => $persona->id,
			'titulo_tipo_id' => '3',
			'borrado' => '0'
		));

		$antiguedad_persona = $this->persona_antiguedad_model->get(array('persona_id' => $persona->id,
			'join' => array(
				array('table' => 'antiguedad_tipo', 'where' => 'persona_antiguedad.antiguedad_tipo_id=antiguedad_tipo.id', 'type' => 'left', 'columnas' => array("antiguedad_tipo.descripcion")),
				array('table' => 'entidad_emisora', 'where' => 'entidad_emisora.id=persona_antiguedad.entidad_emisora_id', 'type' => 'left', 'columnas' => array("entidad as institucion"))
		)));

		$antecedentes_persona = $this->persona_antecedente_model->get(array('persona_id' => $persona->id,
			'join' => array(
				array('table' => 'modalidad', 'where' => 'modalidad.id=persona_antecedente.modalidad_id', 'type' => 'left', 'columnas' => array('descripcion as modalidad'))
		)));

		$persona_cargos = $this->persona_cargo_model->get(array(
			'documento_bono' => $persona->documento,
			'estado' => '1'
		));

		$data['persona_cargos'] = $persona_cargos;
		$data['antecedentes_persona'] = $antecedentes_persona;
		$data['antiguedad_persona'] = $antiguedad_persona;
		$data['titulos_persona'] = $titulos_persona;
		$data['postitulos_persona'] = $postitulos_persona;
		$data['posgrados_persona'] = $posgrados_persona;
		$data['error'] = (validation_errors() ? validation_errors() : ($this->recepcion_model->get_error() ? $this->recepcion_model->get_error() : $this->session->flashdata('error')));
		$data['message'] = $this->session->flashdata('message');
		$data['inscripcion'] = $inscripcion;
		$data['persona'] = $persona;
		$this->load->model('bono_secundario/inscripcion_model');
		$persona->observaciones_recepcion = '';
		unset($this->recepcion_model->fields['documento_tipo']['input_type']);
		unset($this->recepcion_model->fields['sexo']['input_type']);
		unset($this->recepcion_model->fields['sexo']['array']);
		$this->recepcion_model->fields['sexo']['readonly'] = TRUE;
		$this->recepcion_model->fields['observaciones_recepcion']['value'] = $inscripcion->observaciones_recepcion;
		$data['fields'] = $this->build_fields($this->recepcion_model->fields, $persona);
		$data['escuela'] = $escuela;
		$data['recibir'] = "Recibir y aceptar seleccionado";
		$data['recibir_forzar'] = "Recibir y no permitir modificaciones";
		$data['class'] = array('precepcion' => '', 'recibidos' => '');
		$data['title'] = 'Bono Secundario - Ver persona';
		$this->load_template('juntas/inscripcion/inscripcion_ver', $data);
	}
}