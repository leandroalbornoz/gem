<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mensaje extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('mensaje_bono_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL,ROL_ASISTENCIA_DIVISION,ROL_DOCENTE_CURSADA));
		$this->roles_destinatarios = array(ROL_DIR_ESCUELA, ROL_LINEA, ROL_SUPERVISION, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_AREA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_ESCUELA_ALUM, ROL_ESCUELA_CAR);
		$this->nav_route = 'menu/mensaje';
	}

	public function index() {
		return $this->bandeja();
	}

	public function respondedor() {
		if (!es_rol_bono($this->rol)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$data['mensajes'] = $this->mensaje_bono_model->get_mensajes($this->usuario, $this->rol);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Bandeja de mensajes';
		$this->load_template('bono_secundario/mensaje/mensaje_respondedor', $data);
	}

	public function bandeja() {
		if (!es_rol_bono($this->rol)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData_mensaje = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 5),
				array('label' => 'Remitente', 'data' => 'de_usuario', 'width' => 27),
				array('label' => 'Fecha', 'data' => 'fecha', 'render' => 'datetime', 'width' => 8),
				array('label' => 'Tema', 'data' => 'tema', 'width' => 8),
				array('label' => 'Asunto', 'data' => 'asunto', 'width' => 35),
				array('label' => 'Mensaje', 'data' => 'mensaje', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Destinatario', 'data' => 'para_rol', 'width' => 13),
				array('label' => '', 'data' => 'menu', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'mensaje_bandeja_table',
			'source_url' => 'bono_secundario/mensaje/listar_data/mensaje_bandeja',
			'reuse_var' => TRUE,
			'initComplete' => "complete_mensaje_bandeja_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-xl-4.col-lg-12"l><"col-xl-8.col-lg-12"p>>rt<"row"<"col-xl-4.col-lg-12"i><"col-xl-8.col-lg-12"p>>',
			'order' => array(array(2, 'asc'))
		);
		$data['html_table'] = buildHTML($tableData_mensaje);
		$data['js_table'] = buildJS($tableData_mensaje);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Bandeja de mensajes';
		$this->load_template('bono_secundario/mensaje/mensaje_bandeja', $data);
	}

	public function leidos() {
		if (!es_rol_bono($this->rol)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData_rol = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 5),
				array('label' => 'Remitente', 'data' => 'de', 'width' => 36),
				array('label' => 'Fecha', 'data' => 'fecha', 'render' => 'datetime', 'width' => 10),
				array('label' => 'Tema', 'data' => 'tema', 'width' => 10),
				array('label' => 'Asunto', 'data' => 'asunto', 'width' => 37),
				array('label' => 'Mensaje', 'data' => 'mensaje', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Destinatario', 'data' => 'para', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Leído', 'data' => 'leido_fecha', 'width' => 5, 'render' => 'datetime', 'responsive_class' => 'none'),
				array('label' => 'Leído por', 'data' => 'leido_usuario', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'menu', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'mensaje_rol_table',
			'source_url' => 'bono_secundario/mensaje/listar_data/leidos_rol',
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
				array('label' => 'Tema', 'data' => 'tema', 'width' => 10),
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
		$this->load_template('bono_secundario/mensaje/mensaje_leidos', $data);
	}

	public function enviados() {
		if (!es_rol_bono($this->rol)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => '', 'data' => '', 'width' => 5),
				array('label' => 'Remitente', 'data' => 'de', 'width' => 20),
				array('label' => 'Fecha', 'data' => 'fecha', 'render' => 'datetime', 'width' => 10),
				array('label' => 'Tema', 'data' => 'tema', 'width' => 10),
				array('label' => 'Asunto', 'data' => 'asunto', 'width' => 29),
				array('label' => 'Mensaje', 'data' => 'mensaje', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Destinatario', 'data' => 'para', 'width' => 20),
				array('label' => 'Leído', 'data' => 'leido', 'width' => 5),
				array('label' => 'Fecha lectura/resolución', 'data' => 'leido_fecha', 'width' => 5, 'render' => 'datetime', 'responsive_class' => 'none'),
				array('label' => 'Usuario lectura/resolución', 'data' => 'leido_usuario', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'menu', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'mensaje_table',
			'source_url' => 'bono_secundario/mensaje/listar_data/enviados',
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
		$this->load_template('bono_secundario/mensaje/mensaje_enviados', $data);
	}

	public function listar_data($tipo = 'leidos_usuario') {
		if (!es_rol_bono($this->rol)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->helper('datatables_functions_helper');
		if ($tipo === "mensaje_bandeja") {
			$this->datatables
				->select("mensaje_bono.id, mensaje_bono.fecha, mensaje_bono.asunto, mensaje_bono.mensaje, CONCAT(bsp.apellido, ', ', bsp.nombre) as de_usuario, CONCAT(p_r.nombre, COALESCE(CONCAT(' ', p_e.nombre), '')) as para_rol, COALESCE(t.descripcion, '') as tema")
				->unset_column('id')
				->from('mensaje_bono')
				->join('bono_secundario.persona bsp', 'bsp.usuario_id = mensaje_bono.de_usuario_id')
				->join('bono_secundario.tema_mensaje t', 't.id = mensaje_bono.tema_id', 'left')
				->join('rol d_r', 'd_r.id = mensaje_bono.de_rol_id', 'left')
				->join('entidad_tipo d_et', 'd_r.entidad_tipo_id = d_et.id', 'left')
				->join('entidad d_e', 'd_e.id = mensaje_bono.de_entidad_id AND d_et.tabla = d_e.tabla', 'left')
				->join('usuario p_u', 'p_u.id = mensaje_bono.para_usuario_id', 'left')
				->join('usuario_persona p_up', 'p_u.id = p_up.usuario_id', 'left')
				->join('persona p_p', 'p_up.cuil = p_p.cuil', 'left')
				->join('rol p_r', 'p_r.id = mensaje_bono.para_rol_id', 'left')
				->join('entidad_tipo p_et', 'p_r.entidad_tipo_id = p_et.id', 'left')
				->join('entidad p_e', 'p_e.id = mensaje_bono.para_entidad_id AND p_et.tabla = p_e.tabla', 'left')
				->add_column('', '')
				->add_column('menu', '<div class="btn-group" role="group">'
					. '<a class="btn btn-xs btn-default" href="bono_secundario/mensaje/modal_ver/$1/1" title="Ver" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>'
					. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" ><span class="caret"></span></button>'
					. '<ul class="dropdown-menu dropdown-menu-right">'
					. '<li><a class="dropdown-item" href="bono_secundario/mensaje/modal_responder/$1" title="Responder" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-pencil"></i> Responder</a></li>'
					. '</ul></div>', 'id')
				->where('leido_fecha IS NULL')
				->where('para_usuario_id IS NULL');
		} else {
			$this->datatables
				->select("mensaje_bono.id, mensaje_bono.fecha, mensaje_bono.asunto, mensaje_bono.mensaje, CONCAT(d_p.apellido, ', ', d_p.nombre) as de, mensaje_bono.leido_fecha, CONCAT(l_p.apellido, ', ', l_p.nombre) as leido_usuario, (CASE WHEN mensaje_bono.leido_fecha IS NULL THEN 0 ELSE 1 END) as leido, CONCAT((CASE WHEN mensaje_bono.para_rol_id IS NULL THEN 'Administrador de Juntas' ELSE p_r.nombre END), COALESCE(CONCAT(' ', p_e.nombre), '')) as para, t.descripcion as tema")
				->unset_column('id')
				->from('mensaje_bono')
				->join('bono_secundario.tema_mensaje t', 't.id = mensaje_bono.tema_id', 'left')
				->join('usuario d_u', 'd_u.id = mensaje_bono.de_usuario_id')
				->join('usuario_persona d_up', 'd_u.id = d_up.usuario_id')
				->join('persona d_p', 'd_up.cuil = d_p.cuil', 'left')
				->join('rol d_r', 'd_r.id = mensaje_bono.de_rol_id', 'left')
				->join('entidad_tipo d_et', 'd_r.entidad_tipo_id = d_et.id', 'left')
				->join('entidad d_e', 'd_e.id = mensaje_bono.de_entidad_id AND d_et.tabla = d_e.tabla', 'left')
				->join('usuario p_u', 'p_u.id = mensaje_bono.para_usuario_id', 'left')
				->join('usuario_persona p_up', 'p_u.id = p_up.usuario_id', 'left')
				->join('persona p_p', 'p_up.cuil = p_p.cuil', 'left')
				->join('rol p_r', 'p_r.id = mensaje_bono.para_rol_id', 'left')
				->join('entidad_tipo p_et', 'p_r.entidad_tipo_id = p_et.id', 'left')
				->join('entidad p_e', 'p_e.id = mensaje_bono.para_entidad_id AND p_et.tabla = p_e.tabla', 'left')
				->join('usuario l_u', 'l_u.id = mensaje_bono.leido_usuario_id', 'left')
				->join('usuario_persona l_up', 'l_u.id = l_up.usuario_id', 'left')
				->join('persona l_p', 'l_up.cuil = l_p.cuil', 'left')
				->add_column('', '')
				->add_column('leido', '$1', 'dt_column_mensaje_leido(leido, leido_fecha, leido_usuario)')
				->add_column('menu', '<a class="btn btn-xs btn-default" href="bono_secundario/mensaje/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>', 'id');
			if ($tipo === 'leidos_rol') {
				$this->datatables->where('mensaje_bono.leido_fecha IS NOT NULL');
			} elseif ($tipo === 'leidos_usuario') {
				$this->datatables->where('mensaje_bono.leido_fecha IS NOT NULL');
				$this->datatables->where('para_usuario_id', $this->usuario);
			} else {
				$this->datatables->where('mensaje_bono.de_usuario_id', $this->usuario);
			}
		}
		echo $this->datatables->generate();
	}

	public function modal_enviar() {
		if (!es_rol_bono($this->rol)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('rol_model');
		if ($this->rol->codigo === ROL_ADMIN || $this->rol->codigo === ROL_USI) {
			$this->array_para_rol_control = $array_para_rol = $this->get_array('rol', 'nombre', 'id', array('where' => array('id NOT IN (5,6,7)'), 'sort_by' => 'id'));
		} else {
			$this->array_para_rol_control = $array_para_rol = $this->get_array('rol', 'nombre', 'id', array('where' => array('id NOT IN (5,6,7,10,11,13)'), 'sort_by' => 'id'));
		}

		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('bono_secundario/tema_mensaje_model');
		$this->tema_mensaje_model->set_database($DB1);
		$this->array_tema_control = $array_tema = $this->get_array('tema_mensaje', 'descripcion', 'id', null, array('' => '-- Seleccionar --'));

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
		$this->set_model_validation_rules($this->mensaje_bono_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				if (!isset($this->rol->rol_id)) {
					$this->load->model('rol_model');
					$rol = $this->rol_model->get(array('codigo' => $this->rol->codigo));
					$this->rol->rol_id = $rol[0]->id;
				}
				$trans_ok = TRUE;
				$trans_ok &= $this->mensaje_bono_model->create(array(
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
					$this->session->set_flashdata('error', $this->mensaje_bono_model->get_error());
				}
				redirect('bono_secundario/mensaje/bandeja', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('bono_secundario/mensaje/bandeja', 'refresh');
			}
		}

		$this->mensaje_bono_model->fields['de']['value'] = $this->session->userdata('usuario')->apellido . ', ' . $this->session->userdata('usuario')->nombre . " ({$this->rol->rol} {$this->rol->entidad})";
		$this->mensaje_bono_model->fields['tema']['array'] = $array_tema;
		$this->mensaje_bono_model->fields['para_rol']['array'] = $array_para_rol;
		$this->mensaje_bono_model->fields['para_entidad']['array'] = $array_para_entidad;
		$data['fields'] = $this->build_fields($this->mensaje_bono_model->fields);
		$data['txt_btn'] = 'Enviar';
		$data['title'] = 'Enviar mensaje';
		$this->load->view('bono_secundario/mensaje/mensaje_modal_enviar', $data);
	}

	public function modal_responder($mensaje_id = NULL) {
		if (!es_rol_bono($this->rol)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$mensaje = $this->mensaje_bono_model->get_one($mensaje_id);

		if (empty($mensaje)) {
			$this->modal_error('No se encontró el mensaje a responder', 'Registro no encontrado');
			return;
		}

		$conversacion = $this->mensaje_bono_model->get_conversacion(empty($mensaje->conversacion_id) ? $mensaje->id : $mensaje->conversacion_id);

		if (empty($conversacion)) {
			$this->modal_error('No se encontró el registro de conversación solicitado', 'Registro no encontrado');
			return;
		}
		$this->mensaje_bono_model->fields = array(
			'de' => array('label' => 'De', 'readonly' => TRUE, 'value' => $this->rol->rol . ' ' . $this->rol->entidad . ' - ' . $this->session->userdata('usuario')->apellido . ', ' . $this->session->userdata('usuario')->nombre),
			'para' => array('label' => 'Para', 'readonly' => TRUE, 'value' => $mensaje->de_usuario),
			'asunto' => array('label' => 'Asunto', 'maxlength' => '100', 'type' => 'hidden', 'required' => TRUE, 'value' => substr('RE:' . $mensaje->asunto, 0, 100)),
			'mensaje' => array('label' => 'Mensaje', 'form_type' => 'textarea', 'rows' => '3', 'required' => TRUE),
		);

		$this->set_model_validation_rules($this->mensaje_bono_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;

				$trans_ok &= $this->mensaje_bono_model->create(array(
					'conversacion_id' => isset($mensaje->conversacion_id) ? $mensaje->conversacion_id : $mensaje->id,
					'fecha' => date('Y-m-d H:i:s'),
					'de_rol_id' => '22',
					'de_entidad_id' => $this->rol->entidad_id,
					'de_usuario_id' => $this->usuario,
					'asunto' => $this->input->post('asunto'),
					'mensaje' => $this->input->post('mensaje'),
					'para_usuario_id' => $mensaje->de_usuario_id,
					'tema_id' => $mensaje->tema_id,
					), FALSE);
				if ($this->input->post('leido')) {
					$trans_ok &= $this->mensaje_bono_model->update(array(
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
					$this->session->set_flashdata('error', $this->mensaje_bono_model->get_error());
				}
				redirect('bono_secundario/mensaje/bandeja', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('bono_secundario/mensaje/bandeja', 'refresh');
			}
		}

		foreach ($conversacion as $mensaje_c) {
			$mensaje_c->de = $mensaje_c->de_usuario;
			$mensaje_c->para = $this->rol->entidad;
		}

		$data['mensaje'] = $mensaje;
		$data['conversacion'] = $conversacion;
		$data['remitente'] = $conversacion[0]->de_usuario;
		$data['fields'] = $this->build_fields($this->mensaje_bono_model->fields);
		$data['txt_btn'] = 'Responder';
		$data['title'] = 'Responder mensaje';
		$this->load->view('bono_secundario/mensaje/mensaje_modal_responder', $data);
	}

	public function modal_ver($id = NULL, $respondible = '0') {
		if (!es_rol_bono($this->rol)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->load->model('mensaje_bono_model');
		$mensaje = $this->mensaje_bono_model->get_one($id);

		if (empty($mensaje)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		if ($this->usuario != $mensaje->de_usuario_id && $this->usuario != $mensaje->para_usuario_id) {
			if (!es_rol_bono($this->rol)) {
				$this->modal_error('No tiene permisos para la acción solicitada', 'Registro no encontrado');
				return;
			}
		}

		$mensaje = $this->mensaje_bono_model->get_one($id);
		$conversacion = $this->mensaje_bono_model->get_conversacion(empty($mensaje->conversacion_id) ? $mensaje->id : $mensaje->conversacion_id);

		if (empty($conversacion)) {
			$this->modal_error('No se encontró el registro de conversación solicitado', 'Registro no encontrado');
			return;
		}
		$this->mensaje_bono_model->fields = array(
			'de' => array('label' => 'De'),
			'para' => array('label' => 'Para'),
			'tema' => array('label' => 'Tema'),
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
				if ($this->rol->entidad_tipo_id == '8' && $this->rol->entidad_id == '3') {
					$trans_ok &= $this->mensaje_bono_model->update(array(
						'id' => $mensaje->id,
						'leido_fecha' => date('Y-m-d H:i:s'),
						'leido_usuario_id' => $this->usuario
					));
				} else {
					$trans_ok &= $this->mensaje_bono_model->update(array(
						'id' => $mensaje->id,
						'leido_fecha' => date('Y-m-d H:i:s'),
						'leido_usuario_id' => $this->usuario
					));
				}

				if ($trans_ok) {
					$this->session->set_flashdata('message', 'Mensaje marcado como leído/resuelto');
				} else {
					$this->session->set_flashdata('error', $this->mensaje_bono_model->get_error());
				}
			}
			redirect('bono_secundario/mensaje/bandeja', 'refresh');
		}

		$mensaje->de = "$mensaje->de_usuario ($mensaje->de_rol)";
		$mensaje->para = $mensaje->para_usuario . (empty($mensaje->para_rol) ? '' : " ($mensaje->para_rol)");

		$data['fields'] = $this->build_fields($this->mensaje_bono_model->fields, $mensaje, TRUE);
		foreach ($conversacion as $mensaje_c) {
			if (empty($mensaje_c->de_rol)) {
				$mensaje_c->de = "$mensaje_c->de_usuario (BONO SECUNDARIO)";
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
		$this->load->view('bono_secundario/mensaje/mensaje_modal_ver', $data);
	}
}
/* End of file Mensaje.php */
/* Location: ./application/controllers/Mensaje.php */