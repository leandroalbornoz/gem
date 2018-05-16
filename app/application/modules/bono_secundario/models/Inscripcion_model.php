<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inscripcion_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'inscripcion';
		$this->msg_name = 'inscripcion';
		$this->id_name = 'id';
		$this->columnas = array('id', 'persona_id', 'escuela_id', 'fecha_cierre', 'fecha_recepcion', 'observaciones_recepcion', 'mensaje', 'ultima_auditoria');
		$this->fields = array(
			'escuela' => array('label' => 'Escuela en la que presenta la documentación', 'input_type' => 'combo', 'id_name' => 'escuela_id'),
			'profesor' => array('label' => 'Profesor/MEP', 'input_type' => 'combo', 'id_name' => 'profesor', 'array' => array('0' => 'No', '1' => 'Si')),
			'bibliotecario' => array('label' => 'Bibliotecario', 'input_type' => 'combo', 'id_name' => 'bibliotecario', 'array' => array('0' => 'No', '1' => 'Si')),
			'secretario' => array('label' => 'Secretario', 'input_type' => 'combo', 'id_name' => 'secretario', 'array' => array('0' => 'No', '1' => 'Si')),
			'preceptor' => array('label' => 'Preceptor', 'input_type' => 'combo', 'id_name' => 'preceptor', 'array' => array('0' => 'No', '1' => 'Si')),
			'atp' => array('label' => 'Ayudante Trabajos Prácticos', 'input_type' => 'combo', 'id_name' => 'atp', 'array' => array('0' => 'No', '1' => 'Si')),
			'cct' => array('label' => 'CCT', 'input_type' => 'combo', 'id_name' => 'cct', 'array' => array('0' => 'No', '1' => 'Si')),
		);
		$this->requeridos = array('');
		$this->default_join = array(
			array('escuela', 'escuela.id = inscripcion.escuela_id', 'left', array('concat(escuela.numero, " - ", escuela.nombre) as escuela')),
			array('persona', 'persona.id = inscripcion.persona_id', 'left', array('persona.cuil'))
		);
	}

	function get_antiguedad($persona_id) {
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$DB1->select('descripcion, ee.entidad as institucion, fecha_desde, fecha_hasta, estado')
			->from('persona_antiguedad')
			->join('antiguedad_tipo', 'persona_antiguedad.antiguedad_tipo_id = antiguedad_tipo.id')
			->join('entidad_emisora ee', 'persona_antiguedad.entidad_emisora_id = ee.id')
			->where('persona_id', $persona_id);
		$query = $DB1->get();

		return $query->result();
	}

	function get_antecedente($persona_id) {
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$DB1->select('antecedente, institucion, numero_resolucion,  fecha_emision, duracion, tipo_duracion, modalidad.descripcion as modalidad, estado')
			->from('persona_antecedente')
			->join('modalidad', 'modalidad.id = persona_antecedente.modalidad_id', 'left')
			->where('persona_id', $persona_id);
		$query = $DB1->get();

		return $query->result();
	}

	function get_estados($persona_id) {
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$persona['titulo'] = $DB1->select('COUNT(1) total, SUM(CASE WHEN estado = 0 THEN 0 ELSE 1 END) validados')
				->from('persona_titulo')
				->where('persona_id', $persona_id)
				->where('borrado', '0')
				->get()->row();
		$persona['antiguedad'] = $DB1->select('COUNT(1) total, SUM(CASE WHEN estado = 0 THEN 0 ELSE 1 END) validados')
				->from('persona_antiguedad')
				->where('persona_id', $persona_id)
				->get()->row();
		$persona['antecedente'] = $DB1->select('COUNT(1) total, SUM(CASE WHEN estado = 0 THEN 0 ELSE 1 END) validados')
				->from('persona_antecedente')
				->where('persona_id', $persona_id)
				->get()->row();

		if (($persona['titulo']->total == $persona['titulo']->validados) && ($persona['antecedente']->total == $persona['antecedente']->validados) && ($persona['antiguedad']->total == $persona['antiguedad']->validados)) {
			return 1; // TODO VALIDADO
		} elseif (($persona['titulo']->validados == '0') && ($persona['antiguedad']->validados == '0') && ($persona['antecedente']->validados) == '0') {
			return 0; // NADA VALIDADO
		} elseif (($persona['titulo']->total != $persona['titulo']->validados) || ($persona['antecedente']->total != $persona['antecedente']->validados) || ($persona['antiguedad']->total != $persona['antiguedad']->validados)) {
			return -1; // VALIDADO PARCIAL
		}
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		return TRUE;
	}
}
/* End of file Inscripcion_model.php */
/* Location: ./application/modules/bono/models/Inscripcion_model.php */