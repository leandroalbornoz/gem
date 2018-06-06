<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agregar_periodo')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['ciclo_lectivo']['label']; ?>
			<div class="input-group date" id="ciclo_lectivo">
				<?php echo $fields['ciclo_lectivo']['form']; ?>
				<div class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</div>
			</div>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['periodo']['label']; ?>
			<?php if ($txt_btn === 'Editar' || $txt_btn === 'Eliminar'): ?>
			<?php echo $fields['periodo']['form']; ?>
			<?php else:?>
			<input type="number" name="periodo" value="" max="5" id="periodo" class="form-control" required="">
			<?php endif; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['inicio']['label']; ?>
			<?php echo $fields['inicio']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['fin']['label']; ?>
			<?php echo $fields['fin']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $periodo->id) : ''; ?>
</div>
<?php echo form_close(); ?>

<script>
	$(document).ready(function() {
		agregar_eventos($('#form_agregar_periodo'))
		$('#ciclo_lectivo').datepicker({
			format: "yyyy",
			startView: "years",
			minViewMode: "years",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: false
		}).datepicker("setDate", new Date());
	
	});
</script>