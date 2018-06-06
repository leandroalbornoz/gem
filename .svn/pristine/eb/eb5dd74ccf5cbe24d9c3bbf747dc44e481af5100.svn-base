<script>
	$(document).ready(function() {
		$('.agregar_caracteristica_select').selectize({
			dropdownParent: 'body',
			placeholder: '-- Seleccione característica existente --'
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Características de Nivel <?= $nivel->descripcion; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<?php if ($rol->codigo === ROL_ADMIN || $rol->codigo === ROL_USI): ?>
				<li><a href="nivel/listar/<?= $nivel->linea_id; ?>">Niveles</a></li>
				<li><a href="nivel/ver/<?= $nivel->id; ?>"><?= $nivel->descripcion; ?></a></li>
			<?php else: ?>
				<li>Nivel <?= $nivel->descripcion; ?></li>
			<?php endif; ?>
			<li class="active">Características</li>
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
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Características existentes para agregar</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<?php echo $js_table_agregar; ?>
						<?php echo $html_table_agregar; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var caracteristica_table;
	function complete_caracteristica_table() {
		$('#caracteristica_table tfoot th').each(function(i) {
			var title = $('#caracteristica_table thead th').eq(i).text();
			if (title !== '') {
				if (title === 'Tipo') {
					$(this).html(<?php echo json_encode(form_dropdown(array('class' => 'input-xs form-control', 'style' => 'width:100%;'), $array_caracteristica_tipo)); ?>);
					$(this).find('select').val(caracteristica_table.column(i).search());
				} else {
					$(this).html('<input class="form-control input-xs" style="width: 100%;" type="text" placeholder="' + title + '" value="' + caracteristica_table.column(i).search() + '"/>');
				}
			}
		});
		$('#caracteristica_table tfoot th').eq(3).html('<button class="btn btn-xs btn-default" onclick="limpiar_filtro(\'caracteristica_table\');" title="Limpiar filtros"><i class="fa fa-eraser"></i> Filtros</button>');
		caracteristica_table.columns().every(function() {
			var column = this;
			$('input,select', caracteristica_table.table().footer().children[0].children[this[0][0]]).on('change keypress', function(e) {
				if (e.type === 'change' || e.which === 13) {
					if (column.search() !== this.value) {
						column.search(this.value).draw();
					}
				}
			});
		});
		var r = $('#caracteristica_table tfoot tr');
		r.find('th').each(function() {
			$(this).css('padding', 8);
		});
		$('#caracteristica_table thead').append(r);
		$('#search_0').css('text-align', 'center');
	}
	var caracteristica_agregar_table;
	function complete_caracteristica_agregar_table() {
		$('#caracteristica_agregar_table tfoot th').each(function(i) {
			var title = $('#caracteristica_agregar_table thead th').eq(i).text();
			if (title !== '') {
				if (title === 'Tipo') {
					$(this).html(<?php echo json_encode(form_dropdown(array('class' => 'input-xs form-control', 'style' => 'width:100%;'), $array_caracteristica_tipo)); ?>);
					$(this).find('select').val(caracteristica_agregar_table.column(i).search());
				} else {
					$(this).html('<input class="form-control input-xs" style="width: 100%;" type="text" placeholder="' + title + '" value="' + caracteristica_agregar_table.column(i).search() + '"/>');
				}
			}
		});
		$('#caracteristica_agregar_table tfoot th').eq(3).html('<button class="btn btn-xs btn-default" onclick="limpiar_filtro(\'caracteristica_agregar_table\');" title="Limpiar filtros"><i class="fa fa-eraser"></i> Filtros</button>');
		caracteristica_agregar_table.columns().every(function() {
			var column = this;
			$('input,select', caracteristica_agregar_table.table().footer().children[0].children[this[0][0]]).on('change keypress', function(e) {
				if (e.type === 'change' || e.which === 13) {
					if (column.search() !== this.value) {
						column.search(this.value).draw();
					}
				}
			});
		});
		var r = $('#caracteristica_agregar_table tfoot tr');
		r.find('th').each(function() {
			$(this).css('padding', 8);
		});
		$('#caracteristica_agregar_table thead').append(r);
		$('#search_0').css('text-align', 'center');
	}
</script>