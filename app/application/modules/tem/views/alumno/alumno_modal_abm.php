<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_editar_alumno')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-2">
			<?php echo $fields['documento_tipo']['label']; ?>
			<?php echo $fields['documento_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields['documento']['label']; ?>
			<?php echo $fields['documento']['form']; ?>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields['sexo']['label']; ?>
			<?php echo $fields['sexo']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields['fecha_nacimiento']['label']; ?>
			<?php echo $fields['fecha_nacimiento']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['apellido']['label']; ?>
			<?php echo $fields['apellido']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['nombre']['label']; ?>
			<?php echo $fields['nombre']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['escuela']['label']; ?>
			<?php echo $fields['escuela']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['carrera']['label']; ?>
			<?php echo $fields['carrera']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['materia']['label']; ?>
			<?php echo $fields['materia']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['ciclo_lectivo']['label']; ?>
			<?php echo $fields['ciclo_lectivo']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields['fecha_inicio']['label']; ?>
			<?php echo $fields['fecha_inicio']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields['fecha_fin']['label']; ?>
			<?php echo $fields['fecha_fin']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields['estado']['label']; ?>
			<?php echo $fields['estado']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<span class="badge bg-red" id="alerta"></span>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo zetta_form_submit($txt_btn); ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $alumno->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#fecha_nacimiento').inputmask('d/m/y');
		$('#ciclo_lectivo').datepicker({
			format: "yyyy",
			startView: "years",
			minViewMode: "years",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true,
			endDate: '+0y'
		});
		$('#fecha_inicio').datepicker({
			format: "dd/mm/yyyy",
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			startDate: '<?php echo (new DateTime($servicio->fecha_alta))->format('d/m/Y'); ?>',
			endDate: '<?php echo empty($servicio->fecha_baja) ? '' : (new DateTime($servicio->fecha_baja))->format('d/m/Y'); ?>',
			todayHighlight: true
		}).on('changeDate', function(selected) {
			var startDate = new Date(selected.date.valueOf());
			$('#fecha_fin').datepicker('setStartDate', startDate);
		}).on('clearDate', function(selected) {
			$('#fecha_fin').datepicker('setStartDate', null);
		});
		$('#fecha_fin').datepicker({
			format: "dd/mm/yyyy",
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			startDate: '<?php echo (new DateTime("$servicio->fecha_alta +1 day"))->format('d/m/Y'); ?>',
			todayHighlight: true
		}).on('changeDate', function(selected) {
			var endDate = new Date(selected.date.valueOf());
			$('#fecha_inicio').datepicker('setEndDate', endDate);
		}).on('clearDate', function(selected) {
			$('#fecha_inicio').datepicker('setEndDate', null);
		});
	});
</script>