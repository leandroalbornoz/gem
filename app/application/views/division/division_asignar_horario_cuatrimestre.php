<style>
	.table-horarios .selectize-control.single .selectize-input:after {
    right: 5px;
	}
	.table-horarios .selectize{
		font-size: 10px;
		line-height: 1.4;
		padding: 2px;
	}
	.table-horarios .selectize-dropdown, .table-horarios .selectize-input, .table-horarios .selectize-input input {
		font-size: 10px;
		line-height: 1.4;
		padding: 2px;
	}
	.table-horarios .selectize-input>input {
		display: inline-block;
		/*position:relative;*/
		/*display: none !;*/
	}
	.table-horarios .selectize-dropdown-content .option{
		border: 1px #888 solid;
		border-radius: 5%;
	}
	.cargo_mas{
		font-weight: bold;
		color: red;
	}
	.cargo_menos{
		color: black;
	}
	.cargo_ok{
		font-weight: bold;
		color: green;
	}
	.table-horarios .selectize-input.disabled{
		display:none;
	}
	.par1 .selectize-input.full > input {
		position:absolute;
	}
	.table-horarios>tbody>tr>td>select.selectized:disabled+div{
		display: none;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Asignar materias a <?php echo "$division->curso $division->division"; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id; ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="division/listar/<?= $escuela->id ?>">Cursos y Divisiones</a></li>
			<li><a href="division/ver/<?= $division->id ?>"><?php echo "$division->curso $division->division"; ?></a></li>
			<li class="active">Asignar materias</li>
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
		<div class="alert alert-warning">
			<h4><i class="icon fa fa-warning"></i> Advertencia!</h4>
			Asegúrese de ordenar los cargos y servicios antes de asignar materias al horario de la división
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta" href="division/ver/<?php echo $division->id; ?>">
							<i class="fa fa-search"></i> Ver división
						</a>
						<a class="btn btn-app btn-app-zetta" href="division/ver_horario/<?php echo $division->id; ?>">
							<i class="fa fa-clock-o"></i> Ver horario
						</a>
						<a class="btn btn-app btn-app-zetta" href="division/editar_horario/<?php echo $division->id; ?>">
							<i class="fa fa-clock-o"></i> Editar horas
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $cuatrimestre == 1 ? 'active btn-app-zetta-active' : '' ?>" href="division/asignar_horario_cuatrimestre/<?php echo $division->id; ?>/1">
							<i class="fa fa-clock-o"></i> Materias 1º Cuatrimestre
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $cuatrimestre == 2 ? 'active btn-app-zetta-active' : '' ?>" href="division/asignar_horario_cuatrimestre/<?php echo $division->id; ?>/2">
							<i class="fa fa-clock-o"></i> Materias 2º Cuatrimestre
						</a>
						<a class="btn btn-app btn-app-zetta pull-right" href="division/cargos/<?php echo $division->id; ?>">
							<i class="fa fa-users"></i> Cargos
						</a>
						<a class="btn btn-app btn-app-zetta pull-right" href="division/alumnos/<?php echo $division->id; ?>">
							<i class="fa fa-users"></i> Alumnos
						</a>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields['curso']['label']; ?>
								<?php echo $fields['curso']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['division']['label']; ?>
								<?php echo $fields['division']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['turno']['label']; ?>
								<?php echo $fields['turno']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['carrera']['label']; ?>
								<?php echo $fields['carrera']['form']; ?>
							</div>
						</div>
						<input type="hidden" name="escuela_id" value="<?= $escuela->id; ?>"/>
						<input type="hidden" name="cuatrimestre" value="<?= $cuatrimestre ?>"/>
						<div class="row">
							<div class="col-lg-10 col-sm-12">
								<table class="table-horarios table table-condensed" style="text-align: center; table-layout: fixed;">
									<tr>
										<th style="text-align: center; width: 40px;">Hora</th>
										<?php foreach ($dias as $dia): ?>
											<th style="text-align: center;"><?php echo mb_substr($dia->nombre, 0, 2); ?></th>
										<?php endforeach; ?>
									</tr>
									<?php for ($hora_catedra = 1; $hora_catedra <= $turno->max_hora_catedra; $hora_catedra++): ?>
										<tr>
											<td style="vertical-align: middle;"><?php echo $hora_catedra; ?></td>
											<?php foreach ($dias as $dia): ?>
												<td style="vertical-align: top;">
													<?php if (isset($turno->horarios[$hora_catedra][$dia->id])): ?>
														<?php echo substr($turno->horarios[$hora_catedra][$dia->id]->hora_desde, 0, 5) . ' - ' . substr($turno->horarios[$hora_catedra][$dia->id]->hora_hasta, 0, 5) . ($turno->horarios[$hora_catedra][$dia->id]->obligaciones === '1.0' ? '' : '<br/>(' . $turno->horarios[$hora_catedra][$dia->id]->obligaciones . ' oblig.)') . '<br/>'; ?>
														<?php echo form_dropdown(array('placeholder' => 'Seleccionar', 'class' => 'par1 selectize', 'name' => "cargo[{$turno->horarios[$hora_catedra][$dia->id]->id}][]"), $options_cargo, $turno->horarios[$hora_catedra][$dia->id]->cargos[0]) ?>
														<?php echo form_dropdown(array('placeholder' => 'Seleccionar', 'class' => 'par2 selectize', 'name' => "cargo[{$turno->horarios[$hora_catedra][$dia->id]->id}][]"), $options_cargo_pp, isset($turno->horarios[$hora_catedra][$dia->id]->cargos[1]) ? $turno->horarios[$hora_catedra][$dia->id]->cargos[1] : '') ?>
														<?php echo form_input(array('type' => 'hidden', 'class' => 'obligaciones_hora', 'value' => $turno->horarios[$hora_catedra][$dia->id]->obligaciones)); ?>
													<?php endif; ?>
												</td>
											<?php endforeach; ?>
										</tr>
									<?php endfor; ?>
								</table>
							</div>
							<div class="col-lg-2 col-sm-12">
								<table class="table table-condensed">
									<tr>
										<th>Cargo</th>
										<th>Hs</th>
										<th class="hidden">En Div</th>
										<th class="hidden">Otra Div</th>
										<th class="hidden">Acum</th>
										<th title="Asignadas en División (Acumuladas con otras Divisiones)">Asig</th>
									</tr>
									<?php if (!empty($cargos)): ?>
										<?php foreach ($cargos as $cargo): ?>
											<tr>
												<td class="text-sm" style="vertical-align: middle;"><?php echo "$cargo->materia<br/><b>$cargo->persona<b/>"; ?></td>
												<td class="cargos_carga_horaria text-center" id="cargo_<?php echo $cargo->id; ?>_carga_horaria" style="vertical-align: middle;"><?php echo $cargo->regimen_tipo_id === '1' ? '-' : $cargo->carga_horaria; ?></td>
												<td class="hidden cargos_hs_asignadas text-center" id="cargo_<?php echo $cargo->id; ?>_hs_asignadas" style="vertical-align: middle;"></td> 
												<td class="hidden cargos_hs_asignadas_otra_div text-center" id="cargo_<?php echo $cargo->id; ?>_hs_asignadas_otra_div" style="vertical-align: middle;"><?php echo $cargo->hs; ?></td>
												<td class="hidden cargos_hs_acumuladas text-center" id="cargo_<?php echo $cargo->id; ?>_hs_asignadas" style="vertical-align: middle;"><?php echo $cargo->hs ? $cargo->hs : 0; ?></td>
												<td class="cargos_hs_asignadas_acumuladas text-center" title="<?php echo "Horarios en otras Divisiones: \n" . implode(" \n", explode(',', $cargo->detalle)); ?>" id="cargo_<?php echo $cargo->id; ?>_hs_asignadas_acumuladas" style="vertical-align: middle;"><?php echo '0 (' . ($cargo->hs ? $cargo->hs : 0) . ')'; ?></td>
											</tr>
										<?php endforeach; ?>
									<?php endif; ?>
								</table>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="division/listar/<?= $escuela->id; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $division->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var opciones_pp_json = JSON.parse('<?php echo json_encode($options_cargo_pp); ?>');
	$(document).ready(function() {
		$(document).ready(function() {
			$('.selectize.par1').change(actualizar_hs_full);
			$('.selectize.par2').change(actualizar_hs_par2);
			actualizar_hs_full(null);
		});
	});
	function actualizar_hs_full(e) {
		var options;
		if (e === null) {
			options = $('.selectize.par1 option:selected');
		} else {
			options = $(e.target).find('option:selected');
		}
		options.each(function() {
			if ($(this).text().substring(0, 5) === 'PP - ') {
				$(this).parent().next().next()[0].selectize.enable();
				var valor = $(this).parent().next().next()[0].selectize.getValue();
				$(this).parent().next().next()[0].selectize.clearOptions();
				for (var index in opciones_pp_json) {
					if (index !== '' && index != $(this).val()) {
						$(this).parent().next().next()[0].selectize.addOption({
							value: index,
							text: opciones_pp_json[index],
						});
					}
				}
				$(this).parent().next().next()[0].selectize.setValue(valor);
			} else {
				$(this).parent().next().next()[0].selectize.enable();
				$(this).parent().next().next()[0].selectize.setValue('');
				$(this).parent().next().next()[0].selectize.disable();
			}
		});
		actualizar_hs_par2();
	}
	function actualizar_hs_par2() {
		$('.cargos_hs_asignadas').each(function() {
			$(this).html('0');
		});
		$('.selectize option:selected').each(function() {
			if ($(this).val() !== '') {
				var carga_horaria = parseInt($('#cargo_' + $(this).val() + '_carga_horaria').html());
				var hs_asignadas = parseFloat($('#cargo_' + $(this).val() + '_hs_asignadas').html());
				hs_asignadas += parseFloat($(this).closest('td').find('.obligaciones_hora').val());
				$('#cargo_' + $(this).val() + '_hs_asignadas').html(hs_asignadas);
				var hs_asignadas_otra_div = parseFloat($('#cargo_' + $(this).val() + '_hs_asignadas_otra_div').html());
				var hs_acumuladas = hs_asignadas + (isNaN(hs_asignadas_otra_div) ? 0.0 : hs_asignadas_otra_div);
				$('#cargo_' + $(this).val() + '_hs_acumuladas').html(hs_acumuladas);
				$('#cargo_' + $(this).val() + '_hs_asignadas_acumuladas').html($('#cargo_' + $(this).val() + '_hs_asignadas').html() + ' (' + hs_acumuladas + ')');
				if (carga_horaria > hs_acumuladas) {
					$('#cargo_' + $(this).val() + '_carga_horaria').removeClass('cargo_mas');
					$('#cargo_' + $(this).val() + '_carga_horaria').removeClass('cargo_ok');
					$('#cargo_' + $(this).val() + '_carga_horaria').addClass('cargo_menos');
				} else if (carga_horaria < hs_acumuladas) {
					$('#cargo_' + $(this).val() + '_carga_horaria').removeClass('cargo_menos');
					$('#cargo_' + $(this).val() + '_carga_horaria').removeClass('cargo_ok');
					$('#cargo_' + $(this).val() + '_carga_horaria').addClass('cargo_mas');
				} else {
					$('#cargo_' + $(this).val() + '_carga_horaria').removeClass('cargo_menos');
					$('#cargo_' + $(this).val() + '_carga_horaria').removeClass('cargo_mas');
					$('#cargo_' + $(this).val() + '_carga_horaria').addClass('cargo_ok');
				}
			}
		});
	}
</script>