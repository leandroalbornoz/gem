<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escuela extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->load->model('preinscripciones/preinscripcion_model');
		$this->load->model('preinscripciones/preinscripcion_calendario_model');
		$this->instancias = $this->preinscripcion_calendario_model->get_instancias(date('Y-m-d'), 1);
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE_CURSADA));
		$this->roles_editar_vacantes = array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION);
		if (in_array($this->rol->codigo, array(ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_GRUPO_ESCUELA_CONSULTA, ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'admin/preinscripcion';
	}

	public function instancia($instancia = NULL, $preinscripcion_id = NULL, $redireccion = 'escuela') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $preinscripcion_id == NULL || !ctype_digit($preinscripcion_id) || $instancia == NULL || !ctype_digit($instancia)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$preinscripcion = $this->preinscripcion_model->get(array(
				'join' => array(
					array('preinscripcion_operativo', "preinscripcion_operativo.id = preinscripcion.preinscripcion_operativo_id", 'preinscripcion.escuela_id', '', ''),
					array('preinscripcion_calendario', 'preinscripcion_calendario.preinscripcion_operativo_id = preinscripcion_operativo.id', '', 'preinscripcion_calendario.deriva')
				),
				'where' => array("preinscripcion.id = $preinscripcion_id", "preinscripcion_calendario.instancia = $instancia")
			))[0];
		if (empty($preinscripcion)) {
			$this->session->set_flashdata('error', 'No se encontró la preinscripción de la escuela');
			redirect("escuela/escritorio/{$preinscripcion->escuela_id}");
		} else {
			if ($preinscripcion->deriva === 'Si') {
				$this->ver_instancia_deriva($preinscripcion->id, $instancia, $redireccion);
			} else {
				$this->ver($preinscripcion->id, $instancia, $redireccion);
			}
		}
	}

	public function agregar($ciclo_lectivo = NULL, $escuela_id = NULL, $redireccion = 'escuela') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->set_model_validation_rules($this->preinscripcion_model);
		if ($this->form_validation->run() === TRUE) {
			$trans_ok = TRUE;
			$trans_ok &= $this->preinscripcion_model->create(array(
				'escuela_id' => $escuela_id,
				'vacantes' => $this->input->post('vacantes'),
				'fecha_carga' => (new DateTime())->format('Y-m-d H:i:s')
			));
			$preinscripcion_id = $this->preinscripcion_model->get_row_id();
			if ($trans_ok) {
				if ($redireccion === 'supervision') {
					$this->session->set_flashdata('message', $this->preinscripcion_model->get_msg());
					redirect("escritorio", 'refresh');
				} else {
					$this->session->set_flashdata('message', $this->preinscripcion_model->get_msg());
					redirect("escuela/escritorio/$escuela->id", 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->preinscripcion_model->get_error() ? $this->preinscripcion_model->get_error() : $this->session->flashdata('error')));

		$this->preinscripcion_model->fields['escuela']['value'] = $escuela->nombre_largo;

		$data['fields'] = $this->build_fields($this->preinscripcion_model->fields);
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['escuela'] = $escuela;

		$data['txt_btn'] = 'Agregar';
		$data['class'] = array('agregar' => 'active btn-app-zetta-active', 'ver' => 'disabled', 'editar' => 'disabled', 'eliminar' => 'disabled');
		$data['title'] = TITLE . ' - Cargar vacantes';
		$this->load->view('preinscripciones/escuela/escuela_instancia_0', $data);
	}

	public function ver($id = NULL, $instancia = '0', $redireccion = 'escuela') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$preinscripcion = $this->preinscripcion_model->get_preinscripcion($id);
		if (empty($preinscripcion)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$preinscripcion_tipos = $this->preinscripcion_model->get_preinscripcion_tipos_instancia($preinscripcion->preinscripcion_operativo_id, $instancia);
		$fecha_hasta = $this->preinscripcion_calendario_model->get(array('preinscripcion_operativo_id' => $preinscripcion->preinscripcion_operativo_id, 'instancia' => $instancia));
		$preinscripcion->escuela = $escuela->nombre_largo;
		$data['error'] = $this->session->flashdata('error');
		if ($instancia === '0') {
			$preinscripcion->inscriptos = 0;
			$preinscripcion->postulantes = 0;
		} else {
			$this->load->model('preinscripciones/preinscripcion_alumno_model');
			$alumnos_db = $this->preinscripcion_alumno_model->get_alumnos($preinscripcion->id);
			$alumnos = array();
			foreach ($alumnos_db as $alumno) {
				$alumnos[$alumno->preinscripcion_tipo_id][] = $alumno;
			}
			$data['abrir_modal'] = $this->session->flashdata('abrir_modal');
			$data['tipo_modal'] = $this->session->flashdata('tipo_modal');
			$data['alumnos'] = $alumnos;
		}
		$data['fecha_hasta'] = $fecha_hasta[0]->hasta;
		$data['fecha'] = date('Y-m-d');
		$data['redireccion'] = $redireccion;
		$data['preinscripcion_tipos'] = $preinscripcion_tipos;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->preinscripcion_model->fields, $preinscripcion, TRUE);
		$data['preinscripcion'] = $preinscripcion;
		$data['ciclo_lectivo'] = $preinscripcion->ciclo_lectivo;
		$data['escuela'] = $escuela;
		$data['instancia'] = $instancia;
		$data['txt_btn'] = NULL;
		$data['class'] = array('ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver preinscripción';

		$this->load_template("preinscripciones/escuela/escuela_instancia", $data);
	}

	public function ver_instancia_deriva($id = NULL, $instancia = NULL, $redireccion = 'escuela') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id) || $instancia == NULL || !ctype_digit($instancia)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$preinscripcion = $this->preinscripcion_model->get_preinscripcion($id);
		if (empty($preinscripcion)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$fecha_hasta = $this->preinscripcion_calendario_model->get(array('instancia' => $instancia));
		$this->load->model('preinscripciones/preinscripcion_alumno_model');
		$this->set_model_validation_rules($this->preinscripcion_alumno_model);
		$errors = FALSE;
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$a_derivados = $this->input->post('derivados');
				if ($id !== $this->input->post('id')) {
					show_error('Esta solicitud no pasó el control de seguridad.');
				}
				$trans_ok = TRUE;
				if ($trans_ok) {
					//hacer count($a_derivados) y compararlo con vacantes que quedan
					if (empty($a_derivados)) {
						$this->session->set_flashdata('error', 'No seleccionó ningún alumno para inscribir');
						redirect("preinscripciones/escuela/instancia/$instancia/$preinscripcion->id/$redireccion", 'refresh');
					}
					foreach ($a_derivados as $pre_a_id) {
						$alumno_derivado = $this->preinscripcion_alumno_model->get_one($pre_a_id);

						if (!empty($alumno_derivado) && ($preinscripcion->vacantes) > ($preinscripcion->inscriptos)) {
							$trans_ok &= $this->preinscripcion_alumno_model->update(array(
								'id' => $alumno_derivado->id,
								'estado' => 'Derivado',
								'preinscripcion_tipo_id' => 5,
								), FALSE);
							$trans_ok &= $this->preinscripcion_alumno_model->create(array(
								'preinscripcion_id' => $id,
								'alumno_id' => $alumno_derivado->alumno_id,
								'preinscripcion_tipo_id' => 5,
								'fecha_carga' => date('Y-m-d H:i:s'),
								'estado' => 'Inscripto',
								), FALSE);
						} else {
							$this->session->set_flashdata('error', 'No hay vacantes suficientes');
							redirect("preinscripciones/escuela/instancia/$instancia/$preinscripcion->id/$redireccion", 'refresh');
						}
					}
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', 'El alumno fue derivado exitosamente');
					redirect("preinscripciones/escuela/instancia/$instancia/$preinscripcion->id/$redireccion", 'refresh');
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

		$this->preinscripcion_model->fields['escuela'] = array('label' => 'Escuelas', 'input_type' => 'combo');
		$this->array_escuela_control = $array_escuela = $this->get_array('preinscripcion', 'escuela', 'id', array(
			'select' => "preinscripcion.escuela_id id, CONCAT(escuela.numero, ' ', escuela.nombre) escuela",
			'join' => array(
				array('preinscripcion_alumno', "preinscripcion_alumno.preinscripcion_id=preinscripcion.id AND preinscripcion_alumno.estado='Postulante'"),
				array('escuela', 'preinscripcion.escuela_id=escuela.id'),
			),
			'escuela_id !=' => $escuela->id,
			'group_by' => 'escuela.id'
			, ''), array());
		$preinscripcion->escuela = $escuela->nombre_largo;
		$data['error'] = $this->session->flashdata('error');
		$this->load->model('preinscripciones/preinscripcion_alumno_model');
		$alumnos_db = $this->preinscripcion_alumno_model->get_alumnos($id);
		$alumnos_derivados = $this->preinscripcion_alumno_model->get_alumnos_derivados($id);
		$alumnos = array();
		foreach ($alumnos_db as $alumno) {
			$alumnos[$alumno->preinscripcion_tipo_id][] = $alumno;
		}
		$data['fecha_hasta'] = $fecha_hasta[0]->hasta;
		$data['fecha'] = date('Y-m-d');
		$data['redireccion'] = $redireccion;
		$data['alumnos'] = $alumnos;
		$data['alumnos_derivados'] = $alumnos_derivados;
		$this->preinscripcion_model->fields['escuela']['array'] = $array_escuela;
		$data['fields'] = $this->build_fields($this->preinscripcion_model->fields, $preinscripcion);
		$data['preinscripcion'] = $preinscripcion;
		$data['ciclo_lectivo'] = $preinscripcion->ciclo_lectivo;
		$data['message'] = $this->session->flashdata('message');
		$vacantes = $preinscripcion->vacantes - $preinscripcion->inscriptos;
		$data['vacantes'] = $vacantes;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Derivar';
		$data['class'] = array('ver' => 'active btn-app-zetta-active', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver preinscripción';

		$this->load_template("preinscripciones/escuela/escuela_instancia_derivar", $data);
	}

	public function cargar_vacantes($id = NULL, $redireccion = 'escuela') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->edicion) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect("preinscripciones/escuela/ver/$id/0");
		}
		if (!isset($this->instancias['0'])) {
			$this->session->set_flashdata('error', 'No puede modificar las vacantes, ya cerró la instancia');
			redirect("preinscripciones/escuela/ver/$id/0");
		}
		$preinscripcion = $this->preinscripcion_model->get_one($id);
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
		$preinscripcion->escuela = $escuela->nombre_largo;
		$this->set_model_validation_rules($this->preinscripcion_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->preinscripcion_model->update(array(
					'id' => $id,
					'vacantes' => $this->input->post('vacantes'),
					'fecha_carga' => (new DateTime())->format('Y-m-d H:i:s')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->preinscripcion_model->get_msg());
					redirect("escuela/escritorio/$escuela->id", 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->preinscripcion_model->get_error() ? $this->preinscripcion_model->get_error() : $this->session->flashdata('error')));
		$data['message'] = $this->session->flashdata('message');

		$this->load->model('preinscripciones/preinscripcion_alumno_model');
		$alumnos = $this->preinscripcion_alumno_model->get_alumnos($id);
		$data['fields'] = $this->build_fields($this->preinscripcion_model->fields, $preinscripcion);

		$data['preinscripcion'] = $preinscripcion;
		$data['ciclo_lectivo'] = $preinscripcion->ciclo_lectivo;
		$data['alumnos'] = $alumnos;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Cargar vacantes';
		$this->load->view('preinscripciones/escuela/escuela_instancia_0', $data);
	}

	public function editar_vacantes($id = NULL, $redireccion = 'escuela') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$preinscripcion = $this->preinscripcion_model->get_one($id);
		if (empty($preinscripcion)) {
			$this->modal_error('No se encontró el registro', 'Registro no encontrado');
			return;
		}
		$this->load->model('preinscripciones/preinscripcion_alumno_model');
		$postulantes = $this->preinscripcion_alumno_model->get_alumnos_postulantes($preinscripcion->id);
		if (!empty($postulantes)) {
			$this->modal_error('No se permite editar vacantes si la escuela tiene alumnos Postulantes/Excedentes', 'Acción no Permitida');
			return;
		}
		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->edicion) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Registro no encontrado');
			return;
		}
		if (!isset($this->instancias['0']) && !in_array($this->rol->codigo, $this->roles_editar_vacantes)) {
			$this->modal_error('No puede modificar las vacantes, ya cerró la instancia', 'Instancia cerrada');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}
		$alumnos = $this->preinscripcion_alumno_model->get_alumnos($id);
		$preinscripcion->escuela = $escuela->nombre_largo;
		$this->set_model_validation_rules($this->preinscripcion_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE && $this->input->post('vacantes') >= count($alumnos)) {
				$trans_ok = TRUE;
				$trans_ok &= $this->preinscripcion_model->update(array(
					'id' => $id,
					'vacantes' => $this->input->post('vacantes'),
					'fecha_carga' => (new DateTime())->format('Y-m-d H:i:s')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->preinscripcion_model->get_msg());
					if ($redireccion === 'supervision') {
						redirect("supervision/escritorio/$escuela->supervision_id", 'refresh');
					} else {
						redirect("escuela/escritorio/$escuela->id", 'refresh');
					}
				} else {
					$this->session->set_flashdata('error', $this->preinscripcion_model->get_error());
					if ($redireccion === 'supervision') {
						redirect("supervision/escritorio/$escuela->supervision_id", 'refresh');
					} else {
						redirect("escuela/escritorio/$escuela->id", 'refresh');
					}
				}
			} else {
				if ($this->input->post('vacantes') < count($alumnos)) {
					$this->session->set_flashdata('error', 'El número de vacantes no puede ser inferior a la cantidad de inscriptos');
				} else {
					$this->session->set_flashdata('error', validation_errors());
				}
				if ($redireccion === 'supervision') {
					redirect("escritorio", 'refresh');
				} else {
					redirect("escuela/escritorio/$escuela->id", 'refresh');
				}
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->preinscripcion_model->get_error() ? $this->preinscripcion_model->get_error() : $this->session->flashdata('error')));
		$data['message'] = $this->session->flashdata('message');
		$data['preinscripcion'] = $preinscripcion;
		$data['cursos'] = array('1' => '1° Grado', '2' => 'Sala 4', '3' => 'Sala 5');
		$data['ciclo_lectivo'] = $preinscripcion->ciclo_lectivo;
		$data['alumnos'] = $alumnos;
		$this->preinscripcion_model->fields['vacantes']['min'] = empty($alumnos) ? '1' : count($alumnos);
		$data['escuela'] = $escuela;
		$data['fields'] = $this->build_fields($this->preinscripcion_model->fields, $preinscripcion);
		$data['txt_btn'] = 'Editar';
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = 'Edición de vacantes';
		$this->load->view('preinscripciones/escuela/escuela_instancia_0', $data);
	}

	public function modal_consultar_estado($ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$model_busqueda = new stdClass();
		$model_busqueda->fields = array(
			'd_documento' => array('label' => 'Documento', 'maxlength' => '10'),
			'd_apellido' => array('label' => 'Apellido', 'maxlength' => '100', 'minlength' => '3'),
			'd_nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'minlength' => '3'),
			'persona_seleccionada' => array('label' => '', 'type' => 'integer', 'required' => TRUE),
		);
		$this->set_model_validation_rules($model_busqueda);

		$data['fields'] = $this->build_fields($model_busqueda->fields);
		$data['txt_btn'] = 'Consultar';
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['title'] = 'Buscar estado de la preinscripción';
		$this->load->view('preinscripciones/preinscripcion/preinscripcion_modal_consultar_estado', $data);
	}

	public function ver_vacantes($id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$preinscripcion = $this->preinscripcion_model->get_one($id);
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
		$preinscripcion->escuela = $escuela->nombre_largo;
		$data['error'] = (validation_errors() ? validation_errors() : ($this->preinscripcion_model->get_error() ? $this->preinscripcion_model->get_error() : $this->session->flashdata('error')));
		$data['message'] = $this->session->flashdata('message');

		$this->load->model('preinscripciones/preinscripcion_alumno_model');
		$alumnos = $this->preinscripcion_alumno_model->get_alumnos($id);
		$data['fields'] = $this->build_fields($this->preinscripcion_model->fields, $preinscripcion, TRUE);
		$data['preinscripcion'] = $preinscripcion;
		$data['ciclo_lectivo'] = $preinscripcion->ciclo_lectivo;
		$data['alumnos'] = $alumnos;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'ver' => '', 'editar' => '', 'eliminar' => '');
		$data['title'] = TITLE . ' - Vista de vacantes';
		$this->load->view('preinscripciones/escuela/escuela_instancia_0', $data);
	}

	public function anexo1_excel($id = NULL, $instancia = NULL, $redireccion = 'escuela') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}

		$preinscripcion = $this->preinscripcion_model->get_one($id);
		if (empty($preinscripcion)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}

		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$campos = array(
			'A' => array('Instancia', 14),
			'B' => array('Orden', 10),
			'C' => array('Apellido y Nombres', 25),
			'D' => array('Documento', 13),
			'E' => array('Tipo', 20),
			'F' => array('F.Nacimiento', 13),
			'G' => array('Sexo', 10),
			'H' => array('Dirección', 40),
			'I' => array('Padre/Madre/Tutor', 40),
			'J' => array('Telefonos', 25),
			'K' => array('Emails', 25),
		);

		$alumnos = $this->db->select(" CONCAT(COALESCE(pret.instancia,''), '° ',('Instancia')) as instancia, 0 AS orden, CONCAT(COALESCE(p.apellido, ''), ', ', COALESCE(p.nombre, '')) as persona, CONCAT(dt.descripcion_corta, ' ', p.documento) documento, pret.descripcion, DATE_FORMAT(p.fecha_nacimiento,'%d/%m/%Y') fecha_nacimiento, s.descripcion as sexo, CONCAT(COALESCE(CONCAT(p.calle,' '),''), COALESCE(CONCAT(p.calle_numero,' '),''), COALESCE(CONCAT('Dpto:',p.departamento,' '),''), COALESCE(CONCAT('P:',p.piso,' '),''), COALESCE(CONCAT('B°:',p.barrio,' '),''), COALESCE(CONCAT('M:',p.manzana,' '),''), COALESCE(CONCAT('C:',p.casa,' '),'')) as direccion, GROUP_CONCAT(CONCAT(COALESCE(pt.descripcion, ''), ' ', COALESCE(fdt.descripcion_corta, ''), ' ', COALESCE(pf.documento, ''), ' ', COALESCE(CONCAT(pf.apellido,','), ''), ' ', COALESCE(pf.nombre, '')) ORDER BY f.id SEPARATOR '\n') familiares,GROUP_CONCAT(CONCAT(COALESCE(pf.telefono_fijo,'SN'),'- ', COALESCE(pf.telefono_movil,'SN')) ORDER BY pf.id SEPARATOR '\n') telefonos , GROUP_CONCAT(COALESCE(pf.email, '') ORDER BY pf.id SEPARATOR '\n') email")
				->from('alumno al')
				->join('persona p', 'p.id = al.persona_id', 'left')
				->join('documento_tipo dt', 'dt.id = p.documento_tipo_id', 'left')
				->join('sexo s', 's.id = p.sexo_id', 'left')
				->join('preinscripcion_alumno preal', 'preal.alumno_id = al.id', 'left')
				->join('preinscripcion pre', 'preal.preinscripcion_id = pre.id', 'left')
				->join('preinscripcion_tipo pret', 'preal.preinscripcion_tipo_id=pret.id ', 'left')
				->join('familia f', 'f.persona_id = p.id', 'left')
				->join('parentesco_tipo pt', 'f.parentesco_tipo_id = pt.id', 'left')
				->join('persona pf', 'f.pariente_id = pf.id', 'left')
				->join('documento_tipo fdt', 'fdt.id = pf.documento_tipo_id', 'left')
				->where('preal.preinscripcion_id', $id)
				->where('preal.estado', 'Inscripto')
				->order_by('pret.id')
				->group_by('al.id')
				->get()->result_array();
		if (!empty($alumnos)) {
			if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
				show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
			}
			$this->load->library('PHPExcel');
			$this->phpexcel->getProperties()->setTitle($escuela->nombre_largo)->setDescription("");
			$this->phpexcel->setActiveSheetIndex(0);

			$sheet = $this->phpexcel->getActiveSheet();
			$escuela_nombre = str_replace("/", " Anexo ", "$escuela->nombre_corto");
			$sheet->setTitle(substr($escuela_nombre, 0, 30));
			$encabezado = array();
			$ultima_columna = 'A';
			foreach ($campos as $columna => $campo) {
				$encabezado[] = $campo[0];
				$sheet->getColumnDimension($columna)->setWidth($campo[1]);
				$ultima_columna = $columna;
			}

			$sheet->setCellValue('A1', 'Planilla de Inscripción - 1° Grado - Ciclo Lectivo 2018');
			$sheet->setCellValue('A2', "Numero y Nombre de la Institución: $escuela->nombre_largo");
			$sheet->setCellValue('A3', "Sección del Supervisor: $escuela->supervision");
			$sheet->setCellValue('A4', 'Fecha: ' . date('d/m/Y'));
			$sheet->fromArray(array($encabezado), NULL, 'A5');
			$sheet->fromArray($alumnos, NULL, 'A6');

			$sheet->getStyle('A5:' . $ultima_columna . '5')->getFont()->setBold(true);
			$sheet->mergeCells('A1:K1');
			$sheet->mergeCells('A2:K2');
			$sheet->mergeCells('A3:K3');
			$sheet->mergeCells('A4:K4');
			$sheet->getStyle('A1:A4')->getFont()->setBold(true);
			$sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A2:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			$ultima_fila = $sheet->getHighestRow();
			for ($i = 6; $i <= $ultima_fila; $i++) {
				$sheet->setCellValue("B$i", $i - 5);
			}
			$sheet->getStyle("A5:K$ultima_fila")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sheet->getStyle("A5:K$ultima_fila")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle("G5:K$ultima_fila")->getAlignment()->setWrapText(true);
			$sheet->getStyle('A4')->getFont()->setBold(true);

			header("Content-Type: application/vnd.ms-excel");
			$nombreArchivo = "Anexo 1 Escuela - $escuela_nombre";
			header("Content-Disposition: attachment; filename=\"$nombreArchivo.xls\"");
			header("Cache-Control: max-age=0");

			$writer = PHPExcel_IOFactory::createWriter($this->phpexcel, "Excel5");
			$writer->save('php://output');
			exit;
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect("preinscripciones/escuela/instancia_$instancia/$preinscripcion->ciclo_lectivo/$escuela->id/$redireccion", 'refresh');
			//redirect("preinscripciones/escuela/ver/$id/$instancia", 'refresh');
		}
	}

	public function anexo3_excel($id = NULL, $instancia = NULL, $redireccion = 'escuela') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}

		$preinscripcion = $this->preinscripcion_model->get_one($id);
		if (empty($preinscripcion)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}

		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$campos = array(
			'A' => array('Orden', 10),
			'B' => array('Apellido y Nombres', 25),
			'C' => array('Documento', 13),
			'D' => array('Tipo', 20),
			'E' => array('F.Nacimiento', 13),
			'F' => array('Sexo', 10),
			'G' => array('Dirección', 40),
			'H' => array('Padre/Madre/Tutor', 40),
			'I' => array('Telefonos', 25),
			'J' => array('Emails', 25),
		);

		$alumnos = $this->db->select("CONCAT(COALESCE(p.apellido, ''), ', ', COALESCE(p.nombre, '')) as persona, CONCAT(dt.descripcion_corta, ' ', p.documento) documento, pret.descripcion as preinscripcion_tipo, DATE_FORMAT(p.fecha_nacimiento,'%d/%m/%Y') fecha_nacimiento, s.descripcion as sexo, CONCAT(COALESCE(CONCAT(p.calle,' '),''), COALESCE(CONCAT(p.calle_numero,' '),''), COALESCE(CONCAT('Dpto:',p.departamento,' '),''), COALESCE(CONCAT('P:',p.piso,' '),''), COALESCE(CONCAT('B°:',p.barrio,' '),''), COALESCE(CONCAT('M:',p.manzana,' '),''), COALESCE(CONCAT('C:',p.casa,' '),'')) as direccion, GROUP_CONCAT(CONCAT(COALESCE(pt.descripcion, ''), ' ', COALESCE(fdt.descripcion_corta, ''), ' ', COALESCE(pf.documento, ''), ' ', COALESCE(CONCAT(pf.apellido,','), ''), ' ', COALESCE(pf.nombre, '')) ORDER BY f.id SEPARATOR '\n') familiares,GROUP_CONCAT(CONCAT(COALESCE(pf.telefono_fijo,'SN'),'- ', COALESCE(pf.telefono_movil,'SN')) ORDER BY pf.id SEPARATOR '\n') telefonos , GROUP_CONCAT(COALESCE(pf.email, '') ORDER BY pf.id SEPARATOR '\n') email")
				->from('alumno al')
				->join('persona p', 'p.id = al.persona_id', 'left')
				->join('documento_tipo dt', 'dt.id = p.documento_tipo_id', 'left')
				->join('sexo s', 's.id = p.sexo_id', 'left')
				->join('preinscripcion_alumno preal', 'preal.alumno_id = al.id', 'left')
				->join('preinscripcion pre', 'preal.preinscripcion_id = pre.id', 'left')
				->join('preinscripcion_tipo pret', 'preal.preinscripcion_tipo_id=pret.id', 'left')
				->join('familia f', 'f.persona_id = p.id', 'left')
				->join('parentesco_tipo pt', 'f.parentesco_tipo_id = pt.id', 'left')
				->join('persona pf', 'f.pariente_id = pf.id', 'left')
				->join('documento_tipo fdt', 'fdt.id = pf.documento_tipo_id', 'left')
				->where('preal.preinscripcion_id', $id)
				->where('preal.estado', 'Postulante')
				->order_by('preal.fecha_carga, p.apellido, p.nombre')
				->group_by('al.id')
				->get()->result_array();

		if (!empty($alumnos)) {
			if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
				show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
			}
			$this->load->library('PHPExcel');
			$this->phpexcel->getProperties()->setTitle($escuela->nombre_largo)->setDescription("");
			$this->phpexcel->setActiveSheetIndex(0);

			$sheet = $this->phpexcel->getActiveSheet();
			$escuela_nombre = str_replace("/", " Anexo ", "$escuela->nombre_corto");
			$sheet->setTitle(substr($escuela_nombre, 0, 30));
			$encabezado = array();
			$ultima_columna = 'A';
			foreach ($campos as $columna => $campo) {
				$encabezado[] = $campo[0];
				$sheet->getColumnDimension($columna)->setWidth($campo[1]);
				$ultima_columna = $columna;
			}

			$sheet->setCellValue('A1', 'ACTA VOLANTE DE EXCEDENTES DE ALUMNOS Y DE DERIVACIÓN DE LOS MISMOS');
			$sheet->setCellValue('A2', "En el día de la fecha, en la Sede de Supervisión sección N°: $escuela->supervision, durante la reunión de reubicación de matricula excedente, la");
			$sheet->setCellValue('A3', "Escuela N°: $escuela->numero, Nombre: $escuela->nombre, deriva a los siguientes niños/as a la Escuela que figura en");
			$sheet->setCellValue('A4', 'planilla.');
			$sheet->fromArray(array($encabezado), NULL, 'A5');
			$sheet->fromArray($alumnos, NULL, 'B6');

			$sheet->getStyle('A5:' . $ultima_columna . '5')->getFont()->setBold(true);
			$sheet->mergeCells('A1:J1');
			$sheet->mergeCells('A2:J2');
			$sheet->mergeCells('A3:J3');
			$sheet->mergeCells('A4:J4');
			$sheet->getStyle('A1:A4')->getFont()->setBold(true);
			$sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A2:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			$ultima_fila = $sheet->getHighestRow();
			for ($i = 6; $i <= $ultima_fila; $i++) {
				$sheet->setCellValue("A$i", $i - 5);
			}
			$sheet->getStyle("A5:J$ultima_fila")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sheet->getStyle("A5:J$ultima_fila")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle("G5:J$ultima_fila")->getAlignment()->setWrapText(true);
			$sheet->getStyle('A4')->getFont()->setBold(true);

			header("Content-Type: application/vnd.ms-excel");
			$nombreArchivo = "Anexo 3 Escuela - $escuela_nombre";
			header("Content-Disposition: attachment; filename=\"$nombreArchivo.xls\"");
			header("Cache-Control: max-age=0");

			$writer = PHPExcel_IOFactory::createWriter($this->phpexcel, "Excel5");
			$writer->save('php://output');
			exit;
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect("preinscripciones/escuela/instancia_$instancia/$preinscripcion->ciclo_lectivo/$escuela->id/$redireccion", 'refresh');
		}
	}

	public function anexo1_imprimir_pdf($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}

		$preinscripcion = $this->preinscripcion_model->get_one($id);
		if (empty($preinscripcion)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$alumnos = $this->db->select("pret.instancia , CONCAT(COALESCE(p.apellido, ''), ', ', COALESCE(p.nombre, '')) as persona, CONCAT(dt.descripcion_corta, ' ', p.documento) documento, p.fecha_nacimiento, s.descripcion as sexo, CONCAT(COALESCE(CONCAT(p.calle,' '),''), COALESCE(CONCAT(p.calle_numero,' '),''), COALESCE(CONCAT('Dpto:',p.departamento,' '),''), COALESCE(CONCAT('P:',p.piso,' '),''), COALESCE(CONCAT('B°:',p.barrio,' '),''), COALESCE(CONCAT('M:',p.manzana,' '),''), COALESCE(CONCAT('C:',p.casa,' '),'')) as direccion, GROUP_CONCAT(CONCAT(COALESCE(pt.descripcion, ''), ' ', COALESCE(fdt.descripcion_corta, ''), ' ', COALESCE(pf.documento, ''), ' ', COALESCE(CONCAT(pf.apellido,','), ''), ' ', COALESCE(pf.nombre, '')) ORDER BY f.id SEPARATOR '<br>') familiares, preal.estado, GROUP_CONCAT(CONCAT(COALESCE(pf.telefono_fijo, 'SN'), ' - ', COALESCE(pf.telefono_movil, 'SN')) ORDER BY pf.id SEPARATOR '<br>') telefonos, GROUP_CONCAT(COALESCE(pf.email, '') ORDER BY pf.id SEPARATOR '<br>') email,  pret.descripcion as preinscripcion_tipo")
				->from('alumno al')
				->join('persona p', 'p.id = al.persona_id', 'left')
				->join('documento_tipo dt', 'dt.id = p.documento_tipo_id', 'left')
				->join('sexo s', 's.id = p.sexo_id', 'left')
				->join('preinscripcion_alumno preal', 'preal.alumno_id = al.id', 'left')
				->join('preinscripcion pre', 'preal.preinscripcion_id = pre.id', 'left')
				->join('preinscripcion_tipo pret', 'preal.preinscripcion_tipo_id=pret.id', 'left')
				->join('familia f', 'f.persona_id = p.id', 'left')
				->join('parentesco_tipo pt', 'f.parentesco_tipo_id = pt.id', 'left')
				->join('persona pf', 'f.pariente_id = pf.id', 'left')
				->join('documento_tipo fdt', 'fdt.id = pf.documento_tipo_id', 'left')
				->where('preal.preinscripcion_id', $id)
				->where('preal.estado', 'Inscripto')
				->order_by('pret.id')
				->group_by('al.id')
				->get()->result();

		$data['error'] = $this->session->flashdata('error');
		$data['alumnos'] = $alumnos;
		$data['escuela'] = $escuela;
		$fecha_actual = date('d/m/Y');
		$content = $this->load->view('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_anexo1_pdf', $data, TRUE);
		$this->load->helper('mpdf');
		$watermark = '';
		exportMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Planilla de Preinscripción', 'Planilla de Preinscripción - Esc. "' . trim($escuela->nombre) . '" Nº ' . $escuela->numero . ' " - Fecha actual: ' . $fecha_actual, '|{PAGENO} de {nb}|', 'LEGAL-L', $watermark, 'I', FALSE, FALSE);
	}

	public function anexo3_imprimir_pdf($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}

		$preinscripcion = $this->preinscripcion_model->get_one($id);
		if (empty($preinscripcion)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$escuela = $this->escuela_model->get_one($preinscripcion->escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$alumnos = $this->db->select("CONCAT(COALESCE(p.apellido, ''), ', ', COALESCE(p.nombre, '')) as persona, CONCAT(dt.descripcion_corta, ' ', p.documento) documento, p.fecha_nacimiento, s.descripcion as sexo, CONCAT(COALESCE(CONCAT(p.calle,' '),''), COALESCE(CONCAT(p.calle_numero,' '),''), COALESCE(CONCAT('Dpto:',p.departamento,' '),''), COALESCE(CONCAT('P:',p.piso,' '),''), COALESCE(CONCAT('B°:',p.barrio,' '),''), COALESCE(CONCAT('M:',p.manzana,' '),''), COALESCE(CONCAT('C:',p.casa,' '),'')) as direccion, GROUP_CONCAT(CONCAT(COALESCE(pt.descripcion, ''), ' ', COALESCE(fdt.descripcion_corta, ''), ' ', COALESCE(pf.documento, ''), ' ', COALESCE(CONCAT(pf.apellido,','), ''), ' ', COALESCE(pf.nombre, '')) ORDER BY f.id SEPARATOR '<br>') familiares, preal.estado, GROUP_CONCAT(CONCAT(COALESCE(pf.telefono_fijo, 'SN'), ' - ', COALESCE(pf.telefono_movil, 'SN')) ORDER BY pf.id SEPARATOR '<br>') telefonos, GROUP_CONCAT(COALESCE(al.email_contacto, '') ORDER BY pf.id SEPARATOR '<br>') email,  pret.descripcion as preinscripcion_tipo, CONCAT((escd.numero), ' - ', (escd.nombre)) as escuela_derivada ")
				->from('alumno al')
				->join('persona p', 'p.id = al.persona_id', 'left')
				->join('documento_tipo dt', 'dt.id = p.documento_tipo_id', 'left')
				->join('sexo s', 's.id = p.sexo_id', 'left')
				->join('preinscripcion_alumno preal', 'preal.alumno_id = al.id', 'left')
				->join('preinscripcion pre', 'preal.preinscripcion_id = pre.id', 'left')
				->join('preinscripcion_tipo pret', 'preal.preinscripcion_tipo_id=pret.id', 'left')
				->join('preinscripcion_alumno preald', 'preal.alumno_id = preald.alumno_id AND preald.preinscripcion_id NOT LIKE ' . $id . ' ', 'left')
				->join('preinscripcion pred', 'preald.preinscripcion_id = pred.id', 'left')
				->join('escuela escd', 'pred.escuela_id = escd.id', 'left')
				->join('familia f', 'f.persona_id = p.id', 'left')
				->join('parentesco_tipo pt', 'f.parentesco_tipo_id = pt.id', 'left')
				->join('persona pf', 'f.pariente_id = pf.id', 'left')
				->join('documento_tipo fdt', 'fdt.id = pf.documento_tipo_id', 'left')
				->where('preal.preinscripcion_id', $id)
				->where("preal.estado IN ('Derivado', 'Postulante')")
				->order_by('pret.id,preal.fecha_carga, p.apellido, p.nombre')
				->group_by('al.id')
				->get()->result();

		$data['error'] = $this->session->flashdata('error');
		$data['alumnos'] = $alumnos;
		$data['escuela'] = $escuela;

		$fecha_actual = date('d/m/Y');
		$content = $this->load->view('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_anexo3_pdf', $data, TRUE);
		$this->load->helper('mpdf');
		$watermark = '';
		exportarMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Planilla de Preinscripción', 'Planilla de Preinscripción - Esc. "' . trim($escuela->nombre) . '" Nº ' . $escuela->numero . ' " - Fecha actual: ' . $fecha_actual, '|{PAGENO} de {nb}|', 'LEGAL-L', $watermark, 'I', FALSE, FALSE);
	}

	public function anexo4_imprimir_pdf($supervision_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $supervision_id == NULL || !ctype_digit($supervision_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
		}

		$this->load->model('supervision_model');
		$supervision = $this->supervision_model->get_one($supervision_id);
		if (empty($supervision)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		$escuelas = $this->db->select("e.id, COUNT(DISTINCT d.id) divisiones_2017, COUNT(DISTINCT ad.id) alumnos_2017, CONCAT((CONCAT((e.numero),' - ',(e.nombre))),' /',(COALESCE(e.anexo,''))) as escuela, p.id as preinscripcion_id, p.vacantes, par.inscriptos, par.postulantes,pa.alumno_id as alumno_id,CONCAT(CONCAT((dt.descripcion_corta),'-',(pe.documento)),' ',CONCAT((pe.apellido),' ',(pe.nombre))) as alumno, pa.estado,  CONCAT((ed.numero), ' - ', (ed.nombre)) as escuela_derivada")
				->from('escuela e')
				->join('supervision s', 'e.supervision_id = s.id')
				->join('division d', 'd.escuela_id = e.id AND d.curso_id=7', 'left')
				->join('alumno_division ad', 'ad.division_id=d.id AND ad.fecha_hasta IS NULL', 'left')
				->join('preinscripcion p', 'p.escuela_id = e.id', 'left')
				->join('preinscripcion_alumno pa ', "pa.preinscripcion_id = p.id AND pa.estado IN ('Derivado','Postulante')", 'left')
				->join('alumno a', 'pa.alumno_id = a.id', 'left')
				->join('persona pe', 'a.persona_id = pe.id', 'left')
				->join('documento_tipo dt', 'dt.id = pe.documento_tipo_id', 'left')
				->join('preinscripcion_alumno pad', 'pa.alumno_id = pad.alumno_id AND pad.id!=pa.id', 'left')
				->join('preinscripcion pd', 'pad.preinscripcion_id = pd.id', 'left')
				->join('escuela ed', 'pd.escuela_id = ed.id', 'left')
				->join("(SELECT p.escuela_id,pa.alumno_id, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Inscripto' THEN 1 ELSE 0 END), 0) inscriptos, "
					. "COALESCE(SUM(CASE pa.estado WHEN 'Postulante' THEN 1 ELSE 0 END), 0) postulantes "
					. "FROM preinscripcion p JOIN preinscripcion_alumno pa ON p.id = pa.preinscripcion_id "
					. "GROUP BY p.escuela_id) par", 'par.escuela_id = e.id', 'left')
				->where('s.id', $supervision_id)
				->where('e.nivel_id=s.nivel_id AND e.dependencia_id=s.dependencia_id')
				->order_by('e.numero, e.anexo')
				->group_by('e.id, pa.alumno_id')
				->get()->result();

		$data['error'] = $this->session->flashdata('error');
		$data['escuelas'] = $escuelas;
		$data['supervision'] = $supervision;

		$fecha_actual = date('d/m/Y');
		$content = $this->load->view('preinscripciones/preinscripcion_alumno/preinscripcion_alumno_anexo4_pdf', $data, TRUE);
		$this->load->helper('mpdf');
		$watermark = '';
		exportarMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', 'Planilla de Preinscripción', 'Anexo IV Supervisión: "' . $supervision->nombre . '" Fecha actual: ' . $fecha_actual, '|{PAGENO} de {nb}|', 'LEGAL-L', $watermark, 'I', FALSE, FALSE);
	}

	public function modal_vacantes_generales($ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$data['escuelas'] = $this->preinscripcion_model->get_escuelas_con_vacantes($ciclo_lectivo);
		$data['txt_btn'] = NULL;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['title'] = TITLE . ' - Ver vacantes disponibles por Escuela';

		$this->load->view('preinscripciones/escuela/escuela_modal_vacantes_generales', $data);
	}
}
/* End of file Escuela.php */
/* Location: ./application/modules/preinscripciones/controllers/Escuela.php */
