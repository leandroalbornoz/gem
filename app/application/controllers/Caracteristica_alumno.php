<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include 'Caracteristica_tipo.php';

class Caracteristica_alumno extends Caracteristica_tipo {

	function __construct() {
		$this->entidad = 'alumno';
		parent::__construct();
	}
}
/* End of file Caracteristica_alumno.php */
/* Location: ./application/controllers/Caracteristica_alumno.php */