<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_certificado_regular')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<h4><strong><u>Escuela</u></strong>: <?php echo "$escuela->numero - $escuela->nombre"; ?></h4>
			<h4><strong><u>Curso y división</u></strong>: <?php echo "$division->curso  $division->division"; ?></h4>
			<h4><strong><u>Alumno</u></strong>: <?php echo "$alumno->apellido, $alumno->nombre"; ?></h4>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['presentado']['label']; ?>
			<?php echo $fields['presentado']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php
	if ($txt_btn === 'Generar certificado') {
		echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Generar certificado', 'id' => 'boton_guardar', 'formtarget' => '_blank'), 'Generar certificado');
	}
	?>
	<?php echo ($txt_btn === 'Generar certificado' || $txt_btn === 'Eliminar') ? form_hidden('id', $alumno_division->id) : ''; ?>
</div>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_certificado_regular'))
		$('#form_certificado_regular').submit(function() {
			$(this).data('submitted', false);
			$(document).data('submitted', false);
		});
	});
</script>
