<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>

<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_editar_preinscripcion_tipo')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields_alumno['documento_tipo']['label']; ?>
			<?php echo $fields_alumno['documento_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_alumno['documento']['label']; ?>
			<?php echo $fields_alumno['documento']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_alumno['apellido']['label']; ?>
			<?php echo $fields_alumno['apellido']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_alumno['nombre']['label']; ?>
			<?php echo $fields_alumno['nombre']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['preinscripcion_tipo']['label']; ?>
			<?php echo $fields['preinscripcion_tipo']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
<?php echo ($txt_btn === 'Guardar') ? form_hidden('id', $preinscripcion_alumno->id) : ''; ?>
</div>
<?php echo form_close(); ?>

<script>
	$(document).ready(function() {
		agregar_eventos($('#form_editar_preinscripcion_tipo'))
	});
</script>