<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Acuerdo_zona_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function buscar_persona($cuil) {
		if (empty($cuil)) {
			return FALSE;
		}
		$persona = $this->db->query("SELECT az.persona, az.nombre
FROM acuerdo_zona az JOIN acuerdo_zona_escuela aze ON az.cesc=aze.cesc
WHERE az.persona = ? AND aze.judicializado='No'
GROUP BY az.persona", array(substr(str_replace('-', '', $cuil), 0, 10)))->row();
		return $persona;
	}
}
/* End of file Acuerdo_zona_model.php */
/* Location: ./application/modules/acuerdo_zona/models/Acuerdo_zona_model.php */