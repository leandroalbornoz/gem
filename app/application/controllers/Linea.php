<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Linea extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('linea_model');
		$this->roles_admin = array(ROL_ADMIN);
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI, ROL_JEFE_LIQUIDACION, ROL_LIQUIDACION, ROL_LINEA, ROL_CONSULTA_LINEA, ROL_CONSULTA);
		$this->nav_route = 'par/linea';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_admin)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Nombre', 'data' => 'nombre', 'width' => 88),
				array('label' => '', 'data' => 'edit', 'width' => 12, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'linea_table',
			'source_url' => 'linea/listar_data'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Lineas';
		$this->load_template('linea/linea_listar', $data);
	}

	public function listar_data() {
		if (!in_array($this->rol->codigo, $this->roles_admin)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('linea.id, linea.nombre')
			->unset_column('id')
			->from('linea')
			->add_column('edit', '<a href="linea/modal_ver/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a> <a href="linea/modal_editar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> <a href="linea/modal_eliminar/$1" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function modal_agregar() {
		if (!in_array($this->rol->codigo, $this->roles_admin)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}

		$this->set_model_validation_rules($this->linea_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->linea_model->create(array(
					'nombre' => $this->input->post('nombre')));

				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->linea_model->get_msg());
				} else {
					$this->session->set_flashdata('error', $this->linea_model->get_error());
				}
				redirect('linea/listar', 'refresh');
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('linea/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->linea_model->fields);

		$data['txt_btn'] = 'Agregar';
		$data['title'] = 'Agregar linea';
		$this->load->view('linea/linea_modal_abm', $data);
	}

	public function modal_editar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_admin) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$linea = $this->linea_model->get(array('id' => $id));
		if (empty($linea)) {
			$this->modal_error('No se encontró el registro a editar', 'Registro no encontrado');
			return;
		}
		$this->set_model_validation_rules($this->linea_model);
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->linea_model->update(array(
					'id' => $this->input->post('id'),
					'nombre' => $this->input->post('nombre')));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->linea_model->get_msg());
					redirect('linea/listar', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect('linea/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->linea_model->fields, $linea);

		$data['linea'] = $linea;

		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar linea';
		$this->load->view('linea/linea_modal_abm', $data);
	}

	public function modal_eliminar($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_admin) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$linea = $this->linea_model->get(array('id' => $id));
		if (empty($linea)) {
			$this->modal_error('No se encontró el registro a eliminar', 'Registro no encontrado');
			return;
		}
		if (isset($_POST) && !empty($_POST)) {
			if ($id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
				return;
			}

			$trans_ok = TRUE;
			$trans_ok &= $this->linea_model->delete(array('id' => $this->input->post('id')));
			if ($trans_ok) {
				$this->session->set_flashdata('message', $this->linea_model->get_msg());
				redirect('linea/listar', 'refresh');
			}
		}

		$data['fields'] = $this->build_fields($this->linea_model->fields, $linea, TRUE);

		$data['linea'] = $linea;

		$data['txt_btn'] = 'Eliminar';
		$data['title'] = 'Eliminar linea';
		$this->load->view('linea/linea_modal_abm', $data);
	}

	public function modal_ver($id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_admin) || $id == NULL || !ctype_digit($id)) {
			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
			return;
		}
		$linea = $this->linea_model->get(array('id' => $id));
		if (empty($linea)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		}

		$data['fields'] = $this->build_fields($this->linea_model->fields, $linea, TRUE);

		$data['linea'] = $linea;
		$data['txt_btn'] = NULL;
		$data['title'] = 'Ver linea';
		$this->load->view('linea/linea_modal_abm', $data);
	}

	public function escritorio($id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('linea_model');
		$linea = $this->linea_model->get_one($id);
		if (empty($linea)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('linea', $this->rol, $linea)) {
			show_error('No tiene permisos para acceder a la linea', 500, 'Acción no autorizada');
		}
		$data['linea'] = $linea;
		$this->load->model('nivel_model');
		$niveles = $this->nivel_model->get(array('linea_id' => $linea->id));
		$this->load->model('supervision_model');
		$this->load->model('carrera_model');
		$this->load->model('caracteristica_escuela_model');
		if (!empty($niveles)) {
			foreach ($niveles as $nivel) {
				$nivel->caracteristicas = $this->caracteristica_escuela_model->get_by_nivel($nivel->id);

				$nivel->supervisiones = $this->supervision_model->get_by_nivel($nivel->id, 1);
				$nivel->carreras = $this->carrera_model->get_by_nivel($nivel->id, 1);
				$nivel->indices = $this->nivel_model->get_indices($nivel->id, 1);

				$this->load->model('preinscripciones/preinscripcion_model');
				$this->load_modules($data, $this->preinscripcion_model->modulo_linea($nivel, $data));

				if ($nivel->id === '9') {
					$this->load->model('tem/tem_proyecto_model');
					$this->load->model('tem/tem_proyecto_escuela_model');
					$proyecto = $this->tem_proyecto_model->get_periodo_activo();
					$escuelas_tem = $this->db->select('sum(cargo.carga_horaria) as horas_asignadas, tem_proyecto_escuela.horas_catedra as horas_permitidas, escuela.numero, escuela.nombre, escuela.id as escuela_id')
						->from('tem_proyecto_escuela')
						->join('escuela', 'escuela.id = tem_proyecto_escuela.escuela_id')
						->join('regimen', 'regimen.planilla_modalidad_id = 2')
						->join('cargo', 'escuela.id = cargo.escuela_id AND cargo.regimen_id = regimen.id', 'left')
						->join('servicio', 'servicio.cargo_id = cargo.id AND servicio.fecha_baja IS NULL')
						->where('tem_proyecto_escuela.tem_proyecto_id', $proyecto->id)
						->group_by('tem_proyecto_escuela.escuela_id')
						->get()
						->result();

					if (!empty($escuelas_tem)) {
						$nivel->modulos['tem'] = $this->load->view('tem/escritorio_linea_modulo_tem', ['escuelas_tem' => $escuelas_tem, 'linea' => $linea], TRUE);
					}
				}
			}
		}
		if ($linea->id === '13') {
			$data['alumnos_hosp_dom'] = TRUE;
			$this->load->model('cargo_model');
			$this->load->model('alumno_derivacion_model');
			$data['cant_escuelas_d_h'] = $this->cargo_model->get_cant_escuelas_d_h();
			$data['cant_cargos_d_h'] = $this->cargo_model->get_cant_cargos_d_h();
			$data['cant_alumnos'] = $this->alumno_derivacion_model->get_cant_alumnos();
			$data['cant_alumnos_baja'] = $this->alumno_derivacion_model->get_cant_alumnos_baja();
			$data['cant_alumnos_alta'] = $this->alumno_derivacion_model->get_cant_alumnos_alta();
			$nivel->modulos['derivacion'] = $this->load->view('alumno_derivacion/escritorio_linea_alumno_derivacion', $this->alumno_derivacion_model->get_vista_linea($data), TRUE);
		}
		if ($linea->id === '9') {
			$this->load->model('alumno_apoyo_especial_model');
			$nivel->modulos['apoyo'] = $this->load->view('alumno_apoyo_especial/escritorio_linea_alumno_apoyo_especial', $this->alumno_apoyo_especial_model->get_vista_linea($data), TRUE);
		}


		$data['niveles'] = $niveles;
		$data['message'] = $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');
		$this->load_template('escritorio/escritorio_linea', $data);
	}

	public function exportar_planilla_general($id) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $id == NULL || !ctype_digit($id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('linea_model');
		$linea = $this->linea_model->get_one($id);
		if (empty($linea)) {
			show_error('No se encontró el registro a ver', 500, 'Registro no encontrado');
		}
		if (!$this->usuarios_model->verificar_permiso('linea', $this->rol, $linea)) {
			show_error('No tiene permisos para acceder a la linea', 500, 'Acción no autorizada');
		}
		$model = new stdClass();
		$model->fields = array(
			'mes' => array('label' => 'Mes', 'type' => 'date', 'required' => TRUE)
		);

		$this->set_model_validation_rules($model);
		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$fecha = $this->get_date_sql('mes', 'd/m/Y', 'Ym');
				$servicios = $this->db->select('escuela.numero, escuela.nombre as escuela, departamento.descripcion as departamento, persona.cuil, persona.apellido, persona.nombre, cargo.carga_horaria, DATE_FORMAT(servicio.fecha_alta, "%d/%m/%Y"), DATE_FORMAT(servicio.fecha_baja, "%d/%m/%Y")')
					->from('tem_proyecto_escuela')
					->join('escuela', 'escuela.id = tem_proyecto_escuela.escuela_id')
					->join('localidad', 'localidad.id = escuela.localidad_id')
					->join('departamento', 'departamento.id = localidad.departamento_id')
					->join('cargo', 'escuela.id = cargo.escuela_id')
					->join('regimen', 'cargo.regimen_id = regimen.id')
					->join('servicio', 'servicio.cargo_id = cargo.id')
					->join('persona', 'servicio.persona_id = persona.id')
					->where('regimen.planilla_modalidad_id', 2)
					->where("'$fecha' >= COALESCE(DATE_FORMAT(servicio.fecha_alta,'%Y%m'),'000000')")
					->where("'$fecha' <= COALESCE(DATE_FORMAT(servicio.fecha_baja,'%Y%m'),'999999')")
					->order_by('departamento.descripcion', 'asc')
					->order_by('escuela.numero', 'asc')
					->order_by('servicio.fecha_alta', 'asc')
					->order_by('persona.apellido', 'asc')
					->get()
					->result_array();

				$campos = array(
					'A' => array('Núm. Escuela', 15),
					'B' => array('Escuela', 30),
					'C' => array('Departamento', 30),
					'D' => array('Cuil', 15),
					'E' => array('Apellido', 30),
					'F' => array('Nombre', 30),
					'G' => array('Horas cargo', 15),
					'H' => array('Fecha alta', 20),
					'I' => array('Fecha baja', 20),
				);
				if (!empty($servicios)) {
					$this->exportar_excel(array('title' => "Planilla asistencia y novedades general"), $campos, $servicios);
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
				redirect("linea/escritorio/$linea->id", 'refresh');
			}
		}
	}

	public function exportar_excel_supervision($linea_id = NULL) {
//if (!in_array($this->rol->codigo, $this->roles_permitidos) || $nivel_id == NULL || !ctype_digit($nivel_id)) {
//			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
//		}
		$ciclo_lectivo_actual = date('Y');
		$ciclo_lectivo_anterior = date('Y', strtotime('-1 year'));
		$campos = array(
			'A' => array('N° - Supervision', 15),
			'B' => array('Regional', 20),
			'C' => array('Supervisor', 35),
			'D' => array('Escuela', 10),
			'E' => array('Telefono', 20),
			'F' => array('Matricula 2017', 15),
			'G' => array('Matricula 2018', 15),
			'H' => array('C.L. Actualizado', 15),
		);
		$query = $this->db->query("SELECT  supervision.nombre,  regional.descripcion,supervision.responsable,
COUNT(DISTINCT escuela.numero) as escuelas, supervision.telefono,
ad.matricula_2017, ad.matricula_2018,ad.porcentaje_actualizacion_cl
FROM supervision
LEFT JOIN escuela ON escuela.supervision_id = supervision.id
LEFT JOIN regional ON regional.id = escuela.regional_id 
LEFT JOIN (SELECT s.id as supervision_id, s.nombre,
    (1-(COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo= ? AND ad.fecha_hasta IS NULL THEN ad.alumno_id ELSE NULL END))/COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=? THEN ad.alumno_id ELSE NULL END)) as porcentaje_actualizacion_cl,
	COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=? THEN ad.alumno_id ELSE NULL END) matricula_2017,
	COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=? AND ad.fecha_hasta IS NULL THEN ad.alumno_id ELSE NULL END) matricula_2018,
	COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=? AND ad.fecha_hasta IS NULL THEN ad.alumno_id ELSE NULL END)-COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=? THEN ad.alumno_id ELSE NULL END) diferencia_año_2018_2017
	FROM escuela e
	LEFT JOIN division d ON d.escuela_id=e.id
	LEFT JOIN alumno_division ad ON ad.division_id = d.id
	LEFT JOIN supervision s ON s.id = e.supervision_id
	where COALESCE(ad.fecha_hasta,?)>= ? and s.linea_id = ?
	GROUP BY s.id
) ad  ON ad.supervision_id=supervision.id
WHERE supervision.linea_id = ?
AND supervision.dependencia_id = 1
GROUP BY supervision.id
ORDER BY supervision.orden;	", array($ciclo_lectivo_anterior, $ciclo_lectivo_anterior, $ciclo_lectivo_anterior, $ciclo_lectivo_actual, $ciclo_lectivo_actual, $ciclo_lectivo_anterior, $ciclo_lectivo_anterior . '-12-01', $ciclo_lectivo_anterior . '-12-01', $linea_id, $linea_id))->result_array();

		if (!empty($query)) {
			$this->load->library('PHPExcel');
			$this->phpexcel->getProperties()->setTitle("Reporte");
			$this->phpexcel->setActiveSheetIndex(0);
			$this->phpexcel->getDefaultStyle()->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('argb' => 'FFFFFFFF')
					),
				)
			);

			$sheet = $this->phpexcel->getActiveSheet();
			$sheet->setTitle("Reporte");
			$encabezado = array();
			$ultima_columna = 'A';
			foreach ($campos as $columna => $campo) {
				$encabezado[] = $campo[0];
				$sheet->getColumnDimension($columna)->setWidth($campo[1]);
				$ultima_columna = $columna;
			}
			$sheet->freezePane('A3');
			$sheet->mergeCells('A1:H1');
			$sheet->getStyle('A1')->getAlignment()->applyFromArray(
				array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);
			$sheet->setCellValue('A1', ("Reporte"));
			$sheet->getStyle('A1:' . $ultima_columna . '1')->getFont()->setBold(true);
			$sheet->fromArray(array($encabezado), NULL, 'A2');
			$sheet->fromArray($query, NULL, 'A3');
			$sheet->getStyle('A2:' . $ultima_columna . '2')->getFont()->setBold(true)->getColor()->setRGB('ffffff');
			$ultima_fila = $sheet->getHighestRow();
			$sheet->getStyle('H2:H' . $ultima_fila)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			$sheet->getStyle("A2:H$ultima_fila")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sheet->getStyle('A2:' . $ultima_columna . '2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('3c8dbc');


			header("Content-Type: application/vnd.ms-excel");
			$nombreArchivo = "Reporte";
			header("Content-Disposition: attachment; filename=\"$nombreArchivo.xls\"");
			header("Cache-Control: max-age=0");

			$writer = PHPExcel_IOFactory::createWriter($this->phpexcel, "Excel5");
			$writer->save('php://output');
			exit;
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect("linea/escritorio/2", 'refresh');
		}
	}
}
/* End of file Linea.php */
/* Location: ./application/controllers/Linea.php */