<div class="content-wrapper">
	<section class="content-header">
		<div role="tabpanel" class="tab-pane" id="tab_alumnos">
			<div style="text-align: left;">
				<img src="img/generales/logo-dge-sm.png" height="70px" width="70px">
			</div>
			<div style="text-align: center;">
				<strong>ANEXO 1</strong>
			</div>
			<div style="margin-top: 3%">
				<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
					<thead>
						<tr>
							<th colspan="11" style="text-align: center; padding:20px 60px 20px 20px;">Planilla de Preinscripción - 1° GRADO - CICLO 2018</th>
						</tr>
						<tr>
							<th colspan="11">N° y Nombre de la institución: <?php echo"$escuela->nombre_largo"?></th>
						</tr>
						<tr>
							<th colspan="11">Sección de Supervisor: <?php echo"$escuela->supervision"?></th>
						</tr>
						<tr>
							<th colspan="11">Fecha: <?php echo date('d/m/Y')?> &nbsp; 1° Instancia</th>
						</tr>
						<tr>
							<th>Instancia</th>
							<th>Orden</th>
							<th>Apellido y Nombres</th>
							<th>Documento</th>
							<th>Tipo</th>
							<th>Fecha Nac.</th>
							<th>Sexo</th>
							<th>Dirección</th>
							<th>Padre/Madre/Tutor</th>
							<th>Teléfonos fijo - móvil</th>
							<th>Correo Electrónico</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($alumnos)): ?>
							<?php foreach ($alumnos as $orden => $alumno): ?>
								<?php if ($alumno->estado === 'Inscripto'): ?>
									<tr>
										<td><?=$alumno->instancia ;?>°</td>
										<td><?= $orden + 1; ?></td>
										<td><?= $alumno->persona; ?></td>
										<td><?= "$alumno->documento"; ?></td>
										<td><?= $alumno->preinscripcion_tipo; ?></td>
										<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
										<td><?= substr($alumno->sexo, 0, 1); ?></td>
										<td><?= $alumno->direccion; ?></td>
										<td><?= $alumno->familiares; ?></td>
										<td><?= $alumno->telefonos; ?> </td>
										<td><?= $alumno->email; ?></td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="10" align="center">-- No tiene alumnos en este anexo --</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>