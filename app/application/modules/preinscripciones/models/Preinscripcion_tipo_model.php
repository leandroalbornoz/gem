<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Preinscripcion_tipo_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'preinscripcion_tipo';
		$this->msg_name = 'Tipo de preinscripción';
		$this->id_name = 'id';
		$this->columnas = array('id', 'instancia', 'descripcion', 'preinscripcion_operativo_id', 'descripcion_larga');
		$this->requeridos = array('instancia', 'descripcion', 'preinscripcion_operativo_id', 'descripcion_larga');
		//$this->unicos = array();
		$this->default_join = array();
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
/* End of file Preinscripcion_tipo_model.php */
/* Location: ./application/modules/preinscripciones/models/Preinscripcion_tipo_model.php */