<style>
	.datepicker table tr td.active:active, .datepicker table tr td.active.highlighted:active, .datepicker table tr td.active.active, .datepicker table tr td.active.highlighted.active, .open > .dropdown-toggle.datepicker table tr td.active, .open > .dropdown-toggle.datepicker table tr td.active.highlighted {
    background-color: #285e8e;
    border-color: #285e8e;
    color: #ffffff;
	}
</style>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_inasistencia_eliminar_mes')); ?>
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
			<?php echo $fields['dias']['form']; ?>
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
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-danger pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_inasistencia_eliminar_mes'))
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
		<?php if(!empty($fechas)):?>
		$('.modal-body #datepicker').datepicker('setDate',<?php echo json_encode($fechas); ?>);
		<?php endif; ?>
	});
</script>