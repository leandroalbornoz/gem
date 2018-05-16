<script>
	$(document).ready(function() {
		var xhr;
		var select_rol, $select_rol;
		var select_entidad, $select_entidad;

		$select_rol = $('#para_rol').selectize({
			onChange: actualizar_entidad
		});

		$select_entidad = $('#para_entidad').selectize({
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
						url: 'ajax/get_entidades_msj/' + value,
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
<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['de']['label']; ?>
			<?php echo $fields['de']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['para_rol']['label']; ?>
			<?php echo $fields['para_rol']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['para_entidad']['label']; ?>
			<?php echo $fields['para_entidad']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['tema']['label']; ?>
			<?php echo $fields['tema']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['asunto']['label']; ?>
			<?php echo $fields['asunto']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['mensaje']['label']; ?>
			<?php echo $fields['mensaje']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $mensaje->id) : ''; ?>
</div>
<?php echo form_close(); ?>