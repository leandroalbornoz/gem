<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Titulo_persona_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'titulo_persona';
		$this->msg_name = 'Título de persona';
		$this->id_name = 'id';
		$this->columnas = array('id', 'persona_id', 'titulo_id', 'fecha_inscripcion', 'fecha_egreso', 'serie', 'numero','numero_titulo', 'observaciones');
		$this->fields = array(
			'persona' => array('label' => 'Persona', 'readonly' => TRUE),
			'titulo' => array('label' => 'Título', 'input_type' => 'combo', 'id_name' => 'titulo_id', 'required' => TRUE),
			'fecha_inscripcion' => array('label' => 'Fecha de Inscrip.', 'type' => 'date', 'required' => TRUE),
			'fecha_egreso' => array('label' => 'Fecha de Egreso', 'type' => 'date', 'required' => TRUE),
			'serie' => array('label' => 'Serie', 'type' => 'integer', 'maxlength' => '11', 'required' => TRUE),
			'numero' => array('label' => 'N° registro', 'type' => 'integer', 'maxlength' => '11', 'required' => TRUE),
			'numero_titulo' => array('label' => 'N° de Título', 'type' => 'integer', 'maxlength' => '11', 'required' => TRUE),
			'observaciones' => array('label' => 'Observaciones', 'maxlength' => '200')
		);
		$this->requeridos = array('persona_id', 'titulo_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('persona', 'persona.id = titulo_persona.persona_id', 'left', array("CONCAT(COALESCE(persona.cuil,persona.documento), ' - ', persona.apellido, ', ', persona.nombre) as persona")),
			array('titulo', 'titulo.id = titulo_persona.titulo_id', 'left', array('titulo.nombre as titulo'))
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

	public function getTitulosPersona($persona_id) {
		return $this->db->select('t.nombre,n.descripcion as pais_origen,tp.fecha_inscripcion,tp.fecha_egreso,tp.serie,tp.numero,tp.numero_titulo,tp.observaciones,tt.descripcion as tipo_titulo,te.descripcion as establecimiento')
				->from('titulo_persona tp')
				->join('titulo t', 't.id = tp.titulo_id', 'inner')
				->join('titulo_tipo tt', 'tt.id = t.titulo_tipo_id', 'inner')
				->join('titulo_establecimiento te', 'te.id = t.titulo_establecimiento_id', 'inner')
				->join('nacionalidad n', 'n.id = t.pais_origen', 'inner')
				->get()->row();
	}
}
/* End of file Titulo_persona_model.php */
/* Location: ./application/modules/titulos/models/Titulo_persona_model.php */