<script>
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	var userSelect;
	$(document).keypress(function(e) {
		if (e.which == 13) {
			buscar_usuarios();
		}
	});
	function buscar_usuarios() {
		$('#btn_buscar_usuarios').prop('disabled', true);
		$('#btn_buscar_usuarios > i').addClass('fa-spin');
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
							$(html).appendTo("#resultados");
						});
					} else {
						$("#resultados").append('No se encontraron usuarios');
					}
					$('#btn_buscar_usuarios').prop('disabled', false);
					$('#btn_buscar_usuarios > i').removeClass('fa-spin');
				}
			});
		} else {
			$("#resultados").append('Debe ingresar al menos 5 caracteres');
			$('#btn_buscar_usuarios').prop('disabled', false);
			$('#btn_buscar_usuarios > i').removeClass('fa-spin');
		}
	}
	function selectUser(element) {
		$('#remote_modal_lg').animate({scrollTop: 0}, 'fast');
		$('.elementUsuario').removeClass('elementSelect');
		$(element).addClass('elementSelect');

		userSelect = $(element).attr('id');
		actualizarRoles();
	}

	function actualizarRoles() {
		$("#roles").html('');
		$.ajax({
			url: 'cursada/modal_buscar_usuarios/<?= $cursada->id; ?>',
			data: {
				usuario: userSelect
			},
			type: 'POST',
			dataType: 'json',
			success: function(data) {
				var html_data = '';
				var cursada_id = <?= $cursada->id; ?>;
				html_data += '<thead><tr><th>Rol</th><th>Entidad</th></tr></thead><tbody>';
				if (data.roles === '-1') {
					html_data += '<tr><th colspan="2"><a class="btn btn-primary" href="usuario/agregar/' + userSelect + '" title="Crear usuario GEM"><i class="fa fa-plus"></i> Crear usuario GEM</a></th></tr>';
				} else {
					for (var rol in data.roles) {
						html_data += '<tr><td>' + data.roles[rol].nombre + '</td><td>' + (data.roles[rol].entidad === null ? '' : data.roles[rol].entidad) + '</td></tr>';
					}
					if (data.rol_cursada === '0') {
						html_data += '</tbody><tfoot><tr><th colspan="2"><a class="btn btn-primary" href="cursada/asignar_rol_cursada/' + userSelect + '/' + cursada_id + '" title="Asignar rol"><i class="fa fa-pencil"></i> Asignar rol</a></th></tr></tfoot>';
					} else {
						html_data += '</tbody><tfoot><tr><th colspan="2"><a class="btn btn-success disabled"><i class="fa fa-ban"></i> Rol ya asignado</a></th></tr></tfoot>';
					}
				}
				$("#roles").html(html_data);
			}
		});
	}
</script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-sm-12">
			<p>Se asignará el rol de cursada al usuario:</p>
		</div>
	</div>
	<form>
		<div class="row">
			<div class="col-md-6 col-lg-6">
				<div class="form-group">
					<label for="usuarioBusqueda">Busque un usuario:</label>
					<div class="input-group">
						<input class="form-control" id="usuarioBusqueda" placeholder="Busque por email o documento | 5 caracteres mínimo" type="text">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default" id="btn_buscar_usuarios" onclick="buscar_usuarios();"><i class="fa fa-refresh"></i>&nbsp;Buscar</button>
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
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
</div>