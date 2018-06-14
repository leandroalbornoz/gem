<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escuela_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'escuela';
		$this->msg_name = 'Escuela';
		$this->id_name = 'id';
		$this->columnas = array('id', 'numero', 'anexo', 'anexos', 'escuela_id', 'cue', 'subcue', 'nombre', 'calle', 'calle_numero', 'departamento', 'piso', 'barrio', 'manzana', 'casa', 'localidad_id', 'codigo_postal', 'nivel_id', 'secundaria_mixta', 'reparticion_id', 'unidad_organizativa', 'supervision_id', 'regional_id', 'delegacion_id', 'dependencia_id', 'zona_id', 'fecha_creacion', 'anio_resolucion', 'numero_resolucion', 'telefono', 'email', 'email2', 'fecha_cierre', 'anio_resolucion_cierre', 'numero_resolucion_cierre', 'regimen_lista_id');
		$this->fields = array(
			'numero' => array('label' => 'Número', 'maxlength' => '6', 'required' => TRUE),
			'anexo' => array('label' => 'Anexo', 'type' => 'integer', 'readonly' => TRUE),
			'cue' => array('label' => 'CUE', 'type' => 'integer', 'maxlength' => '7'),
			'subcue' => array('label' => 'SubCUE', 'maxlength' => '2', 'type' => 'numeric'),
			'nombre' => array('label' => 'Nombre', 'maxlength' => '100', 'required' => TRUE),
			'calle' => array('label' => 'Calle', 'maxlength' => '45'),
			'calle_numero' => array('label' => 'Número', 'maxlength' => '20'),
			'barrio' => array('label' => 'Barrio/Sección', 'maxlength' => '45'),
			'localidad' => array('label' => 'Localidad', 'input_type' => 'combo', 'id_name' => 'localidad_id'),
			'codigo_postal' => array('label' => 'C.P.', 'maxlength' => '8'),
			'linea' => array('label' => 'Dir. Linea', 'input_type' => 'combo', 'id_name' => 'linea_id', 'required' => TRUE),
			'nivel' => array('label' => 'Nivel', 'input_type' => 'combo', 'id_name' => 'nivel_id', 'required' => TRUE),
			'regimen_lista' => array('label' => 'Lista de Regímenes', 'input_type' => 'combo', 'id_name' => 'regimen_lista_id', 'required' => TRUE),
			'reparticion' => array('label' => 'Repartición', 'input_type' => 'combo'),
			'supervision' => array('label' => 'Supervisión', 'input_type' => 'combo', 'id_name' => 'supervision_id'),
			'regional' => array('label' => 'Regional', 'input_type' => 'combo', 'id_name' => 'regional_id', 'required' => TRUE),
			'delegacion' => array('label' => 'Delegación', 'input_type' => 'combo', 'id_name' => 'delegacion_id', 'required' => TRUE),
			'dependencia' => array('label' => 'Gestión', 'input_type' => 'combo', 'id_name' => 'dependencia_id'),
			'zona' => array('label' => 'Zona + Radio', 'input_type' => 'combo', 'id_name' => 'zona_id'),
			'fecha_creacion' => array('label' => 'Fecha Creación', 'type' => 'date'),
			'anio_resolucion' => array('label' => 'Año Resolución', 'type' => 'integer', 'maxlength' => '11'),
			'numero_resolucion' => array('label' => 'Número Resolución', 'maxlength' => '15'),
			'telefono' => array('label' => 'Teléfono', 'maxlength' => '45'),
			'email' => array('label' => 'Email', 'maxlength' => '45'),
			'email2' => array('label' => 'Email Alternativo', 'maxlength' => '45'),
			'fecha_cierre' => array('label' => 'Fecha Cierre', 'type' => 'date'),
			'anio_resolucion_cierre' => array('label' => 'Año Resolución Cierre', 'type' => 'integer', 'maxlength' => '11'),
			'numero_resolucion_cierre' => array('label' => 'Número Resolución Cierre', 'type' => 'integer', 'maxlength' => '11')
		);
		$this->requeridos = array('numero', 'anexo', 'nombre', 'nivel_id', 'regional_id', 'delegacion_id');
		//$this->unicos = array();
		$this->default_join = array(
			array('dependencia', 'dependencia.id = escuela.dependencia_id', 'left', array('dependencia.descripcion as dependencia')),
			array('localidad', 'localidad.id = escuela.localidad_id', 'left', array('localidad.departamento_id')),
			array('departamento', 'departamento.id = localidad.departamento_id', 'left', array('CONCAT(departamento.descripcion, \' - \', localidad.descripcion) as localidad', 'departamento.descripcion departamento')),
			array('nivel', 'nivel.id = escuela.nivel_id', 'left', array('nivel.descripcion as nivel', 'nivel.linea_id', 'nivel.formal')),
			array('regimen_lista', 'regimen_lista.id = escuela.regimen_lista_id', 'left', array('regimen_lista.descripcion as regimen_lista')),
			array('linea', 'linea.id = nivel.linea_id', 'left', array('linea.nombre as linea')),
			array('regional', 'regional.id = escuela.regional_id', 'left', array('regional.descripcion as regional')),
			array('delegacion', 'delegacion.id = escuela.delegacion_id', 'left', array('delegacion.descripcion as delegacion')),
			array('reparticion', 'reparticion.id = escuela.reparticion_id', 'left', array('CONCAT(jurisdiccion.codigo, \' \', reparticion.codigo, \' \', reparticion.descripcion) as reparticion', 'reparticion.codigo as reparticion_codigo')),
			array('jurisdiccion', 'jurisdiccion.id = reparticion.jurisdiccion_id', 'left', array('jurisdiccion.codigo as jurisdiccion_codigo')),
			array('supervision', 'supervision.id = escuela.supervision_id', 'left', array('supervision.nombre as supervision')),
			array('zona', 'zona.id = escuela.zona_id', 'left', array('zona.descripcion as zona', 'zona.valor as zona_valor'))
		);
	}

	public function get($options = array()) {
		$escuelas = parent::get($options);
		if (is_array($escuelas)) {
			foreach ($escuelas as $escuela) {
				$escuela->nombre_corto = $escuela->numero . ($escuela->anexo === '0' ? '' : "/$escuela->anexo");
				$escuela->nombre_largo = $escuela->numero . ($escuela->anexo === '0' ? '' : "/$escuela->anexo") . ' ' . $escuela->nombre;
			}
		} elseif (is_object($escuelas)) {
			$escuelas->nombre_corto = $escuelas->numero . ($escuelas->anexo === '0' ? '' : "/$escuelas->anexo");
			$escuelas->nombre_largo = $escuelas->numero . ($escuelas->anexo === '0' ? '' : "/$escuelas->anexo") . ' ' . $escuelas->nombre;
		}
		return $escuelas;
	}

	public function get_indices($escuela_id) {
		$indices = array();
		$indices['Divisiones con carreras'] = $this->db->select('COUNT(1) total, SUM(CASE WHEN carrera_id IS NULL THEN 0 ELSE 1 END) cantidad')
				->from('division')
				->where('escuela_id', $escuela_id)
				->where('fecha_baja is NULL')
				->get()->row();
		$indices['Cargos con horarios'] = $this->db->select('COUNT(DISTINCT cargo.id) total, COUNT(DISTINCT COALESCE(horario.cargo_id, cargo_horario.cargo_id)) cantidad')
				->from('cargo')
				->join('regimen', 'regimen.id = cargo.regimen_id AND regimen.planilla_modalidad_id=1', '')
				->join('cargo_horario', 'cargo.id=cargo_horario.cargo_id', 'left')
				->join('horario', 'cargo.id=horario.cargo_id', 'left')
				->where('cargo.escuela_id', $escuela_id)
				->where('cargo.fecha_hasta IS NULL')
				->get()->row();
		$this->load->model('alertas_model');
		$indices['alertas'] = $this->alertas_model->get_alertas_escuela($escuela_id);
		return $indices;
	}

	public function get_autoridad($id) {

		return $this->db->select()
				->from('escuela_autoridad')
				->join('autoridad_tipo', 'autoridad_tipo.id = escuela_autoridad.autoridad_tipo_id')
				->join('persona', 'persona.id = escuela_autoridad.persona_id')
				->where('escuela_autoridad.escuela_id', $id)
				->order_by('autoridad_tipo.id asc')
				->limit(1)
				->get()
				->row();
	}

	public function get_by_nivel($nivel_id, $dependencia_id = 0, $supervision_id = 0, $regional_id = 0) {
		$columnas = array('nivel_id', 'dependencia_id', 'supervision_id', 'regional_id');
		$where = array();
		$params = array();
		foreach ($columnas as $columna) {
			if (!empty($$columna)) {
				$where[] = "e.$columna = ?";
			}
		}
		for ($i = 1; $i <= 3; $i++) {
			foreach ($columnas as $columna) {
				if (!empty($$columna)) {
					$params[] = $$columna;
				}
			}
		}
		$query = $this->db->query("
			SELECT e.id, e.numero, e.anexo, e.cue, e.subcue, e.nombre, CONCAT(e.numero, CASE WHEN e.anexo = 0 THEN '' ELSE CONCAT('/', e.anexo) END, ' ', e.nombre) as nombre_corto, e.calle, e.calle_numero, e.departamento, e.piso, e.barrio, e.manzana, e.casa, e.localidad_id, e.codigo_postal, e.nivel_id, e.secundaria_mixta, e.reparticion_id, e.unidad_organizativa, e.supervision_id, e.regional_id, e.delegacion_id, e.dependencia_id, e.zona_id, e.fecha_creacion, e.anio_resolucion, e.numero_resolucion, e.telefono, ad.porcentaje_actualizacion_cl, ad.matricula_2017, ad.matricula_2018, ad.diferencia_año_2018_2017, c.cargos, COUNT(DISTINCT escuela_carrera.carrera_id) as carreras
FROM escuela e
LEFT JOIN (SELECT e.id as escuela_id, 
    100*(1-(COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=2017 AND ad.fecha_hasta IS NULL THEN ad.alumno_id ELSE NULL END))/COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=2017 THEN ad.alumno_id ELSE NULL END)) as porcentaje_actualizacion_cl, 
    COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=2017 THEN ad.alumno_id ELSE NULL END) matricula_2017, 
    COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=2018 AND ad.fecha_hasta IS NULL THEN ad.alumno_id ELSE NULL END) matricula_2018, 
    COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=2018 AND ad.fecha_hasta IS NULL THEN ad.alumno_id ELSE NULL END)-COUNT(DISTINCT CASE WHEN ad.ciclo_lectivo=2017 THEN ad.alumno_id ELSE NULL END) diferencia_año_2018_2017 
    FROM alumno_division ad 
    JOIN division d ON ad.division_id=d.id 
    JOIN escuela e ON d.escuela_id=e.id 
    WHERE " . implode(' AND ', $where) . " AND COALESCE(ad.fecha_hasta,'2017-12-01') >= '2017-12-01' 
    GROUP BY e.id
) ad  ON ad.escuela_id=e.id
LEFT JOIN (SELECT c.escuela_id, COUNT(DISTINCT c.id) cargos
	FROM cargo c
	JOIN escuela e ON c.escuela_id = e.id
	JOIN regimen r ON c.regimen_id = r.id
	WHERE " . implode(' AND ', $where) . " AND c.fecha_hasta IS NULL AND r.planilla_modalidad_id = 1 
	GROUP BY c.escuela_id
) c ON c.escuela_id = e.id
LEFT JOIN escuela_carrera ON escuela_carrera.escuela_id = e.id
WHERE " . implode(' AND ', $where) . "
GROUP BY e.id
ORDER BY e.numero, e.anexo 
", $params);
		return $query->result();
	}

	public function get_anexos($escuela_id) {
		return $this->db->select('escuela.id as escuela_id, escuela.numero, escuela.anexo, escuela.cue, escuela.subcue, escuela.nombre, escuela.nivel_id, escuela.reparticion_id, escuela.supervision_id, escuela.regional_id, escuela.dependencia_id, dependencia.descripcion as dependencia, nivel.descripcion as nivel, regional.descripcion as regional, CONCAT(jurisdiccion.codigo,\'/\',reparticion.codigo) as jurirepa, supervision.nombre as supervision, zona.descripcion as zona')
				->from('escuela')
				->join('dependencia', 'dependencia.id = escuela.dependencia_id', 'left')
				->join('nivel', 'nivel.id = escuela.nivel_id', 'left')
				->join('regional', 'regional.id = escuela.regional_id', 'left')
				->join('reparticion', 'reparticion.id = escuela.reparticion_id', 'left')
				->join('jurisdiccion', 'jurisdiccion.id = reparticion.jurisdiccion_id', 'left')
				->join('supervision', 'supervision.id = escuela.supervision_id', 'left')
				->join('zona', 'zona.id = escuela.zona_id', 'left')
				->where('escuela.escuela_id', $escuela_id)
				->where('escuela.escuela_activa', 'Si')
				->order_by('escuela.anexo', 'ASC')
				->get()->result();
	}

	/**
	 * _can_delete: Devuelve true si puede eliminarse el registro.
	 *
	 * @param int $delete_id
	 * @return bool
	 */
	protected function _can_delete($delete_id) {
		if ($this->db->where('escuela_id', $delete_id)->count_all_results('caracteristica_escuela') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a caracteristica de escuela.');
			return FALSE;
		}
		if ($this->db->where('escuela_id', $delete_id)->count_all_results('cargo') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a cargo.');
			return FALSE;
		}
		if ($this->db->where('escuela_id', $delete_id)->count_all_results('division') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a division.');
			return FALSE;
		}
		if ($this->db->where('escuela_id', $delete_id)->count_all_results('escuela_carrera') > 0) {
			$this->_set_error('No se ha podido eliminar el registro de ' . $this->msg_name . '. Verifique que no esté asociado a escuela de carrera.');
			return FALSE;
		}
		return TRUE;
	}
}
/* End of file Escuela_model.php */
/* Location: ./application/models/Escuela_model.php */