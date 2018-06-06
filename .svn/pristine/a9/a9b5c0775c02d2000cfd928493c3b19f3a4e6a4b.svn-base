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
			<li class="active"><?php echo "Ficha psicopedagógica"; ?></li>
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
						<a class="btn btn-app btn-app-zetta	<?php echo $class['ver']; ?>" href="alumno/ficha_psicopedagogica_ver/<?php echo $alumno_division_id; ?>">
							<i class="fa fa-folder-o" id="btn-editar"></i> F. Psicopedagógica
						</a>
						<?php if ($edicion): ?>
							<?php if (empty($ficha_p_ex)): ?>
								<a class="btn bg-green btn-app btn-app-zett" href="alumno/ficha_psicopedagogica_crear/<?php echo $alumno_division_id; ?>">
									<i class="fa fa-id-card-o"></i> Crear ficha 
								</a>
							<?php endif; ?>
							<?php if (!empty($ficha_p_ex)): ?>
								<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="alumno/ficha_psicopedagogica/<?php echo $alumno_division_id; ?>">
									<i class="fa fa-folder-open-o" id="btn-editar"></i> F. Psico. Editar
								</a>
							<?php endif; ?>
						<?php endif; ?>
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
						<hr>
						<?php if (empty($ficha_p_ex) && $txt_btn !== 'Agregar'): ?>
							<h4 align="center"><u><strong>EL ALUMNO NO POSEE FICHA DE ARTICULACÍON PSICOPEDAGÓGICA SOCIAL</strong></u></h4>
						<?php else: ?>
							<h4 align="center"><u><strong>FICHA DE ARTICULACÍON PSICOPEDAGÓGICA SOCIAL</strong></u></h4>
							<?php if (!empty($fields_tipos)): ?>
								<div class="tab-content">
									<?php $tab_class = 'active'; ?>
									<?php foreach ($fields_tipos as $tipo => $fields_caracteristicas): ?>
										<h3><u><em><?php echo "$tipo:"; ?></em></u></h3>
										<?php $tab_class = ''; ?>
										<?php $tab_class = 'active'; ?>
										<?php $tab_class = ''; ?>
										<div class="row">
											<?php foreach ($fields_caracteristicas as $field): ?>
												<div class="form-group col-sm-6">
													<big><?php echo $field['label'] . ":"; ?></big>
												</div>
												<div class="form-group col-sm-6">
													<?php echo $field['form']; ?>
												</div>
											<?php endforeach; ?>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
							<div class="row">
								<div class="form-group col-sm-12">
									<big><?php echo $fields_ficha['situacion_familiar']['label']; ?></big>
								</div>
								<div class="form-group col-sm-12">
									<?php echo $fields_ficha['situacion_familiar']['form']; ?>
								</div>	
							</div>
							<div class="row">
								<div class="form-group col-sm-12">
									<big><?php echo $fields_ficha['actividad_laboral']['label']; ?></big>
								</div>
								<div class="form-group col-sm-12">
									<?php echo $fields_ficha['actividad_laboral']['form']; ?>
								</div>	
							</div>
						<?php endif; ?>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="alumno/<?php echo empty($txt_btn) ? "ver/$alumno_division_id" : "ficha_psicopedagogica_ver/$alumno_division_id"; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Agregar' || $txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $alumno->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
