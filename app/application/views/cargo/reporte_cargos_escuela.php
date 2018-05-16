<!DOCTYPE html>
<html lang="es">
	<head>
		<base href="<?php echo base_url(); ?>" />
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php echo empty($title) ? TITLE : $title; ?></title>
		<link rel="stylesheet" href="plugins/kv-mpdf-bootstrap.min.css" />
		<link rel="stylesheet" href="css/mpdf/reporte_cargos_escuela_pdf.css" />
	</head>
	<body>
		<div class="content-wrapper">
			<section class="content-header">
				<div role="tabpanel" class="tab-pane" id="tab_cargos">
					<table class="table table-hover table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
						<tr>
							<td style="text-align: left;">
								<img style="font-family: sans-serif; font-size:20px; color: #3c8dbc;" alt="Logo DGE" src="<?= BASE_URL; ?>img/generales/logo-dge-sm.png" height="70" width="70">
							</td>
							<td style="text-align: center;font-size: 18px"><u><b>Reporte de cargos</b></u></td>
							<td style="text-align: right;">
								<img style="font-family: sans-serif; font-size:20px; color: #3c8dbc;" alt="Logo GEM" src="<?= BASE_URL; ?>img/generales/logo-login.png" height="70" width="70">
							</td>
						</tr>
					</table>
					<h5><u><b>N° y Nombre de la institución</b></u>: <?php echo"$escuela->nombre_largo"; ?></h5>
					<h5><u><b>Fecha</b></u>:<?php echo date('d/m/Y'); ?></h5>
					<div id="table" style="">
						<?php if (!empty($cargos)): ?>
							<?php foreach ($cargos as $division_carrera => $cargos_division): ?>
								<div class="div_carrera">
									<?php echo $division_carrera; ?>&nbsp;&rarr;&nbsp;<?php echo count($cargos_division) ?>&nbsp;Cargos
								</div>
								<?php foreach ($cargos_division as $cargo): ?>
									<div class="div_cargo">
										<div class="div_cargo_head">
											<table class="table-responsive table-head">
												<tr>
													<td style="width:6%"><b>Id:</b><?= $cargo[0]->cargo_id; ?></td>
													<td style="width:8%"><b>Cond:</b><?= (empty($cargo[0]->condicion)) ? ' -' : $cargo[0]->condicion; ?></td>
													<td style="width:22%"><b>Rég:</b><?= (empty($cargo[0]->codigo)) ? ' -' : $cargo[0]->codigo . ' ' . $cargo[0]->regimen; ?></td>
													<td style="width:5%"><b>Hs:</b><?= ($cargo[0]->carga_horaria === '0') ? ' -' : $cargo[0]->carga_horaria; ?></td>
													<td style="width:10%"><b>Turno:</b><?= (empty($cargo[0]->turno)) ? ' -' : $cargo[0]->turno; ?></td>
													<td style="width:10%"><b>Div:</b><?= (empty($cargo[0]->division)) ? ' -' : $cargo[0]->division; ?></td>
													<td style="width:28%"><b>Mat:</b><?= ($cargo[0]->cuatrimestre === '0' || empty($cargo[0]->cuatrimestre)) ? '' : "(" . $cargo[0]->cuatrimestre . "°C)"; ?><?= ((empty($cargo[0]->materia)) ? ' -' : $cargo[0]->materia); ?></td>
												</tr>
											</table>
										</div>
										<div>
											<table class="table-responsive">
												<thead>
													<tr>
														<th width="80px">Revista</th>
														<th>Alta</th>
														<th>Baja</th>
														<th>Cuil</th>
														<th>Apellido y Nombre</th>
														<th>Función</th>
														<th>Destino</th>
														<th>Norma</th>
														<th>Tarea</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($cargo as $servicio): ?>
														<tr>
															<td><?= (!empty($servicio->revista)) ? $servicio->revista : ' - '; ?>.</td>
															<td><?= (isset($servicio->fecha_alta)) ? (new DateTime($servicio->fecha_alta))->format('d/m/y') : '-'; ?></td>
															<td><?= (isset($servicio->fecha_baja)) ? (new DateTime($servicio->fecha_baja))->format('d/m/y') : '-'; ?></td>
															<td><?= "$servicio->cuil"; ?></td>
															<td><?= "$servicio->nombre"; ?></td>
															<td><?= (!empty($servicio->funcion_detalle)) ? $servicio->funcion_detalle : '-'; ?></td>
															<td><?= (!empty($servicio->funcion_destino)) ? $servicio->funcion_destino : '-'; ?></td>
															<td><?= (!empty($servicio->funcion_norma)) ? $servicio->funcion_norma : '-'; ?></td>
															<td><?= (!empty($servicio->funcion_tarea)) ? $servicio->funcion_tarea : '-'; ?></td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</section>
		</div>
	</body>
</html>