<?php if (!empty($inasistencias)): ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Estadísticas de asistencias</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="row">
						<div class="col-md-8">
							<p class="text-center">
							<h4 align="center"><u><strong>Asistencia Media</strong></u></h4>
							</p>
							<?php if ($grafica): ?>
								<div class="row">
									<div id="chart">
									</div>
								</div>
							<?php endif; ?>
						</div>
						<!-- /.col -->
						<div class="col-md-4">
							<p class="text-center">
								<strong>Estadisticas del curso</strong>
							</p>
							<?php $dias_habiles_total = 0; ?>
							<?php $asistencia_ideal_total = 0; ?>
							<?php $inasistencias_totales = 0; ?>
							<?php $asistencia_real_total = 0; ?>
							<?php
							foreach ($estadisticas_total as $estadistica_total) {
								$dias_habiles_total += $estadistica_total->dias;
								$asistencia_ideal_total += $estadistica_total->asistencia_ideal;
								$inasistencias_totales += $estadistica_total->dias_falta;
								$asistencia_real_total += $estadistica_total->asistencia_real;
							}
							?>
							<div class="progress-group">
								<span class="progress-text">Días hábiles de cursado/Días del año</span>
								<span class="progress-number"><b><?php echo $dias_habiles_total; ?></b>/365</span>
								<div class="progress sm">
									<?php $porc_dias_habiles_total = $dias_habiles_total * 100 / 365; ?>
									<div class="progress-bar progress-bar-yellow" style="width: <?php echo $porc_dias_habiles_total; ?>%"></div>
								</div>
							</div>
							<div class="progress-group">
								<span class="progress-text">Total inasistencias/Asistencia ideal</span>
								<span class="progress-number"><b><?php echo $inasistencias_totales; ?></b>/<?php echo $asistencia_ideal_total; ?></span>
								<div class="progress sm">
									<?php if (!empty($asistencia_ideal_total)): ?>
										<?php $porc_inasistencias_totales = $inasistencias_totales * 100 / $asistencia_ideal_total; ?>
									<?php else: ?>
										<?php $porc_inasistencias_totales = 0; ?>
									<?php endif; ?>
									<div class="progress-bar progress-bar-red" style="width: <?php echo $porc_inasistencias_totales; ?>%"></div>
								</div>
							</div>
							<div class="progress-group">
								<span class="progress-text">Asistencia real/Asistencia ideal</span>
								<?php if (!empty($asistencia_ideal_total)): ?>
									<?php $porc_asistencia_real = $asistencia_real_total * 100 / $asistencia_ideal_total; ?>
								<?php else: ?>
									<?php $porc_asistencia_real = 0; ?>
								<?php endif; ?>
								<span class="progress-number"><b><?php echo $asistencia_real_total; ?></b>/<?php echo $asistencia_ideal_total; ?></span>
								<div class="progress sm">
									<div class="progress-bar progress-bar-green" style="width: <?php echo $porc_asistencia_real ?>%"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<div class="row">
						<div class="col-sm-4 col-xs-6">
							<div class="description-block border-right">
								<span class="description-percentage text-yellow"><i class="fa fa-dot-circle-o"></i> <?php echo round($porc_dias_habiles_total, 2); ?>%</span>
								<h5 class="description-header"><?php echo $dias_habiles_total; ?></h5>
								<span class="description-text">Días Hábiles</span>
							</div>
						</div>
						<div class="col-sm-4 col-xs-6">
							<div class="description-block border-right">
								<span class="description-percentage text-red"><i class="fa fa-dot-circle-o"></i> <?php echo round($porc_inasistencias_totales, 2); ?>%</span>
								<h5 class="description-header"><?php echo $inasistencias_totales; ?></h5>
								<span class="description-text">Total inasistencias</span>
							</div>
						</div>
						<div class="col-sm-4 col-xs-6">
							<div class="description-block ">
								<span class="description-percentage text-green"><i class="fa fa-dot-circle-o"></i> <?php echo round($porc_asistencia_real, 2); ?>%</span>
								<h5 class="description-header"><?php echo $asistencia_real_total ?></h5>
								<span class="description-text">Asistencia real</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			var chart = c3.generate({
				bindto: '#chart',
				axis: {
					x: {
						type: 'categories',
						max: 11,
						categories: <?php echo $grafica[1]; ?>,
						label: {
							text: 'Mes',
							position: 'outer-right'
						}
					},
					y: {
						max: 1,
						padding: 0,
						tick: {
							format: d3.format('%')
						},
						label: {
							text: '% de asistencias',
							position: 'outer-center'
						}
					}
				},
				data: {
					names: {
						data1: 'Asistencia Media'
					},
					columns: [
	<?php echo $grafica[0]; ?>
					],
					type: 'bar'
				}
			});
		});
	</script>
<?php endif; ?>
