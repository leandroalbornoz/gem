<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'selectizeForm')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['documento_bono']['label']; ?>
			<?php echo $fields['documento_bono']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['persona']['label']; ?>
			<?php echo $fields['persona']['form']; ?>
		</div>
		<?php if (isset($agregar) && !empty($agregar)): ?>
			<div class="form-group col-md-12">
				<?php echo $fields['cargos']['label']; ?>
				<?php echo $fields['cargos']['form']; ?>
			</div>
		<?php endif ?>
		<div class="form-group col-md-12">
			<?php echo $fields['cargo']['label']; ?>
			<?php echo $fields['cargo']['form']; ?>
		</div>
		<?php if ($txt_btn == 'Activar'): ?> 
			<div class="form-group col-md-12">
				<?php echo $fields['estado']['label']; ?>
				<?php echo $fields['estado']['form']; ?>
			</div>
		<?php endif; ?> 
		<div class="form-group col-md-12">
			<?php echo $fields['espacio']['label']; ?>
			<?php echo $fields['espacio']['form']; ?>
		</div>
		<div id="div_persona_matricula"  style="display: none;">
			<div class="form-group col-md-12">
				<u><h4>Datos de Matrícula:</h4></u>
			</div>
			<div class="form-group col-md-12">
				<?php echo $fields_matricula['matricula_tipo']['label']; ?>
				<?php echo $fields_matricula['matricula_tipo']['form']; ?>
			</div>
			<div class="form-group col-md-12">
				<?php echo $fields_matricula['matricula_numero']['label']; ?>
				<?php echo $fields_matricula['matricula_numero']['form']; ?>
			</div>
			<div class="form-group col-md-12">
				<?php echo $fields_matricula['matricula_vence']['label']; ?>
				<?php echo $fields_matricula['matricula_vence']['form']; ?>
			</div>
			<div class="form-group col-md-12" style="color:red; display: none;" id="div_error_matricula"> 
				<p>El Número de Matrícula solo debe ser numérico.</p>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'name' => 'submitform', 'id' => 'submitform', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar' || $txt_btn === 'Activar' || $txt_btn === 'Asignar') ? form_hidden('id', $persona_cargo->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
	agregar_eventos($('#selectizeForm'));
	$(document).ready(function() {
		$("#espacio").on('change', function() {
			if ($.inArray("101", $(this).val()) != -1) {
				$("#div_persona_matricula").show();
				$("#matricula_tipo").attr("required", "true");
				$("#matricula_numero").attr("required", "true");
				$("#matricula_numero").attr("minlength", "3");
				$("#matricula_vence").attr("required", "true");
			} else {
				$("#div_persona_matricula").hide();
			}
		});
		$("#matricula_numero").on('change', function() {
			if ($.isNumeric($('#matricula_numero').val())) {
				$("#div_error_matricula").hide();
				submitform.disabled = false;
			} else {
				submitform.disabled = true;
				$("#div_error_matricula").show();
			}
		});
		$('#espacio').selectize({
			plugins: ['remove_button'],
			valueField: 'espacio_id',
			labelField: 'espacio',
			searchField: 'espacio',
			options: [],
			load: function(query, callback) {
				if (query.length < 1)
					return callback(); //if (!query.length)
				$.ajax({
					url: 'juntas/persona_cargo/ajax_buscar_espacio_curricular',
					type: 'POST',
					dataType: 'json',
					data: {
						espacio: query,
						numero_area: '22',
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

