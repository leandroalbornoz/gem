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
	tbody {
		color: #212121;
	}
	.parent > *:last-child {
		width: 30px;
	}
	.parent i {
		transform: rotate(0deg);
		transition: transform .3s cubic-bezier(.4,0,.2,1);
		margin: -.5rem;
		padding: .5rem;
	}
	.open .parent i {
		transform: rotate(180deg)
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
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="preinscripciones/escuela/instancia_1/<?php echo "$preinscripcion->ciclo_lectivo/$escuela->id/$redireccion"; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<a class=" btn bg-default btn-app " href="preinscripciones/escuela/anexo1_imprimir_pdf/<?php echo $preinscripcion->id ?>" title="Exportar Pdf Inscriptos" target="_blank"><i class="fa fa-file-pdf-o"></i> Anexo I</a>
						<div class="dropdown pull-right">
							<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-file"></i> Reportes
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu menu-right" aria-labelledby="dropdownMenu1">
								<li class="dropdown-header"><strong>Alumnos</strong></li>
								<li><a class="dropdown-item  bg-default" href="preinscripciones/escuela/anexo1_excel/<?php echo $preinscripcion->id ?>/1/<?php echo "$redireccion" ?>" title="Exportar Excel"><i class="fa fa-file-excel-o"></i>Inscriptos</a></li>
							</ul>
						</div>
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
							<thead>
								<tr style="background-color: #e4e4e4" >
									<th style="text-align: center; border-color: gray !important;" colspan="8">
										<div style="width:25px; margin: 2px ;" class="pull-left bg-green-active"><?php echo $preinscripcion->vacantes; ?></div>
										<div style="width:100%; text-align: left;"> Vacantes iniciales</div>
									</th>
								</tr>
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
							<tbody class="open">
								<?php $i = 1; ?>
								<tr style="background-color: #d4d4d4" class="parent">
									<th style="text-align: center; border-color: gray !important;" colspan="8">
										<div style="width:25px; margin: 2px ;" class="pull-left bg-green-active"><?php echo!empty($alumnos[1]) ? count($alumnos[1]) : 0; ?></div>
										<div style="width:80px" class="pull-left">
											<?php if ($fecha <= $fecha_hasta): ?>
												<?php if ($preinscripcion->inscriptos < $preinscripcion->vacantes): ?>
													<a class="btn btn-xs bg-blue pull-left" id="persona_buscar_listar_1" href="preinscripciones/preinscripcion_alumno/modal_buscar/<?php echo $preinscripcion->id; ?>/1/<?php echo $redireccion; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-plus"></i> Agregar
													</a>
												<?php else : ?>
													<span>&nbsp;</span>
												<?php endif; ?>
											<?php else : ?>
												<span>&nbsp;</span>
											<?php endif; ?>
										</div>
										<div style="width:25px; margin: 2px;" class="pull-right">
											<span class="sign pull-right"><i class="fa fa-chevron-down"></i></span>
										</div>
										<div style="width:100%; padding-right:120px;">Hermanos de alumnos</div>
									</th>
								</tr>
								<?php if (!empty($alumnos[1])): ?>
									<?php foreach ($alumnos[1] as $orden => $alumno): ?>
										<tr class="cchild">
											<td><?= $i++; ?></td>
											<td><?= $alumno->persona; ?></td>
											<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
											<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
											<td><?= substr($alumno->sexo, 0, 1); ?></td>
											<td><?= $alumno->direccion; ?></td>
											<td><?= $alumno->familiares; ?></td>
											<td>
												<a class="btn btn-xs btn-warning" href="preinscripciones/preinscripcion_alumno/editar/<?= $alumno->id; ?>" title="Editar"><i class="fa fa-edit"></i></a>
												<?php if ($fecha <= $fecha_hasta || in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION))): ?>
													<a class="btn btn-xs btn-primary" href="preinscripciones/preinscripcion_alumno/modal_editar_preinscripcion_tipo/<?= $alumno->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Modificar tipo de preinscripción"><i class="fa fa-fw fa-long-arrow-down"></i></a>
													<?php if (in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION)) && $preinscripcion->postulantes === '0' && $preinscripcion->derivados === '0'): ?>
														<a class="btn btn-xs btn-danger" href="preinscripciones/preinscripcion_alumno/modal_eliminar_preinscripcion_instancia1/<?= $alumno->id; ?>" title="Eliminar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i></a>
													<?php endif; ?>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr class="cchild">
										<td colspan="8" style="text-align: center;">-- No hay hermanos de alumnos Preinscriptos --</td>
									</tr>
								<?php endif; ?>
							</tbody>
							<tbody class="open">
								<tr style="background-color: #d4d4d4" class="parent">
									<th style="text-align: center; border-color: gray !important;" colspan="8">
										<div style="width:25px; margin: 2px ;" class="pull-left bg-green-active"><?php echo!empty($alumnos[2]) ? count($alumnos[2]) : 0; ?></div>
										<div style="width:80px" class="pull-left">
											<?php if ($fecha <= $fecha_hasta): ?>
												<?php if ($preinscripcion->inscriptos < $preinscripcion->vacantes): ?>
													<a class="btn btn-xs bg-blue pull-left" id="persona_buscar_listar_1" href="preinscripciones/preinscripcion_alumno/modal_buscar/<?php echo $preinscripcion->id; ?>/2/<?php echo $redireccion; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-plus"></i> Agregar
													</a>
												<?php else : ?>
													<span>&nbsp;</span>
												<?php endif; ?>
											<?php else : ?>
												<span>&nbsp;</span>
											<?php endif; ?>
										</div>
										<div style="width:25px; margin: 2px;" class="pull-right">
											<span class="sign pull-right"><i class="fa fa-chevron-down"></i></span>
										</div>
										<div style="width:100%; padding-right:120px;">Alumnos de jardín anexo/nucleado con que articula</div>
									</th>
								</tr>
								<?php if (!empty($alumnos[2])): ?>
									<?php foreach ($alumnos[2] as $orden => $alumno): ?>
										<tr class="cchild">
											<td><?= $i++; ?></td>
											<td><?= $alumno->persona; ?></td>
											<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
											<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
											<td><?= substr($alumno->sexo, 0, 1); ?></td>
											<td><?= $alumno->direccion; ?></td>
											<td><?= $alumno->familiares; ?></td>
											<td><a class="btn btn-xs btn-warning" href="preinscripciones/preinscripcion_alumno/editar/<?= $alumno->id; ?>" title="Editar"><i class="fa fa-edit"></i></a>
												<?php if ($fecha <= $fecha_hasta || in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION))): ?>
													<a class="btn btn-xs btn-primary" href="preinscripciones/preinscripcion_alumno/modal_editar_preinscripcion_tipo/<?= $alumno->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Modificar tipo de preinscripción"><i class="fa fa-fw fa-long-arrow-down"></i></a>
													<?php if (in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION)) && $preinscripcion->postulantes === '0' && $preinscripcion->derivados === '0'): ?>
														<a class="btn btn-xs btn-danger" href="preinscripciones/preinscripcion_alumno/modal_eliminar_preinscripcion_instancia1/<?= $alumno->id; ?>" title="Eliminar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i></a>
													<?php endif; ?>
												<?php endif; ?>
											</td>											
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr class="cchild">
										<td colspan="8" style="text-align: center;">-- No hay alumnos de jardín anexo/nucleado con que articula inscriptos --</td>
									</tr>
								<?php endif; ?>
							</tbody>
							<tbody class="open">
								<tr style="background-color: #d4d4d4;" class="parent">
									<th style="text-align: center; border-color: gray !important;" colspan="8">
										<div style="width:25px; margin: 2px ;" class="pull-left bg-green-active"><?php echo!empty($alumnos[3]) ? count($alumnos[3]) : 0; ?></div>
										<div style="width:80px" class="pull-left">
											<?php if ($fecha <= $fecha_hasta): ?>
												<?php if ($preinscripcion->inscriptos < $preinscripcion->vacantes): ?>
													<a class="btn btn-xs bg-blue pull-left" id="persona_buscar_listar_1" href="preinscripciones/preinscripcion_alumno/modal_buscar/<?php echo $preinscripcion->id; ?>/3/<?php echo $redireccion; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-plus"></i> Agregar
													</a>
												<?php else : ?>
													<span>&nbsp;</span>
												<?php endif; ?>
											<?php else : ?>
												<span>&nbsp;</span>
											<?php endif; ?>
										</div>
										<div style="width:25px; margin: 2px;" class="pull-right">
											<span class="sign pull-right"><i class="fa fa-chevron-down"></i></span>
										</div>
										<div style="width:100%; padding-right:130px;">Hijos de personal</div>
									</th>
								</tr>
								<?php if (!empty($alumnos[3])): ?>
									<?php foreach ($alumnos[3] as $orden => $alumno): ?>
										<tr class="cchild">
											<td><?= $i++; ?></td>
											<td><?= $alumno->persona; ?></td>
											<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
											<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
											<td><?= substr($alumno->sexo, 0, 1); ?></td>
											<td><?= $alumno->direccion; ?></td>
											<td><?= $alumno->familiares; ?></td>
											<td><a class="btn btn-xs btn-warning" href="preinscripciones/preinscripcion_alumno/editar/<?= $alumno->id; ?>" title="Editar"><i class="fa fa-edit"></i></a>
												<?php if ($fecha <= $fecha_hasta || in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION))): ?>
													<a class="btn btn-xs btn-primary" href="preinscripciones/preinscripcion_alumno/modal_editar_preinscripcion_tipo/<?= $alumno->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Modificar tipo de preinscripción"><i class="fa fa-fw fa-long-arrow-down"></i></a>
													<?php if (in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI, ROL_LINEA, ROL_GRUPO_ESCUELA, ROL_SUPERVISION)) && $preinscripcion->postulantes === '0' && $preinscripcion->derivados === '0'): ?>
														<a class="btn btn-xs btn-danger" href="preinscripciones/preinscripcion_alumno/modal_eliminar_preinscripcion_instancia1/<?= $alumno->id; ?>" title="Eliminar" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i></a>
													<?php endif; ?>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr class="cchild">
										<td colspan="8" style="text-align: center;">-- No hay hijos de personal inscriptos --</td>
									</tr>
								<?php endif; ?>
							</tbody>
							<tr>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
							<tr style="background-color: #e4e4e4" >
								<th style="text-align: center; border-color: gray !important;" colspan="8">
									<div style="width:25px; margin: 2px ;" class="pull-left bg-green-active"><?php echo (($preinscripcion->vacantes - $preinscripcion->instancia_1)); ?></div>
									<div style="width:100%; text-align: left;"> Vacantes disponibles</div>
								</th>
							</tr>
						</table>
					</div>
					<div class="box-footer">
						<?php if ($redireccion === 'supervision'): ?>
							<a class="btn btn-default" href="escritorio" title="Volver">Volver</a>
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
		$('table').on('click', 'tr.parent .fa-chevron-down', function() {
			$(this).closest('tbody').toggleClass('open');
		});
	});
</script>