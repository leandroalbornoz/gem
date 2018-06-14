<?php if (!empty($preinscripciones)): ?>
	<div class="box <?= $modulo_activo ? 'box-warning' : 'box-primary collapsed-box'; ?>">
		<div class="box-header with-border">
			<h3 class="box-title">Preinscripciones - Ciclo Lectivo <?= $ciclo_lectivo; ?></h3>
			&nbsp;
			<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/modal_consultar_estado/<?= $ciclo_lectivo ?>"><i class="fa fa-search"></i> Consulta de Alumnos</a>
			<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/modal_vacantes_generales/<?= $ciclo_lectivo ?>"><i class="fa fa-search"></i> Consulta de Escuelas con Vacantes</a>
			<div class="box-tools pull-right">
				<!--<a class="btn-sm btn-success" href="preinscripciones/preinscripcion_alumno/excel/<?= $nivel->id ?>/<?= $nivel->supervisiones[0]->dependencia_id ?>/<?= $escuelas_p[0]->id ?>" title="Exportar excel">-->
					<!--<i class="fa fa-file-excel-o" id="btn-escuelas"></i> Exportar Excel</a>-->
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa <?= $modulo_activo ? 'fa-minus' : 'fa-plus' ?>"></i></button>
			</div>
		</div>
		<div class="box-body ">
			<?php foreach ($preinscripciones as $preinscripcion_operativo_id => $escuelas_p): ?>
				<table class="table table-bordered table-condensed table-striped table-hover">
					<?php $fecha = date('Y-m-d'); ?>
					<thead>
						<tr style="background: #f9f9f9;">
							<th rowspan="2" style="text-align:center;">Supervisión</th>
							<th rowspan="2" style="text-align:center;">Escuelas</th>
							<th colspan="2" style="text-align:center;"><?= $cursos[$preinscripcion_operativo_id] . ' ' . ($ciclo_lectivo - 1); ?></th>
							<th colspan="8" style="text-align:center;">Preinscripción <?= $ciclo_lectivo; ?></th>
							<th style="text-align:right;"></th>
						</tr>
						<tr>
							<th style="text-align:center;">Divisiones</th>
							<th style="text-align:center;">Alumnos</th>
							<th style="text-align:center;">Vacantes declaradas<br>(<?php echo(new DateTime($preinscripcion_instancias[$preinscripcion_operativo_id][0]->desde))->format('d/m'); ?> - <?php echo(new DateTime($preinscripcion_instancias[$preinscripcion_operativo_id][0]->hasta))->format('d/m'); ?>)</th>
							<th style="text-align:center;">Inscriptos</th>
							<th style="text-align:center;">Vacantes<br>finales</th>
							<th style="text-align:center;">1° Inst.<br>(<?php echo(new DateTime($preinscripcion_instancias[$preinscripcion_operativo_id][1]->desde))->format('d/m'); ?> - <?php echo(new DateTime($preinscripcion_instancias[$preinscripcion_operativo_id][1]->hasta))->format('d/m'); ?>)</th>
							<th style="text-align:center;">2° Inst.<br>(<?php echo(new DateTime($preinscripcion_instancias[$preinscripcion_operativo_id][2]->desde))->format('d/m'); ?> - <?php echo(new DateTime($preinscripcion_instancias[$preinscripcion_operativo_id][2]->hasta))->format('d/m'); ?>)</th>
							<th style="text-align:center;">3° Inst.<br>(<?php echo(new DateTime($preinscripcion_instancias[$preinscripcion_operativo_id][3]->desde))->format('d/m'); ?> - <?php echo(new DateTime($preinscripcion_instancias[$preinscripcion_operativo_id][3]->hasta))->format('d/m'); ?>)</th>
							<th style="text-align:center;">4° Inst.<br>(<?php echo(new DateTime($preinscripcion_instancias[$preinscripcion_operativo_id][4]->desde))->format('d/m'); ?> - <?php echo(new DateTime($preinscripcion_instancias[$preinscripcion_operativo_id][4]->hasta))->format('d/m'); ?>)</th>
							<th style="text-align:center;">Excedentes/<br>Derivados</th>
							<th></th>
						</tr>
					</thead>
					<?php $total_escuelas = 0; ?>
					<?php $total_divisiones = 0; ?>
					<?php $total_alumnos = 0; ?>
					<?php $total_vacantes = 0; ?>
					<?php $total_inscriptos = 0; ?>
					<?php $total_vacantes_finales = 0; ?>
					<?php $total_instancia1 = 0; ?>
					<?php $total_instancia2 = 0; ?>
					<?php $total_instancia3 = 0; ?>
					<?php $total_instancia4 = 0; ?>
					<?php $total_postulantes = 0; ?>
					<?php $total_derivados = 0; ?>
					<tbody>
						<?php foreach ($escuelas_p as $escuela): ?>
							<tr>
								<td style=""><?= $escuela->nombre; ?></td>
								<td style="" class="text-center"><?= $escuela->escuelas; ?></td>
								<?php $total_escuelas += $escuela->escuelas; ?>
								<td style="" class="text-center"><?= $escuela->divisiones; ?></td>
								<?php $total_divisiones += $escuela->divisiones; ?>
								<td style="" class="text-center"><?= $escuela->alumnos; ?></td>
								<?php $total_alumnos += $escuela->alumnos; ?>
								<td style="" class="text-center">
									<?php // if ($fecha >= $preinscripcion_instancias[0]->desde): ?>
									<?= "$escuela->vacantes ($escuela->escuelas_p esc.)"; ?>
									<?php // endif; ?>
									<?php $total_vacantes += $escuela->vacantes; ?>
								</td>
								<td style="" class="text-center"><?php echo!empty($escuela->inscriptos) ? $escuela->inscriptos : 0; ?></td>
								<?php $total_inscriptos += $escuela->inscriptos; ?>
								<td style="" class="text-center"><?= $escuela->vacantes - $escuela->inscriptos; ?></td>
								<?php $total_vacantes_finales += $escuela->vacantes - $escuela->inscriptos; ?>
								<td style="" class="text-center">
									<?php if ($fecha >= $preinscripcion_instancias[$preinscripcion_operativo_id][1]->desde): ?>
										<?php if ($fecha <= $preinscripcion_instancias[$preinscripcion_operativo_id][1]->hasta): ?>
											<?php echo!empty($escuela->instancia_1) ? $escuela->instancia_1 : 0; ?>
															<!--<a class="pull-right" href="preinscripciones/escuela/instancia/1/<?= $escuela->preinscripcion_id; ?>/supervision"><i class="fa fa-edit"></i></a>-->
										<?php else: ?>
											<?php echo!empty($escuela->instancia_1) ? $escuela->instancia_1 : 0; ?>
															<!--<a class="pull-right" href="preinscripciones/escuela/instancia/1/<?= $escuela->preinscripcion_id; ?>/supervision"><i class="fa fa-search"></i></a>-->
										<?php endif; ?>
									<?php endif; ?>
								</td>
								<?php $total_instancia1 += $escuela->instancia_1; ?>
								<td style="" class="text-center">
									<?php if ($fecha >= $preinscripcion_instancias[$preinscripcion_operativo_id][2]->desde): ?>
										<?php if ($fecha <= $preinscripcion_instancias[$preinscripcion_operativo_id][2]->hasta): ?>
											<?php echo!empty($escuela->instancia_2_i) ? $escuela->instancia_2_i : 0; ?>
															<!--<a class="pull-right" href="preinscripciones/escuela/instancia/2/<?= $escuela->preinscripcion_id; ?>/supervision"><i class="fa fa-edit"></i></a>-->
										<?php else: ?>
											<?php echo!empty($escuela->instancia_2_i) ? $escuela->instancia_2_i : 0; ?>
															<!--<a class="pull-right" href="preinscripciones/escuela/instancia/2/<?= $escuela->preinscripcion_id; ?>/supervision"><i class="fa fa-edit"></i></a>-->
										<?php endif; ?>
									<?php endif; ?>
								</td>
								<?php $total_instancia2 += $escuela->instancia_2_i; ?>
								<td style="" class="text-center">
									<?php if ($fecha >= $preinscripcion_instancias[$preinscripcion_operativo_id][3]->desde): ?>
										<?php if ($fecha <= $preinscripcion_instancias[$preinscripcion_operativo_id][3]->hasta): ?>
											<?php echo!empty($escuela->instancia_3) ? $escuela->instancia_3 : 0 - !empty($escuela->instancia_3_d) ? $escuela->instancia_3_d : 0; ?>
															<!--<a class="pull-right" href="preinscripciones/escuela/instancia/3/<?= $escuela->preinscripcion_id; ?>/supervision"><i class="fa fa-edit"></i></a>-->
										<?php else: ?>
											<?php echo!empty($escuela->instancia_3) ? $escuela->instancia_3 : 0 - !empty($escuela->instancia_3_d) ? $escuela->instancia_3_d : 0; ?>
															<!--<a class="pull-right" href="preinscripciones/escuela/instancia/3/<?= $escuela->preinscripcion_id; ?>/supervision"><i class="fa fa-edit"></i></a>-->
										<?php endif; ?>
									<?php endif; ?>
								</td>
								<?php $total_instancia3 += $escuela->instancia_3 - $escuela->instancia_3_d; ?>
								<td style="" class="text-center">
									<?php if ($fecha >= $preinscripcion_instancias[$preinscripcion_operativo_id][4]->desde): ?>
										<?php if ($fecha <= $preinscripcion_instancias[$preinscripcion_operativo_id][4]->hasta): ?>
											<?php echo!empty($escuela->instancia_4) ? $escuela->instancia_4 : 0; ?>
															<!--<a class="pull-right" href="preinscripciones/escuela/instancia/4/<?= $escuela->preinscripcion_id; ?>/supervision"><i class="fa fa-edit"></i></a>-->
										<?php else: ?>
											<?php echo!empty($escuela->instancia_4) ? $escuela->instancia_4 : 0; ?>
															<!--<a class="pull-right" href="preinscripciones/escuela/instancia/4/<?= $escuela->preinscripcion_id; ?>/supervision"><i class="fa fa-edit"></i></a>-->
										<?php endif; ?>
									<?php endif; ?>
								</td>
								<?php $total_instancia4 += $escuela->instancia_4; ?>
								<td style="" class="text-center">
									<?php echo!empty($escuela->instancia_2_p) ? $escuela->instancia_2_p : 0; ?>/<?php echo!empty($escuela->instancia_3_d) ? $escuela->instancia_3_d : 0; ?>
								</td>
								<?php $total_postulantes += !empty($escuela->instancia_2_p) ? $escuela->instancia_2_p : 0; ?>
								<?php $total_derivados += !empty($escuela->instancia_3_d) ? $escuela->instancia_3_d : 0; ?>
								<td>
									<a class="btn btn-xs" href="supervision/escritorio/<?= $escuela->id; ?>"><i class="fa fa-home"></i></a>
								</td>
							</tr>
						<?php endforeach; ?>
						<tr style="background-color:#3c8dbc;color: white">
							<td>TOTAL</td>
							<td class="text-center"><?= number_format($total_escuelas, 0, ',', '.'); ?></td>
							<td class="text-center"><?= number_format($total_divisiones, 0, ',', '.'); ?></td>
							<td class="text-center"><?= number_format($total_alumnos, 0, ',', '.'); ?></td>
							<td class="text-center"><?= number_format($total_vacantes, 0, ',', '.'); ?></td>
							<td class="text-center"><?= number_format($total_inscriptos, 0, ',', '.'); ?></td>
							<td class="text-center"><?= number_format($total_vacantes_finales, 0, ',', '.'); ?></td>
							<td class="text-center"><?= number_format($total_instancia1, 0, ',', '.'); ?></td>
							<td class="text-center"><?= number_format($total_instancia2, 0, ',', '.'); ?></td>
							<td class="text-center"><?= number_format($total_instancia3, 0, ',', '.'); ?></td>
							<td class="text-center"><?= number_format($total_instancia4, 0, ',', '.'); ?></td>
							<td class="text-center"><?= number_format($total_postulantes, 0, ',', '.'); ?>/<?= $total_derivados; ?></td>
							<td class="text-center"></td>
						</tr>
					</tbody>
				</table>
				<br>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>