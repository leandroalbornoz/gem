<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Preinscripción Ciclo Lectivo <?php echo $ciclo_lectivo; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/escritorio/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li>Preinscripción alumnos</li>
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
					<div class="box-header with-border">
						<h3 class="box-title">Esc. <?php echo $escuela->nombre_largo; ?> - Preinscripción a 1° grado <?php echo $ciclo_lectivo; ?></h3>
					</div>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="preinscripciones/preinscripcion/ver/<?php echo (!empty($preinscripcion->id)) ? $preinscripcion->id : ''; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="preinscripciones/preinscripcion/editar/<?php echo (!empty($preinscripcion->id)) ? $preinscripcion->id : ''; ?>">
							<i class="fa fa-edit"></i> Editar
						</a>
						<?php if (isset($preinscripcion) && $instancia !== '0'): ?>
							<a class="btn bg-blue btn-app btn-app-zetta" id="persona_buscar_listar" href="preinscripciones/preinscripcion_alumno/modal_buscar/<?php echo "$preinscripcion->id"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal">
								<?php if ($preinscripcion->inscriptos >= $preinscripcion->vacantes): ?>
									<i class="fa fa-plus"></i> Agregar Postulante
								<?php else: ?>
									<i class="fa fa-plus"></i> Agregar Alumno
								<?php endif; ?>
							</a>
							<a class=" btn bg-green btn-success btn-app " href="preinscripciones/preinscripcion/anexo1_excel/<?php echo $preinscripcion->id ?>" title="Exportar excel"><i class="fa fa-file-excel-o"></i> Exportar Inscriptos</a>
							<a class=" btn bg-red btn-success btn-app " href="preinscripciones/preinscripcion/anexo1_imprimir_pdf/<?php echo $preinscripcion->id ?>" title="Exportar Pdf Inscriptos" target="_blank"><i class="fa fa-file-pdf-o"></i> Exportar Inscriptos</a>
							<a class=" btn bg-green btn-success btn-app " href="preinscripciones/preinscripcion/anexo2_excel/<?php echo $preinscripcion->id ?>" title="Exportar excel"><i class="fa fa-file-excel-o"></i> Exportar Postulantes</a>
							<a class=" btn bg-red btn-success btn-app " href="preinscripciones/preinscripcion/anexo2_imprimir_pdf/<?php echo $preinscripcion->id ?>" title="Exportar Pdf Postulantes" target="_blank"><i class="fa fa-file-pdf-o"></i> Exportar Postulantes</a>
						<?php endif; ?>
						<div class="row">
							<?php foreach ($fields as $field): ?>
								<div class="form-group col-sm-4">
									<?php echo $field['label']; ?>
									<?php echo $field['form']; ?>
								</div>
							<?php endforeach; ?>
						</div>
						<div>
							<?php if ($instancia !== '0'): ?>
								<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
									<thead>
										<tr style="background-color: #f4f4f4" >
											<th style="text-align: center;" colspan="8">
												Alumnos Inscriptos
											</th>
										</tr>
										<tr>
											<th>Orden</th>
											<th>Nombre</th>
											<th>Documento</th>
											<th>F. Nacimiento</th>
											<th>Sexo</th>
											<th>Dirección</th>
											<th>Padre/Madre/Tutor</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($alumnos)): ?>
											<?php foreach ($alumnos as $orden => $alumno): ?>
												<?php if ($alumno->estado === 'Inscripto'): ?>
													<tr>
														<td><?= $orden + 1; ?></td>
														<td><?= $alumno->persona; ?></td>
														<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
														<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
														<td><?= substr($alumno->sexo, 0, 1); ?></td>
														<td><?= $alumno->direccion; ?></td>
														<td><?= $alumno->familiares; ?></td>
														<td><a class="btn btn-xs btn-warning" href="preinscripciones/preinscripcion_alumno/editar/<?= $alumno->id; ?>"><i class="fa fa-edit"></i></a></td>
													</tr>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php else : ?>
											<tr>
												<td colspan="8" style="text-align: center;">-- No tiene --</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
								<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
									<thead>
										<tr style="background-color: #f4f4f4" >
											<th style="text-align: center;" colspan="8">
												Alumnos Postulantes
											</th>
										</tr>
										<tr>
											<th>Orden</th>
											<th>Nombre</th>
											<th>Documento</th>
											<th>F. Nacimiento</th>
											<th>Sexo</th>
											<th>Direccion</th>
											<th>Padre/Madre/Tutor</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($alumnos)): ?>
											<?php foreach ($alumnos as $orden => $alumno): ?>
												<?php if ($alumno->estado === 'Postulante'): ?>
													<tr>
														<td><?= $orden + 1; ?></td>
														<td><?= $alumno->persona; ?></td>
														<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
														<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
														<td><?= substr($alumno->sexo, 0, 1); ?></td>
														<td><?= $alumno->direccion; ?></td>
														<td><?= $alumno->familiares; ?></td>
														<td><a class="btn btn-xs btn-warning" href="preinscripciones/preinscripcion_alumno/editar/<?= $alumno->id; ?>"><i class="fa fa-edit"></i></a></td>
													</tr>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php else : ?>
											<tr>
												<td colspan="8" style="text-align: center;">-- No tiene --</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							<?php endif; ?>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="escuela/escritorio/<?php echo $escuela->id; ?>" title="Volver">Volver</a>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar') ? form_hidden('id', $preinscripcion->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
					<div style="display:none;" id="div_persona_buscar_listar"></div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php if (isset($abrir_modal) && $abrir_modal): ?>
	<script>
		$(document).ready(function() {
			$('#div_persona_buscar_listar').append('<a id="a_persona_buscar_listar" href="preinscripciones/preinscripcion_alumno/modal_buscar/<?php echo $preinscripcion->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"></a>');
			setTimeout(function() {
				$('#a_persona_buscar_listar').click();
			}, 500);
		});
	</script>
<?php endif; ?>
