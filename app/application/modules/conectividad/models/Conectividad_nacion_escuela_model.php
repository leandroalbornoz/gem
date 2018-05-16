<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Conectividad_nacion_escuela_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'conectividad_nacion_escuela';
		$this->msg_name = 'Escuela Conectividad';
		$this->id_name = 'id';
		$this->columnas = array('id', 'conectividad_nacion_id', 'escuela_id', 'fecha_inicio', 'fecha_fin', 'instalador', 'celular_contacto');
		$this->fields = array(
			'conectividad_nacion' => array('label' => 'Relevamiento Conectividad', 'readonly' => TRUE),
			'escuela' => array('label' => 'Escuela', 'readonly' => TRUE),
			'fecha_inicio' => array('label' => 'Fecha de Inicio', 'type' => 'date', 'readonly' => TRUE),
			'fecha_fin' => array('label' => 'Fecha de Fin', 'type' => 'date', 'readonly' => TRUE),
			'instalador' => array('label' => 'Instalador', 'type' => 'integer', 'readonly' => TRUE),
			'celular_contacto' => array('label' => 'Celular de Contacto', 'maxlength' => '50', 'required' => TRUE)
		);
		$this->requeridos = array('conectividad_nacion_id', 'escuela_id', 'fecha_inicio', 'fecha_fin', 'instalador', 'celular_contacto');
		//$this->unicos = array();
		$this->default_join = array(
			array('conectividad_nacion', 'conectividad_nacion.id = conectividad_nacion_escuela.conectividad_nacion_id', 'left', array('conectividad_nacion.descripcion as conectividad_nacion', 'conectividad_nacion.fecha_desde', 'conectividad_nacion.fecha_hasta')),
			array('escuela', 'escuela.id = conectividad_nacion_escuela.escuela_id', 'left', array('escuela.nombre as escuela')),
			array("(SELECT conectividad_nacion_escuela_id, COUNT(1) cantidad FROM conectividad_nacion_encargado cnp GROUP BY conectividad_nacion_escuela_id) p", 'p.conectividad_nacion_escuela_id = conectividad_nacion_escuela.id', 'left', array("p.cantidad as encargados_asignados"))
		);
	}

	public function get_conectividad_escuela($conectividad_id, $escuela_id) {
		return $this->db
				->select("cn.id, cn.descripcion conectividad_nacion, cn.fecha_desde, cn.fecha_hasta, cne.id cne_id, cne.escuela_id, cne.fecha_inicio, cne.fecha_fin, cne.instalador, cne.celular_contacto, p.cantidad as encargados_asignados")
				->from('conectividad_nacion_escuela cne')
				->join('conectividad_nacion cn', 'cne.conectividad_nacion_id = cn.id')
				->join('escuela e', 'cne.escuela_id = e.id')
				->join('(SELECT conectividad_nacion_escuela_id, COUNT(1) cantidad FROM conectividad_nacion_encargado cnp GROUP BY conectividad_nacion_escuela_id) p', 'p.conectividad_nacion_escuela_id = cne.id', 'left')
				->where('cne.conectividad_nacion_id', $conectividad_id)
				->where('cne.escuela_id', $escuela_id)
				->get()->row();
	}

	public function get_encargados($cuil, $escuela_id) {
		return $this->db->select("MIN(s.id) servicio_id, p.id, p.nombre, p.apellido, p.cuil, p.documento, dt.descripcion_corta, r.descripcion regimen")
				->from('persona p')
				->join('servicio s', 's.persona_id = p.id AND s.fecha_baja is NULL')
				->join('documento_tipo dt', 'dt.id = p.documento_tipo_id')
				->join('cargo c', 'c.id = s.cargo_id AND c.fecha_hasta is NULL')
				->join('regimen r', 'r.id = c.regimen_id AND r.planilla_modalidad_id=1')
				->where('p.cuil', $cuil)
				->where('c.escuela_id', $escuela_id)
				->where('s.fecha_baja IS NULL')
				->order_by("p.id, r.codigo")
				->group_by("p.id, r.codigo")
				->get()->result();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('conectividad_nacion_escuela_id', $delete_id)->count_all_results('conectividad_nacion_encargado') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a conectividad de nacion de encargado.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Conectividad_nacion_escuela_model.php */
/* Location: ./application/modules/conectividad/models/Conectividad_nacion_escuela_model.php */