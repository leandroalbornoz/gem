<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">Agregar servicio por liquidación</h4>
</div>
<div class="modal-body">
	<div class="row">
		<h4 class="col-md-12"><?php echo "$persona->apellido, $persona->nombre (CUIL: $persona->cuil)"; ?></h4>
		<div class="form-group col-md-4">
			<?php echo $fields['fechaini']['label']; ?>
			<?php echo $fields['fechaini']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields['fechafin']['label']; ?>
			<?php echo $fields['fechafin']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields_s['fecha_alta']['label']; ?>
			<?php echo $fields_s['fecha_alta']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields_s['tipo_destino']['label']; ?>
			<?php echo $fields_s['tipo_destino']['form']; ?>
		</div>
		<div class="form-group col-md-8">
			<?php echo $fields_s['destino']['label']; ?>
			<?php echo $fields_s['destino']['form']; ?>
		</div>
		<div class="form-group col-md-5">
			<?php echo $fields['ug']['label']; ?>
			<?php echo $fields['ug']['form']; ?>
		</div>
		<div class="form-group col-md-5">
			<?php echo $fields['regimen']['label']; ?>
			<?php echo $fields['regimen']['form']; ?>
		</div>
		<div class="form-group col-md-2">
			<?php echo $fields['diasoblig']['label']; ?>
			<?php echo $fields['diasoblig']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['REVISTA']['label']; ?>
			<?php echo $fields['REVISTA']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['liquidacion_s']['label']; ?>
			<?php echo $fields['liquidacion_s']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary" title="Agregar">Agregar</button>
</div>
<script>
	var xhr;
	var select_tipo_destino, $select_tipo_destino;
	var select_destino, $select_destino;
	$(document).ready(function() {
		$('#fecha_alta').datepicker({
			format: "dd/mm/yyyy",
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true
		});
			var destino = $('#destino').val();
		$select_tipo_destino = $('#tipo_destino').selectize({
			onChange: actualizar_destino
		});

		$select_destino = $('#destino').selectize({
			valueField: 'id',
			labelField: 'nombre',
			searchField: ['nombre']
		});

		select_tipo_destino = $select_tipo_destino [0].selectize;
		select_destino = $select_destino[0].selectize;
		if (select_tipo_destino.getValue() !== '') {
			actualizar_destino(select_tipo_destino.getValue());
			select_destino.setValue(destino);
		}
	});

	function actualizar_destino(value) {
		select_destino.enable();
		var valor = select_destino.getValue();
		select_destino.disable();
		select_destino.clearOptions();
		select_destino.load(function(callback) {
			xhr && xhr.abort();
			xhr = $.ajax({
				url: 'ajax/get_destinos/' + value,
				dataType: 'json',
				success: function(results) {
					select_destino.enable();
					callback(results);
					if (results.length === 1) {
						select_destino.setValue(results[0].id);
						select_destino.disable();
					} else {
						select_destino.setValue(valor);
					}
				},
				error: function() {
					callback();
				}
			})
		});
	}
</script>