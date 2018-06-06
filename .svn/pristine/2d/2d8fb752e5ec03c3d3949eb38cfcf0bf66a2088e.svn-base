<script>
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	var userSelect;
	function buscar_usuarios() {
		$("#resultados").empty();
		$("#roles").html('');
		var string = $('#usuarioBusqueda').val();
		if (string.length >= 5) {
			$.ajax({
				url: 'usuarios/administracion/get_usuario',
				data: {
					clave: string
				},
				type: 'POST',
				dataType: 'json',
				success: function(data) {
					if (data.length > 0) {
						data.forEach(function(element) {
							html = '<div id="' + element.id + '" class="col-md-12 elementUsuario" onClick="selectUser(this)">';
							html += '<div class="col-md-3"><img class="img-rounded" width="50" heigth="50" src="data:image/png;charset=utf8;base64,' + element.picture + '"/></div><div class="col-md-9">';
							if (element.cuil) {
								html += '<label><b>Cuil:</b> ' + element.cuil + '</label><br>';
							}
							if (element.dni) {
								html += '<label><b>Documento:</b> ' + element.dni + '</label><br>';
							}
							if (element.apellido) {
								html += '<label><b>Apellido:</b> ' + element.apellido + '</label><br>';
							}
							if (element.nombre) {
								html += '<label><b>Nombre:</b> ' + element.nombre + '</label><br>';
							}
							html += '<label><b>Email:</b> ' + element.usuario + '</label></div></div>';
							$("#resultados").append(html);
						});
					} else {
						$("#resultados").append('No se encontraron usuarios');
					}
				}
			});
		}
	}
	function selectUser(element) {
		$('.elementUsuario').removeClass('elementSelect');
		$(element).addClass('elementSelect');

		userSelect = $(element).attr('id');
		actualizarRoles();
	}

	function actualizarRoles() {
		$("#roles").html('');
		$.ajax({
			url: 'usuarios/administracion/get_permisos',
			data: {
				usuario: userSelect
			},
			type: 'POST',
			dataType: 'json',
			success: function(data) {
				var html_data = '';
				if (data.roles === '-1') {
					if (data.grupos.length === 0) {
						html_data += '<tfoot><tr><th colspan="2"><a class="btn btn-primary" href="usuario/agregar/' + userSelect + '" title="Crear usuario GEM"><i class="fa fa-plus"></i> Crear usuario GEM y agregar acceso</a></th></tr></tfoot>';
					} else {
						html_data += '<tfoot><tr><th colspan="2"><a class="btn btn-primary" href="usuario/agregar/' + userSelect + '" title="Crear usuario GEM"><i class="fa fa-plus"></i> Crear usuario GEM</a></th></tr></tfoot>';
					}
				} else if (data.roles === '0') {
					if (data.grupos.length === 0) {
						html_data += '<tfoot><tr><th colspan="2"><a class="btn btn-primary" href="usuario/editar_roles/' + userSelect + '" title="Editar roles"><i class="fa fa-pencil"></i> Agregar acceso a GEM y editar roles</a></th></tr></tfoot>';
					} else {
						html_data += '<tfoot><tr><th colspan="2"><a class="btn btn-primary" href="usuario/editar_roles/' + userSelect + '" title="Editar roles"><i class="fa fa-pencil"></i> Editar roles</a></th></tr></tfoot>';
					}
				} else {
					html_data += '<thead><tr><th>Rol</th><th>Entidad</th></tr></thead><tbody>';
					for (var rol in data.roles) {
						html_data += '<tr><td>' + data.roles[rol].nombre + '</td><td>' + (data.roles[rol].entidad === null ? '' : data.roles[rol].entidad) + '</td></tr>';
					}
					html_data += '</tbody><tfoot><tr><th colspan="2"><a class="btn btn-primary" href="usuario/editar_roles/' + userSelect + '" title="Editar roles"><i class="fa fa-pencil"></i> Editar roles</a></th></tr></tfoot>';
				}
				$("#roles").html(html_data);
			}
		});
	}
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Permisos
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li class="active"><?php echo ucfirst($controlador); ?></li>
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
						<h3 class="box-title">Asignar rol a usuarios</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<form>
							<div class="row">
								<div class="col-md-5 col-lg-5">
									<div class="form-group">
										<label for="usuarioBusqueda">Busque un usuario:</label>
										<div class="input-group">
											<input class="form-control" id="usuarioBusqueda" placeholder="Busque por email o documento | 5 caracteres mínimo" type="text">
											<span class="input-group-btn">
												<button type="button" class="btn btn-default" onclick="buscar_usuarios();">Buscar</button>
											</span>
										</div>
									</div>
									<hr>
									<div class="" id="resultados">
									</div>
								</div>
								<div class="col-md-6 col-lg-6">
									<div class="form-group">
										<label for="roles">Roles asignados:</label>
										<table class="table table-condensed table-bordered" id="roles">
										</table>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="box-footer">
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>