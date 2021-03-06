<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Division_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'division';
		$this->msg_name = 'División';
		$this->id_name = 'id';
		$this->columnas = array('id', 'escuela_id', 'turno_id', 'curso_id', 'division', 'clave', 'carrera_id', 'fecha_alta', 'fecha_baja', 'modalidad_id', 'calendario_id');
		$this->fields = array(
			'escuela' => array('label' => 'Escuela', 'readonly' => TRUE),
			'turno' => array('label' => 'Turno', 'input_type' => 'combo', 'id_name' => 'turno_id', 'required' => TRUE),
			'curso' => array('label' => 'Curso', 'input_type' => 'combo', 'id_name' => 'curso_id', 'required' => TRUE),
			'division' => array('label' => 'División', 'maxlength' => '20', 'required' => TRUE),
			'carrera' => array('label' => 'Carrera', 'input_type' => 'combo', 'id_name' => 'carrera_id', 'required' => TRUE),
			'fecha_alta' => array('label' => 'Fecha de Alta', 'type' => 'date'),
			'fecha_baja' => array('label' => 'Fecha de Baja', 'type' => 'date', 'readonly' => TRUE),
			'modalidad' => array('label' => 'Modalidad', 'input_type' => 'combo', 'id_name' => 'modalidad_id', 'required' => TRUE),
			'calendario' => array('label' => 'Calendario', 'input_type' => 'combo', 'id_name' => 'calendario_id', 'required' => TRUE),
		);
		$this->requeridos = array('escuela_id', 'turno_id', 'curso_id', 'division', 'carrera_id', 'modalidad_id', 'calendario_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('curso', 'curso.id = division.curso_id', 'left', array('curso.descripcion as curso', 'curso.grado_multiple as grado_multiple', 'curso.calificacion_id as calificacion_id')),
			array('carrera', 'carrera.id = division.carrera_id', 'left', array('carrera.descripcion as carrera')),
			array('escuela', 'escuela.id = division.escuela_id', 'left', array('escuela.nombre as escuela', 'numero')),
			array('turno', 'turno.id = division.turno_id', 'left', array('turno.descripcion as turno')),
			array('modalidad', 'modalidad.id = division.modalidad_id', 'left', array('modalidad.descripcion as modalidad')),
			array('calendario', 'calendario.id = division.calendario_id', 'left', array('calendario.descripcion as calendario', 'calendario.nombre_periodo'))
		);
	}

	public function get_alumnos_transicion($division_id) {
		return $this->db->query("SELECT COUNT(1) AS cantidad
			FROM alumno_division
			WHERE division_id = ?
			AND ciclo_lectivo = 2017
			AND fecha_hasta IS NULL
			AND condicion!='Egreso no efectivo'", array($division_id))->row()->cantidad;
	}

	public function get_by_escuela($escuela_id) {
		$divisiones_db = $this->db->query("SELECT d.id, d.escuela_id, cu.descripcion curso, d.division, t.descripcion turno, ca.descripcion carrera, cd.cargos, cd.horas, ad.alumnos, ad.ciclo_lectivo
FROM division d
LEFT JOIN curso cu ON cu.id = d.curso_id
LEFT JOIN carrera ca ON ca.id = d.carrera_id
LEFT JOIN turno t ON t.id = d.turno_id
LEFT JOIN (
SELECT c.division_id, COUNT(c.id) cargos, SUM(c.carga_horaria) horas
FROM cargo c JOIN regimen r ON c.regimen_id = r.id AND r.planilla_modalidad_id = 1 join division d on c.division_id=d.id where d.escuela_id = ? AND c.fecha_hasta IS NULL
GROUP BY c.division_id) cd ON cd.division_id = d.id
LEFT JOIN (
SELECT ad.division_id, COUNT(DISTINCT CASE WHEN ad.condicion != 'Egreso no efectivo' THEN ad.alumno_id ELSE NULL END) alumnos, ad.ciclo_lectivo
FROM alumno_division ad join division d on ad.division_id = d.id where d.escuela_id = ? AND ad.fecha_hasta IS NULL 
GROUP BY ad.division_id, ad.ciclo_lectivo) ad ON ad.division_id = d.id
WHERE d.escuela_id = ? AND d.fecha_baja IS NULL 
ORDER BY cu.descripcion, d.division, ad.ciclo_lectivo", array($escuela_id, $escuela_id, $escuela_id)
			)->result();
		$divisiones = array();
		foreach ($divisiones_db as $division) {
			if (!isset($divisiones[$division->id])) {
				$divisiones[$division->id] = $division;
				$divisiones[$division->id]->alumnos_cl = array();
			}
			if (!empty($division->ciclo_lectivo)) {
				$divisiones[$division->id]->alumnos_cl[$division->ciclo_lectivo] = $division->alumnos;
			}
		}
		return $divisiones;
	}

	public function get_by_divisiones($escuela_id) {
		return $this->db->select('division.id, division.escuela_id, division.turno_id, division.curso_id, division.division, division.carrera_id, division.fecha_alta, division.fecha_baja, carrera.descripcion as carrera, curso.descripcion as curso, modalidad.descripcion as modalidad, escuela.nombre as escuela, turno.descripcion as turno, COUNT(horario.id) as horarios, division.clave')
				->from('division')
				->join('horario', 'horario.division_id = division.id', 'left')
				->join('carrera', 'carrera.id = division.carrera_id', 'left')
				->join('curso', 'curso.id = division.curso_id', 'left')
				->join('escuela', 'escuela.id = division.escuela_id', 'left')
				->join('turno', 'turno.id = division.turno_id', 'left')
				->join('modalidad', 'modalidad.id = division.modalidad_id', 'left')
				->where('division.escuela_id', $escuela_id)
				->where('division.fecha_baja IS NULL')
				->order_by('curso.descripcion, division.division')
				->group_by('division.id')
				->get()->result();
	}

	public function verificar_usuario_rol_docente($usuario_id, $division_id) {
		return $this->db->select('usuario.id, usuario.usuario, persona.cuil as cuil, CONCAT(persona.apellido, \', \', persona.nombre) as persona, rol.nombre as rol, entidad.nombre as entidad, (CASE usuario.active WHEN 1 THEN "Activo" ELSE "No Activo" END) as active, (CASE WHEN rol_docente.entidad_id IS NOT NULL THEN "Si" ELSE "No" END) as rol_asignado,rol_docente.entidad_id as division_asignada_id')
				->from('usuario')
				->join('usuario_persona', 'usuario_persona.usuario_id=usuario.id')
				->join('usuario_rol', 'usuario_rol.usuario_id=usuario.id', 'left')
				->join('usuario_rol rol_docente', 'rol_docente.usuario_id=usuario.id AND rol_docente.rol_id=5', 'left')
				->join('rol', 'usuario_rol.rol_id=rol.id', 'left')
				->join('entidad_tipo', 'rol.entidad_tipo_id=entidad_tipo.id', 'left')
				->join('entidad', 'entidad.tabla=entidad_tipo.tabla and entidad.id=usuario_rol.entidad_id', 'left')
				->join('persona', "persona.cuil = usuario_persona.cuil", 'left')
				->where('usuario.active', 1)
				->where('rol_docente.entidad_id', $division_id)
				->where('usuario.id', $usuario_id)
				->group_by('usuario.id')
				->get()->result();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('division_id', $delete_id)->count_all_results('alumno_division') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a alumno de division.');
			return FALSE;
		}
		if ($this->db->where('division_id', $delete_id)->count_all_results('cargo') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a cargo.');
			return FALSE;
		}
		if ($this->db->where('division_id', $delete_id)->count_all_results('cargo_historial') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a historial de cargo.');
			return FALSE;
		}
		if ($this->db->where('division_id', $delete_id)->count_all_results('cursada') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a cursada.');
			return FALSE;
		}
		if ($this->db->where('division_id', $delete_id)->count_all_results('horario') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no tenga horarios asociados.');
			return FALSE;
		}
		if ($this->db->where('division_id', $delete_id)->count_all_results('division_inasistencia') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no tenga asistencia de alumnos asociada.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Division_model.php */
/* Location: ./application/models/Division_model.php */