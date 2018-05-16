<style>
	.table>tbody+tbody {
    border-top: 1px solid #ddd;
	}
	.dia_0,.dia_6{
		background-color: lightgray;
	}
	.mes_asistencia{
		width: 50px;
		height: 50px;
		border: darkgray thin solid;
		border-radius: 5px;
		margin-right: 5px;
		text-align: center;
		font-weight: bold;
	}
	.mes_asistencia.active{
		background-color: #00c0ef;
		border-color: #0044cc;
	}
	.info-box-icon{
		float: left;
    height: 40px;
    width: 40px;
    text-align: center;
    font-size: 32px;
    line-height: 40px;
	}
	.badge{
		width:40px;
	}
	.cchild {
		display: none;
	}
	.open>.cchild {
		display: table-row;
	}
	.parent {
		cursor: pointer;
	}
	.parent > *:last-child {
		width: 30px;
	}
	.parent i {
		transform: rotate(0deg);
		transition: transform .3s cubic-bezier(.4,0,.2,1);
		margin: -.5rem;
		padding: .5rem;
	}
	.open>.parent i {
		transform: rotate(180deg);
	}
	.open>.parent>td>span>.fa-minus{
		display: inline !important;
	}
	.open>.parent>td>span>.fa-plus{
		display: none;
	}
	.nav-tabs-custom>.nav-tabs {
    border-bottom-color: #3c8dbc;
	}
	.nav-tabs-custom>.nav-tabs>li {
    border: 3px solid transparent;
		border-top-color: #3c8dbcb8;
    border-right-color: #3c8dbcb8;
    border-left-color: #3c8dbcb8;
    border-radius: 4px;
	}
	.nav-tabs-custom>.nav-tabs>li>a>.fa{
		color: #3c8dbcb8 !important;
	}
	.nav-tabs-custom>.nav-tabs>li.active {
    border-top-color: #3c8dbc;
    border-right-color: #3c8dbc;
    border-left-color: #3c8dbc;
    border-bottom-color: #fff;
    border-radius: 4px;
	}
	.nav-tabs-custom>.nav-tabs>li>a {
    color: #444444b5;
	}
	.nav-tabs-custom>.nav-tabs>li.active>a>.fa {
		color: #3c8dbc !important;
	}
	.text-muted span{
		color: #f39c12 !important;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<b>Escritorio Portal </b>
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
			<div class="col-md-3">
				<div class="box box-primary">
					<div class="box-body box-profile">
						<img class="profile-user-img img-responsive img-circle" src="img/generales/usuario.png" alt="User profile picture">
						<h3 class="profile-username text-center"><strong><?= "$persona->apellido, $persona->nombre."; ?></strong></h3>
						<div class="box-body" style="font-size: 14px;">
							<strong><i class="fa fa-user"></i> Datos Personales</strong>
							<p class="text-muted">
								<?= (!empty($persona->cuil)) ? "Cuil: $persona->cuil" : "<br>Cuil: <span>No cargado</span>"; ?>
								<?= (!empty($persona->documento_tipo) || !empty($persona->documento)) ? "<br>Documento: $persona->documento_tipo $persona->documento" : "<br>Documento: <span>No cargado</span>"; ?>
								<?= (!empty($persona->fecha_nacimiento)) ? "<br>Fecha de nacimiento: " . (new DateTime($persona->fecha_nacimiento))->format('d/m/Y') : "<br>Fecha de nacimiento: <span>No cargado</span>"; ?>
								<?= (!empty($persona->sexo)) ? "<br>Sexo: $persona->sexo" : "<br>Sexo: <span>No cargado</span>"; ?>
								<?= (!empty($persona->nacionalidad)) ? "<br>Nacionalidad: $persona->nacionalidad" : "<br>Nacionalidad: <span>No cargado</span>"; ?>
							<hr>	
							<strong><i class="fa fa-map-marker margin-r-5"></i> Datos domicilio</strong>
							<p class="text-muted">

								<?php if (isset($persona->calle) && !empty($persona->calle)): ?>
									<?= (!empty($persona->calle)) ? "Calle: $persona->calle" : "Calle: <span>No cargado</span>" ?>
									<?= (!empty($persona->calle_numero)) ? "<br>Número: $persona->calle_numero" : "<br>Número: <span>No cargado</span>"; ?>
								<?php endif; ?>
								<?php if (isset($persona->barrio) && !empty($persona->barrio)): ?>
									<?= (!empty($persona->calle) ? "<br>" : "" ); ?>
									<?= (!empty($persona->barrio)) ? "Barrio: $persona->barrio" : "<br>Barrio: <span>No cargado</span>"; ?>
									<?= (!empty($persona->manzana)) ? "<br>M: $persona->manzana" : "<br>M: <span>No cargado</span>"; ?> 
									<?=
									(!empty($persona->casa)) ? " C: $persona->casa" : " C: <span>No cargado</span>";
								endif;
								?>

								<?php if (isset($persona->departamento) && !empty($persona->departamento)) : ?>
									<?= (!empty($persona->departamento)) ? "<br>Depto: $persona->departamento" : "<br>Depto: <span>No cargado</span>"; ?><?= (!empty($persona->piso)) ? " Piso: $persona->piso" : " Piso: <span>No cargado</span>";
							endif;
								?>
<?= (!empty($persona->localidad)) ? "<br>Localidad: $persona->localidad" : "<br>Localidad: <span>No cargado</span>"; ?>
							</p>
							<hr>
							<strong><i class="fa fa-phone"></i> Datos contacto</strong>
							<p class="text-muted">
<?= (!empty($persona->telefono_fijo)) ? "Teléfono Fijo: $persona->telefono_fijo" : "Teléfono Fijo: <span>No cargado</span>"; ?>
<?= (!empty($persona->telefono_movil)) ? "<br>Celular: $persona->telefono_movil" : "<br>Celular: <span>No cargado</span>"; ?>
							</p>
							<hr>
						</div>
					</div>
				</div>
			</div>
<?php if (!empty($trayectoria_alumno)): ?>
				<div class="col-md-9">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"  style="font-weight: bold;">Trayectoria personal</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body ">
							<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
								<thead>
									<tr>
										<th>C.L.</th>
										<th>Condición</th>
										<th>Escuela</th>
										<th>División</th>
										<!--<th>Legajo</th>-->
										<th colspan="2">Ingreso</th>
										<th colspan="2">Egreso</th>
										<th>Estado</th>
										<th>Inasistencias</th>
									</tr>
								</thead>
	<?php if (!empty($trayectoria_alumno)): ?>
		<?php foreach ($trayectoria_alumno as $division): ?>
										<tbody>
											<tr class="parent">
												<td><?= $division->ciclo_lectivo; ?></td>
												<td><?= $division->condicion; ?></td>
												<td><?= "$division->nombre_escuela"; ?></td>
												<td><?= "$division->curso $division->division" . ($division->grado_multiple === 'Si' ? " ($division->curso_grado_multiple)" : ''); ?></td>
												<!--<td><?= $division->legajo; ?></td>-->
												<td><?= empty($division->fecha_desde) ? '' : (new DateTime($division->fecha_desde))->format('d/m/y'); ?></td>
												<td class="text-sm"><?= $division->causa_entrada; ?></td>
												<td><?= empty($division->fecha_hasta) ? '' : (new DateTime($division->fecha_hasta))->format('d/m/y'); ?></td>
												<td class="text-sm"><?= $division->causa_salida; ?></td>
												<td><?= $division->estado; ?></td>
												<td class="text-center">
													<span class="label bg-blue" style="font-size: 100%;">
														<i style="margin-top: -2px; margin-bottom: 2px;" class="fa fa-plus"></i>
														<i style="display:none;" class="fa fa-minus"></i>
													</span>
												</td>
											</tr>
											<tr class="cchild">
												<td colspan="10">
													<div class="row">
															<?php foreach ($periodos_alumno[$division->id] as $periodo): ?>
															<div class="col-md-12 col-lg-6">
																<?php $fecha_ini = new DateTime(substr($periodo->inicio, 0, 8) . '01'); ?>
																<?php $fecha_fin = new DateTime($periodo->fin); ?>
				<?php $dia = DateInterval::createFromDateString('1 month'); ?>
				<?php $fechas = new DatePeriod($fecha_ini, $dia, $fecha_fin); ?>
																<table class="table table-hover table-bordered table-condensed table-striped" role="grid">
																	<thead>
																		<tr style="background-color:#337ab7; color:white;">
																			<th style="width: 340px;" colspan="13" class="text-center"><?= "{$periodo->periodo}° $periodo->nombre_periodo"; ?> - <?= (new DateTime($periodo->inicio))->format('d/m/Y') . ' al ' . $fecha_fin->format('d/m/Y'); ?>
																			</th>
																		</tr>
																		<tr>
																			<th style="width: 15px;" rowspan="2">Mes</th>
																			<th style="text-align: center; width: 50px;" colspan="2">Resumen Mensual</th>
																			<th style="width: 50px;" rowspan="2">Total del mes</th>
																			<th style="width: 50px;" rowspan="2">Asistencia</th>
																			<th style="width: 50px;" rowspan="2">% de Asistencia</th>
																			<th style="width: 30px; text-align: center" rowspan="2"></th>
																		</tr>
																		<tr>
																			<th style="text-align: center; width: 25px;" title="Justificadas">J</th>
																			<th style="text-align: center; width: 25px;" title="Injustificadas">I</th>
																		</tr>
																	</thead>
				<?php foreach ($fechas as $fecha): ?>
																		<tbody>
																			<tr class="parent">	
																				<td>
																					<?= substr($this->nombres_meses[$fecha->format('m')], 0, 3); ?>
																					<?php if (isset($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]) && !empty($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->fecha_cierre)): ?>
																						<span class="label bg-green pull-right" style="font-size: 100%;" ><i class="fa fa-lock" style="transform: none;"></i></span>
																					<?php endif; ?>
																				</td>
																				<td style="text-align: center;">
																					<?php if (isset($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')])): ?>
						<?= number_format($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->falta_j, 1, ',', ''); ?>
																					<?php endif; ?>
																				</td>
																				<td style="text-align: center;">
																					<?php if (isset($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')])): ?>
						<?= number_format($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->falta_i, 1, ',', ''); ?>
																					<?php endif; ?>
																				</td>
																				<td style="text-align: center;">
																					<?php if (isset($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')])): ?>
						<?= number_format(($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->falta_i + $alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->falta_j), 1, ',', ''); ?>
																					<?php endif; ?>
																				</td>
																				<td style="text-align: center;">
																					<?php if (isset($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')])): ?>
																						<?php if (empty($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->fecha_cierre)): ?>
																							-Mes no cerrado-
																						<?php else: ?>
																							<?= number_format($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->asistencia, 1, ',', ''); ?>
						<?php endif; ?>
																					<?php endif; ?>
																				</td>
																				<td style="text-align: center;">
																					<?php if (isset($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')])): ?>
																						<?php if (empty($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->fecha_cierre)): ?>
																							-Mes no cerrado-
																						<?php else: ?>
																							<?= (empty($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->falta_i) && empty($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->falta_j)) ? "100%" : (empty($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->asistencia) ? '0%' : (empty(($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->asistencia + $alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->falta_i + $alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->falta_j)) ? number_format(($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->asistencia * 100), 0, ',', '') . "%" : (number_format(($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->asistencia * 100 / ($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->asistencia + $alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->falta_i + $alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->falta_j)), 0, ',', '') . "%"))); ?>
						<?php endif; ?>
																					<?php endif; ?>
																				</td>
																				<td style="text-align: center;">
																					<?php if (isset($alumno_inasistencia_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')])): ?>
																						<?php if (isset($inasistencias_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]) && $inasistencias_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->resumen_mensual === 'No'): ?>
																							<span class="label bg-blue pull-right" style="font-size: 100%;"><i class="fa fa-plus"></i><i style="display:none;" class="fa fa-minus"></i></span>
						<?php endif; ?>
																			<?php endif; ?>
																				</td>
																			</tr>
																			<?php if (isset($inasistencias_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]) && $inasistencias_alumno[$division->id][$periodo->periodo][$fecha->format('Ym')]->resumen_mensual === 'No'): ?>
																				<?php $dias_semana = array('Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'); ?>
																				<?php $fecha_ini_m = new DateTime($fecha->format('Ym') . '01'); ?>
																				<?php $fecha_ini_m = max(array(new DateTime($periodo->inicio), $fecha_ini_m)); ?>
																				<?php $fecha_fin_m = new DateTime($fecha->format('Ym') . '01 +1 month -1 day'); ?>
						<?php $fecha_fin_m = min(array(new DateTime($periodo->fin), $fecha_fin_m)); ?>
						<?php $dia_m_interval = DateInterval::createFromDateString('1 day'); ?>
																				<tr class="cchild">
																					<td colspan="7">
																						<div class="row">
																							<div class="col-sm-12">
																								<table class="table table-hover table-bordered table-condensed table-striped" role="grid">
																									<thead>
																										<tr>
																											<?php foreach ($dias_semana as $dia_semana): ?>
																												<th style="width: 80px;"><?= $dia_semana; ?></th>
						<?php endforeach; ?>
																										</tr>
																									</thead>
																									<tbody>
																											<?php $primer_dia_semana_m = $fecha_ini_m->format('w'); ?>
																											<?php $fecha_dia_semana = new DateTime($fecha_ini_m->format('Y-m-d')); ?>
																										<tr>
																												<?php foreach ($dias_semana as $dia_semana_id => $dia_semana): ?>
																												<td>
																													<?php if ($dia_semana_id >= $primer_dia_semana_m): ?>
																														<?= $fecha_dia_semana->format('d'); ?>
																														<?php foreach ($alumno_tipo_inasistencia_diaria_alumno[$division->id] as $alumno_inasistencia_tipo): ?>
																															<?php if ($alumno_inasistencia_tipo->fecha === $fecha_dia_semana->format('Y-m-d')): ?>
																																<?php if ($alumno_inasistencia_tipo->inasistencia_tipo_id === "2"): ?>
																																	<?php if ($alumno_inasistencia_tipo->justificada === "Si"): ?>
																																		<span class="label bg-green">A</span>
																																	<?php else: ?>
																																		<span class="label bg-red">A</span>
																																	<?php endif; ?>
																																<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "3"): ?>
																																	<?php if ($alumno_inasistencia_tipo->justificada === "Si"): ?>
																																		<span class="label bg-green">T</span>
																																	<?php else: ?>
																																		<span class="label bg-red">T</span>
																																	<?php endif; ?>
																																<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "4"): ?>
																																	<?php if ($alumno_inasistencia_tipo->justificada === "Si"): ?>
																																		<span class="label bg-green">R</span>
																																	<?php else: ?>
																																		<span class="label bg-red">R</span>
																																	<?php endif; ?>
																																<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "5"): ?>
																																	<span class="fa fa-fw fa-minus" style="display: inline !important;"></span>
																																<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "6"): ?>
																																	<span class="fa fa-fw fa-minus" style="display: inline !important;"></span>
																																<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "7"): ?>
																																	<span class="label bg-blue">A</span>
																																<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "8"): ?>
																																	<span class="label bg-red">P</span>
																																<?php elseif (empty($alumno_inasistencia_tipo->inasistencia_tipo_id)): ?>
																																	<?php if ($alumno_inasistencia_tipo->contraturno_dia === 'No'): ?>
																																		<span class="label text-green" style="border: #000 solid thin; padding: .1em .5em 0.2em">P</span>
																																	<?php else: ?>
																																		<?php if ($alumno_inasistencia_tipo->contraturno !== "Parcial"): ?>
																																			<span class="label text-green" style="border: #000 solid thin; padding: .1em .5em 0.2em">P</span>
																																		<?php endif; ?>
																																	<?php endif; ?>
																																<?php endif; ?>
																															<?php endif; ?>
																														<?php endforeach; ?>
																													<?php $fecha_dia_semana->add($dia_m_interval); ?>
																												<?php endif; ?>
																												</td>
																											<?php endforeach; ?>
																										</tr>
																										<tr>
																													<?php while ($fecha_dia_semana <= $fecha_fin_m): ?>
																												<td>
																													<span class="text-sn"><?= $fecha_dia_semana->format('d'); ?>
																														<?php foreach ($alumno_tipo_inasistencia_diaria_alumno[$division->id] as $alumno_inasistencia_tipo): ?>
																															<?php if ($alumno_inasistencia_tipo->fecha === $fecha_dia_semana->format('Y-m-d')): ?>
																																<?php if ($alumno_inasistencia_tipo->inasistencia_tipo_id === "2"): ?>
																																	<?php if ($alumno_inasistencia_tipo->justificada === "Si"): ?>
																																		<span class="label bg-green">A</span>
																																	<?php else: ?>
																																		<span class="label bg-red">A</span>
																																	<?php endif; ?>
																																<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "3"): ?>
																																	<?php if ($alumno_inasistencia_tipo->justificada === "Si"): ?>
																																		<span class="label bg-green">T</span>
																																	<?php else: ?>
																																		<span class="label bg-red">T</span>
																																	<?php endif; ?>
																																<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "4"): ?>
																																	<?php if ($alumno_inasistencia_tipo->justificada === "Si"): ?>
																																		<span class="label bg-green">R</span>
																																	<?php else: ?>
																																		<span class="label bg-red">R</span>
																																	<?php endif; ?>
																																<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "5"): ?>
																																	<span class="fa fa-fw fa-minus" style="display: inline !important;"></span>
																																<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "6"): ?>
																																	<span class="fa fa-fw fa-minus" style="display: inline !important;"></span>
																																<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "7"): ?>
																																	<span class="label bg-blue">A</span>
																																<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "8"): ?>
																																	<span class="label bg-red">P</span>
																																<?php elseif (empty($alumno_inasistencia_tipo->inasistencia_tipo_id)): ?>
																																	<?php if ($alumno_inasistencia_tipo->contraturno_dia === 'No'): ?>
																																		<span class="label text-green" style="border: #000 solid thin; padding: .1em .5em 0.2em">P</span>
																																	<?php else: ?>
																																		<?php if ($alumno_inasistencia_tipo->contraturno !== "Parcial"): ?>
																																			<span class="label text-green" style="border: #000 solid thin; padding: .1em .5em 0.2em">P</span>
																																		<?php endif; ?>
																																	<?php endif; ?>
																																<?php endif; ?>
																														<?php endif; ?>
																													<?php endforeach; ?>
																													</span>
																												<?php $fecha_dia_semana->add($dia_m_interval); ?>
																												</td>
																												<?php if ($fecha_dia_semana->format('w') === '0'): ?>
																												</tr>
																												<tr>
							<?php endif; ?>
						<?php endwhile; ?>
																										</tr>
																									</tbody>
																								</table>
																							</div>
																							<div class="col-sm-6">
																								<ul class="chart-legend">
																									<li><span class="label bg-green">A</span> Ausente, Justificado</li>
																									<li><span class="label bg-red">A</span> Ausente, No justificado</li>
																									<li><span class="label bg-blue">A</span> Ausente, No computable</li>
																									<li><span class="label bg-red">P</span> Ausente por tardanza</li>
																									<li><span class="label text-green" style="border: #000 solid thin; padding: .1em .5em 0.2em">P</span> Presente</li>
																								</ul>
																							</div>
																							<div class="col-sm-6">
																								<ul class="chart-legend">
																									<li><span class="label bg-green">T</span> Tardanza, Justificada</li>
																									<li><span class="label bg-red">T</span> Tardanza, No justificada</li>
																									<li><span class="label bg-green">R</span> Retira antes, Justificado</li>
																									<li><span class="label bg-red">R</span> Retira antes, No justificado</li>
																									<li><span class="fa	fa-minus" style="display: inline !important;"></span> No pertenece al curso el día de la fecha</li>
																								</ul>
																							</div>
																						</div>
																					</td>
																				</tr>
																		<?php endif; ?>
																		</tbody>
															<?php endforeach; ?>
																</table>
															</div>
			<?php endforeach; ?>
													</div>
												</td>
											</tr>
										</tbody>
		<?php endforeach; ?>
	<?php else: ?>
									<tr>
										<td style="text-align: center;" colspan="10">
											-- Sin trayectoria --
										</td>
									</tr>
	<?php endif; ?>
							</table>
						</div>
					</div>
				</div>
<?php endif; ?>
<?php if (!empty($hijos)): ?>
				<div class="col-md-9">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><b>Hijo/s</b></h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body" style="padding: 0px;">
							<div class="nav-tabs-custom" style="margin-bottom: 0px;">
								<ul class="nav nav-tabs">
									<?php foreach ($hijos as $key => $hijo): ?>
										<li class="<?= ($key === 0) ? "active" : ""; ?>"><a href="#<?= $hijo->persona_id; ?>" data-toggle="tab"><i class="fa fa-user-circle-o fa-2x" aria-hidden="true" style="color: #3c8dbc; float: left; margin-top: -4px; margin-right: 8px;"></i> <strong><?= $hijo->alumno; ?></strong></a></li>
									<?php endforeach; ?>
								</ul>
								<div class="tab-content">
	<?php foreach ($hijos as $key => $hijo): ?>
										<div class="<?= ($key === 0) ? "active" : ""; ?> tab-pane" id="<?= $hijo->persona_id; ?>">
											<h3><u>Datos del alumno</u>:</h3>
											<div class="row" style="font-size: 16px;">
												<div>
													<div class="col-md-12 col-lg-4">
														<strong><i class="fa fa-user"></i> Datos Personales</strong>
														<p class="text-muted" style="height: 115px;">
															<?= (!empty($hijo_persona[$hijo->persona_id]->cuil)) ? "Cuil: " . $hijo_persona[$hijo->persona_id]->cuil : "<br>Cuil: <span>No cargado</span>"; ?>
															<?= (!empty($hijo_persona[$hijo->persona_id]->documento_tipo) || !empty($hijo_persona[$hijo->persona_id]->documento)) ? "<br>Documento: " . $hijo_persona[$hijo->persona_id]->documento_tipo . " " . $hijo_persona[$hijo->persona_id]->documento : "<br>Documento: <span>No cargado</span>"; ?>
															<?= (!empty($hijo_persona[$hijo->persona_id]->fecha_nacimiento)) ? "<br>Fecha de nacimiento: " . (new DateTime($hijo_persona[$hijo->persona_id]->fecha_nacimiento))->format('d/m/Y') : "<br>Fecha de nacimiento: <span>No cargado</span>"; ?>
		<?= (!empty($hijo_persona[$hijo->persona_id]->sexo)) ? "<br>Sexo: " . $hijo_persona[$hijo->persona_id]->sexo : "<br>Sexo: <span>No cargado</span>"; ?>
		<?= (!empty($hijo_persona[$hijo->persona_id]->nacionalidad)) ? "<br>Nacionalidad: " . $hijo_persona[$hijo->persona_id]->nacionalidad : "<br>Nacionalidad: <span>No cargado</span>"; ?>
														</p>
														<hr style="margin: 10px 0;">
													</div>
													<div class="col-md-12 col-lg-4">
														<strong><i class="fa fa-map-marker margin-r-5"></i> Datos domicilio</strong>
														<p class="text-muted" style="height: 115px;">
															<?php if (isset($hijo_persona[$hijo->persona_id]->calle) && !empty($hijo_persona[$hijo->persona_id]->calle)): ?>
																<?= (!empty($hijo_persona[$hijo->persona_id]->calle)) ? "Calle: " . $hijo_persona[$hijo->persona_id]->calle : "Calle: <span>No cargado</span>"; ?>
																<?= (!empty($hijo_persona[$hijo->persona_id]->calle_numero)) ? "<br>Número: " . $hijo_persona[$hijo->persona_id]->calle_numero : "<br>Número: <span>No cargado</span>"; ?>
															<?php endif; ?>
															<?php if (isset($hijo_persona[$hijo->persona_id]->barrio) && !empty($hijo_persona[$hijo->persona_id]->barrio)): ?>
																<?= (!empty($hijo_persona[$hijo->persona_id]->calle) ? "<br>" : "" ); ?>
																<?= (!empty($hijo_persona[$hijo->persona_id]->barrio)) ? "Barrio: " . $hijo_persona[$hijo->persona_id]->barrio : "<br>Barrio: <span>No cargado</span>"; ?>
																<?= (!empty($hijo_persona[$hijo->persona_id]->manzana)) ? " M: " . $hijo_persona[$hijo->persona_id]->barrio : " M: <span>No cargado</span>"; ?>
																<?= (!empty($hijo_persona[$hijo->persona_id]->casa)) ? " C: " . $hijo_persona[$hijo->persona_id]->casa : " C: <span>No cargado</span>"; ?>
															<?php endif; ?>
															<?php if (isset($hijo_persona[$hijo->persona_id]->departamento) && !empty($hijo_persona[$hijo->persona_id]->departamento)) : ?>
																<?= (!empty($hijo_persona[$hijo->persona_id]->departamento)) ? "<br>Depto: " . $hijo_persona[$hijo->persona_id]->departamento : "<br>Depto: <span>No cargado</span>"; ?>
																<?= (!empty($hijo_persona[$hijo->persona_id]->piso)) ? " Piso: " . $hijo_persona[$hijo->persona_id]->piso : " Piso: <span>No cargado</span>"; ?>
		<?php endif; ?>
		<?= (!empty($hijo_persona[$hijo->persona_id]->localidad)) ? "<br>Localidad: " . $hijo_persona[$hijo->persona_id]->localidad : "<br>Localidad: <span>No cargado</span>"; ?>
														</p>
														<hr style="margin: 10px 0;">
													</div>
													<div class="col-md-12 col-lg-4">
														<strong><i class="fa fa-phone"></i> Datos contacto</strong>
														<p class="text-muted" style="height: 115px;">
		<?= (!empty($hijo_persona[$hijo->persona_id]->telefono_fijo)) ? "Teléfono Fijo: " . $hijo_persona[$hijo->persona_id]->telefono_fijo : "Teléfono Fijo: <span>No cargado</span>"; ?>
		<?= (!empty($hijo_persona[$hijo->persona_id]->telefono_movil)) ? "<br>Celular: " . $hijo_persona[$hijo->persona_id]->telefono_movil : "<br>Celular: <span>No cargado</span>"; ?>
														</p>
														<hr style="margin: 10px 0;">
													</div>
												</div>
											</div>
											<h3><u>Trayectoria del alumno</u>:</h3>
											<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
												<thead>
													<tr style="background-color: #f4f4f4" >
														<th style="text-align: center;" colspan="10">
														</th>
													</tr>	
													<tr>
														<th>C.L.</th>
														<th>Condición</th>
														<th>Escuela</th>
														<th>Division</th>
														<!--<th>Legajo</th>-->
														<th colspan="2">Ingreso</th>
														<th colspan="2">Egreso</th>
														<th>Estado</th>
														<th>Inasistencias</th>
													</tr>
												</thead>
		<?php if (!empty($trayectoria[$hijo->persona_id])): ?>
			<?php foreach ($trayectoria[$hijo->persona_id] as $alumno_division): ?>
														<tbody>
															<tr class="parent">
																<td><?= $alumno_division->ciclo_lectivo; ?></td>
																<td><?= (!empty($division->condicion)) ? "$division->condicion" : ""; ?></td>
																<td><?= "$alumno_division->nombre_escuela"; ?></td>
																<td><?= "$alumno_division->curso $alumno_division->division" . ($alumno_division->grado_multiple === 'Si' ? " ($alumno_division->curso_grado_multiple)" : ''); ?></td>
																<!--<td><?= $alumno_division->legajo; ?></td>-->
																<td><?= empty($alumno_division->fecha_desde) ? '' : (new DateTime($alumno_division->fecha_desde))->format('d/m/y'); ?></td>
																<td class="text-sm"><?= $alumno_division->causa_entrada; ?></td>
																<td><?= empty($alumno_division->fecha_hasta) ? '' : (new DateTime($alumno_division->fecha_hasta))->format('d/m/y'); ?></td>
																<td class="text-sm"><?= $alumno_division->causa_salida; ?></td>
																<td><?= $alumno_division->estado; ?></td>
																<td class="text-center">
																	<span class="label bg-blue" style="font-size: 100%;"><i style="margin-top: -2px; margin-bottom: 2px;" class="fa fa-plus"></i><i style="display:none;" class="fa fa-minus"></i></span>
																</td>
															</tr>
															<tr class="cchild">
																<td colspan="10">
																	<div class="row">
																			<?php if (!empty($hijo->alumno_id) && !empty($periodos[$hijo->persona_id][$alumno_division->id])): ?>
																				<?php foreach ($periodos[$hijo->persona_id][$alumno_division->id] as $periodo): ?>
																				<div class="col-md-12 col-lg-6">
																					<?php $fecha_ini = new DateTime(substr($periodo->inicio, 0, 8) . '01'); ?>
																					<?php $fecha_fin = new DateTime($periodo->fin); ?>
						<?php $dia = DateInterval::createFromDateString('1 month'); ?>
						<?php $fechas = new DatePeriod($fecha_ini, $dia, $fecha_fin); ?>
																					<table class="table table-hover table-bordered table-condensed table-striped" role="grid">
																						<thead>
																							<tr style="background-color:#337ab7; color:white;">
																								<th style="width: 340px;" colspan="13" class="text-center"><?= "{$periodo->periodo}° $periodo->nombre_periodo"; ?> - <?= (new DateTime($periodo->inicio))->format('d/m/Y') . ' al ' . $fecha_fin->format('d/m/Y'); ?>
																								</th>
																							</tr>
																							<tr>
																								<th style="width: 15px;" rowspan="2">Mes</th>
																								<th style="text-align: center; width: 50px;" colspan="2">Resumen Mensual</th>
																								<th style="width: 50px;" rowspan="2">Total del mes</th>
																								<th style="width: 50px;" rowspan="2">Asistencia</th>
																								<th style="width: 50px;" rowspan="2">% de Asistencia</th>
																								<th style="width: 30px; text-align: center" rowspan="2"></th>
																							</tr>
																							<tr>
																								<th style="text-align: center; width: 25px;" title="Justificadas">J</th>
																								<th style="text-align: center; width: 25px;" title="Injustificadas">I</th>
																							</tr>
																						</thead>
						<?php foreach ($fechas as $fecha): ?>
																							<tbody>
																								<tr class="parent">	
																									<td>
																										<?= substr($this->nombres_meses[$fecha->format('m')], 0, 3); ?>
																										<?php if (isset($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]) && !empty($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->fecha_cierre)): ?>
																											<span class="label bg-green pull-right" style="font-size: 100%;" ><i class="fa fa-lock" style="transform: none;"></i></span>
																										<?php endif; ?>
																									</td>
																									<td style="text-align: center;">
																										<?php if (isset($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')])): ?>
								<?= number_format($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->falta_j, 1, ',', ''); ?>
																										<?php endif; ?>
																									</td>
																									<td style="text-align: center;">
																										<?php if (isset($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')])): ?>
								<?= number_format($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->falta_i, 1, ',', ''); ?>
																										<?php endif; ?>
																									</td>
																									<td style="text-align: center;">
																										<?php if (isset($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')])): ?>
								<?= number_format(($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->falta_i + $alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->falta_j), 1, ',', ''); ?>
																										<?php endif; ?>
																									</td>
																									<td style="text-align: center;">
																										<?php if (isset($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')])): ?>
																											<?php if (empty($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->fecha_cierre)): ?>
																												-Mes no cerrado-
																											<?php else: ?>
																												<?= number_format($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->asistencia, 1, ',', ''); ?>
								<?php endif; ?>
																										<?php endif; ?>
																									</td>
																									<td style="text-align: center;">
																										<?php if (isset($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')])): ?>
																											<?php if (empty($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->fecha_cierre)): ?>
																												-Mes no cerrado-
																											<?php else: ?>
																												<?= (empty($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->falta_i) && empty($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->falta_j)) ? "100%" : (empty($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->asistencia) ? '0%' : (empty(($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->asistencia + $alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->falta_i + $alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->falta_j)) ? number_format(($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->asistencia * 100), 0, ',', '') . "%" : (number_format(($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->asistencia * 100 / ($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->asistencia + $alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->falta_i + $alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->falta_j)), 0, ',', '') . "%"))); ?>
								<?php endif; ?>
																										<?php endif; ?>
																									</td>
																									<td style="text-align: center;">
																										<?php if (isset($alumno_inasistencia[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')])): ?>
																											<?php if (isset($inasistencias[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]) && $inasistencias[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->resumen_mensual === 'No'): ?>
																												<span class="label bg-blue pull-right" style="font-size: 100%;"><i class="fa fa-plus"></i><i style="display:none;" class="fa fa-minus"></i></span>
								<?php endif; ?>
																								<?php endif; ?>
																									</td>
																								</tr>
																								<?php if (isset($inasistencias[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]) && $inasistencias[$hijo->persona_id][$alumno_division->id][$periodo->periodo][$fecha->format('Ym')]->resumen_mensual === 'No'): ?>
																									<?php $dias_semana = array('Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'); ?>
																									<?php $fecha_ini_m = new DateTime($fecha->format('Ym') . '01'); ?>
																									<?php $fecha_ini_m = max(array(new DateTime($periodo->inicio), $fecha_ini_m)); ?>
																									<?php $fecha_fin_m = new DateTime($fecha->format('Ym') . '01 +1 month -1 day'); ?>
								<?php $fecha_fin_m = min(array(new DateTime($periodo->fin), $fecha_fin_m)); ?>
								<?php $dia_m_interval = DateInterval::createFromDateString('1 day'); ?>
																									<tr class="cchild">
																										<td colspan="7">
																											<div class="row">
																												<div class="col-sm-12">
																													<table class="table table-hover table-bordered table-condensed table-striped" role="grid">
																														<thead>
																															<tr>
																																<?php foreach ($dias_semana as $dia_semana): ?>
																																	<th style="width: 80px;"><?= $dia_semana; ?></th>
								<?php endforeach; ?>
																															</tr>
																														</thead>
																														<tbody>
																																<?php $primer_dia_semana_m = $fecha_ini_m->format('w'); ?>
																																<?php $fecha_dia_semana = new DateTime($fecha_ini_m->format('Y-m-d')); ?>
																															<tr>
																																	<?php foreach ($dias_semana as $dia_semana_id => $dia_semana): ?>
																																	<td>
																																		<?php if ($dia_semana_id >= $primer_dia_semana_m): ?>
																																			<?= $fecha_dia_semana->format('d'); ?>
																																			<?php foreach ($alumno_tipo_inasistencia_diaria[$hijo->persona_id][$alumno_division->id] as $alumno_inasistencia_tipo): ?>
																																				<?php if ($alumno_inasistencia_tipo->fecha === $fecha_dia_semana->format('Y-m-d')): ?>
																																					<?php if ($alumno_inasistencia_tipo->inasistencia_tipo_id === "2"): ?>
																																						<?php if ($alumno_inasistencia_tipo->justificada === "Si"): ?>
																																							<span class="label bg-green">A</span>
																																						<?php else: ?>
																																							<span class="label bg-red">A</span>
																																						<?php endif; ?>
																																					<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "3"): ?>
																																						<?php if ($alumno_inasistencia_tipo->justificada === "Si"): ?>
																																							<span class="label bg-green">T</span>
																																						<?php else: ?>
																																							<span class="label bg-red">T</span>
																																						<?php endif; ?>
																																					<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "4"): ?>
																																						<?php if ($alumno_inasistencia_tipo->justificada === "Si"): ?>
																																							<span class="label bg-green">R</span>
																																						<?php else: ?>
																																							<span class="label bg-red">R</span>
																																						<?php endif; ?>
																																					<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "5"): ?>
																																						<span class="fa fa-fw fa-minus" style="display: inline !important;"></span>
																																					<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "6"): ?>
																																						<span class="fa fa-fw fa-minus" style="display: inline !important;"></span>
																																					<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "7"): ?>
																																						<span class="label bg-blue">A</span>
																																					<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "8"): ?>
																																						<span class="label bg-red">P</span>
																																					<?php elseif (empty($alumno_inasistencia_tipo->inasistencia_tipo_id)): ?>
																																						<?php if ($alumno_inasistencia_tipo->contraturno_dia === 'No'): ?>
																																							<span class="label text-green" style="border: #000 solid thin; padding: .1em .5em 0.2em">P</span>
																																						<?php else: ?>
																																							<?php if ($alumno_inasistencia_tipo->contraturno !== "Parcial"): ?>
																																								<span class="label text-green" style="border: #000 solid thin; padding: .1em .5em 0.2em">P</span>
																																							<?php endif; ?>
																																						<?php endif; ?>
																																					<?php endif; ?>
																																				<?php endif; ?>
																																			<?php endforeach; ?>
																																		<?php $fecha_dia_semana->add($dia_m_interval); ?>
																																	<?php endif; ?>
																																	</td>
																																<?php endforeach; ?>
																															</tr>
																															<tr>
																																		<?php while ($fecha_dia_semana <= $fecha_fin_m): ?>
																																	<td>
																																		<span class="text-sn"><?= $fecha_dia_semana->format('d'); ?>
																																			<?php foreach ($alumno_tipo_inasistencia_diaria[$hijo->persona_id][$alumno_division->id] as $alumno_inasistencia_tipo): ?>
																																				<?php if ($alumno_inasistencia_tipo->fecha === $fecha_dia_semana->format('Y-m-d')): ?>
																																					<?php if ($alumno_inasistencia_tipo->inasistencia_tipo_id === "2"): ?>
																																						<?php if ($alumno_inasistencia_tipo->justificada === "Si"): ?>
																																							<span class="label bg-green">A</span>
																																						<?php else: ?>
																																							<span class="label bg-red">A</span>
																																						<?php endif; ?>
																																					<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "3"): ?>
																																						<?php if ($alumno_inasistencia_tipo->justificada === "Si"): ?>
																																							<span class="label bg-green">T</span>
																																						<?php else: ?>
																																							<span class="label bg-red">T</span>
																																						<?php endif; ?>
																																					<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "4"): ?>
																																						<?php if ($alumno_inasistencia_tipo->justificada === "Si"): ?>
																																							<span class="label bg-green">R</span>
																																						<?php else: ?>
																																							<span class="label bg-red">R</span>
																																						<?php endif; ?>
																																					<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "5"): ?>
																																						<span class="fa fa-fw fa-minus" style="display: inline !important;"></span>
																																					<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "6"): ?>
																																						<span class="fa fa-fw fa-minus" style="display: inline !important;"></span>
																																					<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "7"): ?>
																																						<span class="label bg-blue">A</span>
																																					<?php elseif ($alumno_inasistencia_tipo->inasistencia_tipo_id === "8"): ?>
																																						<span class="label bg-red">P</span>
																																					<?php elseif (empty($alumno_inasistencia_tipo->inasistencia_tipo_id)): ?>
																																						<?php if ($alumno_inasistencia_tipo->contraturno_dia === 'No'): ?>
																																							<span class="label text-green" style="border: #000 solid thin; padding: .1em .5em 0.2em">P</span>
																																						<?php else: ?>
																																							<?php if ($alumno_inasistencia_tipo->contraturno !== "Parcial"): ?>
																																								<span class="label text-green" style="border: #000 solid thin; padding: .1em .5em 0.2em">P</span>
																																							<?php endif; ?>
																																						<?php endif; ?>
																																					<?php endif; ?>
																																			<?php endif; ?>
																																		<?php endforeach; ?>
																																		</span>
																																	<?php $fecha_dia_semana->add($dia_m_interval); ?>
																																	</td>
																																	<?php if ($fecha_dia_semana->format('w') === '0'): ?>
																																	</tr>
																																	<tr>
									<?php endif; ?>
								<?php endwhile; ?>
																															</tr>
																														</tbody>
																													</table>
																												</div>
																												<div class="col-sm-6">
																													<ul class="chart-legend">
																														<li><span class="label bg-green">A</span> Ausente, Justificado</li>
																														<li><span class="label bg-red">A</span> Ausente, No justificado</li>
																														<li><span class="label bg-blue">A</span> Ausente, No computable</li>
																														<li><span class="label bg-red">P</span> Ausente por tardanza</li>
																														<li><span class="label text-green" style="border: #000 solid thin; padding: .1em .5em 0.2em">P</span> Presente</li>
																													</ul>
																												</div>
																												<div class="col-sm-6">
																													<ul class="chart-legend">
																														<li><span class="label bg-green">T</span> Tardanza, Justificada</li>
																														<li><span class="label bg-red">T</span> Tardanza, No justificada</li>
																														<li><span class="label bg-green">R</span> Retira antes, Justificado</li>
																														<li><span class="label bg-red">R</span> Retira antes, No justificado</li>
																														<li><span class="fa	fa-minus" style="display: inline !important;"></span> No pertenece al curso el día de la fecha</li>
																													</ul>
																												</div>
																											</div>
																										</td>
																									</tr>
																							<?php endif; ?>
																							</tbody>
																				<?php endforeach; ?>
																					</table>
																				</div>
					<?php endforeach; ?>
				<?php endif; ?>
																	</div>
																</td>
															</tr>
														</tbody>
			<?php endforeach; ?>
		<?php else: ?>
													<tbody>
														<tr>
															<td style="text-align: center;" colspan="10">
																-- Sin trayectoria --
															</td>
														</tr>
													</tbody>
										<?php endif; ?>
											</table>
										</div>
	<?php endforeach; ?>
								</div>
							</div>
						</div>				
					</div>
				</div>
<?php endif; ?>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		$('tr.parent>td>span>.fa-plus,tr.parent>td>span>.fa-minus').on('click', function() {
			$(this).closest('tbody').toggleClass('open');
		});
	});
</script>