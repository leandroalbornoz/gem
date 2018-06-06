
<div class="content-wrapper">
	<section class="content-header">
		<div role="tabpanel" class="tab-pane" id="tab_alumnos">
			<table class="table table-hover table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
				<tr>
					<td style="text-align: left;">
						<img style="font-family: sans-serif; font-size:20px; color: #3c8dbc;" alt="Logo DGE" src="<?php echo BASE_URL; ?>img/generales/logo-dge-sm.png" height="70" width="70">
					</td>
					<td style="text-align: center;font-size: 18px">Reporte de actualización de datos</td>
					<td style="text-align: right;">
						<img style="font-family: sans-serif; font-size:20px; color: #3c8dbc;" alt="Logo GEM" src="<?php echo BASE_URL; ?>img/generales/logo-login.png" height="70" width="70">
					</td>
				</tr>
			</table>

			<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
				<tr>
					<th style="text-align: center;width: 33%">Total de alumnos</th>
					<th style="text-align: center;width: 33%">Porcentaje con C.L Actualizado</th>
					<th style="text-align: center;width: 33%">Porcentaje con datos cargados</th>
				</tr>
				<?php $porcentaje_con_cl = substr($estadisticas_generales->porcentaje_con_cl, 0, 5); ?>
				<?php $porcentaje_con_datos = substr($estadisticas_generales->porcentaje_con_datos, 0, 5); ?>
				<tr>
					<td style="text-align: center;width: 33%;font-size: 22px"><?php echo $estadisticas_generales->total_alumnos; ?></td>
					<td style="text-align: center;width: 33%;font-size: 22px">
						<?php echo $porcentaje_con_cl * 100; ?>%
						<span>
							<?php if ($porcentaje_con_cl < '0.99'): ?>
								<img src="<?php echo BASE_URL; ?>img/generales/warning.png" height="18" width="18">
							<?php else: ?>
								<img src="<?php echo BASE_URL; ?>img/generales/ok.png" height="20" width="20">
							<?php endif; ?>
						</span>
					</td>
					<td style="text-align: center;width: 33%;font-size: 22px">
						<?php echo $porcentaje_con_datos * 100; ?>%
						<span>
							<?php if ($porcentaje_con_datos < '0.99'): ?>
								<img src="img/generales/warning.png" height="18" width="18">
							<?php else: ?>
								<img src="img/generales/ok.png" height="20" width="20">
							<?php endif; ?>
						</span>
					</td>
				</tr>
			</table>

			<div style="margin-top: 3%">
				<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
					<thead>
						<tr>
							<th colspan="5">N° y Nombre de la institución: <?php echo"$escuela->nombre_largo" ?></th>
						</tr>
						<tr>
							<th colspan="5">Fecha: <?php echo date('d/m/Y') ?></th>
						</tr>
						<tr>
							<th style="text-align: center;">Curso</th>
							<th style="text-align: center;">División</th>
							<th style="text-align: center;">Cantidad de alumnos</th>
							<th style="text-align: center;">Alumnos con C.L actualizado</th>
							<th style="text-align: center;">Alumnos con datos cargados</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($estadisticas)): ?>
							<?php foreach ($estadisticas as $estadistica): ?>
								<tr>
									<td><?php echo $estadistica->curso; ?></td>
									<td><?php echo $estadistica->division; ?></td>
									<td style="text-align: center"><?php echo $estadistica->total_alumnos; ?></td>
									<td style="text-align: center"><?php echo substr($estadistica->porcentaje_con_cl, 0, 5) * 100; ?>%</td>
									<td style="text-align: center"><?php echo substr($estadistica->porcentaje_con_datos, 0, 5) * 100; ?>%</td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="5" align="center">-- No se encontraron datos --</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>