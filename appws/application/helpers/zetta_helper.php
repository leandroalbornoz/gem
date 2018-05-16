<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('auto_version')) {

	function auto_version($url) {
		$path = pathinfo($url);
		$string = $path['basename'];
		$ver = '.version' . filemtime(DIRECTORIO . $url) . '.';
		$str = '.';
		if (( $pos = strrpos($string, $str) ) !== false) {
			$search_length = strlen($str);
			$str = substr_replace($string, $ver, $pos, $search_length);
			return $path['dirname'] . '/' . $str;
		} else
			return $url;
	}
}

if (!function_exists('lm')) {

	function lm($message) {
		log_message('error', print_r($message, TRUE));
	}
}

if (!function_exists('zetta_form_submit')) {

	function zetta_form_submit($txt_btn) {
		if (empty($txt_btn)) {
			return;
		} elseif ($txt_btn === 'Eliminar') {
			return form_submit(array('class' => 'btn btn-danger pull-right', 'title' => 'Eliminar'), 'Eliminar');
		} else {
			return form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Guardar'), 'Guardar');
		}
	}
}

if (!function_exists('trim_html')) {

	function trim_html($html) {
		$search = array(
			'/\n/', // replace end of line by a space
			'/\s+/', // replace end of line by a space
			'/\>[^\S ]+/s', // strip whitespaces after tags, except space
			'/[^\S ]+\</s', // strip whitespaces before tags, except space
			'/(\s)+/s' // shorten multiple whitespace sequences
		);
		$replace = array(
			' ',
			' ',
			'>',
			'<',
			'\\1'
		);
		return preg_replace($search, $replace, $html);
	}
}

/* End of file zetta_helper.php */
/* Location: ./application/helpers/zetta_helper.php */