<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Servicios
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="areas/area/ver/<?php echo $area->id; ?>"> <?php echo "$area->codigo $area->descripcion"; ?></a></li>
			<li><a href="areas/personal/listar/<?php echo $area->id . '/' . $mes_id; ?>">Personal</a></li>
			<li class="active">Agregar</li>
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
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta disabled <?php echo $class['agregar']; ?>" href="servicio/agregar">
							<i class="fa fa-plus" id="btn-agregar"></i> Agregar
						</a>
						<a class="btn btn-app btn-app-zetta disabled" href="servicio/ver/">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta disabled" href="servicio/editar/">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta disabled" href="servicio/eliminar/">
							<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
						</a>
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
									<?php if ($txt_btn === 'Agregar'): ?>
										<div class="form-group col-sm-12">
											<button type="button" onclick="$('#a_tab_servicio').tab('show');" id="btn-tab" disabled class="btn btn-primary pull-right">Seleccionar persona</button>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab_servicio">
								<div class="row">
									<div class="form-group col-md-4">
										<?php echo $fields_servicio['area']['label']; ?>
										<?php echo $fields_servicio['area']['form']; ?>
									</div>
									<div class="form-group col-md-2">
										<?php echo $fields_servicio['situacion_revista']['label']; ?>
										<?php echo $fields_servicio['situacion_revista']['form']; ?>
									</div>
									<div class="form-group col-md-4">
										<?php echo $fields_servicio['regimen']['label']; ?>
										<?php echo $fields_servicio['regimen']['form']; ?>
									</div>
									<div class="form-group col-md-2 div_carga_horaria">
										<?php echo $fields_servicio['carga_horaria_cargo']['label']; ?>
										<?php echo $fields_servicio['carga_horaria_cargo']['form']; ?>
									</div>
									<div class="form-group col-md-2">
										<?php echo $fields_servicio['fecha_alta']['label']; ?>
										<?php echo $fields_servicio['fecha_alta']['form']; ?>
									</div>
									<input type="hidden" name="tipo_regimen" id="tipo_regimen" value="">
								</div>
								<hr>
								<div class="row" id="row-funcion">
									<div class="form-group col-md-4">
										<?php echo $fields_funcion['funcion']['label']; ?>
										<?php echo $fields_funcion['funcion']['form']; ?>
									</div>
									<div class="form-group col-md-4 campos-funcion">
										<?php echo $fields_funcion['tipo_destino']['label']; ?>
										<?php echo $fields_funcion['tipo_destino']['form']; ?>
									</div>
									<div class="form-group col-md-4 campos-funcion">
										<?php echo $fields_funcion['destino']['label']; ?>
										<?php echo $fields_funcion['destino']['form']; ?>
									</div>
									<div class="form-group col-md-4 campos-funcion">
										<?php echo $fields_funcion['norma']['label']; ?>
										<?php echo $fields_funcion['norma']['form']; ?>
									</div>
									<div class="form-group col-md-4 campos-funcion">
										<?php echo $fields_funcion['tarea']['label']; ?>
										<?php echo $fields_funcion['tarea']['form']; ?>
									</div>
									<div class="form-group col-md-4 campos-funcion">
										<?php echo $fields_funcion['carga_horaria']['label']; ?>
										<?php echo $fields_funcion['carga_horaria']['form']; ?>
									</div>
								</div>
								<input type="hidden" name="persona_id" value="" id="persona_id"/>
								<input type="hidden" name="documento_tipo" value="" id="documento_tipo_id"/>
								<button type="submit" disabled id="btn-submit" class="btn btn-primary pull-right">Agregar</button>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="areas/personal/listar/<?php echo $area->id . '/' . $mes_id; ?>" title="Cancelar">Cancelar</a>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		$('#tab_persona input,#cuil,#apellido,#nombre,#fecha_nacimiento').not('#documento,#documento_tipo_id').attr('readonly', true);
		$('#tab_persona input,#cuil,#apellido,#nombre,#fecha_nacimiento').not('#documento,#documento_tipo_id,#persona_id').attr('disabled', true);
		$('#tab_persona select,#sexo').not('#documento_tipo').attr('disabled', true);
		$('.div_carga_horaria').hide();
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
						$('#btn-tab').attr('disabled', false);
						$('#btn-submit').attr('disabled', false);
					}
				});
			}
		});
	});
	var xhr, xhr_regimen;
	var select_tipo_destino, $select_tipo_destino;
	var select_destino, $select_destino;
	var select_regimen, $select_regimen;
	$(document).ready(function() {
		mostrar_campos();
		$('#funcion').change(function() {
			$('#row-funcion input[type="text"]').not('#fecha_desde').val('');
			$('#row-funcion select').not('#funcion').val('');
			mostrar_campos();
		});

		$select_tipo_destino = $('#tipo_destino ').selectize({
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

		if ($('select#regimen').length > 0) {
			$select_regimen = $('#regimen').selectize({
				plugins: {
					'clear_selection': {}
				},
				onChange: actualizar_carga_horaria
			});
			select_regimen = $select_regimen[0].selectize;
			if (select_regimen.getValue() !== '') {
				actualizar_carga_horaria();
			}
		}
	});

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

	function actualizar_carga_horaria() {
		$('.div_carga_horaria').hide();
		$('#tipo_regimen').val('');

		xhr_regimen && xhr_regimen.abort();
		var regimen_id = select_regimen.getValue();
		if (regimen_id !== '') {
			xhr_regimen = $.ajax({
				url: 'ajax/get_tipo_cargo/' + regimen_id,
				dataType: 'json',
				success: function(results) {
					if (results === 'Hora') {
						$('.div_carga_horaria').show();
					}
					$('#tipo_regimen').val(results);
				},
				error: function() {
					callback();
				}
			});
		}
	}
</script>