<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Comedor_racion_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'comedor_racion';
		$this->msg_name = 'Comedor racion';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'type' => 'text', 'disabled' => TRUE),
		);
		$this->requeridos = array('descripcion');
		//$this->unicos = array();
		$this->default_join = array(
		);
	}

	public function get_raciones($mes) {
		$raciones_db = $this->db
				->select("cvr.id, cvr.comedor_racion_id, descripcion, monto")
				->from('comedor_racion cr')
				->join('comedor_valor_racion cvr', 'cr.id=cvr.comedor_racion_id')
				->where('cvr.mes', $mes)
				->order_by('cvr.comedor_racion_id')
				->get()->result();
		$raciones = array();
		foreach ($raciones_db as $racion) {
			$raciones[$racion->comedor_racion_id] = $racion;
		}
		return $raciones;
	}
}