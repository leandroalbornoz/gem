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
			<?php echo $fields['persona']['label']; ?>
			<?php echo $fields['persona']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['titulo']['label']; ?>
			<?php echo $fields['titulo']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['entidad_emisora']['label']; ?>
			<?php echo $fields['entidad_emisora']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['modalidad']['label']; ?>
			<?php echo $fields['modalidad']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['fecha_emision']['label']; ?>
			<?php echo $fields['fecha_emision']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['promedio']['label']; ?>
			<?php echo $fields['promedio']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['norma_legal_tipo']['label']; ?>
			<?php echo $fields['norma_legal_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['norma_legal_numero']['label']; ?>
			<?php echo $fields['norma_legal_numero']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['norma_legal_año']['label']; ?>
			<?php echo $fields['norma_legal_año']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['años_cursado']['label']; ?>
			<?php echo $fields['años_cursado']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['cantidad_hs_reloj']['label']; ?>
			<?php echo $fields['cantidad_hs_reloj']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['registro']['label']; ?>
			<?php echo $fields['registro']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $persona_titulo->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		$("#promedio").on('change', function() {
			$(this).val($(this).val().replace(/,/g, '.'));
		});
		$("#años_cursado").on('change', function() {
			$(this).val($(this).val().replace(/,/g, '.'));
		});
		$('#titulo').selectize({
			valueField: 'id',
			labelField: 'titulo',
			searchField: 'titulo',
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

		$('#entidad_emisora').selectize({
			valueField: 'id',
			labelField: 'entidad_emisora',
			searchField: 'entidad_emisora',
			options: [],
			load: function(query, callback) {
				if (query.length < 4)
					return callback(); //if (!query.length)
				$.ajax({
					url: 'juntas/persona_titulo/ajax_buscar_entidad_emisora',
					type: 'POST',
					dataType: 'json',
					data: {
						entidad_emisora: query
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