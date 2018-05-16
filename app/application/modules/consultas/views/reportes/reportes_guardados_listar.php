<div class="content-wrapper">
	<section class="content-header">
		<h1>Reportes dinámicos</h1>
		<ol class="breadcrumb">
			<li><i class="fa fa-home"></i> Inicio</li>
			<li><a href="consultas/reportes/listar_guardados">Reportes</a></li>
			<li class="active">Listar</li>
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
					<div class="box-body ">
						<a class="btn bg-blue btn-app btn-app-zetta" href="consultas/reportes/iniciar">
							<i class="fa fa-plus" id="btn-agregar"></i> Agregar
						</a>
						<hr style="margin: 10px 0;">
						<table class="table table-hover table-bordered table-condensed">
							<thead>
								<tr>
									<th>Nombre</th>
									<th>Ultima ejecución</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($reportes)): ?>
									<?php foreach ($reportes as $reporte): ?>
										<tr>
											<td><?php echo $reporte->nombre; ?></td>
											<td><?php echo!empty($reporte->ultima_ejecucion) ? date_format(date_create($reporte->ultima_ejecucion), 'd/m/Y H:i:s') : ''; ?></td>
											<td>
												<a href="consultas/reportes/reporte_guardado/<?php echo $reporte->id; ?>" class="btn btn-primary btn-xs"><i class="fa fa-floppy-o"></i> Cargar</a>
												<a href="consultas/reportes/eliminar/<?php echo $reporte->id; ?>" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Eliminar</a>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr><td colspan="3">No hay registros.</td></tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="escritorio" title="Volver">Volver</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>