<style>
	#formulario_novedad .table{
		margin-bottom: 0;
	}
</style>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'formulario_pasar_auditoria')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['fecha_desde']['label']; ?>
			<?php echo $fields['fecha_desde']['form']; ?>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields['dias']['label']; ?>
			<?php echo $fields['dias']['form']; ?>
		</div>
		<?php if ($servicio->regimen_tipo_id === '2'): ?>
			<div class="form-group col-md-3">
				<?php echo $fields['obligaciones']['label']; ?>
				<?php echo $fields['obligaciones']['form']; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<div class="modal-footer">
	<span class="badge bg-red" id="alerta"></span>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Pasar a Auditoría'), 'Pasar a Auditoría'); ?>
	<?php echo form_hidden('id', $baja->id); ?>
	<?php echo form_hidden('escuela_id', $servicio->escuela_id); ?>
</div>
<?php echo form_close(); ?>