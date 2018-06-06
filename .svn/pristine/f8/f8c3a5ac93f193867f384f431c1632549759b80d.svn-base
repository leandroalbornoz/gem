<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cargo_horario_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'cargo_horario';
		$this->msg_name = 'Horario de cargo';
		$this->id_name = 'id';
		$this->columnas = array('id', 'cargo_id', 'horario_id', 'cuatrimestre');
		$this->requeridos = array('cargo_id', 'horario_id');
		//$this->unicos = array();
	}

	public function get_horarios($cargo_id) {
		return $this->db->query('SELECT d.id dia_id, d.nombre dia, COUNT(ch.id) cantidad
			 FROM dia d
			 LEFT JOIN horario h ON d.id = h.dia_id
			 LEFT JOIN cargo_horario ch ON h.id=ch.horario_id AND ch.cargo_id = ?
			 GROUP BY d.id
			 ORDER BY d.id', array($cargo_id))->result();
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
/* End of file Cargo_horario_model.php */
/* Location: ./application/models/Cargo_horario_model.php */