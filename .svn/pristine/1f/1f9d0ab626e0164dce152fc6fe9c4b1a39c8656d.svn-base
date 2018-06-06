<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<style>
	.dia_0,.dia_6{
		background-color: lightgray;
	}
	.mes_asistencia{
		width: 50px;
		height: 50px;
		border: darkgray thin solid;
		border-radius: 5px;
		margin-right: 5px;
		text-align: center;
		font-weight: bold;
	}
	.mes_asistencia.active{
		background-color: #00c0ef;
		border-color: #0044cc;
	}
	#chart_2 .c3-line{
		stroke-width:4px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Alumnos <?= "$division->curso $division->division"; ?> - <label class="label label-default"><?php echo $ciclo_lectivo; ?></label>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id; ?>"><?= "Esc. $escuela->nombre_corto"; ?></a></li>
			<li><a href="division/listar/<?= $escuela->id ?>">Cursos y Divisiones</a></li>
			<?php if (!empty($division)): ?>
				<li><a href="division/ver/<?= $division->id ?>"><?php echo "$division->curso $division->division"; ?></a></li>
			<?php endif; ?>
			<li class="active"><?php echo "Inasistencias"; ?></li>
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
						<a class="btn btn-app btn-app-zetta" href="division/ver/<?php echo (!empty($division->id)) ? $division->id : ''; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver división
						</a>
						<a class="btn btn-app btn-app-zetta" href="division/alumnos/<?php echo $division->id; ?>">
							<i class="fa fa-users"></i> Alumnos
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="division_inasistencia/listar/<?php echo "$division->id/$ciclo_lectivo"; ?>">
							<i class="fa fa-clock-o"></i> Asistencia
						</a>


						<hr style="margin: 10px 0;">
						<div class="row">
							<?php foreach ($periodos as $periodo): ?>
								<div class="col-sm-4">
									<?php $fecha_ini = new DateTime(substr($periodo->inicio, 0, 8) . '01'); ?>
									<?php $fecha_fin = new DateTime($periodo->fin); ?>
									<?php $dia = DateInterval::createFromDateString('1 month'); ?>
									<?php $fechas = new DatePeriod($fecha_ini, $dia, $fecha_fin); ?>
									<h3><?php echo "{$periodo->periodo}° $periodo->nombre_periodo"; ?></h3>
									<h4><?php echo (new DateTime($periodo->inicio))->format('d/m/Y') . ' al ' . $fecha_fin->format('d/m/Y'); ?></h4>
									<?php foreach ($fechas as $fecha): ?>
										<?php if (isset($inasistencias[$periodo->periodo][$fecha->format('Ym')])): ?>
											<a href="division_inasistencia/ver/<?php echo $inasistencias[$periodo->periodo][$fecha->format('Ym')]->id; ?>" class="mes_asistencia pull-left btn <?php echo empty($inasistencias[$periodo->periodo][$fecha->format('Ym')]->fecha_cierre) ? 'btn-default' : 'btn-success'; ?> "><i class="fa fa-2x fa-<?php echo empty($inasistencias[$periodo->periodo][$fecha->format('Ym')]->fecha_cierre) ? 'calendar-o' : 'calendar-check-o'; ?>"></i><br/><?php echo substr($this->nombres_meses[$fecha->format('m')], 0, 3); ?></a>
										<?php else: ?>
											<a data-remote="false" data-toggle="modal" data-target="#remote_modal"  href="division_inasistencia/modal_abrir_mes/<?php echo "$division->id/$ciclo_lectivo/$periodo->periodo/" . $fecha->format('Ym'); ?>" class="mes_asistencia pull-left btn btn-default"><i class="fa fa-2x fa-calendar-plus-o"></i><br/><?php echo substr($this->nombres_meses[$fecha->format('m')], 0, 3); ?></a>
										<?php endif; ?>
									<?php endforeach; ?>
									<p class="clearfix"></p>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="division/<?php echo empty($txt_btn) ? "alumnos/$division->id" : ""; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
					</div>
				</div>
			</div>
		</div>
		<?= $estadisticas; ?>
		<?php if (in_array($this->rol->codigo, $this->admin_rol_asistencia)): ?>

			<div class="row">
				<div class="col-xs-6">
					<div class="box box-primary"><!-- Usuarios -->
						<div class="box-header with-border">
							<h3 class="box-title">Usuarios</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-xs-12">
									<table class="table table-bordered table-condensed table-striped table-hover">
										<tr>
											<th style="width: 25%;">Usuario</th>
											<th style="width: 35%;">CUIL<br/>Persona</th>
											<th style="width: 39%;">Rol<br/>Entidad</th>
											<th style="width: 1%;"></th>
										</tr>
										<?php if (!empty($usuarios)): ?>
											<?php foreach ($usuarios as $usuario): ?>
												<tr>
													<td><?php echo $usuario->usuario; ?></td>
													<td><?php echo "$usuario->cuil<br/>$usuario->persona"; ?></td>
													<td><?php echo "$usuario->rol<br/>$usuario->entidad"; ?></td>
													<td>
														<a class="btn btn-xs" href="usuario/ver/<?php echo $usuario->id; ?>"><i class="fa fa-search"></i></a>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td colspan="4" style="text-align: center;"> -- Sin usuarios -- </td>
											</tr>
										<?php endif; ?>
									</table>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<a class="btn btn-primary" href="division_inasistencia/administrar_rol_asistencia_division/<?= $division->id; ?>">
								<i class="fa fa-cogs"></i> Administrar
							</a>

						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</section>
</div>