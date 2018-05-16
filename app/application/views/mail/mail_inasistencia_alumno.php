<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
	</head>
	<body>
		<table style="font-family: sans-serif;">
			<tr>
				<td>
					<table style="width: 100%;">
						<tr>
							<td style="width:70px; text-align: center;"><img style="font-family: sans-serif; font-size:20px; color: #3c8dbc;" alt="Logo DGE" src="<?php echo BASE_URL; ?>img/generales/logo-dge-sm.png" height="70" width="70"></td>
							<td style="font-size: 1.5em; font-weight: bold; text-decoration: underline; text-align: center;">DIRECCI&Oacute;N GENERAL DE ESCUELAS</td>
							<td style="width:70px; text-align: center;"><img style="font-family: sans-serif; font-size:20px; color: #3c8dbc;" alt="Logo GEM" src="<?php echo BASE_URL; ?>img/generales/logo-login.png" height="70" width="70"></td>
						</tr>
						<tr>
							<td colspan="3" style="font-size: 1.5em; font-weight: bold; text-decoration: underline; text-align: center;">Asistencia de alumnos</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td style="text-align: center;">En el marco de modernización del Gobierno de Mendoza y con el objetivo de fortalecer los vínculos entre los padres y la entidad educativa, es que la DGE le envía el reporte de asistencia escolar de su hijo según la información cargada por la escuela.</td></tr>
			<!--<tr><td style="font-size: 1.2em; font-weight: bold; text-decoration: underline; text-align: center;">Asistencia del alumno correspondiente al mes de <?php // echo $this->nombres_meses[substr($mes, 4, 2)];      ?> del 2017</td></tr>-->
			<tr><td>&nbsp;</td></tr>
			<tr><td><span style="font-weight: bold; text-decoration: underline;">Alumno</span>: <?php echo "$alumno->apellido, $alumno->nombre"; ?></td></tr>
			<tr><td><span style="font-weight: bold; text-decoration: underline;">Documento</span>: <?php echo "$alumno->documento_tipo $alumno->documento"; ?></td></tr>
			<tr><td><span style="font-weight: bold; text-decoration: underline;">Escuela</span>: <?php echo "$alumno_division->escuela"; ?></td></tr>
			<tr><td><span style="font-weight: bold; text-decoration: underline;">Curso</span>: <?php echo "$alumno_division->curso $alumno_division->division"; ?></td></tr>
			<tr><td><span style="font-weight: bold; text-decoration: underline;">Ingreso a curso</span>: <?php echo (new DateTime($alumno_division->fecha_desde))->format('d/m/Y'); ?></td></tr>
			<?php if (isset($alumno_division->fecha_hasta)): ?>
				<tr><td><span style="font-weight: bold; text-decoration: underline;">Egreso del curso</span>: <?php echo (new DateTime($alumno_division->fecha_hasta))->format('d/m/Y'); ?></td></tr>
			<?php endif; ?>
			<tr>
				<td>
					<table border="1" style="width: 100%;">
						<thead>
							<tr>
								<th style="text-align: center; background-color: #3c8dbc; color: white;" rowspan="2">Periodo</th>
								<th style="text-align: center; background-color: #3c8dbc; color: white;" rowspan="2">Mes</th>
								<th style="text-align: center; background-color: #3c8dbc; color: white;" colspan="2">Inasistencias</th>
								<th style="text-align: center; background-color: #3c8dbc; color: white;" rowspan="2">Total<br>Inasistencias</th>
								<th style="text-align: center; background-color: #3c8dbc; color: white;" rowspan="2">Total<br>Asistencias</th>
								<th style="text-align: center; background-color: #3c8dbc; color: white;" rowspan="2">% de<br>Asistencia</th>
								<th style="text-align: center; background-color: #3c8dbc; color: white;" rowspan="2">D&iacute;as h&aacute;biles<br>de cursado </th>
							</tr>
							<tr>
								<th style="text-align: center; width: 50px; background-color: #3c8dbc; color: white;" title="Justificadas">Justificadas</th>
								<th style="text-align: center; width: 50px;	background-color: #3c8dbc; color: white;" title="Injustificadas">Injustificadas</th>
							</tr>
						</thead>
						<tbody>
							<?php $tiene_inasistencia_diaria = FALSE; ?>
							<?php foreach ($asistencia_mensual as $i_periodo): ?>
								<?php foreach ($i_periodo as $i_mes): ?>
									<?php if ($i_mes->resumen_mensual === 'No') $tiene_inasistencia_diaria = TRUE; ?>
									<tr>
										<td style="text-align: center;"><?php echo $this->nombres_meses[substr($i_mes->mes, 4, 2)]; ?></td>
										<td style="text-align: center;"><?php echo "{$i_mes->periodo}° $i_mes->nombre_periodo"; ?></td>
										<td style="text-align: center;"><?php echo $i_mes->falta_j; ?></td>
										<td style="text-align: center;"><?php echo $i_mes->falta_i; ?></td>
										<td style="text-align: center;"><?php echo $i_mes->falta_j + $i_mes->falta_i; ?></td>
										<td style="text-align: center;"><?php echo $i_mes->asistencia; ?></td>
										<td style="text-align: center;"><?php echo number_format(($i_mes->asistencia * 100) / ($i_mes->asistencia + $i_mes->falta_j + $i_mes->falta_i), 1, '.', ''); ?></td>
										<td style="text-align: center;"><?php echo $i_mes->dias; ?></td>
									</tr>
								<?php endforeach; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<?php foreach ($asistencia_mensual as $i_periodo): ?>
						<?php foreach ($i_periodo as $i_mes): ?>
							<?php if ($i_mes->resumen_mensual === 'No'): ?>
								<?php $dias_semana = array('Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S&aacute;'); ?>
								<?php $fecha_ini_m = new DateTime($i_mes->mes . '01'); ?>
								<?php $fecha_ini_m = max(array(new DateTime($i_mes->inicio), $fecha_ini_m)); ?>
								<?php $fecha_fin_m = new DateTime($i_mes->mes . '01 +1 month -1 day'); ?>
								<?php $fecha_fin_m = min(array(new DateTime($i_mes->fin), $fecha_fin_m)); ?>
								<?php $dia_m_interval = DateInterval::createFromDateString('1 day'); ?>
								<p style="font-size: 1.1em; font-weight: bold; text-decoration: underline; text-align: center;">Asistencia detallada - <?php echo $this->nombres_meses[substr($i_mes->mes, 4, 2)]; ?> - <?php echo "{$i_mes->periodo}° $i_mes->nombre_periodo"; ?> (<?php echo $fecha_ini_m->format('d/m/y'); ?> al <?php echo (new DateTime($fecha_fin_m->format('Y-m-d')))->format('d/m/y'); ?>)</p>
								<table style="width: 100%; font-size: 0.9em;" border="1">
									<thead>
										<tr>
											<?php foreach ($dias_semana as $dia_semana): ?>
												<th style="width: 80px; background-color: #3c8dbc; color: white;"><?php echo $dia_semana; ?></th>
											<?php endforeach; ?>
										</tr>
									</thead>
									<tbody>
										<?php $primer_dia_semana_m = $fecha_ini_m->format('w'); ?>
										<?php $fecha_dia_semana = new DateTime($fecha_ini_m->format('Y-m-d')); ?>
										<tr>
											<?php foreach ($dias_semana as $dia_semana_id => $dia_semana): ?>
												<td style="height: 30px; width: 100px;">
													<?php if ($dia_semana_id >= $primer_dia_semana_m): ?>
														<?php echo $fecha_dia_semana->format('d'); ?>&thinsp;
														<?php foreach ($contraturnos as $contraturno): ?>
															<?php if (isset($asistencia_diaria[$i_mes->periodo][$i_mes->mes][$fecha_dia_semana->format('Y-m-d')][$contraturno])): ?>
																<?php echo icono_asistencia($asistencia_diaria[$i_mes->periodo][$i_mes->mes][$fecha_dia_semana->format('Y-m-d')][$contraturno]); ?>
															<?php endif; ?>
														<?php endforeach; ?>
														<?php $fecha_dia_semana->add($dia_m_interval); ?>
													<?php endif; ?>
												</td>
											<?php endforeach; ?>
										</tr>
										<tr>
											<?php while ($fecha_dia_semana <= $fecha_fin_m): ?>
												<td style="height: 30px; width: 100px;">
													<?php echo $fecha_dia_semana->format('d'); ?>&thinsp;
													<?php foreach ($contraturnos as $contraturno): ?>
														<?php if (isset($asistencia_diaria[$i_mes->periodo][$i_mes->mes][$fecha_dia_semana->format('Y-m-d')][$contraturno])): ?>
															<?php echo icono_asistencia($asistencia_diaria[$i_mes->periodo][$i_mes->mes][$fecha_dia_semana->format('Y-m-d')][$contraturno]); ?>
														<?php endif; ?>
													<?php endforeach; ?>
													<?php $fecha_dia_semana->add($dia_m_interval); ?>
												</td>
												<?php if ($fecha_dia_semana->format('w') === '0'): ?>
													<?php echo '</tr><tr>'; ?>
												<?php endif; ?>
											<?php endwhile; ?>
										</tr>
									</tbody>
								</table>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td>
					<?php if ($tiene_inasistencia_diaria): ?>
						<table style="width: 100%; font-size: 0.8em;" border="0">
							<tbody>
								<tr>
									<td>
										<p><?php echo icono('A', '#fff', '#dd4b39', '#9e3528'); ?> Ausente, Justificado</p>
										<p><?php echo icono('A<sub>i</sub>', '#fff', '#dd4b39', '#9e3528'); ?> Ausente, Injustificado</p>
										<p><?php echo icono('A', '#000', '#fff', '#008749'); ?> Ausente, No computable</p>
										<p><?php echo icono('P', '#fff', '#dd4b39', '#9e3528'); ?> Ausente por tardanza</p>
										<p><?php echo icono('P', '#000', '#fff', '#008749'); ?> Presente</p>
									</td>
									<td>
										<p><?php echo icono('T', '#fff', '#dd4b39', '#9e3528'); ?> Tardanza, Justificada</p>
										<p><?php echo icono('T<sub>i</sub>', '#fff', '#dd4b39', '#9e3528'); ?> Tardanza, Injustificada</p>
										<p><?php echo icono('R', '#fff', '#dd4b39', '#9e3528'); ?> Retira antes, Justificado</p>
										<p><?php echo icono('R<sub>i</sub>', '#fff', '#dd4b39', '#9e3528'); ?> Retira antes, Injustificado</p>
										<p><?php echo icono('-', '#000', '#fff', '#999'); ?> No pertene al curso el d&iacute;a de la fecha</p>
									</td>
								</tr>
							</tbody>
						</table>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td>
					<p style="text-align: center;">
						<span style="font-size:12px;">
							<span style="font-family:tahoma,geneva,sans-serif;">
								<strong>Subdirecci&oacute;n de Tecnolog&iacute;as de la Informaci&oacute;n - Direcci&oacute;n General de Escuelas - G.E.M. - Mendoza - Argentina - <?= date('Y'); ?></strong>
							</span>
						</span>
					</p>
					<p style="text-align: center;">
						<span style="font-size:9px;">
							<span style="font-family:tahoma,geneva,sans-serif;">
								Este es un mail legal, libre de virus y contiene informaci&oacute;n que consideramos de su inter&eacute;s, la libre distribuci&oacute;n de este email est&aacute; autorizada por tratarse de prop&oacute;sitos de informaci&oacute;n.
							</span>
						</span>
					</p>
					<p style="text-align: center;">
						<span style="font-size:12px;">
							<span style="font-family:tahoma,geneva,sans-serif;">
							</span>
							Si Ud. no desea recibir este correo, por favor haga click en el siguiente link para no seguir recibiendo correos de la plataforma<br>
							<strong><a href="<?php echo BASE_URL; ?>mail/remover/<?php echo $alumno->id; ?>/<?php echo $clave; ?>" target="_blank">DESUSCRIBIRME</a></strong>
						</span>
					</p>
				</td>
			</tr>
		</table>
</html>
