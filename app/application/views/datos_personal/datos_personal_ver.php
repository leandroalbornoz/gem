<style>
	td.child>ul{
		width:100%;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Datos de personal
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="datos_personal/listar/<?php echo $escuela->id; ?>">Datos personal</a></li>
			<li class="active"><?php echo ucfirst($metodo); ?></li>
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
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-body">
						<?php if (!isset($es_funcion) || $es_funcion === '0'): ?>
							<a class="btn btn-app btn-app-zetta btn-app-zetta-active active" href="datos_personal/ver/<?php echo $servicio->id; ?>">
								<i class="fa fa-search"></i> Ver
							</a>
							<?php if ($this->rol->codigo === ROL_ADMIN || $this->rol->codigo === ROL_USI): ?>
								<a class="btn btn-app btn-app-zetta" href="datos_personal/editar/<?php echo $servicio->id; ?>">
									<i class="fa fa-edit"></i> Editar
								</a>
							<?php endif; ?>
							<a class="btn btn-app btn-app-zetta" href="datos_personal/antiguedad/<?php echo $servicio->id; ?>">
								<i class="fa fa-archive"></i> Antigüedad
							</a>

						<?php endif; ?>
						<div class="row">
							<div class="col-md-12">
								<h4><?php echo "$persona->apellido, $persona->nombre ($persona->documento_tipo $persona->documento - CUIL: $persona->cuil)" . (($this->rol->codigo === ROL_ADMIN || $this->rol->codigo === ROL_USI) ? '<a href="persona/ver/' . $persona->id . '" title="Ir a administración de persona"><i class="fa fa-search"></i></a>' : ''); ?></h4>
							</div>
						</div>
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation"><a href="#tab_personales" aria-controls="tab_personales" role="tab" data-toggle="tab">Datos Personales</a></li>
							<li role="presentation"><a href="#tab_domicilio" aria-controls="tab_domicilio" role="tab" data-toggle="tab">Datos Domicilio</a></li>
							<li role="presentation"><a href="#tab_familiares" aria-controls="tab_familiares" role="tab" data-toggle="tab">Datos Familiares</a></li>
							<li role="presentation" class="active"><a href="#tab_servicios" aria-controls="tab_servicios" role="tab" data-toggle="tab">Servicios</a></li>
							<li role="presentation"><a href="#tab_trayectoria" aria-controls="tab_trayectoria" role="tab" data-toggle="tab">Trayectoria de alumno</a></li>
							<?php if (ENVIRONMENT !== 'production'): ?>
								<li role="presentation"><a href="#tab_reporte_horario" aria-controls="tab_reporte_horario" role="tab" data-toggle="tab">Reporte horario</a></li>
							<?php endif; ?>
						</ul>
						<div class="tab-content">
							<div id="tab_personales" role="tabpanel" class="tab-pane">
								<!--<div id="tab_personales" role="tabpanel" class="tab-pane active">-->
								<div class="row">
									<div class="form-group col-md-3">
										<?php echo $fields['sexo']['label']; ?>
										<?php echo $fields['sexo']['form']; ?>
									</div>
									<div class="form-group col-md-3">
										<?php echo $fields['fecha_nacimiento']['label']; ?>
										<?php echo $fields['fecha_nacimiento']['form']; ?>
									</div>
									<div class="form-group col-md-3">
										<?php echo $fields['nacionalidad']['label']; ?>
										<?php echo $fields['nacionalidad']['form']; ?>
									</div>
									<div class="form-group col-md-3">
										<?php echo $fields['estado_civil']['label']; ?>
										<?php echo $fields['estado_civil']['form']; ?>
									</div>
									<div class="form-group col-md-3">
										<?php echo $fields['nivel_estudio']['label']; ?>
										<?php echo $fields['nivel_estudio']['form']; ?>
									</div>
									<div class="form-group col-md-3">
										<?php echo $fields['ocupacion']['label']; ?>
										<?php echo $fields['ocupacion']['form']; ?>
									</div>
									<div class="form-group col-md-3">
										<?php echo $fields['grupo_sanguineo']['label']; ?>
										<?php echo $fields['grupo_sanguineo']['form']; ?>
									</div>
									<div class="form-group col-md-12">
										<?php echo $fields['obra_social']['label']; ?>
										<?php echo $fields['obra_social']['form']; ?>
									</div>
									<div class="form-group col-md-3">
										<?php echo $fields['depto_nacimiento']['label']; ?>
										<?php echo $fields['depto_nacimiento']['form']; ?>
									</div>
									<div class="form-group col-md-3">
										<?php echo $fields['fecha_defuncion']['label']; ?>
										<?php echo $fields['fecha_defuncion']['form']; ?>
									</div>
									<div class="form-group col-md-6">
										<?php echo $fields['lugar_traslado_emergencia']['label']; ?>
										<?php echo $fields['lugar_traslado_emergencia']['form']; ?>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab_domicilio">
								<div class="row">
									<div class="form-group col-md-4 col-sm-4">
										<?php echo $fields['calle']['label']; ?>
										<?php echo $fields['calle']['form']; ?>
									</div>
									<div class="form-group col-md-2 col-sm-4">
										<?php echo $fields['calle_numero']['label']; ?>
										<?php echo $fields['calle_numero']['form']; ?>
									</div>
									<div class="form-group col-md-4 col-sm-4">
										<?php echo $fields['localidad']['label']; ?>
										<?php echo $fields['localidad']['form']; ?>
									</div>
									<div class="form-group col-md-2 col-sm-4">
										<?php echo $fields['codigo_postal']['label']; ?>
										<?php echo $fields['codigo_postal']['form']; ?>
									</div>
									<div class="form-group col-md-5 col-sm-4">
										<?php echo $fields['barrio']['label']; ?>
										<?php echo $fields['barrio']['form']; ?>
									</div>
									<div class="form-group col-md-1 col-sm-4">
										<?php echo $fields['manzana']['label']; ?>
										<?php echo $fields['manzana']['form']; ?>
									</div>
									<div class="form-group col-md-1 col-sm-4">
										<?php echo $fields['casa']['label']; ?>
										<?php echo $fields['casa']['form']; ?>
									</div>
									<div class="form-group col-md-1 col-sm-4">
										<?php echo $fields['departamento']['label']; ?>
										<?php echo $fields['departamento']['form']; ?>
									</div>
									<div class="form-group col-md-1 col-sm-4">
										<?php echo $fields['piso']['label']; ?>
										<?php echo $fields['piso']['form']; ?>
									</div>
									<div class="form-group col-md-3 col-sm-4">
										<?php echo $fields['nacionalidad']['label']; ?>
										<?php echo $fields['nacionalidad']['form']; ?>
									</div>
									<div class="form-group col-md-2 col-sm-4">
										<?php echo $fields['telefono_fijo']['label']; ?>
										<?php echo $fields['telefono_fijo']['form']; ?>
									</div>
									<div class="form-group col-md-3 col-sm-4">
										<?php echo $fields['telefono_movil']['label']; ?>
										<?php echo $fields['telefono_movil']['form']; ?>
									</div>
									<div class="form-group col-md-3 col-sm-4">
										<?php echo $fields['prestadora']['label']; ?>
										<?php echo $fields['prestadora']['form']; ?>
									</div>
									<div class="form-group col-md-4 col-sm-4">
										<?php echo $fields['email']['label']; ?>
										<?php echo $fields['email']['form']; ?>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab_familiares">
								<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
									<thead>
										<tr style="background-color: #f4f4f4" >
											<th style="text-align: center;" colspan="11">
												Familiares
											</th>
										</tr>
										<tr>
											<th>Parentesco</th>
											<th>Documento</th>
											<th>Apellido</th>
											<th>Nombre</th>
											<th>Nivel de estudios</th>
											<th>Ocupacion</th>
											<th>Celular</th>
											<th>Email</th>
											<th>Convive</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php $madre = FALSE; ?>
										<?php $padre = FALSE; ?>
										<?php if (!empty($parientes)): ?>
											<?php foreach ($parientes as $pariente): ?>
												<?php $madre |= $pariente->parentesco === 'Madre'; ?>
												<?php $padre |= $pariente->parentesco === 'Padre'; ?>
												<tr>
													<td><?= $pariente->parentesco; ?></td>
													<td><?= $pariente->documento; ?></td>
													<td><?= $pariente->apellido; ?></td>
													<td><?= $pariente->nombre; ?></td>
													<td><?= $pariente->nivel_estudio; ?></td>
													<td><?= $pariente->ocupacion; ?></td>
													<td><?= $pariente->telefono_movil; ?></td>
													<td><?= $pariente->email; ?></td>
													<td><?php
														if ($pariente->convive == 1) {
															echo 'SI';
														} else {
															echo 'No';
														}
														?></td>
		<!--													<td width="60">
													<?php if ($txt_btn === 'Editar'): ?>
																																						<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="familia/modal_editar_familiar_persona/<?php echo $persona->id; ?>/<?= $pariente->id ?>"><i class="fa fa-edit"></i></a>
																																						<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="familia/modal_eliminar_familiar_persona/<?php echo $persona->id; ?>/<?= $pariente->id ?>"><i class="fa fa-remove"></i></a>
													<?php endif; ?>
													</td>-->
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr>
												<td style="text-align: center;" colspan="11">
													-- No tiene --
												</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
								<div class="row">
									<?php if (!$padre && (!empty($alumno->documento_padre) || !empty($alumno->nombre_padre))): ?>
										<div class="col-sm-6">
											<label>Padre (SIGA)</label>
											<input class="form-control" readonly value="<?= "$alumno->documento_padre $alumno->nombre_padre"; ?>">
										</div>
									<?php endif; ?>
									<?php if (!$madre && (!empty($alumno->documento_madre) || !empty($alumno->nombre_madre))): ?>
										<div class="col-sm-6">
											<label>Madre (SIGA)</label>
											<input class="form-control" readonly value="<?= "$alumno->documento_madre $alumno->nombre_madre"; ?>">
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane active" id="tab_servicios">
								<?php if (!empty($servicios)): ?>
									<table class="table table-hover table-bordered table-condensed table-responsive dt-responsive dataTable no-footer dtr-inline text-sm" role="grid" id="servicio_table" style="width:100% !important">
										<thead>
											<tr style="background-color: #f4f4f4" >
												<th style="text-align: center;" colspan="10">Servicios</th>
											</tr>
											<tr>
												<th></th>
												<th>Liquidación</th>
												<th>S.R.</th>
												<th>Escuela/Area</th>
												<th>División</th>
												<th>Régimen/Materia</th>
												<th>Hs. Cát.</th>
												<th>Fecha alta</th>
												<th>Fecha baja</th>
												<th></th>
												<th class="none">Observaciones</th>
												<th class="none">F.Detalle</th>
												<th class="none">F.Destino</th>
												<th class="none">F.Norma</th>
												<th class="none">F.Tarea</th>
												<th class="none">F.Hs.</th>
												<th class="none">F.Desde</th>
												<th class="none">Motivo Baja</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($servicios as $servicio): ?>
												<tr class="<?= empty($servicio->fecha_baja) ? '' : 'bg-gray'; ?>">
													<td></td>
													<td><?= $servicio->liquidacion . (empty($servicio->tbcabh_id) ? '<i class="fa fa-times text-red"></i>' : '<i class="fa fa-check text-green"></i>'); ?></td>
													<td><?= $servicio->situacion_revista . ($servicio->condicion_cargo === 'Normal' ? '' : "<br>$servicio->condicion_cargo"); ?></td>
													<td><?php echo empty($servicio->area) ? $servicio->escuela : $servicio->area; ?></td>
													<td class="dt-body-center"><?= $servicio->division; ?></td>
													<td><?= "$servicio->regimen_codigo-$servicio->regimen<br/>$servicio->materia"; ?></td>
													<td class="dt-body-center"><?= $servicio->carga_horaria; ?></td>
													<td><?= empty($servicio->fecha_alta) ? '' : (new DateTime($servicio->fecha_alta))->format('d/m/Y'); ?></td>
													<td><?= empty($servicio->fecha_baja) ? '' : (new DateTime($servicio->fecha_baja))->format('d/m/Y'); ?></td>
													<td>
														<a class="pull-right btn btn-xs btn-default" href="servicio/ver/<?= $servicio->id; ?>"><i class="fa fa-search"></i></a>
													</td>
													<td><?= $servicio->observaciones; ?></td>
													<td><?= $servicio->f_detalle; ?></td>
													<td><?= $servicio->f_destino; ?></td>
													<td><?= $servicio->f_norma; ?></td>
													<td><?= $servicio->f_tarea; ?></td>
													<td><?= $servicio->f_carga_horaria; ?></td>
													<td><?= empty($servicio->f_fecha_desde) ? '' : (new DateTime($servicio->f_fecha_desde))->format('d/m/Y'); ?></td>
													<td><?= $servicio->motivo_baja; ?></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								<?php else: ?>
									<div style="text-align: center;" > -- Sin servicios -- </div>
								<?php endif; ?>
								<?php if (!empty($liquidaciones)): ?>
									<table class="table table-hover table-bordered table-condensed table-responsive dt-responsive dataTable no-footer dtr-inline text-sm" role="grid" id="liquidacion_table" style="width:100% !important">
										<thead>
											<tr style="background-color: #f4f4f4" >
												<th style="text-align: center;" colspan="12">Liquidaciones</th>
											</tr>
											<tr>
												<th>Servicio</th>
												<th>Cobra<br/>sueldo</th>
												<th>Año-Mes<br/>Periodo</th>
												<th>N°Liquidación</th>
												<th>S.R.</th>
												<th>Repartición<br/>Escuela</th>
												<th>Régimen</th>
												<th>Clase</th>
												<th>Hs/Días</th>
												<th>Fecha alta<br/>(Mes-Año)</th>
												<th>Fecha baja<br/>(Mes-Año)</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($liquidaciones as $liquidacion): ?>
												<tr class="<?= $liquidacion->vigente === AMES_LIQUIDACION ? '' : 'bg-gray'; ?>">
													<td><?= empty($liquidacion->servicio_id) ? '<i class="fa fa-times text-red"></i>' : form_hidden('servicio', $liquidacion->servicio_id) . '<i class="fa fa-check text-green"></i>'; ?></td>
													<td><?= $liquidacion->SINSUELDO === '1' ? '<i class="fa fa-times text-red"></i>' : '<i class="fa fa-check text-green"></i>'; ?></td>
													<td><?= $liquidacion->vigente . '<br/>' . $liquidacion->periodo; ?></td>
													<td><?= $liquidacion->liquidacion_s; ?></td>
													<td><?= $liquidacion->REVISTA; ?></td>
													<td><?= "$liquidacion->juri/$liquidacion->repa<br/>" . (empty($liquidacion->guiescid) ? $liquidacion->reparticion : "Esc. $liquidacion->guiescid"); ?></td>
													<td><?= "$liquidacion->regimen<br/>$liquidacion->RegSalDes"; ?></td>
													<td><?= str_pad($liquidacion->diasoblig, 4, '0', STR_PAD_LEFT); ?></td>
													<td><?= $liquidacion->diashorapag; ?></td>
													<td><?= substr($liquidacion->fechaini, 0, 2) . '-' . substr($liquidacion->fechaini, 2); ?></td>
													<td><?= substr($liquidacion->fechafin, 0, 2) . '-' . substr($liquidacion->fechafin, 2); ?></td>
													<td>
														<a href="tbcabh/modal_ver/<?= "$liquidacion->id/$liquidacion->vigente"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								<?php else: ?>
									<div style="text-align: center;" > -- Sin liquidaciones -- </div>
								<?php endif; ?>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab_trayectoria">
								<?php if (!empty($alumno[0]->division_id)): ?>
									<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" id="alumno_table" role="grid">
										<thead>
											<tr style="background-color: #f4f4f4" >
												<th style="text-align: center;" colspan="5">Trayectoria alumno</th>
											</tr>
											<tr>
												<th>Ciclo lectivo</th>
												<th>Escuela</th>
												<th>Division</th>
												<th>Fecha ingreso</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($alumno as $campo): ?>
												<tr>
													<td><?= $campo->ciclo_lectivo ?></td>
													<td><?= $campo->escuela ?></td>
													<td><?= $campo->division2 ?></td>
													<td><?= empty($campo->fecha_desde) ? '' : (new DateTime($campo->fecha_desde))->format('d/m/Y'); ?></td>
													<td>
														<a class="pull-right btn btn-xs btn-default" href="alumno/ver/<?= $campo->division_id ?>"><i class="fa fa-search"></i></a>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								<?php else: ?>
									<div style="text-align: center;" > -- Sin trayectoria de alumno -- </div>
								<?php endif; ?>
							</div>
							<?php if (ENVIRONMENT !== 'production'): ?>
								<div role="tabpanel" class="tab-pane" id="tab_reporte_horario">
									<?php if (!empty($horarios_servicios)): ?>
										<table class="table table-hover table-bordered table-condensed table-responsive dt-responsive dataTable no-footer dtr-inline text-sm" role="grid" id="reporte_horario_table" style="width:100% !important">
											<thead>
												<tr style="background-color: #f4f4f4" >
													<th style="text-align: center;" colspan="12">Reporte Horario</th>
												</tr>
												<tr>
													<th>Escuela/Area</th>
													<th>División</th>
													<th>Régimen/Materia</th>
													<th>Carga Horaria</th>
													<th style="text-align: center;" colspan="7">Horario</th>
													<th></th>
												</tr>
												<tr>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th>Lunes</th>
													<th>Martes</th>
													<th>Miercoles</th>
													<th>Jueves</th>
													<th>Viernes</th>
													<th>Sabado</th>
													<th>Domingo</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($horarios_servicios as $horario_servicio): ?>
													<tr>
														<td>
															<?php echo empty($horario_servicio->area) ? $servicio->escuela : $horario_servicio->area; ?>
														</td>
														<td class="dt-body-center">
															<?= empty($horario_servicio->division) ? "-" : $horario_servicio->division ?>
														</td>
														<td>
															<?= "$horario_servicio->regimen_codigo-$horario_servicio->regimen_descripcion<br>$horario_servicio->materia" ?>
														</td>
														<td>
															<div style="white-space: nowrap; max-width:110px; overflow:hidden; text-overflow:ellipsis;">
																<?= $horario_servicio->regimen_tipo_id === '2' ? "$horario_servicio->carga_horaria Hs <br>" : ''; ?>
																<?= $horario_servicio->sf_detalle; ?>
															</div>
														</td>
														<?php for ($dia = 1; $dia <= 7; $dia++): ?>
															<td class="dt-body-center">
																<?php if (isset($horario_servicio->dias[$dia])): ?>
																	<?php foreach ($horario_servicio->dias[$dia]->horarios as $horario): ?>
																		<?php echo substr($horario->hora_desde, 0, 5) . '-' . substr($horario->hora_hasta, 0, 5); ?>
																		<br/>
																	<?php endforeach; ?>
																<?php endif; ?>
															</td>
														<?php endfor; ?>
														<td><a class="pull-right btn btn-xs btn-default" href="servicio/ver/<?= $horario_servicio->servicio_id; ?>"><i class="fa fa-search"></i></a></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									<?php else: ?>
										<div style="text-align: center;" > -- Sin reporte horario -- </div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="datos_personal/listar/<?php echo $escuela->id; ?>" title="Volver">Volver</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		$('#servicio_table,#antiguedad_table,#alumno_table,#reporte_horario_table').DataTable({
			dom: 't',
			autoWidth: false,
			paging: false,
			ordering: false
		});
	});
</script>