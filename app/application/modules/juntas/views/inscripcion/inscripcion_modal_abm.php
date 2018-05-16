<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_abrir')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['persona']['label']; ?>
			<?php echo $fields['persona']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['fecha_cierre']['label']; ?>
			<?php echo $fields['fecha_cierre']['form']; ?>
		</div>
	</div>
	<?php if (isset($inscripcion->fecha_recepcion)): ?>
		<div class="alert alert-danger" role="alert">
			Está inscripción no se puede abrir ya que pressentó la documentación en la escuela seleccioanda.
		</div>
	<?php endif; ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Abrir') ? form_hidden('id', $inscripcion->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
<?php if (isset($inscripcion->fecha_recepcion)): ?>
		$('input[type="submit"]').attr('disabled', 'disabled');
<?php endif; ?>
</script>
