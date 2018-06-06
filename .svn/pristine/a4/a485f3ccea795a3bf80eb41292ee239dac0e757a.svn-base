<style>
	.info-box-icon {
    border-top-left-radius: 5px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 5px;
    display: block;
    float: left;
    height: 55px;
    width: 70px;
    text-align: center;
    font-size: 45px;
    line-height: 52px;
		color: white;
    background: rgb(60, 141, 188);
	}
	.info-box {
    display: block;
		min-height: 0px; 
    background: #fff;
    width: 18%;
    height: 55px;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    border-radius: 6px;
    margin-bottom: 15px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Comedor Esc. <?= $escuela->nombre_corto; ?> <label class="label label-default"><?php echo $mes_nombre; ?></label>
			<a class="btn btn-primary" data-toggle="modal" data-target="#datepicker_modal"><i class="fa fa-refresh"></i> Cambiar</a>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id; ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li>Comedor</li>
			<li class="active">Cursos y Divisiones</li>
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
		<?php if ($cerrado): ?> 
			<div class="alert alert-warning alert-dismissable">
				<i class="icon fa fa-lock" style="font-size: 18px;"></i>Periodo de carga cerrado.
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta <?php echo $class['escritorio']; ?>" href="escuela/escritorio/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-home"></i> Escritorio
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="comedor/escuela/ver/<?php echo $comedor_presupuesto->id; ?>">
							<i class="fa fa-tasks"></i> Cursos y Divisiones
						</a>
							<?php if (empty($cerrado) ): ?>
							<a class="btn bg-blue btn-app btn-app-zetta" href="comedor/escuela/modal_importar_alumnos/<?php echo $comedor_presupuesto->id; ?> "data-remote="false" data-toggle="modal" data-target="#remote_modal" ><i class="fa fa-sign-in" id="btn-agregar"></i> Importar Alum.
							</a>
							<?php endif; ?>
						<?php if (empty($comedor_presupuesto->dias_albergado)): ?>
							<?php if (empty($cerrado)): ?>
								<a class="btn bg-blue btn-app btn-app-zetta pull-right" href="comedor/escuela/modal_dias_albergue/<?php echo $comedor_presupuesto->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal">
									<i class="fa fa-calendar"></i> Cargar Días
								</a>
							<?php endif; ?>
						<?php else: ?>
							<?php if (empty($cerrado)): ?>
								<a class="btn bg-yellow btn-app btn-app-zetta pull-right" href="comedor/escuela/modal_dias_albergue/<?php echo $comedor_presupuesto->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal">
									<i class="fa fa-calendar"></i> Editar Días
								</a>
							<?php endif; ?>
							<div class="info-box" style="float: right;">
								<span class="info-box-icon"><i class="fa fa-calendar-check-o"></i></span>

								<div class="info-box-content" style="margin-left: 65px;">
									<span class="info-box-text">Días de albergue</span>
									<span class="info-box-number"><?php echo $comedor_presupuesto->dias_albergado; ?></span>
								</div>
							</div>
						<?php endif; ?>
						<div class="info-box" style="float: right;">
								<span class="info-box-icon"><i class="fa fa-calendar-times-o"></i></span>

								<div class="info-box-content" style="margin-left: 65px;">
									<span class="info-box-text">Fecha de Cierre</span>
									<span class="info-box-number"><?php echo $fecha_cierre;?></span>
								</div>
							</div>
						<hr style="margin: 10px 0;">
						<div class="row">
							<div class="col-md-12">
								<?php if (!empty($comedor_divisiones)): ?>
									<table class="table table-bordered table-condensed table-striped table-hover">
										<thead>
											<tr>
												<th>División</th>
												<th>Turno</th>
												<th style="width: 150px;">Alumnos</th>
												<th style="width: 150px;">Ración Completa</th>
												<th style="width: 150px;">Media Ración</th>
												<th style="width: 150px;">Sin Ración</th>
												<?php if (empty($cerrado)): ?>
													<th style="width: 34px;"></th>
												<?php endif; ?>
											</tr>
										</thead>
										<tbody>
											<?php $total_alumnos = 0; ?>
											<?php $total_alumnos_r1 = 0; ?>
											<?php $total_alumnos_r2 = 0; ?>
											<?php $total_alumnos_sr = 0; ?>
											<?php foreach ($comedor_divisiones as $division): ?>
												<tr>
													<td><?php echo "$division->curso $division->division"; ?></td>
													<td><?php echo "$division->turno"; ?></td>
													<td class="text-center"><?php echo empty($division->alumnos) ? '' : $division->alumnos; ?></td>
													<td class="text-center"><?php echo empty($division->alumnos_r1) ? '' : $division->alumnos_r1; ?></td>
													<td class="text-center"><?php echo empty($division->alumnos_r2) ? '' : $division->alumnos_r2; ?></td>
													<td class="text-center"><?php echo empty($division->alumnos_sr) ? '' : $division->alumnos_sr; ?></td>
													<?php if (empty($cerrado)): ?>
														<td>
															<a class="btn btn-xs" href="comedor/division/ver/<?php echo $comedor_presupuesto->id; ?>/<?php echo $division->id; ?>"><i class="fa fa-search"></i></a>
														</td>
													<?php endif; ?>
												</tr>
												<?php $total_alumnos += $division->alumnos; ?>
												<?php $total_alumnos_r1 += $division->alumnos_r1; ?>
												<?php $total_alumnos_r2 += $division->alumnos_r2; ?>
												<?php $total_alumnos_sr += $division->alumnos_sr; ?>
											<?php endforeach; ?>
										</tbody>
										<tr><th colspan="7"></th></tr>
										<tr>
											<th colspan="2">Totales generales para la escuela</th>
											<th style="text-align: center;"><?php echo $total_alumnos; ?></th>
											<th style="text-align: center;"><?php echo $total_alumnos_r1; ?></th>
											<th style="text-align: center;"><?php echo $total_alumnos_r2; ?></th>
											<th style="text-align: center;"><?php echo $total_alumnos_sr; ?></th>
											<?php if (empty($cerrado)): ?>
												<th></th>
											<?php endif; ?>
										</tr>
									</table>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="escuela/escritorio/<?= $escuela->id; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<?php echo form_open("comedor/escuela/cambiar_mes/$escuela->id/$comedor_presupuesto->mes"); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Cambiar Mes</h4>
			</div>
			<div style="display:none;" id="div_servicio_baja"></div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-12" style="text-align:center;">
						<div id="datepicker" data-date="<?php echo $mes; ?>"></div>
						<input type="hidden" name="mes" id="mes" />
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

<script type="text/javascript">
	$(document).ready(function() {
		var fechas = <?= json_encode($meses); ?>;
		var array_fecha = [];
		$("#datepicker").datepicker({
			format: "dd/mm/yyyy",
			startView: "months",
			minViewMode: "months",
			language: 'es',
			todayHighlight: false,
			beforeShowMonth: function(i) {
				var formattedDate = new Date(i);
				var m = formattedDate.getMonth();
				m += 1;
				var y = formattedDate.getFullYear();
				ames = y + '' + m;
				if (ames.length === 5) {
					array_fecha = y + '' + '0' + m;
				} else {
					array_fecha = y + '' + m;
				}
				if ($.inArray(array_fecha, fechas) > -1) {
					return;
				} else {
					return {
						enabled: false
					};
				}
			}
		});
		$("#datepicker").on("changeDate", function(event) {
			$("#mes").val($("#datepicker").datepicker('getFormattedDate'))
		});

	});
</script>

