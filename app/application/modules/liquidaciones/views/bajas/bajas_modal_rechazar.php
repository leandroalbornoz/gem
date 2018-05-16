<script>
	$(document).ready(function() {
		agregar_eventos($('#form_rechazar_baja'));
		setTimeout(function() {
			$('#motivo_rechazo').focus();
		}, 500);
	});
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_rechazar_baja')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<b>Persona:</b> <span><?php echo $baja->persona; ?></span><br>
			<b>Régimen:</b> <span><?php echo $baja->regimen; ?></span><br>
		</div>
		<div class="form-group col-md-6">
			<b>Alta:</b> <span><?php echo (new DateTime($servicio->fecha_alta))->format('d/m/Y'); ?></span><br>
			<b>Baja:</b> <span><?php echo empty($servicio->fecha_baja) ? 'No' : (new DateTime($servicio->fecha_baja))->format('d/m/Y'); ?></span><br>
			<b>Días:</b> <span><?php echo $baja->dias; ?></span>
			<?php if ($servicio->regimen_tipo_id === '2'): ?>
				<b>Obligaciones:</b> <span><?php echo $baja->obligaciones; ?></span><br>
			<?php endif; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['motivo_rechazo']['label']; ?>
			<?php echo $fields['motivo_rechazo']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo form_hidden('id', $baja->id); ?>
	<?php echo form_hidden('escuela_id', $escuela->id); ?>
	<?php echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => 'Rechazar Baja'), 'Rechazar Baja'); ?>
</div>
<?php echo form_close(); ?>