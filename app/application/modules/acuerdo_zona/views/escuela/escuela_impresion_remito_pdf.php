<html lang="es">
	<head>
		<base href="<?php echo base_url(); ?>" />
		<meta charset="utf-8">
	</head>
	<body>
		<div class="content-wrapper">
			<section class="content-header">
				<table style="margin-left:12%;width:80%;">
					<tr>
						<td width="20%" style="text-align: left;"><img src="img/logomza.jpg"></td>
						<td width="60%" style="text-align: center; padding-left:20px;">
							<h3>Gobierno de Mendoza</h3>
							<h4>Dirección General de Escuelas</h4>
						</td>
						<td width="20%" style="text-align: right;"><img src="img/generales/logo-dge-sm.jpg" height="50"></td>
					</tr>
				</table>
				<div style="text-align: center;">
					<strong>Remito de entrega de Acuerdos Zona - <?php echo "Esc. $escuela->nombre_corto - Remtio N° $remito->numero"; ?></strong>
				</div>
				<div style="margin-top: 2%">
					<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
						<thead>
							<tr>
								<th style="width:20%;">Fecha</th>
								<th style="width:20%;">CUIL</th>
								<th style="width:30%;">Persona</th>
								<th style="width:15%;">Conformidad</th>
								<th style="width:15%;">Jubilado/<br/>Asig. Fliar</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($recepciones as $recepcion): ?>
								<tr>
									<td><?= (new DateTime($recepcion->fecha))->format('d/m/Y H:i'); ?></td>
									<td><?= $recepcion->cuil; ?></td>
									<td><?= $recepcion->nombre; ?></td>
									<td><?= $recepcion->estado; ?></td>
									<td><?= $recepcion->jubilado; ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</body>
</html>