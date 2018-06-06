<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-body">
						<table style="width: 100%;">
							<tr>
								<td style="width:70px; text-align: center;"><img style="font-family: sans-serif; font-size:20px; color: #3c8dbc;" alt="Logo DGE" src="<?php echo BASE_URL; ?>img/generales/logo-dge-sm.jpg" height="70" width="70"></td>
								<td style="font-size: 1.5em; font-weight: bold; text-decoration: underline; text-align: center;">DECLARACIÓN JURADA DE PLANTA DE CELADORES</td>
								<td style="width:70px; text-align: center;"><img style="font-family: sans-serif; font-size:20px; color: #3c8dbc;" alt="Logo GEM" src="<?php echo BASE_URL; ?>img/generales/logo-login.jpg" height="70" width="70"></td>
							</tr>
						</table>
						<br>
						<div>
							<table class="table table-hover table-condensed">
								<tbody>
									<tr>
										<td>
											<table >
												<tr>
													<td><b>Escuela N°: </b> <?php echo "$escuela->numero/$escuela->anexo"; ?><br></td>
												</tr>	
												<tr>
													<td><b>Anexos: </b> <?php echo $escuela->anexos; ?><br></td>
												</tr>	
												<tr>
													<td><b>Domicilio: </b> <?php echo "$escuela->calle $escuela->calle_numero, $escuela->barrio $escuela->manzana $escuela->casa"; ?><br></td>
												</tr>	
												<tr>
													<td><b>Departamento: </b> <?php echo $escuela->departamento; ?><br></td>
												</tr>	
												<tr>
													<td><b>Distrito: </b> <?php echo $escuela->localidad; ?><br></td>
												</tr>
												<tr>
													<td><b>Turno: </b> <?php echo $escuela->turno; ?><br> </td>
												</tr>
											</table>
										</td>
										<td>
											<table>
												<tbody>
													<tr>
														<td><b>N° Secciones: </b> <?php echo $escuela->divisiones + $escuela->divisiones_anexo; ?><br></td>
													</tr>
													<?php if (count($anexos) > 1): ?>
														<tr>
															<td>
																<?php foreach ($anexos as $anexo): ?>
																	<?php if ($anexo->anexo === '0'): ?>
																		&nbsp;&nbsp;&nbsp;Sede: <?php echo $anexo->divisiones; ?><br>
																	<?php else: ?>
																		&nbsp;&nbsp;&nbsp;Anexo <?php echo $anexo->anexo; ?>: <?php echo $anexo->divisiones; ?><br>
																	<?php endif; ?>
																<?php endforeach; ?>
															</td>
														</tr>	
													<?php endif; ?>
													<tr>
														<td><b>Matrícula total: </b> <?php echo $escuela->matricula + $escuela->matricula_anexo; ?><br></td>
													</tr>	
													<?php if (count($anexos) > 1): ?>
														<tr>
															<td>
																<?php foreach ($anexos as $anexo): ?>
																	<?php if ($anexo->anexo === '0'): ?>
																		&nbsp;&nbsp;&nbsp;Sede: <?php echo $anexo->alumnos; ?><br>
																	<?php else: ?>
																		&nbsp;&nbsp;&nbsp;Anexo <?php echo $anexo->anexo; ?>: <?php echo $anexo->alumnos; ?><br>
																	<?php endif; ?>
																<?php endforeach; ?>
															</td>
														</tr>
													<?php endif; ?>
												</tbody>
											</table>
										</td>
										<td>
											<table>
												<tbody>
													<tr>
														<td><b>Zona: </b> <?php echo $escuela->zona; ?><br></td>
													</tr>
													<tr>
														<td><b>Teléfono y/o Cel: </b> <?php echo $escuela->telefono; ?><br></td>
													</tr>
													<tr>
														<td><b>E-mail: </b> <?php echo $escuela->email; ?><br></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<br>
						<?php if (!empty($celadores)): ?>
							<div class="row">
								<div class="col-xs-12">
									<div class="box">
										<div class="box-header text-center">
											<h3 class="box-title">Nómina de celadores y funciones</h3>
										</div>
										<div class="box-body table-responsive no-padding">
											<table class="table table-hover table-bordered table-condensed">
												<thead>
													<tr>
														<?php if (count($anexos) > 1): ?>
															<th>Anexo</th>
														<?php endif; ?>
														<th>Persona</th>
														<th>Cuil</th>
														<th>F.nac</th>
														<th>S.R</th>
														<th>Horarios</th>
														<th>Tareas</th>
														<th>Concepto</th>
														<th>Estudios</th>
														<th>Fecha de alta</th>
														<th style="white-space: nowrap">Reemplaza a</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($celadores_horario as $celador): ?>
														<tr>
															<?php if (count($anexos) > 1): ?>
																<td><?php echo $celador->anexo === '0' ? 'Sede' : "Anexo $celador->anexo"; ?></td>
															<?php endif; ?>
															<td><?php echo "$celador->persona"; ?></td>
															<td style="white-space: nowrap"><?php echo $celador->cuil; ?></td>
															<td style="white-space: nowrap"><?php echo (new DateTime($celador->fecha_nacimiento))->format('d/m/Y'); ?></td>
															<td style="white-space: nowrap"><?php echo $celador->situacion_revista; ?></td>
															<td>
																<table style="border:none; border-collapse: collapse">
																	<?php foreach ($celador->horario as $dia => $horario_dia): ?>
																		<tr style="border:none; border-collapse: collapse">
																			<td style="border:none; border-collapse: collapse;padding: 1px"><?php echo $dia; ?>:</td>
																			<td style="border:none; border-collapse: collapse; text-align: right;padding: 1px;">
																				<?php foreach ($horario_dia as $horario): ?>

																					<?php echo substr($horario->hora_desde, 0, -3); ?>-<?php echo substr($horario->hora_hasta, 0, -3); ?><br>
																				<?php endforeach; ?>
																			</td>
																		</tr>
																	<?php endforeach; ?>
																</table>
															</td>
															<td><?php echo $celador->tarea; ?></td>
															<td><?php echo $celador->celador_concepto; ?></td>
															<td><?php echo $celador->nivel_estudio; ?></td>
															<td><?php echo (new DateTime($celador->fecha_alta))->format('d/m/Y') ?></td>
															<td><?php echo $celador->reemplazado; ?></td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<div class="row">
							<div class="col-md-12">
								<div class="box" style="width: 30%">
									<table class="table table-bordered">
										<tbody>
											<?php if (!empty($caracteristicas)): ?>
												<?php foreach ($caracteristicas as $caracteristica): ?>
													<tr style="width: 50%">
														<td><b><?php echo $caracteristica->descripcion; ?></b></td>
														<td><?php echo $caracteristica->valor; ?></td>
													</tr>
												<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td>-- Ninguna característica encontrada --</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	</section>
</div>