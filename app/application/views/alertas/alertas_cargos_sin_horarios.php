<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Cargos sin horarios de Esc. <?= $escuela->nombre_corto; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id ?>"><?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="cargo/listar/<?= $escuela->id; ?>">Cargos</a></li>
			<li class="active">Cargos sin horarios</li>
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
						<?php if ($edicion): ?>
							<a class="btn bg-blue btn-app btn-app-zetta" href="alertas/cargos_sin_horarios_masivo/<?php echo $escuela->id; ?>">
								<i class="fa fa-plus"></i> Cargar Horario Masivo
							</a>
						<?php endif; ?>
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var cargo_table;
	function complete_cargo_table() {
		agregar_filtros('cargo_table', cargo_table, 9);
	}
	function cargo_table_detalles(api, rowIdx, columns) {
		var html = $.map(columns, function(col, i) {
			return col.hidden ?
							'<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
							'<td>' + col.title + ':' + '</td> ' +
							'<td>' + col.data + '</td>' +
							'</tr>' :
							'';
		}).join('');
		var cargo_id = api.row(rowIdx).data().id;
		html = $('<table class="table table-condensed table-bordered table-hover"/>').append(html).prop('outerHTML');
		html += '<div id="cargo_table_detalle_' + rowIdx + '"><i class="fa fa-spin fa-refresh"></i>Cargando servicios...</div>';
		$.ajax({
			type: 'GET',
			url: 'ajax/get_servicios?',
			data: 'cargo_id=' + cargo_id,
			dataType: 'json',
			success: function(result) {
				$('#cargo_table_detalle_' + rowIdx).html(format(cargo_id, result));
			}
		});
		return html;
	}
	function format(cargo_id, servicios) {
		if (servicios.length === 0) {
			return "No hay servicios asignados al cargo";
		}
		var len = servicios.length;
		var html = '<table class="table table-condensed table-bordered table-hover" style="margin-bottom: 0;"><thead><tr class="bg-gray"><th class="text-center" colspan="5">Servicio</th><th class="text-center" colspan="3">Novedades</th><th class="text-center" colspan="6">Función</th></tr><tr><th>Liquidación</th><th>Nombre</th><th>S.R.</th><th>Fecha Alta</th><th>Fecha Baja</th><th>Art.</th><th>Desde</th><th>Hasta</th><th>Detalle</th><th>Destino</th><th>Norma</th><th>Tarea</th><th>Hs.</th><th>Desde</th></tr></thead><tbody>';
		for (var i = 0; i < len; i++) {
			html += '<tr><td>' + servicios[i].liquidacion + '</td>' +
							'<td>' + servicios[i].apellido + ', ' + servicios[i].nombre + '</td>' +
							'<td>' + servicios[i].situacion_revista + '</td>' +
							'<td>' + (!servicios[i].fecha_alta ? '' : moment(servicios[i].fecha_alta).format('DD/MM/YYYY')) + '</td>' +
							'<td>' + (!servicios[i].fecha_baja ? '' : moment(servicios[i].fecha_baja).format('DD/MM/YYYY')) + '</td>' +
							'<td>' + (!servicios[i].novedad_articulo ? '' : servicios[i].novedad_articulo) + '</td>' +
							'<td>' + (!servicios[i].novedad_desde ? '' : servicios[i].novedad_desde) + '</td>' +
							'<td>' + (!servicios[i].novedad_hasta ? '' : servicios[i].novedad_hasta) + '</td>' +
							'<td>' + (!servicios[i].funcion_detalle ? '' : servicios[i].funcion_detalle) + '</td>' +
							'<td>' + (!servicios[i].funcion_destino ? '' : servicios[i].funcion_destino) + '</td>' +
							'<td>' + (!servicios[i].funcion_norma ? '' : servicios[i].funcion_norma) + '</td>' +
							'<td>' + (!servicios[i].funcion_tarea ? '' : servicios[i].funcion_tarea) + '</td>' +
							'<td>' + (!servicios[i].funcion_carga_horaria ? '' : servicios[i].funcion_carga_horaria) + '</td>' +
							'<td>' + (!servicios[i].funcion_desde ? '' : moment(servicios[i].funcion_desde).format('DD/MM/YY')) + '</td></tr>';
		}
		return html + '</tbody></table>';
	}
</script>