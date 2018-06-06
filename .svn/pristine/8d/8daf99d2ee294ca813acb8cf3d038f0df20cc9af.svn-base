<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form-autoridad')); ?>
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
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => 'Eliminar', 'id' => 'btn-submit'), 'Eliminar'); ?>
	<?php echo ($txt_btn === 'Eliminar') ? form_hidden('id', $escuela_autoridad->id) : ''; ?>
</div>
<?php echo form_close(); ?>
