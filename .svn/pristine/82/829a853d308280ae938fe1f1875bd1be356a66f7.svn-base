<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->roles_permitidos = array(ROL_SUPERVISION);
		$this->roles_linea = array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_CONSULTA_LINEA, ROL_CONSULTA);
		$this->nav_route = 'reportes/reportes';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			$this->session->set_flashdata('error', 'No tiene permisos para la acción solicitada');
			redirect('escritorio');
		}
		$this->load->model('supervision_model');
		$supervision = $this->supervision_model->get(array('id' => $this->rol->entidad_id));
		$data['supervision'] = $supervision;
		$data['error'] = $this->session->flashdata('error');
		$this->load_template('reportes/reportes_supervision_listar', $data);
	}

	public function escuelas() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$campos = array(
			'A' => array('Número', 10),
			'B' => array('Anexo', 10),
			'C' => array('CUE', 10),
			'D' => array('SubCUE', 10),
			'E' => array('Nombre', 10),
			'F' => array('Calle', 10),
			'G' => array('N°', 10),
			'H' => array('Barrio', 10),
			'I' => array('Localidad', 10),
			'J' => array('Departamento', 10),
			'K' => array('C.P.', 10),
			'L' => array('Nivel', 10),
			'M' => array('Regímenes', 10),
			'N' => array('Repartición', 10),
			'O' => array('Supervisión', 10),
			'P' => array('Regional', 10),
			'Q' => array('Gestión', 10),
			'R' => array('Zona', 10),
			'S' => array('Fecha Creación', 10),
			'T' => array('Año Resolución', 10),
			'U' => array('Número Resolución', 10),
			'V' => array('Teléfono', 10),
			'W' => array('Email', 10)
		);
		$escuelas = $this->db->select('e.numero, e.anexo, e.cue, e.subcue, e.nombre, e.calle, e.calle_numero, e.barrio, lo.descripcion localidad, ld.descripcion departamento, '
					. 'e.codigo_postal, n.descripcion nivel, rl.descripcion regimen_lista, CONCAT(j.codigo, \' \', r.codigo) reparticion, s.nombre supervision, rg.descripcion regional, d.descripcion dependencia, '
					. 'z.descripcion zona, e.fecha_creacion, e.anio_resolucion, e.numero_resolucion, e.telefono, e.email')
				->from('escuela e')
				->join('dependencia d', 'd.id = e.dependencia_id', 'left')
				->join('localidad lo', 'lo.id = e.localidad_id', 'left')
				->join('departamento ld', 'ld.id = lo.departamento_id', 'left')
				->join('nivel n', 'n.id = e.nivel_id', 'left')
				->join('regimen_lista rl', 'rl.id = e.regimen_lista_id', 'left')
				->join('linea l', 'l.id = n.linea_id', 'left')
				->join('regional rg', 'rg.id = e.regional_id', 'left')
				->join('reparticion r', 'r.id = e.reparticion_id', 'left')
				->join('jurisdiccion j', 'j.id = r.jurisdiccion_id', 'left')
				->join('supervision s', 's.id = e.supervision_id', 'left')
				->join('zona z', 'z.id = e.zona_id', 'left')
				->where('supervision_id', $this->rol->entidad_id)
				->get()->result_array();

		if (!empty($escuelas)) {
			$this->exportar_excel(array('title' => "Escuelas_{$this->rol->entidad_id}_" . date('Ymd')), $campos, $escuelas);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect('reportes/listar', 'refresh');
		}
	}

	public function cargos() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$campos = array(
			'A' => array('Escuela', 10),
			'B' => array('Anexo', 10),
			'C' => array('Régimen', 40),
			'D' => array('Materia', 30),
			'E' => array('División', 20),
			'F' => array('Hs Cátedra', 15),
			'G' => array('Persona actual', 50),
		);
		$cargos = $this->db->select('e.numero, e.anexo, CONCAT(r.codigo, \' \', r.descripcion) as regimen, m.descripcion as materia, '
					. 'CONCAT(cu.descripcion, \' \', d.division) division, c.carga_horaria, CONCAT(COALESCE(p.cuil, p.documento), \' \', p.apellido, \' \', p.nombre) as persona')
				->from('cargo c')
				->join('escuela e', 'e.id = c.escuela_id')
				->join('division d', 'd.id = c.division_id', 'left')
				->join('curso cu', 'cu.id = d.curso_id', 'left')
				->join('espacio_curricular ec', 'ec.id = c.espacio_curricular_id', 'left')
				->join('materia m', 'ec.materia_id = m.id', 'left')
				->join('regimen r', 'r.id = c.regimen_id AND r.planilla_modalidad_id=1')
				->join('servicio sp', "sp.id = (SELECT id FROM servicio s WHERE s.cargo_id = c.id AND ((NOW() BETWEEN s.fecha_alta AND s.fecha_baja) OR NOW() >= s.fecha_alta AND s.fecha_baja IS NULL ) ORDER BY s.fecha_alta DESC LIMIT 1)", 'left')
				->join('persona p', 'p.id = sp.persona_id', 'left')
				->where('e.supervision_id', $this->rol->entidad_id)
				->get()->result_array();

		if (!empty($cargos)) {
			$this->exportar_excel(array('title' => "Cargos_{$this->rol->entidad_id}_" . date('Ymd')), $campos, $cargos);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect('reportes/listar', 'refresh');
		}
	}

	public function servicios() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$campos = array(
			'A' => array('Escuela', 10),
			'B' => array('Anexo', 10),
			'C' => array('Régimen', 40),
			'D' => array('Materia', 30),
			'E' => array('División', 20),
			'F' => array('Hs Cátedra', 15),
			'G' => array('Persona', 50),
			'H' => array('Liquidación', 15),
			'I' => array('Alta', 11),
			'J' => array('Baja', 11),
		);
		$servicios = $this->db->select('e.numero, e.anexo, CONCAT(r.codigo, \' \', r.descripcion) as regimen, m.descripcion as materia, '
					. 'CONCAT(cu.descripcion, \' \', d.division) division, c.carga_horaria, CONCAT(COALESCE(p.cuil, p.documento), \' \', p.apellido, \' \', p.nombre) as persona, s.liquidacion, s.fecha_alta, s.fecha_baja')
				->from('servicio s')
				->join('cargo c', 'c.id = s.cargo_id')
				->join('escuela e', 'e.id = c.escuela_id')
				->join('division d', 'd.id = c.division_id', 'left')
				->join('curso cu', 'cu.id = d.curso_id', 'left')
				->join('espacio_curricular ec', 'ec.id = c.espacio_curricular_id', 'left')
				->join('materia m', 'ec.materia_id = m.id', 'left')
				->join('regimen r', 'r.id = c.regimen_id AND r.planilla_modalidad_id=1')
				->join('persona p', 'p.id = s.persona_id', 'left')
				->where('e.supervision_id', $this->rol->entidad_id)
				->get()->result_array();

		if (!empty($servicios)) {
			$this->exportar_excel(array('title' => "Servicios_{$this->rol->entidad_id}_" . date('Ymd')), $campos, $servicios);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect('reportes/listar', 'refresh');
		}
	}

	public function novedades() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$campos = array(
			'A' => array('Escuela', 10),
			'B' => array('Anexo', 10),
			'C' => array('Régimen', 40),
			'D' => array('Materia', 30),
			'E' => array('División', 20),
			'F' => array('Hs Cátedra', 15),
			'G' => array('Persona', 50),
			'H' => array('Liquidación', 15),
			'I' => array('Artículo', 11),
			'J' => array('Desde', 11),
			'K' => array('Hasta', 11),
			'L' => array('Alta', 11),
			'M' => array('Baja', 11),
		);
		$novedades = $this->db->select('e.numero, e.anexo, CONCAT(r.codigo, \' \', r.descripcion) as regimen, m.descripcion as materia, '
					. 'CONCAT(cu.descripcion, \' \', d.division) division, c.carga_horaria, CONCAT(COALESCE(p.cuil, p.documento), \' \', p.apellido, \' \', p.nombre) as persona, '
					. 's.liquidacion, CONCAT(nt.articulo, \'-\', nt.inciso, \' \', nt.descripcion), sn.fecha_desde, sn.fecha_hasta, s.fecha_alta, s.fecha_baja')
				->from('servicio_novedad sn')
				->join('novedad_tipo nt', 'nt.id = sn.novedad_tipo_id')
				->join('servicio s', 's.id = sn.servicio_id')
				->join('cargo c', 'c.id = s.cargo_id')
				->join('escuela e', 'e.id = c.escuela_id')
				->join('division d', 'd.id = c.division_id', 'left')
				->join('curso cu', 'cu.id = d.curso_id', 'left')
				->join('espacio_curricular ec', 'ec.id = c.espacio_curricular_id', 'left')
				->join('materia m', 'ec.materia_id = m.id', 'left')
				->join('regimen r', 'r.id = c.regimen_id AND r.planilla_modalidad_id=1')
				->join('persona p', 'p.id = s.persona_id', 'left')
				->where('e.supervision_id', $this->rol->entidad_id)
				->where('sn.planilla_baja_id IS NULL')
				->get()->result_array();

		if (!empty($novedades)) {
			$this->exportar_excel(array('title' => "Novedades_{$this->rol->entidad_id}_" . date('Ymd')), $campos, $novedades);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect('reportes/listar', 'refresh');
		}
	}

	public function cargos_d_h_listar($linea_id = NULL) {
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
		if ($this->rol->entidad != 'Educación Domiciliaria y Hospitalaria') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$tableData = array(
			'columns' => array(
				array('label' => 'CUIL', 'data' => 'cuil', 'class' => 'text-sm'),
				array('label' => 'Persona', 'data' => 'persona', 'class' => 'text-sm'),
				array('label' => 'Escuela', 'data' => 'escuela', 'width' => 11, 'class' => 'text-sm'),
				array('label' => 'Condición cargo', 'data' => 'condicion_cargo', 'class' => 'text-sm'),
				array('label' => 'Curso', 'data' => 'curso', 'class' => 'text-sm'),
				array('label' => 'División', 'data' => 'division', 'class' => 'dt-body-center'),
				array('label' => 'Turno', 'data' => 'turno', 'class' => 'text-sm'),
				array('label' => 'C.Horaria', 'data' => 'carga_horaria', 'class' => 'text-sm'),
				array('label' => 'Servicios', 'data' => 'servicios', 'class' => 'text-sm'),
				array('label' => 'F.Alta', 'data' => 'fecha_alta', 'render' => 'date'),
				array('label' => 'F.Baja', 'data' => 'fecha_baja', 'render' => 'date'),
				array('label' => 'Motivo Baja', 'data' => 'motivo_baja', 'class' => 'text-sm'),
				array('label' => 'Liquidación', 'data' => 'liquidacion', 'class' => 'text-sm'),
				array('label' => 'S.R', 'data' => 'situacion_revista', 'width' => 15, 'class' => 'text-sm'),
			),
			'table_id' => 'cargos_d_h_table',
			'source_url' => "reportes/listar_cargos_d_h_data/$linea_id",
			'order' => array(array(2, 'asc'), array(3, 'asc'), array(0, 'asc')),
			'responsive' => FALSE,
			'reuse_var' => TRUE,
			'initComplete' => "complete_cargos_d_h_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$this->load->model('cargo_model');

		$data['cant_escuelas'] = $this->cargo_model->get_cant_escuelas_d_h();
		$data['cant_cargos'] = $this->cargo_model->get_cant_cargos_d_h();
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = TITLE . ' - Cargos Hospitalaria/Domiciliaria';
		$this->load_template('reportes/reportes_cargos_d_h_listar', $data);
	}

	public function listar_cargos_d_h_data($linea_id = NULL) {
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
		if ($this->rol->entidad != 'Educación Domiciliaria y Hospitalaria') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->datatables->select('c.id, cc.descripcion as condicion_cargo, CONCAT(e.numero, \' - \', e.nombre) as escuela, cu.descripcion as curso, d.division as division, t.descripcion as turno, c.carga_horaria, '
				. 'p.cuil, CONCAT(p.apellido, \', \', p.nombre) as persona,  '
				. 'COUNT(DISTINCT s.id) as servicios, s.fecha_alta, s.fecha_baja, s.motivo_baja, s.liquidacion, sr.descripcion as situacion_revista')
			->from('cargo c')
			->unset_column('id')
			->join('escuela e', 'e.id = c.escuela_id', 'left')
			->join('area a', 'a.id = c.area_id', 'left')
			->join('turno t', 't.id = c.turno_id', 'left')
			->join('condicion_cargo cc', 'cc.id = c.condicion_cargo_id', 'left')
			->join('division d', 'd.id = c.division_id', 'left')
			->join('curso cu', 'cu.id = d.curso_id', 'left')
			->join('espacio_curricular ec', 'ec.id = c.espacio_curricular_id', 'left')
			->join('servicio s', "s.cargo_id = c.id AND s.fecha_baja IS NULL", 'left')
			->join('servicio sp', "sp.id = (SELECT id FROM servicio s WHERE cargo_id = c.id AND s.fecha_baja IS NULL ORDER BY fecha_alta DESC LIMIT 1)", 'left', false)
			->join('situacion_revista sr', 'sr.id = s.situacion_revista_id', 'left')
			->join('persona p', 'p.id = sp.persona_id', 'left')
			->where('c.condicion_cargo_id = 2 or c.condicion_cargo_id = 3')
			->where('c.fecha_hasta IS NULL')
			->group_by('c.id, cc.descripcion, cu.descripcion, d.division, c.carga_horaria');

		echo $this->datatables->generate();
	}
}