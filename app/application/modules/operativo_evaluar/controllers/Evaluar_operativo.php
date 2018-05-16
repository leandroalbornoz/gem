<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluar_operativo extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('evaluar_operativo_model');
		$this->load->model('escuela_model');
		$this->load->model('division_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI);
		$this->roles_permitidos_escuela = array(ROL_ADMIN, ROL_USI, ROL_DIR_ESCUELA);
		$this->modulos_permitidos = array(ROL_MODULO_LEER_ESCRIBIR, ROL_ADMIN);
		$this->nav_route = 'par/evaluar_operativo';
	}

	public function listar_escuelas() {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
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
				array('label' => 'Carga', 'data' => 'porcentaje_cargado', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Teléfono', 'data' => 'telefono', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Email', 'data' => 'email', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'escuela_table',
			'source_url' => "operativo_evaluar/evaluar_operativo/listar_data_escuelas/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'reuse_var' => TRUE,
			'initComplete' => "complete_escuela_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);


		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$this->load->model('nivel_model');
		$nivel = $this->nivel_model->get(array('id' => 2));
		$data['nivel'] = $nivel;
		$data['title'] = TITLE . ' - Escuelas Nivel ' . $nivel->descripcion;
		$this->load_template('operativo_evaluar/evaluar_operativo/evaluar_operativo_listar_escuelas', $data);
	}

	public function listar_data_escuelas($rol_codigo, $entidad_id = '') {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}

		$this->datatables
			->select("e.id, e.numero, e.anexo, e.cue, e.subcue, e.nombre, e.calle, e.calle_numero, e.departamento, e.piso, e.barrio, e.manzana, e.casa, e.localidad_id, e.nivel_id, e.reparticion_id, e.supervision_id, e.regional_id, e.dependencia_id, e.zona_id, e.fecha_creacion, e.anio_resolucion, e.numero_resolucion, e.telefono, e.email, e.fecha_cierre, e.anio_resolucion_cierre, e.numero_resolucion_cierre, n.descripcion as nivel, reg.descripcion as regional, CONCAT(jur.codigo,'/',rep.codigo) as jurirepa, s.nombre as supervision,count(DISTINCT ad.id) as total_alumnos, count(DISTINCT eo.id) as total_evaluaciones, CONCAT(FORMAT(100*((count(DISTINCT eo.id))/count(DISTINCT ad.id)),2),'%') as porcentaje_cargado")
			->unset_column('id')
			->from('alumno_division ad')
			->join('evaluar_operativo eo', 'ad.id = eo.alumno_division_id', 'left')
			->join('division d', 'ad.division_id = d.id')
			->join('curso c', "c.id = d.curso_id AND (c.id = 8 OR (c.id = 71 AND d.division LIKE '%2%'))")
			->join('escuela e', 'e.id = d.escuela_id')
			->join('nivel n', 'e.nivel_id = n.id AND e.nivel_id=2')
			->join('regional reg', 'reg.id = e.regional_id', 'left')
			->join('reparticion rep', 'rep.id = e.reparticion_id', 'left')
			->join('jurisdiccion jur', 'jur.id = rep.jurisdiccion_id', 'left')
			->join('supervision s', 's.id = e.supervision_id', 'left')
			->where('ad.ciclo_lectivo', 2017)
			->where("COALESCE(ad.fecha_hasta,'2017-12-01')>='2017-12-01'")
			->group_by('e.id, e.anexo');
		$this->datatables->add_column('edit', '<a class="btn btn-xs btn-default" href="operativo_evaluar/evaluar_operativo/listar_divisiones/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id');

		echo $this->datatables->generate();
	}

	public function listar_divisiones($escuela_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos_escuela, $this->modulos_permitidos) ||
			$escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		if ($this->rol->codigo === ROL_DIR_ESCUELA && ($escuela->dependencia_id !== '2' || $escuela->nivel_id !== '2')) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$divisiones = $this->evaluar_operativo_model->get_divisiones($escuela_id, 2017);
		$porcentaje_carga = $this->evaluar_operativo_model->get_porcentaje_carga($escuela->id, 2017);
		$data['divisiones'] = $divisiones;
		$data['porcentaje_carga'] = $porcentaje_carga;
		$data['escuela'] = $escuela;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Divisiones';
		$this->load_template('operativo_evaluar/evaluar_operativo/evaluar_operativo_listar_divisiones', $data);
	}

	public function listar_alumnos($division_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos_escuela, $this->modulos_permitidos) ||
			$division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$division = $this->division_model->get_one($division_id);
		if (empty($division)) {
			show_error('No se encontró el registro de division', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		if ($this->rol->codigo === ROL_DIR_ESCUELA && ($escuela->dependencia_id !== '2' || $escuela->nivel_id !== '2')) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$ciclo_lectivo = 2017;
		$alumnos_division = $this->evaluar_operativo_model->get_alumnos_division($division->id, $ciclo_lectivo);
		$data['alumnos_division'] = $alumnos_division;
		$data['escuela'] = $escuela;
		$data['division'] = $division;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Divisiones';
		$this->load_template('operativo_evaluar/evaluar_operativo/evaluar_operativo_listar_alumnos', $data);
	}

	public function agregar($alumno_division_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos_escuela, $this->modulos_permitidos) ||
			$alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			show_error('No se encontró el registro de alumno', 500, 'Registro no encontrado');
		}
		$this->load->model('alumno_model');
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		if (empty($alumno)) {
			show_error('No se encontró el registro de alumno', 500, 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($alumno_division->division_id);
		if (empty($division)) {
			show_error('No se encontró el registro de division', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		if ($this->rol->codigo === ROL_DIR_ESCUELA && ($escuela->dependencia_id !== '2' || $escuela->nivel_id !== '2')) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->array_puntuacion_1_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_2_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_3_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_4_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_5_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_6a_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_6b_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_7_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_8_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_9_control = array('0' => 0, '1' => 1, '2' => 2);
		$this->array_puntuacion_10a_control = array('0' => 0, '1' => 1, '2' => 2, '3' => 3);
		$this->array_puntuacion_10b_control = array('0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4);
		$this->array_puntuacion_11a_control = array('0' => 0, '1' => 1, '2' => 2);
		$this->array_puntuacion_11b_control = array('0' => 0, '1' => 1, '2' => 2, '3' => 3);
		$this->array_puntuacion_11c_control = array('0' => 0, '1' => 1, '2' => 2);
		$this->form_validation->set_rules("puntuacion_1", 'Puntuacion_1', 'callback_control_combo[puntuacion_1]');
		$this->form_validation->set_rules("puntuacion_2", 'Puntuacion_2', 'callback_control_combo[puntuacion_2]');
		$this->form_validation->set_rules("puntuacion_3", 'Puntuacion_3', 'callback_control_combo[puntuacion_3]');
		$this->form_validation->set_rules("puntuacion_4", 'Puntuacion_4', 'callback_control_combo[puntuacion_4]');
		$this->form_validation->set_rules("puntuacion_5", 'Puntuacion_5', 'callback_control_combo[puntuacion_5]');
		$this->form_validation->set_rules("puntuacion_6a", 'Puntuacion_6a', 'callback_control_combo[puntuacion_6a]');
		$this->form_validation->set_rules("puntuacion_6b", 'Puntuacion_6b', 'callback_control_combo[puntuacion_6b]');
		$this->form_validation->set_rules("puntuacion_7", 'Puntuacion_7', 'callback_control_combo[puntuacion_7]');
		$this->form_validation->set_rules("puntuacion_8", 'Puntuacion_8', 'callback_control_combo[puntuacion_8]');
		$this->form_validation->set_rules("puntuacion_9", 'Puntuacion_9', 'callback_control_combo[puntuacion_9]');
		$this->form_validation->set_rules("puntuacion_10a", 'Puntuacion_10a', 'callback_control_combo[puntuacion_10a]');
		$this->form_validation->set_rules("puntuacion_10b", 'Puntuacion_10b', 'callback_control_combo[puntuacion_10b]');
		$this->form_validation->set_rules("puntuacion_11a", 'Puntuacion_11a', 'callback_control_combo[puntuacion_11a]');
		$this->form_validation->set_rules("puntuacion_11b", 'Puntuacion_11b', 'callback_control_combo[puntuacion_11b]');
		$this->form_validation->set_rules("puntuacion_11c", 'Puntuacion_11c', 'callback_control_combo[puntuacion_11c]');

		if ($this->form_validation->run() === TRUE) {
			$trans_ok = TRUE;
			$trans_ok &= $this->evaluar_operativo_model->create(array(
				'fecha_carga' => date('Y-m-d'),
				'alumno_id' => $alumno_division->alumno_id,
				'alumno_division_id' => $alumno_division->id,
				'puntuacion_1' => $this->input->post('puntuacion_1'),
				'puntuacion_2' => $this->input->post('puntuacion_2'),
				'puntuacion_3' => $this->input->post('puntuacion_3'),
				'puntuacion_4' => $this->input->post('puntuacion_4'),
				'puntuacion_5' => $this->input->post('puntuacion_5'),
				'puntuacion_6a' => $this->input->post('puntuacion_6a'),
				'puntuacion_6b' => $this->input->post('puntuacion_6b'),
				'puntuacion_7' => $this->input->post('puntuacion_7'),
				'puntuacion_8' => $this->input->post('puntuacion_8'),
				'puntuacion_9' => $this->input->post('puntuacion_9'),
				'puntuacion_10a' => $this->input->post('puntuacion_10a'),
				'puntuacion_10b' => $this->input->post('puntuacion_10b'),
				'puntuacion_11a' => $this->input->post('puntuacion_11a'),
				'puntuacion_11b' => $this->input->post('puntuacion_11b'),
				'puntuacion_11c' => $this->input->post('puntuacion_11c'),
				'estado' => 'Presente'
			));

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->evaluar_operativo_model->get_msg());
				redirect("operativo_evaluar/evaluar_operativo/listar_alumnos/$division->id", 'refresh');
			}
		}

		$data['escuela'] = $escuela;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = (validation_errors() ? validation_errors() : ($this->evaluar_operativo_model->get_error() ? $this->evaluar_operativo_model->get_error() : $this->session->flashdata('error')));
		$data['alumno'] = $alumno;
		$data['alumno_division'] = $alumno_division;
		$data['division'] = $division;
		$data['fields'] = $this->build_fields($this->evaluar_operativo_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = TITLE . ' - Agregar evaluacion';
		$this->load_template('operativo_evaluar/evaluar_operativo/evaluar_operativo_abm_evaluacion', $data);
	}

	public function editar($evaluar_operativo_id = NULL, $alumno_division_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos_escuela, $this->modulos_permitidos) ||
			$alumno_division_id == NULL || !ctype_digit($alumno_division_id) ||
			$evaluar_operativo_id == NULL || !ctype_digit($evaluar_operativo_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			show_error('No se encontró el registro de alumno', 500, 'Registro no encontrado');
		}
		$this->load->model('alumno_model');
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		if (empty($alumno)) {
			show_error('No se encontró el registro de alumno', 500, 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($alumno_division->division_id);
		if (empty($division)) {
			show_error('No se encontró el registro de division', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		if ($this->rol->codigo === ROL_DIR_ESCUELA && ($escuela->dependencia_id !== '2' || $escuela->nivel_id !== '2')) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$evaluar_operativo = $this->evaluar_operativo_model->get_one($evaluar_operativo_id);
		if (empty($evaluar_operativo)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}

		$this->array_puntuacion_1_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_2_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_3_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_4_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_5_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_6a_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_6b_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_7_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_8_control = array('0' => 0, '1' => 1);
		$this->array_puntuacion_9_control = array('0' => 0, '1' => 1, '2' => 2);
		$this->array_puntuacion_10a_control = array('0' => 0, '1' => 1, '2' => 2, '3' => 3);
		$this->array_puntuacion_10b_control = array('0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4);
		$this->array_puntuacion_11a_control = array('0' => 0, '1' => 1, '2' => 2);
		$this->array_puntuacion_11b_control = array('0' => 0, '1' => 1, '2' => 2, '3' => 3);
		$this->array_puntuacion_11c_control = array('0' => 0, '1' => 1, '2' => 2);
		$this->form_validation->set_rules("puntuacion_1", 'Puntuacion_1', 'callback_control_combo[puntuacion_1]');
		$this->form_validation->set_rules("puntuacion_2", 'Puntuacion_2', 'callback_control_combo[puntuacion_2]');
		$this->form_validation->set_rules("puntuacion_3", 'Puntuacion_3', 'callback_control_combo[puntuacion_3]');
		$this->form_validation->set_rules("puntuacion_4", 'Puntuacion_4', 'callback_control_combo[puntuacion_4]');
		$this->form_validation->set_rules("puntuacion_5", 'Puntuacion_5', 'callback_control_combo[puntuacion_5]');
		$this->form_validation->set_rules("puntuacion_6a", 'Puntuacion_6a', 'callback_control_combo[puntuacion_6a]');
		$this->form_validation->set_rules("puntuacion_6b", 'Puntuacion_6b', 'callback_control_combo[puntuacion_6b]');
		$this->form_validation->set_rules("puntuacion_7", 'Puntuacion_7', 'callback_control_combo[puntuacion_7]');
		$this->form_validation->set_rules("puntuacion_8", 'Puntuacion_8', 'callback_control_combo[puntuacion_8]');
		$this->form_validation->set_rules("puntuacion_9", 'Puntuacion_9', 'callback_control_combo[puntuacion_9]');
		$this->form_validation->set_rules("puntuacion_10a", 'Puntuacion_10a', 'callback_control_combo[puntuacion_10a]');
		$this->form_validation->set_rules("puntuacion_10b", 'Puntuacion_10b', 'callback_control_combo[puntuacion_10b]');
		$this->form_validation->set_rules("puntuacion_11a", 'Puntuacion_11a', 'callback_control_combo[puntuacion_11a]');
		$this->form_validation->set_rules("puntuacion_11b", 'Puntuacion_11b', 'callback_control_combo[puntuacion_11b]');
		$this->form_validation->set_rules("puntuacion_11c", 'Puntuacion_11c', 'callback_control_combo[puntuacion_11c]');
		if (isset($_POST) && !empty($_POST)) {
			if ($evaluar_operativo->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->evaluar_operativo_model->update(array(
					'id' => $this->input->post('id'),
					'puntuacion_1' => $this->input->post('puntuacion_1'),
					'puntuacion_2' => $this->input->post('puntuacion_2'),
					'puntuacion_3' => $this->input->post('puntuacion_3'),
					'puntuacion_4' => $this->input->post('puntuacion_4'),
					'puntuacion_5' => $this->input->post('puntuacion_5'),
					'puntuacion_6a' => $this->input->post('puntuacion_6a'),
					'puntuacion_6b' => $this->input->post('puntuacion_6b'),
					'puntuacion_7' => $this->input->post('puntuacion_7'),
					'puntuacion_8' => $this->input->post('puntuacion_8'),
					'puntuacion_9' => $this->input->post('puntuacion_9'),
					'puntuacion_10a' => $this->input->post('puntuacion_10a'),
					'puntuacion_10b' => $this->input->post('puntuacion_10b'),
					'puntuacion_11a' => $this->input->post('puntuacion_11a'),
					'puntuacion_11b' => $this->input->post('puntuacion_11b'),
					'puntuacion_11c' => $this->input->post('puntuacion_11c')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->evaluar_operativo_model->get_msg());
					redirect("operativo_evaluar/evaluar_operativo/listar_alumnos/$division->id", 'refresh');
				}
			}
		}

		$data['escuela'] = $escuela;
		$data['alumno_division'] = $alumno_division;
		$data['alumno'] = $alumno;
		$data['division'] = $division;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = (validation_errors() ? validation_errors() : ($this->evaluar_operativo_model->get_error() ? $this->evaluar_operativo_model->get_error() : $this->session->flashdata('error')));
		$data['fields'] = $this->build_fields($this->evaluar_operativo_model->fields, $evaluar_operativo);
		$data['evaluar_operativo'] = $evaluar_operativo;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar evaluacion';
		$this->load_template('operativo_evaluar/evaluar_operativo/evaluar_operativo_abm_evaluacion', $data);
	}

	public function eliminar($evaluar_operativo_id = NULL, $alumno_division_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos_escuela, $this->modulos_permitidos) ||
			$alumno_division_id == NULL || !ctype_digit($alumno_division_id) ||
			$evaluar_operativo_id == NULL || !ctype_digit($evaluar_operativo_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			show_error('No se encontró el registro de alumno', 500, 'Registro no encontrado');
		}
		$this->load->model('alumno_model');
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		if (empty($alumno)) {
			show_error('No se encontró el registro de alumno', 500, 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($alumno_division->division_id);
		if (empty($division)) {
			show_error('No se encontró el registro de division', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		if ($this->rol->codigo === ROL_DIR_ESCUELA && ($escuela->dependencia_id !== '2' || $escuela->nivel_id !== '2')) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$evaluar_operativo = $this->evaluar_operativo_model->get_one($evaluar_operativo_id);
		if (empty($evaluar_operativo)) {
			show_error('No se encontró el registro de evaluación a editar', 500, 'Registro no encontrado');
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($evaluar_operativo->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->evaluar_operativo_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->evaluar_operativo_model->get_msg());
				redirect("operativo_evaluar/evaluar_operativo/listar_alumnos/$division->id", 'refresh');
			}
		}

		$data['escuela'] = $escuela;
		$data['alumno_division'] = $alumno_division;
		$data['alumno'] = $alumno;
		$data['division'] = $division;
		$data['error'] = (validation_errors() ? validation_errors() : ($this->evaluar_operativo_model->get_error() ? $this->evaluar_operativo_model->get_error() : $this->session->flashdata('error')));
		$data['fields'] = $this->build_fields($this->evaluar_operativo_model->fields, $evaluar_operativo, TRUE);
		$data['evaluar_operativo'] = $evaluar_operativo;
		$data['txt_btn'] = 'Eliminar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => 'active btn-app-zetta-active');
		$data['title'] = TITLE . ' - Eliminar evaluación';
		$this->load_template('operativo_evaluar/evaluar_operativo/evaluar_operativo_abm_evaluacion', $data);
	}

	public function modal_establecer_ausente($alumno_division_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos_escuela, $this->modulos_permitidos) ||
			$alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}

		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			return $this->modal_error('No se encontró el registro de alumno', 'Registro no encontrado');
		}
		$this->load->model('alumno_model');
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		if (empty($alumno)) {
			return $this->modal_error('No se encontró el registro de alumno', 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($alumno_division->division_id);
		if (empty($division)) {
			return $this->modal_error('No se encontró el registro de division', 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de escuela', 'Registro no encontrado');
		}
		if ($this->rol->codigo === ROL_DIR_ESCUELA && ($escuela->dependencia_id !== '2' || $escuela->nivel_id !== '2')) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_division->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->evaluar_operativo_model->create(array(
				'fecha_carga' => date('Y-m-d'),
				'alumno_id' => $alumno_division->alumno_id,
				'alumno_division_id' => $alumno_division->id,
				'puntuacion_1' => '0',
				'puntuacion_2' => '0',
				'puntuacion_3' => '0',
				'puntuacion_4' => '0',
				'puntuacion_5' => '0',
				'puntuacion_6a' => '0',
				'puntuacion_6b' => '0',
				'puntuacion_7' => '0',
				'puntuacion_8' => '0',
				'puntuacion_9' => '0',
				'puntuacion_10a' => '0',
				'puntuacion_10b' => '0',
				'puntuacion_11a' => '0',
				'puntuacion_11b' => '0',
				'puntuacion_11c' => '0',
				'estado' => 'Ausente'
				)
			);

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->evaluar_operativo_model->get_msg());
				redirect("operativo_evaluar/evaluar_operativo/listar_alumnos/$division->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->evaluar_operativo_model->get_error());
				redirect("operativo_evaluar/evaluar_operativo/listar_alumnos/$division->id", 'refresh');
			}
		}
		$data['alumno_division'] = $alumno_division;
		$data['txt_btn'] = 'Confirmar';
		$data['title'] = 'Confirmar Ausente';
		$this->load->view('operativo_evaluar/evaluar_operativo/evaluar_operativo_modal_establecer_ausente', $data);
	}

	public function modal_eliminar_ausente($evaluar_operativo_id = NULL, $alumno_division_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos_escuela, $this->modulos_permitidos) ||
			$alumno_division_id == NULL || !ctype_digit($alumno_division_id) ||
			$evaluar_operativo_id == NULL || !ctype_digit($evaluar_operativo_id)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}

		$this->load->model('alumno_model');
		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			return $this->modal_error('No se encontró el registro de alumno', 'Registro no encontrado');
		}
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		if (empty($alumno)) {
			return $this->modal_error('No se encontró el registro de alumno', 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($alumno_division->division_id);
		if (empty($division)) {
			return $this->modal_error('No se encontró el registro de division', 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			return $this->modal_error('No se encontró el registro de escuela', 'Registro no encontrado');
		}
		if ($this->rol->codigo === ROL_DIR_ESCUELA && ($escuela->dependencia_id !== '2' || $escuela->nivel_id !== '2')) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$evaluar_operativo = $this->evaluar_operativo_model->get_one($evaluar_operativo_id);
		if (empty($evaluar_operativo)) {
			return $this->modal_error('No se encontró el registro de evaluación a editar', 'Registro no encontrado');
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($evaluar_operativo->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->evaluar_operativo_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->evaluar_operativo_model->get_msg());
				redirect('operativo_evaluar/evaluar_operativo/listar_alumnos/' . $division->id, 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->evaluar_operativo_model->get_error());
				redirect('operativo_evaluar/evaluar_operativo/listar_alumnos/' . $division->id, 'refresh');
			}
		}
		$data['evaluar_operativo'] = $evaluar_operativo;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar registro de Ausente';
		$this->load->view('operativo_evaluar/evaluar_operativo/evaluar_operativo_modal_eliminar_ausente', $data);
	}

	public function ver($evaluar_operativo_id = NULL, $alumno_division_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos_escuela, $this->modulos_permitidos) ||
			$alumno_division_id == NULL || !ctype_digit($alumno_division_id) ||
			$evaluar_operativo_id == NULL || !ctype_digit($evaluar_operativo_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			show_error('No se encontró el registro de alumno', 500, 'Registro no encontrado');
		}
		$this->load->model('alumno_model');
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		if (empty($alumno)) {
			show_error('No se encontró el registro de alumno', 500, 'Registro no encontrado');
		}
		$division = $this->division_model->get_one($alumno_division->division_id);
		if (empty($division)) {
			show_error('No se encontró el registro de division', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		if ($this->rol->codigo === ROL_DIR_ESCUELA && ($escuela->dependencia_id !== '2' || $escuela->nivel_id !== '2')) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$evaluar_operativo = $this->evaluar_operativo_model->get_one($evaluar_operativo_id);
		if (empty($evaluar_operativo)) {
			show_error('No se encontró el registro de evaluación a editar', 500, 'Registro no encontrado');
		}

		$data['escuela'] = $escuela;
		$data['alumno_division'] = $alumno_division;
		$data['alumno'] = $alumno;
		$data['division'] = $division;
		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->evaluar_operativo_model->fields, $evaluar_operativo, TRUE);
		$data['evaluar_operativo'] = $evaluar_operativo;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver evaluacion';
		$this->load_template('operativo_evaluar/evaluar_operativo/evaluar_operativo_abm_evaluacion', $data);
	}

	public function excel_reporte_alumnos($division_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos_escuela, $this->modulos_permitidos) ||
			$division_id == NULL || !ctype_digit($division_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$division = $this->division_model->get_one($division_id);
		if (empty($division)) {
			show_error('No se encontró el registro de division', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($division->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		if ($this->rol->codigo === ROL_DIR_ESCUELA && ($escuela->dependencia_id !== '2' || $escuela->nivel_id !== '2')) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$ciclo_lectivo = 2017;

		$campos = array(
			'A' => array('Documento', 15),
			'B' => array('Persona', 35),
			'C' => array('F. Nac', 15),
			'D' => array('F. Ingreso', 15),
			'E' => array('Sexo', 10),
			'F' => array('Curso', 15),
			'G' => array('División.', 15),
			'H' => array('Estado.', 15),
			'I' => array('Comprensión y producción del texto', 17),
			'J' => array('', 17),
			'K' => array('Solución de problemas', 12),
			'L' => array('', 12),
			'M' => array('Nivel de desempeño', 20)
		);

		$alumnos = $this->db->select("CONCAT(documento_tipo.descripcion_corta,' ', persona.documento) as documento, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, DATE_FORMAT(persona.fecha_nacimiento,'%d/%m/%Y'), DATE_FORMAT(alumno_division.fecha_desde,'%d/%m/%Y'), COALESCE(CONCAT(CASE WHEN sexo.id=1 THEN 'M' WHEN sexo.id=2 THEN 'F' ELSE '' END), ' ') as sexo,  curso.descripcion as curso, CASE curso.grado_multiple WHEN 'Si' THEN CONCAT(division.division, ' (', COALESCE(c_gm.descripcion, ''), ')') ELSE division.division END as division, eo.estado, CASE WHEN eo.estado='Ausente' THEN '' ELSE (eo.puntuacion_1 + eo.puntuacion_2 + eo.puntuacion_3 + eo.puntuacion_4 + eo.puntuacion_5 + eo.puntuacion_6a + eo.puntuacion_6b + eo.puntuacion_7 + eo.puntuacion_8 + eo.puntuacion_9 + eo.puntuacion_11a + eo.puntuacion_11b + eo.puntuacion_11c) END as puntuacion_1, CASE WHEN eo.estado='Ausente' THEN '' ELSE (eo.puntuacion_1 + eo.puntuacion_2 + eo.puntuacion_3 + eo.puntuacion_4 + eo.puntuacion_5 + eo.puntuacion_6a + eo.puntuacion_6b + eo.puntuacion_7 + eo.puntuacion_8 + eo.puntuacion_9 + eo.puntuacion_11a + eo.puntuacion_11b + eo.puntuacion_11c)/18 END, CASE WHEN eo.estado='Ausente' THEN '' ELSE(eo.puntuacion_10a + eo.puntuacion_10b) END as puntuacion_2, CASE WHEN eo.estado='Ausente' THEN '' ELSE(eo.puntuacion_10a + eo.puntuacion_10b)/7 END, CASE WHEN eo.estado='Ausente' THEN '' ELSE (((eo.puntuacion_1 + eo.puntuacion_2 + eo.puntuacion_3 + eo.puntuacion_4 + eo.puntuacion_5 + eo.puntuacion_6a + eo.puntuacion_6b + eo.puntuacion_7 + eo.puntuacion_8 + eo.puntuacion_9 + eo.puntuacion_11a + eo.puntuacion_11b + eo.puntuacion_11c) + (eo.puntuacion_10a + eo.puntuacion_10b)) * 10 / 25) END as nivel_desempeño")
				->from('alumno')
				->join('persona', 'persona.id = alumno.persona_id')
				->join('sexo', 'sexo.id = persona.sexo_id', 'left')
				->join('alumno_division', 'alumno_division.alumno_id = alumno.id')
				->join('division', 'division.id = alumno_division.division_id')
				->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id')
				->join('curso', 'division.curso_id = curso.id')
				->join('curso c_gm', 'alumno_division.curso_id = c_gm.id', 'left')
				->join('evaluar_operativo eo', 'alumno.id = eo.alumno_id', 'left')
				->where('division.id', $division_id)
				->where("COALESCE(alumno_division.fecha_hasta,'2017-12-01')>='2017-12-01'")
				->where('ciclo_lectivo', $ciclo_lectivo)
				->order_by('division.curso_id, division.division,sexo.id, persona.fecha_nacimiento')
				->get()->result_array();

		$escuela_nombre = str_replace("/", " Anexo ", "$escuela->nombre_corto");

		if (!empty($alumnos)) {
			$atributos = array('title' => "Alumnos de $division->curso$division->division - $escuela_nombre");
			$registros = $alumnos;
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
			$sheet->mergeCells('I1:J1');
			$sheet->mergeCells('K1:L1');

			$sheet->getStyle('A1:' . $ultima_columna . '1')->getFont()->setBold(true);

			$sheet->fromArray(array($encabezado), NULL, 'A1');
			$sheet->fromArray($registros, NULL, 'A2');

			$ultima_fila = $sheet->getHighestRow();
			for ($i = 2; $i <= $ultima_fila; $i++) {
				$sheet->getStyle("J$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet->getStyle("L$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			}

			header("Content-Type: application/vnd.ms-excel");
			$nombreArchivo = $atributos['title'];
			header("Content-Disposition: attachment; filename=\"$nombreArchivo.xls\"");
			header("Cache-Control: max-age=0");

			$writer = PHPExcel_IOFactory::createWriter($this->phpexcel, "Excel5");
			$writer->save('php://output');
			exit;
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect('operativo_evaluar/evaluar_operativo/listar_alumnos/' . $division->id, 'refresh');
		}
	}

	public function excel_reporte_divisiones($escuela_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos_escuela, $this->modulos_permitidos) ||
			$escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		if ($this->rol->codigo === ROL_DIR_ESCUELA && ($escuela->dependencia_id !== '2' || $escuela->nivel_id !== '2')) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$campos = array(
			'A' => array('Curso', 15),
			'B' => array('División', 10),
			'C' => array('Turno', 10),
			'D' => array('Carrera', 25),
			'E' => array('Modalidad', 15),
			'F' => array('Fecha de alta', 15),
			'G' => array('Total de Alumnos', 15),
			'H' => array('Evaluaciones Cargadas', 22),
			'I' => array('Alumnos Ausentes', 20),
		);

		$divisiones = $this->db->select("curso.descripcion as curso, division.division, turno.descripcion as turno, carrera.descripcion as carrera, modalidad.descripcion as modalidad, DATE_FORMAT(division.fecha_alta,'%d/%m/%Y'), COUNT(DISTINCT alumno_division.alumno_id) as total_alumnos, SUM(CASE WHEN eo.estado='Presente' THEN 1 ELSE 0 END) as total_cargados, SUM(CASE WHEN eo.estado = 'Ausente' THEN 1 ELSE 0 END) as total_ausentes")
				->from('division')
				->join('carrera', 'carrera.id = division.carrera_id', 'left')
				->join('curso', 'curso.id = division.curso_id')
				->join('escuela', 'escuela.id = division.escuela_id')
				->join('turno', 'turno.id = division.turno_id', 'left')
				->join('modalidad', 'modalidad.id = division.modalidad_id', 'left')
				->join('alumno_division', "alumno_division.division_id = division.id AND COALESCE(alumno_division.fecha_hasta,'2017-12-01')>='2017-12-01'")
				->join('alumno', 'alumno_division.alumno_id = alumno.id')
				->join('evaluar_operativo eo', 'alumno.id = eo.alumno_id', 'left')
				->where('division.escuela_id', $escuela_id)
				->where('alumno_division.ciclo_lectivo', 2017)
				->where("(curso.id = 8 OR (curso.id = 71 AND division.division LIKE '%2%'))")
				->group_by('division.id')
				->get()->result_array();

		$escuela_nombre = str_replace("/", " Anexo ", "$escuela->nombre_corto");
		if (!empty($divisiones)) {
			$this->exportar_excel(array('title' => "2° grados de $escuela_nombre"), $campos, $divisiones);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect('operativo_evaluar/evaluar_operativo/listar_divisiones/' . $escuela->id, 'refresh');
		}
	}

	public function excel_reporte_escuela($escuela_id = NULL) {
		if (!accion_permitida($this->rol, $this->roles_permitidos_escuela, $this->modulos_permitidos) ||
			$escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
		}
		if ($this->rol->codigo === ROL_DIR_ESCUELA && ($escuela->dependencia_id !== '2' || $escuela->nivel_id !== '2')) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$ciclo_lectivo = 2017;

		$campos = array(
			'A' => array('Documento', 15),
			'B' => array('Persona', 35),
			'C' => array('F. Nac', 15),
			'D' => array('F. Ingreso', 15),
			'E' => array('Sexo', 10),
			'F' => array('Curso', 15),
			'G' => array('División.', 15),
			'H' => array('Estado.', 15),
			'I' => array('Comprensión y producción del texto', 17),
			'J' => array('', 17),
			'K' => array('Solución de problemas', 12),
			'L' => array('', 12),
			'M' => array('Nivel de desempeño', 20)
		);

		$alumnos = $this->db->select("CONCAT(documento_tipo.descripcion_corta,' ', persona.documento) as documento, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, DATE_FORMAT(persona.fecha_nacimiento,'%d/%m/%Y'), DATE_FORMAT(alumno_division.fecha_desde,'%d/%m/%Y'), COALESCE(CONCAT(CASE WHEN sexo.id=1 THEN 'M' WHEN sexo.id=2 THEN 'F' ELSE '' END), ' ') as sexo,  curso.descripcion as curso, CASE curso.grado_multiple WHEN 'Si' THEN CONCAT(division.division, ' (', COALESCE(c_gm.descripcion, ''), ')') ELSE division.division END as division, eo.estado, CASE WHEN eo.estado='Ausente' THEN '' ELSE (eo.puntuacion_1 + eo.puntuacion_2 + eo.puntuacion_3 + eo.puntuacion_4 + eo.puntuacion_5 + eo.puntuacion_6a + eo.puntuacion_6b + eo.puntuacion_7 + eo.puntuacion_8 + eo.puntuacion_9 + eo.puntuacion_11a + eo.puntuacion_11b + eo.puntuacion_11c) END as puntuacion_1, CASE WHEN eo.estado='Ausente' THEN '' ELSE (eo.puntuacion_1 + eo.puntuacion_2 + eo.puntuacion_3 + eo.puntuacion_4 + eo.puntuacion_5 + eo.puntuacion_6a + eo.puntuacion_6b + eo.puntuacion_7 + eo.puntuacion_8 + eo.puntuacion_9 + eo.puntuacion_11a + eo.puntuacion_11b + eo.puntuacion_11c)/18 END, CASE WHEN eo.estado='Ausente' THEN '' ELSE (eo.puntuacion_10a + eo.puntuacion_10b) END as puntuacion_2, CASE WHEN eo.estado='Ausente' THEN '' ELSE(eo.puntuacion_10a + eo.puntuacion_10b)/7 END, CASE WHEN eo.estado='Ausente' THEN '' ELSE (((eo.puntuacion_1 + eo.puntuacion_2 + eo.puntuacion_3 + eo.puntuacion_4 + eo.puntuacion_5 + eo.puntuacion_6a + eo.puntuacion_6b + eo.puntuacion_7 + eo.puntuacion_8 + eo.puntuacion_9 + eo.puntuacion_11a + eo.puntuacion_11b + eo.puntuacion_11c) + (eo.puntuacion_10a + eo.puntuacion_10b)) * 10 / 25) END as nivel_desempeño")
				->from('alumno')
				->join('persona', 'persona.id = alumno.persona_id')
				->join('sexo', 'sexo.id = persona.sexo_id', 'left')
				->join('alumno_division', 'alumno_division.alumno_id = alumno.id')
				->join('division', 'division.id = alumno_division.division_id')
				->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id')
				->join('curso', 'division.curso_id = curso.id')
				->join('curso c_gm', 'alumno_division.curso_id = c_gm.id', 'left')
				->join('evaluar_operativo eo', 'alumno.id = eo.alumno_id', 'left')
				->where('division.escuela_id', $escuela_id)
				->where("COALESCE(alumno_division.fecha_hasta,'2017-12-01')>='2017-12-01'")
				->where('ciclo_lectivo', $ciclo_lectivo)
				->order_by('division.curso_id, division.division,sexo.id, persona.fecha_nacimiento')
				->get()->result_array();

		if (!empty($alumnos)) {
			$atributos = array('title' => "Alumnos de $escuela->nombre_largo");
			$registros = $alumnos;
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
			$sheet->mergeCells('I1:J1');
			$sheet->mergeCells('K1:L1');

			$sheet->getStyle('A1:' . $ultima_columna . '1')->getFont()->setBold(true);

			$sheet->fromArray(array($encabezado), NULL, 'A1');
			$sheet->fromArray($registros, NULL, 'A2');
			$ultima_fila = $sheet->getHighestRow();
			for ($i = 2; $i <= $ultima_fila; $i++) {
				$sheet->getStyle("J$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet->getStyle("L$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			}
			header("Content-Type: application/vnd.ms-excel");
			$nombreArchivo = $atributos['title'];
			header("Content-Disposition: attachment; filename=\"$nombreArchivo.xls\"");
			header("Cache-Control: max-age=0");

			$writer = PHPExcel_IOFactory::createWriter($this->phpexcel, "Excel5");
			$writer->save('php://output');
			exit;
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect('operativo_evaluar/evaluar_operativo/listar_alumnos/' . $division->id, 'refresh');
		}
	}

	public function reporte_final() {
		if (!accion_permitida($this->rol, $this->roles_permitidos_escuela, $this->modulos_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$ciclo_lectivo = 2017;

		$campos = array(
			'A' => array('Numero', 10),
			'B' => array('Escuela', 35),
			'C' => array('Anexo', 10),
			'D' => array('Porcentaje de carga', 15),
			'E' => array('Evaluaciones cargadas', 10),
			'F' => array('Total de alumnos escuela', 15),
			'G' => array('Total de presentes', 15),
			'H' => array('Total de ausentes', 15),
			'I' => array('Divisiones', 15),
			'J' => array('Comprensión y producción del texto', 25),
			'K' => array('Solución de problemas', 25),
		);

		$reporte_general = $this->db->select("e.numero,  e.nombre,e.anexo, (count(DISTINCT eo.id))/count(DISTINCT ad.id) as porcentaje_cargado,count(distinct eo.id) as evaluaciones_cargadas,count(DISTINCT ad.id) as alumnos_escuela,sum(CASE WHEN eo.estado='Presente' THEN 1 ELSE 0 END) as presentes,sum(CASE WHEN eo.estado='Ausente' THEN 1 ELSE 0 END) as ausentes,count(distinct d.id) as divisiones,
(sum(CASE WHEN eo.estado='Ausente' THEN 0 ELSE (eo.puntuacion_1 + eo.puntuacion_2 + eo.puntuacion_3 + eo.puntuacion_4 + eo.puntuacion_5 + eo.puntuacion_6a + eo.puntuacion_6b + eo.puntuacion_7 + eo.puntuacion_8 + eo.puntuacion_9 + eo.puntuacion_11a + eo.puntuacion_11b + eo.puntuacion_11c)END))/(18*count(distinct eo.alumno_division_id)) as comprension_lectora,
(sum(CASE WHEN eo.estado='Ausente' THEN 0 ELSE (eo.puntuacion_10a + eo.puntuacion_10b) END))/(7*count(distinct eo.alumno_division_id)) as solucion_problemas")
				->from('alumno_division ad')
				->join('evaluar_operativo eo', 'ad.id = eo.alumno_division_id', 'left')
				->join('division d', 'ad.division_id = d.id')
				->join('curso c', "c.id = d.curso_id AND (c.id = 8 OR (c.id = 71 AND d.division LIKE '%2%'))")
				->join('escuela e', 'e.id = d.escuela_id')
				->join('nivel n', 'e.nivel_id = n.id AND e.nivel_id=2')
				->where("ad.ciclo_lectivo", $ciclo_lectivo)
				->where(" COALESCE(ad.fecha_hasta,'2017-12-01') >= '2017-12-01'")
				->order_by('e.numero')
				->group_by('e.id, e.anexo')
				->get()->result_array();

		$campos_2 = array(
			'A' => array('Numero', 10),
			'B' => array('Escuela', 35),
			'C' => array('Anexo', 10),
			'D' => array('Porcentaje de carga', 20),
			'E' => array('Evaluaciones cargadas', 20),
			'F' => array('Total de alumnos escuela', 20),
			'G' => array('Total de presentes', 20),
			'H' => array('Total de ausentes', 20),
			'I' => array('Divisiones', 15),
			'J' => array('1- Reconoce clase textual', 15),
			'K' => array('2- Reconoce información explícita', 15),
			'L' => array('3- Reconoce información explícita', 15),
			'M' => array('4- Reconoce información explícita', 15),
			'N' => array('5- Reconoce información explícita', 15),
			'O' => array('6a- Reconoce tipo de grafías', 15),
			'P' => array('6b- Conexión fonema grafema', 15),
			'Q' => array('7- Identifica la palabra más larga', 15),
			'R' => array('8- Traducción dibujo - palabra', 15),
			'S' => array('9- Traducción letra - número', 15),
			'T' => array('10a- Identifica datos e incógnitas', 15),
			'U' => array('10b- Compara procedimientos usados para resolver problemas y determina procedimientos más económicos para la obtención de un resultado concreto', 15),
			'V' => array('11a -Traducción idea - palabra', 15),
			'W' => array('11b- Dominio de unidades escritas', 15),
			'X' => array('11c- Distribución gráfico espacial', 15)
		);
		$reporte_detalle_item = $this->db
				->select("e.numero, e.nombre, e.anexo, (count(DISTINCT eo.id))/count(DISTINCT ad.id) as porcentaje_cargado,
					 count(distinct eo.id) as evaluaciones_cargadas, count(DISTINCT ad.id) as alumnos_escuela, 
					 sum(CASE WHEN eo.estado='Presente' THEN 1 ELSE 0 END) as presentes, sum(CASE WHEN eo.estado='Ausente' THEN 1 ELSE 0 END) as ausentes, 
					 count(distinct d.id) as divisiones, 
					 sum(eo.puntuacion_1)/(1*count(distinct eo.alumno_division_id)) as puntuacion_1, 
					 sum(eo.puntuacion_2)/(1*count(distinct eo.alumno_division_id)) as puntuacion_2, 
					 sum(eo.puntuacion_3)/(1*count(distinct eo.alumno_division_id)) as puntuacion_3, 
					 sum(eo.puntuacion_4)/(1*count(distinct eo.alumno_division_id)) as puntuacion_4, 
					 sum(eo.puntuacion_5)/(1*count(distinct eo.alumno_division_id)) as puntuacion_5, 
					 sum(eo.puntuacion_6a)/(1*count(distinct eo.alumno_division_id)) as puntuacion_6a, 
					 sum(eo.puntuacion_6b)/(1*count(distinct eo.alumno_division_id)) as puntuacion_6b,
					 sum(eo.puntuacion_7)/(1*count(distinct eo.alumno_division_id)) as puntuacion_7, 
					 sum(eo.puntuacion_8)/(1*count(distinct eo.alumno_division_id)) as puntuacion_8, 
					 sum(eo.puntuacion_9)/(2*count(distinct eo.alumno_division_id)) as puntuacion_9,
					 sum(eo.puntuacion_10a)/(3*count(distinct eo.alumno_division_id)) as puntuacion_10a, 
					 sum(eo.puntuacion_10b)/(4*count(distinct eo.alumno_division_id)) as puntuacion_10b,
					 sum(eo.puntuacion_11a)/(2*count(distinct eo.alumno_division_id)) as puntuacion_11a, 
					 sum(eo.puntuacion_11b)/(3*count(distinct eo.alumno_division_id)) as puntuacion_11b, 
					 sum(eo.puntuacion_11c)/(2*count(distinct eo.alumno_division_id)) as puntuacion_11c")
				->from('alumno_division ad')
				->join('evaluar_operativo eo', 'ad.id = eo.alumno_division_id', 'left')
				->join('division d', 'ad.division_id = d.id AND d.fecha_baja IS NULL')
				->join('curso c', "c.id = d.curso_id AND (c.id = 8 OR (c.id = 71 AND d.division LIKE '%2%'))")
				->join('escuela e', 'e.id = d.escuela_id')
				->join('nivel n', 'e.nivel_id = n.id AND e.nivel_id=2')
				->where("ad.ciclo_lectivo", $ciclo_lectivo)
				->where(" COALESCE(ad.fecha_hasta,'2017-12-01') >= '2017-12-01'")
				->order_by('e.numero')
				->group_by('e.id, e.anexo')
				->get()->result_array();

		if (!empty($reporte_general) && !empty($reporte_detalle_item)) {
			$atributos = array('title' => "Operativo Leer y escribir 2017");
			$registros = $reporte_general;
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
			$ultima_fila = $sheet->getHighestRow();
			for ($i = 2; $i <= $ultima_fila; $i++) {
				$sheet->getStyle("D$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet->getStyle("J$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet->getStyle("K$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			}

			//Bloque de pagina 2
			$this->phpexcel->createSheet();
			$this->phpexcel->setActiveSheetIndex(1);
			$registros_2 = $reporte_detalle_item;
			$sheet_2 = $this->phpexcel->getActiveSheet();
			$sheet_2->setTitle('Name of Sheet 1');
			$sheet_2->setTitle('Detalle de por item');
			$encabezado_2 = array();
			$ultima_columna_2 = 'A';
			foreach ($campos_2 as $columna => $campo) {
				$encabezado_2[] = $campo[0];
				$sheet_2->getColumnDimension($columna)->setWidth($campo[1]);
				$ultima_columna_2 = $columna;
			}
			$sheet_2->getStyle('A1:' . $ultima_columna_2 . '1')->getFont()->setBold(true);
			$sheet_2->fromArray(array($encabezado_2), NULL, 'A1');
			$sheet_2->fromArray($registros_2, NULL, 'A2');
			$ultima_fila_2 = $sheet_2->getHighestRow();
			for ($i = 2; $i <= $ultima_fila_2; $i++) {
				$sheet_2->getStyle("D$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet_2->getStyle("J$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet_2->getStyle("K$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet_2->getStyle("L$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet_2->getStyle("M$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet_2->getStyle("N$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet_2->getStyle("O$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet_2->getStyle("P$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet_2->getStyle("Q$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet_2->getStyle("R$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet_2->getStyle("S$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet_2->getStyle("T$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet_2->getStyle("U$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet_2->getStyle("V$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet_2->getStyle("W$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
				$sheet_2->getStyle("X$i")->getNumberFormat()->applyFromArray(array(
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			}
			//Fin bloque

			header("Content-Type: application/vnd.ms-excel");
			$nombreArchivo = $atributos['title'];
			header("Content-Disposition: attachment; filename=\"$nombreArchivo.xls\"");
			header("Cache-Control: max-age=0");

			$writer = PHPExcel_IOFactory::createWriter($this->phpexcel, "Excel5");
			$writer->save('php://output');
			exit;
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect('operativo_evaluar/listar_escuelas', 'refresh');
		}
	}
}
/* End of file Evaluar_operativo.php */
/* Location: ./application/controllers/Evaluar_operativo.php */