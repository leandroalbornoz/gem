<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Plan Conectividad Nacional
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/escritorio/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li>Plan Conectividad Nacional</li>
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
						<h3 class="box-title">Esc. <?php echo $escuela->nombre_largo; ?> - Instalación Plan Conectividad Nacional - <?php echo $conectividad_escuela->conectividad_nacion; ?></h3>
					</div>
					<div class="box-body">
						<?php if ($conectividad_escuela->fecha_hasta > date('Y-m-d H:i:s')): ?>
							<?php if ('2' > $conectividad_escuela->encargados_asignados): ?>
								<a class="btn bg-blue btn-app btn-app-zetta" id="persona_buscar_listar" href="conectividad/instalacion/modal_buscar_encargado/<?php echo $conectividad_escuela->cne_id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal">
									<i class="fa fa-plus"></i> Agregar encargado
								</a>
							<?php else: ?>
								<a class="btn bg-blue btn-app btn-app-zetta disabled">
									<i class="fa fa-plus"></i> Agregar encargado
								</a>
							<?php endif; ?>
							<a class="btn btn-app btn-app-zetta text-red">
								<i class="fa fa-lock"></i> Fecha límite de Carga: <?php echo (new DateTime($conectividad_escuela->fecha_hasta))->format('d/m/Y') . ' a las ' . (new DateTime($conectividad_escuela->fecha_hasta))->format('H:i'); ?>
							</a>
						<?php else: ?>
							<span class="btn btn-app btn-app-zetta text-green">
								<i class="fa fa-lock"></i> Carga cerrada
							</span>
						<?php endif; ?>
						<div class="row">
							<div class="form-group col-md-12">
								* Por favor completar las personas encargadas (1-2) que estarán en el establecimiento en los días determinados para la instalación. Asimismo completar el celular de contacto para que el instalador pueda coordinar la visita.
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['escuela']['label']; ?>
								<?php echo $fields['escuela']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_inicio']['label']; ?>
								<?php echo $fields['fecha_inicio']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_fin']['label']; ?>
								<?php echo $fields['fecha_fin']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['instalador']['label']; ?>
								<?php echo $fields['instalador']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['celular_contacto']['label']; ?>
								<?php if ($conectividad_escuela->fecha_hasta > date('Y-m-d H:i:s')): ?>
									<div class="input-group">
										<?php echo $fields['celular_contacto']['form']; ?>
										<span class="input-group-btn">
											<a class="btn btn-warning" href="conectividad/instalacion/modal_editar_escuela/<?php echo "$conectividad_escuela->cne_id"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal">
												<i class="fa fa-edit"></i></a>
										</span>
									</div>
								<?php else: ?>
									<?php echo $fields['celular_contacto']['form']; ?>
								<?php endif; ?>
							</div>
						</div>
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
							<thead>
								<tr style="background-color: #e4e4e4;">
									<th colspan="4" style="text-align: center;">Encargados</th>
								</tr>
								<tr>
									<th>Cuil</th>
									<th>Apellido y Nombre</th>
									<th>Regimen</th>
									<th style="width: 80px;"></th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($encargados)): ?>
									<?php foreach ($encargados as $orden => $encargado): ?>
										<tr>
											<td><?php echo $encargado->cuil; ?></td>
											<td><?php echo "$encargado->apellido, $encargado->nombre"; ?></td>
											<td><?php echo $encargado->regimen; ?></td>
											<td>
												<?php if ($conectividad_escuela->fecha_hasta > date('Y-m-d H:i:s')): ?>
													<a class="btn btn-xs btn-danger" href="conectividad/instalacion/modal_eliminar_encargado/<?php echo $conectividad_escuela->cne_id; ?>/<?php echo "$encargado->id"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i> Eliminar encargado</a>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="6" style="text-align: center;">-- No hay encargados asignados --</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="escuela/escritorio/<?php echo $escuela->id; ?>" title="Volver">Volver</a>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>