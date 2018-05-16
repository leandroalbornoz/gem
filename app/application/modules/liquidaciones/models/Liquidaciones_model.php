<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Liquidaciones_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_escuela_auditoria($escuela_id) {
		$ames = (new DateTime(date('Ym') . '01 -1 month'))->format('Ym');
		if ($ames <= '201710') {
			return null;
		}
		return $this->db->select('c.escuela_id')
				->distinct()
				->from('servicio_novedad sn')
				->join('novedad_tipo nt', 'sn.novedad_tipo_id = nt.id')
				->join('servicio s', 'sn.servicio_id = s.id')
				->join('situacion_revista sr', 's.situacion_revista_id = sr.id')
				->join('cargo c', 's.cargo_id = c.id')
				->join('regimen r', 'c.regimen_id = r.id AND r.planilla_modalidad_id = 1')
				->join('escuela e', 'c.escuela_id = e.id')
				->where('e.dependencia_id', 1)
				->where('e.id', $escuela_id)
				->where('sr.planilla_tipo_id IN (1,2)')
				->where('sn.ames', $ames)
				->where("nt.novedad IN ('A','B')")
				->get()
				->row();
	}

	public function get_vista($escuela_id, $data) {
		$ames = (new DateTime(date('Ym') . '01 -1 month'))->format('Ym');
		$return['escuela'] = $data['escuela'];
		$return['administrar'] = $data['administrar'];
		$liquidaciones_db = $this->db->query("SELECT planilla, novedad, estado, count(1) cantidad
FROM
(SELECT sn.estado, nt.novedad novedad, pt.descripcion planilla
FROM servicio_novedad sn
JOIN novedad_tipo nt ON sn.novedad_tipo_id=nt.id
JOIN servicio s ON sn.servicio_id=s.id
JOIN situacion_revista sr ON s.situacion_revista_id = sr.id
JOIN planilla_tipo pt ON sr.planilla_tipo_id=pt.id
JOIN cargo c ON s.cargo_id = c.id
JOIN regimen r ON c.regimen_id = r.id AND r.planilla_modalidad_id=1
JOIN escuela e ON c.escuela_id = e.id
WHERE e.dependencia_id=1 AND e.id=? AND sr.planilla_tipo_id IN(1,2) AND sn.ames=? AND nt.novedad IN ('A','B') AND sn.planilla_baja_id IS NULL
GROUP BY s.id
) t
GROUP BY planilla, novedad, estado
ORDER BY planilla, novedad, estado", array($escuela_id, $ames))->result();
		$liquidaciones = array();
		foreach ($liquidaciones_db as $liquidacion_db) {
			$liquidaciones[$liquidacion_db->planilla][$liquidacion_db->novedad][$liquidacion_db->estado] = $liquidacion_db->cantidad;
		}
		$plazo = $this->db->where('ames', $ames)->get('planilla_asisnov_plazo')->row();
		$return['ames'] = $ames;
		$return['fecha_desde'] = $plazo->auditoria_desde;
		$return['fecha_hasta'] = $plazo->auditoria_hasta;
		$return['planillas'] = array('1' => 'Titulares', '2' => 'Reemplazos');
		$return['novedades'] = array('A' => 'Altas', 'B' => 'Bajas');
		$return['estados'] = array('Cargado', 'Auditado', 'Rechazado');
		$return['liquidaciones'] = $liquidaciones;
		return $return;
	}
}
/* End of file Elecciones_model.php */
/* Location: ./application/modules/elecciones/models/Elecciones_model.php */