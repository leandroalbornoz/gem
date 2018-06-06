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
			Alumnos <?= "$division->curso $division->division"; ?> - <label class="label label-default"><?php echo $ciclo_lectivo; ?></label><a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#datepicker_modal"><i class="fa fa-refresh"></i> Cambiar</a>
		</h1>
		<ol class="breadcrumb">
			<?php if (in_array($this->rol->codigo, array(ROL_ASISTENCIA_DIVISION))): ?>
				<li><i class="fa fa-home"></i> Inicio</li>
				<li><?= "Esc. $escuela->nombre_corto"; ?></li>
				<?php if (!empty($division)): ?>
					<li><?php echo "$division->curso $division->division"; ?></li>
				<?php endif; ?>
				<li class="active"><?php echo "Inasistencias"; ?></li>
			<?php else: ?>
				<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
				<li><a href="escuela/ver/<?= $escuela->id; ?>"><?= "Esc. $escuela->nombre_corto"; ?></a></li>
				<li><a href="division/listar/<?= $escuela->id ?>">Cursos y Divisiones</a></li>
				<?php if (!empty($division)): ?>
					<li><a href="division/ver/<?= $division->id ?>"><?php echo "$division->curso $division->division"; ?></a></li>
				<?php endif; ?>
				<li class="active"><?php echo "Inasistencias"; ?></li>
			<?php endif; ?>
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
			<div class="col-xs-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Información de la División</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-6"><label>Turno:</label> <?= $division->turno; ?></div>
							<div class="col-sm-6"><label>Curso:</label> <?= $division->curso; ?></div>
							<div class="col-sm-12"><label>Division:</label> <?= $division->division; ?></div>
							<div class="col-sm-12"><label>Carrera:</label> <?= $carrera->descripcion; ?></div>
							<div class="col-sm-6"><label>Modalidad:</label> <?= $division->modalidad; ?></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Alumnos</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<table class="table table-condensed table-bordered">
							<thead>
								<tr>
									<th>Ciclo Lectivo</th>
									<th>Alumnos</th>
									<th>M</th>
									<th>F</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($alumnos_div as $cl => $alumnos_sexo): ?>
									<tr>
										<?php $class = $cl == date('Y') ? 'bg-green' : 'bg-red' ?>
										<td><?= "<span style='margin-top: -2px; padding: 1px 7px; margin-left: 2px;' class='badge $class'>$cl</span><br>"; ?></td>
										<td><?= $alumnos_sexo['M'] + $alumnos_sexo['F'] + $alumnos_sexo['N']; ?></td>
										<td><?= $alumnos_sexo['M']; ?></td>
										<td><?= $alumnos_sexo['F']; ?></td>
									<?php endforeach; ?>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="box box-primary collapsed-box">
					<div class="box-header with-border">
						<h3 class="box-title" >Alumnos Activos</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body ">
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" id="tbl_listar_alumnos_inscriptos" role="grid">
							<thead>
								<tr>
									<th>Documento</th>
									<th>Nombre</th>
									<th>F.Nacimiento</th>
									<th>Sexo</th>
									<th>Condición</th>
									<th>Desde</th>
									<th>Causa Entrada</th>
									<th>Hasta</th>
									<th >C.L.</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($alumnos)): ?>
									<?php foreach ($alumnos as $alumno): ?>
										<?php if ($alumno->ciclo_lectivo == $ciclo_lectivo): ?>
											<tr>
												<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
												<td><?= $alumno->persona; ?></td>
												<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
												<td><?= substr($alumno->sexo, 0, 1); ?></td>
												<td><?= $alumno->condicion; ?></td>
												<td><?= empty($alumno->fecha_desde) ? '' : (new DateTime($alumno->fecha_desde))->format('d/m/Y'); ?></td>
												<td><?= $alumno->causa_entrada; ?></td>
												<td><?= empty($alumno->fecha_hasta) ? '' : (new DateTime($alumno->fecha_hasta))->format('d/m/Y'); ?></td>
												<td><?= $alumno->ciclo_lectivo; ?></td>
											</tr>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<?php if (in_array($this->rol->codigo, array(ROL_DOCENTE, ROL_ADMIN, ROL_USI, ROL_DIR_ESCUELA))): ?>
			<div class="row">
				<div class="col-md-12">
					<div class="box box-primary collapsed-box">
						<div class="box-header with-border">
							<h3 class="box-title" >Cursadas de la división</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
							</div>
						</div>
						<div class="box-body ">
							<table id="tbl_ver_cargos" class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline text-sm" role="grid" >
								<thead>
									<tr>
										<th style="width: 20%;text-align: center;">Materia</th>
										<th style="width: 2%;">Carga horaria</th>
										<th style="width: 3%;text-align: center;">Alumnos</th>
										<th style="width: 25%;text-align: center;">Grupo</th>
										<th style="width: 5%;text-align: center;">Desde</th>
										<th style="width: 20%;text-align: center;">Cargo/s</th>
										<th style="width: 10%;text-align: center;">Hs cubiertas</th>
										<?php if ($escuela->nivel_id === '7'): ?>
											<th style="width: 5%;text-align: center;">Cuatr.</th>
										<?php endif; ?>
										<th style="width: 10%;text-align: center;"></th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($cursadas_division)): ?>
										<?php foreach ($cursadas_division as $ec_id => $cursada): ?>
											<tr>
												<td><?php echo $cursada->espacio_curricular; ?></td>
												<td><?php echo $cursada->carga_horaria; ?></td>
												<td><?php echo $cursada->alumnos; ?></td>
												<td><?php echo $cursada->grupo; ?></td>
												<td><?php echo (new DateTime($cursada->fecha_desde))->format('d/m/Y') ?></td>
												<td>
													<?php echo $cursada->personas_cargo; ?><br>
												</td>
												<td style="text-align: center;">
													<?php if (empty($cursada->cargo_cursada)): ?>
														Sin horas cubiertas
													<?php else: ?>
														<?php echo $cursada->carga_horaria_cargo; ?>
													<?php endif; ?>
												</td>
												<?php if ($escuela->nivel_id === '7'): ?>
													<td style="text-align: center;">
														<?php echo ($cursada->cuatrimestre === '0') ? "Anual" : (($cursada->cuatrimestre === '1') ? "1°" : "2°"); ?>
													</td>
												<?php endif; ?>
												<td>
													<div style="margin-left: 15%;" class="btn-group" role="group">
														<a class="btn btn-xs btn-primary" href="cursada/escritorio/<?php echo $cursada->id; ?>" title="Escritorio">&nbsp;<i class="fa fa-desktop" id="btn-agregar"></i>&nbsp;Escritorio</a>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr style="text-align:center">
											<td colspan="9">-- Sin cursadas cargadas en la división --</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Carga de asistencias</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
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
				</div>
			</div>
		</div>
		<?= $estadisticas; ?>
		<?php if (in_array($this->rol->codigo, $this->roles_administrar)): ?>
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
<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<?php echo form_open("division/cambiar_ciclo_lectivo/$division->id/$ciclo_lectivo"); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Cambiar Ciclo Lectivo</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-12" style="text-align:center;">
						<div id="datepicker" data-date="<?php echo "01/01/$ciclo_lectivo"; ?>"></div>
						<input type="hidden" name="ciclo_lectivo" id="ciclo_lectivo" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
				<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Seleccionar'), 'Seleccionar'); ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<script>
	var alumno_table;
	function complete_alumno_table() {
		agregar_filtros('alumno_table', alumno_table, 6);
	}
</script>
<script type="text/javascript">
	$(document).ready(function() {
		var fecha = "<?= date("d/m/Y"); ?>";
		$("#datepicker").datepicker({
			format: "dd/mm/yyyy",
			startView: "years",
			minViewMode: "years",
			language: 'es',
			todayHighlight: false,
			startDate: "01/01/2017",
			endDate: fecha

		});
		$("#datepicker").on("changeDate", function(event) {
			$("#ciclo_lectivo").val($("#datepicker").datepicker('getFormattedDate'))
		});
	});
</script>