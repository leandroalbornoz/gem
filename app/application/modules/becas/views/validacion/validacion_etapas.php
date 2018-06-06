<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<style>
	.datepicker table tr td.etapa, .etapa{
		color:white;
	}
	.datepicker table tr td.day.focused, .datepicker table tr td.day:hover{
		background-color: inherit;
	}
	.datepicker table tr td.today{
		border: blue solid medium;
		background-image: none;
		background: none;
	}
	.datepicker table tr td.today:hover:hover{
		border: darkblue solid medium;
		background-image: none;
		background: none;
		color:white;
	}
	.datepicker table tr td.etapa.etapa-1, .etapa-1 {
		background-color: #757575;
	}
	.datepicker table tr td.etapa.etapa-2, .etapa-2 {
		background-color: #ffa000;
	}
	.datepicker table tr td.etapa.etapa-3, .etapa-3 {
		background-color: #ff5722;
	}
	.datepicker table tr td.etapa.etapa-4, .etapa-4 {
		background-color: #8bc34a;
	}
	.datepicker table tr td.etapa.etapa-5, .etapa-5 {
		background-color: #4caf50;
	}
	.datepicker table tr td.etapa.etapa-6, .etapa-6 {
		background-color: #1b5e20;
	}
	.datepicker table tr td.etapa.etapa-1:hover, .etapa-1:hover {
		background-color: #858585;
	}
	.datepicker table tr td.etapa.etapa-2:hover, .etapa-2:hover {
		background-color: #ffb010;
	}
	.datepicker table tr td.etapa.etapa-3:hover, .etapa-3:hover {
		background-color: #ff6732;
	}
	.datepicker table tr td.etapa.etapa-4:hover, .etapa-4:hover {
		background-color: #9bd35a;
	}
	.datepicker table tr td.etapa.etapa-5:hover, .etapa-5:hover {
		background-color: #5cbf60;
	}
	.datepicker table tr td.etapa.etapa-6:hover, .etapa-6:hover {
		background-color: #2b6e30;
	}
</style>
<div class="col-xs-12">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 style="width:100%; padding-right: 30px;" class="box-title">Etapas</h3>
			<div class="box-tools pull-right">
				<a class="btn btn-danger" title="Procedimiento de Carga Becas Estímulo" href="uploads/ayuda/becas/procedimiento.pdf" target="_blank"><i class="fa fa-file-pdf-o"></i> Procedimiento de Carga</a>
				<a class="btn btn-default" href="uploads/ayuda/becas/memo.pdf" target="_blank"><i class="fa fa-file-pdf-o"></i> Memo</a>
				<a class="btn btn-default" href="http://bases.mendoza.edu.ar/intranet2/adjuntos/RESOL1293DGE18.pdf" target="_blank"><i class="fa fa-file-pdf-o"></i> Resolución</a>
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-sm-4"><div id="calendario_etapas"></div></div>
				<div class="col-sm-8">
					<table class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th>Etapa</th>
								<th>Inicio</th>
								<th>Fin</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($etapas as $etapa): ?>
								<tr>
									<td><span class="badge etapa etapa-<?= $etapa->id; ?>"><?= substr($etapa->descripcion, 0, 1) ?></span> <?= $etapa->descripcion; ?></td>
									<td><?= (new DateTime($etapa->inicio))->format('d/m/Y'); ?></td>
									<td><?= (new DateTime($etapa->fin))->format('d/m/Y'); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$("#calendario_etapas").datepicker({
			inline: true,
			language: 'es',
			format: 'yyyy-mm-dd',
			startDate: '<?= $etapas[0]->inicio; ?>',
			endDate: '<?= $etapas[count($etapas) - 1]->fin; ?>',
			todayHighlight: true,
			beforeShowDay: function(date) {
				var d = moment(date).format('YYYY-MM-DD');
<?php foreach ($etapas as $etapa): ?>
	<?= "if (d >= '$etapa->inicio' && d <= '$etapa->fin') { return {classes: 'etapa etapa-$etapa->id'};}"; ?>
<?php endforeach; ?>
				return true;
			}
		});
	});
</script>