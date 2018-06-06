<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cargo_historial_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'cargo_historial';
		$this->msg_name = 'Historial de cargo';
		$this->id_name = 'id';
		$this->columnas = array('id', 'cargo_id', 'division_id', 'turno_id', 'espacio_curricular_id', 'fecha_hasta', 'observaciones');
		$this->fields = array(
			'division' => array('label' => 'División', 'input_type' => 'combo', 'id_name' => 'division_id', 'class' => 'selectize-manual'),
			'carrera' => array('label' => 'Carrera', 'input_type' => 'combo', 'id_name' => 'carrera_id', 'class' => 'selectize-manual'),
			'espacio_curricular' => array('label' => 'Materia', 'input_type' => 'combo', 'id_name' => 'espacio_curricular_id', 'class' => 'selectize-manual'),
			'turno' => array('label' => 'Turno', 'input_type' => 'combo', 'id_name' => 'turno_id', 'class' => 'selectize-manual'),
			'fecha_hasta' => array('label' => 'Fecha Modificación', 'type' => 'date', 'required' => TRUE),
			'observaciones' => array('label' => 'Observaciones', 'maxlength' => '100')
		);
		$this->requeridos = array('cargo_id', 'fecha_hasta');
		//$this->unicos = array();
		$this->default_join = array(
			array('division', 'cargo.division_id = division.id', 'left', array('CONCAT(curso.descripcion, \' \', division.division) as division')),
			array('curso', 'curso.id = division.curso_id', 'left'),
			array('nivel', 'nivel.id = curso.nivel_id', 'left'),
			array('turno', 'turno.id = cargo.turno_id', 'left', array('turno.descripcion as turno')),
			array('espacio_curricular', 'cargo.espacio_curricular_id = espacio_curricular.id', 'left', array('materia.descripcion as espacio_curricular')),
			array('materia', 'espacio_curricular.materia_id = materia.id', 'left'),
			array('carrera', 'espacio_curricular.carrera_id = carrera.id', 'left', array('carrera.descripcion as carrera'))
		);
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
/* End of file Cargo_historial_model.php */
/* Location: ./application/models/Cargo_historial_model.php */