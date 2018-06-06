<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Servicios Esc. <?= $escuela->nombre_corto; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="servicio/listar/<?php echo $escuela->id . '/'; ?>">Servicios</a></li>
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
					<div class="box-body">
						<?php if ($es_funcion === '0'): ?>
							<a class="btn btn-app btn-app-zetta" href="servicio/ver/<?php echo "$servicio_funcion->servicio_id"; ?>">
								<i class="fa fa-search"></i> Ver
							</a>
							<a class="btn btn-app btn-app-zetta" href="servicio/editar/<?php echo "$servicio_funcion->servicio_id"; ?>">
								<i class="fa fa-edit"></i> Editar
							</a>
						<?php else: ?>
							<a class="btn btn-app btn-app-zetta" href="servicio_funcion/ver/<?php echo "$servicio_funcion->id/$es_funcion"; ?>">
								<i class="fa fa-search"></i> Ver
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="servicio_funcion/horarios/<?php echo "$servicio_funcion->id/$es_funcion"; ?>">
							<i class="fa fa-clock-o"></i> Horario función
						</a>
						<div class="row">
							<div class="form-group col-md-2">
								<?php echo $fields['cuil']['label']; ?>
								<div class="input-group">
									<?php echo $fields['cuil']['form']; ?>
									<span class="input-group-btn">
										<a class="btn btn-default" title="Ver persona" target="_blank" href="datos_personal/ver/<?= $es_funcion === '1' ? "$servicio_funcion->id/1" : "$servicio->id"; ?>"><i class="fa fa-search"></i></a>
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
								<?php if ($es_funcion === '0'): ?>
									<div class="input-group">
										<?php echo $fields['regimen']['form']; ?>
										<span class="input-group-btn">
											<a class="btn btn-default" title="Ver cargo" href="cargo/ver/<?= $servicio->cargo_id; ?>"><i class="fa fa-search"></i></a>
										</span>
									</div>
								<?php else: ?>
									<?php echo $fields['regimen']['form']; ?>
								<?php endif; ?>
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
								<?php echo $fields['liquidacion']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_alta']['label']; ?>
								<?php echo $fields['fecha_alta']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_baja']['label']; ?>
								<?php echo $fields['fecha_baja']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['motivo_baja']['label']; ?>
								<?php echo $fields['motivo_baja']['form']; ?>
							</div>
							<div class="form-group col-md-6">
								<?php echo $fields['reemplazado']['label']; ?>
								<?php echo $fields['reemplazado']['form']; ?>
							</div>
							<div class="form-group col-md-6">
								<?php echo $fields['articulo_reemplazo']['label']; ?>
								<?php echo $fields['articulo_reemplazo']['form']; ?>
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
						<div class="row">
							<div class="form-group col-md-6">
								<?php echo $fields_funcion['funcion']['label']; ?>
								<?php echo $fields_funcion['funcion']['form']; ?>
							</div>
							<div class="form-group col-md-6">
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
						</div>
						<table class="table" style="text-align: center; margin-top:20px; table-layout: fixed;">
							<tr style="background-color: #f4f4f4">
								<th colspan="8" style="text-align:center;">Horarios de función de servicio</th>
							</tr>
							<tr>
								<th style="text-align: center;"></th>
								<?php foreach ($dias as $dia) : ?>
									<th style="text-align: center;"><?= mb_substr($dia->nombre, 0, 2); ?></th>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td style="vertical-align: top;">
									<br/>
									&nbsp;
									<a class="btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="servicio_funcion_horario/modal_carga_masiva/<?= "$servicio_funcion->id/$es_funcion"; ?>"><i class="fa fa-plus"></i> Carga masiva</a>
								</td>
								<?php foreach ($dias as $dia) : ?>
									<?php if (empty($horarios_fn)) : ?>
										<td style="vertical-align: top;">
											<a class="btn btn-xs btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="servicio_funcion_horario/modal_agregar/<?= "$servicio_funcion->id/$dia->id/$es_funcion"; ?>"><i class="fa fa-plus"></i></a>
										</td>
									<?php else : ?>
										<td style="vertical-align: top;">
											<?php if (isset($horarios_fn[$dia->id])): ?>
												<?php foreach ($horarios_fn[$dia->id] as $position => $fn_horario) : ?>
													<?php echo substr($fn_horario->hora_desde, 0, 5) . ' - ' . substr($fn_horario->hora_hasta, 0, 5); ?>
													<br/>
													<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="servicio_funcion_horario/modal_editar/<?= "$fn_horario->id/$es_funcion"; ?>"><i class="fa fa-edit"></i></a>
													<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="servicio_funcion_horario/modal_eliminar/<?= "$fn_horario->id/$es_funcion"; ?>"><i class="fa fa-close"></i></a>
													<?php if (count($horarios_fn[$dia->id]) == (($position + 1))): ?>
														<br/>
														<br/>
														&nbsp;
														<br/>
														<a class="btn btn-xs btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="servicio_funcion_horario/modal_agregar/<?= "$servicio_funcion->id/$dia->id/$es_funcion"; ?>"><i class="fa fa-plus"></i></a>
													<?php endif; ?>
													<br/>
													<br/>
												<?php endforeach; ?>
											<?php else: ?>
												&nbsp;
												<br/>
												<a class="btn btn-xs btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="servicio_funcion_horario/modal_agregar/<?= "$servicio_funcion->id/$dia->id/$es_funcion"; ?>"><i class="fa fa-plus"></i></a>
											<?php endif; ?>
										</td>
									<?php endif; ?>
								<?php endforeach; ?>
							</tr>
						</table>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="servicio/listar/<?php echo $escuela->id . '/'; ?>" title="Volver">Volver</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>