<div class="content-wrapper">
	<section class="content-header">
		<h1>
		<?= $title ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="juntas/escritorio/administrador">Escritorio</a></li>
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
	var inscriptos_table;
	function complete_inscriptos_table() {
		$('#inscriptos_table tfoot th').each(function(i) {
			var title = $('#inscriptos_table thead th').eq(i).text();
			if (title !== '' && title !== 'Cargos' && title !== 'Títulos') {
				$(this).html('<input class="form-control input-xs" style="width: 100%;" type="text" placeholder="' + title + '" value="' + inscriptos_table.column(i).search() + '"/>');
			}
		});
		$('#inscriptos_table tfoot th').eq(6).html('<button class="btn btn-xs btn-default" onclick="limpiar_filtro(\'inscriptos_table\');" title="Limpiar filtros"><i class="fa fa-eraser"></i> Filtros</button>');
		inscriptos_table.columns().every(function() {
			var column = this;
			$('input', inscriptos_table.table().footer().children[0].children[this[0][0]]).on('change keypress', function(e) {
				if (e.type === 'change' || e.which === 13) {
					if (column.search() !== this.value) {
						column.search(this.value).draw();
					}
				}
			});
		});
		var r = $('#inscriptos_table tfoot tr');
		r.find('th').each(function() {
			$(this).css('padding', 8);
		});
		$('#inscriptos_table thead').append(r);
		$('#search_0').css('text-align', 'center');
	}
	var auditadas_table;
	function complete_auditadas_table() {
		$('#auditadas_table tfoot th').each(function(i) {
			var title = $('#auditadas_table thead th').eq(i).text();
			if (title !== '' && title !== 'Cargos' && title !== 'Títulos') {
				$(this).html('<input class="form-control input-xs" style="width: 100%;" type="text" placeholder="' + title + '" value="' + auditadas_table.column(i).search() + '"/>');
			}
		});
		$('#auditadas_table tfoot th').eq(6).html('<button class="btn btn-xs btn-default" onclick="limpiar_filtro(\'auditadas_table\');" title="Limpiar filtros"><i class="fa fa-eraser"></i> Filtros</button>');
		auditadas_table.columns().every(function() {
			var column = this;
			$('input', auditadas_table.table().footer().children[0].children[this[0][0]]).on('change keypress', function(e) {
				if (e.type === 'change' || e.which === 13) {
					if (column.search() !== this.value) {
						column.search(this.value).draw();
					}
				}
			});
		});
		var r = $('#auditadas_table tfoot tr');
		r.find('th').each(function() {
			$(this).css('padding', 8);
		});
		$('#auditadas_table thead').append(r);
		$('#search_0').css('text-align', 'center');
	}
	var vacantes_table;
	function complete_vacantes_table() {
		$('#vacantes_table tfoot th').each(function(i) {
			var title = $('#vacantes_table thead th').eq(i).text();
			if (title !== '' && title !== 'Cargos' && title !== 'Títulos') {
				$(this).html('<input class="form-control input-xs" style="width: 100%;" type="text" placeholder="' + title + '" value="' + vacantes_table.column(i).search() + '"/>');
			}
		});
		$('#vacantes_table tfoot th').eq(6).html('<button class="btn btn-xs btn-default" onclick="limpiar_filtro(\'vacantes_table\');" title="Limpiar filtros"><i class="fa fa-eraser"></i> Filtros</button>');
		vacantes_table.columns().every(function() {
			var column = this;
			$('input', vacantes_table.table().footer().children[0].children[this[0][0]]).on('change keypress', function(e) {
				if (e.type === 'change' || e.which === 13) {
					if (column.search() !== this.value) {
						column.search(this.value).draw();
					}
				}
			});
		});
		var r = $('#vacantes_table tfoot tr');
		r.find('th').each(function() {
			$(this).css('padding', 8);
		});
		$('#vacantes_table thead').append(r);
		$('#search_0').css('text-align', 'center');
	}
</script>