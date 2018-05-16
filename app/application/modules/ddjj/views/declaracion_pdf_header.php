<header>
	<table class="table table-hover table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid" style="margin-bottom: 0px;">
		<tbody>
			<tr>
				<td style="text-align: left; width: 20%;">
					<img src="<?= base_url('img/generales/logo-mendoza.png'); ?>" height="50px"/>
				</td>
				<td style="text-align: center; font-weight: bold;">
					DECLARACION JURADA DE CARGOS, HORAS CATEDRA Y FUNCIONES
					<br>
					LEY N° 6929/01 y Decreto Reglamentario N°285/02)
					<br>
					Resolución N° 531/14
				</td>
				<td style="text-align: right; font-weight: bold; width: 20%;">
					<img src="<?= base_url('img/generales/logo-dge.png'); ?>" height="50px"/>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="table table-condensed" style="margin-bottom: 5px;" role="grid">
		<tbody>
			<tr>
				<td style="text-align: left; font-weight: bold;">
					<p style="font-weight: bold">1. DATOS PERSONALES</p>
				</td>
				<td style="text-align: right; font-weight: bold;">
					<div style="border: 1px solid red; padding: 2px;">
						Fecha: <?= (new DateTime())->format('d/m/Y'); ?> - Fecha límite de validez: <?= (new DateTime('+ 30 days'))->format('d/m/Y'); ?>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<table style="border: 1px solid black; border-collapse: collapse;" width="100%">
		<tbody>
			<tr style="border: 1px solid black;"> 
				<td style="text-align: left;  border: 1px solid black; width: 50%;">
					<b>Cuil: <?= $persona->cuil; ?></b>
					<br>
					<b>Apellido y nombre:</b> <?= $persona->persona; ?>
					<br>
					<b>Fecha de Nacimiento:</b> <?= $persona->fecha_nacimiento; ?>
				</td>
				<td style="text-align: left; border: 1px solid black; width: 50%;">
					<b>Domicilio:</b> <?= $persona->domicilio; ?>
					<br>
					<b>Departamento:</b> <?= $persona->departamento; ?>
					<table style="width: 100%; border-collapse: collapse;">
						<tbody>
							<tr>
								<td style="text-align: left; width: 40%; padding: 0; margin: 0;"><b>Teléfono:</b> <?= $persona->telefono; ?></td>
								<td style="text-align: left; width: 60%; padding: 0; margin: 0;"><b>E-mail:</b> <?= $persona->email; ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</header>