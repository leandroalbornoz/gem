<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ingreso_alumno_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'ingreso_alumno';
		$this->msg_name = 'Ingreso alumno';
		$this->id_name = 'id';
		$this->columnas = array('id', 'alumno_id', 'alumno_division_id', 'hermano_ad_id', 'ingreso_operativo_id', 'abanderado_escuela_id', 'promedio', 'abanderado', 'participa', 'verificar', 'motivo_no_participa', 'asignado', 'fecha_cierre_postulacion', 'latitud', 'longitud', 'hash_cierre');
		$this->fields = array(
			'promedio' => array('label' => 'Promedio', 'type' => 'integer'),
			'abanderado' => array('label' => 'Abanderado', 'type' => 'integer'),
			'participa' => array('label' => 'Participa', 'type' => 'integer'),
			'verificar' => array('label' => 'Verificar', 'type' => 'integer'),
			'motivo_no_participa' => array('label' => 'Motivo no participa', 'type' => 'integer'),
			'asignado' => array('label' => 'Asignado', 'type' => 'integer'),
			'fecha_cierre_postulacion' => array('label' => 'Fecha cierre postulacion', 'type' => 'integer'),
			'latitud' => array('label' => 'Latitud', 'type' => 'integer'),
			'longitud' => array('label' => 'Longitud', 'type' => 'integer'),
			'hash_cierre' => array('label' => 'Hash cierre', 'type' => 'integer'),
		);
		$this->requeridos = array('alumno_id', 'alumno_division_id');
		//$this->unicos = array();
		$this->default_join = array(
		);
	}
	
	public function get_cant_abanderados($escuela_id, $ciclo_lectivo) {
		return $this->db->select("COUNT(distinct ia.id) as cant_abanderados")
				->from('alumno a')
				->join('ingreso_alumno ia', 'a.id=ia.alumno_id')
				->join('alumno_division ad', 'a.id=ad.alumno_id')
				->join('division d', 'ad.division_id=d.id')
				->join('curso c', 'd.curso_id=c.id')
				->join('curso c_gm', 'ad.curso_id=c_gm.id', 'left')
				->where('ia.abanderado_escuela_id', $escuela_id)
				->where('ad.ciclo_lectivo', $ciclo_lectivo)
				->where('d.escuela_id', $escuela_id)
				->where("c.descripcion LIKE '%7%' OR c_gm.descripcion LIKE '%7%'")
				->get()->result();
	}
	
	public function get_certificados($alumno_ingreso_id) {
		return $this->db->select("ia.id, e.numero, e.nombre as escuela_nombre, le.descripcion as localidad_escuela, p.nombre, p.apellido, dt.descripcion_corta, p.documento, ia.promedio, p.calle, p.calle_numero, lp.descripcion as localidad_persona, ad.ciclo_lectivo, d.division, c.descripcion, ia.abanderado, ia.verificar")
				->from('ingreso_alumno ia')
				->join('alumno a', 'a.id=ia.alumno_id', 'left')
				->join('alumno_division ad', 'ad.id=ia.alumno_division_id')
				->join('persona p', 'a.persona_id=p.id')
				->join('localidad lp', 'lp.id=p.localidad_id')
				->join('division d', 'ad.division_id=d.id')
				->join('escuela e', 'd.escuela_id=e.id')
				->join('localidad le', 'le.id=e.localidad_id')
				->join('curso c', 'd.curso_id=c.id')
				->join('curso c_gm', 'ad.curso_id=c_gm.id', 'left')
				->join('documento_tipo dt', 'p.documento_tipo_id=dt.id')
				->where('ia.id', $alumno_ingreso_id)
				->get()->result();
	}
	
	public function get_abanderados_baja($escuela_id, $ciclo_lectivo) {
		return $this->db->select("CONCAT(p.apellido, ', ',p.nombre) as persona, ia.id, ia.abanderado, ia.abanderado_escuela_id, ad.fecha_hasta, d.division as division, c.descripcion as curso")
				->from('alumno a')
				->join('persona p', 'a.persona_id=p.id')
				->join('ingreso_alumno ia', 'a.id=ia.alumno_id')
				->join('alumno_division ad', 'a.id=ad.alumno_id')
				->join('division d', 'ad.division_id=d.id')
				->join('curso c', 'd.curso_id=c.id')
				->join('curso c_gm', 'ad.curso_id=c_gm.id', 'left')
				->where('ia.abanderado_escuela_id', $escuela_id)
				->where('ad.ciclo_lectivo', $ciclo_lectivo)
				->where('ia.abanderado', 'Si')
				->where("c.descripcion LIKE '%7%' OR c_gm.descripcion LIKE '%7%'")
				->group_by('ia.id')
				->having("MAX(CASE WHEN ad.fecha_hasta IS NULL THEN 1 ELSE 0 END)=0")
				->get()->result();
	}
	
}