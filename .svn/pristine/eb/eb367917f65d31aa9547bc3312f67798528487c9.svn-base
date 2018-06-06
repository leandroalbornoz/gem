<style>
	.datepicker,.datepicker>datepicker-days>.table-condensed{
		margin:0 auto;
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
			<li><a href="servicio_altas/listar/<?php echo "$escuela->id/$mes_id"; ?>">Servicios</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="servicio/listar/<?php echo "$escuela->id/$mes_id"; ?>">
							<i class="fa fa-bookmark"></i> Servicios
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="servicio_altas/listar/<?php echo "$escuela->id/$mes_id"; ?>">
							<i class="fa fa-calendar-plus-o"></i> Altas
						</a>
						<a class="btn btn-app btn-app-zetta" href="servicio_novedad/listar/<?php echo "$escuela->id/$mes_id/0"; ?>">
							<i class="fa fa-calendar"></i> Novedades
						</a>
						<a class="btn btn-app btn-app-zetta" href="servicio_novedad/listar/<?php echo "$escuela->id/$mes_id/1"; ?>">
							<i class="fa fa-calendar"></i> Novedades a confirmar
						</a>
						<a class="btn btn-app btn-app-zetta" href="asisnov/index/<?php echo "$escuela->id/$mes_id"; ?>">
							<i class="fa fa-print"></i> Asis y Nov
						</a>
						<div class="btn-group pull-right" role="group" style="margin-left: 5px;">
							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-external-link"></i> Otros accesos <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								<li><a class="dropdown-item btn-default"  href="cargo/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-users"></i> Cargos</a></li>
								<li><a class="dropdown-item btn-default" href="alumno/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-users"></i> Alumnos</a></li>
								<li><a class="dropdown-item btn-default" href="escuela_carrera/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-book"></i> Carreras</a></li>
								<li><a class="dropdown-item btn-default" href="division/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-tasks"></i> Cursos y Divisiones</a></li>
							</ul>
						</div>
						<hr style="margin: 10px 0;">
						<p>Para dar de alta un servicio que es un reemplazo de un servicio activo se deberá realizar desde la lista de Servicios, cargando antes la novedad correspondiente sobre el servicio a reemplazar.</p>
						<p>Para dar de alta un servicio que <b>no</b> es un reemplazo de un servicio activo (Titular, Reemplazo en cargo vacante) se deberá realizar desde la lista de Cargos, seleccionando Agregar servicio sobre el cargo que corresponda (creando antes el cargo si correspondiera).</p>
						<hr style="margin: 10px 0;">
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<?php echo form_open("servicio_altas/cambiar_mes/$escuela->id/$mes_id"); ?>
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
			$('#div_servicio_baja').append('<a id="abrir_m"  href="servicio_altas/modal_baja/<?php echo $mes_id . "/" . $servicio_id . "/" . $escuela->id . "/" . $fecha_baja_s; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"></a>');
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
		agregar_filtros('servicio_table', servicio_table, 12);
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
	function format(servicio_id, novedades) {
		if (novedades.length === 0) {
			return "No hay novedades asignadas al servicio";
		}
		var len = novedades.length;
		var html = '<table class="table table-condensed table-bordered table-hover" style="margin-bottom: 0;"><thead><tr class="bg-gray"><th class="text-center" colspan="7">Novedades</th></td><tr><th>Articulo</th><th>Descripción</th><th>Desde</th><th>Hasta</th><th>Días</th><th>Obligaciones</th><th>Estado</th></tr></thead><tbody>';
		var dias = 0;
		var obligaciones = 0;
		var signo = 0;
		for (var i = 0; i < len; i++) {
			if (novedades[i].novedad_tipo_id === '1') {
				novedades[i].articulo = 'Alta';
				signo = 1;
			} else {
				novedades[i].articulo = ((novedades[i].articulo) + "-" + novedades[i].inciso);
				signo = -1;
			}
			if (novedades[i].dias !== null) {
				dias += parseFloat(novedades[i].dias) * signo;
			} else {
				novedades[i].dias = '';
			}
			if (novedades[i].obligaciones !== null) {
				obligaciones += parseFloat(novedades[i].obligaciones) * signo;
			} else {
				novedades[i].obligaciones = '';
			}
			html += '<tr><td>' + novedades[i].articulo + '</td><td>' + novedades[i].descripcion_corta +
							'<td>' + moment(novedades[i].fecha_desde).format("DD/MM/YY") + '</td><td>' + (novedades[i].id == 1 ? '' : moment(novedades[i].fecha_hasta).format('DD/MM/YY')) + '</td></td><td>' + novedades[i].dias +
							'</td><td>' + novedades[i].obligaciones + '</td><td>' + novedades[i].estado + '</td></tr>';
		}
		html += '<tr><td colspan="4">Días / Obligaciones Cumplidas</td><td>' + dias.toFixed(1) + '</td><td>' + obligaciones.toFixed(2) + '</td><td></td></tr>';
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