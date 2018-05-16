<html lang="es">
	<head>
		<base href="<?php echo base_url(); ?>" />
		<meta charset="utf-8">
	</head>
	<body>
		<?php $meses = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'); ?>
		<div class="tb-cab-index">
			<div class="row">
				<div class="col-lg-12" id="cabeceraEscuela">
					<table class="table table-bordered table-condensed table-responsive table-striped tabla-header">
						<tr>
							<td rowspan="4" style="text-align: center;"><img src="img/logomza.jpg"></td>
							<td colspan="4"><h4><b>PLANILLA DE ASISTENCIA DE PERSONAL</b> - <b>MES:</b> <?= substr($planilla->ames, -2); ?> <b>AÑO:</b> <?= substr($planilla->ames, 0, 4); ?><?= $planilla->rectificativa ? " <b>RECTIFICATIVA:</b> $planilla->rectificativa" : ''; ?></h4></td>
						</tr>
						<tr>
							<td colspan="2"><b>Área</b></td>
							<td colspan="2"><?= $area->descripcion; ?></td>
						</tr>
						<tr>
							<td colspan="2"><b>Código Área</b></td>
							<td colspan="2"><?= $area->codigo; ?></td>
						</tr>
						<tr>
							<td><b>Jurisdicción</b></td>
							<td><?= $area->jurisdiccion_codigo; ?></td>
							<td><b>Nº Repartición</b></td>
							<td><?= $area->reparticion_codigo; ?></td>
						</tr>
					</table>
				</div>
				<div class="col-lg-12 scrollable-area" style="overflow-x: auto; overflow-y: hidden;" >
					<table id="tablaDetalle" class="table table-bordered-bl table-condensed table-responsive table-striped table-hover" style="display: block;">
						<thead>
							<tr>
								<th style="width:250px;">CUIL - Liqui.</th>
								<th rowspan="3">S.R.</th>
								<th rowspan="3">Reg. Sal.</th>
								<th rowspan="3">U.O.</th>
								<th rowspan="3">Cargo<br>Horas</th>
								<th rowspan="3">A Cumplir</th>
								<th rowspan="3">Fecha Alta</th>
								<th colspan="5">Inasistencia</th>
								<th colspan="3">Bajas</th>
								<th colspan="5">Detalle Función</th>
								<th rowspan="3">Observaciones</th>
							</tr>
							<tr>
								<th>Apellido - Nombre</th>
								<th colspan="2">Días</th>
								<th colspan="3">Obligaciones</th>
								<th rowspan="2">Continua</th>
								<th rowspan="2">Fecha Baja</th>
								<th rowspan="2">Motivo</th>
								<th style="width:150px;" rowspan="2">Función</th>
								<th rowspan="2">Orígen<br>Destino<br>Tareas</th>
								<th rowspan="2">Carga Horaria</th>
								<th rowspan="2">Fecha Desde</th>
								<th rowspan="2">Norma Legal</th>
							</tr>
							<tr>
								<th>Fecha Nacimiento</th>
								<th>Desde</th>
								<th>Hasta</th>
								<th>No Cumplió</th>
								<th>Art.</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($filas)): ?>
								<?php foreach ($filas as $fila): ?>
									<tr style="font-size:16px;">
										<td>
											<span style="font-size:18px;"><?= $fila->cuil ? "$fila->cuil ($fila->nroliq)" . (!empty($fila->lsignos) ? ' (S-' . substr($fila->lsignos, -3) . ')' : '') : $fila->liquidacion; ?></span>
											<br/>
											<?= htmlspecialchars("$fila->apellido, $fila->nombre"); ?>
											<br/>
											<?= empty($fila->fecha_nacimiento) ? '' : (new DateTime($fila->fecha_nacimiento))->format('d/m/Y'); ?>
											<?php if ($fila->baja_dada): ?>
												<br><b>Baja Mes Anterior</b>
											<?php endif; ?>
											<?php if (!$fila->tbcab_id): ?>
												<br><b>ALTA MES <?= strtoupper($meses[(new DateTime($fila->fecha_alta))->format('m') - 1]); ?></b>
											<?php endif; ?>
										</td>
										<td>
											<?= substr($fila->situacion_revista, 0, 4); ?>
										</td>
										<td style="font-size:16px;"><?= $fila->regimen_codigo; ?></td>
										<td style="font-size:16px;"><?= $fila->unidad_organizativa; ?></td>
										<td style="font-size:16px; text-align: center;"><?= $fila->oblig; ?></td>
										<td style="font-size:16px; text-align: right;">
											<?php $acumplir = $fila->oblig_cumplir; ?>
											<?= $fila->oblig_cumplir; ?>
										</td>
										<td style="font-size:16px;">
											<?= empty($fila->fecha_alta) ? '' : date_format(new DateTime($fila->fecha_alta), 'd/m/y'); ?>
										</td>
										<!-- begin inasistencia -->
										<td style="font-size:16px; text-align: right;">
											<!-- inasistencia, dias, desde -->
											<?php foreach ($fila->inasistencias as $inasistencia): ?>
												<?php if ($inasistencia->novedad_tipo_id != '2' && $inasistencia->novedad_tipo_novedad != 'B'): ?>
													<?= date_format(new DateTime($inasistencia->dias_desde), 'd/m/y') . '<br />'; ?>
												<?php endif; ?>
											<?php endforeach; ?>
										</td>
										<td style="font-size:16px; text-align: right;">
											<!-- inasistencia, dias, hasta -->
											<?php foreach ($fila->inasistencias as $inasistencia): ?>
												<?php if ($inasistencia->novedad_tipo_id != '2' && $inasistencia->novedad_tipo_novedad != 'B'): ?>
													<?= ($inasistencia->articulo === 'AA-0' ? '' : date_format(new DateTime($inasistencia->dias_hasta), 'd/m/y')) . '<br />'; ?>
												<?php endif; ?>
											<?php endforeach; ?>
										</td>
										<td style="font-size:16px; text-align: right;">
											<!-- Obligaciones no cumplio -->
											<?php $total = $acumplir; ?>
											<?php foreach ($fila->inasistencias as $inasistencia): ?>
												<?php if ($inasistencia->novedad_tipo_id != '2' && $inasistencia->novedad_tipo_novedad != 'B'): ?>
													<?= $inasistencia->oblig_nocumplio . '<br />'; ?>
												<?php endif; ?>
												<?php $total -= $inasistencia->oblig_nocumplio; ?>
											<?php endforeach; ?>
										</td>
										<td style="font-size:16px; text-align: right; white-space: nowrap;">
											<!-- Obligaciones art. -->
											<?php foreach ($fila->inasistencias as $inasistencia): ?>
												<?php if ($inasistencia->novedad_tipo_id != '2' && $inasistencia->novedad_tipo_novedad != 'B'): ?>
													<?= ($inasistencia->articulo === 'AA-0' ? 'Alta' : $inasistencia->articulo) . '<br />'; ?>
												<?php endif; ?>
											<?php endforeach; ?>
										</td>
										<td style="font-size:16px; text-align: right;">
											<!-- Obligaciones total -->
											<?= max($total, 0); ?>
										</td>
										<!-- end inasistencia -->
										<td style="font-size:16px;">
											<!-- continua -->
											<?= $fila->baja_continua ? 'Si' : 'No'; ?>
										</td>
										<td style="font-size:16px;">
											<!-- baja, fecha baja-->
											<?= empty($fila->fecha_baja) ? '' : date_format(new DateTime($fila->fecha_baja), 'd/m/y'); ?>
										</td>
										<td>
											<!-- baja, motivo -->
											<?= $fila->motivo_baja; ?>
										</td>
										<td style="font-size:14px;">
											<!-- detalle funcion -->
											<?= $fila->funcion_detalle; ?>
										</td>
										<td>
											<!-- destino/ tareas-->
											<?= $fila->funcion_tarea; ?>
											<?= $fila->funcion_destino; ?>
										</td>
										<td>
											<!-- carga horaria -->
											<?= $fila->funcion_cargahoraria; ?>
										</td>
										<td>
											<!-- detalle funcion, fecha desde -->
											<?= empty($fila->funcion_desde) ? '' : date_format(new DateTime($fila->funcion_desde), 'd/m/y'); ?>
										</td>
										<td>
											<!-- detalle funcion, norma legal -->
											<?= $fila->funcion_norma; ?>
										</td>
										<td>
											<!-- observaciones -->
											<?= $fila->observaciones; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php if (!empty($filasFuncion)): ?>
			<div style="page-break-before: always;"></div>
			<div class="tb-cab-index">
				<div class="row">
					<div class="col-lg-12" id="cabeceraEscuela">
						<table class="table table-bordered table-condensed table-responsive table-striped tabla-header">
							<tr>
								<td rowspan="4" style="text-align: center;"><img src="img/logomza.jpg"></td>
								<td colspan="4"><h4><b>PLANILLA DE ASISTENCIA DE PERSONAL</b> - <b>MES:</b> <?= substr($planilla->ames, -2); ?> <b>AÑO:</b> <?= substr($planilla->ames, 0, 4); ?><?= $planilla->rectificativa ? " <b>RECTIFICATIVA:</b> $planilla->rectificativa" : ''; ?> - OTROS SERVICIOS</h4></td>
							</tr>
							<tr>
								<td colspan="2"><b>Área</b></td>
								<td colspan="2"><?= $area->descripcion; ?></td>
							</tr>
							<tr>
								<td colspan="2"><b>Código Área</b></td>
								<td colspan="2"><?= $area->codigo; ?></td>
							</tr>
							<tr>
								<td><b>Jurisdicción</b></td>
								<td><?= $area->jurisdiccion_codigo; ?></td>
								<td><b>Nº Repartición</b></td>
								<td><?= $area->reparticion_codigo; ?></td>
							</tr>
						</table>
					</div>
					<div class="col-lg-12 scrollable-area" style="overflow-x: auto; overflow-y: hidden;" >
						<table id="tablaDetalle" class="table table-bordered-bl table-condensed table-responsive table-striped table-hover" style="display: block;">
							<thead>
								<tr>
									<th style="width:250px;">CUIL - Liqui.</th>
									<th rowspan="3">S.R.</th>
									<th rowspan="3">Reg. Sal.</th>
									<th rowspan="3">U.O.</th>
									<th rowspan="3">Cargo<br>Horas</th>
									<th rowspan="3">A Cumplir</th>
									<th rowspan="3">Fecha Alta</th>
									<th colspan="5">Inasistencia</th>
									<th colspan="3">Bajas</th>
									<th colspan="5">Detalle Función</th>
									<th rowspan="3">Observaciones</th>
								</tr>
								<tr>
									<th>Apellido - Nombre</th>
									<th colspan="2">Días</th>
									<th colspan="3">Obligaciones</th>
									<th rowspan="2">Continua</th>
									<th rowspan="2">Fecha Baja</th>
									<th rowspan="2">Motivo</th>
									<th style="width:150px;" rowspan="2">Función</th>
									<th rowspan="2">Orígen<br>Tareas</th>
									<th rowspan="2">Carga Horaria</th>
									<th rowspan="2">Fecha Desde</th>
									<th rowspan="2">Norma Legal</th>
								</tr>
								<tr>
									<th>Fecha Nacimiento</th>
									<th>Desde</th>
									<th>Hasta</th>
									<th>No Cumplió</th>
									<th>Art.</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($filasFuncion as $fila): ?>
									<tr style="font-size:16px;">
										<td>
											<span style="font-size:18px;"><?= $fila->cuil ? "$fila->cuil ($fila->nroliq)" . (!empty($fila->lsignos) ? ' (S-' . substr($fila->lsignos, -3) . ')' : '') : $fila->liquidacion; ?></span>
											<br/>
											<?= htmlspecialchars("$fila->apellido, $fila->nombre"); ?>
											<br/>
											<?= empty($fila->fecha_nacimiento) ? '' : (new DateTime($fila->fecha_nacimiento))->format('d/m/Y'); ?>
											<?php if ($fila->baja_dada): ?>
												<br><b>Baja Mes Anterior</b>
											<?php endif; ?>
											<?php if (!$fila->tbcab_id): ?>
												<br><b>ALTA MES <?= strtoupper($meses[(new DateTime($fila->fecha_alta))->format('m') - 1]); ?></b>
											<?php endif; ?>
										</td>
										<td>
											<?= substr($fila->revista, 0, 4); ?>
										</td>
										<td style="font-size:16px;"><?= $fila->regimen_codigo; ?></td>
										<td style="font-size:16px;"><?= $fila->unidad_organizativa; ?></td>
										<td style="font-size:16px; text-align: center;"><?= $fila->oblig; ?></td>
										<td style="font-size:16px; text-align: right;">
											<?php $acumplir = $fila->oblig_cumplir; ?>
											<?= $fila->oblig_cumplir; ?>
										</td>
										<td style="font-size:16px;">
											<?= empty($fila->fecha_alta) ? '' : date_format(new DateTime($fila->fecha_alta), 'd/m/y'); ?>
										</td>
										<!-- begin inasistencia -->
										<td style="font-size:16px; text-align: right;">
											<!-- inasistencia, dias, desde -->
											<?php foreach ($fila->inasistencias as $inasistencia): ?>
												<?php if ($inasistencia->novedad_tipo_id != '2' && $inasistencia->novedad_tipo_novedad != 'B'): ?>
													<?= date_format(new DateTime($inasistencia->dias_desde), 'd/m/y') . '<br />'; ?>
												<?php endif; ?>
											<?php endforeach; ?>
										</td>
										<td style="font-size:16px; text-align: right;">
											<!-- inasistencia, dias, hasta -->
											<?php foreach ($fila->inasistencias as $inasistencia): ?>
												<?php if ($inasistencia->novedad_tipo_id != '2' && $inasistencia->novedad_tipo_novedad != 'B'): ?>
													<?= ($inasistencia->articulo === 'AA-0' ? '' : date_format(new DateTime($inasistencia->dias_hasta), 'd/m/y')) . '<br />'; ?>
												<?php endif; ?>
											<?php endforeach; ?>
										</td>
										<td style="font-size:16px; text-align: right;">
											<!-- Obligaciones cumplio -->
											<?php $total = $acumplir; ?>
											<?php foreach ($fila->inasistencias as $inasistencia): ?>
												<?php if ($inasistencia->novedad_tipo_id != '2' && $inasistencia->novedad_tipo_novedad != 'B'): ?>
													<?= $inasistencia->oblig_nocumplio . '<br />'; ?>
												<?php endif; ?>
												<?php $total -= $inasistencia->oblig_nocumplio; ?>
											<?php endforeach; ?>
										</td>
										<td style="font-size:16px; text-align: right; white-space: nowrap;">
											<!-- Obligaciones art. -->
											<?php foreach ($fila->inasistencias as $inasistencia): ?>
												<?php if ($inasistencia->novedad_tipo_id != '2' && $inasistencia->novedad_tipo_novedad != 'B'): ?>
													<?= ($inasistencia->articulo === 'AA-0' ? 'Alta' : $inasistencia->articulo) . '<br />'; ?>
												<?php endif; ?>
											<?php endforeach; ?>
										</td>
										<td style="font-size:16px; text-align: right;">
											<!-- Obligaciones total -->
											<?= max($total, 0); ?>
										</td>
										<!-- end inasistencia -->
										<td style="font-size:16px;">
											<!-- continua -->
											<?= $fila->baja_continua ? 'Si' : 'No'; ?>
										</td>
										<td style="font-size:16px;">
											<!-- baja, fecha baja-->
											<?= empty($fila->baja_fecha) ? '' : date_format(new DateTime($fila->fecha_baja), 'd/m/y'); ?>
										</td>
										<td>
											<!-- baja, motivo -->
											<?= $fila->motivo_baja; ?>
										</td>
										<td style="font-size:14px;">
											<!-- detalle funcion -->
											<?= $fila->funcion_detalle; ?>
										</td>
										<td>
											<!-- destino/ tareas-->
											<?= $fila->funcion_tarea; ?>
											<?= $fila->escuela . $fila->area; ?>
										</td>
										<td>
											<!-- carga horaria -->
											<?= $fila->funcion_cargahoraria; ?>
										</td>
										<td>
											<!-- detalle funcion, fecha desde -->
											<?= empty($fila->funcion_desde) ? '' : date_format(new DateTime($fila->funcion_desde), 'd/m/y'); ?>
										</td>
										<td>
											<!-- detalle funcion, norma legal -->
											<?= $fila->funcion_norma; ?>
										</td>
										<td>
											<!-- observaciones -->
											<?= $fila->observaciones; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if (!empty($filasContratos)): ?>
			<div style="page-break-before: always;"></div>
			<div class="tb-cab-index">
				<div class="row">
					<div class="col-lg-12" id="cabeceraEscuela">
						<table class="table table-bordered table-condensed table-responsive table-striped tabla-header">
							<tr>
								<td rowspan="4" style="text-align: center;"><img src="img/logomza.jpg"></td>
								<td colspan="4"><h4><b>PLANILLA DE ASISTENCIA DE PERSONAL</b> - <b>MES:</b> <?= substr($planilla->ames, -2); ?> <b>AÑO:</b> <?= substr($planilla->ames, 0, 4); ?><?= $planilla->rectificativa ? " <b>RECTIFICATIVA:</b> $planilla->rectificativa" : ''; ?> - CONTRATADOS</h4></td>
							</tr>
							<tr>
								<td colspan="2"><b>Área</b></td>
								<td colspan="2"><?= $area->descripcion; ?></td>
							</tr>
							<tr>
								<td colspan="2"><b>Código Área</b></td>
								<td colspan="2"><?= $area->codigo; ?></td>
							</tr>
							<tr>
								<td><b>Jurisdicción</b></td>
								<td><?= $area->jurisdiccion_codigo; ?></td>
								<td><b>Nº Repartición</b></td>
								<td><?= $area->reparticion_codigo; ?></td>
							</tr>
						</table>
					</div>
					<div class="col-lg-12 scrollable-area" style="overflow-x: auto; overflow-y: hidden;" >
						<table id="tablaDetalle" class="table table-bordered-bl table-condensed table-responsive table-striped table-hover" style="display: block;">
							<thead>
								<tr>
									<th style="width:250px;">CUIL - Liqui.</th>
									<th rowspan="3">S.R.</th>
									<th rowspan="3">Reg. Sal.</th>
									<th rowspan="3">U.O.</th>
									<th rowspan="3">Cargo<br>Horas</th>
									<th rowspan="3">A Cumplir</th>
									<th rowspan="3">Fecha Alta</th>
									<th colspan="5">Inasistencia</th>
									<th colspan="3">Bajas</th>
									<th colspan="5">Detalle Función</th>
									<th rowspan="3">Observaciones</th>
								</tr>
								<tr>
									<th>Apellido - Nombre</th>
									<th colspan="2">Días</th>
									<th colspan="3">Obligaciones</th>
									<th rowspan="2">Continua</th>
									<th rowspan="2">Fecha Baja</th>
									<th rowspan="2">Motivo</th>
									<th style="width:150px;" rowspan="2">Función</th>
									<th rowspan="2">Orígen<br>Tareas</th>
									<th rowspan="2">Carga Horaria</th>
									<th rowspan="2">Fecha Desde</th>
									<th rowspan="2">Norma Legal</th>
								</tr>
								<tr>
									<th>Fecha Nacimiento</th>
									<th>Desde</th>
									<th>Hasta</th>
									<th>No Cumplió</th>
									<th>Art.</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($filasContratos as $fila): ?>
									<tr style="font-size:16px;">
										<td>
											<span style="font-size:18px;"><?= $fila->cuil ? "$fila->cuil ($fila->nroliq)" . (!empty($fila->lsignos) ? ' (S-' . substr($fila->lsignos, -3) . ')' : '') : $fila->liquidacion; ?></span>
											<br/>
											<?= htmlspecialchars("$fila->apellido, $fila->nombre"); ?>
											<br/>
											<?= empty($fila->fecha_nacimiento) ? '' : (new DateTime($fila->fecha_nacimiento))->format('d/m/Y'); ?>
											<?php if ($fila->baja_dada): ?>
												<br><b>Baja Mes Anterior</b>
											<?php endif; ?>
											<?php if (!$fila->tbcab_id): ?>
												<br><b>ALTA MES <?= strtoupper($meses[(new DateTime($fila->fecha_alta))->format('m') - 1]); ?></b>
											<?php endif; ?>
										</td>
										<td>
											<?= substr($fila->revista, 0, 4); ?>
										</td>
										<td style="font-size:16px;"><?= $fila->regimen_codigo; ?></td>
										<td style="font-size:16px;"><?= $fila->unidad_organizativa; ?></td>
										<td style="font-size:16px; text-align: center;"><?= $fila->oblig; ?></td>
										<td style="font-size:16px; text-align: right;">
											<?php $acumplir = $fila->oblig_cumplir; ?>
											<?= $fila->oblig_cumplir; ?>
										</td>
										<td style="font-size:16px;">
											<?= empty($fila->fecha_alta) ? '' : date_format(new DateTime($fila->fecha_alta), 'd/m/y'); ?>
										</td>
										<!-- begin inasistencia -->
										<td style="font-size:16px; text-align: right;">
											<!-- inasistencia, dias, desde -->
											<?php foreach ($fila->inasistencias as $inasistencia): ?>
												<?php if ($inasistencia->novedad_tipo_id != '2' && $inasistencia->novedad_tipo_novedad != 'B'): ?>
													<?= date_format(new DateTime($inasistencia->dias_desde), 'd/m/y') . '<br />'; ?>
												<?php endif; ?>
											<?php endforeach; ?>
										</td>
										<td style="font-size:16px; text-align: right;">
											<!-- inasistencia, dias, hasta -->
											<?php foreach ($fila->inasistencias as $inasistencia): ?>
												<?php if ($inasistencia->novedad_tipo_id != '2' && $inasistencia->novedad_tipo_novedad != 'B'): ?>
													<?= ($inasistencia->articulo === 'AA-0' ? '' : date_format(new DateTime($inasistencia->dias_hasta), 'd/m/y')) . '<br />'; ?>
												<?php endif; ?>
											<?php endforeach; ?>
										</td>
										<td style="font-size:16px; text-align: right;">
											<!-- Obligaciones cumplio -->
											<?php $total = $acumplir; ?>
											<?php foreach ($fila->inasistencias as $inasistencia): ?>
												<?php if ($inasistencia->novedad_tipo_id != '2' && $inasistencia->novedad_tipo_novedad != 'B'): ?>
													<?= $inasistencia->oblig_nocumplio . '<br />'; ?>
												<?php endif; ?>
												<?php $total -= $inasistencia->oblig_nocumplio; ?>
											<?php endforeach; ?>
										</td>
										<td style="font-size:16px; text-align: right; white-space: nowrap;">
											<!-- Obligaciones art. -->
											<?php foreach ($fila->inasistencias as $inasistencia): ?>
												<?php if ($inasistencia->novedad_tipo_id != '2' && $inasistencia->novedad_tipo_novedad != 'B'): ?>
													<?= ($inasistencia->articulo === 'AA-0' ? 'Alta' : $inasistencia->articulo) . '<br />'; ?>
												<?php endif; ?>
											<?php endforeach; ?>
										</td>
										<td style="font-size:16px; text-align: right;">
											<!-- Obligaciones total -->
											<?= max($total, 0); ?>
										</td>
										<!-- end inasistencia -->
										<td style="font-size:16px;">
											<!-- continua -->
											<?= $fila->baja_continua ? 'Si' : 'No'; ?>
										</td>
										<td style="font-size:16px;">
											<!-- baja, fecha baja-->
											<?= empty($fila->baja_fecha) ? '' : date_format(new DateTime($fila->fecha_baja), 'd/m/y'); ?>
										</td>
										<td>
											<!-- baja, motivo -->
											<?= $fila->motivo_baja; ?>
										</td>
										<td style="font-size:14px;">
											<!-- detalle funcion -->
											<?= $fila->funcion_detalle; ?>
										</td>
										<td>
											<!-- destino/ tareas-->
											<?= $fila->funcion_tarea; ?>
											<?= $fila->escuela . $fila->area; ?>
										</td>
										<td>
											<!-- carga horaria -->
											<?= $fila->funcion_cargahoraria; ?>
										</td>
										<td>
											<!-- detalle funcion, fecha desde -->
											<?= empty($fila->funcion_desde) ? '' : date_format(new DateTime($fila->funcion_desde), 'd/m/y'); ?>
										</td>
										<td>
											<!-- detalle funcion, norma legal -->
											<?= $fila->funcion_norma; ?>
										</td>
										<td>
											<!-- observaciones -->
											<?= $fila->observaciones; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if ($final): ?>
			<div style="display: block; border: 1px solid black; margin: 50px; page-break-before: always;">
				<table class="table table-condensed">
					<tr>
						<td colspan="3" style="text-align: center;"><em>La presente planilla tiene carácter de Declaración Jurada. Cualquier dato incluido en forma errónea o falsa será exclusiva responsabilidad del declarante.</em></td>
					</tr>
					<tr>
						<td><br><br><br><br><br><br><br><br><br><hr></td>
						<td><br><br><br><br><br><br><br><br><br></td>
						<td><br><br><br><br><br><br><br><br><br><hr></td>
					</tr>
					<tr>
						<td style="text-align: center;">
							<b>Sello de la Escuela</b>
						</td>
						<td></td>
						<td style="text-align: center;">
							<b>Firma del Directivo</b>
							<p><b>CUIL:</b> <?= ''// $directivo->cuil;    ?></p>
							<p><b> Apellido y Nombre:</b> <?= ''// $directivo->apellido . ', ' . $directivo->nombre;    ?></p>
							<p><b> Email:</b> <?= ''// $directivo->email;    ?></p>
							<p><b> Teléfono:</b> <?= ''//$directivo->telefono_fijo;    ?></p>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<hr>
						</td>
					</tr>
					<tr>
						<td><b>Fecha de Impresión:</b> <?= date('d/m/Y H:i:s'); ?></td>
						<td><b>Rectificativa:</b> <?= $planilla->rectificativa ? $planilla->rectificativa : ''; ?></td>
						<td><b>Cantidad de registros:</b> <?= count($filas); ?></td>
					</tr>
					<tr>
						<td colspan="3">
							<hr>
						</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align: center;">
							<small>Este documento ha sido impreso por medio del Sistema de Asistencias y Novedades desarrollado por la Subdirección de Sistemas - DGE - Mendoza.</small>
						</td>
					</tr>
				</table>
			</div>
		<?php endif; ?>
	</body>
</html>
