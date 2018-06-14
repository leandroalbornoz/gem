<style>
	.table>tbody+tbody {
    border-top: 1px solid #ddd;
	}
	.dia_0,.dia_6{
		background-color: lightgray;
	}
	.btn.btn-xs{
		height: 22px;
    width: 22px;
    vertical-align: middle;
	}
	.btn.btn-xs>.fa.fa-minus {
    margin-top: -3px;
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
			<li class="active"><a href="portal/escritorio/"><i class="fa fa-home"></i> Inicio</a></li>
			<li><?= $persona->apellido . ", " . $persona->nombre ?></li>
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
						<div style="margin-left: 10px; font-size: 14px;">
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
									<?= (!empty($persona->casa)) ? " C: $persona->casa" : " C: <span>No cargado</span>"; ?>
								<?php endif; ?>

								<?php if (isset($persona->departamento) && !empty($persona->departamento)) : ?>
									<?= (!empty($persona->departamento)) ? "<br>Depto: $persona->departamento" : "<br>Depto: <span>No cargado</span>"; ?>
									<?= (!empty($persona->piso)) ? " Piso: $persona->piso" : " Piso: <span>No cargado</span>"; ?>
								<?php endif; ?>
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
					<div class="box-footer">
						<a class="btn btn-default" href="portal/escritorio/" title="Volver">Volver</a>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title" style="font-weight: bold;"> Trayectoria </h3> 
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<span style="font-weight: bold; font-size: 18px;">Escuela: </span> <?= $alumno->nombre_escuela; ?> &nbsp;
						<span style="font-weight: bold; font-size: 18px;">Curso y division: </span> <?= $alumno->curso . " " . $alumno->division; ?> &nbsp;
						<span style="font-weight: bold; font-size: 18px;">Ciclo lectivo: </span><?= $alumno->ciclo_lectivo; ?>&nbsp;
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
							<thead>
								<tr>
									<th style="width: 20%;">Condición</th>
									<th style="width: 20%;">Ingreso</th>
									<th style="width: 20%;">Causa entrada</th>
									<th style="width: 20%;">Egreso</th>
									<th style="width: 20%;">Causa salida</th>
								</tr>
							</thead>
							<tbody>
								<tr class="parent">
									<td><?= $alumno->condicion; ?></td>
									<td><?= empty($alumno->fecha_desde) ? '' : (new DateTime($alumno->fecha_desde))->format('d/m/Y'); ?></td>
									<td class="text-sm"><?= $alumno->causa_entrada; ?></td>
									<td><?= empty($alumno->fecha_hasta) ? '' : (new DateTime($alumno->fecha_hasta))->format('d/m/Y'); ?></td>
									<td class="text-sm"  ><?= $alumno->causa_salida; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title" style="font-weight: bold;"> Inasistencias </h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<?php foreach ($periodos as $periodo): ?>
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
												<th style="width: 30px; text-align: center" ></th>
											</tr>
											<tr>
												<th style="text-align: center; width: 25px;" title="Justificadas">J</th>
												<th style="text-align: center; width: 25px;" title="Injustificadas">I</th>
											</tr>
										</thead>

										<?php foreach ($fechas as $fecha): ?>
											<tbody>
												<?php if (!isset($alumno_inasistencia_alumno[$periodo->periodo][$fecha->format('Ym')])): ?>
													<tr class="parent">
														<td>
															<?= $this->nombres_meses[$fecha->format('m')]; ?>
														</td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>
												<?php else: ?>
													<?php $i_a = $inasistencias_alumno[$periodo->periodo][$fecha->format('Ym')]; ?>
													<?php $a_i_a = $alumno_inasistencia_alumno[$periodo->periodo][$fecha->format('Ym')]; ?>
													<tr class="parent">
														<td>
															<?= $this->nombres_meses[$fecha->format('m')]; ?>
															<?php if (!empty($a_i_a->fecha_cierre)): ?>
																<span class="btn btn-xs bg-green pull-right">
																	<i class="fa fa-lock" style="transform: none;"></i></span>
															<?php endif; ?>
														</td>
														<td style="text-align: center;">
															<?= number_format($a_i_a->falta_j, 1, ',', ''); ?>
														</td>
														<td style="text-align: center;">
															<?= number_format($a_i_a->falta_i, 1, ',', ''); ?>
														</td>
														<td style="text-align: center;">
															<?= number_format(($a_i_a->falta_i + $a_i_a->falta_j), 1, ',', ''); ?>
														</td>
														<?php if (empty($a_i_a->fecha_cierre)): ?>
															<td colspan="2" style="text-align: center;">
																-Mes no cerrado-
															</td>
														<?php else: ?>
															<td style="text-align: center;">
																<?php echo number_format($a_i_a->asistencia, 1, ',', ''); ?>
															</td>
															<td style="text-align: center;">
																<?= (empty($a_i_a->falta_i) && empty($a_i_a->falta_j)) ? "100%" : (empty($a_i_a->asistencia) ? '0%' : (empty(($a_i_a->asistencia + $a_i_a->falta_i + $a_i_a->falta_j)) ? number_format(($a_i_a->asistencia * 100), 0, ',', '') . "%" : (number_format(($a_i_a->asistencia * 100 / ($a_i_a->asistencia + $a_i_a->falta_i + $a_i_a->falta_j)), 0, ',', '') . "%"))); ?>
															</td>
														<?php endif; ?>
														<td style="text-align: center;">
															<?php if ($i_a->resumen_mensual === 'No'): ?>
																<span class="btn btn-xs btn-primary">
																	<i class="fa fa-plus"></i>
																	<i style="display:none;" class="fa fa-minus"></i></span>
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
																						<th style="width: 80px;"><?= $dia_semana; ?> </th>
																					<?php endforeach; ?>
																				</tr>
																			</thead>
																			<tbody>
																				<?php $fecha_ini_b = clone $fecha_ini_m; ?>
																				<?php if ($fecha_ini_b->format('w') !== '0'): ?>
																					<?php $fecha_ini_b = $fecha_ini_b->modify('last week sunday'); ?>
																				<?php endif; ?>
																				<?php $fecha_dia_semana = new DateTime($fecha_ini_b->format('Y-m-d')); ?>
																				<tr>
																					<?php while ($fecha_dia_semana <= $fecha_fin_m): ?>
																						<td>
																							<?php if ($fecha_dia_semana >= $fecha_ini_m): ?>
																								<?= $fecha_dia_semana->format('d'); ?>
																								<?php foreach ($alumno_tipo_inasistencia_diaria_alumno as $a_i_d_a): ?>
																									<?php if ($a_i_d_a->fecha === $fecha_dia_semana->format('Y-m-d')): ?>
																										<?php if ($a_i_d_a->inasistencia_tipo_id === "2"): ?>
																											<?php if ($a_i_d_a->justificada === "Si"): ?>
																												<span class="label bg-green">A</span>
																											<?php else: ?>
																												<span class="label bg-red">A</span>
																											<?php endif; ?>
																										<?php elseif ($a_i_d_a->inasistencia_tipo_id === "3"): ?>
																											<?php if ($a_i_d_a->justificada === "Si"): ?>
																												<span class="label bg-green">T</span>
																											<?php else: ?>
																												<span class="label bg-red">T</span>
																											<?php endif; ?>
																										<?php elseif ($a_i_d_a->inasistencia_tipo_id === "4"): ?>
																											<?php if ($a_i_d_a->justificada === "Si"): ?>
																												<span class="label bg-green">R</span>
																											<?php else: ?>
																												<span class="label bg-red">R</span>
																											<?php endif; ?>
																										<?php elseif ($a_i_d_a->inasistencia_tipo_id === "5"): ?>
																											<span class="fa fa-fw fa-minus" style="display: inline !important;"></span>
																										<?php elseif ($a_i_d_a->inasistencia_tipo_id === "6"): ?>
																											<span class="fa fa-fw fa-minus" style="display: inline !important;"></span>
																										<?php elseif ($a_i_d_a->inasistencia_tipo_id === "7"): ?>
																											<span class="label bg-blue">A</span>
																										<?php elseif ($a_i_d_a->inasistencia_tipo_id === "8"): ?>
																											<span class="label bg-red">P</span>
																										<?php elseif (empty($a_i_d_a->inasistencia_tipo_id)): ?>
																											<?php if ($a_i_d_a->contraturno_dia === 'No'): ?>
																												<span class="label text-green" style="border: #000 solid thin; padding: .1em .5em 0.2em">P</span>
																											<?php else: ?>
																												<?php if ($a_i_d_a->contraturno !== "Parcial"): ?>
																													<span class="label text-green" style="border: #000 solid thin; padding: .1em .5em 0.2em">P</span>
																												<?php endif; ?>
																											<?php endif; ?>
																										<?php endif; ?>
																									<?php endif; ?>
																								<?php endforeach; ?>
																							<?php endif; ?>
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
												<?php endif; ?>
											</tbody>
										<?php endforeach; ?>
									</table>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title" style="font-weight: bold;"> Notas </h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
							<thead>
								<tr style="background-color: #f4f4f4" >
									<th style="text-align: center;" colspan="5">
									</th>
								</tr>
								<tr>
									<th>Materia</th>
									<th>Fecha</th>
									<th>Tipo de evaluación</th>
									<th>Descripción de evaluación</th>
									<th style="text-align: center;">Nota</th>
								</tr>
							</thead>
							<?php $array_notas = array('1' => 'R', '2' => 'A', '3' => 'V'); ?>
							<?php if (!empty($notas)): ?>
								<tbody>
									<?php foreach ($notas as $nota): ?>
										<tr >
											<td><?= $nota->materia; ?></td>
											<td><?= $nota->fecha; ?></td>
											<td><?= $nota->descripcion; ?></td>
											<td><?= $nota->tema; ?></td>
											<td style="text-align: center;" >
												<?php if ($nota->calificacion_id === '1'): ?>
													<?= $array_notas[round($nota->nota)]; ?>
												<?php else: ?>
													<?= $nota->nota ?>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							<?php else: ?>
								<tbody>
									<tr>
										<td style="text-align: center;" colspan="10">
											-- Sin Notas --
										</td>
									</tr>
								</tbody>
							<?php endif; ?>
						</table>
					</div>
				</div>
			</div>
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