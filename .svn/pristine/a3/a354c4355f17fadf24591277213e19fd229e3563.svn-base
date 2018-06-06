<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Abono_escuela_monto_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'abono_escuela_monto';
		$this->msg_name = 'Transporte Escuela Montos';
		$this->id_name = 'id';
		$this->columnas = array('id', 'escuela_id', 'ames', 'abono_escuela_estado_id', 'monto', 'cupo_alumnos');
		$this->fields = array(
			'escuela' => array('label' => 'Escuela', 'input_type' => 'combo', 'id_name' => 'escuela_id', 'required' => TRUE),
			'ames' => array('label' => 'Período', 'type' => 'integer', 'maxlength' => '6', 'required' => TRUE),
			'abono_escuela_estado' => array('label' => 'Estado de Escuela', 'input_type' => 'combo', 'id_name' => 'abono_escuela_estado_id', 'required' => TRUE),
			'monto' => array('label' => 'Monto'),
			'cupo_alumnos' => array('label' => 'Cupo de Alumnos')
		);
		$this->requeridos = array('escuela_id', 'ames');
		//$this->unicos = array();
		$this->default_join = array(
			array('escuela', 'escuela.id = abono_escuela_monto.escuela_id', '', array('escuela.numero escuela')),
			array('abono_escuela_estado', 'abono_escuela_estado.id = abono_escuela_monto.abono_escuela_estado_id', '', array('abono_escuela_estado.descripcion abono_escuela_estado'))
		);
	}

public function get_escuela_mes($escuela_id, $ames) {
		return $this->db->select('monto,ames,escuela_id')
				->from('abono_escuela_monto aem')
				->where('aem.escuela_id', $escuela_id)
				->where('aem.ames', $ames)
				->get()->row();
	}

	protected function _can_delete($delete_id) {
		return TRUE;
	}
}
