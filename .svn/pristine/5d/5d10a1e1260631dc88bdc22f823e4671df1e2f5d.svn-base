<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Alumnos de <?= "$division->curso $division->division"; ?> - <label class="label label-default"><?php echo $ciclo_lectivo; ?></label>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id; ?>"><?= "Esc. $escuela->nombre_corto"; ?></a></li>
			<li><a href="division/listar/<?= $escuela->id ?>">Cursos y Divisiones</a></li>
			<li><a href="division/ver/<?= $division->id ?>"><?php echo "$division->curso $division->division"; ?></a></li>
			<li><a href="division/alumnos/<?php echo "$division->id/$ciclo_lectivo"; ?>">Alumnos</a></li>
			<li class="active">Agregar</li>
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
							<i class="fa fa-search" id="btn-ver"></i> Ver división
						</a>
						<a class="btn btn-app btn-app-zetta" href="division/alumnos/<?php echo "$division->id/$ciclo_lectivo"; ?>">
							<i class="fa fa-users" id="btn-alumnos"></i> Alumnos
						</a>
						<?php if ($edicion): ?>
							<a class="btn bg-blue btn-app btn-app-zetta" id="persona_buscar_listar" href="division/persona_buscar_listar_modal/<?php echo "$division->id/$ciclo_lectivo"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal">
								<i class="fa fa-plus" id="btn-agregar-alumno"></i> Agregar
							</a>
<!--							<a class="btn btn-app btn-app-zetta" href="division/mover_alumnos/<?php echo "$division->id/$ciclo_lectivo"; ?>">
								<i class="fa fa-users" id="btn-mover-alumnos"></i> Mover alumnos
							</a>
							<a class="btn btn-app btn-app-zetta" href="division/sacar_alumnos/<?php echo "$division->id/$ciclo_lectivo"; ?>">
								<i class="fa fa-users" id="btn-sacar-alumnos"></i> Retirar alumnos
							</a>-->
						<?php endif; ?>
<!--						<a class="btn btn-app btn-app-zetta pull-right" href="division/cargos/<?php echo $division->id; ?>">
							<i class="fa fa-users" id="btn-cargos"></i> Cargos
						</a>
						<a class="btn btn-app btn-app-zetta pull-right" href="division/ver_horario/<?php echo (!empty($division->id)) ? $division->id : ''; ?>">
							<i class="fa fa-clock-o" id="btn-horarios"></i> Horarios
						</a>-->
						<hr style="margin: 10px 0;">
						<h4>Verifique que los datos del alumno a incorporar al curso actual sean correctos.</h4>
						<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agregar_alumno')); ?>
						<div class="row">
							<div class="form-group col-sm-2">
								<?php echo $fields_alumno['documento_tipo']['label']; ?>
								<?php echo $fields_alumno['documento_tipo']['form']; ?>
								<?php echo isset($persona) ? form_hidden('documento_tipo', $persona->documento_tipo_id) : ''; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields_alumno['documento']['label']; ?>
								<span class="label label-danger" id="documento_existente"></span>
								<?php echo $fields_alumno['documento']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields_alumno['apellido']['label']; ?>
								<?php echo $fields_alumno['apellido']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields_alumno['nombre']['label']; ?>
								<?php echo $fields_alumno['nombre']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields_alumno['fecha_nacimiento']['label']; ?>
								<?php echo $fields_alumno['fecha_nacimiento']['form']; ?>
							</div>
						</div>
						<h4>Ingrese la fecha, causa de entrada, ciclo lectivo y condición:</h4>
						<div class="form-group col-sm-2">
							<?php echo $fields['condicion']['label']; ?>
							<?php echo $fields['condicion']['form']; ?>
						</div>
						<div class="row">
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_desde']['label']; ?>
								<div class="input-group date" id="datepicker-i">
									<?php echo $fields['fecha_desde']['form']; ?>
									<div class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</div>
								</div>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields_alumno['division']['label']; ?>
								<?php echo $fields_alumno['division']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['causa_entrada']['label']; ?>
								<?php echo $fields['causa_entrada']['form']; ?>
							</div>
							<input type="hidden" name="alumno_id" value="<?php echo empty($alumno) ? '' : $alumno->id; ?>">
							<input type="hidden" name="division_anterior" value="<?php echo empty($division_anterior) ? '' : $division_anterior; ?>">
							<input type="hidden" name="legajo" id="legajo">
							<?php echo isset($p_persona_id) ? form_hidden('p_persona_id', $p_persona_id) : ''; ?>
							<div class="form-group col-md-2">
								<label style="width:100%">Ciclo lectivo</label>
								<div class="input-group date" id="datepicker">
									<input type="text" class="form-control" name="ciclo_lectivo" required id="ciclo_lectivo" value="<?php echo $ciclo_lectivo; ?>"/>
									<div class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</div>
								</div>
							</div>
						</div>
						<label>&nbsp;</label><br/>
						<button class="btn btn-primary pull-left" type="submit" value="1" name="editar">Guardar y editar alumno</button>
						<button class="btn btn-primary pull-right" type="submit" value="2" name="editar">Guardar y continuar agregando</button>
						<?php echo form_close(); ?>
						<br>
						<hr>
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid" id="tbl_trayectoria">
							<thead>
								<tr style="background-color: #f4f4f4" >
									<th style="text-align: center;" colspan="9">Trayectoria alumno</th>
								</tr>
								<tr>
								<tr>
									<th>C. lectivo</th>
									<th>Escuela</th>
									<th>Division</th>
									<th>Legajo</th>
									<th>Desde</th>
									<th>Causa de entrada</th>
									<th>Hasta</th>
									<th>Causa de salida</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($trayectoria)): ?>
									<?php foreach ($trayectoria as $t): ?>
										<tr>
											<td><?php echo $t->ciclo_lectivo; ?></td>
											<td><?php echo "$t->numero_escuela $t->nombre_escuela"; ?></td>
											<td><?php echo "$t->curso $t->division"; ?></td>
											<td><?php echo $t->legajo; ?></td>
											<td><?php echo $t->fecha_desde; ?></td>
											<td><?php echo $t->causa_entrada; ?></td>
											<td><?php echo $t->fecha_hasta; ?></td>
											<td><?php echo $t->causa_salida; ?></td>
											<td><?php echo $t->estado; ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr><td colspan="9">-- No tiene --</td></tr>
								<?php endif; ?>
							</tbody>
						</table>
						<div style="display:none;" id="div_persona_buscar_listar"></div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="division/<?php echo empty($txt_btn) ? "alumnos/$division->id" : ""; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#datepicker').datepicker({
			format: "yyyy",
			startView: "years",
			minViewMode: "years",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true,
			endDate: '+0y'
		});
		$('#fecha_desde').datepicker({
			format: "dd/mm/yyyy",
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true
		})
		agregar_eventos($('#form_agregar_alumno'));
	});
</script>