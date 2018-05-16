<div class="content-wrapper">
	<section class="content-header">
		<h1>Reportes Estáticos</h1>
		<ol class="breadcrumb">
			<li><i class="fa fa-home"></i> Inicio</li>
			<li><a class="active" href="consultas/reportes_estaticos/Escritorio">Reportes Estaticos</a></li>
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
		<div class="box box-primary">
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-primary">
							<div class="panel-heading">Reporte de asistencia, por calendario.</div>
							<div class="panel-body">
								Genera reportes de asisitencia, segun el calendario académico de las escuelas elegidas.
								<a href="consultas/reportes_estaticos/reportes_asistencia_calendario" class="btn btn-success pull-right"><i class="fa fa-table"></i>&nbsp;Ir al reporte</a>
							</div>
						</div>
						<div class="panel panel-primary">
							<div class="panel-heading">Reporte de asistencia, por carrera.</div>
							<div class="panel-body">
								Genera reportes de asistencia, segun las carreras de asociadas a las escuelas.
								<a href="consultas/reportes_estaticos/reportes_asistencia_carrera" class="btn btn-success pull-right"><i class="fa fa-table"></i>&nbsp;Ir al reporte</a>
							</div>
						</div>
						<div class="panel panel-primary">
							<div class="panel-heading">Reporte de oferta educativa por zona.</div>
							<div class="panel-body">
								Genera reportes de carreras disponibles segun la región donde se encuentre las misma.
								<a href="consultas/reportes_estaticos/reportes_oferta_educativa" class="btn btn-success pull-right"><i class="fa fa-table"></i>&nbsp;Ir al reporte</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>