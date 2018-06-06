<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Abono_tipo_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'abono_tipo';
		$this->msg_name = 'Tipo Abono';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripcion', 'maxlength' => '255')
		);
		$this->requeridos = array();
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
		if ($this->db->where('abono_tipo_id', $delete_id)->count_all_results('abono_alumno') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a abono de alumno.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Tipo_abono_model.php */
/* Location: ./application/modules/abono/models/Tipo_abono_model.php */