<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Incidencia_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'incidencia';
		$this->msg_name = 'Incidencias';
		$this->id_name = 'id';
		$this->columnas = array('id', 'servicio_id', 'asunto', 'fecha', 'fecha_cierre', 'estado');
		$this->fields = array(
			'asunto' => array('label' => 'Asunto', 'maxlength' => '45', 'id_name' => 'asunto', 'required' => TRUE),
			'fecha' => array('label' => 'Fecha', 'type' => 'datetime', 'required' => TRUE),
			'estado' => array('label' => 'Estado', 'input_type' => 'combo', 'required' => TRUE, 'id_name' => 'estado', 'array' => array('Pendiente' => 'Pendiente', 'Cerrado' => 'Cerrado'))
		);
		$this->requeridos = array('asunto', 'fecha', 'estado');
		//$this->unicos = array();
		$this->default_join = array(
			array('servicio', 'servicio.id = incidencia.servicio_id', 'left', array('servicio.persona_id as servicio')),);
	}

	public function get_incidencias_servicio($servicio_id) {
		return $this->db->select('incidencia.id, servicio.id as servicio_id, incidencia.asunto, incidencia.estado, incidencia.fecha')
				->from('incidencia')
				->join('servicio', 'incidencia.servicio_id = servicio.id', 'left')
				->where('incidencia.servicio_id', $servicio_id)
				->order_by('incidencia.fecha', 'ASC')
				->get()->result();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('incidencia_id', $delete_id)->count_all_results('incidencia_detalle') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a incidencia de detalle.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Incidencia_model.php */
/* Location: ./application/models/Incidencia_model.php */