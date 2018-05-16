<style>
	.list-group-horizontal .list-group-item {
    display: inline-block;
	}
	.tab-pane{
		padding: 5px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label>Supervisión</label> <?php echo $supervision->nombre; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a>Divisiones con carrera por escuela</a></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Indice de divisiones con carrera por escuela</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-xs-12">
								<?php if (!empty($niveles)): ?>
									<?php foreach ($niveles as $nivel): ?>
										<?php if (!empty($nivel->escuelas)): ?>
											<table class="table table-bordered table-condensed table-striped table-hover">
												<tr>
													<th style="width: 50%;">Escuela</th>
													<th style="width: 45%;">Porcentaje divisiones</th>
													<th style="width: 3%;"></th>
													<th style="width: 2%;"></th>
												</tr>
												<?php foreach ($nivel->indices as $indice): ?>
													<tr>
														<td><?php echo "$indice->esc_numero - $indice->esc_nombre"; ?></td>
														<td class="text-center">
															<div class="progress sm">
																<?php $cumplimiento = $indice->total == 0 ? 0 : (round($indice->cantidad / $indice->total * 100)); ?>
																<div class="progress-bar progress-bar-<?php echo $cumplimiento >= 100 ? 'green' : ($cumplimiento >= 50 ? 'yellow' : 'red'); ?>" style="line-height:10px;font-size:10px; min-width: 2em; width: <?php echo $cumplimiento; ?>%"><?php echo $cumplimiento; ?>%</div>
															</div>
														</td>
														<td><b><?php echo ($indice->cantidad); ?></b>/<?php echo $indice->total; ?></td>
														<td><a class="btn btn-xs" href="division/listar/<?php echo $indice->escuela_id; ?>"><i class="fa fa-search"></i></a></td>
													</tr>
												<?php endforeach; ?>
											</table>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div><!-- Fin Escuelas -->
		</div>
	</section>
</div>
