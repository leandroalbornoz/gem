<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Titulo_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'titulo';
		$this->msg_name = 'Título';
		$this->id_name = 'id';
		$this->columnas = array('id', 'NroTit', 'NroTitS', 'NomTitLon', 'titulo_tipo_id');
		$this->requeridos = array('NomTitLon', 'titulo_tipo_id');
		$this->default_join = array(
			array('persona_titulo', 'persona_titulo.titulo_id = titulo.id', 'left', array('concat(escuela.numero, " - ", escuela.nombre) as escuela'))
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
/* End of file Titulo_model.php */
/* Location: ./application/modules/bono/models/Titulo_model.php */