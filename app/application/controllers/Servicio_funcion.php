<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio_funcion extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('escuela_model');
		$this->load->model('servicio_model');
		$this->load->model('servicio_funcion_model');
		$this->load->model('funcion_model');
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ESCUELA_ALUM, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE_CURSADA));
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_REGIONAL, ROL_GRUPO_ESCUELA_CONSULTA))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'menu/servicio_funcion';
	}

	public function modal_agregar($servicio_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $servicio_id == NULL || !ctype_digit($servicio_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$servicio = $this->servicio_model->get_one($servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el registro a agregar', 'Registro no encontrado');
			return;
		}

		$escuela = $this->escuela_model->get_one($servicio->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$this->array_funcion_control = $array_funcion = $this->get_array('funcion', 'descripcion', 'id', null, array('' => '-- Seleccione una función --'));
		$this->array_tarea_control = $this->servicio_funcion_model->fields['tarea']['array'];
		$this->array_tipo_destino_control = $this->servicio_funcion_model->fields['tipo_destino']['array'];

		$this->set_model_validation_rules($this->servicio_funcion_model);
		if (isset($_POST) && !empty($_POST)) {
			if (empty($this->input->post('tipo_destino'))) {
				$this->array_destino_control = array('' => '');
			} else {
				if ($this->input->post('tipo_destino') === 'escuela') {
					$this->load->model('escuela_model');
					$this->array_destino_control = $this->get_array('escuela', 'nombre_largo', 'id', array('select' => array('id', 'numero', 'anexo', 'nombre')));
				} elseif ($this->input->post('tipo_destino') === 'area') {
					$this->load->model('areas/area_model');
					$this->array_destino_control = $this->get_array('area', 'area', 'id', array('select' => array('id', "CONCAT(codigo, ' - ', descripcion) as area")));
				} else {
					$this->array_destino_control = array('' => '');
				}
			}

			if ($this->form_validation->run() === TRUE) {
				$this->db->trans_begin();
				$trans_ok = TRUE;

				$funcion_anterior = $this->servicio_funcion_model->get_funcion_activa($servicio_id);
				$trans_ok &= $this->servicio_funcion_model->update(array(
					'id' => $funcion_anterior->id,
					'fecha_hasta' => $this->get_date_sql('fecha_desde')
					), FALSE);

				if ($trans_ok) {
					$fields_create = array(
						'servicio_id' => $servicio->id,
						'funcion_id' => $this->input->post('funcion'),
						'norma' => $this->input->post('norma'),
						'tarea' => $this->input->post('tarea'),
						'carga_horaria' => $this->input->post('carga_horaria'),
						'fecha_desde' => $this->get_date_sql('fecha_desde'),
						'fecha_hasta' => $this->get_date_sql('fecha_hasta')
					);
					if ($this->input->post('tipo_destino') === 'escuela') {
						$fields_create['escuela_id'] = $this->input->post('destino');
						$fields_create['destino'] = $this->array_destino_control[$this->input->post('destino')];
					} elseif ($this->input->post('tipo_destino') === 'area') {
						$fields_create['area_id'] = $this->input->post('destino');
						$fields_create['destino'] = $this->array_destino_control[$this->input->post('destino')];
					}
					$trans_ok &= $this->servicio_funcion_model->create($fields_create, FALSE);
				}
				if ($this->db->trans_status() && $trans_ok) {
					$this->db->trans_commit();
					$this->session->set_flashdata('message', $this->servicio_funcion_model->get_msg());
				} else {
					$this->db->trans_rollback();
					$errors = 'Ocurrió un error al intentar agregar.';
					if ($this->servicio_funcion_model->get_error())
						$errors .= '<br>' . $this->servicio_funcion_model->get_error();
				}
				redirect("servicio/editar/$servicio->id", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("servicio/editar/$servicio->id", 'refresh');
			}
		}

		$this->servicio_funcion_model->fields['funcion']['array'] = $array_funcion;
		$this->servicio_funcion_model->fields['destino']['array'] = array();
		$data['fields'] = $this->build_fields($this->servicio_funcion_model->fields);
		$data['fn_mostrar_campos'] = $this->funcion_model->get_fn_mostrar_campos();

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar función de servicio';
		$this->load->view('servicio_funcion/servicio_funcion_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$servicio_funcion = $this->servicio_funcion_model->get(array('id' => $id));

		if (empty($servicio_funcion)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}

		$servicio = $this->servicio_model->get_one($servicio_funcion->servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el registro del servicios', 'Registro no encontrado');
			return;
		}

		$escuela = $this->escuela_model->get_one($servicio->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$this->array_funcion_control = $array_funcion = $this->get_array('funcion', 'descripcion', 'id', null, array('' => ' --Seleccione una función --'));
		$this->array_tarea_control = $this->servicio_funcion_model->fields['tarea']['array'];
		$this->array_tipo_destino_control = $this->servicio_funcion_model->fields['tipo_destino']['array'];

		if (!empty($servicio_funcion->escuela_id)) {
			$servicio_funcion->tipo_destino = 'escuela';
			$servicio_funcion->destino = $servicio_funcion->escuela_id;
			$this->load->model('escuela_model');
			$this->array_destino_control = $array_destino = $this->get_array('escuela', 'nombre_largo', 'id', array('select' => array('id', 'numero', 'anexo', 'nombre')));
		} elseif (!empty($servicio_funcion->area_id)) {
			$servicio_funcion->tipo_destino = 'area';
			$servicio_funcion->destino = $servicio_funcion->area_id;
			$this->load->model('areas/area_model');
			$this->array_destino_control = $array_destino = $this->get_array('area', 'area', 'id', array('select' => array('id', "CONCAT(codigo, ' - ', descripcion) as area")));
		} else {
			$servicio_funcion->tipo_destino = '';
			$array_destino = array();
			$this->array_destino_control = array('' => '');
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			if ($this->input->post('tipo_destino') !== $servicio_funcion->tipo_destino) {
				if ($this->input->post('tipo_destino') === 'escuela') {
					$this->load->model('escuela_model');
					$this->array_destino_control = $this->get_array('escuela', 'nombre_largo', 'id', array('select' => array('id', 'numero', 'anexo', 'nombre')));
				} elseif ($this->input->post('tipo_destino') === 'area') {
					$this->load->model('areas/area_model');
					$this->array_destino_control = $this->get_array('area', 'area', 'id', array('select' => array('id', "CONCAT(codigo, ' - ', descripcion) as area")));
				} else {
					$this->array_destino_control = array('' => '');
				}
			}
			$this->set_model_validation_rules($this->servicio_funcion_model);
			if ($this->form_validation->run() === TRUE) {
				$fields_update = array(
					'id' => $this->input->post('id'),
					'funcion_id' => $this->input->post('funcion'),
					'norma' => $this->input->post('norma'),
					'tarea' => $this->input->post('tarea'),
					'carga_horaria' => $this->input->post('carga_horaria'),
					'fecha_desde' => $this->get_date_sql('fecha_desde'),
					'area_id' => 'NULL',
					'escuela_id' => 'NULL'
				);
				if ($this->input->post('tipo_destino') === 'escuela') {
					$fields_update['escuela_id'] = $this->input->post('destino');
					$fields_update['destino'] = $this->array_destino_control[$this->input->post('destino')];
				} elseif ($this->input->post('tipo_destino') === 'area') {
					$fields_update['area_id'] = $this->input->post('destino');
					$fields_update['destino'] = $this->array_destino_control[$this->input->post('destino')];
				} else {
					$fields_update['destino'] = '';
				}
				$trans_ok = TRUE;
				$trans_ok &= $this->servicio_funcion_model->update($fields_update);
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->servicio_funcion_model->get_msg());
					redirect("servicio/editar/$servicio_funcion->servicio_id", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("servicio/editar/$servicio_funcion->servicio_id", 'refresh');
			}
		}

		$this->servicio_funcion_model->fields['funcion']['array'] = $array_funcion;
		$this->servicio_funcion_model->fields['destino']['array'] = $array_destino;
		$data['fields'] = $this->build_fields($this->servicio_funcion_model->fields, $servicio_funcion);
		$data['fn_mostrar_campos'] = $this->funcion_model->get_fn_mostrar_campos();

		$data['servicio_funcion'] = $servicio_funcion;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar función de servicio';
		$this->load->view('servicio_funcion/servicio_funcion_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$servicio_funcion = $this->servicio_funcion_model->get_one($id);
		if (empty($servicio_funcion)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}


		$servicio = $this->servicio_model->get_one($servicio_funcion->servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el registro del servicio', 'Registro no encontrado');
			return;
		}

		$escuela = $this->escuela_model->get_one($servicio->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->servicio_funcion_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->servicio_funcion_model->get_msg());
				redirect("servicio/editar/$servicio_funcion->servicio_id", 'refresh');
			}
		}
		$data['fields'] = $this->build_fields($this->servicio_funcion_model->fields, $servicio_funcion, TRUE);

		$data['servicio_funcion'] = $servicio_funcion;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar función de servicio';
		$this->load->view('servicio_funcion/servicio_funcion_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$servicio_funcion = $this->servicio_funcion_model->get_one($id);
		if (empty($servicio_funcion)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$servicio = $this->servicio_model->get_one($servicio_funcion->servicio_id);
		if (empty($servicio)) {
			$this->modal_error('No se encontró el registro del servicio', 'Registro no encontrado');
			return;
		}

		$escuela = $this->escuela_model->get_one($servicio->escuela_id);
		if (empty($escuela)) {
			$this->modal_error('No se encontró el registro de la escuela', 'Registro no encontrado');
			return;
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			$this->modal_error('No tiene permisos para acceder a la escuela', 'Acción no autorizada');
			return;
		}

		$data['fields'] = $this->build_fields($this->servicio_funcion_model->fields, $servicio_funcion, TRUE);
		$data['fn_mostrar_campos'] = $this->funcion_model->get_fn_mostrar_campos();

		$data['servicio_funcion'] = $servicio_funcion;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver función de servicio';
		$this->load->view('servicio_funcion/servicio_funcion_modal_abm', $data);
	}

	public function horarios($id = NULL, $es_funcion = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$servicio_funcion = $this->servicio_funcion_model->get_one($id);
		if (empty($servicio_funcion)) {
			show_error('No se encontró el registro de función', 500, 'Registro no encontrado');
		}
		if (!empty($servicio_funcion->escuela_id)) {
			$servicio_funcion->tipo_destino = 'Escuela';
		} elseif (!empty($servicio_funcion->area_id)) {
			$servicio_funcion->tipo_destino = 'Área';
		} else {
			$servicio_funcion->tipo_destino = '';
		}

		$servicio = $this->servicio_model->get_one($servicio_funcion->servicio_id);
		if (empty($servicio)) {
			show_error('No se encontró el registro de servicio', 500, 'Registro no encontrado');
		}

		if ($es_funcion === '1') {
			$escuela = $this->escuela_model->get_one($servicio_funcion->escuela_id);
		} else {
			$escuela = $this->escuela_model->get_one($servicio->escuela_id);
		}
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		if (substr($servicio->regimen, 1, 6) !== '560201') {
			unset($this->servicio_model->fields['celador_concepto']);
		}

		$this->load->model('dia_model');
		$this->load->model('servicio_funcion_horario_model');
		$dias = $this->dia_model->get(array('sort_by' => 'id'));
		$horarios = $this->servicio_funcion_horario_model->get(array(
			'servicio_funcion_id' => $servicio_funcion->id,
			'sort_by' => 'dia_id, hora_desde'
		));

		$horarios_fn = array();
		if (!empty($horarios)) {
			foreach ($horarios as $horario) {
				$horarios_fn[$horario->dia_id][] = $horario;
			}
		}

		$data['fields'] = $this->build_fields($this->servicio_model->fields, $servicio, TRUE);
		$data['fields_funcion'] = $this->build_fields($this->servicio_funcion_model->fields, $servicio_funcion, TRUE);
		$data['fn_mostrar_campos'] = $this->funcion_model->get_fn_mostrar_campos();
		$data['error'] = $this->session->flashdata('error');
		$data['servicio_funcion'] = $servicio_funcion;
		$data['servicio'] = $servicio;
		$data['cargo'] = $servicio;
		$data['escuela'] = $escuela;
		$data['horarios_fn'] = $horarios_fn;
		$data['dias'] = $dias;
		$data['es_funcion'] = $es_funcion;
		$data['title'] = TITLE . ' - Horarios función de servicio';
		$this->load_template('servicio_funcion/servicio_funcion_horarios', $data);
	}

	public function ver($id = NULL, $es_funcion = '1') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$servicio_funcion = $this->servicio_funcion_model->get_one($id);
		if (empty($servicio_funcion)) {
			show_error('No se encontró el registro de función', 500, 'Registro no encontrado');
		}
		if (!empty($servicio_funcion->escuela_id)) {
			$servicio_funcion->tipo_destino = 'Escuela';
		} elseif (!empty($servicio_funcion->area_id)) {
			$servicio_funcion->tipo_destino = 'Área';
		} else {
			$servicio_funcion->tipo_destino = '';
		}

		$servicio = $this->servicio_model->get_one($servicio_funcion->servicio_id);
		if (empty($servicio)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}

		if ($es_funcion === '1') {
			$escuela = $this->escuela_model->get_one($servicio_funcion->escuela_id);
		} else {
			$escuela = $this->escuela_model->get_one($servicio->escuela_id);
		}
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		if (substr($servicio->regimen, 1, 6) !== '560201') {
			unset($this->servicio_model->fields['celador_concepto']);
		}

		$this->load->model('dia_model');
		$this->load->model('servicio_funcion_horario_model');
		$dias = $this->dia_model->get(array('sort_by' => 'id'));
		$horarios = $this->servicio_funcion_horario_model->get(array(
			'servicio_funcion_id' => $servicio_funcion->id,
			'sort_by' => 'dia_id, hora_desde'
		));

		$horarios_fn = array();
		if (!empty($horarios)) {
			foreach ($horarios as $horario) {
				$horarios_fn[$horario->dia_id][] = $horario;
			}
		}

		$data['fields'] = $this->build_fields($this->servicio_model->fields, $servicio, TRUE);
		$data['fields_funcion'] = $this->build_fields($this->servicio_funcion_model->fields, $servicio_funcion, TRUE);
		$data['fn_mostrar_campos'] = $this->funcion_model->get_fn_mostrar_campos();
		$data['error'] = $this->session->flashdata('error');
		$data['servicio_funcion'] = $servicio_funcion;
		$data['servicio'] = $servicio;
		$data['cargo'] = $servicio;
		$data['escuela'] = $escuela;
		$data['horarios_fn'] = $horarios_fn;
		$data['dias'] = $dias;
		$data['es_funcion'] = $es_funcion;
		$data['title'] = TITLE . ' - Ver servicio';
		$this->load_template('servicio_funcion/servicio_funcion_ver', $data);
	}
}
/* End of file Servicio_funcion.php */
/* Location: ./application/controllers/Servicio_funcion.php */