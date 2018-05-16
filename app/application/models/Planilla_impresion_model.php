<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Planilla_impresion_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'planilla_impresion';
		$this->msg_name = 'Impresión de Planilla';
		$this->id_name = 'id';
		$this->columnas = array('id', 'planilla_id', 'impresion');
		$this->requeridos = array('planilla_id', 'impresion');
		//$this->unicos = array();
	}

	public function get_impresion($planilla_id) {
		$planilla = $this->db->select('uncompress(impresion) impresion')
				->from('planilla_impresion')
				->where('planilla_id', $planilla_id)
				->get()->row();

		if (!empty($planilla)) {
			return $planilla->impresion;
		} else {
			return FALSE;
		}
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
/* End of file Planilla_impresion_model.php */
/* Location: ./application/models/Planilla_impresion_model.php */