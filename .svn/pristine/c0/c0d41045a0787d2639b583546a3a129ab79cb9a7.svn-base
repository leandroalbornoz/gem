<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Escuelas de Grupos de Escuelas
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Escuelas de Grupos de Escuelas</a></li>
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
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="escuela_grupo/ver_grupo/<?php echo (!empty($escuela_grupo->id)) ? $escuela_grupo->id : ''; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="escuela_grupo/editar_grupo/<?php echo (!empty($escuela_grupo->id)) ? $escuela_grupo->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<div class="row">
							<?php foreach ($fields as $field): ?>
								<div class="form-group col-sm-12">
									<?php echo $field['label']; ?>
									<?php echo $field['form']; ?>
								</div>	
							<?php endforeach; ?>
						</div>
						<table style="table-layout: fixed; margin-top: 3%;" class="table table-bordered table-condensed table-striped">
							<tr>
								<th  style="text-align: center;"> Escuelas Pertenecientes a <?php echo $escuela_grupo->descripcion; ?>
									<?php if ($txt_btn === 'Editar'): ?>
									<a class=" pull-right btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="escuela_grupo/modal_agregar_escuela/<?= $escuela_grupo->id; ?>"><i class="fa fa-plus">&nbsp;Agregar Escuela</i></a>

									<?php endif; ?>
								</th>
							</tr>
							<tr>
								<?php if(!empty($escuela_grupo_escuela)): ?>
								<td>
									<ul class="list-group">
										<?php foreach ($escuela_grupo_escuela as $escuela): ?>
											<li class="list-group-item">
												<?php if ($txt_btn === 'Editar'): ?>
												<a href="escuela_grupo/modal_eliminar_escuela/<?php echo $escuela->id ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-danger" title="Eliminar"><i class="fa fa-remove"></i>
													</a>&nbsp;
													<?php echo $escuela->escuela; ?>
												<?php else: ?>
													<?php echo $escuela->escuela; ?>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									</ul>
								</td>
								<?php else: ?>
								<td style="text-align: center;">
									<ul class="list-group">
											<li class="list-group-item">
												-- No hay escuelas cargadas en este grupo --
											</li>
									</ul>
								</td>
								<?php endif; ?>
							</tr>
						</table>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="escuela_grupo/listar" title="Cancelar">Cancelar</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>