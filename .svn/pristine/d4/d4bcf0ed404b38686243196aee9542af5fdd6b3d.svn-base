<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">Eliminar alumno de la cursada</h4>
</div>
<?php $data_submit = array('class' => 'btn btn-danger pull-right', 'title' => $txt_btn); ?>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_alumno', 'name' => 'form_alumno')); ?>
<div class="modal-body">
	<h4>Se eliminará la inscripción del siguiente alumno:</h4>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['persona']['label']; ?>
			<?php echo $fields['persona']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['documento']['label']; ?>
			<?php echo $fields['documento']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<input type="hidden" name="alumno_cursada_id" value="<?php echo $alumno_cursada->id; ?>">
	<button  class="btn btn-default pull-left" type="button" id="btn-submit" data-dismiss="modal">Cancelar</button>
	<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
</div>
<?php echo form_close(); ?>