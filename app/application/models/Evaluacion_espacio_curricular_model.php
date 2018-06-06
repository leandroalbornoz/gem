<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluacion_espacio_curricular_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'evaluacion_espacio_curricular';
		$this->msg_name = 'Evaluación espacio curricular';
		$this->id_name = 'id';
		$this->columnas = array('id', 'espacio_curricular_id', 'concepto');
		$this->fields = array(
		);
		$this->requeridos = array('espacio_curricular_id', 'concepto');
		//$this->unicos = array();
		$this->default_join = array();
	}

	public function get_espacios_curriculares_id() {
		$result = $this->db->select('espacio_curricular_id')
				->from('evaluacion_espacio_curricular')
				->group_by('espacio_curricular_id')
				->get()->result();
		$array_espacio_curricular_ids = array();
		foreach ($result as $key => $espacio_curricular_id) {
			$array_espacio_curricular_ids[$key] = $espacio_curricular_id->espacio_curricular_id;
		}
		return $array_espacio_curricular_ids;
	}

	public function get_conceptos_evaluacion($espacio_curricular_id) {
		$result = $this->db->select('id,espacio_curricular_id, concepto')
				->from('evaluacion_espacio_curricular')
				->where('espacio_curricular_id', $espacio_curricular_id)
				->get()->result();
		$array_evaluacion_espacio_curricular = array();
		foreach ($result as $key => $evaluacion_espacio_curricular) {
			$concepto = explode(':', $evaluacion_espacio_curricular->concepto)[0];
			$array_evaluacion_espacio_curricular[$concepto][] = str_replace($concepto . ':', '', $evaluacion_espacio_curricular->concepto);
		}
		return $array_evaluacion_espacio_curricular;
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
/* End of file Evaluacion_model.php */
/* Location: ./application/models/Evaluacion_model.php */
