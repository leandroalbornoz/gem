<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_division')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['asunto']['label']; ?>
			<?php echo $fields['asunto']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['estado']['label']; ?>
			<?php echo $fields['estado']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-12">
			<label for="detalle">Detalle</label>
			<textarea name="detalle" cols="40" rows="5" id="detalle" class="form-control" required="" ></textarea>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Agregar incidencia'), 'Agregar incidencia'); ?>
	<?php echo form_hidden('servicio_id', $servicio->id); ?>
</div>
<?php echo form_close(); ?>