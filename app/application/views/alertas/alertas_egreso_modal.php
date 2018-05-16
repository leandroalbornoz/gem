<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_causa')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['p_persona']['label']; ?>
			<?php echo $fields['p_persona']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['p_escuela']['label']; ?>
			<?php echo $fields['p_escuela']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['p_division']['label']; ?>
			<?php echo $fields['p_division']['form']; ?>
		</div>
		<?php if ($txt_btn !== "Cancelar"): ?>
		<div class="form-group col-md-6">
			<?php echo $fields['p_fecha_desde']['label']; ?>
			<div class="input-group date" id="datepicker">
				<?php echo $fields['p_fecha_desde']['form']; ?>
				<div class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</div>
			</div>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['p_causa_salida']['label']; ?>
			<?php echo $fields['p_causa_salida']['form']; ?>
		</div>
		<?php endif; ?>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php if($txt_btn !== "Cancelar"): ?>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Confirmar egreso'), 'Confirmar egreso'); ?>
	<?php else: ?>
	<?php echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => $title), $title); ?>
	<?php endif; ?>
	<?php echo form_hidden('id', $alumno_division->id); ?>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	$(document).ready(function() {
		agregar_eventos($('#form_causa'))
		$('#p_fecha_desde').datepicker({
			format: "dd/mm/yyyy",
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true
		});
	});
</script>