<style>
	#persona_table_wrapper .paginate_button.last{
		display:none;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Personas
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Personas</a></li>
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
						<a class="btn bg-blue btn-app btn-app-zetta" href="persona/agregar">
							<i class="fa fa-plus" id="btn-agregar"></i> Agregar
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
	var persona_table;
	function complete_persona_table() {
		$('#persona_table tfoot th').each(function(i) {
			var title = $('#persona_table thead th').eq(i).text();
			if (title !== '') {
				if (title === 'Cuil') {
					$(this).html('<input class="form-control input-xs" style="width: 100%;" type="text" placeholder="' + title + '" value="' + persona_table.column(i).search() + '"/>');
					$(this).find('input').inputmask("99-99999999-9");
				} else {
					$(this).html('<input class="form-control input-xs" style="width: 100%;" type="text" placeholder="' + title + '" value="' + persona_table.column(i).search() + '"/>');
				}
			}
		});
		$('#persona_table tfoot th').eq(7).html('<button class="btn btn-xs btn-default" onclick="limpiar_filtro(\'persona_table\');" title="Limpiar filtros"><i class="fa fa-eraser"></i> Filtros</button>');
		persona_table.columns().every(function() {
			var column = this;
			$('input', persona_table.table().footer().children[0].children[this[0][0]]).on('change keypress', function(e) {
				if (e.type === 'change' || e.which === 13) {
					if (column.search() !== this.value) {
						column.search(this.value).draw();
					}
				}
			});
		});
		var r = $('#persona_table tfoot tr');
		r.find('th').each(function() {
			$(this).css('padding', 8);
		});
		$('#persona_table thead').append(r);
		$('#search_0').css('text-align', 'center');
	}
</script>