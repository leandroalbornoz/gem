<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends CI_Model {

	public function get_usuario($user_id) {
		$db_acontrol = $this->load->database('acontrol', TRUE);
		$usuario = $db_acontrol->select('u.id as usuario_id, u.usuario, up.cuil, u.active, u.picture as foto')
				->from('usuario u')
				->join('usuario_persona up', 'u.id=up.usuario_id', 'left')
//				->join('usuario_rol ur', 'ur.usuario_id=u.id')
//				->join('rol r', 'ur.rol_id=r.id')
//				->join('sistema s', 'r.sistema_id=s.id')
				->where('u.id', $user_id)
//				->where('s.key', APP_KEY)
				->get()->row();
		if (!empty($usuario->cuil)) {
			$persona = $this->db
					->select('p.apellido, p.nombre')
					->from('persona p')
					->where("cuil", $usuario->cuil)
					->get()->row();
			if (!empty($persona)) {
				$usuario->apellido = $persona->apellido;
				$usuario->nombre = $persona->nombre;
			}
		}
		return $usuario;
	}

	public function get_grupos($user_id) {
		$db_acontrol = $this->load->database('acontrol', TRUE);
		$roles_db = $db_acontrol->select('ur.id, r.nombre')
				->from('usuario_rol ur')
				->join('rol r', 'ur.rol_id=r.id')
				->join('sistema s', 'r.sistema_id=s.id')
				->where('ur.fecha_baja IS NULL')
				->where('usuario_id', $user_id)
				->where('s.key', APP_KEY)
				->get()->result();
		$grupos = array();
		if (!empty($roles_db)) {
			foreach ($roles_db as $rol) {
				$grupos[] = $rol->nombre;
			}
		}
		return $grupos;
	}

	public function get_rol_activo($user_id) {
		return $this->db->select('ur.id, ur.rol_id, r.codigo, r.nombre rol, r.entidad_tipo_id, et.nombre entidad_tipo, et.tabla, ur.entidad_id, e.nombre entidad')
				->from('usuario_rol ur')
				->join('rol r', 'ur.rol_id=r.id')
				->join('entidad_tipo et', 'r.entidad_tipo_id=et.id', 'left')
				->join('sistema s', 'r.sistema_id=s.id')
				->join('entidad e', 'e.tabla=et.tabla and e.id=ur.entidad_id', 'left')
				->where('usuario_id', $user_id)
				->where('s.key', APP_KEY)
				->where('ur.activo', 1)
				->get()->row();
	}

	public function get_rol_simulado($rol_id, $entidad_id) {
		return $this->db->select('0 id, r.codigo, r.nombre rol, r.entidad_tipo_id, et.nombre entidad_tipo, et.tabla, e.id entidad_id, e.nombre entidad')
				->from('rol r')
				->join('entidad_tipo et', 'r.entidad_tipo_id=et.id', 'left')
				->join('entidad e', 'e.tabla=et.tabla', 'left')
				->where('r.id', $rol_id)
				->where('e.id', $entidad_id)
				->get()->row();
	}

	public function get_rol_simulado_bono($rol_id, $entidad_id) {
		return $this->db->select('0 id, r.codigo, r.nombre rol, r.entidad_tipo_id, et.nombre entidad_tipo, et.tabla, be.gem_id entidad_id, be.nombre entidad')
				->from('rol r')
				->join('entidad_tipo et', 'r.entidad_tipo_id=et.id', 'left')
				->join('bono_escuelas be', 'be.tabla=et.tabla', 'left')
				->where('r.id', $rol_id)
				->where('be.gem_id', $entidad_id)
				->get()->row();
	}

	public function set_rol_activo($user_id, $rol_id, $rol_activo) {
		if (!empty($rol_activo)) {
			$this->db->update('usuario_rol', array('activo' => '0'), array('id' => $rol_activo->id, 'usuario_id' => $user_id));
		}
		$this->db->update('usuario_rol', array('activo' => '1'), array('id' => $rol_id, 'usuario_id' => $user_id));
		return $this->get_rol_activo($user_id);
	}

	public function get_roles($usuario_id) {
		$roles = $this->db->select('ur.id, r.codigo codigo, r.nombre, et.nombre entidad_tipo, et.tabla, ur.entidad_id, e.nombre entidad')
				->from('usuario_rol ur')
				->join('rol r', 'ur.rol_id=r.id')
				->join('entidad_tipo et', 'r.entidad_tipo_id=et.id', 'left')
				->join('entidad e', 'e.tabla=et.tabla and e.id=ur.entidad_id', 'left')
				->where('usuario_id', $usuario_id)
				->order_by('r.nombre, e.nombre')
				->get()->result();
		return $roles;
	}

	public function get_usuarios_by_clave($clave) {
		$db_acontrol = $this->load->database('acontrol', TRUE);
		if (!is_numeric($clave)) {
			$usuarios = $db_acontrol->select('u.*, up.cuil')->from('usuario u')->join('usuario_persona up', 'up.usuario_id=u.id')->like('u.usuario', $clave)->group_by('u.id')->get()->result();
		} else {
			$cedula = $this->load->database('default', TRUE);
			$usuarios = $cedula->select('u.*, up.cuil, p.apellido, p.nombre')->from('usuario u')->join('usuario_persona up', 'up.usuario_id=u.id')->join('persona p', 'p.cuil = up.cuil')->where('p.cuil', $clave)->or_where('p.documento', $clave)->group_by('u.id')->get()->result();
		}
		return $usuarios;
	}

	public function crear_usuario_rol($usuario_id, $rol_id) {
		$db_acontrol = $this->load->database('acontrol', TRUE);
		$db_acontrol->insert('usuario_rol', array(
			'usuario_id' => $usuario_id,
			'rol_id' => $rol_id,
			'id_alta' => $this->usuario,
			'fecha_alta' => date('Y-m-d H:i:s')));
	}

	public function borrar_usuario_rol($usuario_id, $rol_id) {
		$db_acontrol = $this->load->database('acontrol', TRUE);
		$db_acontrol
			->set('id_baja', $this->usuario)
			->set('fecha_baja', date('Y-m-d H:i:s'))
			->where('usuario_id', $usuario_id)
			->where('rol_id', $rol_id)
			->where('fecha_baja IS NULL')
			->update('usuario_rol');
	}

	public function get_permisos($user_id) {
		$db_acontrol = $this->load->database('acontrol', TRUE);
		$roles = $db_acontrol->select('ur.id, r.nombre')
				->from('usuario_rol ur')
				->join('rol r', 'ur.rol_id=r.id')
				->join('sistema s', 'r.sistema_id=s.id')
				->where('ur.fecha_baja IS NULL')
				->where('usuario_id', $user_id)
				->where('s.key', APP_KEY)
				->get()->result();
		echo json_encode($roles);
		return TRUE;
	}

	public function get_permisos_tree($userId) {
		$db_acontrol = $this->load->database('acontrol', TRUE);
		$roles = $db_acontrol->select('rol.id, rol.nombre')->from('rol')->join('sistema', 'rol.sistema_id=sistema.id')
				->where('sistema.key', APP_KEY)->where('rol_padre_id IS NULL')->get()->result();
		$permisos_tree = array();
		foreach ($roles as $rol) {
			$children = array();
			$this->busca_hijos($rol, $children, $userId, $this->checkRol($this->usuario, $rol->id));

			$permisos_tree[] = array(
				'text' => $rol->nombre,
				'id' => $rol->id,
				'state' => [
					'checked' => $this->checkRol($userId, $rol->id),
					'disabled' => !$this->checkRol($this->usuario, $rol->id)
				],
				'children' => $children
			);
		}
		return $permisos_tree;
	}

	private function busca_hijos($rol, &$children, $user_id, $enabled) {
		$db_acontrol = $this->load->database('acontrol', TRUE);
		$hijos = $db_acontrol->select('rol.id, rol.nombre')
				->from('rol')
				->join('sistema', 'rol.sistema_id=sistema.id')
				->where('sistema.key', APP_KEY)
				->where('rol_padre_id', $rol->id)->get()->result();

		foreach ($hijos as $rol_hijo) {
			$hijos_hijo = $db_acontrol->select('rol.id, rol.nombre')
					->from('rol')
					->join('sistema', 'rol.sistema_id=sistema.id')
					->where('sistema.key', APP_KEY)
					->where('rol_padre_id', $rol_hijo->id)->get()->result();
			$children_children = array();
			$this->busca_hijos($rol_hijo, $children_children, $user_id, $enabled || $this->checkRol($this->usuario, $rol_hijo->id));

			$children[] = array(
				'text' => $rol_hijo->nombre,
				'id' => $rol_hijo->id,
				'state' => [
					'checked' => $this->checkRol($user_id, $rol_hijo->id),
					'disabled' => !$this->checkRol($this->usuario, $rol_hijo->id) && !$enabled
				],
				'children' => $children_children
			);
		}
	}

	public function checkRol($usuario_id, $rol_id) {
		$db_acontrol = $this->load->database('acontrol', TRUE);
		$usuario_rol = $db_acontrol->where('usuario_id', $usuario_id)
				->where('rol_id', $rol_id)->where('fecha_baja IS NULL')->get('usuario_rol')->result();
		if (empty($usuario_rol)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function verificar_escuela_grupo($escuela_id, $escuela_grupo_id) {
		return $this->db->select('ege.escuela_grupo_id')
				->from('escuela_grupo_escuela ege')
				->where('ege.escuela_grupo_id', $escuela_grupo_id)
				->where('ege.escuela_id', $escuela_id)
				->get()->result();
	}

	public function verificar_division_escuela($escuela_id, $division_id) {
		return $this->db->select('d.id')
				->from('division d')
				->join('escuela', 'escuela.id = d.escuela_id')
				->where('escuela.id', $escuela_id)
				->where('d.id', $division_id)
				->get()->result();
	}

	public function verificar_cursada_escuela($escuela_id, $cursada_id) {
		return $this->db->select('d.id')
				->from('cursada c')
				->join('division d', 'd.id = c.division_id')
				->join('escuela', 'escuela.id = d.escuela_id')
				->where('escuela.id', $escuela_id)
				->where('c.id', $cursada_id)
				->get()->result();
	}

	public function verificar_permiso($tipo, $rol, $entidad) {
		switch ($tipo) {
			case 'division':
				switch ($rol->codigo) {
					case ROL_DOCENTE:
					case ROL_ASISTENCIA_DIVISION:
						return $rol->entidad_id === $entidad->id;
					default:
						return FALSE;
				}
				break;
			case 'cursada':
				switch ($rol->codigo) {
					case ROL_DOCENTE_CURSADA:
						return $rol->entidad_id === $entidad->id;
					default:
						return FALSE;
				}
				break;
			case 'escuela':
				switch ($rol->codigo) {
					case ROL_ADMIN:
					case ROL_USI:
					case ROL_CONSULTA:
					case ROL_JEFE_LIQUIDACION:
					case ROL_LIQUIDACION:
						return TRUE;
					case ROL_ASISTENCIA_DIVISION:
					case ROL_DOCENTE:
						return !empty($this->verificar_division_escuela($entidad->id, $rol->entidad_id)) ? TRUE : FALSE;
					case ROL_DOCENTE_CURSADA:
						return !empty($this->verificar_cursada_escuela($entidad->id, $rol->entidad_id)) ? TRUE : FALSE;
					case ROL_AREA:
						return FALSE;
					case ROL_ESCUELA_ALUM:
					case ROL_ESCUELA_CAR:
					case ROL_DIR_ESCUELA:
					case ROL_SDIR_ESCUELA:
					case ROL_SEC_ESCUELA:
						return $rol->entidad_id === $entidad->id || $rol->entidad_id === $entidad->escuela_id;
					case ROL_SUPERVISION:
						return $rol->entidad_id === $entidad->supervision_id;
					case ROL_GRUPO_ESCUELA:
					case ROL_GRUPO_ESCUELA_CONSULTA:
						return !empty($this->verificar_escuela_grupo($entidad->id, $rol->entidad_id)) ? TRUE : FALSE;
					case ROL_LINEA:
					case ROL_CONSULTA_LINEA:
						return $rol->entidad_id === $entidad->linea_id;
					case ROL_PRIVADA:
						return $entidad->dependencia_id === '2';
					case ROL_SEOS:
						return $entidad->dependencia_id === '3';
					case ROL_REGIONAL:
						return $rol->entidad_id === $entidad->regional_id;
					default:
						return FALSE;
				}
				break;
			case 'supervision':
				switch ($rol->codigo) {
					case ROL_ADMIN:
					case ROL_USI:
					case ROL_CONSULTA:
					case ROL_JEFE_LIQUIDACION:
					case ROL_LIQUIDACION:
						return TRUE;
					case ROL_AREA:
					case ROL_DOCENTE:
					case ROL_DIR_ESCUELA:
					case ROL_SDIR_ESCUELA:
					case ROL_SEC_ESCUELA:
						return FALSE;
					case ROL_SUPERVISION:
						return $rol->entidad_id === $entidad->id;
					case ROL_LINEA:
					case ROL_GRUPO_ESCUELA:
					case ROL_GRUPO_ESCUELA_CONSULTA:
					case ROL_CONSULTA_LINEA:
						return $rol->entidad_id === $entidad->linea_id && $entidad->dependencia_id === '1';
					case ROL_PRIVADA:
						return $entidad->dependencia_id === '2';
					case ROL_SEOS:
						return $entidad->dependencia_id === '3';
					case ROL_REGIONAL:
						return $rol->entidad_id === $entidad->regional_id;
					default:
						return FALSE;
				}
				break;
			case 'regional':
			case 'jefe_area':
				switch ($rol->codigo) {
					case ROL_ADMIN:
					case ROL_USI:
					case ROL_CONSULTA:
					case ROL_JEFE_LIQUIDACION:
					case ROL_LIQUIDACION:
						return TRUE;
					default:
						return FALSE;
				}
				break;
			case 'linea':
				switch ($rol->codigo) {
					case ROL_ADMIN:
					case ROL_USI:
					case ROL_CONSULTA:
					case ROL_JEFE_LIQUIDACION:
					case ROL_LIQUIDACION:
						return TRUE;
					case ROL_LINEA:
					case ROL_CONSULTA_LINEA:
						return $rol->entidad_id === $entidad->id;
				}
				break;
		}
	}

	public function usuarios_escuela($entidad_id) {
		$this->db->select('u.id, u.usuario, p.cuil as cuil, CONCAT(p.apellido, \', \', p.nombre) as persona, rol.nombre as rol, e.nombre as entidad, u.active, u.id')
			->from('usuario u')
			->join('usuario_persona up', 'up.usuario_id=u.id')
			->join('usuario_rol ur', 'ur.usuario_id=u.id', 'left')
			->join('rol', 'ur.rol_id=rol.id', 'left')
			->join('entidad_tipo et', 'rol.entidad_tipo_id=et.id', 'left')
			->join('entidad e', 'e.tabla=et.tabla and e.id=ur.entidad_id', 'left')
			->join('persona p', "p.cuil = up.cuil", 'left')
			->where("(rol.codigo in ('" . ROL_DIR_ESCUELA . "','" . ROL_ESCUELA_CAR . "','" . ROL_ESCUELA_ALUM . "')) AND (ur.entidad_id = $entidad_id)")
			->order_by('rol.nombre, e.nombre');
		$query = $this->db->get();
		return $query->result();
	}

	public function usuarios_asistencia_division($division_id) {
		$this->db->select('u.id, u.usuario, p.cuil as cuil, CONCAT(p.apellido, \', \', p.nombre) as persona, rol.nombre as rol, e.nombre as entidad, u.active, u.id')
			->from('usuario u')
			->join('usuario_persona up', 'up.usuario_id=u.id')
			->join('usuario_rol ur', 'ur.usuario_id=u.id', 'left')
			->join('rol', 'ur.rol_id=rol.id', 'left')
			->join('entidad_tipo et', 'rol.entidad_tipo_id=et.id', 'left')
			->join('entidad e', 'e.tabla=et.tabla and e.id=ur.entidad_id', 'left')
			->join('persona p', "p.cuil = up.cuil", 'left')
			->where('rol.codigo', ROL_ASISTENCIA_DIVISION)
			->where('ur.entidad_id', $division_id)
			->order_by('rol.nombre, e.nombre');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_usuarios_cursada($cursada_id) {
		$this->db->select('u.id, u.usuario, p.cuil as cuil, CONCAT(p.apellido, \', \', p.nombre) as persona, rol.nombre as rol, e.nombre as entidad, u.active, u.id')
			->from('usuario u')
			->join('usuario_persona up', 'up.usuario_id=u.id')
			->join('usuario_rol ur', 'ur.usuario_id=u.id', 'left')
			->join('rol', 'ur.rol_id=rol.id', 'left')
			->join('entidad_tipo et', 'rol.entidad_tipo_id=et.id', 'left')
			->join('entidad e', 'e.tabla=et.tabla and e.id=ur.entidad_id', 'left')
			->join('persona p', "p.cuil = up.cuil", 'left')
			->where('rol.codigo', ROL_DOCENTE_CURSADA)
			->where('ur.entidad_id', $cursada_id)
			->order_by('rol.nombre, e.nombre');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_usuarios($rol_codigo, $entidad_id) {
		$this->db->select('u.id, u.usuario, p.cuil as cuil, CONCAT(p.apellido, \', \', p.nombre) as persona, rol.nombre as rol, e.nombre as entidad, u.active, u.id')
			->from('usuario u')
			->join('usuario_persona up', 'up.usuario_id=u.id')
			->join('usuario_rol ur', 'ur.usuario_id=u.id', 'left')
			->join('rol', 'ur.rol_id=rol.id', 'left')
			->join('entidad_tipo et', 'rol.entidad_tipo_id=et.id', 'left')
			->join('entidad e', 'e.tabla=et.tabla and e.id=ur.entidad_id', 'left')
			->join('persona p', "p.cuil = up.cuil", 'left')
			->where('rol.codigo', $rol_codigo)
			->where('ur.entidad_id', $entidad_id)
			->order_by('rol.nombre, e.nombre');
		$query = $this->db->get();
		return $query->result();
	}
}
//			->where("(servicio.liquidacion_regimen_id!=cargo.regimen_id OR servicio.liquidacion_carga_horaria!=cargo.carga_horaria)")
