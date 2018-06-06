<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escuela_grupo_escuela_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'escuela_grupo_escuela';
		$this->msg_name = 'Escuela de Grupo de escuela';
		$this->id_name = 'id';
		$this->columnas = array('id', 'escuela_grupo_id', 'escuela_id');
		$this->fields = array(
			'escuela_grupo' => array('label' => 'Grupo de escuela', 'input_type' => 'combo', 'id_name' => 'escuela_grupo_id'),
			'escuela' => array('label' => 'Escuela', 'input_type' => 'combo', 'id_name' => 'escuela_id', 'required' => TRUE)
		);
		$this->requeridos = array('escuela_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('escuela', 'escuela.id = escuela_grupo_escuela.escuela_id', 'left', array('escuela.nombre as escuela')), 
			array('escuela_grupo', 'escuela_grupo.id = escuela_grupo_escuela.escuela_grupo_id', 'left', array('escuela_grupo.descripcion as escuela_grupo')), );
	}
	
	function get_escuelas($escuela_grupo_id) {
		return $this->db->select('ege.id, eg.id as escuela_grupo_id, e.id as escuela_id, (CASE WHEN e.anexo=0 THEN e.numero ELSE CONCAT(e.numero,\'/\',e.anexo) END) as escuela_numero, e.nombre as escuela_nombre, CONCAT(e.numero, CASE WHEN e.anexo=0 THEN \' - \' ELSE CONCAT(\'/\',e.anexo,\' - \') END, e.nombre) as escuela')
			->from('escuela_grupo eg')
			->join('escuela_grupo_escuela ege','ege.escuela_grupo_id = eg.id','left')
			->join('escuela e','e.id = ege.escuela_id','left')
			->where('ege.escuela_grupo_id', $escuela_grupo_id)
			->get()->result();
	}
	
	function consulta($escuela_grupo_id, $escuela_id) {
		return $this->db->select('ege.id')
			->from('escuela_grupo eg')
			->join('escuela_grupo_escuela ege','ege.escuela_grupo_id = eg.id AND ege.escuela_id = '.$escuela_id.'','left')
			->where('ege.escuela_grupo_id', $escuela_grupo_id)
			->get()->row();
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
/* End of file Escuela_grupo_escuela_model.php */
/* Location: ./application/models/Escuela_grupo_escuela_model.php */