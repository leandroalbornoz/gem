<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Personas
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="juntas/<?php echo $controlador; ?>/listar_personas">Escritorio juntas</a></li>
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
	var personas_table;
	function complete_personas_table() {
		agregar_filtros('personas_table', personas_table, 6);
	}
</script>
<script>
	var personas_table;
	function complete_personas_table() {
		$('#personas_table tfoot th').each(function(i) {
			var title = $('#personas_table thead th').eq(i).text();
			if (title !== '' && title !== 'Cargos' && title !== 'Títulos') {
				$(this).html('<input class="form-control input-xs" style="width: 100%;" type="text" placeholder="' + title + '" value="' + personas_table.column(i).search() + '"/>');
			}
		});
		$('#personas_table tfoot th').eq(6).html('<button class="btn btn-xs btn-default" onclick="limpiar_filtro(\'personas_table\');" title="Limpiar filtros"><i class="fa fa-eraser"></i> Filtros</button>');
		personas_table.columns().every(function() {
			var column = this;
			$('input', personas_table.table().footer().children[0].children[this[0][0]]).on('change keypress', function(e) {
				if (e.type === 'change' || e.which === 13) {
					if (column.search() !== this.value) {
						column.search(this.value).draw();
					}
				}
			});
		});
		var r = $('#personas_table tfoot tr');
		r.find('th').each(function() {
			$(this).css('padding', 8);
		});
		$('#personas_table thead').append(r);
		$('#search_0').css('text-align', 'center');
	}
</script>