<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Alumno
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="busqueda/buscar_alumno">Búsqueda de alumnos</a></li>
			<li class="active">Ver alumno</li>
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
							<?php if (!empty($alumno)): ?>
								<?php echo "$alumno->documento_tipo $alumno->documento | $alumno->apellido, $alumno->nombre - $alumno->cuil "; ?>
							<?php endif; ?>
						</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta" href="busqueda/buscar_alumno">
							<i class="fa fa-search"></i> Búsqueda de alumnos
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="busqueda/ver_alumno/<?php echo $alumno->id; ?>">
							<i class="fa fa-search"></i> Ver alumno
						</a>
						<div class="row">
							<div class="form-group col-md-2">
								<?php echo $fields['sexo']['label']; ?>
								<?php echo $fields['sexo']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_nacimiento']['label']; ?>
								<?php echo $fields['fecha_nacimiento']['form']; ?>
							</div>
							<div class="form-group col-md-8">
								<?php echo $fields['email_contacto']['label']; ?>
								<?php echo $fields['email_contacto']['form']; ?>
							</div>
						</div>
						<br><strong><p style="font-size: 17px">Datos domicilio</p></strong>
						<div class="row">
							<div class="form-group col-sm-6">
								<?php echo $fields['direccion']['label']; ?>
								<?php echo $fields['direccion']['form']; ?>
							</div>
							<div class="form-group col-sm-6">
								<?php echo $fields['localidad']['label']; ?>
								<?php echo $fields['localidad']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields['telefono_fijo']['label']; ?>
								<?php echo $fields['telefono_fijo']['form']; ?>
							</div>
							<div class="form-group col-sm-3">
								<?php echo $fields['telefono_movil']['label']; ?>
								<?php echo $fields['telefono_movil']['form']; ?>
							</div>
							<div class="form-group col-sm-3">
								<?php echo $fields['prestadora']['label']; ?>
								<?php echo $fields['prestadora']['form']; ?>
							</div>
							<div class="form-group col-md-4">
								<?php echo $fields['email']['label']; ?>
								<?php echo $fields['email']['form']; ?>
							</div>
						</div><br>
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
							<thead>
								<tr style="background-color: #f4f4f4" >
									<th style="text-align: center;" colspan="10">
										Familiares
									</th>
								</tr>
								<tr>
									<th>Parentesco</th>
									<th>Documento</th>
									<th>Apellido</th>
									<th>Nombre</th>
									<th>Nivel de estudios</th>
									<th>Ocupacion</th>
									<th>Celular</th>
									<th>Email</th>
									<th>Convive</th>
								</tr>
							</thead>
							<tbody>
								<?php $madre = FALSE; ?>
								<?php $padre = FALSE; ?>
								<?php if (!empty($parientes)): ?>
									<?php foreach ($parientes as $pariente): ?>
										<?php $madre |= $pariente->parentesco === 'Madre'; ?>
										<?php $padre |= $pariente->parentesco === 'Padre'; ?>
										<tr>
											<td><?= $pariente->parentesco; ?></td>
											<td><?= $pariente->documento; ?></td>
											<td><?= $pariente->apellido; ?></td>
											<td><?= $pariente->nombre; ?></td>
											<td><?= $pariente->nivel_estudio; ?></td>
											<td><?= $pariente->ocupacion; ?></td>
											<td><?= $pariente->telefono_movil; ?></td>
											<td><?= $pariente->email; ?></td>
											<td><?php
												if ($pariente->convive == 1) {
													echo 'SI';
												} else {
													echo 'No';
												}
												?></td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td style="text-align: center;" colspan="10">
											-- No tiene --
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
						<div class="row">
							<?php if (!$padre && (!empty($alumno->documento_padre) || !empty($alumno->nombre_padre))): ?>
								<div class="col-sm-6">
									<label>Padre (SIGA)</label>
									<input class="form-control" readonly value="<?= "$alumno->documento_padre $alumno->nombre_padre"; ?>">
								</div>
							<?php endif; ?>
							<?php if (!$madre && (!empty($alumno->documento_madre) || !empty($alumno->nombre_madre))): ?>
								<div class="col-sm-6">
									<label>Madre (SIGA)</label>
									<input class="form-control" readonly value="<?= "$alumno->documento_madre $alumno->nombre_madre"; ?>">
								</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="busqueda/buscar_alumno" title="Volver">Volver</a>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Trayectoria</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body ">
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
							<thead>
								<tr>
									<th>C. lectivo</th>
									<th>Escuela</th>
									<th>Division</th>
									<th>Legajo</th>
									<th>Desde</th>
									<th>Causa de entrada</th>
									<th>Hasta</th>
									<th>Causa de salida</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($trayectoria)): ?>
									<?php foreach ($trayectoria as $division): ?>
										<tr>
											<td><?= $division->ciclo_lectivo; ?></td>
											<td><?= "$division->numero_escuela - $division->nombre_escuela"; ?></td>
											<td><?= "$division->curso $division->division"; ?></td>
											<td><?= $division->legajo; ?></td>
											<td><?= empty($division->fecha_desde) ? '' : (new DateTime($division->fecha_desde))->format('d/m/y'); ?></td>
											<td><?= $division->causa_entrada; ?></td>
											<td><?= empty($division->fecha_hasta) ? '' : (new DateTime($division->fecha_hasta))->format('d/m/y'); ?></td>
											<td><?= $division->causa_salida; ?></td>
											<td><?= $division->estado; ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td style="text-align: center;" colspan="9">
											-- Sin trayectoria --
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>