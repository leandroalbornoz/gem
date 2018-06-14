<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Divisiones
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="division/listar/<?= $escuela->id ?>">Cursos y Divisiones</a></li>
			<?php if (!empty($division)): ?>
				<li><a href="division/ver/<?= $division->id ?>"><?php echo "$division->curso $division->division"; ?></a></li>
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
						<?php if ($txt_btn === 'Agregar'): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['agregar']; ?>" href="division/agregar/<?= $escuela->id ?>">
								<i class="fa fa-plus"></i> Agregar
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="division/ver/<?php echo (!empty($division->id)) ? $division->id : ''; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<?php if ($edicion): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="division/editar/<?php echo (!empty($division->id)) ? $division->id : ''; ?>">
								<i class="fa fa-edit"></i> Editar
							</a>
							<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="division/eliminar/<?php echo (!empty($division->id)) ? $division->id : ''; ?>">
								<i class="fa fa-ban"></i> Eliminar
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver'] === 'disabled' ? 'disabled' : ''; ?>" href="division/ver_horario/<?php echo (!empty($division->id)) ? $division->id : ''; ?>">
							<i class="fa fa-clock-o"></i> Horarios
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver'] === 'disabled' ? 'disabled' : ''; ?>" href="division/alumnos/<?php echo (!empty($division->id)) ? $division->id : ''; ?>">
							<i class="fa fa-users"></i> Alumnos
						</a>
						<?php if ($this->rol->codigo !== ROL_ESCUELA_ALUM): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['ver'] === 'disabled' ? 'disabled' : ''; ?>" href="division/cargos/<?php echo (!empty($division->id)) ? $division->id : ''; ?>">
								<i class="fa fa-users"></i> Cargos
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver'] === 'disabled' ? 'disabled' : ''; ?>" href="cursada/listar/<?php echo (!empty($division->id)) ? $division->id : ''; ?>">
							<i class="fa fa-book"></i> Cursadas
						</a>
						<?php if (ENVIRONMENT !== 'production'): ?>
							<a class="btn btn-app btn-app-zetta" href="abono/abono_alumno/listar_division/<?php echo $escuela->id; ?>/<?php echo (!empty($division->id)) ? $division->id : ''; ?>">
								<i class="fa fa-bus" id="btn-abonos"></i> Abonos
							</a>
						<?php endif; ?>
						<?php if (empty($division->fecha_baja)): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['ver'] === 'disabled' ? 'disabled' : ''; ?>" data-remote="false" data-toggle="modal"  data-target="#remote_modal" href="division/modal_cerrar_division/<?php echo (!empty($division->id)) ? $division->id : ''; ?>">
								<i class="fa fa-lock"></i> Cerrar División
							</a>
						<?php endif; ?>

						<?php if ($txt_btn !== 'Agregar'): ?>
							<div class="dropdown pull-right">
								<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
									<i class="fa fa-file"></i> Reportes
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu menu-right" aria-labelledby="dropdownMenu1">
									<li class="dropdown-header"><strong>Alumnos</strong></li>
									<li><a class="dropdown-item btn-warning" href="division/imprimir_curso_division/<?= $division->id ?>" title="Imprimir PDF" target="_blank"><i class="fa fa-file-pdf-o"></i> Exportar PDF</a></li>
									<li><a class="dropdown-item btn-success" href="division/excel/<?= $division->id ?>" title="Exportar excel"><i class="fa fa-file-excel-o"></i> Exportar Excel</a></li>
								</ul>
							</div>
						<?php endif; ?>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields['curso']['label']; ?>
								<?php echo $fields['curso']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['division']['label']; ?>
								<?php echo $fields['division']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['turno']['label']; ?>
								<?php echo $fields['turno']['form']; ?>
							</div>
							<div class="form-group col-md-4">
								<?php echo $fields['carrera']['label']; ?>
								<?php echo $fields['carrera']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['modalidad']['label']; ?>
								<?php echo $fields['modalidad']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_alta']['label']; ?>
								<?php echo $fields['fecha_alta']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_baja']['label']; ?>
								<?php echo $fields['fecha_baja']['form']; ?>
							</div>
							<div class="form-group col-md-5">
								<?php echo $fields['calendario']['label']; ?>
								<?php echo $fields['calendario']['form']; ?>
							</div>
						</div>
						<input type="hidden" name="escuela_id" value="<?= $escuela->id; ?>"/>
						<?php if (empty($txt_btn)): ?>
							<hr>
							<?php if ($this->rol->codigo !== ROL_ESCUELA_ALUM): ?> 
								<div role="tabpanel" class="tab-pane" id="tab_cargos">
									<div style="margin-top: 3%">
										<table id="tbl_cargos" class="table table-hover table-bordered table-condensed" role="grid">
											<thead>
												<tr style="background-color: #f4f4f4;" >
													<th style="text-align: center;" colspan="10">
														<a style="margin-left: 10px;" class="btn btn-xs btn-primary pull-left" href="division/cargos/<?php echo (!empty($division->id)) ? $division->id : ''; ?>"><i class="fa fa-users"></i> Administrar</a>
														<span style="position: absolute; left: 50%; margin-left: -30px;">
															Cargos
														</span>
													</th>
												</tr>
												<tr>
													<th>Condición</th>
													<th>Materia</th>
													<th>Hs.</th>
													<th>Régimen</th>
												</tr>
											</thead>
											<tbody>
												<?php if (!empty($cargos)): ?>
													<?php $cargo_id = 0; ?>
													<?php foreach ($cargos as $cargo): ?>
														<?php if ($cargo->id !== $cargo_id): ?>
															<tr style="background-color: #eee;">
																<td><?= $cargo->condicion_cargo; ?></td>
																<td><?= $cargo->materia; ?></td>
																<td><?= $cargo->carga_horaria; ?></td>
																<td><?= "$cargo->regimen_codigo $cargo->regimen"; ?></td>
															</tr>
															<?php $cargo_id = $cargo->id; ?>
														<?php endif; ?>
														<?php if (!empty($cargo->servicio_id)): ?>
															<tr style="font-style: italic; color: #888;">
																<td style="text-align:right;"><?php echo $cargo->situacion_revista; ?></td>
																<td colspan="2">
																	<?php echo "$cargo->cuil $cargo->apellido, $cargo->nombre"; ?>
																</td>
																<td><?php echo $cargo->liquidacion; ?></td>
															</tr>
														<?php else: ?>
															<tr style="font-style: italic; color: #888;">
																<td></td>
																<td colspan="3">-- Sin servicios en el cargo --</td>
															</tr>
														<?php endif; ?>
														</tr>
													<?php endforeach; ?>
												<?php else : ?>
													<tr>
														<td>-- No tiene --</td>
													</tr>
												<?php endif; ?>
											</tbody>
										</table>
									</div>
								</div>
							<?php endif; ?>
							<hr>
							<div style="margin-top: 3%"></div>
							<div role="tabpanel" class="tab-pane" id="tab_alumnos">
								<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
									<thead>
										<tr style="background-color: #f4f4f4" >
											<th style="" colspan="8">
												<a style="margin-left: 10px;" class="btn btn-xs btn-primary pull-left" href="division/alumnos/<?php echo (!empty($division->id)) ? $division->id : ''; ?>" title="Administrar"><i class="fa fa-users"></i> Administrar</a>
												<span style="position: absolute; left: 50%; margin-left: -33px;">
													Alumnos
													<?php echo empty($cantidad_alumnos) ? '' : "($cantidad_alumnos)"; ?>
												</span>
												<a style="margin-left: 10px;" class="btn btn-xs bg-green pull-right" href="division/excel/<?= $division->id ?>"  title="Exportar Excel"><i class="fa fa-file-excel-o"></i> Exportar Excel</a>
												<a class="btn btn-xs bg-yellow pull-right" href="division/imprimir_curso_division/<?= $division->id ?>" target="_blank" title="Exportar PDF"><i class="fa fa-file-pdf-o"></i> Exportar PDF</a>
											</th>
										</tr>
										<tr>
											<th>C. lectivo</th>
											<th>Condición</th>
											<th>Documento</th>
											<th>Persona</th>
											<th>F. Nacimiento</th>
											<th>Sexo</th>
											<th>Desde</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($alumnos)): ?>
											<?php foreach ($alumnos as $alumno): ?>
												<tr>
													<td><?= $alumno->ciclo_lectivo; ?></td>
													<td><?= $alumno->condicion; ?><a class="btn btn-xs btn-success pull-right" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_division/modal_certificado_alumno_regular/<?= $alumno->id; ?>" title="Imprimir certificado" target="_blank"><i class="fa fa-print"></i></a></td>
													<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
													<td><?= $alumno->persona; ?></td>
													<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
													<td><?= substr($alumno->sexo, 0, 1); ?></td>
													<td><?= empty($alumno->fecha_desde) ? '' : (new DateTime($alumno->fecha_desde))->format('d/m/Y'); ?></td>
													<td><a class="btn btn-xs btn-primary" href="alumno/ver/<?= $alumno->id; ?>" title="Ver"><i class="fa fa-search"></i></a></td>
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr>
												<td colspan="7" style="text-align: center;">-- No tiene --</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						<?php endif; ?>
					</div>
					<div class="box-footer">
						<?php if ($txt_btn === 'Eliminar' && $horario): ?>
							<span class="text-red text-bold">
								La división tiene horarios cargados, se eliminarán los mismos al eliminar la división.
							</span><br/>
						<?php endif; ?>
						<a class="btn btn-default" href="division/listar/<?= $escuela->id; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $division->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>