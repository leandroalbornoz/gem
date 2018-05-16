<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escuela_model extends MY_Model_DB {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'escuela';
		$this->msg_name = 'Escuela';
		$this->id_name = 'id';
		$this->columnas = array('id', 'numero', 'nombre', 'direccion', 'telefono', 'departamento', 'vacantes', 'vacantes_disponibles', 'gem_id');
		$this->fields = array(
			'numero' => array('label' => 'Numero', 'maxlength' => '4', 'required' => TRUE),
			'nombre' => array('label' => 'Nombre', 'maxlength' => '60', 'required' => TRUE),
			'vacantes' => array('label' => 'Vacantes', 'type' => 'integer', 'maxlength' => '10'),
			'vacantes_disponibles' => array('label' => 'Vacantes disponibles', 'type' => 'integer', 'maxlength' => '10')
		);
		$this->requeridos = array('numero', 'nombre');
		//$this->unicos = array();
	}

	function get_escuela_bono($escuela_id) {
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$DB1->select('*')
			->from('escuela')
			->where('id', $escuela_id);
		$query = $DB1->get();
		return $query->row();
	}

	function sumar_vacante($escuela_id) {
		if (empty($escuela_id)) {
			return false;
		} else {
			$DB1 = $this->load->database('bono_secundario', TRUE);
			return $DB1->set('vacantes_disponibles', 'vacantes_disponibles+1', FALSE)
					->where('id', $escuela_id)
					->update('escuela');
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
/* End of file Escuela_model.php */
/* Location: ./application/modules/bono_secundario/models/Escuela_model.php */