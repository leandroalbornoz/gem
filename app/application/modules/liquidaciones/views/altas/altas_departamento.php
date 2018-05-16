<style>
	.table-striped > tbody > tr:nth-of-type(odd) {
		background-color: #dfdfdf;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Auditoría de Altas <?php echo $planilla_tipo->descripcion; ?> - <label class="label label-default"><?php echo empty($departamento) ? 'N/D' : $departamento->descripcion; ?></label> <label class="label label-default"><?php echo $mes; ?></label>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="liquidaciones/escritorio/index/<?php echo $mes; ?>">Auditoría Altas <?php echo $mes; ?></a></li>
			<li class="active"><?php echo empty($departamento) ? 'N/D' : $departamento->descripcion; ?></li>
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
					</div>
				</div>
				<div class="box box-primary">
					<div class="box-body">
						<?php echo $js_table_2; ?>
						<?php echo $html_table_2; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var altas_table;
	var altas_auditadas_table;
	function complete_altas_table() {
		agregar_filtros('altas_table', altas_table, 8);
	}
	function complete_altas_auditadas_table() {
		agregar_filtros('altas_auditadas_table', altas_auditadas_table, 8);
	}
	function altas_table_detalles(api, rowIdx, columns) {
		var html = table_detalles(api, rowIdx, columns);
		html += '<div id="altas_table_detalle_' + rowIdx + '"><i class="fa fa-spin fa-refresh"></i>Cargando altas...</div>';
		var escuela_id = api.row(rowIdx).data().id;
		$.ajax({
			type: 'GET',
			url: 'liquidaciones/altas/ajax_get_altas?',
			data: {
				escuela_id: escuela_id,
				planilla_tipo_id: <?php echo $planilla_tipo->id; ?>,
				mes: <?php echo $mes; ?>
			},
			dataType: 'json',
			success: function(result) {
				$('#altas_table_detalle_' + rowIdx).html(format(result));
			}
		});
		return html;
	}
	function altas_auditadas_table_detalles(api, rowIdx, columns) {
		var html = table_detalles(api, rowIdx, columns);
		html += '<div id="altas_auditadas_table_detalle_' + rowIdx + '"><i class="fa fa-spin fa-refresh"></i>Cargando altas...</div>';
		var escuela_id = api.row(rowIdx).data().id;
		$.ajax({
			type: 'GET',
			url: 'liquidaciones/altas/ajax_get_altas?',
			data: {
				escuela_id: escuela_id,
				planilla_tipo_id: <?php echo $planilla_tipo->id; ?>,
				mes: <?php echo $mes; ?>
			},
			dataType: 'json',
			success: function(result) {
				$('#altas_auditadas_table_detalle_' + rowIdx).html(format(result));
			}
		});
		return html;
	}
	function format(altas) {
		var html = '<table class="table table-condensed table-bordered table-hover" style="margin-bottom: 0;"><thead><tr class="bg-gray"><thead><tr class="bg-gray"><th class="text-center" colspan="7">Altas gem</th></td><tr><th>Persona</th><th>Alta</th><th>RegSal</th><th>Clase</th><th>Baja</th><th>Revista</th></tr></thead><tbody>';
		if (altas.length === 0) {
			html += '<tr><td colspan="7">No hay altas registradas por gem</td></tr>';
		} else {
			var len = altas.length;
			for (var i = 0; i < len; i++) {
				html += '<tr>';
				html += '<td>' + '<a target="_blank" href="persona/ver/' + altas[i].persona_id + '"><i class="fa fa-search"></i></a> ' + altas[i].cuil + ' ' + altas[i].persona + (altas[i].liquidacion === null ? '' : ' - Liquidación: ' + altas[i].liquidacion) + '</td>' +
								'<td>' + moment(altas[i].fecha_alta).format("DD/MM/YY") + '</td>' +
								'<td>' + altas[i].regimen + '</td>' +
								'<td>' + (altas[i].clase === '0000' ? altas[i].puntos : altas[i].clase) + '</td>' +
								'<td>' + (altas[i].baja === null ? '' : moment(altas[i].Baja).format("DD/MM/YY")) + '</td>' +
								'<td>' + altas[i].situacion_revista + '</td>';
				html += '</tr>';
			}
		}
		html += '</tbody></table>';
		return html;
	}
	function url_servicios(servicios_id) {
		var servicios_arr = servicios_id.split(',');
		var html = '';
		for (var i = 0; i < servicios_arr.length; i++) {
			html += '<a target="_blank" href="servicio/ver/' + servicios_arr[i] + '"><i class="fa fa-search"></i></a><br/>';
		}
		return html;
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