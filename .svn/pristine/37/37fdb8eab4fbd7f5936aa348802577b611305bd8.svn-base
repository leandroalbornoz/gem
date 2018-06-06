<?php

class Access_control_model extends CI_Model {

	public function login($login, $password) {
		$db_acontrol = $this->load->database('acontrol', TRUE);
		$usuario = $db_acontrol->select('usuario.id, usuario, password, active, last_login, up.cuil, picture')
				->join('usuario_persona up', 'usuario.id=up.usuario_id')
				->where('usuario', $login)
				->or_where("REPLACE(up.cuil, '-', '')=", str_replace('-', '', $login))
				->limit(1)
				->order_by('id', 'desc')
				->get('usuario')->row();
		if (!empty($usuario)) {
			if (password_verify($password, $usuario->password)) {
				return $usuario;
			}
		} else {
			return FALSE;
		}
	}
}