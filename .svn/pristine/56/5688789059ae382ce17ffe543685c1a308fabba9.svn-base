<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Reporte de oferta educativa por zona
		</h1>
		<ol class="breadcrumb">
			<li><a href="consultas/reportes_estaticos/escritorio"><i class="fa fa-home"></i>Reportes Estaticos</a></li>
			<li><a href="consultas/reportes_estaticos/reportes_oferta_educativa">Reporte oferta educativa</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="consultas/reportes_estaticos/reportes_oferta_educativa">
							<i class="fa fa-reply"></i>Volver
						</a>
						<button type="submit" formaction="consultas/reportes_estaticos/excel_oferta_educativa" class="btn btn-app btn-app-zetta">
							<i class="fa fa-file-excel-o text-green"></i> Exportar
						</button>
						<?php echo form_close(); ?>
						<hr style="margin: 10px 0;">
						<?php if (!empty($reporte_depto)): ?>
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
													<td>N° de Carreras</td>
													<td><span class="badge bg-light-blue"><?php echo count($carreras); ?></span></td>
												</tr>
												<tr>
													<td>N° de escuelas</td>
													<td><span class="badge bg-light-blue"><?php echo count($escuelas); ?></span></td>
												</tr>
												<tr>
													<td>N° de departamentos</td>
													<td><span class="badge bg-light-blue"><?php echo count($reporte_depto); ?></span></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<br>
							<div class="box-header with-border">
								<h3 class="box-title">Departamentos:</h3>
							</div>
							<div class="box-body">
								<div class="box-group" id="accordion">
									<?php $i = 0; ?>
									<?php foreach ($reporte_depto as $depto => $reporte_depto): ?>
										<div class="panel box box-primary">
											<div class="box-header with-border">
												<h4 class="box-title">
													<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>" aria-expanded="false" class="collapsed">
														<?php echo $depto; ?>
													</a>
												</h4>
											</div>
											<div id="collapse<?php echo $i; ?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
												<div class="box-body">
													<div class="panel panel-primary">
														<?php foreach ($reporte_depto as $localidad => $reporte_loc): ?>
															<table id="reporte_table" class="table table-hover table-bordered table-condensed" role="grid" >
																<thead>
																	<tr><td colspan="8"></td></tr>
																	<tr class="active">
																		<th colspan="8" class="text-center">
																			<?php echo $localidad; ?>
																			<span class="label label-success pull-right"><?php echo count($reporte_loc); ?>&nbsp;Registros</span>
																		</th>
																	</tr>
																	<tr>
																		<th style="padding-left: 8px;width: 25%;">Carrera</th>
																		<th style="width: 30%;">Escuela</th>
																		<th style="width: 25%;">Nivel</th>
																		<th style="width: 15%;">Dirección</th>
																		<th style="white-space: nowrap;width: 5%;">Código postal</th>
																	</tr>
																</thead>
																<tbody>
																	<?php foreach ($reporte_loc as $reporte): ?>
																		<tr>
																			<td style="padding-left: 8px;"><?php echo "$reporte->carrera"; ?></td>
																			<td><?php echo "$reporte->escuela"; ?></td>
																			<td><?php echo "$reporte->nivel"; ?></td>
																			<td><?php echo $reporte->direccion; ?></td>
																			<td style="white-space: nowrap;"><?php echo $reporte->codigo_postal; ?></td>
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