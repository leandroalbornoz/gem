<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Preinscripcion_calendario_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'preinscripcion_calendario';
		$this->msg_name = 'Calendario de preinscripción';
		$this->id_name = 'id';
		$this->columnas = array('id', 'ciclo_lectivo', 'instancia', 'descripcion', 'desde', 'hasta');
		$this->requeridos = array('ciclo_lectivo', 'instancia', 'descripcion', 'desde', 'hasta');
		//$this->unicos = array();
		$this->default_join = array();
	}

	public function get_instancias($fecha = NULL) {
		if ($fecha === NULL) {
			$fecha = date('Y-m-d');
		}
		$this->db
			->select('*')
			->from('preinscripcion_calendario')
			->order_by('instancia');
		if ($fecha !== FALSE) {
			$this->db->where('desde <=', $fecha)
				->where('hasta >=', $fecha);
		}
		$instancias_db = $this->db->get()->result();
		$instancias = array();
		foreach ($instancias_db as $instancia) {
			$instancias[$instancia->instancia] = $instancia;
		}
		return $instancias;
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
/* End of file Preinscripcion_calendario_model.php */
/* Location: ./application/modules/preinscripciones/models/Preinscripcion_calendario_model.php */