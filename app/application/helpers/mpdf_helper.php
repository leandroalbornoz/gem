<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Helper de mPDF
 * Autor: Leandro
 * Creado: 23/01/2014
 * Modificado: 11/12/2014 (Gustavo)
 */
if (!function_exists('exportMPDF')) {

	function exportMPDF($htmView, $css = '', $fileName = 'nn', $header = '', $footer = '', $format = 'A4', $watermark = '', $type = 'I', $htmlFooter = FALSE, $htmlHeader = FALSE) {
		$CI = & get_instance();
		$CI->load->library('mpdf_lib');
		$CI->mpdf = new mPDF('c', $format, 0, '', 5, 5, 16, 16, 9, 9, 'L');
		$CI->mpdf->SetTitle($fileName);
		$CI->mpdf->SetDisplayMode('fullwidth');
		//$CI->mpdf->StartProgressBarOutput(2);
		if (ENVIRONMENT === 'production')
			$CI->mpdf->cacheTables = true;
		else
			$CI->mpdf->cacheTables = false;
		//$CI->mpdf->showStats = true;
		$CI->mpdf->pagenumPrefix = 'Página ';
		//$CI->mpdf->pagenumSuffix = ' - ';
		//$CII->mpdf->nbpgPrefix = ' out of ';
		//$CI->mpdf->nbpgSuffix = ' pages';
		if ($watermark != '') {
			$CI->mpdf->SetWatermarkText($watermark, 0.20, 'P');
			$CI->mpdf->showWatermarkText = true;
		}
		if ($htmlHeader)
			$CI->mpdf->SetHTMLHeader($header);
		else
			$CI->mpdf->SetHeader($header);
		if ($htmlFooter)
			$CI->mpdf->SetHTMLFooter($footer);
		else
			$CI->mpdf->SetFooter($footer);

		if ($css !== '') {
			if (is_array($css)) {
				foreach ($css as $value) {
					$stylesheet = file_get_contents($value);
					$CI->mpdf->WriteHTML($stylesheet, 1);
				}
			} else {
				$stylesheet = file_get_contents($css);
				$CI->mpdf->WriteHTML($stylesheet, 1);
			}
		}
		if (is_array($htmView))
			foreach ($htmView as $htm)
				$CI->mpdf->WriteHTML($htm, 2);
		else
			$CI->mpdf->WriteHTML($htmView, 2);

		$CI->mpdf->Output($fileName . '.pdf', $type);
	}
}

if (!function_exists('exportMPDF0')) {

	function exportMPDF0($htmView, $fileName = 'nn', $header = '', $footer = '', $format = 'A4', $type = 'I', $htmlFooter = FALSE, $htmlHeader = FALSE) {
		$CI = & get_instance();
		$CI->load->library('mpdf_lib');
		$CI->mpdf = new mPDF('c', $format, 0, '', 30, 30, 32, 32, 18, 18, 'L');
		$CI->mpdf->SetTitle($fileName);
//		$CI->mpdf->SetDisplayMode('fullwidth');
//		$CI->mpdf->StartProgressBarOutput(2);
		$CI->mpdf->cacheTables = true;
		//$CI->mpdf->showStats = true;
		$CI->mpdf->pagenumPrefix = 'Página ';
		//$CI->mpdf->pagenumSuffix = ' - ';
		//$CII->mpdf->nbpgPrefix = ' out of ';
		//$CI->mpdf->nbpgSuffix = ' pages';
		if ($htmlHeader)
			$CI->mpdf->SetHTMLHeader($header);
		else
			$CI->mpdf->SetHeader($header);
		if ($htmlFooter)
			$CI->mpdf->SetHTMLFooter($footer);
		else
			$CI->mpdf->SetFooter($footer);

		$CI->mpdf->WriteHTML($htmView, 0);

		return $CI->mpdf->Output($fileName . '.pdf', $type);
	}
}

if (!function_exists('exportarMPDF')) {

	function exportarMPDF($htmView, $css = '', $fileName = 'nn', $header = '', $footer = '', $format = 'A4', $watermark = '', $type = 'I', $htmlFooter = FALSE, $htmlHeader = FALSE) {
		include_once dirname(__FILE__) . '/../../vendor/autoload.php';
		$CI = & get_instance();
		$CI->mpdf = new \Mpdf\Mpdf(array(
			'mode' => '',
			'format' => $format,
			'default_font_size' => 0,
			'default_font' => '',
			'margin_left' => 5,
			'margin_right' => 5,
			'margin_top' => 16,
			'margin_bottom' => 16,
			'margin_header' => 9,
			'margin_footer' => 9,
			'orientation' => 'L',
		));
		$CI->mpdf->SetTitle($fileName);
		$CI->mpdf->SetDisplayMode('fullwidth');
		//$CI->mpdf->StartProgressBarOutput(2);
		if (ENVIRONMENT === 'production')
			$CI->mpdf->cacheTables = true;
		else
			$CI->mpdf->cacheTables = false;
		//$CI->mpdf->showStats = true;
		$CI->mpdf->pagenumPrefix = 'Página ';
		//$CI->mpdf->pagenumSuffix = ' - ';
		//$CII->mpdf->nbpgPrefix = ' out of ';
		//$CI->mpdf->nbpgSuffix = ' pages';
		if ($watermark != '') {
			$CI->mpdf->SetWatermarkText($watermark, 0.20, 'P');
			$CI->mpdf->showWatermarkText = true;
		}
		if ($htmlHeader)
			$CI->mpdf->SetHTMLHeader($header);
		else
			$CI->mpdf->SetHeader($header);
		if ($htmlFooter)
			$CI->mpdf->SetHTMLFooter($footer);
		else
			$CI->mpdf->SetFooter($footer);

		if ($css !== '') {
			if (is_array($css)) {
				foreach ($css as $value) {
					$stylesheet = file_get_contents($value);
					$CI->mpdf->WriteHTML($stylesheet, 1);
				}
			} else {
				$stylesheet = file_get_contents($css);
				$CI->mpdf->WriteHTML($stylesheet, 1);
			}
		}
		if (is_array($htmView))
			foreach ($htmView as $htm)
				$CI->mpdf->WriteHTML($htm, 2);
		else
			$CI->mpdf->WriteHTML($htmView, 2);

		return $CI->mpdf->Output($fileName . '.pdf', $type);
	}
}
/* End of file mpdf_helper.php */
/* Location: ./application/helpers/mpdf_helper.php */