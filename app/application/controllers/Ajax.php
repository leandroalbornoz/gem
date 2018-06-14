<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ASISTENCIA_DIVISION)); //@TODO ver permisos
	}

	public function set_menu_collapse() {
		$this->form_validation->set_rules('value', 'Valor', 'integer|required');
		if ($this->form_validation->run() === TRUE) {
			$this->session->set_userdata('menu_collapse', $this->input->post('value'));
			echo json_encode(array('ok' => 'ok'));
			return;
		}
		echo json_encode(array('error' => 'error'));
		return;
	}

	public function get_carreras($escuela_id, $division_id = '') {
		if (in_array($this->rol->codigo, $this->roles_permitidos)) {
			$options_carrera = array(
				'select' => array('carrera.id', 'carrera.descripcion'),
				'join' => array(
					array('escuela_carrera', 'escuela_carrera.carrera_id=carrera.id')
				),
				'where' => array(array('column' => 'escuela_carrera.escuela_id', 'value' => $escuela_id))
			);
			if ($division_id != '') {
				$options_carrera['join'][] = array('division', 'division.carrera_id=carrera.id');
				$options_carrera['where'][] = array('column' => 'division.id', 'value' => $division_id);
			}
			$this->load->model('carrera_model');
			$carreras_query = $this->carrera_model->get($options_carrera);
			if (!empty($carreras_query)) {
				echo json_encode($carreras_query);
				return;
			}
			echo json_encode(array('status' => 'error'));
		} else {
			show_404();
		}
	}

	public function get_turno($division_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('division_model');
		$turno = $this->division_model->get(array(
			'id' => $division_id
		));

		if (!empty($turno)) {
			echo json_encode($turno);
			return;
		}
		echo json_encode(array('status' => 'error'));
	}

	public function get_divisiones($escuela_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get(array('id' => $escuela_id));
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		$this->form_validation->set_rules('turno', 'Turno', 'integer|required');
		$this->form_validation->set_rules('cursos[]', 'Cursos', 'integer|required');
		if ($this->form_validation->run() === TRUE) {
			$turno = $this->input->post('turno');
			$cursos = $this->input->post('cursos');
			$this->load->model('division_model');
			$divisiones = $this->division_model->get(array(
				'select' => array('division.id', "CONCAT(curso.descripcion, ' ', division.division) as division"),
				'join' => array(
					array('curso', 'curso.id=division.curso_id', '')
				),
				'escuela_id' => $escuela->id,
				'turno_id' => $turno,
				'where' => array(
					'curso_id IN (' . implode(',', $cursos) . ')'
				),
				'sort_by' => 'curso.id, division.division'
			));
			if (!empty($divisiones)) {
				echo json_encode($divisiones);
			} else {
				echo json_encode(array());
			}
			return;
		}
		echo json_encode(array('status' => 'error'));
	}

	public function get_entidades($rol_id) {
		if (!in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_GRUPO_ESCUELA_CONSULTA, ROL_PRIVADA, ROL_SEOS, ROL_LIQUIDACION, ROL_JEFE_LIQUIDACION, ROL_DIR_ESCUELA, ROL_MODULO, ROL_PORTAL, ROL_JUNTAS))) {
			show_404();
		}
		if ($this->rol->codigo === ROL_ADMIN || $this->rol->codigo === ROL_USI) {
			if ($rol_id == 1) {
				echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Administrador')));
				return;
			} elseif ($rol_id == 18) {
				echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Soporte al Usuario')));
				return;
			} elseif ($rol_id == 9) {
				echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Dir. Educación Privada')));
				return;
			} elseif ($rol_id == 15) {
				echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Dir. SEOS')));
				return;
			} elseif ($rol_id == 10) {
				echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Consulta General')));
				return;
			} elseif ($rol_id == 11) {
				echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Sup. Liquidaciones')));
				return;
			} elseif ($rol_id == 12) {
				echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Liquidaciones')));
				return;
			} elseif ($rol_id == 24) {
				echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Portal')));
				return;
			} elseif ($rol_id == 21) {
				echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Título')));
				return;
			}
		}
		$this->load->model('entidad_model');
		$entidades = $this->entidad_model->get_entidades($rol_id, $this->rol);
		if (!empty($entidades)) {
			echo json_encode($entidades);
			return;
		}
		echo json_encode(array('status' => 'error'));
	}

	public function get_entidades_msj($rol_id) {
		if ($rol_id == 1) {
			echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Administrador')));
			return;
		} elseif ($rol_id == 18) {
			echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Soporte al Usuario')));
			return;
		} elseif ($rol_id == 9) {
			echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Dir. Educación Privada')));
			return;
		} elseif ($rol_id == 15) {
			echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Dir. SEOS')));
			return;
		} elseif ($rol_id == 10) {
			echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Consulta General')));
			return;
		} elseif ($rol_id == 11) {
			echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Sup. Liquidaciones')));
			return;
		} elseif ($rol_id == 12) {
			echo json_encode(array((object) array('id' => 'NULL', 'nombre' => 'Liquidaciones')));
			return;
		}
		$this->load->model('entidad_model');
		$entidades = $this->entidad_model->get_entidades_msj($rol_id, $this->rol);
		if (!empty($entidades)) {
			echo json_encode($entidades);
			return;
		}
		echo json_encode(array('status' => 'error'));
	}

	public function get_escuelas() {
		$supervisiones = $this->input->post('supervisiones');
		$this->load->model('escuela_model');

		$escuelas = $this->db->query(
				"SELECT escuela.id, CONCAT(escuela.numero,' - ',escuela.nombre) as nombre_largo
				FROM `escuela`
				JOIN `supervision` ON `escuela`.`supervision_id` = `supervision`.`id`
				WHERE `supervision`.`id` IN ?", array(explode(',', $supervisiones)))->result();

		if (!empty($escuelas)) {
			echo json_encode($escuelas);
			return;
		}
		echo json_encode(array('status' => 'error'));
	}

	public function get_destinos($tipo_destino = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_404();
		}
		if (empty($tipo_destino)) {
			echo '';
			return;
		}
		$this->load->model('entidad_model');
		if ($tipo_destino === 'escuela') {
			$entidades = $this->entidad_model->get_entidades(2);
		} else if ($tipo_destino === 'area') {
			$entidades = $this->entidad_model->get_entidades(8);
		}
		if (!empty($entidades)) {
			echo json_encode($entidades);
			return;
		}
		echo json_encode(array('status' => 'error'));
	}

	public function get_materias($nivel_id = '0', $division_id = '0', $carrera_id = '0') {
		if (in_array($this->rol->codigo, $this->roles_permitidos)) {
			if (empty($division_id)) {
				$division_id = '0';
			}
			if (empty($carrera_id)) {
				$carrera_id = '0';
			}
			$options_espacio_curricular = array('select' => array('espacio_curricular.id', 'materia.descripcion'),
				'join' => array(array('materia', 'materia.id=espacio_curricular.materia_id')));
			if ($division_id === '0' && $carrera_id === '0') {
				$options_espacio_curricular['select'] = array('MIN(espacio_curricular.id) id', 'materia.descripcion');
				$options_espacio_curricular['where'] = array('carrera_id IS NULL', "curso.nivel_id=$nivel_id");
				$options_espacio_curricular['join'][] = array('curso', 'curso.id=espacio_curricular.curso_id', 'left');
				$options_espacio_curricular['group_by'] = 'espacio_curricular.materia_id';
			} elseif ($division_id === '0') {
				$options_espacio_curricular['select'] = array('MIN(espacio_curricular.id) id', 'materia.descripcion');
				$options_espacio_curricular['where'] = array("(carrera_id IS NULL OR carrera_id = $carrera_id)", "curso.nivel_id=$nivel_id");
				$options_espacio_curricular['join'][] = array('curso', 'curso.id=espacio_curricular.curso_id', 'left');
				$options_espacio_curricular['group_by'] = 'espacio_curricular.materia_id';
			} else {
				$this->load->model('division_model');
				$division = $this->division_model->get(array('id' => $division_id));
				if (empty($division->carrera_id)) {
					$options_espacio_curricular['where'] = array('carrera_id IS NULL');
				} else {
					$options_espacio_curricular['where'] = array("(carrera_id IS NULL OR carrera_id = $division->carrera_id)");
				}
				$options_espacio_curricular['curso_id'] = $division->curso_id;
			}
			$this->load->model('espacio_curricular_model');
			$materias_query = $this->espacio_curricular_model->get($options_espacio_curricular);
			if (!empty($materias_query)) {
				echo json_encode($materias_query);
				return;
			}
			echo json_encode(array('status' => 'error'));
		} else {
			show_404();
		}
	}

	public function get_servicios() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$cargo_id = $this->input->get('cargo_id');
		if (empty($cargo_id)) {
			show_error('No se ha recibido un cargo', 500, 'Acción no autorizada');
		}
		$this->load->model('cargo_model');
		$cargo = $this->cargo_model->get(array('id' => $cargo_id));
		if (empty($cargo)) {
			show_error('Cargo no encontrado', 500, 'Acción no autorizada');
		}
		$this->load->model('servicio_model');
		$servicios = $this->servicio_model->get(array('cargo_id' => $cargo->id,
			'join' => array(
				array('servicio_funcion', 'servicio_funcion.servicio_id=servicio.id AND servicio_funcion.fecha_hasta IS NULL', 'left', array('servicio_funcion.destino as funcion_destino', 'servicio_funcion.norma as funcion_norma', 'servicio_funcion.tarea as funcion_tarea', 'servicio_funcion.carga_horaria as funcion_carga_horaria', 'servicio_funcion.fecha_desde as funcion_desde')),
				array('funcion', 'servicio_funcion.funcion_id=funcion.id', 'left', array('COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion_detalle')),
				array('situacion_revista', 'situacion_revista.id=servicio.situacion_revista_id', 'left', array('situacion_revista.descripcion as situacion_revista')),
				array('persona', 'persona.id=servicio.persona_id', 'left', array('persona.apellido', 'persona.nombre', 'persona.documento')),
			),
			'group_by' => 'servicio.id',
			'sort_by' => 'servicio.fecha_alta'
		));
		$this->load->model('servicio_novedad_model');
		$novedades = $this->servicio_novedad_model->get(array(
			'join' => array(
				array('servicio', 'servicio_novedad.servicio_id=servicio.id'),
				array('novedad_tipo', 'novedad_tipo.id=servicio_novedad.novedad_tipo_id', 'left', array('novedad_tipo.articulo', 'novedad_tipo.inciso')),
			),
			'ames' => date('Ym'),
			'where' => array('planilla_baja_id IS NULL', array('column' => 'servicio.cargo_id', 'value' => $cargo->id))
		));
		$novedades_servicios = array();
		if (!empty($novedades)) {
			foreach ($novedades as $novedad) {
				$novedades_servicios[$novedad->servicio_id][] = $novedad;
			}
		}
		if (!empty($servicios)) {
			foreach ($servicios as $servicio) {
				if (isset($novedades_servicios[$servicio->id])) {
					$servicio->novedad_desde = '';
					$servicio->novedad_hasta = '';
					$servicio->novedad_articulo = '';
					foreach ($novedades_servicios[$servicio->id] as $novedad) {
						$servicio->novedad_desde .= (empty($servicio->novedad_desde) ? '' : '<br>') . (new DateTime($novedad->fecha_desde))->format('d/m');
						$servicio->novedad_hasta .= (empty($servicio->novedad_hasta) ? '' : '<br>') . ($novedad->articulo === 'AA' ? '' : (new DateTime($novedad->fecha_hasta))->format('d/m'));
						$servicio->novedad_articulo .= (empty($servicio->novedad_articulo) ? '' : '<br>') . ($novedad->articulo === 'AA' ? 'Alta' : "$novedad->articulo-$novedad->inciso");
					}
				}
			}
			echo json_encode($servicios);
			return;
		} else {
			echo json_encode(array());
		}
	}

	public function get_roles() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$usuario_id = $this->input->get('usuario_id');
		if (empty($usuario_id)) {
			show_error('No se ha recibido un usuario', 500, 'Acción no autorizada');
		}
		$roles = $this->usuarios_model->get_roles($usuario_id);
		if (!empty($roles)) {
			echo json_encode($roles);
			return;
		} else {
			echo json_encode(array());
		}
	}

	public function get_persona() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$documento_tipo = $this->input->get('documento_tipo');
		$documento = $this->input->get('documento');
		$id = $this->input->get('id');
		if (empty($documento) || empty($documento_tipo)) {
			echo json_encode('');
			return;
		} else {
			$this->load->model('persona_model');
			if (empty($id)) {
				$persona = $this->persona_model->get(array(
					'documento_tipo_id' => $documento_tipo,
					'documento' => $documento
				));
			} else {
				$persona = $this->persona_model->get(array(
					'documento_tipo_id' => $documento_tipo,
					'documento' => $documento,
					'id !=' => $id
				));
			}
		}
		if (!empty($persona)) {
			$persona[0]->fecha_nacimiento = (new DateTime($persona[0]->fecha_nacimiento))->format('d/m/Y');
			echo json_encode($persona[0]);
		} else {
			echo json_encode('');
		}
	}

	public function get_persona_tem() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$documento_tipo = $this->input->get('documento_tipo');
		$documento = $this->input->get('documento');
		$id = $this->input->get('id');
		$result = array();
		if (empty($documento) || empty($documento_tipo)) {
			$result['errors'] = 'No se recibió datos';
			$result['status'] = 'error';
		} else {
			$persona_tem = $this->db->select('*')
				->from('tem_personal')
				->where('documento', $documento)
				->get()
				->row();
			$this->load->model('persona_model');
			$persona = $this->persona_model->get(array(
				'documento_tipo_id' => $documento_tipo,
				'documento' => $documento
			));
			if (!empty($persona_tem)) {
				if (!empty($persona)) {
					$horas_tem = $this->db->select('SUM(cargo.carga_horaria) horas_tem')
						->from('servicio')
						->join('persona', 'persona.id = servicio.persona_id')
						->join('cargo', 'cargo.id = servicio.cargo_id')
						->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id')
						->where('persona.id', $persona[0]->id)
						->where('condicion_cargo.planilla_modalidad_id', 2)
						->where('servicio.fecha_baja IS NULL')
						->get()
						->row();
					$persona[0]->fecha_nacimiento = (new DateTime($persona[0]->fecha_nacimiento))->format('d/m/Y');
					$result['persona_gem'] = $persona[0];
					$result['persona_tem'] = $persona_tem;
					$result['horas_tem'] = empty($horas_tem->horas_tem) ? 0 : $horas_tem->horas_tem;
				} else {
					$result['persona_gem'] = '';
					$result['persona_tem'] = $persona_tem;
					$result['horas_tem'] = 0;
				}
				$result['status'] = 'success';
			} else {
				$result['errors'] = 'Persona no válida';
				$result['status'] = 'error';
			}
		}

		echo json_encode($result);
	}

	public function get_indocumentado() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$documento_tipo = $this->input->get('documento_tipo');
		$id = $this->input->get('id');
		if (empty($documento_tipo) || $documento_tipo !== '8') {
			echo json_encode('');
			return;
		} else {
			$this->load->model('persona_model');
			if (!empty($id)) {
				$persona = $this->persona_model->get(array('id' => $id));
			}
			if (!empty($persona) && $persona->documento_tipo_id === $documento_tipo) {
				echo json_encode($persona->documento);
			} else {
				$persona = $this->persona_model->get(array(
					'select' => 'COALESCE(max(documento)+1, 1) as documento',
					'documento_tipo_id' => $documento_tipo,
				));
				echo json_encode($persona[0]->documento);
			}
		}
	}

	public function get_servicios_p() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$documento_tipo = $this->input->get('documento_tipo');
		$documento = $this->input->get('documento');
		$id = $this->input->get('id');
		if (empty($documento) || empty($documento_tipo)) {
			echo json_encode('');
			return;
		} else {
			$this->load->model('persona_model');
			if (empty($id)) {
				$persona = $this->persona_model->get(array(
					'documento_tipo_id' => $documento_tipo,
					'documento' => $documento
				));
			} else {
				$persona = $this->persona_model->get(array(
					'documento_tipo_id' => $documento_tipo,
					'documento' => $documento,
					'id !=' => $id
				));
			}
		}
		if (!empty($persona)) {
			$this->load->model('suplementarias/suple_persona_model');
			$persona[0]->fecha_nacimiento = (new DateTime($persona[0]->fecha_nacimiento))->format('d/m/Y');
			$persona[0]->servicios = $this->suple_persona_model->get_servicios_persona($persona[0]->id);
			echo json_encode($persona[0]);
		} else {
			echo json_encode('');
		}
	}

	public function get_suples_p() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$documento_tipo = $this->input->get('documento_tipo');
		$documento = $this->input->get('documento');
		$id = $this->input->get('id');
		if (empty($documento) || empty($documento_tipo)) {
			echo json_encode('');
			return;
		} else {
			$this->load->model('persona_model');
			if (empty($id)) {
				$persona = $this->persona_model->get(array(
					'documento' => $documento
				));
			} else {
				$persona = $this->persona_model->get(array(
					'documento' => $documento,
					'id !=' => $id
				));
			}
		}
		if (!empty($persona)) {
			$this->load->model('suplementarias/suple_persona_model');
			$persona[0]->suples_p = $this->suple_persona_model->get_suple_existente($persona[0]->id);
			echo json_encode($persona[0]);
		} else {
			echo json_encode('');
		}
	}

	public function get_conceptos() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$suple_persona_id = $this->input->get('suple_persona_id');
		if (empty($suple_persona_id)) {
			show_error('No se ha recibido una persona', 500, 'Acción no autorizada');
		}
		$this->load->model('suplementarias/suple_persona_concepto_model');
		$conceptos = $this->suple_persona_concepto_model->get(array(
			'join' => array(
				array('suple_concepto', 'suple_concepto.id=suple_persona_concepto.concepto_id', '', array('suple_concepto.codigo', 'suple_concepto.descripcion', 'suple_concepto.tipo')),
			),
			'suple_persona_id' => $suple_persona_id,
			'where' => array('importe != 0'),
			'sort_by' => "CASE tipo WHEN 'HR' THEN 1 WHEN 'HN' THEN 2 WHEN 'RT' THEN 3 WHEN 'P' THEN 4 ELSE 5 END, codigo, descripcion"
		));
		if (!empty($conceptos)) {
			foreach ($conceptos as $concepto) {
				if ($concepto->tipo === 'HR') {
					$concepto->tipo = "Haberes remunerativos";
				} elseif ($concepto->tipo === 'HN') {
					$concepto->tipo = "Haberes no remunerativos";
				} elseif ($concepto->tipo === 'RT') {
					$concepto->tipo = "Descuentos/Retenciones";
				} elseif ($concepto->tipo === 'P') {
					$concepto->tipo = "Patronales";
				}
			}
			echo json_encode($conceptos);
		} else {
			echo json_encode('');
		}
	}

	public function get_cuil() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$cuil = $this->input->get('cuil');
		$id = $this->input->get('id');
		if (empty($cuil)) {
			echo json_encode('');
			return;
		} else {
			$this->load->model('persona_model');
			if (empty($id)) {
				$persona_cuil = $this->persona_model->get(array(
					'cuil' => $cuil
				));
			} else {
				$persona_cuil = $this->persona_model->get(array(
					'cuil' => $cuil,
					'id !=' => $id
				));
			}
		}
		if (!empty($persona_cuil)) {
			$persona_cuil[0]->fecha_nacimiento = (new DateTime($persona_cuil[0]->fecha_nacimiento))->format('d/m/Y');
			echo json_encode($persona_cuil[0]);
		} else {
			echo json_encode('');
		}
	}

	public function get_personal() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('cargo_model');

		$cuil = $this->input->get('cuil');
		$nombre = $this->input->get('nombre');
		$apellido = $this->input->get('apellido');

		if (!empty($cuil)) {
			$personas_listar = $this->cargo_model->get_personal($cuil);
			if (!empty($personas_listar)) {
				foreach ($personas_listar as $persona) {
					$persona->cuil === NULL ? $persona->cuil = '' : '';
					$persona->persona === NULL ? $persona->persona = '' : '';
					$persona->escuela === NULL ? $persona->escuela = '' : '';
					$persona->cant_cargos === NULL ? $persona->cant_cargos = '' : '';
					if ($persona->cuil === NULL) {
						$persona->cuil = '';
					}
					if ($persona->persona === NULL) {
						$persona->persona = '';
					}
					if ($persona->escuela === NULL) {
						$persona->escuela = '';
					}
					if ($persona->cant_cargos === NULL) {
						$persona->cant_cargos = '';
					}
				}
			}
		}
		if (!empty($apellido) && !empty($nombre) && strlen($apellido) >= 3 && strlen($nombre) >= 3) {
			$personas_listar = $this->cargo_model->get_personal('', $apellido, $nombre);

			if (!empty($personas_listar)) {
				foreach ($personas_listar as $persona) {
					if ($persona->cuil === NULL) {
						$persona->cuil = '';
					}
					if ($persona->persona === NULL) {
						$persona->persona = '';
					}
					if ($persona->escuela === NULL) {
						$persona->escuela = '';
					}
					if ($persona->cant_cargos === NULL) {
						$persona->cant_cargos = '';
					}
				}
			}
		}

		if (!empty($personas_listar)) {
			echo json_encode(array('status' => 'success', 'personas_listar' => $personas_listar));
		} else {
			echo json_encode(array('status' => 'error'));
		}
	}

	public function get_alumno() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_model');

		$documento = $this->input->get('documento');
		$nombre = $this->input->get('nombre');
		$apellido = $this->input->get('apellido');

		if (!empty($documento)) {
			$personas_listar = $this->alumno_model->get_alumno($documento);
			if (!empty($personas_listar)) {
				foreach ($personas_listar as $persona) {
					if ($persona->documento === NULL) {
						$persona->documento = '';
					}
					if ($persona->nombre === NULL) {
						$persona->nombre = '';
					}
					if ($persona->escuela === NULL) {
						$persona->escuela = '';
					}
					if ($persona->direccion === NULL) {
						$persona->direccion = '';
					}
					if ($persona->curso === NULL) {
						$persona->curso = '';
					}
					if ($persona->division === NULL) {
						$persona->division = '';
					}
					if ($persona->ciclo_lectivo === NULL) {
						$persona->ciclo_lectivo = '';
					}
					$persona->fecha_nacimiento === NULL ? $persona->fecha_nacimiento = '' : $persona->fecha_nacimiento = (new DateTime($persona->fecha_nacimiento))->format('d/m/Y');
				}
			}
		}
		if (!empty($apellido) && !empty($nombre) && strlen($apellido) >= 3 && strlen($nombre) >= 3) {
			$personas_listar = $this->alumno_model->get_alumno('', $apellido, $nombre);
			if (!empty($personas_listar)) {
				foreach ($personas_listar as $persona) {
					if ($persona->documento === NULL) {
						$persona->documento = '';
					}
					if ($persona->nombre === NULL) {
						$persona->nombre = '';
					}
					if ($persona->escuela === NULL) {
						$persona->escuela = '';
					}
					if ($persona->direccion === NULL) {
						$persona->direccion = '';
					}
					if ($persona->curso === NULL) {
						$persona->curso = '';
					}
					if ($persona->division === NULL) {
						$persona->division = '';
					}
					if ($persona->ciclo_lectivo === NULL) {
						$persona->ciclo_lectivo = '';
					}
					$persona->fecha_nacimiento === NULL ? $persona->fecha_nacimiento = '' : $persona->fecha_nacimiento = (new DateTime($persona->fecha_nacimiento))->format('d/m/Y');
				}
			}
		}

		if (!empty($personas_listar)) {
			echo json_encode(array('status' => 'success', 'personas_listar' => $personas_listar));
		} else {
			echo json_encode(array('status' => 'error'));
		}
	}

	public function get_listar_personas() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('persona_model');

		$documento = $this->input->get('documento');
		$nombre = $this->input->get('nombre');
		$apellido = $this->input->get('apellido');
		if (!empty($documento)) {
			$personas_listar = $this->persona_model->get(array(
				'documento' => $documento,
				'join' => array(
					array('documento_tipo', 'documento_tipo.id=persona.documento_tipo_id', 'left', array('documento_tipo.descripcion_corta')),
				)
			));
			if (!empty($personas_listar)) {
				foreach ($personas_listar as $persona) {
					if ($persona->documento === NULL) {
						$persona->documento = '';
					}
					if ($persona->nombre === NULL) {
						$persona->nombre = '';
					}
					if ($persona->apellido === NULL) {
						$persona->apellido = '';
					}
					if ($persona->fecha_nacimiento === NULL) {
						$persona->fecha_nacimiento = '';
					} else {
						$persona->fecha_nacimiento = (new DateTime($persona->fecha_nacimiento))->format('d/m/Y');
					}
				}
			}
		}

		if (!empty($apellido) || !empty($nombre)) {
			$personas_listar = $this->persona_model->get(array(
				'join' => array(
					array('documento_tipo', 'documento_tipo.id=persona.documento_tipo_id', 'left', array('documento_tipo.descripcion_corta')),
				),
				'apellido like both' => $apellido,
				'nombre like both' => $nombre
			));
			if (!empty($personas_listar)) {
				foreach ($personas_listar as $persona) {
					if ($persona->documento === NULL) {
						$persona->documento = '';
					}
					if ($persona->nombre === NULL) {
						$persona->nombre = '';
					}
					if ($persona->apellido === NULL) {
						$persona->apellido = '';
					}
					if ($persona->fecha_nacimiento === NULL) {
						$persona->fecha_nacimiento = '';
					} else {
						$persona->fecha_nacimiento = (new DateTime($persona->fecha_nacimiento))->format('d/m/Y');
					}
				}
			}
		}

		if (!empty($personas_listar)) {
			echo json_encode(array('status' => 'success', 'personas_listar' => $personas_listar));
		} else {
			echo json_encode(array('status' => 'error'));
		}
	}

	public function get_listar_personas_bono() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('bono_secundario/persona_bono_model');
		$this->persona_bono_model->set_database($DB1);

		$cuil = $this->input->get('cuil');
		$nombre = $this->input->get('nombre');
		$apellido = $this->input->get('apellido');
		if (!empty($cuil)) {
			$personas_listar = $this->persona_bono_model->get(array(
				'cuil' => $cuil
			));
			if (!empty($personas_listar)) {
				foreach ($personas_listar as $persona) {
					if ($persona->cuil === NULL) {
						$persona->cuil = '';
					}
					if ($persona->apellido === NULL) {
						$persona->apellido = '';
					}
					if ($persona->nombre === NULL) {
						$persona->nombre = '';
					}
					if ($persona->fecha_nacimiento === NULL) {
						$persona->fecha_nacimiento = '';
					} else {
						$persona->fecha_nacimiento = (new DateTime($persona->fecha_nacimiento))->format('d/m/Y');
					}
				}
			}
		}

		if (!empty($apellido) || !empty($nombre)) {
			$personas_listar = $this->persona_bono_model->get(array(
				'apellido like both' => $apellido,
				'nombre like both' => $nombre
			));
			if (!empty($personas_listar)) {
				foreach ($personas_listar as $persona) {
					if ($persona->cuil === NULL) {
						$persona->cuil = '';
					}
					if ($persona->apellido === NULL) {
						$persona->apellido = '';
					}
					if ($persona->nombre === NULL) {
						$persona->nombre = '';
					}
					if ($persona->fecha_nacimiento === NULL) {
						$persona->fecha_nacimiento = '';
					} else {
						$persona->fecha_nacimiento = (new DateTime($persona->fecha_nacimiento))->format('d/m/Y');
					}
				}
			}
		}

		if (!empty($personas_listar)) {
			echo json_encode(array('status' => 'success', 'personas_listar' => $personas_listar));
		} else {
			echo json_encode(array('status' => 'error'));
		}
	}

	public function get_novedades() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$servicio_id = $this->input->get('servicio_id');
		$mes = $this->input->get('mes');
		$tipo = $this->input->get('tipo');
		if (empty($servicio_id) || empty($mes)) {
			show_error('No se han recibido los parámetros necesarios', 500, 'Acción no autorizada');
		}

		if ($tipo === 'funcion') {
			$this->load->model('servicio_funcion_model');
			$servicio_funcion = $this->servicio_funcion_model->get(array('id' => $servicio_id));
			$servicio = new stdClass();
			$servicio->id = $servicio_funcion->servicio_id;
		} else {
			$this->load->model('servicio_model');
			$servicio = $this->servicio_model->get(array('id' => $servicio_id));
		}

		if (empty($servicio)) {
			show_error('No se han recibido los parámetros necesarios', 500, 'Acción no autorizada');
		}
		$this->load->model('servicio_novedad_model');
		$novedades = $this->servicio_novedad_model->get(array(
			'join' => array(
				array('novedad_tipo', 'servicio_novedad.novedad_tipo_id=novedad_tipo.id', 'left', array('novedad_tipo.descripcion_corta', 'novedad_tipo.articulo', 'novedad_tipo.inciso')),
				array('servicio', 'servicio_novedad.servicio_id=servicio.id', ''),
				array('cargo', 'servicio.cargo_id=cargo.id', '', array('cargo.escuela_id')),
			),
			'servicio_id' => $servicio->id,
			'ames' => $mes,
			'where' => array('planilla_baja_id IS NULL'),
			'sort_by' => 'servicio_novedad.fecha_desde'
		));

		if (!empty($novedades)) {
			echo json_encode($novedades);
			return;
		} else {
			echo json_encode(array());
		}
	}

	public function get_niveles($linea_id = '') {
		if (in_array($this->rol->codigo, $this->roles_permitidos) && !empty($linea_id)) {
			$this->load->model('nivel_model');
			$niveles_query = $this->nivel_model->get(array('linea_id' => $linea_id));
			if (!empty($niveles_query)) {
				echo json_encode($niveles_query);
				return;
			}
			echo json_encode(array('status' => 'error'));
		} else {
			show_404();
		}
	}

	public function get_supervisiones($linea_id = '') {
		if (in_array($this->rol->codigo, $this->roles_permitidos) && !empty($linea_id)) {
			$this->load->model('supervision_model');
			$supervision_query = $this->supervision_model->get(array('join' => array(array('linea', "linea.id = supervision.linea_id and linea_id = $linea_id")), 'sort_by' => 'orden'));
			if (!empty($supervision_query)) {
				echo json_encode($supervision_query);
				return;
			}
			echo json_encode(array('status' => 'error'));
		} else {
			show_404();
		}
	}

	public function get_tipo_cargo($regimen_id = '') {
		if (in_array($this->rol->codigo, $this->roles_permitidos) && !empty($regimen_id)) {
			$this->load->model('regimen_model');
			$regimen = $this->regimen_model->get_one($regimen_id);
			if (!empty($regimen)) {
				echo json_encode($regimen->regimen_tipo);
				return;
			}
			echo json_encode(array('status' => 'error'));
		} else {
			show_404();
		}
	}

	public function get_codigo_area() {
		$this->form_validation->set_rules('area', 'Área Padre', 'integer');
		$this->form_validation->set_rules('id', 'Área', 'integer');
		if ($this->form_validation->run() === TRUE) {
			$area_id = $this->input->post('id');
			$area_padre_id = $this->input->post('area');
			$this->load->model('areas/area_model');
			if (isset($area_id)) {
				$area = $this->area_model->get_one($area_id);
			}
			if (!empty($area) && $area->area_padre_id == $area_padre_id) {
				echo json_encode(array('status' => 'success', 'codigo' => $area->codigo));
			} else {
				$codigo = $this->area_model->get_codigo($area_padre_id);
				echo json_encode(array('status' => 'success', 'codigo' => $codigo));
			}
			return;
		}
		echo json_encode(array('status' => 'error'));
		return;
	}

	public function get_listar_celadores() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('persona_model');
		$this->load->model('elecciones/eleccion_desinfeccion_model');
		$eleccion_id = $this->input->get('eleccion_id');
		$dependencia_id = $this->input->get('dependencia_id');
		$cuil = $this->input->get('cuil');
		if (!empty($cuil) && !EMPTY($dependencia_id)) {
			$personas_listar = $this->eleccion_desinfeccion_model->get_celadores($cuil, $dependencia_id, $eleccion_id);
		}
		if (!empty($personas_listar)) {
			echo json_encode(array('status' => 'success', 'personas_listar' => $personas_listar));
		} else {
			echo json_encode(array('status' => 'error'));
		}
	}

	public function get_cargos_cursada() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('cursada_model');
		$this->load->model('cargo_cursada_model');

		$cursada_id = $this->input->get('cursada_id');

		$cursada = $this->cursada_model->get_one($cursada_id);

		$cargos = $this->cargo_cursada_model->get_cargos_cursada($cursada->id);
		if (!empty($cargos)) {
			echo json_encode(array('status' => 'success', 'cargos' => $cargos));
		} else {
			echo json_encode(array('status' => 'error'));
		}
	}

	public function get_cursada_carga_horaria() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('cursada_model');
		$this->load->model('cargo_cursada_model');
		$cursada_id = $this->input->get('cursada_id');
		$carga_horaria_cursada = $this->cargo_cursada_model->get_cursada_carga_horaria($cursada_id);
		if (!empty($carga_horaria_cursada)) {
			echo json_encode(array('status' => 'success', 'carga_horaria_cursada' => $carga_horaria_cursada));
		} else {
			echo json_encode(array('status' => 'error'));
		}
	}

	public function get_listar_titulos() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('../modules/titulos/models/titulo_model');

		$tipo = $this->input->get('tipo');
		$establecimiento = $this->input->get('establecimiento');
		$nombre = $this->input->get('nombre');

		if (!empty($tipo) || !empty($establecimiento) || !empty($nombre)) {
			$titulos_listar = $this->titulo_model->getTitulosLike($tipo, $establecimiento, $nombre);
			if (!empty($titulos_listar)) {
				foreach ($titulos_listar as $titulo) {
					if ($titulo->nombre === NULL) {
						$titulo->nombre = '';
					}
					if ($titulo->pais_origen === NULL) {
						$titulo->pais_origen = '';
					}
					if ($titulo->establecimiento_descripcion === NULL) {
						$titulo->establecimiento_descripcion = '';
					}
					if ($titulo->tipo_descripcion === NULL) {
						$titulo->tipo_descripcion = '';
					}
				}
			}
		}

		if (!empty($titulos_listar)) {
			echo json_encode(array('status' => 'success', 'titulos_listar' => $titulos_listar));
			//exit;
		} else {
			echo json_encode(array('status' => 'error'));
		}
	}

	public function get_trayectoria_alumno() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('alumno_division_model');
		$alumno_id = $this->input->get('alumno_id');
		$trayectoria = $this->alumno_division_model->get_trayectoria_alumno($alumno_id);

		if (!empty($trayectoria)) {
			echo json_encode(array('status' => 'success', 'trayectoria' => $trayectoria));
		} else {
			echo json_encode(array('status' => 'error'));
		}
	}

	public function get_num_extintor() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$numero_registro = $this->input->get('numero_registro');
		if (empty($numero_registro)) {
			echo json_encode('');
			return;
		} else {
			$this->load->model('extintores/extintor_model');
			$extintor = $this->extintor_model->get(array(
				'numero_registro' => $numero_registro,
			));
		}
		if (!empty($extintor)) {
			echo json_encode($extintor);
		} else {
			echo json_encode('');
		}
	}

	public function get_listar_abonos_alumnos() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('alumno_model');
		$this->load->model('abono/abono_alumno_model');
		$escuela_id = $this->input->get('escuela_id');
		$documento = $this->input->get('documento');
		$nombre = $this->input->get('nombre');
		$apellido = $this->input->get('apellido');
		$ames = $this->input->get('ames');
		$division_id = $this->input->get('division_id');
		if (!empty($escuela_id) || !empty($documento) || !empty($nombre) || !empty($apellido)) {
			if ($division_id > 0) {
				$alumnos_listar = $this->alumno_model->get_alumno_escuela_division($escuela_id, $documento, $apellido, $nombre, $division_id);
			} else {
				$alumnos_listar = $this->alumno_model->get_alumno_escuela($escuela_id, $documento, $apellido, $nombre);
			}
			if (!empty($alumnos_listar)) {
				foreach ($alumnos_listar as $alumno) {
					$abono_alumno_id = $this->abono_alumno_model->valida_alumno_escuela($alumno->alumno_id, $ames);
					if ($alumno->nombre === NULL) {
						$alumno->nombre = '';
					}
					if ($alumno->curso === NULL) {
						$alumno->curso = '';
					}
					if ($alumno->division === NULL) {
						$alumno->division = '';
					}
					if ($alumno->documento === NULL) {
						$alumno->documento = '';
					}
					if (!empty($abono_alumno_id)) {
						$alumno->abono_encontrado = "true";
					} else {
						$alumno->abono_encontrado = "false";
					}
				}
			}
		}
		if (!empty($alumnos_listar)) {
			echo json_encode(array('status' => 'success', 'alumnos_listar' => $alumnos_listar));
			//exit;
		} else {
			echo json_encode(array('status' => 'error'));
		}
	}

	public function verificar_mail() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$email = $this->input->get('email_contacto');
		if ((bool) filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$dominio = substr($email, strpos($email, '@'));
			$this->load->model('mail_dominio_model');
			$verificacion = $this->mail_dominio_model->get(array('dominio' => $dominio, 'email_valido' => 'No'));
			if (empty($verificacion)) {
				http_response_code(200);
				echo json_encode(array('status' => 'success'));
			} else {
				http_response_code(406);
				echo json_encode(array('status' => 'error', 'message' => 'Mail no válido'));
			}
		} else {
			http_response_code(406);
			echo json_encode(array('status' => 'error', 'message' => 'Mail no válido2'));
		}
	}
}
/* End of file Ajax.php */
	/* Location: ./application/controllers/Ajax.php */
