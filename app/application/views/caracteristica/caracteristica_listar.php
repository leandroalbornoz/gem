<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Caracteristicas - <?= $caracteristica_tipo->descripcion; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="caracteristica_<?= $caracteristica_tipo->entidad; ?>/listar">Tipos de características <?= $caracteristica_tipo->entidad; ?></a></li>
			<li><a href="caracteristica/listar/<?= "$caracteristica_tipo->entidad/$caracteristica_tipo->id"; ?>"><?= $caracteristica_tipo->descripcion; ?></a></li>
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
						<a class="btn bg-blue btn-app btn-app-zetta" href="caracteristica/agregar/<?= "$caracteristica_tipo->entidad/$caracteristica_tipo->id"; ?>">
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