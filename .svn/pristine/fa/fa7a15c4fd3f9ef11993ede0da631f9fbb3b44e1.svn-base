<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('icono')) {

	function icono($contenido, $color_texto, $color_fondo, $color_borde) {
		return '<span style="display: inline-block; width: 18px; height: 18px;  line-height: 18px; text-align: center; vertical-align: middle; color: ' . $color_texto . ' !important; background-color: ' . $color_fondo . ' !important; border: ' . $color_borde . ' solid thin;">' . $contenido . '</span>';
	}
}

if (!function_exists('icono_asistencia')) {

	function icono_asistencia($i_dia) {

		$iconos = array(
			'Aj' => icono('A', '#fff', '#dd4b39', '#9e3528'),
			'Ai' => icono('A<sub>i</sub>', '#fff', '#dd4b39', '#9e3528'),
			'Tj' => icono('T', '#fff', '#dd4b39', '#9e3528'),
			'Ti' => icono('T<sub>i</sub>', '#fff', '#dd4b39', '#9e3528'),
			'Rj' => icono('R', '#fff', '#dd4b39', '#9e3528'),
			'Ri' => icono('R<sub>i</sub>', '#fff', '#dd4b39', '#9e3528'),
			'NI' => icono('-', '#000', '#fff', '#999'),
			'Ap' => icono('A', '#000', '#fff', '#008749'),
			'Pa' => icono('P', '#fff', '#dd4b39', '#9e3528'),
			'P' => icono('P', '#000', '#fff', '#008749'),
		);
		$icono = '';
		switch ($i_dia->inasistencia_tipo_id) {
			case '2':
				$icono = $i_dia->justificada === 'Si' ? $iconos['Aj'] : $iconos['Ai'];
				break;
			case '3':
				$icono = $i_dia->justificada === 'Si' ? $iconos['Tj'] : $iconos['Ti'];
				break;
			case '4':
				$icono = $i_dia->justificada === 'Si' ? $iconos['Rj'] : $iconos['Ri'];
				break;
			case '5':
			case '6':
				$icono = $iconos['NI'];
				break;
			case '7':
				$icono = $iconos['Ap'];
				break;
			case '8':
				$icono = $iconos['Pa'];
				break;
			case '9':
				break;
			default:
				if ($i_dia->contraturno_dia === 'No' || $i_dia->contraturno !== 'Parcial') {
					$icono = $iconos['P'];
				}
				break;
		}
		return $icono;
	}
}

/* End of file html_helper.php */
/* Location: ./application/helpers/html_helper.php */