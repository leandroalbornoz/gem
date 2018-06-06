<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
	<div class="row">

		<h4 style="text-align:center"> <?php echo "$persona->apellido, $persona->nombre ($persona->documento_tipo $persona->documento - CUIL: $persona->cuil)" . (($this->rol->codigo === ROL_ADMIN || $this->rol->codigo === ROL_USI) ? '<a href="privada/datos_personal/editar' . $persona->id . '" title="Ir a administración de persona"><i class="fa fa-search"></i></a>' : ''); ?></h4>

	</div>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['prenatal_fechacertificada']['label']; ?>
			<?php echo $fields['prenatal_fechacertificada']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['prenatal_fechaposiblenac']['label']; ?>
			<?php echo $fields['prenatal_fechaposiblenac']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['prenatal_fechadefuncion']['label']; ?>
			<?php echo $fields['prenatal_fechadefuncion']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php
	if ($txt_btn === 'Agregar') {
		echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Agregar', 'id' => 'boton_guardar'), 'Agregar');
	} else {
		echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => 'Eliminar'), 'Eliminar');
	}
	?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $familia->id) : ''; ?>
	
</div>

<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_agregar_bonificaciones'))
	});
</script>