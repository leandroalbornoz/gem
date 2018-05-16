<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_rol extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('usuario_rol_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_PRIVADA, ROL_SEOS, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_GRUPO_ESCUELA_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_JEFE_LIQUIDACION, ROL_LIQUIDACION, ROL_DIR_ESCUELA);
		if (in_array($this->rol->codigo, array(ROL_CONSULTA_LINEA, ROL_REGIONAL))) {
			$this->edicion = FALSE;
		}
		$this->roles_admin = array(ROL_ADMIN, ROL_USI);
		$this->modulos_permitidos = array(ROL_MODULO_ADMINISTRADOR_DE_JUNTAS);
		$this->nav_route = 'usuarios/usuario_rol';
	}

	public function modal_agregar($usuario_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $usuario_id == NULL || !ctype_digit($usuario_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('usuario_model');
		$usuario = $this->usuario_model->get_one($usuario_id);
		if (empty($usuario)) {
			$this->modal_error('No se encontró el registro a agregar', 'Registro no encontrado');
			return;
		}
		$this->load->model('rol_model');
		switch ($this->rol->codigo) {
			case ROL_ADMIN:
				$this->array_rol_control = $array_rol = $this->get_array('rol', 'nombre', 'id', array('where' => array("id NOT IN('5','26','27')"), 'sort_by' => 'id'), array('' => '-- Seleccione rol --'));
				break;
			case ROL_USI:
				$this->array_rol_control = $array_rol = $this->get_array('rol', 'nombre', 'id', array('where' => array("id NOT IN('1','5','26','27')"), 'sort_by' => 'id'), array('' => '-- Seleccione rol --'));
				break;
			case ROL_LINEA:
			case ROL_GRUPO_ESCUELA:
			case ROL_GRUPO_ESCUELA_CONSULTA:
			case ROL_CONSULTA_LINEA:
			case ROL_REGIONAL:
			case ROL_PRIVADA:
			case ROL_SEOS:
				$this->array_rol_control = $array_rol = $this->get_array('rol', 'nombre', 'id', array(
					'where' => array("codigo IN ('" . ROL_DIR_ESCUELA . "', '" . ROL_SUPERVISION . "', '" . ROL_ESCUELA_ALUM . "', '" . ROL_ESCUELA_CAR . "')"),
					'sort_by' => 'id'
					), array('' => '-- Seleccione rol --'));
				break;
			case ROL_DIR_ESCUELA:
				$this->load->model('bono_escuelas_model');
				$escuela = $this->bono_escuelas_model->get(array(
					'gem_id' => $this->rol->entidad_id
				));
				if (empty($escuela)) {
					$this->array_rol_control = $array_rol = $this->get_array('rol', 'nombre', 'id', array(
						'where' => array("codigo IN ('" . ROL_ESCUELA_ALUM . "', '" . ROL_ESCUELA_CAR . "')"),
						'sort_by' => 'id'
						), array('' => '-- Seleccione rol --'));
				} else {
					$this->array_rol_control = $array_rol = $this->get_array('rol', 'nombre', 'id', array(
						'where' => array("codigo IN ('" . ROL_ESCUELA_ALUM . "', '" . ROL_ESCUELA_CAR . "', '" . ROL_BONO_SECUNDARIO . "')"),
						'sort_by' => 'id'
						), array('' => '-- Seleccione rol --'));
				}
				break;
			case ROL_LIQUIDACION:
			case ROL_JEFE_LIQUIDACION:
				$this->array_rol_control = $array_rol = $this->get_array('rol', 'nombre', 'id', array(
					'where' => array("codigo IN ('" . ROL_CONSULTA_LINEA . "', '" . ROL_REGIONAL . "', '" . ROL_LINEA . "',  '" . ROL_GRUPO_ESCUELA . "',  '" . ROL_GRUPO_ESCUELA_CONSULTA . "', '" . ROL_DIR_ESCUELA . "', '" . ROL_SUPERVISION . "', '" . ROL_AREA . "', '" . ROL_ESCUELA_ALUM . "', '" . ROL_ESCUELA_CAR . "')"),
					'sort_by' => 'id'
					), array('' => '-- Seleccione rol --'));
				break;
			case ROL_MODULO:
				$this->array_rol_control = $array_rol = $this->get_array('rol', 'nombre', 'id', array(
					'where' => array("codigo IN ('" . ROL_MODULO . "', '" . ROL_JUNTAS . "')"),
					'sort_by' => 'id'
					), array('' => '-- Seleccione rol --'));
				break;
			default:
		}
		if (empty($_POST['rol'])) {
			$this->array_entidad_control = $array_entidad = array('' => '');
		} else {
			$rol = $this->rol_model->get(array('id' => $_POST['rol']));
			if ($rol->codigo === ROL_ADMIN) {
				$this->array_entidad_control = $array_entidad = array('' => 'Administrador');
			} elseif ($rol->codigo === ROL_USI) {
				$this->array_entidad_control = $array_entidad = array('' => 'USI');
			} elseif ($rol->codigo === ROL_PORTAL) {
				$this->array_entidad_control = $array_entidad = array('' => 'Portal');
			} elseif ($rol->codigo === ROL_PRIVADA) {
				$this->array_entidad_control = $array_entidad = array('' => 'Dir. Educación Privada');
			} elseif ($rol->codigo === ROL_SEOS) {
				$this->array_entidad_control = $array_entidad = array('' => 'Dir. SEOS');
			} elseif ($rol->codigo === ROL_CONSULTA) {
				$this->array_entidad_control = $array_entidad = array('' => 'Consulta General');
			} elseif ($rol->codigo === ROL_JEFE_LIQUIDACION) {
				$this->array_entidad_control = $array_entidad = array('' => 'Sup. Liquidaciones');
			} elseif ($rol->codigo === ROL_LIQUIDACION) {
				$this->array_entidad_control = $array_entidad = array('' => 'Liquidaciones');
			} elseif ($rol->codigo === ROL_TITULO) {
				$this->array_entidad_control = $array_entidad = array('' => 'Título');
			} else {
				$this->load->model('entidad_model');
				$entidades = $this->entidad_model->get_entidades($rol->id);
				$array_entidad = array();
				foreach ($entidades as $entidad) {
					$array_entidad[$entidad->id] = $entidad->nombre;
				}
				$this->array_entidad_control = $array_entidad;
			}
			if ($this->rol->codigo === ROL_DIR_ESCUELA) {
				if ($rol->codigo === ROL_ESCUELA_ALUM || $rol->codigo === ROL_ESCUELA_CAR || $rol->codigo === ROL_BONO_SECUNDARIO) {
					$_POST['entidad'] = $this->rol->entidad_id;
				}
			}
		}
		$this->set_model_validation_rules($this->usuario_rol_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$this->load->model('bono_escuelas_model');
				if ($this->input->post('rol') == 20) {
					if ($this->rol->codigo == ROL_DIR_ESCUELA || $this->rol->codigo == ROL_MODULO) {
						$trans_ok &= $this->usuario_rol_model->create(array(
							'usuario_id' => $usuario->id,
							'rol_id' => $this->input->post('rol'),
							'entidad_id' => $this->input->post('entidad'),
							'activo' => '0'
						));
					} else {
						$trans_ok &= $this->usuario_rol_model->create(array(
							'usuario_id' => $usuario->id,
							'rol_id' => $this->input->post('rol'),
							'entidad_id' => $this->input->post('entidad'),
							'activo' => '0'
						));
					}
				} else {
					$trans_ok &= $this->usuario_rol_model->create(array(
						'usuario_id' => $usuario->id,
						'rol_id' => $this->input->post('rol'),
						'entidad_id' => $this->input->post('entidad'),
						'activo' => '0'
					));
				}

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->usuario_rol_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->usuario_rol_model->get_error());
				}
				redirect("usuario/editar_roles/$usuario->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("usuario/editar_roles/$usuario->id", 'refresh');
			}
		}

		$this->usuario_rol_model->fields['usuario']['value'] = $usuario->usuario;
		$this->usuario_rol_model->fields['rol']['array'] = $array_rol;
		$this->usuario_rol_model->fields['entidad']['array'] = $array_entidad;
		$data['fields'] = $this->build_fields($this->usuario_rol_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar rol a usuario';
		$this->load->view('usuario_rol/usuario_rol_modal_abm', $data);
	}

	public function modal_simular() {
		if (!accion_permitida($this->rol, $this->roles_admin)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('usuario_model');
		$usuario = $this->usuario_model->get_one($this->usuario);
		if (empty($usuario)) {
			$this->modal_error('No se encontró el registro del usuario', 'Registro no encontrado');
			return;
		}
		$this->load->model('rol_model');
		if ($this->rol->codigo === ROL_USI) {
			$this->array_rol_control = $array_rol = $this->get_array('rol', 'nombre', 'id', array('where' => array("id NOT IN('1','5','26','27')"), 'sort_by' => 'id'));
		} else {
			$this->array_rol_control = $array_rol = $this->get_array('rol', 'nombre', 'id', array('where' => array("id NOT IN('24','5','26','27')"), 'sort_by' => 'id'));
		}
		if (empty($_POST['rol'])) {
			$this->array_entidad_control = $array_entidad = array('' => '');
		} else {
			$rol_id = $_POST['rol'];
			$rol = $this->rol_model->get(array('id' => $_POST['rol']));
			if ($rol_id == 1) {
				$this->array_entidad_control = $array_entidad = array('' => 'Administrador');
			} elseif ($rol_id == 18) {
				$this->array_entidad_control = $array_entidad = array('' => 'USI');
			} elseif ($rol_id == 9) {
				$this->array_entidad_control = $array_entidad = array('' => 'Dir. Educación Privada');
			} elseif ($rol_id == 15) {
				$this->array_entidad_control = $array_entidad = array('' => 'Dir. SEOS');
			} elseif ($rol_id == 10) {
				$this->array_entidad_control = $array_entidad = array('' => 'Consulta General');
			} elseif ($rol_id == 11) {
				$this->array_entidad_control = $array_entidad = array('' => 'Sup. Liquidaciones');
			} elseif ($rol_id == 12) {
				$this->array_entidad_control = $array_entidad = array('' => 'Liquidaciones');
			} elseif ($rol_id == 21) {
				$this->array_entidad_control = $array_entidad = array('' => 'Título');
			} else {
				$this->load->model('entidad_model');
				$entidades = $this->entidad_model->get_entidades($rol->id);
				$array_entidad = array();
				foreach ($entidades as $entidad) {
					$array_entidad[$entidad->id] = $entidad->nombre;
				}
				$this->array_entidad_control = $array_entidad;
			}
		}
		$this->set_model_validation_rules($this->usuario_rol_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$rol_id = $this->input->post('rol');
				$entidad_id = $this->input->post('entidad');
				if ($rol_id == '20') {
					$rol = $this->usuarios_model->get_rol_simulado_bono($rol_id, $entidad_id);
				} else {
					$rol = $this->usuarios_model->get_rol_simulado($rol_id, $entidad_id);
				}
				if (!empty($rol)) {
					$this->session->set_userdata('rol', $rol);
				}

				$this->session->set_flashdata('message', $this->usuario_rol_model->get_msg());
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect("escritorio", 'refresh');
		}

		$this->usuario_rol_model->fields['usuario']['value'] = $usuario->usuario;
		$this->usuario_rol_model->fields['rol']['array'] = $array_rol;
		$this->usuario_rol_model->fields['entidad']['array'] = $array_entidad;
		$data['fields'] = $this->build_fields($this->usuario_rol_model->fields);
		$data['txt_btn'] = 'Simular';
		$data['title'] = 'Simular rol';
		$this->load->view('usuario_rol/usuario_rol_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL, $usuario_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos) || $id == NULL || !ctype_digit($id) || $usuario_id == NULL || !ctype_digit($usuario_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$usuario_rol = $this->usuario_rol_model->get_one($id);
		if (empty($usuario_rol)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		switch ($this->rol->codigo) {
			case ROL_ADMIN:
				break;
			case ROL_USI:
				if ($usuario_rol->codigo === ROL_ADMIN) {
					$this->modal_error('No puede eliminar roles de Administrador', 'Acción no permitida');
					return;
				}
				break;
			case ROL_DIR_ESCUELA:
				switch ($usuario_rol->codigo) {
					case ROL_ESCUELA_ALUM:
					case ROL_ESCUELA_CAR:
						$this->load->model('escuela_model');
						if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $this->escuela_model->get_one($usuario_rol->entidad_id))) {
							$this->modal_error('Sólo puede eliminar roles asociados a su Escuela', 'Acción no permitida');
							return;
						}
						break;
					case ROL_BONO_SECUNDARIO:
						$this->load->model('escuela_model');
						if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $this->escuela_model->get_one($usuario_rol->entidad_id))) {
							$this->modal_error('Sólo puede eliminar roles asociados a su Escuela', 'Acción no permitida');
							return;
						}
						break;
					default:
						$this->modal_error('Sólo puede eliminar roles asociados a su Escuela', 'Acción no permitida');
						return;
				}
				break;
			case ROL_LINEA:
			case ROL_GRUPO_ESCUELA:
			case ROL_GRUPO_ESCUELA_CONSULTA:
			case ROL_CONSULTA_LINEA:
			case ROL_REGIONAL:
				switch ($usuario_rol->codigo) {
					case ROL_DIR_ESCUELA:
					case ROL_SDIR_ESCUELA:
					case ROL_SEC_ESCUELA:
					case ROL_ESCUELA_ALUM:
					case ROL_ESCUELA_CAR:
						$this->load->model('escuela_model');
						if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $this->escuela_model->get_one($usuario_rol->entidad_id))) {
							$this->modal_error('Sólo puede eliminar roles de escuelas/supervisiones asociadas a su Linea', 'Acción no permitida');
							return;
						}
						break;
					case ROL_SUPERVISION:
						$this->load->model('supervision_model');
						if (!$this->usuarios_model->verificar_permiso('supervision', $this->rol, $this->supervision_model->get_one($usuario_rol->entidad_id))) {
							$this->modal_error('Sólo puede eliminar roles de escuelas/supervisiones asociadas a su Linea', 'Acción no permitida');
							return;
						}
						break;
					default:
						$this->modal_error('Sólo puede eliminar roles de escuelas/supervisiones asociadas a su Linea', 'Acción no permitida');
						return;
				}
				break;
			case ROL_LIQUIDACION:
				switch ($usuario_rol->codigo) {
					case ROL_LINEA:
					case ROL_GRUPO_ESCUELA:
					case ROL_GRUPO_ESCUELA_CONSULTA:
					case ROL_CONSULTA_LINEA:
					case ROL_SUPERVISION:
					case ROL_REGIONAL:
					case ROL_DIR_ESCUELA:
					case ROL_SDIR_ESCUELA:
					case ROL_SEC_ESCUELA:
					case ROL_ESCUELA_ALUM:
					case ROL_ESCUELA_CAR:
					case ROL_AREA:
						break;
					default:
						$this->modal_error('No tiene permisos para realizar esta acción', 'Acción no permitida');
						return;
				}
				break;
			case ROL_JEFE_LIQUIDACION:
				switch ($usuario_rol->codigo) {
					case ROL_LINEA:
					case ROL_GRUPO_ESCUELA:
					case ROL_GRUPO_ESCUELA_CONSULTA:
					case ROL_CONSULTA_LINEA:
					case ROL_SUPERVISION:
					case ROL_REGIONAL:
					case ROL_DIR_ESCUELA:
					case ROL_SDIR_ESCUELA:
					case ROL_SEC_ESCUELA:
					case ROL_ESCUELA_ALUM:
					case ROL_ESCUELA_CAR:
					case ROL_LIQUIDACION:
						break;
					default:
						$this->modal_error('No tiene permisos para realizar esta acción', 'Acción no permitida');
						return;
				}
				break;
			case ROL_PRIVADA:
				switch ($usuario_rol->codigo) {
					case ROL_DIR_ESCUELA:
					case ROL_SDIR_ESCUELA:
					case ROL_SEC_ESCUELA:
					case ROL_ESCUELA_ALUM:
					case ROL_ESCUELA_CAR:
						$this->load->model('escuela_model');
						if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $this->escuela_model->get_one($usuario_rol->entidad_id))) {
							$this->modal_error('Sólo puede eliminar roles de escuelas/supervisiones de Educación Privada', 'Acción no permitida');
							return;
						}
						break;
					case ROL_SUPERVISION:
						$this->load->model('supervision_model');
						if (!$this->usuarios_model->verificar_permiso('supervision', $this->rol, $this->supervision_model->get_one($usuario_rol->entidad_id))) {
							$this->modal_error('Sólo puede eliminar roles de escuelas/supervisiones de Educación Privada', 'Acción no permitida');
							return;
						}
						break;
					default:
						$this->modal_error('Sólo puede eliminar roles de escuelas/supervisiones de Educación Privada', 'Acción no permitida');
						return;
				}
				break;
			case ROL_SEOS:
				switch ($usuario_rol->codigo) {
					case ROL_DIR_ESCUELA:
					case ROL_SDIR_ESCUELA:
					case ROL_SEC_ESCUELA:
					case ROL_ESCUELA_ALUM:
					case ROL_ESCUELA_CAR:
						$this->load->model('escuela_model');
						if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $this->escuela_model->get_one($usuario_rol->entidad_id))) {
							$this->modal_error('Sólo puede eliminar roles de escuelas/supervisiones de SEOS', 'Acción no permitida');
							return;
						}
						break;
					case ROL_SUPERVISION:
						$this->load->model('supervision_model');
						if (!$this->usuarios_model->verificar_permiso('supervision', $this->rol, $this->supervision_model->get_one($usuario_rol->entidad_id))) {
							$this->modal_error('Sólo puede eliminar roles de escuelas/supervisiones de SEOS', 'Acción no permitida');
							return;
						}
						break;
					default:
						$this->modal_error('Sólo puede eliminar roles de escuelas/supervisiones de SEOS', 'Acción no permitida');
						return;
				}
				break;
			CASE ROL_MODULO:
				if ($this->rol->entidad_id === ROL_MODULO_ADMINISTRADOR_DE_JUNTAS) {
					if ($usuario_rol->codigo !== ROL_JUNTAS) {
						$this->modal_error('Acceso denegado', 'Acción no permitida');
						return;
					}
				}
				break;
			default:
				$this->modal_error('Acceso denegado', 'Acción no permitida');
				return;
		}
		$this->load->model('usuario_model');
		$usuario = $this->usuario_model->get(array('id' => $usuario_id));
		$usuario_rol->usuario = $usuario->usuario;
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->usuario_rol_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->usuario_rol_model->get_msg());
				redirect("usuario/editar_roles/$usuario_id", 'refresh');
			}
		}
		$data['fields'] = $this->build_fields($this->usuario_rol_model->fields, $usuario_rol, TRUE);
		$data['usuario_rol'] = $usuario_rol;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar rol de usuario';
		$this->load->view('usuario_rol/usuario_rol_modal_abm', $data);
	}
}
/* End of file Usuario_rol.php */
/* Location: ./application/controllers/Usuario_rol.php */