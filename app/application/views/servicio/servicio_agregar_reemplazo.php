<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Agregar reemplazo <?php echo "Esc. $escuela->nombre_corto" ?> - <label class="label label-default"><?php echo $mes; ?></label>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="servicio/listar/<?php echo $escuela->id . '/'; ?>">Servicios</a></li>
			<li class="active">Agregar reemplazo</li>
		</ol>
	</section>
	<section class="content">
		<?php if (!empty($error)) : ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-ban"></i> Error!</h4>
				<?php echo $error; ?>
			</div>
		<?php endif; ?>
		<?php if (!empty($message)) : ?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-check"></i> OK!</h4>
				<?php echo $message; ?>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agregar_reemplazo')); ?>
					<div class="box-body">
						<div class="row">
							<div class="form-group col-sm-4">
								<?php echo $fields['documento_tipo']['label']; ?>
								<?php echo $fields['documento_tipo']['form']; ?>
							</div>
							<div class="form-group col-sm-4">
								<?php echo $fields['documento']['label']; ?>
								<?php echo $fields['documento']['form']; ?>
							</div>
							<div class="form-group col-sm-4">
								<label>&nbsp;</label><br/>
								<button class="btn btn-default" id="btn-search" type="button">
									<i class="fa fa-search"></i>
								</button>
								<button class="btn btn-default" id="btn-clear" type="button">
									<i class="fa fa-times"></i>
								</button>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-sm-2">
								<?php echo $fields['cuil']['label']; ?>
								<?php echo $fields['cuil']['form']; ?>
							</div>
							<div class="form-group col-sm-3">
								<?php echo $fields['apellido']['label']; ?>
								<?php echo $fields['apellido']['form']; ?>
							</div>
							<div class="form-group col-sm-3">
								<?php echo $fields['nombre']['label']; ?>
								<?php echo $fields['nombre']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields['sexo']['label']; ?>
								<?php echo $fields['sexo']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields['fecha_nacimiento']['label']; ?>
								<?php echo $fields['fecha_nacimiento']['form']; ?>
							</div>
						</div>
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#tab_persona" aria-controls="tab_persona" role="tab" data-toggle="tab">Datos Persona</a></li>
							<li role="presentation"><a id="a_tab_servicio" href="#tab_servicio" aria-controls="tab_servicio" role="tab" data-toggle="tab">Servicio</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="tab_persona">
								<div class="row">
									<div class="form-group col-sm-3">
										<?php echo $fields['calle']['label']; ?>
										<?php echo $fields['calle']['form']; ?>
									</div>
									<div class="form-group col-sm-2">
										<?php echo $fields['calle_numero']['label']; ?>
										<?php echo $fields['calle_numero']['form']; ?>
									</div>
									<div class="form-group col-sm-2">
										<?php echo $fields['departamento']['label']; ?>
										<?php echo $fields['departamento']['form']; ?>
									</div>
									<div class="form-group col-sm-2">
										<?php echo $fields['piso']['label']; ?>
										<?php echo $fields['piso']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['localidad']['label']; ?>
										<?php echo $fields['localidad']['form']; ?>
									</div>
									<div class="form-group col-md-2">
										<?php echo $fields['codigo_postal']['label']; ?>
										<?php echo $fields['codigo_postal']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['barrio']['label']; ?>
										<?php echo $fields['barrio']['form']; ?>
									</div>
									<div class="form-group col-sm-2">
										<?php echo $fields['manzana']['label']; ?>
										<?php echo $fields['manzana']['form']; ?>
									</div>
									<div class="form-group col-sm-2">
										<?php echo $fields['casa']['label']; ?>
										<?php echo $fields['casa']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['nivel_estudio']['label']; ?>
										<?php echo $fields['nivel_estudio']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['ocupacion']['label']; ?>
										<?php echo $fields['ocupacion']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['telefono_fijo']['label']; ?>
										<?php echo $fields['telefono_fijo']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['prestadora']['label']; ?>
										<?php echo $fields['prestadora']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['telefono_movil']['label']; ?>
										<?php echo $fields['telefono_movil']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['estado_civil']['label']; ?>
										<?php echo $fields['estado_civil']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['obra_social']['label']; ?>
										<?php echo $fields['obra_social']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['grupo_sanguineo']['label']; ?>
										<?php echo $fields['grupo_sanguineo']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['depto_nacimiento']['label']; ?>
										<?php echo $fields['depto_nacimiento']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['lugar_traslado_emergencia']['label']; ?>
										<?php echo $fields['lugar_traslado_emergencia']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['nacionalidad']['label']; ?>
										<?php echo $fields['nacionalidad']['form']; ?>
									</div>
									<div class="form-group col-sm-12">
										<button type="button" onclick="$('#a_tab_servicio').tab('show');" id="btn-tab" disabled class="btn btn-primary pull-right">Seleccionar persona</button>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab_servicio">
								<div class="row">
									<div class="form-group col-md-4">
										<?php echo $fields_servicio['regimen']['label']; ?>
										<?php echo $fields_servicio['regimen']['form']; ?>
									</div>
									<?php if ($cargo->regimen_tipo_id === '2'): ?>
										<div class="form-group col-md-1">
											<?php echo $fields_servicio['carga_horaria']['label']; ?>
											<?php echo $fields_servicio['carga_horaria']['form']; ?>
										</div>
									<?php endif; ?>
									<div class="form-group col-md-4">
										<?php echo $fields_servicio['division']['label']; ?>
										<?php echo $fields_servicio['division']['form']; ?>
									</div>
									<div class="form-group col-md-3">
										<?php echo $fields_servicio['espacio_curricular']['label']; ?>
										<?php echo $fields_servicio['espacio_curricular']['form']; ?>
									</div>
									<div class="form-group col-md-3">
										<?php echo $fields_servicio['situacion_revista']['label']; ?>
										<?php echo $fields_servicio['situacion_revista']['form']; ?>
									</div>
									<div class="form-group col-md-2">
										<?php echo $fields_servicio['fecha_alta']['label']; ?>
										<?php echo $fields_servicio['fecha_alta']['form']; ?>
									</div>
									<div class="form-group col-md-2">
										<?php echo $fields_servicio['fecha_baja']['label']; ?>
										<?php echo $fields_servicio['fecha_baja']['form']; ?>
									</div>
									<div class="form-group col-md-2">
										<?php echo $fields_servicio['dias']['label']; ?>
										<?php echo $fields_servicio['dias']['form']; ?>
									</div>
									<?php if ($cargo->regimen_tipo_id === '2'): ?>
										<div class="form-group col-md-3">
											<?php echo $fields_servicio['obligaciones']['label']; ?>
											<?php echo $fields_servicio['obligaciones']['form']; ?>
										</div>
									<?php endif; ?>
									<?php if (substr($cargo->regimen, 1, 6) === '560201'): ?>
										<div class="form-group col-md-3">
											<?php echo $fields_servicio['celador_concepto']['label']; ?>
											<?php echo $fields_servicio['celador_concepto']['form']; ?>
										</div>
									<?php endif; ?>
								</div>
								<?php if (!empty($novedades)): ?>
									<br/>
									<table class="table table-condensed">
										<thead>
											<tr>
												<th colspan="7" class="text-center">Seleccione la novedad sobre la que inicia el reemplazo</th>
											</tr>
											<tr>
												<th></th>
												<th>Artículo</th>
												<th>Novedad</th>
												<th>Desde</th>
												<th>Hasta</th>
												<th>Días</th>
												<th>Obligaciones</th>
											</tr>
										</thead>
										<tbody class="well">
											<?php foreach ($novedades as $novedad): ?>
												<tr>
													<td><input data-desde="<?php echo (new DateTime($novedad->fecha_desde))->format('d/m/Y'); ?>" type="radio" name="novedad" value="<?php echo $novedad->id; ?>"></td>
													<td><?php echo "$novedad->articulo-$novedad->inciso"; ?></td>
													<td><?php echo $novedad->descripcion_corta; ?></td>
													<td><?php echo (new DateTime($novedad->fecha_desde))->format('d/m/Y'); ?></td>
													<td><?php echo (new DateTime($novedad->fecha_hasta))->format('d/m/Y'); ?></td>
													<td><?php echo $novedad->dias; ?></td>
													<td><?php echo $novedad->obligaciones; ?></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								<?php endif; ?>
								<div class="row">
									<?php if ($cargo->regimen_tipo_id === '2' && !empty($horarios)): ?>
										<div class="col-sm-6">
											<label>Horas Cátedra Semanales</label>
											<table class="table table-condensed text-center text-sm">
												<thead>
													<tr>
														<?php foreach ($horarios as $horario): ?>
															<th><?php echo mb_substr($horario->dia, 0, 2); ?></th>
														<?php endforeach; ?>
													</tr>
												</thead>
												<tbody>
													<tr>
														<?php foreach ($horarios as $horario): ?>
															<td class="text-center" id="dia_<?php echo $horario->dia_id; ?>"><?php echo $horario->cantidad; ?></td>
														<?php endforeach; ?>
													</tr>
												</tbody>
											</table>
										</div>
									<?php endif; ?>
								</div>
								<hr>
								<div class="row" id="row-funcion">
									<div class="form-group col-md-6">
										<?php echo $fields_funcion['funcion']['label']; ?>
										<?php echo $fields_funcion['funcion']['form']; ?>
									</div>
									<div class="form-group col-md-6 campos-funcion">
										<?php echo $fields_funcion['norma']['label']; ?>
										<?php echo $fields_funcion['norma']['form']; ?>
									</div>
									<div class="form-group col-md-6 campos-funcion">
										<?php echo $fields_funcion['tipo_destino']['label']; ?>
										<?php echo $fields_funcion['tipo_destino']['form']; ?>
									</div>
									<div class="form-group col-md-6 campos-funcion">
										<?php echo $fields_funcion['destino']['label']; ?>
										<?php echo $fields_funcion['destino']['form']; ?>
									</div>
									<div class="form-group col-md-3 campos-funcion">
										<?php echo $fields_funcion['tarea']['label']; ?>
										<?php echo $fields_funcion['tarea']['form']; ?>
									</div>
									<div class="form-group col-md-3 campos-funcion">
										<?php echo $fields_funcion['carga_horaria']['label']; ?>
										<?php echo $fields_funcion['carga_horaria']['form']; ?>
									</div>
									<div class="form-group col-md-12">
										<?php echo $fields_servicio['observaciones']['label']; ?>
										<?php echo $fields_servicio['observaciones']['form']; ?>
									</div>
								</div>
								<input type="hidden" name="persona_id" value="" id="persona_id"/>
								<input type="hidden" name="documento_tipo" value="" id="documento_tipo_id"/>
								<button type="submit" disabled id="btn-submit" class="btn btn-primary pull-right">Agregar Reemplazo</button>
								<span class="bg-red text-bold pull-right" id="message-error-fecha" hidden="true" style="border-radius: 2px; margin-right: 10px;">&nbsp;La fecha de alta debe ser mayor igual a la fecha de inicio de novedad&nbsp;</span>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="servicio/listar/<?php echo $escuela->id . '/'; ?>" title="Cancelar">Cancelar</a>
						<span class="badge bg-red" id="alerta"></span>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var xhr;
	var select_tipo_destino, $select_tipo_destino;
	var select_destino, $select_destino;
	$(document).ready(function() {
		$('select.selectize#tipo_destino').removeClass('selectize');
		$('select.selectize#destino').removeClass('selectize');
		agregar_eventos($('#form_agregar_reemplazo'));
		$('#fecha_alta,#fecha_baja').datepicker({
			format: "dd/mm/yyyy",
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true,
			startDate: '<?= (($escuela->dependencia_id == '1') ? $fecha_inicio_actual : '') ?>',
			endDate: '<?= (($escuela->dependencia_id == '1') ? $fecha_fin_actual : '') ?>'
		}).on('changeDate', function(e) {
			validarFechas();
		});
		$('#fecha_alta,#fecha_baja').change(calculo_dias_obligaciones);
		$('#check_fecha_baja').change(function() {
			if ($('#check_fecha_baja').prop('checked')) {
				$('#fecha_baja').prop('readonly', false);
				$('#fecha_baja').val('<?php echo $fecha_fin_actual; ?>');
				$('#fecha_baja').datepicker('update', '<?php echo $fecha_fin_actual; ?>');
			} else {
				$('#fecha_baja').prop('readonly', true);
				$('#fecha_baja').val('');
			}
			calculo_dias_obligaciones();
		});
<?php if ($escuela->dependencia_id === '1'): ?>
			$('#fecha_alta').change(validar_fecha_obligacion);
			$('input:radio[name="novedad"]').change(validar_fecha_obligacion);
<?php endif; ?>
		$('#cuil').inputmask("99-99999999-9");
		$('#tab_persona input,#cuil,#apellido,#nombre,#fecha_nacimiento').not('#documento,#documento_tipo_id').attr('readonly', true);
		$('#tab_persona input,#cuil,#apellido,#nombre,#fecha_nacimiento').not('#documento,#documento_tipo_id,#persona_id').attr('disabled', true);
		$('#tab_persona select,#sexo').not('#documento_tipo').attr('disabled', true);
		$('#btn-clear').click(function() {
			$('#btn-clear').attr('disabled', true);
			$('#btn-search').attr('disabled', false);
			$('#tab_persona input,#cuil,#apellido,#nombre,#fecha_nacimiento').attr('readonly', true);
			$('#tab_persona input,#cuil,#apellido,#nombre,#fecha_nacimiento').not('#documento,#documento_tipo_id,#persona_id').attr('disabled', true);
			$('#documento').attr('readonly', false);
			$('#documento_tipo')[0].selectize.enable();
			$('#tab_persona select,#sexo').not('#documento_tipo').each(function() {
				var select = $(this)[0].selectize;
				select.enable();
				select.setValue('');
				select.disable();
			});
			$('#tab_persona input,#cuil,#apellido,#nombre,#fecha_nacimiento').not('#documento,#documento_tipo_id').val('');
			$('#documento').select();
			$('#btn-tab').attr('disabled', true);
			$('#btn-submit').attr('disabled', true);
		});
		$('#btn-search').click(function() {
			var documento_tipo = $('#documento_tipo')[0].selectize.getValue();
			var documento = $('#documento').val();
			if (documento_tipo !== '' && documento !== '') {
				$.ajax({
					type: 'GET',
					url: 'ajax/get_persona?',
					data: {documento_tipo: documento_tipo, documento: documento},
					dataType: 'json',
					success: function(result) {
						$('#tab_persona input,#cuil,#apellido,#nombre,#fecha_nacimiento').attr('readonly', true);
						$('#tab_persona input,#cuil,#apellido,#nombre,#fecha_nacimiento').not('#documento,#documento_tipo_id,#persona_id').attr('disabled', true);
						if (result.cuil === null) {
							$('#cuil,#apellido,#nombre').attr('readonly', false);
							$('#cuil,#apellido,#nombre').attr('disabled', false);
						}
						$('#btn-clear').attr('disabled', false);
						$('#btn-search').attr('disabled', true);
						$('#tab_persona select,#sexp').not('#documento_tipo').each(function() {
							$(this)[0].selectize.disable();
						});
						if (result !== '') {
							$('#persona_id').val(result.id);
							var propiedades = Object.keys(result);
							for (var i = 0; i < propiedades.length; i++) {
								var input;
								if (propiedades[i].slice(-3) === '_id') {
									input = $('#' + propiedades[i].slice(0, -3));
								} else {
									input = $('#' + propiedades[i]);
								}
								if (result[propiedades[i]] !== null) {
									if (input.is('select')) {
										var select = input[0].selectize;
										if (propiedades[i] === 'documento_tipo_id') {
											select.setValue(result[propiedades[i]]);
										} else {
											select.enable();
											select.setValue(result[propiedades[i]]);
											select.disable();
										}
									} else {
										input.val(result[propiedades[i]]);
									}
								}
							}
						} else {
							$('#tab_persona input,#cuil,#apellido,#nombre,#fecha_nacimiento').prop('readonly', false);
							$('#tab_persona input,#cuil,#apellido,#nombre,#fecha_nacimiento').prop('disabled', false);
							$('#documento').attr('readonly', true);
							$('#tab_persona select,#sexo').not('#documento_tipo').each(function() {
								$(this)[0].selectize.enable();
							});
							$('#documento_tipo_id').val($('#documento_tipo')[0].selectize.getValue());
							$('#documento_tipo')[0].selectize.disable();
						}
						$('#documento').attr('readonly', true);
						$('#documento_tipo_id').val($('#documento_tipo')[0].selectize.getValue());
						$('#documento_tipo')[0].selectize.disable();
						$('#btn-tab').attr('disabled', false);
						$('#btn-submit').attr('disabled', false);
					}
				});
			}
		});
		mostrar_campos();
		$('#funcion').change(function() {
			$('#row-funcion input[type="text"]').not('#fecha_desde').val('');
			$('#row-funcion select').not('#funcion').val('');
			mostrar_campos();
		});

		$select_tipo_destino = $('#tipo_destino').selectize({
			onChange: actualizar_destino
		});

		$select_destino = $('#destino').selectize({
			valueField: 'id',
			labelField: 'nombre',
			searchField: ['nombre']
		});

		select_tipo_destino = $select_tipo_destino [0].selectize;
		select_destino = $select_destino[0].selectize;
		if (select_tipo_destino.getValue() !== '') {
			actualizar_destino(select_tipo_destino.getValue());
		}
	});
	function validar_fecha_obligacion() {
		$('#message-error-fecha').attr('hidden', true);
		$('#btn-submit').attr('disabled', false);
		var fecha_alta = $('#fecha_alta').val();
		var fecha_oblig = $('input:radio[name="novedad"]:checked').data('desde');
		if (typeof fecha_oblig !== 'undefined' && fecha_alta !== '') {
			var fecha_alta_moment = moment(fecha_alta, 'DD/MM/YYYY');
			var fecha_oblig_moment = moment(fecha_oblig, 'DD/MM/YYYY');
			if (fecha_oblig_moment.isAfter(fecha_alta_moment)) {
				$('#message-error-fecha').attr('hidden', false);
				$('#btn-submit').attr('disabled', true);
			}
		}
	}
	function calculo_dias_obligaciones() {
		var desde = $('#fecha_alta').val();
		var hasta = $('#check_fecha_baja').prop('checked') ? $('#fecha_baja').val() : '<?php echo $fecha_fin_actual; ?>';

		if (desde !== '' && hasta !== '') {
			desde = moment(desde, 'DD/MM/YYYY');
			hasta = moment(hasta, 'DD/MM/YYYY');

			var dias_desde = desde.toDate();
			var dias_hasta = hasta.toDate();
			var dias = 0;
			var obligaciones = 0;

			while (dias_desde.getTime() <= dias_hasta.getTime())
			{
				if (typeof $('#dia_' + dias_desde.getDay()).html() !== 'undefined') {
					obligaciones += parseFloat($('#dia_' + dias_desde.getDay()).html());
				}
				dias_desde.setDate(dias_desde.getDate() + 1);
				dias++;
			}
			if (dias > 30) {
				$('#dias').val('30');
			} else {
				$('#dias').val(dias.toFixed(0));
			}
			if (obligaciones > <?php echo $cargo->regimen_tipo_id === '2' ? $cargo->carga_horaria * 4 : '0'; ?>) {
				$('#obligaciones').val(<?php echo $cargo->regimen_tipo_id === '2' ? $cargo->carga_horaria * 4 : '0'; ?>);
			} else {
				$('#obligaciones').val(obligaciones.toFixed(1));
			}
		} else {
			$('#obligaciones').val('');
			$('#dias').val('');
		}
	}
	function validarFechas() {
		var desde = $('#fecha_alta').val();
		var hasta = $('#check_fecha_baja').prop('checked') ? $('#fecha_baja').val() : '<?php echo $fecha_fin_actual; ?>';
		var fecha_desde;
		var fecha_hasta;
		var tmp_split;
		if (desde === '') {
			$('#alerta').html('Fecha alta no puede estar vacía');
			$('#btn-submit').attr('disabled', true);
			return false;
		}

		if (hasta === '') {
			$('#alerta').html('Fecha baja no puede estar vacía');
			$('#btn-submit').attr('disabled', true);
			return false;
		}

		tmp_split = desde.split('/');
		fecha_desde = new Date(tmp_split[2], tmp_split[1] - 1, tmp_split[0]);
		tmp_split = hasta.split('/');
		fecha_hasta = new Date(tmp_split[2], tmp_split[1] - 1, tmp_split[0]);
		if (fecha_desde > fecha_hasta) {
			$('#alerta').html('Fecha baja no puede ser superior a fecha de alta');
			$('#btn-submit').attr('disabled', true);
			return false;
		}
		$('#alerta').html('');
		$('#btn-submit').attr('disabled', false);
	}
	function mostrar_campos() {
<?php echo $fn_mostrar_campos; ?>
	}
	function actualizar_destino(value) {
		select_destino.enable();
		var valor = select_destino.getValue();
		select_destino.disable();
		select_destino.clearOptions();
		select_destino.load(function(callback) {
			xhr && xhr.abort();
			xhr = $.ajax({
				url: 'ajax/get_destinos/' + value,
				dataType: 'json',
				success: function(results) {
					select_destino.enable();
					callback(results);
					if (results.length === 1) {
						select_destino.setValue(results[0].id);
						select_destino.disable();
					} else {
						select_destino.setValue(valor);
					}
				},
				error: function() {
					callback();
				}
			});
		});
	}
</script>