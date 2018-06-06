<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Alumno
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"><?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="division/listar/<?= $escuela->id ?>">Cursos y Divisiones</a></li>
			<li><a href="division/ver/<?= $division_id ?>"><?php echo "$division"; ?></a></li>
			<li><a href="division/alumnos/<?php echo "$division_id/$ciclo_lectivo"; ?>">Alumnos</a></li>
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
					<div class="box-header with-border">
						<h3 class="box-title">
							<?php echo "$alumno->apellido, $alumno->nombre"; ?>
						</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta" href="alumno/ver/<?php echo $alumno_division_id; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta" href="alumno/editar/<?php echo $alumno_division_id; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="alumno/caracteristicas/<?php echo $alumno_division_id; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Características
						</a>
						<a class="btn btn-app btn-app-zetta" href="alumno/ficha_psicopedagogica_ver/<?php echo $alumno_division_id; ?>">
							<i class="fa fa-folder-o" id="btn-editar"></i> F. Psicopedagógica
						</a>
						<div class="row">
							<div class="form-group col-md-2">
								<?php echo $fields['documento_tipo']['label']; ?>
								<?php echo $fields['documento_tipo']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['documento']['label']; ?>
								<?php echo $fields['documento']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['apellido']['label']; ?>
								<?php echo $fields['apellido']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['nombre']['label']; ?>
								<?php echo $fields['nombre']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['sexo']['label']; ?>
								<?php echo $fields['sexo']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_nacimiento']['label']; ?>
								<?php echo $fields['fecha_nacimiento']['form']; ?>
							</div>
						</div>
						<?php if (!empty($fields_tipos)): ?>
							<ul class="nav nav-tabs" role="tablist">
								<?php $tab_class = 'active'; ?>
								<?php foreach ($fields_tipos as $tipo => $fields_caracteristicas): ?>
									<li role="presentation" class="<?= $tab_class; ?>"><a href="#<?= strtolower(str_replace('/', '_', str_replace(' ', '_', $tipo))); ?>" aria-controls="<?= strtolower(str_replace('/', '_', str_replace(' ', '_', $tipo))); ?>" role="tab" data-toggle="tab"><?= $tipo; ?></a></li>
									<?php $tab_class = ''; ?>
								<?php endforeach; ?>
							</ul>
							<div class="tab-content">
								<?php $tab_class = 'active'; ?>
								<?php foreach ($fields_tipos as $tipo => $fields_caracteristicas): ?>
									<div role="tabpanel" class="tab-pane <?= $tab_class; ?>" id="<?= strtolower(str_replace('/', '_', str_replace(' ', '_', $tipo))); ?>">
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
						<a class="btn btn-default" href="alumno/<?php echo empty($txt_btn) ? "listar/$escuela->id" : "ver/$alumno_division_id"; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $alumno->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>