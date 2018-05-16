<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_presupuesto')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
	</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?> </h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['numero']['label']; ?>
			<?php echo $fields['numero']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['nombre']['label']; ?>
			<?php echo $fields['nombre']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields2['monto_presupuestado']['label']; ?>
			<?php echo $fields2['monto_presupuestado']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields2['monto_entregado']['label']; ?>
			<?php echo $fields2['monto_entregado']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<?php echo form_hidden('monto_presupuestado', $monto_ideal); ?>
	<?php echo form_hidden('comedor_presupuesto_id', $comedor_presupuesto->id); ?>
	<?php if (empty($comedor_presupuesto->monto_entregado)): ?>
		<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"> <?php echo isset($txt_btn) ? 'Asignar' : 'Cancelar'; ?>
		</button>
		<?php echo form_submit(array('class' => 'btn btn-success pull-right', 'title' => 'Asignar'), 'Asignar'); ?>
	<?php else: ?>
		<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"> <?php echo isset($txt_btn) ? 'Editar' : 'Cancelar'; ?>
		</button>
		<?php echo form_submit(array('class' => 'btn btn-warning pull-right', 'title' => 'Editar'), 'Editar'); ?>
	<?php endif; ?>
</div>
<?php echo form_close(); ?>