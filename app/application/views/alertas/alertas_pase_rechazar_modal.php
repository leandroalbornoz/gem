<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_division')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<h4>¿Esta seguro de que desea rechazar el pase?</h4>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['persona']['label']; ?>
			<?php echo $fields['persona']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['escuela_origen']['label']; ?>
			<?php echo $fields['escuela_origen']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['motivo_rechazo']['label']; ?>
			<?php echo $fields['motivo_rechazo']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right btn-danger', 'title' => 'Rechazar Ingreso'), 'Rechazar Ingreso'); ?>
	<?php echo form_hidden('id', $pase_nuevo->id); ?>
</div>
<?php echo form_close(); ?>