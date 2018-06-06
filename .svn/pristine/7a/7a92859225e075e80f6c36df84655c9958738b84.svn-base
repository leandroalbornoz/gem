<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Asistencia y Novedades <?php echo "$area->codigo $area->descripcion"; ?> - <label class="label label-default"><?php echo $planilla->ames; ?></label>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="areas/area/ver/<?php echo $area->id; ?>"><?php echo "$area->codigo $area->descripcion"; ?></a></li>
			<li class="active"><?php echo ucfirst($metodo); ?></li>
		</ol>
	</section>
	<section class="content">
		<?php if (!empty($error)) : ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-ban"></i> Error!</h4>
				<?php echo $error; ?>
			</div>
		<?php endif; ?>
		<?php if (!empty($message)) : ?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-check"></i> OK!</h4>
				<?php echo $message; ?>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Novedades de <?php echo $planilla->titulo; ?></h3>
					</div>
					<div class="box-body">
						<a class="btn bg-default btn-app btn-app-zetta <?= empty($novedades) ? 'disabled' : '' ?>" href="areas/asisnov/imprimir_parcial/<?= $planilla->id; ?>" target="_blank">
							<i class="fa fa-print" id="btn-imprimir-t-f"></i> Imprimir
						</a>
						<table id="servicio_novedad_table" class="table table-hover table-bordered table-condensed dt-responsive" aria-describedby="servicio_novedad_table_info" role="grid">
							<thead>
								<tr role="row">
									<th class="text-center" style="width: 3%;"></th>
									<th class="text-sm" style="width: 19%;">Persona</th>
									<th class="text-sm" style="width: 7%;">Liquidación</th>
									<th class="text-center" style="width: 3%;">Cur</th>
									<th class="text-center" style="width: 3%;">Div</th>
									<th class="text-sm" style="width: 21%;">Régimen/Materia</th>
									<th class="text-center" style="width: 3%;">Hs.</th>
									<th style="width: 3%;">Art.</th>
									<th class="text-sm" style="width: 10%;">Novedad</th>
									<th class="text-center" style="width: 4%;">Desde</th>
									<th class="text-center" style="width: 4%;">Hasta</th>
									<th class="text-center" style="width: 3%;">Dias</th>
									<th class="text-center" style="width: 3%;">Oblig</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($novedades)) : ?>
									<?php foreach ($novedades as $novedad) : ?>
										<tr role="row">
											<td class="text-center">
												<?php if ($novedad->planilla_alta_id === $planilla->id): ?>
													<i class="text-green fa fa-plus"></i>
												<?php elseif ($novedad->planilla_baja_id === $planilla->id): ?>
													<i class="text-red fa fa-remove"></i>
												<?php endif; ?>
											</td>
											<td class="text-sm"><?= $novedad->persona; ?></td>
											<td class="text-sm"><?= $novedad->liquidacion; ?></td>
											<td class="text-center"><?= $novedad->curso; ?></td>
											<td class="text-center"><?= $novedad->division; ?></td>
											<td class="text-sm"><?= $novedad->regimen_materia; ?><br></td>
											<td class="text-center"><?= $novedad->carga_horaria; ?></td>
											<td class=""><?= $novedad->articulo; ?></td>
											<td class="text-sm"><?= $novedad->novedad_tipo; ?></td>
											<td class="text-center"><?= (new DateTime($novedad->fecha_desde))->format('d/m/Y'); ?></td>
											<td class="text-center"><?= (new DateTime($novedad->fecha_hasta))->format('d/m/Y'); ?></td>
											<td class="text-center"><?= $novedad->dias; ?></td>
											<td class="text-center"><?= $novedad->obligaciones; ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
								<td colspan="13">No hay registros.</td>
							<?php endif; ?>
							</tbody>
						</table>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="areas/asisnov/index/<?php echo $planilla->area_id . '/' . $planilla->ames; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
