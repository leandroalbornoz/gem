<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'usuario';
		$this->msg_name = 'Usuario';
		$this->id_name = 'id';
		$this->columnas = array('id', 'ip_address', 'usuario', 'password', 'authKey', 'accessToken', 'activation_code', 'forgotten_password_code', 'forgotten_password_time', 'remember_code', 'last_login', 'active', 'picture');

		$this->fields = array(
			'usuario' => array('label' => 'Usuario', 'readonly' => TRUE),
			'cuil' => array('label' => 'CUIL', 'readonly' => TRUE),
			'nombre' => array('label' => 'Nombre', 'readonly' => TRUE),
			'active' => array('label' => 'Activo', 'input_type' => 'combo', 'id_name' => 'active', 'array' => array('0' => 'Inactivo', '1' => 'Activo'), 'required' => TRUE)
		);
		$this->requeridos = array('usuario');
		//$this->unicos = array();
		$this->default_join = array(
			array('usuario_persona', 'usuario_persona.usuario_id = usuario.id', 'left'),
			array('persona', "persona.cuil = usuario_persona.cuil", 'left', array('persona.cuil', "CONCAT(persona.apellido, ', ', persona.nombre) as nombre"))
		);
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('usuario_id', $delete_id)->count_all_results('usuario_rol') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a usuario de rol.');
			return FALSE;
		}
		return TRUE;
	}

	public function get_escuela_seleccionada($usuario_id) {
		return $this->db->select('e.id, e.numero, e.nombre')
				->from('escuela e')
				->join('usuario u', 'e.id=u.escuela_seleccionada_id')
				->where('u.id', $usuario_id)->get()->row();
	}

	public function migrar($usuario_id) {
		$usuario = $this->db->where('id', $usuario_id)->get('usuario')->row();
		if (!empty($usuario))
			return TRUE;
		$db_acontrol = $this->load->database('acontrol', TRUE);
		$roles_db = $db_acontrol->select('ur.id, r.nombre')
				->from('usuario_rol ur')
				->join('rol r', 'ur.rol_id=r.id')
				->join('sistema s', 'r.sistema_id=s.id')
				->where('ur.fecha_baja IS NULL')
				->where('usuario_id', $usuario_id)
				->where('s.key', APP_KEY)
				->get()->result();
		$ok = TRUE;
		if (empty($roles_db)) {
			$ok &= $db_acontrol->insert('usuario_rol', array('usuario_id' => $usuario_id, 'id_alta' => $this->session->userdata('usuario')->usuario_id, 'rol_id' => ENVIRONMENT === 'production' ? '51' : '46'));
		}
		$usuario_acontrol = $db_acontrol->select('id, ip_address, usuario, password, authKey, accessToken, activation_code, forgotten_password_code, forgotten_password_time, remember_code, last_login, active, picture')
				->from('usuario')
				->where('id', $usuario_id)
				->get()->row();
		if (!empty($usuario_acontrol)) {
			$ok &= $this->create(array(
				'id' => $usuario_acontrol->id,
				'ip_address' => $usuario_acontrol->ip_address,
				'usuario' => $usuario_acontrol->usuario,
				'password' => $usuario_acontrol->password,
				'authKey' => empty($usuario_acontrol->authKey) ? ' ' : $usuario_acontrol->authKey,
				'accessToken' => empty($usuario_acontrol->accessToken) ? ' ' : $usuario_acontrol->accessToken,
				'activation_code' => $usuario_acontrol->activation_code,
				'forgotten_password_code' => $usuario_acontrol->forgotten_password_code,
				'forgotten_password_time' => $usuario_acontrol->forgotten_password_time,
				'remember_code' => $usuario_acontrol->remember_code,
				'last_login' => $usuario_acontrol->last_login,
				'active' => $usuario_acontrol->active,
				'picture' => $usuario_acontrol->picture)
			);
			$usuario_persona_acontrol = $db_acontrol->select('id, usuario_id, cuil, documento')
					->from('usuario_persona')
					->where('usuario_id', $usuario_id)
					->get()->row();
			if (!empty($usuario_persona_acontrol)) {
				$ok &= $this->db->insert('usuario_persona', array(
					'id' => $usuario_persona_acontrol->id,
					'usuario_id' => $usuario_persona_acontrol->usuario_id,
					'cuil' => $usuario_persona_acontrol->cuil,
					'documento' => $usuario_persona_acontrol->documento)
				);
			} else {
				$ok = FALSE;
			}
		} else {
			$ok = FALSE;
		}
		return $ok;
	}
}
/* End of file Usuario_model.php */
/* Location: ./application/models/Usuario_model.php */