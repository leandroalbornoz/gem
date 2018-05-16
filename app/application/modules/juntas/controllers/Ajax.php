<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->roles_permitidos = array(ROL_JUNTAS, ROL_MODULO, ROL_ADMIN);
		$this->nav_route = 'titulo/titulo_persona';
		$this->modulos_permitidos = array(ROL_MODULO_ADMINISTRADOR_DE_JUNTAS);
	}

	public function get_listar_antecedentes() {
		if (accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos)) {

			$DB1 = $this->load->database('bono_secundario', TRUE);
			$this->load->model('juntas/persona_antecedente_avalado_model');
			$this->persona_antecedente_avalado_model->set_database($DB1);

			$numero_resolucion = $this->input->get('numero_resolucion');
			$antecedente_nombre = $this->input->get('antecedente');
			$identificador = $this->input->get('identificador');
			if (!empty($numero_resolucion)) {
				$antecedentes_listar = $this->persona_antecedente_avalado_model->get(array(
					'numero_resolucion like both' => $numero_resolucion
				));
				if (!empty($antecedentes_listar)) {
					foreach ($antecedentes_listar as $antecedente) {
						if ($antecedente->crsid === NULL) {
							$antecedente->crsid = '';
						}
						if ($antecedente->antecedente === NULL) {
							$antecedente->antecedente = '';
						}
						if ($antecedente->institucion === NULL) {
							$antecedente->institucion = '';
						}
						if ($antecedente->numero_resolucion === NULL) {
							$antecedente->numero_resolucion = '';
						}
					}
				}
			}

			if (!empty($identificador)) {
				$antecedentes_listar = $this->persona_antecedente_avalado_model->get(array(
					'crsid' => $identificador
				));
				if (!empty($antecedentes_listar)) {
					foreach ($antecedentes_listar as $antecedente) {
						if ($antecedente->crsid === NULL) {
							$antecedente->crsid = '';
						}
						if ($antecedente->antecedente === NULL) {
							$antecedente->antecedente = '';
						}
						if ($antecedente->institucion === NULL) {
							$antecedente->institucion = '';
						}
						if ($antecedente->numero_resolucion === NULL) {
							$antecedente->numero_resolucion = '';
						}
					}
				}
			}

			if (!empty($antecedente_nombre) && strlen($antecedente_nombre) >= 3) {
				$antecedentes_listar = $this->persona_antecedente_avalado_model->get(array(
					'antecedente like both' => $antecedente_nombre
				));

				if (!empty($antecedentes_listar)) {
					foreach ($antecedentes_listar as $antecedente) {
						if ($antecedente->crsid === NULL) {
							$antecedente->crsid = '';
						}
						if ($antecedente->antecedente === NULL) {
							$antecedente->antecedente = '';
						}
						if ($antecedente->institucion === NULL) {
							$antecedente->institucion = '';
						}
						if ($antecedente->numero_resolucion === NULL) {
							$antecedente->numero_resolucion = '';
						}
					}
				}
			}

			if (!empty($antecedentes_listar)) {
				echo json_encode(array('status' => 'success', 'antecedentes_listar' => $antecedentes_listar));
			} else {
				echo json_encode(array('status' => 'error'));
			}
		} else {
			show_404();
		}
	}
}
/* End of file Ajax.php */
	/* Location: ./application/modules/domicilio/controllers/Ajax.php */	