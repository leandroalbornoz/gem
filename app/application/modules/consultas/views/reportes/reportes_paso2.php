<style>
	.columna>.btn-xs{
		margin-top:-3px;
		margin-right: 1px;
		padding-top: 0;
		padding-bottom: 0;
	}
	#columnas_seleccionadas>.columna-seleccionada:nth-last-child(1)>.btn-bajar{
		color:lightgray;
		pointer-events: none;
	}
	#columnas_seleccionadas>.columna-seleccionada:nth-child(1)>.btn-subir{
		color:lightgray;
		pointer-events: none;
	}
	.rule-value-container{
		min-width:200px;
	}
	.nav-pills .columna{
		margin-bottom:5px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Reportes</h1>
		<ol class="breadcrumb">
			<li><i class="fa fa-home"></i> Inicio</li>
			<li><a href="consultas/reportes/listar_guardados">Reportes</a></li>
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
				<?php echo form_open('consultas/reportes/iniciar', array('data-toggle' => 'validator')); ?>
				<div class="box box-primary collapsed-box">
					<div class="box-header with-border">
						<h3 class="box-title">Consultando por: <b><?php echo $tabla['nombre']; ?></b></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<a class="btn bg-default btn-app btn-app-zetta" href="consultas/reportes/listar_guardados">
								<i class="fa fa-file-excel-o" id="btn-agregar"></i> Reportes guardados
							</a>
							<a class="btn bg-default btn-app btn-app-zetta-active disabled" href="consultas/reportes/iniciar">
								<i class="fa fa-plus" id="btn-agregar"></i> Agregar
							</a>
							<hr style="margin: 10px 0;">
						</div>
						<label for="tabla">Seleccione por qué desea consultar:</label>
						<select style="width:200px;" id="tabla" name="tabla" class="form-control" required placeholder="Seleccione tabla">
							<?php foreach ($tablas_reportes as $t_id => $t_row): ?>
								<option value="<?php echo $t_id; ?>"><?php echo $t_row['nombre']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="box-footer">
						<button type="submit" class="btn btn-success">Iniciar</button>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
			<div class="col-xs-12 col-lg-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Seleccione columnas para la consulta por <b><?php echo $tabla['nombre']; ?></b></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body text-sm">
						<div class="nav nav-pills">
							<?php foreach ($tabla['columnas'] as $c_id => $c_row): ?>
								<div class="list-group-item columna col-xs-4" data-value="<?php echo $c_id; ?>" data-extra="<?php echo isset($c_row['extra']) ? $c_row['extra'] : ''; ?>">
									<span><?php echo $c_row['label']; ?></span>
									<?php if (isset($c_row['default']) && !$c_row['default']): ?>
										<button type="button" class="btn btn-xs btn-link text-green pull-right seleccionar-columna">
											<i class="fa fa-plus"></i>
										</button>	
									<?php else: ?>
										<button type="button" class="btn btn-xs btn-link text-red pull-right seleccionar-columna">
											<i class="fa fa-minus"></i>
										</button>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_open('consultas/reportes/iniciar', array('data-toggle' => 'validator', 'id' => 'form-vistaprevia')); ?>
			<div class="col-xs-12 col-lg-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Columnas seleccionadas:</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body text-sm">
						<div>
							<ul class="list-group" id="columnas_seleccionadas">
								<?php foreach ($tabla['columnas'] as $c_id => $c_row): ?>
									<?php if (!isset($c_row['default']) || (isset($c_row['default']) && $c_row['default'])): ?>
										<li class="list-group-item columna-seleccionada" data-value="<?php echo $c_id; ?>">
											<span><?php echo $c_row['label']; ?></span>
											<input type="hidden" name="columnas[]" value="<?php echo $c_id; ?>">
											<button type="button" class="btn-link btn btn-xs pull-right btn-bajar"><i class="fa fa-arrow-down"></i></button>
											<button type="button" class="btn-link btn btn-xs pull-right btn-subir"><i class="fa fa-arrow-up"></i></button>
											<?php if (isset($c_row['extra']) && $c_row['extra']): ?>
												<input type="hidden" name="filtros[]" value="agrupar">
											<?php else: ?>
												<select name="filtros[]" class="pull-right">
													<option value="agrupar">Agrupar</option>
													<?php if (isset($c_row['sum']) && $c_row['sum']): ?>
														<option value="sumar">Sumar</option>
													<?php endif; ?>
													<option value="contar">Contar</option>
												</select>
											<?php endif; ?>
										</li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border text-center">
						<h3 class="box-title">Filtros</h3>
					</div>
					<div class="box-body text-sm">
						<div id="builder"></div>
					</div>
					<div class="box-footer">
						<input type="hidden" name="from" value="<?php echo $tabla['id']; ?>" />
						<input type="hidden" name="rules" id="input_rules" value="" />
						<input type="hidden" name="reporte_id" value="<?php echo isset($reporte_id) && !empty($reporte_id) ? $reporte_id : ''; ?>"/>
						<a class="btn btn-default" href="escritorio" title="Volver">Volver</a>
						<button type="button" class="btn btn-primary pull-right" title="Vista previa" id="btn-vistaprevia">Vista previa</button>
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		$('#builder').queryBuilder({
			allow_groups: false,
			filters: <?php echo $filtros; ?>,
			lang_code: 'es',
			conditions: ['AND'],
			plugins: ['bt-selectpicker'],
			allow_empty: true
		});
		$('#builder').on('afterCreateRuleInput.queryBuilder', function(e, rule) {
			if (rule.filter.plugin == 'selectize') {
				rule.$el.find('.rule-value-container').find('.selectize-control').removeClass('form-control');
			}
		});
		$('#tabla,#relaciones').selectize({
			hideSelected: false,
			selectOnTab: true
		});
		$(document).on('click', 'button.btn-subir', function() {
			var hook = $(this).closest('li.columna-seleccionada').prev('li.columna-seleccionada');
			var elementToMove = $(this).closest('li.columna-seleccionada').detach();
			hook.before(elementToMove);
		});
		$(document).on('click', 'button.btn-bajar', function() {
			var hook = $(this).closest('li.columna-seleccionada').next('li.columna-seleccionada');
			var elementToMove = $(this).closest('li.columna-seleccionada').detach();
			hook.after(elementToMove);
		});
		$('button.seleccionar-columna').on('click', function() {
			if ($(this).hasClass('text-red')) {
				$(this).removeClass('text-red');
				$(this).find('i').removeClass('fa-minus');
				$(this).addClass('text-green');
				$(this).find('i').addClass('fa-plus');
				$('input[name="columnas[]"][value="' + $(this).closest('div.columna').data('value') + '"]').closest('li.columna-seleccionada').remove();
			} else {
				$(this).removeClass('text-green');
				$(this).find('i').removeClass('fa-plus');
				$(this).addClass('text-red');
				$(this).find('i').addClass('fa-minus');
				var list = $('ul#columnas_seleccionadas');
				var elementToMove = $(this).closest('div.columna').clone();
				elementToMove = $('<li class="list-group-item"></li>').append(elementToMove.html());
				elementToMove.find('button').remove();
				elementToMove.addClass('columna-seleccionada');
				elementToMove.append('<input type="hidden" name="columnas[]" value="' + $(this).closest('div.columna').data('value') + '">');
				elementToMove.append('<button type="button" class="btn-link btn btn-xs pull-right btn-bajar"><i class="fa fa-arrow-down"></i></button><button type="button" class="btn-link btn btn-xs pull-right btn-subir"><i class="fa fa-arrow-up"></i></button>');
				if ($(this).closest('div.columna').data('extra')) {
					elementToMove.append('<input type="hidden" name="filtros[]" value="agrupar">');
				} else {
					elementToMove.append('<select name="filtros[]" class="pull-right"><option value="agrupar">Agrupar</option><option value="sumar">Sumar</option><option value="contar">Contar</option></select>');
				}
				list.append(elementToMove);
			}
		});
		$('#builder').on('validationError.queryBuilder', function(e, rule, error, value) {
			var l = error.length;
			for (var i = 0; i < l; i++) {
				if (error[i] === 'no_filter') {
					return false;
				}
			}
		});
		$('#btn-vistaprevia').on('click', function(e) {
			e.preventDefault();
			var valido = $('#builder').queryBuilder('validate', {skip_empty: true});
			if (valido) {
				$('#input_rules').val(JSON.stringify($('#builder').queryBuilder('getRules', {skip_empty: true})));
				$('#form-vistaprevia').submit();
			}
		});
	});
</script>