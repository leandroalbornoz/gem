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
			<label>Escuelas de </label> <?php echo $escuela_grupo->descripcion; ?>
		</h1>
		<ol class="breadcrumb">
			<li class="active"><i class="fa fa-home"></i> Inicio</li>
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
				<div class="tab-content">
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
											<table class="table table-bordered table-condensed table-striped table-hover">
												<tr>
													<th>N°</th>
													<th>Nombre</th>
													<th></th>
												</tr>
												<?php foreach ($escuelas as $escuela): ?>
													<tr>
														<td><?php echo "$escuela->escuela_numero"; ?></td>
														<td><?php echo "$escuela->escuela_nombre"; ?></td>
														<td><a class="btn btn-xs" href="escuela/escritorio/<?php echo $escuela->escuela_id; ?>"><i class="fa fa-home"></i></a></td>
													</tr>
												<?php endforeach; ?>
											</table>
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
						<div class="col-xs-6"><!-- Carreras -->
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title">Carreras</h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
									</div>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-xs-12">
											<?php if (!empty($carreras)): ?>
												<table class="table table-bordered table-condensed table-striped table-hover">
													<tr>
														<th>Carrera</th>
														<th style="width: 80px;">Escuelas</th>
														<th style="width: 34px;"></th>
														<th style="width: 34px;"></th>
													</tr>
													<?php foreach ($carreras as $carrera): ?>
														<tr>
															<td><?php echo $carrera->descripcion; ?></td>
															<td class="text-center"><?php echo $carrera->escuelas; ?></td>
															<td><a class="btn btn-xs" href="escuela_carrera/espacios_curriculares/<?php echo $carrera->id; ?>"><i class="fa fa-search"></i></a></td>
															<td><a class="btn btn-xs" href="escuela_carrera/escuelas/<?php echo $carrera->id; ?>"><i class="fa fa-home"></i></a></td>
														</tr>
													<?php endforeach; ?>
												</table>
											<?php endif; ?>
										</div>
									</div>
								</div>
								<div class="box-footer">
									<a class="btn btn-primary" href="carrera/listar">
										<i class="fa fa-cogs" id="btn-carreras"></i> Administrar
									</a>
								</div>
							</div>
						</div><!-- Fin Carreras -->
					</div>
				</div>
			</div>
		</div>
</div>
</section>
</div>