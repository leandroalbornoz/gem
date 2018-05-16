<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Compartir cargo
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="cargo/listar/<?= $escuela->id ?>">Cargos</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="cargo/ver/<?php echo (!empty($cargo->id)) ? $cargo->id : ''; ?>">
							<i class="fa fa-search"></i> Ver cargo
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="cargo/compartir/<?php echo (!empty($cargo->id)) ? $cargo->id : ''; ?>">
							<i class="fa fa-share"></i> Compartir
						</a>
						<div class="row">
							<div class="form-group col-md-4">
								<?php echo $fields['division']['label']; ?>
								<?php if (!empty($cargo->division_id)): ?>
									<div class="input-group">
										<?php echo $fields['division']['form']; ?>
										<span class="input-group-btn">
											<a class="btn btn-secondary btn-default" href="division/ver/<?php echo $cargo->division_id; ?>"><i class="fa fa-search"></i></a>
										</span>
									</div>
								<?php else: ?>
									<?php echo $fields['division']['form']; ?>
								<?php endif; ?>
							</div>
							<div class="form-group col-md-4">
								<?php echo $fields['carrera']['label']; ?>
								<?php echo $fields['carrera']['form']; ?>
							</div>
							<div class="form-group col-md-4">
								<?php echo $fields['espacio_curricular']['label']; ?>
								<?php echo $fields['espacio_curricular']['form']; ?>
							</div>
							<div class="form-group col-md-6">
								<?php echo $fields['regimen']['label']; ?>
								<?php echo $fields['regimen']['form']; ?>
							</div>
							<?php if ($cargo->regimen_tipo_id === '2'): ?>
								<div class="form-group col-md-3">
									<?php echo $fields['carga_horaria']['label']; ?>
									<?php echo $fields['carga_horaria']['form']; ?>
								</div>
							<?php endif; ?>
							<div class="form-group col-md-3">
								<?php echo $fields['condicion_cargo']['label']; ?>
								<?php echo $fields['condicion_cargo']['form']; ?>
							</div>
							<div class="form-group col-md-6">
								<?php echo $fields['resolucion_alta']['label']; ?>
								<?php echo $fields['resolucion_alta']['form']; ?>
							</div>
							<div class="form-group col-md-6">
								<?php echo $fields['codigo_junta']['label']; ?>
								<?php echo $fields['codigo_junta']['form']; ?>
							</div>
							<div class="form-group col-md-12">
								<?php echo $fields['observaciones']['label']; ?>
								<?php echo $fields['observaciones']['form']; ?>
							</div>
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
						<table class="table table-condensed table-hover" style="margin-top:20px; table-layout: fixed;">
							<tr style="background-color: #f4f4f4">
								<th colspan="3" style="text-align:center;">
									Escuelas con las que se comparte el cargo
									<?php if ($edicion): ?>
										<a class="pull-right btn btn-xs btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="cargo_escuela/modal_agregar/<?= $cargo->id ?>"><i class="fa fa-plus"></i> Agregar escuela</a>
									<?php endif; ?>
								</th>
							</tr>
							<?php if (!empty($escuelas)): ?>
								<tr>
									<th>
										Escuela
									</th>
									<th>
										Cantidad de Horas
									</th>
									<td></td>
								</tr>
								<?php foreach ($escuelas as $cargo_escuela) : ?>
									<tr>
										<td>
											<?php echo $cargo_escuela->escuela; ?>
										</td>
										<td>
											<?php echo $cargo_escuela->cantidad_horas; ?>
										</td>
										<?php if ($edicion): ?>
											<td>
												<a class="pull-right btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="cargo_escuela/modal_eliminar/<?= $cargo_escuela->id ?>"><i class="fa fa-close"></i> Quitar escuela</a>
											</td>
										<?php endif; ?>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</table>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="<?php echo empty($return_url) ? "cargo/listar/$escuela->id" : $return_url; ?>" title="Volver">Volver</a>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>