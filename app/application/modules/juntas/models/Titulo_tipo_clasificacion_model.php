<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Titulo_tipo_clasificacion_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'titulo_tipo_clasificacion';
		$this->msg_name = 'Clasificación de título';
		$this->id_name = 'id';
		$this->columnas = array('id', 'titulo_tipo_id', 'descripcion', 'puntaje');
		$this->requeridos = array('id', 'titulo_tipo_id', 'descripcion');
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
/* End of file Titulo_tipo_clasificacion_model.php */
/* Location: ./application/modules/bono/models/Titulo_tipo_clasificacion_model.php */