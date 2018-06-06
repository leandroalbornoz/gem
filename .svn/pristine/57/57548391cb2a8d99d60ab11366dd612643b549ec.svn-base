<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_inasistencia_abrir_mes')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-sm-6 form-group">
			<?php echo $fields['division']['label']; ?>
			<?php echo $fields['division']['form']; ?>
			<?php echo $fields['periodo']['label']; ?>
			<?php echo $fields['periodo']['form']; ?>
			<?php echo $fields['dias']['label']; ?>
			<input type="number" name="dias" value="<?php echo $dias_cursado; ?>" max="<?php echo $dias_max_cursado; ?>" min="1" id="dias" class="form-control" pattern="^(0|[1-9][0-9]*)$" title="Debe ingresar sólo números" autocomplete="off">
			<?php echo $fields['resumen_mensual']['label']; ?>
			<?php echo $fields['resumen_mensual']['form']; ?>
		</div>
		<div class="col-sm-6">
			<div id="datepicker"></div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_inasistencia_abrir_mes'))
		$('.modal-body #datepicker').datepicker({
			format: 'yyyy-mm-dd',
			startView: 'month',
			maxViewMode: 'month',
			minViewMode: 'month',
			daysOfWeekHighlighted: [0, 6],
			language: 'es',
			todayHighlight: false,
			startDate: '<?php echo $fecha_ini->format('Y-m-d'); ?>',
			endDate: '<?php echo $fecha_fin->format('Y-m-d'); ?>',
		});
	});
</script>