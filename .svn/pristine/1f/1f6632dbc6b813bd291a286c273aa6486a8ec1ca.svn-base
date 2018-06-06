<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agregar_cheque')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields_vc['numero']['label']; ?>
			<?php echo $fields_vc['numero']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_vc['importe']['label']; ?>
			<?php echo $fields_vc['importe']['form']; ?>
		</div>
		<div class="form-group col-sm-6">
			<?php echo $fields_vc['fecha']['label']; ?>
			<div class="input-group date" id="datepicker">
				<input type="text" class="form-control dateFormat" name="fecha" required id="fecha" value="<?php echo $fields_vc['fecha']['value']; ?>" required/>
				<div class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</div>
			</div>			
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo zetta_form_submit($txt_btn); ?>
	<input type="hidden" name="id" value="<?php echo $referente->id; ?>" id="id"/>
	<input type="hidden" name="referente_vigencia_id" value="<?php echo $referente_vigencia->id; ?>" id="referente_vigencia_id"/>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $referente->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_agregar_cheque'))
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
	});
</script>