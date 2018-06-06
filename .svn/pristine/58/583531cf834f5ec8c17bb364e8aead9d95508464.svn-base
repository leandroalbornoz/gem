<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tem_mes_semana_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'tem_mes_semana';
		$this->msg_name = 'Mes/Semana TEM';
		$this->id_name = 'id';
		$this->columnas = array('id', 'tem_proyecto_id', 'mes', 'semanas');
		$this->fields = array(
			'tem_proyecto' => array('label' => 'Proyecto TEM', 'input_type' => 'combo', 'id_name' => 'tem_proyecto_id', 'required' => TRUE),
			'mes' => array('label' => 'Mes', 'type' => 'integer', 'maxlength' => '11', 'required' => TRUE),
			'semanas' => array('label' => 'Semanas', 'type' => 'integer', 'maxlength' => '11', 'required' => TRUE)
		);
		$this->requeridos = array('tem_proyecto_id', 'mes', 'semanas');
		//$this->unicos = array();
		$this->default_join = array(
			array('tem_proyecto', 'tem_proyecto.id = tem_mes_semana.tem_proyecto_id', 'left', array('tem_proyecto.descripcion as tem_proyecto')), );
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
/* End of file Tem_mes_semana_model.php */
/* Location: ./application/modules/tem/models/Tem_mes_semana_model.php */