<div class="content-wrapper">
	<section class="content-header">
		<div role="tabpanel" class="tab-pane" id="tab_alumnos">
			<div style="text-align: left;">
				<img src="img/generales/logo-dge-sm.png" height="70px" width="70px">
			</div>
			<div style="text-align: center;">
				<strong>ANEXO 3</strong>
			</div>
			<div style="margin-top: 3%">
				<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
					<thead>
						<tr>
							<th colspan="8" style="text-align: center; padding:10px 50px 20px 20px;">ACTA VOLANTE DE EXCEDENTES DE ALUMNOS Y DE DERIVACIÓN DE LOS MISMOS</th>
						</tr>
						<tr>
							<th colspan="8">
								En el día de la fecha, en la Sede de Supervisión sección N°: <?php echo isset($escuela->supervision) ? "$escuela->supervision" : '..........'; ?>, durante la reunión de reubicación de matricula excedente, la<br> Escuela N°: <?php echo "$escuela->numero"; ?>, Nombre: <?php echo "$escuela->nombre"; ?>, deriva a los siguientes niños/as a la Escuela que figura en planilla.
							</th>
						</tr>
						<tr>
							<th>N° Orden</th>
							<th>Apellido y Nombre del Niño/a</th>
							<th>Documento</th>
							<th>Dirección</th>
							<th>Teléfonos fijo - móvil</th>
							<th>Correo Electrónico</th>
							<th>N° de Escuela que recepciona</th>
							<th>Notificación de Padre, Madre o Tutor/a</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($alumnos)): ?>
							<?php foreach ($alumnos as $orden => $alumno): ?>
								<?php if ($alumno->estado === 'Postulante' || $alumno->estado === 'Derivado'): ?>
									<tr>
										<td><?= $orden + 1; ?></td>
										<td><?= $alumno->persona; ?></td>
										<td><?= "$alumno->documento"; ?></td>
										<td><?= $alumno->direccion; ?></td>
										<td><?= $alumno->telefonos; ?> </td>
										<td><?= $alumno->email; ?></td>
										<td><?= empty($alumno->escuela_derivada) ? "" : "$alumno->escuela_derivada" ; ?></td>
										<td></td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="8" align="center">-- No tiene alumnos en este anexo --</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<div class="row">
				<div style="float:left; width:350px; margin-left:50px;">Firma del Director/a que recibe</div>
				<div style="float:left; width:280px;">Sello de la Escuela</div>
				<div style="float:left; width:380px;">Firma del Director/a que deriva</div>
				<div style="float:left; width:250px;">Sello de la Escuela<br> Mendoza,............de.................de 2017</div>
			</div>
		</div>
	</section>
</div>