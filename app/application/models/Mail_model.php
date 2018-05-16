<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mail_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'mail';
		$this->msg_name = 'Mail';
		$this->id_name = 'id';
		$this->columnas = array('id', 'mail_remitente', 'nombre_remitente', 'mail_destinatario', 'nombre_destinatario', 'asunto', 'mensaje', 'fecha_envio', 'fecha_carga');
		$this->fields = array(
			'mail_remitente' => array('label' => 'Mail remitente'),
			'nombre_remitente' => array('label' => 'Nombre remitente'),
			'mail_destinatario' => array('label' => 'Mail destinatario'),
			'nombre_destinatario' => array('label' => 'Nombre destinario'),
			'asunto' => array('label' => 'Asunto'),
			'mensaje' => array('label' => 'Mensaje'),
			'fecha_envio' => array('label' => 'Fecha_envio'),
			'fecha_carga' => array('label' => 'Fecha_carga')
		);

		$this->requeridos = array();
		//$this->unicos = array();
		$this->default_join = array(
		);
	}

	public function get_mensajes($limit = 10) {
		$query = $this->db
			->select('*')
			->from('mail')
			->where('fecha_envio is null')
			->order_by('fecha_carga', 'asc')
			->limit($limit)
			->get();
		return $query->result();
	}

	public function crear_mail($datos) {
		$this->db->insert('mail', $datos);
	}

	public function get_mails_escuela($escuela_id, $ciclo_lectivo) {
		return $this->db->select('a.email_contacto')
				->from('alumno a')
				->join('persona p', 'p.id = a.persona_id', 'left')
				->join('alumno_division ad', 'ad.alumno_id = a.id', 'left')
				->join('division d', 'd.id = ad.division_id', 'left')
				->join('curso c', 'd.curso_id = c.id', 'left')
				->join('documento_tipo dt', 'p.documento_tipo_id = dt.id', 'left')
				->where('d.escuela_id', $escuela_id)
				->where('ad.fecha_hasta IS NULL')
				->where('ciclo_lectivo', $ciclo_lectivo)
				->order_by('a.id')
				->get()->result();
	}

	public function get_divisiones_notificar() {
		return $this->db->select('d.*, e.numero, e.anexo, e.nombre')
				->from('division d')
				->join('escuela e', 'd.escuela_id=e.id')
				->join('division_inasistencia di', 'di.division_id = d.id')
				->where('di.fecha_notificacion IS NULL')
				->where('di.fecha_cierre IS NOT NULL')
				->order_by('d.id')
				->group_by('d.id')
				->get()->result();
	}

	public function division_inasistencia_notificada($division_id) {
		$this->db
			->set('fecha_notificacion', date('Y-m-d H:i:s'))
			->where('division_id', $division_id)
			->where('fecha_notificacion IS NULL')
			->where('fecha_cierre IS NOT NULL')
			->update('division_inasistencia');
	}

	public function get_alumnos($division_id) {
		return $this->db->select('ad.id')
				->from('alumno_division ad')
				->join('alumno a', 'a.id = ad.alumno_id')
				->join('division_inasistencia di', 'di.division_id = ad.division_id AND di.ciclo_lectivo = ad.ciclo_lectivo')
				->where('di.fecha_notificacion IS NULL')
				->where('di.fecha_cierre IS NOT NULL')
				->where('ad.division_id', $division_id)
				->where("(ad.causa_salida_id != '11' OR causa_salida_id IS NULL)")
				->where("a.email_contacto != ''")
				->where("COALESCE(a.mail_valido, '') != 'No'")
				->where("COALESCE(DATE_FORMAT(ad.fecha_hasta,'%Y%m'),di.mes) >= di.mes AND COALESCE(DATE_FORMAT(ad.fecha_desde,'%Y%m'),di.mes) <= di.mes")
				->group_by('ad.id')
				->get()->result();
	}

	public function get_inasistencias_mes($alumno_division_id) {
		$this->db->select("di.id, SUM(CASE WHEN ai.justificada='Si' THEN ai.falta ELSE 0 END) falta_j, SUM(CASE WHEN ai.justificada='No' THEN ai.falta ELSE 0 END) falta_i, di.dias-SUM(COALESCE(ai.falta,0)) asistencia, di.periodo, di.mes, di.fecha_cierre, it.descripcion, di.resumen_mensual, di.dias, c.descripcion calendario, c.nombre_periodo, cp.inicio, cp.fin")
			->from('alumno_division ad')
			->join('division d', 'd.id = ad.division_id')
			->join('calendario c', 'c.id = d.calendario_id')
			->join('division_inasistencia di', 'di.division_id = ad.division_id AND ad.ciclo_lectivo=di.ciclo_lectivo')
			->join('calendario_periodo cp', 'cp.calendario_id = c.id AND cp.ciclo_lectivo=di.ciclo_lectivo AND cp.periodo=di.periodo AND cp.inicio<=COALESCE(ad.fecha_hasta,cp.inicio) AND cp.fin>=ad.fecha_desde')
			->join('division_inasistencia_dia did', 'did.division_inasistencia_id = di.id', 'left')
			->join('alumno_inasistencia ai', 'did.id = ai.division_inasistencia_dia_id AND ai.alumno_division_id=ad.id', 'left')
			->join('inasistencia_tipo it', 'it.id = ai.inasistencia_tipo_id', 'left')
			->where("COALESCE(DATE_FORMAT(ad.fecha_hasta,'%Y%m'),di.mes) >= di.mes AND COALESCE(DATE_FORMAT(ad.fecha_desde,'%Y%m'),di.mes) <= di.mes")
			->where('ad.id', $alumno_division_id)
			->where('di.fecha_notificacion IS NULL')
			->where('di.fecha_cierre IS NOT NULL')
			->group_by('di.id')
			->order_by('di.ciclo_lectivo, di.periodo, di.mes');
		return $this->db->get()->result();
	}

	public function get_inasistencias_dia($alumno_division_id) {
		$this->db->select("ai.id as id, di.periodo, di.mes, did.fecha, ai.justificada, did.contraturno as contraturno_dia, ct.contraturno, ai.falta, ai.inasistencia_tipo_id")
			->from('alumno_division ad')
			->join('division_inasistencia di', 'di.division_id = ad.division_id AND ad.ciclo_lectivo=di.ciclo_lectivo')
			->join('division_inasistencia_dia did', 'did.division_inasistencia_id = di.id')
			->join("(SELECT DISTINCT contraturno FROM division_inasistencia_dia WHERE contraturno != 'Parcial') ct", 'ct.contraturno=(CASE WHEN did.contraturno=\'No\' THEN did.contraturno ELSE ct.contraturno END)', '', false)
			->join('alumno_inasistencia ai', 'did.id = ai.division_inasistencia_dia_id AND ai.alumno_division_id=ad.id AND ai.contraturno=ct.contraturno', 'left')
			->join('inasistencia_tipo it', 'it.id = ai.inasistencia_tipo_id', 'left')
			->where("COALESCE(DATE_FORMAT(ad.fecha_hasta,'%Y%m'),di.mes) >= di.mes AND COALESCE(DATE_FORMAT(ad.fecha_desde,'%Y%m'),di.mes) <= di.mes")
			->where('di.fecha_notificacion IS NULL')
			->where('di.fecha_cierre IS NOT NULL')
			->where('di.resumen_mensual', 'No')
			->where('ad.id', $alumno_division_id)
			->group_by('did.id, ct.contraturno')
			->order_by('di.ciclo_lectivo, di.periodo, di.mes, did.fecha, ct.contraturno');
		return $this->db->get()->result();
	}

	function update_envio_email($email_id) {
		$this->db
			->set('fecha_envio', date('Y-m-d H:i:s'))
			->where('id', $email_id)
			->update('mail');
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
/* End of file Alumno_model.php */
/* Location: ./application/models/Alumno_model.php */