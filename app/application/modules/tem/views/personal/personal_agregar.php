<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Tutores TEM <?php echo "Esc. $escuela->nombre_corto" ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id ?>"><?php echo "Esc. $escuela->nombre_corto" ?></a></li>
			<li><a href="tem/personal/listar/<?php echo $escuela->id ?>/<?php echo $mes_id ?>">Tutores TEM</a></li>
			<li class="active">Agregar</li>
		</ol>
	</section>
	<section class="content">
		<div class="alert alert-danger alert-dismissable alert-personal hidden">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4><i class="icon fa fa-ban"></i> Error!</h4>
			<p>Personal no válido!</p>
		</div>
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
					<?php echo form_open(base_url("tem/personal/agregar_personal/$escuela->id/$mes_id"), array('data-toggle' => 'validator')); ?>
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
						<hr>
						<div class="row">
							<div class="col-xs-6">
								<table class="table table-bordered table-condensed table-striped table-hover">
									<thead>
										<tr>
											<th colspan="6" class="text-center bg-primary">
												Compatibilidad
											</th>
										</tr>
										<tr>
											<th style="width: 60px;">Cargos<br>jerarquicos</th>
											<th style="width: 60px;">Cargos<br>no jerarquicos</th>
											<th style="width: 60px;">Horas<br>superior</th>
											<th style="width: 60px;">Horas<br>no superior</th>
											<th style="width: 60px;">Horas<br>TEM</th>
											<th style="width: 60px;">Horas<br>disponibles</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td id="cargo_jerarquico"></td>
											<td id="cargo_no_jerarquico"></td>
											<td id="horas_superior"></td>
											<td id="horas_no_superior"></td>
											<td id="horas_tem"></td>
											<td id="horas_disponibles">&nbsp;</td>
										</tr>
									</tbody>
								</table>
								<p id="menssage-compatibilidad"></p>
							</div>
							<div class="col-xs-6" id="mensaje-incompatibilidad" hidden="true">
								<p class="alert alert-warning text-bold">El personal seleccionado no puede ser ingresado por encontrarse incompatible. En caso de tener constancia de las renuncias correspondientes comunicarse al 4492795 con Daniela.</p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="form-group col-md-4">
								<?php echo $fields_servicio['escuela']['label']; ?>
								<?php echo $fields_servicio['escuela']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields_cargo['regimen']['label']; ?>
								<?php echo $fields_cargo['regimen']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields_cargo['condicion_cargo']['label']; ?>
								<?php echo $fields_cargo['condicion_cargo']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields_cargo['carga_horaria']['label']; ?>
								<?php echo $fields_cargo['carga_horaria']['form']; ?>
							</div>
						</div>
						<hr>
						<div class="row" id="row-funcion">
							<div class="form-group col-md-3">
								<?php echo $fields_funcion['funcion']['label']; ?>
								<?php echo $fields_funcion['funcion']['form']; ?>
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
							<div class="form-group col-md-3">
								<?php echo $fields_servicio['obligaciones']['label']; ?>
								<?php echo $fields_servicio['obligaciones']['form']; ?>
							</div>
						</div>
						<input type="hidden" name="persona_id" value="" id="persona_id"/>
						<input type="hidden" name="documento_tipo" value="" id="documento_tipo_id"/>
						<button type="submit" disabled id="btn-submit" class="btn btn-primary pull-right">Agregar</button>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="tem/personal/listar/<?php echo $escuela->id ?>/<?php echo $mes_id ?>" title="Cancelar">Cancelar</a>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		$(".datepicker").datepicker({
			format: "dd/mm/yyyy",
			startDate: '<?php echo $fecha_desde; ?>',
			endDate: '<?php echo $fecha_hasta ?>',
			language: 'es',
			todayHighlight: false
		});
		$('#carga_horaria').change(function() {
			$('#obligaciones').attr('max', $('#carga_horaria').val() * 4);
		});
		$('#fecha_alta').change(calculo_dias_obligaciones);
		$('#cuil').inputmask("99-99999999-9");
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
			$('#btn-submit').attr('disabled', true);
			$('#cargo_jerarquico,#cargo_no_jerarquico,#horas_superior,#horas_no_superior,#horas_tem,#horas_disponibles').html('');
			$('#horas_disponibles').html('&nbsp;');
			var horas_disponibles = $('#carga_horaria').data('hs');
			$('#carga_horaria').attr('max', horas_disponibles);
			$('#carga_horaria').val('');
		});

		function calculo_dias_obligaciones() {
			var desde = $('#fecha_alta').val();
			var hasta = '<?php echo $fecha_fin_actual; ?>';

			if (desde !== '' && hasta !== '') {
				desde = moment(desde, 'DD/MM/YYYY');
				hasta = moment(hasta, 'DD/MM/YYYY');

				var dias_desde = desde.toDate();
				var dias_hasta = hasta.toDate();
				var dias = 0;
				var obligaciones = 0;

				while (dias_desde.getTime() <= dias_hasta.getTime())
				{
					dias_desde.setDate(dias_desde.getDate() + 1);
					dias++;
				}

				if (dias > 30) {
					$('#dias').val('30');
					$('#obligaciones').val($('#carga_horaria').val() * 4);
				} else {
					$('#dias').val(dias.toFixed(0));
					obligaciones = $('#carga_horaria').val() * 4 * dias / 30;
					$('#obligaciones').val(obligaciones.toFixed(0));
				}
			} else {
				$('#obligaciones').val('');
				$('#dias').val('');
			}
		}

		$('#btn-search').click(function() {
			var documento_tipo = $('#documento_tipo')[0].selectize.getValue();
			var documento = $('#documento').val();
			if (documento_tipo !== '' && documento !== '') {
				$.ajax({
					type: 'GET',
					url: 'ajax/get_persona_tem?',
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
						$('#mensaje-incompatibilidad').attr('hidden', true);
						$('#tab_persona select,#sexp').not('#documento_tipo').each(function() {
							$(this)[0].selectize.disable();
						});
						if (result['status'] === 'success') {
							var persona_tem = result['persona_tem'];
							var horas_tem = result['horas_tem'];
							var horas_disponible_escuela = parseInt($('#carga_horaria').attr('max'));
							var horas_disponible_persona = parseInt(persona_tem.horas_disponibles);
							horas_disponible_persona = horas_disponible_persona - parseInt(horas_tem);

							$('#cargo_jerarquico').html('' + persona_tem.cargos_jerarquicos);
							$('#cargo_no_jerarquico').html('' + persona_tem.cargos_no_jerarquicos);
							$('#horas_superior').html('' + persona_tem.horas_superior);
							$('#horas_no_superior').html('' + persona_tem.horas_no_superior);
							$('#horas_tem').html('' + horas_tem);
							$('#horas_disponibles').html('' + horas_disponible_persona);

							if (horas_disponible_persona > 0) {
								if (horas_disponible_persona < horas_disponible_escuela) {
									$('#carga_horaria').attr('max', horas_disponible_persona);
								}
								$('#btn-submit').attr('disabled', false);
							} else {
								$('#mensaje-incompatibilidad').attr('hidden', false);
							}
							if (result['persona_gem'] !== '') {
								var persona = result['persona_gem'];
								$('#persona_id').val(persona.id);
								var propiedades = Object.keys(persona);
								for (var i = 0; i < propiedades.length; i++) {
									var input;
									if (propiedades[i].slice(-3) === '_id') {
										input = $('#' + propiedades[i].slice(0, -3));
									} else {
										input = $('#' + propiedades[i]);
									}
									if (persona[propiedades[i]] !== null) {
										if (input.is('select')) {
											var select = input[0].selectize;
											if (propiedades[i] === 'documento_tipo_id') {
												select.setValue(persona[propiedades[i]]);
											} else {
												select.enable();
												select.setValue(persona[propiedades[i]]);
												select.disable();
											}
										} else {
											input.val(persona[propiedades[i]]);
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
							$(".alert-personal").addClass('hidden');
							$('#documento').attr('readonly', true);
							$('#documento_tipo')[0].selectize.disable();
						} else {
							$(".alert-personal").removeClass('hidden');
							$('#documento').attr('readonly', true);
							$('#documento_tipo')[0].selectize.disable();
						}
					}
				});
			}
		});
	});
</script>