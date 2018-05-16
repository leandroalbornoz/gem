<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_model extends MY_Model {

	public $tablas = array();

	public function __construct() {
		parent::__construct();
		$this->table_name = 'reporte';
		$this->msg_name = 'Reportes';
		$this->id_name = 'id';
		$this->columnas = array('id', 'nombre', 'reporte', 'usuario_id', 'ultima_ejecucion');
		$this->fields = array(
			'nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'required' => TRUE),
			'reporte' => array('label' => 'Reporte', 'required' => TRUE),
		);
		$this->requeridos = array('nombre', 'reporte');
		//$this->unicos = array();
		$this->default_join = array();
		$this->tablas_test = array(
			'ad' => array(
				'id' => 'ad',
				'nombre' => 'Alumno',
			),
			'ca' => array(
				'id' => 'ca',
				'nombre' => 'Alumno - Características',
			),
			'e' => array(
				'id' => 'e',
				'nombre' => 'Escuela',
			),
			'ae' => array(
				'id' => 'ae',
				'nombre' => 'Escuela - Autoridades',
			),
			'ce' => array(
				'id' => 'ce',
				'nombre' => 'Escuela - Características',
			),
			'c' => array(
				'id' => 'c',
				/*				 * ****************************** */
				'nombre' => 'Cargo',
			),
		);
		$this->tablas = array(
// <editor-fold defaultstate="collapsed" desc="Tablas Escuela">
			'e' => array(
				'id' => 'e',
				'nombre' => 'Escuela',
				'columnas' => array(
					'e.numero' => array(
						'label' => 'N° Esc.',
						'type' => 'string',
						'id' => 'e.numero',
						'input' => 'text',
						'operators' => array('equal', 'begins_with'),
					),
					'e.anexo' => array(
						'label' => 'Anexo',
						'type' => 'integer',
						'id' => 'e.anexo',
						'input' => 'number',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
						'sum' => TRUE,
					),
					'e.nombre' => array(
						'label' => 'Escuela',
						'type' => 'string',
						'id' => 'e.nombre',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
					),
					'e.cue' => array(
						'label' => 'CUE',
						'type' => 'integer',
						'id' => 'e.cue',
						'input' => 'number',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
					),
					'e.telefono' => array(
						'label' => 'Teléfono',
						'type' => 'string',
						'id' => 'e.telefono',
						'input' => 'text',
						'extra' => TRUE,
						'default' => FALSE,
						'filter' => FALSE
					),
					'e.calle' => array(
						'alias' => 'domicilio',
						'label' => 'Domicilio',
						'type' => 'string',
						'id' => 'e.calle',
						'input' => 'text',
						'filter' => FALSE,
						'extra' => TRUE,
						'default' => FALSE,
						'sql' => "CONCAT_WS(' ', e.calle, e.calle_numero, e.barrio)"
					),
					's.id' => array(
						'alias' => 'supervision_nombre',
						'label' => 'Supervisión',
						'type' => 'string',
						'default' => FALSE,
						'id' => 's.id',
						'sql' => 's.nombre',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'nombre',
							'sortField' => 'nombre',
							'searchField' => 'nombre',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('supervision', 'nombre', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					's.responsable' => array(
						'label' => 'Resp. Supervisión',
						'type' => 'string',
						'default' => FALSE,
						'id' => 's.responsable',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
					),
					'n.id' => array(
						'alias' => 'nivel',
						'label' => 'Nivel',
						'type' => 'string',
						'id' => 'n.id',
						'sql' => 'n.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('nivel', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'z.id' => array(
						'alias' => 'zona',
						'label' => 'Zona',
						'type' => 'string',
						'id' => 'z.id',
						'sql' => 'z.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('zona', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'l.id' => array(
						'alias' => 'linea',
						'label' => 'Linea',
						'type' => 'string',
						'default' => FALSE,
						'id' => 'l.id',
						'sql' => 'l.nombre',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => true,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'nombre',
							'sortField' => 'nombre',
							'searchField' => 'nombre',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('linea', 'nombre', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'dep.id' => array(
						'alias' => 'dependencia',
						'label' => 'Dependencia',
						'type' => 'string',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'id' => 'dep.id',
						'sql' => 'dep.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'multiple' => FALSE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('dependencia', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'r.id' => array(
						'alias' => 'regional',
						'label' => 'Regional',
						'type' => 'string',
						'id' => 'r.id',
						'sql' => 'r.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('regional', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'rl.id' => array(
						'alias' => 'regimen_lista',
						'label' => 'Lista Regimen',
						'type' => 'string',
						'id' => 'rl.id',
						'sql' => 'rl.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('regimen_lista', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'departamento.id' => array(
						'alias' => 'departamento',
						'label' => 'Departamento',
						'type' => 'string',
						'id' => 'departamento.id',
						'sql' => 'departamento.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('departamento', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'loc.id' => array(
						'alias' => 'localidad',
						'label' => 'Localidad',
						'type' => 'string',
						'id' => 'loc.id',
						'sql' => 'loc.descripcion',
						'input' => 'text',
						'filter' => FALSE,
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('localidad', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
				),
				'tabla' => 'escuela e',
				'relaciones' => array(
					's' => 'e.supervision_id=s.id',
					'n' => 'e.nivel_id=n.id',
					'l' => 'n.linea_id=l.id',
					'dep' => 'e.dependencia_id=dep.id',
					'r' => 'e.regional_id=r.id',
					'rl' => 'e.regimen_lista_id=rl.id',
					'loc' => 'e.localidad_id=loc.id',
					'departamento' => 'loc.departamento_id=departamento.id',
					'z' => 'e.zona_id=z.id',
				),
			),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Tablas Cargos">
			'c' => array(
				'id' => 'c',
				'nombre' => 'Cargos',
				'columnas' => array(
					'c.id' => array(
						'alias' => 'codigo_cargo_id',
						'label' => 'Código Cargo',
						'type' => 'string',
						'id' => 'c.id',
						'input' => 'text',
						'filter' => FALSE
					),
					'servicio.id' => array(
						'alias' => 'servicio_id',
						'label' => 'Código Servicio',
						'type' => 'string',
						'id' => 'servicio.id',
						'input' => 'text',
						'filter' => FALSE
					),
					'e.numero' => array(
						'alias' => 'e_numero',
						'label' => 'Nº Esc.',
						'type' => 'string',
						'id' => 'e.numero',
						'input' => 'text',
						'operators' => array('equal', 'begins_with'),
					),
					'e.nombre' => array(
						'alias' => 'e_nombre',
						'label' => 'Escuela',
						'type' => 'string',
						'id' => 'e.nombre',
						'input' => 'text',
						'operators' => array('equal', 'begins_with'),
					),
					'cc.id' => array(
						'alias' => 'condicion_cargo',
						'label' => 'Condición cargo',
						'type' => 'string',
						'id' => 'cc.id',
						'sql' => 'cc.descripcion',
						'input' => 'text',
						'multiple' => TRUE,
						'operators' => array('equal', 'not_equal'),
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('condicion_cargo', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'c.fecha_hasta' => array(
						'alias' => 'fecha_cierre',
						'label' => 'Fecha Cierre Cargo',
						'type' => 'string',
						'id' => 'c.fecha_hasta',
						'input' => 'text',
						'operators' => array('is_empty', 'is_not_empty'),
						'sql' => 'DATE_FORMAT(c.fecha_hasta, "%d/%m/%Y")'
					),
					'curso.descripcion' => array(
						'alias' => 'curso',
						'label' => 'Curso',
						'type' => 'string',
						'id' => 'curso.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'begins_with', 'is_empty', 'is_not_empty'),
					),
					'd.division' => array(
						'label' => 'División',
						'type' => 'string',
						'id' => 'd.division',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'begins_with', 'is_empty', 'is_not_empty'),
					),
					't.id' => array(
						'alias' => 'turno',
						'label' => 'Turno',
						'type' => 'string',
						'id' => 't.id',
						'sql' => 't.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('turno', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'regimen.codigo' => array(
						'alias' => 'regimen',
						'label' => 'Régimen cód.',
						'type' => 'string',
						'id' => 'regimen.codigo',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'begins_with', 'is_empty', 'is_not_empty'),
						'default' => FALSE
					),
					'regimen.descripcion' => array(
						'alias' => 'regimen_desc',
						'label' => 'Régimen desc.',
						'type' => 'string',
						'id' => 'regimen.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'begins_with', 'is_empty', 'is_not_empty'),
						'default' => FALSE
					),
					'm.descripcion' => array(
						'alias' => 'materia',
						'label' => 'Materia',
						'type' => 'string',
						'id' => 'm.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'begins_with', 'is_empty', 'is_not_empty'),
						'default' => FALSE
					),
					'c.carga_horaria' => array(
						'label' => 'Carga horaria',
						'type' => 'string',
						'id' => 'c.carga_horaria',
						'input' => 'text',
						'sum' => TRUE,
						'operators' => array('equal', 'not_equal'),
						'default' => FALSE
					),
					'servicio.liquidacion' => array(
						'label' => 'Liquidación',
						'type' => 'string',
						'id' => 'servicio.liquidacion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'begins_with', 'is_empty', 'is_not_empty'),
						'default' => FALSE
					),
					'sr.id' => array(
						'alias' => 'situacion_revista',
						'label' => 'Revista',
						'type' => 'string',
						'id' => 'sr.id',
						'sql' => 'sr.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('situacion_revista', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'servicio.fecha_alta' => array(
						'alias' => 'fecha_alta',
						'label' => 'Fecha Alta Servicio',
						'type' => 'string',
						'id' => 'servicio.fecha_alta',
						'input' => 'text',
						'extra' => TRUE,
						'filter' => FALSE,
						'default' => FALSE,
						'sql' => 'DATE_FORMAT(servicio.fecha_alta, "%d/%m/%Y")'
					),
					'servicio.fecha_baja' => array(
						'alias' => 'fecha_baja',
						'label' => 'Fecha Baja Servicio',
						'type' => 'string',
						'id' => 'servicio.fecha_baja',
						'input' => 'text',
						'operators' => array('is_empty', 'is_not_empty'),
						'default' => FALSE,
						'sql' => 'DATE_FORMAT(servicio.fecha_baja, "%d/%m/%Y")'
					),
					'p.cuil' => array(
						'label' => 'Cuil',
						'type' => 'string',
						'id' => 'p.id',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'begins_with', 'is_empty', 'is_not_empty'),
						'default' => FALSE
					),
					'p.id' => array(
						'alias' => 'apellido_nombre',
						'label' => 'Persona',
						'type' => 'string',
						'id' => 'p.id',
						'input' => 'text',
						'extra' => TRUE,
						'filter' => FALSE,
						'default' => FALSE,
						'sql' => "CONCAT(p.apellido, ', ', p.nombre)",
					),
					's.id' => array(
						'alias' => 'supervision',
						'label' => 'Supervision',
						'type' => 'string',
						'id' => 's.id',
						'sql' => 's.nombre',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'nombre',
							'sortField' => 'nombre',
							'searchField' => 'nombre',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('supervision', 'nombre', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'n.id' => array(
						'alias' => 'nivel',
						'label' => 'Nivel',
						'type' => 'string',
						'id' => 'n.id',
						'sql' => 'n.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('nivel', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'l.id' => array(
						'alias' => 'linea',
						'label' => 'Linea',
						'type' => 'string',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'id' => 'l.id',
						'sql' => 'l.nombre',
						'input' => 'text',
						'multiple' => true,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'nombre',
							'sortField' => 'nombre',
							'searchField' => 'nombre',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('linea', 'nombre', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'dep.id' => array(
						'alias' => 'dependencia',
						'label' => 'Dependencia',
						'type' => 'string',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'id' => 'dep.id',
						'sql' => 'dep.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'multiple' => FALSE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('dependencia', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'r.id' => array(
						'alias' => 'regional',
						'label' => 'Regional',
						'type' => 'string',
						'id' => 'r.id',
						'sql' => 'r.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('regional', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'departamento.id' => array(
						'alias' => 'departamento',
						'label' => 'Departamento',
						'type' => 'string',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'id' => 'departamento.id',
						'sql' => 'departamento.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('departamento', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
				),
				'tabla' => 'cargo c',
				'relaciones' => array(
					'cc' => 'c.condicion_cargo_id=cc.id',
					't' => 'c.turno_id=t.id',
					'd' => 'c.division_id=d.id',
					'curso' => 'd.curso_id=curso.id',
					'regimen' => 'c.regimen_id=regimen.id',
					'e' => 'c.escuela_id=e.id',
					'ec' => 'c.espacio_curricular_id=ec.id',
					'm' => 'ec.materia_id=m.id',
					'servicio' => 'servicio.cargo_id=c.id',
					'sr' => 'servicio.situacion_revista_id=sr.id',
					'p' => 'servicio.persona_id=p.id',
					's' => 'e.supervision_id=s.id',
					'n' => 'e.nivel_id=n.id',
					'l' => 'n.linea_id=l.id',
					'dep' => 'e.dependencia_id=dep.id',
					'r' => 'e.regional_id=r.id',
					'loc' => 'e.localidad_id=loc.id',
					'departamento' => 'loc.departamento_id=departamento.id',
				)
			),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Tablas Autoridades Escuela">
			'ae' => array(
				'id' => 'ae',
				'nombre' => 'Escuela - Autoridades',
				'tabla' => 'escuela_autoridad ae',
				'columnas' => array(
					'e.numero' => array(
						'label' => 'N° Esc.',
						'type' => 'string',
						'id' => 'e.numero',
						'input' => 'text',
						'operators' => array('equal', 'begins_with'),
					),
					'e.anexo' => array(
						'label' => 'Anexo',
						'type' => 'integer',
						'id' => 'e.anexo',
						'input' => 'number',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
						'default' => FALSE,
					),
					'e.cue' => array(
						'label' => 'CUE',
						'type' => 'integer',
						'id' => 'e.cue',
						'input' => 'number',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
					),
					'e.nombre' => array(
						'label' => 'Escuela',
						'type' => 'string',
						'id' => 'e.nombre',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
					),
					'e.calle' => array(
						'alias' => 'domicilio',
						'label' => 'Domicilio',
						'type' => 'string',
						'id' => 'e.calle',
						'input' => 'text',
						'filter' => FALSE,
						'extra' => TRUE,
						'default' => FALSE,
						'sql' => "CONCAT_WS(' ', e.calle, e.calle_numero, e.barrio)"
					),
					's.id' => array(
						'alias' => 'supervision_nombre',
						'label' => 'Supervisión',
						'type' => 'string',
						'default' => FALSE,
						'id' => 's.id',
						'sql' => 's.nombre',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'nombre',
							'sortField' => 'nombre',
							'searchField' => 'nombre',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('supervision', 'nombre', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					's.responsable' => array(
						'label' => 'Resp. Supervisión',
						'type' => 'string',
						'default' => FALSE,
						'id' => 's.responsable',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
					),
					'n.id' => array(
						'alias' => 'nivel',
						'label' => 'Nivel',
						'type' => 'string',
						'id' => 'n.id',
						'sql' => 'n.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('nivel', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'l.id' => array(
						'alias' => 'linea',
						'label' => 'Linea',
						'type' => 'string',
						'default' => FALSE,
						'id' => 'l.id',
						'sql' => 'l.nombre',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => true,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'nombre',
							'sortField' => 'nombre',
							'searchField' => 'nombre',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('linea', 'nombre', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'dep.id' => array(
						'alias' => 'dependencia',
						'label' => 'Dependencia',
						'type' => 'string',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'id' => 'dep.id',
						'sql' => 'dep.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'multiple' => FALSE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('dependencia', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'r.id' => array(
						'alias' => 'regional',
						'label' => 'Regional',
						'type' => 'string',
						'id' => 'r.id',
						'sql' => 'r.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('regional', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'rl.id' => array(
						'alias' => 'regimen_lista',
						'label' => 'Lista Regimen',
						'type' => 'string',
						'id' => 'rl.id',
						'sql' => 'rl.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('regimen_lista', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'departamento.id' => array(
						'alias' => 'departamento',
						'label' => 'Departamento',
						'type' => 'string',
						'id' => 'departamento.id',
						'sql' => 'departamento.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('departamento', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'p.apellido' => array(
						'label' => 'Apellido',
						'type' => 'string',
						'id' => 'p.apellido',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'begins_with'),
					),
					'p.nombre' => array(
						'alias' => 'persona_nombre',
						'label' => 'Nombre',
						'type' => 'string',
						'id' => 'p.nombre',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'begins_with'),
					),
					'p.documento' => array(
						'alias' => 'documento',
						'label' => 'Documento',
						'type' => 'string',
						'id' => 'p.documento',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'begins_with'),
					),
					'p.email' => array(
						'alias' => 'email',
						'label' => 'Email',
						'type' => 'string',
						'id' => 'p.email',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'begins_with'),
					),
					'p.telefono_fijo' => array(
						'alias' => 'telefono',
						'label' => 'Teléfono',
						'type' => 'string',
						'id' => 'p.telefono_fijo',
						'input' => 'text',
						'sql' => "CASE WHEN (p.telefono_fijo IS NOT NULL AND p.telefono_movil IS NOT NULL) THEN (CONCAT(p.telefono_movil, ' ', p.telefono_fijo)) ELSE (COALESCE(p.telefono_movil, p.telefono_fijo, ' ')) END",
						'extra' => TRUE,
						'filter' => FALSE
					),
					'autoridad.id' => array(
						'alias' => 'autoridad',
						'label' => 'Autoridad',
						'type' => 'string',
						'id' => 'autoridad.id',
						'sql' => 'autoridad.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('autoridad_tipo', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
				),
				'relaciones' => array(
					'e' => 'ae.escuela_id=e.id',
					's' => 'e.supervision_id=s.id',
					'n' => 'e.nivel_id=n.id',
					'l' => 'n.linea_id=l.id',
					'dep' => 'e.dependencia_id=dep.id',
					'r' => 'e.regional_id=r.id',
					'rl' => 'e.regimen_lista_id=rl.id',
					'loc' => 'e.localidad_id=loc.id',
					'departamento' => 'loc.departamento_id=departamento.id',
					'autoridad' => 'ae.autoridad_tipo_id=autoridad.id',
					'p' => 'ae.persona_id=p.id',
				)
			),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Tablas Características de alumnos">
			'ca' => array(
				'id' => 'ca',
				'nombre' => 'Alumno - Características',
				'tabla' => 'caracteristica_alumno ca',
				'columnas' => array(
					'p.id' => array(
						'alias' => 'alumno',
						'label' => 'Alumno',
						'type' => 'string',
						'id' => 'p.id',
						'input' => 'text',
						'sql' => 'CONCAT(p.apellido, ", ", p.nombre)',
						'extra' => TRUE,
						'filter' => FALSE
					),
					'p.fecha_nacimiento' => array(
						'alias' => 'fecha_nacimiento',
						'label' => 'Fecha nacimiento',
						'type' => 'string',
						'id' => 'p.fecha_nacimiento',
						'input' => 'text',
						'filter' => FALSE,
						'extra' => TRUE,
						'sql' => 'DATE_FORMAT(p.fecha_nacimiento, "%d/%m/%Y")'
					),
					'p.calle' => array(
						'alias' => 'domicilio',
						'label' => 'Domicilio',
						'type' => 'string',
						'id' => 'p.calle',
						'input' => 'text',
						'filter' => FALSE,
						'extra' => TRUE,
						'sql' => "CONCAT_WS(' ', p.calle, p.calle_numero, p.barrio)"
					),
					'p.documento' => array(
						'label' => 'Documento',
						'type' => 'string',
						'id' => 'p.documento',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
					),
					'a.email_contacto' => array(
						'label' => 'Email alumno',
						'type' => 'string',
						'id' => 'a.email_contacto',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
					),
					'sexo.id' => array(
						'alias' => 'sexo',
						'label' => 'Sexo',
						'type' => 'string',
						'id' => 'sexo.id',
						'sql' => 'sexo.descripcion',
						'input' => 'text',
						'operators' => array('equal'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('sexo', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'e.numero' => array(
						'label' => 'N° Esc.',
						'type' => 'string',
						'id' => 'e.numero',
						'input' => 'text',
						'operators' => array('equal', 'begins_with'),
					),
					'e.anexo' => array(
						'label' => 'Anexo',
						'type' => 'integer',
						'id' => 'e.anexo',
						'input' => 'number',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
						'sum' => TRUE,
					),
					'e.nombre' => array(
						'label' => 'Escuela',
						'type' => 'string',
						'id' => 'e.nombre',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
					),
					'e.telefono' => array(
						'label' => 'Esc. Teléfono',
						'type' => 'string',
						'id' => 'e.telefono',
						'input' => 'text',
						'extra' => TRUE,
						'default' => FALSE,
						'filter' => FALSE
					),
					'e.calle' => array(
						'alias' => 'escuela_domicilio',
						'label' => 'Esc. Domicilio',
						'type' => 'string',
						'id' => 'e.calle',
						'input' => 'text',
						'filter' => FALSE,
						'extra' => TRUE,
						'default' => FALSE,
						'sql' => "CONCAT_WS(' ', e.calle, e.calle_numero, e.barrio)"
					),
					's.id' => array(
						'alias' => 'supervision_nombre',
						'label' => 'Supervisión',
						'type' => 'string',
						'default' => FALSE,
						'id' => 's.id',
						'sql' => 's.nombre',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'nombre',
							'sortField' => 'nombre',
							'searchField' => 'nombre',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('supervision', 'nombre', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'r.id' => array(
						'alias' => 'regional',
						'label' => 'Regional',
						'type' => 'string',
						'id' => 'r.id',
						'sql' => 'r.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('regional', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'departamento.id' => array(
						'alias' => 'departamento',
						'label' => 'Esc. Departamento',
						'type' => 'string',
						'id' => 'departamento.id',
						'sql' => 'departamento.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('departamento', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					't.id' => array(
						'alias' => 'turno',
						'label' => 'Turno',
						'type' => 'string',
						'id' => 't.id',
						'sql' => 't.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('turno', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'curso.descripcion' => array(
						'alias' => 'curso',
						'label' => 'Curso',
						'type' => 'string',
						'id' => 'curso.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'operators' => array('equal', 'not_equal', 'begins_with', 'is_empty', 'is_not_empty'),
					),
					'd.division' => array(
						'label' => 'División',
						'type' => 'string',
						'id' => 'd.division',
						'input' => 'text',
						'default' => FALSE,
						'operators' => array('equal', 'not_equal', 'begins_with', 'is_empty', 'is_not_empty'),
					),
					'carrera.id' => array(
						'alias' => 'carrera',
						'label' => 'Carrera',
						'type' => 'string',
						'id' => 'carrera.id',
						'sql' => 'carrera.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('carrera', 'descripcion', 'id', TRUE, null, array('select' => "carrera.id, CONCAT(nivel.descripcion, ' - ', carrera.descripcion) descripcion", 'join' => array(array('nivel', 'carrera.nivel_id=nivel.id', 'left'))))
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'n.id' => array(
						'alias' => 'nivel',
						'label' => 'Nivel',
						'type' => 'string',
						'id' => 'n.id',
						'sql' => 'n.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('nivel', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'a.nombre_padre' => array(
						'label' => 'Padre',
						'type' => 'string',
						'id' => 'a.nombre_padre',
						'input' => 'text',
						'extra' => TRUE,
						'filter' => FALSE,
						'default' => FALSE
					),
					'a.nombre_madre' => array(
						'label' => 'Madre',
						'type' => 'string',
						'id' => 'a.nombre_madre',
						'input' => 'text',
						'extra' => TRUE,
						'filter' => FALSE,
						'default' => FALSE
					),
					'caracteristica.id' => array(
						'alias' => 'caracteristica',
						'label' => 'Característica',
						'type' => 'string',
						'id' => 'caracteristica.id',
						'sql' => 'caracteristica.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('caracteristica', 'descripcion', 'id', TRUE, NULL, array('select' => "caracteristica.id, caracteristica.descripcion", 'join' => array(array('caracteristica_tipo', "caracteristica.caracteristica_tipo_id=caracteristica_tipo.id AND entidad='alumno'", ''))))
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'ct.id' => array(
						'alias' => 'caracteristica_tipo',
						'label' => 'Tipo Característica',
						'type' => 'string',
						'id' => 'ct.id',
						'sql' => 'ct.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('caracteristica_tipo', 'descripcion', 'id', TRUE, array('entidad' => 'alumno'))
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'ca.valor' => array(
						'label' => 'Valor',
						'type' => 'string',
						'id' => 'ca.valor',
						'input' => 'text',
						'default' => FALSE,
						'operators' => array('equal', 'not_equal', 'begins_with', 'is_empty', 'is_not_empty'),
					),
				),
				'relaciones' => array(
					'a' => 'ca.alumno_id=a.id',
					'p' => 'a.persona_id=p.id',
					'sexo' => 'p.sexo_id=sexo.id',
					'caracteristica' => 'ca.caracteristica_id=caracteristica.id',
					'ct' => 'caracteristica.caracteristica_tipo_id=ct.id',
					'ad' => 'ad.alumno_id=a.id',
					'd' => 'ad.division_id=d.id',
					'e' => 'd.escuela_id=e.id',
					's' => 'e.supervision_id=s.id',
					'r' => 'e.regional_id=r.id',
					'loc' => 'e.localidad_id=loc.id',
					'departamento' => 'loc.departamento_id=departamento.id',
					't' => 'd.turno_id=t.id',
					'curso' => 'd.curso_id=curso.id',
					'carrera' => 'd.carrera_id=carrera.id',
					'n' => 'e.nivel_id=n.id'
				),
			),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Tablas Características Escuela">
			'ce' => array(
				'id' => 'ce',
				'nombre' => 'Escuela - Características',
				'tabla' => 'caracteristica ce',
				'columnas' => array(
					'e.numero' => array(
						'label' => 'N° Esc.',
						'type' => 'string',
						'id' => 'e.numero',
						'input' => 'text',
						'operators' => array('equal', 'begins_with'),
					),
					'e.anexo' => array(
						'label' => 'Anexo',
						'type' => 'integer',
						'id' => 'e.anexo',
						'input' => 'number',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
						'sum' => TRUE,
					),
					'e.nombre' => array(
						'label' => 'Escuela',
						'type' => 'string',
						'id' => 'e.nombre',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
					),
					'e.cue' => array(
						'label' => 'CUE',
						'type' => 'integer',
						'id' => 'e.cue',
						'input' => 'number',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
					),
					'e.telefono' => array(
						'label' => 'Teléfono',
						'type' => 'string',
						'id' => 'e.telefono',
						'input' => 'text',
						'extra' => TRUE,
						'default' => FALSE,
						'filter' => FALSE
					),
					'e.calle' => array(
						'alias' => 'domicilio',
						'label' => 'Domicilio',
						'type' => 'string',
						'id' => 'e.calle',
						'input' => 'text',
						'filter' => FALSE,
						'extra' => TRUE,
						'default' => FALSE,
						'sql' => "CONCAT_WS(' ', e.calle, e.calle_numero, e.barrio)"
					),
					's.id' => array(
						'alias' => 'supervision_nombre',
						'label' => 'Supervisión',
						'type' => 'string',
						'default' => FALSE,
						'id' => 's.id',
						'sql' => 's.nombre',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'nombre',
							'sortField' => 'nombre',
							'searchField' => 'nombre',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('supervision', 'nombre', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					's.responsable' => array(
						'label' => 'Resp. Supervisión',
						'type' => 'string',
						'default' => FALSE,
						'id' => 's.responsable',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
					),
					'n.id' => array(
						'alias' => 'nivel',
						'label' => 'Nivel',
						'type' => 'string',
						'id' => 'n.id',
						'sql' => 'n.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('nivel', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'l.id' => array(
						'alias' => 'linea',
						'label' => 'Linea',
						'type' => 'string',
						'default' => FALSE,
						'id' => 'l.id',
						'sql' => 'l.nombre',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => true,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'nombre',
							'sortField' => 'nombre',
							'searchField' => 'nombre',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('linea', 'nombre', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'dep.id' => array(
						'alias' => 'dependencia',
						'label' => 'Dependencia',
						'type' => 'string',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'id' => 'dep.id',
						'sql' => 'dep.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'multiple' => FALSE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('dependencia', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'r.id' => array(
						'alias' => 'regional',
						'label' => 'Regional',
						'type' => 'string',
						'id' => 'r.id',
						'sql' => 'r.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('regional', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'rl.id' => array(
						'alias' => 'regimen_lista',
						'label' => 'Lista Regimen',
						'type' => 'string',
						'id' => 'rl.id',
						'sql' => 'rl.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('regimen_lista', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'departamento.id' => array(
						'alias' => 'departamento',
						'label' => 'Departamento',
						'type' => 'string',
						'id' => 'departamento.id',
						'sql' => 'departamento.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('departamento', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'ce.id' => array(
						'alias' => 'caracteristica',
						'label' => 'Característica',
						'type' => 'string',
						'id' => 'ce.id',
						'sql' => 'ce.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('caracteristica', 'descripcion', 'id', TRUE, NULL, array('select' => "caracteristica.id, caracteristica.descripcion", 'join' => array(array('caracteristica_tipo', "caracteristica.caracteristica_tipo_id=caracteristica_tipo.id AND entidad='escuela'", '')))),
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'ct.id' => array(
						'alias' => 'caracteristica_tipo',
						'label' => 'Tipo Característica',
						'type' => 'string',
						'id' => 'ct.id',
						'sql' => 'ct.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('caracteristica_tipo', 'descripcion', 'id', TRUE, array('entidad' => 'escuela'))
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'cce.valor' => array(
						'label' => 'Valor',
						'type' => 'string',
						'id' => 'cce.valor',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'begins_with', 'is_empty', 'is_not_empty'),
					),
				),
				'relaciones' => array(
					'e' => 'e.id=e.id',
					's' => 'e.supervision_id=s.id',
					'n' => 'e.nivel_id=n.id',
					'l' => 'n.linea_id=l.id',
					'dep' => 'e.dependencia_id=dep.id',
					'r' => 'e.regional_id=r.id',
					'rl' => 'e.regimen_lista_id=rl.id',
					'loc' => 'e.localidad_id=loc.id',
					'departamento' => 'loc.departamento_id=departamento.id',
					'cce' => 'ce.id=cce.caracteristica_id and cce.escuela_id=e.id and cce.fecha_hasta is null',
					'ct' => 'ce.caracteristica_tipo_id=ct.id',
				),
			),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Tablas Alumnos">
			'ad' => array(
				'id' => 'ad',
				'nombre' => 'Alumno',
				'tabla' => 'alumno_division ad',
				'columnas' => array(
					'p.id' => array(
						'alias' => 'alumno',
						'label' => 'Alumno',
						'type' => 'string',
						'id' => 'p.id',
						'input' => 'text',
						'filter' => FALSE,
						'sql' => 'CONCAT(p.apellido, ", ", p.nombre)'
					),
					'p.fecha_nacimiento' => array(
						'alias' => 'fecha_nacimiento',
						'label' => 'Fecha nacimiento',
						'type' => 'string',
						'id' => 'p.fecha_nacimiento',
						'input' => 'text',
						'filter' => FALSE,
						'extra' => TRUE,
						'sql' => 'DATE_FORMAT(p.fecha_nacimiento, "%d/%m/%Y")'
					),
					'p.calle' => array(
						'alias' => 'domicilio',
						'label' => 'Domicilio',
						'type' => 'string',
						'id' => 'p.calle',
						'input' => 'text',
						'filter' => FALSE,
						'extra' => TRUE,
						'sql' => "CONCAT_WS(' ', p.calle, p.calle_numero, p.barrio)"
					),
					'p.documento' => array(
						'label' => 'Documento',
						'type' => 'string',
						'id' => 'p.documento',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
					),
					'a.email_contacto' => array(
						'label' => 'Email alumno',
						'type' => 'string',
						'id' => 'a.email_contacto',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
					),
					'sexo.id' => array(
						'alias' => 'sexo',
						'label' => 'Sexo',
						'type' => 'string',
						'id' => 'sexo.id',
						'input' => 'text',
						'sql' => 'sexo.descripcion',
						'operators' => array('equal'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('sexo', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'a.nombre_padre' => array(
						'label' => 'Padre',
						'type' => 'string',
						'id' => 'a.nombre_padre',
						'input' => 'text',
						'filter' => FALSE,
						'extra' => TRUE,
					),
					'a.nombre_madre' => array(
						'label' => 'Madre',
						'type' => 'string',
						'id' => 'a.nombre_madre',
						'input' => 'text',
						'filter' => FALSE,
						'extra' => TRUE,
					),
					'e.numero' => array(
						'label' => 'N° Esc.',
						'type' => 'string',
						'id' => 'e.numero',
						'input' => 'text',
						'operators' => array('equal', 'begins_with'),
					),
					'e.anexo' => array(
						'label' => 'Anexo',
						'type' => 'integer',
						'id' => 'e.anexo',
						'input' => 'number',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
						'sum' => TRUE,
					),
					'e.nombre' => array(
						'label' => 'Escuela',
						'type' => 'string',
						'id' => 'e.nombre',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty', 'begins_with'),
					),
					'e.calle' => array(
						'alias' => 'escuela_domicilio',
						'label' => 'Esc. Domicilio',
						'type' => 'string',
						'id' => 'e.calle',
						'input' => 'text',
						'filter' => FALSE,
						'extra' => TRUE,
						'default' => FALSE,
						'sql' => "CONCAT_WS(' ', e.calle, e.calle_numero, e.barrio)"
					),
					'e.telefono' => array(
						'label' => 'Esc. Teléfono',
						'type' => 'string',
						'id' => 'e.telefono',
						'input' => 'text',
						'extra' => TRUE,
						'default' => FALSE,
						'filter' => FALSE
					),
					's.id' => array(
						'alias' => 'supervision_nombre',
						'label' => 'Supervisión',
						'type' => 'string',
						'default' => FALSE,
						'id' => 's.id',
						'sql' => 's.nombre',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'nombre',
							'sortField' => 'nombre',
							'searchField' => 'nombre',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('supervision', 'nombre', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'r.id' => array(
						'alias' => 'regional',
						'label' => 'Regional',
						'type' => 'string',
						'id' => 'r.id',
						'sql' => 'r.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('regional', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'departamento.id' => array(
						'alias' => 'departamento',
						'label' => 'Esc. Departamento',
						'type' => 'string',
						'id' => 'departamento.id',
						'sql' => 'departamento.descripcion',
						'input' => 'text',
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'default' => FALSE,
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('departamento', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'd.division' => array(
						'label' => 'División',
						'type' => 'string',
						'id' => 'd.division',
						'input' => 'text',
						'default' => FALSE,
						'operators' => array('equal', 'not_equal', 'begins_with', 'is_empty', 'is_not_empty'),
					),
					'ad.ciclo_lectivo' => array(
						'label' => 'Ciclo Lectivo',
						'type' => 'string',
						'id' => 'ad.ciclo_lectivo',
						'input' => 'text',
					),
					'ad.fecha_hasta' => array(
						'alias' => 'fecha_hasta',
						'label' => 'Fecha Egreso',
						'type' => 'string',
						'id' => 'ad.fecha_hasta',
						'input' => 'text',
						'operators' => array('is_empty', 'is_not_empty'),
						'sql' => 'DATE_FORMAT(ad.fecha_hasta, "%d/%m/%Y")'
					),
					't.id' => array(
						'alias' => 'turno',
						'label' => 'Turno',
						'type' => 'string',
						'id' => 't.id',
						'sql' => 't.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('turno', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'curso.descripcion' => array(
						'alias' => 'curso',
						'label' => 'Curso',
						'type' => 'string',
						'id' => 'curso.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'operators' => array('equal', 'not_equal', 'begins_with', 'is_empty', 'is_not_empty'),
					),
					'carrera.id' => array(
						'alias' => 'carrera',
						'label' => 'Carrera',
						'type' => 'string',
						'id' => 'carrera.id',
						'sql' => 'carrera.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('carrera', 'descripcion', 'id', TRUE, null, array('select' => "carrera.id, CONCAT(nivel.descripcion, ' - ', carrera.descripcion) descripcion", 'join' => array(array('nivel', 'carrera.nivel_id=nivel.id', 'left'))))
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
					'n.id' => array(
						'alias' => 'nivel',
						'label' => 'Nivel',
						'type' => 'string',
						'id' => 'n.id',
						'sql' => 'n.descripcion',
						'input' => 'text',
						'default' => FALSE,
						'operators' => array('equal', 'not_equal', 'is_empty', 'is_not_empty'),
						'multiple' => TRUE,
						'plugin' => 'selectize',
						'plugin_config' => array(
							'valueField' => 'id',
							'labelField' => 'descripcion',
							'sortField' => 'descripcion',
							'searchField' => 'descripcion',
							'create' => false,
							'plugins' => ['remove_button'],
							'options' => $this->get_array('nivel', 'descripcion', 'id', TRUE)
						),
						'valueSetter' => "\$\$function (rule, value) {if (rule.operator.nb_inputs > 0) {var val = value.split(',');var select = rule.\$el.find('.rule-value-container [name$=_0]').selectize()[0].selectize;val.forEach(function (item) {select.addItem(item);});}}\$\$"
					),
				),
				'relaciones' => array(
					'a' => 'ad.alumno_id=a.id',
					'p' => 'a.persona_id=p.id',
					'sexo' => 'p.sexo_id=sexo.id',
					'd' => 'ad.division_id=d.id',
					'e' => 'd.escuela_id=e.id',
					's' => 'e.supervision_id=s.id',
					'r' => 'e.regional_id=r.id',
					'loc' => 'e.localidad_id=loc.id',
					'departamento' => 'loc.departamento_id=departamento.id',
					't' => 'd.turno_id=t.id',
					'curso' => 'd.curso_id=curso.id',
					'carrera' => 'd.carrera_id=carrera.id',
					'n' => 'e.nivel_id=n.id'
				),
			),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Otras tablas">
			'a' => array(
				'id' => 'a',
				'nombre' => 'Alumno',
				'tabla' => 'alumno a',
			),
			'autoridad' => array(
				'id' => 'autoridad',
				'nombre' => 'Autoridad',
				'tabla' => 'autoridad_tipo autoridad',
			),
			'sexo' => array(
				'id' => 'sexo',
				'nombre' => 'Sexo',
				'tabla' => 'sexo',
			),
			'carrera' => array(
				'id' => 'carrera',
				'nombre' => 'Carrera',
				'tabla' => 'carrera',
			),
			'd' => array(
				'id' => 'd',
				'nombre' => 'Divisiones',
				'tabla' => 'division d',
			),
			's' => array(
				'id' => 's',
				'nombre' => 'Supervisión',
				'tabla' => 'supervision s',
			),
			'n' => array(
				'id' => 'n',
				'nombre' => 'Nivel',
				'tabla' => 'nivel n',
			),
			'l' => array(
				'nombre' => 'Linea',
				'tabla' => 'linea l',
			),
			'dep' => array(
				'id' => 'dep',
				'nombre' => 'Dependencia',
				'tabla' => 'dependencia dep',
			),
			'r' => array(
				'id' => 'r',
				'nombre' => 'Regional',
				'tabla' => 'regional r',
			),
			'rl' => array(
				'id' => 'rl',
				'nombre' => 'Lista Regimen',
				'tabla' => 'regimen_lista rl',
			),
			'loc' => array(
				'id' => 'loc',
				'nombre' => 'Localidad',
				'tabla' => 'localidad loc',
			),
			'departamento' => array(
				'id' => 'departamento',
				'nombre' => 'Departamento',
				'tabla' => 'departamento',
			),
			'cc' => array(
				'id' => 'cc',
				'nombre' => 'Condición cargo',
				'tabla' => 'condicion_cargo cc',
			),
			't' => array(
				'id' => 't',
				'nombre' => 'Turno',
				'tabla' => 'turno t',
			),
			'regimen' => array(
				'id' => 'regimen',
				'nombre' => 'Regimen',
				'tabla' => 'regimen',
			),
			'curso' => array(
				'id' => 'curso',
				'nombre' => 'Curso',
				'tabla' => 'curso',
			),
			'ec' => array(
				'id' => 'ec',
				'nombre' => 'Espacio curricular',
				'tabla' => 'espacio_curricular ec',
			),
			'm' => array(
				'id' => 'm',
				'nombre' => 'Materia',
				'tabla' => 'materia m',
			),
			'servicio' => array(
				'id' => 'servicio',
				'nombre' => 'Servicio',
				'tabla' => 'servicio',
			),
			'sr' => array(
				'id' => 'sr',
				'nombre' => 'S.R',
				'tabla' => 'situacion_revista sr',
			),
			'p' => array(
				'id' => 'p',
				'nombre' => 'Persona',
				'tabla' => 'persona p',
			),
			'caracteristica' => array(
				'id' => 'caracteristica',
				'nombre' => 'Característica',
				'tabla' => 'caracteristica',
			),
			'cce' => array(
				'id' => 'cce',
				'nombre' => 'Característica',
				'tabla' => 'caracteristica_escuela cce',
			),
			'ct' => array(
				'id' => 'ct',
				'nombre' => 'Tipo Característica',
				'tabla' => 'caracteristica_tipo ct',
			),
			'z' => array(
				'id' => 'z',
				'nombre' => 'Zona',
				'tabla' => 'zona z',
			),
// </editor-fold>
		);
	}

	public function get_tablas() {
		return $this->tablas;
	}

	public function get_tablas_reportes() {
		return $this->tablas_test;
	}

	public function get_tabla($tabla_id = NULL) {
		if (!empty($tabla_id) && isset($this->tablas[$tabla_id])) {
			return $this->tablas[$tabla_id];
		} else {
			return FALSE;
		}
	}

	public function get_filtros($tabla_id = NULL) {
		$filtros = array();
		if (!empty($tabla_id) && isset($this->tablas[$tabla_id])) {
			$tabla = $this->tablas[$tabla_id];
			foreach ($tabla['columnas'] as $columna) {
				if (!isset($columna['filter']) || (isset($columna['filter']) && $columna['filter'])) {
					$filtros[] = $columna;
				}
			}
		}
		return str_replace('$$"', '', str_replace('"$$', '', json_encode($filtros)));
	}

	public function get_array($model, $desc = 'descripcion', $id = 'id', $json = FALSE, $conditions = NULL, $options = array()) {

		if (isset($options['select'])) {
			$this->db->select($options['select']);
		} else {
			$this->db->select(array($id, $desc));
		}
		$this->db->from($model);
		if (isset($options['join'])) {
			foreach ($options['join'] as $join) {
				$this->db->join($join[0], $join[1], isset($join[2]) ? $join[2] : '');
			}
		}

		if (!empty($conditions)) {
			$this->db->where($conditions);
		}
		$this->db->order_by($desc);
		$registros = $this->db->get()->result();
		if ($json) {
			return $registros;
		}
		if (!empty($registros)) {
			foreach ($registros as $Registro) {
				$array_registros[] = array($Registro->{$id} => $Registro->{$desc});
			}
		}
		return $array_registros;
	}

	public function get_reporte_previo(&$parametros, $tipo = 0) {
		$tabla = $this->reportes_model->get_tabla($parametros['from']);

		$group_by = array();

		foreach ($parametros['columnas'] as $index => $columna) {
			switch ($parametros['filtros'][$index]) {
				case 'agrupar':
					if (isset($tabla['columnas'][$columna]['sql'])) {
						$this->db->select($tabla['columnas'][$columna]['sql'] . ' as ' . $tabla['columnas'][$columna]['alias']);
					} else {
						if (isset($tabla['columnas'][$columna]['alias'])) {
							$this->db->select($columna . ' as ' . $tabla['columnas'][$columna]['alias']);
						} else {
							$this->db->select($columna);
						}
					}
					$group_by[] = $columna;
					$parametros['checked']['agrupar'][] = $columna;
					break;

				case 'contar':
					if (isset($tabla['columnas'][$columna]['alias'])) {
						$this->db->select('COUNT(DISTINCT ' . $columna . ') as ' . $tabla['columnas'][$columna]['alias']);
					} else {
						$this->db->select('COUNT(DISTINCT ' . $columna . ') as ' . '"' . $columna . '"');
					}
					$parametros['checked']['contar'][] = $columna;
					break;

				case 'sumar':
					if (isset($tabla['columnas'][$columna]['alias'])) {
						$this->db->select('SUM(' . $columna . ') as ' . $tabla['columnas'][$columna]['alias']);
					} else {
						$this->db->select('SUM(' . $columna . ') as ' . '"' . $columna . '"');
					}
					$parametros['checked']['sumar'][] = $columna;
					break;
			}
		}
		$this->db->from($tabla['tabla']);

		foreach ($tabla['relaciones'] as $t => $condicion) {
			$tabla_r = $this->reportes_model->get_tabla($t)['tabla'];
			$this->db->join($tabla_r, $condicion, 'left');
		}

		$this->db->where('e.id is NOT NULL', NULL, FALSE);
		if ($tabla['tabla'] === 'caracteristica ce') {
			$this->db->where('ct.entidad', 'escuela');
		}
		if ($tabla['tabla'] === 'cargo c') {
			$this->db->where('regimen.planilla_modalidad_id', 1);
		}
		

		foreach ($parametros['where'] as $where) {
			$this->set_where($where);
		}

		switch ($this->rol->codigo) {
			case ROL_ADMIN:
			case ROL_USI:
			case ROL_CONSULTA:
			case ROL_JEFE_LIQUIDACION:
			case ROL_LIQUIDACION:
				break;
			case ROL_ESCUELA_ALUM:
			case ROL_ESCUELA_CAR:
			case ROL_DIR_ESCUELA:
			case ROL_SDIR_ESCUELA:
			case ROL_SEC_ESCUELA:
				$this->db->where('e.escuela_id', $this->rol->entidad_id);
				break;
			case ROL_SUPERVISION:
				$this->db->where('e.supervision_id', $this->rol->entidad_id);
				break;
			case ROL_LINEA:
			case ROL_GRUPO_ESCUELA:
			case ROL_CONSULTA_LINEA:
				$this->db->where('n.linea_id', $this->rol->entidad_id);
				break;
			case ROL_PRIVADA:
				$this->db->where('e.dependencia_id', '2');
				break;
			case ROL_SEOS:
				$this->db->where('e.dependencia_id', '3');
				break;
			case ROL_REGIONAL:
				$this->db->where('e.regional_id', $this->rol->entidad_id);
				break;
		}

		$this->db->group_by($group_by);

		if ($tipo === 0) {
			$resul = $this->db->limit(10)->get()->result();
			return $resul;
		}

		return $this->db->get()->result_array();
	}

	public function get_reportes_guardados($usuario_id) {
		return $this->db->select(array('id', 'nombre', 'reporte', 'ultima_ejecucion'))
				->from('reporte')
				->where('usuario_id', $usuario_id)
				->get()->result();
	}

	public function set_where($where) {
		switch ($where['operator']) {
			case 'equal':
			case 'in':
				$this->db->where_in($where['filter'], $where['value']);
				break;

			case 'not_equal':
			case 'not_in':
				$this->db->where_not_in($where['filter'], $where['value']);
				break;

			case 'begins_with':
				$this->db->like($where['filter'], $where['value'][0], 'after');
				break;

			case 'not_begins_with':
				$this->db->not_like($where['filter'], $where['value'][0], 'after');
				break;

			case 'contains':
				$this->db->like($where['filter'], $where['value'][0]);
				break;

			case 'not_contains':
				$this->db->not_like($where['filter'], $where['value'][0]);
				break;

			case 'ends_with':
				$this->db->like($where['filter'], $where['value'][0], 'before');
				break;

			case 'not_ends_with':
				$this->db->not_like($where['filter'], $where['value'][0], 'before');
				break;

			case 'is_empty':
				$this->db->group_start();
				$this->db->where($where['filter'], '');
				$this->db->or_where($where['filter'] . ' IS NULL');
				$this->db->group_end();
				break;

			case 'is_not_empty':
				$this->db->group_start();
				$this->db->where($where['filter'] . ' !=', '');
				$this->db->or_where($where['filter'] . ' IS NOT NULL');
				$this->db->group_end();
				break;

			case 'is_null':
				$this->db->where($where['filter'] . ' IS NULL');
				break;

			case 'is_not_null':
				$this->db->where($where['filter'] . ' IS NOT NULL');
				break;
		}
	}
}
/* End of file Reportes_model.php */
/* Location: ./application/models/Reportes_model.php */