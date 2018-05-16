<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?php if (!empty($escuela)): ?>
				<?php echo "Anuncios - $escuela->nombre_largo"; ?>
			<?php else: ?>
				Escuelas
			<?php endif; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Escuelas</a></li>
			<?php if (!empty($escuela)): ?>
				<li><a href="escuela/ver/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>"><?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<?php endif; ?>
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
					<div class="box-body">
						<?php foreach ($anuncios as $anuncio): ?>
							<div class="col-xs-12">
								<div class="box box-warning collapsed-box">
									<div class="box-header with-border">
										<h3 class="box-title"><b><?php echo (new DateTime($anuncio->fecha))->format('d/m/Y') . ' ' . $anuncio->titulo; ?></b></h3>
										<div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
										</div>
									</div>
									<div class="box-body">
										<?php echo $anuncio->texto; ?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
			</div>
		</div>
	</section>
</div>