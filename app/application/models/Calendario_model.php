<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Calendario_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'calendario';
		$this->msg_name = 'Calendario';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'nombre_periodo');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '100'),
			'nombre_periodo' => array('label' => 'Nombre de Período', 'maxlength' => '30')
		);
		$this->requeridos = array();
		//$this->unicos = array();
		$this->default_join = array();
	}

	public function get_periodos($id, $ciclo_lectivo) {
		return $this->db
				->select('c.descripcion calendario, c.nombre_periodo, cp.periodo, cp.inicio, cp.fin')
				->from('calendario c')
				->join('calendario_periodo cp', 'c.id=cp.calendario_id')
				->where('c.id', $id)
				->where('cp.ciclo_lectivo', $ciclo_lectivo)
				->order_by('cp.periodo')
				->get()->result();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('calendario_id', $delete_id)->count_all_results('calendario_periodo') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a calendario de periodo.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Calendario_model.php */
/* Location: ./application/models/Calendario_model.php */