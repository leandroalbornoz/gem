<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_abono_alumno_modal')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['alumno']['label']; ?>
			<?php echo $fields['alumno']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['numero_abono']['label']; ?>
			<?php echo $fields['numero_abono']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['abono_tipo']['label']; ?>
			<?php echo $fields['abono_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields['monto']['label']; ?>
			<?php echo $fields['monto']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['motivo_alta']['label']; ?>
			<?php echo $fields['motivo_alta']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $abono_alumno->id) : ''; ?>
</div>
<input type="hidden" name="division_id" value="<?php echo $division_id; ?>" id="division_id"/>
<?php echo form_hidden('url_redireccion', $url_redireccion); ?>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		$('#monto').inputmask('decimal', {radixPoint: ',', digits: 2, autoUnmask: true, placeholder: '', removeMaskOnSubmit: true, onUnMask: function(value) {
				return value.replace('.', '').replace(',', '.');
			}});
	});
</script>