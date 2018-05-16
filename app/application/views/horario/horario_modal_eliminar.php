<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-sm-6">
			<?php echo $fields['dia']['label']; ?>
			<?php echo $fields['dia']['form']; ?>
		</div>
		<div class="form-group col-sm-6">
			<?php echo $fields['hora_catedra']['label']; ?>
			<?php echo $fields['hora_catedra']['form']; ?>
		</div>
		<div class="form-group col-sm-4">
			<?php echo $fields['hora_desde']['label']; ?>
			<?php echo $fields['hora_desde']['form']; ?>
		</div>
		<div class="form-group col-sm-4">
			<?php echo $fields['hora_hasta']['label']; ?>
			<?php echo $fields['hora_hasta']['form']; ?>
		</div>
		<div class="form-group col-sm-4">
			<?php echo $fields['obligaciones']['label']; ?>
			<?php echo $fields['obligaciones']['form']; ?>
		</div>
	</div>
	<input type="hidden" name="horario_id" value="<?= $horario_id; ?>">
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<button type="submit" class="btn btn-danger pull-right" title="Eliminar">Eliminar</button>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#obligaciones').inputmask('decimal', {radixPoint: ',', digits: 1, autoUnmask: true, placeholder: '', removeMaskOnSubmit: true, onUnMask: function(value) {
				return value.replace('.', '').replace(',', '.');
			}});
	});
</script>