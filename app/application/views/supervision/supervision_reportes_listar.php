<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Reportes - Supervisión <?php echo $supervision->nombre; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li class="active"> Reportes</li>
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
					<div class="box-body ">
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver_escuelas']; ?>" href="supervision/escuelas/<?php echo $supervision_id;?>">
							<i class="fa fa-home" id="btn-ver"></i> Escuelas
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver_reportes']; ?>" href="supervision/reportes/<?php echo $supervision_id;?>">
							<i class="fa fa-file-excel-o" id="btn-ver"></i> Reportes
						</a>
						<div class="list-group">
							<a href="supervision/reporte_escuelas/<?php echo $supervision_id;?>" class="list-group-item"><i class="fa fa-file-excel-o"></i> Escuelas</a>
							<a href="supervision/reporte_cargos/<?php echo $supervision_id;?>" class="list-group-item"><i class="fa fa-file-excel-o"></i> Cargos</a>
							<a href="supervision/reporte_servicios/<?php echo $supervision_id;?>" class="list-group-item"><i class="fa fa-file-excel-o"></i> Servicios</a>
							<a href="supervision/reporte_novedades/<?php echo $supervision_id;?>" class="list-group-item"><i class="fa fa-file-excel-o"></i> Novedades</a>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="escritorio" title="Volver">Volver</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>