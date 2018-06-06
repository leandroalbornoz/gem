<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Separar cargo de servicio Esc. <?= $escuela->nombre_corto; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $servicio->escuela_id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="servicio/listar/<?php echo $servicio->escuela_id . '/'; ?>">Servicios</a></li>
			<li class="active">Separar cargo</li>
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
						<a class="btn btn-app btn-app-zetta" href="servicio/ver/<?php echo (!empty($servicio->id)) ? $servicio->id : ''; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<?php if ($edicion): ?>
							<a class="btn btn-app btn-app-zetta" href="servicio/editar/<?php echo (!empty($servicio->id)) ? $servicio->id : ''; ?>">
								<i class="fa fa-edit"></i> Editar
							</a>
							<?php if ($rol->codigo === ROL_ADMIN || $rol->codigo === ROL_USI): ?>
								<a class="btn btn-app btn-app-zetta" href="servicio/eliminar/<?php echo (!empty($servicio->id)) ? $servicio->id : ''; ?>">
									<i class="fa fa-ban"></i> Eliminar
								</a>
							<?php endif; ?>
							<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="servicio/separar_cargo/<?php echo (!empty($servicio->id)) ? $servicio->id : ''; ?>">
								<i class="fa fa-unlink"></i> Separar cargo
							</a>
						<?php endif; ?>
						<div class="row">
							<div class="form-group col-md-2">
								<?php echo $fields['cuil']['label']; ?>
								<?php echo $fields['cuil']['form']; ?>
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
						</div>
						<div class="row">
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
							<?php if ($servicio->situacion_revista_id === '2'): ?>
								<div class="form-group col-md-6">
									<?php echo $fields['reemplazado']['label']; ?>
									<?php echo $fields['reemplazado']['form']; ?>
								</div>
								<div class="form-group col-md-6">
									<?php echo $fields['articulo_reemplazo']['label']; ?>
									<?php echo $fields['articulo_reemplazo']['form']; ?>
								</div>
							<?php endif; ?>
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
						<h4>Cargo actual</h4>
						<div class="row">
							<div class="form-group col-md-4">
								<?php echo $fields['regimen']['label']; ?>
								<?php echo $fields['regimen']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['carga_horaria']['label']; ?>
								<?php echo $fields['carga_horaria']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['division']['label']; ?>
								<?php echo $fields['division']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['espacio_curricular']['label']; ?>
								<?php echo $fields['espacio_curricular']['form']; ?>
							</div>
						</div>
						<h4>Cargo separado</h4>
						<div class="row">
							<div class="form-group col-md-4">
								<?php echo $fields['liquidacion_regimen']['label']; ?>
								<?php echo $fields['liquidacion_regimen']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['liquidacion_carga_horaria']['label']; ?>
								<?php echo $fields['liquidacion_carga_horaria']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['division']['label']; ?>
								<?php echo $fields['division']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['espacio_curricular']['label']; ?>
								<?php echo $fields['espacio_curricular']['form']; ?>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="servicio/listar/<?php echo $servicio->escuela_id . '/'; ?>" title="Cancelar">Cancelar</a>
						<input type="submit" value="Separar cargo" class="btn btn-primary pull-right" title="Separar cargo">
						<?php echo form_hidden('id', $servicio->id); ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>