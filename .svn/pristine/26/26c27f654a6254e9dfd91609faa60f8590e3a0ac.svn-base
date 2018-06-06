<?php if ($txt_btn === 'Agregar' || $txt_btn === 'Editar'): ?>
	<script>
		$(document).ready(function() {
			var xhr_area;
			$('#area_padre').selectize({
				onChange: actualizar_codigo
			});
	<?php if ($txt_btn === 'Agregar'): ?>
				actualizar_codigo($('#area_padre').val());
	<?php endif; ?>

			function actualizar_codigo(value) {
				xhr_area && xhr_area.abort();
				xhr_area = $.ajax({
					url: 'ajax/get_codigo_area/',
					method: 'POST',
					data: {
						area: value, id: <?php echo isset($area) ? $area->id : 0; ?>
					},
					dataType: 'json',
					success: function(data) {
						$('#codigo').val(data.codigo);
					},
					error: function() {
					}
				});
			}
		});
	</script>
<?php endif; ?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Áreas
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="areas/area/listar">Áreas</a></li>
			<?php if (!empty($area)): ?>
				<li><?php echo "$area->codigo $area->descripcion"; ?></li>
			<?php endif; ?>
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
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<?php if ($rol->codigo === ROL_ADMIN || $rol->codigo === ROL_USI): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['agregar']; ?>" href="areas/area/agregar">
								<i class="fa fa-plus" id="btn-agregar"></i> Agregar
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="areas/area/ver/<?php echo (!empty($area->id)) ? $area->id : ''; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="areas/area/editar/<?php echo (!empty($area->id)) ? $area->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="areas/area/eliminar/<?php echo (!empty($area->id)) ? $area->id : ''; ?>">
							<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['personal']; ?>" href="areas/personal/listar/<?php echo (!empty($area->id)) ? $area->id : ''; ?>">
							<i class="fa fa-users" id="btn-cargos"></i> Personal
						</a>
						<div class="row">
							<div class="form-group col-md-4">
								<?php echo $fields['area_padre']['label']; ?>
								<?php echo $fields['area_padre']['form']; ?>
							</div>
							<div class="form-group col-md-4">
								<?php echo $fields['codigo']['label']; ?>
								<?php echo $fields['codigo']['form']; ?>
							</div>
							<div class="form-group col-md-4">
								<?php echo $fields['descripcion']['label']; ?>
								<?php echo $fields['descripcion']['form']; ?>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="areas/area/listar" title="Cancelar">Cancelar</a>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $area->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>