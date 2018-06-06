<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Portal extends MY_Controller {

	function __construct() {
		parent::__construct();
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('ingreso/feria_model');
		$this->load->model('escuela_model');
		$this->load->model('feria_model');
		$this->roles_permitidos = array(ROL_ADMIN/* , ROL_INGRESO */);
		$this->nav_route = 'ingreso/ingreso';
	}

	public function escritorio() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}


		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Bienvenido a la Feria Educativa Virtual';
		$this->load_template('ingreso/portal/portal_escritorio', $data);
	}

	public function instructivos() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}


		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Feria Educativa Virtual - Instructivos';
		$this->load_template('ingreso/portal/portal_instructivos', $data);
	}

	public function resultado() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}


		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Feria Educativa Virtual - Resultado';
		$this->load_template('ingreso/portal/portal_resultado', $data);
	}

	public function listar_escuelas() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Número', 'data' => 'numero', 'width' => 5),
				array('label' => 'Anexo', 'data' => 'anexo', 'class' => 'dt-body-right', 'width' => 5),
				array('label' => 'CUE', 'data' => 'cue', 'class' => 'dt-body-right', 'width' => 5),
				array('label' => 'Nombre', 'data' => 'nombre', 'width' => 20),
				array('label' => 'Nivel', 'data' => 'nivel', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'Supervisión', 'data' => 'supervision', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Localidad', 'data' => 'localidad', 'width' => 13, 'class' => 'text-sm'),
				array('label' => 'Delegación', 'data' => 'delegacion', 'width' => 8, 'class' => 'text-sm'),
				array('label' => 'Teléfono', 'data' => 'telefono', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Email', 'data' => 'email', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'edit', 'width' => 7, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'escuela_table',
			'source_url' => "ingreso/portal/listar_data/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'reuse_var' => TRUE,
			'initComplete' => "complete_escuela_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Feria Educativa Virtual - Escuelas';
		$this->load_template('ingreso/portal/portal_escuela_listar', $data);
	}

	public function listar_data($rol_codigo, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select("escuela.id, escuela.numero, escuela.anexo, escuela.cue, escuela.nombre, escuela.calle, escuela.calle_numero, escuela.departamento, escuela.piso, escuela.barrio, escuela.manzana, escuela.casa, escuela.localidad_id, escuela.nivel_id, escuela.telefono, escuela.email, nivel.descripcion as nivel, delegacion.descripcion as delegacion, CONCAT(departamento.descripcion,' - ', localidad.descripcion) as localidad, supervision.nombre as supervision")
			->unset_column('id')
			->from('feria')
			->join('escuela', 'escuela.id = feria.escuela_id')
			->join('nivel', 'nivel.id = escuela.nivel_id')
			->join('localidad', 'escuela.localidad_id = localidad.id', 'left')
			->join('departamento', 'localidad.departamento_id = departamento.id', 'left')
			->join('delegacion', 'delegacion.id = escuela.delegacion_id', 'left')
			->join('reparticion', 'reparticion.id = escuela.reparticion_id', 'left')
			->join('jurisdiccion', 'jurisdiccion.id = reparticion.jurisdiccion_id', 'left')
			->join('supervision', 'supervision.id = escuela.supervision_id', 'left')
			->join('zona', 'zona.id = escuela.zona_id', 'left')
			->where('escuela.dependencia_id', '1')
			->where('escuela.escuela_activa', 'Si');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="ingreso/portal/escuela/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '</div>', 'id');
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
				break;
			case ROL_DIR_ESCUELA:
			case ROL_SDIR_ESCUELA:
			case ROL_SEC_ESCUELA:
				$this->datatables->where('escuela.id', $this->rol->entidad_id);
				break;
			case ROL_SUPERVISION:
				$this->datatables->where('supervision.id', $this->rol->entidad_id);
				break;
			case ROL_PRIVADA:
				$this->datatables->where('dependencia_id', 2);
				break;
			case ROL_SEOS:
				$this->datatables->where('dependencia_id', 3);
				break;
			case ROL_REGIONAL:
				$this->datatables->where('regional.id', $this->rol->entidad_id);
				break;
			case ROL_ADMIN:
			case ROL_USI:
			case ROL_JEFE_LIQUIDACION:
			case ROL_LIQUIDACION:
			case ROL_CONSULTA:
				break;
			default:
				echo '';
				return;
		}
		echo $this->datatables->generate();
	}

	public function escuela($escuela_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$this->rol->escuela_id = $escuela->id;
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$feria = $this->feria_model->get(array(
			'escuela_id' => $escuela->id
		));
		if (empty($feria)) {
			show_error('No se encontró el registro de feria', 500, 'Registro no encontrado');
		}

		$this->load->model('ingreso/video_model');
		$videos = $this->video_model->get(array(
			'escuela_id' => $escuela->id
		));

		$this->load->model('ingreso/video_model');
		$this->load->model('ingreso/texto_model');
		$this->load->model('ingreso/imagen_model');
		$this->load->model('feria_escuela_especialidad_model');
		$lista_video = $this->video_model->get(array(
			'feria_id' => $feria[0]->id
		));
		$lista_texto = $this->texto_model->get(array(
			'feria_id' => $feria[0]->id
		));
		$lista_imagen = $this->imagen_model->get(array(
			'feria_id' => $feria[0]->id
		));
		$lista_areas = $this->feria_escuela_especialidad_model->get_escuela_areas($escuela->id);


		$data['css'][] = 'plugins/prettyPhoto/css/prettyPhoto.css';
		$data['js'][] = 'plugins/prettyPhoto/js/jquery.prettyPhoto.js';
//		$data['js'][] = 'plugins/prettyPhoto/js/jquery-1.6.1.min.js';
		$data['lista_video'] = $lista_video;
		$data['lista_imagen'] = $lista_imagen;
		$data['lista_texto'] = $lista_texto;
		$data['lista_areas'] = $lista_areas;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->escuela_model->fields, $escuela, TRUE);
		$data['escuela'] = $escuela;
		$data['videos'] = $videos;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'escritorio' => '', 'ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver escuela feria';
		$this->load_template('ingreso/portal/portal_escuela', $data);
	}
}