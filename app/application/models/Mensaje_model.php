<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mensaje_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'mensaje';
		$this->msg_name = 'Mensaje';
		$this->id_name = 'id';
		$this->columnas = array('id', 'fecha', 'conversacion_id', 'de_usuario_id', 'de_rol_id', 'de_entidad_id', 'asunto', 'mensaje', 'para_usuario_id', 'para_rol_id', 'para_entidad_id', 'leido_fecha', 'leido_usuario_id');
		$this->fields = array(
			'de' => array('label' => 'De', 'readonly' => TRUE),
			'asunto' => array('label' => 'Asunto', 'maxlength' => '100', 'required' => TRUE),
			'mensaje' => array('label' => 'Mensaje', 'form_type' => 'textarea', 'rows' => '5', 'required' => TRUE),
			'para_rol' => array('label' => 'Para', 'input_type' => 'combo', 'id_name' => 'para_rol_id'),
			'para_entidad' => array('label' => '&nbsp;', 'input_type' => 'combo', 'id_name' => 'para_entidad_id'),
		);
		$this->requeridos = array('fecha', 'de_usuario_id', 'de_rol_id', 'asunto', 'mensaje');
		//$this->unicos = array();
		$this->default_join = array(
			array('usuario d_u', 'd_u.id = mensaje.de_usuario_id', 'left'),
			array('usuario_persona d_up', 'd_u.id = d_up.usuario_id', 'left'),
			array('persona d_p', 'd_up.cuil = d_p.cuil', 'left', array("CONCAT(d_p.apellido, ', ', d_p.nombre) as de_usuario")),
			array('rol d_r', 'd_r.id = mensaje.de_rol_id', 'left'),
			array('entidad_tipo d_et', 'd_r.entidad_tipo_id = d_et.id', 'left'),
			array('entidad d_e', 'd_e.id = mensaje.de_entidad_id AND d_et.tabla = d_e.tabla', 'left', array("CONCAT(d_r.nombre, COALESCE(CONCAT(' ', d_e.nombre), '')) as de_rol", 'd_et.tabla as de_tabla')),
			array('usuario p_u', 'p_u.id = mensaje.para_usuario_id', 'left'),
			array('usuario_persona p_up', 'p_u.id = p_up.usuario_id', 'left'),
			array('persona p_p', 'p_up.cuil = p_p.cuil', 'left', array("CONCAT(p_p.apellido, ', ', p_p.nombre) as para_usuario")),
			array('rol p_r', 'p_r.id = mensaje.para_rol_id', 'left', array('p_r.codigo as para_rol_codigo')),
			array('entidad_tipo p_et', 'p_r.entidad_tipo_id = p_et.id', 'left'),
			array('entidad p_e', 'p_e.id = mensaje.para_entidad_id AND p_et.tabla = p_e.tabla', 'left', array("CONCAT(p_r.nombre, COALESCE(CONCAT(' ', p_e.nombre), '')) as para_rol")),
			array('usuario l_u', 'l_u.id = mensaje.leido_usuario_id', 'left'),
			array('usuario_persona l_up', 'l_u.id = l_up.usuario_id', 'left'),
			array('persona l_p', 'l_up.cuil = l_p.cuil', 'left', array("CONCAT(l_p.apellido, ', ', l_p.nombre) as leido_usuario")),
		);
	}

	public function get_mensajes($usuario_id, $rol = NULL, $no_leidos = TRUE) {
		$query = $this->db->select("mensaje.id, mensaje.fecha, mensaje.asunto, mensaje.mensaje, CONCAT(d_p.apellido, ', ', d_p.nombre) as de_usuario, d_et.tabla as de_tabla, CONCAT(d_r.nombre, COALESCE(CONCAT(' ', d_e.nombre), '')) as de_rol, mensaje.de_entidad_id, CONCAT(p_p.apellido, ', ', p_p.nombre) as para_usuario, CONCAT(p_r.nombre, COALESCE(CONCAT(' ', p_e.nombre), '')) as para_rol")
			->from('mensaje')
			->join('usuario d_u', 'd_u.id = mensaje.de_usuario_id', 'left')
			->join('usuario_persona d_up', 'd_u.id = d_up.usuario_id', 'left')
			->join('persona d_p', 'd_up.cuil = d_p.cuil', 'left')
			->join('rol d_r', 'd_r.id = mensaje.de_rol_id', 'left')
			->join('entidad_tipo d_et', 'd_r.entidad_tipo_id = d_et.id', 'left')
			->join('entidad d_e', 'd_e.id = mensaje.de_entidad_id AND d_et.tabla = d_e.tabla', 'left')
			->join('usuario p_u', 'p_u.id = mensaje.para_usuario_id', 'left')
			->join('usuario_persona p_up', 'p_u.id = p_up.usuario_id', 'left')
			->join('persona p_p', 'p_up.cuil = p_p.cuil', 'left')
			->join('rol p_r', 'p_r.id = mensaje.para_rol_id', 'left')
			->join('entidad_tipo p_et', 'p_r.entidad_tipo_id = p_et.id', 'left')
			->join('entidad p_e', 'p_e.id = mensaje.para_entidad_id AND p_et.tabla = p_e.tabla', 'left');
		if ($no_leidos) {
			$query->where('leido_fecha IS NULL');
		}
		$query->where('para_usuario_id', $usuario_id);
		$mensajes_db = $query->get()->result();
		$mensajes = array();
		foreach ($mensajes_db as $mensaje) {
			$mensajes[$mensaje->id] = $mensaje;
		}
		if (isset($rol)) {
			$query = $this->db->select("mensaje.id, mensaje.fecha, mensaje.asunto, mensaje.mensaje, CONCAT(d_p.apellido, ', ', d_p.nombre) as de_usuario, d_et.tabla as de_tabla, mensaje.de_entidad_id, CONCAT(d_r.nombre, COALESCE(CONCAT(' ', d_e.nombre), '')) as de_rol, CONCAT(p_p.apellido, ', ', p_p.nombre) as para_usuario, CONCAT(p_r.nombre, COALESCE(CONCAT(' ', p_e.nombre), '')) as para_rol")
				->from('mensaje')
				->join('usuario d_u', 'd_u.id = mensaje.de_usuario_id', 'left')
				->join('usuario_persona d_up', 'd_u.id = d_up.usuario_id', 'left')
				->join('persona d_p', 'd_up.cuil = d_p.cuil', 'left')
				->join('rol d_r', 'd_r.id = mensaje.de_rol_id', 'left')
				->join('entidad_tipo d_et', 'd_r.entidad_tipo_id = d_et.id', 'left')
				->join('entidad d_e', 'd_e.id = mensaje.de_entidad_id AND d_et.tabla = d_e.tabla', 'left')
				->join('usuario p_u', 'p_u.id = mensaje.para_usuario_id', 'left')
				->join('usuario_persona p_up', 'p_u.id = p_up.usuario_id', 'left')
				->join('persona p_p', 'p_up.cuil = p_p.cuil', 'left')
				->join('rol p_r', 'p_r.id = mensaje.para_rol_id', 'left')
				->join('entidad_tipo p_et', 'p_r.entidad_tipo_id = p_et.id', 'left')
				->join('entidad p_e', 'p_e.id = mensaje.para_entidad_id AND p_et.tabla = p_e.tabla', 'left');
			if ($no_leidos) {
				$query->where('leido_fecha IS NULL');
			}
			if ($this->rol->codigo === ROL_USI) {
				$query->where_in('p_r.codigo', array(ROL_ADMIN,ROL_USI));
			} else {
				$query->where('p_r.codigo', $rol->codigo);
			}
			$query->where('para_entidad_id', $rol->entidad_id);
			$mensajes_db = $query->get()->result();
			foreach ($mensajes_db as $mensaje) {
				$mensajes[$mensaje->id] = $mensaje;
			}
		}

		return $mensajes;
	}

	public function get_conversacion($conversacion_id) {
		return $this->db->select("mensaje.id, mensaje.fecha, mensaje.asunto, mensaje.mensaje, CONCAT(d_p.apellido, ', ', d_p.nombre) as de_usuario, CONCAT(d_r.nombre, COALESCE(CONCAT(' ', d_e.nombre), '')) as de_rol, d_et.tabla as de_tabla, mensaje.de_entidad_id, CONCAT(p_p.apellido, ', ', p_p.nombre) as para_usuario, CONCAT(p_r.nombre, COALESCE(CONCAT(' ', p_e.nombre), '')) as para_rol, p_et.tabla as para_tabla, mensaje.para_entidad_id, ")
				->from('mensaje')
				->join('usuario d_u', 'd_u.id = mensaje.de_usuario_id', 'left')
				->join('rol d_r', 'd_r.id = mensaje.de_rol_id', 'left')
				->join('entidad_tipo d_et', 'd_r.entidad_tipo_id = d_et.id', 'left')
				->join('entidad d_e', 'd_e.id = mensaje.de_entidad_id AND d_et.tabla = d_e.tabla', 'left')
				->join('usuario_persona d_up', 'd_u.id = d_up.usuario_id', 'left')
				->join('persona d_p', 'd_up.cuil = d_p.cuil', 'left')
				->join('usuario p_u', 'p_u.id = mensaje.para_usuario_id', 'left')
				->join('rol p_r', 'p_r.id = mensaje.para_rol_id', 'left')
				->join('entidad_tipo p_et', 'p_r.entidad_tipo_id = p_et.id', 'left')
				->join('entidad p_e', 'p_e.id = mensaje.para_entidad_id AND p_et.tabla = p_e.tabla', 'left')
				->join('usuario_persona p_up', 'p_u.id = p_up.usuario_id', 'left')
				->join('persona p_p', 'p_up.cuil = p_p.cuil', 'left')
				->where('COALESCE(mensaje.conversacion_id, mensaje.id) = ', $conversacion_id)
				->get()->result();
	}

	public function get_escuelas($rol, $linea = '', $supervision = '', $dependencia = '', $nivel = '') {
		$query = $this->db->select('escuela.id, escuela.numero, escuela.anexo, escuela.nombre, escuela.nivel_id, escuela.supervision_id, escuela.regional_id, escuela.dependencia_id, dependencia.descripcion as dependencia, nivel.descripcion as nivel, linea.nombre as linea, supervision.nombre as supervision')
			->from('escuela')
			->join('dependencia', 'dependencia.id = escuela.dependencia_id', 'left')
			->join('nivel', 'nivel.id = escuela.nivel_id', 'left')
			->join('linea', 'linea.id = nivel.linea_id', 'left')
			->join('regional', 'regional.id = escuela.regional_id', 'left')
			->join('supervision', 'supervision.id = escuela.supervision_id', 'left')
			->where('escuela.escuela_activa', 'Si');
		if (!empty($dependencia)) {
			$query->where('dependencia.id', $dependencia);
		}
		if (!empty($linea)) {
			$query->where('linea.id', $linea);
		}
		if (!empty($nivel)) {
			$query->where('nivel.id', $nivel);
		}
		if (!empty($supervision)) {
			$query->where('supervision.id', $supervision);
		}
		if (!in_array($rol->codigo, ARRAY(ROL_ADMIN, ROL_USI, ROL_LIQUIDACION))) {
			switch ($rol->codigo) {
				case ROL_PRIVADA:
					$query->where('dependencia.id', 2);
					break;
				case ROL_SEOS:
					$query->where('dependencia.id', 3);
					break;
				case ROL_LINEA:
				case ROL_CONSULTA_LINEA:
					$query->where('nivel.linea_id', $rol->entidad_id);
					$query->where('dependencia.id', 1);
					break;
				case ROL_SUPERVISION:
					$query->where('supervision.id', $rol->entidad_id);
					break;
				case ROL_REGIONAL:
					$query->where('regional.id', $rol->entidad_id);
					break;
				default:
					return array();
			}
		}
		return $query->get()->result();
	}

	public function get_supervisiones($rol, $dependencia = '', $nivel = '') {
		$query = $this->db->select('supervision.id, supervision.nombre as supervision,supervision.responsable, supervision.email, supervision.dependencia_id, dependencia.descripcion as dependencia, nivel.descripcion as nivel')
			->from('supervision')
			->join('dependencia', 'dependencia.id = supervision.dependencia_id', 'left')
			->join('nivel', 'nivel.id = supervision.nivel_id', 'left');
		if (!empty($dependencia)) {
			$query->where('dependencia.id', $dependencia);
		}
		if (!empty($nivel)) {
			$query->where('nivel.id', $nivel);
		}
		if (!in_array($rol->codigo, ARRAY(ROL_ADMIN, ROL_USI, ROL_LIQUIDACION))) {
			switch ($rol->codigo) {
				case ROL_PRIVADA:
					$query->where('dependencia.id', 2);
					break;
				case ROL_SEOS:
					$query->where('dependencia.id', 3);
					break;
				case ROL_LINEA:
				case ROL_CONSULTA_LINEA:
					$query->where('nivel.linea_id', $rol->entidad_id);
					$query->where('dependencia.id', 1);
					break;
				case ROL_SUPERVISION:
					$query->where('supervision.id', $rol->entidad_id);
					break;
				default:
					return array();
			}
		}
		return $query->get()->result();
	}

	public function get_lineas($rol) {
		$query = $this->db->select('linea.id, linea.nombre as nombre')
			->from('linea');
		return $query->get()->result();
	}

	public function get_areas($rol) {
		$query = $this->db->select('area.id, area.codigo, area.descripcion')
			->from('area');
		return $query->get()->result();
	}

	public function get_regionales($rol) {
		$query = $this->db->select('regional.id, regional.descripcion')
			->from('regional');
		return $query->get()->result();
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
/* End of file Mensaje_model.php */
/* Location: ./application/models/Mensaje_model.php */