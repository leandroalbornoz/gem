<?php if (!empty($proyectos)): ?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Programa Terminalidad Educativa (TEM)</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<?php foreach ($proyectos as $proyecto): ?>
				<h4 style="border-top: blue solid thin;"><?php echo $proyecto->descripcion . ' (Desde ' . (new DateTime($proyecto->fecha_desde))->format('d/m/Y') . ' a ' . (new DateTime($proyecto->fecha_hasta))->format('d/m/Y') . ')'; ?></h4>
				<div class="row">
					<div class="col-xs-4">
						<table class="table table-bordered table-condensed table-striped table-hover">
							<tr>
								<th colspan="4" class="text-center"><span class="label label-success"><?php echo $proyecto->horas_catedra; ?></span> Horas Cátedra permitidas</th>
							</tr>
							<tr>
								<th>Mes</th>
								<th>Semanas</th>
								<th>Obligaciones</th>
								<th></th>
							</tr>
							<?php foreach ($proyecto->meses as $mes): ?>
								<tr>
									<td><?php echo substr($this->nombres_meses[substr($mes->mes, 4, 2)], 0, 3) . ' \'' . substr($mes->mes, 2, 2); ?></td>
									<td class="text-center"><?php echo $mes->semanas; ?></td>
									<td class="text-center"><?php echo $mes->semanas * $proyecto->horas_catedra; ?></td>
									<td>
										<?php if ($administrar && $this->rol->codigo != ROL_ESCUELA_ALUM && date('Ym') >= $mes->mes): ?>
											<a class="btn btn-xs btn-primary" href="tem/personal/listar/<?php echo $escuela->id; ?>/<?php echo $mes->mes; ?>"><i class="fa fa-users"></i> Administrar</a>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>
					<div class="col-xs-8">
						<table class="table table-bordered table-condensed table-striped table-hover">
							<thead>
								<tr>
									<th colspan="4" class="text-center">
										<span class="label label-success"><?php echo $proyecto->horas_asignadas; ?></span> Horas Cátedra asignadas &nbsp;&nbsp;&nbsp; 
									</th>
								</tr>
								<tr>
									<th>Persona</th>
									<th style="width: 60px;">Alta</th>
									<th style="width: 60px;">Horas</th>
									<th style="width: 60px;">Baja</th>
								</tr>
							<tbody>
								</thead>
							<tfoot>
								<tr>
									<th colspan="4" class="text-center">
										<span class="label label-success"><?php echo $proyecto->horas_disponibles; ?></span> Horas Cátedra disponibles
									</th>
								</tr>
							</tfoot>
							<?php if (!empty($proyecto->personal)): ?>
								<?php foreach ($proyecto->personal as $persona): ?>
									<tr>
										<td class="text-sm"><?php echo "$persona->cuil $persona->apellido, $persona->nombre"; ?></td>
										<td class="text-center"><?php echo (new DateTime($persona->fecha_alta))->format('d/m/Y'); ?></td>
										<td class="text-center"><?php echo $persona->carga_horaria; ?></td>
										<td class="text-center"><?php echo empty($persona->fecha_baja) ? '' : (new DateTime($persona->fecha_baja))->format('d/m/Y'); ?></td>
									</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr><td colspan="4">No hay tutores cargados.</td></tr>
							<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>