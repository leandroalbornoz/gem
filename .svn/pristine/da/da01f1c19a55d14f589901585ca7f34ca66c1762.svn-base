<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bono_escuelas_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'bono_escuelas';
		$this->msg_name = 'Escuela de bono';
		$this->id_name = 'id';
		$this->columnas = array('id', 'numero', 'nombre', 'tabla', 'gem_id');
		$this->default_join = array();
	}
	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('autoridad_tipo_id', $delete_id)->count_all_results('escuela_autoridad') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a escuela de autoridad.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Bono_escuelas_model.php */
/* Location: ./application/models/Bono_escuelas_model.php */