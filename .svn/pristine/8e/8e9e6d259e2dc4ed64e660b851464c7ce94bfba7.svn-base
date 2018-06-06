<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Personal de <?= $area->codigo; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="areas/area/ver/<?php echo $area->id; ?>"><?php echo "$area->codigo $area->descripcion"; ?></a></li>
			<li><a href="areas/personal/listar/<?php echo $area->id; ?>">Personal</a></li>
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
						<?php if ($metodo !== 'ver_funcion'): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="areas/personal/ver/<?php echo $servicio->id; ?>">
								<i class="fa fa-search" id="btn-ver"></i> Ver
							</a>
							<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="areas/personal/editar/<?php echo $servicio->id; ?>">
								<i class="fa fa-edit" id="btn-editar"></i> Editar
							</a>
							<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="areas/personal/eliminar/<?php echo $servicio->id; ?>">
								<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
							</a>
							<a class="btn btn-app btn-app-zetta <?php echo $class['mover']; ?>" href="areas/personal/mover/<?php echo $servicio->id; ?>/<?php echo $area->id ?>">
								<i class="fa fa-exchange" id="btn-mover"></i> Mover
							</a>
						<?php else: ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="areas/personal/ver_funcion/<?php echo $servicio_funcion->id; ?>">
								<i class="fa fa-search" id="btn-ver"></i> Ver
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta" href="areas/personal/horarios/<?php echo (!empty($servicio->id)) ? $servicio->id : ''; ?>">
							<i class="fa fa-clock-o"></i> Horarios
						</a>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields['cuil']['label']; ?>
								<div class="input-group">
									<?php echo $fields['cuil']['form']; ?>
									<span class="input-group-btn">
										<a class="btn btn-default" title="Ver persona" href="datos_personal/ver/<?= $servicio->id; ?>"><i class="fa fa-search"></i></a>
									</span>
								</div>
							</div>
							<div class="form-group col-md-6">
								<?php echo $fields['nombre']['label']; ?>
								<?php echo $fields['nombre']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['liquidacion']['label']; ?>
								<?php echo $fields['liquidacion']['form']; ?>
							</div>
							<div class="form-group col-md-6">
								<?php echo $fields['regimen']['label']; ?>
								<?php echo $fields['regimen']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['carga_horaria']['label']; ?>
								<?php echo $fields['carga_horaria']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['condicion_cargo']['label']; ?>
								<?php echo $fields['condicion_cargo']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['situacion_revista']['label']; ?>
								<?php echo $fields['situacion_revista']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['fecha_alta']['label']; ?>
								<?php echo $fields['fecha_alta']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['fecha_baja']['label']; ?>
								<?php echo $fields['fecha_baja']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['motivo_baja']['label']; ?>
								<?php echo $fields['motivo_baja']['form']; ?>
							</div>
							<div class="form-group col-md-12">
								<?php echo $fields['observaciones']['label']; ?>
								<?php echo $fields['observaciones']['form']; ?>
							</div>
							<div class="form-group col-md-6">
								<?php echo $fields_servicio['area']['label']; ?>
								<?php echo $fields_servicio['area']['form']; ?>
							</div>
							<div class="form-group col-md-6">
								<?php echo $fields_destino['area_destino']['label']; ?>
								<?php echo $fields_destino['area_destino']['form']; ?>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="areas/personal/listar/<?php echo $area->id; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Mover'), 'Mover'); ?>
						<?php echo form_hidden('id', $servicio->id); ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
