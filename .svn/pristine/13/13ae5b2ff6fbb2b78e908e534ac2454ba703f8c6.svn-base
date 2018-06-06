<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mail extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->load->model('mail_model');
		$this->load->model('alumno_model');
		$this->load->model('division_inasistencia_model');
		$this->load->helper(array('url', 'language'));
		$this->nombres_meses = array('01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');
	}

	public function crear_mail_inasistencia() {
		if (!is_cli()) {
			show_404();
		}
		$this->db->save_queries = FALSE;
		$divisiones_mes_cerrado = $this->mail_model->get_divisiones_notificar();
		foreach ($divisiones_mes_cerrado as $division) {
			$division->escuela_nombre_largo = $division->numero . ($division->anexo === '0' ? '' : "/$division->anexo") . ' ' . $division->nombre;
			$alumnos = $this->mail_model->get_alumnos($division->id);
			if (!empty($alumnos)) {
				foreach ($alumnos as $alumno) {
					$mail = $this->mail_inasistencia($alumno->id, $division);
					$this->mail_model->crear_mail(array(
						'mail_remitente' => $mail['mail_remitente'],
						'nombre_remitente' => $mail['nombre_remitente'],
						'mail_destinatario' => $mail['mail_destinatario'],
						'nombre_destinatario' => $mail['nombre_destinatario'],
						'asunto' => $mail['asunto'],
						'mensaje' => $mail['mensaje'],
						'fecha_carga' => $mail['fecha_carga']));
				}
				$this->mail_model->division_inasistencia_notificada($division->id);
			}
		}
	}

	private function mail_inasistencia($alumno_division_id, $division) {
		/*
		  public function mail_inasistencia($alumno_division_id, $division_id) {
		  $this->load->model('division_model');
		  $division = $this->division_model->get_one($division_id);
		  $this->load->model('escuela_model');
		  $escuela = $this->escuela_model->get_one($division->escuela_id);
		  $division->escuela_nombre_largo = $escuela->nombre_largo;
		 */
		$this->load->model('alumno_division_model');
		$alumno_division = $this->alumno_division_model->get_one($alumno_division_id);
		if (empty($alumno_division)) {
			echo "No se encontró el registro del alumno ($alumno_division_id)";
			return;
		}

		$this->load->model('calendario_model');
		$periodos = $this->calendario_model->get_periodos($division->calendario_id, $alumno_division->ciclo_lectivo);
		if (empty($periodos)) {
			echo "No se encontraron periodos de inasistencia ($division->calendario_id, $alumno_division->ciclo_lectivo)";
			return;
		}
		$alumno_division->escuela = $division->escuela_nombre_largo;

		$data['periodos'] = $periodos;
		$inasistencias_mes = $this->mail_model->get_inasistencias_mes($alumno_division->id);
		$asistencia_mensual = array();
		foreach ($inasistencias_mes as $i_mes) {
			$asistencia_mensual[$i_mes->periodo][$i_mes->mes] = $i_mes;
		}
		$data['asistencia_mensual'] = $asistencia_mensual;

		$inasistencias_dia = $this->mail_model->get_inasistencias_dia($alumno_division->id);
		$asistencia_diaria = array();
		foreach ($inasistencias_dia as $i_dia) {
			$asistencia_diaria[$i_dia->periodo][$i_dia->mes][$i_dia->fecha][$i_dia->contraturno === 'Si' ? 'Si' : 'No'] = $i_dia;
		}
		$data['contraturnos'] = array('No', 'Si');
		$data['asistencia_diaria'] = $asistencia_diaria;
		$this->load->model('alumno_model');
		$alumno = $this->alumno_model->get_one($alumno_division->alumno_id);
		$data['alumno'] = $alumno;
		$data['ciclo_lectivo'] = $alumno_division->ciclo_lectivo;
		$data['division'] = $division;
		$data['alumno_division'] = $alumno_division;
		$data['txt_btn'] = '';
		$data['title'] = 'G.E.M. - Asistencia de alumnos';
		$this->load->helper('security');
		$data['clave'] = $clave = hash('sha256', $alumno->id);

		$this->load->helper('ui');
		$content = trim_html($this->load->view('mail/mail_inasistencia_alumno', $data, TRUE));
		return array('mail_remitente' => 'notificaciones-gem@mendoza.edu.ar', 'nombre_remitente' => 'GEM', 'mail_destinatario' => $alumno->email_contacto, 'nombre_destinatario' => "$alumno->apellido $alumno->nombre", 'asunto' => 'GEM - Asistencia de Alumno', 'mensaje' => $content, 'fecha_carga' => date('Y-m-d H:i:s'));
//		echo $content;
//		return;
	}

	public function remover($alumno_id = NULL, $clave = NULL) {
		$this->session->unset_userdata('usuario');
		$this->session->unset_userdata('rol');
		$this->session->unset_userdata('acceso');

		if (isset($_POST) && !empty($_POST)) {
			if ($alumno_id !== $this->input->post('alumno_verificar_id') || $clave !== hash('sha256', $this->input->post('alumno_verificar_id'))) {
				show_error('Ocurrió un error al intentar actualizar.');
			}
			$this->db->trans_begin();
			$this->db->set('notificar', 'No');
			$this->db->where('id', $alumno_id);
			$this->db->update('alumno');
			if ($this->db->trans_status()) {
				$this->db->trans_commit();
				$this->session->set_flashdata('message', 'No se le enviaran más notificaciones, muchas gracias.');
				redirect("mail/remover", 'refresh');
			} else {
				$this->db->trans_rollback();
				$errors = 'Ocurrió un error al intentar actualizar.';
				$data['error'] = $errors;
				redirect("mail/remover", 'refresh');
			}
		}

		$data['message'] = $this->session->flashdata('message');
		$data['alumno_id'] = $alumno_id;
		$this->load->view("mail/mail_notificar_inasistencia", $data);
	}

	public function cron_mail($limit = 10) {
		if (!is_cli()) {
			show_404();
		}
		$this->load->model('mail_model');
		$mensajes = $this->mail_model->get_mensajes($limit);
		$this->load->library('email');
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => '192.168.31.6',
//			'smtp_port' => 25,
//			'smtp_user' => 'notificaciones-gem@mendoza.edu.ar',
//			'smtp_pass' => 'nr52pq40',
			'mailtype' => 'html',
			'charset' => 'utf-8'
		);

		$this->email->initialize($config);

		foreach ($mensajes as $mensaje) {
			$this->email->from('notificaciones-gem@mendoza.edu.ar', $mensaje->nombre_remitente);
			$this->email->to($mensaje->mail_destinatario);
//			$this->email->to('ggingins@mendoza.gov.ar,gusgins@gmail.com,vledonne@mendoza.gov.ar');
			$this->email->subject($mensaje->asunto);
			$this->email->message($mensaje->mensaje);
			$this->email->set_alt_message(html_entity_decode(str_replace(array('|a', '|tr|', '</tr>'), array('<a', '', '\r\n'), strip_tags(str_replace(array('<i>', '</i>', '<a', '<tr>'), array('_', '_', '|a', '|tr|'), $mensaje->mensaje)))));
			$this->email->send();
			$this->mail_model->update_envio_email($mensaje->id);
//			print_r($this->email->print_debugger()); exit();
		}
	}
}
/* End of file Mail.php */
/* Location: ./application/controllers/Mail.php */