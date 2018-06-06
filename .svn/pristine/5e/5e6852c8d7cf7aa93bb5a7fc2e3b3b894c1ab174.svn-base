<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ingreso_alumno_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'ingreso_alumno';
		$this->msg_name = 'Ingreso alumno';
		$this->id_name = 'id';
		$this->columnas = array('id', 'alumno_id', 'alumno_division_id', 'hermano_ad_id', 'promedio', 'abanderado', 'participa', 'verificar', 'motivo_no_participa', 'asignado', 'fecha_cierre_postulacion', 'latitud', 'longitud', 'hash_cierre');
		$this->fields = array(
			'promedio' => array('label' => 'Promedio', 'type' => 'integer'),
			'abanderado' => array('label' => 'Abanderado', 'type' => 'integer'),
			'participa' => array('label' => 'Participa', 'type' => 'integer'),
			'verificar' => array('label' => 'Verificar', 'type' => 'integer'),
			'motivo_no_participa' => array('label' => 'Motivo no participa', 'type' => 'integer'),
			'asignado' => array('label' => 'Asignado', 'type' => 'integer'),
			'fecha_cierre_postulacion' => array('label' => 'Fecha cierre postulacion', 'type' => 'integer'),
			'latitud' => array('label' => 'Latitud', 'type' => 'integer'),
			'longitud' => array('label' => 'Longitud', 'type' => 'integer'),
			'hash_cierre' => array('label' => 'Hash cierre', 'type' => 'integer'),
		);
		$this->requeridos = array('alumno_id', 'alumno_division_id');
		//$this->unicos = array();
		$this->default_join = array(
		);
	}
}