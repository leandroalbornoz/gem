<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_seleccionar_periodo')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<?php if ($txt_btn === 'Eliminar'): ?>
	<?php $data_submit = array('class' => 'btn btn-danger pull-right', 'title' => $txt_btn); ?>
<?php else: ?>
	<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
<?php endif; ?>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['ciclo_lectivo']['label']; ?>
			<?php echo $fields['ciclo_lectivo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['periodo']['label']; ?>
			<?php echo $fields['periodo']['form']; ?>
		</div>
		<input type="hidden" name="cursada_id" value="<?php echo $cursada->id; ?>">
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_seleccionar_periodo'));
	});
</script>
