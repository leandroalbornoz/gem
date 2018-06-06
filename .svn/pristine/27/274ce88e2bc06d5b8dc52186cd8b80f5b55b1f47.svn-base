<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Horario_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'horario';
		$this->msg_name = 'Horario';
		$this->id_name = 'id';
		$this->columnas = array('id', 'dia_id', 'hora_desde', 'hora_hasta', 'division_id', 'hora_catedra', 'obligaciones', 'cargo_id');
		$this->fields = array(
			'division' => array('label' => 'División', 'readonly' => TRUE),
			'hora_catedra' => array('label' => 'Hora Cátedra', 'type' => 'integer', 'maxlength' => '11'),
			'obligaciones' => array('label' => 'Obligaciones', 'type' => 'decimal', 'maxlength' => '4'),
			'dia' => array('label' => 'Día', 'input_type' => 'combo', 'required' => TRUE),
			'hora_desde' => array('label' => 'Hora Desde', 'type' => 'time', 'required' => TRUE),
			'hora_hasta' => array('label' => 'Hora Hasta', 'type' => 'time', 'required' => TRUE)
		);
		$this->requeridos = array('dia_id', 'hora_desde', 'hora_hasta');
		//$this->unicos = array();
		$this->default_join = array(
			array('dia', 'dia.id=horario.dia_id', 'left', array('dia.nombre as dia'))
		);
	}

	public function get_horarios($cargo_id) {
		return $this->db->query('SELECT d.id dia_id, d.nombre dia, (
				SELECT COALESCE(SUM(h.obligaciones),0)
				FROM horario h
				WHERE d.id=h.dia_id AND h.cargo_id = ?
			) + (
				SELECT COALESCE(SUM(h.obligaciones),0)
				FROM horario h
				JOIN cargo_horario ch ON h.id=ch.horario_id
				WHERE d.id=h.dia_id AND ch.cargo_id = ?
			) cantidad
		FROM dia d
		ORDER BY d.id', array($cargo_id, $cargo_id))->result();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('horario_id', $delete_id)->count_all_results('cargo_horario') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no tenga materias asignadas.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Horario_model.php */
/* Location: ./application/models/Horario_model.php */