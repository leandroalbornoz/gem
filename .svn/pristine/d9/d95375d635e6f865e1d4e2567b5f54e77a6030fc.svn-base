<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Lista de regímenes
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Lista de regímenes</a></li>
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
						<a class="btn btn-app btn-app-zetta <?php echo $class['agregar']; ?>" href="regimen_lista/agregar">
							<i class="fa fa-plus" id="btn-agregar"></i> Agregar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="regimen_lista/ver/<?php echo (!empty($regimen_lista->id)) ? $regimen_lista->id : ''; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="regimen_lista/editar/<?php echo (!empty($regimen_lista->id)) ? $regimen_lista->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="regimen_lista/eliminar/<?php echo (!empty($regimen_lista->id)) ? $regimen_lista->id : ''; ?>">
							<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
						</a>
						<div class="row">
							<?php foreach ($fields as $field): ?>
								<div class="form-group col-sm-12">
									<?php echo $field['label']; ?> 
									<?php echo $field['form']; ?>
								</div>
							<?php endforeach; ?>
						</div>
						<?php if ($txt_btn !== 'Agregar'): ?>
							<table style="table-layout: fixed; margin-top: 3%;" class="table table-bordered table-condensed table-striped">
								<tr>
									<th style="text-align: center;">Regímenes
										<?php if ($txt_btn === 'Editar'): ?>
											<a class="pull-right btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="regimen_lista/modal_agregar_regimen/<?= $regimen_lista->id; ?>"><i class="fa fa-plus"></i></a>
										<?php endif; ?>
									</th>
								</tr>
								<tr>
									<td>
										<?php if (isset($regimenes_lista) && !empty($regimenes_lista)): ?>
											<ul class="list-group">
												<?php foreach ($regimenes_lista as $regimen): ?>
													<li class="list-group-item">
														<?php if ($txt_btn === 'Editar'): ?>
															<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="regimen_lista/modal_quitar_regimen/<?= $regimen->id ?>"><i class="fa fa-remove"></i></a>
														<?php endif; ?>
														<span class=""> <?= $regimen->regimen ?></span>
													</li>
												<?php endforeach; ?> 
											</ul>
										<?php else: ?>
											-- Sin regímenes --
										<?php endif; ?>
									</td>
								</tr>
							</table>
							<input type="hidden" name="id" value="<?= $regimen_lista->id; ?>">
						<?php endif; ?>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="regimen_lista/listar" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $regimen_lista->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>