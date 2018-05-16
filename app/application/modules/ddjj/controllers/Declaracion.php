<?php

class Declaracion extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->roles_permitidos = array(ROL_ADMIN, ROL_USI);
	}

	public function validaCuil($cuil) {
		$digits = array();
		if (strlen($cuil) != 13) {
			return false;
		}
		for ($i = 0; $i < strlen($cuil); $i++) {
			if ($i == 2 or $i == 11) {
				if ($cuil[$i] != '-') {
					return false;
				}
			} else {
				if (!ctype_digit($cuil[$i])) {
					return false;
				}
				if ($i < 12) {
					$digits[] = $cuil[$i];
				}
			}
		}
		$acum = 0;
		foreach (array(5, 4, 3, 2, 7, 6, 5, 4, 3, 2) as $i => $multiplicador) {
			$acum += $digits[$i] * $multiplicador;
		}
		$cmp = 11 - ($acum % 11);
		if ($cmp == 11) {
			$cmp = 0;
		}
		if ($cmp == 10) {
			$cmp = 9;
		}
		return ($cuil[12] == $cmp);
	}

	public function ver($cuil = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $cuil == NULL) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (!$this->validaCuil($cuil)) {
			$this->session->set_flashdata('error', 'El CUIL ingresado no es válido');
			redirect('escritorio');
		}
		$this->load->model('ddjj/declaracion_model');

		$persona = $this->declaracion_model->get_persona($cuil);
		if (!empty($persona)) {
			$servicios = $this->declaracion_model->get_servicios($persona->cuil);
		}

		$data['persona'] = $persona;
		$data['servicios'] = $servicios;
		$header = $this->load->view('ddjj/declaracion_pdf_header', $data, TRUE);
		$content = $this->load->view('ddjj/declaracion_pdf', $data, TRUE);
		$footer = $this->load->view('ddjj/declaracion_pdf_footer', $data, TRUE);
		$this->load->helper('mpdf');
		$css = array('plugins/kv-mpdf-bootstrap.min.css');
		$fileName = "DDJJ-$persona->persona";
		$format = 'LEGAL-L';
		$watermark = 'SIN VALIDEZ LEGAL';
		$type = 'I';
		$htmlFooter = TRUE;
		$htmlHeader = TRUE;
		include_once dirname(__FILE__) . '../../../../../vendor/autoload.php';
		$this->mpdf = new \Mpdf\Mpdf(array(
			'mode' => '',
			'format' => $format,
			'default_font_size' => 0,
			'default_font' => '',
			'margin_left' => 5,
			'margin_right' => 5,
			'margin_top' => 48,
			'margin_bottom' => 34,
			'margin_header' => 3,
			'margin_footer' => 3,
			'orientation' => 'L',
		));
		$this->mpdf->SetTitle($fileName);
		$this->mpdf->SetDisplayMode('fullwidth');
		//$this->mpdf->StartProgressBarOutput(2);
		$this->mpdf->cacheTables = true;
		//$this->mpdf->showStats = true;
		$this->mpdf->pagenumPrefix = 'Página ';
		if ($watermark != '') {
			$this->mpdf->SetWatermarkText($watermark, 0.20, 'P');
			$this->mpdf->showWatermarkText = true;
		}
		if ($htmlHeader) {
			$this->mpdf->SetHTMLHeader($header);
		} else {
			$this->mpdf->SetHeader($header);
		}
		if ($htmlFooter) {
			$this->mpdf->SetHTMLFooter($footer);
		} else {
			$this->mpdf->SetFooter($footer);
		}

		if ($css !== '') {
			if (is_array($css)) {
				foreach ($css as $value) {
					$stylesheet = file_get_contents($value);
					$this->mpdf->WriteHTML($stylesheet, 1);
				}
			} else {
				$stylesheet = file_get_contents($css);
				$this->mpdf->WriteHTML($stylesheet, 1);
			}
		}
		if (is_array($content)) {
			foreach ($content as $htm) {
				$this->mpdf->WriteHTML($htm, 2);
			}
		} else {
			$this->mpdf->WriteHTML($content, 2);
		}

		$this->mpdf->Output($fileName . '.pdf', $type);
	}
}