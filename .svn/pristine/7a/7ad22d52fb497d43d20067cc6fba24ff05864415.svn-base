<div class="content-wrapper">
	<section class="content-header">
		<div role="tabpanel" class="tab-pane" id="tab_alumnos">
			<div style="text-align: left;">
				<img src="img/generales/logo-dge-sm.png" height="70px" width="70px">
			</div>
			<div style="text-align: center;">
				<strong>ANEXO IV</strong><br>
				<strong><?php echo $supervision->nombre ;?></strong>
			</div>
			<div style="margin-top: 3%">
				<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
					<thead>
						<tr>
							<th style="text-align: center;" rowspan="2">Orden</th>
							<th style="text-align: center;" rowspan="2">Escuela</th>
							<th style="text-align: center;" colspan="2">Matrícula 2017</th>
							<th style="text-align: center;" rowspan="2">Vacantes Declaradas</th>
							<th style="text-align: center;" rowspan="2">Vacantes Disponibles</th>
							<th style="text-align: center;" rowspan="2">Excedentes</th>
							<th style="text-align: center;" rowspan="2">Alumnos</th>
							<th style="text-align: center;" rowspan="2">Estado</th>
							<th style="text-align: center;" rowspan="2">Destino</th>
						</tr>
						<tr>
							<th>Divisiones</th>
							<th>Alumnos</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($escuelas)): ?>
							<?php foreach ($escuelas as $orden => $escuela): ?>
									<tr>
										<td><?= $orden + 1; ?></td>
										<td><?= $escuela->escuela ; ?></td>
										<td><?= $escuela->divisiones_2017 ?></td>
										<td><?= $escuela->alumnos_2017 ?></td>
										<td><?= empty($escuela->vacantes)? 'Sin cargar':$escuela->vacantes ; ?></td>
										<td><?= empty($escuela->vacantes)? 'Sin cargar':($escuela->vacantes - $escuela->inscriptos); ?></td>
										<td><?= $escuela->postulantes; ?></td>
										<td><?= $escuela->alumno ;?></td>
										<td><?= $escuela->estado; ?></td>
										<td><?= $escuela->escuela_derivada; ?></td>
									</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="4" align="center">-- No hay escuelas en este anexo --</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>