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
			<?php $dias_semana = array('Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'); ?>
			<?php $fecha_ini_m = new DateTime($mes . '01'); ?>
			<?php $fecha_ini_p = new DateTime($division_inasistencia->fecha_inicio); ?>
			<?php $fecha_fin_m = new DateTime($mes . '01 +1 month'); ?>
			<?php $fecha_fin_p = new DateTime($division_inasistencia->fecha_fin . ' +1 day'); ?>
			<?php $fecha_ini = max(array($fecha_ini_m, $fecha_ini_p)); ?>
			<?php $fecha_fin = min(array($fecha_fin_m, $fecha_fin_p)); ?>
			<?php $dia = DateInterval::createFromDateString('1 day'); ?>
			<?php $fechas = new DatePeriod($fecha_ini, $dia, $fecha_fin); ?>
			<table class="table table-hover table-bordered table-condensed text-sm" role="grid">
				<thead>
					<tr style="background-color: #f1f1f1">
						<th style="text-align: center;" colspan="8">
							Resumen Mensual de Inasistencias
						</th>
					</tr>
					<tr>
						<th style="text-align: center;">Justificadas</th>
						<th style="text-align: center;">Injustificadas</th>
						<th style="text-align: center;">Días de cursado previos al alta</th>
						<th style="text-align: center;">Días de cursado posteriores a la baja</th>							
						<th style="text-align: center;">Fecha de Ingreso</th>
						<th style="text-align: center;">Fecha de Egreso</th>
					</tr>
				</thead>
				<tbody>
						<tr>
							<td align="center">
								<input type="number" name="justificadas[<?php echo $alumnos->id; ?>]" min="0" max="<?php echo $division_inasistencia->dias; ?>" step="0.5" value="<?php echo isset($alumnos->Si) ? $alumnos->Si->falta : ''; ?>" class="form-control carga-dias">
							</td>
							<td align="center">
								<input type="number" name="injustificadas[<?php echo $alumnos->id; ?>]" min="0" max="<?php echo $division_inasistencia->dias; ?>" step="0.5" value="<?php echo isset($alumnos->No) ? $alumnos->No->falta : ''; ?>" class="form-control carga-dias">
							</td>
							<?php if (!empty($alumnos->fecha_desde) && ((new DateTime($alumnos->fecha_desde))->format('Y-m-d')) > $fecha_ini->format('Y-m-d')): ?>
								<td align="center">
									<input type="number" name="dias_previos[<?php echo $alumnos->id; ?>]" min="0" max="<?php echo $alumnos->dias; ?>" value="<?php echo isset($alumnos->Prev) ? round($alumnos->Prev->falta) : ''; ?>" class="form-control carga-dias">
								</td>
							<?php else: ?>
								<td>
									<input type="hidden" name="dias_previos[<?php echo $alumnos->id; ?>]" value="0">
								</td>
							<?php endif; ?>
							<?php if (!empty($alumnos->fecha_hasta) && ((new DateTime($alumnos->fecha_hasta . ' +1 day')) < (new DateTime($fecha_fin->format('Y-m-d') . ' -1 day')))): ?>
								<td align="center">
									<input type="number" name="dias_posteriores[<?php echo $alumnos->id; ?>]" min="0" max="<?php echo $alumnos->dias; ?>" value="<?php echo isset($alumnos->Post) ? round($alumnos->Post->falta) : ''; ?>" class="form-control carga-dias">
								</td>
							<?php else: ?>
								<td>
									<input type="hidden" name="dias_posteriores[<?php echo $alumnos->id; ?>]" value="0">
								</td>
							<?php endif; ?>
							<td align="center">
								<?= empty($alumnos->fecha_desde) ? '' : (new DateTime($alumnos->fecha_desde))->format('d/m/Y'); ?>
							</td>
							<td align="center">
								<?= empty($alumnos->fecha_hasta) ? '' : (new DateTime($alumnos->fecha_hasta))->format('d/m/Y'); ?>
							</td>
						</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal-footer">
	<input type="hidden" name="division_inasitencia_dia_id" value="<?php //echo $dia_id;   ?>"/>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right supera_dias', 'title' => 'Guardar'), 'Guardar'); ?>
</div>
<?php echo form_close(); ?>
