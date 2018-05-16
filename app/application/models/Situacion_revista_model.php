<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Situacion_revista_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'situacion_revista';
		$this->msg_name = 'Situación de revista';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'planilla_tipo_id');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '255', 'required' => TRUE),
			'planilla_tipo' => array('label' => 'Tipo de planilla', 'input_type' => 'combo', 'required' => TRUE),
			
		);
		$this->requeridos = array('descripcion', 'planilla_tipo_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('planilla_tipo', 'planilla_tipo.id = situacion_revista.planilla_tipo_id', 'left', array('planilla_tipo.descripcion as planilla_tipo'))
		);
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('situacion_revista_id', $delete_id)->count_all_results('servicio') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a servicio.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Situacion_revista_model.php */
/* Location: ./application/models/Situacion_revista_model.php */