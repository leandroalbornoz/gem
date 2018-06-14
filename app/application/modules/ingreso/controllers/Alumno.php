<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alumno extends MY_Controller {

	function __construct() {
		parent::__construct();
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('escuela_model');
		$this->load->model('alumno_model');
		$this->load->model('persona_model');
		//$this->roles_permitidos = array(ROL_ADMIN, ROL_USI);
		$this->roles_admin = array(ROL_ADMIN, ROL_USI);
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE_CURSADA));
		if (in_array($this->rol->codigo, array(ROL_SUPERVISION, ROL_CONSULTA, ROL_CONSULTA_LINEA, ROL_GRUPO_ESCUELA_CONSULTA, ROL_REGIONAL, ROL_ESCUELA_CAR))) {
			$this->edicion = FALSE;
		}
		$this->nav_route = 'menu/alumno';
	}

	public function listar($escuela_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || ($ciclo_lectivo != NULL && !ctype_digit($ciclo_lectivo))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("ingreso/alumno/listar/$escuela_id/$ciclo_lectivo", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}
		$this->load->model('ingreso_alumno_model');
		$ingreso_operativo_id = 1;
		$index = 0;
		$alumnos = $this->db->query("
			SELECT a.id as alumno_id, CONCAT(e.numero,' - ', e.nombre) as nombre_escuela, ad.id as alumno_division_id, a.persona_id, ia.asignado, ia.abanderado_escuela_id, io.nombre, ia.promedio, ia.abanderado, a.email_contacto, ia.motivo_no_participa, ia.participa, a.observaciones, ad.fecha_desde, ad.fecha_hasta, CONCAT(COALESCE(dt.descripcion_corta,''),' ',COALESCE(p.documento,' ')) as documento, p.documento_tipo_id, CONCAT_WS(', ', p.apellido, p.nombre) as persona, CASE c.grado_multiple WHEN 'Si' THEN CONCAT(d.division, ' (', COALESCE(c_gm.descripcion, ''), ')') ELSE d.division END as division, c.descripcion as curso, d.escuela_id, c.grado_multiple, ad.estado_id
			FROM alumno a
			LEFT JOIN ingreso_alumno ia ON a.id=ia.alumno_id
			LEFT JOIN ingreso_operativo io ON io.id=ia.ingreso_operativo_id
			JOIN alumno_division ad ON a.id=ad.alumno_id AND ad.fecha_hasta IS NULL
			JOIN persona p ON a.persona_id=p.id
			JOIN division d ON ad.division_id=d.id
			JOIN escuela e ON d.escuela_id=e.id
			JOIN curso c ON d.curso_id=c.id
			LEFT JOIN curso c_gm ON ad.curso_id=c_gm.id
			JOIN documento_tipo dt ON p.documento_tipo_id=dt.id
			WHERE d.escuela_id=? AND ad.ciclo_lectivo=? AND (c.descripcion LIKE '%7%' OR c_gm.descripcion LIKE '%7%')", array($escuela->id, $ciclo_lectivo))->result();
		foreach ($alumnos as $alumno) {
			$ingreso_alumno = $this->ingreso_alumno_model->get(array('alumno_id' => $alumno->alumno_id, 'ingreso_operativo_id' => $ingreso_operativo_id));
			if (empty($ingreso_alumno)) {
				$trans_ok = TRUE;
				$trans_ok &= $this->ingreso_alumno_model->create(array(
					'alumno_id' => $alumno->alumno_id,
					'ingreso_operativo_id' => $ingreso_operativo_id,
					'alumno_division_id' => $alumno->alumno_division_id,
					'hermano_ad_id' => $alumno->alumno_division_id,
					'abanderado' => 'No',
					'participa' => 'Si'
					), FAlSE);
			}
		}
		foreach ($alumnos as $row => $alumno) {
			$index++;
			$alumnos[$row]->promedio = '<div><input class="input-xs text-right form-control notas input-notas" style="width: 60px;"  name="' . $alumno->alumno_division_id . '" data-a="' . $alumno->alumno_id . '" value="' . ((!empty($alumno->promedio)) ? $alumno->promedio : "0" ) . '" tabindex="' . $index . '"><i class="promedio-status pull-right" style="width:30px;">&nbsp;<i class="promedio-status-ok fa fa-check text-green"></i><i class="promedio-status-loading fa fa-spin fa-refresh text-yellow"></i><i class="promedio-status-error fa fa-times text-red"></i></i></div>';
			if ((!empty($alumno->abanderado_escuela_id)) && $alumno->abanderado_escuela_id !== $escuela->id) {
				$alumnos[$row]->abanderado_escolta = '<div>
							<span style="color: red;font-size: 13px; float: left; margin-left: 15%;"><b>Abanderado en</b></span><br>
							<span style="color: red;font-size: 13px; float: left; margin-left: 20%; margin-top: -6%;"><b>otra escuela</b></span>
					</div>';
			} else {
				$alumnos[$row]->abanderado_escolta = '<div>
							<div class="btn-group btn-group-xs check_abanderado" data-toggle="buttons" style="display: inline-flex;">
								<label class="btn btn-default abanderado_no ' . ((empty($alumno->abanderado)) ? "active" : (($alumno->abanderado == 'No') ? "active" : "" )) . '" style="padding: 1px 20px;">
									<input type="radio" name="abanderado" autocomplete="off" checked="" value="No" data-a="' . $alumno->alumno_id . '" data-e="' . $escuela->id . '" data-c="' . $ciclo_lectivo . '"> No
								</label>
								<label class="btn btn-default text-success abanderado ' . (($alumno->abanderado == 'Si') ? "active" : "" ) . '" style="padding: 1px 20px">
									<input type="radio" name="abanderado" autocomplete="off" value="Si" data-a="' . $alumno->alumno_id . '" data-e="' . $escuela->id . '" data-c="' . $ciclo_lectivo . '"> Si
								</label>
							</div>
							<i class="abaderado-status pull-right" style="width:30px;">&nbsp;<i class="abaderado-status-ok fa fa-check text-green"></i><i class="abaderado-status-loading fa fa-spin fa-refresh text-yellow"></i><i class="abaderado-status-error fa fa-times text-red"></i></i>
					</div>';
			}
			$alumnos[$row]->registro_cuenta = '<div></div>';
			$alumnos[$row]->banco_asigando = '<div>' . $alumno->asignado . '</div>';
			$alumnos[$row]->escuela_asiganada = '<div>' . $alumno->nombre_escuela . '</div>';
			$alumnos[$row]->participa = '<div style="float: left;">
				<select class="input-xs text-right" name="participa" data-a="' . $alumno->alumno_id . '">
					<option value="Si" ' . ((empty($alumno->participa)) ? "" : (($alumno->participa == 'Si') ? "selected" : "" )) . '>Si</option>
					<option value="Ingresa a Esc. Privada" ' . ((empty($alumno->motivo_no_participa)) ? "" : (($alumno->motivo_no_participa != 'Ingresa a Esc. Privada') ? "" : "selected")) . '>No, Ingresa a Esc. Privada</option>
					<option value="Ingresa a Esc. UNC" ' . ((empty($alumno->motivo_no_participa)) ? "" : (($alumno->motivo_no_participa != 'Ingresa a Esc. UNC') ? "" : "selected")) . '>No, Ingresa a Esc. UNC</option>
					<option value="Renunció banco" ' . ((empty($alumno->motivo_no_participa)) ? "" : (($alumno->motivo_no_participa != 'Renunció banco') ? "" : "selected")) . '>No, Renunció banco</option>
					<option value="Otros" ' . ((empty($alumno->motivo_no_participa)) ? "" : (($alumno->motivo_no_participa != 'Otros') ? "" : "selected")) . '>No, Por otros Motivos</option>
				</select>
				</div>
				<i class="participa-status pull-right" style="width:29px;">&nbsp;<i class="participa-status-ok fa fa-check text-green"></i><i class="participa-status-loading fa fa-spin fa-refresh text-yellow"></i><i class="participa-status-error fa fa-times text-red"></i></i>';
			$alumnos[$row]->mail_padre_madre = '<div>' . $alumno->email_contacto . '</div>';
			$alumnos[$row]->edit = '';
		}
		$data['alumnos'] = $alumnos;
		$data['escuela'] = $escuela;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['abanderados_baja'] = $abanderados_baja = $this->ingreso_alumno_model->get_abanderados_baja($escuela_id, $ciclo_lectivo);
		$data['cantidad_abaderados'] = $this->ingreso_alumno_model->get_cant_abanderados($escuela_id, $ciclo_lectivo);
		$data['css'][] = 'plugins/odometer/odometer.css';
		$data['js'][] = 'plugins/odometer/odometer.js';
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Alumnos';
		$data['css'][] = 'plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css';
		$data['js'][] = 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js';
		$data['js'][] = 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js';
		$this->load_template('ingreso/alumno/alumno_listar', $data);
	}

	public function listar_data($escuela_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->datatables
			->select("alumno_division.id as id, alumno.persona_id, alumno.observaciones, alumno_division.fecha_desde, alumno_division.fecha_hasta, CONCAT(COALESCE(documento_tipo.descripcion_corta,''),' ',COALESCE(persona.documento,' ')) as documento, persona.documento_tipo_id, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, CASE curso.grado_multiple WHEN 'Si' THEN CONCAT(division.division, ' (', COALESCE(c_gm.descripcion, ''), ')') ELSE division.division END as division, curso.descripcion as curso, division.escuela_id, curso.grado_multiple")
			->unset_column('id')
			->from('alumno')
			->join('persona', 'persona.id = alumno.persona_id', 'left')
			->join('alumno_division', 'alumno_division.alumno_id = alumno.id', 'left')
			->join('division', 'division.id = alumno_division.division_id', 'left')
			->join('curso', 'division.curso_id = curso.id', 'left')
			->join('curso c_gm', 'alumno_division.curso_id = c_gm.id', 'left')
			->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id', 'left')
			->where('division.escuela_id', $escuela_id)
			->where('ciclo_lectivo', $ciclo_lectivo)
			->where("(curso.descripcion like '%7%' OR c_gm.descripcion LIKE '%7%')")
			->add_column('edit', '');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<button class="btn btn-xs btn-info" data-edit="$1"><span class="fa fa-pencil"></span></button><button class="btn btn-xs btn-success" style="display:none;" data-save="$1"><span class="fa fa-floppy-o"></span></button><button class="btn btn-xs btn-danger" style="display:none;" data-cancel="$1"><span class="fa fa-ban"></span></button>', 'id');
		} else {
			$this->datatables->add_column('edit', '<div class="dt-body-center" role="group">'
				. '<a class="btn btn-xs btn-default" href="alumno/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '</ul></div>', 'id,escuela_id');
		}

		echo $this->datatables->generate();
	}

	public function certificados($escuela_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || ($ciclo_lectivo != NULL && !ctype_digit($ciclo_lectivo))) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("ingreso/alumno/listar/$escuela_id/$ciclo_lectivo", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('ingreso_alumno_model');

//		$this->set_model_validation_rules($model);
		if (isset($_POST) && !empty($_POST)) {
			$alumno_ingreso_ids = $this->input->post('alumno_division');
			if (empty($alumno_ingreso_ids)) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}
			$alumnos_certificados = array();
			foreach ($alumno_ingreso_ids as $alumno_ingreso_id) {
				$alumnos_certificados[$alumno_ingreso_id] = $this->ingreso_alumno_model->get_certificados($alumno_ingreso_id);
			}
			$data['alumnos_certificados'] = $alumnos_certificados;
			$content = $this->load->view('ingreso/alumno/alumno_certificado_imprimir', $data, TRUE);

			$this->load->helper('mpdf');
			exportarMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', '.: Reporte de alumno regular - DGE :.', '', '', 'A4', '', 'I', FALSE, FALSE, 'P');
		}

		$this->load->model('ingreso_alumno_model');
		$tableData = array(
			'columns' => array(
				array('label' => 'Documento', 'data' => 'documento', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Alumno', 'data' => 'persona', 'width' => 30, 'class' => 'text-sm'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 5, 'class' => 'dt-body-center'),
				array('label' => 'Div', 'data' => 'division', 'width' => 15, 'class' => 'dt-body-center'),
				array('label' => 'Participa', 'data' => 'participa', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Promedio', 'data' => 'promedio', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => 'Abanderado/Escolta', 'data' => 'abanderado', 'width' => 10, 'class' => 'dt-body-center'),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'alumno_table',
			'source_url' => "ingreso/alumno/certificados_data/$escuela_id/$ciclo_lectivo",
			'order' => array(array(5, 'asc'), array(2, 'asc'), array(3, 'asc'), array(1, 'asc')),
			'responsive' => FALSE,
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);

		$data['abanderados_baja'] = $abanderados_baja = $this->ingreso_alumno_model->get_abanderados_baja($escuela_id, $ciclo_lectivo);
		$data['escuela'] = $escuela;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Alumnos certificados';
		$this->load_template('ingreso/alumno/alumno_certificado', $data);
	}

	public function certificados_data($escuela_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->datatables
			->select("alumno_division.id as ad_id, ingreso_alumno.id as id, alumno.persona_id, ingreso_alumno.participa, ingreso_alumno.promedio, ingreso_alumno.abanderado, alumno.observaciones, alumno_division.fecha_desde, alumno_division.fecha_hasta, CONCAT(COALESCE(documento_tipo.descripcion_corta,''),' ',COALESCE(persona.documento,' ')) as documento, persona.documento_tipo_id, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, CASE curso.grado_multiple WHEN 'Si' THEN CONCAT(division.division, ' (', COALESCE(c_gm.descripcion, ''), ')') ELSE division.division END as division, curso.descripcion as curso, division.escuela_id, curso.grado_multiple")
			->unset_column('id')
			->from('alumno')
			->join('persona', 'persona.id = alumno.persona_id', 'left')
			->join('alumno_division', 'alumno_division.alumno_id = alumno.id', 'left')
			->join('ingreso_alumno', 'ingreso_alumno.alumno_id = alumno.id', 'left')
			->join('division', 'division.id = alumno_division.division_id', 'left')
			->join('curso', 'division.curso_id = curso.id', 'left')
			->join('curso c_gm', 'alumno_division.curso_id = c_gm.id', 'left')
			->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id', 'left')
			->where('division.escuela_id', $escuela_id)
			->where('ingreso_alumno.promedio IS NOT NULL')
			->where('ciclo_lectivo', $ciclo_lectivo)
			->where("(curso.descripcion like '%7%' OR c_gm.descripcion LIKE '%7%')")
			->add_column('edit', '');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<input type="checkbox" name="alumno_division[]" value="$1">', 'id');
		} else {
			$this->datatables->add_column('edit', '<div class="dt-body-center" role="group">'
				. '<a class="btn btn-xs btn-default" href="alumno/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '</ul></div>', 'id,escuela_id');
		}

		echo $this->datatables->generate();
	}

	public function abanderados_baja($escuela_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("ingreso/alumno/listar/$escuela_id/$ciclo_lectivo", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('ingreso_alumno_model');

		$abanderados_baja = $this->ingreso_alumno_model->get_abanderados_baja($escuela_id, $ciclo_lectivo);
//		$this->set_model_validation_rules($this->ingreso_alumno_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($escuela->id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			$trans_ok = TRUE;
			$this->db->trans_begin();
			foreach ($abanderados_baja as $abanderado) {
//				if ($this->form_validation->run() === TRUE) {
					$trans_ok &= $this->ingreso_alumno_model->update(array(
						'id' => $abanderado->id,
						'abanderado_escuela_id' => 'NULL',
						'abanderado' => 'No'
					));
//				}
			}
			if ($trans_ok && $this->db->trans_status()) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', $this->ingreso_alumno_model->get_msg());
				redirect("ingreso/alumno/listar/$escuela->id/$ciclo_lectivo", 'refresh');
			} else {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', $this->ingreso_alumno_model->get_errors());
				redirect("ingreso/alumno/listar/$escuela->id/$ciclo_lectivo", 'refresh');
			}
		}

		$data['escuela'] = $escuela;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['escuela'] = $escuela;
		$data['txt_btn'] = 'Retirar';
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['abanderados_baja'] = $abanderados_baja;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Alumnos abanderados baja';
		$this->load->view('ingreso/alumno/alumno_modal_abanderados_baja', $data);
	}

	public function editar_datos($escuela_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("ingreso/alumno/listar/$escuela_id/$ciclo_lectivo", 'refresh');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('ingreso_alumno_model');

		$tableData = array(
			'columns' => array(
				array('label' => 'Documento', 'data' => 'documento', 'width' => 8, 'class' => 'text-sm'),
				array('label' => 'Alumno', 'data' => 'persona', 'width' => 22, 'class' => 'text-sm'),
				array('label' => 'Domicilio', 'data' => 'direccion', 'width' => 25, 'class' => 'text-sm'),
				array('label' => 'Cur', 'data' => 'curso', 'width' => 5, 'class' => 'dt-body-center'),
				array('label' => 'Div', 'data' => 'division', 'width' => 15, 'class' => 'dt-body-center'),
				array('label' => 'Participa', 'data' => 'participa', 'width' => 5, 'class' => 'dt-body-center'),
				array('label' => 'Promedio', 'data' => 'promedio', 'width' => 5, 'class' => 'dt-body-center'),
				array('label' => 'Abanderado/Escolta', 'data' => 'abanderado', 'width' => 5, 'class' => 'dt-body-center'),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'alumno_table',
			'source_url' => "ingreso/alumno/editar_datos_data/$escuela_id/$ciclo_lectivo",
			'order' => array(array(1, 'asc')),
			'responsive' => FALSE,
			'reuse_var' => TRUE,
			'initComplete' => "complete_alumno_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);

		$data['escuela'] = $escuela;
		$data['abanderados_baja'] = $abanderados_baja = $this->ingreso_alumno_model->get_abanderados_baja($escuela_id, $ciclo_lectivo);
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Alumnos editar';
		$this->load_template('ingreso/alumno/alumno_editar_datos', $data);
	}

	public function editar_datos_data($escuela_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->datatables
			->select("alumno_division.id as ad_id, ingreso_alumno.id as id, alumno.persona_id, ingreso_alumno.participa, ingreso_alumno.promedio, ingreso_alumno.abanderado, alumno.observaciones, alumno_division.fecha_desde, alumno_division.fecha_hasta, CONCAT(COALESCE(documento_tipo.descripcion_corta,''),' ',COALESCE(persona.documento,' ')) as documento, persona.documento_tipo_id, CONCAT(COALESCE(persona.apellido, ''), ', ', COALESCE(persona.nombre, '')) as persona, CONCAT(COALESCE(CONCAT(persona.calle,' '),''), COALESCE(CONCAT(persona.calle_numero,' '),''), COALESCE(CONCAT('Dpto:',persona.departamento,' '),''), COALESCE(CONCAT('P:',persona.piso,' '),''), COALESCE(CONCAT('B°:',persona.barrio,' '),''), COALESCE(CONCAT('M:',persona.manzana,' '),''), COALESCE(CONCAT('C:',persona.casa,' '),'')) as direccion, CASE curso.grado_multiple WHEN 'Si' THEN CONCAT(division.division, ' (', COALESCE(c_gm.descripcion, ''), ')') ELSE division.division END as division, curso.descripcion as curso, division.escuela_id, curso.grado_multiple")
			->unset_column('id')
			->from('alumno')
			->join('persona', 'persona.id = alumno.persona_id', 'left')
			->join('alumno_division', 'alumno_division.alumno_id = alumno.id', 'left')
			->join('ingreso_alumno', 'ingreso_alumno.alumno_id = alumno.id', 'left')
			->join('division', 'division.id = alumno_division.division_id', 'left')
			->join('curso', 'division.curso_id = curso.id', 'left')
			->join('curso c_gm', 'alumno_division.curso_id = c_gm.id', 'left')
			->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id', 'left')
			->where('division.escuela_id', $escuela_id)
			->where('ingreso_alumno.promedio IS NOT NULL')
			->where('ciclo_lectivo', $ciclo_lectivo)
			->where("(curso.descripcion like '%7%' OR c_gm.descripcion LIKE '%7%')")
			->add_column('edit', '');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<a class="btn btn-xs btn-warning" href="ingreso/alumno/modal_editar_datos/$1/$2/$3" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Editar"><i class="fa fa-edit"></i></a>', "persona_id,$escuela_id,$ciclo_lectivo");
		} else {
			$this->datatables->add_column('edit', '', '');
		}

		echo $this->datatables->generate();
	}

	public function modal_editar_datos($persona_id = NULL, $escuela_id = NULL, $ciclo_lectivo = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $persona_id == NULL || !ctype_digit($persona_id) || $escuela_id == NULL || !ctype_digit($escuela_id) || $ciclo_lectivo == NULL || !ctype_digit($ciclo_lectivo)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
		}
		if (empty($ciclo_lectivo) || empty(DateTime::createFromFormat('Y', $ciclo_lectivo))) {
			$ciclo_lectivo = date('Y');
			redirect("ingreso/alumno/listar/$escuela_id/$ciclo_lectivo", 'refresh');
		}
		$persona = $this->persona_model->get_one($persona_id);
		if (empty($persona)) {
			$this->modal_error('No se encontró el registro de persona', 'Registro no encontrado');
		}
		$escuela = $this->escuela_model->get_one($escuela_id);
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('escuela', $this->rol, $escuela)) {
			show_error('No tiene permisos para acceder a la escuela', 500, 'Acción no autorizada');
		}

		$this->load->model('departamento_model');
		$this->load->model('localidad_model');
		$this->load->model('alumno_model');

		$this->array_localidad_control = $array_localidad = $this->get_array('localidad', 'localidad', 'id', array(
			'select' => array('localidad.id', "CONCAT(departamento.descripcion, ' - ', localidad.descripcion) as localidad"),
			'join' => array(array('departamento', 'departamento.id = localidad.departamento_id')),
			'sort_by' => 'departamento.descripcion, localidad.descripcion'
			), array('' => '-- Seleccionar localidad --'));

		unset($this->persona_model->fields['cuil']);
		unset($this->persona_model->fields['documento_tipo']);
		unset($this->persona_model->fields['documento']);
		unset($this->persona_model->fields['apellido']);
		unset($this->persona_model->fields['nombre']);
		unset($this->persona_model->fields['sexo']);
		unset($this->persona_model->fields['estado_civil']);
		unset($this->persona_model->fields['nivel_estudio']);
		unset($this->persona_model->fields['ocupacion']);
		unset($this->persona_model->fields['prestadora']);
		unset($this->persona_model->fields['fecha_defuncion']);
		unset($this->persona_model->fields['obra_social']);
		unset($this->persona_model->fields['grupo_sanguineo']);
		unset($this->persona_model->fields['depto_nacimiento']);
		unset($this->persona_model->fields['lugar_traslado_emergencia']);
		unset($this->persona_model->fields['nacionalidad']);
		unset($this->persona_model->fields['email']);

		$this->set_model_validation_rules($this->persona_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($persona_id !== $this->input->post('id')) {
				show_error('Esta solicitud no pasó el control de seguridad.');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->persona_model->update(array(
					'id' => $this->input->post('id'),
					'calle' => $this->input->post('calle'),
					'calle_numero' => $this->input->post('calle_numero'),
					'departamento' => $this->input->post('departamento'),
					'piso' => $this->input->post('piso'),
					'barrio' => $this->input->post('barrio'),
					'manzana' => $this->input->post('manzana'),
					'casa' => $this->input->post('casa'),
					'localidad_id' => $this->input->post('localidad'),
					'codigo_postal' => $this->input->post('codigo_postal'),
					'telefono_fijo' => $this->input->post('telefono_fijo'),
					'telefono_movil' => $this->input->post('telefono_movil'),
					'fecha_nacimiento' => $this->get_date_sql('fecha_nacimiento')
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->persona_model->get_msg());
					redirect("ingreso/alumno/editar_datos/$escuela->id/$ciclo_lectivo", 'refresh');
				} else {
					$this->session->set_flashdata('error', $this->persona_model->get_errors());
					redirect("ingreso/alumno/editar_datos/$escuela->id/$ciclo_lectivo", 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("ingreso/alumno/editar_datos/$escuela->id/$ciclo_lectivo", 'refresh');
			}
		}
		$data['error'] = (validation_errors() ? validation_errors() : ($this->persona_model->get_error() ? $this->persona_model->get_error() : $this->session->flashdata('error')));

		$this->persona_model->fields['localidad']['array'] = $array_localidad;

		$data['fields'] = $this->build_fields($this->persona_model->fields, $persona);
		$data['persona_id'] = $persona->id;
		$data['escuela_id'] = $escuela_id;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar datos del alumno';
		$this->load->view('ingreso/alumno/alumno_modal_editar_datos', $data);
	}
}