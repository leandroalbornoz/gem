<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?php if (!empty($escuela)): ?>
				<?php echo "$escuela->nombre_largo - Asistencia de alumnos"; ?> <label class="label label-default"><?php echo $ciclo_lectivo; ?></label>
				<a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#datepicker_modal"><i class="fa fa-refresh"></i> Cambiar</a>
			<?php else: ?>
				Escuelas
			<?php endif; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Escuelas</a></li>
			<?php if (!empty($escuela)): ?>
				<li><?php echo "Esc. $escuela->nombre_largo"; ?></li>
			<?php endif; ?>
			<li class="active"><?php echo "Resumen asistencias alumnos"; ?></li>
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
						<?php if (!empty($calendarios)): ?>
							<?php foreach ($calendarios as $calendario): ?>
								<?php if (!empty($calendario)): ?>
									<h3><u><?php echo $calendario[0]->calendario; ?></u></h3>
									<table class="table table-hover table-bordered dt-responsive" role="grid" >
										<thead>
											<tr>
												<th colspan="14" class="text-center bg-gray"></th>
											</tr>
											<tr>
												<th style="width: 15px;" rowspan="2">Curso y divisiones</th>
												<?php foreach ($calendario as $periodo): ?>
													<?php $fecha_ini = new DateTime(substr($periodo->inicio, 0, 8) . '01'); ?>
													<?php $fecha_fin = new DateTime($periodo->fin); ?>
													<?php $dia = DateInterval::createFromDateString('1 month'); ?>
													<?php $fechas = new DatePeriod($fecha_ini, $dia, $fecha_fin); ?>
													<?php $columnas = (new DateTime($periodo->fin))->format('m') - (new DateTime($periodo->inicio))->format('m') + 1; ?>
													<th style="text-align: center; width: 50px;" colspan="<?php echo $columnas; ?>"><?php echo "{$periodo->periodo}° $periodo->nombre_periodo"; ?></th>
												<?php endforeach; ?>
											</tr>
											<tr>
												<?php foreach ($calendario as $periodo): ?>
													<?php $fecha_ini = new DateTime(substr($periodo->inicio, 0, 8) . '01'); ?>
													<?php $fecha_fin = new DateTime($periodo->fin); ?>
													<?php $dia = DateInterval::createFromDateString('1 month'); ?>
													<?php $fechas = new DatePeriod($fecha_ini, $dia, $fecha_fin); ?>
													<?php foreach ($fechas as $fecha): ?>
												<th style="text-align: center; width: 25px;"><?php echo substr($this->nombres_meses[$fecha->format('m')], 0, 3); ?><br/><a href="escuela/imprimir_asistencia_mensual/<?php echo $escuela->id; ?>/<?php echo $fecha->format('Y'); ?>/<?php echo $fecha->format('m'); ?>/<?php echo $periodo->periodo; ?>" target="_blank" class="label bg-primary" style="font-size: 100%;" title="Ver estadísticas"><i class="fa fa-print" aria-hidden="true"></i> <span style="font-size: 12px;">Reporte</span></a></th>
													<?php endforeach; ?>
												<?php endforeach; ?>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($divisiones_escuela as $division_escuela): ?>
												<?php if ($division_escuela->nombre_periodo == $calendario[0]->nombre_periodo): ?>
													<tr>
														<td>
															<?php echo "$division_escuela->division"; ?>
															<a href="division_inasistencia/listar/<?php echo "$division_escuela->id/$ciclo_lectivo"; ?>" target="_blank" class="label bg-primary pull-right" style="font-size: 100%;" title="Ver estadísticas" ><i class="fa fa-bar-chart"></i></a>
														</td>
														<?php foreach ($calendario as $periodo): ?>
															<?php $fecha_ini = new DateTime(substr($periodo->inicio, 0, 8) . '01'); ?>
															<?php $fecha_fin = new DateTime($periodo->fin); ?>
															<?php $dia = DateInterval::createFromDateString('1 month'); ?>
															<?php $fechas = new DatePeriod($fecha_ini, $dia, $fecha_fin); ?>
															<?php foreach ($fechas as $fecha): ?>
																<td style="text-align: center; width: 25px;">
																	<?php if (!empty($division_mes_inasistencia[$periodo->nombre_periodo][$division_escuela->id][$periodo->periodo][$fecha->format('Ym')]->division_inasistencia_id)): ?>
																		<?php if (($division_mes_inasistencia["$periodo->nombre_periodo"][$division_escuela->id][$periodo->periodo][$fecha->format('Ym')]->dia_abierto) != 0): ?>
																			<?php echo isset($division_mes_inasistencia[$periodo->nombre_periodo][$division_escuela->id][$periodo->periodo][$fecha->format('Ym')]) ? (($division_mes_inasistencia[$periodo->nombre_periodo][$division_escuela->id][$periodo->periodo][$fecha->format('Ym')]->asistencia_media === "100.00000") ? number_format($division_mes_inasistencia[$periodo->nombre_periodo][$division_escuela->id][$periodo->periodo][$fecha->format('Ym')]->asistencia_media, 0, ',', '') . "%" : number_format($division_mes_inasistencia[$periodo->nombre_periodo][$division_escuela->id][$periodo->periodo][$fecha->format('Ym')]->asistencia_media, 2, ',', '') . "%" ) : ""; ?>
																		<?php else: ?>
																			0,00%
																		<?php endif; ?>
																		<?php if (isset($division_mes_inasistencia[$periodo->nombre_periodo][$division_escuela->id][$periodo->periodo][$fecha->format('Ym')])): ?>
																			<?php if (!empty($division_mes_inasistencia[$periodo->nombre_periodo][$division_escuela->id][$periodo->periodo][$fecha->format('Ym')]->fecha_notificacion)): ?>
																				<span class="btn-xs text-green pull-left" style="font-size: 100%;" title="Asistencia notificada" ><i class="fa fa-envelope"></i></span>
																			<?php endif; ?>
																			<?php if (!empty($division_mes_inasistencia[$periodo->nombre_periodo][$division_escuela->id][$periodo->periodo][$fecha->format('Ym')]->fecha_cierre)): ?>
																				<a href="division_inasistencia/ver/<?php echo $division_mes_inasistencia[$periodo->nombre_periodo][$division_escuela->id][$periodo->periodo][$fecha->format('Ym')]->division_inasistencia_id; ?>" target="_blank" class="label bg-green pull-right" style="font-size: 100%;" title="Ver mes"><i class="fa fa-lock"></i></a>
																			<?php else: ?>
																				<a href="division_inasistencia/ver/<?php echo $division_mes_inasistencia[$periodo->nombre_periodo][$division_escuela->id][$periodo->periodo][$fecha->format('Ym')]->division_inasistencia_id; ?>" target="_blank" class="label bg-yellow pull-right" style="font-size: 100%;" title="Ver mes" ><i class="fa fa-unlock-alt"></i></a>
																			<?php endif; ?>
																		<?php endif; ?>
																	<?php endif; ?>
																	<br><span class="text-sm"><?php echo isset($division_mes_inasistencia[$periodo->nombre_periodo][$division_escuela->id][$periodo->periodo][$fecha->format('Ym')]->alumnos) ? $division_mes_inasistencia[$periodo->nombre_periodo][$division_escuela->id][$periodo->periodo][$fecha->format('Ym')]->alumnos : "0"; ?> <i class="fa fa-user text-info" title="Alumnos"></i></span>
																</td>
															<?php endforeach; ?>
														<?php endforeach; ?>
													</tr>
												<?php endif; ?>
											<?php endforeach; ?>
										</tbody>
									</table>
									<br>
								<?php else: ?>
									<div class="alert alert-info alert-dismissable">
										<h4><i class="icon fa fa-ban"></i> Registro no encontrado!</h4> No posee registro de asistencias de alumnos cargados.			
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="escuela/escritorio/<?php echo $escuela->id; ?>" title="<?php echo "Volver"; ?>"><?php echo "Volver"; ?></a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<?php echo form_open("escuela/cambiar_cl_alumnos/$escuela->id/$ciclo_lectivo"); ?>
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
			<div style="display:none;" id="div_persona_buscar_listar"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#datepicker").datepicker({
			format: "dd/mm/yyyy",
			startView: "years",
			minViewMode: "years",
			language: 'es',
			todayHighlight: false,
			endDate: '+0y'
		});
		$("#datepicker").on("changeDate", function(event) {
			$("#ciclo_lectivo").val($("#datepicker").datepicker('getFormattedDate'))
		});
	});
</script>
