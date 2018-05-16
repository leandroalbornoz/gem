<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Espacios extracurriculares
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Espacios extracurriculares</a></li>
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
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="espacio_extracurricular/ver/<?php echo $nivel->id; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="espacio_extracurricular/editar/<?php echo $nivel->id; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<h4>
							Espacios extracurriculares de <?php echo $nivel->descripcion; ?>
						</h4>
						<?php if (!empty($cursos)): ?>
							<table style="table-layout: fixed" class="table table-bordered table-condensed table-striped">
								<tr>
									<?php foreach ($cursos as $curso): ?>
										<th style="text-align: center;"><?php echo $curso->descripcion; ?>
											<?php if ($txt_btn === 'Editar'): ?>
												<a class="pull-right btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="espacio_extracurricular/modal_agregar/<?= $nivel->id ?>/<?= $curso->id ?>"><i class="fa fa-plus"></i><i class="fa fa-book"></i></a>
											<?php endif; ?>
										</th>
									<?php endforeach; ?>
								</tr>
								<tr>
									<?php foreach ($cursos as $curso): ?>
										<td>
											<?php if (!empty($curso->materias)): ?>
												<table class="table table-bordered table-condensed">
													<?php foreach ($curso->materias as $materia): ?>
														<?php if (isset($materia->grupo_id)): ?>
															<tr>
																<td colspan="2" style="font-size: 12px; vertical-align: middle;"><?php echo $materia->materia; ?></td>
															</tr>
														<?php else: ?>
															<tr>
																<td style="vertical-align: middle;"><?php echo $materia->materia; ?></td>
																<td style="width: 60px; text-align: center; vertical-align: middle;">
																	<?php if ($txt_btn === 'Editar'): ?>
																		<a style="vertical-align: middle;" class="btn btn-xs btn-danger" type="button" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="espacio_extracurricular/modal_eliminar/<?= $materia->id ?>"><i class="fa fa-remove"></i></a>
																		<a style="vertical-align: middle;" class="btn btn-xs btn-warning" type="button" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="espacio_extracurricular/modal_editar/<?= $materia->id ?>"><i class="fa fa-edit"></i></a>
																	<?php endif; ?>
																	<?php echo $materia->carga_horaria; ?>
																</td>
															</tr>
														<?php endif; ?>
													<?php endforeach; ?>	
												</table>
											<?php endif; ?>
										</td>
									<?php endforeach; ?>
								</tr>
							</table>
						</div>
					<?php endif; ?>
					<div class="box-footer">
						<a class="btn btn-default" href="espacio_extracurricular/listar" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>