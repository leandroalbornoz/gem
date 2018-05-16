<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Carreras
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li class="active"><?php echo "Espacios curriculares"; ?></li>	
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
					<div class="box-header with-border">
						<h3 class="box-title">
							<?php echo empty($carrera) ? '' : "Carrera - $carrera->descripcion"; ?>
						</h3>
					</div>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="escuela_carrera/espacios_curriculares/<?php echo $carrera->id; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Materias
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="escuela_carrera/escuelas/<?php echo $carrera->id; ?>">
							<i class="fa fa-home" id="btn-ver"></i> Escuelas
						</a>
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
																<th>Codigo Junta</th>
																<th>Horas</th>
															</tr>
														</thead>
														<?php foreach ($curso->materias as $materia): ?>
															<tr>
																<td style="vertical-align: middle;">
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
															</tr>
															<?php if (isset($curso->materias_grupo[$materia->id])): ?>
																<?php foreach ($curso->materias_grupo[$materia->id] as $materia_grupo): ?>
																	<tr>
																		<td colspan="5" style="font-size: 12px; vertical-align: middle; padding-left: 30px">
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
						<a class="btn btn-default" href=""  title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo zetta_form_submit($txt_btn); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>