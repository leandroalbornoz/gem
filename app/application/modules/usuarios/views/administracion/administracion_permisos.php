<script>
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	var userSelect, rolSelect, dataTree;
	function buscar_usuarios() {
		$("#resultados").empty();
		$("#roles").html('');
		$("#parametros").html('');
		$('#tree').remove();
		var string = $('#usuarioBusqueda').val();
		if (string.length >= 5) {
			$.ajax({
				url: 'usuarios/administracion/get_usuario',
				data: {
					clave: string
				},
				type: 'POST',
				dataType: 'json',
				success: function (data) {
					data.forEach(function (element) {
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
				}
			});
		}
	}
	function selectUser(element) {
		$('.elementUsuario').removeClass('elementSelect');
		$(element).addClass('elementSelect');

		userSelect = $(element).attr('id');
		$('#tree').remove();
		$('#tree-container').append('<div id="tree"></div>');
		getTree();
		actualizarRoles();
	}
	function setRol() {
		$.ajax({
			url: 'usuarios/administracion/set_rol',
			data: {
				usuario: userSelect,
				rol: rolSelect
			},
			type: 'POST',
			dataType: 'json',
			success: function (data) {
				actualizarRoles();
			}
		});
	}

	function getParametros() {
		$.ajax({
			url: 'usuarios/administracion/get_parametros',
			data: {
				usuario: userSelect,
				rol: rolSelect
			},
			type: 'POST',
			dataType: 'json',
			success: function (data) {
				dataParam = data;
				inicializar_param(dataParam);
				$("#tree").jstree("open_all");
			}
		});
	}

	function getTree() {
		$.ajax({
			url: 'usuarios/administracion/get_permisos',
			data: {
				usuario: userSelect
			},
			type: 'POST',
			dataType: 'json',
			success: function (data) {
				dataTree = data;
				inicializar(dataTree);
				$("#tree").jstree("open_all");
			}
		});
	}
	function actualizarRoles() {
		$("#roles").html('');
		$("#parametros").html('');
		$.ajax({
			url: 'usuarios/administracion/get_permisos_activos',
			data: {
				usuario: userSelect
			},
			type: 'POST',
			dataType: 'json',
			success: function (data) {
				var html_data = '';
				for (row in data) {
					html_data += '<tr><td><input type="radio" name="roles_activos" onclick="get_parametros(' + data[row].id + ')">' + data[row].nombre + '</input></td></tr>';
				}
				$("#roles").html(html_data);
			}
		});
	}
	function get_parametros(rol_id) {
		$("#parametros").html('');
		$.ajax({
			url: 'usuarios/administracion/get_parametros',
			data: {
				usuario: userSelect,
				rol: rol_id
			},
			type: 'POST',
			dataType: 'json',
			success: function (data) {
				var html_data = '';
				for (row in data) {
					var checked = '';
					if (data[row].checked == true)
						checked = ' checked';
					html_data += '<label style="margin-left: 10px;" class="check-rol checkbox-inline">' +
									'<input type="checkbox" name="parametros_activos"' + checked +
									' onclick="set_parametro(' + rol_id + ', \'' + data[row].valor + '\')">' +
									data[row].valor +
									'</input></label>';
				}
				$("#parametros").html(html_data);
			}
		});
	}
	function set_parametro(rol_id, valor) {
		$.ajax({
			url: 'usuarios/administracion/set_parametro',
			data: {
				usuario: userSelect,
				rol: rol_id,
				parametro: valor
			},
			type: 'POST',
			dataType: 'json',
			success: function (data) {
			}
		});
	}

	function inicializar(tree) {
		$("#tree").jstree({
			"core": {
				"check_callback": true,
				"data": tree,
				"select_mode": 2,
				"clickFolderMode": 1,
				"expand_selected_onload": true,
				"keep_selected_style": true,
			},
			"plugins": ["wholerow", "checkbox"],
			"checkbox": {
				"tie_selection": false,
				"three_state": false,
			}
		});
		$("#tree")
						.bind("check_node.jstree", function (event, data) {
							rolSelect = data.node.id;
							setRol();
						})
						.bind("uncheck_node.jstree", function (event, data) {
							rolSelect = data.node.id;
							setRol();
						})
						.bind("loaded.jstree", function (event, data) {
							$(this).jstree("open_all");
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
								<div class="col-md-6 col-lg-6 col-md-offset-1 col-lg-offset-1">
									<label for="usuario">Marque los roles a asignar</label>
									<div class="form-group" id="tree-container">
										<div id="tree">
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-5 col-lg-5">
									<div class="form-group">
										<label for="roles">Roles asignados:</label>
										<table id="roles">
										</table>
									</div>
								</div>
								<div class="col-md-6 col-lg-6 col-md-offset-1 col-lg-offset-1">
									<div class="form-group">
										<label for="parametros">Parámetros:</label>
										<div id="parametros">
										</div>
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