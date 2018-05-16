<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alertas extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('cargo_model');
		$this->load->model('escuela_model');
		$this->roles_escuela = array(ROL_ADMIN, ROL_USI, ROL_SUPERVISION, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_ESCUELA_ALUM, ROL_ESCUELA_CAR, ROL_PRIVADA);
		$this->nav_route = 'menu/cargo';
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_GRUPO_ESCUELA_CONSULTA))) {
			$this->edicion = FALSE;
		}
	}

	public function divisiones_sin_carreras($escuela_id = NULL) {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_ALUM, ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$tableData = array(
			'columns' => array(
				array('label' => 'Curso', 'data' => 'curso', 'width' => 10),
				array('label' => 'División', 'data' => 'division', 'width' => 10),
				array('label' => 'Turno', 'data' => 'turno', 'width' => 15),
				array('label' => 'Carrera', 'data' => 'carrera', 'width' => 35),
				array('label' => 'Fecha Alta', 'data' => 'fecha_alta', 'render' => 'date', 'width' => 10),
				array('label' => 'Fecha Baja', 'data' => 'fecha_baja', 'render' => 'date', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'division_table',
			'source_url' => "alertas/divisiones_sin_carreras_data/$escuela->id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(0, 'asc'), array(1, 'asc'))
		);

		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['escuela'] = $escuela;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Divisiones';
		$this->load_template('alertas/alertas_divisiones_sin_carreras', $data);
	}

	public function divisiones_sin_carreras_data($escuela_id, $rol_codigo, $entidad_id = '') {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_ALUM, ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
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
			->select('division.id, division.escuela_id, division.turno_id, division.curso_id, division.division, division.carrera_id, division.fecha_alta, division.fecha_baja, carrera.descripcion as carrera, curso.descripcion as curso, escuela.nombre as escuela, turno.descripcion as turno')
			->unset_column('id')
			->from('division')
			->join('carrera', 'carrera.id = division.carrera_id', 'left')
			->join('curso', 'curso.id = division.curso_id', 'left')
			->join('escuela', 'escuela.id = division.escuela_id', 'left')
			->join('turno', 'turno.id = division.turno_id', 'left')
			->where('division.escuela_id', $escuela_id)
			->where('division.fecha_baja IS NULL');
		if ($this->edicion) {
			$this->datatables
				->add_column('edit', '<div class="btn-group" role="group">'
					. '<a class="btn btn-xs btn-default" href="division/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
					. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
					. '<ul class="dropdown-menu dropdown-menu-right">'
					. '<li><a class="dropdown-item" href="division/editar/$1" title="Editar"><i class="fa fa-pencil"></i> Editar</a></li>'
					. '<li><a class="dropdown-item" href="division/eliminar/$1" title="Eliminar"><i class="fa fa-remove"></i> Eliminar</a></li>'
					. '<li><a class="dropdown-item" href="division/ver_horario/$1" title="Horarios"><i class="fa fa-clock-o"></i> Horarios</a></li>'
					. '<li><a class="dropdown-item" href="division/cargos/$1" title="Cargos"><i class="fa fa-users"></i> Cargos</a></li>'
					. '</ul></div>', 'id');
		} else {
			$this->datatables
				->add_column('edit', '', 'id');
		}
		echo $this->datatables->generate();
	}

	public function cargos_sin_horarios($escuela_id = NULL) {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_ALUM))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
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
				array('label' => 'Div', 'data' => 'division', 'width' => 16),
				array('label' => 'Régimen/Materia', 'data' => 'regimen_materia', 'width' => 28, 'class' => 'text-sm'),
				array('label' => 'Hs.', 'data' => 'carga_horaria', 'class' => 'dt-body-right', 'width' => 4),
				array('label' => 'Observaciones', 'data' => 'observaciones', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Servicios', 'data' => 'servicios', 'width' => 6, 'class' => 'dt-body-right', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => 'Persona Actual', 'data' => 'persona', 'width' => 24, 'class' => 'text-sm'),
				array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'order' => array(array(2, 'asc'), array(3, 'asc'), array(4, 'asc'), array(5, 'asc')),
			'table_id' => 'cargo_table',
			'source_url' => "alertas/cargos_sin_horarios_data/$escuela->id/{$this->rol->codigo}/{$this->rol->entidad_id}",
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
		$data['title'] = TITLE . ' - Cargos sin horarios';
		$this->load_template('alertas/alertas_cargos_sin_horarios', $data);
	}

	public function cargos_sin_horarios_data($escuela_id, $rol_codigo, $entidad_id = '') {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_ALUM))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
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
		$mes = date('Ym');
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select('cargo.id, condicion_cargo.descripcion as condicion_cargo, curso.descripcion as curso, cargo.division_id, division.division as division, '
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
			->join('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1')
			->join('servicio', 'servicio.cargo_id = cargo.id', 'left')
			->join('servicio sp', "sp.cargo_id = cargo.id AND '$mes' BETWEEN COALESCE(DATE_FORMAT(sp.fecha_alta,'%Y%m'),'000000') AND COALESCE(DATE_FORMAT(sp.fecha_baja,'%Y%m'),'999999') AND sp.reemplazado_id IS NULL", 'left', false)
			->join('persona', 'persona.id = sp.persona_id', 'left')
			->join('cargo_horario', 'cargo_horario.cargo_id = cargo.id', 'left')
			->join('horario', 'horario.cargo_id = cargo.id', 'left')
			->where('cargo.escuela_id', $escuela_id)
			->where('cargo.fecha_hasta IS NULL')
			->where('COALESCE(horario.id, cargo_horario.id) IS NULL')
			->group_by('cargo.id, condicion_cargo.descripcion, curso.descripcion, division.division, materia.descripcion, carrera.descripcion, cargo.carga_horaria, regimen.codigo, regimen.descripcion')
			->add_column('menu', '$1', 'dt_column_alertas_cargos_sin_horarios_menu(id, division_id)')
			->add_column('', '', 'id');

		echo $this->datatables->generate();
	}

	public function cargos_sin_horarios_masivo($escuela_id = NULL) {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_ALUM))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('horario_model');

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($this->input->post('cargo') === NULL) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				$this->session->set_flashdata('error', 'Ningún dato fue ingresado');
				redirect("alertas/cargos_sin_horarios_masivo/$escuela_id");
			}
			$this->form_validation->set_rules("hora_desde", 'Hora desde', 'trim|required|exact_length[5]|validate_time');
			$this->form_validation->set_rules("hora_hasta", 'Hora hasta', 'trim|required|exact_length[5]|validate_time');
			$this->form_validation->set_rules("dias[]", 'Días', 'required');
			$this->form_validation->set_rules("cargo[]", 'Cargos', 'required');

			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				foreach ($this->input->post('cargo') as $cargo) {
					foreach ($this->input->post('dias') as $dia) {
						$cargo_horario = $this->horario_model->get(array(
							'dia_id' => $dia,
							'cargo_id' => $cargo,
							'where' => array('division_id IS NULL')
						));

						if (!empty($cargo_horario)) {
							$trans_ok &= $this->horario_model->update(array(
								'id' => $cargo_horario[0]->id,
								'hora_desde' => $this->input->post('hora_desde'),
								'hora_hasta' => $this->input->post('hora_hasta')
								), FALSE);
						} else {
							$trans_ok &= $this->horario_model->create(array(
								'cargo_id' => $cargo,
								'dia_id' => $dia,
								'obligaciones' => 1,
								'hora_desde' => $this->input->post('hora_desde'),
								'hora_hasta' => $this->input->post('hora_hasta')
								), FALSE);
						}
					}
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->horario_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->horario_model->get_error());
				}
				redirect("alertas/cargos_sin_horarios_masivo/$escuela_id");
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("alertas/cargos_sin_horarios_masivo/$escuela_id");
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
			'source_url' => "alertas/cargos_sin_horarios_masivo_data/$escuela->id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'details_format' => 'cargo_table_detalles',
			'reuse_var' => TRUE,
			'initComplete' => "complete_cargo_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['escuela'] = $escuela;
		$this->load->model('dia_model');
		$dias = $this->dia_model->get(array('sort_by' => 'id'));
		$data['dias'] = $dias;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Cargos sin horarios';
		$this->load_template('alertas/alertas_cargos_sin_horarios_carga_masiva', $data);
	}

	public function cargos_sin_horarios_masivo_data($escuela_id, $rol_codigo, $entidad_id = '') {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_ALUM))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
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
		$mes = date('Ym');
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select('cargo.id, condicion_cargo.descripcion as condicion_cargo, curso.descripcion as curso, cargo.division_id, division.division as division, '
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
			->join('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1')
			->join('servicio', 'servicio.cargo_id = cargo.id', 'left')
			->join('servicio sp', "sp.cargo_id = cargo.id AND '$mes' BETWEEN COALESCE(DATE_FORMAT(sp.fecha_alta,'%Y%m'),'000000') AND COALESCE(DATE_FORMAT(sp.fecha_baja,'%Y%m'),'999999') AND sp.reemplazado_id IS NULL", 'left', false)
			->join('persona', 'persona.id = sp.persona_id', 'left')
			->join('cargo_horario', 'cargo_horario.cargo_id = cargo.id', 'left')
			->join('horario', 'horario.cargo_id = cargo.id', 'left')
			->where('cargo.escuela_id', $escuela_id)
			->where('cargo.fecha_hasta IS NULL')
			->where('cargo.division_id IS NULL')
			->where('COALESCE(horario.id, cargo_horario.id) IS NULL')
			->group_by('cargo.id, condicion_cargo.descripcion, curso.descripcion, division.division, materia.descripcion, carrera.descripcion, cargo.carga_horaria, regimen.codigo, regimen.descripcion')
			->add_column('', '', 'id')
			->add_column('select', '<input type="checkbox" name="cargo[]" value="$1">', 'id');

		echo $this->datatables->generate();
	}

	public function servicio_funciones($escuela_id = NULL) {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_ALUM))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 2, 'class' => '', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => 'Persona', 'data' => 'persona', 'width' => 23, 'class' => 'text-sm'),
				array('label' => 'Alta', 'data' => 'fecha_alta', 'render' => 'short_date', 'width' => 7),
				array('label' => 'Liquidación', 'data' => 'liquidacion', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'S.R.', 'data' => 'situacion_revista', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 3, 'class' => 'dt-body-center'),
				array('label' => 'Div', 'data' => 'division', 'width' => 3, 'class' => 'dt-body-center'),
				array('label' => 'Régimen/Materia', 'data' => 'regimen_materia', 'width' => 28, 'class' => 'text-sm'),
				array('label' => 'Hs.', 'data' => 'carga_horaria', 'width' => 4, 'class' => 'dt-body-right'),
				array('label' => 'Baja', 'data' => 'fecha_baja', 'render' => 'short_date', 'width' => 6),
				array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all')
			),
			'table_id' => 'servicio_table',
			'source_url' => "alertas/servicio_funciones_data/$escuela_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(1, 'asc'), array(5, 'asc'), array(6, 'asc'), array(7, 'asc')),
			'reuse_var' => TRUE,
			'initComplete' => "complete_servicio_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);

		$data['escuela'] = $escuela;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Servicios';

		$this->load_template('alertas/alertas_servicio_funciones', $data);
	}

	public function servicio_funciones_data($escuela_id, $rol_codigo, $entidad_id = '') {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_ALUM))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
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
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select(
				'servicio.id, servicio.persona_id, servicio.cargo_id, servicio.fecha_alta,'
				. 'servicio.fecha_baja, servicio.liquidacion, servicio.reemplazado_id, '
				. 'servicio.situacion_revista_id, cargo.carga_horaria, '
				. 'CONCAT(COALESCE(persona.cuil, \'\'), \'<br>\', COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona, situacion_revista.descripcion as situacion_revista,'
				. 'division.division, curso.descripcion as curso, CONCAT(regimen.descripcion, \'<br>\', COALESCE(materia.descripcion, \'\')) as regimen_materia, regimen.descripcion as regimen, materia.descripcion as materia, escuela.id as escuela_id')
			->unset_column('id')
			->from('servicio')
			->join('servicio_funcion', 'servicio.id = servicio_funcion.servicio_id AND servicio_funcion.fecha_hasta IS NULL', '')
			->join('cargo', 'cargo.id = servicio.cargo_id', 'left')
			->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
			->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
			->join('persona', 'persona.id = servicio.persona_id', 'left')
			->join('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id', 'left')
			->join('escuela', 'cargo.escuela_id = escuela.id', 'left')
			->join('division', 'cargo.division_id = division.id', 'left')
			->join('curso', 'division.curso_id = curso.id', 'left')
			->join('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1')
			->where('escuela.id', $escuela_id)
			->group_by('servicio.id')
			->having('COUNT(DISTINCT servicio_funcion.id)>1')
			->add_column('menu', '<a class="btn btn-xs btn-default" href="servicio/ver/$1"><i class="fa fa-search"></i> Ver</a>', 'id')
			->add_column('', '', 'id');

		echo $this->datatables->generate();
	}

	public function servicios_separar($escuela_id = NULL) {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_ALUM))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('escuela_model');
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 2, 'class' => '', 'sortable' => 'false', 'searchable' => 'false'),
				array('label' => 'Persona', 'data' => 'persona', 'width' => 23, 'class' => 'text-sm'),
				array('label' => 'Alta', 'data' => 'fecha_alta', 'render' => 'short_date', 'width' => 7),
				array('label' => 'Liquidación', 'data' => 'liquidacion', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'S.R.', 'data' => 'situacion_revista', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 3, 'class' => 'dt-body-center'),
				array('label' => 'Div', 'data' => 'division', 'width' => 3, 'class' => 'dt-body-center'),
				array('label' => 'Régimen/Materia', 'data' => 'regimen_materia', 'width' => 28, 'class' => 'text-sm'),
				array('label' => 'Hs.', 'data' => 'carga_horaria', 'width' => 4, 'class' => 'dt-body-right'),
				array('label' => 'Baja', 'data' => 'fecha_baja', 'render' => 'short_date', 'width' => 6),
				array('label' => 'Observaciones', 'data' => 'observaciones', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all'),
				array('label' => 'F.Detalle', 'data' => 'funcion_detalle', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'F.Destino', 'data' => 'funcion_destino', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'F.Norma', 'data' => 'funcion_norma', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'F.Tarea', 'data' => 'funcion_tarea', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'F.Hs.', 'data' => 'funcion_carga_horaria', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'F.Desde', 'data' => 'funcion_desde', 'width' => 10, 'responsive_class' => 'none'),
			),
			'table_id' => 'servicio_table',
			'source_url' => "alertas/servicios_separar_data/$escuela_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(1, 'asc'), array(5, 'asc'), array(6, 'asc'), array(7, 'asc')),
			'details_format' => 'table_detalles',
			'reuse_var' => TRUE,
			'initComplete' => "complete_servicio_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);

		$data['escuela'] = $escuela;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Servicios';

		$this->load_template('alertas/alertas_servicios_separar', $data);
	}

	public function servicios_separar_data($escuela_id = NULL, $rol_codigo = NULL, $entidad_id = '') {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_ALUM))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
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

		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select(
				'servicio.id, servicio.persona_id, servicio.cargo_id, servicio.fecha_alta,'
				. 'servicio.fecha_baja, servicio.liquidacion, servicio.reemplazado_id, '
				. 'servicio.situacion_revista_id, cargo.carga_horaria, '
				. 'CONCAT(COALESCE(persona.cuil, \'\'), \'<br>\', COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona, situacion_revista.descripcion as situacion_revista, '
				. 'division.division, curso.descripcion as curso, CONCAT(regimen.descripcion, \'<br>\', COALESCE(materia.descripcion, \'\')) as regimen_materia, regimen.descripcion as regimen, materia.descripcion as materia, escuela.id as escuela_id, cargo.observaciones, '
				. 'COALESCE(funcion.descripcion, servicio_funcion.detalle) as funcion_detalle, servicio_funcion.destino as funcion_destino, servicio_funcion.norma as funcion_norma, '
				. 'servicio_funcion.tarea as funcion_tarea, servicio_funcion.carga_horaria as funcion_carga_horaria, servicio_funcion.fecha_desde as funcion_desde')
			->unset_column('id')
			->from('servicio')
			->join('servicio_funcion', 'servicio_funcion.servicio_id=servicio.id AND servicio_funcion.fecha_hasta IS NULL', 'left')
			->join('funcion', 'servicio_funcion.funcion_id=funcion.id', 'left')
			->join('cargo', 'cargo.id = servicio.cargo_id', '')
			->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
			->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
			->join('persona', 'persona.id = servicio.persona_id', 'left')
			->join('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id', 'left')
			->join('escuela', 'cargo.escuela_id = escuela.id', 'left')
			->join('division', 'cargo.division_id = division.id', 'left')
			->join('curso', 'division.curso_id = curso.id', 'left')
			->join('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1', '')
			->where("(servicio.liquidacion_regimen_id!=cargo.regimen_id OR servicio.liquidacion_carga_horaria!=cargo.carga_horaria)")
			->where('escuela.id', $escuela_id)
			->group_by('servicio.id');
		if ($this->edicion) {
			$this->datatables
				->add_column('menu', '<a class="btn btn-xs btn-default" href="servicio/separar_cargo/$1"><i class="fa fa-unlink"></i> Separar cargo</a>', 'id')
				->add_column('', '', 'id');
		} else {
			$this->datatables
				->add_column('menu', '', 'id')
				->add_column('', '', 'id');
		}

		echo $this->datatables->generate();
	}

	public function alumno_egreso($escuela_id = NULL) {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
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
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Alumno', 'data' => 'persona', 'width' => 25, 'class' => 'text-sm'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Div', 'data' => 'division', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'C.L.', 'data' => 'ciclo_lectivo', 'width' => 5, 'class' => 'dt-body-center'),
				array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'short_date', 'width' => 10),
				array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'short_date', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 20, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'alumno_pase_table',
			'source_url' => "alertas/alumno_egreso_data/$escuela->id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(2, 'asc'), array(3, 'asc'), array(0, 'asc')),
			'responsive' => FALSE,
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['escuela'] = $escuela;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Alumnos con ingreso en otra escuela';
		$this->load_template('alertas/alertas_alumno_egreso', $data);
	}

	public function alumno_egreso_data($escuela_id = NULL, $rol_codigo = NULL, $entidad_id = '') {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
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
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select('alumno_division.id, alumno.persona_id, alumno.observaciones, alumno_division.fecha_desde, alumno_division.fecha_hasta,alumno_division.ciclo_lectivo, CONCAT(documento_tipo.descripcion_corta, \' \', persona.documento) as documento, CONCAT(COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona, '
				. 'division.division, curso.descripcion as curso, division.escuela_id')
			->unset_column('id')
			->from('alumno')
			->join('persona', 'persona.id = alumno.persona_id', 'left')
			->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id', 'left')
			->join('alumno_division', 'alumno_division.alumno_id = alumno.id', 'left')
			->join('division', 'division.id = alumno_division.division_id', 'left')
			->join('curso', 'division.curso_id = curso.id', 'left')
			->where('division.escuela_id', $escuela_id)
			->where('alumno_division.estado_id', 2);
		if ($this->edicion) {
			$this->datatables
				->add_column('edit', '<a href="alertas/alumno_egreso_modal/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-primary"><i class="fa fa-check"></i> Confirmar</a>'
					. '&nbsp<a href="alertas/alumno_cancelar_egreso_modal/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i> Cancelar</a>', 'id');
		} else {
			$this->datatables
				->add_column('edit', '', '');
		}
		echo $this->datatables->generate();
	}

	public function alumno_egreso_modal($alumno_division_id = NULL) {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('alumno_division_model');
		$this->load->model('causa_salida_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			$this->modal_error('No se encontró el registro del alumno', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($alumno_division->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}
		$egreso_nuevo = $this->alumno_division_model->get_alumno_egreso($alumno_division->alumno_id);
		if (empty($egreso_nuevo)) {
			$this->modal_error('No se encontró el registro del egreso', 'Registro no encontrado');
			return;
		}
		if ($this->edicion === FALSE) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$model = new stdClass();
		$model->fields = array(
			'p_persona' => array('label' => 'Alumno', 'maxlength' => '100', 'readonly' => TRUE),
			'p_escuela' => array('label' => 'Escuela de destino', 'readonly' => TRUE),
			'p_division' => array('label' => 'Curso y División', 'readonly' => TRUE),
			'p_fecha_desde' => array('label' => 'Fecha de baja'),
			'p_causa_salida' => array('label' => 'Causa de Salida', 'input_type' => 'combo', 'id_name' => 'p_causa_salida_id', 'required' => TRUE)
		);

		$model->fields['p_persona']['value'] = "$egreso_nuevo->documento_tipo $egreso_nuevo->documento - $egreso_nuevo->apellido, $egreso_nuevo->nombre";
		$model->fields['p_escuela']['value'] = $egreso_nuevo->escuela;
		$model->fields['p_division']['value'] = $egreso_nuevo->division;
		$model->fields['p_fecha_desde']['value'] = (new DateTime($egreso_nuevo->fecha_desde . ' -1 day'))->format('d/m/Y');
		$this->array_p_causa_salida_control = $array_p_causa_salida = $this->get_array('causa_salida', 'descripcion', 'id', array('salida_escuela' => 'Si', 'sort_by' => 'id desc'), array('1' => ''));
		$model->fields['p_causa_salida']['array'] = $array_p_causa_salida;

		$this->set_model_validation_rules($model);

		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_division_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->alumno_division_model->update(array(
					'id' => $alumno_division_id,
					'estado_id' => 3,
					'causa_salida_id' => $this->input->post('p_causa_salida'),
					'fecha_hasta' => $this->get_date_sql('p_fecha_desde')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', 'Egreso de alumno confirmado');
					redirect("alertas/alumno_egreso/$alumno_division->escuela_id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("alertas/alumno_egreso/$alumno_division->escuela_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($model->fields);
		$data['alumno_division'] = $alumno_division;
		$data['datos'] = $egreso_nuevo;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Confirmación de Egreso';
		$this->load->view('alertas/alertas_egreso_modal', $data);
	}

	public function alumno_cancelar_egreso_modal($alumno_division_id = NULL) {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('alumno_division_model');
		$this->load->model('causa_salida_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			$this->modal_error('No se encontró el registro del alumno', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($alumno_division->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}
		$egreso_nuevo = $this->alumno_division_model->get_alumno_egreso($alumno_division->alumno_id);
		if (empty($egreso_nuevo)) {
			$this->modal_error('No se encontró el registro del egreso', 'Registro no encontrado');
			return;
		}
		if ($this->edicion === FALSE) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$model = new stdClass();
		$model->fields = array(
			'p_persona' => array('label' => 'Alumno', 'maxlength' => '100', 'readonly' => TRUE),
			'p_escuela' => array('label' => 'Escuela de destino', 'readonly' => TRUE),
			'p_division' => array('label' => 'Curso y División', 'readonly' => TRUE),
		);

		$model->fields['p_persona']['value'] = "$egreso_nuevo->documento_tipo $egreso_nuevo->documento - $egreso_nuevo->apellido, $egreso_nuevo->nombre";
		$model->fields['p_escuela']['value'] = $egreso_nuevo->escuela;
		$model->fields['p_division']['value'] = $egreso_nuevo->division;

		$this->set_model_validation_rules($model);

		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_division_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.', 500, 'Acción no autorizada');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->alumno_division_model->update(array(
					'id' => $alumno_division_id,
					'estado_id' => 1));
				if ($trans_ok) {
					$this->session->set_flashdata('message', 'Se cancelo el egreso de alumno correctamente');
					redirect("alertas/alumno_egreso/$alumno_division->escuela_id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("alertas/alumno_egreso/$alumno_division->escuela_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($model->fields);
		$data['alumno_division'] = $alumno_division;
		$data['datos'] = $egreso_nuevo;
		$data['title'] = 'Cancelar de Egreso';
		$data['txt_btn'] = 'Cancelar';
		$this->load->view('alertas/alertas_egreso_modal', $data);
	}

	public function alumno_pase($escuela_id = NULL) {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
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
				array('label' => 'Documento', 'data' => 'documento', 'width' => 15, 'class' => 'text-sm'),
				array('label' => 'Alumno', 'data' => 'persona', 'width' => 40, 'class' => 'text-sm'),
				array('label' => 'Fecha de pase', 'data' => 'fecha_pase', 'render' => 'short_date', 'width' => 15),
				array('label' => '', 'data' => 'edit', 'width' => 30, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'alumno_pase_table',
			'source_url' => "alertas/alumno_pase_data/$escuela->id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(2, 'asc'), array(0, 'asc')),
			'responsive' => FALSE,
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['escuela'] = $escuela;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Alumnos con pase a otra escuela';
		$this->load_template('alertas/alertas_alumno_pase', $data);
	}

	public function alumno_pase_data($escuela_id = NULL, $rol_codigo = NULL, $entidad_id = '') {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
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
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select('alumno_pase.id, alumno_pase.fecha_pase, CONCAT(documento_tipo.descripcion_corta, \' \', persona.documento) as documento, CONCAT(COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona')
			->unset_column('id')
			->from('alumno')
			->join('alumno_pase', 'alumno_pase.alumno_id = alumno.id', 'left')
			->join('persona', 'persona.id = alumno.persona_id', 'left')
			->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id', 'left')
			->join('escuela ep', 'ep.id = alumno_pase.escuela_destino_id', 'left')
			->join('escuela e', '(e.id=ep.escuela_id or e.id=ep.id)', 'left')
			->where('e.id', $escuela_id)
			->where('alumno_pase.estado', 'Pendiente');
		if ($this->edicion) {
			$this->datatables
				->add_column('edit', '<a href="alertas/alumno_pase_modal/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-primary"><i class="fa fa-check"></i>Confirmar ingreso</a>'
					. '&nbsp<a href="alertas/alumno_pase_rechazar_modal/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i>Rechazar Ingreso</a>', 'id');
		} else {
			$this->datatables
				->add_column('edit', '', '');
		}
		echo $this->datatables->generate();
	}

	public function alumno_pase_modal($alumno_pase_id = NULL) {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $alumno_pase_id == NULL || !ctype_digit($alumno_pase_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}
		if ($this->edicion === FALSE) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$this->load->model('alumno_pase_model');
		$this->load->model('alumno_division_model');
		$this->load->model('division_model');
		$alumno_pase = $this->alumno_pase_model->get_one($alumno_pase_id);
		$escuela = $this->escuela_model->get_one($alumno_pase->escuela_destino_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		$pase_nuevo = $this->alumno_division_model->get_alumno_pase($alumno_pase->id);
		if (empty($pase_nuevo)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		$model = new stdClass();
		$model->fields = array(
			'persona' => array('label' => 'Alumno', 'maxlength' => '100', 'readonly' => TRUE),
			'escuela_origen' => array('label' => 'Escuela de origen', 'readonly' => TRUE),
			'division' => array('label' => 'División de destino', 'input_type' => 'combo', 'required' => TRUE),
			'fecha_alta' => array('label' => 'Fecha de alta', 'required' => TRUE)
		);

		$this->array_division_control = $array_division_destino = $this->get_array('division', 'division', 'id', array(
			'select' => array('division.id', 'CONCAT(curso.descripcion, \' \', division.division, \' \',CASE WHEN escuela.anexo= 0 THEN \'\' ELSE CONCAT(\'(Anexo \', escuela.anexo,\')\') END ) as division'),
			'join' => array(
				array('curso', 'curso.id = division.curso_id'),
				array('escuela', 'escuela.id = division.escuela_id'),
				array('escuela ea', 'escuela.escuela_id = ea.id OR escuela.id = ea.id')
			),
			'where' => array('division.fecha_baja IS NULL',
				array('column' => 'ea.id', 'value' => (empty($escuela->escuela_id) ? $escuela->id : $escuela->escuela_id))
			),
			'sort_by' => 'curso.descripcion, division.division'
			), array('' => '-- Seleccionar división de destino --')
		);



		$model->fields['persona']['value'] = "$pase_nuevo->documento_tipo $pase_nuevo->documento - $pase_nuevo->apellido, $pase_nuevo->nombre";
		$model->fields['escuela_origen']['value'] = $pase_nuevo->escuela_origen;
		$model->fields['fecha_alta']['value'] = (new DateTime($pase_nuevo->fecha_pase))->format('d/m/Y');

		$this->set_model_validation_rules($model);
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_pase_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$division_id = $this->input->post('division');
				if ($trans_ok) {
					$trans_ok &= $this->alumno_pase_model->update(array(
						'id' => $pase_nuevo->id,
						'fecha_pase' => $this->get_date_sql('fecha_alta'),
						'estado' => 'Confirmado'
						), FALSE);
					$division = $this->division_model->get_one($division_id);
					if ($trans_ok) {
						$trans_ok &= $this->alumno_division_model->create(array(
							'alumno_id' => $pase_nuevo->alumno_id,
							'division_id' => $division->id,
							'fecha_desde' => $this->get_date_sql('fecha_alta'),
							'ciclo_lectivo' => $ciclo_lectivo,
							'estado_id' => 1,
							'causa_entrada_id' => 3
							), FALSE);
					}
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', 'Pase de alumno confirmado');
					redirect("alertas/alumno_pase/$escuela->id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al realizar la acción.';
					if ($this->alumno_division_model->get_error())
						$errors .= '<br>' . $this->alumno_division_model->get_error();
					if ($this->alumno_pase_model->get_error())
						$errors .= '<br>' . $this->alumno_pase_model->get_error();
				}
			}else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("alertas/alumno_pase/$escuela->id", 'refresh');
			}
		}

		$model->fields['division']['array'] = $array_division_destino;

		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['fields'] = $this->build_fields($model->fields);
		$data['pase_nuevo'] = $pase_nuevo;
		$data['title'] = 'Confirmación de Pase';
		$this->load->view('alertas/alertas_pase_modal', $data);
	}

	public function alumno_pase_rechazar_modal($alumno_pase_id = NULL) {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $alumno_pase_id == NULL || !ctype_digit($alumno_pase_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		if ($this->edicion === FALSE) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$this->load->model('alumno_pase_model');
		$this->load->model('alumno_division_model');
		$this->load->model('division_model');
		$alumno_pase = $this->alumno_pase_model->get_one($alumno_pase_id);
		$escuela = $this->escuela_model->get_one($alumno_pase->escuela_destino_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			return $this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}
		$pase_nuevo = $this->alumno_division_model->get_alumno_pase($alumno_pase->id);
		if (empty($pase_nuevo)) {
			return $this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
		}
		$model = new stdClass();
		$model->fields = array(
			'persona' => array('label' => 'Alumno', 'maxlength' => '100', 'readonly' => TRUE),
			'escuela_origen' => array('label' => 'Escuela de origen', 'readonly' => TRUE),
			'motivo_rechazo' => array('label' => 'Motivo de Rechazo', 'input_type' => 'combo', 'id_name' => 'motivo_rechazo', 'required' => TRUE, 'array' => array('Alumno no ingresó' => 'Alumno no ingresó', 'Alumno ya ingresado' => 'Alumno ya ingresado'))
		);
		$this->array_motivo_rechazo_control = $model->fields['motivo_rechazo']['array'];
		$model->fields['persona']['value'] = "$pase_nuevo->documento_tipo $pase_nuevo->documento - $pase_nuevo->apellido, $pase_nuevo->nombre";
		$model->fields['escuela_origen']['value'] = $pase_nuevo->escuela_origen;

		$this->set_model_validation_rules($model);
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_pase_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				if ($trans_ok) {
					$trans_ok &= $this->alumno_pase_model->update(array(
						'id' => $pase_nuevo->id,
						'estado' => 'Rechazado',
						'motivo_rechazo' => $this->input->post('motivo_rechazo')
						), FALSE);
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', 'Pase de alumno confirmado');
					redirect("alertas/alumno_pase/$escuela->id", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->alumno_pase_model->get_error())
						$errors .= '<br>' . $this->alumno_pase_model->get_error();
				}
			}else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("alertas/alumno_pase/$escuela->id", 'refresh');
			}
		}

		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['fields'] = $this->build_fields($model->fields);
		$data['pase_nuevo'] = $pase_nuevo;
		$data['title'] = 'Rechazar pase de Alumno';
		$this->load->view('alertas/alertas_pase_rechazar_modal', $data);
	}

	public function novedades_pendientes($escuela_id = NULL) {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_ALUM))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
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
				array('label' => 'Persona', 'data' => 'persona', 'width' => 19, 'class' => 'text-sm'),
				array('label' => 'Liquidación', 'data' => 'liquidacion', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'S.R.', 'data' => 'situacion_revista', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 3, 'class' => 'dt-body-center'),
				array('label' => 'Div', 'data' => 'division', 'width' => 3, 'class' => 'dt-body-center'),
				array('label' => 'Régimen/Materia', 'data' => 'regimen_materia', 'width' => 21, 'class' => 'text-sm'),
				array('label' => 'Hs.', 'data' => 'carga_horaria', 'width' => 3, 'class' => 'dt-body-center'),
				array('label' => 'Art.', 'data' => 'articulo', 'width' => 3),
				array('label' => 'Novedad', 'data' => 'novedad_tipo', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
				array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
				array('label' => 'Dias', 'data' => 'dias', 'width' => 3, 'class' => 'dt-body-center'),
				array('label' => 'Oblig', 'data' => 'obligaciones', 'width' => 3, 'class' => 'dt-body-center'),
				array('label' => 'Mes de Novedad', 'data' => 'mes', 'width' => 3, 'class' => 'text-sm'),
				array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all')
			),
			'table_id' => 'servicio_novedad_table',
			'source_url' => "alertas/novedades_pendientes_data/$escuela_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(1, 'asc'), array(5, 'asc'), array(6, 'asc'), array(7, 'asc')),
			'reuse_var' => TRUE,
			'initComplete' => "complete_servicio_novedad_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);

		$tableData_o = array(
			'columns' => array(
				array('label' => 'Persona', 'data' => 'persona', 'width' => 19, 'class' => 'text-sm'),
				array('label' => 'Liquidación', 'data' => 'liquidacion', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'S.R.', 'data' => 'situacion_revista', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'Régimen/Origen', 'data' => 'regimen_origen', 'width' => 27, 'class' => 'text-sm'),
				array('label' => 'Hs.', 'data' => 'carga_horaria', 'width' => 3, 'class' => 'dt-body-center'),
				array('label' => 'Art.', 'data' => 'articulo', 'width' => 3),
				array('label' => 'Novedad', 'data' => 'novedad_tipo', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
				array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'short_date', 'width' => 4, 'class' => 'dt-body-center'),
				array('label' => 'Dias', 'data' => 'dias', 'width' => 3, 'class' => 'dt-body-center'),
				array('label' => 'Oblig', 'data' => 'obligaciones', 'width' => 3, 'class' => 'dt-body-center'),
				array('label' => 'Mes de Novedad', 'data' => 'mes', 'width' => 3, 'class' => 'text-sm'),
				array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'sortable' => 'false', 'searchable' => 'false', 'responsive_class' => 'all')
			),
			'table_id' => 'servicio_novedad_o_table',
			'source_url' => "alertas/novedades_pendientes_data_otros/$escuela_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(0, 'asc'), array(3, 'asc'), array(4, 'asc')),
			'reuse_var' => TRUE,
			'initComplete' => "complete_servicio_novedad_o_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['escuela'] = $escuela;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['html_table_o'] = buildHTML($tableData_o);
		$data['js_table_o'] = buildJS($tableData_o);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Novedades de servicios';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('alertas/alertas_novedades_pendientes', $data);
	}

	public function novedades_pendientes_data($escuela_id = NULL, $rol_codigo = NULL, $entidad_id = '') {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_ALUM))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
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
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select('servicio_novedad.id, servicio_novedad.fecha_desde, CASE novedad_tipo.id WHEN 1 THEN \'\' ELSE servicio_novedad.fecha_hasta END as fecha_hasta, CASE novedad_tipo.id WHEN 1 THEN \'\' ELSE servicio_novedad.dias END as dias,CASE novedad_tipo.id WHEN 1 THEN \'\' ELSE servicio_novedad.obligaciones END as obligaciones, '
				. 'CASE novedad_tipo.id WHEN 1 THEN \'Alta\' ELSE CONCAT(novedad_tipo.articulo, \'-\', novedad_tipo.inciso) END as articulo, novedad_tipo.descripcion_corta as novedad_tipo, novedad_tipo.novedad, '
				. 'servicio.liquidacion, cargo.carga_horaria, CONCAT(persona.cuil, \'<br>\', persona.apellido, \', \', persona.nombre) as persona, '
				. 'situacion_revista.descripcion as situacion_revista,division.division, curso.descripcion as curso, '
				. 'CONCAT(regimen.descripcion, \'<br>\', COALESCE(materia.descripcion, \'\')) as regimen_materia, cargo.escuela_id, servicio_novedad.origen_id, planilla_asisnov.fecha_cierre,CONCAT(RIGHT(servicio_novedad.ames,2), \'/\', LEFT(servicio_novedad.ames,4)) as mes,servicio_novedad.ames')
			->unset_column('id')
			->from('servicio_novedad')
			->join('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id', 'left')
			->join('servicio', 'servicio.id = servicio_novedad.servicio_id', '')
			->join('cargo', 'cargo.id = servicio.cargo_id', '')
			->join('espacio_curricular', 'espacio_curricular.id = cargo.espacio_curricular_id', 'left')
			->join('materia', 'espacio_curricular.materia_id = materia.id', 'left')
			->join('persona', 'persona.id = servicio.persona_id', 'left')
			->join('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id', 'left')
			->join('division', 'cargo.division_id = division.id', 'left')
			->join('curso', 'division.curso_id = curso.id', 'left')
			->join('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1')
			->join('planilla_asisnov', 'servicio_novedad.planilla_alta_id = planilla_asisnov.id', 'left')
			->where('servicio_novedad.planilla_baja_id IS NULL')
			->where('cargo.escuela_id', $escuela_id);
		if ($this->edicion) {
			$this->datatables->add_column('menu', '$1', 'dt_column_servicio_novedad_menu(ames, id, escuela_id, novedad, 0, 2, fecha_cierre, origen_id)');
		} else {
			$this->datatables->add_column('menu', '<a class="btn btn-xs btn-default" href="servicio_novedad/modal_ver/$3/$1/$2/0" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>', 'id, escuela_id, ames');
		}
		$this->datatables->where('servicio_novedad.estado', 'Pendiente');
		echo $this->datatables->generate();
	}

	public function novedades_pendientes_data_otros($escuela_id = NULL, $rol_codigo = NULL, $entidad_id = '') {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_ALUM))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
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
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select('servicio_novedad.id, servicio_novedad.fecha_desde, servicio_novedad.fecha_hasta, servicio_novedad.dias, servicio_novedad.obligaciones, '
				. 'CONCAT(novedad_tipo.articulo, \'-\', novedad_tipo.inciso) as articulo, novedad_tipo.descripcion_corta as novedad_tipo, novedad_tipo.novedad, '
				. 'servicio.liquidacion, cargo.carga_horaria, CONCAT(persona.cuil, \'<br>\', persona.apellido, \', \', persona.nombre) as persona, '
				. 'situacion_revista.descripcion as situacion_revista, servicio_funcion.escuela_id, '
				. 'CONCAT(regimen.descripcion, \'<br>\', COALESCE(CONCAT(area.codigo, \' \', area.descripcion), CONCAT(escuela.numero, \' \', escuela.nombre, CASE WHEN anexo = 0 THEN \'\' ELSE CONCAT(\'/\', anexo) END))) as regimen_origen, servicio_novedad.origen_id, planilla_asisnov.fecha_cierre,CONCAT(RIGHT(servicio_novedad.ames,2), \'/\', LEFT(servicio_novedad.ames,4)) as mes,servicio_novedad.ames')
			->unset_column('id')
			->from('servicio_novedad')
			->join('novedad_tipo', 'novedad_tipo.id = servicio_novedad.novedad_tipo_id', 'left')
			->join('servicio', 'servicio.id = servicio_novedad.servicio_id', '')
			->join('servicio_funcion', 'servicio.id = servicio_funcion.servicio_id AND servicio_funcion.fecha_hasta IS NULL')
			->join('cargo', 'cargo.id = servicio.cargo_id', '')
			->join('persona', 'persona.id = servicio.persona_id', 'left')
			->join('situacion_revista', 'situacion_revista.id = servicio.situacion_revista_id', 'left')
			->join('escuela', 'escuela.id = cargo.escuela_id', 'left')
			->join('area', 'cargo.area_id = area.id', 'left')
			->join('regimen', 'cargo.regimen_id = regimen.id AND regimen.planilla_modalidad_id=1', '')
			->join('planilla_asisnov', 'servicio_novedad.planilla_alta_id = planilla_asisnov.id', 'left')
			->where('servicio_novedad.planilla_baja_id IS NULL')
			->where('servicio_funcion.escuela_id', $escuela_id)
			->add_column('', '', 'id');
		if ($this->edicion) {
			$this->datatables->add_column('menu', '$1', 'dt_column_servicio_novedad_menu(ames, id, escuela_id, novedad,1,2,1)');
		} else {
			$this->datatables->add_column('menu', '<a class="btn btn-xs btn-default" href="servicio_novedad/modal_ver/$3/$1/$2/1" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>', 'id, escuela_id,ames');
		}
		$this->datatables->where('servicio_novedad.estado', 'Pendiente');
		echo $this->datatables->generate();
	}

	public function alumno_email($escuela_id = NULL) {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
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
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Alumno', 'data' => 'persona', 'width' => 30, 'class' => 'text-sm'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Div', 'data' => 'division', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'C.L.', 'data' => 'ciclo_lectivo', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'short_date', 'width' => 10),
				array('label' => 'Email', 'data' => 'email_contacto', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'alumno_table',
			'source_url' => "alertas/alumno_email_data/$escuela->id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'order' => array(array(2, 'asc'), array(3, 'asc'), array(0, 'asc')),
			'responsive' => FALSE,
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['escuela'] = $escuela;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Alumnos sin email de notificación';
		$this->load_template('alertas/alertas_alumno_email', $data);
	}

	public function alumno_email_data($escuela_id = NULL, $rol_codigo = NULL, $entidad_id = '') {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
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
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select('alumno_division.id, alumno.observaciones, alumno.email_contacto, alumno_division.fecha_desde, alumno_division.fecha_hasta, alumno_division.ciclo_lectivo, CONCAT(documento_tipo.descripcion_corta, \' \', persona.documento) as documento, CONCAT(COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona, curso.descripcion as curso, division.division, division.escuela_id')
			->unset_column('id')
			->from('alumno')
			->join('persona', 'persona.id = alumno.persona_id', 'left')
			->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id', 'left')
			->join('alumno_division', 'alumno_division.alumno_id = alumno.id', 'left')
			->join('division', 'division.id = alumno_division.division_id', 'left')
			->join('curso', 'division.curso_id = curso.id', 'left')
			->where('division.escuela_id', $escuela_id)
			->where("COALESCE(alumno.email_contacto,'')=''")
			->where('alumno_division.fecha_hasta IS NULL')
			->group_by('alumno.id');
		if ($this->edicion) {
			$this->datatables
				->add_column('edit', '<a href="alumno/ver/$1" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="alertas/modal_alumno_email/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Editar</a>', 'id');
		} else {
			$this->datatables
				->add_column('edit', '<a href="alumno/ver/$1" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a>', 'id');
		}
		echo $this->datatables->generate();
	}

	public function modal_alumno_email($alumno_division_id = NULL) {
		if (in_array($this->rol->codigo, array(ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		if (!in_array($this->rol->codigo, $this->roles_escuela) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if ($this->edicion === FALSE) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			$this->modal_error('No se encontró el registro del alumno', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($alumno_division->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}
		$this->load->model('alumno_model');
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		$alumno->alumno = "$alumno->documento_tipo $alumno->documento - $alumno->apellido, $alumno->nombre";
		$alumno->division = "$alumno_division->curso $alumno_division->division";
		$alumno->escuela = $escuela->nombre_largo;

		$model = new stdClass();
		$model->fields = array(
			'alumno' => array('label' => 'Alumno', 'maxlength' => '100', 'readonly' => TRUE),
			'escuela' => array('label' => 'Escuela', 'readonly' => TRUE),
			'division' => array('label' => 'División', 'readonly' => TRUE),
			'email_contacto' => array('label' => 'Email de Contacto / Notificaciones', 'type' => 'email', 'maxlength' => '150', 'required' => TRUE),
			'observaciones' => array('label' => 'Observaciones')
		);

		$this->set_model_validation_rules($model);
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_division_id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->alumno_model->update(array(
					'id' => $alumno->id,
					'email_contacto' => $this->input->post('email_contacto'),
					'observaciones' => $this->input->post('observaciones')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', 'Email de alumno cargado');
				} else {
					$this->session->set_flashdata('error', $this->alumno_model->get_error());
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect("alertas/alumno_email/$alumno_division->escuela_id", 'refresh');
		}

		$data['fields'] = $this->build_fields($model->fields, $alumno);
		$data['alumno_division'] = $alumno_division;
		$data['title'] = 'Cargar email de notificación';
		$this->load->view('alertas/alertas_alumno_email_modal', $data);
	}
}
/* End of file Alertas.php */
/* Location: ./application/controllers/Alertas.php */