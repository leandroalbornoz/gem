<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_titulo_persona_modal')); ?>
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
		<div class="form-group col-md-12">
			<?php echo $fields['titulo']['label']; ?>
			<?php echo $fields['titulo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['fecha_inscripcion']['label']; ?>
			<div class="input-group date" id="datepicker-i">
				<?php echo $fields['fecha_inscripcion']['form']; ?>
				<div class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</div>
			</div>
		</div>

		<div class="form-group col-md-6">
			<?php echo $fields['fecha_egreso']['label']; ?>
			<div class="input-group date" id="datepicker-e">
				<?php echo $fields['fecha_egreso']['form']; ?>
				<div class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</div>
			</div>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields['serie']['label']; ?>
			<?php echo $fields['serie']['form']; ?>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields['numero']['label']; ?>
			<?php echo $fields['numero']['form']; ?>			
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['observaciones']['label']; ?>
			<?php echo $fields['observaciones']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
	agregar_eventos($('#form_titulo_persona_modal'));
	$(document).ready(function() {
		$('#fecha_inscripcion').datepicker({
			format: "dd/mm/yyyy",
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true
		});
		//.datepicker("setDate", new Date())
		$('#fecha_egreso').datepicker({
			format: "dd/mm/yyyy",
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true
		});
	});
</script>