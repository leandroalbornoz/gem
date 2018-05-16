<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Referente_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'referente';
		$this->msg_name = 'Referente';
		$this->id_name = 'id';
		$this->columnas = array('id', 'tribunal_cuenta_id', 'servicio_id', 'domicilio_legal', 'estado');
		$this->fields = array(
			'domicilio_legal' => array('label' => 'Primer Carga*', 'type' => 'date', 'required' => TRUE)
		);
		$this->requeridos = array('tribunal_cuenta_id', 'servicio_id');
		//$this->unicos = array();
		$this->default_join = array(
		);
	}

	public function get_referentes($cuil, $escuela_id, $cuenta_id) {
		return $this->db->select("p.id, ref.id as referente_id, s.id as servicio_id, r.descripcion as regimen, e.id as escuela_id, p.nombre, p.apellido, p.cuil, p.documento, dt.descripcion_corta, tb.id as tribuenal_cuenta_id, (CASE WHEN refv.id IS NULL THEN 0 ELSE 1 END) ya_cargado")
				->from('persona p')
				->join('servicio s', 's.persona_id = p.id AND s.fecha_baja is NULL')
				->join('documento_tipo dt', 'dt.id = p.documento_tipo_id')
				->join('cargo c', 'c.id = s.cargo_id AND c.fecha_hasta is NULL')
				->join('escuela e', 'e.id = c.escuela_id')
				->join('tribunal_cuenta tb', 'tb.escuela_id = e.id')
				->join('regimen r', 'r.id = c.regimen_id AND r.planilla_modalidad_id=1')
				->join('referente ref', 'ref.servicio_id = s.id AND ref.tribunal_cuenta_id = tb.id', 'left')
				->join('referente_vigencia refv', 'refv.referente_id = ref.id AND refv.fecha_hasta IS NULL', 'left')
				->where('p.cuil', $cuil)
				->where('e.id', $escuela_id)
				->where('tb.id', $cuenta_id)
				->get()->result();
	}
	
	public function get_lista_referentes_reporte($escuela_id, $cuenta_id, $fecha_desde, $fecha_hasta) {
		return $this->db->select("tribunal_cuenta.id as tribunal_cuenta_id, referente.id as referente_id, referente_vigencia.id as referente_vigencia_id, referente.estado, persona.apellido, persona.nombre, persona.documento, persona.cuil,escuela.id as escuela_id,escuela.numero, persona.email, persona.telefono_fijo, persona.calle as domicilio_legal, CONCAT(COALESCE(CONCAT(persona.calle,' '),''), COALESCE(CONCAT(persona.calle_numero,' '),''), COALESCE(CONCAT('Dpto:',persona.departamento,' '),''), COALESCE(CONCAT('P:',persona.piso,' '),''), COALESCE(CONCAT('B°:',persona.barrio,' '),''), COALESCE(CONCAT('M:',persona.manzana,' '),''), COALESCE(CONCAT('C:',persona.casa,' '),'')) as domicilio_real, regimen.descripcion as cargo, (CASE WHEN referente_vigencia.fecha_hasta IS NULL THEN 1 ELSE 0 END) as estado, DATE_FORMAT(referente_vigencia.fecha_desde,'%d/%m/%Y') as fecha_desde, COALESCE(DATE_FORMAT(referente_vigencia.fecha_hasta,'%d/%m/%Y'),'Vigente') as fecha_hasta, referente.domicilio_legal")
				->from('tribunal_cuenta')
				->join('referente', 'referente.tribunal_cuenta_id = tribunal_cuenta.id')
				->join('referente_vigencia', 'referente_vigencia.referente_id = referente.id')
				->join('servicio', 'servicio.id = referente.servicio_id', 'left')
				->join('cargo', 'cargo.id = servicio.cargo_id', 'left')
				->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', 'left')
				->join('regimen', 'regimen.id = cargo.regimen_id', 'left')
				->join('persona', 'persona.id = servicio.persona_id', 'left')
				->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id')
				->join('escuela', 'escuela.id = tribunal_cuenta.escuela_id', 'left')
				->where('tribunal_cuenta.escuela_id', $escuela_id)
				->where('referente.tribunal_cuenta_id', $cuenta_id)
				->where("referente_vigencia.fecha_desde >=", $fecha_desde)
				->where("(referente_vigencia.fecha_hasta <= '$fecha_hasta' OR referente_vigencia.fecha_hasta IS NULL)")
				->get()->result();
	}

	public function get_lista_referentes($escuela_id, $cuenta_id) {
		return $this->db->select("tribunal_cuenta.id, referente.id, referente_vigencia.id, referente.estado, persona.apellido, persona.nombre, persona.documento, persona.cuil,escuela.id as escuela_id,escuela.numero, persona.email, persona.telefono_fijo, persona.calle as domicilio_legal, CONCAT(COALESCE(CONCAT(persona.calle,' '),''), COALESCE(CONCAT(persona.calle_numero,' '),''), COALESCE(CONCAT('Dpto:',persona.departamento,' '),''), COALESCE(CONCAT('P:',persona.piso,' '),''), COALESCE(CONCAT('B°:',persona.barrio,' '),''), COALESCE(CONCAT('M:',persona.manzana,' '),''), COALESCE(CONCAT('C:',persona.casa,' '),'')) as domicilio_real, regimen.descripcion as cargo, (CASE WHEN referente_vigencia.fecha_hasta IS NULL THEN 1 ELSE 0 END) as estado, DATE_FORMAT(referente_vigencia.fecha_desde,'%d/%m/%Y') as fecha_desde, COALESCE(DATE_FORMAT(referente_vigencia.fecha_hasta,'%d/%m/%Y'),'Vigente') as fecha_hasta, referente.domicilio_legal")
				->from('tribunal_cuenta')
				->join('referente', 'referente.tribunal_cuenta_id = tribunal_cuenta.id', 'left')
				->join('referente_vigencia', 'referente_vigencia.referente_id = referente.id', 'left')
				->join('servicio', 'servicio.id = referente.servicio_id', 'left')
				->join('cargo', 'cargo.id = servicio.cargo_id', 'left')
				->join('condicion_cargo', 'condicion_cargo.id = cargo.condicion_cargo_id', 'left')
				->join('regimen', 'regimen.id = cargo.regimen_id', 'left')
				->join('persona', 'persona.id = servicio.persona_id', 'left')
				->join('documento_tipo', 'persona.documento_tipo_id = documento_tipo.id')
				->join('escuela', 'escuela.id = tribunal_cuenta.escuela_id', 'left')
				->where('tribunal_cuenta.escuela_id', $escuela_id)
				->where('tribunal_cuenta.id', $cuenta_id)
				->get()->result();
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
/* End of file Referente_model.php */
/* Location: ./application/modules/tribunal/models/Referente_model.php */