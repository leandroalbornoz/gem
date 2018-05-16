<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cargo extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('cargo_model');
		$this->load->model('escuela_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ESCUELA_ALUM, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE_CURSADA));
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_GRUPO_ESCUELA_CONSULTA, ROL_REGIONAL, ROL_ESCUELA_ALUM))) {
			$this->edicion = FALSE;
		}
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION))) {
			$this->edicion_limitada = TRUE;
		}
		$this->nav_route = 'menu/cargo';
	}

	public function listar($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}

		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 2, 'class' => '', 'sortable' => 'false'),
				array('label' => 'Condición', 'data' => 'condicion_cargo', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 5),
				array('label' => 'Div', 'data' => 'division', 'width' => 8),
				array('label' => 'Tur', 'data' => 'turno', 'width' => 8),
				array('label' => 'Régimen/Materia', 'data' => 'regimen_materia', 'width' => 28, 'class' => 'text-sm'),
				array('label' => 'Hs.', 'data' => 'carga_horaria', 'class' => 'dt-body-right', 'width' => 4),
				array('label' => 'Servicios', 'data' => 'servicios', 'width' => 6, 'class' => 'dt-body-right', 'searchable' => 'false'),
				array('label' => '<span title="Servicio con fecha de ingreso más reciente sin baja dada">Persona Actual <i class="fa fa-question text-red"></i></span>', 'data' => 'persona', 'width' => 24, 'class' => 'text-sm'),
				array('label' => 'Observaciones', 'data' => 'observaciones', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(2, 'asc'), array(3, 'asc'), array(4, 'asc'), array(5, 'asc')),
			'table_id' => 'cargo_table',
			'source_url' => "cargo/listar_data/$escuela_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'details_format' => 'cargo_table_detalles',
			'reuse_var' => TRUE,
			'initComplete' => "complete_cargo_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['escuela'] = $escuela;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Cargos';
		$this->load_template('cargo/cargo_listar', $data);
	}

	public function listar_data($escuela_id, $rol_codigo, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->datatables
			->select('cargo.id, condicion_cargo.descripcion as condicion_cargo, curso.descripcion as curso, division.division as division, turno.descripcion as turno, cargo.aportes, '
				. 'CONCAT(regimen.codigo, \' \', regimen.descripcion, \'<br>\', COALESCE(materia.descripcion, \'\')) as regimen_materia, '
				. 'cargo.observaciones, cargo.carga_horaria, COUNT(DISTINCT servicio.id) as servicios, '
				. 'CONCAT(COALESCE(persona.cuil, \'\'), \'<br>\', COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona, CASE WHEN COUNT(DISTINCT llamado.id)=0 THEN \'\' ELSE COUNT(DISTINCT llamado.id) END as llamados')
			->unset_column('id')
			->from('cargo')
			->join('area', 'area.id = cargo.area_id', 'left')
			->join('turno', 'turno.id = cargo.turno_id', 'left')
			->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', 'left')
			->join('division', 'division.id = cargo.division_id', 'left')
			->join('curso', 'curso.id = division.curso_id', 'left')
			->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
			->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
			->join('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', '')
			->join('servicio', "servicio.cargo_id = cargo.id AND servicio.fecha_baja IS NULL", 'left')
			->join('servicio sp', "sp.id = (SELECT id FROM servicio s WHERE cargo_id = cargo.id AND s.fecha_baja IS NULL ORDER BY fecha_alta DESC LIMIT 1)", 'left', false)
			->join('llamado', "llamado.cargo_id=cargo.id AND llamado.estado='Pendiente'", 'left')
			->join('persona', 'persona.id = sp.persona_id', 'left')
			->where('cargo.escuela_id', $escuela_id)
			->where('cargo.fecha_hasta IS NULL')
			->group_by('cargo.id, condicion_cargo.descripcion, curso.descripcion, division.division, materia.descripcion, cargo.carga_horaria, regimen.codigo, regimen.descripcion')
			->add_column('', '', 'id');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="cargo/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item" href="cargo/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '<li><a class="dropdown-item" href="cargo/eliminar/$1" title="Eliminar"><i class="fa fa-remove"></i> Eliminar</a></li>'
				. '<li><a class="dropdown-item btn-primary" href="servicio/agregar/$1"><i class="fa fa-plus" id="btn-agregar"></i> Agregar servicio</a></li>'
				. '<li><a class="dropdown-item btn-primary" href="llamados/llamado/agregar_cargo/$1"><i class="fa fa-plus" id="btn-agregar"></i> Agregar llamado <span class="badge">$2</span></a></li>'
				. '</ul></div>', 'id, llamados');
		} else if ($this->edicion_limitada) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="cargo/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item btn-primary" href="llamados/llamado/agregar_cargo/$1"><i class="fa fa-plus" id="btn-agregar"></i> Agregar llamado <span class="badge">$2</span></a></li>'
				. '</ul></div>', 'id, llamados');
		} else {
			$this->datatables->add_column('edit', '<a class="btn btn-xs btn-default" href="cargo/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id');
		}
		echo $this->datatables->generate();
	}

	public function reubicar_servicios($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}

		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('servicio_model');
		$this->load->model('servicio_historial_model');
		$this->load->model('cargo_model');

		if (isset($_POST) && !empty($_POST)) {
			if ($this->input->post('origen') != NULL && $this->input->post('destino') != NULL) {
				$cargos_origen_ids = array_filter($this->input->post('origen'));
				$cargos_destino_ids = array_filter($this->input->post('destino'));

				if ($this->cargo_model->validar_cargos_reubicar($cargos_origen_ids, $cargos_destino_ids)) {
					foreach ($cargos_destino_ids as $cargo_origen => $cargo) {
						$servicios = $this->servicio_model->get(array(
							'cargo_id' => $cargo_origen,
							'where' => array('fecha_baja IS NULL')
						));
						$trans_ok = TRUE;
						if (!empty($servicios)) {
							foreach ($servicios as $servicio) {
								$trans_ok &= $this->servicio_historial_model->create(array(
									'cargo_id' => $cargo_origen,
									'servicio_id' => $servicio->id,
									'fecha_hasta' => $this->get_date_sql('fecha_hasta'),
									'motivo' => $this->input->post('motivo')
								));
							}
						}
						$servicios_reubicar[$cargo] = $servicios;
					}
					if (!empty($servicios_reubicar)) {
						foreach ($servicios_reubicar as $cargo_destino => $servicio_reubicar) {
							$trans_ok = TRUE;
							if (!empty($servicio_reubicar)) {
								foreach ($servicio_reubicar as $servicio_reubicado) {
									$trans_ok &= $this->servicio_model->update(array(
										'id' => $servicio_reubicado->id,
										'cargo_id' => $cargo_destino
									));
								}
							}
						}
					}
					if ($trans_ok) {
						$this->session->set_flashdata('message', "Los servicios han sido reubicados correctamente");
						redirect("cargo/reubicar_servicios/$escuela_id", 'refresh');
					} else {
						$this->session->set_flashdata('error', $this->servicio_model->get_error());
						redirect("cargo/reubicar_servicios/$escuela_id", 'refresh');
					}
				} else {
					$this->session->set_flashdata('error', 'Ocurrió un error al validar los cargos de los servicios a reubicar');
					redirect("cargo/reubicar_servicios/$escuela_id", 'refresh');
				}
			}
		}

		$tableData = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 2, 'class' => '', 'sortable' => 'false'),
				array('label' => 'Id', 'data' => 'id', 'width' => 5, 'class' => 'text-bold'),
				array('label' => 'Condición', 'data' => 'condicion_cargo', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 5),
				array('label' => 'Div', 'data' => 'division', 'width' => 8),
				array('label' => 'Tur', 'data' => 'turno', 'width' => 8),
				array('label' => 'Materia', 'data' => 'regimen_materia', 'width' => 28, 'class' => 'text-sm'),
				array('label' => 'Servicios', 'data' => 'persona', 'width' => 40, 'class' => 'text-sm'),
				array('label' => 'Observaciones', 'data' => 'observaciones', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'visible' => 'false', 'data' => 'regimen_id'),
				array('label' => '', 'visible' => 'false', 'data' => 'carga_horaria'),
				array('label' => 'Destino', 'data' => 'destino', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(2, 'asc'), array(3, 'asc'), array(4, 'asc'), array(5, 'asc')),
			'table_id' => 'cargo_table',
			'source_url' => "cargo/reubicar_servicios_data/$escuela_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'details_format' => 'cargo_table_detalles',
			'reuse_var' => TRUE,
			'initComplete' => "complete_cargo_table",
			'footer' => TRUE,
			'saveState' => FALSE,
			'disablePagination' => TRUE,
			'dom' => 'rt'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$this->load->model('regimen_model');
		$data['array_regimen'] = $this->get_array('regimen', 'regimen', 'id', array('select' => array('regimen.id', "CONCAT(regimen.codigo,' ', regimen.descripcion) regimen"),
			'join' => array(array('cargo', "cargo.regimen_id=regimen.id AND cargo.escuela_id={$escuela->id} AND cargo.fecha_hasta IS NULL")),
			'group_by' => 'regimen.id',
			'having' => array('COUNT(1)>1')
		));
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$data['fields'] = $this->build_fields($this->servicio_historial_model->fields);
		$data['escuela'] = $escuela;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Cargos';
		$this->load_template('cargo/cargo_reubicar_servicios', $data);
	}

	public function reubicar_servicios_data($escuela_id, $rol_codigo, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->datatables
			->select("cargo.id, condicion_cargo.descripcion as condicion_cargo, curso.descripcion as curso, division.division as division, turno.descripcion as turno, cargo.aportes, CONCAT(regimen.codigo, ' ', CASE WHEN cargo.carga_horaria=0 THEN '' ELSE CONCAT('(',cargo.carga_horaria,')') END, '<br>', COALESCE(materia.descripcion, '')) as regimen_materia, cargo.observaciones, cargo.carga_horaria, COUNT(DISTINCT servicio.id) as servicios, GROUP_CONCAT(CONCAT('<b>',LEFT(situacion_revista.descripcion,1),'</b> ', COALESCE(persona.cuil, ''), ' ', COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) ORDER BY situacion_revista.planilla_tipo_id, servicio.fecha_alta SEPARATOR '<br>' ) as persona, cargo.regimen_id")
			->exact_search('cargo.regimen_id')
			->exact_search('cargo.carga_horaria')
			->unset_column('id')
			->from('cargo')
			->join('area', 'area.id = cargo.area_id', 'left')
			->join('turno', 'turno.id = cargo.turno_id', 'left')
			->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', 'left')
			->join('division', 'division.id = cargo.division_id', 'left')
			->join('curso', 'curso.id = division.curso_id', 'left')
			->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
			->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
			->join('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', '')
			->join('servicio', "servicio.cargo_id = cargo.id AND servicio.fecha_baja IS NULL", 'left')
			->join('situacion_revista', "servicio.situacion_revista_id = situacion_revista.id", 'left')
			->join('persona', 'persona.id = servicio.persona_id', 'left')
			->where('cargo.escuela_id', $escuela_id)
			->where('cargo.fecha_hasta IS NULL')
			->group_by('cargo.id, condicion_cargo.descripcion, curso.descripcion, division.division, materia.descripcion, cargo.carga_horaria, regimen.codigo, regimen.descripcion')
			->add_column('', '', 'id');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="cargo/ver/$1" target="_blank" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item" href="cargo/editar/$1" target="_blank" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '<li><a class="dropdown-item" href="cargo/eliminar/$1" target="_blank" title="Eliminar"><i class="fa fa-remove"></i> Eliminar</a></li>'
				. '</ul></div>', 'id, escuela_id');
			$this->datatables->add_column('destino', '<input class="text-sm reubicar-origen" placeholder="Traer de" type="text" name="origen[$1]" readonly data-id="$1"><input class="text-sm reubicar-destino" placeholder="Mover a" type="text" name="destino[$1]" readonly data-id="$1">', 'id');
		} else {
			$this->datatables->add_column('destino', '', '');
			$this->datatables->add_column('edit', '<a class="btn btn-xs btn-default" href="cargo/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id');
		}
		echo $this->datatables->generate();
	}

	public function listar_cargosc($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 2, 'class' => '', 'sortable' => 'false'),
				array('label' => 'Condición', 'data' => 'condicion_cargo', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 5),
				array('label' => 'Div', 'data' => 'division', 'width' => 8),
				array('label' => 'Tur', 'data' => 'turno', 'width' => 8),
				array('label' => 'Régimen/Materia', 'data' => 'regimen_materia', 'width' => 28, 'class' => 'text-sm'),
				array('label' => 'Hs.', 'data' => 'carga_horaria', 'class' => 'dt-body-right', 'width' => 4),
				array('label' => 'Servicios', 'data' => 'servicios', 'width' => 6, 'class' => 'dt-body-right', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => '<span title="Servicio con fecha de ingreso más reciente sin baja dada">Persona Actual <i class="fa fa-question text-red"></i></span>', 'data' => 'persona', 'width' => 24, 'class' => 'text-sm'),
				array('label' => 'Observaciones', 'data' => 'observaciones', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(2, 'asc'), array(3, 'asc'), array(4, 'asc'), array(5, 'asc')),
			'table_id' => 'cargoc_table',
			'source_url' => "cargo/listar_data_cargoc/$escuela_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'details_format' => 'cargoc_table_detalles',
			'reuse_var' => TRUE,
			'initComplete' => "complete_cargoc_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['escuela'] = $escuela;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Cargos';
		$this->load_template('cargo/cargoc_listar', $data);
	}

	public function listar_data_cargoc($escuela_id, $rol_codigo, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->datatables
			->select('cargo.id, condicion_cargo.descripcion as condicion_cargo, curso.descripcion as curso, division.division as division, turno.descripcion as turno, cargo.aportes, '
				. 'CONCAT(regimen.codigo, \' \', regimen.descripcion, \'<br>\', COALESCE(materia.descripcion, \'\')) as regimen_materia, '
				. 'cargo.observaciones, cargo.carga_horaria, COUNT(DISTINCT servicio.id) as servicios, '
				. 'CONCAT(COALESCE(persona.cuil, \'\'), \'<br>\', COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona')
			->unset_column('id')
			->from('cargo')
			->join('area', 'area.id = cargo.area_id', 'left')
			->join('turno', 'turno.id = cargo.turno_id', 'left')
			->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', 'left')
			->join('division', 'division.id = cargo.division_id', 'left')
			->join('curso', 'curso.id = division.curso_id', 'left')
			->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
			->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
			->join('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', '')
			->join('servicio', "servicio.cargo_id = cargo.id AND servicio.fecha_baja IS NULL", 'left')
			->join('servicio sp', "sp.id = (SELECT id FROM servicio s WHERE cargo_id = cargo.id AND s.fecha_baja IS NULL ORDER BY fecha_alta DESC LIMIT 1)", 'left', false)
			->join('persona', 'persona.id = sp.persona_id', 'left')
			->where('cargo.escuela_id', $escuela_id)
			->where('cargo.fecha_hasta != ""')
			->group_by('cargo.id, condicion_cargo.descripcion, curso.descripcion, division.division, materia.descripcion, cargo.carga_horaria, regimen.codigo, regimen.descripcion')
			->add_column('', '', 'id');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="cargo/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a class="dropdown-item" href="cargo/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
				. '<li><a class="dropdown-item" href="cargo/eliminar/$1" title="Eliminar"><i class="fa fa-remove"></i> Eliminar</a></li>'
				. '<li><a class="dropdown-item btn-primary" href="servicio/agregar/$1"><i class="fa fa-plus" id="btn-agregar"></i> Agregar servicio</a></li>'
				. '</ul></div>', 'id, escuela_id');
		} else {
			$this->datatables->add_column('edit', '<a class="btn btn-xs btn-default" href="cargo/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id');
		}

		echo $this->datatables->generate();
	}

	public function unificar($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("cargo/listar/$escuela_id");
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->form_validation->set_rules('cargo[]', 'Cargos', 'integer|required');
		if ($this->form_validation->run() === TRUE) {
			$cargos_id = $this->input->post('cargo');
			if (empty($cargos_id)) {
				$this->session->set_flashdata('error', 'Debe seleccionar los cargos a unificar');
				redirect("cargo/unificar/$escuela->id", 'refresh');
			}

			$cargos = $this->cargo_model->get(array(
				'escuela_id' => $escuela->id,
				'join' => array(array('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1')),
				'where' => array('cargo.id IN (' . implode(',', $cargos_id) . ')')
			));
			$cargo_rows = count($cargos);
			if ($cargo_rows === count($cargos_id)) {
				$cargo = $cargos[0];
				$cargos_delete = array();
				for ($i = 1; $i < $cargo_rows; $i++) {
					if (
						$cargos[$i]->division_id !== $cargo->division_id ||
						$cargos[$i]->espacio_curricular_id !== $cargo->espacio_curricular_id ||
						$cargos[$i]->condicion_cargo_id !== $cargo->condicion_cargo_id ||
						$cargos[$i]->carga_horaria !== $cargo->carga_horaria ||
						$cargos[$i]->regimen_id !== $cargo->regimen_id
					) {
						$this->session->set_flashdata('error', 'Ocurrió un error al intentar unificar los cargos, no coincide división / materia / condición / regimen / carga Horaria');
						redirect("cargo/unificar/$escuela->id", 'refresh');
					} else {
						$cargos_delete[] = $cargos[$i]->id;
					}
				}
				if (empty($cargos_delete)) {
					$this->session->set_flashdata('error', 'Debe seleccionar al menos dos cargos para unificar');
					redirect("cargo/unificar/$escuela->id", 'refresh');
				}
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$this->load->model('servicio_model');
				$servicios = $this->servicio_model->get(array('select' => 'id', 'where' => array('cargo_id IN (' . implode(',', $cargos_delete) . ')')));
				if (!empty($servicios)) {
					foreach ($servicios as $servicio) {
						$trans_ok &= $this->servicio_model->update(array('id' => $servicio->id, 'cargo_id' => $cargo->id), FALSE);
					}
				}
				$this->load->model('servicio_historial_model');
				$historial_servicios = $this->servicio_historial_model->get(array('select' => 'id', 'where' => array('cargo_id IN (' . implode(',', $cargos_delete) . ')')));
				if (!empty($historial_servicios)) {
					foreach ($historial_servicios as $historial_servicio) {
						$trans_ok &= $this->servicio_historial_model->update(array('id' => $historial_servicio->id, 'cargo_id' => $cargo->id), FALSE);
					}
				}
				$this->load->model('llamados/llamado_model');
				$llamados = $this->llamado_model->get(array('select' => 'id', 'where' => array('cargo_id IN (' . implode(',', $cargos_delete) . ')')));
				if (!empty($llamados)) {
					foreach ($llamados as $llamado) {
						$trans_ok &= $this->llamado_model->update(array('id' => $llamado->id, 'cargo_id' => $cargo->id), FALSE);
					}
				}
				$this->load->model('horario_model');
				$horarios = $this->horario_model->get(array('select' => 'id', 'where' => array('cargo_id IN (' . implode(',', $cargos_delete) . ')')));
				if (!empty($horarios)) {
					foreach ($horarios as $horario) {
						$trans_ok &= $this->horario_model->update(array('id' => $horario->id, 'cargo_id' => $cargo->id), FALSE);
					}
				}
				$this->load->model('cargo_horario_model');
				$cargo_horarios = $this->cargo_horario_model->get(array(
					'select' => 'id',
					'where' => array('cargo_id IN (' . implode(',', $cargos_delete) . ')')
				));
				if (!empty($cargo_horarios)) {
					foreach ($cargo_horarios as $cargo_horario) {
						$trans_ok &= $this->cargo_horario_model->update(array('id' => $cargo_horario->id, 'cargo_id' => $cargo->id), FALSE);
					}
				}
				foreach ($cargos_delete as $cargo_delete) {
					$trans_ok &= $this->cargo_model->delete(array('id' => $cargo_delete), FALSE);
				}

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', 'Cargos unificados');
					redirect("cargo/unificar/$escuela->id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar unificar cargos.';
					if ($this->servicio_model->get_error())
						$errors .= '<br>' . $this->servicio_model->get_error();
					if ($this->cargo_model->get_error())
						$errors .= '<br>' . $this->cargo_model->get_error();
					$this->session->set_flashdata('error', $errors);
					redirect("cargo/unificar/$escuela->id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', 'Ocurrió un error al intentar unificar los cargos');
				redirect("cargo/unificar/$escuela->id", 'refresh');
			}
		}
		$tableData = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 2, 'class' => '', 'sortable' => 'false'),
				array('label' => 'Condición', 'data' => 'condicion_cargo', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 5),
				array('label' => 'Div', 'data' => 'division', 'width' => 16),
				array('label' => 'Régimen/Materia', 'data' => 'regimen_materia', 'width' => 28, 'class' => 'text-sm'),
				array('label' => 'Hs.', 'data' => 'carga_horaria', 'class' => 'dt-body-right', 'width' => 4),
				array('label' => 'Observaciones', 'data' => 'observaciones', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Servicios', 'data' => 'servicios', 'width' => 6, 'class' => 'dt-body-right', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => 'Persona Actual', 'data' => 'persona', 'width' => 24, 'class' => 'text-sm'),
				array('label' => '', 'data' => 'select', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(2, 'asc'), array(3, 'asc'), array(4, 'asc'), array(5, 'asc')),
			'table_id' => 'cargo_table',
			'source_url' => "cargo/unificar_data/$escuela_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'details_format' => 'cargo_table_detalles',
			'reuse_var' => TRUE,
			'initComplete' => "complete_cargo_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['escuela'] = $escuela;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Cargos';
		$this->load_template('cargo/cargo_unificar', $data);
	}

	public function unificar_data($escuela_id, $rol_codigo, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('cargo.id, condicion_cargo.descripcion as condicion_cargo, curso.descripcion as curso, division.division as division, '
				. 'CONCAT(regimen.codigo, \' \', regimen.descripcion, \'<br>\', COALESCE(materia.descripcion, \'\')) as regimen_materia, '
				. 'cargo.observaciones, cargo.carga_horaria, COUNT(DISTINCT servicio.id) as servicios, '
				. 'CONCAT(COALESCE(persona.cuil, \'\'), \'<br>\', COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona')
			->unset_column('id')
			->from('cargo')
			->join('area', 'area.id = cargo.area_id', 'left')
			->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', 'left')
			->join('division', 'division.id = cargo.division_id', 'left')
			->join('curso', 'curso.id = division.curso_id', 'left')
			->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
			->join('carrera', 'espacio_curricular.carrera_id = carrera.id', 'left')
			->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
			->join('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', '')
			->join('servicio', 'servicio.cargo_id = cargo.id AND servicio.fecha_baja IS NULL', 'left')
			->join('servicio sp', "sp.id = (SELECT id FROM servicio s WHERE cargo_id = cargo.id AND s.fecha_baja IS NULL ORDER BY fecha_alta DESC LIMIT 1)", 'left', false)
			->join('persona', 'persona.id = sp.persona_id', 'left')
			->where('cargo.escuela_id', $escuela_id)
			->where('cargo.fecha_hasta IS NULL')
			->group_by('cargo.id, condicion_cargo.descripcion, curso.descripcion, division.division, materia.descripcion, carrera.descripcion, cargo.carga_horaria, regimen.codigo, regimen.descripcion')
			->add_column('', '', 'id')
			->add_column('select', '<input type="checkbox" name="cargo[]" value="$1">', 'id, escuela_id');

		echo $this->datatables->generate();
	}

	public function mover($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("cargo/listar/$escuela_id");
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->array_anexo_control = $array_anexo = $this->get_array('escuela', 'nombre_largo', 'id', array('numero' => $escuela->numero, 'id !=' => $escuela->id, 'sort_by' => 'anexo'), array('' => '-- Seleccione anexo --'));
		unset($this->array_anexo_control['']);
		$model = new stdClass();
		$model->fields = array(
			'anexo' => array('label' => 'Anexo', 'input_type' => 'combo', 'array' => $array_anexo, 'required' => TRUE)
		);
		$this->set_model_validation_rules($model);
		$this->form_validation->set_rules('cargo[]', 'Cargos', 'integer|required');
		if ($this->form_validation->run() === TRUE) {
			$cargos_id = $this->input->post('cargo');
			if (empty($cargos_id)) {
				$this->session->set_flashdata('error', 'Debe seleccionar los cargos a unificar');
				redirect("cargo/mover/$escuela->id", 'refresh');
			}

			$cargos = $this->cargo_model->get(array(
				'escuela_id' => $escuela->id,
				'join' => array(array('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', '')),
				'where' => array('cargo.id IN (' . implode(',', $cargos_id) . ')', 'division_id IS NULL')
			));
			if (!empty($cargos) && count($cargos) === count($cargos_id)) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				foreach ($cargos as $cargo) {
					$trans_ok &= $this->cargo_model->update(array('id' => $cargo->id, 'escuela_id' => $this->input->post('anexo')), FALSE);
				}

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', 'Cargos movidos exitosamente');
					redirect("cargo/mover/$escuela->id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar mover cargos.';
					if ($this->cargo_model->get_error())
						$errors .= '<br>' . $this->cargo_model->get_error();
					$this->session->set_flashdata('error', $errors);
					redirect("cargo/mover/$escuela->id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', 'Ocurrió un error al intentar mover cargos');
				redirect("cargo/mover/$escuela->id", 'refresh');
			}
		}
		$tableData = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 2, 'class' => '', 'sortable' => 'false'),
				array('label' => 'Condición', 'data' => 'condicion_cargo', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 5),
				array('label' => 'Div', 'data' => 'division', 'width' => 16),
				array('label' => 'Régimen/Materia', 'data' => 'regimen_materia', 'width' => 28, 'class' => 'text-sm'),
				array('label' => 'Hs.', 'data' => 'carga_horaria', 'class' => 'dt-body-right', 'width' => 4),
				array('label' => 'Observaciones', 'data' => 'observaciones', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Servicios', 'data' => 'servicios', 'width' => 6, 'class' => 'dt-body-right', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => 'Persona Actual', 'data' => 'persona', 'width' => 24, 'class' => 'text-sm'),
				array('label' => '', 'data' => 'select', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(2, 'asc'), array(3, 'asc'), array(4, 'asc'), array(5, 'asc')),
			'table_id' => 'cargo_table',
			'source_url' => "cargo/mover_data/$escuela_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'details_format' => 'cargo_table_detalles',
			'reuse_var' => TRUE,
			'initComplete' => "complete_cargo_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);

		$data['fields'] = $this->build_fields($model->fields);
		$data['escuela'] = $escuela;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Cargos';
		$this->load_template('cargo/cargo_mover', $data);
	}

	public function mover_data($escuela_id, $rol_codigo, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('cargo.id, condicion_cargo.descripcion as condicion_cargo, curso.descripcion as curso, division.division as division, '
				. 'CONCAT(regimen.codigo, \' \', regimen.descripcion, \'<br>\', COALESCE(materia.descripcion, \'\')) as regimen_materia, '
				. 'cargo.observaciones, cargo.carga_horaria, COUNT(DISTINCT servicio.id) as servicios, '
				. 'CONCAT(COALESCE(persona.cuil, \'\'), \'<br>\', COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona')
			->unset_column('id')
			->from('cargo')
			->join('area', 'area.id = cargo.area_id', 'left')
			->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', 'left')
			->join('division', 'division.id = cargo.division_id', 'left')
			->join('curso', 'curso.id = division.curso_id', 'left')
			->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
			->join('carrera', 'espacio_curricular.carrera_id = carrera.id', 'left')
			->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
			->join('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', '')
			->join('servicio', 'servicio.cargo_id = cargo.id AND servicio.fecha_baja IS NULL', 'left')
			->join('servicio sp', "sp.id = (SELECT id FROM servicio s WHERE cargo_id = cargo.id AND s.fecha_baja IS NULL ORDER BY fecha_alta DESC LIMIT 1)", 'left', false)
			->join('persona', 'persona.id = sp.persona_id', 'left')
			->where('cargo.escuela_id', $escuela_id)
			->where('cargo.division_id IS NULL')
			->group_by('cargo.id, condicion_cargo.descripcion, curso.descripcion, division.division, materia.descripcion, carrera.descripcion, cargo.carga_horaria, regimen.codigo, regimen.descripcion')
			->add_column('', '', 'id')
			->add_column('select', '<input type="checkbox" name="cargo[]" value="$1">', 'id, escuela_id');

		echo $this->datatables->generate();
	}

	public function agregar($escuela_id = NULL, $division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || ($division_id !== NULL && !ctype_digit($division_id))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			if (empty($division_id)) {
				redirect("cargo/listar/$escuela_id");
			} else {
				redirect("division/cargos/$division_id");
			}
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		if ($escuela->nivel_id == 7) {
			$this->cargo_model->fields['cuatrimestre'] = array('label' => 'Cuatrimestre', 'input_type' => 'combo', 'id_name' => 'cuatrimestre', 'class' => 'selectize', 'array' => $this->cargo_model->get_cuatrimestres());
			$this->array_cuatrimestre_control = $this->cargo_model->fields['cuatrimestre']['array'];
		}

		if ($escuela->dependencia_id !== '2') {
			unset($this->cargo_model->fields['aportes']);
		}
		$this->load->model('division_model');
		if (!empty($division_id)) {
			$division = $this->division_model->get(array('id' => $division_id));
			if (empty($division)) {
				show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
			} else {
				$data['division'] = $division;
			}
		}

		$this->load->model('condicion_cargo_model');
		$this->load->model('carrera_model');
		$this->load->model('espacio_curricular_model');
		$this->load->model('regimen_model');
		$this->load->model('turno_model');
		$this->array_condicion_cargo_control = $array_condicion_cargo = $this->get_array('condicion_cargo', 'descripcion', 'id', null, array('' => '-- Seleccionar condición de cargo --'));
		if (empty($_POST['division']) && !empty($division_id)) {
			$this->cargo_model->fields['division']['value'] = $division_id;
		}
		$this->array_division_control = $array_division = $this->get_array('division', 'division', 'id', array(
			'select' => array('division.id', 'CONCAT(curso.descripcion, \' \', division.division) as division'),
			'join' => array(array('curso', 'curso.id=division.curso_id')),
			'escuela_id' => $escuela->id,
			'where' => array('fecha_baja IS NULL'),
			'sort_by' => 'curso.descripcion, division.division'
			), array('' => '-- Sin división --'));
		if (empty($_POST['carrera']) && empty($_POST['division'])) {
			$this->array_carrera_control = $array_carrera = $this->get_array('carrera', 'descripcion', 'id', array(
				'join' => array(
					array('division', 'carrera.id=division.carrera_id AND division.escuela_id=' . $escuela->id),
				),
				'group_by' => 'carrera.id',
				'sort_by' => 'carrera.descripcion'
				), array('' => '-- Sin carrera --'));
			$this->array_espacio_curricular_control = $array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
				'select' => array('MIN(espacio_curricular.id) id', 'materia.descripcion materia'),
				'join' => array(
					array('materia', 'materia.id=espacio_curricular.materia_id', 'left'),
					array('curso', 'curso.id=espacio_curricular.curso_id', 'left')
				),
				'where' => array('carrera_id IS NULL', "curso.nivel_id=$escuela->nivel_id"),
				'group_by' => 'espacio_curricular.materia_id'
				), array('' => '-- Sin materia --'));
		} elseif (empty($_POST['carrera'])) {
			$this->array_carrera_control = $array_carrera = array('' => '');
			$array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
				'join' => array(
					array('materia', 'materia.id=espacio_curricular.materia_id', 'left', array('materia.descripcion as materia')),
					array('division', 'division.carrera_id=espacio_curricular.carrera_id'),
				),
				'where' => array(array('column' => 'division.id', 'value' => $this->input->post('division'))),
				), array('' => '-- Sin materia --'));
			$this->array_espacio_curricular_control = $array_espacio_curricular = $array_espacio_curricular + $this->espacio_curricular_model->get_extracurriculares($this->input->post('division'));
		} else {
			$this->array_carrera_control = $array_carrera = $this->get_array('carrera', 'descripcion', 'id', array(
				'join' => array(
					array('division', 'carrera.id=division.carrera_id AND division.escuela_id=' . $escuela->id),
				),
				'group_by' => 'carrera.id',
				'sort_by' => 'carrera.descripcion'
				), array('' => '-- Sin carrera --'));
			$this->array_espacio_curricular_control = $array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
				'select' => array('MIN(espacio_curricular.id) id', 'materia.descripcion materia'),
				'join' => array(
					array('materia', 'materia.id=espacio_curricular.materia_id', 'left'),
					array('curso', 'curso.id=espacio_curricular.curso_id', 'left')
				),
				'where' => array("(carrera_id IS NULL OR carrera_id = " . $this->input->post('carrera') . ")", "curso.nivel_id=$escuela->nivel_id"),
				'group_by' => 'espacio_curricular.materia_id'
				), array('' => '-- Sin materia --'));
		}
		$this->array_regimen_control = $array_regimen = $this->get_array('regimen', 'regimen', 'id', array(
			'select' => array('regimen.id', 'CONCAT(regimen.codigo, \' \',regimen.descripcion) as regimen'),
			'join' => array(
				array('regimen_lista_regimen', 'regimen_lista_regimen.regimen_id=regimen.id', 'left'),
				array('escuela', 'regimen_lista_regimen.regimen_lista_id=escuela.regimen_lista_id', 'left')
			),
			'where' => array("escuela.id = $escuela->id OR regimen.dependencia_id = $escuela->dependencia_id")
			), array('' => '-- Seleccionar régimen --'));
		$this->array_turno_control = $array_turno = $this->get_array('turno', 'descripcion', 'id', array(), array('' => '-- Seleccionar turno --'));

		if (isset($_POST) && !empty($_POST)) {
			if ($this->input->post('tipo_regimen') === 'Hora') {
				$this->cargo_model->fields['carga_horaria']['required'] = TRUE;
			}
			$this->set_model_validation_rules($this->cargo_model);
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->cargo_model->create(array(
					'condicion_cargo_id' => $this->input->post('condicion_cargo'),
					'turno_id' => $this->input->post('turno'),
					'division_id' => $this->input->post('division'),
					'espacio_curricular_id' => $this->input->post('espacio_curricular'),
					'carga_horaria' => empty($this->input->post('carga_horaria')) ? '0' : $this->input->post('carga_horaria'),
					'regimen_id' => $this->input->post('regimen'),
					'aportes' => isset($this->cargo_model->fields['aportes']) ? $this->input->post('aportes') : '0',
					'escuela_id' => $escuela_id,
					'codigo_junta' => $this->input->post('codigo_junta'),
					'resolucion_alta' => $this->input->post('resolucion_alta'),
					'observaciones' => $this->input->post('observaciones')
				));
				$cargo_id = $this->cargo_model->get_row_id();
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->cargo_model->get_msg());
					redirect("servicio/agregar/$cargo_id", 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->cargo_model->get_error() ? $this->cargo_model->get_error() : $this->session->flashdata('error')));

		$this->cargo_model->fields['condicion_cargo']['array'] = $array_condicion_cargo;
		$this->cargo_model->fields['division']['array'] = $array_division;
		$this->cargo_model->fields['carrera']['array'] = $array_carrera;
		$this->cargo_model->fields['espacio_curricular']['array'] = $array_espacio_curricular;
		$this->cargo_model->fields['regimen']['array'] = $array_regimen;
		$this->cargo_model->fields['turno']['array'] = $array_turno;

		$data['fields'] = $this->build_fields($this->cargo_model->fields);
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled', 'horarios' => 'disabled', 'cerrar_abrir' => 'disabled');
		$data['title'] = TITLE . ' - Agregar cargo';
		$this->load_template('cargo/cargo_abm', $data);
	}

	public function editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("cargo/ver/$id");
		}

		$cargo = $this->cargo_model->get(array('id' => $id,
			'join' => array(
				array('espacio_curricular', 'cargo.espacio_curricular_id=espacio_curricular.id', 'left', array('espacio_curricular.carrera_id')),
				array('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1', '', array('CONCAT(regimen.codigo, \' \',regimen.descripcion) as regimen'))
			)
		));
		if (empty($cargo)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		if (empty($cargo->escuela_id)) {
			$this->session->keep_flashdata('message');
			$this->session->keep_flashdata('error');
			$this->load->model('servicio_model');
			$servicios = $this->servicio_model->get(array('cargo_id' => $id));
			redirect("areas/personal/editar/" . $servicios[0]->id);
		}
		$escuela = $this->escuela_model->get_one($cargo->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		if ($escuela->dependencia_id !== '2') {
			unset($this->cargo_model->fields['aportes']);
		}
		$this->load->model('condicion_cargo_model');
		$this->load->model('division_model');
		$this->load->model('carrera_model');
		$this->load->model('espacio_curricular_model');
		$this->load->model('regimen_model');
		$this->load->model('turno_model');
		$this->array_condicion_cargo_control = $array_condicion_cargo = $this->get_array('condicion_cargo', 'descripcion', 'id', null, array('' => '-- Seleccionar condición de cargo --'));
		$this->array_division_control = $array_division = $this->get_array('division', 'division', 'id', array(
			'select' => array('division.id', 'CONCAT(curso.descripcion, \' \', division.division) as division'),
			'join' => array(
				array('curso', 'curso.id=division.curso_id')
			),
			'escuela_id' => $escuela->id,
			'where' => array('fecha_baja IS NULL'),
			'sort_by' => 'curso.descripcion, division.division'
			), array('' => '-- Sin división --'));
		$this->array_turno_control = $array_turno = $this->get_array('turno', 'descripcion', 'id', array(), array('' => '-- Seleccionar turno --'));
		if ($escuela->nivel_id == 7) {
			$this->cargo_model->fields['cuatrimestre'] = array('label' => 'Cuatrimestre', 'input_type' => 'combo', 'id_name' => 'cuatrimestre', 'class' => 'selectize', 'array' => $this->cargo_model->get_cuatrimestres());
			$this->array_cuatrimestre_control = $this->cargo_model->fields['cuatrimestre']['array'];
		}
		if (empty($_POST)) {
			if (empty($cargo->carrera_id) && empty($cargo->division_id)) {
				$this->array_carrera_control = $array_carrera = $this->get_array('carrera', 'descripcion', 'id', array(
					'join' => array(
						array('division', 'carrera.id=division.carrera_id AND division.escuela_id=' . $escuela->id),
					),
					'group_by' => 'carrera.id',
					'sort_by' => 'carrera.descripcion'
					), array('' => '-- Sin carrera --'));
				$this->array_espacio_curricular_control = $array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
					'select' => array('MIN(espacio_curricular.id) id', 'materia.descripcion materia'),
					'join' => array(
						array('materia', 'materia.id=espacio_curricular.materia_id', 'left'),
						array('curso', 'curso.id=espacio_curricular.curso_id', 'left')
					),
					'where' => array('carrera_id IS NULL', "curso.nivel_id=$escuela->nivel_id"),
					'group_by' => 'espacio_curricular.materia_id'
					), array('' => '-- Sin materia --'));
			} elseif (empty($cargo->carrera_id)) {
				$this->array_carrera_control = $array_carrera = array('' => '');
				$array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
					'join' => array(
						array('materia', 'materia.id=espacio_curricular.materia_id', 'left', array('materia.descripcion as materia')),
						array('division', 'division.carrera_id=espacio_curricular.carrera_id'),
					),
					'where' => array(array('column' => 'division.id', 'value' => $cargo->division_id)),
					), array('' => '-- Sin materia --'));
				$this->array_espacio_curricular_control = $array_espacio_curricular = $array_espacio_curricular + $this->espacio_curricular_model->get_extracurriculares($cargo->division_id);
			} else {
				$this->array_carrera_control = $array_carrera = $this->get_array('carrera', 'descripcion', 'id', array(
					'join' => array(
						array('division', 'carrera.id=division.carrera_id AND division.escuela_id=' . $escuela->id),
					),
					'group_by' => 'carrera.id',
					'sort_by' => 'carrera.descripcion'
					), array('' => '-- Sin carrera --'));
				$this->array_espacio_curricular_control = $array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
					'select' => array('MIN(espacio_curricular.id) id', 'materia.descripcion materia'),
					'join' => array(
						array('materia', 'materia.id=espacio_curricular.materia_id', 'left'),
						array('curso', 'curso.id=espacio_curricular.curso_id', 'left')
					),
					'where' => array("(carrera_id IS NULL OR carrera_id = $cargo->carrera_id)", "curso.nivel_id=$escuela->nivel_id"),
					'group_by' => 'espacio_curricular.materia_id'
					), array('' => '-- Sin materia --'));
			}
		} else {
			if (empty($_POST['carrera']) && empty($_POST['division'])) {
				$this->array_carrera_control = $array_carrera = $this->get_array('carrera', 'descripcion', 'id', array(
					'join' => array(
						array('division', 'carrera.id=division.carrera_id AND division.escuela_id=' . $escuela->id),
					),
					'group_by' => 'carrera.id',
					'sort_by' => 'carrera.descripcion'
					), array('' => '-- Sin carrera --'));
				$this->array_espacio_curricular_control = $array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
					'select' => array('MIN(espacio_curricular.id) id', 'materia.descripcion materia'),
					'join' => array(
						array('materia', 'materia.id=espacio_curricular.materia_id', 'left'),
						array('curso', 'curso.id=espacio_curricular.curso_id', 'left')
					),
					'where' => array('carrera_id IS NULL', "curso.nivel_id=$escuela->nivel_id"),
					'group_by' => 'espacio_curricular.materia_id'
					), array('' => '-- Sin materia --'));
			} elseif (empty($_POST['carrera'])) {
				$this->array_carrera_control = $array_carrera = array('' => '');
				$array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
					'join' => array(
						array('materia', 'materia.id=espacio_curricular.materia_id', 'left', array('materia.descripcion as materia')),
						array('division', 'division.carrera_id=espacio_curricular.carrera_id'),
					),
					'where' => array(array('column' => 'division.id', 'value' => $this->input->post('division'))),
					), array('' => '-- Sin materia --'));
				$this->array_espacio_curricular_control = $array_espacio_curricular = $array_espacio_curricular + $this->espacio_curricular_model->get_extracurriculares($this->input->post('division'));
			} else {
				$this->array_carrera_control = $array_carrera = $this->get_array('carrera', 'descripcion', 'id', array(
					'join' => array(
						array('division', 'carrera.id=division.carrera_id AND division.escuela_id=' . $escuela->id),
					),
					'group_by' => 'carrera.id',
					'sort_by' => 'carrera.descripcion'
					), array('' => '-- Sin carrera --'));
				$this->array_espacio_curricular_control = $array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
					'select' => array('MIN(espacio_curricular.id) id', 'materia.descripcion materia'),
					'join' => array(
						array('materia', 'materia.id=espacio_curricular.materia_id', 'left'),
						array('curso', 'curso.id=espacio_curricular.curso_id', 'left')
					),
					'where' => array("(carrera_id IS NULL OR carrera_id = " . $this->input->post('carrera') . ")", "curso.nivel_id=$escuela->nivel_id"),
					'group_by' => 'espacio_curricular.materia_id'
					), array('' => '-- Sin materia --'));
			}
		}
		if (in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI)) ||
			($this->rol->codigo === ROL_PRIVADA && $escuela->dependencia_id === '2')) {
			$this->array_regimen_control = $array_regimen = $this->get_array('regimen', 'regimen', 'id', array(
				'select' => array('regimen.id', 'CONCAT(regimen.codigo, \' \',regimen.descripcion) as regimen'),
				'join' => array(
					array('regimen_lista_regimen', 'regimen_lista_regimen.regimen_id=regimen.id', 'left'),
					array('escuela', 'regimen_lista_regimen.regimen_lista_id=escuela.regimen_lista_id', 'left')
				),
				'where' => array("escuela.id = $escuela->id OR regimen.dependencia_id = $escuela->dependencia_id OR regimen.id=$cargo->regimen_id")
				), array('' => '-- Seleccionar régimen --'));
			$this->cargo_model->fields['regimen']['array'] = $array_regimen;
		} else {
			unset($this->cargo_model->fields['regimen']['input_type']);
			unset($this->cargo_model->fields['regimen']['id_name']);
			$this->cargo_model->fields['regimen']['readonly'] = TRUE;
			$this->cargo_model->fields['carga_horaria']['readonly'] = TRUE;
		}

		$this->set_model_validation_rules($this->cargo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->cargo_model->update(array(
					'id' => $this->input->post('id'),
					'condicion_cargo_id' => $this->input->post('condicion_cargo'),
					'turno_id' => $this->input->post('turno'),
					'division_id' => $this->input->post('division'),
					'espacio_curricular_id' => $this->input->post('espacio_curricular') > 0 ? $this->input->post('espacio_curricular') : 'NULL',
					'carga_horaria' => isset($this->cargo_model->fields['carga_horaria']['readonly']) ? $cargo->carga_horaria : $this->input->post('carga_horaria'),
					'regimen_id' => isset($this->cargo_model->fields['regimen']['readonly']) ? $cargo->regimen_id : $this->input->post('regimen'),
					'aportes' => isset($this->cargo_model->fields['aportes']) ? $this->input->post('aportes') : '0',
					'codigo_junta' => $this->input->post('codigo_junta'),
					'cuatrimestre' => $this->input->post('cuatrimestre'),
					'resolucion_alta' => $this->input->post('resolucion_alta'),
					'observaciones' => $this->input->post('observaciones')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->cargo_model->get_msg());
					redirect("cargo/listar/$escuela->id", 'refresh');
				}
			}
		}

		$data['error'] = (validation_errors() ? validation_errors() : ($this->cargo_model->get_error() ? $this->cargo_model->get_error() : $this->session->flashdata('error')));
		$data['message'] = $this->session->flashdata('message');

		$this->cargo_model->fields['condicion_cargo']['array'] = $array_condicion_cargo;
		$this->cargo_model->fields['division']['array'] = $array_division;
		$this->cargo_model->fields['carrera']['array'] = $array_carrera;
		$this->cargo_model->fields['espacio_curricular']['array'] = $array_espacio_curricular;
		$this->cargo_model->fields['turno']['array'] = $array_turno;
		$data['fields'] = $this->build_fields($this->cargo_model->fields, $cargo);

		$this->load->model('servicio_model');
		$servicios = $this->servicio_model->get(array(
			'cargo_id' => $cargo->id,
			'join' => array(
				array('persona', 'servicio.persona_id=persona.id', 'left', array('persona.cuil', 'persona.apellido', 'persona.nombre')),
				array('situacion_revista', 'servicio.situacion_revista_id=situacion_revista.id', 'left', array('situacion_revista.descripcion as situacion_revista'))
			)
		));

		$data['cargo'] = $cargo;
		$data['servicios'] = $servicios;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => empty($servicios) ? '' : 'disabled', 'horarios' => '', 'cerrar_abrir' => '');
		$data['title'] = TITLE . ' - Editar cargo';
		$this->load_template('cargo/cargo_abm', $data);
	}

	private function editar_limitado($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("cargo/ver/$id");
		}

		$cargo = $this->cargo_model->get(array('id' => $id,
			'join' => array(
				array('division', 'cargo.division_id = division.id', 'left', array('CONCAT(curso.descripcion, \' \', division.division) as division')),
				array('turno', 'turno.id = cargo.turno_id', 'left', array('turno.descripcion as turno')),
				array('curso', 'curso.id = division.curso_id', 'left'),
				array('espacio_curricular', 'cargo.espacio_curricular_id = espacio_curricular.id', 'left', array('materia.descripcion as espacio_curricular')),
				array('materia', 'espacio_curricular.materia_id = materia.id', 'left', array()),
				array('carrera', 'espacio_curricular.carrera_id = carrera.id', 'left', array('carrera.descripcion as carrera')),
				array('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1', '', array('CONCAT(regimen.codigo, \' \',regimen.descripcion) as regimen'))
			)
		));
		if (empty($cargo)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}
		if (empty($cargo->escuela_id)) {
			$this->session->keep_flashdata('message');
			$this->session->keep_flashdata('error');
			$this->load->model('servicio_model');
			$servicios = $this->servicio_model->get(array('cargo_id' => $id));
			redirect("areas/personal/editar/" . $servicios[0]->id);
		}
		$escuela = $this->escuela_model->get_one($cargo->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		if ($escuela->dependencia_id !== '2') {
			unset($this->cargo_model->fields['aportes']);
		}
		$this->load->model('condicion_cargo_model');
		$this->load->model('regimen_model');
		$this->array_condicion_cargo_control = $array_condicion_cargo = $this->get_array('condicion_cargo', 'descripcion', 'id', null, array('' => '-- Seleccionar condición de cargo --'));
		if ($escuela->nivel_id == 7) {
			$this->cargo_model->fields['cuatrimestre'] = array('label' => 'Cuatrimestre', 'input_type' => 'combo', 'id_name' => 'cuatrimestre', 'class' => 'selectize', 'array' => $this->cargo_model->get_cuatrimestres());
			$this->array_cuatrimestre_control = $this->cargo_model->fields['cuatrimestre']['array'];
		}
		if (in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI)) ||
			($this->rol->codigo === ROL_PRIVADA && $escuela->dependencia_id === '2')) {
			$this->array_regimen_control = $array_regimen = $this->get_array('regimen', 'regimen', 'id', array(
				'select' => array('regimen.id', 'CONCAT(regimen.codigo, \' \',regimen.descripcion) as regimen'),
				'join' => array(
					array('regimen_lista_regimen', 'regimen_lista_regimen.regimen_id=regimen.id', 'left'),
					array('escuela', 'regimen_lista_regimen.regimen_lista_id=escuela.regimen_lista_id', 'left')
				),
				'where' => array("escuela.id = $escuela->id OR regimen.dependencia_id = $escuela->dependencia_id OR regimen.id=$cargo->regimen_id")
				), array('' => '-- Seleccionar régimen --'));
			$this->cargo_model->fields['regimen']['array'] = $array_regimen;
		} else {
			unset($this->cargo_model->fields['regimen']['input_type']);
			unset($this->cargo_model->fields['regimen']['id_name']);
			$this->cargo_model->fields['regimen']['readonly'] = TRUE;
			$this->cargo_model->fields['carga_horaria']['readonly'] = TRUE;
		}
		unset($this->cargo_model->fields['division']['input_type']);
		unset($this->cargo_model->fields['division']['id_name']);
		$this->cargo_model->fields['division']['readonly'] = TRUE;
		unset($this->cargo_model->fields['espacio_curricular']['input_type']);
		unset($this->cargo_model->fields['espacio_curricular']['id_name']);
		$this->cargo_model->fields['espacio_curricular']['readonly'] = TRUE;
		unset($this->cargo_model->fields['carrera']['input_type']);
		unset($this->cargo_model->fields['carrera']['id_name']);
		$this->cargo_model->fields['carrera']['readonly'] = TRUE;
		unset($this->cargo_model->fields['turno']['input_type']);
		unset($this->cargo_model->fields['turno']['id_name']);
		$this->cargo_model->fields['turno']['readonly'] = TRUE;

		$this->set_model_validation_rules($this->cargo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->cargo_model->update(array(
					'id' => $this->input->post('id'),
					'condicion_cargo_id' => $this->input->post('condicion_cargo'),
					'carga_horaria' => isset($this->cargo_model->fields['carga_horaria']['readonly']) ? $cargo->carga_horaria : $this->input->post('carga_horaria'),
					'regimen_id' => isset($this->cargo_model->fields['regimen']['readonly']) ? $cargo->regimen_id : $this->input->post('regimen'),
					'aportes' => isset($this->cargo_model->fields['aportes']) ? $this->input->post('aportes') : '0',
					'codigo_junta' => $this->input->post('codigo_junta'),
					'cuatrimestre' => $this->input->post('cuatrimestre'),
					'resolucion_alta' => $this->input->post('resolucion_alta'),
					'observaciones' => $this->input->post('observaciones')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->cargo_model->get_msg());
					redirect("cargo/listar/$escuela->id", 'refresh');
				}
			}
		}

		$data['error'] = (validation_errors() ? validation_errors() : ($this->cargo_model->get_error() ? $this->cargo_model->get_error() : $this->session->flashdata('error')));
		$data['message'] = $this->session->flashdata('message');

		$this->cargo_model->fields['condicion_cargo']['array'] = $array_condicion_cargo;
		$data['fields'] = $this->build_fields($this->cargo_model->fields, $cargo);

		$this->load->model('servicio_model');
		$servicios = $this->servicio_model->get(array(
			'cargo_id' => $cargo->id,
			'join' => array(
				array('persona', 'servicio.persona_id=persona.id', 'left', array('persona.cuil', 'persona.apellido', 'persona.nombre')),
				array('situacion_revista', 'servicio.situacion_revista_id=situacion_revista.id', 'left', array('situacion_revista.descripcion as situacion_revista'))
			)
		));

		$this->load->model('cargo_historial_model');
		$historial = $this->cargo_historial_model->get(array(
			'cargo_id' => $cargo->id,
			'join' => array(
				array('division', 'cargo_historial.division_id = division.id', 'left', array('CONCAT(curso.descripcion, \' \', division.division) as division')),
				array('curso', 'curso.id = division.curso_id', 'left'),
				array('nivel', 'nivel.id = curso.nivel_id', 'left'),
				array('turno', 'turno.id = cargo_historial.turno_id', 'left', array('turno.descripcion as turno')),
				array('espacio_curricular', 'cargo_historial.espacio_curricular_id = espacio_curricular.id', 'left', array('materia.descripcion as espacio_curricular')),
				array('materia', 'espacio_curricular.materia_id = materia.id', 'left'),
				array('carrera', 'espacio_curricular.carrera_id = carrera.id', 'left', array('carrera.descripcion as carrera'))
			),
			'sort_by' => 'fecha_hasta DESC'
		));

		$data['cargo'] = $cargo;
		$data['servicios'] = $servicios;
		$data['historial'] = $historial;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => empty($servicios) ? '' : 'disabled', 'horarios' => '', 'cerrar_abrir' => '');
		$data['title'] = TITLE . ' - Editar cargo';
		$this->load_template('cargo/cargo_abm', $data);
	}

	private function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		if (!$this->edicion) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}

		$cargo = $this->cargo_model->get(array('id' => $id,
			'join' => array(
				array('espacio_curricular', 'cargo.espacio_curricular_id=espacio_curricular.id', 'left', array('espacio_curricular.carrera_id')),
				array('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1', '', array('CONCAT(regimen.codigo, \' \',regimen.descripcion) as regimen'))
			)
		));
		if (empty($cargo)) {
			return $this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
		}

		if (empty($cargo->escuela_id)) {
			return $this->modal_error('El cargo no corresponde a una escuela', 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($cargo->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('cargo_historial_model');
		$this->load->model('division_model');
		$this->load->model('carrera_model');
		$this->load->model('espacio_curricular_model');
		$this->load->model('turno_model');
		$this->array_division_control = $array_division = $this->get_array('division', 'division', 'id', array(
			'select' => array('division.id', 'CONCAT(curso.descripcion, \' \', division.division) as division'),
			'join' => array(
				array('curso', 'curso.id=division.curso_id')
			),
			'escuela_id' => $escuela->id,
			'where' => array('fecha_baja IS NULL'),
			'sort_by' => 'curso.descripcion, division.division'
			), array('' => '-- Sin división --'));
		$this->array_turno_control = $array_turno = $this->get_array('turno', 'descripcion', 'id', array(), array('' => '-- Seleccionar turno --'));
		if (empty($_POST)) {
			if (empty($cargo->carrera_id) && empty($cargo->division_id)) {
				$this->array_carrera_control = $array_carrera = $this->get_array('carrera', 'descripcion', 'id', array(
					'join' => array(
						array('division', 'carrera.id=division.carrera_id AND division.escuela_id=' . $escuela->id),
					),
					'group_by' => 'carrera.id',
					'sort_by' => 'carrera.descripcion'
					), array('' => '-- Sin carrera --'));
				$this->array_espacio_curricular_control = $array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
					'select' => array('MIN(espacio_curricular.id) id', 'materia.descripcion materia'),
					'join' => array(
						array('materia', 'materia.id=espacio_curricular.materia_id', 'left'),
						array('curso', 'curso.id=espacio_curricular.curso_id', 'left')
					),
					'where' => array('carrera_id IS NULL', "curso.nivel_id=$escuela->nivel_id"),
					'group_by' => 'espacio_curricular.materia_id'
					), array('' => '-- Sin materia --'));
			} elseif (empty($cargo->carrera_id)) {
				$this->array_carrera_control = $array_carrera = array('' => '');
				$array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
					'join' => array(
						array('materia', 'materia.id=espacio_curricular.materia_id', 'left', array('materia.descripcion as materia')),
						array('division', 'division.carrera_id=espacio_curricular.carrera_id'),
					),
					'where' => array(array('column' => 'division.id', 'value' => $cargo->division_id)),
					), array('' => '-- Sin materia --'));
				$this->array_espacio_curricular_control = $array_espacio_curricular = $array_espacio_curricular + $this->espacio_curricular_model->get_extracurriculares($cargo->division_id);
			} else {
				$this->array_carrera_control = $array_carrera = $this->get_array('carrera', 'descripcion', 'id', array(
					'join' => array(
						array('division', 'carrera.id=division.carrera_id AND division.escuela_id=' . $escuela->id),
					),
					'group_by' => 'carrera.id',
					'sort_by' => 'carrera.descripcion'
					), array('' => '-- Sin carrera --'));
				$this->array_espacio_curricular_control = $array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
					'select' => array('MIN(espacio_curricular.id) id', 'materia.descripcion materia'),
					'join' => array(
						array('materia', 'materia.id=espacio_curricular.materia_id', 'left'),
						array('curso', 'curso.id=espacio_curricular.curso_id', 'left')
					),
					'where' => array("(carrera_id IS NULL OR carrera_id = $cargo->carrera_id)", "curso.nivel_id=$escuela->nivel_id"),
					'group_by' => 'espacio_curricular.materia_id'
					), array('' => '-- Sin materia --'));
			}
		} else {
			if (empty($_POST['carrera']) && empty($_POST['division'])) {
				$this->array_carrera_control = $array_carrera = $this->get_array('carrera', 'descripcion', 'id', array(
					'join' => array(
						array('division', 'carrera.id=division.carrera_id AND division.escuela_id=' . $escuela->id),
					),
					'group_by' => 'carrera.id',
					'sort_by' => 'carrera.descripcion'
					), array('' => '-- Sin carrera --'));
				$this->array_espacio_curricular_control = $array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
					'select' => array('MIN(espacio_curricular.id) id', 'materia.descripcion materia'),
					'join' => array(
						array('materia', 'materia.id=espacio_curricular.materia_id', 'left'),
						array('curso', 'curso.id=espacio_curricular.curso_id', 'left')
					),
					'where' => array('carrera_id IS NULL', "curso.nivel_id=$escuela->nivel_id"),
					'group_by' => 'espacio_curricular.materia_id'
					), array('' => '-- Sin materia --'));
			} elseif (empty($_POST['carrera'])) {
				$this->array_carrera_control = $array_carrera = array('' => '');
				$array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
					'join' => array(
						array('materia', 'materia.id=espacio_curricular.materia_id', 'left', array('materia.descripcion as materia')),
						array('division', 'division.carrera_id=espacio_curricular.carrera_id'),
					),
					'where' => array(array('column' => 'division.id', 'value' => $this->input->post('division'))),
					), array('' => '-- Sin materia --'));
				$this->array_espacio_curricular_control = $array_espacio_curricular = $array_espacio_curricular + $this->espacio_curricular_model->get_extracurriculares($this->input->post('division'));
			} else {
				$this->array_carrera_control = $array_carrera = $this->get_array('carrera', 'descripcion', 'id', array(
					'join' => array(
						array('division', 'carrera.id=division.carrera_id AND division.escuela_id=' . $escuela->id),
					),
					'group_by' => 'carrera.id',
					'sort_by' => 'carrera.descripcion'
					), array('' => '-- Sin carrera --'));
				$this->array_espacio_curricular_control = $array_espacio_curricular = $this->get_array('espacio_curricular', 'materia', 'id', array(
					'select' => array('MIN(espacio_curricular.id) id', 'materia.descripcion materia'),
					'join' => array(
						array('materia', 'materia.id=espacio_curricular.materia_id', 'left'),
						array('curso', 'curso.id=espacio_curricular.curso_id', 'left')
					),
					'where' => array("(carrera_id IS NULL OR carrera_id = " . $this->input->post('carrera') . ")", "curso.nivel_id=$escuela->nivel_id"),
					'group_by' => 'espacio_curricular.materia_id'
					), array('' => '-- Sin materia --'));
			}
		}

		$this->set_model_validation_rules($this->cargo_historial_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->cargo_historial_model->create(array(
					'cargo_id' => $this->input->post('id'),
					'turno_id' => empty($cargo->turno_id) ? 'NULL' : $cargo->turno_id,
					'division_id' => empty($cargo->division_id) ? 'NULL' : $cargo->division_id,
					'espacio_curricular_id' => empty($cargo->espacio_curricular_id) ? 'NULL' : $cargo->espacio_curricular_id,
					'fecha_hasta' => $this->get_date_sql('fecha_hasta'),
					'observaciones' => $this->input->post('observaciones')
					), FALSE);
				$trans_ok &= $this->cargo_model->update(array(
					'id' => $this->input->post('id'),
					'condicion_cargo_id' => $this->input->post('condicion_cargo'),
					'turno_id' => $this->input->post('turno'),
					'division_id' => $this->input->post('division'),
					'espacio_curricular_id' => $this->input->post('espacio_curricular') > 0 ? $this->input->post('espacio_curricular') : 'NULL',
					), FALSE);
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->cargo_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->cargo_model->get_error());
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect("cargo/ver/$cargo->id", 'refresh');
		}

		$this->cargo_historial_model->fields['division']['array'] = $array_division;
		$this->cargo_historial_model->fields['carrera']['array'] = $array_carrera;
		$this->cargo_historial_model->fields['espacio_curricular']['array'] = $array_espacio_curricular;
		$this->cargo_historial_model->fields['turno']['array'] = $array_turno;
		$cargo->cargo_id = $cargo->id;
		$cargo->fecha_hasta = '2018-01-01';
		$data['fields'] = $this->build_fields($this->cargo_historial_model->fields, $cargo);

		$data['cargo'] = $cargo;
		$data['escuela'] = $escuela;
		$data['title'] = 'Reubicar cargo';
		$this->load->view('cargo/cargo_modal_abm', $data);
	}

	public function eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("cargo/ver/$id");
		}

		$cargo = $this->cargo_model->get_one($id);
		if (empty($cargo)) {
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}
		if (empty($cargo->escuela_id)) {
			$this->session->keep_flashdata('message');
			$this->session->keep_flashdata('error');
			$this->load->model('servicio_model');
			$servicios = $this->servicio_model->get(array('cargo_id' => $id));
			redirect("areas/personal/eliminar/" . $servicios[0]->id);
		}
		$escuela = $this->escuela_model->get_one($cargo->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		if ($escuela->nivel_id == 7) {
			$this->cargo_model->fields['cuatrimestre'] = array('label' => 'Cuatrimestre', 'input_type' => 'combo', 'id_name' => 'cuatrimestre', 'class' => 'selectize', 'array' => $this->cargo_model->get_cuatrimestres());
			$this->array_cuatrimestre_control = $this->cargo_model->fields['cuatrimestre']['array'];
		}

		if ($escuela->dependencia_id !== '2') {
			unset($this->cargo_model->fields['aportes']);
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->cargo_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->cargo_model->get_msg());
				redirect("cargo/listar/$escuela->id", 'refresh');
			}
		}

		$data['error'] = (validation_errors() ? validation_errors() : ($this->cargo_model->get_error() ? $this->cargo_model->get_error() : $this->session->flashdata('error')));

		$data['fields'] = $this->build_fields($this->cargo_model->fields, $cargo, TRUE);

		$data['cargo'] = $cargo;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active', 'horarios' => '', 'cerrar_abrir' => '');
		$data['title'] = TITLE . ' - Eliminar cargo';
		$this->load_template('cargo/cargo_abm', $data);
	}

	public function ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$cargo = $this->cargo_model->get_one($id);
		if (empty($cargo)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		if (empty($cargo->escuela_id)) {
			$this->session->keep_flashdata('message');
			$this->session->keep_flashdata('error');
			$this->load->model('servicio_model');
			$servicios = $this->servicio_model->get(array('cargo_id' => $id));
			redirect("areas/personal/ver/" . $servicios[0]->id);
		}
		$escuela = $this->escuela_model->get_one($cargo->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		if ($escuela->dependencia_id !== '2') {
			unset($this->cargo_model->fields['aportes']);
		}
		$this->load->model('servicio_model');

		$servicios = $this->servicio_model->get(array(
			'cargo_id' => $cargo->id,
			'join' => array(
				array('persona', 'servicio.persona_id=persona.id', 'left', array('persona.cuil', 'persona.apellido', 'persona.nombre')),
				array('situacion_revista', 'servicio.situacion_revista_id=situacion_revista.id', 'left', array('situacion_revista.descripcion as situacion_revista'))
			)
		));

//		$this->load->model('cargo_historial_model');
//		$historial = $this->cargo_historial_model->get(array(
//			'cargo_id' => $cargo->id,
//			'join' => array(
//				array('division', 'cargo_historial.division_id = division.id', 'left', array('CONCAT(curso.descripcion, \' \', division.division) as division')),
//				array('curso', 'curso.id = division.curso_id', 'left'),
//				array('nivel', 'nivel.id = curso.nivel_id', 'left'),
//				array('turno', 'turno.id = cargo_historial.turno_id', 'left', array('turno.descripcion as turno')),
//				array('espacio_curricular', 'cargo_historial.espacio_curricular_id = espacio_curricular.id', 'left', array('materia.descripcion as espacio_curricular')),
//				array('materia', 'espacio_curricular.materia_id = materia.id', 'left'),
//				array('carrera', 'espacio_curricular.carrera_id = carrera.id', 'left', array('carrera.descripcion as carrera'))
//			),
//			'sort_by' => 'fecha_hasta DESC'
//		));

		if ($escuela->nivel_id == 7) {
			$this->cargo_model->fields['cuatrimestre'] = array('label' => 'Cuatrimestre', 'input_type' => 'combo', 'id_name' => 'cuatrimestre', 'class' => 'selectize', 'array' => $this->cargo_model->get_cuatrimestres());
			$this->cargo_model->fields['cuatrimestre']['value'] = $this->cargo_model->get_cuatrimestres()[$cargo->cuatrimestre];
		}

		if (ENVIRONMENT !== 'production') {
			$this->load->model('cargo_cursada_model');
			$cargos_cursada = $this->cargo_cursada_model->get_cursadas_by_cargo($cargo->id);
			$data['cargos_cursada'] = $cargos_cursada;
		}
		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->cargo_model->fields, $cargo, TRUE);
		$data['message'] = $this->session->flashdata('message');
		$data['cargo'] = $cargo;
		$data['servicios'] = $servicios;
//		$data['historial'] = $historial;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => empty($servicios) ? '' : 'disabled', 'horarios' => '', 'cerrar_abrir' => '');
		$data['title'] = TITLE . ' - Ver cargo';
		$this->load_template('cargo/cargo_abm', $data);
	}

	public function horarios($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$cargo = $this->cargo_model->get_one($id);
		if (empty($cargo)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		if (empty($cargo->escuela_id)) {
			$this->session->keep_flashdata('message');
			$this->session->keep_flashdata('error');
			$this->load->model('servicio_model');
			$servicios = $this->servicio_model->get(array('cargo_id' => $id));
			redirect("areas/personal/horarios/" . $servicios[0]->id);
		}
		$escuela = $this->escuela_model->get_one($cargo->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		if ($escuela->nivel_id == 7) {
			$this->cargo_model->fields['cuatrimestre'] = array('label' => 'Cuatrimestre', 'input_type' => 'combo', 'id_name' => 'cuatrimestre', 'class' => 'selectize', 'array' => $this->cargo_model->get_cuatrimestres());
			$this->cargo_model->fields['cuatrimestre']['value'] = $this->cargo_model->get_cuatrimestres()[$cargo->cuatrimestre];
		}

		if ($escuela->dependencia_id !== '2') {
			unset($this->cargo_model->fields['aportes']);
		}
		$this->load->model('dia_model');
		$this->load->model('horario_model');
		$dias = $this->dia_model->get(array('sort_by' => 'id'));
		$horarios_div_db = $this->horario_model->get(array(
			'select' => 'horario.id, horario.dia_id, horario.hora_desde, horario.hora_hasta, horario.division_id, horario.hora_catedra, horario.obligaciones, horario.cargo_id, division.division, curso.descripcion as curso',
			'join' => array(
				array('cargo_horario', 'cargo_horario.horario_id=horario.id'),
				array('division', 'division.id=horario.division_id'),
				array('curso', 'curso.id=division.curso_id')
			),
			'where' => array("cargo_horario.cargo_id=$id")
		));
		$horarios_car_db = $this->horario_model->get(array('cargo_id' => $id));
		$horarios_div = array();
		if (!empty($horarios_div_db)) {
			foreach ($horarios_div_db as $horario) {
				$horarios_div[$horario->dia_id][] = $horario;
			}
		}
		$varias_divisiones = false;
		foreach ($dias as $dia) {
			if (!empty($horarios_div)) {
				if (isset($horarios_div[$dia->id])) {
					$anterior = new stdClass();
					foreach ($horarios_div[$dia->id] as $key => $cargo_horario) {
						if (isset($anterior->division_id)) {
							if ($anterior->division_id != $cargo_horario->division_id) {
								$varias_divisiones = true;
							}
						}
						$anterior = $cargo_horario;
					}
				}
			}
		}
		$data['varias_divisiones'] = $varias_divisiones;
		$horarios_car = array();
		if (!empty($horarios_car_db)) {
			foreach ($horarios_car_db as $horario) {
				$horarios_car[$horario->dia_id][] = $horario;
			}
		}
		$this->load->library('user_agent');
		$data['return_url'] = $this->agent->referrer();
		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->cargo_model->fields, $cargo, TRUE);
		$data['horarios_car'] = $horarios_car;
		$data['horarios_div'] = $horarios_div;
		$data['dias'] = $dias;
		$this->load->model('servicio_model');
		$servicios = $this->servicio_model->get(array(
			'cargo_id' => $cargo->id,
			'join' => array(
				array('persona', 'servicio.persona_id=persona.id', 'left', array('persona.cuil', 'persona.apellido', 'persona.nombre')),
				array('situacion_revista', 'servicio.situacion_revista_id=situacion_revista.id', 'left', array('situacion_revista.descripcion as situacion_revista'))
			)
		));

		$data['cargo'] = $cargo;
		$data['servicios'] = $servicios;
		$data['escuela'] = $escuela;
		$data['title'] = TITLE . ' - Horarios cargo';
		$this->load_template('cargo/cargo_horarios', $data);
	}

	public function compartir($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$cargo = $this->cargo_model->get_one($id);
		if (empty($cargo)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		if (empty($cargo->escuela_id)) {
			show_error('No se pueden compartir cargos de áreas', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($cargo->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		if ($escuela->dependencia_id !== '2') {
			unset($this->cargo_model->fields['aportes']);
		}
		$this->load->model('cargo_escuela_model');
		$escuelas = $this->cargo_escuela_model->get(array(
			'join' => array(
				array('escuela', 'escuela.id=cargo_escuela.escuela_id', 'left', array("CONCAT(numero, CASE WHEN anexo=0 THEN ' ' ELSE CONCAT('/',anexo,' ') END, nombre) as escuela"))
			),
			'cargo_id' => $id
		));

		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->cargo_model->fields, $cargo, TRUE);
		$data['escuelas'] = $escuelas;
		$this->load->model('servicio_model');
		$servicios = $this->servicio_model->get(array(
			'cargo_id' => $cargo->id,
			'join' => array(
				array('persona', 'servicio.persona_id=persona.id', 'left', array('persona.cuil', 'persona.apellido', 'persona.nombre')),
				array('situacion_revista', 'servicio.situacion_revista_id=situacion_revista.id', 'left', array('situacion_revista.descripcion as situacion_revista'))
			)
		));

		$data['cargo'] = $cargo;
		$data['servicios'] = $servicios;
		$data['escuela'] = $escuela;
		$data['escuelas'] = $escuelas;
		$data['title'] = TITLE . ' - Compartir cargo';
		$this->load_template('cargo/cargo_compartir', $data);
	}

	public function modal_cerrar_cargo($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$cargo = $this->cargo_model->get_one($id);
		if (empty($cargo)) {
			$this->modal_error('No se encontró el registro del cargo', 'Registro no encontrado');
			return;
		}

		$this->load->model('servicio_model');
		$servicios = $this->servicio_model->get(array(
			'cargo_id' => $cargo->id,
			'join' => array(
				array('persona', 'servicio.persona_id=persona.id', 'left', array('persona.cuil', 'persona.apellido', 'persona.nombre')),
			)
		));

		$servicios_activos = 0;
		if (!empty($servicios)) {
			foreach ($servicios as $servicio) {
				if (empty($servicio->fecha_baja)) {
					$servicios_activos = 1;
				}
			}
		}
		$model = new stdClass();
		$model->fields = array(
			'fecha_hasta' => array('label' => 'Fecha de cierre', 'type' => 'date', 'required' => TRUE)
		);
		$this->set_model_validation_rules($model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;

				$trans_ok &= $this->cargo_model->update(array(
					'id' => $id,
					'fecha_hasta' => $this->get_date_sql('fecha_hasta'),
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', 'Cargo cerrado correctamente');
				} else {
					$this->session->set_flashdata('error', 'Error al cerrar el cargo');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect("cargo/ver/$id", 'refresh');
		}

		$data['s_activos'] = $servicios_activos;
		$data['cargo_id'] = $cargo->id;
		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($model->fields, $cargo);
		$data['txt_btn'] = 'Cerrar';
		$data['title'] = 'Cerrar cargo';
		$this->load->view('cargo/cargo_modal_cerrar', $data);
	}

	public function modal_abrir_cargo($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$cargo = $this->cargo_model->get_one($id);
		if (empty($cargo)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->cargo_model->update(array(
				'id' => $id,
				'fecha_hasta' => "NULL",
			));
			if ($trans_ok) {
				$this->session->set_flashdata('message', 'Cargo abierto correctamente');
			} else {
				$this->session->set_flashdata('error', 'Error al abrir el cargo');
			}

			redirect("cargo/ver/$id", 'refresh');
		}

		$data['cargo_id'] = $cargo->id;
		$data['error'] = $this->session->flashdata('error');
		$data['txt_btn'] = 'Abrir';
		$data['title'] = 'Reabrir cargo';
		$this->load->view('cargo/cargo_modal_abrir', $data);
	}

	public function reporte($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$cargos_servicios = $this->db->query("
			select s.persona_id,s.id,s.cargo_id,e.numero, e.anexo,turno.descripcion as turno,d.id as division_id,
			p.cuil,CONCAT(p.apellido, ', ', p.nombre) nombre,cc.descripcion as condicion,COALESCE(f.descripcion, sf.detalle) as funcion_detalle,ec.cuatrimestre,
			t.liquidacion_s, r.codigo, r.descripcion regimen, c.carga_horaria, sr.descripcion revista, s.fecha_alta,
			sf.destino as funcion_destino, sf.norma as funcion_norma, sf.tarea as funcion_tarea,
			s.fecha_baja, ca.descripcion carrera, CONCAT(cu.descripcion, ' ', d.division) division, m.descripcion materia
			from servicio s
			join situacion_revista sr on s.situacion_revista_id=sr.id
			join cargo c on s.cargo_id=c.id
			join regimen r on c.regimen_id=r.id
			join escuela e on c.escuela_id=e.id
			join persona p on s.persona_id=p.id
			left join servicio_funcion sf on sf.servicio_id=s.id AND sf.fecha_hasta IS NULL
			left join funcion f on sf.funcion_id=f.id
			left join turno on turno.id = c.turno_id
			left join condicion_cargo cc on cc.id = c.condicion_cargo_id
			left join tbcabh t on s.id=t.servicio_id and t.vigente=?
			left join division d on c.division_id=d.id
			left join carrera ca on d.carrera_id=ca.id
			left join curso cu on d.curso_id=cu.id
			left join espacio_curricular ec on c.espacio_curricular_id=ec.id
			left join materia m on ec.materia_id=m.id
			WHERE e.numero = ? and (s.fecha_baja or t.id IS NOT NULL) and c.fecha_hasta IS NULL
			ORDER BY cu.descripcion,d.division,r.descripcion,sr.descripcion,s.fecha_alta", array(AMES_LIQUIDACION, $escuela->numero)
			)->result();
		$cargos = array();
		foreach ($cargos_servicios as $cargo_servicio) {
			if (empty($cargo_servicio->division) && empty($cargo_servicio->carrera)) {
				$cargos["Cargos sin carrera"][$cargo_servicio->cargo_id][] = $cargo_servicio;
			} else {
				$cargos["$cargo_servicio->division - $cargo_servicio->carrera"][$cargo_servicio->cargo_id][] = $cargo_servicio;
			}
		}
		$data['escuela'] = $escuela;
		$data['cargos'] = $cargos;
		$content = $this->load->view('cargo/reporte_cargos_escuela', $data, TRUE);
		echo $content;
	}

	public function excel($escuela_id = NULL, $mes = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || ($mes != NULL && !ctype_digit($mes))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($mes) || empty(DateTime::createFromFormat('Ym', $mes))) {
			$this->load->model('planilla_asisnov_model');
			$mes = $this->planilla_asisnov_model->get_mes_actual();
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$campos = array(
			'A' => array('Escuela', 10),
			'B' => array('Condicion', 20),
			'C' => array('Anexo', 10),
			'D' => array('Régimen', 40),
			'E' => array('Hs Cátedra', 15),
			'F' => array('División', 20),
			'G' => array('Materia', 40),
			'H' => array('Turno', 13),
			'I' => array('Obs. Cargo', 30),
			'J' => array('Persona', 50),
			'K' => array('Alta', 11),
			'L' => array('Liquidación', 15),
			'M' => array('Sit. Revista', 15),
			'N' => array('Baja', 11),
			'O' => array('Función (Fn)', 30),
			'P' => array('Fn Destino', 11),
			'Q' => array('Fn Norma', 11),
			'R' => array('Fn Tarea', 11),
			'S' => array('Fn Carga Horaria', 11),
			'T' => array('Fn Desde', 11),
			'U' => array('Observaciones', 50)
		);
		$cargos_db = $this->db->select('c.id, e.numero, cc.descripcion as condicion_cargo, e.anexo,CONCAT(r.codigo, \' \', r.descripcion) as regimen, c.carga_horaria, CONCAT(cu.descripcion, \' \', d.division) division, m.descripcion as materia, t.descripcion as turno, c.observaciones c_observaciones, CONCAT(COALESCE(p.cuil, p.documento), \' \', p.apellido, \' \', p.nombre) as persona, s.fecha_alta, s.liquidacion, sr.descripcion as situacion_revista, s.fecha_baja, sf.detalle as funcion_detalle, sf.destino as funcion_destino, sf.norma as funcion_norma, sf.tarea as funcion_tarea, sf.carga_horaria as funcion_carga_horaria, sf.fecha_desde as funcion_desde, s.observaciones')
				->from('cargo c')
				->join('servicio s', 'c.id = s.cargo_id', 'left')
				->join('servicio_funcion sf', 'sf.servicio_id = s.id AND sf.fecha_hasta IS NULL', 'left')
				->join('situacion_revista sr', 'sr.id = s.situacion_revista_id', 'left')
				->join('escuela e', 'e.id = c.escuela_id')
				->join('division d', 'd.id = c.division_id', 'left')
				->join('curso cu', 'cu.id = d.curso_id', 'left')
				->join('espacio_curricular ec', 'ec.id = c.espacio_curricular_id', 'left')
				->join('materia m', 'ec.materia_id = m.id', 'left')
				->join('regimen r', 'r.id = c.regimen_id AND r.planilla_modalidad_id=1', '')
				->join('persona p', 'p.id = s.persona_id', 'left')
				->join('condicion_cargo cc', 'cc.id = c.condicion_cargo_id', 'left')
				->join('turno t', 't.id = c.turno_id', 'left')
				->where('e.id', $escuela->id)
				->where('c.fecha_hasta IS NULL')
				->where("'$mes' BETWEEN COALESCE(DATE_FORMAT(s.fecha_alta,'%Y%m'),'000000') AND COALESCE(DATE_FORMAT(s.fecha_baja,'%Y%m'),'999999')")
				->group_by('c.id, s.id')
				->order_by('cu.descripcion, d.division, m.descripcion, r.codigo, c.id')
				->get()->result_array();

		if (!empty($cargos_db)) {
			$this->load->library('PHPExcel');
			$this->phpexcel->getProperties()->setTitle("Cargos $escuela->numero $mes")->setDescription("");
			$this->phpexcel->getDefaultStyle()->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('argb' => 'FFFFFFFF')
					),
				)
			);
			$this->phpexcel->setActiveSheetIndex(0);

			$sheet = $this->phpexcel->getActiveSheet();
			$sheet->setTitle("Cargos $escuela->numero $mes");
			$encabezado = array();
			$ultima_columna = 'A';
			foreach ($campos as $columna => $campo) {
				$encabezado[] = $campo[0];
				$sheet->getColumnDimension($columna)->setWidth($campo[1]);
				$ultima_columna = $columna;
			}

			$sheet->getStyle('A1:' . $ultima_columna . '1')->getFont()->setBold(true);

			$sheet->fromArray(array($encabezado), NULL, 'A1');
			$cargos = array();
			$fila = 2;
			$ult_id = 0;
			$fila_ult_id = 0;
			$primero = TRUE;
			foreach ($cargos_db as $id => $cargo) {
				if ($cargo['id'] === $ult_id) {
					if ($primero) {
						$fila_ult_id = $fila - 1;
						$sheet->getStyle("A$fila_ult_id:U$fila_ult_id")->applyFromArray(array(
							'borders' => array(
								'top' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN
								)
							))
						);
						$primero = FALSE;
					}

					$cargos[$fila] = $cargo;
					$cargos[$fila]['condicion_cargo'] = '';
					$cargos[$fila]['numero'] = '';
					$cargos[$fila]['anexo'] = '';
					$cargos[$fila]['regimen'] = '';
					$cargos[$fila]['carga_horaria'] = '';
					$cargos[$fila]['division'] = '';
					$cargos[$fila]['materia'] = '';
					$cargos[$fila]['turno'] = '';
				} else {
					$primero = TRUE;
					if ($fila_ult_id !== 0) {
						$sheet->getStyle("A$fila_ult_id:G" . ($fila - 1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$sheet->mergeCells("A$fila_ult_id:A" . ($fila - 1));
						$sheet->mergeCells("B$fila_ult_id:B" . ($fila - 1));
						$sheet->mergeCells("C$fila_ult_id:C" . ($fila - 1));
						$sheet->mergeCells("D$fila_ult_id:D" . ($fila - 1));
						$sheet->mergeCells("E$fila_ult_id:E" . ($fila - 1));
						$sheet->mergeCells("F$fila_ult_id:F" . ($fila - 1));
						$sheet->mergeCells("G$fila_ult_id:G" . ($fila - 1));
						$sheet->mergeCells("H$fila_ult_id:H" . ($fila - 1));
						$sheet->mergeCells("I$fila_ult_id:I" . ($fila - 1));
						$fila_ult_id = 0;
					}

					$sheet->getStyle("A$fila:U$fila")->applyFromArray(array(
						'borders' => array(
							'top' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
							)
						))
					);
					$ult_id = $cargo['id'];
					$cargos[$fila] = $cargo;
				}
				unset($cargos[$fila]['id']);
				$fila++;
			}
			$sheet->fromArray($cargos, NULL, "A2");

			header("Content-Type: application/vnd.ms-excel");
			$nombreArchivo = "Cargos $escuela->numero $mes";
			header("Content-Disposition: attachment; filename=\"$nombreArchivo.xls\"");
			header("Cache-Control: max-age=0");

			$writer = PHPExcel_IOFactory::createWriter($this->phpexcel, "Excel5");
			$writer->save('php://output');
			exit;
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect("cargo/listar/$escuela->id", 'refresh');
		}
	}
}
/* End of file Cargo.php */
/* Location: ./application/controllers/Cargo.php */