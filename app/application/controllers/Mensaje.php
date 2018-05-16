<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mensaje extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('mensaje_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL));
		$this->roles_mensaje_masivo = array(ROL_ADMIN, ROL_LINEA, ROL_CONSULTA_LINEA, ROL_LIQUIDACION, ROL_SUPERVISION, ROL_REGIONAL, ROL_SEOS, ROL_PRIVADA, ROL_USI);
		$this->roles_destinatarios = array(ROL_DIR_ESCUELA, ROL_LINEA, ROL_SUPERVISION, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_AREA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_ESCUELA_ALUM, ROL_ESCUELA_CAR);
		$this->roles_derivar = array(ROL_ADMIN,ROL_USI);

		$this->nav_route = 'menu/mensaje';
	}

	public function index() {
		return $this->bandeja();
	}

	public function respondedor() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$data['mensajes'] = $this->mensaje_model->get_mensajes($this->usuario, $this->rol);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Bandeja de mensajes';
		$this->load_template('mensaje/mensaje_respondedor', $data);
	}

	public function bandeja() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$data['mensajes'] = $this->mensaje_model->get_mensajes($this->usuario, $this->rol);
		$data['responder_mensaje'] = $this->session->flashdata('responder_mensaje');
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Bandeja de mensajes';
		$this->load_template('mensaje/mensaje_bandeja', $data);
	}

	public function leidos() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData_rol = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 5),
				array('label' => 'Remitente', 'data' => 'de', 'width' => 36),
				array('label' => 'Fecha', 'data' => 'fecha', 'render' => 'datetime', 'width' => 10),
				array('label' => 'Asunto', 'data' => 'asunto', 'width' => 37),
				array('label' => 'Mensaje', 'data' => 'mensaje', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Destinatario', 'data' => 'para', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Leído', 'data' => 'leido_fecha', 'width' => 5, 'render' => 'datetime', 'responsive_class' => 'none'),
				array('label' => 'Leído por', 'data' => 'leido_usuario', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'menu', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'mensaje_rol_table',
			'source_url' => 'mensaje/listar_data/leidos_rol',
			'reuse_var' => TRUE,
			'initComplete' => "complete_mensaje_rol_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-xl-4.col-lg-12"l><"col-xl-8.col-lg-12"p>>rt<"row"<"col-xl-4.col-lg-12"i><"col-xl-8.col-lg-12"p>>',
			'order' => array(array(2, 'desc'))
		);
		$data['html_table_rol'] = buildHTML($tableData_rol);
		$data['js_table_rol'] = buildJS($tableData_rol);
		$tableData_usuario = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 5),
				array('label' => 'Remitente', 'data' => 'de', 'width' => 36),
				array('label' => 'Fecha', 'data' => 'fecha', 'render' => 'datetime', 'width' => 10),
				array('label' => 'Asunto', 'data' => 'asunto', 'width' => 37),
				array('label' => 'Mensaje', 'data' => 'mensaje', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Destinatario', 'data' => 'para', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Leído', 'data' => 'leido_fecha', 'width' => 5, 'render' => 'datetime', 'responsive_class' => 'none'),
				array('label' => 'Leído por', 'data' => 'leido_usuario', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'menu', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'mensaje_usuario_table',
			'source_url' => 'mensaje/listar_data/leidos_usuario',
			'reuse_var' => TRUE,
			'initComplete' => "complete_mensaje_usuario_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-xl-4.col-lg-12"l><"col-xl-8.col-lg-12"p>>rt<"row"<"col-xl-4.col-lg-12"i><"col-xl-8.col-lg-12"p>>',
			'order' => array(array(2, 'desc'))
		);
		$data['html_table_usuario'] = buildHTML($tableData_usuario);
		$data['js_table_usuario'] = buildJS($tableData_usuario);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Mensajes ya leídos';
		$this->load_template('mensaje/mensaje_leidos', $data);
	}

	public function enviados() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 5),
				array('label' => 'Remitente', 'data' => 'de', 'width' => 20),
				array('label' => 'Fecha', 'data' => 'fecha', 'render' => 'datetime', 'width' => 10),
				array('label' => 'Asunto', 'data' => 'asunto', 'width' => 29),
				array('label' => 'Mensaje', 'data' => 'mensaje', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Destinatario', 'data' => 'para', 'width' => 20),
				array('label' => 'Leído', 'data' => 'leido', 'width' => 5),
				array('label' => 'Fecha lectura/resolución', 'data' => 'leido_fecha', 'width' => 5, 'render' => 'datetime', 'responsive_class' => 'none'),
				array('label' => 'Usuario lectura/resolución', 'data' => 'leido_usuario', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'menu', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'mensaje_table',
			'source_url' => 'mensaje/listar_data/enviados',
			'reuse_var' => TRUE,
			'initComplete' => "complete_mensaje_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>',
			'order' => array(array(2, 'desc'))
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Mensajes enviados';
		$this->load_template('mensaje/mensaje_enviados', $data);
	}

	public function listar_data($tipo = 'leidos_usuario') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select("mensaje.id, mensaje.fecha, mensaje.asunto, mensaje.mensaje, CONCAT(d_p.apellido, ', ', d_p.nombre, '<br>', d_r.nombre, COALESCE(CONCAT(' ', d_e.nombre), '')) as de, COALESCE(CONCAT(p_p.apellido, ', ', p_p.nombre), CONCAT(p_r.nombre, COALESCE(CONCAT(' ', p_e.nombre), ''))) as para, mensaje.leido_fecha, CONCAT(l_p.apellido, ', ', l_p.nombre) as leido_usuario, (CASE WHEN mensaje.leido_fecha IS NULL THEN 0 ELSE 1 END) as leido")
			->unset_column('id')
			->from('mensaje')
			->join('usuario d_u', 'd_u.id = mensaje.de_usuario_id')
			->join('usuario_persona d_up', 'd_u.id = d_up.usuario_id')
			->join('persona d_p', 'd_up.cuil = d_p.cuil', 'left')
			->join('rol d_r', 'd_r.id = mensaje.de_rol_id')
			->join('entidad_tipo d_et', 'd_r.entidad_tipo_id = d_et.id', 'left')
			->join('entidad d_e', 'd_e.id = mensaje.de_entidad_id AND d_et.tabla = d_e.tabla', 'left')
			->join('usuario p_u', 'p_u.id = mensaje.para_usuario_id', 'left')
			->join('usuario_persona p_up', 'p_u.id = p_up.usuario_id', 'left')
			->join('persona p_p', 'p_up.cuil = p_p.cuil', 'left')
			->join('rol p_r', 'p_r.id = mensaje.para_rol_id', 'left')
			->join('entidad_tipo p_et', 'p_r.entidad_tipo_id = p_et.id', 'left')
			->join('entidad p_e', 'p_e.id = mensaje.para_entidad_id AND p_et.tabla = p_e.tabla', 'left')
			->join('usuario l_u', 'l_u.id = mensaje.leido_usuario_id', 'left')
			->join('usuario_persona l_up', 'l_u.id = l_up.usuario_id', 'left')
			->join('persona l_p', 'l_up.cuil = l_p.cuil', 'left')
			->add_column('', '')
			->add_column('leido', '$1', 'dt_column_mensaje_leido(leido, leido_fecha, leido_usuario)')
			->add_column('menu', '<a class="btn btn-xs btn-default" href="mensaje/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>', 'id');
		if ($tipo === 'leidos_rol') {
			$this->datatables->where('mensaje.leido_fecha IS NOT NULL');
			$this->datatables->where('p_r.codigo', $this->rol->codigo);
			$this->datatables->where('para_entidad_id', $this->rol->entidad_id);
		} elseif ($tipo === 'leidos_usuario') {
			$this->datatables->where('mensaje.leido_fecha IS NOT NULL');
			$this->datatables->where('para_usuario_id', $this->usuario);
		} else {
			$this->datatables->where('mensaje.de_usuario_id', $this->usuario);
		}
		echo $this->datatables->generate();
	}

	public function mensajes_difusion() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData_difusion = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 2),
				array('label' => 'Remitente', 'data' => 'de', 'width' => 20),
				array('label' => 'Fecha', 'data' => 'fecha', 'render' => 'datetime', 'width' => 8),
				array('label' => 'Asunto', 'data' => 'asunto', 'width' => 15),
				array('label' => 'Destinatario', 'data' => 'para', 'width' => 25),
				array('label' => 'Mensaje', 'data' => 'mensaje', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Leído', 'data' => 'leido_fecha', 'width' => 5, 'render' => 'datetime', 'responsive_class' => 'none'),
				array('label' => 'Leído por', 'data' => 'leido_usuario', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'menu', 'width' => 5, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'mensaje_difusion_table',
			'source_url' => 'mensaje/listar_data_difusion',
			'reuse_var' => TRUE,
			'initComplete' => "complete_mensaje_difusion_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-xl-4.col-lg-12"l><"col-xl-8.col-lg-12"p>>rt<"row"<"col-xl-4.col-lg-12"i><"col-xl-8.col-lg-12"p>>',
			'order' => array(array(2, 'desc'))
		);

		if (in_array($this->rol->codigo, $this->roles_mensaje_masivo)) {
			$tableData_difusion_enviados = array(
				'columns' => array(
					array('label' => '', 'data' => '', 'width' => 5),
					array('label' => 'Remitente', 'data' => 'de', 'width' => 20),
					array('label' => 'Fecha', 'data' => 'fecha', 'render' => 'datetime', 'width' => 10),
					array('label' => 'Asunto', 'data' => 'asunto', 'width' => 15),
					array('label' => 'Mensaje', 'data' => 'mensaje', 'width' => 15, 'responsive_class' => 'none'),
					array('label' => 'Destinatario', 'data' => 'para', 'width' => 20),
					array('label' => '', 'data' => 'menu', 'width' => 15, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
				),
				'table_id' => 'mensaje_difusion_enviado_table',
				'source_url' => 'mensaje/listar_data_difusion_enviados',
				'reuse_var' => TRUE,
				'initComplete' => "complete_mensaje_difusion_enviado_table",
				'footer' => TRUE,
				'dom' => '<"row"<"col-xl-4.col-lg-12"l><"col-xl-8.col-lg-12"p>>rt<"row"<"col-xl-4.col-lg-12"i><"col-xl-8.col-lg-12"p>>',
				'order' => array(array(2, 'desc'))
			);
			$data['html_table_difusion_enviados'] = buildHTML($tableData_difusion_enviados);
			$data['js_table_difusion_enviados'] = buildJS($tableData_difusion_enviados);
		}

		$data['html_table_difusion'] = buildHTML($tableData_difusion);
		$data['js_table_difusion'] = buildJS($tableData_difusion);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Bandeja de Mensajes de difusión';
		$this->load_template('mensaje/mensaje_difusion', $data);
	}

	public function listar_data_difusion() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select("mm_d.id, mensaje_masivo.fecha, mensaje_masivo.asunto, mensaje_masivo.mensaje, CONCAT(d_p.apellido, ', ', d_p.nombre, ' - ', d_r.nombre, COALESCE(CONCAT(' ', d_e.nombre), '')) as de, CONCAT(l_p.apellido, ', ', l_p.nombre) as leido_usuario, CONCAT(p_r.nombre, COALESCE(CONCAT(' ', p_e.nombre), '')) as para, mm_d.leido_fecha, (CASE WHEN mm_d.leido_fecha IS NULL THEN 0 ELSE 1 END) as leido")
			->unset_column('id')
			->from('mensaje_masivo')
			->join('mensaje_masivo_destinatario mm_d', 'mensaje_masivo.id = mm_d.mensaje_masivo_id')
			->join('usuario d_u', 'd_u.id = mensaje_masivo.de_usuario_id', 'left')
			->join('usuario_persona d_up', 'd_u.id = d_up.usuario_id', 'left')
			->join('persona d_p', 'd_up.cuil = d_p.cuil', 'left')
			->join('rol d_r', 'd_r.id = mensaje_masivo.de_rol_id', 'left')
			->join('entidad_tipo d_et', 'd_r.entidad_tipo_id = d_et.id', 'left')
			->join('entidad d_e', 'd_e.id = mensaje_masivo.de_entidad_id AND d_et.tabla = d_e.tabla', 'left')
			->join('rol p_r', 'p_r.id = mensaje_masivo.para_rol_id', 'left')
			->join('entidad_tipo p_et', 'p_r.entidad_tipo_id = p_et.id', 'left')
			->join('entidad p_e', 'p_e.id = mm_d.para_entidad_id AND p_et.tabla = p_e.tabla', 'left')
			->join('usuario l_u', 'l_u.id = mm_d.leido_usuario_id', 'left')
			->join('usuario_persona l_up', 'l_u.id = l_up.usuario_id', 'left')
			->join('persona l_p', 'l_up.cuil = l_p.cuil', 'left')
			->add_column('', '')
			->add_column('menu', '<a class="btn btn-xs btn-default" href="mensaje/modal_ver_difusion/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>', 'id')
			->where('p_r.codigo', $this->rol->codigo)
			->where('para_entidad_id', $this->rol->entidad_id);
		echo $this->datatables->generate();
	}

	public function listar_data_difusion_enviados() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select("mensaje_masivo.id, mensaje_masivo.fecha, mensaje_masivo.asunto, mensaje_masivo.mensaje, CONCAT(d_p.apellido, ', ', d_p.nombre, ' (', d_r.nombre,')', COALESCE(CONCAT(' ', d_e.nombre), '')) as de, CONCAT('Para Rol',': ',p_r.nombre, ' (', COUNT(DISTINCT mm_d.id), ' mensajes enviados)') as para")
			->unset_column('id')
			->from('mensaje_masivo')
			->join('mensaje_masivo_destinatario mm_d', 'mm_d.mensaje_masivo_id = mensaje_masivo.id')
			->join('usuario d_u', 'd_u.id = mensaje_masivo.de_usuario_id', 'left')
			->join('usuario_persona d_up', 'd_u.id = d_up.usuario_id', 'left')
			->join('persona d_p', 'd_up.cuil = d_p.cuil', 'left')
			->join('rol d_r', 'd_r.id = mensaje_masivo.de_rol_id', 'left')
			->join('entidad_tipo d_et', 'd_r.entidad_tipo_id = d_et.id', 'left')
			->join('entidad d_e', 'd_e.id = mensaje_masivo.de_entidad_id AND d_et.tabla = d_e.tabla', 'left')
			->join('rol p_r', 'p_r.id = mensaje_masivo.para_rol_id', 'left')
			->group_by('mm_d.mensaje_masivo_id')
			->add_column('', '')
			->add_column('menu', '<a class="btn btn-xs btn-default" href="mensaje/listar_entidades_difusion/$1"><i class="fa fa-search"></i>Lista de enviados</a>', 'id')
			->where('d_r.codigo', $this->rol->codigo);
		if (!empty($this->rol->entidad_id)) {
			$this->datatables->where('mensaje_masivo.de_entidad_id', $this->rol->entidad_id);
		}
		echo $this->datatables->generate();
	}

	public function listar_entidades_difusion($mensaje_masivo_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_mensaje_masivo) || $mensaje_masivo_id == NULL || !ctype_digit($mensaje_masivo_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model("mensaje_masivo_model");
		$mensaje_masivo = $this->mensaje_masivo_model->get_one($mensaje_masivo_id);
		if (empty($mensaje_masivo)) {
			show_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		if ($this->rol->codigo != $mensaje_masivo->codigo || $this->rol->entidad_id != $mensaje_masivo->de_entidad_id) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Registro no encontrado');
			return;
		}

		$tableData = array(
			'columns' => array(
				array('label' => 'Destinatario', 'data' => 'para', 'width' => 30),
				array('label' => 'Leido Fecha', 'data' => 'leido_fecha', 'render' => 'datetime', 'width' => 15),
				array('label' => 'Usuario lectura/resolución', 'data' => 'leido_usuario', 'width' => 25),
				array('label' => 'Leído', 'data' => 'leido', 'width' => 10),
				array('label' => '', 'data' => 'menu', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'difusion_destinatario_table',
			'source_url' => 'mensaje/listar_data_entidades_difusion/' . $mensaje_masivo_id,
			'reuse_var' => TRUE,
			'initComplete' => "complete_difusion_destinatario_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>',
			'order' => array(array(0, 'desc'))
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['mensaje_difusion'] = $mensaje_masivo;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Destinatarios del mensaje de difusión';
		$this->load_template('mensaje/mensaje_difusion_listar', $data);
	}

	public function listar_data_entidades_difusion($mensaje_masivo_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->helper('datatables_functions_helper');
		$this->datatables
			->select("mm_d.id, mensaje_masivo.fecha, mensaje_masivo.asunto, mensaje_masivo.mensaje, CONCAT(d_p.apellido, ', ', d_p.nombre, ' - ', d_r.nombre, COALESCE(CONCAT(' ', d_e.nombre), '')) as de, CONCAT(l_p.apellido, ', ', l_p.nombre, ' (' , p_r.nombre,')') as leido_usuario, CONCAT(p_r.nombre, COALESCE(CONCAT(' ', p_e.nombre), '')) as para, mm_d.leido_fecha, (CASE WHEN mm_d.leido_fecha IS NULL THEN 0 ELSE 1 END) as leido")
			->unset_column('id')
			->from('mensaje_masivo')
			->join('mensaje_masivo_destinatario mm_d', 'mensaje_masivo.id = mm_d.mensaje_masivo_id')
			->join('usuario d_u', 'd_u.id = mensaje_masivo.de_usuario_id', 'left')
			->join('usuario_persona d_up', 'd_u.id = d_up.usuario_id', 'left')
			->join('persona d_p', 'd_up.cuil = d_p.cuil', 'left')
			->join('rol d_r', 'd_r.id = mensaje_masivo.de_rol_id', 'left')
			->join('entidad_tipo d_et', 'd_r.entidad_tipo_id = d_et.id', 'left')
			->join('entidad d_e', 'd_e.id = mensaje_masivo.de_entidad_id AND d_et.tabla = d_e.tabla', 'left')
			->join('rol p_r', 'p_r.id = mensaje_masivo.para_rol_id', 'left')
			->join('entidad_tipo p_et', 'p_r.entidad_tipo_id = p_et.id', 'left')
			->join('entidad p_e', 'p_e.id = mm_d.para_entidad_id AND p_et.tabla = p_e.tabla', 'left')
			->join('usuario l_u', 'l_u.id = mm_d.leido_usuario_id', 'left')
			->join('usuario_persona l_up', 'l_u.id = l_up.usuario_id', 'left')
			->join('persona l_p', 'l_up.cuil = l_p.cuil', 'left')
			->where('mensaje_masivo.id', $mensaje_masivo_id)
			->add_column('leido', '$1', 'dt_column_mensaje_leido(leido, leido_fecha, leido_usuario)')
			->add_column('menu', '');
		echo $this->datatables->generate();
	}

	public function modal_enviar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('rol_model');
		if ($this->rol->codigo === ROL_ADMIN || $this->rol->codigo === ROL_USI) {
			$this->array_para_rol_control = $array_para_rol = $this->get_array('rol', 'nombre', 'id', array('where' => array('id NOT IN (5,6,7)'), 'sort_by' => 'id'));
		} else {
			$this->array_para_rol_control = $array_para_rol = $this->get_array('rol', 'nombre', 'id', array('where' => array('id NOT IN (5,6,7,10,11,13)'), 'sort_by' => 'id'));
		}
		if (empty($_POST['para_rol'])) {
			$this->array_para_entidad_control = $array_para_entidad = array('' => '');
		} else {
			$rol_id = $_POST['para_rol'];
			$rol = $this->rol_model->get(array('id' => $rol_id));
			if ($rol_id == 1) {
				$this->array_para_entidad_control = $array_para_entidad = array('' => 'Administrador');
			} elseif ($rol_id == 18) {
				$this->array_para_entidad_control = $array_para_entidad = array('' => 'USI');
			} elseif ($rol_id == 9) {
				$this->array_para_entidad_control = $array_para_entidad = array('' => 'Dir. Educación Privada');
			} elseif ($rol_id == 15) {
				$this->array_para_entidad_control = $array_para_entidad = array('' => 'Dir. SEOS');
			} elseif ($rol_id == 10) {
				$this->array_para_entidad_control = $array_para_entidad = array('' => 'Consulta General');
			} elseif ($rol_id == 11) {
				$this->array_para_entidad_control = $array_para_entidad = array('' => 'Sup. Liquidaciones');
			} elseif ($rol_id == 12) {
				$this->array_para_entidad_control = $array_para_entidad = array('' => 'Liquidaciones');
			} else {
				$this->load->model('entidad_model');
				$entidades = $this->entidad_model->get_entidades($rol->id);
				$array_para_entidad = array();
				foreach ($entidades as $entidad) {
					$array_para_entidad[$entidad->id] = $entidad->nombre;
				}
				$this->array_para_entidad_control = $array_para_entidad;
			}
		}
		$this->set_model_validation_rules($this->mensaje_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				if (!isset($this->rol->rol_id)) {
					$this->load->model('rol_model');
					$rol = $this->rol_model->get(array('codigo' => $this->rol->codigo));
					$this->rol->rol_id = $rol[0]->id;
				}
				$trans_ok = TRUE;
				$trans_ok &= $this->mensaje_model->create(array(
					'fecha' => date('Y-m-d H:i:s'),
					'de_usuario_id' => $this->usuario,
					'de_rol_id' => $this->rol->rol_id,
					'de_entidad_id' => $this->rol->entidad_id,
					'asunto' => $this->input->post('asunto'),
					'mensaje' => $this->input->post('mensaje'),
					'para_rol_id' => $this->input->post('para_rol'),
					'para_entidad_id' => $this->input->post('para_entidad')
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', 'Mensaje enviado exitosamente');
				} else {
					$this->session->set_flashdata('error', $this->mensaje_model->get_error());
				}
				redirect('mensaje/bandeja', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('mensaje/bandeja', 'refresh');
			}
		}

		$this->mensaje_model->fields['de']['value'] = $this->session->userdata('usuario')->apellido . ', ' . $this->session->userdata('usuario')->nombre . " ({$this->rol->rol} {$this->rol->entidad})";
		$this->mensaje_model->fields['para_rol']['array'] = $array_para_rol;
		$this->mensaje_model->fields['para_entidad']['array'] = $array_para_entidad;
		$data['fields'] = $this->build_fields($this->mensaje_model->fields);

		$data['txt_btn'] = 'Enviar';
		$data['title'] = 'Enviar mensaje';
		$this->load->view('mensaje/mensaje_modal_enviar', $data);
	}

	public function modal_responder($mensaje_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $mensaje_id == NULL || !ctype_digit($mensaje_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$mensaje = $this->mensaje_model->get_one($mensaje_id);

		if (empty($mensaje)) {
			$this->modal_error('No se encontró el mensaje a responder', 'Registro no encontrado');
			return;
		}

		if (!($this->rol->codigo === ROL_USI && $mensaje->para_rol_codigo === ROL_ADMIN)) {
			if ($this->usuario != $mensaje->de_usuario_id && $this->usuario != $mensaje->para_usuario_id) {
				if ($this->rol->codigo != $mensaje->para_rol_codigo || $this->rol->entidad_id != $mensaje->para_entidad_id) {
					$this->modal_error('No tiene permisos para la acción solicitada', 'Registro no encontrado');
					return;
				}
			}
		}

		$conversacion = $this->mensaje_model->get_conversacion(empty($mensaje->conversacion_id) ? $mensaje->id : $mensaje->conversacion_id);

		if (empty($conversacion)) {
			$this->modal_error('No se encontró el registro de conversación solicitado', 'Registro no encontrado');
			return;
		}
		$this->mensaje_model->fields = array(
			'de' => array('label' => 'De', 'readonly' => TRUE, 'value' => $this->rol->rol . ' ' . $this->rol->entidad . ' - ' . $this->session->userdata('usuario')->apellido . ', ' . $this->session->userdata('usuario')->nombre),
			'para' => array('label' => 'Para', 'readonly' => TRUE, 'value' => $mensaje->de_usuario),
			'asunto' => array('label' => 'Asunto', 'maxlength' => '100', 'type' => 'hidden', 'required' => TRUE, 'value' => substr('RE:' . $mensaje->asunto, 0, 100)),
			'mensaje' => array('label' => 'Mensaje', 'form_type' => 'textarea', 'rows' => '3', 'required' => TRUE),
		);

		$this->set_model_validation_rules($this->mensaje_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;

				$trans_ok &= $this->mensaje_model->create(array(
					'conversacion_id' => isset($mensaje->conversacion_id) ? $mensaje->conversacion_id : $mensaje->id,
					'fecha' => date('Y-m-d H:i:s'),
					'de_usuario_id' => $this->usuario,
					'de_rol_id' => $this->rol->rol_id,
					'de_entidad_id' => $this->rol->entidad_id,
					'asunto' => $this->input->post('asunto'),
					'mensaje' => $this->input->post('mensaje'),
					'para_usuario_id' => $mensaje->de_usuario_id,
					), FALSE);
				if ($this->input->post('leido')) {
					$trans_ok &= $this->mensaje_model->update(array(
						'id' => $mensaje->id,
						'leido_fecha' => date('Y-m-d H:i:s'),
						'leido_usuario_id' => $this->usuario
						), FALSE);
				}

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', 'Mensaje respondido exitosamente');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', $this->mensaje_model->get_error());
				}
				redirect('mensaje/bandeja', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('mensaje/bandeja', 'refresh');
			}
		}

		foreach ($conversacion as $mensaje_c) {
			if ($mensaje_c->de_tabla === 'escuela') {
				$mensaje_c->de = "$mensaje_c->de_usuario " . '(<a target="_blank" href="escuela/escritorio/' . $mensaje_c->de_entidad_id . '" title="Ver escuela">' . $mensaje_c->de_rol . '</a>)';
			} else {
				$mensaje_c->de = "$mensaje_c->de_usuario ($mensaje_c->de_rol)";
			}
			$mensaje_c->para = $mensaje_c->para_usuario . (empty($mensaje_c->para_rol) ? '' : " ($mensaje_c->para_rol)");
		}

		$data['mensaje'] = $mensaje;
		$data['conversacion'] = $conversacion;
		$data['remitente'] = $conversacion[0]->de_usuario;
		$data['fields'] = $this->build_fields($this->mensaje_model->fields);
		$data['txt_btn'] = 'Responder';
		$data['title'] = 'Responder mensaje';
		$this->load->view('mensaje/mensaje_modal_responder', $data);
	}

	public function modal_ver($id = NULL, $respondible = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$mensaje = $this->mensaje_model->get_one($id);

		if (empty($mensaje)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		if (!($this->rol->codigo === ROL_USI && $mensaje->para_rol_codigo === ROL_ADMIN)) {
			if ($this->usuario != $mensaje->de_usuario_id && $this->usuario != $mensaje->para_usuario_id) {
				if ($this->rol->codigo != $mensaje->para_rol_codigo || $this->rol->entidad_id != $mensaje->para_entidad_id) {
					$this->modal_error('No tiene permisos para la acción solicitada', 'Registro no encontrado');
					return;
				}
			}
		}

		$conversacion = $this->mensaje_model->get_conversacion(empty($mensaje->conversacion_id) ? $mensaje->id : $mensaje->conversacion_id);

		if (empty($conversacion)) {
			$this->modal_error('No se encontró el registro de conversación solicitado', 'Registro no encontrado');
			return;
		}
		$this->mensaje_model->fields = array(
			'de' => array('label' => 'De'),
			'para' => array('label' => 'Para'),
			'asunto' => array('label' => 'Asunto'),
			'mensaje' => array('label' => 'Mensaje', 'form_type' => 'textarea', 'rows' => '5'),
			'leido_usuario' => array('label' => 'Usuario lectura/resolución'),
			'leido_fecha' => array('label' => 'Fecha lectura/resolución', 'type' => 'datetime'),
		);

		if (isset($_POST) && !empty($_POST)) {
			$accion = $this->input->post('accion');
			if ($accion === 'responder') {
				$this->session->set_flashdata('responder_mensaje', $mensaje->id);
			} elseif ($accion === 'leer') {
				$trans_ok = TRUE;

				$trans_ok &= $this->mensaje_model->update(array(
					'id' => $mensaje->id,
					'leido_fecha' => date('Y-m-d H:i:s'),
					'leido_usuario_id' => $this->usuario
				));

				if ($trans_ok) {
					$this->session->set_flashdata('message', 'Mensaje marcado como leído/resuelto');
				} else {
					$this->session->set_flashdata('error', $this->mensaje_model->get_error());
				}
			}
			redirect('mensaje/bandeja', 'refresh');
		}

		$mensaje->de = "$mensaje->de_usuario ($mensaje->de_rol)";
		$mensaje->para = $mensaje->para_usuario . (empty($mensaje->para_rol) ? '' : " ($mensaje->para_rol)");

		$data['fields'] = $this->build_fields($this->mensaje_model->fields, $mensaje, TRUE);
		foreach ($conversacion as $mensaje_c) {
			if ($mensaje_c->de_tabla === 'escuela') {
				$mensaje_c->de = "$mensaje_c->de_usuario " . '(<a target="_blank" href="escuela/escritorio/' . $mensaje_c->de_entidad_id . '" title="Ver escuela">' . $mensaje_c->de_rol . '</a>)';
			} else {
				$mensaje_c->de = "$mensaje_c->de_usuario ($mensaje_c->de_rol)";
			}
			$mensaje_c->para = $mensaje_c->para_usuario . (empty($mensaje_c->para_rol) ? '' : " ($mensaje_c->para_rol)");
		}

		$data['mensaje'] = $conversacion[0];
		$data['conversacion'] = $conversacion;
		$data['remitente'] = $conversacion[0]->de_usuario;
		$data['respondible'] = $respondible;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver mensaje';
		$this->load->view('mensaje/mensaje_modal_ver', $data);
	}

	public function modal_derivar($mensaje_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_derivar) || $mensaje_id == NULL || !ctype_digit($mensaje_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('usuario_model');
		$usuario = $this->usuario_model->get_one($this->usuario);
		if (empty($usuario)) {
			$this->modal_error('No se encontró el registro del usuario', 'Registro no encontrado');
			return;
		}
		$mensaje = $this->mensaje_model->get_one($mensaje_id);
		if (empty($mensaje)) {
			$this->modal_error('No se encontró el mensaje a responder', 'Registro no encontrado');
			return;
		}
		$conversacion = $this->mensaje_model->get_conversacion(empty($mensaje->conversacion_id) ? $mensaje->id : $mensaje->conversacion_id);
		if (count($conversacion) > 1) {
			$this->modal_error('No se puede derivar el mensaje ya que pertenece a una conversación', 'Acción no permitida');
			return;
		}
		foreach ($conversacion as $mensaje_c) {
			if ($mensaje_c->de_tabla === 'escuela') {
				$mensaje_c->de = "$mensaje_c->de_usuario " . '(<a target="_blank" href="escuela/escritorio/' . $mensaje_c->de_entidad_id . '" title="Ver escuela">' . $mensaje_c->de_rol . '</a>)';
			} else {
				$mensaje_c->de = "$mensaje_c->de_usuario ($mensaje_c->de_rol)";
			}
			$mensaje_c->para = $mensaje_c->para_usuario . (empty($mensaje_c->para_rol) ? '' : " ($mensaje_c->para_rol)");
		}
		$data['conversacion'] = $conversacion;
		$data['remitente'] = $conversacion[0]->de_usuario;

		$this->mensaje_model->fields = array(
			'de' => array('label' => 'De'),
			'para' => array('label' => 'Para'),
			'asunto' => array('label' => 'Asunto'),
			'mensaje' => array('label' => 'Mensaje', 'form_type' => 'textarea', 'rows' => '5'),
			'leido_usuario' => array('label' => 'Usuario lectura/resolución'),
			'leido_fecha' => array('label' => 'Fecha lectura/resolución', 'type' => 'datetime'),
		);
		$mensaje->de = "$mensaje->de_usuario ($mensaje->de_rol)";
		$mensaje->para = $mensaje->para_usuario . (empty($mensaje->para_rol) ? '' : " ($mensaje->para_rol)");
		$data['fields'] = $this->build_fields($this->mensaje_model->fields, $mensaje, TRUE);

		if (isset($_POST) && !empty($_POST)) {
			if ($this->input->post('usuario_id') != $usuario->id) {
				show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
			}
			$rol_id = $this->input->post('rol');
			$entidad_id = $this->input->post('entidad');
			$trans_ok = TRUE;
			$trans_ok &= $this->mensaje_model->update(array(
				'id' => $mensaje->id,
				'para_rol_id' => $rol_id,
				'para_entidad_id' => $entidad_id
			));
			if ($trans_ok) {
				$this->session->set_flashdata('message', 'Mensaje derivado correctamente');
				redirect("mensaje/bandeja");
			} else {
				$this->session->set_flashdata('error', $this->mensaje_model->get_error());
				redirect("mensaje/bandeja");
			}
		}

		$this->load->model('usuario_rol_model');
		$this->load->model('rol_model');
		$this->load->model('entidad_model');
		$this->array_rol_control = $array_rol = $this->get_array('rol', 'nombre', 'id', array('where' => array('id NOT IN (' . $this->rol->rol_id . ',5,6,7,24,26,27)'), 'sort_by' => 'id'));
		$this->array_entidad_control = $array_entidad = array('' => '');

		$this->usuario_rol_model->fields['usuario']['value'] = $usuario->usuario;
		$this->usuario_rol_model->fields['rol']['array'] = $array_rol;
		$this->usuario_rol_model->fields['entidad']['array'] = $array_entidad;
		$data['fields'] = $this->build_fields($this->usuario_rol_model->fields);
		$data['usuario'] = $usuario;
		$data['txt_btn'] = 'Derivar';
		$data['title'] = 'Derivar mensaje';
		$this->load->view("mensaje/mensaje_modal_derivar", $data);
	}

	public function modal_seleccionar_rol() {
		if (!in_array($this->rol->codigo, $this->roles_mensaje_masivo)) {
			return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$this->load->model('usuario_model');
		$usuario = $this->usuario_model->get_one($this->usuario);
		if (empty($usuario)) {
			$this->modal_error('No se encontró el registro del usuario', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($this->input->post('usuario_id') != $usuario->id) {
				show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
			} else {
				$rol_id = $this->input->post('rol');
				redirect("mensaje/mensaje_difusion/$rol_id");
			}
		}

		$this->load->model('usuario_rol_model');
		$this->load->model('rol_model');
		switch ($this->rol->codigo) {
			case ROL_ADMIN:
			case ROL_USI:
			case ROL_LIQUIDACION:
				$this->array_rol_control = $array_rol = $this->get_array('rol', 'nombre', 'id', array('sort_by' => 'id', 'where' => array("codigo IN ('" . implode("','", $this->roles_destinatarios) . "')")));
				break;
			case ROL_LINEA:
				$this->array_rol_control = $array_rol = $this->get_array('rol', 'nombre', 'id', array('sort_by' => 'id', 'where' => array("entidad_tipo_id IN (1,3)", "codigo IN ('" . implode("','", $this->roles_destinatarios) . "')")));
				break;
			case ROL_SUPERVISION:
			case ROL_CONSULTA_LINEA:
			case ROL_REGIONAL:
			case ROL_SEOS:
			case ROL_PRIVADA:
				$this->array_rol_control = $array_rol = $this->get_array('rol', 'nombre', 'id', array('sort_by' => 'id', 'where' => array("entidad_tipo_id = 1", "codigo IN ('" . implode("','", $this->roles_destinatarios) . "')")));
				break;
			default:
				return $this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}

		unset($this->usuario_rol_model->fields['entidad']);
		$this->usuario_rol_model->fields['usuario']['value'] = $usuario->usuario;
		$this->usuario_rol_model->fields['rol']['array'] = $array_rol;
		$data['usuario'] = $usuario;
		$data['fields'] = $this->build_fields($this->usuario_rol_model->fields);
		$data['txt_btn'] = 'Seleccionar';
		$data['title'] = 'Seleccione el rol al que desea enviar el mensaje de difusión';
		$this->load->view("mensaje/mensaje_modal_seleccionar_rol", $data);
	}

	public function mensaje_difusion($rol_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_mensaje_masivo)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('rol_model');
		$rol_seleccionado = $this->rol_model->get_one($rol_id);
		if (empty($rol_seleccionado)) {
			show_error('No se encontró el registro del ROL', 500, 'Registro no encontrado');
		}
		switch ($this->rol->codigo) {
			case ROL_ADMIN:
			case ROL_LIQUIDACION:
			case ROL_USI:
				if (!in_array($rol_seleccionado->codigo, $this->roles_destinatarios)) {
					show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
				}
				break;
			case ROL_LINEA:
				if (!in_array($rol_seleccionado->codigo, $this->roles_destinatarios) && ($rol_seleccionado->entidad_tipo_id !== '1' || $rol_seleccionado->entidad_tipo_id !== '3')) {
					show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
				}
				break;
			case ROL_CONSULTA_LINEA:
			case ROL_REGIONAL:
			case ROL_SEOS:
			case ROL_SUPERVISION:
			case ROL_PRIVADA:
				if (!in_array($rol_seleccionado->codigo, $this->roles_destinatarios) || $rol_seleccionado->entidad_tipo_id !== '1') {
					show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
				}
				break;
			default:
				show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('mensaje_masivo_model');
		$this->load->model('mensaje_masivo_destinatario_model');

		$this->set_model_validation_rules($this->mensaje_masivo_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$entidades_ids = $this->input->post('entidad');
				if (empty($entidades_ids)) {
					$this->session->set_flashdata('error', 'No se selecciono niguna escuela');
					redirect('mensaje/bandeja', 'refresh');
				}
				$this->db->trans_begin();
				if (!isset($this->rol->rol_id)) {
					$rol = $this->rol_model->get(array('codigo' => $this->rol->codigo));
					$this->rol->rol_id = $rol[0]->id;
				}
				$trans_ok = TRUE;
				$trans_ok &= $this->mensaje_masivo_model->create(array(
					'fecha' => date('Y-m-d H:i:s'),
					'de_usuario_id' => $this->usuario,
					'de_rol_id' => $this->rol->rol_id,
					'de_entidad_id' => $this->rol->entidad_id,
					'asunto' => $this->input->post('asunto'),
					'mensaje' => $this->input->post('mensaje'),
					'para_rol_id' => $rol_seleccionado->id,
					), FALSE);
				$mensaje_masivo_id = $this->mensaje_masivo_model->get_row_id();
				foreach ($entidades_ids as $p_id => $entidad_id) {
					$trans_ok &= $this->mensaje_masivo_destinatario_model->create(array(
						'mensaje_masivo_id' => $mensaje_masivo_id,
						'para_entidad_id' => $entidad_id,
						), FALSE);
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', 'Mensaje masivo enviado exitosamente');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar enviar el mensaje.';
					if ($this->mensaje_masivo_model->get_error()) {
						$errors .= '<br>' . $this->mensaje_masivo_model->get_error();
					}
					if ($this->mensaje_masivo_destinatario_model->get_error()) {
						$errors .= '<br>' . $this->mensaje_masivo_destinatario_model->get_error();
						$this->session->set_flashdata('error', $errors);
					}
				}
				redirect('mensaje/bandeja', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('mensaje/bandeja', 'refresh');
			}
		}

		$this->mensaje_masivo_model->fields['de']['value'] = $this->session->userdata('usuario')->apellido . ', ' . $this->session->userdata('usuario')->nombre . " ({$this->rol->rol} {$this->rol->entidad})";
		$data['fields_mensaje'] = $this->build_fields($this->mensaje_masivo_model->fields);

		$model_busqueda = new stdClass();
		$model_busqueda->fields = array(
			'nivel' => array('label' => 'Nivel', 'input_type' => 'combo', 'class' => 'selectize'),
			'linea' => array('label' => 'Línea', 'input_type' => 'combo', 'class' => 'selectize'),
			'supervision' => array('label' => 'Supervisión', 'input_type' => 'combo', 'class' => 'selectize'),
			'dependencia' => array('label' => 'Dependencia', 'input_type' => 'combo', 'class' => 'selectize'),
		);
		$this->load->model('nivel_model');
		$this->load->model('dependencia_model');
		$this->load->model('supervision_model');
		$this->load->model('linea_model');

		$array_dependencia = $this->get_array('dependencia', 'descripcion', 'id', array('sort_by' => 'id'), array('' => ''));
		$array_linea = $this->get_array('linea', 'nombre', 'id', array(
			'join' => array(
				array('nivel', 'linea.id=nivel.linea_id', 'left', array('linea.nombre as nombre'))
			), 'sort_by' => 'id'), array('' => ''));
		$array_nivel = $this->get_array('nivel', 'descripcion', 'id', array('sort_by' => 'id'), array('' => ''));
		if (in_array($this->rol->codigo, ARRAY(ROL_LINEA))) {
			$array_supervision = $this->get_array('supervision', 'nombre', 'id', array(
				'sort_by' => 'id',
				'where' => array('linea_id =' . $this->rol->entidad_id, 'dependencia_id = 1')
				), array('' => ''));
		} elseif (in_array($this->rol->codigo, ARRAY(ROL_PRIVADA))) {
			$array_supervision = $this->get_array('supervision', 'nombre', 'id', array(
				'sort_by' => 'id',
				'where' => array('dependencia_id = 2')
				), array('' => ''));
		} elseif (in_array($this->rol->codigo, ARRAY(ROL_SEOS))) {
			$array_supervision = $this->get_array('supervision', 'nombre', 'id', array(
				'sort_by' => 'id',
				'where' => array('dependencia_id = 3')
				), array('' => ''));
		} else {
			$array_supervision = $this->get_array('supervision', 'nombre', 'id', array('sort_by' => 'id'), array('' => ''));
		}
		$model_busqueda->fields['dependencia']['array'] = $array_dependencia;
		$model_busqueda->fields['linea']['array'] = $array_linea;
		$model_busqueda->fields['nivel']['array'] = $array_nivel;
		$model_busqueda->fields['supervision']['array'] = $array_supervision;
		$data['fields'] = $this->build_fields($model_busqueda->fields);
		$data['rol_seleccionado'] = $rol_seleccionado;

		switch ($rol_seleccionado->entidad_tipo_id) {
			case 1: //escuelas
				$data['html_destinatarios'] = $this->load->view('mensaje/mensaje_difusion_escuela', $data, TRUE);
				break;
			case 2: //lineas
				$data['html_destinatarios'] = $this->load->view('mensaje/mensaje_difusion_linea', $data, TRUE);
				break;
			case 3: //supervision
				$data['html_destinatarios'] = $this->load->view('mensaje/mensaje_difusion_supervision', $data, TRUE);
				break;
			case 5: //areas
				$data['html_destinatarios'] = $this->load->view('mensaje/mensaje_difusion_area', $data, TRUE);
				break;
			case 6: //regional
				$data['html_destinatarios'] = $this->load->view('mensaje/mensaje_difusion_regional', $data, TRUE);
				break;
			default:
				show_error('No se encontró el registro del rol', 500, 'Registro no encontrado');
				break;
		}
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . 'Mensaje de difusión';
		$this->load_template('mensaje/mensaje_difusion_entidad', $data);
	}

	public function modal_ver_difusion($id = NULL, $respondible = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$this->load->model('mensaje_masivo_model');
		$this->load->model('mensaje_masivo_destinatario_model');

		$mensaje_masivo_destinatario = $this->mensaje_masivo_destinatario_model->get_one($id);
		if (empty($mensaje_masivo_destinatario)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}
		if ($this->rol->codigo != $mensaje_masivo_destinatario->para_rol_codigo || $this->rol->entidad_id != $mensaje_masivo_destinatario->para_entidad_id) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Registro no encontrado');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			$accion = $this->input->post('accion');
			if ($accion === 'leer') {
				$trans_ok = TRUE;
				$trans_ok &= $this->mensaje_masivo_destinatario_model->update(array(
					'id' => $mensaje_masivo_destinatario->id,
					'leido_fecha' => date('Y-m-d H:i:s'),
					'leido_usuario_id' => $this->usuario
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', 'Mensaje marcado como leído/resuelto');
				} else {
					$this->session->set_flashdata('error', $this->mensaje_masivo_destinatario_model->get_error());
				}
			}
			redirect('escritorio', 'refresh');
		}

		$mensaje_masivo_destinatario->de = "$mensaje_masivo_destinatario->de_usuario ($mensaje_masivo_destinatario->de_rol)";
		$mensaje_masivo_destinatario->para = (empty($mensaje_masivo_destinatario->para_rol) ? '' : " ($mensaje_masivo_destinatario->para_rol)");

		$data['fields'] = $this->build_fields($this->mensaje_masivo_destinatario_model->fields, $mensaje_masivo_destinatario, TRUE);
		$data['mensaje_masivo'] = $mensaje_masivo_destinatario;
		$data['respondible'] = $respondible;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver mensaje de difusión';
		$this->load->view('mensaje/mensaje_modal_ver_difusion', $data);
	}

	public function mensaje_difusion_listar_entidades() {
		if (!in_array($this->rol->codigo, $this->roles_mensaje_masivo)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$entidad_tipo = $this->input->post('entidad_tipo');
		switch ($entidad_tipo) {
			case 1: //escuelas
				$nivel = $this->input->post('nivel');
				$supervision = $this->input->post('supervision');
				$linea = $this->input->post('linea');
				$dependencia = $this->input->post('dependencia');
				$escuelas = $this->mensaje_model->get_escuelas($this->rol, $linea, $supervision, $dependencia, $nivel);
				if (!empty($escuelas)) {
					echo json_encode(array('status' => 'success', 'escuelas' => $escuelas));
				} else {
					echo json_encode(array('status' => 'error'));
				}
				break;
			case 2: //lineas
				$lineas = $this->mensaje_model->get_lineas($this->rol);
				if (!empty($lineas)) {
					echo json_encode(array('status' => 'success', 'lineas' => $lineas));
				} else {
					echo json_encode(array('status' => 'error'));
				}
				break;
			case 3: //supervisiones
				$nivel = $this->input->post('nivel');
				$dependencia = $this->input->post('dependencia');
				$supervisiones = $this->mensaje_model->get_supervisiones($this->rol, $dependencia, $nivel);
				if (!empty($supervisiones)) {
					echo json_encode(array('status' => 'success', 'supervisiones' => $supervisiones));
				} else {
					echo json_encode(array('status' => 'error'));
				}
				break;
			case 5: //areas
				$areas = $this->mensaje_model->get_areas($this->rol);
				if (!empty($areas)) {
					echo json_encode(array('status' => 'success', 'areas' => $areas));
				} else {
					echo json_encode(array('status' => 'error'));
				}
				break;
			case 6: //regionales
				$regionales = $this->mensaje_model->get_regionales($this->rol);
				if (!empty($regionales)) {
					echo json_encode(array('status' => 'success', 'regionales' => $regionales));
				} else {
					echo json_encode(array('status' => 'error'));
				}
				break;
			default:
				show_error('No se encontró el registro del rol', 500, 'Registro no encontrado');
				break;
		}
	}
}
/* End of file Mensaje.php */
/* Location: ./application/controllers/Mensaje.php */