<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['alumno']['label']; ?>
			<?php echo $fields['alumno']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['escuela']['label']; ?>
			<?php echo $fields['escuela']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['division']['label']; ?>
			<?php echo $fields['division']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['email_contacto']['label']; ?>
			<?php echo $fields['email_contacto']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['observaciones']['label']; ?>
			<?php echo $fields['observaciones']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Aceptar pase'), 'Guardar'); ?>
	<?php echo form_hidden('id', $alumno_division->id); ?>
</div>
<?php echo form_close(); ?>