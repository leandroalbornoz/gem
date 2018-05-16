<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" />
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Escritorio Escuela
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
									<a class="btn btn-primary btn-xs" href="escuela/anuncios/<?php echo $this->usuario . "/" . $escuela->id; ?>">
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
				<?php for ($i = 1; $i < $cant_anuncios; $i++): ?>
					<?php if ((new DateTime($anuncios[$i]->fecha))->format('Ymd') >= (new DateTime('-1 month'))->format('Ymd')): ?>
						<div class="col-xs-12">
							<div class="box box-warning collapsed-box">
								<div class="box-header with-border">
									<h3 class="box-title"><b><?php echo (new DateTime($anuncios[$i]->fecha))->format('d/m/Y') . ' ' . $anuncios[$i]->titulo; ?></b></h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
									</div>
								</div>
								<div class="box-body">
									<?php echo $anuncios[$i]->texto; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
				<?php endfor; ?>
			<?php endif; ?>
			<?php if (!empty($liquidaciones)): ?>
				<div class="col-xs-6">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><label>Escuela</label> <?php echo "$escuela->nombre_largo" . (empty($escuela->cue) ? '' : " - <label>CUE:</label> $escuela->cue"); ?></h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12"><label>Nivel:</label> <?php echo $escuela->nivel; ?></div>
								<div class="col-sm-12"><label>Supervisión:</label> <?php echo $escuela->supervision; ?></div>
								<div class="col-sm-12"><label>Domicilo:</label> <?php echo "$escuela->calle $escuela->calle_numero $escuela->localidad"; ?></div>
								<div class="col-sm-6"><label>Telefono:</label> <?php echo $escuela->telefono; ?></div>
								<div class="col-sm-6"><label>Email:</label> <?php echo $escuela->email; ?></div>
							</div>
						</div>
						<?php if ($administrar && $this->rol->codigo != ROL_ESCUELA_ALUM): ?>
							<div class="box-footer">
								<a class="btn btn-primary" href="escuela/editar/<?php echo $escuela->id; ?>">
									<i class="fa fa-edit" id="btn-editar"></i> Editar
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="col-xs-6">
					<?php echo $liquidaciones; ?>
				</div>
			<?php else: ?>
				<div class="col-xs-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><label>Escuela</label> <?php echo "$escuela->nombre_largo" . (empty($escuela->cue) ? '' : " - <label>CUE:</label> $escuela->cue"); ?></h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-6"><label>Nivel:</label> <?php echo $escuela->nivel; ?></div>
								<div class="col-sm-6"><label>Supervisión:</label> <?php echo $escuela->supervision; ?></div>
								<div class="col-sm-12"><label>Domicilo:</label> <?php echo "$escuela->calle $escuela->calle_numero $escuela->localidad"; ?></div>
								<div class="col-sm-6"><label>Telefono:</label> <?php echo $escuela->telefono; ?></div>
								<div class="col-sm-6"><label>Email:</label> <?php echo $escuela->email; ?></div>
							</div>
						</div>
						<?php if ($administrar && $this->rol->codigo != ROL_ESCUELA_ALUM): ?>
							<div class="box-footer">
								<a class="btn btn-primary" href="escuela/editar/<?php echo $escuela->id; ?>">
									<i class="fa fa-edit" id="btn-editar"></i> Editar
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
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
					<?php if ($administrar && $this->rol->codigo != ROL_ESCUELA_ALUM): ?>
						<div class="box-footer">
							<a class="btn btn-primary" href="escuela/caracteristicas/<?php echo $escuela->id; ?>">
								<i class="fa fa-edit" id="btn-caracteristicas"></i> Editar características
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php if (isset($anexos) && !empty($anexos)): ?>
				<?php if ($escuela->anexo == 0): ?>
					<div class="col-xs-12">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Anexos</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								</div>
							</div>
							<div class="box-body ">
								<table class="table table-condensed table-bordered table-striped table-hover">
									<tr>
										<th colspan="9" style="text-align:center;">Lista de anexos</th>
									</tr>
									<tr>
										<th>Anexo</th>
										<th>CUE</th>
										<th>Escuela</th>
										<th>Nivel</th>
										<th>Juri/Repa</th>
										<th>Supervisión</th>
										<th>Regional</th>
										<th>Zona</th>
										<th style="width: 20px;"></th>
									</tr>
									<?php foreach ($anexos as $anexo): ?>
										<tr>
											<td><?= $anexo->anexo; ?></td>
											<td><?= $anexo->cue; ?></td>
											<td><?= "$anexo->numero - $anexo->nombre"; ?></td>
											<td><?= $anexo->nivel; ?></td>
											<td><?= $anexo->jurirepa; ?></td>
											<td><?= $anexo->supervision; ?></td>
											<td><?= $anexo->regional; ?></td>
											<td><?= $anexo->zona; ?></td>
											<td>
												<?php if ($administrar): ?>
													<a class="btn btn-xs" href="escuela/escritorio/<?php echo $anexo->escuela_id; ?>"><i class="fa fa-search"></i></a>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</table>
							</div>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<?php if (!empty($modulos)): ?>
				<?php foreach ($modulos as $modulo): ?>
					<div class="col-xs-12">
						<?php echo $modulo; ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
			<div class="col-xs-8">
				<div class="box box-primary"><!-- Divisiones -->
					<div class="box-header with-border">
						<h3 class="box-title">Cursos y Divisiones</h3>
						<div class="box-tools pull-right">
							<!--<a class="dropdown-item btn btn-xs" href="escuela/imprimir_estadisticas_cursos/<?= $escuela->id; ?>" title="Exportar PDF" target="_blank"><i class="fa fa-file-pdf-o text-red"></i></a>-->
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-xs-12">
								<?php if (!empty($divisiones)): ?>
									<table class="table table-bordered table-condensed table-striped table-hover">
										<thead>
											<tr>
												<th>División</th>
												<th>Turno</th>
												<th>Carrera</th>
												<th style="width: 60px;">Cargos</th>
												<th style="width: 60px;">Horas</th>
												<th style="width: 100px;">Alumnos</th>
												<th style="width: 68px;"></th>
											</tr>
										</thead>
										<tbody>
											<?php $total_cargos = 0; ?>
											<?php $total_horas = 0; ?>
											<?php foreach ($divisiones as $division): ?>
												<tr>
													<td><?php echo "$division->curso $division->division"; ?></td>
													<td><?php echo "$division->turno"; ?></td>
													<td class="text-sm"><?php echo "$division->carrera"; ?></td>
													<td class="text-center"><?php echo "$division->cargos"; ?></td>
													<td class="text-center"><?php echo "$division->horas"; ?></td>
													<td class="text-right">
														<?php foreach ($division->alumnos_cl as $cl => $alumnos): ?>
															<?php if ($alumnos > 0): ?>
																<?php $class = $cl == date('Y') ? 'bg-green' : '' ?>
																<?= "$alumnos<span style='margin-top: -2px; padding: 1px 7px; margin-left: 2px;' class='badge $class'>$cl</span>"; ?>
																<?php if ($administrar): ?>
																	<a class="btn btn-xs" href="division/alumnos/<?= "$division->id/$cl"; ?>" title="Ver alumnos C.L.<?= $cl; ?>"><i class="fa fa-users"></i></a>
																	<br>
																<?php endif; ?>
																<?php $alumnos_cl[$cl][] = $alumnos; ?>
															<?php endif; ?>
														<?php endforeach; ?>
													</td>
													<td>
														<?php if ($administrar): ?>
															<a class="btn btn-xs" href="division/ver/<?= $division->id; ?>" title="Ver división"><i class="fa fa-search"></i></a>
<!--															<a class="btn btn-xs" href="cursada/listar/<?= $division->id; ?>" title="Ver cursadas"><i class="fa fa-book"></i></a>-->
														<?php endif; ?>
													</td>
												</tr>
												<?php $total_cargos += $division->cargos; ?>
												<?php $total_horas += $division->horas; ?>
											<?php endforeach; ?>
										</tbody>
										<tr><th></th></tr>
										<tr>
											<th colspan="3">Totales generales para la escuela</th>
											<th style="text-align: center;"><?php echo $total_cargos; ?></th>
											<th style="text-align: center;"><?php echo $total_horas; ?></th>
											<th class="text-right">
												<?php if (!empty($alumnos_cl)): ?>
													<?php foreach ($alumnos_cl as $cl => $alumnos): ?>
														<?php echo array_sum($alumnos); ?><span style="margin-top: -2px; padding: 1px 7px; margin-left: 2px;" class="badge <?php echo ($cl == date('Y')) ? 'bg-green' : 'bg-default'; ?>"><?= $cl; ?></span><br>
													<?php endforeach; ?>
												<?php endif; ?>
											</th>
											<th></th>
										</tr>
									</table>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<?php if ($administrar): ?>
							<a class="btn btn-primary" href="division/listar/<?php echo $escuela->id; ?>">
								<i class="fa fa-cogs" id="btn-divisiones"></i> Administrar
							</a>
						<?php endif; ?>
						<a class="btn btn-success pull-right" href="escuela/alumnos_inasistencias/<?php echo $escuela->id; ?>">
							<i class="fa fa-clock-o" id="btn-carreras"></i> Resumen de asistencia de alumnos
						</a>
					</div>
				</div><!-- Fin Divisiones -->
				<div class="box box-primary"><!-- Cargos -->
					<div class="box-header with-border">
						<h3 class="box-title">Cargos</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-xs-12">
								<?php if (!empty($cargos)): ?>
									<table class="table table-bordered table-condensed table-striped table-hover">
										<tr>
											<th>Código</th>
											<th>Régimen</th>
											<th style="width: 60px;">Cargos</th>
											<th style="width: 60px;">Horas</th>
											<th style="width: 60px;">Servicios activos</th>
										</tr>
										<?php foreach ($cargos as $regimen): ?>
											<tr>
												<td><?php echo $regimen->codigo; ?></td>
												<td class="text-sm"><?php echo $regimen->descripcion; ?></td>
												<td class="text-center"><?php echo $regimen->cargos; ?></td>
												<td class="text-center"><?php echo $regimen->horas; ?></td>
												<td class="text-center"><?php echo $regimen->servicios; ?></td>
											</tr>
										<?php endforeach; ?>
									</table>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php if ($administrar && $this->rol->codigo != ROL_ESCUELA_ALUM): ?>
						<div class="box-footer">
							<a class="btn btn-primary" href="cargo/listar/<?php echo $escuela->id; ?>">
								<i class="fa fa-cogs"></i> Administrar
							</a>
						</div>
					<?php endif; ?>
				</div><!-- Fin Cargos -->
				<?php if ($this->rol->codigo != ROL_ESCUELA_CAR): ?>
					<div class="box box-primary"><!-- Usuarios -->
						<div class="box-header with-border">
							<h3 class="box-title">Usuarios</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-xs-12">
									<table class="table table-bordered table-condensed table-striped table-hover">
										<tr>
											<th style="width: 25%;">Usuario</th>
											<th style="width: 35%;">CUIL<br/>Persona</th>
											<th style="width: 39%;">Rol<br/>Entidad</th>
											<th style="width: 1%;"></th>
										</tr>
										<?php if (!empty($usuarios)): ?>
											<?php foreach ($usuarios as $usuario): ?>
												<tr>
													<td><?php echo $usuario->usuario; ?></td>
													<td><?php echo "$usuario->cuil<br/>$usuario->persona"; ?></td>
													<td><?php echo "$usuario->rol<br/>$usuario->entidad"; ?></td>
													<td>
														<?php if ($administrar): ?>
															<a class="btn btn-xs" href="usuario/ver/<?php echo $usuario->id; ?>"><i class="fa fa-search"></i></a>
														<?php endif; ?>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td colspan="4" style="text-align: center;"> -- Sin usuarios -- </td>
											</tr>
										<?php endif; ?>
									</table>
								</div>
							</div>
						</div>
						<?php if ($administrar && ($this->rol->codigo != ROL_ESCUELA_ALUM)): ?>
							<div class="box-footer">
								<a class="btn btn-primary" href="usuario/listar/<?php echo $escuela->id; ?>">
									<i class="fa fa-cogs"></i> Administrar
								</a>
							</div>
						<?php endif; ?>
					</div><!-- Fin Usuarios -->
				<?php endif; ?>
			</div>
			<div class="col-xs-4">
				<div class="box box-primary"><!-- Carreras -->
					<div class="box-header with-border">
						<h3 class="box-title">Carreras</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-xs-12">
								<?php if (!empty($carreras)): ?>
									<table class="table table-bordered table-condensed table-striped table-hover">
										<tr>
											<th>Carrera</th>
											<th style="width: 80px;">Divisiones</th>
											<th style="width: 80px;">Alumnos</th>
											<th style="width: 34px;"></th>
										</tr>
										<?php foreach ($carreras as $carrera): ?>
											<tr>
												<td><?php echo $carrera->carrera; ?></td>
												<td class="text-center"><?php echo $carrera->divisiones; ?></td>
												<td style="text-align: center;"><?php echo $carrera->alumnos; ?></td>
												<td>
													<?php if ($administrar): ?>
														<a class="btn btn-xs" href="carrera/ver/<?php echo "$carrera->carrera_id/$carrera->id"; ?>"><i class="fa fa-search"></i></a>
													<?php endif; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</table>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php if ($administrar): ?>
						<div class="box-footer">
							<a class="btn btn-primary" href="escuela_carrera/listar/<?php echo $escuela->id; ?>">
								<i class="fa fa-cogs" id="btn-carreras"></i> Administrar
							</a>
						</div>
					<?php endif; ?>
				</div><!-- Fin Carreras -->
				<div class="box box-primary"><!-- Alertas -->
					<div class="box-header with-border">
						<h3 class="box-title">Alertas</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<?php foreach ($indices as $indice_titulo => $indice): ?>
							<?php if ($indice_titulo != 'alertas'): ?>
								<div class="progress-group">
									<span class="progress-text"><?php echo $indice_titulo; ?></span>
									<span class="progress-number"><b><?php echo ($indice->cantidad); ?></b>/<?php echo $indice->total; ?></span>
									<div class="progress sm">
										<?php $cumplimiento = $indice->total == 0 ? 0 : (round($indice->cantidad / $indice->total * 100)); ?>
										<div class="progress-bar progress-bar-<?php echo $cumplimiento >= 100 ? 'green' : ($cumplimiento >= 50 ? 'yellow' : 'red'); ?>" style="line-height:10px;font-size:10px; min-width: 2em; width: <?php echo $cumplimiento; ?>%"><?php echo $cumplimiento; ?>%</div>
									</div>
								</div>
							<?php endif; ?>
							<!-- /.progress-group -->
						<?php endforeach; ?>
					</div>
					<div class="box-body">
						<?php foreach ($indices as $indice_titulo => $indice): ?>
							<?php if ($indice_titulo === 'alertas'): ?>
								<?php foreach ($indice as $indice): ?>
									<div class="progress-group">
										<table class="table table-bordered table-condensed table-striped table-hover">
											<tbody>
												<tr>
													<td class="progress-text"><?php echo $indice->label; ?></td>
													<td class="pull-right"><b><?php echo ($indice->value); ?></b></td>
													<td style="width:1px;">
														<?php if ($this->rol->codigo === ROL_ESCUELA_ALUM && $indice->modulo === 'cargos'): ?>
															<a class="btn btn-default disabled" href="">
																<i class="fa fa-align-left"></i>
															</a>
														<?php elseif ($this->rol->codigo === ROL_ESCUELA_CAR && $indice->modulo === 'alumnos'): ?>
															<a class="btn btn-default disabled" href="">
																<i class="fa fa-align-left"></i>
															</a>
														<?php else: ?>
															<a class="btn btn-default" href="<?= $indice->url; ?>">
																<i class="fa fa-align-left"></i>
															</a>
														<?php endif; ?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<!-- /.progress-group -->
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endforeach; ?>
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
	</section>
</div>
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
