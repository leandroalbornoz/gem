<script>
	$(document).ready(function() {
		$('#rol').selectize();
	});
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="form-group">
		<?php echo $fields['rol']['label']; ?>
		<?php echo $fields['rol']['form']; ?>
	</div>
</div>
<div class="modal-footer">
	<?php echo form_hidden('redirect_url', $redirect_url); ?>
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<button type="submit" class="btn btn-primary pull-right" title="Seleccionar">Seleccionar</button>
</div>
<?php echo form_close(); ?>