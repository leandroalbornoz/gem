<style>
	.info-box-icon {
    border-top-left-radius: 5px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 5px;
    display: block;
    float: left;
    height: 55px;
    width: 70px;
    text-align: center;
    font-size: 45px;
    line-height: 52px;
		color: white;
    background: rgb(60, 141, 188);
	}
	.info-box {
    display: block;
		min-height: 0px; 
    background: #fff;
    width: 18%;
    height: 55px;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    border-radius: 6px;
    margin-bottom: 15px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Esc. <?= $escuela->nombre_largo; ?> <label class="label label-default"><?php echo $mes_nombre; ?></label>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="comedor/administrar/listar_escuelas/<?php echo $comedor_presupuesto->mes; ?>">Listar escuelas</a></li>
			<li><a href="comedor/administrar/escuela_ver/<?php echo $comedor_presupuesto->id; ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li class="active"> Ver</li>
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
		<?php if (empty($comedor_presupuesto->dias_albergado)): ?> 
			<div class="alert alert-danger alert-dismissable">
				<i class="icon fa fa-ban" style="font-size: 18px;"></i>Sin dias Cargados.
			</div>
		<?php endif; ?>

		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta" href="comedor/administrar/listar_escuelas/<?php echo $mes; ?>">
							<i class="fa fa-building"></i> Escuelas
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['administrar']; ?>" href="comedor/administrar/escuela_ver/<?php echo $comedor_presupuesto->id; ?>">
							<i class="fa fa-pencil-square-o"></i> Administrar
						</a>
						<a class="btn btn-app bg-default btn-app-zetta" href="comedor/administrar/reporte_excel_escuela_comedor/<?= "$comedor_presupuesto->id/$comedor_presupuesto->mes"; ?>">
							<i class="fa fa-file-excel-o text-green" id="btn-reporte"></i> Reporte
						</a>
						<?php if (!(empty($comedor_presupuesto->dias_albergado))): ?>
							<?php if ((empty($comedor_presupuesto->monto_entregado))): ?> 
								<a class="btn bg-green btn-app btn-app-zetta pull-right" href="comedor/administrar/modal_presupuesto/<?php echo $comedor_presupuesto->id; ?>" data-toggle="modal" data-target="#remote_modal">
									<i class="fa fa-money"></i> Asignar 
								</a>
							<?php else: ?>
								<a class="btn bg-yellow btn-app btn-app-zetta pull-right"href="comedor/administrar/modal_presupuesto/<?php echo $comedor_presupuesto->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal">
									<i class="fa fa-money"></i> Editar 
								</a>
							<?php endif; ?>
							<div class="info-box" style="float: right;">
								<span class="info-box-icon"><i class="fa fa-calendar-check-o"></i></span>

								<div class="info-box-content" style="margin-left: 65px;">
									<span class="info-box-text">Días de albergue</span>
									<span class="info-box-number"><?php echo $comedor_presupuesto->dias_albergado; ?></span>
								</div>
							</div>
						<?php endif; ?>
						<hr style="margin: 10px 0;">
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<?php if (!empty($comedor_divisiones)): ?>
										<table class="table table-bordered table-condensed table-striped table-hover">
											<thead>
												<tr>
													<th>División</th>
													<th>Turno</th>
													<th style="width: 150px;">Alumnos</th>
													<th style="width: 150px;">Ración Completa</th>
													<th style="width: 150px;">Media Ración</th>
													<th style="width: 150px;">Sin Ración</th>
												</tr>
											</thead>
											<tbody>
												<?php $total_alumnos = 0; ?>
												<?php $total_alumnos_r1 = 0; ?>
												<?php $total_alumnos_r2 = 0; ?>
												<?php $total_alumnos_sr = 0; ?>
												<?php foreach ($comedor_divisiones as $division): ?>
													<tr>
														<td><?php echo "$division->curso $division->division"; ?></td>
														<td><?php echo "$division->turno"; ?></td>
														<td class="text-center"><?php echo empty($division->alumnos) ? '' : $division->alumnos; ?></td>
														<td class="text-center"><?php echo empty($division->alumnos_r1) ? '' : $division->alumnos_r1; ?></td>
														<td class="text-center"><?php echo empty($division->alumnos_r2) ? '' : $division->alumnos_r2; ?></td>
														<td class="text-center"><?php echo empty($division->alumnos_sr) ? '' : $division->alumnos_sr; ?></td>
													</tr>
													<?php $total_alumnos += $division->alumnos; ?>
													<?php $total_alumnos_r1 += $division->alumnos_r1; ?>
													<?php $total_alumnos_r2 += $division->alumnos_r2; ?>
													<?php $total_alumnos_sr += $division->alumnos_sr; ?>
												<?php endforeach; ?>
											</tbody>
											<tr><th colspan="7"></th></tr>
											<tr>
												<th colspan="2">Totales generales para la escuela</th>
												<th style="text-align: center;"><?php echo $total_alumnos; ?></th>
												<th style="text-align: center;"><?php echo $total_alumnos_r1; ?></th>
												<th style="text-align: center;"><?php echo $total_alumnos_r2; ?></th>
												<th style="text-align: center;"><?php echo $total_alumnos_sr; ?></th>
											</tr>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="col-md-5">
										<table class="table table-bordered table-condensed table-striped table-hover">
											<tr>
												<th>Monto ideal </th>
												<th><?php echo"$ " . number_format($monto_ideal, 2, ',', '.'); ?></th>
											</tr>
											<tr> 
												<th> Monto asignado </th>
												<?php if (!empty($comedor_presupuesto->monto_entregado)): ?>
													<th><?php echo"$ " . number_format($comedor_presupuesto->monto_entregado, 2, ',', '.'); ?></th>
												<?php else: ?>
													<th><div class="bg-red text-bold" style="text-align: center;">Monto no asignado</div></th>
												<?php endif; ?>
											</tr>
											<tr> 
												<th> Monto consumido<?php echo(!empty($monto_consumido)) ? "(" . date('d/m/Y') . ")" : '' ?></th>
												<?php if (!empty($monto_consumido)): ?>
													<th><?php echo"$ " . number_format($monto_consumido, 2, ',', '.'); ?> </th>
												<?php else: ?>
													<th><div class="bg-red text-bold" style="text-align: center;">Monto no calculado</div></th>
												<?php endif; ?>
											</tr>
										</table>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="comedor/administrar/listar_escuelas/<?php echo $mes; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>