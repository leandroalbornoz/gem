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
	
	public function login_portal($dni_padres, $dni_hijo, $num_escuela) {
		return $this->db->select('p.id, p.documento as padre_dni, p.cuil as padre_cuil,CONCAT(p.apellido, ", ", p.nombre) as padre_nombre, pa.documento as hijo_dni, CONCAT(pa.apellido, ", ", pa.nombre) as hijo_nombre, CONCAT(e.numero, " - ", e.nombre) as nombre_escuela, p.email as padre_mail')
				->from('persona p')
				->join('familia f', 'p.id = f.pariente_id')
				->join('persona pa', 'f.persona_id = pa.id')
				->join('alumno a', 'a.persona_id = pa.id')
				->join('alumno_division ad', 'ad.alumno_id = a.id')
				->join('division d', 'ad.division_id = d.id')
				->join('escuela e', 'd.escuela_id = e.id')
				->where('p.documento', $dni_padres)
				->where('pa.documento', $dni_hijo)
				->where('e.numero', $num_escuela)
				->get()->row();
	}
	
	public function login_portal_alumno($dni_alumno, $clave_division, $num_escuela) {
		return $this->db->select('p.id, p.nombre, p.apellido, p.documento, a.id as alumno_id, ad.id as alumno_division_id, d.id as division_id, e.id as escuela_id,  CONCAT(e.numero, " - ", e.nombre) as nombre_escuela')
				->from('persona p')
				->join('alumno a', 'a.persona_id = p.id')
				->join('alumno_division ad', 'ad.alumno_id = a.id')
				->join('division d', 'ad.division_id = d.id')
				->join('escuela e', 'd.escuela_id = e.id')
				->where('p.documento', $dni_alumno)
				->where('d.clave', $clave_division)
				->where('e.numero', $num_escuela)
				->get()->row();
	}
	
	public function usuario_existente($dni_padres) {
		$db_acontrol = $this->load->database('acontrol', TRUE);
		return $db_acontrol->select('u.usuario, u.id as usuario_id')
				->from('usuario_persona up')
				->join('usuario u', 'u.id = up.usuario_id')
				->where('up.documento', $dni_padres)
				->get()->row();
	}
	
	public function mail_existente($mail_padres) {
		return $this->db->select('u.usuario, u.id as usuario_id')
				->from('usuario u')
				->where('u.usuario', $mail_padres)
				->get()->row();
	}
	
	public function control_recuperar_password($activation_code) {
		return $this->db->select('u.usuario, u.id as usuario_id')
				->from('usuario u')
				->where('u.forgotten_password_code', $activation_code)
				->get()->row();
	}
	
	public function cuil_existente($cuil_padres) {
		return $this->db->select('up.id as usuario_persona_id')
				->from('usuario_persona up')
				->where('up.cuil', $cuil_padres)
				->get()->row();
	}
	
	public function verificacion($activacion_code) {
				$db_acontrol = $this->load->database('acontrol', TRUE);
		return $db_acontrol->select('u.id as usuario_id')
				->from('usuario u')
				->where('u.activation_code', $activacion_code)
				->get()->row();
	}
	
	public function rol_creado($usuario_id) {
		return $this->db->select('ur.id')
				->from('usuario_rol ur')
				->where('ur.usuario_id', $usuario_id)
				->where('ur.rol_id', '24')
				->get()->row();
	}
}