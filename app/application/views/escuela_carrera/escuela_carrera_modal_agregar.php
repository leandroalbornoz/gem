<script>
	$(document).ready(function() {
		$('#carrera').selectize();
	});
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<?php foreach ($fields as $field): ?>
		<div class="form-group">
			<?php echo $field['label']; ?>
			<?php echo $field['form']; ?>
		</div>
	<?php endforeach; ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary pull-right" title="Agregar">Agregar</button>
	<?php echo form_hidden('escuela_id', $escuela->id); ?>
</div>
<?php echo form_close(); ?>