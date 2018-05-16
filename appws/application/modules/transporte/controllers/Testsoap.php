<?php

class Testsoap extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library("Nusoap");
		$this->load->library('encryption');
	}

	public function testServiceAlumno($dni) {
		$client = new nusoap_client("https://testing.mendoza.edu.ar/gem/appws/index.php/transporte/service?wsdl");
		$params = array(
			'api_key' => '954dfdd6e70461f9542f651fb93b0f40d2d415c9bc0e7dab823abc2e96e54874',
			'dni' => $dni
		);
		$result2 = $client->call('get_alumno', $params);
		print_r("Alumno: ");
		print_r($result2);
	}

	function testServiceDocente($dni) {
		$client = new nusoap_client("https://testing.mendoza.edu.ar/gem/appws/index.php/transporte/service?wsdl");
		$params = array(
			'dni' => $dni,
			'api_key' => '954dfdd6e70461f9542f651fb93b0f40d2d415c9bc0e7dab823abc2e96e54874'
		);
		$result2 = $client->call('get_docente', $params);
		print_r("Docente: ");
		print_r($result2);
	}

	function testServiceEscuelas($escuela_numero, $escuela_anexo) {
		$client = new nusoap_client("https://testing.mendoza.edu.ar/gem/appws/index.php/transporte/service?wsdl");
		$params = array(
			'escuela_numero' => $escuela_numero,
			'escuela_anexo' => $escuela_anexo,
			'api_key' => '954dfdd6e70461f9542f651fb93b0f40d2d415c9bc0e7dab823abc2e96e54874'
		);
		$result = $client->call('get_escuelas', $params);
		print_r("Escuelas: ");
		print_r($result);
	}
}