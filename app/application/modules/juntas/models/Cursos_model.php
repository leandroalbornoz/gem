<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cursos_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'cursos';
		$this->msg_name = 'Antecedente';
		$this->id_name = 'id';
		$this->columnas = array('id', 'antecedente', 'institucion', 'numero_resolucion', 'duracion', 'tipo_duracion');
		$this->requeridos = array('antecedente');
	}
	/*
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */

	protected function _can_delete($delete_id) {
		return TRUE;
	}
}
/* End of file Cursos_model.php */
/* Location: ./application/modules/juntas/models/Cursos_model.php */