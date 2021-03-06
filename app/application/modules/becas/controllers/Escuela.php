<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escuela extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->load->model('becas/beca_persona_model');
		$this->load->model('becas/beca_etapa_model');
		$this->load->model('becas/beca_estado_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_DIR_ESCUELA, ROL_LINEA, ROL_SUPERVISION, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_JEFE_LIQUIDACION, ROL_LIQUIDACION, ROL_REGIONAL, ROL_USI, ROL_GRUPO_ESCUELA, ROL_GRUPO_ESCUELA_CONSULTA);
		$this->roles_admin = array(ROL_USI, ROL_ADMIN);
		$this->modulos_permitidos = array(ROL_MODULO_BECAS);
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_GRUPO_ESCUELA_CONSULTA, ROL_REGIONAL))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'admin/escuela';
	}

	public function recepcion($escuela_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		if (!in_array($escuela->nivel_id, array('2', '3', '4', '6', '8', '22', '23')) || $escuela->dependencia_id !== '1') {
			$this->session->set_flashdata('error', 'No se encuentra habilitado el módulo para el nivel de su escuela');
			redirect('escritorio');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$etapa = $this->beca_etapa_model->get_one(1);
		if ($etapa->inicio > date('Y-m-d') || $etapa->fin < date('Y-m-d')) {
			$vista = 'becas/escuela/escuela_recepcion_editar';
		} else {
			$vista = 'becas/escuela/escuela_recepcion';
		}

		$this->form_validation->set_rules('cuil', 'CUIL', 'required|callback_validaCuil');
		$this->form_validation->set_message('validaCuil', 'Cuil no válido');
		if ($this->form_validation->run() === TRUE) {
			$cuil = $this->input->post('cuil');
			$data['persona'] = $this->beca_persona_model->buscar_persona($cuil);
		}

		$personas = $this->beca_persona_model->get(array(
			'escuela_id' => $escuela->id,
			'join' => array(
				array('persona', 'persona.id=beca_persona.persona_id', 'left', array('cuil', 'nombre', 'apellido')),
				array('beca_estado', 'beca_estado.id=beca_persona.beca_estado_id', 'left', array('beca_estado.descripcion as beca_estado')),
				array('beca_etapa', 'CURDATE() BETWEEN beca_etapa.inicio AND beca_etapa.fin', 'left'),
				array('beca_operacion', "beca_operacion.beca_estado_o_id=beca_estado.id AND beca_operacion.beca_etapa_id=beca_etapa.id AND beca_operacion.cambia_escuela='Si'", 'left', array('COUNT(DISTINCT beca_operacion.id) operaciones')),
			),
			'group_by' => 'beca_persona.id',
			'sort_by' => 'beca_estado.id DESC, fecha DESC'
		));

		$data['error'] = $this->session->flashdata('error');
//		$data['etapas'] = $this->beca_etapa_model->get();

		$data['personas'] = $personas;
		$data['escuela'] = $escuela;
		$data['title'] = 'Becas - Recepción Postulaciones';
		$data['vw_etapas'] = $this->load->view('becas/validacion/validacion_etapas', array('etapas' => $this->beca_etapa_model->get()), TRUE);
		$this->load_template($vista, $data);
	}

	public function recibir($escuela_id) {
		if (!accion_permitida($this->rol, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || !$this->edicion) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$etapa = $this->beca_etapa_model->get_one(1);
		if ($etapa->fin < date('Y-m-d')) {
			show_error('Ya se ha cerrado el periodo de postulación', 500, 'Acción no autorizada');
		}

		$this->form_validation->set_rules('id', 'Id Persona', 'integer|required');
		$this->form_validation->set_rules('cuil', 'CUIL', 'required|callback_validaCuil');
		$this->form_validation->set_message('validaCuil', 'Cuil no válido');
		if ($this->form_validation->run() === TRUE) {
			$persona = $this->beca_persona_model->buscar_persona($this->input->post('cuil'));
			if (!empty($persona) && $persona->id === $this->input->post('id')) {
				$escuelas = explode(',', $persona->escuelas);
				if (in_array($escuela->id, $escuelas) && empty($persona->escuela_postulada)) {
					$trans_ok = $this->beca_persona_model->create(array(
						'beca_id' => '1',
						'escuela_id' => $escuela->id,
						'persona_id' => $persona->id,
						'fecha' => date('Y-m-d H:i:s'),
						'beca_estado_id' => '1'
					));
					if ($trans_ok) {
						$this->session->set_flashdata('message', $this->beca_persona_model->get_msg());
					} else {
						$this->session->set_flashdata('error', $this->beca_persona_model->get_error());
					}
				} elseif(empty($persona->escuela_postulada)) {
					$this->session->set_flashdata('error', "La persona no posee servicios activos en su escuela, no puede registrarla como Postulante. En el caso de que el servicio esté en el anexo de la escuela puede ir a Seleccionar Rol para elegir el rol del anexo y desde ahí realizar la carga.");
				} else {
					$this->session->set_flashdata('error', "Ya se ha recibido la postulación de la persona en $persona->escuela_postulada.");
				}
			} else {
				$this->session->set_flashdata('error', 'Persona no encontrada');
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
		}
		redirect("becas/escuela/recepcion/$escuela->id");
	}

	public function etapa($escuela_id = NULL, $etapa_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $etapa_id == NULL || !ctype_digit($etapa_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->form_validation->set_rules('id', 'Id Persona', 'integer|required');
		$this->form_validation->set_rules('cuil', 'CUIL', 'required|callback_validaCuil');
		$this->form_validation->set_message('validaCuil', 'Cuil no válido');
		if ($this->form_validation->run() === TRUE) {
			$persona = $this->beca_persona_model->buscar_persona($this->input->post('cuil'));
			if (!empty($persona) && $persona->id === $this->input->post('id')) {
				$escuelas = explode(',', $persona->escuelas);
				if (in_array($escuela->id, $escuelas) && empty($persona->escuela_postulada)) {
					$trans_ok = $this->beca_persona_model->create(array(
						'beca_id' => '1',
						'escuela_id' => $escuela->id,
						'persona_id' => $persona->id,
						'fecha' => date('Y-m-d H:i:s'),
						'beca_estado_id' => '1'
					));
					if ($trans_ok) {
						$this->session->set_flashdata('message', $this->beca_persona_model->get_msg());
					} else {
						$this->session->set_flashdata('error', $this->beca_persona_model->get_error());
					}
				} else {
					$this->session->set_flashdata('error', "Ya se ha recibido la postulación de la persona en $persona->escuela_postulada.");
				}
			} else {
				$this->session->set_flashdata('error', 'Persona no encontrada');
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
		}
		redirect("becas/escuela/recepcion/$escuela->id");
	}

	public function imprimir($beca_persona_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos) || $beca_persona_id == NULL || !ctype_digit($beca_persona_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$beca_persona = $this->beca_persona_model->get_one($beca_persona_id);
		if (empty($beca_persona)) {
			show_error('No se encontró el registro recibido', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($beca_persona->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		if ($beca_persona->beca_estado_id !== '1') {
			show_error('La persona no es postulante', 500, 'Acción no permitida');
		}
		$data['escuela'] = $escuela;
		$data['beca_persona'] = $beca_persona;
		$data['servicios'] = $this->beca_persona_model->get_servicios($beca_persona->persona_id);
		$this->load->helper('mpdf');
		$html = $this->load->view('becas/escuela/escuela_persona_imprimir_pdf', $data, TRUE);
		exportarMPDF($html, 'plugins/kv-mpdf-bootstrap.min.css', "Esc. $escuela->nombre_corto - Persona $beca_persona->cuil", "|Becas - Recepción Postulación|Fecha: " . (new DateTime($beca_persona->fecha))->format('d/m/Y'), '', 'A4', '', 'I', FALSE, FALSE);
	}

	public function validaCuil($cuit) {
		$digits = array();
		if (strlen($cuit) != 13)
			return false;
		for ($i = 0; $i < strlen($cuit); $i++) {
			if ($i == 2 or $i == 11) {
				if ($cuit[$i] != '-')
					return false;
			} else {
				if (!ctype_digit($cuit[$i]))
					return false;
				if ($i < 12) {
					$digits[] = $cuit[$i];
				}
			}
		}
		$acum = 0;
		foreach (array(5, 4, 3, 2, 7, 6, 5, 4, 3, 2) as $i => $multiplicador) {
			$acum += $digits[$i] * $multiplicador;
		}
		$cmp = 11 - ($acum % 11);
		if ($cmp == 11)
			$cmp = 0;
		if ($cmp == 10)
			$cmp = 9;
		return ($cuit[12] == $cmp);
	}

	public function modal_editar($id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$beca_persona = $this->beca_persona_model->get_one($id);
		if (empty($beca_persona)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['error'] = $this->session->flashdata('error');

		$data['fields'] = $this->build_fields($this->beca_persona_model->fields, $beca_persona, TRUE);

		$data['beca_persona'] = $beca_persona;
		$this->load->model('becas/beca_operacion_model');
		$data['operaciones_posibles'] = $this->beca_operacion_model->get_operaciones($beca_persona->beca_estado_id, 'escuela');
		$data['title'] = 'Editar Beca Docente';
		$this->load->view('becas/validacion/validacion_modal_abm', $data);
	}

	public function operacion($escuela_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || !isset($_POST)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$beca_persona_id = $this->input->post('id');
		$operacion = $this->input->post('operacion');
		if (empty($beca_persona_id) || empty($operacion)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$beca_persona = $this->beca_persona_model->get_one($beca_persona_id);
		if (empty($beca_persona)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('beca_operacion_model');
		$operaciones_permitidas = $this->beca_operacion_model->get_operaciones($beca_persona->beca_estado_id);
		$operacion_permitida = FALSE;
		foreach ($operaciones_permitidas as $o_p) {
			if ($o_p->id === $operacion) {
				$operacion_permitida = $o_p;
				if ($o_p->cambia_validador === 'No') {
					show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
				}
			}
		}
		if ($operacion_permitida) {
			$trans_ok = $this->beca_persona_model->update(array(
				'id' => $beca_persona->id,
				'beca_estado_id' => $operacion_permitida->beca_estado_d_id
			));
			if ($trans_ok) {
				$this->session->set_flashdata('message', 'Estado cambiado correctamente');
			} else {
				$this->session->set_flashdata('error', 'Ocurrió un error al intentar cambiar el estado');
			}
		} else {
			$this->session->set_flashdata('error', 'Ocurrió un error al intentar cambiar el estado');
		}
		redirect("becas/escuela/recepcion/$beca_persona->escuela_id");
	}
}
/* End of file Escuela.php */
/* Location: ./application/modules/becas/controllers/Escuela.php */