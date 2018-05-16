<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_editar_racion')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<?php foreach ($raciones as $racion): ?>
			<div class="form-group col-md-6">
				<label>Descripción</label>
				<input type="text" name="descripcion[]" value="<?= $racion->descripcion; ?>" id="descripcion" class="form-control" disabled="true"/>
			</div>
			<div class="form-group col-md-6">
				<label for="monto">Monto</label>
				<input type="number" step="0.01" name="monto[]" value="<?= $racion->monto; ?>" id="monto" class="form-control"/>
			</div>
			<?php echo form_hidden('racion_id[]', $racion->id); ?>
		<?php endforeach; ?>
	</div>
</div>
<div class="modal-footer">
	<?php echo form_hidden('mes_actual', $mes_actual); ?>
	<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"> <?php echo isset($txt_btn) ? 'Cancelar' : 'Editar'; ?>
	</button>
	<?php echo form_submit(array('class' => 'btn btn-warning pull-right', 'title' => 'Cancelar'), 'Editar'); ?>
</div>
<?php echo form_close(); ?>