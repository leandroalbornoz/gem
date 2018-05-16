<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Conectividad_nacion_encargado_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'conectividad_nacion_encargado';
		$this->msg_name = 'Encargado Conectividad';
		$this->id_name = 'id';
		$this->columnas = array('id', 'conectividad_nacion_escuela_id', 'persona_id', 'servicio_id');
		$this->fields = array(
			'persona' => array('label' => 'Persona', 'readonly' => TRUE),
			'regimen' => array('label' => 'Régimen', 'readonly' => TRUE)
		);
		$this->requeridos = array('conectividad_nacion_escuela_id', 'persona_id', 'servicio_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('conectividad_nacion_escuela', 'conectividad_nacion_escuela.id = conectividad_nacion_encargado.conectividad_nacion_escuela_id', 'left', array('conectividad_nacion_escuela.escuela_id as conectividad_nacion_escuela')),
			array('persona', 'persona.id = conectividad_nacion_encargado.persona_id', '', array("CONCAT(COALESCE(persona.apellido,''),' , ', COALESCE(persona.nombre,'')) as persona", 'persona.cuil')),
			array('servicio', 'servicio.id = conectividad_nacion_encargado.servicio_id', '', array('servicio.persona_id as servicio')),
			array('cargo', 'cargo.id = servicio.cargo_id'),
			array('regimen', 'regimen.id = cargo.regimen_id', '', array('regimen.descripcion as regimen')),
		);
	}

	public function get_encargados_escuela($conectividad_id, $escuela_id) {
		return $this->db
				->select("cnp.id, cne.conectividad_nacion_id, cnp.persona_id, p.cuil, p.apellido, p.nombre, r.descripcion regimen")
				->from('conectividad_nacion_encargado cnp')
				->join('conectividad_nacion_escuela cne', 'cne.id = cnp.conectividad_nacion_escuela_id')
				->join('servicio s', 's.id = cnp.servicio_id')
				->join('persona p', 'p.id = s.persona_id')
				->join('cargo c', 'c.id = s.cargo_id')
				->join('regimen r', 'r.id = c.regimen_id')
				->where('cne.conectividad_nacion_id', $conectividad_id)
				->where('cne.escuela_id', $escuela_id)
				->order_by('cnp.audi_fecha')
//			 	->where('edp.estado', 'Activo')
				->get()->result();
	}

	public function get_encargado($persona_id) {
		return $this->db
				->select("CONCAT(COALESCE(p.apellido,''),' , ', COALESCE(p.nombre,'')) as nombre, p.cuil")
				->from('conectividad_nacion_encargado cnp')
				->join('servicio s', 's.id = cnp.servicio_id')
				->join('persona p', 'p.id = s.persona_id')
				->join('cargo c', 'c.id = s.cargo_id')
				->join('regimen r', 'r.id = c.regimen_id')
				->where('cnp.id', $persona_id)
				->get()->row();
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
/* End of file Conectividad_nacion_encargado_model.php */
/* Location: ./application/modules/conectividad/models/Conectividad_nacion_encargado_model.php */