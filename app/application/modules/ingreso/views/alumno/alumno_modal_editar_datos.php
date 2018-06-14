<?php echo form_open_multipart(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_editar_persona')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['calle']['label']; ?>
			<?php echo $fields['calle']['form']; ?>
		</div>
		<div class="form-group col-md-2">
			<?php echo $fields['calle_numero']['label']; ?>
			<?php echo $fields['calle_numero']['form']; ?>
		</div>
		<div class="form-group col-md-2">
			<?php echo $fields['departamento']['label']; ?>
			<?php echo $fields['departamento']['form']; ?>
		</div>
		<div class="form-group col-md-2">
			<?php echo $fields['piso']['label']; ?>
			<?php echo $fields['piso']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['barrio']['label']; ?>
			<?php echo $fields['barrio']['form']; ?>
		</div>
		<div class="form-group col-md-2">
			<?php echo $fields['manzana']['label']; ?>
			<?php echo $fields['manzana']['form']; ?>
		</div>
		<div class="form-group col-md-2">
			<?php echo $fields['casa']['label']; ?>
			<?php echo $fields['casa']['form']; ?>
		</div>
		<div class="form-group col-md-2">
			<?php echo $fields['codigo_postal']['label']; ?>
			<?php echo $fields['codigo_postal']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['fecha_nacimiento']['label']; ?>
			<?php echo $fields['fecha_nacimiento']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['localidad']['label']; ?>
			<?php echo $fields['localidad']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['telefono_fijo']['label']; ?>
			<?php echo $fields['telefono_fijo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['telefono_movil']['label']; ?>
			<?php echo $fields['telefono_movil']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<input type="hidden" name="id" value="<?php echo $persona_id; ?>" id="id"/>
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php if($txt_btn === 'Editar'): ?>
	<?php echo form_submit(array('class' => 'btn btn-warning pull-right', 'title' => 'Editar'), 'Editar'); ?>
	<?php else: ?>
	<?php echo zetta_form_submit($txt_btn); ?>
	<?php endif; ?>
	<?php // echo ($txt_btn === 'Eliminar') ? form_hidden('escuela_id', $escuela_id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>	
$(document).ready(function() {
	agregar_eventos($('#form_editar_persona'));
});
</script>