<style>
	.content-header>h1>small {
    font-size: 20px;
    display: inline-block;
    padding-left: 4px;
		font-weight: 500;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?php echo $title ?> <small>Ingreso al nivel secundario</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Escuelas</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="ingreso/portal/instructivos">
							<i class="fa fa-file-pdf-o"></i> Instructivos
						</a>
						<a class="btn btn-app btn-app-zetta" href="uploads/feria/listado_de_escuelas_2018.pdf" target="_blank">
							<i class="fa fa-building"></i> Listado de escuelas
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="ingreso/portal/resultado">
							<i class="fa fa-newspaper-o"></i> Resultados
						</a>
						<a class="btn btn-app btn-app-zetta" href="">
							<i class="fa fa-newspaper-o"></i> Vacantes
						</a>
						<a class="btn btn-app btn-app-zetta" href="ingreso/portal/listar_escuelas">
							<i class="fa fa-search"></i> Busq. escuelas
						</a>
						<hr style="margin: 10px 0;">
						<div class="row">
							<div class="col-md-12">
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="ingreso/portal/escritorio" title="Volver">Volver</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>	