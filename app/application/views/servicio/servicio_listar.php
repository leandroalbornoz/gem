<style>
	.datepicker,.datepicker>datepicker-days>.table-condensed{
		margin:0 auto;
	}
	#servicio_table>tbody>tr.child>div{
		border: #000 solid medium;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Servicios de <?php echo "Esc. $escuela->nombre_corto" ?> - <label class="label label-default"><?php echo $mes; ?></label>
			<a class="btn btn-xs btn-primary" data-toggle="modal" data-target="#datepicker_modal"><i class="fa fa-refresh"></i> Cambiar</a>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="servicio/listar/<?php echo "$escuela->id/$mes_id"; ?>">Servicios</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="escuela/ver/<?php echo $escuela->id; ?>">
							<i class="fa fa-search"></i> Ver escuela
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="servicio/listar/<?php echo "$escuela->id/$mes_id"; ?>">
							<i class="fa fa-bookmark"></i> Servicios
						</a>
						<a class="btn btn-app btn-app-zetta" href="servicio_altas/listar/<?php echo "$escuela->id/$mes_id"; ?>">
							<i class="fa fa-calendar-plus-o"></i> Altas
						</a>
						<a class="btn btn-app btn-app-zetta" href="servicio/excel/<?php echo "$escuela->id/$mes_id"; ?>">
							<i class="fa fa-file-excel-o"></i> Exportar Excel
						</a>
						<a class="btn btn-app btn-app-zetta" href="servicio_novedad/listar/<?php echo "$escuela->id/$mes_id/0"; ?>">
							<i class="fa fa-calendar"></i> Novedades
						</a>
						<a class="btn btn-app btn-app-zetta" href="servicio_novedad/listar/<?php echo "$escuela->id/$mes_id/1"; ?>">
							<i class="fa fa-calendar"></i> Novedades a confirmar
						</a>
									
						<div class="btn-group pull-right" role="group">
							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-external-link"></i> Otros accesos <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								<li><a class="dropdown-item btn-default" href="cargo/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-users"></i> Cargos</a></li>
								<li><a class="dropdown-item btn-default" href="llamados/llamado/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-phone"></i> Llamados</a></li>
								<li><a class="dropdown-item btn-default" href="alumno/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-users"></i> Alumnos</a></li>
								<li><a class="dropdown-item btn-default" href="division/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-tasks"></i> Cursos y Divisiones</a></li>
								<li><a class="dropdown-item btn-default" href="escuela_carrera/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-book"></i> Carreras</a></li>
								<li><a class="dropdown-item btn-default" href="asisnov/index/<?php echo "$escuela->id/$mes_id"; ?>">
							<i class="fa fa-fw fa-print"></i> Asis y Nov</a></li>
							</ul>
						</div>
						<hr style="margin: 10px 0;">
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
						<hr style="margin: 10px 0;">
						<h4 class="text-center">Otros servicios que cumplen función en la escuela</h4>
						<?php echo $js_table_o; ?>
						<?php echo $html_table_o; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<?php echo form_open("servicio/cambiar_mes/$escuela->id/$mes_id"); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Cambiar Mes</h4>
			</div>
			<div style="display:none;" id="div_servicio_baja"></div>

			<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-12" style="text-align:center;">
						<div id="datepicker" data-date="<?php echo $fecha; ?>"></div>
						<input type="hidden" name="mes" id="mes" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
				<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Seleccionar'), 'Seleccionar'); ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<script>
<?php if (isset($abrir_modal) && !empty($abrir_modal)): ?>
		$(document).ready(function() {
			$('#div_servicio_baja').append('<a id="abrir_m"  href="servicio/modal_baja/<?php echo $mes_id . "/" . $servicio_id . "/" . $escuela->id . "/" . $fecha_baja_s; ?>" ></a>');
			setTimeout(function() {
				$('#abrir_m').click();
			}, 500);
		});
<?php endif; ?>
</script>
<script>
	var servicio_table;
	var servicio_f_table;
	function complete_servicio_table() {
		agregar_filtros('servicio_table', servicio_table, 11);
	}
	function servicio_table_detalles(api, rowIdx, columns) {
		var html = table_detalles(api, rowIdx, columns);
		html += '<div id="servicio_table_detalle_' + rowIdx + '"><i class="fa fa-spin fa-refresh"></i>Cargando novedades...</div>';
		var servicio_id = api.row(rowIdx).data().id;
		$.ajax({
			type: 'GET',
			url: 'ajax/get_novedades?',
			data: {
				servicio_id: servicio_id,
				mes: <?php echo $mes_id; ?>
			},
			dataType: 'json',
			success: function(result) {
				$('#servicio_table_detalle_' + rowIdx).html(format(servicio_id, result));
			}
		});
		return html;
	}
	function complete_servicio_f_table() {
		agregar_filtros('servicio_f_table', servicio_f_table, 10);
	}
	function servicio_f_table_detalles(api, rowIdx, columns) {
		var html = table_detalles(api, rowIdx, columns);
		html += '<div id="servicio_f_table_detalle_' + rowIdx + '"><i class="fa fa-spin fa-refresh"></i>Cargando novedades...</div>';
		var servicio_id = api.row(rowIdx).data().id;
		$.ajax({
			type: 'GET',
			url: 'ajax/get_novedades?',
			data: {
				tipo: 'funcion',
				servicio_id: servicio_id,
				mes: <?php echo $mes_id; ?>
			},
			dataType: 'json',
			success: function(result) {
				$('#servicio_f_table_detalle_' + rowIdx).html(format(servicio_id, result));
			}
		});
		return html;
	}
	function format(servicio_id, novedades) {
		if (novedades.length === 0) {
			return "No hay novedades asignadas al servicio";
		}
		var len = novedades.length;
		var html = '<table class="table table-condensed table-bordered table-hover" style="margin-bottom: 0;"><thead><tr class="bg-gray"><th class="text-center" colspan="7">Novedades</th></td><tr><th>Articulo</th><th>Descripción</th><th>Desde</th><th>Hasta</th><th>Días</th><th>Obligaciones</th><th>Estado</th></tr></thead><tbody>';
		for (var i = 0; i < len; i++) {
			novedades[i].articulo = (novedades[i].id != 1) ? ((novedades[i].articulo) + "-" + novedades[i].inciso) : "Alta";
			novedades[i].dias = (novedades[i].id != 1) ? ((novedades[i].dias !== null) ? novedades[i].dias : "") : "";
			novedades[i].obligaciones = (novedades[i].id != 1) ? ((novedades[i].obligaciones !== null) ? novedades[i].obligaciones : "") : "";
			html += '<tr><td>' + novedades[i].articulo + '</td><td>' + novedades[i].descripcion_corta +
							'<td>' + moment(novedades[i].fecha_desde).format("DD/MM/YY") + '</td><td>' + (novedades[i].id == 1 ? '' : moment(novedades[i].fecha_hasta).format('DD/MM/YY')) + '</td></td><td>' + novedades[i].dias +
							'</td><td>' + novedades[i].obligaciones + '</td><td>' + novedades[i].estado + '</td></tr>';
		}
		return html + '</tbody></table>';
	}
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#datepicker").datepicker({
			format: "dd/mm/yyyy",
			startView: "months",
			minViewMode: "months",
			language: 'es',
			todayHighlight: false
		});
		$("#datepicker").on("changeDate", function(event) {
			$("#mes").val($("#datepicker").datepicker('getFormattedDate'))
		});
	});
</script>