<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Extintor extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->load->model('extintores/extintores_model');
		$this->load->model('extintores/extintor_model');
		$this->load->model('extintores/extintor_escuela_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION, ROL_DOCENTE);
		$this->nav_route = 'extinctores/extintor';
	}

	public function ver($relevamiento_id = NULL, $escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $relevamiento_id == NULL || !ctype_digit($relevamiento_id) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$relevamiento = $this->extintor_escuela_model->get_relevamiento_escuela($relevamiento_id, $escuela_id);
		if (empty($relevamiento)) {
			$this->session->set_flashdata('error', 'La escuela no participa del operativo de desinfección por elecciones');
			redirect("escritorio", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$relevamiento->escuela = $escuela->nombre_largo;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->extintor_escuela_model->fields, $relevamiento, TRUE);
		$data['relevamiento'] = $relevamiento;
		$data['escuela'] = $escuela;
		$data['extintores'] = $this->extintor_model->get_extintores_escuela($relevamiento_id, $escuela_id);
		$data['txt_btn'] = NULL;
		$data['class'] = array('ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver Relevamiento';

		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template("extintores/extintor/extintor_abm", $data);
	}

	public function modal_agregar($relevamiento_id = NULL, $escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $relevamiento_id == NULL || !ctype_digit($relevamiento_id) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$relevamiento = $this->extintor_escuela_model->get_relevamiento_escuela($relevamiento_id, $escuela_id);
		if (empty($relevamiento)) {
			return $this->modal_error('La escuela no participa del operativo de relevamiento de extintores', 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		$relevamiento->escuela = $escuela->nombre_largo;

		$this->array_tipo_extintor_control = $this->extintor_model->fields['tipo_extintor']['array'];
		$this->set_model_validation_rules($this->extintor_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->extintor_model->create(array(
					'extintor_relevamiento_id' => $relevamiento_id,
					'escuela_id' => $escuela_id,
					'primer_carga' => $this->get_date_sql('primer_carga'),
					'vencimiento' => $this->get_date_sql('vencimiento'),
					'numero_registro' => $this->input->post('numero_registro'),
					'empresa_instalacion' => $this->input->post('empresa_instalacion'),
					'marca' => $this->input->post('marca'),
					'kilos' => $this->input->post('kilos'),
					'tipo_extintor' => $this->input->post('tipo_extintor')
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->extintor_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->extintor_model->get_error());
				}
				redirect("extintores/extintor/ver/$relevamiento_id/$escuela_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("extintores/extintor/ver/$relevamiento_id/$escuela_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->extintor_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar extintor';
		$this->load->view('extintores/extintor/extintor_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$extintor = $this->extintor_model->get(array('id' => $id));
		if (empty($extintor)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$relevamiento = $this->extintor_escuela_model->get_relevamiento_escuela($extintor->extintor_relevamiento_id, $extintor->escuela_id);
		if (empty($relevamiento)) {
			return $this->modal_error('La escuela no participa del operativo de relevamiento de extintores', 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($extintor->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		$relevamiento->escuela = $escuela->nombre_largo;

		$this->array_tipo_extintor_control = $this->extintor_model->fields['tipo_extintor']['array'];
		$this->set_model_validation_rules($this->extintor_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->extintor_model->update(array(
					'id' => $this->input->post('id'),
					'primer_carga' => $this->get_date_sql('primer_carga'),
					'vencimiento' => $this->get_date_sql('vencimiento'),
					'numero_registro' => $this->input->post('numero_registro'),
					'empresa_instalacion' => $this->input->post('empresa_instalacion'),
					'marca' => $this->input->post('marca'),
					'kilos' => $this->input->post('kilos'),
					'tipo_extintor' => $this->input->post('tipo_extintor')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->extintor_model->get_msg());
					redirect("extintores/extintor/ver/$extintor->extintor_relevamiento_id/$extintor->escuela_id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("extintores/extintor/ver/$extintor->extintor_relevamiento_id/$extintor->escuela_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->extintor_model->fields, $extintor);

		$data['extintor'] = $extintor;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar extintor';
		$this->load->view('extintores/extintor/extintor_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$extintor = $this->extintor_model->get(array('id' => $id));
		if (empty($extintor)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		$relevamiento = $this->extintor_escuela_model->get_relevamiento_escuela($extintor->extintor_relevamiento_id, $extintor->escuela_id);
		if (empty($relevamiento)) {
			return $this->modal_error('La escuela no participa del operativo de relevamiento de extintores', 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($extintor->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		$relevamiento->escuela = $escuela->nombre_largo;

		$this->array_tipo_extintor_control = $this->extintor_model->fields['tipo_extintor']['array'];
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->extintor_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->extintor_model->get_msg());
				redirect("extintores/extintor/ver/$extintor->extintor_relevamiento_id/$extintor->escuela_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->extintor_model->get_error());
				redirect("extintores/extintor/ver/$extintor->extintor_relevamiento_id/$extintor->escuela_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->extintor_model->fields, $extintor, TRUE);

		$data['extintor'] = $extintor;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar extintor';
		$this->load->view('extintores/extintor/extintor_modal_abm', $data);
	}

	public function modal_editar_escuela($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$extintor_escuela = $this->extintor_escuela_model->get(array('id' => $id));
		if (empty($extintor_escuela)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$relevamiento = $this->extintor_escuela_model->get_relevamiento_escuela($extintor_escuela->extintor_relevamiento_id, $extintor_escuela->escuela_id);
		if (empty($relevamiento)) {
			return $this->modal_error('La escuela no participa del operativo de relevamiento de extintores', 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($extintor_escuela->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		$relevamiento->escuela = $escuela->nombre_largo;

		$this->set_model_validation_rules($this->extintor_escuela_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->extintor_escuela_model->update(array(
					'id' => $id,
					'extintores_faltantes' => $this->input->post('extintores_faltantes'),
					'observaciones' => $this->input->post('observaciones'),
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->extintor_escuela_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->extintor_escuela_model->get_error());
				}
				redirect("extintores/extintor/ver/$extintor_escuela->extintor_relevamiento_id/$extintor_escuela->escuela_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("extintores/extintor/ver/$extintor_escuela->extintor_relevamiento_id/$extintor_escuela->escuela_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->extintor_escuela_model->fields, $extintor_escuela);

		$data['extintor_escuela'] = $extintor_escuela;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar Extintores Faltantes/Observaciones';
		$this->load->view('extintores/extintor_escuela/extintor_escuela_modal_abm', $data);
	}

	public function imprimir_pdf($relevamiento_id = NULL, $escuela_id = NULL, $tipo_impresion = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $relevamiento_id == NULL || !ctype_digit($relevamiento_id) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$relevamiento = $this->extintor_escuela_model->get_relevamiento_escuela($relevamiento_id, $escuela_id);
		if (empty($relevamiento)) {
			$this->session->set_flashdata('error', 'La escuela no participa del operativo de desinfección por elecciones');
			redirect("escritorio", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$data['error'] = $this->session->flashdata('error');
		$data['extintores'] = $this->extintor_model->get_extintores_escuela($relevamiento_id, $escuela_id);
		$data['escuela'] = $escuela;
		$data['relevamiento'] = $relevamiento;
		$data['tipo_impresion'] = $tipo_impresion;

		$fecha_actual = date('d/m/Y');
		$content = $this->load->view('extintores/extintor/extintor_imprimir_pdf', $data, TRUE);
		$this->load->helper('mpdf');
		$watermark = '';
		exportMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Planilla de Relevamiento Extintores', 'Planilla de Relevamiento Extintores - Esc. "' . trim($escuela->nombre) . '" Nº ' . $escuela->numero . ' " - Fecha actual: ' . $fecha_actual, '|{PAGENO} de {nb}|', '', $watermark, 'I', FALSE, FALSE);
	}
}
/* End of file Extintor.php */
/* Location: ./application/modules/extintores/controllers/Extintor.php */