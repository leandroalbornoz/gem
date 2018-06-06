<html lang="es">
	<head>
		<base href="<?php echo base_url(); ?>" />
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="description" content="">
	</head>
	<body>
		<header>
			<table>
				<tr>
					<td width="300px">
						<img src='img/generales/logo-dge.png' width="280px" alt=""/>
					</td>
					<td>
						<h3>Validación Bono de Puntaje  <br> Escuelas de Educación Secundaria, Educación Permanente de Jóvenes  <br>
							y Adultos y Educación Técnica y Trabajo</h3>
					</td>
				</tr>
			</table>
		</header>
		<section>
			<br>
			<table>
				<tr>	
					<td colspan="6">
						<h4><u>Datos personales:</u></h4>
					</td>
				</tr>
				<tr>
					<td>
						<strong>Nro.Trámite:</strong>
					</td>
					<td>
						<h4><?= $d_inscripcion->id; ?> </h4>
					</td>
					<td colspan="4"></td>
				</tr>
				<tr>
					<td>
						<strong>Nombre:</strong>
					</td>
					<td>
						<?= "$persona->apellido, $persona->nombre"; ?> 
					</td>
					<td>
						<strong>CUIL:</strong>
					</td>
					<td>
						<?= $persona->cuil; ?> 
					</td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>
						<strong>Domicilio:</strong>
					</td>
					<td>
						<?php
						echo "$persona->calle $persona->calle_numero";
						if (!empty($persona->piso))
							echo " Piso $persona->piso";
						if (!empty($persona->departamento))
							echo " Depto $persona->departamento";
						?> 
					</td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>
						<strong>Departamento - Localidad:</strong>
					</td>
					<td>
						<?= "$persona->localidad"; ?> 
					</td>
					<td>
						<strong>Provincia:</strong>
					</td>
					<td>
						Mendoza 
					</td>
					<td>
						<strong>CP:</strong>
					</td>
					<td>
						<?= $persona->codigo_postal; ?> 
					</td>
				</tr>
				<tr>
					<td >
						<strong>Teléfono:</strong>
					</td>
					<td width="200px">
						<?= $persona->telefono_fijo . " - " . $persona->telefono_movil ?>
					</td>
					<td>
						<strong>Correo Electrónico:</strong>
					</td>
					<td>
						<?= $persona->email; ?> 
					</td>
					<td colspan="2"></td>
				</tr>
			</table>
			<h4><u>Referencias validación:</u></h4>
			<p>
				<img src="img/generales/fa-check-circle-o.png" height="20" width="20"> Aceptado
			</p>
			<p>
				<img src="img/generales/fa-times-circle-o.png" height="20" width="20"> No aceptado
			</p>
			<?php if (isset($d_titulos) && !empty($d_titulos)): ?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<td align="center" colspan="2" style="background-color: #d9d9d9; padding: 2px;">
								<h4>Títulos</h4>
							</td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($d_titulos as $titulo): ?>
							<tr>
								<td>
									<b>Denominación - Entidad: </b><?= $titulo->titulo; ?> - <?= $titulo->entidad_emisora; ?>
									<br>
									<b>F.Emisión: </b><?= (new DateTime($titulo->fecha_emision))->format('d/m/Y') ?>&nbsp;&nbsp;
									<b>Promedio: </b><?= $titulo->promedio; ?> &nbsp;&nbsp;
									<b>Modalidad: </b><?= $titulo->modalidad ?> &nbsp;&nbsp;
									<b>Norma legal: </b><?= "$titulo->norma_legal_tipo $titulo->norma_legal_numero/$titulo->norma_legal_año" ?> &nbsp;&nbsp;
									<br>
									<b>Años de cursado: </b><?= $titulo->años_cursado; ?> &nbsp;&nbsp;
									<b>Horas reloj: </b><?= $titulo->cantidad_hs_reloj; ?> &nbsp;&nbsp;
									<b>Registro: </b><?= $titulo->registro; ?> &nbsp;&nbsp;
								</td>
								<td valign="middle">
									<?= ($titulo->estado) ? '<img src="img/generales/fa-check-circle-o.png" height="20" width="20">' : '<img src="img/generales/fa-times-circle-o.png" height="20" width="20">'; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
			<?php if (isset($d_postitulos) && !empty($d_postitulos)): ?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<td align="center" colspan="2" style="background-color: #d9d9d9; padding: 2px;">
								<h4>Postitulos</h4>
							</td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($d_postitulos as $postitulo): ?>
							<tr>
								<td>
									<b>Denominación - Entidad: </b><?= "$postitulo->titulo - $postitulo->entidad_emisora" ?>
									<br>
									<b>F.Emisión: </b><?= (new DateTime($postitulo->fecha_emision))->format('d/m/Y') ?>&nbsp;&nbsp;
									<b>Promedio: </b><?= $postitulo->promedio; ?> &nbsp;&nbsp;
									<b>Modalidad: </b><?= $postitulo->modalidad ?> &nbsp;&nbsp;
									<b>Norma legal: </b><?= "$postitulo->norma_legal_tipo $postitulo->norma_legal_numero/$postitulo->norma_legal_año" ?> &nbsp;&nbsp;
									<br>
									<b>Tipo: </b><?= $postitulo->postitulo_tipo; ?> &nbsp;&nbsp;
									<b>Años de cursado: </b><?= $postitulo->años_cursado; ?> &nbsp;&nbsp;
									<b>Horas reloj: </b><?= $postitulo->cantidad_hs_reloj; ?> &nbsp;&nbsp;
									<b>Registro: </b><?= $postitulo->registro; ?> &nbsp;&nbsp;
								</td>
								<td valign="middle">
									<?= ($postitulo->estado) ? '<img src="img/generales/fa-check-circle-o.png" height="20" width="20">' : '<img src="img/generales/fa-times-circle-o.png" height="20" width="20">'; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
			<?php if (isset($d_posgrados) && !empty($d_posgrados)): ?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<td align="center" colspan="2" style="background-color: #d9d9d9; padding: 2px;">
								<h4>Posgrado</h4>
							</td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($d_posgrados as $posgrado): ?>
							<tr>
								<td>
									<b>Denominación - Entidad: </b><?= "$posgrado->titulo - $posgrado->entidad_emisora" ?>
									<br>
									<b>F.Emisión: </b><?= (new DateTime($posgrado->fecha_emision))->format('d/m/Y') ?>&nbsp;&nbsp;
									<b>Promedio: </b><?= $posgrado->promedio; ?> &nbsp;&nbsp;
									<b>Modalidad: </b><?= $posgrado->modalidad ?> &nbsp;&nbsp;
									<b>Norma legal: </b><?= "$posgrado->norma_legal_tipo $posgrado->norma_legal_numero/$posgrado->norma_legal_año" ?> &nbsp;&nbsp;
									<br>
									<b>Tipo: </b><?= $posgrado->posgrado_tipo; ?> &nbsp;&nbsp;
									<b>Años de cursado: </b><?= $posgrado->años_cursado; ?> &nbsp;&nbsp;
									<b>Horas reloj: </b><?= $posgrado->cantidad_hs_reloj; ?> &nbsp;&nbsp;
									<b>Registro: </b><?= $posgrado->registro; ?> &nbsp;&nbsp;
								</td>
								<td valign="middle">
									<?= ($posgrado->estado) ? '<img src="img/generales/fa-check-circle-o.png" height="20" width="20">' : '<img src="img/generales/fa-times-circle-o.png" height="20" width="20">'; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
			<table width="100%" class="table table-bordered">
				<thead>
					<tr>
						<td align="center" colspan="5" style="background-color: #d9d9d9; padding: 2px;">
							<h4>Antigüedad</h4>
						</td>
					</tr>
					<tr>
						<td width="150px">
							<strong>Tipo</strong>
						</td>
						<td width="400px">
							<strong>Institución</strong>
						</td>
						<td width="90px">
							<strong>Desde</strong>
						</td>
						<td width="90px">
							<strong>Hasta</strong>
						</td>
						<td>
						</td>
					</tr>
				</thead>
				<tbody>
					<?php if (isset($d_antiguedad) && !empty($d_antiguedad)): ?>
						<?php foreach ($d_antiguedad as $antiguedad) { ?>
							<tr>
								<td>
									<?= $antiguedad->descripcion; ?>
								</td>
								<td>
									<?= $antiguedad->institucion; ?>
								</td>
								<td>
									<?= (new DateTime($antiguedad->fecha_desde))->format('d/m/Y') ?>
								</td>
								<td>
									<?= (new DateTime($antiguedad->fecha_hasta))->format('d/m/Y') ?>
								</td>
								<td valign="middle">
									<?= ($antiguedad->estado) ? '<img src="img/generales/fa-check-circle-o.png" height="20" width="20">' : '<img src="img/generales/fa-times-circle-o.png" height="20" width="20">'; ?>
								</td>
							</tr>
						<?php } ?>
					<?php else: ?>
						<tr>
							<td colspan="4" align="center">
								<?= "-- No posee antiguedad --" ?>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
			<table width="100%" class="table table-bordered">
				<thead>
					<tr>
						<td align="center" colspan="4" style="background-color: #d9d9d9; padding: 2px;">
							<h4>Antecedentes</h4>
						</td>
					</tr>
					<tr>
						<td width="470px">
							<strong>Nombre antecedente</strong>
							<br>
							<strong>Institución</strong>
						</td>
						<td width="130px">
							<strong>N°Resolución</strong>
							<br>
							<strong>Fecha Emisión</strong>
						</td>
						<td width="50px">
							<strong>Duración</strong>
							<br>
							<strong>Modalidad</strong>
						</td>
						<td></td>
					</tr>
				</thead>
				<tbody>						
					<?php if (isset($d_antecedente) && !empty($d_antecedente)): ?>
						<?php foreach ($d_antecedente as $antecedente) { ?>
							<tr>
								<td>
									<?= $antecedente->antecedente; ?> <br>
									<?= $antecedente->institucion; ?>
								</td>
								<td>
									<?= ($antecedente->numero_resolucion) ? $antecedente->numero_resolucion : ''; ?>
									<br>
									<?= (new DateTime($antecedente->fecha_emision))->format('d/m/Y') ?>
								</td>
								<td>
									<?= "$antecedente->duracion  $antecedente->tipo_duracion"; ?>
									<br>
									<?= "$antecedente->modalidad"; ?>
								</td>
								<td valign="middle">
									<?= ($antecedente->estado) ? '<img src="img/generales/fa-check-circle-o.png" height="20" width="20">' : '<img src="img/generales/fa-times-circle-o.png" height="20" width="20">'; ?>
								</td>
							</tr>
						<?php } ?>
					<?php else: ?>
						<tr>
							<td colspan="4" align="center">
								<?= "-- No posee antecedentes --" ?>
							</td>
						</tr>
					<?php endif; ?>

				</tbody>
			</table>
			<h4><u>Datos Inscripción:</u></h4>
			<table width="100%" >
				<tbody>
					<tr>
						<td colspan="4">
							<strong>Cargos a los que aspira:</strong>
							<?php
							if (!empty($persona_cargos)):
								foreach ($persona_cargos as $persona_cargo) {
									echo "$persona_cargo->cargo .-";
								}
							endif;
							?>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<strong>Escuela en la que se presentó la documentación:</strong> <?= $d_inscripcion->escuela; ?>
							<br><strong>Fecha de cierre inscripción: </strong> <?= (new DateTime($d_inscripcion->fecha_cierre))->format('d/m/Y'); ?> <br>
							<br><strong>Fecha de validación: </strong> <?= (new DateTime($d_inscripcion->fecha_recepcion))->format('d/m/Y'); ?> <br>
						</td>
					</tr>
				</tbody>
			</table>
		</section>
	</body>
</html>