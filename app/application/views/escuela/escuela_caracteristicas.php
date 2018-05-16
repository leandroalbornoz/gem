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
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="escuela/caracteristicas/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Características
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar'] === 'disabled' ? 'disabled' : ''; ?>" href="escuela/autoridades/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Autoridades
						</a>
						<div class="btn-group pull-right" role="group" style="margin-left: 5px;">
							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-external-link"></i> Otros accesos <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								
								<li><a class="dropdown-item btn-default" href="escuela_carrera/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-book"></i>Carreras</a></li>
								
								<li><a class="dropdown-item btn-default" href="division/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw  fa-tasks"></i> Cursos y Divisiones</a></li>
								
								<li><a class="dropdown-item btn-default" href="alumno/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-users"></i> Alumnos</a></li>
								<li><a class="dropdown-item btn-default" href="cargo/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-users"></i> Cargos</a></li>
								
								<li><a class="dropdown-item btn-default" href="servicio/listar/<?php echo $escuela->id . '/'; ?>"><i class="fa fa-fw fa-bookmark"></i> Servicios</a></li>
								
								<li><a class="dropdown-item btn-default" href="asisnov/index/<?php echo $escuela->id . '/'; ?>"><i class="fa fa-fw fa-print"></i> Asis y Nov</a></li>
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
						<?php if (!empty($fields_tipos)): ?>
							<ul class="nav nav-tabs" role="tablist">
								<?php $tab_class = 'active'; ?>
								<?php foreach ($fields_tipos as $tipo => $fields_caracteristicas): ?>
									<li role="presentation" class="<?= $tab_class; ?>"><a href="#<?= strtolower(str_replace(' ', '_', $tipo)); ?>" aria-controls="<?= strtolower(str_replace(' ', '_', $tipo)); ?>" role="tab" data-toggle="tab"><?= $tipo; ?></a></li>
									<?php $tab_class = ''; ?>
								<?php endforeach; ?>
							</ul>
							<div class="tab-content">
								<?php $tab_class = 'active'; ?>
								<?php foreach ($fields_tipos as $tipo => $fields_caracteristicas): ?>
									<div role="tabpanel" class="tab-pane <?= $tab_class; ?>" id="<?= strtolower(str_replace(' ', '_', $tipo)); ?>">
										<?php $tab_class = ''; ?>
										<div class="row">
											<?php foreach ($fields_caracteristicas as $field): ?>
												<div class="form-group col-sm-3">
													<?php echo $field['label']; ?>
													<?php echo $field['form']; ?>
												</div>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
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