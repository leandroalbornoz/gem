<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ficha_p_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'ficha_p';
		$this->msg_name = 'Ficha psicopedagógica';
		$this->id_name = 'id';
		$this->columnas = array('id', 'alumno_id', 'informe', 'situacion_familiar', 'actividad_laboral', 'actividad_laboral_tipo');
		$this->fields = array(
			'informe' => array('label' => 'Tipo de archivo (jpg/png/pdf)', 'form_type' => 'input', 'readonly' => TRUE),
			'situacion_familiar' => array('label' => 'Datos importantes que desee agregar (sociales, familiares, de salud, económicos, etc.):', 'form_type' => 'textarea', 'rows' => '5', 'placeholder' => 'Situación socio familiar compleja...', 'maxlength' => '512'),
			'actividad_laboral' => array('label' => '¿El alumno realiza actividades laborales?', 'form_type' => 'textarea', 'rows' => '5', 'placeholder' => 'Describa las actividades...'),
			'actividad_laboral_tipo' => array('label' => 'Actividad laboral tipo'),
		);

		$this->requeridos = array('alumno_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('alumno', 'alumno.id = ficha_p.alumno_id', 'left', array('alumno.persona_id as persona')));
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		return TRUE;
	}
}
/* End of file Alumno_model.php */
/* Location: ./application/models/Alumno_model.php */