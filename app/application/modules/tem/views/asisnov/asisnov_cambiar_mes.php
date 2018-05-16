<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" />
<style>
	.datepicker,.datepicker>datepicker-days>.table-condensed{
		margin:0 auto;
	}
</style>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel">Asistencia y Novedades</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group" style="text-align:center;">
			<div id="asisnov_datepicker" data-date="<?php echo (new DateTime($mes . '01'))->format('d/m/Y'); ?>"></div>
			<input type="hidden" name="mes" id="asisnov_mes" value="<?php echo (new DateTime($mes . '01'))->format('d/m/Y'); ?>"/>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Seleccionar'), 'Seleccionar'); ?>
</div>
<?php echo form_close(); ?>
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#asisnov_datepicker").datepicker({
			format: "dd/mm/yyyy",
			startView: "months",
			minViewMode: "months",
			startDate: '01/03/2017',
			endDate: '01/03/2017',
			language: 'es',
			todayHighlight: false
		});
		$("#asisnov_datepicker").on("changeDate", function(event) {
			$("#asisnov_mes").val($("#asisnov_datepicker").datepicker('getFormattedDate'))
		});
	});
</script>