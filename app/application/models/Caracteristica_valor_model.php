<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Caracteristica_valor_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'caracteristica_valor';
		$this->msg_name = 'Valor de característica';
		$this->id_name = 'id';
		$this->columnas = array('id', 'caracteristica_id', 'valor');
		$this->fields = array(
			'caracteristica' => array('label' => 'Caracteristica', 'input_type' => 'combo', 'id_name' => 'caracteristica_id', 'required' => TRUE),
			'valor' => array('label' => 'Valor', 'maxlength' => '255', 'required' => TRUE)
		);
		$this->requeridos = array('caracteristica_id', 'valor');
		//$this->unicos = array();
		$this->default_join = array(
			array('caracteristica', 'caracteristica.id = caracteristica_valor.caracteristica_id', 'left', array('caracteristica.descripcion as caracteristica'))
		);
	}

	public function get_valor($id) {
		if (is_array($id)) {
			return $this->db->select('GROUP_CONCAT(id) id, GROUP_CONCAT(valor) valor')
					->from('caracteristica_valor')
					->where_in('id', $id)
					->get()
					->row();
		} else {
			return $this->db->select('id, valor')
					->from('caracteristica_valor')
					->where('id', $id)
					->get()
					->row();
		}
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('caracteristica_valor_id', $delete_id)->count_all_results('caracteristica_alumno') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a caracteristica de alumno.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Caracteristica_valor_model.php */
/* Location: ./application/models/Caracteristica_valor_model.php */