<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Beca_operacion_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'beca_operacion';
		$this->msg_name = 'Operación Beca';
		$this->id_name = 'id';
		$this->columnas = array('id', 'beca_etapa_id', 'descripcion', 'beca_estado_o_id', 'beca_estado_d_id', 'cambia_escuela', 'cambia_validador');
		$this->fields = array(
			'beca_etapa' => array('label' => 'Etapa Beca', 'input_type' => 'combo', 'id_name' => 'beca_etapa_id', 'required' => TRUE),
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '50', 'required' => TRUE),
			'beca_estado_o' => array('label' => 'Estado Origen', 'input_type' => 'combo', 'id_name' => 'beca_estado_o_id', 'required' => TRUE),
			'beca_estado_d' => array('label' => 'Estado Destino', 'input_type' => 'combo', 'id_name' => 'beca_estado_d_id', 'required' => TRUE),
			'cambia_escuela' => array('label' => 'Cambiable Escuela', 'required' => TRUE),
			'cambia_validador' => array('label' => 'Cambiable Validador', 'required' => TRUE)
		);
		$this->requeridos = array('beca_etapa_id', 'descripcion', 'beca_estado_o_id', 'beca_estado_d_id', 'cambia_escuela', 'cambia_validador');
		//$this->unicos = array();
		$this->default_join = array(
			array('beca_estado', 'beca_estado.id = beca_operacion.beca_estado_d_id', 'left', array('beca_estado.descripcion as beca_estado_d')),
			array('beca_estado', 'beca_estado.id = beca_operacion.beca_estado_o_id', 'left', array('beca_estado.descripcion as beca_estado_o')),
			array('beca_etapa', 'beca_etapa.id = beca_operacion.beca_etapa_id', 'left', array('beca_etapa.beca_id as beca_etapa')),);
	}

	public function get_operaciones($beca_estado_id, $cambia = '') {
		$where = "";
		switch ($cambia) {
			case 'validador':
				$where .= "AND cambia_validador='Si'";
				break;
			case 'escuela':
				$where .= "AND cambia_escuela='Si'";
				break;
		}
		return $this->db->query("SELECT o.id, o.descripcion operacion, o.beca_estado_d_id, oe.descripcion beca_estado_o, de.descripcion beca_estado_d, o.cambia_escuela, o.cambia_validador, de.clase, de.icono
			FROM beca_operacion o
			JOIN beca_etapa e ON o.beca_etapa_id=e.id
			JOIN beca_estado oe ON o.beca_estado_o_id=oe.id
			JOIN beca_estado de ON o.beca_estado_d_id=de.id
			JOIN beca b ON e.beca_id=b.id
			WHERE CURDATE() BETWEEN e.inicio AND e.fin
			AND o.beca_estado_o_id=? $where
			ORDER BY o.id", array($beca_estado_id))->result();
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
/* End of file Beca_operacion_model.php */
/* Location: ./application/modules/becas/models/Beca_operacion_model.php */