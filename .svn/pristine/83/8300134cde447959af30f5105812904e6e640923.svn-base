<script>
	$(document).ready(function() {
		agregar_eventos($('#form_cerrar_c'));
	});
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_cerrar_c')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<?php if ($s_activos): ?>
		Para cerrar el cargo no debe tener servicios activos
	<?php else: ?>
		<?php foreach ($fields as $field): ?>
			<div class="form-group">
				<?php echo $field['label']; ?>
				<?php echo $field['form']; ?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo ($s_activos) ? 'Cerrar' : 'Cancelar'; ?></button>
	<?php echo ($s_activos) ? '' : zetta_form_submit($txt_btn); ?>
	<?php echo form_hidden('id', $cargo_id); ?>
</div>
<?php echo form_close(); ?>