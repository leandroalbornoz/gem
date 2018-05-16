<style>
	#formulario_novedad .table{
		margin-bottom: 0;
	}
</style>
<script>
	$(document).ready(function() {
		$('#fecha_desde,#fecha_hasta').datepicker({
			format: 'dd/mm/yyyy',
			language: 'es',
			maxViewMode: 'month',
			minViewMode: 'month',
			startDate: '<?php echo $fecha_desde; ?>',
			endDate: '<?php echo $fecha_hasta; ?>'
		}).on('changeDate', function(e) {
			validarFechas();
		});
		$('#fecha_desde').val('<?php echo (new DateTime($alta->fecha_desde))->format('d/m/Y'); ?>');
		$('#fecha_desde').datepicker('update', '<?php echo (new DateTime($alta->fecha_desde))->format('d/m/Y'); ?>');
		$('#fecha_hasta').val('<?php echo (new DateTime($alta->fecha_hasta))->format('d/m/Y'); ?>');
		$('#fecha_hasta').datepicker('update', '<?php echo (new DateTime($alta->fecha_hasta))->format('d/m/Y'); ?>');
		agregar_eventos($('#formulario_novedad'));
		$('#dias').inputmask('integer');
		$('#obligaciones').inputmask('decimal', {radixPoint: ',', digits: 1, autoUnmask: true, placeholder: '', removeMaskOnSubmit: true, onUnMask: function(value) {
				return value.replace('.', '').replace(',', '.');
			}});
		$('#obligaciones').on('change', function(e) {
			var value = $('#obligaciones').inputmask('unmaskedvalue');
			if (value !== '') {
				var decimals = value.split('.')[1];
				if (typeof decimals === 'undefined')
					$('#obligaciones').val(value + ',0');
				else {
					$('#obligaciones').val(value + ('0').substr(decimals.length));
				}
			}
		});
		$('#fecha_desde,#fecha_hasta').change(calculo_dias_obligaciones);
		$('#check_fecha_hasta').change(function() {
			if ($('#check_fecha_hasta').prop('checked')) {
				$('#fecha_hasta').prop('readonly', false);
				$('#fecha_hasta').val('<?php echo $fecha_hasta; ?>');
				$('#fecha_hasta').datepicker('update', '<?php echo $fecha_hasta; ?>');
			} else {
				$('#fecha_hasta').prop('readonly', true);
				$('#fecha_hasta').val('');
			}
			calculo_dias_obligaciones();
		});
	});

	function calculo_dias_obligaciones() {
		var desde = $('#fecha_desde').val();
		var hasta = $('#check_fecha_hasta').prop('checked') ? $('#fecha_hasta').val() : '<?php echo $fecha_hasta; ?>';
		var ames = '<?php echo $mes; ?>';

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
			$('#dias').val(dias.toFixed(0));
		} else {
			$('#obligaciones').val('');
			$('#dias').val('');
		}
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

		if ($('#check_fecha_hasta').prop('checked') && input_hasta.val() === '') {
			$('#alerta').html('Fecha hasta no puede estar vacía');
			$('#btn_submit_novedad').attr('disabled', true);
			return false;
		}
		tmp_split = input_desde.val().split('/');
		fecha_desde = new Date(tmp_split[2], tmp_split[1] - 1, tmp_split[0]);
		tmp_split = input_hasta.val().split('/');
		fecha_hasta = new Date(tmp_split[2], tmp_split[1] - 1, tmp_split[0]);
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
		<div class="form-group col-md-6">
			<?php echo $fields['fecha_desde']['label']; ?>
			<?php echo $fields['fecha_desde']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['fecha_hasta']['label']; ?>
			<?php echo $fields['fecha_hasta']['form']; ?>
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
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Editar', 'id' => 'btn_submit_novedad'), 'Editar'); ?>
	<?php echo form_hidden('id', $alta->id); ?>
	<?php echo form_hidden('escuela_id', $servicio->escuela_id); ?>
</div>
<?php echo form_close(); ?>