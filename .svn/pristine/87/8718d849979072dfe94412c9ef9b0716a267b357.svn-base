<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Carga de evaluación
		</h1>
		<ol class="breadcrumb">
			<li><a href="escritorio"><i class="fa fa-home"></i>Escritorio</a></li>
			<li><?php echo $escuela->nombre_corto; ?></li>
			<li><a href="operativo_evaluar/evaluar_operativo/listar_divisiones/<?php echo $escuela->id; ?>">Cursos y Divisiones</a></li>
			<li><a href="operativo_evaluar/evaluar_operativo/listar_alumnos/<?php echo $division->id; ?>"><?php echo "$division->curso $division->division"; ?></a></li>
			<li class="active">Evaluación</li>
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
					<div class="box-header whit-border">
						<h3 class="box-title">Alumno: <?php echo "$alumno->apellido, $alumno->nombre $alumno->documento_tipo: $alumno->documento" ?></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<?php if ($txt_btn === 'Agregar'): ?>
							<a class="btn btn-app btn-app-zetta disabled " href="operativo_evaluar/evaluar_operativo/listar_alumnos/<?php echo "$division->id"; ?>">
								<i class="fa fa-plus" id="btn-agregar"></i> Agregar
							</a>
						<?php else: ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="operativo_evaluar/evaluar_operativo/ver/<?php echo!empty($evaluar_operativo->id) ? "$evaluar_operativo->id/$alumno_division->id" : $alumno_division->id; ?>">
								<i class="fa fa-search" id="btn-ver"></i> Ver
							</a>
							<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="operativo_evaluar/evaluar_operativo/editar/<?php echo!empty($evaluar_operativo->id) ? "$evaluar_operativo->id/$alumno_division->id" : $alumno_division->id; ?>">
								<i class="fa fa-edit" id="btn-editar"></i> Editar
							</a>
							<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="operativo_evaluar/evaluar_operativo/eliminar/<?php echo!empty($evaluar_operativo->id) ? "$evaluar_operativo->id/$alumno_division->id" : $alumno_division->id; ?>">
								<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
							</a>
						<?php endif; ?>
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group">
									<?php echo $fields['puntuacion_1']['label']; ?>
									<?php echo $fields['puntuacion_1']['form']; ?>
								</div>
								<div class="form-group">
									<?php echo $fields['puntuacion_2']['label']; ?>
									<?php echo $fields['puntuacion_2']['form']; ?>
								</div>
								<div class="form-group">
									<?php echo $fields['puntuacion_3']['label']; ?>
									<?php echo $fields['puntuacion_3']['form']; ?>
								</div>
								<div class="form-group">
									<?php echo $fields['puntuacion_4']['label']; ?>
									<?php echo $fields['puntuacion_4']['form']; ?>
								</div>
								<div class="form-group">
									<?php echo $fields['puntuacion_5']['label']; ?>
									<?php echo $fields['puntuacion_5']['form']; ?>
								</div>
								<div class="form-group">
									<?php echo $fields['puntuacion_6a']['label']; ?>
									<?php echo $fields['puntuacion_6a']['form']; ?>
								</div>
								<div class="form-group">
									<?php echo $fields['puntuacion_6b']['label']; ?>
									<?php echo $fields['puntuacion_6b']['form']; ?>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<?php echo $fields['puntuacion_7']['label']; ?>
									<?php echo $fields['puntuacion_7']['form']; ?>
								</div>
								<div class="form-group">
									<?php echo $fields['puntuacion_8']['label']; ?>
									<?php echo $fields['puntuacion_8']['form']; ?>
								</div>
								<div class="form-group">
									<?php echo $fields['puntuacion_9']['label']; ?>
									<?php echo $fields['puntuacion_9']['form']; ?>
								</div>
								<div class="form-group">
									<?php echo $fields['puntuacion_10a']['label']; ?>
									<?php echo $fields['puntuacion_10a']['form']; ?>
								</div>
								<div class="form-group">
									<?php echo $fields['puntuacion_10b']['label']; ?>
									<?php echo $fields['puntuacion_10b']['form']; ?>
								</div>
								<div class="form-group">
									<?php echo $fields['puntuacion_11a']['label']; ?>
									<?php echo $fields['puntuacion_11a']['form']; ?>
								</div>
								<div class="form-group">
									<?php echo $fields['puntuacion_11b']['label']; ?>
									<?php echo $fields['puntuacion_11b']['form']; ?>
								</div>
								<div class="form-group">
									<?php echo $fields['puntuacion_11c']['label']; ?>
									<?php echo $fields['puntuacion_11c']['form']; ?>
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="operativo_evaluar/evaluar_operativo/listar_alumnos/<?php echo $division->id; ?>" title="Cancelar">Cancelar</a>
						<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $evaluar_operativo->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>