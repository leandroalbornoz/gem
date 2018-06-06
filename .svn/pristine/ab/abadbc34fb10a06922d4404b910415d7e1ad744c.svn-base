<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_alumno_sexo')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<h4><strong><u>Escuela</u></strong>: <?php echo"$escuela->numero - $escuela->nombre"; ?></h4>
			<h4><strong><u>Curso y división</u></strong>: <?php echo"$division"; ?></h4>
			<h4><strong><u>Alumno</u></strong>: <?php echo"$alumno->apellido, $alumno->nombre"; ?></h4>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['sexo']['label']; ?>
			<?php echo $fields['sexo']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php
	if ($txt_btn === 'Editar') {
		echo form_submit(array('class' => 'btn btn-warning pull-right', 'title' => 'Editar', 'id' => 'boton_guardar'), 'Editar');
	}
	?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $alumno->id) : ''; ?>
</div>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_alumno_sexo'))
	});
</script>
