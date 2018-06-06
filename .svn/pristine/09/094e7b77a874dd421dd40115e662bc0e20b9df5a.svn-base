<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Mover divisiones de Esc. <?= $escuela->nombre_corto; ?> a anexos
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id; ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="division/listar/<?= $escuela->id; ?>">Cursos y Divisiones</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="division/listar/<?php echo $escuela->id; ?>">
							<i class="fa fa-tasks"></i> Cursos y Divisiones
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="division/mover/<?= $escuela->id; ?>">
							<i class="fa fa-share"></i> Mover a anexo
						</a>
						<hr style="margin: 10px 0;">
						<p>Filtre las divisiones que desea buscar, seleccionelas con <input disabled type="checkbox">, indique a qué anexo desea moverlas y presione el botón <span class="btn btn-primary btn-xs disabled">Mover</span>.</p>
						<p>Se moverán tanto las divisiones como los cargos que estén asociados a ellas.</p>
						<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
					<div class="box-footer">
						<div class="col-sm-8">
							<?php echo $fields['anexo']['label']; ?>
							<?php echo $fields['anexo']['form']; ?>
						</div>
						<div class="col-sm-4">
							<label>&nbsp;</label><br/>
							<button type="submit" class="btn btn-primary pull-right">Mover</button>
						</div>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var division_table;
	function complete_division_table() {
		$('#division_table tfoot th').each(function(i) {
			var title = $('#division_table thead th').eq(i).text();
			if (title !== '' && title !== 'Horario') {
				$(this).html('<input class="form-control input-xs" style="width: 100%;" type="text" placeholder="' + title + '" value="' + division_table.column(i).search() + '"/>');
			}
		});
		$('#division_table tfoot th').eq(7).html('<button class="btn btn-xs btn-default" onclick="limpiar_filtro(\'division_table\');" title="Limpiar filtros"><i class="fa fa-eraser"></i> Filtros</button>');
		division_table.columns().every(function() {
			var column = this;
			$('input', division_table.table().footer().children[0].children[this[0][0]]).on('change keypress', function(e) {
				if (e.type === 'change' || e.which === 13) {
					if (column.search() !== this.value) {
						column.search(this.value).draw();
					}
				}
			});
		});
		var r = $('#division_table tfoot tr');
		r.find('th').each(function() {
			$(this).css('padding', 8);
		});
		$('#division_table thead').append(r);
		$('#search_0').css('text-align', 'center');
	}
</script>