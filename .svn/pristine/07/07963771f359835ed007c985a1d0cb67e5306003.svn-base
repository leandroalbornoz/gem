<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_seleccionar_rol')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-sm-12">
			<?php echo $fields['usuario']['label']; ?>
			<?php echo $fields['usuario']['form']; ?>
		</div>
		<div class="form-group col-sm-12">
			<?php echo $fields['rol']['label']; ?>
			<?php echo $fields['rol']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo form_hidden('usuario_id', $usuario->id); ?>
</div>
<?php echo form_close(); ?>
<script>
	agregar_eventos($('#form_seleccionar_rol'));
</script>