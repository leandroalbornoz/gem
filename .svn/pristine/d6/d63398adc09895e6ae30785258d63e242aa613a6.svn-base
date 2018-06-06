<script>
	$(document).ready(function() {
		agregar_eventos($('#form_antecedente'));
<?php if (!empty($persona_antecedente->aprobado) && $persona_antecedente->aprobado == 'Si'): ?>
			$("#aprobado").attr('checked', true);
<?php endif; ?>
	});
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_antecedente')); ?>
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
			<?php echo $fields['antecedente']['label']; ?>
			<?php echo $fields['antecedente']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['institucion']['label']; ?>
			<?php echo $fields['institucion']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['numero_resolucion']['label']; ?>
			<?php echo $fields['numero_resolucion']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['modalidad']['label']; ?>
			<?php echo $fields['modalidad']['form']; ?>
		</div>
		<div class="row">
			<div class="form-group col-md-6">
				<label>
					<input type="checkbox" id="aprobado" name="aprobado" value="1" style="margin-top: 33px">
					Con aprobación
				</label>
			</div>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields['fecha_emision']['label']; ?>
			<?php echo $fields['fecha_emision']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields['duracion']['label']; ?>
			<?php echo $fields['duracion']['form']; ?>
		</div>
		<div class="form-group col-md-4" style="padding-top: 25px">
			<?php echo $fields['tipo_duracion']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php $data_submit = array('class' => 'btn pull-right' . ($txt_btn === 'Eliminar' ? ' btn-danger' : ' btn-primary'), 'title' => $txt_btn); ?>
	<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $persona->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		$("#duracion").on('change', function() {
			$(this).val($(this).val().replace(/,/g, '.'));
		});
	});
</script>

