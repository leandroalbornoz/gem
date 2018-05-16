<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Materia_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'materia';
		$this->msg_name = 'Materia';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'es_grupo', 'grupo_id', 'pareja_pedagogica');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '100', 'required' => TRUE),
			'es_grupo' => array('label' => 'Es Grupo', 'input_type' => 'combo', 'id_name' => 'es_grupo', 'required' => TRUE, 'array' => array('No' => 'No', 'Si' => 'Si')),
			'grupo' => array('label' => 'Grupo', 'input_type' => 'combo', 'id_name' => 'grupo_id'),
			'pareja_pedagogica' => array('label' => 'Pareja Pedagógica', 'input_type' => 'combo', 'id_name' => 'pareja_pedagogica', 'required' => TRUE, 'array' => array('No' => 'No', 'Si' => 'Si'))
		);
		$this->requeridos = array('descripcion', 'es_grupo', 'pareja_pedagogica');
		//$this->unicos = array();
		$this->default_join = array(
			array('materia grupo', 'grupo.id = materia.grupo_id', 'left', array('grupo.descripcion as grupo'))
		);
	}

	public function datos_materia($materia_id) {
		return $this->db->select("m.id, m.descripcion as materia, ca.descripcion as carrera, c.descripcion as curso , n.descripcion as nivel, COALESCE(ec.carga_horaria,'') as carga_horaria")
				->from('materia m')
				->join('espacio_curricular ec', 'ec.materia_id = m.id', 'left')
				->join('curso c', 'c.id = ec.curso_id', 'left')
				->join('carrera ca', 'ca.id = ec.carrera_id', 'left')
				->join('nivel n', 'ca.nivel_id = n.id', 'left')
				->where('m.id', $materia_id)
				->order_by('n.id, ca.descripcion, c.descripcion, ec.id')
				->get()->result();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('materia_id', $delete_id)->count_all_results('espacio_curricular') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a espacio de curricular.');
			return FALSE;
		}
		if ($this->db->where('grupo_id', $delete_id)->count_all_results('materia') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a materia.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Materia_model.php */
/* Location: ./application/models/Materia_model.php */