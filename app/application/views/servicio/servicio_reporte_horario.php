<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Servicios Esc. <?= $escuela->nombre_corto; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="servicio/listar/<?php echo $escuela->id . '/'; ?>">Servicios</a></li>
			<li class="active"><?php echo $metodo === 'separar_cargo' ? 'Separar cargo' : ucfirst($metodo); ?></li>
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
						<?php if ($txt_btn === 'Agregar'): ?>
							<a class="btn btn-app btn-app-zetta disabled <?php echo $class['agregar']; ?>" href="servicio/agregar">
								<i class="fa fa-plus"></i> Agregar
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="servicio/ver/<?php echo (!empty($servicios[0]->id)) ? $servicios[0]->id : ''; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<?php if ($edicion): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="servicio/editar/<?php echo (!empty($servicios[0]->id)) ? $servicios[0]->id : ''; ?>">
								<i class="fa fa-edit"></i> Editar
							</a>
							<?php if ($rol->codigo === ROL_ADMIN || $this->rol->codigo === ROL_USI): ?>
								<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="servicio/eliminar/<?php echo (!empty($servicios[0]->id)) ? $servicios[0]->id : ''; ?>">
									<i class="fa fa-ban"></i> Eliminar
								</a>
							<?php endif; ?>
							<a class="btn btn-app btn-app-zetta" href="servicio/separar_cargo/<?php echo (!empty($servicios[0]->id)) ? $servicios[0]->id : ''; ?>">
								<i class="fa fa-unlink"></i> Separar cargo
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta <?php echo $class['reporte_horario']; ?>" href="servicio/reporte_horario/<?php echo (!empty($servicios[0]->id)) ? $servicios[0]->id : ''; ?>">
							<i class="fa fa-list"></i> Reporte de horario
						</a>
						<div class="row">
							<div class="form-group col-md-6">
								<?php echo $fields['persona']['label']; ?>
								<div class="input-group">
									<?php echo $fields['persona']['form']; ?>
									<span class="input-group-btn">
										<a class="btn btn-default" title="Ver persona" href="datos_personal/ver/<?= $servicios[0]->id; ?>"><i class="fa fa-search"></i></a>
									</span>
								</div>
							</div>
							<div class="form-group col-md-6">
								<?php echo $fields['escuela']['label']; ?>
								<?php echo $fields['escuela']['form']; ?>
							</div>
						</div>
						<table class="table" style="text-align: center; margin-top:20px; table-layout: fixed;">
							<tr style="background-color: #f4f4f4">
								<th colspan="8" style="text-align:center;">Horarios del servicio</th>
							</tr>
							<tr>
								<th style="text-align: center;"></th>
								<?php foreach ($dias as $dia) : ?>
									<th style="text-align: center;"><?= mb_substr($dia->nombre, 0, 2); ?></th>
								<?php endforeach; ?>
							</tr>
							<tr>
								<td></td>
								<?php if (!empty($servicios)) : ?>
									<?php foreach ($servicios as $servicio) : ?>
										<?php foreach ($dias as $dia) : ?>
											<?php if ($servicio->dia_id == $dia->id): ?>
												<td style="vertical-align: middle;">
													<?= (!empty($servicio->sfh_hd) && !empty($servicio->sfh_hh)) ? $servicio->shd_hd . "<br>" . $servicio->shd_hh : $servicio->h_hd . "<br>" . $servicio->h_hh ?>
												</td>
											<?php endif; ?>
										<?php endforeach; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</tr>
						</table>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="servicio/listar/<?php echo $escuela->id . '/'; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $servicios[0]->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>