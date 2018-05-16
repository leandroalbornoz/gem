<script>
	$(document).ready(function() {
		$('select#materia').selectize();
	});
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-8">
			<?php echo $fields['carrera']['label']; ?>
			<?php echo $fields['carrera']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields['curso']['label']; ?>
			<?php echo $fields['curso']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['materia']['label']; ?>
			<?php echo $fields['materia']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['carga_horaria']['label']; ?>
			<?php echo $fields['carga_horaria']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['resolucion_alta']['label']; ?>
			<?php echo $fields['resolucion_alta']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['codigo_junta']['label']; ?>
			<?php echo $fields['codigo_junta']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['cuatrimestre']['label']; ?>
			<?php echo $fields['cuatrimestre']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<?php if (!empty($espacio_curricular)) { ?>
		<a style="float: left;" class="btn btn-default" href="espacio_curricular/cargos/<?php echo $espacio_curricular->id; ?>" target="_blank">
			<i class="fa fa-search" id="btn-ver"></i> Ver cargos
		</a>
	<?php } ?>
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo zetta_form_submit($txt_btn); ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $espacio_curricular->id) : ''; ?>
</div>
<?php echo form_close(); ?>