<?php if (!empty($instalaciones)): ?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="text-green fa fa-cloud"></i> Plan Nacional de Conectividad</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<div class="row">
				<?php foreach ($instalaciones as $instalacion): ?>
					<div class="col-xs-12">
						<h4 style="border-top: blue solid thin;"><?php echo $instalacion->descripcion . '<span class="pull-right"><b>Fechas de carga:</b> ' . (new DateTime($instalacion->fecha_desde))->format('d/m/y') . ' al ' . (new DateTime($instalacion->fecha_hasta))->format('d/m/y') . '</span>'; ?></h4>
						<table class="table table-bordered table-condensed table-striped table-hover">
							<thead>
								<tr>
									<th class="">
										<span class="label label-success"><?php echo $instalacion->encargados_asignados; ?></span> Encargados asignados
										<?php if ((new DateTime($instalacion->fecha_desde))->format('Y-m-d') <= date('Y-m-d')): ?>
											<?php if ((new DateTime($instalacion->fecha_hasta))->format('Y-m-d') >= date('Y-m-d')): ?>
												<span class="pull-right">
													<a class="btn btn-xs btn-primary" href="conectividad/instalacion/ver/<?php echo "$instalacion->id/$escuela->id"; ?>"><i class="fa fa-cogs"></i> Administrar</a>
												</span>
											<?php else: ?>
												<span class="pull-right">
													<a class="btn btn-xs btn-primary" href="conectividad/instalacion/ver/<?php echo "$instalacion->id/$escuela->id"; ?>"><i class="fa fa-search"></i> Ver</a>
												</span>
											<?php endif; ?>
										<?php else: ?>
											<span class="pull-right">
												<a class="btn btn-xs btn-primary disabled" href="conectividad/instalacion/ver/<?php echo "$instalacion->id/$escuela->id"; ?>"><i class="fa fa-ban"></i> Aún no disponible</a>
											</span>
										<?php endif; ?>
									</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th class="">
										<?php if (!empty($instalacion->fecha_cierre)): ?>
											<span class="label label-success"><i class="fa fa-lock"></i></span> Carga cerrada - <?php echo (new DateTime($instalacion->fecha_cierre))->format('d/m/Y H:i'); ?>
										<?php else: ?>
											<span class="label label-warning"><i class="fa fa-unlock"></i></span> Carga habilitada
										<?php endif; ?>
									</th>
								</tr>
							</tfoot>
							<tbody>
								<?php if (!empty($instalacion->encargados)): ?>
									<?php foreach ($instalacion->encargados as $encargado): ?>
										<tr>
											<td class="text-sm"><?php echo "$encargado->cuil $encargado->apellido, $encargado->nombre - $encargado->regimen"; ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr><td>No hay personal cargado.</td></tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<?php endif; ?>