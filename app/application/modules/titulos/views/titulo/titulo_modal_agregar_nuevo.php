<script type="text/javascript">
	$(document).ready(function() {
		$('#titulo_establecimiento').on('change', function(e) {
			var value = $('#titulo_establecimiento').inputmask('unmaskedvalue');
			if (value === '1') {
				$('#establecimiento_val').removeClass('hidden');
			} else {
				$('#establecimiento_val').addClass('hidden');
			}
		});
		$('#titulo_tipo').on('change', function(e) {
			var value = $('#titulo_tipo').inputmask('unmaskedvalue');
			if (value === '1') {
				$('#tipo_val').removeClass('hidden');
			} else {
				$('#tipo_val').addClass('hidden');
			}
		});
		$('#pais_origen').on('change', function(e) {
			var value = $('#pais_origen').inputmask('unmaskedvalue');
			if (value === '1') {
				$('#provincia_val').removeClass('hidden');
			} else {
				$('#provincia_val').addClass('hidden');
			}
		});
	});
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agregar_titulo_nuevo')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['nombre']['label']; ?>
			<?php echo $fields['nombre']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['pais_origen']['label']; ?>
			<?php echo $fields['pais_origen']['form']; ?>
		</div>	
		<div class="form-group col-md-6" id="provincia_val">
			<?php echo $fields['provincia']['label']; ?>
			<?php echo $fields['provincia']['form']; ?>
		</div>	
		<div class="form-group col-md-6">
			<?php echo $fields['titulo_establecimiento']['label']; ?>
			<?php echo $fields['titulo_establecimiento']['form']; ?>
		</div>
		<div class="form-group col-md-6 hidden" id="establecimiento_val">
			<?php echo $fields['titulo_establecimiento_val']['label']; ?>
			<?php echo $fields['titulo_establecimiento_val']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['titulo_tipo']['label']; ?>
			<?php echo $fields['titulo_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-6 hidden" id="tipo_val">
			<?php echo $fields['titulo_tipo_val']['label']; ?>
			<?php echo $fields['titulo_tipo_val']['form']; ?>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields_tp['numero']['label']; ?>
			<?php echo $fields_tp['numero']['form']; ?>			
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields_tp['fecha_inscripcion']['label']; ?>
			<div class="input-group date" id="datepicker-i">
				<?php echo $fields_tp['fecha_inscripcion']['form']; ?>
			</div>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields_tp['fecha_egreso']['label']; ?>
			<div class="input-group date" id="datepicker-e">
				<?php echo $fields_tp['fecha_egreso']['form']; ?>
			</div>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields_tp['serie']['label']; ?>
			<?php echo $fields_tp['serie']['form']; ?>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields_tp['numero_titulo']['label']; ?>
			<?php echo $fields_tp['numero_titulo']['form']; ?>			
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields_tp['observaciones']['label']; ?>
			<?php echo $fields_tp['observaciones']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Guardar', 'id' => 'boton_guardar'), 'Guardar'); ?>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	agregar_eventos($('#form_agregar_titulo_nuevo'));
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
