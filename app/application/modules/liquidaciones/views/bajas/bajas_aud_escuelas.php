<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Auditoría de Bajas <?php echo $planilla_tipo->descripcion; ?> - <label class="label label-default"><?php echo $mes; ?></label>
			<a class="btn btn-xs btn-primary" data-toggle="modal" data-target="#datepicker_modal"><i class="fa fa-refresh"></i> Cambiar</a>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li>Bajas</li>
			<li class="active">Auditoría</li>
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
			</div>
		</div>
	</section>
</div>
<script>
	var bajas_table;
	function complete_bajas_table() {
		agregar_filtros('bajas_table', bajas_table, 8);
	}
	function bajas_table_detalles(api, rowIdx, columns) {
		var html = table_detalles(api, rowIdx, columns);
		html += '<div id="bajas_table_detalle_' + rowIdx + '"><i class="fa fa-spin fa-refresh"></i>Cargando bajas...</div>';
		var escuela_id = api.row(rowIdx).data().id;
		$.ajax({
			type: 'GET',
			url: 'liquidaciones/bajas/ajax_get_bajas?',
			data: {
				escuela_id: escuela_id,
				planilla_tipo_id: <?php echo $planilla_tipo->id; ?>,
				mes: <?php echo $mes; ?>
			},
			dataType: 'json',
			success: function(result) {
				$('#bajas_table_detalle_' + rowIdx).html(format(result));
			}
		});
		return html;
	}
	function format(bajas) {
		var html = '<table class="table table-condensed table-bordered table-hover" style="margin-bottom: 0;"><thead><tr class="bg-gray"><thead><tr class="bg-gray"><th class="text-center" colspan="7">Bajas gem</th></td><tr><th>Persona</th><th>Alta</th><th>RegSal</th><th>Clase</th><th>Baja</th><th>Revista</th></tr></thead><tbody>';
		if (bajas.length === 0) {
			html += '<tr><td colspan="6">No hay bajas registradas por gem</td></tr>';
		} else {
			var len = bajas.length;
			for (var i = 0; i < len; i++) {
				html += '<tr>';
				html += '<td>' + '<a target="_blank" href="persona/ver/' + bajas[i].persona_id + '"><i class="fa fa-search"></i></a> ' + bajas[i].cuil + ' ' + bajas[i].persona + (bajas[i].liquidacion === null ? '' : ' - Liquidación: ' + bajas[i].liquidacion) + '</td>' +
								'<td>' + moment(bajas[i].fecha_alta).format("DD/MM/YY") + '</td>' +
								'<td>' + bajas[i].regimen + '</td>' +
								'<td>' + (bajas[i].clase === '0000' ? bajas[i].puntos : bajas[i].clase) + '</td>' +
								'<td>' + (bajas[i].baja === null ? '' : moment(bajas[i].Baja).format("DD/MM/YY")) + '</td>' +
								'<td>' + bajas[i].situacion_revista + '</td>';
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