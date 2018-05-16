<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Usuarios
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Usuarios</a></li>
			<li class="active"><?php echo str_replace('_', ' ', ucfirst($metodo)); ?></li>
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
						<a class="btn btn-app btn-app-zetta <?php echo $class['agregar']; ?>" href="usuario/permisos">
							<i class="fa fa-plus" id="btn-agregar"></i> Agregar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="usuario/ver/<?php echo (!empty($usuario->id)) ? $usuario->id : ''; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<?php if ($rol->codigo === ROL_ADMIN || $rol->codigo === ROL_USI): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="usuario/editar/<?php echo (!empty($usuario->id)) ? $usuario->id : ''; ?>">
								<i class="fa fa-edit" id="btn-editar"></i> Editar
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar_roles']; ?>" href="usuario/editar_roles/<?php echo (!empty($usuario->id)) ? $usuario->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar-valor"></i> Editar Roles
						</a>
						<?php if ($rol->codigo === ROL_ADMIN || $rol->codigo === ROL_USI): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="usuario/eliminar/<?php echo (!empty($usuario->id)) ? $usuario->id : ''; ?>">
								<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
							</a>
						<?php endif; ?>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields['usuario']['label']; ?>
								<?php echo $fields['usuario']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['cuil']['label']; ?>
								<?php echo $fields['cuil']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['nombre']['label']; ?>
								<?php echo $fields['nombre']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['active']['label']; ?>
								<?php echo $fields['active']['form']; ?>
							</div>
						</div>
						<?php if ($txt_btn !== 'Agregar'): ?>
							<table style="table-layout: fixed; margin-top: 3%;" class="table table-bordered table-condensed table-striped">
								<tr>
									<th style="text-align: center;">Roles
										<?php if ($txt_btn === 'Editar roles'): ?>
											<a class="pull-right btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="usuario_rol/modal_agregar/<?= $usuario->id ?>"><i class="fa fa-plus"></i></a>
										<?php endif; ?>
									</th>
								</tr>
								<tr>
									<td>
										<?php if (!empty($roles)): ?>
											<ul class="list-group">
												<?php foreach ($roles as $rol): ?>
													<li class="list-group-item">
														<?php if ($txt_btn === 'Editar roles'): ?>
															<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="usuario_rol/modal_eliminar/<?= $rol->id ?>/<?= $usuario->id ?>"><i class="fa fa-remove"></i></a>
														<?php endif; ?>
														<span> <?= $rol->rol; ?></span>
														<span> <?= $rol->entidad; ?></span>
													</li>
												<?php endforeach; ?>
											</ul>
										<?php else: ?>
											-- Sin roles --
										<?php endif; ?>
									</td>
								</tr>
							</table>
						<?php endif; ?>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="usuario/listar" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php if (!empty($txt_btn) && $txt_btn !== 'Editar valores') echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $usuario->id) : ''; ?>
						<?php echo ($txt_btn === 'Agregar') ? form_hidden('usuario', $usuario_id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>