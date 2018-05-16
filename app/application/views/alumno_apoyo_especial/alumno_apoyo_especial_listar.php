<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Alumnos con apoyo de modalidad especial
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li class="active">Alumno apoyo especial</li>
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
						<a class="btn btn-app btn-app-zetta" href="alumno_apoyo_especial/excel_reporte">
							<i class="fa fa-file-excel-o text-green"></i> Exportar
						</a>
						<hr style="margin: 10px 0;">
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var apoyo_table;
	function complete_alumno_apoyo_table() {
		agregar_filtros('apoyo_table', apoyo_table, 7);
	}
</script>