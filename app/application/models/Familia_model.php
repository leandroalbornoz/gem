<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Familia_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'familia';
		$this->msg_name = 'Familia';
		$this->id_name = 'id';
		$this->columnas = array('id', 'persona_id', 'pariente_id', 'parentesco_tipo_id', 'convive');
		$this->fields = array(
			'parentesco_tipo' => array('label' => 'Tipo de parentesco', 'input_type' => 'combo', 'id_name' => 'parentesco_tipo_id', 'required' => TRUE),
			'convive' => array('label' => 'Convive', 'input_type' => 'combo', 'id_name' => 'convive', 'required' => TRUE, 'array' => array('1' => 'Si', '2' => 'No'))
		);
		$this->requeridos = array('persona_id', 'pariente_id', 'parentesco_tipo_id', 'convive');
		//$this->unicos = array();
		$this->default_join = array(
			array('persona', 'persona.id = familia.persona_id', 'left', array('persona.cuil as persona', 'persona.apellido', 'persona.nombre', 'persona.fecha_nacimiento', 'persona.telefono_fijo', 'persona.telefono_movil', 'persona.documento', 'persona.manzana', 'persona.calle', 'persona.piso', 'persona.barrio', 'persona.calle_numero', 'persona.sexo_id','persona.estado_civil_id')),
			array('sexo', 'sexo.id = persona.sexo_id', 'left', array('sexo.descripcion as sexo')),
			array('estado_civil', 'estado_civil.id = persona.estado_civil_id','left', array('estado_civil.descripcion as estado_civil')),
			array('parentesco_tipo', 'parentesco_tipo.id = familia.parentesco_tipo_id', 'left', array('parentesco_tipo.descripcion as parentesco_tipo')),
		);
	}

	public function get_familiares($persona_id) {
		return $this->db->select('p.nombre, p.apellido, CONCAT(COALESCE(dt.descripcion_corta,\'\'),\' \',COALESCE(p.documento,\'\')) as documento, p.telefono_movil, p.email, t.descripcion as parentesco, o.descripcion as ocupacion, pr.descripcion as prestadora, f.convive, f.pariente_id, f.id, ne.descripcion as nivel_estudio, estado_civil.descripcion as estado_civil, sexo.descripcion as sexo ')
				->from('familia f')
				->join('persona p', 'f.pariente_id=p.id')
				->join('sexo', 'sexo.id=p.sexo_id', 'left')
				->join('estado_civil', 'estado_civil.id=p.estado_civil_id', 'left')
				->join('documento_tipo dt', 'dt.id = p.documento_tipo_id', 'left')
				->join('parentesco_tipo t', 'f.parentesco_tipo_id=t.id')
				->join('ocupacion o', 'p.ocupacion_id=o.id', 'left')
				->join('prestadora pr', 'p.prestadora_id=pr.id', 'left')
				->join('nivel_estudio ne', 'p.nivel_estudio_id=ne.id', 'left')
				->where('f.persona_id', $persona_id)
				->get()->result();
	}

//	public function get_hijos_a($pariente_id) {
//		return $this->db->select('f.id, f.persona_id, f.pariente_id,CONCAT(dt.descripcion_corta, \' \', p.documento) as documento,CONCAT(p.apellido, \' \', p.nombre) as alumno,pt.descripcion as parentesco,CONCAT(c.descripcion, \' \', d.division) as division,e.numero as escuela')
//				->from('familia f')
//				->join('parentesco_tipo pt', 'f.parentesco_tipo_id=pt.id', 'left')
//				->join('persona p', 'p.id=f.persona_id', 'left')
//				->join('alumno al', 'al.persona_id=p.id', 'left')
//				->join('(SELECT alumno_id, division_id FROM alumno_division ad JOIN alumno a ON ad.alumno_id=a.id JOIN persona p ON a.persona_id=p.id JOIN familia f ON p.id=f.persona_id WHERE f.pariente_id = ' . $pariente_id . ' ORDER BY CASE WHEN fecha_hasta IS NULL THEN 0 ELSE 1 END, fecha_desde desc LIMIT 1) ad', 'ad.alumno_id=al.id', 'left')
//				->join('division d', 'ad.division_id=d.id', 'left')
//				->join('curso c', 'd.curso_id=c.id', 'left')
//				->join('escuela e', 'd.escuela_id=e.id', 'left')
//				->join('documento_tipo dt', 'p.documento_tipo_id=dt.id', 'left')
//				->where('f.pariente_id', $pariente_id)
//				->get()->result();
//	}
//
//	public function get_hijos_2($pariente_id) {
//		return $this->db->select('f.id, f.persona_id, f.pariente_id,CONCAT(dt.descripcion_corta, \' \', p.documento) as documento,CONCAT(p.apellido, \' \', p.nombre) as alumno,pt.descripcion as parentesco,GROUP_CONCAT(CONCAT(c.descripcion, \' \', d.division) SEPARATOR \'<br>\') as division,GROUP_CONCAT(e.numero SEPARATOR \'<br>\') as escuela')
//				->from('familia f')
//				->join('parentesco_tipo pt', 'f.parentesco_tipo_id=pt.id', 'left')
//				->join('persona p', 'p.id=f.persona_id', 'left')
//				->join('alumno al', 'al.persona_id=p.id', 'left')
//				->join('alumno_division ad', 'ad.alumno_id=al.id AND ad.fecha_hasta IS NULL', 'left')
//				->join('division d', 'ad.division_id=d.id', 'left')
//				->join('curso c', 'd.curso_id=c.id', 'left')
//				->join('escuela e', 'd.escuela_id=e.id', 'left')
//				->join('documento_tipo dt', 'p.documento_tipo_id=dt.id', 'left')
//				->where('f.pariente_id', $pariente_id)
//				->group_by('f.id')
//				->get()->result();
//	}

	public function get_hijos($pariente_id) {
		return $this->db->select('f.id, f.persona_id, f.pariente_id,CONCAT(dt.descripcion_corta, \' \', p.documento) as documento,CONCAT(p.apellido, \' \', p.nombre) as alumno,pt.descripcion as parentesco,CONCAT(c.descripcion, \' \', d.division) as division, e.numero as escuela, sexo.descripcion as sexo, al.id as alumno_id, ad.id as alumno_division_id')
				->from('familia f')
				->join('parentesco_tipo pt', 'f.parentesco_tipo_id=pt.id', 'left')
				->join('persona p', 'p.id=f.persona_id', 'left')
				->join('sexo', 'p.sexo_id=sexo.id', 'left')
				->join('alumno al', 'al.persona_id=p.id', 'left')
				->join('alumno_division ad', 'ad.alumno_id=al.id AND ad.fecha_hasta IS NULL', 'left')
				->join('division d', 'ad.division_id=d.id', 'left')
				->join('curso c', 'd.curso_id=c.id', 'left')
				->join('escuela e', 'd.escuela_id=e.id', 'left')
				->join('nivel n', 'e.nivel_id=n.id', 'left')
				->join('documento_tipo dt', 'p.documento_tipo_id=dt.id', 'left')
				->where('f.pariente_id', $pariente_id)
				->order_by('f.id, ad.estado_id, n.formal DESC, ad.fecha_desde DESC')
				->group_by('f.id')
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
/* End of file Familia_model.php */
/* Location: ./application/models/Familia_model.php */