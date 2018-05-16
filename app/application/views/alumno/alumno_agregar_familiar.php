<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agregar_familiar')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-4">
			<?php echo $fields2['p_documento_tipo']['label']; ?>
			<?php echo $fields2['p_documento_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields2['p_documento']['label']; ?>
			<?php echo $fields2['p_documento']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<label>&nbsp;</label><br/>
			<button class="btn btn-default" id="btn-search" type="button">
				<i class="fa fa-search"></i>
			</button>
			<button class="btn btn-default" id="btn-clear" type="button">
				<i class="fa fa-times"></i>
			</button>
			<button class="btn btn-default" id="btn-dni_duplicado" type="button" data-toggle="tooltip" title="Seleccionar si el documento ingresado no corresponde a la persona que se muestra!!">
				<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
			</button>
			<span class="label label-danger" id="dni_existente"></span>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields_familia['parentesco_tipo']['label']; ?>
			<?php echo $fields_familia['parentesco_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_familia['convive']['label']; ?>
			<?php echo $fields_familia['convive']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields2['p_apellido']['label']; ?>
			<?php echo $fields2['p_apellido']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields2['p_nombre']['label']; ?>
			<?php echo $fields2['p_nombre']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields2['p_nivel_estudio']['label']; ?>
			<?php echo $fields2['p_nivel_estudio']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields2['p_ocupacion']['label']; ?>
			<?php echo $fields2['p_ocupacion']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields2['p_telefono_movil']['label']; ?>
			<?php echo $fields2['p_telefono_movil']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields2['p_prestadora']['label']; ?>
			<?php echo $fields2['p_prestadora']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields2['p_email']['label']; ?>
			<?php echo $fields2['p_email']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo zetta_form_submit($txt_btn); ?>
	<input type="hidden" name="p_persona_id" value="" id="p_persona_id"/>
	<input type="hidden" name="p_documento_tipo" value="" id="p_documento_tipo_id"/>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $familia->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		dni_d = document.getElementById('btn-dni_duplicado');
		agregar_eventos($('#form_agregar_familiar'))
		$('#p_apellido,#p_nombre').attr('readonly', true);
		$('#p_telefono_movil,#p_email').attr('readonly', true);
		$('#p_nivel_estudio').attr('disabled', true);
		$('#p_nivel_estudio')[0].selectize.disable();
		$('#parentesco_tipo')[0].selectize.disable();
		$('#convive')[0].selectize.disable();
		$('#p_ocupacion').attr('disabled', true);
		$('#p_ocupacion')[0].selectize.disable();
		$('#p_prestadora').attr('disabled', true);
		$('#p_prestadora')[0].selectize.disable();
		$('#btn-dni_duplicado').attr('disabled', true);
		$('#btn-clear').click(function() {
			$('#btn-clear').attr('disabled', true);
			$('#btn-search').attr('disabled', false);
			$('#p_apellido,#p_nombre').attr('readonly', true);
			$('#p_telefono_movil,#p_email').attr('readonly', true);
			$('#p_nivel_estudio').attr('disabled', true);
			$('#p_ocupacion').attr('disabled', true);
			$('#p_nivel_estudio')[0].selectize.enable();
			$('#p_nivel_estudio')[0].selectize.setValue('');
			$('#p_nivel_estudio')[0].selectize.disable();
			$('#p_ocupacion')[0].selectize.enable();
			$('#p_ocupacion')[0].selectize.setValue('');
			$('#p_ocupacion')[0].selectize.disable();
			$('#p_prestadora')[0].selectize.enable();
			$('#p_prestadora')[0].selectize.setValue('');
			$('#p_prestadora')[0].selectize.disable();
			$('#parentesco_tipo')[0].selectize.disable();
			$('#convive')[0].selectize.disable();
			$('#p_documento').attr('readonly', false);
			$('#p_documento_tipo')[0].selectize.enable();
			$('#p_documento_tipo')[0].selectize.setValue(1);
			$('#p_apellido,#p_nombre').val('');
			$('#p_documento').select();
			$('#btn-submit').attr('disabled', false);
			$('#dni_existente').text(null);
			$('#btn-dni_duplicado').attr('disabled', true);
		});
		$('#btn-search').click(function() {
			var documento_tipo = $('#p_documento_tipo')[0].selectize.getValue();
			var documento = $('#p_documento').val();
			if (documento_tipo !== '' && documento !== '') {
				$.ajax({
					type: 'GET',
					url: 'ajax/get_persona?',
					data: {documento_tipo: documento_tipo, documento: documento},
					dataType: 'json',
					success: function(result) {
						$('#p_apellido,#p_nombre').attr('readonly', false);
						$('#p_telefono_movil,#p_email').attr('readonly', true);
						$('#p_nivel_estudio').attr('disabled', true);
						$('#p_ocupacion').attr('disabled', true);
						$('#p_prestadora').attr('disabled', true);
						$('#parentesco_tipo')[0].selectize.enable();
						$('#convive')[0].selectize.enable();
						$('#btn-clear').attr('disabled', false);
						$('#btn-search').attr('disabled', true);
						$('#btn-dni_duplicado').attr('disabled', false);
						if (result !== '') {
							$('#p_persona_id').val(result.id);
							$('#p_nivel_estudio')[0].selectize.enable();
							$('#p_nivel_estudio')[0].selectize.setValue(result.nivel_estudio_id);
							$('#p_nivel_estudio')[0].selectize.disable();
							$('#p_ocupacion')[0].selectize.enable();
							$('#p_ocupacion')[0].selectize.setValue(result.ocupacion_id);
							$('#p_ocupacion')[0].selectize.disable();
							$('#p_prestadora')[0].selectize.enable();
							$('#p_prestadora')[0].selectize.setValue(result.prestadora_id);
							$('#p_prestadora')[0].selectize.disable();
							$('#p_nombre').val(result.nombre);
							$('#p_apellido').val(result.apellido);
							$('#p_telefono_movil').val(result.telefono_movil);
							$('#p_email').val(result.email);
							$('btn-dni_duplicado').tooltip();
						} else {
							$('#p_persona_id').val(null);
							$('#btn-dni_duplicado').attr('disabled', true);
							$('#p_apellido,#p_nombre').prop('readonly', false);
							$('#p_telefono_movil,#p_email').prop('readonly', false);
							$('#p_nivel_estudio')[0].selectize.enable();
							$('#p_ocupacion')[0].selectize.enable();
							$('#p_prestadora')[0].selectize.enable();
							$('#p_documento').attr('readonly', true);
							$('#p_documento_tipo')[0].selectize.enable();
							$('#documento_tipo_id').val($('#p_documento_tipo')[0].selectize.getValue());
							$('#p_documento_tipo')[0].selectize.disable();
						}
						$('#p_documento').attr('readonly', true);
						$('#p_documento_tipo_id').val($('#p_documento_tipo')[0].selectize.getValue());
						$('#p_documento_tipo')[0].selectize.disable();
						$('#btn-submit').attr('disabled', false);
					}
				});
			}
		});

		$('#btn-dni_duplicado').click(function() {
			alert("Recuerde que la creacion de un documento duplicado traera inconvenientes futuros, por favor verifique bien si esta ingresando el numero correcto de documento!!");
			$('#p_documento_tipo')[0].selectize.setValue(6);
			var documento_tipo = $('#p_documento_tipo')[0].selectize.getValue();
			var documento = $('#p_documento').val();
			if (documento_tipo !== '' && documento !== '') {
				$.ajax({
					type: 'GET',
					url: 'ajax/get_persona?',
					data: {documento_tipo: documento_tipo, documento: documento},
					dataType: 'json',
					success: function(result) {
						$('#dni_existente').text(null);
						$('#p_apellido,#p_nombre').attr('readonly', true);
						$('#p_telefono_movil,#p_email').attr('readonly', true);
						$('#p_nivel_estudio').attr('disabled', true);
						$('#p_ocupacion').attr('disabled', true);
						$('#btn-clear').attr('disabled', false);
						$('#btn-search').attr('disabled', true);
						$('#p_nivel_estudio')[0].selectize.setValue('');
						$('#p_ocupacion')[0].selectize.setValue('');
						$('#p_prestadora')[0].selectize.setValue('');
						$('#p_apellido,#p_nombre').val('');
						$('#p_telefono_movil,#p_email').val('');
						if (result !== '') {
							$('#p_persona_id').val(result.id);
							$('#p_nivel_estudio')[0].selectize.enable();
							$('#p_nivel_estudio')[0].selectize.setValue(result.nivel_estudio_id);
							$('#p_nivel_estudio')[0].selectize.disable();
							$('#p_ocupacion')[0].selectize.enable();
							$('#p_ocupacion')[0].selectize.setValue(result.ocupacion_id);
							$('#p_ocupacion')[0].selectize.disable();
							$('#p_prestadora')[0].selectize.enable();
							$('#p_prestadora')[0].selectize.setValue(result.ocupacion_id);
							$('#p_prestadora')[0].selectize.disable();
							$('#p_nombre').val(result.nombre);
							$('#p_apellido').val(result.apellido);
							$('#p_telefono_movil').val(result.telefono_movil);
							$('#p_email').val(result.email);
						} else {
							$('#p_persona_id').val(null);
							$('#btn-dni_duplicado').attr('disabled', true);
							$('#p_apellido,#p_nombre').prop('readonly', false);
							$('#p_telefono_movil,#p_email').prop('readonly', false);
							$('#p_nivel_estudio')[0].selectize.enable();
							$('#p_ocupacion')[0].selectize.enable();
							$('#p_prestadora')[0].selectize.enable();
							$('#p_documento').attr('readonly', true);
							$('#p_documento_tipo')[0].selectize.enable();
							$('#documento_tipo_id').val($('#p_documento_tipo')[0].selectize.getValue());
							$('#p_documento_tipo')[0].selectize.disable();
						}
						$('#p_documento').attr('readonly', true);
						$('#p_documento_tipo_id').val($('#p_documento_tipo')[0].selectize.getValue());
						$('#p_documento_tipo')[0].selectize.disable();
						$('#btn-submit').attr('disabled', false);
					}
				});
			}
		});
	});
</script>