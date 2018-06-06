<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio_funcion_horario_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'servicio_funcion_horario';
		$this->msg_name = 'Horario';
		$this->id_name = 'id';
		$this->columnas = array('id', 'servicio_funcion_id', 'dia_id', 'hora_desde', 'hora_hasta');
		$this->fields = array(
			'dia' => array('label' => 'Día', 'input_type' => 'combo', 'required' => TRUE),
			'hora_desde' => array('label' => 'Hora Desde', 'type' => 'time', 'required' => TRUE),
			'hora_hasta' => array('label' => 'Hora Hasta', 'type' => 'time', 'required' => TRUE)
		);
		$this->requeridos = array('servicio_funcion_id', 'dia_id', 'hora_desde', 'hora_hasta');
		//$this->unicos = array();
		$this->default_join = array(
			array('dia', 'dia.id=servicio_funcion_horario.dia_id', 'left', array('dia.nombre as dia'))
		);
	}

	public function get_horarios($servicio_funcion_id) {
		return $this->db->query('SELECT d.id dia_id, d.nombre dia, (SELECT COUNT(h.id) FROM servicio_funcion_horario h WHERE d.id=h.dia_id AND h.servicio_funcion_id = ?) cantidad
			 FROM dia d
			 ORDER BY d.id', array($servicio_funcion_id))->result();
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
/* End of file Servicio_funcion_horario_model.php */
/* Location: ./application/models/Servicio_funcion_horario_model.php */