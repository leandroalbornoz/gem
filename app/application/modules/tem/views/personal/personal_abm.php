<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Tutores TEM <?php echo "Esc. $escuela->nombre_corto" ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"><?php echo "Esc. $escuela->nombre_corto" ?></a></li>
			<li><a href="tem/personal/listar/<?php echo $escuela->id; ?>/<?php echo date('Ym'); ?>">Tutores TEM</a></li>
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
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="tem/personal/ver/<?php echo $servicio->id; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="tem/personal/editar/<?php echo $servicio->id; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<?php if (in_array($this->rol->codigo, $this->roles_admin)): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar'] ?>" href="tem/personal/eliminar/<?php echo $servicio->id; ?>">
								<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta bg-blue <?php echo $class['editar']; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="tem/personal/modal_buscar_alumno/<?php echo $escuela->id; ?>/<?php echo $servicio->id; ?>">
							<i class="fa fa-plus" id="btn-editar"></i> Agregar alumno
						</a>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields['cuil']['label']; ?>
								<?php echo $fields['cuil']['form']; ?>
							</div>
							<div class="form-group col-md-6">
								<?php echo $fields['nombre']['label']; ?>
								<?php echo $fields['nombre']['form']; ?>
							</div>
						</div>
						<div class="row">
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
						</div>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields['situacion_revista']['label']; ?>
								<?php echo $fields['situacion_revista']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['fecha_alta']['label']; ?>
								<?php if ($servicio->fecha_alta): ?>
									<?php echo $fields['fecha_alta']['form']; ?>
								<?php else: ?>
									<input type="text" name="fecha_alta" value="" id="fecha_alta" class="form-control dateFormat">
								<?php endif; ?>
							</div>
							<?php if ($txt_btn !== 'Agregar'): ?>
								<div class="form-group col-md-3">
									<?php echo $fields['fecha_baja']['label']; ?>
									<?php echo $fields['fecha_baja']['form']; ?>
								</div>
								<div class="form-group col-md-3">
									<?php echo $fields['motivo_baja']['label']; ?>
									<?php echo $fields['motivo_baja']['form']; ?>
								</div>
								<div class="form-group col-md-4">
									<?php echo $fields['observaciones']['label']; ?>
									<?php echo $fields['observaciones']['form']; ?>
								</div>
							<?php endif; ?>
						</div>
						<div style="margin-top: 3%">
							<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
								<thead>
									<tr style="background-color: #f4f4f4" >
										<th style="text-align: center;" colspan="9">Alumnos</th>
									</tr>
									<tr>
										<th>Alumno</th>
										<th>Inicio</th>
										<th>Fin</th>
										<th>Escuela</th>
										<th>Carrera</th>
										<th>Materia</th>
										<th>Año Cursado</th>
										<th>Estado</th>
										<?php if ($txt_btn === 'Editar'): ?>
											<th></th>
										<?php endif; ?>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($alumnos)): ?>
										<?php foreach ($alumnos as $alumno): ?>
											<tr>
												<td><?= "$alumno->documento_tipo $alumno->documento - $alumno->apellido, $alumno->nombre"; ?></td>
												<td><?= (new DateTime($alumno->fecha_inicio))->format('d/m/Y'); ?></td>
												<td><?= empty($alumno->fecha_fin) ? '' : (new DateTime($alumno->fecha_fin))->format('d/m/Y'); ?></td>
												<td><?= $alumno->escuela; ?></td>
												<td><?= $alumno->carrera; ?></td>
												<td><?= $alumno->materia; ?></td>
												<td><?= $alumno->ciclo_lectivo; ?></td>
												<td><?= $alumno->estado; ?></td>
												<?php if ($txt_btn === 'Editar'): ?>
													<td class="text-center">
														<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="tem/alumno/modal_editar/<?php echo $alumno->id; ?>/<?php echo $servicio->id; ?>" title="Editar"><i class="fa fa-edit"></i></a>
														<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="tem/alumno/modal_eliminar/<?php echo $alumno->id; ?>/<?php echo $servicio->id; ?>" title="Eliminar"><i class="fa fa-remove"></i></a>
													</td>
												<?php endif; ?>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<td colspan="9">-- No tiene --</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="tem/personal/listar/<?php echo $escuela->id; ?>/<?php echo date('Ym'); ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo form_hidden('id', $servicio->id); ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<?php if ($txt_btn === 'Editar'): ?>
	<script>
		$(document).ready(function() {
			$('#observaciones').removeAttr('disabled');
		});
	</script>
<?php endif; ?>