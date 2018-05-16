<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Incidencia_detalle_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'incidencia_detalle';
		$this->msg_name = 'Detalles de Incidencias';
		$this->id_name = 'id';
		$this->columnas = array('id', 'incidencia_id', 'detalle', 'fecha');
		$this->fields = array(
			'incidencia' => array('label' => 'Incidencias', 'input_type' => 'combo', 'id_name' => 'incidencia_id', 'required' => TRUE),
			'detalle' => array('label' => 'Detalle', 'maxlength' => '100'),
			'fecha' => array('label' => 'Fecha', 'type' => 'datetime')
		);
		$this->requeridos = array('incidencia_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('incidencia', 'incidencia.id = incidencia_detalle.incidencia_id', 'left', array('incidencia.id as incidencia')),
			array('usuario', 'usuario.id = incidencia_detalle.audi_user', 'left'),
			array('usuario_persona', 'usuario.id = usuario_persona.usuario_id', 'left'),
			array('persona', 'usuario_persona.cuil = persona.cuil', 'left', array("CONCAT(persona.apellido, ', ', persona.nombre) as usuario")),
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
/* End of file Incidencia_detalle_model.php */
/* Location: ./application/models/Incidencia_detalle_model.php */