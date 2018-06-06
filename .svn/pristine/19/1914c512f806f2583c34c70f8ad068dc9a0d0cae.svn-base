<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Calendarios
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Calendarios</a></li>
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
					<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta" href="calendario/agregar">
							<i class="fa fa-plus" id="btn-agregar"></i> Agregar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="calendario/ver/<?php echo (!empty($calendario->id)) ? $calendario->id : ''; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="calendario/editar/<?php echo (!empty($calendario->id)) ? $calendario->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="calendario/eliminar/<?php echo (!empty($calendario->id)) ? $calendario->id : ''; ?>">
							<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
						</a>
						<br>
						<?php foreach ($fields as $field): ?>
							<div class="form-group col-md-12">
								<?php echo $field['label']; ?>
								<?php echo $field['form']; ?>
							</div>
						<?php endforeach; ?>
						<div class="form-group col-md-12">
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
							<thead>
								<tr style="background-color: #f4f4f4" >
									<th style="text-align: center;" colspan="11">
										Período
										<?php if ($txt_btn === 'Editar'): ?>
											<a class="pull-left btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="calendario/modal_agregar_periodo/<?php echo $calendario->id; ?>"><i class="fa fa-plus"></i></a>
										<?php endif; ?>
									</th>
								</tr>
								<tr>
									<th>Ciclo Lectivo</th>
									<th>Período</th>
									<th>Fecha de Inicio</th>
									<th>Fecha de Fin</th>
									<?php if ($txt_btn === 'Editar'): ?>
									<th></th>
									<?php endif; ?>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($periodos)): ?>
									<?php foreach ($periodos as $periodo): ?>
										<tr>
											<td><?php echo $periodo->ciclo_lectivo ?></td>
											<td><?php echo $periodo->periodo ?>° <?php echo $periodo->nombre_periodo ?></td>
											<td><?= empty($periodo->inicio) ? '' : (new DateTime($periodo->inicio))->format('d/m/Y'); ?></td>
											<td><?= empty($periodo->fin) ? '' : (new DateTime($periodo->fin))->format('d/m/Y'); ?></td>
											<?php if ($txt_btn === 'Editar'): ?>
											<td width="60">
													<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="calendario/modal_editar_periodo/<?php echo $periodo->id ?>"><i class="fa fa-edit"></i></a>
													<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="calendario/eliminar_periodo/<?php echo $periodo->id ?>"><i class="fa fa-remove"></i></a>
											</td>
											<?php endif; ?>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td style="text-align: center;" colspan="6">
											-- No tiene --
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
					</div>
					<div class="box-footer">
						<?php if ($txt_btn === 'Editar'|| $txt_btn === 'Eliminar'): ?>
						<a class="btn btn-default" href="calendario/ver/<?php echo $calendario->id; ?>" title="Cancelar">Cancelar</a>
						<?php else: ?>
						<a class="btn btn-default" href="calendario/listar" title="Cancelar">Volver</a>
						<?php endif; ?>
						<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $calendario->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>