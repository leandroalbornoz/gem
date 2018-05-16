<?php if (!empty($escuelas_desinfeccion_eleccion)): ?>
	<div class="box box-primary collapsed-box">
		<div class="box-header with-border">
			<i class="text-green fa fa-shower"></i>
			<h3 class="box-title">Desinfección por elecciones</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
			</div>
		</div>
		<div class="box-body ">
			<?php foreach ($escuelas_desinfeccion_eleccion as $key => $escuela_desinfeccion_eleccion): ?>
				<table class="table table-bordered table-condensed table-striped table-hover">
					<thead>
						<tr style="background-color: #d4d4d4">
							<th colspan="6" style="text-align:center;"><?php echo $key; ?></th>
						</tr>
						<tr>
							<th>Escuela</th>
							<th>Numero de mesas</th>
							<th>Celadores permitidos</th>
							<th>Celadores asignados</th>
							<th>Fecha de cierre</th>
							<th style="text-align: center;"></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($escuela_desinfeccion_eleccion as $escuela): ?>
							<tr>
								<td style="width: 50%;"><?php echo "$escuela->escuela"; ?></td>
								<td style="width: 10%;"><?php echo $escuela->mesas; ?></td>
								<td style="width: 10%;"><?php echo "$escuela->celadores_permitidos"; ?></td>
								<td style="width: 10%;"><?php echo $escuela->celadores_asignados; ?></td>
								<td style="width: 10%;"><?php echo isset($escuela->fecha_cierre) ? (new DateTime($escuela->fecha_cierre))->format('d/m/Y') : 'No cerrado'; ?></td>
								<td style="width: 10%; text-align: center;"><a href="elecciones/desinfeccion/supervision_ver/<?php echo "$escuela->eleccion_id/$escuela->id"; ?>"><i class="fa fa-search"></i></a></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>