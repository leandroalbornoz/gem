<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escuela_carrera extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->load->model('escuela_carrera_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL,ROL_ASISTENCIA_DIVISION,ROL_DOCENTE_CURSADA));
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL,  ROL_GRUPO_ESCUELA_CONSULTA, ROL_ESCUELA_ALUM, ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'menu/escuela_carrera';
	}

	public function listar($escuela_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$tableData = array(
			'columns' => array(
				array('label' => 'Carrera', 'data' => 'carrera', 'width' => 88),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'escuela_carrera_table',
			'source_url' => "escuela_carrera/listar_data/$escuela_id"
		);

		$data['escuela'] = $escuela;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Carreras de escuelas';
		$this->load_template('escuela_carrera/escuela_carrera_listar', $data);
	}

	public function listar_data($escuela_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->datatables
			->select('escuela_carrera.id, escuela_carrera.escuela_id, escuela_carrera.carrera_id, carrera.descripcion as carrera, escuela.nombre as escuela')
			->unset_column('id')
			->from('escuela_carrera')
			->join('carrera', 'carrera.id = escuela_carrera.carrera_id', 'left')
			->join('escuela', 'escuela.id = escuela_carrera.escuela_id', 'left')
			->where('escuela_carrera.escuela_id', $escuela_id);
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="carrera/ver/$2/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
				. '<ul class="dropdown-menu dropdown-menu-right">'
				. '<li><a href="escuela_carrera/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Eliminar"><i class="fa fa-remove"></i> Eliminar</a></li>'
				. '</ul></div>', 'id, carrera_id');
		} else {
			$this->datatables->add_column('edit', '<a class="btn btn-xs btn-default" href="carrera/ver/$2/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id, carrera_id');
		}

		echo $this->datatables->generate();
	}

	public function modal_agregar($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("escuela_carrera/listar/$escuela_id");
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$this->load->model('carrera_model');
		$this->array_carrera_control = $array_carrera = $this->get_array('carrera', 'descripcion', 'id', array(
			'join' => array(array('type' => 'left', 'table' => 'escuela_carrera', 'where' => 'carrera.id = escuela_carrera.carrera_id AND escuela_carrera.escuela_id = ' . $escuela->id)),
			'where' => array('escuela_carrera.carrera_id IS NULL', 'carrera.fecha_hasta IS NULL'),
			'nivel_id' => $escuela->nivel_id
			), array('' => '-- Seleccionar carrera --'));
		$this->set_model_validation_rules($this->escuela_carrera_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($escuela_id !== $this->input->post('escuela_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->escuela_carrera_model->create(array(
					'escuela_id' => $escuela_id,
					'carrera_id' => $this->input->post('carrera')
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->escuela_carrera_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->escuela_carrera_model->get_error());
				}
				redirect("escuela_carrera/listar/$escuela->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("escuela_carrera/listar/$escuela->id", 'refresh');
			}
		}

		$this->escuela_carrera_model->fields['escuela']['value'] = "Esc. $escuela->nombre_largo";
		$this->escuela_carrera_model->fields['carrera']['array'] = $array_carrera;
		$data['fields'] = $this->build_fields($this->escuela_carrera_model->fields);
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar carrera';
		$this->load->view('escuela_carrera/escuela_carrera_modal_agregar', $data);
	}

	public function modal_eliminar($id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("escuela_carrera/listar/$escuela_id");
		}

		$this->load->model('escuela_carrera_model');
		$escuela_carrera = $this->escuela_carrera_model->get_one($id);
		if (empty($escuela_carrera)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}

		$escuela = $this->escuela_model->get_one($escuela_carrera->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('escuela_carrera_id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->escuela_carrera_model->delete(array('id' => $id));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->escuela_carrera_model->get_msg());
				redirect("escuela_carrera/listar/$escuela_carrera->escuela_id", 'refresh');
			}
		}
		$escuela_carrera->escuela = $escuela->nombre_largo;
		$data['fields'] = $this->build_fields($this->escuela_carrera_model->fields, $escuela_carrera, TRUE);

		$data['escuela_carrera_id'] = $id;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
		$data['title'] = 'Eliminar carrera';
		$this->load->view('escuela_carrera/escuela_carrera_modal_eliminar', $data);
	}

	public function escuelas($carrera_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('carrera_model');
		$carrera = $this->carrera_model->get_one($carrera_id);
		if (empty($carrera)) {
			show_error('No se encontró el registro de supervisión', 500, 'Registro no encontrado');
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
			'source_url' => "escuela_carrera/escuelas_data/$carrera_id/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'reuse_var' => TRUE,
			'initComplete' => "complete_escuela_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['carrera'] = $carrera;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Escuelas Supervisión ' . $carrera->descripcion;
		$this->load_template('escuela_carrera/escuela_carrera_listar_escuela', $data);
	}

	public function escuelas_data($carrera_id = NULL, $rol_codigo = '', $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $carrera_id == NULL || !ctype_digit($carrera_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$this->load->model('carrera_model');
		$carrera = $this->carrera_model->get_one($carrera_id);
		if (empty($carrera)) {
			show_error('No se encontró el registro de supervisión', 500, 'Registro no encontrado');
		}

		$this->datatables
			->select('escuela.id, escuela.numero, escuela.anexo, escuela.cue, escuela.subcue, escuela.nombre, escuela.calle, escuela.calle_numero, escuela.departamento, escuela.piso, escuela.barrio, escuela.manzana, escuela.casa, escuela.localidad_id, escuela.nivel_id, escuela.reparticion_id, escuela.supervision_id, escuela.regional_id, escuela.dependencia_id, escuela.zona_id, escuela.fecha_creacion, escuela.anio_resolucion, escuela.numero_resolucion, escuela.telefono, escuela.email, escuela.fecha_cierre, escuela.anio_resolucion_cierre, escuela.numero_resolucion_cierre, dependencia.descripcion as dependencia, nivel.descripcion as nivel, regional.descripcion as regional, CONCAT(jurisdiccion.codigo,\'/\',reparticion.codigo) as jurirepa, supervision.nombre as supervision, zona.descripcion as zona')
			->unset_column('id')
			->from('carrera')
			->join('escuela_carrera', 'escuela_carrera.carrera_id = carrera.id', 'left')
			->join('escuela', 'escuela.id = escuela_carrera.escuela_id', 'left')
			->join('dependencia', 'dependencia.id = escuela.dependencia_id', 'left')
			->join('nivel', 'nivel.id = escuela.nivel_id', 'left')
			->join('regional', 'regional.id = escuela.regional_id', 'left')
			->join('reparticion', 'reparticion.id = escuela.reparticion_id', 'left')
			->join('jurisdiccion', 'jurisdiccion.id = reparticion.jurisdiccion_id ', 'left')
			->join('supervision', 'supervision.id = escuela.supervision_id', 'left')
			->join('zona', 'zona.id = escuela.zona_id', 'left')
			->where('escuela_carrera.carrera_id', $carrera_id);
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
			case ROL_GRUPO_ESCUELA:
				$this->datatables
					->join('escuela_grupo_escuela', 'escuela_grupo_escuela.escuela_id = escuela.id', 'left')
					->join('escuela_grupo', 'escuela_grupo_escuela.escuela_grupo_id = escuela_grupo.id', 'left')
					->where('escuela_grupo.id', $this->rol->entidad_id);
				break;
			case ROL_LINEA:
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

	public function espacios_curriculares($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('carrera_model');
		$carrera = $this->carrera_model->get_one($id);
		if (empty($carrera)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$data['error'] = $this->session->flashdata('error');

		$data['fields'] = $this->build_fields($this->carrera_model->fields, $carrera, TRUE);

		$data['carrera'] = $carrera;
		$this->load->model('curso_model');
		$cursos = $this->curso_model->get(array('nivel_id' => $carrera->nivel_id, 'sort_by' => 'id'));
		$this->load->model('espacio_curricular_model');
		$espacios_curriculares = $this->espacio_curricular_model->get(array('carrera_id' => $carrera->id,
			'join' => array(
				array('table' => 'materia', 'where' => 'materia.id=espacio_curricular.materia_id', 'columnas' => array('materia.descripcion as materia'))
			),
			'sort_by' => 'cuatrimestre, materia.descripcion'
		));

		foreach ($cursos as $curso) {
			$curso->materias = array();
			$array_cursos[$curso->id] = $curso;
		}
		if (!empty($espacios_curriculares)) {
			foreach ($espacios_curriculares as $espacio_curricular) {
				$espacio_curricular->cuatrimestre = $this->espacio_curricular_model->fields['cuatrimestre']['array'][$espacio_curricular->cuatrimestre];
				if (empty($espacio_curricular->grupo_id)) {
					$array_cursos[$espacio_curricular->curso_id]->materias[] = $espacio_curricular;
				} else {
					$array_cursos[$espacio_curricular->curso_id]->materias_grupo[$espacio_curricular->grupo_id][] = $espacio_curricular;
				}
			}
		}

		$data['cursos'] = $array_cursos;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');

		$data['title'] = TITLE . ' - Ver carrera';
		$this->load_template('escuela_carrera/escuela_carrera_espacio_curricular', $data);
	}
}
/* End of file Escuela_carrera.php */
/* Location: ./application/controllers/Escuela_carrera.php */