<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Caracteristica_ficha_p_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'caracteristica_ficha_p';
		$this->msg_name = 'Caracteristicas ficha psicopedagógica';
		$this->id_name = 'id';
		$this->columnas = array('id', 'ficha_p_id', 'caracteristica_id', 'valor', 'fecha_desde', 'fecha_hasta', 'caracteristica_valor_id');
		$this->requeridos = array('ficha_p_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('ficha_p', 'ficha_p.id = caracteristica_ficha_p.ficha_p_id'/*, 'left', array('alumno.nombre as alumno')*/),
			array('caracteristica', 'caracteristica.id = caracteristica_ficha_p.caracteristica_id', 'left', array('caracteristica.descripcion as caracteristica')),
			array('caracteristica_valor', 'caracteristica_valor.id = caracteristica_ficha_p.caracteristica_valor_id', 'left', array('caracteristica_valor.caracteristica_id as caracteristica_valor'))
		);
	}

	public function get_by_alumno($alumno_id) {
		$caracteristicas = $this->db
				->select('ct.descripcion as tipo, c.descripcion as caracteristica, cfp.valor, cfp.caracteristica_valor_id')
				->from('caracteristica_ficha_p cfp')
				->join('caracteristica c', 'c.id=cfp.caracteristica_id', 'left')
				->join('caracteristica_tipo ct', 'ct.id=c.caracteristica_tipo_id')
				->where('cfp.ficha_p_id', $alumno_id)
				->get()->result();
		$tipos_caracteristicas = array();
		if (!empty($caracteristicas)) {
			foreach ($caracteristicas as $caracteristica) {
				$tipos_caracteristicas[$caracteristica->tipo][] = $caracteristica;
			}
		}
		return $tipos_caracteristicas;
	}

	public function get_fields($alumno_id = null, $readonly = FALSE, $editar = FALSE) {
		$fields = array();
		$caracteristicas_db = $this->db
				->select('ct.descripcion as tipo, c.id as caracteristica_id, ct.id as tipo_id, c.descripcion as caracteristica, c.valor_vacio, c.lista_valores, c.valor_multiple, cv.id as valor_id, cv.valor as valor')
				->from('caracteristica c')
				->join('caracteristica_tipo ct', 'ct.id = c.caracteristica_tipo_id')
				->join('caracteristica_valor cv', 'c.id = cv.caracteristica_id', 'left')
				->where('ct.entidad', 'ficha_p')
				->get()->result();
		$caracteristica_alumno = array();
		foreach ($caracteristicas_db as $caracteristica_db) {
			if (!isset($fields[$caracteristica_db->tipo]["caracteristicas[$caracteristica_db->caracteristica_id]"])) {
				$caracteristica_alumno[$caracteristica_db->caracteristica_id] = $caracteristica_db;
				if ($readonly) {
					$fields[$caracteristica_db->tipo]["caracteristicas[$caracteristica_db->caracteristica_id]"] = array('label' => $caracteristica_db->caracteristica, 'placeholder' => $caracteristica_db->valor_vacio, 'readonly' => TRUE);
				} else {
					$fields[$caracteristica_db->tipo]["caracteristicas[$caracteristica_db->caracteristica_id]"] = array('label' => $caracteristica_db->caracteristica, 'placeholder' => $caracteristica_db->valor_vacio);
					if ($caracteristica_db->lista_valores === 'Si') {
						if ($caracteristica_db->valor_multiple === 'Si') {
							$fields[$caracteristica_db->tipo]["caracteristicas[$caracteristica_db->caracteristica_id]"]['type'] = 'multiple';
						}
						$fields[$caracteristica_db->tipo]["caracteristicas[$caracteristica_db->caracteristica_id]"]['input_type'] = 'combo';
						$fields[$caracteristica_db->tipo]["caracteristicas[$caracteristica_db->caracteristica_id]"]['array'][''] = $caracteristica_db->valor_vacio;
					} else {
						if ($caracteristica_db->valor_multiple === 'Si') {
							$fields[$caracteristica_db->tipo]["caracteristicas[$caracteristica_db->caracteristica_id]"]['class'] = 'input-selectize';
						}
					}
				}
			}
			if ($caracteristica_db->lista_valores === 'Si' && !empty($caracteristica_db->valor_id) && !$readonly) {
				$fields[$caracteristica_db->tipo]["caracteristicas[$caracteristica_db->caracteristica_id]"]['array'][$caracteristica_db->valor_id] = $caracteristica_db->valor;
			}
		}

		if (!empty($alumno_id)) {
			$caracteristicas_alumno = $this->db
					->select('ct.descripcion as tipo, cfp.caracteristica_id, c.lista_valores, c.valor_multiple, cfp.id as caracteristica_alumno_id, cfp.valor, cfp.caracteristica_valor_id')
					->from('caracteristica_ficha_p cfp')
					->join('caracteristica c', 'c.id = cfp.caracteristica_id')
					->join('caracteristica_tipo ct', 'ct.id = c.caracteristica_tipo_id')
					->where('ct.entidad', 'ficha_p')
					->where('cfp.ficha_p_id', $alumno_id)
					->where('cfp.fecha_hasta IS NULL')
					->get()->result();
			if (!empty($caracteristicas_alumno)) {
				foreach ($caracteristicas_alumno as $caracteristica) {
					$caracteristica_alumno[$caracteristica->caracteristica_id] = $caracteristica;
					if ($readonly) {
						$fields[$caracteristica->tipo]["caracteristicas[$caracteristica->caracteristica_id]"]['value'] = $caracteristica->valor;
					} else {
						if ($caracteristica->valor_multiple === 'Si') {
							$fields[$caracteristica->tipo]["caracteristicas[$caracteristica->caracteristica_id]"]['value'] = empty($caracteristica->caracteristica_valor_id) ? $caracteristica->valor : explode(',', $caracteristica->caracteristica_valor_id);
						} else {
							$fields[$caracteristica->tipo]["caracteristicas[$caracteristica->caracteristica_id]"]['value'] = empty($caracteristica->caracteristica_valor_id) ? $caracteristica->valor : $caracteristica->caracteristica_valor_id;
						}
					}
				}
			}
		}
		if ($editar) {
			return array($fields, $caracteristica_alumno);
		} else {
			return $fields;
		}
	}
	
	//
	public function fecha_modificacion($alumno_id){
		return $this->db->select('cfp.fecha_desde, cfp.fecha_hasta, p.nombre, p.apellido, up.documento, cfp.audi_fecha, u.usuario as mail')
				->from('caracteristica_ficha_p cfp')
				->join('usuario u', 'u.id = cfp.audi_user', 'left')
				->join('usuario_persona up', ' up.usuario_id = u.id', 'left')
				->join('persona p', 'p.documento = up.documento', 'left')
				->where('cfp.fecha_hasta IS NULL')
				->where('ficha_p_id', $alumno_id)
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
/* End of file Caracteristica_alumno_model.php */
/* Location: ./application/models/Caracteristica_alumno_model.php */