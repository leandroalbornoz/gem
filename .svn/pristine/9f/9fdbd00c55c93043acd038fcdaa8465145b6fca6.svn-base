<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'cursada_id' => 'form_cursada')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['persona']['label']; ?>
			<?php echo $fields['persona']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['espacio_curricular']['label']; ?>
			<?php echo $fields['espacio_curricular']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['carga_horaria']['label']; ?>
			<?php echo $fields['carga_horaria']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
	<?php echo form_hidden('cargo_cursada_id', $cargo_cursada->id); ?>
	<?php echo form_hidden('url_redireccion', $url_redireccion); ?>
</div>
<?php echo form_close(); ?>
