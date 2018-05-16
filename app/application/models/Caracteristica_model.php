<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Caracteristica_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'caracteristica';
		$this->msg_name = 'Caracteristica';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'caracteristica_tipo_id', 'valor_vacio', 'lista_valores', 'valor_multiple');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '100', 'required' => TRUE),
			'caracteristica_tipo' => array('label' => 'Tipo de característica', 'input_type' => 'combo', 'id_name' => 'caracteristica_tipo_id', 'required' => TRUE),
			'valor_vacio' => array('label' => 'Valor Vacío', 'required' => TRUE),
			'lista_valores' => array('label' => 'Lista de Valores', 'input_type' => 'combo', 'id_name' => 'lista_valores', 'required' => TRUE, 'array' => array('No' => 'No', 'Si' => 'Si')),
			'valor_multiple' => array('label' => 'Valor Múltiple', 'input_type' => 'combo', 'id_name' => 'valor_multiple', 'required' => TRUE, 'array' => array('No' => 'No', 'Si' => 'Si')),
			'niveles' => array('label' => 'Niveles', 'input_type' => 'combo', 'type' => 'multiple', 'id_name' => 'niveles')
		);
		$this->requeridos = array('descripcion', 'caracteristica_tipo_id', 'lista_valores');
		//$this->unicos = array();
		$this->default_join = array(
			array('caracteristica_tipo', 'caracteristica_tipo.id = caracteristica.caracteristica_tipo_id', 'left', array('caracteristica_tipo.descripcion as caracteristica_tipo', 'caracteristica_tipo.entidad', 'caracteristica_tipo.id as caracteristica_tipo_id'))
		);
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('caracteristica_id', $delete_id)->count_all_results('caracteristica_alumno') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no hayan alumnos con la característica cargada.');
			return FALSE;
		}
		if ($this->db->where('caracteristica_id', $delete_id)->count_all_results('caracteristica_escuela') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no hayan escuelas con la característica cargada.');
			return FALSE;
		}
		if ($this->db->where('caracteristica_id', $delete_id)->count_all_results('caracteristica_supervision') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no hayan supervisiones con la característica cargada.');
			return FALSE;
		}
		if ($this->db->where('caracteristica_id', $delete_id)->count_all_results('caracteristica_valor') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a caracteristica de valor.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Caracteristica_model.php */
/* Location: ./application/models/Caracteristica_model.php */