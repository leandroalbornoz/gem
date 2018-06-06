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
		$index = 0;
//		$tableData = array(
//			'columns' => array(
//				array('label' => 'Documento', 'data' => 'documento', 'width' => 10, 'class' => 'text-sm'),
//				array('label' => 'Alumno', 'data' => 'persona', 'width' => 40, 'class' => 'text-sm'),
//				array('label' => 'Cur', 'data' => 'curso', 'width' => 5, 'class' => 'dt-body-center'),
//				array('label' => 'Div', 'data' => 'division', 'width' => 15, 'class' => 'dt-body-center'),
//				array('label' => 'Desde', 'data' => 'fecha_desde', 'render' => 'short_date', 'width' => 10),
//				array('label' => 'Hasta', 'data' => 'fecha_hasta', 'render' => 'short_date', 'width' => 10),
//				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
//			),
//			'table_id' => 'alumno_table',
//			'source_url' => "ingreso/alumno/listar_data/$escuela_id/$ciclo_lectivo",
//			'order' => array(array(5, 'asc'), array(2, 'asc'), array(3, 'asc'), array(1, 'asc')),
//			'responsive' => FALSE,
//			'reuse_var' => TRUE,
//			'initComplete' => "complete_alumno_table",
//			'footer' => TRUE,
//			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
//		);
		$alumnos = $this->db->query("
			SELECT a.id as alumno_id, CONCAT(e.numero,' - ', e.nombre) as nombre_escuela, ad.id as id, a.persona_id, ia.asignado, ia.promedio, ia.abanderado, a.email_contacto, ia.motivo_no_participa, ia.participa, a.observaciones, ad.fecha_desde, ad.fecha_hasta, CONCAT(COALESCE(dt.descripcion_corta,''),' ',COALESCE(p.documento,' ')) as documento, p.documento_tipo_id, CONCAT_WS(', ', p.apellido, p.nombre) as persona, CASE c.grado_multiple WHEN 'Si' THEN CONCAT(d.division, ' (', COALESCE(c_gm.descripcion, ''), ')') ELSE d.division END as division, c.descripcion as curso, d.escuela_id, c.grado_multiple
			FROM alumno a
			LEFT JOIN ingreso_alumno ia ON a.id=ia.alumno_id 
			JOIN alumno_division ad ON a.id=ad.alumno_id
			JOIN persona p ON a.persona_id=p.id
			JOIN division d ON ad.division_id=d.id
			JOIN escuela e ON d.escuela_id=e.id
			JOIN curso c ON d.curso_id=c.id
			LEFT JOIN curso c_gm ON ad.curso_id=c_gm.id
			JOIN documento_tipo dt ON p.documento_tipo_id=dt.id
			WHERE d.escuela_id=? AND ad.ciclo_lectivo=? AND (c.descripcion LIKE '%7%' OR c_gm.descripcion LIKE '%7%')", array($escuela->id, $ciclo_lectivo))->result();
		foreach ($alumnos as $row => $alumno) {
			$index++;
			$alumnos[$row]->promedio = '<div><input class="input-xs text-right form-control notas input-notas" style="width: 60px;"  name="'.$alumno->id.'" data-a="'.$alumno->alumno_id.'" value="'.((!empty($alumno->promedio)) ? $alumno->promedio : "0" ).'" tabindex="'.$index.'"><i class="promedio-status pull-right" style="width:30px;">&nbsp;<i class="promedio-status-ok fa fa-check text-green"></i><i class="promedio-status-loading fa fa-spin fa-refresh text-yellow"></i><i class="promedio-status-error fa fa-times text-red"></i></i></div>';
			$alumnos[$row]->abanderado_escolta = '<div>
						<div class="btn-group btn-group-xs check_abanderado" data-toggle="buttons" style="display: inline-flex;">
							<label class="btn btn-default abanderado_no '.((empty($alumno->abanderado)) ? "active" : (($alumno->abanderado == 'No') ? "active" : "" )).'" style="padding: 1px 20px;">
								<input type="radio" name="abanderado" autocomplete="off" checked="" value="No" data-a="'.$alumno->alumno_id.'"> No
							</label>
							<label class="btn btn-default text-success abanderado '.(($alumno->abanderado == 'Si') ? "active" : "" ).'" style="padding: 1px 20px">
								<input type="radio" name="abanderado" autocomplete="off" value="Si" data-a="'.$alumno->alumno_id.'"> Si
							</label>
						</div>
						<i class="abaderado-status pull-right" style="width:30px;">&nbsp;<i class="abaderado-status-ok fa fa-check text-green"></i><i class="abaderado-status-loading fa fa-spin fa-refresh text-yellow"></i><i class="abaderado-status-error fa fa-times text-red"></i></i>
				</div>';
			$alumnos[$row]->registro_cuenta = '<div></div>';
			$alumnos[$row]->banco_asigando = '<div>'.$alumno->asignado.'</div>';
			$alumnos[$row]->escuela_asiganada = '<div>'.$alumno->nombre_escuela.'</div>';
			$alumnos[$row]->participa = '<div style="float: left;">
				<select class="input-xs text-right" name="participa" data-a="'.$alumno->alumno_id.'">
					<option selected="true" disabled="disabled"> - Seleccionar opción - </option>
					<option value="Si" '.((empty($alumno->participa)) ? "" : (($alumno->participa == 'Si') ? "selected" : "" )).'>Si</option>
					<option value="Ingresa a Esc. Privada" '.((empty($alumno->motivo_no_participa)) ? "" : (($alumno->motivo_no_participa != 'Ingresa a Esc. Privada') ? "" : "selected")).'>No, Ingresa a Esc. Privada</option>
					<option value="Ingresa a Esc. UNC" '.((empty($alumno->motivo_no_participa)) ? "" : (($alumno->motivo_no_participa != 'Ingresa a Esc. UNC') ? "" : "selected")).'>No, Ingresa a Esc. UNC</option>
					<option value="Renunció banco" '.((empty($alumno->motivo_no_participa)) ? "" : (($alumno->motivo_no_participa != 'Renunció banco') ? "" : "selected")).'>No, Renunció banco</option>
					<option value="Otros" '.((empty($alumno->motivo_no_participa)) ? "" : (($alumno->motivo_no_participa != 'Otros') ? "" : "selected")).'>No, Por otros Motivos</option>
				</select>
				</div>
				<i class="participa-status pull-right" style="width:29px;">&nbsp;<i class="participa-status-ok fa fa-check text-green"></i><i class="participa-status-loading fa fa-spin fa-refresh text-yellow"></i><i class="participa-status-error fa fa-times text-red"></i></i>';
			$alumnos[$row]->mail_padre_madre = '<div>'.$alumno->email_contacto.'</div>';
			$alumnos[$row]->edit = '';
		}
		$data['alumnos'] = $alumnos;
		$data['escuela'] = $escuela;
		$data['ciclo_lectivo'] = $ciclo_lectivo;
//		$data['html_table'] = buildHTML($tableData);
//		$data['js_table'] = buildJS($tableData);
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
//			->add_column('edit', '<button class="btn btn-xs btn-info" data-edit="$1"><span class="fa fa-pencil"></span></button><button class="btn btn-xs btn-success" style="display:none;" data-save="$1"><span class="fa fa-floppy-o"></span></button><button class="btn btn-xs btn-danger" style="display:none;" data-cancel="$1"><span class="fa fa-ban"></span></button>', 'id');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<button class="btn btn-xs btn-info" data-edit="$1"><span class="fa fa-pencil"></span></button><button class="btn btn-xs btn-success" style="display:none;" data-save="$1"><span class="fa fa-floppy-o"></span></button><button class="btn btn-xs btn-danger" style="display:none;" data-cancel="$1"><span class="fa fa-ban"></span></button>', 'id');
		} else {
			$this->datatables->add_column('edit', '<div class="dt-body-center" role="group">'
				. '<a class="btn btn-xs btn-default" href="alumno/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
				. '</ul></div>', 'id,escuela_id');
		}

		echo $this->datatables->generate();
	}
}