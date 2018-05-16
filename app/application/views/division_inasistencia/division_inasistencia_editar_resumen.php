<style>
	.inasistencia-0.active{
		color: green;
	}
	.inasistencia-0-5.active{
		color: yellowgreen;
	}
	.inasistencia-1.active{
		color: red;
	}
	.active{
		font-weight: bold;
	}
	input[type="number"].form-control{
		text-align:center;
		height:25px;
		width:50px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Inasistencias alumnos <?= "$division->curso $division->division"; ?> - Mes <?php echo $this->nombres_meses[substr($mes, 4, 2)]; ?> - <label class="label label-default"><?php echo $ciclo_lectivo; ?></label>
		</h1>
		<ol class="breadcrumb">
			<?php if (in_array($this->rol->codigo, array(ROL_ASISTENCIA_DIVISION))): ?>
				<li><a href="division/escritorio/<?=$division->id."/".$ciclo_lectivo;?>"><i class="fa fa-home"></i> Inicio</a></li>
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
				<li><a href="division_inasistencia/listar/<?php echo "$division->id/$ciclo_lectivo"; ?>"><?php echo "Inasistencias"; ?></a></li>
			<?php endif; ?>

			<li><a href="division_inasistencia/ver/<?php echo "$division_inasistencia->id/$orden"; ?>">Carga mensual</a></li>
			<li class="active"><?php echo "Agregar resumen"; ?></li>
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
				<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
				<div class="box box-primary">
					<div class="box-body">
						<?php if (!(in_array($this->rol->codigo, array(ROL_ASISTENCIA_DIVISION)))): ?>
							<a class="btn btn-app btn-app-zetta" href="division/ver/<?php echo (!empty($division->id)) ? $division->id : ''; ?>">
								<i class="fa fa-search" id="btn-ver"></i> Ver división
							</a>
							<a class="btn btn-app btn-app-zetta" href="division/alumnos/<?php echo $division->id; ?>">
								<i class="fa fa-users"></i> Alumnos
							</a>
						<?php endif; ?>
						<?php if (!(in_array($this->rol->codigo, array(ROL_ASISTENCIA_DIVISION)))): ?>
							<a class="btn btn-app btn-app-zetta" href="division_inasistencia/listar/<?php echo "$division->id/$ciclo_lectivo"; ?>">
							<?php else: ?>
								<a class="btn btn-app btn-app-zetta" href="division/escritorio/<?php echo "$division->id/$ciclo_lectivo"; ?>">
								<?php endif; ?>
								<i class="fa fa-clock-o"></i> Asistencia
							</a>
							<a class="btn btn-app btn-app-zetta" href="division_inasistencia/ver/<?php echo "$division_inasistencia->id/$orden"; ?>">
								<i class="fa fa-<?php echo empty($division_inasistencia->fecha_cierre) ? 'calendar-o' : 'calendar-check-o'; ?>"></i> <?php echo $this->nombres_meses[substr($mes, 4, 2)]; ?>
							</a>
							<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="division_inasistencia/agregar_resumen_mensual/<?php echo "$division_inasistencia->id/$orden"; ?>">
								<i class="fa fa-clock-o"></i> Asistencia Mensual
							</a>
							<?php $dias_semana = array('Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'); ?>
							<?php $fecha_ini_m = new DateTime($mes . '01'); ?>
							<?php $fecha_ini_p = new DateTime($division_inasistencia->fecha_inicio); ?>
							<?php $fecha_fin_m = new DateTime($mes . '01 +1 month'); ?>
							<?php $fecha_fin_p = new DateTime($division_inasistencia->fecha_fin . ' +1 day'); ?>
							<?php $fecha_ini = max(array($fecha_ini_m, $fecha_ini_p)); ?>
							<?php $fecha_fin = min(array($fecha_fin_m, $fecha_fin_p)); ?>
							<?php $dia = DateInterval::createFromDateString('1 day'); ?>
							<?php $fechas = new DatePeriod($fecha_ini, $dia, $fecha_fin); ?>
							<h4 class="box-title"><strong>
									Inasistencias <?php echo "{$division_inasistencia->periodo}° $division_inasistencia->nombre_periodo"; ?> - Mes de <?php echo $this->nombres_meses[substr($mes, 4, 2)] . ' ' . $ciclo_lectivo; ?> (<?php echo $fecha_ini->format('d/m'); ?> al <?php echo (new DateTime($fecha_fin->format('Y-m-d') . ' -1 day'))->format('d/m'); ?>),
									Días hábiles de cursado <?php echo $division_inasistencia->dias; ?>
								</strong></h4>
							<table class="table table-hover table-bordered table-condensed text-sm" role="grid">
								<thead>
									<tr style="background-color: #f1f1f1">
										<th style="text-align: center;" colspan="8">
											Resumen Mensual de Inasistencias
										</th>
									</tr>
									<tr>
										<th style="">Documento</th>
										<th style="">Nombre</th>
										<th style="text-align: center;">Justificadas</th>
										<th style="text-align: center;">Injustificadas</th>
										<th style="text-align: center;">Días de cursado previos al alta</th>
										<th style="text-align: center;">Días de cursado posteriores a la baja</th>							
										<th style="text-align: center;">Fecha de Ingreso</th>
										<th style="text-align: center;">Fecha de Egreso</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($alumnos as $alumno): ?>
										<tr>
											<td><?php echo "$alumno->documento_tipo $alumno->documento"; ?></td>
											<td><?php echo "$alumno->persona"; ?></td>
											<td align="center">
												<input type="number" name="justificadas[<?php echo $alumno->id; ?>]" min="0" max="<?php echo $division_inasistencia->dias; ?>" step="0.5" value="<?php echo isset($alumno->Si) ? $alumno->Si->falta : ''; ?>" class="form-control carga-dias">
											</td>
											<td align="center">
												<input type="number" name="injustificadas[<?php echo $alumno->id; ?>]" min="0" max="<?php echo $division_inasistencia->dias; ?>" step="0.5" value="<?php echo isset($alumno->No) ? $alumno->No->falta : ''; ?>" class="form-control carga-dias">
											</td>
											<?php if (!empty($alumno->fecha_desde) && ((new DateTime($alumno->fecha_desde))->format('Y-m-d')) > $fecha_ini->format('Y-m-d')): ?>
												<td align="center">
													<input type="number" name="dias_previos[<?php echo $alumno->id; ?>]" min="0" max="<?php echo $alumno->dias; ?>" value="<?php echo isset($alumno->Prev) ? round($alumno->Prev->falta) : ''; ?>" class="form-control carga-dias">
												</td>
											<?php else: ?>
												<td>
													<input type="hidden" name="dias_previos[<?php echo $alumno->id; ?>]" value="0">
												</td>
											<?php endif; ?>
											<?php if (!empty($alumno->fecha_hasta) && ((new DateTime($alumno->fecha_hasta . ' +1 day')) < (new DateTime($fecha_fin->format('Y-m-d') . ' -1 day')))): ?>
												<td align="center">
													<input type="number" name="dias_posteriores[<?php echo $alumno->id; ?>]" min="0" max="<?php echo $alumno->dias; ?>" value="<?php echo isset($alumno->Post) ? round($alumno->Post->falta) : ''; ?>" class="form-control carga-dias">
												</td>
											<?php else: ?>
												<td>
													<input type="hidden" name="dias_posteriores[<?php echo $alumno->id; ?>]" value="0">
												</td>
											<?php endif; ?>
											<td align="center">
												<?= empty($alumno->fecha_desde) ? '' : (new DateTime($alumno->fecha_desde))->format('d/m/Y'); ?>
											</td>
											<td align="center">
												<?= empty($alumno->fecha_hasta) ? '' : (new DateTime($alumno->fecha_hasta))->format('d/m/Y'); ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
					</div>
					<?php form_hidden('alumno_id', $alumno->id); ?>
					<div class="box-footer">
						<input type="hidden" name="division_inasitencia_dia_id" value="<?php echo $dia_id; ?>"/>
						<a class="btn btn-default" href="division_inasistencia/<?php echo empty($txt_btn) ? "ver/$division_inasistencia->id/$orden" : ""; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo form_submit(array('class' => 'btn btn-primary pull-right supera_dias', 'title' => 'Guardar'), 'Guardar'); ?>
						<button type="submit" style="margin-right:10px;" formaction="division_inasistencia/eliminar_resumen_mensual/<?php echo $dia_id; ?>" class="btn btn-danger pull-right">Eliminar registro de asistencias</button>
						<div class="row pull-right hidden" style="padding-right: 5%;"  id="cartel">
							<div class="bg-red text-bold" style="border-radius: 2px;"><h4>La suma de inasistencias no puede superar los días hábiles de cursado</h4>
							</div>
						</div>
					</div>
				</div>
				<?php form_close(); ?>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		$('.carga-dias').change(function() {
			var tr = $(this).closest('tr');
			var dias_habiles = <?php echo $division_inasistencia->dias; ?>;
			var dias_cargados = 0;
			tr.find('.carga-dias').each(function() {
				dias_cargados += parseFloat((this.value === '' ? 0 : this.value));
				tr.css('color', '');
				$('#cartel').addClass('hidden');
				$('.supera_dias').attr('disabled', false);
			});
			if (dias_cargados > dias_habiles) {
				tr.css('color', 'red');
				$('#cartel').removeClass('hidden');
				$('.supera_dias').attr('disabled', true);
			}
		});
	});
</script>