<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form-autoridad')); ?>
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
	<div class="row"><hr></div>
	<div class="row">
		<div class="form-group col-sm-4">
			<?php echo $fields_persona['p_documento_tipo']['label']; ?>
			<?php echo $fields_persona['p_documento_tipo']['form']; ?>
		</div>
		<div class="form-group col-sm-4">
			<?php echo $fields_persona['p_documento']['label']; ?>
			<?php echo $fields_persona['p_documento']['form']; ?>
		</div>
		<div class="form-group col-sm-4">
			<label>&nbsp;</label><br/>
			<button class="btn btn-default" id="btn-search" type="button">
				<i class="fa fa-search"></i>
			</button>
			<button class="btn btn-default" id="btn-clear" type="button">
				<i class="fa fa-times"></i>
			</button>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-sm-6">
			<?php echo $fields_persona['p_apellido']['label']; ?>
			<?php echo $fields_persona['p_apellido']['form']; ?>
		</div>
		<div class="form-group col-sm-6">
			<?php echo $fields_persona['p_nombre']['label']; ?>
			<?php echo $fields_persona['p_nombre']['form']; ?>
		</div>
		<div class="form-group col-sm-6">
			<?php echo $fields_persona['p_cuil']['label']; ?>
			<?php echo $fields_persona['p_cuil']['form']; ?>
		</div>
		<div class="form-group col-sm-6">
			<?php echo $fields_persona['p_email']['label']; ?>
			<?php echo $fields_persona['p_email']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<input type="hidden" name="persona_id" value="" id="persona_id"/>
	<input type="hidden" name="p_documento_tipo_id" value="" id="documento_tipo_id"/>
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Agregar', 'id' => 'btn-submit'), 'Agregar'); ?>
	<?php echo ($txt_btn === 'Eliminar') ? form_hidden('id', $escuela_autoridad->id) : ''; ?>
	<?php echo ($txt_btn === 'Agregar') ? form_hidden('escuela_id', $escuela->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form-autoridad'));
		$('#p_cuil').inputmask("99-99999999-9");  //static mask
		
		$('#btn-submit').attr('disabled', true);
		$('#p_cuil,#p_apellido,#p_nombre,#p_email').attr('readonly', true);

		$('#btn-clear').click(function() {
			$('#btn-clear').attr('disabled', true);
			$('#btn-search').attr('disabled', false);
			$('#p_cuil,#p_apellido,#p_nombre,#p_email').attr('readonly', true);
			$('#p_documento').attr('readonly', false);
			$('#p_documento_tipo')[0].selectize.enable();
			$('#p_cuil,#p_apellido,#p_nombre,#p_email').val('');
			$('#p_documento').select();
			$('#btn-submit').attr('disabled', true);
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
						$('#p_cuil,#p_apellido,#p_nombre,#p_email').attr('readonly', true);
						$('#btn-clear').attr('disabled', false);
						$('#btn-search').attr('disabled', true);
			
						if (result !== '') {
							$('#persona_id').val(result.id);
							$('#documento_tipo_id').val(result.documento_tipo_id);
							$('#p_apellido').val(result.apellido);
							$('#p_nombre').val(result.nombre);
							$('#p_email').val(result.email);
							$('#p_cuil').val(result.cuil);
						} else {
							$('#p_cuil,#p_apellido,#p_nombre,#p_email').prop('readonly', false);
							$('#p_documento').attr('readonly', true);
							$('#documento_tipo_id').val($('#p_documento_tipo')[0].selectize.getValue());
							$('#p_documento_tipo')[0].selectize.disable();
						}
						$('#btn-submit').attr('disabled', false);
					}
				});
			}
		});
	});
</script>
