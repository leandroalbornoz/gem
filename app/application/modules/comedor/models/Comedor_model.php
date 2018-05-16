<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Comedor_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_escuela_comedor($escuela_id) {
		return $this->db->select('MAX(comedor_presupuesto.id) id, MAX(comedor_presupuesto.mes) mes, GROUP_CONCAT(comedor_presupuesto.mes) meses, comedor_presupuesto.escuela_id')
				->from('comedor_presupuesto')
				->where('comedor_presupuesto.escuela_id', $escuela_id)
				->group_by('escuela_id')
				->get()
				->row();
	}

	public function get_vista($escuela_id, $data) {
		$comedor_presupuesto = $data['comedor_presupuesto'];
		$return['escuela'] = $data['escuela'];
		$return['meses'] = $data['meses'];
		$return['mes'] = $data['mes'];
		$return['comedor_presupuesto'] = $data['comedor_presupuesto'];
		$return['administrar'] = $data['administrar'];
		$return['comedor_mes'] = $comedor_presupuesto->mes;
		$return['comedor_mes_nombre'] = $data['comedor_mes_nombre'];
		$comedor_divisiones = $this->db->query(
				"SELECT d.id, cu.descripcion curso, d.division, t.descripcion turno,cp.mes as mes, COUNT(DISTINCT ad.alumno_id) alumnos, 
COUNT(DISTINCT CASE WHEN ca.comedor_racion_id=1 THEN  ad.alumno_id ELSE NULL END) alumnos_r1, 
COUNT(DISTINCT CASE WHEN ca.comedor_racion_id=2 THEN  ad.alumno_id ELSE NULL END) alumnos_r2,
COUNT(DISTINCT CASE WHEN ca.comedor_racion_id IS NULL THEN  ad.alumno_id ELSE NULL END) alumnos_sr
FROM division d
JOIN curso cu ON cu.id = d.curso_id
LEFT JOIN turno t ON t.id = d.turno_id
LEFT JOIN alumno_division ad ON ad.division_id=d.id
LEFT JOIN comedor_alumno ca ON ad.id=ca.alumno_division_id
LEFT JOIN comedor_presupuesto cp ON cp.id=ca.comedor_presupuesto_id
WHERE cp.id = ? AND d.fecha_baja IS NULL AND (ad.fecha_hasta IS NULL OR DATE_FORMAT(ad.fecha_hasta,'%Y%m') >= ?) AND ad.ciclo_lectivo = ?
GROUP BY d.id,cp.mes
ORDER BY cu.descripcion, d.division", array($comedor_presupuesto->id, $data['ames'], substr($data['ames'], 0, 4))
			)->result();
		$return['comedor_divisiones'] = $comedor_divisiones;
		return $return;
	}
}
/* End of file Comedor_model.php */
/* Location: ./application/modules/comedor/models/Comedor_model.php */


