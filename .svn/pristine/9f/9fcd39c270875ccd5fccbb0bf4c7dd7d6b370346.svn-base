<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Funcion_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'funcion';
		$this->msg_name = 'Función';
		$this->id_name = 'id';
		$this->columnas = array('id', 'descripcion', 'usa_tarea', 'usa_carga_horaria', 'usa_destino', 'usa_norma', 'horario_propio', 'planilla_modalidad_id');
		$this->fields = array(
			'descripcion' => array('label' => 'Descripción', 'maxlength' => '45', 'required' => TRUE),
			'usa_tarea' => array('label' => 'Solicitar tarea', 'input_type' => 'combo', 'id_name' => 'usa_tarea', 'array' => array('N' => 'No', 'S' => 'Si')),
			'usa_carga_horaria' => array('label' => 'Solicitar carga horaria', 'input_type' => 'combo', 'id_name' => 'usa_carga_horaria', 'array' => array('N' => 'No', 'S' => 'Si')),
			'usa_destino' => array('label' => 'Solicitar destino', 'input_type' => 'combo', 'id_name' => 'usa_destino', 'array' => array('N' => 'No', 'S' => 'Si')),
			'usa_norma' => array('label' => 'Solicitar norma', 'input_type' => 'combo', 'id_name' => 'usa_norma', 'array' => array('N' => 'No', 'S' => 'Si')),
			'horario_propio' => array('label' => 'Horario propio', 'input_type' => 'combo', 'id_name' => 'horario_propio', 'array' => array('N' => 'No', 'S' => 'Si')),
		);
		$this->requeridos = array('descripcion', 'usa_tarea', 'usa_carga_horaria', 'usa_destino', 'usa_norma', 'horario_propio');
		//$this->unicos = array();
		$this->default_join = array();
	}

	public function get($options = array()) {
		if (!isset($options['planilla_modalidad_id']) && !isset($options['id'])) {
			$options['planilla_modalidad_id'] = 1;
		}
		return parent::get($options);
	}

	public function get_fn_mostrar_campos() {
		$fn_mostrar_campos = "
			$('.campos-funcion').hide();
			var id_funcion = $('#funcion').val();

			switch (id_funcion) {";
		$funciones = $this->get();
		foreach ($funciones as $funcion) {
			$fn_mostrar_campos .= "
				case '$funcion->id': //$funcion->descripcion";
			if ($funcion->usa_tarea === 'S') {
				$fn_mostrar_campos .= "
					$('.campos-funcion #tarea').parent().show();";
			}
			if ($funcion->usa_carga_horaria === 'S') {
				$fn_mostrar_campos .= "
					$('.campos-funcion #carga_horaria').parent().show();";
			}
			if ($funcion->usa_destino === 'S') {
				$fn_mostrar_campos .= "
					$('.campos-funcion #destino').parent().show();
					$('.campos-funcion #tipo_destino').parent().show();";
			}
			if ($funcion->usa_norma === 'S') {
				$fn_mostrar_campos .= "
					$('.campos-funcion #norma').parent().show();";
			}
			$fn_mostrar_campos .= "
					break;";
		}
		$fn_mostrar_campos .= "
			}";
		return $fn_mostrar_campos;
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('funcion_id', $delete_id)->count_all_results('servicio_funcion') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a servicio de funcion.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Funcion_model.php */
/* Location: ./application/models/Funcion_model.php */