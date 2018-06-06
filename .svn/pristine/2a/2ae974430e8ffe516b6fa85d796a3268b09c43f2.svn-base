<div class="row">
	<?php $dias_semana = array('Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'); ?>
	<?php $fecha_ini_m = new DateTime($mes . '01'); ?>
	<?php $fecha_ini_p = new DateTime($division_inasistencia->fecha_inicio); ?>
	<?php $fecha_fin_m = new DateTime($mes . '01 +1 month'); ?>
	<?php $fecha_fin_p = new DateTime($division_inasistencia->fecha_fin . ' +1 day'); ?>
	<?php $fecha_ini = max(array($fecha_ini_m, $fecha_ini_p)); ?>
	<?php $fecha_fin = min(array($fecha_fin_m, $fecha_fin_p)); ?>
	<?php $dia = DateInterval::createFromDateString('1 day'); ?>
	<?php $fechas = new DatePeriod($fecha_ini, $dia, $fecha_fin); ?>
	<?php if ($division_inasistencia->fecha_cierre): ?>
		<?php $alumnos_primer_dia_sexo = array('1' => 0, '2' => 0); ?>
		<?php $alumnos_entrada_sexo = array('1' => 0, '2' => 0); ?>
		<?php $alumnos_salida_sexo = array('1' => 0, '2' => 0); ?>
		<?php $total_dias_sexo = array('1' => 0, '2' => 0); ?>
		<?php $total_general_asistencia_ideal = 0; ?>
		<?php foreach ($alumnos as $alumno): ?>
			<?php $asistencia_ideal = $division_inasistencia->dias - (isset($inasistencias_resumen[$alumno->id]['NC']) ? $inasistencias_resumen[$alumno->id]['NC'] : 0); ?>
			<?php if (empty($alumno->sexo_id)): ?>
				<?php $alumnos_sin_sexo[] = $alumno; ?>
			<?php else: ?>
				<?php if ($alumno->fecha_desde <= $fecha_ini->format('Y-m-d')): ?>
					<?php $alumnos_primer_dia_sexo[$alumno->sexo_id] ++; ?>
				<?php endif; ?>
				<?php if (!empty($alumno->fecha_hasta) && (new DateTime($fecha_fin->format('Y-m-d') . ' -1 day'))->format('Y-m-d') >= ($alumno->fecha_hasta)): ?>
					<?php $alumnos_salida_sexo[$alumno->sexo_id] ++; ?>
				<?php endif; ?>
				<?php if ((new DateTime($fecha_fin->format('Y-m-d') . ' -1 day'))->format('Y-m-d') >= $alumno->fecha_desde && $alumno->fecha_desde > $fecha_ini->format('Y-m-d')): ?>
					<?php $alumnos_entrada_sexo[$alumno->sexo_id] ++; ?>
				<?php endif; ?>
				<?php $total_dias_sexo[$alumno->sexo_id] += $division_inasistencia->dias; ?>
			<?php endif; ?>
			<?php $total_general_asistencia_ideal += $asistencia_ideal; ?>
		<?php endforeach; ?>
		<div class="col-sm-6">
			<table class="table table-hover table-bordered table-condensed text-sm">
				<tr>
					<th style="width: 600px"></th>
					<th>V</th>
					<th>M</th>
					<th style="width: 40px">Total</th>
				</tr>
				<tr>
					<td><strong>Alumnos al 1° día</strong></td>
					<td>
						<span class="badge bg-blue"><?php echo $alumnos_primer_dia_sexo['1']; ?></span>
					</td>
					<td>
						<span class="badge bg-blue"><?php echo $alumnos_primer_dia_sexo['2']; ?></span>
					</td>
					<td>
						<span class="badge bg-green"><?php echo $alumnos_primer_dia_sexo['1'] + $alumnos_primer_dia_sexo['2']; ?></span>
					</td>
				</tr>
				<tr>
					<td><strong>Alumnos Entrada</strong></td>
					<td>
						<span class="badge bg-blue"><?php echo $alumnos_entrada_sexo['1']; ?></span>
					</td>
					<td>
						<span class="badge bg-blue"><?php echo $alumnos_entrada_sexo['2']; ?></span>
					</td>
					<td>
						<span class="badge bg-green"><?php echo $alumnos_entrada_sexo['1'] + $alumnos_entrada_sexo['2']; ?></span>
					</td>
				</tr>
				<tr>
					<td><strong>Alumnos Salida</strong></td>
					<td>
						<span class="badge bg-blue"><?php echo $alumnos_salida_sexo['1']; ?></span>
					</td>
					<td>
						<span class="badge bg-blue"><?php echo $alumnos_salida_sexo['2']; ?></span>
					</td>
					<td>
						<span class="badge bg-green"><?php echo $alumnos_salida_sexo['1'] + $alumnos_salida_sexo['2']; ?></span>
					</td>
				</tr>
				<tr>
					<td style="width: 600px"><strong>Quedan el último día</strong></td>
					<td>
						<span class="badge bg-blue"><?php echo $alumnos_entrada_sexo['1'] + $alumnos_primer_dia_sexo['1'] - $alumnos_salida_sexo['1']; ?></span>
					</td>
					<td>
						<span class="badge bg-blue"><?php echo $alumnos_entrada_sexo['2'] + $alumnos_primer_dia_sexo['2'] - $alumnos_salida_sexo['2']; ?></span>
					</td>
					<td>
						<span class="badge bg-green"><?php echo $alumnos_entrada_sexo['1'] + $alumnos_primer_dia_sexo['1'] - $alumnos_salida_sexo['1'] + $alumnos_entrada_sexo['2'] + $alumnos_primer_dia_sexo['2'] - $alumnos_salida_sexo['2']; ?></span>
					</td>
				</tr>
			</table>
		</div>
		<div class="col-sm-6">
			<table class="table table-hover table-bordered table-condensed text-sm">
				<tr>
					<th style="width: 600px"></th>
					<th>V</th>
					<th>M</th>
					<th style="width: 40px">Total</th>
				</tr>
				<?php $indices = array('Asistencia ideal', 'Total de Inasistencia', 'Total de Asistencia', 'Asistencia Media', '% de Asistencia'); ?>
				<?php foreach (array('1', '2') as $sexo): ?>
					<?php $indices_data['Asistencia ideal'][$sexo] = $total_dias_sexo[$sexo] - (empty($inasistencias_resumen_sexo[$sexo]['NC']) ? 0 : $inasistencias_resumen_sexo[$sexo]['NC']); ?>
					<?php $indices_data['Total de Inasistencia'][$sexo] = (empty($inasistencias_resumen_sexo[$sexo]['No']) ? 0 : $inasistencias_resumen_sexo[$sexo]['No']) + (empty($inasistencias_resumen_sexo[$sexo]['Si']) ? 0 : $inasistencias_resumen_sexo[$sexo]['Si']); ?>
					<?php $indices_data['Total de Asistencia'][$sexo] = $indices_data['Asistencia ideal'][$sexo] - $indices_data['Total de Inasistencia'][$sexo]; ?>
					<?php $indices_data['Asistencia Media'][$sexo] = empty($division_inasistencia->dias) ? 0 : ($indices_data['Asistencia ideal'][$sexo] - $indices_data['Total de Inasistencia'][$sexo]) / $division_inasistencia->dias; ?>
					<?php $indices_data['% de Asistencia'][$sexo] = (empty($indices_data['Asistencia ideal'][$sexo])) ? 0 : ($indices_data['Total de Asistencia'][$sexo] / $indices_data['Asistencia ideal'][$sexo]) * 100; ?>
				<?php endforeach; ?>
				<?php $indices_data['% de Asistencia']['T'] = ($indices_data['Asistencia ideal']['1'] + $indices_data['Asistencia ideal']['2']) == 0 ? 0 : (($indices_data['Total de Asistencia']['1'] + $indices_data['Total de Asistencia']['2']) / ($indices_data['Asistencia ideal']['1'] + $indices_data['Asistencia ideal']['2'])) * 100; ?>
				<?php foreach ($indices as $i => $indice): ?>
					<tr>
						<td><strong><?php echo $indice; ?></strong></td>
						<td>
							<span class="badge bg-blue"><?php echo number_format($indices_data[$indice]['1'], $i === 0 ? 0 : 1, ',', ''); ?></span>
						</td>
						<td>
							<span class="badge bg-blue"><?php echo number_format($indices_data[$indice]['2'], $i === 0 ? 0 : 1, ',', ''); ?></span>
						</td>
						<td><span class="badge bg-green"><?php echo number_format(empty($indices_data[$indice]['T']) ? $indices_data[$indice]['1'] + $indices_data[$indice]['2'] : $indices_data[$indice]['T'], $i === 0 ? 0 : 1, ',', ''); ?></span></td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php if (!empty($alumnos_sin_sexo)): ?>
				<table class="table-striped table-hover table-bordered dt-responsive" role="grid">
					<thead>
						<tr>
							<th colspan="3" class="text-center bg-gray">
								<h5><strong>Alumnos que no poseen cargado el campo sexo en el sistema, complete el campo para que las estadísticas figuren correctamente: </strong></h5>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($alumnos_sin_sexo as $alumno): ?>
							<tr>
								<td>
									<?php echo "$alumno->documento_tipo $alumno->documento"; ?>
								</td>
								<td>
									<?php echo $alumno->persona; ?>
								</td>
								<td>
									<a class="btn btn-xs btn-warning" target="_blank" href="alumno/editar/<?php echo $alumno->id; ?>"  title="Ver"><i class="fa fa-edit"></i></a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>