<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['curso']['label']; ?>
			<?php echo $fields['curso']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<input type="hidden" name="id" value="<?= $alumno_division->id ?>">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo form_submit(array('class' => 'btn btn-warning pull-right', 'title' => $txt_btn), $txt_btn); ?>
</div>
<?php echo form_close(); ?>
