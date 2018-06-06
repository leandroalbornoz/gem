<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Feria Educativa - Escuelas
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Escuelas</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="ingreso/feria/escuela/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="ingreso/feria/escuela_editar/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-edit"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta" href="ingreso/feria/escuela_editar_area_interes/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-edit"></i> Áreas de Interés
						</a>
						<a class="btn btn-app btn-app-zetta" href="ingreso/feria/escuela_asignaciones/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-user"></i> Asignaciones
						</a>
						<hr style="margin: 10px 0;">
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
									<thead>
										<tr style="background-color: #f4f4f4">
											<th colspan="4" class="text-center">
												Videos 
												<a class="btn btn-xs btn-success pull-left" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="ingreso/feria/modal_video_agregar/<?= $escuela->id ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;<i class="fa fa-video-camera"></i></a>
											</th>
										</tr>
										<tr>
											<th>Nombre</th>
											<th>Url</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($lista_videos)): ?>
											<?php foreach ($lista_videos as $video): ?>
												<tr>
													<td><?= $video->pie; ?></td>
													<td><?= $video->path; ?></td>
													<td>
														<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="ingreso/feria/modal_video_editar/<?= $video->id ?>"><i class="fa fa-edit"></i></a>
														<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="ingreso/feria/modal_video_eliminar/<?= $video->id ?>"><i class="fa fa-remove"></i></a>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr>
												<td colspan="4" class="text-center">-- No tiene --</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
									<thead>
										<tr style="background-color: #f4f4f4">
											<th colspan="4" class="text-center">
												Texto
												<a class="btn btn-xs btn-success pull-left" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="ingreso/feria/modal_texto_agregar/<?= $escuela->id ?>"><i class="fa fa-plus">&nbsp;&nbsp;<i class="fa fa-file-text-o"></i></i></a>
											</th>
										</tr>
										<tr>
											<th>Título</th>
											<th>Texto</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($lista_texto)): ?>
											<?php foreach ($lista_texto as $texto): ?>
												<tr>
													<td><?= $texto->encabezado; ?></td>
													<td><?= $texto->texto; ?></td>
													<td>
														<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="ingreso/feria/modal_texto_editar/<?= $texto->id ?>"><i class="fa fa-edit"></i></a>
														<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="ingreso/feria/modal_texto_eliminar/<?= $texto->id ?>"><i class="fa fa-remove"></i></a>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr>
												<td colspan="4" class="text-center">-- No tiene --</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
									<thead>
										<tr style="background-color: #f4f4f4">
											<th colspan="3" class="text-center">
												Imagines
												<a class="btn btn-xs btn-success pull-left" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="ingreso/feria/modal_imagen_agregar/<?= $escuela->id ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;<i class="fa fa-picture-o"></i></a>
											</th>
										</tr>
										<tr>
											<th>Nombre</th>
											<th>Pie</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($lista_imagen)): ?>
											<?php foreach ($lista_imagen as $imagen): ?>
												<tr>
													<td><?= $imagen->path; ?></td>
													<td><?= $imagen->pie; ?></td>
													<td>
														<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="ingreso/feria/modal_imagen_editar/<?= $imagen->id ?>"><i class="fa fa-edit"></i></a>
														<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="ingreso/feria/modal_imagen_eliminar/<?= $imagen->id ?>"><i class="fa fa-remove"></i></a>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr>
												<td colspan="4" class="text-center">-- No tiene --</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="ingreso/feria/listar" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
