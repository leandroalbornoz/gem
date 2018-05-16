<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Reporte de asistencias por supervisión
		</h1>
		<ol class="breadcrumb">
			<li><a href="consultas/reportes_estaticos/escritorio"><i class="fa fa-home"></i>Reportes Estaticos</a></li>
			<li><a href="consultas/reportes_estaticos/reportes_asistencia_calendario">Reporte por calendario</a></li>
			<li>Reporte</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-body">
						<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_reporte')); ?>
						<?php echo form_hidden($_POST); ?>
						<a class="btn btn-app btn-app-zetta" href="consultas/reportes_estaticos/reportes_asistencia_calendario">
							<i class="fa fa-reply"></i>Volver
						</a>
						<button type="submit" formaction="consultas/reportes_estaticos/excel_asistencia" class="btn btn-app btn-app-zetta">
							<i class="fa fa-file-excel-o text-green"></i> Exportar
						</button>
						<?php echo form_close(); ?>
						<hr style="margin: 10px 0;">
						<?php if (!empty($reporte_mes)): ?>
							<div class="row">
								<div class="col-md-4">
									<div class="box-header">
										<h3 class="box-title"><b><u>Resumen estadístico</u></b></h3>
									</div>
									<div class="box-body no-padding">
										<table class="table table-condensed">
											<tbody>
												<tr>
													<th style="width: 70%">Detalle</th>
													<th style="width: 30%">Valor</th>
												</tr>
												<tr>
													<td>Calendarios</td>
													<td><span class="badge bg-light-blue"><?php echo count($reporte_mes); ?></span></td>
												</tr>
												<tr>
													<td>Número de Supervisiones</td>
													<td><span class="badge bg-light-blue"><?php echo (!empty($numero_supervisiones)) ? $numero_supervisiones->valor : count($supervision); ?></span></td>
												</tr>
												<tr>
													<td>Número de escuelas</td>
													<td><span class="badge bg-light-blue"><?php echo (!empty($numero_escuelas)) ? $numero_escuelas->valor : count($escuela); ?></span></td>
												</tr>
												<tr>
													<td>Desde</td>
													<td>
														<span class="badge bg-light-blue"><?php echo $this->nombres_meses[substr($desde, -2)] . " de " . substr($desde, 0, 4); ?> </span>
													</td>
												</tr>
												<tr>
													<td>Hasta</td>
													<td>
														<span class="badge bg-light-blue"><?php echo $this->nombres_meses[substr($hasta, -2)] . " de " . substr($hasta, 0, 4); ?></span>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<br>
							<div class="box-header with-border">
								<h3 class="box-title">Calendarios:</h3>
							</div>
							<div class="box-body">
								<div class="box-group" id="accordion">
									<?php $i = 0; ?>
									<?php foreach ($reporte_mes as $calendario => $reporte_mes): ?>
										<div class="panel box box-primary">
											<div class="box-header with-border">
												<h4 class="box-title">
													<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>" aria-expanded="false" class="collapsed">
														<?php echo $calendario; ?>
													</a>
												</h4>
											</div>
											<div id="collapse<?php echo $i; ?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
												<div class="box-body">
													<?php foreach ($reporte_mes as $periodo => $reporte_periodo): ?>
														<div class="panel panel-primary">
															<div class="panel-heading text-center"><?php echo "$calendario"; ?>&nbsp;
																<span class="badge"><?php echo "$periodo ° Periodo"; ?>&nbsp;(<?php echo $this->nombres_meses[substr(array_keys($reporte_periodo)[0], -2)] . " - " . $this->nombres_meses[substr(array_keys($reporte_periodo)[count($reporte_periodo) - 1], -2)]; ?>)</span>
															</div>
															<?php foreach ($reporte_periodo as $mes => $reporte): ?>
																<table id="reporte_table" class="table table-hover table-bordered table-condensed" role="grid" >
																	<thead>
																		<tr class="active">
																			<th colspan="8" class="text-center">
																				<?php echo $this->nombres_meses[substr($mes, -2)]; ?> 
																				de 
																				<?php echo substr($mes, 0, 4); ?>
																				<span class="label label-success pull-right"><?php echo count($reporte); ?>&nbsp;Registros</span>
																			</th>
																		</tr>
																		<tr>
																			<th style="padding-left: 8px;">Supervision</th>
																			<th>N° de Escuelas</th>
																			<th>N° de Divisiones</th>
																			<th>Asistencia Media</th>
																			<th>Matrícula primer día</th>
																			<th>Matrícula último día</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php foreach ($reporte as $reporte_escuela_mes): ?>
																			<tr>
																				<td style="padding-left: 8px;"><?php echo "$reporte_escuela_mes->supervision"; ?></td>
																				<td><?php echo "$reporte_escuela_mes->numero_escuelas"; ?></td>
																				<td><?php echo "$reporte_escuela_mes->numero_divisiones"; ?></td>
																				<td><?php echo substr($reporte_escuela_mes->asistencia_media, 0, 5); ?> <?php echo!(empty($reporte_escuela_mes->asistencia_media)) ? '%' : ''; ?></td>
																				<td><?php echo $reporte_escuela_mes->alumnos_ini; ?></td>
																				<td><?php echo $reporte_escuela_mes->alumnos_fin; ?></td>
																			</tr>
																		<?php endforeach; ?>
																	</tbody>
																</table>
															<?php endforeach; ?>
														</div>
													<?php endforeach; ?>
												</div>
											</div>
										</div>
										<?php $i++; ?>
									<?php endforeach; ?>
								</div>
							</div>
						<?php else: ?>
							<div class="box-body">
								<div class="alert alert-warning alert-dismissible">
									<h4><i class="icon fa fa-warning"></i> Sin Datos!</h4>
									No se encontraron datos para la consulta realizada.
								</div>
							</div>
						<?php endif; ?>
					</div>
					<div class="box-footer">

					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_reporte'));
		$('#form_reporte').submit(function() {
			$(this).data('submitted', false);
			$(document).data('submitted', false);
		});
	});
</script>