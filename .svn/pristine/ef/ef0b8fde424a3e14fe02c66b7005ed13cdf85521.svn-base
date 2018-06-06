<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Personal de <?= $area->codigo; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="areas/area/ver/<?php echo $area->id; ?>"><?php echo "$area->codigo $area->descripcion"; ?></a></li>
			<li><a href="areas/personal/listar/<?php echo $area->id; ?>">Personal</a></li>
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
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<?php if ($metodo !== 'ver_funcion'): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="areas/personal/ver/<?php echo $servicio->id; ?>">
								<i class="fa fa-search" id="btn-ver"></i> Ver
							</a>
							<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="areas/personal/editar/<?php echo $servicio->id; ?>">
								<i class="fa fa-edit" id="btn-editar"></i> Editar
							</a>
							<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="areas/personal/eliminar/<?php echo $servicio->id; ?>">
								<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
							</a>
							<?php if (in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI))): ?>
								<a class="btn btn-app btn-app-zetta" href="areas/personal/mover/<?php echo $servicio->id; ?>/<?php echo $area->id ?>">
									<i class="fa fa-exchange" id="btn-mover"></i> Mover
								</a>
							<?php endif; ?>
						<?php else: ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="areas/personal/ver_funcion/<?php echo $servicio_funcion->id; ?>">
								<i class="fa fa-search" id="btn-ver"></i> Ver
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta" href="areas/personal/horarios/<?php echo (!empty($servicio->id)) ? $servicio->id : ''; ?>">
							<i class="fa fa-clock-o"></i> Horarios
						</a>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields['cuil']['label']; ?>
								<div class="input-group">
									<?php echo $fields['cuil']['form']; ?>
									<span class="input-group-btn">
										<a class="btn btn-default" title="Ver persona" href="datos_personal/ver/<?= $servicio->id; ?>"><i class="fa fa-search"></i></a>
									</span>
								</div>
							</div>
							<div class="form-group col-md-6">
								<?php echo $fields['nombre']['label']; ?>
								<?php echo $fields['nombre']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['liquidacion']['label']; ?>
								<?php if ($txt_btn === 'Editar' && empty($servicio->liquidacion) && FALSE): //@TODO habilitar para mostrar buscador liquidacion; ?>
									<div class="input-group">
										<?php echo $fields['liquidacion']['form']; ?>
										<span class="input-group-btn">
											<a class="btn btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="liquidacion/modal_buscar_servicio_area/<?= $servicio->id ?>"><i class="fa fa-search"></i></a>
										</span>
									</div>
								<?php else: ?>
									<?php echo $fields['liquidacion']['form']; ?>
								<?php endif; ?>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<?php echo $fields['regimen']['label']; ?>
								<?php echo $fields['regimen']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['carga_horaria']['label']; ?>
								<?php echo $fields['carga_horaria']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['condicion_cargo']['label']; ?>
								<?php echo $fields['condicion_cargo']['form']; ?>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields['situacion_revista']['label']; ?>
								<?php echo $fields['situacion_revista']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['fecha_alta']['label']; ?>
								<?php if ($servicio->fecha_alta): ?>
									<?php echo $fields['fecha_alta']['form']; ?>
								<?php else: ?>
									<input type="text" name="fecha_alta" value="" id="fecha_alta" class="form-control dateFormat">
								<?php endif; ?>
							</div>
							<?php if ($txt_btn !== 'Agregar'): ?>
								<div class="form-group col-md-3">
									<?php echo $fields['fecha_baja']['label']; ?>
									<?php echo $fields['fecha_baja']['form']; ?>
								</div>
								<div class="form-group col-md-3">
									<?php echo $fields['motivo_baja']['label']; ?>
									<?php echo $fields['motivo_baja']['form']; ?>
								</div>
								<?php if ($txt_btn == 'Editar'): ?>
									<div class="form-group col-md-12">
										<?php echo $fields_servicio2['observaciones']['label']; ?>
										<?php echo $fields_servicio2['observaciones']['form']; ?>
									</div>
								<?php else: ?>
									<div class="form-group col-md-12">
										<?php echo $fields['observaciones']['label']; ?>
										<?php echo $fields['observaciones']['form']; ?>
									</div>
								<?php endif; ?>
							<?php endif; ?>
						</div>
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#tab_funciones" aria-controls="tab_funciones" role="tab" data-toggle="tab">Funciones</a></li>
							<li role="presentation"><a href="#tab_novedades" aria-controls="tab_novedades" role="tab" data-toggle="tab">Novedades</a></li>
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
															<?php endif; ?>
															<?php if (empty($funcion->fecha_hasta) || $this->rol->codigo === ROL_ADMIN) : ?>
																<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="servicio_funcion/modal_editar/<?= $funcion->id ?>"><i class="fa fa-edit"></i> Editar función</a>
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
													<td><?= $novedad->estado; ?></td>
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
						</div>	
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="areas/personal/listar/<?php echo $area->id; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo form_hidden('id', $cargo->id); ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
