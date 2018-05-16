<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_movimientos_alumnos')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<h4><u><?php echo $alumno_movimiento->movimiento; ?>.</u></h4>
		</div>
		<div class="form-group col-md-12">
			<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
				<thead>
					<tr style="background-color: #f4f4f4" >
						<th style="text-align: center;" colspan="3">
							Alumnos
						</th>
					</tr>
					<tr>
						<th>Egreso</th>
						<th>Nombre y apellido</th>
						<th>Ingreso</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($tabla_alumnos)): ?>
						<?php foreach ($tabla_alumnos as $alumno): ?>
							<tr>
								<td style="text-align: center;">
									<?php echo (empty($alumno['Egreso'])) ? "" : "<i class='fa fa-times' style='color:red;'></i> Mov. Anterior"; ?>
								</td>
								<td style="text-align: center;">
									<?php echo $alumno['nombre']; ?>
								</td>
								<td style="text-align: center;">
									<?php echo (empty($alumno['Ingreso'])) ? "" : "<i class='fa fa-times' style='color:red;'></i> Mov. Anterior";?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td style="text-align: center;" colspan="5">
								-- No tiene --
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal-footer">
	<input type="hidden" name="alumnos_movimientos_id" value="<?php echo $alumno_movimiento->id; ?>" id="alumnos_movimientos_id"/>
	<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => 'Revertir'), 'Revertir'); ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_movimientos_alumnos'))
	});
</script>