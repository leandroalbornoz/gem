<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Beca_etapa_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'beca_etapa';
		$this->msg_name = 'Etapa Beca';
		$this->id_name = 'id';
		$this->columnas = array('id', 'beca_id', 'descripcion', 'inicio', 'fin');
		$this->fields = array(
			'beca' => array('label' => 'Beca', 'input_type' => 'combo', 'id_name' => 'beca_id', 'required' => TRUE),
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '50', 'required' => TRUE),
			'inicio' => array('label' => 'Inicio', 'type' => 'date', 'required' => TRUE),
			'fin' => array('label' => 'Fin', 'type' => 'date', 'required' => TRUE)
		);
		$this->requeridos = array('beca_id', 'descripcion', 'inicio', 'fin');
		//$this->unicos = array();
		$this->default_join = array(
			array('beca', 'beca.id = beca_etapa.beca_id', 'left', array('beca.descripcion as beca')), );
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('beca_etapa_id', $delete_id)->count_all_results('beca_operacion') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a beca de operacion.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Beca_etapa_model.php */
/* Location: ./application/modules/becas/models/Beca_etapa_model.php */