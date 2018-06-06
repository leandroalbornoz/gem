<style>
	.table-striped>tbody>tr:nth-of-type(odd) {
    background-color: #d9d9d9;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Auditoría de Bajas <?php echo $planilla_tipo->descripcion; ?> - <label class="label label-default"><?php echo empty($escuela->departamento) ? 'N/D' : $escuela->departamento; ?></label> <label class="label label-default"><?php echo $mes; ?></label>
			<a class="btn btn-xs btn-primary" data-toggle="modal" data-target="#datepicker_modal"><i class="fa fa-refresh"></i> Cambiar mes</a>
		</h1>
		<ol class="breadcrumb">
			<?php if ($rol_escuela): ?>
				<li><a href="escuela/escritorio/<?php echo $escuela->id; ?>"><i class="fa fa-home"></i><?php echo "Esc. $escuela->nombre_corto"; ?></a></li>
				<li class="active">Auditoría de Bajas</li>
			<?php else: ?>
				<li><a href="liquidaciones/escritorio"><i class="fa fa-home"></i> Inicio</a></li>
				<li><a href="liquidaciones/escritorio/index/<?php echo $mes; ?>">Auditoría Bajas <?php echo $mes; ?></a></li>
				<li><a href="liquidaciones/bajas/departamento/<?php echo $planilla_tipo->id . '/' . $mes . '/' . (empty($escuela->departamento_id) ? '0' : $escuela->departamento_id); ?>"><?php echo empty($escuela->departamento) ? 'N/D' : $escuela->departamento; ?></a></li>
				<li class="active"><?php echo "Esc. $escuela->nombre_corto"; ?></li>
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
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-header with-border">
						<h3 class="box-title"><label>Escuela</label> <?php echo "$escuela->nombre_largo" . (empty($escuela->cue) ? '' : " - <label>CUE:</label> $escuela->cue") . " - <label>Ju/UO/Repa:</label> $escuela->jurisdiccion_codigo/$escuela->unidad_organizativa/$escuela->reparticion_codigo"; ?></h3>
						<div class="box-tools pull-right">
							<a class="btn btn-xs btn-success"href="asisnov/index/<?= "$escuela->id/$mes"; ?>" target="_blank">Ir a Escuela</a>
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-6"><label>Nivel:</label> <?php echo $escuela->nivel; ?></div>
							<div class="col-sm-6"><label>Supervisión:</label> <?php echo $escuela->supervision; ?></div>
							<div class="col-sm-12"><label>Domicilo:</label> <?php echo "$escuela->calle $escuela->calle_numero $escuela->localidad"; ?></div>
							<div class="col-sm-6"><label>Telefono:</label> <?php echo $escuela->telefono; ?></div>
							<div class="col-sm-6"><label>Email:</label> <?php echo "$escuela->email/$escuela->email2"; ?></div>
						</div>
					</div>
					<div class="box-footer">
						<?php if (!$rol_escuela): ?>
							<a class="btn btn-default" href="liquidaciones/bajas/aud_escuelas/<?php echo "$planilla_tipo->id/$mes"; ?>" title="Volver">Volver</a>
							<?php echo form_hidden('id', $escuela->id); ?>
						<?php endif; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
			<?php $estados = array('cargadas' => 'Cargado', 'aprobadas' => 'Auditado', 'rechazadas' => 'Rechazado', 'procesadas' => 'Procesado'); ?>
			<?php foreach ($estados as $estado_id => $estado): ?>
				<?php $count = empty($bajas_estado[$estado]) ? 0 : count($bajas_estado[$estado]); ?>
				<?php if ($count > 0): ?>
					<div class="col-xs-12">
						<div class="box box-primary">
							<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
							<div class="box-header with-border">
								<h3 class="box-title"><span class="badge"><?= $count; ?></span> Bajas <?= $estado_id; ?></h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								</div>
							</div>
							<div class="box-body">
								<table class="table table-condensed table-bordered table-striped">
									<thead>
										<tr>
											<th>Persona</th>
											<th style="min-width: 300px;">Cargo</th>
											<th>Baja</th>
											<th>Reemplaza</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($bajas_estado[$estado] as $baja): ?>
											<tr>
												<td>
													<?= $baja->cuil; ?> <b>(<?= empty($baja->liquidacion) ? '' : substr($baja->liquidacion, -2); ?>)</b>
													<?php if ($baja->estado === 'Cargado'): ?>
														<?php if ($this->edicion === TRUE): ?>
															<a class="text-sm btn btn-xs btn-warning" target="_blank" href="persona/editar/<?= $baja->persona_id; ?>"><i class="fa fa-edit"></i> Editar</a>
														<?php else: ?>
															<a class="text-sm btn btn-xs btn-primary" target="_blank" href="persona/ver/<?= $baja->persona_id; ?>"><i class="fa fa-eye"></i> Ver</a>
														<?php endif; ?>
													<?php else: ?>
														<a class="text-sm btn btn-xs btn-primary" target="_blank" href="persona/ver/<?= $baja->persona_id; ?>"><i class="fa fa-search"></i> Ver</a>
													<?php endif; ?>
													<br>
													<?= $baja->persona; ?>
													<br>
													<?= (empty($baja->fecha_nacimiento) ? '--Sin Fecha Nac.--' : (new DateTime($baja->fecha_nacimiento))->format('d/m/Y')); ?> - <b><?= $baja->situacion_revista; ?></b>
												</td>
												<td>
													<?= $baja->regimen; ?>
													<span class="text-sm"><?= $baja->regimen_desc; ?></span>-<b><?= $baja->clase === '0000' ? $baja->puntos : $baja->clase; ?></b>
													<br>
													<span class="text-sm"><?= $baja->division; ?></span>
													<br>
													<span class="text-sm"><?= $baja->materia; ?></span>
													<br>
													<?php if (!empty($baja->observaciones)): ?>
														<br>
														Obs.Serv.:<span class="text-sm"><?= $baja->observaciones; ?></span>
													<?php endif; ?>
													<?php if (!empty($baja->observaciones_c)): ?>
														<br>
														Obs.Cargo:<span class="text-sm"><?= $baja->observaciones_c; ?></span>
													<?php endif; ?>
												</td>
												<td>
													Baja: <?= (new DateTime($baja->fecha_baja))->format('d/m/Y'); ?>
													<br>
													<?php if ($baja->regimen_tipo_id === '1'): ?>
														Días: <?= min(array($baja->dias, 30)); ?>
													<?php else: ?>
														Oblig: <?= min(array($baja->obligaciones, $baja->carga_horaria * 4)); ?>
													<?php endif; ?>
													<?php if ($this->edicion === TRUE && $estado === 'Cargado'): ?>
														<a class="text-sm btn btn-xs btn-warning" href="liquidaciones/bajas/modal_editar/<?php echo $escuela->id . '/' . $mes . '/' . $baja->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Editar"><i class="fa fa-edit"></i></a>
													<?php else: ?>
														<a class="text-sm btn btn-xs btn-primary" href="liquidaciones/bajas/modal_ver/<?php echo $escuela->id . '/' . $mes . '/' . $baja->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i></a>
													<?php endif; ?>
													<a class="text-sm btn btn-xs btn-primary" href="liquidaciones/bajas/modal_ver_auditoria/<?php echo $escuela->id . '/' . $mes . '/' . $baja->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Ver modificaciones"><i class="fa fa-eye"></i></a>
													<br>
													Art: <?= "$baja->articulo-$baja->inciso $baja->novedad"; ?>
												</td>
												<td>
													<?= $baja->reemplaza_cuil; ?>
													<?= empty($baja->reemplaza_liquidacion) ? '' : '(' . substr($baja->reemplaza_liquidacion, -2) . ')'; ?>
													<br>
													<?= $baja->reemplaza_persona; ?>
													<br>
													<?= $baja->reemplaza_articulo; ?>
													<span class="text-sm"><?= $baja->reemplaza_articulo_desc; ?></span>
												</td>
												<td>
													<?php if ($this->edicion === TRUE): ?>
														<?php if ($estado === 'Cargado'): ?>
															<button class="btn btn-xs btn-success" type="submit" name="aprobar" value="<?php echo $baja->id; ?>"><i class="fa fa-check"></i> Aprobar</button>
															<br>
															<a class="btn btn-xs btn-danger" href="liquidaciones/bajas/modal_rechazar/<?php echo $escuela->id . '/' . $mes . '/' . $baja->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i> Rechazar</a>
														<?php elseif ($estado === 'Auditado'): ?>
															<button class="btn btn-xs btn-danger" type="submit" name="revertir" value="<?php echo $baja->id; ?>"><i class="fa fa-ban"></i> Revertir aprobación</button>
														<?php elseif ($estado === 'Rechazado'): ?>
															<button class="btn btn-xs btn-danger" type="submit" name="revertir" value="<?php echo $baja->id; ?>"><i class="fa fa-ban"></i> Revertir rechazo</button>
															<br><b>Motivo: </b><?php echo $baja->motivo_rechazo; ?>
														<?php endif; ?>
													<?php elseif ($estado === 'Rechazado'): ?>
														<br><b>Motivo: </b><?php echo $baja->motivo_rechazo; ?>
													<?php endif; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
							<div class="box-footer">
								<?php if (!$rol_escuela): ?>
									<?php echo form_hidden('id', $escuela->id); ?>
								<?php endif; ?>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php if (!empty($casos_revisar)): ?>
				<div class="col-xs-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><span class="badge"><?= count($casos_revisar); ?></span> Casos a Revisar - Servicios con baja GEM y liquidación activa</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body">
							<label>En los casos que efectivamente se deba realizar la baja del servicio, desde este menú se lo podrá incluir en auditoría para dar a misma al primer día del mes.<br><span class="text-red">Recordar enviar a Recupero de Haberes los meses efectivamente pagados de más según la fecha real de baja.</span></label>
							<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
								<thead>
									<tr>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($casos_revisar as $caso): ?>
										<tr>
											<td>
												<?= $caso->cuil; ?> <b>(<?= empty($caso->liquidacion) ? '' : substr($caso->liquidacion, -2); ?>)</b>
												<?php if ($this->edicion === TRUE): ?>
													<a class="text-sm btn btn-xs btn-warning" target="_blank" href="persona/editar/<?= $caso->persona_id; ?>"><i class="fa fa-edit"></i> Editar</a>
												<?php else: ?>
													<a class="text-sm btn btn-xs btn-primary" target="_blank" href="persona/ver/<?= $caso->persona_id; ?>"><i class="fa fa-eye"></i> Ver</a>
												<?php endif; ?>
												<br>
												<?= $caso->persona; ?>
												<br>
												<?= (empty($caso->fecha_nacimiento) ? '--Sin Fecha Nac.--' : (new DateTime($caso->fecha_nacimiento))->format('d/m/Y')); ?> - <b><?= $caso->situacion_revista; ?></b>
											</td>
											<td>
												<?= $caso->regimen_codigo; ?>
												<span class="text-sm"><?= $caso->regimen; ?></span>-<b><?= $caso->carga_horaria === '0' ? $caso->puntos : str_pad($caso->carga_horaria, 4, '0', STR_PAD_LEFT); ?></b>
												<br>
												<span class="text-sm"><?= $caso->division; ?></span>
												<br>
												<span class="text-sm"><?= $caso->materia; ?></span>
												<br>
												<?php if (!empty($caso->observaciones)): ?>
													<br>
													Obs.Serv.:<span class="text-sm"><?= $caso->observaciones; ?></span>
												<?php endif; ?>
												<?php if (!empty($caso->observaciones_c)): ?>
													<br>
													Obs.Cargo:<span class="text-sm"><?= $caso->observaciones_c; ?></span>
												<?php endif; ?>
											</td>
											<td>
												Alta: <?= (new DateTime($caso->fecha_alta))->format('d/m/Y'); ?>
												<br>
												Baja: <?= (new DateTime($caso->fecha_baja))->format('d/m/Y'); ?>
												<br>
												<?php if ($this->edicion === TRUE): ?>
													<a class="text-sm btn btn-xs btn-success" href="liquidaciones/bajas/modal_pasar_auditoria/<?php echo $escuela->id . '/' . $mes . '/' . $caso->servicio_id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Editar"><i class="fa fa-arrow-up"></i></a>
												<?php endif; ?>
											</td>
											<td>
												<?= $caso->reemplaza_cuil; ?>
												<?= empty($caso->reemplaza_liquidacion) ? '' : '(' . substr($caso->reemplaza_liquidacion, -2) . ')'; ?>
												<br>
												<?= $caso->reemplaza; ?>
												<br>
												<?= $caso->reemplaza_articulo; ?>
												<span class="text-sm"><?= $caso->reemplaza_articulo_desc; ?></span>
											</td>
											<td>
												<?php if ($this->edicion === TRUE): ?>
													<?php if ($estado === 'Cargado'): ?>
														<button class="btn btn-xs btn-success" type="submit" name="aprobar" value="<?php echo $caso->id; ?>"><i class="fa fa-check"></i> Aprobar</button>
														<br>
														<a class="btn btn-xs btn-danger" href="liquidaciones/bajas/modal_rechazar/<?php echo $escuela->id . '/' . $mes . '/' . $caso->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i> Rechazar</a>
													<?php elseif ($estado === 'Auditado'): ?>
														<button class="btn btn-xs btn-danger" type="submit" name="revertir" value="<?php echo $caso->id; ?>"><i class="fa fa-ban"></i> Revertir aprobación</button>
													<?php elseif ($estado === 'Rechazado'): ?>
														<button class="btn btn-xs btn-danger" type="submit" name="revertir" value="<?php echo $caso->id; ?>"><i class="fa fa-ban"></i> Revertir rechazo</button>
														<br><b>Motivo: </b><?php echo $caso->motivo_rechazo; ?>
													<?php endif; ?>
												<?php elseif ($estado === 'Rechazado'): ?>
													<br><b>Motivo: </b><?php echo $caso->motivo_rechazo; ?>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<?php if (!empty($autoridades)): ?>
				<div class="col-xs-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Autoridades</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body">
							<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
								<thead>
									<tr>
										<th>Tipo de autoridad</th>
										<th>CUIL</th>
										<th>Nombre</th>
										<th>Tél. Fijo</th>
										<th>Celular</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($autoridades as $autoridad): ?>
										<tr>
											<td><?= $autoridad->autoridad; ?></td>
											<td><?= $autoridad->cuil; ?></td>
											<td><?= $autoridad->persona; ?></td>
											<td><?= $autoridad->telefono_fijo; ?></td>
											<td><?= $autoridad->telefono_movil; ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</div>
<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<?php echo form_open(base_url("liquidaciones/bajas/cambiar_mes/$planilla_tipo->id/$mes/$escuela->id")); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Cambiar Mes</h4>
			</div>
			<div style="display:none;" id="div_servicio_baja"></div>

			<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-12" style="text-align:center;">
						<div id="datepicker" data-date="<?php echo (new DateTime("{$mes}01"))->format('d/m/Y'); ?>"></div>
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
		$("#datepicker").datepicker({
			format: "dd/mm/yyyy",
			startView: "months",
			minViewMode: "months",
			language: 'es',
			todayHighlight: false
		});
		$("#datepicker").on("changeDate", function(event) {
			$("#mes").val($("#datepicker").datepicker('getFormattedDate'))
		});
	});
</script>