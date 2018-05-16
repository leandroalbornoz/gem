<section>
	<table style="width: 100%; margin-bottom: 5px;" role="grid">
		<tbody>
			<tr>
				<td style="text-align: left; font-weight: bold;">
					<p style="font-weight: bold">2. DATOS RELACIONADOS A CARGOS, HORAS CÁTEDRA O FUNCIONES</p>
				</td>
			</tr>
		</tbody>
	</table>
	<table style="width: 100%; border-collapse: collapse;">
		<thead>
			<tr style="border: 1px solid black;">
				<th></th>
				<th style="font-size: 12px; text-align: left; font-weight: bold; border: 1px solid black;" rowspan="2" width="18%">Escuela de Gestión Estatal o Privada y Organismo Nacional, Provincial, Municipal según Ley 6929/01</th>
				<th style="font-size: 12px; text-align: center; font-weight: bold; border: 1px solid black;" rowspan="2" width="16%">Cargo/Horas Cátedra</th>
				<th style="font-size: 12px; text-align: center; font-weight: bold; border: 1px solid black;" rowspan="2" width="3%">Cant.</th>
				<th style="font-size: 12px; text-align: center; font-weight: bold; border: 1px solid black;" rowspan="2" width="10%">Función</th>
				<th style="font-size: 12px; text-align: center; font-weight: bold; border: 1px solid black;" rowspan="2" width="6%">Situación de Revista</th>
				<th style="font-size: 12px; text-align: center; font-weight: bold; border: 1px solid black;" rowspan="2" width="5%">Haber con Goce (Si/No)<span style="color:red">*</span><br>Novedad</th>
				<th style="font-size: 12px; text-align: center; font-weight: bold; border: 1px solid black;" colspan="7" width="35%">Horario de Prestación de Servicios</th>
				<th style="font-size: 12px; text-align: center; font-weight: bold; border: 1px solid black;" rowspan="2" width="7%">Firma del Directivo</th>
			</tr>
			<tr style="border: 1px solid black;">
				<th></th>
				<th style="font-size: 10px; text-align: center; font-weight: bold; border: 1.25px solid black;" width="5%">Lunes</th>
				<th style="font-size: 10px; text-align: center; font-weight: bold; border: 1.25px solid black;" width="5%">Martes</th>
				<th style="font-size: 10px; text-align: center; font-weight: bold; border: 1.25px solid black;" width="5%">Miércoles</th>
				<th style="font-size: 10px; text-align: center; font-weight: bold; border: 1.25px solid black;" width="5%">Jueves</th>
				<th style="font-size: 10px; text-align: center; font-weight: bold; border: 1.25px solid black;" width="5%">Viernes</th>
				<th style="font-size: 10px; text-align: center; font-weight: bold; border: 1.25px solid black;" width="5%">Sábado</th>
				<th style="font-size: 10px; text-align: center; font-weight: bold; border: 1.25px solid black;" width="5%">Domingo</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($servicios as $servicio): ?>
				<tr style="border: 1px solid black;">
					<td></td>
					<td style="text-align: left; border: 1px solid black; font-size: 11px" height="35px"><?= $servicio->institucion . (empty($servicio->destino) ? '' : "<br><span style='font-style: italic;'>(Cumple en: $servicio->destino)</span>"); ?>
						<br>
						<?php
//						if ($liquidacion['codigo'] == '0440'):
//							echo "MAYOR DEDICACION " . $liquidacion['subcod'];
//						elseif ($liquidacion['codigo'] == '0950'):
//							echo "FULL TIME";
//						endif;
//						
						?>
					<td style="text-align: left; border: 1px solid black; font-size: 11px">
						Régimen <?= $servicio->regimen_codigo; ?>, Ptos <?= str_pad($servicio->regimen_puntos, 4, '0', STR_PAD_LEFT); ?>
						<br>
						<?= $servicio->regimen; ?>
					</td>
					<td style="text-align: center; border: 1px solid black; font-size: 11px"><?= $servicio->carga_horaria; ?></td>
					<td style="text-align: center; border: 1px solid black; font-size: 11px"><?= empty($servicio->id) ? '<span style="font-style: italic;">(Sin declarar en GEM)</span>' : ($servicio->funcion . (empty($servicio->funcion_desde) ? '' : '<br>Desde: ' . (new DateTime($servicio->funcion_desde))->format('d/m/Y'))); ?></td>
					<td style="text-align: center; border: 1px solid black; font-size: 11px"><?= strtoupper($servicio->revista) . ($servicio->condicion === 'Normal' ? '' : '<br>' . $servicio->condicion); ?></td>
					<td style="text-align: center; border: 1px solid black; font-size: 11px"><?= "$servicio->haberes<br>" . (empty($servicio->fecha_baja) ? $servicio->novedad : '<span style="font-weight: bold;">Baja: ' . (new DateTime($servicio->fecha_baja))->format('d/m/y') . '</span>'); ?></td>
					<?php for ($dia = 1; $dia <= 7; $dia++): ?>
						<td style="text-align: center; border: 1px solid black; font-size: 10px; white-space: nowrap;">
							<?php if (isset($servicio->sfh_id)): ?>
								<span style="font-style: italic;"><?= $servicio->{"sfh_$dia"}; ?></span>
							<?php else: ?>
								<?php if (empty($servicio->{"h1_$dia"}) || empty($servicio->{"h1_$dia"})): ?>
									<?= $servicio->{"h1_$dia"} . $servicio->{"h2_$dia"}; ?>
								<?php else: ?>
									<?= $servicio->{"h1_$dia"} . '<br>' . $servicio->{"h2_$dia"}; ?>
								<?php endif; ?>
							<?php endif; ?>
						</td>
					<?php endfor; ?>
					<td style='text-align: center; border: 1px solid black; font-size: 11px'></td>
				</tr>
			<?php endforeach; ?>
			<?php for ($i = 0; $i <= 3; $i++): ?>
				<tr style="border: 1px solid black;">
					<td style="border: 1px solid black;" height="35px">&nbsp;</td>
					<?php for ($j = 0; $j < 14; $j++): ?>
						<td style="border: 1px solid black;"></td>
					<?php endfor; ?>
				</tr>
			<?php endfor; ?>
		</tbody>
	</table>
	<div style="page-break-inside: avoid; margin-top: 8px;">
		<table style="width: 100%; margin-bottom: 5px;" role="grid">
			<tbody>
				<tr>
					<td style="text-align: left; font-weight: bold;">
						<p style="font-weight: bold">3. DATOS SOBRE CERTIFICADO DE APTITUD PSICOFÍSICA DEL AGENTE EN EL CARGO, HORAS CÁTEDRA O FUNCIONES</p>
					</td>
				</tr>
			</tbody>
		</table>
		<table style="width: 100%; border-collapse: collapse;">
			<tbody>
				<tr style="border: 1px solid black;">
					<td rowspan="2" width="8%" style="padding-left: 5px; height: 50px; font-size: 12px; border: 1px solid black;">Posee<br>Certificado</td>
					<td rowspan="2" width="3%" style="height: 50px; font-size: 12px; text-align: center; border: 1px solid black;">Si</td>
					<td rowspan="2" width="3%" style="height: 50px; font-size: 12px; text-align: center; border: 1px solid black;">No</td>
					<td rowspan="2" width="9%" style="padding-left: 5px; height: 50px; font-size: 12px; border: 1px solid black;">Fecha de<br>Emisión</td>
					<td width="3%" style="height: 25px; font-size: 12px; text-align: center; border: 1px solid black;">D</td>
					<td width="3%" style="height: 25px; font-size: 12px; text-align: center; border: 1px solid black;">M</td>
					<td width="3%" style="height: 25px; font-size: 12px; text-align: center; border: 1px solid black;">A</td>
					<td rowspan="2" width="9%" style="padding-left: 5px; height: 50px; font-size: 12px; border: 1px solid black;">Fecha de<br>vencimiento</td>
					<td width="3%" style="height: 25px; font-size: 12px; text-align: center; border: 1px solid black;">D</td>
					<td width="3%" style="height: 25px; font-size: 12px; text-align: center; border: 1px solid black;">M</td>
					<td width="3%" style="height: 25px; font-size: 12px; text-align: center; border: 1px solid black;">A</td>
					<td rowspan="2" width="8%" style="padding-left: 5px; height: 50px; font-size: 12px; border: 1px solid black;">Certificado<br>Jerárquico</td>
					<td rowspan="2" width="3%" style="height: 50px; font-size: 12px; text-align: center; border: 1px solid black;">Si</td>
					<td rowspan="2" width="3%" style="height: 50px; font-size: 12px; text-align: center; border: 1px solid black;">No</td>
					<td rowspan="2" width="9%" style="padding-left: 5px; height: 50px; font-size: 12px; border: 1px solid black;">Fecha de<br>Emisión</td>
					<td width="3%" style="height: 25px; font-size: 12px; text-align: center; border: 1px solid black;">D</td>
					<td width="3%" style="height: 25px; font-size: 12px; text-align: center; border: 1px solid black;">M</td>
					<td width="3%" style="height: 25px; font-size: 12px; text-align: center; border: 1px solid black;">A</td>
					<td rowspan="2" width="9%" style="padding-left: 5px; height: 50px; font-size: 12px; border: 1px solid black;">Fecha de<br>vencimiento</td>
					<td width="3%" style="height: 25px; font-size: 12px; text-align: center; border: 1px solid black;">D</td>
					<td width="3%" style="height: 25px; font-size: 12px; text-align: center; border: 1px solid black;">M</td>
					<td width="3%" style="height: 25px; font-size: 12px; text-align: center; border: 1px solid black;">A</td>
				</tr>
				<tr style="border: 1px solid black; height: 50px;">
					<td style="height: 25px; border: 1px solid black;">&nbsp;</td>
					<td style="height: 25px; border: 1px solid black;">&nbsp;</td>
					<td style="height: 25px; border: 1px solid black;">&nbsp;</td>
					<td style="height: 25px; border: 1px solid black;">&nbsp;</td>
					<td style="height: 25px; border: 1px solid black;">&nbsp;</td>
					<td style="height: 25px; border: 1px solid black;">&nbsp;</td>
					<td style="height: 25px; border: 1px solid black;">&nbsp;</td>
					<td style="height: 25px; border: 1px solid black;">&nbsp;</td>
					<td style="height: 25px; border: 1px solid black;">&nbsp;</td>
					<td style="height: 25px; border: 1px solid black;">&nbsp;</td>
					<td style="height: 25px; border: 1px solid black;">&nbsp;</td>
					<td style="height: 25px; border: 1px solid black;">&nbsp;</td>
					<td style="height: 25px; border: 1px solid black;">&nbsp;</td>
				</tr>
			</tbody>
		</table>
		<br>
		<table style="width: 100%; border-collapse: collapse;">
			<tr>
				<td style="width: 55%; height: 25px; font-size: 10px; border: 1px solid black;">ESTE DOCUMENTO PARA SER VÁLIDO COMO DECLARACIÓN JURADA DEBERÁ CONTENER LA FIRMA DEL AGENTE Y DE SU/S SUPERIOR/ES JERÁRQUICO/S</td>
				<td style="width: 2%;">&nbsp;</td>
				<td style="width: 43%; height: 25px; font-size: 10px; border: 1px solid black;">TODOS LOS CAMPOS TIENEN CARÁCTER DE OBLIGATORIOS.</td>
			</tr>
			<tr>
				<td style="width: 55%; height: 25px; font-size: 10px; border: 1px solid black;">EL SOBRERRASPADO VALE SOBRE LO PREIMPRESO SIEMPRE QUE SE ADJUNTE LA DOCUMENTACIÓN RESPALDATORIA QUE LO AVALE.</td>
				<td style="width: 2%;">&nbsp;</td>
				<td style="width: 43%; height: 25px; font-size: 10px; border: 1px solid black;">DEBE DECLARARSE TODO EJERCICIO PRIVADO DE LA PROFESIÓN O TECNICATURA, SEGÚN LO ESTABLECE LA LEY 6929 DE INCOMPATIBILIDAD DOCENTE</td>
			</tr>
		</table>
		<br>
		<table style="width: 100%; border-collapse: collapse;">
			<tbody>
				<tr>
					<td style="padding-left: 0; text-align: left; font-weight: bold;" width="30%">
						<table style="width: 100%; border-collapse: collapse;">
							<tbody>
								<tr>
									<td style="width: 25%; line-height: 30px; text-align: center; font-weight: bold; border: 1px solid black;">RECTIFICO</td>
									<td style="width: 10%; line-height: 30px; text-align: center; font-weight: bold; border: 1px solid black;">SI</td>
									<td style="width: 10%; line-height: 30px; text-align: center; font-weight: bold; border: 1px solid black;">NO</td>
									<td style="width: 55%;"></td>
								</tr>
							</tbody>
						</table>
					</td>
					<td style="text-align: left; font-weight: bold;">
						Adjuntar las certificaciones oficiales probatorias de los datos rectificados
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</section>