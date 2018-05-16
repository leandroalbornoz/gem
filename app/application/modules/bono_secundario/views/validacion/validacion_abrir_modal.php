<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_abrir')); ?>
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
	</div>
	<div class="form-group col-md-12">
		<label style="font-size: 1.2em; color: red;">
			Al efectuar la apertura de la validación solo se podrá editar lo que no fue aceptado en la instancia anterior.
		</label>
	</div>
</div>
<div class="modal-footer">
	<button  type="submit" class="btn btn-warning pull-right" title="Volver a validar" name="submitform" id="submitform">Volver a validar</button>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		var submitform = document.getElementById('submitform');
		$('#enableBtn').on('change', function() {
			if (this.checked) {
				submitform.disabled = false;
			} else {
				submitform.disabled = true;
			}
		});
	});
</script>
