<html lang="es">
	<head>
		<base href="<?php echo base_url(); ?>" />
		<meta charset="utf-8">
	</head>
	<body>
		<div class="content-wrapper">
			<section class="content-header">
				<div role="tabpanel" class="tab-pane" id="tab_alumnos">
					<div style="text-align: left;">
						<img src="img/generales/logo-dge-sm.png" height="70px" width="70px">
					</div>
					<div style="text-align: center;">
						<strong>Planilla de celadores para desinfección de escuelas - Elecciones <?php echo $desinfeccion->eleccion; ?></strong>
					</div>
					<div style="margin-top: 3%">
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
							<thead>
								<tr>
									<th colspan="5">N° y Nombre de la institución: <?php echo"$escuela->nombre_largo" ?></th>
								</tr>
								<tr>
									<th colspan="5">Fecha actual: <?php echo date('d/m/Y') ?></th>
								</tr>
								<tr>
									<th colspan="5">Fecha de cierre: <?php echo (new DateTime($desinfeccion->fecha_cierre))->format('d/m/Y'); ?></th>
								</tr>
								<tr>
									<th style="width: 10%;">Cuil</th>
									<th style="width: 50%;">Apellido y Nombres</th>
									<th style="width: 15%;">Cargado</th>
									<th style="width: 10%;">Estado</th>
									<th style="width: 15%;">Anulado</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($celadores)): ?>
									<?php foreach ($celadores as $orden => $celador): ?>
										<tr>
											<td><?php echo $celador->cuil; ?></td>
											<td><?php echo "$celador->apellido $celador->nombre"; ?></td>
											<td><?php echo (new DateTime($celador->fecha_carga))->format('d/m/y H:i'); ?></td>
											<td><?php echo $celador->estado; ?></td>
											<td><?php echo empty($celador->fecha_anulacion) ? '' : (new DateTime($celador->fecha_anulacion))->format('d/m/y H:i'); ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="5" align="center">-- No se asignaron celadores --</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
	</body>
</html>