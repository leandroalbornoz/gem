<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Divisiones de Esc. <?= $escuela->nombre_corto; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id; ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="division/listar/<?= $escuela->id; ?>">Cursos y Divisiones</a></li>
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
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta" href="escuela/ver/<?php echo $escuela->id; ?>">
							<i class="fa fa-search"></i> Ver escuela
						</a>
						<a class="btn btn-app btn-app-zetta" href="division/listar/<?php echo $escuela->id; ?>">
							<i class="fa fa-tasks"></i> Cursos y Divisiones
						</a>
						<?php if ($edicion): ?>
							<a class="btn bg-blue btn-app btn-app-zetta" href="division/agregar/<?= $escuela->id; ?>">
								<i class="fa fa-plus"></i> Agregar
							</a>
							<a class="btn bg-blue btn-app btn-app-zetta" href="division/establecer_horarios/<?= $escuela->id; ?>">
								<i class="fa fa-clock-o"></i> Horarios masivos
							</a>
							<a class="btn btn-app btn-app-zetta" href="division/mover/<?= $escuela->id; ?>">
								<i class="fa fa-share"></i> Mover a anexo
							</a>
							<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="division/clave_portal/<?php echo "$escuela->id"; ?>">
								<i class="fa fa-lock" id="btn-sacar-alumnos"></i> Clave Portal
							</a>
						<?php endif; ?>
						
						<div class="btn-group pull-right" role="group" style="margin-left: 5px;">
							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-external-link"></i> Otros accesos <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								<?php if ($this->rol->codigo !== ROL_ESCUELA_ALUM): ?>
									<li><a class="dropdown-item btn-default" href="servicio/listar/<?php echo $escuela->id . '/'; ?>"><i class="fa  fa-fw fa-bookmark"></i>Servicios</a></li>
									<li><a class="dropdown-item btn-default"  href="cargo/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-users"></i> Cargos</a></li>
								<?php endif; ?>
								<li><a class="dropdown-item btn-default" href="alumno/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-users"></i> Alumnos</a></li>

								<li><a class="dropdown-item btn-default" href="division/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-tasks"></i> Cursos y Divisiones</a></li>

							</ul>
						</div>
						<hr style="margin: 10px 0;">
						<div role="tabpanel" class="tab-pane" id="tab_alumnos">
							<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
								<thead>
									<tr style="background-color: #f4f4f4" >
										<th style="" colspan="4">
											<span style="position: absolute; left: 50%; margin-left: -70px;">
												Curso y divisiones
											</span>
											<a style="margin-left: 10px;" class="btn btn-xs bg-green pull-right" href="division/excel_clave/<?php echo $escuela->id; ?>"  title="Exportar Excel"><i class="fa fa-file-excel-o"></i> Exportar Excel</a>
											<a class="btn btn-xs bg-yellow pull-right" href="division/imprimir_clave_curso_division/<?php echo $escuela->id; ?>" target="_blank" title="Exportar PDF"><i class="fa fa-file-pdf-o"></i> Exportar PDF</a>
										</th>
									</tr>
									<tr>
										<th>Curso</th>
										<th>División</th>
										<th>Turno</th>
										<th>Clave portal</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($divisiones)): ?>
										<?php foreach ($divisiones as $division): ?>
											<tr>
												<td><?= $division->curso; ?></td>
												<td><?= $division->division; ?></td>
												<td><?= $division->turno; ?></td>
												<td><?= $division->clave; ?></td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<td colspan="7" style="text-align: center;">-- No tiene --</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var division_table;
	function complete_division_table() {
		agregar_filtros('division_table', division_table, 6);
	}
</script>