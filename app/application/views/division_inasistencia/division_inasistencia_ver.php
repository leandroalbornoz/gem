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
	.info-box-icon{
		float: left;
    height: 40px;
    width: 40px;
    text-align: center;
    font-size: 32px;
    line-height: 40px;
	}
	.badge{
		width:40px;
	}
	.cchild {
		display: none;
	}
	.open .cchild {
		display: table-row;
	}
	.parent {
		cursor: pointer;
	}
	.parent > *:last-child {
		width: 30px;
	}
	.parent i {
		transform: rotate(0deg);
		transition: transform .3s cubic-bezier(.4,0,.2,1);
		margin: -.5rem;
		padding: .5rem;
	}
	.open .parent i {
		transform: rotate(180deg)
	}
	.scroll_horizontal {
		overflow-x: auto;
		white-space: nowrap;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Alumnos <?= "$division->curso $division->division"; ?> - <label class="label label-default"><?php echo $ciclo_lectivo; ?></label>
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

			<?php if ($division_inasistencia->resumen_mensual !== 'No'): ?>
				<li class="active"><?php echo "Carga mensual"; ?></li>
			<?php else: ?>
				<li class="active"><?php echo "Carga diaria"; ?></li>
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
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">
							<?php $dias_semana = array('Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'); ?>
							<?php $fecha_ini_m = new DateTime($mes . '01'); ?>
							<?php $fecha_ini_p = new DateTime($division_inasistencia->fecha_inicio); ?>
							<?php $fecha_fin_m = new DateTime($mes . '01 +1 month'); ?>
							<?php $fecha_fin_p = new DateTime($division_inasistencia->fecha_fin . ' +1 day'); ?>
							<?php $fecha_ini = max(array($fecha_ini_m, $fecha_ini_p)); ?>
							<?php $fecha_fin = min(array($fecha_fin_m, $fecha_fin_p)); ?>
							<?php $dia = DateInterval::createFromDateString('1 day'); ?>
							<?php $fechas = new DatePeriod($fecha_ini, $dia, $fecha_fin); ?>
							Inasistencias <?php echo "{$division_inasistencia->periodo}° $division_inasistencia->nombre_periodo"; ?> - Mes de <?php echo $this->nombres_meses[substr($mes, 4, 2)] . ' ' . $ciclo_lectivo; ?> (<?php echo $fecha_ini->format('d/m'); ?> al <?php echo (new DateTime($fecha_fin->format('Y-m-d') . ' -1 day'))->format('d/m'); ?>)</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
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
							<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="division_inasistencia/ver/<?php echo "$division_inasistencia->id/$orden"; ?>">
								<i class="fa fa-<?php echo empty($division_inasistencia->fecha_cierre) ? 'calendar-o' : 'calendar-check-o'; ?>"></i> <?php echo $this->nombres_meses[substr($mes, 4, 2)]; ?>
							</a>
							<?php if (!empty($division_inasistencia->fecha_cierre)): ?>
								<?php if ($division_inasistencia->resumen_mensual === 'Si'): ?>
									<a class="btn bg-default btn-app" href="division_inasistencia/resumen_mensual_imprimir_pdf/<?php echo "$division_inasistencia->id/$orden"; ?>" title="Exportar PDF Inasistencias" target="_blank"><i class="fa fa-file-pdf-o text-red"></i>Resumen</a>
								<?php else: ?>
									<a class="btn bg-default btn-app" href="division_inasistencia/resumen_diario_imprimir_pdf/<?php echo "$division_inasistencia->id/$orden"; ?>" title="Exportar PDF Inasistencias" target="_blank"><i class="fa fa-file-pdf-o text-red"></i>Resumen</a>
								<?php endif; ?>
							<?php endif; ?>
							<?php if (empty($division_inasistencia->fecha_cierre)): ?>
								<?php if ($edicion === TRUE): ?>
									<a data-remote="false" data-toggle="modal" data-target="#remote_modal"  href="division_inasistencia/modal_eliminar_mes/<?php echo "$division_inasistencia->id/$orden"; ?>" class="btn bg-red btn-app pull-right" title="Cerrar Mes">
										<i class="fa fa-trash"></i> Eliminar carga
									</a>
									<?php if ($division_inasistencia->resumen_mensual === 'No'): ?>
										<a data-remote="false" data-toggle="modal" data-target="#remote_modal"  href="division_inasistencia/modal_cerrar_mes/<?php echo "$division_inasistencia->id/$orden"; ?>" class="btn bg-red btn-app pull-right" title="Cerrar Mes">
											<i class="fa fa-lock"></i> Cerrar Mes
										</a>
									<?php else: ?>
										<?php if (!empty($dias_mes_abierto)): ?>
											<a data-remote="false" data-toggle="modal" data-target="#remote_modal"  href="division_inasistencia/modal_cerrar_mes/<?php echo "$division_inasistencia->id/$orden"; ?>" class="btn bg-red btn-app pull-right" title="Cerrar Mes">
												<i class="fa fa-lock"></i> Cerrar Mes
											</a>
										<?php endif; ?>
									<?php endif; ?>
								<?php endif; ?>
							<?php else: ?>
								<?php if (in_array($this->rol->codigo, $this->roles_admin)): ?>
									<a data-remote="false" data-toggle="modal" data-target="#remote_modal"  href="division_inasistencia/modal_abrir_mes_cerrado/<?php echo "$division_inasistencia->id/$orden"; ?>" class="btn bg-yellow btn-app pull-right" title="Abrir Mes">
										<i class="fa fa-unlock-alt"></i> Abrir Mes
									</a>
								<?php endif; ?>
							<?php endif; ?>
							<!-- Tabla inasistencias dia a dia o resumen Mes Cerrada -->
							<?php if (!empty($division_inasistencia->fecha_cierre)): ?>
								<div class="row">
									<div class="col-sm-12">
										<h4>Días hábiles de cursado: <?php echo $division_inasistencia->dias; ?></h4>
										<table id="inasistencia_table" class="table table-hover table-bordered table-condensed text-sm open" role="grid">
											<thead>
												<tr>
													<th style="width: 340px;" colspan="<?php echo ($division_inasistencia->resumen_mensual !== 'Si') ? "2" : ($division->grado_multiple === 'Si') ? "14" : "13"; ?>" class="text-center"><?php echo "{$division_inasistencia->periodo}° $division_inasistencia->nombre_periodo"; ?> - Mes de <?php echo $this->nombres_meses[$fecha_ini->format('m')]; ?>
														<a class="btn btn-xs btn-success pull-left" href="division_inasistencia/ver/<?php echo "$division_inasistencia->id/" . ($orden === '1' ? '2' : '1'); ?>"><i class="fa fa-sort-alpha-asc"></i> Cambiar orden de alumnos</a>
														<div style="width:25px; margin: 2px;" class="pull-right parent">
															<span class="sign pull-right"><i class="fa fa-chevron-down"></i></span>
														</div>
													</th>
												</tr>
												<tr>
													<?php if ($division->grado_multiple === 'Si'): ?>
														<th style="width: 5px;" rowspan="2">Curso</th>
													<?php endif; ?>
													<th style="width: 15px;" rowspan="2">Documento</th>
													<th style="width: 50px;" rowspan="2">Nombre</th>
													<th style="width: 50px;" rowspan="2">Fecha de Ingreso</th>
													<th style="width: 50px;" rowspan="2">Fecha de Egreso</th>
													<th style="text-align: center; width: 50px;" colspan="2">Resumen Mensual</th>
													<th style="text-align: center; width: 50px;" colspan="2">Acumuladas</th>
													<th style="text-align: center; width: 50px;" colspan="2">Totales</th>
													<th style="width: 50px;" rowspan="2">Total del mes</th>
													<th style="width: 50px;" rowspan="2">Asistencia</th>
													<th style="width: 50px;" rowspan="2">% de Asistencia</th>
												</tr>
												<tr>
													<th style="text-align: center; width: 25px;" title="Justificadas">J</th>
													<th style="text-align: center; width: 25px;" title="Injustificadas">I</th>
													<th style="text-align: center; width: 25px;" title="Justificadas">J</th>
													<th style="text-align: center; width: 25px;" title="Injustificadas">I</th>
													<th style="text-align: center; width: 25px;" title="Justificadas">J</th>
													<th style="text-align: center; width: 25px;" title="Injustificadas">I</th>
												</tr>
											</thead>
											<tbody>
												<?php $total_general_inasistencias = 0; ?>
												<?php $total_general_asistencia_ideal = 0; ?>
												<?php foreach ($alumnos as $alumno): ?>
													<?php $total_inasistencias_acumulada_justificada = (isset($inasistencias_resumen[$alumno->id]['Si']) ? $inasistencias_resumen[$alumno->id]['Si'] : 0) + (isset($inasistencias_acumulada_resumen[$alumno->id]['Si']) ? $inasistencias_acumulada_resumen[$alumno->id]['Si'] : 0); ?>
													<?php $total_inasistencias_acumulada_injustificada = (isset($inasistencias_resumen[$alumno->id]['No']) ? $inasistencias_resumen[$alumno->id]['No'] : 0) + (isset($inasistencias_acumulada_resumen[$alumno->id]['No']) ? $inasistencias_acumulada_resumen[$alumno->id]['No'] : 0); ?>
													<?php if (($total_inasistencias_acumulada_justificada + $total_inasistencias_acumulada_injustificada) >= 15): ?>
														<?php $colorear = "style='background-color:#ffb8b8'"; ?>
													<?php elseif (($total_inasistencias_acumulada_justificada + $total_inasistencias_acumulada_injustificada) >= 10): ?>
														<?php $colorear = "style='background-color:#ffffb6'"; ?>
													<?php elseif (($total_inasistencias_acumulada_justificada + $total_inasistencias_acumulada_injustificada) >= 5): ?>
														<?php $colorear = "style='background-color:#ceffc8'"; ?>
													<?php else: ?>
														<?php $colorear = "style=''"; ?>
													<?php endif; ?>
													<tr class="cchild" <?php echo $colorear; ?>>
														<?php if ($division->grado_multiple === 'Si'): ?>
															<td><?php echo "$alumno->curso"; ?></td>
														<?php endif; ?>
														<td><?php echo "$alumno->documento_tipo $alumno->documento"; ?></td>
														<td><?php echo "$alumno->persona"; ?></td>
														<td style="text-align: center;">
															<?= empty($alumno->fecha_desde) ? '' : (new DateTime($alumno->fecha_desde))->format('d/m/Y'); ?>
														</td>
														<td style="text-align: center;">
															<?= empty($alumno->fecha_hasta) ? '' : (new DateTime($alumno->fecha_hasta))->format('d/m/Y'); ?>
														</td>
														<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px" value="<?php echo isset($inasistencias_resumen[$alumno->id]['Si']) ? number_format(($inasistencias_resumen[$alumno->id]['Si']), 1, '.', '') : '0.0'; ?>"></td>
														<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px" value="<?php echo isset($inasistencias_resumen[$alumno->id]['No']) ? number_format(($inasistencias_resumen[$alumno->id]['No']), 1, '.', '') : '0.0'; ?>"></td>
														<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px" value="<?php echo isset($inasistencias_acumulada_resumen[$alumno->id]['Si']) ? number_format(($inasistencias_acumulada_resumen[$alumno->id]['Si']), 1, '.', '') : '0.0'; ?>"></td>
														<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px" value="<?php echo isset($inasistencias_acumulada_resumen[$alumno->id]['No']) ? number_format(($inasistencias_acumulada_resumen[$alumno->id]['No']), 1, '.', '') : '0.0'; ?>"></td>
														<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px" value="<?php echo number_format($total_inasistencias_acumulada_justificada, 1, '.', ''); ?>"></td>
														<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px" value="<?php echo number_format($total_inasistencias_acumulada_injustificada, 1, '.', ''); ?>"></td>
														<?php $total_inasistencias = (isset($inasistencias_resumen[$alumno->id]['No']) ? $inasistencias_resumen[$alumno->id]['No'] : 0) + (isset($inasistencias_resumen[$alumno->id]['Si']) ? $inasistencias_resumen[$alumno->id]['Si'] : 0); ?>
														<?php $total_general_inasistencias += $total_inasistencias; ?>
														<?php $asistencia_ideal = $division_inasistencia->dias - (isset($inasistencias_resumen[$alumno->id]['NC']) ? $inasistencias_resumen[$alumno->id]['NC'] : 0); ?>
														<?php $total_general_asistencia_ideal += $asistencia_ideal; ?>
														<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px"value="<?php echo (isset($total_inasistencias) ? number_format($total_inasistencias, 1, '.', '') : 0.0); ?>"></td>
														<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px"value="<?php echo number_format(($asistencia_ideal - $total_inasistencias), 1, '.', ''); ?>"></td>
														<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:50px"value="<?php echo number_format(empty($asistencia_ideal) || $asistencia_ideal == 0 ? 0 : (($asistencia_ideal - $total_inasistencias) === 0 ? 0 : ($asistencia_ideal - $total_inasistencias) / $asistencia_ideal), 3, '.', '') * 100; ?> %"></td>
													<?php endforeach; ?>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-md-12">
										<?php echo $resumen; ?>
									</div>
									<div class="col-md-12">
										<ul class="chart-legend clearfix">
											<li><i class="fa fa-square" style="color:#ceffc8"></i> Alumnos que superan las 5 inasistencias</li>
											<li><i class="fa fa-square" style="color:#ffffb6"></i> Alumnos que superan las 10 inasistencias</li>
											<li><i class="fa fa-square" style="color:#ffb8b8"></i> Alumnos que superan las 15 inasistencias</li>
										</ul>
									</div>
								</div>
							<?php endif; ?>
							<!-- Tabla inasistencias mensual -->
							<?php if ($division_inasistencia->resumen_mensual === 'Si'): ?>
								<?php if (empty($division_inasistencia->fecha_cierre)): ?>
									<?php if ($edicion === TRUE): ?>
										<a data-remote="false" data-toggle="modal" data-target="#remote_modal"  href="division_inasistencia/modal_editar_dias/<?php echo "$division_inasistencia->id/$orden"; ?>" class="btn bg-yellow btn-app pull-right" title="Guardar Mes">
											<i class="fa fa-edit"></i> Editar Dias
										</a>
									<?php endif; ?>
									<table id="inasistencia_table" class="table table-hover table-bordered table-condensed text-sm" role="grid" >
										<thead>
											<tr>
												<th style="width: 340px;" colspan="<?php echo ($division_inasistencia->resumen_mensual !== 'Si') ? "2" : ($division->grado_multiple === 'Si') ? "9" : "8"; ?>" class="text-center">
													<?php echo "{$division_inasistencia->periodo}° $division_inasistencia->nombre_periodo"; ?> - Mes de <?php echo $this->nombres_meses[$fecha_ini->format('m')]; ?>
													<a class="btn btn-xs btn-success pull-left" href="division_inasistencia/ver/<?php echo "$division_inasistencia->id/" . ($orden === '1' ? '2' : '1'); ?>"><i class="fa fa-sort-alpha-asc"></i> Cambiar orden de alumnos</a>
												</th>
												<?php if ($edicion === TRUE): ?>
													<th style="text-align: center;">
														<?php if (!empty($dias_mes_abierto)): ?>
															<a class="btn btn-warning btn-ms" href="division_inasistencia/editar_resumen_mensual/<?php echo $dias_mes_abierto[0]->id . "/$orden"; ?>" title="Editar"><i class="fa fa-edit"></i> Editar</a>
														<?php else: ?>
															<a class="btn btn-success btn-ms" href="division_inasistencia/agregar_resumen_mensual/<?php echo "$division_inasistencia->id/$orden"; ?>" title="Agregar"><i class="fa fa-plus"></i> Cargar</a>
														<?php endif; ?>
													</th>
												<?php else: ?>
													<th></th>
												<?php endif; ?>
											</tr>
											<tr>
												<?php if ($division->grado_multiple === 'Si'): ?>
													<th style="width: 5px;" rowspan="2">Curso</th>
												<?php endif; ?>
												<th style="width: 50px;" rowspan="2">Documento</th>
												<th style="width: 100px;" rowspan="2">Nombre</th>
												<th style="width: 50px;" rowspan="2">Fecha de ingreso</th>
												<th style="width: 50px;" rowspan="2">Fecha de Egreso</th>
												<th style="width: 50px;" rowspan="2">Asistencia Ideal</th>
												<th style="width: 50px;" rowspan="2">Asistencia media</th>
												<th style="text-align: center; width: 50px;" colspan="2">Resumen Mensual</th>
												<th style="width: 50px; text-align: center;" rowspan="2">Total de Inasistencias</th>
											</tr>
											<tr>
												<th style="text-align: center; width: 25px;" title="Justificadas">J</th>
												<th style="text-align: center; width: 25px;" title="Injustificadas">I</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($alumnos_resumen_mensual as $alumno): ?>
												<tr>
													<?php if ($division->grado_multiple === 'Si'): ?>
														<td><?php echo "$alumno->curso"; ?></td>
													<?php endif; ?>
													<td><?php echo "$alumno->documento_tipo $alumno->documento"; ?></td>
													<td><?php echo "$alumno->persona"; ?></td>
													<td>
														<?= empty($alumno->fecha_desde) ? '' : (new DateTime($alumno->fecha_desde))->format('d/m/Y'); ?>
													</td>
													<td>
														<?= empty($alumno->fecha_hasta) ? '' : (new DateTime($alumno->fecha_hasta))->format('d/m/Y'); ?>
													</td>
													<?php $total_inasistencias = (isset($alumno->No) ? $alumno->No->falta : 0) + (isset($alumno->Si) ? $alumno->Si->falta : 0); ?>
													<?php $dias_NC = (isset($alumno->Prev) ? $alumno->Prev->falta : 0) + (isset($alumno->Post) ? $alumno->Post->falta : 0); ?>
													<?php $asistencia_ideal = $division_inasistencia->dias - $dias_NC; ?>
													<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px"value="<?php echo number_format($asistencia_ideal, 1, '.', ''); ?>"></td>
													<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:50px"value="<?php echo number_format(empty($asistencia_ideal) || $asistencia_ideal == 0 ? 0 : (($asistencia_ideal - $total_inasistencias) === 0 ? 0 : ($asistencia_ideal - $total_inasistencias) / $asistencia_ideal), 3, '.', '') * 100; ?> %"></td>
													<?php if (isset($alumno->Si)): ?>
														<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px" value="<?php echo $alumno->Si->falta; ?>"></td>
													<?php else: ?>
														<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px" value="0.0"></td>
													<?php endif; ?>
													<?php if (isset($alumno->No)): ?>
														<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px"value="<?php echo $alumno->No->falta; ?>"></td>
													<?php else: ?>
														<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px" value="0.0"></td>
													<?php endif; ?>
													<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px"value="<?php echo (isset($total_inasistencias) ? number_format($total_inasistencias, 1, '.', '') : 0.0); ?>"></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>

								<?php endif; ?>
							<?php endif; ?>
							<!-- Tabla inasistencias dia a dia-->
							<?php if ($division_inasistencia->resumen_mensual === 'No'): ?>
								<?php if (empty($division_inasistencia->fecha_cierre)): ?>
									<div class="scroll_horizontal">
										<table id="inasistencia_table" class="table table-hover table-bordered table-condensed text-sm" role="grid" >
											<thead>
												<tr>
													<th style="width: 340px;" colspan="<?php echo ($division_inasistencia->resumen_mensual !== 'Si') ? (($division->grado_multiple === 'Si') ? "3" : "2") : "7"; ?>" class="text-center">
														<?php echo "{$division_inasistencia->periodo}° $division_inasistencia->nombre_periodo"; ?> - Mes de <?php echo $this->nombres_meses[$fecha_ini->format('m')]; ?>
														<a class="btn btn-xs btn-success pull-left" href="division_inasistencia/ver/<?php echo "$division_inasistencia->id/" . ($orden === '1' ? '2' : '1'); ?>"><i class="fa fa-sort-alpha-asc"></i> Cambiar orden de alumnos</a>
													</th>
													<th colspan="2" style="text-align: center;">
													</th>
													<?php foreach ($fechas as $fecha): ?>
														<?php $dia_semana = $fecha->format('w'); ?>
														<?php if ($edicion === TRUE): ?>
															<th class="dia_<?php echo $dia_semana; ?>" style="text-align: center; width: 20px;">
																<?php if (isset($dias[$fecha->format('Y-m-d')])): ?>
																	<a class="btn btn-warning btn-xs" href="division_inasistencia/editar_dia/<?php echo $dias[$fecha->format('Y-m-d')]->id . "/$orden"; ?>" title="Editar"><i class="fa fa-edit"></i></a>
																<?php else: ?>
																	<a class="btn btn-success btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="division_inasistencia/modal_agregar_dia/<?php echo "$division_inasistencia->id/" . $fecha->format('Ymd') . "/$orden"; ?>" title="Agregar"><i class="fa fa-plus"></i></a>
																<?php endif; ?>
															</th>
														<?php else: ?>
															<th></th>
														<?php endif; ?>
													<?php endforeach; ?>
												</tr>
												<tr>
													<?php if ($division->grado_multiple === 'Si'): ?>
														<th style="width: 5px;" rowspan="2">Curso</th>
													<?php endif; ?>
													<th style="width: 50px;" rowspan="2">Documento</th>
													<th style="width: 100px;" rowspan="2">Nombre</th>
													<th style="text-align: center; width: 50px;" colspan="2">Inasistencias</th>
													<?php foreach ($fechas as $fecha): ?>
														<?php $dia_semana = $fecha->format('w'); ?>
														<th class="dia_<?php echo $dia_semana; ?>" style="text-align: center; width: 20px;">
															<?php echo $fecha->format('d'); ?>
														</th>
													<?php endforeach; ?>
												</tr>
												<tr>
													<th style="text-align: center; width: 25px;" title="Justificadas">J</th>
													<th style="text-align: center; width: 25px;" title="Injustificadas">I</th>
													<?php foreach ($fechas as $fecha): ?>
														<?php $dia_semana = $fecha->format('w'); ?>
														<th class="dia_<?php echo $dia_semana; ?>" style="text-align: center; width: 20px;">
															<?php echo $dias_semana[$dia_semana]; ?>
														</th>
													<?php endforeach; ?>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($alumnos as $alumno): ?>
													<tr>
														<?php if ($division->grado_multiple === 'Si'): ?>
															<td><?php echo "$alumno->curso"; ?></td>
														<?php endif; ?>
														<td><?php echo "$alumno->documento_tipo $alumno->documento"; ?></td>
														<td><?php echo "$alumno->persona"; ?></td>
														<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px" value="<?php echo isset($inasistencias_resumen[$alumno->id]['Si']) ? number_format($inasistencias_resumen[$alumno->id]['Si'], 1, '.', '') : '0.0'; ?>"></td>
														<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px" value="<?php echo isset($inasistencias_resumen[$alumno->id]['No']) ? number_format($inasistencias_resumen[$alumno->id]['No'], 1, '.', '') : '0.0'; ?>"></td>
														<?php foreach ($fechas as $fecha): ?>
															<?php $dia_semana = $fecha->format('w'); ?>
															<?php $inasistencia_TP = isset($inasistencias_mes[$alumno->id][$fecha->format('Y-m-d')]['No']) ? $inasistencias_mes[$alumno->id][$fecha->format('Y-m-d')]['No'] : FALSE; ?>
															<?php $inasistencia_CT = isset($inasistencias_mes[$alumno->id][$fecha->format('Y-m-d')]['Si']) ? $inasistencias_mes[$alumno->id][$fecha->format('Y-m-d')]['Si'] : FALSE; ?>
															<td class="dia_<?php echo $dia_semana; ?>" style="text-align: center; width: 20px;">
																<?php if (isset($dias[$fecha->format('Y-m-d')]) && (($dias[$fecha->format('Y-m-d')]->contraturno === 'Si') || ($dias[$fecha->format('Y-m-d')]->contraturno === 'Parcial' && (empty($inasistencia_CT) || $inasistencia_CT->inasistencia_tipo_id !== '9')))): ?>
																	<sup><?php echo $inasistencia_TP ? $inasistencia_TP->falta : "<span class='label' style='border:1px solid gray;'>P</span>"; ?></sup>
																	<sub><?php echo!empty($inasistencia_CT) ? $inasistencia_CT->falta : "<span class='label' style='border:1px solid gray;'>P</span>"; ?></sub><br/>
																<?php else: ?>
																	<?php echo $inasistencia_TP ? $inasistencia_TP->falta : ''; ?><br/>
																<?php endif; ?>
															</td>
														<?php endforeach; ?>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
									<hr>
									<ul class="chart-legend clearfix">
										<div class="col-md-3">
											<li><span class="label bg-green">A</span> Ausente, Justificado</li>
											<li><span class="label bg-red">A</span> Ausente, No justificado</li>
											<li><span class="label bg-blue">A</span> Ausente, No computable</li>
											<li><span class="label bg-red">P</span> Ausente por tardanza</li>
										</div>
										<div class="col-md-3">
											<li><span class="label bg-green">T</span> Tardanza, Justificada</li>
											<li><span class="label bg-red">T</span> Tardanza, No justificada</li>
										</div>
										<div class="col-md-3">
											<li><span class="label bg-green">R</span> Retira antes, Justificado</li>
											<li><span class="label bg-red">R</span> Retira antes, No justificado</li>
											<li><span class="fa fa-fw fa-minus"></span> Alumno no perteneciente al curso el día de la fecha</li>
										</div>
									</ul>
								<?php endif; ?>
							<?php endif; ?>
					</div>
					<?php if (!(in_array($this->rol->codigo, array(ROL_ASISTENCIA_DIVISION)))): ?>
					<div class="box-footer">
						<a class="btn btn-default" href="division_inasistencia/<?php echo empty($txt_btn) ? "listar/$division->id" : ""; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
					</div>
					<?php else: ?>
					<div class="box-footer">
						<a class="btn btn-default" href="division/escritorio/<?php echo "$division->id/$ciclo_lectivo"; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<!-- Tabla inasistencias dia a dia Cerrada-->
		<?php if ($division_inasistencia->resumen_mensual === 'No'): ?>
			<?php if ($division_inasistencia->fecha_cierre): ?>
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Informe de asistencia por día</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								</div>
							</div>
							<div class="box-body">
								<table id="inasistencia_table" class="table table-hover table-bordered table-condensed text-sm" role="grid" >
									<thead>
										<tr>
											<th style="width: 340px;" colspan="<?php echo ($division_inasistencia->resumen_mensual !== 'Si') ? (($division->grado_multiple === 'Si') ? "3" : "2") : "7"; ?>" class="text-center"><?php echo "{$division_inasistencia->periodo}° $division_inasistencia->nombre_periodo"; ?> - Mes de <?php echo $this->nombres_meses[$fecha_ini->format('m')]; ?></th>
											<th colspan="2" style="text-align: center;">
												<span class="label bg-yellow"> MES CERRADO</span>
											</th>
											<?php foreach ($fechas as $fecha): ?>
												<?php $dia_semana = $fecha->format('w'); ?>
												<th></th>
											<?php endforeach; ?>
										</tr>
										<tr>
											<?php if ($division->grado_multiple === 'Si'): ?>
												<th style="width: 5px;" rowspan="2">Curso</th>
											<?php endif; ?>
											<th style="width: 50px;" rowspan="2">Documento</th>
											<th style="width: 100px;" rowspan="2">Nombre</th>
											<th style="text-align: center; width: 50px;" colspan="2">Inasistencias</th>
											<?php foreach ($fechas as $fecha): ?>
												<?php $dia_semana = $fecha->format('w'); ?>
												<th class="dia_<?php echo $dia_semana; ?>" style="text-align: center; width: 20px;">
													<?php echo $fecha->format('d'); ?>
												</th>
											<?php endforeach; ?>
										</tr>
										<tr>
											<th style="text-align: center; width: 25px;" title="Justificadas">J</th>
											<th style="text-align: center; width: 25px;" title="Injustificadas">I</th>
											<?php foreach ($fechas as $fecha): ?>
												<?php $dia_semana = $fecha->format('w'); ?>
												<th class="dia_<?php echo $dia_semana; ?>" style="text-align: center; width: 20px;">
													<?php echo $dias_semana[$dia_semana]; ?>
												</th>
											<?php endforeach; ?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($alumnos as $alumno): ?>
											<tr>
												<?php if ($division->grado_multiple === 'Si'): ?>
													<td><?php echo "$alumno->curso"; ?></td>
												<?php endif; ?>
												<td><?php echo "$alumno->documento_tipo $alumno->documento"; ?></td>
												<td><?php echo "$alumno->persona"; ?></td>
												<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px" value="<?php echo isset($inasistencias_resumen[$alumno->id]['Si']) ? number_format($inasistencias_resumen[$alumno->id]['Si'], 1, '.', '') : '0.0'; ?>"></td>
												<td style="text-align: center; width: 25px;"><input disabled style="text-align:center; width:25px" value="<?php echo isset($inasistencias_resumen[$alumno->id]['No']) ? number_format($inasistencias_resumen[$alumno->id]['No'], 1, '.', '') : '0.0'; ?>"></td>
												<?php foreach ($fechas as $fecha): ?>
													<?php $dia_semana = $fecha->format('w'); ?>
													<?php $inasistencia_TP = isset($inasistencias_mes[$alumno->id][$fecha->format('Y-m-d')]['No']) ? $inasistencias_mes[$alumno->id][$fecha->format('Y-m-d')]['No'] : FALSE; ?>
													<?php $inasistencia_CT = isset($inasistencias_mes[$alumno->id][$fecha->format('Y-m-d')]['Si']) ? $inasistencias_mes[$alumno->id][$fecha->format('Y-m-d')]['Si'] : FALSE; ?>
													<td class="dia_<?php echo $dia_semana; ?>" style="text-align: center; width: 20px;">
														<?php if (isset($dias[$fecha->format('Y-m-d')]) && $dias[$fecha->format('Y-m-d')]->contraturno === 'Si'): ?>
															<sup><?php echo $inasistencia_TP ? $inasistencia_TP->falta : "<span class='label' style='border:1px solid gray;'>P</span>"; ?></sup>
															<sub><?php echo $inasistencia_CT ? $inasistencia_CT->falta : "<span class='label' style='border:1px solid gray;'>P</span>"; ?></sub><br/>
														<?php else: ?>
															<?php echo $inasistencia_TP ? $inasistencia_TP->falta : ''; ?><br/>
														<?php endif; ?>
													</td>
												<?php endforeach; ?>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
							<div class="box-footer">
								<ul class="chart-legend clearfix">
									<div class="col-md-3">
										<li><span class="label bg-green">A</span> Ausente, Justificado</li>
										<li><span class="label bg-red">A</span> Ausente, No justificado</li>
										<li><span class="label bg-blue">A</span> Ausente, No computable</li>
										<li><span class="label bg-red">P</span> Ausente por tardanza</li>
									</div>
									<div class="col-md-3">
										<li><span class="label bg-green">T</span> Tardanza, Justificada</li>
										<li><span class="label bg-red">T</span> Tardanza, No justificada</li>
									</div>
									<div class="col-md-3">
										<li><span class="label bg-green">R</span> Retira antes, Justificado</li>
										<li><span class="label bg-red">R</span> Retira antes, No justificado</li>
										<li><span class="fa fa-fw fa-minus"></span> Alumno no perteneciente al curso el día de la fecha</li>
									</div>
								</ul>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</section>
</div>
<script>
	$(document).ready(function() {
		$('table').on('click', '.parent .fa-chevron-down', function() {
			$(this).closest('table').toggleClass('open');
		});
	});
</script>