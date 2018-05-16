<div class="content-wrapper">
	<section class="content-header">
		<div role="tabpanel" class="tab-pane" id="tab_alumnos">
			<div style="text-align: left;">
				<img src="img/generales/logo-dge-sm.png" height="70px" width="70px">
			</div>
			<div style="margin-top: 3%">
				<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
					<thead>
						<tr>
							<th colspan="9" style="text-align: center; padding:20px 60px 20px 20px;">Planilla de notas cargadas</th>
						</tr>
						<tr>
							<th colspan="9">Espacio Curricular: <?php echo empty($cursada->espacio_curricular) ? '' : $cursada->espacio_curricular; ?></th>
						</tr>
						<tr>
							<th colspan="9">Evaluación: <?php echo empty($evaluacion->evaluacion_tipo) ? '' : $evaluacion->evaluacion_tipo; ?> - <?php echo empty($evaluacion->tema) ? '' : $evaluacion->tema; ?></th>
						</tr>
						<tr>
							<th colspan="9">Curso y División: <?php echo empty($cursada->division) ? '' : $cursada->division; ?></th>
						</tr>
						<tr>
							<th colspan="9">Fecha de la evaluación: <?php echo empty($evaluacion->fecha) ? '' : (new DateTime($evaluacion->fecha))->format('d/m/Y'); ?></th>
						</tr>
						<tr>
							<th>Documento</th>
							<th>Apellido y Nombres</th>
							<th>Sexo</th>
							<th>C.L</th>
							<th>Condición</th>
							<th>Fecha desde</th>
							<th>Fecha hasta</th>
							<th>Nota</th>
							<th>Asistencia</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($notas)): ?>
							<?php foreach ($notas as $orden => $nota_alumno): ?>
								<tr>
									<td><?= $nota_alumno->documento; ?></td>
									<td><?= $nota_alumno->persona; ?></td>
									<td><?= $nota_alumno->sexo; ?></td>
									<td><?= $nota_alumno->ciclo_lectivo; ?></td>
									<td><?= $nota_alumno->condicion; ?></td>
									<td><?= empty($nota_alumno->fecha_desde) ? '' : (new DateTime($nota_alumno->fecha_desde))->format('d/m/Y'); ?></td>
									<td><?= empty($nota_alumno->fecha_hasta) ? '' : (new DateTime($nota_alumno->fecha_hasta))->format('d/m/Y'); ?></td>
									<td><?= $nota_alumno->nota_evaluacion; ?></td>
									<td><?= $nota_alumno->asistencia; ?> </td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="9" align="center">-- No hay notas cargadas en la evaluación --</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>