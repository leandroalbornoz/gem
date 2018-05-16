<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form-autoridad')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-sm-12">
			<?php echo $fields['autoridad_tipo']['label']; ?>
			<?php echo $fields['autoridad_tipo']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['cuil']['label']; ?>
			<?php echo $fields['cuil']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['documento']['label']; ?>
			<?php echo $fields['documento']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['apellido']['label']; ?>
			<?php echo $fields['apellido']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['nombre']['label']; ?>
			<?php echo $fields['nombre']['form']; ?>
		</div>
		<div class="form-group col-sm-4">
			<?php echo $fields['email']['label']; ?>
			<?php echo $fields['email']['form']; ?>
		</div>
		<div class="form-group col-sm-4">
			<?php echo $fields['telefono_fijo']['label']; ?>
			<?php echo $fields['telefono_fijo']['form']; ?>
		</div>
		<div class="form-group col-sm-4">
			<?php echo $fields['telefono_movil']['label']; ?>
			<?php echo $fields['telefono_movil']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Editar', 'id' => 'btn-submit'), 'Editar'); ?>
	<?php echo form_hidden('id', $escuela_autoridad->id); ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form-autoridad'));
	});
</script>