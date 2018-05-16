<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Editar horas de <?php echo "$division->curso $division->division"; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="division/listar/<?= $escuela->id ?>">Cursos y Divisiones</a></li>
			<li><a href="division/ver/<?= $division->id ?>"><?php echo "$division->curso $division->division"; ?></a></li>
			<li class="active">Editar horas</li>
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
						<a class="btn btn-app btn-app-zetta" href="division/ver/<?php echo $division->id; ?>">
							<i class="fa fa-search"></i> Ver división
						</a>
						<a class="btn btn-app btn-app-zetta" href="division/ver_horario/<?php echo $division->id; ?>">
							<i class="fa fa-clock-o"></i> Ver horario
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="division/editar_horario/<?php echo $division->id; ?>">
							<i class="fa fa-clock-o"></i> Editar horas
						</a>
						<a class="btn btn-app btn-app-zetta" href="division/asignar_horario/<?php echo $division->id; ?>">
							<i class="fa fa-clock-o"></i> Asignar materias
						</a>
<div class="btn-group pull-right" role="group" style="margin-left: 5px;">
							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-external-link"></i> Otros accesos <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								<?php if ($this->rol->codigo !== ROL_ESCUELA_ALUM): ?>
									<li><a class="dropdown-item btn-default" href="division/cargos/<?php echo $division->id; ?>"><i class="fa fa-fw fa-users" id="btn-cargos"></i> Cargos</a></li>
								<?php endif; ?>
								<li><a class="dropdown-item btn-default" href="division/alumnos/<?php echo (!empty($division->id)) ? $division->id : ''; ?>"><i class="fa  fa-fw fa-users" id="btn-alumnos"></i> Alumnos</a></li>
							</ul>
						</div>
						<table class="table" style="text-align: center;">
							<tr>
								<th style="text-align: center;">Hora</th>
								<?php foreach ($dias as $dia): ?>
									<th style="text-align: center;"><?php echo mb_substr($dia->nombre, 0, 2); ?></th>
								<?php endforeach; ?>
							</tr>
							<?php for ($hora_catedra = 1; $hora_catedra <= $max_hora_catedra; $hora_catedra++): ?>
								<tr>
									<td style="vertical-align: middle;"><?php echo $hora_catedra; ?></td>
									<?php foreach ($dias as $dia): ?>
										<td style="vertical-align: middle;">
											<?php if (isset($horarios[$hora_catedra][$dia->id])): ?>
												<?php echo substr($horarios[$hora_catedra][$dia->id]->hora_desde, 0, 5) . ' - ' . substr($horarios[$hora_catedra][$dia->id]->hora_hasta, 0, 5) . ($horarios[$hora_catedra][$dia->id]->obligaciones === '1.0' ? '' : '<br/>(' . $horarios[$hora_catedra][$dia->id]->obligaciones . ' oblig.)') . '<br/>'; ?>
												<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="horario/modal_editar/<?= $horarios[$hora_catedra][$dia->id]->id; ?>"><i class="fa fa-edit"></i></a>
												<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="horario/modal_eliminar/<?= $horarios[$hora_catedra][$dia->id]->id; ?>"><i class="fa fa-close"></i></a>
											<?php else: ?>
												<a class="btn btn-xs btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="horario/modal_agregar/<?= "$division->id/$dia->id/$hora_catedra"; ?>"><i class="fa fa-plus"></i></a>
											<?php endif; ?>
										</td>
									<?php endforeach; ?>
								</tr>
							<?php endfor; ?>
							<tr>
								<td style="vertical-align: middle;"><?php echo $hora_catedra; ?></td>
								<?php foreach ($dias as $dia): ?>
									<td style="vertical-align: middle;">
										<a class="btn btn-xs btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="horario/modal_agregar/<?= "$division->id/$dia->id/$hora_catedra"; ?>"><i class="fa fa-plus"></i></a>
									</td>
								<?php endforeach; ?>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>