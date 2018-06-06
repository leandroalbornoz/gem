<style>
	.datepicker,.datepicker>datepicker-days>.table-condensed{
		margin:0 auto;
	}
	.promedio-status-ok,.promedio-status-loading,.promedio-status-error{
		display:none;
	}
	.promedio-status.ok>.promedio-status-ok{
		display:inline-block;
	}
	.promedio-status.loading>.promedio-status-loading{
		display:inline-block;
	}
	.promedio-status.error>.promedio-status-error{
		display:inline-block;
	}

	.abaderado-status-ok,.abaderado-status-loading,.abaderado-status-error{
		display:none;
	}
	.abaderado-status.ok>.abaderado-status-ok{
		display:inline-block;
	}
	.abaderado-status.loading>.abaderado-status-loading{
		display:inline-block;
	}
	.abaderado-status.error>.abaderado-status-error{
		display:inline-block;
	}
	
	.participa-status-ok,.participa-status-loading,.participa-status-error{
		display:none;
	}
	.participa-status.ok>.participa-status-ok{
		display:inline-block;
	}
	.participa-status.loading>.participa-status-loading{
		display:inline-block;
	}
	.participa-status.error>.participa-status-error{
		display:inline-block;
	}

	.abanderado.active {
    color: green;
	}
	.abanderado.active {
    font-weight: bold;
	}
	.abanderado_no.active {
    font-weight: bold;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Alumnos de <?php echo "Esc. $escuela->nombre_corto" ?> - <label class="label label-default"><?php echo $ciclo_lectivo; ?></label>
			<a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#datepicker_modal"><i class="fa fa-refresh"></i> Cambiar</a>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="alumno/listar/<?php echo $escuela->id . '/' . date('Y'); ?>">Alumnos</a></li>
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
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="alumno/listar/<?php echo $escuela->id; ?>">
							<i class="fa fa-users"></i> Alumnos
						</a>
						<div class="btn-group pull-right" role="group" style="margin-left: 5px;">
							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-external-link"></i> Otros accesos <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								<?php if ($this->rol->codigo !== ROL_ESCUELA_ALUM): ?>
									<li><a class="dropdown-item btn-default" href="servicio/listar/<?php echo $escuela->id . '/'; ?>"><i class="fa  fa-fw fa-bookmark"></i>Servicios</a></li>
									<li><a class="dropdown-item btn-default"  href="cargo/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-users"></i> Cargos</a></li>
								<?php endif; ?>
								<li><a class="dropdown-item btn-default" href="division/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-tasks"></i> Cursos y Divisiones</a></li>
								<li><a class="dropdown-item btn-default" href="escuela_carrera/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-book"></i>Carreras</a></li>
								<?php if (ENVIRONMENT !== 'production'): ?>
									<li><a class="dropdown-item btn-default" href="abono/abono_alumno/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-bus"></i>Abonos</a></li>
								<?php endif; ?>	
							</ul>
						</div>
						<hr style="margin: 10px 0;">
						<script type="text/javascript">$(document).ready(function() {
								$.fn.dataTable.moment("DD/MM/YYYY");
								alumno_table = $("#alumno_table").DataTable({
									order: [[5, "asc"], [2, "asc"], [3, "asc"], [1, "asc"]],
									initComplete: complete_alumno_table,
									dom: '<"row"<"col-sm-4"l><"col-sm-8"p>>rt<"row"<"col-sm-4"i><"col-sm-8"p>>',
									data: <?= json_encode($alumnos); ?>,
									paging: false,
									processing: true,
									autoWidth: false,
									language: {"url": "plugins/datatables/spanish.json"},
									columns: [
										{"data": "documento"},
										{"data": "persona"},
										{"data": "curso"},
										{"data": "division"},
										{"data": "promedio"},
										{"data": "abanderado_escolta"},
										{"data": "registro_cuenta"},
										{"data": "banco_asigando"},
										{"data": "escuela_asiganada"},
										{"data": "participa"},
										{"data": "mail_padre_madre"},
										{"data": "edit"}
									],
									columnDefs: [
										{"targets": 0, "width": "6%", "className": "text-sm"},
										{"targets": 1, "width": "15%", "className": "text-sm"},
										{"targets": 2, "width": "5%", "className": "dt-body-center"},
										{"targets": 3, "width": "10%", "className": "dt-body-center"},
										{"targets": 4, "width": "6%", "className": "dt-body-center"},
										{"targets": 5, "width": "5%", "className": "dt-body-center"},
										{"targets": 6, "width": "3%", "className": "dt-body-center"},
										{"targets": 7, "width": "3%", "className": "dt-body-center"},
										{"targets": 8, "width": "14%", "className": "dt-body-center"},
										{"targets": 9, "width": "12%", "className": "dt-body-center"},
										{"targets": 10, "width": "11%", "className": "dt-body-center"},
										{"targets": 11, "width": "5%", "className": "dt-body-center", "searchable": false, "sortable": false}
									],
									colReorder: true
								});
							});
						</script>
						<table id="alumno_table" class="table table-hover table-bordered table-condensed">
							<thead>
								<tr>
									<th>Documento</th>
									<th>Alumno</th>
									<th>Curso</th>
									<th>Divisiones</th>
									<th>Promedio</th>
									<th>Abanderado/Escolta</th>
									<th>Registró Cuenta</th>
									<th>Banco Asignado</th>
									<th>Escuela Asignada</th>
									<th>Participa</th>
									<th>E-mail Padres</th>
									<th class="all"></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</tfoot>
						</table>
						<br>
						<table id="data" class="table table-striped table-condensed">
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<?php echo form_open("alumno/cambiar_ciclo_lectivo/$escuela->id/$ciclo_lectivo"); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Cambiar Ciclo Lectivo</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-12" style="text-align:center;">
						<div id="datepicker" data-date="<?php echo "01/01/$ciclo_lectivo"; ?>"></div>
						<input type="hidden" name="ciclo_lectivo" id="ciclo_lectivo" />
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
	var alumno_table;
	function complete_alumno_table() {
		agregar_filtros('alumno_table', alumno_table, 11);
		Inputmask.extendAliases({
			'nota': {
				alias: "numeric",
				placeholder: '',
				allowPlus: false,
				allowMinus: false,
				insertMode: false,
				radixPoint: ',',
				digits: 2,
				autoUnmask: true,
				removeMaskOnSubmit: true,
				onUnMask: function(value) {
					return value.replace('.', '').replace(',', '.');
				}
			}
		});
		$('input.input-notas').inputmask('nota');
		$('#alumno_table').find('input.input-notas').each(function(index, element) {
			$('input.input-notas').click(function() {
				$(this).focus();
				$(this).select();
			});
		});
		$('input.input-notas').change(function() {
			var input = parseFloat($(this).val());
			var input2 = this;

			if (input > 10) {
				$(this).val(10);
			}
			if (input < 1) {
				$(this).val(1);
			}

			cambiar_estado_promedio(input2, 'loading');
			var alumno_id = $(this).data("a");
			var alumno_division_id = input2.name;
			var promedio = input2.value;
			if (alumno_id !== '' && promedio !== '') {
				$.ajax({
					type: 'GET',
					url: 'ingreso/ajax_ingreso/actualiza_promedio?',
					data: {alumno_division_id: alumno_division_id, promedio: promedio, alumno_id: alumno_id},
					dataType: 'json',
					success: function(result) {
						if (result.status === 'success') {
							cambiar_estado_promedio(input2, 'ok');
						} else {
							cambiar_estado_promedio(input2, 'error');
						}
					}
				});
			}
		});

		$("input[name=abanderado]").change(function() {
			var input = this;
			var alumno_id = $(this).data("a");
			var abanderado = $(this).val();
			cambiar_estado_abaderado(input.closest("div"), 'loading');

			if (alumno_id !== '' && abanderado !== '') {
				$.ajax({
					type: 'GET',
					url: 'ingreso/ajax_ingreso/actualiza_abanderado?',
					data: {alumno_id: alumno_id, abanderado: abanderado},
					dataType: 'json',
					success: function(result) {
						if (result.status === 'success') {
							cambiar_estado_abaderado(input.closest("div"), 'ok');
						} else {
							cambiar_estado_abaderado(input.closest("div"), 'error');
						}
					}
				});
			}
		});

		$("select[name=participa]").change(function() {
			var input = this;
			var alumno_id = $(this).data("a");
			var motivo = $(this).val();
			cambiar_estado_participa(input.closest("div"), 'loading');

			if (alumno_id !== '' && motivo !== '') {
				$.ajax({
					type: 'GET',
					url: 'ingreso/ajax_ingreso/actualiza_participa?',
					data: {alumno_id: alumno_id, motivo: motivo},
					dataType: 'json',
					success: function(result) {
						if (result.status === 'success') {
							cambiar_estado_participa(input.closest("div"), 'ok');
						} else {
							cambiar_estado_participa(input.closest("div"), 'error');
						}
					}
				});
			}
		});
	}

	function cambiar_estado_promedio(input, estado) {
		$(input).next('.promedio-status').removeClass('loading');
		$(input).next('.promedio-status').removeClass('ok');
		$(input).next('.promedio-status').removeClass('error');
		$(input).next('.promedio-status').addClass(estado);
	}
	
	function cambiar_estado_abaderado(input, estado) {
		$(input).next('.abaderado-status').removeClass('loading');
		$(input).next('.abaderado-status').removeClass('ok');
		$(input).next('.abaderado-status').removeClass('error');
		$(input).next('.abaderado-status').addClass(estado);
	}
	
	function cambiar_estado_participa(input, estado) {
		$(input).next('.participa-status').removeClass('loading');
		$(input).next('.participa-status').removeClass('ok');
		$(input).next('.participa-status').removeClass('error');
		$(input).next('.participa-status').addClass(estado);
	}
</script>
