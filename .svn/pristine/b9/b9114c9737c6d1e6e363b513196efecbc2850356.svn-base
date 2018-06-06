<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alumno_apoyo_especial extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('alumno_apoyo_especial_model');
		$this->load->model('alumno_division_model');
		$this->load->model('alumno_model');
		$this->load->model('escuela_model');
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_DIR_ESCUELA, ROL_SDIR_ESCUELA, ROL_SEC_ESCUELA, ROL_ESCUELA_ALUM, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION, ROL_DOCENTE);
		$this->nav_route = 'par/alumno_apoyo_especial';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$tableData = array(
			'columns' => array(
				array('label' => 'Documento', 'data' => 'documento', 'width' => 5),
				array('label' => 'Alumno', 'data' => 'alumno', 'class' => 'dt-body-right', 'width' => 25),
				array('label' => 'Escuela de apoyo', 'data' => 'escuela_apoyo', 'class' => 'dt-body-right', 'width' => 25),
				array('label' => 'Escuela de origen', 'data' => 'escuela_origen', 'width' => 25),
				array('label' => 'Trayectoria compartida', 'data' => 'trayectoria_compartida', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'CUD', 'data' => 'cud', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Cohorte Incial', 'data' => 'cohorte_inicial', 'width' => 5, 'class' => 'text-sm'),
				array('label' => '', 'data' => '', 'width' => 5, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'apoyo_table',
			'source_url' => "alumno_apoyo_especial/listar_data/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_apoyo_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['title'] = 'Alumnos con apoyo de modalidad especial';
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		return $this->load_template('alumno_apoyo_especial/alumno_apoyo_especial_listar', $data);
	}

	public function listar_data($rol_codigo, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}

		$this->datatables
			->select("a.id as alumno_id, ap.id as alumno_apoyo_especial_id, p.id as persona_id,e.id as escuela_apoyo_id,eo.id as escuela_origen_id,CONCAT(e.numero,' - ',e.nombre) as escuela_apoyo,CONCAT(eo.numero,' - ',eo.nombre) as escuela_origen ,
CONCAT(COALESCE(dt.descripcion_corta,''),' ',COALESCE(p.documento,'')) as documento, 
CONCAT(COALESCE(p.apellido,''),', ',COALESCE(p.nombre,'')) AS alumno, ap.cohorte_inicial, ap.cud as cud, ap.trayectoria_compartida")
			->from('alumno_apoyo_especial ap')
			->join('escuela e', 'e.id= ap.escuela_id', 'left')
			->join('escuela eo', 'eo.id= ap.escuela_origen_id', 'left')
			->join('alumno a ', 'a.id=ap.alumno_id', 'left')
			->join('persona p', 'p.id = a.persona_id', 'left')
			->join('documento_tipo dt', 'p.documento_tipo_id=dt.id', 'left');
		$this->datatables->add_column('', '', '');
		echo $this->datatables->generate();
	}

	public function excel_reporte() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$campos = array(
			'A' => array('Documento', 15),
			'B' => array('Alumno', 35),
			'C' => array('Escuela de apoyo', 35),
			'D' => array('Escuela de origen', 35),
			'E' => array('Trayectoria compartida', 10),
			'F' => array('CUD', 10),
			'G' => array('Cohorte Inicial', 10),
		);

		$alumnos = $this->db
				->select("CONCAT(COALESCE(dt.descripcion_corta,''),' ',COALESCE(p.documento,'')) as documento,
CONCAT(COALESCE(p.apellido,''),', ',COALESCE(p.nombre,'')) AS alumno,CONCAT(e.numero,' - ',e.nombre) as escuela_apoyo,CONCAT(eo.numero,' - ',eo.nombre) as escuela_origen,ap.trayectoria_compartida ,ap.cud as cud,ap.cohorte_inicial")
				->from('alumno_apoyo_especial ap')
				->join('escuela e', 'e.id= ap.escuela_id', 'left')
				->join('escuela eo', 'eo.id= ap.escuela_origen_id', 'left')
				->join('alumno a ', 'a.id=ap.alumno_id', 'left')
				->join('persona p', 'p.id = a.persona_id', 'left')
				->join('documento_tipo dt', 'p.documento_tipo_id=dt.id', 'left')
				->get()->result_array();

		if (!empty($alumnos)) {
			$this->exportar_excel(array('title' => "Reporte de alumnos con apoyo de modalidad especial"), $campos, $alumnos);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect('alumno_apoyo_especial/listar', 'refresh');
		}
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
		$this->alumno_apoyo_especial_model->fields['alumno']['value'] = "$alumno->apellido, $alumno->nombre";


		if (empty($alumno_division->ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $alumno_division->ciclo_lectivo))) {
			$alumno_division->ciclo_lectivo = date('Y');
		}

		$this->array_escuela_control = $array_escuela = $this->get_array('escuela', 'nombre_largo', 'id', array('nivel_id' => 6, 'where' => array("numero NOT IN('7069','7636')")), array('' => '-- Seleccionar escuela --'));
		$this->array_cud_control = $this->alumno_apoyo_especial_model->fields['cud']['array'];
		$this->array_trayectoria_compartida_control = $this->alumno_apoyo_especial_model->fields['trayectoria_compartida']['array'];

		$this->set_model_validation_rules($this->alumno_apoyo_especial_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->alumno_apoyo_especial_model->create(array(
					'alumno_id' => $alumno_division->alumno_id,
					'escuela_origen_id' => $escuela->id,
					'escuela_id' => $this->input->post('escuela'),
					'cud' => $this->input->post('cud'),
					'cohorte_inicial' => $this->input->post('cohorte_inicial'),
					'trayectoria_compartida' => $this->input->post('trayectoria_compartida'),
					'fecha_grabacion' => date('Y-m-d')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->alumno_apoyo_especial_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->alumno_apoyo_especial_model->get_error());
				}
				redirect("alumno/editar/$alumno_division_id#apoyo", 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("alumno/editar/$alumno_division_id#apoyo", 'refresh');
			}
		}


		$this->alumno_apoyo_especial_model->fields['escuela']['array'] = $array_escuela;
		$data['fields'] = $this->build_fields($this->alumno_apoyo_especial_model->fields);
		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar apoyo de modalidad especial';
		$this->load->view('alumno_apoyo_especial/alumno_apoyo_especial_modal_abm', $data);
	}

	public function modal_editar($alumno_division_id = NULL, $alumno_apoyo_especial_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id) || $alumno_apoyo_especial_id == NULL || !ctype_digit($alumno_apoyo_especial_id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$alumno_apoyo_especial = $this->alumno_apoyo_especial_model->get(array('id' => $alumno_apoyo_especial_id));
		if (empty($alumno_apoyo_especial)) {
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
		$alumno_apoyo_especial->alumno = "$alumno->apellido, $alumno->nombre";
		$alumno_apoyo_especial->cohorte_inicial = $alumno_apoyo_especial->cohorte_inicial;
		$this->array_cud_control = $this->alumno_apoyo_especial_model->fields['cud']['array'];
		$this->array_trayectoria_compartida_control = $this->alumno_apoyo_especial_model->fields['trayectoria_compartida']['array'];
		$this->array_escuela_control = $array_escuela = $this->get_array('escuela', 'nombre_largo', 'id', array('nivel_id' => 6, 'where' => array("numero NOT IN('7069','7636')")), array('' => '-- Seleccionar escuela --'));
		$this->set_model_validation_rules($this->alumno_apoyo_especial_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_apoyo_especial_id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->alumno_apoyo_especial_model->update(array(
					'id' => $alumno_apoyo_especial_id,
					'alumno_id' => $alumno->id,
					'escuela_origen_id' => $escuela->id,
					'escuela_id' => $this->input->post('escuela'),
					'cud' => $this->input->post('cud'),
					'cohorte_inicial' => $this->input->post('cohorte_inicial'),
					'trayectoria_compartida' => $this->input->post('trayectoria_compartida'),
					'fecha_grabada' => $this->input->post('fecha_grabada')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->alumno_apoyo_especial_model->get_msg());
					redirect("alumno/editar/$alumno_division_id#apoyo", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("alumno/editar/$alumno_division_id#apoyo", 'refresh');
			}
		}

		$this->alumno_apoyo_especial_model->fields['escuela']['array'] = $array_escuela;
		$data['fields'] = $this->build_fields($this->alumno_apoyo_especial_model->fields, $alumno_apoyo_especial);
		$data['alumno_apoyo_especial'] = $alumno_apoyo_especial;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar apoyo de modalidad especial';
		$this->load->view('alumno_apoyo_especial/alumno_apoyo_especial_modal_abm', $data);
	}

	public function modal_eliminar($alumno_division_id = NULL, $alumno_apoyo_especial_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $alumno_division_id == NULL || !ctype_digit($alumno_division_id) || $alumno_apoyo_especial_id == NULL || !ctype_digit($alumno_apoyo_especial_id)) {
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

		$alumno_apoyo_especial = $this->alumno_apoyo_especial_model->get_one($alumno_apoyo_especial_id);
		if (empty($alumno_apoyo_especial)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_apoyo_especial_id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$trans_ok = TRUE;
			$trans_ok &= $this->alumno_apoyo_especial_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->alumno_apoyo_especial_model->get_msg());
				redirect("alumno/editar/$alumno_division_id#apoyo", 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->alumno_apoyo_especial_model->fields, $alumno_apoyo_especial, TRUE);

		$data['alumno_apoyo_especial'] = $alumno_apoyo_especial;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar apoyo de modalidad especial';
		$this->load->view('alumno_apoyo_especial/alumno_apoyo_especial_modal_abm', $data);
	}
}
/* End of file Alumno_apoyo_especial.php */
/* Location: ./application/controllers/Alumno_apoyo_especial.php */