<style>
	#formulario_novedad .table{
		margin-bottom: 0;
	}
</style>
<script>
	var unavailableDates = JSON.parse('<?php echo json_encode($fechas_inhabilitadas); ?>');
	var novedades_concomitantes = JSON.parse('<?php echo json_encode($novedades_concomitantes); ?>');
	$(document).ready(function() {
		$('#calendars').datepicker({
			inputs: $('#cal_desde, #cal_hasta'),
			datesDisabled: unavailableDates,
			format: 'dd/mm/yyyy',
			language: 'es',
			maxViewMode: 'month',
			minViewMode: 'month',
			startDate: '<?php echo $fecha_desde; ?>',
			endDate: '14/12/2017'
		}).on('changeDate', function(e) {
			validarFechas();
		});
		$('#cal_desde').on('changeDate', function() {
			$('#fecha_desde').val($('#cal_desde').datepicker('getFormattedDate'));
			$('#fecha_desde').change();
		});
		$('#cal_hasta').on('changeDate', function() {
			$('#fecha_hasta').val($('#cal_hasta').datepicker('getFormattedDate'));
			$('#fecha_hasta').change();
		});
<?php if ($operacion === 'Editar' || $operacion === 'Editar p' || $operacion === 'Confirmar p'): ?>
			cambia_novedad_tipo();
	<?php if ($operacion === 'Confirmar p'): ?>
				calculo_dias_obligaciones();
	<?php endif; ?>
<?php endif; ?>
<?php if ($operacion === 'Editar' || $operacion === 'Editar p' || $operacion === 'Confirmar p'): ?>
			$('#cal_desde').datepicker('update', '<?php echo (new DateTime($servicio_novedad->fecha_desde))->format('d/m/Y'); ?>');
			$('#fecha_desde').val('<?php echo (new DateTime($servicio_novedad->fecha_desde))->format('d/m/Y'); ?>');
			$('#cal_hasta').datepicker('update', '<?php echo (new DateTime($servicio_novedad->fecha_hasta))->format('d/m/Y'); ?>');
			$('#fecha_hasta').val('<?php echo (new DateTime($servicio_novedad->fecha_hasta))->format('d/m/Y'); ?>');
<?php endif; ?>
		agregar_eventos($('#formulario_novedad'));
		$('#dias').inputmask('decimal', {radixPoint: ',', digits: 1, autoUnmask: true, placeholder: '', removeMaskOnSubmit: true, onUnMask: function(value) {
				return value.replace('.', '').replace(',', '.');
			}});
		$('#dias').on('change', function(e) {
			var value = $('#dias').inputmask('unmaskedvalue');
			if (value !== '') {
				var decimals = value.split('.')[1];
				if (typeof decimals === 'undefined')
					$('#dias').val(value + ',0');
				else {
					$('#dias').val(value + ('0').substr(decimals.length));
				}
			}
		});
		$('#fecha_desde,#fecha_hasta').change(calculo_dias_obligaciones);
		$('#novedad_tipo').change(function() {
			cambia_novedad_tipo();
			calculo_dias_obligaciones();
		});
	});

	function calculo_dias_obligaciones() {
		if ($.inArray($('#novedad_tipo').val(), novedades_concomitantes) !== -1) {
			$('#obligaciones').val('0.0');
			$('#dias').val('0.0');
			return;
		}
		var desde = $('#fecha_desde').val();
		var hasta = $('#fecha_hasta').val();
		var ames = '<?php echo $ames; ?>';

		if (desde !== '' && hasta !== '') {
			desde = moment(desde, 'DD/MM/YYYY');
			hasta = moment(hasta, 'DD/MM/YYYY');
			ames = moment(ames, 'YYYY/MM');

			var ames_desde = ames.startOf('month').toDate();
			var ames_hasta = ames.endOf('month').toDate();

			var date_desde = desde.toDate();
			var date_hasta = hasta.toDate();
			var dias = 0;
			var obligaciones = 0;

			var dias_desde = (date_desde.getTime() > ames_desde.getTime()) ? date_desde : ames_desde;
			var dias_hasta = (date_hasta.getTime() < ames_hasta.getTime()) ? date_hasta : ames_hasta;

			while (dias_desde.getTime() <= dias_hasta.getTime())
			{
				if (typeof $('#dia_' + dias_desde.getDay()).html() !== 'undefined') {
					obligaciones += parseFloat($('#dia_' + dias_desde.getDay()).html());
				}
				dias_desde.setDate(dias_desde.getDate() + 1);
				dias++;
			}
			$('#obligaciones').val(obligaciones.toFixed(1));
			$('#dias').val(dias.toFixed(1));
		} else {
			$('#obligaciones').val('');
			$('#dias').val('');
		}
	}

	function cambia_novedad_tipo() {
		if ($('#novedad_tipo').val() === '2' && '<?php echo empty($fecha_alta) ? '1' : '2'; ?>' === '2') {
			$('#cal_desde,#cal_hasta').datepicker('setStartDate', '<?php echo (new DateTime($ames . '01'))->format('d/m/Y'); ?>');
			$('#cal_desde,#cal_hasta').datepicker('setEndDate', '<?php echo $fecha_alta; ?>');
			$('#cal_desde,#cal_hasta').datepicker('setDatesDisabled', unavailableDates);
		} else if ($.inArray($('#novedad_tipo').val(), novedades_concomitantes) !== -1) {
			$('#cal_desde,#cal_hasta').datepicker('setStartDate', '<?php echo $fecha_desde; ?>');
			$('#cal_desde,#cal_hasta').datepicker('setEndDate', '');
			$('#cal_desde,#cal_hasta').datepicker('setDatesDisabled', []);
		} else {
			$('#cal_desde,#cal_hasta').datepicker('setStartDate', '<?php echo $fecha_desde; ?>');
			$('#cal_desde,#cal_hasta').datepicker('setEndDate', '');
			$('#cal_desde,#cal_hasta').datepicker('setDatesDisabled', unavailableDates);
		}
		validarFechas();
	}

	function validarFechas() {
		var input_desde = $("#fecha_desde");
		var input_hasta = $("#fecha_hasta");
		var fecha_desde;
		var fecha_hasta;
		var tmp_split;
		var fecha_tmp;
		if (input_desde.val() === '') {
			$('#alerta').html('Fecha desde no puede estar vacía');
			$('#btn_submit_novedad').attr('disabled', true);
			return false;
		}
		var month_current = moment('<?= $fecha_desde; ?>', 'DD-MM-YYYY').format('YYYYMM');
		var month_select = moment(input_desde.val(), 'DD-MM-YYYY').format('YYYYMM');
		if (month_current < month_select) {
			$('#alerta').html('Fecha desde no puede ser de un mes posterior');
			$('#btn_submit_novedad').attr('disabled', true);
			return false;
		}

		if (input_hasta.val() === '') {
			$('#alerta').html('Fecha hasta no puede estar vacía');
			$('#btn_submit_novedad').attr('disabled', true);
			return false;
		}
		if ($.inArray($('#novedad_tipo').val() + '', novedades_concomitantes) === -1) {
			tmp_split = input_desde.val().split('/');
			fecha_desde = new Date(tmp_split[2], tmp_split[1] - 1, tmp_split[0]);
			tmp_split = input_hasta.val().split('/');
			fecha_hasta = new Date(tmp_split[2], tmp_split[1] - 1, tmp_split[0]);
			for (i = 0; i < unavailableDates.length; i++) {
				tmp_split = unavailableDates[i].split('/');
				fecha_tmp = new Date(tmp_split[2], tmp_split[1] - 1, tmp_split[0]);
				if (fecha_desde < fecha_tmp && fecha_hasta > fecha_tmp) {
					$('#alerta').html('No puede seleccionar un rango que contenga el día ' + unavailableDates[i]);
					$('#btn_submit_novedad').attr('disabled', true);
					return false;
				}
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
			<?php echo $fields['fecha_desde']['label']; ?>
			<?php echo $fields['fecha_desde']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['fecha_hasta']['label']; ?>
			<?php echo $fields['fecha_hasta']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<div class="row" id="calendars">
				<div class="col-md-6" id="cal_desde"></div>
				<div class="col-md-6" id="cal_hasta"></div>
			</div>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields['dias']['label']; ?>
			<?php echo $fields['dias']['form']; ?>
		</div>
		<?php if ($servicio->regimen_tipo_id === '2'): ?>
			<div class="form-group col-md-3">
				<?php echo $fields['obligaciones']['label']; ?>
				<?php echo $fields['obligaciones']['form']; ?>
			</div>
		<?php endif; ?>
		<?php if ($operacion === 'Editar' || $operacion === 'Editar p' || $operacion === 'Confirmar p'): ?>
			<div class="form-group col-md-6">
				<?php echo $fields['estado']['label']; ?>
				<?php echo $fields['estado']['form']; ?>
			</div>
			<?php $class = 'col-md-12'; ?>
		<?php else: ?>
			<?php $class = 'col-md-6'; ?>
		<?php endif; ?>
		<?php if ($servicio->regimen_tipo_id === '2' && !empty($horarios)): ?>
			<div class="<?php echo $class; ?>">
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
							<?php if ($servicio->regimen_tipo_id === '2'): ?>
								<th>Obligaciones</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($novedades as $novedad): ?>
							<tr>
								<td><?php echo "$novedad->articulo-$novedad->inciso"; ?></td>
								<td><?php echo (new DateTime($novedad->fecha_desde))->format('d/m/Y'); ?></td>
								<td><?php echo (new DateTime($novedad->fecha_hasta))->format('d/m/Y'); ?></td>
								<td><?php echo $novedad->dias; ?></td>
								<?php if ($servicio->regimen_tipo_id === '2'): ?>
									<td><?php echo $novedad->obligaciones; ?></td>
								<?php endif; ?>
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
	<?php
	$btn = 'Guardar';
	if ($operacion === 'Confirmar p') {
		$btn = 'Confirmar';
	}
	echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $btn, 'id' => 'btn_submit_novedad'), $btn);
	if ($operacion === 'Editar' || $operacion === 'Editar p' || $operacion === 'Confirmar p') {
		echo form_hidden('id', $servicio_novedad->id);
	}
	?>
</div>
<?php echo form_close(); ?>