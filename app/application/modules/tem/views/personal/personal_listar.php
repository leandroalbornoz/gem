<style>
	td.details-control {
    background: url('img/details_open.png') no-repeat center center;
    cursor: pointer;
	}
	tr.shown td.details-control {
    background: url('img/details_close.png') no-repeat center center;
	}
	.datepicker,.datepicker>datepicker-days>.table-condensed{
		margin:0 auto;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Tutores TEM <?php echo "Esc. $escuela->nombre_corto" ?> - <label class="label label-default"><?php echo $mes; ?></label>
			<a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#datepicker_modal"><i class="fa fa-refresh"></i> Cambiar</a>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/escritorio/<?php echo $escuela->id ?>"><?php echo "Esc. $escuela->nombre_corto" ?></a></li>
			<li><a href="tem/personal/listar/<?php echo $escuela->id ?>/<?php echo $mes_id ?>">Tutores TEM</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="escuela/escritorio/<?php echo $escuela->id ?>">
							<i class="fa fa-search"></i> Ver escuela
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="tem/personal/listar/<?php echo $escuela->id ?>/<?php echo $mes_id ?>">
							<i class="fa fa-users"></i> Tutores
						</a>
						<a class="btn btn-app btn-app-zetta pull-right" href="tem/asisnov/index/<?php echo "$escuela->id/$mes_id"; ?>">
							<i class="fa fa-print"></i> Asis. nov
						</a>
						<a class="btn btn-app btn-app-zetta pull-right" href="tem/personal_novedad/listar/<?php echo $escuela->id ?>/<?php echo $mes_id ?>">
							<i class="fa fa-calendar"></i> Novedades
						</a>
						<div class="row">
							<div class="col-sm-7">
								<?php echo $js_table; ?>
								<?php echo $html_table; ?>
							</div>
							<div class="col-sm-5">
								<table class="table">
									<tr>
										<th colspan="5"><?php echo $proyecto->horas_catedra; ?> Horas Cátedra permitidas</th>
									</tr>
									<tr>
										<th>Desde</th>
										<th>Hasta</th>
										<th>Asignadas</th>
										<th>Disponibles</th>
										<th></th>
									</tr>
									<?php foreach ($rangos_disponibles as $rango) : ?>
										<tr>
											<td><?php echo (new DateTime($rango['fecha_desde']))->format('d/m/Y'); ?></td>
											<td><?php echo (new DateTime($rango['fecha_hasta']))->format('d/m/Y'); ?></td>
											<td><?php echo $rango['horas_asignadas'] ?></td>
											<td><?php echo $rango['horas_disponibles'] ?></td>
											<td>
												<?php if (isset($rango['alta']) && $rango['alta'] && $rango['horas_disponibles'] > 0): ?>
													<?php echo form_open(base_url("tem/personal/agregar/$escuela->id/$mes_id")); ?>
													<input type="hidden" name="fecha_desde" value="<?php echo $rango['fecha_desde'] ?>"/>
													<input type="hidden" name="fecha_hasta" value="<?php echo $rango['fecha_hasta'] ?>"/>
													<input type="hidden" name="horas_disponibles" value="<?php echo $rango['horas_disponibles'] ?>"/>
													<button class="btn btn-xs btn-primary" type="submit"><i class="fa fa-plus"></i> Alta</button>
													<?php echo form_close() ?>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<?php echo form_open(base_url("tem/personal/cambiar_mes/$escuela->id/$mes_id")); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Cambiar Mes</h4>
			</div>
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
	var servicio_table;
	function complete_servicio_table() {
		agregar_filtros('servicio_table', servicio_table, 5);

		$('#servicio_table tbody').on('click', 'td.details-control', function() {
			var tr = $(this).closest('tr');
			var row = servicio_table.row(tr);
			var servicio_id = row.data().id;
			if (row.child.isShown()) {
				tr.removeClass('shown');
				row.child.hide();
			} else {
				$.ajax({
					type: 'GET',
					url: 'ajax/get_novedades?',
					data: {
						servicio_id: servicio_id,
						mes: <?php echo $mes_id; ?>
					},
					dataType: 'json',
					success: function(result) {
						row.child(format(servicio_id, result)).show();
						tr.addClass('shown');
					}
				});
			}
		});
	}
	function format(servicio_id, novedades) {
		if (novedades.length === 0) {
			return "No hay novedades asignadas al servicio";
		}
		var len = novedades.length;
		var html = '<table class="table table-condensed table-bordered table-hover" style="margin-bottom: 0;"><thead><tr><th>Articulo</th><th>Inciso</th><th>Descripción</th><th>Desde</th><th>Hasta</th><th>Días</th><th>Obligaciones</th><th>Estado</th><th></th></tr></thead><tbody>';
		for (var i = 0; i < len; i++) {
			novedades[i].dias = (novedades[i].novedad_tipo_id !== 1) ? ((novedades[i].dias !== null) ? novedades[i].dias : "") : "";
			novedades[i].obligaciones = (novedades[i].novedad_tipo_id !== 1) ? ((novedades[i].obligaciones !== null) ? novedades[i].obligaciones : "") : "";
			html += '<tr><td>' + novedades[i].articulo + '</td><td>' + novedades[i].inciso + '</td><td>' + novedades[i].descripcion_corta +
							'<td>' + moment(novedades[i].fecha_desde).format("DD/MM/YY") + '</td><td>' + (novedades[i].articulo === 'AA' ? '' : moment(novedades[i].fecha_hasta).format('DD/MM/YY')) + '</td></td><td>' + novedades[i].dias +
							'</td><td>' + novedades[i].obligaciones + '</td><td>' + novedades[i].estado + '</td>' +
							'<td><div class="btn-group" role="group">' +
							'<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>' +
							'<ul class="dropdown-menu dropdown-menu-right">' +
							'<li><a class="dropdown-item btn-warning" href="tem/personal_novedad/modal_editar/' + novedades[i].ames + '/' + novedades[i].id + '/' + novedades[i].escuela_id + '" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-pencil"></i>Editar</a></li>' +
							'<li><a class="dropdown-item btn-danger" href="tem/personal_novedad/modal_eliminar/' + novedades[i].ames + '/' + novedades[i].id + '/' + novedades[i].escuela_id + '" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i>Eliminar</a></li>' +
							'</ul></div></td></tr>';
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
			startDate: "01/10/2017",
			endDate: "01/02/2018",
			language: 'es',
			todayHighlight: false
		});
		$("#datepicker").on("changeDate", function(event) {
			$("#mes").val($("#datepicker").datepicker('getFormattedDate'));
		});
	});
</script>