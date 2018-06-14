<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Carreras de <?= $escuela->nombre_corto; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id; ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="escuela_carrera/listar/<?= $escuela->id; ?>">Carreras</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="escuela/ver/<?php echo $escuela->id; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver escuela
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="escuela_carrera/listar/<?php echo $escuela->id; ?>">
							<i class="fa fa-book" id="btn-carreras"></i> Carreras
						</a>
						<?php if ($edicion): ?>
							<a class="btn bg-blue btn-app btn-app-zetta" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="escuela_carrera/modal_agregar/<?= $escuela->id; ?>">
								<i class="fa fa-plus" id="btn-agregar"></i> Agregar
							</a>
						<?php endif; ?>
						
						<div class="btn-group pull-right" role="group" style="margin-left: 5px;">
							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-external-link"></i> Otros accesos <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								<?php if ($this->rol->codigo !== ROL_ESCUELA_ALUM): ?>
									<li><a class="dropdown-item btn-default" href="servicio/listar/<?php echo $escuela->id . '/'; ?>"><i class="fa  fa-fw fa-bookmark"></i>Servicios</a></li>
									<li><a class="dropdown-item btn-default"  href="cargo/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-users"></i> Cargos</a></li>
								<?php endif; ?>
								<li><a class="dropdown-item btn-default" href="alumno/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-users"></i> Alumnos</a></li>

								<li><a class="dropdown-item btn-default" href="division/listar/<?php echo $escuela->id; ?>"><i class="fa fa-fw fa-tasks"></i> Cursos y Divisiones</a></li>
								<li><a class="dropdown-item btn-default" href="asisnov/index/<?php echo $escuela->id."/".date('Ym'); ?>">
							<i class="fa fa-fw fa-print"></i> Asis y Nov</a></li>

							</ul>
						</div>
						<hr style="margin: 10px 0;">
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>