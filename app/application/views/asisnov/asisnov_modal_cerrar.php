<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-sm-12">
			<p>¿Está seguro que desea cerrar?</p>
		</div>
	</div>
</div>
<div class="modal-footer">
	<input type="hidden" name="id" value="<?= $planilla->id ?>">
	<span class="badge bg-red" id="alerta"></span>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => 'Cerrar', 'id' => 'btn_submit_novedad'), 'Cerrar'); ?>
</div>
<?php echo form_close(); ?>