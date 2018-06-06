<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-sm-12">
			<?php foreach ($dias as $dia): ?>
				<label class="checkbox-inline">
					<input type="checkbox" name="dias[<?= $dia->id ?>]" value="<?= $dia->id ?>" class="dias"> <?= mb_substr($dia->nombre, 0, 2) ?>
				</label>
			<?php endforeach; ?>
		</div>
		<div class="form-group col-sm-6">
			<label>Desde</label>
			<input type="text" name="hora_desde" id="hora_desde" class="form-control"/>
		</div>
		<div class="form-group col-sm-6">
			<label>Hasta</label>
			<input type="text" name="hora_hasta" id="hora_hasta" class="form-control" />
		</div>
		<input type="hidden" name="servicio_funcion_id" value="<?= $servicio_funcion->id ?>"/>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<button type="submit" class="btn btn-primary pull-right" title="Confirmar">Cargar</button>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#hora_desde,#hora_hasta').inputmask('hh:mm');
	});
</script>