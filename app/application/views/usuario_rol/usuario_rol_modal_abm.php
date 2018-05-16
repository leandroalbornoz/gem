<?php if ($txt_btn === 'Agregar' || $txt_btn === 'Simular'): ?>
	<script>
		$(document).ready(function() {
			var xhr;
			var select_rol, $select_rol;
			var select_entidad, $select_entidad;

			$select_rol = $('#rol').selectize({
				onChange: actualizar_entidad
			});

			$select_entidad = $('#entidad').selectize({
				valueField: 'id',
				labelField: 'nombre',
				searchField: ['nombre']
			});

			select_rol = $select_rol[0].selectize;
			select_entidad = $select_entidad[0].selectize;
			if (select_rol.getValue() !== '') {
				actualizar_entidad(select_rol.getValue());
			}

			function actualizar_entidad(value) {
				select_entidad.enable();
				var valor = select_entidad.getValue();
				select_entidad.disable();
				select_entidad.clearOptions();
				if (value !== '') {
					select_entidad.load(function(callback) {
						xhr && xhr.abort();
						xhr = $.ajax({
							url: 'ajax/get_entidades/' + value,
							dataType: 'json',
							success: function(results) {
								select_entidad.enable();
								callback(results);
								if (results.length === 1) {
									select_entidad.setValue(results[0].id);
									select_entidad.disable();
								} else {
									select_entidad.setValue(valor);
								}
							},
							error: function() {
								callback();
							}
						})
					});
				}
			}
		});
	</script>
<?php endif; ?>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<?php foreach ($fields as $field): ?>
		<div class="form-group">
			<?php echo $field['label']; ?>
			<?php echo $field['form']; ?>
		</div>
	<?php endforeach; ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php if ($txt_btn === 'Simular'): ?>
		<button type="submit" class="btn btn-primary pull-right" title="Simular">Simular</button>
	<?php else: ?>
		<?php echo zetta_form_submit($txt_btn); ?>
	<?php endif; ?>
	<?php echo $txt_btn === 'Eliminar' ? form_hidden('id', $usuario_rol->id) : ''; ?>
</div>
<?php echo form_close(); ?>