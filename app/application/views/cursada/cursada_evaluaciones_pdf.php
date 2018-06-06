<div class="content-wrapper">
	<section class="content-header">
		<div role="tabpanel" class="tab-pane" id="tab_alumnos">
			<div style="text-align: left;">
				<img src="img/generales/logo-dge-sm.png" height="70px" width="70px">
			</div>
			<div style="margin-top: 3%">
				<?php $array_notas = array('1' => 'R', '2' => 'A', '3' => 'V'); ?>
				<?php if (!empty($evaluaciones_periodo)): ?>
					<?php $num = 0; ?>
					<?php foreach ($evaluaciones_periodo as $calendario_periodo => $evaluaciones): ?>
						<h4><b><?php echo $calendario_periodo; ?></b></h4>
						<table class="table table-hover table-bordered table-condensed text-sm" role="grid" >
							<thead>
								<tr>
									<th style="width:8%;text-align: center; white-space: nowrap;">Documento</th>
									<th style="width:10%;text-align: center; white-space: nowrap;">Alumno</th>
									<?php foreach ($evaluaciones as $evaluacion): ?>
										<?php $num++; ?>
										<th style="white-space: nowrap;text-align: center;">
											<?= '<span>' . (new DateTime($evaluacion->fecha))->format('d/m') . "<br>N°$num</span>"; ?>
										</th>
									<?php endforeach; ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($alumnos as $alumno): ?>
									<tr>
										<td style=" white-space: nowrap;"><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
										<td style=" white-space: nowrap;"><?= $alumno->persona; ?></td>
										<?php foreach ($evaluaciones as $evaluacion): ?>
											<?php if (isset($evaluacion->notas[$alumno->id])): ?>
												<?php if ($evaluacion->notas[$alumno->id]->asistencia === 'Ausente'): ?>
													<td style="text-align: center;">
														<span>Aus</span>
													</td>
												<?php else: ?>
													<?php if ($cursada->calificacion_id === '1'): ?>
														<td class="text-right" style="text-align: center;">
															<span><?= $array_notas[round($evaluacion->notas[$alumno->id]->nota)]; ?></span>
														</td>
													<?php else: ?>
														<td class="text-right" style="text-align: center;">
															<span><?= number_format($evaluacion->notas[$alumno->id]->nota, 2, ',', '.'); ?></span>
														</td>
													<?php endif; ?>
												<?php endif; ?>
											<?php else: ?>
												<td></td>
											<?php endif; ?>
										<?php endforeach; ?>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table><br>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php if (!empty($evaluaciones_periodo)): ?>
				<div style='page-break-inside: avoid;margin-left: 15px;padding-top: 15px;'>
					<h3><u>Índice de evaluaciones</u></h3>
					<table class="table table-sm">
						<?php $num = 0; ?>
						<tr>
							<th>Número</th>
							<th>Tipo</th>
							<th>Fecha</th>
							<th>Tema</th>
						</tr>
						<?php foreach ($evaluaciones_periodo as $ev_id => $evaluacion_obj): ?>
							<?php foreach ($evaluacion_obj as $evaluacion): ?>
								<tr>
									<?php $num++; ?>
									<td><?= "<b>N°$num</b> " ?></td>
									<td><?= "$evaluacion->evaluacion_tipo" ?></td>
									<td><?= (new DateTime($evaluacion->fecha))->format('d/m/Y') ?></td>
									<td><?= "$evaluacion->tema" ?></td>
								</tr>
							<?php endforeach; ?>
						<?php endforeach; ?>
					</table>
				</div>
			<?php endif; ?>
		</div>
	</section>
</div>