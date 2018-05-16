<?php if ($escuela->nivel_id === '2' && $escuela->dependencia_id === '1'): ?>
	<div class="box box-warning">
		<div class="box-header with-border">
			<h3 class="box-title">Ingreso a 1° Grado - Ciclo Lectivo 2018</h3>
			&nbsp;
			<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/modal_consultar_estado"><i class="fa fa-search"></i> Consulta de Alumnos</a>
			<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/modal_vacantes_generales"><i class="fa fa-search"></i> Consulta de Escuelas con Vacantes</a>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<table class="table table-condensed table-bordered table-striped table-hover">
				<tr>
					<th colspan="5" style="text-align:center;">Calendario</th>
				</tr>
				<tr>
					<th>Instancia</th>
					<th>Desde</th>
					<th>Hasta</th>
					<th>Estado</th>
					<th>
					</th>
				</tr>
				<?php $fecha = date('Y-m-d'); ?>
				<tr>
					<td><?php echo $preinscripcion_instancias[0]->descripcion; ?></td>
					<td><?php echo (new DateTime($preinscripcion_instancias[0]->desde))->format('d/m/Y'); ?></td>
					<td><?php echo (new DateTime($preinscripcion_instancias[0]->hasta))->format('d/m/Y'); ?></td>
					<td>
						<?php
						if (isset($preinscripcion)) {
							echo '<span><i class="fa fa-check text-green"></i> ' . $preinscripcion->vacantes . ' Vacantes declaradas</span>';
						} else {
							echo '<span><i class="fa fa-times text-red"></i> -- Falta declarar vacantes -- </span>';
						}
						?>
					</td>
					<?php if ($administrar): ?>
						<?php if ($fecha >= $preinscripcion_instancias[0]->desde): ?>
							<?php if (empty($preinscripcion->id)): ?>
								<td>
									<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/<?php echo (!empty($preinscripcion->id)) ? 'editar_vacantes/' . $preinscripcion->id : 'instancia_0/2018/' . $escuela->id; ?>/escuela"><i class="fa fa-edit"></i> <?php echo (!empty($preinscripcion->id)) ? 'Editar' : 'Cargar'; ?> vacantes</a>
								</td>
							<?php elseif ($fecha <= $preinscripcion_instancias[0]->hasta): ?>
								<td>
									<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/<?php echo (!empty($preinscripcion->id)) ? 'editar_vacantes/' . $preinscripcion->id : 'instancia_0/2018/' . $escuela->id; ?>/escuela"><i class="fa fa-edit"></i> <?php echo (!empty($preinscripcion->id)) ? 'Editar' : 'Cargar'; ?> vacantes</a>
								</td>
							<?php else: ?>
								<td>
									<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/<?php echo (!empty($preinscripcion->id)) ? 'ver_vacantes/' . $preinscripcion->id : '' ?>"><i class="fa fa-search"></i> Ver vacantes</a>
								</td>
							<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
				</tr>
				<?php
				for ($i = 1; $i < count($preinscripcion_instancias); $i++):
					?>
					<?php $instancia = $preinscripcion_instancias[$i]; ?>
					<tr>
						<td><?php
							echo $instancia->descripcion;
							switch ($instancia->instancia) {
								case '1': echo " - Inscripción Directa";
									break;
								case '2': echo " - Nuevos Aspirantes";
									break;
								case '3': echo " - Derivación de Alumnos";
									break;
								case '4': echo " - Casos Particulares";
									break;
							}
							?></td>
						<td><?php echo (new DateTime($instancia->desde))->format('d/m/Y'); ?></td>
						<td><?php echo (new DateTime($instancia->hasta))->format('d/m/Y'); ?></td>
						<td>
							<?php
							switch ($instancia->instancia) {
								case '1':
									if (isset($preinscripcion) && $preinscripcion->instancia_1 !== '0') {
										echo '<span><i class="fa fa-check text-green"></i> ' . $preinscripcion->instancia_1 . ' Alumnos inscriptos</span><br/>';
										echo '<span><i class="fa fa-check text-green"></i> ' . ($preinscripcion->vacantes - $preinscripcion->instancia_1) . ' Vacantes disponibles</span>';
									} else {
										echo '<span><i class="fa fa-times text-red"></i> -- Sin alumnos inscriptos -- </span>';
									}
									break;
								case '2':
									if (isset($preinscripcion) && ($preinscripcion->instancia_2_i !== '0' || ($preinscripcion->vacantes - $preinscripcion->instancia_1) === 0)) {
										echo '<span><i class="fa fa-check text-green"></i> ' . $preinscripcion->instancia_2_i . ' Alumnos inscriptos</span><br/>';
										echo '<span><i class="fa fa-check text-green"></i> ' . ($preinscripcion->vacantes - $preinscripcion->instancia_1 - $preinscripcion->instancia_2_i) . ' Vacantes disponibles</span><br>';
										echo '<span><i class="fa fa-check text-green"></i> ' . ($preinscripcion->instancia_2_p + (isset($preinscripcion->instancia_3_d) ? $preinscripcion->instancia_3_d : 0)) . ' Alumnos Postulantes/Excedentes</span>';
									} else {
										echo '<span><i class="fa fa-times text-red"></i> -- Sin alumnos inscriptos -- </span>';
									}
									break;
								case '3':
									if (isset($preinscripcion) && ($preinscripcion->instancia_3_d > 0)) {
										echo '<span><i class="fa fa-check text-green"></i> ' . $preinscripcion->instancia_3_d . ' Postulantes Derivados</span><br/>';
									} elseif (isset($preinscripcion->instancia_3_i) && ($preinscripcion->instancia_3_i) > 0) {
										echo '<span><i class="fa fa-check text-green"></i> ' . $preinscripcion->instancia_3_i . ' Alumnos Recibidos</span><br/>';
										echo '<span><i class="fa fa-check text-green"></i> ' . ($preinscripcion->vacantes - $preinscripcion->instancia_1 - $preinscripcion->instancia_2_i - $preinscripcion->instancia_3_i ) . ' Vacantes disponibles</span><br>';
									} else {
										echo '<span><i class="fa fa-times text-red"></i> -- Sin alumnos derivados -- </span>';
									}
									break;
								case '4':
									if (isset($preinscripcion) && ($preinscripcion->instancia_4 !== '0' || ($preinscripcion->vacantes - $preinscripcion->instancia_1 - $preinscripcion->instancia_2_i - $preinscripcion->instancia_3_i) === 0)) {
										echo '<span><i class="fa fa-check text-green"></i> ' . $preinscripcion->instancia_4 . ' Alumnos inscriptos</span><br/>';
										echo '<span><i class="fa fa-check text-green"></i> ' . ($preinscripcion->vacantes - $preinscripcion->instancia_1 - $preinscripcion->instancia_2_i - $preinscripcion->instancia_3_i - $preinscripcion->instancia_4) . ' Vacantes disponibles</span><br>';
									} else {
										echo '<span><i class="fa fa-times text-red"></i> -- Sin alumnos inscriptos -- </span>';
									}
									break;
							}
							?>
						</td>
						<?php if ($administrar && $fecha >= $instancia->desde): ?>
							<?php if (!isset($preinscripcion->id)): ?>
								<td></td>
							<?php elseif ($fecha <= $instancia->hasta): ?>
								<td><a class="btn btn-primary btn-xs" href="preinscripciones/escuela/instancia_<?php echo "$instancia->instancia/$instancia->ciclo_lectivo/$escuela->id"; ?>/escuela"><i class="fa fa-edit"></i> Editar instancia <?php echo $instancia->instancia; ?></a></td>
							<?php else: ?>
								<td>
									<a class="btn btn-primary btn-xs" href="preinscripciones/escuela/instancia_<?php echo "$instancia->instancia/$instancia->ciclo_lectivo/$escuela->id"; ?>/escuela"><i class="fa fa-search"></i> Ver instancia <?php echo $instancia->instancia; ?></a>
								</td>
							<?php endif; ?>
						<?php else: ?>
							<td></td>
						<?php endif; ?>
					</tr>
				<?php endfor; ?>
			</table>
		</div>
	</div>
<?php endif; ?>