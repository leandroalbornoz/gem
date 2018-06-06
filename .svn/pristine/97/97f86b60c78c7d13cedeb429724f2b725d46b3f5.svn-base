<style>
	.table>tbody+tbody {
    border-top: 1px solid #ddd;
	}
	.btn.btn-xs{
		height:22px;
		width:22px;
		vertical-align: middle;
	}
	.btn.btn-xs>.fa.fa-minus{
    margin-top: -3px;
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
	.open .cchild {
		display: table-row;
	}
	.parent i {
		transform: rotate(0deg);
		transition: transform .3s cubic-bezier(.4,0,.2,1);
		margin: -.5rem;
		padding: .5rem;
	}
	.open .parent i {
		transform: rotate(180deg);
	}
	.open .fa-minus{
		display: block !important;
	}
	.open .fa-plus{
		display: none;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Alumno Inasistencia
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id; ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="division/listar/<?= $escuela->id ?>">Cursos y Divisiones</a></li>
			<li><a href="division/ver/<?= $division_id ?>"><?= "$division"; ?></a></li>
			<li><a href="division/alumnos/<?= "$division_id/$ciclo_lectivo"; ?>">Alumno</a></li>
			<li><a href="alumno/ver/<?= $alumno_division_id; ?>"><?="$alumno->apellido, $alumno->nombre";?></a></li>
			<li class="active"><?= ucfirst($metodo); ?></li>
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
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">
							<?php if (!empty($alumno)): ?>
								<?= "$alumno->apellido, $alumno->nombre"; ?>
							<?php endif; ?>
						</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta" href="alumno/ver/<?= $alumno_division_id; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['inasistencia']; ?>" href="alumno_division/inasistencia/<?= $alumno_division_id; ?>">
							<i class="fa fa-clock-o" id="btn-ver"></i> Inasistencia
						</a>
						<div class="row">
							<div class="form-group col-md-2">
								<?= $fields['cuil']['label']; ?>
								<?= $fields['cuil']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?= $fields['documento_tipo']['label']; ?>
								<?= $fields['documento_tipo']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?= $fields['documento']['label']; ?>
								<span class="label label-danger" id="documento_existente"></span>
								<?= $fields['documento']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?= $fields['apellido']['label']; ?>
								<?= $fields['apellido']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?= $fields['nombre']['label']; ?>
								<?= $fields['nombre']['form']; ?>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-2">
								<?= $fields['sexo']['label']; ?>
								<?= $fields['sexo']['form']; ?>
							</div>
							<div class="form-group col-sm-3">
								<?= $fields['nacionalidad']['label']; ?>
								<?= $fields['nacionalidad']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?= $fields['fecha_nacimiento']['label']; ?>
								<?= $fields['fecha_nacimiento']['form']; ?>
							</div>
							<div class="form-group col-md-5">
								<?= $fields['email_contacto']['label']; ?>
								<?= $fields['email_contacto']['form']; ?>
							</div>
						</div>	
					</div>
				</div>
				<div style="display:none;" id="div_buscar_familiar"></div>
			</div>
			<?php if ($txt_btn !== 'Editar'): ?>
				<div class="col-sm-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Asistencia</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<?php foreach ($periodos as $periodo): ?>
							<div class="box-body ">
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
											<th style="width: 10%;" rowspan="2">Mes</th>
											<th style="width: 10%;" rowspan="2">Tipo Asist.</th>
											<th style="text-align: center; width: 20%;" colspan="2">Resumen Mensual</th>
											<th style="text-align: center; width: 20%;" rowspan="2">Total del mes</th>
											<th style="text-align: center; width: 20%;" rowspan="2">Asistencia</th>
											<th style="text-align: center; width: 20%;" rowspan="2">% de Asistencia</th>
										</tr>
										<tr>
											<th style="text-align: center; width: 10.5%;" title="Justificadas">J</th>
											<th style="text-align: center; width: 10.5%;" title="Injustificadas">I</th>
										</tr>
									</thead>
									<?php foreach ($fechas as $fecha): ?>
										<tbody>
											<?php if (!isset($alumno_inasistencia[$periodo->periodo][$fecha->format('Ym')])): ?>
												<tr class="parent">
													<td>
														<?= $this->nombres_meses[$fecha->format('m')]; ?>
													</td>
													<td>Sin Asist.</td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>
											<?php else: ?>
												<?php $i = $inasistencias[$periodo->periodo][$fecha->format('Ym')]; ?>
												<?php $a_i = $alumno_inasistencia[$periodo->periodo][$fecha->format('Ym')]; ?>
												<tr class="parent">
													<td>
														<?= $this->nombres_meses[$fecha->format('m')]; ?>
													</td>
													<td>
														<?php if ($i->resumen_mensual === 'Si'): ?>
															<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_division/modal_editar_inasistencia_mensual/<?= "$alumno_division_id/{$fecha->format('Ym')}/$periodo->periodo"; ?>" title="Editar"><i class="fa fa-pencil"></i></a>
															Mensual 
														<?php else: ?>
															<span class="btn btn-xs btn-primary"><i class="fa fa-plus"></i><i style="display:none;" class="fa fa-minus"></i></span>
															Diario
														<?php endif; ?>
													</td>
													<td style="text-align: center;">
														<?= number_format($a_i->falta_j, 1, ',', ''); ?>
													</td>
													<td style="text-align: center;">
														<?= number_format($a_i->falta_i, 1, ',', ''); ?>
													</td>
													<td style="text-align: center;">
														<?= number_format(($a_i->falta_i + $a_i->falta_j), 1, ',', ''); ?>
													</td>
													<td style="text-align: center;">
														<?php if (empty($a_i->fecha_cierre)): ?>
															-Mes no cerrado-
														<?php else: ?>
															<?= number_format($a_i->asistencia, 1, ',', ''); ?>
														<?php endif; ?>
													</td>
													<td style="text-align: center;">
														<?php if (empty($a_i->fecha_cierre)): ?>
															-Mes no cerrado-
														<?php else: ?>
															<?= (empty($a_i->falta_i) && empty($a_i->falta_j)) ? "100%" : (empty($a_i->asistencia) ? '0%' : (empty(($a_i->asistencia + $a_i->falta_i + $a_i->falta_j)) ? number_format(($a_i->asistencia * 100), 0, ',', '') . "%" : (number_format(($a_i->asistencia * 100 / ($a_i->asistencia + $a_i->falta_i + $a_i->falta_j)), 0, ',', '') . "%"))); ?>
														<?php endif; ?>
													</td>
													<?php if ($i->resumen_mensual === 'No'): ?>
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
																							<?php foreach ($alumno_tipo_inasistencia_diaria as $a_i_d): ?>
																								<?php if ($a_i_d->fecha === $fecha_dia_semana->format('Y-m-d')): ?>
																									<?php if ($a_i_d->inasistencia_tipo_id === "2"): ?>
																										<?php if ($a_i_d->justificada === "Si"): ?>
																											<span class="label bg-green">A</span>
																										<?php else: ?>
																											<span class="label bg-red">A</span>
																										<?php endif; ?>
																									<?php elseif ($a_i_d->inasistencia_tipo_id === "3"): ?>
																										<?php if ($a_i_d->justificada === "Si"): ?>
																											<span class="label bg-green">T</span>
																										<?php else: ?>
																											<span class="label bg-red">T</span>
																										<?php endif; ?>
																									<?php elseif ($a_i_d->inasistencia_tipo_id === "4"): ?>
																										<?php if ($a_i_d->justificada === "Si"): ?>
																											<span class="label bg-green">R</span>
																										<?php else: ?>
																											<span class="label bg-red">R</span>
																										<?php endif; ?>
																									<?php elseif ($a_i_d->inasistencia_tipo_id === "5"): ?>
																										<span class="fa fa-fw fa-minus" style="display: inline !important;"></span>
																									<?php elseif ($a_i_d->inasistencia_tipo_id === "6"): ?>
																										<span class="fa fa-fw fa-minus" style="display: inline !important;"></span>
																									<?php elseif ($a_i_d->inasistencia_tipo_id === "7"): ?>
																										<span class="label bg-blue">A</span>
																									<?php elseif ($a_i_d->inasistencia_tipo_id === "8"): ?>
																										<span class="label bg-red">P</span>
																									<?php elseif (empty($a_i_d->inasistencia_tipo_id)): ?>
																										<?php if ($a_i_d->contraturno_dia === 'No'): ?>
																											<span class="label text-green" style="border: #000 solid thin; padding: .1em .5em 0.2em">P</span>
																										<?php else: ?>
																											<?php if ($a_i_d->contraturno !== "Parcial"): ?>
																												<span class="label text-green" style="border: #000 solid thin; padding: .1em .5em 0.2em">P</span>
																											<?php endif; ?>
																										<?php endif; ?>
																									<?php endif; ?>
																									<?php if ($a_i_d->contraturno_dia === 'No' || ($a_i_d->contraturno !== 'No')): ?>
																										<a class="btn btn-xs btn-warning pull-right-container" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_division/modal_editar_inasistencia_dia/<?= $alumno_division_id . "/" . $a_i_d->division_inasistencia_dia_id; ?>" title="Editar"><i class="fa fa-pencil"></i></a>
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
					<?php endif; ?>
				</div>
			</div>
		</div>
</div>
<script>
	$(document).ready(function() {
		$('table').on('click', 'tr.parent .fa-plus,tr.parent .fa-minus', function() {
			$(this).closest('tbody').toggleClass('open');
		});
	});
</script>