<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mail_dominio_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'mail_dominio';
		$this->msg_name = 'Mail_dominio';
		$this->id_name = 'id';
		$this->columnas = array('id', 'dominio', 'email_valido');
		$this->fields = array(
			'dominio' => array('label' => 'Mail remitente'),
			'email_valido' => array('label' => 'Nombre remitente'),
		);
		$this->requeridos = array();
		$this->default_join = array(
		);
	}
	
}
