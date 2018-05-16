<?php if (!empty($relevamientos_extintores)): ?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="text-red fa fa-fire-extinguisher"></i> Relevamiento Extintores</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<div class="row">
				<?php foreach ($relevamientos_extintores as $relevamiento_extintores): ?>
					<div class="col-xs-12">
						<h4 style="margin-top: 0;border-top: blue solid thin;"><?php echo $relevamiento_extintores->descripcion . '<span class="pull-right"><b>Fechas de carga:</b> ' . (new DateTime($relevamiento_extintores->fecha_desde))->format('d/m/y') . ' al ' . (new DateTime($relevamiento_extintores->fecha_hasta))->format('d/m/y') . '</span>'; ?></h4>
						<table class="table table-bordered table-condensed table-striped table-hover">
							<thead>
								<tr>
									<th>
										<span class="label label-success"><?php echo $relevamiento_extintores->extintores_cargados; ?></span> Extintores cargados
										<?php if ($relevamiento_extintores->fecha_desde <= date('Y-m-d H:i:s')): ?>
											<?php if ($relevamiento_extintores->fecha_hasta >= date('Y-m-d H:i:s')): ?>
												<span class="pull-right">
													<a class="btn btn-xs btn-primary" href="extintores/extintor/ver/<?php echo "$relevamiento_extintores->id/$escuela->id"; ?>"><i class="fa fa-cogs"></i> Administrar</a>
												</span>
											<?php else: ?>
												<span class="pull-right">
													<a class="btn btn-xs btn-primary" href="extintores/extintor/ver/<?php echo "$relevamiento_extintores->id/$escuela->id"; ?>"><i class="fa fa-search"></i> Ver</a>
												</span>
											<?php endif; ?>
										<?php else: ?>
											<span class="pull-right">
												<a class="btn btn-xs btn-primary disabled" href="extintores/extintor/ver/<?php echo "$relevamiento_extintores->id/$escuela->id"; ?>"><i class="fa fa-ban"></i> Aún no disponible</a>
											</span>
										<?php endif; ?>
									</th>
								</tr>
								<tr>
									<th><span class="label label-danger"><?php echo $relevamiento_extintores->extintores_faltantes; ?></span> Extintores faltantes</th>
								</tr>
								<tr>
									<th><?php echo $relevamiento_extintores->observaciones; ?></th>
								</tr>
							</thead>
						</table>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<?php endif; ?>