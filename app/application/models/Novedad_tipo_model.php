<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Novedad_tipo_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'novedad_tipo';
		$this->msg_name = 'Tipo de novedad';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'articulo', 'inciso', 'descripcion_corta', 'concomitante', 'novedad', 'reemplazo');
		$this->fields = array(
			'articulo' => array('label' => 'Articulo', 'maxlength' => '2'),
			'inciso' => array('label' => 'Inciso', 'maxlength' => '2'),
			'descripcion_corta' => array('label' => 'Descripción', 'maxlength' => '60'),
			'descripcion' => array('label' => 'Detalle', 'maxlength' => '60'),
			'concomitante' => array('label' => 'Concomitante', 'input_type' => 'combo', 'id_name' => 'concomitante', 'array' => array('N' => 'No', 'S' => 'Si')),
			'novedad' => array('label' => 'Novedad', 'input_type' => 'combo', 'id_name' => 'novedad', 'array' => array('A' => 'Alta', 'N' => 'Novedad', 'B' => 'Baja', 'O' => 'Otro')),
			'reemplazo' => array('label' => 'Reemplazo', 'input_type' => 'combo', 'id_name' => 'reemplazo', 'array' => array('N' => 'No', 'S' => 'Si')),
		);
		$this->requeridos = array('articulo', 'inciso', 'descripcion', 'descripcion_corta', 'concomitante', 'novedad', 'reemplazo');
		//$this->unicos = array();
		$this->default_join = array();
	}

	// Operación: N: Novedad, A: Alta, R: Reemplazo, B: Baja
	public function get_tipos_novedades($operacion = 'N', $concomitantes = FALSE) {
		$where = array();
		switch ($operacion) {
			case 'N':
				$where = array("novedad_tipo.novedad='N'");
				break;
			case 'A':
				$where = array("novedad_tipo.novedad='A'");
				break;
			case 'B':
				$where = array("novedad_tipo.novedad='B'");
				break;
			case 'R':
				$where = array("novedad_tipo.reemplazo='S'");
				break;
		}
		$tipos_novedades = $this->get(array(
			'select' => array('novedad_tipo.id', 'CONCAT(novedad_tipo.articulo, \'-\', novedad_tipo.inciso, \' \', COALESCE(novedad_tipo.descripcion_corta,\'\')) as novedad', 'novedad_tipo.concomitante'),
			'where' => $where,
			'sort_by' => 'novedad_tipo.articulo, novedad_tipo.inciso'
		));
		$novedades_concomitantes = array();
		$array_novedad_tipo = array('' => '-- Seleccionar artículo --');
		foreach ($tipos_novedades as $tipo_novedad) {
			$array_novedad_tipo[$tipo_novedad->id] = $tipo_novedad->novedad;
			if ($tipo_novedad->concomitante === 'S') {
				$novedades_concomitantes[] = $tipo_novedad->id;
			}
		}
		if ($concomitantes) {
			return array($array_novedad_tipo, $novedades_concomitantes);
		} else {
			return $array_novedad_tipo;
		}
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('novedad_tipo_id', $delete_id)->count_all_results('servicio_novedad') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a servicio de novedad.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Novedad_tipo_model.php */
/* Location: ./application/models/Novedad_tipo_model.php */
