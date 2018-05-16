<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Llamados <?= $filtro === 'pendientes' ? 'Pendientes' : ($filtro === 'publicados' ? 'Publicados' : ''); ?> de Esc. <?= $escuela->nombre_corto; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="llamados/llamado/listar/<?php echo $escuela->id; ?>">Llamados</a></li>
			<li class="active"><?php echo ucfirst($metodo); ?> <?= $filtro === 'pendientes' ? 'Pendientes' : ($filtro === 'publicados' ? 'Publicados' : ''); ?></li>
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
						<a class="btn btn-app btn-app-zetta" href="escuela/ver/<?php echo $escuela->id; ?>">
							<i class="fa fa-search"></i> Ver escuela
						</a>
						<a class="btn btn-app btn-app-zetta <?= $filtro === 'todos' ? 'active btn-app-zetta-active' : '' ?>" href="llamados/llamado/listar/<?php echo $escuela->id; ?>">
							<i class="fa fa-phone"></i> Llamados
						</a>
						<a class="btn btn-app btn-app-zetta <?= $filtro === 'pendientes' ? 'active btn-app-zetta-active' : '' ?>" href="llamados/llamado/listar/<?php echo $escuela->id; ?>/pendientes">
							<i class="fa fa-phone"></i> Pendientes
						</a>
						<a class="btn btn-app btn-app-zetta <?= $filtro === 'publicados' ? 'active btn-app-zetta-active' : '' ?>" href="llamados/llamado/listar/<?php echo "$escuela->id"; ?>/publicados">
							<i class="fa fa-phone"></i> Publicados
						</a>
						<div class="btn-group pull-right" role="group">
							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-external-link"></i> Otros accesos <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								<li><a class="dropdown-item btn-default" href="cargo/listar/<?php echo $escuela->id; ?>"><i class="fa fa-users"></i> Cargos</a></li>
								<li><a class="dropdown-item btn-default" href="servicio/listar/<?php echo $escuela->id; ?>"><i class="fa fa-bookmark"></i> Servicios</a></li>
								<li><a class="dropdown-item btn-default" href="servicio_novedad/listar/<?php echo $escuela->id; ?>"><i class="fa fa-calendar"></i> Novedades</a></li>
								<li><a class="dropdown-item btn-default" href="asisnov/listar/<?php echo $escuela->id; ?>"><i class="fa fa-print"></i> Asis y Nov</a></li>
								<li><a class="dropdown-item btn-default" href="alumno/listar/<?php echo $escuela->id; ?>"><i class="fa fa-users"></i> Alumnos</a></li>
								<li><a class="dropdown-item btn-default" href="division/listar/<?php echo $escuela->id; ?>"><i class="fa fa-tasks"></i> Cursos y Divisiones</a></li>
								<li><a class="dropdown-item btn-default" href="escuela_carrera/listar/<?php echo $escuela->id; ?>"><i class="fa fa-book"></i> Carreras</a></li>
							</ul>
						</div>
						<hr style="margin: 10px 0;">
						<?php echo $js_table; ?>
						<div class="text-sm">
							<?php echo $html_table; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var llamado_table;
	function complete_llamado_table() {
		agregar_filtros('llamado_table', llamado_table, 23);
	}
</script>