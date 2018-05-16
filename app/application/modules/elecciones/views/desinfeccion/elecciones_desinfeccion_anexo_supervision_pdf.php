<div class="content-wrapper">
	<section class="content-header">
		<div role="tabpanel" class="tab-pane" id="tab_alumnos">
			<div style="text-align: left;">
				<img src="img/generales/logo-dge-sm.png" height="70px" width="70px">
			</div>
			<div style="text-align: center;">
				<strong>ANEXO</strong>
			</div>
			<div style="margin-top: 3%">
				<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
					<thead>
						<tr>
							<th colspan="5" style="text-align: center; padding:20px 60px 20px 20px;">Planilla de escuelas para desinfección por elecciones</th>
						</tr>
						<tr>
							<th colspan="5">Fecha actual: <?php echo date('d/m/Y') ?></th>
						</tr>
						<tr>
							<th>Escuela</th>
							<th>Numero de mesas</th>
							<th>Celadores permitidos</th>
							<th>Celadores asignados</th>
							<th>Fecha de cierre</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($escuelas)): ?>
							<?php foreach ($escuelas as $escuela): ?>
								<tr>
									<td style="width: 50%;"><?php echo "$escuela->escuela"; ?></td>
									<td style="width: 10%;"><?php echo $escuela->mesas; ?></td>
									<td style="width: 10%;"><?php echo "$escuela->celadores_permitidos"; ?></td>
									<td style="width: 10%;"><?php echo $escuela->celadores_asignados; ?></td>
									<td style="width: 10%;"><?php echo isset($escuela->fecha_cierre) ? (new DateTime($escuela->fecha_cierre))->format('d/m/Y') : 'No cerrado'; ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="10" align="center">-- No hay escuelas con desinfeccion asignada --</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>