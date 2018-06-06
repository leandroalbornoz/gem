<?php

class Escritorio extends MY_Controller {

	function __construct() {
		parent::__construct();
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->load->model('escuela_model');
		$this->load->model('ingreso/feria_model');
		$this->roles_permitidos = array(ROL_ADMIN/*, ROL_INGRESO*/);
		$this->nav_route = 'ingreso/ingreso';
	}

	public function listar() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$tableData = array(
			'columns' => array(
				array('label' => 'Número', 'data' => 'numero', 'width' => 5),
				array('label' => 'Anexo', 'data' => 'anexo', 'class' => 'dt-body-right', 'width' => 5),
				array('label' => 'CUE', 'data' => 'cue', 'class' => 'dt-body-right', 'width' => 5),
				array('label' => 'Nombre', 'data' => 'nombre', 'width' => 20),
				array('label' => 'Nivel', 'data' => 'nivel', 'width' => 7, 'class' => 'text-sm'),
				array('label' => 'Juri/Repa', 'data' => 'jurirepa', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Supervisión', 'data' => 'supervision', 'width' => 10, 'class' => 'text-sm'),
				array('label' => 'Regional', 'data' => 'regional', 'width' => 8, 'class' => 'text-sm'),
				array('label' => 'Zona', 'data' => 'zona', 'width' => 5, 'class' => 'text-sm'),
				array('label' => 'Teléfono', 'data' => 'telefono', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => 'Email', 'data' => 'email', 'width' => 10, 'responsive_class' => 'none'),
				array('label' => '', 'data' => 'edit', 'width' => 10, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'escuela_table',
			'source_url' => "ingreso/escritorio/listar_data/{$this->rol->codigo}/{$this->rol->entidad_id}",
			'reuse_var' => TRUE,
			'initComplete' => "complete_escuela_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Feria Educativa';
		$this->load_template('ingreso/escritorio/escuela_listar', $data);
	}

	public function listar_data($rol_codigo, $entidad_id = '') {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if ($rol_codigo !== $this->rol->codigo || ((!empty($this->rol->entidad_id) || !empty($entidad_id)) && $this->rol->entidad_id !== $entidad_id)) {
			show_error('Ha cambiado su rol, por favor refresque la página', 500, 'Acción no autorizada');
		}
		$this->datatables
			->select('escuela.id, escuela.numero, escuela.anexo, escuela.cue, escuela.subcue, escuela.nombre, escuela.calle, escuela.calle_numero, escuela.departamento, escuela.piso, escuela.barrio, escuela.manzana, escuela.casa, escuela.localidad_id, escuela.nivel_id, escuela.reparticion_id, escuela.supervision_id, escuela.regional_id, escuela.dependencia_id, escuela.zona_id, escuela.fecha_creacion, escuela.anio_resolucion, escuela.numero_resolucion, escuela.telefono, escuela.email, escuela.fecha_cierre, escuela.anio_resolucion_cierre, escuela.numero_resolucion_cierre, dependencia.descripcion as dependencia, nivel.descripcion as nivel, regional.descripcion as regional, CONCAT(jurisdiccion.codigo,\'/\',reparticion.codigo) as jurirepa, supervision.nombre as supervision, zona.descripcion as zona')
			->unset_column('id')
			->from('feria')
			->join('escuela', 'escuela.id = feria.escuela_id')
			->join('dependencia', 'dependencia.id = escuela.dependencia_id', 'left')
			->join('nivel', 'nivel.id = escuela.nivel_id', 'left')
			->join('regional', 'regional.id = escuela.regional_id', 'left')
			->join('reparticion', 'reparticion.id = escuela.reparticion_id', 'left')
			->join('jurisdiccion', 'jurisdiccion.id = reparticion.jurisdiccion_id', 'left')
			->join('supervision', 'supervision.id = escuela.supervision_id', 'left')
			->join('zona', 'zona.id = escuela.zona_id', 'left');
		if ($this->edicion) {
			$this->datatables->add_column('edit', '<div class="btn-group" role="group">'
				. '<a class="btn btn-xs btn-default" href="ingreso/feria/escritorio/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>'
//				. '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>'
//				. '<ul class="dropdown-menu dropdown-menu-right">'
//				. '<li><a class="dropdown-item" href="ingreso/feria/editar/$1" title="Editar Feria"><i class="fa fa-pencil"></i> Editar Feria</a></li>'
//				. '</ul>'
				. '</div>', 'id');
		} else {
			$this->datatables->add_column('edit', '<a class="btn btn-xs btn-default" href="escuela/ver/$1" title="Ver"><i class="fa fa-search"></i> Ver</a>', 'id');
		}
		switch ($this->rol->codigo) {
			case ROL_GRUPO_ESCUELA:
				$this->datatables
					->join('escuela_grupo_escuela', 'escuela_grupo_escuela.escuela_id = escuela.id', 'left')
					->join('escuela_grupo', 'escuela_grupo_escuela.escuela_grupo_id = escuela_grupo.id', 'left')
					->where('escuela_grupo.id', $this->rol->entidad_id);
				break;
			case ROL_LINEA:
			case ROL_CONSULTA_LINEA:
				$this->datatables->where('nivel.linea_id', $this->rol->entidad_id);
				break;
			case ROL_DIR_ESCUELA:
			case ROL_SDIR_ESCUELA:
			case ROL_SEC_ESCUELA:
				$this->datatables->where('escuela.id', $this->rol->entidad_id);
				break;
			case ROL_SUPERVISION:
				$this->datatables->where('supervision.id', $this->rol->entidad_id);
				break;
			case ROL_PRIVADA:
				$this->datatables->where('dependencia_id', 2);
				break;
			case ROL_SEOS:
				$this->datatables->where('dependencia_id', 3);
				break;
			case ROL_REGIONAL:
				$this->datatables->where('regional.id', $this->rol->entidad_id);
				break;
			case ROL_ADMIN:
			case ROL_USI:
			case ROL_JEFE_LIQUIDACION:
			case ROL_LIQUIDACION:
			case ROL_CONSULTA:
				break;
			default:
				echo '';
				return;
		}
		echo $this->datatables->generate();
	}
	
}