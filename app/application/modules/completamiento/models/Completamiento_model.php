<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Completamiento_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_vista($escuela_id, $data) {
		$return['escuela'] = $data['escuela'];
		$return['administrar'] = $data['administrar'];
		$estadisticas_generales = $this->db->query(
				"SELECT 
COUNT(ad.id) total_alumnos,
COUNT(CASE WHEN ad.ciclo_lectivo>=2018 THEN ad.id ELSE NULL END) alumnos_con_cl,
COUNT(CASE WHEN (p.sexo_id IS NOT NULL AND p.fecha_nacimiento IS NOT NULL AND p.nombre IS NOT NULL AND p.apellido IS NOT NULL AND p.documento IS NOT NULL) THEN ad.id ELSE NULL END) as alumnos_con_datos,
COUNT(CASE WHEN ad.ciclo_lectivo>=2018 THEN ad.id ELSE NULL END)/
COUNT(ad.id) as porcentaje_con_cl,
COUNT(CASE WHEN (p.sexo_id IS NOT NULL AND p.fecha_nacimiento IS NOT NULL AND p.nombre IS NOT NULL AND p.apellido IS NOT NULL AND p.documento IS NOT NULL) THEN ad.id ELSE NULL END)/
COUNT(ad.id) as porcentaje_con_datos
FROM alumno_division ad
JOIN alumno a ON ad.alumno_id=a.id
JOIN persona p ON a.persona_id=p.id
JOIN division d ON ad.division_id=d.id
AND ad.fecha_hasta IS NULL
WHERE d.escuela_id=?", array($escuela_id)
			)->row();
		$return['estadisticas_generales'] = $estadisticas_generales;
		return $return;
	}
}
/* End of file Completamiento_model.php */
/* Location: ./application/modules/Completamiento/models/Completamiento_model.php */