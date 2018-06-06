<!DOCTYPE html>
<html lang="es">
	<head>
		<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
		<title>.: Reporte de Referentes - Tribunal de Cuentas - DGE :.</title>
		<link href="plugins/bootstrap-2.3.1/css/bootstrap.css" rel="stylesheet">
		<style>
			#print_area{
        line-height: 1;
			}
			#print_area table{
        font-size: small;
			}
		</style>
	</head>
	<body>
		<div style="margin: 30px;">
			<div id="print_area" style="margin-top: 20px;">
				<img src="http://testing.mendoza.edu.ar/gem/img/banner_colonias.png" width="100%">
				<label align="center"><h4>Reporte de referentes</h4></label>
				<label align="center"><h5>Datos de la escuela</h5></label>
				<div class="row-fluid">
					<div class="span12">
						<div class="input-append">
							Escuela Nº <input size="40" type="text" value="<?php echo $escuela->numero; ?>">
							Escuela Nombre <input size="100" type="text" value="<?php echo $escuela->nombre; ?>">   
							Departamento <input size="30" type="text" value="<?php echo $escuela->departamento; ?>">                        
						</div>            
					</div>           
				</div>   
				<div class="row-fluid">
					<div class="span12">
						<div class="input-append">
							Domicilio <input size="100" type="text" value="<?php echo "$escuela->calle $escuela->calle_numero" . (empty($escuela->barrio) ? '' : " B°:$escuela->barrio"); ?>">
							Cuenta Corriente Banco Nación Nº <input size="50" type="text" value="<?php echo $tribunal_cuenta->numero_cuenta; ?>">                                   
						</div>            
					</div>           
				</div>    
				<div class="row-fluid">
					<div class="span12">
						<div class="input-append">
							<?php if(!empty($fecha_hasta) && !empty($fecha_desde)): ?>
							Fecha de Reporte Desde <input size="25" type="text" value="<?php echo $fecha_desde; ?>"> Hasta <input size="25" type="text" value="<?php echo $fecha_hasta; ?>">
							<?php else: ?>							
							Fecha de Reporte <input size="25" type="text" value="<?php echo date('d/m/Y'); ?>">                                    
							<?php endif; ?>
						</div>            
					</div>           
				</div>  
				<label><h5>Responsables de las cuentas bancarias</h5></label>
				<table border="1" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th align="center" rowspan="2">APELLIDO Y NOMBRE</th>
							<th align="center" rowspan="2">DNI</th>
							<th rowspan="2" align="center">CUIL</th>
							<th rowspan="2" align="center">EMAIL</th>
							<th rowspan="2" align="center">TELÉFONO</th>
							<th rowspan="2" align="center">CARGO</th>
							<th colspan="2" align="center">PERÍODO</th>
							<th rowspan="2" align="center">DOMICILIO REAL</th>
							<th rowspan="2" align="center">DOMICILIO LEGAL</th>
						</tr>
						<tr>
							<th>DESDE</th>
							<th>HASTA</th>
						</tr>      
					</thead>
					<tbody>
						<?php foreach ($referentes as $referente): ?>
							<tr>
								<td align="center"><?php echo $referente->apellido . ', ' . $referente->nombre; ?></td>
								<td align="center"><?php echo $referente->documento; ?></td>
								<td align="center"><?php echo $referente->cuil; ?></td>
								<td align="center"><?php echo $referente->email; ?></td>
								<td align="center"><?php echo $referente->telefono_fijo; ?></td>
								<td align="center"><?php echo $referente->cargo; ?></td>
								<td align="center"><?php echo $referente->fecha_desde; ?></td>
								<td align="center"><?php echo $referente->fecha_hasta; ?></td>
								<td align="center"><?php echo $referente->domicilio_real; ?></td>
								<td align="center">Casa de Gobierno - 3er Piso Ala Este - Ciudad</td>
							</tr>   
						<?php endforeach; ?>
					</tbody>
				</table>
				LA PRESENTE TIENE CARÁCTER DE DECLARACIÓN JURADA
			</div>
		</div>
	</body>
</html>