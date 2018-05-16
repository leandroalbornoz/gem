<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recepcion extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->roles_permitidos = array(ROL_BONO_SECUNDARIO);
	}

	public function listar_personas_data_precepcion($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables->set_database('bono_secundario');
		$this->datatables
			->select("inscripcion.id, CONCAT(escuela.numero, ' - ', escuela.nombre) as escuela, persona.cuil, CONCAT(persona.apellido, ', ', persona.nombre) as persona, CONCAT(persona.calle, ' ', persona.calle_numero, ' ', persona.piso) as domicilio, CONCAT(persona.telefono_fijo, ' ', persona.telefono_movil) as telefonos, persona.email, inscripcion.fecha_cierre as fecha_inscripcion, inscripcion.fecha_recepcion")
			->unset_column('id')
			->from('inscripcion')
			->join('persona', 'inscripcion.persona_id = persona.id', 'left')
			->join('escuela', 'escuela.id = inscripcion.escuela_id', 'left')
			->where("inscripcion.fecha_recepcion is NULL AND inscripcion.fecha_cierre IS NOT NULL AND inscripcion.escuela_id = $escuela_id AND inscripcion.fecha_reclamo is NULL")
			->add_column('edit', '<a href="bono_secundario/recepcion/recibir/$1" title="Administrar"><i class="fa fa-download"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function listar_personas_data_recibidos($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables->set_database('bono_secundario');
		$this->datatables
			->select('inscripcion.id, CONCAT(escuela.numero, \' - \', escuela.nombre) as escuela, persona.cuil, CONCAT(persona.apellido, \', \', persona.nombre) as persona, CONCAT(persona.calle, \' \', persona.calle_numero, \' \', persona.piso) as domicilio, CONCAT(persona.telefono_fijo, \' \', persona.telefono_movil) as telefonos, persona.email, inscripcion.fecha_cierre as fecha_inscripcion, inscripcion.fecha_recepcion')
			->unset_column('id')
			->from('inscripcion')
			->join('persona', 'inscripcion.persona_id = persona.id')
			->join('escuela', 'escuela.id = inscripcion.escuela_id', 'left')
			->where("inscripcion.fecha_recepcion IS NOT NULL AND inscripcion.escuela_id = $escuela_id AND inscripcion.fecha_reclamo IS NULL")
			->add_column('edit', '<a href="bono_secundario/recepcion/inscripcion_ver/$1" title="Administrar"><i class="fa fa-search"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function listar_personas_data_reclamo($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$this->datatables->set_database('bono_secundario');
		$this->datatables
			->select("inscripcion.id, CONCAT(escuela.numero, ' - ', escuela.nombre) as escuela, persona.cuil, CONCAT(persona.apellido, ', ', persona.nombre) as persona, CONCAT(persona.calle, ' ', persona.calle_numero, ' ', persona.piso) as domicilio, CONCAT(persona.telefono_fijo, ' ', persona.telefono_movil) as telefonos, persona.email, inscripcion.fecha_cierre as fecha_inscripcion, inscripcion.fecha_recepcion, inscripcion.fecha_reclamo")
			->unset_column('id')
			->from('inscripcion')
			->join('persona', 'inscripcion.persona_id = persona.id')
			->join('escuela', 'escuela.id = inscripcion.escuela_id', 'left')
			->where("inscripcion.fecha_reclamo is NOT NULL AND inscripcion.escuela_id = $escuela_id")
			->add_column('edit', '<a href="bono_secundario/recepcion/recibir_reclamo/$1" title="Administrar"><i class="fa fa-download"></i></a>', 'id');

		echo $this->datatables->generate();
	}

	public function recibir($inscripcion_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $inscripcion_id == NULL || !ctype_digit($inscripcion_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$DB1 = $this->load->database('bono_secundario', TRUE);

		$this->load->model('bono_secundario/inscripcion_model');
		$this->inscripcion_model->set_database($DB1);
		$inscripcion = $this->inscripcion_model->get_one($inscripcion_id);
		if (empty($inscripcion)) {
			show_error('No se encontró la inscripción a recibir', 500, 'Registro no encontrado');
		}
		$this->load->model('bono_secundario/escuela_model');
		$this->escuela_model->set_database($DB1);
		$escuela = $this->escuela_model->get_one($inscripcion->escuela_id);
		if (!empty($escuela) && ($escuela->gem_id != $this->rol->entidad_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		if (isset($inscripcion->fecha_recepcion)) {
			$this->session->set_flashdata('error', "Está persona ya fue auditada anteriormente");
			redirect("bono_secundario/recepcion/pendientes/$inscripcion->escuela_id", 'refresh');
		}
		$this->load->model('bono_secundario/recepcion_model');
		$this->recepcion_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_bono_model');
		$this->persona_bono_model->set_database($DB1);
		$persona = $this->persona_bono_model->get_persona($inscripcion->persona_id);
		if (isset($persona) && !empty($persona)) {
			$this->load->model('persona_model');
			$persona_gem = $this->persona_model->get_persona_parcial('cuil', $persona->cuil);
			if (!empty($persona_gem)) {
				$persona_gem = $persona_gem;
				$data['persona_gem'] = $persona_gem;
				$data['fields_p_gem'] = $this->build_fields($this->persona_bono_model->fields, $persona_gem, TRUE);
				unset($persona->usuario_id);
				$array_persona = get_object_vars($persona);
				$array_persona_gem = get_object_vars($persona_gem);
				array_diff($array_persona, $array_persona_gem);
			}
		}

		$this->load->model('bono_secundario/persona_titulo_model');
		$this->persona_titulo_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_antiguedad_model');
		$this->persona_antiguedad_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_antecedente_model');
		$this->persona_antecedente_model->set_database($DB1);
		$this->load->model('juntas/persona_cargo_model');
		$this->persona_cargo_model->set_database($DB1);

		$this->form_validation->set_rules('observacion_recepcion', 'Observacion de Recepción', 'max_length[150]');
		if ($this->form_validation->run() === TRUE) {
			$this->recepcion_model->set_database($DB1);
			$DB1->trans_begin();
			$trans_ok = TRUE;
			if (isset($_POST) && !empty($_POST)) {
				if ($inscripcion_id !== $this->input->post('id')) {
					show_error('Esta solicitud no pasó el control de seguridad.');
				}
				$titulo_estado_ids = $this->input->post('titulo_estado');

				$count = count($titulo_estado_ids);
				for ($i = 0; $i < $count; $i++) {
					if ($trans_ok) {
						$trans_ok &= $this->persona_titulo_model->update(array(
							'id' => $titulo_estado_ids[$i],
							'estado' => 1
							), FALSE);
					}
				}

				$antecedente_estado_ids = $this->input->post('antecedente_estado');
				$count = count($antecedente_estado_ids);
				for ($i = 0; $i < $count; $i++) {
					if ($trans_ok) {
						$trans_ok &= $this->persona_antecedente_model->update(array(
							'id' => $antecedente_estado_ids[$i],
							'estado' => 1
							), FALSE);
					}
				}

				$antiguedad_estado_ids = $this->input->post('antiguedad_estado');
				$count = count($antiguedad_estado_ids);
				for ($i = 0; $i < $count; $i++) {
					if ($trans_ok) {
						$trans_ok &= $this->persona_antiguedad_model->update(array(
							'id' => $antiguedad_estado_ids[$i],
							'estado' => 1
							), FALSE);
					}
				}

				if ($DB1->trans_status() && $trans_ok) {
					$DB1->trans_commit();
					if ($this->input->post('recibir') == 'Recibir y aceptar seleccionado') {
						$trans_ok &= $this->recepcion_model->update(array(
							'id' => $inscripcion->id,
							'ultima_auditoria' => date("Y-m-d"),
							'observaciones_recepcion' => $this->input->post('observaciones_recepcion')
							), FALSE);
					} else {
						$trans_ok &= $this->recepcion_model->update(array(
							'id' => $inscripcion->id,
							'fecha_recepcion' => date("Y-m-d"),
							'observaciones_recepcion' => $this->input->post('observaciones_recepcion'),
							), FALSE);
						$mensaje = $this->validacion_ver($persona->id);
					}
				} else {
					$DB1->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->recepcion_model->get_error())
						$errors .= '<br>' . $this->recepcion_model->get_error();
					if ($this->persona_titulo_model->get_error())
						$errors .= '<br>' . $this->persona_titulo_model->get_error();
					if ($this->persona_antecedente_model->get_error())
						$errors .= '<br>' . $this->persona_antecedente_model->get_error();
					if ($this->persona_antiguedad_model->get_error())
						$errors .= '<br>' . $this->persona_antiguedad_model->get_error();

					$this->session->set_flashdata('error', $errors);
				}
				redirect("bono_secundario/recepcion/pendientes/$inscripcion->escuela_id", 'refresh');
			}
		}

		$titulos_persona = $this->persona_titulo_model->get(array(
			'join' => $this->persona_titulo_model->default_join,
			'persona_id' => $persona->id,
			'titulo_tipo_id' => '1',
			'borrado' => '0'
		));
		$postitulos_persona = $this->persona_titulo_model->get(array(
			'join' => $this->persona_titulo_model->default_join,
			'persona_id' => $persona->id,
			'titulo_tipo_id' => '2',
			'borrado' => '0'
		));
		$posgrados_persona = $this->persona_titulo_model->get(array(
			'join' => $this->persona_titulo_model->default_join,
			'persona_id' => $persona->id,
			'titulo_tipo_id' => '3',
			'borrado' => '0'
		));

		$antiguedad_persona = $this->persona_antiguedad_model->get(array('persona_id' => $persona->id,
			'join' => array(
				array('table' => 'antiguedad_tipo', 'where' => 'persona_antiguedad.antiguedad_tipo_id=antiguedad_tipo.id', 'type' => 'left', 'columnas' => array("antiguedad_tipo.descripcion")),
				array('table' => 'entidad_emisora', 'where' => 'entidad_emisora.id=persona_antiguedad.entidad_emisora_id', 'type' => 'left', 'columnas' => array("entidad as institucion"))
		)));

		$antecedentes_persona = $this->persona_antecedente_model->get(array('persona_id' => $persona->id,
			'join' => array(
				array('table' => 'modalidad', 'where' => 'modalidad.id=persona_antecedente.modalidad_id', 'type' => 'left', 'columnas' => array('descripcion as modalidad'))
		)));

		$persona_cargos = $this->persona_cargo_model->get(array(
			'documento_bono' => $persona->documento,
			'estado' => '1'
		));

		$data['persona_cargos'] = $persona_cargos;
		$data['antecedentes_persona'] = $antecedentes_persona;
		$data['antiguedad_persona'] = $antiguedad_persona;
		$data['titulos_persona'] = $titulos_persona;
		$data['postitulos_persona'] = $postitulos_persona;
		$data['posgrados_persona'] = $posgrados_persona;
		$data['error'] = (validation_errors() ? validation_errors() : ($this->recepcion_model->get_error() ? $this->recepcion_model->get_error() : $this->session->flashdata('error')));
		$data['message'] = $this->session->flashdata('message');
		$data['inscripcion'] = $inscripcion;
		$data['persona'] = $persona;
		$this->load->model('bono_secundario/inscripcion_model');
		$persona->observaciones_recepcion = '';
		unset($this->recepcion_model->fields['documento_tipo']['input_type']);
		unset($this->recepcion_model->fields['sexo']['input_type']);
		unset($this->recepcion_model->fields['sexo']['array']);
		$this->recepcion_model->fields['sexo']['readonly'] = TRUE;
		$this->recepcion_model->fields['observaciones_recepcion']['value'] = $inscripcion->observaciones_recepcion;
		$data['fields'] = $this->build_fields($this->recepcion_model->fields, $persona);
		$data['recibir'] = "Recibir y aceptar seleccionado";
		$data['recibir_forzar'] = "Recibir y no permitir modificaciones";
		$data['class'] = array('precepcion' => '', 'recibidos' => '');
		$data['title'] = 'Bono Secundario - Ver persona';
		$this->load_template('bono_secundario/recepcion/recepcion_recibir', $data);
	}

	public function recibir_reclamo($inscripcion_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $inscripcion_id == NULL || !ctype_digit($inscripcion_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$DB1 = $this->load->database('bono_secundario', TRUE);

		$this->load->model('bono_secundario/inscripcion_model');
		$this->inscripcion_model->set_database($DB1);
		$inscripcion = $this->inscripcion_model->get_one($inscripcion_id);
		if (empty($inscripcion)) {
			show_error('No se encontró la inscripción a recibir', 500, 'Registro no encontrado');
		}
		$this->load->model('bono_secundario/escuela_model');
		$this->escuela_model->set_database($DB1);
		$escuela = $this->escuela_model->get_one($inscripcion->escuela_id);
		if (!empty($escuela) && ($escuela->gem_id != $this->rol->entidad_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('bono_secundario/recepcion_model');
		$this->recepcion_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_bono_model');
		$this->persona_bono_model->set_database($DB1);
		$persona = $this->persona_bono_model->get_persona($inscripcion->persona_id);
		if (isset($persona) && !empty($persona)) {
			$this->load->model('persona_model');
			$persona_gem = $this->persona_model->get_persona_parcial('cuil', $persona->cuil);
			if (!empty($persona_gem)) {
				$persona_gem = $persona_gem;
				$data['persona_gem'] = $persona_gem;
				$data['fields_p_gem'] = $this->build_fields($this->persona_bono_model->fields, $persona_gem, TRUE);
				unset($persona->usuario_id);
				$array_persona = get_object_vars($persona);
				$array_persona_gem = get_object_vars($persona_gem);
				array_diff($array_persona, $array_persona_gem);
			}
		}

		$this->load->model('bono_secundario/persona_titulo_model');
		$this->persona_titulo_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_antiguedad_model');
		$this->persona_antiguedad_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_antecedente_model');
		$this->persona_antecedente_model->set_database($DB1);
		$this->load->model('juntas/persona_cargo_model');
		$this->persona_cargo_model->set_database($DB1);

		$this->form_validation->set_rules('observacion_recepcion', 'Observacion de Recepción', 'max_length[150]');
		if ($this->form_validation->run() === TRUE) {
			$this->recepcion_model->set_database($DB1);
			$DB1->trans_begin();
			$trans_ok = TRUE;
			if (isset($_POST) && !empty($_POST)) {
				lm($_POST);
				if ($inscripcion_id !== $this->input->post('id')) {
					show_error('Esta solicitud no pasó el control de seguridad.');
				}
				$titulo_estado_ids = $this->input->post('titulo_estado');

				$count = count($titulo_estado_ids);
				for ($i = 0; $i < $count; $i++) {
					if ($trans_ok) {
						$trans_ok &= $this->persona_titulo_model->update(array(
							'id' => $titulo_estado_ids[$i],
							'estado' => '0'
							), FALSE);
					}
				}

				$antecedente_estado_ids = $this->input->post('antecedente_estado');
				$count = count($antecedente_estado_ids);
				for ($i = 0; $i < $count; $i++) {
					if ($trans_ok) {
						$trans_ok &= $this->persona_antecedente_model->update(array(
							'id' => $antecedente_estado_ids[$i],
							'estado' => '0'
							), FALSE);
					}
				}

				$antiguedad_estado_ids = $this->input->post('antiguedad_estado');
				$count = count($antiguedad_estado_ids);
				for ($i = 0; $i < $count; $i++) {
					if ($trans_ok) {
						$trans_ok &= $this->persona_antiguedad_model->update(array(
							'id' => $antiguedad_estado_ids[$i],
							'estado' => '0'
							), FALSE);
					}
				}

				if ($DB1->trans_status() && $trans_ok) {
					$DB1->trans_commit();
					$trans_ok &= $this->recepcion_model->update(array(
						'id' => $inscripcion->id,
						'ultima_auditoria' => date("Y-m-d"),
						'fecha_reclamo' => 'NULL',
						'fecha_recepcion' => 'NULL',
						'observaciones_recepcion' => $this->input->post('observaciones_recepcion')
						), FALSE);
				} else {
					$DB1->trans_rollback();
					$errors = 'Ocurrió un error al intentar actualizar.';
					if ($this->recepcion_model->get_error())
						$errors .= '<br>' . $this->recepcion_model->get_error();
					if ($this->persona_titulo_model->get_error())
						$errors .= '<br>' . $this->persona_titulo_model->get_error();
					if ($this->persona_antecedente_model->get_error())
						$errors .= '<br>' . $this->persona_antecedente_model->get_error();
					if ($this->persona_antiguedad_model->get_error())
						$errors .= '<br>' . $this->persona_antiguedad_model->get_error();

					$this->session->set_flashdata('error', $errors);
				}
				redirect("bono_secundario/recepcion/pendientes/$inscripcion->escuela_id", 'refresh');
			}
		}

		$titulos_persona = $this->persona_titulo_model->get(array(
			'join' => $this->persona_titulo_model->default_join,
			'persona_id' => $persona->id,
			'titulo_tipo_id' => '1',
			'borrado' => '0'
		));
		$postitulos_persona = $this->persona_titulo_model->get(array(
			'join' => $this->persona_titulo_model->default_join,
			'persona_id' => $persona->id,
			'titulo_tipo_id' => '2',
			'borrado' => '0'
		));
		$posgrados_persona = $this->persona_titulo_model->get(array(
			'join' => $this->persona_titulo_model->default_join,
			'persona_id' => $persona->id,
			'titulo_tipo_id' => '3',
			'borrado' => '0'
		));

		$antiguedad_persona = $this->persona_antiguedad_model->get(array('persona_id' => $persona->id,
			'join' => array(
				array('table' => 'antiguedad_tipo', 'where' => 'persona_antiguedad.antiguedad_tipo_id=antiguedad_tipo.id', 'type' => 'left', 'columnas' => array("antiguedad_tipo.descripcion")),
				array('table' => 'entidad_emisora', 'where' => 'entidad_emisora.id=persona_antiguedad.entidad_emisora_id', 'type' => 'left', 'columnas' => array("entidad as institucion"))
		)));

		$antecedentes_persona = $this->persona_antecedente_model->get(array('persona_id' => $persona->id,
			'join' => array(
				array('table' => 'modalidad', 'where' => 'modalidad.id=persona_antecedente.modalidad_id', 'type' => 'left', 'columnas' => array('descripcion as modalidad'))
		)));

		$persona_cargos = $this->persona_cargo_model->get(array(
			'documento_bono' => $persona->documento,
			'estado' => '1'
		));

		$data['persona_cargos'] = $persona_cargos;
		$data['antecedentes_persona'] = $antecedentes_persona;
		$data['antiguedad_persona'] = $antiguedad_persona;
		$data['titulos_persona'] = $titulos_persona;
		$data['postitulos_persona'] = $postitulos_persona;
		$data['posgrados_persona'] = $posgrados_persona;
		$data['error'] = (validation_errors() ? validation_errors() : ($this->recepcion_model->get_error() ? $this->recepcion_model->get_error() : $this->session->flashdata('error')));
		$data['message'] = $this->session->flashdata('message');
		$data['inscripcion'] = $inscripcion;
		$data['persona'] = $persona;
		$this->load->model('bono_secundario/inscripcion_model');
		$persona->observaciones_recepcion = '';
		unset($this->recepcion_model->fields['documento_tipo']['input_type']);
		unset($this->recepcion_model->fields['sexo']['input_type']);
		unset($this->recepcion_model->fields['sexo']['array']);
		$this->recepcion_model->fields['sexo']['readonly'] = TRUE;
		$this->recepcion_model->fields['observaciones_recepcion']['value'] = $inscripcion->observaciones_recepcion;
		$data['fields'] = $this->build_fields($this->recepcion_model->fields, $persona);
		$data['txt_btn'] = "Aceptar reclamo";
		$data['title'] = 'Bono Secundario - Ver persona';
		$this->load_template('bono_secundario/recepcion/recepcion_reclamo', $data);
	}

	function pendientes($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('bono_secundario/escuela_model');
		$this->escuela_model->set_database($DB1);
		$escuela = $this->escuela_model->get(array('gem_id' => $this->rol->entidad_id));

		$tableData = array(
			'columns' => array(
				array('label' => 'N° Trámite', 'data' => 'id', 'class' => 'dt-body-right', 'width' => 5),
				array('label' => 'Escuela', 'data' => 'escuela', 'width' => 13),
				array('label' => 'CUIL', 'data' => 'cuil', 'width' => 7),
				array('label' => 'Nombre', 'data' => 'persona', 'width' => 14),
				array('label' => 'Domicilio', 'data' => 'domicilio', 'width' => 13),
				array('label' => 'Teléfonos', 'data' => 'telefonos', 'width' => 10),
				array('label' => 'Email', 'data' => 'email', 'width' => 10),
				array('label' => 'Fecha Inscripción', 'data' => 'fecha_inscripcion', 'render' => 'date', 'width' => 8),
				array('label' => 'Fecha Recepción', 'data' => 'fecha_recepcion', 'render' => 'date', 'width' => 8),
				array('label' => '', 'data' => 'edit', 'width' => 5, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'persona_table',
			'source_url' => "bono_secundario/recepcion/listar_personas_data_precepcion/$escuela_id",
			'reuse_var' => TRUE,
			'initComplete' => "complete_persona_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$this->load->model('bono_secundario/recepcion_model');
		$precepcion = $this->recepcion_model->get_precepcion($escuela_id);
		$recibidos = $this->recepcion_model->get_recibidos($escuela_id);
		$reclamos = $this->recepcion_model->get_reclamos($escuela_id);

		$data['escuela'] = $escuela[0];
		$data['cantidad_bonos'] = array('precepcion' => $precepcion, 'recibidos' => $recibidos, 'reclamos' => $reclamos);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['class'] = array('pendientes' => 'active btn-app-zetta-active', 'recibidos' => '', 'escuelas' => '', 'reclamos' => '');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Bono Secundario - Personas';
		$this->load_template('bono_secundario/persona/persona_listar', $data);
	}

	function recibidos($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('bono_secundario/escuela_model');
		$this->escuela_model->set_database($DB1);
		$escuela = $this->escuela_model->get(array('gem_id' => $this->rol->entidad_id));


		$tableData = array(
			'columns' => array(
				array('label' => 'N° Trámite', 'data' => 'id', 'class' => 'dt-body-right', 'width' => 5),
				array('label' => 'Escuela', 'data' => 'escuela', 'width' => 13),
				array('label' => 'CUIL', 'data' => 'cuil', 'width' => 10),
				array('label' => 'Nombre', 'data' => 'persona', 'width' => 11),
				array('label' => 'Domicilio', 'data' => 'domicilio', 'width' => 13),
				array('label' => 'Teléfonos', 'data' => 'telefonos', 'width' => 13),
				array('label' => 'Email', 'data' => 'email', 'width' => 10),
				array('label' => 'Fecha Inscripción', 'data' => 'fecha_inscripcion', 'render' => 'date', 'width' => 10),
				array('label' => 'Fecha Recepción', 'data' => 'fecha_recepcion', 'render' => 'date', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 5, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'persona_table',
			'source_url' => "bono_secundario/recepcion/listar_personas_data_recibidos/$escuela_id",
			'reuse_var' => TRUE,
			'initComplete' => "complete_persona_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$this->load->model('bono_secundario/recepcion_model');
		$precepcion = $this->recepcion_model->get_precepcion($escuela_id);
		$recibidos = $this->recepcion_model->get_recibidos($escuela_id);
		$reclamos = $this->recepcion_model->get_reclamos($escuela_id);

		$data['escuela'] = $escuela[0];
		$data['cantidad_bonos'] = array('precepcion' => $precepcion, 'recibidos' => $recibidos, 'reclamos' => $reclamos);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['class'] = array('pendientes' => '', 'recibidos' => 'active btn-app-zetta-active', 'escuelas' => '', 'reclamos' => '');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Bono Secundario - Personas';
		$this->load_template('bono_secundario/persona/persona_listar', $data);
	}

	function reclamos($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('bono_secundario/escuela_model');
		$this->escuela_model->set_database($DB1);
		$escuela = $this->escuela_model->get(array('gem_id' => $this->rol->entidad_id));


		$tableData = array(
			'columns' => array(
				array('label' => 'N° Trámite', 'data' => 'id', 'class' => 'dt-body-right', 'width' => 5),
				array('label' => 'CUIL', 'data' => 'cuil', 'width' => 10),
				array('label' => 'Nombre', 'data' => 'persona', 'width' => 11),
				array('label' => 'Domicilio', 'data' => 'domicilio', 'width' => 13),
				array('label' => 'Teléfonos', 'data' => 'telefonos', 'width' => 13),
				array('label' => 'Email', 'data' => 'email', 'width' => 10),
				array('label' => 'Fecha Inscripción', 'data' => 'fecha_inscripcion', 'render' => 'date', 'width' => 10),
				array('label' => 'Fecha Recepción', 'data' => 'fecha_recepcion', 'render' => 'date', 'width' => 10),
				array('label' => 'Fecha Reclamo', 'data' => 'fecha_reclamo', 'render' => 'date', 'width' => 10),
				array('label' => '', 'data' => 'edit', 'width' => 5, 'class' => 'dt-body-center', 'responsive_class' => 'all', 'sortable' => 'false', 'searchable' => 'false')
			),
			'table_id' => 'persona_table',
			'source_url' => "bono_secundario/recepcion/listar_personas_data_reclamo/$escuela_id",
			'reuse_var' => TRUE,
			'initComplete' => "complete_persona_table",
			'footer' => TRUE,
			'dom' => '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>'
		);
		$this->load->model('bono_secundario/recepcion_model');
		$precepcion = $this->recepcion_model->get_precepcion($escuela_id);
		$recibidos = $this->recepcion_model->get_recibidos($escuela_id);
		$reclamos = $this->recepcion_model->get_reclamos($escuela_id);

		$data['escuela'] = $escuela[0];
		$data['cantidad_bonos'] = array('precepcion' => $precepcion, 'recibidos' => $recibidos, 'reclamos' => $reclamos);
		$data['html_table'] = buildHTML($tableData);
		$data['js_table'] = buildJS($tableData);
		$data['error'] = $this->session->flashdata('error');
		$data['class'] = array('pendientes' => '', 'recibidos' => '', 'escuelas' => '', 'reclamos' => 'active btn-app-zetta-active');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Bono Secundario - Personas';
		$this->load_template('bono_secundario/persona/persona_listar', $data);
	}

	public function inscripcion_ver($inscripcion_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $inscripcion_id == NULL || !ctype_digit($inscripcion_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$DB1 = $this->load->database('bono_secundario', TRUE);

		$this->load->model('bono_secundario/inscripcion_model');
		$this->inscripcion_model->set_database($DB1);
		$inscripcion = $this->inscripcion_model->get_one($inscripcion_id);

		if (empty($inscripcion)) {
			show_error('No se encontró la inscripción a recibir', 500, 'Registro no encontrado');
		}
		if (empty($inscripcion->fecha_recepcion)) {
			show_error('No se encontró la inscripción a ver', 500, 'Registro no encontrado');
		}

		$this->load->model('bono_secundario/escuela_model');
		$this->escuela_model->set_database($DB1);
		$escuela = $this->escuela_model->get_one($inscripcion->escuela_id);
		if (!empty($escuela) && ($escuela->gem_id != $this->rol->entidad_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$this->load->model('bono_secundario/persona_bono_model');
		$persona = $this->persona_bono_model->get_persona($inscripcion->persona_id);
		$this->load->model('bono_secundario/recepcion_model');
		$this->recepcion_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_titulo_model');
		$this->persona_titulo_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_antiguedad_model');
		$this->persona_antiguedad_model->set_database($DB1);
		$this->load->model('bono_secundario/persona_antecedente_model');
		$this->persona_antecedente_model->set_database($DB1);
		$this->load->model('juntas/persona_cargo_model');
		$this->persona_cargo_model->set_database($DB1);

		$this->load->model('bono/persona_titulo_model');
		$titulos_persona = $this->persona_titulo_model->get(array(
			'join' => $this->persona_titulo_model->default_join,
			'persona_id' => $persona->id,
			'titulo_tipo_id' => '1',
			'borrado' => '0'
		));
		$postitulos_persona = $this->persona_titulo_model->get(array(
			'join' => $this->persona_titulo_model->default_join,
			'persona_id' => $persona->id,
			'titulo_tipo_id' => '2',
			'borrado' => '0'
		));
		$posgrados_persona = $this->persona_titulo_model->get(array(
			'join' => $this->persona_titulo_model->default_join,
			'persona_id' => $persona->id,
			'titulo_tipo_id' => '3',
			'borrado' => '0'
		));

		$antiguedad_persona = $this->persona_antiguedad_model->get(array('persona_id' => $persona->id,
			'join' => array(
				array('table' => 'antiguedad_tipo', 'where' => 'persona_antiguedad.antiguedad_tipo_id=antiguedad_tipo.id', 'type' => 'left', 'columnas' => array("antiguedad_tipo.descripcion")),
				array('table' => 'entidad_emisora', 'where' => 'entidad_emisora.id=persona_antiguedad.entidad_emisora_id', 'type' => 'left', 'columnas' => array("entidad as institucion"))
		)));

		$antecedentes_persona = $this->persona_antecedente_model->get(array('persona_id' => $persona->id,
			'join' => array(
				array('table' => 'modalidad', 'where' => 'modalidad.id=persona_antecedente.modalidad_id', 'type' => 'left', 'columnas' => array('descripcion as modalidad'))
		)));

		$persona_cargos = $this->persona_cargo_model->get(array(
			'documento_bono' => $persona->documento,
			'estado' => '1'
		));

		$data['persona_cargos'] = $persona_cargos;
		$data['antecedentes_persona'] = $antecedentes_persona;
		$data['antiguedad_persona'] = $antiguedad_persona;
		$data['titulos_persona'] = $titulos_persona;
		$data['postitulos_persona'] = $postitulos_persona;
		$data['posgrados_persona'] = $posgrados_persona;
		$data['inscripcion'] = $inscripcion;
		$data['persona'] = $persona;
		$this->load->model('bono_secundario/inscripcion_model');
		$persona->observaciones_recepcion = '';
		$data['fields'] = $this->build_fields($this->recepcion_model->fields, $persona, TRUE);
		$data['class'] = array('precepcion' => '', 'recibidos' => '');
		$data['title'] = 'Bono Secundario - Ver persona';
		$this->load_template('bono_secundario/recepcion/recepcion_inscripcion_ver', $data);
	}

	public function modal_editar_dp($persona_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $persona_id == NULL || !ctype_digit($persona_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('bono_secundario/persona_bono_model');
		$this->persona_bono_model->set_database($DB1);
		$persona = $this->persona_bono_model->get_persona($persona_id);
		$this->set_model_validation_rules($this->persona_bono_model);
		if (empty($persona)) {
			$this->modal_error('No se encontró el registro a ver', 'Registro no encontrado');
			return;
		} else {
			$DB1 = $this->load->database('bono_secundario', TRUE);
			$this->load->model('bono_secundario/inscripcion_model');
			$this->inscripcion_model->set_database($DB1);
			$inscripcion = $this->inscripcion_model->get(array(
				'join' => array(
					array('table' => 'persona', 'where' => "persona.id=inscripcion.persona_id and persona.cuil = '$persona->cuil'", 'columnas' => array('persona.cuil'))
				)
			));
			if (isset($inscripcion) && !empty($inscripcion)) {
				$inscripcion = $inscripcion[0];
				$data['inscripcion'] = $inscripcion;
			}
		}
		$this->load->model('localidad_model');
		$this->load->model('sexo_model');
		$this->load->model('documento_tipo_model');
		$this->array_localidad_control = $array_localidad = $this->get_array('localidad', 'localidad', 'id', array(
			'select' => array('localidad.id', "CONCAT(departamento.descripcion, ' - ', localidad.descripcion) as localidad"),
			'join' => array(array('departamento', 'departamento.id = localidad.departamento_id')),
			'sort_by' => 'departamento.descripcion, localidad.descripcion'
			), array('' => '-- Seleccionar localidad --'));
		$this->array_sexo_control = $array_sexo = $this->get_array('sexo', 'descripcion', 'id', null, array('' => '-- Seleccionar --'));
		$this->array_documento_tipo_control = $array_documento_tipo = $this->get_array('documento_tipo', 'descripcion_corta', 'id', null, array('' => '-- Seleccionar --'));

		if (isset($_POST) && !empty($_POST)) {
			if ($inscripcion->persona_id !== $this->input->post('id')) {
				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
			}
			if ($this->form_validation->run() === TRUE) {
				$trans_ok = TRUE;
				$trans_ok &= $this->persona_bono_model->update(array(
					'id' => $this->input->post('id'),
					'apellido' => $this->input->post('apellido'),
					'nombre' => $this->input->post('nombre'),
					'fecha_nacimiento' => $this->get_date_sql('fecha_nacimiento'),
					'sexo_id' => $this->input->post('sexo'),
					'calle' => $this->input->post('calle'),
					'calle_numero' => $this->input->post('calle_numero'),
					'piso' => $this->input->post('piso'),
					'departamento' => $this->input->post('departamento'),
					'localidad_id' => $this->input->post('localidad'),
					'telefono_fijo' => $this->input->post('telefono_fijo'),
					'telefono_movil' => $this->input->post('telefono_movil'),
					'codigo_postal' => $this->input->post('codigo_postal'),
				));
				if ($trans_ok) {
					$this->session->set_flashdata('message', $this->persona_bono_model->get_msg());
					redirect("bono_secundario/recepcion/recibir/$inscripcion->id", 'refresh');
				}
			}
		}
		$this->persona_bono_model->fields['localidad']['array'] = $array_localidad;
		$this->persona_bono_model->fields['sexo']['array'] = $array_sexo;
		unset($this->persona_bono_model->fields['documento_tipo']['input_type']);
		$data['fields'] = $this->build_fields($this->persona_bono_model->fields, $persona);

		$data['persona'] = $persona;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['txt_btn'] = 'Editar';
		$data['title'] = 'Editar Persona BONO SECUNDARIO';
		$this->load->view('bono_secundario/persona/persona_editar_modal', $data);
	}

	public function excel($escuela_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $escuela_id == NULL || !ctype_digit($escuela_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}
		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('bono_secundario/escuela_model');
		$this->escuela_model->set_database($DB1);
		$escuela = $this->escuela_model->get(array('gem_id' => $this->rol->entidad_id));
		if (empty($escuela)) {
			show_error('No se encontró el registro de la escuela', 500, 'Registro no encontrado');
		}

		$campos = array(
			'A' => array('N° Trámite', 10),
			'B' => array('Escuela', 25),
			'C' => array('CUIL', 15),
			'D' => array('Nombre', 40),
			'E' => array('Telefonos', 30),
			'F' => array('Email', 40),
			'G' => array('Fecha de Inscripción', 20),
		);
		$precepcion_db = $DB1->select('inscripcion.id, CONCAT(escuela.numero, \' - \', escuela.nombre) as escuela, persona.cuil, CONCAT(persona.apellido, \', \', persona.nombre) as persona, CONCAT(persona.telefono_fijo, \' \', persona.telefono_movil) telefonos, persona.email, DATE_FORMAT(inscripcion.fecha_cierre, "%d/%m/%Y") as fecha_inscripcion')
				->from('inscripcion')
				->join('persona', 'inscripcion.persona_id = persona.id')
				->join('escuela', 'escuela.id = inscripcion.escuela_id', 'left')
				->where("inscripcion.fecha_recepcion is NULL AND inscripcion.fecha_cierre IS NOT NULL AND inscripcion.escuela_id = $escuela_id")
				->order_by('inscripcion.id, persona')
				->get()->result_array();

		if (!empty($precepcion_db)) {
			$this->exportar_excel(array('title' => "Inscriptos pendientes de recepción $escuela_id"), $campos, $precepcion_db);
		} else {
			$this->session->set_flashdata('error', 'Sin datos para el reporte seleccionado');
			redirect("bono_secundario/recepcion/$escuela_id", 'refresh');
		}
	}

	public function validacion_ver($persona_id = NULL) {
		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $persona_id == NULL || !ctype_digit($persona_id)) {
			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
		}

		$DB1 = $this->load->database('bono_secundario', TRUE);
		$this->load->model('bono_secundario/persona_bono_model');
		$this->persona_bono_model->set_database($DB1);

		$persona_cuil = $this->persona_bono_model->get(array('id' => $persona_id,
			'join' => array(
				array('documento_tipo', 'documento_tipo.id = persona.documento_tipo_id', 'left', array('documento_tipo.descripcion_corta as documento_tipo')),
				array('localidad', 'localidad.id = persona.localidad_id', 'left', array("CONCAT(d_localidad.descripcion, ' - ', localidad.descripcion) as localidad")),
				array('departamento d_localidad', 'd_localidad.id = localidad.departamento_id', 'left'),
				array('sexo', 'sexo.id = persona.sexo_id', 'left', array('sexo.descripcion as sexo'))
		)));

		if (empty($persona_cuil)) {
			redirect('bono/persona/agregar', 'refresh');
		} else {
			$persona = $persona_cuil;
		}

		$this->load->model('bono_secundario/inscripcion_model');
		$this->inscripcion_model->set_database($DB1);
		$inscripcion = $this->inscripcion_model->get(array('persona_id' => $persona->id));
		if (empty($inscripcion[0]->fecha_cierre)) {
			redirect('bono/inscripcion/realizar_inscripcion', 'refresh');
		}

		$this->load->model('juntas/persona_cargo_model');
		$this->persona_cargo_model->set_database($DB1);
		$persona_cargos = $this->persona_cargo_model->get(array(
			'documento_bono' => $persona->documento,
			'estado' => '1'
		));
		$this->load->model('juntas/persona_titulo_model');
		$this->persona_titulo_model->set_database($DB1);
		$d_titulos = $this->persona_titulo_model->get(array(
			'join' => $this->persona_titulo_model->default_join,
			'persona_id' => $persona->id,
			'titulo_tipo_id' => '1',
			'borrado' => '0'
		));
		$d_postitulos = $this->persona_titulo_model->get(array(
			'join' => $this->persona_titulo_model->default_join,
			'persona_id' => $persona->id,
			'titulo_tipo_id' => '2',
			'borrado' => '0'
		));
		$d_posgrados = $this->persona_titulo_model->get(array(
			'join' => $this->persona_titulo_model->default_join,
			'persona_id' => $persona->id,
			'titulo_tipo_id' => '3',
			'borrado' => '0'
		));
		$d_antecedente = $this->inscripcion_model->get_antecedente($persona->id);
		$d_antiguedad = $this->inscripcion_model->get_antiguedad($persona->id);
		$d_inscripcion = $this->inscripcion_model->get_one($inscripcion[0]->id);
		$data['d_inscripcion'] = $d_inscripcion;
		$data['persona_cargos'] = $persona_cargos;
		$data['d_antiguedad'] = $d_antiguedad;
		$data['d_antecedente'] = $d_antecedente;
		$data['persona'] = $persona;
		$data['d_titulos'] = $d_titulos;
		$data['d_postitulos'] = $d_postitulos;
		$data['d_posgrados'] = $d_posgrados;
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		$data['title'] = 'Bono Secundario - Ver validación';
		$content = $this->load->view('bono_secundario/validacion/ver_validacion', $data, TRUE);
		$this->load->helper('mpdf');
		$pdf = exportarMPDF($content, 'plugins/kv-mpdf-bootstrap.min.css', "Validación-$persona->apellido $persona->nombre", "$persona->id - $persona->apellido, $persona->nombre", '|{PAGENO} de {nb}|', 'A4', '', 'S', FALSE, FALSE);
		$enviado = $this->send_email($persona->email, $pdf, "$persona->apellido, $persona->nombre");
		return $enviado;
	}

	public function send_email($to_email, $pdf, $nombre) {
		$this->load->library('email');
		$config['smtp_host'] = 'smtp.mendoza.gov.ar';
		$config['mailtype'] = 'html';
		$config['protocol'] = 'smtp';
		$config['smtp_port'] = '25';
		$config['charset'] = 'utf-8';
		$config['smtp_user'] = 'dge-jcmdes@mendoza.gov.ar';
		$config['smtp_pass'] = 'tai9aKah';
		$this->email->initialize($config);
		$this->email->from('dge-jcmdes@mendoza.gov.ar', 'Bono Secundario');
		$this->email->to($to_email);
		$this->email->subject('Constancia Validación de Bono Secundario');
		$this->email->attach($pdf, 'attachment', "constancia_validacion_$nombre.pdf", 'application/pdf');

		$mensaje = "<!DOCTYPE html><html><head><title>Constancia Bono Secundario $nombre</title><meta charset=UTF-8><meta name=viewport content=width=device-width, initial-scale=1.0></head>"
			. "<body>"
			. "<div>"
			. "Estimado Docente<br><br>
Se remite adjunto comprobante de la validación efectuada. Este comprobante reemplaza cualquiera que haya recibido con anterioridad .<br>
Atentamente <br>"
			. "</div>"
			. "</body>"
			. "</html>";
		$this->email->message($mensaje);
		return $this->email->send();
	}
//	public function mover_a_pendiente($inscripcion_id) {
//		if (!in_array($this->rol->codigo, $this->roles_permitidos) || $inscripcion_id == NULL || !ctype_digit($inscripcion_id)) {
//			show_error('No tiene permisos para la acción solicitada', 500, 'Acción no autorizada');
//		}
//
//		$DB1 = $this->load->database('bono_secundario', TRUE);
//		$this->load->model('bono_secundario/inscripcion_model');
//		$this->inscripcion_model->set_database($DB1);
//		$inscripcion = $this->inscripcion_model->get_one($inscripcion_id);
//		if (empty($inscripcion)) {
//			$this->modal_error('No se encontró el registro a ver', 'Acción no autorizada');
//			return;
//		}
//
//		$this->load->model('bono_secundario/persona_bono_model');
//		$this->persona_bono_model->set_database($DB1);
//		$persona = $this->persona_bono_model->get_one($inscripcion->persona_id);
//		if (empty($persona)) {
//			$this->modal_error('No se encontró el registro a ver', 'Acción no autorizada');
//			return;
//		}
//
//		$this->load->model('bono_secundario/escuela_model');
//		$this->escuela_model->set_database($DB1);
//		$escuela = $this->escuela_model->get_one($inscripcion->escuela_id);
//		if (!empty($escuela) && ($escuela->gem_id != $this->rol->entidad_id)) {
//			$this->modal_error('No tiene permisos para la acción solicitada', 'Acción no autorizada');
//			return;
//		}
//
//		if (isset($inscripcion) && !empty($inscripcion)) {
//			$model_inscripcion = new stdClass();
//			$model_inscripcion->fields = array(
//				'persona' => array('label' => 'Persona', 'readonly' => 'TRUE'));
//			if (!empty($persona->apellido) && !empty($persona->nombre)) {
//				$model_inscripcion->fields['persona']['value'] = "$persona->cuil | $persona->apellido, $persona->nombre";
//			} else {
//				$model_inscripcion->fields['persona']['value'] = "$persona->cuil";
//			}
//			$estado = $this->inscripcion_model->get_estados($persona->id);
//			if ($estado == '1') {
//				$this->modal_error('Ya se encuentra toda la documentación validada', 'Acción no autorizada');
//				return;
//			}
//			$data['estado'] = $estado;
//		} else {
//			$this->modal_error('Esta persona no cerró su inscripción todavía', 'Acción no autorizada');
//			return;
//		}
//
//		if (isset($_POST) && !empty($_POST)) {
//			if ($inscripcion->id !== $this->input->post('id')) {
//				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
//			}
//			if ($inscripcion->escuela_id !== $this->input->post('escuela_id')) {
//				$this->modal_error('Esta solicitud no pasó el control de seguridad.', 'Acción no autorizada');
//			}
//			$this->db->trans_begin();
//			$trans_ok = TRUE;
//
//			$trans_ok &= $this->inscripcion_model->update(array('id' => $inscripcion->id, 'fecha_recepcion' => 'NULL'));
//
//			if ($this->db->trans_status() && $trans_ok) {
//				$this->db->trans_commit();
//				$this->session->set_flashdata('message', $this->inscripcion_model->get_msg());
//				redirect("bono_secundario/recepcion/pendientes/$inscripcion->escuela_id", 'refresh');
//			} else {
//				$this->db->trans_rollback();
//				$this->session->set_flashdata('error', $this->inscripcion_model->get_error());
//				redirect("bono_secundario/recepcion/inscripcion_ver/$inscripcion-id", 'refresh');
//			}
//		}
//
//		$data['fields'] = $this->build_fields($model_inscripcion->fields);
//		$data['persona'] = $persona;
//		$data['txt_btn'] = 'Volver a validar';
//		$data['inscripcion'] = $inscripcion;
//		$data['title'] = 'Deshacer Validación';
//		$this->load->view('bono_secundario/validacion/validacion_abrir_modal', $data);
//	}
}
/* End of file Recepcion.php */
	/* Location: ./application/modules/bono/controllers/Recepcion.php */
	