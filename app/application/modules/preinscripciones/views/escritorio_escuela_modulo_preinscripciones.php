<div class="box <?= $modulo_activo ? 'box-warning' : 'box-primary collapsed-box'; ?>">
	<div class="box-header with-border">
		<h3 class="box-title">Preinscripciones - Ciclo Lectivo <?= $ciclo_lectivo; ?></h3>
		&nbsp;
		<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/modal_consultar_estado/<?= $ciclo_lectivo ?>"><i class="fa fa-search"></i> Consulta de Alumnos</a>
		<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/modal_vacantes_generales/<?= $ciclo_lectivo ?>"><i class="fa fa-search"></i> Consulta de Escuelas con Vacantes</a>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa <?= $modulo_activo ? 'fa-minus' : 'fa-plus' ?>"></i></button>
		</div>
	</div>
	<div class="box-body">
		<?php foreach ($preinscripciones_array as $preinscripcion): ?>
			<table class="table table-condensed table-bordered table-striped table-hover">
				<tr>
					<th colspan="8" style="text-align:center;"><?= $preinscripcion->po_descripcion; ?></th>
				</tr>
				<tr>
					<th style="width: 20%;">Instancia</th>
					<th style="width: 10%;">Desde</th>
					<th style="width: 10%;">Hasta</th>
					<?php if (count($preinscripcion->turnos) > 1): ?>
						<?php foreach ($preinscripcion->turnos as $turno => $preinscripcion_turno): ?>
							<th style="width: 30%;text-align: center;" colspan="2">Turno <?php echo $turno ?></th>
						<?php endforeach; ?>
					<?php else: ?>
						<th style="width: 30%;text-align: center;" colspan="2">Estado</th>
					<?php endif; ?>
				</tr>
				<?php $fecha = date('Y-m-d'); ?>
				<tr>
					<td>Carga de vacantes</td>
					<td>
						<?php echo (new DateTime($preinscripcion_instancias[$preinscripcion->preinscripcion_operativo_id][0]->desde))->format('d/m/Y'); ?>
					</td>
					<td>
						<?php echo (new DateTime($preinscripcion_instancias[$preinscripcion->preinscripcion_operativo_id][0]->hasta))->format('d/m/Y'); ?>
					</td>
					<?php foreach ($preinscripcion->turnos as $turno => $preinscripcion_turno): ?>
						<?php if ($administrar): ?>
							<?php if ($fecha >= $preinscripcion_instancias[$preinscripcion->preinscripcion_operativo_id][0]->desde): ?>
								<?php if (empty($preinscripcion_turno->id)): ?>
									<td style="text-align: center;">
										<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/<?php echo (!empty($preinscripcion_turno->id)) ? 'editar_vacantes/' . $preinscripcion_turno->id : 'instancia/0/2018/' . $escuela->id; ?>/escuela"><i class="fa fa-edit"></i></a>
									</td>
								<?php elseif ($fecha <= $preinscripcion_instancias[$preinscripcion->preinscripcion_operativo_id][0]->hasta): ?>
									<td style="text-align: center;">
										<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/<?php echo (!empty($preinscripcion_turno->id)) ? 'editar_vacantes/' . $preinscripcion_turno->id : 'instancia/0/2018/' . $escuela->id; ?>/escuela"><i class="fa fa-edit"></i></a>
									</td>
								<?php else: ?>
									<td style="text-align: center;">
										<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/<?php echo (!empty($preinscripcion_turno->id)) ? 'ver_vacantes/' . $preinscripcion_turno->id : '' ?>"><i class="fa fa-search"></i> Ver vacantes</a>
									</td>
								<?php endif; ?>
							<?php endif; ?>
						<?php endif; ?>
						<td>
							<?php if (isset($preinscripcion_turno)): ?>
								<?php echo '<span><i class="fa fa-check text-green"></i> ' . $preinscripcion_turno->vacantes . ' Vacantes declaradas</span>'; ?>
							<?php else: ?>
								<?php echo '<span><i class="fa fa-times text-red"></i> -- Falta declarar vacantes -- </span>'; ?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
				<?php $vacantes = $preinscripcion_turno->vacantes; ?>
				<?php foreach ($preinscripcion_instancias[$preinscripcion->preinscripcion_operativo_id] as $instancia): ?>
					<?php if ($instancia->instancia !== '0'): ?>
						<tr>
							<td><?php echo $instancia->descripcion_larga; ?></td>
							<td><?php echo (new DateTime($instancia->desde))->format('d/m/Y'); ?></td>
							<td><?php echo (new DateTime($instancia->hasta))->format('d/m/Y'); ?></td>
							<?php foreach ($preinscripcion->turnos as $turno => $preinscripcion_turno): ?>
								<?php if ($administrar && $fecha >= $instancia->desde): ?>
									<?php if (!isset($preinscripcion_turno->id)): ?>
										<td></td>
									<?php elseif ($fecha <= $instancia->hasta): ?>
										<td style="text-align: center;"><a class="btn btn-primary btn-xs" href="preinscripciones/escuela/instancia/<?php echo "$instancia->instancia/$preinscripcion_turno->id"; ?>/escuela"><i class="fa fa-edit"></i></a>
										</td>
									<?php else: ?>
										<td style="text-align: center;">
											<a class="btn btn-primary btn-xs" href="preinscripciones/escuela/instancia/<?php echo "$instancia->instancia/$preinscripcion_turno->id"; ?>/escuela"><i class="fa fa-search"></i></a>
										</td>
									<?php endif; ?>
								<?php else: ?>
									<td></td>
								<?php endif; ?>
								<?php $vacantes -= $preinscripcion_turno->{"instancia_{$instancia->instancia}_i"}; ?>
								<td>
									<?php if (isset($preinscripcion_turno->{"instancia_$instancia->instancia"}) && ($preinscripcion_turno->{"instancia_{$instancia->instancia}_i"} !== '0' || $vacantes === 0)): ?>
										<?php echo '<span><i class="fa fa-check text-green"></i> ' . $preinscripcion_turno->{"instancia_{$instancia->instancia}_i"} . ' Alumnos inscriptos</span><br/>'; ?>
										<?php echo '<span><i class="fa fa-check text-green"></i> ' . $vacantes . ' Vacantes disponibles</span><br>'; ?>
										<?php if (isset($preinscripcion_turno->{"instancia_{$instancia->instancia}_p"})): ?>
											<?php echo '<span><i class="fa fa-check text-green"></i> ' . ($preinscripcion_turno->{"instancia_{$instancia->instancia}_p"}) . ' Alumnos Postulantes/Excedentes</span>'; ?>
										<?php endif; ?>
									<?php else: ?>
										<?php if ($instancia->deriva === 'Si'): ?>
											<?php echo '<span><i class="fa fa-times text-red"></i> -- Sin alumnos derivados -- </span>'; ?>
										<?php else: ?>
											<?php echo '<span><i class="fa fa-times text-red"></i> -- Sin alumnos inscriptos -- </span>'; ?>
										<?php endif; ?>
									<?php endif; ?>
								</td>

							<?php endforeach; ?>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			</table><br>
		<?php endforeach; ?>
	</div>
</div>
