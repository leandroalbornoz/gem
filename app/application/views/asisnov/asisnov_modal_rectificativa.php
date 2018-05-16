<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'formulario_novedad')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-sm-12">
			<p>¿Está seguro que desea agregar la <?= (!isset($planilla->id)) ? ' planilla' : ' rectificativa '.($planilla->rectificativa + 1) ?>?</p>
		</div>
	</div>
</div>
<div class="modal-footer">
	<input type="hidden" name="id" value="<?php echo (!empty($planilla->id)) ? $planilla->id : '' ?>">
	<span class="badge bg-red" id="alerta"></span>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Agregar', 'id' => 'btn_submit_novedad'), 'Agregar'); ?>
</div>
<?php echo form_close(); ?>