<style>
	.datepicker,.datepicker>datepicker-days>.table-condensed{
		margin:0 auto;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Alumnos de <?php echo "" ?><label class="label label-default"></label>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href=""> <?php echo "Educación Domiciliaria y Hospitalaria"; ?></a></li>
			<li><a href="alumno_derivacion/listar/<?php echo $linea->id; ?>">Alumnos</a></li>
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
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="alumno_derivacion/listar/<?php echo $linea->id; ?>">
							<i class="fa fa-users"></i> Alumnos
						</a>
						<a class="btn btn-app btn-app-zetta" href="alumno_derivacion/excel/<?php echo $linea->id; ?>">
							<i class="fa fa-file-excel-o"></i> Exportar Excel
						</a>
						<hr style="margin: 10px 0;">
						<div class="content">
							<table class="table table-striped">
                <tbody>
									<tr>
										<td>Total de alumnos con derivación Hospitalaria/Domiciliaria:</td>
										<td><span class="badge bg-blue"><?php echo isset($cant_alumnos) ? "$cant_alumnos->cantidad" : ""; ?></span></td>
									</tr>
									<tr>
										<td>Alumnos con alta de derivación Hospitalaria/Domiciliaria:</td>
										<td><span class="badge bg-green"><?php echo isset($cant_alumnos_alta) ? "$cant_alumnos_alta->cantidad" : ""; ?></span></td>
									</tr>
									<tr>
										<td>Alumnos retirados de derivación Hospitalaria/Domiciliaria:</td>
										<td><span class="badge bg-red"><?php echo isset($cant_alumnos_baja) ? "$cant_alumnos_baja->cantidad" : ""; ?></span></td>
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
	var alumno_derivacion_table;
	function complete_alumno_derivacion_table() {
		agregar_filtros('alumno_derivacion_table', alumno_derivacion_table, 8);
	}
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#datepicker").datepicker({
			format: "dd/mm/yyyy",
			startView: "years",
			minViewMode: "years",
			language: 'es',
			todayHighlight: false
		});
		$("#datepicker").on("changeDate", function(event) {
			$("#ciclo_lectivo").val($("#datepicker").datepicker('getFormattedDate'))
		});
	});
</script>