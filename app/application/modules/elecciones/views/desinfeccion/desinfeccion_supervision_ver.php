<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Desinfección de Escuelas
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/escritorio/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li>Desinfección Elecciones</li>
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
						<h3 class="box-title">Esc. <?php echo $escuela->nombre_largo; ?> - Operativo Desinfección Escuelas - <?php echo $desinfeccion->eleccion; ?></h3>
					</div>
					<div class="box-body">
						<?php if (empty($desinfeccion->fecha_cierre)): ?>
							<?php if ($desinfeccion->fecha_hasta >= date('Y-m-d')): ?>
								<a class="btn btn-app btn-app-zetta text-red">
									<i class="fa fa-lock"></i> La carga cerrará el <?php echo (new DateTime($desinfeccion->fecha_hasta))->format('d/m/Y'); ?> a las 23:59
								</a>
							<?php endif; ?>
						<?php else: ?>
							<span class="btn btn-app btn-app-zetta text-green">
								<i class="fa fa-lock"></i> Carga cerrada
							</span>
						<?php endif; ?>
						<a class="btn bg-default btn-app bg-green" href="elecciones/desinfeccion/imprimir_pdf/<?php echo $desinfeccion->id; ?>" title="Exportar PDF" target="_blank"><i class="fa fa-file-pdf-o"></i>Imprimir</a>
						<div class="row">
							<div class="form-group col-md-6">
								<?php echo $fields['escuela']['label']; ?>
								<?php echo $fields['escuela']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['mesas']['label']; ?>
								<?php echo $fields['mesas']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['celadores_permitidos']['label']; ?>
								<?php echo $fields['celadores_permitidos']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_cierre']['label']; ?>
								<?php echo $fields['fecha_cierre']['form']; ?>
							</div>
						</div>
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
							<thead>
								<tr style="background-color: #e4e4e4;">
									<th colspan="6" style="text-align: center;">Celadores</th>
								</tr>
								<tr>
									<th>Cuil</th>
									<th>Apellido y Nombre</th>
									<th style="width: 120px;">Cargado</th>
									<th style="width: 80px;">Estado</th>
									<th style="width: 200px;">Anulado</th>
									<th style="width: 80px;"></th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($celadores)): ?>
									<?php foreach ($celadores as $orden => $celador): ?>
										<tr>
											<td><?php echo $celador->cuil; ?></td>
											<td><?php echo "$celador->apellido, $celador->nombre"; ?></td>
											<td><?php echo (new DateTime($celador->fecha_carga))->format('d/m/Y H:i'); ?></td>
											<?php if ($celador->estado === 'Activo'): ?>
												<td>
													<span class="text-green"><i class="fa fa-check"></i> Activo</span>
												</td>
												<td></td>
											<?php else: ?>
												<td>
													<span class="text-red"><i class="fa fa-ban"></i> Anulado</span>
												</td>
												<td><?php echo (new DateTime($celador->fecha_anulacion))->format('d/m/Y H:i'); ?></td>
											<?php endif; ?>
											<td></td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="6" style="text-align: center;">-- No hay celadores asignados --</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="escritorio" title="Volver">Volver</a>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
