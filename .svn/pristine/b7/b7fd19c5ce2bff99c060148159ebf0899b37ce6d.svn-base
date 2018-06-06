<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Llamados_model extends CI_Model {

	public function get_llamados($environment) {
		if ($environment == 'prod') {
			$db = $this->db;
		} else {
			$db = $this->load->database('testing', TRUE);
		}
		$datos = $db->select("fecha_publicacion fecha_carga, tipo_llamado, condicion_cargo, regimen, horas, lugar_trabajo, direccion, localidad, departamento, regional, zona, fecha_llamado_1, fecha_llamado_2, fecha_llamado_3, fecha_llamado_4, articulo, fin_estimado, division, materia, turno, horario, presentarse_en, movilidad, prioridad, condiciones_adicionales, observaciones_adicionales, estado")
				->from('llamado')
				->where("estado IN ('Publicado', 'Cubierto', 'No Cubierto', 'Cancelado')")
				->get()->result();
		return $datos;
	}
}
