<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Feria extends MY_Controller {

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
		$this->load_template('ingreso/feria/feria_escuela', $data);
	}

	public function escuela_editar($escuela_id) {
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
		$lista_videos = $this->video_model->get(array(
			'escuela_id' => $escuela->id
		));

		$this->load->model('ingreso/texto_model');
		$this->load->model('ingreso/imagen_model');
		$lista_texto = $this->texto_model->get(array(
			'feria_id' => $feria[0]->id
		));
		$lista_imagen = $this->imagen_model->get(array(
			'feria_id' => $feria[0]->id
		));

		$data['css'][] = 'plugins/bootstrap-fileinput/css/fileinput.css';
		$data['js'][] = 'plugins/bootstrap-fileinput/js/fileinput.js';
		$data['js'][] = 'plugins/bootstrap-fileinput/js/locales/es.js';
		$data['js'][] = 'plugins/bootstrap-fileinput/themes/fa/theme.js';

		$data['css'][] = 'plugins/bootstrap-wysihtml5/css/bootstrap3-wysihtml5.min.css';
		$data['js'][] = 'plugins/bootstrap-wysihtml5/js/bootstrap3-wysihtml5.all.min.js';
		$data['js'][] = 'plugins/bootstrap-wysihtml5/js/bootstrap3-wysihtml5.min.js';

		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$data['fields'] = $this->build_fields($this->escuela_model->fields, $escuela, TRUE);
		$data['lista_texto'] = $lista_texto;
		$data['lista_imagen'] = $lista_imagen;
		$data['escuela'] = $escuela;
		$data['lista_videos'] = $lista_videos;
		$data['txt_btn'] = NULL;
		$data['class'] = array('agregar' => '', 'escritorio' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Ver escuela feria';
		$this->load_template('ingreso/feria/feria_escuela_editar', $data);
	}

	public function escuela_editar_area_interes($escuela_id) {
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
		$this->load->model('feria_especialidad_model');
		$this->load->model('feria_escuela_especialidad_model');

		if (isset($_POST) && !empty($_POST)) {
			$areas_interes_agregar = $this->input->post('areas_interes_agregar');
			$areas_interes_retirar = $this->input->post('areas_interes_retirar');
//			if (empty($areas_interes_agregar) || empty($areas_interes_retirar)) {
//				show_error('Esta solicitud no pasó el control de seguridad.');
//			}
			if (!empty($areas_interes_agregar)) {
				$trans_ok = TRUE;
				foreach ($areas_interes_agregar as $area_interes) {
					$area_existente = $this->feria_escuela_especialidad_model->get_area_existente($escuela->id, $area_interes);
					if(empty($area_existente)){
						$trans_ok &= $this->feria_escuela_especialidad_model->create(array(
							'escuela_id' => $escuela->id,
							'especialidad_id' => $area_interes
							), FALSE);
					}
				}
			}
			if (!empty($areas_interes_retirar)) {
				$trans_ok = TRUE;
				foreach ($areas_interes_retirar as $area_interes) {
					if ($area_interes != 'undefined') {
						$trans_ok &= $this->feria_escuela_especialidad_model->delete(array(
							'id' => $area_interes,
							), FALSE);
					}
				}
			}

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->feria_escuela_especialidad_model->get_msg());
				redirect("ingreso/feria/escuela/$escuela->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->feria_escuela_especialidad_model->get_error());
				redirect("ingreso/feria/escuela/$escuela->id", 'refresh');
			}
		}
		
		$lista_areas = $this->feria_escuela_especialidad_model->get_escuela_areas($escuela->id);
		$areas_interes = $this->get_array('feria_especialidad');
		foreach ($lista_areas as $area) {
			unset($areas_interes[$area->id]);
		}
		
		$data['areas_interes'] = $areas_interes;
		$data['lista_areas'] = $lista_areas;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Guardar';
		$data['class'] = array('agregar' => '', 'escritorio' => '', 'ver' => '', 'editar' => 'active btn-app-zetta-active', 'eliminar' => '');
		$data['title'] = TITLE . ' - Editar escuela feria interés';
		$this->load_template('ingreso/feria/feria_escuela_editar_area_interes', $data);
	}

	public function modal_texto_agregar($escuela_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de escuela', 'Registro no encontrado');
		}
		$this->rol->escuela_id = $escuela->id;
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		$feria = $this->feria_model->get(array(
			'escuela_id' => $escuela->id
		));
		if (empty($feria)) {
			$this->modal_error('No se encontró el registro de feria', 'Registro no encontrado');
		}
		$this->load->model('ingreso/texto_model');

		$this->set_model_validation_rules($this->texto_model);
		if (isset($_POST) && !empty($_POST)) {
			$encabezado = $this->input->post('encabezado');
			$texto = $this->input->post('texto');
			if ($encabezado == '' || $texto == '') {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->texto_model->create(array(
				'encabezado' => $encabezado,
				'texto' => $texto,
				'feria_id' => $feria[0]->id,
				'posicion' => '',
				'fecha_alta' => ''
				), FALSE);

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->texto_model->get_msg());
				redirect("ingreso/feria/escuela_editar/$escuela_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->texto_model->get_error());
				redirect("ingreso/feria/escuela_editar/$escuela_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->texto_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar texto';
		$this->load->view('ingreso/feria/feria_modal_texto_abm', $data);
	}

	public function modal_texto_editar($texto_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $texto_id == NULL || !ctype_digit($texto_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$this->load->model('ingreso/texto_model');
		$texto = $this->texto_model->get_one($texto_id);
		if (empty($texto)) {
			$this->modal_error('No se encontró el registro de texto', 'Registro no encontrado');
		}
		$feria = $this->feria_model->get_one($texto->feria_id);
		if (empty($feria)) {
			$this->modal_error('No se encontró el registro de feria', 'Registro no encontrado');
		}

		$this->set_model_validation_rules($this->texto_model);
		if (isset($_POST) && !empty($_POST)) {
			$encabezado_edit = $this->input->post('encabezado');
			$texto_edit = $this->input->post('texto');
			if ($encabezado_edit == '' || $texto_edit == '') {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->texto_model->update(array(
				'id' => $texto_id,
				'encabezado' => $encabezado_edit,
				'texto' => $texto_edit,
				'feria_id' => $texto->feria_id,
				'posicion' => '',
				'fecha_alta' => ''
				), FALSE);

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->texto_model->get_msg());
				redirect("ingreso/feria/escuela_editar/$feria->escuela_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->texto_model->get_error());
				redirect("ingreso/feria/escuela_editar/$feria->escuela_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->texto_model->fields, $texto);
		$data['txt_btn'] = 'Editar';
		$data['escuela_id'] = $feria->escuela_id;
		$data['title'] = 'Editar texto';
		$this->load->view('ingreso/feria/feria_modal_texto_abm', $data);
	}

	public function modal_texto_eliminar($texto_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $texto_id == NULL || !ctype_digit($texto_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$this->load->model('ingreso/texto_model');
		$texto = $this->texto_model->get_one($texto_id);
		if (empty($texto)) {
			$this->modal_error('No se encontró el registro de texto', 'Registro no encontrado');
		}
		$feria = $this->feria_model->get_one($texto->feria_id);
		if (empty($feria)) {
			$this->modal_error('No se encontró el registro de feria', 'Registro no encontrado');
		}

		$this->set_model_validation_rules($this->texto_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($texto_id == '') {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->texto_model->delete(array(
				'id' => $texto_id
				), FALSE);

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->texto_model->get_msg());
				redirect("ingreso/feria/escuela_editar/$feria->escuela_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->texto_model->get_error());
				redirect("ingreso/feria/escuela_editar/$feria->escuela_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->texto_model->fields, $texto, TRUE);
		$data['txt_btn'] = 'Eliminar';
		$data['escuela_id'] = $feria->escuela_id;
		$data['title'] = 'Eliminar texto';
		$this->load->view('ingreso/feria/feria_modal_texto_abm', $data);
	}

	public function modal_video_agregar($escuela_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de escuela', 500, 'Registro no encontrado');
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
		$this->set_model_validation_rules($this->escuela_model);
		if (isset($_POST) && !empty($_POST)) {
			$video_tipo = $this->input->post('video_tipo');
			$video_id = $this->input->post('video_id');
			if ($video_id == '' || $video_tipo == '') {
				$this->form_validation->set_message('error', 'La URL ingresada no se corresponde con una dirección de video de Youtube o Vimeo.');
				redirect("ingreso/feria/escuela_editar/$escuela_id", 'refresh');
			} else {
				$json = $this->video_model->get_json_video($video_tipo, $video_id);
				switch ($video_tipo):
					case 'youtube':
						if (isset($json->error)) {
							$this->form_validation->set_message('error', 'El video no existe en el servidor de Youtube.');
							redirect("ingreso/feria/escuela_editar/$escuela_id", 'refresh');
						}
						break;
					case 'vimeo':
						if (!$json) {
							$this->form_validation->set_message('error', 'El video no existe en el servidor de Vimeo.');
							redirect("ingreso/feria/escuela_editar/$escuela_id", 'refresh');
						}
						break;
				endswitch;
			}
//			if ($this->form_validation->run() === TRUE) {
//				$posicion = $this->video_model->get_posicion_max($feria->id);
//			}

			$trans_ok = TRUE;
			$trans_ok &= $this->video_model->create(array(
				'path' => $this->input->post('path'),
				'pie' => $this->input->post('pie'),
				'thumbnail' => $this->input->post('video_id'),
				'feria_id' => $feria[0]->id
				), FALSE);

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->video_model->get_msg());
				redirect("ingreso/feria/escuela_editar/$escuela_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->video_model->get_error());
				redirect("ingreso/feria/escuela_editar/$escuela_id", 'refresh');
			}
		}

		$this->load->model('ingreso/video_model');
		$data['fields'] = $this->build_fields($this->video_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar video';
		$this->load->view('ingreso/feria/feria_modal_video_abm', $data);
	}

	public function modal_video_editar($video_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $video_id == NULL || !ctype_digit($video_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('ingreso/video_model');
		$video = $this->video_model->get_one($video_id);
		if (empty($video)) {
			$this->modal_error('No se encontró el registro de imagen', 'Registro no encontrado');
		}

		$feria = $this->feria_model->get_one($video->feria_id);
		if (empty($feria)) {
			$this->modal_error('No se encontró el registro de feria', 'Registro no encontrado');
		}

		$this->load->model('ingreso/video_model');
		$this->set_model_validation_rules($this->escuela_model);
		if (isset($_POST) && !empty($_POST)) {
			$video_tipo = $this->input->post('video_tipo');
			$video_id = $this->input->post('video_id');
			if ($video_id == '' || $video_tipo == '') {
				$this->form_validation->set_message('error', 'La URL ingresada no se corresponde con una dirección de video de Youtube o Vimeo.');
				redirect("ingreso/feria/escuela_editar/$feria->escuela_id", 'refresh');
			} else {
				$json = $this->video_model->get_json_video($video_tipo, $video_id);
				switch ($video_tipo):
					case 'youtube':
						if (isset($json->error)) {
							$this->form_validation->set_message('error', 'El video no existe en el servidor de Youtube.');
							redirect("ingreso/feria/escuela_editar/$feria->escuela_id", 'refresh');
						}
						break;
					case 'vimeo':
						if (!$json) {
							$this->form_validation->set_message('error', 'El video no existe en el servidor de Vimeo.');
							redirect("ingreso/feria/escuela_editar/$feria->escuela_id", 'refresh');
						}
						break;
				endswitch;
			}
//			if ($this->form_validation->run() === TRUE) {
//				$posicion = $this->video_model->get_posicion_max($feria->id);
//			}

			$trans_ok = TRUE;
			$trans_ok &= $this->video_model->update(array(
				'id' => $video_id,
				'path' => $this->input->post('path'),
				'pie' => $this->input->post('pie'),
				'feria_id' => $feria[0]->id
				), FALSE);

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->video_model->get_msg());
				redirect("ingreso/feria/escuela_editar/$escuela_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->video_model->get_error());
				redirect("ingreso/feria/escuela_editar/$escuela_id", 'refresh');
			}
		}

		$this->load->model('ingreso/video_model');
		$data['escuela_id'] = $feria->escuela_id;
		$data['fields'] = $this->build_fields($this->video_model->fields, $video);
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Agregar video';
		$this->load->view('ingreso/feria/feria_modal_video_abm', $data);
	}

	public function modal_video_eliminar($video_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $video_id == NULL || !ctype_digit($video_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('ingreso/video_model');
		$video = $this->video_model->get_one($video_id);
		if (empty($video)) {
			$this->modal_error('No se encontró el registro de imagen', 'Registro no encontrado');
		}

		$feria = $this->feria_model->get_one($video->feria_id);
		if (empty($feria)) {
			$this->modal_error('No se encontró el registro de feria', 'Registro no encontrado');
		}
		$this->load->model('ingreso/video_model');
		$this->set_model_validation_rules($this->escuela_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($video_id == '') {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->video_model->delete(array(
				'id' => $video_id
				), FALSE);

			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->video_model->get_msg());
				redirect("ingreso/feria/escuela_editar/$feria->escuela_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->video_model->get_error());
				redirect("ingreso/feria/escuela_editar/$feria->escuela_id", 'refresh');
			}
		}

		$this->load->model('ingreso/video_model');
		$data['escuela_id'] = $feria->escuela_id;
		$data['fields'] = $this->build_fields($this->video_model->fields, $video, true);
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Agregar video';
		$this->load->view('ingreso/feria/feria_modal_video_abm', $data);
	}

	public function modal_imagen_agregar($escuela_id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de escuela', 'Registro no encontrado');
		}
		$this->rol->escuela_id = $escuela->id;
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
		}
		$feria = $this->feria_model->get(array(
			'escuela_id' => $escuela->id
		));
		if (empty($feria)) {
			$this->modal_error('No se encontró el registro de feria', 'Registro no encontrado');
		}

		$this->load->model('ingreso/imagen_model');
		$this->set_model_validation_rules($this->imagen_model);
		if (isset($_POST) && !empty($_POST)) {
			$path = $_FILES['path'];
			$path2 = $path;
			$pie = $this->input->post('pie');
			if ($path == '' || $pie == '') {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->imagen_model->create(array(
				'path' => $path['name'],
				'pie' => $pie,
				'posicion' => '',
				'feria_id' => $feria[0]->id,
				'fecha_alta' => ''
				), FALSE);
			if (!empty($_FILES['path']['name']) && $trans_ok) {
				$directorio = 'uploads/feria/' . $escuela_id . '/fullscreen/';
				if (!file_exists($directorio)) {
					mkdir($directorio, 0755, TRUE);
				}
				$config['upload_path'] = $directorio;
				$config['allowed_types'] = 'jpg|jpeg|png';
				$config['file_name'] = $path['name'];
				$config['overwrite'] = TRUE;
				$config['max_size'] = 2048;
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('path')) {
					$error_msg = $this->upload->display_errors();
				} else {
					$upload_data = $this->upload->data();
					$config['image_library'] = 'gd2';
					$config['source_image'] = $upload_data['full_path'];
					$config['maintain_ratio'] = TRUE;
					$config['width'] = 750;
					$config['height'] = 500;
					$this->load->library('image_lib', $config);
					copy($upload_data['full_path'], str_replace('/fullscreen/', '/thumbnails/', $upload_data['full_path']));

					if (!$this->image_lib->resize()) {
						$error_msg = $this->image_lib->display_errors();
					}
					$this->image_lib->clear();
					$config['image_library'] = 'gd2';
					$config['source_image'] = str_replace('/fullscreen/', '/thumbnails/', $upload_data['full_path']);
					$config['maintain_ratio'] = TRUE;
					$config['width'] = 250;
					$config['height'] = 250;
					$this->image_lib->initialize($config);

					if (!$this->image_lib->resize()) {
						$error_msg = $this->image_lib->display_errors();
					}
				}
			}
			if (empty($error_msg)) {
				$this->session->set_flashdata('message', $this->imagen_model->get_msg());
				redirect("ingreso/feria/escuela_editar/$escuela_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', empty($error_msg) ? '$this->imagen_model->get_error()' : $error_msg);
				redirect("ingreso/feria/escuela_editar/$escuela_id", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->imagen_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar imagen';
		$this->load->view('ingreso/feria/feria_modal_imagen_abm', $data);
	}

	public function modal_imagen_editar($imagen_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $imagen_id == NULL || !ctype_digit($imagen_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$this->load->model('ingreso/imagen_model');
		$imagen = $this->imagen_model->get_one($imagen_id);
		if (empty($imagen)) {
			$this->modal_error('No se encontró el registro de imagen', 'Registro no encontrado');
		}

		$feria = $this->feria_model->get_one($imagen->feria_id);
		if (empty($feria)) {
			$this->modal_error('No se encontró el registro de feria', 'Registro no encontrado');
		}

		$this->set_model_validation_rules($this->imagen_model);
		if (isset($_POST) && !empty($_POST)) {
			$pie = $this->input->post('pie');
			if ($pie == '') {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->imagen_model->update(array(
				'id' => $imagen_id,
				'pie' => $pie,
				'posicion' => '',
				'feria_id' => $feria->id,
				'fecha_alta' => ''
				), FALSE);

//			if (!empty($_FILES['path']['name']) && $trans_ok) {
//				$directorio = 'uploads/feria/' . str_pad($feria->escuela_id, 6, '0', STR_PAD_LEFT) . '/';
//				if (!file_exists($directorio)) {
//					mkdir($directorio, 0755, TRUE);
//				}
//				$config['upload_path'] = $directorio;
//				$config['allowed_types'] = 'jpg|jpeg|png';
//				$config['file_name'] = $path['name'];
//				$config['overwrite'] = TRUE;
//				$config['max_size'] = 2048;
//				$this->load->library('upload', $config);
//
//				if (!$this->upload->do_upload('path')) {
//					$error_msg = $this->upload->display_errors();
//				} else {
//					$upload_data = $this->upload->data();
//					$config['image_library'] = 'gd2';
//					$config['source_image'] = $upload_data['full_path'];
//					$config['maintain_ratio'] = TRUE;
//					$config['width'] = 160;
//					$config['height'] = 160;
//					$this->load->library('image_lib', $config);
//
//					if (!$this->image_lib->resize()) {
//						$error_msg = $this->image_lib->display_errors();
//					}
//				}
//			}
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->imagen_model->get_msg());
				redirect("ingreso/feria/escuela_editar/$feria->escuela_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', empty($error_msg) ? '$this->imagen_model->get_error()' : $error_msg);
				redirect("ingreso/feria/escuela_editar/$feria->escuela_id", 'refresh');
			}
		}

		$this->imagen_model->fields['path']['disabled'] = TRUE;
		$data['escuela_id'] = $feria->escuela_id;
		$data['ruta_imagen'] = "uploads/feria/$feria->escuela_id/thumbnails/$imagen->path";
		$data['fields'] = $this->build_fields($this->imagen_model->fields, $imagen);
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar imagen';
		$this->load->view('ingreso/feria/feria_modal_imagen_abm', $data);
	}

	public function modal_imagen_eliminar($imagen_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $imagen_id == NULL || !ctype_digit($imagen_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		$this->load->model('ingreso/imagen_model');
		$imagen = $this->imagen_model->get_one($imagen_id);
		if (empty($imagen)) {
			$this->modal_error('No se encontró el registro de imagen', 'Registro no encontrado');
		}

		$feria = $this->feria_model->get_one($imagen->feria_id);
		if (empty($feria)) {
			$this->modal_error('No se encontró el registro de feria', 'Registro no encontrado');
		}

		$this->set_model_validation_rules($this->imagen_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($imagen_id == '') {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->imagen_model->delete(array(
				'id' => $imagen_id
				), FALSE);
			$file = "uploads/feria/$feria->escuela_id/fullscreen/$imagen->path";
			$file2 = "uploads/feria/$feria->escuela_id/thumbnails/$imagen->path";
			$deletefullscreen = unlink($file);
			$deletethumbnails = unlink($file2);

			if ($trans_ok || $deletefullscreen || $deletethumbnails) {
				$this->session->set_flashdata('message', $this->imagen_model->get_msg());
				redirect("ingreso/feria/escuela_editar/$feria->escuela_id", 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->imagen_model->get_error());
				redirect("ingreso/feria/escuela_editar/$feria->escuela_id", 'refresh');
			}
		}

		$data['escuela_id'] = $feria->escuela_id;
		$data['ruta_imagen'] = "uploads/feria/$feria->escuela_id/thumbnails/$imagen->path";
		$data['fields'] = $this->build_fields($this->imagen_model->fields, $imagen, true);
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar imagen';
		$this->load->view('ingreso/feria/feria_modal_imagen_abm', $data);
	}
}