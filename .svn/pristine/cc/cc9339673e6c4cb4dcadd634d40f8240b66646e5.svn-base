<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_reubicar_cargos')); ?>
<style>
	.reubicar-origen, .reubicar-origen-exp{
		background-color: #00a65a;
		color:white;
		width:80px;
		cursor: pointer;
		text-align: center;
	}
	.reubicar-destino, .reubicar-destino-exp{
		background-color: #dd4b39;
		color:white;
		width:80px;
		cursor: pointer;
		text-align: center;
	}
	.reubicar-origen::-webkit-input-placeholder,.reubicar-destino::-webkit-input-placeholder,.reubicar-origen-exp::-webkit-input-placeholder,.reubicar-destino-exp::-webkit-input-placeholder {
		color: #eee;
		font-weight: bold;
	}
	.reubicar-origen.active,.reubicar-destino.active {
		outline: blue medium solid;
	}
	#tabla_v>thead>tr>th{
		text-align: center;
		padding-right: 0;
	}
</style>
<script>
	var origen = '';
	var tabla = '';
	var destino = '';
	var valores = '';
	var tabla = '';
	var mover_a = new Array();
	var traer_de = new Array();
	$(document).ready(function() {
		$('#datepicker').datepicker({
			format: 'dd/mm/yyyy',
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true
		});
		$("#seleccionar").on('click', function() {
			if (tabla == '') {
				$('#aviso').remove();
				$('#tabla').append('<h5 id="aviso">Ningún servicio seleccionado para ser reubicado.</h5>');
				$('#guardar').attr('disabled', true);
			} else {
				$('#aviso').remove();
			}
		});
		$(document).on('click', '.reubicar-destino', function() {
			$('.reubicar-destino').removeClass('active');
			origen = $(this).data('id');
			if (destino !== '') {
				reubicar_cargo();
			} else {
				$(this).addClass('active');
			}
		});
		$(document).on('click', '.reubicar-origen', function() {
			$('.reubicar-origen').removeClass('active');
			destino = $(this).data('id');
			if (origen !== '') {
				reubicar_cargo();
			} else {
				$(this).addClass('active');
			}
		});

		function reubicar_cargo() {
			$('.reubicar-origen').removeClass('active');
			$('.reubicar-destino').removeClass('active');
			if (origen == destino) {
				origen = '';
				destino = '';
				return;
			}
			$('input.reubicar-destino').filter(function() {
				return this.value == destino;
			}).val('');
			$('input.reubicar-origen').filter(function() {
				return this.value == origen;
			}).val('');
			$('input.reubicar-origen[name="origen[' + destino + ']"]').val(origen);
			$('input.reubicar-destino[name="destino[' + origen + ']"]').val(destino);
			$('.traer-de-' + origen).remove();
			$('.mover-a-' + destino).remove();
			$('#tabla_v>tbody').append('<tr class="traer-de-' + origen + ' mover-a-' + destino + '"><td>' + origen + '</td><td>' + destino + '</td></tr>');
			if ($('.mover-a-' + origen).length === 0) {
				$('#tabla_errores>tbody').append('<tr class="mover-a-' + origen + '"><td>' + origen + '</td><td>Debe asignar servicios a este cargo</td></tr>');
			}
			if ($('.traer-de-' + destino).length === 0) {
				$('#tabla_errores>tbody').append('<tr class="traer-de-' + destino + '"><td>' + destino + '</td><td>Debe retirar los servicios a este cargo</td></tr>');
			}
			armar_tabla();
			origen = '';
			destino = '';
		}

		function armar_tabla() {
			$('#tabla_v').remove();
			var boton = 0;
			tabla = "<table class='table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline' id='tabla_v' style='text-align: center;'><thead><tr style='background-color: #eee;'><th>Traer servicios de</th><th></th><th>Cargo</th><th></th><th>Mover servicios a</th></tr></thead><body>";
			$('input.reubicar-destino').each(function() {
				if ($(this).val() !== '') {
					if ($('input.reubicar-origen[name="origen[' + $(this).data('id') + ']"]').val() !== '') {
						tabla += '<tr><td>' + $('input.reubicar-origen[name="origen[' + $(this).data('id') + ']"]').val() + '</td><td><i class="fa fa-arrow-right"></i></td><td><b>' + $(this).data('id') + '</td><td><i class="fa fa-arrow-right"></i></td><td>' + $(this).val() + '</td></tr>';

					} else {
						tabla += '<tr><td> <i class="fa fa-times" style="color:red;"></i> Falta asignar</td><td><i class="fa fa-arrow-right"></i></td><td><b>' + $(this).data('id') + '</td><td><i class="fa fa-arrow-right"></i></td><td>' + $(this).val() + '</td></tr>';
						boton++;
					}
				}
			});
			$('input.reubicar-origen').each(function() {
				if ($(this).val() !== '') {
					if ($('input.reubicar-destino[name="destino[' + $(this).data('id') + ']"]').val() === '') {
						tabla += '<tr><td>' + $(this).val() + '</td><td><i class="fa fa-arrow-right"></i></td><td><b>' + $(this).data('id') + '</td><td><i class="fa fa-arrow-right"></i></td><td><i class="fa fa-times" style="color:red;"></i> Falta asignar</td></tr>';
						boton++;
					}
				}
			});
			if (boton == 0) {
				$('#guardar').attr('disabled', false);
				$('#campo_motivo').attr('hidden', false);
				$('#campo_fecha_hasta').attr('hidden', false);
			} else {
				$('#guardar').attr('disabled', true);
				$('#campo_motivo').attr('hidden', true);
				$('#campo_fecha_hasta').attr('hidden', true);
			}
			tabla += "</body></table>";
			$('#tabla').append(tabla);
		}
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Reubicar servicios de Cargos de Esc. <?= $escuela->nombre_corto; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id; ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="cargo/listar/<?= $escuela->id; ?>">Cargos</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="cargo/listar/<?php echo $escuela->id; ?>">
							<i class="fa fa-users"></i> Cargos
						</a>
						<?php if ($edicion): ?>
							<a class="btn btn-app btn-app-zetta" href="cargo/unificar/<?php echo $escuela->id; ?>">
								<i class="fa fa-chain"></i> Unificar
							</a>
							<a class="btn btn-app btn-app-zetta" href="cargo/mover/<?php echo $escuela->id; ?>">
								<i class="fa fa-share"></i> Mover a anexo
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="cargo/reubicar_servicios/<?php echo $escuela->id; ?>">
							<i class="fa fa-exchange"></i> Reubicar servicios
						</a>
						<a class="btn btn-app bg-default btn-app-zetta" target="_blank" href="cargo/reporte/<?php echo $escuela->id; ?>">
							<i class="fa fa-print"></i> Reporte Planta
						</a>
						<div class="btn-group pull-right" role="group" style="margin-left: 5px;">
							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-external-link"></i> Otros accesos <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								<li><a class="dropdown-item btn-default" href="servicio/listar/<?php echo $escuela->id . '/'; ?>"><i class="fa  fa-fw fa-bookmark"></i>Servicios</a></li>
								<li><a class="dropdown-item btn-default" href="division/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-tasks"></i> Cursos y Divisiones</a></li>
							</ul>
						</div>
						<hr style="margin: 10px 0;">
						<h4>Desde aquí (Cargos) podrá reubicar al personal de su escuela (Servicios) en el Cargo a ocupar</h4>
						<h5>¿Cómo realizar estos movimientos de personal?</h5>
						<ol>
							<li>Seleccione régimen y/o horas a reubicar</li>
							<li>Hacer clic en <input style="cursor: auto" class="text-sm reubicar-destino-exp" placeholder="Mover a" type="text" readonly> en la fila del cargo original desde donde debe REUBICAR el Servicio (Persona)</li>
							<li>Hacer clic en <input style="cursor: auto" class="text-sm reubicar-origen-exp" placeholder="Traer de" type="text" readonly> en la fila del cargo de destino donde REUBICARÁ el Servicio (Persona)</li>
							<li>Hacer clic en Realizar Cambios</li>
						</ol>
						<h5><b>Importante: Cada CARGO tiene asignado un nº de identificación. Para REALIZAR LOS CAMBIOS de la REUBICACIÓN, no podrá quedar ningún servicio sin un cargo asignado. Debe cerrar el circuito.</b></h5>
						<div class="row">
							<div class="col-sm-12">

							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<?php echo form_dropdown(array('class' => 'form-control', 'style' => 'width:100%;', 'id' => 'filtro_regimen'), $array_regimen); ?>
							</div>
							<div class="form-group col-md-3">
								<input class="form-control" type="number" placeholder="Horas" value="0" id="filtro_horas">
							</div>
						</div>
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
					<div class="box-footer">
						<button class="btn btn-primary pull-right" type="button" data-toggle="modal" data-target="#modal_reubicar" data-remote="false" title="Realizar cambios" id="seleccionar">Realizar cambios</button>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="modal_reubicar"  role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Reubicación de servicios</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-sm-6" id="campo_fecha_hasta" hidden>
						<?php echo $fields['fecha_hasta']['label']; ?>
						<div class="input-group date" id="datepicker">
							<input type="text" class="form-control dateFormat" name="fecha_hasta" required id="fecha_hasta" value="" required/>
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</div>
						</div>			
					</div>
					<div class="form-group col-sm-6" id="campo_motivo" hidden>
						<?php echo $fields['motivo']['label']; ?>
						<?php echo $fields['motivo']['form']; ?>
					</div>
					<div class="form-group col-md-12">
						<div id="tabla">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
				<button class="btn btn-primary pull-right" type="submit" id="guardar" title="Guardar">Guardar</button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>

<script>
	$(document).ready(function() {
		var xhr_regimen;
		var select_regimen, $select_regimen;
		$('#fitro_horas').attr('readonly', true);
		$('#fitro_horas').val(0);
		if ($('select#filtro_regimen').length > 0) {
			$select_regimen = $('select#filtro_regimen').selectize({
				onChange: actualizar_carga_horaria
			});
			select_regimen = $select_regimen[0].selectize;
			if (select_regimen.getValue() !== '') {
				actualizar_carga_horaria();
			}
		}

		function actualizar_carga_horaria() {
			xhr_regimen && xhr_regimen.abort();
			var regimen_id = select_regimen.getValue();
			if (regimen_id !== '') {
				xhr_regimen = $.ajax({
					url: 'ajax/get_tipo_cargo/' + regimen_id,
					dataType: 'json',
					success: function(results) {
						if (results === 'Hora') {
							$('#filtro_horas').attr('readonly', false);
						} else {
							$('#filtro_horas').attr('readonly', true);
							$('#filtro_horas').val(0);
							$('#filtro_horas').change();
						}
					},
					error: function() {
						callback();
					}
				});
			}
		}
	});
	var cargo_table;
	function complete_cargo_table() {

		$('#cargo_table tfoot th').each(function(i) {
			var title = $('#cargo_table thead th').eq(i).text();
			if (title !== '' && title !== 'Servicios') {
				$(this).html('<input class="form-control input-xs" style="width: 100%;" type="text" placeholder="' + title + '" value="' + cargo_table.column(i).search() + '"/>');
			}
		});
		$('#cargo_table tfoot th').eq(10).html('<button class="btn btn-xs btn-default" onclick="limpiar_filtro(\'cargo_table\');" title="Limpiar filtros"><i class="fa fa-eraser"></i> Filtros</button>');

		$('#filtro_regimen').on('change', function(e) {
			cargo_table.columns(9).search(this.value).draw();
		});
		$('#filtro_horas').on('change keypress', function(e) {
			cargo_table.columns(10).search(this.value).draw();
		});
		setTimeout(function() {
			$('#filtro_regimen,#filtro_horas').change();
		}, 1);
		cargo_table.columns().every(function() {
			var column = this;
			$('input', cargo_table.table().footer().children[0].children[this[0][0]]).on('change keypress', function(e) {
				if (e.type === 'change' || e.which === 13) {
					if (column.search() !== this.value) {
						column.search(this.value).draw();
					}
				}
			});
		});
		var r = $('#cargo_table tfoot tr');
		r.find('th').each(function() {
			$(this).css('padding', 8);
		});
		$('#cargo_table thead').append(r);
		$('#search_0').css('text-align', 'center');
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
			html += '<tr><td><a href="servicio/ver/' + servicios[i].id + '"><i class="fa fa-search"></i></a>' + servicios[i].liquidacion + '</td>' +
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