<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Leer y Escribir en 2° grado
		</h1>
		<ol class="breadcrumb">
			<li><a href="escritorio"><i class="fa fa-home"></i>Escritorio</a></li>
			<li><?php echo $escuela->nombre_corto; ?></li>
			<li>Cursos y Divisiones</li>
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
						<a class="btn btn-app btn-app-zetta " href="escritorio">
							<i class="fa fa-home"></i> Escritorio
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="operativo_evaluar/evaluar_operativo/listar_divisiones/<?php echo $escuela->id; ?>">
							<i class="fa fa-tasks"></i> Divisiones
						</a>
						<a class=" btn bg-green btn-success btn-app " href="operativo_evaluar/evaluar_operativo/excel_reporte_escuela/<?php echo $escuela->id ?>" title="Exportar excel"><i class="fa fa-file-excel-o"></i>Reporte Esc.
						</a>
						<a class=" btn bg-green btn-success btn-app " href="operativo_evaluar/evaluar_operativo/excel_reporte_divisiones/<?php echo $escuela->id ?>" title="Exportar excel"><i class="fa fa-file-excel-o"></i>Reporte Curso
						</a>
						<div class="col-lg-3 col-xs-6 pull-right">
							<div class="info-box <?php echo ($porcentaje_carga->porcentaje_cargado > 0.95) ? 'bg-green' : (($porcentaje_carga->porcentaje_cargado > 0.6) ? 'bg-yellow' : 'bg-red'); ?>">
								<span class="info-box-icon"><i class="ion ion-pie-graph"></i></span>
								<div class="info-box-content">
									<span class="info-box-text">Porcentaje cargado</span>
									<span class="info-box-number"><?php echo $porcentaje_carga->porcentaje_cargado * 100; ?>%</span>
									<div class="progress">
										<div class="progress-bar" style="width: <?php echo $porcentaje_carga->porcentaje_cargado * 100; ?>%"></div>
									</div>
								</div>
							</div>
						</div>
						<table class="table table-hover table-bordered table-condensed dt-responsive" role="grid" >
							<thead>
								<tr>
									<th colspan="10" class="text-center bg-gray">Divisiones de 2do grado</th>
								</tr>
								<tr>
									<th style="width: 5%;">Curso</th>
									<th style="width: 5%;">División</th>
									<th style="width: 10%;">Turno</th>
									<th style="width: 20%;">Carrera</th>
									<th style="width: 10%;">Modalidad</th>
									<th style="width: 10%;">Fecha de alta</th>
									<th style="width: 10%;">Total de Alumnos</th>
									<th style="width: 10%;text-align: center;">Evaluaciones Cargadas</th>
									<th style="width: 10%;text-align: center;">Alumnos Ausentes</th>
									<th style="width: 15%;text-align: center;"></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($divisiones as $id => $division): ?>
									<tr>
										<td><?php echo $division->curso; ?></td>
										<td><?php echo $division->division; ?></td>
										<td><?php echo $division->turno; ?></td>
										<td><?php echo $division->carrera; ?></td>
										<td><?php echo $division->modalidad; ?></td>
										<td><?php echo (new DateTime($division->fecha_alta))->format('d/m/Y') ?></td>
										<td><?php echo $division->total_alumnos; ?></td>
										<td><?php echo $division->total_cargados; ?></td>
										<td><?php echo $division->total_ausentes; ?></td>
										<td style="text-align: center;">
											<a href="operativo_evaluar/evaluar_operativo/listar_alumnos/<?php echo $division->id; ?>" class="btn btn-xs btn-success" title="Listar Alumnos"><i class="fa fa-list"></i>&nbsp;&nbsp;Cargar evaluaciones</a>
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