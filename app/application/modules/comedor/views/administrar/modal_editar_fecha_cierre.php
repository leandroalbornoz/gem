<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_editar_fecha_racion')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['fecha_cierre']['label']; ?>
			<?php echo ($fields['fecha_cierre']['form']); ?>
		</div>	

		<div class="form-group col-md-6">
			<label for="fecha_cierre">Nueva fecha</label>
			<div class="input-group date" id="datepicker2">
				<?php echo $fields2['fecha_cierre']['form']; ?>
				<div class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</div>
			</div>	
		</div>
	</div>
</div>
<div class="modal-footer">
	<?php echo form_hidden('mes', $ames); ?>
	<?php echo form_hidden('comedor_plazo', $comedor_plazo->id); ?>
	<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"> <?php echo isset($txt_btn) ? 'Cancelar' : 'Editar'; ?>
	</button>
	<?php echo form_submit(array('class' => 'btn btn-warning pull-right', 'title' => 'Cancelar'), 'Editar'); ?>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	agregar_eventos($('#form_importar_escuela'));
	$(document).ready(function() {
		$('#datepicker2').datepicker({
			format: 'dd-mm-yyyy',
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true
		});
	});
</script>