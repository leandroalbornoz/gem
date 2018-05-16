<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inasistencia_tipo_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'inasistencia_tipo';
		$this->msg_name = 'Tipo de inasistencia';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'falta_defecto', 'falta_contraturno');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '45', 'required' => TRUE),
			'falta_defecto' => array('label' => 'Falta', 'type' => 'decimal', 'required' => TRUE)
		);
		$this->requeridos = array('descripcion');
		//$this->unicos = array();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('inasistencia_tipo_id', $delete_id)->count_all_results('alumno_inasistencia') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a alumno de inasistencia.');
			return FALSE;
		}
		return TRUE;
	}

	public function get_array() {
		$ti = $this->get();
		$ts = array();
		foreach ($ti as $t) {
			$ts[$t->id] = $t;
		}
		return $ts;
	}
}
/* End of file Inasistencia_tipo_model.php */
/* Location: ./application/models/Inasistencia_tipo_model.php */