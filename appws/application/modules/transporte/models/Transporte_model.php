<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transporte_model extends CI_Model {

	public function get_alumno($documento) {
		$datos = $this->db->select("p.cuil, dt.descripcion_corta as tipo_documento, p.documento, p.apellido, p.nombre, s.descripcion as sexo, ec.descripcion as estado_civil, p.fecha_nacimiento, nac.descripcion as nacionalidad, p.telefono_fijo, p.telefono_movil, p.email, p.calle, p.calle_numero, p.departamento as depto, p.piso, p.barrio, p.manzana, p.casa, l.descripcion as localidad, dep.descripcion as departamento, prov.descripcion as provincia, p.codigo_postal")
				->from('alumno a')
				->join('persona p', 'p.id = a.persona_id')
				->join('alumno_division ad', 'a.id = ad.alumno_id', 'left')
				->join('division d', 'd.id = ad.division_id', 'left')
				->join('escuela e', 'e.id = d.escuela_id')
				->join('documento_tipo dt', 'p.documento_tipo_id = dt.id', 'left')
				->join('sexo s', 's.id = p.sexo_id', 'left')
				->join('estado_civil ec', 'ec.id = p.estado_civil_id', 'left')
				->join('localidad l', 'l.id = p.localidad_id', 'left')
				->join('departamento dep', 'dep.id = l.departamento_id', 'left')
				->join('provincia prov', 'prov.id = dep.provincia_id', 'left')
				->join('nacionalidad nac', 'nac.id = p.nacionalidad_id', 'left')
				->join('curso c', 'c.id = d.curso_id', 'left')
				->where('p.documento', $documento)
				->order_by('p.id')
				->get()->row();
		return $datos;
	}

	public function get_alumno_escuela($documento) {
		$datos = $this->db->select("e.numero as numero_escuela, e.anexo as anexo_escuela, e.nombre as nombre_escuela, c.id as division_id, GROUP_CONCAT(DISTINCT TRIM(CONCAT(c.descripcion, ' ', d.division)) ORDER BY c.descripcion, d.division SEPARATOR '-') division, ad.ciclo_lectivo, e.calle, e.calle_numero, e.departamento as depto, e.piso, e.barrio, e.manzana, e.casa, l.descripcion as localidad, dep.descripcion as departamento, prov.descripcion as provincia, e.codigo_postal,n.id as nivel_id, n.descripcion as nivel, 'alumno' as tipo_persona")
				->from('alumno a')
				->join('persona p', 'p.id = a.persona_id')
				->join('alumno_division ad', 'a.id = ad.alumno_id', 'left')
				->join('division d', 'd.id = ad.division_id', 'left')
				->join('escuela e', 'e.id = d.escuela_id')
				->join('localidad l', 'l.id = e.localidad_id', 'left')
				->join('departamento dep', 'dep.id = l.departamento_id', 'left')
				->join('provincia prov', 'prov.id = dep.provincia_id', 'left')
				->join('curso c', 'c.id = d.curso_id', 'left')
				->join('nivel n', 'n.id = e.nivel_id', 'left')
				->where('p.documento', $documento)
				->where('e.escuela_activa', 'Si')
				->group_by('e.id')
				->order_by('p.id')
				->get()->result();
		return $datos;
	}

	function get_docente($documento) {
		$datos = $this->db->select("p.cuil, dt.descripcion_corta as tipo_documento, p.documento, p.apellido, p.nombre, s.descripcion as sexo, ec.descripcion as estado_civil, p.fecha_nacimiento, n.descripcion as nacionalidad, p.telefono_fijo, p.telefono_movil, p.email, p.calle, p.calle_numero, p.departamento as depto, p.piso, p.barrio, p.manzana, p.casa, l.descripcion as localidad, d.descripcion as departamento, prov.descripcion as provincia, p.codigo_postal")
				->from('servicio serv')
				->join('cargo ca', 'ca.id = serv.cargo_id', 'left')
				->join('persona p', 'serv.persona_id = p.id')
				->join('documento_tipo dt', 'dt.id = p.documento_tipo_id', 'left')
				->join('localidad l', 'l.id = p.localidad_id', 'left')
				->join('departamento d', 'd.id = l.departamento_id')
				->join('provincia prov', 'prov.id = d.provincia_id', 'left')
				->join('sexo s', 's.id = p.sexo_id', 'left')
				->join('estado_civil ec', 'ec.id = p.estado_civil_id', 'left')
				->join('nacionalidad n', 'n.id = p.nacionalidad_id', 'left')
				->join('cargo carg', 'carg.id = serv.cargo_id', 'left')
				->join('division div', 'carg.division_id = div.id', 'left')
				->join('curso c', 'c.id = div.curso_id', 'left')
				->where('serv.fecha_baja is null', null, false)
				->where('p.documento', $documento)
				->order_by('p.id')
				->get()->row();
		return $datos;
	}

	function get_docente_escuela($documento) {
		$datos = $this->db->select("e.numero as numero_escuela, e.anexo as anexo_escuela, e.nombre as nombre_escuela, c.id as division_id, GROUP_CONCAT(DISTINCT TRIM(CONCAT(c.descripcion, ' ', d.division)) ORDER BY c.descripcion, d.division SEPARATOR '-') division, e.calle, e.calle_numero, e.departamento as depto, e.piso, e.barrio, e.manzana, e.casa, l.descripcion as localidad, dep.descripcion as departamento, prov.descripcion as provincia, e.codigo_postal, n.id as nivel_id, n.descripcion as nivel, CASE WHEN reg.codigo='0560201' THEN 'celador' ELSE 'docente' END as tipo_persona")
				->from('persona p')
				->join('servicio s', 'p.id = s.persona_id')
				->join('cargo ca', 'ca.id = s.cargo_id', 'left')
				->join('regimen reg', 'reg.id = ca.regimen_id', 'left')
				->join('division d', 'd.id = ca.division_id', 'left')
				->join('escuela e', 'e.id = ca.escuela_id')
				->join('localidad l', 'l.id = e.localidad_id', 'left')
				->join('departamento dep', 'dep.id = l.departamento_id', 'left')
				->join('provincia prov', 'prov.id = dep.provincia_id', 'left')
				->join('curso c', 'c.id = d.curso_id', 'left')
				->join('nivel n', 'n.id = e.nivel_id', 'left')
				->where('p.documento', $documento)
				->where('e.escuela_activa', 'Si')
				->group_by("e.id, CASE WHEN reg.codigo='0560201' THEN 1 ELSE 0 END")
				->order_by('p.id')
				->get()->result();
		return $datos;
	}

	function get_familiares($documento) {
		$datos = $this->db->select("pt.descripcion as parentesco, dt.descripcion_corta as tipo_documento, pariente.documento, pariente.apellido, pariente.nombre")
				->from('persona p')
				->join('familia f', 'f.persona_id = p.id')
				->join('parentesco_tipo pt', 'pt.id = f.parentesco_tipo_id')
				->join('persona pariente', 'pariente.id = f.pariente_id')
				->join('documento_tipo dt', 'pariente.documento_tipo_id = dt.id')
				->where('p.documento', $documento)
				->get()->result();
		return $datos;
	}

	function get_escuelas($escuela_numero,$escuela_anexo) {
		$datos = $this->db->select("e.numero, e.anexo, e.nombre, e.calle, e.calle_numero, e.departamento, e.piso, e.barrio, e.manzana, e.casa, l.descripcion as localidad, e.codigo_postal, n.descripcion as nivel, e.telefono, email, email2")
				->from('escuela e')
				->join('localidad l', 'l.id = e.localidad_id', 'left')
				->join('nivel n', 'n.id = e.nivel_id', 'left')
				->where('escuela_activa', 'Si')
				->where('e.numero', $escuela_numero)
				->where('e.anexo', $escuela_anexo)
				->order_by('e.numero', 'desc')
				->get()->result();
		return $datos;
	}
}