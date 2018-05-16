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
	.inasistencia-1-5.active{
		color: blue;
	}
	.active{
		font-weight: bold;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Inasistencias alumnos <?= "$division->curso $division->division"; ?> - Día <?php echo (new DateTime($fecha))->format('d/m'); ?> - <label class="label label-default"><?php echo $ciclo_lectivo; ?></label>
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
			<li><a href="division_inasistencia/ver/<?php echo "$division_inasistencia->id/$orden"; ?>">Carga diaria</a></li>
			<li class="active">Editar día</li>
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
				<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_division_inasistencia_editar_dia', 'name' => 'form_division_inasistencia_editar_dia')); ?>
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
							<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="division_inasistencia/editar_dia/<?php echo "$dia->id/$orden"; ?>">
								<i class="fa fa-clock-o"></i> Asistencia Día
							</a>
							<?php if ($dia->contraturno === 'No'): ?>
								<?php $turnos = array('No'); ?>
							<?php elseif ($dia->contraturno === 'Parcial'): ?>
								<?php $turnos = array('No', 'Si'); ?>
							<?php else: ?>
								<?php $turnos = array('No', 'Si'); ?>
							<?php endif; ?>
							<?php if ($dia->inasistencia_actividad_id !== '1'): ?>
								<h4> <?php echo $dia->inasistencia_actividad; ?>
									- <a class="btn btn-xs btn-primary" href="javascript:seleccionar_todo()" title="Marcar todos">Fuerza mayor</a>
								</h4>
							<?php endif; ?>
							<?php foreach ($turnos as $turno): ?>
								<table id="inasistencia_table" class="table table-hover table-bordered table-condensed text-sm" role="grid" >
									<thead>
										<tr>
											<th colspan="4" class="text-center bg-gray"><?php echo $turno === 'No' ? 'Turno Principal' : 'Contraturno'; ?></th>
										</tr>
										<tr>
											<th style="">Documento</th>
											<th style="">Nombre</th>
											<th style="text-align: center;">Asistencia</th>
											<th style="text-align: center;">Justificada</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($alumnos[$turno] as $alumno): ?>
											<tr>
												<td><?php echo "$alumno->documento_tipo $alumno->documento"; ?></td>
												<td><?php echo "$alumno->persona"; ?></td>
												<td style="text-align: center;">
													<div class="btn-group btn-group-xs inasistencia" data-toggle="buttons">
														<?php if ($dia->contraturno === 'Parcial' && $turno === 'Si'): ?>
															<label class="btn btn-default inasistencia-0 <?php echo ($alumno->inasistencia_tipo === 'No corresponde' ) ? 'active' : ''; ?>">
																<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="9" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === 'No corresponde') ? 'checked' : ''; ?>/>(No corresponde)
															</label>
														<?php endif; ?>
														<?php if ($alumno->fecha_hasta && ((new DateTime($alumno->fecha_hasta))->format('Y-m-d')) <= $fecha): ?>
															<input type="hidden" name="alumno_inasistencia_ids[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="<?php echo $alumno->alumno_inasistencia_id; ?>"/>
															<label class="btn btn-default inasistencia-0 <?php echo ($alumno->inasistencia_tipo === NULL || $alumno->inasistencia_tipo === 'Posterior egreso' ) ? 'active' : ''; ?>">
																<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="6" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === NULL || $alumno->inasistencia_tipo === 'Posterior egreso') ? 'checked' : ''; ?>/> - Salido -
															</label>
														<?php elseif ($alumno->fecha_desde && ((new DateTime($alumno->fecha_desde))->format('Y-m-d')) > $fecha): ?>
															<input type="hidden" name="alumno_inasistencia_ids[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="<?php echo $alumno->alumno_inasistencia_id; ?>"/>
															<label class="btn btn-default inasistencia-0 <?php echo ($alumno->inasistencia_tipo === NULL || $alumno->inasistencia_tipo === 'Previo ingreso' ) ? 'active' : ''; ?>">
																<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="5" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === NULL || $alumno->inasistencia_tipo === 'Previo ingreso') ? 'checked' : ''; ?>/> - No inscripto -
															</label>
														<?php else: ?>
															<input type="hidden" name="alumno_inasistencia_ids[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="<?php echo $alumno->alumno_inasistencia_id; ?>"/>
															<label class="btn btn-default inasistencia-0 <?php echo ($alumno->inasistencia_tipo === NULL ) ? 'active' : ''; ?>">
																<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="0" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === NULL) ? 'checked' : ''; ?>/> Presente
															</label>
															<label class="btn btn-default inasistencia-0-5 text-success <?php echo ($alumno->inasistencia_tipo === 'Tardanza') ? 'active' : ''; ?>">
																<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="3" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === 'Tardanza') ? 'checked' : ''; ?>/> Tardanza
															</label>
															<label class="btn btn-default inasistencia-1 text-success <?php echo ($alumno->inasistencia_tipo === 'Inasistencia') ? 'active' : ''; ?>">
																<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="2" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === 'Inasistencia') ? 'checked' : ''; ?>/> Inasistencia
															</label>
															<label class="btn btn-default inasistencia-1 text-success <?php echo ($alumno->inasistencia_tipo === 'Presente NC') ? 'active' : ''; ?>">
																<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="8" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === 'Presente NC') ? 'checked' : ''; ?>/> Inasistencia por tardanza
															</label>
															<label class="btn btn-default inasistencia-0-5 text-success <?php echo ($alumno->inasistencia_tipo === 'Retira antes') ? 'active' : ''; ?>">
																<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="4" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === 'Retira antes' ) ? 'checked' : ''; ?>/> Retira antes
															</label>
															<label class="btn btn-default inasistencia-1-5 text-success <?php echo ($alumno->inasistencia_tipo === 'Fuerza mayor') ? 'active' : ''; ?>" id="inasistencia_fm_<?php echo "{$turno}_$alumno->id"; ?>">
																<input type="radio" name="inasistencia[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" value="7" id="inasistencia_fm_<?php echo "{$turno}_$alumno->id"; ?>" autocomplete="off" <?php echo ($alumno->inasistencia_tipo === 'Fuerza mayor') ? 'checked' : ''; ?>/> Fuerza mayor
															</label>
														<?php endif; ?>
													</div>
												</td>
												<td style="text-align: center;">
													<div class="btn-group btn-group-xs justificada <?php echo ($alumno->inasistencia_tipo === NULL || $alumno->inasistencia_tipo_id === '7' || $alumno->inasistencia_tipo_id === '8' || $alumno->inasistencia_tipo_id === '9') ? 'hidden' : ''; ?>" data-toggle="buttons">
														<?php if ($alumno->fecha_hasta && ((new DateTime($alumno->fecha_hasta))->format('Y-m-d')) <= $fecha): ?>
															<label class="btn btn-default justificada-0 <?php echo ($alumno->justificada === 'NC' || $alumno->justificada === null) ? 'active' : ''; ?>">
																<input type="radio" name="justificada[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" autocomplete="off" <?php echo ($alumno->justificada === 'NC' || $alumno->justificada === null) ? 'checked' : ''; ?> value="NC"/>-
															</label>
														<?php elseif ($alumno->fecha_desde && ((new DateTime($alumno->fecha_desde))->format('Y-m-d')) > $fecha): ?>
															<label class="btn btn-default justificada-0 <?php echo ($alumno->justificada === 'NC' || $alumno->justificada === null) ? 'active' : ''; ?>">
																<input type="radio" name="justificada[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" autocomplete="off" <?php echo ($alumno->justificada === 'NC' || $alumno->justificada === null) ? 'checked' : ''; ?> value="NC"/>-
															</label>
														<?php else: ?>
															<label class="btn btn-default justificada-0 <?php echo ($alumno->justificada === 'No' || $alumno->justificada === null) ? 'active' : ''; ?>">
																<input type="radio" name="justificada[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" autocomplete="off" <?php echo ($alumno->justificada === 'No' || $alumno->justificada === null) ? 'checked' : ''; ?> value="No"/> No
															</label>
															<label class="btn btn-default justificada-1 text-success <?php echo ($alumno->justificada === 'Si') ? 'active' : ''; ?>">
																<input type="radio" name="justificada[<?php echo $turno; ?>][<?php echo $alumno->id; ?>]" autocomplete="off" <?php echo ($alumno->justificada === 'Si') ? 'checked' : ''; ?> value="Si"/> Si
															</label>
														<?php endif; ?>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							<?php endforeach; ?>
					</div>
					<div class="box-footer">
						<input type="hidden" name="division_inasitencia_dia_id" value="<?php echo $dia->id; ?>"/>
						<a class="btn btn-default" href="division_inasistencia/<?php echo empty($txt_btn) ? "ver/$division_inasistencia->id/$orden" : ""; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Guardar'), 'Guardar'); ?>
						<button type="submit" style="margin-right:10px;" formaction="division_inasistencia/eliminar_dia/<?php echo $dia->id ?>" class="btn btn-danger pull-right">Eliminar asistencias del día </button>
					</div>
				</div>
				<?php form_close(); ?>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<?php echo form_open("division/cambiar_cl_alumnos/$division->id/$ciclo_lectivo"); ?>
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
<script>
	$(document).ready(function() {
		$('.inasistencia input[type="radio"').change(function() {
			if ($(this).val() === '0' || $(this).val() === '7' || $(this).val() === '8' || $(this).val() === '9') {
				$(this).closest('tr').find('.justificada').addClass('hidden');
			} else {
				$(this).closest('tr').find('.justificada').removeClass('hidden');
			}
		});
	});
	function seleccionar_todo() {
		for (i = 0; i < document.form_division_inasistencia_editar_dia.elements.length; i++) {
			if (document.form_division_inasistencia_editar_dia.elements[i].type == "radio" && document.form_division_inasistencia_editar_dia.elements[i].value == "7") {
				$('#' + document.form_division_inasistencia_editar_dia.elements[i].id).button('toggle')
			}
		}
	}
</script>
