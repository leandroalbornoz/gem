<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Aprender_operativo_aplicador_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'aprender_operativo_aplicador';
		$this->msg_name = 'Aplicador de Operativo aprender';
		$this->id_name = 'id';
		$this->columnas = array('id', 'aprender_operativo_id', 'persona_id', 'estado', 'tipo_usuario');
		$this->fields = array(
			'aprender_operativo' => array('label' => 'Operativo Aprender', 'input_type' => 'combo', 'id_name' => 'aprender_operativo_id', 'required' => TRUE),
			'cuil' => array('label' => 'Cuil', 'input_type' => 'combo', 'id_name' => 'persona_id', 'required' => TRUE),
			'telefono' => array('label' => 'Teléfonos fijo/móvil', 'readonly' => TRUE),
			'nombre' => array('label' => 'Nombre', 'readonly' => TRUE),
			'email' => array('label' => 'Email', 'readonly' => TRUE)
		);
		$this->requeridos = array('aprender_operativo_id', 'persona_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('aprender_operativo', 'aprender_operativo.id = aprender_operativo_aplicador.aprender_operativo_id', 'left', array('aprender_operativo.id as aprender_operativo')),
			array('persona', 'persona.id = aprender_operativo_aplicador.persona_id', 'left', array('persona.cuil as cuil', 'persona.email as email', 'CONCAT(COALESCE(persona.telefono_fijo,\'SN\'),\' / \',COALESCE(persona.telefono_movil,\'SN\')) as telefono', 'CONCAT(COALESCE(persona.apellido,\'\'),\', \',COALESCE(persona.nombre,\'\')) as nombre')),);
	}

	public function get_aplicadores_escuela($escuela_id) {
		$aplicadores_db = $this->db
				->select("ap.id as operativo_persona_id, ao.escuela_id, ap.persona_id, p.nombre, p.apellido, p.cuil, p.email, p.telefono_fijo, p.telefono_movil, ao.operativo_tipo_id")
				->from('aprender_operativo_aplicador ap')
				->join('persona p', 'p.id = ap.persona_id', 'left')
				->join('aprender_operativo ao', 'ap.aprender_operativo_id = ao.id ', 'left')
				->join('escuela e', 'e.id = ao.escuela_id', 'left')
				->where('e.id', $escuela_id)
				->where('ap.estado', 'Activo')
				->get()->result();
		$aplicadores = array();
		if (!empty($aplicadores_db)) {
			foreach ($aplicadores_db as $aplicador) {
				$aplicadores[$aplicador->operativo_tipo_id][] = $aplicador;
			}
		}
		return $aplicadores;
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
/* End of file Aprender_operativo_aplicador_model.php */
/* Location: ./application/models/Aprender_operativo_aplicador_model.php */