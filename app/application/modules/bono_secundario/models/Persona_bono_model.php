<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Persona_bono_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'persona';
		$this->msg_name = 'Persona';
		$this->id_name = 'id';
		$this->columnas = array('id', 'cuil', 'apellido', 'nombre', 'fecha_nacimiento', 'sexo_id', 'calle', 'calle_numero', 'piso', 'departamento', 'telefono_fijo', 'telefono_movil', 'localidad_id', 'codigo_postal', 'email', 'documento', 'documento_tipo_id', 'gem_id', 'usuario_id');
		$this->fields = array(
			'cuil' => array('label' => 'Cuil', 'maxlength' => '13', 'readonly' => TRUE),
			'apellido' => array('label' => 'Apellido', 'maxlength' => '25'),
			'nombre' => array('label' => 'Nombre', 'maxlength' => '25'),
			'fecha_nacimiento' => array('label' => 'Fecha de Nacimiento', 'type' => 'date', 'placeholder' => 'dd/mm/aaaa'),
			'sexo' => array('label' => 'Sexo', 'input_type' => 'combo', 'id_name' => 'sexo_id', 'readonly' => TRUE),
			'calle' => array('label' => 'Calle', 'maxlength' => '60'),
			'calle_numero' => array('label' => 'Número', 'maxlength' => '5'),
			'piso' => array('label' => 'Piso', 'maxlength' => '2'),
			'departamento' => array('label' => 'Dpto', 'maxlength' => '5'),
			'telefono_fijo' => array('label' => 'Teléfono', 'maxlength' => '20'),
			'telefono_movil' => array('label' => 'Teléfono alternativo', 'maxlength' => '20'),
			'localidad' => array('label' => 'Localidad', 'input_type' => 'combo', 'id_name' => 'localidad_id'),
			'codigo_postal' => array('label' => 'Código Postal', 'maxlength' => '8'),
			'email' => array('label' => 'Email', 'maxlength' => '100', 'readonly' => TRUE, 'tabindex' => '-1'),
			'documento' => array('label' => 'N° Documento', 'maxlength' => '8', 'readonly' => TRUE),
			'documento_tipo' => array('label' => 'Tipo de documento', 'input_type' => 'combo', 'id_name' => 'documento_tipo_id', 'required' => TRUE, 'readonly' => TRUE)
		);
		$this->requeridos = array('cuil');
		$this->unicos = array('cuil');
		$this->default_join = array(
			array('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id', 'left', array('documento_tipo.descripcion_corta as documento_tipo')),
			array('localidad', 'localidad.id = persona.localidad_id', 'left', array("CONCAT(d_localidad.descripcion, ' - ', localidad.descripcion) as localidad")),
			array('departamento d_localidad', 'd_localidad.id = localidad.departamento_id', 'left'),
			array('sexo', 'sexo.id = persona.sexo_id', 'left', array('sexo.descripcion as sexo'))
		);
	}

	function get_persona($persona_id) {
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$DB1->select('persona.id, persona.gem_id, persona.cuil, persona.apellido, persona.nombre, persona.fecha_nacimiento, persona.sexo_id, persona.calle, persona.calle_numero, persona.piso, persona.departamento, persona.telefono_fijo, persona.telefono_movil, persona.codigo_postal, persona.email, persona.documento, persona.usuario_id, CONCAT(departamento.descripcion, " - ", localidad.descripcion) as localidad, CONCAT(escuela.numero, " - ", escuela.nombre) as escuela, documento_tipo.descripcion_corta as documento_tipo, sexo.descripcion as sexo, persona.localidad_id, sexo.descripcion')
			->from('persona')
			->join('inscripcion', 'persona.id = inscripcion.persona_id', 'left')
			->join('escuela', 'escuela.id = inscripcion.escuela_id', 'left')
			->join('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id', 'left')
			->join('localidad', 'localidad.id = persona.localidad_id', 'left')
			->join('departamento', 'departamento.id = localidad.departamento_id', 'left')
			->join('sexo', 'sexo.id = persona.sexo_id', 'left')
			->where('persona_id', $persona_id);
		$query = $DB1->get();
		return $query->row();
	}

	public function get_indices() {
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$indices = array();
		$indices['Personas inscriptas del total de registradas'] = $DB1->select('COUNT(1) total, COALESCE(SUM(CASE WHEN inscripcion.fecha_cierre IS NULL THEN 0 ELSE 1 END) +1, "0") cantidad')
				->from('persona')
				->join('inscripcion', 'persona.id = inscripcion.persona_id', 'left')
				->get()->row();
		$indices['Personas inscriptas del total de registradas']->link = 'juntas/alertas/listar_inscriptos';
		$indices['Personas auditadas'] = $DB1->select('COALESCE(SUM(CASE WHEN inscripcion.fecha_cierre IS NULL THEN 0 ELSE 1 END)+1, "0") total, COALESCE(SUM(CASE WHEN inscripcion.fecha_recepcion IS NULL THEN 0 ELSE 1 END), "0") cantidad')
				->from('persona')
				->join('inscripcion', 'persona.id = inscripcion.persona_id')
				->get()->row();
		$indices['Personas auditadas']->link = 'juntas/alertas/listar_auditadas';
		$indices['Vacantes disponibles'] = $DB1->select('SUM(escuela.vacantes) total, SUM(escuela.vacantes_disponibles) cantidad')
				->from('escuela')
				->get()->row();
		$indices['Vacantes disponibles']->link = 'juntas/alertas/listar_vacantes';
		return $indices;
	}

	public function get_alertas_bono() {
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$alertas = $DB1->query("SELECT 'Personas Registradas' indice, count(DISTINCT p.id) as 'cantidad' FROM `persona` `p` 
UNION
SELECT 'Antigüedades Registradas', COUNT(DISTINCT pg.id) as 'cantidad' FROM `persona_antiguedad` `pg`
UNION
SELECT 'Antecedentes Registrados', COUNT(DISTINCT pa.id) as 'cantidad' FROM `persona_antecedente` `pa`
UNION
SELECT 'Títulos Registrados', COUNT(DISTINCT pt.id) as 'cantidad' FROM `persona_titulo` `pt`
UNION
SELECT 'Inscripciones Cerradas', COUNT(DISTINCT i.id) as 'cantidad' FROM `inscripcion` `i`
;")->result();
		return $alertas;
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
/* End of file Persona_bono_model.php */
/* Location: ./application/modules/gem/bono_secundario/models/Persona_bono_model.php */