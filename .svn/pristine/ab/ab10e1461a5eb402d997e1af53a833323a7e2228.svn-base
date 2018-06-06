<style>
	.datepicker,.datepicker>datepicker-days>.table-condensed{
		margin:0 auto;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Asistencia y Novedades <label class="label label-default"><?php echo $mes; ?></label>
			<a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#datepicker_modal"><i class="fa fa-refresh"></i> Cambiar</a>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="asisnov/listar/<?php echo "$escuela->id/$mes_id"; ?>">Asistencia y Novedades</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="escuela/ver/<?php echo $escuela->id; ?>">
							<i class="fa fa-search"></i> Ver escuela
						</a>
						<a class="btn btn-app btn-app-zetta" href="servicio/listar/<?php echo "$escuela->id/$mes_id"; ?>">
							<i class="fa fa-bookmark"></i> Servicios
						</a>
						<a class="btn btn-app btn-app-zetta" href="servicio_novedad/listar/<?php echo "$escuela->id/$mes_id"; ?>">
							<i class="fa fa-calendar"></i> Novedades
						</a>
						<a class="btn btn-app btn-app-zetta" href="servicio_novedad/listar/<?php echo "$escuela->id/$mes_id/1"; ?>">
							<i class="fa fa-calendar"></i> Novedades a confirmar
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="asisnov/index/<?php echo "$escuela->id/$mes_id"; ?>">
							<i class="fa fa-print"></i> Asis y Nov
						</a>
						<div class="btn-group pull-right" role="group" style="margin-left: 5px;">
							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-external-link"></i> Otros accesos <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								<li><a class="dropdown-item btn-default"  href="cargo/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-users"></i> Cargos</a></li>
								<li><a class="dropdown-item btn-default" href="alumno/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-users"></i> Alumnos</a></li>
								<li><a class="dropdown-item btn-default" href="division/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-tasks"></i> Cursos y Divisiones</a></li>
								<li><a class="dropdown-item btn-default" href="escuela_carrera/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-book"></i>Carreras</a></li>
							</ul>
						</div>
						<hr style="margin: 10px 0;">
						<div class="row">
							<div class="col-xs-6">
								<div class="panel well well-sm">
									<div class="box-header with-border">
										<h3 class="box-title">Planilla Suplementaria</h3>
									</div>
									<div class="box-body">
										<a class="btn bg-green btn-app btn-app-zetta" href="files/planillas/planilla_suplementaria.pdf" target="_blank">
											<i class="fa fa-print"></i> Imprimir
										</a>
									</div>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="panel well well-sm">
									<div class="box-header with-border">
										<h3 class="box-title">Planilla Celadores (Contraturno)</h3>
									</div>
									<div class="box-body">
										<a class="btn bg-green btn-app btn-app-zetta" href="files/planillas/planilla_celador.pdf" target="_blank">
											<i class="fa fa-print"></i> Imprimir
										</a>
									</div>
								</div>
							</div>
							<?php if (!empty($alertas)): ?>
								<div class="col-sm-12">
									<h3>Por favor complete las siguientes alertas para poder trabajar con la planilla.</h3>
									<ul class="list-group">
										<?php foreach ($alertas as $alerta): ?>
											<li class="list-group-item">
												<span class="label label-danger"><?php echo $alerta->value; ?></span>
												<?php echo $alerta->label; ?>
												<a href="<?php echo $alerta->url; ?>" target="_blank" class="btn btn-xs btn-primary"><i class="fa fa-check"> Corregir</i></a>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php else: ?>
								<div class="col-xs-6">
									<div class="panel well well-sm">
										<div class="box-header with-border">
											<h3 class="box-title">Planilla Titulares</h3>
										</div>
										<div class="box-body">
											<?php if (!empty($planillas[1])) : ?>
												<?php if (empty($planillas_abiertas[1])): ?>
													<a class="btn bg-green btn-app btn-app-zetta" href="asisnov/imprimir/1/<?php echo "$escuela->id/$mes_id/2"; ?>" target="_blank">
														<i class="fa fa-print" id="btn-imprimir-t-f"></i> Presentacion
													</a>
													<?php if ($edicion): ?>
														<a class="btn bg-blue btn-app btn-app-zetta" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="asisnov/modal_rectificativa/1/<?php echo "$escuela->id/$mes_id"; ?>">
															<i class="fa fa-plus" id="btn-rectificativa-r"></i> Rectificativa
														</a>
													<?php endif; ?>
												<?php else: ?>
													<a class="btn bg-yellow btn-app btn-app-zetta" href="asisnov/imprimir/1/<?php echo "$escuela->id/$mes_id/1"; ?>" target="_blank">
														<i class="fa fa-print" id="btn-imprimir-t-r"></i> Revisión
													</a>
													<?php if ($edicion): ?>
														<a class="btn bg-red btn-app btn-app-zetta" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="asisnov/modal_cerrar/<?php echo $planillas_abiertas[1][0]->id; ?>">
															<i class="fa fa-check" id="btn-cerrar-t"></i> Cerrar
														</a>
													<?php endif; ?>
												<?php endif; ?>
												<?php if (!$cant_casos_revisar_1): ?>
													<a class="btn bg-green btn-app btn-app-zetta pull-right" href="asisnov/imprimir_casos/1/<?php echo $escuela->id; ?>" target="_blank">
														<i class="fa fa-print"></i> Casos a Revisar
													</a>
												<?php endif; ?>
												<div class="well well-sm">
													<table class="table">
														<thead>
															<tr>
																<th>Rectificativa</th>
																<th>Creación</th>
																<th>Cierre</th>
																<th></th>
															</tr>
														</thead>
														<tbody>
															<?php if (!empty($planillas[1])): ?>
																<?php foreach ($planillas[1] as $planilla): ?>
																	<tr>
																		<td><?php echo $planilla->rectificativa; ?></td>
																		<td><?php echo (new DateTime($planilla->fecha_creacion))->format('d/m/Y H:i'); ?></td>
																		<td><?php echo empty($planilla->fecha_cierre) ? '' : (new DateTime($planilla->fecha_cierre))->format('d/m/Y H:i'); ?></td>
																		<td><a href="asisnov/listar/<?php echo $planilla->id; ?>" class="btn btn-primary btn-xs"><i class="fa fa-search"></i> Ver</a></td>
																	</tr>
																<?php endforeach; ?>
															<?php endif; ?>
														</tbody>
													</table>
												</div>
											<?php elseif ($edicion): ?>
												<a class="btn bg-blue btn-app btn-app-zetta" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="asisnov/modal_rectificativa/1/<?php echo "$escuela->id/$mes_id"; ?>">
													<i class="fa fa-plus" id="btn-rectificativa-r"></i> Planilla
												</a>
												<?php if ($cant_casos_revisar_1): ?>
													<a class="btn bg-green btn-app btn-app-zetta pull-right" href="asisnov/imprimir_casos/1/<?php echo $escuela->id; ?>" target="_blank">
														<i class="fa fa-print"></i> Casos a Revisar
													</a>
												<?php endif; ?>
											<?php endif; ?>
										</div>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="panel well well-sm">
										<div class="box-header with-border">
											<h3 class="box-title">Planilla Reemplazos</h3>
										</div>
										<div class="box-body">
											<?php if (!empty($planillas[2])) : ?>
												<?php if (empty($planillas_abiertas[2])): ?>
													<a class="btn bg-green btn-app btn-app-zetta" href="asisnov/imprimir/2/<?php echo "$escuela->id/$mes_id/2"; ?>" target="_blank">
														<i class="fa fa-print" id="btn-imprimir-r-f"></i> Presentacion
													</a>
													<?php if ($edicion): ?>
														<a class="btn bg-blue btn-app btn-app-zetta" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="asisnov/modal_rectificativa/2/<?php echo "$escuela->id/$mes_id"; ?>">
															<i class="fa fa-plus" id="btn-rectificativa-r"></i> Rectificativa
														</a>
													<?php endif; ?>
												<?php else: ?>
													<?php if (!empty($casos_revisar_2)): ?>
														<a class="btn bg-yellow btn-app btn-app-zetta" href="asisnov/imprimir/2/<?php echo "$escuela->id/$mes_id/1"; ?>" target="_blank">
															<i class="fa fa-print" id="btn-imprimir-r-r"></i> Revisión
														</a>
													<?php endif; ?>
													<?php if ($edicion): ?>
														<a class="btn bg-red btn-app btn-app-zetta" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="asisnov/modal_cerrar/<?php echo $planillas_abiertas[2][0]->id; ?>">
															<i class="fa fa-check" id="btn-cerrar-r"></i> Cerrar
														</a>
													<?php endif; ?>
												<?php endif; ?>
												<?php if ($cant_casos_revisar_2): ?>
													<a class="btn bg-green btn-app btn-app-zetta pull-right" href="asisnov/imprimir_casos/2/<?php echo $escuela->id; ?>" target="_blank">
														<i class="fa fa-print"></i> Casos a Revisar
													</a>
												<?php endif; ?>
												<div class="well well-sm">
													<table class="table">
														<thead>
															<tr>
																<th>Rectificativa</th>
																<th>Creación</th>
																<th>Cierre</th>
																<th></th>
															</tr>
														</thead>
														<tbody>
															<?php if (!empty($planillas[2])): ?>
																<?php foreach ($planillas[2] as $planilla): ?>
																	<tr>
																		<td><?php echo $planilla->rectificativa; ?></td>
																		<td><?php echo (new DateTime($planilla->fecha_creacion))->format('d/m/Y H:i'); ?></td>
																		<td><?php echo empty($planilla->fecha_cierre) ? '' : (new DateTime($planilla->fecha_cierre))->format('d/m/Y H:i'); ?></td>
																		<td><a href="asisnov/listar/<?php echo $planilla->id; ?>" class="btn btn-primary btn-xs"><i class="fa fa-search"></i> Ver</a></td>
																	</tr>
																<?php endforeach; ?>
															<?php endif; ?>
														</tbody>
													</table>
												</div>
											<?php elseif ($edicion): ?>
												<a class="btn bg-blue btn-app btn-app-zetta" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="asisnov/modal_rectificativa/2/<?php echo "$escuela->id/$mes_id"; ?>">
													<i class="fa fa-plus" id="btn-rectificativa-r"></i> Planilla
												</a>
												<?php if ($cant_casos_revisar_2): ?>
													<a class="btn bg-green btn-app btn-app-zetta pull-right" href="asisnov/imprimir_casos/2/<?php echo $escuela->id; ?>" target="_blank">
														<i class="fa fa-print"></i> Casos a Revisar
													</a>
												<?php endif; ?>
											<?php endif; ?>
										</div>
									</div>
								</div>
								<?php if ($escuela->dependencia_id === '1'): ?>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<div class="panel well well-sm">
											<div class="box-header with-border">
												<h3 class="box-title">Casos a Revisar Titulares (<?php echo $cant_casos_revisar_1; ?>)</h3>
											</div>
											<div class="box-body">
												<table class="table table-striped" style="border: 2px solid #bfbfbf;">
													<tbody>
														<?php foreach ($casos_revisar_1 as $key => $caso): ?>
															<?php if (count($caso)): ?>
																<tr>
																	<td><b><?php echo $labels_casos[$key]; ?></b></td>
																	<td>
																		<span class="badge bg-light-blue"><?php echo (empty($caso)) ? "0" : count($caso); ?></span>
																	</td>
																	<td>
																		<a class="btn btn-xs btn-primary pull-left" data-remote="false" data-toggle="modal" data-target="#caso_modal_1_<?php echo $key; ?>" title="Ver"><i class="fa fa-search"></i> Ver</a>
																	</td>
																</tr>
																<tr>
																	<td colspan="3"><?php echo $explicacion_casos[$key]; ?></td>
																</tr>
															<?php endif; ?>
														<?php endforeach; ?>
														<?php if (!$cant_casos_revisar_1): ?>
															<tr>
														<div class="alert alert-success alert-dismissible">
															Sin casos a revisar
														</div>
														</tr>
													<?php endif; ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="col-xs-6">
										<div class="panel well well-sm">
											<div class="box-header with-border">
												<h3 class="box-title">Casos a Revisar Reemplazos (<?php echo $cant_casos_revisar_2; ?>)</h3>
											</div>
											<div class="box-body">
												<table class="table table-striped" style="border: 2px solid #bfbfbf;">
													<tbody>
														<?php foreach ($casos_revisar_2 as $key => $caso): ?>
															<?php if (count($caso)): ?>
																<tr>
																	<td><b><?php echo $labels_casos[$key]; ?></b></td>
																	<td>
																		<span class="badge bg-light-blue"><?php echo (empty($caso)) ? "" : count($caso); ?></span>
																	</td>
																	<td>
																		<a class="btn btn-xs btn-primary pull-left" data-remote="false" data-toggle="modal" data-target="#caso_modal_2_<?php echo $key; ?>" title="Ver"><i class="fa fa-search"></i> Ver</a>
																	</td>
																</tr>
																<tr>
																	<td colspan="3"><?php echo $explicacion_casos[$key]; ?></td>
																</tr>
															<?php endif; ?>
														<?php endforeach; ?>
														<?php if (!$cant_casos_revisar_2): ?>
															<tr>
														<div class="alert alert-success alert-dismissible">
															Sin casos a revisar
														</div>
														</tr>
													<?php endif; ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<?php echo form_open("asisnov/cambiar_mes/$escuela->id/$mes_id"); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Cambiar Mes</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-12" style="text-align:center;">
						<div id="datepicker" data-date="<?php echo $fecha; ?>"></div>
						<input type="hidden" name="mes" id="mes" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
				<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Seleccionar'), 'Seleccionar'); ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<?php foreach ($casos_revisar_1 as $key => $caso): ?>
	<div class="modal fade" id="caso_modal_1_<?php echo $key; ?>" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Caso Titulares: <?php echo $labels_casos[$key]; ?></h4>
				</div>
				<div class="modal-body">
					<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
						<thead>
							<tr style="background-color: #f4f4f4" >
								<th style="text-align: center;" colspan="11">
									Titulares
								</th>
							</tr>
							<tr>
								<th>CUIL</th>
								<th>Apellido - Nombre</th>
								<th>Reg. Sal.</th>
								<th>Horas</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($caso)): ?>
								<?php foreach ($caso as $persona): ?>
									<tr>
										<td><?php echo $persona->cuil; ?></td>
										<td><?php echo ($key == "liquidacion_sin_servicio") ? $persona->nombre : "$persona->apellido, $persona->nombre"; ?></td>
										<td><?php echo ($key == "liquidacion_sin_servicio") ? $persona->regimen : $persona->regimen_codigo; ?></td>
										<td><?php echo ($key == "liquidacion_sin_servicio") ? $persona->diasoblig : $persona->carga_horaria; ?></td>
										<td><a class="btn btn-xs btn-primary pull-left" title="Ver" href="<?php echo ($key == "liquidacion_sin_servicio") ? "persona/ver/$persona->persona_id" : "servicio/ver/$persona->servicio_id"; ?>" target="_blank"><i class="fa fa-search"></i> Ver</a></td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td style="text-align: center;" colspan="11">
										-- No tiene --
									</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
				</div>
			</div>
		</div>
	</div>
<?php endforeach; ?>
<?php foreach ($casos_revisar_2 as $key => $caso): ?>
	<div class="modal fade" id="caso_modal_2_<?php echo $key; ?>" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Caso Reemplazos: <?php echo $labels_casos[$key]; ?></h4>
				</div>
				<div class="modal-body">

					<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
						<thead>
							<tr style="background-color: #f4f4f4" >
								<th style="text-align: center;" colspan="11">
									Reemplazos
								</th>
							</tr>
							<tr>
								<th>CUIL</th>
								<th>Apellido - Nombre</th>
								<th>Reg. Sal.</th>
								<th>Horas</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($caso)): ?>
								<?php foreach ($caso as $persona): ?>
									<tr>
										<td><?php echo $persona->cuil; ?></td>
										<td><?php echo ($key == "liquidacion_sin_servicio") ? $persona->nombre : "$persona->apellido, $persona->nombre"; ?></td>
										<td><?php echo ($key == "liquidacion_sin_servicio") ? $persona->regimen : $persona->regimen_codigo; ?></td>
										<td><?php echo ($key == "liquidacion_sin_servicio") ? $persona->diasoblig : $persona->carga_horaria; ?></td>
										<td><a class="btn btn-xs btn-primary pull-left" title="Ver" href="<?php echo ($key == "liquidacion_sin_servicio") ? "persona/ver/$persona->persona_id" : "servicio/ver/$persona->servicio_id"; ?>" target="_blank"><i class="fa fa-search"></i> Ver</a></td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td style="text-align: center;" colspan="11">
										-- No tiene --
									</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
				</div>
			</div>
		</div>
	</div>
<?php endforeach; ?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#datepicker").datepicker({
			format: "dd/mm/yyyy",
			startView: "months",
			minViewMode: "months",
			language: 'es',
			todayHighlight: false
		});
		$("#datepicker").on("changeDate", function(event) {
			$("#mes").val($("#datepicker").datepicker('getFormattedDate'))
		});
	});
</script>