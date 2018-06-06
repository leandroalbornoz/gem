<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Persona_antecedente_avalado_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'curso_avalado';
		$this->msg_name = 'Antecedente de Persona';
		$this->id_name = 'id';
		$this->columnas = array('id', 'crsid', 'institucion', 'antecedente', 'duracion', 'tipo_duracion', 'numero_resolucion', 'fecha_resolucion');
		$this->fields = array(
			'persona' => array('label' => 'Persona', 'readonly' => TRUE)
		);
		$this->default_join = array(
			array('persona', 'persona.id=persona_antecedente.persona_id', 'left', array('CONCAT(cuil, \' \', persona.apellido, \', \', persona.nombre) as persona'))
		);
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
/* End of file Persona_antecedente_model.php */
/* Location: ./application/modules/juntas/models/Persona_antecedente_model.php */