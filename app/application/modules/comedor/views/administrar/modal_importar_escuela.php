<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_importar_escuela')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<?php if ($contar != 0): ?>
			<div class="col-md-12">
				<h4>Introduzca la fecha de cierre para el mes nuevo.</h4>
			</div>	
			<?php if ($crear == TRUE): ?>
				<div class="col-md-6">
					<?php echo $fields['fecha_cierre']['label']; ?>
					<div class="input-group date" id="datepicker2">
						<?php echo $fields['fecha_cierre']['form']; ?>
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</div>
					</div>	
				</div>	
			<?php endif; ?>
		<?php else: ?>
			<div class="col-md-12">
				<h4><?php echo (empty($cartel)) ? "Todas las escuelas ya importadas." : "$cartel"; ?></h4>
			</div>
		<?php endif; ?>
	</div>
</div>
<div class="modal-footer">
	<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<input type="hidden" name="mes" value="<?php echo $mes; ?>" id="mes"/>
	<?php if ($contar != 0): ?>
		<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Importar'), 'Importar'); ?>&nbsp;
	<?php endif; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('comedor_presupuesto_id', $comedor_presupuesto->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	agregar_eventos($('#form_importar_escuela'));
	$(document).ready(function() {
		$('#datepicker2').datepicker({
			format: 'dd/mm/yyyy',
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true
		});
	});
</script>