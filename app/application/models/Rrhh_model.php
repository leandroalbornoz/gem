<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rrhh_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = '';
		$this->msg_name = '';
		$this->id_name = '';
		$this->columnas = array();
		$this->fields = array();
		$this->requeridos = array();
		//$this->unicos = array();
		$this->default_join = array(
		);
	}

	public function get_escuela($escuela_id) {
		return $this->db->query(
				"SELECT escuela.id, escuela.numero, escuela.anexo, escuela.anexos, escuela.escuela_id, escuela.cue, escuela.subcue, escuela.nombre, CONCAT(escuela.numero,' - ', escuela.nombre) as nombre_largo,
			escuela.calle, escuela.calle_numero, escuela.departamento, escuela.piso, escuela.barrio, escuela.manzana, escuela.casa, escuela.secundaria_mixta, 
			escuela.supervision_id,escuela.numero_resolucion, escuela.telefono, escuela.email, escuela.email2, dependencia.descripcion as dependencia,
			CONCAT(departamento.descripcion, ' - ', localidad.descripcion) as localidad, departamento.descripcion departamento, 
			supervision.nombre as supervision, zona.descripcion as zona, zona.valor as zona_valor,
			group_concat(distinct turno.descripcion) as turno, count(distinct division.id) as divisiones, count(distinct alumno.id) as matricula, 
			anexos.divisiones_anexo, anexos.matricula_anexo
			FROM escuela
			LEFT JOIN dependencia ON dependencia.id = escuela.dependencia_id
			LEFT JOIN localidad ON localidad.id = escuela.localidad_id
			LEFT JOIN departamento ON departamento.id = localidad.departamento_id
			LEFT JOIN nivel ON nivel.id = escuela.nivel_id
			LEFT JOIN regimen_lista ON regimen_lista.id = escuela.regimen_lista_id
			LEFT JOIN supervision ON supervision.id = escuela.supervision_id
			LEFT JOIN zona ON zona.id = escuela.zona_id
			LEFT JOIN division ON division.escuela_id = escuela.id AND division.fecha_baja IS NULL
			LEFT JOIN turno ON turno.id = division.turno_id
			LEFT JOIN alumno_division ON alumno_division.division_id = division.id AND alumno_division.fecha_hasta IS NULL AND alumno_division.ciclo_lectivo >= 2017
			LEFT JOIN alumno ON alumno.id = alumno_division.alumno_id
			LEFT JOIN
			(
			SELECT escuela.id, escuela.escuela_id, count(distinct division.id) as divisiones_anexo, count(distinct alumno.id) as matricula_anexo
			FROM escuela
			LEFT JOIN division ON division.escuela_id = escuela.id AND division.fecha_baja IS NULL
			LEFT JOIN alumno_division ON alumno_division.division_id = division.id AND alumno_division.fecha_hasta IS NULL AND alumno_division.ciclo_lectivo >= 2017
			LEFT JOIN alumno ON alumno.id = alumno_division.alumno_id
			WHERE escuela.escuela_id = ?
			group by escuela.escuela_id
			) anexos ON anexos.escuela_id = escuela.id
			WHERE escuela.id = ? 
			GROUP BY escuela.id "
				, array($escuela_id, $escuela_id))->row();
	}

	public function get_planta_celadores($escuela_numero) {
		return $this->db->query(" 
							SELECT p.id as persona_id,e.anexo as anexo,s.id as servicio_id, e.id as escuela_id , CONCAT(p.apellido,', ',p.nombre) as persona, p.cuil, 
				p.fecha_nacimiento, ne.descripcion as nivel_estudio, c.id as cargo_id, c.carga_horaria , 
				sr.descripcion as situacion_revista, s.fecha_alta, cc.descripcion as celador_concepto, 
				CONCAT(pr.apellido, ', ', pr.nombre) as reemplazado, sf.tarea 
				FROM servicio s 
				JOIN cargo c ON s.cargo_id=c.id 
				JOIN regimen r ON c.regimen_id=r.id 
				JOIN escuela e ON c.escuela_id=e.id and e.numero = ?
				JOIN situacion_revista sr ON sr.id = s.situacion_revista_id
				JOIN persona p ON p.id = s.persona_id
				LEFT JOIN celador_concepto cc ON cc.id = s.celador_concepto_id
				LEFT JOIN servicio_funcion sf ON s.id = sf.servicio_id AND sf.fecha_hasta IS NULL
				LEFT JOIN servicio remp ON remp.id = s.reemplazado_id
				LEFT JOIN persona pr ON pr.id = remp.persona_id
				LEFT JOIN nivel_estudio ne ON p.nivel_estudio_id = ne.id  
				WHERE s.fecha_baja IS NULL AND r.codigo IN ('0560201','0560202','0560301','0560302','0540302') 
				GROUP BY s.id 
			", array($escuela_numero))->result();
	}

	public function get_celadores_tareas($tareas, $rol) {
		$query = "
				SELECT p.id as persona_id,s.id as servicio_id, e.id as escuela_id ,e.anexo as anexo, CONCAT(COALESCE(e.numero,''),' - ',COALESCE(e.nombre,'')) as escuela,departamento.descripcion as departamento, CONCAT(p.apellido,', ',p.nombre) as persona, p.cuil, 
		p.fecha_nacimiento, ne.descripcion as nivel_estudio, c.id as cargo_id, c.carga_horaria , 
		sr.descripcion as situacion_revista, s.fecha_alta, cc.descripcion as celador_concepto, 
		CONCAT(pr.apellido, ', ', pr.nombre) as reemplazado, sf.tarea 
		FROM servicio s 
		JOIN cargo c ON s.cargo_id=c.id 
		JOIN regimen r ON c.regimen_id=r.id 
		JOIN escuela e ON c.escuela_id=e.id
		JOIN situacion_revista sr ON sr.id = s.situacion_revista_id
		JOIN persona p ON p.id = s.persona_id
		LEFT JOIN celador_concepto cc ON cc.id = s.celador_concepto_id
		JOIN servicio_funcion sf ON s.id = sf.servicio_id AND sf.tarea IS NOT NULL AND sf.tarea IN ?
		LEFT JOIN servicio remp ON remp.id = s.reemplazado_id
		LEFT JOIN persona pr ON pr.id = remp.persona_id
		LEFT JOIN nivel_estudio ne ON p.nivel_estudio_id = ne.id
		LEFT JOIN localidad ON localidad.id = e.localidad_id
		LEFT JOIN departamento ON departamento.id = localidad.departamento_id 
	";
		switch ($rol->codigo) {
			case ROL_GRUPO_ESCUELA:
			case ROL_GRUPO_ESCUELA_CONSULTA:
				$query .= " LEFT JOIN escuela_grupo_escuela ege ON ege.escuela_id = e.id
									LEFT JOIN escuela_grupo eg ON ege.escuela_grupo_id = eg.id
									WHERE s.fecha_baja IS NULL AND r.codigo IN ('0560201','0560202','0560301','0560302','0540302') 
									AND eg.id = ? 
									GROUP BY s.id ";
				return $this->db->query($query, array($tareas, $rol->entidad_id))->result();
				break;
			case ROL_LINEA:
			case ROL_CONSULTA_LINEA:
				$query .= "
						LEFT JOIN nivel ON nivel.id = e.nivel_id
						WHERE s.fecha_baja IS NULL AND r.codigo IN ('0560201','0560202','0560301','0560302','0540302')
						AND nivel.linea_id = ?  
						GROUP BY s.id ";
				return $this->db->query($query, array($tareas, $rol->entidad_id))->result();
				break;
			case ROL_DIR_ESCUELA:
			case ROL_SDIR_ESCUELA:
			case ROL_SEC_ESCUELA:
				$query .= "
					WHERE s.fecha_baja IS NULL AND r.codigo IN ('0560201','0560202','0560301','0560302','0540302')
					AND e.id = ? 
					GROUP BY s.id ";
				return $this->db->query($query, array($tareas, $rol->entidad_id))->result();
				break;
			case ROL_SUPERVISION:
				$query .= "
						LEFT JOIN supervision ON supervision.id = e.supervision_id
						WHERE s.fecha_baja IS NULL AND r.codigo IN ('0560201','0560202','0560301','0560302','0540302')
						AND supervision.id = ? 
						GROUP BY s.id ";
				return $this->db->query($query, array($tareas, $rol->entidad_id))->result();
				break;
			case ROL_PRIVADA:
				$query .= "
					WHERE s.fecha_baja IS NULL AND r.codigo IN ('0560201','0560202','0560301','0560302','0540302')
					AND e.dependencia_id = 2 
					GROUP BY s.id ";
				return $this->db->query($query, array($tareas))->result();
				break;
			case ROL_SEOS:
				$query .= "
					WHERE s.fecha_baja IS NULL AND r.codigo IN ('0560201','0560202','0560301','0560302','0540302')
					AND e.dependencia_id = 3 
					GROUP BY s.id ";
				return $this->db->query($query, array($tareas))->result();
				break;
			case ROL_ADMIN:
			case ROL_USI:
			case ROL_JEFE_LIQUIDACION:
			case ROL_LIQUIDACION:
			case ROL_CONSULTA:
				$query .= "
					WHERE s.fecha_baja IS NULL AND r.codigo IN ('0560201','0560202','0560301','0560302','0540302') 
					GROUP BY s.id ";
				return $this->db->query($query, array($tareas))->result();
				break;
		}
	}

	public function get_caracteristicas_escuela($escuela_id) {
		return $this->db
				->select('e.id as escuela_id,ce.id as caracteristica_escuela_id, ct.descripcion as tipo, ce.caracteristica_id, c.descripcion, ce.valor')
				->from('escuela e')
				->join('caracteristica_escuela ce', 'ce.escuela_id = e.id')
				->join('caracteristica c', 'c.id = ce.caracteristica_id')
				->join('caracteristica_tipo ct', 'ct.id = c.caracteristica_tipo_id')
				->where('e.id', $escuela_id)
				->where('ce.fecha_hasta IS NULL')
				->where("c.id IN ('65','66','69','157','47','42','38','145')")
				->group_by('c.id')
				->get()->result();
	}

	public function get_detalles_anexos($escuela_id) {
		return $this->db
				->select('e.numero,e.id as escuela_id,count(distinct d.id) as divisiones, e.anexo, count(distinct a.id) as alumnos')
				->from('division d')
				->join('escuela e', 'd.escuela_id = e.id')
				->join('alumno_division ad', 'ad.division_id = d.id AND ad.ciclo_lectivo >= 2017 AND ad.fecha_hasta IS NULL', 'left')
				->join('alumno a', 'ad.alumno_id = a.id', 'left')
				->where("(e.id = $escuela_id OR e.escuela_id = $escuela_id) AND d.fecha_baja IS NULL")
				->group_by('e.anexo')
				->get()->result();
	}

	public function get_horarios_cargo($cargo_id, $escuela_numero) {

		return $this->db
				->select('cargo.id, horario.dia_id, dia.nombre as dia, hora_desde, hora_hasta')
				->from('horario ')
				->join('dia', 'dia.id = horario.dia_id')
				->join('cargo', 'cargo.id = horario.cargo_id')
				->join('escuela', 'escuela.id = cargo.escuela_id')
				->where('horario.cargo_id', $cargo_id)
				->where('escuela.numero', $escuela_numero)
				->get()->result();
	}

	public function get_horarios_celadores_escuela($escuela_numero) {

		return $this->db
				->select('cargo.id as cargo_id, horario.dia_id, dia.nombre as dia, hora_desde, hora_hasta')
				->from('horario ')
				->join('dia', 'dia.id = horario.dia_id')
				->join('cargo', 'cargo.id = horario.cargo_id')
				->join('escuela', 'escuela.id = cargo.escuela_id')
				->where('escuela.numero', $escuela_numero)
				->get()->result();
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
/* End of file Rrhh_model.php */
/* Location: ./application/models/Rrhh_model.php */

