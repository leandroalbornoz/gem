<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Division_inasistencia_dia_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'division_inasistencia_dia';
		$this->msg_name = 'Día Inasistencia';
		$this->id_name = 'id';
		$this->columnas = array('id', 'division_inasistencia_id', 'fecha', 'inasistencia_actividad_id', 'contraturno');
		$this->fields = array(
			'inasistencia_actividad' => array('label' => 'Actividad', 'input_type' => 'combo', 'id_name' => 'inasistencia_actividad_id', 'required' => TRUE),
			'contraturno' => array('label' => 'Contraturno en el Día', 'input_type' => 'combo', 'id_name' => 'contraturno', 'required' => TRUE),
		);
		$this->requeridos = array('division_inasistencia_id', 'inasistencia_actividad_id', 'contraturno');
		//$this->unicos = array();
		$this->default_join = array(
			array('inasistencia_actividad', 'inasistencia_actividad.id = division_inasistencia_dia.inasistencia_actividad_id', 'left', array('inasistencia_actividad.descripcion as inasistencia_actividad')),);
	}

	public function get_by_fecha($division_inasistencia_id, $fecha) {
		if (empty($fecha)) {
			return $this->db->from('division_inasistencia_dia')->where('division_inasistencia_id', $division_inasistencia_id)->where('fecha is NULL')->get()->row();
		} else {
			return $this->db->from('division_inasistencia_dia')->where('division_inasistencia_id', $division_inasistencia_id)->where('fecha', $fecha)->get()->row();
		}
	}

	public function get_dias($division_inasistencia_id) {
		$dias_db = $this->db->from('division_inasistencia_dia')->where('division_inasistencia_id', $division_inasistencia_id)->get()->result();
		$dias = array();
		if (!empty($dias_db)) {
			foreach ($dias_db as $dia) {
				$dias[$dia->fecha] = $dia;
			}
		}
		return $dias;
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('division_inasistencia_dia_id', $delete_id)->count_all_results('alumno_inasistencia') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a alumno de inasistencia.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Division_inasistencia_dia_model.php */
/* Location: ./application/models/Division_inasistencia_dia_model.php */