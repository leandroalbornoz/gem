<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Relevamiento de Extintores
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/escritorio/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li>Relevamiento de Extintores</li>
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
						<h3 class="box-title">Esc. <?php echo $escuela->nombre_largo; ?> - Relevamiento Extintores - <?php echo $relevamiento->extintor_relevamiento; ?></h3>
					</div>
					<div class="box-body">
						<?php if ($relevamiento->fecha_hasta > date('Y-m-d H:i:s')): ?>
							<a class="btn bg-blue btn-app btn-app-zetta" href="extintores/extintor/modal_agregar/<?php echo "$relevamiento->id/$escuela->id"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal">
								<i class="fa fa-plus"></i> Agregar extintor
							</a>
							<a class="btn btn-app btn-app-zetta text-red">
								<i class="fa fa-lock"></i> Fecha límite de Carga: <?php echo (new DateTime($relevamiento->fecha_hasta))->format('d/m/Y'); ?> a las 23:59
							</a>
						<?php else: ?>
							<span class="btn btn-app btn-app-zetta text-green">
								<i class="fa fa-lock"></i> Carga cerrada
							</span>
						<?php endif; ?>
						<a class="btn bg-default btn-app bg-green" href="extintores/extintor/imprimir_pdf/<?php echo "$relevamiento->id/$escuela->id/1"; ?>" title="Exportar PDF" target="_blank"><i class="fa fa-file-pdf-o"></i>Imprimir</a>
						<a class="btn bg-default btn-app text-green" href="extintores/extintor/imprimir_pdf/<?php echo "$relevamiento->id/$escuela->id/2"; ?>" title="Imprimir para facilitar la recopilación de información" target="_blank"><i class="fa fa-file-pdf-o"></i>Planilla relevamiento</a>
						<div class="row">
							<div class="form-group col-md-12">
								* En el caso de compartir edificio, coordine para que la carga/solicitud de extintores faltantes la realice sólo una escuela sobre la totalidad del edificio. Identifique en observaciones qué institución va a realizar la carga en este caso.
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['extintores_faltantes']['label']; ?>
								<?php if ($relevamiento->fecha_hasta > date('Y-m-d H:i:s')): ?>
									<div class="input-group">
										<?php echo $fields['extintores_faltantes']['form']; ?>
										<span class="input-group-btn">
											<a class="btn btn-warning" href="extintores/extintor/modal_editar_escuela/<?php echo "$relevamiento->ee_id"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal">
												<i class="fa fa-edit"></i></a>
										</span>
									</div>
								<?php else: ?>
									<?php echo $fields['extintores_faltantes']['form']; ?>
								<?php endif; ?>
							</div>
							<div class="form-group col-md-9">
								<?php echo $fields['observaciones']['label']; ?>
								<?php if ($relevamiento->fecha_hasta > date('Y-m-d H:i:s')): ?>
									<div class="input-group">
										<?php echo $fields['observaciones']['form']; ?>
										<span class="input-group-btn">
											<a class="btn btn-warning" href="extintores/extintor/modal_editar_escuela/<?php echo "$relevamiento->ee_id"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal">
												<i class="fa fa-edit"></i></a>
										</span>
									</div>
								<?php else: ?>
									<?php echo $fields['observaciones']['form']; ?>
								<?php endif; ?>
							</div>
						</div>
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
							<thead>
								<tr style="background-color: #e4e4e4;">
									<th colspan="8" style="text-align: center;"><span class="label label-primary"><?php echo empty($extintores) ? '0' : count($extintores); ?></span>Extintores</th>
								</tr>
								<tr>
									<th>N° Registro</th>
									<th>Primer Carga</th>
									<th>Vencimiento</th>
									<th>Kilos</th>
									<th>Tipo Extintor</th>
									<th>Empresa</th>
									<th>Marca</th>
									<th style="width: 80px;"></th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($extintores)): ?>
									<?php foreach ($extintores as $orden => $extintor): ?>
										<tr>
											<td><?php echo $extintor->numero_registro; ?></td>
											<td><?php echo empty($extintor->primer_carga) ? '' : (new DateTime($extintor->primer_carga))->format('d/m/Y'); ?></td>
											<td><?php echo empty($extintor->vencimiento) ? '' : (new DateTime($extintor->vencimiento))->format('d/m/Y'); ?></td>
											<td><?php echo $extintor->kilos; ?></td>
											<td><?php echo $extintor->tipo_extintor; ?></td>
											<td><?php echo $extintor->empresa_instalacion; ?></td>
											<td><?php echo $extintor->marca; ?></td>
											<td>
												<?php if ($relevamiento->fecha_hasta > date('Y-m-d H:i:s')): ?>
													<a class="btn btn-xs btn-warning" href="extintores/extintor/modal_editar/<?php echo $extintor->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-edit"></i></a>
													<a class="btn btn-xs btn-danger" href="extintores/extintor/modal_eliminar/<?php echo $extintor->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i></a>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="8" style="text-align: center;">-- No hay extintores cargados --</td>
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