<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mensaje_bono_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'mensaje_bono';
		$this->msg_name = 'Mensaje';
		$this->id_name = 'id';
		$this->columnas = array('id', 'fecha', 'de_usuario_id', 'de_rol_id', 'de_entidad_id', 'tema_id', 'asunto', 'mensaje', 'para_rol_id', 'para_entidad_id', 'para_usuario_id', 'leido_fecha', 'leido_usuario_id', 'conversacion_id');
		$this->fields = array(
			'de' => array('label' => 'De', 'readonly' => TRUE),
			'asunto' => array('label' => 'Asunto', 'maxlength' => '100', 'required' => TRUE),
			'tema' => array('label' => 'Tema', 'input_type' => 'combo', 'id_name' => 'tema_id', 'required' => TRUE),
			'mensaje' => array('label' => 'Mensaje', 'form_type' => 'textarea', 'rows' => '5', 'required' => TRUE),
			'para_rol' => array('label' => 'Para', 'input_type' => 'combo', 'id_name' => 'para_rol_id'),
			'para_entidad' => array('label' => '&nbsp;', 'input_type' => 'combo', 'id_name' => 'para_entidad_id'),
		);
		$this->requeridos = array('fecha', 'de_usuario_id', 'asunto', 'mensaje');
//$this->unicos = array();
		$this->default_join = array(
			array('bono_secundario.persona bsp', 'mensaje_bono.de_usuario_id = bsp.usuario_id', 'left', array("CONCAT(bsp.apellido, ', ', bsp.nombre, ' (', bsp.cuil, ')') as de_usuario")),
			array('bono_secundario.tema_mensaje t', 't.id = mensaje_bono.tema_id', 'left', array("t.descripcion as tema")),
			array('rol d_r', 'd_r.id = mensaje_bono.de_rol_id', 'left'),
			array('entidad_tipo d_et', 'd_r.entidad_tipo_id = d_et.id', 'left'),
			array('entidad d_e', 'd_e.id = mensaje_bono.de_entidad_id AND d_et.tabla = d_e.tabla', 'left', array("CONCAT(d_r.nombre, COALESCE(CONCAT(' ', d_e.nombre), '')) as de_rol", 'd_et.tabla as de_tabla')),
			array('usuario p_u', 'p_u.id = mensaje_bono.para_usuario_id', 'left'),
			array('usuario_persona p_up', 'p_u.id = p_up.usuario_id', 'left'),
			array('persona p_p', 'p_up.cuil = p_p.cuil', 'left', array("CONCAT(p_p.apellido, ', ', p_p.nombre) as para_usuario")),
			array('rol p_r', 'p_r.id = mensaje_bono.para_rol_id', 'left', array('p_r.codigo as para_rol_codigo')),
			array('entidad_tipo p_et', 'p_r.entidad_tipo_id = p_et.id', 'left'),
			array('entidad p_e', 'p_e.id = mensaje_bono.para_entidad_id AND p_et.tabla = p_e.tabla', 'left', array("CONCAT(p_r.nombre, COALESCE(CONCAT(' ', p_e.nombre), '')) as para_rol")),
			array('usuario l_u', 'l_u.id = mensaje_bono.leido_usuario_id', 'left'),
			array('usuario_persona l_up', 'l_u.id = l_up.usuario_id', 'left'),
			array('persona l_p', 'l_up.cuil = l_p.cuil', 'left', array("CONCAT(l_p.apellido, ', ', l_p.nombre) as leido_usuario")),
		);
	}

	public function get_mensajes($usuario_id, $rol = NULL, $no_leidos = TRUE, $template = TRUE) {
		$mensajes = array();
		if (es_rol_bono($this->rol)) {
			$query = $this->db->select("mensaje_bono.id, mensaje_bono.fecha, mensaje_bono.asunto, mensaje_bono.mensaje, CONCAT(bsp.apellido, ', ', bsp.nombre) as de_usuario, d_et.tabla as de_tabla, CONCAT(d_r.nombre, COALESCE(CONCAT(' ', d_e.nombre), '')) as de_rol, mensaje_bono.de_entidad_id, CONCAT(p_p.apellido, ', ', p_p.nombre) as para_usuario, CONCAT(p_r.nombre, COALESCE(CONCAT(' ', p_e.nombre), '')) as para_rol, t.descripcion as tema")
				->from('mensaje_bono')
				->join('bono_secundario.persona bsp', 'bsp.usuario_id = mensaje_bono.de_usuario_id')
				->join('bono_secundario.tema_mensaje t', 't.id = mensaje_bono.tema_id', 'left')
				->join('rol d_r', 'd_r.id = mensaje_bono.de_rol_id', 'left')
				->join('entidad_tipo d_et', 'd_r.entidad_tipo_id = d_et.id', 'left')
				->join('entidad d_e', 'd_e.id = mensaje_bono.de_entidad_id AND d_et.tabla = d_e.tabla', 'left')
				->join('usuario p_u', 'p_u.id = mensaje_bono.para_usuario_id', 'left')
				->join('usuario_persona p_up', 'p_u.id = p_up.usuario_id', 'left')
				->join('persona p_p', 'p_up.cuil = p_p.cuil', 'left')
				->join('rol p_r', 'p_r.id = mensaje_bono.para_rol_id', 'left')
				->join('entidad_tipo p_et', 'p_r.entidad_tipo_id = p_et.id', 'left')
				->join('entidad p_e', 'p_e.id = mensaje_bono.para_entidad_id AND p_et.tabla = p_e.tabla', 'left');
			if ($no_leidos) {
				$query->where('leido_fecha IS NULL');
			}
			if ($template) {
				$query->limit('10')
					->order_by('fecha', 'desc');
			}
			$mensajes_db = $query->get()->result();
			foreach ($mensajes_db as $mensaje) {
				$mensajes[$mensaje->id] = $mensaje;
			}
		}
		return $mensajes;
	}

	public function get_conversacion($conversacion_id) {
		return $this->db->select("mensaje_bono.id, mensaje_bono.fecha, mensaje_bono.asunto, mensaje_bono.mensaje, CONCAT(bsp.apellido, ', ', bsp.nombre, ' (', bsp.cuil, ')') as de_usuario, CONCAT(d_r.nombre, COALESCE(CONCAT(' ', d_e.nombre), '')) as de_rol, d_et.tabla as de_tabla, mensaje_bono.de_entidad_id, CONCAT(p_p.apellido, ', ', p_p.nombre) as para_usuario, CONCAT(p_r.nombre, COALESCE(CONCAT(' ', p_e.nombre), '')) as para_rol, p_et.tabla as para_tabla, mensaje_bono.para_entidad_id, bsp.id as persona_id, t.descripcion as tema")
				->from('mensaje_bono')
				->join('bono_secundario.persona bsp', 'bsp.usuario_id = mensaje_bono.de_usuario_id')
				->join('bono_secundario.tema_mensaje t', 't.id = mensaje_bono.tema_id', 'left')
				->join('usuario d_u', 'd_u.id = mensaje_bono.de_usuario_id', 'left')
				->join('rol d_r', 'd_r.id = mensaje_bono.de_rol_id', 'left')
				->join('entidad_tipo d_et', 'd_r.entidad_tipo_id = d_et.id', 'left')
				->join('entidad d_e', 'd_e.id = mensaje_bono.de_entidad_id AND d_et.tabla = d_e.tabla', 'left')
				->join('usuario_persona d_up', 'd_u.id = d_up.usuario_id', 'left')
				->join('persona d_p', 'd_up.cuil = d_p.cuil', 'left')
				->join('usuario p_u', 'p_u.id = mensaje_bono.para_usuario_id', 'left')
				->join('rol p_r', 'p_r.id = mensaje_bono.para_rol_id', 'left')
				->join('entidad_tipo p_et', 'p_r.entidad_tipo_id = p_et.id', 'left')
				->join('entidad p_e', 'p_e.id = mensaje_bono.para_entidad_id AND p_et.tabla = p_e.tabla', 'left')
				->join('usuario_persona p_up', 'p_u.id = p_up.usuario_id', 'left')
				->join('persona p_p', 'p_up.cuil = p_p.cuil', 'left')
				->where('COALESCE(mensaje_bono.conversacion_id, mensaje_bono.id) = ', $conversacion_id)
				->get()->result();
	}

	public function count_mensajes() {
		if (es_rol_bono($this->rol)) {
			$query = $this->db->select("count(*) as sin_leer")
				->from('mensaje_bono')
				->where('leido_fecha IS NULL')
				->where('para_usuario_id IS NULL');
			$mensajes = $query->get()->row();
		}
		return $mensajes;
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
/* End of file Mensaje_bono_model.php */
/* Location: ./application/models/Mensaje_bono_model.php */