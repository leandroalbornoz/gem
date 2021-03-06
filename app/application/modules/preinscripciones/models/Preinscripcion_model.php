<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Preinscripcion_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'preinscripcion';
		$this->msg_name = 'Preinscripción a Escuela';
		$this->id_name = 'id';
		$this->columnas = array('id', 'escuela_id', 'preinscripcion_operativo_id', 'turno_id', 'vacantes', 'fecha_carga');
		$this->fields = array(
			'escuela' => array('label' => 'Escuela', 'readonly' => TRUE),
			'vacantes' => array('label' => 'Vacantes', 'type' => 'number', 'min' => '1', 'required' => TRUE),
		);
		$this->requeridos = array('escuela_id', 'vacantes', 'fecha_carga');
		//$this->unicos = array();
		$this->default_join = array(
			array('escuela', 'escuela.id = preinscripcion.escuela_id', 'left', array("CONCAT(escuela.numero, ' - ', escuela.nombre) as escuela")),
			array('preinscripcion_operativo', 'preinscripcion_operativo.id = preinscripcion.preinscripcion_operativo_id', '', array('preinscripcion_operativo.ciclo_lectivo as ciclo_lectivo')),
		);
	}

	public function modulo_escuela($escuela, $data) {
		$modulos = array();
		// Preinscripción Sala de 4, Sala de 5 - 2019
		if (ENVIRONMENT !== 'production' && $escuela->dependencia_id === '1' && in_array($escuela->nivel_id, array('2', '8'))) {
			$this->load->model('preinscripciones/preinscripcion_calendario_model');
			$data['preinscripcion_instancias'] = $this->preinscripcion_calendario_model->get_instancias(FALSE);
			$this->load->model('preinscripciones/preinscripcion_model');
			$modulos[] = (object) array(
					'type' => 'modulos',
					'name' => 'escuela_preinscripcion_2019',
					'view' => 'preinscripciones/escritorio_escuela_modulo_preinscripciones',
					'data' => $this->get_vista($escuela->id, 2019, $data, TRUE));
		}
		// Preinscripción 1° Grado - 2018
		if ($escuela->dependencia_id === '1' && $escuela->nivel_id === '2') {
			$this->load->model('preinscripciones/preinscripcion_calendario_model');
			$data['preinscripcion_instancias'] = $this->preinscripcion_calendario_model->get_instancias(FALSE);
			$modulos[] = (object) array(
					'type' => 'modulos_inactivos',
					'name' => 'escuela_preinscripcion_2018',
					'view' => 'preinscripciones/escritorio_escuela_modulo_preinscripciones',
					'data' => $this->get_vista($escuela->id, 2018, $data, FALSE));
		}
		return $modulos;
	}

	public function modulo_supervision($supervision, $data) {
		$modulos = array();
		$escuelas_preinscripcion = $this->preinscripcion_model->get_escuelas_preinscripcion($supervision->id);
		if (!empty($escuelas_preinscripcion)) {
			// Preinscripción Sala de 4, Sala de 5 - 2019
			if (ENVIRONMENT !== 'production' && $supervision->dependencia_id === '1' && in_array($supervision->nivel_id, array('2', '8'))) {
				$this->load->model('preinscripciones/preinscripcion_calendario_model');
				$data['preinscripcion_instancias'] = $this->preinscripcion_calendario_model->get_instancias(FALSE);
				$this->load->model('preinscripciones/preinscripcion_model');
				$modulos[] = (object) array(
						'type' => 'modulos',
						'name' => 'escuela_preinscripcion_2019',
						'view' => 'preinscripciones/escritorio_supervision_modulo_preinscripciones',
						'data' => $this->get_vista_supervision($supervision->id, 2019, $data, TRUE));
			}
			// Preinscripción 1° Grado - 2018
			if ($supervision->dependencia_id === '1' && $supervision->nivel_id === '2') {
				$this->load->model('preinscripciones/preinscripcion_calendario_model');
				$data['preinscripcion_instancias'] = $this->preinscripcion_calendario_model->get_instancias(FALSE);
				$modulos[] = (object) array(
						'type' => 'modulos_inactivos',
						'name' => 'escuela_preinscripcion_2018',
						'view' => 'preinscripciones/escritorio_supervision_modulo_preinscripciones',
						'data' => $this->get_vista_supervision($supervision->id, 2018, $data, FALSE));
			}
			return $modulos;
		}
	}

	public function modulo_linea($nivel, $data) {
		$modulos = array();
		$data['nivel'] = $nivel;
		// Preinscripción Sala de 4, Sala de 5 - 2019
		if (ENVIRONMENT !== 'production' && in_array($nivel->id, array('2', '8'))) {
			$this->load->model('preinscripciones/preinscripcion_calendario_model');
			$data['preinscripcion_instancias'] = $this->preinscripcion_calendario_model->get_instancias(FALSE);
			$escuelas = $this->get_by_nivel($nivel->id, 2019);
			$modulos[] = (object) array(
					'type' => "modulos_$nivel->id",
					'name' => 'escuela_preinscripcion_2019',
					'view' => 'preinscripciones/escritorio_linea_modulo_preinscripciones',
					'data' => $this->get_vista_linea($escuelas, 2019, $data, TRUE));
		}
		// Preinscripción 1° Grado - 2018
		if ($nivel->id === '2') {
			$this->load->model('preinscripciones/preinscripcion_calendario_model');
			$data['preinscripcion_instancias'] = $this->preinscripcion_calendario_model->get_instancias(FALSE);
			$escuelas = $this->get_by_nivel($nivel->id, 2018);
			$modulos[] = (object) array(
					'type' => "modulos_inactivos_$nivel->id",
					'name' => 'escuela_preinscripcion_2018',
					'view' => 'preinscripciones/escritorio_linea_modulo_preinscripciones',
					'data' => $this->get_vista_linea($escuelas, 2018, $data, FALSE));
		}
		return $modulos;
	}

	public function get_preinscripcion($id) {
		return $this->db
				->select("p.id, p.escuela_id, p.vacantes, po.ciclo_lectivo,po.id as preinscripcion_operativo_id, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Inscripto' THEN 1 ELSE 0 END), 0) inscriptos, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Postulante' THEN 1 ELSE 0 END), 0) postulantes, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Derivado' THEN 1 ELSE 0 END), 0) derivados, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 1 THEN 1 ELSE 0 END), 0) instancia_1, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 1 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_1_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2 THEN 1 ELSE 0 END), 0) instancia_2, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2  AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_2_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2 AND pa.estado = 'Postulante' THEN 1 ELSE 0 END), 0) instancia_2_p, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2 AND pa.estado = 'Derivado' THEN 1 ELSE 0 END), 0) instancia_2_d, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 THEN 1 ELSE 0 END), 0) instancia_3, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_3_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 AND pa.estado = 'Postulante' THEN 1 ELSE 0 END), 0) instancia_3_p, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 AND pa.estado = 'Derivado' THEN 1 ELSE 0 END), 0) instancia_3_d, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 THEN 1 ELSE 0 END), 0) instancia_4,"
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_4_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 AND pa.estado = 'Postulante' THEN 1 ELSE 0 END), 0) instancia_4_p, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 AND pa.estado = 'Derivado' THEN 1 ELSE 0 END), 0) instancia_4_d, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 5 THEN 1 ELSE 0 END), 0) instancia_5, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 5 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_5_i")
				->from('preinscripcion p')
				->join('preinscripcion_operativo po', 'po.id = p.preinscripcion_operativo_id')
				->join('preinscripcion_alumno pa', "pa.preinscripcion_id=p.id", 'left')
				->join('preinscripcion_tipo pt', "pt.preinscripcion_operativo_id = po.id AND pt.id= pa.preinscripcion_tipo_id", 'left')
				->where('p.id', $id)
				->group_by('p.id')
				->get()->row();
	}

	public function get_preinscripcion_tipos_instancia($preinscripcion_operativo_id, $instancia) {
		return $this->db->select('pt.id,pt.descripcion as preinscripcion_tipo,pt.instancia,pt.preinscripcion_operativo_id')
				->from('preinscripcion_tipo pt')
				->where('pt.instancia', $instancia)
				->where('pt.preinscripcion_operativo_id', $preinscripcion_operativo_id)
				->get()->result();
	}

	public function get_instancia($tipo_id) {
		return $this->db->select('pt.id,pt.descripcion as preinscripcion_tipo,pt.instancia,pt.preinscripcion_operativo_id')
				->from('preinscripcion_tipo pt')
				->where('pt.id', $tipo_id)
				->get()->row();
	}

	public function get_postulantes_escuelas($id) {
		return $this->db
				->select("p.id, p.escuela_id, p.vacantes, po.ciclo_lectivo, esc.numero, esc.nombre")
				->from('preinscripcion p')
				->join('preinscripcion_operativo po', 'po.id = p.preinscripcion_operativo_id')
				->join('preinscripcion_alumno pa', "pa.preinscripcion_id=p.id AND estado='Postulante'")
				->join('escuela esc', 'p.escuela_id = esc.id', 'left')
				->where('pa.estado !=', 'Anulado')
				->group_by('p.escuela_id')
				->get()->result();
	}

	public function get_by_escuela($escuela_id, $ciclo_lectivo) {
		return $this->db
				->select("p.id, p.escuela_id, p.vacantes, po.ciclo_lectivo, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Inscripto' THEN 1 ELSE 0 END), 0) inscriptos, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Postulante' THEN 1 ELSE 0 END), 0) postulantes, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Derivado' THEN 1 ELSE 0 END), 0) derivados, "
					. "COALESCE(SUM(CASE WHEN pa.preinscripcion_tipo_id <= 3 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_1, "
					. "COALESCE(SUM(CASE WHEN pa.preinscripcion_tipo_id = 4  AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_2_i, "
					. "COALESCE(SUM(CASE WHEN pa.preinscripcion_tipo_id = 4 AND pa.estado = 'Postulante' THEN 1 ELSE 0 END), 0) instancia_2_p, "
					. "COALESCE(SUM(CASE WHEN pa.preinscripcion_tipo_id = 5 AND pa.estado = 'Derivado' THEN 1 ELSE 0 END), 0) instancia_3_d, "
					. "COALESCE(SUM(CASE WHEN pa.preinscripcion_tipo_id = 5 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_3_i, "
					. "COALESCE(SUM(CASE WHEN pa.preinscripcion_tipo_id = 5 THEN 1 ELSE 0 END), 0) instancia_3, "
					. "COALESCE(SUM(CASE WHEN pa.preinscripcion_tipo_id = 6 THEN 1 ELSE 0 END), 0) instancia_4")
				->from('preinscripcion p')
				->join('preinscripcion_operativo po', 'po.id = p.preinscripcion_operativo_id')
				->join('preinscripcion_alumno pa', 'pa.preinscripcion_id=p.id AND pa.estado !=\'Anulado\' ', 'left')
				->where('p.escuela_id', $escuela_id)
				->where('po.ciclo_lectivo', $ciclo_lectivo)
				->group_by('p.id')
				->get()->row();
	}

	public function get_escuelas_preinscripcion($supervision_id) {
		return $this->db->select('e.id')
				->from('escuela e')
				->join('supervision s', 'e.supervision_id = s.id')
				->where('s.id', $supervision_id)
				->where('e.nivel_id=s.nivel_id AND e.dependencia_id=s.dependencia_id')
				->order_by('e.numero, e.anexo')
				->group_by('e.id')
				->get()->result();
	}

	public function get_vista($escuela_id, $ciclo_lectivo, $data, $activo = TRUE) {
		$return['preinscripcion_instancias'] = $data['preinscripcion_instancias'];
		$return['escuela'] = $data['escuela'];
		$return['administrar'] = $data['administrar'];
		$preinscripciones = $this->db->select("p.id, p.escuela_id, p.vacantes, po.ciclo_lectivo, po.id as preinscripcion_operativo_id, po.descripcion as po_descripcion, t.descripcion turno, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Inscripto' THEN 1 ELSE 0 END), 0) inscriptos, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Postulante' THEN 1 ELSE 0 END), 0) postulantes, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Derivado' THEN 1 ELSE 0 END), 0) derivados, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 1 THEN 1 ELSE 0 END), 0) instancia_1, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 1 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_1_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2 THEN 1 ELSE 0 END), 0) instancia_2, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2  AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_2_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2 AND pa.estado = 'Postulante' THEN 1 ELSE 0 END), 0) instancia_2_p, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2 AND pa.estado = 'Derivado' THEN 1 ELSE 0 END), 0) instancia_2_d, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 THEN 1 ELSE 0 END), 0) instancia_3, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_3_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 AND pa.estado = 'Postulante' THEN 1 ELSE 0 END), 0) instancia_3_p, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 AND pa.estado = 'Derivado' THEN 1 ELSE 0 END), 0) instancia_3_d, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 THEN 1 ELSE 0 END), 0) instancia_4,"
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_4_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 AND pa.estado = 'Postulante' THEN 1 ELSE 0 END), 0) instancia_4_p, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 AND pa.estado = 'Derivado' THEN 1 ELSE 0 END), 0) instancia_4_d, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 5 THEN 1 ELSE 0 END), 0) instancia_5, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 5 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_5_i")
				->from('preinscripcion p')
				->join('preinscripcion_operativo po', 'po.id = p.preinscripcion_operativo_id')
				->join('turno t', 'p.turno_id = t.id', 'left')
				->join('preinscripcion_alumno pa', "pa.preinscripcion_id=p.id AND pa.estado !='Anulado'", 'left')
				->join('preinscripcion_tipo pt', "pt.id=pa.preinscripcion_tipo_id AND pt.preinscripcion_operativo_id = po.id", 'left')
				->where('p.escuela_id', $escuela_id)
				->where('po.ciclo_lectivo', $ciclo_lectivo)
				->group_by('p.id')
				->get()->result();
		if (empty($preinscripciones)) {
			return FALSE;
		}
		$preinscripciones_array = array();
		foreach ($preinscripciones as $preinscripcion) {
			if (empty($preinscripciones_array[$preinscripcion->preinscripcion_operativo_id])) {
				$preinscripciones_array[$preinscripcion->preinscripcion_operativo_id] = new stdClass();
				$preinscripciones_array[$preinscripcion->preinscripcion_operativo_id]->escuela_id = $preinscripcion->escuela_id;
				$preinscripciones_array[$preinscripcion->preinscripcion_operativo_id]->ciclo_lectivo = $preinscripcion->ciclo_lectivo;
				$preinscripciones_array[$preinscripcion->preinscripcion_operativo_id]->po_descripcion = $preinscripcion->po_descripcion;
				$preinscripciones_array[$preinscripcion->preinscripcion_operativo_id]->preinscripcion_operativo_id = $preinscripcion->preinscripcion_operativo_id;
				$preinscripciones_array[$preinscripcion->preinscripcion_operativo_id]->turnos = array();
			}
			$preinscripciones_array[$preinscripcion->preinscripcion_operativo_id]->turnos[$preinscripcion->turno] = $preinscripcion;
		}
		$return['ciclo_lectivo'] = $ciclo_lectivo;
		$return['preinscripciones'] = $preinscripciones;
		$return['modulo_activo'] = $activo;
		$return['preinscripciones_array'] = $preinscripciones_array;
		return $return;
	}

	public function get_vista_supervision($supervision_id, $ciclo_lectivo, $data, $activo = TRUE) {
		$return['preinscripcion_instancias'] = $data['preinscripcion_instancias'];
		$return['supervision'] = $data['supervision'];
		$return['administrar'] = $data['administrar'];
		$escuelas_p = $this->db->select("p.preinscripcion_operativo_id, e.id, e.numero, e.anexo, e.nombre, t.descripcion turno, COUNT(DISTINCT d.id) divisiones, COUNT(DISTINCT ad.alumno_id) alumnos, p.id as preinscripcion_id, p.vacantes,"
					. "pa.inscriptos, pa.postulantes,pa.derivados, "
					. "pa.instancia_1,pa.instancia_1_i,"
					. "pa.instancia_2,pa.instancia_2_i,pa.instancia_2_p,pa.instancia_2_d,"
					. "pa.instancia_3, pa.instancia_3_i,pa.instancia_3_p,pa.instancia_3_d,"
					. "pa.instancia_4,pa.instancia_4_i,pa.instancia_4_p,pa.instancia_4_d,"
					. "pa.instancia_5,pa.instancia_5_i,"
					. "po.id as preinscripcion_operativo_id,"
					. "po.descripcion as po_descripcion")
				->from('escuela e')
				->join('preinscripcion p', 'p.escuela_id = e.id')
				->join('turno t', 'p.turno_id = t.id', 'left')
				->join('preinscripcion_operativo po', 'po.id = p.preinscripcion_operativo_id')
				->join('supervision s', 'e.supervision_id = s.id')
				->join('division d', 'd.escuela_id = e.id AND d.turno_id=t.id AND ((po.id = 1 AND d.curso_id = 7) OR (po.id = 2 AND (d.curso_id = 21 OR d.curso_id = 65)) OR (po.id = 3 AND (d.curso_id = 20 OR d.curso_id = 64)))', 'left')
				->join('alumno_division ad', 'ad.division_id=d.id AND ad.fecha_hasta IS NULL', 'left')
				->join("(SELECT p.turno_id, p.preinscripcion_operativo_id, p.escuela_id, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Inscripto' THEN 1 ELSE 0 END), 0) inscriptos, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Postulante' THEN 1 ELSE 0 END), 0) postulantes, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Derivado' THEN 1 ELSE 0 END), 0) derivados, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 1 THEN 1 ELSE 0 END), 0) instancia_1, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 1 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_1_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2 THEN 1 ELSE 0 END), 0) instancia_2, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2  AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_2_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2 AND pa.estado = 'Postulante' THEN 1 ELSE 0 END), 0) instancia_2_p, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2 AND pa.estado = 'Derivado' THEN 1 ELSE 0 END), 0) instancia_2_d, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 THEN 1 ELSE 0 END), 0) instancia_3, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_3_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 AND pa.estado = 'Postulante' THEN 1 ELSE 0 END), 0) instancia_3_p, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 AND pa.estado = 'Derivado' THEN 1 ELSE 0 END), 0) instancia_3_d, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 THEN 1 ELSE 0 END), 0) instancia_4,"
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_4_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 AND pa.estado = 'Postulante' THEN 1 ELSE 0 END), 0) instancia_4_p, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 AND pa.estado = 'Derivado' THEN 1 ELSE 0 END), 0) instancia_4_d, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 5 THEN 1 ELSE 0 END), 0) instancia_5, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 5 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_5_i "
					. "FROM preinscripcion p "
					. "JOIN preinscripcion_operativo po ON po.id = p.preinscripcion_operativo_id "
					. "LEFT JOIN preinscripcion_alumno pa ON p.id = pa.preinscripcion_id AND pa.estado !='Anulado' "
					. "LEFT JOIN preinscripcion_tipo pt ON pa.preinscripcion_tipo_id = pt.id AND pt.preinscripcion_operativo_id = po.id "
					. "GROUP BY p.preinscripcion_operativo_id, p.escuela_id, p.turno_id) pa", 'pa.escuela_id = e.id AND pa.preinscripcion_operativo_id=po.id AND pa.turno_id=p.turno_id', 'left')
				->where('s.id', $supervision_id)
				->where('po.ciclo_lectivo', $ciclo_lectivo)
				->where('e.nivel_id=s.nivel_id AND e.dependencia_id=s.dependencia_id')
				->order_by('po.id, e.numero, e.anexo, p.turno_id')
				->group_by('e.id, po.id, p.turno_id')
				->get()->result();
		$preinscripciones = array();
		foreach ($escuelas_p as $escuela_p) {
			$preinscripciones[$escuela_p->preinscripcion_operativo_id][] = $escuela_p;
		}
		$return['cursos'] = array('1' => '1° Grado', '2' => 'Sala 4', '3' => 'Sala 5');
		$return['preinscripciones'] = $preinscripciones;
		$return['modulo_activo'] = $activo;
		$return['ciclo_lectivo'] = $ciclo_lectivo;
		return $return;
	}

	public function get_vista_linea($preinscripciones, $ciclo_lectivo, $data, $activo = TRUE) {
		if (empty($preinscripciones)) {
			return FALSE;
		}
		$return['cursos'] = array('1' => '1° Grado', '2' => 'Sala 4', '3' => 'Sala 5');
		$return['nivel'] = $data['nivel'];
		$return['preinscripcion_instancias'] = $data['preinscripcion_instancias'];
		$return['ciclo_lectivo'] = $ciclo_lectivo;
		$return['preinscripciones'] = $preinscripciones;
		$return['modulo_activo'] = $activo;
		return $return;
	}

	public function get_by_supervision($supervision_id) {
		return $this->db
				->select('e.id, e.numero, e.anexo, e.nombre, COUNT(DISTINCT d.id) divisiones, COUNT(DISTINCT ad.id) alumnos, p.id as preinscripcion_id, p.vacantes, pa.inscriptos, pa.postulantes, pa.instancia_1, pa.instancia_2, pa.instancia_3, pa.instancia_2_p, pa.instancia_2_i, pa.instancia_3_d, pa.instancia_4')
				->from('escuela e')
				->join('supervision s', 'e.supervision_id = s.id')
				->join('division d', 'd.escuela_id = e.id AND d.curso_id=7', 'left')
				->join('alumno_division ad', 'ad.division_id=d.id AND ad.fecha_hasta IS NULL', 'left')
				->join('preinscripcion p', 'p.escuela_id = e.id', 'left')
				->join("(SELECT p.escuela_id, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Inscripto' THEN 1 ELSE 0 END), 0) inscriptos, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Postulante' THEN 1 ELSE 0 END), 0) postulantes, "
					. "COALESCE(SUM(CASE WHEN pa.preinscripcion_tipo_id <= 3 AND pa.estado !='Anulado' THEN 1 ELSE 0 END), 0) instancia_1, "
					. "COALESCE(SUM(CASE WHEN pa.preinscripcion_tipo_id = 4  AND pa.estado !='Anulado' THEN 1 ELSE 0 END), 0) instancia_2, "
					. "COALESCE(SUM(CASE WHEN pa.preinscripcion_tipo_id = 4 AND pa.estado = 'Postulante' THEN 1 ELSE 0 END), 0) instancia_2_p, "
					. "COALESCE(SUM(CASE WHEN pa.preinscripcion_tipo_id = 4 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_2_i, "
					. "COALESCE(SUM(CASE WHEN pa.preinscripcion_tipo_id = 5 AND pa.estado = 'Derivado' THEN 1 ELSE 0 END), 0) instancia_3_d, "
					. "COALESCE(SUM(CASE WHEN pa.preinscripcion_tipo_id = 5 AND pa.estado !='Anulado' THEN 1 ELSE 0 END), 0) instancia_3, "
					. "COALESCE(SUM(CASE WHEN pa.preinscripcion_tipo_id = 6 AND pa.estado !='Anulado' THEN 1 ELSE 0 END), 0) instancia_4 "
					. "FROM preinscripcion p JOIN preinscripcion_alumno pa ON p.id = pa.preinscripcion_id "
					. "GROUP BY p.escuela_id) pa", 'pa.escuela_id = e.id', 'left')
				->where('s.id', $supervision_id)
				->where('e.nivel_id=s.nivel_id AND e.dependencia_id=s.dependencia_id')
				->order_by('e.numero, e.anexo')
				->group_by('e.id')
				->get()->result();
	}

	public function get_by_nivel($nivel_id, $ciclo_lectivo) {
		$supervisiones_p = $this->db
				->select("pr.preinscripcion_operativo_id, s.id, s.orden numero, s.nombre, COUNT(DISTINCT e.id) escuelas, p.escuelas_p, COUNT(DISTINCT d.id) divisiones, COUNT(DISTINCT ad.alumno_id) alumnos, pr.id as preinscripcion_id, p.vacantes,"
					. "pa.inscriptos, pa.postulantes, pa.derivados, "
					. "pa.instancia_1,pa.instancia_1_i,"
					. "pa.instancia_2,pa.instancia_2_i,pa.instancia_2_p,pa.instancia_2_d,"
					. "pa.instancia_3, pa.instancia_3_i,pa.instancia_3_p,pa.instancia_3_d,"
					. "pa.instancia_4,pa.instancia_4_i,pa.instancia_4_p,pa.instancia_4_d,"
					. "pa.instancia_5,pa.instancia_5_i,"
					. "po.id as preinscripcion_operativo_id,"
					. "po.descripcion as po_descripcion")
				->from('escuela e')
				->join('preinscripcion pr', 'pr.escuela_id = e.id')
				->join('preinscripcion_operativo po', 'po.id = pr.preinscripcion_operativo_id')
				->join('supervision s', 'e.supervision_id = s.id', 'left')
				->join('division d', 'd.escuela_id = e.id AND ((po.id = 1 AND d.curso_id = 7) OR (po.id = 2 AND (d.curso_id = 21 OR d.curso_id = 65)) OR (po.id = 3 AND (d.curso_id = 20 OR d.curso_id = 64)))', 'left')
				->join('alumno_division ad', 'ad.division_id=d.id AND ad.fecha_hasta IS NULL', 'left')
				->join("(SELECT e.supervision_id, COUNT(DISTINCT e.id) escuelas_p, SUM(p.vacantes) vacantes "
					. "FROM preinscripcion p JOIN escuela e ON p.escuela_id = e.id JOIN preinscripcion_operativo po ON p.preinscripcion_operativo_id=po.id AND po.ciclo_lectivo = $ciclo_lectivo "
					. "GROUP BY e.supervision_id) p", 'p.supervision_id = s.id', 'left')
				->join("(SELECT e.supervision_id, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Inscripto' THEN 1 ELSE 0 END), 0) inscriptos, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Postulante' THEN 1 ELSE 0 END), 0) postulantes, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Derivado' THEN 1 ELSE 0 END), 0) derivados, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 1 THEN 1 ELSE 0 END), 0) instancia_1, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 1 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_1_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2 THEN 1 ELSE 0 END), 0) instancia_2, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2  AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_2_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2 AND pa.estado = 'Postulante' THEN 1 ELSE 0 END), 0) instancia_2_p, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 2 AND pa.estado = 'Derivado' THEN 1 ELSE 0 END), 0) instancia_2_d, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 THEN 1 ELSE 0 END), 0) instancia_3, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_3_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 AND pa.estado = 'Postulante' THEN 1 ELSE 0 END), 0) instancia_3_p, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 3 AND pa.estado = 'Derivado' THEN 1 ELSE 0 END), 0) instancia_3_d, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 THEN 1 ELSE 0 END), 0) instancia_4,"
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_4_i, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 AND pa.estado = 'Postulante' THEN 1 ELSE 0 END), 0) instancia_4_p, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 4 AND pa.estado = 'Derivado' THEN 1 ELSE 0 END), 0) instancia_4_d, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 5 THEN 1 ELSE 0 END), 0) instancia_5, "
					. "COALESCE(SUM(CASE WHEN pt.instancia = 5 AND pa.estado = 'Inscripto' THEN 1 ELSE 0 END), 0) instancia_5_i "
					. "FROM preinscripcion p JOIN preinscripcion_alumno pa ON p.id = pa.preinscripcion_id AND pa.estado!='Anulado' JOIN escuela e ON p.escuela_id = e.id JOIN preinscripcion_operativo po ON p.preinscripcion_operativo_id=po.id AND po.ciclo_lectivo = $ciclo_lectivo "
					. "LEFT JOIN preinscripcion_tipo pt ON pa.preinscripcion_tipo_id = pt.id AND pt.preinscripcion_operativo_id = po.id "
					. "GROUP BY e.supervision_id) pa", 'pa.supervision_id = s.id', 'left')
				->where('e.nivel_id', $nivel_id)
				->where('e.dependencia_id', 1)
				->where('po.ciclo_lectivo', $ciclo_lectivo)
				->where('e.nivel_id=s.nivel_id AND e.dependencia_id=s.dependencia_id')
				->order_by('s.orden')
				->group_by('s.id, po.id')
				->get()->result();
		$preinscripciones = array();
		foreach ($supervisiones_p as $supervision_p) {
			$preinscripciones[$supervision_p->preinscripcion_operativo_id][] = $supervision_p;
		}
		return $preinscripciones;
	}

	public function get_escuelas_con_vacantes($ciclo_lectivo) {
		return $this->db
				->select('po.descripcion operativo, t.descripcion turno, s.nombre supervision, e.numero, e.nombre, l.descripcion localidad, d.descripcion departamento, p.vacantes-COALESCE(pa.inscriptos, 0) as vacantes')
				->from('escuela e')
				->join('preinscripcion p', 'p.escuela_id = e.id')
				->join('preinscripcion_operativo po', 'po.id = p.preinscripcion_operativo_id')
				->join('turno t', 'p.turno_id = t.id', 'left')
				->join('supervision s', 'e.supervision_id = s.id', 'left')
				->join('localidad l', 'e.localidad_id=l.id', 'left')
				->join('departamento d', 'l.departamento_id=d.id', 'left')
				->join("(SELECT p.preinscripcion_operativo_id, p.escuela_id, "
					. "COUNT(pa.id) inscriptos "
					. "FROM preinscripcion p JOIN preinscripcion_alumno pa ON p.id = pa.preinscripcion_id AND pa.estado='Inscripto' "
					. "GROUP BY p.preinscripcion_operativo_id, p.escuela_id) pa", 'pa.escuela_id = e.id AND po.id = pa.preinscripcion_operativo_id', 'left')
				->where('e.nivel_id=s.nivel_id AND e.dependencia_id=s.dependencia_id')
				->where('p.vacantes>COALESCE(pa.inscriptos, 0)')
				->where('po.ciclo_lectivo', $ciclo_lectivo)
				->order_by('s.orden, e.numero')
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
/* End of file Preinscripcion_model.php */
/* Location: ./application/modules/preinscripciones/models/Preinscripcion_model.php */
