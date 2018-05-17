<style>
	.datepicker,.datepicker>datepicker-days>.table-condensed{
		margin:0 auto;
	}
</style>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agregar_alumno_abono_nuevo')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title . $alumno->apellido . ", " . $alumno->nombre . " (" . $alumno->documento_tipo . " " . $alumno->documento . ")"; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['numero_abono']['label']; ?>
			<?php echo $fields['numero_abono']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['abono_tipo']['label']; ?>
			<?php if ($monto_escuela_mes->abono_escuela_estado_id == 2): ?>
				<input type="text" name="abono_tipo_text" value="Contratado" readonly="1" id="abono_tipo_text" class="form-control">
				<input type="hidden" name="abono_tipo" value="<?php echo 4; ?>" id="abono_tipo"/>
			<?php else: ?>
				<?php echo $fields['abono_tipo']['form']; ?>
			<?php endif; ?>
		</div>
		<?php if ($monto_total_escuela > 0 || $monto_escuela_mes->abono_escuela_estado_id <> 2): ?>	
			<div class="form-group col-md-6">
				<?php echo $fields['monto']['label']; ?>
				<?php echo $fields['monto']['form']; ?>
			</div>
		<?php else: ?>	
			<input type="hidden" name="monto" value="<?php echo 0; ?>" id="monto"/>
		<?php endif; ?>
		<div class="form-group col-md-6">
			<?php echo $fields['motivo_alta']['label']; ?>
			<?php echo $fields['motivo_alta']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<label for="alumno">Estado Alumno</label>
			<?php if ($monto_total_escuela <> 0 || $monto_escuela_mes->abono_escuela_estado_id == 2): ?>	
				<input type="text" name="alumno" value="Asignado" readonly="1" id="alumno" class="form-control">
				<input type="hidden" name="abono_alumno_estado_id" value="<?php echo 1; ?>" id="abono_alumno_estado_id"/>
			<?php elseif ($monto_total_escuela == 0 && $monto_escuela_mes->abono_escuela_estado_id == 1): ?>	
				<input type="text" name="alumno" value="En espera" readonly="1" id="alumno" class="form-control">
				<input type="hidden" name="abono_alumno_estado_id" value="<?php echo 2; ?>" id="abono_alumno_estado_id"/>
			<?php endif; ?>
		</div>
		<?php if ($url_redireccion): ?>
			<div class="form-group col-md-6">
				<?php echo $fields['ames']['label']; ?>
				<?php echo $fields['ames']['form']; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<div class="modal-footer">
	<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Guardar', 'id' => 'boton_guardar'), 'Guardar'); ?>
</div>
<input type="hidden" name="alumno" value="<?php echo $alumno->id; ?>" id="alumno"/>
<input type="hidden" name="escuela" value="<?php echo $escuela->id; ?>" id="escuela"/>
<input type="hidden" name="monto_total_escuela" value="<?php echo $monto_total_escuela; ?>" id="monto_total_escuela"/>
<input type="hidden" name="cupos_total_escuela" value="<?php echo $cupos_total_escuela; ?>" id="cupos_total_escuela"/>
<input type="hidden" name="cantidad_alumnos_espera" value="<?php echo $cantidad_alumnos_espera; ?>" id="cantidad_alumnos_espera"/>
<?php if (!$url_redireccion): ?>
	<input type="hidden" name="ames" value="<?php echo $ames; ?>" id="ames"/>
	<input type="hidden" name="division_id" value="<?php echo $division_id; ?>" id="ames"/>
<?php endif; ?>
<?php echo form_hidden('url_redireccion', $url_redireccion); ?>
<?php echo form_close(); ?>
<script type="text/javascript">
	agregar_eventos($('#form_agregar_alumno_abono_nuevo'));
	$(document).ready(function() {
		$('#monto').inputmask('decimal', {radixPoint: ',', digits: 2, autoUnmask: true, placeholder: '', removeMaskOnSubmit: true, onUnMask: function(value) {
				return value.replace('.', '').replace(',', '.');
			}});
		$("#ames").datepicker({
			format: "yyyymm",
			startView: "months",
			minViewMode: "months",
			language: 'es',
			todayHighlight: false
		});
	});
</script>
