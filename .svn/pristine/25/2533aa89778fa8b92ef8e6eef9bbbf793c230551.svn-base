<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Postulaciones Becas - Recepción
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/escritorio/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li class="active">Recepción Postulaciones</li>
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
			<?= $vw_etapas; ?>
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 style="width:100%; padding-right: 30px;" class="box-title">Postulaciones recibidas</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body ">
						<table class="table table-condensed table-bordered">
							<thead>
								<tr>
									<th>Fecha</th>
									<th>CUIL</th>
									<th>Persona</th>
									<th>Estado</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($personas)): ?>
									<?php foreach ($personas as $persona): ?>
										<tr>
											<td><?= (new DateTime($persona->fecha))->format('d/m/Y H:i'); ?></td>
											<td><?= $persona->cuil; ?></td>
											<td><?= "$persona->apellido, $persona->nombre"; ?></td>
											<td><?= $persona->beca_estado; ?></td>
											<td><?php if ($persona->operaciones > 0): ?><a href="becas/escuela/modal_editar/<?= $persona->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Editar</a><?php endif; ?></td>
										</tr>
									<?php endforeach; ?>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>