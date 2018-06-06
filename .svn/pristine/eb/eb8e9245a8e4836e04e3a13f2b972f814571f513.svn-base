<?php

include_once "Response.php";

class Service extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library("Nusoap");

		$ns = "servicio";
		$this->nusoap_server = new soap_server();
		$this->nusoap_server->soap_defencoding = 'ISO-8859-1';
		$this->nusoap_server->decode_utf8 = false;
		$this->nusoap_server->encode_utf8 = true;
		$this->nusoap_server->configureWSDL("SOAP Server", $ns);
		$this->nusoap_server->wsdl->schemaTargetNamespace = $ns;

		$this->nusoap_server->wsdl->addComplexType(
			"Llamado", "complexType", "struct", "all", "", array(
			"fecha_carga" => array("name" => "fecha_carga", "type" => "xsd:string"),
			"tipo_llamado" => array("name" => "tipo_llamado", "type" => "xsd:string"),
			"condicion_cargo" => array("name" => "condicion_cargo", "type" => "xsd:string"),
			"regimen" => array("name" => "regimen", "type" => "xsd:string"),
			"horas" => array("name" => "horas", "type" => "xsd:string"),
			"lugar_trabajo" => array("name" => "lugar_trabajo", "type" => "xsd:string"),
			"direccion" => array("name" => "direccion", "type" => "xsd:string"),
			"localidad" => array("name" => "localidad", "type" => "xsd:string"),
			"departamento" => array("name" => "departamento", "type" => "xsd:string"),
			"regional" => array("name" => "regional", "type" => "xsd:string"),
			"zona" => array("name" => "zona", "type" => "xsd:string"),
			"fecha_llamado_1" => array("name" => "fecha_llamado_1", "type" => "xsd:string"),
			"fecha_llamado_2" => array("name" => "fecha_llamado_2", "type" => "xsd:string"),
			"fecha_llamado_3" => array("name" => "fecha_llamado_3", "type" => "xsd:string"),
			"fecha_llamado_4" => array("name" => "fecha_llamado_4", "type" => "xsd:string"),
			"articulo" => array("name" => "articulo", "type" => "xsd:string"),
			"fin_estimado" => array("name" => "fin_estimado", "type" => "xsd:string"),
			"division" => array("name" => "division", "type" => "xsd:string"),
			"materia" => array("name" => "materia", "type" => "xsd:string"),
			"turno" => array("name" => "turno", "type" => "xsd:string"),
			"horario" => array("name" => "horario", "type" => "xsd:string"),
			"presentarse_en" => array("name" => "presentarse_en", "type" => "xsd:string"),
			"movilidad" => array("name" => "movilidad", "type" => "xsd:string"),
			"prioridad" => array("name" => "prioridad", "type" => "xsd:string"),
			"condiciones_adicionales" => array("name" => "condiciones_adicionales", "type" => "xsd:string"),
			"observaciones_adicionales" => array("name" => "observaciones_adicionales", "type" => "xsd:string"),
			"estado" => array("name" => "estado", "type" => "xsd:string"),
			"texto_plano" => array("name" => "texto_plano", "type" => "xsd:string")
		));

		$this->nusoap_server->wsdl->addComplexType(
			'ArrayOfLlamado', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
			array(
				'ref' => 'SOAP-ENC:arrayType',
				'wsdl:arrayType' => 'tns:Llamado[]'
			)
			), 'tns:Llamado');

		$this->nusoap_server->wsdl->addComplexType(
			'Response_llamados', 'complexType', 'struct', 'all', '', array
			('data' => array('type' => 'tns:ArrayOfLlamado')
			)
		);
		//get_alumno
		$this->nusoap_server->register(
			"get_llamados", array(
			'api_key' => "xsd:string"
			), array("return" => "tns:Response_llamados"), $ns, $ns . "#get_llamados", "rpc", "encoded", "Servicio que retorna llamados"
		);
	}

	function index() {

		function valida_key($key) {
			$key_llamados_test = '4A614E645267556B58703273357638792F423F4428472B4B6250655368566D59';
			$key_llamados_prod = '423F4528482B4D6251655468576D5A7134743777217A24432646294A404E6352';
			$return = (object) array();
			if ($key_llamados_test == $key) {
				$return->estado = true;
				$return->environment = 'test';
				return $return;
			} elseif ($key_llamados_prod == $key) {
				$return->estado = true;
				$return->environment = 'prod';
				return $return;
			} else {
				$return->estado = false;
				return $return;
			}
		}

		function crea_texto_plano($llamado) {
			$texto_plano = "Se llama a concurso para cubrir ";
			if ($llamado->tipo_llamado === 'Cargo') {
				$texto_plano .= 'el cargo ' . empty($llamado->regimen) ? '' : $llamado->regimen . " en la institución ";
			} else {
				$texto_plano .= "$llamado->horas horas en la institución ";
			}
			$texto_plano .= empty($llamado->lugar_trabajo) ? '' : $llamado->lugar_trabajo . " ubicada en ";
			$texto_plano .= empty($llamado->direccion) ? '' : $llamado->direccion . " - ";
			$texto_plano .= empty($llamado->localidad) ? '' : $llamado->localidad . " - ";
			$texto_plano .= empty($llamado->departamento) ? '' : $llamado->departamento . ". Ofrece: ";
			$texto_plano .= empty($llamado->fecha_llamado_1) ? '' : "1° llamado el " . (new DateTime($llamado->fecha_llamado_1))->format('d/m/Y') . " a las " . (new DateTime($llamado->fecha_llamado_1))->format('H:i') . "hs. ";
			$texto_plano .= empty($llamado->fecha_llamado_2) ? '' : "2° llamado el " . (new DateTime($llamado->fecha_llamado_2))->format('d/m/Y') . " a las " . (new DateTime($llamado->fecha_llamado_2))->format('H:i') . "hs. ";
			$texto_plano .= empty($llamado->fecha_llamado_3) ? '' : "3° llamado el " . (new DateTime($llamado->fecha_llamado_3))->format('d/m/Y') . " a las " . (new DateTime($llamado->fecha_llamado_3))->format('H:i') . "hs. ";
			$texto_plano .= empty($llamado->fecha_llamado_4) ? '' : "4° llamado el " . (new DateTime($llamado->fecha_llamado_4))->format('d/m/Y') . " a las " . (new DateTime($llamado->fecha_llamado_4))->format('H:i') . "hs. ";
			$texto_plano .= empty($llamado->materia) ? '' : "Para $llamado->materia, ";
			$texto_plano .= empty(trim($llamado->division)) ? '' : "$llamado->division, ";
			$texto_plano .= empty($llamado->turno) ? '' : "Turno: $llamado->turno, ";
			$texto_plano .= empty($llamado->horario) ? '' : "Horario: $llamado->horario, ";
			if (!empty($llamado->articulo)) {
				if ($llamado->articulo !== 'Cargo vacante') {
					$texto_plano .= "Art: $llamado->articulo. ";
				} else {
					$texto_plano .= "Cargo vacante. ";
				}
			}
			if (!empty($llamado->fin_estimado)) {
				if ($llamado->fin_estimado !== 'A término') {
					$texto_plano .= "Fin estimado: $llamado->fin_estimado. ";
				} else {
					$texto_plano .= "A término. ";
				}
			}
			$texto_plano .= empty($llamado->presentarse_en) ? '' : "A presentarse en: $llamado->presentarse_en. ";
			$texto_plano .= empty($llamado->zona) ? '' : "Zona: $llamado->zona. ";
			$texto_plano .= empty($llamado->movilidad) ? '' : "Movilidad: $llamado->movilidad. ";
			$texto_plano .= empty($llamado->prioridad) ? '' : "Prioridad: $llamado->prioridad. ";
			$texto_plano .= empty($llamado->condiciones_adicionales) ? '' : "Condiciones adicionales: $llamado->condiciones_adicionales. ";
			$texto_plano .= empty($llamado->observaciones_adicionales) ? '' : "Observaciones adicionales: $llamado->observaciones_adicionales. ";
			return $texto_plano;
		}

		function parseLlamados($llamados) {
			$datos_llamados = array();
			if (!empty($llamados)) {
				foreach ($llamados as $llamado) {
					$llamado_parse = array();
					$llamado_parse['fecha_carga'] = $llamado->fecha_carga;
					$llamado_parse['tipo_llamado'] = $llamado->tipo_llamado;
					$llamado_parse['condicion_cargo'] = $llamado->condicion_cargo;
					$llamado_parse['regimen'] = $llamado->regimen;
					$llamado_parse['horas'] = $llamado->horas;
					$llamado_parse['lugar_trabajo'] = $llamado->lugar_trabajo;
					$llamado_parse['direccion'] = $llamado->direccion;
					$llamado_parse['localidad'] = $llamado->localidad;
					$llamado_parse['departamento'] = $llamado->departamento;
					$llamado_parse['regional'] = $llamado->regional;
					$llamado_parse['zona'] = $llamado->zona;
					$llamado_parse['fecha_llamado_1'] = $llamado->fecha_llamado_1;
					$llamado_parse['fecha_llamado_2'] = $llamado->fecha_llamado_2;
					$llamado_parse['fecha_llamado_3'] = $llamado->fecha_llamado_3;
					$llamado_parse['fecha_llamado_4'] = $llamado->fecha_llamado_4;
					$llamado_parse['articulo'] = $llamado->articulo;
					$llamado_parse['fin_estimado'] = $llamado->fin_estimado;
					$llamado_parse['division'] = $llamado->division;
					$llamado_parse['materia'] = $llamado->materia;
					$llamado_parse['turno'] = $llamado->turno;
					$llamado_parse['horario'] = $llamado->horario;
					$llamado_parse['presentarse_en'] = $llamado->presentarse_en;
					$llamado_parse['movilidad'] = $llamado->movilidad;
					$llamado_parse['prioridad'] = $llamado->prioridad;
					$llamado_parse['condiciones_adicionales'] = $llamado->condiciones_adicionales;
					$llamado_parse['observaciones_adicionales'] = $llamado->observaciones_adicionales;
					$llamado_parse['estado'] = $llamado->estado;
					$llamado_parse['texto_plano'] = crea_texto_plano($llamado);
					array_push($datos_llamados, $llamado_parse);
				}
			}
			return $datos_llamados;
		}

		function get_llamados($api_key) {
			$api_key_valid = valida_key($api_key);
			if ($api_key_valid->estado) {
				$ci = & get_instance();
				$ci->load->model('llamados/llamados_model');
				$return_llamados = $ci->llamados_model->get_llamados($api_key_valid->environment);
				$llamados_parsed = parseLlamados($return_llamados);
				$result = new Response();
				if (!empty($return_llamados)) {
					$result->data = $llamados_parsed;
				} else {
					$result->data = $llamados_parsed;
				}
				return $result;
			} else {
				$result->data = NULL;
			}
		}
		$this->nusoap_server->service(file_get_contents("php://input"));
	}
}