<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Persona_titulo_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'persona_titulo';
		$this->auditoria = TRUE;
		$this->aud_table_name = 'aud_persona_titulo';
		$this->msg_name = 'Título de persona';
		$this->id_name = 'id';
		$this->columnas = array('id', 'titulo_id', 'titulo_tipo_id', 'persona_id', 'entidad_emisora_id', 'postitulo_tipo_id', 'posgrado_tipo_id', 'modalidad_id', 'fecha_emision', 'promedio', 'registro', 'norma_legal_tipo_id', 'norma_legal_numero', 'norma_legal_año', 'años_cursado', 'cantidad_hs_reloj', 'estado', 'borrado');
		$this->fields = array(
			'persona' => array('label' => 'Persona', 'readonly' => TRUE),
			'entidad_emisora' => array('label' => 'Entidad emisora', 'input_type' => 'combo', 'required' => TRUE, 'class' => 'input-selectize'),
			'postitulo_tipo' => array('label' => 'Tipo de postítulo', 'input_type' => 'combo', 'required' => TRUE),
			'posgrado_tipo' => array('label' => 'Tipo de posgrado', 'input_type' => 'combo', 'required' => TRUE),
			'titulo' => array('label' => 'Título', 'input_type' => 'combo', 'required' => TRUE, 'class' => 'no-selectize'),
			'modalidad' => array('label' => 'Modalidad', 'input_type' => 'combo', 'required' => TRUE),
			'fecha_emision' => array('label' => 'Fecha de Emisión (dd/mm/aaaa)', 'type' => 'date', 'required' => TRUE, 'placeholder' => 'dd/mm/aaaa'),
			'promedio' => array('label' => 'Promedio', 'type' => 'numeric'),
			'norma_legal_tipo' => array('label' => 'Tipo norma legal', 'input_type' => 'combo'),
			'norma_legal_numero' => array('label' => 'Norma legal número', 'type' => 'integer'),
			'norma_legal_año' => array('label' => 'Norma legal año', 'type' => 'integer'),
			'años_cursado' => array('label' => 'Años de cursado', 'type' => 'numeric'),
			'cantidad_hs_reloj' => array('label' => 'Horas reloj', 'type' => 'integer'),
			'registro' => array('label' => 'Registro Legajo', 'type' => 'text', 'maxlength' => '30', 'required' => TRUE),
		);
		$this->requeridos = array('titulo_id', 'fecha_emision', 'promedio');
		$this->default_join = array(
			array('persona', 'persona.id=persona_titulo.persona_id', 'left', array('CONCAT(cuil, \' \', persona.apellido, \', \', persona.nombre) as persona')),
			array('titulo', 'titulo.id=persona_titulo.titulo_id', 'left', array('NomTitLon as titulo')),
			array('modalidad', 'modalidad.id=persona_titulo.modalidad_id', 'left', array('modalidad.descripcion as modalidad')),
			array('entidad_emisora', 'entidad_emisora.id=persona_titulo.entidad_emisora_id', 'left', array('entidad as entidad_emisora')),
			array('table' => 'titulo_tipo', 'where' => 'persona_titulo.titulo_tipo_id= titulo_tipo.id', 'type' => 'left', 'columnas' => array('titulo_tipo.descripcion as titulo_tipo')),
			array('norma_legal_tipo', 'norma_legal_tipo.id=persona_titulo.norma_legal_tipo_id', 'left', array('norma_legal_tipo.descripcion as norma_legal_tipo')),
			array('postitulo_tipo', 'postitulo_tipo.id=persona_titulo.postitulo_tipo_id', 'left', array('postitulo_tipo.descripcion as postitulo_tipo')),
			array('posgrado_tipo', 'posgrado_tipo.id=persona_titulo.posgrado_tipo_id', 'left', array('posgrado_tipo.descripcion as posgrado_tipo'))
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
/* End of file Persona_titulo_model.php */
/* Location: ./application/modules/bono/models/Persona_titulo_model.php */