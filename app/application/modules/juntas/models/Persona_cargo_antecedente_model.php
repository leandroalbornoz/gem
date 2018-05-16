<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Persona_cargo_antecedente_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'persona_cargo_antecedente';
		$this->msg_name = 'Cargo - Antecedente de Persona';
		$this->id_name = 'id';
		$this->columnas = array('id', 'persona_antecedente_id', 'persona_cargo_id');
		$this->requeridos = array();
	}

	public function get_cargos_habilitados($persona_documento) {
		$this->db->select('cargo')
			->from('persona_cargo')
			->where('documento_bono', $persona_documento);
		$query = $this->db->get();
		if (count($query->result()) > 1) {
			foreach ($query->result() as $cargo) {
				$persona_cargos[] = $cargo->cargo;
			}
			return $persona_cargos;
		} else if (count($query->result()) == 1) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	public function get_cargos($persona_documento) {
		$this->db->select("GROUP_CONCAT(DISTINCT pc.cargo ORDER BY pc.cargo SEPARATOR ', ') cargos")
			->from('persona_cargo pc')
			->where('pc.documento_bono	', $persona_documento);
		$query = $this->db->get();
		return $query->row();
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
/* End of file Posgrado_tipo_model.php */
/* Location: ./application/modules/bono/models/Persona_cargo_model.php */