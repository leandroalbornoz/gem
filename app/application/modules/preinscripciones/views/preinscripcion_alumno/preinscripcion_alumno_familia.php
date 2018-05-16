<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_familiar')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields_familia['parentesco_tipo']['label']; ?>
			<?php echo $fields_familia['parentesco_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_familia['convive']['label']; ?>
			<?php echo $fields_familia['convive']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['documento_tipo']['label']; ?>
			<?php echo $fields['documento_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['documento']['label']; ?>
			<span class="label label-danger" id="documento_existente_modal"></span>
			<?php echo $fields['documento']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['apellido']['label']; ?>
			<?php echo $fields['apellido']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['nombre']['label']; ?>
			<?php echo $fields['nombre']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['nivel_estudio']['label']; ?>
			<?php echo $fields['nivel_estudio']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['ocupacion']['label']; ?>
			<?php echo $fields['ocupacion']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['telefono_movil']['label']; ?>
			<?php echo $fields['telefono_movil']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['prestadora']['label']; ?>
			<?php echo $fields['prestadora']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['email']['label']; ?>
			<?php echo $fields['email']['form']; ?>
		</div>
	</div>
	<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
			<thead>
				<tr style="background-color: #f4f4f4" >
					<th style="text-align:center;" colspan="11">
						Familiares
					</th>
				</tr>
				<tr>
					<th>Parentesco</th>
					<th>Nombre</th>
					<th>Documento</th>
					<th>Escuela</th>
					<th>Curso/División</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($hijos)): ?>
					<?php foreach ($hijos as $hijo): ?>
						<tr>
							<td><?= $hijo->parentesco.' de:'; ?></td>
							<td><?= $hijo->alumno; ?></td>
							<td><?= $hijo->documento; ?></td>
							<td><?= $hijo->escuela; ?></td>
							<td><?= $hijo->division; ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td style="text-align: center;" colspan="11">
							-- No tiene --
						</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php
	if ($txt_btn === 'Editar') {
		echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Guardar', 'id' => 'boton_guardar'), 'Guardar');
	} else {
		echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => 'Eliminar'), 'Eliminar');
	}
	?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $familia->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_familiar'))
		$('#form_familiar #documento,#form_familiar #documento_tipo').change(verificar_doc_repetido);
		function verificar_doc_repetido(e) {
			var documento_tipo = $('#form_familiar #documento_tipo')[0].selectize.getValue();
			var documento = $('#form_familiar #documento').val();
			if (documento_tipo === '8') {
				$.ajax({
					type: 'GET',
					url: 'ajax/get_indocumentado?',
					data: {documento_tipo: documento_tipo, documento: documento, id: '<?= empty($persona) ? '' : $persona->id; ?>'},
					dataType: 'json',
					success: function(result) {
						if (result !== '') {
							$('#form_familiar #documento_existente_modal').text(null);
							$('#form_familiar #boton_guardar').attr('disabled', false);
							$('#form_familiar #documento').closest('.form-group').removeClass("has-error");
							$('#form_familiar #documento').val(result);
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
							$('#form_familiar #documento_existente_modal').text('Doc. en Uso');
							$('#form_familiar #boton_guardar').attr('disabled', true);
							$('#form_familiar #documento').closest('.form-group').addClass("has-error");
						} else {
							$('#form_familiar #documento_existente_modal').text(null);
							$('#form_familiar #boton_guardar').attr('disabled', false);
							$('#form_familiar #documento').closest('.form-group').removeClass("has-error");
						}
					}
				});
			}
		}
	});
</script>