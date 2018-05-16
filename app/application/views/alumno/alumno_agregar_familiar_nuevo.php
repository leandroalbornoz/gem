<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agregar_familiar_nuevo')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['parentesco_tipo']['label']; ?>
			<?php echo $fields['parentesco_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['convive']['label']; ?>
			<?php echo $fields['convive']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields_p['p_documento_tipo']['label']; ?>
			<?php echo $fields_p['p_documento_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_p['p_documento']['label']; ?>
			<span class="label label-danger" id="documento_existente_modal"></span>
			<?php echo $fields_p['p_documento']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields_p['p_apellido']['label']; ?>
			<?php echo $fields_p['p_apellido']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_p['p_nombre']['label']; ?>
			<?php echo $fields_p['p_nombre']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields_p['p_nivel_estudio']['label']; ?>
			<?php echo $fields_p['p_nivel_estudio']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_p['p_ocupacion']['label']; ?>
			<?php echo $fields_p['p_ocupacion']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields_p['p_telefono_movil']['label']; ?>
			<?php echo $fields_p['p_telefono_movil']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_p['p_prestadora']['label']; ?>
			<?php echo $fields_p['p_prestadora']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields_p['p_email']['label']; ?>
			<?php echo $fields_p['p_email']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Guardar', 'id' => 'boton_guardar'), 'Guardar'); ?>
</div>
<?php echo form_close(); ?>

<script>
	$(document).ready(function() {
		agregar_eventos($('#form_agregar_familiar_nuevo'))
		$('#form_agregar_familiar_nuevo #p_documento,#form_agregar_familiar_nuevo #p_documento_tipo').change(verificar_doc_repetido);
		function verificar_doc_repetido(e) {
			var documento_tipo = $('#form_agregar_familiar_nuevo #p_documento_tipo')[0].selectize.getValue();
			var documento = $('#form_agregar_familiar_nuevo #p_documento').val();
			if (documento_tipo === '8') {
				$.ajax({
					type: 'GET',
					url: 'ajax/get_indocumentado?',
					data: {documento_tipo: documento_tipo, documento: documento, id: '<?= empty($persona) ? '' : $persona->id; ?>'},
					dataType: 'json',
					success: function(result) {
						if (result !== '') {
							$('#form_agregar_familiar_nuevo #documento_existente_modal').text(null);
							$('#form_agregar_familiar_nuevo #boton_guardar').attr('disabled', false);
							$('#form_agregar_familiar_nuevo #p_documento').closest('.form-group').removeClass("has-error");
							$('#form_agregar_familiar_nuevo #p_documento').val(result);
						}
					}
				});
			} else if (documento_tipo !== '' && documento !== '') {
				$.ajax({
					type: 'GET',
					url: 'ajax/get_persona?',
					data: {documento_tipo: documento_tipo, documento: documento, id: '<?= empty($persona) ? '' : $persona->id; ?>'},
					dataType: 'json',
					success: function(result) {
						if (result !== '') {
							$('#form_agregar_familiar_nuevo #documento_existente_modal').text('Doc. en Uso');
							$('#form_agregar_familiar_nuevo #boton_guardar').attr('disabled', true);
							$('#form_agregar_familiar_nuevo #p_documento').closest('.form-group').addClass("has-error");

						} else {
							$('#form_agregar_familiar_nuevo #documento_existente_modal').text(null);
							$('#form_agregar_familiar_nuevo #boton_guardar').attr('disabled', false);
							$('#form_agregar_familiar_nuevo #p_documento').closest('.form-group').removeClass("has-error");

						}
					}
				});
			}
		}
	});
</script>