<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_ingreso extends MY_Controller {

	function __construct() {
		parent::__construct();
//		$this->roles_permitidos = array_diff(explode(',', ROLES)); //@TODO ver permisos
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
					GROUP BY escuela.id", array($mes_desde, $numero_escuela))->result();
		}
		if (!empty($escuelas)) {
			echo json_encode(array('status' => 'success', 'escuelas' => $escuelas));
			return;
		}
		echo json_encode(array('status' => 'error'));
	}

	public function actualiza_promedio() {

		$alumno_id = $this->input->get('alumno_id');
		$alumno_division_id = $this->input->get('alumno_division_id');
		$promedio = $this->input->get('promedio');

		$this->load->model('ingreso/ingreso_alumno_model');

		$ingreso_alumno = $this->ingreso_alumno_model->get(array('alumno_id' => $alumno_id));

		if (empty($ingreso_alumno)) {
			$trans_ok = TRUE;
			$trans_ok &= $this->ingreso_alumno_model->create(array(
				'alumno_id' => $alumno_id,
				'alumno_division_id' => $alumno_division_id,
				'hermano_ad_id' => $alumno_division_id,
				'promedio' => $promedio,
				), FAlSE);
		} else {
			$trans_ok = TRUE;
			$trans_ok &= $this->ingreso_alumno_model->update(array(
				'id' => $ingreso_alumno[0]->id,
				'promedio' => $promedio,
				), FAlSE);
		}

		if ($trans_ok) {
			$mensaje = $this->ingreso_alumno_model->get_msg();
			echo json_encode(array('status' => 'success', 'mensaje' => $mensaje));
			return;
		} else {
			$errors = 'Ocurrió un error al intentar actualizar.';
			if ($this->ingreso_alumno_model->get_error())
				$errors .= '<br>' . $this->ingreso_alumno_model->get_error();
			echo json_encode(array('status' => 'error', 'errors' => $errors));
			return;
		}
	}

	public function actualiza_abanderado() {
		$alumno_id = $this->input->get('alumno_id');
		$abanderado = $this->input->get('abanderado');
		$escuela_id = $this->input->get('escuela_id');
		$ciclo_lectivo = $this->input->get('ciclo_lectivo');

		$this->load->model('ingreso/ingreso_alumno_model');

		$ingreso_alumno = $this->ingreso_alumno_model->get(array('alumno_id' => $alumno_id));
		$cantidad_abaderados = $this->ingreso_alumno_model->get_cant_abanderados($escuela_id, $ciclo_lectivo);

		if ($abanderado === 'Si' && $cantidad_abaderados[0]->cant_abanderados > '6') {
//		if ($cantidad_abaderados[0]->cant_abanderados > '6') {
			echo json_encode(array('status' => 'error', 'errors' => 'Supera cantidad permitida de abanderados'));
			return;
		}

		if (empty($ingreso_alumno)) {
			$trans_ok = TRUE;
			$trans_ok &= $this->ingreso_alumno_model->create(array(
				'alumno_id' => $alumno_id,
				'abanderado' => $abanderado,
				'abanderado_escuela_id' => $escuela_id,
				), FAlSE);
		} else {
			$trans_ok = TRUE;
			$trans_ok &= $this->ingreso_alumno_model->update(array(
				'id' => $ingreso_alumno[0]->id,
				'abanderado' => $abanderado,
				'abanderado_escuela_id' => ($abanderado === 'Si') ? $escuela_id : '',
				), FAlSE);
		}
		
		$cantidad_abaderados = $this->ingreso_alumno_model->get_cant_abanderados($escuela_id, $ciclo_lectivo);
		
		if ($trans_ok) {
			$mensaje = $this->ingreso_alumno_model->get_msg();
			echo json_encode(array('status' => 'success', 'mensaje' => $mensaje, 'cant_abanderados' => $cantidad_abaderados[0]->cant_abanderados));
			return;
		} else {
			$errors = 'Ocurrió un error al intentar actualizar.';
			if ($this->ingreso_alumno_model->get_error())
				$errors .= '<br>' . $this->ingreso_alumno_model->get_error();
			echo json_encode(array('status' => 'error', 'errors' => $errors, 'cant_abanderados' => $cantidad_abaderados[0]->cant_abanderados));
			return;
		}
	}

	public function actualiza_participa() {
		$alumno_id = $this->input->get('alumno_id');
		$motivo = $this->input->get('motivo');

		$this->load->model('ingreso/ingreso_alumno_model');

		$ingreso_alumno = $this->ingreso_alumno_model->get(array('alumno_id' => $alumno_id));

		if ($motivo == 'Si') {
			if (empty($ingreso_alumno)) {
				$trans_ok = TRUE;
				$trans_ok &= $this->ingreso_alumno_model->create(array(
					'alumno_id' => $alumno_id,
					'participa' => 'Si',
					'motivo_no_participa' => '',
					), FAlSE);
			} else {
				$trans_ok = TRUE;
				$trans_ok &= $this->ingreso_alumno_model->update(array(
					'id' => $ingreso_alumno[0]->id,
					'participa' => 'Si',
					'motivo_no_participa' => '',
					), FAlSE);
			}
		} else {
			if (empty($ingreso_alumno)) {
				$trans_ok = TRUE;
				$trans_ok &= $this->ingreso_alumno_model->create(array(
					'alumno_id' => $alumno_id,
					'participa' => 'No',
					'motivo_no_participa' => $motivo,
					), FAlSE);
			} else {
				$trans_ok = TRUE;
				$trans_ok &= $this->ingreso_alumno_model->update(array(
					'id' => $ingreso_alumno[0]->id,
					'participa' => 'No',
					'motivo_no_participa' => $motivo,
					), FAlSE);
			}
		}

		if ($trans_ok) {
			$mensaje = $this->ingreso_alumno_model->get_msg();
			echo json_encode(array('status' => 'success', 'mensaje' => $mensaje));
			return;
		} else {
			$errors = 'Ocurrió un error al intentar actualizar.';
			if ($this->ingreso_alumno_model->get_error())
				$errors .= '<br>' . $this->ingreso_alumno_model->get_error();
			echo json_encode(array('status' => 'error', 'errors' => $errors));
			return;
		}
	}
}