<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Datos de personal
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="datos_personal/listar/<?php echo $escuela->id; ?>">Datos personal</a></li>
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
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var persona_table;
	function complete_persona_table() {
		agregar_filtros('persona_table', persona_table, 7)
	}
	function persona_table_detalles(api, rowIdx, columns) {
		var html = table_detalles(api, rowIdx, columns);
		html += '<div id="persona_table_detalle_' + rowIdx + '"><i class="fa fa-spin fa-refresh"></i>Cargando servicios...</div>';
//		var servicio_id = api.row(rowIdx).data().id;
//		$.ajax({
//			type: 'GET',
//			url: 'ajax/get_novedades?',
//			data: {
//				servicio_id: servicio_id,
//			},
//			dataType: 'json',
//			success: function(result) {
//				$('#servicio_table_detalle_' + rowIdx).html(format(servicio_id, result));
//			}
//		});
		return html;
	}
	
asdasdajkh

</script>