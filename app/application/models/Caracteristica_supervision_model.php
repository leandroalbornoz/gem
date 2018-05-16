<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Caracteristica_supervision_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'caracteristica_supervision';
		$this->msg_name = 'Característica de supervisión';
		$this->id_name = 'id';
		$this->columnas = array('id', 'supervision_id', 'caracteristica_id', 'valor', 'fecha_desde', 'fecha_hasta', 'caracteristica_valor_id');
		$this->requeridos = array();
		//$this->unicos = array();
		$this->default_join = array(
			array('caracteristica', 'caracteristica.id = caracteristica_supervision.caracteristica_id', 'left', array('caracteristica.descripcion as caracteristica')),
			array('caracteristica_valor', 'caracteristica_valor.id = caracteristica_supervision.caracteristica_valor_id', 'left', array('caracteristica_valor.caracteristica_id as caracteristica_valor')),
			array('supervision', 'supervision.id = caracteristica_supervision.supervision_id', 'left', array('supervision.nombre as supervision')),);
	}

	public function get_by_supervision($supervision_id) {
		$caracteristicas = $this->db
				->select('ct.descripcion as tipo, c.descripcion as caracteristica, ce.valor, ce.caracteristica_valor_id')
				->from('caracteristica_supervision ce')
				->join('caracteristica c', 'c.id=ce.caracteristica_id', 'left')
				->join('caracteristica_tipo ct', 'ct.id=c.caracteristica_tipo_id')
				->where('ce.supervision_id', $supervision_id)
				->get()->result();
		$tipos_caracteristicas = array();
		if (!empty($caracteristicas)) {
			foreach ($caracteristicas as $caracteristica) {
				$tipos_caracteristicas[$caracteristica->tipo][] = $caracteristica;
			}
		}
		return $tipos_caracteristicas;
	}

	public function get_fields($nivel_id, $supervision_id = null, $readonly = FALSE, $editar = FALSE) {
		$fields = array();
		$caracteristicas_db = $this->db
				->select('ct.descripcion as tipo, c.id as caracteristica_id, ct.id as tipo_id, c.descripcion as caracteristica, c.valor_vacio, c.lista_valores, c.valor_multiple, cv.id as valor_id, cv.valor as valor')
				->from('caracteristica c')
				->join('caracteristica_tipo ct', 'ct.id = c.caracteristica_tipo_id')
				->join('caracteristica_valor cv', 'c.id = cv.caracteristica_id', 'left')
				->join('caracteristica_nivel cn', 'c.id = cn.caracteristica_id')
				->where('ct.entidad', 'supervision')
				->where('cn.nivel_id', $nivel_id)
				->order_by('ct.id, c.id')
				->get()->result();
		$caracteristica_supervision = array();
		foreach ($caracteristicas_db as $caracteristica_db) {
			if (!isset($fields[$caracteristica_db->tipo]["caracteristicas[$caracteristica_db->caracteristica_id]"])) {
				$caracteristica_supervision[$caracteristica_db->caracteristica_id] = $caracteristica_db;
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

		if (!empty($supervision_id)) {
			$caracteristicas_supervision = $this->db
					->select('ct.descripcion as tipo, ce.caracteristica_id, c.lista_valores, c.valor_multiple, ce.id as caracteristica_supervision_id, ce.valor, ce.caracteristica_valor_id')
					->from('caracteristica_supervision ce')
					->join('caracteristica c', 'c.id = ce.caracteristica_id')
					->join('caracteristica_tipo ct', 'ct.id = c.caracteristica_tipo_id')
					->join('caracteristica_nivel cn', 'c.id = cn.caracteristica_id')
					->where('ct.entidad', 'supervision')
					->where('ce.supervision_id', $supervision_id)
					->where('ce.fecha_hasta IS NULL')
					->where('cn.nivel_id', $nivel_id)
					->order_by('ct.id, c.id')
					->get()->result();
			if (!empty($caracteristicas_supervision)) {
				foreach ($caracteristicas_supervision as $caracteristica) {
					$caracteristica_supervision[$caracteristica->caracteristica_id] = $caracteristica;
					if ($readonly) {
						$fields[$caracteristica->tipo]["caracteristicas[$caracteristica->caracteristica_id]"]['value'] = $caracteristica->valor;
					} else {
						if ($caracteristica->valor_multiple === 'Si' && $caracteristica->lista_valores === 'Si') {
							$fields[$caracteristica->tipo]["caracteristicas[$caracteristica->caracteristica_id]"]['value'] = empty($caracteristica->caracteristica_valor_id) ? $caracteristica->valor : explode(',', $caracteristica->caracteristica_valor_id);
						} else {
							$fields[$caracteristica->tipo]["caracteristicas[$caracteristica->caracteristica_id]"]['value'] = empty($caracteristica->caracteristica_valor_id) ? $caracteristica->valor : $caracteristica->caracteristica_valor_id;
						}
					}
				}
			}
		}
		if ($editar) {
			return array($fields, $caracteristica_supervision);
		} else {
			return $fields;
		}
	}

	public function get_by_nivel($nivel_id) {
		$caracteristicas_db = $this->db
				->select('ct.descripcion as tipo, c.id as caracteristica_id, ct.id as tipo_id, c.descripcion as caracteristica, c.lista_valores, cv.id as valor_id, cv.valor as valor')
				->from('caracteristica c')
				->join('caracteristica_tipo ct', 'ct.id = c.caracteristica_tipo_id')
				->join('caracteristica_valor cv', 'c.id = cv.caracteristica_id', 'left')
				->join('caracteristica_nivel cn', 'c.id = cn.caracteristica_id')
				->where('ct.entidad', 'supervision')
				->where('cn.nivel_id', $nivel_id)
				->get()->result();
		$tipos = array();
		foreach ($caracteristicas_db as $c) {
			if (empty($tipos[$c->tipo_id])) {
				$tipos[$c->tipo_id] = new stdClass();
				$tipos[$c->tipo_id]->id = $c->tipo_id;
				$tipos[$c->tipo_id]->descripcion = $c->tipo;
				$tipos[$c->tipo_id]->caracteristicas = array();
			}
			if (empty($tipos[$c->tipo_id]->caracteristicas[$c->caracteristica_id])) {
				$tipos[$c->tipo_id]->caracteristicas[$c->caracteristica_id] = new stdClass();
				$tipos[$c->tipo_id]->caracteristicas[$c->caracteristica_id]->id = $c->caracteristica_id;
				$tipos[$c->tipo_id]->caracteristicas[$c->caracteristica_id]->descripcion = $c->caracteristica;
				$tipos[$c->tipo_id]->caracteristicas[$c->caracteristica_id]->lista_valores = $c->lista_valores;
				if ($c->lista_valores === 'Si') {
					$tipos[$c->tipo_id]->caracteristicas[$c->caracteristica_id]->valores = array();
				}
			}
			if ($c->lista_valores === 'Si') {
				$tipos[$c->tipo_id]->caracteristicas[$c->caracteristica_id]->valores[$c->valor_id] = $c->valor;
			}
		}
		return $tipos;
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
/* End of file Caracteristica_supervision_model.php */
/* Location: ./application/models/Caracteristica_supervision_model.php */