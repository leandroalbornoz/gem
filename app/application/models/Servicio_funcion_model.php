<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio_funcion_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'servicio_funcion';
		$this->msg_name = 'Funcion de servicio';
		$this->id_name = 'id';
		$this->columnas = array('id', 'servicio_id', 'funcion_id', 'destino', 'escuela_id', 'area_id', 'norma', 'tarea', 'carga_horaria', 'fecha_desde', 'fecha_hasta');
		$this->fields = array(
			'funcion' => array('label' => 'Función', 'input_type' => 'combo', 'id_name' => 'funcion_id'),
			'destino' => array('label' => 'Destino', 'input_type' => 'combo', 'id_name' => 'destino', 'array' => array('' => '-- Seleccione tipo de destino --')),
			'tipo_destino' => array('label' => 'Tipo destino', 'input_type' => 'combo', 'id_name' => 'tipo_destino', 'array' => array(
					'' => '-- Misma área/escuela --',
					'escuela' => 'Escuela',
					'area' => 'Área'
				)),
			'norma' => array('label' => 'Norma', 'maxlength' => '45'),
			'tarea' => array('label' => 'Tarea', 'input_type' => 'combo', 'id_name' => 'tarea', 'array' => array(
					'' => '-- Seleccione Tarea --',
					'TAREAS LIVIANAS' => 'TAREAS LIVIANAS',
					'SERENO' => 'SERENO',
					'COCINERO' => 'COCINERO',
					'SERVICIOS GENERALES' => 'SERVICIOS GENERALES',
					'ADMINISTRATIVO' => 'ADMINISTRATIVO',
					'CALDERISTA' => 'CALDERISTA',
					'OBRERO RURAL' => 'OBRERO RURAL',
					'MANTENIMIENTO' => 'MANTENIMIENTO'
				)),
			'carga_horaria' => array('label' => 'Carga Horaria', 'maxlength' => '45'),
			'fecha_desde' => array('label' => 'Fecha Desde', 'type' => 'date', 'required' => TRUE),
			'fecha_hasta' => array('label' => 'Fecha Hasta', 'type' => 'date')
		);
		$this->requeridos = array();
		//$this->unicos = array();
		$this->default_join = array(
			array('funcion', 'servicio_funcion.funcion_id=funcion.id', 'left', array('COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion'))
		);
	}

	public function get_funcion_activa($servicio_id) {
		return $this->db->select('id')
				->from('servicio_funcion')
				->where('fecha_hasta IS NULL')
				->where('servicio_id', $servicio_id)
				->get()->row();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('servicio_funcion_id', $delete_id)->count_all_results('servicio_funcion_horario') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no tenga horarios asociados.');
			return FALSE;
		}
		if ($this->db->where('servicio_funcion_id', $delete_id)->count_all_results('servicio_novedad') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no tenga novedades asociadas.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Servicio_funcion_model.php */
/* Location: ./application/models/Servicio_funcion_model.php */