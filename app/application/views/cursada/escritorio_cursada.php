<style>
	.btn-group {
    display: inline-table;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?php echo "$escuela->numero" . ($escuela->anexo === '0' ? '' : "/$escuela->anexo") . " - $cursada->division - $cursada->espacio_curricular"; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="cursada/listar/<?php echo $division->id; ?>">Cursadas</a></li>
			<li class="active"><i class="fa fa-home"></i>&nbsp;Escritorio de <?php echo $cursada->espacio_curricular; ?></li>
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
			<div class="col-xs-4">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title" style="font-weight: bold;">Cursada</h3>
						<div class="box-tools pull-right">
							<a class="btn btn-danger" title="Manual cursadas" href="uploads/ayuda/cursadas/manual.pdf" target="_blank" style=""><i class="fa fa-file-pdf-o"></i> Manual</a>
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body ">
						<div class="row">
							<div class="col-sm-12"><label>División:</label>&nbsp;<?php echo $cursada->division; ?></div>
							<div class="col-sm-12"><label>Espacio Curricular:</label>&nbsp;<?php echo $cursada->espacio_curricular; ?></div>
							<div class="col-sm-6"><label>Carga horaria:</label>&nbsp;<?php echo $cursada->carga_horaria; ?></div>
							<div class="col-sm-6"><label>Alumnos:</label>&nbsp;<?php echo $cursada->alumnos; ?></div>
							<?php if (!empty($cursada->grupo)): ?>
								<div class="col-sm-12"><label>Grupo:</label>&nbsp;<?php echo $cursada->grupo; ?></div>
							<?php endif; ?>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Editar Cursada" href="cursada/modal_editar/<?php echo "$cursada->id?redirect_url=" . urlencode(str_replace(base_url(), '', current_url())); ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
					</div>
				</div>
			</div>
			<div class="col-xs-8">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title" style="font-weight: bold;">Cargos de la cursada</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body ">
						<table id="tbl_ver_cargos" class="table table-hover table-bordered table-condensed text-sm  dt-responsive" role="grid" >
							<thead>
								<tr>
									<th style="text-align: center;">Condición/Régimen</th>
									<th style="text-align: center;">Persona</th>
									<th style="text-align: center;">Hs Cubiertas</th>
									<th style="text-align: center;"></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($cargos_cursada as $cc_id => $cargo_cursada): ?>
									<tr>
										<td><?php echo "$cargo_cursada->condicion_cargo<br>$cargo_cursada->regimen_codigo $cargo_cursada->regimen"; ?></td>
										<td><?php echo $cargo_cursada->persona; ?></td>
										<td><?php echo $cargo_cursada->carga_horaria; ?></td>
										<td style="text-align: center;">
											<div class="btn-group" role="group">
												<a class="btn btn-xs btn-default" title="Ver cargo" target="_blank" href="cargo/ver/<?php echo $cargo_cursada->cargo_id; ?>"><i class="fa fa-search" id="btn-editar"></i>&nbsp;Ver</a>
												<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><span class="caret"></span></button>
												<ul class="dropdown-menu dropdown-menu-left">
													<li><a href="cursada/modal_editar_cargo/<?php echo "$cargo_cursada->id?redirect_url=" . urlencode(str_replace(base_url(), '', current_url())); ?>" class="dropdown-item btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Editar carga horaria"><i class="fa fa-edit"></i>&nbsp;Editar</a></li>
													<li><a href="cursada/modal_eliminar_cargo/<?php echo "$cargo_cursada->id?redirect_url=" . urlencode(str_replace(base_url(), '', current_url())); ?>" class="dropdown-item btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Eliminar cargo"><i class="fa fa-remove"></i>&nbsp;Eliminar</a></li>
												</ul>
											</div>	
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<div class="box-footer">
						<?php if ($cursada->carga_horaria_cargos < $cursada->carga_horaria): ?>
							<a class="btn btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Agregar cargo" href="cursada/modal_agregar_cargo/<?php echo "$cursada->id?redirect_url=" . urlencode(str_replace(base_url(), '', current_url())); ?>">
								<i class="fa fa-plus" id="btn-editar"></i> Agregar cargo</a>
						<?php else: ?>
							<a class="btn btn-primary" title="Agregar cargo" id="boton-editar"><i class="fa fa-plus" id="btn-editar"></i> Agregar cargo</a>
							<span class="bg-red text-bold hidden pull-right" id="cartel" style="border-radius: 2px;">&nbsp;La carga horaria máxima ya fue alcanzada, edite la carga horaria.&nbsp;</span>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title" style="font-weight: bold;" >Evaluaciones</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<ul class="nav nav-tabs" role="tablist">
							<?php foreach ($periodos as $pid => $periodo): ?>
								<li role="presentation" <?= $pid === 0 ? ' class="active"' : ''; ?>><a href="#tab_<?= $periodo->periodo; ?>" aria-controls="tab_<?= $periodo->periodo; ?>" role="tab" data-toggle="tab"><?php echo "$periodo->periodo" . "° $periodo->nombre_periodo"; ?></a></li>
							<?php endforeach; ?>
						</ul>
						<div class="tab-content">
							<?php foreach ($periodos as $pid => $periodo): ?>
								<div role="tabpanel" class="tab-pane <?= $pid === 0 ? 'active' : ''; ?> " id="tab_<?= $periodo->periodo; ?>">
									<table id="tbl_ver_evaluaciones" class="table table-hover table-bordered table-condensed text-sm  dt-responsive" role="grid" >
										<thead>
											<tr>
												<th style="width: 10%;">Tipo de evaluación</th>
												<th style="width: 30%;">Tema</th>
												<th style="width: 10%;">Fecha</th>
												<th style="width: 10%;">Periodo</th>
												<th style="width: 10%;">Total de alumnos</th>
												<th style="width: 10%;">Ausentes</th>
												<th style="width: 5%;">Promedio de notas</th>
												<th style="width: 15%;"></th>
											</tr>
										</thead>
										<tbody>
											<?php $array_notas = array('1' => 'R', '2' => 'A', '3' => 'V'); ?>
											<?php if (!empty($evaluaciones[$periodo->periodo])): ?>
												<?php foreach ($evaluaciones[$periodo->periodo] as $evaluacion): ?>
													<tr>
														<td><?= "$evaluacion->evaluacion_tipo"; ?></td>
														<td><?= "$evaluacion->tema"; ?></td>
														<td><?= empty($evaluacion->fecha) ? '' : (new DateTime($evaluacion->fecha))->format('d/m/Y'); ?></td>
														<td><?= empty($evaluacion->periodo) ? '' : $evaluacion->periodo; ?></td>
														<td><?= !empty($evaluacion->inscriptos) ? $evaluacion->inscriptos : '-'; ?></td>
														<td><?= !empty($evaluacion->ausentes) ? $evaluacion->ausentes : '-'; ?></td>
														<td>
															<?php if ($evaluacion->inscriptos - $evaluacion->ausentes != '0'): ?>
																<?php if ($evaluacion->calificacion_id === '1'): ?>
																	<?= !empty($evaluacion->inscriptos) ? $array_notas["" . round($evaluacion->suma_notas / ($evaluacion->inscriptos - $evaluacion->ausentes))] : '-'; ?>
																<?php else: ?>
																	<?= !empty($evaluacion->inscriptos) ? number_format($evaluacion->suma_notas / ($evaluacion->inscriptos - $evaluacion->ausentes), 2, ',', '.') : '-'; ?>
																<?php endif; ?>
															<?php else: ?>
																-
															<?php endif; ?>
														</td>
														<td style="text-align: center;">
															<div class="btn-group" role="group">
																<a class="btn btn-xs btn-default" href="evaluacion/editar/<?php echo "$evaluacion->id"; ?>" title="Ver">&nbsp;<i class="fa fa-pencil"></i>&nbsp;Cargar notas</a>
																<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><span class="caret"></span></button>
																<ul class="dropdown-menu dropdown-menu-left">
																	<li><a class="dropdown-item btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="evaluacion/modal_editar/<?php echo $evaluacion->id; ?>" title = "Editar"><i class = "fa fa-pencil"></i> Editar</a></li>
																	<li><a class = "dropdown-item btn-danger" href = "evaluacion/eliminar/<?php echo $evaluacion->id; ?>" title = "Eliminar"><i class = "fa fa-remove"></i> Eliminar</a></li>
																	<li><a class = "dropdown-item  bg-green" href = "cursada/excel_evaluacion/<?php echo "$evaluacion->id"; ?>" title = "Exportar Excel"><i class = "fa fa-file-excel-o"></i>Exportar Excel</a></li>
																	<li><a class = "dropdown-item  bg-warning" target = "_blank" href = "cursada/pdf_evaluacion/<?php echo "$evaluacion->id"; ?>" title = "Exportar PDF"><i class = "fa fa-file-pdf-o"></i>Exportar PDF</a></li>
																</ul>
															</div>
														</td>
													</tr>
												<?php endforeach; ?>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Agregar evaluación" href="evaluacion/modal_agregar/<?php echo $cursada->id; ?>">
							<i class="fa fa-plus" id="btn-editar"></i>&nbsp;Agregar evaluación</a>
						<?php if (in_array($cursada->espacio_curricular_id, $array_evaluacion_espacio_curricular_id)): ?>
							<a class="btn btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Agregar evaluación" href="evaluacion/modal_agregar_concepto/<?php echo $cursada->id; ?>">
								<i class="fa fa-plus" id="btn-editar"></i>&nbsp;Agregar evaluación de concepto</a>
						<?php endif; ?>
						<a class="btn btn-success pull-right" title="Ver notas evaluaciones" href="cursada/evaluaciones/<?php echo $cursada->id; ?>">
							<i class="fa fa-book"></i> Ver notas evaluaciones
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title" style="font-weight: bold;">Alumnos Inscriptos activos</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body ">
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" id="tbl_listar_alumnos_inscriptos" role="grid">
							<thead>
								<tr>
									<th style="width:10%;">Documento</th>
									<th style="width:32%;">Nombre</th>
									<th style="width:14%;">F.Nac</th>
									<th style="width:5%;">Sexo</th>
									<th style="width:10%;">Desde</th>
									<th style="width:10%;">Hasta</th>
									<th style="width:5%;">C.L.</th>
									<th style="width:14%;"></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</tfoot>
							<tbody>
								<?php if (!empty($alumnos)): ?>
									<?php foreach ($alumnos as $alumno): ?>
										<tr>
											<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
											<td><?= $alumno->persona; ?></td>
											<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
											<td><?= substr($alumno->sexo, 0, 1); ?></td>
											<td><?= empty($alumno->fecha_desde) ? '' : (new DateTime($alumno->fecha_desde))->format('d/m/Y'); ?></td>
											<td><?= empty($alumno->fecha_hasta) ? '' : (new DateTime($alumno->fecha_hasta))->format('d/m/Y'); ?></td>
											<td><?= $alumno->ciclo_lectivo; ?></td>
											<td style="text-align: center;">
												<div class="btn-group" role="group">
													<a href="evaluacion/modal_seleccion_periodo/<?php echo "$cursada->id/$alumno->alumno_division_id"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default" title="Seleccionar periodo de carga"><i class="fa fa-pencil"></i>&nbsp;Cargar Notas</a>
													<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><span class="caret"></span></button>
													<ul class="dropdown-menu dropdown-menu-left">
														<li><a href="alumno/ver/<?php echo $alumno->alumno_division_id ?>" target="_blank" class="btn btn-xs btn-default" title="Ver alumno"><i class="fa fa-search"></i>Ver alumno</a></li>
														<?php if ($cursada->alumnos != 'Todos'): ?>
															<li><a href="alumno_cursada/eliminar/<?php echo $alumno->id ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="dropdown-item btn-danger" title="Eliminar alumno"><i class="fa fa-remove"></i>Eliminar</a></li>
														<?php endif; ?>
													</ul>
												</div>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
					<?php if ($cursada->alumnos != 'Todos'): ?>
						<div class="box-footer">
							<a class="btn btn-primary" title="Editar alumnos de cursada" href="alumno_cursada/agregar_alumnos/<?php echo $cursada->id; ?>"><i class="fa fa-plus" id="btn-editar"></i>&nbsp;Agregar alumnos</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php if (in_array($this->rol->codigo, $this->roles_administrar)): ?>
			<div class="row">
				<div class="col-xs-6">
					<div class="box box-primary"><!-- Usuarios -->
						<div class="box-header with-border">
							<h3 class="box-title">Usuarios</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-xs-12">
									<table class="table table-bordered table-condensed table-striped table-hover">
										<tr>
											<th style="width: 25%;">Usuario</th>
											<th style="width: 35%;">CUIL<br/>Persona</th>
											<th style="width: 39%;">Rol<br/>Entidad</th>
											<th style="width: 1%;"></th>
										</tr>
										<?php if (!empty($usuarios)): ?>
											<?php foreach ($usuarios as $usuario): ?>
												<tr>
													<td><?php echo $usuario->usuario; ?></td>
													<td><?php echo "$usuario->cuil<br/>$usuario->persona"; ?></td>
													<td><?php echo "$usuario->rol<br/>$usuario->entidad"; ?></td>
													<td>
														<a class="btn btn-xs" href="usuario/ver/<?php echo $usuario->id; ?>"><i class="fa fa-search"></i></a>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td colspan="4" style="text-align: center;"> -- Sin usuarios -- </td>
											</tr>
										<?php endif; ?>
									</table>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<a class="btn btn-primary" href="cursada/administrar_rol_cursada/<?php echo $cursada->id; ?>">
								<i class="fa fa-cogs"></i> Administrar
							</a>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</section>
</div>

<script>
	var table_alumnos_busqueda;
	$(document).ready(function() {
		table_alumnos_busqueda = $('#tbl_listar_alumnos_inscriptos').DataTable({dom: 'tp', autoWidth: false, pagingType: 'simple_numbers', bPaginate: false, language: {url: 'plugins/datatables/spanish.json'}, initComplete: cursada_alumno_table, columnDefs: [{orderable: false, targets: [7]}]});
	});
	$("#boton-editar").click(function() {
		$.ajax({
			type: 'GET',
			url: 'ajax/get_cursada_carga_horaria?',
			data: {cursada_id: <?php echo $cursada->id; ?>},
			dataType: 'json',
			success: function(result) {
				if (result.status === 'success') {
					var carga_horaria = result.carga_horaria_cursada.carga_horaria;
					var carga_horaria_cubierta = result.carga_horaria_cursada.carga_horaria_cubierta;
					if (carga_horaria <= carga_horaria_cubierta) {
						if ($('#cartel').hasClass('hidden')) {
							$('#cartel').removeClass('hidden');
						} else {
							$('#cartel').addClass('hidden');
						}
					}
				}
			}
		});
	});
	function cursada_alumno_table() {
		agregar_filtros('tbl_listar_alumnos_inscriptos', table_alumnos_busqueda, 7);
	}
</script>
