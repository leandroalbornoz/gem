<div class="content-wrapper">
	<section class="content-header">
		<h1>Reportes Estáticos</h1>
		<ol class="breadcrumb">
			<li><i class="fa fa-home"></i> Inicio</li>
			<li><a href="consultas/reportes_estaticos/escritorio">Reportes Estaticos</a></li>
			<li class="active">Reporte por calendario</li>
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
		<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_reporte')); ?>
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Reporte de asistencia por Calendario</h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-3">
						<div class="form-group">
							<?php echo $fields['tipo_reporte']['label']; ?>
							<?php echo $fields['tipo_reporte']['form']; ?>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
							<?php echo $fields['supervision']['label']; ?>
							<?php echo $fields['supervision']['form']; ?>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group" id="select_escuela">
							<?php echo $fields['escuela']['label']; ?>
							<?php echo $fields['escuela']['form']; ?>
						</div>
					</div>
				</div>
				<script>
					$(document).ready(function() {
						var xhr;
						var select_supervision, $select_supervision;
						var select_escuelas, $select_escuelas;

						$select_supervision = $('select#supervision').selectize({
							onChange: function(value) {
								if ($('#tipo_reporte').val() === '1') {
									select_escuelas.disable();
									select_escuelas.clearOptions();
									select_escuelas.load(function(callback) {
										xhr && xhr.abort();
										if (value != null) {
											var arreglo = value.join();
										} else {
											var arreglo = null;
										}
										xhr = $.ajax({
											type: 'POST',
											url: 'ajax/get_escuelas?',
											data: {supervisiones: arreglo},
											dataType: 'json',
											success: function(results) {
												select_escuelas.enable();
												callback(results);
											},
											error: function() {
												callback();
											}
										});
									});
								}
							}
						});
						$select_escuelas = $('select#escuela').selectize({
							valueField: 'id',
							labelField: 'nombre_largo',
							searchField: ['nombre_largo']
						});
						select_escuelas = $select_escuelas[0].selectize;
						select_supervision = $select_supervision[0].selectize;

						$('#tipo_reporte').on('change', function() {
							if (this.value === '2') {
								select_escuelas.disable();
								$('select#escuela').selectize()[0].selectize.clear();
							} else {
								select_escuelas.enable();
								$('select#escuela').selectize()[0].selectize.clear();
							}
						});
					});
				</script>
				<div class="row">
					<div class="form-group col-md-3">
						<?php echo $fields['desde']['label']; ?>
						<div class="input-group date" id="datepicker-desde">
							<?php echo $fields['desde']['form']; ?>
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</div>
						</div>
					</div>
					<div class="form-group col-md-3">
						<?php echo $fields['hasta']['label']; ?>
						<div class="input-group date" id="datepicker-hasta">
							<?php echo $fields['hasta']['form']; ?>
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<a class="btn btn-primary" href="consultas/reportes_estaticos/escritorio">
					Cancelar
				</a>
				<?php $data_submit = array('class' => 'btn btn-success pull-right', 'title' => $txt_btn); ?>
				<?php echo form_submit($data_submit, $txt_btn); ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_reporte'));
		$("#datepicker-desde").datepicker({
			format: "mm/yyyy",
			startView: "months",
			minViewMode: "months",
			language: 'es',
			todayHighlight: false,
			orientation: "bottom"
		});
		$("#datepicker-hasta").datepicker({
			format: "mm/yyyy",
			startView: "months",
			minViewMode: "months",
			language: 'es',
			todayHighlight: false,
			orientation: "bottom"
		});
	});
</script>