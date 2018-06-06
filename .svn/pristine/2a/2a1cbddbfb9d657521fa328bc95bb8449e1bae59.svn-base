<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_editar_cuenta')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['descripcion_cuenta']['label']; ?>
			<?php echo $fields['descripcion_cuenta']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['numero_cuenta']['label']; ?>
			<?php echo $fields['numero_cuenta']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo zetta_form_submit($txt_btn); ?>
	<input type="hidden" name="tribunal_cuenta_id" value="<?php echo $tribunal_cuenta->id;?>" id="tribunal_cuenta_id"/>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $escuela->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_agregar_fondos'))
	});
</script>