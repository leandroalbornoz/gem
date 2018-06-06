<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alumno_derivacion extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('alumno_derivacion_model');
		$this->load->model('alumno_division_model');
		$this->load->model('alumno_model');
		$this->load->model('diagnostico_model');
		$this->load->model('escuela_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_ESCUELA_ALUM, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION, ROL_DOCENTE);
		$this->roles_linea = array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_CONSULTA_LINEA, ROL_CONSULTA);
		$this->nav_route = 'par/alumno_derivacion';
	}

	public function modal_agregar($alumno_division_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

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

		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		$this->alumno_derivacion_model->fields['alumno']['value'] = "$alumno->apellido, $alumno->nombre";
		$this->array_diagnostico_control = $array_diagnostico = $this->get_array('diagnostico', 'detalle', 'id', array('sort_by' => 'id'), array('' => '-- Seleccionar diagnóstico --'));
		$this->array_escuela_control = $array_escuela = $this->get_array('escuela', 'nombre_largo', 'id', array(
			'join' => array(array('escuela_prestacion', 'escuela.id=escuela_prestacion.escuela_id')),
			'sort_by' => 'escuela.numero, escuela.anexo'
			), array('' => '-- Seleccionar escuela --'));

		$this->set_model_validation_rules($this->alumno_derivacion_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->alumno_derivacion_model->create(array(
					'alumno_id' => $alumno_division->alumno_id,
					'escuela_origen_id' => $escuela->id,
					'escuela_id' => $this->input->post('escuela'),
					'ingreso' => $this->get_date_sql('ingreso'),
					'egreso' => $this->get_date_sql('egreso'),
					'diagnostico_id' => $this->input->post('diagnostico'),
					'fecha_grabacion' => date('Y-m-d')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->alumno_derivacion_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->alumno_derivacion_model->get_error());
				}
				redirect("alumno/editar/$alumno_division_id#derivaciones", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("alumno/editar/$alumno_division_id#derivaciones", 'refresh');
			}
		}

		$this->alumno_derivacion_model->fields['diagnostico']['array'] = $array_diagnostico;
		$this->alumno_derivacion_model->fields['escuela']['array'] = $array_escuela;
		$data['fields'] = $this->build_fields($this->alumno_derivacion_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar derivación Hospitalaria/Domiciliaria';
		$this->load->view('alumno_derivacion/alumno_derivacion_modal_abm', $data);
	}

	public function modal_editar($alumno_division_id = NULL, $alumno_derivacion_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id) || !ctype_digit($alumno_derivacion_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$alumno_derivacion = $this->alumno_derivacion_model->get(array('id' => $alumno_derivacion_id));
		if (empty($alumno_derivacion)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}

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

		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		$alumno_derivacion->alumno = "$alumno->apellido, $alumno->nombre";
		$this->array_diagnostico_control = $array_diagnostico = $this->get_array('diagnostico', 'detalle', 'id', null, array('' => '-- Seleccionar diagnóstico --'));
		$this->array_escuela_control = $array_escuela = $this->get_array('escuela', 'nombre_largo', 'id', array('nivel_id' => 11), array('' => '-- Seleccionar escuela --'));

		$this->set_model_validation_rules($this->alumno_derivacion_model);

		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_derivacion_id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->alumno_derivacion_model->update(array(
					'id' => $alumno_derivacion_id,
					'alumno_id' => $alumno_division->alumno_id,
					'escuela_origen_id' => $escuela->id,
					'escuela_id' => $this->input->post('escuela'),
					'ingreso' => $this->get_date_sql('ingreso'),
					'egreso' => $this->get_date_sql('egreso'),
					'diagnostico_id' => $this->input->post('diagnostico'),
					'fecha_grabacion' => date('Y-m-d')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->alumno_derivacion_model->get_msg());
					redirect("alumno/editar/$alumno_division_id#derivaciones", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("alumno/editar/$alumno_division_id#derivaciones", 'refresh');
			}
		}

		$this->alumno_derivacion_model->fields['diagnostico']['array'] = $array_diagnostico;
		$this->alumno_derivacion_model->fields['escuela']['array'] = $array_escuela;
		$data['fields'] = $this->build_fields($this->alumno_derivacion_model->fields, $alumno_derivacion);
		$data['alumno_derivacion'] = $alumno_derivacion;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar derivación hospitalaria/domiciliaria';
		$this->load->view('alumno_derivacion/alumno_derivacion_modal_abm', $data);
	}

	public function modal_eliminar($alumno_division_id = NULL, $alumno_derivacion_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id) || !ctype_digit($alumno_derivacion_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$alumno_derivacion = $this->alumno_derivacion_model->get_one($alumno_derivacion_id);
		if (empty($alumno_derivacion)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}

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

		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_derivacion_id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->alumno_derivacion_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->alumno_derivacion_model->get_msg());
				redirect("alumno/editar/$alumno_division_id#derivaciones", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->alumno_derivacion_model->fields, $alumno_derivacion, TRUE);
		$data['alumno_derivacion'] = $alumno_derivacion;
		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar derivación hospitalaria/domiciliaria';
		$this->load->view('alumno_derivacion/alumno_derivacion_modal_abm', $data);
	}

	public function listar($linea_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_linea) || $linea_id == NULL || !ctype_digit($linea_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('linea_model');
		$linea = $this->linea_model->get_one($linea_id);
		if (empty($linea)) {
			show_error('No se encontró el registro', 500, 'Registro no encontrado');
		}
		if (in_array($this->rol->codigo, array(ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_CONSULTA_LINEA)) && $this->rol->entidad_id !== $linea_id) {
			show_error('No tiene permisos para acceder a la lista de alumnos', 500, 'Acción no autorizada');
		}

		$tableData = array(
			'columns' => array(
				array('label' => 'Documento', 'data' => 'documento', 'width' => 9, 'class' => 'text-sm'),
				array('label' => 'Alumno', 'data' => 'persona', 'width' => 15, 'class' => 'text-sm'),
				array('label' => 'Curso - Div.', 'data' => 'curso', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Patología', 'data' => 'diagnostico', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Escuela origen', 'data' => 'escuela_origen', 'width' => 15, 'class' => 'text-sm'),
				array('label' => 'Esc. D y H ó Servicio', 'data' => 'escuela', 'width' => 15, 'class' => 'text-sm'),
				array('label' => 'F. Entrada', 'data' => 'ingreso', 'render' => 'short_date', 'width' => 8),
				array('label' => 'F. Salida', 'data' => 'egreso', 'render' => 'short_date', 'width' => 8),
				array('label' => '', 'data' => 'edit', 'width' => 5, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'alumno_derivacion_table',
			'source_url' => "alumno_derivacion/listar_data/$linea_id",
			'order' => array(array(2, 'asc'), array(3, 'asc'), array(0, 'asc')),
			'responsive' => FALSE,
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_derivacion_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);

		$data['cant_alumnos'] = $this->alumno_derivacion_model->get_cant_alumnos();
		$data['cant_alumnos_baja'] = $this->alumno_derivacion_model->get_cant_alumnos_baja();
		$data['cant_alumnos_alta'] = $this->alumno_derivacion_model->get_cant_alumnos_alta();
		$data['linea'] = $linea;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Alumnos';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('alumno_derivacion/alumno_derivacion_listar', $data);
	}

	public function listar_data($linea_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_linea) || $linea_id == NULL || !ctype_digit($linea_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('linea_model');
		$linea = $this->linea_model->get_one($linea_id);
		if (empty($linea)) {
			show_error('No se encontró el registro de las escuelas', 500, 'Registro no encontrado');
		}
		if (in_array($this->rol->codigo, array(ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_CONSULTA_LINEA)) && $this->rol->entidad_id !== $linea_id) {
			show_error('No tiene permisos para acceder a la lista de alumnos', 500, 'Acción no autorizada');
		}

		$this->datatables->select("alumno_division.id, alumno.persona_id, alumno.observaciones, alumno_derivacion.ingreso, alumno_derivacion.egreso, 
			CONCAT(documento_tipo.descripcion_corta, ' ', persona.documento) as documento, persona.documento_tipo_id, 
			CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, 
			CONCAT(COALESCE(escuela.numero, ''), ' - ', COALESCE(escuela.nombre, '')) as escuela,
			GROUP_CONCAT(CONCAT(COALESCE(es3.numero, ''), ' - ', COALESCE(es3.nombre, '')) ORDER BY nivel.formal DESC SEPARATOR '<br>') as escuela_origen,
			GROUP_CONCAT(CONCAT(COALESCE(curso.descripcion, ''), ' - ', COALESCE(division.division, '')) ORDER BY nivel.formal DESC SEPARATOR '<br>') as curso, diagnostico.detalle as diagnostico")
			->unset_column('id')
			->from('alumno_derivacion')
			->join('alumno', 'alumno.id = alumno_derivacion.alumno_id', 'left')
			->join('persona', 'persona.id = alumno.persona_id', 'left')
			->join('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id', 'left')
			->join('alumno_division', 'alumno_division.alumno_id = alumno.id', 'left')
			->join('division', 'division.id = alumno_division.division_id', 'left')
			->join('escuela es3', 'es3.id = division.escuela_id AND nivel_id!=11', 'left')
			->join('nivel', 'nivel.id = es3.nivel_id', 'left')
			->join('curso', 'division.curso_id = curso.id', 'left')
			->join('diagnostico', 'alumno_derivacion.diagnostico_id = diagnostico.id', 'left')
			->join('escuela', 'escuela.id = alumno_derivacion.escuela_id', 'left')
			->join('escuela es2', 'es2.id = alumno_derivacion.escuela_origen_id', 'left')
			->where('alumno_division.fecha_hasta IS NULL')
			->where('alumno_derivacion.egreso IS NULL')
			->group_by('alumno_derivacion.id');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '');
		} else {
			$this->datatables->add_column('edit', '<div class="dt-body-center" role="group">'
				. '');
		}

		echo $this->datatables->generate();
	}

	public function excel($linea_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_linea) || $linea_id == NULL || !ctype_digit($linea_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('linea_model');
		$linea = $this->linea_model->get_one($linea_id);
		if (empty($linea)) {
			show_error('No se encontró el registro', 500, 'Registro no encontrado');
		}
		if (in_array($this->rol->codigo, array(ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_CONSULTA_LINEA)) && $this->rol->entidad_id !== $linea_id) {
			show_error('No tiene permisos para acceder a la lista de alumnos', 500, 'Acción no autorizada');
		}


		$campos = array(
			'A' => array('Documento', 15),
			'B' => array('Alumno', 30),
			'C' => array('Curso - Div.', 20),
			'D' => array('Patología', 35),
			'E' => array('Escuela origeno', 45),
			'F' => array('Esc. D y H ó Servicio', 45),
			'G' => array('F. Entrada', 15),
			'H' => array('F. Salida', 15)
		);

		$alumnos = $this->db->select('CONCAT(documento_tipo.descripcion_corta, \' \', persona.documento) as documento, CONCAT(COALESCE(persona.apellido, \'\'), \', \', COALESCE(persona.nombre, \'\')) as persona, '
					. 'CONCAT(COALESCE(curso.descripcion, \'\'), \' - \', COALESCE(division.division, \'\')) as curso2, diagnostico.detalle as diagnostico, CONCAT(COALESCE(es2.numero, \'\'), \' - \', COALESCE(es2.nombre, \'\')) as escuela_origen, CONCAT(COALESCE(escuela.numero, \'\'), \' - \', COALESCE(escuela.nombre, \'\')) as escuela,alumno_derivacion.ingreso, alumno_derivacion.egreso')
				->from('alumno_derivacion')
				->join('alumno', 'alumno.id = alumno_derivacion.alumno_id', 'left')
				->join('persona', 'persona.id = alumno.persona_id', 'left')
				->join('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id', 'left')
				->join('alumno_division', 'alumno_division.alumno_id = alumno.id', 'left')
				->join('division', 'division.id = alumno_division.division_id', 'left')
				->join('escuela es3', 'es3.id = division.escuela_id', 'left')
				->join('nivel', 'nivel.id = es3.nivel_id', 'left')
				->join('curso', 'division.curso_id = curso.id', 'left')
				->join('diagnostico', 'alumno_derivacion.diagnostico_id = diagnostico.id', 'left')
				->join('escuela', 'escuela.id = alumno_derivacion.escuela_id', 'left')
				->join('escuela es2', 'es2.id = alumno_derivacion.escuela_origen_id', 'left')
				->where("COALESCE(nivel.formal, 'Si')=", 'Si')
				->where('alumno_division.fecha_hasta IS NULL')
				->get()->result_array();

		if (!empty($alumnos)) {
			$this->exportar_excel(array('title' => "Alumnos derivacion "), $campos, $alumnos);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect("alumno_derivacion/listar/$linea_id", 'refresh');
		}
	}
}
/* End of file Alumno_derivacion.php */
/* Location: ./application/controllers/Alumno_derivacion.php */