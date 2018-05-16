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
}