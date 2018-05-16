<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['conectividad_nacion']['label']; ?>
			<?php echo $fields['conectividad_nacion']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['escuela']['label']; ?>
			<?php echo $fields['escuela']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields['fecha_inicio']['label']; ?>
			<?php echo $fields['fecha_inicio']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields['fecha_fin']['label']; ?>
			<?php echo $fields['fecha_fin']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields['instalador']['label']; ?>
			<?php echo $fields['instalador']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['celular_contacto']['label']; ?>
			<?php echo $fields['celular_contacto']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $conectividad_escuela->id) : ''; ?>
</div>
<?php echo form_close(); ?>