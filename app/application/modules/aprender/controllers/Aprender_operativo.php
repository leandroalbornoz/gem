<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Aprender_operativo extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('aprender/aprender_operativo_model');
		$this->load->model('aprender/aprender_operativo_aplicador_model');
		$this->load->model('escuela_model');
		$this->roles_admin = array(ROL_ADMIN, ROL_USI);
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION);
		$this->nav_route = 'par/aprender_operativo';
	}

	public function ver($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		$operativos = $this->aprender_operativo_model->get_operativos($escuela_id);
		if (empty($operativos)) {
			$this->session->set_flashdata('error', 'El Operativo Aprender no se encuentra habilitado para su Escuela');
			redirect('escritorio');
		}
		$aplicadores = $this->aprender_operativo_aplicador_model->get_aplicadores_escuela($escuela->id);

		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['aplicadores'] = $aplicadores;
		$data['escuela'] = $escuela;
		$data['operativos'] = $operativos;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver operativo aprender';
		$this->load_template('aprender_operativo/aprender_operativo_abm', $data);
	}

	public function modal_cerrar($aprender_operativo_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $aprender_operativo_id == NULL || !ctype_digit($aprender_operativo_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$aprender_operativo = $this->aprender_operativo_model->get_one($aprender_operativo_id);
		if (empty($aprender_operativo)) {
			show_error('No se encontró el registro del operativo aprender', 500, 'Registro no encontrado');
		}
		if (!empty($aprender_operativo->fecha_cierre)) {
			$this->session->set_flashdata('error', 'La planilla de carga del operativo ya ha sido cerrada.');
			redirect("aprender/aprender_operativo/ver/$aprender_operativo->escuela_id", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($aprender_operativo->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->db->trans_begin();
		$trans_ok = TRUE;
		$trans_ok &= $this->aprender_operativo_model->update(array(
			'id' => $aprender_operativo->id,
			'fecha_cierre' => date('Y-m-d H:i:s')
			), FALSE);
		if ($this->db->trans_status() && $trans_ok) {
			$this->db->trans_commit();
			$this->session->set_flashdata('message', 'Se cerró la carga correctamente');
			redirect("aprender/aprender_operativo/ver/$aprender_operativo->escuela_id", 'refresh');
		} else {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', $this->aprender_operativo_model->get_error());
			redirect("aprender/aprender_operativo/ver/$aprender_operativo->escuela_id", 'refresh');
		}
	}

	public function modal_abrir($aprender_operativo_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_admin) || $aprender_operativo_id == NULL || !ctype_digit($aprender_operativo_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$aprender_operativo = $this->aprender_operativo_model->get_one($aprender_operativo_id);
		if (empty($aprender_operativo)) {
			show_error('No se encontró el registro del operativo aprender', 500, 'Registro no encontrado');
		}
		if (empty($aprender_operativo->fecha_cierre)) {
			$this->session->set_flashdata('error', 'La planilla de carga del operativo ya ha sido abierta.');
			redirect("aprender/aprender_operativo/ver/$aprender_operativo->escuela_id", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($aprender_operativo->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->db->trans_begin();
		$trans_ok = TRUE;
		$trans_ok &= $this->aprender_operativo_model->update(array(
			'id' => $aprender_operativo->id,
			'fecha_cierre' => ''
			), FALSE);
		if ($this->db->trans_status() && $trans_ok) {
			$this->db->trans_commit();
			$this->session->set_flashdata('message', 'Se abrió la carga correctamente');
			redirect("aprender/aprender_operativo/ver/$aprender_operativo->escuela_id", 'refresh');
		} else {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', $this->aprender_operativo_model->get_error());
			redirect("aprender/aprender_operativo/ver/$aprender_operativo->escuela_id", 'refresh');
		}
	}

	public function imprimir_pdf($operativo_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $operativo_id == NULL || !ctype_digit($operativo_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$operativo = $this->aprender_operativo_model->get_one($operativo_id);
		if (empty($operativo)) {
			show_error('No se encontró el registro del operativo aprender', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($operativo->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$aplicadores = $this->aprender_operativo_aplicador_model->get_aplicadores_escuela($escuela->id);

		$data['error'] = $this->session->flashdata('error');
		$data['aplicadores'] = isset($aplicadores[$operativo->operativo_tipo_id]) ? $aplicadores[$operativo->operativo_tipo_id] : array();
		$data['escuela'] = $escuela;
		$data['operativo'] = $operativo;

		$fecha_actual = date('d/m/Y');
		$content = $this->load->view('aprender/aprender_operativo/aprender_operativo_imprimir_pdf', $data, TRUE);
		$this->load->helper('mpdf');
		$watermark = '';
		exportarMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Operativo Aprender 2017', 'Planilla del operativo Aprender 2017 - Esc. "' . trim($escuela->nombre) . '" Nº ' . $escuela->numero . ' " - Fecha actual: ' . $fecha_actual, '|{PAGENO} de {nb}|', '', $watermark, 'I', FALSE, FALSE);
	}

	public function modal_buscar_aplicador($aprender_operativo_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $aprender_operativo_id == NULL || !ctype_digit($aprender_operativo_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$operativo = $this->aprender_operativo_model->get_one($aprender_operativo_id);
		if (empty($operativo)) {
			show_error('No se encontró el registro del operativo', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($operativo->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$data['docentes'] = $this->aprender_operativo_model->buscar_aplicadores($operativo);
		$data['escuela'] = $escuela;
		$data['operativo'] = $operativo;
		$data['title'] = 'Buscar aplicador a agregar';
		$this->load->view('aprender/aprender_operativo/aprender_operativo_modal_buscar_aplicador', $data);
	}

	function agregar_aplicador($operativo_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $operativo_id == NULL || !ctype_digit($operativo_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$operativo = $this->aprender_operativo_model->get_one($operativo_id);
		if (empty($operativo)) {
			show_error('No se encontró el registro del operativo', 500, 'Registro no encontrado');
		}
		if (!empty($operativo->fecha_cierre)) {
			$this->session->set_flashdata('error', 'La planilla de carga ya ha sido cerrada.');
			redirect("aprender/aprender_operativo/ver/$operativo->escuela_id", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($operativo->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->form_validation->set_rules('aplicador', 'Aplicador', 'required|integer');
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$aplicador = $this->input->post('aplicador');
				$docentes = $this->aprender_operativo_model->buscar_aplicadores($operativo);
				$aplicador_ok = FALSE;
				foreach ($docentes as $docente) {
					if ($docente->id === $aplicador && empty($docente->aplicador_id) && !empty($docente->email) && (!empty($docente->telefono_fijo) || !empty($docente->telefono_movil))
					) {
						$aplicador_ok = TRUE;
					}
				}
				if (!$aplicador_ok) {
					$this->session->set_flashdata('error', 'El aplicador ingresado no es válido.');
					redirect("aprender/aprender_operativo/ver/$operativo->escuela_id", 'refresh');
				}
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->aprender_operativo_aplicador_model->create(array(
					'aprender_operativo_id' => $operativo_id,
					'persona_id' => $aplicador
					), FALSE);
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->aprender_operativo_aplicador_model->get_msg());
					redirect('aprender/aprender_operativo/ver/' . $escuela->id, 'refresh');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->eleccion_desinfeccion_persona_model->get_error());
					redirect('aprender/aprender_operativo/ver/' . $escuela->id, 'refresh');
				}
			}
		}

		$data['escuela'] = $escuela;
		$data['aprender_operativo'] = $operativo;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Aplicadores de Operativo Aprender';
		$this->load->view('aprender_operativo_aplicador/aprender_operativo_aplicador_modal_listar', $data);
	}

	public function modal_eliminar_aplicador($operativo_id = NULL, $aplicador_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $operativo_id == NULL || !ctype_digit($operativo_id) || $aplicador_id == NULL || !ctype_digit($aplicador_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$operativo = $this->aprender_operativo_model->get_one($operativo_id);
		if (empty($operativo)) {
			show_error('No se encontró el registro de del operativo', 500, 'Registro no encontrado');
		}
		if (!empty($operativo->fecha_cierre)) {
			$this->session->set_flashdata('error', 'La planilla de carga ya ha sido cerrada.');
			redirect("aprender/aprender_operativo/ver/$operativo->escuela_id", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($operativo->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$aplicador = $this->aprender_operativo_aplicador_model->get_one($aplicador_id);
		if (empty($aplicador) || $aplicador->aprender_operativo_id !== $operativo->id) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($operativo->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$trans_ok &= $this->aprender_operativo_aplicador_model->update(array(
				'id' => $aplicador->id,
				'estado' => 'Eliminado'
				), FALSE);
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Aplicador eliminado correctamente');
				redirect("aprender/aprender_operativo/ver/$operativo->escuela_id", 'refresh');
			} else {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', $this->aprender_operativo_aplicador_model->get_error());
				redirect("aprender/aprender_operativo/ver/$operativo->escuela_id", 'refresh');
			}
		}
		$operativo->escuela = $escuela->nombre_largo;
		$data['fields'] = $this->build_fields($this->aprender_operativo_aplicador_model->fields, $aplicador, TRUE);
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['aprender_operativo'] = $operativo;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar aplicador';
		$this->load->view('aprender/aprender_operativo/aprender_operativo_modal_eliminar_aplicador', $data);
	}
}
/* End of file Aprender_operativo.php */
/* Location: ./application/controllers/Aprender_operativo.php */