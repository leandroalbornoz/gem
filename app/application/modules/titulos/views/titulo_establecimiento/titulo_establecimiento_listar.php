<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Establecimientos
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="titulos/<?php echo $controlador; ?>">Establecimientos</a></li>
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
						<a class="btn bg-blue btn-app btn-app-zetta" href="titulos/titulo_establecimiento/modal_agregar" data-remote="false" data-toggle="modal" data-target="#remote_modal">
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
	$(document).ready(function() {
		$("#remote_modal").on("show.bs.modal", function(e) {
			var link = $(e.relatedTarget);
			$(this).find(".modal-content").load(link.attr("href"));
		});
		$('#remote_modal').on("hidden.bs.modal", function(e) {
			$(this).find(".modal-content").empty();
		});
	});
</script>
<div class="modal fade" id="remote_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>