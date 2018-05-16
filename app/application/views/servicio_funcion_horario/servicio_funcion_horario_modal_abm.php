<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['dia']['label']; ?>
			<?php echo $fields['dia']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['hora_desde']['label']; ?>
			<?php echo $fields['hora_desde']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['hora_hasta']['label']; ?>
			<?php echo $fields['hora_hasta']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo zetta_form_submit($txt_btn); ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $servicio_funcion_horario->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#hora_desde').inputmask('hh:mm');
		$('#hora_hasta').inputmask('hh:mm');
	});
</script>