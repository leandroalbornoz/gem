<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_estaticos_model extends MY_Model {

	public $tablas = array();

	public function __construct() {
		parent::__construct();
		$this->table_name = 'reporte_estatico';
		$this->msg_name = 'Reportes_estaticos';
		$this->id_name = 'id';
		$this->columnas = array();
		$this->fields = array(
		);
		$this->requeridos = array();
		//$this->unicos = array();
		$this->default_join = array();
	}

	public function get_reporte_asitencias_escuela($data) {
		$desde = $data['desde'];
		$hasta = $data['hasta'];
		$escuela = $data['escuela'];
		$supervision = $data['supervision'];

		if (!empty($data['reporte_carrera']) && $data['reporte_carrera']) {
			$carrera = $data['carrera'];
			$divisiones_riesgo = $data['divisiones_riesgo'];

			$query = " SELECT d.escuela_id,e.numero,e.nombre as escuela,car.descripcion as carrera, s.id as supervision_id, s.nombre as supervision,
count(d.id) as numero_divisiones,m.ames as mes,count(CASE WHEN rap.alumnos < ? THEN d.id ELSE NULL END) as divisiones_riesgo,
 COALESCE(sum(rap.alumnos), 0) alumnos,
 COALESCE(sum(rap.alumnos_ini),0) alumnos_ini,
 COALESCE(sum(rap.alumnos_fin),0) alumnos_fin,
 (COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0) - COALESCE(sum(rap.dias_falta), 0)) as asistencia_real, 
 (COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0)) as asistencia_ideal, 
 (COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0) - COALESCE(sum(rap.dias_falta), 0))/(COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0))*100 asistencia_media
FROM division d  ";
			if (!empty($supervision) && !empty($escuela)) {
				$query .= "	
								JOIN escuela e ON e.id=d.escuela_id AND e.id IN ?
								JOIN supervision s ON e.supervision_id = s.id AND s.id IN ? ";
			} elseif (!empty($supervision) && empty($escuela)) {
				$query .= "	
								JOIN escuela e ON e.id=d.escuela_id
								JOIN supervision s ON e.supervision_id = s.id AND s.id IN ? ";
			} elseif (empty($supervision) && !empty($escuela)) {
				$query .= " 
								JOIN escuela e ON e.id=d.escuela_id AND e.id IN ?
								JOIN supervision s ON e.supervision_id = s.id";
			} else {
				$query .= " 
								JOIN escuela e ON e.id=d.escuela_id
								JOIN supervision s ON e.supervision_id = s.id ";
			}
			$query .= " JOIN reporte_asistencia_parcial_mes rap ON rap.division_id=d.id ";
			if (!empty($carrera)) {
				$query .= " JOIN carrera car ON d.carrera_id = car.id and car.id IN ? ";
			} else {
				$query .= " JOIN carrera car ON d.carrera_id = car.id ";
			}
			$query .= "JOIN calendario ca ON ca.id = d.calendario_id
						JOIN planilla_asisnov_plazo m ON rap.mes=m.ames
						WHERE rap.mes BETWEEN ? AND ? 
						AND car.fecha_hasta IS NULL
						group by e.id, m.ames, car.id
						ORDER BY e.id,s.id,car.id,m.ames ASC ";
			if (!empty($carrera)) {
				if (!empty($supervision) && !empty($escuela))
					return $this->db->query($query, array($divisiones_riesgo, $escuela, $supervision, $carrera, $desde, $hasta))->result();
				elseif (!empty($supervision) && empty($escuela))
					return $this->db->query($query, array($divisiones_riesgo, $supervision, $carrera, $desde, $hasta))->result();
				elseif (empty($supervision) && !empty($escuela))
					return $this->db->query($query, array($divisiones_riesgo, $escuela, $carrera, $desde, $hasta))->result();
				else
					return $this->db->query($query, array($divisiones_riesgo, $carrera, $desde, $hasta))->result();
			}else {
				if (!empty($supervision) && !empty($escuela))
					return $this->db->query($query, array($divisiones_riesgo, $escuela, $supervision, $desde, $hasta))->result();
				elseif (!empty($supervision) && empty($escuela))
					return $this->db->query($query, array($divisiones_riesgo, $supervision, $desde, $hasta))->result();
				elseif (empty($supervision) && !empty($escuela))
					return $this->db->query($query, array($divisiones_riesgo, $escuela, $desde, $hasta))->result();
				else
					return $this->db->query($query, array($divisiones_riesgo, $desde, $hasta))->result();
			}
		} else {
			$query = " SELECT e.numero,e.nombre as escuela,s.id as supervision_id,COALESCE(s.nombre,'') as supervision,count(d.id) as numero_divisiones,
m.ames as mes,rap.periodo, ca.id as calendario_id ,ca.descripcion as calendario,
 COALESCE(sum(rap.alumnos), 0) alumnos,
 COALESCE(sum(rap.alumnos_ini),0) alumnos_ini,
 COALESCE(sum(rap.alumnos_fin),0) alumnos_fin,
 (COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0) - COALESCE(sum(rap.dias_falta), 0)) as asistencia_real, 
 (COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0)) as asistencia_ideal, 
 (COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0) - COALESCE(sum(rap.dias_falta), 0))/(COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0))*100 asistencia_media
			FROM division d  ";
			if (!empty($supervision) && !empty($escuela)) {
				$query .= " JOIN escuela e ON e.id=d.escuela_id AND e.id IN ? 
								JOIN supervision s ON e.supervision_id = s.id AND s.id IN ? ";
			} elseif (!empty($supervision) && empty($escuela)) {
				$query .= " JOIN escuela e ON e.id=d.escuela_id
								JOIN supervision s ON e.supervision_id = s.id AND s.id IN ? ";
			} elseif (empty($supervision) && !empty($escuela)) {
				$query .= " JOIN escuela e ON e.id=d.escuela_id AND e.id IN ? 
								JOIN supervision s ON e.supervision_id = s.id ";
			} else {
				$query .= " JOIN escuela e ON e.id=d.escuela_id 
								JOIN supervision s ON e.supervision_id = s.id ";
			}
			$query .= " JOIN reporte_asistencia_parcial_calendario rap ON rap.division_id=d.id
 JOIN calendario ca ON ca.id = d.calendario_id 
 JOIN calendario_periodo cp ON ca.id = cp.calendario_id AND rap.periodo=cp.periodo AND cp.ciclo_lectivo=left(rap.mes,4)
 JOIN planilla_asisnov_plazo m ON rap.mes=m.ames 
 WHERE m.ames BETWEEN ? AND ?
 group by e.id, m.ames,rap.periodo,ca.id, cp.id
 ORDER BY rap.mes,rap.periodo,e.numero, ca.id ASC";
			if (!empty($supervision) && !empty($escuela))
				return $this->db->query($query, array($escuela, $supervision, $desde, $hasta))->result();
			elseif (!empty($supervision) && empty($escuela))
				return $this->db->query($query, array($supervision, $desde, $hasta))->result();
			elseif (empty($supervision) && !empty($escuela))
				return $this->db->query($query, array($escuela, $desde, $hasta))->result();
			else
				return $this->db->query($query, array($desde, $hasta))->result();
		}
	}

	public function get_reporte_asitencias_supervision($data) {
		$desde = $data['desde'];
		$hasta = $data['hasta'];
		$supervision = $data['supervision'];

		if (!empty($data['reporte_carrera']) && $data['reporte_carrera']) {
			$divisiones_riesgo = $data['divisiones_riesgo'];
			$carrera = $data['carrera'];
			$query = "
				SELECT s.id,s.nombre as supervision,car.descripcion as carrera, count( distinct e.id) as numero_escuelas,count(distinct d.id) as numero_divisiones,
 m.ames mes,
 COUNT(CASE WHEN rap.alumnos < ? THEN d.id ELSE NULL END) as divisiones_riesgo,
 COALESCE(sum(rap.alumnos), 0) alumnos,
 COALESCE(sum(rap.alumnos_ini),0) alumnos_ini,
 COALESCE(sum(rap.alumnos_fin),0) alumnos_fin, 
 (COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0) - COALESCE(sum(rap.dias_falta), 0)) as asistencia_real,
 (COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0)) as asistencia_ideal, 
 (COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0) - COALESCE(sum(rap.dias_falta), 0))/(COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0))*100 asistencia_media
FROM division d 
JOIN escuela e ON e.id=d.escuela_id ";
			if (!empty($supervision)) {
				$query .= " JOIN supervision s ON e.supervision_id = s.id AND s.id IN ? ";
			} else {
				$query .= " JOIN supervision s ON e.supervision_id = s.id  ";
			}
			if (!empty($carrera)) {
				$query .= " JOIN carrera car ON d.carrera_id = car.id AND car.id IN ? ";
			} else {
				$query .= " JOIN carrera car ON d.carrera_id = car.id ";
			}
			$query .= "JOIN reporte_asistencia_parcial_mes rap ON rap.division_id=d.id 
				JOIN calendario ca ON ca.id = d.calendario_id 
				JOIN planilla_asisnov_plazo m ON rap.mes=m.ames 
				WHERE rap.mes BETWEEN ? AND ? 
				AND car.fecha_hasta IS NULL 
				group by s.id, m.ames, car.id 
				ORDER BY s.id,s.id,car.id,m.ames ASC";

			if (!empty($supervision) && !empty($carrera)) {
				return $this->db->query($query, array($divisiones_riesgo, $supervision, $carrera, $desde, $hasta))->result();
			} elseif (!empty($supervision) && empty($carrera)) {
				return $this->db->query($query, array($divisiones_riesgo, $supervision, $desde, $hasta))->result();
			} elseif (empty($supervision) && !empty($carrera)) {
				return $this->db->query($query, array($divisiones_riesgo, $carrera, $desde, $hasta))->result();
			} else {
				return $this->db->query($query, array($divisiones_riesgo, $desde, $hasta))->result();
			}
		} else {
			$query = "SELECT s.id as supervision_id,COALESCE(s.nombre,'') as supervision, count( distinct e.id) as numero_escuelas,count(d.id) as numero_divisiones, 
m.ames as mes,rap.periodo, ca.id as calendario_id ,ca.descripcion as calendario,
 COALESCE(sum(rap.alumnos), 0) alumnos,
 COALESCE(sum(rap.alumnos_ini),0) alumnos_ini,
 COALESCE(sum(rap.alumnos_fin),0) alumnos_fin, 
(COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0) - COALESCE(sum(rap.dias_falta), 0)) as asistencia_real,
 (COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0)) as asistencia_ideal, 
 (COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0) - COALESCE(sum(rap.dias_falta), 0))/(COALESCE(sum(rap.alumnos*rap.dias), 0) - COALESCE(sum(rap.dias_nc), 0))*100 asistencia_media
			FROM division d 
			JOIN escuela e ON e.id=d.escuela_id ";
			if (!empty($supervision)) {
				$query .= " JOIN supervision s ON e.supervision_id = s.id AND s.id IN ?";
			} else {
				$query .= " JOIN supervision s ON e.supervision_id = s.id ";
			}
			$query .= " JOIN reporte_asistencia_parcial_calendario rap ON rap.division_id=d.id
JOIN calendario ca ON ca.id = d.calendario_id 
JOIN calendario_periodo cp ON ca.id = cp.calendario_id AND rap.periodo=cp.periodo AND cp.ciclo_lectivo=left(rap.mes,4)
JOIN planilla_asisnov_plazo m ON rap.mes=m.ames 
WHERE m.ames BETWEEN ? AND ?
group by s.id, m.ames,rap.periodo,ca.id, cp.id 
ORDER BY rap.mes,rap.periodo,s.id, ca.id ASC ";
			if (!empty($supervision)) {
				return $this->db->query($query, array($supervision, $desde, $hasta))->result();
			} else {
				return $this->db->query($query, array($desde, $hasta))->result();
			}
		}
	}

	public function get_numero_escuelas($data) {
		$supervision = $data['supervision'];
		$escuela = $data['escuela'];
		$query = "SELECT count(distinct e.id) as valor 
				FROM reporte_asistencia_parcial_mes rap ";
		if (!empty($supervision)) {
			$query .= "JOIN escuela e ON rap.escuela_id=e.id
JOIN supervision s ON s.id= e.supervision_id and s.id IN ?";
			return $this->db->query($query, array($supervision))->row();
		} elseif (!empty($escuela)) {
			$query .= "JOIN escuela e ON rap.escuela_id=e.id AND e.id IN ? ";
			return $this->db->query($query, array($escuela))->row();
		} else {
			$query .= " JOIN escuela e ON rap.escuela_id=e.id ";
			return $this->db->query($query)->row();
		}
	}

	public function get_numero_supervisiones($data) {
		$escuela = $data['escuela'];
		$supervision = $data['supervision'];
		$query = "
			SELECT count(distinct s.id) as valor 
			FROM reporte_asistencia_parcial_mes rap ";
		if (!empty($escuela)) {
			$query .= " JOIN escuela e ON rap.escuela_id=e.id AND e.id IN ? 
JOIN supervision s ON s.id= e.supervision_id ";
			return $this->db->query($query, array($escuela))->row();
		} elseif (!empty($supervision)) {
			$query .= "JOIN escuela e ON rap.escuela_id=e.id 
JOIN supervision s ON s.id= e.supervision_id AND s.id IN ? ";
			return $this->db->query($query, array($supervision))->row();
		} else {
			$query .= "JOIN escuela e ON rap.escuela_id=e.id 
JOIN supervision s ON s.id= e.supervision_id";
			return $this->db->query($query)->row();
		}
	}

	public function reporte_oferta_educativa($data) {
		$departamentos = $data['departamento'];
		$query = $this->db
			->select("c.descripcion as carrera, n.descripcion as nivel,e.id as escuela_id, c.id as carrera_id,
					CONCAT(e.numero,' - ',e.nombre,(CASE WHEN (e.anexo = 0) THEN ''ELSE CONCAT('/', e.anexo)END)) AS escuela,dep.descripcion as departamento, loc.descripcion as localidad,e.codigo_postal,CONCAT(COALESCE(e.barrio,''),' ',COALESCE(e.calle,''),' ',COALESCE(e.calle_numero,''),' ',COALESCE(e.piso,'')) as direccion")
			->from('carrera c')
			->join('nivel n', 'n.id= c.nivel_id', 'left')
			->join('linea l', 'n.linea_id = l.id', 'left')
			->join('escuela_carrera ec', 'ec.carrera_id = c.id', 'left')
			->join('escuela e', 'e.id = ec.escuela_id', 'left')
			->join('localidad loc', 'loc.id = e.localidad_id', 'left')
			->join('departamento dep', 'dep.id = loc.departamento_id', 'left')
			->where('c.fecha_hasta IS NULL');
		if (!empty($departamentos)) {
			$query->where_in('dep.id', $departamentos);
		}
		if ($this->rol->codigo === 'linea') {
			$query->where('l.id', $this->rol->entidad_id);
		} else if ($this->rol->codigo === 'supervision') {
			$query->where('e.supervision_id', $this->rol->entidad_id);
		}
		$query->group_by('c.id');
		$reporte = $query->get()->result();
		$reporte_depto = array();
		foreach ($reporte as $data_reporte) {
			if (!empty($data_reporte->departamento) && !empty($data_reporte->localidad)) {
				$data['escuelas'][$data_reporte->escuela_id] = $data_reporte->escuela_id;
				$data['carreras'][$data_reporte->carrera_id] = $data_reporte->carrera_id;
				$reporte_depto[$data_reporte->departamento][$data_reporte->localidad][] = $data_reporte;
			} else if (!empty($data_reporte->localidad)) {
				$reporte_depto['FALTAN DATOS'][$data_reporte->localidad][] = $data_reporte;
			} else {
				$reporte_depto['FALTAN DATOS']['SIN LOCALIDAD'][] = $data_reporte;
			}
		}
		$data['reporte_depto'] = $reporte_depto;
		return $data;
	}
}