<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recepcion_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'inscripcion';
		$this->msg_name = 'Inscripcion';
		$this->id_name = 'id';
		$this->columnas = array('id', 'persona_id', 'fecha_cierre', 'fecha_recepcion', 'observaciones_recepcion', 'ultima_auditoria', 'fecha_reclamo');
		$this->fields = array(
			'cuil' => array('label' => 'Cuil', 'maxlength' => '13', 'readonly' => TRUE),
			'apellido' => array('label' => 'Apellido', 'maxlength' => '25', 'readonly' => TRUE),
			'nombre' => array('label' => 'Nombre', 'maxlength' => '25', 'readonly' => TRUE),
			'fecha_nacimiento' => array('label' => 'Fecha Nac.', 'type' => 'date', 'placeholder' => 'dd/mm/aaaa', 'readonly' => TRUE),
			'sexo' => array('label' => 'Sexo', 'input_type' => 'combo', 'id_name' => 'sexo'),
			'calle' => array('label' => 'Calle', 'maxlength' => '60', 'readonly' => TRUE),
			'calle_numero' => array('label' => 'Número', 'maxlength' => '5', 'readonly' => TRUE),
			'piso' => array('label' => 'Piso', 'maxlength' => '2', 'readonly' => TRUE),
			'departamento' => array('label' => 'Dpto', 'maxlength' => '5', 'readonly' => TRUE),
			'telefono_fijo' => array('label' => 'Teléfono', 'maxlength' => '20', 'readonly' => TRUE),
			'telefono_movil' => array('label' => 'Teléfono alternativo', 'maxlength' => '20', 'readonly' => TRUE),
			'localidad' => array('label' => 'Localidad', 'id_name' => 'localidad_id', 'readonly' => TRUE),
			'codigo_postal' => array('label' => 'Código Postal', 'maxlength' => '8', 'readonly' => TRUE),
			'email' => array('label' => 'Email', 'maxlength' => '100', 'tabindex' => '-1', 'readonly' => TRUE),
			'documento' => array('label' => 'N° Documento', 'maxlength' => '8', 'readonly' => TRUE),
			'documento_tipo' => array('label' => 'Tipo de documento', 'input_type' => 'combo', 'id_name' => 'documento_tipo_id', 'required' => TRUE, 'readonly' => TRUE),
			'observaciones_recepcion' => array('label' => 'Observaciones', 'form_type' => 'textarea', 'rows' => '3')
		);
		$this->requeridos = array('');
		$this->unicos = array('');
	}

	function get_precepcion($escuela_id) {
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$DB1->select('COUNT(bono_secundario.inscripcion.escuela_id) AS precepcion')
			->from('bono_secundario.inscripcion')
			->where("inscripcion.fecha_recepcion IS NULL AND inscripcion.fecha_cierre IS NOT NULL AND inscripcion.escuela_id = $escuela_id AND inscripcion.fecha_reclamo is NULL");
		$query = $DB1->get();

		return $query->row(0)->precepcion;
	}

	function get_recibidos($escuela_id) {
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$DB1->select('COUNT(bono_secundario.inscripcion.escuela_id) AS precepcion')
			->from('bono_secundario.inscripcion')
			->where("inscripcion.fecha_recepcion IS NOT NULL AND inscripcion.escuela_id = $escuela_id AND inscripcion.fecha_reclamo IS NULL");
		$query = $DB1->get();

		return $query->row(0)->precepcion;
	}

	function get_reclamos($escuela_id) {
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$DB1->select('COUNT(bono_secundario.inscripcion.escuela_id) AS reclamo')
			->from('bono_secundario.inscripcion')
			->where("inscripcion.fecha_reclamo IS NOT NULL AND inscripcion.escuela_id = $escuela_id");
		$query = $DB1->get();
		return $query->row(0)->reclamo;
	}
}