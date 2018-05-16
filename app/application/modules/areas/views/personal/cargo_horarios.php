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
							<a class="btn btn-app btn-app-zetta  <?php echo $class['editar']; ?>" href="areas/personal/editar/<?php echo $servicio->id; ?>">
								<i class="fa fa-edit" id="btn-editar"></i> Editar
							</a>
							<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="areas/personal/eliminar/<?php echo $servicio->id; ?>">
								<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
							</a>
						<?php else: ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="areas/personal/ver_funcion/<?php echo $servicio_funcion->id; ?>">
								<i class="fa fa-search" id="btn-ver"></i> Ver
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="areas/personal/horarios/<?php echo (!empty($servicio->id)) ? $servicio->id : ''; ?>">
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
								<div class="form-group col-md-12">
									<?php echo $fields['observaciones']['label']; ?>
									<?php echo $fields['observaciones']['form']; ?>
								</div> 
							<?php endif; ?>
							<?php if (!empty($servicios)): ?>
								<div class="form-group col-md-12">
									<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
										<thead>
											<tr style="background-color: #f4f4f4" >
												<th style="text-align: center;" colspan="6">Servicios</th>
											</tr>
											<tr>
												<th>Revista</th>
												<th>Liquidación</th>
												<th>Persona</th>
												<th>Alta</th>
												<th>Baja</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($servicios as $servicio): ?>
												<tr>
													<td><?= $servicio->situacion_revista; ?></td>
													<td><?= $servicio->liquidacion ?></td>
													<td><?= "$servicio->apellido, $servicio->nombre" ?></td>
													<td><?= empty($servicio->fecha_alta) ? '' : (new DateTime($servicio->fecha_alta))->format('d/m/Y'); ?></td>
													<td><?= empty($servicio->fecha_baja) ? '' : (new DateTime($servicio->fecha_baja))->format('d/m/Y'); ?></td>
													<th>
														<a class="pull-right btn btn-xs btn-default" href="servicio/ver/<?= $servicio->id ?>"><i class="fa fa-search"></i></a>
													</th>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							<?php endif; ?>
						</div>

						<table class="table" style="text-align: center; margin-top:20px; table-layout: fixed;">
							<tr style="background-color: #f4f4f4">
								<th colspan="8" style="text-align:center;">Horarios de cargo</th>
							</tr>
							<tr>
								<th style="text-align: center;"></th>
								<?php foreach ($dias as $dia) : ?>
									<th style="text-align: center;"><?= mb_substr($dia->nombre, 0, 2); ?></th>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td style="vertical-align: top;">
									<?php if ($edicion): ?>
										<br/>
										&nbsp;
										<a class="btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="areas/cargo_horario/modal_carga_masiva/<?= $cargo->id ?>/<?= $servicio->id ?>"><i class="fa fa-plus"></i></a>
									<?php endif; ?>
								</td>
								<?php foreach ($dias as $dia) : ?>
									<?php if (empty($horarios_car)) : ?>
										<td style="vertical-align: middle;">
											<?php if ($edicion): ?>
												<a class="btn btn-xs btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="areas/cargo_horario/modal_agregar/<?= $cargo->id ?>/<?= $dia->id ?>/<?= $servicio->id ?>"><i class="fa fa-plus"></i></a>
											<?php endif; ?>
										</td>
									<?php else : ?>
										<td style="vertical-align: top;">
											<?php if (isset($horarios_car[$dia->id])): ?>
												<?php foreach ($horarios_car[$dia->id] as $position => $cargo_horario) : ?>
													<?php echo substr($cargo_horario->hora_desde, 0, 5) . ' - ' . substr($cargo_horario->hora_hasta, 0, 5); ?>
													<?php if (empty($cargo_horario->division_id)): ?>
														<br/>
														<?php if ($edicion): ?>
															<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="areas/cargo_horario/modal_editar/<?= $cargo_horario->id ?>/<?= $servicio->id ?>"><i class="fa fa-edit"></i></a>
															<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="areas/cargo_horario/modal_eliminar/<?= $cargo_horario->id ?>/<?= $servicio->id ?>"><i class="fa fa-close"></i></a>
															<?php if (count($horarios_car[$dia->id]) == (($position + 1))): ?>
																<br/>
																<br/>
																&nbsp;
																<br/>
																<a class="btn btn-xs btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="areas/cargo_horario/modal_agregar/<?= $cargo->id ?>/<?= $dia->id ?>/<?= $servicio->id ?>"><i class="fa fa-plus"></i></a>
															<?php endif; ?>
															<br/>
														<?php endif; ?>
													<?php else: ?>
														<span class="label label-default">Escuela</span>
													<?php endif; ?>
													<br/>
												<?php endforeach; ?>
											<?php else: ?>
												<?php if ($edicion): ?>
													&nbsp;
													<br/>
													<a class="btn btn-xs btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="areas/cargo_horario/modal_agregar/<?= $cargo->id ?>/<?= $dia->id ?>/<?= $servicio->id ?>"><i class="fa fa-plus"></i></a>
												<?php endif; ?>
											<?php endif; ?>
										</td>
									<?php endif; ?>
								<?php endforeach; ?>
							</tr>
						</table>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="<?php echo empty($return_url) ? "areas/personal/ver/$servicio->id" : $return_url; ?>" title="Volver">Volver</a>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>