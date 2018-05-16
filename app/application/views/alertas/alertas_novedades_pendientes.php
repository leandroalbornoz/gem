<div class="content-wrapper">
	<section class="content-header">
		<h1>Novedades pendientes de <?php echo "Esc. $escuela->nombre_corto" ?></h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li class="active">Novedades pendientes</li>
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
						<hr style="margin: 10px 0;">
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
						<hr style="margin: 10px 0;">
						<h4 class="text-center">Novedades de otros servicios que cumplen función en la escuela</h4>
						<?php echo $js_table_o; ?>
						<?php echo $html_table_o; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var servicio_novedad_table;
	var servicio_novedad_o_table;
	function complete_servicio_novedad_table() {
		agregar_filtros('servicio_novedad_table', servicio_novedad_table, 14);
	}
	function complete_servicio_novedad_o_table() {
		agregar_filtros('servicio_novedad_o_table', servicio_novedad_o_table, 12);
	}
</script>