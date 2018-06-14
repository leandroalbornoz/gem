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
			<label>Supervisión</label> <?php echo $supervision->nombre; ?>
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
				<?php echo $error; ?>
			</div>
		<?php endif; ?>
		<?php if (!empty($message)) : ?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-check"></i> OK!</h4>
				<?php echo $message; ?>
			</div>
		<?php endif; ?>
		<div class="row">
			<?php if (!empty($anuncios)): ?>
				<?php $cant_anuncios = count($anuncios); ?>
				<div class="col-xs-12">
					<div class="box box-warning collapsed-box">
						<div class="box-header with-border">
							<h3 class="box-title"><b><?php echo (new DateTime($anuncios[0]->fecha))->format('d/m/Y') . ' ' . $anuncios[0]->titulo; ?></b></h3>
							<div class="box-tools pull-right">
								<?php if ($cant_anuncios > 1): ?>
									<a class="btn btn-primary btn-xs" href="escuela/anuncios/<?php echo $this->usuario; ?>">
										<i class="fa fa-list"></i> Ver todos los anuncios (<?= $cant_anuncios; ?>)
									</a>
								<?php endif; ?>
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
							</div>
						</div>
						<div class="box-body">
							<?php echo $anuncios[0]->texto; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><label>Supervisión</label> <?php echo "$supervision->nombre"; ?></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12"><label>Nivel:</label> <?php echo $supervision->nivel; ?></div>
							<div class="col-sm-12"><label>Responsable:</label> <?php echo $supervision->responsable ?></div>
							<div class="col-sm-12"><label>Telefono:</label> <?php echo $supervision->telefono; ?></div>
							<div class="col-sm-12"><label>Domicilio:</label> <?php echo "B° $supervision->barrio calle: $supervision->calle N°$supervision->calle_numero, $supervision->localidad - CP: $supervision->codigo_postal"; ?></div>
							<div class="col-sm-12"><label>Email:</label> <?php echo $supervision->email; ?></div>
						</div>
					</div>
					<?php if ($administrar): ?>
						<div class="box-footer">
							<a class="btn btn-primary" href="supervision/editar/<?php echo $supervision->id ?>">
								<i class="fa fa-edit" id="btn-editar"></i> Editar
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="box box-primary collapsed-box">
					<div class="box-header with-border">
						<h3 class="box-title">Características</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body ">
						<?php if (!empty($fields_tipos)): ?>
							<ul class="nav nav-tabs" role="tablist">
								<?php $tab_class = 'active'; ?>
								<?php foreach ($fields_tipos as $tipo => $fields_caracteristicas): ?>
									<li role="presentation" class="<?= $tab_class; ?>"><a href="#<?= strtolower(str_replace(' ', '_', $tipo)); ?>" aria-controls="<?= strtolower(str_replace(' ', '_', $tipo)); ?>" role="tab" data-toggle="tab"><?= $tipo; ?></a></li>
									<?php $tab_class = ''; ?>
								<?php endforeach; ?>
							</ul>
							<div class="tab-content">
								<?php $tab_class = 'active'; ?>
								<?php foreach ($fields_tipos as $tipo => $fields_caracteristicas): ?>
									<div role="tabpanel" class="tab-pane <?= $tab_class; ?>" id="<?= strtolower(str_replace(' ', '_', $tipo)); ?>">
										<?php $tab_class = ''; ?>
										<div class="row">
											<?php foreach ($fields_caracteristicas as $field): ?>
												<div class="form-group col-sm-3">
													<?php echo $field['label']; ?>
													<?php echo $field['form']; ?>
												</div>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
					<?php if ($administrar || $supervision->id === $this->rol->entidad_id): ?>
						<div class="box-footer">
							<a class="btn btn-primary" href="supervision/caracteristicas/<?php echo $supervision->id ?>">
								<i class="fa fa-edit" id="btn-caracteristicas"></i> Editar características
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
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
									<?php if (!empty($modulos)): ?>
										<?php foreach ($modulos as $modulo): ?>
											<div class="col-xs-12">
												<?php echo $modulo; ?>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>
									<div class="col-xs-8"><!-- Escuelas -->
										<div class="box box-primary">
											<div class="box-header with-border">
												<h3 class="box-title">Escuelas</h3>
												<div class="box-tools pull-right">
													<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
												</div>
											</div>
											<div class="box-body">
												<div class="row">
													<div class="col-xs-12">
														<?php if (!empty($nivel->escuelas)): ?>
															<table class="table table-bordered table-condensed table-striped table-hover">
																<tr>
																	<th>N°</th>
																	<th>Escuela</th>
																	<th style="width: 60px;">Cargos</th>
																	<th style="white-space: nowrap">Matrícula</th>
																	<th class="text-center">C.L Actualizado</th>
																	<th style="width: 34px;"></th>
																</tr>
																<?php foreach ($nivel->escuelas as $escuela): ?>
																	<tr>
																		<td><?php echo "$escuela->nombre_corto"; ?></td>
																		<td><?php echo "$escuela->nombre"; ?></td>
																		<td class="text-center"><?php echo "$escuela->cargos"; ?></td>		
																		<td class="text-right">
																			<?php echo "$escuela->matricula_2017"; ?>
																			<span style="margin-top: -2px; padding: 1px 7px; margin-left: 2px;" class="badge ">2017</span><br>
																			<?php echo "$escuela->matricula_2018"; ?>
																			<span style="margin-top: -2px; padding: 1px 7px; margin-left: 2px;" class="badge bg-green">2018</span>
																		</td>
																		<td class="text-center">
																			<?php echo substr($escuela->porcentaje_actualizacion_cl, 0, -2); ?>%
																		</td>
																		<td>
																			<a class="btn btn-xs" href="escuela/escritorio/<?php echo $escuela->id; ?>"><i class="fa fa-search"></i></a>
																		</td>
																	</tr>
																<?php endforeach; ?>
															</table>
														<?php endif; ?>
													</div>
												</div>
											</div>
											<div class="box-footer">
												<a class="btn btn-primary" href="supervision/escuelas/<?php echo $supervision->id; ?>/1">
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
									<div class="col-xs-4"><!-- Carreras -->
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
																		<td><?php echo $carrera->descripcion; ?></td>
																		<td class="text-center"><?php echo $carrera->escuelas; ?></td>
																		<td>
																			<a class="btn btn-xs" href="escuela_carrera/espacios_curriculares/<?php echo $carrera->id; ?>"><i class="fa fa-search"></i></a>
																		</td>
																		<td>
																			<a class="btn btn-xs" href="escuela_carrera/escuelas/<?php echo $carrera->id; ?>"><i class="fa fa-home"></i></a>
																		</td>
																	</tr>
																<?php endforeach; ?>
															</table>
														<?php endif; ?>
													</div>
												</div>
											</div>
											<div class="box-footer">
												<a class="btn btn-primary" href="carrera/listar/<?php echo $nivel->id; ?>">
													<i class="fa fa-cogs" id="btn-carreras"></i> Administrar
												</a>
											</div>
										</div>
									</div><!-- Fin Carreras -->
									<div class="col-xs-4"><!-- Alertas -->
										<div class="box box-primary">
											<div class="box-header with-border">
												<h3 class="box-title">Alertas</h3>
												<div class="box-tools pull-right">
													<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
												</div>
											</div>
											<div class="box-body">
												<table class="table">
													<?php foreach ($nivel->indices as $indice_titulo => $indice): ?>
														<tr>
															<td>
																<div class="progress-group">
																	<span class="progress-text"><?php echo $indice_titulo; ?></span>
																	<span class="progress-number"><b><?php echo ($indice->cantidad); ?></b>/<?php echo $indice->total; ?></span>
																	<div class="progress sm">
																		<?php $cumplimiento = $indice->total == 0 ? 0 : (round($indice->cantidad / $indice->total * 100)); ?>
																		<div class="progress-bar progress-bar-<?php echo $cumplimiento >= 100 ? 'green' : ($cumplimiento >= 50 ? 'yellow' : 'red'); ?>" style="line-height:10px;font-size:10px; min-width: 2em; width: <?php echo $cumplimiento; ?>%"><?php echo $cumplimiento; ?>%</div>
																	</div>
																</div>
															</td>
															<td style="width:1px;">
																<!-- /.progress-group -->
																<?php if ($indice_titulo == 'Divisiones con carreras'): ?>
																	<a class="btn btn-default" href="division/divisiones_con_carrera_por_escuela/<?php echo "$supervision->id"; ?>"><i class="fa fa-align-left"></i></a>
																<?php endif; ?>
															</td>
														</tr>
													<?php endforeach; ?>
												</table>
											</div>
										</div>
									</div><!-- Fin Alertas -->
									<?php if (!empty($modulos_inactivos)): ?>
										<?php foreach ($modulos_inactivos as $modulo): ?>
											<div class="col-xs-12">
												<?php echo $modulo; ?>
											</div>
										<?php endforeach; ?>
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