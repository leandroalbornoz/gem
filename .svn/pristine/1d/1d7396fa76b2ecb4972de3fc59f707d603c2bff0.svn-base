<html lang="es">
	<head>
		<base href="<?php echo base_url(); ?>" />
		<meta charset="utf-8">
	</head>
	<body>
		<?php $meses = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'); ?>
		<?php $primera = TRUE; ?>
		<?php if (!empty($filas)): ?>
			<?php foreach ($filas as $filas_tipo => $contenido): ?>
				<?php if (!empty($contenido)): ?>
				<?php if (!$primera): ?>
					<div style="page-break-before: always;"></div>
				<?php else: ?>
					<?php $primera = FALSE; ?>
				<?php endif; ?>
					<div class="tb-cab-index">
						<div class="row">
							<div class="col-lg-12" id="cabeceraEscuela">
								<table class="table table-bordered table-condensed table-responsive table-striped tabla-header">
									<tr>
										<td rowspan="5" style="text-align: center;"><img src="img/logomza.jpg"></td>
										<td colspan="6"><h4><b>PLANILLA DE ASISTENCIA DE PERSONAL <?= strtoupper($planilla->planilla_tipo); ?></b> - <b>MES:</b> <?= (int) substr($planilla->ames, -2); ?>  <b>AÑO:</b> <?= substr($planilla->ames, 0, 4); ?>  <?= $planilla->rectificativa ? " <b>RECTIFICATIVA:</b> $planilla->rectificativa" : ''; ?> </h4></td>
									</tr>
									<tr>
										<td colspan="6" class="text-center"><h4><b><?= $filas_tipo ?></b></h4></td>
									</tr>
									<tr>
										<td colspan="2"><b>Área</b></td>
										<td colspan="2"><?= $area->descripcion; ?></td>
										<td><b>Código Área</b></td>
										<td><?= $area->codigo; ?></td>
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
											<th colspan="28" class="text-center"><?= $filas_tipo; ?></th>
										</tr>
										<tr>
											<th rowspan="3">CUIL - Liqui.</th>
											<th style="width:150px;" rowspan="">Apellido - Nombre</th>
											<th rowspan="3">Revista</th>
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
											<th rowspan="2">Fecha Nacimiento</th>
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
											<th>Desde</th>
											<th>Hasta</th>
											<th>No Cumplió</th>
											<th>Art.</th>
											<th>Total</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($contenido as $novedad): ?>
											<tr style="font-size:16px;">
												<td style="font-size:18px;">
													<?= $novedad->cuil ? $novedad->cuil . '-' . substr($novedad->liquidacion, -2) : $novedad->liquidacion; ?>
												</td>
												<td style="font-size:16px;">
													<?= htmlspecialchars("$novedad->apellido, $novedad->nombre") . '<br>' . (empty($novedad->fecha_nacimiento) ? '' : date_format(new DateTime($novedad->fecha_nacimiento), 'd/m/Y')); ?>
												</td>
												<td>
													<?= substr($novedad->situacion_revista, 0, 4); ?>
												</td>
												<td style="font-size:16px;"><?= $novedad->regimen_codigo; ?></td>
												<td style="font-size:16px;">1</td>
												<td style="font-size:16px; text-align: center;"><?= $novedad->regimen_tipo_id === '1' ? $novedad->puntos : $novedad->carga_horaria; ?></td>
												<td style="font-size:16px; text-align: right;"><?= $novedad->regimen_tipo_id === '1' ? '30' : $novedad->carga_horaria * 4; ?></td>
												<td style="font-size:16px;">
													<?= empty($novedad->fecha_alta) ? '' : date_format(new DateTime($novedad->fecha_alta), 'd/m/y'); ?>
												</td>
												<!-- begin inasistencia -->
												<td style="font-size:16px; text-align: right;">
													<!-- inasistencia, dias, desde -->
													<?= date_format(new DateTime($novedad->fecha_desde), 'd/m/y') . '<br />'; ?>
												</td>
												<td style="font-size:16px; text-align: right;">
													<!-- inasistencia, dias, hasta -->
													<?= "$novedad->articulo-$novedad->inciso" === 'AA-0' ? '' : date_format(new DateTime($novedad->fecha_hasta), 'd/m/y') . '<br />'; ?>
												</td>
												<td style="font-size:16px; text-align: right;">
													<!-- Obligaciones cumplio -->
													<?= ($novedad->regimen_tipo_id === '1' ? $novedad->dias : $novedad->obligaciones) . '<br />'; ?>
												</td>
												<td style="font-size:16px; text-align: right; white-space: nowrap;">
													<!-- Obligaciones art. -->
													<?= ("$novedad->articulo-$novedad->inciso" === 'AA-0' ? 'Alta' : "$novedad->articulo-$novedad->inciso") . '<br />'; ?>
												</td>
												<td style="font-size:16px; text-align: right;">
													<!-- Obligaciones total -->
												</td>
												<!-- end inasistencia -->
												<td style="font-size:16px;">
													<!-- continua -->
													<?= empty($novedad->fecha_baja) ? 'Si' : 'No'; ?>
												</td>
												<td style="font-size:16px;">
													<!-- baja, fecha baja-->
													<?= empty($novedad->fecha_baja) ? '' : date_format(new DateTime($novedad->fecha_baja), 'd/m/y'); ?>
												</td>
												<td>
													<!-- baja, motivo -->
													<?= $novedad->motivo_baja; ?>
												</td>
												<td style="font-size:14px;">
													<!-- detalle funcion -->
													<?= $novedad->funcion_detalle; ?>
												</td>
												<td>
													<!-- destino/ tareas-->
													<?= $novedad->funcion_tarea; ?>
													<?= $novedad->funcion_destino; ?>
												</td>
												<td>
													<!-- carga horaria -->
													<?= $novedad->funcion_cargahoraria; ?>
												</td>
												<td>
													<!-- detalle funcion, fecha desde -->
													<?= empty($novedad->funcion_desde) ? '' : date_format(new DateTime($novedad->funcion_desde), 'd/m/y'); ?>
												</td>
												<td>
													<!-- detalle funcion, norma legal -->
													<?= $novedad->funcion_norma; ?>
												</td>
												<td>
													<!-- observaciones -->
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if ($planilla->fecha_cierre): ?>
			<div style="page-break-before: always;"></div>
			<div style="display: block; border: 1px solid black; margin: 50px;">
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
							<p><b>CUIL:</b> <?= $autoridad->cuil; ?></p>
							<p><b> Apellido y Nombre:</b> <?= $autoridad->apellido . ', ' . $autoridad->nombre; ?></p>
							<p><b> Email:</b> <?= $autoridad->email; ?></p>
							<p><b> Teléfono:</b> <?= $autoridad->telefono_fijo; ?></p>
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
						<td><b>Cantidad de registros:</b> <?= (!empty($novedades_creadas) ? count($novedades_creadas) : 0) + (!empty($novedades_eliminadas) ? count($novedades_eliminadas) : 0); ?></td>
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