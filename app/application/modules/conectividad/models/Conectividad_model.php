<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Conectividad_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_escuela_conectividad($escuela_id) {
		return $this->db->select('escuela_id')
				->from('conectividad_nacion_escuela')
				->where('escuela_id', $escuela_id)
				->get()
				->row();
	}

	public function get_vista($escuela_id, $data) {
		$return['escuela'] = $data['escuela'];
		$return['administrar'] = $data['administrar'];
		$instalaciones_db = $this->db->select('cn.id, cn.descripcion, cn.fecha_desde, cn.fecha_hasta, cne.fecha_inicio, cne.fecha_fin, cne.instalador, cne.celular_contacto')->from('conectividad_nacion cn')->join('conectividad_nacion_escuela cne', 'cn.id=cne.conectividad_nacion_id')->where('cne.escuela_id', $escuela_id)->order_by('cn.id desc')->get()->result();
		$instalaciones = array();
		foreach ($instalaciones_db as $instalacion_db) {
			$instalacion_db->encargados_asignados = 0;
			$instalacion_db->encargados_permitidos = 2;
			$instalaciones[$instalacion_db->id] = $instalacion_db;
		}
		$encargados_instalacion_db = $this->db->query("
				SELECT cne.conectividad_nacion_id, p.id, p.cuil, p.apellido, p.nombre, r.descripcion regimen
				FROM conectividad_nacion_encargado cnp
				JOIN conectividad_nacion_escuela cne ON cne.id = cnp.conectividad_nacion_escuela_id
				JOIN servicio s ON s.id = cnp.servicio_id
				JOIN cargo c ON c.id = s.cargo_id
				JOIN regimen r ON r.id = c.regimen_id
				JOIN persona p ON p.id = cnp.persona_id
				WHERE cne.escuela_id = ?
				", array($escuela_id)
			)->result();
		foreach ($encargados_instalacion_db as $encargado_instalacion_db) {
			$instalaciones[$encargado_instalacion_db->conectividad_nacion_id]->encargados[] = $encargado_instalacion_db;
			$instalaciones[$encargado_instalacion_db->conectividad_nacion_id]->encargados_asignados++;
		}
		$return['instalaciones'] = $instalaciones;
		return $return;
	}

	public function get_escuelas_conectividad($supervision_id) {
		return $this->db->select('escuela.id')
				->from('conectividad_nacion_escuela cne')
				->join('escuela', 'cne.escuela_id = escuela.id', 'left')
				->where('escuela.supervision_id', $supervision_id)
				->get()->result();
	}

	public function get_vista_supervision($supervision_id, $data) {
		$return['escuela'] = $data['supervision'];
		$return['administrar'] = $data['administrar'];
		$instalaciones = $this->db->select('cn.id, cne.id as conectividad_nacion_escuela_id, cn.descripcion, cn.fecha_desde, cn.fecha_hasta, cne.fecha_inicio, cne.fecha_fin, cne.instalador, cne.celular_contacto, CONCAT(escuela.numero, CASE WHEN escuela.anexo = 0 THEN \' - \' ELSE CONCAT(\'/\',escuela.anexo,\' - \') END, escuela.nombre) as nombre_escuela, escuela.id as escuela_id, COALESCE(p.cantidad,0) as encargados_asignados')
				->from('conectividad_nacion cn')
				->join('conectividad_nacion_escuela cne', 'cn.id=cne.conectividad_nacion_id')
				->join('escuela', 'cne.escuela_id = escuela.id')
				->join('(SELECT conectividad_nacion_escuela_id, COUNT(1) cantidad FROM conectividad_nacion_encargado cnp GROUP BY conectividad_nacion_escuela_id) p', 'p.conectividad_nacion_escuela_id = cne.id', 'left')
				->where('escuela.supervision_id', $supervision_id)
				->order_by('cn.id desc, escuela.numero, escuela.anexo')
				->get()->result();
		$return['instalaciones'] = $instalaciones;
		return $return;
	}
}
/* End of file Conectividad_model.php */
/* Location: ./application/modules/conectividad/models/Conectividad_model.php */