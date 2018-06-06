<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agregar_titulo_existente')); ?>
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
		<div class="form-group col-md-6">
			<?php echo $fields['provincia']['label']; ?>
			<?php echo $fields['provincia']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['titulo_establecimiento']['label']; ?>
			<?php echo $fields['titulo_establecimiento']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<b><?php echo "Carrera" ?></b>
			<?php echo $fields['titulo_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields_tp['numero']['label']; ?>
			<?php echo $fields_tp['numero']['form']; ?>			
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields_tp['fecha_inscripcion']['label']; ?>
			<div class="input-group date" id="datepicker-i">
				<?php echo $fields_tp['fecha_inscripcion']['form']; ?>
				<div class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</div>
			</div>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields_tp['fecha_egreso']['label']; ?>
			<div class="input-group date" id="datepicker-e">
				<?php echo $fields_tp['fecha_egreso']['form']; ?>
				<div class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</div>
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
	<input type="hidden" name="titulo_id" value="<?php echo $titulo->id; ?>" id="titulo_id"/>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
	agregar_eventos($('#form_agregar_titulo_existente'));
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
