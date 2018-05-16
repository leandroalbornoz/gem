<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pregunta_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'pregunta';
		$this->msg_name = 'Pregunta';
		$this->id_name = 'id';
		$this->columnas = array('id', 'pregunta', 'respuesta', 'orden');
		$this->fields = array(
			'pregunta' => array('label' => 'Pregunta', 'form_type' => 'textarea', 'rows' => '4', 'required' => TRUE),
			'respuesta' => array('label' => 'Respuesta', 'form_type' => 'textarea', 'rows' => '4', 'required' => TRUE),
			'orden' => array('label' => 'Orden', 'type' => 'integer', 'required' => TRUE)
		);
		$this->requeridos = array('pregunta', 'respuesta');
		//$this->unicos = array();
		$this->default_join = array();
	}

	public function get_preguntas() {
		return $this->db->from('pregunta')->order_by('orden')->get()->result();
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
/* End of file Pregunta_model.php */
/* Location: ./application/models/Pregunta_model.php */