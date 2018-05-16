<style>
	.table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th{
    padding: 6px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Buscar trayectoria de Alumnos
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
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
						<div class="row"  onkeypress="validar(event)">
							<div class="form-group col-sm-3">
								<div class="row">
									<label class="col-sm-12" style="line-height: 22px;"><input type="radio" name="opcion" value="2" checked=""> Búsqueda por Documento.</label>
									<div class="form-group col-sm-12">
										<?php echo $fields['documento']['label']; ?>
										<?php echo $fields['documento']['form']; ?>
									</div>
								</div>
							</div>
							<div class="form-group col-sm-6">
								<div class="row">
									<label class="col-sm-12" style="line-height: 22px;"><input type="radio" name="opcion" value="1"> Búsqueda por Apellido y Nombre.<br></label>
									<div class="form-group col-sm-6">
										<?php echo $fields['apellido']['label']; ?>
										<span class="label label-danger" id="dato_invalido_asterisco"></span>
										<?php echo $fields['apellido']['form']; ?>
									</div>
									<div class="form-group col-sm-6">
										<?php echo $fields['nombre']['label']; ?>
										<?php echo $fields['nombre']['form']; ?>
									</div>
								</div>
							</div>
							<div class="form-group col-sm-3">
								<div class="row">
									<label class="col-sm-12 hidden-xs" style="line-height: 23px;">&nbsp;</label>
								</div>
								<div class="row">
									<label class="col-sm-12">&nbsp;</label>
									<div class="col-sm-12">
										<button class="btn btn-primary" id="btn-search" type="button">
											<i class="fa fa-search"></i>
										</button>
										<button class="btn btn-default" id="btn-clear" type="button">
											<i class="fa fa-times"></i>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="box-body ">
						<table class="table table-hover table-bordered table-condensed no-footer" role="grid" id="tbl_listar_alumnos">
							<thead>
								<tr>
									<th style="width: 3%;"></th>
									<th style="width: 40%;">Nombre y Apellido</th>
									<th style="width: 20%;">Documento</th>
									<th style="width: 20%;">Fecha de Nacimiento</th>
									<th class="none"></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</tfoot>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	function validar(e) {
		var tecla = (document.all) ? e.keyCode : e.which;
		if (tecla === 13) {
			$('#btn-search').click();
		}
	}
	var table_alumnos_busqueda;
	$(document).ready(function() {
		table_alumnos_busqueda = $('#tbl_listar_alumnos').DataTable({dom: 'tp', autoWidth: false, paging: 'simple', pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 10, lengthMenu: [10], columnDefs: [
				{orderable: false, targets: [1]},
				{data: null, targets: 0, defaultContent: ''},
				{data: 'nombre', targets: 1},
				{data: 'documento', targets: 2},
				{data: 'fecha_nacimiento', targets: 3},
				{data: null, targets: 4, defaultContent: ''}
			],
			initComplete: alumno_index_table,
			responsive: {details: {renderer: alumno_table_detalles}}
		});
		$('#apellido,#nombre').attr('disabled', true);
		$('#documento').attr('disabled', false);
		$('#documento').attr('required', true);
		$("input[name='opcion']").change(function() {
			if ($(this).val() === '1') {
				$('#apellido,#nombre').attr('disabled', false);
				$('#apellido,#nombre').attr('required', true);
				$('#documento').attr('disabled', true);
				$('#documento').val('');
				$('#btn-clear').click();
			} else if ($(this).val() === '2') {
				$('#documento').attr('disabled', false);
				$('#documento').attr('required', true);
				$('#apellido,#nombre').attr('disabled', true);
				$('#apellido,#nombre').val('');
				$('#apellido,#nombre').closest('.form-group').removeClass("has-error");
				$('#').closest('.form-group').removeClass("has-error");
				$('#btn-clear').click();
			}
		});
		$('#btn-clear').attr('disabled', true);
		$('#btn-clear').click(function() {
			$('#btn-clear').attr('disabled', true);
			$('#btn-search').attr('disabled', false);
			$('#apellido,#nombre').attr('readonly', false);
			$('#documento').attr('readonly', false);
			table_alumnos_busqueda.clear();
			$('#tbl_listar_alumnos tbody').html('');
		});
		$('#btn-search').click(function() {
			$('#apellido,#nombre').closest('.form-group').removeClass("has-error");
			$('#btn-search').attr('disabled', true);
			$('#dato_invalido').text('');
			table_alumnos_busqueda.clear();
			var documento = $('#documento').val();
			var nombre = $('#nombre').val();
			var apellido = $('#apellido').val();
			if ((nombre.length >= 3 && apellido.length >= 3) || documento !== '') {
				$('#tbl_listar_alumnos tbody').html('');
				if (nombre !== '' || apellido !== '' || documento !== '') {
					$.ajax({
						type: 'GET',
						url: 'ajax/get_alumno?',
						data: {nombre: nombre, apellido: apellido, documento: documento},
						dataType: 'json',
						success: function(result) {
							$('#apellido,#nombre').attr('readonly', true);
							$('#btn-clear').attr('disabled', false);
							$('#documento').attr('readonly', true);
							if (result.status === 'success') {
								if (result.personas_listar.length > 0) {
									for (var idx in result.personas_listar) {
										var personas_listar = result.personas_listar[idx];
										table_alumnos_busqueda.row.add(personas_listar);
									}
								}
								table_alumnos_busqueda.draw();
								$('#btn-submit').attr('disabled', false);
							} else {
								table_alumnos_busqueda.row.add([
									'',
									'Persona no encontrada',
									'-',
									'-']);
								table_alumnos_busqueda.draw();
							}
						}
					});
				}
			} else {
				$('#btn-search').attr('disabled', false);
				if ((nombre.length < 3 || apellido.length < 3)) {
					if ((nombre.length < 3)) {
						$('#nombre').closest('.form-group').addClass("has-error");
					}
					if (apellido.length < 3) {
						$('#apellido').closest('.form-group').addClass("has-error");
					}
					$('#dato_invalido').text('Ingrese como mínimo 3 caracteres en apellido y nombre');
				}
			}
		});
		function alumno_index_table() {
			$('#tbl_listar_alumnos tfoot th').each(function(i) {
				var title = $('#tbl_listar_alumnos thead th').eq(i).text();
				if (title !== '') {
					$(this).html('<input class="form-control input-xs" style="width: 100%;" type="text" placeholder="' + title + '" value="' + table_alumnos_busqueda.column(i).search() + '"/>');
				}
			});
			$('#tbl_listar_alumnos tfoot th').eq(0).html('<button class="btn btn-xs btn-default" onclick="limpiar_filtro(\'tbl_listar_alumnos\');" title="Limpiar filtros"><i class="fa fa-eraser"></i> Filtros</button>');
			table_alumnos_busqueda.columns().every(function() {
				var column = this;
				$('input', table_alumnos_busqueda.table().footer().children[0].children[this[0][0]]).on('change keypress', function(e) {
					if (e.type === 'change' || e.which === 13) {
						if (column.search() !== this.value) {
							column.search(this.value).draw();
						}
					}
				});
			});
			var r = $('#tbl_listar_alumnos tfoot tr');
			r.find('th').each(function() {
				$(this).css('padding', 8);
			});
			$('#tbl_listar_alumnos thead').append(r);
			$('#search_0').css('text-align', 'center');
		}
		function alumno_table_detalles(api, rowIdx, columns) {
			var html = $.map(columns, function(col, i) {
				return col.hidden ?
								'<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
								'<td>' + col.data + '</td>' +
								'</tr>' :
								'';
			}).join('');
			var alumno_id = table_alumnos_busqueda.row(rowIdx).data().alumno_id;
			html = $('<table class="table table-condensed table-bordered table-hover"/>').append(html).prop('outerHTML');
			html += '<div id="trayectoria_table_detalle_' + rowIdx + '"><i class="fa fa-spin fa-refresh"></i>Cargando trayectoria...</div>';
			$.ajax({
				type: 'GET',
				url: 'ajax/get_trayectoria_alumno?',
				data: {alumno_id: alumno_id},
				dataType: 'json',
				success: function(result) {
					$('#trayectoria_table_detalle_' + rowIdx).html(format(alumno_id, result.trayectoria));
				}
			});
			return html;
		}
		function format(alumno_id, trayectoria) {
			if (trayectoria === undefined) {
				return "No se encuentra trayectoria para el alumno";
			} else if (trayectoria.length === 0) {
				return "No se encuentra trayectoria para el alumno";
			}
			var len = trayectoria.length;
			var html = '<table class="table table-condensed table-bordered table-hover" style="margin-bottom: 0;">\n\
<thead>\n\<tr class="bg-blue">\n\<th class="text-center" colspan="10">Trayectoria del alumno</th>\n\</tr>\n\<tr>\n\<th>C.L</th>\n\<th>Condición</th>\n\<th>Escuela</th>\n\<th>División</th>\n\<th>Legajo</th>\n\<th>Ingreso</th>\n\<th>Egreso</th>\n\<th>Estado</th>\n\<th>Inasisitencias</th>\n\</thead>\n\<tbody>';
			for (var i = 0; i < len; i++) {
				html += '<tr><td>' + (!trayectoria[i].ciclo_lectivo ? '' : trayectoria[i].ciclo_lectivo) + '</td>' +
								'<td>' + (!trayectoria[i].condicion ? '' : trayectoria[i].condicion) + '</td>' +
								'<td>' + (!trayectoria[i].nombre_escuela ? '' : trayectoria[i].nombre_escuela) + '</td>' +
								'<td>' + (!trayectoria[i].division ? '' : trayectoria[i].division) + '</td>' +
								'<td>' + (!trayectoria[i].legajo ? '' : trayectoria[i].legajo) + '</td>' +
								'<td>' + (!trayectoria[i].fecha_desde ? '' : moment(trayectoria[i].fecha_desde).format('DD/MM/YYYY')) + '</td>' +
								'<td>' + (!trayectoria[i].fecha_hasta ? '' : moment(trayectoria[i].fecha_hasta).format('DD/MM/YYYY')) + '</td>' +
								'<td>' + (!trayectoria[i].estado ? '' : trayectoria[i].estado) + '</td>' +
								'<td>' + (!trayectoria[i].falta ? '' : trayectoria[i].falta) + '</td>\n\</tr>';
			}
			return html + '</tbody></table>';
		}
	});
</script>