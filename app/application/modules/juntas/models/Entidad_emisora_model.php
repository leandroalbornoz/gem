<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Entidad_emisora_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'entidad_emisora';
		$this->msg_name = 'Entidad emisora';
		$this->id_name = 'id';
		$this->columnas = array('id', 'provincia_id', 'cue_anexo', 'entidad', 'localidad', 'departamento', 'email');
		$this->requeridos = array();
	}

	public function get_entidad($provincia = '', $entidad = '') {
		$this->db->select('entidad_emisora.id as entidad_id, cue_anexo as cue, entidad, provincia')
			->from('entidad_emisora')
			->join('provincia', 'provincia.id = entidad_emisora.provincia_id', 'left')
			->order_by('entidad_emisora.entidad', 'ASC');
		if (!empty($provincia)  || !empty($entidad)) {
			$this->db->where('entidad_emisora.provincia_id', $provincia);
			$this->db->like('entidad_emisora.entidad', $entidad, 'after');
		} else {
			return FALSE;
		}
	}

	function find_entidad_emisora($search) {
		$this->db->select('id, entidad as entidad_emisora')
			->from('entidad_emisora')
			->like('entidad', $search);
		return $this->db->get()->result();
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
/* End of file Antiguedad_tipo_model.php */
/* Location: ./application/modules/bono/models/Entidad_emisora_model.php */