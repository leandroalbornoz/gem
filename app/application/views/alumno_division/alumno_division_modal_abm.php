<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_editar_alumno_division')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<?php if (in_array($this->rol->codigo, $this->roles_admin)): ?>
			<div class="form-group col-md-6">
				<?php echo $fields['ciclo_lectivo']['label']; ?>
				<?php echo $fields['ciclo_lectivo']['form']; ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $fields['escuela']['label']; ?>
				<?php echo $fields['escuela']['form']; ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $fields['division']['label']; ?>
				<?php echo $fields['division']['form']; ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $fields['legajo']['label']; ?>
				<?php echo $fields['legajo']['form']; ?>
			</div>
		<?php else: ?>
			<div class="form-group col-md-12">
				<h4><?php echo "<strong><u>Escuela</u>:</strong> $alumno_division->escuela"; ?></h4>
				<h4><?php echo "<strong><u>División</u>:</strong> $alumno_division->curso - $alumno_division->division"; ?></h4>
				<h4><?php echo "<strong><u>Ciclo lectivo</u>:</strong> $alumno_division->ciclo_lectivo"; ?></h4>
			</div>
		<?php endif; ?>
		<div class="form-group col-md-6">
			<?php echo $fields['fecha_desde']['label']; ?>
			<?php echo $fields['fecha_desde']['form']; ?>
		</div>
		<div class="form-group col-md-6" hidden>
				<?php echo $fields['condicion']['label']; ?>
				<?php echo $fields['condicion']['form']; ?>
		</div>
		<?php if (in_array($this->rol->codigo, $this->roles_admin)): ?>
			<div class="form-group col-md-6">
				<?php echo $fields['causa_entrada']['label']; ?>
				<?php echo $fields['causa_entrada']['form']; ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $fields['fecha_hasta']['label']; ?>
				<?php echo $fields['fecha_hasta']['form']; ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $fields['causa_salida']['label']; ?>
				<?php echo $fields['causa_salida']['form']; ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $fields['estado']['label']; ?>
				<?php echo $fields['estado']['form']; ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $fields['condicion']['label']; ?>
				<?php echo $fields['condicion']['form']; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<div class="modal-footer">
	<span class="badge bg-red" id="alerta"></span>
	<?php if ($txt_btn == 'Eliminar'): ?>
		<input type="hidden" name="id" value="<?= $alumno_division->id ?>">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		<?php echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => 'Eliminar', 'id' => 'btn_submit_novedad'), 'Eliminar'); ?>
	<?php else: ?>
		<input type="hidden" name="id" value="<?= $alumno_division->id ?>">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		<?php echo form_submit(array('class' => 'btn btn-warning pull-right', 'title' => $txt_btn, 'id' => 'btn_submit_editar'), $txt_btn); ?>
	<?php endif; ?>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	$(document).ready(function() {
		agregar_eventos($('#form_editar_alumno_division'))
		$('#fecha_desde').inputmask('d/m/y');
		$('#fecha_hasta').inputmask('d/m/y');
<?php if ($txt_btn == "Editar"): ?>
			$('#fecha_desde').change(validarFechas);
<?php endif; ?>
		var causa_entrada = $('#causa_entrada')[0].selectize.getValue();
		if (causa_entrada === '10') {
			$('#causa_entrada')[0].selectize.disable();
		}
		$('#ciclo_lectivo').datepicker({
			format: "yyyy",
			startView: "years",
			minViewMode: "years",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: false,
			startDate: '-y',
			endDate: '+1y'
		});
	});
<?php if ($txt_btn == "Editar"): ?>
		function validarFechas(e) {
			var fecha_min = "<?php echo!empty($inasistencias_cargadas->fecha_min) ? (new DateTime($inasistencias_cargadas->fecha_min))->format('Y/m/d') : ""; ?>";
			var fecha_desde = $('#fecha_desde').val();
			var date_desde = fecha_desde.split('/');
//			console.log(date_desde[2]);

			if (date_desde[2] == <?php echo date("Y"); ?>) {
				$('#alerta').html('');
				$('#btn_submit_editar').attr('disabled', false);
				return;
			} else {
				$('#alerta').html('La fecha debe pertenecer al ciclo lectivo actual');
				$('#btn_submit_editar').attr('disabled', true);
				return;
			}

			var fecha_desde = date_desde[2] + '/' + date_desde[1] + '/' + date_desde[0];

			if (fecha_desde > fecha_min) {
				$('#alerta').html('Fecha desde no permitida, revisar inasistencias cargadas');
				$('#btn_submit_editar').attr('disabled', true);
				return;
			} else {
				$('#alerta').html('');
				$('#btn_submit_editar').attr('disabled', false);
				return;
			}
		}
<?php endif; ?>
</script>