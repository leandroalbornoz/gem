<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_alumno_apoyo_especial')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['alumno']['label']; ?>
			<?php echo $fields['alumno']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['escuela']['label']; ?>
			<?php echo $fields['escuela']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['cud']['label']; ?>
			<?php echo $fields['cud']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['trayectoria_compartida']['label']; ?>
			<?php echo $fields['trayectoria_compartida']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['cohorte_inicial']['label']; ?>
			<div class="input-group date" id="datepicker">
				<?php echo $fields['cohorte_inicial']['form']; ?>
				<div class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $alumno_apoyo_especial->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	agregar_eventos($('#form_alumno_apoyo_especial'));
	$(document).ready(function() {
		$("#datepicker").datepicker({
			format: "yyyy",
			startView: "years",
			minViewMode: "years",
			language: 'es',
			todayHighlight: false,
			endDate: '+0y'
		});
	});
</script>