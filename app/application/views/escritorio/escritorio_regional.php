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
			<label>Regional</label> <?php echo $regional->descripcion; ?>
		</h1>
		<ol class="breadcrumb">
			<li class="active"><i class="fa fa-home"></i> Inicio</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<?php if (!empty($niveles)): ?>
					<ul class="nav nav-tabs" role="tablist">
						<?php $tab_class = 'active'; ?>
						<?php foreach ($niveles as $nivel): ?>
							<li role="presentation" class="<?= $tab_class; ?>"><a href="#nivel_<?= $nivel->id; ?>" aria-controls="<?= strtolower(str_replace(' ', '_', $nivel->descripcion)); ?>" role="tab" data-toggle="tab">Nivel <?= $nivel->descripcion; ?></a></li>
							<?php $tab_class = ''; ?>
						<?php endforeach; ?>
					</ul>
					<div class="tab-content">
						<?php $tab_class = 'active'; ?>
						<?php foreach ($niveles as $nivel): ?>
							<div role="tabpanel" class="tab-pane <?= $tab_class; ?>" id="nivel_<?= $nivel->id; ?>">
								<?php $tab_class = ''; ?>
								<div class="row">
									<div class="col-xs-6"><!-- Escuelas -->
										<div class="box box-primary">
											<div class="box-header with-border">
												<h3 class="box-title">Escuelas</h3>
												<div class="box-tools pull-right">
													<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
												</div>
											</div>
											<div class="box-body">
												<div class="row">
													<div class="col-xs-12">
														<?php if (!empty($nivel->escuelas)): ?>
															<table class="table table-bordered table-condensed table-striped table-hover">
																<tr>
																	<th>N°</th>
																	<th>Escuela</th>
																	<th style="width: 60px;">Cargos</th>
																	<th style="width: 34px;"></th>
																</tr>
																<?php foreach ($nivel->escuelas as $escuela): ?>
																	<tr>
																		<td><?php echo "$escuela->nombre_corto"; ?></td>
																		<td><?php echo "$escuela->nombre"; ?></td>
																		<td class="text-center"><?php echo "$escuela->cargos"; ?></td>
																		<td><a class="btn btn-xs" href="escuela/ver/<?php echo $escuela->id; ?>"><i class="fa fa-search"></i></a></td>
																	</tr>
																<?php endforeach; ?>
															</table>
														<?php endif; ?>
													</div>
												</div>
											</div>
											<div class="box-footer">
												<a class="btn btn-primary" href="escuela/listar">
													<i class="fa fa-cogs" id="btn-escuelas"></i> Administrar
												</a>
											</div>
										</div>
									</div><!-- Fin Escuelas -->
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
</div>