<style>
	.parent ~ .cchild {
		display: none;
	}
	.open .parent ~ .cchild {
		display: table-row;
	}
	.parent {
		cursor: pointer;
	}
	.open .sign .fa-minus{
		display: block !important;
	}
	.open .sign .fa-plus{
		display: none;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Preinscripción Ciclo Lectivo <?php echo $ciclo_lectivo; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/escritorio/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li>Preinscripción alumnos</li>
			<li class="active"><?php echo "1° Instancia"; ?></li>
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
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="preinscripciones/escuela/instancia/<?php echo "$instancia/$preinscripcion->id/$redireccion"; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<a class=" btn bg-default btn-app " href="preinscripciones/escuela/anexo1_imprimir_pdf/<?php echo $preinscripcion->id ?>" title="Exportar Pdf Inscriptos" target="_blank"><i class="fa fa-file-pdf-o"></i> Anexo I</a>
						<?php if ($instancia === '2'): ?>
							<a class=" btn bg-default btn-app " href="preinscripciones/escuela/anexo3_imprimir_pdf/<?php echo $preinscripcion->id ?>" title="Exportar Pdf Inscriptos" target="_blank"><i class="fa fa-file-pdf-o"></i> Anexo III</a>
						<?php endif; ?>
						<div class="dropdown pull-right">
							<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-file"></i> Reportes
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu menu-right" aria-labelledby="dropdownMenu1">
								<li class="dropdown-header"><strong>Alumnos</strong></li>
								<li><a class="dropdown-item  bg-default" href="preinscripciones/escuela/anexo1_excel/<?php echo "$preinscripcion->id/$instancia/$redireccion" ?>" title="Exportar Excel"><i class="fa fa-file-excel-o"></i>Inscriptos</a></li>
								<?php if ($instancia === '2'): ?>
									<li><a class="dropdown-item  bg-default" href="preinscripciones/escuela/anexo3_excel/<?php echo "$preinscripcion->id/$instancia/$redireccion" ?>" title="Exportar excel"><i class="fa fa-file-excel-o"></i> Excedentes</a></li>
								<?php endif; ?>
							</ul>
						</div>
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
							<thead>
								<tr>
									<th style="text-align: center; border-color: #3c8dbc !important;" colspan="8">
										<span class="label label-success pull-left" style="margin-top: 0.25%;margin-left: 0.25%;"><?php echo $preinscripcion->vacantes; ?></span>
										<div style="width:100%; text-align: left;">&nbsp;&nbsp;Vacantes iniciales</div>
									</th>
								</tr>
								<?php if ($instancia === '2'): ?>
									<tr>
										<th style="text-align: center; border-color: #3c8dbc !important;" colspan="8">
											<span class="label label-success pull-left" style="margin-top: 0.25%;margin-left: 0.25%;"><?php echo (($preinscripcion->instancia_1)); ?></span>
											<div style="width:100%; text-align: left;">&nbsp;&nbsp;Preinscriptos en 1° Instancia</div>
										</th>
									</tr>
									<tr>
										<th style="text-align: center; border-color: #3c8dbc !important;" colspan="8">
											<span class="label label-success pull-left" style="margin-top: 0.25%;margin-left: 0.25%;"><?php echo (($preinscripcion->vacantes - $preinscripcion->instancia_1)); ?></span>
											<div style="width:100%; text-align: left;">&nbsp;&nbsp;Vacantes Disponibles en 2° Instancia</div>
										</th>
									</tr>
								<?php endif; ?>
							</thead>
							<?php foreach ($preinscripcion_tipos as $tipo): ?>
								<tbody>
									<?php $i = 1; ?>
									<tr class="parent">
										<th style="text-align: center; border-color: #3c8dbc !important;" colspan="8">
											<span class="label label-success pull-left" style="margin-top: 0.25%;margin-left: 0.25%;margin-right: 0.35%;border-color: white;"><?php echo!empty($alumnos[$tipo->id]) ? count($alumnos[$tipo->id]) : 0; ?></span>
											<div style="width:80px;" class="pull-left">
												<?php if ($fecha <= $fecha_hasta): ?>
													<?php if ($preinscripcion->inscriptos < $preinscripcion->vacantes): ?>
														<a class="btn btn-xs bg-blue pull-left" id="persona_buscar_listar_1" href="preinscripciones/preinscripcion_alumno/modal_buscar/<?php echo "$preinscripcion->id/$tipo->id/$redireccion"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-plus"></i> Agregar
														</a>
													<?php else : ?>
														<span>&nbsp;</span>
													<?php endif; ?>
												<?php else : ?>
													<span>&nbsp;</span>
												<?php endif; ?>
											</div>
											<span class="btn btn-xs btn-success sign pull-right" style="width: 25px;height: 22px;"><i class="fa fa-plus"></i><i class="fa fa-minus" style="display: none; margin-top: 3px;"></i></span>
											<div style="width:100%; padding-right:120px;"><?= $tipo->preinscripcion_tipo; ?></div>
										</th>
									</tr>
									<tr class="cchild">
										<td>
											<table class="table table-hover table-bordered table-condensed" role="grid">
												<thead>
													<tr>
														<th>Orden</th>
														<th>Nombre</th>
														<th>Documento</th>
														<th>F.Nac.</th>
														<th>Sexo</th>
														<th>Dirección</th>
														<th>Padre/Madre/Tutor</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php if (!empty($alumnos[$tipo->id])): ?>
														<?php foreach ($alumnos[$tipo->id] as $orden => $alumno): ?>
															<?php if ($alumno->estado === 'Inscripto'): ?>
																<tr>
																	<td><?= $i++; ?></td>
																	<td><?= $alumno->persona; ?></td>
																	<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
																	<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
																	<td><?= substr($alumno->sexo, 0, 1); ?></td>
																	<td><?= $alumno->direccion; ?></td>
																	<td><?= $alumno->familiares; ?></td>
																	<?php if ($instancia === '2'): ?>
																		<td style="text-align: center;">
																			<a class="btn btn-xs btn-warning" href="preinscripciones/preinscripcion_alumno/editar/<?= $alumno->id; ?>"><i class="fa fa-edit"></i></a>
																			<?php if (in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION)) && $preinscripcion->postulantes === '0' && $preinscripcion->derivados === '0'): ?>
																				<a class="btn btn-xs btn-danger " href="preinscripciones/preinscripcion_alumno/modal_eliminar_preinscripcion_instancia2/<?= $alumno->id; ?>" title="Eliminar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i></a>
																			<?php endif; ?>
																		</td>
																	<?php else: ?>
																		<td style="text-align: center;">
																			<a class="btn btn-xs btn-warning" href="preinscripciones/preinscripcion_alumno/editar/<?= $alumno->id; ?>" title="Editar" target="_blank"><i class="fa fa-edit"></i></a>
																			<?php if ($fecha <= $fecha_hasta || in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION))): ?>
																				<a class="btn btn-xs btn-primary" href="preinscripciones/preinscripcion_alumno/modal_editar_preinscripcion_tipo/<?= $alumno->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Modificar tipo de preinscripción"><i class="fa fa-fw fa-long-arrow-down"></i></a>
																				<?php if (in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION)) && $preinscripcion->postulantes === '0' && $preinscripcion->derivados === '0'): ?>
																					<a class="btn btn-xs btn-danger" href="preinscripciones/preinscripcion_alumno/modal_eliminar_preinscripcion_instancia/<?= $alumno->id; ?>" title="Eliminar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i></a>
																				<?php endif; ?>
																			<?php endif; ?>
																		</td>
																	<?php endif; ?>
																</tr>
															<?php endif; ?>
														<?php endforeach; ?>
													<?php else : ?>
														<tr>
															<td colspan="8" style="text-align: center;">-- No hay <?= $tipo->preinscripcion_tipo; ?> Preinscriptos --</td>
														</tr>
													<?php endif; ?>
												</tbody>
											</table>
										</td>
									</tr>
								<?php endforeach; ?>
								<?php if ($instancia === '2'): ?>
									<tr>
										<th style="text-align: center; border-color: #3c8dbc !important;" colspan="9">
											<span class="label label-success pull-left" style="margin-top: 0.25%;margin-left: 0.25%;margin-right: 0.35%;border-color: white;"><?php echo (($preinscripcion->vacantes - $preinscripcion->instancia_1 - $preinscripcion->instancia_2_i)); ?></span>
											<div style="width:100%; text-align: left;">	Vacantes Finales</div>
										</th>
									</tr>
								<tbody>
									<tr class="parent" >
										<th style="text-align: center; border-color: #3c8dbc !important;" colspan="9">
											<span class="label label-success pull-left" style="margin-top: 0.25%;margin-left: 0.25%;margin-right: 0.35%;border-color: white;"><?php echo!empty($alumnos[4]) ? $preinscripcion->postulantes + $preinscripcion->derivados : 0; ?></span>
											<div style="width:80px" class="pull-left">
												<?php if ($fecha <= $fecha_hasta): ?>
													<?php if ($preinscripcion->inscriptos >= $preinscripcion->vacantes): ?>
														<a class="btn btn-xs bg-blue pull-left" id="persona_buscar_listar_1" href="preinscripciones/preinscripcion_alumno/modal_buscar/<?php echo "$preinscripcion->id/$tipo->id/$redireccion"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-plus"></i> Agregar
														</a>
													<?php else : ?>
														<span>&nbsp;</span>
													<?php endif; ?>
												<?php else : ?>
													<span>&nbsp;</span>
												<?php endif; ?>
											</div>
											<span class="btn btn-xs btn-success sign pull-right" style="width: 25px;height: 22px;"><i class="fa fa-plus"></i><i class="fa fa-minus" style="display: none; margin-top: 3px;"></i></span>
											<div style="width:100%; padding-right:130px;">Alumnos Postulantes/Excedentes</div>
										</th>
									</tr>
									<tr class="cchild">
										<td>
											<table class="table table-hover table-bordered table-condensed" role="grid">
												<thead>
													<tr>
														<th>Orden</th>
														<th>Nombre</th>
														<th>Documento</th>
														<th>F.Nac.</th>
														<th>Sexo</th>
														<th>Dirección</th>
														<th>Padre/Madre/Tutor</th>
														<th>Escuela de destino</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php if (!empty($alumnos[4])): ?>
														<?php foreach ($alumnos[4] as $orden => $alumno): ?>
															<?php if ($alumno->estado === 'Postulante'): ?>
																<tr>
																	<td><?= $i++; ?></td>
																	<td><?= $alumno->persona; ?></td>
																	<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
																	<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
																	<td><?= substr($alumno->sexo, 0, 1); ?></td>
																	<td><?= $alumno->direccion; ?></td>
																	<td><?= $alumno->familiares; ?></td>
																	<td>No derivado</td>
																	<td><a class="btn btn-xs btn-warning" href="preinscripciones/preinscripcion_alumno/editar/<?= $alumno->id; ?>"><i class="fa fa-edit"></i></a>
																		<?php if (in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION))): ?>
																			<a class="btn btn-xs btn-danger" href="preinscripciones/preinscripcion_alumno/modal_eliminar_preinscripcion_instancia2/<?= $alumno->id; ?>" title="Eliminar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i></a>
																		<?php endif; ?>
																	</td>
																</tr>
															<?php endif; ?>
														<?php endforeach; ?>
														<?php if (!empty($alumnos[5])): ?>
															<?php foreach ($alumnos[5] as $orden => $alumno): ?>
																<?php if ($alumno->estado === 'Derivado'): ?>
																	<tr>
																		<td><?= $i++; ?></td>
																		<td><?= $alumno->persona; ?></td>
																		<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
																		<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
																		<td><?= substr($alumno->sexo, 0, 1); ?></td>
																		<td><?= $alumno->direccion; ?></td>
																		<td><?= $alumno->familiares; ?></td>
																		<td><?= $alumno->escuela_derivada; ?></td>
																		<td></td>
																	</tr>
																<?php endif; ?>
															<?php endforeach; ?>
														<?php endif; ?>
													<?php else : ?>
														<tr>
															<td colspan="9" style="text-align: center;">-- No hay alumnos postulantes inscriptos --</td>
														</tr>
													<?php endif; ?>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							<?php endif; ?>
							<tfoot>
								<tr>
									<th style="text-align: center; border-color: #3c8dbc !important;" colspan="8">
										<?php if ($instancia === '2'): ?>
											<span class="label label-success pull-left" style="margin-top: 0.25%;margin-left: 0.25%;"><?php echo (($preinscripcion->vacantes - $preinscripcion->instancia_1 - $preinscripcion->instancia_2_i)); ?></span>
										<?php else: ?>
											<span class="label label-success pull-left" style="margin-top: 0.25%;margin-left: 0.25%;"><?php echo (($preinscripcion->vacantes - $preinscripcion->instancia_1)); ?></span>
										<?php endif; ?>
										<div style="width:100%; text-align: left;">&nbsp;&nbsp;Vacantes disponibles</div>
									</th>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="box-footer">
						<?php if ($redireccion === 'supervision'): ?>
							<a class="btn btn-default" href="supervision/escritorio/<?= $escuela->supervision_id; ?>" title="Volver">Volver</a>
						<?php else: ?>
							<a class="btn btn-default" href="escuela/escritorio/<?php echo $escuela->id; ?>" title="Volver">Volver</a>
						<?php endif; ?>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar') ? form_hidden('id', $preinscripcion->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
<?php if (isset($abrir_modal) && $abrir_modal): ?>
			setTimeout(function() {
				$('#persona_buscar_listar_<?php echo $tipo_modal; ?>').click();
			}, 500);
<?php endif; ?>
		$('table tr.parent').on('click', function() {
			var tbody = $(this).closest('tbody');
			tbody.find('.cchild').fadeToggle('fast').end().toggleClass('open');
		});
	});
</script>
