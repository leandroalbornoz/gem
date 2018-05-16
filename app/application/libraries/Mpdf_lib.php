<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/**
 * Lib de mPDF
 * Autor: Leandro
 * Creado: 23/01/2014
 * Modificado: 23/01/2014 (Leandro)
 */
require_once dirname(__FILE__) . '/mpdf57/mpdf.php';
class Mpdf_lib extends mPDF
{

	function __construct()
	{
		set_time_limit(300);
		ini_set("memory_limit", "256M");
		parent::__construct();
	}
}
/* End of file mpdf_lib.php */
/* Location: ./application/libraries/mpdf_lib.php */