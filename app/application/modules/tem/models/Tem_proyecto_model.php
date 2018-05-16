<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tem_proyecto_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'tem_proyecto';
		$this->msg_name = 'Proyecto TEM';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'fecha_desde', 'fecha_hasta');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripcion', 'maxlength' => '50', 'required' => TRUE),
			'fecha_desde' => array('label' => 'Fecha de Desde', 'type' => 'date', 'required' => TRUE),
			'fecha_hasta' => array('label' => 'Fecha de Hasta', 'type' => 'date', 'required' => TRUE)
		);
		$this->requeridos = array('descripcion', 'fecha_desde', 'fecha_hasta');
		//$this->unicos = array();
		$this->default_join = array();
	}

	public function get_proyecto_mes($mes){
		return $this->db->query('SELECT tm.mes, tm.semanas, tp.descripcion, tp.fecha_desde, tp.fecha_hasta '
			. 'FROM tem_mes_semana tm '
			. 'JOIN tem_proyecto tp ON tm.tem_proyecto_id=tp.id '
			. 'WHERE tm.mes=?', array($mes))->row();
	}
	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('tem_proyecto_id', $delete_id)->count_all_results('tem_mes_semana') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a tem de mes de semana.');
			return FALSE;
		}
		if ($this->db->where('tem_proyecto_id', $delete_id)->count_all_results('tem_proyecto_escuela') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a tem de proyecto de escuela.');
			return FALSE;
		}
		return TRUE;
	}

	public function get_periodo_activo() {
		$date = date('Y-m-d');
		
		return $this->db->where('fecha_desde <=', $date)
			->where('fecha_hasta >=', $date)
			->from('tem_proyecto')
			->get()
			->row();
	}
}
/* End of file Tem_proyecto_model.php */
/* Location: ./application/modules/tem/models/Tem_proyecto_model.php */