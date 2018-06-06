<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Beca_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'beca';
		$this->msg_name = 'Beca';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '45', 'required' => TRUE)
		);
		$this->requeridos = array('descripcion');
		//$this->unicos = array();
		$this->default_join = array();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('beca_id', $delete_id)->count_all_results('beca_etapa') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a beca de etapa.');
			return FALSE;
		}
		if ($this->db->where('beca_id', $delete_id)->count_all_results('beca_persona') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a beca de persona.');
			return FALSE;
		}
		return TRUE;
	}

	public function get_estadisticas($beca_id) {
		$estadisticas = array();
		$estadisticas['por_estado'] = $this->db->query(
				"SELECT e.descripcion estado, COUNT(distinct p.id) personas, count(distinct p.escuela_id) escuelas
				FROM gem.beca_persona p join beca_estado e on p.beca_estado_id=e.id
				WHERE p.beca_id=?
				GROUP BY e.id
				ORDER BY e.id", array($beca_id))->result();
		$estadisticas['por_fecha'] = $this->db->query(
				"SELECT DATE(p.fecha) fecha, COUNT(distinct p.id) personas
				FROM gem.beca_persona p join beca_estado e on p.beca_estado_id=e.id
				WHERE p.beca_id=?
				GROUP BY DATE(p.fecha)
				ORDER BY DATE(p.fecha)", array($beca_id))->result();
		return $estadisticas;
	}
}
/* End of file Beca_model.php */
/* Location: ./application/modules/becas/models/Beca_model.php */