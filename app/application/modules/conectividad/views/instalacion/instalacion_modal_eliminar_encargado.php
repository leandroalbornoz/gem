<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
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
			<?php echo $fields['regimen']['label']; ?>
			<?php echo $fields['regimen']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php
	if ($txt_btn === 'Editar') {
		echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Guardar', 'id' => 'boton_guardar'), 'Guardar');
	} else {
		echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => 'Eliminar'), 'Eliminar');
	}
	?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $conectividad->id) : ''; ?>
</div>
<?php echo form_close(); ?>