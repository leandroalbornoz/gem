<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Titulo_model extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'titulo';
		$this->msg_name = 'Titulo';
		$this->id_name = 'id';
		$this->columnas = array('id', 'nombre', 'pais_origen_id', 'provincia_id', 'titulo_establecimiento_id', 'titulo_tipo_id');
		$this->fields = array(
			'nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'required' => TRUE),
			'pais_origen' => array('label' => 'País Origen', 'input_type' => 'combo', 'id_name' => 'pais_origen_id', 'required' => TRUE),
			'provincia' => array('label' => 'Provincia', 'input_type' => 'combo', 'id_name' => 'provincia_id', 'required' => TRUE),
			'titulo_establecimiento' => array('label' => 'Establecimiento', 'input_type' => 'combo', 'id_name' => 'titulo_establecimiento_id', 'required' => TRUE),
			'titulo_tipo' => array('label' => 'Carrera', 'input_type' => 'combo', 'id_name' => 'titulo_tipo_id', 'required' => TRUE)
		);
		$this->requeridos = array('nombre','pais_origen_id', 'titulo_establecimiento_id', 'titulo_tipo_id');
	//	$this->unicos = array('nombre', 'titulo_establecimiento_id', 'titulo_tipo_id');
		$this->default_join = array(
			array('titulo_establecimiento', 'titulo_establecimiento.id = titulo.titulo_establecimiento_id', 'left', array('titulo_establecimiento.descripcion as titulo_establecimiento')), 
			array('titulo_tipo', 'titulo_tipo.id = titulo.titulo_tipo_id', 'left', array('titulo_tipo.descripcion as titulo_tipo')),
			array('nacionalidad', 'nacionalidad.id = titulo.pais_origen_id', 'left', array('nacionalidad.descripcion as pais_origen')),
			array('provincia', 'provincia.id = titulo.provincia_id', 'left', array('provincia.descripcion as provincia')),);
	}
	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('titulo_id', $delete_id)->count_all_results('titulo_persona') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a titulo de persona.');
			return FALSE;
		}
		return TRUE;
	}
	public function getTitulos($persona_id){
			return $this->db->select('t.nombre,n.descripcion as pais_origen,tp.fecha_inscripcion,tp.fecha_egreso,tp.serie,tp.numero,tp.observaciones,tt.descripcion as tipo_titulo,te.descripcion as establecimiento')
				->from('titulo_persona tp')
				->join('titulo t', 't.id = tp.titulo_id', 'left')
				->join('titulo_tipo tt', 'tt.id = t.titulo_tipo_id', 'left')
				->join('titulo_establecimiento te', 'te.id = t.titulo_establecimiento_id', 'left')
				->join('nacionalidad n', 'n.id = t.pais_origen_id', 'left')
				->join('provincia p', 'p.id = t.provincia_id', 'left')
				->where('tp.persona_id', $persona_id)
				->get()->row();
	}
	public function getTitulosLike($tipo,$establecimiento,$nombre){
		return $this->db->select('titulo.id,titulo.nombre,nacionalidad.descripcion as pais_origen,provincia.descripcion as provincia,titulo.titulo_tipo_id, titulo.titulo_establecimiento_id,titulo_tipo.descripcion as tipo_descripcion, titulo_establecimiento.descripcion as establecimiento_descripcion')
			->from('titulo')
			->join('titulo_tipo', 'titulo_tipo.id=titulo.titulo_tipo_id', 'left')
			->join('titulo_establecimiento', 'titulo_establecimiento.id=titulo.titulo_establecimiento_id', 'left')
			->join('nacionalidad', 'nacionalidad.id=titulo.pais_origen_id', 'left')
			->join('provincia', 'provincia.id = titulo.provincia_id', 'left')
			->like('titulo.nombre ', $nombre)
			->like('titulo_tipo.descripcion',$tipo)
			->like('titulo_establecimiento.descripcion',$establecimiento)
			->get()->result();		
	}
}
/* End of file Titulo_model.php */
/* Location: ./application/modules/titulos/models/Titulo_model.php */