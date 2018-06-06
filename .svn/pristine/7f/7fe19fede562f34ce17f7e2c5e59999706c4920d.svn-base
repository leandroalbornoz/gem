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
						<strong>Planilla de relevamiento de Extintores - <?php echo $relevamiento->extintor_relevamiento; ?></strong>
					</div>
					<div style="margin-top: 2%">
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
							<thead>
								<tr>
									<th colspan="7">N° y Nombre de la institución: <?php echo"$escuela->nombre_largo" ?></th>
								</tr>
								<tr>
									<th colspan="7">Extintores faltantes: <?php echo $tipo_impresion === '2' ? '__' : $relevamiento->extintores_faltantes; ?></th>
								</tr>
								<tr>
									<th colspan="7">Observaciones: <?php echo $tipo_impresion === '2' ? '_________________________________' : $relevamiento->observaciones; ?></th>
								</tr>
								<tr><th colspan="7"><span style="color:red;"><?php echo $tipo_impresion === '2' ? 'Puede utilizar esta planilla para relevar los datos de los extintores. Luego deberá realizar la carga en GEM' : 'No es necesario imprimir/entregar la planilla. Los datos serán obtenidos desde GEM por quien corresponda.'; ?></span></th></tr>
								<tr style="background-color: #e4e4e4;">
									<th colspan="7" style="text-align: center;"><?php echo $tipo_impresion === '2' ? '' : (empty($extintores) ? '0' : count($extintores)); ?> Extintores</th>
								</tr>
								<?php if ($tipo_impresion === '2'): ?>
									<tr>
										<th colspan="7"><span style="color:red;">*</span> Campo obligatorio</th>
									</tr>
								<?php endif; ?>
								<tr>
									<th style="width: 13%;">N° Registro <?php echo $tipo_impresion === '2' ? '<span style="color:red;">*</span>' : '' ?></th>
									<th style="width: 11%;">1° Carga <?php echo $tipo_impresion === '2' ? '<span style="color:red;">*</span>' : '' ?></th>
									<th style="width: 16%;">Vencimiento  <?php echo $tipo_impresion === '2' ? '<span style="color:red;">*</span>' : '' ?></th>
									<th style="width: 10%;">Kilos  <?php echo $tipo_impresion === '2' ? '<span style="color:red;">*</span>' : '' ?></th>
									<th style="width: 15%;">Tipo Extintor <?php echo $tipo_impresion === '2' ? '<span style="color:red;">*</span>' : '' ?></th>
									<th style="width: 20%;">Empresa</th>
									<th style="width: 15%;">Marca</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($tipo_impresion === '2'): ?>
									<?php for ($i = 0; $i < 25; $i++): ?>
										<tr>
											<td><?= $i+1; ?>__________</td>
											<td>__/__/____</td>
											<td>__/__/____</td>
											<td>______</td>
											<td>____________</td>
											<td>_______________</td>
											<td>____________</td>
										</tr>
									<?php endfor; ?>
								<?php else: ?>
									<?php if (!empty($extintores)): ?>
										<?php foreach ($extintores as $orden => $extintor): ?>
											<tr>
												<td><?php echo $extintor->numero_registro; ?></td>
												<td><?php echo empty($extintor->primer_carga) ? '' : (new DateTime($extintor->primer_carga))->format('d/m/Y'); ?></td>
												<td><?php echo empty($extintor->vencimiento) ? '' : (new DateTime($extintor->vencimiento))->format('d/m/Y'); ?></td>
												<td><?php echo $extintor->kilos; ?></td>
												<td><?php echo $extintor->tipo_extintor; ?></td>
												<td><?php echo $extintor->empresa_instalacion; ?></td>
												<td><?php echo $extintor->marca; ?></td>
											</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<td colspan="7" style="text-align: center;">-- No hay extintores cargados --</td>
										</tr>
									<?php endif; ?>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
	</body>
</html>