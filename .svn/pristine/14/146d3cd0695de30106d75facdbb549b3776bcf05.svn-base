<script>
	$(document).ready(function() {
		var xhr_tipo_clasificacion;
		var select_titulo_tipo, $select_titulo_tipo;
		var select_tipo_clasificacion, $select_tipo_clasificacion;

		$select_titulo_tipo = $('#titulo_tipo').selectize({
			onChange: titulo_tipo_actualizado
		});

		$select_tipo_clasificacion = $('#titulo_tipo_clasificacion').selectize({
			valueField: 'id',
			labelField: 'descripcion',
			searchField: ['titulo_tipo_id']
		});
		select_titulo_tipo = $select_titulo_tipo[0].selectize;
		select_tipo_clasificacion = $select_tipo_clasificacion[0].selectize;

		function titulo_tipo_actualizado(value) {
			select_tipo_clasificacion.enable();
			var valor = select_tipo_clasificacion.getValue();
			select_tipo_clasificacion.disable();
			select_tipo_clasificacion.clearOptions();
			if (value == '') {
				return;
			}
			select_tipo_clasificacion.load(function(callback) {
				xhr_tipo_clasificacion && xhr_tipo_clasificacion.abort();
				xhr_tipo_clasificacion = $.ajax({
					url: 'juntas/titulo/get_titulo_tipo_clasificacion',
					type: 'POST',
					dataType: 'json',
					data: {
						titulo_tipo: value
					},
					success: function(results) {
						select_tipo_clasificacion.enable();
						callback(results);
						if (results.length === 1) {
							select_tipo_clasificacion.setValue(results[0].id);
						} else {
							select_tipo_clasificacion.setValue(valor);
						}
					},
					error: function() {
						callback();
					}
				});
			});
		}

		$('#NomTitLon').selectize({
			valueField: 'id',
			labelField: 'titulo',
			searchField: 'titulo',
			create: true,
			options: [],
			load: function(query, callback) {
				if (query.length < 4)
					return callback(); //if (!query.length)
				$.ajax({
					url: 'juntas/persona_titulo/ajax_buscar_titulo',
					type: 'POST',
					dataType: 'json',
					data: {
						titulo: query
					},
					error: function() {
						callback();
					},
					success: function(res) {
						callback(res);
					}
				});
			}
		});
	});
</script>
<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_titulo_persona_modal')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['titulo_tipo']['label']; ?>
			<?php echo $fields['titulo_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['NomTitLon']['label']; ?>
			<?php echo $fields['NomTitLon']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['titulo_tipo_clasificacion']['label']; ?>
			<?php echo $fields['titulo_tipo_clasificacion']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $titulo->id) : ''; ?>
</div>
<?php echo form_close(); ?>
