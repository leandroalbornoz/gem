<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escritorio extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->roles_permitidos = array(ROL_ADMIN);
		$this->nav_route = 'trayectoria/buscar_alumno';
		$this->modulos_permitidos = array(ROL_MODULO_CONSULTA_TRAYECTORIA);
		$this->load->model('escuela_model');
		$this->load->model('alumno_model');
		$this->load->model('persona_model');
	}

	public function index() {
		redirect('trayectoria/escritorio/buscar_alumno');
	}

	public function buscar_alumno() {
		if (!accion_permitida($this->rol, $this->roles_permitidos, $this->modulos_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$model_busqueda = new stdClass();
		$model_busqueda->fields = array(
			'documento' => array('label' => 'Documento', 'maxlength' => '13'),
			'apellido' => array('label' => 'Apellido', 'maxlength' => '100', 'minlength' => '3'),
			'nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'minlength' => '3'),
		);
		$data['fields'] = $this->build_fields($model_busqueda->fields);
		$data['txt_btn'] = 'Buscar';
		$data['title'] = 'Buscar Alumno';
		$this->load_template('trayectoria/trayectoria_alumnos/trayectoria_alumnos_escritorio', $data);
	}
}
/* End of file Escritorio.php */