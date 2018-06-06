<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">
		<?= !empty($title) ? $title : ''; ?>
	</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['documento']['label']; ?>
			<?php echo $fields['documento']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['cuil']['label']; ?>
			<?php echo $fields['cuil']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['apellido']['label']; ?>
			<?php echo $fields['apellido']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['nombre']['label']; ?>
			<?php echo $fields['nombre']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['fecha_nacimiento']['label']; ?>
			<?php echo $fields['fecha_nacimiento']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['sexo']['label']; ?>
			<?php echo $fields['sexo']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['calle']['label']; ?>
			<?php echo $fields['calle']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['calle_numero']['label']; ?>
			<?php echo $fields['calle_numero']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['piso']['label']; ?>
			<?php echo $fields['piso']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['departamento']['label']; ?>
			<?php echo $fields['departamento']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['localidad']['label']; ?>
			<?php echo $fields['localidad']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['codigo_postal']['label']; ?>
			<?php echo $fields['codigo_postal']['form']; ?>
		</div>
	</div>
	<div class="row">
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
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $inscripcion->persona_id) : ''; ?>
</div>
<?php echo form_close(); ?>