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
						<strong>Planilla de Resumen del Operativo Aprender 2017 - <?php echo $operativo->operativo_tipo;?></strong>
					</div>
					<div style="margin-top: 3%">
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
							<thead>
								<tr>
									<th colspan="4">N° y Nombre de la institución: <?php echo"$escuela->nombre_largo" ?></th>
								</tr>
								<tr>
									<th colspan="4">Fecha actual: <?php echo date('d/m/Y') ?></th>
								</tr>
								<tr>
									<th colspan="4">Fecha de cierre: <?php echo (new DateTime($operativo->fecha_cierre))->format('d/m/Y'); ?></th>
								</tr>
								<tr>
									<th style="width: 10%;">Cuil</th>
									<th style="width: 30%;">Apellido y Nombres</th>
									<th style="width: 30%;">Telefonos</th>
									<th style="width: 30%;">Email</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($aplicadores)): ?>
									<?php foreach ($aplicadores as $orden => $aplicador): ?>
										<tr>
											<td><?php echo $aplicador->cuil; ?></td>
											<td><?php echo "$aplicador->apellido $aplicador->nombre"; ?></td>
											<td><?php echo "$aplicador->telefono_fijo/$aplicador->telefono_movil"; ?></td>
											<td><?php echo "$aplicador->email"; ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="10" align="center">-- No se cargaron aplicadores en el operativo --</td>
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