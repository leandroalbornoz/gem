<style>
	.input_hora{
		text-align:center;
		width:40px;
		border: 1px #000 solid;
		background-color: #eee;
	}
	.input_recreo{
		text-align:center;
		width:90px;
		border: 1px #000 solid;
	}
	.input_recreo_general{
		text-align:center;
		width:100px;
		border: 1px #000 solid;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Esc. <?= $escuela->nombre_corto; ?> - Div: <?php echo "$division->curso $division->division"; ?> - Agregar horario
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id; ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="division/listar/<?= $escuela->id ?>">Cursos y Divisiones</a></li>
			<li><a href="division/ver/<?= $division->id ?>"><?php echo "$division->curso $division->division"; ?></a></li>
			<li class="active">Agregar horario</li>
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
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta" href="division/ver/<?php echo $division->id; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver División
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="division/agregar_horario/<?php echo $division->id; ?>">
							<i class="fa fa-clock-o"></i> Agregar Horario
						</a>
						<div class="row">
							<div class="form-group col-md-2">
								<?php echo $fields['turno']['label']; ?>
								<?php echo $fields['turno']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['curso']['label']; ?>
								<?php echo $fields['curso']['form']; ?>
							</div>
							<div class="form-group col-md-8">
								<?php echo $fields['division']['label']; ?>
								<?php echo $fields['division']['form']; ?>
							</div>
							<div class="form-group col-sm-12">
								<label>Días</label><br/>
								<?php foreach ($dias as $dia): ?>
									<label class="checkbox-inline">
										<?php echo '<input type="checkbox" id="dia-' . $dia->id . '" name="dias[' . $dia->id . ']" value="' . $dia->id . '" class="dias"' . ($dia->id <= 5 ? ' checked' : '') . '> ' . mb_substr($dia->nombre, 0, 2); ?>
									</label>
								<?php endforeach; ?>
							</div>
							<div class="form-group col-sm-3">
								<label>Hora inicial</label>
								<input type="text" name="hora_inicial" id="hora_inicial" class="form-control" placeholder="hh:mm" value="08:00"/>
							</div>
							<div class="form-group col-sm-3">
								<label>Duración hora de clase (minutos)</label>
								<input type="text" name="duracion" id="duracion" class="form-control" value="40"/>
							</div>
							<div class="form-group col-sm-3">
								<label>Cantidad de horas de clase por día</label>
								<input type="number" name="horas" id="horas" class="form-control" value="6"/>
							</div>
							<div class="form-group col-sm-3">
								<label>&nbsp;</label><br/>
								<button class="btn btn-success" type="button" onclick="armar_horario();" title="Generar">Generar</button>
							</div>
							<table class="table table-condensed" style="text-align: center; table-layout: fixed;" id="tbl-horarios">
								<thead>
								<th style="text-align: center;">Hora de clase</th>
								<?php foreach ($dias as $dia): ?>
									<th style="text-align: center;"><?= $dia->nombre ?></th>
								<?php endforeach; ?>
								</thead>
								<tbody>
									<tr><td colspan="8">-- Ingrese <b>Hora inicial</b>, <b>Duración de hora de clase</b> y <b>Cantidad de horas de clase por día</b> y presione <span class="btn btn-default btn-xs">Generar</span> --</td></tr>
								</tbody>
							</table>
						</div>
						<input type="hidden" name="escuela_id" value="<?= $escuela->id ?>"/>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="division/listar/<?= $escuela->id ?>" title="Cancelar">Cancelar</a>
						<?php echo form_hidden('division_id', $division->id); ?>
						<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Agregar horario'), 'Agregar horario'); ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	function armar_horario() {
		var hora_inicial = $('#hora_inicial').val();
		var duracion = $('#duracion').val();
		var horas = $('#horas').val();
		var html = '';
		var hora_desde = moment(hora_inicial, 'HH:mm');
		var hora_hasta = moment(hora_inicial, 'HH:mm').add(duracion, 'minutes');
		for (var i = 1; i <= horas; i++) {
			html += '<tr data-hora="' + i + '" class="horas">';
			html += '<th style="text-align: center;">' + i + '</th>';
			for (var d = 1; d <= 7; d++) {
				if ($('#dia-' + d).prop('checked')) {
					html += '<td data-dia="' + d + '" class="dias">';
					html += '<input class="input_hora hora_desde" name="hora[' + i + '][dia][' + d + '][desde]" type="text" value="' + hora_desde.format('HH:mm') + '" readonly="true"/>';
					html += ' - '
					html += '<input class="input_hora hora_hasta" name="hora[' + i + '][dia][' + d + '][hasta]" type="text" value="' + hora_hasta.format('HH:mm') + '" readonly="true"/>';
					html += '</td>';
				} else {
					html += '<td></td>';
				}
			}
			hora_desde = hora_desde.add(duracion, 'minutes');
			hora_hasta = hora_hasta.add(duracion, 'minutes');
			if (i != horas) {
				html += '<tr data-recreo="' + i + '">';
				html += '<td><input class="input_recreo_general" type="text" value="" placeholder="Rec.Gral.(mm)"></td>';
				for (var d = 1; d < 7; d++) {
					if ($('#dia-' + d).prop('checked')) {
						html += '<td data-horario="' + d + '">';
						html += '<input class="input_recreo" type="text" value="" placeholder="Recreo (mm)">';
						html += '</td>';
					} else {
						html += '<td></td>';
					}
				}
				html += '</tr>';
			}
			html += '</tr>';
		}
		$('#tbl-horarios tbody').html(html);
		add_eventos_recreos();
	}
	function add_eventos_recreos() {
		$('.input_recreo_general').change(function() {
			var recreo = $(this).val();
			var tr_recreo = $(this).parent().parent();
			tr_recreo.children('td[data-horario]').each(function() {
				var input_recreo = $(this).children();
				input_recreo.val(recreo);
				input_recreo.change();
			});
		});
		$('.input_recreo').change(function() {
			$('.input_recreo').each(function(index, input) {
				var input_recreo = $(this).val();
				var tr_hora_catedra = $(this).parents('tr');
				var data_horario = $(this).parents('td').data('horario');
				$(tr_hora_catedra).next('.horas').each(function() {
					$(this).children('td[data-dia="' + data_horario + '"]').each(function() {
						var input_hasta = $(this).parent('tr').prev().prev().find('[data-dia="' + data_horario + '"]').children('.hora_hasta').val();
						var hora_desde = moment(input_hasta, "HH:mm").add(input_recreo, 'minutes');
						var hora_hasta = moment(input_hasta, "HH:mm").add(input_recreo, 'minutes').add($('#duracion').val(), 'minutes');
						$(this).children('.hora_desde').val(hora_desde.format("HH:mm"));
						$(this).children('.hora_hasta').val(hora_hasta.format("HH:mm"));
					});
				});
			});
		});
	}
</script>