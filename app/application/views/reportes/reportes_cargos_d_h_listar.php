<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Cargos con condición de cargo Hospitalario/Domiciliario<label class="label label-default"></label>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href=""> <?php echo "Educación Domiciliaria y Hospitalaria"; ?></a></li>
			<li><a href="reportes/cargos_d_h_listar">Cargos Hopistalario/Domiciliario</a></li>
			<li class="active"></li>
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
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta" href="">
							<i class="fa fa-search"></i> Ver escritorio
						</a>
						<hr style="margin: 10px 0;">
						<div class="content">
							<table class="table table-striped">
                <tbody>
									<tr>
										<td>Total de cargos con condición de cargo Hospitalario/Domiciliario:</td>
										<td><span class="badge bg-blue"><?php echo isset($cant_cargos) ? "$cant_cargos->cantidad" : ""; ?></span></td>
									</tr>
									<tr>
										<td>Total de escuelas con cargos con condicion Hospitalario/Domiciliario:</td>
										<td><span class="badge bg-green"><?php echo isset($cant_escuelas) ? "$cant_escuelas->cantidad" : ""; ?></span></td>
									</tr>
								</tbody>
							</table>
							<hr>
							<?php echo $js_table; ?>
							<?php echo $html_table; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var cargos_d_h_table;
	function complete_cargos_d_h_table() {
		agregar_filtros('cargos_d_h_table', cargos_d_h_table, 14);
	}
</script>
