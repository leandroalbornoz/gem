<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Agregar alumno de Tutor TEM
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id; ?>"><?= "Esc. $escuela->nombre_corto"; ?></a></li>
			<li><a href="tem/personal/listar/<?php echo $escuela->id; ?>/<?php echo date('Ym'); ?>">Tutores TEM</a></li>
			<li><a href="tem/personal/ver/<?php echo $servicio->id; ?>">Tutor TEM</a></li>
			<li class="active">Agregar</li>
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
						<a class="btn btn-app btn-app-zetta" href="tem/personal/ver/<?php echo $servicio->id; ?>">
							<i class="fa fa-search"></i> Ver Tutor
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active">
							<i class="fa fa-plus"></i> Agregar alumno
						</a>
						<hr style="margin: 10px 0;">
						<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agregar_alumno')); ?>
						<div class="row">
							<div class="form-group col-sm-2">
								<?php echo $fields['documento_tipo']['label']; ?>
								<?php echo $fields['documento_tipo']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields['documento']['label']; ?>
								<span class="label label-danger" id="documento_existente"></span>
								<?php echo $fields['documento']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields['apellido']['label']; ?>
								<?php echo $fields['apellido']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields['nombre']['label']; ?>
								<?php echo $fields['nombre']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields['fecha_nacimiento']['label']; ?>
								<?php echo $fields['fecha_nacimiento']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields['sexo']['label']; ?>
								<?php echo $fields['sexo']['form']; ?>
							</div>
						</div>
						<hr style="margin: 10px 0;">
						<div class="row">
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_inicio']['label']; ?>
								<?php echo $fields['fecha_inicio']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['escuela']['label']; ?>
								<?php echo $fields['escuela']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['carrera']['label']; ?>
								<?php echo $fields['carrera']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['materia']['label']; ?>
								<?php echo $fields['materia']['form']; ?>
							</div>
							<?php echo isset($p_persona_id) ? form_hidden('p_persona_id', $p_persona_id) : ''; ?>
							<div class="form-group col-md-2">
								<?php echo $fields['ciclo_lectivo']['label']; ?>
								<?php echo $fields['ciclo_lectivo']['form']; ?>
							</div>
						</div>
						<label>&nbsp;</label><br/>
						<button class="btn btn-primary pull-right" id="boton_guardar" type="submit">Guardar</button>
						<?php echo form_close(); ?>
						<br>
						<div style="display:none;" id="div_persona_buscar_listar"></div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="tem/personal/ver/<?php echo $servicio->id; ?>" title="Cancelar">Cancelar</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#ciclo_lectivo').datepicker({
			format: "yyyy",
			startView: "years",
			minViewMode: "years",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true,
			endDate: '+0y'
		});
		$('#fecha_inicio').datepicker({
			format: "dd/mm/yyyy",
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			startDate: '<?php echo (new DateTime($servicio->fecha_alta))->format('d/m/Y'); ?>',
			endDate: '<?php echo empty($servicio->fecha_baja) ? '' : (new DateTime($servicio->fecha_baja))->format('d/m/Y'); ?>',
			todayHighlight: true
		});
		agregar_eventos($('#form_agregar_alumno'));
		$('#documento,#documento_tipo').change(verificar_doc_repetido);
		var xhr;
		$('#escuela').selectize({
			valueField: 'escuela',
			labelField: 'escuela',
			searchField: 'escuela',
			maxItems: 1,
			create: true,
			createOnBlur: true,
			render: {
				option_create: function(data, escape) {
					return '<div class="create">Agregar <strong>' + escape(data.input) + '</strong>&hellip;</div>';
				}
			},
			load: function(query, callback) {
				if (query.length <= 3)
					return callback();
				xhr && xhr.abort();
				xhr = $.ajax({
					url: 'tem/ajax/get_escuelas/' + encodeURIComponent(query),
					type: 'GET',
					dataType: 'json',
					error: function() {
						callback();
					},
					success: function(res) {
						callback(res.escuelas);
					}
				});
			}
		});
		$('#fecha_inicio').select();
	});
	function verificar_doc_repetido(e) {
		$('#documento').attr('readonly', false);
		var documento_tipo = $('#documento_tipo')[0].selectize.getValue();
		var documento = $('#documento').val();
		if (documento_tipo === '8') {
			$.ajax({
				type: 'GET',
				url: 'ajax/get_indocumentado?',
				data: {documento_tipo: documento_tipo, documento: documento, id: '<?= empty($persona) ? '' : $persona->id; ?>'},
				dataType: 'json',
				success: function(result) {
					if (result !== '') {
						$('#documento_existente').text(null);
						$('#documento').attr('readonly', true);
						$('#boton_guardar').attr('disabled', false);
						$('#documento').closest('.form-group').removeClass("has-error");
						$('#documento').val(result);
					}
				}
			});
		} else if (documento_tipo !== '' && documento !== '') {
			$.ajax({
				type: 'GET',
				url: 'ajax/get_persona?',
				data: {documento_tipo: documento_tipo, documento: documento, id: '<?= empty($persona) ? '' : $persona->id; ?>'},
				dataType: 'json',
				success: function(result) {
					if (result !== '') {
						$('#documento_existente').text('Doc.en Uso');
						$('#boton_guardar').attr('disabled', true);
						$('#documento').closest('.form-group').addClass("has-error");
					} else {
						$('#documento_existente').text(null);
						$('#boton_guardar').attr('disabled', false);
						$('#documento').closest('.form-group').removeClass("has-error");
					}
				}
			});
		}
	}
</script>