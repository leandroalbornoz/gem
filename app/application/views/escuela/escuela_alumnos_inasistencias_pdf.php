<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-body">
						<table class="table table-hover dt-responsive" role="grid">
							<tr>
								<td style="width:70px; text-align: center;"><img src="img/generales/logo-dge-sm.png" height="70px" width="70px"></td>
								<td style="font-size: 1.5em; font-weight: bold; text-decoration: underline; text-align: center;">DIRECCI&Oacute;N GENERAL DE ESCUELAS</td>
								<td style="font-size: 1.5em; font-weight: bold; text-decoration: underline; text-align: center;">Escuela: <?php echo $escuela->nombre_largo; ?></td>
								<td style="width:70px; text-align: center;"><img src="img/generales/logo-login.png" height="70px" width="70px"></td>
							</tr>
							<tr>
								<th colspan="4" style="font-size: 1.4em; font-weight: bold; text-decoration: underline; text-align: center;"><u>Reporte general de asistencia de alumnos del mes de <?php echo substr($this->nombres_meses[$mes], 0); ?> - <?php echo $ciclo_lectivo; ?></u></th>
							</tr>
						</table>
						<?php if (!empty($calendarios)): ?>
							<?php foreach ($calendarios as $calendario): ?>
								<?php if (!empty($calendario)): ?>
									<table class="table table-hover table-bordered dt-responsive" role="grid" style="border-collapse: collapse;">
										<thead>
											<tr>
												<th colspan="26" style="font-size: 1.4em; font-weight: bold; text-decoration: underline; text-align: center;"><u><?php echo $calendario[0]->calendario; ?> -  <?php echo $calendario[$periodo - 1]->periodo; ?>º <?php echo $calendario[$periodo - 1]->nombre_periodo; ?></u></th>
											</tr>
											<tr>
												<th>División</th>
												<th>Turno</th>
												<th style="text-align: center; width: 120px;" colspan="3">Entrados</th>
												<th style="text-align: center; width: 120px;" colspan="3">Salidos</th>
												<th style="text-align: center; width: 120px;" colspan="3">En lista</th>
												<th style="text-align: center; width: 210px;" colspan="3">Asistencia</th>
												<th style="text-align: center; width: 210px;" colspan="3">Inasistencia</th>
												<th style="text-align: center; width: 120px;" colspan="3">No inscripto</th>
												<th style="text-align: center; width: 330px; border: 2px solid black;" colspan="6">Asistencia Media</th>
											</tr>
											<tr>
												<th></th>
												<th></th>
												<th style="text-align: center; width: 40px;">V</th>
												<th style="text-align: center; width: 40px;">M</th>
												<th style="text-align: center; width: 40px;">T</th>
												<th style="text-align: center; width: 40px;">V</th>
												<th style="text-align: center; width: 40px;">M</th>
												<th style="text-align: center; width: 40px;">T</th>
												<th style="text-align: center; width: 40px;">V</th>
												<th style="text-align: center; width: 40px;">M</th>
												<th style="text-align: center; width: 40px;">T</th>
												<th style="text-align: center; width: 70px;">V</th>
												<th style="text-align: center; width: 70px;">M</th>
												<th style="text-align: center; width: 70px;">T</th>
												<th style="text-align: center; width: 70px;">V</th>
												<th style="text-align: center; width: 70px;">M</th>
												<th style="text-align: center; width: 70px;">T</th>
												<th style="text-align: center; width: 40px;">V</th>
												<th style="text-align: center; width: 40px;">M</th>
												<th style="text-align: center; width: 40px;">T</th>
												<th style="text-align: center; width: 55px; border-left: 2px solid black; border-bottom: 2px solid black;">V</th>
												<th style="text-align: center; width: 55px; border-bottom: 2px solid black;">%</th>
												<th style="text-align: center; width: 55px; border-bottom: 2px solid black;">M</th>
												<th style="text-align: center; width: 55px; border-bottom: 2px solid black;">%</th>
												<th style="text-align: center; width: 55px; border-bottom: 2px solid black;">T</th>
												<th style="text-align: center; width: 55px; border-right: 2px solid black;  border-bottom: 2px solid black;">%</th>
											</tr>
										</thead>
										<tbody>
											<?php $suma_total_v_entrados = 0; ?>
											<?php $suma_total_m_entrados = 0; ?>
											<?php $suma_total_entrados = 0; ?>
											<?php $suma_total_v_salidos = 0; ?>
											<?php $suma_total_m_salidos = 0; ?>
											<?php $suma_total_salidos = 0; ?>
											<?php $suma_total_v_lista = 0; ?>
											<?php $suma_total_m_lista = 0; ?>
											<?php $suma_total_lista = 0; ?>
											<?php $suma_total_v_asis = 0; ?>
											<?php $suma_total_m_asis = 0; ?>
											<?php $suma_total_asis = 0; ?>
											<?php $suma_total_v_inas = 0; ?>
											<?php $suma_total_m_inas = 0; ?>
											<?php $suma_total_inas = 0; ?>
											<?php $suma_total_v_ni = 0; ?>
											<?php $suma_total_m_ni = 0; ?>
											<?php $suma_total_ni = 0; ?>
											<?php $suma_total_v_am = 0; ?>
											<?php $suma_total_v_amp = 0; ?>
											<?php $suma_total_m_am = 0; ?>
											<?php $suma_total_m_amp = 0; ?>
											<?php $suma_total_t_am = 0; ?>
											<?php $suma_total_t_amp = 0; ?>
											<?php if (!empty($divisiones_escuela)): ?>
												<?php foreach ($divisiones_escuela as $division_escuela): ?>
													<?php if ($division_escuela->nombre_periodo == $calendario[0]->nombre_periodo): ?>
														<?php if ($division_escuela->periodo == $periodo): ?>
															<tr>
																<td>
																	<?php echo "$division_escuela->curso $division_escuela->division"; ?>
																</td>
																<td>
																	<?php echo "$division_escuela->turno"; ?>
																</td>
																<td>
																	<?php echo "$division_escuela->alumnos_entrados_hombres"; ?>
																</td>
																<td>
																	<?php echo "$division_escuela->alumnos_entrados_mujeres"; ?>
																</td>
																<?php $suma_total_v_entrados += $division_escuela->alumnos_entrados_hombres; ?>
																<?php $suma_total_m_entrados += $division_escuela->alumnos_entrados_mujeres; ?>
																<?php $suma_total_entrados += $division_escuela->alumnos_entrados_hombres + $division_escuela->alumnos_entrados_mujeres; ?>
																<?php $total_entrados = $division_escuela->alumnos_entrados_hombres + $division_escuela->alumnos_entrados_mujeres; ?>
																<td>
																	<?php echo "$total_entrados"; ?>
																</td>
																<td>
																	<?php echo "$division_escuela->alumnos_salidos_hombres"; ?>
																</td>
																<td>
																	<?php echo "$division_escuela->alumnos_salidos_mujeres"; ?>
																</td>
																<?php $suma_total_v_salidos += $division_escuela->alumnos_salidos_hombres; ?>
																<?php $suma_total_m_salidos += $division_escuela->alumnos_salidos_mujeres; ?>
																<?php $suma_total_salidos += $division_escuela->alumnos_salidos_hombres + $division_escuela->alumnos_salidos_mujeres; ?>
																<?php $total_salidos = $division_escuela->alumnos_salidos_hombres + $division_escuela->alumnos_salidos_mujeres; ?>
																<td>
																	<?php echo "$total_salidos"; ?>
																</td>

																<?php $total_lista_hombres = $division_escuela->alumnos_hombres_primer_dia + $division_escuela->alumnos_entrados_hombres - $division_escuela->alumnos_salidos_hombres; ?>
																<td>
																	<?php echo "$total_lista_hombres"; ?>
																</td>
																<?php $total_lista_mujeres = $division_escuela->alumnos_mujeres_primer_dia + $division_escuela->alumnos_entrados_mujeres - $division_escuela->alumnos_salidos_mujeres; ?>
																<td>
																	<?php echo "$total_lista_mujeres"; ?>
																</td>
																<?php $suma_total_v_lista += $total_lista_hombres; ?>
																<?php $suma_total_m_lista += $total_lista_mujeres; ?>
																<?php $suma_total_lista += $total_lista_mujeres + $total_lista_hombres; ?>
																<?php $total_lista = $total_lista_mujeres + $total_lista_hombres; ?>
																<td>
																	<?php echo "$total_lista"; ?>
																</td>
																<td>
																	<?php echo number_format($division_escuela->asistencia_real_hombres, 1, ',', ' '); ?>
																</td>
																<td>
																	<?php echo number_format($division_escuela->asistencia_real_mujeres, 1, ',', ' '); ?>
																</td>
																<?php $suma_total_v_asis += $division_escuela->asistencia_real_hombres; ?>
																<?php $suma_total_m_asis += $division_escuela->asistencia_real_mujeres; ?>
																<?php $suma_total_asis += $division_escuela->asistencia_real_hombres + $division_escuela->asistencia_real_mujeres; ?>
																<?php $total_asistencia = $division_escuela->asistencia_real_hombres + $division_escuela->asistencia_real_mujeres; ?>
																<td>
																	<?php echo number_format($total_asistencia, 1, ',', ' '); ?>
																</td>

																<?php $total_inasistencia_hombres = $division_escuela->asistencia_ideal_hombres - $division_escuela->asistencia_real_hombres; ?>
																<td>
																	<?php echo number_format($total_inasistencia_hombres, 1, ',', ' '); ?>
																</td>
																<?php $total_inasistencia_mujeres = $division_escuela->asistencia_ideal_mujeres - $division_escuela->asistencia_real_mujeres; ?>
																<td>
																	<?php echo number_format($total_inasistencia_mujeres, 1, ',', ' '); ?>
																</td>
																<?php $suma_total_v_inas += $total_inasistencia_hombres; ?>
																<?php $suma_total_m_inas += $total_inasistencia_mujeres; ?>
																<?php $suma_total_inas += $total_inasistencia_hombres + $total_inasistencia_mujeres; ?>
																<?php $total_inasistencia = $total_inasistencia_hombres + $total_inasistencia_mujeres; ?>
																<td>
																	<?php echo number_format($total_inasistencia, 1, ',', ' '); ?>
																</td>
																<td>
																	<?php echo number_format($division_escuela->alumnos_nc_hombres, 0); ?>
																</td>
																<td>
																	<?php echo number_format($division_escuela->alumnos_nc_mujeres, 0); ?>
																</td>
																<?php $suma_total_v_ni += $division_escuela->alumnos_nc_hombres; ?>
																<?php $suma_total_m_ni += $division_escuela->alumnos_nc_mujeres; ?>
																<?php $suma_total_ni += $division_escuela->alumnos_nc_hombres + $division_escuela->alumnos_nc_mujeres; ?>
																<?php $total_dias_nc = $division_escuela->alumnos_nc_hombres + $division_escuela->alumnos_nc_mujeres; ?>
																<td>
																	<?php echo number_format($total_dias_nc, 0); ?>
																</td>
																<?php $asistencia_media_hombres = ($division_escuela->asistencia_ideal_hombres - $total_inasistencia_hombres) / $division_escuela->dias ?>
																<td style="border-left: 2px solid black;">
																	<?php echo number_format($asistencia_media_hombres, 2, ',', ' '); ?>
																</td>
																<td>
																	<?php echo number_format($division_escuela->asistencia_media_hombres, 2, ',', ' '); ?>
																</td>
																<?php $asistencia_media_mujeres = ($division_escuela->asistencia_ideal_mujeres - $total_inasistencia_mujeres) / $division_escuela->dias; ?>
																<td>
																	<?php echo number_format($asistencia_media_mujeres, 2, ',', ' '); ?>
																</td>
																<td>
																	<?php echo number_format($division_escuela->asistencia_media_mujeres, 2, ',', ' '); ?>
																</td>
																<?php $total_asistencia_media = $asistencia_media_hombres + $asistencia_media_mujeres; ?>
																<td>
																	<?php echo number_format($total_asistencia_media, 2, ',', ' '); ?> 
																</td>
																<?php if (($division_escuela->asistencia_ideal_hombres + $division_escuela->asistencia_ideal_mujeres) != '0'): ?>
																	<?php $total_asistencia_media_porcentaje = ($total_asistencia / ($division_escuela->asistencia_ideal_hombres + $division_escuela->asistencia_ideal_mujeres)) * 100; ?>
																<?php endif; ?>
																<td style="border-right: 2px solid black;">
																	<?php if (($division_escuela->asistencia_ideal_hombres + $division_escuela->asistencia_ideal_mujeres) != '0'): ?>
																		<?php echo number_format($total_asistencia_media_porcentaje, 2, ',', ' '); ?>
																	<?php else: ?>
																		<?php echo "0,00"; ?>
																	<?php endif; ?>
																</td>
																<?php $suma_total_v_am += $asistencia_media_hombres; ?>
																<?php $suma_total_v_amp += $division_escuela->asistencia_media_hombres; ?>
																<?php $suma_total_m_am += $asistencia_media_mujeres; ?>
																<?php $suma_total_m_amp += $division_escuela->asistencia_media_mujeres; ?>
																<?php $suma_total_t_am += $total_asistencia_media; ?>
																<?php $suma_total_t_amp += $total_asistencia_media_porcentaje; ?>
															</tr>
														<?php endif; ?>
													<?php endif; ?>
												<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td colspan="27">
														--- No hay datos para mostrar --- 
													</td>
												</tr>
											<?php endif; ?>
											<tr>
												<td colspan="2">Totales generales</td>
												<td><?php echo $suma_total_v_entrados; ?></td>
												<td><?php echo $suma_total_m_entrados; ?></td>
												<td><?php echo $suma_total_entrados; ?></td>
												<td><?php echo $suma_total_v_salidos; ?></td>
												<td><?php echo $suma_total_m_salidos; ?></td>
												<td><?php echo $suma_total_salidos; ?></td>
												<td><?php echo $suma_total_v_lista; ?></td>
												<td><?php echo $suma_total_m_lista; ?></td>
												<td><?php echo $suma_total_lista; ?></td>
												<td><span style="white-space: nowrap"><?php echo number_format($suma_total_v_asis, 1, ',', ' '); ?></span></td>
												<td><?php echo number_format($suma_total_m_asis, 1, ',', ' '); ?></td>
												<td><?php echo number_format($suma_total_asis, 1, ',', ' '); ?></td>
												<td><?php echo number_format($suma_total_v_inas, 1, ',', ' '); ?></td>
												<td><?php echo number_format($suma_total_m_inas, 1, ',', ' '); ?></td>
												<td><?php echo number_format($suma_total_inas, 1, ',', ' '); ?></td>
												<td><?php echo number_format($suma_total_v_ni, 0, ',', ' '); ?></td>
												<td><?php echo number_format($suma_total_m_ni, 0, ',', ' '); ?></td>
												<td><?php echo number_format($suma_total_ni, 0, ',', ' '); ?></td>
												<td style="border-left: 2px solid black; border-bottom: 2px solid black;"><?php echo number_format($suma_total_v_am, 2, ',', ' '); ?></td>
												<td style="border-bottom: 2px solid black;">
													<?php if (($suma_total_v_asis + $suma_total_v_inas) != '0'): ?>
														<?php $suma_total_v_amp = ($suma_total_v_asis / ($suma_total_v_asis + $suma_total_v_inas)) * 100; ?>
														<?php echo number_format($suma_total_v_amp, 2, ',', ' '); ?>
													<?php else: ?>
														<?php echo "0,00"; ?>
													<?php endif; ?>
												</td>
												<td style="border-bottom: 2px solid black;"><?php echo number_format($suma_total_m_am, 2, ',', ' '); ?></td>
												<td style="border-bottom: 2px solid black;">
													<?php if (($suma_total_m_asis + $suma_total_m_inas) != '0'): ?>
														<?php $suma_total_m_amp = ($suma_total_m_asis / ($suma_total_m_asis + $suma_total_m_inas)) * 100; ?>
														<?php echo number_format($suma_total_m_amp, 2, ',', ' '); ?>
													<?php else: ?>
														<?php echo "0,00"; ?>
													<?php endif; ?>
												</td>
												<td style="border-bottom: 2px solid black;"><?php echo number_format($suma_total_t_am, 2, ',', ' '); ?></td>
												<td style="border-right: 2px solid black; border-bottom: 2px solid black;">
													<?php if (($suma_total_asis + $suma_total_inas) != '0'): ?>
														<?php $suma_total_t_amp = ($suma_total_asis / ($suma_total_asis + $suma_total_inas)) * 100; ?>
														<?php echo number_format($suma_total_t_amp, 2, ',', ' '); ?>
													<?php else: ?>
														<?php echo "0,00"; ?>
													<?php endif; ?>
												</td>
											</tr>
										</tbody>
									</table>
									<br>
								<?php else: ?>
									<div class="alert alert-info alert-dismissable">
										<h4><i class="icon fa fa-ban"></i> Registro no encontrado!</h4> No posee registro de asistencias de alumnos cargados.			
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php else: ?>
							<div class="alert alert-info alert-dismissable">
								<h4><i class="icon fa fa-ban"></i> Registro no encontrado!</h4> No posee registro de asistencias de alumnos cargados.			
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>