<?php if (!empty($elecciones)): ?>
	<div class="box box-primary <?php echo $activo ? '' : 'collapsed-box'; ?>">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="text-green fa fa-shower"></i> Desinfección Elecciones</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa <?php echo $activo ? 'fa-minus' : 'fa-plus'; ?>"></i></button>
			</div>
		</div>
		<div class="box-body">
			<div class="row">
				<?php foreach ($elecciones as $eleccion): ?>
					<div class="col-xs-6">
						<h4 style="border-top: blue solid thin;"><?php echo $eleccion->descripcion . '<span class="pull-right"><b>Fechas de carga:</b> ' . (new DateTime($eleccion->fecha_desde))->format('d/m/y') . ' al ' . (new DateTime($eleccion->fecha_hasta))->format('d/m/y') . '</span>'; ?></h4>
						<table class="table table-bordered table-condensed table-striped table-hover">
							<thead>
								<tr>
									<th class="">
										<span class="label label-success"><?php echo $eleccion->celadores_permitidos; ?></span> Celadores permitidos
										<span class="pull-right">
											<span class="label label-success"><?php echo $eleccion->mesas; ?></span> Mesas
										</span>
									</th>
								</tr>
								<tr>
									<th class="">
										<span class="label label-success"><?php echo $eleccion->celadores_asignados; ?></span> Celadores asignados
										<?php if ($eleccion->fecha_desde <= date('Y-m-d')): ?>
											<?php if ($eleccion->fecha_hasta >= date('Y-m-d')): ?>
												<span class="pull-right">
													<a class="btn btn-xs btn-primary" href="elecciones/desinfeccion/ver/<?php echo "$eleccion->id/$escuela->id"; ?>"><i class="fa fa-cogs"></i> Administrar</a>
												</span>
											<?php else: ?>
												<span class="pull-right">
													<a class="btn btn-xs btn-primary" href="elecciones/desinfeccion/ver/<?php echo "$eleccion->id/$escuela->id"; ?>"><i class="fa fa-search"></i> Ver</a>
												</span>
											<?php endif; ?>
										<?php else: ?>
											<span class="pull-right">
												<a class="btn btn-xs btn-primary disabled" href="elecciones/desinfeccion/ver/<?php echo "$eleccion->id/$escuela->id"; ?>"><i class="fa fa-ban"></i> Aún no disponible</a>
											</span>
										<?php endif; ?>
									</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th class="">
										<span class="label label-success"><?php echo $eleccion->celadores_permitidos - $eleccion->celadores_asignados; ?></span> Celadores disponibles
									</th>
								</tr>
								<tr>
									<th class="">
										<?php if (!empty($eleccion->fecha_cierre)): ?>
											<span class="label label-success"><i class="fa fa-lock"></i></span> Carga cerrada - <?php echo (new DateTime($eleccion->fecha_cierre))->format('d/m/Y H:i'); ?>
										<?php else: ?>
											<span class="label label-warning"><i class="fa fa-unlock"></i></span> Carga habilitada
										<?php endif; ?>
									</th>
								</tr>
							</tfoot>
							<tbody>
								<?php if (!empty($eleccion->celadores)): ?>
									<?php foreach ($eleccion->celadores as $celador): ?>
										<tr>
											<td class="text-sm"><?php echo "$celador->cuil $celador->apellido, $celador->nombre"; ?></td>
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