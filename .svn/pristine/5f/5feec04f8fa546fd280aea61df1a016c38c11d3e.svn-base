<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_editar_alumno_division')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
			<div class="form-group col-md-8">
				<?php echo $fields['condicion']['label']; ?>
				<?php echo $fields['condicion']['form']; ?>
			</div>
	</div>
</div>
<div class="modal-footer">
	<span class="badge bg-red" id="alerta"></span>
	<?php if ($txt_btn == 'Eliminar'): ?>
		<input type="hidden" name="id" value="<?= $alumno_division->id ?>">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		<?php echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => 'Eliminar', 'id' => 'btn_submit_novedad'), 'Eliminar'); ?>
	<?php else: ?>
		<input type="hidden" name="id" value="<?= $alumno_division->id ?>">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		<?php echo form_submit(array('class' => 'btn btn-warning pull-right', 'title' => $txt_btn, 'id' => 'btn_submit_editar'), $txt_btn); ?>
	<?php endif; ?>
</div>
<?php echo form_close(); ?>