<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Supervision_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'supervision';
		$this->msg_name = 'Supervisión';
		$this->id_name = 'id';
		$this->columnas = array('id', 'responsable', 'linea_id', 'nivel_id', 'dependencia_id', 'nombre', 'email', 'blackberry', 'telefono', 'sede', 'orden', 'calle', 'calle_numero', 'barrio', 'localidad_id', 'codigo_postal');
		$this->fields = array(
			'responsable' => array('label' => 'Responsable', 'maxlength' => '150', 'required' => TRUE),
			'linea' => array('label' => 'Linea', 'input_type' => 'combo', 'id_name' => 'linea_id', 'required' => TRUE),
			'nivel' => array('label' => 'Nivel', 'input_type' => 'combo', 'id_name' => 'nivel_id', 'required' => TRUE),
			'calle' => array('label' => 'Calle', 'maxlength' => '45'),
			'calle_numero' => array('label' => 'Número', 'maxlength' => '20'),
			'barrio' => array('label' => 'Barrio/Sección', 'maxlength' => '45'),
			'localidad' => array('label' => 'Localidad', 'input_type' => 'combo', 'id_name' => 'localidad_id'),
			'codigo_postal' => array('label' => 'C.P.', 'maxlength' => '8'),
			'dependencia' => array('label' => 'Gestión', 'input_type' => 'combo', 'id_name' => 'dependencia_id', 'required' => TRUE),
			'nombre' => array('label' => 'Nombre', 'maxlength' => '150'),
			'email' => array('label' => 'Email', 'maxlength' => '100'),
			'blackberry' => array('label' => 'Blackberry', 'maxlength' => '100'),
			'telefono' => array('label' => 'Teléfono', 'maxlength' => '100'),
			'sede' => array('label' => 'Sede', 'maxlength' => '100'),
			'orden' => array('label' => 'Orden', 'type' => 'integer', 'maxlength' => '10')
		);
		$this->requeridos = array('responsable', 'linea_id', 'nivel_id', 'dependencia_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('nivel', 'nivel.id = supervision.nivel_id', 'left', array('nivel.descripcion as nivel', 'nivel.linea_id')),
			array('linea', 'linea.id = supervision.linea_id', 'left', array('linea.nombre as linea')),
			array('dependencia', 'dependencia.id = supervision.dependencia_id', 'left', array('dependencia.descripcion as dependencia')),
			array('localidad', 'localidad.id = supervision.localidad_id', 'left'),
			array('departamento', 'departamento.id = localidad.departamento_id', 'left', array('CONCAT(departamento.descripcion, \' - \', localidad.descripcion) as localidad')),
		);
	}

	public function get_by_nivel($nivel_id, $dependencia = 0) {
		$options['join'] = array(
			array('escuela', 'escuela.supervision_id = supervision.id', 'left', array('COUNT(DISTINCT escuela.numero) as escuelas')),
			array('regional','regional.id = escuela.regional_id ','left',array('regional.descripcion as regional')),
			array("(SELECT s.id as supervision_id, s.nombre,
    100*(1-(COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=2017 AND ad.fecha_hasta IS NULL THEN ad.alumno_id ELSE NULL END))/COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=2017 THEN ad.alumno_id ELSE NULL END)) as porcentaje_actualizacion_cl,
	COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=2017 THEN ad.alumno_id ELSE NULL END) matricula_2017,
	COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=2018 AND ad.fecha_hasta IS NULL THEN ad.alumno_id ELSE NULL END) matricula_2018,
	COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=2018 AND ad.fecha_hasta IS NULL THEN ad.alumno_id ELSE NULL END)-COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=2017 THEN ad.alumno_id ELSE NULL END) diferencia_año_2018_2017
	FROM escuela e
	LEFT JOIN division d ON d.escuela_id=e.id
	LEFT JOIN alumno_division ad ON ad.division_id = d.id
	LEFT JOIN supervision s ON s.id = e.supervision_id
	where COALESCE(ad.fecha_hasta,'2017-12-01')>='2017-12-01' and s.nivel_id = '" . $nivel_id . "'
	GROUP BY s.id
) ad ", " ad.supervision_id=supervision.id", 'left', array("ad.porcentaje_actualizacion_cl", "
	ad.matricula_2017", "ad.matricula_2018", "ad.diferencia_año_2018_2017"))
		);
		if ($dependencia) {
			$options['dependencia_id'] = $dependencia;
		}
		$options['nivel_id'] = $nivel_id;
		$options['group_by'] = 'supervision.id';
		$options['sort_by'] = 'supervision.orden';

		return $this->get($options);
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('supervision_id', $delete_id)->count_all_results('escuela') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a escuela.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Supervision_model.php */
/* Location: ./application/models/Supervision_model.php */