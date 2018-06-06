<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mensaje_masivo_destinatario_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'mensaje_masivo_destinatario';
		$this->msg_name = 'Mensaje masivo destinatario';
		$this->id_name = 'id';
		$this->columnas = array('id', 'mensaje_masivo_id', 'para_entidad_id', 'leido_fecha', 'leido_usuario_id');
		$this->fields = array();
		$this->requeridos = array('mensaje_masivo_id', 'para_entidad_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('mensaje_masivo', 'mensaje_masivo.id = mensaje_masivo_destinatario.mensaje_masivo_id', '', array('mensaje_masivo.asunto as asunto', 'mensaje_masivo.mensaje as mensaje')),
			array('usuario d_u', 'd_u.id = mensaje_masivo.de_usuario_id', 'left', array('d_u.id as de_usuario_id')),
			array('usuario_persona d_up', 'd_u.id = d_up.usuario_id', 'left'),
			array('persona d_p', 'd_up.cuil = d_p.cuil', 'left', array("CONCAT(d_p.apellido, ', ', d_p.nombre) as de_usuario")),
			array('rol d_r', 'd_r.id = mensaje_masivo.de_rol_id', 'left'),
			array('entidad_tipo d_et', 'd_r.entidad_tipo_id = d_et.id', 'left'),
			array('entidad d_e', 'd_e.id = mensaje_masivo.de_entidad_id AND d_et.tabla = d_e.tabla', 'left', array("CONCAT(d_r.nombre, COALESCE(CONCAT(' ', d_e.nombre), '')) as de_rol", 'd_et.tabla as de_tabla')),
			array('rol p_r', 'p_r.id = mensaje_masivo.para_rol_id', 'left', array('p_r.codigo as para_rol_codigo')),
			array('entidad_tipo p_et', 'p_r.entidad_tipo_id = p_et.id', 'left'),
			array('entidad p_e', 'p_e.id = mensaje_masivo_destinatario.para_entidad_id AND p_et.tabla = p_e.tabla', 'left', array("CONCAT(p_r.nombre, COALESCE(CONCAT(' ', p_e.nombre), '')) as para_rol")),
			array('usuario l_u', 'l_u.id = mensaje_masivo_destinatario.leido_usuario_id', 'left'),
			array('usuario_persona l_up', 'l_u.id = l_up.usuario_id', 'left'),
			array('persona l_p', 'l_up.cuil = l_p.cuil', 'left', array("CONCAT(l_p.apellido, ', ', l_p.nombre) as leido_usuario")),
		);
	}

	public function get_niveles_bylinea($linea_id) {
		return $this->db->select('nivel.id as id, nivel.descripcion as nombre')
				->from('nivel')
				->where('nivel.linea_id', $linea_id)
				->order_by('nivel.id')
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
/* End of file Mensaje_masivo_destinatario_model.php */
/* Location: ./application/models/Mensaje_model.php */