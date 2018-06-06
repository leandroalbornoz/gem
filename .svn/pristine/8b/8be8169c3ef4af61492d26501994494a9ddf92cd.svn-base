<?php

defined('BASEPATH')	OR	exit('No direct script access allowed');

class	Acuerdo_zona_recepcion_model	extends	MY_Model	{

	public	function	__construct()	{
		parent::__construct();
		$this->table_name	=	'acuerdo_zona_recepcion';
		$this->msg_name	=	'Recepción Acuerdo Zona';
		$this->id_name	=	'id';
		$this->columnas	=	array('id',	'acuerdo_zona_remito_id',	'fecha',	'cuil',	'persona',	'nombre',	'jubilado',	'estado');
		$this->fields	=	array(
				'persona'	=>	array('label'	=>	'Persona',	'maxlength'	=>	'10',	'required'	=>	TRUE),
				'jubilado'	=>	array('label'	=>	'Jubilado',	'input_type'	=>	'combo',	'array'	=>	array('Si'	=>	'Si',	'No'	=>	'No'),	'required'	=>	TRUE),
				'estado'	=>	array('label'	=>	'Estado',	'input_type'	=>	'combo',	'array'	=>	array('Conforme'	=>	'Conforme',	'Disconforme'	=>	'Disconforme'),	'required'	=>	TRUE)
		);
		$this->requeridos	=	array('acuerdo_zona_remito_id',	'cuil',	'persona',	'jubilado',	'estado');
		//$this->unicos = array();
		$this->default_join	=	array(
				array('acuerdo_zona_remito',	'acuerdo_zona_remito.id = acuerdo_zona_recepcion.acuerdo_zona_remito_id',	'left',	array('acuerdo_zona_remito.numero as acuerdo_zona_remito')),);
	}

	public	function	buscar_inconsistencia_cuil($cuil,	$estado)	{
		$inconsistencia	=	$this->db->query('
			SELECT e.numero escuela, e.anexo, rem.numero, rec.estado
			FROM acuerdo_zona_recepcion rec 
			JOIN acuerdo_zona_remito rem ON rem.id=rec.acuerdo_zona_remito_id 
			JOIN escuela e ON rem.escuela_id=e.id 
			WHERE rec.cuil = ? AND estado != ?'
						,	array($cuil,	$estado))->row();
		if	($inconsistencia)	{
			if	($inconsistencia->anexo	!==	'0')	{
				$inconsistencia->escuela	=	"$inconsistencia->escuela/$inconsistencia->anexo";
			}
		}
		return	$inconsistencia;
	}

	/**
		* _can_delete: Devuelve true si puede eliminarse el registro.
		*
		* @param int $delete_id
		* @return bool
		*/
	protected	function	_can_delete($delete_id)	{
		return	TRUE;
	}
}
/* End of file Acuerdo_zona_recepcion_model.php */
/* Location: ./application/modules/acuerdo_zona/models/Acuerdo_zona_recepcion_model.php */