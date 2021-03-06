<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Reporte de asistencias por escuela
		</h1>
		<ol class="breadcrumb">
			<li><a href="consultas/reportes_estaticos/escritorio"><i class="fa fa-home"></i>Reportes Estaticos</a></li>
			<li><a href="consultas/reportes_estaticos/reportes_asistencia_carrera">Reporte por carrera</a></li>
			<li>Reporte</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-body">
						<div class="col-xs-9 pull-left">
							<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_reporte')); ?>
							<?php echo form_hidden($_POST); ?>
							<a class="btn btn-app btn-app-zetta" href="consultas/reportes_estaticos/reportes_asistencia_carrera">
								<i class="fa fa-reply"></i>Volver
							</a>
							<button type="submit" formaction="consultas/reportes_estaticos/excel_asistencia" class="btn btn-app btn-app-zetta">
								<i class="fa fa-file-excel-o text-green"></i> Exportar
							</button>
							<?php echo form_close(); ?>
						</div>
						<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agrupamiento')); ?>
						<?php echo form_hidden($_POST); ?>
						<div class="col-xs-3 pull-right">
							<div class="form-group">
								<?php echo $fields['agrupamiento']['label']; ?>
								<?php echo $fields['agrupamiento']['form']; ?>
							</div>
						</div>
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
													<td><?php echo ucfirst($criterio_agrupamiento); ?></td>
													<td><span class="badge bg-light-blue"><?php echo count($reporte_mes); ?></span></td>
												</tr>
												<tr>
													<td>Número de escuelas</td>
													<td><span class="badge bg-light-blue"><?php echo (!empty($numero_escuelas)) ? $numero_escuelas->valor : count($escuela); ?></span></td>
												</tr>
												<tr>
													<td>Número de Supervisiones</td>
													<td><span class="badge bg-light-blue"><?php echo (!empty($numero_supervisiones)) ? $numero_supervisiones->valor : count($supervision); ?></span></td>
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
								<h3 class="box-title"><?php echo ucfirst($criterio_agrupamiento); ?>:</h3>
							</div>
							<div class="box-body">
								<div class="box-group" id="accordion">
									<?php $i = 0; ?>
									<?php foreach ($reporte_mes as $carrera => $reporte_carrera): ?>
										<div class="panel box box-primary">
											<div class="box-header with-border">
												<h4 class="box-title">
													<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>" aria-expanded="false" class="collapsed">
														<?php echo $carrera; ?>
													</a>
												</h4>
											</div>
											<div id="collapse<?php echo $i; ?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
												<div class="box-body">
													<div class="panel panel-primary">
														<?php foreach ($reporte_carrera as $mes => $reporte): ?>
															<table id="reporte_table" class="table table-hover table-bordered table-condensed" role="grid">
																<thead>
																	<tr><td colspan="8"></td></tr>
																	<tr class="active">
																		<th colspan="8" class="text-center">
																			<?php echo $this->nombres_meses[substr($mes, -2)]; ?> 
																			de 
																			<?php echo substr($mes, 0, 4); ?>
																			<span class="label label-success pull-right"><?php echo count($reporte); ?>&nbsp;Registros</span>
																		</th>
																	</tr>
																	<tr>
																		<th style="padding-left: 8px;">Escuela</th>
																		<th>Asistencia Media</th>
																		<th>Numero de divisiones</th>
																		<th>Divisiones en riesgo</th>
																		<th>Matrícula primer día</th>
																		<th>Matrícula ultimo día</th>
																		<th><?php echo ucfirst($detalle); ?></th>
																	</tr>
																</thead>
																<tbody>
																	<?php foreach ($reporte as $reporte_escuela_mes): ?>
																		<tr>
																			<td style="padding-left: 8px;"><?php echo "$reporte_escuela_mes->numero - $reporte_escuela_mes->escuela"; ?></td>
																			<td><?php echo substr($reporte_escuela_mes->asistencia_media, 0, 5); ?> <?php echo!(empty($reporte_escuela_mes->asistencia_media)) ? '%' : ''; ?></td>
																			<td><?php echo $reporte_escuela_mes->numero_divisiones; ?></td>
																			<td><?php echo $reporte_escuela_mes->divisiones_riesgo; ?></td>
																			<td><?php echo $reporte_escuela_mes->alumnos_ini; ?></td>
																			<td><?php echo $reporte_escuela_mes->alumnos_fin; ?></td>
																			<td><?php echo $reporte_escuela_mes->{$detalle}; ?></td>
																		</tr>
																	<?php endforeach; ?>
																</tbody>
															</table>
														<?php endforeach; ?>
													</div>
												</div>
											</div>
										</div>
										<?php $i++; ?>
									<?php endforeach; ?>
								</div>
							</div>
						<?php else: ?>
							<br><br>
							<div class="box-body">
								<div class="alert alert-warning alert-dismissible">
									<h4><i class="icon fa fa-warning"></i> Sin Datos!</h4>
									No se encontraron datos para la consulta realizada.
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_reporte'));
		agregar_eventos($('#form_agrupamiento'));
		$('#form_reporte,#form_agrupamiento').submit(function() {
			$(this).data('submitted', false);
			$(document).data('submitted', false);
		});
		$('#agrupamiento').on('change', function() {
			$('#form_agrupamiento').submit();
		});
	});
</script>