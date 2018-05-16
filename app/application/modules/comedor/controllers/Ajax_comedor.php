<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_comedor extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->roles_permitidos = array_diff(explode(',', ROLES), array(ROL_PORTAL, ROL_ASISTENCIA_DIVISION, ROL_DOCENTE_CURSADA)); //@TODO ver permisos
		if (ENVIRONMENT === 'production') {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
	}

	public function get_escuelas() {
		if (!in_array($this->rol->codigo, $this->roles_permitidos)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$nombre_escuela = $this->input->get('nombre');
		$numero_escuela = $this->input->get('numero');
		$mes_desde = $this->input->get('mes_desde');
		$this->load->model('escuela_model');

		if (!empty($nombre_escuela)) {
			$escuelas = $this->db->query(
					"SELECT escuela.id,escuela.numero,
CONCAT(escuela.nombre,'' ,CASE WHEN (escuela.anexo = 0) THEN '' ELSE CONCAT('/',escuela.anexo) END) AS escuela,
CASE WHEN (comedor_presupuesto.id IS NULL) THEN 'Si' ELSE 'No' END permitido
FROM `escuela`
LEFT JOIN `comedor_presupuesto` ON comedor_presupuesto.escuela_id = escuela.id
AND comedor_presupuesto.mes = ?
WHERE `escuela`.`nombre` LIKE ? ESCAPE'!'
GROUP BY escuela.id", array($mes_desde, '%' . $nombre_escuela . '%'))->result();
		}
		if (!empty($numero_escuela)) {
			$escuelas = $this->db->query(
					"SELECT escuela.id,escuela.numero,
CONCAT(escuela.nombre,'' ,CASE WHEN (escuela.anexo = 0) THEN '' ELSE CONCAT('/',escuela.anexo) END) AS escuela,
CASE WHEN (comedor_presupuesto.id IS NULL) THEN 'Si' ELSE 'No' END permitido
FROM `escuela`
LEFT JOIN `comedor_presupuesto` ON comedor_presupuesto.escuela_id = escuela.id
AND comedor_presupuesto.mes = ?
WHERE `escuela`.`numero` = ?
GROUP BY escuela.id", array($mes_desde, $numero_escuela)
				)->result();
		}
		if (!empty($escuelas)) {
			echo json_encode(array('status' => 'success', 'escuelas' => $escuelas));
			return;
		}
		echo json_encode(array('status' => 'error'));
	}
}