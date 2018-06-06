<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{

	public function validate_date($date)
	{
		if (empty($date) || $date === -1)
		{
			return TRUE;
		}
		$date_format = 'd-m-Y';
		$new_date = str_replace('/', '-', trim($date));
		$time = strtotime($new_date);
		$is_valid = date($date_format, $time) === $new_date;
		if ($is_valid)
		{
			return TRUE;
		}
		return FALSE;
	}

	public function validate_time($time)
	{
		if (empty($time) || $time === -1)
		{
			return TRUE;
		}

		$partes = explode(':', $time);
		if (count($partes) > 2)
		{
			return FALSE;
		}

		if (!is_numeric($partes[0]) || !is_numeric($partes[1]))
		{
			return FALSE;
		}
		else if ((int) $partes[0] > 24 || (int) $partes[1] > 59)
		{
			return FALSE;
		}
		else if (mktime((int) $partes[0], (int) $partes[1]) === FALSE)
		{
			return FALSE;
		}

		return TRUE;
	}

	public function validate_datetime($datetime)
	{
		$date = substr($datetime, 0, -6);
		$time = substr($datetime, -5);
		return ($this->validate_date($date) && $this->validate_time($time));
	}

	public function money($value)
	{
		$fmt = numfmt_create('es_AR', NumberFormatter::DECIMAL);
		$value = $fmt->parse($value);
		return $value === FALSE ? "$value" : $value;
	}

	public function validate_cbu($cbu)
	{
		if (empty($cbu))
		{
			return TRUE;
		}
		if (strlen($cbu) !== 22) // Cadena debe tener 22 caracteres
		{
			return FALSE;
		}
		if (!ctype_digit($cbu)) // Todos los caracteres deben ser dígitos
		{
			return FALSE;
		}

		$peso = array(3, 1, 7, 9);

		$suma_8 = 0;
		$j_8 = 0;
		for ($i = 6; $i >= 0; $i--)
		{
			$suma_8+=(substr($cbu, $i, 1) * $peso[$j_8 % 4]);
			$j_8++;
		}
		$digito_8 = (10 - ($suma_8 % 10)) % 10;
		if ($digito_8 !== substr($cbu, 7, 1)) // Validación Dígito 8
		{
			return FALSE;
		}

		$suma_22 = 0;
		$j_22 = 0;
		for ($i = 20; $i >= 8; $i--)
		{
			$suma_22+=(substr($cbu, $i, 1) * $peso[$j_22 % 4]);
			$j_22++;
		}
		$digito_22 = (10 - ($suma_22 % 10)) % 10;
		if ($digito_22 !== substr($cbu, 21, 1)) // Validación Dígito 22
		{
			return FALSE;
		}
		return TRUE;
	}

}