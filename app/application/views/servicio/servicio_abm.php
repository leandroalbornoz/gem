<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Servicios Esc. <?= $escuela->nombre_corto; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $servicio->escuela_id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="servicio/listar/<?php echo $servicio->escuela_id . '/'; ?>">Servicios</a></li>
			<li class="active"><?php echo $metodo === 'separar_cargo' ? 'Separar cargo' : ucfirst($metodo); ?></li>
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
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<?php if ($txt_btn === 'Agregar'): ?>
							<a class="btn btn-app btn-app-zetta disabled <?php echo $class['agregar']; ?>" href="servicio/agregar">
								<i class="fa fa-plus"></i> Agregar
							</a>
						<?php else: ?>
							<a class="btn btn-app btn-app-zetta" href="cargo/ver/<?php echo (!empty($servicio->cargo_id)) ? $servicio->cargo_id : ''; ?>">
								<i class="fa fa-search"></i> Ver cargo
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="servicio/ver/<?php echo (!empty($servicio->id)) ? $servicio->id : ''; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<?php if ($edicion): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="servicio/editar/<?php echo (!empty($servicio->id)) ? $servicio->id : ''; ?>">
								<i class="fa fa-edit"></i> Editar
							</a>
							<?php if ($rol->codigo === ROL_ADMIN || $this->rol->codigo === ROL_USI): ?>
								<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="servicio/eliminar/<?php echo (!empty($servicio->id)) ? $servicio->id : ''; ?>">
									<i class="fa fa-ban"></i> Eliminar
								</a>
							<?php endif; ?>
							<a class="btn btn-app btn-app-zetta" href="servicio/separar_cargo/<?php echo (!empty($servicio->id)) ? $servicio->id : ''; ?>">
								<i class="fa fa-unlink"></i> Separar cargo
							</a>
						<?php endif; ?>
						<div class="row">
							<div class="form-group col-md-2">
								<?php echo $fields['cuil']['label']; ?>
								<div class="input-group">
									<?php echo $fields['cuil']['form']; ?>
									<span class="input-group-btn">
										<a class="btn btn-default" title="Ver persona" href="datos_personal/ver/<?= $servicio->id; ?>"><i class="fa fa-search"></i></a>
									</span>
								</div>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['apellido']['label']; ?>
								<?php echo $fields['apellido']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['nombre']['label']; ?>
								<?php echo $fields['nombre']['form']; ?>
							</div>
							<div class="form-group col-md-4">
								<?php echo $fields['escuela']['label']; ?>
								<?php echo $fields['escuela']['form']; ?>
							</div>
							<div class="form-group col-md-4">
								<?php echo $fields['regimen']['label']; ?>
								<?php echo $fields['regimen']['form']; ?>
							</div>
							<?php if ($servicio->regimen_tipo_id === '2'): ?>
								<div class="form-group col-md-2">
									<?php echo $fields['carga_horaria']['label']; ?>
									<?php echo $fields['carga_horaria']['form']; ?>
								</div>
							<?php endif; ?>
							<div class="form-group col-md-3">
								<?php echo $fields['division']['label']; ?>
								<?php echo $fields['division']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['espacio_curricular']['label']; ?>
								<?php echo $fields['espacio_curricular']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['situacion_revista']['label']; ?>
								<?php echo $fields['situacion_revista']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['liquidacion']['label']; ?>
								<?php if ($txt_btn === 'Editar' && empty($servicio->liquidacion) && FALSE): //@TODO habilitar para mostrar buscador liquidacion; ?>
									<div class="input-group">
										<?php echo $fields['liquidacion']['form']; ?>
										<span class="input-group-btn">
											<a class="btn btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="liquidacion/modal_buscar_servicio_escuela/<?= $servicio->id ?>"><i class="fa fa-search"></i></a>
										</span>
									</div>
								<?php else: ?>
									<?php echo $fields['liquidacion']['form']; ?>
								<?php endif; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_alta']['label']; ?>
								<?php echo $fields['fecha_alta']['form']; ?>
							</div>
							<?php if ($txt_btn !== 'Agregar'): ?>
								<div class="form-group col-md-2">
									<?php echo $fields['fecha_baja']['label']; ?>
									<?php echo $fields['fecha_baja']['form']; ?>
								</div>
								<div class="form-group col-md-3">
									<?php echo $fields['motivo_baja']['label']; ?>
									<?php echo $fields['motivo_baja']['form']; ?>
								</div>
							<?php endif; ?>
							<div class="form-group col-md-6">
								<?php echo $fields['reemplazado']['label']; ?>
								<?php if ($txt_btn === 'Editar'): ?>
									<div class="input-group">
										<?php echo $fields['reemplazado']['form']; ?>
										<span class="input-group-btn">
											<a class="btn btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="servicio/modal_asignar_reemplazado/<?= $servicio->id ?>"><i class="fa fa-edit"></i></a>
										</span>
									</div>
								<?php else: ?>
									<?php if (isset($servicio->reemplazado_id)): ?>
										<div class="input-group">
											<?php echo $fields['reemplazado']['form']; ?>
											<span class="input-group-btn">
												<a class="btn btn-default" target="_blank" href="servicio/ver/<?= $servicio->reemplazado_id ?>"><i class="fa fa-search"></i></a>
											</span>
										</div>
									<?php else: ?>
										<?php echo $fields['reemplazado']['form']; ?>
									<?php endif; ?>
								<?php endif; ?>
							</div>
							<div class="form-group col-md-6">
								<?php echo $fields['articulo_reemplazo']['label']; ?>
								<?php if ($txt_btn === 'Editar'): ?>
									<div class="input-group">
										<?php echo $fields['articulo_reemplazo']['form']; ?>
										<span class="input-group-btn">
											<a class="btn btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="servicio/modal_asignar_reemplazado/<?= $servicio->id ?>"><i class="fa fa-edit"></i></a>
										</span>
									</div>
								<?php else: ?>
									<?php echo $fields['articulo_reemplazo']['form']; ?>
								<?php endif; ?>
							</div>
							<?php if (substr($servicio->regimen, 1, 6) === '560201'): ?>
								<div class="form-group col-md-3">
									<?php echo $fields['celador_concepto']['label']; ?>
									<?php echo $fields['celador_concepto']['form']; ?>
								</div>
							<?php endif; ?>
							<div class="form-group col-md-12">
								<?php echo $fields['observaciones']['label']; ?>
								<?php echo $fields['observaciones']['form']; ?>
							</div>
						</div>
						<?php if ($txt_btn == 'Agregar'): ?>
							<div class="row">
								<div><hr></div>
								<div class="form-group col-md-6">
									<?php echo $fields_funcion['funcion']['label']; ?>
									<?php echo $fields_funcion['funcion']['form']; ?>
								</div>
								<div class="form-group col-md-3">
									<?php echo $fields_funcion['destino']['label']; ?>
									<?php echo $fields_funcion['destino']['form']; ?>
								</div>
								<div class="form-group col-md-3">
									<?php echo $fields_funcion['norma']['label']; ?>
									<?php echo $fields_funcion['norma']['form']; ?>
								</div>
								<div class="form-group col-md-3">
									<?php echo $fields_funcion['tarea']['label']; ?>
									<?php echo $fields_funcion['tarea']['form']; ?>
								</div>
								<div class="form-group col-md-3">
									<?php echo $fields_funcion['carga_horaria']['label']; ?>
									<?php echo $fields_funcion['carga_horaria']['form']; ?>
								</div>
								<div class="form-group col-md-3">
									<?php echo $fields_funcion['fecha_desde']['label']; ?>
									<?php echo $fields_funcion['fecha_desde']['form']; ?>
								</div>
								<div class="form-group col-md-3">
									<?php echo $fields_funcion['fecha_hasta']['label']; ?>
									<?php echo $fields_funcion['fecha_hasta']['form']; ?>
								</div>
							</div>
						<?php endif; ?>

						<?php if ($txt_btn === 'Editar' || empty($txt_btn)): ?>
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active"><a href="#tab_funciones" aria-controls="tab_funciones" role="tab" data-toggle="tab">Funciones</a></li>
								<li role="presentation"><a href="#tab_novedades" aria-controls="tab_novedades" role="tab" data-toggle="tab">Novedades</a></li>
								<li role="presentation"><a href="#tab_otros_servicios" aria-controls="tab_otros_servicios" role="tab" data-toggle="tab">Otros servicios del cargo</a></li>
								<li role="presentation"><a href="#tab_otros_cargos" aria-controls="tab_otros_cargos" role="tab" data-toggle="tab">Otros servicios de la persona en la escuela</a></li>
								<?php if ($this->rol->codigo === ROL_ADMIN): ?>
									<li role="presentation"><a href="#tab_incidencias" aria-controls="tab_incidencias" role="tab" data-toggle="tab">Incidencias</a></li>
								<?php endif; ?>
							</ul>
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="tab_funciones">	
									<table class="table table-hover table-bordered table-condensed table-striped dt-responsive dataTable no-footer dtr-inline" role="grid">
										<thead>
											<tr style="background-color: #f4f4f4" >
												<th style="text-align: center;" colspan="8">Funciones</th>
											</tr>
											<tr>
												<th>Detalle</th>
												<th>Destino</th>
												<th>Norma</th>
												<th>Tarea</th>
												<th>Carga horaria</th>
												<th>Desde</th>
												<th>Hasta</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($servicio_funciones)): ?>
												<?php foreach ($servicio_funciones as $funcion): ?>
													<tr>
														<td><?= $funcion->funcion; ?></td>
														<td><?= $funcion->destino; ?></td>
														<td><?= $funcion->norma; ?></td>
														<td><?= $funcion->tarea; ?></td>
														<td><?= $funcion->carga_horaria; ?></td>
														<td><?= empty($funcion->fecha_desde) ? '' : date_format(new DateTime($funcion->fecha_desde), 'd/m/Y'); ?></td>
														<td><?= empty($funcion->fecha_hasta) ? '' : date_format(new DateTime($funcion->fecha_hasta), 'd/m/Y'); ?></td>
														<td>
															<?php if ($txt_btn === 'Editar') : ?>
																<?php if (empty($funcion->fecha_hasta)) : ?>
																	<a class="btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="servicio_funcion/modal_agregar/<?= $servicio->id ?>"><i class="fa fa-plus"></i> Cambia a nueva función</a>
																	<br/>
																<?php endif; ?>
																<?php if (empty($funcion->fecha_hasta) || $this->rol->codigo === ROL_ADMIN) : ?>
																	<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="servicio_funcion/modal_editar/<?= $funcion->id ?>"><i class="fa fa-edit"></i> Editar función</a>
																	<?php if ($funcion->horario_propio === 'S') : ?>
																		<br/>
																		<a class="btn btn-xs btn-warning" href="servicio_funcion/horarios/<?= $funcion->id; ?>/0"><i class="fa fa-clock-o"></i> Editar horario</a>
																	<?php endif; ?>
																<?php endif; ?>
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
											<?php else : ?>
												<tr>
													<td colspan="8">-- No tiene --</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
								<div role="tabpanel" class="tab-pane" id="tab_novedades">
									<table class="table table-hover table-bordered table-condensed table-striped dt-responsive dataTable no-footer dtr-inline" role="grid">
										<thead>
											<tr style="background-color: #f4f4f4" >
												<th style="text-align: center;" colspan="8">Novedades</th>
											</tr>
											<tr>
												<th>Novedad</th>
												<th>Mes/Año</th>
												<th>Desde</th>
												<th>Hasta</th>
												<th>Días</th>
												<th>Oblig</th>
												<th>Estado</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($servicio_novedades)): ?>
												<?php foreach ($servicio_novedades as $novedad): ?>
													<tr>
														<td><?= "$novedad->articulo $novedad->novedad_tipo"; ?></td>
														<td><?= empty($novedad->ames) ? '' : date_format(new DateTime($novedad->ames . '01'), 'm/Y'); ?></td>
														<td><?= empty($novedad->fecha_desde) ? '' : date_format(new DateTime($novedad->fecha_desde), 'd/m/Y'); ?></td>
														<?php if ($novedad->novedad_tipo_id === '1'): ?>
															<?php if ($novedad->fecha_hasta != $novedad->fecha_desde): ?>
																<td><?= empty($novedad->fecha_hasta) ? '' : date_format(new DateTime($novedad->fecha_hasta), 'd/m/Y'); ?></td>
															<?php else: ?>
																<td></td>
															<?php endif; ?>
														<?php else: ?>
															<td><?= empty($novedad->fecha_hasta) ? '' : date_format(new DateTime($novedad->fecha_hasta), 'd/m/Y'); ?></td>
														<?php endif; ?>
														<td><?= $novedad->dias; ?></td>
														<td><?= $novedad->obligaciones; ?></td>
														<td><?= $novedad->estado . ($novedad->estado === 'Rechazado' ? " <b>($novedad->motivo_rechazo)</b>" : ''); ?></td>
														<td>
															<?php if ($txt_btn === 'Editar' && $this->rol->codigo === ROL_ADMIN) : ?>
																<?php if ($novedad->estado === 'Pendiente'): ?>
																	<a class="btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="servicio_novedad/modal_editar/<?php echo "$novedad->ames/$novedad->id/$novedad->escuela_id/1?redirect_url=" . urlencode(str_replace(base_url(), '', current_url())); ?>"><i class="fa fa-edit"></i>Editar</a>
																<?php else: ?>
																	<a class="btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="servicio_novedad/modal_editar/<?php echo "$novedad->ames/$novedad->id/$novedad->escuela_id/0?redirect_url=" . urlencode(str_replace(base_url(), '', current_url())); ?>"><i class="fa fa-edit"></i>Editar</a>
																<?php endif; ?>
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
											<?php else : ?>
												<tr>
													<td colspan="8" align="center">-- No tiene --</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
								<div role="tabpanel" class="tab-pane" id="tab_otros_servicios">
									<table class="table table-hover table-bordered table-condensed table-striped dt-responsive dataTable no-footer dtr-inline" role="grid">
										<thead>
											<tr style="background-color: #f4f4f4" >
												<th style="text-align: center;" colspan="8">Otros servicios del cargo</th>
											</tr>
											<tr>
												<th>Cuil</th>
												<th>Persona</th>
												<th>Fecha alta</th>
												<th>Liquidación</th>
												<th>Situación de revista</th>
												<th>Remplaza a</th>
												<th>Artículo reemplazo</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($otros_servicios)): ?>
												<?php foreach ($otros_servicios as $otro_servicio): ?>
													<tr>
														<td><?= $otro_servicio->cuil; ?></td>
														<td><?= $otro_servicio->persona; ?></td>
														<td><?= empty($otro_servicio->fecha_alta) ? '' : date_format(new DateTime($otro_servicio->fecha_alta), 'd/m/Y'); ?></td>
														<td><?= $otro_servicio->liquidacion; ?></td>
														<td><?= $otro_servicio->situacion_revista; ?></td>
														<td><?= $otro_servicio->reemplazado; ?></td>
														<td><?= $otro_servicio->articulo_reemplazo; ?></td>
														<td><a class="btn btn-xs btn-success" href="servicio/ver/<?php echo $otro_servicio->id; ?>"><i class="fa fa-search"></i></a></td>
													</tr>
												<?php endforeach; ?>
											<?php else : ?>
												<tr>
													<td colspan="8" align="center">-- No tiene --</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
								<div role="tabpanel" class="tab-pane" id="tab_otros_cargos">
									<table class="table table-hover table-bordered table-condensed table-striped dt-responsive dataTable no-footer dtr-inline" role="grid">
										<thead>
											<tr style="background-color: #f4f4f4" >
												<th style="text-align: center;" colspan="7">Otros servicios de la persona en la escuela</th>
											</tr>
											<tr>
												<th>Condición<br/>Sit. revista</th>
												<th>División<br/>Turno</th>
												<th>Régimen<br/>Materia</th>
												<th>Hs</th>
												<th>Liquidación</th>
												<th>Reemplaza a</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($otros_cargos)): ?>
												<?php foreach ($otros_cargos as $cargo): ?>
													<tr>
														<td><?= "$cargo->condicion_cargo<br>$cargo->situacion_revista"; ?></td>
														<td><?= "$cargo->curso<br>$cargo->turno"; ?></td>
														<td><?= "$cargo->regimen<br>$cargo->materia"; ?></td>
														<td><?= $cargo->carga_horaria > 0 ? $cargo->carga_horaria : ''; ?></td>
														<td><?= $cargo->liquidacion; ?></td>
														<td><?= "$cargo->reemplazado<br>$cargo->articulo_reemplazo"; ?></td>
														<td>
															<?php if ($this->rol->codigo === ROL_ADMIN): ?>
																<a class="btn btn-xs btn-success" href="cargo/ver/<?php echo $cargo->id; ?>"><i class="fa fa-search"></i></a>
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
											<?php else : ?>
												<tr>
													<td colspan="7" align="center">-- No tiene --</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
								<?php if ($this->rol->codigo === ROL_ADMIN): ?>
									<div role="tabpanel" class="tab-pane" id="tab_incidencias">
										<table class="table table-hover table-bordered table-condensed table-striped dt-responsive dataTable no-footer dtr-inline" role="grid">
											<thead>
												<tr style="background-color: #f4f4f4" >
													<th style="text-align: center;" colspan="6">Incidencias del servicio
														<?php if ($txt_btn === 'Editar') : ?>
															<a class="btn btn-xs btn-success pull-right" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="incidencia/modal_agregar/<?php echo $servicio->id; ?>"><i class="fa fa-plus"></i>&nbsp;Agregar Incidencia</a>
														<?php endif; ?>
													</th>
												</tr>
												<tr>
													<th>Fecha</th>
													<th>Asunto</th>
													<th>Estado</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<?php if (!empty($incidencias)): ?>
													<?php foreach ($incidencias as $incidencia): ?>
														<tr>
															<td style="width: 15%"><?= empty($incidencia->fecha) ? '' : date_format(new DateTime($incidencia->fecha), 'd/m/Y'); ?></td>
															<td style="width: 30%"><?= $incidencia->asunto; ?></td>
															<td style="width: 20%"><?= $incidencia->estado; ?></td>
															<td style="width: 35%; text-align: center;">
																<a class="btn btn-xs btn-default" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="incidencia_detalle/modal_ver/<?php echo $incidencia->id; ?>"><i class="fa fa-search"></i>Ver detalles</a>
																<?php if ($txt_btn === 'Editar') : ?>
																	<a class="btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="incidencia_detalle/modal_agregar/<?php echo $incidencia->id; ?>"><i class="fa fa-plus"></i>&nbsp;Agregar detalle</a>
																	<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="incidencia/modal_eliminar/<?php echo "$incidencia->id/$servicio->id"; ?>"><i class="fa fa-remove"></i>&nbsp;Eliminar</a>
																<?php endif; ?>
															</td>
														</tr>
													<?php endforeach; ?>
												<?php else : ?>
													<tr>
														<td colspan="6" align="center">-- No tiene --</td>
													</tr>
												<?php endif; ?>
											</tbody>
										</table>
									</div>
								<?php endif; ?>
							</div>	
						<?php endif; ?>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="servicio/listar/<?php echo $escuela->id . '/'; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $servicio->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		var url = document.location.toString();
		if (url.match('#')) {
			$('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
		}
		$('.nav-tabs a').on('shown.bs.tab', function(e) {
			if (history.replaceState) {
				history.replaceState(null, null, url.split('#')[0] + e.target.hash);
			} else {
				window.location.hash = e.target.hash;
			}
		});
	});
</script>