<style>
	td.details-control {
    background: url('img/details_open.png') no-repeat center center;
    cursor: pointer;
	}
	tr.shown td.details-control {
    background: url('img/details_close.png') no-repeat center center;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Usuarios
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Usuarios</a></li>
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
						<a class="btn bg-blue btn-app btn-app-zetta" href="usuario/permisos">
							<i class="fa fa-plus" id="btn-agregar"></i> Agregar
						</a>
						<a class="btn btn-app btn-app-zetta" href="usuario/excel">
							<i class="fa fa-file-excel-o"></i> Exportar Excel
						</a>
						<hr style="margin: 10px 0;">
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var usuario_table;
	function complete_usuario_table() {
		agregar_filtros('usuario_table', usuario_table, 7);

		$('#usuario_table tbody').on('click', 'td.details-control', function() {
			var tr = $(this).closest('tr');
			var row = usuario_table.row(tr);
			var usuario_id = row.data().id;
			if (row.child.isShown()) {
				tr.removeClass('shown');
				row.child.hide();
			} else {
				$.ajax({
					type: 'GET',
					url: 'ajax/get_roles?',
					data: 'usuario_id=' + usuario_id,
					dataType: 'json',
					success: function(result) {
						row.child(format(result)).show();
						tr.addClass('shown');
					}
				});
			}
		});
	}
	function format(roles) {
		if (roles.length === 0) {
			return "No hay roles asignados al usuario";
		}
		var len = roles.length;
		var html = '<table class="table table-condensed table-bordered table-hover" style="margin-bottom: 0;"><thead><tr><th>Rol</th><th>Entidad</th></tr></thead><tbody>';
		for (var i = 0; i < len; i++) {
			html += '<tr><td>' + roles[i].nombre + '</td><td>' + (roles[i].entidad === null ? '' : roles[i].entidad) + '</td></tr>'
		}
		return html + '</tbody></table>';
	}
</script>
