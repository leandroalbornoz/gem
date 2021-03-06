<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Espacio_curricular_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'espacio_curricular';
		$this->msg_name = 'Espacio curricular';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'materia_id');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'readonly' => TRUE)
		);
	}

	function get_espacios_cct($numero_area = NULL, $espacio = NULL) {
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$DB1->select('ea.id, ea.espacio_id, ec.descripcion as espacio')
			->from('espacio_antes ea')
			->join('espacio_curricular ec', "ec.id = ea.espacio_id and nroarea = $numero_area", 'left')
			->like('descripcion', $espacio);
		$query = $DB1->get();
		return $query->result();
	}

	function find_entidad_emisora($search) {
		$this->db->select('id, entidad as entidad_emisora')
			->from('entidad_emisora')
			->like('entidad', $search);
		return $this->db->get()->result();
	}

	function get_espacio_cargo($persona_cargo_id = NULL) {
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$DB1->select('pi.id persona_idoneidad_id, ec.descripcion')
			->from('espacio_curricular ec')
			->join('persona_idoneidad pi', "pi.espacio_id = ec.id", 'left')
			->join('persona_cargo_antecedente pca', "pca.id = pi.persona_cargo_antecedente_id", 'left')
			->join('persona_cargo pc', "pc.id = pca.persona_cargo_id", 'left')
			->join('persona p', "p.documento = pc.documento_bono", 'left')
			->where('pc.id', $persona_cargo_id);
		$query = $DB1->get();
		return $query->result();
	}

	function verificar_espacio($espacio_id = NULL, $persona_cargo_antecedente_id = NULL) {
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$DB1->select('pi.id')
			->from('persona_idoneidad pi')
			->join('persona_cargo_antecedente pca', "pca.id = pi.persona_cargo_antecedente_id", 'left')
			->where('pi.espacio_id', $espacio_id)
			->where('pca.id', $persona_cargo_antecedente_id);
		$query = $DB1->get()->result();
		if (!empty($query)) {
			return "1";
		} else {
			return "0";
		}
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
/* Location: ./application/modules/bono/models/Persona_idoneidad_model.php */