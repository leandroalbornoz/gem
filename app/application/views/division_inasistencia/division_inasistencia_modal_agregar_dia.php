<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_inasistencia_agregar_dia')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<?php foreach ($fields as $field): ?>
			<div class="col-sm-6 form-group">
				<?php echo $field['label']; ?>
				<?php echo $field['form']; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Cargar día'), 'Cargar día'); ?>
</div>
<?php echo form_close(); ?>

<script>
	$(document).ready(function() {
		agregar_eventos($('#form_inasistencia_agregar_dia'))
	});
</script>