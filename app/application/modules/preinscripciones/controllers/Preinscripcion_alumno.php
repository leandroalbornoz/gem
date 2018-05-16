<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Preinscripcion_alumno extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->load->model('preinscripciones/preinscripcion_model');
		$this->load->model('preinscripciones/preinscripcion_alumno_model');
		$this->load->model('persona_model');
		$this->load->model('alumno_model');
		$this->load->model('alumno_division_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE_CURSADA));
		$this->nav_route = 'admin/preinscripcion';
	}

	public function modal_buscar($preinscripcion_id = NULL, $tipo_id = NULL, $redireccion = 'escuela') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $preinscripcion_id == NULL || !ctype_digit($preinscripcion_id) || $tipo_id == NULL || !in_array($tipo_id, array('1', '2', '3', '4', '5', '6'))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$preinscripcion = $this->preinscripcion_model->get_one($preinscripcion_id);
		if (empty($preinscripcion)) {
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$model_busqueda = new stdClass();
		$model_busqueda->fields = array(
			'd_documento' => array('label' => 'Documento', 'maxlength' => '9'),
			'd_apellido' => array('label' => 'Apellido', 'maxlength' => '100', 'minlength' => '3'),
			'd_nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'minlength' => '3'),
			'persona_seleccionada' => array('label' => '', 'type' => 'integer', 'required' => TRUE),
		);
		$data['fields'] = $this->build_fields($model_busqueda->fields);
		$data['escuela'] = $escuela;
		$data['preinscripcion'] = $preinscripcion;
		$data['redireccion'] = $redireccion;
		$data['tipo_id'] = $tipo_id;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Buscar alumno a agregar';
		$this->load->view('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_modal_buscar', $data);
	}

	public function agregar_existente($preinscripcion_id = NULL, $tipo_id = NULL, $persona_id = NULL, $redireccion = 'escuela') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $preinscripcion_id == NULL || !ctype_digit($preinscripcion_id) || $tipo_id == NULL || !in_array($tipo_id, array('1', '2', '3', '4', '5', '6')) || $persona_id == NULL || !ctype_digit($persona_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$preinscripcion = $this->preinscripcion_model->get_one($preinscripcion_id);
		if (empty($preinscripcion)) {
			show_error('No se encontró el registro de la preinscripción', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$persona = $this->persona_model->get_one($persona_id);
		if (empty($persona)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		$alumno = $this->alumno_model->get_by_persona($persona_id);
		switch ($tipo_id) {
			case '1':
			case '2':
			case '3':
				$instancia = '1';
				break;
			case '4':
				$instancia = '2';
				break;
			case '5':
				$instancia = '3';
				break;
			case '6':
				$instancia = '4';
				break;
		}
		if (!empty($alumno)) {
			$preinscripcion_ex = $this->preinscripcion_alumno_model->get_preinscripcion_alumno($alumno->id);
			if (!empty($preinscripcion_ex)) {
				if ($preinscripcion_ex->id === $escuela->id) {
					$this->session->set_flashdata('error', 'El alumno ya se encuentra preinscripto en esta escuela');
				} else {
					$this->session->set_flashdata('error', "El alumno ya se encuentra preinscripto en $preinscripcion_ex->numero $preinscripcion_ex->nombre");
				}
				redirect("preinscripciones/escuela/instancia_$instancia/$preinscripcion->ciclo_lectivo/$escuela->id/$redireccion", 'refresh');
			}
			$persona->observaciones = $alumno->observaciones;
			$persona->email_contacto = $alumno->email_contacto;
			$trayectoria = $this->alumno_division_model->get_trayectoria_alumno($alumno->id);
		} else {
			$trayectoria = array();
			$persona->observaciones = '';
			$persona->email_contacto = '';
		}
		$this->set_model_validation_rules($this->preinscripcion_alumno_model);
		$errors = FALSE;
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$alumno_id = $this->input->post('alumno_id');
				$solicitud = $this->preinscripcion_model->get_preinscripcion($preinscripcion_id);
				$trans_ok = TRUE;
				if (!empty($alumno) && $alumno->id !== $alumno_id) {
					show_error('Esta solicitud no pasó el control de seguridad.');
				}
				if ($trans_ok) {
					if (empty($alumno_id)) {
						$trans_ok &= $this->alumno_model->create(array('persona_id' => $persona_id), FALSE);
						$alumno_id = $this->alumno_model->get_row_id();
					}
					if (($solicitud->vacantes) > ($solicitud->inscriptos)) {
						$trans_ok &= $this->preinscripcion_alumno_model->create(array(
							'preinscripcion_id' => $preinscripcion->id,
							'alumno_id' => $alumno_id,
							'preinscripcion_tipo_id' => $tipo_id,
							'fecha_carga' => date('Y-m-d H:i:s'),
							'estado' => 'Inscripto',
							), FALSE);
						$preinscripcion_alumno_id = $this->preinscripcion_alumno_model->get_row_id();
					} else {
						$trans_ok &= $this->preinscripcion_alumno_model->create(array(
							'preinscripcion_id' => $preinscripcion->id,
							'alumno_id' => $alumno_id,
							'preinscripcion_tipo_id' => $tipo_id,
							'fecha_carga' => date('Y-m-d H:i:s'),
							'estado' => 'Postulante',
							), FALSE);
						$preinscripcion_alumno_id = $this->preinscripcion_alumno_model->get_row_id();
					}
				}

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->preinscripcion_alumno_model->get_msg());
					if ($this->input->post('editar') === '1') {
						redirect("preinscripciones/preinscripcion_alumno/editar/$preinscripcion_alumno_id", 'refresh');
					} else {
						$this->session->set_flashdata('abrir_modal', TRUE);
						if ($tipo_id <= '3') {
							redirect("preinscripciones/preinscripcion_alumno/editar/$preinscripcion_alumno_id#tab_familiares", 'refresh');
						} else {
							redirect("preinscripciones/preinscripcion_alumno/editar/$preinscripcion_alumno_id#tab_familiares", 'refresh');
						}
					}
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->preinscripcion_alumno_model->get_error())
						$errors .= '<br>' . $this->preinscripcion_alumno_model->get_error();
					if ($this->alumno_model->get_error())
						$errors .= '<br>' . $this->alumno_model->get_error();
				}
			}
		}

		$data['error'] = (validation_errors() ? validation_errors() : $errors);
		$persona->division = '';
		$data['fields_alumno'] = $this->build_fields($this->preinscripcion_alumno_model->fields_alumno, $persona);
		$data['fields'] = $this->build_fields($this->preinscripcion_alumno_model->fields);
		$data['escuela'] = $escuela;
		$data['persona'] = $persona;
		$data['redireccion'] = $redireccion;
		$data['alumno'] = $alumno;
		$data['trayectoria'] = $trayectoria;
		$data['preinscripcion'] = $preinscripcion;
		$data['instancia'] = $instancia;
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Agregar preinscripción de alumno';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_agregar_existente', $data);
	}

	public function agregar_nuevo($preinscripcion_id = NULL, $tipo_id = NULL, $redireccion = "escuela") {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $preinscripcion_id == NULL || !ctype_digit($preinscripcion_id) || $tipo_id == NULL || !in_array($tipo_id, array('1', '2', '3', '4', '5', '6'))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$preinscripcion = $this->preinscripcion_model->get_one($preinscripcion_id);
		if (empty($preinscripcion)) {
			show_error('No se encontró el registro de la división', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->load->model('documento_tipo_model');
		$this->load->model('nacionalidad_model');
		$this->load->model('grupo_sanguineo_model');
		$this->load->model('obra_social_model');
		$this->load->model('localidad_model');

		$model_alumno = new stdClass();
		$model_alumno->fields = array(
			'cuil' => array('label' => 'CUIL', 'maxlength' => '13'),
			'documento_tipo' => array('label' => 'Tipo de documento', 'input_type' => 'combo', 'id_name' => 'documento_tipo_id', 'required' => TRUE, 'array' => $this->get_array('documento_tipo', 'descripcion_corta', 'id', null, array('1' => ''))),
			'documento' => array('label' => 'Documento', 'type' => 'integer', 'maxlength' => '9', 'required' => TRUE),
			'apellido' => array('label' => 'Apellido', 'maxlength' => '100', 'required' => TRUE),
			'nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'required' => TRUE),
			'fecha_nacimiento' => array('label' => 'Fecha Nacimiento', 'type' => 'date'),
			'calle' => array('label' => 'Calle', 'maxlength' => '50'),
			'nacionalidad' => array('label' => 'Nacionalidad', 'input_type' => 'combo', 'id_name' => 'nacionalidad_id', 'array' => $this->get_array('nacionalidad', 'descripcion', 'id', null, array('' => ''))),
			'calle_numero' => array('label' => 'Número', 'maxlength' => '10'),
			'email_contacto' => array('label' => 'Email de Contacto / Notificaciones', 'type' => 'email', 'maxlength' => '150'),
			'grupo_sanguineo' => array('label' => 'Grupo sanguíneo', 'input_type' => 'combo', 'id_name' => 'grupo_sanguineo_id', 'array' => $this->get_array('grupo_sanguineo', 'descripcion', 'id', null, array('' => ''))),
			'obra_social' => array('label' => 'Obra social', 'input_type' => 'combo', 'id_name' => 'obra_social_id', 'array' => $this->get_array('obra_social', 'obra_social', 'id', array(
					'select' => array('obra_social.id', "CASE WHEN obra_social.descripcion_corta IS NULL OR obra_social.descripcion_corta='' THEN obra_social.descripcion ELSE obra_social.descripcion_corta END as obra_social")
					), array('' => ''))),
			'localidad' => array('label' => 'Localidad - Departamento', 'input_type' => 'combo', 'id_name' => 'localidad_id', 'array' => $this->get_array('localidad', 'localidad', 'id', array(
					'select' => array('localidad.id', "CONCAT(departamento.descripcion, ' - ', localidad.descripcion) as localidad"),
					'join' => array(array('departamento', 'departamento.id = localidad.departamento_id')),
					'sort_by' => 'departamento.descripcion, localidad.descripcion'
					), array('' => ''))),
			'barrio' => array('label' => 'Barrio', 'maxlength' => '45'),
			'manzana' => array('label' => 'Manzana', 'maxlength' => '5'),
			'casa' => array('label' => 'Casa', 'type' => 'integer', 'maxlength' => '3'),
			'departamento' => array('label' => 'Depto', 'maxlength' => '5'),
			'piso' => array('label' => 'Piso', 'maxlength' => '5'),
			'observaciones' => array('label' => 'Observaciones'),
		);

		$this->array_documento_tipo_control = $model_alumno->fields['documento_tipo']['array'];
		$this->array_nacionalidad_control = $model_alumno->fields['nacionalidad']['array'];
		$this->array_grupo_sanguineo_control = $model_alumno->fields['grupo_sanguineo']['array'];
		$this->array_obra_social_control = $model_alumno->fields['obra_social']['array'];
		$this->array_localidad_control = $model_alumno->fields['localidad']['array'];

		$this->set_model_validation_rules($model_alumno);
		$errors = FALSE;
		if ($this->form_validation->run() === TRUE) {
			$solicitud = $this->preinscripcion_model->get_preinscripcion($preinscripcion_id);
			$this->db->trans_begin();
			$trans_ok = TRUE;
			if ($trans_ok) {
				$trans_ok &= $this->persona_model->create(array(
					'cuil' => $this->input->post('cuil'),
					'documento_tipo_id' => $this->input->post('documento_tipo'),
					'documento' => $this->input->post('documento'),
					'apellido' => $this->input->post('apellido'),
					'nombre' => $this->input->post('nombre'),
					'nacionalidad_id' => $this->input->post('nacionalidad'),
					'fecha_nacimiento' => $this->get_date_sql('fecha_nacimiento'),
					'grupo_sanguineo_id' => $this->input->post('grupo_sanguineo'),
					'obra_social_id' => $this->input->post('obra_social'),
					'calle' => $this->input->post('calle'),
					'calle_numero' => $this->input->post('calle_numero'),
					'departamento' => $this->input->post('departamento'),
					'localidad_id' => $this->input->post('localidad'),
					'barrio' => $this->input->post('barrio'),
					'manzana' => $this->input->post('manzana'),
					'casa' => $this->input->post('casa'),
					'piso' => $this->input->post('piso')
					), FALSE);
				$persona_id = $this->persona_model->get_row_id();
				$trans_ok &= $this->alumno_model->create(array(
					'persona_id' => $persona_id,
					'email_contacto' => $this->input->post('email_contacto'),
					'observaciones' => $this->input->post('observaciones')
					), FALSE);
				$alumno_id = $this->alumno_model->get_row_id();
				if (($solicitud->vacantes) > ($solicitud->inscriptos)) {
					$trans_ok &= $this->preinscripcion_alumno_model->create(array(
						'preinscripcion_id' => $preinscripcion->id,
						'alumno_id' => $alumno_id,
						'preinscripcion_tipo_id' => $tipo_id,
						'fecha_carga' => date('Y-m-d H:i:s'),
						'estado' => 'Inscripto',
						), FALSE);
					$preinscripcion_alumno_id = $this->preinscripcion_alumno_model->get_row_id();
				} else {
					$trans_ok &= $this->preinscripcion_alumno_model->create(array(
						'preinscripcion_id' => $preinscripcion->id,
						'alumno_id' => $alumno_id,
						'preinscripcion_tipo_id' => $tipo_id,
						'fecha_carga' => date('Y-m-d H:i:s'),
						'estado' => 'Postulante',
						), FALSE);
					$preinscripcion_alumno_id = $this->preinscripcion_alumno_model->get_row_id();
				}
			}
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->preinscripcion_alumno_model->get_msg());
				if ($this->input->post('editar') === '1') {
					redirect("preinscripcione/preinscripcion_alumno/editar/$preinscripcion_alumno_id", 'refresh');
				} else {
					$this->session->set_flashdata('abrir_modal', TRUE);
					$this->session->set_flashdata('tipo_modal', $tipo_id);
					if ($tipo_id <= '3') {
						redirect("preinscripciones/preinscripcion_alumno/editar/$preinscripcion_alumno_id#tab_familiares", 'refresh');
					} else {
						redirect("preinscripciones/preinscripcion_alumno/editar/$preinscripcion_alumno_id#tab_familiares", 'refresh');
					}
				}
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al intentar agregar el alumno.';
				if ($this->preinscripcion_alumno_model->get_error())
					$errors .= '<br>' . $this->preinscripcion_alumno_model->get_error();
			}
		}

		$data['error'] = (validation_errors() ? validation_errors() : ($errors ? $errors : $this->session->flashdata('error')));


		$data['fields_alumno'] = $this->build_fields($model_alumno->fields);
		$data['fields'] = $this->build_fields($this->preinscripcion_alumno_model->fields);
		$data['escuela'] = $escuela;
		$data['redireccion'] = $redireccion;
		$data['preinscripcion'] = $preinscripcion;
		$data['instancia'] = $tipo_id <= '3' ? '1' : '2';
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Agregar alumno a preinscripción';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_agregar_nuevo', $data);
	}

	public function editar($preinscripcion_alumno_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $preinscripcion_alumno_id == NULL || !ctype_digit($preinscripcion_alumno_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$preinscripcion_alumno = $this->preinscripcion_alumno_model->get_one($preinscripcion_alumno_id);
		if (empty($preinscripcion_alumno)) {
			show_error('No se encontró el registro', 500, 'Registro no encontrado');
		}
		$preinscripcion = $this->preinscripcion_model->get_one($preinscripcion_alumno->preinscripcion_id);
		if (empty($preinscripcion)) {
			show_error('No se encontró el registro', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_model');
		$alumno = $this->alumno_model->get(array('id' => $preinscripcion_alumno->alumno_id, 'join' => array(
				array('persona', 'alumno.persona_id=persona.id', 'left', array('cuil', 'documento_tipo_id', 'documento', 'apellido', 'nombre', 'calle', 'calle_numero', 'departamento', 'piso', 'barrio', 'manzana', 'casa', 'localidad_id', 'sexo_id', 'estado_civil_id', 'nivel_estudio_id', 'ocupacion_id', 'telefono_fijo', 'telefono_movil', 'prestadora_id', 'fecha_nacimiento', 'fecha_defuncion', 'obra_social_id', 'contacto_id', 'grupo_sanguineo_id', 'depto_nacimiento_id', 'lugar_traslado_emergencia', 'nacionalidad_id', 'email'))
		)));
		if (empty($alumno)) {
			show_error('No se encontró el registro a editar', 500, 'Registro no encontrado');
		}

		$this->load->model('documento_tipo_model');
		$this->load->model('estado_civil_model');
		$this->load->model('grupo_sanguineo_model');
		$this->load->model('localidad_model');
		$this->load->model('nacionalidad_model');
		$this->load->model('nivel_estudio_model');
		$this->load->model('obra_social_model');
		$this->load->model('ocupacion_model');
		$this->load->model('prestadora_model');
		$this->load->model('sexo_model');

		$this->array_documento_tipo_control = $array_documento_tipo = $this->get_array('documento_tipo', 'descripcion_corta', 'id', null, array('' => '-- Seleccionar --'));
		$this->array_estado_civil_control = $array_estado_civil = $this->get_array('estado_civil', 'descripcion', 'id', null, array('' => '-- Seleccionar estado civil --'));
		$this->array_grupo_sanguineo_control = $array_grupo_sanguineo = $this->get_array('grupo_sanguineo', 'descripcion', 'id', null, array('' => '-- Seleccionar grupo sanguíneo --'));
		$this->array_localidad_control = $array_localidad = $this->get_array('localidad', 'localidad', 'id', array(
			'select' => array('localidad.id', "CONCAT(departamento.descripcion, ' - ', localidad.descripcion) as localidad"),
			'join' => array(array('departamento', 'departamento.id = localidad.departamento_id')),
			'sort_by' => 'departamento.descripcion, localidad.descripcion'
			), array('' => '-- Seleccionar localidad --'));
		$this->array_nacionalidad_control = $array_nacionalidad = $this->get_array('nacionalidad', 'descripcion', 'id', null, array('' => '-- Seleccionar nacionalidad --'));
		$this->array_nivel_estudio_control = $array_nivel_estudio = $this->get_array('nivel_estudio', 'descripcion', 'id', null, array('' => '-- Seleccionar nivel de estudios --'));
		$this->array_obra_social_control = $array_obra_social = $this->get_array('obra_social', 'obra_social', 'id', array(
			'select' => array('obra_social.id', "CASE WHEN obra_social.descripcion_corta IS NULL OR obra_social.descripcion_corta='' THEN obra_social.descripcion ELSE obra_social.descripcion_corta END as obra_social")
			), array('' => '-- Seleccionar obra social --'));
		$this->array_ocupacion_control = $array_ocupacion = $this->get_array('ocupacion', 'descripcion', 'id', null, array('' => '-- Seleccionar ocupación --'));
		$this->array_prestadora_control = $array_prestadora = $this->get_array('prestadora', 'descripcion', 'id', null, array('' => '-- Seleccionar prestadora --'));
		$this->array_sexo_control = $array_sexo = $this->get_array('sexo', 'descripcion', 'id', null, array('' => '-- Seleccionar sexo --'));
		unset($this->persona_model->fields['depto_nacimiento']);

		$this->set_model_validation_rules($this->alumno_model);
		$this->set_model_validation_rules($this->persona_model);
		$instancia = '0';
		switch ($preinscripcion_alumno->preinscripcion_tipo_id) {
			case '1':
			case '2':
			case '3':
				$instancia = '1';
				break;
			case '4':
				$instancia = '2';
				break;
			case '5':
				$instancia = '3';
				break;
			case '6':
				$instancia = '4';
				break;
		}

		$errors = '';
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->persona_model->update(array(
					'id' => $alumno->persona_id,
					'cuil' => $this->input->post('cuil'),
					'documento_tipo_id' => $this->input->post('documento_tipo'),
					'documento' => $this->input->post('documento'),
					'apellido' => $this->input->post('apellido'),
					'nombre' => $this->input->post('nombre'),
					'calle' => $this->input->post('calle'),
					'calle_numero' => $this->input->post('calle_numero'),
					'departamento' => $this->input->post('departamento'),
					'piso' => $this->input->post('piso'),
					'barrio' => $this->input->post('barrio'),
					'manzana' => $this->input->post('manzana'),
					'casa' => $this->input->post('casa'),
					'localidad_id' => $this->input->post('localidad'),
					'sexo_id' => $this->input->post('sexo'),
					'estado_civil_id' => $this->input->post('estado_civil'),
					'nivel_estudio_id' => $this->input->post('nivel_estudio'),
					'ocupacion_id' => $this->input->post('ocupacion'),
					'telefono_fijo' => $this->input->post('telefono_fijo'),
					'telefono_movil' => $this->input->post('telefono_movil'),
					'prestadora_id' => $this->input->post('prestadora'),
					'fecha_nacimiento' => $this->get_date_sql('fecha_nacimiento'),
					'fecha_defuncion' => $this->get_date_sql('fecha_defuncion'),
					'obra_social_id' => $this->input->post('obra_social'),
					'grupo_sanguineo_id' => $this->input->post('grupo_sanguineo'),
					'lugar_traslado_emergencia' => $this->input->post('lugar_traslado_emergencia'),
					'nacionalidad_id' => $this->input->post('nacionalidad')
					), FALSE);

				$trans_ok &= $this->alumno_model->update(array(
					'id' => $this->input->post('id'),
					'email_contacto' => $this->input->post('email_contacto'),
					'observaciones' => $this->input->post('observaciones'),
					), FALSE);
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->alumno_model->get_msg());
					redirect("preinscripciones/escuela/instancia_$instancia/$preinscripcion->ciclo_lectivo/$escuela->id/escuela", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->alumno_model->get_error())
						$errors .= '<br>' . $this->alumno_model->get_error();
					if ($this->persona_model->get_error())
						$errors .= '<br>' . $this->persona_model->get_error();
				}
			}
		}

		$data['error'] = (validation_errors() ? validation_errors() : (!empty($errors) ? $errors : $this->session->flashdata('error')));

		$this->alumno_model->fields['documento_tipo']['array'] = $array_documento_tipo;
		$this->alumno_model->fields['estado_civil']['array'] = $array_estado_civil;
		$this->alumno_model->fields['grupo_sanguineo']['array'] = $array_grupo_sanguineo;
		$this->alumno_model->fields['localidad']['array'] = $array_localidad;
		$this->alumno_model->fields['nacionalidad']['array'] = $array_nacionalidad;
		$this->alumno_model->fields['nivel_estudio']['array'] = $array_nivel_estudio;
		$this->alumno_model->fields['obra_social']['array'] = $array_obra_social;
		$this->alumno_model->fields['ocupacion']['array'] = $array_ocupacion;
		$this->alumno_model->fields['prestadora']['array'] = $array_prestadora;
		$this->alumno_model->fields['sexo']['array'] = $array_sexo;

		$data['fields'] = $this->build_fields($this->alumno_model->fields, $alumno);
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');

		$this->load->model('familia_model');
		$parientes = $this->familia_model->get_familiares($alumno->persona_id);

		$data['pariente_id'] = $this->session->flashdata('pariente_id');
		$data['abrir_modal'] = $this->session->flashdata('abrir_modal');
		$data['ciclo_lectivo'] = $preinscripcion->ciclo_lectivo;
		$data['escuela'] = $escuela;
		$data['parientes'] = $parientes;
		$data['trayectoria'] = $this->alumno_division_model->get_trayectoria_alumno($alumno->id);
		$data['alumno'] = $alumno;
		$data['preinscripcion_alumno_id'] = $preinscripcion_alumno_id;
		$data['preinscripcion'] = $preinscripcion;
		$data['instancia'] = $instancia;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar alumno';
		$this->load_template('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_editar', $data);
	}

	public function eliminar($preinscripcion_alumno_id = NULL) {
		if (($this->rol->codigo !== ROL_ADMIN && $this->rol->codigo !== ROL_USI) || $preinscripcion_alumno_id == NULL || !ctype_digit($preinscripcion_alumno_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$preinscripcion_alumno = $this->preinscripcion_alumno_model->get_one($preinscripcion_alumno_id);
		if (empty($preinscripcion_alumno)) {
			show_error('No se encontró el registro', 500, 'Registro no encontrado');
		}
		$preinscripcion = $this->preinscripcion_model->get_one($preinscripcion_alumno->preinscripcion_id);
		if (empty($preinscripcion)) {
			show_error('No se encontró el registro', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->db->trans_begin();
		$trans_ok = TRUE;
		$trans_ok &= $this->preinscripcion_alumno_model->update(array(
			'id' => $preinscripcion_alumno->id,
			'estado' => 'Anulado'
			), FALSE);
		if ($this->db->trans_status() && $trans_ok) {
			$this->db->trans_commit();
			$this->session->set_flashdata('message', 'Alumno preinscripto eliminado correctamente');
			redirect("preinscripciones/escuela/instancia_1/$preinscripcion->ciclo_lectivo/$escuela->id/escuela", 'refresh');
		} else {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', $this->preinscripcion_alumno_model->get_error());
			redirect("preinscripciones/escuela/instancia_1/$preinscripcion->ciclo_lectivo/$escuela->id/escuela", 'refresh');
		}
	}

	public function modal_eliminar_preinscripcion_instancia1($preinscripcion_alumno_id = NULL) {
		if (!in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION)) || $preinscripcion_alumno_id == NULL || !ctype_digit($preinscripcion_alumno_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$preinscripcion_alumno = $this->preinscripcion_alumno_model->get_one($preinscripcion_alumno_id);
		if (empty($preinscripcion_alumno)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}
		$preinscripcion = $this->preinscripcion_model->get_one($preinscripcion_alumno->preinscripcion_id);
		if (empty($preinscripcion)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$alumno = $this->preinscripcion_alumno_model->get_alumno_preinscripto($preinscripcion_alumno->alumno_id);
		if (isset($_POST) && !empty($_POST)) {
			if ($preinscripcion_alumno->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$trans_ok &= $this->preinscripcion_alumno_model->update(array(
				'id' => $preinscripcion_alumno->id,
				'estado' => 'Anulado'
				), FALSE);
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Alumno preinscripto eliminado correctamente');
				redirect("preinscripciones/escuela/instancia_1/$preinscripcion->ciclo_lectivo/$escuela->id/escuela", 'refresh');
			} else {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', $this->preinscripcion_alumno_model->get_error());
				redirect("preinscripciones/escuela/instancia_1/$preinscripcion->ciclo_lectivo/$escuela->id/escuela", 'refresh');
			}
		}

		$data['preinscripcion_alumno'] = $preinscripcion_alumno;
		$data['alumno'] = $alumno;
		$data['fields'] = $this->build_fields($this->preinscripcion_alumno_model->fields_alumno_preinscripto, $alumno, TRUE);
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar alumno preinscripto';
		$this->load->view('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_eliminar', $data);
	}

	public function modal_eliminar_preinscripcion_instancia2($preinscripcion_alumno_id = NULL) {
		if (!in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION)) || $preinscripcion_alumno_id == NULL || !ctype_digit($preinscripcion_alumno_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$preinscripcion_alumno = $this->preinscripcion_alumno_model->get_one($preinscripcion_alumno_id);
		if (empty($preinscripcion_alumno)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}
		$preinscripcion = $this->preinscripcion_model->get_one($preinscripcion_alumno->preinscripcion_id);
		if (empty($preinscripcion)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$alumno = $this->preinscripcion_alumno_model->get_alumno_preinscripto($preinscripcion_alumno->alumno_id);
		if (isset($_POST) && !empty($_POST)) {
			if ($preinscripcion_alumno->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$trans_ok &= $this->preinscripcion_alumno_model->update(array(
				'id' => $preinscripcion_alumno->id,
				'estado' => 'Anulado'
				), FALSE);
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Alumno preinscripto eliminado correctamente');
				redirect("preinscripciones/escuela/instancia_2/$preinscripcion->ciclo_lectivo/$escuela->id/escuela", 'refresh');
			} else {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', $this->preinscripcion_alumno_model->get_error());
				redirect("preinscripciones/escuela/instancia_2/$preinscripcion->ciclo_lectivo/$escuela->id/escuela", 'refresh');
			}
		}

		$data['preinscripcion_alumno'] = $preinscripcion_alumno;
		$data['alumno'] = $alumno;
		$data['fields'] = $this->build_fields($this->preinscripcion_alumno_model->fields_alumno_preinscripto, $alumno, TRUE);
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar alumno preinscripto';
		$this->load->view('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_eliminar', $data);
	}

	public function modal_eliminar_preinscripcion_instancia4($preinscripcion_alumno_id = NULL) {
		if (!in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION)) || $preinscripcion_alumno_id == NULL || !ctype_digit($preinscripcion_alumno_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$preinscripcion_alumno = $this->preinscripcion_alumno_model->get_one($preinscripcion_alumno_id);
		if (empty($preinscripcion_alumno)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}
		$preinscripcion = $this->preinscripcion_model->get_one($preinscripcion_alumno->preinscripcion_id);
		if (empty($preinscripcion)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$alumno = $this->preinscripcion_alumno_model->get_alumno_preinscripto($preinscripcion_alumno->alumno_id);
		if (isset($_POST) && !empty($_POST)) {
			if ($preinscripcion_alumno->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$this->db->trans_begin();
			$trans_ok = TRUE;
			$trans_ok &= $this->preinscripcion_alumno_model->update(array(
				'id' => $preinscripcion_alumno->id,
				'estado' => 'Anulado'
				), FALSE);
			if ($this->db->trans_status() && $trans_ok) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'Alumno preinscripto eliminado correctamente');
				redirect("preinscripciones/escuela/instancia_4/$preinscripcion->ciclo_lectivo/$escuela->id/escuela", 'refresh');
			} else {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', $this->preinscripcion_alumno_model->get_error());
				redirect("preinscripciones/escuela/instancia_4/$preinscripcion->ciclo_lectivo/$escuela->id/escuela", 'refresh');
			}
		}

		$data['preinscripcion_alumno'] = $preinscripcion_alumno;
		$data['alumno'] = $alumno;
		$data['fields'] = $this->build_fields($this->preinscripcion_alumno_model->fields_alumno_preinscripto, $alumno, TRUE);
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar alumno preinscripto';
		$this->load->view('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_eliminar', $data);
	}

	public function modal_alumno_familia_buscar($preinscripcion_alumno_id = NULL, $persona_id = '0') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $preinscripcion_alumno_id == NULL || !ctype_digit($preinscripcion_alumno_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$preinscripcion_alumno = $this->preinscripcion_alumno_model->get_one($preinscripcion_alumno_id);
		if (empty($preinscripcion_alumno)) {
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}
		$preinscripcion = $this->preinscripcion_model->get_one($preinscripcion_alumno->preinscripcion_id);
		if (empty($preinscripcion)) {
			show_error('No se encontró el registro a eliminar', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('alumno_model');
		$model_busqueda = new stdClass();
		$model_busqueda->fields = array(
			'd_documento' => array('label' => 'Documento', 'maxlength' => '9'),
			'd_apellido' => array('label' => 'Apellido', 'maxlength' => '100', 'minlength' => '3'),
			'd_nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'minlength' => '3'),
			'persona_seleccionada' => array('label' => '', 'type' => 'integer', 'required' => TRUE),
		);

		$this->set_model_validation_rules($model_busqueda);
		if (isset($_POST) && !empty($_POST)) {
			$pariente_id = $this->input->post('persona_id');
			if ($pariente_id !== '') {
				$this->session->set_flashdata('pariente_id', $pariente_id);
			}
			redirect("preinscripciones/preinscripcion_alumno/editar/$preinscripcion_alumno_id#tab_familiares", 'refresh');
		}

		$data['fields'] = $this->build_fields($model_busqueda->fields);
		$data['escuela'] = $escuela;
		$data['preinscripcion'] = $preinscripcion;
		$data['preinscripcion_alumno'] = $preinscripcion_alumno;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Buscar familiar a agregar';
		$this->load->view('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_familia_buscar_modal', $data);
	}

	public function modal_agregar_familiar_existente($preinscripcion_alumno_id = NULL, $pariente_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $preinscripcion_alumno_id == NULL || !ctype_digit($preinscripcion_alumno_id) || $pariente_id == NULL || !ctype_digit($pariente_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$persona = $this->persona_model->get_one($pariente_id);
		if (empty($persona)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}
		$preinscripcion_alumno = $this->preinscripcion_alumno_model->get_one($preinscripcion_alumno_id);
		if (empty($preinscripcion_alumno)) {
			show_error('No se encontró el registro', 500, 'Registro no encontrado');
		}
		$preinscripcion = $this->preinscripcion_model->get_one($preinscripcion_alumno->preinscripcion_id);
		if (empty($preinscripcion)) {
			show_error('No se encontró el registro', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$alumno = $this->alumno_model->get(array('id' => $preinscripcion_alumno->alumno_id));
		if (empty($alumno)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}

		$this->load->model('parentesco_tipo_model');
		$this->load->model('familia_model');
		$hijos = $this->familia_model->get_hijos($pariente_id);

		$persona_model = new stdClass();
		$persona_model->fields = array(
			'documento_tipo' => array('label' => 'Tipo', 'readonly' => TRUE),
			'documento' => array('label' => 'Documento', 'readonly' => TRUE),
			'apellido' => array('label' => 'Apellido', 'readonly' => TRUE),
			'nombre' => array('label' => 'Nombre', 'readonly' => TRUE),
			'nivel_estudio' => array('label' => 'Nivel de estudios', 'readonly' => TRUE),
			'ocupacion' => array('label' => 'Ocupación', 'readonly' => TRUE),
			'prestadora' => array('label' => 'Prestadora', 'readonly' => TRUE),
			'telefono_movil' => array('label' => 'Celular', 'readonly' => TRUE),
			'email' => array('label' => 'Email', 'readonly' => TRUE)
		);


		$this->array_parentesco_tipo_control = $this->familia_model->fields['parentesco_tipo']['array'] = $this->get_array('parentesco_tipo', 'descripcion', 'id', null, array('' => '-- Seleccionar parentesco --'));
		$this->array_convive_control = $this->familia_model->fields['convive']['array'];

		$this->set_model_validation_rules($this->familia_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				if ($pariente_id !== $this->input->post('persona_id')) {
					show_error('Esta solicitud no pasó el control de seguridad.');
				}
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->familia_model->create(array(
					'persona_id' => $alumno->persona_id,
					'pariente_id' => $pariente_id,
					'parentesco_tipo_id' => $this->input->post('parentesco_tipo'),
					'convive' => $this->input->post('convive')
					), FALSE);

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->familia_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar el familiar.';
					if ($this->familia_model->get_error())
						$errors .= '<br>' . $this->familia_model->get_error();
					$this->session->set_flashdata('error', $errors);
				}
			}else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect("preinscripciones/preinscripcion_alumno/editar/$preinscripcion_alumno_id#tab_familiares", 'refresh');
		}

		$data['fields_p'] = $this->build_fields($persona_model->fields, $persona, TRUE);
		$data['fields'] = $this->build_fields($this->familia_model->fields);
		$data['hijos'] = $hijos;
		$data['persona'] = $persona;
		$data['alumno'] = $alumno;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar nuevo familiar';
		$this->load->view('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_agregar_familiar_existente', $data);
	}

	public function modal_agregar_familiar_nuevo($preinscripcion_alumno_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $preinscripcion_alumno_id == NULL || !ctype_digit($preinscripcion_alumno_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$preinscripcion_alumno = $this->preinscripcion_alumno_model->get_one($preinscripcion_alumno_id);
		if (empty($preinscripcion_alumno)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}
		$preinscripcion = $this->preinscripcion_model->get_one($preinscripcion_alumno->preinscripcion_id);
		if (empty($preinscripcion)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$alumno = $this->alumno_model->get(array('id' => $preinscripcion_alumno->alumno_id));
		if (empty($alumno)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}

		$this->load->model('documento_tipo_model');
		$this->load->model('ocupacion_model');
		$this->load->model('prestadora_model');
		$this->load->model('nivel_estudio_model');
		$this->load->model('familia_model');
		$this->load->model('parentesco_tipo_model');

		$model = new stdClass();
		$model->fields = array(
			'p_documento_tipo' => array('label' => 'Tipo de documento', 'input_type' => 'combo', 'id_name' => 'documento_tipo_id', 'required' => TRUE),
			'p_documento' => array('label' => 'Documento', 'type' => 'integer', 'maxlength' => '9', 'required' => TRUE),
			'p_apellido' => array('label' => 'Apellido', 'maxlength' => '100', 'required' => TRUE),
			'p_nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'required' => TRUE),
			'p_nivel_estudio' => array('label' => 'Nivel de estudios', 'input_type' => 'combo', 'id_name' => 'nivel_estudio_id'),
			'p_ocupacion' => array('label' => 'Ocupación', 'input_type' => 'combo', 'id_name' => 'ocupacion_id'),
			'p_prestadora' => array('label' => 'Prestadora', 'input_type' => 'combo', 'id_name' => 'prestadora_id'),
			'p_telefono_movil' => array('label' => 'Celular', 'maxlength' => '40'),
			'p_email' => array('label' => 'Email', 'maxlength' => '100')
		);

		$this->array_p_documento_tipo_control = $model->fields['p_documento_tipo']['array'] = $this->get_array('documento_tipo', 'descripcion_corta', 'id', null, array('1' => ''));
		$this->array_p_ocupacion_control = $model->fields['p_ocupacion']['array'] = $this->get_array('ocupacion', 'descripcion', 'id', null, array('' => '-- Seleccionar ocupación --'));
		$this->array_p_prestadora_control = $model->fields['p_prestadora']['array'] = $this->get_array('prestadora', 'descripcion', 'id', null, array('' => '-- Seleccionar prestadora --'));
		$this->array_p_nivel_estudio_control = $model->fields['p_nivel_estudio']['array'] = $this->get_array('nivel_estudio', 'descripcion', 'id', null, array('' => '-- Seleccionar nivel de estudios --'));
		$this->array_parentesco_tipo_control = $this->familia_model->fields['parentesco_tipo']['array'] = $this->get_array('parentesco_tipo', 'descripcion', 'id', null, array('' => '-- Seleccionar parentesco --'));
		$this->array_convive_control = $this->familia_model->fields['convive']['array'];

		$this->set_model_validation_rules($model);
		$this->set_model_validation_rules($this->familia_model);

		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->persona_model->create(array(
					'documento_tipo_id' => $this->input->post('p_documento_tipo'),
					'documento' => $this->input->post('p_documento'),
					'nombre' => $this->input->post('p_nombre'),
					'apellido' => $this->input->post('p_apellido'),
					'nivel_estudio_id' => $this->input->post('p_nivel_estudio'),
					'ocupacion_id' => $this->input->post('p_ocupacion'),
					'prestadora_id' => $this->input->post('p_prestadora'),
					'telefono_movil' => $this->input->post('p_telefono_movil'),
					'email' => $this->input->post('p_email')
					), FALSE);
				$pariente_id = $this->persona_model->get_row_id();

				$trans_ok &= $this->familia_model->create(array(
					'persona_id' => $alumno->persona_id,
					'pariente_id' => $pariente_id,
					'parentesco_tipo_id' => $this->input->post('parentesco_tipo'),
					'convive' => $this->input->post('convive')
					), FALSE);

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->familia_model->get_msg());
					redirect("preinscripciones/preinscripcion_alumno/editar/$preinscripcion_alumno_id#tab_familiares", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar el familiar.';
					if ($this->persona_model->get_error())
						$errors .= '<br>' . $this->persona_model->get_error();
					if ($this->familia_model->get_error())
						$errors .= '<br>' . $this->familia_model->get_error();
					$this->session->set_flashdata('error', $errors);
				}
			}else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect("preinscripciones/preinscripcion_alumno/editar/$preinscripcion_alumno_id#tab_familiares", 'refresh');
		}

		$data['fields_p'] = $this->build_fields($model->fields);
		$data['fields'] = $this->build_fields($this->familia_model->fields);

		$data['alumno'] = $alumno;
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar nuevo familiar';
		$this->load->view('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_agregar_familiar_nuevo', $data);
	}

	public function modal_eliminar_familiar($preinscripcion_alumno_id = NULL, $familia_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $preinscripcion_alumno_id == NULL || !ctype_digit($preinscripcion_alumno_id) || $familia_id == NULL || !ctype_digit($familia_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$preinscripcion_alumno = $this->preinscripcion_alumno_model->get_one($preinscripcion_alumno_id);
		if (empty($preinscripcion_alumno)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}
		$preinscripcion = $this->preinscripcion_model->get_one($preinscripcion_alumno->preinscripcion_id);
		if (empty($preinscripcion)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$this->load->model('familia_model');
		$familia = $this->familia_model->get_one($familia_id);
		$hijos = $this->familia_model->get_hijos($familia->pariente_id);
		if (empty($familia)) {
			$this->modal_error('No se encontró el registro del familiar a eliminar', 'Registro no encontrado');
			return;
		}

		$familia->convive = $this->familia_model->fields['convive']['array'][$familia->convive];

		$persona = $this->persona_model->get_one($familia->pariente_id);
		if (empty($persona)) {
			$this->modal_error('No se encontró el registro de la persona', 'Registro no encontrado');
			return;
		}

		unset($this->persona_model->fields['cuil']);
		unset($this->persona_model->fields['calle']);
		unset($this->persona_model->fields['calle_numero']);
		unset($this->persona_model->fields['departamento']);
		unset($this->persona_model->fields['piso']);
		unset($this->persona_model->fields['barrio']);
		unset($this->persona_model->fields['manzana']);
		unset($this->persona_model->fields['casa']);
		unset($this->persona_model->fields['localidad']);
		unset($this->persona_model->fields['sexo']);
		unset($this->persona_model->fields['estado_civil']);
		unset($this->persona_model->fields['telefono_fijo']);
		unset($this->persona_model->fields['fecha_nacimiento']);
		unset($this->persona_model->fields['fecha_defuncion']);
		unset($this->persona_model->fields['obra_social']);
		unset($this->persona_model->fields['grupo_sanguineo']);
		unset($this->persona_model->fields['depto_nacimiento']);
		unset($this->persona_model->fields['lugar_traslado_emergencia']);
		unset($this->persona_model->fields['nacionalidad']);

		if (isset($_POST) && !empty($_POST)) {
			if ($familia->id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->familia_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->familia_model->get_msg());
				redirect("preinscripciones/preinscripcion_alumno/editar/$preinscripcion_alumno_id#tab_familiares", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona, TRUE);
		$data['fields_familia'] = $this->build_fields($this->familia_model->fields, $familia, TRUE);
		$data['hijos'] = $hijos;
		$data['familia'] = $familia;
		$data['persona'] = $persona;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar familiar';
		$this->load->view('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_familia', $data);
	}

	public function modal_editar_familiar($preinscripcion_alumno_id = NULL, $familia_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $preinscripcion_alumno_id == NULL || !ctype_digit($preinscripcion_alumno_id) || $familia_id == NULL || !ctype_digit($familia_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$preinscripcion_alumno = $this->preinscripcion_alumno_model->get_one($preinscripcion_alumno_id);
		if (empty($preinscripcion_alumno)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}
		$preinscripcion = $this->preinscripcion_model->get_one($preinscripcion_alumno->preinscripcion_id);
		if (empty($preinscripcion)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$this->load->model('familia_model');
		$familia = $this->familia_model->get(array('id' => $familia_id));
		if (empty($familia)) {
			$this->modal_error('No se encontró el registro del familiar', 'Registro no encontrado');
			return;
		}

		$hijos = $this->familia_model->get_hijos($familia->pariente_id);

		$persona = $this->persona_model->get(array('id' => $familia->pariente_id));
		if (empty($persona)) {
			$this->modal_error('No se encontró el registro de la persona', 'Registro no encontrado');
			return;
		}

		$this->load->model('ocupacion_model');
		$this->load->model('documento_tipo_model');
		$this->load->model('parentesco_tipo_model');
		$this->load->model('nivel_estudio_model');
		$this->load->model('prestadora_model');

		$this->array_documento_tipo_control = $array_documento_tipo = $this->get_array('documento_tipo', 'descripcion_corta', 'id', null, array('' => '-- Seleccionar --'));
		$this->array_nivel_estudio_control = $array_nivel_estudio = $this->get_array('nivel_estudio', 'descripcion', 'id', null, array('' => ''));
		$this->array_prestadora_control = $array_prestadora = $this->get_array('prestadora', 'descripcion', 'id', null, array('' => ''));
		$this->array_ocupacion_control = $array_ocupacion = $this->get_array('ocupacion', 'descripcion', 'id', null, array('' => ''));
		$this->array_convive_control = $this->familia_model->fields['convive']['array'];
		$this->array_parentesco_tipo_control = $array_parentesco_tipo = $this->get_array('parentesco_tipo', 'descripcion');

		unset($this->persona_model->fields['cuil']);
		unset($this->persona_model->fields['calle']);
		unset($this->persona_model->fields['calle_numero']);
		unset($this->persona_model->fields['departamento']);
		unset($this->persona_model->fields['piso']);
		unset($this->persona_model->fields['barrio']);
		unset($this->persona_model->fields['manzana']);
		unset($this->persona_model->fields['casa']);
		unset($this->persona_model->fields['localidad']);
		unset($this->persona_model->fields['sexo']);
		unset($this->persona_model->fields['estado_civil']);
		unset($this->persona_model->fields['telefono_fijo']);
		unset($this->persona_model->fields['fecha_nacimiento']);
		unset($this->persona_model->fields['fecha_defuncion']);
		unset($this->persona_model->fields['obra_social']);
		unset($this->persona_model->fields['grupo_sanguineo']);
		unset($this->persona_model->fields['depto_nacimiento']);
		unset($this->persona_model->fields['lugar_traslado_emergencia']);
		unset($this->persona_model->fields['nacionalidad']);

		$this->set_model_validation_rules($this->familia_model);
		$this->set_model_validation_rules($this->persona_model);

		if (isset($_POST) && !empty($_POST)) {
			if ($familia_id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->persona_model->update(array(
					'id' => $familia->pariente_id,
					'nombre' => $this->input->post('nombre'),
					'apellido' => $this->input->post('apellido'),
					'documento' => $this->input->post('documento'),
					'nivel_estudio_id' => $this->input->post('nivel_estudio'),
					'ocupacion_id' => $this->input->post('ocupacion'),
					'telefono_movil' => $this->input->post('telefono_movil'),
					'email' => $this->input->post('email'),
					'prestadora_id' => $this->input->post('prestadora')
					), FALSE);

				$trans_ok &= $this->familia_model->update(array(
					'id' => $familia_id,
					'parentesco_tipo_id' => $this->input->post('parentesco_tipo'),
					'convive' => $this->input->post('convive')
					), FALSE);

				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->familia_model->get_msg());
					redirect("preinscripciones/preinscripcion_alumno/editar/$preinscripcion_alumno_id#tab_familiares", 'refresh');
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar el familiar.';
					if ($this->persona_model->get_error())
						$errors .= '<br>' . $this->persona_model->get_error();
					if ($this->familia_model->get_error())
						$errors .= '<br>' . $this->familia_model->get_error();
					$this->session->set_flashdata('error', $errors);
					redirect("preinscripciones/preinscripcion_alumno/editar/$preinscripcion_alumno_id#tab_familiares", 'refresh');
				}
			}else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("preinscripciones/preinscripcion_alumno/editar/$preinscripcion_alumno_id#tab_familiares", 'refresh');
			}
		}

		$this->persona_model->fields['documento_tipo']['array'] = $array_documento_tipo;
		$this->persona_model->fields['nivel_estudio']['array'] = $array_nivel_estudio;
		$this->persona_model->fields['prestadora']['array'] = $array_prestadora;
		$this->persona_model->fields['ocupacion']['array'] = $array_ocupacion;
		$this->familia_model->fields['parentesco_tipo']['array'] = $array_parentesco_tipo;

		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona);
		$data['fields_familia'] = $this->build_fields($this->familia_model->fields, $familia);
		$data['hijos'] = $hijos;
		$data['familia'] = $familia;
		$data['persona'] = $persona;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar valores del familiar';
		$this->load->view('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_familia', $data);
	}

	public function modal_editar_preinscripcion_tipo($preinscripcion_alumno_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $preinscripcion_alumno_id == NULL || !ctype_digit($preinscripcion_alumno_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$preinscripcion_alumno = $this->preinscripcion_alumno_model->get_one($preinscripcion_alumno_id);
		if (empty($preinscripcion_alumno)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}
		$preinscripcion = $this->preinscripcion_model->get_one($preinscripcion_alumno->preinscripcion_id);
		if (empty($preinscripcion)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}
		$alumno = $this->alumno_model->get(array('id' => $preinscripcion_alumno->alumno_id));
		if (empty($alumno)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$persona = $this->persona_model->get_one($alumno->persona_id);
		if (empty($persona)) {
			show_error('No se encontró el registro de la persona', 500, 'Registro no encontrado');
		}

		$this->preinscripcion_alumno_model->fields['preinscripcion_tipo'] = array('label' => 'Tipo de preinscripción', 'input_type' => 'combo', 'id_name' => 'preinscripcion_tipo_id', 'required' => TRUE, 'array' => array('1' => 'Hermanos de alumnos', '2' => 'Alumnos de jardín anexo / nucleado', '3' => 'Hijos de personal'));

		unset($this->preinscripcion_alumno_model->fields['escuela']);
		unset($this->preinscripcion_alumno_model->fields['ciclo_lectivo']);
		unset($this->preinscripcion_alumno_model->fields['ciclo_lectivo']);
		unset($this->preinscripcion_alumno_model->fields_alumno['email_contacto']);
		unset($this->preinscripcion_alumno_model->fields_alumno['observaciones']);

		$this->array_preinscripcion_tipo_control = $this->preinscripcion_alumno_model->fields['preinscripcion_tipo']['array'];

		$this->set_model_validation_rules($this->preinscripcion_alumno_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($preinscripcion_alumno->id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;
				$trans_ok &= $this->preinscripcion_alumno_model->update(array(
					'id' => $preinscripcion_alumno_id,
					'preinscripcion_tipo_id' => $this->input->post('preinscripcion_tipo')
					), FALSE);
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', 'Tipo de preinscripción modificado correctamente');
					redirect("preinscripciones/escuela/instancia_1/$preinscripcion->ciclo_lectivo/$escuela->id/escuela", 'refresh');
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', validation_errors());
					redirect("preinscripciones/escuela/instancia_1/$preinscripcion->ciclo_lectivo/$escuela->id/escuela", 'refresh');
				}
			}
		}

		$this->preinscripcion_alumno_model->fields['preinscripcion_tipo']['value'] = $preinscripcion_alumno->preinscripcion_tipo_id;

		$data['fields'] = $this->build_fields($this->preinscripcion_alumno_model->fields);
		$data['fields_alumno'] = $this->build_fields($this->preinscripcion_alumno_model->fields_alumno, $persona);
		$data['preinscripcion_alumno'] = $preinscripcion_alumno;
		$data['persona'] = $persona;
		$data['txt_btn'] = 'Guardar';
		$data['title'] = 'Modificar tipo de preinscripción';
		$this->load->view('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_editar_tipo', $data);
	}

	public function excel($nivel_id = NULL, $dependencia_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $nivel_id == NULL || $dependencia_id == NULL || !ctype_digit($nivel_id) || !ctype_digit($dependencia_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $nivel_id == NULL || $dependencia_id == NULL || !ctype_digit($nivel_id) || !ctype_digit($dependencia_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$campos = array(
			'A' => array('Supervisiones', 20),
			'B' => array('Escuelas', 10),
			'C' => array('Divisiones', 10),
			'D' => array('Alumnos', 10),
			'E' => array('Declaradas', 13),
			'F' => array('1° Ins', 6),
			'G' => array('2° Ins', 6),
			'H' => array('3° Ins', 6),
			'I' => array('Total', 6),
			'J' => array('Disponibles', 13),
		);

		$datos_supervisiones = $this->db->select('s.nombre, COUNT(DISTINCT e.id) escuelas, COUNT(DISTINCT d.id) divisiones, COUNT(DISTINCT ad.id) alumnos, p.vacantes,  pa.instancia_1, pa.instancia_2, pa.instancia_3, pa.inscriptos, (p.vacantes-pa.instancia_1-pa.instancia_2-pa.instancia_3) as vacantes_disponibles')
				->from('escuela e')
				->join('supervision s', 's.id = e.supervision_id', 'left')
				->join('division d', 'd.escuela_id = e.id  AND d.curso_id=7', 'left')
				->join('alumno_division ad', 'ad.division_id = d.id AND ad.fecha_hasta IS NULL', 'left')
				->join('(SELECT e.supervision_id, COUNT(DISTINCT e.id) escuelas_p, SUM(p.vacantes) vacantes FROM preinscripcion p JOIN escuela e ON p.escuela_id = e.id GROUP BY e.supervision_id) p', 'p.supervision_id = s.id', 'left')
				->join('(SELECT e.supervision_id, COALESCE(SUM(CASE pa.estado WHEN "Inscripto" THEN 1 ELSE 0 END), 0) inscriptos, COALESCE(SUM(CASE pa.estado WHEN "Postulante" THEN 1 ELSE 0 END), 0) postulantes, COALESCE(SUM(CASE WHEN pa.preinscripcion_tipo_id <= 3 THEN 1 ELSE 0 END),	0) instancia_1, COALESCE(SUM(CASE pa.preinscripcion_tipo_id WHEN 4 THEN 1 ELSE 0 END),0) instancia_2, COALESCE(SUM(CASE pa.preinscripcion_tipo_id WHEN 5 THEN 1 ELSE 0 END),0) instancia_3 FROM preinscripcion p JOIN preinscripcion_alumno pa ON p.id = pa.preinscripcion_id JOIN escuela e ON p.escuela_id = e.id GROUP BY e.supervision_id) pa', 'pa.supervision_id = s.id', 'left')
				->where('e.nivel_id', $nivel_id)
				->where('e.dependencia_id', $dependencia_id)
				->where('e.nivel_id = s.nivel_id AND e.dependencia_id = s.dependencia_id')
				->group_by('s.id')
				->order_by('s.orden')
				->get()->result_array();


		if (!empty($datos_supervisiones)) {
			if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
				show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
			}

			$this->load->library('PHPExcel');
			$this->phpexcel->setActiveSheetIndex(0);

			$sheet = $this->phpexcel->getActiveSheet();
			$sheet->setTitle("Ingreso a primer grado 2018");
			$encabezado = array();
			$ultima_columna = 'A';
			foreach ($campos as $columna => $campo) {
				$encabezado[] = $campo[0];
				$sheet->getColumnDimension($columna)->setWidth($campo[1]);
				$ultima_columna = $columna;
			}

			$sheet->setCellValue('A1', 'INGRESO A PRIMER GRADO 2018');
			$sheet->fromArray(array($encabezado), NULL, 'A3');
			$sheet->fromArray($datos_supervisiones, NULL, 'A5');

			$sheet->mergeCells('A1:J1');
			$sheet->getStyle('A1')->getFont()->setBold(true);
			$sheet->getStyle('A2:J2')->getFont()->setBold(true);
			$sheet->getStyle('A3:J3')->getFont()->setBold(true);
			$sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$sheet->setCellValue("C2", '1er Grado 2017');
			$sheet->setCellValue("E2", 'Vacantes');
			$sheet->setCellValue("F2", 'Inscriptos');
			$sheet->setCellValue("J2", 'Vacantes');
			$sheet->mergeCells('C2:D2');
			$sheet->mergeCells('F2:I2');


			$ultima_fila = $sheet->getHighestRow();
			for ($i = 6; $i <= $ultima_fila; $i++) {
				$sheet->setCellValue("B$i", $i - 5);
			}
			for ($i = 2; $i <= $ultima_fila; $i++) {
				$sheet->getStyle("B$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle("C$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle("D$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle("E$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle("F$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle("G$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle("H$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle("I$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle("J$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			}
			$sheet->getStyle("A3:J$ultima_fila")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sheet->getStyle("A4:J$ultima_fila")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle("G5:J$ultima_fila")->getAlignment()->setWrapText(true);


			$sheet->setCellValue("A4", 'TOTAL GENERAL');
			$sheet->setCellValue("B4", "=SUM(B5:B" . ($ultima_fila) . ")");
			$sheet->setCellValue("C4", "=SUM(C5:C" . ($ultima_fila) . ")");
			$sheet->setCellValue("D4", "=SUM(D5:D" . ($ultima_fila) . ")");
			$sheet->setCellValue("E4", "=SUM(E5:E" . ($ultima_fila) . ")");
			$sheet->setCellValue("F4", "=SUM(F5:F" . ($ultima_fila) . ")");
			$sheet->setCellValue("G4", "=SUM(G5:G" . ($ultima_fila) . ")");
			$sheet->setCellValue("H4", "=SUM(H5:H" . ($ultima_fila) . ")");
			$sheet->setCellValue("I4", "=SUM(I4:I" . ($ultima_fila) . ")");
			$sheet->setCellValue("J4", '=CONCATENATE(SUM(J5:J' . ($ultima_fila) . ')," (",TEXT(SUM(J5:J' . ($ultima_fila) . ')/E4' . ',"0,00%"),CHAR(41))');
			$sheet->getStyle("A4:J4")->getFont()->setBold(true);
			$sheet->getStyle("A4:J4")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sheet->getStyle("A4:J4")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('DEDBDB');

			header("Content-Type: application/vnd.ms-excel");
			$nombreArchivo = "Ingreso a primer grado 2018";
			header("Content-Disposition: attachment; filename=\"$nombreArchivo.xls\"");
			header("Cache-Control: max-age=0");

			$writer = PHPExcel_IOFactory::createWriter($this->phpexcel, "Excel5");
			$writer->save('php://output');
			exit;
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect("escritorio", 'refresh');
		}
	}
}
/* End of file Preinscripcion_alumno.php */
/* Location: ./application/modules/preinscripciones/controllers/Preinscripcion_alumno.php */