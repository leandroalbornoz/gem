<?php if (($txt_btn === 'Agregar' || $txt_btn === 'Editar') && in_array($this->rol->codigo, $this->roles_admin)): ?>
		<script>
			$(document).ready(function() {
				var xhr_nivel;
				var select_linea, $select_linea;
				var select_nivel, $select_nivel;

				$select_linea = $('select#linea').selectize({
					onChange: linea_actualizada
				});

				$select_nivel = $('select#nivel').selectize({
					valueField: 'id',
					labelField: 'descripcion',
					searchField: ['descripcion']
				});

				select_linea = $select_linea[0].selectize;
				select_nivel = $select_nivel[0].selectize;

				if (select_linea.getValue() !== '') {
					linea_actualizada(select_linea.getValue());
				}
				function linea_actualizada(value) {
					actualizar_nivel(value);
				}
				function actualizar_nivel(value) {
					select_nivel.enable();
					var valor = select_nivel.getValue();
					select_nivel.disable();
					select_nivel.clearOptions();
					if (value == '') {
						return;
					}
					select_nivel.load(function(callback) {
						xhr_nivel && xhr_nivel.abort();
						xhr_nivel = $.ajax({
							url: 'ajax/get_niveles/' + value,
							dataType: 'json',
							success: function(results) {
								select_nivel.enable();
								callback(results);
								if (results.length === 1) {
									select_nivel.setValue(results[0].id);
								} else {
									select_nivel.setValue(valor);
								}
							},
							error: function() {
								callback();
							}
						});
					});
				}
			});
		</script>
<?php endif; ?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Supervisiones
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Supervisiones</a></li>
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
						<?php if ($txt_btn === 'Agregar'): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['agregar']; ?>" href="supervision/agregar">
								<i class="fa fa-plus" id="btn-agregar"></i> Agregar
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="supervision/ver/<?php echo (!empty($supervision->id)) ? $supervision->id : ''; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="supervision/editar/<?php echo (!empty($supervision->id)) ? $supervision->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['caracteristica']; ?>" href="supervision/caracteristicas/<?php echo (!empty($supervision->id)) ? $supervision->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Características
						</a>
						<?php if (in_array($this->rol->codigo, $this->roles_admin)): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="supervision/eliminar/<?php echo (!empty($supervision->id)) ? $supervision->id : ''; ?>">
								<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
							</a>
						<?php endif; ?>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields['dependencia']['label']; ?>
								<?php echo $fields['dependencia']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['linea']['label']; ?>
								<?php echo $fields['linea']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['nivel']['label']; ?>
								<?php echo $fields['nivel']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['nombre']['label']; ?>
								<?php echo $fields['nombre']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['orden']['label']; ?>
								<?php echo $fields['orden']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['responsable']['label']; ?>
								<?php echo $fields['responsable']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['sede']['label']; ?>
								<?php echo $fields['sede']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['email']['label']; ?>
								<?php echo $fields['email']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['calle']['label']; ?>
								<?php echo $fields['calle']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['calle_numero']['label']; ?>
								<?php echo $fields['calle_numero']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['barrio']['label']; ?>
								<?php echo $fields['barrio']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['localidad']['label']; ?>
								<?php echo $fields['localidad']['form']; ?>
							</div>
							<div class="form-group col-md-1">
								<?php echo $fields['codigo_postal']['label']; ?>
								<?php echo $fields['codigo_postal']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['telefono']['label']; ?>
								<?php echo $fields['telefono']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['blackberry']['label']; ?>
								<?php echo $fields['blackberry']['form']; ?>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="<?php echo isset($redirect) ? $redirect : 'supervision/listar' ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $supervision->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
