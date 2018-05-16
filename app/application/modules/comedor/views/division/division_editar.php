<div class="content-wrapper">
	<section class="content-header">
		<h1 class="box-title">Comedor <label class="label label-default"><?php echo $mes_nombre; ?></label></h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="comedor/escuela/ver/<?php echo $comedor_presupuesto->id; ?>">Cursos y Divisiones</a></li>
			<?php if (!empty($division)): ?>
				<li><?php echo "$division->curso $division->division"; ?></li>
			<?php endif; ?>
			<li class="active"><?php echo ucfirst($metodo); ?></li>
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
						<a class="btn btn-app btn-app-zetta" href="escuela/escritorio/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-home"></i> Escritorio
						</a>
						<a class="btn btn-app btn-app-zetta" href="comedor/escuela/ver/<?php echo $comedor_presupuesto->id; ?>">
							<i class="fa fa-tasks"></i> Cursos y Divisiones
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="comedor/division/ver/<?php echo $comedor_presupuesto->id; ?>/<?php echo (!empty($division->id)) ? $division->id : ''; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="comedor/division/editar/<?php echo $comedor_presupuesto->id; ?>/<?php echo (!empty($division->id)) ? $division->id : ''; ?>">
							<i class="fa fa-edit"></i> Editar 
						</a>
						<hr style="margin: 10px 0;">
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields['curso']['label']; ?>
								<?php echo $fields['curso']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['division']['label']; ?>
								<?php echo $fields['division']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['turno']['label']; ?>
								<?php echo $fields['turno']['form']; ?>
							</div>
							<div class="form-group col-md-4">
								<?php echo $fields['carrera']['label']; ?>
								<?php echo $fields['carrera']['form']; ?>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields_racion_general['comedor_racion']['label']; ?>
								<?php echo $fields_racion_general['comedor_racion']['form']; ?>
							</div>
							<div class="col-md-5">
								<label style="width:100%">&nbsp;</label>
								<a class="btn btn-sm btn-success" href="javascript:cambiar_todo()" title="Cambiar todos">Cambiar todos</a>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="tab_alumnos">
							<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
								<thead>
									<tr style="background-color: #f4f4f4" >
										<th style="text-align: center" colspan="8">
											Alumnos
											<?php echo empty($cantidad_alumnos) ? '' : "($cantidad_alumnos)"; ?>
										</th>
									</tr>
									<tr>
										<th>Documento</th>
										<th>Persona</th>
										<th>F. Nacimiento</th>
										<th>Ración</th>
									</tr>
								</thead>
								<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agregar_racion', 'name' => 'form_agregar_racion')); ?>
								<tbody>
									<?php if (!empty($alumnos)): ?>
										<?php foreach ($alumnos as $alumno): ?>
											<tr>
												<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
												<td><?= $alumno->persona; ?></td>
												<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
												<td>
													<?php echo $fields_raciones["comedor_racion[$alumno->id]"]['form']; ?>
													<input type="hidden" name="alumno_division_id[<?php echo $alumno->id; ?>]" value="<?php echo $alumno->id; ?>" id="alumno_division_id"/>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<td colspan="7" style="text-align: center;">-- No tiene --</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="comedor/division/ver/<?php echo $comedor_presupuesto->id; ?>/<?php echo $division->id; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<button class="btn btn-primary pull-right" type="submit" id="guardar" title="Guardar">Guardar</button>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	function cambiar_todo() {
		var opcion = $('#comedor_racion').val();
		$(".racion").each(function() {
			$(this).val(opcion);
		});
	}
</script>
