<script>
	$(document).ready(function() {
		agregar_eventos($('#formulario_compartir'));
	});
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'formulario_compartir')); ?>
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
	<?php if ($txt_btn === 'Eliminar'): ?>
		<?php echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => 'Dejar de compartir'), 'Dejar de compartir'); ?>
		<?php echo form_hidden('id', $cargo_escuela->id); ?>
	<?php else: ?>
		<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn); ?>
	<?php endif; ?>
</div>
<?php echo form_close(); ?>