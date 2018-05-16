<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Supervisiones
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Supervisiones</a></li>
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
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="supervision/ver/<?php echo (!empty($supervision->id)) ? $supervision->id : ''; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="supervision/editar/<?php echo (!empty($supervision->id)) ? $supervision->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['caracteristica']; ?>" href="supervision/caracteristicas/<?php echo (!empty($supervision->id)) ? $supervision->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Características
						</a>
						<?php if (in_array($this->rol->codigo, $this->roles_admin)): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="supervision/eliminar/<?php echo (!empty($supervision->id)) ? $supervision->id : ''; ?>">
								<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
							</a>
						<?php endif; ?>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields['dependencia']['label']; ?>
								<?php echo $fields['dependencia']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['linea']['label']; ?>
								<?php echo $fields['linea']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['nivel']['label']; ?>
								<?php echo $fields['nivel']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['nombre']['label']; ?>
								<?php echo $fields['nombre']['form']; ?>
							</div>
						</div>
						<?php if (!empty($fields_tipos)): ?>
							<ul class="nav nav-tabs" role="tablist">
								<?php $tab_class = 'active'; ?>
								<?php foreach ($fields_tipos as $tipo => $fields_caracteristicas): ?>
									<li role="presentation" class="<?= $tab_class; ?>"><a href="#<?= strtolower(str_replace(' ', '_', $tipo)); ?>" aria-controls="<?= strtolower(str_replace(' ', '_', $tipo)); ?>" role="tab" data-toggle="tab"><?= $tipo; ?></a></li>
									<?php $tab_class = ''; ?>
								<?php endforeach; ?>
							</ul>
							<div class="tab-content">
								<?php $tab_class = 'active'; ?>
								<?php foreach ($fields_tipos as $tipo => $fields_caracteristicas): ?>
									<div role="tabpanel" class="tab-pane <?= $tab_class; ?>" id="<?= strtolower(str_replace(' ', '_', $tipo)); ?>">
										<?php $tab_class = ''; ?>
										<div class="row">
											<?php foreach ($fields_caracteristicas as $field): ?>
												<div class="form-group col-sm-3">
													<?php echo $field['label']; ?>
													<?php echo $field['form']; ?>
												</div>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="<?php echo isset($redirect) ? $redirect : 'supervision/listar' ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $supervision->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>