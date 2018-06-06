<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_rol_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'usuario_rol';
		$this->msg_name = 'Rol de usuario';
		$this->id_name = 'id';
		$this->columnas = array('id', 'usuario_id', 'rol_id', 'entidad_id', 'activo');
		$this->fields = array(
			'usuario' => array('label' => 'Usuario', 'readonly' => TRUE),
			'rol' => array('label' => 'Rol', 'input_type' => 'combo', 'id_name' => 'rol_id', 'required' => TRUE),
			'entidad' => array('label' => 'Entidad', 'input_type' => 'combo', 'id_name' => 'entidad_id', 'required' => TRUE)
		);
		$this->requeridos = array('usuario_id', 'rol_id');
		$this->unicos = array(array('usuario_id', 'rol_id', 'entidad_id'));
		$this->default_join = array(
			array('rol', 'rol.id = usuario_rol.rol_id', 'left', array('rol.nombre as rol', 'rol.codigo')),
			array('entidad_tipo', 'entidad_tipo.id = rol.entidad_tipo_id', 'left'),
			array('entidad', 'entidad.tabla = entidad_tipo.tabla AND entidad.id = usuario_rol.entidad_id', 'left', array('entidad.nombre as entidad'))
		);
	}

	public function nuevo_anexo($escuela_id, $anexo_id) {
		$ok = TRUE;
		$usuarios_escuela = $this->db->select('usuario_id, rol_id')
				->from('usuario_rol ur')
				->join('rol r', 'ur.rol_id = r.id')
				->where('r.codigo', ROL_DIR_ESCUELA)
				->where('ur.entidad_id', $escuela_id)
				->get()->result();
		if (!empty($usuarios_escuela)) {
			foreach ($usuarios_escuela as $usuario_escuela) {
				$ok &= $this->create(array(
					'usuario_id' => $usuario_escuela->usuario_id,
					'rol_id' => $usuario_escuela->rol_id,
					'entidad_id' => $anexo_id
					), FALSE);
			}
		}
		return $ok;
	}

	public function get_usuarios($roles, $entidad_id) {
		return $this->db->query('SELECT usuario_rol.id,usuario_id,entidad_id,rol_id '
				. 'FROM usuario_rol '
				. ' JOIN rol ON usuario_rol.rol_id=rol.id '
				. 'WHERE rol.codigo IN ? '
				. 'AND usuario_rol.entidad_id = ? ', array($roles, $entidad_id))->result();
	}
}
/* End of file Usuario_rol_model.php */
/* Location: ./application/models/Usuario_rol_model.php */