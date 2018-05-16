<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Escuelas
			<?php echo empty($nivel) ? '' : " - Nivel $nivel->descripcion"; ?>
			<?php echo empty($supervision) ? '' : " - Supervisión $supervision->nombre"; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Escuelas</a></li>
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
					<div class="box-body">
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
						<a class="btn btn-success pull-left" href="operativo_evaluar/evaluar_operativo/reporte_final">
							<i class="fa fa-file-excel-o"></i> Reporte operativo Leer y Escribir 2017.
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var escuela_table;
	function complete_escuela_table() {
		agregar_filtros('escuela_table', escuela_table, 11);
	}
</script>

