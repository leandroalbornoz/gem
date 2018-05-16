<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?php if (!empty($escuela)): ?>
				<?php echo "$escuela->nombre_largo"; ?>
			<?php else: ?>
				Escuelas
			<?php endif; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Escuelas</a></li>
			<?php if (!empty($escuela)): ?>
				<li><?php echo "Esc. $escuela->nombre_largo"; ?></li>
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
						<?php if ($rol->codigo === ROL_ADMIN || $rol->codigo === ROL_USI): ?>
							<a class="btn btn-app btn-app-zetta" href="escuela/agregar">
								<i class="fa fa-plus" id="btn-agregar"></i> Agregar
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta" href="escuela/ver/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta" href="escuela/editar/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta" href="escuela/caracteristicas/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Características
						</a>
						<a class="btn btn-app btn-app-zetta btn-app-zetta-active active" href="escuela/autoridades/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Autoridades
						</a>
						<div class="btn-group pull-right" role="group" style="margin-left: 5px;">
							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-external-link"></i> Otros accesos <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								
								<li><a class="dropdown-item btn-default" href="escuela_carrera/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-book"></i>Carreras</a></li>
								
								<li><a class="dropdown-item btn-default" href="division/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-tasks"></i> Cursos y Divisiones</a></li>
								
								<li><a class="dropdown-item btn-default" href="alumno/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-users"></i> Alumnos</a></li>
								<li><a class="dropdown-item btn-default" href="cargo/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-users"></i> Cargos</a></li>
								
								<li><a class="dropdown-item btn-default" href="servicio/listar/<?php echo $escuela->id . '/'; ?>"><i class="fa  fa-fw fa-bookmark"></i> Servicios</a></li>
								
								<li><a class="dropdown-item btn-default" href="asisnov/index/<?php echo $escuela->id . '/'; ?>"><i class="fa  fa-fw fa-print"></i> Asis y Nov</a></li>
							</ul>
						</div>
						<div class="row">
							<div class="form-group col-md-2">
								<?php echo $fields['numero']['label']; ?>
								<?php echo $fields['numero']['form']; ?>
							</div>
							<div class="form-group col-md-1">
								<?php echo $fields['anexo']['label']; ?>
								<?php echo $fields['anexo']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['cue']['label']; ?>
								<?php echo $fields['cue']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['subcue']['label']; ?>
								<?php echo $fields['subcue']['form']; ?>
							</div>
							<div class="form-group col-md-5">
								<?php echo $fields['nombre']['label']; ?>
								<?php echo $fields['nombre']['form']; ?>
							</div>
						</div>
						<div style="margin-top: 3%">
							<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
								<thead>
									<tr style="background-color: #f4f4f4">
										<th class="text-left">
											<a class="btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="escuela_autoridad/modal_agregar/<?= $escuela->id ?>"><i class="fa fa-plus"></i></a>
										</th>
										<th colspan="6" class="text-center">Autoridades</th>
									</tr>
									<tr>
										<th></th>
										<th>Autoridad</th>
										<th>Cuil</th>
										<th>Persona</th>
										<th>Tél. Fijo</th>
										<th>Celular</th>
										<th>E-mail</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($autoridades)): ?>
										<?php foreach ($autoridades as $autoridad): ?>
											<tr>
												<td>
													<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="escuela_autoridad/modal_eliminar/<?= $autoridad->id; ?>"><i class="fa fa-remove"></i></a>
													<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="escuela_autoridad/modal_editar/<?= $autoridad->id; ?>"><i class="fa fa-edit"></i></a>
												</td>
												<td><?= $autoridad->autoridad; ?></td>
												<td><?= $autoridad->cuil; ?></td>
												<td><?= $autoridad->persona; ?></td>
												<td><?= $autoridad->telefono_fijo; ?></td>
												<td><?= $autoridad->telefono_movil; ?></td>
												<td><?= $autoridad->email; ?></td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<td colspan="3" class="text-center">-- No tiene --</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="escuela/listar" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $escuela->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
