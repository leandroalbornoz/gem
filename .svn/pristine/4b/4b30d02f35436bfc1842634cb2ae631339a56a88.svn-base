<style>
	.list-group-horizontal .list-group-item {
    display: inline-block;
	}
	.tab-pane{
		padding: 5px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label>Linea</label> <?= $linea->nombre; ?>
		</h1>
		<ol class="breadcrumb">
			<li class="active"><i class="fa fa-home"></i> Inicio</li>
		</ol>
	</section>
	<section class="content">
		<?php if (!empty($error)) : ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-ban"></i> Error!</h4>
				<?= $error; ?>
			</div>
		<?php endif; ?>
		<?php if (!empty($message)) : ?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-check"></i> OK!</h4>
				<?= $message; ?>
			</div>
		<?php endif; ?>
		<div class="row">
			<?php if (!empty($anuncios)): ?>
				<?php $cant_anuncios = count($anuncios); ?>
				<div class="col-xs-12">
					<div class="box box-warning collapsed-box">
						<div class="box-header with-border">
							<h3 class="box-title"><b><?= (new DateTime($anuncios[0]->fecha))->format('d/m/Y') . ' ' . $anuncios[0]->titulo; ?></b></h3>
							<div class="box-tools pull-right">
								<?php if ($cant_anuncios > 1): ?>
									<a class="btn btn-primary btn-xs" href="escuela/anuncios/<?= $this->usuario; ?>">
										<i class="fa fa-list"></i> Ver todos los anuncios (<?= $cant_anuncios; ?>)
									</a>
								<?php endif; ?>
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
							</div>
						</div>
						<div class="box-body">
							<?= $anuncios[0]->texto; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="col-xs-12">
				<?php if (!empty($niveles)): ?>
					<ul class="nav nav-tabs" role="tablist">
						<?php $tab_class = 'active'; ?>
						<?php foreach ($niveles as $nivel): ?>
							<li role="presentation" class="<?= $tab_class; ?>"><a href="#nivel_<?= $nivel->id; ?>" aria-controls="<?= strtolower(str_replace(' ', '_', $nivel->descripcion)); ?>" role="tab" data-toggle="tab">Nivel <?= $nivel->descripcion; ?></a></li>
							<?php $tab_class = ''; ?>
						<?php endforeach; ?>
					</ul>
					<div class="tab-content">
						<?php $tab_class = 'active'; ?>
						<?php foreach ($niveles as $nivel): ?>
							<div role="tabpanel" class="tab-pane <?= $tab_class; ?>" id="nivel_<?= $nivel->id; ?>">
								<?php $tab_class = ''; ?>
								<div class="row">
									<div class="col-xs-12">
										<div class="box box-primary collapsed-box">
											<div class="box-header with-border">
												<h3 class="box-title">Características</h3>
												<div class="box-tools pull-right">
													<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
												</div>
											</div>
											<div class="box-body ">
												<?php if (!empty($nivel->caracteristicas)): ?>
													<ul class="nav nav-tabs" role="tablist">
														<?php $tab_class = 'active'; ?>
														<?php foreach ($nivel->caracteristicas as $tipo_caracteristica): ?>
															<li role="presentation" class="<?= $tab_class; ?>"><a href="#tipo_<?= $nivel->id; ?>_<?= $tipo_caracteristica->id; ?>" aria-controls="tipo_<?= $tipo_caracteristica->id; ?>" role="tab" data-toggle="tab"><?= $tipo_caracteristica->descripcion; ?></a></li>
															<?php $tab_class = ''; ?>
														<?php endforeach; ?>
													</ul>
													<div class="tab-content">
														<?php $tab_class = 'active'; ?>
														<?php foreach ($nivel->caracteristicas as $tipo_caracteristica): ?>
															<div role="tabpanel" class="tab-pane <?= $tab_class; ?>" id="tipo_<?= $nivel->id; ?>_<?= $tipo_caracteristica->id; ?>">
																<?php $tab_class = ''; ?>
																<div class="list-group list-group-horizontal list-inline">
																	<?php foreach ($tipo_caracteristica->caracteristicas as $caracteristica): ?>
																		<a href="#" class="list-group-item"><?= $caracteristica->descripcion; ?></a>
																	<?php endforeach; ?>
																</div>
															</div>
														<?php endforeach; ?>
													</div>
												<?php endif; ?>
											</div>
											<div class="box-footer">
												<a class="btn btn-primary" href="caracteristica_nivel/listar/<?= $nivel->id; ?>">
													<i class="fa fa-edit" id="btn-caracteristicasr"></i> Editar características
												</a>
											</div>
										</div>
									</div>
									<div class="col-xs-12"><!-- Supervisiones -->
										<div class="box box-primary">
											<div class="box-header with-border">
												<div class="box-tools pull-right">
													<a class="btn-sm btn-success" href="linea/exportar_excel_supervision/<?=$linea->id;?>" title="Exportar excel">
															<i class="fa fa-file-excel-o" id="btn-escuelas"></i> Exportar Excel</a>
														<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
													</div>
												<h3 class="box-title">Supervisiones</h3>
												<div class="box-tools pull-right">
													<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
												</div>
											</div>
											<div class="box-body">
												<div class="row">
													<div class="col-xs-12">
														<?php if (!empty($nivel->supervisiones)): ?>
															<table class="table table-bordered table-condensed table-striped table-hover">
																<tr>
																	<th>N°</th>
																	<th>Supervisión</th>
																	<th class="text-center">Regional </th>
																	<th class="text-center">Supervisor</th>
																	<th style="width: 60px;">Escuelas</th>
																	<th class="text-center">Teléfono</th>
																	<th style="white-space: nowrap">Matrícula</th>
																	<th class="text-center">C.L Actualizado</th>

																	<th></th>
																	<th></th>
																</tr>
																<?php foreach ($nivel->supervisiones as $supervision): ?>
																	<tr>
																		<td><?= $supervision->orden; ?></td>
																		<td><?= $supervision->nombre; ?></td>
																		<td> <?= $supervision->regional; ?> </td>
																		<td> <?= $supervision->responsable; ?> </td>
																		<td class="text-center"><?= "$supervision->escuelas"; ?></td>
																		<td> <?= $supervision->telefono; ?></td>
																		<td class="text-right">
																			<?= "$supervision->matricula_2017"; ?>
																			<span style="margin-top: -2px; padding: 1px 7px; margin-left: 2px;" class="badge ">2017</span><br>
																			<?= "$supervision->matricula_2018"; ?>
																			<span style="margin-top: -2px; padding: 1px 7px; margin-left: 2px;" class="badge bg-green">2018</span>
																		</td>
																		<td class="text-center">
																			<?= substr($supervision->porcentaje_actualizacion_cl, 0, -2); ?>%
																		</td>
																		<td><a class="btn btn-xs" href="supervision/escritorio/<?= $supervision->id; ?>"><i class="fa fa-home"></i></a></td>
																		<td><a class="btn btn-xs" href="supervision/reportes/<?= $supervision->id; ?>"><i class="fa fa-file-excel-o"></i></a></td>
																	</tr>
																<?php endforeach; ?>
															</table>
														<?php endif; ?>
													</div>
												</div>
											</div>
											<div class="box-footer">
												<a class="btn btn-primary" href="escuela/listar/<?= $nivel->id; ?>/1">
													<i class="fa fa-cogs" id="btn-escuelas"></i> Administrar
												</a>
												<?php if (ENVIRONMENT !== 'production'): ?>
													<a class="btn btn-success pull-right" href="consultas/reportes_estaticos/escritorio">
														<i class="fa fa-table" id="btn-escuelas"></i> Reportes estáticos
													</a>
												<?php endif; ?>
											</div>
										</div>
									</div><!-- Fin Escuelas -->
									<div class="col-xs-6"><!-- Carreras -->
										<div class="box box-primary">
											<div class="box-header with-border">
												<h3 class="box-title">Carreras</h3>
												<div class="box-tools pull-right">
													<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
												</div>
											</div>
											<div class="box-body">
												<div class="row">
													<div class="col-xs-12">
														<?php if (!empty($nivel->carreras)): ?>
															<table class="table table-bordered table-condensed table-striped table-hover">
																<tr>
																	<th>Carrera</th>
																	<th style="width: 80px;">Escuelas</th>
																	<th style="width: 34px;"></th>
																	<th style="width: 34px;"></th>
																</tr>
																<?php foreach ($nivel->carreras as $carrera): ?>
																	<tr>
																		<td><?= $carrera->descripcion; ?></td>
																		<td class="text-center"><?= $carrera->escuelas; ?></td>
																		<td><a class="btn btn-xs" href="escuela_carrera/espacios_curriculares/<?= $carrera->id; ?>"><i class="fa fa-search"></i></a></td>
																		<td><a class="btn btn-xs" href="escuela_carrera/escuelas/<?= $carrera->id; ?>"><i class="fa fa-home"></i></a></td>
																	</tr>
																<?php endforeach; ?>
															</table>
														<?php endif; ?>
													</div>
												</div>
											</div>
											<div class="box-footer">
												<a class="btn btn-primary" href="carrera/listar/<?= $nivel->id; ?>">
													<i class="fa fa-cogs" id="btn-carreras"></i> Administrar
												</a>
											</div>
										</div>
									</div><!-- Fin Carreras -->
									<?php if (!empty($nivel->modulos)): ?>
										<?php foreach ($nivel->modulos as $modulo): ?>
											<div class="col-xs-6">
												<?= $modulo; ?>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>
									<div class="col-xs-6"><!-- Alertas -->
										<div class="box box-primary">
											<div class="box-header with-border">
												<h3 class="box-title">Alertas</h3>
												<div class="box-tools pull-right">
													<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
												</div>
											</div>
											<div class="box-body">
												<?php foreach ($nivel->indices as $indice_titulo => $indice): ?>
													<div class="progress-group">
														<span class="progress-text"><?= $indice_titulo; ?></span>
														<span class="progress-number"><b><?= ($indice->cantidad); ?></b>/<?= $indice->total; ?></span>
														<div class="progress sm">
															<?php $cumplimiento = $indice->total == 0 ? 0 : (round($indice->cantidad / $indice->total * 100)); ?>
															<div class="progress-bar progress-bar-<?= $cumplimiento >= 100 ? 'green' : ($cumplimiento >= 50 ? 'yellow' : 'red'); ?>" style="line-height:10px;font-size:10px; min-width: 2em; width: <?= $cumplimiento; ?>%"><?= $cumplimiento; ?>%</div>
														</div>
													</div>
													<!-- /.progress-group -->
												<?php endforeach; ?>
											</div>
										</div>
									</div><!-- Fin Alertas -->
									<?php if ($nivel->id === '2'/* && ENVIRONMENT !== 'production' */): ?>
										<div class="col-xs-12">
											<div class="box box-warning">
												<div class="box-header with-border">
													<h3 class="box-title">Ingreso a 1° Grado - Ciclo Lectivo 2018</h3>
													&nbsp;
													<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/modal_consultar_estado"><i class="fa fa-search"></i> Consulta de Alumnos</a>
													<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/modal_vacantes_generales"><i class="fa fa-search"></i> Consulta de Escuelas con Vacantes</a>
													<div class="box-tools pull-right">
														<a class="btn-sm btn-success" href="preinscripciones/preinscripcion_alumno/excel/<?= $nivel->id ?>/<?= $nivel->supervisiones[0]->dependencia_id ?>/<?= $escuelas_p[0]->id ?>" title="Exportar excel">
															<i class="fa fa-file-excel-o" id="btn-escuelas"></i> Exportar Excel</a>
														<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
													</div>
												</div>
												<div class="box-body ">
													<table class="table table-bordered table-condensed table-striped table-hover">
														<?php $fecha = date('Y-m-d'); ?>
														<tr>
															<th rowspan="2" style="text-align:center;">Supervisión</th>
															<th rowspan="2" style="text-align:center;">Escuelas</th>
															<th colspan="2" style="text-align:center;">1° Grado 2017</th>
															<th colspan="8" style="text-align:center;">Preinscripción 2018</th>
															<th style="text-align:right;"></th>
														</tr>
														<tr>
															<th style="text-align:center;">Divisiones</th>
															<th style="text-align:center;">Alumnos</th>
															<th style="text-align:center;">Vacantes<br>(<?php echo(new DateTime($preinscripcion_instancias[0]->desde))->format('d/m'); ?> - <?php echo(new DateTime($preinscripcion_instancias[0]->hasta))->format('d/m'); ?>)</th>
															<th style="text-align:center;">Total Insc.</th>
															<th style="text-align:center;">Vacantes finales</th>
															<th style="text-align:center;">1° Inst.<br>(<?php echo(new DateTime($preinscripcion_instancias[1]->desde))->format('d/m'); ?> - <?php echo(new DateTime($preinscripcion_instancias[1]->hasta))->format('d/m'); ?>)</th>
															<th style="text-align:center;">2° Inst.<br>(<?php echo(new DateTime($preinscripcion_instancias[2]->desde))->format('d/m'); ?> - <?php echo(new DateTime($preinscripcion_instancias[2]->hasta))->format('d/m'); ?>)</th>
															<th style="text-align:center;">3° Inst.<br>(<?php echo(new DateTime($preinscripcion_instancias[3]->desde))->format('d/m'); ?> - <?php echo(new DateTime($preinscripcion_instancias[3]->hasta))->format('d/m'); ?>)</th>
															<th style="text-align:center;">4° Inst.<br>(<?php echo(new DateTime($preinscripcion_instancias[4]->desde))->format('d/m'); ?> - <?php echo(new DateTime($preinscripcion_instancias[4]->hasta))->format('d/m'); ?>)</th>
															<th style="text-align:center;">Postulantes/<br>Derivados</th>
															<th></th>
														</tr>
														<?php $total_escuelas = 0; ?>
														<?php $total_divisiones = 0; ?>
														<?php $total_alumnos = 0; ?>
														<?php $total_vacantes = 0; ?>
														<?php $total_inscriptos = 0; ?>
														<?php $total_vacantes_finales = 0; ?>
														<?php $total_instancia1 = 0; ?>
														<?php $total_instancia2 = 0; ?>
														<?php $total_instancia3 = 0; ?>
														<?php $total_instancia4 = 0; ?>
														<?php $total_postulantes = 0; ?>
														<?php $total_derivados = 0; ?>
														<?php foreach ($escuelas_p as $escuela): ?>
															<tr>
																<td style="width: 20%;"><?= $escuela->nombre; ?></td>
																<td style="width: 5%;" class="text-center"><?= $escuela->escuelas; ?></td>
																<?php $total_escuelas += $escuela->escuelas; ?>
																<td style="width: 5%;" class="text-center"><?= $escuela->divisiones; ?></td>
																<?php $total_divisiones += $escuela->divisiones; ?>
																<td style="width: 5%;" class="text-center"><?= $escuela->alumnos; ?></td>
																<?php $total_alumnos += $escuela->alumnos; ?>
																<td style="width: 10%;" class="text-center">
																	<?php if ($fecha >= $preinscripcion_instancias[0]->desde): ?>
																		<?= "$escuela->vacantes ($escuela->escuelas_p esc.)"; ?>
																	<?php endif; ?>
																	<?php $total_vacantes += $escuela->vacantes; ?>
																</td>
																<td style="width: 5%;" class="text-center"><?= $escuela->inscriptos; ?></td>
																<?php $total_inscriptos += $escuela->inscriptos; ?>
																<td style="width: 5%;" class="text-center"><?= $escuela->vacantes - $escuela->inscriptos; ?></td>
																<?php $total_vacantes_finales += $escuela->vacantes - $escuela->inscriptos; ?>
																<td style="width: 10%;" class="text-center">
																	<?php if ($fecha >= $preinscripcion_instancias[1]->desde): ?>
																		<?php if ($fecha <= $preinscripcion_instancias[1]->hasta): ?>
																			<?= $escuela->instancia_1; ?>
																		<?php else: ?>
																			<?= $escuela->instancia_1; ?>
																		<?php endif; ?>
																	<?php endif; ?>
																</td>
																<?php $total_instancia1 += $escuela->instancia_1; ?>
																<td style="width: 10%;" class="text-center">
																	<?php if ($fecha >= $preinscripcion_instancias[2]->desde): ?>
																		<?php if ($fecha <= $preinscripcion_instancias[2]->hasta): ?>
																			<?= $escuela->instancia_2_i; ?>
																		<?php else: ?>
																			<?= $escuela->instancia_2_i; ?>
																		<?php endif; ?>
																	<?php endif; ?>
																</td>
																<?php $total_instancia2 += $escuela->instancia_2_i; ?>
																<td style="width: 10%;" class="text-center">
																	<?php if ($fecha >= $preinscripcion_instancias[3]->desde): ?>
																		<?= $escuela->instancia_3 - $escuela->instancia_3_d; ?>
																	<?php endif; ?>
																</td>
																<?php $total_instancia3 += $escuela->instancia_3 - $escuela->instancia_3_d; ?>
																<td style="width: 10%;" class="text-center">
																	<?php if ($fecha >= $preinscripcion_instancias[4]->desde): ?>
																		<?= $escuela->instancia_4; ?>
																	<?php endif; ?>
																</td>
																<?php $total_instancia4 += $escuela->instancia_4; ?>
																<td style="width: 5%;" class="text-center">
																	<?php echo!empty($escuela->instancia_2_p) ? $escuela->instancia_2_p : 0; ?>/<?php echo!empty($escuela->instancia_3_d) ? $escuela->instancia_3_d : 0; ?>
																</td>
																<?php $total_postulantes += !empty($escuela->instancia_2_p) ? $escuela->instancia_2_p : 0; ?>
																<?php $total_derivados += !empty($escuela->instancia_3_d) ? $escuela->instancia_3_d : 0; ?>
																<td>
																	<a class="btn btn-xs" href="supervision/escritorio/<?= $escuela->id; ?>"><i class="fa fa-home"></i></a>
																</td>
															</tr>
														<?php endforeach; ?>
														<tr style="background-color:#3c8dbc;color: white">
															<td>TOTAL</td>
															<td class="text-center"><?= number_format($total_escuelas, 0, ',', '.'); ?></td>
															<td class="text-center"><?= number_format($total_divisiones, 0, ',', '.'); ?></td>
															<td class="text-center"><?= number_format($total_alumnos, 0, ',', '.'); ?></td>
															<td class="text-center"><?= number_format($total_vacantes, 0, ',', '.'); ?></td>
															<td class="text-center"><?=number_format($total_inscriptos, 0, ',', '.'); ?></td>
															<td class="text-center"><?= number_format($total_vacantes_finales, 0, ',', '.'); ?></td>
															<td class="text-center"><?= number_format($total_instancia1, 0, ',', '.'); ?></td>
															<td class="text-center"><?= number_format($total_instancia2, 0, ',', '.'); ?></td>
															<td class="text-center"><?= number_format($total_instancia3, 0, ',', '.'); ?></td>
															<td class="text-center"><?= number_format($total_instancia4, 0, ',', '.'); ?></td>
															<td class="text-center"><?= number_format($total_postulantes, 0, ',', '.'); ?>/<?= $total_derivados; ?></td>
															<td class="text-center"></td>
														</tr>
													</table>
												</div>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
</div>