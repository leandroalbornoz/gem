<style>
	td.child>ul{
		width:100%;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Personas
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Personas</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="persona/ver/<?php echo $persona->id; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta" href="persona/liquidacion/<?php echo $persona->id; ?>">
							<i class="fa fa-money"></i> Liquidación
						</a>
						<a class="btn btn-app btn-app-zetta btn-app-zetta-active active" href="persona/unificar_servicios/<?php echo $persona->id; ?>">
							<i class="fa fa-bookmark"></i> Unificar servicios
						</a>
						<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
						<div class="row">
							<div class="col-md-12">
								<h4><?php echo "$persona->apellido, $persona->nombre ($persona->documento_tipo $persona->documento - CUIL: $persona->cuil)"; ?></h4>
							</div>
						</div>
						<h4>Seleccione los servicios a unificar <button type="submit" class="pull-right btn btn-xs btn-success">Unificar servicios</button></h4>
						<?php if (!empty($servicios)): ?>
							<table class="table table-hover table-bordered table-condensed table-responsive dt-responsive dataTable no-footer dtr-inline text-sm" role="grid" id="servicio_table" style="width:100% !important">
								<thead>
									<tr style="background-color: #f4f4f4" >
										<th style="text-align: center;" colspan="10">Servicios</th>
									</tr>
									<tr>
										<th></th>
										<th>Liquidación</th>
										<th>S.R.</th>
										<th>Escuela/Area</th>
										<th>División</th>
										<th>Régimen/Materia</th>
										<th>Hs. Cát.</th>
										<th>Fecha alta</th>
										<th>Fecha baja</th>
										<th></th>
										<th class="none">Observaciones</th>
										<th class="none">F.Detalle</th>
										<th class="none">F.Destino</th>
										<th class="none">F.Norma</th>
										<th class="none">F.Tarea</th>
										<th class="none">F.Hs.</th>
										<th class="none">F.Desde</th>
										<th class="none">Motivo Baja</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($servicios as $servicio): ?>
										<tr data-filter="" class="<?= empty($servicio->fecha_baja) ? '' : 'bg-gray'; ?>">
											<td></td>
											<td>
												<?= $servicio->liquidacion; ?>
												<?php if (empty($servicio->tbcabh_id)): ?>
													<i class="fa fa-times text-red"></i>
												<?php else: ?>
													<i class="fa fa-check text-green"></i>
												<?php endif; ?>
											</td>
											<td><?= $servicio->situacion_revista; ?></td>
											<td><?php echo empty($servicio->area) ? $servicio->escuela : $servicio->area; ?></td>
											<td class="dt-body-center"><?= $servicio->division; ?></td>
											<td><?= "$servicio->regimen_codigo-$servicio->regimen<br/>$servicio->materia"; ?></td>
											<td class="dt-body-center"><?= $servicio->carga_horaria; ?></td>
											<td><?= empty($servicio->fecha_alta) ? '' : (new DateTime($servicio->fecha_alta))->format('d/m/Y'); ?></td>
											<td><?= empty($servicio->fecha_baja) ? '' : (new DateTime($servicio->fecha_baja))->format('d/m/Y'); ?></td>
											<td>
												<a class="pull-right btn btn-xs btn-default" target="_blank" href="servicio/ver/<?= $servicio->id; ?>"><i class="fa fa-search"></i></a>
												<?php if (empty($servicio->liquidacion)): ?>
													<label><input type="radio" name="servicio_1_id" value="<?php echo $servicio->id; ?>">Cargo</label>
												<?php else: ?>
													<label><input type="radio" name="servicio_2_id" value="<?php echo $servicio->id; ?>">N°Liq</label>
												<?php endif; ?>
											</td>
											<td><?= $servicio->observaciones; ?></td>
											<td><?= $servicio->f_detalle; ?></td>
											<td><?= $servicio->f_destino; ?></td>
											<td><?= $servicio->f_norma; ?></td>
											<td><?= $servicio->f_tarea; ?></td>
											<td><?= $servicio->f_carga_horaria; ?></td>
											<td><?= empty($servicio->f_fecha_desde) ? '' : (new DateTime($servicio->f_fecha_desde))->format('d/m/Y'); ?></td>
											<td><?= $servicio->motivo_baja; ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php else: ?>
							-- Sin servicios --
						<?php endif; ?>
						<?php if (!empty($liquidaciones)): ?>
							<table class="table table-hover table-bordered table-condensed table-responsive dt-responsive dataTable no-footer dtr-inline text-sm" role="grid" id="liquidacion_table" style="width:100% !important">
								<thead>
									<tr style="background-color: #f4f4f4" >
										<th style="text-align: center;" colspan="12">Liquidaciones</th>
									</tr>
									<tr>
										<th>Servicio</th>
										<th>Cobra sueldo</th>
										<th>Año-Mes<br/>Periodo</th>
										<th>N°Liquidación</th>
										<th>S.R.</th>
										<th>Repartición/Escuela</th>
										<th>Régimen</th>
										<th>Clase</th>
										<th>Hs/Días</th>
										<th>Fecha alta<br/>(Mes-Año)</th>
										<th>Fecha baja<br/>(Mes-Año)</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($liquidaciones as $liquidacion): ?>
										<tr class="<?= $liquidacion->vigente === AMES_LIQUIDACION ? '' : 'bg-gray'; ?>">
											<td><?= empty($liquidacion->servicio_id) ? '<i class="fa fa-times text-red"></i>' : form_hidden('servicio', $liquidacion->servicio_id) . '<i class="fa fa-check text-green"></i>'; ?></td>
											<td><?= $liquidacion->SINSUELDO === '1' ? '<i class="fa fa-times text-red"></i>' : '<i class="fa fa-check text-green"></i>'; ?></td>
											<td><?= $liquidacion->vigente . '<br/>' . $liquidacion->periodo; ?></td>
											<td><?= $liquidacion->liquidacion_s; ?></td>
											<td><?= $liquidacion->REVISTA; ?></td>
											<td><?= "$liquidacion->juri/$liquidacion->repa" . (empty($liquidacion->guiescid) ? '' : " - Esc. $liquidacion->guiescid"); ?></td>
											<td><?= "$liquidacion->regimen-$liquidacion->RegSalDes"; ?></td>
											<td><?= str_pad($liquidacion->diasoblig, 4, '0', STR_PAD_LEFT); ?></td>
											<td><?= $liquidacion->diashorapag; ?></td>
											<td><?= substr($liquidacion->fechaini, 0, 2) . '-' . substr($liquidacion->fechaini, 2); ?></td>
											<td><?= substr($liquidacion->fechafin, 0, 2) . '-' . substr($liquidacion->fechafin, 2); ?></td>
											<td>
												<a href="tbcabh/modal_ver/<?= "$liquidacion->id/$liquidacion->vigente"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" class="btn btn-xs btn-default"><i class="fa fa-search"></i></a>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php else: ?>
							-- Sin liquidaciones --
						<?php endif; ?>
						<?php echo form_close(); ?>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="persona/listar" title="Volver">Volver</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		$('#servicio_table').DataTable({
			dom: 't',
			autoWidth: false,
			paging: false,
			ordering: false
		});
		$('input[name="servicio_id[]"]').change(function() {
			$('input[name="servicio_id[]"]').each(function() {
				if (this.checked) {
				} else {
				}
			});
		});
	});
</script>