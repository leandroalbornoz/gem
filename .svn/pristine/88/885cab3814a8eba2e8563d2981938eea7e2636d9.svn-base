<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Completamiento de datos - 2018</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<div class="col-xs-6">
			<table class="table table-condensed">
				<tbody>
					<tr>
						<th style="width: 20px"></th>
						<th style="width: 40px"></th>
						<th style="width: 20px"></th>
					</tr>
					<?php $porcentaje_con_cl = substr($estadisticas_generales->porcentaje_con_cl, 0, 4); ?>
					<?php $porcentaje_con_datos = substr($estadisticas_generales->porcentaje_con_datos, 0, 4); ?>
					<tr>
						<td>Total de alumnos</td>
						<td>
							<span class="badge bg-blue"><?php echo $estadisticas_generales->total_alumnos; ?></span>
						</td>
						<td></td>
					</tr>
					<tr>
						<td>Alumnos con ciclo lectivo actualizado</td>
						<td>
							<div class="progress progress-xs">
								<div class="progress-bar <?php echo ($porcentaje_con_cl < '0.8') ? 'progress-bar-danger' : (($porcentaje_con_cl > '0.99') ? 'progress-bar-success' : 'progress-bar-warning'); ?>" style="width: <?php echo $estadisticas_generales->porcentaje_con_cl * 100; ?>%"></div>
							</div>
						</td>
						<td>
							<span class="<?php echo ($porcentaje_con_cl < '0.8') ? 'badge bg-red' : (($porcentaje_con_cl > '0.99') ? 'badge bg-green' : 'badge bg-yellow'); ?>"><?php echo $estadisticas_generales->porcentaje_con_cl * 100; ?> %</span>
						</td>
					</tr>
					<tr>
						<td>Alumnos con datos personales completos</td>
						<td>
							<div class="progress progress-xs">
								<div class="progress-bar <?php echo ($porcentaje_con_datos < '0.8') ? 'progress-bar-danger' : (($porcentaje_con_datos > '0.99') ? 'progress-bar-success' : 'progress-bar-warning'); ?>" style="width: <?php echo $estadisticas_generales->porcentaje_con_datos * 100; ?>%"></div>
							</div>
						</td>
						<td>
							<span class="<?php echo ($porcentaje_con_datos < '0.8') ? 'badge bg-red' : (($porcentaje_con_datos > '0.99') ? 'badge bg-green' : 'badge bg-yellow'); ?>"><?php echo $estadisticas_generales->porcentaje_con_datos * 100; ?> %</span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="box-footer">
		<a class="btn btn-success btn-xs pull-right" target="_blank" href="completamiento/completamiento/reporte_indicadores_pdf/<?php echo $escuela->id; ?>"><i class="fa fa-file-pdf-o"></i>&nbsp; Reporte de indicadores</a>
	</div>
</div>
