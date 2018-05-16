<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agregar_bonificaciones')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['persona']['label']; ?>
			<?php echo $fields['persona']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['cuil']['label']; ?>
			<?php echo $fields['cuil']['form']; ?>
		</div>
		<br>
		<div class="form-group col-md-6">
				<?php echo $fields['bonificacion_escolaridad']['label']; ?>
				<?php echo $fields['bonificacion_escolaridad']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['bonificacion_matrimonio']['label']; ?>
			<?php echo $fields['bonificacion_matrimonio']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['bonificacion_cargo']['label']; ?>
			<?php echo $fields['bonificacion_cargo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['bonificacion_discapacidad']['label']; ?>
			<?php echo $fields['bonificacion_discapacidad']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php
	if ($txt_btn === 'Agregar') {
		echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Agregar', 'id' => 'boton_guardar'), 'Agregar');
	} else {
		echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => 'Eliminar'), 'Eliminar');
	}
	?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $bonificacion->id) : ''; ?>
</div>

<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_agregar_bonificaciones'))
	});
</script>