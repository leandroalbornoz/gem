<script>
	$(document).ready(function() {
		agregar_eventos($('#formulario_escuela_grupo'));
	});
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'formulario_escuela_grupo')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-sm-12">
			<?php if ($txt_btn === 'Quitar'): ?>
				<p>¿Está seguro que desea quitar la escuela?</p>
			<?php elseif ($txt_btn === 'Agregar'): ?>
				<?php foreach ($fields as $field): ?>
					<div class="form-group col-sm-12">
						<?php echo $field['label']; ?> 
						<?php echo $field['form']; ?>
					</div>
				<?php endforeach; ?>
				<input type="hidden" name="escuela_grupo_id" value="<?= $escuela_grupo->id; ?>">
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<?php if ($txt_btn === 'Quitar'): ?>
		<input type="hidden" name="escuela_grupo_id" value="<?= $escuela_grupo->id; ?>">
		<input type="hidden" name="escuela_grupo_escuela_id" value="<?= $escuela_grupo_escuela->id; ?>">
	<?php endif; ?>
	<span class="badge bg-red" id="alerta"></span>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	
	<?php echo form_submit(array('class' => 'btn '.($txt_btn === 'Quitar' ? 'btn-danger' : 'btn-primary').' pull-right', 'title' => $txt_btn, 'id' => 'btn_submit_novedad'), $txt_btn); ?>
</div>
<?php echo form_close(); ?>