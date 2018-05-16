<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_servicio_funcion')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-3">
			<?php echo $fields['fecha_desde']['label']; ?>
			<?php echo $fields['fecha_desde']['form']; ?>
		</div>
		<div class="form-group col-md-9">
			<?php echo $fields['funcion']['label']; ?>
			<?php echo $fields['funcion']['form']; ?>
		</div>
		<div class="form-group col-md-6 campos-funcion">
			<?php echo $fields['tipo_destino']['label']; ?>
			<?php echo $fields['tipo_destino']['form']; ?>
		</div>
		<div class="form-group col-md-6 campos-funcion">
			<?php echo $fields['destino']['label']; ?>
			<?php echo $fields['destino']['form']; ?>
		</div>
		<div class="form-group col-md-6 campos-funcion">
			<?php echo $fields['norma']['label']; ?>
			<?php echo $fields['norma']['form']; ?>
		</div>
		<div class="form-group col-md-6 campos-funcion">
			<?php echo $fields['tarea']['label']; ?>
			<?php echo $fields['tarea']['form']; ?>
		</div>
		<div class="form-group col-md-6 campos-funcion">
			<?php echo $fields['carga_horaria']['label']; ?>
			<?php echo $fields['carga_horaria']['form']; ?>
		</div>
		<div class="form-group col-md-6 campos-funcion">
			<?php echo $fields['fecha_hasta']['label']; ?>
			<?php echo $fields['fecha_hasta']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo zetta_form_submit($txt_btn); ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $servicio_funcion->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<?php if ($txt_btn === 'Agregar' || $txt_btn === 'Editar'): ?>
	<script>
		var xhr;
		var select_tipo_destino, $select_tipo_destino;
		var select_destino, $select_destino;
		$(document).ready(function() {
			$('.campos-funcion').hide();
			$('#fecha_desde').inputmask('d/m/y');
			$('#fecha_hasta').inputmask('d/m/y');
			$('#funcion').change(function() {
				$('#form_servicio_funcion :input[type="text"]').not('#fecha_desde').val('');
				$('#form_servicio_funcion .select').not('#funcion').val('');
				mostrar_campos();
			});

			$select_tipo_destino = $('#tipo_destino').selectize({
				onChange: actualizar_destino
			});

			$select_destino = $('#destino').selectize({
				valueField: 'id',
				labelField: 'nombre',
				searchField: ['nombre']
			});

			select_tipo_destino = $select_tipo_destino[0].selectize;
			select_destino = $select_destino[0].selectize;
			if (select_tipo_destino.getValue() !== '') {
				actualizar_destino(select_tipo_destino.getValue());
			}
		agregar_eventos($('#form_servicio_funcion'));
		<?php if ($txt_btn === 'Editar'): ?>
				mostrar_campos();
		<?php endif; ?>
		});

		function mostrar_campos() {
	<?php echo $fn_mostrar_campos; ?>
		}
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
<?php endif; ?>