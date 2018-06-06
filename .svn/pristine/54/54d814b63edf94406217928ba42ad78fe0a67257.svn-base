<?php if (!empty($instalaciones)): ?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="text-green fa fa-cloud"></i> Plan Nacional de Conectividad</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<table class="table table-bordered table-condensed table-striped table-hover">
				<thead>
					<tr>
						<th>Escuela</th>
						<th style="text-align: center;">Encargados asignados</th>
						<th style="text-align: center;">Periodo de instalación</th>
						<th style="text-align: center;">Teléfono de contacto</th>
						<th style="text-align: center;"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($instalaciones as $instalacion): ?>
						<tr>
							<td style="width: 40%;"><?php echo "$instalacion->nombre_escuela"; ?></td>
							<td style="width: 15%; text-align: center;">
								<span class="label <?php echo ($instalacion->encargados_asignados === '0') ? 'label-danger' : 'label-success'; ?>"><?php echo $instalacion->encargados_asignados; ?></span>
							</td>
							<td style="width: 17%;text-align: center;">
								<?php echo (new DateTime($instalacion->fecha_inicio))->format('d/m/y') . ' al ' . (new DateTime($instalacion->fecha_fin))->format('d/m/y'); ?>
							</td>
							<td style="width: 17%;text-align: center;">
								<?php echo!(empty($instalacion->celular_contacto)) ? '<i class="fa fa-check text-green"></i>' . $instalacion->celular_contacto : '<i class="fa fa-times text-red"></i> Falta cargar'; ?>
							</td>
							<td style="width: 11%; text-align: center;">
								<?php if ((new DateTime($instalacion->fecha_desde))->format('Y-m-d') <= date('Y-m-d')): ?>
									<?php if ((new DateTime($instalacion->fecha_hasta))->format('Y-m-d') >= date('Y-m-d')): ?>
										<span class="pull-right">
											<a class="btn btn-xs btn-primary" target="_blank" href="conectividad/instalacion/ver/<?php echo "$instalacion->id/$instalacion->escuela_id"; ?>"><i class="fa fa-cogs"></i> Administrar</a>
										</span>
									<?php else: ?>
										<span class="pull-right">
											<a class="btn btn-xs btn-primary" target="_blank" href="conectividad/instalacion/ver/<?php echo "$instalacion->id/$instalacion->escuela_id"; ?>"><i class="fa fa-search"></i> Ver</a>
										</span>
									<?php endif; ?>
								<?php else: ?>
									<span class="pull-right">
										<a class="btn btn-xs btn-primary disabled" target="_blank" href="conectividad/instalacion/ver/<?php echo "$instalacion->id/$instalacion->escuela_id"; ?>"><i class="fa fa-ban"></i> Aún no disponible</a>
									</span>
								<?php endif; ?>
							</td>

						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php endif; ?>