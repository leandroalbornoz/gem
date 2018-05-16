<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="text-green fa fa-book"></i> Operativo Leer y Escribir en 2° Grado - 2017</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-bordered table-condensed table-striped table-hover text-center">
					<thead>
						<tr>
							<th colspan="4">2° Grado</th>
						</tr>
						<tr>
							<th>División</th>
							<th>Total alumnos</th>
							<th>Total cargados</th>
							<th>Total ausentes</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($divisiones)): ?>
							<?php foreach ($divisiones as $division): ?>
								<tr>
									<td><?php echo "$division->curso $division->division"; ?></td>
									<td><?php echo $division->total_alumnos; ?></td>
									<td><?php echo $division->total_cargados; ?></td>
									<td><?php echo $division->total_ausentes; ?></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php if ($administrar): ?>
		<div class="box-footer">
			<a class="btn btn-primary" href="operativo_evaluar/evaluar_operativo/listar_divisiones/<?php echo $escuela->id; ?>">
				<i class="fa fa-cogs"></i> Administrar
			</a>
		</div>
	<?php endif; ?>
</div>