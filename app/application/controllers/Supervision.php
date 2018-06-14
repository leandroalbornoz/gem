<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Supervision extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('supervision_model');
		$this->roles_admin = array(ROL_ADMIN, ROL_USI);
		$this->roles_editar = array(ROL_ADMIN, ROL_USI, ROL_SUPERVISION, ROL_LINEA);
		$this->roles_escuelas = array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_PRIVADA, ROL_SEOS, ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_JEFE_LIQUIDACION, ROL_LIQUIDACION, ROL_REGIONAL);
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_GRUPO_ESCUELA_CONSULTA))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'admin/supervision';
	}

	public function listar($nivel_id = '0', $dependencia_id = '0') {
		if (!in_array($this->rol->codigo, $this->roles_admin)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$tableData = array(
			'columns' => array(
				array('label' => 'Responsable', 'data' => 'responsable', 'width' => 15),
				array('label' => 'Nivel', 'data' => 'nivel', 'width' => 15),
				array('label' => 'Gestión', 'data' => 'dependencia', 'width' => 10),
				array('label' => 'Nombre', 'data' => 'nombre', 'width' => 20),
				array('label' => 'Email', 'data' => 'email', 'width' => 20),
				array('label' => 'Blackberry', 'data' => 'blackberry', 'width' => 20, 'responsive_class' => 'none'),
				array('label' => 'Teléfono', 'data' => 'telefono', 'width' => 20, 'responsive_class' => 'none'),
				array('label' => 'Sede', 'data' => 'sede', 'width' => 20, 'responsive_class' => 'none'),
				array('label' => 'Orden', 'data' => 'orden', 'class' => 'dt-body-right', 'width' => 8),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'supervision_table',
			'source_url' => "supervision/listar_data/$nivel_id/$dependencia_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(6, 'asc')),
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Supervisiones';
		$this->load_template('supervision/supervision_listar', $data);
	}

	public function listar_data($nivel_id, $dependencia_id, $rol_codigo, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_admin)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('supervision.id, supervision.responsable, supervision.nombre, supervision.email, supervision.orden, supervision.blackberry, supervision.telefono, supervision.sede, nivel.descripcion as nivel, dependencia.descripcion as dependencia')
			->unset_column('id')
			->from('supervision')
			->join('nivel', 'nivel.id = supervision.nivel_id', 'left')
			->join('dependencia', 'dependencia.id = supervision.dependencia_id', 'left');
		$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
			. '<a class="btn btn-xs btn-default" href="supervision/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
			. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
			. '<ul class="dropdown-menu dropdown-menu-right">'
			. '<li><a class="dropdown-item" href="supervision/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
			. '<li><a class="dropdown-item" href="supervision/caracteristicas/$1" title="Características"><i class="fa fa-list-alt"></i> Características</a></li>'
			. '<li><a class="dropdown-item" href="supervision/eliminar/$1" title="Eliminar"><i class="fa fa-remove"></i> Eliminar</a></li>'
			. '</ul></div>', 'id');

		if ($nivel_id !== '0') {
			$this->datatables->where('nivel_id', $nivel_id);
		}
		if ($dependencia_id !== '0') {
			$this->datatables->where('dependencia_id', $dependencia_id);
		}

		echo $this->datatables->generate();
	}

	public function agregar() {
		if (!in_array($this->rol->codigo, $this->roles_admin)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('nivel_model');
		$this->load->model('dependencia_model');
		$this->load->model('linea_model');
		$this->load->model('localidad_model');
		$this->array_linea_control = $array_linea = $this->get_array('linea', 'nombre', 'id', null, array('' => '-- Seleccionar linea --'));
		$this->array_nivel_control = $array_nivel = $this->get_array('nivel', 'descripcion', 'id', null, array('' => '-- Seleccionar nivel --'));
		$this->array_dependencia_control = $array_dependencia = $this->get_array('dependencia', 'descripcion', 'id', null, array('' => '-- Seleccionar gestión --'));
		$this->array_localidad_control = $array_localidad = $this->get_array('localidad', 'localidad', 'id', array(
			'join' => array(
				array('departamento', 'departamento.id=localidad.departamento_id', 'left', array('CONCAT(departamento.descripcion, \' - \', localidad.descripcion) as localidad'))
			),
			'sort_by' => 'departamento.descripcion, localidad.descripcion'
			), array('' => '-- Seleccionar localidad --'));

		$this->set_model_validation_rules($this->supervision_model);
		if ($this->form_validation->run() === TRUE) {
			$trans_ok = TRUE;
			$trans_ok &= $this->supervision_model->create(array(
				'responsable' => $this->input->post('responsable'),
				'linea_id' => $this->input->post('linea'),
				'nivel_id' => $this->input->post('nivel'),
				'dependencia_id' => $this->input->post('dependencia'),
				'localidad_id' => $this->input->post('localidad'),
				'calle' => $this->input->post('calle'),
				'calle_numero' => $this->input->post('calle_numero'),
				'barrio' => $this->input->post('barrio'),
				'codigo_postal' => $this->input->post('codigo_postal'),
				'nombre' => $this->input->post('nombre'),
				'email' => $this->input->post('email'),
				'orden' => $this->input->post('orden'),
				'blackberry' => $this->input->post('blackberry'),
				'telefono' => $this->input->post('telefono'),
				'sede' => $this->input->post('sede')
			));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->supervision_model->get_msg());
				redirect('supervision/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->supervision_model->get_error() ? $this->supervision_model->get_error() : $this->session->flashdata('error')));

		$this->supervision_model->fields['linea']['array'] = $array_linea;
		$this->supervision_model->fields['nivel']['array'] = $array_nivel;
		$this->supervision_model->fields['dependencia']['array'] = $array_dependencia;
		$this->supervision_model->fields['localidad']['array'] = $array_localidad;
		$data['fields'] = $this->build_fields($this->supervision_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'caracteristica' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = TITLE . ' - Agregar supervisión';
		$this->load_template('supervision/supervision_abm', $data);
	}

	public function editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_editar) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$supervision = $this->supervision_model->get_one($id);
		if (empty($supervision)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		$this->load->model('linea_model');
		$this->load->model('nivel_model');
		$this->load->model('dependencia_model');
		$this->load->model('localidad_model');
		$this->array_localidad_control = $array_localidad = $this->get_array('localidad', 'localidad', 'id', array(
			'join' => array(
				array('departamento', 'departamento.id=localidad.departamento_id', 'left', array('CONCAT(departamento.descripcion, \' - \', localidad.descripcion) as localidad'))
			),
			'sort_by' => 'departamento.descripcion, localidad.descripcion'
			), array('' => '-- Seleccionar localidad --'));
		if (!in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI))) {
			$this->supervision_model->fields['nombre']['readonly'] = TRUE;
			$this->supervision_model->fields['orden']['readonly'] = TRUE;
			unset($this->supervision_model->fields['nivel']['input_type']);
			unset($this->supervision_model->fields['nivel']['id_name']);
			$this->supervision_model->fields['nivel']['readonly'] = TRUE;
			unset($this->supervision_model->fields['linea']['input_type']);
			unset($this->supervision_model->fields['linea']['id_name']);
			$this->supervision_model->fields['linea']['readonly'] = TRUE;
			unset($this->supervision_model->fields['dependencia']['input_type']);
			unset($this->supervision_model->fields['dependencia']['id_name']);
			$this->supervision_model->fields['dependencia']['readonly'] = TRUE;
		} else {
			$this->array_linea_control = $array_linea = $this->get_array('linea', 'nombre', 'id', null, array('' => '-- Seleccionar linea --'));
			$this->array_nivel_control = $array_nivel = $this->get_array('nivel', 'descripcion', 'id', null, array('' => '-- Seleccionar nivel --'));
			$this->array_dependencia_control = $array_dependencia = $this->get_array('dependencia', 'descripcion', 'id', null, array('' => '-- Seleccionar gestión --'));
			$this->supervision_model->fields['linea']['array'] = $array_linea;
			$this->supervision_model->fields['nivel']['array'] = $array_nivel;
			$this->supervision_model->fields['dependencia']['array'] = $array_dependencia;
		}
		if ($this->rol->codigo === ROL_SUPERVISION || $this->rol->codigo === ROL_LINEA) {
			$data['redirect'] = 'escritorio';
		} else {
			$data['redirect'] = 'supervision/listar';
		}

		$this->set_model_validation_rules($this->supervision_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$campos_update = array(
					'id' => $this->input->post('id'),
					'responsable' => $this->input->post('responsable'),
					'email' => $this->input->post('email'),
					'blackberry' => $this->input->post('blackberry'),
					'telefono' => $this->input->post('telefono'),
					'sede' => $this->input->post('sede'),
					'localidad_id' => $this->input->post('localidad'),
					'calle' => $this->input->post('calle'),
					'calle_numero' => $this->input->post('calle_numero'),
					'barrio' => $this->input->post('barrio'),
					'codigo_postal' => $this->input->post('codigo_postal'),
				);
				if (in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI))) {
					$campos_update['dependencia_id'] = $this->input->post('dependencia');
					$campos_update['linea_id'] = $this->input->post('linea');
					$campos_update['nivel_id'] = $this->input->post('nivel');
					$campos_update['nombre'] = $this->input->post('nombre');
					$campos_update['orden'] = $this->input->post('orden');
				}
				$trans_ok &= $this->supervision_model->update($campos_update);
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->supervision_model->get_msg());
					redirect("supervision/ver/$supervision->id", 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->supervision_model->get_error() ? $this->supervision_model->get_error() : $this->session->flashdata('error')));
		$this->supervision_model->fields['localidad']['array'] = $array_localidad;
		$data['fields'] = $this->build_fields($this->supervision_model->fields, $supervision);
		$data['supervision'] = $supervision;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'caracteristica' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar supervisión';
		$this->load_template('supervision/supervision_abm', $data);
	}

	public function eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_admin) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$supervision = $this->supervision_model->get_one($id);
		if (empty($supervision)) {
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->supervision_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->supervision_model->get_msg());
				redirect('supervision/listar', 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->supervision_model->get_error() ? $this->supervision_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->supervision_model->fields, $supervision, TRUE);

		$data['supervision'] = $supervision;

		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'caracteristica' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = TITLE . ' - Eliminar supervisión';
		$this->load_template('supervision/supervision_abm', $data);
	}

	public function ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_editar) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$supervision = $this->supervision_model->get_one($id);
		if (empty($supervision)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		if ($this->rol->codigo === ROL_SUPERVISION || $this->rol->codigo === ROL_LINEA) {
			$data['redirect'] = 'escritorio';
		} else {
			$data['redirect'] = 'supervision/listar';
		}

		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->supervision_model->fields, $supervision, TRUE);
		$data['supervision'] = $supervision;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'caracteristica' => '', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver supervisión';
		$this->load_template('supervision/supervision_abm', $data);
	}

	public function caracteristicas($id) {
		if (!in_array($this->rol->codigo, $this->roles_editar) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$supervision = $this->supervision_model->get_one($id);
		if (empty($supervision)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('supervision', $this->rol, $supervision)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->load->model('caracteristica_supervision_model');
		$fields_caracteristicas = $this->caracteristica_supervision_model->get_fields($supervision->nivel_id, $supervision->id, FALSE, TRUE);
		$fields_tipos = $fields_caracteristicas[0];
		$lista_caracteristicas = $fields_caracteristicas[1];

		foreach ($fields_tipos as $fields) {
			foreach ($fields as $field_id => $field) {
				if (isset($field['input_type'])) {
					$this->{"array_{$field_id}_control"} = $field['array'];
				}
			}
			$this->set_model_validation_rules((object) array('fields' => $fields));
		}
		if ($this->rol->codigo === ROL_SUPERVISION) {
			$data['redirect'] = 'escritorio';
		} else {
			$data['redirect'] = 'supervision/listar';
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$this->load->model('caracteristica_model');
				$this->load->model('caracteristica_valor_model');
				foreach ($this->input->post('caracteristicas') as $id_caracteristica => $valor_caracteristica) {
					if (!isset($lista_caracteristicas[$id_caracteristica]->caracteristica_supervision_id)) {
						if ($lista_caracteristicas[$id_caracteristica]->lista_valores === 'Si') {
							if (!empty($valor_caracteristica)) {
								$valor = $this->caracteristica_valor_model->get_valor($valor_caracteristica);
								if (!empty($valor)) {
									$trans_ok &= $this->caracteristica_supervision_model->create(array(
										'supervision_id' => $id,
										'caracteristica_id' => $id_caracteristica,
										'valor' => $valor->valor,
										'fecha_desde' => date('Y-m-d'),
										'caracteristica_valor_id' => $valor->id
										), FALSE);
								} else {
									$this->db->trans_rollback();
									show_error('No se encontró el registro a actualizar', 500, 'Registro no encontrado');
								}
							}
						} else {
							if (!empty($valor_caracteristica)) {
								$trans_ok &= $this->caracteristica_supervision_model->create(array(
									'supervision_id' => $id,
									'caracteristica_id' => $id_caracteristica,
									'fecha_desde' => date('Y-m-d'),
									'valor' => $valor_caracteristica
									), FALSE);
							}
						}
					} else {
						if ($lista_caracteristicas[$id_caracteristica]->lista_valores === 'Si') {
							if (!empty($valor_caracteristica)) {
								$valor = $this->caracteristica_valor_model->get_valor($valor_caracteristica);
								if (!empty($valor)) {
									if ($lista_caracteristicas[$id_caracteristica]->valor !== $valor->valor) {
										$trans_ok &= $this->caracteristica_supervision_model->update(array(
											'id' => $lista_caracteristicas[$id_caracteristica]->caracteristica_supervision_id,
											'fecha_hasta' => date('Y-m-d')
											), FALSE);
										$trans_ok &= $this->caracteristica_supervision_model->create(array(
											'supervision_id' => $id,
											'caracteristica_id' => $id_caracteristica,
											'fecha_desde' => date('Y-m-d'),
											'valor' => $valor->valor,
											'caracteristica_valor_id' => $valor->id
											), FALSE);
									}
								} else {
									$this->db->trans_rollback();
									show_error('No se encontró el registro a actualizar', 500, 'Registro no encontrado');
								}
							} else {
								if (!empty($lista_caracteristicas[$id_caracteristica]->valor)) {
									$trans_ok &= $this->caracteristica_supervision_model->update(array(
										'id' => $lista_caracteristicas[$id_caracteristica]->caracteristica_supervision_id,
										'fecha_hasta' => date('Y-m-d')
										), FALSE);
//									No insertar características vacías
//									$trans_ok &= $this->caracteristica_escuela_model->create(array(
//										'escuela_id' => $id,
//										'caracteristica_id' => $id_caracteristica,
//										'fecha_desde' => date('Y-m-d'),
//										'valor' => 'NULL',
//										'caracteristica_valor_id' => 'NULL'
//										), FALSE);
								}
							}
						} else {
							if ($lista_caracteristicas[$id_caracteristica]->valor != $valor_caracteristica) {
								$trans_ok &= $this->caracteristica_supervision_model->update(array(
									'id' => $lista_caracteristicas[$id_caracteristica]->caracteristica_supervision_id,
									'fecha_hasta' => date('Y-m-d')
									), FALSE);
								if (!empty($valor_caracteristica)) {//No insertar características vacías
									$trans_ok &= $this->caracteristica_supervision_model->create(array(
										'supervision_id' => $id,
										'caracteristica_id' => $id_caracteristica,
										'fecha_desde' => date('Y-m-d'),
										'valor' => $valor_caracteristica
										), FALSE);
								}
							}
						}
					}
					unset($lista_caracteristicas[$id_caracteristica]);
				}
				foreach ($lista_caracteristicas as $caracteristica) {
					if (isset($caracteristica->caracteristica_supervision_id)) {
						$trans_ok &= $this->caracteristica_supervision_model->update(array(
							'id' => $caracteristica->caracteristica_supervision_id,
							'fecha_hasta' => date('Y-m-d')
							), FALSE);
//					No insertar características vacías
//					$trans_ok &= $this->caracteristica_escuela_model->create(array(
//						'escuela_id' => $id,
//						'caracteristica_id' => $id_caracteristica,
//						'fecha_desde' => date('Y-m-d'),
//						'valor' => 'NULL',
//						'caracteristica_valor_id' => 'NULL'
//						), FALSE);
					}
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->caracteristica_supervision_model->get_msg());
					redirect("supervision/ver/$supervision->id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->caracteristica_supervision_model->get_error())
						$errors .= '<br>' . $this->caracteristica_supervision_model->get_error();
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : (!empty($errors) ? $errors : $this->session->flashdata('error')));
		$data['fields'] = $this->build_fields($this->supervision_model->fields, $supervision, TRUE);

		foreach ($fields_tipos as $tipo => $fields) {
			$data['fields_tipos'][$tipo] = $this->build_fields($fields);
		}

		$data['supervision'] = $supervision;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'caracteristica' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar características de supervisión';
		$this->load_template('supervision/supervision_caracteristicas', $data);
	}

	public function escritorio($id) {
		if (!in_array($this->rol->codigo, $this->roles_escuelas) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('anuncio_model');
		$data['anuncios'] = $this->anuncio_model->get_anuncios();
		$this->load->model('supervision_model');
		$supervision = $this->supervision_model->get_one($id);
		if (empty($supervision)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('supervision', $this->rol, $supervision)) {
			show_error('No tiene permisos para acceder a la supervision', 500, 'Acción no autorizada');
		}
		$data['supervision'] = $supervision;
		$data['administrar'] = TRUE;
		$this->load->model('nivel_model');
		$nivel = $this->nivel_model->get(array('id' => $supervision->nivel_id));
		$this->load->model('escuela_model');
		$this->load->model('carrera_model');
		$this->load->model('caracteristica_supervision_model');
		$fields_tipos = $this->caracteristica_supervision_model->get_fields($supervision->nivel_id, $supervision->id, TRUE);
		foreach ($fields_tipos as $tipo => $fields) {
			$data['fields_tipos'][$tipo] = $this->build_fields($fields);
		}
		$this->load->model('caracteristica_escuela_model');
		$nivel->caracteristicas = $this->caracteristica_escuela_model->get_by_nivel($nivel->id);
		$nivel->escuelas = $this->escuela_model->get_by_nivel($nivel->id, $supervision->dependencia_id, $supervision->id);
		$nivel->carreras = $this->carrera_model->get_by_nivel($nivel->id, $supervision->dependencia_id, $supervision->id);
		$nivel->indices = $this->nivel_model->get_indices($nivel->id, $supervision->dependencia_id, $supervision->id);
		
		$this->load->model('preinscripciones/preinscripcion_model');
		$this->load_modules($data, $this->preinscripcion_model->modulo_supervision($supervision, $data));

		$this->load->model('elecciones/eleccion_desinfeccion_model');
		$this->load->model('elecciones/eleccion_desinfeccion_persona_model');
		$escuelas_desinfeccion = $this->eleccion_desinfeccion_model->get_escuelas_desinfeccion($supervision->id);
		if (!empty($escuelas_desinfeccion)) {
			$data['modulos_inactivos']['escuelas_desinfeccion'] = $this->load->view('elecciones/escritorio_supervision_modulo_desinfeccion', $this->eleccion_desinfeccion_model->get_vista_supervision($supervision->id, $data), TRUE);
		}

		if ($supervision->dependencia_id === '1') {
			$this->load->model('conectividad/conectividad_model');
			$escuelas_conectividad = $this->conectividad_model->get_escuelas_conectividad($supervision->id);
			if (!empty($escuelas_conectividad)) {
				$data['modulos']['conectividad'] = $this->load->view('conectividad/escritorio_supervision_modulo_conectividad', $this->conectividad_model->get_vista_supervision($supervision->id, $data), TRUE);
			}
		}

		$data['administrar'] = in_array($this->rol->codigo, $this->roles_editar);
		$data['niveles'] = array($nivel);
		$this->load_template('escritorio/escritorio_supervision', $data);
	}

	public function escuelas($supervision_id = NULL, $redireccion = 0) {
		if (!in_array($this->rol->codigo, $this->roles_escuelas) || $supervision_id == NULL || !ctype_digit($supervision_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$supervision = $this->supervision_model->get_one($supervision_id);
		if (empty($supervision)) {
			show_error('No se encontró el registro de supervisión', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('supervision', $this->rol, $supervision)) {
			show_error('No tiene permisos para acceder a la supervisión', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Número', 'data' => 'numero', 'width' => 5),
				array('label' => 'Anexo', 'data' => 'anexo', 'class' => 'dt-body-right', 'width' => 5),
				array('label' => 'CUE', 'data' => 'cue', 'class' => 'dt-body-right', 'width' => 5),
				array('label' => 'Nombre', 'data' => 'nombre', 'width' => 20),
				array('label' => 'Nivel', 'data' => 'nivel', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'Juri/Repa', 'data' => 'jurirepa', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Supervisión', 'data' => 'supervision', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Regional', 'data' => 'regional', 'width' => 8, 'class' => 'text-sm'),
				array('label' => 'Zona', 'data' => 'zona', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Teléfono', 'data' => 'telefono', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Email', 'data' => 'email', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'escuela_table',
			'source_url' => "supervision/escuelas_data/$supervision_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'reuse_var' => TRUE,
			'initComplete' => "complete_escuela_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['redireccion'] = $redireccion;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['supervision'] = $supervision;
		$data['supervision_id'] = $supervision_id;
		$data['class'] = array('agregar' => '', 'ver_escuelas' => 'active btn-app-zetta-active', 'ver_reportes' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Escuelas Supervisión ' . $supervision->nombre;
		$this->load_template('supervision/supervision_escuela_listar', $data);
	}

	public function escuelas_data($supervision_id = NULL, $rol_codigo = '', $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_escuelas) || $supervision_id == NULL || !ctype_digit($supervision_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$supervision = $this->supervision_model->get_one($supervision_id);
		if (empty($supervision)) {
			show_error('No se encontró el registro de supervisión', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('supervision', $this->rol, $supervision)) {
			show_error('No tiene permisos para acceder a la supervisión', 500, 'Acción no autorizada');
		}

		$this->datatables
			->select('escuela.id, escuela.numero, escuela.anexo, escuela.cue, escuela.subcue, escuela.nombre, escuela.calle, escuela.calle_numero, escuela.departamento, escuela.piso, escuela.barrio, escuela.manzana, escuela.casa, escuela.localidad_id, escuela.nivel_id, escuela.reparticion_id, escuela.supervision_id, escuela.regional_id, escuela.dependencia_id, escuela.zona_id, escuela.fecha_creacion, escuela.anio_resolucion, escuela.numero_resolucion, escuela.telefono, escuela.email, escuela.fecha_cierre, escuela.anio_resolucion_cierre, escuela.numero_resolucion_cierre, dependencia.descripcion as dependencia, nivel.descripcion as nivel, regional.descripcion as regional, CONCAT(jurisdiccion.codigo,\'/\',reparticion.codigo) as jurirepa, supervision.nombre as supervision, zona.descripcion as zona')
			->unset_column('id')
			->from('escuela')
			->join('dependencia', 'dependencia.id = escuela.dependencia_id', 'left')
			->join('nivel', 'nivel.id = escuela.nivel_id', 'left')
			->join('regional', 'regional.id = escuela.regional_id', 'left')
			->join('reparticion', 'reparticion.id = escuela.reparticion_id', 'left')
			->join('jurisdiccion', 'jurisdiccion.id = reparticion.jurisdiccion_id', 'left')
			->join('supervision', 'supervision.id = escuela.supervision_id', 'left')
			->join('zona', 'zona.id = escuela.zona_id', 'left')
			->where('escuela.supervision_id', $supervision_id);
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="escuela/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item" href="escuela/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '</ul></div>', 'id');
		} else {
			$this->datatables->add_column('edit', '<a class="btn btn-xs btn-default" href="escuela/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id');
		}
		switch ($this->rol->codigo) {
			case ROL_LINEA:
			case ROL_GRUPO_ESCUELA:
			case ROL_CONSULTA_LINEA:
				$this->datatables->where('nivel.linea_id', $this->rol->entidad_id);
				$this->datatables->where('dependencia_id', '1');
				break;
			case ROL_SUPERVISION:
				$this->datatables->where('supervision.id', $this->rol->entidad_id);
				break;
			case ROL_PRIVADA:
				$this->datatables->where('dependencia_id', '2');
				break;
			case ROL_REGIONAL:
				$this->datatables->where('regional.id', $this->rol->regional_id);
				break;
			default:
		}

		echo $this->datatables->generate();
	}

	public function reportes($supervision_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_escuelas) || $supervision_id == NULL || !ctype_digit($supervision_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('supervision_model');
		$supervision = $this->supervision_model->get(array('id' => $supervision_id));
		$data['supervision'] = $supervision;
		$data['supervision_id'] = $supervision_id;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver_escuelas' => '', 'ver_reportes' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['error'] = $this->session->flashdata('error');
		$this->load_template('supervision/supervision_reportes_listar', $data);
	}

	public function reporte_escuelas($supervision_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_escuelas) || $supervision_id == NULL || !ctype_digit($supervision_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$campos = array(
			'A' => array('Número', 10),
			'B' => array('Anexo', 10),
			'C' => array('CUE', 10),
			'D' => array('SubCUE', 10),
			'E' => array('Nombre', 10),
			'F' => array('Calle', 10),
			'G' => array('N°', 10),
			'H' => array('Barrio', 10),
			'I' => array('Localidad', 10),
			'J' => array('Departamento', 10),
			'K' => array('C.P.', 10),
			'L' => array('Nivel', 10),
			'M' => array('Regímenes', 10),
			'N' => array('Repartición', 10),
			'O' => array('Supervisión', 10),
			'P' => array('Regional', 10),
			'Q' => array('Gestión', 10),
			'R' => array('Zona', 10),
			'S' => array('Fecha Creación', 10),
			'T' => array('Año Resolución', 10),
			'U' => array('Número Resolución', 10),
			'V' => array('Teléfono', 10),
			'W' => array('Email', 10)
		);
		$escuelas = $this->db->select('e.numero, e.anexo, e.cue, e.subcue, e.nombre, e.calle, e.calle_numero, e.barrio, lo.descripcion localidad, ld.descripcion departamento, '
					. 'e.codigo_postal, n.descripcion nivel, rl.descripcion regimen_lista, CONCAT(j.codigo, \' \', r.codigo) reparticion, s.nombre supervision, rg.descripcion regional, d.descripcion dependencia, '
					. 'z.descripcion zona, e.fecha_creacion, e.anio_resolucion, e.numero_resolucion, e.telefono, e.email')
				->from('escuela e')
				->join('dependencia d', 'd.id = e.dependencia_id', 'left')
				->join('localidad lo', 'lo.id = e.localidad_id', 'left')
				->join('departamento ld', 'ld.id = lo.departamento_id', 'left')
				->join('nivel n', 'n.id = e.nivel_id', 'left')
				->join('regimen_lista rl', 'rl.id = e.regimen_lista_id', 'left')
				->join('linea l', 'l.id = n.linea_id', 'left')
				->join('regional rg', 'rg.id = e.regional_id', 'left')
				->join('reparticion r', 'r.id = e.reparticion_id', 'left')
				->join('jurisdiccion j', 'j.id = r.jurisdiccion_id', 'left')
				->join('supervision s', 's.id = e.supervision_id', 'left')
				->join('zona z', 'z.id = e.zona_id', 'left')
				->where('supervision_id', $supervision_id)
				->get()->result_array();

		if (!empty($escuelas)) {
			$this->exportar_excel_supervision(array('title' => "Escuelas_{$supervision_id}_" . date('Ymd')), $campos, $escuelas);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect('supervision/reportes/' . $supervision_id, 'refresh');
		}
	}

	public function reporte_cargos($supervision_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_escuelas) || $supervision_id == NULL || !ctype_digit($supervision_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$campos = array(
			'A' => array('Escuela', 10),
			'B' => array('Anexo', 10),
			'C' => array('Régimen', 40),
			'D' => array('Materia', 30),
			'E' => array('División', 20),
			'F' => array('Hs Cátedra', 15),
			'G' => array('Persona actual', 50),
		);
		$cargos = $this->db->select('e.numero, e.anexo, CONCAT(r.codigo, \' \', r.descripcion) as regimen, m.descripcion as materia, '
					. 'CONCAT(cu.descripcion, \' \', d.division) division, c.carga_horaria, CONCAT(COALESCE(p.cuil, p.documento), \' \', p.apellido, \' \', p.nombre) as persona')
				->from('cargo c')
				->join('escuela e', 'e.id = c.escuela_id')
				->join('division d', 'd.id = c.division_id', 'left')
				->join('curso cu', 'cu.id = d.curso_id', 'left')
				->join('espacio_curricular ec', 'ec.id = c.espacio_curricular_id', 'left')
				->join('materia m', 'ec.materia_id = m.id', 'left')
				->join('regimen r', 'r.id = c.regimen_id', 'left')
				->join('servicio sp', "sp.id = (SELECT id FROM servicio s WHERE s.cargo_id = c.id AND ((NOW() BETWEEN s.fecha_alta AND s.fecha_baja) OR NOW() >= s.fecha_alta AND s.fecha_baja IS NULL ) ORDER BY s.fecha_alta DESC LIMIT 1)", 'left')
				->join('persona p', 'p.id = sp.persona_id', 'left')
				->where('e.supervision_id', $supervision_id)
				->get()->result_array();

		if (!empty($cargos)) {
			$this->exportar_excel_supervision(array('title' => "Cargos_{$supervision_id}_" . date('Ymd')), $campos, $cargos);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect('supervision/reportes/' . $supervision_id, 'refresh');
		}
	}

	public function reporte_servicios($supervision_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_escuelas) || $supervision_id == NULL || !ctype_digit($supervision_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$campos = array(
			'A' => array('Escuela', 10),
			'B' => array('Anexo', 10),
			'C' => array('Régimen', 40),
			'D' => array('Materia', 30),
			'E' => array('División', 20),
			'F' => array('Hs Cátedra', 15),
			'G' => array('Persona', 50),
			'H' => array('Liquidación', 15),
			'I' => array('Alta', 11),
			'J' => array('Baja', 11),
		);
		$servicios = $this->db->select('e.numero, e.anexo, CONCAT(r.codigo, \' \', r.descripcion) as regimen, m.descripcion as materia, '
					. 'CONCAT(cu.descripcion, \' \', d.division) division, c.carga_horaria, CONCAT(COALESCE(p.cuil, p.documento), \' \', p.apellido, \' \', p.nombre) as persona, s.liquidacion, s.fecha_alta, s.fecha_baja')
				->from('servicio s')
				->join('cargo c', 'c.id = s.cargo_id')
				->join('escuela e', 'e.id = c.escuela_id')
				->join('division d', 'd.id = c.division_id', 'left')
				->join('curso cu', 'cu.id = d.curso_id', 'left')
				->join('espacio_curricular ec', 'ec.id = c.espacio_curricular_id', 'left')
				->join('materia m', 'ec.materia_id = m.id', 'left')
				->join('regimen r', 'r.id = c.regimen_id AND r.planilla_modalidad_id=1', '')
				->join('persona p', 'p.id = s.persona_id', 'left')
				->where('e.supervision_id', $supervision_id)
				->get()->result_array();

		if (!empty($servicios)) {
			$this->exportar_excel_supervision(array('title' => "Servicios_{$supervision_id}_" . date('Ymd')), $campos, $servicios);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect('supervision/reportes/' . $supervision_id, 'refresh');
		}
	}

	public function reporte_novedades($supervision_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_escuelas) || $supervision_id == NULL || !ctype_digit($supervision_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$campos = array(
			'A' => array('Escuela', 10),
			'B' => array('Anexo', 10),
			'C' => array('Régimen', 40),
			'D' => array('Materia', 30),
			'E' => array('División', 20),
			'F' => array('Hs Cátedra', 15),
			'G' => array('Persona', 50),
			'H' => array('Liquidación', 15),
			'I' => array('Artículo', 11),
			'J' => array('Desde', 11),
			'K' => array('Hasta', 11),
			'L' => array('Alta', 11),
			'M' => array('Baja', 11),
		);
		$novedades = $this->db->select('e.numero, e.anexo, CONCAT(r.codigo, \' \', r.descripcion) as regimen, m.descripcion as materia, '
					. 'CONCAT(cu.descripcion, \' \', d.division) division, c.carga_horaria, CONCAT(COALESCE(p.cuil, p.documento), \' \', p.apellido, \' \', p.nombre) as persona, '
					. 's.liquidacion, CONCAT(nt.articulo, \'-\', nt.inciso, \' \', nt.descripcion), sn.fecha_desde, sn.fecha_hasta, s.fecha_alta, s.fecha_baja')
				->from('servicio_novedad sn')
				->join('novedad_tipo nt', 'nt.id = sn.novedad_tipo_id')
				->join('servicio s', 's.id = sn.servicio_id')
				->join('cargo c', 'c.id = s.cargo_id')
				->join('escuela e', 'e.id = c.escuela_id')
				->join('division d', 'd.id = c.division_id', 'left')
				->join('curso cu', 'cu.id = d.curso_id', 'left')
				->join('espacio_curricular ec', 'ec.id = c.espacio_curricular_id', 'left')
				->join('materia m', 'ec.materia_id = m.id', 'left')
				->join('regimen r', 'r.id = c.regimen_id AND r.planilla_modalidad_id=1', '')
				->join('persona p', 'p.id = s.persona_id', 'left')
				->where('e.supervision_id', $supervision_id)
				->where('sn.planilla_baja_id IS NULL')
				->get()->result_array();

		if (!empty($novedades)) {
			$this->exportar_excel_supervision(array('title' => "Novedades_{$supervision_id}_" . date('Ymd')), $campos, $novedades);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect('supervision/reportes/' . $supervision_id, 'refresh');
		}
	}

	protected function exportar_excel_supervision($atributos, $campos, $registros) {
		if (!in_array($this->rol->codigo, $this->roles_escuelas)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->library('PHPExcel');
		$this->phpexcel->getProperties()->setTitle($atributos['title'])->setDescription("");
		$this->phpexcel->setActiveSheetIndex(0);

		$sheet = $this->phpexcel->getActiveSheet();
		$sheet->setTitle(substr($atributos['title'], 0, 30));
		$encabezado = array();
		$ultima_columna = 'A';
		foreach ($campos as $columna => $campo) {
			$encabezado[] = $campo[0];
			$sheet->getColumnDimension($columna)->setWidth($campo[1]);
			$ultima_columna = $columna;
		}

		$sheet->getStyle('A1:' . $ultima_columna . '1')->getFont()->setBold(true);

		$sheet->fromArray(array($encabezado), NULL, 'A1');
		$sheet->fromArray($registros, NULL, 'A2');

		header("Content-Type: application/vnd.ms-excel");
		$nombreArchivo = $atributos['title'];
		header("Content-Disposition: attachment; filename=\"$nombreArchivo.xls\"");
		header("Cache-Control: max-age=0");

		$writer = PHPExcel_IOFactory::createWriter($this->phpexcel, "Excel5");
		$writer->save('php://output');
		exit;
	}
}
/* End of file Supervision.php */
/* Location: ./application/controllers/Supervision.php */