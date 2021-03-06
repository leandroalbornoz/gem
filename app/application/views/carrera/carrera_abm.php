<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Carreras
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<?php if (isset($escuela)): ?>
				<li><a href="escuela/ver/<?= $escuela->id; ?>"></i><?= "Esc. $escuela->nombre_largo"; ?></a></li>
				<li><a href="escuela_carrera/listar/<?= $escuela->id; ?>">Carreras</a></li>
			<?php else: ?>
				<li><a href="<?php echo $controlador; ?>">Carreras</a></li>
			<?php endif; ?>
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
						<?php if ($edicion): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['agregar']; ?>" href="carrera/agregar">
								<i class="fa fa-plus" id="btn-agregar"></i> Agregar
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="carrera/ver/<?php echo (!empty($carrera->id)) ? $carrera->id : ''; ?>/<?php echo (!empty($escuela_carrera_id)) ? $escuela_carrera_id : ''; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<?php if ($edicion): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="carrera/editar/<?php echo (!empty($carrera->id)) ? $carrera->id : ''; ?>">
								<i class="fa fa-edit" id="btn-editar"></i> Editar
							</a>
							<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="carrera/eliminar/<?php echo (!empty($carrera->id)) ? $carrera->id : ''; ?>">
								<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
							</a>
						<?php endif; ?>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields['nivel']['label']; ?>
								<?php echo $fields['nivel']['form']; ?>
							</div>
							<div class="form-group col-md-5">
								<?php echo $fields['descripcion']['label']; ?>
								<?php echo $fields['descripcion']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_desde']['label']; ?>
								<?php echo $fields['fecha_desde']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_hasta']['label']; ?>
								<?php echo $fields['fecha_hasta']['form']; ?>
							</div>
						</div>
						<?php if (!empty($cursos)): ?>
							<label class="form-control-static">Espacio curricular</label>
							<?php foreach ($cursos as $curso): ?>
								<table class="table table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
									<thead>
										<tr style="background-color: #f4f4f4" >
											<th style="text-align: center;" colspan="11">
												<?= $curso->descripcion; ?>
												<?php if ($txt_btn === 'Editar'): ?>
													<a class="pull-right btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="espacio_curricular/modal_agregar/<?= $carrera->id ?>/<?= $curso->id ?>"><i class="fa fa-plus"></i><i class="fa fa-book"></i></a>
												<?php endif; ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($curso->materias)): ?>
											<tr>
												<td>
													<table class="table table-bordered table-condensed">
														<thead>
															<tr>
																<th>Materia</th>
																<th>Cuatrimestre</th>
																<th>Resolución</th>
																<th>Cod. Materia Junta</th>
																<th>Horas</th>
																<?php if ($txt_btn === 'Editar'): ?>
																	<th></th>
																<?php endif; ?>
															</tr>
														</thead>
														<?php foreach ($curso->materias as $materia): ?>
															<tr>
																<td style="vertical-align: middle;">
																	<?php if ($txt_btn === 'Editar'): ?>
																		<a style="vertical-align: middle;" class="btn btn-xs btn-warning" type="button" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="materia/modal_editar/<?= $materia->materia_id ?>/<?php echo $carrera->id; ?>"><i class="fa fa-edit"></i></a>
																	<?php endif; ?>
																	<?php echo $materia->materia; ?>
																</td>
																<td style="width: 80px; text-align: center; vertical-align: middle;">
																	<?php echo $materia->cuatrimestre; ?>
																</td>
																<td style="width: 120px; text-align: center; vertical-align: middle;">
																	<?php echo $materia->resolucion_alta; ?>
																</td>
																<td style="width: 200px; text-align: center; vertical-align: middle;">
																	<?php echo $materia->codigo_junta; ?>
																</td>
																<td style="width: 60px; text-align: center; vertical-align: middle;">
																	<?php echo $materia->carga_horaria; ?>
																</td>
																<?php if ($txt_btn === 'Editar'): ?>
																	<td style="width: 60px; text-align: center; vertical-align: middle;">
																		<a style="vertical-align: middle;" class="btn btn-xs btn-danger" type="button" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="espacio_curricular/modal_eliminar/<?= $materia->id ?>"><i class="fa fa-remove"></i></a>
																		<a style="vertical-align: middle;" class="btn btn-xs btn-warning" type="button" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="espacio_curricular/modal_editar/<?= $materia->id ?>"><i class="fa fa-edit"></i></a>
																	</td>
																<?php endif; ?>
															</tr>
															<?php if (isset($curso->materias_grupo[$materia->id])): ?>
																<?php foreach ($curso->materias_grupo[$materia->id] as $materia_grupo): ?>
																	<tr>
																		<td colspan="5" style="font-size: 12px; vertical-align: middle; padding-left: 30px">
																			<?php if ($txt_btn === 'Editar'): ?>
																				<a style="vertical-align: middle;" class="btn btn-xs btn-warning" type="button" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="materia/modal_editar/<?= $materia_grupo->materia_id ?>/<?php echo $carrera->id; ?>"><i class="fa fa-edit"></i></a>
																			<?php endif; ?>
																			<?php echo $materia_grupo->materia; ?>
																		</td>
																	</tr>
																<?php endforeach; ?>
															<?php endif; ?>
														<?php endforeach; ?>
													</table>
												</td>
											</tr>
										<?php else : ?>
											<tr>
												<td style="text-align: center; padding-right: 20px">
													-- No tiene --
												</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<div class="box-footer">
						<?php if (isset($escuela)): ?>
							<a class="btn btn-default" href="escuela_carrera/listar/<?= $escuela->id; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php else: ?>
							<a class="btn btn-default" href="carrera/listar/<?php echo (isset($carrera->nivel_id) && !empty($carrera->nivel_id)) ? $carrera->nivel_id : '' ?>"  title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php endif; ?>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $carrera->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>