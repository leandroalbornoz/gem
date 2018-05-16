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
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Reportes</h1>
		<ol class="breadcrumb">
			<li><i class="fa fa-home"></i> Inicio</li>
			<li><a href="consultas/reportes/listar_guardados">Reportes</a></li>
			<li class="active">Eliminar</li>
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
					<div class="box-header with-border">
						<h3 class="box-title">Consultando por: <b><?php echo $tabla['nombre']; ?></b></h3>
					</div>
				</div>
			</div>
			<?php echo form_open('consultas/reportes/iniciar', array('data-toggle' => 'validator', 'id' => 'form-vistaprevia')); ?>
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Columnas seleccionadas:</h3>
					</div>
					<div class="box-body text-sm">
						<div>
							<ul class="nav nav-pills" id="columnas_seleccionadas">
								<?php foreach ($columnas as $columna_id): ?>
									<div class="col-xs-4" style="margin-bottom:5px;">
										<li class="list-group-item columna-seleccionada" data-value="<?php echo $columna_id; ?>">
											<span><?php echo $tabla['columnas'][$columna_id]['label']; ?></span>
											<input type="hidden" name="columnas[]" value="<?php echo $columna_id; ?>">
										</li>
									</div>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Filtros:</h3>
					</div>
					<div class="box-body text-sm">
						<table class="table table-hover table-bordered table-condensed" id="tbl-reglas">
							<thead>
								<tr>
									<th>Columna</th>
									<th>Operador</th>
									<th>Valor</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-xs-12" id="div-filtro">
				<div class="box box-primary">
					<div class="box-header with-border text-center">
						<h3 class="box-title">Filtros</h3>
					</div>
					<div class="box-body text-sm">
						<div id="builder"></div>
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>
			<?php if (!empty($registros)): ?>
				<div class="col-xs-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Vista previa:</h3>
						</div>
						<div class="box-body text-sm">
							<div style="overflow-x: scroll;">
								<table class="table table-bordered">
									<thead>
										<?php foreach ($columnas as $columna_id): ?>
										<th><?= $tabla['columnas'][$columna_id]['label']; ?></th>
									<?php endforeach; ?>
									</thead>
									<tbody>
										<?php foreach ($registros as $index => $row): ?>
											<tr>
												<?php foreach ($row as $column): ?>
													<td><?= $column ?></td>
												<?php endforeach; ?>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
						<?php echo form_open("consultas/reportes/eliminar/$reporte_id", array('data-toggle' => 'validator', 'id' => 'form-eliminar')) ?>
						<div class="box-footer">
							<a class="btn btn-default" href="consultas/reportes/listar_guardados" title="Cancelar">Cancelar</a>
							<input type="hidden" name="id" value="<?php echo $reporte_id; ?>"/>
							<button type="submit" class="btn btn-danger pull-right" title="Eliminar" id="btn-exportar" style="margin-left: 5px;">Eliminar</button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			<?php endif; ?> 
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		String.prototype.replaceAll = function(search, replacement) {
			var target = this;
			return target.replace(new RegExp(search, 'g'), replacement);
		};

		$('#builder').queryBuilder({
			allow_groups: false,
			filters: <?php echo $filtros; ?>,
			lang_code: 'es',
			conditions: ['AND'],
			plugins: ['bt-selectpicker']
		});
		$('#builder').queryBuilder('setRules', JSON.parse('<?php echo $rules ?>'));
		$('.rules-list .rule-container').each(function(e) {
			var html = '<tr>';
			html += '<td>' + $(this).find('.rule-filter-container').find('select>option:selected').text() + '</td>';
			html += '<td>' + $(this).find('.rule-operator-container').find('select>option:selected').text() + '</td>';
			if ($(this).find('.rule-value-container').find('.item').size() > 0) {
				html += '<td>' + $(this).find('.rule-value-container').find('.item').text().replaceAll('×', ' - ').slice(0, -2) + '</td>';
			} else {
				html += '<td>' + $(this).find('.rule-value-container').find('input').val() + '</td>';
			}
			html += '</tr>';
			$('#tbl-reglas>tbody').append(html);
		});
		$(':button[data-add="rule"]').hide();
		$('#div-filtro').hide();
	});
</script>