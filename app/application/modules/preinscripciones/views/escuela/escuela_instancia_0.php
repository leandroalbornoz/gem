<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>

<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title"><?php echo $title; ?> - Ciclo Lectivo 2018</h4>
</div>
<div class="modal-body">
	<div class="row">
			<div class="form-group col-sm-12">
				<?php echo $fields['escuela']['label']; ?>
				<?php echo $fields['escuela']['form']; ?>
			</div>
		
			<div class="form-group col-sm-12">
				<?php echo $fields['vacantes']['label']; ?>
				<?php echo $fields['vacantes']['form']; ?>
			</div>
		<div class="col-sm-6">
			<div id="datepicker"></div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
<?php echo ($txt_btn === 'Editar') ? form_hidden('id', $preinscripcion->id) : ''; ?>
</div>
<?php echo form_close(); ?>