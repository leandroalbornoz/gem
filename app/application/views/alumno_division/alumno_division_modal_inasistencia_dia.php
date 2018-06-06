<style>
	.inasistencia-0.active{
		color: green;
	}
	.inasistencia-0-5.active{
		color: yellowgreen;
	}
	.inasistencia-1.active{
		color: red;
	}
	.inasistencia-1-5.active{
		color: blue;
	}
	.active{
		font-weight: bold;
	}
</style>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_editar_assitencia_dia')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><strong><u><?php echo $title; ?></u>:</strong> <?php echo "$alumno->apellido, $alumno->nombre"; ?></h4>
	<h4 class="modal-title" id="myModalLabel"><strong><u>Escuela</u>:</strong> <?php echo "$escuela->nombre"; ?></h4>
	<h4 class="modal-title" id="myModalLabel"><strong><u>Curso y division</u>:</strong> <?php echo "$alumno_division->curso $alumno_division->division"; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_division_inasistencia_editar_dia', 'name' => 'form_division_inasistencia_editar_dia')); ?>

			<?php if ($division_inasistencia_dia->contraturno === 'No'): ?>
				<?php $turnos = array('No'); ?>
			<?php elseif ($division_inasistencia_dia->contraturno === 'Parcial'): ?>
				<?php $turnos = array('No', 'Si'); ?>
			<?php else: ?>
				<?php $turnos = array('No', 'Si'); ?>
			<?php endif; ?>
			<?php foreach ($turnos as $turno): ?>
				<table id="inasistencia_table" class="table table-hover table-bordered table-condensed text-sm" role="grid" >
					<thead>
						<tr>
							<th colspan="4" class="text-center bg-gray"><?php echo $turno === 'No' ? 'Turno Principal' : 'Contraturno'; ?></th>
						</tr>
						<tr>
							<th style="text-align: center;">Asistencia</th>
							<th style="text-align: center;">Justificada</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($alumnos[$turno] as $alumno): ?>
							<tr>
								<td style="text-align: center;">
									<div class="btn-group-xs inasistencia" data-toggle="buttons">
										<?php if ($division_inasistencia_dia->contraturno === 'Parcial' && $turno === 'Si'): ?>
											<label class="btn btn-default inasistencia-0 <?php echo ($alumno->inasistencia_tipo === 'No corresponde' ) ? 'active' : ''; ?>">
												<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="9" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === 'No corresponde') ? 'checked' : ''; ?>/>(No corresponde)
											</label>
										<?php endif; ?>
										<?php if ($alumno->fecha_hasta && ((new DateTime($alumno->fecha_hasta))->format('Y-m-d')) <= $division_inasistencia_dia->fecha): ?>
											<input type="hidden" name="alumno_inasistencia_ids[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="<?php echo $alumno->alumno_inasistencia_id; ?>"/>
											<label class="btn btn-default inasistencia-0 <?php echo ($alumno->inasistencia_tipo === NULL || $alumno->inasistencia_tipo === 'Posterior egreso' ) ? 'active' : ''; ?>">
												<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="6" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === NULL || $alumno->inasistencia_tipo === 'Posterior egreso') ? 'checked' : ''; ?>/> - Salido -
											</label>
										<?php elseif ($alumno->fecha_desde && ((new DateTime($alumno->fecha_desde))->format('Y-m-d')) > $division_inasistencia_dia->fecha): ?>
											<input type="hidden" name="alumno_inasistencia_ids[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="<?php echo $alumno->alumno_inasistencia_id; ?>"/>
											<label class="btn btn-default inasistencia-0 <?php echo ($alumno->inasistencia_tipo === NULL || $alumno->inasistencia_tipo === 'Previo ingreso' ) ? 'active' : ''; ?>">
												<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="5" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === NULL || $alumno->inasistencia_tipo === 'Previo ingreso') ? 'checked' : ''; ?>/> - No inscripto -
											</label>
										<?php else: ?>
											<input type="hidden" name="alumno_inasistencia_ids[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="<?php echo $alumno->alumno_inasistencia_id; ?>"/>
											<label class="btn btn-default inasistencia-0 <?php echo ($alumno->inasistencia_tipo === NULL ) ? 'active' : ''; ?>">
												<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="0" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === NULL) ? 'checked' : ''; ?>/> Presente
											</label>
											<label class="btn btn-default inasistencia-0-5 text-success <?php echo ($alumno->inasistencia_tipo === 'Tardanza') ? 'active' : ''; ?>">
												<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="3" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === 'Tardanza') ? 'checked' : ''; ?>/> Tardanza
											</label>
											<label class="btn btn-default inasistencia-1 text-success <?php echo ($alumno->inasistencia_tipo === 'Inasistencia') ? 'active' : ''; ?>">
												<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="2" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === 'Inasistencia') ? 'checked' : ''; ?>/> Inasistencia
											</label>
											<label class="btn btn-default inasistencia-1 text-success <?php echo ($alumno->inasistencia_tipo === 'Presente NC') ? 'active' : ''; ?>">
												<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="8" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === 'Presente NC') ? 'checked' : ''; ?>/> Inasistencia por tardanza
											</label>
											<label class="btn btn-default inasistencia-0-5 text-success <?php echo ($alumno->inasistencia_tipo === 'Retira antes') ? 'active' : ''; ?>">
												<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="4" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === 'Retira antes' ) ? 'checked' : ''; ?>/> Retira antes
											</label>
											<label class="btn btn-default inasistencia-1-5 text-success <?php echo ($alumno->inasistencia_tipo === 'Fuerza mayor') ? 'active' : ''; ?>" id="inasistencia_fm_<?php echo "{$turno}_$alumno->id"; ?>">
												<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="7" id="inasistencia_fm_<?php echo "{$turno}_$alumno->id"; ?>" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === 'Fuerza mayor') ? 'checked' : ''; ?>/> Fuerza mayor
											</label>
										<?php endif; ?>
									</div>
								</td>
								<td style="text-align: center;">
									<div class="btn-group-xs justificada <?php echo ($alumno->inasistencia_tipo === NULL || $alumno->inasistencia_tipo_id === '7' || $alumno->inasistencia_tipo_id === '8' || $alumno->inasistencia_tipo_id === '9') ? 'hidden' : ''; ?>" data-toggle="buttons">
										<?php if ($alumno->fecha_hasta && ((new DateTime($alumno->fecha_hasta))->format('Y-m-d')) <= $division_inasistencia_dia->fecha): ?>
											<label class="btn btn-default justificada-0 <?php echo ($alumno->justificada === 'NC' || $alumno->justificada === null) ? 'active' : ''; ?>">
												<input type="radio" name="justificada[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" autocomplete="off" <?php echo ($alumno->justificada === 'NC' || $alumno->justificada === null) ? 'checked' : ''; ?> value="NC"/>-
											</label>
										<?php elseif ($alumno->fecha_desde && ((new DateTime($alumno->fecha_desde))->format('Y-m-d')) > $division_inasistencia_dia->fecha): ?>
											<label class="btn btn-default justificada-0 <?php echo ($alumno->justificada === 'NC' || $alumno->justificada === null) ? 'active' : ''; ?>">
												<input type="radio" name="justificada[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" autocomplete="off" <?php echo ($alumno->justificada === 'NC' || $alumno->justificada === null) ? 'checked' : ''; ?> value="NC"/>-
											</label>
										<?php else: ?>
											<label class="btn btn-default justificada-0 <?php echo ($alumno->justificada === 'No' || $alumno->justificada === null) ? 'active' : ''; ?>">
												<input type="radio" name="justificada[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" autocomplete="off" <?php echo ($alumno->justificada === 'No' || $alumno->justificada === null) ? 'checked' : ''; ?> value="No"/> No
											</label>
											<label class="btn btn-default justificada-1 text-success <?php echo ($alumno->justificada === 'Si') ? 'active' : ''; ?>">
												<input type="radio" name="justificada[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" autocomplete="off" <?php echo ($alumno->justificada === 'Si') ? 'checked' : ''; ?> value="Si"/> Si
											</label>
										<?php endif; ?>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<input type="hidden" name="division_inasitencia_dia_id" value="<?php echo $division_inasistencia_dia->id; ?>"/>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Guardar'), 'Guardar'); ?>
</div>
<?php echo form_close(); ?>

<script>
	$(document).ready(function() {
		$('.inasistencia input[type="radio"').change(function() {
			if ($(this).val() === '0' || $(this).val() === '7' || $(this).val() === '8' || $(this).val() === '9') {
				$(this).closest('tr').find('.justificada').addClass('hidden');
			} else {
				$(this).closest('tr').find('.justificada').removeClass('hidden');
			}
		});
	});
</script>
