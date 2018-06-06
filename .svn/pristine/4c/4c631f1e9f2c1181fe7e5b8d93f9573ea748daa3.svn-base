<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Usuarios de cursada en <?php echo "$division->curso $division->division"; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i></a></li>
			<li><a href="escuela/ver/<?= $escuela->id; ?>"><?= "Esc. $escuela->nombre_corto"; ?></a></li>
			<li><a href="division/ver/<?= $division->id ?>"><?php echo "$division->curso $division->division"; ?></a></li>
			<li><a href="cursada/listar/<?= $division->id ?>"><?php echo "Cursadas"; ?></a></li>
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
						<div class="row">
							<div class="col-md-12">
								<a class="btn btn-app btn-app-zetta" href="cursada/escritorio/<?= $cursada->id ?>">
									<i class="fa fa-clock-o"></i> Cursadas
								</a>
								<a class="btn bg-blue btn-app btn-app-zetta" data-remote="false" data-toggle="modal" data-target="#remote_modal_lg"  href="cursada/modal_buscar_usuarios/<?php echo "$cursada->id"; ?>">
									<i class="fa fa-refresh"></i> Asignar rol
								</a>
							</div>
						</div>
						<hr style="margin: 10px 0;">
						<div class="row">
							<div class="col-md-12">
								<?php echo $js_table; ?>
								<?php echo $html_table; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var usuario_table;
	function complete_usuario_table() {
		agregar_filtros('usuario_table', usuario_table, 6);
	}
</script>
<script>
	var usuario_table;
	function complete_usuario_table() {
		agregar_filtros('usuario_table', usuario_table, 6);
	}
	$(document).ready(function() {
		$("#remote_modal_lg").on("show.bs.modal", function(e) {
			var link = $(e.relatedTarget);
			$(this).find(".modal-content").load(link.attr("href"));
		});
		$('#remote_modal_lg').on("hidden.bs.modal", function(e) {
			$(this).find(".modal-content").empty();
		});
	});
</script>
<div class="modal fade" id="remote_modal_lg" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		</div>
	</div>
</div>