<html lang="es">
	<head>
		<base href="<?php echo base_url(); ?>" />
		<meta charset="utf-8">
	</head>
	<body>
		<?php $salto = FALSE; ?>
		<?php $meses = array('01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'); ?>
		<?php $fecha = new DateTime($beca_persona->fecha); ?>
		<?php $tipo_doc = array('ORIGINAL (Delegación)', 'DUPLICADO (Directivo)', 'TRIPLICADO (Postulante)'); ?>
		<?php for ($i = 0; $i < 3; $i++): ?>
			<section class="invoice" <?= $salto ? ' style="page-break-before: always"' : '' ?>>
				<table style="margin-left:7%;width:80%;">
					<tbody>
						<tr>
							<td width="12%" style="text-align: center;"><img src="img/logomza.jpg"></td>
							<td width="12%" style="text-align: center;"><img src="img/generales/logo-dge-sm.jpg" height="60"></td>
							<td width="52%" style="text-align: center; padding-left:20px;">
								<h3>Gobierno de Mendoza</h3>
								<h4>Dirección General de Escuelas</h4>
							</td>
							<td width="24%" style="text-align: right;"><img src="img/generales/logo-login.jpg" height="70"></td>
						</tr>
					</tbody>
				</table>
				<h4 class="text-center">Becas del Plan Provincial de Estímulo al Desarrollo Profesional Docente</h4>
				<h3 class="text-center">Recepción de Postulación - <?= $tipo_doc[$i]; ?></h3>
				<h4 style="text-align: center; "><?php echo "Escuela: $escuela->nombre_largo"; ?></h4>
				<br/>
				<div class="row">
					<div class="col-xs-offset-1 col-xs-10" style="text-align: justify;">
						<p><b>Fecha Postulación:</b> <?= (new DateTime($beca_persona->fecha))->format('d/m/Y H:i:s'); ?></p>
						<p><b>CUIL:</b> <?= $beca_persona->cuil; ?> <b>Nombre:</b> <?= "$beca_persona->apellido, $beca_persona->nombre"; ?></p>
						<table class="table table-hover table-bordered table-condensed" style="font-size: 12px;" role="grid">
							<thead>
								<tr>
									<th colspan="5" style="text-align: center;">Servicios activos</th>
								</tr>
								<tr>
									<th style="width:10%;">Esc.</th>
									<th style="width:20%;">Alta / Liq</th>
									<th style="width:30%;">Revista / Función</th>
									<th style="width:50%;">Condición / Regimen / División / Materia</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($servicios as $servicio): ?>
									<tr>
										<td><?= $servicio->escuela; ?></td>
										<td><?= (empty($servicio->fecha_alta) ? '' : (new DateTime($servicio->fecha_alta))->format('d/m/Y') . '<br/>') . "$servicio->liquidacion_s<br>$servicio->lsignos"; ?></td>
										<td><?= "$servicio->situacion_revista<br>$servicio->funcion"; ?></td>
										<td>
											<?= "$servicio->condicion_cargo<br>$servicio->regimen " . ($servicio->carga_horaria > 0 ? "<b>$servicio->carga_horaria</b> " : '') . "$servicio->regimen_descripcion"; ?>
											<?= empty($servicio->division) ? '' : "<br>$servicio->curso $servicio->division"; ?>
											<?= empty($servicio->materia) ? '' : "<br>$servicio->materia"; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</section>
			<br/>
			<br/>
			<table style="width:100%;">
				<tr>
					<td style="width:50%; text-align: center;">...................</td>
					<td style="width:50%; text-align: center;">...................</td>
				</tr>
				<tr>
					<td style="width:50%; text-align: center;">Firma Postulante</td>
					<td style="width:50%; text-align: center;">Firma Directivo<br>Me comprometo a elevar la documentación correspondiente</td>
				</tr>
			</table>
			<br/>
		</section>
		<?php $salto = TRUE; ?>
	<?php endfor; ?>
</body>
</html>