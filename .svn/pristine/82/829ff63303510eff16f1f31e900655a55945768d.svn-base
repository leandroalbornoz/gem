<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Características
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="caracteristica_<?= $caracteristica_tipo->entidad; ?>/listar">Tipos de características <?= $caracteristica_tipo->entidad; ?></a></li>
			<li><a href="caracteristica/listar/<?= "$caracteristica_tipo->entidad/$caracteristica_tipo->id"; ?>"><?= $caracteristica_tipo->descripcion; ?></a></li>
			<?php if (isset($caracteristica)): ?>
				<li><a class="active"><?= $caracteristica->descripcion; ?></a></li>
			<?php endif; ?>
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
						<a class="btn btn-app btn-app-zetta <?php echo $class['agregar']; ?>" href="caracteristica/agregar/<?= "$caracteristica_tipo->entidad/$caracteristica_tipo->id"; ?>">
							<i class="fa fa-plus" id="btn-agregar"></i> Agregar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="caracteristica/ver/<?= isset($caracteristica) ? $caracteristica->id : '' ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="caracteristica/editar/<?= isset($caracteristica) ? $caracteristica->id : '' ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar_valores']; ?>" href="caracteristica/editar_valores/<?= isset($caracteristica) ? $caracteristica->id : '' ?>">
							<i class="fa fa-edit" id="btn-editar-valor"></i> Editar Valores
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="caracteristica/eliminar/<?= isset($caracteristica) ? $caracteristica->id : '' ?>">
							<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
						</a>
						<div class="row">
							<div class="form-group col-md-4">
								<?php echo $fields['caracteristica_tipo']['label']; ?>
								<?php echo $fields['caracteristica_tipo']['form']; ?>
							</div>
							<div class="form-group col-md-4">
								<?php echo $fields['descripcion']['label']; ?>
								<?php echo $fields['descripcion']['form']; ?>
							</div>
							<div class="form-group col-md-4">
								<?php echo $fields['valor_vacio']['label']; ?>
								<?php echo $fields['valor_vacio']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['lista_valores']['label']; ?>
								<?php echo $fields['lista_valores']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['valor_multiple']['label']; ?>
								<?php echo $fields['valor_multiple']['form']; ?>
							</div>
							<?php if ($txt_btn === 'Agregar' || $txt_btn === 'Editar'): ?>
								<div class="form-group col-md-12">
									<?php echo $fields['niveles']['label']; ?>
									<?php echo $fields['niveles']['form']; ?>
								</div>
							<?php endif; ?>
						</div>
						<?php if ($txt_btn !== 'Agregar'): ?>
							<table style="table-layout: fixed; margin-top: 3%;" class="table table-bordered table-condensed table-striped">
								<tr>
									<th style="text-align: center;">Valores
										<?php if ($txt_btn === 'Editar valores'): ?>
											<a class="pull-right btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="caracteristica_valor/modal_agregar/<?= $caracteristica->id ?>"><i class="fa fa-plus"></i></a>
										<?php endif; ?>
									</th>
								</tr>
								<tr>
									<td>
										<?php if (!empty($valores)): ?>
											<ul class="list-group">
												<?php foreach ($valores as $valor): ?>
													<li class="list-group-item">
														<?php if ($txt_btn === 'Editar valores'): ?>
															<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="caracteristica_valor/modal_eliminar/<?= $valor->id ?>/<?= $caracteristica->id ?>"><i class="fa fa-remove"></i></a>
															<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="caracteristica_valor/modal_editar/<?= $valor->id ?>/<?= $caracteristica->id ?>"><i class="fa fa-edit"></i></a>
														<?php endif; ?>
														<span class=""> <?= $valor->valor; ?></span>
													</li>
												<?php endforeach; ?>
											</ul>
										<?php else: ?>
											-- Sin valores --
										<?php endif; ?>
									</td>
								</tr>
							</table>
						<?php endif; ?>
						<?php if ($txt_btn !== 'Agregar' && $txt_btn !== 'Editar'): ?>
							<table style="table-layout: fixed; margin-top: 3%;" class="table table-bordered table-condensed table-striped">
								<tr>
									<th style="text-align: center;">Niveles</th>
								</tr>
								<tr>
									<td>
										<?php if (!empty($niveles)): ?>
											<ul class="list-group">
												<?php foreach ($niveles as $nivel): ?>
													<li class="list-group-item">
														<span> <?= $nivel->nivel ?></span>
													</li>
												<?php endforeach; ?>
											</ul>
										<?php else: ?>
											-- Sin niveles --
										<?php endif; ?>
									</td>
								</tr>
							</table>
						<?php endif; ?>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="caracteristica/listar/<?= "$caracteristica_tipo->entidad/$caracteristica_tipo->id"; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php if (!empty($txt_btn) && $txt_btn !== 'Editar valores') echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $caracteristica->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>