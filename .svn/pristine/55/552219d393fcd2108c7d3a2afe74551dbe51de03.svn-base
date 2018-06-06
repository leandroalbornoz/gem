<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_asignar_rol')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-sm-12">
			<p>Se asignará el rol de cursada al usuario:</p>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['usuario']['label']; ?>
			<?php echo $fields['usuario']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['cuil']['label']; ?>
			<?php echo $fields['cuil']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_division['curso']['label']; ?>
			<?php echo $fields_division['curso']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_division['division']['label']; ?>
			<?php echo $fields_division['division']['form']; ?>
		</div>
	</div><br>
	<div class="row">
		<div class="col-md-12">
			
		</div>	
	</div>	
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo form_hidden('usuario_id', $usuario->id); ?>
	<?php echo form_hidden('division_id', $division->id); ?>
	<?php echo form_close(); ?>
</div>
