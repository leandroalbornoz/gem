<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Anuncio_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'anuncio';
		$this->msg_name = 'Anuncio';
		$this->id_name = 'id';
		$this->columnas = array('id', 'fecha', 'titulo', 'texto');
		$this->fields = array(
			'fecha' => array('label' => 'Fecha', 'type' => 'date', 'required' => TRUE),
			'titulo' => array('label' => 'Titulo', 'maxlength' => '100', 'required' => TRUE),
			'texto' => array('label' => 'Texto', 'form_type' => 'textarea', 'required' => TRUE)
		);
		$this->requeridos = array('fecha', 'titulo', 'texto');
		//$this->unicos = array();
		$this->default_join = array();
	}

	public function get_anuncios() {
		return $this->db->select('a.id, a.fecha, a.texto, a.titulo')
			->from('anuncio a')
			->join('anuncio_usuario au', "au.anuncio_id=a.id AND au.usuario_id=$this->usuario", 'left')
			->where('au.id IS NULL')
			->order_by('a.fecha', 'DESC')
			->get()->result();
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
/* End of file Anuncio_model.php */
/* Location: ./application/models/Anuncio_model.php */