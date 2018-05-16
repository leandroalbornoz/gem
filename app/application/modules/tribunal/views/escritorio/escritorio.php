<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Tribunal de cuentas
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo "tribunal/escritorio/listar/"; ?>">Escuelas</a></li>
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
						<h3 class="box-title"> Escuelas</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"> Referentes</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<a class="btn bg-green btn-app btn-app-zetta" href="tribunal/escritorio/excel_referentes" title="Exportar Excel">
							<i class="fa fa-file-excel-o" id=""></i> Exportar excel
						</a>
						<hr style="margin: 10px 0;">
						<?php echo $js_table_referentes; ?>
						<?php echo $html_table_referentes; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var escuela_table;
	var referentes_table;
	function complete_escuela_table() {
		agregar_filtros('escuela_table', escuela_table, 11);
	}
	function complete_referentes_table() {
		agregar_filtros('referentes_table', referentes_table, 9);
	}
</script>