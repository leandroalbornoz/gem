<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Leer y Escribir 2017: Alumnos de <?php echo "$division->curso $division->division"; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="escritorio"><i class="fa fa-home"></i>Escritorio</a></li>
			<li><?php echo $escuela->nombre_corto; ?></li>
			<li><a href="operativo_evaluar/evaluar_operativo/listar_divisiones/<?php echo $escuela->id; ?>">Cursos y Divisiones</a></li>
			<li><?php echo "$division->curso $division->division"; ?></li>
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
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta" href="escritorio">
							<i class="fa fa-home"></i> Escritorio
						</a>
						<a class="btn btn-app btn-app-zetta" href="operativo_evaluar/evaluar_operativo/listar_divisiones/<?php echo $escuela->id; ?>">
							<i class="fa fa-tasks"></i> Divisiones
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="operativo_evaluar/evaluar_operativo/listar_alumnos/<?php echo $division->id; ?>">
							<i class="fa fa-users"></i> <?php echo "$division->curso $division->division"; ?>
						</a>
						<a class=" btn bg-green btn-success btn-app" href="operativo_evaluar/evaluar_operativo/excel_reporte_alumnos/<?php echo $division->id ?>" title="Exportar excel"><i class="fa fa-file-excel-o"></i>Reporte Alumnos
						</a>
						<table class="table table-hover table-bordered table-condensed dt-responsive" role="grid" >
							<thead>
								<tr>
									<th colspan="12" class="text-center bg-gray">Alumnos de <?php echo "$division->curso $division->division"; ?></th>
								</tr>
								<tr>
									<th style="width: 12%;">Documento</th>
									<th style="width: 20%;">Persona</th>
									<th style="width: 7%;">Fecha<br>Ingreso.</th>
									<th style="width: 4%;">Sexo</th>
									<th style="width: 5%;">Div.</th>
									<th style="width: 10%;text-align: center;" colspan="2">Comprensión y<br>producción del texto</th>
									<th style="width: 10%;text-align: center;" colspan="2">Solución de<br>problemas</th>
									<th style="width: 16%;text-align: center;">Nivel de<br>desempeño</th>
									<th style="width: 16%;"></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($alumnos_division as $id => $alumno_division): ?>
									<tr>
										<td><?php echo $alumno_division->documento; ?></td>
										<td><?php echo $alumno_division->persona; ?></td>
										<td><?php echo (new DateTime($alumno_division->fecha_desde))->format('d/m/Y') ?></td>
										<td><?php echo empty($alumno_division->sexo) ? '' : $alumno_division->sexo; ?></td>
										<td style="text-align: center;"><?php echo "$alumno_division->division"; ?></td>
										<?php if (empty($alumno_division->evaluacion_operativo_id) || $alumno_division->estado === 'Ausente'): ?>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										<?php else: ?>
											<td><?php echo $alumno_division->puntuacion_1; ?></td>
											<td><?php echo number_format($alumno_division->puntuacion_1 * 100 / 18, 2, '.', ''); ?>%</td>
											<td><?php echo $alumno_division->puntuacion_2; ?></td>
											<td><?php echo number_format($alumno_division->puntuacion_2 * 100 / 7, 2, '.', ''); ?>%</td>
										<?php endif; ?>
										<td>
											<?php $promedio = ($alumno_division->puntuacion_2 + $alumno_division->puntuacion_1) * 10 / 25; ?>
											<?php if (empty($alumno_division->evaluacion_operativo_id) || $alumno_division->estado === 'Ausente'): ?>
											<?php else: ?>
												<?php if ($promedio <= 10.00 && $promedio >= 9.00): ?>
													Avanzado: 
												<?php elseif ($promedio <= 8.99 && $promedio >= 7.00): ?>
													Satisfactorio:
												<?php elseif ($promedio <= 6.99 && $promedio >= 4.00): ?>
													Básico:
												<?php else: ?>
													Por debajo del básico:
												<?php endif; ?>
												<?php echo ($alumno_division->puntuacion_2 + $alumno_division->puntuacion_1) * 10 / 25 ?>
											<?php endif; ?>
										</td>
										<td style="text-align: center;">
											<?php if (empty($alumno_division->evaluacion_operativo_id) && $alumno_division->estado !== 'Ausente'): ?>
												<a href="operativo_evaluar/evaluar_operativo/agregar/<?php echo $alumno_division->id; ?>" class="btn btn-xs btn-success" title="Cargar evaluación"><i class="fa fa-plus"></i>&nbsp;&nbsp;Cargar</a>
												<a href="operativo_evaluar/evaluar_operativo/modal_establecer_ausente/<?php echo $alumno_division->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning" title="Alumno ausente"><i class="fa fa-remove"></i>&nbsp;&nbsp;Ausente</a>
											<?php elseif ($alumno_division->estado === 'Ausente'): ?>
												<a href="operativo_evaluar/evaluar_operativo/modal_eliminar_ausente/<?php echo "$alumno_division->evaluacion_operativo_id/$alumno_division->id"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs bg-red" title="Eliminar ausente"><i class="fa fa-remove"></i>&nbsp;&nbsp;Eliminar Ausente</a>
											<?php else: ?>
												<a href="operativo_evaluar/evaluar_operativo/ver/<?php echo "$alumno_division->evaluacion_operativo_id/$alumno_division->id"; ?>" class="btn btn-xs btn-default" title="Ver Carga"><i class="fa fa-search"></i></a>
												<a href="operativo_evaluar/evaluar_operativo/editar/<?php echo "$alumno_division->evaluacion_operativo_id/$alumno_division->id"; ?>" class="btn btn-xs bg-yellow" title="Editar Carga"><i class="fa fa-edit"></i></a>
												<a href="operativo_evaluar/evaluar_operativo/eliminar/<?php echo "$alumno_division->evaluacion_operativo_id/$alumno_division->id"; ?>" class="btn btn-xs bg-red" title="Eliminar Carga"><i class="fa fa-remove"></i></a>
												<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>