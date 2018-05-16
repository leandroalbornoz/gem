<style>
	#formulario_novedad .table{
		margin-bottom: 0;
	}
</style>
<script>
	var unavailableDates = JSON.parse('<?php echo json_encode($fechas_inhabilitadas); ?>');
	var hasta = moment('<?php echo $fecha_hasta; ?>', 'DD/MM/YYYY');
	$(document).ready(function() {
		$('#cal_desde').datepicker({
			datesDisabled: unavailableDates,
			startDate: '<?php echo $fecha_desde; ?>',
			endDate: '<?php echo $fecha_hasta; ?>',
			format: 'dd/mm/yyyy',
			language: 'es',
			maxViewMode: 'month',
			minViewMode: 'month'
		}).on('changeDate', function(e) {
			$('#fecha_desde').val($('#cal_desde').datepicker('getFormattedDate'));
			$('#fecha_desde').change();
			validarFechas();
		});
		$('#cal_desde').datepicker('setDate', '<?php echo (new DateTime($baja->fecha_desde))->format('d/m/Y'); ?>');
		$('#fecha_desde').val($('#cal_desde').datepicker('getFormattedDate'));
		agregar_eventos($('#formulario_novedad'));
		$('#fecha_desde').change(function() {
			var desde = $('#fecha_desde').val();
			if (desde !== '') {
				desde = moment(desde, 'DD/MM/YYYY');
				var date_desde = desde.toDate();
				var date_hasta = hasta.toDate();
				var obligaciones = 0;
				while (date_desde.getTime() <= date_hasta.getTime())
				{
					if (typeof $('#dia_' + date_desde.getDay()).html() !== 'undefined') {
						obligaciones += parseFloat($('#dia_' + date_desde.getDay()).html());
					}
					date_desde.setDate(date_desde.getDate() + 1);
				}
				$('#obligaciones').val(obligaciones.toFixed(1));
				$('#obligaciones').change();
				var diferencia = 30 - desde.date();
				$('#dias').val(diferencia + 1);
				$('#dias').change();
			} else {
				$('#dias').val('');
				$('#dias').change();
			}
		});
		$('#dias').change(function() {
			$('#dias_pagar').val(30 - $('#dias').val())
		});
		$('#obligaciones').change(function() {
			$('#obligaciones_pagar').val(<?php echo $servicio->carga_horaria * 4; ?> - $('#obligaciones').val())
		});
	});
	function validarFechas() {
		var input_desde = $("#fecha_desde");
		var fecha_desde;
		var fecha_hasta = hasta.toDate();
		var tmp_split;
		var fecha_tmp;

		if (input_desde.val() === '') {
			$('#alerta').html('Fecha desde no puede estar vacía');
			$('#btn_submit_novedad').attr('disabled', true);
			return false;
		}

		tmp_split = input_desde.val().split('/');
		fecha_desde = new Date(tmp_split[2], tmp_split[1] - 1, tmp_split[0]);
		for (i = 0; i < unavailableDates.length; i++) {
			tmp_split = unavailableDates[i].split('/');
			fecha_tmp = new Date(tmp_split[2], tmp_split[1] - 1, tmp_split[0]);
			if (fecha_desde < fecha_tmp && fecha_hasta > fecha_tmp) {
				$('#alerta').html('No puede seleccionar un rango que contenga el día ' + unavailableDates[i]);
				$('#btn_submit_novedad').attr('disabled', true);
				return false;
			}
		}
		$('#alerta').html('');
		$('#btn_submit_novedad').attr('disabled', false);
	}
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'formulario_novedad')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['novedad_tipo']['label']; ?>
			<?php echo $fields['novedad_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<div id="cal_desde"></div>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['fecha_desde']['label']; ?>
			<?php echo $fields['fecha_desde']['form']; ?>
			<input type="hidden" id="fecha_hasta" name="fecha_hasta">
		</div>
		<?php if ($servicio->regimen_tipo_id === '1'): ?>
			<div class="form-group col-md-3">
				<?php echo $fields['dias']['label']; ?>
				<?php echo $fields['dias']['form']; ?>
			</div>
			<div class="form-group col-md-3">
				<?php echo $fields['dias_pagar']['label']; ?>
				<?php echo $fields['dias_pagar']['form']; ?>
			</div>
		<?php endif; ?>
		<?php if ($servicio->regimen_tipo_id === '2'): ?>
			<div class="form-group col-md-3">
				<?php echo $fields['obligaciones']['label']; ?>
				<?php echo $fields['obligaciones']['form']; ?>
			</div>
			<div class="form-group col-md-3">
				<?php echo $fields['obligaciones_pagar']['label']; ?>
				<?php echo $fields['obligaciones_pagar']['form']; ?>
			</div>
			<?php if ($servicio->regimen_tipo_id === '2'): ?>
				<div class="col-md-6">
				<label>Horas Cátedra Semanales <span style="font-size: 1.1em;">(<?php echo $servicio->carga_horaria; ?>)</span></label>
					<?php if (!empty($horarios)): ?>
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
					<?php endif; ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php if (!empty($novedades)): ?>
			<div class="col-md-12">
				<label>Novedades Servicio</label>
				<table class="table table-condensed text-center text-sm">
					<thead>
						<tr>
							<th>Artículo</th>
							<th>Desde</th>
							<th>Hasta</th>
							<th>Días</th>
							<th>Obligaciones</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($novedades as $novedad): ?>
							<tr>
								<td><?php echo "$novedad->articulo-$novedad->inciso"; ?></td>
								<td><?php echo (new DateTime($novedad->fecha_desde))->format('d/m/Y'); ?></td>
								<td><?php echo (new DateTime($novedad->fecha_hasta))->format('d/m/Y'); ?></td>
								<td><?php echo $novedad->dias; ?></td>
								<td><?php echo $novedad->obligaciones; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>
</div>
<div class="modal-footer">
	<span class="badge bg-red" id="alerta"></span>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Editar baja', 'id' => 'btn_submit_novedad'), 'Editar baja'); ?>
</div>
<?php echo form_close(); ?>