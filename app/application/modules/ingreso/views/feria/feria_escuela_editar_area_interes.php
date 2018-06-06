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
		<h1>
			Feria Educativa - Escuelas
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Escuelas</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="ingreso/feria/escuela/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta" href="ingreso/feria/escuela_editar/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-edit"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="ingreso/feria/escuela_editar_area_interes/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-edit"></i> Áreas de Interés
						</a>
						<a class="btn btn-app btn-app-zetta" href="ingreso/feria/escuela_asignaciones/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-user"></i> Asignaciones
						</a>
						<hr style="margin: 10px 0;">
						<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agregar_areas')); ?>
						<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-6">
									<div class="box-body" style="padding: 0px;">
										<div class="box-group">
											<div class="panel box box-success">
												<div class="box-header with-border">
													<h4 class="box-title">
														Áreas de Interés seleccionadas
													</h4>
													<span class="badge bg-green" style="float: right; font-size: 15px;" id="cantidad_seleccionada"></span>
												</div>
												<div>
													<div class="box-body text-sm">
														<div class="nav nav-pills" id="areas_interes_seleccionadas" style="height:554px; overflow-y: scroll;"> 
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="box-body" style="padding: 0px;">
										<div class="box-group">
											<div class="panel box box-info">
												<div class="box-header with-border">
													<h4 class="box-title">
														Áreas de Interés
													</h4>
												</div>
												<div>
													<div class="box-body">
														<div class="input-group">
															<span class="input-group-addon">Buscar</span>
															<input id="filtrar" type="text" class="form-control" placeholder="Ingresa el nombre del área de interés que deseas buscar...">
														</div>
													</div>
													<div class="box-body text-sm">
														<div class="nav nav-pills buscar" id="areas_interes" style="height:500px; overflow-y: scroll;">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<?php echo zetta_form_submit($txt_btn); ?>
						<a class="btn btn-default" href="ingreso/feria/listar" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		var cantidad = "";
<?php foreach ($areas_interes as $id_area => $area_interes): ?>
			html = "";
			html += '<div class="list-group-item columna col-xs-6" style="padding-top: 8px; border-radius: 0px;" id="<?php echo $id_area; ?>">';
			html += '<span><?php echo $area_interes; ?></span>';
			html += '<button type="button" class="btn btn-xs btn-link text-green pull-right seleccionar-area" data-extra="<?php echo $id_area; ?>" data-nombre="<?php echo $area_interes; ?>">';
			html += '<i class="fa fa-plus"></i>';
			html += '</button>';
			html += '</div>';
			$('#areas_interes').append(html);
<?php endforeach; ?>

<?php foreach ($lista_areas as $lista_area): ?>
			html = "";
			html += '<div class="list-group-item columna col-xs-6" style="padding-top: 8px; border-radius: 0px;" id="retirar_<?php echo $lista_area->id; ?>">';
			html += '<span><?php echo $lista_area->descripcion; ?></span>';
			html += '<button type="button" class="btn btn-xs btn-link text-red pull-right retirar-area" data-extra="<?php echo $lista_area->id; ?>" data-nombre="<?php echo $lista_area->descripcion; ?>"  data-retirar="<?php echo $lista_area->feria_escuela_especiliadad_id; ?>"> ';
			html += '<i class="fa fa-minus"></i>';
			html += '</button>';
			html += '</div>';
			$('#areas_interes_seleccionadas').append(html);
			cantidad++;
			$('#cantidad_seleccionada').text(cantidad);
<?php endforeach; ?>

		$(document).on('click', 'button.seleccionar-area', function() {
			var area_id = $(this).data('extra');
			$('#' + area_id).remove();
			html = "";
			html += '<div class="list-group-item columna col-xs-6" style="padding-top: 8px; border-radius: 0px;" id="retirar_' + area_id + '">';
			html += '<span id="span_seleccionar">' + $(this).data('nombre') + '</span>';
			html += '<input type="hidden" name="areas_interes_agregar[]" value="' + area_id + '" id="	areas_interes_agregar[]"/>';
			html += '<button type="button" class="btn btn-xs btn-link text-red pull-right retirar-area" data-extra="' + area_id + '" data-nombre="' + $(this).data('nombre') + '">';
			html += '<i class="fa fa-minus"></i>';
			html += '</button>';
			html += '</div>';
			cantidad++;
			$('#areas_interes_seleccionadas').append(html);
			$('#cantidad_seleccionada').text(cantidad);
			var $wrapper = $('#areas_interes_seleccionadas'), $articles = $wrapper.find('.columna');
			[].sort.call($articles, function(a, b) {
				if ($(a).html() > $(b).html()) {
					return 1;
				} else if ($(a).html() < $(b).html()) {
					return -1;
				} else {
					return 0;
				}
			});
			$articles.each(function() {
				$wrapper.append(this);
			});
		});

		$(document).on('click', 'button.retirar-area', function() {
			var area_id = $(this).data('extra');
			var area_retirar_id = $(this).data('retirar');
			$('#retirar_' + area_id).remove();
			html = "";
			html += '<div class="list-group-item columna col-xs-6" style="padding-top: 8px; border-radius: 0px;" id="' + area_id + '">';
			html += '<span>' + $(this).data('nombre') + '</span>';
			html += '<input type="hidden" name="areas_interes_retirar[]" value="' + area_retirar_id + '" id="	areas_interes_retirar[]"/>';
			html += '<button type="button" class="btn btn-xs btn-link text-green pull-right seleccionar-area" data-extra="' + area_id + '" data-nombre="' + $(this).data('nombre') + '">';
			html += '<i class="fa fa-plus"></i>';
			html += '</button>';
			html += '</div>';
			cantidad--;
			$('#areas_interes').append(html);
			$('#cantidad_seleccionada').text(cantidad);
			var $wrapper = $('#areas_interes'), $articles = $wrapper.find('.columna');
			[].sort.call($articles, function(a, b) {
				if ($(a).html() > $(b).html()) {
					return 1;
				} else if ($(a).html() < $(b).html()) {
					return -1;
				} else {
					return 0;
				}
			});
			$articles.each(function() {
				$wrapper.append(this);
			});
		});

		(function($) {
			$('#filtrar').keyup(function() {
				var rex = new RegExp($(this).val(), 'i');
				$('.buscar div').hide();
				$('.buscar div').filter(function() {
					return rex.test($(this).text());
				}).show();
			})
		}(jQuery));
	});
</script>