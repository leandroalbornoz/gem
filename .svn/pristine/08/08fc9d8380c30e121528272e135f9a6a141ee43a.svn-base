<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_reporte_dinamico')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-sm-12">
			<h4>Por favor seleccione el rango de fechas correspondiente al periodo que desea reportar.</h4>
		</div>
		<div class="form-group col-sm-6">
			<label style="width:100%">Fecha desde (Vigencia del Referente)</label>
			<div class="input-group date" id="datepicker">
				<input type="text" class="form-control dateFormat" name="fecha_desde" required id="fecha_desde" value="<?php echo date('d/m/Y'); ?>" required/>
				<div class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</div>
			</div>			
		</div>
		<div class="form-group col-sm-6">
			<label style="width:100%">Fecha hasta (Vigencia del Referente)</label>
			<div class="input-group date" id="datepicker2">
				<input type="text" class="form-control dateFormat" name="fecha_hasta" required id="fecha_hasta" value="<?php echo date('d/m/Y'); ?>" required/>
				<div class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</div>
			</div>			
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<input type="hidden" name="escuela_id" value="<?php echo $escuela->id; ?>" id="escuela_id"/>
	<input type="hidden" name="tribunal_cuenta_id" value="<?php echo $tribunal_cuenta->id; ?>" id="tribunal_cuenta_id"/>&nbsp;
	<?php echo ($txt_btn === 'Generar') ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Generar reporte', 'formtarget' => '_blank'), 'Generar reporte') : ""; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $escuela->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_reporte_dinamico'))
		$('#form_reporte_dinamico').submit(function() {
			$(this).data('submitted', false);
			$(document).data('submitted', false);
		});
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#datepicker').datepicker({
			format: 'dd/mm/yyyy',
			endDate: '<?php echo date('d/m/Y'); ?>',
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true
		});
		$('#datepicker2').datepicker({
			format: 'dd/mm/yyyy',
			endDate: '<?php echo date('d/m/Y'); ?>',
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true
		});
	});
</script>