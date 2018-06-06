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
			<label>Dirección</label> SEOS
		</h1>
		<ol class="breadcrumb">
			<li class="active"><i class="fa fa-home"></i> Inicio</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<?php if (!empty($niveles)): ?>
					<ul class="nav nav-tabs" role="tablist">
						<?php $tab_class = 'active'; ?>
						<?php foreach ($niveles as $nivel): ?>
							<?php if (!empty($nivel->escuelas)): ?>
								<li role="presentation" class="<?= $tab_class; ?>"><a href="#nivel_<?= $nivel->id; ?>" aria-controls="<?= strtolower(str_replace(' ', '_', $nivel->descripcion)); ?>" role="tab" data-toggle="tab">Nivel <?= $nivel->descripcion; ?></a></li>
								<?php $tab_class = ''; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
					<div class="tab-content">
						<?php $tab_class = 'active'; ?>
						<?php foreach ($niveles as $nivel): ?>
							<?php if (!empty($nivel->escuelas)): ?>
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
																			<a href="#" class="list-group-item"><?php echo $caracteristica->descripcion; ?></a>
																		<?php endforeach; ?>
																	</div>
																</div>
															<?php endforeach; ?>
														</div>
													<?php endif; ?>
												</div>
												<div class="box-footer">
													<a class="btn btn-primary" href="caracteristica_nivel/listar/<?php echo $nivel->id; ?>">
														<i class="fa fa-edit" id="btn-caracteristicasr"></i> Editar características
													</a>
												</div>
											</div>
										</div>
										<div class="col-xs-6"><!-- Escuelas -->
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
																		<th style="width: 34px;"></th>
																	</tr>
																	<?php foreach ($nivel->escuelas as $escuela): ?>
																		<tr>
																			<td><?php echo "$escuela->nombre_corto"; ?></td>
																			<td><?php echo "$escuela->nombre"; ?></td>
																			<td class="text-center"><?php echo "$escuela->cargos"; ?></td>
																			<td><a class="btn btn-xs" href="escuela/ver/<?php echo $escuela->id; ?>"><i class="fa fa-search"></i></a></td>
																		</tr>
																	<?php endforeach; ?>
																</table>
															<?php endif; ?>
														</div>
													</div>
												</div>
												<div class="box-footer">
													<a class="btn btn-primary" href="escuela/listar">
														<i class="fa fa-cogs" id="btn-escuelas"></i> Administrar
													</a>
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
																	</tr>
																	<?php foreach ($nivel->carreras as $carrera): ?>
																		<tr>
																			<td><?php echo $carrera->descripcion; ?></td>
																			<td class="text-center"><?php echo $carrera->escuelas; ?></td>
																			<td><a class="btn btn-xs" href="carrera/ver/<?php echo $carrera->id; ?>"><i class="fa fa-search"></i></a></td>
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
															<span class="progress-text"><?php echo $indice_titulo; ?></span>
															<span class="progress-number"><b><?php echo ($indice->cantidad); ?></b>/<?php echo $indice->total; ?></span>
															<div class="progress sm">
																<?php $cumplimiento = $indice->total == 0 ? 0 : (round($indice->cantidad / $indice->total * 100)); ?>
																<div class="progress-bar progress-bar-<?php echo $cumplimiento >= 100 ? 'green' : ($cumplimiento >= 50 ? 'yellow' : 'red'); ?>" style="line-height:10px;font-size:10px; min-width: 2em; width: <?php echo $cumplimiento; ?>%"><?php echo $cumplimiento; ?>%</div>
															</div>
														</div>
														<!-- /.progress-group -->
													<?php endforeach; ?>
												</div>
											</div>
										</div><!-- Fin Alertas -->
									</div>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
</div>