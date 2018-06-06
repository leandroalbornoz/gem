<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Llamado_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'llamado';
		$this->msg_name = 'Llamado';
		$this->id_name = 'id';
		$this->columnas = array('id', 'tipo_llamado', 'servicio_novedad_id', 'cargo_id', 'fecha_carga', 'fecha_publicacion', 'regimen', 'horas', 'lugar_trabajo', 'direccion', 'localidad', 'departamento', 'regional', 'fecha_llamado_1', 'fecha_llamado_2', 'fecha_llamado_3', 'fecha_llamado_4', 'articulo', 'fin_estimado', 'division', 'materia', 'condicion_cargo', 'turno', 'horario', 'presentarse_en', 'zona', 'movilidad', 'prioridad', 'condiciones_adicionales', 'observaciones_adicionales', 'estado', 'motivo_no_publica');
		$this->fields = array(
			'tipo_llamado' => array('label' => 'Tipo Llamado', 'maxlength' => '50', 'readonly' => TRUE),
			'fecha_carga' => array('label' => 'Fecha de Carga', 'type' => 'datetime', 'readonly' => TRUE, 'disabled' => TRUE, 'value' => date('d/m/Y H:i:s')),
			'fecha_publicacion' => array('label' => 'Fecha Publicación', 'type' => 'datetime', 'readonly' => TRUE, 'disabled' => TRUE),
			'regimen' => array('label' => 'Régimen', 'maxlength' => '200'),
			'horas' => array('label' => 'Horas', 'maxlength' => '4'),
			'articulo' => array('label' => 'Artículo', 'maxlength' => '20'),
			'fin_estimado' => array('label' => 'Fin Estimado', 'maxlength' => '30'),
			'division' => array('label' => 'División', 'maxlength' => '50'),
			'materia' => array('label' => 'Materia', 'maxlength' => '50'),
			'condicion_cargo' => array('label' => 'Condición cargo', 'maxlength' => '50'),
			'turno' => array('label' => 'Turno', 'maxlength' => '20'),
			'lugar_trabajo' => array('label' => 'Lugar Trabajo', 'maxlength' => '200'),
			'direccion' => array('label' => 'Dirección', 'maxlength' => '200'),
			'localidad' => array('label' => 'Localidad', 'maxlength' => '50'),
			'departamento' => array('label' => 'Departamento', 'maxlength' => '50'),
			'regional' => array('label' => 'Regional', 'maxlength' => '50'),
			'fecha_llamado_1' => array('label' => 'Fecha 1° Llamado', 'type' => 'datetime', 'data-llamado' => '1'),
			'fecha_llamado_2' => array('label' => 'Fecha 2° Llamado', 'type' => 'datetime', 'data-llamado' => '2'),
			'fecha_llamado_3' => array('label' => 'Fecha 3° Llamado', 'type' => 'datetime', 'data-llamado' => '3'),
			'fecha_llamado_4' => array('label' => 'Fecha 4° Llamado', 'type' => 'datetime', 'data-llamado' => '4'),
			'horario' => array('label' => 'Horario', 'maxlength' => '500'),
			'presentarse_en' => array('label' => 'Presentarse a llamado en', 'maxlength' => '200'),
			'zona' => array('label' => 'Zona', 'maxlength' => '30'),
			'movilidad' => array('label' => 'Movilidad', 'maxlength' => '200'),
			'prioridad' => array('label' => 'Prioridad','form_type' => 'textarea', 'rows' => '2' ,'maxlength' => '200'),
			'condiciones_adicionales' => array('label' => 'Condiciones Adicionales','form_type' => 'textarea', 'rows' => '2','maxlength' => '300'),
			'observaciones_adicionales' => array('label' => 'Observaciones Adicionales','form_type' => 'textarea', 'rows' => '2','maxlength' => '300'),
			'estado' => array('label' => 'Estado', 'id_name' => 'estado', 'readonly' => TRUE),
			'motivo_no_publica' => array('label' => 'Motivo No Publica', 'maxlength' => '200', 'input_type' => 'combo', 'id_name' => 'motivo_no_publica', 'array' => array('' => '', 'Continúa Reemplazo' => 'Continúa Reemplazo', 'No Corresponde' => 'No Corresponde'), 'required' => TRUE),
			'texto_plano' => array('label' => 'Texto Llamado', 'form_type' => 'textarea', 'id_name' => 'texto_plano', 'readonly' => TRUE)
		);
		$this->requeridos = array('fecha_carga');
		//$this->unicos = array();
		$this->default_join = array(
			array('cargo', 'llamado.cargo_id=cargo.id', '', array('cargo.escuela_id'))
		);
	}

	public function realizar_novedad($novedad_id) {
		$novedad = $this->db->query("
			SELECT sn.id, sn.dias, r.descripcion regimen, e.nivel_id, sf.funcion_id
			FROM servicio_novedad sn
				JOIN servicio s ON sn.servicio_id=s.id
				LEFT JOIN servicio_funcion sf ON s.id=sf.servicio_id AND sf.fecha_hasta IS NULL
				JOIN cargo c ON s.cargo_id=c.id
				JOIN regimen r ON c.regimen_id=r.id
				JOIN escuela e ON c.escuela_id=e.id
				JOIN nivel n ON e.nivel_id=n.id
			WHERE sn.id=?
				AND e.dependencia_id IN (1)
				", array($novedad_id))->row();
		if (!empty($novedad)) {
			if (in_array($novedad->nivel_id, array('2', '5', '6', '8'))) {
				if (((int) $novedad->dias) >= 3 && strtolower(substr($novedad->regimen, 0, 8)) !== 'director') {
					return TRUE;
				}
			} else {
				if (((int) $novedad->dias) >= 3 && strtolower(substr($novedad->regimen, 0, 8)) !== 'director') {
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	public function armar_novedad($novedad_id) {
		$llamado = $this->db->query("SELECT sn.id, s.cargo_id, sn.fecha_hasta fin_estimado, nt.articulo, nt.inciso, z.descripcion zona, r.descripcion regimen, CASE WHEN c.carga_horaria=0 THEN '' ELSE c.carga_horaria END horas, e.id escuela_id, e.numero, e.anexo, e.nombre, e.calle, e.calle_numero, e.barrio, l.descripcion localidad, de.descripcion departamento, regional.descripcion regional, cu.descripcion curso, d.division, t.descripcion turno, m.descripcion materia, GROUP_CONCAT(CONCAT(LEFT(dia.nombre, 2), ' ', LEFT(h.hora_desde, 5), '-', LEFT(h.hora_hasta, 5)) ORDER BY dia.id, h.hora_desde) horario, cc.descripcion condicion_cargo, rt.descripcion tipo_llamado"
				. " FROM servicio_novedad sn"
				. " JOIN servicio s ON sn.servicio_id=s.id"
				. " JOIN novedad_tipo nt ON sn.novedad_tipo_id=nt.id"
				. " JOIN cargo c ON s.cargo_id=c.id"
				. " LEFT JOIN condicion_cargo cc ON c.condicion_cargo_id=cc.id"
				. " LEFT JOIN cargo_horario ch ON c.id=ch.cargo_id"
				. " LEFT JOIN horario h ON h.id=ch.horario_id OR h.cargo_id=c.id"
				. " LEFT JOIN dia ON dia.id=h.dia_id"
				. " JOIN regimen r ON c.regimen_id=r.id"
				. " JOIN regimen_tipo rt ON r.regimen_tipo_id=rt.id"
				. " JOIN escuela e ON c.escuela_id=e.id"
				. " LEFT JOIN zona z ON e.zona_id=z.id"
				. " LEFT JOIN localidad l ON e.localidad_id=l.id"
				. " LEFT JOIN departamento de ON l.departamento_id=de.id"
				. " LEFT JOIN regional ON e.regional_id=regional.id"
				. " LEFT JOIN division d ON c.division_id=d.id"
				. " LEFT JOIN curso cu ON d.curso_id=cu.id"
				. " LEFT JOIN espacio_curricular ec ON c.espacio_curricular_id=ec.id"
				. " LEFT JOIN materia m ON ec.materia_id=m.id"
				. " LEFT JOIN turno t ON c.turno_id=t.id"
				. " LEFT JOIN caracteristica_escuela ce_movilidad ON e.id=ce_movilidad.escuela_id AND ce_movilidad.caracteristica_id=53"
				. " WHERE sn.id=?"
				. " GROUP BY sn.id", array($novedad_id))->row();

		if (empty($llamado->id)) {
			return FALSE;
		} else {
			$llamado->fecha_carga = date('Y-m-d H:i:s');
			$llamado->fecha_publicacion = date('Y-m-d H:i:s');
			$llamado->publicar = 'Si';
			$llamado->motivo_no_publica = '';
			$llamado->articulo = (empty($llamado->articulo)) ? 'Cargo vacante' : "$llamado->articulo-$llamado->inciso";
			$llamado->direccion = trim(("$llamado->calle $llamado->calle_numero") .
				(empty($llamado->barrio) ? '' : " B° $llamado->barrio") .
				(empty($llamado->manzana) ? '' : " M:$llamado->manzana") .
				(empty($llamado->casa) ? '' : " C:$llamado->casa"));
			$llamado->fin_estimado = (new DateTime($llamado->fin_estimado))->format('d/m/Y');
			$llamado->division = "$llamado->curso $llamado->division";
			$llamado->fecha_llamado_1 = '';
			$llamado->fecha_llamado_2 = '';
			$llamado->fecha_llamado_3 = '';
			$llamado->fecha_llamado_4 = '';
//			$llamado->fin_estimado = ''; Fecha / A término / Sin término
			$llamado->lugar_trabajo = (empty($llamado->anexo) ? "$llamado->numero" : "$llamado->numero/$llamado->anexo") . " - $llamado->nombre";
//			$llamado->horario = '';
			$llamado->presentarse_en = 'Escuela';
			$llamado->movilidad = '';
			$llamado->prioridad = '';
			$llamado->condiciones_adicionales = '';
			$llamado->observaciones_adicionales = '';
			$llamado->estado = 'Pendiente';
			$llamado->texto_plano = '';
			return $llamado;
		}
	}

	public function armar_cargo($cargo_id) {
		$llamado = $this->db->query("SELECT c.id, z.descripcion zona, r.descripcion regimen, CASE WHEN c.carga_horaria=0 THEN '' ELSE c.carga_horaria END horas, e.id escuela_id, e.numero, e.anexo, e.nombre, e.calle, e.calle_numero, e.barrio, l.descripcion localidad, de.descripcion departamento, regional.descripcion regional, cu.descripcion curso, d.division, t.descripcion turno, m.descripcion materia, GROUP_CONCAT(CONCAT(LEFT(dia.nombre, 2), ' ', LEFT(h.hora_desde, 5), '-', LEFT(h.hora_hasta, 5)) ORDER BY dia.id, h.hora_desde) horario, cc.descripcion condicion_cargo, rt.descripcion tipo_llamado"
				. " FROM cargo c"
				. " LEFT JOIN condicion_cargo cc ON c.condicion_cargo_id=cc.id"
				. " LEFT JOIN cargo_horario ch ON c.id=ch.cargo_id"
				. " LEFT JOIN horario h ON h.id=ch.horario_id OR h.cargo_id=c.id"
				. " LEFT JOIN dia ON dia.id=h.dia_id"
				. " JOIN regimen r ON c.regimen_id=r.id"
				. " LEFT JOIN regimen_tipo rt ON r.regimen_tipo_id=rt.id"
				. " JOIN escuela e ON c.escuela_id=e.id"
				. " LEFT JOIN zona z ON e.zona_id=z.id"
				. " LEFT JOIN localidad l ON e.localidad_id=l.id"
				. " LEFT JOIN departamento de ON l.departamento_id=de.id"
				. " LEFT JOIN regional ON e.regional_id=regional.id"
				. " LEFT JOIN division d ON c.division_id=d.id"
				. " LEFT JOIN curso cu ON d.curso_id=cu.id"
				. " LEFT JOIN espacio_curricular ec ON c.espacio_curricular_id=ec.id"
				. " LEFT JOIN materia m ON ec.materia_id=m.id"
				. " LEFT JOIN turno t ON c.turno_id=t.id"
				. " LEFT JOIN caracteristica_escuela ce_movilidad ON e.id=ce_movilidad.escuela_id AND ce_movilidad.caracteristica_id=53"
				. " WHERE c.id=?"
				. " GROUP BY c.id", array($cargo_id))->row();
		if (empty($llamado->id)) {
			return FALSE;
		} else {
			$llamado->fecha_carga = date('Y-m-d H:i:s');
			$llamado->fecha_publicacion = date('Y-m-d H:i:s');
			$llamado->publicar = 'Si';
			$llamado->motivo_no_publica = '';
			$llamado->articulo = 'Cargo vacante';
			$llamado->direccion = trim(("$llamado->calle $llamado->calle_numero") .
				(empty($llamado->barrio) ? '' : " B° $llamado->barrio") .
				(empty($llamado->manzana) ? '' : " M:$llamado->manzana") .
				(empty($llamado->casa) ? '' : " C:$llamado->casa"));
//			$llamado->fin_estimado = ''; Fecha / A término / Sin término
			$llamado->fin_estimado = 'A término';
			$llamado->division = "$llamado->curso $llamado->division";
			$llamado->fecha_llamado_1 = '';
			$llamado->fecha_llamado_2 = '';
			$llamado->fecha_llamado_3 = '';
			$llamado->fecha_llamado_4 = '';
			$llamado->lugar_trabajo = (empty($llamado->anexo) ? "$llamado->numero" : "$llamado->numero/$llamado->anexo") . " - $llamado->nombre";
			$llamado->presentarse_en = 'Escuela';
			$llamado->movilidad = '';
			$llamado->prioridad = '';
			$llamado->condiciones_adicionales = '';
			$llamado->observaciones_adicionales = '';
			$llamado->estado = 'Pendiente';
			$llamado->texto_plano = '';
			return $llamado;
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
/* End of file Llamado_model.php */
/* Location: ./application/modules/llamados/models/Llamado_model.php */